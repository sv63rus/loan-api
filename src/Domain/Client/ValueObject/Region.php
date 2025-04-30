<?php

declare(strict_types=1);

namespace App\Domain\Client\ValueObject;

enum Region: string
{
    case PR = 'PR';
    case BR = 'BR';
    case OS = 'OS';

    public static function fromString(string $region): self
    {
        return match (strtoupper($region)) {
            'PR' => self::PR,
            'BR' => self::BR,
            'OS' => self::OS,
            default => throw new \InvalidArgumentException("Unknown region $region"),
        };
    }
}
