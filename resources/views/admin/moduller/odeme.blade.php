@extends('admin.layouts.app')

@section('title','Ödeme Yöntemleri')
@section('page-title','Ödeme Yöntemleri')

@section('content')
<div class="space-y-6">
    @if(!$aktif)
        <div class="bg-yellow-50 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">Bu modül pasif. Modüller sayfasından aktifleştirin.</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-semibold mb-2">Kredi Kartı</h3>
            <p class="text-sm text-gray-600">PayTR, Iyzico, Stripe vb. sağlayıcı ayarları (yapım aşamasında).</p>
        </div>
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-semibold mb-2">Havale/EFT</h3>
            <p class="text-sm text-gray-600">Banka hesapları ve yönergeler (yapım aşamasında).</p>
        </div>
    </div>
</div>
@endsection
