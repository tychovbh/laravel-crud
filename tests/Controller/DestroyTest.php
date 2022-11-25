<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

        $this->deleteJson(route('users.destroy', ['user' => $user->id]))
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

        $this->deleteJson(route('posts.destroy', ['post' => $post->id]))
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

        $this->deleteJson(route('posts.forceDestroy', ['post' => $post->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /**
     * @test
     */
    public function itCanForceDestroySoftDeleted()
    {
        $post = Post::factory()->create();
        $post->delete();

        $this->deleteJson(route('posts.forceDestroy', ['post' => $post->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /**
     * @test
     */
    public function itCanRestoreDestroyed()
    {
        $post = Post::factory()->create();
        $post->delete();

        $this->putJson(route('posts.restore', ['post' => $post->id, 'resource' => 'off']))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $post->id,
                    'deleted_at' => null
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'deleted_at' => null
        ]);
    }

    /**
     * @test
     */
    public function itCanBulkSoftDestroy()
    {
        $post = Post::factory(3)->create();

        $this->deleteJson(route('posts.bulkDestroy', ['id' => [$post[0]->id, $post[2]->id]]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post[0]->id,
            'deleted_at' => now()
        ]);
    }

    /**
     * @test
     */
    public function itCanBulkRestore()
    {
        $post = Post::factory(3)->create();

        Post::bulkDestroy([1,3]);

        $this->postJson(route('posts.bulkRestore'), ['id' => [$post[0]->id, $post[2]->id]])
            ->assertStatus(200);

        $this->assertDatabaseHas('posts', [
            'id' => $post[0]->id,
            'deleted_at' => null
        ]);
    }

    /**
     * @test
     */
    public function itCanBulkForceDestroy()
    {
        $post = Post::factory(3)->create();

        $this->deleteJson(route('posts.bulkForceDestroy', ['id' => [$post[0]->id, $post[2]->id]]))

            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseCount('posts', 1);
    }
}
