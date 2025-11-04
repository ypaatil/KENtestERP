@extends('layouts.master') 
@section('content')   
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
<style>
    .text-right
    {
        text-align:right;
    }
    
    .navbar-brand-box
    {
        width: 266px !important;
    }
</style> 
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Outlet Sale List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Outlet</a></li>
               <li class="breadcrumb-item active">Outlet Sale List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('OutletSale.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th class="text-center" nowrap>Sr. No.</th>
                     <th class="text-center">Outward No.</th>
                     <th class="text-center">Date</th> 
                     <th class="text-center">Customer Name</th> 
                     <th class="text-center">Employee Code</th> 
                     <th class="text-center">Payment Type</th> 
                     <th nowrap>Total Qty</th>  
                     <th class="text-center">Amount</th> 
                     <th class="text-center">User Name</th> 
                     <th class="text-center">Print</th>
                     <th class="text-center">Edit</th>
                     <th class="text-center">Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($OutletList as $row)    
                  <tr>
                     <td class="text-center">{{ $srno++ }}</td>
                     <td class="text-center">{{ $row->outlet_sale_id }}</td>
                     <td>{{ $row->bill_date }}</td>
                     <td>{{ $row->fullName ? $row->fullName : $row->other_customer }}</td>  
                     <td class="text-center">{{ $row->employeeCode ? $row->employeeCode : "Other" }}</td>  
                     <td>{{ $row->payment_option_name }}</td>  
                     <td class="text-right">{{ $row->total_qty }}</td> 
                     <td class="text-right">{{money_format('%!.2n',($row->net_amount)) }}</td>
                     <td>{{ $row->username }}</td>
                     <td class="text-center">
                        <a class="btn btn-outline-secondary btn-sm print" target="_blank" href="OutletSalePrint/{{$row->outlet_sale_id}}" title="print">
                            <i class="fas fa-print"></i>
                        </a>
                     </td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('OutletSale.edit', $row->outlet_sale_id)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     @else
                     <td class="text-center">
                        <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                        <i class="fas fa-lock"></i>
                        </a>
                     </td>
                     @endif
                     @if($chekform->delete_access==1) 
                     <td class="text-center">
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->outlet_sale_id }}"  data-route="{{route('OutletSale.destroy', $row->outlet_sale_id )}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button>         
                     </td>
                     @else
                     <td class="text-center">
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
   </div>
   <!-- end col -->
</div>
<!-- end row -->
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