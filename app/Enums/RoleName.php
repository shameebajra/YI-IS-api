<?php
declare(strict_types=1);

namespace App\Enums;


class RoleName{
    public const HR = 'HR';
    public const PM = 'PM' ;
    public const SEI = 'SEI';
    public const SEII = 'SEII';
    public const INTERN = 'Intern';

    public const ROLES =[
        self::HR,
        self::PM,
        self::SEI,
        self::SEII,
        self::INTERN,
    ];
}
