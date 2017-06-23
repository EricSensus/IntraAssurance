<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 14/02/2017
 * Time: 16:51
 */

namespace Jenga\MyProject\Medical\Lib;


class Medical
{
    public static function determineAgeBracket($agebracket, $gender){
//        dd($agebracket);
        switch ($agebracket) {
            case '1-18':
                return $agebracket = 'Ac';
                break;

            case '19-30':
                if($gender=='Male')
                    $agebracket = 'A1';
                else
                    $agebracket = 'A1';
                return $agebracket;
                break;

            case '31-40':
                if($gender=='Male')
                    $agebracket = 'A2';
                else
                    $agebracket = 'A2';
                return $agebracket;
                break;

            case '41-50':
                if($gender=='Male')
                    $agebracket = 'A3';
                else
                    $agebracket = 'A3';
                return $agebracket;
                break;

            case '51-59':
                if($gender=='Male')
                    $agebracket = 'A4';
                else
                    $agebracket = 'A4';
                return $agebracket;
                break;

            case '60-65':
                if($gender=='Male')
                    $agebracket = 'A5';
                else
                    $agebracket = 'A5';
                return $agebracket;
                break;
        }
    }

    public static function getTheOptionalBenefits($step_data, $priority, $dependants_no = null){
        foreach ($step_data as $key => $value) {
            $step2[$key] = $value;
        }

        switch($priority){
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
                for($dep = 1; $dep <= $dependants_no; $dep++){
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

    public static function determinePlan($plan){
        if ($plan == 'premier')
            $plan = 'P1';
        else if($plan == 'advanced')
            $plan = 'P2';
        else if($plan == 'executive')
            $plan = 'P3';
        else
            $plan = 'P4';
        return $plan;
    }

    public static function getOptionalList($optionals){
        $optional_list = [];
        if (count($optionals)){
            foreach ($optionals as $optional){
                $optional_list[] = $optional;
            }
        }
        return $optional_list;
    }
}