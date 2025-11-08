<?php

namespace App\Http\Controllers;

use App\Models\TrimsInwardMasterModel;
use App\Models\TrimsInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\RackModel;
use App\Models\LocationModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\PurchaseOrderModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use App\Models\StockAssociationModel;
use Illuminate\Support\Facades\DB;
use Session;
use DataTables;
use Queue;
use DateTime;

date_default_timezone_set("Asia/Kolkata");
setlocale(LC_MONETARY, 'en_IN');

use App\Services\TrimsInOutActivityLog;
use App\Services\TrimsInOutMasterActivityLog;


class TrimsInwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '100')
            ->first();

        //DB::enableQueryLog(); 
        $data = TrimsInwardMasterModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardMaster.Ac_code')
            ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardMaster.po_code')
            ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
            ->leftJoin('ledger_master as LM2', 'LM2.ac_code', '=', 'trimsInwardMaster.buyer_id')
            ->leftJoin('bom_types', 'bom_types.bom_type_id', '=', 'purchase_order.bom_type')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'trimsInwardMaster.userId')
            ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'trimsInwardMaster.po_type_id')
            ->leftJoin(DB::raw('(SELECT po_code, tr_code, COUNT(*) as stock_count FROM stock_association GROUP BY po_code, tr_code) as sa'), function ($join) {
                $join->on('sa.po_code', '=', 'trimsInwardMaster.po_code')
                    ->on('sa.tr_code', '=', 'trimsInwardMaster.trimCode');
            })
            ->where('trimsInwardMaster.delflag', '=', '0')
            ->where('trimsInwardMaster.invoice_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            ->get([
                'trimsInwardMaster.*',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'po_type_master.po_type_name',
                'bom_types.bom_type_name',
                'LM1.ac_short_name as buyer1',
                'LM2.ac_short_name as buyer2',
                DB::raw('COALESCE(sa.stock_count, 0) as stock_count')
            ]);

        //dd(DB::getQueryLog());
        return view('TrimsInwardList', compact('data', 'chekform'));
    }


    public function TrimsInwardShowAll()
    {
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '100')
            ->first();


        $data = TrimsInwardMasterModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardMaster.Ac_code')
            ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardMaster.po_code')
            ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
            ->leftJoin('ledger_master as LM2', 'LM2.ac_code', '=', 'trimsInwardMaster.buyer_id')
            ->leftJoin('bom_types', 'bom_types.bom_type_id', '=', 'purchase_order.bom_type')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'trimsInwardMaster.userId')
            ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'trimsInwardMaster.po_type_id')
            ->leftJoin(DB::raw('(SELECT po_code, tr_code, COUNT(*) as stock_count FROM stock_association GROUP BY po_code, tr_code) as sa'), function ($join) {
                $join->on('sa.po_code', '=', 'trimsInwardMaster.po_code')
                    ->on('sa.tr_code', '=', 'trimsInwardMaster.trimCode');
            })
            ->where('trimsInwardMaster.delflag', '=', '0')
            ->get([
                'trimsInwardMaster.*',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'po_type_master.po_type_name',
                'bom_types.bom_type_name',
                'LM1.ac_short_name as buyer1',
                'LM2.ac_short_name as buyer2',
                DB::raw('COALESCE(sa.stock_count, 0) as stock_count')
            ]);


        return view('TrimsInwardList', compact('data', 'chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='TrimMaster' and c_name='C1'"));
        $LocationList = LocationModel::where('location_master.delflag', '=', '0')->get();
        $JobStatusList = JobStatusModel::where('job_status_master.delflag', '=', '0')->get();
        $firmlist = DB::table('firm_master')->get();

        $RackList = RackModel::where('rack_master.delflag', '=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag', '=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag', '=', '0')->whereIn('ledger_master.bt_id', [1, 2, 4])->get();
        $itemlist = DB::table('item_master')->where('item_master.delflag', '0')->where('item_master.cat_id', '!=', '1')->get();
        $unitlist = DB::table('unit_master')->get();
        $BillToList =  DB::table('ledger_details')->get();

        $POList = PurchaseOrderModel::where('purchase_order.po_status', '=', '1')->where('purchase_order.bom_type', '!=', '1')->get();

        $vendorWorkOrderList = DB::table('vendor_work_order_master')
            ->select('vw_code as code')
            ->where('delflag', 0)

            ->unionAll(
                DB::table('vendor_purchase_order_master')
                    ->select('vpo_code as code')
                    ->where('process_id', 3)
                    ->where('delflag', 0)
            )
            ->get();

        $TGEList = DB::table('trim_gate_entry_master')->where('delflag', '=', 0)->get();
        return view('TrimsInward', compact('firmlist', 'RackList', 'ledgerlist', 'gstlist', 'itemlist', 'code', 'unitlist', 'POTypeList', 'JobStatusList', 'POList', 'LocationList', 'vendorWorkOrderList', 'TGEList', 'BillToList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo '<pre>';print_r($_POST);exit;
        $firm_id = $request->input('firm_id');
        $is_opening = isset($request->is_opening) ? 1 : 0;

        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
            ->where('c_name', '=', 'C1')
            ->where('type', '=', 'TrimMaster')
            ->where('firm_id', '=', 1)
            ->first();

        $TrNo = $codefetch->code . '' . $codefetch->tr_no;
        //echo $TrNo;exit; 
        $sr_no = TrimsInwardMasterModel::max('sr_no');

        $po_code = '';
        if ($is_opening == 1) {
            $po_code = 'OS' . ($sr_no + 1);
        } else {
            $po_code = $request->input('po_code');
        }

        $data = array(
            'trimCode' => $TrNo,
            "po_code" => $po_code,
            "trimDate" => $request->input('trimDate'),
            "invoice_no" => $request->input('invoice_no'),
            "invoice_date" => $request->input('invoice_date'),
            "Ac_code" => $request->input('Ac_code'),
            "po_type_id" => $request->input('po_type_id'),
            "totalqty" => $request->input('totalqty'),
            'total_amount' => $request->total_amount,
            'isReturnFabricInward' => $request->isReturnFabricInward,
            'vw_code' => $request->vw_code,
            "delflag" => 0,
            'is_opening' => $is_opening,
            'location_id' => $request->location_id,
            'tge_code' => $request->tge_code,
            'bill_to' => $request->bill_to,
            "userId" => $request->input('userId')
        );
        $itemcodes = 0;
        $allocate_qtys = 0;

        // Insert
        $value = TrimsInwardMasterModel::insert($data);

        $update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='TrimMaster' AND firm_id=1");
        if ($value) {
            Session::flash('message', 'Insert successfully.');
        } else {
            Session::flash('message', 'Username already exists.');
        }



        $cnt = $request->input('cnt');
        $itemcodes = count($request->item_codes);
        if ($request->allocate_qty != "") {

            $allocate_qtys = count($request->allocate_qty);
        }
        if ($cnt > 0) {

            for ($x = 0; $x < $itemcodes; $x++) {

                $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $po_code)->first();

                $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;

                $data2 = array(
                    'trimCode' => $TrNo,
                    'trimDate' => $request->input('trimDate'),
                    'Ac_code' => $request->input('Ac_code'),
                    "po_code" => $po_code,
                    'item_code' => isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0,
                    'hsn_code' => 0,
                    'unit_id' => isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0,
                    'item_qty' => isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0,
                    'item_rate' => isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0,
                    'amount' => isset($request->amounts[$x]) ? $request->amounts[$x] : 0,
                    'rack_id' => isset($request->rack_ids[$x]) ? $request->rack_ids[$x] : 0,
                    'is_opening' => $is_opening,
                    'buyer_id' => $buyer_id,
                    'tge_code' => $request->tge_code,
                    'location_id' => isset($request->location_id) ? $request->location_id : 0,
                );

                TrimsInwardDetailModel::insert($data2);

                $item_code = isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0;
                $unit_id = isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0;
                $item_qty = isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0;
                $item_rate = isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0;
                $amount = isset($request->amounts[$x]) ? $request->amounts[$x] : 0;
                $rack_id = isset($request->rack_ids[$x]) ? $request->rack_ids[$x] : 0;
                $location_id = isset($request->location_id[$x]) ? $request->location_id[$x] : 0;


                $buyerData = DB::table('purchaseorder_detail')
                    ->select('LM1.ac_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                    ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                    ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.Ac_code')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                    ->where('purchaseorder_detail.pur_code', $po_code)
                    ->where('purchaseorder_detail.item_code', $item_code)
                    ->groupBy('purchaseorder_detail.pur_code')
                    ->get();

                $itemData = DB::table('item_master')->select('item_name', 'item_description')->where('item_code', $item_code)->first();
                $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->input('Ac_code'))->first();

                $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";

                $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;

                if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") {
                    $job_status_id = 1;
                    $po_status = "Moving";
                } else {
                    $job_status_id = 2;
                    $po_status = "Non Moving";
                }


                $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                $item_description = isset($itemData->item_description) ? $itemData->item_description : "";

                DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,buyer_name,suplier_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
                                select "' . $request->trimDate . '","' . $buyerName . '","' . $suplierName . '","' . $po_code . '","' . $item_code . '","' . addslashes($item_name) . '","' . $item_rate . '", "-",
                                        "' . addslashes($item_description) . '", "' . $po_status . '","' . $job_status_id . '", "' . $rack_id . '","' . $request->Ac_code . '","' . $request->Ac_code . '","' . $unit_id . '","' . $TrNo . '","' . $item_qty . '",0,"","' . $amount . '"');
                //dd(DB::getQueryLog());


            }
            for ($y = 0; $y < $allocate_qtys; $y++) {

                $data3 = array(
                    "po_code" => $po_code,
                    "po_date" => $request->input('trimDate'),
                    "tr_code" => $TrNo,
                    "tr_date" => $request->input('trimDate'),
                    'bom_code' => $request->stock_bom_code[$y],
                    'sales_order_no' => $request->sales_order_no[$y],
                    'cat_id' => $request->cat_id[$y],
                    'class_id' => $request->class_id[$y],
                    "item_code" => $request->item_code[$y],
                    'unit_id' => 0,
                    'qty' => $request->allocate_qty[$y],
                    "tr_type" => 1,
                );

                StockAssociationModel::insert($data3);
            }
        }

        return redirect()->route('TrimsInward.index')->with('message', 'Add Record Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {


        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '9')
            ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1', 'lm1.ac_code', '=', 'purchase_order.Ac_code')
            ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
            ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
            ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')
            ->where('purchase_order.delflag', '=', '0')
            ->where('purchase_order.approveFlag', '=', '1')
            ->get(['purchase_order.*', 'usermaster.username', 'lm1.ac_name as ac_name1', 'firm_master.firm_name', 'tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data', 'chekform'));
    }



    public function Disapprovedshow()
    {


        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '9')
            ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1', 'lm1.ac_code', '=', 'purchase_order.Ac_code')
            ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
            ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
            ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')
            ->where('purchase_order.delflag', '=', '0')
            ->where('purchase_order.approveFlag', '=', '2')
            ->get(['purchase_order.*', 'usermaster.username', 'lm1.ac_name as ac_name1', 'firm_master.firm_name', 'tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data', 'chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $JobStatusList = JobStatusModel::where('job_status_master.delflag', '=', '0')->get();
        $firmlist = DB::table('firm_master')->where('firm_master.delflag', '=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag', '=', '0')->whereIn('ledger_master.bt_id', [1, 2, 4])->get();
        $itemlist = DB::table('item_master')->where('item_master.delflag', '0')->where('item_master.cat_id', '!=', '1')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag', '=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag', '=', '0')->get();
        //DB::enableQueryLog();
        $purchasefetch = TrimsInwardMasterModel::find($id);
        //dd(DB::getQueryLog());
        $LocationList = LocationModel::where('location_master.delflag', '=', '0')->get();
        $po_sr_no = DB::select("select sr_no from purchase_order where pur_code='" . $purchasefetch->po_code . "'");
        $POList = PurchaseOrderModel::get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $RackList = RackModel::where('rack_master.delflag', '=', '0')->get();
        // DB::enableQueryLog();
        $detailpurchase = TrimsInwardDetailModel::join('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
            ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardDetail.po_code')
            ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
            ->where('trimCode', '=', $purchasefetch->trimCode)
            ->get(['trimsInwardDetail.*', 'item_master.item_name', 'item_master.item_description', 'purchase_order.bom_code', 'item_master.cat_id', 'item_master.class_id', 'classification_master.class_name']);
        $vendorWorkOrderList = DB::table('vendor_work_order_master')->select('vw_code')->where('delflag', '=', 0)->get();
        $TGEList = DB::table('trim_gate_entry_master')->where('delflag', '=', 0)->get();

        if (strpos($purchasefetch->po_code, "PO/") !== false) {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.pur_code='" . $purchasefetch->po_code . "'");
        } else {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.Ac_code='" . $purchasefetch->Ac_code . "'");
        }

        //dd(DB::getQueryLog()); 
        return view('TrimsInwardEdit', compact('POList', 'RackList', 'purchasefetch', 'po_sr_no', 'firmlist', 'TGEList', 'ledgerlist', 'gstlist', 'itemlist', 'detailpurchase', 'unitlist', 'POTypeList', 'JobStatusList', 'BOMLIST', 'LocationList', 'vendorWorkOrderList', 'BillToList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code, TrimsInOutActivityLog $loggerDetail, TrimsInOutMasterActivityLog $loggerMaster)
    {
        //echo '<pre>';print_R($_POST);exit;
        $is_opening = isset($request->is_opening) ? 1 : 0;
        $po_code = '';
        $allocate_qtys = 0;

        $sr_no = TrimsInwardMasterModel::select('sr_no')->where('trimCode', '=', $request->trimCode)->get();

        if ($is_opening == 1) {
            $po_code = 'OS' . ($sr_no[0]->sr_no);
        } else {
            $po_code = $request->input('po_code');
        }

        $data = array(
            'trimCode' => $request->trimCode,
            "po_code" => $po_code,
            "trimDate" => $request->input('trimDate'),
            "invoice_no" => $request->input('invoice_no'),
            "invoice_date" => $request->input('invoice_date'),
            "Ac_code" => $request->input('Ac_code'),
            "po_type_id" => $request->input('po_type_id'),
            "totalqty" => $request->input('totalqty'),
            'total_amount' => $request->total_amount,
            'isReturnFabricInward' => $request->isReturnFabricInward,
            'vw_code' => $request->vw_code,
            "delflag" => 0,
            'is_opening' => $is_opening,
            'location_id' => $request->location_id,
            'tge_code' => $request->tge_code,
            'bill_to' => $request->bill_to,
            "userId" => $request->input('userId')
        );



        $MasterOldFetch = DB::table('trimsInwardMaster')
            ->select('trimDate', 'invoice_no', 'invoice_date', 'is_opening', 'totalqty', 'total_amount', 'isReturnFabricInward')
            ->where('trimCode', $request->trimCode)
            ->first();

        $MasterOld = (array) $MasterOldFetch;

        $MasterNew = [
            'trimDate' => $request->trimDate,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'is_opening' => $request->is_opening,
            'totalqty' => $request->totalqty,
            'total_amount' => $request->total_amount,
            'isReturnFabricInward' => $request->isReturnFabricInward
        ];


        try {
            $loggerMaster->logIfChangedTrimsInOutMaster(
                'trimsInwardMaster',
                $request->trimCode,
                $MasterOld,
                $MasterNew,
                'UPDATE',
                $request->trimDate,
                'TRIMS_INWARD'
            );
            // Log::info('Logger called successfully for trimsInwardMaster.', [
            //   $newDataDetail
            // ]);
        } catch (\Exception $e) {
            Log::error('Logger failed for trimsInwardMaster.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'trimCode' =>  $request->trimCode,
                'data' => $MasterNew
            ]);
        }




        $purchase = TrimsInwardMasterModel::findOrFail($pur_code);

        $purchase->fill($data)->save();


        $olddata1 = DB::table('trimsInwardDetail')
            ->select('item_code', 'item_qty', 'item_rate', 'amount', 'rack_id')
            ->where('trimCode', $request->input('trimCode'))
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $combinedOldData = $olddata1;



        DB::table('trimsInwardDetail')->where('trimCode', $request->input('trimCode'))->delete();
        DB::table('stock_association')->where('tr_code', $request->input('trimCode'))->delete();


        $cnt = $request->input('cnt');

        $itemcodes = count($request->item_codes);
        if ($request->allocate_qty != "") {
            $allocate_qtys = count($request->allocate_qty);
        }

        if ($cnt > 0) {

            $newDataDetail2 = [];

            for ($x = 0; $x < $itemcodes; $x++) {

                $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $po_code)->first();

                $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;

                $data2[] = array(
                    'trimCode' => $request->trimCode,
                    'trimDate' => $request->input('trimDate'),
                    'Ac_code' => $request->input('Ac_code'),
                    "po_code" => $po_code,
                    'item_code' => isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0,
                    'hsn_code' => isset($request->hsn_codes[$x]) ? $request->hsn_codes[$x] : 0,
                    'unit_id' => isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0,
                    'item_qty' => isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0,
                    'item_rate' => isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0,
                    'amount' => isset($request->amounts[$x]) ? $request->amounts[$x] : 0,
                    'rack_id' => isset($request->rack_id[$x]) ? $request->rack_id[$x] : 0,
                    'is_opening' => $is_opening,
                    'buyer_id' => $buyer_id,
                    'tge_code' => $request->tge_code,
                    'location_id' => isset($request->location_id) ? $request->location_id : 0,
                );



                $item_code = isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0;
                $unit_id = isset($request->unit_ids[$x]) ? $request->unit_ids[$x] : 0;
                $item_qty = isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0;
                $item_rate = isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0;
                $amount = isset($request->amounts[$x]) ? $request->amounts[$x] : 0;
                $rack_id = isset($request->rack_ids[$x]) ? $request->rack_ids[$x] : 0;
                $location_id = isset($request->location_id[$x]) ? $request->location_id[$x] : 0;

                $buyerData = DB::table('purchaseorder_detail')
                    ->select('LM1.ac_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                    ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                    ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                    ->where('purchaseorder_detail.pur_code', $po_code)
                    ->where('purchaseorder_detail.item_code', $item_code)
                    ->groupBy('purchaseorder_detail.pur_code')
                    ->get();

                $itemData = DB::table('item_master')->select('item_name', 'item_description')->where('item_code', $item_code)->first();

                $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->input('Ac_code'))->first();

                $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";

                $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";

                $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;

                if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") {
                    $job_status_id = 1;
                    $po_status = "Moving";
                } else {
                    $job_status_id = 2;
                    $po_status = "Non Moving";
                }


                $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                $item_description = isset($itemData->item_description) ? $itemData->item_description : "";


                DB::table('dump_trim_stock_data')->where('trimCode', $request->input('trimCode'))->where('item_code', $item_code)->where('po_no', $po_code)->delete();

                DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,buyer_name,suplier_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
                                select "' . $request->trimDate . '","' . $buyerName . '","' . $suplierName . '","' . $po_code . '","' . $item_code . '","' . addslashes($item_name) . '","' . $item_rate . '", "-",
                                        "' . addslashes($item_description) . '", "' . $po_status . '","' . $job_status_id . '", "' . $rack_id . '","' . $request->Ac_code . '","' . $request->Ac_code . '","' . $unit_id . '","' . $request->trimCode . '","' . $item_qty . '",0,"","' . $amount . '"');


                $existingData = DB::table('trimsOutwardDetail')->select('tout_date', 'item_qty')->where('po_code', '=', $po_code)->where('item_code', '=', $item_code)->get();

                $trim_date = $request->input('trimDate');

                $updated_string = '';
                foreach ($existingData as $outwards) {
                    $updated_string .=  $outwards->tout_date . '=>' . $outwards->item_qty . ",";
                }
                DB::table('dump_trim_stock_data')
                    ->where('item_code', '=', $item_code)
                    ->where('po_no', '=', $po_code)
                    ->update([
                        'tout_date' =>  $trim_date,
                        'outward_qty' => $item_qty,
                        'ind_outward_qty' => '',
                    ]);
                DB::table('dump_trim_stock_data')
                    ->where('item_code', '=', $item_code)
                    ->where('po_no', '=', $po_code)
                    ->update([
                        'tout_date' =>  $trim_date,
                        'outward_qty' => $item_qty,
                        'ind_outward_qty' => $updated_string
                    ]);

                // DB::select("DELETE FROM  dump_trim_stock_data WHERE po_no = '".$po_code."' AND item_code = '".$item_code."' AND item_code NOT IN (SELECT item_code FROM trimsOutwardDetail WHERE  po_code = '".$po_code."' AND item_code = '".$item_code."')");


                $newDataDetail2[] = [
                    'item_code' => isset($request->item_codes[$x]) ? $request->item_codes[$x] : 0,
                    'item_qty' => isset($request->item_qtys[$x]) ? $request->item_qtys[$x] : 0,
                    'item_rate' => isset($request->item_rates[$x]) ? $request->item_rates[$x] : 0,
                    'amount' => isset($request->amounts[$x]) ? $request->amounts[$x] : 0,
                    'rack_id' => isset($request->rack_id[$x]) ? $request->rack_id[$x] : 0
                ];
            }



            $combinedNewData = $newDataDetail2;

            try {
                $loggerDetail->logIfChangedTrimsInOutDetail(
                    'trimsInwardDetail',
                    $request->trimCode,
                    $combinedOldData,
                    $combinedNewData,
                    'UPDATE',
                    $request->input('trimDate'),
                    'TRIMS_INWARD'
                );
                // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
                //   $newDataDetail
                // ]);
            } catch (\Exception $e) {
                Log::error('Logger failed for fabric_outward_details.', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'trCode' => $request->trimCode,
                    'data' => $combinedNewData
                ]);
            }





            TrimsInwardDetailModel::insert($data2);
            for ($y = 0; $y < $allocate_qtys; $y++) {

                $data3 = array(
                    "po_code" => $po_code,
                    "po_date" => $request->input('trimDate'),
                    "tr_code" => $request->trimCode,
                    "tr_date" => $request->input('trimDate'),
                    'bom_code' => $request->stock_bom_code[$y],
                    'sales_order_no' => $request->sales_order_no[$y],
                    'cat_id' => $request->cat_id[$y],
                    'class_id' => $request->class_id[$y],
                    "item_code" => $request->item_code[$y],
                    'unit_id' => 0,
                    'qty' => $request->allocate_qty[$y],
                    "tr_type" => 1,
                );

                StockAssociationModel::insert($data3);
            }
        }

        return redirect()->route('TrimsInward.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($trimCode)
    {

        $trimCode = base64_decode($trimCode);

        // $trimsDetailsData = DB::SELECT('SELECT po_code,item_code FROM trimsInwardDetail WHERE trimCode ="'.$trimCode.'"');
        // foreach($trimsDetailsData as $row)
        // {

        // } 
        DB::table('dump_trim_stock_data')->where('trimCode', '=', $trimCode)->delete();

        $master = TrimsInwardMasterModel::where('trimCode', $trimCode)->delete();


        $detail = TrimsInwardDetailModel::where('trimCode', $trimCode)->delete();

        $stockData = StockAssociationModel::where('tr_code', $trimCode)->delete();

        Session::flash('delete', 'Deleted record successfully');
    }

    public function closestatus($id) {}

    public function GetPartyDetails(Request $request)
    {

        $ac_code = $request->input('ac_code');
        $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='" . $ac_code . "' and delflag=0");
        return json_encode($PartyRecords);
    }



    public function getBoMDetail(Request $request)
    {



        $itemlist = DB::table('item_master')
            ->get();

        $unitlist = DB::table('unit_master')
            ->get();






        if ($request->type == 1) {

            $table = "bom_fabric_details";
        } else if ($request->type == 2) {

            $table = "bom_sewing_trims_details";
        } else if ($request->type == 3) {

            $table = "bom_packing_trims_details";
        }

        //DB::enableQueryLog();

        $bom_codeids = explode(',', $request->bom_code);

        $data = DB::table($table)
            ->whereIn('bom_code', $bom_codeids)
            ->get();

        //dd(DB::getQueryLog());


        $html = '';

        $no = 1;

        foreach ($data as $value) {




            if ($request->tax_type_id == 1) {

                $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));


                $Camt = ($value->total_amount * ($datagst[0]->cgst_per / 100));

                $Samt = ($value->total_amount * ($datagst[0]->sgst_per / 100));

                $Iamt = 0;

                $TAmount = $value->total_amount + $Camt + $Samt + 0;

                $igst_per = 0;
            } else  if ($request->tax_type_id == 2) {

                $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));


                $Iamt = ($value->total_amount * ($datagst[0]->igst_per / 100));

                $Camt = 0;
                $Samt = 0;

                $TAmount = $value->total_amount + $Iamt + 0;
            } else if ($request->tax_type_id == 3) {

                $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));
            }


            $html .= '<tr id="bomdis">';

            $html .= '
