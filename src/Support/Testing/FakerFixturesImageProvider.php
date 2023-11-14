<?php

declare(strict_types=1);

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerFixturesImageProvider extends Base
{
    public function fixturesImage(
        string $sourceDirectory = '',
        string $targetDirectory = null,
        string $urlPath = null
    ): string
    {
        if(is_null($targetDirectory)) {
            $targetDirectory = $sourceDirectory;
        }

        if (is_null($urlPath)) {
            $urlPath = str_replace(
                DIRECTORY_SEPARATOR,
                '/',
                "/storage/images/$targetDirectory/"
            );
        }

        $storage = Storage::disk('images');

        if(!$storage->exists($targetDirectory)) {
            $storage->makeDirectory($targetDirectory);
        }

        $filename = $this->generator->file(
            base_path("tests/Fixtures/images/$sourceDirectory"),
            $storage->path($targetDirectory),
            false
        );

        return $urlPath . $filename;
    }
}
