<?php

namespace Jenga\MyProject\Policies\Models;

use Jenga\App\Request\Input;
use Jenga\App\Models\ORM;

use Jenga\MyProject\Elements;

class PoliciesModel extends ORM
{
    /**
     * @var string
     */
    public $table = 'policies';
    /**
     * @var array
     */
    public $columns = [
        'policy_number' => 'Policy No',
        'issue_date' => 'Issue Date',
        'insurers_id' => 'Insurer',
        'start_date' => 'Validity',
        'customers_id' => 'Customer',
        'products_id' => 'Product',
        'status' => 'Status'
    ];

    /**
     * @param $id
     * @return mixed
     */
    public function getDataFromQuote($id)
    {
        $data = $this->table('customer_quotes')->where('id', $id)->first();
        return $data;
    }

    /**
     * @param $search
     * @param null $user
     * @return mixed
     */
    public function search($search, $user = null)
    {
        $data = $this->select(TABLE_PREFIX . 'policies.*, ' . TABLE_PREFIX . 'customers.insurer_agents_id')
            ->join('customers', TABLE_PREFIX . 'policies.customers_id = ' . TABLE_PREFIX . 'customers.id');

        // filter the search results by the logged in agent
        if (!is_null($user) && $user->is('agent'))
            $data->where('insurer_agents_id', $this->user()->insurer_agents_id);

        //search for the customer name
        if ($search['name'] != '') {
            $condition = 'Name: ' . $search['name'] . ', ';
            $data->where(TABLE_PREFIX . 'customers.name', 'LIKE', '%' . $search['name'] . '%');
        }

        //search for the product
        if ($search['product'] != '') {

            //get the product name
            $product = Elements::call('Products/ProductsController')->getProduct($search['product'], 'array');

            $condition .= 'Product: ' . $product['name'] . ', ';
            $data->where('products_id', '=', $search['product']);
        }

        //search for the insurer
        if ($search['insurer'] != '') {

            //get the insurer name
            $insurer = Elements::call('Insurers/InsurersController')->getInsurer($search['insurer']);

            $condition .= 'Insurer: ' . $insurer['name'] . ', ';
            $data->where('insurers_id', '=', $search['insurer']);
        }

        $searchresults['condition'] = $condition;
        $searchresults['result'] = $data->show();

        //store the search variables for use with the other tools
        $this->store();

        return $searchresults;
    }

    /**
     * @param null $user
     * @return \Jenga\App\Database\Mysqli\Database
     */
    public function getPolicies($user = null)
    {
        $policies = $this->select(TABLE_PREFIX . 'policies.*, ' . TABLE_PREFIX . 'customers.insurer_agents_id')
            ->join('customers', TABLE_PREFIX . 'policies.customers_id = ' . TABLE_PREFIX . 'customers.id');

        // filter by logged in user
        if (!is_null($user) && $user->is('agent'))
            $policies->where('insurer_agents_id', $user->insurer_agents_id);

        if (Input::post('pages') != 'all_pages') {
            $pages = explode(',', Input::post('pages'));

            $start = explode('-', $pages[0])[1];
            $length = explode('-', $pages[1])[1];
            //$end = explode('-', $pages[2])[1];

            $column = explode('-', $pages[3])[1];
            $order = $pages[4];

            $columns = $this->returnColumns('keys');
            $tablecol = $columns[$column];

            $policies = $policies->orderBy($tablecol, $order)
                ->show([$start, $length]);
        }

        return $policies;
    }

    /**
     * @return mixed
     */
    public function getPolicyAnalysis()
    {

        for ($month = 1; $month <= (date('m') + 12); $month++) {

            $start = mktime(0, 0, 0, $month, 1, date("Y", strtotime('-1 year')));
            $end = mktime(23, 59, 0, $month, date('t', $start), date("Y", strtotime('-1 year')));

            $reports = $this->where('datetime', '>=', $start)
                ->where('datetime', '<=', $end)
                ->show();

            $issuecount = 0;
            $notissuecount = 0;
            foreach ($reports as $report) {

                if ($report->issue_date == 0)
                    $notissuecount++;
                else
                    $issuecount++;
            }

            if ($month >= 13) {
                $newmonth = $month - 12;
                $dateObj = \DateTime::createFromFormat('!m', $newmonth);
            } else {
                $dateObj = \DateTime::createFromFormat('!m', $month);
            }

            $monthName = $dateObj->format('M'); // March   

            $notissue[$monthName] = $notissuecount;
            $issue[$monthName] = $issuecount;

            $months[] = $monthName;
        }

        $count = 0;
        foreach ($months as $month) {

            $monthdata['issued'][] = $issue[$month];
            $monthdata['notissued'][] = $notissue[$month];

            if ($count == 11)
                break;

            $count++;
        }

        $monthdata['months'] = $months;

        return $monthdata;
    }

