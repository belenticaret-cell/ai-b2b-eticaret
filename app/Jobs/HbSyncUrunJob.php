<?php

namespace App\Jobs;

use App\Models\Magaza;
use App\Models\Urun;
use App\Services\PlatformEntegrasyonService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HbSyncUrunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // saniye

    public function __construct(public int $magazaId, public int $urunId)
    {
    }

    public function handle(PlatformEntegrasyonService $service): void
    {
        $magaza = Magaza::find($this->magazaId);
        $urun = Urun::find($this->urunId);
        if (!$magaza || !$urun) return;
        // Tek ürün senkronu
        $service->urunleriSenkronize($magaza, [$urun->id]);
    }
}
