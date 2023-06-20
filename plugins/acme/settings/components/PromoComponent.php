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
            ],
            'templateType' => [
                'title'             => 'Внешний вид',
                'type'              => 'dropdown',
                'default'           => 'default',
                'placeholder'       => 'Выберите тип',
                'options'           => [ 'default' => 'По умолчанию', 'fixed' => 'С политикой конфиденциальности']
            ]
        ];
    }

    public function getBannerIdOptions()
    {
        return Promo::all()->lists('name', 'id');
    }

    public function onRender()
    {
        $view = $this->property('templateType');

        if($view == 'fixed') {
          return $this->renderPartial('@_fixed.htm');
        }
    }

    public function onRun()
    {
        $conditional = ['id' => $this->property('bannerId'), 'is_active' => 1];
        $this->promo = Promo::where($conditional)->first();

    }

}
