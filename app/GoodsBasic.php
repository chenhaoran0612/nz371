<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsBasic extends Model
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

    public function goodsOnline()
    {
        return GoodsOnline::where('goods_basic_id', $this->id)->first();
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

    //新旧程度
    public static function newMap()
    {
    	return [
    		'10'=>'全新',
    		'9' => '九成新',
    		'8' => '八成新',
    		'7' => '七成新',
    		'6' => '六成新',
    		'5' => '五成新',
    		'4' => '四成新',
    		'3' => '三成新',
    	];
    }

    public function getAttributeAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getPushStatus()
    {
        $map = [
            '0' => '未推送',
            '1' => '已推送'
        ];
        return $map[$this->push_status];
    }

    public function push()
    {
        $this->push_status = 1;
        $this->save();
    }

    public function resetStatus()
    {
        $this->push_status = 0;
        $this->save();
    }

}
