@extends('layouts.bayi.app')

@section('title', 'Sipariş Detayı')
@section('page-title', 'Sipariş Detayı')

@section('content')
<div class="bg-white border rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold">Sipariş #{{ $siparis->id }}</h2>
        <a href="{{ route('bayi.siparisler') }}" class="text-sm text-blue-600">← Listeye Dön</a>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <div class="text-sm text-gray-500">Oluşturma</div>
            <div class="font-medium">{{ optional($siparis->created_at)->format('d.m.Y H:i') }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Durum</div>
            <div class="font-medium">{{ $siparis->durum ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Toplam</div>
            <div class="font-medium">{{ number_format((float)($siparis->toplam_tutar ?? 0), 2) }} ₺</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-semibold mb-2">Ürünler</h3>
        <div class="space-y-2">
            @foreach(($siparis->siparisUrunleri ?? []) as $su)
                <div class="p-3 border rounded-lg flex items-center justify-between">
                    <div class="text-sm">{{ $su->urun->ad ?? 'Ürün' }} <span class="text-gray-500">x {{ $su->adet }}</span></div>
                    <div class="text-sm font-semibold">{{ number_format((float)($su->birim_fiyat ?? 0), 2) }} ₺</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
