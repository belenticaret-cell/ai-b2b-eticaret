@extends('admin.layouts.app')

@section('title','Entegrasyon Modülü')
@section('page-title','Entegrasyon Modülü')

@section('content')
<div class="space-y-6">
    @isset($aktif)
        @if(!$aktif)
            <div class="bg-yellow-50 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">Bu modül pasif. Modüller sayfasından aktifleştirin.</div>
        @endif
    @endisset

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.moduller.entegrasyon.ayar') }}" class="block bg-white rounded shadow p-6 hover:shadow-md transition">
            <h3 class="font-semibold mb-1">🔧 Entegrasyon Ayarları</h3>
            <p class="text-sm text-gray-600">Varsayılan platform ve cache süresi gibi genel ayarlar.</p>
        </a>
        <a href="{{ route('admin.magaza.index') }}" class="block bg-white rounded shadow p-6 hover:shadow-md transition">
            <h3 class="font-semibold mb-1">🏪 Mağazalar</h3>
            <p class="text-sm text-gray-600">Platform mağazaları ekleyin, test edin ve senkronize edin.</p>
        </a>
        <a href="#" onclick="alert('API Test yakında!')" class="block bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded shadow p-6 hover:shadow-md transition">
            <h3 class="font-semibold mb-1">🧪 API Test & Configuration</h3>
            <p class="text-sm text-blue-100">Yakında: Credentials doğrulama ve mock/real switch.</p>
        </a>
        <a href="{{ route('admin.xml.export') }}" class="block bg-white rounded shadow p-6 hover:shadow-md transition">
            <h3 class="font-semibold mb-1">📤 XML Dışa Aktar</h3>
            <p class="text-sm text-gray-600">Katalog, stok ve fiyat XML feed’leri oluşturun.</p>
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="block bg-white rounded shadow p-6 hover:shadow-md transition">
            <h3 class="font-semibold mb-1">📂 Kategori Yönetimi</h3>
            <p class="text-sm text-gray-600">XML’e göre kategori ağacını yönetin ve eşleşmeleri güncelleyin.</p>
        </a>
    </div>
</div>
@endsection
