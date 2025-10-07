@extends('layouts.app')

@section('title', 'Mağaza - AI B2B E-Ticaret')
@section('meta_description', 'AI B2B E-Ticaret mağazasında binlerce kaliteli ürünü keşfedin. En uygun fiyatlarla güvenli alışveriş.')

@section('content')
<div x-data="magazaApp()" class="bg-gray-50 min-h-screen">
    <!-- Modern Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-4xl font-bold mb-2">🛍️ AI B2B Mağaza</h1>
                    <p class="text-blue-100 text-lg">{{ $urunler->total() }} ürün arasından seçim yapın</p>
                </div>
                
                <!-- Live Search -->
                <div class="w-full md:w-96">
                    <form action="{{ route('vitrin.arama') }}" method="GET" class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="🔍 Ürün ara..." 
                               class="w-full px-6 py-3 rounded-full text-gray-900 border-0 focus:ring-4 focus:ring-blue-300 focus:outline-none"
                               x-model="searchQuery" 
                               @input.debounce.500ms="liveSearch()">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-1/4">
                <div class="sticky top-24 space-y-6">
                    <!-- Filter Header -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">🎯 Filtreler</h3>
                            <button @click="clearFilters()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Temizle
                            </button>
                        </div>
                        
                        <!-- Active Filters -->
                        <div x-show="hasActiveFilters()" class="mb-4">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="filter in activeFilters" :key="filter.key">
                                    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                        <span x-text="filter.label"></span>
                                        <button @click="removeFilter(filter.key)" class="ml-2 text-blue-600 hover:text-blue-800">
                                            ✕
                                        </button>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                            📂 Kategoriler
                        </h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($kategoriler as $kategori)
                                <div class="space-y-2">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="kategori" value="{{ $kategori->id }}" 
                                               {{ request('kategori_id') == $kategori->id ? 'checked' : '' }}
                                               @change="filterByCategory($event.target.value)"
                                               class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <span class="ml-3 text-gray-700 group-hover:text-blue-600 transition-colors">
                                            {{ $kategori->ad }}
                                        </span>
                                        <span class="ml-auto text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            {{ $kategori->urunler_count ?? 0 }}
                                        </span>
                                    </label>
                                    
                                    @if($kategori->children->count() > 0)
                                        <div class="ml-6 space-y-1">
                                            @foreach($kategori->children as $alt)
                                                <label class="flex items-center cursor-pointer group">
                                                    <input type="radio" name="kategori" value="{{ $alt->id }}" 
                                                           {{ request('kategori_id') == $alt->id ? 'checked' : '' }}
                                                           @change="filterByCategory($event.target.value)"
                                                           class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <span class="ml-3 text-sm text-gray-600 group-hover:text-blue-600 transition-colors">
                                                        {{ $alt->ad }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                            💰 Fiyat Aralığı
                        </h4>
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm text-gray-600 mb-1">Min</label>
                                    <input type="number" x-model="priceRange.min" 
                                           placeholder="{{ number_format($minFiyat) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm text-gray-600 mb-1">Max</label>
                                    <input type="number" x-model="priceRange.max" 
                                           placeholder="{{ number_format($maxFiyat) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <button @click="applyPriceFilter()" 
                                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Fiyat Filtresi Uygula
                            </button>
                        </div>
                    </div>

                    <!-- Brands Filter -->
                    @if($markalar->count() > 0)
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                            🏷️ Markalar
                        </h4>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($markalar as $marka)
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="marka" value="{{ $marka->id }}" 
                                           {{ request('marka_id') == $marka->id ? 'checked' : '' }}
                                           @change="filterByBrand($event.target.value)"
                                           class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-gray-700 group-hover:text-blue-600 transition-colors">
                                        {{ $marka->ad }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Toolbar -->
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 mb-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <!-- Results Info -->
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-600">
                                <strong>{{ $urunler->total() }}</strong> ürün bulundu
                            </span>
                            @if(request('q'))
                                <span class="text-sm text-gray-500">
                                    "{{ request('q') }}" araması için
                                </span>
                            @endif
                        </div>

                        <!-- Sort Options -->
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700">Sırala:</label>
                            <select x-model="sortBy" @change="applySorting()" 
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="yeni">🆕 En Yeni</option>
                                <option value="populer">🔥 En Popüler</option>
                                <option value="fiyat_artan">💰 Fiyat: Düşük → Yüksek</option>
                                <option value="fiyat_azalan">💎 Fiyat: Yüksek → Düşük</option>
                                <option value="isim">🔤 İsim: A → Z</option>
                            </select>

                            <!-- View Toggle -->
                            <div class="flex bg-gray-100 rounded-lg p-1">
                                <button @click="viewMode = 'grid'" 
                                        :class="viewMode === 'grid' ? 'bg-white shadow' : ''"
                                        class="p-2 rounded-md transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </button>
                                <button @click="viewMode = 'list'" 
                                        :class="viewMode === 'list' ? 'bg-white shadow' : ''"
                                        class="p-2 rounded-md transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid/List -->
                <div id="products-container" class="transition-all duration-300">
                    @if($urunler->count() > 0)
                        <!-- Grid View -->
                        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($urunler as $urun)
                                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 hover:-translate-y-2">
                                    <!-- Product Image -->
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        @if($urun->gorsel)
                                            <img src="{{ $urun->gorsel }}" alt="{{ $urun->ad }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <span class="text-6xl text-gray-400">📦</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Quick Actions -->
                                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button class="bg-white/90 backdrop-blur-sm p-2 rounded-full shadow-lg hover:bg-white transition-all">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Discount Badge -->
                                        @if(isset($urun->indirim_orani) && $urun->indirim_orani > 0)
                                            <div class="absolute top-4 left-4">
                                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-sm font-bold">
                                                    -%{{ $urun->indirim_orani }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-6">
                                        <!-- Category & Brand -->
                                        <div class="flex items-center justify-between mb-2">
                                            @if($urun->kategori)
                                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                                                    {{ $urun->kategori->ad }}
                                                </span>
                                            @endif
                                            @if($urun->marka)
                                                <span class="text-xs text-gray-500">{{ $urun->marka->ad }}</span>
                                            @endif
                                        </div>

                                        <!-- Product Name -->
                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                            {{ $urun->ad }}
                                        </h3>

                                        <!-- Product Description -->
                                        @if($urun->aciklama)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ Str::limit($urun->aciklama, 80) }}
                                            </p>
                                        @endif

                                        <!-- Price -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex flex-col">
                                                <span class="text-2xl font-bold text-blue-600">
                                                    ₺{{ number_format($urun->fiyat, 2, ',', '.') }}
                                                </span>
                                                @if(isset($urun->eski_fiyat) && $urun->eski_fiyat > $urun->fiyat)
                                                    <span class="text-sm text-gray-500 line-through">
                                                        ₺{{ number_format($urun->eski_fiyat, 2, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Stock Status -->
                                            <div class="text-right">
                                                @if($urun->stok > 0)
                                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                                        ✅ Stokta
                                                    </span>
                                                @else
                                                    <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">
                                                        ❌ Tükendi
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex gap-2">
                                            @if($urun->stok > 0)
                                                <button @click="addToCart({{ $urun->id }}, '{{ $urun->ad }}', {{ $urun->fiyat }})" 
                                                        class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                                                    🛒 Sepete Ekle
                                                </button>
                                            @else
                                                <button disabled class="flex-1 bg-gray-300 text-gray-500 py-3 rounded-lg font-semibold cursor-not-allowed">
                                                    Stokta Yok
                                                </button>
                                            @endif
                                            
                                            <button class="bg-gray-100 text-gray-600 px-4 py-3 rounded-lg hover:bg-gray-200 transition-all">
                                                👁️
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- List View -->
                        <div x-show="viewMode === 'list'" class="space-y-4">
                            @foreach($urunler as $urun)
                                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                                    <div class="flex p-6">
                                        <!-- Product Image -->
                                        <div class="w-32 h-32 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                            @if($urun->gorsel)
                                                <img src="{{ $urun->gorsel }}" alt="{{ $urun->ad }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-4xl text-gray-400">📦</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 ml-6">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $urun->ad }}</h3>
                                                    <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($urun->aciklama, 150) }}</p>
                                                    
                                                    <div class="flex items-center space-x-4 mb-3">
                                                        @if($urun->kategori)
                                                            <span class="text-sm text-blue-600">📂 {{ $urun->kategori->ad }}</span>
                                                        @endif
                                                        @if($urun->marka)
                                                            <span class="text-sm text-gray-500">🏷️ {{ $urun->marka->ad }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="text-right ml-6">
                                                    <div class="text-3xl font-bold text-blue-600 mb-2">
                                                        ₺{{ number_format($urun->fiyat, 2, ',', '.') }}
                                                    </div>
                                                    
                                                    @if($urun->stok > 0)
                                                        <button @click="addToCart({{ $urun->id }}, '{{ $urun->ad }}', {{ $urun->fiyat }})" 
                                                                class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                                                            🛒 Sepete Ekle
                                                        </button>
                                                    @else
                                                        <button disabled class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                                                            Stokta Yok
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12 flex justify-center">
                            {{ $urunler->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="text-8xl mb-6">🔍</div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Ürün bulunamadı</h3>
                            <p class="text-gray-600 mb-8">Arama kriterlerinizi değiştirerek tekrar deneyin.</p>
                            <button @click="clearFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                Filtreleri Temizle
                            </button>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="toastMessage"></span>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function magazaApp() {
    return {
        viewMode: 'grid',
        searchQuery: '{{ request('q') }}',
        sortBy: '{{ request('sirala', 'yeni') }}',
        priceRange: {
            min: {{ request('min_fiyat', '') ?: 'null' }},
            max: {{ request('max_fiyat', '') ?: 'null' }}
        },
        activeFilters: [],
        showToast: false,
        toastMessage: '',
        
        init() {
            this.updateActiveFilters();
        },
        
        hasActiveFilters() {
            return this.activeFilters.length > 0;
        },
        
        updateActiveFilters() {
            this.activeFilters = [];
            
            if (this.searchQuery) {
                this.activeFilters.push({key: 'search', label: `Arama: "${this.searchQuery}"`});
            }
            
            const kategoriId = '{{ request('kategori_id') }}';
            if (kategoriId) {
                this.activeFilters.push({key: 'category', label: 'Kategori seçili'});
            }
            
            const markaId = '{{ request('marka_id') }}';
            if (markaId) {
                this.activeFilters.push({key: 'brand', label: 'Marka seçili'});
            }
            
            if (this.priceRange.min || this.priceRange.max) {
                let label = 'Fiyat: ';
                if (this.priceRange.min) label += `₺${this.priceRange.min}`;
                if (this.priceRange.min && this.priceRange.max) label += ' - ';
                if (this.priceRange.max) label += `₺${this.priceRange.max}`;
                this.activeFilters.push({key: 'price', label: label});
            }
        },
        
        removeFilter(key) {
            switch(key) {
                case 'search':
                    this.searchQuery = '';
                    this.liveSearch();
                    break;
                case 'category':
                    this.filterByCategory('');
                    break;
                case 'brand':
                    this.filterByBrand('');
                    break;
                case 'price':
                    this.priceRange = {min: null, max: null};
                    this.applyPriceFilter();
                    break;
            }
        },
        
        clearFilters() {
            window.location.href = '{{ route('vitrin.magaza') }}';
        },
        
        liveSearch() {
            if (this.searchQuery.length >= 2 || this.searchQuery === '') {
                const url = new URL(window.location);
                if (this.searchQuery) {
                    url.searchParams.set('q', this.searchQuery);
                } else {
                    url.searchParams.delete('q');
                }
                window.location.href = url.toString();
            }
        },
        
        filterByCategory(categoryId) {
            const url = new URL(window.location);
            if (categoryId) {
                url.searchParams.set('kategori_id', categoryId);
            } else {
                url.searchParams.delete('kategori_id');
            }
            window.location.href = url.toString();
        },
        
        filterByBrand(brandId) {
            const url = new URL(window.location);
            if (brandId) {
                url.searchParams.set('marka_id', brandId);
            } else {
                url.searchParams.delete('marka_id');
            }
            window.location.href = url.toString();
        },
        
        applyPriceFilter() {
            const url = new URL(window.location);
            if (this.priceRange.min) {
                url.searchParams.set('min_fiyat', this.priceRange.min);
            } else {
                url.searchParams.delete('min_fiyat');
            }
            if (this.priceRange.max) {
                url.searchParams.set('max_fiyat', this.priceRange.max);
            } else {
                url.searchParams.delete('max_fiyat');
            }
            window.location.href = url.toString();
        },
        
        applySorting() {
            const url = new URL(window.location);
            url.searchParams.set('sirala', this.sortBy);
            window.location.href = url.toString();
        },
        
        addToCart(productId, productName, price) {
            // AJAX request to add to cart
            fetch('{{ route('sepet.ekle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    urun_id: productId,
                    adet: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.toastMessage = `"${productName}" sepete eklendi!`;
                    this.showToast = true;
                    
                    // Update cart count in header
                    const cartBadge = document.querySelector('a[href="{{ route('sepet.index') }}"] span');
                    if (cartBadge) {
                        const currentCount = parseInt(cartBadge.textContent) || 0;
                        cartBadge.textContent = currentCount + 1;
                    }
                    
                    setTimeout(() => {
                        this.showToast = false;
                    }, 3000);
                } else {
                    alert('Ürün sepete eklenirken hata oluştu.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu.');
            });
        }
    }
}
</script>
@endsection