<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CheckCreditCommand;
use App\Application\Command\IssueCreditCommand;
use App\Entity\Loan;
use App\Infrastructure\Notification\LoggerCreditNotifier;
use App\Repository\LoanApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class IssueCreditHandler
{
    public function __construct(
        private CheckCreditHandler                 $checkHandler,
        private LoanApplicationRepository $apps,
        private EntityManagerInterface    $em,
        private LoggerCreditNotifier $notifier

    ) {
    }

    public function __invoke(IssueCreditCommand $command): int
    {
        $result = ($this->checkHandler)(new CheckCreditCommand($command->clientId));
        $app = $this->apps->find($result['applicationId']);

        if (! $app) {
            throw new \DomainException('Application not found');
        }

        if (! $app->isEligible()) {
            $this->notifier->notifyRejected(
                $app->getClient()->getName(),
                $app->getRejectionReason() ?? 'No reason provided'
            );
            throw new \DomainException('Client is not eligible for credit');
        }

        $loan = new Loan(
            $app->getClient(),
            $command->loanName,
            $command->amount,
            $command->rate,
            $command->startDate,
            $command->endDate
        );
        $loan->approve();

        $app->markIssued();

        $this->em->persist($loan);
        $this->em->persist($app);
        $this->em->flush();

        $this->notifier->notifyApproved(
            $app->getClient()->getName(),
            $command->loanName,
            $command->amount
        );

        return (int) $loan->getId();
    }
}
