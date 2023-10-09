<?php

namespace Tests\Feature;

use App\Models\Name;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
            ->assertStatus(200)
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
            '{model}' => $singular->id,
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

        $this->putJson(route('plural.update', ['{model}' => $singular->id]), $update->toArray())
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

        $this->deleteJson(route('plural.destroy', ['{model}' => $singular->id]))
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

        $this->deleteJson(route('plural.forceDestroy', ['{model}' => $singular->id]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);

        $this->assertDatabaseMissing('plural', ['id' => $singular->id]);
    }

    /**
     * @test
     */
    public function itCanRestoreDestroyed()
    {
        $singular = Name::factory()->create();
        $singular->delete();

        $this->putJson(route('plural.restore', ['{model}' => $singular->id]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $singular->id,
                ]
            ]);

        $this->assertDatabaseHas('plural', [
            'id' => $singular->id,
            'deleted_at' => null
        ]);
    }
}
