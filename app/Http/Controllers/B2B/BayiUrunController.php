<?php

namespace App\Http\Controllers\B2B;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use Illuminate\Http\Request;

class BayiUrunController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $bayiId = optional($user->bayi)->id;

        $query = Urun::query()->with(['kategori', 'marka']);

        if ($request->filled('q')) {
            $query->arama($request->q);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('marka_id')) {
            $query->where('marka_id', $request->marka_id);
        }

        $query->aktif()->stokta();

        $urunler = $query->paginate(20)->withQueryString();

        // Bayi fiyatlarını map et
        $bayiFiyatlari = [];
        foreach ($urunler as $urun) {
            $bayiFiyatlari[$urun->id] = $urun->getBayiFiyati($bayiId);
        }

        return view('bayi.urunler', compact('urunler', 'bayiFiyatlari'));
    }
}
