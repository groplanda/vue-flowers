<?php namespace Acme\Shop;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets()
    {
        return [
            'Acme\Shop\FormWidgets\Parentbox' => [
                'label' => 'Parent box',
                'code' => 'parentbox'
            ]
        ];
    }
}
