<?php

namespace App\Service;

use App\Entity\Employee;
use Symfony\Component\HttpFoundation\UrlHelper;

class EmployeeNormalizer {

    private $urlConstructor;

    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlConstructor = $urlHelper;
    }

    /**
     * Normalize an employee.
     * 
     * @param Employee $employee
     * 
     * @return array|null
     */

    public function employeeNormalizer(Employee $employee): ?array {

        $projects = [];
        
        foreach($employee->getProjects() as $project) {
            array_push($projects, [
                'id' => $project->getId(),
                'name' => $project->getName()
            ]);
        }

        $avatar = '';

        if ($employee->getAvatar()) {
            $avatar = $this->urlConstructor->getAbsoluteUrl('employee/avatar/'.$employee->getAvatar());
        }


        $data = [
            'name' => $employee->getName(),
            'email' => $employee->getEmail(),
            'age' => $employee->getAge(),
            'city' => $employee->getCity(),
            'department' => [
                'id' => $employee->getDepartment()->getId(),
                'name' => $employee->getDepartment()->getName()
            ],
            'projects' => $projects,
            'avatar' => $avatar
        ];

        return $data;

    }
}