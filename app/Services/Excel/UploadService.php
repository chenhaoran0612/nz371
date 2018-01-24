<?php 
namespace App\Services\Excel;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\Excel\MyReadFilter;

class UploadService
{
    
    private $path;
    private $th;
    private $reader;
    private $sheetName;

    public function __construct($path , $th , $sheetName = 'data' , $fileType = 'Xlsx')
    {
        $this->path = $path;
        $this->th = $th;
        $this->sheetName = $sheetName;
        $this->reader = IOFactory::createReader($fileType);
        $this->reader->setReadDataOnly(true);
        $this->reader->setLoadSheetsOnly($sheetName);
    }

    /**
     * [get description] 获取上传文件对应数组
     * @return [type] [description]
     */
    public function getUploadData()
    {

        //注释代码可以设置excel只阅读哪些
        // $filterSubset = new MyReadFilter( 2 , 9999 ,['A','B' ,'C' ,'D' ,'E']);
        // $this->reader->setReadFilter($filterSubset);
        $uploadData = [];
        $file = $this->reader->load($this->path);
        $arrayData = $file->getSheetByName($this->sheetName)->toArray();

        //获取th数组
        $thArray = $this->getThData();
        //第一行为th数据 不进行封装
        for ($i=1; $i < count($arrayData); $i++) { 
            $one = $arrayData[$i];
            $oneRes = [];
            foreach ($one as $key => $value) {
                if(!isset($thArray[$key])) continue;
                $oneRes[$thArray[$key]] = $value;
            }
            array_push($uploadData, $oneRes);
        }
        return $uploadData;

    }

    /**
     * [getThData description] 获取th数组
     * @return [type] [description]
     */
    public function getThData()
    {
        $res = [];
        foreach ($this->th as $one) {
            array_push($res, $one);
        }
        return $res;
    }


}