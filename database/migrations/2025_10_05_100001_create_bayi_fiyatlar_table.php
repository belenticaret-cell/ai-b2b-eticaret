<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bayi_fiyatlar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_id')->constrained('bayiler')->onDelete('cascade');
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade');
            $table->decimal('fiyat', 12, 2)->nullable();
            $table->decimal('iskonto_orani', 5, 2)->nullable(); // % cinsinden
            $table->dateTime('baslangic_tarihi')->nullable();
            $table->dateTime('bitis_tarihi')->nullable();
            $table->timestamps();
            $table->unique(['bayi_id', 'urun_id']);
            $table->index(['bayi_id']);
            $table->index(['urun_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bayi_fiyatlar');
    }
};
