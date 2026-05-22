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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            // Informasi Umum
            $table->string('hospital_name')->default('RS Nusantara Medika');
            $table->text('hospital_description')->nullable();
            
            // Kontak
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
            $table->string('contact_emergency')->nullable();
            
            // Sosial Media
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_youtube')->nullable();
            
            // Beranda (Home)
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->integer('stats_years')->default(15);
            $table->integer('stats_patients')->default(50000);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
