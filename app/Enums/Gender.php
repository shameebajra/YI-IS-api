<?php
namespace App\Enums;

class Gender{
    public const Male = 'M';
    public const Female = 'F';
    public const Others = 'O';

    public const ALL=[
        self::Male,
        self::Female,
        self::Others,
    ];
}
