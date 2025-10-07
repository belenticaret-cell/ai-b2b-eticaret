<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siparis;
use Illuminate\Http\Request;

class SiparisController extends Controller
{
    public function index(Request $request)
    {
        $q = Siparis::query()->with(['kullanici','magaza']);

        if ($request->filled('durum')) {
            $q->where('durum', $request->get('durum'));
        }
        if ($request->filled('search')) {
            $s = $request->get('search');
            $q->where(function($qq) use ($s) {
                $qq->where('siparis_no', 'like', "%{$s}%")
                   ->orWhere('durum', 'like', "%{$s}%")
                   ->orWhereHas('kullanici', function($uq) use ($s) { $uq->where('ad','like',"%{$s}%")->orWhere('email','like',"%{$s}%"); });
            });
        }

        $siparisler = $q->latest('id')->paginate(20)->withQueryString();
        $durumlar = ['beklemede','yeni','onaylandi','hazirlandi','kargolandi','teslim_edildi','iptal_edildi'];

        return view('admin.siparis.index', compact('siparisler','durumlar'));
    }

    public function show(Siparis $siparis)
    {
        $siparis->load(['kullanici','magaza','urunler.urun']);
        return view('admin.siparis.show', compact('siparis'));
    }
}
