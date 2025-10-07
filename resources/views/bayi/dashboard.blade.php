@extends('layouts.bayi.app')

@section('title', 'Bayi Admin')
@section('page-title', 'Bayi Admin Paneli')

@section('content')
@php
    $bayiAd = $bayi->ad ?? 'Bayi';
@endphp
<div class="space-y-6">
    <!-- Karşılama -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold">👋 Hoş geldiniz, {{ $bayiAd }}</h2>
        <p class="text-indigo-100">Kendi mağazanızı yönetin, ürün ve siparişleri takip edin.</p>
    </div>

    <!-- Hızlı Aksiyonlar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('bayi.urunler') }}" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">📦</div>
            <div class="font-semibold">Ürünlerim</div>
            <div class="text-sm text-gray-500">Fiyat ve görünürlük</div>
        </a>
        <a href="{{ route('bayi.siparisler') }}" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">🧾</div>
            <div class="font-semibold">Siparişlerim</div>
            <div class="text-sm text-gray-500">Durum ve teslimat</div>
        </a>
        <a href="{{ route('bayi.cari') }}" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">💳</div>
            <div class="font-semibold">Cari Hesap</div>
            <div class="text-sm text-gray-500">Bakiye ve hareketler</div>
        </a>
        <a href="{{ route('bayi.ayarlar') }}" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">⚙️</div>
            <div class="font-semibold">Mağaza Ayarları</div>
            <div class="text-sm text-gray-500">Logo, iletişim, vitrin</div>
        </a>
    </div>

    <!-- Özet Kartları -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Toplam Sipariş</div>
            <div class="text-2xl font-bold">{{ $stats['toplam_siparis'] }}</div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Bu Ay Sipariş</div>
            <div class="text-2xl font-bold">{{ $stats['bu_ay_siparis'] }}</div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Bekleyen Sipariş</div>
            <div class="text-2xl font-bold">{{ $stats['bekleyen_siparis'] }}</div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Aktif Ürün</div>
            <div class="text-2xl font-bold">{{ $stats['aktif_urun'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Son Siparişler -->
        <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Son Siparişler</h3>
                <a href="{{ route('bayi.siparisler') }}" class="text-sm text-blue-600">Tümü →</a>
            </div>
            <div class="space-y-3">
                @forelse($sonSiparisler as $s)
                    <div class="p-3 border rounded-lg flex items-center justify-between">
                        <div>
                            <div class="font-medium text-sm">#{{ $s->id }}</div>
                            <div class="text-xs text-gray-500">{{ optional($s->created_at)->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-teal-600">{{ number_format((float)($s->toplam_tutar ?? 0), 2) }} ₺</div>
                            <div class="text-xs text-gray-500">{{ $s->durum ?? 'bekliyor' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Henüz sipariş yok.</div>
                @endforelse
            </div>
        </div>

        <!-- Popüler Ürünler -->
        <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Popüler Ürünler</h3>
                <a href="{{ route('bayi.urunler') }}" class="text-sm text-blue-600">Ürünlere Git →</a>
            </div>
            <div class="space-y-3">
                @forelse($populerUrunler as $p)
                    <div class="p-3 border rounded-lg flex items-center justify-between">
                        <div class="font-medium text-sm">{{ $p->urun->ad ?? 'Ürün' }}</div>
                        <div class="text-xs text-gray-500">{{ $p->toplam_adet }} adet</div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Henüz satış verisi yok.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
