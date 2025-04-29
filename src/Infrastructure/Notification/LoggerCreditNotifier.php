<?php
declare(strict_types=1);

namespace App\Infrastructure\Notification;

use Psr\Log\LoggerInterface;

final readonly class LoggerCreditNotifier
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function notifyApproved(string $clientName, string $loanName, float $amount): void
    {
        $message = sprintf(
            '[%s] Уведомление клиенту %s: Кредит "%s" на сумму %.2f одобрен.',
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $clientName,
            $loanName,
            $amount
        );

        $this->logger->info($message);
    }

    public function notifyRejected(string $clientName, string $reason): void
    {
        $message = sprintf(
            '[%s] Уведомление клиенту %s: Кредит отклонен (%s).',
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $clientName,
            $reason
        );

        $this->logger->warning($message);
    }
}
