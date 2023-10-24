<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        // Тут бы добавить slugable интерфейс, что бы вызывать не по Model
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? $model->generateUniqueSlug(self::slugColumn(), self::slugFrom());
        });
    }

    protected static function slugColumn(): string
    {
        return 'slug';
    }

    protected static function slugFrom(): string
    {
        return 'title';
    }

    protected function generateUniqueSlug(string $slugColumn, string $slugFrom): Stringable
    {
        $slugNumber = 0;
        do {
            $slug = str($this->{$slugFrom} . ($slugNumber === 0 ? '' : ' ' . $slugNumber))->slug();
            $slugNumber++;
        } while ($this->newModelQuery()->where($slugColumn, '=', $slug)->withoutGlobalScopes()->exists());

        return $slug;
    }
}
