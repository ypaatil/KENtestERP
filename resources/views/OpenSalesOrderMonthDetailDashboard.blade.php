      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
@php
 
 if($job_status_id==1) { @endphp
 
 <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Open Sales Order Month Detail Dashboard</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Open Sales Order Month Detail Dashboard</li>
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
                                    <div class="table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center; white-space:nowrap">
                                             
                                                <th>Buyer Name</th>
                                                <th>Total Order Value  (Lakh)</th>
                                                <th>Total Order Qty  (Lakh)</th>
                                                <th>Total Shipped Qty  (Lakh)</th>
                                                <th>Pending  (Lakh)</th>
                                                @foreach($ShipmentMonth as $rows)  
                                                   <th>  {{ $rows->ShipMonth }}  (Lakh) </th>
                                                @endforeach
                                                 
                                               
                                            </tr>
                                            </thead>
        
                                            <tbody>
                                        @php
                                               $Buyer_Purchase_Order_List = Illuminate\Support\Facades\DB::select('select DATE_FORMAT(shipment_date,"%M-%Y") as shipmonth1,  buyer_purchse_order_master.Ac_code, ledger_master.ac_name,
                                               sum(order_value) as total_order_value, sum(total_qty) as total_order_qty,  sum(balance_qty) as total_pending_qty ,sum(shipped_qty) as total_shipped_qty
                                               from buyer_purchse_order_master
                                               inner join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
                                               where buyer_purchse_order_master.job_status_id=1
                                               group by buyer_purchse_order_master.Ac_code  order by buyer_purchse_order_master.shipment_date asc');
                                        @endphp      
                                           @foreach($Buyer_Purchase_Order_List as $row) 
                                              <tr > 
                                                <td  style="text-align:center; white-space:nowrap"> {{ $row->ac_name  }} </td>
                                                
                                                  <td style="text-align:right;"> {{  number_format((double)($row->total_order_value/100000), 3, '.', '')  }} </td>
                                                <td style="text-align:right;"> {{    number_format((double)($row->total_order_qty/100000), 3, '.', '')}} </td> 
                                              <td style="text-align:right;"> {{   number_format((double)($row->total_shipped_qty/100000), 3, '.', '') }} </td> 
                                                <td style="text-align:right;"> {{ number_format((double)($row->total_pending_qty/100000), 3, '.', '') }} </td>  
                                               
                                               
                                              @foreach($ShipmentMonth as $rowx)   
                                                @php
                                                
                                                $Buyer_Purchase_Order_List2 = Illuminate\Support\Facades\DB::select('select   
                                                ifnull(sum(balance_qty),0) as balance_qty
                                                from buyer_purchse_order_master where buyer_purchse_order_master.job_status_id=1 and 
                                                buyer_purchse_order_master.Ac_code="'.$row->Ac_code.'" and
                                                DATE_FORMAT(buyer_purchse_order_master.shipment_date,"%M-%Y")="'.$rowx->ShipMonth.'"');
                                              
                                                @endphp
                                                  @foreach($Buyer_Purchase_Order_List2 as $row1) 
                                                  
                                                   @if($row1->balance_qty!=0)
                                                <td style="text-align:right;"> {{  number_format((double)($row1->balance_qty/100000), 3, '.', '') }} </td> 
                                                 @else
                                                 <td></td>
                                                 @endif
                                             
                                                  
                                                    @endforeach
                                              
                                              
                                                
                                              @endforeach
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