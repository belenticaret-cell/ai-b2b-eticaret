<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_loglar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kullanici_id')->nullable()->constrained('kullanicilar')->onDelete('set null');
            $table->string('islem'); // create, update, delete, login, import, export vs.
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();
            $table->index(['model','model_id']);
            $table->index(['islem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_loglar');
    }
};
