<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\Bayi;
use App\Models\Magaza;
use App\Models\SiteAyar;
use App\Models\Siparis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Basit test data
        $istatistik = [
            'urun' => 10,
            'bayi' => 5,
            'magaza' => 3,
        ];
        
        $platformStats = [
            'toplam_magaza' => 3,
            'aktif_magaza' => 2,
            'trendyol' => 1,
            'hepsiburada' => 1,
            'n11' => 1,
            'amazon' => 0,
            'son_24_saat_senkron' => 5,
        ];
        
        $errorStats = [
            'basarili_senkron_orani' => 95,
            'cloudflare_engel' => 0,
            'rate_limit_hata' => 0,
        ];
        
        $sonAktiviteler = [
            [
                'zaman' => now()->subMinutes(15),
                'islem' => 'Test işlem',
                'durum' => 'success',
                'magaza' => 'Test Mağaza'
            ],
            [
                'zaman' => now()->subMinutes(25),
                'islem' => 'Ürün güncelleme',
                'durum' => 'success',
                'magaza' => 'Trendyol'
            ],
            [
                'zaman' => now()->subMinutes(35),
                'islem' => 'Stok senkronizasyonu',
                'durum' => 'error',
                'magaza' => 'Hepsiburada'
            ],
        ];

        return view('admin.dashboard', [
            'istatistik' => $istatistik,
            'platformStats' => $platformStats,
            'errorStats' => $errorStats,
            'sonAktiviteler' => $sonAktiviteler,
        ]);
    }
    
    private function getDashboardData()
    {
        // Temel istatistikler
        $istatistik = [
            'urun' => class_exists(Urun::class) ? Urun::count() : 0,
            'bayi' => class_exists(Bayi::class) ? Bayi::count() : 0,
            'magaza' => class_exists(Magaza::class) ? Magaza::count() : 0,
        ];
        
        $sonUrunler = class_exists(Urun::class) ? Urun::latest('id')->take(6)->get() : collect();

        // Site ayarlarını al
        $siteAyarlar = [];
        if (class_exists(SiteAyar::class)) {
            $siteAyarlar = SiteAyar::pluck('deger', 'anahtar')->toArray();
        }

        // Platform istatistikleri
        $platformStats = [];
        if (class_exists(Magaza::class)) {
            $platformStats = [
                'toplam_magaza' => Magaza::count(),
                'aktif_magaza' => Magaza::where('aktif', true)->count(),
                'pasif_magaza' => Magaza::where('aktif', false)->count(),
                'trendyol' => Magaza::where('platform', 'trendyol')->count(),
                'hepsiburada' => Magaza::where('platform', 'hepsiburada')->count(),
                'n11' => Magaza::where('platform', 'n11')->count(),
                'amazon' => Magaza::where('platform', 'amazon')->count(),
                'son_24_saat_senkron' => Magaza::where('son_senkron_tarihi', '>=', now()->subDay())->count(),
            ];
        }

        // Error tracking stats (mock - gerçek log analizi için geliştirilebilir)
        $errorStats = [
            'son_24_saat_hata' => rand(0, 5), // Mock data
            'basarili_senkron_orani' => rand(85, 98), // Mock data
            'cloudflare_engel' => rand(0, 3), // Mock data
            'rate_limit_hata' => rand(0, 2), // Mock data
        ];

        // Ek metrikler
        $stokToplam = 0;
        $stokDegeri = 0.0;
        $dusukStokler = collect();
        if (class_exists(Urun::class)) {
            // stok toplamı ve stok değeri
            $stokToplam = (int) Urun::sum('stok');
            $stokDegeri = (float) Urun::select(DB::raw('SUM(COALESCE(stok,0) * COALESCE(fiyat,0)) as toplam'))
                ->value('toplam');
            // düşük stok listesi (<=5)
            $dusukStokler = Urun::whereNotNull('stok')
                ->where('stok', '<=', 5)
                ->orderBy('stok')
                ->take(5)
                ->get();
        }

        // Son aktiviteler (mock data - gerçek sistem için log analizi)
        $sonAktiviteler = [
            (object)[
                'zaman' => now()->subMinutes(15),
                'islem' => 'Trendyol katalog çekme',
                'durum' => 'success',
                'detay' => '25 ürün güncellendi',
                'magaza' => 'Test Trendyol Mağaza'
            ],
            (object)[
                'zaman' => now()->subHours(2),
                'islem' => 'Hepsiburada stok senkronizasyonu',
                'durum' => 'error',
                'detay' => 'API bağlantı hatası',
                'magaza' => 'Hepsiburada Mağaza'
            ],
            (object)[
                'zaman' => now()->subHours(4),
                'islem' => 'N11 fiyat güncelleme',
                'durum' => 'success',
                'detay' => '142 ürün fiyatı güncellendi',
                'magaza' => 'N11 Mağaza'
            ],
        ];

        return [
            'istatistik' => $istatistik,
            'platformStats' => $platformStats,
            'errorStats' => $errorStats,
            'sonAktiviteler' => $sonAktiviteler,
            'sonUrunler' => $sonUrunler,
            'stokToplam' => $stokToplam,
            'stokDegeri' => $stokDegeri,
            'dusukStokler' => $dusukStokler,
            'siteAyarlar' => $siteAyarlar,
        ];
    }
    
    /**
     * Dashboard cache'ini temizle
     */
    public function clearCache()
    {
        Cache::forget('admin_dashboard_data');
        return redirect()->route('admin.dashboard')->with('success', 'Dashboard cache temizlendi');
    }
}
