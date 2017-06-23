<?php
namespace Jenga\MyProject\Reports\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Html\Excel;
use Jenga\App\Request\Input;
use Jenga\MyProject\Claims\Controllers\ClaimsController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Policies\Controllers\PoliciesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Reports\Models\ReportsModel;
use Jenga\MyProject\Reports\Views\ReportsView;

/**
 * Created by PhpStorm.
 * User: developer1
 * Date: 6/7/17
 * Time: 3:44 PM
 * Class ReportsController
 * @property-read ReportsView $view
 * @property-read ReportsModel $model
 */
class ReportsController extends Controller
{
    private $element;
    private $company;
    /**
     * @var QuotesController
     */
    private $quotes_ctrl;
    /**
     * @var PoliciesController
     */
    private $policies_ctrl;

    /**
     * @var ClaimsController
     */
    private $claims_ctrl;


    public function _initData(){
        $this->company = $this->model->table('own_company')->first();
        $this->quotes_ctrl = Elements::call('Quotes/QuotesController');
        $this->policies_ctrl = Elements::call('Policies/PoliciesController');
        $this->claims_ctrl = Elements::call('Claims/ClaimsController');

    }

    public function index(){
        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {
            $action = 'show';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function show(){
        $this->view->generateReports();
    }

    /**
     * Exports the selected report to an excel file
     */
    public function export(){
        $this->_initData();
        $from_date = Input::post('from_date');
        $to_date = Input::post('to_date');

        switch (Input::post('report_type')){
            case 'Quotes Performance':
                $quotes = $this->quotes_ctrl->getProcessedQuotes();
                $title = ' Quotes Performance for ' . $from_date . ' to '. $to_date;

                $doc = new Excel($this->company->name . $title, Input::post('filename'));
                $doc->generateDoc([
                    'agent_name' => 'Agent Name',
                    'total_quotes' => 'Total Quotes',
                    'pending' => 'Pending',
                    'rejected' => 'Rejected',
                    'accepted' => 'Accepted',
                    'new' => 'New',
                    'agent_attached' => 'Agent Attached',
                    'policy_created' => 'Complete'
                ], $quotes, 'excel', $title);
                break;

            case 'Policies Performance':
                $policies = $this->policies_ctrl->getProcessedPolicies();
                $title = ' Policies Performance for ' . $from_date . ' to '. $to_date;

                $doc = new Excel($this->company->name . $title, Input::post('filename'));
                $doc->generateDoc([
                    'agent_name' => 'Agent Name',
                    'total_policies' => 'Total Policies',
                    'active' => 'Total Active',
                    'expired' => 'Total Expired'
                ], $policies, 'excel', $title);
                break;

            case 'Claims Performance':
                $claims = $this->claims_ctrl->getProcessedClaims();
                $title = ' Claims Performance for ' . $from_date . ' to '. $to_date;

                $doc = new Excel($this->company->name . $title, Input::post('filename'));
                $doc->generateDoc([
                    'agent_name' => 'Agent Name',
                    'total_quotes' => 'Total Quotes',
                    'new_claims' => 'New',
                    'open_claims' => 'Open',
                    'processing' => 'Processing',
                    'closed_claims' => 'Closed'
                ], $claims, 'excel', $title);
                break;
        }
    }
}
