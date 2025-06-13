<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('introducciones', function (Blueprint $table) {
            $table->string('envia')->nullable()->after('numero_remito_papel');
            $table->string('procedencia')->nullable()->after('envia');
            $table->boolean('vigente')->default(true)->after('procedencia');
        });
    }

    public function down()
    {
        Schema::table('introducciones', function (Blueprint $table) {
            $table->dropColumn(['envia', 'procedencia', 'vigente']);
        });
    }
};
