<?php namespace Acme\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAcmeShopOrders2 extends Migration
{
    public function up()
    {
        Schema::table('acme_shop_orders', function($table)
        {
            $table->integer('user_id')->nullable();
            $table->string('total', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('acme_shop_orders', function($table)
        {
            $table->dropColumn('user_id');
            $table->dropColumn('total');
        });
    }
}
