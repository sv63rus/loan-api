<?php
declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;

final class AgeSpecification implements SpecificationInterface
{
    private int $min;
    private int $max;

    public function __construct(int $min = 18, int $max = 60)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function isSatisfiedBy(Client $candidate): bool
    {
        $age = $candidate->age()->value();
        return $age >= $this->min && $age <= $this->max;
    }
}
