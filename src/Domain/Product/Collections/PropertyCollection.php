<?php

declare(strict_types=1);

namespace Domain\Product\Collections;

use Illuminate\Database\Eloquent\Collection;

class PropertyCollection extends Collection
{
    public function keyValues(): mixed
    {
        return $this->mapWithKeys(fn($property) => [
            $property->title => $property->pivot->value
        ]);
    }
}
