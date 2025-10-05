@extends('admin.layouts.app')

@section('title', 'Kategori Düzenle')
@section('page-title', 'Kategori Düzenle')

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

    <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                <input type="text" name="ad" value="{{ old('ad', $kategori->ad) }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug (boşsa otomatik)</label>
                <input type="text" name="slug" value="{{ old('slug', $kategori->slug) }}" class="w-full px-3 py-2 border rounded" placeholder="ornek-kategori">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Üst Kategori</label>
                <select name="parent_id" class="w-full px-3 py-2 border rounded">
                    <option value="">(Yok)</option>
                    @foreach($kategoriler as $k)
                        <option value="{{ $k->id }}" @selected(old('parent_id', $kategori->parent_id)==$k->id)>{{ $k->getFullPath() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sıra</label>
                <input type="number" name="sira" value="{{ old('sira', $kategori->sira) }}" class="w-full px-3 py-2 border rounded" min="0">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="3" class="w-full px-3 py-2 border rounded">{{ old('aciklama', $kategori->aciklama) }}</textarea>
            </div>
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Başlık</label>
                    <input type="text" name="seo_baslik" value="{{ old('seo_baslik', $kategori->seo_baslik) }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO Açıklama</label>
                    <input type="text" name="seo_aciklama" value="{{ old('seo_aciklama', $kategori->seo_aciklama) }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="durum" value="1" class="mr-2" {{ old('durum', $kategori->durum) ? 'checked' : '' }}>
                    Aktif
                </label>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Geri</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Güncelle</button>
        </div>
    </form>
</div>
@endsection
