<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->longText('cover_image')->nullable()->after('content');
            $table->text('excerpt')->nullable()->after('cover_image');
            $table->foreignId('category_id')->nullable()->after('excerpt')
                  ->constrained('article_categories')->nullOnDelete();
            $table->string('meta_title')->nullable()->after('category_id');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'cover_image',
                'excerpt',
                'category_id',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
