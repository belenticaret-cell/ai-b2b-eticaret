@extends('admin.layouts.app')

@section('title', 'Bayi Detay')
@section('page-title', 'Bayi Detay')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">{{ $bayi->ad }}</h3>
                <p class="text-sm text-gray-600">{{ $bayi->email }} @if($bayi->telefon) • {{ $bayi->telefon }} @endif</p>
                @if($bayi->adres)
                    <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $bayi->adres }}</p>
                @endif
                @if($bayi->kullanici)
                    <p class="mt-2 text-sm text-gray-600">Bağlı Kullanıcı: {{ $bayi->kullanici->ad }} ({{ $bayi->kullanici->email }})</p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.bayi.edit', $bayi) }}" class="px-4 py-2 bg-blue-600 text-white rounded">Düzenle</a>
                <form method="POST" action="{{ route('admin.bayi.destroy', $bayi) }}" onsubmit="return confirm('Bu bayiyi silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Sil</button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold mb-4">Bayi Fiyatları</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İndirim</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Geçerlilik</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($fiyatlar as $f)
                    <tr>
                        <td class="px-4 py-2"><a href="{{ route('admin.urun.edit', $f->urun) }}" class="text-blue-600 hover:underline">{{ $f->urun->ad ?? ('#'.$f->urun_id) }}</a></td>
                        <td class="px-4 py-2 text-gray-700">{{ $f->urun->sku ?? '-' }}</td>
                        <td class="px-4 py-2 font-medium">{{ number_format($f->fiyat, 2, ',', '.') }} ₺</td>
                        <td class="px-4 py-2">{{ $f->indirim_orani ? ($f->indirim_orani.'%') : '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">
                            @if($f->baslangic_tarihi || $f->bitis_tarihi)
                                {{ $f->baslangic_tarihi ? $f->baslangic_tarihi->format('d.m.Y') : '—' }} - {{ $f->bitis_tarihi ? $f->bitis_tarihi->format('d.m.Y') : '—' }}
                            @else
                                Süresiz
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <form method="POST" action="{{ route('admin.urun.bayi-fiyat.sil', [$f->urun_id, $f->id]) }}" onsubmit="return confirm('Fiyat kaydını silmek istiyor musunuz?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-50 text-red-700 rounded">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Kayıtlı bayi fiyatı bulunmuyor.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $fiyatlar->links() }}
        </div>
    </div>
</div>
@endsection
