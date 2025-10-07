<?php

namespace App\Http\Controllers\B2B;

use App\Http\Controllers\Controller;
use App\Models\SiteAyar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BayiAyarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->rol !== 'bayi') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $bayi = Auth::user()->bayi;
        // Bayi-specific ayarlar: anahtarları bayi_id ile namespaceleriz
        $prefix = 'bayi_'.$bayi->id.'_';
        $keys = ['magaza_ad','logo_url','telefon','adres','vitrin_aktif'];
        $ayarlar = [];
        foreach ($keys as $k) {
            $ayarlar[$k] = SiteAyar::get($prefix.$k);
        }
        return view('bayi.ayarlar', compact('bayi', 'ayarlar'));
    }

    public function kaydet(Request $request)
    {
        $bayi = Auth::user()->bayi;
        $data = $request->validate([
            'magaza_ad' => 'nullable|string|max:255',
            'telefon' => 'nullable|string|max:50',
            'adres' => 'nullable|string|max:1000',
            'vitrin_aktif' => 'required|in:0,1',
            'logo' => 'nullable|image|max:2048',
        ]);

        $prefix = 'bayi_'.$bayi->id.'_';

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/bayi_logolar');
            $url = str_replace('public/', 'storage/', $path);
            SiteAyar::set($prefix.'logo_url', asset($url));
        }

        foreach (['magaza_ad','telefon','adres','vitrin_aktif'] as $k) {
            if (array_key_exists($k, $data)) {
                SiteAyar::set($prefix.$k, (string) $data[$k]);
            }
        }

        return back()->with('success', 'Mağaza ayarları güncellendi.');
    }
}
