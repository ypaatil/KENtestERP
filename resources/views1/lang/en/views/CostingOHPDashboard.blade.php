      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
@php
 
 if($job_status_id==1) { @endphp
 
 <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Sales Order: Open</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Sales Order : Open</li>
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
    <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qtyc/100000), 2, '.', '')}} </h4>
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
    <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qtyc/100000), 2, '.', '')}}  </h4>
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
    <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
    <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qtyc/100000), 2, '.', '')}} </h4>
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
        
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr>
                                                <th>Code</th>
											    <th>PO No</th>	
											  
											    <th>Buyer Name</th>
												<th>Buyer Brand</th>
												<th>Order Date</th>
                                                <th>Shipment Date</th>
                                                <th>Main Style Category</th>
                                                <th>Style Name</th> 
                                              
                                                <th>Order Qty (Lakh)</th>
                                                  <th>FOB Rate</th>
                                                   <th>Profit Value</th>
                                                    <th>Profit %</th>
                                                    <th>OH Value</th>
                                                     <th>OH %</th>
                                                       <th>OH+P Value</th>
                                                       <th>OH+P%</th>
                                                       <th>Order Value (Lakh)</th>
                                                       <th>OHP Value (Lakh)</th>
                                          
                                                <th>Bulk Merchant</th>
                                               
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($BOMCostingStatusList as $row)    
                                            <tr>
                                                <td> {{ $row->tr_code  }} </td>
                                                <td> {{ $row->po_code  }} </td>
											 
												<td> {{ $row->ac_name  }} </td>
												 <td> {{ $row->brand_name  }} </td>
                                                <td> {{ date('d-m-Y', strtotime($row->order_received_date)) }}   </td>
                                                <td> {{ date('d-m-Y', strtotime($row->shipment_date)) }} </td>
                                                <td> {{ $row->mainstyle_name  }} </td>
                                                <td> {{ $row->style_no  }} </td>
                                               
                                                <td style="text-align:right;"> {{   number_format((double)($row->total_qty/100000), 2, '.', '') }} </td> 
                                                 
                                                  <td style="text-align:right;"> {{ $row->order_rate  }} </td>
                                                  <td style="text-align:right;"> {{ $row->Profit  }} </td>
                                                  <td style="text-align:right;"> {{ ($row->Profit/$row->order_rate)*100  }} </td>
                                                   
                                                 <td style="text-align:right;"> {{ number_format($row->other_value)  }} </td> 
                                                  <td style="text-align:right;"> {{ ($row->other_value/$row->order_rate)*100  }} </td>
                                                 
                                                  <td style="text-align:right;"> {{ number_format($row->other_value+$row->Profit)  }} </td>
                                                    <td style="text-align:right;"> {{  (($row->other_value+$row->Profit)/$row->order_rate)*100  }} </td>
                                                     <td style="text-align:right;">  {{   number_format((double)($row->order_value/100000), 2, '.', '') }} </td> 
                                                 <td style="text-align:right;"> {{  number_format((double)((($row->other_value+$row->Profit)*$row->total_qty)/100000), 2, '.', '') }} </td>
                                                  
                                                 <td> {{ $row->merchant_name  }} </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                                                       <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  
                        
                        @endsection