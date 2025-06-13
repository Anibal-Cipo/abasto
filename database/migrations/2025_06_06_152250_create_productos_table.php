<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('categoria', 50); // CARNES, LACTEOS, FRUTAS, etc.
            $table->enum('tipo_medicion', ['CANTIDAD', 'PESO', 'MIXTO']);
            $table->string('unidad_primaria', 20)->nullable(); // Para MIXTO: "medias res", "cuartos"
            $table->string('unidad_secundaria', 20); // kg, litros, unidades, etc.
            $table->integer('dias_vencimiento')->default(30);
            $table->boolean('requiere_temperatura')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['categoria', 'activo']);
            $table->index('tipo_medicion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
