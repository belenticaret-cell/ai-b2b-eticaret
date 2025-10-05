@php($pad = max(0, ($level ?? 0) * 12))
<li class="pl-{{ ($level ?? 0) * 4 }}">
    <div class="flex items-center justify-between py-1">
        <div class="flex items-center gap-2">
            <span class="text-sm font-medium {{ $node->durum ? 'text-gray-800' : 'text-gray-400 line-through' }}">{{ $node->ad }}</span>
            <span class="text-xs text-gray-500">/{{ $node->slug }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kategori.edit', $node) }}" class="text-blue-600 text-xs">Düzenle</a>
            <form action="{{ route('admin.kategori.destroy', $node) }}" method="POST" onsubmit="return confirm('Silinsin mi? Alt kategoriler de silinir.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 text-xs">Sil</button>
            </form>
        </div>
    </div>
    @if ($node->children && $node->children->count())
        <ul class="ml-4 border-l border-gray-200 pl-3">
            @foreach ($node->children->sortBy('sira') as $child)
                @include('admin.kategori.partials.node', ['node' => $child, 'level' => ($level ?? 0)+1])
            @endforeach
        </ul>
    @endif
</li>
