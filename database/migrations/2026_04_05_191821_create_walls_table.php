<?php

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
        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->text('Assembly_Description');
            $table->string('Climate_Zone');
            $table->text('Wall_Type');
            $table->decimal('R_Value_U_Value');
            $table->decimal('Embodied_Carbon');
            $table->decimal('Fire_Resistance_Rating');
            $table->decimal('Wall_Thickness(m/in)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walls');
    }
};
