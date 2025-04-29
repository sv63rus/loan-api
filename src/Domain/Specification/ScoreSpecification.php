<?php
declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;

final class ScoreSpecification implements SpecificationInterface
{
    private int $threshold;

    public function __construct(int $threshold = 500)
    {
        $this->threshold = $threshold;
    }

    public function isSatisfiedBy(Client $candidate): bool
    {
        return $candidate->score()->isAbove($this->threshold);
    }
}