<td><input type="text" name="id[]" value="' . $no . '" id="id" style="width:50px;"/></td>
 
<td> <select name="item_codes[]"  id="item_code" style="width:100px;" required>
<option value="">--Select Item--</option>';

            foreach ($itemlist as  $row1) {
                $html .= '<option value="' . $row1->item_code . '"';

                $row1->item_code == $value->item_code ? $html .= 'selected="selected"' : '';


                $html .= '>' . $row1->item_name . '</option>';
            }

            $html .= '</select></td> 



<td>
 <img  src="https://ken.korbofx.org/thumbnail/' . $datagst[0]->item_image_path . '"  id="item_image" name="item_image[]" class="imgs">
 
<input type="hidden"  name="hsn_code[]" value="' . $datagst[0]->hsn_code . '" id="hsn_code" style="width:80px;" required/> </td>';

            $html .= '<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required>
<option value="">--Select Unit--</option>';

            foreach ($unitlist as  $rowunit) {
                $html .= '<option value="' . $rowunit->unit_id . '"';

                $rowunit->unit_id == $value->unit_id ? $html .= 'selected="selected"' : '';


                $html .= '>' . $rowunit->unit_name . '</option>';
            }

            $html .= '</select></td>';


            $html .= '
<td><input type="text"   name="item_qtys[]"   value="' . $value->bom_qty . '" id="item_qty" style="width:80px;" required/>
	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
</td>
<td><input type="text"   name="item_rates[]"  value="' . $value->rate_per_unit . '" class="RATE"  id="item_rate" style="width:80px;" required/></td>
<td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
<td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" required/></td>
<td><input type="text"   name="pur_cgsts[]"  value="' . $datagst[0]->cgst_per . '" class="pur_cgsts"  id="pur_cgst" style="width:80px;" required/></td>
<td><input type="text"   name="camts[]"  value="' . number_format((float)$Camt, 2, '.', '') . '" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_sgsts[]"  value="' . $datagst[0]->sgst_per . '" class=""  id="pur_sgst" style="width:80px;" required/></td>
<td><input type="text"   name="samts[]"  value="' . number_format((float)$Samt, 2, '.', '') . '" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_igsts[]"  value="' . $datagst[0]->igst_per . '" class=""  id="pur_igst" style="width:80px;" required/></td>
<td><input type="text"   name="iamts[]"  value="' . number_format((float)$Iamt, 2, '.', '') . '" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
<td><input type="text"   name="amounts[]"  value="' . $value->total_amount . '" class="GROSS"  id="amount" style="width:80px;" required/></td>
<td><input type="text" name="freight_hsn[]" class="" id="freight_hsn" value="0" style="width:80px;"></td>

<td><input type="text" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="0" style="width:80px;"></td>


<td><input type="text"   name="total_amounts[]"  class="TOTAMT" value="' . number_format((float)$TAmount, 2, '.', '') . '"  id="total_amount" style="width:80px;" required/></td>

