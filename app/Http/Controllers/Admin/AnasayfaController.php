<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\SiteAyar;
use App\Models\Urun;
use Illuminate\Http\Request;

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
        ]);

        // Dizi olanları CSV olarak saklayalım (SiteAyar basit key-value yapıda)
        $map = $validated;
        if (isset($map['anasayfa_onecikan_kategoriler']) && is_array($map['anasayfa_onecikan_kategoriler'])) {
            $map['anasayfa_onecikan_kategoriler'] = implode(',', $map['anasayfa_onecikan_kategoriler']);
        }
        if (isset($map['anasayfa_onecikan_urunler']) && is_array($map['anasayfa_onecikan_urunler'])) {
            $map['anasayfa_onecikan_urunler'] = implode(',', $map['anasayfa_onecikan_urunler']);
        }

        foreach ($map as $anahtar => $deger) {
            SiteAyar::updateOrCreate(
                ['anahtar' => $anahtar],
                ['deger' => $deger, 'grup' => str_starts_with($anahtar, 'anasayfa_') ? 'anasayfa' : 'genel', 'tip' => 'text']
            );
        }

        return back()->with('success', 'Anasayfa ayarları güncellendi.');
    }
}
