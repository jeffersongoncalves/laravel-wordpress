## Laravel WordPress

This package provides a wrapper around the [WordPress REST API](https://developer.wordpress.org/rest-api/) for posts, pages, media, categories, tags, comments and users.

### Installation

@verbatim
<code-snippet name="Install the package" lang="bash">
composer require jeffersongoncalves/laravel-wordpress
php artisan vendor:publish --tag="laravel-wordpress-config"
</code-snippet>
@endverbatim

Set `WORDPRESS_BASE_URL`, `WORDPRESS_USERNAME` and `WORDPRESS_APPLICATION_PASSWORD` in `.env`. The application password is generated in WordPress under Users > Your Profile > Application Passwords and is sent as HTTP Basic credentials.

### Features

- **Posts** and **Pages**: list, get, create, update and delete (trash by default, `force: true` to delete permanently).
- **Media**: list, get, update, delete and upload — `uploadMedia()` sends the raw file body with a `Content-Disposition` header, as the REST API requires.
- **Categories** and **Tags**: full CRUD, with `name`/`slug` shortcuts on create.
- **Comments** and **Users**: CRUD for comments, plus `listUsers`, `getUser` and `currentUser` (`/users/me`).

@verbatim
<code-snippet name="Create a draft post" lang="php">
use Jeffersongoncalves\Wordpress\Facades\Wordpress;

$response = Wordpress::createPost([
    'title' => 'Post Title',
    'content' => '<p>Post content here</p>',
    'status' => 'draft',
    'categories' => [1],
]);

$postId = $response->json('id');
</code-snippet>
@endverbatim

### Configuration

@verbatim
<code-snippet name="Config example" lang="php">
// config/wordpress.php
return [
    'base_url' => env('WORDPRESS_BASE_URL'),
    'username' => env('WORDPRESS_USERNAME'),
    'application_password' => env('WORDPRESS_APPLICATION_PASSWORD'),
    'namespace' => env('WORDPRESS_NAMESPACE', 'wp-json/wp/v2'),
];
</code-snippet>
@endverbatim

### Best Practices

- Every method returns an `Illuminate\Http\Client\Response` — check `->successful()`/`->status()` before trusting `->json()`.
- `$filters` and `$attributes` arrays go straight to the REST API, so use the parameter names WordPress documents (`per_page`, `status`, `search`, `_embed`, ...) instead of looking for a dedicated method.
- Use `Http::fake()` in tests instead of hitting a real WordPress site; the package resolves its HTTP client through Laravel's `Http` facade.
- Basic auth sends credentials on every request — always point `WORDPRESS_BASE_URL` at an HTTPS site.
