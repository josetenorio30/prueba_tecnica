<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Project;
use App\Entity\UserProject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/tasks')]
#[OA\Tag(name: 'Tareas')]
final class TaskController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener lista de tareas',
        description: 'Devuelve todas las tareas con su usuario, proyecto y tarifa asignada.',
        tags: ['Tareas']
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de tareas obtenida correctamente',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Diseñar interfaz'),
                    new OA\Property(property: 'user', type: 'string', example: 'Juan Pérez'),
                    new OA\Property(property: 'project', type: 'string', example: 'Proyecto CRM'),
                    new OA\Property(property: 'rate', type: 'number', example: 150.00),
                ]
            )
        )
    )]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();

        $data = [];

        foreach ($tasks as $t) {
            $user = $t->getUser();
            $project = $t->getProject();

            // Buscar la tarifa (UserProject)
            $rate = null;
            if ($user && $project) {
                $userProject = $em->getRepository(UserProject::class)
                    ->findOneBy(['user' => $user, 'project' => $project]);
                $rate = $userProject?->getRate();
            }

            $data[] = [
                'id' => $t->getId(),
                'name' => $t->getName(),
                'user' => $user?->getName(),
                'project' => $project?->getName(),
                'rate' => $rate,
            ];
        }

        return $this->json($data, 200);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear una nueva tarea',
        description: 'Crea una tarea asignada a un usuario y a un proyecto específicos.',
        tags: ['Tareas']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Implementar API de tareas'),
                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                new OA\Property(property: 'project_id', type: 'integer', example: 2),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Tarea creada correctamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Task created'),
                new OA\Property(property: 'id', type: 'integer', example: 10)
            ]
        )
    )]
    #[OA\Response(response: 404, description: 'Usuario o proyecto no encontrado')]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $em->getRepository(User::class)->find($data['user_id'] ?? null);
        $project = $em->getRepository(Project::class)->find($data['project_id'] ?? null);

        if (!$user || !$project) {
            return $this->json(['error' => 'User or project not found'], 404);
        }

        $task = new Task();
        $task->setName($data['name'] ?? 'Sin título');
        $task->setUser($user);
        $task->setProject($project);

        $em->persist($task);
        $em->flush();

        return $this->json(['message' => 'Task created', 'id' => $task->getId()], 201);
    }
}
