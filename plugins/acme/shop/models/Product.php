<?php namespace Acme\Shop\Models;

use Model;

/**
 * Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'acme_shop_product';

    protected $jsonable = ['props'];

    /**
     * @var array Validation rules
     */
    public $rules = [
      'title' => 'required',
      'gallery.*' => 'image|max:1000|dimensions:min_width=100,min_height=100'
    ];

    public $attachMany = [
      'gallery' => ['System\Models\File', 'delete' => true ]
    ];

    public function afterDelete() {
      foreach ($this->gallery as $image) {
          $image->delete();
      }
    }

    public $belongsToMany = [
      'categories' => [
          'Acme\Shop\Models\Category',
          'table' => 'acme_shop_product_categories',
      ],
      'tags' => [
        'Acme\Shop\Models\Tag',
        'table' => 'acme_shop_product_tags',
      ],
    ];
}
