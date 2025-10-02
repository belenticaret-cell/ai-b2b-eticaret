<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urun extends Model
{
    use HasFactory;

    protected $table = 'uruns';

    protected $fillable = [
        'ad',
        'sku',
        'aciklama',
        'fiyat',
        'stok',
        'resim',
        'seo',
        'gecmis_satis',
        'trend_score',
        'ai_score'
    ];

    protected $casts = [
        'fiyat' => 'decimal:2',
        'gecmis_satis' => 'array'
    ];

    public function sepets()
    {
        return $this->hasMany(Sepet::class);
    }

    public function getPotansiyelSkorAttribute()
    {
        $gecmis = $this->gecmis_satis ?? [10];
        $trend = $this->trend_score ?? 50;
        $ai = $this->ai_score ?? 50;
        
        return ($gecmis[0] * 0.4) + ($trend * 0.3) + ($ai * 0.3);
    }
}