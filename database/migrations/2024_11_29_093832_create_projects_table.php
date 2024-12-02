<?php
declare(strict_types=1);

use App\Enums\TableNames;
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
        Schema::create(TableNames::PROJECTS, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('year_of_start');
            $table->boolean('is_domestic');
            $table->foreignId('employee_id')
                    ->constrained('employees')
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
        Schema::dropIfExists('projects');
    }
};
