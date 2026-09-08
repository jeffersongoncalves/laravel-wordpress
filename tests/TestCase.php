<?php

namespace Jeffersongoncalves\Wordpress\Tests;

use Jeffersongoncalves\Wordpress\WordpressServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WordpressServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('wordpress.base_url', 'https://example.com');
        $app['config']->set('wordpress.username', 'admin');
        $app['config']->set('wordpress.application_password', 'fake app password');
        $app['config']->set('wordpress.namespace', 'wp-json/wp/v2');
    }
}
