@extends('layouts.master') 
@section('content')   
<!-- end page title -->
@php
setlocale(LC_MONETARY, 'en_IN');  
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Total Sales Order Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Total Sales Order Detail Dashboard</li>
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
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrderc)}} </h4>
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
                  <p class="  fw-medium" style="color:#fff;" >Order Qty</p>
                  <h4 class="mb-0" style="color:#fff;" >{{money_format('%!i',($total_qtyc))}} </h4>
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
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty</p>
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',$shipped_qtyc)}}  </h4>
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
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty</p>
                  <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',$open_qtyc)}} </h4>
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
</div>
@php 
}
@endphp                          
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Code</th>
                        <th>PO status</th>
                        <th>Order Type</th>
                        <th>Received Date</th>
                        <th>Plan Cut Date</th>
                        <th>Shipment Date</th>
                        <th>Buyer Name</th>
                        <th>PO No</th>
                        <th>Buyer Brand</th>
                        <th>Main Style Category</th>
                        <th>Style Name</th>
                        <th>SAM</th>
                        <th>Order Rate</th>
                        <th>Order Qty  </th>
                        <th>Order Value  </th>
                        <th>Shipped Qty </th>
                        <th>Balance Qty  </th>
                        <th>Bulk Merchant</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($Buyer_Purchase_Order_List as $row)  
                     @php
                        if($row->order_type == 1)
                        {
                            $orderType = 'Fresh';
                        }
                        else if($row->order_type == 2)
                        {
                            $orderType = 'Stock';
                        }
                        else if($row->order_type == 3)
                        {
                            $orderType = 'Job Work';
                        }
                        else
                        {
                            $orderType = '';
                        }
                     @endphp  
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td> {{ $row->job_status_name  }} </td>
                        <td> {{ $orderType  }} </td>
                        <td style="text-align:center; white-space:nowrap;">  {{ $row->order_received_date }}   </td>
                        <td style="text-align:center; white-space:nowrap">  {{ $row->plan_cut_date }} </td>
                        <td style="text-align:center; white-space:nowrap">  {{ $row->shipment_date }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->Ac_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->style_no  }} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->sam  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!i',(double)($row->order_rate))  }} </td>
                        <td style="text-align:right;"> {{ number_format($row->total_qty) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!i',(double)($row->total_qty*$row->order_rate)) }} </td>
                        <td style="text-align:right;"> {{ number_format((double)($row->shipped_qty))}} </td>
                        <td style="text-align:right;"> {{ number_format((double)($row->balance_qty))}} </td>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->merchant_name  }} </td>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection