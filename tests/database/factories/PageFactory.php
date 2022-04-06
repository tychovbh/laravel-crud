<?php

namespace Tychovbh\LaravelCrud\Tests\Database\Factories;


use Tychovbh\LaravelCrud\Tests\App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'url' => $this->faker->url,
        ];
    }
}
