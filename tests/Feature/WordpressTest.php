<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Jeffersongoncalves\Wordpress\Facades\Wordpress;

beforeEach(function () {
    Http::preventStrayRequests();
});

it('lists posts with filters and basic auth', function () {
    Http::fake(['example.com/wp-json/wp/v2/posts*' => Http::response([])]);

    $response = Wordpress::listPosts(['per_page' => 10, 'status' => 'publish']);

    expect($response->successful())->toBeTrue();
    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wp/v2/posts')
        && $request['per_page'] === 10
        && $request['status'] === 'publish'
        && $request->hasHeader('Authorization', 'Basic '.base64_encode('admin:fake app password')));
});

it('gets a post', function () {
    Http::fake(['example.com/wp-json/wp/v2/posts/123' => Http::response(['id' => 123])]);

    $response = Wordpress::getPost(123);

    expect($response->json('id'))->toBe(123);
    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && $request->url() === 'https://example.com/wp-json/wp/v2/posts/123');
});

it('creates a post', function () {
    Http::fake(['example.com/wp-json/wp/v2/posts' => Http::response(['id' => 1], 201)]);

    $response = Wordpress::createPost([
        'title' => 'Post Title',
        'content' => '<p>Post content here</p>',
        'status' => 'draft',
        'categories' => [1],
    ]);

    expect($response->status())->toBe(201);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wp/v2/posts'
        && $request['title'] === 'Post Title'
        && $request['status'] === 'draft'
        && $request['categories'] === [1]);
});

it('updates a post', function () {
    Http::fake(['example.com/wp-json/wp/v2/posts/123' => Http::response(['id' => 123])]);

    $response = Wordpress::updatePost(123, ['title' => 'Updated Title', 'status' => 'publish']);

    expect($response->successful())->toBeTrue();
    Http::assertSent(fn (Request $request) => $request->method() === 'PUT'
        && $request->url() === 'https://example.com/wp-json/wp/v2/posts/123'
        && $request['status'] === 'publish');
});

it('deletes a post, trashing it by default', function () {
    Http::fake(['example.com/wp-json/wp/v2/posts/123*' => Http::response([])]);

    Wordpress::deletePost(123);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wp/v2/posts/123')
        && $request['force'] === false);
});

it('lists pages', function () {
    Http::fake(['example.com/wp-json/wp/v2/pages*' => Http::response([])]);

    Wordpress::listPages(['per_page' => 20]);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wp/v2/pages')
        && $request['per_page'] === 20);
});

it('creates a page', function () {
    Http::fake(['example.com/wp-json/wp/v2/pages' => Http::response(['id' => 9], 201)]);

    $response = Wordpress::createPage(['title' => 'About', 'status' => 'publish']);

    expect($response->status())->toBe(201);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wp/v2/pages'
        && $request['title'] === 'About');
});

it('uploads media as a raw body with a content disposition header', function () {
    Http::fake(['example.com/wp-json/wp/v2/media*' => Http::response(['id' => 42], 201)]);

    $response = Wordpress::uploadMedia('image.jpg', 'binary-data', 'image/jpeg', ['post' => 123]);

    expect($response->json('id'))->toBe(42);
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wp/v2/media?post=123'
        && $request->body() === 'binary-data'
        && $request->hasHeader('Content-Type', 'image/jpeg')
        && $request->hasHeader('Content-Disposition', 'attachment; filename="image.jpg"'));
});

it('deletes media with force by default', function () {
    Http::fake(['example.com/wp-json/wp/v2/media/42*' => Http::response([])]);

    Wordpress::deleteMedia(42);

    Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
        && $request['force'] === true);
});

it('lists categories', function () {
    Http::fake(['example.com/wp-json/wp/v2/categories*' => Http::response([])]);

    Wordpress::listCategories();

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wp/v2/categories'));
});

it('creates a category and drops the null slug', function () {
    Http::fake(['example.com/wp-json/wp/v2/categories' => Http::response(['id' => 5], 201)]);

    Wordpress::createCategory('Category Name');

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['name'] === 'Category Name'
        && ! array_key_exists('slug', $request->data()));
});

it('creates a tag with a slug and extra attributes', function () {
    Http::fake(['example.com/wp-json/wp/v2/tags' => Http::response(['id' => 6], 201)]);

    Wordpress::createTag('Tag Name', 'tag-name', ['description' => 'A tag']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === 'https://example.com/wp-json/wp/v2/tags'
        && $request['name'] === 'Tag Name'
        && $request['slug'] === 'tag-name'
        && $request['description'] === 'A tag');
});

it('creates a comment', function () {
    Http::fake(['example.com/wp-json/wp/v2/comments' => Http::response(['id' => 7], 201)]);

    Wordpress::createComment(['post' => 123, 'content' => 'Nice post']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request['post'] === 123
        && $request['content'] === 'Nice post');
});

it('lists users', function () {
    Http::fake(['example.com/wp-json/wp/v2/users*' => Http::response([])]);

    Wordpress::listUsers(['per_page' => 5]);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://example.com/wp-json/wp/v2/users')
        && $request['per_page'] === 5);
});

it('gets the current user', function () {
    Http::fake(['example.com/wp-json/wp/v2/users/me*' => Http::response(['id' => 1])]);

    expect(Wordpress::currentUser()->json('id'))->toBe(1);
});

it('normalises trailing slashes in the site url and namespace', function () {
    config()->set('wordpress.base_url', 'https://blog.test/');
    config()->set('wordpress.namespace', '/wp-json/wp/v2/');
    app()->forgetInstance(Jeffersongoncalves\Wordpress\Wordpress::class);
    Wordpress::clearResolvedInstances();

    Http::fake(['blog.test/wp-json/wp/v2/posts*' => Http::response([])]);

    Wordpress::listPosts();

    Http::assertSent(fn (Request $request) => str_starts_with($request->url(), 'https://blog.test/wp-json/wp/v2/posts'));
});
