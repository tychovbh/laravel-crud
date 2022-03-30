<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
