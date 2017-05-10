<?php

namespace Jenga\MyProject\Quotes\Controllers;

use function DI\object;
use Dompdf\Dompdf;
use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Html\Excel;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Accident\Repositories\AccidentQuotation;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Domestic\Repositories\DomesticQuotation;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Motor\Repositories\MotorQuotation;
use Jenga\MyProject\Quotes\Library\Companies\AIGQuotes;
use Jenga\MyProject\Quotes\Library\Companies\JubileeQuotes;
use Jenga\MyProject\Quotes\Library\Quotes;
use Jenga\MyProject\Quotes\Models\QuotesModel;
use Jenga\MyProject\Quotes\Views\QuotesView;
use Jenga\MyProject\Services\Charts;
use Jenga\MyProject\Users\Controllers\UsersController;
use Symfony\Component\Debug\DebugClassLoader;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Class QuotesController
 * @property-read QuotesView $view
 * @property-read QuotesModel $model
 * @package Jenga\MyProject\Quotes\Controllers
 */
Class QuotesController extends Controller
{
    /**
     * @var UsersController
     */
    private $userCtrl;
    /**
     * @var CustomersController
     */
    private $customerCtrl;

    public $statuslist = [
        'new' => 'New',
        'agent_attached' => 'Agent Attached',
        'pending' => 'Response Pending',
        'policy_pending' => 'Policy Pending',
        'policy_created' => 'Complete',
        'rejected' => 'Rejected'
    ];

    public function index()
    {
        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {
            $action = 'showQuotes';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function acceptQuote($link)
    {
        $the_quote = $this->getQuoteById(Help::decrypt($link));
        if (empty($the_quote)) {
            Redirect::to('/')->withNotice('Quote was not found', 'danger');
            exit;
        }
        $data['customer'] = (object)$this->getCustomerDataArray($the_quote->customers_id);
        $data['quote'] = $the_quote;
        $data['product'] = json_decode($the_quote->product_info);
        $ent = json_decode($the_quote->customer_entity_data_id);
        foreach ($ent as $en) {
            $x = Elements::call('Entities/EntitiesController')->getEntityDataByfinder($en);
            $data['entities'][] = json_decode($x->entity_values);
        }
        $data['product_info'] = Elements::call('Products/ProductsController')->getProduct($the_quote->products_id, 'stdClass');
        $data['quotation'] = json_decode($the_quote->amount);
        $this->view->createQuotePreview($data, true);
    }

    public function getLeads()
    {
        $leads = $this->getTheLeads($this->statuslist);

        $this->set('source', $leads);
        $this->set('count', count($leads));

        $this->view->showLeads();
    }

    public function add()
    {
        if (isset($_GET['policy']) && $_GET['policy'] == true) {
            Session::set('policy', $_GET['policy']);
        } else {
            Session::delete('policy');
        }
        //check for customer
        if (!is_null(Input::get('id'))) {
            $customer = Elements::call('Customers/CustomersController')->getCustomerById(Input::get('id'), 'raw');
        }

        //get agents
        $agents = Elements::call('Agents/AgentsController')->retrieveAgents();
        foreach ($agents as $agent) {
            $agentlist[$agent->id] = $agent->names;
        }

        //get products
        $products = Elements::call('Products/ProductsController')->getProduct();
        foreach ($products as $product) {
            $productlist[$product->id] = $product->name;
        }

        //get insurers
        $insurers = Elements::call('Insurers/InsurersController')->getInsurer();
        foreach ($insurers as $insurer) {
            $insurerlist[$insurer->id] = $insurer->name;
        }

        $this->view->addQuote($agentlist, $productlist, $insurerlist, $customer);
    }

    /**
     * Get the record to be edited
     */
    public function edit()
    {
        $quote_id = Input::get('id');
        $quote = $this->model->where('id', $quote_id)->first();
        $customer = $this->getCustomerDataArray($quote->customers_id);

        if (empty($quote)) {
            Redirect::withNotice('The quotation has not been found')
                ->to(Url::route('/admin/{element}/{action}', ['element' => 'quotes']));
        }
        $product_id = $quote->products_id;
        $alias = null;
        switch ($product_id) {
            case 1:
                $alias = 'motor';
                $_element = Elements::call('Motor/MotorController');
                break;
            case 5:
                $alias = 'accident';
                $_element = Elements::call('Accident/AccidentController');
                break;
            case 7:
                $alias = 'travel';
                $_element = Elements::call('Travel/TravelController');
                Session::set('quote_id', $quote_id);

                $this->view->set('entity_data', $this->getEntityDataFromQuote($quote));
                $this->view->set('product_info', $quote->product_info);
                $this->view->loadTabs($_element->getSchematic(), $customer, $alias);
                return;
            case 8:
                $alias = 'domestic';
                $_element = Elements::call('Domestic/DomesticController');
                break;
            case 9:
                $alias = 'medical';
                $_element = Elements::call('Medical/MedicalController');
                Session::set('quote_id', $quote_id);

                $this->view->set('entity_data', $this->getEntityDataFromQuote($quote));
                $this->view->set('product_info', $quote->product_info);
                $this->view->loadTabs($_element->getSchematic(), $customer, $alias);
                return;
        }
        $arr = $this->explodeQuote($quote);
        $arr['quote_id'] = $quote->id;
        $arr['product'] = $alias;
        $this->view->quoteEditWizard($_element->getSchematic(), $arr);
    }

    public function getEntityDataFromQuote($quote){
        $entity_data_ids = json_decode($quote->customer_entity_data_id, true);
        $entity_data = array();

        if(count($entity_data_ids)){
            foreach($entity_data_ids as $entity_data_id){
                $entity = $this->model->table('customer_entity_data')->where('id', $entity_data_id)->first();
                $entity_data[] = json_decode($entity->entity_values, true);
            }
        }

        return $entity_data;
    }

    private function checkIfPolicyDone($quote_id)
    {
        $_p = Elements::call('Policies/PoliciesController');
        $get = $_p->model->where('customer_quotes_id', $quote_id)->first();
        return !empty($get);
    }

    private function explodeQuote($quote)
    {
        if ($this->checkIfPolicyDone($quote->id)) {
            return false; //throttle updates
        }
        $build = [];
        $product_info = json_decode($quote->product_info);
        $_scope = json_decode($quote->customer_entity_data_id);
        //$info = $this->setUpSpecialParams($_customer);
        $_entities = Elements::call('Entities/EntitiesController');
        $ents = [];
        foreach ($_scope as $key => $item) {
            $raw = $_entities->getEntityDataByfinder($item);
            $ents[] = json_decode($raw->entity_values);
        }
        $main_entity = $ents[0];
        $others = array_except($ents, 0);
        $bake = $this->mixin($others);
        //  $the_arrays = $info;
        $build = array_merge($build, $this->getCustomerDataArray($quote->customers_id));
        $build = array_merge($build, get_object_vars($main_entity));
        foreach ($bake as $cake) {
            $build = array_merge($build, $cake);
        }
        $build = array_merge($build, get_object_vars($product_info));
        return $build;
    }

    private function mixin($mix)
    {
        $sharp = [];
        foreach ($mix as $l => $v) {
            foreach ($v as $i => $k) {
                $sharp[$l][$i . $l] = $k;
            }
        }
        return $sharp;
    }

    public function emailQuote()
    {
        $id = Input::get('id');
        $quote = $this->model->where('id', $id)->first();

        //get products
        $products = Elements::call('Products/ProductsController')->getProduct($quote->products_id);

        //get customer
        $customer = (object)$this->getCustomerDataArray($quote->customers_id);
        //get agent
        $agent = Elements::call('Agents/AgentsController')->retrieveAgents($customer->insurer_agents_id);
        //the email quote form
        $this->view->mailQuote($quote, $customer, $products, $agent);
    }

    public function markStatus()
    {

        $id = Input::get('id');
        $quote = $this->model->where('id', $id)->first();

        //get product
        $product = Elements::call('Products/ProductsController')->getProduct($quote->products_id);

        //process recommendations
        $amounts = json_decode($quote->amount, true);

        foreach ($amounts as $insurerid => $price) {

            //get insurers
            $insurer = Elements::call('Insurers/InsurersController')->getInsurer($insurerid);
            $pricing = number_format($price, 2);

            $offerslist[$id . ':' . $insurerid . ':' . $price] = $insurer['name'] . ' offer for ksh.' . $pricing;
        }

        $this->view->markForm($quote, $product, $offerslist);
    }

    public function acceptRejectQuote()
    {
        $status = "Accepted";
        if (Input::post('action') == 'No') {
            $status = "Declined";
        }
        $this->saveStatus(Input::post('quote'), Input::post('action'), $status);
        Redirect::withNotice('Your response was successfully recorded')
            ->to(Url::base());
    }

    public function saveStatus($id = null, $offer = null, $status = null, $redirect = false)
    {

        $this->view->disable();

        //quote id
        if (!is_null($id))
            $quote = $this->model->find($id);
        else
            $quote = $this->model->find(Input::get('id'));

        //quote offer
        if (!is_null($offer))
            $quote->acceptedoffer = $offer;
        else
            $quote->acceptedoffer = Input::post('offer');

        //quote status
        if (!is_null($status))
            $quote->status = $status;
        else
            $quote->status = Input::post('response');

        $quote->save();

        if ($quote->hasNoErrors()) {

            if (Input::post('redirect') == 'true' || $redirect == true) {

                Redirect::withNotice('The status has been changed')
                    ->to(Url::route('/admin/{element}/{action}/{id}', ['element' => 'quotes', 'action' => 'edit', 'id' => Input::get('id')]));
            } else {
                return TRUE;
            }
        }
    }

    public function createAttachment()
    {

        $this->view->disable();
        $quotefile = $this->generatePDFQuote(Input::post('id'), TRUE, TRUE);

        $this->view->createEmailAttachment($quotefile);
    }

    public function sendEmail()
    {
        $mail = new \PHPMailer();

        //Set who the message is to be sent from
        $mail->setFrom(Input::post('ownemail'), Input::post('replyname') . ' - ' . Input::post('owncompany'));

        //Set an alternative reply-to address
        $mail->addReplyTo(Input::post('replyto'), Input::post('replyname'));

        //Set who the message is to be sent to
        $mail->addAddress(Input::post('email'), Input::post('customer_name'));

        //Set the subject line
        $mail->Subject = Input::post('subject');

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML(Input::post('content'));

        //Replace the plain text body with one created manually
        //$mail->AltBody = Input::post('content');
        $path = Input::post('email_attach_filepath');
        if (!empty($path)) {
            $mail->addAttachment($path);
        }
        $this->view->disable();
        $mail->send();

        //change quotation status
        $status = $this->changeQuoteStatus(Input::post('id'), 'pending');

        if ($status) {
            Redirect::withNotice('The Quotation has been sent to <strong>' . Input::post('email') . '</strong>')
                ->to('/admin/quotes');
        } else {
            throw \Exception($this->model->getLastError());
            // throw App::exception($this->model->getLastError());
        }
    }

    /**
     * Updates the current quote status
     * @param type $id
     * @param type $status
     * @return boolean
     */
    public function changeQuoteStatus($id, $status)
    {

        $quote = $this->model->find($id);

        $quote->status = $status;
        $quote->save();

        if ($quote->hasNoErrors()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Creates the product form from the existing records and the respective javascript to populate the table
     * @param type $pid
     * @param type $formid
     * @param type $formvalues
     * @depends $this->edit()
     * @return array the rendered form and script
     */
    public function createProductFormForEdit($pid, $formid, $formvalues)
    {

        //process the form values to make the script
        $formfields = json_decode($formvalues, true);
        $script = '';
        foreach ($formfields as $key => $value) {

            $script .= "$('#" . $key . "').val('" . trim($value) . "');\n";
        }

        //get the form
        $form = Elements::call('Forms/FormsController')->processFormAttributes($formid, $pid);

        $productform['script'] = $script;
        $productform['form'] = $form;

        return $productform;
    }

    /**
     * Creates the entity form from the existing records and the respective javascript to populate the table
     *
     * @param type $entitydataid
     * @param type $formvalues
     * @return mixed
     */
    public function createEntityFormForEdit($entitydataid, $formvalues)
    {

        if (Sanitize::is_json($entitydataid)) {

            $entity['table'] = $this->createStoredEntitiesTable($entitydataid);
        } else {
            $script = '';
            //process the form values to make the script
            foreach ($formvalues as $key => $value) {
                $script .= "$('#" . $key . "').val('" . trim($value) . "');\n";
            }

            //get the form id from the entities table which is listed in the customer entities table
            $entity = Elements::call('Entities/EntitiesController');

            $entityid = $entity->getEntityDataById($entitydataid)->entities_id;
            $eform = $entity->getEntityById($entityid);

            //get the form
            $form = Elements::call('Forms/FormsController')->processFormAttributes($eform->forms_id, $entityid);

            $entityform['script'] = $script;
            $entityform['form'] = $form;
        }

        return $entityform;
    }

    /**
     * Porcesses the sent json ids and returns the rendered table
     *
     * @param type $jsonids
     * @return string
     */
    public function createStoredEntitiesTable($jsonids)
    {

        $ids = json_decode($jsonids, true);

        $entity = Elements::call('Entities/EntitiesController');
        $enttable = '';
        foreach ($ids as $id) {
            $enttable .= $entity->returnEntityEntries($id);
        }

        return $enttable;
    }

    /**
     * Creates the pricing form from the existing records to populate the table
     * @param type $amounts
     * @param type $recommendation
     * @return string
     */
    public function createPricingFormForEdit($amounts, $recommendation)
    {
        $pricing = '';
        foreach ($amounts as $insurerid => $price) {
            $pricing .= $this->createInsurerPriceTable($insurerid, $price, $recommendation);
        }

        return $pricing;
    }

    private function _processQuotes($dbquotes)
    {
//        print_r($dbquotes);
        foreach ($dbquotes as $quote) {

            $quote->date = date('d M Y', $quote->datetime);

            //get customer
            $customer = Elements::call('Customers/CustomersController')->find($quote->customers_id);
            $quote->customer = (is_null($customer->name) ? 'Customer not found' : $customer->name);

            //get product
            $product = Elements::call('Products/ProductsController')->getProduct($quote->products_id);
            $quote->product = $product['name'];

            //get policy
            $policy = Elements::call('Policies/PoliciesController')->getPolicyByQuote($quote->id);

            //get agents
            $agent = Elements::call('Agents/AgentsController')->retrieveAgents($customer->insurer_agents_id);
            $quote->agent = $agent->names;
            $quote->premium = number_format(json_decode($quote->amount)->total, 2);
            //get entities
            if (Sanitize::is_json($quote->customer_entity_data_id) != false) {
                $eids = json_decode($quote->customer_entity_data_id, true);

                foreach ($eids as $eid) {
                    $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($eid);
                }
            } else {
                $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($quote->customer_entity_data_id);
            }

            $first_entity = $entity[0];
            $first_entity_value = @array_keys($first_entity['entity'])[0];

            $quote->entity = $first_entity['entity'][$first_entity_value]
                . ' <br/> <span style="color:grey; font-size: 12px;">' . $first_entity['type'] . (count($entity) > 1 ? '(' . count($entity) . ')' : '') . '</span> ';

            $quote->actions = '<div class="row">'
                . '<div class="col-md-2">'
                . '<a target="_blank" href="' . SITE_PATH . '/quotes/previewquote/' . Help::encrypt($quote->id) . '/' . Help::encrypt('internal') . '" >'
                . '<img style="opacity: 0.5" ' . Notifications::tooltip('Click to preview quote') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/preview_icon.png" />'
                . '</a>'
                . '</div>'
                . '<div class="col-md-2">'
                . '<a data-toggle="modal" data-target="#emailmodal" href="' . SITE_PATH . '/ajax/admin/quotes/emailquote/' . $quote->id . '" >'
                . '<img style="opacity: 0.5" ' . Notifications::tooltip('Click to email quote') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/email-icon.png" />'
                . '</a>'
                . '</div>'
                . '<div class="col-md-2">'
                . '<a target="_blank" href="' . SITE_PATH . '/ajax/admin/quotes/pdfquote/' . $quote->id . '" >'
                . '<img style="opacity: 0.5" ' . Notifications::tooltip('Click to create quote PDF') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/pdf_icon.png" />'
                . '</a>'
                . '</div>'
                . '</div>';

            //process status
            $status = [
                'new' => 'New',
                'pending' => 'Response Pending',
                'policy_pending' => 'Policy Pending',
                'policy_created' => 'Complete',
                'rejected' => 'Rejected'
            ];
            $quote->status = $status[$quote->status];

            $quotes[] = $quote;
        }

        return $quotes;
    }

    private function _processQuotesForExport($dbquotes)
    {

        $rawquotes = $this->_processQuotes($dbquotes);
        $prices = '';
        foreach ($rawquotes as $quote) {

            $result = $this->model->createEmpty();

            //process amount
            $amounts = json_decode($quote->amount, true);

            foreach ($amounts as $insurerid => $price) {

                $insurer = Elements::call('Insurers/InsurersController')->getInsurer($insurerid);
                $prices .= $insurer['name'] . ' ksh ' . number_format($price, 2) . ', ';
            }

            $prices = rtrim($prices, ', ');

            //this follows the order of columns on the model side
            $result->quoteno = $quote->id;
            $result->dategen = $quote->date;
            $result->customer = $quote->customer;
            $result->product = $quote->product;
            $result->entity = strip_tags($quote->entity);
            $result->agent = $quote->agent;
            $result->status = $quote->status;
            $result->amount = $prices;

            $quotes[] = $result;
            unset($prices);
        }

        return $quotes;
    }

    public function showQuotes()
    {

        $dbquotes = $this->model->getQuotes();
//        print_r($dbquotes);exit;
        $quotes = $this->_processQuotes($dbquotes);
        $this->view->set('count', count($quotes));
        $this->view->set('source', $quotes);
        $this->view->generateTable();
    }

    public function showArchivedQuotes()
    {

        $dbquotes = $this->model->getArchivedQuotes();
        $quotes = $this->_processQuotes($dbquotes);

        $this->view->set('count', count($quotes));
        $this->view->set('source', $quotes);

        //create the return url from the Navigation element
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'quotes']);
        $alerts = Notifications::Alert(' This section only displays <strong>Completed or Rejected</strong> quotes '
            . '<a data-dismiss="alert" class="close" href="' . Url::base() . $url . '">Ã—</a>', 'info', TRUE, TRUE);

        $this->view->set('alerts', $alerts);

        $this->view->generateTable();
    }

    /**
     * Generates the active quote tables for the dashboard section
     */
    public function getActiveQuotes()
    {
        $dbquotes = $this->model->select(TABLE_PREFIX . 'customer_quotes.*, '
            . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents, '
            . TABLE_PREFIX . 'customer_quotes.*')
            ->join('customers', TABLE_PREFIX .
                "customers.id = " . TABLE_PREFIX . "customer_quotes.customers_id")
            ->where('status', 'IN', ['new', 'pending']);

        if (Session::has('agentsid'))
            $dbquotes = $dbquotes->where('insurer_agents_id', Session::get('agentsid'));

        $quotes = $this->_processQuotes($dbquotes->show());

        $this->view->set('count', count($quotes));
        $this->view->set('source', $quotes);

        $this->view->generateMiniTable();
    }

    public function getQuotesByCustomer($id)
    {

        $dbquotes = $this->model->where('customers_id', '=', $id)->show();

        if (count($dbquotes) >= 1) {
            return $this->_processQuotes($dbquotes);
        } else {
            return NULL;
        }
    }

    public function getQuoteById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function analyseProducts($settings = [])
    {

        $series = $this->model->getProductAnalysis();

        //build the chart
        $chart = new Charts($settings);

        $chart->title('% Quotes by Products');

        $chart->setup([
            'plotBackgroundColor' => 'null',
            'plotBorderWidth' => 'null',
            'plotShadow' => 'false'
        ]);

        $chart->tooltip([
            "pointFormat" => "'{series.name}: <b>{point.percentage:.1f}%</b>'"
        ]);

        $chart->plotOptions([
            $settings['type'] => [
                'allowPointSelect' => 'true',
                'cursor' => "'pointer'",
                'dataLabels' => [
                    'enabled' => 'false'
                ],
                'showInLegend' => 'true'
            ]
        ]);

        $seriesmethod = $settings['type'] . 'Series';
        $chart->$seriesmethod($series);

        $pie = $chart->build();

        $this->view->set('pie', $pie);
        $this->view->setViewPanel('products-share');
    }

    public function analyseQuotesByMonth($settings)
    {

        $quotes = $this->model->getQuotesByMonth();
        $months = array_keys($quotes);

        $qchart = new Charts($settings);

        $qchart->setup(['type' => $settings['type']]);
        $qchart->title('Generated Quotes By Month');

        $qchart->xAxis([
            'categories' => $months
        ]);

        $qchart->yAxis([
            'allowDecimals' => 'false',
            'min' => 0,
            'title' => [
                'text' => "''"
            ],
            'plotLines' => [
                'value' => '0',
                'width' => '1',
                'color' => "'#808080'"
            ]
        ]);

        $qchart->tooltip([
            'valueSuffix' => "' Quote(s)'"
        ]);

        $qchart->legend([
            'layout' => "'vertical'",
            'align' => "'right'",
            'verticalAlign' => "'middle'",
            'floating' => 'true',
            'borderWidth' => '0'
        ]);

        $seriesmethod = $settings['type'] . 'Series';
        $qchart->$seriesmethod($quotes, ['Quotes']);

        $quoteschart = $qchart->build();

        $this->view->set('monthlyquotes', $quoteschart);
        $this->view->setViewPanel('monthly-quotes');
    }

    public function unpaidQuotes()
    {

        $unpaid = $this->model->getUnpaid();

        $this->view->set('unpaid_count', count($unpaid));
        $this->view->set('unpaid', $unpaid);

        $this->view->generateUnpaidTable();
        $this->view->setViewPanel('unpaid-quotes');
    }

    public function incompleteQuotes()
    {

        $incomplete = $this->model->getIncomplete();

        $this->view->set('count', $incomplete['count']);
        $this->view->set('source', $incomplete['data']);

        $this->view->generateIncompleteTable();
        $this->view->setViewPanel('incomplete-quotes');
    }

    public function previewQuote($id)
    {
        $the_quote = $this->getQuoteById(Help::decrypt($id));
        if (empty($the_quote)) {
            Redirect::to('/admin/quotes/add')->withNotice('Quote was not found', 'danger');
            exit;
        }
        $data['customer'] = (object)$this->getCustomerDataArray($the_quote->customers_id);
        $data['quote'] = $the_quote;
        $data['product'] = json_decode($the_quote->product_info);
        $ent = json_decode($the_quote->customer_entity_data_id);
        foreach ($ent as $en) {
            $x = Elements::call('Entities/EntitiesController')->getEntityDataByfinder($en);
            $data['entities'][] = json_decode($x->entity_values);
        }
        $data['product_info'] = Elements::call('Products/ProductsController')->getProduct($the_quote->products_id, 'stdClass');
        $data['quotation'] = json_decode($the_quote->amount);
        $this->view->createQuotePreview($data);
    }

    /**
     * Checks and loads existing pdf quote or generates new one
     * @param type $id
     */
    public function pdfQuote($id)
    {
//        $this->view->disable();
//        $url = Url::base() . '/quotes/mypreviewquote/' .urlencode(Help::encrypt($id));// . '/' . Help::encrypt('internal');
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        $result = curl_exec($curl);
//        curl_close($curl);
//        $dompdf = new Dompdf();
//        $dompdf->loadHtml($result);
//
//        $dompdf->setPaper('A4', 'landscape');
//        $dompdf->render();
//        $dompdf->stream();
        $this->generatePDFQuote($id);
    }

    public function generatePDFQuote($id, $savedoc = true, $return = false)
    {

        //get quote
        $quote = $this->model->find($id);
        $this->view->disable();
        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);
        $url = Url::base() . '/quotes/mypreviewquote/' . urlencode(Help::encrypt($id));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        // output the HTML content
        $pdf = new \mPDF();

        $pdf->useSubstitutions = false; // optional - just as an example
        $pdf->SetHeader("\n\n" . 'Page {PAGENO}');  // optional - just as an example
        $pdf->CSSselectMedia = 'mpdf'; // assuming you used this in the document header
        $pdf->setBasePath($url);
        $pdf->writeHTML($result);

        //create and save document record
        if (!$return) {
            //Close and output PDF document
            $filename = 'quotation' . '_' . rand(0, 100) . '_' . date('d-m-Y', $quote->datetime) . '.pdf';
            $qname = PROJECT_PATH . DS . 'uploads' . DS . $filename;

            $pdf->Output($qname, 'F');
            $pdf->Output($qname, 'I');
        } else {

            //Close and output PDF document
            $filename = 'quotation' . '_' . $quote->id . '_' . date('d-m-Y', $quote->datetime) . '.pdf';
            $qname = PROJECT_PATH . DS . 'uploads' . DS . $filename;

            $pdf->Output($qname, 'F');
        }

        $filepath = 'project' . DS . 'uploads' . DS . $filename;
        $description = 'PDF document for Quote No.' . $quote->id;
        $doctype = 'quotes_pdf';

        if ($savedoc == true) {
            $newid = Elements::call('Documents/DocumentsController')->saveDoc($filename, $filepath, $doctype, $description);
            $this->model->saveQuoteDocuments($quote->id, $newid);
        }

        return $qname;
    }

    public function generateTCPDF($id, $savedoc = true, $return = false)
    {

        require_once ABSOLUTE_PATH . DS . 'project' . DS . 'tools' . DS . 'tcpdf' . DS . 'tcpdf.php';

        //get quote
        $quote = $this->model->find($id);

        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);

        // create new PDF document
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($owncompany->name);
        $pdf->SetTitle('Insurance Quotation No' . sprintf("%'.05d\n", $quote->id));
        $pdf->SetSubject('Insurance PDF Quotation');
        $pdf->SetKeywords('TCPDF, PDF, insurance, quotation');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 061', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('helvetica', '', 10);

        // add a page
        $pdf->AddPage();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Url::base() . '/ajax/quotes/previewquote/' . Help::encrypt($id) . '/' . Help::encrypt('internal'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();

        //creat and save document record
        if ($return == false) {

            //Close and output PDF document
            $filename = 'quotation' . '_' . rand(0, 100) . '_' . date('d-m-Y', $quote->datetime) . '.pdf';
            $qname = ABSOLUTE_PROJECT_PATH . DS . 'admin' . DS . 'quotes' . DS . 'documents' . DS . $filename;

            $pdf->Output($qname, 'FI');
        } elseif ($return == true) {

            //Close and output PDF document
            $filename = 'quotation' . '_' . $quote->id . '_' . date('d-m-Y', $quote->datetime) . '.pdf';
            $qname = ABSOLUTE_PROJECT_PATH . DS . 'admin' . DS . 'quotes' . DS . 'documents' . DS . $filename;

            $pdf->Output($qname, 'F');
        }

        $filepath = 'admin' . DS . 'quotes' . DS . 'documents' . DS . $filename;
        $description = 'PDF document for Quote No.' . $quote->id;
        $doctype = 'quotes_pdf';

        if ($savedoc == true) {
            $newid = Elements::call('Documents/DocumentsController')->saveDoc($filename, $filepath, $doctype, $description);
            $this->model->saveQuoteDocuments($quote->id, $newid);
        }

        return $qname;
    }

    public function search()
    {

        $dbquotes = $this->model->getQuotes();

        $params = $dbquotes['terms'];
        unset($dbquotes['terms']);

        $quotes = $this->_processQuotes($dbquotes);

        $search = array_values($quotes);
        $searchcount = count($search);

        //create the return url from the Navigation element
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'quotes']);
        $alerts = Notifications::Alert($searchcount . ' Search Results for ' . $params
            . '<a data-dismiss="alert" class="close" href="' . Url::base() . $url . '">Ã—</a>', 'info', TRUE, TRUE);

        $this->view->set('count', $searchcount);
        $this->view->set('alerts', $alerts);
        $this->view->set('source', $search);

        $this->view->generateTable();
    }

    public function export()
    {

        $dbquotes = $this->model->getQuotes();
        $quotes = $this->_processQuotesForExport($dbquotes);

        $columns = $this->model->returnColumns('values');

        $company = $this->model->table('own_company')->first();
        $doc = new Excel($company->name . ' Quotation Listing', Input::post('filename'));

        $doc->generateDoc($columns, $quotes, Input::post('format'));
    }

    public function printer()
    {

        $this->view->disable();

        $dbquotes = $this->model->getQuotes();
        $quotes = $this->_processQuotes($dbquotes);

        $this->view->set('count', count($quotes));
        $this->view->set('source', $quotes);

        HTML::head();
        $this->view->generateTable();

        HTML::printPage();
    }

    public function delete()
    {

        $this->view->disable();
        $ids = Input::post('ids');

        foreach ($ids as $id) {

            $this->model->connectPayments();
            $this->model->where('id', '=', $id)->delete();
        }

        $url = Url::route('/admin/quotes/{action}/{id}');
        Redirect::withNotice('The quotation(s) have been deleted', 'success')
            ->to($url);
    }

    /**
     * Deletes quote document id
     * @param type $quoteid
     * @param type $id
     */
    public function deleteDoc($quoteid, $id)
    {

        //delete document from Documents element
        $doc = Elements::call('Documents/DocumentsController')->deleteDoc($id);

        if ($doc !== FALSE) {

            $del = $this->model->deleteQuoteDocuments($quoteid, $id);

            if ($del) {
                Redirect::withNotice('The linked quotation document has been deleted')
                    ->to(Url::route('/admin/{element}/{action}/{id}', ['element' => 'quotes', 'action' => 'edit', 'id' => $quoteid]));
            }
        }
    }

    public function getQuotes()
    {

        $this->view->disable();

        //get the customers quotes
        $id = Input::request('id');
        $quotes = $this->model->where('customers_id', '=', $id)->show();

        if (count($quotes) >= 1) {

            foreach ($quotes as $quote) {

                $list[] = ['id' => $quote->id,
                    'type' => $quote->quotetype,
                    'datetime' => $quote->datetime,
                    'data' => $quote->customer_data_id,
                    'amount' => $quote->amount];
            }

            $this->view->getQuoteList($list);
        } else {

            echo Notifications::Alert('No quotes found', 'error', true, true);
        }
    }

    public function getCustomer()
    {

        $this->view->disable();
        $name = Input::request('query');

        echo Elements::call('Customers/CustomersController')->getCustomerByName($name, 'select');
    }

    public function getCustomerDetails($id = null)
    {

        $this->view->disable();

        if (is_null($id))
            $id = Input::request('id');

        echo Elements::call('Customers/CustomersController')->getCustomerById($id);
    }

    /**
     * Create the insurer price table
     * so that the user can insert the prices
     * for each company included in the quotation
     *
     * @group InsurerPricing
     */
    public function createInsurerPriceTable($insurerid = null, $amount = null, $recommendation = null)
    {

        if (is_null($insurerid)) {

            $this->view->disable();
            $insurerid = Input::post('insid');
        }

        //get insurers
        $insurer = Elements::call('Insurers/InsurersController')->getInsurer($insurerid);

        if (Input::post('insid'))
            echo $this->view->insurerPrice($insurer['id'], $insurer['name'], 'ksh');
        else
            return $this->view->insurerPrice($insurer['id'], $insurer['name'], 'ksh', $amount, $recommendation);
    }

    /**
     * Saves the newly generated quotes
     */
    public function save()
    {

        $this->view->disable();

        if (Input::post('id') != '')
            $quote = $this->model->find(Input::post('id'));
        else
            $quote = $this->model;
        //compile customer info
        $cinfo['customer'] = Input::post('customer');
        $cinfo['email'] = Input::post('email');
        $cinfo['phone'] = Input::post('phone');


        //compile the product info
        $pid = Input::post('product');
        $pformkeys = json_decode(Input::post('index_' . $pid), TRUE);

        foreach ($pformkeys as $pkey) {
            $pinfo[$pkey] = Input::post($pkey);
        }

        //compile and save the entity info
        $eselect = Input::post('entities');
        $eid = Input::post('entityformid_' . $eselect);
        $eformkeys = json_decode(Input::post('index_' . $eid), TRUE);

        //save entity if new
        if (Input::post('newentity') == $eselect) {
            foreach ($eformkeys as $ekey) {
                $einfo[$ekey] = Input::post($ekey);
            }

            $entityid = Elements::call('Entities/EntitiesController')
                ->saveEntityDataRemotely(Input::post('customerid'), $eselect, json_encode($einfo), Input::post('data_id'));

            $entid = $entityid['last_altered_row'];
        } //combine all sent entities
        else {
            $entid = Input::post('entids');
        }

        $quote->customers_id = Input::post('customerid');
        $quote->products_id = Input::post('product');
        $quote->insurer_agents_id = Input::post('agent');
        $quote->datetime = strtotime(Input::post('dategen'));
        $quote->customer_info = json_encode($cinfo);
        $quote->product_info = json_encode($pinfo);
        $quote->customer_entity_data_id = is_array($entid) ? json_encode($entid) : json_encode([$entid]);


        //compile the pricing info
        $insurers = Input::post('insurers');
        if (count($insurers)) {
            foreach ($insurers as $insurerid) {
                $amount[$insurerid] = Input::post('price_' . $insurerid);
            }
        }

        //$quote->amount = json_encode($amount);
        $quote->amount = Input::post('my_total_see');
        $quote->recommendation = Input::post('recommendation');
        $quote->status = Input::post('status');
        $save = $quote->save();

        if (!array_key_exists('ERROR', $save)) {

            //$url = Url::route('/admin/quotes/{action}/{id}');
            if (Input::post('request_type') == '__ajax') {
                $return = [
                    'success' => true,
                    'quote_id' => $quote->last_altered_row
                ];
                echo json_encode($return);
            } else {
                if (!Input::has('id')) {
                    Redirect::withNotice('The quote has been added', 'success')
                        ->to('/admin/quotes');
                } else {
                    Redirect::withNotice('The quote edit has been saved', 'success')
                        ->to('/admin/quotes');
                }
            }
        } else {
            if (Input::post('request_type') == '__ajax') {
                $return = ['success' => false];
                echo json_encode($return);
            }
        }
    }

    /**
     * Save quote remotely
     * @param array $ids
     * @param array $customer_info
     * @param array $product_info
     * @param array $amount
     * @param null $entid
     * @param null $quote_id
     * @return mixed
     */
    public function saveQuoteRemotely($ids = [], $customer_info = [], $product_info = [], $amount = [], $entid = null, $quote_id = null)
    {
        $this->userCtrl = Elements::call('Users/UsersController');
        $quote = $this->model;
        if (!empty($quote_id))
//            $quote->id = $quote_id;
            $quote = $this->model->find($quote_id);
        $quote->customers_id = $ids['customer_id'];
        $quote->products_id = $ids['product_id'];
        $quote->datetime = time();

        $quote->customer_info = $customer_info;
        $quote->product_info = (count($product_info)) ? json_encode($product_info) : 0;

        if (!empty($entid))
            $quote->customer_entity_data_id = is_array($entid) ? json_encode($entid) : json_encode([$entid]);
        else
            $quote->customer_entity_data_id = 0;

        $quote->amount = (count($amount)) ? json_encode($amount) : json_encode(0);
        $quote->status = 'new';
        $quote->save();
        if ($quote->hasNoErrors()) {
            return $quote->last_altered_row;
        }
    }

    public function assignAgent($quote_no)
    {
        $agents = Elements::call('Agents/AgentsController')->getAllAgents();
        $all_agents = [];
        if (count($agents)) {
            foreach ($agents as $agent) {
                $all_agents[$agent->id] = $agent->names;
            }
        }

        $this->view->loadAgentAssignment($all_agents, $quote_no);
    }

    public function attachAgent()
    {
        $quote_no = Help::decrypt(Input::post('quote_no'));

        // change the status of the quote
        $this->model->changeQuoteStatus($quote_no);

        // get the customer id from quote no
        $customer_id = $this->model->find($quote_no)->customers_id;

        // attach the selected agent to the customer
        Elements::call('Customers/CustomersController')->attachAgentToCustomer($customer_id, Input::post('agent'));

        Session::flash('status', 'agent_attached');
        Session::flash('quote_id', $quote_no);
        Redirect::to(Input::post('destination'))->withNotice('Agent has been attached');
    }

    public function getTheLeads()
    {
        $statuses = $this->statuslist;
        $table_one = TABLE_PREFIX . $this->model->table;

        $leads = $this->model->select($table_one . '.id as quote_no, c.name, c.insurer_agents_id,
        ' . $table_one . '.customers_id,
        ' . $table_one . '.status,
        ' . $table_one . '.datetime,
        ' . $table_one . '.products_id')
            ->join('customers c', $table_one . '.customers_id = c.id', 'LEFT')
            ->where('source', 'External');

        if (Session::has('agentsid'))
            $leads = $leads->where('insurer_agents_id', Session::get('agentsid'));

        $leads = $leads->where('status', 'new')
            ->orWhere('status', 'agent_attached');

        $leads = $leads->get();

        if (count($leads)) {
            foreach ($leads as $lead) {

                $lead->datetime = date('Y-M-d', $lead->datetime);
                $lead->name = '<a href="' . Url::base() . '/admin/customers/show/' . $lead->customers_id . '">' . $lead->name .
                    (!empty($lead->insurer_agents_id) ? ' (Existing)' : ' (New)')
                    . '</a>';
                $product = Elements::call('Products/ProductsController')
                    ->getProduct($lead->products_id);
                $lead->products_id = $product['name'];

                if ($lead->status == 'new') {
                    $lead->actions = '<div class="row">'
                        . '<div class="col-md-2">'
                        . '<a data-toggle="modal" data-target="#assign-agent-modal" href="' . Url::link('/admin/leads/assignAgent/' . Help::encrypt($lead->quote_no)) . '">'
                        . '<img style="opacity: 0.5" ' . Notifications::tooltip('Click to Assign Agent') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/preview_icon.png" />'
                        . '</a>'
                        . '</div>';
                } else {
                    $lead->actions = '<div class="row">'
                        . '<div class="col-md-2">'
                        . '<a id="quo_' . $lead->quote_no . '" data-toggle="modal" data-target="#addtaskmodal" href="' . Url::link('/admin/leads/createTask/' . Help::encrypt($lead->insurer_agents_id)) . '">'
                        . '<img style="opacity: 0.5" ' . Notifications::tooltip('Click to Create Task') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/preview_icon.png" />'
                        . '</a>'
                        . '</div>';
                }
                $lead->status = $statuses[$lead->status];

                $customer_leads[] = $lead;
            }
        }
        return $customer_leads;
    }

    public function createTaskForAgent($agent_id)
    {
        $this->view->disable();
        $agent_id = Help::decrypt($agent_id);

        //get agents
        $agents = Elements::call('Agents/AgentsController')->retrieveAgents();

        foreach ($agents as $agent) {
            $agentslist[$agent->id] = $agent->names;
        }

        // load the modal remotely
        Elements::call('Tasks/TasksController')->view->addForm($agentslist, $agent_id, true);
    }

    private function formatCustomerObject($_customer)
    {
        $info = get_object_vars($_customer);
        $product_datas = json_decode($info['additional_info']);
        unset($info['additional_info']);
        $info['dob'] = date('Y-m-d', $info['date_of_birth']);
        $info['mobile'] = $info['mobile_no'];
        $info['address'] = $info['postal_address'];
        $info['code'] = $info['postal_code'];
        $info['surname'] = substr($info['name'], 0, strpos($info['name'], ' '));
        $info['names'] = substr($info['name'], 1 + strpos($info['name'], ' '));
        $the_arrays = $info;
        foreach ($product_datas as $for_product) {
            $the_arrays = array_merge($the_arrays, get_object_vars($for_product));
        }
        return $the_arrays;
    }

    private function getCustomerDataArray($customer_id = null)
    {
        if (empty($customer_id)) {
            if (empty($customer_id = Input::post('customer'))) {
                return [];
            }
        }
        $_customer = Elements::call('Customers/CustomersController')->getCustomerById($customer_id, false);
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
        return $the_arrays;
    }

    public function internalQuote($element)
    {
        $customer = $this->getCustomerDataArray();

        // delete any previous sessions
        if (Session::has('customer_id') || Session::has('quote_id')) {
            Session::delete('customer_id');
            Session::delete('quote_id');
        }
        switch ($element) {
            case 'motor':
                $_element = Elements::call('Motor/MotorController');
                break;
            case 'accident':
                $_element = Elements::call('Accident/AccidentController');
                break;
            case 'domestic':
                $_element = Elements::call('Domestic/DomesticController');
                break;
            case 'travel':
                $_element = Elements::call('Travel/TravelController');
                $this->view->loadTabs($_element->getSchematic(), $customer, $element);
                return;
            case 'medical':
                $_element = Elements::call('Medical/MedicalController');
                $this->view->loadTabs($_element->getSchematic(), $customer, $element);
                return;
            default:
                return 'Unknown element';
        }
        $this->view->quoteWizard($_element->getSchematic(), $customer);
    }

    /**
     * @param CustomersController $_customer
     * @param EntitiesController $_entity
     * @param $element
     */
    public function saveInternalQuote(CustomersController $_customer, EntitiesController $_entity, $element)
    {
        $this->view->disable();
        $quote_id = Input::post('quote_id');
        $editing = !(empty($quote_id));
        switch ($element) {
            case 'motor':
                $_element = Elements::call('Motor/MotorController');
                $alias = 'vehicle';
                $product_id = 1;
                break;
            case 'domestic':
                $_element = Elements::call('Domestic/DomesticController');
                $alias = 'private_property';
                $product_id = 8;
                break;
            case 'accident':
                $_element = Elements::call('Accident/AccidentController');
                $alias = 'person';
                $product_id = 5;
                break;
            default:
                return 'Unknown element';
        }
        $step1 = $_element->getInputForStep(1);
        $step2 = $_element->getInputForStep(2);
        $step3 = $_element->getInputForStep(3);
        if ($editing) {
            $this->clearTrash();
        }
        $customer_id = $_customer->saveCustomer($element, $step1, true);
        $entity_id = $_entity->getEntityIdByAlias($alias)->id;
        $car_details = json_encode($step2);
        $saved = $_entity->saveEntityDataRemotely($customer_id, $entity_id, $car_details, null, $product_id);
        $ids['customer_id'] = $customer_id;
        $ids['product_id'] = $product_id;
        $entities = $this->checkForOtherCovers($element, $customer_id, $product_id);
        $customer_info = json_encode(get_object_vars($_customer->getCustomerById($customer_id, null)));
        array_unshift($entities, $saved);
        $quote = $this->saveQuoteRemotely($ids, $customer_info, $step3, null, $entities, $quote_id);
        echo json_encode(['quote' => $quote]);
    }

    private function clearTrash()
    {
        $quote_id = Input::post('quote_id');
        $_quote = $this->getQuoteById($quote_id);
        $_ids = json_decode($_quote->customer_entity_data_id);
        $__entity = Elements::call('Entities/EntitiesController');
        foreach ($_ids as $id) {
            $__entity->model->table('customer_entity_data')->where('id', $id)->delete();
        }
//        $this->model->where('id', '=', $quote_id)->delete();
    }

    private function checkForOtherCovers($element, $cust_id, $product)
    {
        switch ($element) {
            case 'accident':
                $alias = 'person';
                if (Input::post('other_covers') == 'yes')
                    $count = Input::post('howmany');
                break;
            case 'motor':
                $alias = 'vehicle';
                if (Input::post('somecovers') == 'yes')
                    $count = Input::post('othercovers');
                break;
            default:
                $count = null;
                break;
        }
        if (empty($count)) {
            return [];
        }
        $saved = [];
        $_entity = Elements::call('Entities/EntitiesController');
        for ($i = 1; $i <= $count; $i++) {
            $got = $this->__buildStack($i);
            $entity_id = $_entity->getEntityIdByAlias($alias)->id;
            $saved[] = $_entity->saveEntityDataRemotely($cust_id, $entity_id, json_encode($got), null, $product);
        }
        return $saved;
    }

    /**
     * Preview an internal quote
     * @param int $quote
     */
    public function internalQuoteView($quote)
    {
        $engine = null;
        $the_quote = $this->getQuoteById($quote);
        if (empty($the_quote)) {
            Redirect::to('/admin/quotes/add')->withNotice('Quote does not exist', 'danger');
            exit;
        }
        $jubilee = (new JubileeQuotes($the_quote))->calculate();
        $aig = (new AIGQuotes($the_quote))->calculate();
        $this->view->quotePreview([$jubilee, $aig]);
    }

    private function __buildStack($index)
    {
        $build = [];
        foreach (Input::post() as $key => $value) {
            if (ends_with($key, $index)) {
                $build[rtrim($key, $index)] = $value;
            }
        }
        return $build;
    }

    public function myQuotes($dash = false)
    {
        $this->setData();

        $customer = $this->customerCtrl->model->getCustomerById(Session::get('customer_id'));

        $dbquotes = $this->model->where('customers_id', $customer->id)->get();

        $quotes = $this->_processQuotes($dbquotes);
        $this->view->set('count', count($quotes));
        $this->view->set('source', $quotes);

        $this->view->generateTable($dash);
    }

    public function setData()
    {
        $this->userCtrl = Elements::call('Users/UsersController');
        $this->customerCtrl = Elements::call('Customers/CustomersController');
    }

    public function myDashQuotes()
    {
        $this->myQuotes(true);
    }
}