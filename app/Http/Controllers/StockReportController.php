<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\FabricTransactionModel;
use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use App\Models\FabricCheckingModel;
use App\Models\FabricCheckingDetailModel;
use App\Models\FabricOutwardModel;
use App\Models\FabricOutwardDetailModel;
use App\Models\ItemModel;
use App\Models\ShadeModel;
use App\Models\PartModel;
use PDF;
use DB;

class StockReportController extends Controller
{
    
   public function index()
    {
 
    }

function FabricStock()
    {
 
     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->FabricData());
     return $pdf->stream();
    }

function FabricStock2()
    {
 
     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->FabricData2());
     return $pdf->stream();
    }

     public function FabricData()
     {
    

   
     //DB::enableQueryLog();

   $InwardFabric = DB::select("SELECT purchase_order.sr_no,purchase_order.pur_code,purchase_order.pur_date, job_status_name,purchaseorder_detail.item_code,
   item_description, dimension,color_name,
  purchaseorder_detail.item_qty
   
   ,
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1
  and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as received_meter 
 ,
    (select(purchaseorder_detail.item_qty 
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
  and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)   )) as to_be_received_meter ,
  
    
  
ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as rejected_meter,
   
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as issue_meter 
   ,
  
   (select( 
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  -
  ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  )) as fabric_stock,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 30 day),0) as t30_days_meter,
   
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 60 day),0) as t60_days_meter,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 30 day),0) as t90_days_meter
   
   
    FROM `purchaseorder_detail` 
    
    left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
   
    left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
    left outer  join job_status_master on job_status_master.job_status_id=purchase_order.po_status
    ");

  
 

  // dd(DB::getQueryLog());



    $output = '
     <style>
   @page{

   	margin:1%;
   }


     </style>';

  $output .= '
  <p style="text-align:center;"><img src="./images/logo.png"> </p>
    <h3 align="center">Fabric Stock Report</h3>
   <table style="width:200vh; margin:0px;padding:0;border:1px solid; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr> 
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">PO No</th> 
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">PO Date</th>     
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">Fabric Code</th>
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="80%">Fabric Quality</th> 
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="30%">Color</th>
             <th style="border: 1px solid; padding:8px;font-size:12px;" width="30%">Width</th> 
            <th style="border: 1px solid; padding:8px; font-size:12px;" width="50%">PO Qty</th>
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">Received Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">To Be Received Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Rejected Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Issue Meter</th>
            <th style="border: 1px solid; padding:20px;font-size:12px;" width="50%">Stock Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">PO Status</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">(30 > Days)</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">(60 > Days)</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="80%">(90 > Days)</th>
           
    </tr>';  
  //dd(DB::getQueryLog());

$no=1;
 foreach($InwardFabric as $rowDetail)
     {
      $output .= '
      <tr>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%"> 
            <a href="http://ken.korbofx.org/PurchaseOrder/'.$rowDetail->sr_no.'/edit" >'.$rowDetail->pur_code.'</a></td>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->pur_date.'</td>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->item_code.'</td>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->item_description.'</td>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->color_name.'</td>
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->dimension.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->item_qty.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="60%" >'.$rowDetail->received_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->to_be_received_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->rejected_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->issue_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->fabric_stock.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->job_status_name.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->t30_days_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->t60_days_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="80%">'.$rowDetail->t90_days_meter.'</td>
      
      </tr>';

        $no=$no+1;
           }     

  $output .= '</table>';

  return $output;
       
     
 }
          
public function FabricData2()
     {
    

   
     //DB::enableQueryLog();

   $InwardFabric = DB::select("SELECT purchase_order.pur_code,purchase_order.pur_date, job_status_name,purchaseorder_detail.item_code,
   item_description, dimension,color_name,
  purchaseorder_detail.item_qty
   
   ,
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1
  and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as received_meter 
 ,
    (select(purchaseorder_detail.item_qty 
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
  and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)   )) as to_be_received_meter ,
  
    
  
ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as rejected_meter,
   
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as issue_meter 
   ,
  
   (select( 
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  -
  ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code),0)
  )) as fabric_stock,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 30 day
   
   ),0) as t30_days_meter,
   
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 60 day),0) as t60_days_meter,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 30 day),0) as t90_days_meter
   
   
    FROM `purchaseorder_detail` 
    
    left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
   
    left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
    left outer  join job_status_master on job_status_master.job_status_id=purchase_order.po_status
    ");

  
 

  // dd(DB::getQueryLog());



    $output = '
     <style>
   @page{

   	margin:1%;
   }


     </style>';

  $output .= '
    <p style="text-align:center;"><img src="./images/logo.png"> </p>
    <h3 align="center">Fabric Stock Summary Report</h3>
   <table style="width:200vh; margin:0px;padding:0;border:1px solid; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr> 
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">Fabric Code</th>
            <th style="border: 1px solid; padding:8px; font-size:12px;" width="50%">PO Qty</th>
            <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">Received Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">To Be Received Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Rejected Meter</th>
            <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Issue Meter</th>
            <th style="border: 1px solid; padding:20px;font-size:12px;" width="50%">Stock Meter</th>
            
           
    </tr>';  
  //dd(DB::getQueryLog());

