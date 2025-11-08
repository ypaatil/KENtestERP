<?php

namespace App\Http\Controllers;

use App\Models\FabricOutwardModel;
use Illuminate\Http\Request;
use App\Models\FabricOutwardDetailModel;
use Illuminate\Support\Facades\DB;
use DateTime;
use DatePeriod;
use DateInterval;

class FabricOutwardReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('FabricOutwardReport');
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

        $FabricOutwardMaster = FabricOutwardModel::join('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')

            ->whereBetween('fabric_outward_master.fout_date', [$request->fdate, $request->tdate])
            ->get(['fabric_outward_master.*', 'usermaster.username', 'ledger_master.Ac_name']);
        // $query = DB::getQueryLog();
        //     $query = end($query);
        //     dd($query);

        $fout_code = 0;
        foreach ($FabricOutwardMaster as $rowfetch) {

            $fout_code = $rowfetch->fout_code;
        }


        $FabricOutwardDetailstable = FabricOutwardDetailModel::join('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code')

            ->join('part_master', 'part_master.part_id', '=', 'fabric_outward_details.part_id')
            ->where('fabric_outward_details.fout_code', '=', $fout_code)->get(['fabric_outward_details.*', 'item_master.color_name', 'item_master.item_description', 'item_master.dimension', 'part_master.part_name']);

        return view('rptFabricOutwardReport', compact('FabricOutwardMaster', 'FabricOutwardDetailstable'));
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

    public function GetFabricInOutStockReportForm()
    {
        return view('GetFabricInOutStockReport');
    }

    public function GetFabricInOutStockSummaryReport()
    {
        return view('GetFabricInOutStockSummaryReport');
    }


    public function FabricInOutStockSummaryReport(Request $request)
    {

        $fdate = $request->fdate;
        $tdate = $request->tdate;

        if ($tdate > date('Y-m-d')) {
            $tdate = date('Y-m-d');
        }


        $period = $this->getBetweenDates($fdate, $tdate);


        $FirmDetail =  DB::table('firm_master')->first();

        return view('FabricInOutStockSummaryReport', compact('period', 'fdate', 'tdate', 'FirmDetail'));
    }


    public  function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];

        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        for (
            $currentDate = $startDate;
            $currentDate <= $endDate;
            $currentDate += (86400)
        ) {

            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }

        return $rangArray;
    }

    public function getFabricInOutStockReport(Request $request)
    {

        $fdate = $request->fdate;
        $tdate = $request->tdate;

        if ($tdate > date('Y-m-d')) {
            $tdate = date('Y-m-d');
        }


        $period = $this->getBetweenDates($fdate, $tdate);

        $FirmDetail =  DB::table('firm_master')->first();

        //   DB::enableQueryLog();

        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        return view('FabricInOutStockPrint', compact('period', 'fdate', 'tdate', 'FirmDetail'));
    }

    public function FabricOutwardPrint($fout_code)
    {
        $FirmDetail =  DB::table('firm_master')->first();
        $fout_code = base64_decode($fout_code);
        //   DB::enableQueryLog();

        $FabricOutwardMaster = FabricOutwardModel::leftJoin('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
            ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->where('fabric_outward_master.fout_code', $fout_code)
            ->get(['fabric_outward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'vendor_purchase_order_master.sales_order_no', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address']);

        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);

        return view('FabricOutwardPrint', compact('FabricOutwardMaster', 'FirmDetail'));
    }

     public function FabricOutwardPrintView($fout_code)
    {
        $FirmDetail =  DB::table('firm_master')->first();
        $fout_code = base64_decode($fout_code);
        //   DB::enableQueryLog();

        $FabricOutwardMaster = FabricOutwardModel::leftJoin('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
            ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->where('fabric_outward_master.fout_code', $fout_code)
            ->get(['fabric_outward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'vendor_purchase_order_master.sales_order_no', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address']);



        return view('FabricOutwardPrintView', compact('FabricOutwardMaster', 'FirmDetail'));
    }



    public function FabricOutwardRollsPrint($fout_code)
    {

        $fout_code = base64_decode($fout_code);
        //   DB::enableQueryLog();
        $FirmDetail =  DB::table('firm_master')->first();
        $FabricOutwardMaster = FabricOutwardModel::join('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
            ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'fabric_outward_master.mainstyle_id', 'left outer')
            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'fabric_outward_master.substyle_id', 'left outer')
            ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_outward_master.fg_id', 'left outer')
            ->where('fabric_outward_master.fout_code', $fout_code)
            ->get(['fabric_outward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'vendor_purchase_order_master.sales_order_no', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address', 'fg_master.fg_name', 'main_style_master.mainstyle_name', 'sub_style_master.substyle_name']);

        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        return view('FabricOutwardRollsPrint', compact('FabricOutwardMaster', 'FirmDetail'));
    }

    public function FabricOutwardRollsPrintView($fout_code)
    {

        $fout_code = base64_decode($fout_code);
        //   DB::enableQueryLog();
        $FirmDetail =  DB::table('firm_master')->first();
        $FabricOutwardMaster = FabricOutwardModel::join('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
            ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'fabric_outward_master.mainstyle_id', 'left outer')
            ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'fabric_outward_master.substyle_id', 'left outer')
            ->join('fg_master', 'fg_master.fg_id', '=', 'fabric_outward_master.fg_id', 'left outer')
            ->where('fabric_outward_master.fout_code', $fout_code)
            ->get(['fabric_outward_master.*', 'usermaster.username', 'ledger_master.Ac_name', 'vendor_purchase_order_master.sales_order_no', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address', 'fg_master.fg_name', 'main_style_master.mainstyle_name', 'sub_style_master.substyle_name']);


        return view('FabricOutwardRollsView', compact('FabricOutwardMaster', 'FirmDetail'));
    }
}
