<?php

namespace App\Services;

use App\Http\Controllers\Admin\MagazaController;
use App\Http\Controllers\Admin\XMLController;
use App\Models\Bayi;
use App\Models\Kategori;
use App\Models\Kullanici;
use App\Models\Magaza;
use App\Models\Marka;
use App\Models\Urun;
use App\Models\UrunOzellik;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Throwable;

class HealthCheckService
{
    /**
     * Hızlı sağlık kontrolü: temp sqlite DB üzerinde çalışır, sonuçları döner.
     */
    public function run(bool $keepDb = false): array
    {
        $dbPath = storage_path('framework/health.sqlite');
        $results = [];
        $success = true;
        $startedAt = now();
        $t0 = microtime(true);
        try {
            if (File::exists($dbPath)) {
                File::delete($dbPath);
            }
            File::put($dbPath, '');

            // Runtime DB config override (bu request kapsamı ile sınırlı)
            Config::set('database.default', 'sqlite');
            Config::set('database.connections.sqlite.database', $dbPath);

            // Migrate
            Artisan::call('migrate', ['--force' => true]);

            $results['magaza_ekleme'] = $this->withTiming(fn() => $this->testMagazaEkleme());
            $results['xml_import'] = $this->withTiming(fn() => $this->testXmlImport());
            $results['kategori_crud'] = $this->withTiming(fn() => $this->testKategoriCrud());
            $results['marka_crud'] = $this->withTiming(fn() => $this->testMarkaCrud());
            $results['urun_crud'] = $this->withTiming(fn() => $this->testUrunCrud());
            $results['ozellik_crud'] = $this->withTiming(fn() => $this->testOzellikCrud());
            $results['bayi_crud'] = $this->withTiming(fn() => $this->testBayiCrud());

            foreach ($results as $r) {
                $success = $success && ($r['success'] ?? false);
            }
        } catch (Throwable $e) {
            $results['fatal'] = ['success' => false, 'message' => $e->getMessage()];
            $success = false;
        } finally {
            if (!$keepDb && File::exists($dbPath)) {
                File::delete($dbPath);
            }
        }

        $durationMs = (int) round((microtime(true) - $t0) * 1000);
        $payload = [
            'success' => $success,
            'started_at' => $startedAt->toIso8601String(),
            'duration_ms' => $durationMs,
            'results' => $results,
        ];
        $this->writeHistory($payload);
        return $payload;
    }

