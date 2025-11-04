@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Data Tables</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Color List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-md-3">
      <a href="{{ Route('Color.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
   <div class="col-md-9">
      <form action="{{ route('importcolor') }}" method="POST" enctype="multipart/form-data">
         @csrf
         <div class="row">
            <div class="col-md-3">
             <input type="file" name="colorfile" class="form-control"> 
            </div>
            <div class="col-md-3">
             <button class="btn btn-success">Import Color Data</button>
            </div>
         </div>
      </form>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="color_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Color Id</th>
                     <th>Color </th>
                     <th>Status </th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript"> 

  
   function ChangeStatus(row)
   {  
          
          var color_id = $(row).attr('color_id');
          var status1 = $(row).attr('status');
          if(status1 == 1)
          {
              var status = 0;
          }
          else
          {
              var status = 1;
          }
          
          Swal.fire({
            title: 'Are you sure?',
            text: "This color status will change",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
          }).then((result) => {
            if (result.isConfirmed) 
            {
                 $.ajax({
                 type: "GET", 
                 url: "{{ route('changeColorStatus') }}",
                 data:{'color_id':color_id,'status':status},
                 success: function(data)
                 {
                     Swal.fire(
                        'Closed!',
                        'Your color status has been changed.',
                        'success'
                      )
                      
                      if(status == 1)
                      { 
                        $(row).removeClass('btn-danger').addClass('btn-success').html('Active');
                      }
                      else
                      {
                        $(row).removeClass('btn-success').addClass('btn-danger').html('In Active');
                      }
                      
                      $(row).attr('status', status);
                  }
                  }); 
            }
          })
    }
    
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
        $('#color_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#color_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('Color.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'color_id', name: 'color_id'},
          {data: 'color_name', name: 'color_name'},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false}
        ]
    });
    
  });
</script>     
@endsection