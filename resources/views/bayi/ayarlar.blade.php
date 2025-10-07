@extends('layouts.bayi.app')

@section('title', 'Mağaza Ayarları')
@section('page-title', 'Mağaza Ayarları')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white border rounded-xl p-6">
        <form method="POST" action="{{ route('bayi.ayarlar.kaydet') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Mağaza Adı</label>
                    <input type="text" name="magaza_ad" value="{{ old('magaza_ad', $ayarlar['magaza_ad'] ?? $bayi->ad) }}" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Logo</label>
                    <input type="file" name="logo" class="mt-1 w-full border rounded px-3 py-2 bg-white" />
                    @if(!empty($ayarlar['logo_url']))
                        <img src="{{ $ayarlar['logo_url'] }}" alt="Logo" class="h-12 mt-2">
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium">İletişim Telefonu</label>
                    <input type="text" name="telefon" value="{{ old('telefon', $ayarlar['telefon'] ?? $bayi->telefon) }}" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Adres</label>
                    <textarea name="adres" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('adres', $ayarlar['adres'] ?? $bayi->adres) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Vitrin Durumu</label>
                    <select name="vitrin_aktif" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="1" {{ ($ayarlar['vitrin_aktif'] ?? '1') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($ayarlar['vitrin_aktif'] ?? '1') === '0' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
