@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Transfer FG Stock List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Location Transfer FG Stock List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('LocTransferPackingInhouse.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="Transfer_Packing_Inhouse_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>SrNo</th>
                     <th>Code</th>
                     <th>Sales Order No</th>
                     <th>From Location</th>
                     <th>To Location</th>
                     <th>Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Order Rate</th>
                     <th>Total Qty</th>
                     <th>Total Amount </th>
                     <th>Narration</th>
                     <th>User</th>
                     <th>Print</th>
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
        $('#Transfer_Packing_Inhouse_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#Transfer_Packing_Inhouse_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('LocTransferPackingInhouse.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'ltpki_code', name: 'ltpki_code1'},
          {data: 'ltpki_code', name: 'ltpki_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'from_location', name: 'from_location'},
          {data: 'to_location', name: 'to_location'},
          {data: 'ltpki_date', name: "ltpki_date"},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'order_rate', name: 'order_rate'},
          {data: 'total_qty', name: 'total_qty'},
          {data: 'order_amount', name: 'order_amount'},
          {data: 'narration', name: 'narration'},
          {data: 'username', name: 'username'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false}
        ]
    });
    
  });
</script>  
@endsection