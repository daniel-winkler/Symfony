<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


                                // AbstractController es un controlador de symfony que pone a disposicion multitud de caracteristicas.
class DefaultController extends AbstractController{

    const PEOPLE = [
        ['name' => 'Carlos', 'email' => 'carlos@correo.com', 'age' => 30, 'city' => 'Benalmádena'],
        ['name' => 'Carmen', 'email' => 'carmen@correo.com', 'age' => 25, 'city' => 'Fuengirola'],
        ['name' => 'Carmelo', 'email' => 'carmelo@correo.com', 'age' => 35, 'city' => 'Torremolinos'],
        ['name' => 'Carolina', 'email' => 'carolina@correo.com', 'age' => 38, 'city' => 'Málaga'],        
    ];

    /**
     * @Route("/default", name="default_index");
     */

    // La clase ruta debe estar precedida en los comentario por una arroba.
    // El primer parámetro de Route es la URL a la que queremos asociar la acción.
    // El segundo parámetro de Route es el nombre que queremos dar a la ruta.

    public function index(): Response {

        // Una acción siempre debe devolver una respesta.
        // Por defecto deberá ser un objeto de la clase,
        // Symfony\Component\HttpFoundation\Response

        // render() es un metodo heredado de AbstractController que devuelve el contenido declarado en una plantilla twig
        // https://twig.symfony.com/doc/3.x/templates.html

        // $name = "Daniel";
        // $lastname = "Winkler";

        return $this->render('default/index.html.twig', [
            // "nombre" => $name,
            // "apellido" => $lastname
            "people" => self::PEOPLE
        ]);
    }

    /**
     * @Route("/hola", name="default_hola");
     */

    public function hola(): Response {
        return new Response("<html><body>hola</body></html>");
    }

    /**
     * @Route(
     *     "/default.{_format}",
     *     name="default_index_json",
     *     requirements = {
     *         "_format": "json"
     *     }
     * )
     */

    // symfony console router:match /default.json

    public function indexJson(): JsonResponse {
        return $this->json(self::PEOPLE); // con este metodo podriamos utilizar la clase Response, ya que la funcion json() utiliza JsonResponse.
        // return new JsonResponse(self::PEOPLE); // con self accedemos a constantes dentro de la clase
    }
}



