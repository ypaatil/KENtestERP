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
    .text-nowrap {
      white-space: nowrap;
    }

</style> 
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18" style="font-size:20px;">Costing</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Costing</li>
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
@if($chekform->write_access==1)    
   <div class="col-md-4">
      <a href="{{ Route('SalesOrderCosting.create') }}"><button type="button" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
@else
   <div class="col-md-4"> 
   </div>
@endif

    <div class="col-lg-4 text-center">
       <h4><b>Note : </b> Showing last 3 month records. If you want to all click on <a href="javascript:void(0);"  onclick="showAll();">Show All Data</a> button</h4>
    </div>
    <div class="col-lg-4 text-right">
        <button type="button" class="btn btn-warning w-md float-right" onclick="showAll();">Show All Data</button> &nbsp; &nbsp; &nbsp;
        <button type="button" class="btn btn-danger w-md float-right" onclick="back();">Back</button> &nbsp; &nbsp; &nbsp;
    </div>
</div>
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
            <table  data-page-length='25' id="sales_consting_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center;vertical-align: middle;">
                     <th nowrap>Sr No</th>
                     <th nowrap>Costing No</th>
                     <th nowrap>Order No</th> 
                     <th nowrap>Entry Date</th>
                     <th nowrap>Buyer Name</th>
                     <th>Brand</th> 
                     <th>Style</th> 
                     <th nowrap>Rate (INR)</th> 
                     <th>BD Appr.</th> 
                     <th>CEO Appr.</th> 
                     <th nowrap>User Name</th>
                     <th nowrap>Last Updated</th>
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
        var url = 'SalesOrderCosting?page=0';
        TableData(url);
    });
  
    function showAll()
    {
        var url = 'SalesOrderCosting?page=1';
        TableData(url);
    }
    
    function back()
    {
        var url = 'SalesOrderCosting?page=0';
        TableData(url);
    }

  
// ✅ Custom SOC code sorting (e.g. SOC-996 → numeric 996)
$.extend($.fn.dataTable.ext.type.order, {
    "soccode-pre": function (data) {
        if (!data) return 0;
        let num = data.toString().replace(/[^0-9]/g, ''); // Extract only numbers
        return parseInt(num, 10) || 0;
    }
});

// ✅ DataTable initialization
function TableData(url) {
    // Destroy existing table if already initialized
    if ($.fn.DataTable.isDataTable('#sales_consting_table')) {
        $('#sales_consting_table').DataTable().destroy();
    }

    // Initialize new DataTable
    let table = $('#sales_consting_table').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: url,
            dataSrc: function (json) {
                // ✅ Sort entire dataset before DataTable renders
                json.data.sort(function (a, b) {
                    // Extract numeric part from SOC code
                    let numA = parseInt((a.soc_code || '').replace(/[^0-9]/g, '')) || 0;
                    let numB = parseInt((b.soc_code || '').replace(/[^0-9]/g, '')) || 0;
                    return numB - numA; // DESC
                });
                return json.data;
            }
        },
        bDestroy: true,

        dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],

        // ✅ No need to sort again here; data already sorted
        order: [], 
        pageLength: 25,
        responsive: true,

        columns: [
            { data: null, name: 'serial_no', orderable: false, searchable: false },
            { data: 'soc_code', name: 'soc_code', className: 'text-nowrap', type: 'soccode' },
            { data: 'sales_order_no', name: 'sales_order_no' },
            { data: 'entry_date', name: 'entry_date' },
            { data: 'ac_short_name', name: 'ac_short_name' },
            { data: 'brand_name', name: 'brand_name' },
            { data: 'mainstyle_name', name: 'mainstyle_name' },
            { data: 'order_rate', name: 'order_rate', className: 'text-right' },
            { data: 'isMarketing', name: 'isMarketing' },
            { data: 'isCEO', name: 'isCEO' },
            { data: 'username', name: 'username' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'action1', name: 'action1', orderable: false, searchable: false },
            { data: 'action2', name: 'action2', orderable: false, searchable: false },
            { data: 'action3', name: 'action3', orderable: false, searchable: false },
            { data: 'action4', name: 'action4', orderable: false, searchable: false }
        ],

        // ✅ Auto-generate serial numbers
        rowCallback: function (row, data, displayIndex) {
            let api = this.api();
            let pageInfo = api.page.info();
            let serialNo = pageInfo.start + displayIndex + 1;
            $('td:eq(0)', row).html(serialNo);
        }
    });
}


</script>  
@endsection