$no=1;
 foreach($InwardFabric as $rowDetail)
     {
      $output .= '
      <tr>
             
            <td  style="border: 1px solid;word-wrap: break-word;font-size:13px; text-align:center;" width="20%">'.$rowDetail->item_code.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->item_qty.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="60%" >'.$rowDetail->received_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->to_be_received_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->rejected_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->issue_meter.'</td>
            <td style="border: 1px solid; padding:12px;font-size:13px; text-align:center;" width="40%">'.$rowDetail->fabric_stock.'</td>
           
      </tr>';

        $no=$no+1;
           }     

  $output .= '</table>';

  return $output;
       
     
 }


function itemsPdf(Request $request)
    {
   

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->mostPurchaseItems());
     return $pdf->stream();
    }


  

 public function GetOnPageFabricStock()
 
 {
     
     
     $PODetails = DB::select("SELECT ifnull((select count(sr_no) from purchase_order where bom_type=1),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order where bom_type=1),0) as poTotal,
     
 ifnull((select sum(Net_Amount) from purchase_order where po_status=2 and bom_type=1),0) as receivedTotal 
       ");

     $GRNTotal = DB::select(" SELECT  
     purchaseorder_detail.item_rate* ifnull((select sum(meter) from inward_details 
     where inward_details.item_code=purchaseorder_detail.item_code),0)  as received_meter  
     FROM `purchaseorder_detail` 
     left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
     ");
    $AmountGrn=0;
    foreach($GRNTotal as $row)
    {
        $AmountGrn=$AmountGrn + $row->received_meter;
    }
   
     
     
        $InwardFabric = DB::select("SELECT purchase_order.sr_no,purchase_order.pur_code,purchase_order.pur_date, job_status_name,
        purchaseorder_detail.item_code, item_name, item_image_path,
        
                            item_description, dimension,color_name, purchaseorder_detail.item_qty ,
                            
                            ifnull((select sum(meter) from inward_details where inward_details.item_code=purchaseorder_detail.item_code
                            and inward_details.po_code=purchaseorder_detail.pur_code),0) as received_meter ,
                          
                            ifnull((select sum(meter) from fabric_checking_details
                            
                            inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
                            
                            where fabric_checking_details.item_code=purchaseorder_detail.item_code 
                            and fabric_checking_master.po_code=purchaseorder_detail.pur_code),0) as passed_meter,
                         
                             ifnull((select sum(reject_short_meter) from fabric_checking_details
                            inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
                            where fabric_checking_details.item_code=purchaseorder_detail.item_code 
                            and fabric_checking_master.po_code=purchaseorder_detail.pur_code),0) as rejected_meter,
                           
                             ifnull((select sum(fabric_outward_details.meter) from fabric_outward_details 
                            inner join inward_details on inward_details.track_code=fabric_outward_details.track_code
                             where fabric_outward_details.item_code=purchaseorder_detail.item_code  
                             and inward_details.po_code=purchaseorder_detail.pur_code),0) as issue_meter,
                             
                           
                             (select ifnull((select sum(item_qty) from purchaseorder_detail as pod
                             where pod.item_code=purchaseorder_detail.item_code 
                           and   pod.pur_code=purchaseorder_detail.pur_code and
                           purchaseorder_detail.pur_date > now() - INTERVAL 30 day),0)
                           
                            -
                            ifnull((select sum(meter) from inward_details 
                            where inward_details.item_code=purchaseorder_detail.item_code 
                           and   inward_details.po_code=purchaseorder_detail.pur_code 
                           and in_date > now() - INTERVAL 30 day),0) )as t30_days_meter,
                           
                           
                            (select ifnull((select sum(item_qty) from purchaseorder_detail as pod 
                            where pod.item_code=purchaseorder_detail.item_code 
                            and   pod.pur_code=purchaseorder_detail.pur_code 
                            and datediff(current_date,date(pur_date)) BETWEEN  31 AND 60),0)
                           
                            -
                            ifnull((select sum(meter) from inward_details
                            where inward_details.item_code=purchaseorder_detail.item_code 
                            and     inward_details.po_code=purchaseorder_detail.pur_code 
                            and datediff(current_date,date(in_date)) BETWEEN  31 AND 60),0) )as t60_days_meter,
                           
                           
                           
                             (select ifnull((select sum(item_qty) from purchaseorder_detail as pod 
                             where pod.item_code=purchaseorder_detail.item_code 
                            and   pod.pur_code=purchaseorder_detail.pur_code and 
                            datediff(current_date,date(pur_date)) BETWEEN  61 AND 90),0)
                           
                            -
                            ifnull((select sum(meter) from inward_details 
                            where inward_details.item_code=purchaseorder_detail.item_code 
                              and inward_details.po_code=purchaseorder_detail.pur_code
                            and datediff(current_date,date(in_date)) BETWEEN  61 AND 90),0) )as t90_days_meter 
                           
                           
                            FROM `purchaseorder_detail` 
                            
                            left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
                            left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
                            left outer  join job_status_master on job_status_master.job_status_id=purchase_order.po_status
                            where purchase_order.bom_type=1
    ");
     
      return view('FabricStockOnPage', compact('InwardFabric','PODetails','AmountGrn'));
      
 }


//   (select(purchaseorder_detail.item_qty 
                            
//                             -
//                             ifnull((select sum(meter) from inward_details where inward_details.item_code=purchaseorder_detail.item_code 
//                             and   inward_details.po_code=purchaseorder_detail.pur_code),0)   )) as to_be_received_meter ,

//   (select(ifnull((select sum(meter) from inward_details
//                             where inward_details.item_code=purchaseorder_detail.item_code and 
//                             inward_details.tr_type=1 and inward_details.po_code=purchaseorder_detail.pur_code),0)
//                           -
//                           ifnull((select sum(meter) from inward_details 
//                           where inward_details.item_code=purchaseorder_detail.item_code and 
//                           inward_details.tr_type=3 and inward_details.po_code=purchaseorder_detail.pur_code),0)
//                           -
//                           ifnull((select sum(rejected_meter) from inward_details 
//                           where inward_details.item_code=purchaseorder_detail.item_code and 
//                           inward_details.tr_type=2 and inward_details.po_code=purchaseorder_detail.pur_code),0)
//                           )) as fabric_stock,







 public function GetOnPageFabricStockSummary()
 {
     
     
 $PODetails = DB::select("SELECT ifnull((select count(sr_no) from purchase_order),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order),0) as poTotal,
 ifnull((select sum(item_qty) from purchaseorder_detail inner join purchase_order on  purchase_order.pur_code=purchaseorder_detail.pur_code where purchase_order.po_status=1),0) as POMeter,
 ifnull((select sum(meter) from inward_details),0) as GRNMeter");
     
  $InwardFabric = DB::select("SELECT    purchaseorder_detail.item_code, item_name,item_image_path,
  item_description, dimension,color_name,
  sum(purchaseorder_detail.item_qty) as item_qty
   
   ,
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1
    ),0) as received_meter 
 ,
    (select(  sum(purchaseorder_detail.item_qty) 
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
  and fabric_transaction.tr_type=1   ),0))) as to_be_received_meter ,
  
   
  
    ifnull((select sum(meter) from fabric_checking_details where fabric_checking_details.item_code=purchaseorder_detail.item_code),0) as passed_meter 
   ,
ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2
  ),0) as rejected_meter,
   
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3
  ),0) as issue_meter 
   ,
  
   (select( 
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1
    ) ,0)
  -
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=3
  ),0)
  -
  ifnull((select sum(rejected_meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=2
   ),0)
  )) as fabric_stock,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1   and tr_date > now() - INTERVAL 30 day ),0) as t30_days_meter,
   
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1   and tr_date > now() - INTERVAL 60 day ),0) as t60_days_meter,
   
   ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=1   and tr_date > now() - INTERVAL 30 day ),0) as t90_days_meter
   
   
    FROM `purchaseorder_detail` 
    
    left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
    left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
    group by   purchaseorder_detail.item_code  
    ");
     
      return view('FabricStockSummaryOnPage', compact('InwardFabric','PODetails'));
      
 }





