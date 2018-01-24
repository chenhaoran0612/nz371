<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\User;
use App\UserGroup;
use App\PaymentMethod;
use App\UserAccount;
use App\TransactionLog;
use App\Alipay\AlipaySubmit;
use App\Alipay\AlipayNotify;
use ElfSundae\Laravel\Hashid\Facades\Hashid;
use App\OperationLog;
use Carbon\Carbon;
use App\SystemInfo;


class AccountController extends Controller
{
    const REQUIRED = '*为必填项';
    const OPERATE_SUCCESS = '操作成功';
    const OPERATE_FAIL = '操作失败';
    const AUTH_LOWER = '未找到对应信息或权限不足';
    const DELETE_SUCCESS = "删除成功";
    const FORMATE_ERROR = "格式错误";
    const PAYMENT_SUCCESS = "支付成功";
    const CHAR_LONG = '字符串过长';
    const PAYMENT_PASSWORD_ERROR = "支付密码错误";

    /**
     * 支付方式配置
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentMethod(Request $request)
    {
        $user =Auth::user();
        $data['user'] = $user;
        $data['paymentMethods'] = PaymentMethod::user($user)->get();
        $data['payment'] = PaymentMethod::user($user)->get()->toArray();
        return view('account.payment_method', $data);
    }

    /**
     * 账户管理新增
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function paymentMethodCreate(Request $request)
    {
        $user = Auth::user();
        $data['paymentMethodMap'] = PaymentMethod::paymentMethodMap();
        $data['currencyMap'] = PaymentMethod::currencyMap();
        return view('account.payment_method_create', $data);
    }

    public function getPaymentMethod(Request $request)
    {
        return PaymentMethod::paymentMethodConfig($request->payment_method);
    }

    //经销商创建
    public function paymentMethodCreateSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();
        if ($user->isVnedorOrDistributor()) {
            return ['result'=>false, 'message' => self::AUTH_LOWER];
        }
        $data = $request->all();
        $message = $this->paymentMethodValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }
        
        $data['user_id'] = $userId;
        $data['status'] = 1;
        $paymentMethod = PaymentMethod::create($data);
        userAccount::createAccountByPaymentMethod($paymentMethod, $user);
        OperationLog::createFromRequest($request);
        return ['result'=>true, 'message' => self::OPERATE_SUCCESS];
    }

    public function paymentMethodValidator($data){
        $validate = 
        [   'name' => 'required|max:255',
            'method' => 'required|max:255',
            'currency' => 'sometimes|required|max:255',
        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'max' => self::CHAR_LONG,
        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }
        return true;
    }

    public function paymentMethodSave(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        if (!$paymentmethod = PaymentMethod::user($user)->whereId($id)->first()) {
            return back();
        }
        $data['paymentMethodMap'] = PaymentMethod::paymentMethodMap();
        $data['currencyMap'] = PaymentMethod::currencyMap();
        $data['paymentMethodConfig'] = PaymentMethod::paymentMethodConfig($paymentmethod->method);
        $data['paymentmethod'] = $paymentmethod;
        return view('account.payment_method_save', $data);
    }

    public function paymentMethodSaveCreate(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();
        if ($user->isVnedorOrDistributor()) {
            return ['result'=>false, 'message' => self::OPERATE_FAIL];
        }
        $data = $request->all();
        $data['id'] = Hashid::decode($request->id);
        $message = $this->paymentMethodValidator($data);
        if (is_array($message)) {
            return ['result' => false, 'message'=>$message];
        }

        $paymentMethod = PaymentMethod::user($user)->whereId($data['id'])->first();
        if (!$paymentMethod) {
            return ['result' => false, 'message'=> self::AUTH_LOWER];
        }
        $paymentMethod->update($data);
        OperationLog::createFromRequest($request);
        return ['result'=>true, 'message' => self::OPERATE_SUCCESS];
    }

    public function paymentMethodStatusSave(Request $request)
    {
        $user = Auth::user();
        $userId = $user->getParentId();

        if ($user->isVnedorOrDistributor()) {
            return ['result'=>false, 'message' => self::OPERATE_FAIL];
        }
        $id = Hashid::decode($request->id);
        $paymentMethod = PaymentMethod::user($user)->whereId($id)->first();
        if (!$paymentMethod) {
            return ['result' => false, 'message'=> self::AUTH_LOWER];
        }

        $paymentMethod->update(['status' => $request->status]);
        OperationLog::createFromRequest($request);
        return ['result'=>true, 'message' => self::OPERATE_SUCCESS];
    }

    /**
     * 账户列表
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function accountUser(Request $request)
    {
        $user = Auth::user();
        $searchs['payment_method_id'] = Hashid::decode($request->payment_method_id);

        $conditions = [
            'payment_method_id' => '=',
        ];

        $data['userAccounts'] = UserAccount::whereHas('user', function($query) use($request){
            $searchs = $request->only('user_code', 'name', 'role');
            $conditions = [
                'user_code' => '=',
                'name' => 'like',
                'role' => '='
            ];
            $query->search($searchs, $conditions);
        })->search($searchs, $conditions)->user($user)->orderBy('operate_user_id', 'desc')->paginate(20);

        $data['userAccounts']->appends(array_filter($request->all()));
        $data['paymentMethods'] = PaymentMethod::user($user)->get();
        $data['actionMap'] = PaymentMethod::actionMap();
        $data['paymentMethodMap'] = PaymentMethod::paymentMethodMap();
        return view('account.user_account', $data);
    }

    /**
     * 用户金额修改
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function amountEdit(Request $request)
    {
        $user = Auth::user();
        //验证子账户 注册账户
        if ($user->isVnedorOrDistributor()) {
            return ['result'=>false, 'message' => self::AUTH_LOWER];
        }
        $data = $request->all();
        $data['id'] = Hashid::decode($request->id);

        
        $validate = 
        [
            'id' => 'required',
            'payment_password' => 'required',
            'amount' => 'required|numeric',
            'action' => 'required',
            'payment_method' => 'required',
            'out_trade_no' => 'required',

        ];
        $message = 
        [
            'required' => self::REQUIRED,
            'numeric' => self::FORMATE_ERROR,

        ];
        $validator = Validator::make($data, $validate, $message);
        if ($validator->fails()) {
            return ['result' => false, 'message' => $validator->errors()->toArray()];
        }

        //验证支付密码
        $password = $request->get('payment_password');
        $res = SystemInfo::validatePaymentPassword($user , $password);
        if(!$res){
            return ['result' => false ,'message' => self::PAYMENT_PASSWORD_ERROR];
        }

        $userAccount = UserAccount::whereId($data['id'])->user($user)->first();
        if (!$userAccount) {
            return ['result' => false, 'message'=> self::AUTH_LOWER];
        }

        //消费
        if ($data['action'] == 'consumption') {
            $data['amount'] = -$data['amount'];
        }
        unset($data['id']);
        $data = [
            'action' => $data['action'],
            'payment_method' => $data['payment_method'],
            'out_trade_no' => $data['out_trade_no'],
            'amount' => $data['amount'],
            'operate_user_id' => $user->id,
            'user_id' => $user->getParentId(),
            'status' => 1,
            'paid_at' => Carbon::now(),
            'user_account_id' => $userAccount->id,
            'payment_method_id' => $userAccount->payment_method_id,
        ];
        TransactionLog::create($data);
        $userAccount->userAmountAdd($data['amount']);
        return ['result'=>true, 'message' => self::OPERATE_SUCCESS];
    }

    public function distributor(Request $request)
    {
        $user = Auth::user();
        if (!$user->isDistributor()) {
            return back();
        }
        $data['userAccounts'] = UserAccount::where('operate_user_id', $user->id)->user($user)->paginate(20);
        $data['paymentMethods'] = PaymentMethod::user($user)->get();
        return view('account.account_vendor', $data);

    }

    public function vendor(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVendor()) {
            return back();
        }
        $data['userAccounts'] = UserAccount::where('operate_user_id', $user->id)->user($user)->paginate(20);
        $data['paymentMethods'] = PaymentMethod::user($user)->get();
        return view('account.account_vendor', $data);
    }

    /**
     * 注册用户交易详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function userTransactionLog(Request $request)
    {
        $user =Auth::user();
        if ($user->isVnedorOrDistributor()) {
            return back();
        }
        $searchs = $request->only('action', 'payment_method', 'payment_method_id');
        $searchs['payment_method_id'] = Hashid::decode($request->payment_method_id);
        $conditions = [
            'action' => '=',
            'payment_method' => '=',
            'payment_method_id' => '=',
        ];

        $role = $request->role;
        $user_id = Hashid::decode($request->user_id);
        $data['user'] = $user->getParent();
        $data['transactionLogs'] = TransactionLog::whereHas('userAccount', function($query) use($user_id){
            $user_id ? $query->where('operate_user_id', $user_id) : '';
        })->whereHas('UserAccount',function($query) use($role){
            $query->whereHas('user', function($query) use($role){
                $role?$query->where('role', $role) : '';
            });
        })->search($searchs, $conditions)->user($user)->orderBy('id', 'desc')->paginate(50);
        $data['paymentMethodMaps'] = PaymentMethod::paymentMethodMap();
        $data['actionMaps'] = PaymentMethod::actionMap();
        $data['paymentMethods'] = PaymentMethod::user($user)->active()->get();
        $data['users'] = User::user($user)->whereIn('role', ['distributor', 'vendor'])->get();

        return view('account.user_transaction_log', $data);
    }

    /**
     * 经销商交易详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function distributorTransactionLog(Request $request)
    {
        $user =Auth::user();
        if (!$user->isDistributor()) {
            return back();
        }
        $searchs = $request->only('action', 'payment_method',  'payment_method_id');
        $searchs['payment_method_id'] = Hashid::decode($request->payment_method_id);
        $conditions = [
            'action' => '=',
            'payment_method' => '=',
            'payment_method_id' => '=',
        ];
        
        $data['user'] = $user->getParent();
        $userAccount = UserAccount::where('operate_user_id', $user->id)->user($user)->pluck('id')->toArray();
        $data['transactionLogs'] = TransactionLog::search($searchs, $conditions)->whereIn('user_account_id', $userAccount)->orderBy('id', 'desc')->paginate(20);
        $data['paymentMethodMaps'] = PaymentMethod::paymentMethodMap();
        $data['actionMaps'] = PaymentMethod::actionMap();
        $data['paymentMethods'] = PaymentMethod::user($user)->active()->get();

        return view('account.vendor_transaction_log', $data);
    }

    /**
     * 供应商交易详情
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function vendorTransactionLog(Request $request)
    {
        $user =Auth::user();
        if (!$user->isVendor()) {
            return back();
        }

        $searchs = $request->only('action', 'payment_method', 'payment_method_id');
        $searchs['payment_method_id'] = Hashid::decode($request->payment_method_id);
        $conditions = [
            'action' => '=',
            'payment_method' => '=',
            'payment_method_id' => '='
        ];

        $data['user'] = $user->getParent();
        $userAccount = UserAccount::where('operate_user_id', $user->id)->user($user)->pluck('id')->toArray();
        $data['transactionLogs'] = TransactionLog::search($searchs, $conditions)->whereIn('user_account_id', $userAccount)->orderBy('id', 'desc')->paginate(20);
        $data['paymentMethodMaps'] = PaymentMethod::paymentMethodMap();
        $data['actionMaps'] = PaymentMethod::actionMap();
        $data['paymentMethods'] = PaymentMethod::user($user)->active()->get();

        return view('account.vendor_transaction_log', $data);
    }

    /**
     * 充值列表
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function onlinePay(Request $request)
    {
        $user =Auth::user();
        $data['payments'] = PaymentMethod::user($user)->active()->get();
        return view('account.onlinepay', $data);
    }

    /**
     * 账号充值
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function recharge(Request $request)
    {
        $user = Auth::user();
        $id = Hashid::decode($request->id);
        $paymentMethod = PaymentMethod::whereId($id)->user($user)->first();
        if (!$paymentMethod) {
            return back();
        }

        // if ($data['amount'] < 0.1) {
        //     $result = ['result'=> false, 'message' => self::PAYMENT_AMOUNT_ERROR];
        //     return redirect('/user/transaction')->with($result);
        // }
        $amount = $request->amount;
        switch ($paymentMethod->method) {
            case 'alipay':
                $out_trade_no = "ixtron-".date('YmdHis');
                $parameter = $this->alipayConfig($paymentMethod, $out_trade_no, $amount);
                $url = $this->alipayapi($parameter);
                TransactionLog::transactionLogCreate(__FUNCTION__, $paymentMethod, $amount, $out_trade_no, $user);
                OperationLog::createFromRequest($request);
                Header("Location: $url");
                break;
            
            default:
                $result = ['result'=> false, 'message' => self::PAYMENT_ERROR];
                return redirect('/account/transaction/onlinepay')->with($result);
                break;
        }
    }

    public function alipayConfig($paymentMethod, $out_trade_no = '', $total_fee = '')
    {
        $alipay_config = $paymentMethod->config;
        // 参数初始化
        $subject = 'Kirin充值';
        $body = "Kirin充值";

        $parameter = array(
                "service"       => 'create_forex_trade',
                "partner"       => $alipay_config['partner'],
                "notify_url"    => 'http://ixtron.kirin2.com/alipay/notify_url',
                "return_url"    => 'http://ixtron.kirin2.com/alipay/state',
                "out_trade_no"  => $out_trade_no,
                "subject"   => $subject,
                "total_fee" => $total_fee,
                "body"  => $body,
                "currency" => $paymentMethod->currency,
                "_input_charset" => 'utf-8',
                "product_code" => 'NEW_OVERSEAS_SELLER',
                'sign_type' => 'MD5',
                "key" => $alipay_config['key'],
                // 'payment_method_id' => $paymentMethod->id,
        );
        return $parameter;
    }

    //国际支付宝
    public function alipayapi($parameter)
    {
        $alipaySubmit = new AlipaySubmit($parameter);
        return $alipaySubmit->alipay_gateway_new.$alipaySubmit->buildRequestParaToString($parameter);
    }

    //支付宝回调地址
    public function alipayState(Request $request)
    {
        //http://localhost:3000/alipay/state?sign=adb9a024059dc846de8b24d1754003fd&trade_no=2018011721001003920551661160&total_fee=0.01&sign_type=MD5&out_trade_no=ixtron-20180117055015&trade_status=TRADE_FINISHED&currency=USD

        // $alipay_config = config('alipay.data');

        $transactionLog = TransactionLog::where('out_trade_no', $request->out_trade_no)->where('amount', $request->total_fee)->unpayment()->first();
        if (!$transactionLog) {
            $result = ['result'=> true, 'message' => self::PAYMENT_SUCCESS];
            return redirect('/account/transaction/onlinepay')->with($result);
        }
        
        $paymentMethod = PaymentMethod::whereId($transactionLog->payment_method_id)->first();
        $alipay_config = $this->alipayConfig($paymentMethod);

        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {
            if ($request->currency != $paymentMethod->currency || $request->trade_status != 'TRADE_FINISHED') {
                $result = ['result'=> false, 'message' => self::PAYMENT_FAIL];
                return redirect('/account/transaction/onlinepay')->with($result);
            }

            //付款记录确认
            $transactionLog->makePayment($request->trade_no);

            $userAccount = UserAccount::where('operate_user_id', $transactionLog->operate_user_id)->where('payment_method_id', $paymentMethod->id)->first();

            $userAccount->userAmountAdd($request->total_fee);

            $result = ['result'=> true, 'message' => self::PAYMENT_SUCCESS];
            $user = $transactionLog->operateUser;
            if ($user->isVendor()) {
                return redirect('/account/vendor')->with($result);
            } else {
                return redirect('/account/distributor')->with($result);
            }

        }else{
            return "参数错误！";
        }
    }

    public function alipayNotifyUrl(Request $request)
    {
        $data = $request->all();
        if(empty($data)) {//判断POST来的数组是否为空
            return 'failure';
        }else {

            $transactionLog = TransactionLog::where('out_trade_no', $request->out_trade_no)->where('amount', $request->total_fee)->unpayment()->first();
            if (!$transactionLog) {
                return 'failure';
            }
            
            $paymentMethod = PaymentMethod::whereId($transactionLog->payment_method_id)->first();
            $alipay_config = $this->alipayConfig($paymentMethod);
            $alipayNotify = new AlipayNotify($alipay_config);
            //验证MD5的结果
            $isSign = $alipayNotify->getSignVeryfy($data, $data["sign"]);
            
            //获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
            $responseTxt = 'failure';
            if (! empty($data["notify_id"])) {
                $responseTxt = $alipayNotify->getResponse($data["notify_id"]);
            }
            
            //验证
            //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
            //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
            if (preg_match("/true$/i",$responseTxt) && $isSign) {

                if ($request->currency != $paymentMethod->currency || $request->trade_status != 'TRADE_FINISHED') {
                    return 'failure';
                }
                
                //付款记录确认
                $transactionLog->makePayment($data['trade_no']);

                $userAccount = UserAccount::where('operate_user_id', $transactionLog->operate_user_id)->where('payment_method_id', $paymentMethod->id)->first();

                $userAccount->userAmountAdd($request->total_fee);

                return 'success';

            } else {
                return 'failure';
            }
        }
    }
}
