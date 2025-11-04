@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">BOM</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">BOM</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('BOM.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
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
            <table  data-order='[[ 0, "desc" ]]' data-page-length='25' id="bom_table" class="table table-bordered dt-responsive nowrap w-100 footable_2">
               <thead>
                  <tr style="text-align:center;">
                     <th>SRNO</th>
                     <th>BOM No</th>
                     <th>Sales Order No</th>
                     <th>Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Season</th>
                     <th>Fabric Cost</th>
                     <th>Sewing Trims Cost</th>
                     <th>Packing Trims Cost</th>
                     <th>Total Cost</th>
                     <th>BOM</th>
                     <th>Budget</th>
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
   $(document).on('click','.DeleteRecord',function(e) {
   
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
   
   
    $(function () 
    {
        $('#bom_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#bom_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('BOM.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excel', exportOptions: { modifier: { page: 'all', search: 'none' } } },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'bom_code1', name: 'bom_code1'},
          {data: 'bom_code', name: 'bom_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'bom_date', name: "bom_date"},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'season_name', name: 'season_name'},
          {data: 'fabric_value', name: 'fabric_value'},
          {data: 'sewing_trims_value', name: 'sewing_trims_value'},
          {data: 'packing_trims_value', name: 'packing_trims_value'},
          {data: 'total_cost_value', name: 'total_cost_value'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action4', name: 'action3',orderable: false, searchable: false},
        ]
    });
    
  });
   
</script>  
@endsection