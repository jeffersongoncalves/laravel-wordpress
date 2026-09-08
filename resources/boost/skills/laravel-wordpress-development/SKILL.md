---
name: laravel-wordpress-development
description: Build and work with the Laravel WordPress REST API client, including posts, pages, media, categories, tags, comments and users.
---

# Laravel WordPress Development

## When to use this skill

Use this skill when:
- Calling the WordPress REST API from a Laravel app (posts, pages, media, categories, tags, comments, users)
- Configuring the `WORDPRESS_BASE_URL` / `WORDPRESS_USERNAME` / `WORDPRESS_APPLICATION_PASSWORD` environment variables
- Writing tests that mock WordPress HTTP calls

## Core Concepts

### The `Wordpress` class

`Jeffersongoncalves\Wordpress\Wordpress` is bound as a singleton in the container, built from `config('wordpress.*')`. Every public method sends a request via Laravel's `Http` facade (HTTP Basic authenticated with the application password) and returns an `Illuminate\Http\Client\Response`.

The client base URL is `{base_url}/{namespace}` — by default `https://example.com/wp-json/wp/v2` — with trailing slashes normalised, so method paths are plain (`/posts`, `/media/42`).

```php
use Jeffersongoncalves\Wordpress\Facades\Wordpress;

$response = Wordpress::getPost(123);

if ($response->successful()) {
    $post = $response->json();
}
```

### Resource coverage

| Resource   | Methods |
|------------|---------|
| Posts      | `listPosts`, `getPost`, `createPost`, `updatePost`, `deletePost` |
| Pages      | `listPages`, `getPage`, `createPage`, `updatePage`, `deletePage` |
| Media      | `listMedia`, `getMedia`, `uploadMedia`, `updateMedia`, `deleteMedia` |
| Categories | `listCategories`, `getCategory`, `createCategory`, `updateCategory`, `deleteCategory` |
| Tags       | `listTags`, `getTag`, `createTag`, `updateTag`, `deleteTag` |
| Comments   | `listComments`, `getComment`, `createComment`, `updateComment`, `deleteComment` |
| Users      | `listUsers`, `getUser`, `currentUser` |

## Common Patterns

### Filtering collections

```php
Wordpress::listPosts([
    'per_page' => 25,
    'status' => 'publish',
    'search' => 'laravel',
    'after' => '2026-01-01T00:00:00',
]);
```

### Trash vs. permanent delete

```php
Wordpress::deletePost(123);              // status becomes `trash`
Wordpress::deletePost(123, force: true); // gone for good
```

Media has no trash in WordPress, so `deleteMedia()` forces by default. Terms (categories/tags) always delete with `force=true`.

### Uploading media

```php
Wordpress::uploadMedia(
    filename: 'image.jpg',
    contents: file_get_contents(storage_path('app/image.jpg')),
    mimeType: 'image/jpeg',
    attributes: ['post' => 123, 'alt_text' => 'A description'],
);
```

`$attributes` are sent as query parameters because the request body carries the binary file.

### Testing with `Http::fake()`

```php
use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\Wordpress\Facades\Wordpress;

Http::fake(['example.com/wp-json/wp/v2/posts/*' => Http::response(['id' => 123])]);

$response = Wordpress::getPost(123);

Http::assertSent(fn ($request) => $request->url() === 'https://example.com/wp-json/wp/v2/posts/123');
```

## Troubleshooting

### Error: 401 Unauthorized

**Causa**: wrong username, revoked application password, or a host that strips the `Authorization` header.

**Solução**:
```php
// Confirm the credentials resolve and the token is accepted
Wordpress::currentUser()->json();
```
If the credentials are right but requests still fail, the site is likely dropping `Authorization` (common with Apache + CGI); add `SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1` to `.htaccess`.

### Error: 403 rest_cannot_create

**Causa**: the user lacks the capability for that endpoint (e.g. a Subscriber creating posts).

**Solução**: use an account with the Author/Editor/Administrator role.

## API Reference

### `Wordpress::createPost(array $attributes)`

| Parameter     | Type     | Description                                             |
|---------------|----------|---------------------------------------------------------|
| `title`       | `string` | Post title                                              |
| `content`     | `string` | HTML content                                            |
| `status`      | `string` | `publish`, `draft`, `pending`, `private`, `future`       |
| `categories`  | `array`  | Category IDs                                            |
| `tags`        | `array`  | Tag IDs                                                 |

**Returns**: `Illuminate\Http\Client\Response`
