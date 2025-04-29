<?php
declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

final class Score
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException(sprintf('Score must be non-negative, %d given', $value));
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isAbove(int $threshold): bool
    {
        return $this->value > $threshold;
    }
}
