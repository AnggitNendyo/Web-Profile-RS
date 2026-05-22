<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['front_title', 'name', 'back_title', 'title', 'start_year', 'education', 'photo_path', 'is_active'];

    protected $casts = [
        'education' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = ['full_name', 'experience_years'];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    public function getFullNameAttribute()
    {
        $front = $this->front_title ? $this->front_title . ' ' : '';
        $back = $this->back_title ? ', ' . $this->back_title : '';
        return trim("{$front}{$this->name}{$back}");
    }

    public function getExperienceYearsAttribute()
    {
        if (!$this->start_year) return 0;
        return max(0, (int) date('Y') - $this->start_year);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }
}
