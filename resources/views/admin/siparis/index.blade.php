@extends('admin.layouts.app')

@section('title','Siparişler')
@section('page-title','Siparişler')

@section('content')
<div class="space-y-4">
    <form method="GET" class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm text-gray-600">Ara</label>
            <input type="text" name="search" value="{{ request('search') }}" class="px-3 py-2 border rounded" placeholder="sipariş no, müşteri, email">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Durum</label>
            <select name="durum" class="px-3 py-2 border rounded">
                <option value="">Hepsi</option>
                @foreach($durumlar as $d)
                    <option value="{{ $d }}" {{ request('durum')===$d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Filtrele</button>
        </div>
        <div class="ml-auto text-sm text-gray-500">Toplam: {{ $siparisler->total() }}</div>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Müşteri</th>
                    <th class="px-4 py-2 text-left">Durum</th>
                    <th class="px-4 py-2 text-right">Tutar</th>
                    <th class="px-4 py-2 text-left">Tarih</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($siparisler as $s)
                    <tr>
                        <td class="px-4 py-2">{{ $s->siparis_no ?? ('SIP'.str_pad($s->id,6,'0',STR_PAD_LEFT)) }}</td>
                        <td class="px-4 py-2">{{ $s->kullanici->ad ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.siparis.index', ['durum' => $s->durum]) }}" class="inline-block">
                                <span class="px-2 py-1 rounded text-xs {{ $s->durum==='teslim_edildi' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ $s->durum }}</span>
                            </a>
                        </td>
                        <td class="px-4 py-2 text-right">{{ number_format((float)($s->toplam_tutar ?? $s->net_tutar ?? 0),2) }} ₺</td>
                        <td class="px-4 py-2">{{ optional($s->created_at)->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('admin.siparis.show',$s) }}" class="px-3 py-1 bg-gray-100 rounded">Görüntüle</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">Sipariş bulunamadı</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $siparisler->links() }}</div>
    </div>
</div>
@endsection