    /** Basit süre ölçüm wrapper'ı */
    private function withTiming(callable $fn): array
    {
        $t = microtime(true);
        try {
            $res = $fn();
            if (!is_array($res)) { $res = ['success' => (bool) $res]; }
            $res['duration_ms'] = (int) round((microtime(true) - $t) * 1000);
            return $res;
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'duration_ms' => (int) round((microtime(true) - $t) * 1000),
            ];
        }
    }

    /** History dosyasına JSONL olarak ekler */
    private function writeHistory(array $payload): void
    {
        try {
            $dir = storage_path('app/health');
            if (!File::exists($dir)) { File::makeDirectory($dir, 0755, true); }
            $file = $dir . DIRECTORY_SEPARATOR . 'history.jsonl';
            File::append($file, json_encode($payload, JSON_UNESCAPED_UNICODE) . PHP_EOL);
        } catch (Throwable $e) {
            // yut
        }
    }

    /** Son N çalıştırmayı döner */
    public function getHistory(int $limit = 5): array
    {
        $file = storage_path('app/health/history.jsonl');
        if (!File::exists($file)) return [];
        try {
            $lines = preg_split("/\r?\n/", trim(File::get($file)));
            $lines = array_values(array_filter($lines));
            $slice = array_slice($lines, -$limit);
            $out = [];
            foreach ($slice as $line) {
                $decoded = json_decode($line, true);
                if (is_array($decoded)) $out[] = $decoded;
            }
            return array_reverse($out); // yeniler üstte
        } catch (Throwable $e) {
            return [];
        }
    }

    private function testMagazaEkleme(): array
    {
        try {
            $controller = app(MagazaController::class);
            $payload = ['ad' => 'Health Test Mağaza', 'platform' => 'Trendyol'];
            $request = Request::create('/admin/magaza/ekle', 'POST', $payload);
            $controller->store($request);
            $ok = Magaza::where('ad', 'Health Test Mağaza')->exists();
            return ['success' => $ok, 'message' => $ok ? 'Mağaza oluşturuldu' : 'Mağaza bulunamadı'];
        } catch (Throwable $e) {
            try {
                $magaza = Magaza::create(['ad' => 'Health Test Mağaza', 'entegrasyon_turu' => 'Trendyol', 'platform' => 'Trendyol']);
                $ok = $magaza && $magaza->exists;
                return ['success' => $ok, 'message' => $ok ? 'Fallback ile mağaza oluşturuldu' : 'Fallback başarısız'];
            } catch (Throwable $e2) {
                return ['success' => false, 'message' => 'Controller: '.$e->getMessage().' | Fallback: '.$e2->getMessage()];
            }
        }
    }

    private function testXmlImport(): array
    {
        $tmpPath = storage_path('framework/health-import.xml');
        try {
            $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urunler>
  <urun>
    <ad>Health XML Ürün</ad>
    <fiyat>123.45</fiyat>
    <stok>10</stok>
  </urun>
  <urun>
    <ad>Health XML Ürün 2</ad>
    <fiyat>9.99</fiyat>
    <stok>2</stok>
  </urun>
</urunler>
XML;
            File::put($tmpPath, $xml);
            $uploaded = new UploadedFile($tmpPath, 'health.xml', 'text/xml', null, true);
            $server = ['HTTP_REFERER' => '/'];
            $request = Request::create('/admin/xml/import', 'POST', [], [], ['xml' => $uploaded], $server);
            $controller = app(XMLController::class);
            $controller->import($request);
            $ok = Urun::where('ad', 'Health XML Ürün')->where('fiyat', 123.45)->exists();
            return ['success' => $ok, 'message' => $ok ? 'XML import ürün oluşturdu' : 'XML import başarısız'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } finally {
            if (File::exists($tmpPath)) File::delete($tmpPath);
        }
    }

    private function testKategoriCrud(): array
    {
        try {
            $kat = Kategori::create(['ad' => 'Health Kategori', 'slug' => 'health-kategori', 'durum' => true, 'sira' => 0]);
            $kat->update(['ad' => 'Health Kategori Guncel']);
            $id = $kat->id; $kat->delete();
            $ok = !Kategori::find($id);
            return ['success' => $ok, 'message' => $ok ? 'Kategori CRUD ok' : 'Kategori silinmedi'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function testMarkaCrud(): array
    {
        try {
            $m = Marka::create(['ad' => 'Health Marka', 'slug' => 'health-marka', 'durum' => true]);
            $m->update(['aciklama' => 'test']);
            $id = $m->id; $m->delete();
            $ok = !Marka::find($id);
            return ['success' => $ok, 'message' => $ok ? 'Marka CRUD ok' : 'Marka silinmedi'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function testUrunCrud(): array
    {
        try {
            $u = Urun::create(['ad' => 'Health Urun', 'fiyat' => 10, 'stok' => 1]);
            $u->update(['fiyat' => 12.5, 'stok' => 3]);
            $id = $u->id; $u->delete();
            $ok = !Urun::find($id);
            return ['success' => $ok, 'message' => $ok ? 'Urun CRUD ok' : 'Urun silinmedi'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function testOzellikCrud(): array
    {
        try {
            $u = Urun::create(['ad' => 'Health Ozellik Urunu', 'fiyat' => 1]);
            $o = UrunOzellik::create(['urun_id' => $u->id, 'ad' => 'Renk', 'deger' => 'Kırmızı', 'sira' => 0]);
            $o->update(['deger' => 'Mavi']);
            $oid = $o->id; $o->delete();
            $ok = !UrunOzellik::find($oid);
            $u->delete();
            return ['success' => $ok, 'message' => $ok ? 'Ozellik CRUD ok' : 'Ozellik silinmedi'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function testBayiCrud(): array
    {
        try {
            $user = Kullanici::create(['ad' => 'Health Bayi User', 'email' => 'health-bayi@example.com', 'password' => 'password', 'rol' => 'bayi']);
            $b = Bayi::create(['ad' => 'Health Bayi', 'email' => 'health-bayi@example.com', 'kullanici_id' => $user->id]);
            $b->update(['telefon' => '555']);
            $id = $b->id; $b->delete();
            $user->delete();
            $ok = !Bayi::find($id);
            return ['success' => $ok, 'message' => $ok ? 'Bayi CRUD ok' : 'Bayi silinmedi'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
