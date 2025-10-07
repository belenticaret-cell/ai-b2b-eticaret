@extends('admin.layouts.app')

@section('title','Sipariş Detayı')
@section('page-title','Sipariş Detayı')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-sm text-gray-500">Sipariş No</div>
                <div class="text-xl font-semibold">{{ $siparis->siparis_no ?? ('SIP'.str_pad($siparis->id,6,'0',STR_PAD_LEFT)) }}</div>
            </div>
            <div>
                <span class="px-3 py-1 rounded text-sm {{ $siparis->durum==='teslim_edildi' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $siparis->durum }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div>
                <div class="text-sm text-gray-500 mb-1">Müşteri</div>
                <div class="font-medium">{{ $siparis->kullanici->ad ?? '-' }}</div>
                <div class="text-sm text-gray-500">{{ $siparis->kullanici->email ?? '' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Tutar</div>
                <div class="font-medium">{{ number_format((float)$siparis->toplam_tutar,2) }} ₺</div>
            </div>
            <div>
                <div class="text-sm text-gray-500 mb-1">Tarih</div>
                <div class="font-medium">{{ optional($siparis->created_at)->format('d.m.Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Ürünler</h3>
        <div class="divide-y">
            @forelse($siparis->urunler as $su)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <div class="font-medium">{{ $su->urun_adi ?? $su->urun?->ad ?? 'Ürün' }}</div>
                        <div class="text-sm text-gray-500">Adet: {{ $su->adet }} • Fiyat: {{ number_format((float)($su->birim_fiyat ?? 0),2) }} ₺</div>
                    </div>
                    <div class="font-medium">{{ number_format((float)($su->toplam_fiyat ?? 0),2) }} ₺</div>
                </div>
            @empty
                <div class="py-6 text-center text-gray-500">Ürün satırı yok</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
