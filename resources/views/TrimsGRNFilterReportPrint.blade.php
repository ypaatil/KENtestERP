@extends('layouts.master') 
@section('content')   

                        @php setlocale(LC_MONETARY, 'en_IN'); @endphp
                        <!-- end page title -->
                     <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Trims GRN Detail</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Trims GRN Detail</li>
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
    <p class="  fw-medium" style="color:#fff;">Total Amount</p>
    <h4 class="mb-0" style="color:#fff;">{{money_format('%!i',round($TrimsInwardTotal[0]->TotalAmount,2))}} </h4>
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






                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        <div class="table-responsive">
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center;">
                                                 <th>GRN No.</th>
                                                    <th>GRN Date.</th>
                                             
                                                <th>PO No</th>   
                                                <th>Supplier Name</th>
                                                 <th>Sales Order No</th>
                                                 
                                               
                                                    <th>Item Code</th>
                                                     <th>Item Name</th>
                                                     <th>Item Description</th>
                                                       <th>Color</th>
                                                      <th>GRN Qty    </th>
                                                      <th>Rate   </th>
                                                      <th>Value    </th>
                                                    
                                                      
                                                       
                                              
                                               
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           @foreach($TrimsInwardDetails as $row)   
                                              
                                            <tr>
                                           <td style="text-align:center; white-space:nowrap"> {{ $row->trimCode  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->trimDate  }} </td>
                                               
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>
                                              <td style="text-align:center; white-space:nowrap">{{$row->ac_name}}</td> 
                                              <td style="text-align:center; white-space:nowrap">   @if($row->is_opening!=1) {{$row->sales_order_no}} @else Opening Stock @endif  </td>
                                                
                                               
                                                <td> {{ $row->item_code  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->item_name  }} </td>  
                                                
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->item_description  }} </td>
                                                <td style="text-align:center; white-space:nowrap"> {{ $row->color_name  }} </td>
                                                <td style="text-align:right;"> {{   $row->item_qty  }} </td>
                                                <td style="text-align:right;"> {{   $row->item_rate  }} </td>
                                                <td style="text-align:right;"> {{   round($row->item_qty *$row->item_rate) }} </td>
                                                 
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