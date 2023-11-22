<?php

declare(strict_types=1);

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

class OptionValueCollection extends Collection
{
    public function keyValues(): mixed
    {
        return $this->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });
    }
}
