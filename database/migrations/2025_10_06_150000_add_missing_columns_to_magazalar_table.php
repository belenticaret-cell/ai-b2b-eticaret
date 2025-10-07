<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magazalar', function (Blueprint $table) {
            if (!Schema::hasColumn('magazalar', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('entegrasyon_turu');
            }
            if (!Schema::hasColumn('magazalar', 'auto_senkron')) {
                $table->boolean('auto_senkron')->default(false)->after('aktif');
            }
            if (!Schema::hasColumn('magazalar', 'test_mode')) {
                $table->boolean('test_mode')->default(false)->after('auto_senkron');
            }
            if (!Schema::hasColumn('magazalar', 'api_url')) {
                $table->string('api_url')->nullable()->after('api_gizli_anahtar');
            }
            if (!Schema::hasColumn('magazalar', 'magaza_id')) {
                $table->string('magaza_id')->nullable()->after('platform');
            }
            if (!Schema::hasColumn('magazalar', 'komisyon_orani')) {
                $table->decimal('komisyon_orani', 5, 2)->nullable()->after('magaza_id');
            }
            if (!Schema::hasColumn('magazalar', 'son_senkron_tarihi')) {
                $table->timestamp('son_senkron_tarihi')->nullable()->after('son_senkron');
            }
            if (!Schema::hasColumn('magazalar', 'son_baglanti_testi')) {
                $table->timestamp('son_baglanti_testi')->nullable()->after('son_senkron_tarihi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('magazalar', function (Blueprint $table) {
            if (Schema::hasColumn('magazalar', 'aktif')) $table->dropColumn('aktif');
            if (Schema::hasColumn('magazalar', 'auto_senkron')) $table->dropColumn('auto_senkron');
            if (Schema::hasColumn('magazalar', 'test_mode')) $table->dropColumn('test_mode');
            if (Schema::hasColumn('magazalar', 'api_url')) $table->dropColumn('api_url');
            if (Schema::hasColumn('magazalar', 'magaza_id')) $table->dropColumn('magaza_id');
            if (Schema::hasColumn('magazalar', 'komisyon_orani')) $table->dropColumn('komisyon_orani');
            if (Schema::hasColumn('magazalar', 'son_senkron_tarihi')) $table->dropColumn('son_senkron_tarihi');
            if (Schema::hasColumn('magazalar', 'son_baglanti_testi')) $table->dropColumn('son_baglanti_testi');
        });
    }
};
