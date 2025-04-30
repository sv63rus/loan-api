<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;

interface SpecificationInterface
{
    public function isSatisfiedBy(Client $candidate): bool;
}
