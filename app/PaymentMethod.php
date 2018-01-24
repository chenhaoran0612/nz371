<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function paymentMethodMap()
    {
        return [
            'alipay' => '国际支付宝',
            'bank_transfer' => '银行转账',
            'system_settlement' => '系统结算',
            'other' => '其他',
        ];
    }

    public static function actionMap()
    {
        return [
            'recharge' => '充值',
            'consumption' => '消费'
        ];
    }

    public function getPaymentMethodValueAttribute()
    {
        return $this->paymentMethodMap()[$this->method];
    }

    public static function paymentMethodConfig($method)
    {
    	$map = [
    		'alipay' => [
    			'partner' => 'partner',
    			'key' => 'key',
                // 'currency' => '币种',
    			// 'notify_url' => 'notify_url',
    			// 'return_url' => 'return_url',
    			// 'sign_type' => 'sign_type',
    			// 'input_charset' => 'input_charset',
    			// 'cacert' => 'cacert',
    			// 'transport' => 'transport',
    			// 'service' => 'service',
    		],
    	];
    	return isset($map[$method]) ? $map[$method] : [];
    }

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user->getParentId());
    }

    public function getConfigAttribute($value)
    {
        return json_decode($value, TRUE);
    }

    public function setConfigAttribute($value)
    {
        $this->attributes['config'] = json_encode($value);
    }

    public static function getPaymentMethodId($user, $method)
    {
        $paymentMethod = self::user($user)->where('method', $method)->first();

        return $paymentMethod ? $paymentMethod->id: '';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function currencyMap()
    {
        return [
            'USD',
            'EUR',
            'GBP',
            'CNY'
        ];
    }
}

