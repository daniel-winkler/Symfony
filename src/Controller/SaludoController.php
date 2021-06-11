<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SaludoController {

    /**
     * @Route("/saludo", name="saludo_index");
     */

    public function index(): Response {

        return new Response('hola mundo');
    }
}
