<?php
declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

final class Pin
{
    private string $value;

    public function __construct(string $value)
    {
        if (!preg_match('/^\d{3}-\d{2}-\d{4}$/', $value)) {
            throw new \InvalidArgumentException(sprintf('Invalid PIN format: %s', $value));
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
