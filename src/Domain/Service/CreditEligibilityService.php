<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Client\Client;
use App\Domain\Specification\SpecificationInterface;

final class CreditEligibilityService
{
    /** @var iterable<SpecificationInterface> */
    private iterable $rules;

    public function __construct(iterable $rules)
    {
        $this->rules = $rules;
    }

    public function isEligible(Client $client): bool
    {
        foreach ($this->rules as $rule) {
            if (! $rule->isSatisfiedBy($client)) {
                return false;
            }
        }

        return true;
    }
}
