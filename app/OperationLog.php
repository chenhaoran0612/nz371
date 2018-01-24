<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    protected $guarded = [];

    protected $actionNameMap = [
        'categoryCreate' => '增加品类',
    ];

    public static function createFromRequest($request)
    {
        $user = $request->user();
        $data = $request->all();
        $actionName = $request->route()->getActionName();
        list($controller, $method) = explode('@', $actionName);
        self::create([
            'operator_user_id' => $user->id,
            'user_id' => $user->getParentId(),
            'params' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'controller' => $controller,
            'method' => $method
        ]);
    }

    public static function createFromQueue($user, $params)
    {
        $controller = $params['controller'];
        $method = $params['method'];
        unset($params['controller']);
        unset($params['method']);
        self::create([
            'operator_user_id' => $user->id,
            'user_id' => $user->getParentId(),
            'params' => json_encode($params, JSON_UNESCAPED_UNICODE),
            'controller' => $controller,
            'method' => $method
        ]);
    }

    public static function scopeUser($query, $user)
    {
        return $query->whereIn('user_id', $user->getUserIds());
    }

    public function scopeModule($query ,$controller)
    {
        return $query->where('controller', $controller);
    }

    public function getActionAttribute()
    {
        return isset($this->actionNameMap[ $this->method ]) ? $this->actionNameMap[ $this->method ] : $this->method;
    }

    public function getOperatorAttribute(){
        return User::withTrashed()->whereId( $this->operator_user_id )->value('name');
    }

    public function getUserAttribute(){
        return User::withTrashed()->whereId( $this->user_id )->value('name');
    }

    public function getLogAttribute(){
        $params = json_decode( $this->params , true );
        array_pull( $params , '_token');
        $logData = '日志内容:<br>';
        foreach ($params as $key => $param) {
            if(is_array($param)){
                if (empty($param)) {

                } else {
                    $arrayParam = $key . ':{';
                    foreach ($param as $key =>$value) {
                        $arrayParam .= $key . ':' . $value  .';';
                    }
                    $logData .= $arrayParam .'}<br>';
                }
            } else {
                $logData .= $key . ':' . $param .'<br>';
            }
        }
        return $logData;
    }
}
