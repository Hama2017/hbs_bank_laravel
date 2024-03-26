<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCardsTable extends Migration
{
    public function up()
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->string('number');
            $table->string('cvv');
            $table->string('exp_date');
            $table->decimal('amount');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_cards');
    }
}
