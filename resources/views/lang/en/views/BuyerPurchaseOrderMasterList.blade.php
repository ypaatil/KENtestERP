      
   @extends('layouts.master') 

@section('content')   

                        
                        <!-- end page title -->
@php
 
if($job_status_id==0) { @endphp
 
    
      

        

<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Sales Order: All</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Sales Order : All</li>
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
    <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
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
    <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
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
    <p class="  fw-medium" style="color:#fff;">Order Value(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($total_value/100000), 2, '.', '')}}   </h4>
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
 elseif($job_status_id==1) { @endphp
 
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
    <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
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
    <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
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
    <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qty/100000), 2, '.', '')}}  </h4>
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
    <p class="  fw-medium" style="color:#fff;">Open Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qty/100000), 2, '.', '')}} </h4>
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
      elseif($job_status_id==2) { @endphp
   <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Sales Order: Closed</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Sales Order : Closed</li>
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
    <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
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
    <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
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
    <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;">  {{number_format((double)($shipped_qty/100000), 2, '.', '')}}  </h4>
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
    <p class="  fw-medium" style="color:#fff;">Balance Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;">{{number_format((double)($open_qty/100000), 2, '.', '')}} </h4>
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
      
  @php }    
       elseif($job_status_id==3) { @endphp
       
        <div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-18">Sales Order: Cancelled</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                <li class="breadcrumb-item active">Sales Order : Cancelled</li>
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
    <h4 class="mb-0" style="color:#fff;">{{number_format($NoOfOrder)}} </h4>
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
    <p class="  fw-medium" style="color:#fff;" >Order Qty(Lacs)</p>
    <h4 class="mb-0" style="color:#fff;" >{{number_format((double)($total_qty/100000), 2, '.', '')}} </h4>
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
    
    </div>  
      
  @php }
      @endphp                           
                        
                        
                        
                        @if($chekform->write_access==1)    
                        <div class="row">
                        <div class="col-md-12">
                        <a href="{{ Route('BuyerPurchaseOrder.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
                        <a href="{{ Route('BuyerPurchaseOrder.index') }}"><button type="button" class="btn btn-danger w-md">All</button></a>
                        <a href="{{ Route('SalesOrderOpen') }}"><button type="button" class="btn btn-warning w-md">Open</button></a>
                        <a href="{{ Route('SalesOrderClosed') }}"><button type="button" class="btn btn-success w-md">Closed</button></a>
                        <a href="{{ Route('SalesOrderCancelled') }}"><button type="button" class="btn btn-secondary w-md">Cancelled</button></a>
                        </div>
                        </div>
                        @endif
                        
                        
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                    <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center;">
                                                <th>SrNo</th>
                                               <th>Buyer PO No</th>     
                                               <th>Order No</th>
                                                <th>Date</th>
                                                <th>Shipment Date</th>
                                                <th>Buyer Name</th>
                                                <th>Main Style Category</th>
                                                <th>Order Qty</th>
                                                <th>Shipped Qty</th>
                                                <th>Balance Qty</th>
                                                <th>Narration</th>
                                                <th>PO status</th>
                                                <th>User</th>
                                                <th>Print</th>  
                                                <th>Edit</th>     
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
        
                                            <tbody>

                                            @foreach($Buyer_Purchase_Order_List as $row)    
                                            <tr>
                                                @php $number=intval(substr($row->tr_code,5,15)); @endphp
                                                  <td>{{$number}}</td>
                                                    <td style="text-align:center; white-space:nowrap"> {{ $row->po_code  }} </td>    
                                                <td style="text-align:center; white-space:nowrap;"> {{ $row->tr_code  }} </td>
                                               <td style="text-align:center; white-space:nowrap;">  {{ date('d-m-Y', strtotime($row->tr_date)) }}   </td>
                                                <td style="text-align:center; white-space:nowrap;">  {{ date('d-m-Y', strtotime($row->shipment_date)) }} </td>
                                                <td style="text-align:center;"> {{ $row->Ac_name  }} </td>
                                          
                                                <td style="text-align:center;"> {{ $row->mainstyle_name  }} </td>
                                               
                                                 
                                                 <td style="text-align:right;"> {{ number_format($row->total_qty)  }} </td> 
                                                <td style="text-align:right;"> {{ number_format($row->shipped_qty)  }} </td> 
                                                <td style="text-align:right;"> {{ number_format($row->balance_qty)  }} </td> 
                                                <td style="text-align:center;"> {{ $row->narration  }} </td>
                                                <td style="text-align:center;"> {{ $row->job_status_name  }} </td>
                                                <td style="text-align:center;"> {{ $row->username  }} </td>
                                                @if($chekform->edit_access==1)
                                                     <td>
                                                <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/{{ $row->tr_code }}" title="print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                </td>
                                                
                                                <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" href="{{route('BuyerPurchaseOrder.edit', $row->tr_code)}}" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                </td>
                                                @else
                                                <td>
                                                    <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                                                        <i class="fas fa-lock"></i>
                                                    </a>
                                                </td>

                                                @endif
                                                @if($chekform->delete_access==1) 
                                                <td>
                                                
      <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->tr_code }}"  data-route="{{route('BuyerPurchaseOrder.destroy', $row->tr_code )}}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                        </button>   
                                                
                                                </td>
                                                
  @else

                         <td>
                               
<button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
<i class="fas fa-lock"></i>
</button>
                                </td>

@endif
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                        
 <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

        
       
        
      
<script type="text/javascript"> 

 $(document).on('click','#DeleteRecord',function(e) {

    var Route = $(this).attr("data-route");
    var id = $(this).data("id");
    var token = $(this).data("token");

   //alert(Route);
  
  //alert(data);
    if (confirm("Are you sure you want to Delete this Record?") == true) {
  $.ajax({
         url: Route,
         type: "DELETE",
          data: {
          "id": id,
          "_method": 'DELETE',
           "_token": token,
           },
         
         success: function(data){

            //alert(data);
         location.reload();

         }
});
}

 });
</script>  
                        
                        @endsection