<?php

namespace App\Models;

use App\Enums\PurchasableType;
use App\Http\Controllers\ProductsController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Markdown;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia, Sortable
{
    use InteractsWithMedia, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public $with = [
        'media',
        'purchasables',
    ];

    /**
     * @var mixed
     */
    private $purchasables;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('product-image')
            ->singleFile()
            ->withResponsiveImages();
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('product-image');
    }

    public function purchasables(): HasMany
    {
        return $this->hasMany(Purchasable::class);
    }

    public function purchasablesWithoutRenewals(): HasMany
    {
        return $this->hasMany(Purchasable::class)->whereNotIn('type', [
            PurchasableType::TYPE_STANDARD_RENEWAL,
            PurchasableType::TYPE_UNLIMITED_DOMAINS_RENEWAL,
        ]);
    }

    public function requiresLicense(): bool
    {
        return $this->purchasables->contains(fn (Purchasable $purchasable) => $purchasable->requires_license);
    }

    public function getUrl(): string
    {
        return action([ProductsController::class, 'show'], $this);
    }

    public function getDescriptionAttribute(string $value)
    {
        return Markdown::parse($value);
    }

    public function getLongDescriptionAttribute(string $value)
    {
        return Markdown::parse($value);
    }
}