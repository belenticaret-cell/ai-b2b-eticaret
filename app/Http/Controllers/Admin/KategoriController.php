<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $q = Kategori::query()->with(['parent','children' => function($qq){ $qq->orderBy('sira'); }]);
        if ($s = $request->get('search')) {
            $q->where('ad','like',"%$s%");
        }
        $kategoriler = $q->whereNull('parent_id')->orderBy('sira')->get();
        $tumKategoriler = Kategori::orderBy('ad')->get(['id','ad','parent_id']);
        return view('admin.kategori.index', compact('kategoriler','tumKategoriler'));
    }

    public function create()
    {
        $kategoriler = Kategori::orderBy('ad')->get();
        return view('admin.kategori.create', compact('kategoriler'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:kategoriler,slug'],
            'aciklama' => ['nullable','string'],
            'parent_id' => ['nullable','exists:kategoriler,id'],
            'sira' => ['nullable','integer','min:0'],
            'durum' => ['nullable','boolean'],
            'seo_baslik' => ['nullable','string','max:255'],
            'seo_aciklama' => ['nullable','string'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['ad']);
        $data['durum'] = $request->boolean('durum');
        $data['sira'] = $data['sira'] ?? 0;

        Kategori::create($data);
        return redirect()->route('admin.kategori.index')->with('success','Kategori eklendi.');
    }

    public function edit(Kategori $kategori)
    {
        $kategoriler = Kategori::where('id','!=',$kategori->id)->orderBy('ad')->get();
        return view('admin.kategori.edit', compact('kategori','kategoriler'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:kategoriler,slug,'.$kategori->id],
            'aciklama' => ['nullable','string'],
            'parent_id' => ['nullable','exists:kategoriler,id'],
            'sira' => ['nullable','integer','min:0'],
            'durum' => ['nullable','boolean'],
            'seo_baslik' => ['nullable','string','max:255'],
            'seo_aciklama' => ['nullable','string'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['ad']);
        $data['durum'] = $request->boolean('durum');
        $data['sira'] = $data['sira'] ?? 0;
        if (isset($data['parent_id']) && (int)$data['parent_id'] === (int)$kategori->id) {
            unset($data['parent_id']);
        }

        $kategori->update($data);
        return redirect()->route('admin.kategori.index')->with('success','Kategori güncellendi.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return back()->with('success','Kategori silindi.');
    }
}
