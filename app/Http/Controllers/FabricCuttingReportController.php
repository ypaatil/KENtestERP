<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CuttingMasterModel;
use Illuminate\Support\Facades\DB;
use App\Models\CuttingBalanceDetailModel;
use App\Models\CuttingDetailModel;

class FabricCuttingReportController extends Controller
{
    
         public function index()
    {
        
        
       return view('FabricCuttingReport'); 
        
    }
    
    
       public function store(Request $request)
    {
            $fdate=$request->fdate;
        $tdate=$request->tdate;
        
      
     //DB::enableQueryLog();
      $CuttingMasterList = CuttingMasterModel::join('usermaster', 'usermaster.userId', '=', 'cutting_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'cutting_master.Ac_code')
        ->join('ctable_master', 'ctable_master.table_id', '=', 'cutting_master.table_id')
       ->whereBetween('cutting_master.cu_date', [$request->fdate, $request->tdate])
        ->get(['cutting_master.*','usermaster.username','ledger_master.Ac_name','ctable_master.table_name']);
//dd(DB::getQueryLog());
    
    $cu_code=0;
         foreach ($CuttingMasterList as $rowfetch){ 
             
                      $cu_code=$rowfetch->cu_code;

                 }
    

    
        return view('rptFabricCutting', compact('CuttingMasterList'));
}
    
    
    
    
    
    
}
