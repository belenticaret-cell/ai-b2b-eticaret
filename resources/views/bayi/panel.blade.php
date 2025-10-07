@extends('layouts.bayi.app')

@section('title', 'Bayi Admin Paneli')
@section('page-title', 'Bayi Admin Paneli')

@section('content')
<div class="bg-white border rounded-xl p-6">
    <h2 class="text-lg font-semibold mb-2">Bayi Admin Paneli</h2>
    <p class="text-sm text-gray-600">Sol menüden işlemlerinizi seçin.</p>
    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('bayi.urunler') }}" class="px-3 py-2 border rounded">📦 Ürünlerim</a>
        <a href="{{ route('bayi.siparisler') }}" class="px-3 py-2 border rounded">🧾 Siparişlerim</a>
        <a href="{{ route('bayi.cari') }}" class="px-3 py-2 border rounded">💳 Cari Hesap</a>
        <a href="{{ route('bayi.ayarlar') }}" class="px-3 py-2 border rounded">⚙️ Mağaza Ayarları</a>
    </div>
    <p class="text-xs text-gray-500 mt-4">Not: Bu sayfa otomatik olarak yeni Bayi Admin Dashboard'a yönlendirilir.</p>
    <a href="{{ route('bayi.panel') }}" class="text-blue-600 text-sm underline mt-2 inline-block">Kontrol Paneline Git →</a>
    </div>
@endsection
