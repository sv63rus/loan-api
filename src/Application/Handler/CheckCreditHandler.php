<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CheckCreditCommand;
use App\Domain\Client\Client as DomainClient;
use App\Domain\Client\ValueObject\Age;
use App\Domain\Client\ValueObject\Income;
use App\Domain\Client\ValueObject\Pin;
use App\Domain\Client\ValueObject\Region;
use App\Domain\Client\ValueObject\Score;
use App\Domain\Service\CreditEligibilityService;
use App\Entity\LoanApplication;
use App\Repository\ClientRepository;
use App\Repository\LoanApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CheckCreditHandler
{
    public function __construct(
        private LoanApplicationRepository $loanApplicationRepository,
        private ClientRepository          $clients,
        private CreditEligibilityService  $eligibility,
        private EntityManagerInterface    $em
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function __invoke(CheckCreditCommand $command): array
    {
        $entity = $this->clients->find($command->clientId);
        if (! $entity) {
            throw new \DomainException('Client not found');
        }

        $domainClient = new DomainClient(
            (string) $entity->getId(),
            $entity->getName(),
            new Age($entity->getAge()),
            Region::fromString($entity->getRegion()),
            new Income($entity->getIncome()),
            new Score($entity->getScore()),
            new Pin($entity->getPin()),
            $entity->getEmail(),
            $entity->getPhone()
        );

        $eligible = $this->eligibility->isEligible($domainClient);
        $reason = $eligible ? null : 'Eligibility rules failed';

        $app = $this->loanApplicationRepository
            ->findOneBy([
                'client' => $entity,
            ]);

        if ($app) {
            return [
                'applicationId' => $app->getId(),
                'eligible' => $app->isEligible(),
                'reason' => $app->getRejectionReason(),
            ];
        }

        $app = new LoanApplication(
            $entity,
            0,
            0.0,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            $eligible,
            $reason
        );

        $this->em->persist($app);
        $this->em->flush();

        return [
            'clientId' => $entity->getId(),
            'eligible' => $eligible,
            'reason'   => $reason,
        ];
    }
}
