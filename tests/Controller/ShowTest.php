<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
