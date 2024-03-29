<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $user = User::factory()->create();
        $update = User::factory()->make();
        $expected = array_merge(['id' => $user->id], $update->toArray());

        $this->putJson(route('users.update', ['user' => $user->id]), $update->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => $expected
            ]);

        $this->assertDatabaseHas('users', $expected);
    }

    /**
     * @test
     */
    public function itCanUpdateWithCache()
    {
        $this->markTestSkipped('works with breakpoints but not directly');
        $user = User::factory()->create();

        // Cache record
        $this->actingAs($user)->getJson(route('users.show', ['user' => $user->id]))
            ->assertStatus(200);


        $update = User::factory()->make();
        $expected = array_merge(['id' => $user->id], $update->toArray());

        // Update record
        $this->putJson(route('users.update', ['user' => $user->id]), $update->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => $expected
            ]);

        $this->assertDatabaseHas('users', $expected);

        // Assert that cache is cleared via update
        $response = Cache::tags([
            'users.show',
            'users.show.' . $user->id,
        ])->get('/users/' . $user->id);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function itCanStoreDetectResource()
    {
        $post = Post::factory()->create();
        $update = Post::factory()->make();
        $expected = array_merge(['id' => $post->id], $update->toArray());

        $this->putJson(route('posts.update', ['post' => $post->id]), $update->toArray())
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => 1,
                    'title' => $update->title,
                    'title_short' => Str::limit($update->title, 3),
                ]
            ]);

        $this->assertDatabaseHas('posts', $expected);
    }

    /**
     * @test
     */
    public function itCantUpdate()
    {
        $user = User::factory()->create();

        $this->putJson(route('users.update', ['user' => $user->id]), ['email' => 'test'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The email must be a valid email address.',
                'errors' => [
                    'email' => ['The email must be a valid email address.']
                ]
            ]);
    }
}
