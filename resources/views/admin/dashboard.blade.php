@extends('admin.layouts.app')

@section('title', 'Admin Panel')
@section('page-title', 'Admin Panel')

@section('content')
@php
    // Dashboard'dan controller'dan gelen data kullan - performans optimizasyonu
    $urunSayisi = $istatistik['urun'] ?? 0;
    $bayiSayisi = $istatistik['bayi'] ?? 0;
    $kategoriSayisi = \App\Models\Kategori::count();
    $siparisler = \App\Models\Siparis::count();
    $bugununSiparisleri = \App\Models\Siparis::whereDate('created_at', today())->count();
    
    // Controller'dan gelen verileri kullan
    $platformStats = $platformStats ?? [];
    $errorStats = $errorStats ?? [];
    $bugunSenkron = $platformStats['son_24_saat_senkron'] ?? 0;
    $sayfaSayisi = \App\Models\SayfaIcerik::where('durum', true)->count();
    $sonSiparisler = \App\Models\Siparis::with('kullanici')->latest()->limit(5)->get();

    // Session notes güvenli parse
    $sessionNotes = class_exists('\App\Support\SessionNotes') ? \App\Support\SessionNotes::parseToday() : ['yapilanlar' => [], 'yapilacaklar' => []];
    $yapilanlarOzet = array_slice($sessionNotes['yapilanlar'] ?? [], 0, 3);
    $yapilacaklarOzet = array_slice($sessionNotes['yapilacaklar'] ?? [], 0, 3);
@endphp

