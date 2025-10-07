<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAyar;
use Illuminate\Http\Request;

class VitrinController extends Controller
{
    public function index()
    {
        $ayarlar = SiteAyar::pluck('deger', 'anahtar')->toArray();
        $stats = [
            'ziyaret_sayisi' => (int)($ayarlar['vitrin_ziyaret_sayisi'] ?? 1247),
            'magaza_yonlendirme' => (int)($ayarlar['vitrin_magaza_yonlendirme'] ?? 892),
            'donusum_orani' => (float)($ayarlar['vitrin_donusum_orani'] ?? 71.6),
            'populerlik_skoru' => (int)($ayarlar['vitrin_populerlik_skoru'] ?? 94),
        ];

        return view('admin.vitrin.index', compact('ayarlar', 'stats'));
    }

    public function guncelle(Request $request)
    {
        // Basitçe tüm gelen alanları key-value olarak SiteAyar'a yazalım
        $fields = [
            // Hero
            'hero_baslik', 'hero_alt_baslik', 'hero_cta_text', 'hero_cta_link',
            // Özellikler
            'ozellik_1_baslik','ozellik_1_icon','ozellik_1_aciklama',
            'ozellik_2_baslik','ozellik_2_icon','ozellik_2_aciklama',
            'ozellik_3_baslik','ozellik_3_icon','ozellik_3_aciklama',
        ];

        foreach ($fields as $key) {
            if ($request->has($key)) {
                SiteAyar::updateOrCreate(
                    ['anahtar' => $key],
                    ['deger' => (string)$request->input($key), 'grup' => 'vitrin', 'tip' => 'text']
                );
            }
        }

        return back()->with('success', 'Vitrin ayarları güncellendi.');
    }
}
