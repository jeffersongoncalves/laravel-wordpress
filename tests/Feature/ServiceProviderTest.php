<?php

use Jeffersongoncalves\Wordpress\Facades\Wordpress as WordpressFacade;
use Jeffersongoncalves\Wordpress\Wordpress;

it('registers the wordpress singleton', function () {
    expect(app(Wordpress::class))->toBeInstanceOf(Wordpress::class);
    expect(app(Wordpress::class))->toBe(app(Wordpress::class));
});

it('resolves the facade to the wordpress class', function () {
    expect(WordpressFacade::getFacadeRoot())->toBeInstanceOf(Wordpress::class);
});

it('merges the package config', function () {
    expect(config('wordpress.namespace'))->toBe('wp-json/wp/v2');
});
