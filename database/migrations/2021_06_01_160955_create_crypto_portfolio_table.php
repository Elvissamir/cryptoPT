<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoPortfolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_portfolio', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('portfolio_id')
                  ->foreign('portfolio_id')
                  ->references('id')
                  ->on('portfolios');

            $table->unsignedbigInteger('crypto_id')
                  ->foreign('crypto_id')
                  ->references('id')
                  ->on('cryptos');

            $table->decimal('amount', 16, 8);
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
        Schema::dropIfExists('crypto_portfolio');
    }
}
