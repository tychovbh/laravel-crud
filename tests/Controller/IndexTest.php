<?php

namespace Tychovbh\LaravelCrud\Tests\Controller;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tychovbh\LaravelCrud\Tests\App\Models\User;
use Tychovbh\LaravelCrud\Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanIndex()
    {
        $users = User::factory()->count(4)->create();

        $this->getJson(route('users.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' => $users->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithPagination()
    {
        $page1 = User::factory()->count(2)->create();
        $page2 = User::factory()->count(2)->create();

        $this->getJson(route('users.index', ['paginate' => 2]))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $page1->toArray(),
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'last_page' => 2,
                    'per_page' => 2,
                    'to' => 2,
                    'total' => 4
                ]
            ]);

        $this->getJson(route('users.index', ['paginate' => 2, 'page' => 2]))
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => $page2->toArray(),
                'meta' => [
                    'current_page' => 2,
                    'from' => 3,
                    'last_page' => 2,
                    'per_page' => 2,
                    'to' => 4,
                    'total' => 4
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithParams()
    {
        $user = User::factory()->create();
        User::factory()->count(4)->create();

        $this->getJson(route('users.index', ['email' => $user->email]))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$user->toArray()]
            ]);
    }

    /**
     * @test
     */
    public function itCanIndexWithCustomParams()
    {
        $user = User::factory()->create([
            'name' => 'tycho'
        ]);

        User::factory()->count(4)->create();

        $this->getJson(route('users.index', ['search' => 'ych']))
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [$user->toArray()]
            ]);
    }
}
