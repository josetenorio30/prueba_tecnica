<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[Route('/api/users', name: 'api_users_')]
final class UserController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/users',
        summary: 'Obtener lista de usuarios',
        tags: ['Usuarios'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuarios',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'email', type: 'string')
                        ]
                    )
                )
            )
        ]
    )]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();

        $data = array_map(fn($u) => [
            'id' => $u->getId(),
            'name' => $u->getName(),
            'email' => $u->getEmail(),
        ], $users);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/users',
        summary: 'Crear un usuario',
        tags: ['Usuarios'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Juan Pérez'),
                    new OA\Property(property: 'email', type: 'string', example: 'juan@example.com')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuario creado exitosamente'
            )
        ]
    )]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Usuario creado', 'id' => $user->getId()], 201);
    }
}
