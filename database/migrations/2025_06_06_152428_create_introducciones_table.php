<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('introducciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('introductor_id')->constrained('introductores')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuario que carga
            $table->string('numero_remito', 20)->unique();
            $table->date('fecha');
            $table->time('hora');
            $table->string('vehiculo')->nullable();
            $table->string('dominio', 10)->nullable();
            $table->string('habilitacion_vehiculo')->nullable();
            $table->text('receptores')->nullable();
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('pt_numero', 50)->nullable(); // Permiso Tránsito
            $table->string('ptr_numero', 50)->nullable(); // Permiso Tránsito Restringido
            $table->string('qr_code', 100)->unique()->nullable();
            $table->timestamps();
            
            $table->index(['introductor_id', 'fecha']);
            $table->index(['fecha', 'hora']);
            $table->index('numero_remito');
        });
    }

    public function down()
    {
        Schema::dropIfExists('introducciones');
    }
};