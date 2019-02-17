<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('market_transactions');

        Schema::create('market_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->char('goods', 6);
            $table->unsignedInteger('user');
            $table->integer('quantity');
            $table->integer('price');
            $table->bigInteger('total');
            $table->timestamps();

            $table->foreign('goods')
                ->references('code')->on('goods')
                ->onDelete('cascade');

            $table->foreign('user')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_transactions');
    }
}
