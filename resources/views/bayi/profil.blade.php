@extends('layouts.bayi.app')

@section('title', 'Profilim')
@section('page-title', 'Profilim')

@section('content')
<div class="max-w-xl">
    <div class="bg-white border rounded-xl p-6">
        <form method="POST" action="{{ route('bayi.profil.guncelle') }}">
            @csrf
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Bayi Adı</label>
                    <input type="text" name="ad" value="{{ old('ad', $bayi->ad ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Telefon</label>
                    <input type="text" name="telefon" value="{{ old('telefon', $bayi->telefon ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Adres</label>
                    <textarea name="adres" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('adres', $bayi->adres ?? '') }}</textarea>
                </div>
            </div>
            <div class="mt-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
