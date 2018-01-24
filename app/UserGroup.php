<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGroup extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeUser($query, $user)
    {
        return $query->where('user_id', $user->getParentId());
    }

    public function getUserPermissionAttribute()
    {
    	return explode(',', $this->permission);
    }

}
