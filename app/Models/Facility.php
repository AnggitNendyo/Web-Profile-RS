<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Facility extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\FacilityFactory> */
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category',
        'name',
        'description',
        'price_estimation',
        'bed_capacity',
        'features',
        'facility_category_id'
    ];

    public function facilityCategory()
    {
        return $this->belongsTo(FacilityCategory::class, 'facility_category_id');
    }

    protected $casts = [
        'features' => 'array',
    ];

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
