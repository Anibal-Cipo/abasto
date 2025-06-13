<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('introducciones', function (Blueprint $table) {
            $table->string('precintos_origen')->nullable()->after('observaciones');
            $table->string('reprecintado')->nullable()->after('precintos_origen');
            $table->string('ganaderia_numero')->nullable()->after('reprecintado');
            $table->boolean('remito_papel')->default(false)->after('ganaderia_numero');
            $table->string('numero_remito_papel')->nullable()->after('remito_papel');
        });

        // Agregar campo tipo a introductores
        Schema::table('introductores', function (Blueprint $table) {
            $table->enum('tipo', ['introductor', 'receptor', 'ambos'])->default('introductor')->after('activo');
        });
    }

    public function down()
    {
        Schema::table('introducciones', function (Blueprint $table) {
            $table->dropColumn(['precintos_origen', 'reprecintado', 'ganaderia_numero', 'remito_papel', 'numero_remito_papel']);
        });

        Schema::table('introductores', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
