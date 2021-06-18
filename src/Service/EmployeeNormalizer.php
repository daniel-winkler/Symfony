<?php

namespace App\Service;

use App\Entity\Employee;

class EmployeeNormalizer {

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

        $data = [
            'name' => $employee->getName(),
            'email' => $employee->getEmail(),
            'age' => $employee->getAge(),
            'city' => $employee->getCity(),
            'department' => [
                'id' => $employee->getDepartment()->getId(),
                'name' => $employee->getDepartment()->getName()
            ],
            'projects' => $projects
        ];

        return $data;

    }
}