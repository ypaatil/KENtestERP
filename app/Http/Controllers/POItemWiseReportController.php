<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderDetailModel;
use PDF;
use DB;

class POItemWiseReportController extends Controller
{
    
   public function index()
    {

      $ledgerlist = DB::table('ledger_master')->get();
       $itemlist=DB::table('item_master')
   ->get();

      return view('POItemWiseReport',compact('ledgerlist','itemlist')); 
    }

function pdf(Request $request)
    {

   
         $item_code=$request->item_code;

        

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->podata($item_code));
     return $pdf->stream();
    }



     public function podata($item_code)
     {
    

   
     //DB::enableQueryLog();

$query =DB::table('purchaseorder_detail')
  ->join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')
  ->join('unit_master', 'unit_master.unit_id', '=', 'purchaseorder_detail.unit_id')
   ->join('ledger_master', 'ledger_master.ac_code', '=', 'purchaseorder_detail.Ac_code')
     ->select('purchaseorder_detail.*','item_master.item_name','unit_master.unit_name','ledger_master.ac_name');



if(isset($item_code) && $item_code!="" && $item_code!='All') {

   $query->where('purchaseorder_detail.item_code','=',$item_code);
}


 $detailpurchase = $query->get();

  // dd(DB::getQueryLog());



    $output = '
     <style>
   @page{

   	margin:1%;
   }


     </style>';

  $output .= '
    <h3 align="center">Purchase Order</h3>
   <table style="width:100vh; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
 <th style="border: 1px solid; padding:8px;font-size:12px;" width="80%">PO Date</th>     
  <th style="border: 1px solid; padding:8px;font-size:12px;" width="80%">PO No</th> 
 <th style="border: 1px solid; padding:8px;font-size:12px;" width="80%">Supplier Name</th> 
<th style="border: 1px solid;font-size:12px;" width="150%">Item Name</th>
    <th style="border: 1px solid; padding:8px;font-size:12px;" width="50%">HSN</th>
    <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Unit</th>
    <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Qty</th>
     <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Rate</th>
<th style="border: 1px solid; padding:20px;font-size:12px;" width="50%">CGST%</th>
<th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">CAMT</th>
<th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">SGST%</th>
<th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">SAMT</th>
<th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">IGST%</th>
<th style="border: 1px solid; padding:12px;font-size:12px;" width="50%">IAMT</th>     


         <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Amt</th>
   <th style="border: 1px solid; padding:5px;font-size:12px;" width="30%">Fr HSN</th>
   <th style="border: 1px solid; padding:12px;font-size:12px;" width="45%">Freight</th>
      <th style="border: 1px solid; padding:12px;font-size:12px;" width="40%">Total Amt</th> 


   </tr>';  
  //dd(DB::getQueryLog());

$no=1;
 foreach($detailpurchase as $rowDetail)
     {
      $output .= '
      <tr>
      <td  style="border: 1px solid;word-wrap: break-word;font-size:13px;" width="20%">'.$rowDetail->pur_date.'</td>
       <td  style="border: 1px solid;word-wrap: break-word;font-size:13px;" width="20%">'.$rowDetail->pur_code.'</td>
   <td  style="border: 1px solid;word-wrap: break-word;font-size:13px;" width="20%">'.$rowDetail->ac_name.'</td>

       <td  style="border: 1px solid;word-wrap: break-word;font-size:13px;" width="20%">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid;word-wrap: break-word;font-size:13px;" width="70%">'.$rowDetail->hsn_code.'</td>
       <td style="border: 1px solid;font-size:13px;" width="40%">'.$rowDetail->unit_name.'</td>
       <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->item_qty.'</td>
       <td style="border: 1px solid; padding:12px;font-size:13px;" width="60%">'.$rowDetail->item_rate.'</td>


 <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->pur_cgst.'</td>
  <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->camt.'</td>
   <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->pur_sgst.'</td>
    <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->samt.'</td>
     <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->pur_igst.'</td>
      <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->iamt.'</td>

       <td style="border: 1px solid; padding:12px;font-size:13px;" width="60%">'.$rowDetail->amount.'</td>
        <td style="border: 1px solid;font-size:13px;" width="60%">'.$rowDetail->freight_hsn.'</td>
         <td style="border: 1px solid; padding:12px;font-size:13px;" width="40%">'.$rowDetail->freight_amt.'</td>

         <td style="border: 1px solid; padding:12px;font-size:13px;" width="60%">'.$rowDetail->total_amount.'</td>

      </tr>
      ';

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


  public function mostPurchaseItems()
     {

   
     //DB::enableQueryLog();

$detailpurchase = DB::select(DB::raw("
select item_master.item_name,(select SUM(purchaseorder_detail.item_qty) from purchaseorder_detail where purchaseorder_detail.item_code=item_master.item_code) as totalqty
           from purchaseorder_detail

 inner join item_master on item_master.item_code=purchaseorder_detail.item_code

           group by item_master.item_name,item_master.item_code
           order by totalqty desc"));

  //dd(DB::getQueryLog());


    $output = '
     <style>
   @page{

    margin:1%;
   }


     </style>';

  $output .= '
    <h3 align="center">Most Purchased Items</h3>
   <table style="width:100%; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
<th style="border: 1px solid;">Item Name</th>
<th style="border: 1px solid; padding:12px;">Quantity</th>     


   </tr>';  
  //dd(DB::getQueryLog());

$no=1;
 foreach($detailpurchase as $rowDetail)
     {
      $output .= '
      <tr>
       <td  style="border: 1px solid;word-wrap: break-word;">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid;word-wrap: break-word;">'.$rowDetail->totalqty.'</td>
      </tr>
      ';

        $no=$no+1;
           }     

  $output .= '</table>';

  return $output;
       
     
 }
          





function MostconsumeditemsPdf(Request $request)
    {
   

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->mostConsumedItems());
     return $pdf->stream();
    }


  public function mostConsumedItems()
     {

   
     //DB::enableQueryLog();

$detailpurchase = DB::select(DB::raw("
select item_master.item_name,(select SUM(purchaseorder_detail.item_qty) from purchaseorder_detail where purchaseorder_detail.item_code=item_master.item_code) as totalqty
           from purchaseorder_detail

 inner join item_master on item_master.item_code=purchaseorder_detail.item_code

           group by item_master.item_name,item_master.item_code
           order by totalqty desc"));

  //dd(DB::getQueryLog());


    $output = '
     <style>
   @page{

    margin:1%;
   }


     </style>';

  $output .= '
    <h3 align="center">Most Consumed Items</h3>
   <table style="width:100%; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
<th style="border: 1px solid;">Item Name</th>
<th style="border: 1px solid; padding:12px;">Quantity</th>     


   </tr>';  
  //dd(DB::getQueryLog());

$no=1;
 foreach($detailpurchase as $rowDetail)
     {
      $output .= '
      <tr>
       <td  style="border: 1px solid;word-wrap: break-word;">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid;word-wrap: break-word;">'.$rowDetail->totalqty.'</td>
      </tr>
      ';

        $no=$no+1;
           }     

  $output .= '</table>';

  return $output;
       
     
 }




}
