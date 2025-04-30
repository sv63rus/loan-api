<?php

declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

final class Income
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException(sprintf('Income must be non-negative, %d given', $value));
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}
