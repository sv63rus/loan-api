<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\Command\CreateClientCommand;
use App\Application\Handler\CreateClientHandler;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/clients')]
#[OA\Tag(name: 'Clients')]
final class ClientController extends AbstractController
{
    public function __construct(
        private readonly CreateClientHandler $handler,
    ) {
    }

    #[Route('', name: 'api_create_client', methods: ['POST'])]
    #[OA\Post(
        path: '/api/clients',
        summary: 'Создать нового клиента',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'age', 'region', 'income', 'score', 'pin', 'email', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Petr Pavel'),
                    new OA\Property(property: 'age', type: 'integer', example: 35),
                    new OA\Property(property: 'region', type: 'string', example: 'PR'),
                    new OA\Property(property: 'income', type: 'integer', example: 1500),
                    new OA\Property(property: 'score', type: 'integer', example: 600),
                    new OA\Property(property: 'pin', type: 'string', example: '123-45-6789'),
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        example: 'petr.pavel@example.com'
                    ),
                    new OA\Property(property: 'phone', type: 'string', example: '+420123456789'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Клиент создан',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Ошибка валидации'),
        ]
    )]
    public function create(Request $request): Response
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return $this->json([
                'error' => 'Invalid JSON',
            ], 400);
        }
        if (! is_array($data)) {
            return $this->json([
                'error' => 'Invalid JSON',
            ], 400);
        }

        foreach (['name', 'age', 'region', 'income', 'score', 'pin', 'email', 'phone'] as $field) {
            if (empty($data[$field])) {
                return $this->json([
                    'error' => "Missing field $field",
                ], 400);
            }
        }

        $command = new CreateClientCommand(
            $data['name'],
            (int) $data['age'],
            $data['region'],
            (int) $data['income'],
            (int) $data['score'],
            $data['pin'],
            $data['email'],
            $data['phone']
        );

        $id = ($this->handler)($command);

        return $this->json([
            'id' => $id,
        ], 201);
    }
}
