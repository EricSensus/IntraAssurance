<?php

namespace Jenga\MyProject\Api\Library;

use Jenga\App\Request\Input;

/**
 * Class ApiRepository
 * @package Jenga\project\api\library
 */
abstract class ApiRepository extends API
{
    /**
     * Handle PULL requests for customer
     * @return object
     * @throws ApiExceptions
     */
    protected function getCustomer()
    {
        if ($this->single) {
            $customer = $this->_customer->getCustomerDataArray($this->id, true);
            if (empty($customer)) {
                throw new ApiExceptions("Customer not found", 404);
            }
            return (object)$customer;
        }
        $customers = [];
        foreach ($this->_customer->model->select('id')->all() as $customer) {
            $customers[] = $this->_customer->getCustomerDataArray($customer->id, true);
        }
        return (object)$customers;
    }

    /**
     * Create a new customer
     * @return string
     * @throws ApiExceptions
     */
    protected function postCustomer()
    {
        Validator::validate($this->request, Validator::$customer);
        $customer = $this->_customer->model;
        $customer->name = $this->request->name;
        $customer->id_number = $this->request->id_number;
        $customer->mobile_no = $this->request->mobile;
        $customer->email = $this->request->email;
        $customer->postal_address = $this->request->postal_address;
        $customer->postal_code = $this->request->postal_code;
        $customer->date_of_birth = strtotime($this->request->dob);
        $customer->regdate = time();
        $customer->save();
        if ($customer->hasNoErrors()) {
            return 'OK';
        }
        throw new ApiExceptions("Invalid data", 400);
    }

    /**
     * Get the quotes
     * @return object
     * @throws ApiExceptions
     */
    protected function getQuote()
    {
        if (!empty($this->id)) {
            $quote = $this->_quote->getQuoteData($this->id);
            if (empty($quote)) {
                throw new ApiExceptions("Quote not found", 404);
            }
            return $quote;
        }
        $quotes = [];
        foreach ($this->_quote->model->select('id')->all() as $quote) {
            $quotes[] = $this->_quote->getQuoteData($quote->id);
        }
        return (object)$quotes;
    }

    /**
     * @return object
     * @throws ApiExceptions
     */
    protected function getPolicy()
    {
        if (!empty($this->id)) {
            $policy = $this->_policy->getPolicyData($this->id);
            if (empty($policy)) {
                throw new ApiExceptions("Policy not found", 404);
            }
            return $policy;
        }
        $policies = [];
        foreach ($this->_policy->model->select('id')->all() as $policy) {
            $policies[] = $this->_policy->getPolicyData($policy->id);
        }
        return (object)$policies;
    }

    /**
     * @return string
     * @throws ApiExceptions
     */
    public function postPolicy()
    {
        Validator::validate($this->request, Validator::$policy);
        $policy = $this->_policy->model;
        $policy->policy_number = Input::post('policyno');
        $policy->customers_id = Input::post('customers_id');

        $policy->issue_date = 0;
        $policy->start_date = strtotime(Input::post('startdate'));
        $policy->end_date = strtotime(Input::post('enddate'));
        $policy->datetime = time();

        $policy->insurers_id = Input::post('insurers_id');
        $policy->products_id = Input::post('products_id');
        $policy->customer_quotes_id = Input::post('customer_quotes_id');

        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;

        $policy->status = $statuslist['policy_pending'];
        $policy->currency_code = Input::post('code');
        $policy->amount = Input::post('amount');
        $policy->save();
        if ($policy->hasNoErrors()) {
            return 'OK';
        }
        throw new ApiExceptions("Invalid data", 400);
    }

    /**
     * @return object
     * @throws ApiExceptions
     */
    protected function getClaim()
    {
        if (!empty($this->id)) {
            $claim = $this->_claim->getClaimData($this->id);
            if (empty($claim)) {
                throw new ApiExceptions("Claim not found", 404);
            }
            return $claim;
        }
        $policies = [];
        foreach ($this->_claim->model->select('id')->all() as $claim) {
            $policies[] = $this->_claim->getClaimData($claim->id);
        }
        return (object)$policies;
    }
}