<?php
declare(strict_types=1);

namespace App\Enums;

class RoleWeight{
    public const INTERN = 1;
    public const SEI = 2;
    public const SEII = 3;
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
