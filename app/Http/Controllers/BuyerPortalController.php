<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use DB;
use Session;


class BuyerPortalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    // function __construct()
    // {
        
    //     $this->middleware('database');
    // }

    public function BuyerAuth(Request $request)
    {
        $username=$request->post('username');
        $password=$request->post('password');
    
       
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        $userInfo = Login::where('username','=', $request->username)->where('password','=', $request->password)->where('user_type','=', 7)->first();  

       if(isset($userInfo->userId)){
            $request->session()->put('BUYER_LOGIN',true);
            $request->session()->put('userId',$userInfo->userId);
             $request->session()->put('user_type',$userInfo->user_type);
              $request->session()->put('vendorId',$userInfo->vendorId);
             $request->session()->put('username',$userInfo->username);
              $request->session()->put('year_id',$request->year_id);   
              //$this->BuyerAuth($userInfo);
           
            return redirect('BuyerPortal');
        }else{
           
            $request->session()->flash('error','Please enter valid login details');
            return redirect('buyerPortalLogin');
        }
           
    }
    
    public function index()
    { 
        $userId = Session::get('userId');
        //DB::enableQueryLog();

        $orderOutStandingData = DB::SELECT('select sum(total_qty) as order_qty, po_code,
                                ifnull((select sum(order_qty) FROM sale_transaction_detail WHERE Ac_code = '.$userId.' AND sales_order_no = buyer_purchse_order_master.tr_code),0) as dispatch_qty,
                                ifnull((select sum(size_qty_total) FROM packing_inhouse_detail WHERE Ac_code = '.$userId.' AND sales_order_no = buyer_purchse_order_master.tr_code),0) as ready_qty from buyer_purchse_order_master 
                                WHERE Ac_code = '.$userId.' AND job_status_id = 1 GROUP BY buyer_purchse_order_master.po_code');
         //dd(DB::getQueryLog());
         
        $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code,buyer_purchse_order_master.style_description,
            buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no  , merchant_master.merchant_name,buyer_purchse_order_master.style_img_path,
            buyer_purchse_order_master.Ac_code, ac_name, username,
            buyer_purchase_order_detail.color_id,color_master.color_name, sum(size_qty_total) as order_qty  
             
            FROM `buyer_purchse_order_master` 
            inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code 
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where buyer_purchse_order_master.job_status_id=1 and  buyer_purchse_order_master.og_id!=4
            group by buyer_purchse_order_master.tr_code
        ");
        return view('orderOutstandingList', compact('orderOutStandingData','ProductionOrderDetailList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function show(Login $login)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function edit(Login $login)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Login $login)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Login  $login
     * @return \Illuminate\Http\Response
     */
    public function destroy(Login $login)
    {
        //
    }
    
    public function buyerPortalLogin()
    {
        $financialYearList=DB::table('ken_year_databases')->select('year_id','year_name')->where('delflag',0)->get(); 
        
        return view('buyerPortalLogin',compact('financialYearList'));
    }
    
    public function LoadDailyProdDashboard(Request $request)
    { 
        
        $type = $request->filter; 
        $filter1 = '';  
        $html = '';
        
        if($type == 1)
        {
            $filter = ' AND buyer_purchse_order_master.job_status_id=1 AND buyer_purchse_order_master.og_id!=4';
        }
        else if($type == 2)
        {
            
            $filter = ' AND buyer_purchse_order_master.job_status_id=2 AND buyer_purchse_order_master.og_id!=4';
        } 
        
        $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code,buyer_purchse_order_master.style_description,
            buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no  , merchant_master.merchant_name,buyer_purchse_order_master.style_img_path,
            buyer_purchse_order_master.Ac_code, ac_name, username,
            buyer_purchase_order_detail.color_id,color_master.color_name, sum(size_qty_total) as order_qty  
             
            FROM `buyer_purchse_order_master` 
            inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code 
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where 1 ".$filter." group by buyer_purchse_order_master.tr_code");
            
        foreach($ProductionOrderDetailList as $row)   
        {
                     
             $TotalCutQty=DB::select("select  ifnull(sum(size_qty_total),0)  as total_cutting_qty from cut_panel_grn_detail where
             cut_panel_grn_detail.color_id='".$row->color_id."' and
             cut_panel_grn_detail.sales_order_no='".$row->tr_code."'");  
            
            
             $TotalCutIssueQty=DB::select("select ifnull(sum(size_qty_total),0) as total_cut_panel_issue  from cut_panel_issue_detail where
             cut_panel_issue_detail.color_id='".$row->color_id."' and
             cut_panel_issue_detail.sales_order_no='".$row->tr_code."'");
            
             $TotalStitchQty=DB::select("select ifnull(sum(size_qty_total),0) as total_stitching_qty from stitching_inhouse_detail where
             stitching_inhouse_detail.color_id='".$row->color_id."' and
             stitching_inhouse_detail.sales_order_no='".$row->tr_code."'"); 
              
             $TotalRejectQCQty=DB::select("select ifnull(sum(size_qty_total),0) as total_qcstitching_reject_qty from qcstitching_inhouse_reject_detail where
             qcstitching_inhouse_reject_detail.color_id='".$row->color_id."' and 
             qcstitching_inhouse_reject_detail.sales_order_no='".$row->tr_code."'");
             
             $TotalPassQCQty=DB::select("select ifnull(sum(size_qty_total),0) as total_qcstitching_pass_qty from qcstitching_inhouse_detail where
             qcstitching_inhouse_detail.color_id='".$row->color_id."' and 
             qcstitching_inhouse_detail.sales_order_no='".$row->tr_code."'");
             
             $TotalPackQty=DB::select("select ifnull(sum(size_qty_total),0) as total_packing_qty from packing_inhouse_detail
             inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
             where   packing_inhouse_detail.color_id='".$row->color_id."'
             and packing_inhouse_detail.sales_order_no='".$row->tr_code."'");
              
             
             $TotalShipQty=DB::select("select ifnull(sum(size_qty_total),0) as total_shipment_qty from carton_packing_inhouse_detail
             inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
             where   carton_packing_inhouse_detail.color_id='".$row->color_id."'
             and carton_packing_inhouse_detail.sales_order_no='".$row->tr_code."'
             and carton_packing_inhouse_master.endflag=1");  
            
             if($TotalShipQty[0]->total_shipment_qty > 0 && $TotalCutQty[0]->total_cutting_qty > 0)
             { 
                $cutToShip = round($TotalShipQty[0]->total_shipment_qty/$TotalCutQty[0]->total_cutting_qty,2);
             }
             else
             {
                $cutToShip = 0;
             }
             
             if($TotalShipQty[0]->total_shipment_qty > 0 && $row->order_qty > 0)
             { 
                $orderToShip = round($TotalShipQty[0]->total_shipment_qty/$row->order_qty,2);
             }
             else
             {
                $orderToShip = 0;
             }
                                            
              $html .='<tr>
                  <td class="text-center">'.$row->po_code.'</td>
                  <td class="text-center"><a href="javascript:void(0);">'.$row->tr_code.'</a></td>
                  <td>'.$row->style_description.'</td>
                  <td><a href="javascript:void(0);">'.$row->style_no.'</a></td>
                  <td class="text-right"><a href="javascript:void(0);"><img src="../images/'.$row->style_img_path.'" width="50" height="50"  /> </a></td>
                  <td>'.$row->color_name.'</td> 
                  <td class="text-right">'.(money_format('%!i',($row->order_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalCutQty[0]->total_cutting_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalStitchQty[0]->total_stitching_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalPassQCQty[0]->total_qcstitching_pass_qty))).'</td> 
                  <td class="text-right">-</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalRejectQCQty[0]->total_qcstitching_reject_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalCutQty[0]->total_cutting_qty - $TotalStitchQty[0]->total_stitching_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalPackQty[0]->total_packing_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalPassQCQty[0]->total_qcstitching_pass_qty - $TotalPackQty[0]->total_packing_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalShipQty[0]->total_shipment_qty))).'</td> 
                  <td class="text-right">'.(money_format('%!i',($TotalPackQty[0]->total_packing_qty - $TotalShipQty[0]->total_shipment_qty))).'</td> 
                  <td class="text-right">'.$cutToShip.'</td> 
                  <td class="text-right">'.$orderToShip.'</td> 
              </tr>';  
        }
        return response()->json(['html' => $html]);
    }
}
