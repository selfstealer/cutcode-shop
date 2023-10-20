<?php

declare(strict_types=1);

namespace App\Faker;

use Faker\Provider\Base;
use Faker\Provider\File as BaseFile;
use Illuminate\Support\Facades\File;

/**
 * @method string \Faker\Generator::file($sourceDirectory = '/tmp', $targetDirectory = '/tmp', $urlPath = null)
 */

class FakerImageFileProvider extends Base
{
    public function imageFile(
        string $sourceDirectory = '/tmp',
        string $targetDirectory = '/tmp',
        string $urlPath = null
    ): string
    {
        if (!File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, recursive: true);
        }

        if (is_null($urlPath)) {
            $urlPath = '/storage/';

            $storage_public_path = storage_path('app/public');
            if (str_starts_with($targetDirectory, $storage_public_path)) {
                $urlPath .= str_replace(
                    DIRECTORY_SEPARATOR,
                    '/',
                    ltrim(str_replace($storage_public_path, '', $targetDirectory), '/')
                );
                $urlPath .= '/';
            }
        }

        $filename = BaseFile::file($sourceDirectory, $targetDirectory, false);

        return $urlPath . $filename;
    }
}
