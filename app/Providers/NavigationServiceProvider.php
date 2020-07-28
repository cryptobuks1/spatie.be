<?php

namespace App\Providers;

use Barryvdh\Debugbar\ServiceProvider;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class NavigationServiceProvider extends ServiceProvider
{
    public function register()
    {
        Menu::macro('main', function (array $properties = []) {
            return Menu::new()
                ->route('open-source.index', 'Open source')
                ->route('products.index', 'Products')
                ->route('videos.index', 'Videos')
                ->route('web-development', 'Web development')

                ->setActiveFromRequest()
                ->addClass($properties['class'] ?? '');
        });

        Menu::macro('service', function (array $properties = []) {
            return Menu::new()
                ->route('about', 'About us')
                ->setActiveFromRequest()
                ->addClass($properties['class'] ?? '');
        });

        Menu::macro('opensource', function () {
            return Menu::new()
                ->route('open-source.index', 'Overview')
                ->route('open-source.packages', 'Packages')
                ->route('open-source.projects', 'Projects')
                ->route('open-source.postcards', 'Postcard wall')
                ->route('open-source.support', 'Support us')
                ->addClass('leading-loose links-underline links-white')
                ->setActiveFromRequest('/open-source')
                ->setActiveClass('font-bold')
                ->each(function (Link $link) {
                    if ($link->isActive()) {
                        $link->prepend('<span class="absolute pin-l -ml-4 icon fill-white">' . svg('icons/far-angle-right') . '</span>');
                    }
                });
        });
    }
}
