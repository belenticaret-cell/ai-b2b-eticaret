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
    $bugunSenkron = $platformStats['son_24_saat_senkron'] ?? 0;
    $sayfaSayisi = \App\Models\SayfaIcerik::where('durum', true)->count();
    $sonSiparisler = \App\Models\Siparis::with('kullanici')->latest()->limit(5)->get();
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
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4">
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
                
                <!-- Sipariş Yönetimi -->
                <a href="#" onclick="alert('Sipariş yönetimi yakında!')" 
                   class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-orange-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🛍️</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Siparişler</h4>
                        <div class="mt-2 pt-2 border-t border-orange-200">
                            <p class="text-lg font-bold text-orange-600">{{ $siparisler }}</p>
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
                <a href="{{ route('admin.moduller.entegrasyon') }}" 
                   class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-green-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🔗</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Entegrasyon</h4>
                        <div class="mt-2 pt-2 border-t border-green-200">
                            <p class="text-lg font-bold text-green-600">{{ $platformStats['aktif_magaza'] ?? 0 }}</p>
                            <p class="text-xs text-gray-600">aktif mağaza</p>
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
                
                <!-- Kategori Yönetimi -->
                <a href="{{ route('admin.kategori.index') }}" 
                   class="bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-teal-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📂</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Kategoriler</h4>
                        <div class="mt-2 pt-2 border-t border-teal-200">
                            <p class="text-lg font-bold text-teal-600">{{ $kategoriSayisi }}</p>
                            <p class="text-xs text-gray-600">kategori</p>
                        </div>
                    </div>
                </a>
                
                <!-- Mağaza Listesi -->
                <a href="{{ route('admin.magaza.index') }}" 
                   class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-indigo-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏪</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Mağazalar</h4>
                        <div class="mt-2 pt-2 border-t border-indigo-200">
                            <p class="text-lg font-bold text-indigo-600">{{ $platformStats['toplam_magaza'] ?? 0 }}</p>
                            <p class="text-xs text-gray-600">platform</p>
                        </div>
                    </div>
                </a>
                
                <!-- API Test -->
                <a href="#" onclick="alert('API Test özelliği yakında!')" 
                   class="bg-gradient-to-br from-cyan-50 to-cyan-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-cyan-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🧪</div>
                        <h4 class="font-semibold text-gray-800 text-sm">API Test</h4>
                        <div class="mt-2 pt-2 border-t border-cyan-200">
                            <p class="text-lg font-bold text-cyan-600">%{{ $errorStats['basarili_senkron_orani'] ?? 95 }}</p>
                            <p class="text-xs text-gray-600">başarı oranı</p>
                        </div>
                    </div>
                </a>
                
                <!-- Site Önizleme -->
                <a href="{{ route('vitrin.index') }}" target="_blank"
                   class="bg-gradient-to-br from-pink-50 to-pink-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-pink-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🌐</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Site Önizleme</h4>
                        <div class="mt-2 pt-2 border-t border-pink-200">
                            <p class="text-lg font-bold text-pink-600">
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
                <a href="{{ route('admin.site-ayar.index') }}" 
                   class="bg-gradient-to-br from-emerald-50 to-teal-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-emerald-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">⚙️</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Site Ayarları</h4>
                        <div class="mt-2 pt-2 border-t border-emerald-200">
                            <p class="text-lg font-bold text-emerald-600">
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
                <a href="#" x-data x-on:click="$dispatch('open-feature-modal')"
                   class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-amber-200 group cursor-pointer">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">✨</div>
                        <h4 class="font-semibold text-gray-800 text-sm">Yeni Özellik</h4>
                        <div class="mt-2 pt-2 border-t border-amber-200">
                            <p class="text-lg font-bold text-amber-600">+</p>
                            <p class="text-xs text-gray-600">özellik ekle</p>
                        </div>
                    </div>
                </a>
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
                        <a href="{{ route('admin.moduller.entegrasyon') }}" 
                           class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:shadow-lg transition-all text-center">
                            🔗 Entegrasyon
                        </a>
                        <a href="#" onclick="alert('API Test özelliği yakında!')" 
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
                    <a href="#" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Tümünü Gör →</a>
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
                            <p class="text-xs text-gray-500">{{ $aktivite['zaman']->diffForHumans() }}</p>
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
            <h3 class="text-lg font-bold text-gray-900">🧾 Son Siparişler</h3>
            <a href="#" onclick="alert('Sipariş yönetimi yakında!')" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Tümü →</a>
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
            
            // Success notification
            this.showNotification(`🎉 "${this.formData.name}" özelliği eklendi!`, 'success');
            
            // Reset form and close modal
            this.formData = { type: '', name: '', description: '', priority: 'medium' };
            this.$dispatch('close-modal');
            
            // Reload page to show updates
            setTimeout(() => {
                location.reload();
            }, 1000);
        },
        
        showNotification(message, type) {
            // Simple notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
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