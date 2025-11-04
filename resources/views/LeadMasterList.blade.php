@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Lead List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Lead List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('Lead.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
                     <th>Sr No</th>
                     <th>Lead Id</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>  
                     <th>Market</th>  
                     <th>Currency</th>  
                     <th>Country</th>  
                     <th>Lead Status</th> 
                     <th>User</th>   
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1; 
                  @endphp
                  @foreach($crmData as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td> 
                     <td>LD{{ $row->crm_id }}</td>
                     <td>{{ $row->buyer_name }}</td>
                     <td>{{ $row->buyer_brand }}</td>  
                     <td>{{ $row->order_group_name }}</td>  
                     <td>{{ $row->currency_name }}</td>  
                     <td>{{ $row->c_name }}</td>  
                     <td>{{ $row->lead_status_name }}</td>  
                     <td>{{ $row->username }}</td>   
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('Lead.edit', $row->crm_id)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td> 
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->crm_id }}"  data-route="{{route('Lead.destroy', $row->crm_id )}}" title="Delete">
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
</script>  
@endsection