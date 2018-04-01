<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\UploadManager;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client as GuzzleClient;
use File;

class Qiniu
{

    // 需要填写你的 Access Key 和 Secret Key
    protected $accessKey = 'X65WK2RdjyP8rS_ySlY-eEHHDRj8nDLa09jIVaZz';

    protected $secretKey = 'iAkNrC5cWTBAOnk8D7FUvTAbIv8Q7B29QGoiaGb5';

    protected $domainName = 'http://p6hkyfbga.bkt.clouddn.com/';

    protected $token;

    protected $client;

    //获取QiniuToken
    public function __construct()
    {
        // 构建鉴权对象
        $auth = new QiniuAuth($this->accessKey, $this->secretKey);
        // 要上传的空间
        $bucket = 'image-upload';
        $this->token = $auth->uploadToken($bucket, null, 3600);
        $this->client = new GuzzleClient;
    }

    /**
     * [upload description]
     * @param   $file 文件对象实体
     * @return [array]       []
     */
    public function upload($file, $originalName = false)
    {
        $extension = $file->getClientOriginalExtension();
        return $this->uploadToQiniu($file, $extension, $originalName);
    }

    // 文件的实际地址
    public function uploadFileByPath($filePath)
    {
        $path = parse_url($filePath);
        $extension = pathinfo($path['path'])['extension'];
        return $this->uploadToQiniu($filePath, $extension);
    }

    private function uploadToQiniu($file, $extension, $originalName = false)
    {
        if ($originalName) {
            $name = $file->getClientOriginalName();
            $key = date('Y/m/', time()).$name;
        } else {
            // 上传到七牛后保存的文件名
            $key = date('Y/m/', time()).md5($file.time()).'.'.$extension;
        }

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($this->token, $key, $file);
        if ($err !== null) {
            return ['result' => false, 'message' => $err->message()];
        } else {
            $ret['url'] = $this->domainName.$ret['key'];
            $ret['originalName'] = isset($name)? $name : '';
            return ['result' => true, 'message' => $ret];
        }
    }

    /**
     * 更新外站单个链接
     * @param  [url] $filePath 文件绝对地址
     * @return [type]           [description]
     */
    public function uploadOutsideSingleUrl($filePath)
    {
        $suffix = pathinfo($filePath, PATHINFO_EXTENSION);
        $suffix = explode('?', $suffix);
        $random = str_random(10);
        $file = 'uploads/goods/files/'.$random.'.'.$suffix[0];
        $this->client->get($filePath, ['save_to' => public_path($file)]);
        $result = $this->uploadFileByPath(public_path($file));
        File::delete(public_path($file));

        if ($result['result']) {
            return $result['message']['url'];
        } else {
            return '';
        }
    }

    /**
     * 更新网站多个链接
     * @param  [array] $filePathArray 文件绝对地址
     * @return [type]                [description]
     */
    public function uploadOutsideMultipleUrl($filePathArray)
    {
        try{
            foreach ($filePathArray as $filePath) {
                $imageUrl[] = $this->uploadOutsideSingleUrl($filePath);
            }
            $imageUrl = array_filter($imageUrl);
            return ['result' => true, 'imageUrl' => $imageUrl];
        }catch(\Exception $e){
            return ['result' => false, 'message' => $e->getMessage()];
        }

    }
}
