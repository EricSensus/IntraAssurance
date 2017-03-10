<?php
namespace Jenga\MyProject\Quotes\Lib;

use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\App\Views\Overlays;
use Jenga\MyProject\Quotes\Models\TravelPricing;

class TravelQuotes extends Quotes implements QuotesInterface
{
    /**
     * @var TravelPricing
     */
    private $_pricing_model;

    /**
     * Set some additional data
     * @return QuotesInterface
     */
    public function setAdditionalValues()
    {
        $this->_pricing_model = App::get(TravelPricing::class);
        return $this;
    }

    /**
     * Do calculations
     */
    public function calculateValues()
    {
//        print_r(Input::post());exit;
////        $quote = $this->quote->getQuoteById(Session::get('quote_id'));
//        $product_info = json_decode($quote->product_info);
//        $customer_info = json_decode($quote->product_info);
//        $amounts = json_decode($quote->amount);
        $post = Input::post();
        $days = Input::post('Approximately_how_many_days_will_you_be_on_travel');
        // get the rate
        $cover = $this->getCover($post['Plan']);
        exit("Calculation not complete");

//        $quote = [
//            'name' => $customer_info->surname . ' ' . $customer_info->names,
//            'cover_plan' => $cover,
//            'days_of_travel' => $product_info->no_travel_days,
//            'basic_premium' => $amounts->basic_premium,
//            'training_levy' => $amounts->training_levy,
//            'phcf' => $amounts->phcf,
//            'stamp_duty' => $amounts->stamp_duty
//        ];

        // check if there are any companions
//        if ($companions_no = $product_info->no_of_travel_companions) {
//            for ($i = 1; $i <= $companions_no; $i++) {
//                $companion_data[] = [
//                    'companion_name' => $product_info->{'companion_name' . $i},
//                ];
//            }
//            $quote['companions'] = $companion_data;
//        }

    }

    /**
     * Important fields to show in quote total
     * @return array
     */
    public function quoteFields()
    {
        return [];
    }

    /**
     * @return QuotesInterface
     */
    public function previewQuote()
    {
        $this->calculateValues();
        return $this;
    }


    public function getRate($plan, $days)
    {
        $plan_id = $this->_pricing_model->find(['plan' => $plan])->id;

        if (($plan != "Haj and Umrah Plan Basic") && ($plan != "Haj and Umrah Plan Plus") && ($plan != "Haj and Umrah Plan Extra")) {
            $rate = $this->model->table('travel_pricing')
                ->join("travel_plan_details tpd", TABLE_PREFIX . "travel_pricing.id = tpd.plan_id", "LEFT")
                ->where('days', '=', $days)
                ->where('plan_id', '=', $plan_id)
                ->get();
            $premium_rate = $rate[0]->premium;
        } else {
            $rates = $this->model->table('travel_pricing')
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

    public function getCover($plan)
    {
        switch ($plan) {
            case 'europe plus plan':
                return 'Europe Plus Plan';
            case   'africa basic plan':
                return 'Africa Basic Plan';
            case 'world wide basic plan':
                return 'World Wide Basic Plan';
            case 'world wide plus plan':
                return 'World Wide Plus Plan';
            case 'world wide extra':
                return 'World Wide Extra';
            case 'haj and umra basic plan':
                return 'Haj and Umra Basic Plan';
            case 'haj and umra plus plan':
                return 'Haj and Umra Plus Plan';
            case 'haj and umra extra plan':
                return 'Haj and Umra Extra Plan';
            default:
                return 'Unknown';
        }
    }

    public function getDependants($other_covers)
    {
        $companions = json_decode($other_covers->details);
        $comps = [];
        foreach ($companions as $key => $companion) {
            $comps[$key] = $companion;
        }
        return $comps;
    }

    public function calculateAmounts($cover_plan, $travel_days)
    {
        // get the rate
        $cover = $this->getCover($cover_plan);
        $premium_rate = $this->getRate($cover, $travel_days);

        $levy = $this->medical->determineRate($premium_rate, 'Training Levy', 'Travel');
        $phcf = $this->medical->determineRate($premium_rate, 'P.H.C.F Fund', 'Travel');
        $stamp_duty = $this->medical->determineRate($premium_rate, 'Stamp Duty', 'Travel');

        $amounts = [
            'insurer_id' => $this->insurer_id,
            'basic_premium' => $premium_rate * 90,
            'training_levy' => $levy,
            'phcf' => $phcf,
            'stamp_duty' => $stamp_duty
        ];

        $companions = $this->entity->getEntityDataByCustomerAndEntityId(Session::get('customer_id'), $this->entity_id);
        if (count($companions)) {
            $entity_data_ids = [];
            foreach ($companions as $companion) {
                // get the entity data ids
                $entity_data_ids[] = $companion->id;
            }
        }

        return [
            'amounts' => $amounts,
            'entity_data_ids' => $entity_data_ids
        ];
    }
}