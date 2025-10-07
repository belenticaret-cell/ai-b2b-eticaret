@extends('admin.layouts.app')

@section('title','Sistem Sağlığı')
@section('page-title','Sistem Sağlığı')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.health.run') }}" class="bg-white p-4 rounded shadow flex items-center gap-4">
        @csrf
        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Sağlık Kontrolünü Çalıştır</button>
        <label class="inline-flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="keep" class="rounded"> Geçici DB dosyasını sakla (incelemek için)
        </label>
    </form>

    @if(isset($report))
        <div class="bg-white rounded shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Sonuçlar</h3>
                <div class="text-sm text-gray-500">
                    <span>{{ \Carbon\Carbon::parse($report['started_at'])->format('d.m.Y H:i:s') }}</span>
                    <span class="ml-2">~ {{ $report['duration_ms'] }} ms</span>
                </div>
                @if($report['success'])
                    <span class="px-2 py-1 rounded text-green-800 bg-green-100">TÜM TESTLER BAŞARILI</span>
                @else
                    <span class="px-2 py-1 rounded text-red-800 bg-red-100">HATALAR VAR</span>
                @endif
            </div>
            <div class="mt-4">
                <ul class="divide-y">
                    @foreach(($report['results'] ?? []) as $key => $res)
                        <li class="py-2 flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ str_replace('_',' ', $key) }}</div>
                                <div class="text-xs text-gray-500">~ {{ $res['duration_ms'] ?? 0 }} ms</div>
                                @if(!($res['success'] ?? false))
                                    <div class="text-sm text-red-600">{{ $res['message'] ?? 'Hata' }}</div>
                                @else
                                    <div class="text-sm text-gray-500">{{ $res['message'] ?? 'Başarılı' }}</div>
                                @endif
                            </div>
                            <div>
                                @if($res['success'] ?? false)
                                    <span class="px-2 py-1 rounded text-green-800 bg-green-100">Başarılı</span>
                                @else
                                    <span class="px-2 py-1 rounded text-red-800 bg-red-100">Hatalı</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(!empty($history))
        <div class="bg-white rounded shadow p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Son Çalıştırmalar</h3>
                <span class="text-sm text-gray-500">son {{ count($history) }} kayıt</span>
            </div>
            <div class="mt-3">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Tarih</th>
                            <th class="py-2">Süre</th>
                            <th class="py-2">Durum</th>
                            <th class="py-2">Özet</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($history as $item)
                        <tr class="border-t">
                            <td class="py-2">{{ \Carbon\Carbon::parse($item['started_at'])->format('d.m.Y H:i:s') }}</td>
                            <td class="py-2">{{ $item['duration_ms'] }} ms</td>
                            <td class="py-2">
                                @if($item['success'])
                                    <span class="px-2 py-1 rounded text-green-800 bg-green-100">Başarılı</span>
                                @else
                                    <span class="px-2 py-1 rounded text-red-800 bg-red-100">Hatalı</span>
                                @endif
                            </td>
                            <td class="py-2">
                                @php
                                    $fails = [];
                                    foreach (($item['results'] ?? []) as $k=>$r) { if (!(bool)($r['success'] ?? false)) $fails[] = $k; }
                                @endphp
                                @if(empty($fails))
                                    <span class="text-gray-500">Tüm testler geçti</span>
                                @else
                                    <span class="text-red-600">Hatalı: {{ implode(', ', $fails) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
