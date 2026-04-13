<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wall_id')
                ->constrained('walls')
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained('materials')
                ->cascadeOnDelete();

            $table->unsignedInteger('layer_number');
            $table->decimal('layer_thickness', 8, 3);

            $table->unique(['wall_id', 'layer_number']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wall_material_layers');
    }
};
