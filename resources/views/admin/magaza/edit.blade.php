@extends('admin.layouts.app')

@section('title', 'Mağaza Düzenle')
@section('page-title', 'Mağaza Düzenle')

@section('content')
<div class="space-y-6">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.magaza.update', $magaza) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mağaza Adı</label>
                <input type="text" name="ad" value="{{ old('ad', $magaza->ad) }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                <select name="platform" class="w-full px-3 py-2 border rounded">
                    @foreach($platformlar as $pAd => $p)
                        <option value="{{ $pAd }}" {{ old('platform', $magaza->platform) === $pAd ? 'selected' : '' }}>{{ $pAd }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hepsiburada için Mağaza ID (merchantId) gereklidir.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">API Anahtarı</label>
                <input type="text" name="api_anahtari" value="{{ old('api_anahtari', $magaza->api_anahtari) }}" class="w-full px-3 py-2 border rounded" placeholder="API Key">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">API Gizli Anahtarı</label>
                <input type="text" name="api_gizli_anahtari" value="{{ old('api_gizli_anahtari', $magaza->api_gizli_anahtari) }}" class="w-full px-3 py-2 border rounded" placeholder="API Secret">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">API URL</label>
                <input type="text" name="api_url" value="{{ old('api_url', $magaza->api_url) }}" class="w-full px-3 py-2 border rounded" placeholder="Örn: https://listing-external.hepsiburada.com/listings/{merchantId}">
                <p class="text-xs text-gray-500 mt-1">Hepsiburada için listings/{merchantId} içeren URL girerseniz Mağaza ID otomatik çıkarılır.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mağaza ID</label>
                <input type="text" name="magaza_id" value="{{ old('magaza_id', $magaza->magaza_id) }}" class="w-full px-3 py-2 border rounded" placeholder="merchantId (örn: f6288...8d8)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Komisyon Oranı (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="komisyon_orani" value="{{ old('komisyon_orani', $magaza->komisyon_orani) }}" class="w-full px-3 py-2 border rounded" placeholder="Örn: 12.5">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="2" class="w-full px-3 py-2 border rounded" placeholder="Notlar, entegrasyon açıklaması vb.">{{ old('aciklama', $magaza->aciklama) }}</textarea>
            </div>
            <div class="flex items-center gap-6 md:col-span-2">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="aktif" {{ old('aktif', $magaza->aktif) ? 'checked' : '' }} class="rounded">
                    <span>Aktif</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="auto_senkron" {{ old('auto_senkron', $magaza->auto_senkron) ? 'checked' : '' }} class="rounded">
                    <span>Otomatik Senkron</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="test_mode" {{ old('test_mode', $magaza->test_mode) ? 'checked' : '' }} class="rounded">
                    <span>Test Modu</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="test_connection" class="rounded">
                    <span>Güncelledikten sonra bağlantıyı test et</span>
                </label>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.magaza.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Geri</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Güncelle</button>
        </div>
    </form>
</div>
@endsection
