@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<style>
    .text-right
    {
        text-align:right;
    }
</style>

<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">PO Authority Matrix</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">PO Authority Matrix : All</li>
            </ol>
         </div>
      </div>
   </div>
</div>
                     
@if($chekform->write_access==1)    
<div class="row">
   <div class="col-md-12">
      <a href="{{ Route('so_po_authority_matrix.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
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
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="salesOrderTable" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr> 
                     <th nowrap style="text-align:center;">Date</th>
                     <th nowrap style="text-align:center;">Sales Order</th>   
                     <th nowrap style="text-align:center;" style="text-align:center;">Buyer</th>
                     <th nowrap style="text-align:center;">Brand</th>  
                     <th nowrap style="text-align:center;">Category</th>
                     <th nowrap style="text-align:center;">Item</th>
                     <th nowrap style="text-align:center;">Classification</th>  
                     <th nowrap style="text-align:center;">Bom Qty</th>      
                     <th nowrap style="text-align:center;">Created By</th>   
                     <th style="text-align:center;">Edit</th>
                     <th style="text-align:center;">Delete</th>
                  </tr>
               </thead>
               <tbody>
                 
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
<script type="text/javascript"> 
$(function () {
    var Route = window.location.href;

    // Destroy existing instance if any
    if ($.fn.DataTable.isDataTable('#salesOrderTable')) {
        $('#salesOrderTable').DataTable().destroy();
    }

    $('#salesOrderTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: Route,
            type: "GET",
            dataSrc: "data" // Adjust if your response format is different
        },
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        columns: [
            { data: 'so_po_authority_date', name: 'so_po_authority_date' },
            { data: 'sales_order_no', name: 'sales_order_no' },  
            { data: 'ac_name', name: 'ledger_master.ac_name' },
            { data: 'brand_name', name: 'brand_master.brand_name' }, 
            { data: 'cat_name', name: 'cat_name' },      
            { data: 'item_name', name: 'item_master.item_name' },
            { data: 'class_name', name: 'classification_master.class_name' },  
            { data: 'bom_qty', name: 'bom_qty', className:"text-right" },
            { data: 'username', name: 'username' },
            { data: 'action1', name: 'action1', orderable: false, searchable: false },
            { data: 'action2', name: 'action2', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 25
    });
});

   
   
   
   
   
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
   
              // console.log(data);
             location.reload();
   
            }
   });
   }
   
    });
</script>  
@endsection