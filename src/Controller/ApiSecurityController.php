<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_security", methods={"POST"})
     */
    public function login(): Response
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles()
        ]);
    }
}
