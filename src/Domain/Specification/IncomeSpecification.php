<?php

declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;

final class IncomeSpecification implements SpecificationInterface
{
    private int $minimum;

    public function __construct(int $minimum = 1000)
    {
        $this->minimum = $minimum;
    }

    public function isSatisfiedBy(Client $candidate): bool
    {
        return $candidate->income()->value() >= $this->minimum;
    }
}
