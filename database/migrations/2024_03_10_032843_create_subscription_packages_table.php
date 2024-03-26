<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPackagesTable extends Migration
{
    public function up()
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('account_number');
            $table->foreignId('id_package')->constrained('packages');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_packages');
    }
}
