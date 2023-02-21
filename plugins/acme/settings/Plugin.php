<?php namespace Acme\Settings;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
      return [
        'Acme\Settings\Components\PromoComponent' => 'PromoComponent',
      ];
    }

    public function registerSettings()
    {
    }
}
