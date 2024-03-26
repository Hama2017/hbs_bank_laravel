<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSubscriptionPackagesTable extends Migration
{
    public function up()
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->string('account_number');
            $table->foreignId('id_package')->constrained('packages');
        });
    }

    public function down()
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn('account_number');
            $table->dropForeign(['id_package']);
        });
    }
}
