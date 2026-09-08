<?php

namespace Jeffersongoncalves\Wordpress;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WordpressServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-wordpress')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
