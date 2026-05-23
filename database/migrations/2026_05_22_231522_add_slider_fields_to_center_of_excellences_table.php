<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('center_of_excellences', function (Blueprint $table) {
            $table->json('slider_images')->nullable()->after('slug');
            $table->boolean('show_slider')->default(true)->after('slider_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('center_of_excellences', function (Blueprint $table) {
            $table->dropColumn(['slider_images', 'show_slider']);
        });
    }
};
