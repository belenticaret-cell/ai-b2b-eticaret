<?php

namespace App\Http\Controllers;

use App\Models\Urun;
use App\Models\Kategori;
use App\Models\Marka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VitrinController extends Controller
{
    // Ana sayfa - ürün listesi
    public function index(Request $request)
    {
        $query = Urun::query()->with(['kategori', 'marka']);
        $seciliKategori = null;
        $altKategoriler = collect();
        $breadcrumbs = [];
        
        // Kategori filtresi
        if ($request->kategori_id) {
            $seciliKategori = Kategori::with('parent', 'children')->find($request->kategori_id);
            if ($seciliKategori) {
                // Seçili kategori ve tüm alt kategorilerindeki ürünleri dahil et
                $altTum = $seciliKategori->getAllChildren()->pluck('id')->all();
                $ids = array_unique(array_merge([$seciliKategori->id], $altTum));
                $query->whereIn('kategori_id', $ids);
                // Alt kategori listesi
                $altKategoriler = $seciliKategori->children()->aktif()->orderBy('sira')->get();
                // Breadcrumbs
                $bc = [];
                $cur = $seciliKategori;
                while ($cur) {
                    array_unshift($bc, $cur);
                    $cur = $cur->parent;
                }
                $breadcrumbs = $bc;
            }
        }
        
        // Marka filtresi
        if ($request->marka_id) {
            $query->where('marka_id', $request->marka_id);
        }
        
        // Arama
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->where('ad', 'like', '%' . $request->q . '%')
                  ->orWhere('aciklama', 'like', '%' . $request->q . '%')
                  ->orWhere('sku', 'like', '%' . $request->q . '%');
            });
        }
        
        // Fiyat aralığı
        if ($request->min_fiyat) {
            $query->where('fiyat', '>=', $request->min_fiyat);
        }
        if ($request->max_fiyat) {
            $query->where('fiyat', '<=', $request->max_fiyat);
        }
        
        // Sıralama
        $sirala = $request->sirala ?? 'yeni';
        switch ($sirala) {
            case 'fiyat_artan':
                $query->orderBy('fiyat', 'asc');
                break;
            case 'fiyat_azalan':
                $query->orderBy('fiyat', 'desc');
                break;
            case 'isim':
                $query->orderBy('ad', 'asc');
                break;
            default:
                $query->latest('id');
                break;
        }
        
        $urunler = $query->paginate(12)->withQueryString();

        // Bu sayfadaki ürünler için mağaza eşleştirmelerini çek
        $urunMagazalari = [];
        if ($urunler->count() > 0) {
            $ids = $urunler->pluck('id')->all();
            $rows = DB::table('magaza_urun')
                ->join('magazalar', 'magazalar.id', '=', 'magaza_urun.magaza_id')
                ->whereIn('magaza_urun.urun_id', $ids)
                ->select('magaza_urun.urun_id', 'magazalar.ad', 'magazalar.platform')
                ->get();
            foreach ($rows as $r) {
                $urunMagazalari[$r->urun_id][] = [
                    'ad' => $r->ad,
                    'platform' => $r->platform,
                ];
            }
        }
        
        // Filtre ve menü için kategoriler
        $kategoriler = Kategori::orderBy('ad')->get();
        $kategoriAgaci = Kategori::aktif()->whereNull('parent_id')
            ->with(['children' => function($q){
                $q->aktif()->orderBy('sira');
            }, 'children.children' => function($q){
                $q->aktif()->orderBy('sira');
            }])
            ->orderBy('sira')
            ->get();
        $markalar = Marka::orderBy('ad')->get();

        // Site ayarları
        $siteAyarlar = [];
        if (class_exists(\App\Models\SiteAyar::class)) {
            $siteAyarlar = \App\Models\SiteAyar::pluck('deger', 'anahtar')->toArray();
        }

        return view('vitrin.index', compact(
            'urunler',
            'urunMagazalari',
            'kategoriler',
            'markalar',
            'siteAyarlar',
            'kategoriAgaci',
            'seciliKategori',
            'altKategoriler',
            'breadcrumbs'
        ));
    }

    // Ürün detay
    public function urunDetay($id)
    {
        $urun = Urun::with(['kategori', 'marka'])->findOrFail($id);
        
        $magazalar = DB::table('magaza_urun')
            ->join('magazalar', 'magazalar.id', '=', 'magaza_urun.magaza_id')
            ->where('magaza_urun.urun_id', $urun->id)
            ->select('magazalar.ad', 'magazalar.platform')
            ->get();
            
        // Benzer ürünler
        $benzerUrunler = Urun::where('kategori_id', $urun->kategori_id)
            ->where('id', '!=', $urun->id)
            ->limit(4)
            ->get();

        // Site ayarları
        $siteAyarlar = [];
        if (class_exists(\App\Models\SiteAyar::class)) {
            $siteAyarlar = \App\Models\SiteAyar::pluck('deger', 'anahtar')->toArray();
        }
            
    return view('vitrin.detay', compact('urun', 'magazalar', 'benzerUrunler', 'siteAyarlar'));
    }

    // Ürünler sayfası
    public function urunler(Request $request)
    {
        return $this->index($request);
    }

    // Sepet sayfası
    public function sepet()
    {
        // Site ayarları
        $siteAyarlar = [];
        if (class_exists(\App\Models\SiteAyar::class)) {
            $siteAyarlar = \App\Models\SiteAyar::pluck('deger', 'anahtar')->toArray();
        }

        return view('vitrin.sepet', compact('siteAyarlar'));
    }

    // Ödeme sayfası
    public function odeme()
    {
        // Site ayarları
        $siteAyarlar = [];
        if (class_exists(\App\Models\SiteAyar::class)) {
            $siteAyarlar = \App\Models\SiteAyar::pluck('deger', 'anahtar')->toArray();
        }

        return view('vitrin.odeme', compact('siteAyarlar'));
    }
    
    // Kategori sayfası
    public function kategori(Kategori $kategori, Request $request)
    {
        $request->merge(['kategori_id' => $kategori->id]);
        return $this->index($request);
    }

    // Kategori slug ile
    public function kategoriSlug(string $slug, Request $request)
    {
        $kategori = Kategori::where('slug', $slug)->firstOrFail();
        $request->merge(['kategori_id' => $kategori->id]);
        return $this->index($request);
    }
    
    // Arama sayfası
    public function arama(Request $request)
    {
        if (!$request->q) {
            return redirect()->route('vitrin.index');
        }
        
        return $this->index($request);
    }
}
