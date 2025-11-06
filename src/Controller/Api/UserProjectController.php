<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Project;
use App\Entity\UserProject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/user-projects')]
#[OA\Tag(name: 'Usuarios-Proyectos')]
final class UserProjectController extends AbstractController
{
    #[Route('', methods: ['POST'])]
    #[OA\Post(
        summary: 'Asignar usuario a un proyecto',
        description: 'Permite asignar un usuario a un proyecto con una tarifa personalizada.',
        tags: ['Usuarios-Proyectos']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'user_id', type: 'integer', example: 1, description: 'ID del usuario'),
                new OA\Property(property: 'project_id', type: 'integer', example: 2, description: 'ID del proyecto'),
                new OA\Property(property: 'rate', type: 'number', format: 'float', example: 150.50, description: 'Tarifa por hora o unidad del usuario en el proyecto')
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Usuario asignado al proyecto correctamente',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'User assigned to project'),
                new OA\Property(property: 'user_id', type: 'integer', example: 1),
                new OA\Property(property: 'project_id', type: 'integer', example: 2),
                new OA\Property(property: 'rate', type: 'number', example: 150.50)
            ]
        )
    )]
    #[OA\Response(response: 404, description: 'Usuario o proyecto no encontrado')]
    public function assign(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validaciones iniciales
        if (empty($data['user_id']) || empty($data['project_id']) || !isset($data['rate'])) {
            return $this->json(['error' => 'Campos obligatorios: user_id, project_id, rate'], 400);
        }

        $user = $em->getRepository(User::class)->find($data['user_id']);
        $project = $em->getRepository(Project::class)->find($data['project_id']);

        if (!$user || !$project) {
            return $this->json(['error' => 'User or project not found'], 404);
        }

        
        $userProject = new UserProject();
        $userProject->setUser($user);
        $userProject->setProject($project);
        $userProject->setRate($data['rate']);

        $em->persist($userProject);
        $em->flush();

        return $this->json([
            'message' => 'User assigned to project',
            'user_id' => $user->getId(),
            'project_id' => $project->getId(),
            'rate' => $userProject->getRate()
        ], 201);
    }
}
