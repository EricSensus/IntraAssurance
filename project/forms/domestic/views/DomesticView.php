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
use Jenga\App\Views\HTML;
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
                $this->propertyDetails();
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
            'map' => [2,1,1,2,1,1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2,2,1,2,1,2,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
            'controls' => [
                'Period of Insurance: From:*' => ['text', 'insurancefrom', '', ['class' => 'form-control', 'required' => '']],
                'Period of Insurance: To:*' => ['text', 'insuranceto', '', ['class' => 'form-control', 'required' => '']],
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
                ['text', 'dwelling_value', '', [
                    'class' => 'form-control', 'required' => ''
                ]],
                "Total Sum Insured on Buildings Kshs." => ['text', 'total_sum_insured', '', [
                    'class' => 'form-control', 'required' => ''
                ]],
                '{note3}' => ['note', 'note3', 'note3', '(<i style=\'font-weight: normal;\'><small>Note: The sum insured for the buildings should be the reinstatement value
                . i.e. the cost of rebuilding the house including walls and outbuildings, making allowance for Architects and Surveyors, 
                consultancy fees and cost of Debris removal</small></i>)'],
                '{note3}' => ['note', 'note3', 'note3', '<h4>Section B - Contents</h4>'],
                'Do you require Section B - Contents' => ['radios', 'sectionb', [
                    1 => 'Yes',
                    0 => 'No'
                ], 1],
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
                'Furniture' => ['textarea', 'furniture', '',['class' => 'form-control']],
                'Furniture Value (KES)' => ['text', 'furniturevalue', '', ['class' => 'form-control']],
                'Household Linen' => ['textarea', 'household_linen', '', ['class' => 'form-control']],
                'Household Linen Value (KES)' => ['text', 'householdlinen_value', '', ['class' => 'form-control']],
                'Curtlery, Glass, Crockery' => ['textarea', 'curtlery_and_others', '', ['class' => 'form-control']],
                'Curtlery, Glass, Crockery Value(KES)' => ['text', 'curtleryandothers_value', '', ['class' => 'form-control']],
                'Pictures and ornaments' => ['textarea', 'pictures_and_ornaments', '', ['class' => 'form-control']],
                'Pictures and ornaments Value(KES)' => ['text', 'picturesandornaments_value', '', ['class' => 'form-control']],
                'Wines and Spirits' => ['textarea', 'wines_and_spirits', '', ['class' => 'form-control']],
                'Wines and Spirits Value(KES)' => ['text', 'winesandspirits_value', '', ['class' => 'form-control']],
                'Personal Clothing' => ['textarea', 'personal_clothing', '', ['class' => 'form-control']],
                'Personal Clothing Value(KES)' => ['text', 'personalclothing_value', '', ['class' => 'form-control']],
                'Photographic Equipment' => ['textarea', 'photographic_equipment', '', ['class' => 'form-control']],
                'Photographic Equipment Value(KES)' => ['text', 'photographicequipment_value', '', ['class' => 'form-control']],
                'Jewelry and Valuables(attach jewelry report valuation for any single item valued in excess of KES. 50,000/-) Ksh.' => ['textarea', 'jewelry_and_valuables', '', ['class' => 'form-control']],
                'Jewelry and Valuables Value(KES)' => ['text', 'jewelryandvaluables_value', '', ['class' => 'form-control']],
                'Others Specify' => ['textarea', 'other_specifications', '', ['class' => 'form-control']],
                'Others Specify Value(KES)' => ['text', 'othersvalue', '', ['class' => 'form-control']],
                'Total Sum Insured' => ['text', 'total_sum_insured','', ['class' => 'form-control']],
                'Specify here any article of greater value than 5% of the total sum insured on the above contents' => [
                    'textarea', 'more_articles', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Items'
                    ]
                ],
                'Values(KES)' => [
                    'textarea', 'morearticles_values', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Values of the specified articles (Separate using commas(,))'
                    ]
                ],
                '{note8}' => ['note', 'note8', 'note8', '
                    <b>Option 2</b>
                    <p>Complete this option if you wish to insure each item individually</p>
                    <p>Proposer\'s Estimate of the value of individual items making up the contents</p>
                    <p><em>Do not include a value for any item which is to be insured under the "<b>ALL RISKS</b>"</em></p>
                    <input type="hidden" id="site_path" value="'.SITE_PATH.'"/>
                '],
                'Would you wish to insure each item individually?' => ['radios', 'insure_individually', [
                    1 => 'Yes', 0 => 'No'
                ]],
                'How many items would you wish to insure?' => ['select', 'how_many', '', $this->data->no_of_items, [
                    'class' => 'form-control', 'disabled' => ''
                ]],
                '{divnote1}' => ['note', 'divnote1', 'divnote1', '<div id="more_fields"><span class="hide">'.HTML::AddPreloader().'</span></div>'],
                'Please indicate the security arrangements you have put in place' => ['radios', 'security_arrangements', [
                    'own_watchman' => 'Own Watchman',
                    'security_guards' => 'Security Guards',
                    'anyother' => 'Any Other'
                ]],
                'Please Specify any other security arrangements' => ['textarea', 'specify_any_ther_security', '', [
                    'class' => 'form-control', 'disabled' => ''
                ]],
                '{note9}' => ['note', 'note9', 'note9', '<h4>Section C - All Risks</h4>'],
                'Do you require Section C - All Risks' => ['radios', 'sectionc', [
                    1 => 'Yes',
                    0 => 'No'
                ], 1],
                '{note10}' => ['note', 'note10', 'note10', '<b>Note:</b> The sum insured should be the replacement value of the property less a deduction for wear, tear and depreciation.
                <p>Please give a detailed description and state separately the value of each item as provided here below</p>
                <p>For any items of jewelry with sum insured upto and in excess of <b>KES. 50,000/=</b> a valuation report must be submitted.</p>
                '],
                'No of Items' => ['select', 'section_c_no_of_items', '', $this->data->no_of_items, [
                    'class' => 'form-control'
                ]],
                '{divnote2}' => ['note', 'divnote2', 'divnote2', '<div id="sectionc_fields"></div>'],
                '{note11}' => ['note', 'note11', 'note11', '<h3>Section D - Work Injury benefit (as per WIBA Act. 2007)</h3>'],
                'Do you require Section D - Work Injury Benefit' => ['radios', 'work_injury_benefit', [
                    1 => 'Yes',
                    0 => 'No'
                ], 1],
                'No of Employees' => ['select', 'no_employees', '', $this->data->no_of_items, [
                    'class' => 'form-control'
                ]],
                '{note12}' => ['note', 'note12', 'note12', '<div id="sectiond_fields"></div>'],
                '{note13}' => ['note', 'note13', 'note13', '<h3>Section E - Employer\'s Liability</h3>'],
                'Limit of Cover required' => ['checkboxes', 'limit_of_cover', [
                    'option_a' => 'Option A', 'option_b' => 'Option B'
                ]],
                '{note14}' => ['note', 'note14', 'note14', '
                    <p>Any one person (Option A) - KES. 2,000,000/= (Option B) - KES. 4,000,000</p>
                    <p>Any one Occurrence (Option A) - KES. 10,000,000/= (Option B) - KES. 15,000,000</p>
                    <p>Any one year (Option A) - KES. 20,000,000/= (Option B) - KES. 30,000,000</p>
                    <b>Subject to deductible of KES. 10,000/= each and every claim.</b>
                '],
                'Limit of indemnity required' => ['text', 'limit_of_indemnity', '', ['class' => 'form-control']],
                '{note15}' => ['note', 'note15', 'note15', '<h3>Section F - Occupier\'s and Personal Liability</h3>'],
                'Limit of indemnity required ' => ['text', 'sectionf_limit_of_indemnity', '', ['class' => 'form-control']],
                '{note16}' => ['note', 'note16', 'note16', '
                    <h4>Declation</h4>
                    <p>I/We do hereby declare that the above answers are true to the best of my knowledge and belief and 
                    that I/We have not withheld any information whatever regarding the proposal. I/We agree that the declaration 
                    and the answers given above shall be the basis of the contract between me/us and <b>Intra Africa Assurance Co. Ltd.</b></p>
                '],
                'I Agree' => ['radios', 'i_agree', [1 => 'Yes', 0 => 'No']],
                '{note17}' => ['note', 'note17', 'note17', '
                    <p>This liability of the company does not attach until the proposal has been accepted by the Company and premium has been paid.</p>
                '],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >', ['class' => 'btn btn-success pull-right']]
            ],
        ];
        $form = Generate::Form('domestic_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function propertyDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/domestic/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [1,1,2,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
            'controls' => [
                'Situation of Premises: Plot No: ' => ['text', 'plot_no', '', ['class' => 'form-control', 'required' => '']],
                '{note79}' => ['note', 'note79', 'note79', 'Of what material was the dwelling constructed?'],
                'Walls' => ['text', 'walls', '', ['class' => 'form-control']],
                'Roof' => ['text', 'roof', '', ['class' => 'form-control']],
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
                'Telephone Contacts*' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '']],
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
        $this->setViewPanel('view_data');
//        $this->setViewPanel('quotations');
    }

}
