<?php
declare(strict_types=1);

namespace App\Domain\Specification;

use App\Domain\Client\Client;
use App\Domain\Client\ValueObject\Region;
use App\Domain\Credit\Credit;

final class OstravaRateAdjustmentSpecification implements SpecificationInterface
{
    private float $adjustPercent;

    public function __construct(float $adjustPercent = 5.0)
    {
        $this->adjustPercent = $adjustPercent;
    }

    public function isSatisfiedBy(Client $candidate): bool
    {
        return $candidate->region() === Region::OS;
    }

    public function adjust(Credit $credit): void
    {
        $credit->adjustRate($this->adjustPercent);
    }
}
