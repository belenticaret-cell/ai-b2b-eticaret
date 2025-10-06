@extends('admin.layouts.app')

@section('title','Entegrasyon Ayarları')
@section('page-title','Entegrasyon Ayarları')

@section('content')
<div class="space-y-6">
    @if(!$aktif)
        <div class="bg-yellow-50 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">Bu modül pasif. Modüller sayfasından aktifleştirin.</div>
    @endif

    <form method="POST" action="{{ route('admin.moduller.entegrasyon.ayar.kaydet') }}" class="bg-white rounded shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Varsayılan Platform</label>
                <input type="text" name="varsayilan_platform" value="{{ old('varsayilan_platform', $ayarlar['entegrasyon_varsayilan_platform'] ?? '') }}" class="w-full px-3 py-2 border rounded" placeholder="trendyol|hepsiburada|n11|amazon">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">XML Cache Süresi (dk)</label>
                <input type="number" name="xml_cache_suresi" value="{{ old('xml_cache_suresi', $ayarlar['entegrasyon_xml_cache_suresi'] ?? 0) }}" class="w-full px-3 py-2 border rounded" min="0">
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">Kaydet</button>
        </div>
    </form>
</div>
@endsection
