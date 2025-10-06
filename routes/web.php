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
use App\Http\Controllers\Admin\BayiController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ModulController;
use App\Http\Controllers\Admin\MarkaController;
use App\Http\Controllers\Admin\OzellikController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VitrinController;
use App\Http\Controllers\Api\V1\SepetController as ApiSepetController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\B2B\BayiUrunController;

// Ana sayfa
Route::get('/', [VitrinController::class, 'index'])->name('vitrin.index');

// Vitrin (B2C)
Route::get('/vitrin', [VitrinController::class, 'index'])->name('vitrin.home');

Route::get('/vitrin/urunler', [VitrinController::class, 'urunler'])->name('vitrin.urunler');
Route::get('/vitrin/arama', [VitrinController::class, 'arama'])->name('vitrin.arama');
Route::get('/kategori/{slug}', [VitrinController::class, 'kategoriSlug'])->name('vitrin.kategori.slug');

Route::get('/vitrin/urun/{id}', [VitrinController::class, 'urunDetay'])->name('vitrin.urun-detay');

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

// Dashboard (Auth gerekli)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bayi Paneli
Route::middleware(['auth', 'bayi'])->group(function () {
    Route::get('/bayi/panel', function () {
        return view('bayi.panel');
    })->name('bayi.panel');

    // Bayi fiyatlı ürün listesi
    Route::get('/bayi/urunler', [BayiUrunController::class, 'index'])->name('bayi.urunler');
});

