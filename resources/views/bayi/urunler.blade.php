@extends('layouts.bayi.app')

@section('title', 'Bayi Ürünleri')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Bayi Ürünleri</h1>
        <a href="{{ route('b2b.panel') }}" class="text-blue-600 hover:underline">B2B Panele Dön</a>
    </div>

    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <form method="GET" class="grid md:grid-cols-4 gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Ürün ara..." class="border rounded px-3 py-2" />
            <input type="number" step="0.01" name="min" value="{{ request('min') }}" placeholder="Min Fiyat" class="border rounded px-3 py-2" />
            <input type="number" step="0.01" name="max" value="{{ request('max') }}" placeholder="Maks Fiyat" class="border rounded px-3 py-2" />
            <button class="bg-blue-600 text-white rounded px-4">Filtrele</button>
        </form>
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        @foreach(($urunler ?? []) as $urun)
        <div class="bg-white rounded-lg shadow p-4">
            <img src="{{ $urun->getAnaResim() ?? 'https://via.placeholder.com/400x300' }}" class="w-full h-40 object-cover rounded" />
            <div class="mt-3">
                <h3 class="font-semibold">{{ $urun->ad }}</h3>
                <p class="text-sm text-gray-500">SKU: {{ $urun->sku }}</p>
                <p class="mt-2 text-gray-700">
                    <span class="text-xs text-gray-500 line-through">{{ number_format($urun->fiyat, 2) }} ₺</span>
                    <span class="font-bold ml-2">{{ number_format(($bayiFiyatlari[$urun->id] ?? $urun->fiyat), 2) }} ₺</span>
                </p>
                <a href="{{ route('vitrin.urun-detay', $urun->id) }}" class="inline-block mt-3 text-blue-600 hover:underline">Detay</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ ($urunler ?? null)?->links() }}
    </div>
</div>
@endsection
