<?php

namespace Jenga\MyProject\Tracker\Controllers;

use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Tracker\Models\TrackerModel;
use Jenga\MyProject\Tracker\Views\TrackerView;

/**
 * Class TrackerController
 *
 * @property-read TrackerModel $model
 * @property-read TrackerView $view
 *
 * @package Jenga\MyProject\Tracker\Controllers
 */
class TrackerController extends Controller
{

    public function index()
    {
    }

    /**
     * @return array
     */
    public function activeTracking()
    {
        return $this->model->all();
    }

    /**
     * @param int $customer_id
     * @param int $product_id
     * @param int $step
     * @param int $quote_id
     * @return bool
     */
    public function start($customer_id, $product_id, $step, $quote_id)
    {

        $tracker = $this->model;

        $tracker->customer_id = $customer_id;
        $tracker->product_id = $product_id;
        $tracker->quote_id = $quote_id;
        $tracker->step = $step;
        $tracker->created_at = time();
        $tracker->modified_at = time();
        $tracker->status = 'incomplete';

        $tracker->save();
        if ($tracker->hasNoErrors())
            return $tracker->last_altered_row;
        return false;
    }

    /**
     * Modifies the tracker to assign the correct location
     *
     * @param int $id
     * @param int $step
     * @return bool
     */
    public function assign($id, $step)
    {
        $tracker = $this->model->find($id);
        $tracker->step = $step;
        $tracker->modified_at = time();
        $tracker->save();

        return $tracker->hasNoErrors();
    }

    /**
     * Removes the quote tracking when the quote is complete
     *
     * @param int $id
     * @return bool
     */
    public function close($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}
