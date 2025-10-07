<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\Bayi;
use App\Models\SiteAyar;
use Illuminate\Http\Request;
use App\Support\SessionNotes;

class GelistiriciController extends Controller
{
    public function index()
    {
        $stats = [
            'urunler' => class_exists(Urun::class) ? (int) Urun::count() : 0,
            'bayiler' => class_exists(Bayi::class) ? (int) Bayi::count() : 0,
            'siparisler' => 0,
            'kategoriler' => 0,
            'site_ayarlari' => class_exists(SiteAyar::class) ? (int) SiteAyar::count() : 0,
        ];

        $parsed = SessionNotes::parseToday();
        $projeDurumu = [
            'tamamlanan' => $parsed['yapilanlar'] ?? [],
            'gelistirme_asamasi' => [],
            'planlanan' => $parsed['yapilacaklar'] ?? [],
        ];

        $teknolojiStack = [
            'backend' => ['PHP 8.2','Laravel 12','Sanctum','SQLite'],
            'frontend' => ['Blade','TailwindCSS','Alpine.js','Vite'],
            'tools' => ['Composer','PestPHP','Postman','Git'],
            'deployment' => ['Apache (XAMPP)','Windows'],
        ];

        $sistemSagligi = [
            'veritabani' => 'OK',
            'cache' => 'OK',
            'queue' => 'OK',
            'dosya_sistemi' => 'OK',
        ];

        return view('admin.gelistirici.index', compact('stats','projeDurumu','teknolojiStack','sistemSagligi'));
    }

    public function notEkle(Request $request)
    {
        $data = $request->validate([
            'baslik' => ['required','string','max:255'],
            'icerik' => ['required','string'],
            'oncelik' => ['required','in:düşük,orta,yüksek,acil'],
        ]);

        $notlar = session('gelistirici_notlar', []);
        $notlar[] = [
            'baslik' => $data['baslik'],
            'icerik' => $data['icerik'],
            'oncelik' => $data['oncelik'],
            'tarih' => now()->format('d.m.Y H:i'),
        ];
        session(['gelistirici_notlar' => $notlar]);

        return redirect()->route('admin.gelistirici.index')->with('success','Not eklendi');
    }
}