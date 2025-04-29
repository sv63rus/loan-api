<?php
declare(strict_types=1);

namespace App\Domain\Credit;

final class CreditDecision
{
    private bool $approved;
    /** @var string[] */
    private array $reasons;

    private function __construct(bool $approved, array $reasons = [])
    {
        $this->approved = $approved;
        $this->reasons  = $reasons;
    }

    public static function approved(): self
    {
        return new self(true);
    }

    public static function rejected(string ...$reasons): self
    {
        return new self(false, $reasons);
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    /** @return string[] */
    public function reasons(): array
    {
        return $this->reasons;
    }
}
