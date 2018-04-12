<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\HolandTest;
use App\HolandQuestion;
use App\HolandTestDetail;
use DB;

class ParseHolandTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-holand-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'parse-holand-test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $holandTests = HolandTest::pending()->get();
        foreach ($holandTests as $holandTest) {
            $ids = $holandTest->result;
            $idArray = explode(',' , $ids);
            $results = HolandQuestion::select(DB::raw(' count(1) as total , type '))->whereIn( 'id' , $idArray)->groupBy('type')->orderBy('total' , 'desc')->get();
            
            $fields = ['user_id' => $holandTest->user_id , 'holand_test_id' => $holandTest->id];
            $conds = ['user_id' => $holandTest->user_id , 'holand_test_id' => $holandTest->id];
            $type = '';
            foreach ($results as $one) {
                $type .= $one['type']; 
                $fields[$one['type']] = $one['total'];
            }
            $type = mb_substr($type, 0 , 3);
            $holandTest->status = 'success';
            $holandTest->type = $type ;
            $holandTest->save();
            HolandTestDetail::updateOrCreate($conds, $fields);
        }
    }
}
