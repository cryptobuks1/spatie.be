<?php

namespace App\Console\Commands;

use App\Models\Postcard;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob;

class RegeneratePostcardResponsiveImagesCommand extends Command
{
    protected $signature = 'postcards:regenerate';

    protected $description = 'Regenerates the responsive images for all the postcards';

    public function handle()
    {
        Postcard::each(function (Postcard $postcard) {
            $postcard->getMedia()->each(fn (Media $media) => GenerateResponsiveImagesJob::dispatch($media));
        });
    }
}
