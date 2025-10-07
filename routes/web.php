<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UrunController as AdminUrunController;
use App\Http\Controllers\Admin\MagazaController as AdminMagazaController;
use App\Http\Controllers\Admin\SiteAyarController;
use App\Http\Controllers\Admin\SayfaYonetimController;
use App\Http\Controllers\Admin\XMLController;
use App\Http\Controllers\Admin\AIController;
use App\Http\Controllers\Admin\BarkodController;
use App\Http\Controllers\Admin\AnasayfaController;
use App\Http\Controllers\Admin\VitrinController as AdminVitrinController;
use App\Http\Controllers\Admin\BayiController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ModulController;
use App\Http\Controllers\Admin\MarkaController;
use App\Http\Controllers\Admin\OzellikController;
use App\Http\Controllers\Admin\GelistiriciController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VitrinController;
use App\Http\Controllers\Api\V1\SepetController as ApiSepetController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\B2B\BayiUrunController;
use App\Http\Controllers\B2B\BayiPanelController;
use App\Http\Controllers\B2B\BayiAyarController;

// Ana sayfa
Route::get('/', [VitrinController::class, 'index'])->name('vitrin.index');

// E-Ticaret Mağaza
Route::get('/magaza', [VitrinController::class, 'magaza'])->name('vitrin.magaza');

// Vitrin (B2C)
Route::get('/vitrin', [VitrinController::class, 'index'])->name('vitrin.home');

Route::get('/vitrin/urunler', [VitrinController::class, 'urunler'])->name('vitrin.urunler');
Route::get('/vitrin/arama', [VitrinController::class, 'arama'])->name('vitrin.arama');
Route::get('/kategori/{slug}', [VitrinController::class, 'kategoriSlug'])->name('vitrin.kategori.slug');

Route::get('/vitrin/urun/{id}', [VitrinController::class, 'urunDetay'])->name('vitrin.urun-detay');
// Bayi vitrini (geçici: id ile)
Route::get('/vitrin/bayi/{bayi}', [VitrinController::class, 'bayiVitrin'])->name('vitrin.bayi');

Route::get('/vitrin/sepet', [VitrinController::class, 'sepet'])->name('vitrin.sepet');
// Sepet linki için alias (layouts.app içinde route('sepet.index') kullanılıyor)
Route::get('/sepet', function() {
    return redirect()->route('vitrin.sepet');
})->name('sepet.index');

Route::get('/vitrin/odeme', [VitrinController::class, 'odeme'])->name('vitrin.odeme');

// Sepet (Session-based, web forms)
Route::post('/sepet/ekle', [ApiSepetController::class, 'ekle'])->name('sepet.ekle');

// Statik/İçerik Sayfaları
Route::get('/sayfa/{slug}', [SayfaController::class, 'goster'])->name('sayfa.goster');
Route::get('/iletisim', [SayfaController::class, 'iletisim'])->name('sayfa.iletisim');
Route::post('/iletisim', [SayfaController::class, 'iletisimFormuGonder'])->name('sayfa.iletisim.gonder');
Route::get('/hakkimizda', [SayfaController::class, 'hakkimizda'])->name('sayfa.hakkimizda');
Route::get('/gizlilik-politikasi', [SayfaController::class, 'gizlilikPolitikasi'])->name('sayfa.gizlilik');
Route::get('/kullanim-sartlari', [SayfaController::class, 'kullanimSartlari'])->name('sayfa.kullanim');

// Auth routes
require __DIR__.'/auth.php';

