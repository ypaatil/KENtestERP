<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderDetailModel;
use PDF;
use DB;


class POReportController extends Controller
{
 

   public function index()
    {

      $ledgerlist = DB::table('ledger_master')->get();

      return view('PurchaseOrderReport',compact('ledgerlist')); 
    }


function pdf(Request $request)
    {

        $fdate=$request->fdate;
        $tdate=$request->tdate;
         $Ac_code=$request->Ac_code;
         $approveFlag=$request->approveFlag;
        

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->podata($fdate,$tdate,$Ac_code,$approveFlag));
     return $pdf->stream();
    }



     public function podata($fdate,$tdate,$Ac_code,$approveFlag)
     {
    
     //DB::enableQueryLog();

$query =DB::table('purchase_order')
     ->join('ledger_master as lm1', 'lm1.ac_code', '=', 'purchase_order.Ac_code')
     ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
     ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
     ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')
     ->whereBetween('purchase_order.pur_date', [$fdate, $tdate])
     ->select('purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name');

if(isset($Ac_code) && $Ac_code!="" && $Ac_code!='All') {

    $query->where('purchase_order.Ac_code', $Ac_code);
}

if(isset($approveFlag) && $approveFlag!="" && $approveFlag!='All') {

    $query->where('purchase_order.approveFlag', $approveFlag);
}

 $poMaster = $query->get();

//dd(DB::getQueryLog());
 $output = '
     <h3 align="center">Purchase Order</h3>';
  foreach($poMaster as $rowMaster)
     {
     $output .= '
   <table align="left" cellpadding="0" cellspacing="0" style="margin:5px;  page-break-inside: avoid;" width="100%">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">PO No</th>
    <th style="border: 1px solid; padding:12px;" width="30%">PO Date</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Supplier</th>
    <th style="border: 1px solid; padding:12px;" width="15%">GST</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Gross Amount</th>
     <th style="border: 1px solid; padding:12px;" width="20%">GST Amount</th>
         <th style="border: 1px solid; padding:12px;" width="20%">Net Amount</th>
   <th style="border: 1px solid; padding:12px;" width="20%">Narration</th>
      <th style="border: 1px solid; padding:12px;" width="20%">Status</th>

   </tr>
     ';  


   
      $output .= '
      <tr>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->pur_code.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->pur_date.'</td>
       <td style="border: 1px solid; padding:12px;" colspan="2">'.$rowMaster->ac_name1.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->tax_type_name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->Gross_amount.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->Gst_amount.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->Net_amount.'</td>';

        if($rowMaster->approveFlag==0)
        {
          $output .= '<td style="border: 1px solid; padding:12px;">Pending</td>';
        } 
        elseif($rowMaster->approveFlag==1)
        {

          $output .= '<td style="border: 1px solid; padding:12px;">Approved</td>';
        }
        elseif($rowMaster->approveFlag==2)
        {
          $output .= '<td style="border: 1px solid; padding:12px;">Unapproved</td>';
        }

      $output .= '</tr> 
    <tr>
       <td style="border: 1px solid; padding:12px;" colspan="9"><b>Narration: </b>'.$rowMaster->narration.'</td>
    </tr>';
    
     $output .= '</table>';
     

  

  $output .= '<br>';

  $output .= '
   <table style="width:100%; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
<th style="border: 1px solid;" width="150%">Item Name</th>
    <th style="border: 1px solid; padding:8px;" width="50%">HSN</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Unit</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Qty</th>
     <th style="border: 1px solid; padding:12px;" width="40%">Rate</th>
<th style="border: 1px solid; padding:20px;" width="50%">CGST%</th>
<th style="border: 1px solid; padding:12px;" width="50%">CAMT</th>
<th style="border: 1px solid; padding:12px;" width="50%">SGST%</th>
<th style="border: 1px solid; padding:12px;" width="50%">SAMT</th>
<th style="border: 1px solid; padding:12px;" width="50%">IGST%</th>
<th style="border: 1px solid; padding:12px;" width="50%">IAMT</th>     


         <th style="border: 1px solid; padding:12px;" width="40%">Amt</th>
   <th style="border: 1px solid; padding:5px;" width="30%">Fr HSN</th>
   <th style="border: 1px solid; padding:12px;" width="45%">Freight</th>
      <th style="border: 1px solid; padding:12px;" width="40%">Total Amt</th> 
   </tr>  ';  

     //DB::enableQueryLog();


 $detailpurchase = PurchaseOrderDetailModel::join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')
  ->join('unit_master', 'unit_master.unit_id', '=', 'purchaseorder_detail.unit_id')    
  ->where('purchaseorder_detail.pur_code','=',$rowMaster->pur_code)
  ->get(['purchaseorder_detail.*','item_master.item_name','unit_master.unit_name']);




  //dd(DB::getQueryLog());

$no=1;
 foreach($detailpurchase as $rowDetail)
     {
      $output .= '
      <tr>
       <td  style="border: 1px solid;word-wrap: break-word;" width="20%">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid; padding:12px;word-wrap: break-word;" width="70%">'.$rowDetail->hsn_code.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->unit_name.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->item_qty.'</td>
       <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->item_rate.'</td>


 <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->pur_cgst.'</td>
  <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->camt.'</td>
   <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->pur_sgst.'</td>
    <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->samt.'</td>
     <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->pur_igst.'</td>
      <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->iamt.'</td>

       <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->amount.'</td>
        <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->freight_hsn.'</td>
         <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->freight_amt.'</td>

         <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->total_amount.'</td>

      </tr>
      ';

        $no=$no+1;
           }     

  $output .= '</table>';

  $output .= "<p style='text-align:center;'>**************</p>";
         
     
 }
          return $output;

}



   public function generatePO($pur_code)
    {

   
    
     $pur_codes=base64_decode($pur_code);
     
     // DB::enableQueryLog();

     $query =DB::table('purchase_order')
     ->join('ledger_master as lm1', 'lm1.ac_code', '=', 'purchase_order.Ac_code')
     ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
     ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
     ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')
     ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'purchase_order.po_type_id')
     ->where('purchase_order.pur_code',$pur_codes)
     ->select('purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','lm1.address','lm1.gst_no','lm1.mobile','lm1.email',
     'lm1.pan_no','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name');
      $poMaster = $query->get();


     $SalesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$pur_codes."'");


       //dd(DB::getQueryLog());

          $data = [
            'title' => 'Purchase Order',
            'poMaster' => $poMaster,
            'SalesOrderNo'=>$SalesOrderNo
        ];

       /*      
     $pdf = \App::make('dompdf.wrapper');
            $pdf = PDF::loadView('poprint',$data)->setOptions(['defaultFont' => 'sans-serif']);  
           // return $pdf->download('poprint.pdf');  
              $pdf->setPaper('A4', 'portrait');

          return $pdf->stream();*/
  
        return view('poprint',$data);  


    }




}
