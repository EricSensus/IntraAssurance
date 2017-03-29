<?php
namespace Jenga\MyProject\Rates\Models;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Input;

/**
 * Created by PhpStorm.
 * User: developer
 * Date: 27/02/2017
 * Time: 13:04
 */
class RatesModel extends ORM{
    public $table = 'rates';

    public function saveRate($insurer_id){
        $this->rate_name = Input::post('rate_name');
        $this->alias = Input::post('alias');
        $this->rate_value = Input::post('rate_value');
        $this->rate_type = Input::post('rate_type');
        $this->rate_category = Input::post('rate_category');
        $this->insurer_id = $insurer_id;
        $this->save();

        if ($this->hasNoErrors())
            return true;
        return false;
    }

    public function rateExists($finder){
        return $this->find($finder)->count();
    }

    public function getRateData($id){
        return $this->find($id);
    }

    public function updateRate($insurer){
        
        $rate = $this->find(Input::post('edit'));
        
        $rate->rate_name = Input::post('rate_name');
        $rate->alias = Input::post('alias');
        $rate->rate_value = Input::post('rate_value');
        $rate->rate_type = Input::post('rate_type');
        $rate->rate_category = Input::post('rate_category');
        $rate->insurer_id = $insurer->id;
        
        $rate->save();

        if ($this->hasNoErrors())
            return true;
        return false;
    }
}