// Dashboard: rol bazlı yönlendirme (herhangi bir sebeple buraya düşülürse doğru panele taşı)
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $rol = auth()->user()->rol ?? null;
    if ($rol === 'admin') {
        return redirect()->route('admin.panel');
    }
    if ($rol === 'bayi') {
        return redirect()->route('bayi.panel');
    }
    // müşteri veya diğer roller => public vitrin
    return redirect()->route('vitrin.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bayi Paneli
Route::middleware(['auth', 'bayi'])->group(function () {
    // Bayi Admin Dashboard
    Route::get('/bayi/panel', [BayiPanelController::class, 'dashboard'])->name('bayi.panel');

    // Bayi fiyatlı ürün listesi
    Route::get('/bayi/urunler', [BayiUrunController::class, 'index'])->name('bayi.urunler');

    // Siparişler ve detay
    Route::get('/bayi/siparisler', [BayiPanelController::class, 'siparisler'])->name('bayi.siparisler');
    Route::get('/bayi/siparis/{id}', [BayiPanelController::class, 'siparisDetay'])->name('bayi.siparis.detay');

    // Toplu sipariş
    Route::get('/bayi/toplu-siparis', [BayiPanelController::class, 'topluSiparis'])->name('bayi.toplu-siparis');

    // Cari hesap
    Route::get('/bayi/cari', [BayiPanelController::class, 'cariHesap'])->name('bayi.cari');

    // Profil
    Route::get('/bayi/profil', [BayiPanelController::class, 'profil'])->name('bayi.profil');
    Route::post('/bayi/profil', [BayiPanelController::class, 'profilGuncelle'])->name('bayi.profil.guncelle');

    // Bayi mağaza ayarları
    Route::get('/bayi/ayarlar', [BayiAyarController::class, 'index'])->name('bayi.ayarlar');
    Route::post('/bayi/ayarlar', [BayiAyarController::class, 'kaydet'])->name('bayi.ayarlar.kaydet');
});

// Admin Paneli - Geçici üst seviye tanımlar kaldırıldı; tüm admin rotaları prefix('admin') altında.

// Admin Paneli - Authenticated Routes
// Admin routes - basit ve etkili
Route::prefix('admin')->group(function () {
    // Test route
    Route::get('/test', function() {
        return 'Admin test route çalışıyor!';
    });

    // Panel ve Dashboard - HİÇ MİDDLEWARE YOK
    Route::get('/', [DashboardController::class, 'index'])->name('admin.panel');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Geliştirici - basit
    Route::get('/gelistirici', function() {
        return 'Geliştirici sayfası çalışıyor!';
    })->name('admin.gelistirici.index');

    // Modüller
    Route::get('/moduller', [ModulController::class, 'index'])->name('admin.moduller');
    Route::post('/moduller', [ModulController::class, 'guncelle'])->name('admin.moduller.guncelle');
    Route::get('/moduller/entegrasyon', [ModulController::class, 'entegrasyon'])->name('admin.moduller.entegrasyon');
    Route::get('/moduller/entegrasyon/ayar', [ModulController::class, 'entegrasyonAyar'])->name('admin.moduller.entegrasyon.ayar');
    Route::post('/moduller/entegrasyon/ayar', [ModulController::class, 'entegrasyonAyarKaydet'])->name('admin.moduller.entegrasyon.ayar.kaydet');
    Route::get('/moduller/kargo', [ModulController::class, 'kargo'])->name('admin.moduller.kargo');
    Route::get('/moduller/odeme', [ModulController::class, 'odeme'])->name('admin.moduller.odeme');

    // Geliştirici
    Route::get('/gelistirici', [GelistiriciController::class, 'index'])->name('admin.gelistirici.index');
    Route::post('/gelistirici/not-ekle', [GelistiriciController::class, 'notEkle'])->name('admin.gelistirici.not-ekle');

    // AI ürün önerisi
    Route::post('/ai/urun-onerisi', [AIController::class, 'urunOnerisi'])->name('admin.ai.urunOnerisi');

    // Barkod ile ürün çekme
    Route::post('/barkod/fetch', [BarkodController::class, 'fetchProduct'])->name('admin.barkod.fetch');

    // Ürün Yönetimi (CRUD + Toplu İşlemler)
    Route::get('/urun', [AdminUrunController::class, 'index'])->name('admin.urun.index');
    Route::get('/urun/yeni', [AdminUrunController::class, 'create'])->name('admin.urun.create');
    Route::post('/urun/ekle', [AdminUrunController::class, 'store'])->name('admin.urun.store');
    Route::get('/urun/{urun}', [AdminUrunController::class, 'show'])->name('admin.urun.show');
    Route::get('/urun/{urun}/duzenle', [AdminUrunController::class, 'edit'])->name('admin.urun.edit');
    Route::put('/urun/{urun}', [AdminUrunController::class, 'update'])->name('admin.urun.update');
    Route::delete('/urun/{urun}', [AdminUrunController::class, 'destroy'])->name('admin.urun.destroy');
    Route::post('/urun/toplu-islem', [AdminUrunController::class, 'bulkAction'])->name('admin.urun.bulk');
    // Ürün Bazlı Bayi Fiyat Yönetimi
    Route::post('/urun/{urun}/bayi-fiyat', function(\App\Models\Urun $urun, \Illuminate\Http\Request $request) {
        $data = $request->validate([
            'bayi_id' => ['required','exists:bayiler,id'],
            'fiyat' => ['required','numeric','min:0'],
            'iskonto_orani' => ['nullable','numeric','min:0','max:100'],
            'baslangic_tarihi' => ['nullable','date'],
            'bitis_tarihi' => ['nullable','date','after_or_equal:baslangic_tarihi'],
        ]);
        $payload = array_merge($data, ['urun_id' => $urun->id]);
        // Unique(bayi_id, urun_id) için upsert benzeri mantık
        $existing = \App\Models\BayiFiyat::where('urun_id',$urun->id)->where('bayi_id',$data['bayi_id'])->first();
        if ($existing) {
            $existing->update($payload);
        } else {
            \App\Models\BayiFiyat::create($payload);
        }
        return back()->with('success', 'Bayi fiyatı kaydedildi.');
    })->name('admin.urun.bayi-fiyat.kaydet');

    Route::delete('/urun/{urun}/bayi-fiyat/{id}', function(\App\Models\Urun $urun, $id) {
        $kayit = \App\Models\BayiFiyat::where('urun_id',$urun->id)->where('id',$id)->firstOrFail();
        $kayit->delete();
        return back()->with('success', 'Bayi fiyatı silindi.');
    })->name('admin.urun.bayi-fiyat.sil');
    
    // Mağaza Yönetimi (CRUD + Entegrasyon)
    Route::get('/magaza', [AdminMagazaController::class, 'index'])->name('admin.magaza.index');
    Route::get('/magaza/yeni', [AdminMagazaController::class, 'create'])->name('admin.magaza.create');
    Route::post('/magaza/ekle', [AdminMagazaController::class, 'store'])->name('admin.magaza.store');
    Route::get('/magaza/{magaza}', [AdminMagazaController::class, 'show'])->name('admin.magaza.show');
    Route::get('/magaza/{magaza}/duzenle', [AdminMagazaController::class, 'edit'])->name('admin.magaza.edit');
    Route::put('/magaza/{magaza}', [AdminMagazaController::class, 'update'])->name('admin.magaza.update');
    Route::delete('/magaza/{magaza}', [AdminMagazaController::class, 'destroy'])->name('admin.magaza.destroy');
    Route::post('/magaza/{magaza}/test-connection', [AdminMagazaController::class, 'testConnection'])->name('admin.magaza.test');
    Route::post('/magaza/{magaza}/senkronize', [AdminMagazaController::class, 'senkronize'])->name('admin.magaza.sync');
    Route::post('/magaza/toplu-islem', [AdminMagazaController::class, 'bulkAction'])->name('admin.magaza.bulk');
    
    // Site Ayarları
    Route::get('/site-ayarlari', [SiteAyarController::class, 'index'])->name('admin.site-ayarlari');
    Route::post('/site-ayarlari', [SiteAyarController::class, 'guncelle'])->name('admin.site-ayarlari.guncelle');
    Route::post('/site-ayarlari/yeni', [SiteAyarController::class, 'yeniAyar'])->name('admin.site-ayarlari.yeni');
    Route::delete('/site-ayarlari/{id}', [SiteAyarController::class, 'sil'])->name('admin.site-ayarlari.sil');
    
    // E-Ticaret Site Yönetimi
    Route::get('/eticaret-ayarlari', [SiteAyarController::class, 'index'])->name('admin.site-ayar.index');
    Route::post('/eticaret-ayarlari', [SiteAyarController::class, 'update'])->name('admin.site-ayar.update');
    Route::post('/eticaret-ayarlari/toggle', [SiteAyarController::class, 'toggleSite'])->name('admin.site-ayar.toggle');

    // Anasayfa Yönetimi
    Route::get('/anasayfa', [AnasayfaController::class, 'index'])->name('admin.anasayfa');
    Route::post('/anasayfa', [AnasayfaController::class, 'guncelle'])->name('admin.anasayfa.guncelle');

    // Vitrin (Marketing) Yönetimi
    Route::get('/vitrin', [AdminVitrinController::class, 'index'])->name('admin.vitrin.index');
    Route::post('/vitrin', [AdminVitrinController::class, 'guncelle'])->name('admin.vitrin.guncelle');
    
    // Sayfa Yönetimi
    Route::get('/sayfalar', [SayfaYonetimController::class, 'index'])->name('admin.sayfalar');
    Route::get('/sayfalar/yeni', [SayfaYonetimController::class, 'create'])->name('admin.sayfalar.create');
    Route::post('/sayfalar', [SayfaYonetimController::class, 'store'])->name('admin.sayfalar.store');
    Route::get('/sayfalar/{sayfa}/duzenle', [SayfaYonetimController::class, 'edit'])->name('admin.sayfalar.edit');
    Route::put('/sayfalar/{sayfa}', [SayfaYonetimController::class, 'update'])->name('admin.sayfalar.update');
    Route::delete('/sayfalar/{sayfa}', [SayfaYonetimController::class, 'destroy'])->name('admin.sayfalar.destroy');

    // XML içe/dışa aktarma
    Route::post('/xml/import', [XMLController::class, 'import'])->name('admin.xml.import');
    Route::get('/xml/export', [XMLController::class, 'export'])->name('admin.xml.export');

    // Kategori Yönetimi
    Route::get('/kategoriler', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::get('/kategoriler/yeni', [KategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/kategoriler', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategoriler/{kategori}/duzenle', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/kategoriler/{kategori}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategoriler/{kategori}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // Marka Yönetimi
    Route::get('/markalar', [MarkaController::class, 'index'])->name('admin.marka.index');
    Route::get('/markalar/yeni', [MarkaController::class, 'create'])->name('admin.marka.create');
    Route::post('/markalar', [MarkaController::class, 'store'])->name('admin.marka.store');
    Route::get('/markalar/{marka}/duzenle', [MarkaController::class, 'edit'])->name('admin.marka.edit');
    Route::put('/markalar/{marka}', [MarkaController::class, 'update'])->name('admin.marka.update');
    Route::delete('/markalar/{marka}', [MarkaController::class, 'destroy'])->name('admin.marka.destroy');

    // Bayi Yönetimi

    // Özellik Yönetimi
    Route::get('/ozellikler', [OzellikController::class, 'index'])->name('admin.ozellik.index');
    Route::get('/ozellikler/yeni', [OzellikController::class, 'create'])->name('admin.ozellik.create');
    Route::post('/ozellikler', [OzellikController::class, 'store'])->name('admin.ozellik.store');
    Route::get('/ozellikler/{ozellik}/duzenle', [OzellikController::class, 'edit'])->name('admin.ozellik.edit');
    Route::put('/ozellikler/{ozellik}', [OzellikController::class, 'update'])->name('admin.ozellik.update');
    Route::delete('/ozellikler/{ozellik}', [OzellikController::class, 'destroy'])->name('admin.ozellik.destroy');
    Route::post('/ozellikler/bulk-sil', [OzellikController::class, 'bulkDelete'])->name('admin.ozellik.bulk-delete');
    Route::get('/bayiler', [BayiController::class, 'index'])->name('admin.bayi.index');
    Route::get('/bayiler/yeni', [BayiController::class, 'create'])->name('admin.bayi.create');
    Route::post('/bayiler', [BayiController::class, 'store'])->name('admin.bayi.store');
    Route::get('/bayiler/{bayi}', [BayiController::class, 'show'])->name('admin.bayi.show');
    Route::get('/bayiler/{bayi}/duzenle', [BayiController::class, 'edit'])->name('admin.bayi.edit');
    Route::put('/bayiler/{bayi}', [BayiController::class, 'update'])->name('admin.bayi.update');
    Route::delete('/bayiler/{bayi}', [BayiController::class, 'destroy'])->name('admin.bayi.destroy');
});

// B2B Login (guest) - giriş sonrası bayi paneline yönlendirmek için intended set et
Route::get('/b2b-login', function () {
    // Girişten sonra nereye gitsin?
    session(['url.intended' => route('bayi.panel')]);
    return view('auth.b2b-login');
})->middleware('guest')->name('b2b.login');

// B2B Panel giriş noktası – role göre yönlendir
Route::get('/b2b', function () {
    if (!auth()->check()) {
        return redirect()->route('b2b.login');
    }
    $user = auth()->user();
    if (in_array($user->rol ?? null, ['bayi', 'admin'], true)) {
        return redirect()->route('bayi.panel');
    }
    return redirect()->route('dashboard');
})->name('b2b.panel');

// Sadece LOCAL ortam için hızlı demo giriş linkleri
if (app()->environment('local')) {
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
            'admin' => redirect()->route('admin.panel')->with('success', 'Admin olarak giriş yapıldı.'),
            'bayi' => redirect()->route('b2b.panel')->with('success', 'Bayi olarak giriş yapıldı.'),
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