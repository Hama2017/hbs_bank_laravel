<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->string('account_number');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
}
