<?php

namespace App\Http\Controllers;
 
use App\Models\ItemModel;
use App\Models\FinishingBillingDetailModel;
use App\Models\FinishingBillingMasterModel;
use App\Models\PerticularModel;
use App\Models\LedgerModel;
use App\Models\BrandModel;
use App\Models\MainStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FinishingBillingController extends Controller
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
        ->where('form_id', '282')
        ->first();

        $finishingBillingData = FinishingBillingMasterModel::leftjoin('perticular_master', 'perticular_master.perticular_id', '=', 'finishing_billing_master.perticular_id') 
            ->where('finishing_billing_master.delflag', '=', '0')
            ->select('finishing_billing_master.*', 'perticular_master.perticular_name')
            ->orderBy('finishing_billing_master.finishing_billing_code', 'DESC')
            ->get();


        return view('FinishingBillingMasterList', compact('finishingBillingData','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $BrandList = BrandModel::select('*')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get(); 
        
        $SalesOrderList = DB::SELECT("SELECT * from inward_for_packing_master GROUP BY sales_order_no");
                    
        // $SalesOrderList = DB::SELECT("
        //     SELECT frm.*
        //     FROM finishing_rate_master frm
        //     LEFT JOIN (
        //         SELECT 
        //             sales_order_no,Ac_code, 
        //             IFNULL(SUM(total_qty), 0) - IFNULL((
        //                 SELECT SUM(packing_qty) 
        //                 FROM finishing_billing_details 
        //                 WHERE finishing_billing_details.sales_order_no = ifp.sales_order_no
        //             ), 0) AS total_packing_inward
        //         FROM inward_for_packing_master ifp
        //         GROUP BY ifp.sales_order_no
        //     ) AS inwardData ON frm.Ac_code = inwardData.Ac_code
        //     WHERE frm.delflag = 0
        //     AND (inwardData.total_packing_inward IS NULL OR inwardData.total_packing_inward > 0)
        // ");


        $perticularList = PerticularModel::where('delflag','=', '0')
                        ->whereIn('perticular_id', [8,9,12])
                        ->get();  
        $perticularList1 = PerticularModel::where('delflag', '=', '0')
                        ->get(); 
                        
        $counter_number = DB::select("select c_code, tr_no + 1 as tr_no from counter_number where c_name ='C1' AND type='FinishingBilling'");
        $counter = isset($counter_number[0]->tr_no) ? $counter_number[0]->tr_no : 0;
        
        return view('FinishingBillingMaster',compact('SalesOrderList','Ledger', 'BrandList','MainStyleList', 'FGList', 'perticularList', 'counter', 'perticularList1'));
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
            $data1=array
            (  
                'finishing_billing_code'=>$request->finishing_billing_code,
                'finishing_billing_date'=>$request->finishing_billing_date,
                'perticular_id'=>$request->perticular_id,
                'bill_no'=>$request->bill_no,  
                'total_qty'=>$request->total_qty,  
                'total_amount'=>$request->total_amount,  
                'narration'=>$request->narration,  
                'supplier_id'=>$request->supplier_id,  
                'userId'=>Session::get('userId'), 
                'created_at'=>date("Y-m-d H:i:s"),
                'updated_at'=>date("Y-m-d H:i:s"),
                'delflag'=>0
             );
         
            FinishingBillingMasterModel::insert($data1); 
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FinishingBilling'");
            $maxId = FinishingBillingMasterModel::max('finishing_billing_code');
            $sales_order_no = $request->sales_order_no;
            if(count($sales_order_no)>0)
            {   
                for($x=0; $x<count($sales_order_no); $x++) 
                { 
                    $data4 =array
                    ( 
                        'finishing_billing_code'=>$maxId, 
                        'sales_order_no'=>$request->sales_order_no[$x],
                        'perticular_ids'=>$request->perticular_ids[$x],
                        'brand_id'=>$request->brand_id[$x], 
                        'fg_id'=>$request->fg_id[$x], 
                        'style_no'=>$request->style_no[$x], 
                        'till_date_packing_inward_qty'=>$request->till_date_packing_inward_qty[$x], 
                        'till_date_packing_qty'=>$request->till_date_packing_qty[$x],  
                        'till_date_billing_qty'=>$request->till_date_billing_qty[$x],  
                        'till_date_balance_qty'=>$request->till_date_balance_qty[$x],  
                        'packing_qty'=>$request->packing_qty[$x],  
                        'rate'=>$request->rate[$x],  
                        'amount'=>$request->amount[$x],  
                    ); 
                    FinishingBillingDetailModel::insert($data4);
                }
            }
    
        
        return redirect()->route('FinishingBilling.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinishingBilling  $FinishingBilling
     * @return \Illuminate\Http\Response
     */
    public function show(FinishingBilling $FinishingBilling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinishingBilling  $FinishingBilling
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $FinishingBillingMaster = FinishingBillingMasterModel::find($id);  
        $FinishingBillingDetailList = FinishingBillingDetailModel::where('finishing_billing_code','=', $FinishingBillingMaster->finishing_billing_code)->get(); 
       
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $BrandList = BrandModel::select('*')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get(); 
        $SalesOrderList = DB::SELECT("SELECT * from inward_for_packing_master GROUP BY sales_order_no");
        $perticularList = PerticularModel::where('delflag','=', '0')
                        ->whereIn('perticular_id', [8,9,12])
                        ->get();  
        $perticularList1 = PerticularModel::where('delflag', '=', '0')
                        ->get(); 
        
        return view('FinishingBillingMasterEdit',compact('FinishingBillingMaster', 'FinishingBillingDetailList', 'Ledger','BrandList','MainStyleList','FGList','SalesOrderList', 'perticularList', 'perticularList1')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinishingBilling  $FinishingBilling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $data1=array
        (  
            'finishing_billing_code'=>$request->finishing_billing_code,
            'finishing_billing_date'=>$request->finishing_billing_date,
            'perticular_id'=>$request->perticular_id,
            'bill_no'=>$request->bill_no,  
            'total_qty'=>$request->total_qty,  
            'total_amount'=>$request->total_amount,  
            'narration'=>$request->narration,  
            'supplier_id'=>$request->supplier_id,  
            'userId'=>Session::get('userId'), 
            'created_at'=>date("Y-m-d H:i:s"),
            'updated_at'=>date("Y-m-d H:i:s"),
            'delflag'=>0
         );
         
        $FinishingBillingList = FinishingBillingMasterModel::findOrFail($request->finishing_billing_code);
        $FinishingBillingList->fill($data1)->save(); 
         
        $sales_order_no = $request->sales_order_no;
        FinishingBillingDetailModel::where('finishing_billing_code', $request->finishing_billing_code)->delete();
        if(count($sales_order_no)>0)
        {   
            for($x=0; $x<count($sales_order_no); $x++) 
            {
                
                $data4 =array
                ( 
                        'finishing_billing_code'=>$request->finishing_billing_code, 
                        'sales_order_no'=>$request->sales_order_no[$x],
                        'perticular_ids'=>$request->perticular_ids[$x],
                        'brand_id'=>$request->brand_id[$x], 
                        'fg_id'=>$request->fg_id[$x], 
                        'style_no'=>$request->style_no[$x], 
                        'till_date_packing_inward_qty'=>$request->till_date_packing_inward_qty[$x], 
                        'till_date_packing_qty'=>$request->till_date_packing_qty[$x],  
                        'till_date_billing_qty'=>$request->till_date_billing_qty[$x],   
                        'till_date_balance_qty'=>$request->till_date_balance_qty[$x],
                        'packing_qty'=>$request->packing_qty[$x],  
                        'rate'=>$request->rate[$x],  
                        'amount'=>$request->amount[$x],  
                );
                FinishingBillingDetailModel::insert($data4);
            }
        }
        return redirect()->route('FinishingBilling.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinishingBilling  $FinishingBilling
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        FinishingBillingMasterModel::where('finishing_billing_code', $id)->delete();  
        FinishingBillingDetailModel::where('finishing_billing_code', $id)->delete();  

        return 1;
    } 
    
    public function GetPerticularCode(Request $request)
    {  
        $perticularData = PerticularModel::where('perticular_id', $request->perticular_id)->first();  
        $financial_year = DB::SELECT("select fin_year_name from financial_year_master where financial_year_master.fin_year_id=(select max(fin_year_id) FROM financial_year_master WHERE delflag=0)");
        return response()->json(['perticular_code' => $perticularData->perticular_code, 'financial_year' => $financial_year[0]->fin_year_name]);
    }
    
    public function GetPackingQtySalesOrderWise(Request $request)
    {  
        $packingData = DB::SELECT("SELECT sum(total_qty) as total_packing FROM packing_inhouse_master WHERE sales_order_no ='".$request->sales_order_no."'"); 
        
        $inwardData = DB::SELECT("SELECT ifnull(sum(total_qty),0) - ifnull((select sum(packing_qty) FROM finishing_billing_details WHERE sales_order_no ='".$request->sales_order_no."' AND perticular_ids='".$request->perticular_id."'),0) as total_packing_inward 
                                FROM inward_for_packing_master WHERE sales_order_no ='".$request->sales_order_no."'");  
                                
        if($request->perticular_id == 8)
        {
            //DB::enableQueryLog();
             $finishingData = DB::SELECT("SELECT (packing_rate) as total_finishing FROM finishing_rate_details 
                            INNER JOIN finishing_rate_master ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                            WHERE finishing_rate_master.brand_id ='".$request->brand_id."' AND finishing_rate_date <='".$request->finishing_billing_date."'  ORDER BY finishing_rate_master.finishing_rate_code DESC
                            LIMIT 1");  
            //dd(DB::getQueryLog());
        }
        else if($request->perticular_id == 9)
        {
             $finishingData = DB::SELECT("SELECT (finishing_rate) as total_finishing FROM finishing_rate_details 
                            INNER JOIN finishing_rate_master ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                            WHERE finishing_rate_master.brand_id ='".$request->brand_id."' AND finishing_rate_date <='".$request->finishing_billing_date."' ORDER BY finishing_rate_date ASC
                            LIMIT 1"); 
            
        }
        else if($request->perticular_id == 12)
        {
             $finishingData = DB::SELECT("SELECT (kaj_button_rate) as total_finishing FROM finishing_rate_details 
                            INNER JOIN finishing_rate_master ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                            WHERE finishing_rate_master.brand_id ='".$request->brand_id."' AND finishing_rate_date <='".$request->finishing_billing_date."' ORDER BY finishing_rate_date ASC
                            LIMIT 1"); 
        }
        else
        {
            $finishingData = '';
        }
       
        //DB::enableQueryLog();             
        $BillingData = DB::SELECT("SELECT sum(packing_qty) as till_date_billing_qty FROM finishing_billing_details 
                                INNER JOIN  finishing_billing_master ON finishing_billing_master.finishing_billing_code = finishing_billing_details.finishing_billing_code
                                WHERE sales_order_no ='".$request->sales_order_no."' AND perticular_ids =".$request->perticular_id);  
        //dd(DB::getQueryLog());
        $total_packing = isset($packingData[0]->total_packing) ? $packingData[0]->total_packing : 0;
        $total_packing_inward = isset($inwardData[0]->total_packing_inward) ? $inwardData[0]->total_packing_inward : 0;
        $total_finishing = isset($finishingData[0]->total_finishing) ? $finishingData[0]->total_finishing : 0;
        $till_date_billing_qty = isset($BillingData[0]->till_date_billing_qty) ? $BillingData[0]->till_date_billing_qty : 0;
        
        return response()->json(['total_packing' =>$total_packing , 'total_packing_inward' => $total_packing_inward, 'total_finishing' => $total_finishing, 'till_date_billing_qty' => $till_date_billing_qty]);
    }
    public function FinishingBillingPrint($finishing_billing_code)
    {
        
        $finishingBillingMaster = FinishingBillingMasterModel::leftjoin('perticular_master', 'perticular_master.perticular_id', '=', 'finishing_billing_master.perticular_id') 
                                ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'finishing_billing_master.supplier_id')  
                                ->select('finishing_billing_master.*', 'perticular_master.perticular_name', 'ledger_master.*') 
                                ->where('finishing_billing_master.delflag', '=', '0')
                                ->where('finishing_billing_master.finishing_billing_code', '=', $finishing_billing_code)
                                ->first();
            
        $finishingBillingDetail = FinishingBillingDetailModel::leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'finishing_billing_details.sales_order_no')
                                ->leftjoin('ledger_master', 'ledger_master.ac_code', '=', 'buyer_purchse_order_master.Ac_code')  
                                ->leftjoin('brand_master', 'brand_master.brand_id', '=', 'finishing_billing_details.brand_id') 
                                ->select('finishing_billing_details.*', 'brand_master.brand_name', 'ledger_master.ac_short_name')  
                                ->where('finishing_billing_details.finishing_billing_code', '=', $finishing_billing_code)
                                ->get();
            
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
       return view('FinishingBillingPrint', compact('finishingBillingMaster','finishingBillingDetail','FirmDetail'));
      
    }
    
}
