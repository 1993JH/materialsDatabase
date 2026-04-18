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
        Schema::create('fire_rating_wall_types', function (Blueprint $table) {
            $table->id();
            $table->string('wall_type')->unique();
            $table->unsignedSmallInteger('loadbearing_minutes');
            $table->unsignedSmallInteger('non_loadbearing_minutes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fire_rating_wall_types');
    }
};
