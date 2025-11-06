<?php

namespace App\Controller\Api;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/projects')]
#[OA\Tag(name: 'Proyectos')]
final class ProjectController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener lista de proyectos',
        description: 'Devuelve todos los proyectos registrados en el sistema.',
        tags: ['Proyectos']
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de proyectos obtenida correctamente',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Proyecto CRM'),
                ]
            )
        )
    )]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $projects = $em->getRepository(Project::class)->findAll();

        $data = array_map(fn($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
        ], $projects);

        return $this->json($data, 200);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear un nuevo proyecto',
        description: 'Crea un nuevo proyecto en el sistema.',
        tags: ['Proyectos']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Sistema de Inventario'),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Proyecto creado correctamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Project created'),
                new OA\Property(property: 'id', type: 'integer', example: 5)
            ]
        )
    )]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return $this->json(['error' => 'El campo "name" es obligatorio'], 400);
        }

        $project = new Project();
        $project->setName($data['name']);

        $em->persist($project);
        $em->flush();

        return $this->json(['message' => 'Project created', 'id' => $project->getId()], 201);
    }
}
