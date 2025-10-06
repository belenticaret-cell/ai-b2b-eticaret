<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urun_ozellikler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade');
            $table->string('ad');
            $table->string('deger')->nullable();
            $table->string('birim')->nullable();
            $table->unsignedInteger('sira')->default(0);
            $table->timestamps();
            $table->index(['urun_id', 'ad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urun_ozellikler');
    }
};
