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
     
     
     $PODetails = DB::select("SELECT ifnull((select count(sr_no) from purchase_order),0)  as noOfPO,  ifnull((select sum(Net_Amount) from purchase_order),0) as poTotal,
 ifnull((select sum(Net_Amount) from purchase_order where po_status=2),0) as receivedTotal 
       ");

     $GRNTotal = DB::select(" SELECT  
     purchaseorder_detail.item_rate* ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1),0)  as received_meter  
     FROM `purchaseorder_detail` 
     left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
     ");
    $AmountGrn=0;
    foreach($GRNTotal as $row)
    {
        $AmountGrn=$AmountGrn + $row->received_meter;
    }
   
     
     
        $InwardFabric = DB::select("SELECT purchase_order.sr_no,purchase_order.pur_code,purchase_order.pur_date, job_status_name,purchaseorder_detail.item_code, item_name, item_image_path,
   item_description, dimension,color_name,
  purchaseorder_detail.item_qty
   
   ,
  ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code and fabric_transaction.tr_type=1
  and fabric_transaction.po_code=purchaseorder_detail.pur_code),0) as received_meter ,
  
    ifnull((select sum(meter) from fabric_checking_details where fabric_checking_details.item_code=purchaseorder_detail.item_code 
    and fabric_checking_details.po_code=purchaseorder_detail.pur_code),0) as passed_meter 
  
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
   
     (select ifnull((select sum(item_qty) from purchaseorder_detail as pod where pod.item_code=purchaseorder_detail.item_code 
   and   pod.pur_code=purchaseorder_detail.pur_code and purchaseorder_detail.pur_date > now() - INTERVAL 30 day),0)
   
    -
    ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code and tr_date > now() - INTERVAL 30 day),0) )as t30_days_meter,
   
   
     (select ifnull((select sum(item_qty) from purchaseorder_detail as pod where pod.item_code=purchaseorder_detail.item_code 
   and   pod.pur_code=purchaseorder_detail.pur_code and datediff(current_date,date(pur_date)) BETWEEN  31 AND 60),0)
   
    -
    ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code and datediff(current_date,date(tr_date)) BETWEEN  31 AND 60),0) )as t60_days_meter,
   
   
   
     (select ifnull((select sum(item_qty) from purchaseorder_detail as pod where pod.item_code=purchaseorder_detail.item_code 
   and   pod.pur_code=purchaseorder_detail.pur_code and datediff(current_date,date(pur_date)) BETWEEN  61 AND 90),0)
   
    -
    ifnull((select sum(meter) from fabric_transaction where fabric_transaction.item_code=purchaseorder_detail.item_code 
   and fabric_transaction.tr_type=2 and fabric_transaction.po_code=purchaseorder_detail.pur_code and datediff(current_date,date(tr_date)) BETWEEN  61 AND 90),0) )as t90_days_meter 
   
   
    FROM `purchaseorder_detail` 
    
    left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
   
    left outer join purchase_order on purchase_order.pur_code=purchaseorder_detail.pur_code
    left outer  join job_status_master on job_status_master.job_status_id=purchase_order.po_status
    ");
     
      return view('FabricStockOnPage', compact('InwardFabric','PODetails','AmountGrn'));
      
 }



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
         $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        // $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        // $FGList =  FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
        $PartList =  PartModel::where('part_master.delflag', '=', '0')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag', '=', '0')->get();
        //  $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
        // $CPList =  DB::table('cp_master')->get();
       //  DB::enableQueryLog();
         $POList = PurchaseOrderModel::where('sr_no','=', $sr_no)->first();  
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
          // echo $sr_no;
       //echo $POList->pur_code;
    // DB::enableQueryLog();
    $InwardFabric = DB::select("SELECT inward_master.`in_code`, inward_master.`in_date`, inward_master.`gp_no`,inward_details.`part_id`,
       inward_details.`style_no`, inward_details.`fg_id`, inward_details.shade_id,
     inward_details.`item_code`, inward_details.`roll_no`,  
     inward_details.`meter`, inward_details.`track_code`, inward_details.`usedflag`, inward_master.`job_code`, 
     inward_master.`total_meter`, inward_master.`total_taga_qty`, inward_master.`in_narration` 
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code where inward_master.po_code='".$POList->pur_code."' and item_code=".$item_code);
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
<th>Part</th>
<th>Old Meter</th>
<th>Meter</th>
<th>TrackCode</th>
 
</tr>
</thead>
<tbody>';
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
    
$html .='
<td><input type="text"  " value="'.$row->in_code.'" id="id" style="width:100px;"/></td>
<td><input type="date"  " value="'.$row->in_date.'" id="id" style="width:100px;"/></td>
 
<td> <select name="item_code[]"  id="item_code" style="width:100px;" required>
<option value="">--Item--</option>';

foreach($ItemList as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $row->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 

<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Part--</option>';
foreach($PartList as  $rowfg)
{
    $html.='<option value="'.$rowfg->part_id.'"';

    $rowfg->part_id == $row->part_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowfg->part_name.'</option>';
}
 
$html.='</select></td>
<td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();"   value="1"  id="taga_qty1" style="width:50px;"/><input type="text"  name="old_meter[]" onkeyup="mycalc();"   value="'.$row->meter.'" id="old_meter1" style="width:80px;" required/></td>
<td><input type="text" class="METER" name="meter[]" onkeyup="mycalc();"   value="'.$row->meter.'" id="meter1" style="width:80px;" required/></td>
 
<td><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;"  /></td>
 
';
  
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
