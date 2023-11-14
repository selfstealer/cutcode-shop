<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pipeline\Pipeline;
use Laravel\Scout\Attributes\SearchUsingFullText;
//use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
//    use Searchable;

    protected $fillable = [
        'slug',
        'title',
        'brand_id',
        'price',
        'thumbnail',
        'on_home_page',
        'sorting',
        'text',
    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];

    protected function thumbnailDir(): string
    {
        return 'products';
    }

    #[SearchUsingFullText(['title', 'text'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
        ];
    }

    public function scopeFiltered(Builder $builder): Builder
    {
//        foreach (filters() as $filter) {
//            $builder = $filter->apply($builder);
//        }
//        return $builder;
        return app(Pipeline::class)
            ->send($builder)
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

    public function scopeSorted(Builder $builder): Builder
    {
        return $builder->when(request('sort'), function (Builder $builder) {
            $column = request()->str('sort');
            if($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $builder->orderBy((string)$column->remove('-'), $direction);
            }
        });
    }

    public function scopeHomePage(Builder $builder): Builder
    {
        return $builder->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
