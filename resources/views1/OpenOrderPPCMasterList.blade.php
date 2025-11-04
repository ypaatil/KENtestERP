@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Open Order PPC</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Open Order PPC List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('OpenOrderPPC.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
      <a href="{{ Route('rptOpenOrderPPC') }}" target="_blank"><button type="button" class="btn btn-secondary w-md">View Report</button></a>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr.No.</th>
                     <th>Sales Order No.</th>
                     <th>Vendor</th>
                     <th>Qty</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($openOrderPPCList as $row)    
                  <tr>
                     <td>{{ $srno++ }}</td>
                     <td>{{ $row->sales_order_no }}</td>
                     <td>{{ $row->ac_name }}</td>
                     <td>{{ $row->vendorQty }}</td>
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('OpenOrderPPC.edit', $row->openOrderPPCDetailId )}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     <td>
                        <button  class="btn   btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ base64_encode($row->openOrderPPCDetailId) }}"  data-route="{{route('OpenOrderPPC.destroy', base64_encode($row->openOrderPPCDetailId))}}" title="Delete">
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
      if (confirm("Are you sure you want to Delete this Record?") == true) 
      {
            $.ajax({
                   url: Route,
                   type: "DELETE",
                    data: {
                    "id": id,
                    "_method": 'DELETE',
                     "_token": token,
                     },
                   
                   success: function(data)
                   {
                      location.reload();
          
                   }
          });
      }
   });
</script>     
@endsection