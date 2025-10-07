@extends('layouts.app')

@section('title', 'AI B2B E-Ticaret - Ana Sayfa')
@section('meta_description', 'AI B2B E-Ticaret platformunda kaliteli ürünleri keşfedin. B2B ve B2C satış seçenekleri ile en uygun fiyatları bulun.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $siteAyarlar['anasayfa_hero_baslik'] ?? ($siteAyarlar['site_adi'] ?? 'AI B2B E-Ticaret') }}</h1>
        <p class="text-xl mb-8">{{ $siteAyarlar['anasayfa_hero_altbaslik'] ?? ($siteAyarlar['site_aciklama'] ?? 'Modern teknoloji ile ticaretin buluştuğu platform') }}</p>
        <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <h3 class="font-semibold">✨ AI Destekli</h3>
                <p class="text-sm">Akıllı ürün önerileri</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <h3 class="font-semibold">🔄 Çoklu Platform</h3>
                <p class="text-sm">Trendyol, Hepsiburada, N11</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4">
                <h3 class="font-semibold">💼 B2B Çözümler</h3>
                <p class="text-sm">Bayiler için özel fiyatlar</p>
            </div>
        </div>
        @if(!empty($siteAyarlar['anasayfa_hero_buton_yazi']))
            <div class="mt-8">
                <a href="{{ $siteAyarlar['anasayfa_hero_buton_link'] ?? route('vitrin.urunler') }}" class="inline-block bg-white text-blue-700 font-semibold px-6 py-3 rounded shadow hover:bg-gray-100">
                    {{ $siteAyarlar['anasayfa_hero_buton_yazi'] }}
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Neden Bizi Seçmelisiniz?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Geniş Ürün Yelpazesi</h3>
                <p class="text-gray-600">Binlerce ürün çeşidi ile her ihtiyacınıza uygun çözümler</p>
            </div>
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Güvenli Ödeme</h3>
                <p class="text-gray-600">SSL sertifikası ile korunan güvenli ödeme altyapısı</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Hızlı Teslimat</h3>
                <p class="text-gray-600">1-3 iş günü içinde hızlı ve güvenli teslimat</p>
            </div>
        </div>
    </div>
</section>

@if(isset($oneCikanKategoriler) && $oneCikanKategoriler->count())
<section class="py-10 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Öne Çıkan Kategoriler</h2>
            <a href="{{ route('vitrin.urunler') }}" class="text-blue-600 hover:underline">Tüm Ürünler</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($oneCikanKategoriler as $kat)
                <a href="{{ route('vitrin.kategori.slug', $kat->slug) }}" class="block bg-gray-50 hover:bg-gray-100 rounded p-4 text-center border">
                    <div class="font-semibold">{{ $kat->ad }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(isset($oneCikanUrunler) && $oneCikanUrunler->count())
<section class="py-10 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Öne Çıkan Ürünler</h2>
            <a href="{{ route('vitrin.urunler') }}" class="text-blue-600 hover:underline">Tümünü Gör</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($oneCikanUrunler as $urun)
                <div class="bg-white rounded-lg shadow-md">
                    <img src="{{ $urun->gorsel ? (Str::startsWith($urun->gorsel, ['http://','https://']) ? $urun->gorsel : asset('storage/'.$urun->gorsel)) : 'https://placehold.co/600x400?text=Urun' }}" class="w-full h-40 object-cover rounded-t" />
                    <div class="p-4">
                        <div class="font-semibold line-clamp-1 mb-1">{{ $urun->ad }}</div>
                        <div class="text-blue-600 font-bold mb-3">{{ number_format($urun->fiyat, 2) }} ₺</div>
                        <div class="flex gap-2">
                            <a class="flex-1 text-center bg-gray-100 hover:bg-gray-200 rounded py-2 text-sm" href="{{ route('vitrin.urun-detay', $urun->id) }}">Detay</a>
                            <form action="{{ route('sepet.ekle') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="urun_id" value="{{ $urun->id }}">
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded py-2 text-sm">Sepete Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Products Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold">
                @if(request('q'))
                    "{{ request('q') }}" için Arama Sonuçları
                @elseif(request('kategori_id'))
                    @php($kategori = $kategoriler->find(request('kategori_id')))
                    {{ $kategori ? $kategori->ad : 'Kategori' }} Ürünleri
                @else
                    Öne Çıkan Ürünler
                @endif
            </h2>
            @if($urunler->count() > 0)
                <span class="text-gray-600">{{ $urunler->total() }} ürün bulundu</span>
            @endif
        </div>

        @if(!empty($breadcrumbs))
            <nav class="text-sm text-gray-600 mb-6">
                <ol class="list-reset flex">
                    <li><a href="{{ route('vitrin.index') }}" class="hover:underline">Anasayfa</a></li>
                    @foreach($breadcrumbs as $i => $bc)
                        <li class="mx-2">/</li>
                        <li>
                            @if($i < count($breadcrumbs)-1)
                                <a href="{{ route('vitrin.kategori.slug', $bc->slug) }}" class="hover:underline">{{ $bc->ad }}</a>
                            @else
                                <span class="font-semibold">{{ $bc->ad }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        
        <!-- Filters + Alt Kategoriler -->
        @if(isset($altKategoriler) && $altKategoriler->count())
            <div class="mb-6 flex flex-wrap gap-2">
                @foreach($altKategoriler as $alt)
                    <a href="{{ route('vitrin.kategori.slug', $alt->slug) }}" class="px-3 py-1 bg-white rounded border hover:bg-gray-50 text-sm">{{ $alt->ad }}</a>
                @endforeach
            </div>
        @endif

        @if(isset($kategoriAgaci) && $kategoriAgaci->count())
            <details class="mb-4 bg-white rounded border p-4" open>
                <summary class="cursor-pointer font-semibold">Kategoriler</summary>
                <div class="mt-3 grid md:grid-cols-3 gap-4 text-sm">
                    @foreach($kategoriAgaci as $ana)
                        <div>
                            <div class="mb-1 font-semibold"><a href="{{ route('vitrin.kategori.slug', $ana->slug) }}" class="hover:underline">{{ $ana->ad }}</a></div>
                            @if($ana->children && $ana->children->count())
                                <ul class="ml-3 list-disc text-gray-700">
                                    @foreach($ana->children as $alt)
                                        <li><a href="{{ route('vitrin.kategori.slug', $alt->slug) }}" class="hover:underline">{{ $alt->ad }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </details>
        @endif
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('vitrin.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ürün Arama</label>
                    <input type="text" name="q" value="{{ request('q') }}" 
                           placeholder="Ürün adı, açıklama veya SKU..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Category -->
                <div class="md:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tümü</option>
                        @foreach($kategoriler as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->ad }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Brand -->
                <div class="md:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marka</label>
                    <select name="marka_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tümü</option>
                        @foreach($markalar as $marka)
                            <option value="{{ $marka->id }}" {{ request('marka_id') == $marka->id ? 'selected' : '' }}>
                                {{ $marka->ad }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Price Range -->
                <div class="flex space-x-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Fiyat</label>
                        <input type="number" name="min_fiyat" value="{{ request('min_fiyat') }}" 
                               placeholder="0"
                               class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Fiyat</label>
                        <input type="number" name="max_fiyat" value="{{ request('max_fiyat') }}" 
                               placeholder="999999"
                               class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <!-- Sort -->
                <div class="md:w-40">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sırala</label>
                    <select name="sirala" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="yeni" {{ request('sirala') == 'yeni' ? 'selected' : '' }}>Yeniler</option>
                        <option value="fiyat_artan" {{ request('sirala') == 'fiyat_artan' ? 'selected' : '' }}>Fiyat (Artan)</option>
                        <option value="fiyat_azalan" {{ request('sirala') == 'fiyat_azalan' ? 'selected' : '' }}>Fiyat (Azalan)</option>
                        <option value="isim" {{ request('sirala') == 'isim' ? 'selected' : '' }}>İsim</option>
                    </select>
                </div>
                
                <!-- Submit -->
                <div class="flex space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                        Filtrele
                    </button>
                    <a href="{{ route('vitrin.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                        Temizle
                    </a>
                </div>
            </form>
        </div>
        
        @if($urunler->count() === 0)
            <div class="bg-white rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Henüz Ürün Bulunmuyor</h3>
                <p class="text-gray-500">Yakında harika ürünlerle karşınızda olacağız!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($urunler as $urun)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="relative">
                            <img src="{{ $urun->gorsel ? (Str::startsWith($urun->gorsel, ['http://','https://']) ? $urun->gorsel : asset('storage/'.$urun->gorsel)) : 'https://placehold.co/600x400?text=Urun' }}" 
                                 class="w-full h-48 object-cover rounded-t-lg" 
                                 alt="{{ $urun->ad }}">
                            @if($urun->stok && $urun->stok < 10)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">Son {{ $urun->stok }} Adet</span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2 line-clamp-2">{{ $urun->ad }}</h3>
                            
                            @if($urun->aciklama)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($urun->aciklama, 80) }}</p>
                            @endif
                            
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($urun->fiyat, 2) }} ₺</span>
                                @if($urun->stok)
                                    <span class="text-sm text-gray-500">Stok: {{ $urun->stok }}</span>
                                @endif
                            </div>
                            
                            @php($ms = $urunMagazalari[$urun->id] ?? [])
                            @if(!empty($ms))
                                <div class="mb-3 flex flex-wrap gap-1">
                                    @foreach($ms as $m)
                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                            {{ $m['ad'] }}
                                            @if(!empty($m['platform']))
                                                <span class="text-gray-500">({{ $m['platform'] }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('vitrin.urun-detay', $urun->id) }}" 
                                   class="flex-1 bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                                    Detay
                                </a>
                                <form action="{{ route('sepet.ekle') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="urun_id" value="{{ $urun->id }}">
                                    <button type="submit" 
                                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition text-sm font-medium">
                                        Sepete Ekle
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($urunler->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $urunler->links() }}
                </div>
            @endif
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">B2B Bayiimiz Olmak İster misiniz?</h2>
        <p class="text-xl mb-8">Özel fiyatlar ve avantajlı koşullarla ticaretinizi büyütün</p>
        <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-4">
            <a href="/b2b-login" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                B2B Giriş Yap
            </a>
            <a href="{{ route('sayfa.iletisim') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                Bize Ulaşın
            </a>
        </div>
    </div>
</section>
@endsection
