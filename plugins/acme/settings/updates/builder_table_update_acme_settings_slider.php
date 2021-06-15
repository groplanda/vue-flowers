<?php namespace Acme\Settings\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateAcmeSettingsSlider extends Migration
{
    public function up()
    {
        Schema::table('acme_settings_slider', function($table)
        {
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('acme_settings_slider', function($table)
        {
            $table->dropColumn('description');
        });
    }
}
