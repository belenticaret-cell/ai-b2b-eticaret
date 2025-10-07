@extends('admin.layouts.app')

@section('title', 'Admin Panel')
@section('page-title', 'Admin Panel')

@section('content')
@php
    // Dashboard için gerekli d        <!-- Günlük Hedefler -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">🎯 Günlük Hedefler</h3>
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                <!-- Ürün Hedefi -->
                <div class="mb-6">tanımlayalım
    $urunSayisi = \App\Models\Urun::count();
    $bayiSayisi = \App\Models\Bayi::count();
    $kategoriSayisi = \App\Models\Kategori::count();
    $siparisler = \App\Models\Siparis::count();
    $bugununSiparisleri = \App\Models\Siparis::whereDate('created_at', today())->count();
    $bugunSenkron = $platformStats['son_24_saat_senkron'] ?? 0;
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
                
                <!-- Sipariş Yönetimi -->
                <a href="{{ route('admin.siparis.index') }}" 
                   class="bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:scale-105 border border-orange-200 group">
                    <div class="text-center">
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">�️</div>
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
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">�</div>
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
                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">�</div>
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
                <a href="{{ route('admin.api-test.index') }}" 
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
            </div>
        </div>
        
        <!-- Günlük Hedefler -->
        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">� Günlük Hedefler</h3>
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                @php
                    $urunSayisi = \App\Models\Urun::count();
                    $bugununSiparisleri = \App\Models\Siparis::whereDate('created_at', today())->count();
                    $bugunSenkron = $platformStats['son_24_saat_senkron'] ?? 0;
                @endphp
                
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

    <!-- Gelişmiş İstatistikler -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Toplam Ürün</p>
                    <p class="text-3xl font-bold mb-1">{{ $urunSayisi }}</p>
                    <div class="flex items-center">
                        <span class="text-xs bg-blue-400 bg-opacity-50 px-2 py-1 rounded-full">
                            📦 Aktif
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Aktif Mağazalar</p>
                    <p class="text-3xl font-bold mb-1">{{ $platformStats['aktif_magaza'] ?? 0 }}</p>
                    <div class="flex items-center">
                        <span class="text-xs text-green-100">/ {{ $platformStats['toplam_magaza'] ?? 0 }} toplam</span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Toplam Bayiler</p>
                    <p class="text-3xl font-bold mb-1">{{ $bayiSayisi }}</p>
                    <div class="flex items-center">
                        <span class="text-xs bg-purple-400 bg-opacity-50 px-2 py-1 rounded-full">
                            👥 B2B
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-red-500 p-6 rounded-2xl text-white shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Sistem Durumu</p>
                    <p class="text-2xl font-bold mb-1">%{{ $errorStats['basarili_senkron_orani'] ?? 95 }}</p>
                    <div class="flex items-center">
                        <span class="text-xs bg-orange-400 bg-opacity-50 px-2 py-1 rounded-full">
                            @if(($errorStats['basarili_senkron_orani'] ?? 95) >= 95)
                                ✅ Mükemmel
                            @elseif(($errorStats['basarili_senkron_orani'] ?? 95) >= 80)
                                ⚠️ İyi
                            @else
                                ❌ Dikkat
                            @endif
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-4 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
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
                        <a href="{{ route('admin.api-test.index') }}" 
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

    <!-- Son Siparişler ve Extra Bilgiler -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Son Siparişler -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">🧾 Son Siparişler</h3>
                <a href="{{ route('admin.siparis.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Tümü →</a>
            </div>
            @php($sonSiparisler = \App\Models\Siparis::with('kullanici')->latest()->limit(5)->get())
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

        <!-- Sistem Bilgileri & Kısayollar -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6">⚡ Sistem & Kısayollar</h3>
            
            <!-- Sistem Durumu -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-200 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-green-800">🟢 Sistem Çalışıyor</h4>
                        <p class="text-sm text-green-600">Tüm servisler aktif</p>
                    </div>
                    <div class="text-2xl">⚡</div>
                </div>
            </div>

            <!-- Kısayollar -->
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.magaza.index') }}" 
                   class="bg-blue-50 hover:bg-blue-100 p-3 rounded-lg text-center transition-all border border-blue-200">
                    <div class="text-xl mb-1">🏪</div>
                    <p class="text-xs font-medium text-blue-800">Mağazalar</p>
                </a>
                
                <a href="{{ route('admin.bayi.index') }}" 
                   class="bg-purple-50 hover:bg-purple-100 p-3 rounded-lg text-center transition-all border border-purple-200">
                    <div class="text-xl mb-1">👥</div>
                    <p class="text-xs font-medium text-purple-800">Bayiler</p>
                </a>
                
                <a href="{{ route('admin.kategori.index') }}" 
                   class="bg-orange-50 hover:bg-orange-100 p-3 rounded-lg text-center transition-all border border-orange-200">
                    <div class="text-xl mb-1">📂</div>
                    <p class="text-xs font-medium text-orange-800">Kategoriler</p>
                </a>
                
                <a href="{{ route('vitrin.index') }}" target="_blank"
                   class="bg-pink-50 hover:bg-pink-100 p-3 rounded-lg text-center transition-all border border-pink-200">
                    <div class="text-xl mb-1">🌐</div>
                    <p class="text-xs font-medium text-pink-800">Site Önizleme</p>
                </a>
            </div>

            <!-- Site İstatistikleri -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">📊 Site İstatistikleri</h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Aktif Sayfalar:</span>
                        <span class="font-semibold">{{ \App\Models\SayfaIcerik::where('durum', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Toplam Siparişler:</span>
                        <span class="font-semibold">{{ $siparisler }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sistem Uptime:</span>
                        <span class="font-semibold text-green-600">99.8%</span>
                    </div>
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