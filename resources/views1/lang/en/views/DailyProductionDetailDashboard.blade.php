      
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
                                               <th rowspan="2">Order No</th>
                                                <th rowspan="2">Main Style Category</th>
                                                <th rowspan="2">Style Name</th> 
                                                 <th rowspan="2">Buyer PO No</th> 
                                                  <th rowspan="2"> Color</th> 
                                                 <th rowspan="2">Order Qty</th>
                                                <th colspan="3">Cutting</th>
                                                <th colspan="3">Line Issue</th>
                                                <th colspan="3">Stitching</th>
                                                <th colspan="3">Rejection</th>
                                                <th colspan="3">Finishing</th>
                                                <th colspan="3">Packing</th>
                                                <th rowspan="2">User</th>
                                               
                                            </tr>
                                            <tr>
                                                <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                 <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                 <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                 <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                  <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                  <th  >Today</th>
                                                <th  >Total</th>
                                                <th >Balance</th>
                                                
                                            </tr>
                                            
                                            
                                            </thead>
        
                                            <tbody>

                                            @foreach($ProductionOrderDetailList as $row)    
                                            <tr>
                                                <td> {{ $row->tr_code  }} </td>
                                                <td> {{ $row->mainstyle_name  }} </td>
                                                <td> {{ $row->style_no  }} </td>
                                                <td> {{ $row->po_code  }} </td>
                                                <td> {{ $row->color_name  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty }} </td>
                                                <td style="text-align:right;"> {{ $row->today_cutting_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_cutting_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty-$row->total_cutting_qty  }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->today_cut_panel_issue  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_cut_panel_issue  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty- $row->total_cutting_qty }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->today_stitching_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_stitching_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty-$row->total_stitching_qty  }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->today_qcstitching_reject_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_qcstitching_reject_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty-$row->total_qcstitching_reject_qty  }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->today_finishing_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_finishing_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty-$row->total_finishing_qty  }} </td>
                                                
                                                <td style="text-align:right;"> {{ $row->today_packing_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->total_packing_qty  }} </td>
                                                <td style="text-align:right;"> {{ $row->order_qty-$row->total_packing_qty  }} </td>
                                                   
                                                <td> {{ $row->username  }} </td>
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