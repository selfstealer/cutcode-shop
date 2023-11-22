<?php

namespace App\View\ViewModels;

use Domain\Catalog\Models\Category;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Spatie\ViewModels\ViewModel;
use Support\Traits\Makeable;

class CatalogViewModel extends ViewModel
{
    use Makeable;

    public function __construct(
        public Category $category
    )
    {
    }

    public function categories(): Collection|array
    {
        return Category::query()
            ->select('id', 'title', 'slug')
            ->has('products')
            ->get();
    }

    public function products(): LengthAwarePaginator
    {
//        return Product::search('Alice')
//            ->query(function (Builder $builder) use ($category) {
//                $builder->select('id', 'title', 'slug', 'price', 'thumbnail')
//                    ->when($category->exists, function (Builder $builder) use ($category) {
//                        $builder->whereRelation('categories', 'categories.id', '=', $category->id);
//                    })
//                    ->filtered()
//                    ->sorted();
//            })
//            ->paginate(6);

        return Product::query()
            ->select('id', 'title', 'slug', 'price', 'thumbnail', 'json_properties')
            ->search()
            ->withCategory($this->category)
            ->filtered()
            ->sorted()
            ->paginate(6);
    }
}
