<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redespachos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('introduccion_id')->constrained('introducciones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('numero_redespacho', 20)->unique();
            $table->date('fecha');
            $table->time('hora');
            $table->string('destino');
            $table->string('dominio', 10)->nullable();
            $table->string('habilitacion_destino')->nullable();
            $table->boolean('certificado_sanitario')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->index(['introduccion_id', 'fecha']);
            $table->index('numero_redespacho');
        });
    }

    public function down()
    {
        Schema::dropIfExists('redespachos');
    }
};
