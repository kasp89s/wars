<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_items_sold', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->integer('itemId')->unsigned()->index();
            $table->integer('count')->unsigned();
            $table->decimal('totalAmount', 10);
            $table->timestamps();

            $table->foreign('itemId')->references('id')->on('bar_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bar_items_sold');
    }
};
