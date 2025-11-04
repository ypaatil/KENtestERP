<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\SaleTransactionMasterModel;
use App\Models\SaleTransactionDetailModel;
use Session;
use Illuminate\Support\Facades\View;

setlocale(LC_MONETARY, 'en_IN'); 

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
  
        $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id','=',Session::get('userId'))
        ->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
       
            $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: select('buyer_purchse_order_master.*','sales_order_costing_master.sam',DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
            , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
            )
            ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('buyer_purchse_order_master.og_id','!=', '4')
             ->where('buyer_purchse_order_master.job_status_id','=', '1')
            ->get();
         
            $total_valuec=0;
            $total_qtyc=0;
            $total_order_min=0;
            $total_shipped_qtyc= 0;
            $total_shipped_min = 0;
            $total_balance_qty = 0;
            $total_balance_min = 0;
            $total_produce_qty = 0;
            $total_produce_min = 0;
            $FGStock = 0;
            $total_fabric_value = 0;
            $total_trim_value = 0;
            $total_FGStock = 0;
            
            foreach($Buyer_Purchase_Order_List as $row)
            {
                $total_valuec=$total_valuec + $row->order_value; 
                $total_qtyc=$total_qtyc+$row->total_qty; 
                $total_order_min= $total_order_min + (($row->sam) * ($row->total_qty)); 
                $total_shipped_qtyc=$total_shipped_qtyc+$row->shipped_qty;
                
                $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                
                $total_shipped_min = $total_shipped_min + ($Ship * $row->sam);
                $total_balance_qty = $total_balance_qty + $row->balance_qty;
                $total_balance_min = $total_balance_min + ($row->balance_qty * $row->sam);
                
                $FGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty' FROM `packing_inhouse_size_detail2` WHERE  packing_inhouse_size_detail2.sales_order_no = '". $row->tr_code."' GROUP by packing_inhouse_size_detail2.sales_order_no");
                    // dd(DB::getQueryLog());    
                 
                if($FGStockData != null)
                { 
                    $FGStock = $FGStockData[0]->packing_grn_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                }
                 
                $total_produce_qty = $total_produce_qty + ($row->balance_qty - $FGStock);
                $total_produce_min = $total_produce_min + ($row->sam * ($row->balance_qty - $FGStock)); 
                
                
            }
            
            $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
            foreach($FabricInwardDetails as $row)
            {
                $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
            }
            
            
            
            $TrimsInwardDetails = DB::select("select trimsInwardDetail.*,(select ifnull(sum(item_qty),0) as item_qty  from trimsOutwardDetail 
                where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty 
                from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
                inner join item_master on item_master.item_code=trimsInwardDetail.item_code
                inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
                
            foreach($TrimsInwardDetails as $rows)
            {
                $total_trim_value = $total_trim_value + ((($rows->out_qty) - ($rows->item_qty)) * $rows->item_rate);
            }
            
            $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                        DB::raw('sum(Gst_amount) as TotalGst'),
                        DB::raw('sum(Net_amount) as TotalNet'),
                        DB::raw('sum(total_qty) as TotalQty'))
                        ->where('sale_transaction_master.delflag','=', '0')
                        ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
                        ->get();
        
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
             
            $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                     (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty',
                    
                    
                    order_rate
                    FROM `packing_inhouse_size_detail2`
                    LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                    LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                    LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
                    GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
                // dd(DB::getQueryLog());    
                    
            foreach($FGStockData1 as $row1)
            {
                $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
            }
            return view('dashboard',compact('Authicateuser','total_qtyc','total_order_min','total_shipped_qtyc','total_shipped_min','total_balance_qty','total_balance_min','total_produce_qty','total_produce_min','total_fabric_value','total_trim_value','SaleTotal','MonthList','total_FGStock'));

    }
    
    
    public function dashboard2nd()
    {

//DB::enableQueryLog();
  
   $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
->where('form_auth.emp_id','=',Session::get('userId'))
->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);

