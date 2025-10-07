<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\SiteAyar;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AnasayfaController extends Controller
{
    public function index()
    {
        // Mevcut ayarları oku
        $ayarlar = SiteAyar::pluck('deger', 'anahtar')->toArray();

        // Seçim listeleri için yardımcı veriler
        $kategoriler = Kategori::orderBy('ad')->get(['id','ad']);
        $urunler = Urun::orderBy('ad')->limit(200)->get(['id','ad']);

        return view('admin.anasayfa.index', compact('ayarlar', 'kategoriler', 'urunler'));
    }

    public function guncelle(Request $request)
    {
        $validated = $request->validate([
            'anasayfa_hero_baslik' => 'nullable|string|max:255',
            'anasayfa_hero_altbaslik' => 'nullable|string|max:500',
            'anasayfa_hero_buton_yazi' => 'nullable|string|max:100',
            'anasayfa_hero_buton_link' => 'nullable|string|max:255',
            'anasayfa_duyuru' => 'nullable|string|max:500',
            'anasayfa_ust_banner_resmi' => 'nullable|string|max:1000',
            'anasayfa_alt_banner_resmi' => 'nullable|string|max:1000',
            'anasayfa_onecikan_kategoriler' => 'nullable|array',
            'anasayfa_onecikan_kategoriler.*' => 'integer|exists:kategoriler,id',
            'anasayfa_onecikan_urunler' => 'nullable|array',
            'anasayfa_onecikan_urunler.*' => 'integer|exists:urunler,id',
            // Theme fields
            'theme_header_aktif' => 'nullable|in:0,1',
            'theme_footer_aktif' => 'nullable|in:0,1',
            'theme_logo_position' => 'nullable|in:left,center,right',
            'theme_logo_max_h' => 'nullable|integer|min:20|max:120',
            'theme_logo_dosya' => 'nullable|file|mimes:png,svg|max:2048',
        ]);

        // Dizi olanları CSV olarak saklayalım (SiteAyar basit key-value yapıda)
        $map = $validated;
        if (isset($map['anasayfa_onecikan_kategoriler']) && is_array($map['anasayfa_onecikan_kategoriler'])) {
            $map['anasayfa_onecikan_kategoriler'] = implode(',', $map['anasayfa_onecikan_kategoriler']);
        }
        if (isset($map['anasayfa_onecikan_urunler']) && is_array($map['anasayfa_onecikan_urunler'])) {
            $map['anasayfa_onecikan_urunler'] = implode(',', $map['anasayfa_onecikan_urunler']);
        }

        // Logo yükleme ve otomatik ölçeklendirme
        if ($request->hasFile('theme_logo_dosya')) {
            $file = $request->file('theme_logo_dosya');
            $maxH = (int)($validated['theme_logo_max_h'] ?? 40);
            $disk = Storage::disk('public');
            $path = 'theme/logo';
            $disk->makeDirectory($path);

            if (strtolower($file->getClientOriginalExtension()) === 'svg') {
                $stored = $file->storeAs($path, 'logo.svg', 'public');
                $publicUrl = $disk->url($path . '/logo.svg');
            } else {
                // PNG - otomatik ölçeklendir (GD varsa); yoksa orijinal kopyala
                $filename = 'logo.png';
                $fullPath = $disk->path($path . '/' . $filename);
                try {
                    if (function_exists('imagecreatetruecolor')) {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($file->getRealPath());
                        $image->scale(height: $maxH);
                        $image->toPng()->save($fullPath);
                    } else {
                        // GD yoksa doğrudan sakla (orijinal boyutta)
                        $file->storeAs($path, $filename, 'public');
                    }
                } catch (\Throwable $e) {
                    // Her ihtimale karşı fallback: orijinal dosyayı kopyala
                    $file->storeAs($path, $filename, 'public');
                }
                $publicUrl = $disk->url($path . '/' . $filename);
            }

            // Kaydet
            SiteAyar::updateOrCreate(
                ['anahtar' => 'theme_logo_url'],
                ['deger' => $publicUrl, 'grup' => 'theme', 'tip' => 'text']
            );
        }

        foreach ($map as $anahtar => $deger) {
            $grup = str_starts_with($anahtar, 'anasayfa_') ? 'anasayfa' : (str_starts_with($anahtar, 'theme_') ? 'theme' : 'genel');
            SiteAyar::updateOrCreate(
                ['anahtar' => $anahtar],
                ['deger' => $deger, 'grup' => $grup, 'tip' => 'text']
            );
        }

        return back()->with('success', 'Anasayfa ayarları güncellendi.');
    }
}
