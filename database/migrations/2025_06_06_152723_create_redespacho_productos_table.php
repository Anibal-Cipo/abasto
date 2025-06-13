<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redespacho_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redespacho_id')->constrained('redespachos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->decimal('cantidad_primaria', 10, 2)->nullable();
            $table->decimal('cantidad_secundaria', 10, 2);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->index(['redespacho_id', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('redespacho_productos');
    }
};