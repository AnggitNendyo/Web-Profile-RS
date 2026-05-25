<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'type',
        'description',
        'requirements',
        'apply_link',
        'apply_email',
        'is_active',
        'deadline',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline' => 'date',
    ];
}
