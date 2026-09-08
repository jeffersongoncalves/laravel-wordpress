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
}
