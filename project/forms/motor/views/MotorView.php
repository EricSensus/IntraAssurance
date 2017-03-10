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

/**
 * Class MotorView
 */
class MotorView extends View
{

    private $data;

    public function wizard($data = null)
    {
        $this->data = $data;
        switch ($this->data->step) {
            case "1":
                $this->personalDetails();
                break;
            case "2":
                $this->carDetails();
                break;
            case "22":
                $this->otherCarDetails();
                break;
            case "3":
                $this->coverDetails();
                break;
            case "4":
                $this->showQuotations();
                break;
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
            'map' => [3, 3, 3, 3, 3, 1, 1, 1],
            'action' => '/motor/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Title *' => ['select', 'title', '', $this->data->titles, ['class' => 'form-control', 'required' => '']],
                'Surname *' => ['text', 'surname', '', ['class' => 'form-control', 'required' => '']],
                'Other Names *' => ['text', 'names', '', ['class' => 'form-control', 'required' => '']],
                'Occupation/Profession *' => ['text', 'occupation', '', ['class' => 'form-control', 'required' => '']],
                'Date of Birth *' => ['text', 'dob', '', ['class' => 'form-control', 'required' => '']],
                'PIN No. *' => ['text', 'pin', '', ['class' => 'form-control', 'required' => '']],
                'ID or Passport No. *' => ['text', 'idpassport', '', ['class' => 'form-control', 'required' => '']],
                'Driving Licence No. *' => ['text', 'dlno', '', ['class' => 'form-control', 'required' => '']],
                'Year First Driving Licence issued *' => ['select', 'dlyearissued', null, $this->data->years, ['class' => 'form-control', 'required' => '']],
                'No of years driving experience *' => ['select', 'drivingexperience', '', $this->data->numbers, ['class' => 'form-control', 'required' => '']],
                'Email*' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],
                'Mobile Number *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '',]],
                'P.O Box*' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
                'Town *' => ['select', 'town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Do you (and/or any other persons who to your knowledge will drive the car (s)) suffer from defective vision or hearing, or any physical infirmity including fits?'
                => ['radios', 'deffectivevision', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Particulars' => ['textarea', 'particulars', '', ['class' => 'form-control', 'rows' => 2]],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Car Details >>', ['class' => 'btn btn-success']]
            ]
        ];
        
        $form = Generate::Form('motor_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        
        $this->setViewPanel('personal_details');
    }

