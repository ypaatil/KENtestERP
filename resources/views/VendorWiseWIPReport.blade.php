@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Vendor Wise WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Vendor Wise Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
                 <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                     <thead>
                          <tr style="background-color:#eee;"> 
                               <th nowrap>Vendor Name</th>
                               <th nowrap>Sales Order No</th>
                               <th nowrap>Buyer Name</th>
                               <th class="text-center" nowrap>Work Order Qty</th> 
                               <th class="text-center" nowrap>Packing Qty</th>
                               <th class="text-center" nowrap>Rejection Qty</th> 
                               <th class="text-center" nowrap>Inward Qty</th> 
                               <th class="text-center" nowrap>WIP</th>
                               <th class="text-center" nowrap>Rate</th>
                               <th class="text-center" nowrap>WIP Value</th>
                          </tr>
                     </thead>
                     <tbody>  
                        @foreach($vendorList as $row)   
                        <tr> 
                           <td nowrap>{{ $row->vendor_name }}</td>
                           <td nowrap>{{ $row->sales_order_no }}</td>
                           <td nowrap>{{ $row->buyer_name }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",$row->work_order_qty) }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",$row->packing_qty) }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",$row->reject_qty) }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",$row->packing_qty + $row->reject_qty) }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",(($row->work_order_qty - $row->cut_panel_grn_qty) + ($row->cut_panel_grn_qty - $row->stitching_qty) + ($row->stitching_qty - $row->packing_qty - $row->reject_qty) - $row->wip_adjust_qty)) }}</td>
                           <td nowrap class="text-right">{{ round(($row->fabric_value + $row->packing_trims_value+ $row->sewing_trims_value),2) }}</td>
                           <td nowrap class="text-right">{{ money_format("%!.0n",(($row->work_order_qty - $row->cut_panel_grn_qty) + ($row->cut_panel_grn_qty - $row->stitching_qty) + ($row->stitching_qty - $row->packing_qty - $row->reject_qty) - $row->wip_adjust_qty) * ($row->fabric_value + $row->packing_trims_value+ $row->sewing_trims_value)) }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script></script>
@endsection