<?php namespace Acme\Settings\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateAcmeSettingsPromo extends Migration
{
    public function up()
    {
        Schema::create('acme_settings_promo', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->text('feature')->nullable();
            $table->integer('sort_order')->nullable();
            $table->boolean('is_active')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('acme_settings_promo');
    }
}
