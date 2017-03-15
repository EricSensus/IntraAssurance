<?php
use Jenga\App\Html\Generate;

$schematic = [
    
    /**
     * Display settings 
     * preventjQuery => 'TRUE' or 'FALSE', 
     * css => 'TRUE', 'FALSE' or 'path/to/other.css'
     */
    'preventjQuery' => FALSE,
    //css => 'TRUE', 'FALSE' or 'path/to/other.css'
    'method' => 'POST',
    'action' => '/login',
    
    /**
     * Under controls 
     * 'field label name' => [
     *      'field_type',
     *      'field_name',
     *      'default value',
     *      ['field_attributes']
     * ]
     */
    'controls' => [        
        'Username or Email Address' => ['text','username','',['label'=>'inside']],
        'Password' => ['password', 'password', '',['label'=>'inside']],
        '{forgot_password}' => ['note','note_password','password','<a href="#">forgot username or password</a>'],
        'Destination' => ['hidden','destination','/admin/dashboard'],
        '{submit}' => ['submit','userlogin','Login']
    ],
    
    /**
     * Under validation
     * 
     * 'field_name' => [
     *      'validation_rule_one' => 'Error message one',
     *      'validation_rule_two' => 'Error message two'
     * ]
     */
    'validation' =>[
        'username' => [
            'required' => 'Please enter the username'
        ],
        'password' => [
            'required' => 'Please enter your password'
        ]
    ]
];

$login = Generate::Form('loginform', $schematic);

echo '<div class="logintop">'
        . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/login/images/intraassurance-logo.png" />'
    . '</div>';

$login->render('vertical');
