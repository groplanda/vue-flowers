<?php namespace Acme\Settings\Models;

use Model;

/**
 * Model
 */
class Demand extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'acme_settings_request';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
