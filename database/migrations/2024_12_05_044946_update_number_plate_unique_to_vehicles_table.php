<?php

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
        Schema::table(TableNames::VEHICLES, function (Blueprint $table) {
            $table->unique('number_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(TableNames::VEHICLES, function (Blueprint $table) {
            $table->dropUnique('number_plate');
        });
    }
};
