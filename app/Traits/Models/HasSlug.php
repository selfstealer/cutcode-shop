<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->slug = $model->slug
                ?? str($model->{self::slugFrom()})
                    ->append(microtime(true)) // time() всё равно генерит ошибки TODO дз генаратор уникального имени
                    ->slug();
        });
    }

    public static function slugFrom(): string
    {
        return 'title';
    }
}
