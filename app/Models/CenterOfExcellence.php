<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CenterOfExcellence extends Model
{
    /** @use HasFactory<\Database\Factories\CenterOfExcellenceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'short_description', 'full_content', 'technology_used', 'slug', 'slider_images', 'show_slider', 'banner_image'];

    protected $casts = [
        'technology_used' => 'array',
        'slider_images' => 'array',
        'show_slider' => 'boolean',
    ];


}
