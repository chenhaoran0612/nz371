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

    public function getLevelMapAttribute()
    {
        return [
            '1' => '一级',
            '2' => '二级',
            '3' => '三级',
            '4' => '四级',
            '5' => '五级',
            // '6' => '六级',
            // '7' => '七级',
            // '8' => '八级',
            // '9' => '九级',
            // '10' => '十级',
        ];
    }


    public function getCreditMapAttribute()
    {
        return [
            '1' => '一级',
            '2' => '二级',
            '3' => '三级',
            '4' => '四级',
            '5' => '五级',
            // '6' => '六级',
            // '7' => '七级',
            // '8' => '八级',
            // '9' => '九级',
            // '10' => '十级',
        ];
    }

    public function qualificationMap()
    {
        return [
            '1' => '一般纳税人(有出口资质)',
            '2' => '一般纳税人(无出口资质)',
            '3' => '非一般纳税人(无出口资质)',

        ];
    }

    public function scopeDistributor($query)
    {
        return $query->where('role','distributor');
    }
    
    public function scopeVendor($query)
    {
        return $query->where('role' , 'vendor');
    }

    public function scopeSubaccount($query)
    {
        return $query->where('role','subaccount');
    }


    public function spiderPermissson()
    {
        $parentUser = self::where('id',$this->getParentId())->first();
        $parentPermissionArr = explode(',',  $parentUser->permission);
        $selfPermissionArr = explode(',',  $this->permission);
        return (in_array('spider_spiderjd',$parentPermissionArr) && in_array('spider_spiderjdsingle', $parentPermissionArr)) ? 1 : 0;
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
            case 'distributor':
                $distributorPermission = config('modelPermission.normal_permission.distributor');
                foreach ($distributorPermission as $key => $distributor) {
                    $permission = array_merge($permission, array_keys($distributor));
                }
                return $this->matchPermission($permission);
            case 'vendor':
                $vendorPermission = config('modelPermission.normal_permission.vendor');
                foreach ($vendorPermission as $key => $vendor) {
                    $permission = array_merge($permission, array_keys($vendor));
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
        // return ['user_distributor' ,'user_vendor' ,'user_subaccount' ,'order_group', 'user_subaccountcreate','user_subaccountedit'];
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

    public function getPaymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod','payment_method_id','id')->withTrashed();
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

    public function isVnedorOrDistributor()
    {
        return ($this->role == 'vendor' || $this->role == 'distributor')? true : false;
    }

    public function isVendor()
    {
        return $this->role == 'vendor' ? true : false;
    }

    public function isDistributor()
    {
        return $this->role == 'distributor' ? true : false;
    }

    public function getSystemId()
    {
        switch ($this->role) {
            case 'vendor':
            case 'distributor':
                return $this->id;
                break;
            case 'subaccount':
                return $this->getParentId();
                break;
            default:
                return $this->getParentId();
                break;
        }
    }

    public function isAdmin()
    {
        return ($this->role == 'register' || $this->role == 'subaccount') ? true : false;
    }

}
