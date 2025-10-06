<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marka;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarkaController extends Controller
{
    public function index(Request $request)
    {
        $q = Marka::query();
        if ($request->filled('search')) {
            $s = $request->get('search');
            $q->where(function($qq) use ($s) {
                $qq->where('ad', 'like', "%{$s}%")
                   ->orWhere('aciklama', 'like', "%{$s}%");
            });
        }
        $q->orderBy('ad');
        $markalar = $q->paginate(20)->withQueryString();
        return view('admin.marka.index', compact('markalar'));
    }

    public function create()
    {
        return view('admin.marka.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'aciklama' => ['nullable','string'],
            'logo' => ['nullable','url'],
            'seo_baslik' => ['nullable','string','max:255'],
            'seo_aciklama' => ['nullable','string','max:500'],
            'meta_etiketler' => ['nullable','array'],
            'durum' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($data['ad']);
        $orj = $data['slug'];
        $i = 1;
        while (Marka::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $orj . '-' . $i++;
        }
        $data['durum'] = $request->has('durum');

        if (isset($data['meta_etiketler'])) {
            // array -> json string olarak saklanır, model cast'i array'e döndürür
            $data['meta_etiketler'] = array_values($data['meta_etiketler']);
        }

        Marka::create($data);
        return redirect()->route('admin.marka.index')->with('success', '✅ Marka eklendi');
    }

    public function edit(Marka $marka)
    {
        return view('admin.marka.edit', compact('marka'));
    }

    public function update(Request $request, Marka $marka)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'aciklama' => ['nullable','string'],
            'logo' => ['nullable','url'],
            'seo_baslik' => ['nullable','string','max:255'],
            'seo_aciklama' => ['nullable','string','max:500'],
            'meta_etiketler' => ['nullable','array'],
            'durum' => ['boolean'],
        ]);

        if ($data['ad'] !== $marka->ad) {
            $data['slug'] = Str::slug($data['ad']);
            $orj = $data['slug'];
            $i = 1;
            while (Marka::where('slug', $data['slug'])->where('id', '!=', $marka->id)->exists()) {
                $data['slug'] = $orj . '-' . $i++;
            }
        }
        $data['durum'] = $request->has('durum');
        if (isset($data['meta_etiketler'])) {
            $data['meta_etiketler'] = array_values($data['meta_etiketler']);
        }

        $marka->update($data);
        return redirect()->route('admin.marka.index')->with('success', '✅ Marka güncellendi');
    }

    public function destroy(Marka $marka)
    {
        $marka->delete();
        return redirect()->route('admin.marka.index')->with('success', '✅ Marka silindi');
    }
}
