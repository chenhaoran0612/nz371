<?php
namespace App\Services\Helper;

class Helper 
{
    /**
     * 是否为手机号码
     * @param $string
     * @return bool
     */
    public static function isMobile($string) {
        return !!preg_match('/^1[3|4|5|7|8]\d{9}$/', $string);
    }

    /**
     * [passwordVal description]验证密码是否是6-20位
     * @param  [type] $string [description]
     * @return [type]         [description]
     */
    public static function passwordVal($string)
    {
    	return (mb_strlen($string) > 5 && mb_strlen($string) < 21 );
    }

}



?>
