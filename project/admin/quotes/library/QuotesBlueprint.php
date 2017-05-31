<?php

namespace Jenga\MyProject\Quotes\Library;

use Jenga\App\Core\App;
use Jenga\MyProject\Accident\Models\PersonalCoverPricing;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Insurers\Controllers\InsurersController;
use Jenga\MyProject\Products\Controllers\ProductsController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Rates\Controllers\RatesController;

/**
 * Class RatesBlueprint
 * @property-read PersonalCoverPricing $_pricing_model
 * @package Jenga\MyProject\Rates\Lib
 */
abstract class QuotesBlueprint
{
    /**
     * @var int
     */
    protected $insurer_id = 14;

    /**
     * @var CustomersController
     */
    protected $_customer;
    /**
     * @var EntitiesController
     */
    private $_entities;
    /**
     * @var QuotesController
     */
    private $_quotes;
    /**
     * @var ProductsController
     */
    private $_products;
    /**
     * @var RatesController
     */
    private $_rates;
    /**
     * @var InsurersController
     */
    private $_insurer;
    /**
     * The saved quote
     * @var object|array
     */
    protected $saved_quote;

    /**
     * @var int
     */
    protected $customer_id;
    /**
     * @var object
     */
    public $insurer, $customer, $main_entity, $other_entities, $product, $quote_product_info;
    /**
     * The field of interest for an entity that can distinguish it
     */
    private $entity_index = 'id';
    /**
     * @var \Jenga\MyProject\Quotes\Models\QuotesModel
     */
    protected $_model;

