@extends('layouts.bayi.app')

@section('title', 'Toplu Sipariş')
@section('page-title', 'Toplu Sipariş Oluştur')

@section('content')
<div class="bg-white border rounded-xl p-6">
    <p class="text-sm text-gray-600 mb-4">Kategori bazlı ürünlerinizden hızlıca toplu sipariş oluşturun.</p>
    <div class="space-y-4">
        @forelse($bayiFiyatlar as $kategoriAd => $kayitlar)
            <div class="border rounded-lg">
                <div class="px-4 py-2 bg-gray-50 font-semibold">{{ $kategoriAd }}</div>
                <div class="p-4 grid md:grid-cols-2 gap-3">
                    @foreach($kayitlar as $k)
                        <div class="flex items-center justify-between">
                            <div class="text-sm">{{ $k->urun->ad ?? 'Ürün' }}</div>
                            <div class="text-sm text-gray-500">{{ number_format((float)($k->fiyat ?? 0), 2) }} ₺</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Tanımlı bir ürün bulunamadı.</div>
        @endforelse
    </div>
</div>
@endsection
