<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                    ->constrained('employees')
                    ->onDelete('cascade')
                    ->onUpdate('cascade')
                    ->name('fk_employee_projects_employee_id')
                    ->index();
            $table->foreignId('project_id')
                    ->constrained('projects')
                    ->onDelete('cascade')
                    ->onUpdate('cascade')
                    ->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_projects');
    }
};
