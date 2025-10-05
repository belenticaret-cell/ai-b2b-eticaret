<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bayi;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BayiController extends Controller
{
    public function index(Request $request)
    {
        $q = Bayi::query()->with(['kullanici'])->withCount('fiyatlar');

        if ($s = $request->get('search')) {
            $q->where(function($qq) use ($s){
                $qq->where('ad','like',"%$s%")
                   ->orWhere('email','like',"%$s%")
                   ->orWhere('telefon','like',"%$s%")
                   ->orWhere('adres','like',"%$s%");
            });
        }

        $bayiler = $q->orderBy('ad')->paginate(20)->withQueryString();
        return view('admin.bayi.index', compact('bayiler'));
    }

    public function create()
    {
        $kullanicilar = Kullanici::where('rol','bayi')->orderBy('ad')->get(['id','ad','email']);
        return view('admin.bayi.create', compact('kullanicilar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'telefon' => ['nullable','string','max:50'],
            'adres' => ['nullable','string','max:1000'],
            'kullanici_id' => ['nullable','exists:kullanicilar,id'],
        ]);
        $createdUser = null;
        // Opsiyonel: Bayi için kullanıcı oluştur
        if (!$request->filled('kullanici_id') && $request->boolean('kullanici_olustur')) {
            $request->validate([
                'kullanici_email' => ['required','email','max:255','unique:kullanicilar,email'],
                'gecici_sifre' => ['nullable','string','min:8'],
            ], [], [
                'kullanici_email' => 'Kullanıcı E-posta',
                'gecici_sifre' => 'Geçici Şifre',
            ]);

            $plainPassword = $request->input('gecici_sifre') ?: Str::password(12);
            $createdUser = Kullanici::create([
                'ad' => $data['ad'],
                'email' => $request->input('kullanici_email'),
                'password' => $plainPassword,
                'rol' => 'bayi',
            ]);

            // Bayi kayıt payload'una bağla
            $data['kullanici_id'] = $createdUser->id;
        }

        $bayi = Bayi::create($data);

        $msg = 'Bayi eklendi.';
        if ($createdUser) {
            $loginUrl = route('b2b.login');
            $msg .= " Kullanıcı oluşturuldu. Giriş bilgileri → E-posta: " . $createdUser->email . " | Şifre: " . $plainPassword . " | Giriş: " . $loginUrl;
        }

        return redirect()->route('admin.bayi.index')->with('success', $msg);
    }

    public function edit(Bayi $bayi)
    {
        $kullanicilar = Kullanici::where('rol','bayi')->orderBy('ad')->get(['id','ad','email']);
        return view('admin.bayi.edit', compact('bayi','kullanicilar'));
    }

    public function update(Request $request, Bayi $bayi)
    {
        $data = $request->validate([
            'ad' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'telefon' => ['nullable','string','max:50'],
            'adres' => ['nullable','string','max:1000'],
            'kullanici_id' => ['nullable','exists:kullanicilar,id'],
        ]);
        $createdUser = null;
        if (!$request->filled('kullanici_id') && $request->boolean('kullanici_olustur')) {
            $request->validate([
                'kullanici_email' => ['required','email','max:255','unique:kullanicilar,email'],
                'gecici_sifre' => ['nullable','string','min:8'],
            ]);
            $plainPassword = $request->input('gecici_sifre') ?: \Illuminate\Support\Str::password(12);
            $createdUser = Kullanici::create([
                'ad' => $data['ad'],
                'email' => $request->input('kullanici_email'),
                'password' => $plainPassword,
                'rol' => 'bayi',
            ]);
            $data['kullanici_id'] = $createdUser->id;
        }

        $bayi->update($data);
        $msg = 'Bayi güncellendi.';
        if ($createdUser) {
            $loginUrl = route('b2b.login');
            $msg .= " Kullanıcı oluşturuldu. Giriş bilgileri → E-posta: " . $createdUser->email . " | Şifre: " . $plainPassword . " | Giriş: " . $loginUrl;
        }
        return redirect()->route('admin.bayi.index')->with('success', $msg);
    }

    public function destroy(Bayi $bayi)
    {
        $bayi->delete();
        return back()->with('success','Bayi silindi.');
    }

    public function show(Bayi $bayi)
    {
        $bayi->load(['kullanici']);
        $fiyatlar = $bayi->fiyatlar()->with('urun')->latest()->paginate(20);
        return view('admin.bayi.show', compact('bayi','fiyatlar'));
    }
}
