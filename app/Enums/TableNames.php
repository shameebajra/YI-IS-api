<?php
declare(strict_types=1);

namespace App\Enums;

class TableNames{
    public const ROLES = 'roles';
    public const EMPLOYEES = 'employees';
    public const VEHICLES = 'vehicles';
    public const PROJECTS = 'projects';
    public const EMPLOYEE_PROJECTS = 'employee_projects';

    public const TABLES=[
        self::ROLES,
        self::EMPLOYEES,
        self::VEHICLES,
        self::PROJECTS,
        self::EMPLOYEE_PROJECTS,
    ];

}
