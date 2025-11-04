@extends('layouts.master') 
@section('content')   
<style>

    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Style No</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Style No List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-md-3">
      <a href="{{ Route('StyleNo.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="style_no_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Style No Id</th>
                     <th>Buyer Name</th>
                     <th>Style No</th>
                     <th>Username</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 

   $(document).on('click','.DeleteRecord',function(e) 
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
     
    $(function () 
    {
        $('#style_no_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#style_no_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('StyleNo.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [  
          {data: 'style_no_id', name: 'style_no_id'}, 
          {data: 'ac_short_name', name: 'ac_short_name'}, 
          {data: 'style_no', name: 'style_no'}, 
          {data: 'username', name: 'username'}, 
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false}
        ]
    });
    
  });
</script>     
@endsection