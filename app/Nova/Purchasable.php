<?php

namespace App\Nova;

use App\Enums\PurchasableType;
use App\Models\Product as EloquentProduct;
use App\Models\Purchasable as EloquentPurchasable;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Purchasable extends Resource
{
    use HasSortableRows;

    public static $model = EloquentPurchasable::class;

    public static $title = 'name';

    public static $displayInNavigation = false;

    public static $search = [
        'id', 'title',
    ];

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Paddle id', 'paddle_product_id')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Type')->options(PurchasableType::getLabels()) ->rules('required'),

            Image::make('Image')
                ->store(function (Request $request, EloquentPurchasable $product) {
                    return function () use ($request, $product) {
                        $product
                            ->addMedia($request->file('image'))
                            ->toMediaCollection('purchasable-image');
                    };
                })
                ->thumbnail(function ($value) {
                    return $value;
                })
                ->preview(function ($value, $disk) {
                    return $value;
                })->delete(function ($request, EloquentPurchasable $product) {
                    $product->deleteMedia($product->getFirstMedia('purchasable-image'));

                    return [];
                }),

            Markdown::make('Description'),
            Boolean::make('Requires license'),

            BelongsTo::make('Product'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}