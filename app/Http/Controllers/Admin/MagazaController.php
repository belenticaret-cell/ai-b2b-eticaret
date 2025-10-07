<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magaza;
use App\Models\Urun;
use App\Services\PlatformEntegrasyonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MagazaController extends Controller
{
    protected $platformService;

    public function __construct(PlatformEntegrasyonService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function index(Request $request)
    {
        $query = Magaza::query();

        // Arama filtresi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ad', 'like', "%{$search}%")
                  ->orWhere('platform', 'like', "%{$search}%");
            });
        }

        // Platform filtresi
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        // Durum filtresi
        if ($request->filled('durum')) {
            $query->where('aktif', $request->durum === 'aktif');
        }

        $magazalar = $query->latest('id')->paginate(15)->withQueryString();

        // Her mağaza için ürün sayısı ve istatistikler
        $magazaIstatistik = [];
        foreach ($magazalar as $magaza) {
            $urunSayisi = DB::table('magaza_urun')->where('magaza_id', $magaza->id)->count();
            $sonSenkron = $magaza->son_senkron_tarihi ?? 'Hiç';
            
            $magazaIstatistik[$magaza->id] = [
                'urun_sayisi' => $urunSayisi,
                'son_senkron' => $sonSenkron,
                'durum' => $magaza->aktif ? 'Aktif' : 'Pasif',
                'senkron_status' => $this->getSenkronStatus($magaza)
            ];
        }

        // Platform istatistikleri
        $platformStats = [
            'toplam_magaza' => Magaza::count(),
            'aktif_magaza' => Magaza::where('aktif', true)->count(),
            'trendyol' => Magaza::where('platform', 'Trendyol')->count(),
            'hepsiburada' => Magaza::where('platform', 'Hepsiburada')->count(),
            'n11' => Magaza::where('platform', 'N11')->count(),
        ];

        $platformlar = ['Trendyol', 'Hepsiburada', 'N11', 'Amazon', 'Pazarama', 'GittiGidiyor'];

        return view('admin.magaza.index', compact(
            'magazalar', 
            'magazaIstatistik', 
            'platformStats',
            'platformlar'
        ));
    }

    public function create()
    {
        $platformlar = [
            'Trendyol' => ['api_url' => 'https://api.trendyol.com', 'test_mode' => true],
            'Hepsiburada' => ['api_url' => 'https://api.hepsiburada.com', 'test_mode' => true],
            'N11' => ['api_url' => 'https://api.n11.com', 'test_mode' => true],
            'Amazon' => ['api_url' => 'https://api.amazon.com', 'test_mode' => true],
            'Pazarama' => ['api_url' => 'https://api.pazarama.com', 'test_mode' => true],
            'GittiGidiyor' => ['api_url' => 'https://api.gittigidiyor.com', 'test_mode' => true],
        ];

        return view('admin.magaza.create', compact('platformlar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'platform' => ['required','string','max:100'],
            'api_anahtari' => ['nullable','string','max:255'],
            'api_gizli_anahtari' => ['nullable','string','max:255'],
            'api_url' => ['nullable','url','max:255'],
            'magaza_id' => ['nullable','string','max:100'],
            'komisyon_orani' => ['nullable','numeric','min:0','max:100'],
            'auto_senkron' => ['boolean'],
            'aktif' => ['boolean'],
            // test_mode için geniş kabul: farklı doğruluk ifadelerini kabul ediyoruz
            'test_mode' => ['sometimes'],
            'aciklama' => ['nullable','string','max:500'],
        ]);

        $data['aktif'] = $request->has('aktif');
        $data['auto_senkron'] = $request->has('auto_senkron');
        // test_mode değerini güvenle bool'a çevir (true,false,1,0,on,off,yes,no)
        $data['test_mode'] = filter_var($request->input('test_mode', false), FILTER_VALIDATE_BOOLEAN);
        $data['son_senkron_tarihi'] = null;

        // Hepsiburada için api_url'den merchant id (magaza_id) çıkarımı
        if (strtolower($data['platform']) === 'hepsiburada' && empty($data['magaza_id']) && !empty($data['api_url'])) {
            if (preg_match('#/listings/([^/]+)#i', $data['api_url'], $m)) {
                $data['magaza_id'] = $m[1];
            }
        }

        $magaza = Magaza::create($data);

        // API bağlantısını test et
        if ($request->has('test_connection')) {
            $testResult = $this->platformService->testConnection($magaza->platform, [
                'api_key' => $magaza->api_anahtari,
                'api_secret' => $magaza->api_gizli_anahtari,
                'api_url' => $magaza->api_url,
                'magaza_id' => $magaza->magaza_id,
                'test_mode' => $magaza->test_mode,
            ]);
            if ($testResult['success']) {
                $magaza->update(['son_baglanti_testi' => now()]);
                return redirect()->route('admin.magaza.index')
                    ->with('success', "✅ Mağaza eklendi ve API bağlantısı başarılı!");
            } else {
                return redirect()->route('admin.magaza.index')
                    ->with('warning', "⚠️ Mağaza eklendi ancak API bağlantısında sorun var: " . $testResult['message']);
            }
        }

        return redirect()->route('admin.magaza.index')->with('success', '✅ Mağaza başarıyla eklendi!');
    }

    public function show(Magaza $magaza)
    {
        // Mağazaya ait ürünler
        $urunler = DB::table('magaza_urun')
            ->join('urunler', 'urunler.id', '=', 'magaza_urun.urun_id')
            ->where('magaza_urun.magaza_id', $magaza->id)
            ->select('urunler.*')
            ->paginate(20);

        // Uzak katalogdan çekilmiş ürünler (eşleşmiş/ eşleşmemiş)
        $platformUrunleri = $magaza->platformUrunleri()
            ->orderByDesc('updated_at')
            ->paginate(15, ['*'], 'platform_sayfa');

        // Senkronizasyon logları (mock data)
        $senkronLoglar = collect([
            [
                'tarih' => now()->subHours(2),
                'islem' => 'Ürün senkronizasyonu',
                'sonuc' => 'Başarılı',
                'detay' => '25 ürün güncellendi',
                'durum' => 'success'
            ],
            [
                'tarih' => now()->subHours(6),
                'islem' => 'Stok güncelleme',
                'sonuc' => 'Başarılı',
                'detay' => '142 ürün stoku güncellendi',
                'durum' => 'success'
            ],
            [
                'tarih' => now()->subHours(12),
                'islem' => 'Fiyat senkronizasyonu',
                'sonuc' => 'Hata',
                'detay' => 'API limiti aşıldı',
                'durum' => 'error'
            ],
        ]);

        // Performans metrikleri
        $performans = [
            'toplam_urun' => DB::table('magaza_urun')->where('magaza_id', $magaza->id)->count(),
            'aktif_urun' => DB::table('magaza_urun')
                ->join('urunler', 'urunler.id', '=', 'magaza_urun.urun_id')
                ->where('magaza_urun.magaza_id', $magaza->id)
                ->where('urunler.aktif', true)
                ->count(),
            'son_senkron' => $magaza->son_senkron_tarihi ? $magaza->son_senkron_tarihi->diffForHumans() : 'Hiç',
            'api_durumu' => $this->getApiDurumu($magaza),
        ];

        return view('admin.magaza.show', compact('magaza', 'urunler', 'senkronLoglar', 'performans', 'platformUrunleri'));
    }

    public function edit(Magaza $magaza)
    {
        $platformlar = [
            'Trendyol' => ['api_url' => 'https://api.trendyol.com', 'test_mode' => true],
            'Hepsiburada' => ['api_url' => 'https://api.hepsiburada.com', 'test_mode' => true],
            'N11' => ['api_url' => 'https://api.n11.com', 'test_mode' => true],
            'Amazon' => ['api_url' => 'https://api.amazon.com', 'test_mode' => true],
            'Pazarama' => ['api_url' => 'https://api.pazarama.com', 'test_mode' => true],
            'GittiGidiyor' => ['api_url' => 'https://api.gittigidiyor.com', 'test_mode' => true],
        ];

        return view('admin.magaza.edit', compact('magaza', 'platformlar'));
    }

    public function update(Request $request, Magaza $magaza)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'platform' => ['required','string','max:100'],
            'api_anahtari' => ['nullable','string','max:255'],
            'api_gizli_anahtari' => ['nullable','string','max:255'],
            'api_url' => ['nullable','url','max:255'],
            'magaza_id' => ['nullable','string','max:100'],
            'komisyon_orani' => ['nullable','numeric','min:0','max:100'],
            'auto_senkron' => ['boolean'],
            'aktif' => ['boolean'],
            'test_mode' => ['sometimes'],
            'aciklama' => ['nullable','string','max:500'],
        ]);

        $data['aktif'] = $request->has('aktif');
        $data['auto_senkron'] = $request->has('auto_senkron');
        $data['test_mode'] = filter_var($request->input('test_mode', $magaza->test_mode), FILTER_VALIDATE_BOOLEAN);

        // Hepsiburada için api_url'den merchant id çıkarımı (magaza_id boşsa)
        if (strtolower($data['platform']) === 'hepsiburada' && empty($data['magaza_id']) && !empty($data['api_url'])) {
            if (preg_match('#/listings/([^/]+)#i', $data['api_url'], $m)) {
                $data['magaza_id'] = $m[1];
            }
        }

        $magaza->update($data);

        return redirect()->route('admin.magaza.index')->with('success', '✅ Mağaza başarıyla güncellendi!');
    }

    public function destroy(Magaza $magaza)
    {
        // Önce ürün eşleştirmelerini sil
        DB::table('magaza_urun')->where('magaza_id', $magaza->id)->delete();
        
        $magaza->delete();
        
        return redirect()->route('admin.magaza.index')->with('success', '✅ Mağaza başarıyla silindi!');
    }

    // API Bağlantı Testi
    public function testConnection(Magaza $magaza)
    {
        try {
            // Platform service üzerinden test
            $result = $this->platformService->testConnection($magaza->platform, [
                'api_key' => $magaza->api_anahtari,
                'api_secret' => $magaza->api_gizli_anahtari,
                'api_url' => $magaza->api_url,
                'magaza_id' => $magaza->magaza_id,
                'test_mode' => $magaza->test_mode,
            ]);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bağlantı testi sırasında hata: ' . $e->getMessage()
            ]);
        }
    }

    // Ürün Senkronizasyonu
    public function senkronize(Request $request, Magaza $magaza)
    {
        try {
            $islemTuru = $request->get('islem', 'urun'); // urun, stok, fiyat
            $queue = filter_var($request->get('queue', false), FILTER_VALIDATE_BOOLEAN);
            
            if ($queue) {
                // Kuyrukta çalıştır
                switch ($islemTuru) {
                    case 'stok':
                        \App\Jobs\HbSyncStokJob::dispatch($magaza->id);
                        break;
                    case 'fiyat':
                        \App\Jobs\HbSyncFiyatJob::dispatch($magaza->id);
                        break;
                    default:
                        // Ürün senkronu: eşleşmiş her ürün için job
                        $ids = $magaza->urunler()->pluck('urun_id');
                        foreach ($ids as $uid) {
                            \App\Jobs\HbSyncUrunJob::dispatch($magaza->id, $uid)->onQueue('hb');
                        }
                        break;
                }
                $magaza->update(['son_senkron_tarihi' => now()]);
                return $request->expectsJson()
                    ? response()->json(['success' => true, 'message' => 'İşlem kuyruğa alındı.'])
                    : back()->with('success', 'İşlem kuyruğa alındı.');
            }

            $result = $this->platformService->senkronize($magaza, $islemTuru);
            
            // Son senkron tarihini güncelle
            $magaza->update(['son_senkron_tarihi' => now()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => $result['success'],
                    'message' => $result['message'],
                    'data' => $result['data'] ?? null,
                ]);
            }

            return back()->with($result['success'] ? 'success' : 'error', ($result['success'] ? '✅ ' : '❌ ') . $result['message']);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Senkronizasyon sırasında hata: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', '❌ Senkronizasyon sırasında hata: ' . $e->getMessage());
        }
    }

    // Toplu İşlemler
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,sync_all,test_all',
            'magaza_ids' => 'required|array',
            'magaza_ids.*' => 'exists:magazalar,id',
        ]);

        $magazaIds = $request->magaza_ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                Magaza::whereIn('id', $magazaIds)->update(['aktif' => true]);
                return back()->with('success', '✅ Seçili mağazalar aktifleştirildi!');

            case 'deactivate':
                Magaza::whereIn('id', $magazaIds)->update(['aktif' => false]);
                return back()->with('success', '✅ Seçili mağazalar pasifleştirildi!');

            case 'sync_all':
                $başarılı = 0;
                $hatalı = 0;
                
                foreach ($magazaIds as $id) {
                    $magaza = Magaza::find($id);
                    try {
                        $result = $this->platformService->senkronize($magaza, 'urun');
                        if ($result['success']) {
                            $başarılı++;
                            $magaza->update(['son_senkron_tarihi' => now()]);
                        } else {
                            $hatalı++;
                        }
                    } catch (\Exception $e) {
                        $hatalı++;
                    }
                }
                
                return back()->with('success', "✅ Senkronizasyon tamamlandı: {$başarılı} başarılı, {$hatalı} hatalı");

            case 'test_all':
                $başarılı = 0;
                $hatalı = 0;
                
                foreach ($magazaIds as $id) {
                    $magaza = Magaza::find($id);
                    $result = $this->platformService->testConnection($magaza->platform, [
                        'api_key' => $magaza->api_anahtari,
                        'api_secret' => $magaza->api_gizli_anahtari,
                        'api_url' => $magaza->api_url,
                        'test_mode' => $magaza->test_mode,
                    ]);
                    if ($result['success']) {
                        $başarılı++;
                        $magaza->update(['son_baglanti_testi' => now()]);
                    } else {
                        $hatalı++;
                    }
                }
                
                return back()->with('success', "✅ Bağlantı testleri tamamlandı: {$başarılı} başarılı, {$hatalı} hatalı");
        }

        return back()->with('error', '❌ İşlem gerçekleştirilemedi!');
    }

    // Uzak katalog çekme
    public function uzakKatalogCek(Magaza $magaza)
    {
        try {
            \Log::info('Katalog çekme başlatıldı', [
                'magaza_id' => $magaza->id,
                'platform' => $magaza->platform,
                'user_id' => auth()->id()
            ]);
            
            // Platform-specific ön kontroller
            if (strtolower($magaza->platform) === 'hepsiburada') {
                if (empty($magaza->magaza_id)) {
                    return back()->with('error', '❌ Hepsiburada katalog çekilemedi: Mağaza kimliği (magaza_id) tanımlı değil. Lütfen mağaza düzenle sayfasından doldurun.');
                }
                if (empty($magaza->api_anahtari) || empty($magaza->api_gizli_anahtari)) {
                    return back()->with('error', '🔐 Hepsiburada katalog çekilemedi: API anahtarı/gizli anahtar tanımlı değil.');
                }
            } elseif (strtolower($magaza->platform) === 'trendyol') {
                if (empty($magaza->magaza_id)) {
                    return back()->with('error', '❌ Trendyol katalog çekilemedi: Mağaza kimliği (supplierId) tanımlı değil.');
                }
                if (empty($magaza->api_anahtari) || empty($magaza->api_gizli_anahtari)) {
                    return back()->with('error', '🔐 Trendyol katalog çekilemedi: API Key/Secret tanımlı değil.');
                }
            }
            
            $startTime = microtime(true);
            $res = $this->platformService->uzakKatalogCekVeKaydet($magaza);
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            \Log::info('Katalog çekme tamamlandı', [
                'magaza_id' => $magaza->id,
                'success' => $res['success'],
                'duration_ms' => $duration,
                'result' => $res
            ]);
            
            $msg = $res['message'];
            
            // Correlation ID'yi yakala ve göster
            $correlationId = $res['correlation_id'] ?? null;
            
            // Detaylı hata bilgilerini ekle
            if (!$res['success']) {
                // Error type'ı varsa göster
                if (isset($res['error_type'])) {
                    $msg .= "\n\n⚠️ Hata Tipi: " . $res['error_type'];
                }
                
                // Error code'u varsa göster
                if (isset($res['error_code'])) {
                    $msg .= "\n🚨 HTTP Kodu: " . $res['error_code'];
                }
                
                // Details varsa göster
                if (isset($res['data']['details'])) {
                    $details = $res['data']['details'];
                    if (!empty($details)) {
                        $firstDetail = is_array($details) ? $details[0] : $details;
                        if (is_string($firstDetail)) {
                            $msg .= "\n\n📋 Detay: " . $firstDetail;
                        }
                    }
                }
                
                // Retry bilgileri varsa göster
                if (isset($res['retry_info'])) {
                    $retryInfo = $res['retry_info'];
                    if (isset($retryInfo['retry_after_seconds'])) {
                        $msg .= "\n\n⏱️ Tekrar deneme süresi: " . $retryInfo['retry_after_seconds'] . " saniye";
                    }
                    if (isset($retryInfo['max_retries'])) {
                        $msg .= "\n🔄 Maksimum deneme: " . $retryInfo['max_retries'];
                    }
                }
                
                // Correlation ID varsa göster
                if ($correlationId) {
                    $msg .= "\n\n🔗 Correlation ID: " . $correlationId;
                    $msg .= "\n💡 Bu ID'yi destek taleplerinizde paylaşın.";
                }
                
                // Platform-specific çözüm önerileri
                if (strtolower($magaza->platform) === 'trendyol') {
                    if (isset($res['error_code']) && $res['error_code'] == 403) {
                        $msg .= "\n\n�️ Çözüm Önerileri:";
                        $msg .= "\n• IP adresinizi Trendyol'a whitelist ettirin";
                        $msg .= "\n• Güvenilir proxy/VPN kullanın";
                        $msg .= "\n• API credentials'ları kontrol edin";
                    } elseif (isset($res['error_code']) && $res['error_code'] == 429) {
                        $msg .= "\n\n🛠️ Rate Limit Çözümü:";
                        $msg .= "\n• Biraz bekleyip tekrar deneyin";
                        $msg .= "\n• API kullanım limitinizi kontrol edin";
                    }
                }
            } else {
                // Başarılı durumda da correlation ID göster
                if ($correlationId) {
                    $msg .= "\n\n🔗 İşlem ID: " . $correlationId;
                }
            }
            
            return back()->with($res['success'] ? 'success' : 'error', $msg);
            
        } catch (\Exception $e) {
            \Log::error('Katalog çekme exception', [
                'magaza_id' => $magaza->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', '💥 Katalog çekme hatası: '.$e->getMessage() . "\n\nLütfen log dosyalarını kontrol edin.");
        }
    }

    // Platform ürünü yerel ürünle eşleştir
    public function urunEsle(Request $request, Magaza $magaza)
    {
        $data = $request->validate([
            'platform_urun_id' => ['required','integer','exists:magaza_platform_urunleri,id'],
            'urun_id' => ['required','integer','exists:urunler,id'],
        ]);

        $kayit = \App\Models\MagazaPlatformUrunu::where('magaza_id', $magaza->id)
            ->where('id', $data['platform_urun_id'])
            ->firstOrFail();
        $kayit->urun_id = $data['urun_id'];
        $kayit->save();

        // Pivotu bağla (detach etmeden)
        $magaza->urunler()->syncWithoutDetaching([
            $data['urun_id'] => [
                'platform_urun_id' => $kayit->platform_urun_id,
                'platform_sku' => $kayit->platform_sku,
                'senkron_durum' => 'eslendi'
            ]
        ]);

        return back()->with('success', 'Platform ürünü yerel ürünle eşleştirildi.');
    }

    // Tek bir eşleşmiş ürünü platforma gönder
    public function urunGonder(Request $request, Magaza $magaza)
    {
        $data = $request->validate([
            'platform_urun_id' => ['required','integer','exists:magaza_platform_urunleri,id'],
        ]);

        $kayit = \App\Models\MagazaPlatformUrunu::where('magaza_id', $magaza->id)
            ->where('id', $data['platform_urun_id'])
            ->firstOrFail();

        if (!$kayit->urun_id) {
            return back()->with('error', 'Önce yerel ürünle eşleştirmeniz gerekiyor.');
        }

        $res = $this->platformService->urunleriSenkronize($magaza, [$kayit->urun_id]);

        return back()->with($res['error_count'] === 0 ? 'success' : 'error',
            ($res['error_count'] === 0 ? '✅ ' : '❌ ') . 'Ürün gönderimi tamamlandı: '
            . ($res['success_count'] ?? 0) . ' başarılı, ' . ($res['error_count'] ?? 0) . ' hatalı');
    }

    // Yardımcı metodlar
    private function getSenkronStatus($magaza)
    {
        if (!$magaza->son_senkron_tarihi) {
            return ['status' => 'never', 'class' => 'bg-gray-100 text-gray-800', 'text' => 'Hiç'];
        }

        $diff = now()->diffInHours($magaza->son_senkron_tarihi);
        
        if ($diff < 1) {
            return ['status' => 'recent', 'class' => 'bg-green-100 text-green-800', 'text' => 'Güncel'];
        } elseif ($diff < 24) {
            return ['status' => 'old', 'class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Eski'];
        } else {
            return ['status' => 'very_old', 'class' => 'bg-red-100 text-red-800', 'text' => 'Çok Eski'];
        }
    }

    private function getApiDurumu($magaza)
    {
        // Mock API durumu kontrolü
        $statuses = ['online', 'offline', 'limited'];
        return $statuses[array_rand($statuses)];
    }
}
 
