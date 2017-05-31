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
class RatesModel extends ORM
{
    public $table = 'rates';

    /**
     * Save rate
     * @param null $insurer_id
     * @return bool
     */
    public function saveRate($insurer_id = null)
    {
        if (empty($insurer_id)) {
            $insurer_id = Input::post('insurer_id');
        }
        $this->rate_name = Input::post('rate_name');
        $this->rate_value = Input::post('rate_value');
        $this->rate_type = Input::post('rate_type');
        $this->rate_category = Input::post('rate_category');
        $this->insurer_id = $insurer_id;
        $this->save();

        if ($this->hasNoErrors())
            return true;
        return false;
    }

    public function rateExists($finder)
    {
        return $this->find($finder)->count();
    }

    public function getRateData($id)
    {
        return $this->find($id);
    }

    /**
     * Update rates from post
     * @return bool
     */
    public function updateRate()
    {
        $rate = $this->find(Input::post('edit'));
        $rate->rate_name = Input::post('rate_name');
        $rate->rate_value = Input::post('rate_value');
        $rate->rate_type = Input::post('rate_type');
        $rate->rate_category = Input::post('rate_category');
        $rate->insurer_id = Input::post('insurer_id');
        $rate->save();

        return $this->hasNoErrors();
    }
}