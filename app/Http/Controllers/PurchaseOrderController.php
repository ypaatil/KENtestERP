<?php
namespace App\Http\Controllers;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\CategoryModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;
use DataTables;
ini_set('memory_limit', '10G');
use App\Services\PurchaseOrderDetailActivityLog;
use App\Services\PurchaseOrderMasterActivityLog;

use Log;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '31')
        ->first();
        
        if(Session::get('user_type') != 14)
        {
            if( $request->page == 1)
            { 
                $InwardFabric = DB::select("SELECT ifnull((select count(sr_no) from purchase_order),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order),0) as poTotal,
                    ifnull((select sum(Net_Amount) from purchase_order where po_status=2),0) as receivedTotal");
             
                // $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
                //     ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
                //     ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
                //     ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
                //     ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
                //     ->where('purchase_order.delflag','=', '0')
                //     ->where('purchase_order.approveFlag','=', '0')
                //     ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name']);
                    
                    $data = PurchaseOrderModel::leftJoin('ledger_master as lm1', 'lm1.ac_code', '=', 'purchase_order.Ac_code')
                        ->leftJoin('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_order.buyer_id')
                        ->leftJoin('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
                        ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
                        ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
                        ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')
                        ->leftJoin('purchaseorder_detail', 'purchaseorder_detail.pur_code', '=', 'purchase_order.pur_code')
                        ->where('purchase_order.delflag', '=', '0') 
                        ->groupBy('purchase_order.pur_code') 
                        ->get([
                            'purchase_order.*',
                            'usermaster.username',
                            'lm1.ac_short_name as ac_name1',
                            'lm2.ac_short_name as buyer',
                            'firm_master.firm_name',
                            'tax_type_master.tax_type_name',
                            'po_type_master.po_type_name',
                            DB::raw("GROUP_CONCAT(DISTINCT purchaseorder_detail.sales_order_no SEPARATOR ',') as sales_order_nos") // Combine sales order numbers
                        ]); 

            }
            else
            {
                $InwardFabric = DB::select("SELECT ifnull((select count(sr_no) from purchase_order WHERE pur_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order WHERE pur_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)),0) as poTotal,
                    ifnull((select sum(Net_Amount) from purchase_order where po_status=2 AND pur_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)),0) as receivedTotal");
             
                $data = PurchaseOrderModel::leftJoin('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
                    ->leftJoin('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_order.buyer_id')
                    ->leftJoin('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
                    ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
                    ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
                    ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
                    ->leftJoin('purchaseorder_detail', 'purchaseorder_detail.pur_code', '=', 'purchase_order.pur_code')
                    ->where('purchase_order.delflag','=', '0') 
                    ->where('purchase_order.pur_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
                    ->groupBy('purchase_order.pur_code') 
                    ->get(['purchase_order.*','lm2.ac_short_name as buyer','usermaster.username','lm1.ac_short_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name',
                            DB::raw("GROUP_CONCAT(DISTINCT purchaseorder_detail.sales_order_no SEPARATOR ',') as sales_order_nos")]);
               
            } 
        }
        else
        {
            
                $InwardFabric = DB::select("SELECT ifnull((select count(sr_no) from purchase_order),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order),0) as poTotal,
                    ifnull((select sum(Net_Amount) from purchase_order where po_status=2),0) as receivedTotal");
             
                $data = PurchaseOrderModel::leftJoin('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
                    ->leftJoin('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_order.buyer_id')
                    ->leftJoin('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
                    ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
                    ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
                    ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
                    ->leftJoin('purchaseorder_detail', 'purchaseorder_detail.pur_code', '=', 'purchase_order.pur_code')
                    ->where('purchase_order.delflag','=', '0') 
                    ->where('purchase_order.userId','=',  Session::get('userId'))
                    ->where('purchase_order.pur_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
                    ->groupBy('purchase_order.pur_code') 
                    ->get(['purchase_order.*','lm2.ac_short_name as buyer','usermaster.username','lm1.ac_short_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name',
                            DB::raw("GROUP_CONCAT(DISTINCT purchaseorder_detail.sales_order_no SEPARATOR ',') as sales_order_nos")]);
        }
        
        if ($request->ajax()) 
        {
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('sono',function ($row) {
                    //  $SalesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->pur_code."'");
                    //  $sono=''; 
                    //  foreach($SalesOrderNo as $rows){ 
                    //      $sono=$sono.$rows->sales_order_no.','; 
                         
                    //  } 
                     $sono = $row->sales_order_nos;
                     return $sono;
                }) 
                ->addColumn('bom_type_wise',function ($row) 
                {
                    if($row->bom_type=='1') 
                    {
                        $bom_type_wise = 'Fabric';
                    }
                    else if($row->bom_type == '2' || $row->bom_type == '3' ||  $row->bom_type == '2,3')
                    {
                        $bom_type_wise = 'Trims';
                    }
                    else if($row->bom_type == '4')
                    {
                        $bom_type_wise = 'Other';
                    }
                    else
                    {
                        $bom_type_wise = '';
                    }
            
                    return $bom_type_wise;
                })   
                ->addColumn('approved_status',function ($row) 
                {
                     $status = 'Pending';
                     return $status;
                })   
                ->addColumn('updated_at',function ($row) 
                {
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
                     return $updated_at;
                })  
                ->addColumn('po_status',function ($row) 
                {
                    if($row->po_status == 1)
                    {
                        $status = 'Moving';
                    }
                    else if($row->po_status == 2)
                    {
                         $status = 'Non Moving';
                    }
                    else
                    {
                        $status = '-';
                    }
                     return $status;
                })  
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="print/'.base64_encode($row->pur_code).'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1)
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('PurchaseOrder.edit', $row->sr_no).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->pur_code.'" data-potype="'.base64_encode($row->bom_type).'"  data-route="'.route('PurchaseOrder.destroy',base64_encode($row->pur_code)).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['po_status','action1','action2','action3','updated_at'])
        
                ->make(true);
        }
        return view('PurchaseOrderList', compact('chekform', 'InwardFabric'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='Other_Purchase' and c_name='C1'")); 
        $firmlist = DB::table('firm_master')->get();
        $ledgerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1])->where('ledger_master.ac_code','>', '39')->get();
        $buyerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id', '=', 2)->get();
        $POTypeList = POTypeModel::join('po_type_auth', "po_type_auth.po_type_id","=","po_type_master.po_type_id")->where('po_type_auth.username','=',Session::get('username'))->where('po_type_master.delflag','=', '0')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        
        
        //$BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
        $BOMLIST= DB::select("select bom_code, sales_order_no from bom_master where sales_order_no in 
            (select sales_order_no from sales_order_costing_master 
            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no 
            where  buyer_purchse_order_master.job_status_id=1 AND sales_order_costing_master.is_approved=2 OR buyer_purchse_order_master.og_id = 4)");
        
        return view('PurchaseOrder',compact('firmlist','ledgerlist','buyerlist','gstlist','itemlist','ClassList','code','unitlist','POTypeList','BOMLIST'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {  
            DB::beginTransaction();
        
            $firm_id = $request->input('firm_id');      

            $codefetch = DB::table('counter_number')
                ->select(DB::raw("tr_no + 1 as tr_no, c_code, code"))
                ->where('c_name', 'C1')
                ->where('type', 'PurchaseOrder')
                ->where('firm_id', $firm_id)
                ->first();

            if ($request->input('bom_codes') != '') {
                $bom_code = implode(",", $request->input('bom_codes'));
            } else {
                $bom_code = '';
            }

            $bom_type = implode(",", (array)$request->input('bom_type', []));
            $class_ids = implode(",", (array)$request->input('class_id', []));

            if ($bom_type == '1') {
                $TrNo = $codefetch->code . '/25-26/' . 'F' . $codefetch->tr_no;
            } else if ($class_ids == '148') {
                $TrNo = $codefetch->code . '/25-26/' . 'S' . $codefetch->tr_no;
            } else {
                $TrNo = $codefetch->code . '/25-26/' . 'T' . $codefetch->tr_no;
            }

            $data = [
                'pur_code' => $TrNo,
                'bom_code' => $bom_code,
                'bom_type' => $bom_type,
                'class_id' => $class_ids,
                'pur_date' => $request->pur_date,
                'Ac_code' => $request->Ac_code,
                'po_type_id' => $request->po_type_id,
                'tax_type_id' => $request->tax_type_id,
                'total_qty' => $request->total_qty,
                'Gross_amount' => $request->Gross_amount,
                'Gst_amount' => $request->Gst_amount,
                'totFreightAmt' => $request->totFreightAmt,
                'Net_amount' => $request->Net_amount,
                'narration' => $request->narration,
                'firm_id' => $firm_id,
                'c_code' => $codefetch->c_code,
                'gstNo' => $request->gstNo,
                'address' => $request->address,
                'deliveryAddress' => $request->deliveryAddress,
                'supplierRef' => $request->supplierRef,
                'terms_and_conditions' => $request->terms_and_conditions,
                'delivery_date' => $request->delivery_date,
                'po_status' => $request->po_status ? $request->po_status : 1,
                'closeDate' => $request->closeDate ?? '',
                'userId' => $request->userId,
                'buyer_id' => $request->buyer_id,
                'bill_to' => $request->bill_to,
                'ship_to' => $request->ship_to,
                'reason_disapproval' => '0',
                'approveFlag' => 0,
                'delflag' => 0
            ];

            $closeDate = date('Y-m-d');

            PurchaseOrderModel::insert($data);

            DB::update("UPDATE counter_number SET tr_no = tr_no + 1  
                        WHERE c_name ='C1' AND type='PurchaseOrder' AND firm_id=?", [$firm_id]);
            
            DB::update("UPDATE dump_fabric_stock_data SET closeDate=? WHERE po_no=?", [$closeDate, $TrNo]);
            DB::update("UPDATE dump_trim_stock_data SET closeDate=? WHERE po_no=?", [$closeDate, $TrNo]);

            $itemcodes = count($request->item_codes);
            $data2 = [];
            $item_code = '';

            if ($itemcodes > 0) {
                for ($x = 0; $x < $itemcodes; $x++) {

                    $data2[] = [
                        'pur_code' => $TrNo,
                        'pur_date' => $request->pur_date,
                        'bom_code' => $bom_code,
                        'bom_type' => $bom_type,
                        'class_id' => $class_ids,
                        'Ac_code' => $request->Ac_code,

                        'sales_order_no' => $request->sales_order_no[$x] ?? null,
                        'item_code' => $request->item_codes[$x] ?? null,
                        'hsn_code' => $request->hsn_code[$x] ?? null,
                        'unit_id' => $request->unit_id[$x] ?? null,
                        'item_qty' => $request->item_qtys[$x] ?? 0,
                        'item_rate' => $request->item_rates[$x] ?? 0,
                        'disc_per' => $request->disc_pers[$x] ?? 0,
                        'disc_amount' => $request->disc_amounts[$x] ?? 0,
                        'pur_cgst' => $request->pur_cgsts[$x] ?? 0,
                        'camt' => $request->camts[$x] ?? 0,
                        'pur_sgst' => $request->pur_sgsts[$x] ?? 0,
                        'samt' => $request->samts[$x] ?? 0,
                        'pur_igst' => $request->pur_igsts[$x] ?? 0,
                        'iamt' => $request->iamts[$x] ?? 0,
                        'amount' => $request->amounts[$x] ?? 0,
                        'freight_hsn' => 0,
                        'freight_amt' => $request->freight_amt[$x] ?? 0,
                        'total_amount' => $request->total_amounts[$x] ?? 0,

                        'conQty' => $request->conQtys[$x] ?? 0,
                        'unitIdM' => $request->unitIdMs[$x] ?? ($request->unit_id[$x] ?? null),
                        'priUnitd' => $request->priUnitds[$x] ?? ($request->unit_id[$x] ?? null),
                        'SecConQty' => $request->SecConQtys[$x] ?? 0,
                        'secUnitId' => $request->secUnitIds[$x] ?? ($request->unit_id[$x] ?? null),
                        'poQty' => $request->poQtys[$x] ?? 0,
                        'poUnitId' => $request->poUnitIds[$x] ?? ($request->unit_id[$x] ?? null),
                        'rateM' => $request->rateMs[$x] ?? 0,
                        'totalQty' => $request->totalQtys[$x] ?? 0,

                        'firm_id' => $firm_id
                    ];

                    if (!empty($request->item_codes[$x])) {
                        $item_code .= $request->item_codes[$x] . ',';
                    }
                }

                PurchaseOrderDetailModel::insert($data2);
            }

            $item_code = rtrim($item_code, ",");

            if (!empty($item_code)) {
                DB::update("UPDATE bom_fabric_details 
                            SET usedFlag = 1 
                            WHERE bom_code=? AND item_code IN ($item_code)", [$bom_code]);
            }

            DB::commit();
            return redirect()->route('PurchaseOrder.index')->with('message', 'Add Record Successfully');
        }

        catch (\Exception $e) {
            DB::rollBack();
            \Log::info("Message: ".$e->getMessage()." Line: ".$e->getLine());
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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
        ->where('form_id', '31')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '1')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);
       
        return view('POApprovalList', compact('data','chekform'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '31')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '2')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $firmlist = DB::table('firm_master')->get(); 
        $ledgerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        $CatList = CategoryModel::where('delflag','=', '0')->get(); 
        $POTypeList = POTypeModel::join('po_type_auth', "po_type_auth.po_type_id","=","po_type_master.po_type_id")->where('po_type_auth.username','=',Session::get('username'))->where('po_type_master.delflag','=', '0')->get();
        $purchasefetch = PurchaseOrderModel::find($id);
        $BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
        $buyerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id', '=', 2)->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', $purchasefetch->class_id)->get();
        // $BOMLIST= DB::select("select bom_code, sales_order_no from bom_master where sales_order_no in 
        //     (select sales_order_no from sales_order_costing_master 
        //     INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no where sales_order_costing_master.is_approved=2 OR buyer_purchse_order_master.og_id = 4)");
    
        // DB::enableQueryLog();
  
     
        $is_approved=$purchasefetch->approveFlag;
    
        $detailpurchase = PurchaseOrderDetailModel::select('purchaseorder_detail.*','item_master.item_name','item_master.cat_id','item_master.item_image_path',
        DB::raw("(select ifnull(sum(inward_details.meter),0) from inward_details where inward_details.item_code= purchaseorder_detail.item_code    and inward_details.po_code='".$purchasefetch->pur_code."') as FabGRNQty"),
        DB::raw("(select ifnull(sum(trimsInwardDetail.item_qty),0) from trimsInwardDetail where trimsInwardDetail.item_code= purchaseorder_detail.item_code    and po_code='".$purchasefetch->pur_code."') as TrimGRNQty")
        ,'item_master.class_id')->join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')->where('pur_code','=', $purchasefetch->pur_code)->get();

        $ship_to_details = DB::select("select * from ledger_details");
        $bill_to_details = DB::select("select * from ledger_details where ac_code = ".$purchasefetch->Ac_code);
        
        return view('PurchaseOrderEdit', compact('purchasefetch','ship_to_details','bill_to_details','CatList','firmlist','ledgerlist','is_approved','gstlist','ClassList','itemlist','detailpurchase','unitlist','POTypeList','BOMLIST', 'buyerlist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code,PurchaseOrderDetailActivityLog $loggerDetail,PurchaseOrderMasterActivityLog $loggerMaster)
    {   
        
       try 
       {  
            DB::beginTransaction();
            
           if($request->input('bom_codes')!=''){$bom_code=implode(",",$request->input('bom_codes'));}else{ $bom_code='';}  
           
            // $bom_type=implode(",",$request->input('bom_type'));
            // $class_id_input = $request->input('class_id');
            // if(is_array($class_id_input) && !empty($class_id_input)) 
            // {
            //     $class_ids = implode(",",$request->input('class_id'));
            // }
            // else
            // {
            //     $class_ids = '';
            // }
            
            $bom_type = implode(",", (array)$request->input('bom_type', []));
            $class_ids = implode(",", (array)$request->input('class_id', []));
    
            $closeDate = isset($request->closeDate) ? $request->closeDate : "";
            
            $data = array('pur_code'=>$request->input('pur_code'),
            "bom_code"=> $bom_code,
            "bom_type"=> $bom_type,
            "class_id"=> $class_ids,
            "pur_date"=> $request->input('pur_date'),
            "Ac_code"=> $request->input('Ac_code'),
            "po_type_id"=> $request->input('po_type_id'),
            "tax_type_id"=> $request->input('tax_type_id'),
            "total_qty"=> $request->input('total_qty'),
            "Gross_amount"=> $request->input('Gross_amount'),
            "Gst_amount"=> $request->input('Gst_amount'),
            "totFreightAmt"=> $request->input('totFreightAmt'),
            "Net_amount"=> $request->input('Net_amount'),
            "narration"=> $request->input('narration'),
            "firm_id"=> $request->input('firm_id'),
            "c_code"=> $request->input('c_code'),
            "gstNo"=> $request->input('gstNo'),
            "address"=> $request->input('address'),
            "deliveryAddress"=> $request->input('deliveryAddress'),
            "supplierRef"=> $request->input('supplierRef'),
            "userId"=> $request->input('userId'),
            "approveFlag"=> $request->approveFlag,
            "terms_and_conditions"=> $request->input('terms_and_conditions'),
            "delivery_date"=>$request->input('delivery_date'),
            "buyer_id"=> $request->buyer_id,
            "bill_to"=> $request->bill_to,
            "ship_to"=> $request->ship_to,
            "po_status"=>$request->input('po_status') ? $request->input('po_status') : 1,
            "closeDate"=>$closeDate,
            "delflag"=>0,
            "reason_disapproval"=> $request->input('reason_disapproval'),
            );
            
            
            
            
            
             $MasterOldFetch = DB::table('purchase_order')
             ->select('pur_date','total_qty','Gross_amount', 'Gst_amount', 'totFreightAmt', 'Net_amount', 'narration', 'address', 'deliveryAddress', 'terms_and_conditions',
             'delivery_date', 'closeDate','po_status','reason_disapproval','approveFlag')  
             ->where('pur_code',$request->pur_code)
             ->first();
            
            
                 $MasterOld = (array) $MasterOldFetch;
            
             $MasterNew=["pur_date"=> $request->input('pur_date'),
            "total_qty"=> $request->input('total_qty'),
            "Gross_amount"=> $request->input('Gross_amount'),
            "Gst_amount"=> $request->input('Gst_amount'),
            "totFreightAmt"=> $request->input('totFreightAmt'),
            "Net_amount"=> $request->input('Net_amount'),
            "narration"=> $request->input('narration'),
            "address"=> $request->input('address'),
            "deliveryAddress"=> $request->input('deliveryAddress'),
            "terms_and_conditions"=> $request->input('terms_and_conditions'),
            "delivery_date"=>$request->input('delivery_date'),
            "closeDate"=>$closeDate, 
            "po_status"=>$request->input('po_status') ? $request->input('po_status') : 1,
            "reason_disapproval"=> $request->input('reason_disapproval'), 
            "approveFlag"=> $request->approveFlag];
            
            
    
            
                   try {
                $loggerMaster->logIfChangedPurchaseOrderMaster(
                'purchase_order',
                $request->pur_code,
                $MasterOld,
                $MasterNew,
                'UPDATE',
                $request->pur_date,
                'Master'
                );
                // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
                //   $newDataDetail
                // ]);
                } catch (\Exception $e) {
                Log::error('Logger failed for purchase_order.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pur_code' => $request->pur_code,
                'data' => $MasterNew
                ]);
                }  
              
            
            
            // Insert
            $purchase = PurchaseOrderModel::findOrFail($pur_code);  
            
            $purchase->fill($data)->save();
           if($closeDate != '')
           {
                DB::SELECT("UPDATE dump_fabric_stock_data SET closeDate = '".$closeDate."', po_status='Non Moving', job_status_id=2 WHERE po_no='".$request->pur_code."'"); 
                DB::SELECT("UPDATE dump_trim_stock_data SET closeDate = '".$closeDate."', po_status='Non Moving', job_status_id=2 WHERE po_no='".$request->pur_code."'");
          
           }
           
           
           
           
               $olddata = DB::table('purchaseorder_detail')
                ->select('sales_order_no','item_code','item_qty','item_rate','disc_per','disc_amount','pur_cgst','camt',
                'pur_sgst','samt','pur_igst','iamt','amount','freight_amt','total_amount')  
                ->where('pur_code',$request->input('pur_code'))
                ->get()
                ->map(function ($item) {
                return (array) $item;
                })
                ->toArray();
                
                $combinedOldData = $olddata;
           
           
    
           DB::table('purchaseorder_detail')->where('pur_code', $request->input('pur_code'))->delete();
           DB::table('ledgerentry_details')->where('tr_no', $request->input('pur_code'))->delete();
           DB::table('ledgerentry')->where('TrNo', $request->input('pur_code'))->delete();
           DB::table('transactions')->where('TrNo', $request->input('pur_code'))->delete();
        
        
            $cnt = $request->input('cnt');
            
            $itemcodes=count($request->item_codes);
            
            if($itemcodes>0)
            {
            $item_code='';
            
            $newDataDetail=[];
            
            for($x=0;$x<$itemcodes;$x++) {
            # code...
             
            // echo $request;
            $data2=array(
            
            'pur_code' =>$request->input('pur_code'),
            'pur_date' => $request->input('pur_date'),
            "bom_code"=>$bom_code,
            "bom_type"=> $bom_type,
            "class_id"=> $class_ids,
            'Ac_code' => $request->input('Ac_code'),
            'sales_order_no' => $request->sales_order_no[$x],
            'item_code' => $request->item_codes[$x],
            'hsn_code' => $request->hsn_code[$x],
            'unit_id' => $request->unit_id[$x],
            'item_qty' => $request->item_qtys[$x],
            'item_rate' => $request->item_rates[$x],
            'disc_per' => $request->disc_pers[$x],
            'disc_amount' => $request->disc_amounts[$x],
            'pur_cgst' => $request->pur_cgsts[$x],
            'camt' => $request->camts[$x],
            'pur_sgst' => $request->pur_sgsts[$x],
            'samt' => $request->samts[$x],
            'pur_igst' => $request->pur_igsts[$x],
            'iamt' => $request->iamts[$x],
            'amount' => $request->amounts[$x],
            'freight_hsn' => 0,
            'freight_amt' => $request->freight_amt[$x],
            'total_amount' => $request->total_amounts[$x],
            'conQty'=> isset($request->conQtys[$x])?$request->conQtys[$x]:0, 
            'unitIdM'=> isset($request->unitIdMs[$x]) ? $request->unitIdMs[$x] : $request->unit_id[$x],
            'priUnitd'=> isset($request->priUnitds[$x])?$request->priUnitds[$x]: $request->unit_id[$x],
            'SecConQty'=> isset($request->SecConQtys[$x])?$request->SecConQtys[$x]:0,
            'secUnitId'=>isset($request->secUnitIds[$x])?$request->secUnitIds[$x]: $request->unit_id[$x],
            'poQty'=> isset($request->poQtys[$x])? $request->poQtys[$x]:0,
            'poUnitId'=>isset($request->poUnitIds[$x]) ? $request->poUnitIds[$x] : $request->unit_id[$x],
            'rateM'=> isset($request->rateMs[$x])?$request->rateMs[$x]:0,
            'totalQty'=> isset($request->totalQtys[$x])? $request->totalQtys[$x] :0,
            
             
            'firm_id' => $request->firm_id);
            
             
            PurchaseOrderDetailModel::insert($data2);
             
            $item_code=$item_code.$request->item_codes[$x].',';
            
                   $newDataDetail[]=[
                    'sales_order_no' => $request->sales_order_no[$x],
                    'item_code' => $request->item_codes[$x],
                    'item_qty' => $request->item_qtys[$x],
                    'item_rate' => $request->item_rates[$x],
                    'disc_per' => $request->disc_pers[$x],
                    'disc_amount' => $request->disc_amounts[$x],
                    'pur_cgst' => $request->pur_cgsts[$x],
                    'camt' => $request->camts[$x],
                    'pur_sgst' => $request->pur_sgsts[$x],
                    'samt' => $request->samts[$x],
                    'pur_igst' => $request->pur_igsts[$x],
                    'iamt' => $request->iamts[$x],
                    'amount' => $request->amounts[$x],
                    'freight_amt' => $request->freight_amt[$x],
                    'total_amount' => $request->total_amounts[$x]
                            ]; 
            
            }
            
                 $combinedNewData = $newDataDetail;       
               
                try {
                $loggerDetail->logIfChangedPurchaseOrderDetail(
                'purchaseorder_detail',
                $request->pur_code,
                $combinedOldData,
                $combinedNewData,
                'UPDATE',
                 $request->input('pur_date'),
                'purchaseorder_detail'
                );
                // Log::info('Logger called successfully for purchaseorder_detail.', [
                //   $newDataDetail
                // ]);
                } catch (\Exception $e) {
                Log::error('Logger failed for purchaseorder_detail.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sales_order_no' => $request->sales_order_no,
                'data' => $combinedNewData
                ]);
                }  
            //  DB::enableQueryLog();
                
                
            //   $query = DB::getQueryLog();
            //         $query = end($query);
            //         dd($query);
            $item_code=rtrim($item_code,","); 
            
            if (!empty($item_code)) 
            {
                // If item_code is an array, convert it to a quoted string
                if (is_array($item_code)) {
                    $item_code = implode(",", array_map(function($item) {
                        return "'".addslashes($item)."'";
                    }, $item_code));
                }
            
                $updateBOMItem = DB::update("UPDATE bom_fabric_details SET usedFlag = 1 WHERE bom_code = ? AND item_code IN ($item_code)", [$bom_code]);
            }
    
            }
        
           DB::commit();
           return redirect()->route('PurchaseOrder.index')->with('message', 'Update Record Succesfully'); 
         }
         catch (\Exception $e) 
         {
            // If an exception occurs, rollback the transaction and handle the exception
             \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
          
              DB::rollBack();
      
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
//     public function destroy($pur_code, Request $request)
//     {
//         $potype=$request->potype;
//         $pur_code=base64_decode($pur_code);
//         $item_code='';
//         $bom_code='';  
//           $master1 =PurchaseOrderDetailModel::select('bom_code','item_code')->where('pur_code',$pur_code)->get();
//           foreach($master1 as $row)
//           {
//               $item_code=$item_code.$row->item_code.',';
               
//           }
//           $item_code=rtrim($item_code,",");
             
         
//         $bom_codeids=explode(",",$master1[0]->bom_code);
//         if(count($bom_codeids)>1)
//         {
//         foreach($bom_codeids as $bom)
//         {
//             $bom_code=$bom_code."'".$bom."',";
            
//         }
//         $bom_code=rtrim($bom_code,",");
//         }
//         else
//         {
//           $bom_code =$master1[0]->bom_code;
//         }
//         //echo $bom_code;
             
             
//             $Records=DB::select("select  
//              (select count(po_code) from inward_master where po_code='".$pur_code."') po_codes1,
//              (select count(po_code) from trimsInwardMaster where po_code='".$pur_code."') as po_codes2
//              ");
           
//     if($potype==1)
//     {  
//           echo 'Fabric:'.$Records[0]->po_codes1;
           
//           if($Records[0]->po_codes1==0) 
//         {   
           
//             $master =PurchaseOrderModel::where('pur_code',$pur_code)->delete();      
//             $detail =PurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();
//             $detail =FabricTransactionModel::where('tr_code',$pur_code)->delete();
            
            
//             //  if(count($bom_codeids)>1)
//             // {  
            
//                 // DB::enableQueryLog();
//               // $updateBOMItem = DB::select("update bom_fabric_details set usedFlag=0 where  bom_code =$bom_code and item_code in (".$item_code.")");  
            
// //   $query1 = DB::getQueryLog();
// //         $query1 = end($query1);
// //         dd($query1);
//             // }
//             // else
//             // {
//               $updateBOMItem = DB::select("update bom_fabric_details set usedFlag= 0   where   bom_code='".$bom_code."' and item_code in (".$item_code.")");  
//             // }
            
//             Session::flash('error', 'Deleted record successfully'); 
//         }
//         else
//         {
//             Session::flash('error', "Purchase Order Can Not be deleted as Fabric GRN Done against PO No.:'".$pur_code."'");
            
//         }
//     }
//     else
//     {
           
//           //echo 'Trims:'.$Records[0]->po_codes2;
//         if($Records[0]->po_codes2==0) 
//         {   
//             $master =PurchaseOrderModel::where('pur_code',$pur_code)->delete();      
//             $detail =PurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();
//             $detail =FabricTransactionModel::where('tr_code',$pur_code)->delete();
//             Session::flash('error', 'Deleted record successfully'); 
//         }
//         else
//         {
//             Session::flash('error', "Purchase Order Can Not be deleted as Trims GRN Done against PO No.:'".$pur_code."'");
            
//         }
//     }
           
    
        
//     }


    public function destroy($pur_code, Request $request)
    {
        $potype=$request->potype;
        $pur_code=base64_decode($pur_code);
        $item_code='';
        $bom_code='';  
         
        // DB::enableQueryLog();
          $master1 =PurchaseOrderDetailModel::select('bom_code','item_code')->where('pur_code',$pur_code)->get();
    //   $query1 = DB::getQueryLog();
    //     $query1 = end($query1);
    //     dd($query1);
        
       // exit;
       
        if(count($master1)>0)
       {   foreach($master1 as $row)
          {
              $item_code=$item_code.$row->item_code.',';
               
          }
        $item_code=rtrim($item_code,",");
        $bom_codeids=explode(",",$master1[0]->bom_code);
        if(count($bom_codeids)>1)
        {
        foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        $bom_code=rtrim($bom_code,",");
        }
        else
        {
           $bom_code =$master1[0]->bom_code;
        }
        //echo $bom_code;
       }     
             
        $Records=DB::select("select  
        (select count(in_code)  from inward_master where po_code='".$pur_code."') as finwards, 
        (select count(trimCode)   from trimsInwardMaster where po_code='".$pur_code."') as tinwards");
        
          $counts=($Records[0]->finwards + $Records[0]->tinwards);
       // echo $counts;   
          
  
       $RecordList=DB::select("select  
       (select GROUP_CONCAT(in_code)  from inward_master where po_code='".$pur_code."') as FabInwardList, 
       (select GROUP_CONCAT(trimCode)   from trimsInwardMaster where po_code='".$pur_code."') as TrimInwardList 
        
       ");
    
       if($RecordList[0]->FabInwardList!='')
        {
            $Message="Fabric Inward :".$RecordList[0]->FabInwardList;
        }
        elseif($RecordList[0]->TrimInwardList!='')
        {
            $Message="Trims Inward : ".$RecordList[0]->TrimInwardList;
        }
 // echo $Message;
           
    if($potype==1)
    {  
         //  echo 'Fabric:'.$Records[0]->po_codes1;
           
          if($counts==0) 
        {   
           
            $master =PurchaseOrderModel::where('pur_code',$pur_code)->delete();      
            $detail =PurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();
            $detail =FabricTransactionModel::where('tr_code',$pur_code)->delete();
            $updateBOMItem = DB::select("update bom_fabric_details set usedFlag= 0   where   bom_code='".$bom_code."' and item_code in (".$item_code.")");  
            Session::flash('delete', 'Deleted record successfully'); 
        }
        else
        {
            Session::flash('delete', "Fabric PO Can't be Deleted, Remove References:".$Message);
            
        }
    }
    else
    {
           
           //echo 'Trims:'.$Records[0]->po_codes2;
        if($counts==0) 
        {   
            $master =PurchaseOrderModel::where('pur_code',$pur_code)->delete();      
            $detail =PurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();
            $detail =FabricTransactionModel::where('tr_code',$pur_code)->delete();
            Session::flash('delete', 'Deleted record successfully'); 
        }
        else
        {
            Session::flash('delete', "Trims PO Can't be Deleted, Remove References:".$Message);
            
        }
    }
           
    
        
    }





 public function getItemListFromPO(Request $request)
{
    $po_code= base64_decode($request->input('po_code'));
    
     //echo $po_code;
     $ItemList = DB::table('purchaseorder_detail')->select('purchaseorder_detail.item_code', 'item_master.item_name')
    ->join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')
    ->whereIN('item_master.cat_id',[2,3])->where('purchaseorder_detail.pur_code',$po_code)->DISTINCT('purchaseorder_detail.item_code')->get();
    
    if (!$po_code)
    {
        $html = '<option value="">--Item List--</option>';
        
    } 
    elseif(count($ItemList)>0)
    {
       
        
        
        $html = '';
        $html = '<option value="">--Item List--</option>';
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    }
    else
    {
        
         $ItemList = DB::table('trimsInwardDetail')->select('trimsInwardDetail.item_code', 'item_master.item_name')
        ->join('item_master','item_master.item_code', '=', 'trimsInwardDetail.item_code')
        ->whereIN('item_master.cat_id',[2,3])->where('trimsInwardDetail.po_code',$po_code)->DISTINCT('trimsInwardDetail.item_code')->get();
    
        $html = '';
        $html = '<option value="">--Item List--</option>';
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    
    }
    
    
    
    
    
    
    
    
      return response()->json(['html' => $html]);
}
    








    
     public function closestatus($id)
    {
              
          
        
    }
    
    public function GetPartyDetails(Request $request)
    {
        $sr_no = $request->bill_to;
        $PartyRecords = DB::select("SELECT state_id, gst_no FROM ledger_details WHERE sr_no = ?", [$sr_no]);
        return response()->json($PartyRecords);
    }
        
    public function GetClassLists(Request $request)
    {
        $bom_types = array_map('trim', explode(',', $request->cat_id)); 
        $BOMCodes = array_map('trim', explode(',', $request->sales_order_nos));
        $po_type_id = $request->po_type_id;
        $BOMLIST = DB::table('bom_master')
            ->whereIn('bom_code', $BOMCodes)
            ->pluck('sales_order_no') 
            ->toArray();
    
        $sales_order_nos = $BOMLIST; 
        $queries = [];
    
        foreach ($bom_types as $cat_id) 
        {
            $cat_id = (int) $cat_id;
  
            if($po_type_id != 2)
            {
                    if ($cat_id == 2) {
                        $q = DB::table('bom_sewing_trims_details as t')
                            ->join('classification_master as c', function ($join) use ($cat_id) {
                                $join->on('c.class_id', '=', 't.class_id')
                                     ->where('c.cat_id', '=', $cat_id);
                            })
                            ->whereIn('t.sales_order_no', $sales_order_nos)
                            ->select('t.class_id', 'c.class_name', DB::raw("$cat_id as cat_id"), DB::raw(value: "'Sewing Trims' as cat_name"))
                            ->groupBy('t.class_id', 'c.class_name');
                        $queries[] = $q;
            
                    } elseif ($cat_id == 3) {
                        $q = DB::table('bom_packing_trims_details as t')
                            ->join('classification_master as c', function ($join) use ($cat_id) {
                                $join->on('c.class_id', '=', 't.class_id')
                                     ->where('c.cat_id', '=', $cat_id);
                            })
                            ->whereIn('t.sales_order_no', $sales_order_nos)
                            ->select('t.class_id', 'c.class_name', DB::raw("$cat_id as cat_id"), DB::raw(value: "'Packing Trims' as cat_name"))
                            ->groupBy('t.class_id', 'c.class_name');
                        $queries[] = $q;
                    }
                    elseif ($cat_id == 1) {
                                                 
                        $q = DB::table('classification_master as t')
                        ->select('t.class_id', 't.class_name', DB::raw("$cat_id as cat_id"), DB::raw(value: "'Fabric' as cat_name"))
                        ->where('t.cat_id', '=', $cat_id)
                        ->groupBy('t.class_id', 't.class_name');

                        $queries[] = $q;
                         
                    }
            }
            else
            {
                if ($cat_id == 2) 
                {
                    $q = DB::table('bom_sewing_trims_details as t')
                        ->join('classification_master as c', function ($join) use ($cat_id) {
                            $join->on('c.class_id', '=', 't.class_id')
                                 ->where('c.cat_id', '=', $cat_id);
                        })
                        ->select('t.class_id', 'c.class_name', DB::raw("$cat_id as cat_id"), DB::raw(value: "'Sewing Trims' as cat_name"))
                        ->groupBy('t.class_id', 'c.class_name');
                    $queries[] = $q;
        
                } elseif ($cat_id == 3) {
                    $q = DB::table('bom_packing_trims_details as t')
                        ->join('classification_master as c', function ($join) use ($cat_id) {
                            $join->on('c.class_id', '=', 't.class_id')
                                 ->where('c.cat_id', '=', $cat_id);
                        })
                        ->select('t.class_id', 'c.class_name', DB::raw("$cat_id as cat_id"), DB::raw("'Packing Trims' as cat_name"))
                        ->groupBy('t.class_id', 'c.class_name');
                    $queries[] = $q;
                }elseif ($cat_id == 1) {
                    $q = DB::table('bom_fabric_details as t')
                        ->join('classification_master as c', function ($join) use ($cat_id) {
                            $join->on('c.class_id', '=', 't.class_id')
                                 ->where('c.cat_id', '=', $cat_id);
                        })
                        ->select('t.class_id', 'c.class_name', DB::raw("$cat_id as cat_id"), DB::raw("'Fabric' as cat_name"))
                        ->groupBy('t.class_id', 'c.class_name');
                    $queries[] = $q;
                }elseif ($cat_id == 4) {
                    $q = DB::table('classification_master as t')
                        ->select('t.class_id', 't.class_name', DB::raw("$cat_id as cat_id"), DB::raw("'Other' as cat_name"))
                        ->where('t.cat_id', '=', $cat_id)
                        ->groupBy('t.class_id', 't.class_name');
                    $queries[] = $q;
                }
            }
        }
    
        if (count($queries) > 0) {
            $query = array_shift($queries);
            foreach ($queries as $q) {
                $query->unionAll($q);
            }
            $ClassList = $query->orderBy('class_id', 'asc')->get();
        } else {
            $ClassList = collect();
        }
        
        $html = '';
        foreach ($ClassList as $row) {
            $html .= '<option value="'.$row->class_id.'" data-bomtype="'.$row->cat_name.'" >'.$row->class_name.'</option>';
        }
    
        return response()->json(['html' => $html]);
    }

    public function GetPOList(Request $request)
    {
        
         
        $POList = DB::table('purchase_order')->select('purchase_order.pur_code')
        ->where('Ac_code',$request->Ac_code)->get();
        
        if(!$request->Ac_code)
        {
            $html = '<option value="">--PO No--</option>';
            } else {
            $html = '';
            $html = '<option value="">--PO No--</option>';
            
            foreach ($POList as $row) 
            {$html .= '<option value="'.$row->pur_code.'">'.$row->pur_code.'</option>';}
        }
          return response()->json(['html' => $html]);
    }
    

    public function getBoMDetailDemoCreate(Request $request)
    {
         $html=''; $no=1;
        $bom_code=''; 
        // echo $request->bom_code;
        $bom_codeids=explode(",",$request->bom_code);
        $class_ids=$request->class_ids;
        //  echo $class_ids;
         
         $class_id_each=explode(",",$class_ids);
         
        foreach($class_id_each as $rowClass)
        {
            //echo $rowClass;
            // DB::enableQueryLog();
            $cat_id=DB::table('classification_master')->select('cat_id')->where('class_id','=',$rowClass)->get();
            //  $query = DB::getQueryLog();
            //     $query = end($query);
            //     dd($query);
           //echo $cat_id[0]->cat_id;  
        }   
   
        if($cat_id[0]->cat_id=='1')
        {
         
            $table="bom_fabric_details"; 
           // $bom_codeids=explode(",",$request->bom_code);
            foreach($bom_codeids as $bom)
            {
                $bom_code=$bom_code."'".$bom."',";
                
            }
            $bom_code=rtrim($bom_code,",");
            // echo  $bom_code; exit;
            //  DB::enableQueryLog();
     
            // $data=DB::table($table)
            // ->leftJoin('item_master', 'item_master.item_code', '=', 'purchase_order.item_code')
            // ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
            // ->whereIn('item_master.class_id', $class_ids)->whereIn('bom_code',$bom_codeids)->where('usedFlag',0)->get();
            
            // DB::enableQueryLog();
            $data= DB::select('select item_master.*,bom_fabric_details.*, sum(bom_fabric_details.bom_qty) as bom_qty, (sum(bom_fabric_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_fabric_details.item_code = purchaseorder_detail.item_code AND bom_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no 
                AND bom_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty from bom_fabric_details
              inner join item_master on item_master.item_code=bom_fabric_details.item_code
              inner join classification_master on classification_master.class_id=item_master.class_id
              where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY bom_fabric_details.item_code'); 
               
            // dd(DB::getQueryLog());
        
        foreach ($data as $value) 
        {
           if($cat_id[0]->cat_id=='1')
           {
                $stock = DB::select(DB::raw("SELECT 
                                CASE 
                                    WHEN ROUND(
                                        (
                                            (SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                        ), 2
                                    ) = 0 THEN 0
                                    ELSE ROUND(
                                        (
                                            (SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                        ), 2
                                    )
                                END AS Stock"));

           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
        
                        $html .='<tr class="cls_'.$value->class_id.'">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > 
                        <button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                    
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        $html.='
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="'.round($value->item_qty).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any" name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any" name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any" name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" /><input type="hidden" step="any" name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any" name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" name="freight_amt[]" onkeyup="calFreightAmt(this);"  class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any" name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any" name="conQtys[]" readOnly value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="unitIdMs[]" readOnly value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="priUnitds[]" readOnly value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="SecConQtys[]" readOnly value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="secUnitIds[]" readOnly value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="poQtys[]" readOnly value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="poUnitIds[]" readOnly value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="rateMs[]" readOnly value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="totalQtys[]" readOnly value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
        }
    
        
        //**************** For Trim Fabric Data From Table *************************
        
        
          $data= DB::select('select  bom_trim_fabric_details.*,sum(bom_trim_fabric_details.bom_qty) as bom_qty, (sum(bom_trim_fabric_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_trim_fabric_details.item_code = purchaseorder_detail.item_code AND bom_trim_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty  from bom_trim_fabric_details
          inner join item_master on item_master.item_code=bom_trim_fabric_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where usedFlag=0 and item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY bom_trim_fabric_details.item_code'); 
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
         
        foreach ($data as $value) 
        {
           if($cat_id[0]->cat_id=='1')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
                // (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("
                                SELECT 
                                    CASE 
                                        WHEN ROUND(
                                            ((SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        ) = 0 THEN 0
                                        ELSE ROUND(
                                            ((SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        )
                                    END AS Stock"));
                            

           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > 
                        <button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button><button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0; 
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty,2).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max,2).'"  value="'.round($value->item_qty,2).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
    }
        
        
        //*********************** End of Trim Fabric Data  ************************
         
        
     } 
     else if($cat_id[0]->cat_id=='2')
     { 
        $table="bom_sewing_trims_details"; 
         foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        $bom_code=rtrim($bom_code,",");
       // echo $bom_code;
        //   DB::enableQueryLog();
          $data= DB::select('select bom_sewing_trims_details.*,sum(bom_sewing_trims_details.bom_qty) as bom_qty, (sum(bom_sewing_trims_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_sewing_trims_details.item_code = purchaseorder_detail.item_code AND bom_sewing_trims_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty from bom_sewing_trims_details
          inner join item_master on item_master.item_code=bom_sewing_trims_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY bom_sewing_trims_details.item_code'); 
        
        
        //On Request from Ken Team(Nikhil Bhosale), this condition removed to raise multiple po for the same item for the same sales order
        //  and bom_sewing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))
        
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);   
         
          foreach ($data as $value) 
     {
        //   if($cat_id[0]->cat_id=='1')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
        //         (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
        //   else
        
           if($cat_id[0]->cat_id=='2')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                // (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("SELECT 
                                    CASE 
                                        WHEN ROUND(
                                            (
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                -
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        ) = 0 THEN 0
                                        ELSE ROUND(
                                            (
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                -
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        )
                                    END AS Stock"));

           }
          
              
                        // if($request->tax_type_id==1)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                        //     from item_master where item_code='".$value->item_code."'"));
                        //     $Camt=($value->total_amount * ($value->cgst_per/100));
                        //     $Samt=($value->total_amount * ($value->sgst_per/100));
                        //     $Iamt=0;                 
                        //     $TAmount=$value->total_amount + $Camt+ $Samt;
                        //     $igst_per=0;
                        //     // print_r($value->item_code);
                        // } 
                        // elseif($request->tax_type_id==2)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                        //     from item_master where item_code='$value->item_code'"));
                        //     $Iamt=($value->total_amount * ($value->igst_per/100));
                        //     $Camt=0;
                        //     $Samt=0;
                        //     $TAmount=$value->total_amount + $Iamt ;
                        // } 
                      
                      
                      if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > 
                        <button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button><button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required  >
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        
                        if($value->class_id == 12)
                        {
                            $button_qty = $value->item_qty/144;
                            $button_rate = $value->rate_per_unit * 144;
                        }
                        else
                        {
                             $button_qty = $value->item_qty;
                             $button_rate = $value->rate_per_unit;
                        }
                        
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');"  readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($button_qty,2).'"  value="'.round($button_qty,2).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$button_rate.'" value="'.$button_rate.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" /><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        ';
                        if($value->class_id==4) {
                            $html .='<input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poQtys[]" readOnly     value="'.ceil($max/10000).'" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="rateMs[]" readOnly     value="'.round(($value->rate_per_unit *  10000),2).'" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="'.ceil($max).'" style="width:80px; height:30px;">
                            
                            </td>
                            ';
                         }
                         else
                         {
                              $html .='<input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                            
                            </td>
                            ';
                         }
                        $html .='</tr>';
                        $no=$no+1;
    }
             
     } 
     else if($cat_id[0]->cat_id=='3')
     { 
        $table="bom_packing_trims_details"; 
       // $data=DB::table($table)->whereIn('bom_code',$bom_codeids)->get();
        
        foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        
        $bom_code=rtrim($bom_code,",");
        $data= DB::select('select bom_packing_trims_details.*,sum(bom_packing_trims_details.bom_qty) as bom_qty, (sum(bom_packing_trims_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_packing_trims_details.item_code = purchaseorder_detail.item_code AND bom_packing_trims_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty 
        from bom_packing_trims_details
        inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        inner join classification_master on classification_master.class_id=item_master.class_id
        where item_master.class_id in('.$class_ids.') and
        bom_code in ('.$bom_code.') GROUP BY bom_packing_trims_details.item_code'); 
        
        
        //On Request from Ken Team(Nikhil Bhosale), this condition removed to raise multiple po for the same item for the same sales order
        //   $data= DB::select('select * from bom_packing_trims_details
        //   inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        //   inner join classification_master on classification_master.class_id=item_master.class_id
        //   where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.')
        //   and bom_packing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))'); 
        
        
        // $bom_code=rtrim($bom_code,",");
        // $data= DB::select('select *,  sum(bom_packing_trims_details.bom_qty) as total_bom_qty,
        // (select ifnull(sum(item_qty),0)  from purchaseorder_detail 
        // where  purchaseorder_detail.bom_code in ('.$bom_code.') and purchaseorder_detail.item_code =bom_packing_trims_details.item_code )as po_qty
        // from bom_packing_trims_details
        // inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        // inner join classification_master on classification_master.class_id=item_master.class_id
        // where item_master.class_id in('.$class_ids.') and
        // bom_code in ('.$bom_code.')'); 
        
         
    foreach ($data as $value) 
    {
        
        
    //     if($value->po_qty < $value->total_bom_qty)
    //   { 
        
      
           if( $cat_id[0]->cat_id=='3')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                // (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("SELECT 
                                            CASE 
                                                WHEN ROUND(
                                                    ((SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                    -
                                                    (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                                    ), 2
                                                ) = 0 THEN 0
                                                ELSE ROUND(
                                                    ((SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                    -
                                                    (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                                    ), 2
                                                )
                                            END AS Stock"));

           }
          
              
                         if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> 
                        <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);"> X</button> 
                        <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        
                        
                        if($value->class_id == 12)
                        {
                            $button_qty = $value->item_qty/144;
                            $button_rate = $value->rate_per_unit * 144;
                        }
                        else
                        {
                             $button_qty = $value->item_qty;
                             $button_rate = $value->rate_per_unit;
                        }
                        
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($button_qty,2).'"  value="'.round($button_qty,2).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"  name="item_rates[]" min="0" max="'.$button_rate.'" value="'.$button_rate.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"  name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"  name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                         <input type="hidden" step="any"  name="conQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                        ';
                        $html .='</tr>';
                        $no=$no+1;
    //  }
   
    }
     }
    
    return response()->json(['html' => $html]);
    }
    
    public function getBoMDetail(Request $request)
    {
        $html=''; $no=1;
        $bom_code=''; 
        // echo $request->bom_code;
        $bom_codeids=explode(",",$request->bom_code);
        $class_ids=$request->class_ids;
        //  echo $class_ids;
         
         $class_id_each=explode(",",$class_ids);
         
        foreach($class_id_each as $rowClass)
        {
            //echo $rowClass;
            // DB::enableQueryLog();
            $cat_id=DB::table('classification_master')->select('cat_id')->where('class_id','=',$rowClass)->get();
            //  $query = DB::getQueryLog(); 
            //     $query = end($query);
            //     dd($query);
           //echo $cat_id[0]->cat_id;  
        }   
        if($cat_id[0]->cat_id=='1')
        {
         
            $table="bom_fabric_details"; 
           // $bom_codeids=explode(",",$request->bom_code);
            foreach($bom_codeids as $bom)
            {
                $bom_code=$bom_code."'".$bom."',";
                
            }
            $bom_code=rtrim($bom_code,",");
            // echo  $bom_code; exit;
            //  DB::enableQueryLog();
     
            // $data=DB::table($table)
            // ->leftJoin('item_master', 'item_master.item_code', '=', 'purchase_order.item_code')
            // ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
            // ->whereIn('item_master.class_id', $class_ids)->whereIn('bom_code',$bom_codeids)->where('usedFlag',0)->get();
            
            // DB::enableQueryLog();
            $data= DB::select('select item_master.*,bom_fabric_details.*, sum(bom_fabric_details.bom_qty) as bom_qty, (sum(bom_fabric_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_fabric_details.item_code = purchaseorder_detail.item_code AND bom_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no 
                AND bom_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty,classification_master.class_name from bom_fabric_details
              inner join item_master on item_master.item_code=bom_fabric_details.item_code
              inner join classification_master on classification_master.class_id=item_master.class_id
              where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY  bom_fabric_details.sales_order_no,bom_fabric_details.item_code'); 
               
            // dd(DB::getQueryLog());
        
        foreach ($data as $value) 
        {
           if($cat_id[0]->cat_id=='1')
           {
                $stock = DB::select(DB::raw("SELECT 
                                CASE 
                                    WHEN ROUND(
                                        (
                                            (SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                        ), 2
                                    ) = 0 THEN 0
                                    ELSE ROUND(
                                        (
                                            (SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                        ), 2
                                    )
                                END AS Stock"));

           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                       
                        if($value->class_id==4)
                        {
                            $dis = '';
                        }
                        else
                        {
                            $dis = 'disabled';
                        }


                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        $html .='<tr class="cls_'.$value->class_id.'" data-cat="Fabric">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>           
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left" disabled>+</button></td> 
                        <td>  
                            <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);">X</button> 
                        </td>        
                        <td>  
                            <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv" '.$dis.'>?</button>
                        </td>                    
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->class_name.'</td> 
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        $html.='
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;" onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="'.round($value->item_qty).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any" name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any" name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any" name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any" name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any" name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any" name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" name="freight_amt[]" onkeyup="calFreightAmt(this);"  class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any" name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any" name="conQtys[]" readOnly value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="unitIdMs[]" readOnly value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="priUnitds[]" readOnly value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="SecConQtys[]" readOnly value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="secUnitIds[]" readOnly value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="poQtys[]" readOnly value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="poUnitIds[]" readOnly value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="rateMs[]" readOnly value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any" name="totalQtys[]" readOnly value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
        }
    
        
        //**************** For Trim Fabric Data From Table *************************
        
        
          $data= DB::select('select  item_master.*,bom_trim_fabric_details.*,sum(bom_trim_fabric_details.bom_qty) as bom_qty, (sum(bom_trim_fabric_details.bom_qty) 
                - (select ifnull(sum(item_qty),0) from purchaseorder_detail where bom_trim_fabric_details.item_code = purchaseorder_detail.item_code 
                AND bom_trim_fabric_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty,classification_master.class_name  from bom_trim_fabric_details
          inner join item_master on item_master.item_code=bom_trim_fabric_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where usedFlag=0 and item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY bom_trim_fabric_details.sales_order_no, bom_trim_fabric_details.item_code'); 
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
         
        foreach ($data as $value) 
        {
           if($cat_id[0]->cat_id=='1')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
                // (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("
                                SELECT 
                                    CASE 
                                        WHEN ROUND(
                                            ((SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        ) = 0 THEN 0
                                        ELSE ROUND(
                                            ((SELECT IFNULL(SUM(meter), 0) FROM inward_details WHERE item_code = '".$value->item_code."')
                                            -
                                            (SELECT IFNULL(SUM(meter), 0) FROM fabric_outward_details WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        )
                                    END AS Stock"));
                            

           }
        //   elseif($cat_id[0]->cat_id=='2' || $cat_id[0]->cat_id=='3')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
        //         (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
          
              
                        if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                         
                        if($value->class_id==4)
                        {
                            $dis = '';
                        }
                        else
                        {
                            $dis = 'disabled';
                        }


                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'" data-cat="Fabric">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td> 
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left" disabled>+</button></td> 
                        <td>  
                            <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);">X</button> 
                        </td>     
                        <td>  
                            <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv" '.$dis.'>?</button>
                        </td>     
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->class_name.'</td> 
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0; 
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($max).'"  value="'.round($value->item_qty).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$value->rate_per_unit.'" value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                        <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                      ';
                        $html .='</tr>';
                        $no=$no+1;
    }
        
        
        //*********************** End of Trim Fabric Data  ************************
         
        
     } 
     else if($cat_id[0]->cat_id=='2')
     { 
        $table="bom_sewing_trims_details"; 
         foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        $bom_code=rtrim($bom_code,",");
       // echo $bom_code;
        //   DB::enableQueryLog();
          $data= DB::select('select item_master.*, bom_sewing_trims_details.*,sum(bom_sewing_trims_details.bom_qty) as bom_qty, (sum(bom_sewing_trims_details.bom_qty) 
                - (select ifnull(sum(item_qty),0)from purchaseorder_detail where bom_sewing_trims_details.item_code = purchaseorder_detail.item_code
                 AND bom_sewing_trims_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty,classification_master.class_name  from bom_sewing_trims_details
          inner join item_master on item_master.item_code=bom_sewing_trims_details.item_code
          inner join classification_master on classification_master.class_id=item_master.class_id
          where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.') GROUP BY  bom_sewing_trims_details.sales_order_no, bom_sewing_trims_details.item_code'); 
        
        
        //On Request from Ken Team(Nikhil Bhosale), this condition removed to raise multiple po for the same item for the same sales order
        //  and bom_sewing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))
        
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);   
         
          foreach ($data as $value) 
     {
        //   if($cat_id[0]->cat_id=='1')
        //   {
        //         $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$value->item_code."')-
        //         (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$value->item_code."')
        //         ) as Stock"));
        //   }
        //   else
        
           if($cat_id[0]->cat_id=='2')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                // (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("SELECT 
                                    CASE 
                                        WHEN ROUND(
                                            (
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                -
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        ) = 0 THEN 0
                                        ELSE ROUND(
                                            (
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                -
                                                (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                            ), 2
                                        )
                                    END AS Stock"));

           }
          
              
                        // if($request->tax_type_id==1)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                        //     from item_master where item_code='".$value->item_code."'"));
                        //     $Camt=($value->total_amount * ($value->cgst_per/100));
                        //     $Samt=($value->total_amount * ($value->sgst_per/100));
                        //     $Iamt=0;                 
                        //     $TAmount=$value->total_amount + $Camt+ $Samt;
                        //     $igst_per=0;
                        //     // print_r($value->item_code);
                        // } 
                        // elseif($request->tax_type_id==2)
                        // {
                        //     $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                        //     from item_master where item_code='$value->item_code'"));
                        //     $Iamt=($value->total_amount * ($value->igst_per/100));
                        //     $Camt=0;
                        //     $Samt=0;
                        //     $TAmount=$value->total_amount + $Iamt ;
                        // } 
                      
                      
                      if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                         
                        if($value->class_id==4)
                        {
                            $dis = '';
                        }
                        else
                        {
                            $dis = 'disabled';
                        }


                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'" data-cat="Sewing">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left" disabled>+</button></td> 
                        <td>  
                            <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);">X</button> 
                        </td> 
                        <td>  
                            <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv" '.$dis.'>?</button>
                        </td>    
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->class_name.'</td> 
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        
                        if($value->class_id == 12)
                        {
                            $button_qty = round($value->item_qty/144,precision: 2);
                            $button_rate = $value->rate_per_unit * 144;
                        }
                        else
                        {
                             $button_qty = round($value->item_qty);
                             $button_rate = $value->rate_per_unit;
                        }
                        
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');"  readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.$button_qty.'"  value="'.$button_qty.'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$button_rate.'" value="'.$button_rate.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" /><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" /><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        ';
                        if($value->class_id==4) {
                            $html .='<input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poQtys[]" readOnly     value="'.ceil($max/10000).'" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="rateMs[]" readOnly     value="'.round(($value->rate_per_unit *  10000),2).'" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="'.ceil($max).'" style="width:80px; height:30px;">
                            
                            </td>
                            ';
                         }
                         else
                         {
                              $html .='<input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                            <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                            
                            </td>
                            ';
                         }
                        $html .='</tr>';
                        $no=$no+1;
    }
             
     } 
     else if($cat_id[0]->cat_id=='3')
     { 
        $table="bom_packing_trims_details"; 
       // $data=DB::table($table)->whereIn('bom_code',$bom_codeids)->get();
        
        foreach($bom_codeids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
            
        }
        
        $bom_code=rtrim($bom_code,",");
        $data= DB::select('select  item_master.*,bom_packing_trims_details.*,sum(bom_packing_trims_details.bom_qty) as bom_qty, (sum(bom_packing_trims_details.bom_qty) 
                - (select ifnull(sum(item_qty),0)from purchaseorder_detail where bom_packing_trims_details.item_code = purchaseorder_detail.item_code
                 AND bom_packing_trims_details.sales_order_no = purchaseorder_detail.sales_order_no)) as item_qty,classification_master.class_name  
        from bom_packing_trims_details
        inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        inner join classification_master on classification_master.class_id=item_master.class_id
        where item_master.class_id in('.$class_ids.') and
        bom_code in ('.$bom_code.') GROUP BY  bom_packing_trims_details.sales_order_no,bom_packing_trims_details.item_code'); 
        
        
        //On Request from Ken Team(Nikhil Bhosale), this condition removed to raise multiple po for the same item for the same sales order
        //   $data= DB::select('select * from bom_packing_trims_details
        //   inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        //   inner join classification_master on classification_master.class_id=item_master.class_id
        //   where item_master.class_id in('.$class_ids.') and bom_code in ('.$bom_code.')
        //   and bom_packing_trims_details.item_code not in (select item_code from purchaseorder_detail where purchaseorder_detail.bom_code in  ('.$bom_code.'))'); 
        
        
        // $bom_code=rtrim($bom_code,",");
        // $data= DB::select('select *,  sum(bom_packing_trims_details.bom_qty) as total_bom_qty,
        // (select ifnull(sum(item_qty),0)  from purchaseorder_detail 
        // where  purchaseorder_detail.bom_code in ('.$bom_code.') and purchaseorder_detail.item_code =bom_packing_trims_details.item_code )as po_qty
        // from bom_packing_trims_details
        // inner join item_master on item_master.item_code=bom_packing_trims_details.item_code
        // inner join classification_master on classification_master.class_id=item_master.class_id
        // where item_master.class_id in('.$class_ids.') and
        // bom_code in ('.$bom_code.')'); 
        
         
    foreach ($data as $value) 
    {
        
        
    //     if($value->po_qty < $value->total_bom_qty)
    //   { 
        
      
           if( $cat_id[0]->cat_id=='3')
           {
                // $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$value->item_code."')-
                // (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$value->item_code."')
                // ) as Stock"));
                
                $stock = DB::select(DB::raw("SELECT 
                                            CASE 
                                                WHEN ROUND(
                                                    ((SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                    -
                                                    (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                                    ), 2
                                                ) = 0 THEN 0
                                                ELSE ROUND(
                                                    ((SELECT IFNULL(SUM(item_qty), 0) FROM trimsInwardDetail WHERE item_code = '".$value->item_code."')
                                                    -
                                                    (SELECT IFNULL(SUM(item_qty), 0) FROM trimsOutwardDetail WHERE item_code = '".$value->item_code."')
                                                    ), 2
                                                )
                                            END AS Stock"));

           }
          
              
                         if($request->tax_type_id==1)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path , moq
                            // from item_master where item_code='".$value->item_code."'"));
                            $CPer=$value->cgst_per;
                            $Camt=($value->total_amount * ($CPer/100));
                            $SPer=$value->sgst_per;
                            $Samt=($value->total_amount * ($SPer/100));
                            $IPer=0;
                            $Iamt=0;                 
                            $TAmount=$value->total_amount + $Camt+ $Samt;
                            // print_r($value->item_code);
                        } 
                        elseif($request->tax_type_id==2)
                        {
                            // $datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path, moq
                            // from item_master where item_code='$value->item_code'"));
                            $Camt=0;
                            $Samt=0;
                            $CPer=0;
                            $Camt=0;
                            $SPer=0;
                            $Samt=0;
                            $IPer=$value->igst_per;
                            $Iamt=($value->total_amount * ($IPer/100));
                            $TAmount=$value->total_amount + $Iamt ;
                        } 
                        
                        if($value->class_id==4)
                        {
                            $dis = '';
                        }
                        else
                        {
                            $dis = 'disabled';
                        }

                        
                        $itemlist=DB::table('item_master')->where('delflag','=','0')->where('item_code','=', $value->item_code)->get();
                        $unitlist=DB::table('unit_master')->where('delflag','=','0')->where('unit_id','=', $value->unit_id)->get();
                        
                        $html .='<tr class="cls_'.$value->class_id.'" data-cat="Packing">';
                        $html .='
                        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
                        <td> <button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left" disabled>+</button> </td> 
                        <td>  
                            <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);">X</button> 
                        </td>    
                        <td>  
                            <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv"  '.$dis.'>?</button>
                        </td>   
                        <td><input type="text"  name="sales_order_no[]" value="'.$value->sales_order_no.'" id="sales_order_no" style="width:80px;"  readOnly/> </td>
                        <td>'.$value->class_name.'</td>
                        <td>'.$value->item_code.'</td>
                        <td> <select name="item_codes[]"  id="item_code" style="width:250px; height:30px;"  disabled>
                        <option value="">--Select Item--</option>';
                        foreach($itemlist as  $row1)
                        {
                            $html.='<option value="'.$row1->item_code.'"';
                            $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$row1->item_name.'</option>';
                        }
                        $html.='</select></td> 
                        <td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code.'" id="hsn_code" style="width:80px;"  readOnly/> </td>';
                        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
                        <option value="">--Select Unit--</option>';
                        foreach($unitlist as  $rowunit)
                        {
                            $html.='<option value="'.$rowunit->unit_id.'"';
                            $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>';
                        $max=0;
                        if($value->moq>$value->bom_qty || $value->moq>$value->item_qty){$max=$value->moq;}else{$max=$value->item_qty ?? $value->bom_qty;}
                        
                        
                        if($value->class_id == 12)
                        {
                            $button_qty = $value->item_qty/144;
                            $button_rate = $value->rate_per_unit * 144;
                        }
                        else
                        {
                             $button_qty = $value->item_qty;
                             $button_rate = $value->rate_per_unit;
                        }
                        
                        $html.='
                        
                        <td><input type="text" value="'.round($value->bom_qty).'" name="bom_qty[]"  style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="'.$stock[0]->Stock.'"   style="width:80px;  height:30px;"  onclick="stockPopup(this,'.$value->item_code.');" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"    min="'.round($value->moq).'" max="'.round($button_qty).'"  value="'.round($button_qty).'" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]" min="0" max="'.$button_rate.'" value="'.$button_rate.'" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="'.$CPer.'" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="'.$SPer.'" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="'.$IPer.'" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="hidden" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any"    name="amounts[]" readOnly value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="text" value="'.$value->moq.'"   style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px; height:30px;" required/>
                        
                        
                         <input type="hidden" step="any"  name="conQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                        ';
                        $html .='</tr>';
                        $no=$no+1;
    //  }
   
    }
     }
    
    return response()->json(['html' => $html]);
    }
    
    public function GetStockDetailPopup(Request $request)
    {

        if($request->bom_type_arr[0] == 2 || $request->bom_type_arr[0] == 3)
        {
         $TrimsInwardStockDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardMaster.trimCode,trimsInwardDetail.item_code,
            ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
            
            (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
            where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
            
            trimsInwardMaster.po_code, 
           ledger_master.ac_name,item_master.dimension,item_master.item_name,
            item_master.color_name,item_master.item_description,rack_master.rack_name
            from trimsInwardDetail
            left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
            left join item_master on item_master.item_code=trimsInwardDetail.item_code
            left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id 
            where trimsInwardDetail.item_code = '".$request->item_code."' 
            group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
            ");
            
            $html = '';
            
            foreach($TrimsInwardStockDetails as $stockDetails)
            {
                $stock_qty = number_format($stockDetails->item_qty-$stockDetails->out_qty);
                if($stock_qty > 0)
                {
                   $html .='<tr>
                                <td>'.$stockDetails->ac_name.'</td>
                                <td>'.$stockDetails->po_code.'</td>
                                <td>'.$stockDetails->trimCode.'</td>
                                <td>-</td>
                                <td>'.$stock_qty.'</td>
                                <td>-</td>
                                <td>'.$stockDetails->rack_name.'</td>
                            </tr>';
                }
            }
        }
        
        
        if($request->bom_type_arr[0] == 1)
        {
            //DB::enableQueryLog();
            $FabricInwardDetails =DB::select("select inward_details.* ,inward_master.po_code as po_codes,inward_master.in_code, inward_master.invoice_no,inward_details.track_code,shade_master.shade_name,
                (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
                cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
                item_master.item_name,item_master.color_name,item_master.item_description,
                quality_master.quality_name,rack_master.rack_name from inward_details
                left join inward_master on inward_master.in_code=inward_details.in_code
                left  join cp_master on cp_master.cp_id=inward_details.cp_id 
                left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
                left join item_master on item_master.item_code=inward_details.item_code 
                left join quality_master on quality_master.quality_code=item_master.quality_code 
                left join part_master on part_master.part_id=inward_details.part_id 
                left join shade_master on shade_master.shade_id=inward_details.shade_id 
                left join rack_master on rack_master.rack_id=inward_details.rack_id  
                where inward_details.item_code = '".$request->item_code."' ");
            //dd(DB::getQueryLog());    
            $html = '';
            
            foreach($FabricInwardDetails as $row)
            {
                $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name FROM fabric_checking_details 
                  LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                  WHERE track_code = '".$row->track_code."'");
                  if(count($checking_width) > 0)
                  {
                        $width = $checking_width[0]->width;
                  }
                  else
                  {
                        $width = 0;
                  }  
                  $totalQty = ($row->meter) - ($row->out_meter);
                $html .='<tr>
                            <td>'.$row->ac_name.'</td>
                            <td>'.$row->po_code.'</td>
                            <td>'.$row->in_code.'</td>
                            <td>'.$row->track_code.'</td>
                            <td>'.$totalQty.'</td>
                            <td>'.$width.'</td>
                            <td>'.$row->rack_name.'</td>
                        </tr>';
            }
        }
        
        return response()->json(['html' => $html]);
    }
    
     
    public function UpdatePurchaseOrderStatus(Request $request)
    {
        $po_no = $request->po_no;
        $item_code = $request->item_code;
        $job_status_id =  $request->job_status_id;
        
        DB::table("purchaseorder_detail")->where('pur_code', $po_no)->where('item_code', $item_code)->update(['job_status_id' => $job_status_id]);
        DB::table("dump_trim_stock_data")->where('po_no', $po_no)->where('item_code', $item_code)->update(['job_status_id' => $job_status_id,'closeDate'=>date('Y-m-d')]);
        
        return 1;
    }
    
    public function getItemCodeList(Request $request)
    { 
        $ItemList = DB::SELECT("SELECT item_code FROM item_master WHERE delflag=0 AND class_id =".$request->class_id);
        $item_arr = [];
        foreach($ItemList as $row)
        {
            $item_arr[] = $row->item_code;
        }
        return response()->json(['ItemList' => $item_arr]);
    }

    public function GetBuyerFromBOM(Request $request)
    { 
        $BOMList = DB::SELECT("SELECT Ac_code FROM bom_master WHERE delflag=0 AND bom_code ='".$request->bom_code."'");
         
        return response()->json(['buyer_id' => $BOMList[0]->Ac_code]);
    }
    
     
    public function GetAllTradersFromLedger(Request $request)
    {
        
            $ac_code= $request->input('ac_code'); 
            $PartyDetails = DB::select("select * from ledger_details");
            $html = '<option value="">--Select--</option>';
            foreach($PartyDetails as  $row)
            {
                $html.='<option value="'.$row->trade_name.'">'.$row->trade_name.'('.$row->site_code.')</option>';
            }
            return response()->json(['detail' => $html]); 
         
    }
    
     
    public function GetAllTradersFromPurchase(Request $request)
    {
        
            $ac_code= $request->input('ac_code'); 
            $PartyDetails = DB::select("select * from ledger_details WHERE sr_no IN(1083,1084,1085)");
            $html = '<option value="">--Select--</option>';
            foreach($PartyDetails as  $row)
            {
                $html.='<option value="'.$row->sr_no.'">'.$row->trade_name.'('.$row->site_code.')</option>';
            }
            return response()->json(['detail' => $html]); 
         
    }
    public function GetPartyDetailsPurchase(Request $request)
    {
        
            $ac_code= $request->input('ac_code');
            $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
            $PartyDetails = DB::select("select * from ledger_details where ac_code='".$ac_code."'");
            $html = '<option value="">--Select--</option>';
            foreach($PartyDetails as  $row)
            {
                $html.='<option value="'.$row->sr_no.'">'.$row->trade_name.'('.$row->site_code.')</option>';
            }
            return response()->json(['master' => $PartyRecords, 'detail' => $html]); 
         
    }
    
}
