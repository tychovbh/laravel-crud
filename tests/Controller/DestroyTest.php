<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanDestroy()
    {
        $user = User::factory()->create();

        $this->deleteJson(route('users.destroy', ['id' => $user->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * @test
     */
    public function itCanSoftDestroy()
    {
        $post = Post::factory()->create();

        $this->deleteJson(route('posts.destroy', ['id' => $post->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => now()
        ]);
    }



    /**
     * @test
     */
    public function itCanForceDestroy()
    {
        $post = Post::factory()->create();
        $post->delete();

        $this->deleteJson(route('posts.forceDestroy', ['id' => $post->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('posts', ['model' => $post->id]);
    }
}
