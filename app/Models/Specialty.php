<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Specialty extends Model
{
    /** @use HasFactory<\Database\Factories\SpecialtyFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'icon', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }
}
