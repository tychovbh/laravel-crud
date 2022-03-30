<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->putJson(route('users.update', ['id' => $user->id]), $update->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => $expected
            ]);

        $this->assertDatabaseHas('users', $expected);
    }

    /**
     * @test
     */
    public function itCantUpdate()
    {
        $user = User::factory()->create();

        $this->putJson(route('users.update', ['id' => $user->id]), ['email' => 'test'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The email must be a valid email address.',
                'errors' => [
                    'email' => ['The email must be a valid email address.']
                ]
            ]);
    }
}
