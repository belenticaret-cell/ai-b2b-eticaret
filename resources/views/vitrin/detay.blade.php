@extends('layouts.app')

@section('content')
<section class="py-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Gallery -->
            <div>
                <img src="{{ $urun->gorsel ? (Str::startsWith($urun->gorsel, ['http://','https://']) ? $urun->gorsel : asset('storage/'.$urun->gorsel)) : 'https://placehold.co/800x600?text=Urun' }}" class="w-full h-auto rounded-lg shadow" alt="{{ $urun->ad }}">
                {{-- Thumbnail strip could go here when UrunResim entegre --}}
            </div>
            <!-- Info -->
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $urun->ad }}</h1>
                <div class="flex items-center gap-3 mb-4">
                    <div class="text-3xl font-extrabold text-blue-600">{{ number_format($urun->fiyat, 2) }} ₺</div>
                    @if($urun->stok)
                        <span class="text-green-600 text-sm">Stokta ({{ $urun->stok }})</span>
                    @else
                        <span class="text-red-600 text-sm">Stokta yok</span>
                    @endif
                </div>
                @if(isset($magazalar) && $magazalar->count())
                    <div class="mb-3">
                        <div class="text-gray-600 text-sm mb-1">Satıldığı Mağazalar</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($magazalar as $m)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $m->ad }}@if(!empty($m->platform)) <span class="text-gray-500">({{ $m->platform }})</span>@endif</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($urun->aciklama)
                    <div class="prose max-w-none mb-6">{!! nl2br(e($urun->aciklama)) !!}</div>
                @endif
                <form action="{{ route('sepet.ekle') }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="hidden" name="urun_id" value="{{ $urun->id }}">
                    <input type="number" name="adet" value="1" min="1" class="w-24 px-3 py-2 border rounded">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Sepete Ekle</button>
                </form>

                <div class="mt-6 text-sm text-gray-600">
                    <div>Kategori: @if($urun->kategori)<a href="{{ route('vitrin.kategori.slug', $urun->kategori->slug) }}" class="hover:underline">{{ $urun->kategori->ad }}</a>@endif</div>
                    <div>Marka: {{ $urun->marka->ad ?? '-' }}</div>
                    <div>SKU: {{ $urun->sku ?? '-' }}</div>
                    <div>Barkod: {{ $urun->barkod ?? '-' }}</div>
                </div>
            </div>
        </div>

        @if(isset($benzerUrunler) && $benzerUrunler->count())
        <div class="mt-12">
            <h2 class="text-xl font-bold mb-4">Benzer Ürünler</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($benzerUrunler as $u)
                    <div class="bg-white rounded-lg shadow">
                        <img src="{{ $u->gorsel ? (Str::startsWith($u->gorsel, ['http://','https://']) ? $u->gorsel : asset('storage/'.$u->gorsel)) : 'https://placehold.co/600x400?text=Urun' }}" class="w-full h-40 object-cover rounded-t">
                        <div class="p-4">
                            <div class="font-semibold line-clamp-1">{{ $u->ad }}</div>
                            <div class="text-blue-600 font-bold mb-3">{{ number_format($u->fiyat, 2) }} ₺</div>
                            <a href="{{ route('vitrin.urun-detay', $u->id) }}" class="block text-center bg-gray-100 hover:bg-gray-200 rounded py-2 text-sm">İncele</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    </section>
@endsection
