<?php
declare(strict_types=1);

namespace App\Domain\Credit;

use App\Domain\Client\Client;
use DateTimeImmutable;

final class Credit
{
    private string $id;
    private Client $client;
    private string $name;
    private int $amount;
    private float $rate;
    private DateTimeImmutable $startDate;
    private DateTimeImmutable $endDate;
    private bool $approved = false;

    public function __construct(
        string $id,
        Client $client,
        string $name,
        int $amount,
        float $rate,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }

        $this->id         = $id;
        $this->client     = $client;
        $this->name       = $name;
        $this->amount     = $amount;
        $this->rate       = $rate;
        $this->startDate  = $startDate;
        $this->endDate    = $endDate;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function approve(): void
    {
        $this->approved = true;
    }

    public function adjustRate(float $percentage): void
    {
        $this->rate += $this->rate * ($percentage / 100);
    }
}
