<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Database\Factories\EmployeeFactory;
use Database\Factories\UserFactory;
use Illuminate\Console\Command;

class EmployeeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:employee-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add factory employee data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for($i=0; $i<=20; $i++){
            User::factory()->create();
        }
    }
}
