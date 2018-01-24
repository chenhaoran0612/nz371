<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $guarded = [];

    public function scopeUser($query , $user)
    {
        return $query->where('user_id',$user->getParentId());
    }

    public function operateUser()
    {
        return $this->belongsTo('App\User','operate_user_id','id')->withTrashed();
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod','payment_method_id','id')->withTrashed();
    }

    public function userAccount()
    {
        return $this->belongsTo('App\UserAccount','user_account_id','id')->withTrashed();
    }

    /**
     * 交易记录创建
     * @param  [type]   $function       方法名
     * @param  [type]   $payment_method 付款方式
     * @param  [type]   $amount         金额
     * @param  [type]   $trade_no       交易号
     * @param  User $user           user
     * @return [type]                   [description]
     */
    public static function transactionLogCreate($function, $paymentMethod, $amount, $out_trade_no, User $user)
    {
        $userAccount = UserAccount::where('user_id', $user->getParentId())->where('operate_user_id', $user->id)->where('payment_method_id', $paymentMethod->id)->first();
        $data = [
            'action' => $function,
            'payment_method' => $paymentMethod->method,
            'amount' => $amount,
            'out_trade_no' => $out_trade_no,
            'operate_user_id' => $user->id,
            'user_id' => $user->getParentId(),
            'payment_method_id' => $paymentMethod->id,
            'user_account_id' => $userAccount->id,
        ];
        self::create($data);
    }

    //付款记录确认
    public function makePayment($trade_no)
    {
        $data = [
            'status' => 1,
            'trade_no' => $trade_no,
            'paid_at' => Carbon::now(),
        ];
        $this->update($data);
    }

    public function scopeSearch($query, $searchs, $conditions)
    {
        $searchs = array_filter($searchs);
        foreach ($searchs as $key => $search) {
            if (is_string($conditions[$key])) {
                if ($conditions[$key] == '=') {
                    $query = $query->where($key, '=', $search);
                }
                if ($conditions[$key] == 'like') {
                    $query = $query->where($key, 'like', '%'.$search.'%');
                }
            }
            if (is_array($conditions[$key])) {
                $query = $query->where($conditions[$key][1], $conditions[$key][0], $search);
            }
        }
    }

    public function scopeUnpayment($query)
    {
        return $query->where('status', 0);
    }


}
