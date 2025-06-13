<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('introduccion_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('introduccion_id')->constrained('introducciones')->onDelete('cascade');
            $table->enum('tipo_archivo', ['PT', 'PTR', 'REMITO_IMAGEN']);
            $table->string('nombre_original');
            $table->string('nombre_archivo'); // Nombre hasheado en storage
            $table->string('ruta_archivo', 500);
            $table->string('mime_type', 50);
            $table->integer('tamaño_archivo')->nullable();
            $table->timestamps();
            
            $table->index(['introduccion_id', 'tipo_archivo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('introduccion_archivos');
    }
};