/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/

        return view('dashboard2nd',compact('Authicateuser'));

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
      
    }

    public function WorkInProgressStatusList()
    {
       return view('WorkInProgressStatusList');
    }
     
    public function OrderStatusListDashboard()
    {
       return view('OrderStatusListDashboard');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    function logout(){
        if(session()->has('ADMIN_LOGIN')){
            session()->pull('ADMIN_LOGIN');
            return redirect('login');
        }
    }
    
    public function SpeededDashboard()
    {
        $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id','=',Session::get('userId'))
        ->get(['form_master.form_code','form_master.form_label','form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access','form_auth.delete_access']);
  
        return view('SpeededDashboard');
    }
    
    public function MDDashboard()
    {
        return view('MDDashboard');
    }
    
    public function MDDashboard1()
    {
        return view('MDDashboard1');
    } 
    
    public function AllDataMDDashboard()
    { 
        $html = '';
        // $style = ''; $head='';
        $table_head_data = DB::select("select distinct table_head from temp_order_sales_dashboard  ");
        foreach($table_head_data as $row)
        {  
             
            
            if($row->table_head == 1)
            {
                $head = '1. Order Booking';
                $style = '';
            }
            else if($row->table_head == 2)
            {
                $head = '2. Sales';      
                $style = 'style="background: goldenrod;"';
            }
            else if($row->table_head == 3)
            {
                $head = '3. OCR';        
                $style = 'style="background: #da2076c7;"';        
            }
            else if($row->table_head == 4)
            {
                $head = '4. Fabric';     
                $style = 'style="background: #70da20c7;"';           
            }
            else if($row->table_head == 5)
            {
                $head = '5. Trims';   
                $style = 'style="background: #ed6e13c7;"';             
            }
            else if($row->table_head == 6)
            {
                $head = '6. Operations'; 
                $style = 'style="background: #523ccfc7;"';               
            }
            else if($row->table_head == 7)
            {
                $head = '7. Open Order Status'; 
                $style = 'style="background: #cfa23cc7;"';               
            }
            else if($row->table_head == 8)
            {
                $head = '8. HR';     
                $style = 'style="background: #cf3c65c7;"';           
            }
            else if($row->table_head == 9)
            {
                $head = '8. Inventory Status'; 
                $style = 'style="background: #3c92cfc7;"';  
                
                
                
            //     $html ='<table width="100%" border="1" id="tbl1">
            //  <caption '.$style.'> '.$head.'</caption>
            //   <thead>
            //       <tr class="row1">
            //         <th nowrap>Key Indicators</th>
            //         <th class="col2">UOM</th>
            //         <th>Today</th>
            //         <th>Last Month End</th>
            //         <th>Last Year End</th>
            //       </tr>
            //   </thead>';
               
            }
            
            
             
             $html .='<table width="100%" border="1" id="tbl1">
             <caption '.$style.'> '.$head.'</caption>
              <thead>
                  <tr class="row1">
                    <th nowrap>Key Indicators</th>
                    <th class="col2">UOM</th>
                    <th>Today</th>
                    <th>Month To Date</th>
                    <th>Year To Date</th>
                  </tr>
              </thead>';
            
            
            
            
            
              $temp_table_data = DB::select("select * from temp_order_sales_dashboard WHERE table_head=".$row->table_head);
              $html .='<tbody>';
              $temp = '';
              foreach($temp_table_data as $row1)
              {     
                    if($row1->company_name != $temp)
                    {
                        $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$row1->company_name.'</td></tr>';
                    }
                    $html .='<tr>
                        <td>'.$row1->key_Indicators.'</td>
                        <td class="col2"> '.$row1->uom.' </td>';
                       
                       
                        // <td class="text-right">'.money_format("%!i",round($row1->today,2)).'</td>
                        // <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).'</td>
                        // <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).'</td>
                    
                  if($row1->uom=='Pcs')   
                  {   $html .='   
                        <td class="text-right">'.money_format("%!.0n",round($row1->today)).'</td>
                        <td class="text-right">'.money_format("%!.0n",round($row1->month_to_date)).'</td>
                        <td class="text-right">'.money_format("%!.0n",round($row1->year_to_date)).'</td>
                     </tr>';
                  }
                  else
                  {
                      $html .='   
                        <td class="text-right">'.money_format("%!i",round($row1->today,2)).'</td>
                        <td class="text-right">'.money_format("%!i",round($row1->month_to_date,2)).'</td>
                        <td class="text-right">'.money_format("%!i",round($row1->year_to_date,2)).'</td>
                     </tr>';
                      
                  }
                    
                      
                      
                      $temp = $row1->company_name;
              }
             $html .='</tbody></table>';
        }
          return response()->json(['html' => $html]);
    }
    
    public function AllDataMDDashboard1()
    { 
        $html = '';
        $today_stock_value = 0;
        $month_stock_value = 0;
        $year_stock_value = 0;
        
        $table_head_data = DB::select("select * from temp_order_sales_dashboard WHERE 1 GROUP BY table_head");
        foreach($table_head_data as $row)
        {  
            if($row->table_head == 1)
            {
                $head = 'Order Booking';
                $head1 = 'Order Booking';
                $style = 'style="color: #3d0cd5;"';
            }
            else if($row->table_head == 2)
            {
                $head = 'Sales';    
                $head1 = 'Sales';       
                $style = 'style="color: goldenrod;"';
            }
            else if($row->table_head == 3)
            {
                $head = 'OCR'; 
                $head1 = 'OCR';        
                $style = 'style="color: #da2076c7;"';        
            }
            else if($row->table_head == 4)
            {
                $head = 'Fabric';  
                $head1 = 'Fabric';     
                $style = 'style="color: #70da20c7;"';           
            }
            else if($row->table_head == 5)
            {
                $head = 'Trims'; 
                $head1 = 'Trims';   
                $style = 'style="color: #ed6e13c7;"';             
            }
            else if($row->table_head == 6)
            {
                $head = 'Stitching'; 
                $head1 = 'Cutting-Inhouse'; 
                $style = 'style="color: #523ccfc7;"';               
            }
            else if($row->table_head == 8)
            {
                $head = 'Open Order Status'; 
                $head1 = 'Open Order Status'; 
                $style = 'style="color: #cfa23cc7;"';               
            }
            else if($row->table_head == 10)
            {
                $head = 'Inventory Status'; 
                $head1 = 'Inventory Status';
                $style = 'style="color: #3c92cfc7;"';               
            }
            else if($row->table_head == 7)
            {
                $head = 'Packing'; 
                $head1 = 'Packing';
                $style = 'style="color: #3c92cfc7;"';               
            }
            
            $html .='';
                 
            $temp_table_data = DB::select("select * from temp_order_sales_dashboard WHERE table_head=".$row->table_head);
            $totalCount = count($temp_table_data)/2;
           
            $tc = explode(".", $totalCount + 1);
            $temp = '';
            $cnt = 1;
            
            if($head1=='Inventory Status')
             { 
                $html .='<table width="100%" id="tbl1">
                 <tr style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080; " ><td >'.$head1.'</td>
                 <td  style="text-align:right;">Today(Last Day)</td> <td style="text-align:right;">Last Month</td> <td  style="text-align:right;">Last Year</td> </tr>';
             }
             else
             {
                 $html .='<table width="100%" id="tbl1">
                 <tr style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080;" ><td colspan="5"> '.$head1.'</td></tr>';
             }
            foreach($temp_table_data as $row1)
            { 
                // if($row1->company_name != $temp)
                // {
                //     $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$row1->company_name.'</td></tr>';
                // }
                if($row1->company_name != $temp)
                {
                    $last ='style="border-bottom: 4px solid;"';
                }
                else
                {
                    $last = '';
                }
                if(count($temp_table_data) == $cnt )
                {
                    $border ='style=" "';
                }
                else
                {
                    $border ='';
                }
                
                if($row1->company_name != $temp)
                {
                    $html .='<tr style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#000080;"><td colspan="5"  >'.$head.'-'.$row1->company_name.'</td></tr>';
                }
                   
            
                $html .='<tr '.$border.' >';
                
                if($row1->uom=='Pcs')   
                {
                   $pcs = '(Pcs)';
                }
                else
                {
                    $pcs = '';
                }
                
                if($row1->uom=='Rs')   
                {
                   $rs = '(Rs)';
                }
                else
                {
                    $rs = '';
                }
                
                if($row1->uom=='%')   
                {
                   $per = '(%)';
                }
                else
                {
                    $per = '';
                } 
                
                if($row1->uom=='Mtr')   
                {
                   $meter = '(Mtr)';
                }
                else
                {
                    $meter = '';
                }
                $html .=' 
                         <td style="font-weight:bold;">'.$row1->key_Indicators.''.$pcs.''.$rs.''.$per.''.$meter.'</td>';
                
                  if($row1->today == 0 || $row1->today == 0.00)
                  {
                        $tunit = '';
                  }
                  else
                  {
                        $tunit = 'L';
                  }
                     
                  if($row1->month_to_date == 0 || $row1->month_to_date == 0.00)
                  {
                     $munit = '';
                  }
                  else
                  {
                        $munit = 'L';
                  }
                    
                  if($row1->year_to_date == 0 || $row1->year_to_date == 0.00)
                  {
                        $yunit = '';
                  }
                  else
                  {
                        $yunit = 'L';
                  }
                      

                  if($row1->uom=='Pcs')   
                  {  
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4  || $row->table_head == 5 || $row->table_head == 6)
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/GetOCRSummaryReport';
                      }
                      else if($row->table_head == 4)
                      {
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource")
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          $redirect = '/FabricStockDataMD';
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          $redirect = '/TrimsStockData';
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                          $redirect = '/GetVendorWorkOrderStock';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          $redirect = '/FGStockReport';
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      
                     $html .='   
                        <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                     </tr>';
                  }
                  else if($row1->uom!='%')
                  {
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 || $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/GetOCRSummaryReport';
                      }
                      else if($row->table_head == 4)
                      {
                        
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource" || $row->table_head == 7)
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                                            
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          $redirect = '/FabricStockDataMD';
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          $redirect = '/TrimsStockData';
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                          $redirect = '/GetVendorWorkOrderStock';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          $redirect = '/FGStockReport';
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      
                      $html .='   
                        <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                     </tr>';
                  }
                  else if($row1->uom='%')
                  { 
                      
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 || $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                      if($row->table_head == 1)
                      {
                          $redirect = '/TotalSalesOrderDetailMDDashboard';
                      }
                      else if($row->table_head == 2)
                      {
                          $redirect = '/SaleFilterReportMD';
                      }
                      else if($row->table_head == 3)
                      {
                          $redirect = '/GetOCRSummaryReport';
                      }
                      else if($row->table_head == 4)
                      {
                          if($row1->key_Indicators === "Issued Quantity-Meter" || $row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/FabricOutwardDataMD';
                          }
                          else
                          {
                            $redirect = '/FabricGRNDataMD';
                          }
                      }
                      else if($row->table_head == 5)
                      {
                          if($row1->key_Indicators === "Issued Value")
                          {
                              $redirect = '/TrimsOutwardDataMD';
                          }
                          else
                          {
                               $redirect = '/TrimsGRNDataMD';
                          }
                      }
                      else if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT - 1")
                      {
                          $redirect = '/StitchingGRNDashboardMD/56';
                      }
                      if($row1->company_name === "ANSH APPAREL")
                      {
                          $redirect = '/StitchingGRNDashboardMD/69';
                      }
                      if($row1->company_name === "NANDINI FASHION")
                      {
                          $redirect = '/StitchingGRNDashboardMD/110';
                      }
                      if($row1->company_name === "KEN GLOBAL DESIGNS - UNIT  - 2")
                      {
                          $redirect = '/StitchingGRNDashboardMD/115';
                      }
                      
                      if($row1->company_name === "Outsource")
                      {
                          $redirect = '/PackingGRNReportMD';
                      }
                                           
                      if($row1->key_Indicators === "Fabric - Moving Quantity" || $row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Quantity" || $row1->key_Indicators === "Fabric - Non - Moving Value")
                      {
                          $redirect = '/FabricStockDataMD';
                      } 
                      
                      if($row1->key_Indicators === "Trims - Moving Value" || $row1->key_Indicators === "Trims - Non - Moving Value")
                      {
                          $redirect = '/TrimsStockData';
                      } 
                      
                      if($row1->key_Indicators === "WIP -  Quantity" || $row1->key_Indicators === "WIP -  Value")
                      {
                          $redirect = '/GetVendorWorkOrderStock';
                      } 
                      
                      if($row1->key_Indicators === "FG - Moving Quantity" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Quantity" || $row1->key_Indicators === "FG - Non - Moving Value")
                      {
                          $redirect = '/FGStockReport';
                      }
                      
                      if($row1->key_Indicators === "Cutting Quantity Pcs")
                      {
                          $redirect = '/CutPanelGRNReportMD';
                      }
                      
                      if($row1->key_Indicators === "Cut Panel Issue Quantity Pcs")
                      {
                          $redirect = '/CutPanelIssueReportMD';
                      }
                      
                      $html .='   
                        <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.money_format('%!.0n',round($row1->today)).' '.$tunit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).'</a></td>
                     </tr>';
                  }
                  else 
                  {
                        
                      if($row->table_head == 1 || $row->table_head == 2 || $row->table_head == 4 || $row->table_head == 5 || $row->table_head == 6 )
                      {
                          $tunit = "";
                          $tobunit = money_format('%!.0n',round($row1->today));
                      }
                      else
                      {
                          $tobunit = money_format("%!i",round($row1->today,2)).' '.$tunit;
                      }
                      
                       
                      if($row->table_head == 8)
                      {
                          $redirect = '/OpenSalesOrderDetailMDDashboard';
                      }
                      else
                      {
                          $redirect = '';
                      }
                        
                        
                      $html .='   
                        <td class="text-right"><a href="'.$redirect.'/d" target="_blank" >'.$tobunit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/m" target="_blank" >'.money_format("%!i",round($row1->month_to_date,2)).' '.$munit.'</a></td>
                        <td class="text-right"><a href="'.$redirect.'/y" target="_blank" >'.money_format("%!i",round($row1->year_to_date,2)).' '.$yunit.'</a></td>
                     </tr>';
                      
                  }
                  
                  if($row1->key_Indicators === "Fabric - Moving Value" || $row1->key_Indicators === "Fabric - Non - Moving Value" || $row1->key_Indicators === "Trims - Moving Value" 
                      || $row1->key_Indicators === "Trims - Non - Moving Value" || $row1->key_Indicators === "FG - Moving Value" || $row1->key_Indicators === "FG - Non - Moving Value" 
                      || $row1->key_Indicators === "WIP -  Value")
                  {
                      $today_stock_value = $today_stock_value + $row1->today;
                      $month_stock_value = $month_stock_value + $row1->month_to_date;
                      $year_stock_value = $year_stock_value + $row1->year_to_date;
                  }
                  $temp = $row1->company_name;
                  $cnt++;
            } 
          
        }
         $html .='<tr style="border-bottom: 4px solid; border-top: 4px solid; font-weight:bold; color:#f90808;"> 
                     <td><strong>Stock Value</strong></td>
                     <td class="text-right">'.$today_stock_value.' L</td>
                     <td class="text-right">'.$month_stock_value.' L</td>
                     <td class="text-right">'.$year_stock_value.' L</td>
                 </tr>';  
          return response()->json(['html' => $html]);
    }
    public function refreshData()
    {
        //DB::enableQueryLog();
       
        //dd(DB::getQueryLog());
        $this->orderBookingDashboard();
        $this->salesMDDashboard();
        $this->ocrMDDashboard();
        $this->fabricMDDashboard();
        $this->trimsMDDashboard();
        $this->operationMDDashboard();
        $this->openOrderMDDashboard();
        $this->inventoryStatusMDDashboard();
        $this->InventoryWIPValue();
        return 1; 
        
    }
    
    public function orderBookingDashboard()
    {
         DB::table('temp_order_sales_dashboard')->where('table_head', 1)->delete();
         
        $Buyer_Purchase_Order_List = DB::select("select buyer_purchse_order_master.tr_code, buyer_purchse_order_master.order_rate, buyer_purchse_order_master.sam as sam
                from buyer_purchse_order_master WHERE job_status_id!=3 AND buyer_purchse_order_master.og_id != 4");
       
        
        $html = "";
        $order_qty=0; $order_value=0; $order_min=0;
        $tOrder_qty=0; $tOrder_value=0; $tOrder_min=0;
        $yOrder_qty=0; $yOrder_value=0; $yOrder_min=0;
        
         $yearMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where  job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and buyer_purchse_order_master.order_received_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
            and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
            
        $monthMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where  MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE()) AND buyer_purchse_order_master.og_id != 4");  
            
        $todayMinData = DB::select("select sum((buyer_purchase_order_detail.size_qty_total * buyer_purchse_order_master.sam)) as total_min
            from buyer_purchase_order_detail  
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code
            where buyer_purchse_order_master.order_received_date ='".date('Y-m-d')."' AND buyer_purchse_order_master.og_id != 4");          
         
         
        foreach($Buyer_Purchase_Order_List as $row)
        {
            
              $monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty from buyer_purchse_order_master 
                where MONTH(buyer_purchse_order_master.order_received_date)=MONTH(CURRENT_DATE()) and YEAR(buyer_purchse_order_master.order_received_date)=YEAR(CURRENT_DATE()) 
                AND job_status_id!=3 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.tr_code='".$row->tr_code."'");
                 
              $todayData = DB::select("select ifnull(sum(total_qty),0) as order_qty,order_rate from buyer_purchse_order_master 
                where job_status_id!=3 AND buyer_purchse_order_master.order_received_date ='".date('Y-m-d')."' AND buyer_purchse_order_master.og_id != 4");
            
            //DB::enableQueryLog();
              $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(order_value),0) as total_order_value from buyer_purchse_order_master 
                  where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 
                  and order_received_date between (select fdate from financial_year_master where financial_year_master.fin_year_id=3) 
                  and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
              // dd(DB::getQueryLog());
        
              
              $tOrder_qty = $todayData[0]->order_qty;
              $tOrder_value =  ($todayData[0]->order_qty* $todayData[0]->order_rate);
              
              $order_qty = $order_qty + ($monthData[0]->order_qty/100000);
              $order_value = $order_value + ($monthData[0]->order_qty* $row->order_rate)/100000;
              
              $yOrder_qty =  ($yearData[0]->order_qty/100000);
              $yOrder_value =   ($yearData[0]->total_order_value)/100000;
              
              
        }
              $tOrder_min = $todayMinData[0]->total_min;
              $order_min =  $monthMinData[0]->total_min/100000;
              $yOrder_min = $yearMinData[0]->total_min/100000;
              
        //echo $tOrder_qty;exit;
            $html .='<tr>
                        <td> Quantity </td>
                        <td>'.money_format('%!.0n', round($tOrder_qty)).'</td>
                        <td>'.money_format('%!.0n', round($order_qty,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_qty,2)).'</td>
                      </tr>
                      <tr>
                        <td> Value </td>
                        <td>'.money_format('%!.0n', round($tOrder_value)).'</td>
                        <td>'.money_format('%!.0n', round($order_value,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_value,2)).'</td>
                      </tr>
                      <tr>
                        <td> Minutes </td>
                        <td>'.money_format('%!.0n', round($tOrder_min)).'</td>
                        <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_min,2)).'</td>
                      </tr>';
        
            $orderBookingArr = array(
                array('Quantity','Pcs',round($tOrder_qty),round($order_qty,2),round($yOrder_qty,2),1,""),
                array('Value','Rs',round($tOrder_value),round($order_value,2),round($yOrder_value,2),1,""),
                array('Minutes','',round($tOrder_min),round($order_min,2),round($yOrder_min,2),1,"")
            );      
            $this->tempInsertData($orderBookingArr);
        
        
        return response()->json(['html' => $html]);
    }
    
    public function salesMDDashboard()
    {
        
        DB::table('temp_order_sales_dashboard')->where('table_head', 2)->delete();
        $Sales_List = DB::select("select sale_transaction_detail.sales_order_no, sale_transaction_detail.order_rate, buyer_purchse_order_master.sam as sam
                from  sale_transaction_master INNER JOIN sale_transaction_detail ON sale_transaction_detail.sale_code = sale_transaction_master.sale_code
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no");
       
        
        $html = "";
        $order_qty=0; $order_value=0; $order_min=0;
        $tOrder_qty=0; $tOrder_value=0; $tOrder_min=0;
        $yOrder_qty=0; $yOrder_value=0; $yOrder_min=0;
        
        
        $yearMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where  sale_transaction_master.sale_date between (select fdate from financial_year_master 
            where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
            
        $monthMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where  MONTH(sale_transaction_detail.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_detail.sale_date)=YEAR(CURRENT_DATE())");  
            
        $todayMinData = DB::select("select sum((order_qty * buyer_purchse_order_master.sam)) as total_min
            from sale_transaction_detail  
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no
            where sale_transaction_detail.sale_date ='".date('Y-m-d')."'");          
         
        foreach($Sales_List as $row)
        {
             
              
              //$monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty from sale_transaction_master 
               // where MONTH(sale_transaction_master.sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE())  ");
              
              $monthData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
             
              $todayData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value from sale_transaction_master 
                where sale_transaction_master.sale_date ='".date('Y-m-d')."'");
                
            //   $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty from sale_transaction_master 
            //     where YEAR(sale_transaction_master.sale_date)=YEAR(CURRENT_DATE())  ");
              $yearData = DB::select("select ifnull(sum(total_qty),0) as order_qty, ifnull(sum(Gross_amount),0) as total_sale_value 
                from sale_transaction_master where sale_date between (select fdate from financial_year_master 
                where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
           
                //DB::enableQueryLog();
              //dd(DB::getQueryLog());
              
              $tOrder_qty =  ($todayData[0]->order_qty);
              $tOrder_value = $todayData[0]->total_sale_value;
              
              
              $order_qty = $monthData[0]->order_qty/100000;
              $order_value = $monthData[0]->total_sale_value/100000;
              
              $yOrder_qty = $yearData[0]->order_qty/100000;
              $yOrder_value = $yearData[0]->total_sale_value/100000;
              
              //echo $yOrder_min;exit;
             
        }
    
              $tOrder_min = $todayMinData[0]->total_min;
              $order_min = $monthMinData[0]->total_min/100000;
              $yOrder_min = $yearMinData[0]->total_min/100000;

            $html .='<tr>
                        <td> Quantity </td>
                        <td>'.money_format('%!.0n', round($tOrder_qty)).'</td>
                        <td>'.money_format('%!.0n', round($order_qty,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_qty,2)).'</td>
                      </tr>
                      <tr>
                        <td> Value </td>
                        <td>'.money_format('%!.0n', round($tOrder_value)).'</td>
                        <td>'.money_format('%!.0n', round($order_value,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_value,2)).'</td>
                      </tr>
                      <tr>
                        <td> Minutes </td>
                        <td>'.money_format('%!.0n', round($tOrder_min)).'</td>
                        <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                        <td>'.money_format('%!.0n', round($yOrder_min,2)).'</td>
                      </tr>';
        
            $salesArr = array(
                array('Quantity','Pcs',round($tOrder_qty),round($order_qty,2),round($yOrder_qty,2),2,""),
                array('Value','Rs',round($tOrder_value),round($order_value,2),round($yOrder_value,2),2,""),
                array('Minutes','',round($tOrder_min),round($order_min,2),round($yOrder_min,2),2,"")
            );  
           // DB::enableQueryLog();
            $this->tempInsertData($salesArr);
            //dd(D::getQueryLog());
        return response()->json(['html' => $html]);
    }
    
    public function ocrMDDashboard()
    {
        
        DB::table('temp_order_sales_dashboard')->where('table_head', 3)->delete();
        
        $html = "";
        $cut_to_ship=0; $order_to_ship=0; $order_min=0;
        $tcut_to_ship=0; $torder_to_ship=0; $torder_min=0;
        $ycut_to_ship=0; $yorder_to_ship=0; $yorder_min=0;
    
        $overAllData = DB::select("select ifnull(sum(size_qty_total),0) as size_qty_total  from buyer_purchase_order_detail  
        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_detail.tr_code WHERE buyer_purchse_order_master.og_id != 4");
 
        $monthData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty  from cut_panel_grn_detail 
            where MONTH(cut_panel_grn_detail.cpg_date)= MONTH(CURRENT_DATE()) and YEAR(cut_panel_grn_detail.cpg_date)=YEAR(CURRENT_DATE())");
        
      
        $todayData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty from cut_panel_grn_detail 
            where cut_panel_grn_detail.cpg_date ='".date('Y-m-d')."'");

        $yearData = DB::select("select ifnull(sum(size_qty_total),0) as cut_order_qty from cut_panel_grn_detail 
            where YEAR(cut_panel_grn_detail.cpg_date)=YEAR(CURRENT_DATE())");

        $monthInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail 
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            where MONTH(carton_packing_inhouse_detail.cpki_date)=MONTH(CURRENT_DATE()) and YEAR(carton_packing_inhouse_detail.cpki_date)=YEAR(CURRENT_DATE())
            AND carton_packing_inhouse_master.endflag=1  ");
     
        $todayInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail 
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            where carton_packing_inhouse_detail.cpki_date ='".date('Y-m-d')."'  AND carton_packing_inhouse_master.endflag=1");
            
        $yearInvoiceData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail  
            INNER JOIN carton_packing_inhouse_master ON carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
            where YEAR(carton_packing_inhouse_detail.cpki_date)=YEAR(CURRENT_DATE())   AND carton_packing_inhouse_master.endflag=1");
    
        $torder_to_ship = ((($todayInvoiceData[0]->invoice_qty/$overAllData[0]->size_qty_total)*100));
        $order_to_ship = ((($monthInvoiceData[0]->invoice_qty/$overAllData[0]->size_qty_total)*100));
        $yorder_to_ship = ((($yearInvoiceData[0]->invoice_qty/$overAllData[0]->size_qty_total)*100));
        
        if($monthData[0]->cut_order_qty > 0)
        {
            $cut_to_ship = ((($monthInvoiceData[0]->invoice_qty/$monthData[0]->cut_order_qty)*100));
        }
        else
        {
             $cut_to_ship = 0;
        }
          
        if($todayData[0]->cut_order_qty > 0)
        {
            $tcut_to_ship = ((($todayInvoiceData[0]->invoice_qty/$todayData[0]->cut_order_qty)*100));
        }
        else
        {
            $tcut_to_ship = 0;
        }
         
        if($yearData[0]->cut_order_qty > 0)
        {
            $ycut_to_ship =  ((($yearInvoiceData[0]->invoice_qty/$yearData[0]->cut_order_qty)*100));
        }
        else
        {
             $ycut_to_ship = 0;
        } 
              
        $html .='<tr>
                    <td> Cut to Ship </td>
                    <td>'.money_format('%!.0n', round($tcut_to_ship,2)).'</td>
                    <td>'.money_format('%!.0n', round($cut_to_ship,2)).'</td>
                    <td>'.money_format('%!.0n', round($ycut_to_ship,2)).'</td>
                  </tr>
                  <tr>
                    <td> Order to Ship </td>
                    <td>'.money_format('%!.0n', round($torder_to_ship,2)).'</td>
                    <td>'.money_format('%!.0n', round($order_to_ship,2)).'</td>
                    <td>'.money_format('%!.0n', round($yorder_to_ship,2)).'</td>
                  </tr>
                  <tr>
                    <td> No. of Orders Pending for OCR </td>
                    <td>'.money_format('%!.0n', round($torder_min,2)).'</td>
                    <td>'.money_format('%!.0n', round($order_min,2)).'</td>
                    <td>'.money_format('%!.0n', round($yorder_min,2)).'</td>
                  </tr>';
        
            $ocrArr = array(
                array('Cut to Ship','%',round($tcut_to_ship,2),round($cut_to_ship,2),round($ycut_to_ship,2),3,""),
                array('Order to Ship','%',round($torder_to_ship,2),round($order_to_ship,2),round($yorder_to_ship,2),3,""),
                array(' No. of Orders Pending for OCR','',round($torder_min,2),round($order_min,2),round($yorder_min,2),3,"")
            );      
            $this->tempInsertData($ocrArr);
        
        return response()->json(['html' => $html]);
    }
    
    public function fabricMDDashboard()
    {
        DB::table('temp_order_sales_dashboard')->where('table_head', 4)->delete();
        $html = '';
        $todayData = DB::select("select ifnull(sum(meter * item_rate),0) as total_value,sum(meter) as meter from inward_details 
         WHERE in_date = '".date('Y-m-d')."'");
        
        $monthData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from inward_details 
        WHERE MONTH(inward_details.in_date) = MONTH(CURRENT_DATE()) and YEAR(inward_details.in_date)=YEAR(CURRENT_DATE())");
        
        $yearData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from inward_details 
        WHERE in_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        
        $tvalue = ($todayData[0]->total_value);
        $mvalue = ($monthData[0]->total_value)/100000;
        $yvalue = ($yearData[0]->total_value)/100000;
        
        $todayOutData = DB::select("select ifnull(sum(meter * item_rate),0) as total_value,sum(meter) as meter from fabric_outward_details 
         WHERE fout_date = '".date('Y-m-d')."'");
        
        $monthOutData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from fabric_outward_details 
        WHERE MONTH(fabric_outward_details.fout_date) = MONTH(CURRENT_DATE()) and YEAR(fabric_outward_details.fout_date)=YEAR(CURRENT_DATE())");
        
        $yearOutData = DB::select("select sum(meter * item_rate) as total_value, sum(meter) as meter from fabric_outward_details 
        where fout_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        $tOutValue = ($todayOutData[0]->total_value);
        $mOutValue = ($monthOutData[0]->total_value)/100000;
        $yOutValue = ($yearOutData[0]->total_value)/100000;
        
        $tGrnMeterQty = $todayData[0]->meter;
        $mGrnMeterQty = $monthData[0]->meter/100000;
        $yGrnMeterQty = $yearData[0]->meter/100000;
        
        $tIssueMeterQty = $todayOutData[0]->meter;
        $mIssueMeterQty = $monthOutData[0]->meter/100000;
        $yIssueMeterQty = $yearOutData[0]->meter/100000;
        
        $html .='<tr>
                <td> GRN Quantity-Meter</td>
                <td>'.money_format('%!.0n', round($tGrnMeterQty)).'</td>
                <td>'.money_format('%!.0n', round($mGrnMeterQty,2)).'</td>
                <td>'.money_format('%!.0n', round($yGrnMeterQty,2)).'</td>
              </tr>
              <tr>
                <td> GRN Value </td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td>  Issued Quantity-Meter </td>
                 <td>'.money_format('%!.0n', round($tIssueMeterQty)).'</td>
                <td>'.money_format('%!.0n', round($mIssueMeterQty,2)).'</td>
                <td>'.money_format('%!.0n', round($yIssueMeterQty,2)).'</td>
              </tr>
              <tr>
                <td>  Issued Value   </td>
                <td>'.money_format('%!.0n', round($tOutValue)).'</td>
                <td>'.money_format('%!.0n', round($mOutValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yOutValue,2)).'</td>
              </tr>';
        
            $fabricArr = array(
                array('GRN Quantity-Meter','',round($tGrnMeterQty),round($mGrnMeterQty,2),round($yGrnMeterQty,2),4,""),
                array('GRN Value','Rs',round($tvalue),round($mvalue,2),round($yvalue,2),4,""),
                array('Issued Quantity-Meter','',round($tIssueMeterQty),round($mIssueMeterQty,2),round($yIssueMeterQty,2),4,""),
                array('Issued Value','Rs',round($tOutValue),round($mOutValue,2),round($yOutValue,2),4,""),
            );      
            $this->tempInsertData($fabricArr); 
            
        return response()->json(['html' => $html]);
    }
    
    public function trimsMDDashboard()
    {
        DB::table('temp_order_sales_dashboard')->where('table_head', 5)->delete();
        $html = '';
        $todayData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsInwardDetail 
         WHERE trimDate = '".date('Y-m-d')."'");
        
        $monthData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsInwardDetail 
        WHERE MONTH(trimsInwardDetail.trimDate) = MONTH(CURRENT_DATE()) and YEAR(trimsInwardDetail.trimDate)=YEAR(CURRENT_DATE())");
        
        $yearData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsInwardDetail 
        WHERE trimDate between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        $tvalue = ($todayData[0]->total_value);
        $mvalue = ($monthData[0]->total_value)/100000;
        $yvalue = ($yearData[0]->total_value)/100000;
        
        $todayOutData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsOutwardDetail 
         WHERE tout_date = '".date('Y-m-d')."'");
        
        $monthOutData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsOutwardDetail 
        WHERE MONTH(trimsOutwardDetail.tout_date) = MONTH(CURRENT_DATE()) and YEAR(trimsOutwardDetail.tout_date)=YEAR(CURRENT_DATE())");
        
        $yearOutData = DB::select("select sum(item_qty * item_rate) as total_value, sum(item_qty) as item_qty from trimsOutwardDetail 
        WHERE tout_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        $tOutValue = ($todayOutData[0]->total_value);
        $mOutValue = ($monthOutData[0]->total_value)/100000;
        $yOutValue = ($yearOutData[0]->total_value)/100000;
        
        $html .='<tr>
                <td> GRN Value </td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td> Issued Value </td>
                <td>'.money_format('%!.0n', round($tOutValue)).'</td>
                <td>'.money_format('%!.0n', round($mOutValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yOutValue,2)).'</td>
              </tr>';
              
            $trimsArr = array(
                array(' GRN Value','Rs',round($tvalue),round($mvalue,2),round($yvalue,2),5,""),
                array('Issued Value','Rs',round($tOutValue),round($mOutValue,2),round($yOutValue,2),5,"")
            );      
            $this->tempInsertData($trimsArr); 
            
        return response()->json(['html' => $html]);
    }
    
    public function operationMDDashboard()
    {
         $html = '';
         $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at
         FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        DB::table('temp_order_sales_dashboard')->where('table_head', 6)->delete();
        DB::table('temp_order_sales_dashboard')->where('table_head', 7)->delete();
       
        
       
        $todayData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
         WHERE vendorId=56 and cpg_date = '".date('Y-m-d')."'");
        
        $monthData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
        WHERE vendorId=56 and MONTH(cut_panel_grn_size_detail2.cpg_date) = MONTH(CURRENT_DATE()) and YEAR(cut_panel_grn_size_detail2.cpg_date)=YEAR(CURRENT_DATE())");
        
        $yearData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_grn_size_detail2 
        WHERE vendorId=56 and cut_panel_grn_size_detail2.cpg_date between '".$Financial_Year[0]->fdate."' and NOW()");
        
        $tvalue = ($todayData[0]->size_qty);
        $mvalue = ($monthData[0]->size_qty)/100000;
        $yvalue = ($yearData[0]->size_qty)/100000;
        
        $todayIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
         WHERE vendorId=56 and cpi_date = '".date('Y-m-d')."'");
        
        $monthIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
        WHERE vendorId=56 and MONTH(cut_panel_issue_size_detail2.cpi_date) = MONTH(CURRENT_DATE()) and YEAR(cut_panel_issue_size_detail2.cpi_date)=YEAR(CURRENT_DATE())");
        
        $yearIssueData = DB::select("select ifnull(sum(size_qty),0) as size_qty from cut_panel_issue_size_detail2 
        WHERE vendorId=56 and cut_panel_issue_size_detail2.cpi_date between '".$Financial_Year[0]->fdate."' and NOW()");
        
        $tIssueValue = ($todayIssueData[0]->size_qty);
        $mIssueValue = ($monthIssueData[0]->size_qty)/100000;
        $yIssueValue = ($yearIssueData[0]->size_qty)/100000;
        
         //---------------------------------------------Over All-----------------------------------------
         
         
                
                $todayKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code=stitching_inhouse_master.sales_order_no) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where 
                qcstitching_inhouse_reject_detail.qcsti_date= '".date('Y-m-d')."' and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code 
                WHERE stitching_inhouse_size_detail2.sti_date = '".date('Y-m-d')."' and stitching_inhouse_size_detail2.vendorId in (56,115,69,110)
                group by stitching_inhouse_master.sales_order_no");
        
                $monthKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                MONTH(qcstitching_inhouse_reject_detail.qcsti_date)=MONTH(CURRENT_DATE()) 
                and YEAR(qcstitching_inhouse_reject_detail.qcsti_date)=YEAR(CURRENT_DATE()) and 
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE   stitching_inhouse_size_detail2.vendorId in (56,115,69,110) and MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE())  
                group by stitching_inhouse_master.sales_order_no");
                
                $yearKen_1DataOverAll = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where 
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.qcsti_date  between '".$Financial_Year[0]->fdate."'  and NOW() and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE   stitching_inhouse_size_detail2.vendorId in (56,115,69,110) and  stitching_inhouse_size_detail2.sti_date between '".$Financial_Year[0]->fdate."'  and NOW()
                group by stitching_inhouse_master.sales_order_no");
                
                $todayKen_1MinOverAll=0;
                $todayKen_1Plant_EffiOverAll=0;
                $todayWorkerMinOverAll=0;
                $todaySizeQtyOverAll=0;
                $todaysRejectQtyOverAll=0;
                $todaysRejPerOverAll=0;
                $notOverAll=1;
                foreach($todayKen_1DataOverAll as $row1)
                {
                    $todaySizeQtyOverAll = $todaySizeQtyOverAll + $row1->size_qty;
                    if(($row1->size_qty * $row1->sam) !=0 &&  $row1->total_workers!=0)
                    {   
                        $todayWorkerMinOverAll= $todayWorkerMinOverAll + $row1->total_workers * 480;
                        $todayKen_1MinOverAll =$todayKen_1MinOverAll + $row1->size_qty * $row1->sam;
                        $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll + ((($row1->size_qty * $row1->sam)/($row1->total_workers * 480))) * 100;
                        $todaysRejectQtyOverAll = $todaysRejectQtyOverAll + $row1->rejectionQty;
                        $notOverAll++;
                    }
                    else
                    {
                        $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll + 0;
                    }
                 
                }
                
                if($todaysRejectQtyOverAll!=0 && $todaySizeQtyOverAll!=0)
               { $todaysRejPerOverAll =  round((($todaysRejectQtyOverAll/ $todaySizeQtyOverAll)*100),2);}else{$todaysRejPerOverAll=0;}
                
                 if($todayKen_1Plant_EffiOverAll!=0 && ($notOverAll-1)!=0)
                {
                    $todayKen_1Plant_EffiOverAll = $todayKen_1Plant_EffiOverAll / ($notOverAll-1);
                }
                else
                {
                    $todayKen_1Plant_EffiOverAll=0;
                }
                
                
                $monthKen_1MinOverAll=0;
                $monthSizeQtyOverAll=0;
                $monthworkersMinOverAll=0;
                $monthRejectQtyOverAll=0;
                $monthRejPerOverAll=0;
                $monthKen_1Plant_EffiOverAll=0; $noOverAll=1;
                foreach($monthKen_1DataOverAll as $row2)
                {
                    $monthSizeQtyOverAll = $monthSizeQtyOverAll + $row2->size_qty;
                    if(($row2->size_qty * $row2->sam) !=0 &&  $row2->total_workers!=0)
                    {   $monthRejectQtyOverAll= $monthRejectQtyOverAll + $row2->rejectionQty;
                        $monthKen_1MinOverAll =$monthKen_1MinOverAll +  $row2->size_qty * $row2->sam;
                        $monthworkersMinOverAll= $monthworkersMinOverAll + ($row2->total_workers * 480);
                        $monthKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + ((($row2->size_qty * $row2->sam)/($row2->total_workers * 480))) * 100;
                         
                        $noOverAll++;
                    }
                    else
                    {
                        $monthKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + 0;
                    }
                  
                }
                
                
                 if($monthRejectQtyOverAll!=0 && $monthSizeQtyOverAll!=0)
               { $monthRejPerOverAll=  round((($monthRejectQtyOverAll/ $monthSizeQtyOverAll)*100),2);}else{$$monthRejPerOverAll=0;}
                
                //echo $monthRejPerOverAll;
                if($monthKen_1Plant_EffiOverAll!=0 && ($noOverAll-1)!=0)
                {
                     $monthKen_1Plant_EffiOverAll=  $monthKen_1Plant_EffiOverAll/ ($noOverAll-1);
                }
                else
                {
                    $monthKen_1Plant_EffiOverAll=0;
                }
               
                 $yearKen_1MinOverAll=0;
                 $yearSizeQtyOverAll=0;
                 $yearWorkerMinOverAll=0;
                 $yearKen_1Plant_EffiOverAll=0;
                 $noyOverAll=1;
                 $yearRejectQtyOverAll=0;
                 $yearRejPerOverAll=0;
                foreach($yearKen_1DataOverAll as $row3)
                {
                    $yearSizeQtyOverAll = $yearSizeQtyOverAll + $row3->size_qty;
                    if(($row3->size_qty * $row3->sam) !=0 &&  $row3->total_workers!=0)
                    {   
                        $yearRejectQtyOverAll = $yearRejectQtyOverAll + $row3->rejectionQty;
                        $yearKen_1MinOverAll = $yearKen_1MinOverAll +  $row3->size_qty * $row3->sam;
                        $yearWorkerMinOverAll = $yearWorkerMinOverAll + ($row3->total_workers * 480);
                        $yearKen_1Plant_EffiOverAll = $monthKen_1Plant_EffiOverAll + ((($row3->size_qty * $row3->sam)/($row3->total_workers * 480))) * 100;
                        $noyOverAll++;
                    }
                    else
                    {
                        $yearKen_1Plant_EffiOverAll = $yearKen_1Plant_EffiOverAll + 0;
                    }
                  
                }
                
                if($yearRejectQtyOverAll!=0 && $yearSizeQtyOverAll!=0)
               { $yearRejPerOverAll= round((($yearRejectQtyOverAll/ $yearSizeQtyOverAll)*100),2);}else{$yearRejPer=0;}
                
                
                if($yearKen_1Plant_EffiOverAll!=0 && ($noOverAll-1)!=0)
                {
                     $yearKen_1Plant_EffiOverAll=  $yearKen_1Plant_EffiOverAll/ ($noOverAll-1);
                }
                else
                {
                    $yearKen_1Plant_EffiOverAll=0;
                }
                
                  
           // echo $yearKen_1Plant_Effi; exit;
           
            
            $html .='<tr>
                <td>Cutting Quantity Pcs</td>
                <td>'.money_format('%!.0n', round($tvalue)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td>Cut Panel Issue Quantity Pcs</td>
                <td>'.money_format('%!.0n', round($tIssueValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mIssueValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yIssueValue,2)).'</td>
              </tr>
              <tr>
                <td>Cutting Room Efficiency</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>';
              
            
             $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">OverAll</td></tr>
              <tr>
                <td>Produced Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $todaySizeQtyOverAll).'</td>
                <td class="mpcs">'.money_format('%!.0n', $monthSizeQtyOverAll).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yearSizeQtyOverAll).'</td>
              </tr> 
              <tr>
                <td>Produced Minutes</td>
                <td class="tmin">'.money_format('%!.0n', round($todayKen_1MinOverAll,2)).'</td>
                <td class="mmin">'.money_format('%!.0n', round($monthKen_1MinOverAll,2)).'</td>
                <td class="ymin">'.money_format('%!.0n', round($yearKen_1MinOverAll,2)).'</td>
              </tr>
              <tr>
                <td>Plant Efficiency</td>';
                if($todayKen_1Plant_EffiOverAll !=0 && count($todayKen_1DataOverAll)!=0)
                { 
                   $html .='<td class="teff">'.money_format('%!.0n', round((($todayKen_1Plant_EffiOverAll)/count($todayKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                
                 if($monthKen_1Plant_EffiOverAll !=0 && count($monthKen_1DataOverAll)!=0)
                {
                    $html .='<td class="meff">'.money_format('%!.0n', round((($monthKen_1Plant_EffiOverAll)/count($monthKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                 
                 if($yearKen_1Plant_EffiOverAll !=0 && count($yearKen_1DataOverAll)!=0)
                { 
                    $html .='<td class="yeff">'.money_format('%!.0n', round((($yearKen_1Plant_EffiOverAll)/count($yearKen_1DataOverAll)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                } 
                
              $html .='</tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>DHU</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>'.$todaysRejPerOverAll.'</td>
                 <td>'.$monthRejPerOverAll.'</td>
                  <td>'.$yearRejPerOverAll.'</td>
              </tr>';
                          
                // $operationArr = array(
                //     array('Produced Pcs','',round($todaySizeQty,2),round($monthSizeQty,2),round($yearSizeQty,2),6, $vendor->ac_name),
                //     array('Produced Minutes','',round($todayKen_1Min,2),round($monthKen_1Min,2),round($yearKen_1Min,2),6,$vendor->ac_name),
                //     array('Plant Efficiency','%',round($todayKen_1Plant_Effi,2),round($monthKen_1Plant_Effi,2),round($yearKen_1Plant_Effi,2),6,$vendor->ac_name),
                //     array('CPM','Rs/Min',"-","-","-",6,$vendor->ac_name),
                //     array('DHU','%',"-","-","-",6,$vendor->ac_name),
                //     array('Rejection','%',$todaysRejPer,$monthRejPer,$yearRejPer,6,$vendor->ac_name)
                // );      
                // $this->tempInsertData($operationArr);  
        
        // Over All End -------------------------------------------
        
             
                          
            $operationArr2 = array(
                array('Cutting Quantity Pcs','',round($tvalue),round($mvalue,2),round($yvalue,2),6,""),
                array('Cut Panel Issue Quantity Pcs','',round($tIssueValue),round($mIssueValue,2),round($yIssueValue,2),6,""),
                array('Cutting Room Efficiency','%',"-","-","-",6,""),
                array('Produced Pcs','',round($todaySizeQtyOverAll,2),round($monthSizeQtyOverAll/100000,2),round($yearSizeQtyOverAll/100000,2),6,"Overall"),
                array('Produced Minutes','',round($todayKen_1MinOverAll,2),round($monthKen_1MinOverAll/100000,2),round($yearKen_1MinOverAll/100000,2),6,"Overall"),
                array('Plant Efficiency','%',round($todayKen_1Plant_EffiOverAll,2),round($monthKen_1Plant_EffiOverAll,2),round($yearKen_1Plant_EffiOverAll,2),6,"Overall"),
                array('CPM','Rs/Min',"-","-","-",6,"Overall"),
                array('DHU','%',"-","-","-",6,"Overall"),
                array('Rejection','%',$todaysRejPerOverAll,$monthRejPerOverAll,$yearRejPerOverAll,6,"Overall")
            );      
            $this->tempInsertData($operationArr2); 
            
            
            
             $vendorData = DB::select("select ac_code,ac_name from ledger_master WHERE ac_code IN (56,115,69,110)");

             foreach($vendorData as $vendor)
             {
                $todayKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  
                buyer_purchse_order_master.tr_code=stitching_inhouse_master.sales_order_no) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where 
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                qcstitching_inhouse_reject_detail.qcsti_date= '".date('Y-m-d')."' and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code 
                WHERE stitching_inhouse_size_detail2.sti_date = '".date('Y-m-d')."' 
                AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
        
                $monthKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where  buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                MONTH(qcstitching_inhouse_reject_detail.qcsti_date)=MONTH(CURRENT_DATE()) and YEAR(qcstitching_inhouse_reject_detail.qcsti_date)=YEAR(CURRENT_DATE()) and 
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE())  
                AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
                
                $yearKen_1Data = DB::select("select ifnull(sum(size_qty),0) as size_qty, sum(total_workers)  as total_workers ,
                (select buyer_purchse_order_master.sam from buyer_purchse_order_master where 
                buyer_purchse_order_master.tr_code = stitching_inhouse_master.sales_order_no ) as sam,
                (select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_reject_detail where
                qcstitching_inhouse_reject_detail.vendorId ='".$vendor->ac_code."'and 
                qcstitching_inhouse_reject_detail.qcsti_date  between '".$Financial_Year[0]->fdate."'  and NOW() and
                qcstitching_inhouse_reject_detail.sales_order_no=stitching_inhouse_master.sales_order_no) as rejectionQty 
                from stitching_inhouse_size_detail2 
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE stitching_inhouse_size_detail2.sti_date between '".$Financial_Year[0]->fdate."'  and NOW() AND stitching_inhouse_size_detail2.vendorId ='".$vendor->ac_code."' group by stitching_inhouse_master.sales_order_no");
                $todayKen_1Min=0;
                $todayKen_1Plant_Effi=0;
                $todayWorkerMin=0;
                $todaySizeQty=0;
                $todaysRejectQty=0;
                $todaysRejPer=0;
                $not=1;
                foreach($todayKen_1Data as $row1)
                {
                     $todaySizeQty = $todaySizeQty + $row1->size_qty;
                       $todayKen_1Min =$todayKen_1Min + $row1->size_qty * $row1->sam;
                       $todaysRejectQty = $todaysRejectQty + $row1->rejectionQty;
                     if(($row1->size_qty * $row1->sam) !=0 &&  $row1->total_workers!=0)
                    {   
                        $todayWorkerMin= $todayWorkerMin + $row1->total_workers * 480;
                      
                        $todayKen_1Plant_Effi =$todayKen_1Plant_Effi + ((($row1->size_qty * $row1->sam)/($row1->total_workers  * 480))) * 100;
                       
                        $not++;
                    } 
                    else
                    {
                        $todayKen_1Plant_Effi = $todayKen_1Plant_Effi + 0;
                    }
                 
                }
                
                if($todaysRejectQty!=0 && $todaySizeQty!=0)
               { $todaysRejPer=   round((($todaysRejectQty/ $todaySizeQty)*100),2);}else{$todaysRejPer=0;}
                
                
                
                if($todayKen_1Plant_Effi!=0 && ($not-1) !=0)
                {
                    $todayKen_1Plant_Effi = $todayKen_1Plant_Effi / ($not-1);
                }
                else
                {
                    $todayKen_1Plant_Effi=0;
                }
                
                
                $monthKen_1Min=0;
                $monthSizeQty=0;
                $monthworkersMin=0;
                $monthRejectQty=0;
                $monthRejPer=0;
                $monthKen_1Plant_Effi=0; $no=1;
                foreach($monthKen_1Data as $row2)
                {
                    $monthSizeQty = $monthSizeQty + $row2->size_qty;
                    $monthRejectQty= $monthRejectQty +   $row2->rejectionQty;
                    $monthKen_1Min =$monthKen_1Min +  $row2->size_qty * $row2->sam;
                    if(($row2->size_qty * $row2->sam) !=0 &&  $row2->total_workers!=0)
                    {  
                        $monthworkersMin= $monthworkersMin + ($row2->total_workers * 480);
                        $monthKen_1Plant_Effi = $monthKen_1Plant_Effi + ((($row2->size_qty * $row2->sam)/($row2->total_workers * 480))) * 100;
                        $no++;
                    }
                    else
                    {
                        $monthKen_1Plant_Effi = $monthKen_1Plant_Effi + 0;
                    }
                  
                }
                
                
                 if($monthRejectQty!=0 && $monthSizeQty!=0)
               { $monthRejPer=  round((($monthRejectQty/ $monthSizeQty)*100),2);}else{$$monthRejPer=0;}
                
                //echo $monthRejPer;
                if($monthKen_1Plant_Effi!=0 && ($no-1)!=0)
                {
                     $monthKen_1Plant_Effi=  $monthKen_1Plant_Effi/ ($no-1);
                }
                else
                {
                    $monthKen_1Plant_Effi=0;
                }
               
                 
                
                 $yearKen_1Min=0;
                 $yearSizeQty=0;
                 $yearWorkerMin=0;
                 $yearKen_1Plant_Effi=0;
                 $noy=1;
                 $yearRejectQty=0;
                $yearRejPer=0;
                foreach($yearKen_1Data as $row3)
                {
                    $yearSizeQty = $yearSizeQty + $row3->size_qty; 
                    $yearRejectQty = $yearRejectQty + $row3->rejectionQty;
                    $yearKen_1Min = $yearKen_1Min +  $row3->size_qty * $row3->sam;
                    if(($row3->size_qty * $row3->sam) !=0 &&  $row3->total_workers!=0)
                    {   
                      
                        $yearWorkerMin = $yearWorkerMin + ($row3->total_workers * 480);
                        $yearKen_1Plant_Effi = $monthKen_1Plant_Effi + ((($row3->size_qty * $row3->sam)/($row3->total_workers * 480))) * 100;
                        $noy++;
                    }
                    else
                    {
                        $yearKen_1Plant_Effi = $yearKen_1Plant_Effi + 0;
                    }
                  
                }
                
                if($yearRejectQty!=0 && $yearSizeQty!=0)
               { $yearRejPer= round((($yearRejectQty/ $yearSizeQty)*100),2);}else{$yearRejPer=0;}
                
                
                if($yearKen_1Plant_Effi!=0 && ($no-1)!=0)
                {
                     $yearKen_1Plant_Effi=  $yearKen_1Plant_Effi/ ($no-1);
                }
                else
                {
                    $yearKen_1Plant_Effi=0;
                }
                
                  
           // echo $yearKen_1Plant_Effi; exit;
        
             $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">'.$vendor->ac_name.'</td></tr>
              <tr>
                <td>Produced Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $todaySizeQty).'</td>
                <td class="mpcs">'.money_format('%!.0n', $monthSizeQty).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yearSizeQty).'</td>
              </tr> 
              <tr>
                <td>Produced Minutes</td>
                <td class="tmin">'.money_format('%!.0n', round($todayKen_1Min,2)).'</td>
                <td class="mmin">'.money_format('%!.0n', round($monthKen_1Min,2)).'</td>
                <td class="ymin">'.money_format('%!.0n', round($yearKen_1Min,2)).'</td>
              </tr>
              <tr>
                <td>Plant Efficiency</td>';
                if($todayKen_1Plant_Effi !=0 && count($todayKen_1Data)!=0)
                { 
                   $html .='<td class="teff">'.money_format('%!.0n', round((($todayKen_1Plant_Effi)/count($todayKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                
                 if($monthKen_1Plant_Effi !=0 && count($monthKen_1Data)!=0)
                {
                    $html .='<td class="meff">'.money_format('%!.0n', round((($monthKen_1Plant_Effi)/count($monthKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                }
                 
                 if($yearKen_1Plant_Effi !=0 && count($yearKen_1Data)!=0)
                { 
                    $html .='<td class="yeff">'.money_format('%!.0n', round((($yearKen_1Plant_Effi)/count($yearKen_1Data)),2)).'</td>';
                }
                else
                {
                    $html .='<td class="teff">0.00</td>';
                } 
                
              $html .='</tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>DHU</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>'.$todaysRejPer.'</td>
                 <td>'.$monthRejPer.'</td>
                  <td>'.$yearRejPer.'</td>
              </tr>';
                          
                $operationArr = array(
                    array('Produced Pcs','',round($todaySizeQty,2),round($monthSizeQty/100000,2),round($yearSizeQty/100000,2),6, $vendor->ac_name),
                    array('Produced Minutes','',round($todayKen_1Min,2),round($monthKen_1Min/100000,2),round($yearKen_1Min/100000,2),6,$vendor->ac_name),
                    array('Plant Efficiency','%',round($todayKen_1Plant_Effi,2),round($monthKen_1Plant_Effi,2),round($yearKen_1Plant_Effi,2),6,$vendor->ac_name),
                    array('CPM','Rs/Min',"-","-","-",6,$vendor->ac_name),
                    array('DHU','%',"-","-","-",6,$vendor->ac_name),
                    array('Rejection','%',$todaysRejPer,$monthRejPer,$yearRejPer,6,$vendor->ac_name)
                );      
                $this->tempInsertData($operationArr);  
             }
          
            $tOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
                sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as TodayInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND packing_inhouse_size_detail2.pki_date = '".date('Y-m-d')."'");
                
                
            $mOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as MonthInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND 
                MONTH(packing_inhouse_size_detail2.pki_date) = MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE())");
            
            $yOutSourceData = DB::select("select ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as size_qty , buyer_purchse_order_master.sam as sam,
              sum(packing_inhouse_size_detail2.size_qty*buyer_purchse_order_master.sam) as YearInwardMins
                from packing_inhouse_size_detail2 
                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = packing_inhouse_size_detail2.sales_order_no 
                WHERE packing_inhouse_size_detail2.vendorId NOT IN (56,115,69,110) AND
                packing_inhouse_size_detail2.pki_date between '".$Financial_Year[0]->fdate."'  and NOW()");
                     
            
            // $tOutSourceMin = $tOutSourceData[0]->size_qty * $tOutSourceData[0]->sam;
            // $mOutSourceMin = $mOutSourceData[0]->size_qty * $mOutSourceData[0]->sam;
            // $yOutSourceMin = $yOutSourceData[0]->size_qty * $yOutSourceData[0]->sam;
            
            
            
             $tOutSourceMin = $tOutSourceData[0]->TodayInwardMins;
            $mOutSourceMin =  $mOutSourceData[0]->MonthInwardMins;
            $yOutSourceMin =  $yOutSourceData[0]->YearInwardMins;
            
            
            
            
            
            
            $tPkgQty = ($tOutSourceData[0]->size_qty)/100000;
            $mPkgQty = ($mOutSourceData[0]->size_qty)/100000;
            $yPkgQty = ($yOutSourceData[0]->size_qty)/100000;
            
              
            $html .='<tr><td colspan="5" class="text-center" style="background: #6479b52e;font-weight: 900;font-size: 20px;">Outsource</td></tr>
              <tr>
                <td>Inward Pcs</td>
                <td class="tpcs">'.money_format('%!.0n', $tOutSourceData[0]->size_qty).'</td>
                <td class="mpcs">'.money_format('%!.0n', $mOutSourceData[0]->size_qty).'</td>
                <td class="ypcs">'.money_format('%!.0n', $yOutSourceData[0]->size_qty).'</td>
              </tr> 
              <tr>
                <td>Inward Minutes</td>
                <td class="tmin">'.money_format('%!.0n', $tOutSourceData[0]->TodayInwardMins).'</td>
                <td class="mmin">'.money_format('%!.0n', $mOutSourceData[0]->MonthInwardMins).'</td>
                <td class="ymin">'.money_format('%!.0n', $yOutSourceData[0]->YearInwardMins).'</td>
              </tr>
              <tr>
                <td>CPM</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Rejection</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td> Packing Quantity Pcs </td>
                <td>'.money_format('%!.0n',$tPkgQty).'</td>
                <td>'.money_format('%!.0n',$mPkgQty).'</td>
                <td>'.money_format('%!.0n',$yPkgQty).'</td>
              </tr>';
            
            $operationArr7 = array(
                array('Inward Pcs','',round($tOutSourceData[0]->size_qty,2),round($mOutSourceData[0]->size_qty/100000,2),round($yOutSourceData[0]->size_qty/100000,2),6,"Outsource"),
                array('Inward Minutes','',round($tOutSourceMin,2),round($mOutSourceMin/100000,2),round($yOutSourceMin/100000,2),6,"Outsource"),
                array('CPM','Rs/Min',"-","-","-",6,"Outsource"),
                array('Rejection','',"-","-","-",6,"Outsource"),
                array('Packing Quantity Pcs','',round($tPkgQty,2),round($mPkgQty,2),round($yPkgQty,2),7,""),
            );      
            $this->tempInsertData($operationArr7);  
        return response()->json(['html' => $html]);
    }
    
    public function openOrderMDDashboard()
    {         
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        //DB::enableQueryLog();
         DB::table('temp_order_sales_dashboard')->where('table_head', 8)->delete();
        // dd(DB::getQueryLog());
        $html = '';
        $FGStock= 0;
        
        //DB::enableQueryLog();
            $todayData = DB::select("select ifnull(sum(total_qty),0) as total_qty,
            (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty,
            buyer_purchse_order_master.sam,
            ifnull(sum(balance_qty),0) as balance_qty,
            sum(balance_qty * buyer_purchse_order_master.sam) as balance_value 
            from buyer_purchse_order_master 
            INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
            WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
            AND buyer_purchse_order_master.job_status_id = 1  and
            buyer_purchse_order_master.order_received_date <= '".date('Y-m-d')."'
            group by buyer_purchse_order_master.tr_code
        ");
        
        
        // dd(DB::getQueryLog());
        $monthData = DB::select("select ifnull(sum(total_qty),0) as total_qty,  
        (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty,buyer_purchse_order_master.sam,  
        sum(balance_qty * buyer_purchse_order_master.sam) as balance_value,
        ifnull(sum(balance_qty),0) as balance_qty from buyer_purchse_order_master 
        INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 
        AND buyer_purchse_order_master.job_status_id = 1  and
        buyer_purchse_order_master.order_received_date  <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') 
        group by buyer_purchse_order_master.tr_code");
        
        
        
        $yearData = DB::select("select ifnull(sum(total_qty),0) as total_qty, 
        (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty,
        buyer_purchse_order_master.sam, 
        sum(balance_qty * buyer_purchse_order_master.sam) as balance_value,ifnull(sum(balance_qty),0) as balance_qty 
        from buyer_purchse_order_master 
        INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
        WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4
        AND buyer_purchse_order_master.job_status_id = 1 AND    
        buyer_purchse_order_master.order_received_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH),
        '%Y-%m-%d') group by buyer_purchse_order_master.tr_code");
        
        $tProduceMinValue=0;
        $mProduceMinValue=0;
        $yProduceMinValue=0;
       $tvalue=0; 
       $tMinValue=0;
       $tBalValue=0;
       $tBalMinValue=0;
       $tshippedQty=0;
       
       foreach($todayData as $td)
       {
            $tvalue = $tvalue + ($td->total_qty)/100000;
            $tshippedQty =$tshippedQty +  ($td->shipped_qty/100000);
            $tMinValue = $tMinValue + ($td->sam * $td->total_qty)/100000;
            $tBalValue = $tBalValue + ($td->balance_qty)/100000;
            $tBalMinValue = $tBalMinValue + ($td->balance_qty * $td->sam)/100000;
            $tProduceMinValue=$tProduceMinValue + (($td->total_qty-$td->shipped_qty)*$td->sam)/100000;
            
            //  echo '1';
      }
       
       
      
        
        $mvalue=0;
        $mMinVvalue=0;
        $mBalMinValue=0;
        $mBalValue=0;
        $mshippedQty=0;
        foreach($monthData as $md)
           {
                $mvalue =$mvalue + ($md->total_qty)/100000;
                $mshippedQty =$mshippedQty +  ($md->shipped_qty/100000);
                $mMinVvalue = $mMinVvalue +  (($md->sam * $md->total_qty))/100000;
                $mBalMinValue = $mBalMinValue + ($md->balance_value)/100000;
                $mBalValue = $mBalValue + ($md->balance_qty)/100000;
                 $mProduceMinValue=$mProduceMinValue + (($md->total_qty-$md->shipped_qty)*$md->sam)/100000;
                //echo '2';
           }
       
       
       $yMinVvalue=0; 
       $yBalValue=0; 
       $yBalMinValue=0;  
       $yvalue=0;
       $yshippedQty=0;
         foreach($yearData as $yd)
       {
            $yshippedQty =$yshippedQty +  ($yd->shipped_qty/100000);
            $yMinVvalue =$yMinVvalue +  (($yd->sam * $yd->total_qty))/100000;
            $yBalValue = $yBalValue + ($yd->balance_qty)/100000;
            $yBalMinValue = $yBalMinValue + ($yd->balance_value)/100000;
            $yvalue = $yvalue +  ($yd->total_qty)/100000;
            $yProduceMinValue=$yProduceMinValue + (($yd->total_qty-$yd->shipped_qty)*$yd->sam)/100000;
           // echo '3';
       }
              
                
        $mFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1 AND 
         carton_packing_inhouse_size_detail2.cpki_date  <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'carton_pack_qty',
            
              
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND  transfer_packing_inhouse_size_detail2.tpki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'transfer_qty' 
        
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and   buyer_purchse_order_master.og_id!=4 and
        packing_inhouse_size_detail2.pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
    
    
    
    
    
        $tFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
        AND carton_packing_inhouse_size_detail2.cpki_date <= '".date('Y-m-d')."'
        ) as 'carton_pack_qty',
        
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND  transfer_packing_inhouse_size_detail2.tpki_date <= '".date('Y-m-d')."'
        ) as 'transfer_qty' 
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and buyer_purchse_order_master.og_id!=4 
        and packing_inhouse_size_detail2.pki_date <= '".date('Y-m-d')."'");
    
    
    
        $yFGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1 AND
        carton_packing_inhouse_size_detail2.cpki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'carton_pack_qty',
          
        (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and transfer_packing_inhouse_size_detail2.usedFlag=1 AND
        transfer_packing_inhouse_size_detail2.tpki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
        ) as 'transfer_qty' 
        
        FROM `packing_inhouse_size_detail2` 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id=1 and buyer_purchse_order_master.og_id!=4 and packing_inhouse_size_detail2.pki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
        // dd(DB::getQueryLog());    

        $tFGStock = $tFGStockData[0]->packing_grn_qty - $tFGStockData[0]->carton_pack_qty -  $tFGStockData[0]->transfer_qty;
        $mFGStock = $mFGStockData[0]->packing_grn_qty - $mFGStockData[0]->carton_pack_qty -  $mFGStockData[0]->transfer_qty;
        $yFGStock = $yFGStockData[0]->packing_grn_qty - $yFGStockData[0]->carton_pack_qty -  $yFGStockData[0]->transfer_qty;
       
        // $tProduceValue = $tvalue-($tFGStock - $todayData[0]->balance_qty)/100000;
        // $mProduceValue = $mvalue-($mFGStock - $monthData[0]->balance_qty)/100000;
        // $yProduceValue = $yvalue-($yFGStock - $yearData[0]->balance_qty)/100000;
        
        
        // echo $tFGStock.'  '.$mFGStock.'   '.$yFGStock;
        // exit;
        
        
        $tProduceValue = $tvalue - $tshippedQty;
        $mProduceValue = $mvalue- $mshippedQty;
        $yProduceValue = $yvalue- $yshippedQty;
        
        
        
        
        
        // $tProduceMinValue = (($todayData[0]->sam) * ($tvalue-($todayData[0]->balance_qty - $tFGStock))/100000);
        // $mProduceMinValue = (($monthData[0]->sam) * ($mvalue-($monthData[0]->balance_qty - $mFGStock))/100000);
        
        //($monthData[0]->balance_value - $mFGStock)/100000;
        
        
        
        // $yProduceMinValue = (($yearData[0]->sam) * ($yvalue-($yearData[0]->balance_qty - $mFGStock))/100000);
        
      //  ($yearData[0]->balance_value - $yFGStock)/100000;
        
        
        if($tProduceValue == '-0')
        {
            $tProduceValue = 0;
        }
        if($mProduceValue == '-0')
        {
            $mProduceValue = 0;
        }
        if($yProduceValue == '-0')
        {
            $yProduceValue = 0;
        }
        
        $html .='<tr>
                <td> Total Open Orders Pcs </td>
                <td>'.money_format('%!.0n', round($tvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($mvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yvalue,2)).'</td>
              </tr>
              <tr>
                <td> Total Open Orders Min </td>
                <td>'.money_format('%!.0n', round($tMinValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mMinVvalue,2)).'</td>
                <td>'.money_format('%!.0n', round($yMinVvalue,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Ship Pcs </td>
                <td>'.money_format('%!.0n', round($tBalValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalValue,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Ship Min </td>
                <td>'.money_format('%!.0n', round($tBalMinValue,2)).'</td>
                <td>'.money_format('%!.0n', round($mBalMinValue,2)).'</td>
                <td>'.money_format('%!.0n', round($yBalMinValue,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Produce Pcs </td>
                <td>'.money_format('%!.0n', round($tProduceValue ? $tProduceValue : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($mProduceValue ? $mProduceValue : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($yProduceValue ? $yProduceValue : 0,2)).'</td>
              </tr>
              <tr>
                <td> Balance To Produce Min </td>
                <td>'.money_format('%!.0n', round($tProduceMinValue ? $tProduceMinValue : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($mProduceMinValue ? $mProduceMinValue : 0,2)).'</td>
                <td>'.money_format('%!.0n', round($yProduceMinValue ? $yProduceMinValue : 0,2)).'</td>
              </tr>';
                
             
            $openOrdersArr = array(
                array('Total Open Orders Pcs','',round($tvalue,2),round($mvalue,2),round($yvalue,2),8,""),
                array('Total Open Orders Min','',round($tMinValue,2),round($mMinVvalue,2),round($yMinVvalue,2),8,""),
                array('Balance To Ship Pcs','',round($tBalValue,2),round($mBalValue,2),round($yBalValue,2),8,""),
                array('Balance To Ship Min','',round($tBalMinValue,2),round($mBalMinValue,2),round($yBalMinValue,2),8,""),
                array('Balance To Produce Pcs','',round($tProduceValue,2),round($mProduceValue,2),round($yProduceValue,2),8,""),
                array('Balance To Produce Min','',round($tProduceMinValue,2),round($mProduceMinValue,2),round($yProduceMinValue,2),8,"")
            );
            
           
            $this->tempInsertData($openOrdersArr);
            
        return response()->json(['html' => $html]);
    }
    
    public function inventoryStatusMDDashboard()
    {
        setlocale(LC_MONETARY, 'en_IN');  
       $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete();
        $html = '';
      
        
            $today_Qty = 0;
            $today_Value = 0;
           
            $TOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
            $TOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < '".date('Y-m-d')."') -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStockValue");
            
            
            $TodayIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
            $TodayOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
            $today_Qty=$TOpeningStock[0]->OpeningStock + $TodayIn[0]->Inward - $TodayOut[0]->Outward;
            $today_Value=$TOpeningStockValue[0]->OpeningStockValue + $TodayIn[0]->InwardValue - $TodayOut[0]->OutwardValue;
            
         /**********************************************************/
       
         $MOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
            $MOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
            $MonthIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $TMonthOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $month_Qty=$MOpeningStock[0]->OpeningStock + $MonthIn[0]->Inward - $TMonthOut[0]->Outward;
            $month_Value=$MOpeningStockValue[0]->OpeningStockValue + $MonthIn[0]->InwardValue - $TMonthOut[0]->OutwardValue;
       
            /********************************************************************************/ 
            
            
         $year_Qty = 0;
         $year_Value = 0;
        
         $YOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStock");
            
            
          //  dd(DB::getQueryLog()); 
            $YOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')))  as OpeningStockValue");
            
            
            $YearIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $YearInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $year_Qty=$YOpeningStock[0]->OpeningStock + $YearIn[0]->Inward - $YearInOut[0]->Outward;
            $year_Value=$YOpeningStockValue[0]->OpeningStockValue + $YearIn[0]->InwardValue - $YearInOut[0]->OutwardValue;
        
        /**********************************************************************************/
           $today_non_Qty = 0;
           $today_non_Value = 0;
       
            $TnonOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."') 
          
              +
          
            (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
            -
             
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')-
            
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."'))  as OpeningStock");
            
            $TnonOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  inward_details.in_date < '".date('Y-m-d')."')
            
              +
            
            (select ifnull((sum(meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < '".date('Y-m-d')."') 
            
            -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
             -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
             where inward_master.is_opening=1 and  fabric_outward_details.fout_date < '".date('Y-m-d')."')
            
            )  as OpeningStockValue");
            
            
            $TodaynonIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  inward_details.in_date = '".date('Y-m-d')."'");
            
            $TodaynonInOp=DB::select("select ifnull(sum(meter),0)  as Inwardop, ifnull(sum(meter*inward_details.item_rate),0) as InwardValueop
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where  inward_master.is_opening=1 and  inward_details.in_date = '".date('Y-m-d')."'");
            
             $TodaynonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
            ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
            
            
             $TodaynonOutop=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
             ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where  inward_master.is_opening=1 and  fabric_outward_details.fout_date = '".date('Y-m-d')."'");
                
            $today_non_Qty=$TnonOpeningStock[0]->OpeningStock +  $TodaynonInOp[0]->Inwardop + $TodaynonIn[0]->Inward - $TodaynonOut[0]->Outward- $TodaynonOutop[0]->Outwardop;
            $today_non_Value=$TnonOpeningStockValue[0]->OpeningStockValue + $TodaynonInOp[0]->InwardValueop + $TodaynonIn[0]->InwardValue - $TodaynonOut[0]->OutwardValue- $TodaynonOutop[0]->OutwardValueop;
            
            /***********************************************/
          
            $MnonOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))  
              
                +
            (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')) 
             -
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            -
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
            )  as OpeningStock");
            
            $MnonOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            +
            (select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
            
            -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            -
            
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and    fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
               
            )  as OpeningStockValue");
            
            
            $MonthnonIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            $MonthnonInOp=DB::select("select ifnull(sum(meter),0)  as Inwardop, ifnull(sum(meter*inward_details.item_rate),0) as InwardValueop
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
            $TMonthnonOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, 
            ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status= 2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            $TMonthnonOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
            ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')"); 
            
            $month_non_Qty=$MnonOpeningStock[0]->OpeningStock + $MonthnonIn[0]->Inward + $MonthnonInOp[0]->Inwardop - $TMonthnonOut[0]->Outward-$TMonthnonOutOp[0]->Outwardop;
            $month_non_Value=$MnonOpeningStockValue[0]->OpeningStockValue + $MonthnonIn[0]->InwardValue + $MonthnonInOp[0]->InwardValueop - $TMonthnonOut[0]->OutwardValue - $TMonthnonOutOp[0]->OutwardValueop;
            
                /*********************************************************/
           $year_non_Qty = 0;
           $year_non_Value = 0;
        
           $YnonOpeningStock=DB::select("select ( (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
            +
            (select ifnull(sum(meter),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
            
                    
            -
             
            (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            -
            
             (select ifnull(sum(fabric_outward_details.meter),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            
            )  as OpeningStock");
            
            
        
            $YnonOpeningStockValue=DB::select("select ((select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
           
            +
            
            (select ifnull((sum(inward_details.meter*inward_details.item_rate)),0)  
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')) 
              
              -
             
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
            -
            
            (select ifnull((sum(fabric_outward_details.meter*inward_details.item_rate)),0)  
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                
            )  as OpeningStockValue");
            
            
            $YearnonIn=DB::select("select ifnull(sum(meter),0)  as Inward, ifnull(sum(meter*inward_details.item_rate),0) as InwardValue
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $YearnonInOp=DB::select("select ifnull(sum(meter),0)  as Inwardop,
            ifnull(sum(meter*inward_details.item_rate),0) as InwardValueop
            from inward_details
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  inward_details.in_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
            $YearnonInOut=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outward, ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValue
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code
            where purchase_order.po_status=2 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
             $YearnonInOutOp=DB::select("select ifnull(sum(fabric_outward_details.meter),0)   as Outwardop,
             ifnull(sum(fabric_outward_details.meter*inward_details.item_rate),0) as OutwardValueop
            from fabric_outward_details
            inner join   inward_details on inward_details.track_code=fabric_outward_details.track_code
            inner join   inward_master on inward_master.in_code=inward_details.in_code
            where inward_master.is_opening=1 and  fabric_outward_details.fout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            
            
            $year_non_Qty=$YnonOpeningStock[0]->OpeningStock + $YearnonIn[0]->Inward + $YearnonInOp[0]->Inwardop - $YearnonInOut[0]->Outward - $YearnonInOutOp[0]->Outwardop;
            $year_non_Value=$YnonOpeningStockValue[0]->OpeningStockValue + $YearnonIn[0]->InwardValue + $YearnonInOp[0]->InwardValueop - $YearnonInOut[0]->OutwardValue - $YearnonInOutOp[0]->OutwardValueop;
             
     
     
     
     
     ///*****************************************************************************
     
     
                        // $TrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                            
                            
                        //     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        //     where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
                        //     trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        //     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code)
                            
                        //     as out_qty  
                        //     from trimsInwardDetail
                        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                        //     where  purchase_order.po_status=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                        //     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        //     ");
                            
                         $TrimsInwardDetailsIn = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
                         trimsInwardDetail.item_rate   
                         from trimsInwardDetail
                         inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                         where  purchase_order.po_status=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                         group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
                                  ;
                         
                         $TrimsInwardDetailsOut = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
                         trimsOutwardDetail.item_rate   from trimsOutwardDetail 
                         inner JOIN purchase_order ON purchase_order.pur_code =trimsOutwardDetail.po_code
                         where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
                         purchase_order.po_status=1  group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                        
                         $today_Trims_Value=0; 
                            
                        $today_Trims_In_Value=0;                        
                        foreach($TrimsInwardDetailsIn as $row)  
                        {
                           $today_Trims_In_Value=$today_Trims_In_Value + round(($row->item_qty ) * $row->item_rate);
                        } 
                            
                             
                        $today_Trims_Out_Value=0;                        
                        foreach($TrimsInwardDetailsOut as $row)  
                        {
                           $today_Trims_Out_Value=$today_Trims_Out_Value + round(($row->item_qty ) * $row->item_rate);
                        }
     
                            $today_Trims_Value= $today_Trims_In_Value - $today_Trims_Out_Value ;
     
     
     
                        //     $TrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                        //     (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        //     where trimsOutwardDetail.tout_date < '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        //     and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                        //     from trimsInwardDetail
                        //     where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                        //     group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        //     ");
     
     
                                              
                        // foreach($TrimsInwardDetails2 as $row)  
                        // {
                        //   $today_Trims_Value=$today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                        // }
     
     
     
        // $TrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //  WHERE purchase_order.po_status = 1 and
        //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
                 
        //     -
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where   purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."'))  as OpeningStockValue
        //   ");
        
        
        
        //--------------------------------------------
        
        //     $TrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate     
        //     from trimsInwardDetail
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //     WHERE purchase_order.po_status = 1 and
        //     trimsInwardDetail.trimDate <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code");
         
        //      $tinval=0;
        //      foreach($TrimsInwardToday as $ttt)
        //      {
        //          $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
        //      }
         
         
        
        //     $TrimsOutwardToday = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate   
        //     from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' group by trimsOutwardDetail.item_code");
        
        // $toutval=0;
        //  foreach($TrimsOutwardToday as $ttto)
        //  {
        //      $toutval = $toutval + ($ttto->item_rate * $ttto->item_qty);
        //  }
         
         
         
        //     $TrimsInwardTodayOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate     
        //     from trimsInwardDetail
        //     WHERE  trimsInwardDetail.is_opening = 1 and
        //     trimsInwardDetail.trimDate <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code");
        //     $tinvalop=0;
        //     foreach($TrimsInwardTodayOp as $ttt)
        //     {
        //       $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
        //     }
         
        //     $TrimsOutwardTodayOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate   
        //     from trimsOutwardDetail 
        //     inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
        //     where  trimsInwardDetail.is_opening=1
        //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' group by trimsOutwardDetail.item_code");
         
        //     $toutvalop=0;
        //     foreach($TrimsOutwardTodayOp as $ttto)
        //     {
        //      $toutval = $toutvalop + ($ttto->item_rate * $ttto->item_qty);
        //     }
        
        //     $today_Trims_Value=  $tinvalop +  $tinval - $toutval -$toutvalop;
        
        
        //--------------------------------------------------------------
        
            // $TrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
            // from trimsInwardDetail
            // left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            // left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
            // WHERE trimsInwardMaster.is_opening = 1 
            // trimsInwardDetail.trimDate = '".date('Y-m-d')."'");      
  
         
        //$today_Trims_Value = $TrimsOpeningStock[0]->OpeningStockValue + ($TrimsInwardToday[0]->TodaysInValue) - $TrimsOutwardToday[0]->TodaysOutValue;
        
        
        // $MTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //  WHERE purchase_order.po_status = 1 and
        //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
        //     -
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') ))  as MOpeningStockValue
        //   ");
        
        
                    $MTrimsInward = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                    (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                    where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                    and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                    from trimsInwardDetail
                    inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                    where purchase_order.po_status=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                    group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
    
                    $month_Trims_Value=0;                        
                    foreach($MTrimsInward as $row)  
                    {
                       $month_Trims_Value=$month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                    }
      
     
                    // $MTrimsInward2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                    // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                    // where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                    // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                    // from trimsInwardDetail
                    // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                    // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                    // ");
     
     
                                              
                    // foreach($TrimsInwardDetails2 as $row)  
                    // {
                    //   $month_Trims_Value=$month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                    // }
     
         
        
        
        
            // $MTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate 
            // from trimsInwardDetail
            // inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            // WHERE purchase_order.po_status = 1 and
            // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
         
            //  $tinval=0;
            //  foreach($MTrimsInward as $ttt)
            //  {
            //      $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
            //  }
         
         
         
         
            // $MTrimsInwardop = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty, trimsInwardDetail.item_rate 
            // from trimsInwardDetail
            // WHERE trimsInwardDetail.is_opening = 1 and
            // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
         
            // $tinvalop=0;
            //  foreach($MTrimsInwardop as $ttt)
            //  {
            //      $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
            //  }
         
         
         
         
        
            // $MTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty   ,trimsOutwardDetail.item_rate 
            // from trimsOutwardDetail 
            // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
            // where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
            // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
        
            // $toutval=0;
            //  foreach($MTrimsOutward as $ttt)
            //  {
            //      $toutval = $toutval + ($ttt->item_rate * $ttt->item_qty);
            //  }
        
        
        
            // $MTrimsOutwardop = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty   ,trimsOutwardDetail.item_rate 
            // from trimsOutwardDetail 
            // inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
            // where  trimsInwardDetail.is_opening = 1
            // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
            //  $toutvalop=0;
            //  foreach($MTrimsOutwardop as $ttt)
            //  {
            //      $toutvalop = $toutvalop + ($ttt->item_rate * $ttt->item_qty);
            //  }
         
        
          
         
           
         
            //   $month_Trims_Value  =$tinvalop + $tinval - $toutval -$toutvalop;
            //$month_Trims_Value = ($MTrimsOpeningStock[0]->MOpeningStockValue + ($MTrimsInward[0]->MonthInValue - $MTrimsOutward[0]->MonthOutValue))  ;
       
      
            // $YTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
            //  from trimsInwardDetail
            //  inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            //  WHERE purchase_order.po_status = 1 and
            //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
            // -
            // (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
            // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
            // where   purchase_order.po_status = 1  
            // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') ))  as YOpeningStockValue
            // ");
            
            
            
                    $YTrimsInward = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                    (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                    where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                    and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                    and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                    from trimsInwardDetail
                    inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                    where purchase_order.po_status=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
                    group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
    
                    $year_Trims_Value=0;                        
                    foreach($YTrimsInward as $row)  
                    {
                       $year_Trims_Value=$year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                    }
      
     
                    // $YTrimsInward2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                    // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                    // where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                    // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                    // from trimsInwardDetail
                    // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                    // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                    // ");
     
     
                                              
                    // foreach($YTrimsInward2 as $row)  
                    // {
                    //   $year_Trims_Value=$year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                    // }
            
            
            
            
            
            
            
            
            
            
        
    //         $YTrimsOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as  item_qty, trimsInwardDetail.item_rate 
    //         from trimsInwardDetail 
    //          WHERE trimsInwardDetail.is_opening = 1 and
    //         trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.item_code");
          
    //         $YTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty),0) as item_qty,   trimsInwardDetail.item_rate 
    //         from trimsInwardDetail
    //         inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
    //         WHERE purchase_order.po_status = 1 and
    //         trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.po_code, trimsInwardDetail.item_code");
         
        
    //         $YTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty, trimsOutwardDetail.item_rate 
    //         from trimsOutwardDetail 
    //         inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
    //         where   purchase_order.po_status = 1  
    //         and trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsOutwardDetail.item_code");
      
      
    //         $YTrimsOutwardop = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty , trimsOutwardDetail.item_rate 
    //         from trimsOutwardDetail 
    //         inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code = trimsOutwardDetail.po_code
    //         where    trimsInwardDetail.is_opening = 1
    //         and trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') group by trimsInwardDetail.po_code,trimsOutwardDetail.item_code");
    //     //$year_Trims_Value = ($YTrimsOpeningStock[0]->YOpeningStockValue + ($YTrimsInward[0]->YearInValue - $YTrimsOutward[0]->YearOutValue))  ;
         
        
    //      $tinvalop=0;
    //      foreach($YTrimsOp as $ttt)
    //      {
    //          $tinvalop = $tinvalop + ($ttt->item_rate * $ttt->item_qty);
    //      }
        
        
        
    //      $tinval=0;
    //      foreach($YTrimsInward as $ttt)
    //      {
    //          $tinval = $tinval + ($ttt->item_rate * $ttt->item_qty);
    //      }
         
         
    //       $toutval=0;
    //      foreach($YTrimsOutward as $ttt)
    //      {
    //          $toutval = $toutval + ($ttt->item_rate * $ttt->item_qty);
    //      }
        
    //      $toutvalop=0;
    //      foreach($YTrimsOutwardop as $ttt)
    //      {
    //          $toutvalop = $toutvalop + ($ttt->item_rate * $ttt->item_qty);
    //      }
         
    //   $year_Trims_Value =$tinvalop +  $tinval - $toutval - $toutvalop;
        //*************************************************************
   
        //  $NonTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //  WHERE purchase_order.po_status = 1 and
        //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
       
        //  +
         
        //  (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  WHERE trimsInwardDetail.is_opening = 1 and
        //  trimsInwardDetail.trimDate < '".date('Y-m-d')."')
                 
        //     -
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where   purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."')
        //     -
            
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
        //     where   trimsInwardMaster.is_opening = 1   
        //     and trimsOutwardDetail.tout_date < '".date('Y-m-d')."')
         //           )  as OpeningStockValue
        //   ");
        
        
        
        
        
        
        
        
                // $NonTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                // where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                // from trimsInwardDetail
                // inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                // where purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                // ");

                // $non_today_Trims_Value=0;                        
                // foreach($NonTrimsInwardDetails as $row)  
                // {
                //   $non_today_Trims_Value=$non_today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                // }


                // $NonTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                // (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                // where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                // and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                // from trimsInwardDetail
                // where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                // group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                // ");

                       
                // foreach($NonTrimsInwardDetails2 as $row)  
                // {
                //   $non_today_Trims_Value=$non_today_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                // }


                $non_today_Trims_Value=0; 
                $NonTrimsInwardDetailsIn = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
                trimsInwardDetail.item_rate   
                from trimsInwardDetail
                inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                where  purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
                        
                $non_today_Trims_In_Value=0;                        
                foreach($NonTrimsInwardDetailsIn as $row)  
                {
                   $non_today_Trims_In_Value=$non_today_Trims_In_Value + round(($row->item_qty ) * $row->item_rate);
                } 
                  
                $NonTrimsInwardDetailsInOpeing = DB::select("select  sum(trimsInwardDetail.item_qty) as item_qty  ,
                trimsInwardDetail.item_rate   
                from trimsInwardDetail
                where  trimsInwardDetail.is_opening=1 and trimsInwardDetail.trimDate <= '".date('Y-m-d')."'
                group by  trimsInwardDetail.po_code,trimsInwardDetail.item_code");
                  
                $non_today_Trims_OpeningIN_Value=0;                        
                foreach($NonTrimsInwardDetailsInOpeing as $row)  
                {
                   $non_today_Trims_OpeningIN_Value=$non_today_Trims_OpeningIN_Value + round(($row->item_qty ) * $row->item_rate);
                }
                
                $non_today_Trims_ValueTotalIN=$non_today_Trims_In_Value + $non_today_Trims_OpeningIN_Value;
                
               // echo 'Close PO In Value'.$non_today_Trims_In_Value . ' & Opening Stock In Value'. $non_today_Trims_OpeningIN_Value;
                
                
                $NonTrimsInwardDetailsOut = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
                trimsOutwardDetail.item_rate   from trimsOutwardDetail 
                inner JOIN purchase_order ON purchase_order.pur_code =trimsOutwardDetail.po_code
                where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and 
                purchase_order.po_status!=1  group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                
                $non_today_Trims_Out_Value=0;                        
                foreach($NonTrimsInwardDetailsOut as $row)  
                {
                   $non_today_Trims_Out_Value=$non_today_Trims_Out_Value + round(($row->item_qty ) * $row->item_rate);
                }

                $NonTrimsInwardDetailsOutOpening = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty),0) as item_qty,
                trimsOutwardDetail.item_rate   from trimsOutwardDetail 
                inner JOIN trimsInwardDetail ON trimsInwardDetail.po_code =trimsOutwardDetail.po_code 
                and trimsInwardDetail.item_code =trimsOutwardDetail.item_code
                where trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' and trimsInwardDetail.is_opening=1
                group by  trimsOutwardDetail.po_code, trimsOutwardDetail.item_code");  
                
                $non_today_Trims_Out_ValueOpening=0;                        
                foreach($NonTrimsInwardDetailsOutOpening as $row)  
                {
                   $non_today_Trims_Out_ValueOpening=$non_today_Trims_Out_ValueOpening + round(($row->item_qty ) * $row->item_rate);
                }

               // echo 'Close PO Out Value: '.$non_today_Trims_Out_Value . ' & Opening Stock Out Value: '. $non_today_Trims_Out_ValueOpening;
                
                $non_today_Trims_ValueTotalOut= $non_today_Trims_Out_Value + $non_today_Trims_Out_ValueOpening;
                
                // echo 'Total IN Value: '.$non_today_Trims_ValueTotalIN.' & Total Out Value: '.$non_today_Trims_ValueTotalOut;
                

                $non_today_Trims_Value= $non_today_Trims_ValueTotalIN - $non_today_Trims_ValueTotalOut ;

                // echo   'Total non Moving Value:'.$non_today_Trims_Value;  
               
               // exit;
        
        
        
        
        
        
        
        
        
        
        //     $NonTrimsInwardToday = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as TodaysInValue
        //     from trimsInwardDetail
        //     left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //     WHERE purchase_order.po_status = 1 and
        //     trimsInwardDetail.trimDate  <= '".date('Y-m-d')."' group by trimsInwardDetail.po_code,trimsInwardDetail.item_code");
             
             
        //     $NonTrimsInwardTodayOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as TodaysInValue
        //     from trimsInwardDetail
        //     WHERE trimsInwardDetail.is_opening = 1 and
        //     trimsInwardDetail.trimDate  <= '".date('Y-m-d')."' group by trimsInwardDetail.item_code ");
          
        //     $NonTrimsOutwardToday = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as TodaysOutValue
        //     from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."'");
        
        //     $NonTrimsOutwardTodayOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as TodaysOutValue
        //     from trimsOutwardDetail 
        //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
        //     where   trimsInwardMaster.is_opening = 1 
        //     and trimsOutwardDetail.tout_date <= '".date('Y-m-d')."'");
        
           
        // $non_today_Trims_Value =  ($NonTrimsInwardToday[0]->TodaysInValue) + ($NonTrimsInwardTodayOp[0]->TodaysInValue) - $NonTrimsOutwardToday[0]->TodaysOutValue - $NonTrimsOutwardTodayOp[0]->TodaysOutValue;
        
        
        
        
        
                        $NonMTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                        (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                        from trimsInwardDetail
                        inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                        where purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                        group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
        
                        $non_month_Trims_Value=0;                        
                        foreach($NonMTrimsInwardDetails as $row)  
                        {
                           $non_month_Trims_Value=$non_month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                        }
     
      
                        $NonMTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                        (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                        from trimsInwardDetail
                        where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                        group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
     
                               
                        foreach($NonMTrimsInwardDetails2 as $row)  
                        {
                           $non_month_Trims_Value=$non_month_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                        }
        
        
        // $NonMTrimsOpeningStock =  DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //  WHERE purchase_order.po_status = 1 and
        //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
       
        //  +
         
        //  (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
        //  from trimsInwardDetail
        //  WHERE trimsInwardDetail.is_opening = 1 and
        //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
        //     -
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where   purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
        //     -
            
        //     (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
        //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
        //     where   trimsInwardMaster.is_opening = 1   
        //     and trimsOutwardDetail.tout_date < DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d'))
            
        //           )  as OpeningStockValue
        //   ");
        
        
        //     $NonMTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as MonthInValue
        //     from trimsInwardDetail
        //     left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
        //     WHERE purchase_order.po_status = 1 and
        //     trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
         
        //     $NonMTrimsInwardOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as MonthInValue
        //     from trimsInwardDetail
        //      WHERE trimsInwardDetail.is_opening = 1 and
        //     trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
        //     $NonTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as MonthOutValue
        //     from trimsOutwardDetail 
        //     inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
        //     where trimsOutwardDetail.po_code=purchase_order.pur_code and purchase_order.po_status = 1  
        //     and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
        //     $NonTrimsOutwardOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as MonthOutValue
        //     from trimsOutwardDetail 
        //     inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
        //     where   trimsInwardMaster.is_opening = 1 
        //     and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
        
              
        //     $non_month_Trims_Value = ($NonMTrimsOpeningStock[0]->OpeningStockValue  + $NonMTrimsInward[0]->MonthInValue + $NonMTrimsInwardOp[0]->MonthInValue - $NonTrimsOutward[0]->MonthOutValue - $NonTrimsOutwardOp[0]->MonthOutValue);
      
      
      
      
      
                        $NonYTrimsInwardDetails = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                        (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                        from trimsInwardDetail
                        inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
                        where purchase_order.po_status!=1 and trimsInwardDetail.trimDate <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
                        group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
        
                        $non_year_Trims_Value=0;                        
                        foreach($NonYTrimsInwardDetails as $row)  
                        {
                           $non_year_Trims_Value=$non_year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                        }
     
      
                        $NonYTrimsInwardDetails2 = DB::select("select sum(item_qty) as item_qty, trimsInwardDetail.item_rate,
                        (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
                        where trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and trimsOutwardDetail.po_code=trimsInwardDetail.po_code 
                        and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty  
                        from trimsInwardDetail
                        where trimsInwardDetail.is_opening =1 and trimsInwardDetail.trimDate <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                        group by trimsInwardDetail.po_code,trimsInwardDetail.item_code
                        ");
     
                               
                        foreach($NonYTrimsInwardDetails2 as $row)  
                        {
                           $non_year_Trims_Value=$non_year_Trims_Value + round(($row->item_qty - $row->out_qty) * $row->item_rate);
                        }
      
      
      
            // $NonYTrimsOpeningStock = DB::select("select  ((select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
            //  from trimsInwardDetail
            //  inner JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            //  WHERE purchase_order.po_status = 1 and
            //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
            //      +
                 
            //      (select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0)
            //  from trimsInwardDetail
            //  WHERE  trimsInwardDetail.is_opening = 1  and
            //  trimsInwardDetail.trimDate < DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d'))
                 
                  
            // -
            // (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
            // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
            // where   purchase_order.po_status = 1  
            // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') )
            
            // -
            
            //  (select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0)  from trimsOutwardDetail 
            // inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
            // where   trimsInwardMaster.is_opening = 1 
            // and trimsOutwardDetail.tout_date <   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') )
            
            
            // )  as YOpeningStockValue
            // ");
        
         
        
            // $NonYTrimsInward = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as YearInValue
            // from trimsInwardDetail
            // left JOIN purchase_order ON purchase_order.pur_code = trimsInwardDetail.po_code
            // WHERE purchase_order.po_status = 1 and
            // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
            // $NonYTrimsInwardOp = DB::select("select ifnull(sum(trimsInwardDetail.item_qty)*trimsInwardDetail.item_rate,0) as YearInValue
            // from trimsInwardDetail
            //  WHERE trimsInwardDetail.is_opening = 1 and
            // trimsInwardDetail.trimDate = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
         
       
            //   $NonYTrimsOutward = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as YearOutValue
            // from trimsOutwardDetail 
            // inner JOIN purchase_order ON purchase_order.pur_code = trimsOutwardDetail.po_code
            // where   purchase_order.po_status = 1  
            // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
      
      
      
            // $NonYTrimsOutwardOp = DB::select("select ifnull(sum(trimsOutwardDetail.item_qty)*trimsOutwardDetail.item_rate,0) as YearOutValue
            // from trimsOutwardDetail 
            // inner join trimsInwardMaster on trimsInwardMaster.po_code=trimsOutwardDetail.po_code
            // where   trimsInwardMaster.is_opening = 1     
            // and trimsOutwardDetail.tout_date = DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
        
            
            // $non_year_Trims_Value = ($NonYTrimsOpeningStock[0]->YOpeningStockValue + ($NonYTrimsInward[0]->YearInValue + $NonYTrimsInwardOp[0]->YearInValue - $NonYTrimsOutward[0]->YearOutValue - $NonYTrimsOutwardOp[0]->YearOutValue))  ;
        
        
         //****************************************************************************
         
         
   
        
        $today_WIP_value = 0;
        $month_WIP_value = 0;
        $year_WIP_value = 0;
        
        $todayFGStock = 0;
        $todayFGValue = 0;
        
        $TodayFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,packing_inhouse_size_detail2.size_id,packing_inhouse_size_detail2.color_id,packing_inhouse_size_detail2.Ac_code, 
        ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', buyer_purchse_order_master.order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            WHERE buyer_purchse_order_master.job_status_id = 1 AND packing_inhouse_size_detail2.pki_date <= '".date('Y-m-d')."'
            GROUP by packing_inhouse_size_detail2.sales_order_no");
            
                    
             foreach($TodayFinishedGoodsStock as $row)
        {
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            WHERE carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND carton_packing_inhouse_size_detail2.cpki_date <= '".date('Y-m-d')."'");
            
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            WHERE transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND transfer_packing_inhouse_size_detail2.tpki_date <= '".date('Y-m-d')."'");
            
            $todayFGStock = $todayFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $todayFGValue = $todayFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
             
            echo ' TodayFGValue:'.$todayFGValue;
            
        $monthFGStock = 0;
        $monthFGValue = 0;
        
        $MonthFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,
        packing_inhouse_size_detail2.size_id,packing_inhouse_size_detail2.color_id,
        packing_inhouse_size_detail2.Ac_code, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
        buyer_purchse_order_master.order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            WHERE buyer_purchse_order_master.job_status_id = 1 AND  pki_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
            GROUP by packing_inhouse_size_detail2.sales_order_no");
            
        $yearFGStock = 0;
        $yearFGValue = 0;
        
        $YearFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,
        packing_inhouse_size_detail2.size_id,packing_inhouse_size_detail2.color_id,packing_inhouse_size_detail2.Ac_code, 
        ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',buyer_purchse_order_master.order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            WHERE buyer_purchse_order_master.job_status_id = 1 AND pki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
            GROUP by packing_inhouse_size_detail2.sales_order_no");

        
        foreach($MonthFinishedGoodsStock as $row)
        {
            //DB::enableQueryLog();
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            WHERE carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND  carton_packing_inhouse_master.cpki_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            //dd(DB::getQueryLog());
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            WHERE transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND  transfer_packing_inhouse_master.tpki_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $monthFGStock = $monthFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $monthFGValue = $monthFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
        
        
         echo ' MonthFGValue:'.$monthFGValue;
        
        
        foreach($YearFinishedGoodsStock as $row)
        {
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            WHERE carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND carton_packing_inhouse_master.cpki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            WHERE transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND transfer_packing_inhouse_master.tpki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $yearFGStock = $yearFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $yearFGValue = $yearFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
        
         echo ' YearFGValue:'.$yearFGValue;
        
        $todayNonFGStock = 0;
        $todayNonFGValue = 0;
        
        $TodayNonFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,packing_inhouse_size_detail2.size_id,
        packing_inhouse_size_detail2.color_id,packing_inhouse_size_detail2.Ac_code,
        ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', buyer_purchse_order_master.order_rate
        FROM `packing_inhouse_size_detail2`
        LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        WHERE buyer_purchse_order_master.job_status_id in (2,3) AND packing_inhouse_size_detail2.pki_date <= '".date('Y-m-d')."'
        GROUP by packing_inhouse_size_detail2.sales_order_no");
            
        $monthNonFGStock = 0;
        $monthNonFGValue = 0;
        
        $MonthNonFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,packing_inhouse_size_detail2.size_id,
        packing_inhouse_size_detail2.color_id,packing_inhouse_size_detail2.Ac_code, 
        ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', buyer_purchse_order_master.order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            WHERE buyer_purchse_order_master.job_status_id in (2,3) AND pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
            GROUP by packing_inhouse_size_detail2.sales_order_no");
            
        $yearNonFGStock = 0;
        $yearNonFGValue = 0;
        
        $YearNonFinishedGoodsStock = DB::select("SELECT packing_inhouse_size_detail2.sales_order_no,packing_inhouse_size_detail2.size_id,
            packing_inhouse_size_detail2.color_id,packing_inhouse_size_detail2.Ac_code,
            ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', buyer_purchse_order_master.order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            WHERE buyer_purchse_order_master.job_status_id in (2,3) AND  pki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
            GROUP by packing_inhouse_size_detail2.sales_order_no");
            
        foreach($TodayNonFinishedGoodsStock as $row)
        {
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND carton_packing_inhouse_size_detail2.cpki_date <= '".date('Y-m-d')."'");
            
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND transfer_packing_inhouse_size_detail2.tpki_date <= '".date('Y-m-d')."'");
            
            $todayNonFGStock = $todayNonFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $todayNonFGValue =  $todayNonFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
        
        
        echo ' TodayNonValue:'.$todayNonFGValue;
        
        
        
        foreach($MonthNonFinishedGoodsStock as $row)
        {
            //DB::enableQueryLog();
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where  carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND    carton_packing_inhouse_master.cpki_date<= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            //dd(DB::getQueryLog());
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND transfer_packing_inhouse_master.tpki_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $monthNonFGStock = $monthNonFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $monthNonFGValue =  $monthNonFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
        
        
        echo ' MonthNonValue:'.$monthNonFGValue;
        
        foreach($YearNonFinishedGoodsStock as $row)
        {
            $cartonData = DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where  carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
            and carton_packing_inhouse_master.endflag=1 AND carton_packing_inhouse_master.cpki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $transferData = DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where  transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."' 
            and transfer_packing_inhouse_size_detail2.usedFlag=1 
            AND transfer_packing_inhouse_master.tpki_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
            
            $yearNonFGStock = $yearNonFGStock + ($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty);
            $yearNonFGValue = $yearNonFGValue + ((($row->packing_grn_qty - $cartonData[0]->carton_pack_qty - $transferData[0]->transfer_qty) * ($row->order_rate))/100000);
        }
    
    
     echo ' YearNonValue:'.$yearNonFGValue;
        
        $html .='<tr>
                    <td>Fabric - Moving Quantity</td>
                    <td>'.money_format("%!i",round($today_Qty/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_Qty/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_Qty/100000,2)).'</td>
                 </tr> 
                 <tr>
                    <td> Fabric - Moving Value</td>
                    <td>'.money_format("%!i",round($today_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_Value/100000,2)).'</td>
                 </tr> 
                 <tr>
                    <td> Fabric - Non - Moving Quantity </td>
                    <td>'.money_format("%!i",round($today_non_Qty/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_non_Qty/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_non_Qty/100000,2)).'</td>
                  </tr>
                  <tr>
                    <td> Fabric - Non - Moving Value </td>
                    <td>'.money_format("%!i",round($today_non_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_non_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_non_Value/100000,2)).'</td>
                  </tr>
                  <tr>
                    <td> Trims - Moving Value </td>
                    <td>'.money_format("%!i",round($today_Trims_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_Trims_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_Trims_Value/100000,2)).'</td>
                  </tr>
                  <tr>
                    <td> Trims - Non - Moving Value </td>
                    <td>'.money_format("%!i",round($non_today_Trims_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($non_month_Trims_Value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($non_year_Trims_Value/100000,2)).'</td>
                  </tr>
               
                  
                  <tr>
                    <td>FG - Moving Quantity</td>
                    <td>'.money_format("%!.0n", ($todayFGStock/100000)).'</td>
                    <td>'.money_format("%!.0n", ($monthFGStock/100000)).'</td>
                    <td>'.money_format("%!.0n", ($yearFGStock/100000)).'</td>
                  </tr>
                  <tr>
                    <td>FG - Moving Value</td>
                    <td>'.money_format("%!i",round($todayFGValue/100000,2)).'</td>
                    <td>'.money_format("%!i",round($monthFGValue/100000,2)).'</td>
                    <td>'.money_format("%!i",round($yearFGValue/100000,2)).'</td>
                  </tr>
                  <tr>
                    <td>FG - Non - Moving Quantity</td>
                    <td>'.money_format("%!.0n", ($todayNonFGStock/100000)).'</td>
                    <td>'.money_format("%!.0n", ($monthNonFGStock/100000)).'</td>
                    <td>'.money_format("%!.0n", ($yearNonFGStock/100000)).'</td>
                  </tr>
                  <tr>
                    <td>FG - Non - Moving Value</td>
                    <td>'.money_format("%!i",round($todayNonFGValue/100000,2)).'</td>
                    <td>'.money_format("%!i",round($monthNonFGValue/100000,2)).'</td>
                    <td>'.money_format("%!i",round($yearNonFGValue/100000,2)).'</td>
                  </tr>';
                  
            $inventoryStatusArr = array(
            
                array('Fabric - Moving Quantity','Mtr',round($today_Qty/100000,2),round($month_Qty/100000,2),round($year_Qty/100000,2),10,""),
                array('Fabric - Moving Value','Rs',round($today_Value/100000,2),round($month_Value/100000,2),round($year_Value/100000,2),10,""),
                array('Fabric - Non - Moving Quantity','Mtr',round($today_non_Qty/100000,2),round($month_non_Qty/100000,2),round($year_non_Qty/100000,2),10,""),
                array('Fabric - Non - Moving Value','Rs',round($today_non_Value/100000,2),round($month_non_Value/100000,2),round($year_non_Value/100000,2),10,""),
                array('Trims - Moving Value','Rs',round($today_Trims_Value/100000,2),round($month_Trims_Value/100000,2),round($year_Trims_Value/100000,2),10,""),
                array('Trims - Non - Moving Value','Rs',round($non_today_Trims_Value/100000,2),round($non_month_Trims_Value/100000,2),round($non_year_Trims_Value/100000,2),10,""),
               
                array('FG - Moving Quantity','Pcs',round($todayFGStock/100000,2),round($monthFGStock/100000,2),round($yearFGStock/100000,2),10,""),
                array('FG - Moving Value','Rs',round($todayFGValue,2),round($monthFGValue,2),round($yearFGValue,2),10,""),
                array('FG - Non - Moving Quantity','Pcs',round($todayNonFGStock/100000,2),round($monthNonFGStock/100000,2),round($yearNonFGStock/100000,2),10,""),
                array('FG - Non - Moving Value','Rs',round($todayNonFGValue,2),round($monthNonFGValue,2),round($yearNonFGValue,2),10,"")

            );     
            
            $this->tempInsertData($inventoryStatusArr);   
        return response()->json(['html' => $html]);
    } 
    
    public function InventoryWIPValue()
    {
           
            setlocale(LC_MONETARY, 'en_IN');  
       $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
       // DB::table('temp_order_sales_dashboard')->where('table_head', 10)->delete();
           
           //****************************WIP Value Starts******************************
    
    
    
    
    
          
                       
        $todayOrders = DB::select("select 
                  (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.is_opening=0 and packing_inhouse_detail.vendorId=vendor_work_order_master.vendorId 
                 and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177') ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where   vendor_work_order_master.endflag=1
                 AND vendor_work_order_master.vendorId != '177'
                AND vendor_work_order_detail.vw_date <= '".date('Y-m-d')."' group by vendor_work_order_master.vendorId");
      
       
      
        $monthOrders = DB::select("select 
                  (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.is_opening=0 and packing_inhouse_detail.vendorId=vendor_work_order_master.vendorId and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177') ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where   vendor_work_order_master.endflag=1
                 AND vendor_work_order_master.vendorId != '177' 
                 AND vendor_work_order_detail.vw_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') ");
         
        
        $yearOrders = DB::select("select 
                  (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.is_opening=0 and packing_inhouse_detail.vendorId=vendor_work_order_master.vendorId and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177') ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where   vendor_work_order_master.endflag=1
                 AND vendor_work_order_master.vendorId != '177' 
                 AND vendor_work_order_detail.vw_date <=  DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')");
                 
            $today_total_WIP = 0;           
            foreach($todayOrders as $ow)
            {
                $today_total_WIP = $today_total_WIP + $ow->WIP;
            }
            
            $month_total_WIP = 0;           
            foreach($monthOrders as $ow)
            {
                $month_total_WIP = $month_total_WIP + $ow->WIP;
            }
            
            $year_total_WIP = 0;           
            foreach($yearOrders as $ow)
            {
                $year_total_WIP = $year_total_WIP + $ow->WIP;
            }
        
    
        $today_WIP_value = 0;
        $month_WIP_value = 0;
        $year_WIP_value = 0;
        
         
        
        $VendorCutProcessOrderList=DB::select("select   sales_order_no,
        vpo_code, vendorId  from vendor_purchase_order_master 
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
        where buyer_purchse_order_master.job_status_id=1  
        and vendor_purchase_order_master.process_id=1 and vendor_purchase_order_master.endflag!=2 ");
        
           
        $WIPFabricTodayValue=0;
         foreach($VendorCutProcessOrderList as $row)   
             {                   
                  
                
                   // DB::enableQueryLog();
              
                $FabOutDetail=DB::select("SELECT   
                fabric_outward_details.item_code ,vpo_code,
                ifnull(sum(fabric_outward_details.meter),0) as IssueQty,
                inward_details.item_rate as po_rate,
                
                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as Consumption,
                
                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as BOMQTY 
                
                FROM fabric_outward_details  
                INNER join inward_details on inward_details.track_code=fabric_outward_details.track_code
                where  fabric_outward_details.fout_date <='".date('Y-m-d')."' and    vpo_code='".$row->vpo_code."' 
                group by  fabric_outward_details.item_code");
                   
                  // dd(DB::getQueryLog());
              
                            
                 foreach($FabOutDetail as $FabOut)
                 {
                           
                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                    from packing_inhouse_detail
                    where packing_inhouse_detail.pki_date <='".date('Y-m-d')."' and vpo_code = '".$FabOut->vpo_code."'");
                               
                        $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                        $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                        $FGRM = round($FGCons * $PKQty);
                       $WIPFabricTodayValue=  $WIPFabricTodayValue +  round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate);  
                    
                }
                                             
            }
                       
                       //echo $WIPFabricTodayValue.'    |    ';  
                                            
        
        
                    $VendorWorkOrderList=DB::select("select   sales_order_no,
                    vw_code , vendorId   from vendor_work_order_master 
                    inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_work_order_master.sales_order_no
                    where buyer_purchse_order_master.job_status_id=1 and vendor_work_order_master.endflag!=2  ");
                    
                    $WIP_SewingTodayTrimsValue=0;
                    
                     foreach($VendorWorkOrderList as $row)   
                     {                      
                                
                               
                                
                                
                                  // DB::enableQueryLog();
                              
                                $SewingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code ,
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vw_code='".$row->vw_code."' and
                                trimsOutwardDetail.tout_date <= '".date('Y-m-d')."' 
                                group by  trimsOutwardDetail.item_code  ");
                                   
                                // dd(DB::getQueryLog());
                                
                                         
                                       foreach($SewingTrimsOutDetail as $FabOut)
                                         {
                                                $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                                                from stitching_inhouse_detail
                                                where stitching_inhouse_detail.sti_date <= '".date('Y-m-d')."' and 
                                                stitching_inhouse_detail.vw_code='".$row->vw_code."' ");
                                                 
                                                $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                                $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                                $FGRM = round($FGCons * $PKQty);
                                                $WIP_SewingTodayTrimsValue= $WIP_SewingTodayTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)  ;
                                        
                                        //   if(round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)<=0)
                                        //   {
                                        //       echo 'Item Code:'.$FabOut->item_code.' vw_code:'.$row->vw_code; exit;
                                        //   }
                                        
                                         }
              
                     }     
                                            
       // echo $WIP_SewingTodayTrimsValue; exit;
         
                    $VendorPackProcessOrderList=DB::select("select   sales_order_no,
                    vpo_code,   vendorId   from vendor_purchase_order_master 
                    inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
                    where buyer_purchse_order_master.job_status_id=1 and vendor_purchase_order_master.process_id=3 and vendor_purchase_order_master.endflag!=2");
        
        
                    $WIP_PackingTodayTrimsValue=0;
        
                    foreach($VendorPackProcessOrderList as $row)   
                    {                    
                              
                                 
                                 //  DB::enableQueryLog();
                              
                                $PackingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code,  
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vpo_code='".$row->vpo_code."' and  
                                trimsOutwardDetail.tout_date <= '".date('Y-m-d')."'
                                group by  trimsOutwardDetail.item_code");
                                   
                                 //  dd(DB::getQueryLog());
                                       
                                         
                                foreach($PackingTrimsOutDetail as $FabOut)
                                  {       
                                         
                                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty from 
                                    packing_inhouse_detail where pki_date <= '".date('Y-m-d')."' and  vpo_code='".$row->vpo_code."'");
                                    $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                    $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                    $FGRM = round($FGCons * $PKQty);
                                    $WIP_PackingTodayTrimsValue =$WIP_PackingTodayTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate) ; 
                                   }
            
                    }
        
        
        
        $today_WIP_value=$WIP_PackingTodayTrimsValue + $WIP_SewingTodayTrimsValue +$WIPFabricTodayValue;
        
        
        
        
        
        //************************For Month***********************
        //  $VendorCutProcessOrderList=DB::select("select   sales_order_no,
        // vpo_code, vendorId  from vendor_purchase_order_master 
        // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
        // where buyer_purchse_order_master.job_status_id=1  
        // and vendor_purchase_order_master.process_id=1 and vendor_purchase_order_master.endflag!=2 ");
        
           
        $WIPFabricMonthValue=0;
         foreach($VendorCutProcessOrderList as $row)   
             {                   
                  
                
                   // DB::enableQueryLog();
              
                $FabOutDetail=DB::select("SELECT   
                fabric_outward_details.item_code ,vpo_code,
                ifnull(sum(fabric_outward_details.meter),0) as IssueQty,
                inward_details.item_rate as po_rate,
                
                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as Consumption,
                
                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as BOMQTY 
                
                FROM fabric_outward_details  
                INNER join inward_details on inward_details.track_code=fabric_outward_details.track_code
                where  fabric_outward_details.fout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and    vpo_code='".$row->vpo_code."' 
                group by  fabric_outward_details.item_code");
                   
                  // dd(DB::getQueryLog());
              
                            
                 foreach($FabOutDetail as $FabOut)
                 {
                           
                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                    from packing_inhouse_detail
                    where packing_inhouse_detail.pki_date <=DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and vpo_code = '".$FabOut->vpo_code."'");
                               
                        $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                        $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                        $FGRM = round($FGCons * $PKQty);
                       $WIPFabricMonthValue=  $WIPFabricMonthValue +  round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate);  
                    
                }
                                             
            }
                       
                       //echo $WIPFabricMonthValue.'    |    ';  
                                            
        
        
                    // $VendorWorkOrderList=DB::select("select   sales_order_no,
                    // vw_code , vendorId   from vendor_work_order_master 
                    // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_work_order_master.sales_order_no
                    // where buyer_purchse_order_master.job_status_id=1 and vendor_work_order_master.endflag!=2  ");
                    
                    $WIP_SewingMonthTrimsValue=0;
                    
                     foreach($VendorWorkOrderList as $row)   
                     {                      
                                
                               
                                
                                
                                  // DB::enableQueryLog();
                              
                                $SewingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code ,
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vw_code='".$row->vw_code."' and
                                trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                                group by  trimsOutwardDetail.item_code  ");
                                   
                                // dd(DB::getQueryLog());
                                
                                         
                                       foreach($SewingTrimsOutDetail as $FabOut)
                                         {
                                                $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                                                from stitching_inhouse_detail
                                                where stitching_inhouse_detail.sti_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and 
                                                stitching_inhouse_detail.vw_code='".$row->vw_code."' ");
                                                 
                                                $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                                $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                                $FGRM = round($FGCons * $PKQty);
                                                $WIP_SewingMonthTrimsValue= $WIP_SewingMonthTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)  ;
                                        
                                        //   if(round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)<=0)
                                        //   {
                                        //       echo 'Item Code:'.$FabOut->item_code.' vw_code:'.$row->vw_code; exit;
                                        //   }
                                        
                                         }
              
                     }     
                                            
       // echo $WIP_SewingTodayTrimsValue; exit;
         
                    // $VendorPackProcessOrderList=DB::select("select   sales_order_no,
                    // vpo_code,   vendorId   from vendor_purchase_order_master 
                    // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
                    // where buyer_purchse_order_master.job_status_id=1 and vendor_purchase_order_master.process_id=3 and vendor_purchase_order_master.endflag!=2");
        
        
                    $WIP_PackingMonthTrimsValue=0;
        
                    foreach($VendorPackProcessOrderList as $row)   
                    {                    
                              
                                 
                                 //  DB::enableQueryLog();
                              
                                $PackingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code,  
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vpo_code='".$row->vpo_code."' and  
                                trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d')
                                group by  trimsOutwardDetail.item_code");
                                   
                                 //  dd(DB::getQueryLog());
                                       
                                         
                                foreach($PackingTrimsOutDetail as $FabOut)
                                  {       
                                         
                                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty from 
                                    packing_inhouse_detail where pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), '%Y-%m-%d') and  vpo_code='".$row->vpo_code."'");
                                    $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                    $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                    $FGRM = round($FGCons * $PKQty);
                                    $WIP_PackingMonthTrimsValue =$WIP_PackingMonthTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate) ; 
                                   }
            
                    }
        
        
        
        $month_WIP_value=$WIPFabricMonthValue + $WIP_SewingMonthTrimsValue +$WIP_PackingMonthTrimsValue;
        
        
        
        
        
        
        
        
        
         //************************For Year***********************
        //  $VendorCutProcessOrderList=DB::select("select   sales_order_no,
        // vpo_code, vendorId  from vendor_purchase_order_master 
        // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
        // where buyer_purchse_order_master.job_status_id=1  
        // and vendor_purchase_order_master.process_id=1 and vendor_purchase_order_master.endflag!=2 ");
        
           
        $WIPFabricYearValue=0;
         foreach($VendorCutProcessOrderList as $row)   
             {                   
                  
                
                   // DB::enableQueryLog();
              
                $FabOutDetail=DB::select("SELECT   
                fabric_outward_details.item_code ,vpo_code,
                ifnull(sum(fabric_outward_details.meter),0) as IssueQty,
                inward_details.item_rate as po_rate,
                
                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as Consumption,
                
                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_fabric_details 
                where vendor_purchase_order_fabric_details.vpo_code='".$row->vpo_code."'   
                and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code),0) as BOMQTY 
                
                FROM fabric_outward_details  
                INNER join inward_details on inward_details.track_code=fabric_outward_details.track_code
                where  fabric_outward_details.fout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and    vpo_code='".$row->vpo_code."' 
                group by  fabric_outward_details.item_code");
                   
                  // dd(DB::getQueryLog());
              
                            
                 foreach($FabOutDetail as $FabOut)
                 {
                           
                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                    from packing_inhouse_detail
                    where packing_inhouse_detail.pki_date <=DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and vpo_code = '".$FabOut->vpo_code."'");
                               
                        $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                        $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                        $FGRM = round($FGCons * $PKQty);
                       $WIPFabricYearValue=  $WIPFabricYearValue +  round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate);  
                    
                }
                                             
            }
                       
                       //echo $WIPFabricYearValue.'    |    ';  
                                            
        
        
                    // $VendorWorkOrderList=DB::select("select   sales_order_no,
                    // vw_code , vendorId   from vendor_work_order_master 
                    // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_work_order_master.sales_order_no
                    // where buyer_purchse_order_master.job_status_id=1 and vendor_work_order_master.endflag!=2  ");
                    
                    $WIP_SewingYearTrimsValue=0;
                    
                     foreach($VendorWorkOrderList as $row)   
                     {                      
                                
                               
                                
                                
                                  // DB::enableQueryLog();
                              
                                $SewingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code ,
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_work_order_sewing_trims_details 
                                where vendor_work_order_sewing_trims_details.vw_code='".$row->vw_code."'  
                                and vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vw_code='".$row->vw_code."' and
                                trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
                                group by  trimsOutwardDetail.item_code  ");
                                   
                                // dd(DB::getQueryLog());
                                
                                         
                                       foreach($SewingTrimsOutDetail as $FabOut)
                                         {
                                                $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty
                                                from stitching_inhouse_detail
                                                where stitching_inhouse_detail.sti_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and 
                                                stitching_inhouse_detail.vw_code='".$row->vw_code."' ");
                                                 
                                                $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                                $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                                $FGRM = round($FGCons * $PKQty);
                                                $WIP_SewingYearTrimsValue= $WIP_SewingYearTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)  ;
                                        
                                        //   if(round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate)<=0)
                                        //   {
                                        //       echo 'Item Code:'.$FabOut->item_code.' vw_code:'.$row->vw_code; exit;
                                        //   }
                                        
                                         }
              
                     }     
                                            
       // echo $WIP_SewingTodayTrimsValue; exit;
         
                    // $VendorPackProcessOrderList=DB::select("select   sales_order_no,
                    // vpo_code,   vendorId   from vendor_purchase_order_master 
                    // inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
                    // where buyer_purchse_order_master.job_status_id=1 and vendor_purchase_order_master.process_id=3 and vendor_purchase_order_master.endflag!=2");
        
        
                    $WIP_PackingYearTrimsValue=0;
        
                    foreach($VendorPackProcessOrderList as $row)   
                    {                    
                              
                                 
                                 //  DB::enableQueryLog();
                              
                                $PackingTrimsOutDetail=DB::select("SELECT   
                                trimsOutwardDetail.item_code,  
                                ifnull(sum(trimsOutwardDetail.item_qty),0) as IssueQty,
                                trimsOutwardDetail.item_rate as po_rate,
                                
                                ifnull((select ROUND(AVG(final_cons),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as Consumption,
                                
                                ifnull((select ROUND(AVG(bom_qty),4) from vendor_purchase_order_packing_trims_details 
                                where vendor_purchase_order_packing_trims_details.vpo_code='".$row->vpo_code."'  
                                and vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code),0) as BOMQTY 
                                
                                FROM trimsOutwardDetail  
                                where trimsOutwardDetail.vpo_code='".$row->vpo_code."' and  
                                trimsOutwardDetail.tout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')
                                group by  trimsOutwardDetail.item_code");
                                   
                                 //  dd(DB::getQueryLog());
                                       
                                         
                                foreach($PackingTrimsOutDetail as $FabOut)
                                  {       
                                         
                                    $FGQty=DB::select("select ifnull(sum(size_qty_total),0) as PKQty from 
                                    packing_inhouse_detail where pki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d') and  vpo_code='".$row->vpo_code."'");
                                    $FGCons=  $FabOut->Consumption ? $FabOut->Consumption : 0 ;
                                    $PKQty=  isset($FGQty[0]->PKQty) ? $FGQty[0]->PKQty : 0;
                                    $FGRM = round($FGCons * $PKQty);
                                    $WIP_PackingYearTrimsValue =$WIP_PackingYearTrimsValue + round(($FabOut->IssueQty - $FGRM) * $FabOut->po_rate) ; 
                                   }
            
                    }
        
        
        
        $year_WIP_value=$WIPFabricYearValue + $WIP_SewingYearTrimsValue +$WIP_PackingYearTrimsValue;
        
         
        
        
        $html='';
        
       // echo $today_WIP_value; exit;
        
        	$html.='
        	   <tr>
                    <td>WIP -  Quantity</td>
                    <td>'.money_format("%!.0n",round($today_total_WIP/100000,2)).'</td>
                    <td>'.money_format("%!.0n",round($month_total_WIP/100000,2)).'</td>
                    <td>'.money_format("%!.0n",round($year_total_WIP/100000,2)).'</td>
                  </tr> 
        	
        	        <tr>
                    <td>WIP - Value</td>
                    <td>'.money_format("%!i",round($today_WIP_value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($month_WIP_value/100000,2)).'</td>
                    <td>'.money_format("%!i",round($year_WIP_value/100000,2)).'</td>
                  </tr>';
				  
				  
		$inventoryStatusArr = 
		array(
             array('WIP -  Quantity','Pcs',round($today_total_WIP/100000,2),round($month_total_WIP/100000,2),round($year_total_WIP/100000,2),10,""),
             array('WIP -  Value','Rs',round($today_WIP_value/100000,2),round($month_WIP_value/100000,2),round($year_WIP_value/100000,2),10,""),
              
            );      
            $this->tempInsertData($inventoryStatusArr);   
        return response()->json(['html' => $html]);
        
        
      ///  exit;
        
        
        
        
        
        
   //****************************WIP Value Ends******************************  
    }
    
    public function tempInsertData($dataArr)
    {
        foreach($dataArr as $key => $value)
        {
            DB::table('temp_order_sales_dashboard')->insert([
                
                "key_Indicators"=>$dataArr[$key][0],
                "uom"=>$dataArr[$key][1],
                "today"=>$dataArr[$key][2],
                "month_to_date"=>$dataArr[$key][3],
                "year_to_date"=>$dataArr[$key][4],
                "table_head"=>$dataArr[$key][5],
                "company_name"=>$dataArr[$key][6],
                ]);
        }
        return 1;
    }
    public function SalesOrderDetailDashboard()
    {
          $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: select('buyer_purchse_order_master.*','sales_order_costing_master.sam',DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
            , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty')
            )
            ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('buyer_purchse_order_master.og_id','!=', '4')
             ->where('buyer_purchse_order_master.job_status_id','=', '1')
            ->get();
         
            $total_valuec=0;
            $total_qtyc=0;
            $total_order_min=0;
            $total_shipped_qtyc= 0;
            $total_shipped_min = 0;
            $total_balance_qty = 0;
            $total_balance_min = 0;
            $total_produce_qty = 0;
            $total_produce_min = 0;
            $FGStock = 0;
            $total_fabric_value = 0;
            $total_trim_value = 0;
            $total_FGStock = 0;
            
            foreach($Buyer_Purchase_Order_List as $row)
            {
                $total_valuec=$total_valuec + $row->order_value; 
                $total_qtyc=$total_qtyc+$row->total_qty; 
                $total_order_min= $total_order_min + (($row->sam) * ($row->total_qty)); 
                $total_shipped_qtyc=$total_shipped_qtyc+$row->shipped_qty;
                
                $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                     
                $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                
                $total_shipped_min = $total_shipped_min + ($Ship * $row->sam);
                $total_balance_qty = $total_balance_qty + $row->balance_qty;
                $total_balance_min = $total_balance_min + ($row->balance_qty * $row->sam);
                
                $FGStockData = DB::select("SELECT ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty' FROM `packing_inhouse_size_detail2` WHERE  packing_inhouse_size_detail2.sales_order_no = '". $row->tr_code."' GROUP by packing_inhouse_size_detail2.sales_order_no");
                    // dd(DB::getQueryLog());    
                 
                if($FGStockData != null)
                { 
                    $FGStock = $FGStockData[0]->packing_grn_qty - $FGStockData[0]->carton_pack_qty -  $FGStockData[0]->transfer_qty;
                }
                 
                $total_produce_qty = $total_produce_qty + ($row->balance_qty - $FGStock);
                $total_produce_min = $total_produce_min + ($row->sam * ($row->balance_qty - $FGStock)); 
                
                
            }
            
            $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
            foreach($FabricInwardDetails as $row)
            {
                $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
            }
            
            
            
            $TrimsInwardDetails = DB::select("select trimsInwardDetail.*,(select ifnull(sum(item_qty),0) as item_qty  from trimsOutwardDetail 
                where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty 
                from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
                inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
                inner join item_master on item_master.item_code=trimsInwardDetail.item_code
                inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
                
            foreach($TrimsInwardDetails as $rows)
            {
                $total_trim_value = $total_trim_value + ((($rows->out_qty) - ($rows->item_qty)) * $rows->item_rate);
            }
            
            $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                        DB::raw('sum(Gst_amount) as TotalGst'),
                        DB::raw('sum(Net_amount) as TotalNet'),
                        DB::raw('sum(total_qty) as TotalQty'))
                        ->where('sale_transaction_master.delflag','=', '0')
                        ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
                        ->get();
        
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
                from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
             
            $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
                    
                    (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
                    inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                    where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and carton_packing_inhouse_master.endflag=1
                    ) as 'carton_pack_qty',
                    
                     (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
                    inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
                    where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
                    transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
                    and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
                    and transfer_packing_inhouse_size_detail2.usedFlag=1
                    ) as 'transfer_qty',
                    
                    
                    order_rate
                    FROM `packing_inhouse_size_detail2`
                    LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                    LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                    LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
                    GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
                // dd(DB::getQueryLog());    
                    
            foreach($FGStockData1 as $row1)
            {
                $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
            }
             $html = '';
             $html .='<tr>
                    <td class="text-center">Total Live Orders</td>
                    <td class="text-center">'.number_format((double)($total_qtyc/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_order_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Delivered</td>
                    <td class="text-center">'.number_format((double)($total_shipped_qtyc/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_shipped_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Balance To Ship</td>
                    <td class="text-center">'.number_format((double)($total_balance_qty/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_balance_min/100000), 2, '.', '').'</a></td>
                 </tr>
                 <tr>
                    <td class="text-center">Balance To Produce</td>
                    <td class="text-center">'.number_format((double)($total_produce_qty/100000), 2, '.', '').'</td>
                    <td class="text-center"><a href="/OpenSalesOrderDetailDashboard">'.number_format((double)($total_produce_min/100000), 2, '.', '').'</a></td>
                 </tr>';
                 
         return response()->json(['html' => $html]);
    }
    
    public function SalesDashboard()
    {
        setlocale(LC_MONETARY, 'en_IN');
        
         $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

        $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
                    DB::raw('sum(Gst_amount) as TotalGst'),
                    DB::raw('sum(Net_amount) as TotalNet'),
                    DB::raw('sum(total_qty) as TotalQty'))
                    ->where('sale_transaction_master.delflag','=', '0')
                    ->whereBetween('sale_date',array($Financial[0]->fdate, $Financial[0]->tdate))
                    ->get();
    
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
            from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
        $html = '';
        $html .='<tr>
            <td class="text-center">Monthly</td> 
            <td class="text-center">400.00</td>
            <td class="text-center"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.number_format((double)($MonthList[0]->total_sale_value/100000), 2, '.', '').'</a></td>
            <td class="text-center">'.number_format((double)((($MonthList[0]->total_sale_value/400) * 100)/100000), 2, '.', '').'%</td>
         </tr>
         <tr>
            <td class="text-center">Yearly</td>
            <td class="text-center">5,000.00</td>
            <td class="text-center"><a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',$SaleTotal[0]->TotalGross/100000).'</a></td>
            <td class="text-center">'.number_format((double)((($SaleTotal[0]->TotalGross/5000) * 100)/100000), 2, '.', '').'%</td>
         </tr>';       
         
        return response()->json(['html' => $html]);
    }
    
    public function RawMaterialDashboard()
    {  
        $total_fabric_value = 0;
        $total_trim_value = 0;
        
        $FabricInwardDetails =DB::select("select inward_details.*, (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter from inward_details");
            
        foreach($FabricInwardDetails as $row)
        {
            $total_fabric_value = $total_fabric_value + ($row->meter-$row->out_meter) * $row->item_rate;
        }
        
        $TrimsInwardDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
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
       group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
       ");
       
       if(count($TrimsInwardDetails)>0)
       {
           foreach($TrimsInwardDetails as $yrow) 
           {
                $total_trim_value=$total_trim_value + (($yrow->item_qty-$yrow->out_qty) *$yrow->item_rate);
           }
       }     
        
        $html = '';
        $html .='<tr>
                <td class="text-center">Fabric</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center"><a href="/FabricStockSummaryData">'.number_format((double)($total_fabric_value/100000), 2, '.', '').'</a></td>
             </tr>
             <tr>
                <td class="text-center">Trims</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center"><a href="/TrimsStockData">'.number_format((double)($total_trim_value/100000), 2, '.', '').'</a></td>
             </tr>';
        return response()->json(['html' => $html]);
    }
    
    public function Finishing()
    {  
        $total_FGStock = 0;
        
        $FGStockData1 = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
            (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
            inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
            where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and carton_packing_inhouse_master.endflag=1
            ) as 'carton_pack_qty',
            
             (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and transfer_packing_inhouse_size_detail2.usedFlag=1
            ) as 'transfer_qty',
            
            
            order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
            LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
            GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
            // dd(DB::getQueryLog());    
                
        foreach($FGStockData1 as $row1)
        {
            $total_FGStock = $total_FGStock + (($row1->packing_grn_qty - $row1->carton_pack_qty -  $row1->transfer_qty)) * ($row1->order_rate);
        }
        
        $html = '';
        $html .=' <tr>
                    <td class="text-center">Garments</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">'.number_format((double)($total_FGStock/100000), 2, '.', '').'</td>
                 </tr>';
                 
        return response()->json(['html' => $html]);
    }
    
    public function OrderStatus()
    {  
        
         setlocale(LC_MONETARY, 'en_IN');
        
        $total_FGStock = 0;
        
        $DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
            BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
            SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
            SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
            BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
            SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
            
            $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
            
            $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and 
            MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
            
            $YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
            from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and 
            order_received_date between (select fdate from financial_year_master 
            where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        $html = '';
        $html .=' <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Volume (Pcs) In Lakh</b>	</td>
                        <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_TD_P).'</td>';
                        if($TodayList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_order_qty/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_TD_P) * (100)),2)).'%</td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_M_TO_Dt_P).'</td>';
                        if($MonthList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',(round((($MonthList[0]->total_order_qty/100000)),2))).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_M_TO_Dt_P) * (100)),2)).'%</td>';
                        } 
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;"> 0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                       
                        $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");

                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VOL_Yr_TO_Dt_P).'</td>';
                        if($YearList[0]->total_order_qty!=0)
                        {
                             $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_order_qty/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_Yr_TO_Dt_P) * (100)),2)).'% </td>';
                        }
                        else
                        {
                        $html .='<td style="border: black 0.5px solid;">0.00</td>
                                <td style="border: black 0.5px solid;"> 0.00</td>';
                        }
                     $html .='</tr>';
                     $html .='<tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Value in In Lakh</b></td>
                        <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VAL_TD_P).'</td>';
                        if($TodayList[0]->total_order_qty!=0)
                        {
                           $html .=' <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_TD_P) *(100)),2)).' % </td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;" >'.money_format('%!i',$DBoard[0]->BK_VAL_M_TO_Dt_P).'</td>';
                        if($MonthList[0]->total_order_qty!=0)
                        {
                            $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_M_TO_Dt_P) *(100)),2)).'%</td>';
                        }
                        else
                        {
                            $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                        $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->BK_VAL_Yr_TO_Dt_P).'</td>';
                        if($YearList[0]->total_order_qty!=0)
                        {
                           $html .='<td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_order_value/100000),2)).'</a></td>
                            <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_Yr_TO_Dt_P) *(100)),2)).'%</td>';
                        }
                        else
                        {
                             $html .='<td style="border: black 0.5px solid;">0.00</td>
                            <td style="border: black 0.5px solid;">0.00</td>';
                        }
                      $html .='</tr>';
                 
        return response()->json(['html' => $html]);
    }
    
    public function SaleStatus()
    {  
        
      setlocale(LC_MONETARY, 'en_IN');   
        
        $SaleTotal = SaleTransactionMasterModel::select(DB::raw('sum(Gross_amount) as TotalGross'),
            DB::raw('sum(Gst_amount) as TotalGst'),
            DB::raw('sum(Net_amount) as TotalNet'),
            DB::raw('sum(total_qty) as TotalQty'))
            ->where('sale_transaction_master.delflag','=', '0')
            ->whereBetween('sale_date',array('2022-04-01','2023-04-01'))
            ->get();
        
        $DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
            BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
            SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
            SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
            BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
            SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
        
        $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
        
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
        
        $YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
        from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and order_received_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=1) and (select tdate from financial_year_master where financial_year_master.fin_year_id=1)");
        
        $TodayList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
        from sale_transaction_master where `sale_date`=CURRENT_DATE()");
        
        $MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
        from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
        
        $YearList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value 
        from sale_transaction_master where sale_date between (select fdate from financial_year_master 
        where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)");
        
        $html = '';
        
        $html .='<tr style="color:black; text-align:right;">
            <td style="border: black 0.5px solid;"><b>Sale Volume (Pcs) In Lakh</b>	</td>
            <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_TD_P).'</td>';
            if($TodayList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_TD_P) * (100)),2)).' %</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
             $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_M_TO_Dt_P).'</td>';
            if($MonthList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_M_TO_Dt_P) * (100)),2)).' %</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VOL_Yr_TO_Dt_P).'</td>';
            
            $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
            
            if($YearList[0]->total_sale_qty!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($YearList[0]->total_sale_qty/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($YearList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_Yr_TO_Dt_P) * (100)),2)).' % </td>';
            }
            else
            {
                 $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;"> 0.00</td>';
            }
        $html .='</tr>';
         
        $html .='<tr style="color:black;text-align:right; ">
         
            <td style="border: black 0.5px solid;"><b>Sale Value in In Lakh</b></td>
            <td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_TD_P).'</td>';
            if($TodayList[0]->total_sale_value!=0)
            {
                $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',round(($TodayList[0]->total_sale_value/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($TodayList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_TD_P) * (100)),2)).' % </td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_M_TO_Dt_P).'</td>';
            if($MonthList[0]->total_sale_value!=0)
            {
                 $html .='<td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',round(($MonthList[0]->total_sale_value/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round(((($MonthList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_M_TO_Dt_P) * (100)),2)).'%</td>';
            }
            else
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
            $html .='<td style="border: black 0.5px solid;">'.money_format('%!i',$DBoard[0]->SAL_VAL_Yr_TO_Dt_P).'</td>';
            if($YearList[0]->total_sale_value!=0)
            {
                $html .='<td style="border: black 0.5px solid;"> <a href="/SaleFilterReport?fdate='.$Financial[0]->fdate.'&tdate='.$Financial[0]->tdate.'">'.money_format('%!i',round(($SaleTotal[0]->TotalGross/100000),2)).'</a></td>
                <td style="border: black 0.5px solid;">'.money_format('%!i',round((((($SaleTotal[0]->TotalGross/5000) * 100)/100000) * (100)),2)).'%</td>';  
            }
            else   
            {
                $html .='<td style="border: black 0.5px solid;">0.00</td>
                <td style="border: black 0.5px solid;">0.00</td>';
            }
        $html .='</tr>';
       return response()->json(['html' => $html]);
    }
    
    public function FabricStatus()
    {  
        
     setlocale(LC_MONETARY, 'en_IN');    
        
        $today_meter=0; 
        $today_amount=0;
        $month_meter=0; 
        $month_amount=0;
        $year_meter=0; 
        $year_amount=0;
        
        $TodayDetails =DB::select("select inward_details.meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        (inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details where in_date=CURRENT_DATE()");
        
        if(count($TodayDetails)>0)
        {
            foreach($TodayDetails as $trow) 
            {
                $today_meter=$today_meter + $trow->meter;
                $today_amount=$today_amount + ($trow->meter *$trow->item_rate);
            }
        }   
        
        $MonthDetails =DB::select("select inward_details.meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        (inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details where MONTH(in_date)=MONTH(CURRENT_DATE()) and YEAR(in_date)=YEAR(CURRENT_DATE())");
        
        if(count($MonthDetails)>0)
        {
            foreach($MonthDetails as $mrow) 
            {
                $month_meter=$month_meter + $mrow->meter;
                $month_amount=$month_amount + ($mrow->meter *$mrow->item_rate);
                }
        } 
        
        $year_meter=0;
        
        $YearDetails =DB::select("select sum(inward_details.meter) as in_meter,inward_details.item_rate,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code) as out_meter,
        (sum(inward_details.meter) - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
        from inward_details group by inward_details.track_code");
        
        if(count($YearDetails)>0)
        {
            foreach($YearDetails as $yrow) 
            {
                $year_meter=$year_meter + ($yrow->in_meter - $yrow->out_meter);
                $year_amount=$year_amount + ($yrow->StockMeter *$yrow->item_rate);
            }
        }   

        $html ='';
        $html .='<tr style="color:black;">
                    <td><b>Inward Today</b>	</td>
                    <td style="text-align:right;color:black; ">  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_meter/100000 )).'</a> </td>
                    <td style="color:black;text-align:right;"  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_amount/100000)).'</a></td>
                 </tr>
                 <tr style="color:black;">
                    <td><b>Inward MTD	</b></td>
                    <td style="text-align:right;color:black;">
                       <a href="/FabricGRNFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">
                          '.money_format('%!i',($month_meter/100000 )).'
                    </td>
                    <td style="text-align:right;color:black;">  <a href="/FabricGRNFilterReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',($month_amount/100000)).'</td>
                 </tr >
                 <tr style="color:black;">
                 <td><b>Fabric Stock</b>	</td>
                 <td style="text-align:right;color:black;"><a href="FabricInOutStockReport?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'" >'.money_format('%!i',($year_meter/100000)).'</a> </td>
                 <td style="text-align:right;color:black;"><a href="FabricStockSummaryData" >'.money_format('%!i',($year_amount/100000)).'</a></td>
                 </tr>';
        return response()->json(['html' => $html]);
    }
    
    public function FinishingGoodsStatus()
    {       
        
       setlocale(LC_MONETARY, 'en_IN');  
        
       $today_packed=0; 
       $today_amount=0;
       $month_packed=0; 
       $month_amount=0;
       $year_packed=0; 
       $year_amount=0;
       
       $TodayDetails =DB::select("SELECT sales_order_no, `order_rate`, 
       ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
       from packing_inhouse_master
       inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
       where pki_date=CURRENT_DATE() group by sales_order_no");
       
       if(count($TodayDetails)>0)
       {
           foreach($TodayDetails as $trow) 
           {
               $today_packed=$today_packed + $trow->Packed_Qty;
               $today_amount=$today_amount + ($trow->Packed_Qty *$trow->order_rate);
           }
       }   
       
       $MonthDetails =DB::select("SELECT sales_order_no, `order_rate`, 
       ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
       from packing_inhouse_master
        inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
       where   MONTH(pki_date)=MONTH(CURRENT_DATE()) and YEAR(pki_date)=YEAR(CURRENT_DATE()) group by sales_order_no ");
       
       if(count($MonthDetails)>0)
       {
           foreach($MonthDetails as $mrow) 
           {
               $month_packed=$month_packed + $mrow->Packed_Qty;
               $month_amount=$month_amount + ($mrow->Packed_Qty *$mrow->order_rate);
           }
       }   
       
       $YearDetails =DB::select("SELECT sales_order_no, `order_rate`, 
           ifnull(sum(packing_inhouse_detail.size_qty_total),0) as Packed_Qty, (select ifnull(sum(sale_transaction_detail.order_qty),0) 
           from sale_transaction_detail where sales_order_no=packing_inhouse_detail.sales_order_no) as 'sold' 
           ,(SELECT ifnull(sum(transfer_packing_inhouse_detail.size_qty_total),0)
           from transfer_packing_inhouse_detail where transfer_packing_inhouse_detail.usedFlag=1 and  main_sales_order_no=packing_inhouse_detail.sales_order_no) as TransferQty
           FROM `packing_inhouse_detail`
           inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_detail.sales_order_no
           group by sales_order_no ");
       
       $Totalstock=0;
       if(count($YearDetails)>0)
       {
           foreach($YearDetails as $yrow) 
           {
               $year_packed=$yrow->Packed_Qty - $yrow->sold - $yrow->TransferQty;
               $Totalstock=$Totalstock+$year_packed;
               $year_amount=round(($year_amount + ($yrow->Packed_Qty * $yrow->order_rate)),2);
           }
       }  
       $html ='';
       $html .='<tr style="color:black;">
                        <td><b>Inward Today</b>	</td>
                        <td style="text-align:right;">'.money_format('%!i',($today_packed/100000 )).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($today_amount/100000)).'</td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Inward MTD</b>	</td>
                        <td style="text-align:right;">'.money_format('%!i',($month_packed/100000 )).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($month_amount/100000)).'</td>
                     </tr >
                     <tr style="color:black;">
                        <td><b>FG Stock</b>	</td>
                        <td style="text-align:right;"><a href="/FGStockSummaryReport">'.money_format('%!i',($Totalstock/100000)).'</a> </td>
                        <td style="text-align:right;"><a href="/FGStockReport">'.money_format('%!i',($year_amount/100000)).'</a></td>
                     </tr>';
       return response()->json(['html' => $html]);
    }
    
    public function TrimStatus()
    {   
        
     setlocale(LC_MONETARY, 'en_IN');    
        
       $today_qty=0; 
       $today_amount=0;
       $month_qty=0; 
       $month_amount=0;
       $year_qty=0; 
       $year_amount=0;
       
       $TodayDetails =DB::select("select ifnull((trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate 
       from trimsInwardDetail   where trimDate=CURRENT_DATE()");
       
       if(count($TodayDetails)>0)
       {  
           foreach($TodayDetails as $trow) 
           {
               $today_qty=$today_qty + $trow->item_qty;
               $today_amount=$today_amount + ($trow->item_qty * $trow->item_rate);
           }
       } 
       
       $MonthDetails =DB::select("select ifnull( (trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate
       from trimsInwardDetail where MONTH(trimDate)=MONTH(CURRENT_DATE()) and YEAR(trimDate)=YEAR(CURRENT_DATE())");
       
       if(count($MonthDetails)>0)
       {
           foreach($MonthDetails as $mrow) 
           {
                $month_amount=$month_amount + ($mrow->item_qty * $mrow->item_rate);
           }
       }   

       $TrimsInwardDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
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
       group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
       ");
       
       if(count($TrimsInwardDetails)>0)
       {
           foreach($TrimsInwardDetails as $yrow) 
           {
                $year_amount=$year_amount + (($yrow->item_qty-$yrow->out_qty) *$yrow->item_rate);
           }
       }     
       
       $html ='';
       $html .='<tr style="color:black;">
                <td><b>Inward Today</b>	</td>
                <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate='.date('Y-m-d').'&tdate='.date('Y-m-d').'">'.money_format('%!i',($today_amount/100000)).'</a></td>
             </tr>
             <tr style="color:black;">
                <td><b>Inward MTD</b>	</td>
                <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate='.date('Y-m-01').'&tdate='.date('Y-m-t').'">'.money_format('%!i',($month_amount/100000)).'</a></td>
             </tr>
             <tr style="color:black;">
                <td><b>Trim Stock</b>	</td>
                <td style="text-align:right;"><a href="TrimsStockData">'.money_format('%!i',($year_amount/100000)).'</a></td>
             </tr>';
       return response()->json(['html' => $html]);
    }
    
    public function WorkInProgressStatus()
    {   
        
       setlocale(LC_MONETARY, 'en_IN');  
        
           $no = 1;
           $total_WQty=0; 
           $total_cut_panel_issue=0; 
           $total_packing_qty=0; 
           $total_WIP=0; 
           $total_issue_meter=0;
           
           $JobWorkers=DB::select('select distinct(vendorId) , Ac_name  from vendor_work_order_master 
           left join ledger_master on ledger_master.Ac_code=vendor_work_order_master.vendorId');
       
           $html1 ='';
           $html2 ='';
           
            foreach($JobWorkers as $rowJW)
            {
                 $orders=DB::select("select ifnull(sum(vendor_work_order_detail.size_qty_total),0) as wqty,
                 (select ifnull(sum(meter),0) from fabric_outward_details
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code
                 where fabric_outward_details.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1)   as issue_meter ,
                 (select ifnull(sum(size_qty_total),0) from cut_panel_issue_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=cut_panel_issue_detail.vw_code
                 where cut_panel_issue_detail.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1)   as cut_panel_issue_qty ,
                 (select ifnull(sum(size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on  vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177')  as packing_grn_qty ,
                 (ifnull(sum(vendor_work_order_detail.size_qty_total),0) - (select ifnull(sum(packing_inhouse_detail.size_qty_total),0) from packing_inhouse_detail
                 inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                 where packing_inhouse_detail.vendorId='".$rowJW->vendorId."' and vendor_purchase_order_master.endflag=1 AND vendor_work_order_master.vendorId != '177') ) as WIP
                 from vendor_work_order_detail
                 inner join vendor_work_order_master on vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                 where vendor_work_order_master.vendorId='".$rowJW->vendorId."' and vendor_work_order_master.endflag=1
                 AND vendor_work_order_master.vendorId != '177'
                 order by WIP");
            
                 foreach($orders as $ow)
                 {
                         $total_WQty=$total_WQty + $ow->wqty;
                         $total_cut_panel_issue=$total_cut_panel_issue + $ow->cut_panel_issue_qty;
                         $total_packing_qty=$total_packing_qty + $ow->packing_grn_qty;
                         $total_WIP=$total_WIP + $ow->WIP;
                         $total_issue_meter=$total_issue_meter+$ow->issue_meter;
                         if((($ow->wqty) + ($ow->cut_panel_issue_qty)  + ($ow->packing_grn_qty)  + ($ow->WIP))!=0)
                         {
                		     $html1 .='<tr>
                                            <td class="text-center">'.$no++.'</td>
                                            <td>'.$rowJW->Ac_name.'</td>
                                            <td class="text-center">'.number_format($ow->wqty).'</td>
                                            <td class="text-center">'.number_format($ow->cut_panel_issue_qty).'</td>
                                            <td class="text-center">'.number_format($ow->packing_grn_qty).'</td>
                                            <td class="text-center"><a href="'.url("WIPDetailReport", [$rowJW->vendorId]).'" target="_blank">'.number_format($ow->WIP).'</a></td>
                                       </tr>';
                        }
                 
                }
            }
                
    		$html2 .='<tr>                
        				<th></th>            
        				<th>Total :</th>
        				<th>'.money_format('%!.0n',round($total_WQty)).'</th>
        				<th>'.money_format('%!.0n',round($total_cut_panel_issue)).'</th>
        				<th>'.money_format('%!.0n',round($total_packing_qty)).'</th>
        				<th>'.money_format('%!.0n',round($total_WIP)).'</th>
        			</tr>';
    			
            return response()->json(['html1' => $html1,'html2' => $html2]);
    }
    
    public function GarmentSale()
    {     
        
     setlocale(LC_MONETARY, 'en_IN');    
        
         $MonthSale =DB::select("SELECT DATE_FORMAT(`sale_date`, '%b-%Y') AS SaleDate, sale_date,
         ifnull(sum(`total_qty`),0) as soldQty,     (sum(Gross_amount)/ifnull(sum(`total_qty`),0)) as Rate,
         sum(Gross_amount) as Taxable_Amount FROM sale_transaction_master where
         sale_transaction_master.sale_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY SaleDate ORDER BY sale_date ASC");
         
         $totalAmt=0;
         $AvrRate=0;
         $TotalPcs=0;
         $no=0;
         $html1 ='';
         $html2 ='';
         
        if(count($MonthSale)>0)
        {
            foreach($MonthSale as $mrow) 
            {
                $month=date('m', strtotime($mrow->sale_date));
             
                $html1 .='<tr style="color:black;">
                    <td><b>'.$mrow->SaleDate.'</b>	</td>
                    <td style="text-align:right;"><a href="/SaleFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->sale_date)).'">'.money_format('%!i',($mrow->soldQty/100000 )).'</a></td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Rate )).'</td>
                    <td style="text-align:right;"><a href="/SaleFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->sale_date)).'">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</a></td>
                    </tr>';
             
             $totalAmt=$totalAmt + ($mrow->Taxable_Amount/100000);
             $TotalPcs=$TotalPcs + ($mrow->soldQty/100000);
             $AvrRate=$AvrRate + $mrow->Rate;
             $no=$no+1;
            }
            
            $html2 .='<tr>
                        <td><b>Total</b></td>
                        <td style="text-align:right;">'.money_format('%!i',$TotalPcs).'</td>
                        <td style="text-align:right;">'.money_format('%!i',($AvrRate/$no)).'</td>
                        <td style="text-align:right;">'.money_format('%!i',$totalAmt).'</td>
                     </tr>';
        }
       
       return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function GarmentPurchase()
    {   
        
     setlocale(LC_MONETARY, 'en_IN');    
        
        $totalAmt=0;
        $AvrRate=0;
        $TotalPcs=0;
        $html1 ='';
        $html2 =''; 
        
        $MonthInward =DB::select("SELECT DATE_FORMAT(`in_date`, '%b-%Y') AS INDate, in_date ,  
         ifnull(sum(total_meter),0) as meter ,(ifnull(sum(total_amount),0)/ifnull(sum(total_meter),0)) as Rate,
         ifnull(sum(total_amount),0) as Taxable_Amount FROM inward_master 
         where inward_master.in_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY INDate ORDER BY inward_master.in_date ASC");
         
         if(count($MonthInward)>0)
         {
             $no=0;
             foreach($MonthInward as $mrow) 
             {
                 $month=date('m', strtotime($mrow->in_date));
             
                $html1 .='<tr style="color:black;">
                            <td><b>'.$mrow->INDate.'</b></td>
                            <td style="text-align:right;"><a href="/FabricGRNFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->in_date)).'">'.money_format('%!i',($mrow->meter/100000 )).'</a></td>
                            <td style="text-align:right;">'.money_format('%!i',($mrow->Rate )).'</td>
                            <td style="text-align:right;"> <a href="/FabricGRNFilterReport?fdate='.date('Y-'.$month.'-01').'&tdate='.date('Y-m-t', strtotime($mrow->in_date)).'">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</a></td>
                         </tr>';
                 
                 $TotalPcs = $TotalPcs + ($mrow->meter/100000);
                 $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                 $AvrRate = $AvrRate + $mrow->Rate;
                 $no=$no+1;
             }
              $html2 .='<tr>                           
                            <td><b>Total</b></td>  
                            <td style="text-align:right;">'.money_format('%!i',round($TotalPcs)).'</td>
                            <td style="text-align:right;">'.money_format('%!i',round($AvrRate/$no)).'</td>
                            <td style="text-align:right;">'.money_format('%!i',round($totalAmt)).'</td>
                         </tr>';
             
         }
      
                    
        return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function FinishedGoodsInward()
    {  
        
      setlocale(LC_MONETARY, 'en_IN');   
        
         $MonthInward =DB::select("SELECT DATE_FORMAT(`pki_date`, '%b-%Y') AS PKIDate, 
         ifnull(sum(total_qty),0) as TotalQty ,AVG(rate) as Rate,(ifnull(sum(total_qty),0)*AVG(rate)) as Taxable_Amount FROM packing_inhouse_master 
         where packing_inhouse_master.pki_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
         and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
         GROUP BY PKIDate ORDER BY packing_inhouse_master.pki_date ASC");
         
         $html1 = '';
         $html2 = '';
         $no = 1;
         
         if(count($MonthInward)>0)
         {
             $totalAmt=0;
             $AvrRate=0;
             $TotalPcs=0;
             foreach($MonthInward as $mrow) 
             {
                 $html1 .='<tr style="color:black;">
                    <td><b>'.$mrow->PKIDate.'</b>	</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->TotalQty/100000)).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Taxable_Amount/$mrow->TotalQty)).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($mrow->Taxable_Amount/100000)).'</td>
                 </tr>';
                 
                 $TotalPcs = $TotalPcs + ($mrow->TotalQty/100000);
                 $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                 $AvrRate = $AvrRate +  ($mrow->Taxable_Amount/$mrow->TotalQty);
                 $no=$no+1;
             } 
             $html2 .='<tr>
                <td><b>Total</b></td>
                <td style="text-align:right;">'.round($TotalPcs,2).'</td>
                <td style="text-align:right;">'.round(($AvrRate/$no),2).'</td>
                <td style="text-align:right;">'.round($totalAmt,2).'</td>
             </tr>';
         }
        return response()->json(['html1' => $html1, 'html2' => $html2]);
    }
    
    public function GraphicalDashboard()
    {
        return view('GraphicalDashboard');
    }
    
    public function GraphicalSaleDashboard()
    {
        
        $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master WHERE delflag = 0");
        
        $dateArr  = [];
        $QtyArr  = [];
        $min  = 0;
        $max  = 0;
        foreach($Buyer_Purchase_Order_List as $order)
        {
            $SaleTransactionDetails =
            DB::select("SELECT sale_transaction_master.sale_date,sum(order_qty) as order_qty,min(order_qty) as minQty, max(order_qty) as maxQty FROM sale_transaction_detail 
            LEFT JOIN sale_transaction_master ON sale_transaction_master.sale_code = sale_transaction_detail.sale_code
            WHERE sales_order_no ='".$order->tr_code."' GROUP BY sale_transaction_master.sale_code");
           
            foreach($SaleTransactionDetails as $sales)
            {
                $dateArr[] = $sales->sale_date;
                
                $QtyArr[] = $sales->order_qty;
                
            }
            
        } 
        $min = min($QtyArr);
        $max = max($QtyArr);
        print_r($QtyArr); exit;
        
        //print_r($max);exit;
         $tar = array(74, 83, 15, 97, 86, 65, 93, 10, 94);
         $amt = array(46, 57, 59, 54, 62, 58, 64, 60, 66);
         
         $target = json_encode($tar);
         $amount = json_encode($amt);
         
         return response()->json(['target' => $target, 'amount'=>$amount]);
    }
}
