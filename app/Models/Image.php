<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Image extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->withResponsiveImages()
            ->nonQueued();
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (Image $image) {
            $image
                ->addMedia(resource_path($image->path))
                ->withResponsiveImages()
                ->preservingOriginal()
                ->toMediaCollection();
        });
    }

    public static function findByPath(string $path): ?Image
    {
        return static::wherePath($path)->first();
    }

    public static function createWithPath(string $path): Image
    {
        return static::create([
            'path' => static::normalizePath($path),
        ]);
    }

    public function scopeWherePath(Builder $builder, string $path): Builder
    {
        return $builder->where('path', static::normalizePath($path));
    }

    protected static function normalizePath(string $path): string
    {
        $resourcePath = 'images';

        $fullResourcePath = "resources/{$resourcePath}";

        $basePath = resource_path($resourcePath);

        return
            rtrim($resourcePath, '/')
            . '/'
            . ltrim(str_replace([$basePath, $fullResourcePath, $resourcePath], '', $path), '/');
    }
}
