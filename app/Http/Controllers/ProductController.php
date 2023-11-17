<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function __invoke(Product $product) : mixed
    {
        $product->load(['optionValues.option']);

        $also = Product::query()
            ->where(static function(Builder $builder) use ($product) {
                $builder->whereIn('id', session('also', []))
                    ->where('id', '!=', $product->id);
            })
            ->get();

        $options = $product->optionValues->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });

        session()->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'also' => $also
        ]);
    }
}
