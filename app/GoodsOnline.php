<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsOnline extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user->getParentId());
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function vendor()
    {
        return $this->hasOne(User::class, 'id', 'vendor_id')->withTrashed();
    }

    public function goodsBasic()
    {
        return $this->hasOne(GoodsBasic::class, 'id', 'goods_basic_id')->withTrashed();
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

    public function getOnlineStatus()
    {
        $map = [
            '0' => '审核中',
            '1' => '在线',
            '2' => '下架'
        ];
        return $map[$this->online_status];
    }

    public function getAttributeAttribute($value)
    {
        return json_decode($value, true);
    }

    public function scopeOnline($query)
    {
        return $query->where('online_status', 1);
    }

    public function offOnline()
    {
        $this->online_status = 2;
        $this->save();
    }

    //重新提交
    public function rePush()
    {
        $this->repush = 1;
        $this->save();
    }

    //判断是否在线
    public function isOnline()
    {
        if ($this->online_status == 1) {
            return true;
        }
        return false;
    }

    //是否在审核中
    public function isInApprove()
    {
        if (!$this->item_number) {
            return true;
        }
        if ($this->repush == 1) {
            return true;
        }
        return false;
    }

}
