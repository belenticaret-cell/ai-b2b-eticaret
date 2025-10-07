<?php

namespace App\Jobs;

use App\Models\Magaza;
use App\Services\PlatformEntegrasyonService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HbSyncFiyatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $magazaId)
    {
    }

    public function handle(PlatformEntegrasyonService $service): void
    {
        $magaza = Magaza::find($this->magazaId);
        if (!$magaza) return;
        $service->fiyatSenkronize($magaza);
    }
}
