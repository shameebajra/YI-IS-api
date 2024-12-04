<?php

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
            ['name'=> RoleName::HR, 'weight'=> RoleWeight::ROLE_5],
            ['name'=> RoleName::PM, 'weight'=> RoleWeight::ROLE_4],
            ['name'=> RoleName::SEI, 'weight'=> RoleWeight::ROLE_3],
            ['name'=> RoleName::SEII, 'weight'=> RoleWeight::ROLE_2],
            ['name'=> RoleName::INTERN, 'weight'=> RoleWeight::ROLE_1],


        ];
        DB::table('roles')->insert($roles);

    }
}
