<?php

namespace App\Http\Controllers;

use App\Models\LedgerModel;
use App\Models\FabricInwardReportModel;
use Illuminate\Http\Request;
use App\Models\FabricInwardDetailModel;
use Illuminate\Support\Facades\DB;

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

    $fdate = $request->fdate;
    $tdate = $request->tdate;

    $FabricInwardDetail = FabricInwardReportModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
      ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
      ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
      ->whereBetween('inward_master.in_date', [$request->fdate, $request->tdate])
      ->get(['inward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'cp_master.cp_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);

    $in_code = 0;
    foreach ($FabricInwardDetail as $rowfetch) {

      $in_code = $rowfetch->in_code;
    }


    $FabricInwardDetailstable = FabricInwardDetailModel::join('item_master', 'item_master.item_code', '=', 'inward_details.item_code')

      ->join('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
      ->where('inward_details.in_code', '=', $in_code)->get(['inward_details.*', 'item_master.color_name', 'item_master.item_description', 'item_master.dimension', 'part_master.part_name']);



    return view('rptFabricInward', compact('FabricInwardDetail', 'FabricInwardDetailstable'));
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




  public function GetFabricGRNReport()
  {
    $LedgerList = LedgerModel::where('ledger_master.delflag', '=', '0')->where('ledger_master.Ac_code', '>', '39')->get();


    return view('GetFabricGRNReport', compact('LedgerList'));
  }



  public function FabricGRNFilterReport(Request $request)
  {

    $fdate = $request->fdate;
    $tdate = $request->tdate;
    $Ac_code = $request->Ac_code;
    $pur_code = $request->pur_code;





    if ($pur_code = '' && $Ac_code != '') {  // DB::enableQueryLog();
      $FabricInwardDetails = FabricInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
        ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
        ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
        ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
        ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
        ->where('inward_details.Ac_code', $Ac_code)->whereBetween('in_date', array($fdate, $tdate))
        ->get(['inward_details.*', 'cp_master.cp_name', 'part_master.part_name', 'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'quality_master.quality_name', 'rack_master.rack_name']);


      $FabricGRNTotal = FabricInwardDetailModel::select(DB::raw('sum(meter) as TotalMeter'), DB::raw('sum(round(item_rate*meter)) as TotalAmount'))->where('inward_details.Ac_code', $Ac_code)->whereBetween('in_date', array($fdate, $tdate))
        ->get();
    } elseif ($pur_code = !'' && $Ac_code != '') {
      $FabricInwardDetails = FabricInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
        ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
        ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
        ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
        ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
        ->where('inward_details.Ac_code', $Ac_code)->whereBetween('in_date', array($fdate, $tdate))->where('po_code', $pur_code)
        ->get(['inward_details.*', 'cp_master.cp_name', 'part_master.part_name', 'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'quality_master.quality_name', 'rack_master.rack_name']);


      $FabricGRNTotal = FabricInwardDetailModel::select(DB::raw('sum(meter) as TotalMeter'), DB::raw('sum(round(item_rate*meter)) as TotalAmount'))
        ->where('inward_details.Ac_code', $Ac_code)->whereBetween('in_date', array($fdate, $tdate))->where('po_code', $pur_code)
        ->get();
    } else {
      $FabricInwardDetails = FabricInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
        ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
        ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
        ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
        ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
        ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
        ->whereBetween('in_date', array($fdate, $tdate))
        ->get(['inward_details.*', 'cp_master.cp_name', 'part_master.part_name', 'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'quality_master.quality_name', 'rack_master.rack_name']);

      $FabricGRNTotal = FabricInwardDetailModel::select(DB::raw('sum(meter) as TotalMeter'), DB::raw('sum(round(item_rate*meter)) as TotalAmount'))
        ->whereBetween('in_date', array($fdate, $tdate))
        ->get();
    }
    //   DB::enableQueryLog();

    //     $query = DB::getQueryLog();
    //   $query = end($query);
    //   dd($query);
    return view('FabricGRNFilterReportPrint', compact('FabricInwardDetails', 'FabricGRNTotal'));
  }

  public function FabricGRNPrint($in_code)
  {
    $FirmDetail = DB::table('firm_master')->where('delflag', '=', '0')->first();
    $in_code = base64_decode($in_code);
    $FabricInwardMaster = FabricInwardReportModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
      ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
      ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
      ->where('inward_master.in_code', $in_code)
      ->get(['inward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'cp_master.cp_name', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address']);
    return view('PrintFabricGRNGST', compact('FabricInwardMaster', 'FirmDetail'));
  }

  public function FabricGRNPrintView($in_code)
  {
    $FirmDetail = DB::table('firm_master')
      ->where('delflag', '=', '0')
      ->first();

    $in_code = base64_decode($in_code);

    $FabricInwardMaster = DB::table('inward_master')
      ->join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
      ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
      ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')

      // ✅ Added for Goods Sent / Shipped To
      ->leftJoin('ledger_details', 'inward_master.bill_to', '=', 'ledger_details.sr_no') // Bill To (Sent By)
      ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'inward_master.po_code')
      ->leftJoin('ledger_details as ld', 'ld.sr_no', '=', 'purchase_order.ship_to') // Ship To

      ->where('inward_master.in_code', $in_code)
      ->get([
        'inward_master.*',
        'usermaster.username',
        'ledger_master.Ac_name',
        'ledger_master.gst_no',
        'ledger_master.pan_no',
        'ledger_master.state_id',
        'ledger_master.address',
        'cp_master.cp_name',

        // ✅ Extra fields for display
        'ledger_details.addr1 as bill_to_addr1',
        'ld.addr1 as po_addr1',
        'ld.trade_name',
        'ledger_master.Ac_name as supplier_name'
      ]);

    return view('FabricInwardView', compact('FabricInwardMaster', 'FirmDetail'));
  }
}
