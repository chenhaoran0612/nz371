<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccount extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeUser($query , $user)
    {
        return $query->where('user_id',$user->getParentId());
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'operate_user_id')->withTrashed();
    }

    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id')->withTrashed();
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

    //用户余额新增
    public function userAmountAdd($amount)
    {
        $this->amount += $amount;
        $this->save(); 
    }

    public static function createAccountByPaymentMethod($paymentMethod, $user)
    {
        $users = User::user($user)->whereIn('role', ['vendor', 'distributor'])->get();
        foreach ($users as $key => $user) {
            $data = [
                'operate_user_id' => $user->id,
                'user_id' => $user->getParentId(),
                'amount' => 0.00,
                'payment_method_id' => $paymentMethod->id,
            ];
            self::create($data);
        }
    }

    public static function createAccountByUser($user)
    {
        $paymentMethods = PaymentMethod::user($user)->active()->get();
        foreach ($paymentMethods as $key => $paymentMethod) {
            $data = [
                'operate_user_id' => $user->id,
                'user_id' => $user->getParentId(),
                'amount' => 0.00,
                'payment_method_id' => $paymentMethod->id,
            ];
            self::create($data);
        }
    }


}
