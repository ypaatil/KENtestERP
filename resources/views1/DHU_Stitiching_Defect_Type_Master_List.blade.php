@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">DHU - Stitiching Defect Type</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">DHU - Stitiching Defect Type</li>
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
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('DHUStitichingDefectType.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Main Style Name</th>
                     <th>DHU Stiching Defect Type Name</th>
                     <th>DHU Stiching Operation Name</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($dhuList as $row)
                  <tr>
                     <td>{{$row->mainstyle_name}}</td>
                     <td>{{$row->dhu_sdt_Name}}</td>
                     <td>{{$row->dhu_so_Name}}</td>
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('DHUStitichingDefectType.edit', $row->dhu_sdt_Id)}}" title="Edit">
                       <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     <td>
                        <button  class="btn btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->dhu_sdt_Id }}"  data-route="{{route('DHUStitichingDefectType.destroy', $row->dhu_sdt_Id )}}" title="Delete">
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