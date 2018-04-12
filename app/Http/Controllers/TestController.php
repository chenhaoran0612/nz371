<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Services\Helper\Helper;
use App\HolandQuestion;
use App\HolandTest;
use App\HolandTestDetail;

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
