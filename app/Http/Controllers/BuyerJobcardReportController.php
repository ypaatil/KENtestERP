<?php

namespace App\Http\Controllers;

use App\Models\BuyerJobcardReportModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BuyerJobCardDetail;
use App\Models\BuyerJobCardSampleDetail;
use App\Models\BuyerJobCardModel;

class BuyerJobcardReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
       return view('BuyerJobCardReport'); 
        
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
      
     //DB::enableQueryLog();
         $jobcardMaster = BuyerJobCardModel::join('usermaster', 'usermaster.userId', '=', 'job_card_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'job_card_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'job_card_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'job_card_master.job_status_id')
        ->join('brand_master', 'brand_master.brand_id', '=', 'job_card_master.brand_id')
        ->join('season_master', 'season_master.season_id', '=', 'job_card_master.season_id')
        ->join('cp_master', 'cp_master.cp_id', '=', 'job_card_master.cp_id')
        ->whereBetween('job_card_master.po_date', [$request->fdate, $request->tdate])
        ->get(['job_card_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name','fg_master.fg_name','job_status_master.job_status_name','brand_master.brand_name', 'season_master.season_name']);
//dd(DB::getQueryLog());
    
    $po_code=0;
         foreach ($jobcardMaster as $rowfetch){ 
             
                      $po_code=$rowfetch->po_code;

                 }
    
       
         $job_card_detailslist = BuyerJobCardDetail::join('color_master','color_master.color_id', '=', 'job_card_details.color_id')
        ->where('job_card_details.po_code','=', $po_code)->get(['job_card_details.*','color_master.color_name']);
        
        $SampleSetList = BuyerJobCardSampleDetail::join('samples_master','samples_master.sample_id', '=', 'job_card_sample_details.sample_id')
        ->where('job_card_sample_details.po_code','=', $po_code)->get(['job_card_sample_details.*','samples_master.sample_name']);
        
    
    
    
        return view('rptBuyerJobCard', compact('jobcardMaster','job_card_detailslist','SampleSetList'));
        
        
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BuyerJobcardReportModel  $buyerJobcardReportModel
     * @return \Illuminate\Http\Response
     */
    public function show(BuyerJobcardReportModel $buyerJobcardReportModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuyerJobcardReportModel  $buyerJobcardReportModel
     * @return \Illuminate\Http\Response
     */
    public function edit(BuyerJobcardReportModel $buyerJobcardReportModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuyerJobcardReportModel  $buyerJobcardReportModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BuyerJobcardReportModel $buyerJobcardReportModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BuyerJobcardReportModel  $buyerJobcardReportModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(BuyerJobcardReportModel $buyerJobcardReportModel)
    {
        //
    }
}
