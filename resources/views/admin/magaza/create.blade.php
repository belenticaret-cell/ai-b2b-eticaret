@extends('admin.layouts.app')

@section('title', 'Mağaza Ekle')
@section('page-title', 'Yeni Mağaza')

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

    <form method="POST" action="{{ route('admin.magaza.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mağaza Adı</label>
                <input type="text" name="ad" value="{{ old('ad') }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                <input type="text" name="platform" value="{{ old('platform') }}" class="w-full px-3 py-2 border rounded" placeholder="Trendyol, Hepsiburada, N11...">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">API Anahtarı</label>
                <input type="text" name="api_anahtari" value="{{ old('api_anahtari') }}" class="w-full px-3 py-2 border rounded">
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.magaza.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Vazgeç</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kaydet</button>
        </div>
    </form>
</div>
@endsection
