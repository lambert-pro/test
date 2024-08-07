<?php

/**
 * zh-cn
 */
class ZhCn
{
    public $error_template = array(
        'default' => '@this 验证错误',
        'index_array' => '@this 必须是索引数组',
        'required' => '@this 不能为空',
        'unset_required' => '@this 允许不设置，一旦设置则不能为空',
        'preg' => '@this 格式错误，必须是 @preg',
        'call_method' => '@thisthod 未定义',
        '=' => '@this 必须等于 @p1',
        '!=' => '@this 必须不等于 @p1',
        '==' => '@this 必须全等于 @p1',
        '!==' => '@this 必须不全等于 @p1',
        '>' => '@this 必须大于 @p1',
        '<' => '@this 必须小于 @p1',
        '>=' => '@this 必须大于等于 @p1',
        '<=' => '@this 必须小于等于 @p1',
        '<>' => '@this 必须大于 @p1 且小于 @p2',
        '<=>' => '@this 必须大于 @p1 且小于等于 @p2',
        '<>=' => '@this 必须大于等于 @p1 且小于 @p2',
        '<=>=' => '@this 必须大于等于 @p1 且小于等于 @p2',
        '(n)' => '@this 必须是数字且在此之内 @p1',
        '!(n)' => '@this 必须是数字且不在此之内 @p1',
        '(s)' => '@this 必须是字符串且在此之内 @p1',
        '!(s)' => '@this 必须是字符串且不在此之内 @p1',
        'len=' => '@this 长度必须等于 @p1',
        'len!=' => '@this 长度必须不等于 @p1',
        'len>' => '@this 长度必须大于 @p1',
        'len<' => '@this 长度必须小于 @p1',
        'len>=' => '@this 长度必须大于等于 @p1',
        'len<=' => '@this 长度必须小于等于 @p1',
        'len<>' => '@this 长度必须大于 @p1 且小于 @p2',
        'len<=>' => '@this 长度必须大于 @p1 且小于等于 @p2',
        'len<>=' => '@this 长度必须大于等于 @p1 且小于 @p2',
        'len<=>=' => '@this 长度必须大于等于 @p1 且小于等于 @p2',
        'int' => '@this 必须是整型',
        'float' => '@this 必须是小数',
        'string' => '@this 必须是字符串',
        'arr' => '@this 必须是数组',
        'bool' => '@this 必须是布尔型',
        'bool=' => '@this 必须是布尔型且等于 @p1',
        'bool_str' => '@this 必须是布尔型字符串',
        'bool_str=' => '@this 必须是布尔型字符串且等于 @p1',
        'email' => '@this 必须是邮箱',
        'url' => '@this 必须是网址',
        'ip' => '@this 必须是IP地址',
        'mac' => '@this 必须是MAC地址',
        'dob' => '@this 必须是正确的日期',
        'file_base64' => '@this 必须是正确的文件的base64码',
        'uuid' => '@this 必须是 UUID',
        'oauth2_grant_type' => '@this 必须是合法的 OAuth2 授权类型'
    );
}
