<?php

namespace App\Http\Middleware;

use App\Models\SiteAyar;
use Closure;
use Illuminate\Http\Request;

class ModuleActive
{
    public function handle(Request $request, Closure $next, string $module)
    {
        $map = [
            'entegrasyon' => 'modul_entegrasyon_aktif',
            'kargo' => 'modul_kargo_aktif',
            'odeme' => 'modul_odeme_aktif',
        ];

        $key = $map[$module] ?? null;
        if ($key && !(bool) SiteAyar::get($key, false)) {
            if ($request->expectsJson()) {
                return response()->json(['success'=>false,'message'=>'Modül pasif: '.$module], 403);
            }
            return redirect()->route('admin.moduller')->with('error', ucfirst($module).' modülü pasif. Lütfen aktifleştirin.');
        }

        return $next($request);
    }
}
