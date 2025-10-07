<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagazaPlatformUrunu extends Model
{
    protected $table = 'magaza_platform_urunleri';

    protected $fillable = [
        'magaza_id',
        'platform',
        'platform_urun_id',
        'platform_sku',
        'baslik',
        'fiyat',
        'stok',
        'ham_veri',
        'urun_id',
        'son_senkron',
    ];

    protected $casts = [
        'ham_veri' => 'array',
        'son_senkron' => 'datetime',
        'fiyat' => 'decimal:2',
    ];

    public function magaza()
    {
        return $this->belongsTo(Magaza::class);
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
