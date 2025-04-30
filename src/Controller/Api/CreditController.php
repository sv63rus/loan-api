<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\Command\CheckCreditCommand;
use App\Application\Command\IssueCreditCommand;
use App\Application\Handler\CheckCreditHandler;
use App\Application\Handler\IssueCreditHandler;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/credits')]
#[OA\Tag(name: 'Credits')]
final class CreditController extends AbstractController
{
    public function __construct(
        private readonly CheckCreditHandler $checkHandler,
        private readonly IssueCreditHandler $issueHandler,
    ) {
    }

    #[Route('/check', name: 'api_credit_check', methods: ['POST'])]
    #[OA\Post(
        summary: 'Проверить возможность выдачи кредита',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['clientId'],
                properties: [
                    new OA\Property(property: 'clientId', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Результат проверки',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'eligible', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Клиент не найден'),
        ]
    )]
    public function check(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->json([
                'error' => 'Invalid JSON',
            ], 400);
        }
        if (! is_array($data) || ! isset($data['clientId'])) {
            return $this->json([
                'error' => 'clientId is required',
            ], 400);
        }

        try {
            $eligible = ($this->checkHandler)(new CheckCreditCommand((int) $data['clientId']));
        } catch (\DomainException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 404);
        }

        return $this->json([
            'eligible' => $eligible,
        ], 200);
    }

    #[Route('/issue', name: 'api_credit_issue', methods: ['POST'])]
    #[OA\Post(
        summary: 'Выдать кредит клиенту',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['clientId', 'loanName', 'amount', 'rate', 'startDate', 'endDate'],
                properties: [
                    new OA\Property(property: 'clientId', type: 'integer', example: 1),
                    new OA\Property(property: 'loanName', type: 'string', example: 'Personal Loan'),
                    new OA\Property(property: 'amount', type: 'integer', example: 1000),
                    new OA\Property(property: 'rate', type: 'number', format: 'float', example: 10.0),
                    new OA\Property(property: 'startDate', type: 'string', format: 'date', example: '2024-01-01'),
                    new OA\Property(property: 'endDate', type: 'string', format: 'date', example: '2024-12-31'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Кредит выдан',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'loanId', type: 'integer', example: 42),
                        new OA\Property(property: 'message', type: 'string', example: 'Credit issued'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Неправильные данные'),
            new OA\Response(response: 404, description: 'Клиент не найден'),
            new OA\Response(response: 422, description: 'Клиент не прошёл проверку'),
        ]
    )]
    public function issue(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $required = ['clientId', 'loanName', 'amount', 'rate', 'startDate', 'endDate'];
            if (! is_array($data) || count(array_diff($required, array_keys($data))) > 0) {
                return $this->json([
                    'error' => 'Missing fields',
                ], 400);
            }

            $command = new IssueCreditCommand(
                (int) $data['clientId'],
                $data['loanName'],
                (int) $data['amount'],
                (float) $data['rate'],
                new \DateTimeImmutable($data['startDate']),
                new \DateTimeImmutable($data['endDate'])
            );
            $loanId = ($this->issueHandler)($command);
        } catch (\DomainException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 'Client not found' === $e->getMessage() ? 404 : 422);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Invalid data',
            ], 400);
        }

        return $this->json([
            'loanId' => $loanId,
            'message' => 'Credit issued',
        ], 201);
    }
}