    private function carDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/motor/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [3, 4, 2, 2, 2, 2, 1, 2, 1,],
            'controls' => [
                'Registration no/mark*' => ['text', 'regno', '', ['class' => 'form-control', 'required' => '']],
                'Chassis No *' => ['text', 'chassisno', '', ['class' => 'form-control', 'required' => '']],
                'Engine No *' => ['text', 'engineno', '', ['class' => 'form-control', 'required' => '']],
                'Make *' => ['select', 'makes', '', $this->data->makes, ['class' => 'form-control', 'required' => '']],
                'Model *' => ['text', 'model', '', ['class' => 'form-control', 'required' => '']],
                'Type of body *' => ['text', 'bodytype', '', ['class' => 'form-control', 'required' => '']],
                'Seating Capacity *' => ['text', 'seatingcapacity', '', ['class' => 'form-control', 'required' => '']],
                'Year of Manufacture *' => ['select', 'dlyear', '', $this->data->years, ['class' => 'form-control', 'required' => '']],
                'Estimated Value including accessories and parts in Kshs <span style="font-size: 11px;">(*will be subject to valuation by the insurer)</span> *'
                => ['text', 'valueestimate', '', ['class' => 'form-control', 'required']],
                'Is the vehicle fitted with any anti theft device *' => ['radios', 'antitheft', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, please give particulars below*' => ['textarea', 'antitheftdetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Where is this car normally parked during daytime?*' => ['select', 'daytimeparking', '', $this->data->parking_lots, ['class' => 'form-control', 'required' => '']],
                'Tell us the town, estate or road' => ['text', 'daytimeparkingdetails', '', ['class' => 'form-control', 'required']],
                'Where is this car normally parked at night ? *' => ['select', 'nightparking', '', $this->data->parking_lots, ['class' => 'form-control', 'required']],
                'Tell us the town, estate or road *' => ['text', 'nighparkingdetails', '', ['class' => 'form-control']],
                'How do you use this vehicle?' => ['checkboxes', 'carusage', $this->data->car_usage, ['class' => 'form-control', 'rows' => 2]],
                'Do you want to add another car?' => ['radios', 'somecovers', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'How many additional cars? Choose  number' => ['select', 'othercovers', 1, [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9'], ['class' => 'form-control']],
                '{submit}' => ['submit', 'btnSubmitSpecial', 'Proceed to Cover Details >>', ['class' => 'btn btn-success',]]
            ]
        ];
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
            'map' => [3, 4, 1, 1, 1, 1, 1, 4, 4, 4, 4, 4, 1, 1, 1, 1],
            'controls' => [
                'Cover start date *' => ['text', 'coverstart', '', ['class' => 'form-control', 'required' => '']],
                'Cover End *' => ['text', 'coverend', '', ['class' => 'form-control', 'required' => '']],
                'Type of cover *' => ['select', 'covertype', '', $this->data->cover_type, ['class' => 'form-control', 'required' => '']],
                'Riots & Strikes?' => ['radios', 'riotes', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Windscreen?' => ['radios', 'windscreen', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Audio System?' => ['radios', 'audio', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Passenger Liability' => ['radios', 'passenger', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Terrorism' => ['radios', 'terrorism', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'What is your No Claim Discount entitlement (NCD)(%)? (*Proof letter will be required)' =>
                    ['select', 'ncddiscount', '', [0 => "0", 10 => 10, 20 => 20, 30 => 30, 40 => 40, 50 => 50], ['class' => 'form-control']],
                'Have you, or anyone else who will drive this vehicle, had any insurance declined, voided or special terms imposed and regardless of blame
' => ['radios', 'previousdeclines', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Have you, or anyone else who will drive this vehicle (s), had any motor related accidents or losses, whether there was a claim or not and regardless of blame' =>
                    ['radios', 'previousaccidents', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                //some loop
                '{noted1}' => ['note', 'noted1', "noted1", "If the answer is yes, please provide details for the last five years below"],
                //1
                // '{note1}' => ['note', 'note1', "note1", "<h4>Year 1</h4><hr/>"],
                "Year 1: Claim No" => ['text', "claim_no_yr1", '', ['class' => 'form-control',]],
                "Year 1: Claim Amount" => ['text', "claim_amount_yr1", '', ['class' => 'form-control',]],
                "Year 1: Insurer" => ['text', "insurer_yr1", '', ['class' => 'form-control',]],
                "Year 1: Claim Details" => ['text', "claim_details_yr1", '', ['class' => 'form-control',]],
                //2
                // '{note2}' => ['note', 'note2', "note2", "<h4>Year 2</h4><hr/>"],
                "Year 2: Claim No" => ['text', "claim_no_yr2", '', ['class' => 'form-control',]],
                "Year 2: Claim Amount" => ['text', "claim_amount_yr2", '', ['class' => 'form-control',]],
                "Year 2: Insurer" => ['text', "insurer_yr2", '', ['class' => 'form-control',]],
                "Year 2: Claim Details" => ['text', "claim_details_yr2", '', ['class' => 'form-control',]],
                //3
                //'{note3}' => ['note', 'note3', "note3", "<h4>Year 3</h4><hr/>"],
                "Year 3: Claim No" => ['text', "claim_no_yr3", '', ['class' => 'form-control',]],
                "Year 3: Claim Amount" => ['text', "claim_amount_yr3", '', ['class' => 'form-control',]],
                "Year 3: Insurer" => ['text', "insurer_yr3", '', ['class' => 'form-control',]],
                "Year 3: Claim Details" => ['text', "claim_details_yr3", '', ['class' => 'form-control',]],
                //4
                // '{note4}' => ['note', 'note4', "note4", "<h4>Year 4</h4><hr/>"],
                "Year 4: Claim No" => ['text', "claim_no_yr4", '', ['class' => 'form-control',]],
                "Year 4: Claim Amount" => ['text', "claim_amount_yr4", '', ['class' => 'form-control',]],
                "Year 4: Insurer" => ['text', "insurer_yr4", '', ['class' => 'form-control',]],
                "Year 4: Claim Details" => ['text', "claim_details_yr4", '', ['class' => 'form-control',]],
                //5
                //'{note5}' => ['note', 'nots5', "note5", "<h4>Year 5</h4><hr/>"],
                "Year 5: Claim No" => ['text', "claim_no_yr5", '', ['class' => 'form-control',]],
                "Year 5: Claim Amount" => ['text', "claim_amount_yr5", '', ['class' => 'form-control',]],
                "Year 5: Insurer" => ['text', "insurer_yr5", '', ['class' => 'form-control',]],
                "Year 5: Claim Details" => ['text', "claim_details_yr5", '', ['class' => 'form-control',]],
                //end
                ' Where or how would you like to get your motor certificate? (Please note: You will need an ID or PIN certificate, No Claim Discount (NCD) Letter, and log book copy to collect your motor certificate)'
                => ['select', 'pickat', '', $this->data->pick_cert, ['class' => 'form-control', 'required' => '']],
                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>The liability of the Jubilee Insurance Company of Kenya Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p><p></p>
                    <p><strong>DECLARATION</strong></p><p></p>
                    <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
                'I hereby agree to all the above terms and conditions' => ['radios', 'acceptterms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >>', ['class' => 'btn btn-success']]
            ]
        ];
        $form = Generate::Form('motor_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function showQuotations()
    {
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
