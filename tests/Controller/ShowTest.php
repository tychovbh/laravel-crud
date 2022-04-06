<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanShow()
    {
        $user = User::factory()->create();

        $this->getJson(route('users.show', ['id' => $user->id]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $user->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCanShowWithParams()
    {
        $user = User::factory()->create();

        $this->getJson(route('users.show', ['id' => $user->id, 'verified' => 0]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $user->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCantShowWithParams()
    {
        $user = User::factory()->create([
            'verified' => 1
        ]);

        $this->getJson(route('users.show', ['id' => $user->id, 'verified' => 0]))
            ->assertStatus(404)
            ->assertJson([
                'message' => (new ModelNotFoundException())->setModel(User::class)->getMessage()
            ]);
    }

    /**
     * @test
     */
    public function itCanShowDetectResource()
    {
        $post = Post::factory()->create();

        $this->getJson(route('posts.show', ['id' => $post->id]))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'title' => $post->title,
                    'title_short' => Str::limit($post->title, 3),
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanShowResourceOff()
    {
        $post = Post::factory()->create();

        $this->getJson(route('posts.show', ['id' => $post->id, 'resource' => 'off']))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $post->toArray()
            ]);
    }
}
