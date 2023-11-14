<?php

declare(strict_types=1);

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PriceFilter extends AbstractFilter
{

    public function title(): string
    {
        return 'Цена';
    }

    public function key(): string
    {
        return 'price';
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->when($this->requestValue(), function (Builder $builder) {
            $builder->whereBetween('price', [
                $this->requestValue('from', 0) * 100,
                $this->requestValue('to', 100000) * 100,
            ]);
        });
    }

    public function values(): array
    {
        return [
            'from' => 0,
            'to' => 100000,
        ];
    }

    public function view(): string
    {
        return 'catalog.filters.price';
    }
}
