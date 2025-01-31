<?php
declare(strict_types=1);

use App\Enums\Gender;
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
        Schema::create(TableNames::EMPLOYEES, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum ('gender',Gender::ALL);
            $table->date('join_date');
            $table->foreignId('role_id')
                  ->constrained('roles')
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
        Schema::dropIfExists('employees');
    }
};
