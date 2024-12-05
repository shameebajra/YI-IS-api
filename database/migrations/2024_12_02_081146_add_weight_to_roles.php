<?php
declare(strict_types=1);

use App\Enums\RoleWeight;
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
        Schema::table(TableNames::ROLES, function (Blueprint $table) {
            $table->enum('weight', RoleWeight::WEIGHTS );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(TableNames::ROLES, function (Blueprint $table) {
            //
        });
    }
};
