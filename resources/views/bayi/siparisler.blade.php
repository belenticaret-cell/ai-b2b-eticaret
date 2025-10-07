@extends('layouts.bayi.app')

@section('title', 'Siparişlerim')
@section('page-title', 'Siparişlerim')

@section('content')
<div class="bg-white border rounded-xl p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600 border-b">
                    <th class="py-2 pr-4">#</th>
                    <th class="py-2 pr-4">Tarih</th>
                    <th class="py-2 pr-4">Durum</th>
                    <th class="py-2 pr-4">Toplam</th>
                    <th class="py-2 pr-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse(($siparisler ?? []) as $s)
                    <tr class="border-b">
                        <td class="py-2 pr-4">#{{ $s->id }}</td>
                        <td class="py-2 pr-4">{{ optional($s->created_at)->format('d.m.Y H:i') }}</td>
                        <td class="py-2 pr-4">{{ $s->durum ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ number_format((float)($s->toplam_tutar ?? 0), 2) }} ₺</td>
                        <td class="py-2 pr-4 text-right">
                            <a href="{{ route('bayi.siparis.detay', $s->id) }}" class="text-blue-600">Detay →</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">Henüz sipariş yok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ ($siparisler ?? null)?->links() }}
    </div>
</div>
@endsection