public function GetInwardFabList(Request $request)
{
         $sr_no= $request->input('sr_no');
         $item_code= $request->input('item_code');
         
       //  DB::enableQueryLog();
         $POList = PurchaseOrderModel::where('sr_no','=', $sr_no)->first();  
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
      
    // DB::enableQueryLog();
    $InwardFabric = DB::select("SELECT inward_master.`in_code`, inward_master.`in_date`,   
        item_master.item_name, part_master.part_name, shade_master.shade_name,
     inward_details.`item_code`, inward_details.`roll_no`,  
     inward_details.`meter`, inward_details.`track_code`, inward_details.`usedflag`,  
     inward_master.`total_meter`, inward_master.`total_taga_qty`, inward_master.`in_narration` 
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code 
    inner join item_master on item_master.item_code=inward_details.item_code
    inner join part_master on part_master.part_id=inward_details.part_id
    inner join shade_master on shade_master.shade_id=inward_details.shade_id
    
    where inward_master.po_code='".$POList->pur_code."' 
    and inward_details.item_code=".$item_code);
    //   $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
    
    $html ='';
    $html .= '<input type="number" value="'.count($InwardFabric).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
   
    
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>In Code</th>
<th>In Date</th>
<th>Item Name</th>
<th>Shade</th>
<th>Part</th>
 
<th>Meter</th>
<th>TrackCode</th>
 
</tr>
</thead>
<tbody>';
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
    
$html .='
<td><input type="text"  " value="'.$row->in_code.'"   style="width:150px;" readOnly /></td>
<td><input type="date"  " value="'.$row->in_date.'"   style="width:100px;" readOnly /></td>
<td><input type="text"  value="'.$row->item_name.'"   style="width:150px;" readOnly required/></td>
<td><input type="text"  value="'.$row->part_name.'"   style="width:100px;" readOnly required/></td>
<td><input type="text"  value="'.$row->shade_name.'"   style="width:50px;" readOnly required/></td>
 
<td><input type="text"    value="'.$row->meter.'"   style="width:80px;" readOnly required/></td>
<td><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;" readOnly  /></td>';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    $html .='</tbody>
    </table>';

    if(count($InwardFabric)!=0)
    {
          return response()->json(['html' => $html]);
    }
  
     
}




public function GetCompareFabricPOInwardData(Request $request)
{
         $sr_no= $request->input('sr_no');
         $item_code= $request->input('item_code');
         
       //  DB::enableQueryLog();
         $POList = PurchaseOrderModel::where('sr_no','=', $sr_no)->first();  
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
      
    // DB::enableQueryLog();
    $InwardFabric = DB::select("SELECT    
     item_master.item_name,  item_master.item_description,
     inward_details.`item_code`,   
     inward_details.`meter`,
      (select sum(item_qty) as po_item_qty from purchaseorder_detail where purchaseorder_detail.pur_code='".$POList->pur_code."') as po_item_qty 
       
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code 
    inner join item_master on item_master.item_code=inward_details.item_code
    where inward_master.po_code='".$POList->pur_code."' 
    and inward_details.item_code='".$item_code."' group by inward_master.po_code");
    //   $query = DB::getQueryLog();
    //       $query = end($query);
    //       dd($query);
    
    $html ='';
    $html .= '<input type="number" value="'.count($InwardFabric).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
   
    
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
 
<th>Item Name</th>

<th>Description</th>
<th>PO Meter</th>
<th>Meter</th>
<th>To Be Received</th>
</tr>
</thead>
<tbody>';
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
    
$html .='
 
<td><input type="text"  value="'.$row->item_name.'"   style="width:150px;" readOnly required/></td>
<td><input type="text"  value="'.$row->item_description.'"   style="width:150px;" readOnly required/></td>
<td><input type="text"    value="'.$row->po_item_qty.'"   style="width:80px;" readOnly required/></td>
<td><input type="text"    value="'.$row->meter.'"   style="width:80px;" readOnly required/></td>
<td><input type="text"    value="'.($row->po_item_qty-$row->meter).'"   style="width:80px;" readOnly required/></td>';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    $html .='</tbody>
    </table>';

    if(count($InwardFabric)!=0)
    {
          return response()->json(['html' => $html]);
    }
  
     
}















}
