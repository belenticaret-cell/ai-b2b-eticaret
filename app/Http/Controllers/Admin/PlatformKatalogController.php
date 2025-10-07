<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magaza;
use App\Models\MagazaPlatformUrunu;
use Illuminate\Http\Request;

class PlatformKatalogController extends Controller
{
    public function index(Request $request)
    {
        $magazalar = Magaza::orderBy('ad')->get(['id','ad','platform']);
        $magazaId = (int) $request->get('magaza_id', 0);
        $q = trim((string) $request->get('q'));
        $durum = $request->get('durum'); // eslesmis|eslesmemis|null

        $platformUrunleri = collect();
        $magaza = null;
        if ($magazaId) {
            $magaza = $magazalar->firstWhere('id', $magazaId);
            $query = MagazaPlatformUrunu::where('magaza_id', $magazaId)->orderByDesc('updated_at');
            if ($q !== '') {
                $query->where(function($qq) use ($q) {
                    $qq->where('platform_sku','like',"%{$q}%")
                       ->orWhere('baslik','like',"%{$q}%");
                });
            }
            if ($durum === 'eslesmis') {
                $query->whereNotNull('urun_id');
            } elseif ($durum === 'eslesmemis') {
                $query->whereNull('urun_id');
            }
            $platformUrunleri = $query->paginate(25)->withQueryString();
        }

        return view('admin.entegrasyon.platform-katalog', compact('magazalar','magaza','platformUrunleri','magazaId','q','durum'));
    }
}
