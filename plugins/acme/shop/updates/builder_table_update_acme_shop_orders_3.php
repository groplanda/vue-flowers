<?php namespace Acme\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAcmeShopOrders3 extends Migration
{
    public function up()
    {
        Schema::table('acme_shop_orders', function($table)
        {
            $table->integer('user_id')->nullable();
            $table->string('total')->nullable();
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
