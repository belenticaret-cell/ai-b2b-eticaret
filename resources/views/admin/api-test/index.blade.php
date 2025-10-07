@extends('admin.layouts.app')

@section('title', 'API Test & Configuration')
@section('page-title', 'API Test & Configuration')

@section('content')
<div class="space-y-8">
    <!-- Mode Switch -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">🔧 API Mode</h3>
                <p class="text-gray-600">Şu anki mod: <span class="font-bold text-blue-600">{{ $apiMode }}</span></p>
            </div>
            <form method="POST" action="{{ route('admin.api-test.toggle-mode') }}">
                @csrf
                <div class="flex items-center space-x-3">
                    <label class="text-sm font-medium text-gray-700">Mock Mode</label>
                    <input type="hidden" name="mock_mode" value="0">
                    <input type="checkbox" name="mock_mode" value="1" 
                           {{ str_contains($apiMode, 'Mock') ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           class="toggle-checkbox">
                </div>
            </form>
        </div>
    </div>

    <!-- Manual API Test -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">🧪 Manuel API Test</h3>
        
        <form method="POST" action="{{ route('admin.api-test.credentials') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Platform</label>
                    <select name="platform" class="w-full rounded-lg border-gray-300 focus:border-blue-500" required>
                        <option value="">Platform Seçin</option>
                        <option value="trendyol">Trendyol</option>
                        <option value="hepsiburada">Hepsiburada</option>
                        <option value="n11">N11</option>
                    </select>
                </div>
            </div>
            
            <!-- Trendyol Fields -->
            <div id="trendyol-fields" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="api_key" placeholder="Trendyol API Key" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                    <input type="password" name="api_secret" placeholder="Trendyol API Secret" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier ID</label>
                    <input type="text" name="supplier_id" placeholder="12345" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
            </div>

            <!-- Hepsiburada Fields -->
            <div id="hepsiburada-fields" class="grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" placeholder="HB Username" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="HB Password" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                    <input type="text" name="merchant_id" placeholder="HB Merchant ID" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    🧪 Test API Credentials
                </button>
                <a href="{{ route('admin.api-test.env-credentials') }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                    📋 .ENV Credentials
                </a>
                <a href="{{ route('admin.api-test.bulk-test') }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    🚀 Toplu Test (Mağazalar)
                </a>
            </div>
        </form>
    </div>

    <!-- Existing Stores -->
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">🏪 Mevcut Mağazalar</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Mağaza</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Platform</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Durum</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">API Key</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($magazalar as $magaza)
                    <tr>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium">{{ $magaza->ad }}</p>
                                <p class="text-sm text-gray-500">ID: {{ $magaza->id }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($magaza->platform === 'trendyol') bg-orange-100 text-orange-800
                                @elseif($magaza->platform === 'hepsiburada') bg-blue-100 text-blue-800
                                @elseif($magaza->platform === 'n11') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($magaza->platform) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($magaza->aktif)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🟢 Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    🔴 Pasif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($magaza->api_anahtari)
                                <span class="text-green-600">🔑 Var</span>
                                <p class="text-xs text-gray-500">{{ substr($magaza->api_anahtari, 0, 10) }}...</p>
                            @else
                                <span class="text-red-600">❌ Yok</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.magaza.show', $magaza) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                👁️ Detay
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Guide -->
    <div class="bg-blue-50 p-6 rounded-xl">
        <h3 class="text-lg font-semibold text-blue-800 mb-3">📖 API Kurulum Rehberi</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <h4 class="font-medium text-blue-700 mb-2">🔸 Trendyol</h4>
                <ul class="space-y-1 text-blue-600">
                    <li>• Partner hesabı açın</li>
                    <li>• API Key & Secret alın</li>
                    <li>• Supplier ID'nizi öğrenin</li>
                    <li>• IP whitelist yaptırın</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-blue-700 mb-2">🔸 Hepsiburada</h4>
                <ul class="space-y-1 text-blue-600">
                    <li>• Satıcı hesabı açın</li>
                    <li>• API username/password</li>
                    <li>• Merchant ID alın</li>
                    <li>• Test ortamında deneyin</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-blue-700 mb-2">🔸 N11</h4>
                <ul class="space-y-1 text-blue-600">
                    <li>• Mağaza hesabı açın</li>
                    <li>• SOAP API erişimi</li>
                    <li>• Shop ID alın</li>
                    <li>• WSDL entegrasyonu</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const platformSelect = document.querySelector('select[name="platform"]');
    const trendyolFields = document.getElementById('trendyol-fields');
    const hepsiburadaFields = document.getElementById('hepsiburada-fields');
    
    platformSelect.addEventListener('change', function() {
        // Hide all platform fields
        trendyolFields.classList.add('hidden');
        hepsiburadaFields.classList.add('hidden');
        
        // Show selected platform fields
        if (this.value === 'trendyol') {
            trendyolFields.classList.remove('hidden');
        } else if (this.value === 'hepsiburada') {
            hepsiburadaFields.classList.remove('hidden');
        }
    });
});
</script>
@endsection