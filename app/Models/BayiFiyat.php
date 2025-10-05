<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BayiFiyat extends Model
{
    protected $table = 'bayi_fiyatlar';

    protected $fillable = [
        'bayi_id', 'urun_id', 'fiyat', 'iskonto_orani'
    ];

    public function bayi(): BelongsTo
    {
        return $this->belongsTo(Bayi::class);
    }

    public function urun(): BelongsTo
    {
        return $this->belongsTo(Urun::class);
    }
}
