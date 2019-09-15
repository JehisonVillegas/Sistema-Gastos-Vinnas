<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_gastos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idGasto')->unsigned();
            $table->foreign('idGasto')->references('id')->on('gastos')->onDelete('cascade');
            $table->integer('idInsumo')->unsigned();
            $table->foreign('idInsumo')->references('id')->on('insumos');
            $table->integer('cantidad');
            $table->decimal('costo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_gastos');
    }
}
