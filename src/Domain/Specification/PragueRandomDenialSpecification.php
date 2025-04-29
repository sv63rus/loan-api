<?php
declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\Region;

final class PragueRandomDenialSpecification implements SpecificationInterface
{
    public function isSatisfiedBy(Client $candidate): bool
    {
        if ($candidate->region() !== Region::PR) {
            return true;
        }

        return random_int(0, 1) === 0;
    }
}
