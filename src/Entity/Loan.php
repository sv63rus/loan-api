<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LoanRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['loan:read']],
    denormalizationContext: ['groups' => ['loan:write']]
)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['loan:read','loan:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['loan:read','loan:write'])]
    private ?int $amount = null;

    #[ORM\Column]
    #[Groups(['loan:read','loan:write'])]
    private ?float $rate = null;

    #[ORM\Column]
    #[Groups(['loan:read','loan:write'])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    #[Groups(['loan:read','loan:write'])]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column]
    #[Groups(['loan:read','loan:write'])]
    private ?bool $approved = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function __construct(
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

        $this->client    = $client;
        $this->name      = $name;
        $this->amount    = $amount;
        $this->rate      = $rate;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function approve(): void
    {
        $this->approved = true;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
