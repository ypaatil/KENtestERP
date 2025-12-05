@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp  
<!-- end page title -->
<style>
    #lblSync
    {
        background: #a8e94269;
        padding: 10px;
        font-weight: 900;
        background-position: left top;
        padding-top:95px;
        margin-bottom:60px;
        -webkit-animation-duration: 10s;animation-duration: 10s;
        -webkit-animation-fill-mode: both;animation-fill-mode: both;
    }
    .hide
    {
        display:none;
    }
    
    .success-main-checkmark
    {
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
        text-align:right;
    }
    
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">FG Stock Report - Date Wise</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">FG Stock Report - Date Wise</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body"> 
                    <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4 hide">  
                                @php
                                    $syncDetail = DB::table('syncronization_time_mgmt')->select('*')->where('stmt_type','=',3)->first();
                                
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
                                        <a href=""><button class="btn btn-warning" id="sync" onclick="syncData();" >Synchronization(<b style='color:green'>Completed </b>)</button></a><br/>
                                        <span id="last_updated_time" style="color:green;">Last Synchronization : {{$sync_time1}}</span>
                                @php
                                    }
                                @endphp
                          </div> 
                            <div class="col-md-4 hide">
                              <button class="btn btn-warning"  onclick="DumpData();" >Temp Table</button><br/>
                              <div id="waiting" class="hide"  style="font-weight: bolder;">Please Wait......Process started time is <span id="counter"></span>.</div> 
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-secondary success-main-checkmark" id="All" onclick="filters(0,this);" style="width: 120px;">All</button>
                                <button class="btn btn-warning" id="Moving" onclick="filters(1,this);" style="width: 120px;">Moving</button>
                                <button class="btn btn-info" id="Non_Moving" onclick="filters(2,this);" style="width: 120px;">Non Moving</button>
                            </div>
                        </div>
                    </div><br/> 
                <div class="row">
                    <div class="col-md-3">
                       <div class="card mini-stats-wid">
                          <div class="card-body" style="background: #40e0d096;">  
                              <div class="col-md-12" id="totalFGStock"><b>Total Stock(In Lakh): {{$total_stock1}}</b></div> 
                          </div>
                       </div>
                    </div>
                    <div class="col-md-3">
                       <div class="card mini-stats-wid">
                          <div class="card-body" style="background: #9acd328f;">  
                              <div class="col-md-12" id="totalFGValue"><b>Total Value(In Lakh): {{$total_value1}}</b></div> 
                          </div>
                       </div>
                    </div>  
                </div>
                <div class="alert alert-success hide">
                    Data Synchronization Completed
                </div> 
                <div class="row">
                   <div class="col-md-12">
                      <div class="card">
                         <div class="card-body">  
                           <div class="row">
                               <div class="col-md-3">
                                 <div class="mb-3">
                                    <label for="fromDate" class="form-label">Date</label>
                                    <input type="date" name="currentDate" id="currentDate" value="{{$currentDate}}" class="form-control"> 
                                 </div>
                               </div> 
                               <div class="col-md-3 hide">
                                 <div class="mb-3">
                                    <label for="ac_code" class="form-label">Buyer Name</label>
                                    <select name="ac_code" id="ac_code" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($buyerList as $row)
                                            <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div> 
                               <div class="col-md-3 hide">
                                 <div class="mb-3">
                                    <label for="job_status_id" class="form-label">Job Status Name</label>
                                    <select name="job_status_id" id="job_status_id" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($jobStatusList as $row)
                                            <option value="{{$row->job_status_id}}">{{$row->job_status_name}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div> 
                               <div class="col-md-3 hide">
                                 <div class="mb-3">
                                    <label for="sales_order_no" class="form-label">Sales Order No</label>
                                    <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($salesOrderList as $row)
                                            <option value="{{$row->tr_code}}">{{$row->tr_code}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div> 
                               <div class="col-md-3 hide">
                                 <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($brandList as $row)
                                            <option value="{{$row->brand_id}}">{{$row->brand_name}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div>  
                               <div class="col-md-3 hide">
                                 <div class="mb-3">
                                    <label for="mainstyle_id" class="form-label">Main Style Category</label>
                                    <select name="mainstyle_id" id="mainstyle_id" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($mainStyleList as $row)
                                            <option value="{{$row->mainstyle_id}}">{{$row->mainstyle_name}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div>
                               <div class="col-md-3 hide">  
                                 <div class="mb-3">
                                    <label for="orderTypeId" class="form-label">Order Type</label>
                                    <select name="orderTypeId" id="orderTypeId" class="form-control select2">
                                        <option value="">--Select--</option>
                                        @foreach($orderTypeList as $row) 
                                            <option value="{{$row->orderTypeId}}">{{$row->order_type}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                               </div> 
                               <div class="col-sm-6">
                                  <label for="formrow-inputState" class="form-label"></label>
                                  <div class="form-group">
                                     <button type="button" onclick="tableData();" class="btn btn-primary w-md">Search</button>
                                     <a href="/FGStockReportTrial" class="btn btn-danger w-md">Cancel</a>
                                  </div>
                               </div> 
                           </div> 
                         </div>
                      </div>
                   </div>
                </div>
                <div class="table-responsive">
                    <table id="tbl" class="table table-bordered   nowrap w-100">
                      <thead>
                        <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                            <th colspan="8"></th>
                            <th style="text-align: right;">Total : </th>
                            <th id="head_packing_grn_qty">{{$total_packing}}</th>
                            <th id="head_carton_packing_qty">{{$total_carton}}</th>
                            <th id="head_transfered_qty">{{$total_transfer}}</th>
                            <th id="head_fg_stock">{{$total_stock}}</th>
                            <th></th>
                            <th id="head_value">{{$total_value}}</th>
                        </tr>
                        <tr style="text-align:center; white-space:nowrap">
						    <th>Sr. No.<span class="filter-icon  hide">🔽</span><div class="filter-menu sr-no"></div></th>
                            <th>Sales Order No<span class="filter-icon">🔽</span><div class="filter-menu sales-order-no"></div></th>
                            <th>Buyer Name<span class="filter-icon">🔽</span><div class="filter-menu buyer-name"></div></th>
                            <th>Brand<span class="filter-icon">🔽</span><div class="filter-menu brand"></div></th>
                            <th>SAM<span class="filter-icon">🔽</span><div class="filter-menu sam"></div></th>
                            <th>Order Type<span class="filter-icon">🔽</span><div class="filter-menu order-type"></div></th>
                            <th>Style Category<span class="filter-icon">🔽</span><div class="filter-menu style-category"></div></th>
                            <th>Garment Color<span class="filter-icon">🔽</span><div class="filter-menu garment-color"></div></th>
                            <th>Size<span class="filter-icon">🔽</span><div class="filter-menu size"></div></th>
                            <th>Inward Qty<span class="filter-icon">🔽</span><div class="filter-menu inward-qty"></div></th>
                            <th>Outward Qty<span class="filter-icon">🔽</span><div class="filter-menu outward-qty"></div></th>
                            <th>Transfered Qty<span class="filter-icon">🔽</span><div class="filter-menu transfered-qty"></div></th>
                            <th>FG Stock<span class="filter-icon">🔽</span><div class="filter-menu fg-stock"></div></th>
                            <th>FOB Rate<span class="filter-icon">🔽</span><div class="filter-menu fob-rate"></div></th>
                            <th>Value<span class="filter-icon">🔽</span><div class="filter-menu value"></div></th>

                        </tr>
                        </thead>
                       <tbody>
                            <!--<tr>-->
                            <!--    <th colspan="15" class="text-center" style="color:red;" id="loadTbody">Please Wait.......! Data is loading.</th>-->
                            <!--</tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>  
<input type="hidden" id="currentDate" value="{{$currentDate}}">
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>

<script type="text/javascript">

    // function html_table_to_excel(type)
    //     {
    //         var data = document.getElementById('tbl');
    
    //         var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    
    //         XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    
    //         XLSX.writeFile(file, 'FG Stock Report.' + type);
    // }
    
    // const export_button = document.getElementById('export_button');

    // export_button.addEventListener('click', () =>  {
    //     html_table_to_excel('xlsx');
    // });
    
    function getQueryParam(name) 
    {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    
   let page = 1;




   function tableData(job_status_id) 
   {
         $('#head_packing_grn_qty').text(0);
        $('#head_carton_packing_qty').text(0);
        $('#head_transfered_qty').text(0);
        $('#head_fg_stock').text(0);
        $('#head_value').text(0);    
        sessionStorage.setItem('btnclickforgetalldata', 1);    
         removeFilterColor();
        


         var currentURL = window.location.href; 
                        
         var totalpacking_qty = 0;
         var totalcarton_pack_qty = 0;
         var totaltransfer_qty = 0;
         var totalstock = 0;
       
         
      	 $('#tbl').DataTable().clear().destroy();
        
        //   var table = $('#tbl').DataTable({
        //     ajax: currentURL,    
        //     dom : 'Bfrtip',
        //     processing: true,
        //     serverSide: false,
        //     buttons: [
        //         { extend: 'copyHtml5', footer: true },
        //         { extend: 'excelHtml5', footer: true },
        //         { extend: 'csvHtml5', footer: true },
        //         { extend: 'pdfHtml5', footer: true }
        //     ],
        //      "footerCallback": function (row, data, start, end, display) 
        //      {   
                  
        //         // var totalValue = 0;
        //         // if(data.length > 0)
        //         // {
        //         //     for (var i = 0; i < data.length; i++) 
        //         //     {
        //         //         // totalpacking_qty += parseFloat(data[i].packing_qty);
        //         //         // totalcarton_pack_qty += parseFloat(data[i].carton_pack_qty);
        //         //         // totaltransfer_qty += parseFloat(data[i].transfer_qty);
        //         //         // totalstock += parseFloat(data[i].stock);
        //         //         totalValue += parseFloat(data[i].Value);
        //         //     }
        //         //     console.log(totalValue);
        //         // }
              
        //         //$('#head_packing_grn_qty').html(totalpacking_qty.toLocaleString('en-IN'));
        //         // $('#head_carton_packing_qty').html(totalcarton_pack_qty.toLocaleString('en-IN'));
        //         // $('#head_transfered_qty').html(totaltransfer_qty.toLocaleString('en-IN'));
        //         // $('#head_fg_stock').html(totalstock.toLocaleString('en-IN'));
        //         // $('#head_value').html(totalSum.toLocaleString('en-IN'));
        //         // $("#totalFGStock").html('<b>Total Stock : </b><b>'+totalstock/100000+'</b>');
        //         // $("#totalFGValue").html('<b>Total Value : </b><b>'+totalValue/100000+'</b>');
        //       },
        //       columns: [
        //           {data: 'ac_name', name: 'ac_name'},
        //           {data: 'sales_order_no', name: 'sales_order_no'},
        //           {data: 'sam', name: 'sam'},
        //           {data: 'job_status_name', name: 'job_status_name'},
        //           {data: 'brand_name', name: "brand_name"},
        //           {data: 'mainstyle_name', name: 'mainstyle_name'}, 
        //           {data: 'color_name', name: 'color_name'},
        //           {data: 'size_name', name: 'size_name'},
        //           {data: 'packing_qty', name: 'packing_qty'},
        //           {data: 'carton_pack_qty', name: 'carton_pack_qty'},
        //           {data: 'transfer_qty', name: 'transfer_qty'},
        //           {data: 'stock', name: 'stock'},
        //           {data: 'fob_rate', name: 'fob_rate'},
        //           {data: 'Value', name: 'Value'},
        //     ]
        // });
        var currentDate = $("#currentDate").val();
        // $.ajax({
        //     dataType: "json", 
        //     url: "{{ route('LoadFGStockReportTrial') }}", 
        //     data:{'currentDate':currentDate},
        //     success: function(data)
        //     {
        //         console.log(data); 
                    
        //     } 
        // });
            var Ac_code = $("#Ac_code").val();
            var sales_order_no = $("#sales_order_no").val();
            var brand_id = $("#brand_id").val();
            var mainstyle_id = $("#mainstyle_id").val();
            var color_id = $("#color_id").val(); 
            var orderTypeId = $("#orderTypeId").val();
            
            $.ajax({
                url:  "{{ route('LoadFGStockReportTrial') }}",
                type: 'GET',
                data: { 'page': page,'currentDate':currentDate,'Ac_code':Ac_code,'sales_order_no':sales_order_no,'brand_id':brand_id,'mainstyle_id':mainstyle_id,'color_id':color_id,'job_status_id':job_status_id,'orderTypeId':orderTypeId },  
                beforeSend: function(e, xhr, settings) 
                {
                    //  var percentage = 0;
           
                    //   var timer = setInterval(function(){
                    //      percentage = percentage + 20;
                    //      //progress_bar_process(percentage, timer);
                    //   }, 1000);
                        
                    //   var counter = 0;
                    //   var timer = 0;
                    //   setInterval(function () {
                    //     var t = '';
                    //     if(counter < 60)
                    //     {
                    //       t = 'Seconds';
                    //       $('#counter').html(counter+' '+t);
                    //       if(timer > 0)
                    //       {
                    //             $('#counter').html(timer+' Minutes '+counter+' '+t);
                    //       }
                    //     }
                    //     else
                    //     {
                    //       t = 'Minutes';
                    //       timer++;
                    //       $('#counter').html(timer+' '+t);
                    //       counter = 0;
                    //     }
                    //     ++counter;
                    //   }, 1000);
           
                },
                success: function(data) 
                {                
                    // if(lastRow != 'undefined')
                    // {
                    //     lastRow.after(data); 
                    //     setTimeout(function() 
                    //     { 
                    //         page++;  
                    //         tableData(); 
                            
                    //     }, 2500);
                        
                    // }
                    // else
                    // {
                        if ($.fn.DataTable.isDataTable('#dt')) {
                            $('#dt').DataTable().clear().destroy();
                        } 
                
                        const today = new Date();
                        const day = String(today.getDate()).padStart(2, '0');
                        const month = String(today.getMonth() + 1).padStart(2, '0');
                        const year = today.getFullYear();
                        const formattedDate = `${day}-${month}-${year}`;
                        const exportTitle = 'FG Stock Date Wise Report - (' + formattedDate + ')';
        
                         var myArray = JSON.parse(data.html); 
                         $('#tbl').DataTable({
                            "dom": 'lBfrtip', // 'f' added for the search box
                            "pageLength": 10,
                             initComplete: function () {
                             buildAllMenusFGStockReport();                                
                             sessionStorage.setItem('btnclickforgetalldata', 0);                                 
                            },
                            buttons: [
                                {
                                    extend: 'copyHtml5',
                                    text: 'Copy',
                                    title: exportTitle, exportOptions: commonExportOptions() 
                                },
                                {
                                    extend: 'excelHtml5',
                                    text: 'Excel',
                                    title: exportTitle, exportOptions: commonExportOptions() 
                                },
                                {
                                    extend: 'csvHtml5',
                                    text: 'CSV',
                                    title: exportTitle, exportOptions: commonExportOptions() 
                                },
                                {
                                    extend: 'pdfHtml5',
                                    text: 'PDF',
                                    title: exportTitle, exportOptions: commonExportOptions() ,
                                    orientation: 'landscape',     // or 'portrait'
                                    pageSize: 'A4',               // A4, A3, etc.
                                    customize: function (doc) {
                                        doc.defaultStyle.fontSize = 10; // PDF text size
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: 'Print Table',
                                    title: exportTitle, exportOptions: commonExportOptions() 
                                }
                            ],
                            data: myArray,
                            columns: [
                               { data: "srno"}, 
                               { data: "sales_order_no"}, 
                               { data: "ac_name"}, 
                               { data: "brand_name"},
                               { data: "sam", className: 'text-right' }, 
                               { data: "order_type"},
                               { data: "mainstyle_name"},
                               { data: "color_name"},
                               { data: "size_name", className: 'text-right'},
                               { data: "packing_qty", className: 'text-right'},
                               { data: "carton_pack_qty", className: 'text-right'},
                               { data: "transfer_qty", className: 'text-right'},
                               { data: "stock", className: 'text-right'},
                               { data: "fob_rate", className: 'text-right'},
                               { data: "value", className: 'text-right'}, 
                            ],
                        });
                
                        $("#loadTbody").remove();
                        $("#head_packing_grn_qty").html(data.total_packing);
                        $("#head_carton_packing_qty").html(data.total_carton);
                        $("#head_transfered_qty").html(data.total_transfer);
                        $("#head_fg_stock").html(data.total_stock);
                        $("#head_value").html(data.total_value);
                        $("#Ac_code").val(data.Ac_code);
                        $("#sales_order_no").val(data.sales_order_no);
                        $("#brand_id").val(data.brand_id);
                        $("#mainstyle_id").val(data.mainstyle_id);
                        $("#color_id").val(data.color_id);
                        $("#job_status_id").val(data.job_status_id);
                        $("#orderTypeId").val(data.orderTypeId);
                        
                        var total_S = data.total_stock1;
                        var total_V = data.total_value1;
                        
                        $("#totalFGStock").html('<b>Total Stock(In Lakh): '+total_S+'</b>');
                        $("#totalFGValue").html('<b>Total Value(In Lakh): '+total_V+'</b>');
                      
                        // setTimeout(function() 
                        // { 
                            // page++;  
                           
                            
                        // }, 2500);
                    // } 
                },
              complete: function()
              {  
                //   $('#tbl').DataTable();
              }
            });
    }
    

             // Start script for filter search and apply        
         $(document).on('click', '.apply-btn', function() {
         const menu = $(this).closest('.filter-menu');
       
         if (!validateFilterMenu(menu)) {
               return;
         }

         if (menu.hasClass('sr-no')) applySimpleFilter(0, menu);
        else if (menu.hasClass('sales-order-no')) applySimpleFilter(1, menu);
        else if (menu.hasClass('buyer-name')) applySimpleFilter(2, menu);
        else if (menu.hasClass('brand')) applySimpleFilter(3, menu);
        else if (menu.hasClass('sam')) applySimpleFilter(4, menu);
        else if (menu.hasClass('order-type')) applySimpleFilter(5, menu);
        else if (menu.hasClass('style-category')) applySimpleFilter(6, menu);
        else if (menu.hasClass('garment-color')) applySimpleFilter(7, menu);
        else if (menu.hasClass('size')) applySimpleFilter(8, menu);
        else if (menu.hasClass('inward-qty')) applySimpleFilter(9, menu);
        else if (menu.hasClass('outward-qty')) applySimpleFilter(10, menu);
        else if (menu.hasClass('transfered-qty')) applySimpleFilter(11, menu);
        else if (menu.hasClass('fg-stock')) applySimpleFilter(12, menu);
        else if (menu.hasClass('fob-rate')) applySimpleFilter(13, menu);
        else if (menu.hasClass('value')) applySimpleFilter(14, menu);



         $('.filter-menu').hide();         
         buildAllMenusFGStockReport();
         updateTotalValuesForFGStockReport();      
         });
         // End script for filter search and apply
    
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
    
    function filters(value,row)
    {
        $("button").removeClass('success-main-checkmark');
        $(row).addClass('success-main-checkmark');
        tableData(value);
    }
     
    
    function DumpData()
    {
         $.ajax({
            dataType: "json",
            url: "{{ route('DumpFGData') }}",
            beforeSend: function() 
            { 
            },
            success: function(data)
            { 
                 console.log("completed");   
            },
            complete: function(data)
            {
              
            },
            error: function (error) 
            {
            }
        });
    }
    
    var xhr;
    function syncData()
    {
         xhr = $.ajax({
            dataType: "json",
            url: "{{ route('RunCronFGJob') }}",
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
                tableData();
                $(".alert-success").removeClass('hide'); 
                    
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
   
    function abort()
    {
        console.log("abort");
        xhr.abort();
    }
     
   $(function() {
      window.ajax_loading = false;
      $.hasAjaxRunning = function() 
      {
          return window.ajax_loading;
      };
      $(document).ajaxStart(function() { 
          $('#waiting').removeClass('hide');
          $('#starting').addClass('hide');
          window.ajax_loading = true;
      });
      $(document).ajaxStop(function() 
      { 
          $('#waiting').addClass('hide');
          $('#starting').removeClass('hide');
          window.ajax_loading = false; 
      });
   });

  
</script>                                        
@endsection