<?php namespace Acme\Settings\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAcmeSettingsGallery extends Migration
{
    public function up()
    {
        Schema::table('acme_settings_gallery', function($table)
        {
            $table->integer('sort_order')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('acme_settings_gallery', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
