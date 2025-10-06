<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $islem, ?string $model = null, $modelId = null, array $payload = []): void
    {
        try {
            AuditLog::create([
                'kullanici_id' => Auth::id(),
                'islem' => $islem,
                'model' => $model,
                'model_id' => $modelId,
                'payload' => $payload,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Sessizce geç; log tablosu yoksa veya hata olursa uygulamayı etkilemesin
        }
    }
}
