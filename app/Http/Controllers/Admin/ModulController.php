<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAyar;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    protected array $modules = [
        'entegrasyon' => [
            'key' => 'modul_entegrasyon_aktif',
            'title' => 'Entegrasyon Modülü',
            'desc' => 'Trendyol, Hepsiburada, N11, Amazon vb. mağaza ve XML entegrasyonları.',
        ],
        'kargo' => [
            'key' => 'modul_kargo_aktif',
            'title' => 'Kargo Modülü',
            'desc' => 'Kargo firmaları, gönderim kuralları ve ücretlendirme.',
        ],
        'odeme' => [
            'key' => 'modul_odeme_aktif',
            'title' => 'Ödeme Yöntemleri',
            'desc' => 'Kredi kartı, havale/EFT, kapıda ödeme vb. yöntemler.',
        ],
    ];

    public function index()
    {
        $status = [
            'entegrasyon' => (bool) SiteAyar::get('modul_entegrasyon_aktif', true),
            'kargo' => (bool) SiteAyar::get('modul_kargo_aktif', false),
            'odeme' => (bool) SiteAyar::get('modul_odeme_aktif', false),
        ];
        return view('admin.moduller.index', [
            'modules' => $this->modules,
            'status' => $status,
        ]);
    }

    public function guncelle(Request $request)
    {
        $request->validate([
            'modul_entegrasyon_aktif' => ['nullable','boolean'],
            'modul_kargo_aktif' => ['nullable','boolean'],
            'modul_odeme_aktif' => ['nullable','boolean'],
        ]);

        SiteAyar::set('modul_entegrasyon_aktif', $request->boolean('modul_entegrasyon_aktif') ? '1' : '0', 'text', 'moduller');
        SiteAyar::set('modul_kargo_aktif', $request->boolean('modul_kargo_aktif') ? '1' : '0', 'text', 'moduller');
        SiteAyar::set('modul_odeme_aktif', $request->boolean('modul_odeme_aktif') ? '1' : '0', 'text', 'moduller');

        return back()->with('success','Modül durumları güncellendi.');
    }

    public function entegrasyon()
    {
        $aktif = (bool) SiteAyar::get('modul_entegrasyon_aktif', true);
        return view('admin.moduller.entegrasyon', compact('aktif'));
    }

    public function kargo()
    {
        $aktif = (bool) SiteAyar::get('modul_kargo_aktif', false);
        return view('admin.moduller.kargo', compact('aktif'));
    }

    public function odeme()
    {
        $aktif = (bool) SiteAyar::get('modul_odeme_aktif', false);
        return view('admin.moduller.odeme', compact('aktif'));
    }

    public function entegrasyonAyar()
    {
        $aktif = (bool) SiteAyar::get('modul_entegrasyon_aktif', true);
        $ayarlar = SiteAyar::getGrup('entegrasyon') ?? [];
        return view('admin.moduller.entegrasyon-ayar', compact('aktif','ayarlar'));
    }

    public function entegrasyonAyarKaydet(Request $request)
    {
        $data = $request->validate([
            'varsayilan_platform' => ['nullable','string','max:50'],
            'xml_cache_suresi' => ['nullable','integer','min:0'],
        ]);
        foreach ($data as $k=>$v) {
            SiteAyar::set('entegrasyon_'.$k, (string)$v, 'text', 'entegrasyon');
        }
        return back()->with('success','Entegrasyon ayarları kaydedildi.');
    }
}
