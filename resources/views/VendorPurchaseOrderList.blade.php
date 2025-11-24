@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
    
   .navbar-brand-box
   {
        width: 266px !important;
   }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Vendor Purchase Order Model</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Vendor Process Order </li>
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
<div class="row">
   <div class="col-md-4">   
       @if($chekform->write_access==1) 
              <a href="{{ Route('VendorPurchaseOrder.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
        @endif
   </div>
    <div class="col-lg-4 text-center">
       <h4><b>Note : </b> Showing last 2 month records. If you want to all click on <a href="javascript:void(0);" onclick="showAll();">Show All Data</a> button</h4>
    </div>
    <div class="col-lg-4 text-right">
        <a href="javascript:void(0);" onclick="showAll();"><button type="button" class="btn btn-warning w-md float-right">Show All Data</button></a> &nbsp; &nbsp; &nbsp;
        <a href="VendorPurchaseOrder"><button type="button" class="btn btn-danger w-md float-right">Back</button></a> &nbsp; &nbsp; &nbsp;
    </div> 
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/VendorPurchaseOrder" method="GET" enctype="multipart/form-data">
                <div class="row">  
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="job_status_id" class="form-label">Status</label> 
                           <select name="job_status_id" class="form-control select2" id="job_status_id">
                                    <option value="1" {{ $job_status_id == 1 ? 'selected="selected"' : '' }} >Open</option> 
                                    <option value="2" {{ $job_status_id == 2 ? 'selected="selected"' : '' }} >Close</option>  
                            </select>
                        </div>
                    </div>  
                    <div class="col-sm-6 mt-2">
                        <label for="" class="form-label"></label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-md">Search</button>
                            <a href="/VendorPurchaseOrder" class="btn btn-danger w-md">Cancel</a>
                        </div>
                    </div>
                </div> 
                </div>
                </form>
            </div>
        </div>
    </div>
@if(session()->has('message'))
<div class="col-md-6">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('messagedelete'))
<div class="col-md-6">
   <div class="alert alert-danger">
      {{ session()->get('messagedelete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="dt" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th><a href="javascript:void(0);" class="btn-sm btn-warning"  onclick="GetCheckBox(this);" >Print</a></th>
                     <th>Sr No</th>
                     <th>Process PO No</th>
                     <th>Status</th>
                     <th>Process Type</th>
                     <th>Vendor Name</th>
                     <th>Sales Order No</th>
                     <th>PO Date</th>
                     <th>Delivery Date</th>
                     <th>Buyer Name</th>
                     <th>User Name</th>
                     <th>Main Style</th>
                     <th>Process Order Qty</th>
                     <th>Updated Date</th>
                     <th>Print</th>
                     <th>DC Print</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<script type="text/javascript"> 
    
    
    function GetCheckBox() 
    {
        // Initialize an empty array to store selected ofp_code values
        var chk_arr = [];
        
        // Iterate through all checkboxes with the class 'chk'
        $('.chk').each(function() {
            if ($(this).prop('checked')) {
                // Push the 'ofp_code' attribute of the checked checkbox into the array
                chk_arr.push($(this).attr('vpo_code'));
            }
        });
    
        // If there are selected items, construct the URL and redirect
        if (chk_arr.length > 0) {
            const baseUrl = "VendorPurchaseOrderMergedPrint";
            
            // Convert array to a comma-separated string for better URL handling
            const queryString = `vpo_codes=${chk_arr.join(',')}`;
            
            // Redirect to the URL with the constructed query string
            window.location.href = `${baseUrl}?${queryString}`;
        } else {
            alert("No items selected!");
        }
    }
    
    function getParameterByName(name) {
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    $(document).ready(function () 
    {
        var job_status_id = getParameterByName('job_status_id') || $('#job_status_id').val() || 2;
    
        var url = 'VendorPurchaseOrder?job_status_id=' + job_status_id + '&page=0';
    
        if ($('#dt').length) {
            TableData(url);
        }
    
        // var url = 'VendorPurchaseOrder?page=0';
        // if ($('#dt').length) {
        //     TableData(url);
        // }
    });
    
    function showAll() {
        var url = 'VendorPurchaseOrder?page=1';
        TableData(url);
    }
    
    function TableData(url) {
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().destroy();
        }
    
        $('#dt').DataTable({
            processing: true,
            serverSide: false,
            ajax: url,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            columns: [
                { data: 'chk', name: 'chk' },
                { data: 'vpo_code1', name: 'vpo_code1' },
                { data: 'vpo_code', name: 'vpo_code' },
                { data: 'job_status_name', name: 'job_status_name' },
                { data: 'process_name', name: "process_name" },
                { data: 'vendorName', name: 'vendorName' },
                { data: 'sales_order_no', name: 'sales_order_no' },
                { data: 'vpo_date', name: 'vpo_date' },
                { data: 'delivery_date', name: 'delivery_date' },
                { data: 'Ac_name', name: 'Ac_name' },
                { data: 'username', name: 'username' }, 
                { data: 'mainstyle_name', name: 'mainstyle_name' },
                { data: 'final_bom_qty', name: 'final_bom_qty' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action1', name: 'action1', orderable: false, searchable: false },
                { data: 'action2', name: 'action2', orderable: false, searchable: false },
                { data: 'action3', name: 'action3', orderable: false, searchable: false },
                { data: 'action4', name: 'action4', orderable: false, searchable: false },
                { data: 'action5', name: 'action5', orderable: false, searchable: false }
            ]
        });
    }

    
   function closeOrder(row)
   {
         $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });   
          var vpo_code = $(row).attr('vpo_code');
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
                 url: "{{ route('PurchaseOrderClose') }}",
                 data:{'vpo_code':vpo_code},
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
   $(document).on('click','.delete',function(e) 
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