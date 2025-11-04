<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialInwardStoreModel;
use App\Models\MIStoreDetailModel;
use PDF;
use DB;

class MaterialInwardStoreReportController extends Controller
{
    
   public function index()
    {

      $ledgerlist = DB::table('ledger_master')->get();
       $itemlist=DB::table('item_master')
   ->get();

      return view('StoreInwardReport',compact('ledgerlist','itemlist')); 
    }


function pdf(Request $request)
    {

         $fdate=$request->fdate;
         $tdate=$request->tdate;
         $Ac_code=$request->Ac_code;
         $item_code=$request->item_code;
        

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->inwarddata($fdate,$tdate,$Ac_code,$item_code));
     return $pdf->stream();
    }



     public function inwarddata($fdate,$tdate,$Ac_code,$item_code)
     {
    
     //DB::enableQueryLog();

$query =DB::table('store_inward_master')
     ->join('ledger_master as lm1', 'lm1.ac_code', '=', 'store_inward_master.Ac_code')
     ->join('usermaster', 'usermaster.userId', '=', 'store_inward_master.userId')
     ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'store_inward_master.tax_type_id')
     ->join('firm_master', 'firm_master.firm_id', '=', 'store_inward_master.firm_id')
    ->join('inwardtype', 'inwardtype.inwardTypeId', '=', 'store_inward_master.inwardTypeId')
     ->whereBetween('store_inward_master.storeInward_date', [$fdate, $tdate])
     ->select('store_inward_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','inwardtype.inwardType');

if(isset($Ac_code) && $Ac_code!="" && $Ac_code!='All') {

    $query->where('store_inward_master.Ac_code', $Ac_code);
}

 $poMaster = $query->get();

//dd(DB::getQueryLog());
 $output = '
     <h3 align="center">Material Inward</h3>';
  foreach($poMaster as $rowMaster)
     {
     $output .= '
   <table align="left" cellpadding="0" cellspacing="0" style="margin:5px;  page-break-inside: avoid;" width="100%">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">Inward No</th>
    <th style="border: 1px solid; padding:12px;" width="30%">Inward Date</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Inward Type</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Supplier Name</th>
    <th style="border: 1px solid; padding:12px;" width="20%">GST  No</th>
     <th style="border: 1px solid; padding:12px;" width="20%">GST</th>';
        
if($rowMaster->inwardTypeId=='1')
          {
  $output .= '<th style="border: 1px solid; padding:12px;" width="20%">Invoice No</th>
   <th style="border: 1px solid; padding:12px;" width="30%">Invoice Date</th>';

} else {

   $output .= '<th style="border: 1px solid; padding:12px;" width="20%">DC No</th>
   <th style="border: 1px solid; padding:12px;" width="30%">DC Date</th>';
}

$output .= '<th style="border: 1px solid; padding:12px;" width="20%">Gross Amount</th>
       <th style="border: 1px solid; padding:12px;" width="20%">GST Amt</th>
          <th style="border: 1px solid; padding:12px;" width="20%">Net Amt</th>
            <th style="border: 1px solid; padding:12px;" width="20%">Narration</th>

   </tr>
     ';  


   
      $output .= '
      <tr>
      <td style="border: 1px solid; padding:12px;">'.$rowMaster->storeInCode.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->storeInward_date.'</td>

       <td style="border: 1px solid; padding:12px;">'.$rowMaster->inwardType.'</td>


       <td style="border: 1px solid; padding:12px;">'.$rowMaster->ac_name1.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->gstNo.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->tax_type_name.'</td>';


if($rowMaster->inwardTypeId==1)
          {
        $output .= '<td style="border: 1px solid; padding:12px;">'.$rowMaster->pur_bill_no.'</td>
        <td style="border: 1px solid; padding:12px;">'.$rowMaster->pur_bill_date.'</td>';
      } else{

            $output .= '<td style="border: 1px solid; padding:12px;">'.$rowMaster->dc_no.'</td>
        <td style="border: 1px solid; padding:12px;" width="30%">'.$rowMaster->dc_date.'</td>';
      }


  $output .='<td style="border: 1px solid; padding:12px;">'.$rowMaster->Gross_amount.'</td>
  <td style="border: 1px solid; padding:12px;">'.$rowMaster->Gst_amount.'</td>
    <td style="border: 1px solid; padding:12px;">'.$rowMaster->Net_amount.'</td>
     <td style="border: 1px solid; padding:12px;">'.$rowMaster->narration.'</td>

        ';


      


      $output .= '</tr>';
    
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


   </tr>';    

     //DB::enableQueryLog();


 $detailinward = MIStoreDetailModel::join('item_master','item_master.item_code', '=', 'store_inward_detail.item_code')
  ->join('unit_master', 'unit_master.unit_id', '=', 'store_inward_detail.unit_id')    
  ->where('store_inward_detail.storeInCode','=',$rowMaster->storeInCode)
  ->get(['store_inward_detail.*','item_master.item_name','unit_master.unit_name']);




  //dd(DB::getQueryLog());

$no=1;
 foreach($detailinward as $rowDetail)
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





}