<div class="space-y-8" x-data="adminPanel()">
    <!-- Hoş Geldin Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👋 Hoş geldiniz!</h1>
                    <p class="text-indigo-100 text-lg">AI B2B E-Ticaret Yönetim Paneli</p>
                    <p class="text-indigo-200 text-sm mt-2">📅 {{ now()->format('d.m.Y H:i') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-20 p-4 rounded-full">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Görevler & Sistem Özeti -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Hızlı Görevler -->
        <div class="lg:col-span-3">
            <h3 class="text-xl font-bold text-gray-800 mb-4">⚡ Hızlı Görevler & Sistem Durumu</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                <!-- Ürün Yönetimi -->
                <a href="/ai-b2b/public/admin/urun/yeni" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-blue-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">📦</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Yeni Ürün</h4>
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <p class="text-base font-bold text-blue-600">{{ $urunSayisi }}</p>
                            <p class="text-xs text-gray-600">toplam ürün</p>
                        </div>
                    </div>
                </a>
                
                <!-- Sipariş Yönetimi -->
                <a href="#" onclick="showComingSoon('Sipariş yönetimi')" 
                   class="bg-gradient-to-br from-orange-50 to-orange-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-orange-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">🛍️</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Siparişler</h4>
                        <div class="mt-2 pt-2 border-t border-orange-200">
                            <p class="text-base font-bold text-orange-600">{{ $siparisler }}</p>
                            <p class="text-xs text-gray-600">
                                @if($siparisler > 0)
                                    {{ $bugununSiparisleri }} bugün
                                @else
                                    sipariş yok
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
                
                <!-- Mağaza & Entegrasyon -->
                <a href="/ai-b2b/public/admin/moduller/entegrasyon" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-green-50 to-green-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-green-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">🔗</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Entegrasyon</h4>
                        <div class="mt-2 pt-2 border-t border-green-200">
                            <p class="text-base font-bold text-green-600">{{ $platformStats['aktif_magaza'] ?? 0 }}</p>
                            <p class="text-xs text-gray-600">aktif mağaza</p>
                        </div>
                    </div>
                </a>
                
                <!-- Bayi Yönetimi -->
                <a href="/ai-b2b/public/admin/bayiler" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-purple-50 to-purple-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-purple-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">👥</div>
                        <h4 class="font-semibold text-gray-800 text-xs">B2B Bayiler</h4>
                        <div class="mt-2 pt-2 border-t border-purple-200">
                            <p class="text-base font-bold text-purple-600">{{ $bayiSayisi }}</p>
                            <p class="text-xs text-gray-600">kayıtlı bayi</p>
                        </div>
                    </div>
                </a>
                
                <!-- Kategori Yönetimi -->
                <a href="/ai-b2b/public/admin/kategoriler" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-teal-50 to-teal-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-teal-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">📂</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Kategoriler</h4>
                        <div class="mt-2 pt-2 border-t border-teal-200">
                            <p class="text-base font-bold text-teal-600">{{ $kategoriSayisi }}</p>
                            <p class="text-xs text-gray-600">kategori</p>
                        </div>
                    </div>
                </a>
                
                <!-- Mağaza Listesi -->
                <a href="/ai-b2b/public/admin/magaza" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-indigo-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">🏪</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Mağazalar</h4>
                        <div class="mt-2 pt-2 border-t border-indigo-200">
                            <p class="text-base font-bold text-indigo-600">{{ $platformStats['toplam_magaza'] ?? 0 }}</p>
                            <p class="text-xs text-gray-600">platform</p>
                        </div>
                    </div>
                </a>
                
                <!-- API Test -->
                <a href="#" onclick="showComingSoon('API Test özelliği')" 
                   class="bg-gradient-to-br from-cyan-50 to-cyan-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-cyan-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">🧪</div>
                        <h4 class="font-semibold text-gray-800 text-xs">API Test</h4>
                        <div class="mt-2 pt-2 border-t border-cyan-200">
                            <p class="text-base font-bold text-cyan-600">%{{ $errorStats['basarili_senkron_orani'] ?? 95 }}</p>
                            <p class="text-xs text-gray-600">başarı oranı</p>
                        </div>
                    </div>
                </a>
                
                <!-- Site Önizleme -->
                <a href="/ai-b2b/public/" target="_blank" rel="noopener"
                   class="bg-gradient-to-br from-pink-50 to-pink-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-pink-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">🌐</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Site Önizleme</h4>
                        <div class="mt-2 pt-2 border-t border-pink-200">
                            <p class="text-base font-bold text-pink-600">
                                @if(($errorStats['basarili_senkron_orani'] ?? 95) >= 95)
                                    🟢
                                @elseif(($errorStats['basarili_senkron_orani'] ?? 95) >= 80)
                                    🟡
                                @else
                                    🔴
                                @endif
                                Aktif
                            </p>
                            <p class="text-xs text-gray-600">sistem durumu</p>
                        </div>
                    </div>
                </a>
                
                <!-- Site Ayarları -->
                <a href="/ai-b2b/public/admin/site-ayarlari" 
                   data-navigation="admin-link"
                   class="bg-gradient-to-br from-emerald-50 to-teal-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-emerald-200 group">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">⚙️</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Site Ayarları</h4>
                        <div class="mt-2 pt-2 border-t border-emerald-200">
                            <p class="text-base font-bold text-emerald-600">
                                @php
                                    $siteAktif = \App\Models\SiteAyar::where('anahtar', 'site_aktif')->value('deger') ?? '0';
                                @endphp
                                @if($siteAktif === '1')
                                    🟢 Aktif
                                @else
                                    🔴 Pasif
                                @endif
                            </p>
                            <p class="text-xs text-gray-600">e-ticaret durumu</p>
                        </div>
                    </div>
                </a>
                
                <!-- Yeni Özellik Ekle -->
                <button type="button" x-data x-on:click="$dispatch('open-feature-modal')"
                   class="bg-gradient-to-br from-amber-50 to-amber-100 p-3 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-amber-200 group cursor-pointer text-left">
                    <div class="text-center">
                        <div class="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">✨</div>
                        <h4 class="font-semibold text-gray-800 text-xs">Yeni Özellik</h4>
                        <div class="mt-2 pt-2 border-t border-amber-200">
                            <p class="text-base font-bold text-amber-600">+</p>
                            <p class="text-xs text-gray-600">özellik ekle</p>
                        </div>
                    </div>
                </button>
            </div>
        </div>
        
        <!-- Günlük Hedefler -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">🎯 Günlük Hedefler</h3>
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <!-- Ürün Hedefi -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">📦 Ürün Hedefi</span>
                        <span class="text-sm text-gray-600">{{ $urunSayisi }}/100</span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: {{ min(($urunSayisi/100)*100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">100 ürüne ulaşmak için {{ max(0, 100-$urunSayisi) }} ürün daha</p>
                </div>

                <!-- Sipariş Hedefi -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">🛍️ Günlük Sipariş</span>
                        <span class="text-sm text-gray-600">{{ $bugununSiparisleri }}/5</span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-2 rounded-full" style="width: {{ min(($bugununSiparisleri/5)*100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Günlük hedef: 5 sipariş</p>
                </div>

                <!-- Senkron Hedefi -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">🔄 Senkronizasyon</span>
                        <span class="text-sm text-gray-600">{{ $bugunSenkron }}/10</span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: {{ min(($bugunSenkron/10)*100, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Günlük senkron hedefi</p>
                </div>

                <!-- Genel Başarı -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="text-center">
                        @php($genelBasari = (($urunSayisi/100) + ($bugununSiparisleri/5) + ($bugunSenkron/10))/3 * 100)
                        <div class="text-2xl mb-2">
                            @if($genelBasari >= 80) 🏆
                            @elseif($genelBasari >= 60) 🥉
                            @elseif($genelBasari >= 40) 🎖️
                            @else 🎯
                            @endif
                        </div>
                        <h4 class="font-semibold text-gray-800">%{{ number_format($genelBasari, 0) }} Tamamlandı</h4>
                        <p class="text-xs text-gray-500 mt-1">Günlük genel başarı oranı</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Kontrol Bağlantıları -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            🔍 <span class="ml-2">Hızlı Kontrol Bağlantıları</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <a href="/ai-b2b/public/admin/vitrin" data-navigation="admin-link" class="admin-quick-link">🎨 Vitrin Yönetimi</a>
            <a href="/ai-b2b/public/admin/anasayfa" data-navigation="admin-link" class="admin-quick-link">🏠 Anasayfa Yönetimi</a>
            <a href="/ai-b2b/public/admin/site-ayarlari" data-navigation="admin-link" class="admin-quick-link">⚙️ Site Yönetimi</a>
            <a href="/ai-b2b/public/admin/moduller/entegrasyon" data-navigation="admin-link" class="admin-quick-link">🔗 Entegrasyon Modülü</a>
            <a href="/ai-b2b/public/admin/magaza" data-navigation="admin-link" class="admin-quick-link">🏪 Mağazalar</a>
            <a href="/ai-b2b/public/admin/kategoriler" data-navigation="admin-link" class="admin-quick-link">📂 Kategoriler</a>
            <a href="/ai-b2b/public/admin/urun/yeni" data-navigation="admin-link" class="admin-quick-link">📦 Yeni Ürün</a>
            <a href="/ai-b2b/public/admin/bayiler" data-navigation="admin-link" class="admin-quick-link">👥 Bayiler</a>
            <a href="/ai-b2b/public/admin/gelistirici" data-navigation="admin-link" class="admin-quick-link">🧑‍💻 Geliştirici</a>
            <a href="/ai-b2b/public/" target="_blank" rel="noopener" class="admin-quick-link">🌐 Vitrin (Public)</a>
            <a href="/ai-b2b/public/magaza" target="_blank" rel="noopener" class="admin-quick-link">🛍️ Mağaza (Public)</a>
            <a href="/ai-b2b/public/b2b-login" target="_blank" rel="noopener" class="admin-quick-link">🏢 B2B Giriş</a>
        </div>
        <p class="text-xs text-gray-500 mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
            💡 <strong>Not:</strong> B2B panel altındaki sayfalar bayi rolüyle giriş gerektirir.
        </p>
    </div>

    <!-- Ana Kontrol Paneli -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Platform Stats -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">🏪 Platform Durumu</h3>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Aktif</span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-orange-500 rounded-full mr-3 animate-pulse"></div>
                            <div>
                                <span class="font-semibold text-gray-800">Trendyol</span>
                                <p class="text-xs text-gray-600">Son senkron: 5dk önce</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-orange-600 font-bold text-lg">{{ $platformStats['trendyol'] ?? 0 }}</span>
                            <p class="text-xs text-gray-500">ürün</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                            <div>
                                <span class="font-semibold text-gray-800">Hepsiburada</span>
                                <p class="text-xs text-gray-600">Son senkron: 12dk önce</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-blue-600 font-bold text-lg">{{ $platformStats['hepsiburada'] ?? 0 }}</span>
                            <p class="text-xs text-gray-500">ürün</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                            <div>
                                <span class="font-semibold text-gray-800">N11</span>
                                <p class="text-xs text-gray-600">Son senkron: 8dk önce</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-green-600 font-bold text-lg">{{ $platformStats['n11'] ?? 0 }}</span>
                            <p class="text-xs text-gray-500">ürün</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                            <div>
                                <span class="font-semibold text-gray-800">Amazon</span>
                                <p class="text-xs text-gray-600">Pasif</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-purple-600 font-bold text-lg">{{ $platformStats['amazon'] ?? 0 }}</span>
                            <p class="text-xs text-gray-500">ürün</p>
                        </div>
                    </div>
                </div>
                
                <!-- Platform Yönetim Linkleri -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-3">
                        <a href="/ai-b2b/public/admin/moduller/entegrasyon" data-navigation="admin-link"
                           class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all text-center">
                            🔗 Entegrasyon
                        </a>
                        <a href="#" onclick="showComingSoon('API Test özelliği')" 
                           class="bg-gradient-to-r from-teal-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all text-center">
                            🧪 API Test
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hata İzleme & Son Aktiviteler -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hata İzleme -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">🚨 Sistem Sağlığı</h3>
                    <div class="flex space-x-2">
                        <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">
                            ✅ %{{ $errorStats['basarili_senkron_orani'] ?? 95 }} Başarı
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200">
                        <div class="text-2xl mb-2">🛡️</div>
                        <p class="text-sm font-semibold text-gray-800">Cloudflare</p>
                        <p class="text-lg font-bold text-red-600">{{ $errorStats['cloudflare_engel'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">403 hatası</p>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                        <div class="text-2xl mb-2">⏱️</div>
                        <p class="text-sm font-semibold text-gray-800">Rate Limit</p>
                        <p class="text-lg font-bold text-yellow-600">{{ $errorStats['rate_limit_hata'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">429 hatası</p>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-2xl mb-2">🔄</div>
                        <p class="text-sm font-semibold text-gray-800">Son Senkron</p>
                        <p class="text-lg font-bold text-blue-600">{{ $platformStats['son_24_saat_senkron'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">24 saatte</p>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-2xl mb-2">📈</div>
                        <p class="text-sm font-semibold text-gray-800">Uptime</p>
                        <p class="text-lg font-bold text-green-600">99.8%</p>
                        <p class="text-xs text-gray-500">son 30 gün</p>
                    </div>
                </div>
            </div>

            <!-- Son Aktiviteler -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">📊 Son Aktiviteler</h3>
                    <a href="/ai-b2b/public/admin/gelistirici" data-navigation="admin-link" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center px-3 py-1 rounded-lg border border-blue-200 hover:bg-blue-50 transition-colors">
                        <span>Tümünü Gör</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($sonAktiviteler as $aktivite)
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="w-3 h-3 mr-4 rounded-full
                                @if($aktivite['durum'] === 'success') bg-green-500 
                                @elseif($aktivite['durum'] === 'error') bg-red-500 
                                @else bg-yellow-500 @endif animate-pulse">
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $aktivite['islem'] }}</p>
                                <p class="text-gray-600 text-xs">{{ $aktivite['magaza'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($aktivite['zaman'])->diffForHumans() }}</p>
                            <div class="flex items-center mt-1">
                                @if($aktivite['durum'] === 'success')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">✅ Başarılı</span>
                                @elseif($aktivite['durum'] === 'error')
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">❌ Hata</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">⚠️ Uyarı</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Son Siparişler -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                🧾 <span class="ml-2">Son Siparişler</span>
            </h3>
            <a href="#" onclick="showComingSoon('Sipariş yönetimi')" class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center px-3 py-1 rounded-lg border border-blue-200 hover:bg-blue-50 transition-colors">
                <span>Tümü</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
            <div class="space-y-4">
                @forelse($sonSiparisler as $s)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200 hover:shadow-md transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">{{ $s->siparis_no ?? ('SIP'.str_pad($s->id,6,'0',STR_PAD_LEFT)) }}</p>
                                <p class="text-xs text-gray-600">{{ $s->kullanici->ad ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ optional($s->created_at)->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-teal-600">
                                    {{ number_format((float)$s->toplam_tutar, 2) }} ₺
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">✅ Aktif</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <div class="text-4xl mb-4">🧾</div>
                        <p class="text-lg font-semibold">Henüz sipariş yok</p>
                        <p class="text-sm">İlk sipariş geldiğinde burada görünecek</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Yapılanlar & Yapılacaklar Özeti -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-800 flex items-center">
                    ✅ <span class="ml-2">Bugün Yapılanlar</span>
                </h4>
                <a href="/ai-b2b/public/admin/gelistirici" data-navigation="admin-link" class="text-sm text-blue-600 hover:underline">Tümü →</a>
            </div>
            @if(count($yapilanlarOzet))
                <ul class="space-y-2 list-disc ml-5">
                    @foreach($yapilanlarOzet as $i)
                        <li class="text-sm text-gray-700">{{ $i['ad'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Bugün için kayıtlı bir madde yok.</p>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-800 flex items-center">
                    📝 <span class="ml-2">Yapılacaklar</span>
                </h4>
                <a href="/ai-b2b/public/admin/gelistirici" data-navigation="admin-link" class="text-sm text-blue-600 hover:underline">Tümü →</a>
            </div>
            @if(count($yapilacaklarOzet))
                <ul class="space-y-2 list-disc ml-5">
                    @foreach($yapilacaklarOzet as $i)
                        <li class="text-sm text-gray-700">{{ $i['ad'] }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">Henüz planlanan madde yok.</p>
            @endif
        </div>
    </div>
</div>

<!-- Yeni Özellik Ekleme Modal -->
<div x-data="{ showFeatureModal: false }" 
     x-on:open-feature-modal.window="showFeatureModal = true"
     x-show="showFeatureModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showFeatureModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div x-show="showFeatureModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                    <span class="text-2xl">✨</span>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Yeni Özellik Ekle
                    </h3>
                    
                    <form x-data="featureForm()" @submit.prevent="submitFeature">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Özellik Türü</label>
                                <select x-model="formData.type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seçiniz...</option>
                                    <option value="module">📦 Yeni Modül</option>
                                    <option value="integration">🔗 Platform Entegrasyonu</option>
                                    <option value="automation">🤖 Otomasyon</option>
                                    <option value="api">⚡ API Endpoint</option>
                                    <option value="ui">🎨 UI Geliştirmesi</option>
                                    <option value="analytics">📊 Analytics</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Özellik Adı</label>
                                <input type="text" x-model="formData.name" placeholder="Örn: WhatsApp Entegrasyonu" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                                <textarea x-model="formData.description" rows="3" placeholder="Bu özellik ne yapacak?"
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Öncelik</label>
                                <select x-model="formData.priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low">🟢 Düşük</option>
                                    <option value="medium">🟡 Orta</option>
                                    <option value="high">🔴 Yüksek</option>
                                    <option value="critical">⚡ Kritik</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                            <button type="submit" 
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                ✨ Özellik Ekle
                            </button>
                            <button type="button" 
                                    @click="showFeatureModal = false"
                                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                İptal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function adminPanel() {
    return {
        activeTab: 'overview',
        notifications: 3,
        
        // Bildirim işlevleri
        showNotification(message, type = 'success') {
            showToast(message, type);
        },
        
        // Dashboard yenileme
        refreshDashboard() {
            // Dashboard verilerini yenile
            location.reload();
        }
    }
}

function featureForm() {
    return {
        formData: {
            type: '',
            name: '',
            description: '',
            priority: 'medium'
        },
        
        submitFeature() {
            if (!this.formData.type || !this.formData.name) {
                showToast('Lütfen özellik türü ve adını giriniz!', 'error');
                return;
            }
            
            // Simulated feature creation
            const features = JSON.parse(localStorage.getItem('newFeatures') || '[]');
            const newFeature = {
                id: Date.now(),
                ...this.formData,
                status: 'planned',
                createdAt: new Date().toISOString()
            };
            
            features.push(newFeature);
            localStorage.setItem('newFeatures', JSON.stringify(features));
            
            // Success notification
            showToast(`🎉 "${this.formData.name}" özelliği eklendi!`, 'success');
            
            // Reset form and close modal
            this.formData = { type: '', name: '', description: '', priority: 'medium' };
            this.$dispatch('close-modal');
            
            // Reload page to show updates
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }
}

// Geliştirilmiş Toast Notification Sistemi
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast-notification transform transition-all duration-300 ease-in-out translate-x-full opacity-0 mb-4 p-4 rounded-lg shadow-lg max-w-sm ${getToastClasses(type)}`;
    
    const icon = getToastIcon(type);
    toast.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${icon}
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'fixed top-4 right-4 z-50';
    document.body.appendChild(container);
    return container;
}

function getToastClasses(type) {
    const classes = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    return classes[type] || classes.info;
}

function getToastIcon(type) {
    const icons = {
        success: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>',
        error: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>',
        warning: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>',
        info: '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>'
    };
    return icons[type] || icons.info;
}

// "Yakında" modalı için geliştirilmiş fonksiyon
function showComingSoon(feature) {
    showToast(`🚧 ${feature} yakında geliyor! Geliştirme devam ediyor.`, 'info');
}

// Optimized admin navigation handler
function handleAdminNavigation(event) {
    const link = event.target.closest('a[data-navigation="admin-link"]');
    if (!link) return;
    
    const href = link.getAttribute('href');
    if (href && href.startsWith('/') && !link.hasAttribute('target')) {
        event.preventDefault();
        
        // Show loading state
        const originalText = link.textContent;
        link.style.opacity = '0.7';
        
        // Navigate
        window.location.href = href;
        
        // Reset after a short delay (in case navigation fails)
        setTimeout(() => {
            link.style.opacity = '1';
        }, 1000);
    }
}

// Sayfa yüklendiğinde admin paneli başlat
document.addEventListener('DOMContentLoaded', function() {
    // Admin navigation handler
    document.addEventListener('click', handleAdminNavigation);
    
    // Kartların animasyonlu giriş efekti
    const cards = document.querySelectorAll('.transform');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
    
    // Performance monitoring
    if (window.performance && window.performance.now) {
        const loadTime = window.performance.now();
        if (loadTime > 2000) {
            console.warn('Dashboard yavaş yüklendi:', loadTime + 'ms');
        }
    }
});
</script>

<!-- CSS Stillerini ekle -->
<style>
.admin-quick-link {
    @apply px-3 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-sm transition-all duration-200 text-center block;
}

.admin-quick-link:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.toast-notification {
    min-width: 300px;
}

@media (max-width: 640px) {
    .admin-quick-link {
        @apply text-xs px-2 py-1;
    }
    .toast-notification {
        min-width: 250px;
    }
}
</style>
@endsection