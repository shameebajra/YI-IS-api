<?php
declare(strict_types=1);

namespace App\Enums;

class RoleWeight{
    public const ROLE_1 = 1;
    public const ROLE_2 = 2;
    public const ROLE_3 = 3;
    public const ROLE_4 = 4;
    public const ROLE_5 = 5;

    public const WEIGHTS = [
        self::ROLE_1,
        self::ROLE_2,
        self::ROLE_3,
        self::ROLE_4,
        self::ROLE_5,
    ];
}
