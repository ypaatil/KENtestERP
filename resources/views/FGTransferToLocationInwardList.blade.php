@extends('layouts.master') 
@section('content')   
<style>
   .text-right
   {
   text-align:right;
   }
</style>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">FG Transfer To Location Inward List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">FG Transfer To Location Inward List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@if($chekform->write_access==1)    
<div class="col-md-4 mt-4">
   <a href="{{ Route('FGTransferToLocationInward.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a> 
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
                  <tr>
                     <th>Sr No</th>
                     <th>Code</th>
                     <th>FGT Code</th>
                     <th>PKI Code</th>
                     <th>From Location</th>
                     <th>Sales Order No</th>
                     <th>Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Order Rate</th>
                     <th>Total Qty</th>
                     <th>Total Amount </th>
                     <th>Narration</th>
                     <th>Username</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($FGTransferToLocationInwardList as $row)    
                  <tr>
                     <td> {{ substr($row->fgti_code,5,15)  }} </td>
                     <td> {{ $row->fgti_code  }} </td>
                     <td> {{ $row->fgt_code  }} </td>
                     <td> {{ $row->pki_code  }} </td>
                     <td> {{ $row->location  }} </td>
                     <td> {{ $row->sales_order_no  }} </td>
                     <td>{{ date("d-m-Y", strtotime($row->fgti_date)) }}</td>
                     <td> {{ $row->Ac_name  }} </td>
                     <td> {{ $row->order_rate  }} </td>
                     <td> {{ $row->total_qty  }} </td>
                     <td> {{ $row->order_amount  }} </td>
                     <td> {{ $row->narration  }} </td>
                     <td> {{ $row->username  }} </td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('FGTransferToLocationInward.edit', $row->fgti_code)}}" title="Edit"> 
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
                        <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->fgti_code }}"  data-route="{{route('FGTransferToLocationInward.destroy', $row->fgti_code )}}" title="Delete">
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