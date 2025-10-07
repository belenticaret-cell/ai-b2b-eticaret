<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UrunController as AdminUrunController;
use App\Http\Controllers\Admin\MagazaController as AdminMagazaController;
use App\Http\Controllers\Admin\SiteAyarController;
use App\Http\Controllers\Admin\GelistiriciController;

// B2B Controllers
use App\Http\Controllers\B2B\BayiPanelController;

// Vitrin Controllers
use App\Http\Controllers\VitrinController;
use App\Http\Controllers\SayfaController;

// API Controllers
use App\Http\Controllers\Api\V1\SepetController as ApiSepetController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Misafir Erişimi)
|--------------------------------------------------------------------------
*/

// Ana Sayfa - Pazarlama/Tanıtım
Route::get('/', [VitrinController::class, 'index'])->name('vitrin.index');

// E-Ticaret Mağaza
Route::get('/magaza', [VitrinController::class, 'magaza'])->name('vitrin.magaza');

// Vitrin Sayfaları
Route::prefix('vitrin')->group(function () {
    Route::get('/urunler', [VitrinController::class, 'urunler'])->name('vitrin.urunler');
    Route::get('/arama', [VitrinController::class, 'arama'])->name('vitrin.arama');
    Route::get('/urun/{id}', [VitrinController::class, 'urunDetay'])->name('vitrin.urun-detay');
    Route::get('/sepet', [VitrinController::class, 'sepet'])->name('vitrin.sepet');
    Route::get('/odeme', [VitrinController::class, 'odeme'])->name('vitrin.odeme');
});

// Kategori Slug
Route::get('/kategori/{slug}', [VitrinController::class, 'kategoriSlug'])->name('vitrin.kategori.slug');

// Sepet İşlemleri (Session-based)
Route::post('/sepet/ekle', [ApiSepetController::class, 'ekle'])->name('sepet.ekle');
Route::get('/sepet', function() {
    return redirect()->route('vitrin.sepet');
})->name('sepet.index');

// Statik Sayfalar
Route::get('/sayfa/{slug}', [SayfaController::class, 'goster'])->name('sayfa.goster');
Route::get('/iletisim', [SayfaController::class, 'iletisim'])->name('sayfa.iletisim');
Route::post('/iletisim', [SayfaController::class, 'iletisimFormuGonder'])->name('sayfa.iletisim.gonder');
Route::get('/hakkimizda', [SayfaController::class, 'hakkimizda'])->name('sayfa.hakkimizda');
Route::get('/gizlilik-politikasi', [SayfaController::class, 'gizlilikPolitikasi'])->name('sayfa.gizlilik');
Route::get('/kullanim-sartlari', [SayfaController::class, 'kullanimSartlari'])->name('sayfa.kullanim');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

// B2B Özel Giriş
Route::get('/b2b-login', function () {
    return view('auth.b2b-login');
})->name('b2b.login')->middleware('guest');

/*
|--------------------------------------------------------------------------
| USER DASHBOARD (Normal Kullanıcılar)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| B2B PANEL (Bayi Kullanıcıları)
|--------------------------------------------------------------------------
*/

