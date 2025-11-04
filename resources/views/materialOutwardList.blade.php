@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
</style> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spares - Material Outward For Machine List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spares - Material Outward For Machine</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
@if(session()->has('delete'))
<div class="alert alert-danger">
   {{ session()->get('delete') }}
</div>
@endif
@if($chekform->write_access==1)
<div class="row">
   <div class="col-lg-6">
      <a href="{{ Route('MaterialOutward.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New</button></a> 
   </div>
</div>
@endif
</br>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table  data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead style="background-color: #556ee6; color: white; text-align:center; font-size:12px;">
                  <tr>
                     <th>Sr No</th>
                     <th>Material Outward No</th>
                     <th>Date</th>
                     <th>Location</th>
                     <th>Total Qty</th>
                     <th>Username</th>
                     <th>Edit</th>
                     <th>Remove</th>
                  </tr>
               </thead>
               <tbody>
                   @php
                        $srno = 1;
                   @endphp
                   @foreach($OutwardList as $row)
                  <tr>
                     <td class="text-right">{{$srno++}}</td>
                     <td>{{$row->materialOutwardCode}}</td>
                     <td class="text-center">{{date("d-m-Y", strtotime($row->materialOutwardDate))}}</td>
                     <td>{{$row->location}}</td>
                     <td class="text-right">{{$row->totalqty}}</td>
                     <td>{{$row->username}}</td>
                     <td class="text-center">
                        <a class="btn btn-sm edit" href="{{route('MaterialOutward.edit',base64_encode($row->materialOutwardCode))}}" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a> 
                     </td>
                     <td class="text-center"> 
                        <button class="btn btn-sm delete DeleteRecord" data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}"  data-id="{{ base64_encode($row->materialOutwardCode) }}"
                            data-route="{{ route('MaterialOutward.destroy',base64_encode($row->materialOutwardCode) )}}" title="Delete">
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
    $(document).on('click','#DeleteRecord',function(e) 
    {
   
        var Route = $(this).attr("data-route");
        var id = $(this).data("id");
        var token = $(this).data("token");
   
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