<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Article extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;

    protected $fillable = [
        'title',
        'volanta',
        'slug',
        'excerpt',
        'body',
        'status',
        'published_at',
        'author_id',
        'section_id',
        'cover_image',
        'tags',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'featured_at',
        'allow_comments',
        'views_count',
        'reading_time',
        'show_author_name',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'featured_at' => 'datetime',
            'tags' => 'array',
            'meta_keywords' => 'array',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'views_count' => 'integer',
            'reading_time' => 'integer',
            'show_author_name' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->approved();
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function featuredBlocks()
    {
        return $this->hasMany(FeaturedBlock::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function calculateReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->body));
        $this->reading_time = ceil($wordCount / 200);
        return $this->reading_time;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getUrlAttribute(): string
    {
        return route('articles.show', $this->slug);
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageAttribute(): ?string
    {
        $media = $this->getFirstMedia('cover');
        return $media ? $media->getUrl() : null;
    }

    /**
     * Get the featured image URL with conversion
     */
    public function getFeaturedImageUrl(string $conversion = ''): ?string
    {
        $media = $this->getFirstMedia('cover');
        if (!$media) {
            return null;
        }
        
        return $conversion ? $media->getUrl($conversion) : $media->getUrl();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600)
            ->optimize()
            ->nonQueued();
    }

    public function getVisibleAuthorNameAttribute(): string
    {
        // Si el autor no debe mostrarse, devolvemos "Ndi Diario Digital - {Sección}" cuando exista sección
        if (!$this->show_author_name) {
            $sectionName = $this->section ? $this->section->name : null;
            return $sectionName ? "Ndi Diario Digital - {$sectionName}" : 'Ndi Diario Digital';
        }

        return $this->author ? $this->author->name : 'Ndi Diario Digital';
    }
}
