<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use Auth;

class SystemInfo extends Model
{
    protected $guarded = [];
    use SoftDeletes;


    public function scopeUser($query, $user)
    {
    	//经销商与供应商有各自的系统配置信息
    	switch ($user->role) {
    		case 'vendor':
    		case 'distributor':
    			return $query->where('user_id', $user['id']);
    			break;
    		case 'subaccount':
    			return $query->where('user_id', $user->getParentId());
    			break;
    		default:
    			return $query->where('user_id', $user->getParentId());
    			break;
    	}
        
    }

    /**
     * [validatePaymentPassword description]验证支付密码是否真实有效
     * @param  [type] $user     [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public static function validatePaymentPassword($user , $password)
    {
        $data = self::user($user)->first();
        if($data && isset($data['payment_password']) && $data['payment_password'] == Hashid::encode($password)){
            return true;
        }
        return false;
    }

    /**
     * [getPhoneAttribute description]获取认证手机号数据
     * @return [type] [description]
     */
    public function getPhoneAttribute()
    {
        return self::getPhone(Auth::user());
    }

    /**
     * [getPhone description]获取到当前用户对应的手机号
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public static function getPhone($user)
    {
        $data = self::user($user)->first();
        if($data && isset($data['phone_number']) && $data['phone_number'] != ''){
            return $data['phone_number'];
        }
        //系统表中没有记录相关操作
        return User::where('id' , Auth::user()->getSystemId())->value('phone');
        
    }

    /**
     * [checkVerifyCode description] 校验验证码准确性,验证一次之后设置验证码失效
     * @param  [type] $user       [description]
     * @param  [type] $verifyCode [description]
     * @return [type]             [description]
     */
    public static function checkVerifyCode($user , $verifyCode)
    {
    	$code = self::user($user)->where('verify_code_expired_at' ,'>' , Carbon::now()->toDateTimeString())->first();
    	if($code  && $code['verify_code'] == $verifyCode ){
    		$code->update(['verify_code_expired_at' =>  Carbon::now()->toDateTimeString()]);
    		return true;
    	}
    	return false;
    }



}
