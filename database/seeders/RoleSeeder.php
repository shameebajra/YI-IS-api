<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\RoleName;
use App\Enums\RoleWeight;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles= [
            ['name'=> RoleName::HR, 'weight'=> RoleWeight::HR],
            ['name'=> RoleName::PM, 'weight'=> RoleWeight::PM],
            ['name'=> RoleName::SEI, 'weight'=> RoleWeight::SEI],
            ['name'=> RoleName::SEII, 'weight'=> RoleWeight::SEII],
            ['name'=> RoleName::INTERN, 'weight'=> RoleWeight::INTERN],


        ];

        DB::table('roles')->insert($roles);

    }
}
