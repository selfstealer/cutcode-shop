<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\BrandFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\OptionFactory;
use Database\Factories\OptionValueFactory;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        BrandFactory::new()->count(20)->create();

        $properties = PropertyFactory::new()->count(10)
            ->create();

        OptionFactory::new()->count(2)->create();

        $optionValues = OptionValueFactory::new()->count(10)->create();

        // Ну такой себе рандом число выбирается один раз
        CategoryFactory::new()->count(15)
            ->has(ProductFactory::new()
                ->count(10)
                ->hasAttached($optionValues)
                ->hasAttached($properties, static function () {
                    return ['value' => ucfirst(fake()->word())];
                })
            )
            ->create();


    }
}
