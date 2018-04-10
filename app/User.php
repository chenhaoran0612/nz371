<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use ElfSundae\Laravel\Hashid\Facades\Hashid;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $guarded = ['comfirm_password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function menuLoad()
    {
        $allSubnode = config('nodeArr.subnodeArr');
        $allNode = config('nodeArr.nodeArr');
        $allNodeRes = [];
        if ($this->role == 'admin') {
            foreach ($allNode as $key => $node) {
                $allNodeRes[ $node['index'] ] ['setting'] = $node;
                foreach ($allSubnode as $subnode) {
                    if ($key == $subnode['parent_node']) {
                        $allNodeRes[$node['index']]['subnodeArr'][$subnode['index']] = $subnode;
                    }
                }
                ksort($allNodeRes[$node['index']]['subnodeArr']);
            }
            ksort($allNodeRes);
            return $allNodeRes;
        } else {
            $subnodeArr =  $this->getPermissionArr();
            $subnodeResArr = [];
            $allNodeRes = [];
            $nodeArr = [];
            foreach ($subnodeArr as $permission) {
                if(isset($allSubnode[$permission])){
                    $subnodeResArr[$permission] =  $allSubnode[$permission];
                    if(isset($subnodeResArr[$permission]['parent_node'])){
                        if( null != $subnodeResArr[$permission] && null != $subnodeResArr[$permission]['parent_node']){
                            $nodeArr[$subnodeResArr[$permission]['parent_node']]['subnodeArr'][$subnodeResArr[$permission]['index']] = $subnodeResArr[$permission];
                            $nodeArr[$subnodeResArr[$permission]['parent_node']]['setting'] = $allNode[$subnodeResArr[$permission]['parent_node']];
                            ksort($nodeArr[$subnodeResArr[$permission]['parent_node']]['subnodeArr']);
                        }
                        $allNodeRes[$nodeArr[$subnodeResArr[$permission]['parent_node']]['setting']['index']] = $nodeArr[$subnodeResArr[$permission]['parent_node']];
                        ksort($allNodeRes);
                    }
                }
            }
            return $allNodeRes;
        }
    }

    public function getParentId()
    {
        return $this->parent_id ? : $this->id;
    }

    public function getParent()
    {
        return $this->parent_id ? User::find($this->parent_id) : $this;
    }

    public function scopeUser($query, $user)
    {
        return $query->where('parent_id', $user->getParentId());
    }

    public function scopeStudent($query)
    {
        return $query->where('role', 'student');
    }

    public function setUserCode()
    {
        $this->user_code = 'U'.$this->id;
        $this->save();
    }

    public function scopeSearch($query, $searchs, $conditions)
    {
        if (isset($searchs['status'])) {
            $status = $searchs['status'];
        }
        $searchs = array_filter($searchs);
        if (isset($status) && is_numeric($status)) {
            $searchs['status'] = $status;
        }
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

    public function scopeSubaccount($query)
    {
        return $query->where('role','subaccount');
    }

    public function getGroupIdAttribute($value)
    {
        return explode(',', $value);
    }

    public function setGroupIdAttribute($value)
    {
        foreach ($value as $key => $groupId) {
            $value[$key] = Hashid::decode($groupId);
        }
        $this->attributes['group_id'] = implode(',', $value);
    }


    public function getPermissionArr()
    {
        $permission = [];
        switch ($this->role) {
            case 'register':
                $subaccountPermission = config('modelPermission.normal_permission.subaccount');
                foreach ($subaccountPermission as $key => $account) {
                    $permission = array_merge($permission, array_keys($account));
                }
                return $this->matchPermission($permission);
            case 'subaccount':
                $groupId = array_filter($this->group_id);
                if (!empty($groupId)) {
                    $subAccountPermissions = UserGroup::whereIn('id', $groupId)->pluck('permission')->toArray();
                    foreach ($subAccountPermissions as $key => $subAccountPermission) {
                        $permission = array_merge($permission, explode(',', $subAccountPermission));
                    }
                    $permission = array_unique(array_filter($permission));
                    return $this->matchPermission($permission);
                } else {
                    return [];
                }
                break;
            
            default:
                return [];
                break;
        }
    }

    public function matchPermission($permission)
    {
        $relation_permission = config('modelPermission.relation_permission');
        foreach ($permission as $value) {
            if(isset($relation_permission[$value]))
                $permission = array_merge($permission, explode(',', $relation_permission[$value]));
        }
        $public_permission = config('modelPermission.public_permission');
        return array_merge($permission, $public_permission);
    }

    public function getUserType()
    {
        $map = [
            'distributor' => '经销商',
            'vendor' => '供应商',
            'subaccount' => '子账号',
            'register' => '主账户',
        ];
        return isset($map[$this->role])? $map[$this->role] : '';
    }

}
