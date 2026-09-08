<?php

namespace Jeffersongoncalves\Wordpress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\Wordpress\Wordpress
 */
class Wordpress extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-wordpress';
    }
}
