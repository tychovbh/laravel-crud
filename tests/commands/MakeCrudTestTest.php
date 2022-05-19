<?php

namespace Tychovbh\LaravelCrud\Tests\Commands;


use Tychovbh\LaravelCrud\Tests\TestCase;

class MakeCrudTestTest extends TestCase
{
    /**
     * @test
     */
    public function itCanMakeTest()
    {
        $this->artisan('make:crud-test User')
            ->expectsConfirmation('Support soft deletes?', 'no')
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function itCanMakeTestSoftDeletes()
    {
        $this->artisan('make:crud-test User')
            ->expectsConfirmation('Support soft deletes?', 'yes')
            ->assertSuccessful();
    }
}
