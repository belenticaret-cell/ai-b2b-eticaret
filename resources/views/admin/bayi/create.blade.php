@extends('admin.layouts.app')

@section('title', 'Yeni Bayi')
@section('page-title', 'Yeni Bayi')

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

    <form method="POST" action="{{ route('admin.bayi.store') }}" class="bg-white rounded-lg shadow p-6 space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                <input type="text" name="ad" value="{{ old('ad') }}" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                <input type="text" name="telefon" value="{{ old('telefon') }}" class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı (opsiyonel)</label>
                <select name="kullanici_id" class="w-full px-3 py-2 border rounded">
                    <option value="">Seçiniz</option>
                    @foreach($kullanicilar as $k)
                        <option value="{{ $k->id }}">{{ $k->ad }} ({{ $k->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                <textarea name="adres" rows="3" class="w-full px-3 py-2 border rounded">{{ old('adres') }}</textarea>
            </div>
        </div>
        <div class="border-t pt-4 space-y-3">
            <label class="inline-flex items-center">
                <input type="checkbox" name="kullanici_olustur" value="1" class="mr-2" {{ old('kullanici_olustur') ? 'checked' : '' }}>
                Bu bayi için kullanıcı oluştur (rol: bayi)
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı E-posta</label>
                    <input type="email" name="kullanici_email" value="{{ old('kullanici_email') }}" class="w-full px-3 py-2 border rounded" placeholder="ornek@firma.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Geçici Şifre (boş bırakılırsa otomatik)</label>
                    <input type="text" name="gecici_sifre" value="{{ old('gecici_sifre') }}" class="w-full px-3 py-2 border rounded" placeholder="En az 8 karakter">
                </div>
            </div>
            <p class="text-xs text-gray-500">Kaydetmeden sonra ekranda giriş bilgilerini göreceksiniz. Dilerseniz müşteriye iletin.</p>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.bayi.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Vazgeç</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kaydet</button>
        </div>
    </form>
</div>
@endsection
