<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ $siteAyarlar['site_adi'] ?? 'AI B2B E-Ticaret' }}</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Admin Tools JS -->
    <script src="{{ asset('js/admin-tools.js') }}"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <h1 class="text-white text-lg font-semibold">Admin Panel</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.panel') }}" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition {{ request()->routeIs('admin.panel') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- Ürünler -->
                    <div x-data="{ open: {{ request()->routeIs('admin.urun.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 18V8.5L4 12v5h12v-5l-6-3.5z" clip-rule="evenodd"></path>
                                </svg>
                                Ürün Yönetimi
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('admin.urun.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.urun.index') ? 'text-white' : '' }}">
                                Ürün Listesi
                            </a>
                            <a href="{{ route('admin.urun.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.urun.create') ? 'text-white' : '' }}">
                                Yeni Ürün
                            </a>
                        </div>
                    </div>
                    
                    <!-- Bayiler -->
                    <div x-data="{ open: {{ request()->routeIs('admin.bayi.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                Bayi Yönetimi
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('admin.bayi.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.bayi.index') ? 'text-white' : '' }}">
                                Bayi Listesi
                            </a>
                            <a href="{{ route('admin.bayi.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.bayi.create') ? 'text-white' : '' }}">
                                Yeni Bayi
                            </a>
                        </div>
                    </div>

                    <!-- Mağazalar -->
                    <div x-data="{ open: {{ request()->routeIs('admin.magaza.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                                Mağaza Yönetimi
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('admin.magaza.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.magaza.index') ? 'text-white' : '' }}">
                                Mağaza Listesi
                            </a>
                            <a href="{{ route('admin.magaza.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.magaza.create') ? 'text-white' : '' }}">
                                Yeni Mağaza
                            </a>
                        </div>
                    </div>

                    <!-- Siparişler -->
                    <a href="#" onclick="alert('Sipariş yönetimi yakında!')" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 3a1 1 0 011-1h2a1 1 0 011 1v1h6V3a1 1 0 011-1h2a1 1 0 011 1v1h1a1 1 0 011 1v12a2 2 0 01-2 2H4a2 2 0 01-2-2V5a1 1 0 011-1h1V3zM4 7h12v10H4V7z"/>
                        </svg>
                        Siparişler
                    </a>

                    <!-- Kategoriler -->
                    <div x-data="{ open: {{ request()->routeIs('admin.kategori.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm3 3h6v2H7V6zm0 4h6v2H7v-2zm0 4h6v2H7v-2z"></path>
                                </svg>
                                Kategori Yönetimi
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('admin.kategori.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.kategori.index') ? 'text-white' : '' }}">
                                Kategori Ağacı
                            </a>
                            <a href="{{ route('admin.kategori.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.kategori.create') ? 'text-white' : '' }}">
                                Yeni Kategori
                            </a>
                        </div>
                    </div>

                    <!-- Markalar -->
                    <div x-data="{ open: {{ request()->routeIs('admin.marka.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h3l2 2h7a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                </svg>
                                Marka Yönetimi
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 space-y-1">
                            <a href="{{ route('admin.marka.index') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.marka.index') ? 'text-white' : '' }}">
                                Marka Listesi
                            </a>
                            <a href="{{ route('admin.marka.create') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.marka.create') ? 'text-white' : '' }}">
                                Yeni Marka
                            </a>
                        </div>
                    </div>

                    <!-- Özellik Yönetimi -->
                    <a href="{{ route('admin.ozellik.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition {{ request()->routeIs('admin.ozellik.*') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M11 17a1 1 0 01-1 1H5a1 1 0 110-2h5a1 1 0 011 1zM17 13a1 1 0 01-1 1H5a1 1 0 110-2h11a1 1 0 011 1zM17 9a1 1 0 01-1 1H5a1 1 0 110-2h11a1 1 0 011 1z" />
                        </svg>
                        Özellik Yönetimi
                    </a>
                    
                    <!-- Site Ayarları -->
                    <a href="{{ route('admin.site-ayarlari') }}" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition {{ request()->routeIs('admin.site-ayarlari') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                        </svg>
                        Site Ayarları
                    </a>

                    <!-- Sistem Sağlığı -->
                    <a href="#" onclick="alert('Sistem sağlığı yakında!')" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 7H7v6h6V7z"></path>
                            <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm8 8H7V7h6v4z" clip-rule="evenodd"></path>
                        </svg>
                        Sistem Sağlığı
                    </a>
                    
                    <!-- Anasayfa Yönetimi -->
                    <a href="{{ route('admin.anasayfa') }}" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition {{ request()->routeIs('admin.anasayfa*') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 1.293a1 1 0 00-1.414 0l-8 8A1 1 0 002 11h1v7a1 1 0 001 1h5v-5h2v5h5a1 1 0 001-1v-7h1a1 1 0 00.707-1.707l-8-8z" />
                        </svg>
                        Anasayfa Yönetimi
                    </a>
                    
                    <!-- Sayfalar -->
                    <a href="{{ route('admin.sayfalar') }}" 
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition {{ request()->routeIs('admin.sayfalar*') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd"></path>
                        </svg>
                        Sayfa Yönetimi
                    </a>
                    
                    <!-- AI & Araçlar -->
                    <div class="pt-4 border-t border-gray-700">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Araçlar</p>
                        <a href="#" onclick="aiUrunOnerisi()" 
                           class="flex items-center px-4 py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            AI Ürün Önerisi
                        </a>
                        <a href="#" onclick="barkodFetch()" 
                           class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                            </svg>
                            Barkod Okuyucu
                        </a>
                    </div>

                    <!-- Modüller (Collapsible) -->
                    <div class="pt-4 border-t border-gray-700" x-data="{ open: {{ request()->routeIs('admin.moduller*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="flex items-center justify-between w-full px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm3 3h6v2H7V6zm0 4h6v2H7v-2zm0 4h6v2H7v-2z"></path>
                                </svg>
                                Modüller
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('admin.moduller') }}" 
                               class="block px-4 py-2 text-sm text-gray-300 hover:text-white {{ request()->routeIs('admin.moduller') ? 'text-white' : '' }}">
                                Modül Yönetimi
                            </a>
                            @php(
                                $modAktif = [
                                    'entegrasyon' => (bool) ($siteAyarlar['modul_entegrasyon_aktif'] ?? true),
                                    'kargo' => (bool) ($siteAyarlar['modul_kargo_aktif'] ?? false),
                                    'odeme' => (bool) ($siteAyarlar['modul_odeme_aktif'] ?? false),
                                ]
                            )
                            <a href="{{ $modAktif['entegrasyon'] ? route('admin.moduller.entegrasyon') : '#' }}" class="block px-4 py-2 text-sm {{ $modAktif['entegrasyon'] ? 'text-gray-300 hover:text-white' : 'text-gray-500 cursor-not-allowed' }} {{ request()->routeIs('admin.moduller.entegrasyon') ? 'text-white' : '' }}">Entegrasyon</a>
                            <a href="{{ $modAktif['kargo'] ? route('admin.moduller.kargo') : '#' }}" class="block px-4 py-2 text-sm {{ $modAktif['kargo'] ? 'text-gray-300 hover:text-white' : 'text-gray-500 cursor-not-allowed' }} {{ request()->routeIs('admin.moduller.kargo') ? 'text-white' : '' }}">Kargo</a>
                            <a href="{{ $modAktif['odeme'] ? route('admin.moduller.odeme') : '#' }}" class="block px-4 py-2 text-sm {{ $modAktif['odeme'] ? 'text-gray-300 hover:text-white' : 'text-gray-500 cursor-not-allowed' }} {{ request()->routeIs('admin.moduller.odeme') ? 'text-white' : '' }}">Ödeme</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('vitrin.index') }}" target="_blank" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Siteyi Görüntüle
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                                <span>{{ auth()->user()->ad ?? 'Admin' }}</span>
                                <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="border-t bg-white px-4 py-2" x-data="{openGroup:null}">
                    <ul class="flex flex-wrap gap-2 text-sm">
                        <li class="relative" @mouseenter="openGroup='katalog'" @mouseleave="openGroup=null">
                            <button type="button" class="px-3 py-2 rounded hover:bg-gray-100 flex items-center gap-1">
                                <span>📦 Katalog</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-80 bg-white shadow-lg border rounded-md z-40" x-show="openGroup==='katalog'" x-transition>
                                <div class="p-2 grid grid-cols-1">
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.urun.index') }}">Ürünler</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.urun.create') }}">Yeni Ürün</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.kategori.index') }}">Kategoriler</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.marka.index') }}">Markalar</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.ozellik.index') }}">Özellik Yönetimi</a>
                                </div>
                            </div>
                        </li>
                        <li class="relative" @mouseenter="openGroup='icerik'" @mouseleave="openGroup=null">
                            <button type="button" class="px-3 py-2 rounded hover:bg-gray-100 flex items-center gap-1">
                                <span>📝 İçerik</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-72 bg-white shadow-lg border rounded-md z-40" x-show="openGroup==='icerik'" x-transition>
                                <div class="p-2 grid grid-cols-1">
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.sayfalar') }}">Sayfalar</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.anasayfa') }}">Anasayfa Yönetimi</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="#" onclick="alert('Sistem sağlığı yakında!')">Sistem Sağlığı</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="#" onclick="alert('Sipariş yönetimi yakında!')">Siparişler</a>
                                </div>
                            </div>
                        </li>
                        <li class="relative" @mouseenter="openGroup='entegrasyon'" @mouseleave="openGroup=null">
                            <button type="button" class="px-3 py-2 rounded hover:bg-gray-100 flex items-center gap-1">
                                <span>🔗 Entegrasyon</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-96 bg-white shadow-lg border rounded-md z-40" x-show="openGroup==='entegrasyon'" x-transition>
                                <div class="p-2 grid grid-cols-1">
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.magaza.index') }}">Mağaza Listesi</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.moduller.entegrasyon') }}">Modül: Entegrasyon</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="#" onclick="alert('Platform katalogu yakında!')">Platform Katalogu</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.moduller.entegrasyon.ayar') }}">Entegrasyon Ayarları</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.moduller') }}">Modül Yönetimi</a>
                                    <div class="px-3 py-2 text-xs text-gray-500 border-t">XML</div>
                                    <form action="{{ route('admin.xml.import') }}" method="POST" enctype="multipart/form-data" class="px-3 py-2 flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="xml_file" accept=".xml" class="text-xs" required>
                                        <input type="hidden" name="import_type" value="urun">
                                        <button class="px-2 py-1 border rounded text-xs">İçe Aktar</button>
                                    </form>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.xml.export', ['export_type' => 'urun']) }}">Ürün XML Dışa Aktar</a>
                                </div>
                            </div>
                        </li>
                        <li class="relative" @mouseenter="openGroup='b2b'" @mouseleave="openGroup=null">
                            <button type="button" class="px-3 py-2 rounded hover:bg-gray-100 flex items-center gap-1">
                                <span>🏷️ B2B</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-64 bg-white shadow-lg border rounded-md z-40" x-show="openGroup==='b2b'" x-transition>
                                <div class="p-2 grid grid-cols-1">
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.bayi.index') }}">Bayiler</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('bayi.urunler') }}">Bayi Ürün Listesi</a>
                                </div>
                            </div>
                        </li>
                        <li class="relative" @mouseenter="openGroup='ayar'" @mouseleave="openGroup=null">
                            <button type="button" class="px-3 py-2 rounded hover:bg-gray-100 flex items-center gap-1">
                                <span>⚙️ Ayarlar</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 w-72 bg-white shadow-lg border rounded-md z-40" x-show="openGroup==='ayar'" x-transition>
                                <div class="p-2 grid grid-cols-1">
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.site-ayarlari') }}">Site Ayarları</a>
                                    <a class="px-3 py-2 rounded hover:bg-gray-50" href="{{ route('admin.panel') }}">Yönetim Paneli</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>
    
    <!-- Scripts -->
    <script>
        function aiUrunOnerisi() {
            if (confirm('AI Ürün Önerisi almak istiyor musunuz?')) {
                fetch('{{ route("admin.ai.urunOnerisi") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('AI Önerisi: ' + data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu.');
                });
            }
        }
        
        function barkodFetch() {
            const barkod = prompt('Barkod numarasını giriniz:');
            if (barkod) {
                fetch('{{ route("admin.barkod.fetch") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ barkod: barkod })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Ürün bulundu: ' + data.urun.ad + ' - ' + data.urun.fiyat + ' TL');
                    } else {
                        alert('Ürün bulunamadı: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu.');
                });
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>