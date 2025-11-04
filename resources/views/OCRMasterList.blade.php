@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">OCR Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">OCR Master</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('OCR.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
@if(session()->has('message'))
<div class="col-md-3">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('delete'))
<div class="col-md-3">
   <div class="alert alert-danger">
      {{ session()->get('delete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="example table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>SrNo</th>
                     <th>Sales Order No</th>
                     <th class="text-center">Total Transport Qty</th>
                     <th class="text-center">Total Testing Qty</th> 
                     <th>Edit</th> 
                     <th>Delete</th> 
                  </tr>
               </thead>
               <tbody> 
                @php
                    $srno = 1;
                @endphp
                @foreach($OCRMasterList as $row)
                  <tr> 
                     <td>{{$srno++}}</td>
                     <td>{{$row->sales_order_no}}</td>
                     <td class="text-center">{{$row->total_transport_qty}}</td>
                     <td class="text-center">{{$row->total_testing_qty}}</td>
                     <td>  
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('OCR.edit', $row->ocr_master_id)}}" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a> 
                     </td>
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{$row->ocr_master_id}}" 
                           data-route="{{route('OCR.destroy', $row->ocr_master_id)}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button>                 
                     </td>
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
   
   
   //  $(document).ready(function() {
   //     $('#datatable-buttons').DataTable( {
   //         "order": [[ 16, "desc" ]]
   //     } );
   // } );
   
   
</script>            
@endsection