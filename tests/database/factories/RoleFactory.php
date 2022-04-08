<?php

namespace Tychovbh\LaravelCrud\Tests\Database\Factories;


use Illuminate\Support\Str;
use Tychovbh\LaravelCrud\Tests\App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $label = $this->faker->name;
        $name = Str::slug($label);
        return [
            'name' => $name,
            'label' => $label,
        ];
    }
}
