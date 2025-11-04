<?php

namespace App\Http\Controllers;

use App\Models\RequisitionOutwardMasterModel;
use App\Models\RequisitionMasterModel;
use App\Models\RequisitionOutwardDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class RequisitionOutwardReportController extends Controller
{
  
   public function index()
    {

      $ledgerlist = DB::table('ledger_master')->get();

      return view('MIStoreOutwardReport',compact('ledgerlist')); 
    }


function pdf(Request $request)
    {

        $fdate=$request->fdate;
        $tdate=$request->tdate;
        

     $pdf = \App::make('dompdf.wrapper');

     $pdf->setPaper('A4', 'landscape');

     $pdf->loadHTML($this->requisitionoutwarddata($fdate,$tdate));
     return $pdf->stream();
    }



     public function requisitionoutwarddata($fdate,$tdate)
     {
    
     //DB::enableQueryLog();

$query =DB::table('requisition_outward_master')
     ->join('usermaster', 'usermaster.userId', '=', 'requisition_outward_master.userId')
     ->join('firm_master', 'firm_master.firm_id', '=', 'requisition_outward_master.firm_id')
   ->join('department_master', 'department_master.dept_id', '=', 'requisition_outward_master.dept_id')
    ->join('machinemaster', 'machinemaster.machineId', '=', 'requisition_outward_master.machineId')
    ->join('reasonmaster', 'reasonmaster.reasonId', '=', 'requisition_outward_master.reasonId')
       ->join('requisition_master', 'requisition_master.requisitionNo', '=', 'requisition_outward_master.requisitionNo')
        ->join('requisitiontypemaster', 'requisitiontypemaster.requisitionId', '=', 'requisition_master.requisitionTypeId')

     ->whereBetween('requisition_outward_master.requisition_outward_date', [$fdate, $tdate])
     ->select('requisition_outward_master.*','usermaster.username','machinemaster.machineName','firm_master.firm_name','department_master.dept_name','reasonmaster.reason','requisitiontypemaster.requisitiontype');


 $poMaster = $query->get();

//dd(DB::getQueryLog());
 $output = '
     <h3 align="center">Outward Report</h3>';
  foreach($poMaster as $rowMaster)
     {
     $output .= '
   <table align="left" cellpadding="0" cellspacing="0" style="margin:5px;  page-break-inside: avoid;" width="100%">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">Outward No</th>
    <th style="border: 1px solid; padding:12px;" width="30%">Outward Date</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Requisition No</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Department</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Machine</th>
     <th style="border: 1px solid; padding:12px;" width="20%">Issue To</th>
         <th style="border: 1px solid; padding:12px;" width="20%">Reason</th>
      <th style="border: 1px solid; padding:12px;" width="20%">Status</th>

   </tr>
     ';  


   
      $output .= '
      <tr>
      <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisition_outward_no.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisition_outward_date.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->requisitionNo.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->dept_name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->machineName.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->issueTo.'</td>
       <td style="border: 1px solid; padding:12px;">'.$rowMaster->reason.'</td>';
          $output .= '<td style="border: 1px solid; padding:12px;">'.$rowMaster->requisitiontype.'</td>';

      $output .= '</tr>';
    
     $output .= '</table>';
     

  

  $output .= '<br>';

  $output .= '
   <table style="width:100%; margin:0px;padding:0;border:none; border-collapse:collapse;table-layout:fixed;page-break-inside: avoid;">
      <tr>
  <th style="border: 1px solid;" width="150%">Item Name</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Unit</th>
    <th style="border: 1px solid; padding:12px;" width="40%">Balance Qty</th>
     <th style="border: 1px solid; padding:12px;" width="40%">Approved Qty</th>
<th style="border: 1px solid; padding:20px;" width="50%">Issued Qty</th>

   </tr>';    

     //DB::enableQueryLog();


 $RequisitionOutwardDetail = RequisitionOutwardDetailModel::join('item_master','item_master.item_code', '=', 'requisition_outward_detail.item_code')
  ->join('unit_master', 'unit_master.unit_id', '=', 'requisition_outward_detail.unit_id')    
  ->where('requisition_outward_detail.requisition_outward_no','=',$rowMaster->requisition_outward_no)
  ->get(['requisition_outward_detail.*','item_master.item_name','unit_master.unit_name']);



  //dd(DB::getQueryLog());

$no=1;
 foreach($RequisitionOutwardDetail as $rowDetail)
     {
      $output .= '
      <tr>
       <td  style="border: 1px solid;word-wrap: break-word;" width="20%">'.$rowDetail->item_name.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->unit_name.'</td>
       <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->balanceQty.'</td>
       <td style="border: 1px solid; padding:12px;" width="60%">'.$rowDetail->approvedQty.'</td>
 <td style="border: 1px solid; padding:12px;" width="40%">'.$rowDetail->issuedQty.'</td>

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
