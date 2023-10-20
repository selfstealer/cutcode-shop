<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;

trait HasSlug
{
    protected int $slugNumber = 0;

    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug ?? $model->generateUniqueSlug(self::slugFrom());
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }

    protected function generateUniqueSlug(string $slugFrom): Stringable
    {
        do {
            $slug = str($this->{$slugFrom} . ($this->slugNumber === 0 ? '' : ' ' . $this->slugNumber))->slug();
            $this->slugNumber++;
        } while ($this->newModelQuery()->where('slug', '=', $slug)->exists());

        return $slug;
    }
}
