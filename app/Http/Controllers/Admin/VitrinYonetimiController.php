<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAyar;
use Illuminate\Http\Request;

class VitrinYonetimiController extends Controller
{
    public function index()
    {
        // Vitrin ayarlarını al
        $ayarlar = SiteAyar::pluck('deger', 'anahtar')->toArray();
        
        // İstatistikler (demo data)
        $stats = [
            'ziyaret_sayisi' => 1247,
            'magaza_yonlendirme' => 892,
            'donusum_orani' => 71.6,
            'populerlik_skoru' => 94,
        ];
        
        return view('admin.vitrin.index', compact('ayarlar', 'stats'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'hero_baslik' => 'required|string|max:255',
            'hero_alt_baslik' => 'nullable|string|max:500',
            'hero_cta_text' => 'required|string|max:100',
            'hero_cta_link' => 'required|string|max:255',
        ]);
        
        // Hero section ayarları
        SiteAyar::updateOrCreate(
            ['anahtar' => 'hero_baslik'],
            ['deger' => $request->hero_baslik]
        );
        
        SiteAyar::updateOrCreate(
            ['anahtar' => 'hero_alt_baslik'],
            ['deger' => $request->hero_alt_baslik]
        );
        
        SiteAyar::updateOrCreate(
            ['anahtar' => 'hero_cta_text'],
            ['deger' => $request->hero_cta_text]
        );
        
        SiteAyar::updateOrCreate(
            ['anahtar' => 'hero_cta_link'],
            ['deger' => $request->hero_cta_link]
        );
        
        // Özellikler
        for ($i = 1; $i <= 3; $i++) {
            if ($request->has("ozellik_{$i}_baslik")) {
                SiteAyar::updateOrCreate(
                    ['anahtar' => "ozellik_{$i}_baslik"],
                    ['deger' => $request->input("ozellik_{$i}_baslik")]
                );
            }
            
            if ($request->has("ozellik_{$i}_icon")) {
                SiteAyar::updateOrCreate(
                    ['anahtar' => "ozellik_{$i}_icon"],
                    ['deger' => $request->input("ozellik_{$i}_icon")]
                );
            }
            
            if ($request->has("ozellik_{$i}_aciklama")) {
                SiteAyar::updateOrCreate(
                    ['anahtar' => "ozellik_{$i}_aciklama"],
                    ['deger' => $request->input("ozellik_{$i}_aciklama")]
                );
            }
        }
        
        return back()->with('success', 'Vitrin ayarları başarıyla güncellendi!');
    }
}