Route::prefix('b2b')->middleware(['auth', 'bayi'])->name('b2b.')->group(function () {
    Route::get('/dashboard', [BayiPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('/urunler', [BayiPanelController::class, 'urunler'])->name('urunler');
    Route::get('/siparisler', [BayiPanelController::class, 'siparisler'])->name('siparisler');
    Route::get('/siparis/{id}', [BayiPanelController::class, 'siparisDetay'])->name('siparis-detay');
    Route::get('/toplu-siparis', [BayiPanelController::class, 'topluSiparis'])->name('toplu-siparis');
    Route::get('/cari-hesap', [BayiPanelController::class, 'cariHesap'])->name('cari-hesap');
    Route::get('/profil', [BayiPanelController::class, 'profil'])->name('profil');
    Route::post('/profil', [BayiPanelController::class, 'profilGuncelle'])->name('profil.guncelle');
});

/*
|--------------------------------------------------------------------------
| ADMIN PANEL (Admin Kullanıcıları) - Geçici Auth Bypass
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Ana Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Site Ayarları & E-Ticaret Yönetimi
    Route::get('/site-ayarlari', [SiteAyarController::class, 'index'])->name('site-ayarlari.index');
    Route::post('/site-ayarlari', [SiteAyarController::class, 'update'])->name('site-ayarlari.update');
    Route::post('/site-ayarlari/toggle', [SiteAyarController::class, 'toggleSite'])->name('site-ayarlari.toggle');
    
    // E-Ticaret Site Ayarları (alias)
    Route::get('/site-ayar', [SiteAyarController::class, 'index'])->name('site-ayar.index');
    Route::post('/site-ayar', [SiteAyarController::class, 'update'])->name('site-ayar.update');
    Route::post('/site-ayar/toggle', [SiteAyarController::class, 'toggleSite'])->name('site-ayar.toggle');
    
    // Ürün Yönetimi
    Route::get('/urun', [AdminUrunController::class, 'index'])->name('urun.index');
    Route::get('/urun/create', [AdminUrunController::class, 'create'])->name('urun.create');
    Route::post('/urun', [AdminUrunController::class, 'store'])->name('urun.store');
    Route::get('/urun/{urun}', [AdminUrunController::class, 'show'])->name('urun.show');
    Route::get('/urun/{urun}/edit', [AdminUrunController::class, 'edit'])->name('urun.edit');
    Route::put('/urun/{urun}', [AdminUrunController::class, 'update'])->name('urun.update');
    Route::delete('/urun/{urun}', [AdminUrunController::class, 'destroy'])->name('urun.destroy');
    
    // Mağaza Yönetimi
    Route::resource('magazalar', AdminMagazaController::class);
    Route::post('/magazalar/{magaza}/test', [AdminMagazaController::class, 'testConnection'])->name('magaza.test');
    Route::post('/magazalar/{magaza}/sync', [AdminMagazaController::class, 'senkronize'])->name('magaza.sync');
    
    // Geliştirici Tracking
    Route::get('/gelistirici', [GelistiriciController::class, 'index'])->name('gelistirici.index');
    Route::post('/gelistirici/not-ekle', [GelistiriciController::class, 'notEkle'])->name('gelistirici.not-ekle');
    Route::delete('/gelistirici/not-sil/{id}', [GelistiriciController::class, 'notSil'])->name('gelistirici.not-sil');
    
    // Dashboard'da kullanılan eksik route'lar - Placeholder'lar
    Route::get('/vitrin', function() { 
        return view('admin.vitrin.index'); 
    })->name('vitrin.index');
    
    Route::get('/bayi', function() { 
        return view('admin.bayi.index'); 
    })->name('bayi.index');
    
    Route::get('/moduller/entegrasyon', function() { 
        return view('admin.moduller.entegrasyon'); 
    })->name('moduller.entegrasyon');
});

/*
|--------------------------------------------------------------------------
| DEVELOPMENT ROUTES (Local Environment Only)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    // Hızlı Demo Giriş Linkleri
    Route::get('/dev-login/{rol}', function (string $rol) {
        $email = match ($rol) {
            'admin' => 'admin@aib2b.local',
            'bayi' => 'bayi@aib2b.local',
            'musteri' => 'musteri@aib2b.local',
            default => null,
        };

        if (!$email) {
            abort(404);
        }

        $user = \App\Models\Kullanici::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Demo kullanıcı bulunamadı. Lütfen seeder çalıştırın.');
        }

        \Illuminate\Support\Facades\Auth::login($user);
        request()->session()->regenerate();

        return match ($rol) {
            'admin' => redirect()->route('admin.dashboard')->with('success', 'Admin olarak giriş yapıldı.'),
            'bayi' => redirect()->route('b2b.dashboard')->with('success', 'Bayi olarak giriş yapıldı.'),
            default => redirect()->route('vitrin.index')->with('success', 'Müşteri olarak giriş yapıldı.'),
        };
    })->name('dev.login');

    Route::get('/dev-logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('vitrin.index')->with('success', 'Çıkış yapıldı.');
    })->name('dev.logout');
}