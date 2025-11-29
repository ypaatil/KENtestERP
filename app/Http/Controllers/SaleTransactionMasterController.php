<?php
namespace App\Http\Controllers;
use App\Models\SaleTransactionMasterModel;
use App\Models\SaleTransactionDetailModel;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\MonthlyShipmentTargetModel;
use App\Models\MonthlyShipmentTargetDetailModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Session;
use App\Services\SaleTransactionActivityLog;
use App\Services\SaleTransactionMasterActivityLog;
use Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;



date_default_timezone_set('Asia/Kolkata');

class SaleTransactionMasterController extends Controller
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
            ->where('form_id', '115')
            ->first();
 
             // DB::enableQueryLog();
            // $SaleTransactionMasterData = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
            // ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
            // ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
            // ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')
            // ->where('sale_transaction_master.sale_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            // ->where('sale_transaction_master.delflag', '=', '0')
            // ->orderBy('sale_transaction_master.sr_no', 'DESC')
            // ->select([
            //     'sale_transaction_master.*',
            //     'usermaster.username',
            //     'lm1.ac_short_name as ac_name1',
            //     'firm_master.firm_name',
            //     'tax_type_master.tax_type_name',
            //     DB::raw("(SELECT GROUP_CONCAT(sales_order_no) FROM sale_transaction_detail WHERE sale_transaction_detail.sale_code = sale_transaction_master.sale_code) as sales_order_nos")
            // ])
            // ->get();
            
           $SaleTransactionMasterData = SaleTransactionMasterModel::join('ledger_master as lm1', 'lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
            ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
            ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')
            // ->leftJoin(DB::raw('(SELECT sale_code, GROUP_CONCAT(sales_order_no) as sales_order_nos FROM sale_transaction_detail GROUP BY sale_code) as std'), 'std.sale_code', '=', 'sale_transaction_master.sale_code')
            ->where('sale_transaction_master.sale_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            ->where('sale_transaction_master.delflag', '=', '0')
            ->orderBy('sale_transaction_master.sr_no', 'DESC')
            ->select([
                'sale_transaction_master.*',
                'usermaster.username',
                'lm1.ac_short_name as ac_name1',
                'firm_master.firm_name',
                'tax_type_master.tax_type_name'
            ])
            // ->limit(100) // Optional: limit for performance
            ->get();

            
            
            
        //dd(DB::getQueryLog());
        return view('SaleTransactionMasterList', compact('SaleTransactionMasterData','chekform'));
    }
    
    public function saleTransactionShowAll()
    { 
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '115')
            ->first();
 
        // DB::enableQueryLog();
        $SaleTransactionMasterData = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
            ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
            ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')
            ->where('sale_transaction_master.delflag', '=', '0')
            ->orderBy('sale_transaction_master.sr_no', 'DESC')
            ->select([
                'sale_transaction_master.*',
                'usermaster.username',
                'lm1.ac_short_name as ac_name1',
                'firm_master.firm_name',
                'tax_type_master.tax_type_name',
                // DB::raw("(SELECT GROUP_CONCAT(sales_order_no) FROM sale_transaction_detail WHERE sale_transaction_detail.sale_code = sale_transaction_master.sale_code) as sales_order_nos")
            ])
            ->get();
        //dd(DB::getQueryLog());
        return view('SaleTransactionMasterList', compact('SaleTransactionMasterData','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='SaleTransaction' and c_name='C1'"));
        $firmlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.isFirm','=', '1')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $unitlist = DB::table('unit_master')->get();
        $salesHeadlist = DB::table('sales_head_master')
                        ->join('sales_head_auth', 'sales_head_auth.sales_head_id', '=', 'sales_head_master.sales_head_id')
                        ->where('sales_head_auth.username','=', Session::get('username'))
                        ->where('sales_head_master.delflag','=', '0')
                        ->get();
        $paymentTermslist = DB::table('payment_term')->where('payment_term.delflag','=', '0')->get();
        
        $dispatchlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '5')->get();
        
        $shipment_mode_list = DB::table('shipment_mode_master')->where('shipment_mode_master.delflag','=', '0')->get();

		return view('SaleTransactionMaster',compact('firmlist','ledgerlist','gstlist', 'code','unitlist','salesHeadlist','paymentTermslist','dispatchlist','shipment_mode_list'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
       try 
       {  
           
          DB::beginTransaction();
           
         $firm_id=$request->input('firm_id');      
    
        //DB::enableQueryLog();
          $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
          ->where('c_name','=','C1')
          ->where('type','=','SaleTransaction')
          ->first();
          //DB::enableQueryLog();
        /*$query = DB::getQueryLog();
        $query = end($query);
        dd($query);*/
         
        $TrNo=$codefetch->code.'/21-22/'.'KDPL'.$codefetch->tr_no;
        $carton_packing_nos=implode(",",$request->input('carton_packing_no'));
        
        $codes='';
        $cpki_codes=$request->input('carton_packing_no');
        foreach($cpki_codes as $cpki)
        {
                $codes=$codes."'".$cpki."',";
        }
        $codes=rtrim($codes,",");
        $CloseCPKI=DB::select("update carton_packing_inhouse_master set endflag=1 where cpki_code in (".$codes.")");
        
        $data = array('sale_code'=>$request->sale_code,
            "carton_packing_nos"=>$carton_packing_nos,
            "sale_date"=> $request->sale_date,
            "Ac_code"=> $request->Ac_code,
            "tax_type_id"=> $request->tax_type_id,
            "total_qty"=> $request->total_qty,
            "freight_charges"=> $request->freight_charges,
            "Gross_amount"=> $request->Gross_amount,
            "other_cost"=> $request->other_cost,
            "other_cost_gst_per"=> $request->other_cost_gst_per,
            "other_cost_gst_amt"=> $request->other_cost_gst_amt,
            "sent_through"=> $request->sent_through,
            "address"=> $request->address,
            "Gst_amount"=> $request->Gst_amount,
            "Net_amount"=> $request->Net_amount,
            "narration"=> $request->narration,
            "firm_id"=> $request->firm_id,
            "c_code"=> $codefetch->c_code,
            "terms_and_conditions"=> $request->terms_and_conditions,
            "userId"=> $request->userId,
            "sales_head_id"=> $request->sales_head_id,
            "isCancel"=>0,
            "delflag"=>0,
            "created_at"=>date("Y-m-d h:i:s"),
            "delivary_note"=> $request->delivary_note,
            "delivary_note_date"=> $request->delivary_note_date,
            "mode_of_payment"=> $request->mode_of_payment,
            "transport_id"=> $request->transport_id,
            "bill_of_landing"=> $request->bill_of_landing,
            "vehicle_no"=> $request->vehicle_no,
            "terms_of_delivery_id"=> $request->terms_of_delivery_id,
            "bill_to"=> $request->bill_to,
            "ship_to"=> $request->ship_to,
            "destination"=> $request->destination,
            "no_of_cartons"=> $request->no_of_cartons,
            "distance"=> $request->distance,
            "transDocNo"=> $request->transDocNo,
            "transDocDate"=> $request->transDocDate
        );
        
        // Insert
        $value = SaleTransactionMasterModel::insertGetId($data);
        if($value){
        Session::flash('message','Insert successfully.');
        }else{
        Session::flash('message','Username already exists.');
        }
         
        $sales_order_no=count($request->sales_order_no);
        
        $sr_no = DB::table('sale_transaction_master')->max('sr_no'); 
        
        if($sales_order_no>0)
        {
        
            for($x=0;$x<$sales_order_no; $x++) 
            { 
                $data2[]=array(
                
                'sr_no' =>$value,
                'sale_code' =>$request->input('sale_code'),
                'sale_date' => $request->input('sale_date'),
                'Ac_code' => $request->input('Ac_code'),
                'sales_order_no' => $request->sales_order_no[$x] ?? null,
                'buyer_po_no' => $request->buyer_po_no[$x] ?? null,
                'style_no_id' => $request->style_no_id[$x] ?? null,
                'hsn_code' => $request->hsn_code[$x] ?? null,
                'unit_id' => $request->unit_id[$x] ?? null,
                'order_qty' => $request->order_qty[$x] ?? 0,
                'pack_order_qty' => $request->pack_order_qty[$x] ?? 0,
                'order_rate' => $request->order_rate[$x] ?? 0,
                'disc_per' => $request->disc_pers[$x] ?? 0,
                'disc_amount' => $request->disc_amounts[$x] ?? 0,
                'sale_cgst' => $request->sale_cgsts[$x] ?? 0,
                'camt' => $request->camts[$x] ?? 0,
                'sale_sgst' => $request->sale_sgsts[$x] ?? 0,
                'samt' => $request->samts[$x] ?? 0,
                'sale_igst' => $request->sale_igsts[$x] ?? 0,
                'iamt' => $request->iamts[$x] ?? 0,
                'amount' => $request->amounts[$x] ?? 0,
                'total_amount' => $request->total_amounts[$x] ?? 0,
                'firm_id' => $request->firm_id);
            
                // $cartonData = explode(",",$carton_packing_nos);
                // foreach($cartonData as $row1)
                // {
                //     //DB::enableQueryLog();
                //     $cartonDetails = DB::select("
                //         SELECT cpki_code,sales_order_no,size_id,sum(size_qty) as size_qty,color_id FROM carton_packing_inhouse_size_detail2 
                //         WHERE cpki_code = '".$row1."'  
                //           AND sales_order_no = '".$request->sales_order_no[$x]."' 
                //         GROUP BY cpki_code, size_id,sales_order_no,color_id
                //     ");
                    
                //     foreach ($cartonDetails as $row2) 
                //     {
                //         DB::table('FGStockDataByTwo')
                //             ->where('code', '=', $row2->cpki_code)
                //             ->where('sales_order_no', '=', $request->sales_order_no[$x]) 
                //             ->update([
                //                 'is_sale' => 0,
                //                 'invoice_no' => $request->sale_code,
                //                 'invoice_date' => $request->sale_date
                //             ]);
                             
                //     }  
                     
                //     DB::SELECT('INSERT INTO temp_sales_transaction(sale_code,sale_date,order_qty,cpki_code,sales_order_no)
                //     select "'.$request->sale_code.'","'.$request->sale_date.'","'.$request->order_qty[$x].'","'.$row1.'","'.$request->sales_order_no[$x].'"');  
                    
                    
                // }
                
                $cartonData = explode(",",$carton_packing_nos);
                //print_r($cartonData);exit;
                foreach($cartonData as $row1)
                { 
                    DB::table('FGStockDataByTwo')
                    ->where('code', '=', $row1)
                    ->where('sales_order_no', '=', $request->sales_order_no[$x])
                    ->update([
                        'is_sale' => 0,
                        'invoice_no' => $request->sale_code,
                        'invoice_date' => $request->sale_date
                        ]);          
                        DB::SELECT('INSERT INTO temp_sales_transaction(sale_code,sale_date,order_qty,cpki_code,sales_order_no)
                        select "'.$request->sale_code.'","'.$request->sale_date.'","'.$request->order_qty[$x].'","'.$row1.'","'.$request->sales_order_no[$x].'"');   
                }
            }
            
                SaleTransactionDetailModel::insert($data2);
                }
            
            $sales_head_id = $request->input('sales_head_id');
            
               
            DB::select("update counter_number set tr_no= tr_no + 1 where c_name ='C1' AND type='SaleTransaction'");  
            DB::select("update sales_head_master set tr_no= tr_no + 1 where sales_head_id =".$sales_head_id);  
         
            DB::commit();
            return redirect()->route('SaleTransaction.index')->with('message', 'Add Record Succesfully');
              
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
     * Display the specified resource.
     *
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '115')
        ->first();

        $data = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        ->where('sale_transaction_master.delflag','=', '0') 
        ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '115')
        ->first();

        $data = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        ->where('sale_transaction_master.delflag','=', '0') 
        ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        ini_set('memory_limit', '10240M');

        $firmlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.isFirm','=', '1')->get();
        $ledgerlist = DB::table('ledger_master')->get();
        $gstlist = DB::table('tax_type_master')->get(); 
        $salesHeadlist = DB::table('sales_head_master')
                         ->join('sales_head_auth', 'sales_head_auth.sales_head_id', '=', 'sales_head_master.sales_head_id')
                         ->where('sales_head_auth.username','=', Session::get('username'))
                         ->where('sales_head_master.delflag','=', '0')
                         ->get();

        $unitlist = DB::table('unit_master')->get();
        
        $paymentTermslist = DB::table('payment_term')->where('payment_term.delflag','=', '0')->get();
        
        $shipment_mode_list = DB::table('shipment_mode_master')->where('shipment_mode_master.delflag','=', '0')->get();
        
        $dispatchlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '5')->get();
    
        //DB::enableQueryLog();

        $SaleTransactionMasterList = SaleTransactionMasterModel::find($id);
        //dd(DB::getQueryLog());
         $carton_packing_nos = "'" . implode ( "', '",explode(',', $SaleTransactionMasterList->carton_packing_nos)) . "'";
         
         
        $CartonPackingList = DB::select("select distinct carton_packing_inhouse_size_detail.cpki_code,carton_packing_inhouse_size_detail.sales_order_no from carton_packing_inhouse_size_detail
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code= carton_packing_inhouse_size_detail.cpki_code
        where carton_packing_inhouse_size_detail.Ac_code ='".$SaleTransactionMasterList->Ac_code."' and carton_packing_inhouse_master.endflag!=1
        
        union 
        
        select distinct carton_packing_inhouse_size_detail.cpki_code,carton_packing_inhouse_size_detail.sales_order_no from carton_packing_inhouse_size_detail
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code= carton_packing_inhouse_size_detail.cpki_code
        where carton_packing_inhouse_size_detail.cpki_code in ($carton_packing_nos) GROUP BY carton_packing_inhouse_size_detail.cpki_code");
         
        // DB::enableQueryLog();
        $SaleTransactionDetails = SaleTransactionDetailModel::where('sr_no', '=', $id)->groupBy('sale_transaction_detail.sales_order_no')->get(['sale_transaction_detail.*']);

        $ledgerDetails = DB::table('ledger_details')->where('ac_code','=', $SaleTransactionMasterList->Ac_code)->get();
        // dd(DB::getQueryLog());
        
        $hsnlist = DB::table('hsn_master')->where('delflag','=', 0)->get();
        
        return view('SaleTransactionMasterEdit',compact('SaleTransactionMasterList','hsnlist','firmlist','CartonPackingList','ledgerlist','gstlist', 'SaleTransactionDetails','unitlist','salesHeadlist','paymentTermslist','dispatchlist','shipment_mode_list','ledgerDetails' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,SaleTransactionActivityLog $logger,SaleTransactionMasterActivityLog $loggerMaster)
    {
        try
        {
            
            DB::beginTransaction();
           
           // echo "<pre>";print_r($_POST);exit;
            if($request->input('isCancel') != "")
            {
                $isCancel = 1; 
            }
            else
            {
                $isCancel = 0; 
            }
       
            $sale_code=$request->input('sale_code');
            
            $carton_packing_nos=implode(",",$request->input('carton_packing_no'));
            
            
            $codes='';
            //DB::enableQueryLog();
            $Saved=DB::select("select carton_packing_nos from sale_transaction_master where sr_no ='".$id."'");
            //dd(DB::getQueryLog());
            if(count($Saved) > 0)
            {
                $SavedCodes=explode(",", $Saved[0]->carton_packing_nos);
                foreach($SavedCodes as $cpkis)
                {
                  $codes=$codes."'".$cpkis."',";
                }
                
                $codes=rtrim($codes,",");
            }
            else
            {
               $codes = ""; 
            }
         
            
            $CloseCPKI=DB::select("update carton_packing_inhouse_master set endflag=0 where cpki_code in(".$codes.")");
            
            
            $code='';
            $cpki_codes=$request->input('carton_packing_no');
            foreach($cpki_codes as $cpki)
            {
                $code=$code."'".$cpki."',";
            }
            $code=rtrim($code,",");
            $CloseCPKI=DB::select("update carton_packing_inhouse_master set endflag=1 where cpki_code in (".$code.")");
             
            $data = array(
            'sale_code'=>$request->sale_code,
            "sale_date"=> $request->sale_date,
            "Ac_code"=> $request->Ac_code,
            "carton_packing_nos"=>$carton_packing_nos,
            "tax_type_id"=> $request->tax_type_id,
            "total_qty"=> $request->total_qty,
            "freight_charges"=> $request->freight_charges,
            "Gross_amount"=> $request->Gross_amount,
            "other_cost"=> $request->other_cost,
            "other_cost_gst_per"=> $request->other_cost_gst_per,
            "other_cost_gst_amt"=> $request->other_cost_gst_amt,
            "sent_through"=> $request->sent_through,
            "address"=> $request->address,
            "Gst_amount"=> $request->Gst_amount,
            "Net_amount"=> $request->Net_amount,
            "narration"=> $request->narration,
            "firm_id"=> $request->firm_id,
            "c_code"=> $request->c_code,
            "userId"=> $request->userId,
            "sales_head_id"=> $request->sales_head_id,
            "terms_and_conditions"=> $request->terms_and_conditions,
            "delflag"=>0,
            "isCancel"=>$isCancel,
            "updated_at"=>date("Y-m-d H:i:s"),
            "delivary_note"=> $request->delivary_note,
            "delivary_note_date"=> $request->delivary_note_date,
            "mode_of_payment"=> $request->mode_of_payment,
            "transport_id"=> $request->transport_id,
            "bill_of_landing"=> $request->bill_of_landing,
            "vehicle_no"=> $request->vehicle_no,
            "terms_of_delivery_id"=> $request->terms_of_delivery_id,
            "bill_to"=> $request->bill_to,
            "ship_to"=> $request->ship_to,
            "destination"=> $request->destination,
            "no_of_cartons"=> $request->no_of_cartons,
            "distance"=> $request->distance,
            "transDocNo"=> $request->transDocNo,
            "transDocDate"=> $request->transDocDate
            );
            
            // Insert
            $SalesTransactionMasterList = SaleTransactionMasterModel::findOrFail($id);
            
            
                $MasterOldFetch = DB::table('sale_transaction_master')
            ->select('carton_packing_nos', 'Ac_code', 'tax_type_id', 'total_qty', 'freight_charges', 'Gross_amount', 'other_cost',
            'other_cost_gst_per', 'other_cost_gst_amt', 'Gst_amount', 'Net_amount', 'narration', 
             'sent_through','address','terms_and_conditions', 'isCancel')  
            ->where('sale_code',$request->sale_code)
            ->first();
            
            
    
                 $MasterOld = (array) $MasterOldFetch;
       
            
            
                      $MasterNew=[
            "carton_packing_nos"=>$carton_packing_nos,
            "Ac_code"=> $request->Ac_code,
            "tax_type_id"=> $request->tax_type_id,
            "total_qty"=> $request->total_qty,
            "freight_charges"=> $request->freight_charges,
            "Gross_amount"=> $request->Gross_amount,
            "other_cost"=> $request->other_cost,
            "other_cost_gst_per"=> $request->other_cost_gst_per,
            "other_cost_gst_amt"=> $request->other_cost_gst_amt,
            "Gst_amount"=> $request->Gst_amount, 
            "Net_amount"=> $request->Net_amount,  
             "narration"=> $request->narration, 
            "sent_through"=> $request->sent_through,
            "address"=> $request->address,
            "terms_and_conditions"=> $request->terms_and_conditions,
            "isCancel"=>$isCancel];
            
            
            
                   try {
                $loggerMaster->logIfChangedSaleTransactionMaster(
                'sale_transaction_master',
                $request->sale_code,
                0,
                $MasterOld,
                $MasterNew,
                'UPDATE',
                $request->sale_date,
                'sale_transaction_master'
                );
                // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
                //   $newDataDetail
                // ]);
                } catch (\Exception $e) {
                Log::error('Logger failed for sale_transaction_master.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sale_code' => $request->sale_code,
                'data' => $MasterNew
                ]);
                }  
             
            $SalesTransactionMasterList->fill($data)->save();
             
             
            $olddata1 = DB::table('sale_transaction_detail')
            ->select('sale_code','sale_date','Ac_code','sales_order_no','hsn_code','unit_id','order_qty','order_rate',
            'disc_per','disc_amount','sale_cgst','camt','sale_sgst','samt','sale_igst','iamt','amount','total_amount')  
            ->where('sale_code',$request->sale_code)
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
             
            
            $combinedOldData = $olddata1;
              
            DB::table('sale_transaction_detail')->where('sale_code', $request->sale_code)->delete();
          
            $cnt=count($request->sales_order_no);
            
            if($cnt>0)
            {
                
                        $newDataDetail2=[];
                
                for($x=0;$x<$cnt;$x++) 
                { 
                
                        $data2=array(
                        'sr_no' =>$id,
                        'sale_code' =>$request->input('sale_code'),
                        'sale_date' => $request->input('sale_date'),
                        'Ac_code' => $request->input('Ac_code'),
                        'sales_order_no' => $request->sales_order_no[$x] ?? null,
                        'style_no_id' => $request->style_no_id[$x] ?? null,
                        'hsn_code' => $request->hsn_code[$x] ?? null,
                        'unit_id' => $request->unit_id[$x] ?? null,
                        'order_qty' => $request->order_qtys[$x] ?? 0,
                        'pack_order_qty' => $request->pack_order_qty[$x] ?? 0,
                        'order_rate' => $request->item_rates[$x] ?? 0,
                        'disc_per' => $request->disc_pers[$x] ?? 0,
                        'disc_amount' => $request->disc_amounts[$x] ?? 0,
                        'sale_cgst' => $request->sale_cgsts[$x] ?? 0,
                        'camt' => $request->camts[$x] ?? 0,
                        'sale_sgst' => $request->sale_sgsts[$x] ?? 0,
                        'samt' => $request->samts[$x] ?? 0,
                        'sale_igst' => $request->sale_igsts[$x] ?? 0,
                        'iamt' => $request->iamts[$x] ?? 0,
                        'amount' => $request->amounts[$x] ?? 0,
                        'total_amount' => $request->total_amounts[$x] ?? 0,
                        'buyer_po_no' => $request->buyer_po_no[$x]?? null,
                        'firm_id' => $request->firm_id);
                        
                        SaleTransactionDetailModel::insert($data2);
                        
                        $cartonData = explode(",",$carton_packing_nos);
                        //print_r($cartonData);exit;
                        foreach($cartonData as $row1)
                        { 
                            //DB::enableQueryLog();
                            DB::table('FGStockDataByTwo')
                            ->where('code', '=', $row1)
                            ->where('sales_order_no', '=', $request->sales_order_no[$x])
                            ->update([
                                'is_sale' => 0,
                                'invoice_no' => $request->sale_code,
                                'invoice_date' => $request->sale_date
                            ]);
                        }
                        foreach($cartonData as $row2)
                        { 
                            //dd(DB::getQueryLog());
                            DB::SELECT('INSERT INTO temp_sales_transaction(sale_code,sale_date,order_qty,cpki_code,sales_order_no)
                            select "'.$request->sale_code.'","'.$request->sale_date.'","'.$request->order_qtys[$x].'","'.$row2.'","'.$request->sales_order_no[$x].'"');   
                        }
                        
                     
                $newDataDetail2[]=['sale_code' =>$request->input('sale_code'),
                        'sale_date' => $request->input('sale_date'),
                        'Ac_code' => $request->input('Ac_code'),
                        'sales_order_no' => $request->sales_order_no[$x],
                        'hsn_code' => $request->hsn_code[$x],
                        'unit_id' => $request->unit_id[$x],
                        'order_qty' => $request->order_qtys[$x],
                        'order_rate' => $request->item_rates[$x],
                        'disc_per' => $request->disc_pers[$x],
                        'disc_amount' => $request->disc_amounts[$x],
                        'sale_cgst' => $request->sale_cgsts[$x],
                        'camt' => $request->camts[$x],
                        'sale_sgst' => $request->sale_sgsts[$x],
                        'samt' => $request->samts[$x],
                        'sale_igst' => $request->sale_igsts[$x],
                        'iamt' => $request->iamts[$x],
                        'amount' => $request->amounts[$x],
                        'total_amount' => $request->total_amounts[$x]];        
                        
                }
                
                
    
                   $combinedNewData = $newDataDetail2;       
               
                try {
                $logger->logIfChangedSaleTransaction(
                'sale_transaction_detail',
                $request->sale_code,
                $request->sales_order_no,
                $combinedOldData,
                $combinedNewData,
                'UPDATE',
                $request->sale_date,
                'sale_transaction_detail'
                );
                // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
                //   $newDataDetail
                // ]);
                } catch (\Exception $e) {
                Log::error('Logger failed for sale_transaction_detail.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'sale_code' => $request->sale_code,
                'data' => $combinedNewData
                ]);
                }     
            }
            
            DB::select("update sales_head_master set tr_no= tr_no + 1 where sales_head_id =".$request->input('sales_head_id'));  
        
            DB::commit();
            return redirect()->route('SaleTransaction.index')->with('message', 'Update Record Succesfully');

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
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($sr_no)
    { 
        //DB::enableQueryLog();
        
            DB::select("update carton_packing_inhouse_master set endflag=0 where cpki_code NOT IN (select carton_packing_nos FROM sale_transaction_master)");
        //dd(DB::getQueryLog());
            $saleData = DB::table('sale_transaction_master')->select('sale_code')->where('sr_no', $sr_no)->first();
            
            DB::table('FGStockDataByTwo')->where('invoice_no', '=', $saleData->sale_code)->update(['size_qty' => 0,'invoice_no' => NULL,'invoice_date' => NULL]);
                            
            DB::table('temp_sales_transaction')->where('sale_code', $saleData->sale_code)->delete();
            
            $master =SaleTransactionMasterModel::where('sr_no',$sr_no)->delete();      
            $detail =SaleTransactionDetailModel::where('sr_no',$sr_no)->delete();
            
            DB::select("update carton_packing_inhouse_master set endflag=0 where cpki_code NOT IN (select carton_packing_nos FROM sale_transaction_master)");    
            
            Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function GetPartyDetailsSale(Request $request)
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
    
    public function GetTradePartyDetailsSale(Request $request)
    {
        
            $trade_name= $request->input('trade_name'); 
            $PartyDetails = DB::select("select state_id from ledger_details where trade_name='".$trade_name."'");
            $state_id = isset($PartyDetails[0]->state_id) ? $PartyDetails[0]->state_id : 0;
            return response()->json(['state_id' => $state_id]); 
         
    }
    
    public function getSalesOrderData(Request $request)
    { 
            $itemlist=DB::table('item_master')->get();
            $unitlist=DB::table('unit_master')->get();
            
            $hsnlist = DB::table('hsn_master')->where('delflag','=', 0)->get();
            
            //DB::enableQueryLog();
            $cpki_code='';
            $caron_packing_nos=explode(',',$request->carton_packing_nos);
            foreach($caron_packing_nos as $cpki)
            {
                 $cpki_code=$cpki_code."'".$cpki."',";
            }
            $cpki_code=rtrim($cpki_code,",");




//   $data=DB::table('carton_packing_inhouse_size_detail')->select('tr_code as sales_order_no', 'order_rate','total_qty as order_qty' , 'unit_id')
//   ->whereIn('tr_code',$sales_order_nos)->get();
  
 // DB::enableQueryLog();
      $MasterdataList = DB::select("SELECT carton_packing_inhouse_size_detail.item_code, carton_packing_inhouse_size_detail.color_id, color_name, sales_order_no,buyer_purchse_order_master.unit_id,buyer_purchse_order_master.po_code,
      sum(size_qty_total) as size_qty_total from carton_packing_inhouse_size_detail
      inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=carton_packing_inhouse_size_detail.sales_order_no
      inner join color_master on 
      color_master.color_id=carton_packing_inhouse_size_detail.color_id where cpki_code in (".$cpki_code.")
      group by  carton_packing_inhouse_size_detail.sales_order_no");
  
   //dd(DB::getQueryLog());
 

   $html='';

$no=1;

     foreach ($MasterdataList as $value) 
     {
          
        $order_rate=0;
        $order_qty=$value->size_qty_total;
        $total_amount =   $order_rate * $order_qty;
if($request->tax_type_id==1)
{
   $sgst=2.5;
   $cgst=2.5;
   $igst=0;
    $Camt=($total_amount * (2.5/100));
    $Samt=($total_amount * (2.5/100));
    $Iamt=0;                 
    $TAmount=$total_amount + $Camt+ $Samt;
    $igst_per=0;
     
} 
elseif($request->tax_type_id==2)
{ 
     $sgst=0;
   $cgst=0;
   $igst=5;
    $Iamt=($total_amount * (5/100));
    $Camt=0;
    $Samt=0;
    $TAmount=$total_amount + $Iamt;

} 
   
   $styleNoList = DB::table('style_no_master')
            ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.style_no_id', '=', 'style_no_master.style_no_id')
            ->select('buyer_purchase_order_detail.style_no_id','style_no_master.style_no')
            ->where('delflag','=',0)
            ->where('tr_code','=',$value->sales_order_no)
            ->groupBy('buyer_purchase_order_detail.style_no_id')
            ->get();
                                                                            
   $html .='<tr id="bomdis">';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td><input type="text" name="sales_order_no[]" required  readOnly id="sales_order_no" style="width:150px;" value="'.$value->sales_order_no.'"/>  </td> 
 
<td><input type="text" name="buyer_po_no[]" required id="buyer_po_no" style="width:150px;" value="'.$value->po_code.'"/>  </td>';  

$html .='<td> <select name="style_no_id[]"  id="style_no_id" style="width:120px;" class="form-control" disabled>';

foreach($styleNoList as  $rowstyle)
{
    $html.='<option value="'.$rowstyle->style_no_id.'"';

    $rowstyle->style_no_id; 


    $html.='>'.$rowstyle->style_no.'</option>';
}
$html.='</select></td>';

$html .='<td> <select name="hsn_code[]"  id="hsn_code" style="width:120px;" class="form-control" required>
<option value="">--Select--</option>';

foreach($hsnlist as  $rowhsn)
{
    $html.='<option value="'.$rowhsn->hsn_code.'"';

    $rowhsn->hsn_code; 


    $html.='>'.$rowhsn->hsn_code.'</option>';
}
$html.='</select></td>';

$html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required onchange="CalPackQty(this);">
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td>';
$html.='
    <td>
    <input type="text" class="ITEMQTY"   name="order_qty[]" readOnly  value="'.$value->size_qty_total.'" id="order_qty" style="width:80px;" readonly/>
    <input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
    </td>
    <td>
        <input type="text" class="PCKITEMQTY"   name="pack_order_qty[]"  value="'.$value->size_qty_total.'"  style="width:80px;" readonly /> 
    </td>
    <td><input type="text"   name="order_rate[]"  value="'.$order_rate.'" class="RATE" step="any" id="order_rate" style="width:80px;"  required  onchange="CalculateRow(this);"   /></td>
    <td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
    <td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="sale_cgsts[]"   value="'.$cgst.'" class="sale_cgsts"  id="sale_cgst" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="sale_sgsts[]"   value="'.$sgst.'" class=""  id="sale_sgst" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="sale_igsts[]"   value="'.$igst.'" class=""  id="sale_igst" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="amounts[]" readOnly value="'.number_format((float)$total_amount, 2, '.', '').'" class="GROSS"  id="amount" style="width:80px;" readOnly/></td>
    <td><input type="text"   name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px;" readOnly/></td>
    <td> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>';
    $html .='</tr>';
    $no=$no+1;
}
return response()->json(['html' => $html]);
         
    }
    
  
  
  
public function CartonPackingList(Request $request)
{
    //   DB::enableQueryLog();
         
     $CartonPackingList = DB::select("select distinct carton_packing_inhouse_master.cpki_code, carton_packing_inhouse_size_detail.sales_order_no 
     from carton_packing_inhouse_size_detail
     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_size_detail.cpki_code
     where carton_packing_inhouse_master.Ac_code ='".$request->Ac_code."' and
     carton_packing_inhouse_master.endflag!=1 GROUP BY carton_packing_inhouse_master.cpki_code");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Carton Packing List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Carton Packing List--</option>';
        
        foreach ($CartonPackingList as $row)  
        
        {$html .= '<option value="'.$row->cpki_code.'">'.$row->cpki_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}  
  
  
  
  
  
  public function GetSalesOrderList(Request $request)
{
    //   DB::enableQueryLog();
         
     $SalesOrderList = DB::select("select tr_code as sales_order_no from buyer_purchse_order_master where Ac_code ='".$request->Ac_code."'");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Sales Order No--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Sales Order No--</option>';
        
        foreach ($SalesOrderList as $row)  
        
        {$html .= '<option value="'.$row->sales_order_no.'">'.$row->sales_order_no.'</option>';}
    }
      return response()->json(['html' => $html]);
}  
  
  public function GetSaleReport()
  {
      
       $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
      return view('GetSaleReport', compact('LedgerList'));
  }
     
     
     
     
    public function SaleFilterReport (Request $request)
    {
       
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
        $Ac_code = $request->Ac_code;
        $sale_code = $request->sale_code;
        $sales_head_id = $request->sales_head_id ??  [1, 2, 3, 5, 6, 8]; 

        // if($Ac_code!='')
        // {  
        //     $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        //     ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
        //      ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
        //       ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        //     ->where('sale_transaction_master.delflag','=', '0')
        //     ->where('sale_transaction_master.sales_head_id','!=', 10)
        //     ->where('sale_transaction_master.Ac_code',$Ac_code)->whereBetween('sale_date',array($fdate,$tdate))
        //      ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);
             
        //     $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),DB::raw('sum(Gst_amount) as TotalGst'),DB::raw('sum(Net_amount) as TotalNet'),DB::raw('sum(total_qty) as TotalQty'))
        //     ->where('sale_transaction_master.delflag','=', '0')
        //     ->where('sale_transaction_master.Ac_code',$Ac_code)->whereBetween('sale_date',array($fdate,$tdate))
        //     ->get();
        // }
        // elseif($Ac_code=='')
        // {
        //   // DB::enableQueryLog();
        //   $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        //     ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
        //     ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
        //     ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        //     ->where('sale_transaction_master.delflag','=', '0')
        //     ->where('sale_transaction_master.sales_head_id','!=', 10) 
        //     ->whereBetween('sale_transaction_master.sale_date',array($fdate,$tdate))
        //      ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);
        //       // dd(DB::getQueryLog());
        //     $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
        //     DB::raw('sum(Gst_amount) as TotalGst'),
        //     DB::raw('sum(Net_amount) as TotalNet'),
        //     DB::raw('sum(total_qty) as TotalQty'))
        //     ->where('sale_transaction_master.delflag','=', '0')
        //     ->where('sale_transaction_master.sales_head_id','!=', 10)
        //     ->whereBetween('sale_transaction_master.sale_date',array($fdate,$tdate))
        //     ->get();
        // }
        
        $filter = "";
        
        if (is_array($sales_head_id) && !empty($sales_head_id)) 
        {
            $salesHeadIdsStr = implode(',', $sales_head_id); 
            $filter .= " AND sale_transaction_master.sales_head_id IN (".$salesHeadIdsStr.")";
        }

        if($fromDate !="" && $toDate !="")
        {
            $filter .= " AND sale_transaction_master.sale_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
    
        if($Ac_code !="")
        {
            $filter .= " AND sale_transaction_master.Ac_code = '".$Ac_code."'";
        }
        
          
        if($sale_code !="")
        {
            $filter .= " AND sale_transaction_master.sale_code = '".$sale_code."'";
        }
      
        $SaleTransactionMasterList = DB::SELECT("SELECT sale_transaction_master.*,usermaster.username,lm1.ac_short_name as ac_name1,firm_master.firm_name,tax_type_master.tax_type_name,
                                        sales_head_master.sales_head_name
                                        FROM sale_transaction_master LEFT JOIN ledger_master as lm1 ON lm1.ac_code = sale_transaction_master.Ac_code
                                        LEFT JOIN usermaster ON usermaster.userId = sale_transaction_master.userId 
                                        LEFT JOIN tax_type_master ON tax_type_master.tax_type_id = sale_transaction_master.tax_type_id
                                        LEFT JOIN firm_master ON firm_master.firm_id = sale_transaction_master.firm_id
                                        LEFT JOIN sales_head_master ON sales_head_master.sales_head_id = sale_transaction_master.sales_head_id
                                        WHERE sale_transaction_master.delflag = 0 ".$filter);
        
        $SaleTotal = DB::SELECT("SELECT sum(Gross_amount) as TotalGross,sum(Gst_amount) as TotalGst,sum(Net_amount) as TotalNet,sum(total_qty) as TotalQty 
                    FROM sale_transaction_master WHERE sale_transaction_master.delflag=0 ".$filter);
        
        $DFilter = "";
        
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $invoiceList = DB::SELECT("SELECT sale_code FROM sale_transaction_master WHERE delflag=0");
        $salesHeadlist = DB::table('sales_head_master')
                     ->join('sales_head_auth', 'sales_head_auth.sales_head_id', '=', 'sales_head_master.sales_head_id')
                     ->where('sales_head_auth.username','=', Session::get('username'))
                     ->where('sales_head_master.delflag','=', '0')
                     ->get();
                         
        return view('SaleFilterReportPrint', compact('SaleTransactionMasterList','SaleTotal','DFilter','fromDate','toDate','Ac_code','sale_code','LedgerList','invoiceList','filter','salesHeadlist','sales_head_id'));
      
    }
     
      
    public function sampleGiftOutwardReport (Request $request)
    {
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
       
        $filter = "";
        
        if($fromDate !="" && $toDate !="")
        {
            $filter .= " AND sale_transaction_master.sale_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        }
        
        $SaleTransactionMasterList = DB::SELECT("SELECT sale_transaction_master.*,sales_head_master.sales_head_name 
                                        FROM sale_transaction_master 
                                        INNER JOIN sales_head_master ON sales_head_master.sales_head_id = sale_transaction_master.sales_head_id
                                        WHERE sale_transaction_master.delflag = 0 AND sale_transaction_master.sales_head_id IN(11,12) ".$filter);
        
        
        return view('sampleGiftOutwardReport', compact('SaleTransactionMasterList','fromDate','toDate'));
      
    }
    
    public function SaleFilterReportMD($DFilter)
    {
              
        if($DFilter == 'd')
        {
            $filterDate = " AND sale_transaction_master.sale_date = '".date('Y-m-d')."'";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND MONTH(sale_transaction_master.sale_date) = MONTH(CURRENT_DATE()) and YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE()) AND sale_transaction_master.sale_date !="'.date('Y-m-d').'"';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND sale_transaction_master.sale_date between (select fdate from financial_year_master 
                            where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)';
        }
        else
        {
            $filterDate = "";
        }
        
        $SaleTransactionMasterList = DB::select("select sale_transaction_master.*,usermaster.username,ledger_master.ac_name as ac_name1,
            firm_master.firm_name,tax_type_master.tax_type_name FROM sale_transaction_master
            LEFT JOIN usermaster ON usermaster.userId = sale_transaction_master.userId
            LEFT JOIN tax_type_master ON tax_type_master.tax_type_id = sale_transaction_master.tax_type_id
            LEFT JOIN firm_master ON firm_master.firm_id = sale_transaction_master.firm_id
            INNER JOIN ledger_master ON ledger_master.ac_code = sale_transaction_master.Ac_code
            WHERE sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag = 0 ".$filterDate);      
        
        $SaleTotal = DB::select("select sum(Gross_amount) as TotalGross,sum(Gst_amount) as TotalGst,sum(Net_amount) as TotalNet,sum(total_qty) as TotalQty FROM sale_transaction_master
        WHERE sale_transaction_master.delflag = 0 ".$filterDate);    
        
        return view('SaleFilterReportPrint', compact('SaleTransactionMasterList','SaleTotal','DFilter'));
      
    }
    
    public function PrintSaleTransaction($id)
    {
        
        //DB::enableQueryLog();
      $BuyerPurchaseOrderMasterList = DB::table('sale_transaction_master')
        ->select('sale_transaction_master.sr_no','sale_transaction_master.tax_type_id','sale_transaction_master.sale_code','sale_transaction_detail.samt','sale_transaction_detail.iamt',
            'sale_transaction_detail.sale_cgst','sale_transaction_detail.sale_sgst','sale_transaction_detail.sale_igst','sale_transaction_detail.camt',
            'sale_transaction_detail.sales_order_no','unit_master.unit_name','sale_transaction_detail.order_qty','sale_transaction_detail.order_rate',
            'sale_transaction_detail.hsn_code','buyer_purchse_order_master.style_description','main_style_master.mainstyle_name')
        ->join('sale_transaction_detail', 'sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code') 
        ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','sale_transaction_detail.sales_order_no')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('sale_transaction_master.sr_no','=', $id)
        ->groupby('sale_transaction_detail.sales_order_no')
        ->get(); 
        
         $BuyerDetail = DB::table('sale_transaction_master')->select('*','ledger_master.*') ->join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')->where('sale_transaction_master.sr_no','=', $id)->first(); 

      return view('PrintSaleTransaction', compact('BuyerPurchaseOrderMasterList','BuyerDetail'));
    }


    public function PrintSaleTransactionView($id)
    {
        
      $BuyerPurchaseOrderMasterList = DB::table('sale_transaction_master')
        ->select('sale_transaction_master.sr_no','sale_transaction_master.tax_type_id','sale_transaction_master.sale_code','sale_transaction_detail.samt','sale_transaction_detail.iamt',
            'sale_transaction_detail.sale_cgst','sale_transaction_detail.sale_sgst','sale_transaction_detail.sale_igst','sale_transaction_detail.camt',
            'sale_transaction_detail.sales_order_no','unit_master.unit_name','sale_transaction_detail.order_qty','sale_transaction_detail.order_rate',
            'sale_transaction_detail.hsn_code','buyer_purchse_order_master.style_description','main_style_master.mainstyle_name')
        ->join('sale_transaction_detail', 'sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code') 
        ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','sale_transaction_detail.sales_order_no')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('sale_transaction_master.sr_no','=', $id)
        ->groupby('sale_transaction_detail.sales_order_no')
        ->get(); 
        
         $BuyerDetail = DB::table('sale_transaction_master')->select('*','ledger_master.*') ->join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')->where('sale_transaction_master.sr_no','=', $id)->first(); 

      return view('PrintSaleTransactionView', compact('BuyerPurchaseOrderMasterList','BuyerDetail'));
    }

    
     
    public function DCPrintSaleTransaction($id)
    {
        
        //DB::enableQueryLog();
      $BuyerPurchaseOrderMasterList = DB::table('sale_transaction_master')
        ->select('sale_transaction_master.tax_type_id','sale_transaction_master.sale_code','sale_transaction_detail.samt','sale_transaction_detail.iamt',
            'sale_transaction_detail.sale_cgst','sale_transaction_detail.sale_sgst','sale_transaction_detail.sale_igst','sale_transaction_detail.camt',
            'sale_transaction_detail.sales_order_no','unit_master.unit_name','sale_transaction_detail.order_qty','sale_transaction_detail.order_rate',
            'sale_transaction_detail.hsn_code','buyer_purchse_order_master.style_description','main_style_master.mainstyle_name')
        ->join('sale_transaction_detail', 'sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code') 
        ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','sale_transaction_detail.sales_order_no')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('sale_transaction_master.sr_no','=', $id)
        ->groupby('sale_transaction_detail.sales_order_no')
        ->get(); 
        
         $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
         $BuyerDetail = DB::table('sale_transaction_master')->select('*','ledger_master.*','sale_transaction_master.address as sent_address') ->join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')->where('sale_transaction_master.sr_no','=', $id)->first(); 

      return view('DCPrintSaleTransaction', compact('BuyerPurchaseOrderMasterList','BuyerDetail', 'FirmDetail'));
    }

     public function DCPrintSaleTransactionView($id)
    {
        
        //DB::enableQueryLog();
      $BuyerPurchaseOrderMasterList = DB::table('sale_transaction_master')
        ->select('sale_transaction_master.tax_type_id','sale_transaction_master.sale_code','sale_transaction_detail.samt','sale_transaction_detail.iamt',
            'sale_transaction_detail.sale_cgst','sale_transaction_detail.sale_sgst','sale_transaction_detail.sale_igst','sale_transaction_detail.camt',
            'sale_transaction_detail.sales_order_no','unit_master.unit_name','sale_transaction_detail.order_qty','sale_transaction_detail.order_rate',
            'sale_transaction_detail.hsn_code','buyer_purchse_order_master.style_description','main_style_master.mainstyle_name')
        ->join('sale_transaction_detail', 'sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code') 
        ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','sale_transaction_detail.sales_order_no')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('sale_transaction_master.sr_no','=', $id)
        ->groupby('sale_transaction_detail.sales_order_no')
        ->get(); 
        
         $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
         $BuyerDetail = DB::table('sale_transaction_master')->select('*','ledger_master.*','sale_transaction_master.address as sent_address') ->join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')->where('sale_transaction_master.sr_no','=', $id)->first(); 

      return view('DCPrintSaleTransactionView', compact('BuyerPurchaseOrderMasterList','BuyerDetail', 'FirmDetail'));
    }
      
    public function MonthlyShipmentTargetMaster(Request $request)
    {
        $fromDate =  date($request->monthDate."-01") ? date($request->monthDate."-01") : date("Y-m-01");
        $toDate =  date($request->monthDate."-t") ? date($request->monthDate."-t") : date("Y-m-t");
        $monthDate = $request->monthDate;
        
        $salesOrderList = BuyerPurchaseOrderMasterModel::
                select('buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','order_type_master.order_type','buyer_purchse_order_master.sam','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
                ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
                , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
                )
                   
                ->leftJoin('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                ->leftJoin('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
                ->where('buyer_purchse_order_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.og_id','!=', '4')
                ->Where('buyer_purchse_order_master.job_status_id','=', '2')
                ->whereBetween('buyer_purchse_order_master.order_close_date', [$fromDate, $toDate])
                ->orWhere('buyer_purchse_order_master.order_received_date', '<=', $toDate)
                ->Where('buyer_purchse_order_master.delflag','=', '0')
                ->Where('buyer_purchse_order_master.og_id','!=', '4')
                ->Where('buyer_purchse_order_master.job_status_id','=', '1')
                ->orderBy('brand_master.brand_name', 'ASC')
                ->get();
       // $salesOrderList = DB::SELECT("SELECT ")
		return view('MonthlyShipmentTargetMaster',compact('salesOrderList','fromDate','toDate','monthDate'));     
    } 
     
    public function GetBuyerData(Request $request)
    {
        //DB::enableQueryLog();
        $buyerData = DB::table('buyer_purchse_order_master')->select('ledger_master.ac_code','ledger_master.ac_name')
                 ->join('ledger_master', 'ledger_master.ac_code', '=', 'buyer_purchse_order_master.ac_code') 
                 ->where('tr_code','=',$request->sales_order_no)
                 ->DISTINCT()->get();
                 
        $html = '';
        $html = '<option value="">--Sales Order No--</option>';
        
        foreach ($buyerData as $row)  
        {
                $html .= '<option value="'.$row->ac_code.'">'.$row->ac_name.'</option>';
            
        }
          return response()->json(['buyer' => $html]);
    }
    
    public function GetStyleCategoryData(Request $request)
    {
        //DB::enableQueryLog();
        $styleData = DB::table('buyer_purchse_order_master')->select('main_style_master.mainstyle_id','main_style_master.mainstyle_name')
                 ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id') 
                 ->where('tr_code','=',$request->sales_order_no)
                 ->where('Ac_code','=',$request->ac_code)
                 ->DISTINCT()->get();
                 
        $orderData = DB::table('sale_transaction_detail')->select('sale_transaction_detail.order_rate')
                 ->where('sales_order_no','=',$request->sales_order_no)
                 ->where('Ac_code','=',$request->ac_code)
                 ->first();     
        if($orderData != "")
        {
            $orderRate = $orderData->order_rate;
        }
        else
        {
            $orderRate = "";
        }
              
        //dd(DB::getQueryLog());   
        $html = '';
        $html = '<option value="">--Category--</option>';
        
        foreach ($styleData as $row)  
        {
                $html .= '<option value="'.$row->mainstyle_id.'">'.$row->mainstyle_name.'</option>';
            
        }
          return response()->json(['mainStyle' => $html,'order_rate' => $orderRate]);
    }
    
    public function monthlyShipmentTargetStore(Request $request)
    {
        //DB::enableQueryLog();
        DB::table('monthly_shipment_target_detail')->where('monthDate', '=',  date("Y-m", strtotime( $request->monthDate."-01")))->delete();
        //dd(DB::getQueryLog());
        for($x=0; $x<count($request->week1); $x++) 
        {
            $data2=array(
                'sales_order_no'=>$request->sales_order_no[$x],
                'buyer_code'=>$request->buyer_code[$x],
                'mainstyle_id'=>$request->mainstyle_id[$x],
                'po_code'=>$request->po_code[$x],
                'brand_id'=>$request->brand_id[$x],
                'fg_id'=>$request->fg_id[$x],
                'sam'=>$request->sam[$x],
                'order_value'=>$request->order_value[$x],
                'fromDate'=>$request->fromDate[$x],
                'toDate'=>$request->toDate[$x],
                'week1'=>$request->week1[$x],
                'week2'=>$request->week2[$x],
                'week3'=>$request->week3[$x],
                'week4'=>$request->week4[$x],
                'targetQty'=>$request->targetQty[$x],
                'orderRate'=>$request->order_rate[$x],
                'value'=>$request->value[$x],
                'userId'=>$request->userId,
                'monthDate'=>$request->monthDate,
                'updated_at'=>date("Y-m-d")
           
            );
            MonthlyShipmentTargetDetailModel::insert($data2);
               
        }
        return '<script>window.location.href = "MonthlyShipmentTargetMaster?monthDate='.$request->monthDate.'";</script>';
    }
    
    public function rptMonthlyShipmentTarget(Request $request)
    {  
        $fromDate =  date($request->monthDate."-01") ? date($request->monthDate."-01") : date("Y-m-01");
        $toDate =  date($request->monthDate."-t") ? date($request->monthDate."-t") : date("Y-m-t");
        $monthDate = $request->monthDate;
         
                 
       $salesOrderList = BuyerPurchaseOrderMasterModel::
                select('buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','order_type_master.order_type','buyer_purchse_order_master.sam','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
                ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
                , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
                )
                   
                ->leftJoin('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                ->leftJoin('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
                ->where('buyer_purchse_order_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.og_id','!=', '4')
                ->Where('buyer_purchse_order_master.job_status_id','=', '2')
                ->whereBetween('buyer_purchse_order_master.order_close_date', [$fromDate, $toDate])
                ->orWhere('buyer_purchse_order_master.order_received_date', '<=', $toDate)
                ->Where('buyer_purchse_order_master.delflag','=', '0')
                ->Where('buyer_purchse_order_master.og_id','!=', '4')
                ->Where('buyer_purchse_order_master.job_status_id','=', '1')
                ->orderBy('brand_master.brand_name', 'ASC')
                ->get();
            
        return view('rptMonthlyShipmentTarget',compact('salesOrderList','fromDate','toDate','monthDate'));
    }
    
    public function GetSalesInvoiceCode(Request $request)
    {
        //DB::enableQueryLog();
        $salesHeadData = DB::table('sales_head_master')->select('*') 
                 ->where('sales_head_id','=',$request->sales_head_id)
                 ->first();
                 
         $counterData = DB::table('counter_number')->select('tr_no') 
                 ->where('type','=','SaleTransaction1')
                 ->first();
                 
        if($request->sales_head_id == 1 || $request->sales_head_id == 2 || $request->sales_head_id == 3)
        {
             $series = $salesHeadData->tr_no + 1;       
        }
        else if($request->sales_head_id == 5 || $request->sales_head_id == 6)
        {
             $series = str_pad($salesHeadData->tr_no + 1, 9, '0', STR_PAD_LEFT);    
        }
        else
        {
            $series = $salesHeadData->tr_no + 1;    
        }
       
        
        $invoice_series = $salesHeadData->invoice_series."".$series;
        return $invoice_series;
    }
    
    
    public function checkInvoice(Request $request)
    {
        //DB::enableQueryLog();
        $salesData = DB::table('sale_transaction_master')->select('*') 
                 ->where('sale_code','=',$request->sale_code)
                 ->get();
        
        return count($salesData);
    }
    
    
    public function GetBuyerWiseFOBSalesReport(Request $request)
    {
         
            $fdate= $request->BuyerFOBSalesFromDate;
            $tdate= $request->BuyerFOBSalesToDate; 
            
           $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
                                            ->join('sale_transaction_detail','sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code')
                                            ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                                            ->where('sale_transaction_master.delflag','=', '0')
                                            ->where('sale_transaction_master.sales_head_id','!=', 10)
                                            ->where('buyer_purchse_order_master.order_type','=', 1)
                                            ->whereBetween('sale_transaction_master.sale_date',array($fdate,$tdate))
                                            ->groupBy('sale_transaction_detail.Ac_code')
                                            ->orderBy('lm1.ac_short_name','ASC')
                                            ->get(['sale_transaction_master.*',DB::raw('sum(sale_transaction_detail.order_qty) as total_qty'),DB::raw('sum(sale_transaction_detail.amount) as Gross_amount'),'lm1.ac_short_name as ac_name1']);
                     
             $html = '';
             $html1="";
             $totalLPcs = 0;
             $totalRsCr = 0;
             $totalLMin = 0;
             $totalLCMOHP = 0; 
             $totalFOB = 0;
             $total_gross =0;
             $total_qty = 0;
             $total_value = 0;
             $cmohp_value1 = 0;
             $totalCMOHPValue = 0;
             
             foreach($SaleTransactionMasterList as $row)    
             {
                   $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                        AND buyer_purchse_order_master.order_type=1 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 GROUP BY sale_transaction_detail.Ac_code");   
                   
                            $costingData = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                            INNER JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                            INNER JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                            INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                            WHERE  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                            AND buyer_purchse_order_master.order_type=1 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 AND og_id !=4");

                                            
                            $cmohp = 0;
                            $cmohp_value = 0;
                            $cmohpmain = 0;
                            foreach($costingData as $costing)
                            {
                                $total_cost_value = isset($costing->total_cost_value) ? $costing->total_cost_value : 0;
                                $other_value = isset($costing->other_value) ? $costing->other_value : 0;
                                $order_rate = isset($costing->order_rate) ? $costing->order_rate : 0;
                                $production_value = isset($costing->production_value) ? $costing->production_value : 0;
                                
                                $profit_value=0.00;
                                $profit_value = ($order_rate - $total_cost_value);
                              
                                $cmohp1 = $production_value + $profit_value + $other_value;
                                $cmohp2 = $costing->sam;
                                if($cmohp1 && $cmohp2)
                                {
                                    $cmohp = $cmohp1/$cmohp2;
                                }
                                else
                                {
                                    $cmohp = 0;
                                }
                           
                              $cmohpmain = $cmohp;
                           
                            $cmohp_value += $costing->order_qty*$cmohpmain;
        
                            }
                            
                            if($cmohp_value > 0 && $row->total_qty > 0)
                            { 
                                $cmohp_per_min = $cmohp_value/$row->total_qty;
                            }
                            else
                            {
                                $cmohp_per_min = 0;
                            }
                    $cmohp_value1 +=$cmohp_value;
                    $min = isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0; 
                    
                    if($row->Gross_amount > 0 && $row->total_qty > 0)
                    { 
                        $val_production = ($row->Gross_amount/$row->total_qty);
                    }
                    else
                    {
                        $val_production = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                    
                    
                $html .='<tr>
                            <td  nowrap>'.$row->ac_name1.'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->total_qty/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $val_production).'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->Gross_amount/10000000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $min/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $cmohp_per_min).'</td> 
                         </tr>';
                         
                         
                $totalLPcs += round($row->total_qty/100000,2);
                $totalRsCr += round($row->Gross_amount/10000000,2);
                $totalLMin += round(($min/100000),2);
                $totalLCMOHP += round($cmohp_per_min,2);
                $total_gross += $row->Gross_amount;
                $total_qty += $row->total_qty;
                $total_value += $cmohp_value/$row->total_qty;
               
        } 
        
        if($total_qty > 0 && $total_gross > 0)
        {
             $totalFOB +=  round($total_gross/$total_qty,2);
        }
        else
        {
            $totalFOB = 0;
        }
        
        
        if($total_qty > 0 && $cmohp_value1 > 0)
        {
             $totalCMOHPValue +=  round($cmohp_value1/$total_qty,2);
        }
        else
        {
            $totalCMOHPValue = 0;
        }
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalFOB).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLMin).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalCMOHPValue).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
    public function GetBuyerWiseJobWorkSalesReport(Request $request)
    {
         
            $fdate= $request->BuyerJobWorkSalesFromDate;
            $tdate= $request->BuyerJobWorkSalesToDate; 
            
            $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
                                            ->join('sale_transaction_detail','sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code')
                                            ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                                            ->where('sale_transaction_master.delflag','=', '0')
                                            ->where('sale_transaction_master.sales_head_id','!=', 10)
                                            ->where('buyer_purchse_order_master.order_type','=', 3)
                                            ->whereBetween('sale_transaction_master.sale_date',array($fdate,$tdate))
                                            ->groupBy('sale_transaction_master.Ac_code')
                                            ->orderBy('lm1.ac_short_name','ASC')
                                            ->get(['sale_transaction_master.*',DB::raw('sum(DISTINCT sale_transaction_master.total_qty) as total_qty'),DB::raw('sum(DISTINCT Gross_amount) as Gross_amount'),'lm1.ac_short_name as ac_name1']);
                     
             $html = '';
             $html1="";
             $totalLPcs = 0;
             $totalRsCr = 0;
             $totalLMin = 0;
             $totalLCMOHP = 0; 
             $totalFOB = 0;
             $total_gross =0;
             $total_qty = 0;
             $total_value = 0;
             $cmohp_value1 = 0;
             $totalCMOHPValue = 0;
             
             foreach($SaleTransactionMasterList as $row)    
             {
                   $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                        AND buyer_purchse_order_master.order_type=3 AND buyer_purchse_order_master.og_id !=4 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 GROUP BY sale_transaction_detail.Ac_code");   
                   
                            $costingData = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                            INNER JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                            INNER JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                            INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                            WHERE  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                            AND buyer_purchse_order_master.order_type=3 AND buyer_purchse_order_master.og_id !=4 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 AND og_id !=4");

                                            
                            $cmohp = 0;
                            $cmohp_value = 0;
                            $cmohpmain = 0;
                            foreach($costingData as $costing)
                            {
                                $total_cost_value = isset($costing->total_cost_value) ? $costing->total_cost_value : 0;
                                $other_value = isset($costing->other_value) ? $costing->other_value : 0;
                                $order_rate = isset($costing->order_rate) ? $costing->order_rate : 0;
                                $production_value = isset($costing->production_value) ? $costing->production_value : 0;
                                
                                $profit_value=0.00;
                                $profit_value = ($order_rate - $total_cost_value);
                              
                                $cmohp1 = $production_value + $profit_value + $other_value;
                                $cmohp2 = $costing->sam;
                                if($cmohp1 && $cmohp2)
                                {
                                    $cmohp = $cmohp1/$cmohp2;
                                }
                                else
                                {
                                    $cmohp = 0;
                                }
                           
                              $cmohpmain = $cmohp;
                           
                            $cmohp_value += $costing->order_qty*$cmohpmain;
        
                            }
                            
                            if($cmohp_value > 0 && $row->total_qty > 0)
                            { 
                                $cmohp_per_min = $cmohp_value/$row->total_qty;
                            }
                            else
                            {
                                $cmohp_per_min = 0;
                            }
                    $cmohp_value1 +=$cmohp_value;
                    $min = isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0; 
                    
                    if($row->Gross_amount > 0 && $row->total_qty > 0)
                    { 
                        $val_production = ($row->Gross_amount/$row->total_qty);
                    }
                    else
                    {
                        $val_production = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                    
                    
                $html .='<tr>
                            <td  nowrap>'.$row->ac_name1.'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->total_qty/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $val_production).'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->Gross_amount/10000000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $min/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $cmohp_per_min).'</td> 
                         </tr>';
                         
                         
                $totalLPcs += round($row->total_qty/100000,2);
                $totalRsCr += round($row->Gross_amount/10000000,2);
                $totalLMin += round(($min/100000),2);
                $totalLCMOHP += round($cmohp_per_min,2);
                $total_gross += $row->Gross_amount;
                $total_qty += $row->total_qty;
                $total_value += $cmohp_value/$row->total_qty;
               
        } 
        
        if($total_qty > 0 && $total_gross > 0)
        {
             $totalFOB +=  round($total_gross/$total_qty,2);
        }
        else
        {
            $totalFOB = 0;
        }
        
        
        if($total_qty > 0 && $cmohp_value1 > 0)
        {
             $totalCMOHPValue +=  round($cmohp_value1/$total_qty,2);
        }
        else
        {
            $totalCMOHPValue = 0;
        }
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalFOB).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLMin).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalCMOHPValue).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
    public function GetBuyerWiseStockSalesReport(Request $request)
    {
         
         
            $fdate= $request->BuyerStockSalesFromDate;
            $tdate= $request->BuyerStockSalesToDate; 
            //DB::enableQueryLog();
            $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code') 
                                            ->join('sale_transaction_detail','sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code')
                                            ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                                            ->where('sale_transaction_master.delflag','=', '0')
                                            ->where('buyer_purchse_order_master.delflag','=', '0')
                                            ->where('buyer_purchse_order_master.order_type','=', 2) 
                                            ->where('buyer_purchse_order_master.og_id','!=', 4)
                                            ->where('sale_transaction_master.sales_head_id','!=', 10)  
                                            ->whereBetween('sale_transaction_master.sale_date',array($fdate,$tdate))
                                            ->groupBy('sale_transaction_master.Ac_code')
                                            ->orderBy('lm1.ac_short_name','ASC') 
                                            ->get(['sale_transaction_master.*',DB::raw('sum(DISTINCT sale_transaction_master.total_qty) as total_qty'),DB::raw('sum(DISTINCT Gross_amount) as Gross_amount'),'lm1.ac_short_name as ac_name1']);
              // dd(DB::getQueryLog());      
             $html = '';
             $html1="";
             $totalLPcs = 0;
             $totalRsCr = 0;
             $totalLMin = 0;
             $totalLCMOHP = 0; 
             $totalFOB = 0;
             $total_gross =0;
             $total_qty = 0;
             $total_value = 0;
             $cmohp_value1 = 0;
             $totalCMOHPValue = 0;
             
             foreach($SaleTransactionMasterList as $row)    
             {
                   $MinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code 
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no 
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_detail.Ac_code='".$row->Ac_code."'  AND og_id !=4
                        AND buyer_purchse_order_master.order_type=2 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 GROUP BY sale_transaction_detail.Ac_code");   
                   
                            $costingData = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                            INNER JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                            INNER JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                            INNER JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                            WHERE sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                            AND buyer_purchse_order_master.order_type=2 AND sale_transaction_master.sales_head_id != 10 AND sale_transaction_master.delflag=0 AND og_id !=4");

                                            
                            $cmohp = 0;
                            $cmohp_value = 0;
                            $cmohpmain = 0;
                            foreach($costingData as $costing)
                            {
                                $total_cost_value = isset($costing->total_cost_value) ? $costing->total_cost_value : 0;
                                $other_value = isset($costing->other_value) ? $costing->other_value : 0;
                                $order_rate = isset($costing->order_rate) ? $costing->order_rate : 0;
                                $production_value = isset($costing->production_value) ? $costing->production_value : 0;
                                
                                $profit_value=0.00;
                                $profit_value = ($order_rate - $total_cost_value);
                              
                                $cmohp1 = $production_value + $profit_value + $other_value;
                                $cmohp2 = $costing->sam;
                                if($cmohp1 && $cmohp2)
                                {
                                    $cmohp = $cmohp1/$cmohp2;
                                }
                                else
                                {
                                    $cmohp = 0;
                                }
                           
                              $cmohpmain = $cmohp;
                           
                            $cmohp_value += $costing->order_qty*$cmohpmain;
        
                            }
                            
                            if($cmohp_value > 0 && $row->total_qty > 0)
                            { 
                                $cmohp_per_min = $cmohp_value/$row->total_qty;
                            }
                            else
                            {
                                $cmohp_per_min = 0;
                            }
                    $cmohp_value1 +=$cmohp_value;
                    $min = isset($MinData[0]->total_min) ? $MinData[0]->total_min : 0; 
                    
                    if($row->Gross_amount > 0 && $row->total_qty > 0)
                    { 
                        $val_production = ($row->Gross_amount/$row->total_qty);
                    }
                    else
                    {
                        $val_production = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                    
                    
                $html .='<tr>
                            <td  nowrap>'.$row->ac_name1.'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->total_qty/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $val_production).'</td>
                            <td class="text-right">'.sprintf("%.2f", $row->Gross_amount/10000000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $min/100000).'</td>
                            <td class="text-right">'.sprintf("%.2f", $cmohp_per_min).'</td> 
                         </tr>';
                         
                         
                $totalLPcs += round($row->total_qty/100000,2);
                $totalRsCr += round($row->Gross_amount/10000000,2);
                $totalLMin += round(($min/100000),2);
                $totalLCMOHP += round($cmohp_per_min,2);
                $total_gross += $row->Gross_amount;
                $total_qty += $row->total_qty;
                $total_value += $cmohp_value/$row->total_qty;
               
        } 
        
        if($total_qty > 0 && $total_gross > 0)
        {
             $totalFOB +=  round($total_gross/$total_qty,2);
        }
        else
        {
            $totalFOB = 0;
        }
        
        
        if($total_qty > 0 && $cmohp_value1 > 0)
        {
             $totalCMOHPValue +=  round($cmohp_value1/$total_qty,2);
        }
        else
        {
            $totalCMOHPValue = 0;
        }
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalFOB).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLMin).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalCMOHPValue).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
     
    public function SalesTransactionPrint(Request $request)
    {
        $FirmDetail =  DB::table('firm_master')->first();
        $SaleTransactionMasterList = DB::select("select sale_transaction_master.*,usermaster.username,ledger_master.ac_name as ac_name1,
            firm_master.firm_name,tax_type_master.tax_type_name FROM sale_transaction_master
            LEFT JOIN usermaster ON usermaster.userId = sale_transaction_master.userId
            LEFT JOIN tax_type_master ON tax_type_master.tax_type_id = sale_transaction_master.tax_type_id
            LEFT JOIN firm_master ON firm_master.firm_id = sale_transaction_master.firm_id
            INNER JOIN ledger_master ON ledger_master.ac_code = sale_transaction_master.Ac_code
            WHERE 1");      
        
        return view('SalesTransactionPrint', compact('SaleTransactionMasterList','FirmDetail'));
    }
    
    public function GetKGDPLSales(Request $request)
    { 
        
        $html = '';
        $headTotalQty = 0; 
        $headTotalMin = 0; 
        $headTotalAmount = 0; 
        $startMonth = 4;  
 
        $numberOfMonths = 12; 
        
        $financialData = DB::table('financial_year_master')->select('fin_year_name')->where('fin_year_id','=',$request->fin_year_id)->first();
         
        $fin_year_id = explode("-",$financialData->fin_year_name);
        
        $currentYear = $fin_year_id[0];  
        $currentMonth = date("n",strtotime($fin_year_id[0]));  
    
        
        $financialYearMonths = array();
        for ($i = 0; $i < $numberOfMonths; $i++) 
        {
            $month = ($startMonth + $i - 1) % 12 + 1;
            $year = $fin_year_id[0] - (($startMonth > $currentMonth) ? 1 : 0);
            if($month < 10)
            {
                $month = '0'.$month;
            }
            else
            {
                $month = $month;
            }
            
            if($i > 8)
            { 
            	 $financialYearMonths[] = $fin_year_id[1]."-".$month;
            }
            else
            {
            	$financialYearMonths[] = $fin_year_id[0]."-".$month;
            }  
          
        }
        
         $total_count = 0;
         foreach($financialYearMonths as $months)
         {
             
             $fromDate = date('Y-m-01',strtotime($months));
             $toDate =  date('Y-m-t',strtotime($months));
             //DB::enableQueryLog();
             $SaleTransactionData = DB::SELECT("SELECT sum(amount) as TotalGross,sum(order_qty) as totalQty,sum(order_qty * buyer_purchse_order_master.sam) as totalMin 
                    FROM sale_transaction_master 
                    INNER JOIN sale_transaction_detail ON sale_transaction_detail.sale_code = sale_transaction_master.sale_code
                    INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                    WHERE sale_transaction_master.delflag=0 AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8) AND sale_transaction_detail.sale_date BETWEEN '".$fromDate."' AND '".$toDate."'");
             //dd(DB::getQueryLog());
             
             $totalQty = isset($SaleTransactionData[0]->totalQty) ? $SaleTransactionData[0]->totalQty : 0;
             $totalMin = isset($SaleTransactionData[0]->totalMin) ? $SaleTransactionData[0]->totalMin : 0;
             $TotalGross = isset($SaleTransactionData[0]->TotalGross) ? $SaleTransactionData[0]->TotalGross : 0;
             if($totalQty > 0)
             {
                 $TQty = sprintf("%.2f", $totalQty/100000);
                 $TMin = sprintf("%.2f", $totalMin/100000);
                 $TGross = sprintf("%.2f", $TotalGross/10000000);
             }
             else
             {
                 $TQty = "-";
                 $TMin = "-";
                 $TGross = "-";
             }
             $html .='<tr>
                        <td  nowrap class="text-center" style="border-right:3px solid black;">'.(date('M-Y', strtotime($months))).'</td>
                        <td class="text-center" style="border-right:3px solid black;">'.$TQty.'</td>
                        <td class="text-center" style="border-right:3px solid black;">'.$TMin.'</td>
                        <td class="text-center" style="border-right:3px solid black;">'.$TGross.'</td> 
                     </tr>';
            if($totalQty > 0)
            {
                $total_count++;
            }
            $headTotalQty += sprintf("%.2f", $totalQty/100000);
            $headTotalMin += sprintf("%.2f", $totalMin/100000);
            $headTotalAmount += sprintf("%.2f", $TotalGross/10000000);      
         } 
         
         
         $html .='<tr>
                    <th  nowrap class="text-center" style="border-right:3px solid black;background: cyan;">Total</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$headTotalQty.'</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$headTotalMin.'</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$headTotalAmount.'</th> 
                 </tr>';
        
         if($headTotalQty > 0 && $total_count > 0)
         { 
            $htq = sprintf("%.2f", (($headTotalQty)/$total_count));
         }
         else
         {
            $htq = 0;
         }
         
         if($headTotalMin > 0 && $total_count > 0)
         { 
            $htm = sprintf("%.2f", (($headTotalMin)/$total_count));
         }
         else
         {
            $htm = 0;
         }
         
         if($headTotalAmount > 0 && $total_count > 0)
         { 
            $hta = sprintf("%.2f", (($headTotalAmount)/$total_count));
         }
         else
         {
            $hta = 0;
         }
         
         $html .='<tr>
                    <th  nowrap class="text-center" style="border-right:3px solid black;background: cyan;">Average</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$htq.'</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$htm.'</th>
                    <th class="text-center" style="border-right:3px solid black;background: cyan;">'.$hta.'</th> 
                 </tr>';
        return response()->json(['html' => $html]);
   
    }
    
    public function GetMontlyBudgetSalesReport(Request $request)
    {
         
            $fdate= $request->fromDate;
            $tdate= $request->toDate; 
            
            // $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
            //                                 ->where('sale_transaction_master.delflag','=', '0')
            //                                 ->where('sale_transaction_master.sales_head_id','!=', 10)
            //                                 ->whereBetween('sale_date',array($fdate,$tdate))
            //                                 ->groupBy('sale_transaction_master.Ac_code')
            //                                 ->orderBy('lm1.ac_short_name','ASC') 
            //                                 ->get(['sale_transaction_master.*',DB::raw('sum(total_qty) as total_qty'),DB::raw('sum(Gross_amount) as Gross_amount'),'lm1.ac_short_name as ac_name1']);
           
           $SaleTransactionMasterList = DB::select("select ac_short_name as ac_name1,buyer_purchse_order_master.orderCategoryId,sale_transaction_master.Ac_code,sum((sale_transaction_detail.order_qty * buyer_purchse_order_master.sam)) as total_min, sum(sale_transaction_detail.order_qty) as total_qty,sum(sale_transaction_detail.amount) as Gross_amount, order_group_name,OrderCategoryShortName
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId 
                        LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                        LEFT JOIN ledger_master ON ledger_master.ac_code = sale_transaction_master.Ac_code
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8)
                        AND sale_transaction_master.delflag=0 AND buyer_purchse_order_master.og_id !=4 AND buyer_purchse_order_master.order_type !=2 GROUP BY  buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.orderCategoryId ORDER BY ac_short_name ASC");   
                   
             $srno = 1;        
             $html = '<table id="monthly_budget3" class="table  dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr. No.</th>
                                        <th>Buyer Name</th> 
                                        <th>Order Category</th>
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">FOB</th>
                                        <th class="text-center">Rs. Cr.</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">CMOHP</th> 
                                        <th class="text-center">% (LM)</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="monthlyBudgetTbodySales1">';
             $html1="";
             $total_LMin = 0;
             $totalLPcs = 0;
             $totalRsCr = 0;
             $totalLMin = 0;
             $totalLCMOHP = 0; 
             $totalFOB = 0;
             $total_gross =0;
             $total_qty = 0;
             $total_value = 0;
             $cmohp_value1 = 0;
             $totalCMOHPValue = 0;
             
             foreach($SaleTransactionMasterList as $row)    
             {
                    $costingData = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                    LEFT JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                    LEFT JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    WHERE buyer_purchse_order_master.orderCategoryId = ".$row->orderCategoryId." AND sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                    AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8) AND sale_transaction_master.delflag=0 AND og_id !=4 AND buyer_purchse_order_master.order_type !=2 ");
                                     
                    $order_group_name = isset($row->order_group_name) ? $row->order_group_name : "";                  
       
                    $cmohp = 0;
                    $cmohp_value = 0;
                    $cmohpmain = 0;
                    foreach($costingData as $costing)
                    {
                        $total_cost_value = isset($costing->total_cost_value) ? $costing->total_cost_value : 0;
                        $other_value = isset($costing->other_value) ? $costing->other_value : 0;
                        $order_rate = isset($costing->order_rate) ? $costing->order_rate : 0;
                        $production_value = isset($costing->production_value) ? $costing->production_value : 0;
                        
                        $profit_value=0.00;
                        $profit_value = ($order_rate - $total_cost_value);
                      
                        $cmohp1 = $production_value + $profit_value + $other_value;
                        $cmohp2 = $costing->sam;
                        if($cmohp1 && $cmohp2)
                        {
                            $cmohp = $cmohp1/$cmohp2;
                        }
                        else
                        {
                            $cmohp = 0;
                        }
                   
                      $cmohpmain = $cmohp;
                   
                    $cmohp_value += $costing->order_qty*$cmohpmain;

                    }
                    
                    if($cmohp_value > 0 && $row->total_qty > 0)
                    { 
                        $cmohp_per_min = $cmohp_value/$row->total_qty;
                    }
                    else
                    {
                        $cmohp_per_min = 0;
                    }
                    $cmohp_value1 +=$cmohp_value;
                    $min = isset($row->total_min) ? $row->total_min : 0; 
                    
                    if($row->Gross_amount > 0 && $row->total_qty > 0)
                    { 
                        $val_production = ($row->Gross_amount/$row->total_qty);
                    }
                    else
                    {
                        $val_production = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                
                 $MinData1 = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8)
                        AND sale_transaction_master.delflag=0");   
                        
                $totalMinQty = isset($MinData1[0]->total_min) ? $MinData1[0]->total_min : 0;
                if($min > 0 && $totalMinQty > 0)
                { 
                    $LMMin = (sprintf("%.2f", $min/100000))/sprintf("%.2f", $totalMinQty/100000);
                }
                else
                {
                    $LMMin = 0;
                }
                
                $first_character = substr($order_group_name, 0, 1);
                      
                $html .='<tr>
                            <td  nowrap>'.($srno++).'</td>
                            <td  nowrap>'.$row->ac_name1.'</td>
                            <td  class="text-center">'.$first_character."-".$row->OrderCategoryShortName.'</td> 
                            <td class="text-center">'.sprintf("%.2f", $row->total_qty/100000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $val_production).'</td>
                            <td class="text-center">'.sprintf("%.2f", $row->Gross_amount/10000000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $min/100000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $cmohp_per_min).'</td> 
                            <td class="text-center">'.sprintf("%.2f", $LMMin * 100).'</td> 
                         </tr>';
                         
                         
                $totalLPcs += $row->total_qty;
                $totalRsCr += $row->Gross_amount;
                $totalLMin += $min;
                $totalLCMOHP += round($cmohp_per_min,2);
                $total_gross += $row->Gross_amount;
                $total_qty += $row->total_qty;
                $total_value += $cmohp_value/$row->total_qty;
                $total_LMin += sprintf("%.2f", $LMMin * 100);
               
        } 
        
        if($total_qty > 0 && $total_gross > 0)
        {
             $totalFOB +=  round($total_gross/$total_qty,2);
        }
        else
        {
            $totalFOB = 0;
        }
        
        
        if($total_qty > 0 && $cmohp_value1 > 0)
        {
             $totalCMOHPValue +=  round($cmohp_value1/$total_qty,2);
        }
        else
        {
            $totalCMOHPValue = 0;
        }
        $html .= '</tbody>
                    <tfoot id="monthlyBudgetTfootSales2">
                         <tr> 
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"><b>Total : </b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLPcs/100000).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalFOB).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalRsCr/10000000).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLMin/100000).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalCMOHPValue).'</b></th> 
                            <th class="text-center"><b>'.sprintf("%.2f", round($total_LMin)).'</b></th> 
                         </tr> 
                     </tfoot>
                     </table>';
        
                     
              $SaleTransactionMasterList2 = DB::select("select ac_short_name as ac_name1,sale_transaction_master.Ac_code,sum((sale_transaction_detail.order_qty * buyer_purchse_order_master.sam)) as total_min, sum(sale_transaction_detail.order_qty) as total_qty,sum(sale_transaction_detail.amount) as Gross_amount, order_group_name,OrderCategoryShortName
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId 
                        LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                        LEFT JOIN ledger_master ON ledger_master.ac_code = sale_transaction_master.Ac_code
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8)
                        AND sale_transaction_master.delflag=0 AND buyer_purchse_order_master.og_id !=4 AND buyer_purchse_order_master.order_type = 2 GROUP BY  buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.orderCategoryId ORDER BY ac_short_name ASC");   
                   
             $srno2 = 1;        
             $html2 = '<div class="col-md-12 mt-5"><label class="mb-4" style="font-size: 25px;color: black;">Sales (STOCK)</label> </div><table id="monthly_budget4" class="table  dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr. No.</th>
                                        <th>Buyer Name</th> 
                                        <th>Order Category</th>
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">FOB</th>
                                        <th class="text-center">Rs. Cr.</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">CMOHP</th> 
                                        <th class="text-center">% (LM)</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="monthlyBudgetTbodySales4">';
           
             $total_LMin2 = 0;
             $totalLPcs2 = 0;
             $totalRsCr2 = 0;
             $totalLMin2 = 0;
             $totalLCMOHP2 = 0; 
             $totalFOB2 = 0;
             $total_gross2 =0;
             $total_qty2 = 0;
             $total_value2 = 0;
             $cmohp_value13 = 0;
             $totalCMOHPValue2 = 0;
             
             foreach($SaleTransactionMasterList2 as $row2)    
             {
                     $costingData2 = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                    LEFT JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                    LEFT JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    WHERE  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row->Ac_code."' 
                                    AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8) AND sale_transaction_master.delflag=0 AND og_id !=4 AND buyer_purchse_order_master.order_type =2 ");
                                     
                    $order_group_name2 = isset($row2->order_group_name) ? $row2->order_group_name : "";                  
       
                    $cmohp2 = 0;
                    $cmohp_value2 = 0;
                    $cmohpmain2 = 0;
                    foreach($costingData2 as $costing2)
                    {
                        $total_cost_value2 = isset($costing2->total_cost_value) ? $costing2->total_cost_value : 0;
                        $other_value2 = isset($costing2->other_value) ? $costing2->other_value : 0;
                        $order_rate2 = isset($costing2->order_rate) ? $costing2->order_rate : 0;
                        $production_value2 = isset($costing2->production_value) ? $costing2->production_value : 0;
                        
                        $profit_value2=0.00;
                        $profit_value2 = ($order_rate2 - $total_cost_value2);
                      
                        $cmohp13 = $production_value2 + $profit_value2 + $other_value2;
                        $cmohp23 = $costing1->sam;
                        if($cmohp13 && $cmohp23)
                        {
                            $cmohp2 = $cmohp13/$cmohp23;
                        }
                        else
                        {
                            $cmohp2 = 0;
                        }
                   
                      $cmohpmain2 = $cmohp2;
                   
                      $cmohp_value2 += $costing2->order_qty*$cmohpmain2;

                    }
                    
                    if($cmohp_value2 > 0 && $row2->total_qty > 0)
                    { 
                        $cmohp_per_min2 = $cmohp_value2/$row2->total_qty;
                    }
                    else
                    {
                        $cmohp_per_min2 = 0;
                    }
                    $cmohp_value13 +=$cmohp_value2;
                    $min2 = isset($row2->total_min) ? $row2->total_min : 0; 
                    
                    if($row2->Gross_amount > 0 && $row2->total_qty > 0)
                    { 
                        $val_production2 = ($row2->Gross_amount/$row2->total_qty);
                    }
                    else
                    {
                        $val_production2 = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                
                 $MinData13 = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8)
                        AND sale_transaction_master.delflag=0");   
                        
                $totalMinQty2 = isset($MinData13[0]->total_min) ? $MinData13[0]->total_min : 0;
                if($min2 > 0 && $totalMinQty2 > 0)
                { 
                    $LMMin2 = (sprintf("%.2f", $min2/100000))/sprintf("%.2f", $totalMinQty2/100000);
                }
                else
                {
                    $LMMin2 = 0;
                }
                
                $first_character2 = substr($order_group_name2, 0, 1);
                      
                $html2 .='<tr>
                            <td  nowrap>'.($srno2++).'</td>
                            <td  nowrap>'.$row2->ac_name1.'</td>
                            <td  class="text-center">'.$first_character2."-".$row2->OrderCategoryShortName.'</td> 
                            <td class="text-center">'.sprintf("%.2f", $row2->total_qty/100000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $val_production2).'</td>
                            <td class="text-center">'.sprintf("%.2f", $row2->Gross_amount/10000000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $min2/100000).'</td>
                            <td class="text-center">-</td> 
                            <td class="text-center">-</td> 
                         </tr>';
                         
                         
                $totalLPcs2 += $row2->total_qty;
                $totalRsCr2 += $row2->Gross_amount;
                $totalLMin2 += $min2;
                $totalLCMOHP2 += round($cmohp_per_min2,2);
                $total_gross2 += $row2->Gross_amount;
                $total_qty2 += $row2->total_qty;
                $total_value2 += $cmohp_value2/$row2->total_qty;
                $total_LMin2 += sprintf("%.2f", $LMMin2 * 100);
               
        } 
        
        if($total_qty2 > 0 && $total_gross2 > 0)
        {
             $totalFOB2 +=  round($total_gross2/$total_qty2,2);
        }
        else
        {
            $totalFOB2 = 0;
        }
        
        
        if($total_qty2 > 0 && $cmohp_value13 > 0)
        {
             $totalCMOHPValue2 +=  round($cmohp_value13/$total_qty2,2);
        }
        else
        {
            $totalCMOHPValue2 = 0;
        }
        $html2 .= '</tbody>
                    <tfoot id="monthlyBudgetTfootSales3">
                         <tr> 
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"><b>Total :</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLPcs2/100000).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalFOB2).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalRsCr2/10000000).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLMin2/100000).'</b></th>
                            <th class="text-center"><b>-</b></th> 
                            <th class="text-center"><b>-</b></th> 
                         </tr> 
                     </tfoot>
                     </table>';
                     
                                  
                     
        $SaleTransactionMasterList1 = DB::select("select sales_head_master.sales_head_name as sales_head_name,sale_transaction_master.Ac_code,sum((sale_transaction_detail.order_qty * buyer_purchse_order_master.sam)) as total_min, sum(sale_transaction_detail.order_qty) as total_qty,sum(sale_transaction_detail.amount) as Gross_amount, order_group_name,OrderCategoryShortName
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId 
                        LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id 
                        LEFT JOIN sales_head_master ON sales_head_master.sales_head_id = sale_transaction_master.sales_head_id
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(4,7,9,11,12)
                        AND sale_transaction_master.delflag=0 GROUP BY sale_transaction_master.sales_head_id ORDER BY sale_transaction_master.sales_head_id ASC");   
             $srno1 = 1;        
             $html1 = '<div class="col-md-12 mt-5"><label class="mb-4" style="font-size: 25px;color: black;">Sales (OTHER)</label></div><table id="monthly_budget1" class="table  dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr. No.</th>
                                        <th>Sale Head Name</th> 
                                        <th>Order Category</th>
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">FOB</th>
                                        <th class="text-center">Rs. Cr.</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">CMOHP</th> 
                                        <th class="text-center">% (LM)</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="monthlyBudgetTbodySales1">';
           
             $total_LMin1 = 0;
             $totalLPcs1 = 0;
             $totalRsCr1 = 0;
             $totalLMin1 = 0;
             $totalLCMOHP1 = 0; 
             $totalFOB1 = 0;
             $total_gross1 =0;
             $total_qty1 = 0;
             $total_value1 = 0;
             $cmohp_value12 = 0;
             $totalCMOHPValue1 = 0;
             
             foreach($SaleTransactionMasterList1 as $row1)    
             {
                    $costingData1 = DB::SELECT("SELECT sales_order_costing_master.*,buyer_purchse_order_master.sam,sale_transaction_detail.order_qty  FROM sales_order_costing_master 
                                    LEFT JOIN  buyer_purchse_order_master ON  buyer_purchse_order_master.tr_code = sales_order_costing_master.sales_order_no
                                    LEFT JOIN  sale_transaction_detail ON  sale_transaction_detail.sales_order_no = sales_order_costing_master.sales_order_no
                                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                                    WHERE  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND  sale_transaction_detail.Ac_code='".$row1->Ac_code."' 
                                    AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8) AND sale_transaction_master.delflag=0 AND og_id !=4");
                                     
                    $order_group_name1 = isset($row1->order_group_name) ? $row1->order_group_name : "";                  
       
                    $cmohp1 = 0;
                    $cmohp_value1 = 0;
                    $cmohpmain1 = 0;
                    foreach($costingData1 as $costing1)
                    {
                        $total_cost_value1 = isset($costing1->total_cost_value) ? $costing1->total_cost_value : 0;
                        $other_value1 = isset($costing1->other_value) ? $costing1->other_value : 0;
                        $order_rate1 = isset($costing1->order_rate) ? $costing1->order_rate : 0;
                        $production_value1 = isset($costing1->production_value) ? $costing1->production_value : 0;
                        
                        $profit_value1=0.00;
                        $profit_value1 = ($order_rate1 - $total_cost_value1);
                      
                        $cmohp12 = $production_value1 + $profit_value1 + $other_value1;
                        $cmohp22 = $costing1->sam;
                        if($cmohp12 && $cmohp22)
                        {
                            $cmohp1 = $cmohp12/$cmohp22;
                        }
                        else
                        {
                            $cmohp1 = 0;
                        }
                   
                      $cmohpmain1 = $cmohp1;
                   
                      $cmohp_value1 += $costing1->order_qty*$cmohpmain1;

                    }
                    
                    if($cmohp_value1 > 0 && $row1->total_qty > 0)
                    { 
                        $cmohp_per_min1 = $cmohp_value1/$row1->total_qty;
                    }
                    else
                    {
                        $cmohp_per_min1 = 0;
                    }
                    $cmohp_value12 +=$cmohp_value1;
                    $min1 = isset($row1->total_min) ? $row1->total_min : 0; 
                    
                    if($row1->Gross_amount > 0 && $row1->total_qty > 0)
                    { 
                        $val_production1 = ($row1->Gross_amount/$row1->total_qty);
                    }
                    else
                    {
                        $val_production1 = 0;
                    } 
                    
                    // if($cmohp_value > 0 && $row->total_qty > 0)
                    // {
                    //     $cmohp2 = $cmohp_value/$row->total_qty;
                        
                    // }
                    // else
                    // {
                    //     $cmohp2 = 0;
                    // }
                
                 $MinData12 = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id IN(1,2,3,5,6,8)
                        AND sale_transaction_master.delflag=0");   
                        
                $totalMinQty1 = isset($MinData12[0]->total_min) ? $MinData12[0]->total_min : 0;
                if($min1 > 0 && $totalMinQty1 > 0)
                { 
                    $LMMin1 = (sprintf("%.2f", $min1/100000))/sprintf("%.2f", $totalMinQty1/100000);
                }
                else
                {
                    $LMMin1 = 0;
                }
                
                $first_character1 = substr($order_group_name1, 0, 1);
                      
                $html1 .='<tr>
                            <td  nowrap>'.($srno1++).'</td>
                            <td  nowrap>'.$row1->sales_head_name.'</td>
                            <td  class="text-center">'.$first_character1."-".$row1->OrderCategoryShortName.'</td> 
                            <td class="text-center">'.sprintf("%.2f", $row1->total_qty/100000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $val_production1).'</td>
                            <td class="text-center">'.sprintf("%.2f", $row1->Gross_amount/10000000).'</td>
                            <td class="text-center">'.sprintf("%.2f", $min1/100000).'</td>
                            <td class="text-center">-</td> 
                            <td class="text-center">-</td> 
                         </tr>';
                         
                         
                $totalLPcs1 += round($row1->total_qty/100000,2);
                $totalRsCr1 += round($row1->Gross_amount/10000000,2);
                $totalLMin1 += round(($min1/100000),2);
                $totalLCMOHP1 += round($cmohp_per_min1,2);
                $total_gross1 += $row1->Gross_amount;
                $total_qty1 += $row1->total_qty;
                $total_value1 += $cmohp_value1/$row1->total_qty;
                $total_LMin1 += sprintf("%.2f", $LMMin1 * 100);
               
        } 
        
        if($total_qty1 > 0 && $total_gross1 > 0)
        {
             $totalFOB1 +=  round($total_gross1/$total_qty1,2);
        }
        else
        {
            $totalFOB1 = 0;
        }
        
        
        if($total_qty1 > 0 && $cmohp_value12 > 0)
        {
             $totalCMOHPValue1 +=  round($cmohp_value12/$total_qty1,2);
        }
        else
        {
            $totalCMOHPValue1 = 0;
        }
        $html1 .= '</tbody>
                     <tfoot id="monthlyBudgetTfootSales4">
                         <tr> 
                            <th nowrap class="text-right" style="background: antiquewhite;"></th>
                            <th nowrap class="text-right" style="background: antiquewhite;"></th>
                            <th nowrap class="text-right" style="background: antiquewhite;"><b>Grand Total :</b></th>
                            <th class="text-center" style="background: antiquewhite;"><b>'.sprintf("%.2f", (($totalLPcs/100000) + ($totalLPcs2/100000) + $totalLPcs1)).'</b></th>
                            <th class="text-center" style="background: antiquewhite;"><b>-</b></th>
                            <th class="text-center" style="background: antiquewhite;"><b>'.sprintf("%.2f", (($totalRsCr/10000000) + ($totalRsCr2/10000000) + $totalRsCr1)).'</b></th>
                            <th class="text-center" style="background: antiquewhite;"><b>'.sprintf("%.2f", (($totalLMin/100000) + ($totalLMin2/100000) + $totalLMin1)).'</b></th>
                            <th class="text-center" style="background: antiquewhite;"><b>-</b></th> 
                            <th class="text-center" style="background: antiquewhite;"><b>-</b></th> 
                         </tr> 
                     </tfoot>
                    <tfoot id="monthlyBudgetTfootSales2">
                         <tr> 
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right"><b>Total : </b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLPcs1).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalFOB1).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalRsCr1).'</b></th>
                            <th class="text-center"><b>'.sprintf("%.2f", $totalLMin1).'</b></th>
                            <th class="text-center"><b>-</b></th> 
                            <th class="text-center"><b>-</b></th> 
                         </tr> 
                     </tfoot>
                     </table>';
                     
                     
        return response()->json(['html' => $html,'html1' => $html1,'html2' => $html2]);
    }
    
    public function GetSalesSummaryReport(Request $request)
    {
         
            $fdate= $request->salesSummaryFromDate;
            $tdate= $request->salesSummaryToDate; 
            
            $SaleTransactionList = DB::select("select sum((sale_transaction_detail.order_qty * buyer_purchse_order_master.sam)) as min, order_group_name,OrderCategoryName
                        from sale_transaction_detail  
                        LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                        LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                        LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId 
                        LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                        where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id != 10 
                        AND sale_transaction_master.delflag=0 GROUP BY buyer_purchse_order_master.orderCategoryId");   
             $srno = 1;        
             $html = '<table id="monthly_budget1" class="table  dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr. No.</th>
                                        <th class="text-center">Type</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">% L Min</th> 
                                     </tr>
                                  </thead>
                                  <tbody>';
           
             $totalLMin = 0;
             $totalLMinPer = 0; 
                     
             $MinData1 = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
                    from sale_transaction_detail  
                    LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
                    LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
                    where  sale_transaction_detail.sale_date BETWEEN '".$fdate."' AND '".$tdate."' AND sale_transaction_master.sales_head_id != 10 
                    AND sale_transaction_master.delflag=0  AND og_id !=4");   
                    
             foreach($SaleTransactionList as $row)    
             {              
                $totalMin = isset($MinData1[0]->total_min) ? $MinData1[0]->total_min : 0;
                if($row->min > 0 && $totalMin > 0)
                { 
                    $LMMin = ($row->min/$totalMin) * 100;
                }
                else
                {
                    $LMMin = 0;
                } 
                  
                $html .='<tr>
                            <td class="text-center">'.($srno++).'</td>
                            <td class="text-center">'.$row->order_group_name."-".$row->OrderCategoryName.'</td> 
                            <td class="text-center">'.sprintf("%.2f", $row->min/100000).'</td> 
                            <td class="text-center"'.$totalMin.'-'.$row->min.'>'.sprintf("%.2f", ($LMMin)).'</td>  
                         </tr>';
                           
                $totalLMin += sprintf("%.2f", $row->min/100000); 
                $totalLMinPer += sprintf("%.2f", ($LMMin));
               
        
        } 
        

        $html .= '</tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th class="text-right">Total</th>
                            <th class="text-center">'.$totalLMin.'</th>
                            <th class="text-center">'.$totalLMinPer.'</th>
                        </tr> 
                    </tfoot>
               </table>';
               

        return response()->json(['html' => $html]);
    }
     
    public function GetInvoiceWiseSalesOrderList(Request $request)
    {
        //DB::enableQueryLog();
        $buyerData = DB::table('sale_transaction_detail')->select('sales_order_no') 
                 ->where('sale_code','=',$request->sale_code)
                 ->DISTINCT()->get();
                 
        $html = '';
        $html = '<option value="">--Sales Order No--</option>';
        
        foreach ($buyerData as $row)  
        {
                $html .= '<option value="'.$row->sales_order_no.'">'.$row->sales_order_no.'</option>';
            
        }
          return response()->json(['html' => $html]);
    }
    
    public function export(Request $request)
    { 
        // DB::enableQueryLog();
        $BuyerPurchaseOrderMasterList = DB::table('sale_transaction_master')
            ->select('sale_transaction_master.bill_to','sale_transaction_master.ship_to','sale_transaction_master.tax_type_id','sale_transaction_master.sale_code','sale_transaction_master.sale_date','sale_transaction_detail.samt','sale_transaction_detail.iamt',
                'sale_transaction_detail.sale_cgst','sale_transaction_detail.sale_sgst','sale_transaction_detail.sale_igst','sale_transaction_detail.camt',
                'sale_transaction_detail.sales_order_no','unit_master.unit_name','sale_transaction_detail.order_qty','sale_transaction_detail.order_rate',
                'sale_transaction_detail.hsn_code','buyer_purchse_order_master.style_description','main_style_master.mainstyle_name','order_type_master.*',
                'buyer_purchse_order_master.po_code','sale_transaction_master.mode_of_payment')
            ->join('sale_transaction_detail', 'sale_transaction_detail.sale_code', '=', 'sale_transaction_master.sale_code') 
            ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','sale_transaction_detail.sales_order_no')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
            ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
            ->join('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('sale_transaction_master.sr_no','=', $request->sr_no)
            ->groupby('sale_transaction_detail.sales_order_no')
            ->get(); 
        //  dd(DB::getQueryLog());   
        $bill_to = isset($BuyerPurchaseOrderMasterList[0]->bill_to) ? $BuyerPurchaseOrderMasterList[0]->bill_to : '';
        $ship_to = isset($BuyerPurchaseOrderMasterList[0]->ship_to) ? $BuyerPurchaseOrderMasterList[0]->ship_to : '';
        
        // $BuyerDetail = DB::table('sale_transaction_master')->select('*','ledger_master.*') ->join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')->where('sale_transaction_master.sr_no','=', $request->sr_no)->first(); 

        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name,country_master.c_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            LEFT JOIN country_master ON country_master.c_id = state_master.country_id 
                            WHERE ledger_details.sr_no=".$bill_to); 
          
        $ledgerDetails1 = DB::SELECT("SELECT ledger_details.*,state_master.state_name,country_master.c_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            LEFT JOIN country_master ON country_master.c_id = state_master.country_id 
                            WHERE ledger_details.sr_no=".$ship_to); 

        // You can fetch them from session, request, or DB depending on your app flow

        $BuyePO = DB::SELECT("SELECT sale_date,GROUP_CONCAT(buyer_po_no ORDER BY buyer_po_no SEPARATOR ',') AS buyer_po_nos FROM sale_transaction_detail WHERE sr_no = ".$request->sr_no." GROUP BY sr_no");
        
        $xmlContent = View::make('exports.tally-xml', compact('BuyerPurchaseOrderMasterList','ledgerDetails','ledgerDetails1','BuyePO'))->render();

        return Response::make($xmlContent, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="tally_invoice.xml"',
        ]);
    }
   
    public function generateEinvoice(Request $request)
    {
        $invoice = SaleTransactionMasterModel::join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')
            ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id') 
            ->where('sale_transaction_master.sale_code', $request->sale_code)
            ->select('sale_transaction_master.*', 'ledger_master.*','state_master.state_name','ledger_master.city_name', 'ledger_master.address', 'state_master.state_id')
            ->first();


        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.trade_name='".$invoice->bill_to."'"); 
          
        $ledgerDetails1 = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.trade_name='".$invoice->ship_to."'"); 
                            
        $invoiceDetails = SaleTransactionDetailModel::join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                        ->join('style_no_master', 'style_no_master.style_no_id', '=', 'sale_transaction_detail.style_no_id')
                        ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
                        ->where('sale_code', '=', $request->sale_code)
                        ->groupBy('sale_transaction_detail.sales_order_no')
                        ->get(['sale_transaction_detail.*','buyer_purchse_order_master.style_description', 'unit_master.unit_name','style_no_master.style_no']);
        
        // 2. Generate item list array
        $itemList = [];

        foreach ($invoiceDetails as $index => $item) {
            $orderQty   = round((float) $item->pack_order_qty, 2);
            $unitPrice  = round((float) $item->order_rate, 3);
            $totalAmount = round($orderQty * $unitPrice, 2);
            $discount = round((float) ($item->disc_amount ?? 0), 2);
            $assessableAmount = round($totalAmount - $discount, 2);
        
            $cgstAmt = $sgstAmt = $igstAmt = 0.00;
        
            if ($invoice->tax_type_id == 1) {
                
                $gstRate = $item->sale_cgst + $item->sale_sgst; 
                $cgstAmt = round($assessableAmount * ($gstRate / 2) / 100, 2);
                $sgstAmt = round($assessableAmount * ($gstRate / 2) / 100, 2);
            } else {
                $gstRate = $item->sale_igst; 
                $igstAmt = round($assessableAmount * $gstRate / 100, 2);
            }
        
            $totItemVal = round($assessableAmount + $cgstAmt + $sgstAmt + $igstAmt, 2);
        
            if(Str::contains($item->unit_name, 'Pack -')) 
            {
                 $unitname = 'PAC';
            }
            else
            {
                 $unitname = $item->unit_name;
            }
            
            $itemList[] = [
                "SlNo"       => (string)($index + 1),
                "PrdDesc"    => $item->style_no,
                "IsServc"    => "N",
                "HsnCd"      => $item->hsn_code,
                "Qty"        => number_format($orderQty, 2, '.', ''),
                "Unit"       => $unitname,
                "UnitPrice"  => number_format($unitPrice, 3, '.', ''),
                "TotAmt"     => number_format($totalAmount, 2, '.', ''),
                "Discount"   => number_format($discount, 2, '.', ''),
                "AssAmt"     => number_format($assessableAmount, 2, '.', ''),
                "GstRt"      => number_format($gstRate, 3, '.', ''),
                "CgstAmt"    => number_format($cgstAmt, 2, '.', ''),
                "SgstAmt"    => number_format($sgstAmt, 2, '.', ''),
                "IgstAmt"    => number_format($igstAmt, 2, '.', ''),
                "TotItemVal" => number_format($totItemVal, 2, '.', ''),
            ];
        }
        
        // Now compute totals (as floats first)
        $assVal  = round(array_sum(array_column($itemList, 'AssAmt')), 2);
        $cgstVal = round(array_sum(array_column($itemList, 'CgstAmt')), 2);
        $sgstVal = round(array_sum(array_column($itemList, 'SgstAmt')), 2);
        $igstVal = round(array_sum(array_column($itemList, 'IgstAmt')), 2);
        $totVal  = round(array_sum(array_column($itemList, 'TotItemVal')), 2);
        
        $roundedAmt  = round($totVal, 0); // 160
        $roundOffAmt = $roundedAmt - $totVal; // -0.12
 
        $eInvoicePayload = [
            "Version" => "1.1",
            "TranDtls" => [
                "TaxSch"   => "GST",
                "SupTyp"   => "B2B",
                "RegRev"   => "N",
                "IsEcomm"  => "N" // ✅ Required
            ],  
            "DocDtls" => [
                "Typ" => "INV",
                "No"  => $request->sale_code,
                "Dt"  => date('d/m/Y'), // use dynamic date
            ],
            "SellerDtls" => [
                "Gstin" => "27ABCCS7591Q1ZD",
                "LglNm" => "Ken Global Designs Pvt. Ltd.",
                "Addr1" => "Gat No.- 298/299, A/P Kondigre, PAN NO.:ABCCS7591Q, MSME-UDYAM-15-0016970",
                "Loc"   => "ICHALKARANJI",
                "Pin"   => 416101,
                "Stcd"  => "27"
            ], 
            "BuyerDtls" => [
                "Gstin" => $ledgerDetails[0]->gst_no,
                "LglNm" => $ledgerDetails[0]->trade_name,
                "Pos"   => (string) $ledgerDetails[0]->state_id, // ensure it's "07" not "\"07\""
                "Addr1" => substr(trim($ledgerDetails[0]->addr1), 0, 100),
                "Loc"   => $ledgerDetails[0]->state_name,
                "Pin"   => $ledgerDetails[0]->pin_code,
                "Stcd"  => (string) $ledgerDetails[0]->state_id,
            ],
            "ShipDtls" => [
                "Gstin" => $ledgerDetails1[0]->gst_no,
                "LglNm" => $ledgerDetails1[0]->trade_name,
                "Addr1" => substr(trim($ledgerDetails1[0]->addr1), 0, 100),
                "Loc"   => $ledgerDetails1[0]->state_name,
                "Pin"   => $ledgerDetails1[0]->pin_code,
                "Stcd"  => (string)$ledgerDetails1[0]->state_id,
            ],
            "ItemList" => $itemList,
            "ValDtls" => [
                "AssVal"    => number_format($assVal, 2, '.', ''),
                "CgstVal"   => number_format($cgstVal, 2, '.', ''),
                "SgstVal"   => number_format($sgstVal, 2, '.', ''),
                "IgstVal"   => number_format($igstVal, 2, '.', ''),
                "RndOffAmt" => number_format($roundOffAmt, 2, '.', ''),  
                "TotInvVal" => number_format($roundedAmt, 2, '.', ''),  
            ]
        ];  
         
        //  echo "<pre>"; print_R($eInvoicePayload);exit;   
        $token = $this->getEInvoiceToken(); 
 
        $headers = [
            'Content-Type' => 'application/json',
            'aspid'     => '1786182725', // include this!
            'password' => 'Kgdpl@123',
            'AuthToken' => $token,
            'Gstin'     => '27ABCCS7591Q1ZD',
            'User_name' => 'API_KGDPL_ERP',
            'eInvPwd' => 'Kgdpl@7591'
        ];
  
        $apiUrl = 'https://einvapi.charteredinfo.com/eicore/dec/v1.03/Invoice'; 
   
        unset($eInvoicePayload['TranDtls']['EcmGstin']);
        
        $eInvoiceResponse = Http::withHeaders($headers)
            ->withBody(json_encode($eInvoicePayload), 'application/json')
            ->post($apiUrl);
            
        if ($eInvoiceResponse->successful()) 
        {
            $response = $eInvoiceResponse->json();
        
            if (!empty($response) && isset($response['Status']) && $response['Status'] == 1 && isset($response['Data'])) {
                
                // ✅ Safely decode Data if it's a JSON string
                $data = is_string($response['Data']) ? json_decode($response['Data'], true) : $response['Data'];
        
                // ✅ Check if data is array now
                if (is_array($data)) {
                    $invoice->irn = $data['Irn'] ?? null;
                    $invoice->ack_no = $data['AckNo'] ?? null;
                    $invoice->AckDt = $data['AckDt'] ?? null;
                    // $invoice->SignedInvoice = $data['SignedInvoice'] ?? null;
                    $invoice->SignedQRCode = $data['SignedQRCode'] ?? null;
                    
                    $invoice->save();
                    
                    return response()->json([
                            'message' => 'E-Invoice generated successfully',
                            'irn' => $invoice->irn,
                            'gstin' => '27ABCCS7591Q1ZD',
                            'SignedQRCode' => $invoice->SignedQRCode
                    ]);
                } else {
                    // Log decode error
                    Log::error('Failed to decode Data from eInvoice response', [
                        'raw_data' => $response['Data'],
                    ]);
        
                    return response()->json([
                        'error' => 'Invalid response format from e-invoice system.',
                    ], 500);
                }
            } else {
                // Response was successful but missing or invalid Data
                Log::error('E-Invoice generation failed with Status != 1 or missing Data', [
                    'response' => $response,
                ]);
        
                return response()->json([
                    'error' => 'E-Invoice generation failed due to invalid response.',
                    'details' => $response,
                ], 500);
            }
        } 
        else 
        {
            return response()->json([
                'error' => 'E-Invoice generation API call failed.',
                'details' => $eInvoiceResponse->json(),
            ], 500);
        }

    }
    
    public function generateEWayBill(Request $request)
    {
        
        $token = $this->getEInvoiceToken(); 
        //DB::enableQueryLog();
        $invoice = SaleTransactionMasterModel::join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')
            ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id') 
            ->join('payment_term', 'payment_term.ptm_id', '=', 'sale_transaction_master.terms_of_delivery_id', 'left') 
            ->join('ledger_master as Trans', 'Trans.ac_code', '=', 'sale_transaction_master.Ac_code', 'left')
            ->join('ledger_master as port', 'port.ac_code', '=', 'sale_transaction_master.transport_id', 'left')
            ->where('sale_transaction_master.sale_code', $request->sale_code)
            ->select(
                'sale_transaction_master.*',
                'ledger_master.*',
                'Trans.ac_short_name as dispatch_name',
                'state_master.state_name',
                'ledger_master.city_name',
                'ledger_master.address',
                'payment_term.ptm_name',
                'port.note'
            )
            ->first();
           //dd(DB::getQueryLog()); 
        $sale_code = $invoice->sale_code;  
        $sale_date = $invoice->sale_date;  
        
        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.trade_name='".$invoice->bill_to."'"); 
        
        $ledgerDetails1 = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.trade_name='".$invoice->ship_to."'"); 
                                                
        $invoiceDetails = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
                    ->join('style_no_master', 'style_no_master.style_no_id', '=', 'sale_transaction_detail.style_no_id')
                    ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
                    ->where('sale_transaction_detail.sale_code', '=', $request->sale_code)
                    ->groupBy('buyer_purchse_order_master.style_no')
                    ->get(['sale_transaction_master.*','sale_transaction_detail.*',DB::raw('sum(sale_transaction_detail.pack_order_qty) as qty'),DB::raw('sum(sale_transaction_detail.total_amount) as taxable_amt'),
                            DB::raw('sum(sale_transaction_detail.pack_order_qty * sale_transaction_detail.order_rate) as amount'),'sale_transaction_detail.order_rate','buyer_purchse_order_master.style_description', 'unit_master.unit_name','style_no_master.style_no']);
       
        $itemList = [];
        $cgstRate = 0;
        $sgstRate  = 0;
        
        foreach ($invoiceDetails as $index => $item) {
            $cgstRate = $ledgerDetails[0]->state_id == 27 ? $item->sale_cgst : 0;
            $sgstRate = $ledgerDetails[0]->state_id == 27 ? $item->sale_sgst : 0;
            $igstRate = $ledgerDetails[0]->state_id != 27 ? $item->sale_igst : 0;
            $cessRate = 0.00;
            
            if(Str::contains($item->unit_name, 'Pack -')) 
            {
                 $unitname = 'PAC';
            }
            else
            {
                 $unitname = $item->unit_name;
            }
            
            $itemList[] = [
                "productName"        => $item->style_description,
                "productDesc"        => $item->style_no,
                "hsnCode"            => $item->hsn_code,
                "quantity"           => round($item->qty),
                "qtyUnit"            => $unitname,
                "taxableAmount"      => round($item->amount),
                "cgstRate"           => round($cgstRate, 2),
                "sgstRate"           => round($sgstRate, 2),
                "igstRate"           => round($igstRate, 2),
                "cessRate"           => round($cessRate, 2),
                "cessNonAdvolAmount" => 0.00,
            ];
        }
        
        $tax_type = $invoice->tax_type_id;
        
        if ($ledgerDetails[0]->state_id == 27) { 
            $cgstValue = round(($invoice->Gross_amount * $cgstRate) / 100, 2);
            $sgstValue = round(($invoice->Gross_amount * $sgstRate) / 100, 2);
            $igstValue = 0;
        } else {
            $cgstValue = 0;
            $sgstValue = 0;
            $igstValue = round($invoice->Gross_amount * $invoice->Gst_amount, 2);
        }
        
        $cessValue = $invoiceDetails->sum(function ($item) {
            return $item->Gross_amount * ($item->sale_igst == 0 ? $item->sale_cgst + $item->sale_sgst : $item->sale_igst) / 100;
        });
        
        $expectedTotal = round($invoiceDetails->sum('Gross_amount'), 2) + round($cessValue ?? 0.00, 2);
        // $expectedTotal = round($invoiceDetails->sum('taxable_amt'), 2);
        
        // echo $distance;exit;
        $ewayBillPayload1 = [
            "supplyType"        => "O",
            "subSupplyType"     => "1",
            "subSupplyDesc"     => "Transaction",
            "docType"           => "INV",
            "docNo"             => $sale_code,
            "docDate"           => date("d/m/Y", strtotime($sale_date)),
            // FROM
            "fromGstin"         => "27ABCCS7591Q1ZD",
            "fromTrdName"       => "Ken Global Designs Pvt. Ltd.",
            "fromAddr1"         => "GAT NO 298/299",
            "fromAddr2"         => "GAT NO 298/299,A/P Kondigre",
            "fromPlace"         => "A/P Kondigre",
            "fromPincode"       => "416101",
            "actFromStateCode"  => "27",
            "fromStateCode"     => "27", 
            
            // TO
            "toGstin"           => $ledgerDetails[0]->gst_no,
            "toTrdName"         => $ledgerDetails[0]->trade_name,
            "toAddr1"           => $ledgerDetails1[0]->addr1,
            "toAddr2"           => "",
            "toPlace"           => $ledgerDetails1[0]->state_name,
            "toPincode"         => $ledgerDetails1[0]->pin_code,
            "actToStateCode"    => $ledgerDetails1[0]->state_id,
            "toStateCode"       => $ledgerDetails[0]->state_id,
            
            // Transaction
            "transactionType"   => "4",
            "otherValue"        => 0,
            "totalValue"        => round($invoice->Gross_amount),
            "cgstValue"         => round($cgstValue, 2),
            "sgstValue"         => round($sgstValue, 2),
            "igstValue"         => round($igstValue , 2),
            "cessValue"         => 0,
            "cessNonAdvolValue" => 0,
            "totInvValue"       => round($expectedTotal),
            
            // "transMode"         => $invoice->terms_of_delivery_id,
            "transDistance"     => $invoice->distance,
            "transporterId"     => $invoice->note ?? "",
            // "transDocNo"        => $invoice->transDocNo ?? "-",
            // "transDocDate"      => "-", 
             
            // Items
            "itemList"          => $itemList,
        ];
        
        if (!empty($invoice->vehicle_no))
        {
            // 🚚 Road Transport
            $ewayBillPayload1['transMode']   = "1"; // Road
            $ewayBillPayload1['vehicleNo']   = $invoice->vehicle_no;
            $ewayBillPayload1['vehicleType'] = "R"; // Regular
        } 
        elseif (in_array($invoice->terms_of_delivery_id, ["2", "3", "4"]))
        {
            // 🚆✈️🚢 Rail / Air / Ship Transport
            $ewayBillPayload1['transMode']    = $invoice->terms_of_delivery_id;
            $ewayBillPayload1['transDocNo']   = $invoice->transDocNo ?? "NA"; 
            $ewayBillPayload1['transDocDate'] = !empty($invoice->transDocDate) 
                                                  ? date("d/m/Y", strtotime($invoice->transDocDate)) 
                                                  : date("d/m/Y"); // today as fallback
        }

        \Log::info($ewayBillPayload1);
        
        // echo '<pre>'; print_r($ewayBillPayload1);exit;
        $payloadJson = json_encode($ewayBillPayload1, JSON_UNESCAPED_SLASHES);
        
        $ewayBillUrl = "https://einvapi.charteredinfo.com/v1.03/dec/ewayapi?action=GENEWAYBILL"; 
        $response = Http::withHeaders([ 
            "authtoken"    => $token, 
            "gstin"        => "27ABCCS7591Q1ZD",  
            'username'   => 'API_KGDPL_ERP', 
            'aspid'       => '1786182725',
            'password'    => 'Kgdpl@123',
        ])->withBody($payloadJson, 'application/json')
          ->post($ewayBillUrl);
   
        if ($response->successful()) {
            $res = $response->json();
            $ewayRes = $res; 

            // Save E-Way Bill in DB
            $invoice->eway_bill_no   = $ewayRes['ewayBillNo'] ?? null;
            $invoice->eway_bill_date = $ewayRes['ewayBillDate'] ?? null;
            $invoice->validUpto      = $ewayRes['validUpto'] ?? null;
            $invoice->save();

            return response()->json([
                'GSTIN'        => '27ABCCS7591Q1ZD',
                'ewayBillNo'   => $ewayRes['ewayBillNo'] ?? null,
                'ewayBillDate' => $ewayRes['ewayBillDate'] ?? null,
                'validUpto'    => $ewayRes['validUpto'] ?? null,
                'status'       => 'success'
            ]);
        } else {
            return response()->json([
                'error' => $response['message'] ?? 'E-Way Bill generation failed.',
                'raw'   => $response->json()
            ], 400);
        }

    }
    
  
    // public function generateEWayBill(Request $request)
    // {
        
    //     $token = $this->getEInvoiceToken(); 
    //     // DB::enableQueryLog();
    //     $invoice = SaleTransactionMasterModel::join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.Ac_code')
    //         ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id') 
    //         ->join('payment_term', 'payment_term.ptm_id', '=', 'sale_transaction_master.terms_of_delivery_id', 'left') 
    //         ->join('ledger_master as Trans', 'Trans.ac_code', '=', 'sale_transaction_master.Ac_code', 'left')
    //         ->where('sale_transaction_master.sale_code', $request->sale_code)
    //         ->select(
    //             'sale_transaction_master.*',
    //             'ledger_master.*',
    //             'Trans.ac_short_name as dispatch_name',
    //             'state_master.state_name',
    //             'ledger_master.city_name',
    //             'ledger_master.address',
    //             'payment_term.ptm_name'
    //         )
    //         ->first();
    //         // dd(DB::getQueryLog());   
    //     $sale_code = $invoice->sale_code;  
    //     $sale_date = $invoice->sale_date;  
       
    //     $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
    //                         LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
    //                         WHERE ledger_details.trade_name='".$invoice->bill_to."'"); 
                       
    //     $invoiceDetails = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
    //                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
    //                 ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
    //                 ->where('sale_transaction_detail.sale_code', '=', $request->sale_code)
    //                 ->groupBy('buyer_purchse_order_master.style_no')
    //                 ->get(['sale_transaction_master.*','sale_transaction_detail.*',DB::raw('sum(sale_transaction_detail.order_qty) as qty'),DB::raw('sum(sale_transaction_detail.total_amount) as taxable_amt'),
    //                         DB::raw('sum(sale_transaction_detail.order_qty * buyer_purchse_order_master.order_rate) as amount'),'buyer_purchse_order_master.order_rate',
    //                         'buyer_purchse_order_master.style_no','buyer_purchse_order_master.style_description', 'unit_master.unit_name']);
       
    //     $itemList = [];
        
    //     foreach ($invoiceDetails as $index => $item) {
    //         $cgstRate = $ledgerDetails[0]->state_id == 27 ? $item->sale_cgst : 0;
    //         $sgstRate = $ledgerDetails[0]->state_id == 27 ? $item->sale_sgst : 0;
    //         $igstRate = $ledgerDetails[0]->state_id == 27 ? $item->sale_igst : 0;
    //         $cessRate = 0.00;
        
    //         $itemList[] = [
    //             "productName"        => $item->style_no,
    //             "productDesc"        => $item->style_description,
    //             "hsnCode"            => $item->hsn_code,
    //             "quantity"           => round($item->qty, 2),
    //             "qtyUnit"            => $item->unit_name,
    //             "taxableAmount"      => round($item->taxable_amt, 2),
    //             "cgstRate"           => round($cgstRate, 2),
    //             "sgstRate"           => round($sgstRate, 2),
    //             "igstRate"           => round($igstRate, 2),
    //             "cessRate"           => round($cessRate, 2),
    //             "cessNonAdvolAmount" => 0.00,
    //         ];
    //     }
        
    //     $tax_type = $invoice->tax_type_id;
        
    //     if ($ledgerDetails[0]->state_id == 27) {
    //         $cgstValue = round(($invoice->taxable_amt * $cgstRate) / 100, 2);
    //         $sgstValue = round(($invoice->taxable_amt * $sgstRate) / 100, 2);
    //         $igstValue = 0;
    //     } else {
    //         $cgstValue = 0;
    //         $sgstValue = 0;
    //         $igstValue = round(($invoice->taxable_amt * $igstRate) / 100, 2); 
    //     }
        
    //     $cessValue = $invoiceDetails->sum(function ($item) {
    //         return $item->taxable_amt * ($item->sale_igst == 0 ? $item->sale_cgst + $item->sale_sgst : $item->sale_igst) / 100;
    //     });
    //     // echo $cgstValue;exit;
    //     $expectedTotal = round($invoiceDetails->sum('taxable_amt'), 2) + round($cgstValue, 2) + round($sgstValue, 2) + round($igstValue, 2) + round($cessValue ?? 0.00, 2);
    //     $distance = $this->getPinCodeDistance('416101', $ledgerDetails[0]->pin_code);
       
    //     $ewayBillPayload1 = [
    //         "supplyType"        => "O",
    //         "subSupplyType"     => "1",
    //         "subSupplyDesc"     => "Transaction",
    //         "docType"           => "INV",
    //         "docNo"             => $sale_code,
    //         "docDate"           => date("d/m/Y", strtotime($sale_date)),
    //         // FROM
    //         "fromGstin"         => "27ABCCS7591Q1ZD",
    //         "fromTrdName"       => "Ken Global Designs Pvt. Ltd.",
    //         "fromAddr1"         => "GAT NO 298/299",
    //         "fromAddr2"         => "GAT NO 298/299,A/P Kondigre",
    //         "fromPlace"         => "A/P Kondigre",
    //         "fromPincode"       => "416101",
    //         "actFromStateCode"  => "27",
    //         "fromStateCode"     => "27", 
            
    //         // TO
    //         "toGstin"           => $ledgerDetails[0]->gst_no,
    //         "toTrdName"         => $ledgerDetails[0]->trade_name,
    //         "toAddr1"           => $ledgerDetails[0]->addr1,
    //         "toAddr2"           => $ledgerDetails[0]->addr1,
    //         "toPlace"           => $ledgerDetails[0]->state_name,
    //         "toPincode"         => $ledgerDetails[0]->pin_code,
    //         "actToStateCode"    => $ledgerDetails[0]->state_id,
    //         "toStateCode"       => $ledgerDetails[0]->state_id,
            
    //         // Transaction
    //         "transactionType"   => "4",
    //         "otherValue"        => 0,
    //         "totalValue"        => round($invoice->taxable_amt, 2),
    //         "cgstValue"         => round($cgstValue, 2),
    //         "sgstValue"         => round($sgstValue, 2),
    //         "igstValue"         => round($igstValue, 2),
    //         "cessValue"         => 0,
    //         "cessNonAdvolValue" => 0,
    //         "totInvValue"       => round($expectedTotal, 2),
            
    //         "transDocNo"        => "DOC/123",
    //         "transMode"         => "1",
    //         "transDistance"     => $distance,
    //         "transDocDate"      => date("d/m/Y", strtotime($sale_date)),
    //         "vehicleNo"         => "MH09CU1146",
    //         "vehicleType"       => "R",
            
    //         // Items
    //         "itemList"          => $itemList,
    //     ];
    //     //  echo '<pre>'; print_R($ewayBillPayload1);exit;
    //     \Log::info($ewayBillPayload1);
        
    //     $payloadJson = json_encode($ewayBillPayload1, JSON_UNESCAPED_SLASHES);
        
    //     $ewayBillUrl = "https://einvapi.charteredinfo.com/v1.03/dec/ewayapi?action=GENEWAYBILL"; 
    //     $response = Http::withHeaders([ 
    //         "authtoken"    => $token, 
    //         "gstin"        => "27ABCCS7591Q1ZD",  
    //         'username'   => 'API_KGDPL_ERP', 
    //         'aspid'       => '1786182725',
    //         'password'    => 'Kgdpl@123',
    //     ])->withBody($payloadJson, 'application/json')
    //       ->post($ewayBillUrl);
   
    //     if ($response->successful()) {
    //         $res = $response->json();
    //         $ewayRes = $res; 

    //         // Save E-Way Bill in DB
    //         $invoice->eway_bill_no   = $ewayRes['ewayBillNo'] ?? null;
    //         $invoice->eway_bill_date = $ewayRes['ewayBillDate'] ?? null;
    //         $invoice->validUpto      = $ewayRes['validUpto'] ?? null;
    //         $invoice->distance       = $ewayRes['transDistance'] ?? null;
    //         $invoice->save();

    //         return response()->json([
    //             'GSTIN'        => '27ABCCS7591Q1ZD',
    //             'ewayBillNo'   => $ewayRes['ewayBillNo'] ?? null,
    //             'ewayBillDate' => $ewayRes['ewayBillDate'] ?? null,
    //             'validUpto'    => $ewayRes['validUpto'] ?? null,
    //             'status'       => 'success'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'error' => $response['message'] ?? 'E-Way Bill generation failed.',
    //             'raw'   => $response->json()
    //         ], 400);
    //     }

    // }
  
    /**
     * Step 2: Encrypt JSON payload
     */
    protected function encryptPayload($payload)
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        
        return base64_encode(openssl_encrypt($json, "AES-256-ECB", $this->apiKey, OPENSSL_RAW_DATA));
    }

    /**
     * Step 3: Decrypt NIC response
     */
    protected function decryptResponse($encryptedData)
    {
        return openssl_decrypt(
            base64_decode($encryptedData),
            "AES-256-ECB",
            $this->apiKey,
            OPENSSL_RAW_DATA
        );
    }
    
    public function getPinCodeDistance($pincode1,$pincode2)
    {
        // $pincode1 = "416115";
        // $pincode2 = "560095";
    
        $km = null; // Initialize
    
        $coords1 = $this->getCoordinatesFree($pincode1);
        $coords2 = $this->getCoordinatesFree($pincode2);
    
        if ($coords1 && $coords2) {
            $url = "http://router.project-osrm.org/route/v1/driving/{$coords1['lon']},{$coords1['lat']};{$coords2['lon']},{$coords2['lat']}?overview=false";
            $response = Http::get($url);
    
            if ($response->successful()) {
                $routeData = $response->json();
                if (!empty($routeData['routes'][0]['distance'])) {
                    $meters = $routeData['routes'][0]['distance'];
                    $km = round($meters / 1000, 2); // 2 decimal places
                }
            }
        }
    
        return $km ? $km : 0;
    }
    
    public function ReadDistanceFromPincode(Request $request)
    {
        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.trade_name='".$request->trade_name."'"); 

        $distance = $this->getPinCodeDistance('416101', $ledgerDetails[0]->pin_code);
        
        return round($distance) ? round($distance) : 0;
         
    }
    
    public function getCoordinatesFree($pincode)
    {
        $url = "https://nominatim.openstreetmap.org/search";
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelApp/1.0 (your-email@example.com)'
        ])->get($url, [
            'postalcode' => $pincode,
            'country' => 'India',
            'format' => 'json'
        ]);
    
        if ($response->successful()) {
            $data = $response->json();
            if (!empty($data[0])) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            }
        }
    
        return null;
    }
    
    public function getEInvoiceToken()
    { 
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get('https://einvapi.charteredinfo.com/eivital/dec/v1.04/auth', [
            'aspid' => '1786182725',
            'password' => 'Kgdpl@123',
            'Gstin' => '27ABCCS7591Q1ZD',
            'User_name' => 'API_KGDPL_ERP',
            'eInvPwd' => 'Kgdpl@7591'
        ]);
 

        $data = $response->json();
         
        $token = $data['Data']['AuthToken'] ?? null; 
        return $token;
    }
    
    // public function getEInvoiceTokenForProduction()
    // { 
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json'
    //     ])->get('https://api.einvoice1.gst.gov.in/eivital/v1.03/auth', [
    //         'aspid' => '1786182725',
    //         'password' => 'Kgdpl@123',
    //         'Gstin' => '27ABCCS7591Q1ZD',
    //         'User_name' => 'API_KGDPL111',
    //         'eInvPwd' => 'Kgdpl@7591'
    //     ]);
 

    //     $data = $response->json();
         
    //     $token = $data['Data']['AuthToken'] ?? null; 
    //     return $token;
    // }
    public function getEInvoiceTokenForProduction()
    { 
        $payload = [
            'aspid' => '1786182725',
            'password' => 'Kgdpl@123',
            'Gstin' => '27ABCCS7591Q1ZD',
            'User_name' => 'API_KGDPL_ERP',
            'eInvPwd' => 'Kgdpl@7591'
        ];
    
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
            'aspid' => '1786182725',
            'password' => 'Kgdpl@123',
            'Gstin' => '27ABCCS7591Q1ZD',
            'User_name' => 'API_KGDPL_ERP',
            'eInvPwd' => 'Kgdpl@7591'
        ])->get('https://einvapi.charteredinfo.com/eivital/dec/v1.04/auth', $payload);
         //print_r();exit;
        if ($response->failed()) {
            throw new \Exception("E-Invoice Auth Failed: " . $response->body());
        }
    
        $data = $response->json();
        return $data['Data']['AuthToken'] ?? null;
    }

    public function EInvoice($sr_no)
    { 
        //   DB::enableQueryLog();
        $invoice = SaleTransactionMasterModel::join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.firm_id', 'left')
            ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id', 'left') 
            ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'sale_transaction_master.terms_of_delivery_id', 'left') 
            ->join('ledger_master as Trans', 'Trans.ac_code', '=', 'sale_transaction_master.transport_id', 'left')
            ->where('sale_transaction_master.sr_no', $sr_no)
            ->select(
                'sale_transaction_master.*',
                'ledger_master.*',
                'Trans.ac_short_name as dispatch_name',
                'Trans.note as tranport',
                'state_master.state_name',
                'ledger_master.city_name',
                'ledger_master.address',
                'shipment_mode_master.ship_mode_name'
            )
            ->first();
        //  dd(DB::getQueryLog());             
        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.ac_code=".$invoice->Ac_code); 
                            
        //   DB::enableQueryLog();                  
        $BillToDetails = DB::table('ledger_details') 
            ->leftJoin('state_master', 'state_master.state_id', '=', 'ledger_details.state_id')
            ->where('ledger_details.trade_name', $invoice->bill_to)
            ->select('ledger_details.*', 'state_master.state_name')
            ->get();

               
                                 
        $ShipToDetails = DB::table('ledger_details') 
            ->leftJoin('state_master', 'state_master.state_id', '=', 'ledger_details.state_id')
            ->where('ledger_details.trade_name', $invoice->ship_to)
            ->select('ledger_details.*', 'state_master.state_name')
            ->get();
                               
                            
        // $invoiceDetails = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
        //                 ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
        //                 ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
        //                 ->where('sale_transaction_detail.sr_no', '=', $sr_no)
        //                 ->groupBy('buyer_purchse_order_master.style_no')
        //                 ->get(['sale_transaction_master.*','sale_transaction_detail.*',DB::raw('sum(sale_transaction_detail.order_qty) as qty'),
        //                         DB::raw('sum(sale_transaction_detail.order_qty * buyer_purchse_order_master.order_rate) as amount'),'sale_transaction_detail.order_rate',
        //                         'buyer_purchse_order_master.style_description','buyer_purchse_order_master.style_no', 'unit_master.unit_name']);
         
        $invoiceDetails = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
            ->join('buyer_purchase_order_detail', 'buyer_purchase_order_detail.tr_code', '=', 'buyer_purchse_order_master.tr_code') 
            ->join('carton_packing_inhouse_detail', function ($join) {
                $join->on(DB::raw("FIND_IN_SET(carton_packing_inhouse_detail.cpki_code, sale_transaction_master.carton_packing_nos)"), '>', DB::raw('0'));
            })
            ->join('style_no_master', 'style_no_master.style_no_id', '=', 'buyer_purchase_order_detail.style_no_id', 'left')
            ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id')
            ->where('sale_transaction_detail.sr_no', '=', $sr_no)
            ->whereNotNull('buyer_purchase_order_detail.style_no_id')   
            ->where('buyer_purchase_order_detail.style_no_id', '>', 0) 
            ->groupBy('sale_transaction_detail.sales_order_no')
            ->get([
                'sale_transaction_master.*',
                'sale_transaction_detail.*',
                DB::raw('SUM(DISTINCT sale_transaction_detail.pack_order_qty) as qty'),
                DB::raw('SUM(DISTINCT sale_transaction_detail.pack_order_qty * buyer_purchse_order_master.order_rate) as amount'),
                'sale_transaction_detail.order_rate',
                'buyer_purchse_order_master.style_description',
                'style_no_master.style_no',
                'unit_master.unit_name'
            ]);
        
                                      
        $invoiceDetails1 = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no') 
            ->where('sale_transaction_detail.sr_no', '=', $sr_no)
            ->groupBy(
                'sale_transaction_detail.hsn_code'
            )
            ->get([
                'sale_transaction_master.*',
                'sale_transaction_detail.*',
                DB::raw('SUM(sale_transaction_detail.pack_order_qty) as qty'),
                DB::raw('SUM(sale_transaction_detail.pack_order_qty * sale_transaction_detail.order_rate) as amount'),
                'sale_transaction_detail.order_rate',
                'buyer_purchse_order_master.style_description'
        ]);


       $BuyePO = DB::SELECT("SELECT sale_date,GROUP_CONCAT(buyer_po_no ORDER BY buyer_po_no SEPARATOR ',') AS buyer_po_nos FROM sale_transaction_detail WHERE sr_no = ".$sr_no." GROUP BY sr_no");


        return view('e-invoice', compact('invoice','invoiceDetails','ledgerDetails','BillToDetails','ShipToDetails','BuyePO','invoiceDetails1'));
    }
     
    public function EInvoicePreview($sr_no)
    { 
        
        $invoice = SaleTransactionMasterModel::join('ledger_master', 'ledger_master.ac_code', '=', 'sale_transaction_master.firm_id')
            ->join('state_master', 'state_master.state_id', '=', 'ledger_master.state_id') 
            ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'sale_transaction_master.terms_of_delivery_id', 'left') 
            ->join('ledger_master as Trans', 'Trans.ac_code', '=', 'sale_transaction_master.transport_id', 'left')
            ->where('sale_transaction_master.sr_no', $sr_no)
            ->select(
                'sale_transaction_master.*',
                'ledger_master.*',
                'Trans.ac_short_name as dispatch_name',
                'Trans.note as tranport',
                'state_master.state_name',
                'ledger_master.city_name',
                'ledger_master.address',
                'shipment_mode_master.ship_mode_name'
            )
            ->first();
            
        $ledgerDetails = DB::SELECT("SELECT ledger_details.*,state_master.state_name FROM ledger_details
                            LEFT JOIN state_master ON state_master.state_id = ledger_details.state_id 
                            WHERE ledger_details.ac_code=".$invoice->Ac_code); 
                            
        //   DB::enableQueryLog();                  
        $BillToDetails = DB::table('ledger_details') 
            ->leftJoin('state_master', 'state_master.state_id', '=', 'ledger_details.state_id')
            ->where('ledger_details.trade_name', $invoice->bill_to)
            ->select('ledger_details.*', 'state_master.state_name')
            ->get();

               
                                 
        $ShipToDetails = DB::table('ledger_details') 
            ->leftJoin('state_master', 'state_master.state_id', '=', 'ledger_details.state_id')
            ->where('ledger_details.trade_name', $invoice->ship_to)
            ->select('ledger_details.*', 'state_master.state_name')
            ->get();
        // DB::enableQueryLog(); 
        $invoiceDetails = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no')
            ->join('buyer_purchase_order_detail', 'buyer_purchase_order_detail.tr_code', '=', 'buyer_purchse_order_master.tr_code') 
            ->join('carton_packing_inhouse_detail', function ($join) {
                $join->on(DB::raw("FIND_IN_SET(carton_packing_inhouse_detail.cpki_code, sale_transaction_master.carton_packing_nos)"), '>', DB::raw('0'));
            })
            ->join('style_no_master', 'style_no_master.style_no_id', '=', 'buyer_purchase_order_detail.style_no_id', 'left')
            ->join('unit_master', 'unit_master.unit_id', '=', 'sale_transaction_detail.unit_id', 'left')
            ->where('sale_transaction_detail.sr_no', '=', $sr_no)
            ->whereNotNull('buyer_purchase_order_detail.style_no_id')   
            ->where('buyer_purchase_order_detail.style_no_id', '>', 0) 
            ->groupBy('sale_transaction_detail.sales_order_no')
            ->get([
                'sale_transaction_master.*',
                'sale_transaction_detail.*',
                DB::raw('SUM(DISTINCT sale_transaction_detail.pack_order_qty) as qty'),
                DB::raw('SUM(DISTINCT sale_transaction_detail.pack_order_qty * buyer_purchse_order_master.order_rate) as amount'),
                'sale_transaction_detail.order_rate',
                'buyer_purchse_order_master.style_description',
                'style_no_master.style_no',
                'unit_master.unit_name'
            ]);
        
            //  dd(DB::getQueryLog());                
        //DB::enableQueryLog();
                                       
        $invoiceDetails1 = SaleTransactionDetailModel::join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sale_transaction_detail.sales_order_no') 
            ->where('sale_transaction_detail.sr_no', '=', $sr_no)
            ->groupBy(
                'sale_transaction_detail.hsn_code'
            )
            ->get([
                'sale_transaction_master.*',
                'sale_transaction_detail.*',
                DB::raw('SUM(sale_transaction_detail.pack_order_qty) as qty'),
                DB::raw('SUM(sale_transaction_detail.pack_order_qty * sale_transaction_detail.order_rate) as amount'),
                'sale_transaction_detail.order_rate',
                'buyer_purchse_order_master.style_description'
        ]);

                                
        //dd(DB::getQueryLog());
        $BuyePO = DB::SELECT("SELECT sale_date,GROUP_CONCAT(buyer_po_no ORDER BY buyer_po_no SEPARATOR ',') AS buyer_po_nos FROM sale_transaction_detail WHERE sr_no = ".$sr_no." GROUP BY sr_no");


        return view('EInvoicePreview', compact('invoice','invoiceDetails','ledgerDetails','BillToDetails','ShipToDetails','BuyePO','invoiceDetails1'));
    }
     
     
    public function SaleInvoiceQRCode(Request $request)
    {
        $saleCode = $request->sale_code;
        $qrImage  = $request->qr_image; // base64 image
        $qrImage1  = $request->qr_image1; // base64 image
    
        if (empty($saleCode) || empty($qrImage)) {
            return response()->json(['error' => 'Missing sale_code or qr_image'], 400);
        }
    
        // Extract and decode the base64 image
        $imageParts = explode(',', $qrImage);
        $imageParts1 = explode(',', $qrImage1);
        if (count($imageParts) !== 2) {
            return response()->json(['error' => 'Invalid QR image format'], 400);
        }
    
        $decodedImage = base64_decode($imageParts[1]);
        $decodedImage1 = base64_decode($imageParts1[1]);
    
        // Define location and filename
        $folderPath = public_path('uploads/QRCode/');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    
        $fileName = time() . '_invoice.png';
        $filePath = $folderPath . '/' . $fileName;
        
        $fileName1 = time() . '_eway.png';
        $filePath1 = $folderPath . '/' . $fileName1;
    
        // Delete if file with same name exists (unlikely with time() but still safe)
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        // Save the image
        file_put_contents($filePath, $decodedImage);
        file_put_contents($filePath1, $decodedImage1);
    
        DB::table('sale_transaction_master')
        ->where('sale_code', $saleCode)
        ->update([
            'qr_code_file' => $fileName,
            'qr_code_path' => 'uploads/QRCode/' . $fileName,
            'eway_file' => $fileName1,
            'eway_path' => 'uploads/QRCode/' . $fileName1,
            'updated_at'   => now()
        ]);

        return response()->json([
            'success' => true,
            'path'    => 'uploads/QRCode/' . $fileName
        ]);
    }
    
    public function SaleEwayBillQRCode(Request $request)
    {
        $saleCode = $request->sale_code; 
        $qrImage1  = $request->qr_image1; // base64 image
        $validUpto = $request->validUpto;
        
        if (empty($saleCode) || empty($qrImage)) {
            return response()->json(['error' => 'Missing sale_code or qr_image'], 400);
        }
    
        // Extract and decode the base64 image 
        $imageParts1 = explode(',', $qrImage1);
        if (count($imageParts) !== 2) {
            return response()->json(['error' => 'Invalid QR image format'], 400);
        }
     
        $decodedImage1 = base64_decode($imageParts1[1]);
    
        // Define location and filename
        $folderPath = public_path('uploads/QRCode/');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
     
        $fileName1 = time() . '_eway.png';
        $filePath1 = $folderPath . '/' . $fileName1;
    
        // Delete if file with same name exists (unlikely with time() but still safe)
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        // Save the image 
        file_put_contents($filePath1, $decodedImage1);
    
        DB::table('sale_transaction_master')
        ->where('sale_code', $saleCode)
        ->update([ 
            'eway_file' => $fileName1,
            'eway_path' => 'uploads/QRCode/' . $fileName1,
            'validUpto' => $validUpto,
            'updated_at'   => now()
        ]);

        return response()->json([
            'success' => true,
            'path'    => 'uploads/QRCode/' . $fileName
        ]);
    }
    
}
