<?php

declare(strict_types=1);

namespace Domain\Product\QueryBuilders;

use Domain\Catalog\Facades\Sorter;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class ProductQueryBuilder extends Builder
{
    public function homePage(): ProductQueryBuilder
    {
        return $this->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function filtered(): ProductQueryBuilder|Builder
    {
//        foreach (filters() as $filter) {
//            $builder = $filter->apply($builder);
//        }
//        return $builder;
        return app(Pipeline::class)
            ->send($this)
            ->through(filters())
            ->thenReturn();
//        return $builder->when(request('filters.brands'), function (Builder $builder) {
//            $builder->whereIn('brand_id', request('filters.brands'));
//        })->when(request('filters.price'), function (Builder $builder) {
//            $builder->whereBetween('price', [
//                request('filters.price.from', 0) * 100,
//                request('filters.price.to', 100000) * 100,
//            ]);
//        });
    }

    public function sorted(): ProductQueryBuilder|Builder
    {
        return Sorter::run($this);
//        return $builder->when(request('sort'), function (Builder $builder) {
//            $column = request()->str('sort');
//            if($column->contains(['price', 'title'])) {
//                $direction = $column->contains('-') ? 'DESC' : 'ASC';
//                $builder->orderBy((string)$column->remove('-'), $direction);
//            }
//        });
    }

    public function withCategory(Category $category): ProductQueryBuilder|Builder
    {
        // TODO нарущаем, протекла категория сюда
        return $this->when($category->exists, function (Builder $builder) use ($category) {
            $builder->whereRelation('categories', 'categories.id', '=', $category->id);
        });
    }

    public function search(): ProductQueryBuilder|Builder
    {
        return $this->when(request('search'), function (Builder $builder) {
            $builder->whereFullText(['title', 'text'], request('search'));
        });
    }
}
