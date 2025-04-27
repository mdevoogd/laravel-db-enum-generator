<?php

namespace mdevoogd\LaravelDatabaseEnum;

use mdevoogd\LaravelDatabaseEnum\Providers\LaravelDatabaseEnumServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * The laravel enum service provider test.
 *
 */
class LaravelEnumServiceProviderTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelDatabaseEnumServiceProvider::class,
        ];
    }

    /**
     * @test
     */
    public function artisanCommandIsLoaded()
    {
        $this->artisan('make:enum --help')->assertExitCode(0);
    }
}
