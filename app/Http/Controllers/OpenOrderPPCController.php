<?php

namespace App\Http\Controllers;

use App\Models\PPCMasterModel;
use App\Models\SAHPPCMasterModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\OpenOrderPPCDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\LineModel;
use Illuminate\Support\Facades\DB;

use Session;
 
class OpenOrderPPCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $openOrderPPCList =  DB::table('open_order_ppc_details')->select('*','ledger_master.ac_name')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'open_order_ppc_details.vendorId')
        ->get();

        return view('OpenOrderPPCMasterList', compact('openOrderPPCList'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        $salesOrderList = BuyerPurchaseOrderMasterModel::select('*')->where('buyer_purchse_order_master.job_status_id','=', '1')->get();
		return view('OpenOrderPPCMaster', compact('Ledger','salesOrderList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendorId = $request->input('vendorId');
            
        for($x=0; $x<count($vendorId); $x++) 
        {    
                $data1=array(
                    'vendorId'=>$request->vendorId[$x],
                    'sales_order_no'=>$request->sales_order_no[$x],
                    'vendorQty'=>$request->vendorQty[$x],
                    'userId'=>$request->userId,
                    'updated_at'=>date('Y-m-d'),
                );
                OpenOrderPPCDetailModel::insert($data1);
        }
        return redirect()->route('OpenOrderPPC.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(PPCMasterModel $PPCMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        $salesOrderList = BuyerPurchaseOrderMasterModel::select('*')->where('buyer_purchse_order_master.job_status_id','=', '1')->get();
        $OpenOrderPPCMasterList = OpenOrderPPCDetailModel::find($id);
        return view('OpenOrderPPCMaster', compact('OpenOrderPPCMasterList','Ledger','salesOrderList'));
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $data=array('vendorId'=>$request->vendorId,
    //      'sales_order_no'=>$request->sales_order_no,
    //      'vendorQty'=>$request->vendorQty,
    //      'userId'=>$request->userId,
    //      'updated_at'=>$request->updated_at);


    //     $OpenOrderPPCList = OpenOrderPPCDetailModel::findOrFail($request->input('openOrderPPCDetailId'));  
    //     $OpenOrderPPCList->fill($data)->save();

    //     return redirect()->route('OpenOrderPPC.index')->with('message', 'Update Record Succesfully');
    // }
    
    public function update(Request $request, $id)
    {
        $vendorId = $request->input('vendorId');
            
        for($x=0; $x<count($vendorId); $x++) 
        {    
                $data1=array(
                    'vendorId'=>$request->vendorId[$x],
                    'sales_order_no'=>$request->sales_order_no[$x],
                    'vendorQty'=>$request->vendorQty[$x],
                    'userId'=>$request->userId,
                    'updated_at'=>date('Y-m-d'),
                );
                $OpenOrderPPCList = OpenOrderPPCDetailModel::findOrFail($id);  
                $OpenOrderPPCList->fill($data1)->save();
        }
        
        return redirect()->route('OpenOrderPPC.index')->with('message', 'Update Record Succesfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PPCMasterModel  $PPCMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($openOrderPPCDetailId)
    {
        	$openOrderPPCDetailId=base64_decode($openOrderPPCDetailId);     
            $detail =OpenOrderPPCDetailModel::where('openOrderPPCDetailId',$openOrderPPCDetailId)->delete();
            Session::flash('delete', 'Deleted record successfully'); 
    }


    public function rptOpenOrderPPC()
    {
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','sales_order_costing_master.sam','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
        ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
        )
           
        ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.og_id','!=', '4')
        ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get();
        
         $vendorCount = OpenOrderPPCDetailModel::select('vendorQty','ledger_master.ac_name')->join('ledger_master', 'ledger_master.Ac_code', '=', 'open_order_ppc_details.vendorId')->groupby('vendorId')->get();
         
        return view('rptOpenOrderPPC', compact('Buyer_Purchase_Order_List','vendorCount'));
    }
}
