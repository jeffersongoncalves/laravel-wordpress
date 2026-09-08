<?php

namespace Jeffersongoncalves\Wordpress;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Wordpress
{
    public function __construct(
        protected string $baseUrl,
        protected string $username,
        protected string $applicationPassword,
        protected string $namespace = 'wp-json/wp/v2',
    ) {}

    protected function client(): PendingRequest
    {
        return Http::baseUrl(rtrim($this->baseUrl, '/').'/'.trim($this->namespace, '/'))
            ->withBasicAuth($this->username, $this->applicationPassword)
            ->acceptJson();
    }

    // --- Posts ---

    public function listPosts(array $filters = []): Response
    {
        return $this->client()->get('/posts', $filters);
    }

    public function getPost(int $id, array $filters = []): Response
    {
        return $this->client()->get("/posts/{$id}", $filters);
    }

    public function createPost(array $attributes): Response
    {
        return $this->client()->post('/posts', $attributes);
    }

    public function updatePost(int $id, array $attributes): Response
    {
        return $this->client()->put("/posts/{$id}", $attributes);
    }

    public function deletePost(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/posts/{$id}", ['force' => $force]);
    }

    // --- Pages ---

    public function listPages(array $filters = []): Response
    {
        return $this->client()->get('/pages', $filters);
    }

    public function getPage(int $id, array $filters = []): Response
    {
        return $this->client()->get("/pages/{$id}", $filters);
    }

    public function createPage(array $attributes): Response
    {
        return $this->client()->post('/pages', $attributes);
    }

    public function updatePage(int $id, array $attributes): Response
    {
        return $this->client()->put("/pages/{$id}", $attributes);
    }

    public function deletePage(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/pages/{$id}", ['force' => $force]);
    }

    // --- Media ---

    public function listMedia(array $filters = []): Response
    {
        return $this->client()->get('/media', $filters);
    }

    public function getMedia(int $id, array $filters = []): Response
    {
        return $this->client()->get("/media/{$id}", $filters);
    }

    public function uploadMedia(string $filename, string $contents, string $mimeType, array $attributes = []): Response
    {
        return $this->client()
            ->withBody($contents, $mimeType)
            ->withHeaders(['Content-Disposition' => "attachment; filename=\"{$filename}\""])
            ->post('/media?'.http_build_query($attributes));
    }

    public function updateMedia(int $id, array $attributes): Response
    {
        return $this->client()->put("/media/{$id}", $attributes);
    }

    public function deleteMedia(int $id, bool $force = true): Response
    {
        return $this->client()->delete("/media/{$id}", ['force' => $force]);
    }

    // --- Categories ---

    public function listCategories(array $filters = []): Response
    {
        return $this->client()->get('/categories', $filters);
    }

    public function getCategory(int $id, array $filters = []): Response
    {
        return $this->client()->get("/categories/{$id}", $filters);
    }

    public function createCategory(string $name, ?string $slug = null, array $attributes = []): Response
    {
        return $this->client()->post('/categories', array_filter([
            'name' => $name,
            'slug' => $slug,
            ...$attributes,
        ], fn ($value) => $value !== null));
    }

    public function updateCategory(int $id, array $attributes): Response
    {
        return $this->client()->put("/categories/{$id}", $attributes);
    }

    public function deleteCategory(int $id): Response
    {
        return $this->client()->delete("/categories/{$id}", ['force' => true]);
    }

    // --- Tags ---

    public function listTags(array $filters = []): Response
    {
        return $this->client()->get('/tags', $filters);
    }

    public function getTag(int $id, array $filters = []): Response
    {
        return $this->client()->get("/tags/{$id}", $filters);
    }

    public function createTag(string $name, ?string $slug = null, array $attributes = []): Response
    {
        return $this->client()->post('/tags', array_filter([
            'name' => $name,
            'slug' => $slug,
            ...$attributes,
        ], fn ($value) => $value !== null));
    }

    public function updateTag(int $id, array $attributes): Response
    {
        return $this->client()->put("/tags/{$id}", $attributes);
    }

    public function deleteTag(int $id): Response
    {
        return $this->client()->delete("/tags/{$id}", ['force' => true]);
    }

    // --- Comments ---

    public function listComments(array $filters = []): Response
    {
        return $this->client()->get('/comments', $filters);
    }

    public function getComment(int $id, array $filters = []): Response
    {
        return $this->client()->get("/comments/{$id}", $filters);
    }

    public function createComment(array $attributes): Response
    {
        return $this->client()->post('/comments', $attributes);
    }

    public function updateComment(int $id, array $attributes): Response
    {
        return $this->client()->put("/comments/{$id}", $attributes);
    }

    public function deleteComment(int $id, bool $force = false): Response
    {
        return $this->client()->delete("/comments/{$id}", ['force' => $force]);
    }

    // --- Users ---

    public function listUsers(array $filters = []): Response
    {
        return $this->client()->get('/users', $filters);
    }

    public function getUser(int $id, array $filters = []): Response
    {
        return $this->client()->get("/users/{$id}", $filters);
    }

    public function currentUser(array $filters = []): Response
    {
        return $this->client()->get('/users/me', $filters);
    }
}
