<?php
// 创建订单号
if (!function_exists("getOrderNum")) {
    function getOrderNum($prefix = '')
    {
        return $prefix . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}

/**
 * 用户密码加密方法
 * @param string $str 加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
if (!function_exists("userMd5")) {
    function userMd5($str, $auth_key = '')
    {
        return '' === $str ? '' : md5(sha1($str) . $auth_key);
    }
}


/**
 * 用户密码加密方法
 * @param string $str 加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
if (!function_exists("userUniqid")) {
    function userUniqid()
    {
        return uniqid(mt_rand(100, 999));
    }
}

/**
 * 生成固定长度邀请码
 * @return string
 */
if (!function_exists("createInvitationCode")) {
    function createInvitationCode()
    {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0, 25)]
            . strtoupper(dechex(date('m')))
            . date('d')
            . substr(time(), -5)
            . substr(microtime(), 2, 5)
            . sprintf('%02d', rand(0, 99));
        for (
            $a = md5($rand, true),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            $d = '',
            $f = 0;
            $f < 6;
            $g = ord($a[$f]),
            $d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],
            $f++
        ) ;
        return $d;
    }
}

if(!function_exists("db_escape"))
{
    function db_escape($value)
    {
        return str_replace(array("\\", "\0", "\n", "\r", "\x1a", "'", '"'), array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'), $value);
        // return DB::connection()->getPdo()->quote($value);
    }
}

if (!function_exists("moneyf"))
{
    // 格式化价格
    function moneyf($money){  
        return number_format($money, 2, ".", "");
    }
}

if (!function_exists("data_letter_sort"))
{
    /**
     * 按照 字母 分组 并 排序
     * 
     * @param {Array} $list ; 需要 排序的 数据， 一维数组
     * @param {string} $field ; 排序 需要 依据 的字段，该字段 必须为 拼音 
     */
    function data_letter_sort($list, $field) {
        $resault = array();
    
        foreach( $list as $key => $val ){
            // 添加 # 分组，用来 存放 首字母不能 转为 大写英文的 数据
            $resault['#'] = array();
            // 首字母 转 大写英文
            $letter = strtoupper( substr($val[$field], 0, 1) );
            // 是否 大写 英文 字母
            if( !preg_match('/^[A-Z]+$/', $letter) ){
                $letter = '#';
            }
            // 创建 字母 分组
            if( !array_key_exists($letter, $resault) ){
                $resault[$letter] = array();
            }
            // 字母 分组 添加 数据
            Array_push($resault[$letter], $val);
        }
        // 依据 键名 字母 排序，该函数 返回 boolean
        ksort($resault);
        // 将 # 分组 放到 最后
        if (isset($resault['#']))
        {
            $arr_last = $resault['#'];
            unset($resault['#']);
            $resault['#'] = $arr_last;
        }
        
        
        return $resault;
    }
}