<?php

namespace Tests\Feature;

use App\Models\Name;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tychovbh\LaravelCrud\Tests\App\Models\Post;

class NameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itCanIndex()
    {
        $plural = Name::factory()->count(3)->create();

        $this->getJson(route('plural.index'))
            ->asserStatus(200)
            ->assertJsonCount($plural->count(), 'data')
            ->assertJson([
                'data' => $plural->map->only('id')->toArray()
            ]);
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $singular = Name::factory()->create();

        $this->getJson(route('plural.show', [
            'singular' => $singular->id,
        ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $singular->id,
                ],
            ]);
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $singular = Name::factory()->make();
        $this->postJson(route('plural.store'), $singular->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => 1
                ],
            ]);

        $this->assertDatabaseHas('plural', [
            'id' => 1
        ]);
    }

    /**
     * It can update
     *
     * @test
     */
    public function itCanUpdate()
    {
        $singular = Name::factory()->create();
        $update = Name::factory()->make();

        $this->putJson(route('plural.update', ['singular' => $singular->id]), $update->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $singular->id,
//                    'name' => $update->name TODO: Replace with exciting database field
                ],
            ]);

        $this->assertDatabaseHas('plural', [
            'id' => $singular->id,
//            'name' => $update->name  TODO: Replace with exciting database field
        ]);
    }

    /**
     * @test
     */
    public function itCanSoftDestroy()
    {
        $singular = Name::factory()->create();

        $this->deleteJson(route('plural.destroy', ['singular' => $singular->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseHas('plural', [
            'id' => $singular->id,
            'deleted_at' => now()
        ]);
    }

    /**
     * @test
     */
    public function itCanForceDestroy()
    {
        $singular = Name::factory()->create();

        $this->deleteJson(route('plural.forceDestroy', ['singular' => $singular->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('plural', ['id' => $singular->id]);
    }
}
