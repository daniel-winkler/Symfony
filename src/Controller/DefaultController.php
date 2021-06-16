<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


                                // AbstractController es un controlador de symfony que pone a disposicion multitud de caracteristicas.
class DefaultController extends AbstractController{

    

    /**
     * @Route("/default", name="default_index");
     */

    // La clase ruta debe estar precedida en los comentario por una arroba.
    // El primer parámetro de Route es la URL a la que queremos asociar la acción.
    // El segundo parámetro de Route es el nombre que queremos dar a la ruta.

    public function index(Request $request, EmployeeRepository $employeeRepository): Response { // es mas comodo declarar la funcion de esta manera donde recibimos toda la info desde EmployeeRepository, que se nos creó al crear la entidad Employee por consola
        
        if($request->query->has('term')) {
            $people = $employeeRepository->findByTerm($request->query->get('term'));
            return $this->render('default/index.html.twig', [
                "people" => $people
            ]);
        }
        // if($request->query->has('id')) {
        //     echo '<pre>'; var_dump($request->get('id')); echo '</pre>'; 
        //     die();
        // }

        // echo '<pre>'; var_dump($request->query); echo '</pre>'; // equivalente a la superglobal $_GET de php
        // echo '<pre>'; var_dump($request->request); echo '</pre>'; // equivalente a la superglobal $_POST de php
        // echo '<pre>'; var_dump($request->server); echo '</pre>'; // equivalente a la superglobal $_SERVER de php
        // echo '<pre>'; var_dump($request->files); echo '</pre>'; // equivalente a la superglobal $_FILES de php
        // echo '<pre>'; var_dump($request->cookies); echo '</pre>'; // equivalente a la superglobal $_COOKIE de php
        // die();

        // Una acción siempre debe devolver una respesta.
        // Por defecto deberá ser un objeto de la clase,
        // Symfony\Component\HttpFoundation\Response

        // render() es un metodo heredado de AbstractController que devuelve el contenido declarado en una plantilla twig
        // https://twig.symfony.com/doc/3.x/templates.html

        // $name = "Daniel";
        // $lastname = "Winkler";

        // OTRO METODO
        // $orm = $this->getDoctrine();
        // $repo = $orm->getRepository(Employee::class); // use App\Entity\Employee;
        // $people = $repo->findAll();

        $order = [];
        if($request->query->has('orderBy')) {
            $order[$request->query->get('orderBy')] = $request->query->get('orderDir');
        }

        $people = $employeeRepository->findBy([], $order); // ctrl+click a EmployeeRepository para ver los metodos disponibles

        return $this->render('default/index.html.twig', [
            // "nombre" => $name,
            // "apellido" => $lastname
            "people" => $people
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

    public function indexJson(Request $request, EmployeeRepository $employeeRepository): JsonResponse {
        
        $person = $request->query->has('id') ? $employeeRepository->find($request->query->get('id')) : $employeeRepository->findAll();
        // $orm = $this->getDoctrine();
        // $repo = $orm->getRepository(Employee::class); // use App\Entity\Employee;
        // $people = $repo->findAll();
        // $people = $employeeRepository->findAll();
        
        return $this->json($person); // con este metodo podriamos utilizar la clase Response, ya que la funcion json() utiliza JsonResponse.
        // return new JsonResponse($people); // con self accedemos a constantes dentro de la clase
    }

    // Solucion Loli
    // public function userJson(int $id, EmployeeRepository $employeeRepository): JsonResponse {
    //     $data = $employeeRepository->find($id);
    //     return $this->json($data);
    // }


    /**
     * @Route(
     *      "/default/{id}",
     *      name="default_show",
     *      requirements = {
     *          "id": "\d+"
     *      }
     * )
     */

    public function show(int $id, EmployeeRepository $employeeRepository): Response {
        // var_dump($id); die();
        $person = $employeeRepository->find($id);
        dump($person); // nos indica en el profiler como debug lo que nos vale
        return $this->render('default/show.html.twig', [
            'id' => $id,
            'person' => $person
        ]);
    }

    /**
     * @Route(
     *      "/default/{id}",
     *      name="default_show",
     *      requirements = {
     *          "id": "\d+"
     *      }
     * )
     */
    // La técinca ParamConverte inyecta directamente,
    // un objeto del tipo indicado como parámetro
    // intentando hacer un match del parámetro de la ruta
    // con alguna de las propiedades del objeto requerido.
    // public function show(Employee $employee): Response {
    //     return $this->render('default/show.html.twig', [
    //         'person' => $employee
    //     ]);
    // }


    /**
     * @Route(
     *      "/redirect-to-home",
     *      name="default_redirect_to_home"
     * )
     */
    public function redirectToHome(): Response {
        
        // Redirigir a la URL "/"
        // return $this->redirect('/');

        // Redirigir a una ruta utilizando su nombre.
        // return $this->redirectToRoute('default_show', [
        //     'id' => 1
        // ]);

        return new RedirectResponse("/", Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * @Route(
     *      "default/{id}.{_format}",
     *      name="default_person_json",
     *      requirements = {
     *         "_format": "json"
     *      }
     * )
     */
    public function personJson(int $id, EmployeeRepository $employeeRepository): JsonResponse {
        $person = $employeeRepository->find($id);
        return $this->json($person);
    }
}



