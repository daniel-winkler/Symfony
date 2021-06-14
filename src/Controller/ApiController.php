<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_") // indicamos la ruta como raiz de las siguientes, que se van concatenando.
 */

class ApiController extends AbstractController
{
    // #[Route('/api', name: 'api')]  // Route que se nos autogenera con el comando symfony console make:controller (en windows)

    /**
     * @Route("/", name="get")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }
}
