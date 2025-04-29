<?php
declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\LoanApplicationRepository::class)]
#[ORM\Table(name: 'loan_applications')]
class LoanApplication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\Column(type: 'integer')]
    private int $amount;

    #[ORM\Column(type: 'float')]
    private float $rate;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $startDate;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeImmutable $endDate;

    #[ORM\Column(type: 'boolean')]
    private bool $eligible;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $rejectionReason = null;

    #[ORM\Column(type: 'boolean')]
    private bool $issued = false;

    public function __construct(
        Client $client,
        int $amount,
        float $rate,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        bool $eligible,
        ?string $rejectionReason = null
    ) {
        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }

        $this->client          = $client;
        $this->amount          = $amount;
        $this->rate            = $rate;
        $this->startDate       = $startDate;
        $this->endDate         = $endDate;
        $this->eligible        = $eligible;
        $this->rejectionReason = $rejectionReason;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function isEligible(): bool
    {
        return $this->eligible;
    }

    public function getRejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function isIssued(): bool
    {
        return $this->issued;
    }

    public function markIssued(): void
    {
        $this->issued = true;
    }

    public function setEligible(bool $eligible, ?string $reason): void
    {
        $this->eligible = $eligible;
        $this->rejectionReason = $reason;
    }
}
