@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order Costing</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order Costing</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!--<div class="row">-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#152d9f;" >-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;">No. of Orders</p>-->
<!--<h4 class="mb-0" style="color:#fff;">  </h4>-->
<!--    </div>-->
<!--    <div class="flex-shrink-0 align-self-center">-->
<!--    <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">-->
<!--    <span class="avatar-title" style="background-color:#152d9f;">-->
<!--    <i class="bx bx-copy-alt font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#556ee6;">-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;" >Order Qty</p>-->
<!--<h4 class="mb-0" style="color:#fff;" >  </h4>-->
<!--    </div>-->
<!--    <div class="flex-shrink-0 align-self-center ">-->
<!--    <div class="avatar-sm rounded-circle bg-primary  ">-->
<!--    <span class="avatar-title  " style="background-color:#556ee6;" >-->
<!--   <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    <div class="col-md-3">-->
<!--    <div class="card mini-stats-wid" style="background-color:#f79733;">-->
<!--    <div class="card-body">-->
<!--    <div class="d-flex">-->
<!--    <div class="flex-grow-1">-->
<!--    <p class="  fw-medium" style="color:#fff;">Order Value</p>-->
<!--<h4 class="mb-0" style="color:#fff;">  </h4>-->
<!--    </div>-->
<!--   <div class="flex-shrink-0 align-self-center">-->
<!--    <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--    <span class="avatar-title  " style="background-color:#f79733;">-->
<!--    <i class="bx bx-archive-in font-size-24"></i>-->
<!--    </span>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>-->
<!--    </div>  -->
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('SalesOrderCosting.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
@endif
@if(session()->has('message'))
<div class="col-md-12">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('error'))
<div class="col-md-12">
   <div class="alert alert-danger">
      {{ session()->get('error') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="sales_consting_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center;">
                     <th>SrNo</th>
                     <th>Costing No</th>
                     <th>Order No</th>
                     <th>Status</th>
                     <th>Costing Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Main Style</th>
                     <th>Style Name</th>
                     <th>FOB Rate</th>
                     <th>Fabric Cost</th>
                     <th>Sewing Trims Cost</th>
                     <th>Packing Trims Cost</th>
                     <th>Production Cost</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Repeat</th>
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

        if (confirm("Are you sure you want to Delete this Record?") == true) {
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
        $('#sales_consting_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
        
       var table = $('#sales_consting_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('SalesOrderCosting.index') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'soc_code1', name: 'soc_code1'},
          {data: 'soc_code', name: 'soc_code'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'job_status_name', name: "job_status_name"},
          {data: 'soc_date', name: 'soc_date'},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'mainstyle_name', name: 'mainstyle_name'},
          {data: 'fg_name', name: 'fg_name'},
          {data: 'order_rate', name: 'order_rate'},
          {data: 'fabric_value', name: 'fabric_value'},
          {data: 'sewing_trims_value', name: 'sewing_trims_value'},
          {data: 'packing_trims_value', name: 'packing_trims_value'},
          {data: 'production_value', name: 'production_value'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action4', name: 'action3',orderable: false, searchable: false},
        ]
    });
    
  });
</script>  
@endsection