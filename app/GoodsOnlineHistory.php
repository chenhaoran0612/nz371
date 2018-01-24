<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsOnlineHistory extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user->getParentId());
    }

    public function scopeVendor($query, $user)
    {
        return $query->where('vendor_id', $user->id);
    }

    public function setAttributeAttribute($value)
    {
        $this->attributes['attribute'] = json_encode($value);
    }

    public function getAttributeAttribute($value)
    {
        return json_decode($value, true);
    }

    public static function createGoodsOnlineHistory($goodsOnline)
    {
        $data = $goodsOnline->toArray();
        $data['goods_online_id'] = $data['id'];
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['deleted_at']);
        return GoodsOnlineHistory::create($data);
    }
}