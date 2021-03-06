<?php namespace Acme\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAcmeShopCategories2 extends Migration
{
    public function up()
    {
        Schema::table('acme_shop_categories', function($table)
        {
            $table->boolean('is_quiz')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('acme_shop_categories', function($table)
        {
            $table->dropColumn('is_quiz');
        });
    }
}
