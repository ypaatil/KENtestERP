      
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
                                    <div class="table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr>
                                               <th>Code</th>
                                                <th>Bulk Merchant</th>
                                                <th>Received Date</th>
                                                <th>Entry Date</th>
                                                <th>Shipment Date</th>
                                                <th>Buyer Name</th>
                                                <th>PO No</th>  
                                                <th>Main Style Category</th>
                                                <th>Style Name</th> 
                                                <th>Garment Name</th> 
                                                <th>Buyer Brand</th>
                                                <th>Order Qty (Lakh)</th>
                                                <th>Shipped Qty (Lakh)</th>
                                                <th>Order Rate</th>
                                               
                                                <th>UOM</th>
                                                <th>Fabric Cost (Costing)</th>
                                                <th>Fabric Cost (Budget)</th>
                                                <th>Trims Cost (Costing)</th>
                                                <th>Trims Cost (Budget)</th>
                                                <th>MFG Cost (Costing)</th>
                                                <th>MFG Cost (Budget)</th>
                                                <th>Commission Cost (Costing)</th>
                                                <th>Commission Cost (Budget)</th>
                                                <th>Transport Cost (Costing)</th>
                                                <th>Transport Cost (Budget)</th>
                                                <th>OH</th>
                                                <th>DBK</th>
                                                <th>BOM Cost (Costing)</th>
                                                <th>BOM Cost (Budget)</th>
                                                <th>Profit Cost (Costing)</th>
                                                <th>Profit Cost (Budget)</th>
                                                <th>Profit % (Costing)</th>
                                                <th>Profit % (Budget)</th>
                                                <th>Ship Month</th>
                                               
                                               
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($Buyer_Purchase_Order_List as $row)    
                                            <tr>
                                                <td> {{ $row->tr_code  }} </td>
                                                 <td> {{ $row->merchant_name  }} </td>
                                                <td> {{ $row->order_received_date  }} </td>
                                                <td>  {{ date('d-m-Y', strtotime($row->tr_date)) }}   </td>
                                                <td>  {{ date('d-m-Y', strtotime($row->shipment_date)) }} </td>
                                                <td> {{ $row->Ac_name  }} </td>
                                                 <td> {{ $row->po_code  }} </td>
                                                 <td> {{ $row->mainstyle_name  }} </td>
                                                <td> {{ $row->style_no  }} </td>
                                                 <td> {{ $row->fg_name  }} </td>
                                                <td > {{ $row->brand_name  }} </td>
                                                <td style="text-align:right;"> {{    number_format((double)($row->total_qty/100000), 2, '.', '')}} </td> 
                                                <td style="text-align:right;"> {{   number_format((double)($row->shipped_qty/100000), 2, '.', '') }} </td> 
                                                <td style="text-align:right;"> {{ $row->order_rate  }} </td>
                                                <td style="text-align:right;"> {{ $row->unit_name  }} </td>
                                                <td style="text-align:right;"> {{ $row->cfabric_value  }} </td>
                                                <td style="text-align:right;"> {{ $row->bfabric_value/$row->total_qty  }} </td>
                                              
                                                <td style="text-align:right;"> {{ $row->csewing_trims_value + $row->cpacking_trims_value  }} </td>
                                                <td style="text-align:right;"> {{ (($row->bsewing_trims_value + $row->bpacking_trims_value) / $row->total_qty)  }} </td>
                                               
                                                <td style="text-align:right;"> {{ $row->production_value  }}  </td>
                                                <td style="text-align:right;"> {{ $row->production_value  }}  </td>
                                               
                                                <td style="text-align:right;"> {{ $row->agent_commision_value  }}  </td>
                                                <td style="text-align:right;"> {{ $row->agent_commision_value  }}  </td>
                                               
                                                <td style="text-align:right;"> {{ $row->transaport_value  }}   </td>
                                                <td style="text-align:right;"> {{ $row->transaport_value  }}  </td>
                                               <td style="text-align:right;"> {{ $row->other_value  }}  </td>
                                               
                                               <td style="text-align:right;"> {{ $row->dbk_value  }}  </td>
                                                <td style="text-align:right;"> {{  $row->cfabric_value + $row->csewing_trims_value + $row->cpacking_trims_value  }} </td>
                                                <td style="text-align:right;"> {{ ($row->bfabric_value + $row->bsewing_trims_value + $row->bpacking_trims_value) / $row->total_qty  }} </td>
                                              
                                                <td style="text-align:right;"> {{ $row->order_rate - $row->ctotal_cost_value   }} </td>
                                                <td style="text-align:right;"> {{round($row->order_rate - ((($row->bfabric_value/$row->total_qty)+($row->bsewing_trims_value + $row->bpacking_trims_value)/$row->total_qty)+$row->agent_commision_value+$row->transaport_value + $row->production_value+ $row->other_value+ $row->dbk_value),2)  }} </td>
                                              
                                                <td style="text-align:right;"> {{ round((($row->order_rate - $row->ctotal_cost_value)/$row->order_rate)*100,2)  }} </td>
                                                <td style="text-align:right;"> {{ round((($row->order_rate - ((($row->bfabric_value/$row->total_qty)+($row->bsewing_trims_value + $row->bpacking_trims_value)/$row->total_qty)+$row->agent_commision_value+$row->transaport_value + $row->production_value+ $row->other_value+ $row->dbk_value))/$row->order_rate)*100 ,2) }} </td>
                                                
                                                
                                                <td>  {{ date('M-Y', strtotime($row->shipment_date)) }} </td>
                                               
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