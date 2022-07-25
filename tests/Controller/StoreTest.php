<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\Page;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanStore()
    {
        $page = Page::factory()->make();

        $this->postJson(route('pages.store'), $page->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => $page->toArray()
            ]);

        $this->assertDatabaseHas('pages', $page->toArray());
    }

    /**
     * @test
     */
    public function itCanStoreWithAuth()
    {
        $auth = User::factory()->create();
        $user = User::factory()->make();

        $this->actingAs($auth)->postJson(route('users.store'), $user->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => $user->toArray()
            ]);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /**
     * @test
     */
    public function itCanStoreDetectResource()
    {
        $post = Post::factory()->make();

        $this->postJson(route('posts.store'), $post->toArray())
            ->assertStatus(201)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'title' => $post->title,
                    'title_short' => Str::limit($post->title, 3),
                ]
            ]);

        $this->assertDatabaseHas('posts', $post->toArray());
    }

    /**
     * @test
     */
    public function itCantStoreAuthErrors()
    {
        $user = User::factory()->make();

        $this->postJson(route('users.store'), $user->toArray())
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * @test
     */
    public function itCantStoreFormErrors()
    {
        $auth = User::factory()->create();
        $user = User::factory()->make();

        $this->actingAs($auth)->postJson(route('users.store'), ['email' => $user->email])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The name field is required.',
                'errors' => [
                    'name' => ['The name field is required.']
                ]
            ]);
    }
}