<td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
';

            $html .= '</tr>';
            $no = $no + 1;
        }


        return response()->json(['html' => $html]);
    }




    public function getPoForTrims(Request $request)
    {
        ini_set('memory_limit', '1G');
        $po_code = base64_decode($request->po_code);
        //$itemlist=DB::table('item_master')->where('item_master.cat_id','!=','1')->where('item_master.delflag','0')->get();
        //$unitlist=DB::table('unit_master')->where('unit_master.delflag','0')->get();
        $RackList = DB::table('rack_master')->where('rack_master.delflag', '0')->get();
        //DB::enableQueryLog();
        $data = DB::select(DB::raw("SELECT classification_master.class_name,purchase_order.sr_no, purchaseorder_detail.bom_code, purchaseorder_detail.pur_code, purchaseorder_detail.pur_date, purchaseorder_detail.Ac_code, 
         purchaseorder_detail.item_code,item_master.item_description, item_master.cat_id,item_master.class_id, purchaseorder_detail.hsn_code,
         purchaseorder_detail.unit_id,purchaseorder_detail.item_rate, sum(purchaseorder_detail.item_qty)  as totalQty,purchaseorder_detail.sales_order_no   FROM   purchaseorder_detail
         inner join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
         LEFT join item_master on item_master.item_code=purchaseorder_detail.item_code
         LEFT join bom_master ON bom_master.sales_order_no = purchaseorder_detail.sales_order_no
         LEFT join classification_master ON classification_master.class_id = item_master.class_id
         where purchase_order.pur_code='" . $po_code . "' GROUP BY purchaseorder_detail.item_code"));
        //dd(DB::getQueryLog());     

        $html = '';

        $html .= '<div class="table-wrap" id="trimInward">
                <div class="table-responsive">
                       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                <thead>
                <tr>
                    <th>SrNo</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Classification</th>
                    <th>UOM</th>
                    <th>To Be Received</th>
                    <th>Qty</th>
                    <th>Item Rate</th>
                    <th>Amount</th>
                    <th>Rack</th>
                    <th nowrap>Remove <button type="button" name="allocate[]"  onclick="stockAllocate(this);" id="mainAllocation"  isClick = "0" class="btn btn-success pull-left">Allocate</button></th>
                </tr>
                </thead>
                <tbody>';
        $no = 1;
        foreach ($data as $value) {
            if ($value->item_code != NULL) {
                $itemlist = DB::table('item_master')->where('item_master.cat_id', '!=', '1')->where('item_code', '=', $value->item_code)->where('item_master.delflag', '0')->get();
                $unitlist = DB::table('unit_master')->where('unit_id', '=', $value->unit_id)->where('unit_master.delflag', '0')->get();

                $InwardTrims = DB::select("SELECT   
                            trimsInwardDetail.`item_code`, unit_master.unit_name, item_master.item_name, item_master.item_description, class_name, 
                            sum(trimsInwardDetail.`item_qty`) as item_qty, trimsInwardDetail.item_rate, trimsInwardDetail.unit_id
                            FROM `trimsInwardDetail` 
                            inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode 
                            inner join item_master on item_master.item_code=trimsInwardDetail.item_code
                            inner join unit_master on unit_master.unit_id=trimsInwardDetail.unit_id
                            inner join classification_master on classification_master.class_id=item_master.class_id
                            where trimsInwardMaster.po_code='" . $value->pur_code . "' and trimsInwardDetail.item_code='" . $value->item_code . "'
                            group by trimsInwardMaster.po_code, trimsInwardDetail.item_code");

                $purchaseOrderData = DB::select("select sum(item_qty) as po_item_qty from purchaseorder_detail 
                                                    where purchaseorder_detail.pur_code='" . $value->pur_code . "' AND purchaseorder_detail.item_code = '" . $value->item_code . "'");

                $allow_qty = $value->totalQty * 0.05;
                $toBeReceived = (isset($purchaseOrderData[0]->po_item_qty) ?  $purchaseOrderData[0]->po_item_qty : 0) - (isset($InwardTrims[0]->item_qty) ?  $InwardTrims[0]->item_qty : 0) + $allow_qty;

                $purchase_qty = (isset($purchaseOrderData[0]->po_item_qty) ?  $purchaseOrderData[0]->po_item_qty : 0);
                $item_qty1 = (isset($purchaseOrderData[0]->po_item_qty) ?  $purchaseOrderData[0]->po_item_qty : 0) - (isset($InwardTrims[0]->item_qty) ?  $InwardTrims[0]->item_qty : 0);
                if ($item_qty1 < 0) {
                    $item_qty = 0;
                } else {
                    $item_qty = $item_qty1;
                }
                $html .= '<tr  item_code="' . $value->item_code . '" isClick = "0" qty="' . $item_qty . '" bom_code="' . $value->bom_code . '" cat_id="' . $value->cat_id . '" class_id="' . $value->class_id . '">';

                $html .= '<td><input type="text" name="id[]" value="' . $no . '" id="id" style="width:50px;" readonly/></td>
                         
                         <td> <span onclick="openmodal(' . $value->sr_no . ',' . $value->item_code . ');" style="color:#556ee6; cursor: pointer;">' . $value->item_code . '</span></td>
                        <td> <select name="item_codes[]"  id="item_codes" style="width:300px; height:30px;" required disabled >
                        <option value="">--Select Item--</option>';

                foreach ($itemlist as  $row1) {
                    $html .= '<option value="' . $row1->item_code . '"';

                    $row1->item_code == $value->item_code ? $html .= 'selected="selected"' : '';


                    $html .= '>' . $row1->item_name . '</option>';
                }

                $html .= '</select></td> ';
                $html .= '<td><input type="text" value="' . $value->class_name . '" style="width:250px;height:30px;" readOnly/>';
                $html .= '<td> <select name="unit_ids[]"  id="unit_ids" style="width:80px; height:30px;" disabled >
                        <option value="">--Select Unit--</option>';

                foreach ($unitlist as  $rowunit) {
                    $html .= '<option value="' . $rowunit->unit_id . '"';

                    $rowunit->unit_id == $value->unit_id ? $html .= 'selected="selected"' : '';


                    $html .= '>' . $rowunit->unit_name . '</option>';
                }

                $html .= '</select></td>';
                $html .= '<td><input type="number" step="any" class="toBeReceived"  name="toBeReceived[]" value="' . round($toBeReceived, 2) . '" id="toBeReceived" style="width:80px;height:30px;" readOnly/>
                                </td>';
                $html .= '<td><input type="number" step="any" class="QTY"  name="item_qtys[]" onchange="SetQtyToBtn(this);" allow="' . $allow_qty . '" purchase_qty = "' . $purchase_qty . '" current="' . round($item_qty, 2) . '" value="' . round($item_qty, 2) . '" id="item_qty" style="width:80px;height:30px;" required/>
                        <input type="hidden"  name="hsn_code[]" value="' . $value->hsn_code  . '" id="hsn_code" style="width:80px; height:30px;" readOnly required/>
                        </td>';
                $html .= '<td><input type="number" step="any" name="item_rates[]" readOnly  value="' . round($value->item_rate, 5) . '" id="item_rates" style="width:80px;height:30px;" required/></td>';

                $html .= '<td><input type="number" step="any" class="AMT"  name="amounts[]" readOnly value="' . (round($purchase_qty, 2) * round($value->item_rate, 2)) . '" id="amounts" style="width:80px;height:30px;" required/></td>';



                $html .= '<td> <select name="rack_ids[]"  id="rack_ids" style="width:100px; height:30px;">
                        <option value="">--Select Rack--</option>';

                foreach ($RackList as  $rowrack) {
                    $html .= '<option value="' . $rowrack->rack_id . '"';

                    $html .= '>' . $rowrack->rack_name . '</option>';
                }

                $html .= '</select></td>
                        
                        
                        <td nowrap><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
                        ';

                $html .= '</tr>';
                $no = $no + 1;
            }
        }

        $html .= '<input type="number" value="' . count($data) . '" name="cnt" id="cnt" readonly="" hidden="true"  />';
        $html .= '</table>
                   </div>
                </div>';
        return response()->json(['html' => $html]);
    }

    public function DemoCreate()
    {

        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='TrimMaster' and c_name='C1'"));
        $LocationList = LocationModel::where('location_master.delflag', '=', '0')->get();
        $JobStatusList = JobStatusModel::where('job_status_master.delflag', '=', '0')->get();
        $firmlist = DB::table('firm_master')->get();

        $RackList = RackModel::where('rack_master.delflag', '=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag', '=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag', '=', '0')->whereIn('ledger_master.bt_id', [1, 2, 4])->get();
        $itemlist = DB::table('item_master')->where('item_master.delflag', '0')->where('item_master.cat_id', '!=', '1')->get();
        $unitlist = DB::table('unit_master')->get();
        $BillToList =  DB::table('ledger_details')->get();

        $POList = PurchaseOrderModel::where('purchase_order.po_status', '=', '1')->where('purchase_order.bom_type', '!=', '1')->get();
        $vendorWorkOrderList = DB::table('vendor_work_order_master')->select('vw_code')->where('delflag', '=', 0)->get();
        $TGEList = DB::table('trim_gate_entry_master')->where('delflag', '=', 0)->get();
        return view('DemoCreate', compact('firmlist', 'RackList', 'ledgerlist', 'gstlist', 'itemlist', 'code', 'unitlist', 'POTypeList', 'JobStatusList', 'POList', 'LocationList', 'vendorWorkOrderList', 'TGEList', 'BillToList'));
    }

    public function stockAllocate(Request $request)
    {

        $bom_code = $request->bom_code;

        $item_code = $request->item_code;
        $exist_Item_qty = $request->item_qty;
        $cat_id = $request->cat_id;
        $class_id = $request->class_id;
        $item_name = $request->item_name;
        $is_opening = $request->is_opening;
        $po_type_id = $request->po_type_id;
        $total_avaliable_qty = 0;
        $bom_sewingData = "";
        $bomArray = explode(",", $bom_code);
        $qtyArr = [];
        $html = "";
        $totalQty = 0;
        $allocate_qty = 0;
        $bom_Total = 0;

        if ($cat_id == 2) {
            // DB::enableQueryLog();
            $bomData = DB::select("SELECT * FROM bom_sewing_trims_details  WHERE bom_code IN ('" . str_replace(",", "','", $bom_code) . "') AND item_code =" . $item_code);
            //dd(DB::getQueryLog());
        } else if ($cat_id == 3) {
            $bomData = DB::select("SELECT * FROM bom_packing_trims_details  WHERE bom_code IN ('" . str_replace(",", "','", $bom_code) . "') AND item_code =" . $item_code);
        } else {
            $bomData = [];
        }

        if ($is_opening == 1 || $po_type_id == 2) {
            $allocate_qty =  $exist_Item_qty;
            $itemlist = DB::table('item_master')->where('item_master.item_code', '=', $item_code)->where('item_master.delflag', '0')->get();
            $html .= '<tr>
                    <td><input type="text" name="stock_bom_code[]" value="" class="form-control" style="width:100px;" readonly /></td>
                    <td><input type="text" name="sales_order_no[]" value="0" class="form-control" style="width:100px;" readonly/></td>
                    <td><input type="text" name="item_code[]" value="' . $item_code . '" class="form-control" style="width:100px;" readonly/></td>
                    <td nowrap><input type="text" name="item_name[]" value="' . $itemlist[0]->item_name . '" class="form-control" style="width:300px;" readonly/></td>
                    <td nowrap><input type="text" name="allocate_qty[]" value="' . round($allocate_qty, 2) . '" class="form-control allocate_qty" style="width:100px;" readonly />
                                <input type="hidden" name="cat_id[]" value="' . $cat_id . '" class="form-control" style="width:100px;" />
                                <input type="hidden" name="class_id[]" value="' . $class_id . '" class="form-control" style="width:100px;" />
                    </td>
                </tr>';
        } else {
            foreach ($bomData as $bom) {
                if ($cat_id == 2) {
                    //DB::enableQueryLog();
                    $bom_Total = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_sewing_trims_details  WHERE bom_code IN  ('" . str_replace(",", "','", $bom_code) . "') AND item_code =" . $item_code);
                    //dd(DB::getQueryLog());
                } else if ($cat_id == 3) {
                    $bom_Total = DB::select("SELECT sum(bom_qty) as totalQty FROM bom_packing_trims_details  WHERE bom_code IN  ('" . str_replace(",", "','", $bom_code) . "') AND item_code =" . $item_code);
                }

                if ($bom_Total != "") {
                    $totalQty = $bom_Total[0]->totalQty;
                } else {
                    $totalQty = 0;
                }
                $itemlist = DB::table('item_master')->where('item_master.item_code', '=', $bom->item_code)->where('item_master.delflag', '0')->get();

                $salesOrderData = DB::select("SELECT sales_order_no FROM bom_master WHERE bom_master.bom_code ='" . $bom->bom_code . "'");
                //dd(DB::getQueryLog());

                if (count($salesOrderData) > 0) {
                    $sales_order_no = $salesOrderData[0]->sales_order_no;

                    // $bom_sewingData = DB::select("SELECT item_qty FROM bom_sewing_trims_details WHERE bom_code = '".$bom->bom_code."' AND  sales_order_no = '".$sales_order_no."' AND item_code=".$item_code);

                    // //    DB::enableQueryLog();           
                    // $bom_packingData = DB::select("SELECT item_qty FROM bom_packing_trims_details WHERE bom_code = '".$bom->bom_code."' AND  sales_order_no = '".$sales_order_no."' AND item_code=".$item_code);
                    // // dd(DB::getQueryLog());
                } else {
                    $sales_order_no = "0";
                    // $bom_sewingData = [];
                    // $bom_packingData = [];
                }
                // //DB::enableQueryLog();

                // if($cat_id == 2)
                // {
                //     if(count($bom_sewingData) > 0)
                //     {
                //         $item_qty = $bom_sewingData[0]->item_qty;
                //     }
                //     else
                //     {
                //         $item_qty = 0;
                //     }
                // }
                // else if($cat_id == 3)
                // {
                //     if(count($bom_packingData) > 0)
                //     {
                //          $item_qty = $bom_packingData[0]->item_qty; 
                //     }
                //     else
                //     {
                //         $item_qty = 0;
                //     }
                // }  
                // else
                // {
                //     $item_qty = 0;
                // }

                // if($totalQty > 0 && $bom->bom_qty > 0)
                // {
                //$allocate_qty = $bom->bom_qty;
                // $allocate_qty = (($bom->bom_qty/($totalQty + ($totalQty * (3/100)))) * 100) * $exist_Item_qty;
                //  $allocate_qty = (($bom->bom_qty/(round($totalQty) + (round($totalQty) * ($bom->wastage/100))))) * $exist_Item_qty;

                if ($bom->bom_qty > 0 && $totalQty > 0) {
                    $allocate_qty = (round($bom->bom_qty) / (round($totalQty)))  * $exist_Item_qty;
                } else {
                    $allocate_qty = 0;
                }
                // }
                // else
                // {
                //     $allocate_qty = 0;
                // }

                if ($allocate_qty > 0) {
                    $html .= '<tr>
                                <td><input type="text" name="stock_bom_code[]" value="' . $bom->bom_code . '" class="form-control" style="width:100px;" readonly /></td>
                                <td><input type="text" name="sales_order_no[]" value="' . $sales_order_no . '" class="form-control" style="width:100px;" readonly/></td>
                                <td><input type="text" name="item_code[]" value="' . $item_code . '" class="form-control" style="width:100px;" readonly/></td>
                                <td nowrap><input type="text" name="item_name[]" value="' . $itemlist[0]->item_name . '" class="form-control" style="width:300px;" readonly/></td>
                                <td nowrap><input type="text" name="allocate_qty[]" value="' . round($allocate_qty, 2) . '" class="form-control" style="width:100px;" readonly />
                                            <input type="hidden" name="cat_id[]" value="' . $cat_id . '" class="form-control" style="width:100px;" />
                                            <input type="hidden" name="class_id[]" value="' . $class_id . '" class="form-control" style="width:100px;" />
                                </td>
                            </tr>';
                }
                $allocate_qty = 0;
            }
        }


        return response()->json(['html' => $html]);
    }

    public function getPoMasterDetailTrims(Request $request)
    {


        $po_codee = base64_decode($request->po_code);

        $data = DB::table('purchase_order')->where('pur_code', '=', $po_codee)
            ->get(['purchase_order.*']);


        return $data;
    }

    // public function TrimsGRNData()
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();


    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
    //     $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 

    //     //DB::enableQueryLog();
    //     $TrimsInwardDetails = TrimsInwardDetailModel::
    //       leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
    //       ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
    //       ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
    //       ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
    //       ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no','trimsInwardMaster.po_code',
    //       'trimsInwardMaster.invoice_date',  'ledger_master.ac_name','item_master.dimension', 'item_master.item_name',
    //       'item_master.color_name','item_master.item_description', 'rack_master.rack_name']);


    //  // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
    //     return view('TrimsGRNData',compact('TrimsInwardDetails'));
    // }

    public function TrimsGRNData(Request $request)
    {
        $fromDate =  isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");

        if ($request->ajax()) {
            $TrimsInwardDetails = TrimsInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
                ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardDetail.po_code')
                ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                ->leftJoin('ledger_master as LM2', 'LM2.ac_code', '=', 'trimsInwardMaster.buyer_id')
                ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
                ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'trimsInwardMaster.Ac_code')
                ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=', 'trimsInwardMaster.vw_code')
                ->leftJoin('ledger_master as LM3', 'LM3.ac_code', '=', 'vendor_work_order_master.vendorId')
                ->whereBetween('trimsInwardMaster.trimDate', [$fromDate, $toDate])
                ->where('item_master.cat_id', '!=', 4)
                ->get([
                    'trimsInwardDetail.*',
                    'LM1.ac_short_name as buyer',
                    'trimsInwardMaster.is_opening',
                    'trimsInwardMaster.invoice_no',
                    'trimsInwardMaster.po_code',
                    'LM1.ac_short_name as BuyerName1',
                    'LM2.ac_short_name as BuyerName2',
                    'trimsInwardMaster.invoice_date',
                    'ledger_master.ac_name',
                    'item_master.dimension',
                    'item_master.item_name',
                    'trimsInwardMaster.vw_code',
                    'LM3.ac_short_name as vendorName',
                    DB::raw('(SELECT trade_name FROM ledger_details WHERE sr_no = purchase_order.bill_to OR ac_code = trimsInwardMaster.Ac_code LIMIT 1) as trade_name'),
                    DB::raw('(SELECT site_code FROM ledger_details WHERE sr_no = purchase_order.bill_to OR ac_code = trimsInwardMaster.Ac_code LIMIT 1) as site_code'),
                    'item_master.color_name',
                    'item_master.item_description',
                    'rack_master.rack_name'
                ]);


            //dd(DB::getQueryLog());
            return Datatables::of($TrimsInwardDetails)

                ->addColumn('trimDate', function ($row) {
                    $trimDate = date("d-M-Y", strtotime($row->trimDate));
                    return $trimDate;
                })
                ->addColumn('invoice_date', function ($row) {
                    $invoice_date = date("d-M-Y", strtotime($row->invoice_date));
                    return $invoice_date;
                })
                ->addColumn('sales_order_no', function ($row) {
                    $sales_order_no = isset($row->sales_order_no) ? $row->sales_order_no : "Opening Stock";
                    return $sales_order_no;
                })

                ->addColumn('buyer', function ($row) {
                    // $BuyerData=DB::table('purchaseorder_detail')->select('L1.Ac_name as BuyerName')
                    //         ->leftJoin('item_master', 'item_master.item_code', '=','purchaseorder_detail.item_code')
                    //         ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=','purchaseorder_detail.sales_order_no')
                    //         ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                    //         ->Where('purchaseorder_detail.pur_code',$row->po_code)
                    //         ->Where('purchaseorder_detail.item_code',$row->item_code)
                    //         ->limit(1)->get();

                    if ($row->BuyerName1 != '') {
                        $buyer = $row->BuyerName1;
                    } else {
                        $buyer = $row->BuyerName2;
                    }
                    return $buyer;
                })

                ->addColumn('item_qty', function ($row) {
                    $item_qty =  number_format(round($row->item_qty, 2), 2, '.', ',');
                    return $item_qty;
                })
                ->addColumn('item_value', function ($row) {

                    $item_value =  number_format(round($row->item_qty * $row->item_rate, 2), 2, '.', ',');
                    return $item_value;
                })
                ->addColumn('bill_name', function ($row) {
                    $bill_name =  $row->trade_name . '(' . $row->site_code . ')';
                    return $bill_name;
                })
                ->rawColumns(['sales_order_no', 'buyer', 'item_value', 'item_qty', 'trimDate', 'invoice_date', 'bill_name'])

                ->make(true);
        }

        return view('TrimsGRNData', compact('fromDate', 'toDate'));
    }

    // public function TrimsGRNDataMD($DFilter)
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();


    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
    //     $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 

    //     if($DFilter == 'd')
    //     {
    //         $filterDate = " AND trimsInwardDetail.trimDate =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    //     }
    //     else if($DFilter == 'm')
    //     {
    //         $filterDate = ' AND MONTH(trimsInwardDetail.trimDate) = MONTH(CURRENT_DATE()) and YEAR(trimsInwardDetail.trimDate)=YEAR(CURRENT_DATE()) AND trimsInwardDetail.trimDate !="'.date('Y-m-d').'"';
    //     }
    //     else if($DFilter == 'y')
    //     {
    //         $filterDate = ' AND trimsInwardDetail.trimDate between (select fdate from financial_year_master 
    //                         where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
    //     }
    //     else
    //     {
    //         $filterDate = "";
    //     }
    //     //DB::enableQueryLog();
    //     $TrimsInwardDetails = DB::select("SELECT trimsInwardDetail.*, trimsInwardMaster.is_opening, trimsInwardMaster.invoice_no,trimsInwardMaster.po_code,
    //       trimsInwardMaster.invoice_date,  ledger_master.ac_name,item_master.dimension, item_master.item_name,
    //       item_master.color_name,item_master.item_description, rack_master.rack_name FROM trimsInwardDetail 
    //       LEFT JOIN ledger_master ON ledger_master.ac_code = trimsInwardDetail.Ac_code
    //       LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
    //       LEFT JOIN rack_master ON rack_master.rack_id = trimsInwardDetail.rack_id
    //       LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = trimsInwardDetail.trimCode WHERE 1 ".$filterDate);
    //       //dd(DB::getQueryLog());
    //  // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
    //     return view('TrimsGRNData',compact('TrimsInwardDetails'));
    // }

    public function TrimsGRNDataMD(Request $request, $DFilter)
    {
        if ($request->ajax()) {
            if ($DFilter == 'd') {
                $filterDate = " AND trimsInwardDetail.trimDate =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            } else if ($DFilter == 'm') {
                $filterDate = ' AND MONTH(trimsInwardDetail.trimDate) = MONTH(CURRENT_DATE()) and YEAR(trimsInwardDetail.trimDate)=YEAR(CURRENT_DATE()) AND trimsInwardDetail.trimDate !="' . date('Y-m-d') . '"';
            } else if ($DFilter == 'y') {
                $filterDate = ' AND trimsInwardDetail.trimDate between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
            } else {
                $filterDate = "";
            }
            //DB::enableQueryLog();
            $TrimsInwardDetails = DB::select("SELECT trimsInwardDetail.*, trimsInwardMaster.is_opening, trimsInwardMaster.invoice_no,trimsInwardMaster.po_code,
              trimsInwardMaster.invoice_date,  ledger_master.ac_name,item_master.dimension, item_master.item_name,
              item_master.color_name,item_master.item_description, rack_master.rack_name FROM trimsInwardDetail 
              LEFT JOIN ledger_master ON ledger_master.ac_code = trimsInwardDetail.Ac_code
              LEFT JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code
              LEFT JOIN rack_master ON rack_master.rack_id = trimsInwardDetail.rack_id
              LEFT JOIN trimsInwardMaster ON trimsInwardMaster.trimCode = trimsInwardDetail.trimCode WHERE 
              item_master.cat_id != 4 " . $filterDate);
            //dd(DB::getQueryLog());
            return Datatables::of($TrimsInwardDetails)

                ->addColumn('sales_order_no', function ($row) {
                    if ($row->is_opening != 1) {
                        $sales_order_no = isset($row->sales_order_no) ? $row->sales_order_no : "-";
                    } else {
                        $sales_order_no = 'Opening Stock';
                    }
                    return $sales_order_no;
                })

                ->addColumn('buyer', function ($row) {
                    $BuyerData = DB::table('purchaseorder_detail')->select('L1.Ac_name as BuyerName')
                        ->leftJoin('item_master', 'item_master.item_code', '=', 'purchaseorder_detail.item_code')
                        ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                        ->leftJoin('ledger_master as L1', 'L1.ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                        ->Where('purchaseorder_detail.pur_code', $row->po_code)
                        ->Where('purchaseorder_detail.item_code', $row->item_code)
                        ->limit(1)->get();

                    if ($row->is_opening != 1) {
                        $buyer = isset($BuyerData[0]->BuyerName) ? $BuyerData[0]->BuyerName : "-";
                    } else {
                        $buyer = 'N/A';
                    }
                    return $buyer;
                })

                ->addColumn('item_value', function ($row) {
                    $item_value = round($row->item_qty * $row->item_rate);
                    return $item_value;
                })
                ->rawColumns(['sales_order_no', 'buyer', 'item_value'])

                ->make(true);
        }

        return view('TrimsGRNData');
    }

    public function GetTrimsGRNReport()
    {
        $LedgerList = LedgerModel::where('ledger_master.delflag', '=', '0')->where('ledger_master.Ac_code', '>', '39')->get();


        return view('GetTrimsGRNReport', compact('LedgerList'));
    }






    public function TrimsGRNReportPrint(Request $request)
    {


        $fdate = $request->fdate;
        $tdate = $request->tdate;
        $Ac_code = $request->Ac_code;
        $pur_code = $request->pur_code;
        // DB::enableQueryLog();
        if ($pur_code = '' && $Ac_code != '') {
            $TrimsInwardDetails = TrimsInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
                ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
                ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->where('trimsInwardDetail.Ac_code', $Ac_code)->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))
                ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no', 'trimsInwardMaster.po_code',  'trimsInwardMaster.invoice_date',  'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'rack_master.rack_name']);

            $TrimsInwardTotal = TrimsInwardDetailModel::select(DB::raw('sum(item_qty) as TotalQty'), DB::raw('sum(round(item_rate*item_qty)) as TotalAmount'))
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->where('trimsInwardDetail.Ac_code', $Ac_code)->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))
                ->get();
        } elseif ($pur_code = !'' && $Ac_code != '') {
            $TrimsInwardDetails = TrimsInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
                ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
                ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->where('trimsInwardDetail.Ac_code', $Ac_code)->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))->where('trimsInwardDetail.po_code', $pur_code)
                ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no', 'trimsInwardMaster.po_code',  'trimsInwardMaster.invoice_date',  'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'rack_master.rack_name']);


            $TrimsInwardTotal = TrimsInwardDetailModel::select(DB::raw('sum(item_qty) as TotalQty'), DB::raw('sum(round(item_rate*item_qty)) as TotalAmount'))
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->where('trimsInwardDetail.Ac_code', $Ac_code)->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))->where('trimsInwardDetail.po_code', $pur_code)
                ->get();
        } else {
            $TrimsInwardDetails = TrimsInwardDetailModel::leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
                ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
                ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))
                ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no', 'trimsInwardMaster.po_code',  'trimsInwardMaster.invoice_date',  'ledger_master.ac_name', 'item_master.dimension', 'item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'rack_master.rack_name']);

            $TrimsInwardTotal = TrimsInwardDetailModel::select(DB::raw('sum(item_qty) as TotalQty'), DB::raw('sum(round(item_rate*item_qty)) as TotalAmount'))
                ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
                ->whereBetween('trimsInwardMaster.trimDate', array($fdate, $tdate))
                ->get();
        }


        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('TrimsGRNFilterReportPrint', compact('TrimsInwardDetails', 'TrimsInwardTotal'));
    }


    public function TrimsStockData()
    {
        // $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        // $FGList =  DB::table('fg_master')->get();
        // $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        // $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        // $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        // $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 

        //DB::enableQueryLog();

        // $TrimsInwardDetails1 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
        //     ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
        //     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
        //     where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
        //     trimsInwardMaster.po_code, 
        //     ledger_master.ac_name,item_master.dimension,item_master.item_name,
        //     item_master.color_name,item_master.item_description,rack_master.rack_name
        //     from trimsInwardDetail
        //     left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
        //     left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
        //     left join item_master on item_master.item_code=trimsInwardDetail.item_code
        //     left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id 
        //     WHERE item_master.cat_id !=4
        //     group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
        // ");  

        $TrimsInwardDetails1 = DB::select("SELECT 
                TIM.po_code,
                TID.item_code,
                LM.ac_name,
                SUM(TID.item_qty) AS item_qty,
                TID.item_rate,
                IFNULL(SUM(TOD.item_qty), 0) AS out_qty,
                TIM.po_code,
                LM.ac_name,
                IM.dimension,
                IM.item_name,
                IM.color_name,
                IM.item_description,
                RM.rack_name
            FROM 
                trimsInwardDetail TID
                LEFT JOIN trimsInwardMaster TIM ON TIM.trimCode = TID.trimCode
                LEFT JOIN ledger_master LM ON LM.ac_code = TID.ac_code
                LEFT JOIN item_master IM ON IM.item_code = TID.item_code AND IM.cat_id != 4
                LEFT JOIN rack_master RM ON RM.rack_id = TID.rack_id
                LEFT JOIN trimsOutwardDetail TOD ON TOD.po_code = TID.po_code AND TOD.item_code = TID.item_code
            GROUP BY 
                TIM.po_code,
                TID.item_code");

        //dd(DB::getQueryLog()); 
        $isOpening = "";
        return view('TrimsStockData', compact('TrimsInwardDetails1', 'isOpening'));
    }
    public function TrimsStockData1(Request $request)
    {
        return view('TrimsStockData1');
    }
    public function loadDumpTrimStockData(Request $request)
    {
        $TrimsInwardDetails = DB::select("SELECT * FROM dump_trim_stock_data");
        $totalAmountData = DB::select("SELECT sum(value) as overallAmt FROM dump_trim_stock_data");
        $html = "";
        foreach ($TrimsInwardDetails as $row) {
            $html .= '<tr>
                         <td style="text-align:center; white-space:nowrap">' . $row->suplier_name . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->buyer_name . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->po_status . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->po_no . '</td>
                         <td>' . $row->item_code . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->item_name . '</td>
                         <td style="text-align:right;">' . $row->stock_qty . '</td>
                         <td style="text-align:right;">' . $row->rate . '</td>
                         <td style="text-align:right;">' . $row->value . '</td>
                         <td style="text-align:right;">' . $row->width . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->color . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->item_description . '</td>
                         <td style="text-align:center; white-space:nowrap">' . $row->rack_name . '</td>
                      </tr>';
        }

        $overall = isset($totalAmountData[0]->overallAmt) ? $totalAmountData[0]->overallAmt : 0;
        return response()->json(['html1' => $html, 'overall' => round($overall)]);
    }


    // public function trimStocks(Request $request)
    // { 
    //     $total_stock_qty = 0;
    //     $total_value = 0;
    //     $total_value1 = 0;
    //     $html1 = "";
    //     DB::table('dump_trim_stock_data')->delete();
    //       $TrimsInwardDetails1 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
    //         ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,

    //         (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //         where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,

    //         trimsInwardMaster.po_code, 
    //         ledger_master.ac_name,item_master.dimension,item_master.item_name,
    //         item_master.color_name,item_master.item_description,rack_master.rack_name
    //         from trimsInwardDetail
    //         left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
    //         left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
    //         left join item_master on item_master.item_code=trimsInwardDetail.item_code
    //         left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id 
    //         WHERE item_master.cat_id !=4
    //         group by trimsInwardMaster.po_code,trimsInwardDetail.item_code");
    //     //dd(DB::getQueryLog()); 
    //     $isOpening = "";

    //     foreach($TrimsInwardDetails1 as $rows)
    //     {
    //          if($isOpening == 1)
    //          {
    //             $po_status = ' AND po_status = 1';
    //          }
    //          else if($isOpening == 2)
    //          {
    //             $po_status = ' AND po_status = 2';
    //          }
    //          else
    //          {
    //             $po_status = "";
    //          }

    //          $StatusData = DB::select("select ifnull(purchase_order.po_status,0) as po_status
    //          from purchase_order WHERE purchase_order.pur_code = '".$rows->po_code."'".$po_status);
    //          if(count($StatusData) > 0)
    //          {
    //          $po_status = $StatusData[0]->po_status;
    //          }
    //          else
    //          {
    //          $po_status = 0;
    //          }
    //          $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
    //          if(count($JobStatusList) > 0)
    //          {
    //          $job_status_name = $JobStatusList[0]->job_status_name;
    //          }
    //          else
    //          {
    //          $job_status_name = "-";
    //          }
    //          $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$rows->po_code."'");
    //          if(count($salesOrderNo) > 0)
    //          {
    //          $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
    //          INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
    //          where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
    //          if(count($buyerData) > 0)
    //          {
    //          $buyer_name = $buyerData[0]->ac_name;
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }

    //          $values = ($rows->item_qty-$rows->out_qty)*$rows->item_rate;
    //             DB::table('dump_trim_stock_data')->insert(
    //                 array('suplier_name' => $rows->ac_name,
    //                       'buyer_name' => $buyer_name,
    //                       'po_status' => $job_status_name,
    //                       'po_no' => $rows->po_code,
    //                       'item_code' => $rows->item_code,
    //                       'item_name' => $rows->item_name,
    //                       'stock_qty' => number_format($rows->item_qty - $rows->out_qty),
    //                       'rate' => $rows->item_rate,
    //                       'value' => number_format(round($values)),
    //                       'width' => $rows->dimension,
    //                       'color' => $rows->color_name,
    //                       'item_description' => $rows->item_description,
    //                       'rack_name' => $rows->rack_name,
    //                 )
    //             );

    //           $total_value += $values;
    //     }

    //     if($isOpening == 2)
    //     {

    //     $TrimsInwardDetails2 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
    //         ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
    //         (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
    //         where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
    //         trimsInwardMaster.po_code, 
    //         ledger_master.ac_name,item_master.dimension,item_master.item_name,
    //         item_master.color_name,item_master.item_description,rack_master.rack_name
    //         from trimsInwardDetail
    //         INNER join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
    //         INNER JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
    //         left join item_master on item_master.item_code=trimsInwardDetail.item_code
    //         left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id WHERE 1 AND trimsInwardMaster.is_opening=1
    //         group by trimsInwardMaster.po_code,trimsInwardDetail.item_code");

    //     foreach($TrimsInwardDetails2 as $row)   
    //     {
    //          if($isOpening == 1)
    //          {
    //             $po_status = ' AND po_status = 1';
    //          }
    //          else if($isOpening == 2)
    //          {
    //             $po_status = ' AND po_status = 2';
    //          }
    //          else
    //          {
    //             $po_status = "";
    //          }

    //          $StatusData = DB::select("select ifnull(purchase_order.po_status,0) as po_status
    //          from purchase_order WHERE purchase_order.pur_code = '".$row->po_code."'".$po_status);
    //          if(count($StatusData) > 0)
    //          {
    //          $po_status = $StatusData[0]->po_status;
    //          }
    //          else
    //          {
    //          $po_status = 0;
    //          }
    //          $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$po_status);
    //          if(count($JobStatusList) > 0)
    //          {
    //          $job_status_name = $JobStatusList[0]->job_status_name;
    //          }
    //          else
    //          {
    //          $job_status_name = "-";
    //          }
    //          $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_code."'");
    //          if(count($salesOrderNo) > 0)
    //          {
    //          $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
    //          INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
    //          where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
    //          if(count($buyerData) > 0)
    //          {
    //          $buyer_name = $buyerData[0]->ac_name;
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }
    //          }
    //          else
    //          {
    //          $buyer_name = "-";
    //          }   

    //          $values1 = ($row->item_qty-$row->out_qty)*$row->item_rate;

    //           DB::table('dump_trim_stock_data')->insert(
    //             array('suplier_name' => $row->ac_name,
    //                   'buyer_name' => $buyer_name,
    //                   'po_status' => $job_status_name,
    //                   'po_no' => $row->po_code,
    //                   'item_code' => $row->item_code,
    //                   'item_name' => $row->item_name,
    //                   'stock_qty' => number_format($row->item_qty - $row->out_qty),
    //                   'rate' => $row->item_rate,
    //                   'value' => number_format(round($values1)),
    //                   'width' => $row->dimension,
    //                   'color' => $row->color_name,
    //                   'item_description' => $row->item_description,
    //                   'rack_name' => $row->rack_name,
    //             )
    //         );

    //               $total_value1 += $values1;
    //     }

    //     }


    //     return 1;
    // }


    public function TrimsStockDataMD($isOpening, $DFilter)
    {
        $Financial_Year = DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

        if ($DFilter == 'd') {
            $filterDate = " AND trimsInwardDetail.trimDate < '" . date('Y-m-d') . "'";
            $filterDate1 = " AND trimsOutwardDetail.tout_date < '" . date('Y-m-d') . "'";
        } else if ($DFilter == 'm') {
            $filterDate = ' AND trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
        } else if ($DFilter == 'y') {
            $filterDate = ' AND trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY("' . $Financial_Year[0]->fdate . '" - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY("' . $Financial_Year[0]->fdate . '" - INTERVAL 1 MONTH), "%Y-%m-%d")';
        } else {
            $filterDate = "";
            $filterDate1 = "";
        }
        //

        if ($isOpening == 2) {
            $po_status = " AND purchase_order.po_status !=1 ";
        } elseif ($isOpening == 1) {
            $po_status = " AND purchase_order.po_status =1 ";
        }

        $TrimsInwardDetails1 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
               
          (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code " . $filterDate1 . ") as out_qty ,
            item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description,rack_master.rack_name
            from trimsInwardDetail
            inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            INNER JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id WHERE 1 " . $filterDate . $po_status . "
            and item_master.cat_id!=4
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code");
        //  DB::enableQueryLog();
        $TrimsInwardDetails2 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code " . $filterDate1 . ") as out_qty ,
            item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description,rack_master.rack_name
            from trimsInwardDetail
            inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id WHERE 1 " . $filterDate . " AND trimsInwardMaster.is_opening=1 
            and item_master.cat_id!=4  
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
            ");

        //   dd(DB::getQueryLog()); 

        return view('TrimsStockData', compact('TrimsInwardDetails1', 'TrimsInwardDetails2', 'isOpening'));
    }



    public function TrimsPOVsGRNDashboard(Request $request)
    {

        if ($request->ajax()) {

            //  DB::enableQueryLog();  
            //DB::enableQueryLog();
            $TrimPOGRNList = DB::select("SELECT  purchase_order.pur_code, purchase_order.pur_date, trimsInwardMaster.trimCode,
            trimsInwardMaster.trimDate, trimsInwardMaster.invoice_no, trimsInwardMaster.invoice_date, item_master.item_name,
            item_master.item_description, unit_master.unit_name, trimsInwardDetail.item_rate,trimsInwardDetail.item_code,
            (select sum(purchaseorder_detail.item_qty) 
            from purchaseorder_detail where purchaseorder_detail.pur_code=purchase_order.pur_code and
            purchaseorder_detail.item_code=trimsInwardDetail.item_code) as po_qty, sum(item_qty) as received_qty,
            (sum(item_qty)*trimsInwardDetail.item_rate) as received_Value FROM `trimsInwardDetail` 
            LEFT join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode 
            inner join purchase_order on purchase_order.pur_code=trimsInwardMaster.po_code 
            LEFT join item_master on item_master.item_code=trimsInwardDetail.item_code 
            LEFT join unit_master on unit_master.unit_id=item_master.unit_id 
            GROUP by trimsInwardMaster.po_code, trimsInwardDetail.item_code");
            //dd(DB::getQueryLog());
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);

            return Datatables::of($TrimPOGRNList)
                ->addIndexColumn()
                ->addColumn('PO_value', function ($row) {

                    $PO_value = round(($row->po_qty * $row->item_rate));

                    return $PO_value;
                })
                ->addColumn('pending_qty', function ($row) {

                    $pending_qty = ($row->po_qty - $row->received_qty);

                    return $pending_qty;
                })

                ->rawColumns(['PO_value', 'pending_qty'])

                ->make(true);
        }

        return view('TrimsPOVsGRNDashboard');
    }

    public function TrimsGRNPrint($trimCode)
    {

        $FirmDetail =  DB::table('firm_master')->first();

        $trimCode = base64_decode($trimCode);
        $TrimsInwardMaster = TrimsInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimsInwardMaster.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimsInwardMaster.Ac_code')
            ->where('trimsInwardMaster.trimCode', $trimCode)
            ->get(['trimsInwardMaster.*', 'usermaster.username', 'ledger_master.Ac_name', 'ledger_master.gst_no', 'ledger_master.pan_no', 'ledger_master.state_id', 'ledger_master.address']);
        return view('TrimsGRNPrint', compact('FirmDetail', 'TrimsInwardMaster'));
    }

    public function TrimsGRNPrintView($trimCode)
    {

        $FirmDetail =  DB::table('firm_master')->first();

        $trimCode = base64_decode($trimCode);
        $TrimsInwardMaster = TrimsInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimsInwardMaster.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimsInwardMaster.Ac_code')
            ->leftJoin('ledger_details', 'trimsInwardMaster.bill_to', '=', 'ledger_details.sr_no')
            ->join('ledger_master as lm', 'lm.Ac_code', '=', 'trimsInwardMaster.Ac_code')
            ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardMaster.po_code')
            // ->leftJoin('ledger_master as lpo', 'lpo.Ac_code', '=', 'purchase_order.Ac_code')
            ->leftJoin('ledger_details as ld', 'ld.sr_no', '=', 'purchase_order.ship_to')

            ->where('trimsInwardMaster.trimCode', $trimCode)
            ->get([
                'trimsInwardMaster.*',
                'usermaster.username',
                'ledger_master.Ac_name',
                'ledger_master.gst_no',
                'ledger_master.pan_no',
                'ledger_master.state_id',
                'ledger_master.address',
                'ledger_details.addr1 as bill_to_addr1',
                'lm.Ac_name as supplier_name',
                'ld.addr1 as po_addr1',
                'ld.trade_name'
            ]);


        return view('TrimsInwardPrintView', compact('FirmDetail', 'TrimsInwardMaster'));
    }



    public function GetOnPageTrimStock()

    {


        $PODetails = DB::select("SELECT ifnull((select count(sr_no) from purchase_order where bom_type in (2,3)),0)  as noOfPO,
     ifnull((select sum(Net_Amount) from purchase_order where bom_type in (2,3)),0) as poTotal,
     
    ifnull((select sum(Net_Amount) from purchase_order where po_status=2 and bom_type in (2,3)),0) as receivedTotal 
       ");

        $GRNTotal = DB::select(" SELECT  
     purchaseorder_detail.item_rate* ifnull((select sum(item_qty) from trimsInwardDetail 
     where trimsInwardDetail.item_code=purchaseorder_detail.item_code),0)  as received_qty_amt  
     FROM `purchaseorder_detail` 
     left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
     ");
        $AmountGrn = 0;
        foreach ($GRNTotal as $row) {
            $AmountGrn = $AmountGrn + $row->received_qty_amt;
        }



        $InwardTrims = DB::select("SELECT purchase_order.sr_no, purchase_order.pur_code,purchase_order.pur_date, job_status_name,
        purchaseorder_detail.item_code, item_name, item_image_path,
        
                            item_description, dimension,color_name, sum(purchaseorder_detail.item_qty) as item_qty ,
                            
                            ifnull((select sum(item_qty) from trimsInwardDetail where trimsInwardDetail.item_code=purchaseorder_detail.item_code
                            and trimsInwardDetail.po_code=purchaseorder_detail.pur_code),0) as received_item_qty ,
                          
                          
                           
                             ifnull((select sum(trimsOutwardDetail.item_qty) from trimsOutwardDetail 
                             where trimsOutwardDetail.item_code=purchaseorder_detail.item_code  
                             and trimsOutwardDetail.po_code=purchaseorder_detail.pur_code),0) as issue_item_qty,
                             
                           
                             (select ifnull((select sum(item_qty) from purchaseorder_detail as pod
                             where pod.item_code=purchaseorder_detail.item_code 
                           and   pod.pur_code=purchaseorder_detail.pur_code and
                           purchaseorder_detail.pur_date > now() - INTERVAL 30 day),0)
                           
                            -
                            ifnull((select sum(item_qty) from trimsInwardDetail 
                            where trimsInwardDetail.item_code=purchaseorder_detail.item_code 
                           and   trimsInwardDetail.po_code=purchaseorder_detail.pur_code 
                           and trimDate > now() - INTERVAL 30 day),0) )as t30_days_item_qty,
                           
                           
                            (select ifnull((select sum(item_qty) from purchaseorder_detail as pod 
                            where pod.item_code=purchaseorder_detail.item_code 
                            and   pod.pur_code=purchaseorder_detail.pur_code 
                            and datediff(current_date,date(pur_date)) BETWEEN  31 AND 60),0)
                           
                            -
                            ifnull((select sum(item_qty) from trimsInwardDetail
                            where trimsInwardDetail.item_code=purchaseorder_detail.item_code 
                            and     trimsInwardDetail.po_code=purchaseorder_detail.pur_code 
                            and datediff(current_date,date(trimDate)) BETWEEN  31 AND 60),0) )as t60_days_item_qty,
                           
                           
                            (select ifnull((select sum(item_qty) from purchaseorder_detail as pod 
                            where pod.item_code=purchaseorder_detail.item_code 
                            and   pod.pur_code=purchaseorder_detail.pur_code and 
                            datediff(current_date,date(pur_date)) BETWEEN  61 AND 90),0)
                           
                            -
                            ifnull((select sum(item_qty) from trimsInwardDetail 
                            where trimsInwardDetail.item_code=purchaseorder_detail.item_code 
                            and trimsInwardDetail.po_code=purchaseorder_detail.pur_code
                            and datediff(current_date,date(trimDate)) BETWEEN  61 AND 90),0) )as t90_days_item_qty 
                           
                           
                            FROM `purchaseorder_detail` 
                            
                            left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
                            left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
                            left outer  join job_status_master on job_status_master.job_status_id=purchase_order.po_status
                            where purchase_order.bom_type in (2,3)
                            group by  purchaseorder_detail.pur_code, purchaseorder_detail.item_code
    ");

        return view('TrimStockOnPage', compact('InwardTrims', 'PODetails', 'AmountGrn'));
    }





    public function GetTrimsInwardList(Request $request)
    {
        $sr_no = $request->input('sr_no');
        $item_code = $request->input('item_code');
        $ItemList = ItemModel::where('item_master.delflag', '=', '0')->get();
        $POList = PurchaseOrderModel::where('sr_no', '=', $sr_no)->first();
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        // echo $sr_no;

        // DB::enableQueryLog();
        $InwardTrims = DB::select("SELECT trimsInwardMaster.`trimCode`, trimsInwardMaster.`trimDate`,   
    trimsInwardDetail.`item_code`, unit_master.unit_name, item_master.item_name, item_master.item_description,
    sum(trimsInwardDetail.`item_qty`) as item_qty , trimsInwardDetail.item_rate, trimsInwardDetail.unit_id
    FROM `trimsInwardDetail` 
    inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode 
    inner join item_master on item_master.item_code=trimsInwardDetail.item_code
    inner join unit_master on unit_master.unit_id=trimsInwardDetail.unit_id
    where trimsInwardMaster.po_code='" . $POList->pur_code . "' and trimsInwardDetail.item_code='" . $item_code . "'
    group by trimsInwardDetail.`trimCode`  , trimsInwardDetail.item_code
    ");
        //   $query = DB::getQueryLog();
        //       $query = end($query);
        //       dd($query);

        $html = '';
        $html .= '<input type="number" value="' . count($InwardTrims) . '" name="cntrr" id="cntrr" readonly="" hidden="true"  />';


        $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>In Code</th>
<th>In Date</th>
<th>Item Name</th>
<th>Description</th>
<th>Unit</th>
<th>Qty</th>
</tr>
</thead>
<tbody>';
        $no = 1;
        foreach ($InwardTrims as $row) {
            $html .= '<tr>';

            $html .= '
<td><input type="text"  value="' . $row->trimCode . '" id="id" style="width:150px;" readOnly/></td>
<td><input type="date"  value="' . $row->trimDate . '" id="id" style="width:100px;" readOnly/></td>
<td><input type="text"  value="' . $row->item_name . '"  style="width:200px;" required readOnly/></td>
<td><input type="text"  value="' . $row->item_description . '"  style="width:200px;" required readOnly/></td>
<td><input type="text"  value="' . $row->unit_name . '"  style="width:80px;" required readOnly/></td>
<td><input type="text"  name="item_qty[]"    value="' . $row->item_qty . '" id="item_qty" style="width:80px;" required readOnly/></td>';

            $html .= '</tr>';
            $no = $no + 1;
        }

        $html .= '</tbody>
    </table>';

        if (count($InwardTrims) != 0) {
            return response()->json(['html' => $html]);
        }
    }



    public function GetComparePOInwardList(Request $request)
    {
        $sr_no = $request->input('sr_no');
        $item_code = $request->input('item_code');
        $ItemList = ItemModel::where('item_master.delflag', '=', '0')->get();
        $POList = PurchaseOrderModel::where('sr_no', '=', $sr_no)->first();
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        // echo $sr_no;
        //   DB::enableQueryLog();
        $InwardTrims = DB::select("SELECT   
    trimsInwardDetail.`item_code`, unit_master.unit_name, item_master.item_name, item_master.item_description,
    (select sum(item_qty) as po_item_qty from purchaseorder_detail where purchaseorder_detail.pur_code='" . $POList->pur_code . "' and purchaseorder_detail.item_code='" . $item_code . "') as po_item_qty,
    sum(trimsInwardDetail.`item_qty`) as item_qty , trimsInwardDetail.item_rate, trimsInwardDetail.unit_id
    FROM `trimsInwardDetail` 
    inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode 
    inner join item_master on item_master.item_code=trimsInwardDetail.item_code
    inner join unit_master on unit_master.unit_id=trimsInwardDetail.unit_id
    where trimsInwardMaster.po_code='" . $POList->pur_code . "' and trimsInwardDetail.item_code='" . $item_code . "'
    group by trimsInwardMaster.po_code, trimsInwardDetail.item_code");
        //   $query = DB::getQueryLog();
        //       $query = end($query);
        //       dd($query);

        $html = '<b>PO No:' . $POList->pur_code . '</b> <br>';
        $html .= '<input type="number" value="' . count($InwardTrims) . '" name="cntrr" id="cntrr" readonly="" hidden="true"  />';


        $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>

<th>Item Name</th>
<th>Description</th>
<th>Unit</th>
<th>PO Qty</th>
<th>Received Qty</th>
<th>To Be Received</th>
</tr>
</thead>
<tbody>';
        $no = 1;
        foreach ($InwardTrims as $row) {
            $html .= '<tr>';

            $html .= '
 
<td><input type="text"  value="' . $row->item_name . '"  style="width:200px;" required readOnly/></td>
<td><input type="text"  value="' . $row->item_description . '"  style="width:200px;" required readOnly/></td>
<td><input type="text"  value="' . $row->unit_name . '"  style="width:80px;" required readOnly/></td>
<td><input type="text"  value="' . $row->po_item_qty . '" id="item_qty" style="width:80px;" required readOnly/></td> 
<td><input type="text"  value="' . $row->item_qty . '" id="item_qty" style="width:80px;" required readOnly/></td>
<td><input type="text"  value="' . ($row->po_item_qty - $row->item_qty) . '" id="item_qty" style="width:80px;" required readOnly/></td>
';



            $html .= '</tr>';
            $no = $no + 1;
        }

        $html .= '</tbody>
    </table>';

        if (count($InwardTrims) != 0) {
            return response()->json(['html' => $html]);
        }
    }

    public function checkPOIsExist(Request $request)
    {
        $trimsOutwardData = DB::table('trimsOutwardDetail')->where('po_code', '=', $request->po_code)->get();
        return response()->json(['html' => count($trimsOutwardData)]);
    }

    public function TrimsStockDataTrial(Request $request)
    {
        $Financial_Year = DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

        $currentDate = $request->currentDate ? $request->currentDate : "";
        if ($currentDate == "") {
            echo "<script>location.href='TrimsStockDataTrial?currentDate=" . date('Y-m-d') . "';</script>";
        }
        if ($currentDate != "") {
            $trimDate = " AND trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('" . $Financial_Year[0]->fdate . "' - INTERVAL 1 MONTH), '" . $currentDate . "')";
            $tout_date = " AND trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('" . $Financial_Year[0]->fdate . "' - INTERVAL 1 MONTH), '" . $currentDate . "')";
        } else {
            $trimDate = "";
            $tout_date = "";
        }

        $TrimsInwardDetails1 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code" . $tout_date . ") as out_qty ,
            trimsInwardMaster.po_code, 
            ledger_master.ac_name,item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description,rack_master.rack_name
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id 
            WHERE item_master.cat_id !=4 " . $trimDate . "
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
        ");

        $isOpening = "";

        return view('TrimsStockDataTrial', compact('TrimsInwardDetails1', 'currentDate', 'isOpening'));
    }

    public function loadDateWiseTrimStockData(Request $request)
    {
        $Amt = 0;
        $Financial_Year = DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

        $currentDate = $request->currentDate ? $request->currentDate : "";
        if ($currentDate == "") {
            echo "<script>location.href='TrimsStockDataTrial?currentDate=" . date('Y-m-d') . "';</script>";
        }
        if ($currentDate != "") {
            $trimDate = " AND trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('" . $Financial_Year[0]->fdate . "' - INTERVAL 1 MONTH), '" . $currentDate . "')";
            $tout_date = " AND trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('" . $Financial_Year[0]->fdate . "' - INTERVAL 1 MONTH), '" . $currentDate . "')";
        } else {
            $trimDate = "";
            $tout_date = "";
        }

        $TrimsInwardDetails1 = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
            (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code" . $tout_date . ") as out_qty ,
            trimsInwardMaster.po_code, 
            ledger_master.ac_name,item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description,rack_master.rack_name
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id 
            WHERE item_master.cat_id !=4 " . $trimDate . "
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
        ");

        $isOpening = "";
        $total_stock_qty = 0;
        $total_value = 0;
        $total_value1 = 0;
        $html = "";
        foreach ($TrimsInwardDetails1 as $row) {
            if ($isOpening == 1) {
                $po_status = ' AND po_status = 1';
            } else if ($isOpening == 2) {
                $po_status = ' AND po_status = 2';
            } else {
                $po_status = "";
            }

            $StatusData = DB::select("select ifnull(purchase_order.po_status,0) as po_status
         from purchase_order WHERE purchase_order.pur_code = '" . $row->po_code . "'" . $po_status);

            if (count($StatusData) > 0) {
                $po_status = $StatusData[0]->po_status;
            } else {
                $po_status = 0;
            }
            $JobStatusList = DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id =' . $po_status);

            if (count($JobStatusList) > 0) {
                $job_status_name = $JobStatusList[0]->job_status_name;
            } else {
                $job_status_name = "-";
            }

            $salesOrderNo = DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='" . $row->po_code . "'");

            if (count($salesOrderNo) > 0) {
                $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                 INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                 where buyer_purchse_order_master.tr_code='" . $salesOrderNo[0]->sales_order_no . "'");

                if (count($buyerData) > 0) {
                    $buyer_name = $buyerData[0]->ac_name;
                } else {
                    $buyer_name = "-";
                }
            } else {
                $buyer_name = "-";
            }

            $values = ($row->item_qty - $row->out_qty) * $row->item_rate;

            //  DB::table('temp_datewise_trims_stock_data')->insert(
            //     array('ac_code' => $row->ac_code,
            //           'buyer_ac_code' => $buyer_name,
            //           'ac_name' => $row->ac_name,
            //           'buyer_ac_name' => $row->buyer_ac_name,
            //           'job_status_id' => $row->job_status_id,
            //           'job_status_name' => $row->job_status_name,
            //           'po_code' => $row->po_code,
            //           'item_code' => $row->item_code,
            //           'item_name' => $row->item_name,
            //           'stock' => number_format($row->item_qty - $row->out_qty),
            //           'item_rate' => $row->item_rate,
            //           'stock_values' => number_format(round($values)),
            //           'dimensions' => $row->dimension,
            //           'color_id' => $row->color_id,
            //           'color_name' => $row->color_name,
            //           'item_description' => $row->item_description,
            //           'rack_id' => $row->rack_id,
            //           'rack_name' => $row->rack_name,
            //           'trimDate' => $row->trimDate,
            //           'tout_date' => $row->tout_date,
            //     )
            //  );

            $html .= '<tr>
            <td style="text-align:center; white-space:nowrap">' . $row->ac_name . '</td>
            <td style="text-align:center; white-space:nowrap">' . $buyer_name . '</td>
            <td style="text-align:center; white-space:nowrap">' . $job_status_name . '</td>
            <td style="text-align:center; white-space:nowrap">' . $row->po_code . '</td>
            <td>' . $row->item_code . '</td>
            <td style="text-align:center; white-space:nowrap">' . $row->item_name . '</td>
            <td style="text-align:right;">' . number_format($row->item_qty - $row->out_qty) . '</td>
            <td style="text-align:right;">' . $row->item_rate . '</td>
            <td style="text-align:right;">' . number_format(round($values)) . '</td>
            <td style="text-align:right;">' . $row->dimension . '</td>
            <td style="text-align:center; white-space:nowrap">' . $row->color_name . '</td>
            <td style="text-align:center; white-space:nowrap">' . $row->item_description . '</td>
            <td style="text-align:center; white-space:nowrap">' . $row->rack_name . '</td>
         </tr>';
            $Amt = $Amt + round(($row->item_qty - $row->out_qty) * $row->item_rate);
        }
        $isOpening = "";
        return response()->json(['html' => $html, 'currentDate' => $currentDate, 'Amt' => money_format('%!i', round($Amt, 2))]);
    }

    public function TrimsStockDataTrialCloned(Request $request)
    {
        // $TrimsInwardDetails =DB::select("select * from dump_trim_stock_data");

        $currentDate = $request->currentDate ? $request->currentDate : "";
        if ($currentDate == "") {
            echo "<script>location.href='TrimsStockDataTrialCloned?currentDate=" . date('Y-m-d') . "';</script>";
        }
        return view('TrimsStockDataTrialCloned', compact('currentDate'));
    }

    public function TrimsStocks1()
    {
        DB::table('dump_trim_stock_data')->delete();
        try {
            $trimData =  DB::SELECT("select trimsInwardMaster.trimDate,trimsInwardMaster.trimCode,trimsInwardMaster.po_code as po_no,trimsInwardDetail.item_code,
                ledger_master.ac_name, sum(trimsInwardDetail.item_qty) as grn_qty,trimsInwardDetail.item_rate as rate,trimsInwardDetail.rack_id,job_status_master.job_status_name,purchase_order.po_status,
                trimsInwardMaster.po_code,trimsInwardDetail.amount as amount,
                ledger_master.ac_name as suplier_name,item_master.dimension,item_master.item_name,
                item_master.color_name,item_master.item_description
                from trimsInwardDetail
                left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
                left join item_master on item_master.item_code=trimsInwardDetail.item_code
                left join purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                left join job_status_master ON job_status_master.job_status_id = purchase_order.po_status
                WHERE item_master.cat_id !=4 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code,trimsInwardDetail.trimCode");

            foreach ($trimData as $row) {
                $trimDate = str_replace('"', "", $row->trimDate);
                $suplier_name = str_replace('"', "", $row->suplier_name);
                $po_no = str_replace('"', "", $row->po_no);
                $trimCode = str_replace('"', "", $row->trimCode);
                $item_code = str_replace('"', "", $row->item_code);
                $item_name = str_replace('"', "", $row->item_name);
                $color =  "";
                $item_description = str_replace('"', "", $row->item_description);
                $grn_qty = str_replace('"', "", $row->grn_qty);
                $rate = str_replace('"', "", $row->rate);
                $rack_id = str_replace('"', "", $row->rack_id);
                $ac_code = 0;
                $suplier_id = 0;
                $unit_id = 0;
                $po_status = $row->job_status_name;
                $job_status_id = $row->po_status;
                $amount = str_replace('"', "", $row->amount);
                $ind_outward_qty1 = "";
                $ind_outward_qty = 0;
                $tout_date = "";
                $outward_qty = 0;

                //DB::enableQueryLog(); 

                DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,tout_date,suplier_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
                                select "' . $trimDate . '","' . $tout_date . '","' . $suplier_name . '","' . $po_no . '","' . $item_code . '","' . $item_name . '","' . $rate . '", "' . $color . '",
                                        "' . $item_description . '", "' . $po_status . '","' . $job_status_id . '", "' . $rack_id . '","' . $ac_code . '","' . $suplier_id . '","' . $unit_id . '","' . $trimCode . '","' . $grn_qty . '","' . $outward_qty . '","' . $ind_outward_qty . '","' . $amount . '"');
                //dd(DB::getQueryLog());

            }
        } catch (\Exception $e) {

            DB::table('dump_trim_stock_data')->delete();
        }
        return 1;
        exit;
    }

    public function UpdateFoutDumpData()
    {
        $trimData =  DB::SELECT("select *  from dump_trim_stock_data");
        $ind_outward_qty1 = 0;
        $ind_outward_qty = 0;
        $tout_date = "";
        $outward_qty = 0;

        foreach ($trimData as $row) {
            $outwardData1 = DB::SELECT("select ifnull(item_qty,0) as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='" . $row->po_no . "' AND item_code ='" . $row->item_code . "'");

            foreach ($outwardData1 as $OD) {
                $outQty = isset($ind_outward_qty1) ? $ind_outward_qty1 : 0;
                $ind_outward_qty1 = $OD->tout_date . "=>" . $OD->outward_qty . "," . $outQty;
            }

            $outwardData = DB::SELECT("select ifnull(sum(item_qty),0) as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='" . $row->po_no . "' AND item_code=" . $row->item_code);

            $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
            $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0;

            DB::table('dump_trim_stock_data')->where('trimDate', $row->trimDate)->where('trimCode', $row->trimCode)->where('po_no', $row->po_no)->where('item_code', $row->item_code)->update(['ind_outward_qty' => $ind_outward_qty1, 'tout_date' => $tout_date, 'outward_qty' => $outward_qty]);
            $ind_outward_qty1 = 0;
        }
    }

    public function LoadTrimsStockDataTrialCloned(Request $request)
    {

        $currentDate = $request->currentDate ? $request->currentDate : "";
        $job_status_id = $request->job_status_id ? $request->job_status_id : 0;

        //   $fabricData = DB::SELECT("select inward_details.in_date,'',ledger_master.ac_name as suplier_name,inward_master.po_code as po_no,inward_details.in_code as grn_no,inward_master.invoice_no,item_master.item_code,item_master.item_image_path as preview,shade_master.shade_name as shade_no,item_master.item_name,
        //     quality_master.quality_name, item_master.color_name as color,item_master.item_description,ifnull(purchase_order.po_status,0) as po_status,inward_details.track_code as track_name, inward_details.meter as grn_qty, 
        //     inward_details.item_rate as rate,inward_details.rack_id from inward_details 
        //     left join inward_master on inward_master.in_code=inward_details.in_code
        //     left JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
        //     left join ledger_master on ledger_master.ac_code=inward_details.Ac_code                    
        //     left join item_master on item_master.item_code=inward_details.item_code 
        //     left join quality_master on quality_master.quality_code=item_master.quality_code  
        //     left join shade_master on shade_master.shade_id=inward_details.shade_id");


        // $TrimsInwardDetails =DB::select("SELECT dump_trim_stock_data.*, (SELECT sum(grn_qty) FROM dump_trim_stock_data AS df WHERE df.po_no = dump_trim_stock_data.po_no 
        //                         AND df.item_code= dump_trim_stock_data.item_code  AND df.trimDate = dump_trim_stock_data.trimDate
        //                         AND df.trimDate <= '".$currentDate."') as gq,
        //                         (SELECT sum(outward_qty) FROM dump_trim_stock_data as df1 WHERE df1.po_no = dump_trim_stock_data.po_no 
        //                         AND df1.item_code= dump_trim_stock_data.item_code AND df1.tout_date = dump_trim_stock_data.tout_date
        //                         AND df1.tout_date <= '".$currentDate."') as oq FROM dump_trim_stock_data GROUP BY po_no");
        //DB::enableQueryLog();  
        $filter = '';
        if ($job_status_id > 0) {
            if ($job_status_id == 1) {
                $filter .= " AND job_status_id ='1' OR dump_trim_stock_data.closeDate > '" . $currentDate . "'";
            } else {
                $filter .= " AND job_status_id IN(0,2) AND dump_trim_stock_data.trimDate <= '" . $currentDate . "'";
            }
        }

        // $TrimsInwardDetails =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq,ledger_master.ac_short_name as buyer_name,po_type_master.po_type_name,
        //                         (SELECT trade_name FROM ledger_details WHERE ledger_details.ac_code = dump_trim_stock_data.po_no) as trade_name,
        //                         (SELECT site_code FROM ledger_details WHERE ledger_details.ac_code = dump_trim_stock_data.po_no) as site_code
        //                         FROM dump_trim_stock_data 
        //                         INNER JOIN item_master ON item_master.item_code = dump_trim_stock_data.item_code
        //                         LEFT JOIN purchase_order ON purchase_order.pur_code = dump_trim_stock_data.po_no
        //                         LEFT JOIN po_type_master ON po_type_master.po_type_id = purchase_order.po_type_id
        //                         LEFT JOIN ledger_master ON ledger_master.ac_code = purchase_order.buyer_id
        //                         WHERE item_master.class_id != 94 AND trimDate <='".$currentDate."' ".$filter." GROUP BY dump_trim_stock_data.po_no,dump_trim_stock_data.item_code");


        $TrimsInwardDetails = DB::select("
            SELECT dump_trim_stock_data.*, 
                   LM1.ac_short_name as suplier_name,
                   SUM(grn_qty) AS gq,
                   SUM(outward_qty) AS oq,
                   ledger_master.ac_short_name AS buyer_name,
                   po_type_master.po_type_name,
                    COALESCE(
                        (SELECT trade_name 
                         FROM ledger_details 
                         WHERE sr_no = purchase_order.bill_to 
                         LIMIT 1)
                    ) AS trade_name,
            
                COALESCE(
                    (SELECT site_code 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS site_code
            FROM dump_trim_stock_data
            INNER JOIN item_master 
                ON item_master.item_code = dump_trim_stock_data.item_code
            LEFT JOIN purchase_order 
                ON purchase_order.pur_code = dump_trim_stock_data.po_no
            LEFT JOIN trimsInwardMaster 
                ON trimsInwardMaster.trimCode = dump_trim_stock_data.trimCode
            LEFT JOIN po_type_master 
                ON po_type_master.po_type_id = purchase_order.po_type_id
            LEFT JOIN ledger_master 
                ON ledger_master.ac_code = purchase_order.buyer_id
            LEFT JOIN ledger_master as LM1
                ON LM1.ac_code = trimsInwardMaster.Ac_code
            WHERE item_master.class_id != 94 
              AND dump_trim_stock_data.trimDate <= '" . $currentDate . "' 
              " . $filter . "
            GROUP BY dump_trim_stock_data.po_no, dump_trim_stock_data.item_code
        ");


        //dd(DB::getQueryLog());
        $html = [];
        $total_value = 0;
        $total_stock = 0;
        $total_amount = 0;

        foreach ($TrimsInwardDetails as $row) {
            $q_qty = 0;
            $outward_qty = isset($row->oq) ? $row->oq : 0;
            $grn_qty = isset($row->gq) ? $row->gq : 0;
            $ind_outward1 = (explode(",", $row->ind_outward_qty));


            foreach ($ind_outward1 as $indu) {

                $ind_outward2 = (explode("=>", $indu));

                if ($ind_outward2[0] <= $currentDate) {
                    $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                    $q_qty = $q_qty + $ind_out;
                }
            }

            $stocks =  $row->gq - $q_qty;


            if (Session::get('userId') == 1 || Session::get('userId') == 2) {
                $disbaled = "";
            } else {
                $disbaled = "disabled";
            }
            $dis = '';
            if ($row->job_status_id == 1 || $currentDate < $row->closeDate) {
                $slider = "Moving";
                $chk = 'checked';
                if ($currentDate < $row->closeDate) {
                    $dis = "disabled";
                }
            } else {
                $slider = "Non Moving";
                $dis = "disabled";
                $chk = '';
            }

            if ($job_status_id == 1) {
                if ($row->closeDate == '' || $row->closeDate == '0000-00-00') {
                    $action = '<label class="switch">
                            <input type="checkbox" ' . $chk . ' ' . $disbaled . ' ' . $dis . ' onchange="updateSliderState(this);" po_no=' . $row->po_no . ' item_code=' . $row->item_code . '>
                            <span class="slider round" data-state="' . $slider . '"></span>
                        </label>';
                } else {
                    $action = '-';
                }
            } else {
                $action = '-';
            }

            if ($row->buyer_name == '') {
                $BuyerData = DB::select("SELECT ledger_master.ac_short_name as buyer_name FROM trimsInwardMaster LEFT JOIN ledger_master ON ledger_master.ac_code = trimsInwardMaster.buyer_id WHERE po_code ='" . $row->po_no . "'");

                $buyer_name = isset($BuyerData[0]->buyer_name) ? $BuyerData[0]->buyer_name : "-";
            } else {
                $buyer_name = $row->buyer_name;
            }

            $closeDate = '';
            if ($row->closeDate != '') {
                $closeDate = date("d-M-Y", strtotime($row->closeDate));
            }


            if ($row->site_code != '') {
                $bill_to = $row->trade_name . '(' . $row->site_code . ')';
            } else {
                $bill_to = $row->trade_name;
            }

            if ($bill_to == '') {
                // DB::enableQueryLog();
                $tradeData = DB::select("SELECT ledger_details.trade_name FROM ledger_details LEFT JOIN ledger_master ON ledger_master.ac_code = ledger_details.ac_code WHERE ac_short_name LIKE '%" . $row->suplier_name . "%' LIMIT 1");
                //  dd(DB::getQueryLog());
                $tn = isset($tradeData[0]->trade_name) ? $tradeData[0]->trade_name : "";
                $sc = isset($tradeData[0]->site_code) ? $tradeData[0]->site_code : "";

                if ($sc != '') {
                    $bill_to = $tn . '(' . $sc . ')';
                } else {
                    $bill_to = $tn;
                }
            }

            $html[] =  array(
                'Action' => $action,
                'suplier_name' => $row->suplier_name,
                'bill_to' => $bill_to,
                'buyer_name' => $buyer_name,
                'po_status' => $slider,
                'closeDate' => $closeDate,
                'po_no' => $row->po_no,
                'po_type_name' => $row->po_type_name,
                'item_code' => $row->item_code,
                'item_name' => $row->item_name,
                'width' => $row->width,
                'color' => $row->color,
                'item_description' => $row->item_description,
                'gq'     => number_format(round($row->gq, 2), 2, '.', ','),
                'q_qty'  => number_format(round($q_qty, 2), 2, '.', ','),
                'stocks' => number_format(round($stocks, 2), 2, '.', ','),
                'rate'   => number_format(round($row->rate, 2), 2, '.', ','), // consistent with others
                'value'  => number_format(round($stocks * $row->rate, 2), 2, '.', ','),

            );

            $total_value += ($stocks * $row->rate);
            $total_stock +=  $stocks;
            $total_amount +=  $row->amount;
        }

        $jsonData = json_encode($html);

        return response()->json(['html' => $jsonData, 'total_stock' => round($total_stock / 100000, 2), 'currentDate' => $currentDate, 'total_value' => round($total_value / 100000, 2), 'total_amount' => round($total_amount / 100000, 2)]);
    }



    public function GetTrimCodeWiseData(Request $request)
    {
        $stockData = DB::select("select stock_association.*,item_master.item_name from stock_association 
           INNER JOIN item_master ON item_master.item_code = stock_association.item_code
           where tr_code ='" . $request->trimCode . "'");
        if (count($stockData) > 0) {
            $isclick = 1;
            $allocateBtn = 'btn-success';
        } else {
            $isclick = 0;
            $allocateBtn = 'btn-danger';
        }
        //DB::enableQueryLog();
        $detailpurchase = TrimsInwardDetailModel::join('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code', 'left')
            ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'trimsInwardDetail.po_code')
            ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
            ->where('trimCode', '=', $request->trimCode)
            ->get(['trimsInwardDetail.*', 'item_master.item_name', 'item_master.item_description', 'purchase_order.bom_code', 'item_master.cat_id', 'item_master.class_id', 'classification_master.class_name']);
        //dd(DB::getQueryLog());    
        $itemlist = DB::table('item_master')->where('item_master.delflag', '0')->where('item_master.cat_id', '!=', '1')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag', '=', '0')->get();
        $RackList = RackModel::where('rack_master.delflag', '=', '0')->get();

        $html = '';

        $po_sr_no = DB::select("select sr_no from purchase_order where pur_code='" . $request->po_code . "'");
        $no = 1;

        foreach ($detailpurchase as $row) {
            $srno = isset($po_sr_no[0]->sr_no) ? $po_sr_no[0]->sr_no : 'Opening';
            $item_code = $row->item_code;


            $grnData = DB::SELECT("SELECT sum(item_qty) as inward_qty FROM trimsInwardDetail WHERE po_code='" . $row->po_code . "' AND item_code=" . $item_code);
            $outwardData = DB::SELECT("SELECT sum(item_qty) as outward_qty FROM trimsOutwardDetail WHERE po_code='" . $row->po_code . "' AND item_code=" . $item_code);
            $grn_qty = isset($grnData[0]->inward_qty) ? $grnData[0]->inward_qty : 0;
            $out_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0;
            $max_qty = $grn_qty - $out_qty;
            $concated = $srno . "," . $item_code;

            $html .= '<tr>
              <td><input type="text" name="id" value="' . $no . '" id="id"  style="width:50px;"/></td>
              <td>
                 <span onclick="openmodal(' . $concated . ')" style="color:#556ee6; cursor: pointer;">' . $row->item_code . '</span>
              </td>
              <td>
                 <select name="item_codes[]" class="select2" id="item_codes"  class="select2" style="width:260px;height:30px;" onchange="GetUnit(this);" disabled>';

            $html .= '<option value="' . $row->item_code . '">' . $row->item_name . '-(' . $row->item_code . ')</option>';

            $html .= '</select>
              </td>
              <td><input type="text"  value="' . $row->class_name . '" style="width:250px;height:30px;" readonly> </td>
              <td>
                 <select name="unit_ids[]"   id="unit_ids"   style="width:80px;height:30px;" disabled>
                    <option value="">--- Select Unit ---</option>';
            foreach ($unitlist as  $rowunit) {
                $html .= '<option value="' . $rowunit->unit_id . '" ' . ($rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '') . ' >' . $rowunit->unit_name . '</option>';
            }
            $html .= '</select>
              </td>
              <td><input   type="number" step="any" class="QTY" min="' . ($row->item_qty - $max_qty) . '" name="item_qtys[]" value="' . $row->item_qty . '" stock="' . $max_qty . '" max="' . ($row->item_qty) . '" style="width:80px;height:30px;" onchange="checkNumber(this);" id="item_qty">
                 <input type="hidden" name="hsn_codes[]"   value="' . $row->hsn_code . '" id="hsn_codes" style="width:80px;height:30px;" required/>
              </td>
              <td><input type="number" step="any"  name="item_rates[]"    value="' . $row->item_rate . '" id="item_rates" style="width:80px;height:30px;" required/>
              <td><input type="number" step="any" readOnly  name="amounts[]" class="AMT"  value="' . $row->amount . '" id="amounts" style="width:80px;height:30px;" required/>
              <td>
                 <select name="rack_id[]"  id="rack_id" class="select2" style="width:100px;height:30px;" required>
                    <option value="">--Racks--</option>';
            foreach ($RackList as  $rowrack) {
                $html .= '<option value="' . $rowrack->rack_id . '" ' . ($rowrack->rack_id == $row->rack_id ? 'selected="selected"' : '') . '>' . $rowrack->rack_name . '</option>';
            }
            $html .= '</select>
              </td>
              <td style="display: flex;">
                 <button type="button" onclick=" mycalc();" class="Abutton btn btn-warning  btn-sm  pull-left" disabled>+</button>  
                 <button type="button" name="allocate[]"  onclick="stockAllocate(this);" item_code="' . $row->item_code . '" isClick="' . $isclick . '" qty="' . $row->item_qty . '" bom_code="' . $row->bom_code . '" cat_id="' . $row->cat_id . '" class_id="' . $row->class_id . '"  class="btn ' . $allocateBtn . ' pull-left ml-2" style="margin-left: 10px;">Allocate</button> 
                 <input type="button" class="btn btn-danger pull-left ml-2" onclick="deleteRow(this);" value="X" style="margin-left: 10px;" >  
              </td>
           </tr>';
            $no = $no + 1;
        }

        return response()->json(['html' => $html]);
    }

    public function GetTrimCodeWiseStockData(Request $request)
    {

        $stockData = DB::select("select stock_association.*,item_master.item_name from stock_association 
                INNER JOIN item_master ON item_master.item_code = stock_association.item_code
                where tr_code ='" . $request->trimCode . "'");

        $html = '';
        if (count($stockData) > 0) {
            foreach ($stockData as $row) {
                $html = '<tr>
                    <td><input type="text" name="stock_bom_code[]" value="' . $row->bom_code . '" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="sales_order_no[]" value="' . $row->sales_order_no . '" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="item_code[]" value="' . $row->item_code . '" class="form-control" style="width:100px;" readonly=""></td>
                    <td><input type="text" name="item_name[]" value="' . $row->item_name . '" class="form-control" style="width:260px;" readonly=""></td>
                    <td>
                        <input type="text" name="allocate_qty[]" value="' . $row->qty . '" class="form-control" style="width:100px;" readonly="">
                        <input type="hidden" name="cat_id[]" value="' . $row->cat_id . '" class="form-control" style="width:100px;" >
                        <input type="hidden" name="class_id[]" value="' . $row->class_id . '"  class="form-control" style="width:100px;">
                    </td>
                </tr>';
            }
        }

        return response()->json(['html' => $html]);
    }

    public function GetTrimsInOutStockReportForm()
    {
        return view('GetTrimsInOutStockReportForm');
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

    public function TrimsInOutStockReport(Request $request)
    {

        $fdate = $request->fdate;
        $tdate = $request->tdate;

        if ($tdate > date('Y-m-d')) {
            $tdate = date('Y-m-d');
        }


        $period = $this->getBetweenDates($fdate, $tdate);

        $FirmDetail =  DB::table('firm_master')->first();

        return view('TrimsInOutStockReport', compact('period', 'fdate', 'tdate', 'FirmDetail'));
    }


    public function RunCronTrimJob()
    {
        date_default_timezone_set("Asia/Calcutta");
        $time = date("H:i", strtotime("+60 seconds"));

        DB::table('syncronization_time_mgmt')->update(['sync_table' => 0]);
        DB::table('syncronization_time_mgmt')->where('stmt_type', '=', 2)->update(['start_time' => $time, 'status' => 0, 'sync_table' => 1]);
    }


    public function getTrimsPODetails(Request $request)
    {
        $po_code = $request->po_code;
        //echo $po_code;
        //DB::enableQueryLog();
        $MasterdataList = DB::select("select pur_code, purchase_order.Ac_code, ledger_master.ac_name, purchase_order.po_type_id from purchase_order 
        inner join ledger_master on ledger_master.ac_code=purchase_order.Ac_code
        where purchase_order.pur_code='" . $po_code . "'");
        //dd(DB::getQueryLog());
        return json_encode($MasterdataList);
    }

    public function TrimsInventoryAgingReport()
    {
        return view('TrimsInventoryAgingReport');
    }

    public function loadTrimsInventoryAgingReport(Request $request)
    {
        $currentDate = $request->current_date;

        $TrimsInwardDetails = DB::select("SELECT df.*,item_master.item_name, (SELECT sum(grn_qty) FROM dump_trim_stock_data WHERE po_no = df.po_no AND item_code = df.item_code AND trimDate <='" . $currentDate . "') as gq  
                                        FROM dump_trim_stock_data as df 
                                        INNER JOIN item_master ON item_master.item_code = df.item_code
                                        WHERE item_master.class_id != 94 AND df.trimDate <='" . $currentDate . "'  GROUP BY df.po_no,df.item_code");


        $html = [];

        // Initialize arrays to store aggregated sums
        $aggregatedData = [];

        foreach ($TrimsInwardDetails as $row) {
            $item_code = $row->item_code;

            // Initialize if item_code doesn't exist in aggregatedData
            if (!isset($aggregatedData[$item_code])) {
                $aggregatedData[$item_code] = [
                    'item_code' => $row->item_code,
                    'item_name' => $row->item_name,
                    'total_value30' => 0,
                    'total_value60' => 0,
                    'total_value90' => 0,
                    'total_value180' => 0,
                    'total_value365' => 0,
                    'previousYearValue' => 0,
                    'total_value' => 0,
                ];
            }


            $grn_qty = isset($row->gq) ? $row->gq : 0;
            $ind_outward1 = (explode(",", $row->ind_outward_qty));
            $q_qty = 0;

            foreach ($ind_outward1 as $indu) {
                $ind_outward2 = (explode("=>", $indu));
                $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;

                if ($ind_outward2[0] <= $currentDate) {
                    $q_qty += $q_qty1;
                }
            }

            $stocks = $row->gq - $q_qty;

            // Assign stock values based on date ranges
            $stocks1 = ($row->trimDate >= date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks2 = ($row->trimDate >= date('Y-m-d', strtotime('-60 days')) && $row->trimDate < date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks3 = ($row->trimDate >= date('Y-m-d', strtotime('-90 days')) && $row->trimDate < date('Y-m-d', strtotime('-60 days'))) ? $stocks : 0;
            $stocks4 = ($row->trimDate >= date('Y-m-d', strtotime('-180 days')) && $row->trimDate < date('Y-m-d', strtotime('-90 days'))) ? $stocks : 0;
            $stocks5 = ($row->trimDate >= date('Y-m-d', strtotime('-365 days')) && $row->trimDate < date('Y-m-d', strtotime('-180 days'))) ? $stocks : 0;
            $stocks6 = ($row->trimDate <= date('Y-m-d', strtotime('-1 year'))) ? $stocks : 0;

            // Calculate total stock and total value
            $total_stock = $stocks1 + $stocks2 + $stocks3 + $stocks4 + $stocks5 + $stocks6;
            $total_value = ($stocks1 * $row->rate) + ($stocks2 * $row->rate) + ($stocks3 * $row->rate) + ($stocks4 * $row->rate) + ($stocks5 * $row->rate) + ($stocks6 * $row->rate);

            // Aggregate the sums into $aggregatedData
            $aggregatedData[$item_code]['total_value30'] += round($stocks1 * $row->rate, 2);
            $aggregatedData[$item_code]['total_value60'] += round($stocks2 * $row->rate, 2);
            $aggregatedData[$item_code]['total_value90'] += round($stocks3 * $row->rate, 2);
            $aggregatedData[$item_code]['total_value180'] += round($stocks4 * $row->rate, 2);
            $aggregatedData[$item_code]['total_value365'] += round($stocks5 * $row->rate, 2);
            $aggregatedData[$item_code]['previousYearValue'] += round($stocks6 * $row->rate, 2);
            $aggregatedData[$item_code]['total_value'] += round($total_value, 2);
        }

        // Prepare final HTML output
        foreach ($aggregatedData as $data) {
            if ($data['total_value'] > 0) {
                $html[] = [
                    'srno' => count($html) + 1,
                    'item_code' => $data['item_code'],
                    'item_name' => $data['item_name'],
                    'total_value30' => money_format("%!.0n", $data['total_value30']),
                    'total_value60' => money_format("%!.0n", $data['total_value60']),
                    'total_value90' => money_format("%!.0n", $data['total_value90']),
                    'total_value180' => money_format("%!.0n", $data['total_value180']),
                    'total_value365' => money_format("%!.0n", $data['total_value365']),
                    'previousYearValue' => money_format("%!.0n", $data['previousYearValue']),
                    'total_value' => money_format("%!.0n", $data['total_value']),
                ];
            }
        }

        $jsonData = json_encode($html);
        return response()->json(['html' => $jsonData, 'currentDate' => $currentDate]);
    }

    public function SyncTrimsStock()
    {

        //  DB::table('dump_trim_stock_data')->delete();

        //  DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => "", 'status' => 0]);

        //  $trimData =  DB::SELECT("select trimsInwardMaster.trimDate,trimsInwardMaster.trimCode,trimsInwardMaster.po_code as po_no,trimsInwardDetail.item_code,
        //     ledger_master.ac_name, sum(trimsInwardDetail.item_qty) as grn_qty,trimsInwardDetail.item_rate as rate,trimsInwardDetail.rack_id,job_status_master.job_status_name,purchase_order.po_status,
        //     trimsInwardMaster.po_code,trimsInwardDetail.amount as amount,
        //     ledger_master.ac_name as suplier_name,item_master.dimension,item_master.item_name,
        //     item_master.color_name,item_master.item_description
        //     from trimsInwardDetail
        //     left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
        //     left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
        //     left join item_master on item_master.item_code=trimsInwardDetail.item_code
        //     left join purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code 
        //     left join job_status_master ON job_status_master.job_status_id = purchase_order.po_status 
        //     WHERE item_master.cat_id !=4 AND item_master.class_id != 94 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code,trimsInwardDetail.trimCode");

        //   foreach($trimData as $row)
        //   {  
        //         $buyerData = DB::SELECT("select LM1.ac_name as buyer_name,purchaseorder_detail.job_status_id,job_status_master.job_status_name FROM purchaseorder_detail 
        //                                                             INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = purchaseorder_detail.sales_order_no 
        //                                                             INNER JOIN purchase_order ON purchase_order.pur_code = purchaseorder_detail.pur_code 
        //                                                             INNER JOIN ledger_master as LM1 ON LM1.ac_code = purchase_order.buyer_id 
        //                                                             LEFT JOIN job_status_master ON job_status_master.job_status_id = purchaseorder_detail.job_status_id 
        //                                                             WHERE purchaseorder_detail.pur_code = '". $row->po_no."' AND purchaseorder_detail.item_code=".$row->item_code."
        //                                                             GROUP BY purchaseorder_detail.pur_code");

        //         $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
        //         if($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "")
        //         {
        //             $job_status_id =  1;
        //             $po_status = "Moving";
        //         }
        //         else
        //         {
        //             $job_status_id = 2;
        //             $po_status = "Non Moving";
        //         }

        //         $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
        //         $trimDate = str_replace('"', "", $row->trimDate);
        //         $suplier_name = str_replace('"', "", $row->suplier_name);  
        //         $po_no = str_replace('"', "", $row->po_no);
        //         $trimCode = str_replace('"', "", $row->trimCode); 
        //         $item_code = str_replace('"', "", $row->item_code); 
        //         $item_name = str_replace('"', "", $row->item_name);
        //         $color =  "";
        //         $item_description = str_replace('"', "", $row->item_description); 
        //         $grn_qty = str_replace('"', "", $row->grn_qty);
        //         $rate = str_replace('"', "", $row->rate);
        //         $rack_id = str_replace('"', "", $row->rack_id);
        //         $ac_code = 0;
        //         $suplier_id = 0;
        //         $unit_id = 0;
        //         $amount = str_replace('"', "", $row->amount);  

        //         $outwardData = DB::SELECT("select sum(item_qty) as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='".$row->po_no."' AND item_code=".$row->item_code);
        //         $ind_outward_qty1 = "";
        //         $outwardData1 = DB::SELECT("select item_qty as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='".$row->po_no."' AND item_code=".$row->item_code);

        //         foreach($outwardData1 as $OD)
        //         {
        //             $ind_outward_qty1 = $OD->tout_date."=>".$OD->outward_qty.",".$ind_outward_qty1;
        //         }

        //         $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
        //         $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
        //         $ind_outward_qty = rtrim($ind_outward_qty1,","); 

        //       //DB::enableQueryLog(); 

        //          DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,tout_date,suplier_name,buyer_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
        //                 select "'.$trimDate.'","'.$tout_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$item_code.'","'.addslashes($item_name).'","'.$rate.'", "'.$color.'",
        //                         "'.addslashes($item_description).'", "'.$po_status.'", "'.$job_status_id.'", "'.$rack_id.'","'.$ac_code.'","'.$suplier_id.'","'.$unit_id.'","'.$trimCode.'","'.$grn_qty.'","'.$outward_qty.'","'.$ind_outward_qty.'","'.$amount.'"');
        //         //dd(DB::getQueryLog());
        //   }

        // date_default_timezone_set("Asia/Calcutta");
        // DB::table('syncronization_time_mgmt')->where('stmt_type','=',2)->update(['end_time' => date("H:i", time()), 'status' => 1,'sync_table'=>0]);
        echo json_encode('ok');
    }

    public function test()
    {
        DB::table('dump_trim_stock_data')->delete();

        DB::table('syncronization_time_mgmt')->where('stmt_type', '=', 2)->update(['end_time' => "", 'status' => 0]);

        $trimData =  DB::SELECT("select trimsInwardMaster.trimDate,trimsInwardMaster.trimCode,trimsInwardMaster.po_code as po_no,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(trimsInwardDetail.item_qty) as grn_qty,trimsInwardDetail.item_rate as rate,trimsInwardDetail.rack_id,job_status_master.job_status_name,purchase_order.po_status,
            trimsInwardMaster.po_code,trimsInwardDetail.amount as amount,
            ledger_master.ac_name as suplier_name,item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code 
            left join job_status_master ON job_status_master.job_status_id = purchase_order.po_status 
            WHERE item_master.cat_id !=4 AND item_master.class_id != 94 group by trimsInwardDetail.po_code,trimsInwardDetail.item_code,trimsInwardDetail.trimCode");

        foreach ($trimData as $row) {
            $buyerData = DB::SELECT("select LM1.ac_short_name as buyer_name,purchaseorder_detail.job_status_id,job_status_master.job_status_name FROM purchaseorder_detail 
                                                                    INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = purchaseorder_detail.sales_order_no 
                                                                    INNER JOIN purchase_order ON purchase_order.pur_code = purchaseorder_detail.pur_code 
                                                                    INNER JOIN ledger_master as LM1 ON LM1.ac_code = purchase_order.buyer_id 
                                                                    LEFT JOIN job_status_master ON job_status_master.job_status_id = purchaseorder_detail.job_status_id 
                                                                    WHERE purchaseorder_detail.pur_code = '" . $row->po_no . "' AND purchaseorder_detail.item_code=" . $row->item_code . "
                                                                    GROUP BY purchaseorder_detail.pur_code");

            $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
            if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") {
                $job_status_id =  1;
                $po_status = "Moving";
            } else {
                $job_status_id = 2;
                $po_status = "Non Moving";
            }

            $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
            $trimDate = str_replace('"', "", $row->trimDate);
            $suplier_name = str_replace('"', "", $row->suplier_name);
            $po_no = str_replace('"', "", $row->po_no);
            $trimCode = str_replace('"', "", $row->trimCode);
            $item_code = str_replace('"', "", $row->item_code);
            $item_name = str_replace('"', "", $row->item_name);
            $color =  "";
            $item_description = str_replace('"', "", $row->item_description);
            $grn_qty = str_replace('"', "", $row->grn_qty);
            $rate = str_replace('"', "", $row->rate);
            $rack_id = str_replace('"', "", $row->rack_id);
            $ac_code = 0;
            $suplier_id = 0;
            $unit_id = 0;
            $amount = str_replace('"', "", $row->amount);

            $outwardData = DB::SELECT("select sum(item_qty) as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='" . $row->po_no . "' AND item_code=" . $row->item_code);
            $ind_outward_qty1 = "";
            $outwardData1 = DB::SELECT("select item_qty as outward_qty,tout_date FROM trimsOutwardDetail WHERE po_code ='" . $row->po_no . "' AND item_code=" . $row->item_code);

            foreach ($outwardData1 as $OD) {
                $ind_outward_qty1 = $OD->tout_date . "=>" . $OD->outward_qty . "," . $ind_outward_qty1;
            }

            $tout_date = isset($outwardData[0]->tout_date) ? $outwardData[0]->tout_date : "";
            $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0;
            $ind_outward_qty = rtrim($ind_outward_qty1, ",");

            //DB::enableQueryLog(); 

            DB::SELECT('INSERT INTO dump_trim_stock_data(trimDate,tout_date,suplier_name,buyer_name,po_no,item_code,item_name,rate,color,item_description,po_status,job_status_id,rack_id,ac_code,suplier_id,unit_id,trimCode,grn_qty,outward_qty,ind_outward_qty,amount)
                        select "' . $trimDate . '","' . $tout_date . '","' . $suplier_name . '","' . $buyerName . '","' . $po_no . '","' . $item_code . '","' . addslashes($item_name) . '","' . $rate . '", "' . $color . '",
                                "' . addslashes($item_description) . '", "' . $po_status . '", "' . $job_status_id . '", "' . $rack_id . '","' . $ac_code . '","' . $suplier_id . '","' . $unit_id . '","' . $trimCode . '","' . $grn_qty . '","' . $outward_qty . '","' . $ind_outward_qty . '","' . $amount . '"');
            //dd(DB::getQueryLog());
        }

        return view('test');
    }


    public function TrimsStockDataTrialCloned1(Request $request)
    {
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if ($currentDate == "") {
            echo "<script>location.href='TrimsStockDataTrialCloned1?currentDate=" . date('Y-m-d') . "';</script>";
        }
        return view('TrimsStockDataTrialCloned1', compact('currentDate'));
    }

    public function LoadTrimsStockDataTrialCloned2(Request $request)
    {
        ini_set('memory_limit', '100024M');

        $currentDate   = $request->currentDate ?? "";
        $job_status_id = (int)($request->job_status_id ?? 0);

        $filter = '';
        if ($job_status_id > 0) {
            if ($job_status_id == 1) {
                $filter .= " AND (job_status_id ='1' OR dump_trim_stock_data.closeDate > '" . $currentDate . "')";
            } else {
                $filter .= " AND job_status_id IN(0,2) AND dump_trim_stock_data.trimDate <= '" . $currentDate . "'";
            }
        }

        $TrimsInwardDetails = DB::select("
            SELECT dump_trim_stock_data.*, 
                   LM1.ac_short_name as suplier_name,
                   ledger_master.ac_short_name as buyer_name, 
                   po_type_master.po_type_name, 
                   COALESCE(
                        (SELECT trade_name 
                         FROM ledger_details 
                         WHERE sr_no = purchase_order.bill_to 
                         LIMIT 1)
                    ) AS trade_name,
            
                    COALESCE(
                        (SELECT site_code 
                         FROM ledger_details 
                         WHERE sr_no = purchase_order.bill_to 
                         LIMIT 1)
                    ) AS site_code

            FROM dump_trim_stock_data 
            INNER JOIN item_master ON item_master.item_code = dump_trim_stock_data.item_code
            LEFT JOIN purchase_order ON purchase_order.pur_code = dump_trim_stock_data.po_no
            LEFT JOIN trimsInwardMaster 
                ON trimsInwardMaster.trimCode = dump_trim_stock_data.trimCode
            LEFT JOIN ledger_master as LM1
                ON LM1.ac_code = trimsInwardMaster.Ac_code
            LEFT JOIN po_type_master ON po_type_master.po_type_id = purchase_order.po_type_id
            LEFT JOIN ledger_master ON ledger_master.ac_code = purchase_order.buyer_id
            WHERE item_master.class_id != 94 
              AND dump_trim_stock_data.trimDate <= '" . $currentDate . "' $filter ");


        try {
            $currentDateObj = $currentDate ? \Carbon\Carbon::parse($currentDate) : \Carbon\Carbon::now();
        } catch (\Exception $e) {
            $currentDateObj = \Carbon\Carbon::now();
        }

        $grouped = [];

        // Grouping and build lots + outwards list (parsed)
        foreach ($TrimsInwardDetails as $row) {
            $key = ($row->po_no ?? '') . '|' . ($row->item_code ?? '');

            if ($row->site_code != '') {
                $bill_to = $row->trade_name . '(' . $row->site_code . ')';
            } else {
                $bill_to = $row->trade_name;
            }

            if ($bill_to == '') {
                // DB::enableQueryLog();
                $tradeData = DB::select("SELECT ledger_details.trade_name FROM ledger_details LEFT JOIN ledger_master ON ledger_master.ac_code = ledger_details.ac_code WHERE ac_short_name LIKE '%" . $row->suplier_name . "%' LIMIT 1");
                //  dd(DB::getQueryLog());
                $tn = isset($tradeData[0]->trade_name) ? $tradeData[0]->trade_name : "";
                $sc = isset($tradeData[0]->site_code) ? $tradeData[0]->site_code : "";

                if ($sc != '') {
                    $bill_to = $tn . '(' . $sc . ')';
                } else {
                    $bill_to = $tn;
                }
            }


            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'suplier_name' => $row->suplier_name ?? '',
                    'bill_to' => $bill_to,
                    'buyer_name'   => $row->buyer_name ?? '',
                    'po_no'        => $row->po_no ?? '',
                    'po_type_name' => $row->po_type_name ?? '',
                    'item_code'    => $row->item_code ?? '',
                    'item_name'    => $row->item_name ?? '',
                    'width'        => $row->width ?? '',
                    'color'        => $row->color ?? '',
                    'item_description' => $row->item_description ?? '',
                    'rate'         => (float)($row->rate ?? 0.0),
                    'closeDate'    => $row->closeDate ?? '',
                    'job_status_id' => (int)($row->job_status_id ?? 0),
                    'amount'       => 0.0,
                    'gq'           => 0.0,
                    'q_qty'        => 0.0,
                    'lots'         => [],
                    'outwards'     => [],
                    'seen_outwards' => [],
                    'outward_0_30' => 0.0,
                    'outward_31_60' => 0.0,
                    'outward_61_90' => 0.0,
                    'outward_91_180' => 0.0,
                    'outward_180_plus' => 0.0,
                    'stock_0_30' => 0.0,
                    'value_0_30' => 0.0,
                    'stock_31_60' => 0.0,
                    'value_31_60' => 0.0,
                    'stock_61_90' => 0.0,
                    'value_61_90' => 0.0,
                    'stock_91_180' => 0.0,
                    'value_91_180' => 0.0,
                    'stock_180_plus' => 0.0,
                    'value_180_plus' => 0.0,
                    'stocks' => 0.0,
                    'value' => 0.0,
                ];
            }

            // Inward (lots)
            $grnQty = (float)($row->grn_qty ?? 0);
            $grouped[$key]['gq'] += $grnQty;
            $grouped[$key]['amount'] += (float)($row->amount ?? 0);
            $grouped[$key]['lots'][] = [
                'grn_qty'   => $grnQty,
                'remaining' => $grnQty,
                'rate'      => (float)($row->rate ?? 0.0),
                'trimCode'  => $row->trimCode ?? null,
                'trimDate'  => $row->trimDate ?? null,
            ];

            // Outward parsing (ind_outward_qty format: "YYYY-MM-DD=>qty,YYYY-MM-DD=>qty,...")
            $indStr = trim($row->ind_outward_qty ?? '');
            if ($indStr !== '') {
                $parts = array_filter(array_map('trim', explode(',', $indStr)), function ($v) {
                    return $v !== '';
                });
                foreach ($parts as $idx => $part) {
                    $pair = explode('=>', $part);
                    if (count($pair) < 2) continue;
                    $outDateStr = trim($pair[0]);
                    $qtyOutRaw  = trim($pair[1]);
                    $qtyOut = (float) str_replace(',', '', $qtyOutRaw);
                    if ($outDateStr === '' || $qtyOut == 0.0) continue;
                    try {
                        $outDateObj = \Carbon\Carbon::parse($outDateStr);
                    } catch (\Exception $e) {
                        continue;
                    }
                    if ($outDateObj->gt($currentDateObj)) continue;

                    // Unique key to avoid duplicates: use outDate, qty, po, item_code and part index
                    $uniqueKey = $outDateObj->format('Y-m-d') . '|'
                        . number_format($qtyOut, 6, '.', '') . '|'
                        . (string)($row->po_no ?? '') . '|'
                        . (string)($row->item_code ?? '') . '|'
                        . $idx;

                    if (isset($grouped[$key]['seen_outwards'][$uniqueKey])) continue;

                    $grouped[$key]['seen_outwards'][$uniqueKey] = true;
                    $grouped[$key]['outwards'][] = ['qty' => $qtyOut, 'outDateObj' => $outDateObj];
                }
            }
        }

        // FIFO allocation (consume outwards from oldest lots first)
        foreach ($grouped as $key => &$g) {
            // sum q_qty (total outward quantity numeric)
            $g['q_qty'] = 0.0;
            foreach ($g['outwards'] as $o) $g['q_qty'] += (float)$o['qty'];

            if (empty($g['outwards']) || empty($g['lots'])) {
                // nothing to allocate — ensure outward bucket sums are zero or derived only from outwards (already zero)
                continue;
            }

            // sort lots ascending by trimDate (oldest first)
            usort($g['lots'], function ($a, $b) {
                $aDate = $a['trimDate'] ?? '1970-01-01';
                $bDate = $b['trimDate'] ?? '1970-01-01';
                return strtotime($aDate) <=> strtotime($bDate);
            });

            // sort outwards ascending by outDate (oldest outward first)
            usort($g['outwards'], function ($a, $b) {
                return $a['outDateObj']->timestamp <=> $b['outDateObj']->timestamp;
            });

            // Perform FIFO allocation
            foreach ($g['outwards'] as $out) {
                $qtyToApply = (float)$out['qty'];
                // try to consume from lots in order
                foreach ($g['lots'] as &$lot) {
                    if ($qtyToApply <= 0) break;
                    // ensure lot remaining is numeric
                    $lotRemaining = (float)$lot['remaining'];
                    if ($lotRemaining <= 0) continue; // nothing to consume from this lot

                    // Only consume what's available in the lot
                    $consume = min($lotRemaining, $qtyToApply);
                    $lot['remaining'] = $lotRemaining - $consume;
                    $qtyToApply -= $consume;

                    // Age for outward must be calculated relative to outward date (how old the stock was when it was taken out)
                    try {
                        $trimDateObj = $lot['trimDate'] ? \Carbon\Carbon::parse($lot['trimDate']) : $currentDateObj;
                    } catch (\Exception $e) {
                        $trimDateObj = $currentDateObj;
                    }

                    $ageInDays = $trimDateObj->diffInDays($out['outDateObj'] ?? $currentDateObj);

                    if ($ageInDays <= 30) {
                        $g['outward_0_30'] += $consume;
                    } elseif ($ageInDays <= 60) {
                        $g['outward_31_60'] += $consume;
                    } elseif ($ageInDays <= 90) {
                        $g['outward_61_90'] += $consume;
                    } elseif ($ageInDays <= 180) {
                        $g['outward_91_180'] += $consume;
                    } else {
                        $g['outward_180_plus'] += $consume;
                    }
                }
                unset($lot);

                // If qtyToApply still > 0 here, it means outwards exceed all lots (overship) — ignore the extra for ageing allocation
            }
        }
        unset($g);

        // Stock buckets (remaining quantities by age relative to currentDateObj)
        foreach ($grouped as $key => &$g) {
            $g['stock_0_30'] = $g['value_0_30'] = $g['stock_31_60'] = $g['value_31_60'] = $g['stock_61_90'] = $g['value_61_90'] = $g['stock_91_180'] = $g['value_91_180'] = $g['stock_180_plus'] = $g['value_180_plus'] = 0.0;
            $totalRemaining = 0.0;
            $totalValue = 0.0;

            foreach ($g['lots'] as $lot) {
                // Ensure remaining cannot be negative
                $remaining = max(0.0, (float)$lot['remaining']);
                $rate = (float)$lot['rate'];

                try {
                    $trimDateObj = $lot['trimDate'] ? \Carbon\Carbon::parse($lot['trimDate']) : $currentDateObj;
                } catch (\Exception $e) {
                    $trimDateObj = $currentDateObj;
                }

                $ageInDays = $trimDateObj->diffInDays($currentDateObj);
                $value = $remaining * $rate; // per-lot value

                if ($ageInDays <= 30) {
                    $g['stock_0_30'] += $remaining;
                    $g['value_0_30'] += $value;
                } elseif ($ageInDays <= 60) {
                    $g['stock_31_60'] += $remaining;
                    $g['value_31_60'] += $value;
                } elseif ($ageInDays <= 90) {
                    $g['stock_61_90'] += $remaining;
                    $g['value_61_90'] += $value;
                } elseif ($ageInDays <= 180) {
                    $g['stock_91_180'] += $remaining;
                    $g['value_91_180'] += $value;
                } else {
                    $g['stock_180_plus'] += $remaining;
                    $g['value_180_plus'] += $value;
                }

                $totalRemaining += $remaining;
                $totalValue += $value;
            }

            // Keep original behavior: stocks = totalRemaining, value = sum(per-lot values) (rounded)
            $g['stocks'] = $totalRemaining;
            $g['value'] = round($totalValue, 2);

            // Original override: if value differs from stocks * grouped rate, replace value with expectedValue
            $expectedValue = round($g['stocks'] * (float)$g['rate'], 4);
            if (abs($g['value'] - $expectedValue) > 0.01) {
                $g['value'] = $expectedValue;
            }

            // --- FIX: recompute bucket value_* fields to match final $g['value'] while preserving stock buckets ---
            // Distribute final total value across buckets proportionally to bucket stocks so they sum to final value.
            if ($g['stocks'] > 0) {
                $s0 = $g['stock_0_30'];
                $s31 = $g['stock_31_60'];
                $s61 = $g['stock_61_90'];
                $s91 = $g['stock_91_180'];
                $s180 = $g['stock_180_plus'];

                // compute proportional values (use unrounded sums, round only final assignments)
                $v0 = ($s0 / $g['stocks']) * $g['value'];
                $v31 = ($s31 / $g['stocks']) * $g['value'];
                $v61 = ($s61 / $g['stocks']) * $g['value'];
                $v91 = ($s91 / $g['stocks']) * $g['value'];
                // last bucket gets the remainder to avoid rounding drift
                $sumFirstFour = $v0 + $v31 + $v61 + $v91;
                $v180 = $g['value'] - $sumFirstFour;

                // guard small negative zeros
                if (abs($v180) < 0.00001) $v180 = 0.0;

                $g['value_0_30'] = round($v0, 2);
                $g['value_31_60'] = round($v31, 2);
                $g['value_61_90'] = round($v61, 2);
                $g['value_91_180'] = round($v91, 2);
                $g['value_180_plus'] = round($v180, 2);
            } else {
                // no stock => zero bucket values
                $g['value_0_30'] = $g['value_31_60'] = $g['value_61_90'] = $g['value_91_180'] = $g['value_180_plus'] = 0.0;
            }

            // Round stock buckets for consistent presentation (kept numeric for totals)
            $g['stock_0_30'] = round($g['stock_0_30'], 2);
            $g['stock_31_60'] = round($g['stock_31_60'], 2);
            $g['stock_61_90'] = round($g['stock_61_90'], 2);
            $g['stock_91_180'] = round($g['stock_91_180'], 2);
            $g['stock_180_plus'] = round($g['stock_180_plus'], 2);
        }
        unset($g);

        // Build response HTML/array and totals (sum numerically before formatting)
        $html = [];

        $total_value = 0.0;
        $total_stock = 0.0;
        $total_amount = 0.0;

        foreach ($grouped as $row) {
            // remove seen_outwards if present
            if (isset($row['seen_outwards'])) unset($row['seen_outwards']);

            $slider = 'Non Moving';
            $chk = '';
            $dis = 'disabled';
            if ($row['job_status_id'] == 1 || ($currentDate !== '' && $currentDate < ($row['closeDate'] ?? ''))) {
                $slider = 'Moving';
                $chk = 'checked';
                if ($currentDate < ($row['closeDate'] ?? '')) $dis = 'disabled';
            }
            $disbaled = (in_array(Session::get('userId'), [1, 2])) ? "" : "disabled";
            $action = ($job_status_id == 1 && (empty($row['closeDate']) || $row['closeDate'] == '0000-00-00'))
                ? '<label class="switch"><input type="checkbox" ' . $chk . ' ' . $disbaled . ' ' . $dis . ' onchange="updateSliderState(this);" po_no=' . $row['po_no'] . ' item_code=' . $row['item_code'] . '><span class="slider round" data-state="' . $slider . '"></span></label>'
                : '-';

            // capture numeric totals BEFORE formatting
            $num_gq = (float)$row['gq'];
            $num_q_qty = (float)$row['q_qty'];
            $num_stocks = (float)$row['stocks'];
            $num_rate = (float)$row['rate'];
            $num_value = (float)$row['value'];
            $num_amount = (float)$row['amount'];

            // Format all numeric fields for display
            // NOTE: money_format can be deprecated on some PHP builds. If it fails, replace with number_format.
            $row['gq'] = money_format("%!.2n", round($num_gq, 2));
            $row['q_qty'] = money_format("%!.2n", round($num_q_qty, 2));
            $row['stocks'] = money_format("%!.2n", round($num_stocks, 2));
            $row['rate'] = money_format("%!.4n", round($num_rate, 4));
            $row['value'] = money_format("%!.2n", round($num_value, 2));
            $row['amount'] = money_format("%!.2n", round($num_amount, 2));

            $row['stock_0_30'] = money_format("%!.2n", round(($row['stock_0_30']), 2));
            $row['stock_31_60'] = money_format("%!.2n", round(($row['stock_31_60']), 2));
            $row['stock_61_90'] = money_format("%!.2n", round(($row['stock_61_90']), 2));
            $row['stock_91_180'] = money_format("%!.2n", round(($row['stock_91_180']), 2));
            $row['stock_180_plus'] = money_format("%!.2n", round(($row['stock_180_plus']), 2));

            $row['value_0_30'] = money_format("%!.2n", round($row['value_0_30'], 2));
            $row['value_31_60'] = money_format("%!.2n", round($row['value_31_60'], 2));
            $row['value_61_90'] = money_format("%!.2n", round($row['value_61_90'], 2));
            $row['value_91_180'] = money_format("%!.2n", round($row['value_91_180'], 2));
            $row['value_180_plus'] = money_format("%!.2n", round($row['value_180_plus'], 2));

            // Also format outward buckets for display (these were numeric)
            $row['outward_0_30'] = money_format("%!.2n", round(($row['outward_0_30']), 2));
            $row['outward_31_60'] = money_format("%!.2n", round(($row['outward_31_60']), 2));
            $row['outward_61_90'] = money_format("%!.2n", round(($row['outward_61_90']), 2));
            $row['outward_91_180'] = money_format("%!.2n", round(($row['outward_91_180']), 2));
            $row['outward_180_plus'] = money_format("%!.2n", round(($row['outward_180_plus']), 2));

            $html[] = array_merge($row, ['Action' => $action, 'po_status' => $slider]);

            // increment totals numerically
            $total_value += $num_value;
            $total_stock += $num_stocks;
            $total_amount += $num_amount;
        }

        return response()->json([
            'html' => json_encode($html),
            // Based on original behavior dividing totals by 100000 before formatting
            'total_stock' => money_format("%!.2n", round(($total_stock / 100000), 2)),
            'currentDate' => $currentDate,
            'total_value' => money_format("%!.2n", round(($total_value / 100000), 2)),
            'total_amount' => money_format("%!.2n", round(($total_amount / 100000), 2))
        ]);
    }
    public function GetItemWorkOrderPucharseOrder(Request $request)
    {
        $vendorWorkOrderList = DB::query()
            ->fromSub(function ($query) use ($request) {
                $query->from('vendor_work_order_sewing_trims_details')
                    ->select('vendor_work_order_sewing_trims_details.item_code')
                    ->where('vendor_work_order_sewing_trims_details.vw_code', $request->vw_code)
                    ->unionAll(
                        DB::table('vendor_purchase_order_packing_trims_details')
                            ->select('vendor_purchase_order_packing_trims_details.item_code')
                            ->where('vpo_code', $request->vw_code)
                    );
            }, 'combined')
            ->join('item_master', 'item_master.item_code', '=', 'combined.item_code') // adjust join column name if needed
            ->select('item_master.item_code', 'item_master.item_name')
            ->get();


        $html = '<option value="">--Select Item--</option>';
        if (count($vendorWorkOrderList) > 0) {
            foreach ($vendorWorkOrderList as $row) {
                $html .= '<option value="' . $row->item_code . '">(' . $row->item_code . ') ' . $row->item_name . '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    public function GetTrimsVendorName(Request $request)
    {
        $code = $request->code;

        $vendorData = DB::select("
            SELECT lm.ac_name 
            FROM vendor_purchase_order_master vpo
            INNER JOIN ledger_master lm ON lm.ac_code = vpo.vendorId
            WHERE vpo.vpo_code = ?
            
            UNION ALL
            
            SELECT lm.ac_name 
            FROM vendor_work_order_master vwo
            INNER JOIN ledger_master lm ON lm.ac_code = vwo.vendorId
            WHERE vwo.vw_code = ?
        ", [$code, $code]);

        return response()->json(['html' => $vendorData[0]->ac_name]);
    }
}
