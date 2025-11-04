@extends('layouts.master') 
@section('content')    
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Vendor Work Order</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Vendor Work Order </li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-4">
    @if($chekform->write_access==1)     
          <a href="{{ Route('VendorWorkOrder.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
    @endif
   </div>
    <div class="col-lg-4 text-center">
       <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="javascript:void(0);"  onclick="showAll();">Show All Data</a> button</h4>
    </div>
    <div class="col-lg-4 text-right">
        <button type="button" class="btn btn-warning w-md float-right" onclick="showAll();">Show All Data</button> &nbsp; &nbsp; &nbsp;
        <button type="button" class="btn btn-danger w-md float-right" onclick="back();">Back</button> &nbsp; &nbsp; &nbsp;
    </div>
</div>
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="vendor_work_order_table"  data-export-title="test" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center;">
                     <th>SrNo</th>
                     <th>Work Order No</th>
                     <th>Status</th>
                     <th>Vendor Name</th>
                     <th>Sales Order No</th>
                     <th>Work Order Date</th>
                     <th>Buyer Name</th>
                     <th>User Name</th>
                     <th>Job Work Rate</th>
                     <th>Delivery Date</th>
                     <th>Work Order Qty</th>
                     <th>Updated Date</th>
                     <th>Contract Print</th>
                     <th>Print</th>
                     <th>Edit</th>
                     <th>Order</th>
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

   function closeOrder(row)
   {
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });   
        var vw_code = $(row).attr('vw_code');
        Swal.fire({
          title: 'Are you sure?',
          text: "This order will close",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, do it!'
        }).then((result) => {
          if (result.isConfirmed) 
          {
               $.ajax({
               type: "POST", 
               url: "{{ route('WorkOrderClose') }}",
               data:{'vw_code':vw_code},
               success: function(data)
               {
                   Swal.fire(
                      'Closed!',
                      'Your Order has been closed.',
                      'success'
                    )
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
        var url = 'VendorWorkOrder?page=0';
        TableData(url);
    });
  
    function showAll()
    {
        var url = 'VendorWorkOrder?page=1';
        TableData(url);
    }
    
    function back()
    {
        var url = 'VendorWorkOrder?page=0';
        TableData(url);
    }

  
   function TableData(url)
   {
        $('#vendor_work_order_table').dataTable({
            "bDestroy": true
        }).fnDestroy();
       document.title='Vendor Work Order Report';
       var table = $('#vendor_work_order_table').DataTable({
        // processing: true,
        // serverSide: true,
        ajax: url,
        dom: 'lBfrtip', 
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excel',
              title: '', 
              exportOptions: { modifier: { 
              order : 'index', 
              page : 'all', 
              search : 'none'}}},
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'vw_code1', name: 'vw_code1'},
          {data: 'vw_code', name: 'vw_code'},
          {data: 'job_status_name', name: 'job_status_name'},
          {data: 'vendorName', name: "vendorName"},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'vw_date', name: 'vw_date'},
          {data: 'Ac_name', name: 'Ac_name'},
          {data: 'username', name: 'username'},
          {data: 'vendorRate', name: 'vendorRate'},
          {data: 'delivery_date', name: 'delivery_date'},
          {data: 'final_bom_qty', name: 'final_bom_qty'},
          {data: 'updated_at', name: 'updated_at'},
          {data: 'action1', name: 'action1',orderable: false, searchable: false},
          {data: 'action2', name: 'action2',orderable: false, searchable: false},
          {data: 'action3', name: 'action3',orderable: false, searchable: false},
          {data: 'action5', name: 'action5',orderable: false, searchable: false},
          {data: 'action4', name: 'action4',orderable: false, searchable: false}
        ]
    });
    
  }
</script>  
@endsection