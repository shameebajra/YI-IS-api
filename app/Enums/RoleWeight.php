<?php
declare(strict_types=1);

namespace App\Enums;

class RoleWeight{
    public const INTERN = 1;
    public const SEII = 2;
    public const SEI = 3;
    public const PM = 4;
    public const HR = 5;

    public const WEIGHTS = [
        self::HR,
        self::PM,
        self::SEI,
        self::SEII,
        self::INTERN,
    ];
}
