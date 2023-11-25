<?php

declare(strict_types=1);

namespace Domain\Cart\StorageIdentities;

use Domain\Cart\Contracts\CartIdentityStorageContract;

class FakeSessionIdentityStorage implements CartIdentityStorageContract
{
    public function get(): string
    {
        return 'tests';
    }
}
