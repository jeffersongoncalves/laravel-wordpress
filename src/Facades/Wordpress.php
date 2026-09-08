<?php

namespace Jeffersongoncalves\Wordpress\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Client\Response listPosts(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getPost(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response createPost(array $attributes)
 * @method static \Illuminate\Http\Client\Response updatePost(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deletePost(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listPages(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getPage(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response createPage(array $attributes)
 * @method static \Illuminate\Http\Client\Response updatePage(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deletePage(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listMedia(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getMedia(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response uploadMedia(string $filename, string $contents, string $mimeType, array $attributes = [])
 * @method static \Illuminate\Http\Client\Response updateMedia(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteMedia(int $id, bool $force = true)
 * @method static \Illuminate\Http\Client\Response listCategories(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getCategory(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response createCategory(string $name, ?string $slug = null, array $attributes = [])
 * @method static \Illuminate\Http\Client\Response updateCategory(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteCategory(int $id)
 * @method static \Illuminate\Http\Client\Response listTags(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getTag(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response createTag(string $name, ?string $slug = null, array $attributes = [])
 * @method static \Illuminate\Http\Client\Response updateTag(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteTag(int $id)
 * @method static \Illuminate\Http\Client\Response listComments(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getComment(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response createComment(array $attributes)
 * @method static \Illuminate\Http\Client\Response updateComment(int $id, array $attributes)
 * @method static \Illuminate\Http\Client\Response deleteComment(int $id, bool $force = false)
 * @method static \Illuminate\Http\Client\Response listUsers(array $filters = [])
 * @method static \Illuminate\Http\Client\Response getUser(int $id, array $filters = [])
 * @method static \Illuminate\Http\Client\Response currentUser(array $filters = [])
 *
 * @see \Jeffersongoncalves\Wordpress\Wordpress
 */
class Wordpress extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jeffersongoncalves\Wordpress\Wordpress::class;
    }
}
