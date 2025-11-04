@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Outward For Packing Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Outward For Packing Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th nowrap>Sr. No.</th>
                     <th nowrap>Outward  No.</th> 
                     <th nowrap>Outward Date</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Work Order No</th>
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Buyer Brand</th>
                     <th nowrap>From Stitching Vendor</th>
                     <th nowrap>Sent To Packing Vendor</th>
                     <th nowrap>Main Style Category</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Color</th>
                     <th nowrap>Sizes</th>
                     <th nowrap>Size Qty</th>
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
    
    $(document).ready(function() 
    {
         tableData(); 
    }); 
    
    
    function tableData() 
    {
        var currentURL = window.location.href;
    
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#dt')) {
            // DataTable is already initialized, clear and destroy it
            $('#dt').DataTable().clear().destroy();
        }
    
        var table = $('#dt').DataTable({
            ajax: currentURL,
            processing: false,
            serverSide: false,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                {  
                    extend: 'excel', 
                    exportOptions: {
                        modifier: {
                            order: 'index',  
                            page: 'all', 
                            search: 'none'  
                        },
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                    }
                },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            columns: [
                {data: 'srno', name: 'srno'}, 
                {data: 'ofp_code', name: 'ofp_code'},
                {data: 'ofp_date', name: 'ofp_date',class: 'text-center'},
                {data: 'sales_order_no', name: 'sales_order_no',class: 'text-center'},
                {data: 'vw_code', name: 'vw_code'},
                {data: 'buyer_name', name: 'buyer_name'},
                {data: 'brand_name', name: 'brand_name'},
                {data: 'from_vendor', name: 'from_vendor'},
                {data: 'sent_vendor', name: 'sent_vendor'},
                {data: 'mainstyle_name', name: 'mainstyle_name'}, 
                {data: 'fg_name', name: 'fg_name'},
                {data: 'color_name', name: 'color_name'},
                {data: 'size_name', name: 'size_name'},
                {data: 'size_qty', name: 'size_qty'}
            ]
        });
    }

</script>               
@endsection