<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/amazing-employees", name="api_employees_")
 */

// https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#successful_responses
// https://github.com/omniti-labs/jsend
// https://www.restapitutorial.com/lessons/httpmethods.html
// https://www.redhat.com/es/topics/api/what-is-a-rest-api

class ApiEmployeesController extends AbstractController
{
    /**
     * @Route(
     *      "",
     *      name="cget",
     *      methods={"GET"}  
     * )
     */
    public function index(
        Request $request,
        EmployeeRepository $employeeRepository,
        EmployeeNormalizer $employeeNormalizer
    ): Response
    {
        if($request->query->has('term')) {
            $people = $employeeRepository->findByTerm($request->query->get('term'));

            $data = [];

            foreach($people as $employee){
                $data[] = $employeeNormalizer->employeeNormalizer($employee);
            }

            return $this->json($data);
        }

        $result = $employeeRepository->findAll();

        $data = [];

        foreach($result as $employee){
            $data[] = $employeeNormalizer->employeeNormalizer($employee);
        }

        return $this->json($data);
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="get",
     *      methods={"GET"},
     *      requirements={
     *          "id": "\d+"
     *      }     
     * )
     */
    public function show(
        int $id,
        EmployeeRepository $employeeRepository,
        EmployeeNormalizer $employeeNormalizer
    ): Response
    {
        // Dump Server // https://symfony.com/doc/current/components/var_dumper.html#the-dump-server
        $data = $employeeRepository->find($id);
        dump($id);
        dump($data); // symfony console server:dump
        return $this->json($employeeNormalizer->employeeNormalizer($data));
    }

    /**
     * @Route(
     *      "",
     *      name="post",
     *      methods={"POST"}   
     * )
     */
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        DepartmentRepository $departmentRepository,
        EmployeeNormalizer $employeeNormalizer
        ): Response
    {
        // dump($request->request);
        $data = $request->request;

        $department = $departmentRepository->find($data->get('department_id'));

        $employee = new Employee();

        $employee->setName($data->get('name'));
        $employee->setEmail($data->get('email'));
        $employee->setAge($data->get('age'));
        $employee->setCity($data->get('city'));
        $employee->setPhone($data->get('phone'));
        $employee->setDepartment($department);

        $errors = $validator->validate($employee);
        // dump($errors);
        if(count($errors) > 0){
            $dataErrors = [];

            /**
             * @var \Symfony\Component\Validator\ConstraintViolation $error
             */
            foreach($errors as $error) {
                $dataErrors[] = $error->getMessage(); // la ruta @var da informacion de lo que es la variable $error y nos ayuda a autocompletar getMessage()
            }

            return $this->json([
                'status' => 'error',
                'data' => [
                    'errors' => $dataErrors
                ]
            ],
                Response::HTTP_BAD_REQUEST    
            );
        }

        // dump($employee);

        // Entity Manager // https://symfony.com/doc/current/doctrine.html
        $entityManager->persist($employee); // persist guarda los datos en memoria (como un commit en git)
        $entityManager->flush(); // flush ejecuta y manda el body a la base de datos

        return $this->json(
            $employeeNormalizer->employeeNormalizer($employee),
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'api_employees_get', // el name de la ruta a la que queremos relocalizar
                    [
                        'id' => $employee->getId() // ya que en nuestro Route hemos puesto parametros obligatorios id, se lo pasamos por array asociativo como nos indica generateURl
                    ]
                )
            ]
        );
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="put",
     *      methods={"PUT"},
     *      requirements={
     *          "id": "\d+"
     *      } 
     * )
     */
    public function update(
        Request $request,
        Employee $employee,
        EntityManagerInterface $entityManager
    ): Response
    {
        $data = $request->request;

        $data->has('name') ? $employee->setName($data->get('name')) : null ;
        $data->has('email') ? $employee->setEmail($data->get('email')) : null;
        $data->has('age') ? $employee->setAge($data->get('age')) : null;
        $data->has('city') ? $employee->setCity($data->get('city')) : null;
        $data->has('phone') ? $employee->setPhone($data->get('phone')) : null;

        // $entityManager->persist($employee); // no es necesario el persist cuando el objeto ya esta en nuestra BBDD
        $entityManager->flush();

        return $this->json(
            $employee,
            Response::HTTP_OK
        );
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="delete",
     *      methods={"DELETE"},
     *      requirements={
     *          "id": "\d+"
     *      } 
     * )
     */
    public function remove(
        Employee $employee,
        EntityManagerInterface $entityManager
        ): Response
    {
        dump($employee);

        //$employee = $employeeRepository->find($id); (int $id, EmployeeRepository $employeeRepository)
        // if(!$employee) {
        //     return $this->json([
        //         'message' => sprintf('No he encontrado el empledo con id.: %s', $id)
        //     ], Response::HTTP_NOT_FOUND);
        // }

        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
