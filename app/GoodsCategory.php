<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsCategory extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user->getParentId());
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

}
