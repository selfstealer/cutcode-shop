<?php

declare(strict_types=1);

namespace Support\Testing;

use Faker\Provider\Base;
use Faker\Provider\File as BaseFile;
use Illuminate\Support\Facades\File;

class FakerFixturesFileProvider extends Base
{
    public function fixturesFile(
        string $sourceDirectory = '',
        string $targetDirectory = '',
        string $urlPath = null
    ): string
    {
        $sourceDir = base_path("tests/Fixtures/$sourceDirectory");
        $targetDir = storage_path("app/public/$targetDirectory");

        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, recursive: true);
        }

        if (is_null($urlPath)) {
            $urlPath = '/storage/';

            $storage_public_path = storage_path('app/public');
            if (str_starts_with($targetDir, $storage_public_path)) {
                $urlPath .= str_replace(
                    DIRECTORY_SEPARATOR,
                    '/',
                    ltrim(str_replace($storage_public_path, '', $targetDir), '/')
                );
                $urlPath .= '/';
            }
        }

        $filename = BaseFile::file($sourceDir, $targetDir, false);

        return $urlPath . $filename;
    }
}
