<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUserToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('id_user')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
}
