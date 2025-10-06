<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrunOzellik extends Model
{
    use HasFactory;

    protected $table = 'urun_ozellikler';

    protected $fillable = [
        'urun_id',
        'ad',
        'deger',
        'birim',
        'sira',
    ];

    public function urun()
    {
        return $this->belongsTo(Urun::class);
    }
}
