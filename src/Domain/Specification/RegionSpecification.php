<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\Region;

final class RegionSpecification implements SpecificationInterface
{
    /**
     * @var Region[]
     */
    private array $allowed;

    /**
     * @param array<Region> $allowed
     */
    public function __construct(array $allowed = [Region::PR, Region::BR, Region::OS])
    {
        $this->allowed = $allowed;
    }

    public function isSatisfiedBy(Client $candidate): bool
    {
        return in_array($candidate->region()->value, $this->allowed, true);
    }
}
