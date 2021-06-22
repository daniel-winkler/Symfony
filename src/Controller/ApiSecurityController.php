<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_security", methods={"POST"})
     */
    public function login(
        EntityManagerInterface $entityManagerInterface
    ): Response
    {
        $user = $this->getUser();

        $token = sha1(random_bytes(32)); // sha1 normaliza los bytes generados para que no salgan caracteres raros
        $now = new \DateTime();
        $tokenExpiration = $now->add(new \DateInterval('PT1H'));

        $user->setToken($token);
        $user->setTokenExpiration($tokenExpiration);

        // $entityManagerInterface->persist();
        $entityManagerInterface->flush();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'token' => $user->getToken(),
            'token_expiration' => $user->getTokenExpiration()
        ]);
    }
}
