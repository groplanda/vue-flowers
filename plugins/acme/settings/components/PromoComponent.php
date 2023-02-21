<?php namespace Acme\Settings\Components;

use Cms\Classes\ComponentBase;
use Acme\Settings\Models\Promo;
use Response;

class PromoComponent extends ComponentBase
{
    public $promo;

    public function componentDetails()
    {
        return [
            'name'          => 'Промо',
            'description'   => 'Отображение баннера'
        ];
    }

    public function defineProperties()
    {
        return [
            'bannerId' => [
                'title'             => 'Выберите баннер',
                'type'              => 'dropdown',
                'default'           => 'us'
            ]
        ];
    }

    public function getBannerIdOptions()
    {
        return Promo::all()->lists('name', 'id');
    }

    public function onRun()
    {
        $conditional = ['id' => $this->property('bannerId'), 'is_active' => 1];
        $this->promo = Promo::where($conditional)->first();

    }

}
