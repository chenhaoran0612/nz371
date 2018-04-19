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
use App\MbtiTest;

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
    	$data['questions'] = HolandQuestion::inRandomOrder()->get();
    	return view('test.holand' , $data);
    }

    /**
     * [ImtiIndex description]学生IMTI测试入口
     * @param Request $request [description]
     */
    public function mbtiIndex(Request $request)
    {
        $user = Auth::user();
        $data['questions'] = MbtiTest::questions();
        return view('test.mbti' , $data);
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
     * [mbtiSubmit description]MBTI测试提交
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function mbtiSubmit(Request $request)
    {
        $data = $request->get('data');
        $user = Auth::user();
        $resultArr = array_column($data , NULL ,'type');
        $fields = $this->parseMbtiData($resultArr);
        $fields['user_id'] = $user->id;
        MbtiTest::create($fields);
        return ['result' => true ,'message' => '操作成功'];
    }

    /**
     * [parseMbtiData description]解析MBTI数据
     * @param  [type] $resultArr [description]
     * @return [type]            [description]
     */
    public function parseMbtiData($resultArr)
    {
        if(!isset($resultArr['I'])){
            $resultArr['I']['count'] = 0;
        }
        if(!isset($resultArr['E'])){
            $resultArr['E']['count'] = 0;
        }
        if(!isset($resultArr['S'])){
            $resultArr['S']['count'] = 0;
        }
        if(!isset($resultArr['N'])){
            $resultArr['N']['count'] = 0;
        }
        if(!isset($resultArr['T'])){
            $resultArr['T']['count'] = 0;
        }
        if(!isset($resultArr['F'])){
            $resultArr['F']['count'] = 0;
        }
        if(!isset($resultArr['J'])){
            $resultArr['J']['count'] = 0;
        }
        if(!isset($resultArr['P'])){
            $resultArr['P']['count'] = 0;
        }
        
        $energy = $resultArr['E']['count'] > $resultArr['I']['count'] ? 'E' : 'I'; 
        $apperceive = $resultArr['S']['count'] > $resultArr['N']['count'] ? 'S' : 'N';
        $decision = $resultArr['T']['count'] > $resultArr['F']['count'] ? 'T' : 'F';
        $life = $resultArr['J']['count'] > $resultArr['P']['count'] ? 'J' : 'P';
        $fields = [
            'energy' => $energy,
            'apperceive' => $apperceive,
            'decision' => $decision,
            'life' => $life,
        ];
        return $fields;
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
     * [holandAdmin description]教师查看MBTI测试
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function mbtiAdmin(Request $request)
    {
        $user = Auth::user();
        $nickName = $request->get('nick_name');
        $query = MbtiTest::orderBy('created_at');
        $userIds = [];
        if($nickName){
            $userIds = User::where('nick_name' , 'like' , '%' . $nickName .'%')->pluck('id');
            $query->whereIn('user_id' , $userIds);
        }
        
        if($user->role == 'student'){
            abort(403);
        }
        $data['mbtiTests'] = $query->paginate(20);
        $data['mbtiTests']->appends(array_filter($request->all()));
        return view('test.mbti-report-admin' , $data);
    }

    /**
     * [holandAdminDetail description]教师查看MBTI测试详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function mbtiAdminDetail(Request $request)
    {
        $user = Auth::user();
        if($user->role == 'student'){
            abort(403);
        }
        $reportId = $request->get('id');
        $mbtiTest = MbtiTest::where('id' , $reportId )->first();
        if(!$mbtiTest){
            abort(403);
        }
        $data['mbtiTest'] = $mbtiTest;
        return view('test.mbti-admin-detail' , $data);
    }


    public function mbtiReportDetail(Request $request)
    {
        $user  = Auth::user();
        $reportId = $request->get('id');
        $holandTest = MbtiTest::where('user_id' , $user->id)->where('id' , $reportId )->first();
        if(!$holandTest){
            abort(403);
        }
        $data['mbtiTest'] = $holandTest;
        return view('test.mbti-report-detail' , $data);
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

    public function mbtiReport(Request $request)
    {
        $user = Auth::user();
        $data['mbtiTests'] = MbtiTest::where('user_id' , $user->id)->get();
        return view('test.mbti-report-index' , $data);
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
