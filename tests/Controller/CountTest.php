<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class CountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanCount()
    {
        $users = User::factory()->count(3)->create();

        $this->getJson(route('users.count'))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'count' => $users->count()
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanCountWithGetParams()
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $this->getJson(route('users.count', ['email' => $user->email]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'count' => 1
                ]
            ]);
    }
}
