<?php

namespace App\Providers;


use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;
use Support\Testing\FakerFixturesFileProvider;

class TestingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerFixturesFileProvider($faker));
            return $faker;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
