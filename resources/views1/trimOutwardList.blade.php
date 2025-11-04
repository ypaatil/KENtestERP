@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trim Outward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trim Outward</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('TrimsOutward.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="trim_outward_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>DC No</th>
                     <th>Issue Date</th>
                     <th>Outward Type</th>
                     <th>Vendor Name</th>
                     <th>Trims Type </th>
                     <th>Process Order No </th>
                     <th>Sales Order No.</th>
                     <th>Buyer Name</th>
                     <th>Total Qty</th>
                     <th>Regular Print</th>
                     <th>GST Print</th>
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
                   
                   success: function(data){
           
                      //alert(data);
                   location.reload();
           
                   }
               });
        }
   
   });
   
    $(function () 
    {
        $('#trim_outward_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#trim_outward_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('TrimsOutward.index') }}",
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        columns: [
          {data: 'trimOutCode1', name: 'trimOutCode1'},
          {data: 'trimOutCode', name: 'trimOutCode'},
          {data: 'tout_date', name: 'tout_date'},
          {data: 'out_type_name', name: 'out_type_name'},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'vw_code', name: 'vw_code'},
          {data: 'Trim_Type', name: 'Trim_Type'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'vendorName', name: 'vendorName'},
          {data: 'total_qty', name: 'total_qty'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action4', name: 'action3',orderable: false, searchable: false},
        ]
    });
    
  });
  
</script> 
@endsection