<?php
namespace Jenga\MyProject\Reports\Views;

use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
/**
 * Created by PhpStorm.
 * User: developer1
 * Date: 6/7/17
 * Time: 3:48 PM
 */
class ReportsView extends View
{
    public function generateReports(){
        $controls = [
            'Report Type' => ['select', 'report_type', '', [
                'Quotes Performance' => 'Quotes Assigned Performance (By Agent and Direct); Pending, Rejected, Accepted, New, Agent Attached & Complete Quotes',
                'Policies Performance' => 'Policies Performance (Active & Expired)',
                'Claims Performance' => 'Claims Performance (Open, Closed & Cancelled)'
            ]],
            'From Date' => ['date', 'from_date', ''],
            'To Date' => ['date', 'to_date', ''],
            '{submit}' => ['submit', 'btnsubmit', 'Export to Excel']
        ];

        $report_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/reports/export',
            'controls' => $controls,
            'validation' => [
                'report_type' => ['required' => 'Please select a Report Type first'],
                'from_date' => ['required' => 'Choose From date'],
                'to_date' => ['required' => 'Choose To date']
            ]
        ];

        $form = Generate::Form('quoteform', $report_schematic);

        $report_form = $form->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'reports' . DS . 'views' . DS . 'panels' . DS . 'excel-reports-template.php', TRUE);

        $this->set('excel_reports', $report_form);
        $this->setViewPanel('excel-reports');
    }
}