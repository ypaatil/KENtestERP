@extends('layouts.master') 
@section('content')   
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
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Checking Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Checking Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div> 
                   <div class="col-sm-5">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button type="button" onclick="tableData(1);" class="btn btn-primary w-md">Search</button>
                         <a href="javascript:void(0);" onclick="ClearReport(0);" class="btn btn-danger w-md">Cancel</a>
                      </div>
                   </div> 
               </div>
         </div>
      </div>
   </div>
</div>

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body table-responsive">
            <table id="dt" class="table table-bordered  dt-responsive nowrap w-100 ">
               <thead>
                  <tr style="text-align:center;">
                     
                     <th>Date <span class="filter-icon">🔽</span><div class="filter-menu date_"></div></th>
                     <th>CHK Code <span class="filter-icon">🔽</span><div class="filter-menu chk-code"></div></th>
                     <th>Track Code <span class="filter-icon">🔽</span><div class="filter-menu track-code"></div></th>
                     <th>Supplier Name <span class="filter-icon">🔽</span><div class="filter-menu supplier-name"></div></th>
                     <th>Bill To <span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                     <th>PO Code <span class="filter-icon">🔽</span><div class="filter-menu po-code"></div></th>
                     <th>GRN No <span class="filter-icon">🔽</span><div class="filter-menu grn-no"></div></th>
                     <th>Item Code <span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                     <th>Item Name <span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                     <th>Color <span class="filter-icon">🔽</span><div class="filter-menu color"></div></th>
                     <th>Item Description <span class="filter-icon">🔽</span><div class="filter-menu item-description"></div></th>
                     <th>Status <span class="filter-icon">🔽</span><div class="filter-menu status"></div></th>
                     <th>GRN Meter </th>
                     <th>QC Pass Meter </th>
                     <th>QC Reject Meter </th>
                     <th>QC Pass + Reject Meter </th>
                     <th>Shrinkage <span class="filter-icon ">🔽</span><div class="filter-menu shrinkage"></div></th>
                     <th>Pass % <span class="filter-icon ">🔽</span><div class="filter-menu pass-percentage"></div></th>
                     <th>Reject % <span class="filter-icon ">🔽</span><div class="filter-menu reject-percentage"></div></th>

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
<script type="text/javascript">
   function tableData(ele) 
    {
      var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
         var currentURL = "";

         if(ele == 1)
         {
              currentURL = "FabricCheckingDashboard?fromDate="+fromDate+"&toDate="+toDate; 
         }
         else
         { 
               currentURL = window.location.href+"?fromDate="+fromDate+"&toDate="+toDate; 
         }

         const today = new Date();
         const day = String(today.getDate()).padStart(2, '0');
         const month = String(today.getMonth() + 1).padStart(2, '0');
         const year = today.getFullYear();
         const formattedDate = `${day}-${month}-${year}`;
         const exportTitle = 'Fabric Checking Report (' + formattedDate + ')';

         
   	   $('#dt').DataTable().clear().destroy();
     
        var table = $('#dt').DataTable({
        
        // ajax: "{{ route('FabricCheckingDashboard') }}",

        ajax: {
                url: currentURL,
                type: "GET"
            },
         deferRender: true,
              dom: 'lBfrtip',
         buttons: [
             { extend: 'copyHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
             { extend: 'excelHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
             { extend: 'csvHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() },
             { extend: 'pdfHtml5', footer: true ,title: exportTitle, exportOptions: commonExportOptions() }
         ],
         
         initComplete: function () {
               buildAllMenusFabricCheckingDashboardReport();
            },

         columns: [
           {data: 'chk_date', name: 'chk_date'},
           {data: 'chk_code', name: 'chk_code'},
           {data: 'track_code', name: 'track_code'},
            {data: 'Ac_name', name: 'ledger_master.Ac_name'},
            {data: 'bill_to', name: 'bill_to'},
            {data: 'po_code', name: 'po_code'},
            {data: 'in_code', name: 'in_code'},
           {data: 'item_code', name: 'fabric_checking_details.item_code'},
           {data: 'item_name', name: 'item_master.item_name'},
           {data: 'color_name', name: "item_master.color_name"},
           {data: 'item_description', name: 'item_master.item_description'},
           {data: 'status', name: 'status'},
           {data: 'total_Meter', name: 'total_Meter'},
           {data: 'totalPassMeter', name: 'totalPassMeter'},
           {data: 'totalRejectMeter', name: 'totalRejectMeter'},
           {data: 'totalPassRejectPer', name: 'totalPassRejectPer'},
           {data: 'shrinkage', name: 'shrinkage'},
           {data: 'totalPassPer', name: 'totalPassPer'},
           {data: 'totalRejectPer', name: 'totalRejectPer'},
             
         ]
     });
     
   } 

            // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if (menu.hasClass('date_')) applyDateFilter(0, menu);
         else if (menu.hasClass('chk-code')) applySimpleFilter(1, menu);
         else if (menu.hasClass('track-code')) applySimpleFilter(2, menu);
         else if (menu.hasClass('supplier-name')) applySimpleFilter(3, menu);
         else if (menu.hasClass('bill-to')) applySimpleFilter(4, menu);
         else if (menu.hasClass('po-code')) applySimpleFilter(5, menu);
         else if (menu.hasClass('grn-no')) applySimpleFilter(6, menu);
         else if (menu.hasClass('item-code')) applySimpleFilter(7, menu);
         else if (menu.hasClass('item-name')) applySimpleFilter(8, menu);
         else if (menu.hasClass('color')) applySimpleFilter(9, menu);
         else if (menu.hasClass('item-description')) applySimpleFilter(10, menu);
         else if (menu.hasClass('status')) applySimpleFilter(11, menu);
         else if (menu.hasClass('shrinkage')) applySimpleFilter(16, menu);
         else if (menu.hasClass('pass-percentage')) applySimpleFilter(17, menu);
         else if (menu.hasClass('reject-percentage')) applySimpleFilter(18, menu);

         $('.filter-menu').hide();         
         buildAllMenusFabricCheckingDashboardReport();       
         });
        // End script for filter search and apply

   function ClearReport()
    {
         removeFilterColor();
         tableData(0);
    }

    $( document ).ready(function() 
    { 
      removeFilterColor();
      tableData(0);       
    });

</script> 
@endsection