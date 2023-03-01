<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\Page;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanIndex()
    {
        $users = User::factory()->count(4)->create();

        $this->getJson(route('users.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' => $users->toArray()
            ]);
    }


    /**
     * @test
     */
    public function itCanIndexWithRouteResource()
    {
        $users = User::factory()->count(4)->create();

        $this->getJson(route('users.v1.index'))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $users->map(fn (User $user) => ['id' => $user->id, 'test' => 'test f'])->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithPagination()
    {
        $page1 = User::factory()->count(2)->create();
        $page2 = User::factory()->count(2)->create();

        $this->getJson(route('users.index', ['paginate' => 2]))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $page1->toArray(),
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 2,
                    'per_page' => 2,
                    'to' => 2,
                    'total' => 4
                ]
            ]);

        $this->getJson(route('users.index', ['paginate' => 2, 'page' => 2]))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $page2->toArray(),
                'meta' => [
                    'current_page' => 2,
                    'from' => 3,
                    'last_page' => 2,
                    'per_page' => 2,
                    'to' => 4,
                    'total' => 4
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithDefaultParams()
    {
        $from = now()->subDays(2);
        $posts = Post::factory()->count(2)->create([
            'created_at' => $from
        ]);

        Post::factory()->count(2)->create([
            'created_at' => now()->subDays(4)
        ]);

        $this->getJson(route('posts.index', ['from' => $from->format('Y-m-d'), 'resource' => 'off']))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $posts->toArray()
            ]);
    }


    /**
     * @test
     */
    public function itCanIndexWithDatabaseColumnsAsParams()
    {
        $user = User::factory()->create();
        User::factory()->count(4)->create();

        $this->getJson(route('users.index', ['email' => $user->email]))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$user->toArray()]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithCustomParams()
    {
        $user = User::factory()->create([
            'name' => 'tycho'
        ]);

        User::factory()->count(4)->create();

        $this->getJson(route('users.index', ['search' => 'ych']))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$user->toArray()]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithSelect()
    {
        $users = User::factory()->count(4)->create();

        // Request fields as string
        $this->getJson(route('users.index', ['select' => 'name,email']))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $users->map(fn(User $user) => $user->only('email', 'name'))->toArray()
            ]);


        // Request fields as array
        $this->getJson(route('users.index', ['select' => ['email', 'name']]))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $users->map(fn(User $user) => $user->only('email', 'name'))->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithSelectAndResource()
    {
        $posts = Post::factory()->count(4)->create();

        $this->getJson(route('posts.index', ['select' => 'id,title']))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $posts->map(fn(Post $post) => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'title_short' => Str::limit($post->title, 3)
                ])
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexDetectResource()
    {
        $posts = Post::factory()->count(2)->create();

        $this->getJson(route('posts.index', ['paginate' => 2]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $posts->map(fn(Post $post) => $post->only('title'))->toArray(),
                'meta' => [
                    'current_page' => 1
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexDetectCollection()
    {
        $posts = Page::factory()->count(2)->create();

        $this->getJson(route('pages.index', ['paginate' => 2]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $posts->toArray(),
                'meta' => [
                    'current_page' => 1
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexResourceOff()
    {
        $posts = Page::factory()->count(2)->create();

        $this->getJson(route('pages.index', ['resource' => 'off']))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $posts->toArray(),
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexOnlyTrashed()
    {
        Post::factory()->count(2)->create();
        $trash = Post::factory()->create();
        $trash->delete();

        $this->getJson(route('posts.index', ['only_trashed' => true]))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    ['id' => $trash->id],
                ],
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithTrashed()
    {
        $posts = Post::factory()->count(2)->create();
        $trash = Post::factory()->create();
        $trash->delete();

        $posts = $posts->push($trash);
        $this->getJson(route('posts.index', ['with_trashed' => true]))
            ->assertStatus(200)
            ->assertJsonCount($posts->count(), 'data')
            ->assertJson([
                'data' => $posts->map(fn(Post $post) => $post->only('id'))->toArray(),
            ]);
    }
}
