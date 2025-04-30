<?php

declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

final class Age
{
    private int $value;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException(sprintf('Age must be non-negative, %d given', $value));
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
