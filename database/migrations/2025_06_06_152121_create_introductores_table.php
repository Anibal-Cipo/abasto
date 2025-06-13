<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('introductores', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('cuit', 13)->unique();
            $table->text('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('habilitacion_municipal')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['cuit', 'activo']);
            $table->index('razon_social');
        });
    }

    public function down()
    {
        Schema::dropIfExists('introductores');
    }
};