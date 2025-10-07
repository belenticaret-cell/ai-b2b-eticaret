@extends('admin.layouts.app')

@section('title', 'Admin Panel')
@section('page-title', 'Admin Panel')

@section('content')
@php
    // Dashboard için gerekli değişkenleri tanımlayalım
    $urunSayisi = \App\Models\Urun::count();
    $bayiSayisi = \App\Models\Bayi::count();
    $kategoriSayisi = \App\Models\Kategori::count();
    $siparisler = \App\Models\Siparis::count();
    $bugununSiparisleri = \App\Models\Siparis::whereDate('created_at', today())->count();
    $sayfaSayisi = \App\Models\SayfaIcerik::where('durum', true)->count();
    $sonSiparisler = \App\Models\Siparis::with('kullanici')->latest()->limit(5)->get();
    $siteAktif = \App\Models\SiteAyar::where('anahtar', 'site_aktif')->value('deger') == '1';
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

    <!-- Ana İçerik Alanı -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <!-- Sol Panel - Ana Admin Fonksiyonları -->
        <div class="xl:col-span-3 space-y-6">
            <!-- Ana Sistem Kartları -->
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">🏛️ Ana Sistem Yönetimi</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Ürün Yönetimi -->
                    <a href="{{ route('admin.urun.create') }}" 
                       class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-blue-200 group">
                        <div class="text-center">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📦</div>
                            <h4 class="font-semibold text-gray-800 text-sm">Yeni Ürün</h4>
                            <div class="mt-2 pt-2 border-t border-blue-200">
                                <p class="text-lg font-bold text-blue-600">{{ $urunSayisi }}</p>
                                <p class="text-xs text-gray-600">toplam ürün</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- E-Ticaret Site Yönetimi -->
                    <a href="{{ route('admin.site-ayar.index') }}" 
                       class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-indigo-200 group">
                        <div class="text-center">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏪</div>
                            <h4 class="font-semibold text-gray-800 text-sm">Site Yönetimi</h4>
                            <div class="mt-2 pt-2 border-t border-indigo-200">
                                <p class="text-lg font-bold text-indigo-600">{{ $siteAktif ? 'Aktif' : 'Pasif' }}</p>
                                <p class="text-xs text-gray-600">e-ticaret sitesi</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Vitrin Yönetimi -->
                    <a href="{{ route('admin.vitrin.index') }}" 
                       class="bg-gradient-to-br from-pink-50 to-pink-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-pink-200 group">
                        <div class="text-center">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎨</div>
                            <h4 class="font-semibold text-gray-800 text-sm">Vitrin Yönetimi</h4>
                            <div class="mt-2 pt-2 border-t border-pink-200">
                                <p class="text-lg font-bold text-pink-600">Pazarlama</p>
                                <p class="text-xs text-gray-600">ana sayfa içeriği</p>
                            </div>
                        </div>
                    </a>

                    <!-- Bayi Yönetimi -->
                    <a href="{{ route('admin.bayi.index') }}" 
                       class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-purple-200 group">
                        <div class="text-center">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">👥</div>
                            <h4 class="font-semibold text-gray-800 text-sm">B2B Bayiler</h4>
                            <div class="mt-2 pt-2 border-t border-purple-200">
                                <p class="text-lg font-bold text-purple-600">{{ $bayiSayisi }}</p>
                                <p class="text-xs text-gray-600">kayıtlı bayi</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- İstatistikler -->
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Sistem İstatistikleri</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl shadow border">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Ürünler</p>
                                <p class="text-lg font-semibold">{{ number_format($urunSayisi) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow border">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Bayiler</p>
                                <p class="text-lg font-semibold">{{ number_format($bayiSayisi) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow border">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Siparişler</p>
                                <p class="text-lg font-semibold">{{ number_format($siparisler) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl shadow border">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Kategoriler</p>
                                <p class="text-lg font-semibold">{{ number_format($kategoriSayisi) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Son Aktiviteler -->
            <div class="bg-white rounded-xl shadow-md border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">📋 Son Aktiviteler</h3>
                </div>
                <div class="p-6">
                    @forelse($sonSiparisler as $siparis)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Sipariş #{{ $siparis->id }}</p>
                                    <p class="text-xs text-gray-500">{{ $siparis->kullanici->name ?? 'Bilinmeyen' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">₺{{ number_format($siparis->toplam_tutar ?? 0, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $siparis->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">Henüz aktivite yok</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sağ Panel - Geliştirme Aşaması -->
        <div class="xl:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Geliştirme Aşaması Header -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-100 p-4 rounded-xl border border-amber-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-200 rounded-lg">
                            <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-amber-800">🚧 Geliştirme Aşaması</h3>
                            <p class="text-xs text-amber-600">Aktif projeler</p>
                        </div>
                    </div>
                </div>

                <!-- B2B Sistem Geliştirmeleri -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 bg-green-50">
                        <h4 class="font-semibold text-green-800 text-sm">✅ Tamamlanan</h4>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">B2B Panel</p>
                                <p class="text-xs text-gray-500">Dashboard, giriş sistemi</p>
                                <a href="{{ route('b2b.dashboard') }}" class="text-xs text-green-600 hover:underline">→ Test Et</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Vitrin Yönetimi</p>
                                <p class="text-xs text-gray-500">Ana sayfa kontrolü</p>
                                <a href="{{ route('admin.vitrin.index') }}" class="text-xs text-green-600 hover:underline">→ Yönet</a>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Site Ayarları</p>
                                <p class="text-xs text-gray-500">E-ticaret kontrolü</p>
                                <a href="{{ route('admin.site-ayar.index') }}" class="text-xs text-green-600 hover:underline">→ Ayarla</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Geliştirme Aşamasındakiler -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 bg-yellow-50">
                        <h4 class="font-semibold text-yellow-800 text-sm">⚠️ Geliştirme Aşaması</h4>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 animate-pulse"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">B2B Ürün Listesi</p>
                                <p class="text-xs text-gray-500">Bayi özel fiyatlar</p>
                                <button onclick="alert('Henüz hazır değil!')" class="text-xs text-yellow-600 hover:underline">→ Geliştir</button>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 animate-pulse"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Toplu Sipariş</p>
                                <p class="text-xs text-gray-500">Excel upload sistemi</p>
                                <button onclick="alert('Henüz hazır değil!')" class="text-xs text-yellow-600 hover:underline">→ Geliştir</button>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 animate-pulse"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Cari Hesap</p>
                                <p class="text-xs text-gray-500">Finansal takip</p>
                                <button onclick="alert('Henüz hazır değil!')" class="text-xs text-yellow-600 hover:underline">→ Geliştir</button>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 animate-pulse"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Platform Entegrasyonları</p>
                                <p class="text-xs text-gray-500">Trendyol, Hepsiburada</p>
                                <a href="{{ route('admin.moduller.entegrasyon') }}" class="text-xs text-yellow-600 hover:underline">→ Geliştir</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planlanan Özellikler -->
                <div class="bg-white rounded-xl shadow border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 bg-blue-50">
                        <h4 class="font-semibold text-blue-800 text-sm">🔮 Planlanan</h4>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">AI Ürün Önerisi</p>
                                <p class="text-xs text-gray-500">GPT entegrasyonu</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Mobil Uygulama</p>
                                <p class="text-xs text-gray-500">React Native</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Raporlama</p>
                                <p class="text-xs text-gray-500">Analitik dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hızlı Eylemler -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-100 rounded-xl p-4 border border-indigo-200">
                    <h4 class="font-semibold text-indigo-800 text-sm mb-3">⚡ Hızlı Eylemler</h4>
                    <div class="space-y-2">
                        <button onclick="window.dispatchEvent(new CustomEvent('open-feature-modal'))" 
                                class="w-full text-left px-3 py-2 bg-white rounded-lg border border-indigo-200 hover:bg-indigo-50 text-sm text-indigo-700">
                            + Yeni Özellik Ekle
                        </button>
                        
                        <a href="{{ route('vitrin.index') }}" target="_blank"
                           class="w-full text-left px-3 py-2 bg-white rounded-lg border border-indigo-200 hover:bg-indigo-50 text-sm text-indigo-700 block">
                            👁️ Vitrin Önizle
                        </a>
                        
                        <a href="{{ route('b2b.login') }}" 
                           class="w-full text-left px-3 py-2 bg-white rounded-lg border border-indigo-200 hover:bg-indigo-50 text-sm text-indigo-700 block">
                            🏢 B2B Girişi
                        </a>
                    </div>
                </div>
            </div>
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
             class="fixed inset-0 bg-gray-500 bg-opacity-75" 
             @click="showFeatureModal = false"></div>

        <div x-show="showFeatureModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-center mb-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Yeni Özellik Ekle</h3>
                        <p class="text-sm text-gray-500">Geliştirme listesine yeni özellik ekleyin</p>
                    </div>
                </div>
                
                <div x-data="featureForm()">
                    <form @submit.prevent="submitFeature()">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Özellik Türü</label>
                                <select x-model="formData.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seçiniz</option>
                                    <option value="b2b">B2B Özelliği</option>
                                    <option value="ecommerce">E-Ticaret</option>
                                    <option value="admin">Admin Panel</option>
                                    <option value="integration">Entegrasyon</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Özellik Adı</label>
                                <input type="text" x-model="formData.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Örn: Toplu Fiyat Güncelleme">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                                <textarea x-model="formData.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Özellik detaylarını açıklayın..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Öncelik</label>
                                <select x-model="formData.priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low">Düşük</option>
                                    <option value="medium">Orta</option>
                                    <option value="high">Yüksek</option>
                                    <option value="urgent">Acil</option>
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
            // Toast notification gösterme
            console.log(`${type}: ${message}`);
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
                alert('Lütfen özellik türü ve adını giriniz!');
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
            
            alert(`Özellik "${this.formData.name}" başarıyla eklendi!`);
            
            // Form reset
            this.formData = {
                type: '',
                name: '',
                description: '',
                priority: 'medium'
            };
            
            // Modal close
            this.$dispatch('close-feature-modal');
            document.querySelector('[x-data*="showFeatureModal"]').__x.$data.showFeatureModal = false;
        }
    }
}

// Sayfa yüklendiğinde dashboard animasyonları
document.addEventListener('DOMContentLoaded', function() {
    // Kartların animasyonlu giriş efekti
    const cards = document.querySelectorAll('.transform');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection