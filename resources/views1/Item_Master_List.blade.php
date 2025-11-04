@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Data Tables</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">Item List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-lg-6">
      <a href="{{ Route('Item.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a> &nbsp; &nbsp; &nbsp;
      <a href="list/{{ 0 }}"><button type="button" class="btn btn-success w-md float-right">Active</button></a>&nbsp; &nbsp; &nbsp;
      <a href="list/{{ 1 }}"><button type="button" class="btn btn-danger w-md float-right">InActive</button></a>
   </div>
   <div class="col-md-3">
      <form action="{{ route('itemimport') }}" method="POST" enctype="multipart/form-data">
         @csrf
         <input type="file" name="itemfile" class="form-control">
         <br>
         <button class="btn btn-success">Import Item Data</button>
      </form>
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="Item_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Code</th>
                     <th>Category Name</th>
                     <th>Classification</th>
                     <th>Item Name</th>
                     <th>Item Description</th>
                     <th>Quality</th>
                     <th>UOM</th>
                     <th>Color</th>
                     <th>Width/Dimention</th>
                     <th>MOQ</th>
                     <th>Status</th>
                     <th>HSN Code</th>
                     <th>Preview</th>
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
        $('#Item_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#Item_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('Item.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excel', exportOptions: { modifier: { page: 'all', search: 'none' } } },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'item_code', name: 'item_code'},
          {data: 'cat_name', name: 'cat_name'},
          {data: 'class_name', name: 'class_name'},
          {data: 'item_name', name: "item_name"},
          {data: 'item_description', name: 'item_description'},
          {data: 'quality_name', name: 'quality_name'},
          {data: 'unit_name', name: 'unit_name'},
          {data: 'color_name', name: 'color_name'},
          {data: 'dimension', name: 'dimension'},
          {data: 'moq', name: 'moq'},
          {data: 'status1', name: 'status1'},
          {data: 'hsn_code', name: 'hsn_code'},
          {data: 'imagePath', name: 'imagePath'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false}
        ]
    });
    
  });
</script>    
@endsection