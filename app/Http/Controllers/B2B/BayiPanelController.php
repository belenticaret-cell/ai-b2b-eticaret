<?php

namespace App\Http\Controllers\B2B;

use App\Http\Controllers\Controller;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\Siparis;
use App\Models\SiparisUrunu;
use App\Models\BayiFiyat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BayiPanelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->rol !== 'bayi') {
                abort(403, 'Bu sayfaya erişim yetkiniz yok.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $bayi = Auth::user()->bayi;
        if (!$bayi) {
            abort(404, 'Bayi bilgisi bulunamadı.');
        }

        // Bayi istatistikleri
        $stats = [
            'toplam_siparis' => Siparis::where('bayi_id', $bayi->id)->count(),
            'aktif_urun' => BayiFiyat::where('bayi_id', $bayi->id)->count(),
            'bu_ay_siparis' => Siparis::where('bayi_id', $bayi->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'bekleyen_siparis' => Siparis::where('bayi_id', $bayi->id)
                ->where('durum', 'bekliyor')
                ->count(),
        ];

        // Son siparişler
        $sonSiparisler = Siparis::where('bayi_id', $bayi->id)
            ->with(['siparisUrunleri.urun'])
            ->latest()
            ->take(5)
            ->get();

        // En çok satılan ürünler
        $populerUrunler = SiparisUrunu::whereHas('siparis', function($q) use ($bayi) {
                $q->where('bayi_id', $bayi->id);
            })
            ->with('urun')
            ->selectRaw('urun_id, SUM(adet) as toplam_adet')
            ->groupBy('urun_id')
            ->orderByDesc('toplam_adet')
            ->take(5)
            ->get();

    return view('bayi.dashboard', compact('bayi', 'stats', 'sonSiparisler', 'populerUrunler'));
    }

    public function urunler(Request $request)
    {
        $bayi = Auth::user()->bayi;
        
        $query = BayiFiyat::where('bayi_id', $bayi->id)
            ->with(['urun.kategori', 'urun.marka']);

        // Arama
        if ($request->q) {
            $query->whereHas('urun', function($q) use ($request) {
                $q->where('ad', 'like', '%' . $request->q . '%')
                  ->orWhere('sku', 'like', '%' . $request->q . '%');
            });
        }

        // Kategori filtresi
        if ($request->kategori_id) {
            $query->whereHas('urun', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }

        $bayiFiyatlar = $query->paginate(20)->withQueryString();
        $kategoriler = Kategori::orderBy('ad')->get();

    return view('bayi.urunler', compact('bayiFiyatlar', 'kategoriler'));
    }

    public function siparisler(Request $request)
    {
        $bayi = Auth::user()->bayi;
        
        $query = Siparis::where('bayi_id', $bayi->id)
            ->with(['siparisUrunleri.urun']);

        // Durum filtresi
        if ($request->durum) {
            $query->where('durum', $request->durum);
        }

        // Tarih filtresi
        if ($request->baslangic_tarihi) {
            $query->where('created_at', '>=', $request->baslangic_tarihi);
        }
        if ($request->bitis_tarihi) {
            $query->where('created_at', '<=', $request->bitis_tarihi . ' 23:59:59');
        }

        $siparisler = $query->latest()->paginate(15)->withQueryString();

    return view('bayi.siparisler', compact('siparisler'));
    }

    public function siparisDetay($id)
    {
        $bayi = Auth::user()->bayi;
        
        $siparis = Siparis::where('bayi_id', $bayi->id)
            ->with(['siparisUrunleri.urun'])
            ->findOrFail($id);

    return view('bayi.siparis-detay', compact('siparis'));
    }

    public function topluSiparis()
    {
        $bayi = Auth::user()->bayi;
        
        // Bayi'nin fiyatlı ürünleri
        $bayiFiyatlar = BayiFiyat::where('bayi_id', $bayi->id)
            ->with(['urun.kategori'])
            ->get()
            ->groupBy('urun.kategori.ad');

    return view('bayi.toplu-siparis', compact('bayiFiyatlar'));
    }

    public function cariHesap()
    {
        $bayi = Auth::user()->bayi;
        
        // Cari hesap özeti
        $cariOzet = [
            'toplam_borc' => 0, // Mock data
            'odenen_tutar' => 0,
            'kalan_borc' => 0,
            'kredi_limiti' => 50000, // Mock data
            'kullanilabilir_kredi' => 50000,
        ];

        // Son hareketler
        $cariHareketler = Siparis::where('bayi_id', $bayi->id)
            ->latest()
            ->take(10)
            ->get();

    return view('bayi.cari-hesap', compact('cariOzet', 'cariHareketler'));
    }

    public function profil()
    {
        $bayi = Auth::user()->bayi;
    return view('bayi.profil', compact('bayi'));
    }

    public function profilGuncelle(Request $request)
    {
        $request->validate([
            'ad' => 'required|string|max:255',
            'telefon' => 'required|string|max:20',
            'adres' => 'required|string|max:500',
        ]);

        $bayi = Auth::user()->bayi;
        $bayi->update($request->only(['ad', 'telefon', 'adres']));

        return back()->with('success', 'Profil bilgileriniz güncellendi.');
    }
}