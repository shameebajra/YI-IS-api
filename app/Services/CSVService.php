<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Gender;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\User;

class CSVService
{

    public function __construct(protected string $filename)
    {

    }

    public function readCsv()
    {
        $csvContents = Storage::disk('data')->get($this->filename);
        return $csvContents;
    }

    function generateEmailAddress(string $firstName, string $lastName)
    {
        $randomNumber = rand(1, 100);

        return $firstName . '.' . $lastName . $randomNumber . '@gmail.com';
    }

    function generateName(string $firstName, string $lastName)
    {
        return $firstName . " " . $lastName;
    }

    function getMappedRole()
    {
        return Role::inRandomOrder()->first();
    }

    function generatePassword(string $firstname)
    {
        $dummyPassword = $firstname . "123";

        return bcrypt($dummyPassword);
    }

    function createUserArr() {
        $data = $this->readCsv();
        $rows = explode("\n", $data);

        $users = [];

//        foreach ($rows as $row) {
//            $users[] = $this->mapUserData($row);
//        }
//        ----

//        $users =  array_map(function ($row) {
//            return $this->mapUserData($row);
//        }, $rows);
//        ---

       $users = collect($rows)->map(function ($row) {
           return $this->mapUserData($row);
       });

        User::insert($users->toArray());
    }


    function mapUserData($row) {
        $rowVals = explode(',', $row);

        return [
            'name' => $this->generateName($rowVals[0], $rowVals[2] ?? ''),
            'email' => $this->generateEmailAddress($rowVals[0], $rowVals[2] ?? ''),
            'role_id' => $this->getMappedRole()->id,
            'password' => $this->generatePassword($rowVals[0]),
            'gender' => Gender::ALL[array_rand(Gender::ALL)],
            'join_date' => now(),
        ];
    }


    function doThis($array){
        foreach($array as $row){}
    }
}
