<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountNumbersTable extends Migration
{
    public function up()
    {
        Schema::create('account_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_account_type')->constrained('account_types');
            $table->string('account_number');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_numbers');
    }
}
