<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Motor\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Session;
use Jenga\App\Views\View;
use Jenga\App\Helpers\Help;

/**
 * Class MotorView
 */
class MotorView extends View {

    private $data;
    private $want_schematic = false;
    protected $special = false;

    public function getSchematic($data = null, $count = null)
    {
        if (!empty($count)) {
            $this->data = $data;
            $this->special = true;
            $this->data->cars = $count;
            return $this->otherCarDetails();
        }
        $this->want_schematic = true;
        return $this->wizard($data);
    }

    public function wizard($data = null)
    {
        $this->data = $data;
        $this->set('_data', $data);
        $x = null;
        switch ($this->data->step) {
            case "1":
                $x = $this->personalDetails();
                break;
            case "2":
                $x = $this->carDetails();
                break;
            case "22":
                $x = $this->otherCarDetails();
                break;
            case "3":
                $x = $this->coverDetails();
                break;
            case "4":
                $x = $this->showQuotations();
                break;
        }
        if ($this->want_schematic) {
            return $x;
        }
    }

    private function personalDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [2, 3, 3, 3, 1],
            'action' => '/motor/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Title *' => ['select', 'title', '', $this->data->titles, ['class' => 'form-control', 'required' => '']],
                'Full Name *' => ['text', 'FullName', '', ['class' => 'form-control', 'required' => '']],
                'Date of Birth *' => ['text', 'dob', '', ['class' => 'form-control datepicker', 'required' => '']],
                'Occupation/Profession *' => ['text', 'Occupation', '', ['class' => 'form-control', 'required' => '']],
                'ID or Passport No. *' => ['text', 'id_passport_no', '', ['class' => 'form-control', 'required' => '']],
                'Mobile Number *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '',]],
                'Email*' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],
                'PIN Number*' => ['text', 'pin', '', ['class' => 'form-control', 'required' => '']],
                'Address *' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
                'Town *' => ['select', 'Town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Car Details >>', ['class' => 'pull-right btn btn-success']]
            ]
        ];

        if ($this->want_schematic) {
            return $schematic;
        }

        $form = Generate::Form('motor_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);

        $this->setViewPanel('personal_details');
    }

    private function carDetails(){

        if(!Session::has('motor_commercial')){
            $map = [4, 4, 2, 2, 2, 2, 2, 1, 1,];
        }
        else{
            $map = [4, 3, 2, 2, 2, 2, 2, 2, 1, 1,];
        }

        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/motor/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => $map,
            'controls' => [
                '{type}' => ['hidden','type','private'],
                'Registration no*' => ['text', 'RegNo', '', ['class' => 'form-control', 'required' => '']],
                'Vehicle Color*' => ['text', 'vehicle_color', '', ['class' => 'form-control', 'required' => '']],
                'Chassis No' => ['text', 'ChassisNo', '', ['class' => 'form-control']],
                'Engine No' => ['text', 'EngineNo', '', ['class' => 'form-control']],
                'Make *' => ['select', 'CarMake', '', $this->data->makes, ['class' => 'form-control', 'required' => '']],
                'Cubic Capacity (cc)' => ['text', 'CC', '', ['class' => 'form-control']],
                'Type of body *' => ['text', 'BodyType', '', ['class' => 'form-control', 'required' => '']],
                'Seating Capacity *' => ['text', 'SeatingCapacity', '', ['class' => 'form-control', 'required' => '']],
                'Year of Manufacture *' => ['select', 'ManufactureDate', '', $this->data->years, ['class' => 'form-control', 'required' => '']],
                'Insured Value (Estimated Value of Vehicle)*'
                => ['text', 'ValueEstimate', '', ['class' => 'form-control', 'required' => '']],
                'Is the vehicle fitted with any anti theft device *' => ['radios', 'AntiTheft', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, please give particulars below (type and condition)*' => ['textarea', 'AntiTheftDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Are there any non-standard accessories in the vehicle (roof rack) *' => ['radios', 'NonStandardAccessories', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If so, please give particulars below (type and value)*' => ['textarea', 'NonStandardAccessoriesDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Is the vehicle subject to any special features (left hand drive,duty free)' => ['radios', 'SpecialFeatures', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If so, please give particulars of the features' => ['textarea', 'SpecialFeaturesDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Are you the owner of the vehicles and are they registered in your name' => ['radios', 'TheOwner', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If not state name and address of owner' => ['textarea', 'NameOfOwner', '', ['class' => 'form-control', 'rows' => 2]],
                'How do you use this vehicle?' => ['checkboxes', 'carusage', $this->data->car_usage, ['class' => 'form-control', 'rows' => 2]],
                //   'Do you want to add another car?' => ['radios', 'somecovers', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                //'How many additional cars? Choose  number' => ['select', 'othercovers', 1, [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9'], ['class' => 'form-control']],
                '{submit}' => ['submit', 'btnSubmitSpecial', 'Proceed to Cover Details >>', ['class' => 'btn btn-success pull-right',]]
            ]
        ];

        //add tonnage select field
        if(Session::has('motor_commercial')
                && Session::get('motor_commercial') === TRUE){

            $tonnage_list = [
                ''=>'Select Tonnage',
                'tpc03' => '0-3 tons',
                'tpc37' => '3-7 tons',
                'tpc715' => '7-15 tons',
                'tpc15p' => 'Above 15 tons'
            ];

            $tonfield = [
                '{type}' => ['hidden','type','commercial'],
                'Vehicle Tonnage *' => ['select','tonnage','', $tonnage_list, ['class' => 'form-control', 'required' => '']]
            ];

            Help::array_splice_assoc($schematic['controls'], array_search('Cubic Capacity (cc)', $schematic['controls']), 1, $tonfield);
        }

        if ($this->want_schematic) {
            return $schematic;
        }

        $form = Generate::Form('motor_car_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-6,col-sm-6'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('car_details');
    }

    private function coverDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/motor/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [1, 2, 1, 1, 2, 2, 4, 2, 1, 1, 1, 1, 1, 1, 1, 4, 4, 4,  1, 1, 1],
            'controls' => [
                '{cover details}' => ['note', 'cover_heading', "benefits", "<span style=\"font-weight:bold\">Enter the cover type and life span</span>"],

                'Cover start date *' => ['text', 'coverstart', '', ['class' => 'form-control', 'required' => '']],
                'Cover End *' => ['text', 'coverend', '', ['class' => 'form-control', 'required' => '']],

                'Type of cover *' => ['select', 'covertype', '', $this->data->cover_type, ['class' => 'form-control', 'required' => '']],

                '{Additional Benefits}' => ['note', 'benefits', "benefits", "<span style=\"font-weight:bold\">Choose Additional Benefits to include in cover</span>"],

                'Windscreen?' => ['radios', 'windscreen', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If yes, state value' => ['text', 'WindscreenValue', '', ['class' => 'form-control', 'rows' => 2]],

                'Entertainment Equipment?' => ['radios', 'entertainment_equipment', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If yes, state value of equipment' => ['text', 'entertainmentvalue', '', ['class' => 'form-control', 'rows' => 2]],

                'Political Violence?' => ['radios', 'political_violence', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'SRCC (Strikes, Riots and Civil Commotion)?' => ['radios', 'srcc', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Excess Protector' => ['radios', 'excess_protector', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Loss of Use' => ['radios', 'loss_of_use', ['yes' => 'Yes', 'no' => 'No'], 'no'],

                'Do you require the cover Personal Accidents' => ['radios', 'NeedPersonalCover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If yes, give details ' => ['textarea', 'PersonalCoverDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Have you, or anyone else who will drive this vehicle (s), had any motor related accidents or losses, whether there was a claim or not and regardless of blame' =>
                    ['radios', 'previousaccidents', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Has any company declined your proposal?' => ['radios', 'decline_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Has any company required an increase in premium?' => ['radios', 'demand_increased_rate', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Has any company required your to bear the first portion of the loss?' => ['radios', 'imposed_special_terms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Has any company declined to renew or cover your policy?' => ['radios', 'declined_renewal', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Have you, or anyone else who will drive this vehicle, had any insurance declined, voided or special terms imposed and regardless of blame'
                => ['radios', 'previousdeclines', ['yes' => 'Yes', 'no' => 'No'], 'no'],

                //some loop
                '{noted1}' => ['note', 'noted1', "noted1", "Give a record of accident and/or losses during the past three years
                in connection to any motor vehicle owned or driven by you "],
                //1
                // '{note1}' => ['note', 'note1', "note1", "<h4>Year 1</h4><hr/>"],
                "Year 1: No of vehicles owned" => ['text', "record_no_of_vehicles_yr1", '', ['class' => 'form-control',]],
                "Year 1: Total no. of accidents" => ['text', "record_of_of_accidents_yr1", '', ['class' => 'form-control',]],
                "Year 1: Paid Amount" => ['text', "record_paid_yr1", '', ['class' => 'form-control',]],
                "Year 1: Outstanding Amount" => ['text', "record_outstanding_yr1", '', ['class' => 'form-control',]],
                // '{note1}' => ['note', 'note1', "note1", "<h4>Year 1</h4><hr/>"],

                "Year 2: No of vehicles owned" => ['text', "record_no_of_vehicles_yr2", '', ['class' => 'form-control',]],
                "Year 2: Total no. of accidents" => ['text', "record_of_of_accidents_yr2", '', ['class' => 'form-control',]],
                "Year 2: Paid Amount" => ['text', "record_paid_yr2", '', ['class' => 'form-control',]],
                "Year 2: Outstanding Amount" => ['text', "record_outstanding_yr2", '', ['class' => 'form-control',]],

                // '{note1}' => ['note', 'note1', "note1", "<h4>Year 1</h4><hr/>"],
                "Year 3: No of vehicles owned" => ['text', "record_no_of_vehicles_yr3", '', ['class' => 'form-control',]],
                "Year 3: Total no. of accidents" => ['text', "record_of_of_accidents_yr3", '', ['class' => 'form-control',]],
                "Year 3: Paid Amount" => ['text', "record_paid_yr3", '', ['class' => 'form-control',]],
                "Year 3: Outstanding Amount" => ['text', "record_outstanding_yr3", '', ['class' => 'form-control',]],

                //end
                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>DECLARATION</strong></p><p></p>
                    <p>I hereby warrant and declare the truth of all the above statements and that I have not withheld any material information
and I agree that this proposal shall be the basis of the contract between me and Intra Africa Assurance Co. Ltd. And I
agree to notify the company of any material alteration in my occupation, health or habits and to accept a policy subject
to the terms, exceptions and conditions prescribed by the company</p>'],
                'I hereby agree to all the above terms and conditions' => ['radios', 'acceptterms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >>', ['class' => 'btn btn-success pull-right']]
            ]
        ];

        $form = Generate::Form('motor_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);

        if ($this->want_schematic) {
            return $schematic;
        }

        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function showQuotations(){

        $this->set('data', $this->data);
        $this->setViewPanel('show_quotations');
    }

    private function otherCarDetails()
    {
        $this->data->cars = Session::get('other_covers');
        $controls = $this->makeMoreCars();
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/motor/save/22',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => $this->data->mapper,
            'controls' => $controls
        ];
        $form = Generate::Form('motor_other_car_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-6,col-sm-6'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('other_car_details');
    }

    private function makeMoreCars()
    {
        $map_template = [1, 3, 3, 1, 4, 1, 3, 2, 2, 2, 1,];
        $my_map = [];
        $my_push = [];
        for ($i = 1; $i <= $this->data->cars; $i++) {
            $group = [
                '{Car ' . $i . '}' => ['note', 'mine' . $i, '<h3>Enter Car ' . $i . ' Details</h3><hr/>'],
                'Car ' . $i . ': Registration no/mark*' => ['text', 'regno' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Chassis No *' => ['text', 'chassisno' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Engine No *' => ['text', 'engineno' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Make *' => ['select', 'makes' . $i, '', $this->data->makes, ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Model *' => ['text', 'model' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Type of body *' => ['text', 'bodytype' . $i, '', ['class' => 'form-control', 'required' => '']],
                //included
                'Car ' . $i . ' What is your No Claim Discount entitlement (NCD)(%)? <span style="font-size: 11px;">(*Proof letter will be required)</span>'
                => ['select', 'ncd_percent' . $i, '', [0 => "0", 10 => 10, 20 => 20, 30 => 30, 40 => 40, 50 => 50], ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ' Riots & Strikes?' => ['radios', 'riotes' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Car ' . $i . ' Windscreen?' => ['radios', 'windscreen' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Car ' . $i . ' Audio System?' => ['radios', 'audio' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Car ' . $i . ' Passenger Liability' => ['radios', 'passenger' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Car ' . $i . ' Terrorism' => ['radios', 'terrorism' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                //specifics
                'Car ' . $i . ': Seating Capacity *' => ['text', 'seatingcapacity' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Year of Manufacture *' => ['select', 'dlyear' . $i, '', $this->data->years, ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Estimated Value including accessories and parts in Kshs <span style="font-size: 11px;">(*will be subject to valuation by the insurer)</span> *'
                => ['text', 'valueestimate' . $i, '', ['class' => 'form-control', 'required']],
                'Car ' . $i . ': Is the vehicle fitted with any anti theft device *' => ['radios', 'antitheft' . $i, ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Car ' . $i . ': If Yes, please give particulars below*' => ['textarea', 'antitheftdetails' . $i, '', ['class' => 'form-control', 'rows' => 2]],
                'Car ' . $i . ': Where is this car normally parked during daytime?*' => ['select', 'daytimeparking' . $i, '', $this->data->parking_lots, ['class' => 'form-control', 'required' => '']],
                'Car ' . $i . ': Tell us the town, estate or road' => ['text', 'daytimeparkingdetails' . $i, '', ['class' => 'form-control', 'required']],
                'Car ' . $i . ': Where is this car normally parked at night ? *' => ['select', 'nightparking' . $i, '', $this->data->parking_lots, ['class' => 'form-control', 'required']],
                'Car ' . $i . ': Tell us the town, estate or road *' => ['text', 'nighparkingdetails' . $i, '', ['class' => 'form-control']],
                'Car ' . $i . ': How do you use this vehicle?' => ['checkboxes', 'carusage' . $i, $this->data->car_usage, ['class' => 'form-control', 'rows' => 2]],
            ];
            $my_push = array_merge($my_push, $group);
            $my_map = array_merge($my_map, $map_template);
        }
        $my_push = array_merge($my_push, ['{submit}' => ['submit', 'btnsubmit', 'Proceed to Cover Details >>', ['class' => 'btn btn-success']]]);
        $my_map = array_merge($my_map, [1]);
        $this->data->mapper = $my_map;
        return $my_push;
    }

}
