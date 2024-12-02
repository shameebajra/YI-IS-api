<?php
declare(strict_types=1);

namespace App\Enums;

class Gender{
    public const MALE = 'M';
    public const FEMALE = 'F';
    public const OTHERS = 'O';

    public const ALL=[
        self::MALE,
        self::FEMALE,
        self::OTHERS,
    ];
}
