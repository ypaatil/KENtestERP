<?php

namespace App\Http\Controllers;

use App\Models\FabricInwardReportModel;
use Illuminate\Http\Request;
use App\Models\FabricInwardDetailModel;

class FabricInwardReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
      return view('FabricInwardReport'); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
           $fdate=$request->fdate;
           $tdate=$request->tdate;
      
         $FabricInwardDetail = FabricInwardReportModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
         ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
        ->whereBetween('inward_master.in_date', [$request->fdate, $request->tdate])
         ->get(['inward_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
     // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
     
         $in_code=0;
         foreach ($FabricInwardDetail as $rowfetch){ 
             
                      $in_code=$rowfetch->in_code;

                 }
     
     
         $FabricInwardDetailstable = FabricInwardDetailModel::join('item_master','item_master.item_code', '=', 'inward_details.item_code')
             
            ->join('part_master','part_master.part_id', '=', 'inward_details.part_id')
            ->where('inward_details.in_code','=', $in_code)->get(['inward_details.*', 'item_master.color_name','item_master.item_description','item_master.dimension','part_master.part_name']);
     
     
     
         return view('rptFabricInward', compact('FabricInwardDetail','FabricInwardDetailstable'));
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricInwardReportModel  $fabricInwardReportModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricInwardReportModel $fabricInwardReportModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricInwardReportModel  $fabricInwardReportModel
     * @return \Illuminate\Http\Response
     */
    public function edit(FabricInwardReportModel $fabricInwardReportModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricInwardReportModel  $fabricInwardReportModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FabricInwardReportModel $fabricInwardReportModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardReportModel  $fabricInwardReportModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(FabricInwardReportModel $fabricInwardReportModel)
    {
        //
    }
    
    
    
    
    
    
    
     public function FabricGRNPrint($in_code)
    {
        
         $in_code=base64_decode($in_code);
         $FabricInwardMaster = FabricInwardReportModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
         ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
         ->where('inward_master.in_code', $in_code)
         ->get(['inward_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name','ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('PrintFabricGRNGST', compact('FabricInwardMaster'));
      
    }
    
    
    
    
    
    
    
     
}
