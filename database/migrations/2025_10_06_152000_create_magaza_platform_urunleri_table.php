<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magaza_platform_urunleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magaza_id')->constrained('magazalar')->onDelete('cascade');
            $table->string('platform', 50)->nullable()->index();
            $table->string('platform_urun_id')->nullable()->index();
            $table->string('platform_sku')->nullable()->index();
            $table->string('baslik')->nullable();
            $table->decimal('fiyat', 12, 2)->nullable();
            $table->integer('stok')->nullable();
            $table->json('ham_veri')->nullable();
            $table->foreignId('urun_id')->nullable()->constrained('urunler')->nullOnDelete();
            $table->timestamp('son_senkron')->nullable();
            $table->timestamps();
            $table->unique(['magaza_id','platform_sku']);
            $table->index(['magaza_id','urun_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magaza_platform_urunleri');
    }
};
