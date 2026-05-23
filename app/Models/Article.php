<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'author_id', 'published_at',
        'cover_image', 'excerpt', 'category_id',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = ['reading_time'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
    /**
     * Calculate reading time in minutes based on word count.
     * Average reading speed: ~200 words per minute.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) ceil($wordCount / 200));
    }
}
