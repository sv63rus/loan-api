<?php
declare(strict_types=1);

namespace App\Application\Command;

final readonly class IssueCreditCommand
{
    public function __construct(
        public int                $clientId,
        public string             $loanName,
        public int                $amount,
        public float              $rate,
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate
    ) {
    }
}
