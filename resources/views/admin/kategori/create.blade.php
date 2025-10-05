@extends('admin.layouts.app')

@section('title', 'Yeni Kategori')
@section('page-title', 'Yeni Kategori')

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

    <form method="POST" action="{{ route('admin.kategori.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                <input type="text" name="ad" value="{{ old('ad') }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (boşsa otomatik)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 border rounded" placeholder="ornek-kategori">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Üst Kategori</label>
                <select name="parent_id" class="w-full px-3 py-2 border rounded">
                    <option value="">(Yok)</option>
                    @foreach($kategoriler as $k)
                        <option value="{{ $k->id }}">{{ $k->getFullPath() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sıra</label>
                <input type="number" name="sira" value="{{ old('sira',0) }}" class="w-full px-3 py-2 border rounded" min="0">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="3" class="w-full px-3 py-2 border rounded">{{ old('aciklama') }}</textarea>
            </div>
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Başlık</label>
                    <input type="text" name="seo_baslik" value="{{ old('seo_baslik') }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Açıklama</label>
                    <input type="text" name="seo_aciklama" value="{{ old('seo_aciklama') }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="durum" value="1" class="mr-2" {{ old('durum', true) ? 'checked' : '' }}>
                    Aktif
                </label>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Vazgeç</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kaydet</button>
        </div>
    </form>
</div>
@endsection
