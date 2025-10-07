<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAyar;
use App\Models\Kategori;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteAyarController extends Controller
{
    public function index()
    {
        // Mevcut site ayarlarını al
        $ayarlar = SiteAyar::pluck('deger', 'anahtar')->toArray();
        
        // Kategoriler (satışa açılabilir)
        $kategoriler = Kategori::withCount('urunler')->orderBy('ad')->get();
        
        // İstatistikler
        $stats = [
            'toplam_urun' => Urun::count(),
            'aktif_urun' => Urun::where('durum', true)->count(),
            'kategori_sayisi' => Kategori::count(),
            'satisa_acik_kategori' => $this->getSatisaAcikKategoriler()->count(),
        ];
        
        return view('admin.site-ayar.index', compact('ayarlar', 'kategoriler', 'stats'));
    }
    
    public function guncelle(Request $request)
    {
        $request->validate([
            'ayarlar' => 'required|array',
            'ayarlar.*' => 'nullable|string|max:1000',
        ]);
        
        foreach ($request->ayarlar as $anahtar => $deger) {
            SiteAyar::updateOrCreate(
                ['anahtar' => $anahtar],
                ['deger' => $deger]
            );
        }
        
        return back()->with('success', 'Site ayarları başarıyla güncellendi.');
    }
    
    public function yeniAyar(Request $request)
    {
        $request->validate([
            'anahtar' => 'required|string|max:255|unique:site_ayarlari,anahtar',
            'deger' => 'required|string|max:1000',
            'tip' => 'required|in:text,email,url,number,textarea,image',
            'grup' => 'required|string|max:100',
        ]);
        
        SiteAyar::create($request->all());
        
        return back()->with('success', 'Yeni ayar başarıyla eklendi.');
    }
    
    public function sil($id)
    {
        $ayar = SiteAyar::findOrFail($id);
        $ayar->delete();
        
        return back()->with('success', 'Ayar başarıyla silindi.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_adi' => 'required|string|max:255',
            'site_aciklama' => 'nullable|string|max:500',
            'site_aktif' => 'required|in:0,1',
            'satisa_acik_kategoriler' => 'nullable|array',
            'satisa_acik_kategoriler.*' => 'exists:kategoriler,id',
        ]);

        DB::transaction(function () use ($request) {
            $ayarlar = [
                'site_adi' => $request->site_adi,
                'site_aciklama' => $request->site_aciklama,
                'site_aktif' => $request->site_aktif,
                'satisa_acik_kategoriler' => $request->satisa_acik_kategoriler ? implode(',', $request->satisa_acik_kategoriler) : '',
                'guncelleme_tarihi' => now()->toDateTimeString(),
            ];

            foreach ($ayarlar as $anahtar => $deger) {
                SiteAyar::updateOrCreate(
                    ['anahtar' => $anahtar],
                    ['deger' => $deger ?? '']
                );
            }

            // Kategori durumlarını güncelle
            if ($request->site_aktif === '1' && !empty($request->satisa_acik_kategoriler)) {
                $this->updateKategoriDurumları($request->satisa_acik_kategoriler);
            } elseif ($request->site_aktif === '0') {
                // Site pasifse tüm ürünleri pasif yap
                Urun::query()->update(['durum' => false]);
            }
        });

        return redirect()->route('admin.site-ayar.index')
                        ->with('success', '🎉 Site ayarları başarıyla güncellendi!');
    }

    public function toggleSite(Request $request)
    {
        $durum = $request->input('durum', '0');
        
        SiteAyar::updateOrCreate(
            ['anahtar' => 'site_aktif'],
            ['deger' => $durum]
        );

        if ($durum === '0') {
            // Site pasifse tüm ürünleri pasif yap
            Urun::query()->update(['durum' => false]);
            $mesaj = '🔴 E-ticaret sitesi pasif hale getirildi';
        } else {
            $mesaj = '🟢 E-ticaret sitesi aktif hale getirildi';
        }

        return response()->json([
            'success' => true,
            'message' => $mesaj,
            'durum' => $durum
        ]);
    }

    private function getSatisaAcikKategoriler()
    {
        $kategorilerStr = SiteAyar::where('anahtar', 'satisa_acik_kategoriler')->value('deger') ?? '';
        if (empty($kategorilerStr)) {
            return collect();
        }
        
        $ids = array_filter(explode(',', $kategorilerStr));
        return Kategori::whereIn('id', $ids)->get();
    }

    private function updateKategoriDurumları($secilenKategoriler)
    {
        // Önce tüm ürünleri pasif yap
        Urun::query()->update(['durum' => false]);
        
        // Seçilen kategorilerdeki ürünleri aktif yap
        Urun::whereIn('kategori_id', $secilenKategoriler)
            ->update(['durum' => true]);
            
        // Alt kategorilerdeki ürünleri de aktif yap
        $altKategoriler = Kategori::whereIn('parent_id', $secilenKategoriler)->pluck('id')->toArray();
        if (!empty($altKategoriler)) {
            Urun::whereIn('kategori_id', $altKategoriler)
                ->update(['durum' => true]);
        }
    }
}
