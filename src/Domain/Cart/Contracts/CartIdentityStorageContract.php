<?php

declare(strict_types=1);

namespace Domain\Cart\Contracts;

interface CartIdentityStorageContract
{
    public function get(): string;
}
