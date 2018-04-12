<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Services\Helper\Helper;
use App\HolandQuestion;
use App\HolandTest;
use App\HolandTestDetail;
use App\User;

class TestController extends Controller
{
    
    /**
     * [holand description]学生霍兰德测试入口
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandIndex(Request $request)
    {
    	$user = Auth::user();
    	$data['questions'] = HolandQuestion::get();
    	return view('test.holand' , $data);
    }

    /**
     * [holandSubmit description]霍兰德测试结果提交
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandSubmit(Request $request)
    {
    	$user = Auth::user();
    	$fields = [
    		'user_id' => $user->id,
    		'result' => $request->get('ids')
    	];
    	HolandTest::create($fields);
    	return ['result' => true ,'message' => '操作成功，正在处理'];
    }

    /**
     * [holandAdmin description]教师查看霍兰德测试
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandAdmin(Request $request)
    {
        $user = Auth::user();
        $nickName = $request->get('nick_name');
        $query = HolandTest::orderBy('created_at');
        $userIds = [];
        if($nickName){
            $userIds = User::where('nick_name' , 'like' , '%' . $nickName .'%')->pluck('id');
            $query->whereIn('user_id' , $userIds);
        }
        
        if($user->role == 'student'){
            abort(403);
        }
        $data['holandTests'] = $query->paginate(20);
        $data['holandTests']->appends(array_filter($request->all()));
        return view('test.holand-report-admin' , $data);
    }

    /**
     * [holandAdminDetail description]教师查看霍兰德测试详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandAdminDetail(Request $request)
    {
        $user = Auth::user();
        if($user->role == 'student'){
            abort(403);
        }
        $reportId = $request->get('id');
        $holandTest = HolandTest::where('id' , $reportId )->first();
        if(!$holandTest){
            abort(403);
        }
        $data['holandTest'] = $holandTest;
        $data['resultMaps'] = HolandTest::JOBMAP;
        return view('test.holand-admin-detail' , $data);


    }

    /**
     * [holandReportDetail description]霍兰德报告详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandReportDetail(Request $request)
    {
    	$user  = Auth::user();
    	$reportId = $request->get('id');
    	$holandTest = HolandTest::where('user_id' , $user->id)->where('id' , $reportId )->first();
    	if(!$holandTest){
    		abort(403);
    	}
    	$data['holandTest'] = $holandTest;
    	$data['resultMaps'] = HolandTest::JOBMAP;
    	return view('test.holand-report-detail' , $data);

    }


    /**
     * [holandReport description]霍兰德报告列表
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function holandReport(Request $request)
    {
    	$user = Auth::user();
    	$data['holandTests'] = HolandTest::where('user_id' , $user->id)->get();
    	return view('test.holand-report-index' , $data);
    }

    


}
