<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Domestic\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Views\View;

/**
 * Class DomesticView
 */
class DomesticView extends View {

    private $data;

    public function wizard($data = null) {
        $this->data = $data;
        switch ($this->data->step) {
            case "1":
                $this->personalDetails();
                break;
            case "2":
                $this->properyDetails();
                break;
            case "3":
                $this->coverDetails();
                break;
            case "4":
                $this->showQuotation();
                break;
        }
    }

    public function coverDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/domestic/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [2,1,1,2,1,1,1,1,1,1,1,1,1,1],
            'controls' => [
                'Period of Insurance: From:*' => ['select', 'period_of_insurance_from', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Period of Insurance: To:*' => ['select', 'period_of_insurance_to', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{note1}' => ['note', 'note3', 'note3', 'If yes, state amount and number of months for which the cover is required?'],
                'Do you wish to insure rent receivable or rent payable?' =>
                    ['radios', 'insure_rent_receivable',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'Amount*' => ['text', 'cover_amount', '', ['class' => 'form-control']],
                'Number of Months*' => ['text', 'no_of_months', '', ['class' => 'form-control']],
                'Do you wish to enhance the value of your building automatically at the end of every insurance period?' =>
                    ['radios', 'enhance_value_auto',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'If so indicate the percentage increase required. Tick appropriate option below: ' => [
                    'checkboxes', 'percetage_increase', [
                        '0.05' => 'Five Percent',
                        '0.10' => 'Ten Percent',
                        '0.15' => 'Fifteen Percent',
                        '0.20' => 'Twenty Percent',
                    ]
                ],
                '{note2' => ['note', 'note2', 'note2', '<h4>Section A - The Buildings</h4>'],
                "The proposer's residence being a private dwelling house or flat and all the domestic offices, stables, garage,
                and outbuildings on the same premises and used in connection therewith and the walls, gates and fences around,
                and pertaining thereto, including Landlord's fixtures and fittings in the said building all situated above: 
                (<i style='font-weight: normal;'>All the said buildings are brick, stone or concrete built, with tile, concrete, or metal roof</i>)" =>
                ['text', 'dwelling_value', '', ['class' => 'form-control']],
                "Total Sum Insured on Buildings Kshs." => ['text', 'total_sum_insured', '', ['class' => 'form-control']],
                '{note3}' => ['note', 'note3', 'note3', '(<i style=\'font-weight: normal;\'><small>Note: The sum insured for the buildings should be the reinstatement value
                . i.e. the cost of rebuilding the house including walls and outbuildings, making allowance for Architects and Surveyors, 
                consultancy fees and cost of Debris removal</small></i>)'],
                '{note3}' => ['note', 'note3', 'note3', '<h4>Section B - Contents</h4>'],
                '{note4}' => ['note', 'note4', 'note4', '<b>Note 1:</b> The sum Insured should be the replacement value less depreciation, 
                wear and tear of the property.'],
                '{note5}' => ['note', 'note5', 'note5', '<b>Note 2:</b> No one article (furniture excepted) shall be deemed of greater value
                than 5% of the total sum Insured on the contents unless such article is specifically Insured.'],
                '{note6}' => ['note', 'note6', 'note6', '<b>Note 3:</b> The total value of platinum, gold and silver articles, jewelry will be
                deemed not to exceed one thirth of the total sum insured on the said contents unless specifically agreed upon with the insurer.
                If the said value exceeds this portion on the total value of such property should be specified.'],
                '{note7}' => ['note', 'note7', 'note7', '<b>Option 1:</b> On furniture, household goods and personal effects of every
                description the property of the proposer or any member of the proposer\'s family normally residing with the 
                proposer, and fixtures and fittings the proposer\'s own for which proposer is legally responsible, not being
                landlord\'s fixtures and fittings, in the building of the proposer\'s residense.'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >', ['class' => 'btn btn-success']]
            ],
        ];
        $form = Generate::Form('domestic_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function properyDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/domestic/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
            'controls' => [
                'Situation of Premises: Plot No: ' => ['text', 'plot_no', '', ['class' => 'form-control', 'required' => '']],
                'Of what material was the dwelling constructed?' =>['radios', 'construction_material',
                    [
                        'walls' => 'Walls',
                        'roof' => 'Roof'
                    ]
                ],
                'What is the height in storeys?' => ['text', 'height_in_storeys', '', ['class' => 'form-control']],
                'Is any business, profession or trade carried on in any of the section of the premises of which the dwelling forms a part?' =>
                ['radios', 'activities_carried',
                    [
                        'yes' => 'Yes',
                        'no' => 'No'
                    ]
                ],
                'If so, give particulars' => ['textarea', 'activities_particulars', '', ['class' => 'form-control']],
                '{note}' => ['note', 'note1', 'note1', 'Is the premises:'],
                'a) A private dwelling house?' =>
                    ['radios', 'private_dwelling',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'If not, please explain' => ['textarea', 'pdwelling_particulars', '', ['class' => 'form-control']],
                'b) A self contained flat with separate entrance exclusively under your control' =>
                    ['radios', 'self_contained_flat',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'Is the dwelling solely in your occupation? (Including your family and servants)' =>
                    ['radios', 'solely_occupation',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'Will the dwelling be left without an inhabitant for more than seven (7) days?' =>
                    ['radios', 'without_inhabitant_7days',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'If so, state the extent' => ['text', 'no_inhabitant7days_extent', '', ['class' => 'form-control']],
                'Will the dwelling be left without an inhabitant for more than thirty (30) days?' =>
                    ['radios', 'withoutinhabitant30days',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                'If so, state the extent ' => ['text', 'inhabitant30days_particulars', '', ['class' => 'form-control']],
                '{note2}' => ['note', 'note2', 'note2', '<b>Note</b> <p>Whenever the dwelling is to be left unoccupied
                for a period exceeding the above stated days please notify the company.</p>'],
                'Are the buildings in good state of repair and will they be so maintained?' =>
                    ['radios', 'good_state',
                        [
                            'yes' => 'Yes',
                            'no' => 'No'
                        ]
                    ],
                '{submit}' => ['submit', 'btnSubmitSpecial', 'Proceed to Cover Details >', ['class' => 'btn btn-success pull-right',]]
            ]
        ];
        $form = Generate::Form('domestic_property_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('property_details');
    }

    private function personalDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [2, 2, 2, 2, 2, 1],
            'action' => '/domestic/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Proposer Surname *' => ['text', 'surname', '', ['class' => 'form-control', 'required' => '']],
                'Other Names *' => ['text', 'names', '', ['class' => 'form-control', 'required' => '']],
                'PIN No. *' => ['text', 'pin', '', ['class' => 'form-control', 'required' => '']],
                'ID or Passport No. *' => ['text', 'idnumber', '', ['class' => 'form-control', 'required' => '']],
                'Telephone Contacts*' => ['text', 'telephone_contacts', '', ['class' => 'form-control', 'required' => '']],
                'Email*' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],
                'Postal Address: P.O Box*' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
                'Street *' => ['text', 'street', '', '', ['class' => 'form-control', 'required' => '']],
                'Town *:' => ['select', 'town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Property Details >', ['class' => 'btn btn-success pull-right']]
            ]
        ];
        $form = Generate::Form('domestic_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('personal_details');
    }

    private function showQuotation() {
        $this->set('data', $this->data);
        $this->setViewPanel('quotations');
    }

}
