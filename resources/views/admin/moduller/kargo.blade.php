@extends('admin.layouts.app')

@section('title','Kargo Modülü')
@section('page-title','Kargo Modülü')

@section('content')
<div class="space-y-6">
    @if(!$aktif)
        <div class="bg-yellow-50 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">Bu modül pasif. Modüller sayfasından aktifleştirin.</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-semibold mb-2">Kargo Firmaları</h3>
            <p class="text-sm text-gray-600">Aras, Yurtiçi, MNG vb. entegrasyonları bağlayın (yapım aşamasında).</p>
        </div>
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-semibold mb-2">Gönderim Kuralları</h3>
            <p class="text-sm text-gray-600">Ağırlık/bölge/ücret kurallarını tanımlayın (yapım aşamasında).</p>
        </div>
    </div>
</div>
@endsection
