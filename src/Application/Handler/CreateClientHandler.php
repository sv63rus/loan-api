<?php
declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateClientCommand;
use App\Entity\Client as ClientEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateClientHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(CreateClientCommand $command): int
    {
        $client = new ClientEntity(
            $command->name,
            $command->age,
            $command->region,
            $command->pin,
            $command->score,
            $command->income,
            $command->email,
            $command->phone
        );

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $client->getId();
    }
}
