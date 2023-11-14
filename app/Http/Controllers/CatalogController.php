<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use function Clue\StreamFilter\fun;

class CatalogController extends Controller
{
    public function __invoke(?Category $category) : mixed
    {
        $categories = Category::query()
            ->select('id', 'title', 'slug')
            ->has('products')
            ->get();

//        $products = Product::search('Alice')
//            ->query(function (Builder $builder) use ($category) {
//                $builder->select('id', 'title', 'slug', 'price', 'thumbnail')
//                    ->when($category->exists, function (Builder $builder) use ($category) {
//                        $builder->whereRelation('categories', 'categories.id', '=', $category->id);
//                    })
//                    ->filtered()
//                    ->sorted();
//            })
//            ->paginate(6);
        $products = Product::query()
            ->select('id', 'title', 'slug', 'price', 'thumbnail')
            ->when(request('search'), function (Builder $builder) {
                $builder->whereFullText(['title', 'text'], request('search'));
            })
            ->when($category->exists, function (Builder $builder) use ($category) {
                $builder->whereRelation('categories', 'categories.id', '=', $category->id);
            })
            ->filtered()
            ->sorted()
            ->paginate(6);

        // $products->setRelation(...)

        return view('catalog.index', compact(
            'products',
            'categories',
            'category',
        ));
    }
}
