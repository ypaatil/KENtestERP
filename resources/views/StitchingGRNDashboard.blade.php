@extends('layouts.master') 
@section('content')   
@php
    setlocale(LC_MONETARY, 'en_IN');   
    ini_set('memory_limit', '10G'); 
@endphp
<style>

    .center-cell 
    {
        text-align: center !important; 
    }

</style>
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Stitching GRN Detail Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Stitching GRN Detail Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row hide">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;"></h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" > </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"></h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary">
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
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
            <div class="row">
               <div class="col-md-2"></div>
               <div class="col-md-10">
                  <div class="card mini-stats-wid">
                     <div class="card-body">
                        <div class="d-flex"> 
                         <form action="StitchingGRNDashboard" method="GET">
                             <div class="row">
                                <div class="col-md-3">  
                                    <label><b>From Date</b></label><input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}" required>
                                </div>
                                <div class="col-md-3">  
                                    <label><b>To Date</b></label><input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}" required>
                                </div>
                                <div class="col-md-6 mt-4">   
                                     <label></label><button type="submit" class="btn btn-primary">Search</button>
                                     <label></label><a href="StitchingGRNDashboard" class="btn btn-danger">Clear</a>
                                     <a href="javascript:void(0);" id="exportBtn" class="btn btn-warning">Export to Excel</a>
                                </div>
                             </div>
                         </form>
                        </div>
                     </div>
                  </div>
               </div> 
            </div>
         <div class="card-body table-responsive">
            <table id="dt" class="table">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>Sr No</th>
                     <th nowrap>GRN No</th>
                     <th nowrap>GRN Date</th>
                     <th nowrap>Sales Order No</th>
                     <th nowrap>Order Status</th>
                     <th nowrap>Buyer Name</th>
                     <th nowrap>Buyer Brand</th>
                     <th nowrap>SAM</th>
                     <th nowrap>Work Order No</th>
                     <th nowrap>Vendor Name</th>
                     <th nowrap>Main Style Category</th>
                     <th nowrap>Style Name</th>
                     <th nowrap>Color</th>
                     <th nowrap>Line no</th>
                     <th nowrap>Sizes</th>
                     <th nowrap>Size Qty</th>
                     <th nowrap>Minutes</th>
                     <th nowrap>Helper</th>
                     <th nowrap>Operator</th>
                     <th nowrap>Total Manpower</th>
                  </tr> 
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <input type="hidden" id="h_total_operator" value="0">
   <input type="hidden" id="h_sales_order_no" value="">
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>

    function exportFullTableToExcel() 
    {
      // Export with SheetJS
      const worksheet = XLSX.utils.table_to_sheet(document.getElementById("dt"));
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
      XLSX.writeFile(workbook, "datatable_export.xlsx");
    
    }
    
    $( document ).ready(function() 
    { 
        tableData(); 
    });
 
        
    $('#exportBtn').on('click', function () 
    {
        exportFullTableToExcel();
    });
    
    
    function tableData() 
    {
        var currentURL = window.location.href;
    
        $('#dt').DataTable().clear().destroy();
    
        var table = $('#dt').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            processing: true,
            stateSave: true,
            paging: false,
            buttons: [],
            // buttons: [
            //     { extend: 'copyHtml5', footer: true },
            //     { extend: 'excelHtml5', footer: true },
            //     { extend: 'csvHtml5', footer: true },
            //     { extend: 'pdfHtml5', footer: true }
            // ],
            // buttons: [
            //   {
            //     extend: 'excelHtml5',
            //     text: 'Export with merge',
            //     customize: function (xlsx) {
            //       var sheet = xlsx.xl.worksheets['sheet1.xls'];
            
            //       // Example: Merge A2:A3
            //       $('mergeCells', sheet).append('<mergeCell ref="A2:A3"/>');
            //       $('worksheet', sheet).attr('xmlns:mx', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            //       $('worksheet', sheet).attr('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
            //       $('worksheet', sheet).append('<mergeCells count="1"><mergeCell ref="A2:A3"/></mergeCells>');
            //     }
            //   }
            // ],
            footerCallback: function (row, data, start, end, display) {
                var total_size_qty = 0;
                var total_min = 0;
    
                for (var i = 0; i < data.length; i++) {
                    total_size_qty += parseFloat(data[i].size_qty || 0);
                    total_min += parseFloat(data[i].Minutes || 0);
                }
    
                $('#head_total_size_qty').html(total_size_qty.toFixed(2));
                $('#head_total_min').html(total_min.toFixed(2));
            },
            columnDefs: [{
                targets: 0,
                autoWidth: true,
                searchable: false,
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            }],
            createdRow: function (row, data, dataIndex) {
                $(row).addClass('sales_order_' + data.sales_order_no);
                $(row).addClass('total_op_' + data.total_operator1);
            },
            // drawCallback: function (settings) {
            //     var api = this.api();
            //     var seen = {};
            
            //     api.rows({ page: 'current' }).every(function (rowIdx, tableLoop, rowLoop) {
            //         var data = this.data();
            //         var node = this.node();
            //         var so = data.sales_order_no;
            
            //         if (!seen[so]) {
            //             seen[so] = {
            //                 count: 0,
            //                 rows: [],
            //                 helperText: 0,
            //                 workerText: 0,
            //                 manpowerText: 0
            //             };
            //         }
            
            //         seen[so].count++;  // Moved this outside the if-block ✅
            //         seen[so].rows.push(node);
            
            //         // Accumulate totals (can be changed to sum if needed)
            //         seen[so].helperText = parseFloat(data.total_helpers || 0);
            //         seen[so].workerText = parseFloat(data.total_workers || 0);
            //         seen[so].manpowerText = parseFloat(data.total_manpower || 0);
            //     });
            
            //     $.each(seen, function (so, group) {
            //         if (group.count > 1) {
            //             for (var i = 0; i < group.rows.length; i++) {
            //                 var $row = $(group.rows[i]);
                          
            //                 var helperCell = $row.find('.h_' + so);
            //                 var workerCell = $row.find('.w_' + so);
            //                 var manpowerCell = $row.find('.m_' + so); 
            //                 if (i === 0) {
            //                     helperCell.attr('rowspan', helperCell.length).text(group.helperText).addClass('center-cell');
            //                     workerCell.attr('rowspan', helperCell.length).text(group.workerText).addClass('center-cell');
            //                     manpowerCell.attr('rowspan', helperCell.length).text(group.manpowerText).addClass('center-cell');
            //                 } else {
            //                     helperCell.remove();
            //                     workerCell.remove();
            //                     manpowerCell.remove();
            //                 }
            //             }
            //         } 
            //     });
            // },
            columns: [
                    { data: null, name: 'srno' },
                    { data: 'sti_code', name: 'sti_code' },
                    { data: 'sti_date', name: 'sti_date' },
                    { data: 'sales_order_no', name: 'sales_order_no' },
                    {data: 'job_status_name', name: 'job_status_name'},
                    { data: 'buyer_name', name: 'buyer_name' },
                    { data: 'brand_name', name: 'brand_name' },
                    { data: 'sam', name: 'sam' },
                    { data: 'vw_code', name: 'vw_code' },
                    { data: 'vendor_name', name: 'vendor_name' },
                    { data: 'mainstyle_name', name: 'mainstyle_name' },
                    { data: 'style_no', name: 'style_no' },
                    { data: 'color_name', name: 'color_name' },
                    { data: 'line_no', name: 'line_no' },
                    { data: 'size_name', name: 'size_name' },
                    { data: 'size_qty', name: 'size_qty' },
                    { data: 'Minutes', name: 'Minutes' }, 
                    {
                        data: 'total_helpers',
                        name: 'total_helpers',
                        createdCell: function(td, cellData, rowData) {
                            $(td).addClass('h_' + rowData.sti_code);
                        }
                    },
                    {
                        data: 'total_workers',
                        name: 'total_workers',
                        createdCell: function(td, cellData, rowData) {
                            $(td).addClass('w_' + rowData.sti_code);
                        }
                    },
                    {
                        data: 'total_manpower',
                        name: 'total_manpower',
                        createdCell: function(td, cellData, rowData) {
                            $(td).addClass('m_' + rowData.sti_code);
                        }
                    }, 
            ]
        });
        setTimeout(function()
        {
         SetCustomisedData();
        }, 2000);
    }
    
    // function SetCustomisedData()
    // {
    //     let uniqueClasses = new Set();
    
    //     // Step 1: Collect all unique h_KDPL-* class names from every td
    //     $('#dt > tbody > tr > td').each(function () {
    //         let classes = $(this).attr('class');
    //         if (!classes) return;
    
    //         classes.split(/\s+/).forEach(cls => {
    //             if (cls.startsWith('h_STI-')) {
    //                 uniqueClasses.add(cls);
    //             }
    //         });
    //     });
    
    //     let classList = Array.from(uniqueClasses);
    
    //     // Step 2: For each class, get the first occurrence's text and apply it to all cells with that class
    //     classList.forEach(cls => {
    //         let $cells = $('#dt > tbody > tr > td.' + cls);
    //         let firstText = $cells.first().text().trim();
    
    //         $cells.text(0);  
    //         $cells.first().text(firstText);
    //     });
    // }
    
    function SetCustomisedData()
    {
        let prefixes = ['h_STI-', 'w_STI-', 'm_STI-'];
        let uniqueClasses = new Set();
    
        // Step 1: Collect all unique class names that match the prefixes
        $('#dt > tbody > tr > td').each(function () {
            let classes = $(this).attr('class');
            if (!classes) return;
    
            classes.split(/\s+/).forEach(cls => {
                prefixes.forEach(prefix => {
                    if (cls.startsWith(prefix)) {
                        uniqueClasses.add(cls);
                    }
                });
            });
        });
    
        let classList = Array.from(uniqueClasses);
    
        // Step 2: For each class, get the first occurrence's text and apply it to all cells with that class
        classList.forEach(cls => {
            let $cells = $('#dt > tbody > tr > td.' + cls);
            let firstText = $cells.first().text().trim();
    
            $cells.text(0);  // Clear all cells
            $cells.first().text(firstText);  // Set text only in the first cell
        });
    }

    
    function custum(ele)
    {    
        var temp = $("#h_total_operator").val();
        var td_val = ele;
        if(td_val != temp)
        {
            $("#h_total_operator").val(td_val);
            return ele = td_val;
        }
        else
        {
            return ele = 0;
        }
        temp = td_val;
        $("#h_total_operator").val("");
    }
</script>
@endsection