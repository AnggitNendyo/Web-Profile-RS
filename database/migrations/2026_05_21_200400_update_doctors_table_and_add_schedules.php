<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pivot table for doctor specialties
        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // 2. Add new columns to doctors table
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('front_title')->nullable()->after('id');
            $table->string('back_title')->nullable()->after('name');
            $table->integer('start_year')->nullable()->after('is_active');
        });

        // 3. Migrate existing data
        $doctors = DB::table('doctors')->get();
        $currentYear = (int) date('Y');

        foreach ($doctors as $doctor) {
            // Migrate specialty to pivot table
            if ($doctor->specialty_id) {
                DB::table('doctor_specialty')->insert([
                    'doctor_id' => $doctor->id,
                    'specialty_id' => $doctor->specialty_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Migrate experience_years to start_year
            $startYear = $doctor->experience_years ? ($currentYear - $doctor->experience_years) : $currentYear;

            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update([
                    'start_year' => $startYear
                ]);
        }

        // 4. Drop old columns
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn(['specialty_id', 'experience_years']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('experience_years')->default(0);
        });

        $doctors = DB::table('doctors')->get();
        $currentYear = (int) date('Y');

        foreach ($doctors as $doctor) {
            $specialty = DB::table('doctor_specialty')
                ->where('doctor_id', $doctor->id)
                ->first();
            
            $experience = $doctor->start_year ? ($currentYear - $doctor->start_year) : 0;

            DB::table('doctors')
                ->where('id', $doctor->id)
                ->update([
                    'specialty_id' => $specialty ? $specialty->specialty_id : null,
                    'experience_years' => $experience,
                ]);
        }

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['front_title', 'back_title', 'start_year']);
        });

        Schema::dropIfExists('doctor_specialty');
    }
};
