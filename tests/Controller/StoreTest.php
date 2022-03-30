<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
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
        $auth = User::factory()->create();
        $user = User::factory()->make();

        $this->actingAs($auth)->postJson(route('users.store'), $user->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => $user->toArray()
            ]);

        $this->assertDatabaseHas('users', $user->toArray());
    }

    /**
     * @test
     */
    public function itCantStoreAuthErrors()
    {
        $user = User::factory()->make();

        $this->postJson(route('users.store'), $user->toArray())
            ->assertStatus(403)
            ->assertJson([
                'message' => 'This action is unauthorized.',
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
