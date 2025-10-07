@extends('layouts.bayi.app')

@section('title', 'Cari Hesap')
@section('page-title', 'Cari Hesap')

@section('content')
<div class="bg-white border rounded-xl p-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div>
            <div class="text-xs text-gray-500">Toplam Borç</div>
            <div class="text-lg font-semibold">{{ number_format(($cariOzet['toplam_borc'] ?? 0), 2) }} ₺</div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Ödenen</div>
            <div class="text-lg font-semibold">{{ number_format(($cariOzet['odenen_tutar'] ?? 0), 2) }} ₺</div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Kalan</div>
            <div class="text-lg font-semibold">{{ number_format(($cariOzet['kalan_borc'] ?? 0), 2) }} ₺</div>
        </div>
        <div>
            <div class="text-xs text-gray-500">Kredi Limiti</div>
            <div class="text-lg font-semibold">{{ number_format(($cariOzet['kredi_limiti'] ?? 0), 2) }} ₺</div>
        </div>
    </div>

    <h3 class="font-semibold mb-2">Son Hareketler</h3>
    <div class="space-y-2">
        @forelse(($cariHareketler ?? []) as $h)
            <div class="p-3 border rounded-lg flex items-center justify-between">
                <div class="text-sm">Sipariş #{{ $h->id }}</div>
                <div class="text-xs text-gray-500">{{ optional($h->created_at)->format('d.m.Y H:i') }}</div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Henüz hareket yok.</div>
        @endforelse
    </div>
</div>
@endsection
