<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Auth;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use App\Services\VerifyCode\Rongyun;
use App\Services\Helper\Helper;
use App\SystemInfo;
use Carbon\Carbon;


class SystemController extends Controller
{
    
    const VERIFY_MESSAGE_SUCCESS = '获取验证码成功';
    const VERIFY_MESSAGE_FAIL = '获取验证码失败';
    const TPLID = 1;
    const AVALIABLE_TIME = 10 ; 
    const CHANGE_PHONE_SUCCESS = '修改手机号成功!';
    const VERIFY_CODE_ERROR = '验证码错误!';
    const PHONE_FAILD = '手机号验证失败!';
    const PHONE_GET_ERROR = '获取手机号失败，请联系主账号设置';
    const PASSWORD_LENGTH_FAILD = "请输入正确位数密码";
    const CHANGE_PASSWORD_SUCCESS = "修改支付密码成功";

	/**
	 * [manage description] 主账号系统管理入口
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function manage(Request $request)
    {
        $user = Auth::user();
        $infos = SystemInfo::user($user)->first();
        if(!$infos){
            $infos = SystemInfo::create(['user_id' => $user->getSystemId() ,'phone_number' => $user->phone]);
        }
        $data['infos'] = $infos;
        $data['user'] = $user;
        return view('system.manage' , $data);
    }

    /**
     * [changePhone description]修改手机号
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changePhone(Request $request)
    {
        $user = Auth::user();
        $verifyCode = $request->get('verify_code');
        $newPhoneNumber = $request->get('new_phone_number');
        //验证手机号 先验证手机号，后验证验证码
        $res = Helper::isMobile($newPhoneNumber);
        if(!$res){
            return ['result' => false ,'message' => self::PHONE_FAILD];
        }
        $res = SystemInfo::checkVerifyCode($user , $verifyCode);
        if(!$res){
            return ['result' => false ,'message' => self::VERIFY_CODE_ERROR];
        }
        //修改对应手机号记录
        SystemInfo::user($user)->update(['phone_number' => $newPhoneNumber]);
        return ['result' => true ,'message' => self::CHANGE_PHONE_SUCCESS];
    }

    /**
     * [changePaymentPassword description]修改支付密码
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changePaymentPassword(Request $request)
    {
        $user = Auth::user();
        $verifyCode = $request->get('verify_code');
        $newPassword = $request->get('new_password');
        //验证密码是否长度符合要求
        $res = Helper::passwordVal($newPassword);
        if(!$res){
            return ['result' => false ,'message' => self::PASSWORD_LENGTH_FAILD];
        }
        $res = SystemInfo::checkVerifyCode($user , $verifyCode);
        if(!$res){
            return ['result' => false ,'message' => self::VERIFY_CODE_ERROR];
        }
        //修改对应手机号记录
        SystemInfo::user($user)->update(['payment_password' => Hashid::encode($newPassword)]);
        return ['result' => true ,'message' => self::CHANGE_PASSWORD_SUCCESS];
    }

    /**
     * [getVerifyCode description]获取验证码 验证码机制为一个用户十分钟内只有一个验证码有效，使用过后 验证码失效
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getVerifyCode(Request $request)
    {
        $user = Auth::user();
        $sendTemplateSMS = new Rongyun();
        $phoneNumber = SystemInfo::getPhone($user);
        if(!$phoneNumber){
            return ['result' => false ,'message' => self::PHONE_GET_ERROR];
        }
        //随机生成四位
        $verifyCode =  random_int(1000,9999);
        $res = $sendTemplateSMS->sendTemplateSMS($phoneNumber , [$verifyCode , SELF::AVALIABLE_TIME] , SELF::TPLID );
        $fields = ['user_id' => $user->getSystemId() ,'phone_number' => $phoneNumber ,'verify_code' => $verifyCode ,'verify_code_expired_at' => Carbon::now()->addMinutes(SELF::AVALIABLE_TIME)];
        $conds = ['user_id' => $user->getSystemId() ];
        SystemInfo::updateOrCreate($conds, $fields);
        if(!$res){
            return ['result' => true ,'message' => self::VERIFY_MESSAGE_FAIL];
        }
        return ['result' => true ,'message' => self::VERIFY_MESSAGE_SUCCESS];
    }

    
}
