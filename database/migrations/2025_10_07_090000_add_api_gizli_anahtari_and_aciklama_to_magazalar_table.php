<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('magazalar', function (Blueprint $table) {
            if (!Schema::hasColumn('magazalar', 'api_gizli_anahtari')) {
                $table->string('api_gizli_anahtari')->nullable()->after('api_anahtari');
            }
            if (!Schema::hasColumn('magazalar', 'aciklama')) {
                $table->string('aciklama', 500)->nullable()->after('test_mode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('magazalar', function (Blueprint $table) {
            if (Schema::hasColumn('magazalar', 'api_gizli_anahtari')) {
                $table->dropColumn('api_gizli_anahtari');
            }
            if (Schema::hasColumn('magazalar', 'aciklama')) {
                $table->dropColumn('aciklama');
            }
        });
    }
};
