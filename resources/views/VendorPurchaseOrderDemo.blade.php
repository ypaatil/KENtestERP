 @extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.3.0/css/scroller.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">  
 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<style>
    .hide{
        display:none;
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

  
</style> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body"> 
         <div class="row">
              <div class="col-md-4 hide">  
                    @php
                        $syncDetail = DB::table('syncronization_time_mgmt')->select('*')->where('stmt_type','=',1)->first();
                    
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
              <div class="col-md-4 mt-1"> </div>
              <div class="col-md-4 mt-1"> 
                    <button class="btn btn-secondary success-main-checkmark" id="All" onclick="filters(0,this);" style="width: 120px;">All</button>
                    <button class="btn btn-warning" id="Moving" onclick="filters(1,this);" style="width: 120px;">Moving</button>
                    <button class="btn btn-info" id="Non_Moving" onclick="filters(2,this);" style="width: 120px;">Non Moving</button>
              </div>
              <div class="col-md-6">
                  <div id="waiting" class="hide"  style="font-weight: bolder;">Please Wait......Process started time is <span id="counter"></span>.</div> 
              </div>
         </div>
      </div>
   </div>
</div> 
<div class="row">
    <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Total Stock Qty(Meter In Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="total_Stock_qty">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary" >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
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
                  <p class="  fw-medium" style="color:#fff;">Value(In Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" id="all_value">0</h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
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
              <form action="/VendorPurchaseOrderDemo" method="GET">
                  <div class="row">
                      <div class="col-md-6">
                            <label><b style="color:#fff!important">Stock as On</b></label>
                            <input type="date" name="currentDate" id="currentDate"  value="{{$currentDate}}" class="form-control"> 
                      </div>
                      <div class="col-md-6 mt-4"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/VendorPurchaseOrderDemo" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div> 
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-12 hide text-center"  id="waiting1"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>
            <div class="table-responsive hide" >
               <table id="dt" class="table table-bordered  nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Suplier Name</th>
                        <th>Buyer Name</th>
                        <th>Status</th> 
                        <th>PO No</th>
                        <th>GRN No</th>
                        <th>Invoice No</th>
                        <th>Item Code</th>
                        <th>Preview</th>
                        <th>Shade No.</th>
                        <th>Item Name</th>
                        <th>Width</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Track Code</th> 
                        <th>Rack Name</th> 
                        <th>GRN Qty</th>
                        <th>QC Qty</th>
                        <th>Outward Qty</th>
                        <th>Stock Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!--<script src="{{ URL::asset('assets/libs/performant-huge-data-grid/js/huge-grid.js')}}"></script>-->
<!--<script src="https://code.jquery.com/jquery-1.12.3.js"></script>-->
<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>
<script>
   
    function filters(value,row)
    {
        
        $("button").removeClass('success-main-checkmark');
        $(row).addClass('success-main-checkmark');

        LoadFabricStockDataTrialCloned(value);
    }
    
    LoadFabricStockDataTrialCloned(0);
    
    function LoadFabricStockDataTrialCloned(job_status_id)
    {       
      
        $('#dt').DataTable().clear().destroy();  
        var currentDate = $("#currentDate").val();
            
        // $('#dt').DataTable({
        //   // ... skipped other options ...
        //   ajax: {
        //       url: URL,
        //       dataSrc: 'html'
        //   },
        //   columns: [
        //       { data: "suplier_name"}, 
        //       { data: "buyer_name"}, 
        //       { data: "po_no" }, 
        //       { data: "grn_no"},
        //       { data: "invoice_no"},
        //       { data: "item_code"},
        //       { data: "preview"},
        //       { data: "shade_no"},
        //       { data: "item_name"},
        //       { data: "width"},
        //       { data: "quality_name"},
        //       { data: "color"},
        //       { data: "item_description"},
        //       { data: "track_name"},
        //       { data: "rack_name"},
        //       { data: "gq"},
        //       { data: "qc_qty"},
        //       { data: "q_qty"},
        //       { data: "stocks"},
        //       { data: "rate"},
        //       { data: "value"},
        //   ]
        // });
                
        var URL = "LoadFabricStockDataTrialCloned1?currentDate="+currentDate+"&job_status_id="+job_status_id;    
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
                
                 var myArray = JSON.parse(data.html); 
                 $('#dt').DataTable({
                    data: myArray,
                    "dom": 'lBrtip',
                    "pageLength": 10, 
                    "buttons": ['csv','print', 'excel', 'pdf'],
                    columns: [
                       { data: "suplier_name"}, 
                       { data: "buyer_name"},  
                       { data: "status"}, 
                       { data: "po_no" }, 
                       { data: "grn_no"},
                       { data: "invoice_no"},
                       { data: "item_code"},
                       { data: "preview"},
                       { data: "shade_no"},
                       { data: "item_name"},
                       { data: "width"},
                       { data: "quality_name"},
                       { data: "color"},
                       { data: "item_description"},
                       { data: "track_name"},
                       { data: "rack_name"},
                       { data: "gq"},
                       { data: "qc_qty"},
                       { data: "q_qty"},
                       { data: "stocks"},
                       { data: "rate"},
                       { data: "value"},
                    ]
                });
        
                $('#total_Stock_qty').html(data.total_stock);
                $('#all_value').html(data.total_value);
                // $('#currentDate').val(data.currentDate);
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
    $( document ).ready(function() 
    {
            // new DataTable('#dt', {
            //     ajax: function (data, callback, settings) {
                   
             
            //         setTimeout(() => {
            //             callback({
            //                 draw: data.draw, 
            //             });
            //         }, 150);
            //     }, 
            //     columnDefs: [{
            //         "defaultContent": "-",
            //         "targets": "_all"
            //       }],
            //     processing: true,
            //     ordering: false,
            //     scroller: true,
            //     scrollY: 200,
            //     searching: false,
            //     serverSide: true
            // });
    });
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
                LoadFabricStockDataTrialCloned();
            }
        });
    }

 
    function DumpData()
    {
             
       $.ajax({
            dataType: "json",
            type: "GET", 
            url: "{{ route('RunCronJob') }}",
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
                LoadFabricStockDataTrialCloned();
                // UpdateFoutDumpData();
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
                 LoadFabricStockDataTrialCloned();
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