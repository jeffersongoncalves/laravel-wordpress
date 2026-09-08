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
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Wordpress::class, fn () => new Wordpress(
            baseUrl: (string) config('wordpress.base_url'),
            username: (string) config('wordpress.username'),
            applicationPassword: (string) config('wordpress.application_password'),
            namespace: (string) config('wordpress.namespace'),
        ));
    }
}
