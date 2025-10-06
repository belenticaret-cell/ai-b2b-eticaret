@extends('admin.layouts.app')

@section('title','Modüller')
@section('page-title','Modül Yönetimi')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.moduller.guncelle') }}" class="bg-white rounded shadow p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($modules as $key => $m)
                <div class="border rounded p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold">{{ $m['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $m['desc'] }}</p>
                        </div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="{{ $m['key'] }}" value="1" class="mr-2" {{ ($status[$key] ?? false) ? 'checked' : '' }}>
                            <span class="text-sm">Aktif</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        @if($key==='entegrasyon')
                            <a href="{{ route('admin.moduller.entegrasyon') }}" class="text-blue-600 text-sm">Ayrıntılar</a>
                        @elseif($key==='kargo')
                            <a href="{{ route('admin.moduller.kargo') }}" class="text-blue-600 text-sm">Ayrıntılar</a>
                        @elseif($key==='odeme')
                            <a href="{{ route('admin.moduller.odeme') }}" class="text-blue-600 text-sm">Ayrıntılar</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">Kaydet</button>
        </div>
    </form>
</div>
@endsection
