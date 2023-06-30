<?php 

/**
 * ApiTemplate
 */
class ApiTemplate
{
    public $error_template = array(
        'default' => 'validation failed',
        'index_array' => 'must be a numeric array',
        'required' => 'required',
        'unset_required' => 'must be unset or not be empty',
        'preg' => 'format is invalid, must be @preg',
        'call_method' => '@method is undefined',
        '=' => 'must be equal to @p1',
        '!=' => 'must be not equal to @p1',
        '==' => 'must be identically equal to @p1',
        '!==' => 'must be not identically equal to @p1',
        '>' => 'must be greater than @p1',
        '<' => 'must be less than @p1',
        '>=' => 'must be greater than or equal to @p1',
        '<=' => 'must be less than or equal to @p1',
        '<>' => 'must be greater than @p1 and less than @p2',
        '<=>' => 'must be greater than @p1 and less than or equal to @p2',
        '<>=' => 'must be greater than or equal to @p1 and less than @p2',
        '<=>=' => 'must be greater than or equal to @p1 and less than or equal to @p2',
        '(n)' => 'must be numeric and valid values: @p1',
        '!(n)' => 'must be numeric and can not be valid values: @p1',
        '(s)' => 'must be string and valid values: @p1',
        '!(s)' => 'must be string and can not be valid values: @p1',
        'len=' => 'length must be equal to @p1',
        'len!=' => 'length must be not equal to @p1',
        'len>' => 'length must be greater than @p1',
        'len<' => 'length must be less than @p1',
        'len>=' => 'length must be greater than or equal to @p1',
        'len<=' => 'length must be less than or equal to @p1',
        'len<>' => 'length must be greater than @p1 and less than @p2',
        'len<=>' => 'length must be greater than @p1 and less than or equal to @p2',
        'len<>=' => 'length must be greater than or equal to @p1 and less than @p2',
        'len<=>=' => 'length must be greater than or equal to @p1 and less than or equal to @p2',
        'int' => 'must be integer',
        'float' => 'must be float',
        'string' => 'must be string',
        'arr' => 'must be array',
        'bool' => 'must be boolean',
        'bool=' => 'must be boolean @p1',
        'bool_str' => 'must be boolean string',
        'bool_str=' => 'must be boolean string @p1',
        'email' => 'must be email',
        'url' => 'must be url',
        'ip' => 'must be IP address',
        'mac' => 'must be MAC address',
        'dob' => 'must be a valid date',
        'file_base64' => 'must be a valid file base64',
        'uuid' => 'must be a UUID'
    );
}
