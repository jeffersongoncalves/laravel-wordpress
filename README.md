<div class="filament-hidden">

![Laravel WordPress](https://raw.githubusercontent.com/jeffersongoncalves/laravel-wordpress/main/art/jeffersongoncalves-laravel-wordpress.png)

</div>

# Laravel WordPress

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-wordpress.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-wordpress)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-wordpress/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-wordpress/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-wordpress/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-wordpress/actions?query=workflow%3A%22Fix+PHP+code+styling%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-wordpress.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-wordpress)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-wordpress.svg?style=flat-square)](LICENSE.md)

WordPress REST API integration for Laravel. A thin wrapper around the [WordPress REST API](https://developer.wordpress.org/rest-api/) covering posts, pages, media, categories, tags, comments and users, built on Laravel's HTTP client.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-wordpress
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-wordpress-config"
```

This is the contents of the published config file:

```php
return [
    'base_url' => env('WORDPRESS_BASE_URL'),
    'username' => env('WORDPRESS_USERNAME'),
    'application_password' => env('WORDPRESS_APPLICATION_PASSWORD'),
    'namespace' => env('WORDPRESS_NAMESPACE', 'wp-json/wp/v2'),
];
```

Generate an [Application Password](https://developer.wordpress.org/rest-api/reference/application-passwords/) in WordPress under **Users > Your Profile > Application Passwords**, then add your credentials to `.env`:

```
WORDPRESS_BASE_URL=https://example.com
WORDPRESS_USERNAME=your-wordpress-username
WORDPRESS_APPLICATION_PASSWORD=xxxx xxxx xxxx xxxx xxxx xxxx
```

Requests are authenticated with HTTP Basic (`Authorization: Basic base64(username:application_password)`), so the site must be served over HTTPS.

## Usage

You can use the `Wordpress` facade, or inject `Jeffersongoncalves\Wordpress\Wordpress` wherever you need it.

### Posts

```php
use Jeffersongoncalves\Wordpress\Facades\Wordpress;

Wordpress::listPosts(['per_page' => 10, 'status' => 'publish']);
Wordpress::getPost(123);

Wordpress::createPost([
    'title' => 'Post Title',
    'content' => '<p>Post content here</p>',
    'status' => 'draft',
    'categories' => [1],
    'tags' => [5, 6],
]);

Wordpress::updatePost(123, ['title' => 'Updated Title', 'status' => 'publish']);

Wordpress::deletePost(123);              // moves to trash
Wordpress::deletePost(123, force: true); // deletes permanently
```

Post statuses: `publish`, `draft`, `pending`, `private`, `future`, `trash`.

### Pages

```php
Wordpress::listPages(['per_page' => 20]);
Wordpress::getPage(9);
Wordpress::createPage(['title' => 'About', 'content' => '<p>Hello</p>', 'status' => 'publish']);
Wordpress::updatePage(9, ['title' => 'About us']);
Wordpress::deletePage(9);
```

### Media

```php
Wordpress::listMedia(['per_page' => 20]);
Wordpress::getMedia(42);

Wordpress::uploadMedia(
    filename: 'image.jpg',
    contents: file_get_contents('/path/to/image.jpg'),
    mimeType: 'image/jpeg',
    attributes: ['post' => 123],
);

Wordpress::updateMedia(42, ['alt_text' => 'A description']);
Wordpress::deleteMedia(42);
```

### Categories and tags

```php
Wordpress::listCategories();
Wordpress::getCategory(1);
Wordpress::createCategory('Category Name', 'category-name');
Wordpress::updateCategory(1, ['description' => 'Updated']);
Wordpress::deleteCategory(1);

Wordpress::listTags();
Wordpress::getTag(5);
Wordpress::createTag('Tag Name', 'tag-name');
Wordpress::updateTag(5, ['description' => 'Updated']);
Wordpress::deleteTag(5);
```

### Comments

```php
Wordpress::listComments(['post' => 123]);
Wordpress::getComment(7);
Wordpress::createComment(['post' => 123, 'content' => 'Nice post']);
Wordpress::updateComment(7, ['status' => 'approved']);
Wordpress::deleteComment(7);
```

### Users

```php
Wordpress::listUsers(['per_page' => 10]);
Wordpress::getUser(1);
Wordpress::currentUser();
```

Every method returns an `Illuminate\Http\Client\Response`, so you can use `->json()`, `->successful()`, `->status()`, etc. as usual. `$filters` and `$attributes` arrays are passed straight through to the REST API, so any parameter documented by WordPress is supported.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Jefferson Simão Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
