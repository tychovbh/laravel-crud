<?php

namespace Tychovbh\LaravelCrud\Tests\Commands;


use Tychovbh\LaravelCrud\Tests\TestCase;

class MakeParamsTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCreateParams()
    {
        $this->artisan('make:params User')
            ->assertSuccessful();
    }
}
