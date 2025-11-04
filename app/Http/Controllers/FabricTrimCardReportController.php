<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FabricTrimCardMasterModel;
use App\Models\FabricTrimCardDetailModel;

class FabricTrimCardReportController extends Controller
{
    
          public function index()
    {
        
        
       return view('FabricTrimCardReport'); 
        
    }
    
    
     public function store(Request $request)
    {
            $fdate=$request->fdate;
        $tdate=$request->tdate;
      
     //DB::enableQueryLog();
       $FabricTrimbCardList = FabricTrimCardMasterModel::join('usermaster', 'usermaster.userId', '=', 'fabric_trim_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_trim_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_trim_card_master.fg_id')
        ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_trim_card_master.cp_id')
        ->where('fabric_trim_card_master.delflag','=', '0')
        ->get(['fabric_trim_card_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','cp_master.cp_name']);
    // $query = DB::getQueryLog();
//dd(DB::getQueryLog());
    
   
    
        return view('rptFabricTrimCard', compact('FabricTrimbCardList'));
}

    
    
    
    
}
