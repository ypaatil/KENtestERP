<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricCheckingModel;
use App\Models\FabricCheckingDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\FabricInwardModel;

class FabricCheckingReportController extends Controller
{
      public function index()
    {
        
        
       return view('FabricCheckingReport'); 
        
    }

   public function store(Request $request)
    {
            $fdate=$request->fdate;
        $tdate=$request->tdate;
      
     //DB::enableQueryLog();
         $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->whereBetween('fabric_checking_master.chk_date', [$request->fdate, $request->tdate])
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','ledger_master.gst_no','ledger_master.pan_no','cp_master.cp_name']);
//dd(DB::getQueryLog());
    
    $chk_code=0;
         foreach ($fabricChekingMaster as $rowfetch){$chk_code=$rowfetch->chk_code;}
       
            $FabricChekingdetailslist = FabricCheckingDetailModel::join('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
          ->join('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
          ->where('fabric_checking_details.chk_code','=', $chk_code)->get(['fabric_checking_details.*', 'shade_master.shade_name','part_master.part_name' ]);
        
    
    
        return view('FabricCheckingPrintReport', compact('fabricChekingMaster','FabricChekingdetailslist'));
}



public function FabricCheckPrint($chk_code)
    {
        
         
         $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$chk_code)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
          $GetPO=FabricInwardModel::select('po_code')->where('in_code',$fabricChekingMaster[0]->in_code)->get();
          //$po_code= $GetPO[0]->po_code ? $GetPO[0]->po_code : "-";
      
         return view('FabricCheckingPrintReport', compact('fabricChekingMaster'));
      
    }

    public function FabricCheckPrintView($chk_code)
    {
        
         
         $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$chk_code)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
          $GetPO=FabricInwardModel::select('po_code')->where('in_code',$fabricChekingMaster[0]->in_code)->get();
          //$po_code= $GetPO[0]->po_code ? $GetPO[0]->po_code : "-";
      
         return view('FabricCheckingPrintView', compact('fabricChekingMaster'));
      
    }



}
