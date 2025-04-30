<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\Region;

final class PragueRandomDenialSpecification implements SpecificationInterface
{
    public function isSatisfiedBy(Client $candidate): bool
    {
        if (Region::PR !== $candidate->region()) {
            return true;
        }

        return 0 === random_int(0, 1);
    }
}
