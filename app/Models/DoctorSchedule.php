<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['doctor_id', 'day_of_week', 'start_time', 'end_time', 'location_clinic'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
