      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
@php
 
 if($job_status_id==1) { @endphp
 
 <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Order Progress Finishing Detail Dashboard</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Order Progress Finishing Detail Dashboard</li>
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
    <h4 class="mb-0" style="color:#fff;">  </h4>
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
    <h4 class="mb-0" style="color:#fff;" >  </h4>
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
    <h4 class="mb-0" style="color:#fff;">     </h4>
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
    <h4 class="mb-0" style="color:#fff;"> </h4>
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
                                    <table id="datatable-buttons" class="table   table-bordered">
                                          <thead>
                                            <tr style="text-align:center">
                                                <th rowspan="2">Work Order No</th>
                                                 <th rowspan="2">Vendor Name</th>
                                                <th rowspan="2">Order No</th>
                                                <th rowspan="2">Main Style Category</th>
                                                <th rowspan="2">Buyer PO No</th> 
                                                <th rowspan="2"> Color</th> 
                                                <th rowspan="2">Order Qty</th>
                                              
                                                <th colspan="2">Finishing</th>
                                            </tr>
                                            <tr>
                                                
                                                <th>Total</th>
                                                <th>Balance</th>
                                               
                                                 
                                               
                                                
                                            </tr>
                                            
                                            
                                            </thead>
        
                                            <tbody>

                                            @foreach($ProductionOrderDetailList as $row)    
                                            <tr>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->vpo_code  }} </td>
                                                 <td style="text-align:center; white-space:nowrap"> {{ $row->vendorName  }} </td>
                                                
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->sales_order_no  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                                               
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->color_name  }} </td>
                                                <td style="text-align:right;"> {{ number_format($row->order_qty) }} </td>
                                          
                                                <td style="text-align:right;"> {{ number_format($row->total_finishing_qty ) }} </td>
                                                <td style="text-align:right;"> {{ number_format($row->order_qty-$row->total_finishing_qty ) }} </td>
                                         
                                              
                                                
                                       
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                          </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
                                                       <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
  
                        
                        @endsection