// Admin Paneli
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/panel', [DashboardController::class, 'index'])->name('admin.panel');

    // Modüller
    Route::get('/admin/moduller', [ModulController::class, 'index'])->name('admin.moduller');
    Route::post('/admin/moduller', [ModulController::class, 'guncelle'])->name('admin.moduller.guncelle');
    Route::get('/admin/moduller/entegrasyon', [ModulController::class, 'entegrasyon'])->middleware('module:entegrasyon')->name('admin.moduller.entegrasyon');
    Route::get('/admin/moduller/entegrasyon/ayar', [ModulController::class, 'entegrasyonAyar'])->middleware('module:entegrasyon')->name('admin.moduller.entegrasyon.ayar');
    Route::post('/admin/moduller/entegrasyon/ayar', [ModulController::class, 'entegrasyonAyarKaydet'])->middleware('module:entegrasyon')->name('admin.moduller.entegrasyon.ayar.kaydet');
    Route::get('/admin/moduller/kargo', [ModulController::class, 'kargo'])->middleware('module:kargo')->name('admin.moduller.kargo');
    Route::get('/admin/moduller/odeme', [ModulController::class, 'odeme'])->middleware('module:odeme')->name('admin.moduller.odeme');

    // AI ürün önerisi
    Route::post('/admin/ai/urun-onerisi', [AIController::class, 'urunOnerisi'])->name('admin.ai.urunOnerisi');

    // Barkod ile ürün çekme
    Route::post('/admin/barkod/fetch', [BarkodController::class, 'fetchProduct'])->name('admin.barkod.fetch');

    // Ürün Yönetimi (CRUD + Toplu İşlemler)
    Route::get('/admin/urun', [AdminUrunController::class, 'index'])->name('admin.urun.index');
    Route::get('/admin/urun/yeni', [AdminUrunController::class, 'create'])->name('admin.urun.create');
    Route::post('/admin/urun/ekle', [AdminUrunController::class, 'store'])->name('admin.urun.store');
    Route::get('/admin/urun/{urun}', [AdminUrunController::class, 'show'])->name('admin.urun.show');
    Route::get('/admin/urun/{urun}/duzenle', [AdminUrunController::class, 'edit'])->name('admin.urun.edit');
    Route::put('/admin/urun/{urun}', [AdminUrunController::class, 'update'])->name('admin.urun.update');
    Route::delete('/admin/urun/{urun}', [AdminUrunController::class, 'destroy'])->name('admin.urun.destroy');
    Route::post('/admin/urun/toplu-islem', [AdminUrunController::class, 'bulkAction'])->name('admin.urun.bulk');
    // Ürün Bazlı Bayi Fiyat Yönetimi
    Route::post('/admin/urun/{urun}/bayi-fiyat', function(\App\Models\Urun $urun, \Illuminate\Http\Request $request) {
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

    Route::delete('/admin/urun/{urun}/bayi-fiyat/{id}', function(\App\Models\Urun $urun, $id) {
        $kayit = \App\Models\BayiFiyat::where('urun_id',$urun->id)->where('id',$id)->firstOrFail();
        $kayit->delete();
        return back()->with('success', 'Bayi fiyatı silindi.');
    })->name('admin.urun.bayi-fiyat.sil');
    
    // Mağaza Yönetimi (CRUD + Entegrasyon)
    Route::get('/admin/magaza', [AdminMagazaController::class, 'index'])->name('admin.magaza.index');
    Route::get('/admin/magaza/yeni', [AdminMagazaController::class, 'create'])->name('admin.magaza.create');
    Route::post('/admin/magaza/ekle', [AdminMagazaController::class, 'store'])->name('admin.magaza.store');
    Route::get('/admin/magaza/{magaza}', [AdminMagazaController::class, 'show'])->name('admin.magaza.show');
    Route::get('/admin/magaza/{magaza}/duzenle', [AdminMagazaController::class, 'edit'])->name('admin.magaza.edit');
    Route::put('/admin/magaza/{magaza}', [AdminMagazaController::class, 'update'])->name('admin.magaza.update');
    Route::delete('/admin/magaza/{magaza}', [AdminMagazaController::class, 'destroy'])->name('admin.magaza.destroy');
    Route::post('/admin/magaza/{magaza}/test-connection', [AdminMagazaController::class, 'testConnection'])->name('admin.magaza.test');
    Route::post('/admin/magaza/{magaza}/senkronize', [AdminMagazaController::class, 'senkronize'])->name('admin.magaza.sync');
    Route::post('/admin/magaza/toplu-islem', [AdminMagazaController::class, 'bulkAction'])->name('admin.magaza.bulk');
    
    // Site Ayarları
    Route::get('/admin/site-ayarlari', [SiteAyarController::class, 'index'])->name('admin.site-ayarlari');
    Route::post('/admin/site-ayarlari', [SiteAyarController::class, 'guncelle'])->name('admin.site-ayarlari.guncelle');
    Route::post('/admin/site-ayarlari/yeni', [SiteAyarController::class, 'yeniAyar'])->name('admin.site-ayarlari.yeni');
    Route::delete('/admin/site-ayarlari/{id}', [SiteAyarController::class, 'sil'])->name('admin.site-ayarlari.sil');

    // Anasayfa Yönetimi
    Route::get('/admin/anasayfa', [AnasayfaController::class, 'index'])->name('admin.anasayfa');
    Route::post('/admin/anasayfa', [AnasayfaController::class, 'guncelle'])->name('admin.anasayfa.guncelle');
    
    // Sayfa Yönetimi
    Route::get('/admin/sayfalar', [SayfaYonetimController::class, 'index'])->name('admin.sayfalar');
    Route::get('/admin/sayfalar/yeni', [SayfaYonetimController::class, 'create'])->name('admin.sayfalar.create');
    Route::post('/admin/sayfalar', [SayfaYonetimController::class, 'store'])->name('admin.sayfalar.store');
    Route::get('/admin/sayfalar/{sayfa}/duzenle', [SayfaYonetimController::class, 'edit'])->name('admin.sayfalar.edit');
    Route::put('/admin/sayfalar/{sayfa}', [SayfaYonetimController::class, 'update'])->name('admin.sayfalar.update');
    Route::delete('/admin/sayfalar/{sayfa}', [SayfaYonetimController::class, 'destroy'])->name('admin.sayfalar.destroy');

    // XML içe/dışa aktarma
    Route::post('/admin/xml/import', [XMLController::class, 'import'])->name('admin.xml.import');
    Route::get('/admin/xml/export', [XMLController::class, 'export'])->name('admin.xml.export');

    // Kategori Yönetimi
    Route::get('/admin/kategoriler', [KategoriController::class, 'index'])->name('admin.kategori.index');
    Route::get('/admin/kategoriler/yeni', [KategoriController::class, 'create'])->name('admin.kategori.create');
    Route::post('/admin/kategoriler', [KategoriController::class, 'store'])->name('admin.kategori.store');
    Route::get('/admin/kategoriler/{kategori}/duzenle', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
    Route::put('/admin/kategoriler/{kategori}', [KategoriController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/admin/kategoriler/{kategori}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');

    // Marka Yönetimi
    Route::get('/admin/markalar', [MarkaController::class, 'index'])->name('admin.marka.index');
    Route::get('/admin/markalar/yeni', [MarkaController::class, 'create'])->name('admin.marka.create');
    Route::post('/admin/markalar', [MarkaController::class, 'store'])->name('admin.marka.store');
    Route::get('/admin/markalar/{marka}/duzenle', [MarkaController::class, 'edit'])->name('admin.marka.edit');
    Route::put('/admin/markalar/{marka}', [MarkaController::class, 'update'])->name('admin.marka.update');
    Route::delete('/admin/markalar/{marka}', [MarkaController::class, 'destroy'])->name('admin.marka.destroy');

    // Bayi Yönetimi
    // Marka Yönetimi
    Route::get('/admin/markalar', [MarkaController::class, 'index'])->name('admin.marka.index');
    Route::get('/admin/markalar/yeni', [MarkaController::class, 'create'])->name('admin.marka.create');
    Route::post('/admin/markalar', [MarkaController::class, 'store'])->name('admin.marka.store');
    Route::get('/admin/markalar/{marka}/duzenle', [MarkaController::class, 'edit'])->name('admin.marka.edit');
    Route::put('/admin/markalar/{marka}', [MarkaController::class, 'update'])->name('admin.marka.update');
    Route::delete('/admin/markalar/{marka}', [MarkaController::class, 'destroy'])->name('admin.marka.destroy');

    // Özellik Yönetimi
    Route::get('/admin/ozellikler', [OzellikController::class, 'index'])->name('admin.ozellik.index');
    Route::get('/admin/ozellikler/yeni', [OzellikController::class, 'create'])->name('admin.ozellik.create');
    Route::post('/admin/ozellikler', [OzellikController::class, 'store'])->name('admin.ozellik.store');
    Route::get('/admin/ozellikler/{ozellik}/duzenle', [OzellikController::class, 'edit'])->name('admin.ozellik.edit');
    Route::put('/admin/ozellikler/{ozellik}', [OzellikController::class, 'update'])->name('admin.ozellik.update');
    Route::delete('/admin/ozellikler/{ozellik}', [OzellikController::class, 'destroy'])->name('admin.ozellik.destroy');
    Route::post('/admin/ozellikler/bulk-sil', [OzellikController::class, 'bulkDelete'])->name('admin.ozellik.bulk-delete');
    Route::get('/admin/bayiler', [BayiController::class, 'index'])->name('admin.bayi.index');
    Route::get('/admin/bayiler/yeni', [BayiController::class, 'create'])->name('admin.bayi.create');
    Route::post('/admin/bayiler', [BayiController::class, 'store'])->name('admin.bayi.store');
    Route::get('/admin/bayiler/{bayi}', [BayiController::class, 'show'])->name('admin.bayi.show');
    Route::get('/admin/bayiler/{bayi}/duzenle', [BayiController::class, 'edit'])->name('admin.bayi.edit');
    Route::put('/admin/bayiler/{bayi}', [BayiController::class, 'update'])->name('admin.bayi.update');
    Route::delete('/admin/bayiler/{bayi}', [BayiController::class, 'destroy'])->name('admin.bayi.destroy');
});

// B2B Login 
Route::get('/b2b-login', function () {
    return view('auth.b2b-login');
})->name('b2b.login');

// B2B Panel (sadece bayi ve admin)
Route::middleware(['auth', 'bayi'])->group(function () {
    Route::get('/b2b', function () {
        return view('b2b.panel');
    })->name('b2b.panel');
});

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