    /**
     * @param bool $return_values
     * @return array
     */
    public function returnColumns($return_values = FALSE)
    {

        if ($return_values == FALSE) {

            return $this->columns;
        } elseif ($return_values == 'values') {

            return array_values($this->columns);
        } elseif ($return_values == 'keys') {

            return array_keys($this->columns);
        }
    }

    /**
     * @param $policy_id
     * @return array
     */
    public function getPolicyDocs($policy_id)
    {
        $policy_docs = $this->table('policies_documents')->where('policies_id', $policy_id)->show();
        $count = count($policy_docs);

        return [
            'count' => $count,
            'docs' => $policy_docs
        ];
    }

    /**
     * @param $policy_id
     * @return object
     */
    public function getPolicy($policy_id)
    {
        $policy = $this->where('id', $policy_id)->first();
        return $policy;
    }

    /**
     * @return mixed
     */
    public function getDirectActivePolicies()
    {
        $now = time();

        $direct_quotes = $this->select(TABLE_PREFIX . 'policies.*, '
            . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('end_date', '<', $now)
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')))
            ->whereIsNull('insurer_agents_id');

        return $direct_quotes;
    }

    /**
     * @return mixed
     */
    public function getDirectExpiredPolicies()
    {
        $now = time();

        $direct_quotes = $this->select(TABLE_PREFIX . 'policies.*, '
            . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('end_date', '<', $now)
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')))
            ->whereIsNull('insurer_agents_id');

        return $direct_quotes;
    }

    /**
     * @return mixed
     */
    public function getDirectPolicies()
    {
        $now = time();

        $direct_quotes = $this->select(TABLE_PREFIX . 'policies.*, '
            . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')))
            ->whereIsNull('insurer_agents_id');

        return $direct_quotes;
    }

    /**
     * Get Quotes by Agent
     * @param $agent_id
     * @param array $conditions
     * @return \Jenga\App\Database\Mysqli\Database
     */
    public function getPoliciesByAgent($agent_id, $conditions = array())
    {

        $agent_quotes = $this->select(TABLE_PREFIX . 'policies.*, '
            . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('insurer_agents_id', $agent_id)
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')));

        if (count($conditions)) {
            foreach ($conditions as $key => $value) {
                $agent_quotes->where($key, $value);
            }
        }
        return $agent_quotes;
    }

    /**
     * Mega finder
     * @param $quote_id
     * @param $string
     * @return mixed
     */
    public function findFromTable($quote_id, $string)
    {
        return $this->table($string)->where('id', $quote_id)->first();
    }

    /**
     * @param $agent_id
     * @return \Jenga\App\Database\Mysqli\Database
     */
    public function getExpiredPolicies($agent_id) {
        $now = time();

        $expired_policies = $this->select(TABLE_PREFIX . 'policies.*, ' . TABLE_PREFIX . 'customers.insurer_agents_id')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('insurer_agents_id', $agent_id)
            ->where('end_date', '<', $now)
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')));

        return $expired_policies;
    }

    /**
     * @param $agent_id
     * @return \Jenga\App\Database\Mysqli\Database
     */
    public function getActivePolicies($agent_id) {
        $now = time();

        $expired_policies = $this->select(TABLE_PREFIX . 'policies.*, ' . TABLE_PREFIX . 'customers.insurer_agents_id')
            ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
            ->where('insurer_agents_id', $agent_id)
            ->where('end_date', '>', $now)
            ->where('datetime', '>=', strtotime(Input::post('from_date')))
            ->where('datetime', '<=', strtotime(Input::post('to_date')));

        return $expired_policies;
    }
}