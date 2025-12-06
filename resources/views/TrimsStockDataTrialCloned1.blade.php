@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
   .switch {
        position: relative;
        display: inline-block;
        width: 150px; /* Adjust as needed */
        height: 34px; /* Adjust as needed */
    }
    
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s; /* Vendor prefixes not needed if not targeting older browsers */
    }
    
    .slider:before {
        content: "";
        position: absolute;
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s; /* Vendor prefixes not needed if not targeting older browsers */
    }
    
    .slider::after {
        content: attr(data-state);
        color: white;
        font-size: 12px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: block;
        text-align: center;
        pointer-events: none;
    }
    
    input:checked + .slider {
        background-color: #2196F3;
    }
    
    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }
    
    input:checked + .slider:before {
        transform: translateX(116px); /* Adjust value as needed */
    }
    
    input:checked + .slider::after {
        content: attr(data-state);
        left: 45%; /* Adjust as needed */
    }
    
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    
    .slider.round:before {
        border-radius: 50%;
    }
 
    .success-main-checkmark {
        position: relative; /* Ensure the button is a positioned parent */
    }
    
    .success-main-checkmark:after {
        content: '✔';
        position: absolute;
        top: 50%; /* Move the top of the pseudo-element to the middle of the button */
        left: 90%; /* Move the left of the pseudo-element to the middle of the button */
        transform: translate(-50%, -50%); /* Center the pseudo-element */
        width: 27px;
        height: 26px;
        text-align: center;
        border: 1px solid #aaa;
        background: #0b0b0b;
        border-radius: 50%;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, .3);
    }
    .text-right
    {
          text-align: right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body"> 
         <div class="row"> 
              <div class="col-md-4 hide">  
                    @php
                        $syncDetail = DB::table('syncronization_time_mgmt')->select('*')->where('stmt_type','=',2)->first();
                    
                        if($syncDetail->end_time == "")
                        {
                            $sync_time = $syncDetail->start_time;
                        }
                        else
                        {
                            $sync_time = $syncDetail->end_time;
                        }
                        
                        $sync_time1 =  date('h:i A', strtotime($sync_time));
                        
                        if($syncDetail->status == 0 && $syncDetail->end_time === "")
                        { 
                    @endphp 
                            <button class="btn btn-warning" id="sync" disabled >Synchronization (<b style='color:red'>In Progress </b>)</button>
                            <img src="{{ URL::asset('images/loading-waiting.gif')}}" width="50" height="50"><br/>
                            <span id="last_updated_time" style="color:green;">Last Synchronization : {{$sync_time1}}</span>
                               <a href=""><button class="btn btn-warning" id="sync" onclick="DumpData();" >Synchronization(<b style='color:green'>Completed </b>)</button></a><br/>
                    @php
                        }
                        else
                        {
                    @endphp
                            <a href=""><button class="btn btn-warning" id="sync" onclick="DumpData();" >Synchronization(<b style='color:green'>Completed </b>)</button></a><br/>
                            <span id="last_updated_time" style="color:green;">Last Synchronization : {{$sync_time1}}</span>
                    @php
                        }
                    @endphp
              </div> 
              <div class="col-md-4"></div> 
              <div class="col-md-4 mt-1"> 
                    <button class="btn btn-secondary success-main-checkmark" id="All" onclick="filters(0,this);" style="width: 120px;">All</button>
                    <button class="btn btn-warning" id="Moving" onclick="filters(1,this);" style="width: 120px;">Moving</button>
                    <button class="btn btn-info" id="Non_Moving" onclick="filters(2,this);" style="width: 120px;">Non Moving</button>
              </div> 
         </div>
          <div class="col-md-2 mt-4">  
                <button class="btn btn-info hide" onclick="UpdateFoutDumpData()" >Refresh</button>
          </div> 
          <div class="col-md-6 mt-4 ml-3 hide" style="margin-left:15px;">  
                <button class="btn btn-secondary" onclick="RefreshDumpData()" >Refresh Dump Data</button>
          </div> 
      </div>
   </div>
</div>
<div class="row">
<div class="col-md-3">
   <div class="card mini-stats-wid" style="background-color:#152d9f;" >
      <div class="card-body">
         <div class="d-flex">
            <div class="flex-grow-1">
               <p class="  fw-medium" style="color:#fff;">Total Stock(In Lakh)</p>
               <h4 class="mb-0" style="color:#fff;" id="head_total_stock_qty">0</h4>
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
<div class="col-md-6">
   <div class="card mini-stats-wid" style="background-color:#152d9f;" >
      <div class="card-body">
          <form action="/TrimsStockDataTrialCloned1" method="GET">
              <div class="row">
                  <div class="col-md-6">
                        <label><b style="color:#fff!important">Stock as On</b></label>
                        <input type="date" name="currentDate" value="{{$currentDate}}" class="form-control"> 
                  </div>
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/TrimsStockDataTrialCloned1" class="btn btn-warning">Clear</a>
                  </div>
              </div>
          </form>
      </div>
   </div>
</div>
  <div class="hide" id="head_total_value">0</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-12 hide text-center" id="waiting1"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>
            <div class="table-responsive hide">
               <table id="dt" class="table table-bordered nowrap w-100">
                  <thead> 
                     <tr style="text-align:center;">
                        <th>Action <span class="filter-icon hide">🔽</span><div class="filter-menu action"></div></th>
                        <th>Supplier Name <span class="filter-icon">🔽</span><div class="filter-menu supplier-name"></div></th>
                        <th>Bill To <span class="filter-icon">🔽</span><div class="filter-menu bill-to"></div></th>
                        <th>Buyer Name <span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                        <th>PO Status <span class="filter-icon">🔽</span><div class="filter-menu po-status"></div></th>
                        <th>Close Date <span class="filter-icon">🔽</span><div class="filter-menu close-date"></div></th>
                        <th>PO No <span class="filter-icon">🔽</span><div class="filter-menu po-no"></div></th>
                        <th>PO Type <span class="filter-icon">🔽</span><div class="filter-menu po-type"></div></th>
                        <th>Item Code <span class="filter-icon">🔽</span><div class="filter-menu item-code"></div></th>
                        <th>Item Name <span class="filter-icon">🔽</span><div class="filter-menu item-name"></div></th>
                        <th>Width <span class="filter-icon">🔽</span><div class="filter-menu width"></div></th>
                        <th>Color <span class="filter-icon">🔽</span><div class="filter-menu color"></div></th>
                        <th>Item Description <span class="filter-icon">🔽</span><div class="filter-menu item-description"></div></th>
                        <th>GRN Qty <span class="filter-icon">🔽</span><div class="filter-menu grn-qty"></div></th>
                        <th>Outward Qty <span class="filter-icon">🔽</span><div class="filter-menu outward-qty"></div></th>
                        <th>Stock Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-qty"></div></th>
                        <th>Rate <span class="filter-icon">🔽</span><div class="filter-menu rate"></div></th>
                        <th>Value <span class="filter-icon">🔽</span><div class="filter-menu value"></div></th>

                        <th class="text-center">0-30 Days Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-0-30"></div></th>
                        <th class="text-center">0-30 Days Value <span class="filter-icon">🔽</span><div class="filter-menu value-0-30"></div></th>
                        <th class="text-center">31-60 Days Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-31-60"></div></th>
                        <th class="text-center">31-60 Days Value <span class="filter-icon">🔽</span><div class="filter-menu value-31-60"></div></th>
                        <th class="text-center">61-90 Days Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-61-90"></div></th>
                        <th class="text-center">61-90 Days Value <span class="filter-icon">🔽</span><div class="filter-menu value-61-90"></div></th>
                        <th class="text-center">91-180 Days Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-91-180"></div></th>
                        <th class="text-center">91-180 Days Value <span class="filter-icon">🔽</span><div class="filter-menu value-91-180"></div></th>
                        <th class="text-center">180+ Days Qty <span class="filter-icon">🔽</span><div class="filter-menu stock-180-plus"></div></th>
                        <th class="text-center">180+ Days Value <span class="filter-icon">🔽</span><div class="filter-menu value-180-plus"></div></th>
                     </tr>
                  </thead>
                  <tbody>  
                </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://code.jquery.com/jquery-1.12.3.js"></script> 

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    function getQueryParam(name) 
    {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    
    function filters(value,row)
    {
        $("button").removeClass('success-main-checkmark');
        $(row).addClass('success-main-checkmark');
        LoadTrimsStockDataTrialCloned1(value);
    }
     
    function updateSliderState(checkbox) 
    {
        var slider = checkbox.nextElementSibling;
        var po_no = $(checkbox).attr("po_no");
        var item_code = $(checkbox).attr("item_code");
        var stateValue;
    
        if (checkbox.checked) {
            stateValue = "Moving";
            slider.dataset.state = stateValue;
            // Assuming you want to update the status immediately when checked
            var job_status_id = 1;
            // $.ajax({
            //     dataType: "json",
            //     type: "GET",
            //     url: "{{ route('UpdatePurchaseOrderStatus') }}",
            //     data: { "po_no": po_no, "item_code": item_code, "job_status_id": job_status_id },
            //     success: function(data) {
            //         var today = new Date();
            //         var day = ("0" + today.getDate()).slice(-2);
            //         var month = ("0" + (today.getMonth() + 1)).slice(-2);
            //         var year = today.getFullYear();
            //         var formattedDate = year + '-' + month + '-' + day;
    
            //         $(checkbox).closest('tr').find('td:eq(3)').text(stateValue);
            //         $(checkbox).closest('tr').find('td:eq(4)').text(formattedDate);
            //         console.log("Updated");
            //     }
            // });
        } else {
            Swal.fire({
                title: 'Are you sure?',
                text: "This process cannot be reversed...!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Update state and UI elements only after the confirmation alert is closed
                    stateValue = "Non Moving";
                    slider.dataset.state = stateValue;
                    $(checkbox).attr('disabled', true);
                    var job_status_id = 2; // This is just a variable; use it as needed
    
                    // AJAX request to update the purchase order status
                    $.ajax({
                        dataType: "json",
                        type: "GET",
                        url: "{{ route('UpdatePurchaseOrderStatus') }}",
                        data: { "po_no": po_no, "item_code": item_code, "job_status_id": job_status_id },
                        success: function(data) {
                            var today = new Date();
                            var day = ("0" + today.getDate()).slice(-2);
                            var month = ("0" + (today.getMonth() + 1)).slice(-2);
                            var year = today.getFullYear();
                            var formattedDate = year + '-' + month + '-' + day;
    
                            $(checkbox).closest('tr').find('td:eq(3)').text(stateValue);
                            $(checkbox).closest('tr').find('td:eq(4)').text(formattedDate);
                            console.log("Updated");
                        }
                    });
    
                    Swal.fire(
                        'Transferred!',
                        'Transferred to Non Moving',
                        'success'
                    );
                } else { 
                    checkbox.checked = true;
                }
            });
        }
    }

    $( document ).ready(function() 
    {      
    var job_status_id = getQueryParam('job_status_id');
    
    if(job_status_id == 1)
    {
        $("#Moving").trigger('click');
    }
    else if(job_status_id == 2)
    {
        $("#Non_Moving").trigger('click');
    }
    else
    {
        $("#All").trigger('click');
    }
    sessionStorage.setItem('btnclickforgetalldata', 0); 
    }); 
    
    function LoadTrimsStockDataTrialCloned1(job_status_id)
    {       
        sessionStorage.setItem('btnclickforgetalldata', 1);    
        removeFilterColor();
        $('#dt').DataTable().clear().destroy();  
        var currentDate = getSearchParams("currentDate");

        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Trims Stock Aging Report (' + formattedDate + ')'; 
       
        var URL = "LoadTrimsStockDataTrialCloned2?currentDate="+currentDate+"&job_status_id="+job_status_id;  
        $.ajax({
            dataType: "json",
            type: "GET", 
            url: URL, 
            beforeSend: function() 
            {
                $("#waiting1").removeClass("hide");
                $(".table-responsive").addClass("hide");
            },
            success: function(data)
            { 
                if ( $.fn.DataTable.isDataTable('#dt')) 
                {
                   $('#dt').DataTable().destroy();
                }

                var myArray = JSON.parse(data.html); 
                $('#dt').DataTable({
                    "dom": 'lBfrtip', // 'f' added for the search box
                    "pageLength": 10,
                     initComplete: function () {
                    buildAllMenusTrimsStockDataTrialClonedReport();
                    sessionStorage.setItem('btnclickforgetalldata', 0); 
                    },
                    buttons: [
                            { extend: 'copyHtml5', footer: true, title: exportTitle , exportOptions: commonExportOptions() 
                            },
                            { extend: 'excelHtml5', footer: true, title: exportTitle , exportOptions: commonExportOptions()  
                            },
                            { extend: 'csvHtml5', footer: true, title: exportTitle , exportOptions: commonExportOptions() 
                            },
                            { extend: 'pdfHtml5', footer: true, title: exportTitle , exportOptions: commonExportOptions()  }
                    ], 
                    data: myArray,
                    columns: [
                        { data: "Action" },
                        { data: "suplier_name" },
                        { data: "bill_to" },
                        { data: "buyer_name" },
                        { data: "po_status" },
                        { data: "closeDate" },
                        { data: "po_no" },
                        { data: "po_type_name" },
                        { data: "item_code" },
                        { data: "item_name" },
                        { data: "width" },
                        { data: "color" },
                        { data: "item_description" },
                        { data: "gq", className: "text-right" },
                        { data: "q_qty", className: "text-right" },
                        { data: "stocks", className: "text-right" },
                        { data: "rate", className: "text-right" },
                        { data: "value", className: "text-right" },
                        { data: "stock_0_30", className: "text-right" },
                        { data: "value_0_30", className: "text-right" },
                        { data: "stock_31_60", className: "text-right" },
                        { data: "value_31_60", className: "text-right" },
                        { data: "stock_61_90", className: "text-right" },
                        { data: "value_61_90", className: "text-right" },
                        { data: "stock_91_180", className: "text-right" },
                        { data: "value_91_180", className: "text-right" },
                        { data: "stock_180_plus", className: "text-right" },
                        { data: "value_180_plus", className: "text-right" }
                    ]
                });

                $('#head_total_stock_qty').html(data.total_value);
                $('#all_value').html(data.total_value);
                $('#currentDate').val(data.currentDate);
            },
            complete: function (data) 
            {
                  $("#waiting1").addClass("hide");
                  $(".table-responsive").removeClass("hide");
            //       var table = $('#dt').DataTable({
            //         pageLength: 10,
            //         paging: true,
            //         searching: true,
            //         // responsive: true,
            //         dom: 'lBfrtip',
            //         buttons: [
            //             { extend: 'copyHtml5', footer: true },
            //             { extend: 'excelHtml5', footer: true },
            //             { extend: 'csvHtml5', footer: true },
            //             { extend: 'pdfHtml5', footer: true }
            //         ],
            //   });
            }
        });
    } 
    
    function getSearchParams(k)
    {
         var p={};
         location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
         return k?p[k]:p;
    }
    
    function UpdateFoutDumpData() 
    {
        $.ajax({
            dataType: "json",
            type: "GET", 
            url: "{{ route('UpdateFoutDumpData') }}",
            success: function(data)
            {
                LoadTrimsStockDataTrialCloned1();
            }
        });
    }


                 // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

        if (menu.hasClass('action')) applySimpleFilter(0, menu);
        else if (menu.hasClass('supplier-name')) applySimpleFilter(1, menu);
        else if (menu.hasClass('bill-to')) applySimpleFilter(2, menu);
        else if (menu.hasClass('buyer-name')) applySimpleFilter(3, menu);
        else if (menu.hasClass('po-status')) applySimpleFilter(4, menu);
        else if (menu.hasClass('close-date')) applySimpleFilter(5, menu);
        else if (menu.hasClass('po-no')) applySimpleFilter(6, menu);
        else if (menu.hasClass('po-type')) applySimpleFilter(7, menu);
        else if (menu.hasClass('item-code')) applySimpleFilter(8, menu);
        else if (menu.hasClass('item-name')) applySimpleFilter(9, menu);
        else if (menu.hasClass('width')) applySimpleFilter(10, menu);
        else if (menu.hasClass('color')) applySimpleFilter(11, menu);
        else if (menu.hasClass('item-description')) applySimpleFilter(12, menu);
        else if (menu.hasClass('grn-qty')) applySimpleFilter(13, menu);
        else if (menu.hasClass('outward-qty')) applySimpleFilter(14, menu);
        else if (menu.hasClass('stock-qty')) applySimpleFilter(15, menu);
        else if (menu.hasClass('rate')) applySimpleFilter(16, menu);
        else if (menu.hasClass('value')) applySimpleFilter(17, menu);
        else if (menu.hasClass('stock-0-30')) applySimpleFilter(18, menu);
        else if (menu.hasClass('value-0-30')) applySimpleFilter(19, menu);
        else if (menu.hasClass('stock-31-60')) applySimpleFilter(20, menu);
        else if (menu.hasClass('value-31-60')) applySimpleFilter(21, menu);
        else if (menu.hasClass('stock-61-90')) applySimpleFilter(22, menu);
        else if (menu.hasClass('value-61-90')) applySimpleFilter(23, menu);
        else if (menu.hasClass('stock-91-180')) applySimpleFilter(24, menu);
        else if (menu.hasClass('value-91-180')) applySimpleFilter(25, menu);
        else if (menu.hasClass('stock-180-plus')) applySimpleFilter(26, menu);
        else if (menu.hasClass('value-180-plus')) applySimpleFilter(27, menu);


         $('.filter-menu').hide();         
         buildAllMenusTrimsStockDataTrialClonedReport();       
         });
         // End script for filter search and apply  

 
    function DumpData()
    {
             
       $.ajax({
            dataType: "json",
            type: "GET", 
            url: "{{ route('SyncTrimsStock') }}",
            beforeSend: function() 
            {
                $('#waiting').removeClass('hide');
                $("#sync").attr('disabled','disabled');        
                var percentage = 0;
           
                var timer = setInterval(function(){
                     percentage = percentage + 20;
                     //progress_bar_process(percentage, timer);
                }, 1000);
                    
                var counter = 0;
                var timer = 0;
                setInterval(function () {
                    var t = '';
                    if(counter < 60)
                    {
                       t = 'Seconds';
                       $('#counter').html(counter+' '+t);
                       if(timer > 0)
                       {
                            $('#counter').html(timer+' Minutes '+counter+' '+t);
                       }
                    }
                    else
                    {
                       t = 'Minutes';
                       timer++;
                       $('#counter').html(timer+' '+t);
                       counter = 0;
                    }
                    ++counter;
                }, 1000);
            },
            success: function(data)
            {
                //UpdateFoutDumpData(); 
                
            },
            complete: function(data)
            {
                $("#sync").removeAttr('disabled');
                setTimeout(function() 
                { 
                    $(".alert-success").addClass('hide'); 
                    
                }, 2500);
                
                
                  $('#waiting').addClass('hide');
                  var d = new Date();
                  var Hours = "";
                  var Minutes = "";
                  var Seconds = "";
                  
                  if(d.getHours() < 10 )
                  {
                       Hours = "0"+d.getHours();
                  }
                  else
                  {
                        Hours = d.getHours();
                  }
                  if(d.getMinutes() < 10)
                  {
                       Minutes = "0"+d.getHours();
                  }
                  else
                  {
                       Minutes = d.getMinutes();
                  }
                  
                  if(d.getSeconds() < 10)
                  {
                       Seconds = "0"+d.getSeconds();
                  }
                  else
                  {
                       Seconds = d.getSeconds();
                  }
                  
                  var time = Hours + ":"+ Minutes + ":" + Seconds;
               
                  const timeString = time;
                  const timeString12hr = new Date('1970-01-01T' + timeString + 'Z').toLocaleTimeString('en-US',
                      {
                          timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'
                      }
                    );
       
                 $('#last_updated_time').html("Last Synchronization : "+timeString12hr);
                 
                var counter = 0;
                var timer = 0;
               // UpdateFoutDumpData(); 
            },
            error: function (error) 
            {
            }
        });
    }
    
    function RefreshDumpData()  
    {
             
       $.ajax({
            dataType: "json",
            type: "GET", 
            url: "{{ route('RefreshDumpData') }}",
            success: function(data)
            {
                 LoadTrimsStockDataTrialCloned1();
            }
        });
    }
    $('#head_total_grn_qty').html($('#total_grn_qty').val());
    $('#head_total_qc_qty').html($('#total_qc_qty').val());
    $('#head_total_outward_qty').html($('#total_outward_qty').val());
    $('#head_total_stock_qty').html($('#total_stock_qty').val());
    $('#head_total_value').html($('#total_value').val());
     
    $("#total_outward_qty").html($('#total_outward_qty').val());
    $("#total_Stock_qty").html($('#total_stock_qty').val());
    $("#all_value").html($('#total_value').val());
    
    
</script>
@endsection