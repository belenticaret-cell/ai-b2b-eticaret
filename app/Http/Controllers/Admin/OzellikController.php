<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\UrunOzellik;
use Illuminate\Http\Request;

class OzellikController extends Controller
{
    public function index(Request $request)
    {
        $q = UrunOzellik::query()->with('urun');
        if ($request->filled('search')) {
            $s = $request->get('search');
            $q->where(function($qq) use ($s) {
                $qq->where('ad', 'like', "%{$s}%")
                   ->orWhere('deger', 'like', "%{$s}%");
            });
        }
        if ($request->filled('urun_id')) {
            $q->where('urun_id', $request->urun_id);
        }
        $q->orderBy('urun_id')->orderBy('sira');
        $ozellikler = $q->paginate(30)->withQueryString();
        $urunler = Urun::orderBy('ad')->select('id','ad')->get();
        return view('admin.ozellik.index', compact('ozellikler','urunler'));
    }

    public function create()
    {
        $urunler = Urun::orderBy('ad')->select('id','ad')->get();
        return view('admin.ozellik.create', compact('urunler'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'urun_id' => ['required','exists:urunler,id'],
            'ad' => ['required','string','max:255'],
            'deger' => ['nullable','string','max:500'],
            'birim' => ['nullable','string','max:50'],
            'sira' => ['nullable','integer','min:0'],
        ]);
        UrunOzellik::create($data);
        return redirect()->route('admin.ozellik.index')->with('success', '✅ Özellik eklendi');
    }

    public function edit(UrunOzellik $ozellik)
    {
        $urunler = Urun::orderBy('ad')->select('id','ad')->get();
        return view('admin.ozellik.edit', compact('ozellik','urunler'));
    }

    public function update(Request $request, UrunOzellik $ozellik)
    {
        $data = $request->validate([
            'urun_id' => ['required','exists:urunler,id'],
            'ad' => ['required','string','max:255'],
            'deger' => ['nullable','string','max:500'],
            'birim' => ['nullable','string','max:50'],
            'sira' => ['nullable','integer','min:0'],
        ]);
        $ozellik->update($data);
        return redirect()->route('admin.ozellik.index')->with('success', '✅ Özellik güncellendi');
    }

    public function destroy(UrunOzellik $ozellik)
    {
        $ozellik->delete();
        return redirect()->route('admin.ozellik.index')->with('success', '✅ Özellik silindi');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate(['ids' => ['required','array'], 'ids.*' => ['integer','exists:urun_ozellikler,id']]);
        UrunOzellik::whereIn('id', $ids['ids'])->delete();
        return back()->with('success', '✅ Seçili özellikler silindi');
    }
}
