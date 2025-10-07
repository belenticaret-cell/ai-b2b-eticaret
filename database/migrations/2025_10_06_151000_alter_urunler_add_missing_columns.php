<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            if (!Schema::hasColumn('urunler', 'slug')) {
                $table->string('slug')->nullable()->after('ad');
            }
            if (!Schema::hasColumn('urunler', 'bayi_fiyat')) {
                $table->decimal('bayi_fiyat', 10, 2)->nullable()->after('fiyat');
            }
            if (!Schema::hasColumn('urunler', 'minimum_stok')) {
                $table->integer('minimum_stok')->default(0)->after('stok');
            }
            if (!Schema::hasColumn('urunler', 'gorsel')) {
                $table->string('gorsel')->nullable()->after('resim');
            }
            if (!Schema::hasColumn('urunler', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->nullable()->after('gorsel');
            }
            if (!Schema::hasColumn('urunler', 'marka_id')) {
                $table->unsignedBigInteger('marka_id')->nullable()->after('kategori_id');
            }
            if (!Schema::hasColumn('urunler', 'durum')) {
                $table->boolean('durum')->default(true)->after('marka_id');
            }
            if (!Schema::hasColumn('urunler', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('durum');
            }
            if (!Schema::hasColumn('urunler', 'agirlik')) {
                $table->decimal('agirlik', 10, 2)->nullable()->after('aktif');
            }
            if (!Schema::hasColumn('urunler', 'boyutlar')) {
                $table->json('boyutlar')->nullable()->after('agirlik');
            }
            if (!Schema::hasColumn('urunler', 'seo_baslik')) {
                $table->string('seo_baslik')->nullable()->after('boyutlar');
            }
            if (!Schema::hasColumn('urunler', 'seo_aciklama')) {
                $table->text('seo_aciklama')->nullable()->after('seo_baslik');
            }
            if (!Schema::hasColumn('urunler', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('seo_aciklama');
            }
            if (!Schema::hasColumn('urunler', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('urunler', 'meta_etiketler')) {
                $table->json('meta_etiketler')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('urunler', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $cols = [
                'slug','bayi_fiyat','minimum_stok','gorsel','kategori_id','marka_id','durum','aktif','agirlik','boyutlar','seo_baslik','seo_aciklama','meta_title','meta_description','meta_etiketler'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('urunler', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('urunler', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
