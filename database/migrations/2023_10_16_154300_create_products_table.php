<?php

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')
                ->unique();

            $table->foreignIdFor(Brand::class)
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->unsignedInteger('price')
                ->default(0);

            $table->string('thumbnail')
                ->nullable();

            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(app()->isLocal()) {
            Schema::dropIfExists('category_product');
            Schema::dropIfExists('products');
        }
    }
};
