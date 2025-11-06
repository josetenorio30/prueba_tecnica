<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskViewController extends AbstractController
{
    #[Route('/tasks/view', name: 'tasks_view')]
    public function index(): Response
    {
        return $this->render('tasks/view.html.twig');
    }
}
