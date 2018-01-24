<?php 
namespace App\Services\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DownloadService
{
    
    protected $data;
    protected $fileName;
    protected $th;
    protected $maxNum;
    
    CONST ERROR_MESSAGE = '输入参数有误，请检查是否符合下载规范';
    CONST SUCCESS_MESSAGE = '下载任务添加成功';
    CONST NUM_ERROR = '下载超过最大下载数';

    CONST STATIC_MAP = [
        0 => 'A',1 => 'B',2 => 'C',3 => 'D',4 => 'E',5 => 'F',6 => 'G',7 => 'H',8 => 'I',9 => 'J',10 => 'K',11 => 'L',12 => 'M',13 => 'N',
        14 => 'O',15 => 'P',16 => 'Q',17 => 'R',18 => 'S',19 => 'T',20 => 'U',21 => 'V',22 => 'W',23 => 'X',24 => 'Y',25 => 'Z', 26 => 'AA'
    ];


    /**
     * [__construct description]
     * @param [type]  $fileName [文件名称] '111' 
     * @param [type]  $th       [表头 数组] ['a','b' ,'c']
     * @param [type]  $dataArr  [数据内容]  [ 0 => [ 1 , 2 , 3 ] , 1=> [2,3,4]  , 2 => [3,'' , '5']]
     * @param integer $maxNum   [允许最大行数] number
     */
    public function __construct($fileName , $th , $dataArr , $maxNum = 500)
    {
        $this->data = $dataArr;
        $this->fileName = $fileName;
        $this->th = $th;
        $this->maxNum = $maxNum;
    }


    public function download()
    {

        if(empty($this->data) || empty($this->fileName) || empty($this->th) || empty($this->maxNum)){
            return ['result' => false ,'message' => SELF::ERROR_MESSAGE];
        }
        if(count($this->data) > $this->maxNum){
            return ['result' => false ,'message' => SELF::NUM_ERROR];
        }
        $filePath = $this->downloadData();
        return ['result' => true ,'message' => SELF::SUCCESS_MESSAGE ,'url' => $filePath];
    }


    private function downloadData()
    {
        $path = public_path() . '/download/' . $this->fileName . '.xls' ;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');

        $worksheet = $spreadsheet->getActiveSheet();
        //写入th
        for ($i=0; $i < count($this->th); $i++) { 
            $worksheet->getCell(SELF::STATIC_MAP[$i] . '1')->setValue($this->th[$i]);
        }
        //写入data
        for ($row=0; $row < count($this->data); $row++) {
            //其中某一个行 
            $oneRowData = $this->data[$row];
            for ($one=0; $one < count($oneRowData) ; $one++) { 
                //1 为th因此从row 2开始
                $worksheet->getCell(SELF::STATIC_MAP[$one] . ($row + 2) )->setValue($oneRowData[$one]);
            }
        }
        //写入文件
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save($path);
        return $path;
        
    }

}