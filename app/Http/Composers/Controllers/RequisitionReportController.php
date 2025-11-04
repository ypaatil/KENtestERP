<?php

namespace App\Http\Controllers;

use App\Models\RequisitionMasterModel;
use App\Models\RequisitionDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class RequisitionReportController extends Controller
{
 

   public function index()
    {

      $ledgerlist = DB::table('ledger_master')->get();

      return view('RequisitionReport',compact('ledgerlist')); 
    }


function pdf(Request $request)
    {

        $fdate=$request->fdate;
        $tdate=$request->tdate;
         $Ac_code=$request->Ac_code;
         $approveFlag=$request->approveFlag;
        

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->requisitiondata($fdate,$tdate,$approveFlag));
     return $pdf->stream();
    }



     public function requisitiondata($fdate,$tdate,$approveFlag)
     {
    
     //DB::enableQueryLog();

$query =DB::table('requisition_master')
     ->join('usermaster', 'usermaster.userId', '=', 'requisition_master.userId')
     ->join('requisitiontypemaster', 'requisitiontypemaster.requisitionId', '=', 'requisition_master.requisitionTypeId')
     ->join('firm_master', 'firm_master.firm_id', '=', 'requisition_master.firm_id')
   ->join('department_master', 'department_master.dept_id', '=', 'requisition_master.dept_id')
    ->join('machinemaster', 'machinemaster.machineId', '=', 'requisition_master.machineId')
    ->join('reasonmaster', 'reasonmaster.reasonId', '=', 'requisition_master.reasonId')

     ->whereBetween('requisition_master.requisitionDate', [$fdate, $tdate])
     ->select('requisition_master.*','usermaster.username','machinemaster.machineName','firm_master.firm_name','department_master.dept_name','reasonmaster.reason','requisitiontypemaster.requisitiontype');

if(isset($approveFlag) && $approveFlag!="" && $approveFlag!='All') {

    $query->where('requisition_master.requisitionApproveFlag', $approveFlag);
}

 $poMaster = $query->get();

//dd(DB::getQueryLog());
 $output = '
     <h3 align="center">Requisition Report</h3>';
  foreach($poMaster as $rowMaster)
     {
     $output .= '
   <table align="left" cellpadding="0" cellspacing="0" style="margin:5px;  page-break-inside: avoid;" width="100%">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">Requisition No</th>
    <th style="border: 1px solid; padding:12px;" width="30%">Requisition Date</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Requisition Type</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Department</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Machine</th>
     <th style="border: 1px solid; padding:12px;" width="20%">Issue To</th>
         <th style="border: 1px solid; padding:12px;" width="20%">Reason</th>
      <th style="border: 1px solid; padding:12px;" width="20%">Status</th>

   </tr>
     ';  


   
      $output .= '
      <tr>
      <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisitionNo.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisitionDate.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisitiontype.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->dept_name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->machineName.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->issueTo.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->reason.'</td>';

        if($rowMaster->requisitionApproveFlag==1)
        {
          $output .= '<td style="border: 1px solid; padding:12px;">Approved</td>';
        } else{

          $output .= '<td style="border: 1px solid; padding:12px;">Unapproved</td>';
        }


      $output .= '</tr>';
    
     $output .= '</table>';
     

  

  $output .= '<br>';

  $output .= '
   <table style="width:100%; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
<th style="border: 1px solid;" width="150%">Item Name</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Unit</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Requested Qty</th>
     <th style="border: 1px solid; padding:12px;" width="40%">Stock Qty</th>
<th style="border: 1px solid; padding:20px;" width="50%">Approved Qty</th>

   </tr>';    

     //DB::enableQueryLog();


 $detailpurchase = RequisitionDetailModel::join('item_master','item_master.item_code', '=', 'requisition_detail.item_code')
  ->join('unit_master', 'unit_master.unit_id', '=', 'requisition_detail.unit_id')    
  ->where('requisition_detail.requisitionNo','=',$rowMaster->requisitionNo)
  ->get(['requisition_detail.*','item_master.item_name','unit_master.unit_name']);




  //dd(DB::getQueryLog());

$no=1;
 foreach($detailpurchase as $rowDetail)
     {
      $output .= '
      <tr>
       <td  style="border: 1px solid;word-wrap: break-word;" width="20%">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->unit_name.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->requestedQty.'</td>
       <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->stockQty.'</td>
 <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->approvedQty.'</td>

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
