<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_loglar';
    protected $fillable = [
        'kullanici_id', 'islem', 'model', 'model_id', 'payload', 'ip', 'user_agent'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }
}
