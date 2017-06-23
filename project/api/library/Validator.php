<?php

namespace Jenga\MyProject\Api\Library;
/**
 * Class Validator
 * @package Jenga\MyProject\Api\Library
 */
class Validator
{

    /**
     * @var array
     */
    public static $customer = [
        'name',
        'id_number',
        'email',
        'dob',
        'postal_address',
        'postal_code',
        'mobile',
    ];

    public static $policy = [
        ''
    ];
    /**
     * Check if key exists else throw exception.
     * @param array $data
     * @param array $rules
     * @return bool
     * @throws ApiExceptions
     */
    public static function validate($data, $rules)
    {
        foreach ($rules as $value) {
            if (!array_key_exists($value, $data)) {
                throw new ApiExceptions("Missing field " . $value, 400);
            }
        }
        return true;
    }
}