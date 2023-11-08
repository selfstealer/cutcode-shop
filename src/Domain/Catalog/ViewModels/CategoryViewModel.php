<?php

declare(strict_types=1);

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

class CategoryViewModel
{
    use Makeable;

    public function homePage(): Collection|array
    {
//        Cache::forget('category_home_page');
        /** tags(['category']) - можно только для memcached и redis */
//        return Cache::rememberForever('category_home_page', function () {
            return Category::query()
                ->homePage()
                ->get();
//        });
    }
}