    /**
     * RatesBlueprint constructor.
     * @param object|\stdClass $quote
     */
    public function __construct($quote)
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_rates = Elements::call('Rates/RatesController');
        $this->_products = Elements::call('Products/ProductsController');
        $this->_insurer = Elements::call('Insurers/InsurersController');
        $this->_model = $this->_quotes->model;
        $this->_pricing_model = App::get(PersonalCoverPricing::class);
        $this->saved_quote = $quote;
        $this->setUpExtraValues();
    }


    /**
     * Do quote calculation
     * @return mixed
     */
    public function calculate()
    {
        switch ($this->product->alias) {
            case 'motor_insurance':
                $this->entity_index = 'regno';
                $this->getMotorQuote();
                break;
            case 'personal_accident':
                $this->entity_index = 'name';
                $this->getAccidentQuote();
                break;
            case 'travel_insurance':
                $this->getTravelQuote();
                break;
            case 'domestic_package':
                $this->entity_index = 'plotno';
                $this->getDomesticQuote();
                break;
            case 'medical_insurance':
                $this->entity_index = 'name';
                $this->getMedicalQuote();
                break;
        }
        $this->updateQuote();
        return $this;
    }

    /**
     * Set the amount in database
     */
    private function updateQuote()
    {
        $available = get_object_vars($this);
        $to_save = array_only($available, $this->quoteFields());
        $others = [];
        foreach ($this->other_entities as $en) {
            $others[$en->{$this->entity_index}] = array_only(get_object_vars($en), $this->quoteFields());
        }
        $mine = $this->_quotes->model->find($this->saved_quote->id);
        $amount_exist = !empty($mine->amount);
        $to_save = array_prepend($to_save, $others, 'other_covers');
        $to_save = array_prepend($to_save, $this->insurer_id, 'insurer_id');
        $to_save['chosen'] = ($this->insurer_id == "14"); //@todo Make this nice

        if (!$amount_exist) {
            $save = [$this->insurer_id => $to_save];
        } else {
            $save = get_object_vars(json_decode($mine->amount));
            $save[$this->insurer_id] = $to_save;
        }
        $total = json_encode($save);
        $mine->amount = $total;
        $mine->save();
    }

    /**
     * Determines the optional benefits for medical
     * @param $selected_plan
     * @param $core_optional_benefits
     * @return int
     */
    public function getOptionalBenefitsTotal($selected_plan, $core_optional_benefits)
    {
        $optional_total = 0;
        // get core optional benefits
        $core_optional_benefits = $this->_model->table('medical_pricing')
            ->where('agerange_benefits', 'IN', $core_optional_benefits)
            ->get();

        if (count($core_optional_benefits)) {
            foreach ($core_optional_benefits as $benefit) {
                $benefit_amount = $benefit->$selected_plan;
                $optional_total += $benefit_amount;
            }
        }
        return $optional_total;
    }

    /**
     * Get cover
     * @param $plan
     * @return string
     */
    protected function getCoverForTravel($plan)
    {
        if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'africa_basic_plan')
            return 'Africa Basic Plan';
        else if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'world_wide_basic_plan')
            return 'Worldwide Basic Plan';
        else if ($plan == 'world_wide_plus_plan')
            return 'Worldwide Plus Plan';
        else if ($plan == 'world_wide_extra')
            return 'Worldwide Extra';
        else if ($plan == 'haj_and_umra_basic_plan')
            return 'Haj and Umrah Plan Basic';
        else if ($plan == 'haj_and_umra_plus_plan')
            return 'Haj and Umrah Plan Plus';
        else if ($plan == 'haj_and_umra_extra_plan')
            return 'Haj and Umrah Plan Extra';
    }

    /**
     * Determine the rate based on selected plan and days
     * @param $plan
     * @param $days
     * @return mixed
     */
    protected function getRateForTravel($plan, $days)
    {
        $premium_rate = null;
        $plan_id = $this->_model->table('travel_pricing')->find([
            'plan' => $plan
        ])->id;

        if (($plan != "Haj and Umrah Plan Basic") && ($plan != "Haj and Umrah Plan Plus") && ($plan != "Haj and Umrah Plan Extra")) {
            $rate = $this->_model->table('travel_pricing')
                ->join("travel_plan_details tpd", TABLE_PREFIX . "travel_pricing.id = tpd.plan_id", "LEFT")
                ->where('days', '=', $days)
                ->where('plan_id', '=', $plan_id)
                ->get();
            $premium_rate = $rate[0]->premium;
        } else {
            $rates = $this->_model->table('travel_pricing')
                ->join("travel_plan_details tpd", TABLE_PREFIX . "travel_pricing.id = tpd.plan_id")
                ->where('plan_id', '=', $plan_id)
                ->get();

            if (count($rates)) {
                foreach ($rates as $rate) {
                    $range = $rate->days;
                    $range = explode("-", $range);

                    if (($days >= $range[0]) && ($days <= $range[1])) {
                        $premium_rate = $rate->premium;
                        break;
                    } else {
                        continue;
                    }
                }
            }
        }
        return $premium_rate;
    }

    /**
     * Additional parameters for quote generation
     */
    public function setUpExtraValues()
    {
        $this->customer_id = $this->saved_quote->customers_id;
        $this->customer = $this->getCustomerDataArray($this->customer_id);
        $__id = json_decode($this->saved_quote->customer_entity_data_id);
        $this->main_entity = $this->getFullEntityDetails($__id[0]);
        $others = array_except($__id, 0);
        $all = [];
        foreach ($others as $_id) {
            $all[] = $this->getFullEntityDetails($_id);
        }
        $this->other_entities = $all;
        $this->product = $this->_products->getProductById($this->saved_quote->products_id);
        $this->quote_product_info = json_decode($this->saved_quote->product_info);
        $this->insurer = (object)$this->_insurer->getInsurer($this->insurer_id);
    }

    /**
     * Override some values in the above statements
     */
    protected function setupTravelValues()
    {
        $__id = json_decode($this->saved_quote->customer_entity_data_id);
        $this->main_entity = $this->customer;
        $all = [];
        foreach ($__id as $_id) {
            $all[] = $this->getFullEntityDetails($_id);
        }
        $this->other_entities = $all;
    }

    /**
     * Fetch premium rate values
     * @param $_band
     * @param $_age_bracket
     * @param $_class
     * @return double
     */
    protected function _payments($_band, $_age_bracket, $_class)
    {

        $class = "C1";
        if ($_class == "class2") {
            $class = "C2";
        }
        switch ($_band) {
            case "band1":
                $band = "B1";
                break;
            case "band2":
                $band = "B2";
                break;
            case "band3":
                $band = "B3";
                break;
            case "band4":
                $band = "B4";
                break;
            case "band5":
                $band = "B5";
                break;
            case "band6":
                $band = "B6";
                break;
            case "band7":
                $band = "B7";
                break;
            default:
                $band = "B1";
        }

        switch ($_age_bracket) {
            case "56-69":
                $age_bracket = "A3";
                break;
            case "18-55":
                $age_bracket = "A2";
                break;
            default:
                $age_bracket = "A1";
        }
        $result = $this->_pricing_model
            ->where('age_bracket', $age_bracket)
            ->where('class', $class)
            ->where('band', $band);
        if (count($result->show(1)) == 1)
            return $result->show(1)[0]->premium;
        return null;
    }

    /**
     * System representation for age bracket
     * @param $range
     * @return string
     */
    protected function formatAgeBracket($range)
    {
        switch ($range) {
            case '3 - 17':
                $age_bracket = '3-17';
                break;
            case '18-21':
            case '19-30':
            case '22-25':
            case '26-30':
            case '31-40':
            case '41-50':
            case '51-60':
                $age_bracket = '18-55';
                break;
            case '61 - 69':
            case '70 or over':
                $age_bracket = '56-69';
                break;
            default:
                $age_bracket = '18-55';
                break;
        }
        return $age_bracket;
    }

    protected function getTheOptionalBenefits($step_data, $priority, $dependants_no = null)
    {
        foreach ($step_data as $key => $value) {
            $step2[$key] = $value;
        }

        switch ($priority) {
            case 'core':
                $optionals = [];
                for ($l = 'a'; $l <= 'd'; $l++) {
                    for ($i = 1; $i <= 4; $i++) {
                        $index = 'b' . $l . $i;

                        if (!empty($step2[$index]))
                            $optionals[$index] = $step2[$index];
                    }
                }

                return self::getOptionalLIst($optionals);
                break;

            case 'dependants':
//                print_r($step_data);exit;
                $dep_optionals = [];
                for ($dep = 1; $dep <= $dependants_no; $dep++) {
                    for ($l = 'a'; $l <= 'd'; $l++) {
                        for ($i = 1; $i <= 4; $i++) {
                            $index = 'b' . $l . $i . '_' . $dep;

                            if (!empty($step2[$index]) && isset($step2[$index])) {
                                $dep_optionals[$index] = $step2[$index];
                            }
                        }
                    }
                }

                return self::getOptionalLIst($dep_optionals);
                break;
        }
    }

    public function determinePlan($plan)
    {
        if ($plan == 'premier')
            $plan = 'P1';
        else if ($plan == 'advanced')
            $plan = 'P2';
        else if ($plan == 'executive')
            $plan = 'P3';
        else
            $plan = 'P4';
        return $plan;
    }

    public static function getOptionalList($optionals)
    {
        $optional_list = [];
        if (count($optionals)) {
            foreach ($optionals as $optional) {
                $optional_list[] = $optional;
            }
        }
        return $optional_list;
    }

    /**
     * Get System representation for age bracket
     * @param $agebracket
     * @param $gender
     * @return string
     */
    protected function determineAgeBracket($agebracket, $gender)
    {
//        dd($gender);
        switch ($agebracket) {
            case '1-18':
                return $agebracket = 'Ac';
                break;

            case '19-30':
                if ($gender == 'Male')
                    $agebracket = 'A1';
                else
                    $agebracket = 'A1';
                return $agebracket;
                break;

            case '31-40':
                if ($gender == 'Male')
                    return $agebracket = 'A2';
                else
                    $agebracket = 'A2';
                return $agebracket;
                break;

            case '41-50':
                if ($gender == 'Male')
                    $agebracket = 'A3';
                else
                    $agebracket = 'A3';
                return $agebracket;
                break;

            case '51-59':
                if ($gender == 'Male')
                    $agebracket = 'A4';
                else
                    $agebracket = 'A4';
                return $agebracket;
                break;

            case '60-65':
                if ($gender == 'Male')
                    $agebracket = 'A5';
                else
                    $agebracket = 'A5';
                return $agebracket;
                break;
        }
    }


    /**
     * Get the quotes field
     * @return array
     */
    private function quoteFields()
    {
        return [
            'policy_fund', 'stamp_duty', 'total', 'other_total', 'basic_premium', //accident
            'training_levy', 'policy_levy', 'section_a', 'section_b', 'section_c',
            'workmen', 'owner_liability', 'occupiers_liability', 'gross_premium',//domestic
            'training_levy', 'policy_levy',
            'passenger', 'audio', 'windscreen', 'terrorism',
            'net_premium', 'total_net_premiums', 'ncd_amount' //motor
        ];
    }

    /**
     * Get the rates
     * @param double $tsi
     * @param string $rate_name
     * @param string $category
     * @param string|null $rate_type
     * @return float|int
     */
    protected function getRates($tsi, $rate_name, $category, $rate_type = null)
    {
        if (!($this->used_rates instanceof \stdClass)) {
            $this->used_rates = new \stdClass();
        }
        $model = $this->_rates->model;
        if (empty($rate_type)) {
            $rate = $model->where('rate_category', $category)
                ->where('insurer_id', $this->insurer_id)
                ->where('rate_name', $rate_name)->first();
        } else {
            $rate = $model->where('rate_category', $category)
                ->where('insurer_id', $this->insurer_id)
                ->where('rate_name', $rate_name)
                ->where('rate_type', $rate_type)->first();
        }
        if ($rate->rate_type == 'Percentage') {
            $computed_value = (($tsi * $rate->rate_value) / 100);
        } elseif ($rate->rate_type == 'Fixed') {
            $computed_value = $rate->rate_value;
        }

        $this->used_rates->{$this->strClean($category . " " . $rate_name)} = ['rate' => $rate->rate_value, 'type' => $rate->rate_type];
        return $computed_value;
    }

    /**
     * Get the return rate
     * @param $rate_name
     * @param $category
     * @return string
     */
    public function getReturnRate($rate_name, $category)
    {
        $rates = $this->_rates->model;
        $rate_model = $rates->find(['rate_category' => $category, 'rate_name' => $rate_name, 'insurer_id' => $this->insurer_id]);
        return ($rate_model->rate_type == 'Percentage' ? $rate_model->rate_value . '%' : $rate_model->rate_value);
    }

    /**
     * Clean a string to return in rates help block
     * @param $text
     * @return string
     */
    protected function strClean($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        return (empty($text)) ? 'n-a' : $text;
    }

    private function getCustomerDataArray($customer_id = null)
    {
        $_customer = $this->_customer->getCustomerById($customer_id, false);
        $info = get_object_vars($_customer);
        $product_datas = json_decode($info['additional_info']);
        unset($info['additional_info']);
        $info['dob'] = date('Y-m-d', $info['date_of_birth']);
        $info['mobile'] = $info['mobile_no'];
        $info['address'] = $info['postal_address'];
        $info['code'] = $info['postal_code'];
        $info['surname'] = substr($info['name'], 0, strpos($info['name'], ' '));
        $info['names'] = substr($info['name'], 1 + strpos($info['name'], ' '));
        $info['id_passport_no'] = $info['id_passport_number'] = $_customer->id_number;
        $the_arrays = $info;
        if (count($product_datas)) {
            foreach ($product_datas as $for_product) {
                $the_arrays = array_merge($the_arrays, get_object_vars($for_product));
            }
        }
        return (object)$the_arrays;
    }

    /**
     * Get all the details
     * @param $entity_id
     * @return mixed
     */
    private function getFullEntityDetails($entity_id)
    {
        $db_info = $this->_entities->getEntityDataByfinder($entity_id);
        $extras = json_decode($db_info->entity_values);
        unset($db_info->entity_values);
        foreach ($extras as $k => $v) {
            $db_info->{$k} = $v;
        }
        return $db_info;
    }

    /**
     * Do calculation for Motor
     * @return mixed
     */
    abstract protected function getMotorQuote();

    /**
     * Do calculation for Travel
     * @return mixed
     */
    abstract protected function getTravelQuote();

    /**
     * Do calculation for Accident
     * @return mixed
     */
    abstract protected function getAccidentQuote();

    /**
     * Do calculation for Medical
     * @return mixed
     */
    abstract protected function getMedicalQuote();

    /**
     * Do calculation for Domestic
     * @return mixed
     */
    abstract protected function getDomesticQuote();


}