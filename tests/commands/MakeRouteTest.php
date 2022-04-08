<?php

namespace Tychovbh\LaravelCrud\Tests\Commands;


use Tychovbh\LaravelCrud\Tests\TestCase;

class MakeRouteTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateRoute()
    {
        $this->artisan('make:route UserRoute')
        ->assertSuccessful();
    }

    /**
     * @test
     */
    public function itCanCreateRouteWithBindings()
    {
        $this->artisan('make:route UserRoute --bindings')
            ->assertSuccessful();
    }
}
