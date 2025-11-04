@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spare Item Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Spare Item Master List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-lg-6">
      <a href="{{ Route('SpareItem.create') }}"><button type="button" class="btn btn-primary w-md float-right">Add New Record</button></a> 
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
                     <th nowrap>Spare Item Code</th>
                     <th nowrap>Machine Type</th>
                     <th nowrap>Machine Make</th>
                     <th nowrap>Machine Model</th>
                     <th nowrap>Item Name</th>
                     <th nowrap>Part Name</th> 
                     <th nowrap>CGST %</th>
                     <th nowrap>SGST %</th>
                     <th nowrap>IGST %</th>
                     <th nowrap>HSN Code</th>
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
        ajax: "{{ route('SpareItem.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excel', exportOptions: { modifier: { page: 'all', search: 'none' } } },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'spare_item_code', name: 'spare_item_code'},
          {data: 'machinetype_name', name: 'machinetype_name'},
          {data: 'machine_make_name', name: 'machine_make_name'},
          {data: 'mc_model_name', name: 'mc_model_name'},
          {data: 'item_name', name: "item_name"},
          {data: 'dimension', name: 'dimension'},
          {data: 'cgst_per', name: 'cgst_per'},
          {data: 'sgst_per', name: 'sgst_per'},
          {data: 'igst_per', name: 'igst_per'},
          {data: 'hsn_code', name: 'hsn_code'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false}
        ]
    });
    
  });
</script>    
@endsection