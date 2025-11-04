@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">QC Final GRN Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="qc_grn_qty">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Pending For QC Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="pending_for_qc_qty">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Stock Qty</p>
                  <h4 class="mb-0" style="color:#fff;" id="total_Stock_qty">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Value</p>
                  <h4 class="mb-0" style="color:#fff;" id="all_value">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
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
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="16"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">0</th>
                        <th id="head_total_qc_qty"></th>
                        <th id="head_total_outward_qty"></th>
                        <th id="head_total_stock_qty">0</th>
                        <th></th>
                        <th id="head_total_value">0</th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Supplier Name</th>
                        <th>Buyer Name</th>
                        <th>PO Status</th>
                        <th>PO No</th>
                        <th>GRN No</th>
                        <th>Invoice No</th>
                        <th>Item Code</th>
                        <th>Preview</th>
                        <th>Shade No.</th>
                        <th>Item Name</th>
                        <th>Width</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Status</th>
                        <th>Track Code</th>
                        <th>Rack Name</th>
                        <th>GRN Qty</th>
                        <th>QC Qty</th>
                        <th>Outward Qty</th>
                        <th>Stock Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php
                        $total_grn_qty = 0;
                        $total_qc_qty = 0;
                        $total_outward_qty = 0;
                        $total_stock_qty = 0;
                        $total_value = 0;
                    @endphp
                    @foreach($FabricInwardDetails1 as $row)   
                    @if(($row->meter-$row->out_meter)>0)
                    @php
                     
                         $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name FROM fabric_checking_details 
                         LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                         WHERE track_code = '".$row->track_code."'");
                         
                         if(count($checking_width) > 0)
                         {
                             $width = $checking_width[0]->width;
                             $fcs_name = $checking_width[0]->fcs_name;
                         }
                         else
                         {
                             $width = 0;
                             $fcs_name = "-";
                         }
                         
                        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$row->po_status);
                        
                        if(count($JobStatusList) > 0)
                        {
                            $job_status_name = $JobStatusList[0]->job_status_name;
                        }
                        else
                        {
                            $job_status_name = "-";
                        }
                        
                        $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_codes."'");
                     
                    if(count($salesOrderNo) > 0)
                    {
                         $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                         INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                         where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                         
                         if(count($buyerData) > 0)
                         {
                            $buyer_name = $buyerData[0]->ac_name;
                         }
                         else
                         {
                            $buyer_name = "-";
                         }
                    }
                    else
                    {
                        $buyer_name = "-";
                    } 
                     
                    $GRNData =DB::select("select meter as grnQty from inward_details where  track_code='".$row->track_code."'");
                        
                    if(count($GRNData) > 0)
                    {
                        $grnQty = $GRNData[0]->grnQty;
                    }
                    else
                    {
                        $grnQty = 0;
                    }
                     
                    $QCData =DB::select("select meter as QCQty from fabric_checking_details where track_code='".$row->track_code."'");
                     
                    if(count($QCData) > 0)
                    {
                         $QCQty = $QCData[0]->QCQty;
                    }
                    else
                    {
                        $QCQty = 0;
                    }
                    @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap">{{$row->ac_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$buyer_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$job_status_name}}</td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_codes  }} </td>
                         <td style="text-align:center; white-space:nowrap"> {{ $row->in_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{$row->invoice_no}} </td>
                        <td> {{ $row->item_code  }} </td>
                        @if($row->item_image_path!='')
                        <td><a href="{{url('images/'.$row->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$row->item_image_path)}}" alt="{{ $row->item_code }}"></a></td>
                        @else
                        <td>No Image</td>
                        @endif
                        <td style="text-align:center; white-space:nowrap"> {{ $row->shade_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $width  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->quality_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->color_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_description  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $fcs_name  }} </td>
                        <td> {{ $row->track_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->rack_name  }} </td>
                        <td style="text-align:right; ">{{$grnQty}}</td>
                        <td style="text-align:right; ">{{$QCQty}}</td>
                        <td style="text-align:right; ">{{$row->out_meter}}</td>
                        <td style="text-align:right; "> {{  $row->ActualMeter-$row->out_meter  }} </td>
                        <td style="text-align:right; "> {{  $row->item_rate  }} </td>
                        <td style="text-align:right; "> {{  ($row->ActualMeter-$row->out_meter) * $row->item_rate }} </td>
                     </tr>
                     @php
                        $total_grn_qty = $total_grn_qty + $grnQty;
                        $total_qc_qty = $total_qc_qty + $QCQty;
                        $total_outward_qty = $total_outward_qty + $row->out_meter;
                        $total_stock_qty = $total_stock_qty + ($row->ActualMeter-$row->out_meter);
                        $total_value = $total_value + (($row->ActualMeter-$row->out_meter) * $row->item_rate);
                     @endphp
                     @endif
                     @endforeach
                     
                     
                    @if($isOpening == 2)
                    @foreach($FabricInwardDetails2 as $row)   
                    @if(($row->ActualMeter-$row->out_meter)>0)
                    @php
                     
                         $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name FROM fabric_checking_details 
                         LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                         WHERE track_code = '".$row->track_code."'");
                         
                         if(count($checking_width) > 0)
                         {
                             $width = $checking_width[0]->width;
                             $fcs_name = $checking_width[0]->fcs_name;
                         }
                         else
                         {
                             $width = 0;
                             $fcs_name = "-";
                         }
                         
                         
                            $job_status_name = "-";
                        
                        
                        $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_codes."'");
                     
                    if(count($salesOrderNo) > 0)
                    {
                         $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                         INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                         where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                         
                         if(count($buyerData) > 0)
                         {
                            $buyer_name = $buyerData[0]->ac_name;
                         }
                         else
                         {
                            $buyer_name = "-";
                         }
                    }
                    else
                    {
                        $buyer_name = "-";
                    } 
                     
                    $GRNData =DB::select("select meter as grnQty from inward_details where  track_code='".$row->track_code."'");
                        
                    if(count($GRNData) > 0)
                    {
                        $grnQty = $GRNData[0]->grnQty;
                    }
                    else
                    {
                        $grnQty = 0;
                    }
                     
                    $QCData =DB::select("select meter as QCQty from fabric_checking_details where track_code='".$row->track_code."'");
                     
                    if(count($QCData) > 0)
                    {
                         $QCQty = $QCData[0]->QCQty;
                    }
                    else
                    {
                        $QCQty = 0;
                    }
                    @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap">{{$row->ac_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$buyer_name}}</td>
                        <td style="text-align:center; white-space:nowrap">{{$job_status_name}}</td>
                       
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_codes  }} </td> 
                        <td style="text-align:center; white-space:nowrap"> {{ $row->in_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{$row->invoice_no}} </td>
                        <td> {{ $row->item_code  }} </td>
                        @if($row->item_image_path!='')
                        <td><a href="{{url('images/'.$row->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$row->item_image_path)}}" alt="{{ $row->item_code }}"></a></td>
                        @else
                        <td>No Image</td>
                        @endif
                        <td style="text-align:center; white-space:nowrap"> {{ $row->shade_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $width  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->quality_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->color_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->item_description  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $fcs_name  }} </td>
                        <td> {{ $row->track_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->rack_name  }} </td>
                        <td style="text-align:right; ">{{$grnQty}}</td>
                        <td style="text-align:right; ">{{$QCQty}}</td>  
                        <td style="text-align:right; ">{{$row->out_meter}}</td>
                        <td style="text-align:right; "> {{  $row->ActualMeter-$row->out_meter  }} </td>
                        <td style="text-align:right; "> {{  $row->item_rate  }} </td>
                        <td style="text-align:right; "> {{  ($row->ActualMeter-$row->out_meter) * $row->item_rate }} </td>
                     </tr>
                     @php
                        $total_grn_qty = $total_grn_qty + $grnQty;
                        $total_qc_qty = $total_qc_qty + $QCQty;
                        $total_outward_qty = $total_outward_qty + $row->out_meter;
                        $total_stock_qty = $total_stock_qty + ($row->meter-$row->out_meter);
                        $total_value = $total_value + (($row->meter-$row->out_meter) * $row->item_rate);
                     @endphp
                     @endif
                     @endforeach
                     @endif
                     <input type="hidden" id="total_grn_qty" value="{{money_format('%!i',round($total_grn_qty))}}">
                     <input type="hidden" id="total_qc_qty" value="{{money_format('%!i',round($total_qc_qty))}}">
                     <input type="hidden" id="total_outward_qty" value="{{money_format('%!i',round($total_outward_qty))}}">
                     <input type="hidden" id="total_stock_qty" value="{{money_format('%!i',round($total_stock_qty))}}">
                     <input type="hidden" id="total_value" value="{{money_format('%!i',round($total_value))}}"> 
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $('#head_total_grn_qty').html($('#total_grn_qty').val());
    $('#head_total_qc_qty').html($('#total_qc_qty').val());
    $('#head_total_outward_qty').html($('#total_outward_qty').val());
    $('#head_total_stock_qty').html($('#total_stock_qty').val());
    $('#head_total_value').html($('#total_value').val());
    
    $("#qc_grn_qty").html($('#total_grn_qty').val());
    $("#pending_for_qc_qty").html($('#total_qc_qty').val());
    $("#total_outward_qty").html($('#total_outward_qty').val());
    $("#total_Stock_qty").html($('#total_stock_qty').val());
    $("#all_value").html($('#total_value').val());
</script>
@endsection