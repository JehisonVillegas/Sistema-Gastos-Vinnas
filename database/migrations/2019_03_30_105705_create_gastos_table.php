<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idCuartel')->unsigned();
            $table->integer('idCategoria')->unsigned();
            $table->foreign('idCuartel')->references('id')->on('cuartels');
            $table->foreign('idCategoria')->references('id')->on('categorias');
            $table->string('nombre');
            $table->decimal('TotalmanoDeObra');
            $table->decimal('Totalinsumos');
            $table->decimal('gastoExtra')->nullable();
            $table->decimal('Total');
            $table->dateTime('fecha_hora');
            $table->timestamps();
            $table->string('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos');
    }
}
