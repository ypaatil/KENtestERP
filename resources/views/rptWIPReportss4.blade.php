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
    .text-right
    {
        text-align: right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">WIP Report - 4</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">WIP Report - 4</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row">
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="fw-medium" style="color:#fff;">Total WIP(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP">0</h4>
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
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="  fw-medium" style="color:#fff;">Total Value(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP_value">0</h4>
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
    <div class="col-md-8">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/rptWIPReportss4" method="GET">
                  <div class="row"> 
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">PO Status</label>
                            <select name="job_status_id[]" class="form-control" id="job_status_id"  multiple>
                                 <option value="0">--All--</option>
                                 <option value="1">Open</option>
                                 <option value="2">Close</option>
                                 <option value="5">Pending For OCR</option>
                            </select>
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="order_type" class="form-label">Order Type</label>
                            <select name="order_type[]" class="form-control"  id="order_type" multiple required>
                               <option value="">--Select--</option>
                               <option value="1"  {{ $order_type == 1 ? 'selected="selected"' : '' }}>Fresh</option>
                               <option value="2"  {{ $order_type == 2 ? 'selected="selected"' : '' }}>Stock</option>
                               <option value="3"  {{ $order_type == 3 ? 'selected="selected"' : '' }}>Job Work</option>
                            </select>
                         </div>
                      </div>
                      
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="order_type" class="form-label">Date</label>
                            <input type="date" class="form-control" name="tr_date" id="tr_date" value="{{isset($tr_date) ? $tr_date:""}}">
                         </div>
                      </div>
                      <div class="col-md-3 mt-3"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/rptWIPReportss4" class="btn btn-warning">Clear</a>
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
            <!--<div class="col-md-12 hide text-center"  id="waiting"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>-->
            <div class="table-responsive">
               
                     <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                         <thead>
                              <tr style="background-color:#eee;"> 
                                   <th nowrap>Code</th>
                                   <th nowrap>Order Type</th>
                                   <th nowrap>PO status</th>
                                   <th class="text-center" nowrap>Received Date</th>
                                   <th class="text-center" nowrap>Buyer Name</th> 
                                   <th class="text-center" nowrap>Buyer Brand</th>
                                   <th class="text-center" nowrap>Category</th>
                                   <th class="text-center" nowrap>Style Name</th>
                                   <th class="text-center" nowrap>SAM</th>
                                   <th class="text-center" nowrap>Order Rate</th>
                                   <th class="text-center" nowrap>Stock Rate</th> 
                                   <th class="text-center" nowrap>Work Order Qty</th>
                                   <th class="text-center" nowrap>Work Order Value</th>
                                   <th class="text-center" nowrap>Packing Qty</th>
                                   <th class="text-center" nowrap>Packing Value</th>
                                   <th class="text-center" nowrap>WIP Qty</th>
                                   <th class="text-center" nowrap>WIP Value</th>
                              </tr>
                         </thead>
                         <tbody>
                            @php
                                $totalWIP = 0;
                                $totalWIPValue = 0;
                            @endphp
                            @foreach($Buyer_Purchase_Order_List as $row)  
                            @php
                            
                                if($currentDate != "")
                                {
                                    $vw_date = " AND vendor_work_order_detail.vw_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')"; 
                                    $pkiDate = " AND packing_inhouse_size_detail2.pki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
                                    $pkiDate1 = " AND packing_inhouse_detail.pki_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')"; 
                                }
                                else
                                { 
                                    $vw_date= ""; 
                                    $pkiDate = "";
                                    $pkiDate1 = ""; 
                                }
        
                                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                                     where  sales_order_no='".$row->tr_code."'".$vw_date);
                                
                                $openWorkOrderData = DB::select("SELECT ifnull(sum(size_qty_total),0) as order_qty from vendor_work_order_detail  
                                                    LEFT join  vendor_work_order_master on  vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                                                    where  vendor_work_order_detail.sales_order_no='".$row->tr_code."' 
                                                    AND vendor_work_order_master.endflag = 1".$vw_date);
                                    
                           
                               $PackingData = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2  
                                                WHERE packing_inhouse_size_detail2.sales_order_no = '".$row->tr_code."'".$pkiDate);
      
                               if(count($PackingData) > 0)
                               {
                                     $packingQty = $PackingData[0]->size_qty;
                               }
                               else
                               {
                                     $packingQty = 0;
                               }
                               
                               $openPackingOrderData = DB::select("SELECT ifnull(sum(size_qty_total),0) as order_qty from packing_inhouse_detail  
                                                    LEFT join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                                                    where  packing_inhouse_detail.sales_order_no='".$row->tr_code."' 
                                                    AND vendor_purchase_order_master.endflag = 1".$pkiDate1);
                          
                               // DB::enableQueryLog();
                               //$fgData = DB::select("SELECT order_rate from temp_fg_stock_report_data where sales_order_no='".$row->tr_code."'");
                               //DB::enableQueryLog();
                               $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                          left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                          where buyer_purchse_order_master.tr_code='".$row->tr_code."' group by buyer_purchse_order_master.tr_code");
                                //dd(DB::getQueryLog());
                                $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                if($stockData == 0)
                                {
                                    $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                }
                                else
                                {
                                    $fob_rate = $fgData[0]->total_cost_value;
                                }
                              //dd(DB::getQueryLog()); 
                              
                              if($row->order_type == 1)
                              {
                                  $order_type = 'Fresh';
                              }
                              else if($row->order_type == 2)
                              {
                                  $order_type = 'Stock';
                              }
                              else if($row->order_type == 3)
                              {
                                  $order_type = 'Job Work';
                              }
                              else
                              {
                                  $order_type = '';
                              }
                              
                           
                            @endphp
                            <tr>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{ $order_type  }}</td>
                               <td nowrap>{{ $row->job_status_name  }}</td>
                               <td class="text-center" nowrap> {{ date('d-m-Y', strtotime($row->tr_date)) }}</td>
                               <td nowrap> {{ $row->Ac_name  }} </td> 
                               <td class="text-center" nowrap> {{ $row->brand_name  }} </td>
                               <td class="text-center" nowrap>{{ $row->mainstyle_name  }}</td>
                               <td nowrap> {{ $row->style_no  }}</td>
                               <td nowrap class="text-right">{{ number_format($row->sam)  }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($row->order_rate) )}}</td>
                               <td class="text-right" nowrap>{{ number_format((isset($fob_rate) ? $fob_rate : 0),2 )}}</td> 
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty)}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty * $fob_rate)}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",$packingQty)}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",$packingQty * $fob_rate)}}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",((isset($VendorData[0]->work_order_qty) ? $VendorData[0]->work_order_qty : 0) -  $packingQty)) }} </td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",(((isset($VendorData[0]->work_order_qty) ? $VendorData[0]->work_order_qty : 0) -  $packingQty) * $fob_rate)) }} </td>
                            </tr>
                            @php
                            
                                $totalWIP += (isset($VendorData[0]->work_order_qty) ? $VendorData[0]->work_order_qty : 0) -  $packingQty;
                                $totalWIPValue += (((isset($VendorData[0]->work_order_qty) ? $VendorData[0]->work_order_qty : 0) -  $packingQty) * $fob_rate);
                              
                            @endphp
                            @endforeach
                         </tbody>
                      </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<input type="hidden" id="totalWIP" value="{{$totalWIP}}" />
<input type="hidden" id="totalWIPValue" value="{{$totalWIPValue}}" />
<input type="hidden" id="selectedValue" value="{{$selectedValue}}" />
<input type="hidden" id="selectedValueOrderType" value="{{$selectedValueOrderType}}" />
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script>  
    $( document ).ready(function() {
        $("#head_total_WIP").html(($("#totalWIP").val()/100000).toFixed(2));
        $("#head_total_WIP_value").html(($("#totalWIPValue").val()/100000).toFixed(2));
        // $("#job_status_id").val($('#selectedValue').val());
        var selectedValue = $("#selectedValue").val();
        var selectedValueOrderType = $("#selectedValueOrderType").val();
        
        $.each(selectedValue.split(","), function(i,e){
            $("#job_status_id option[value='" + e + "']").prop("selected", true);
        });
        
        $.each(selectedValueOrderType.split(","), function(i,e){
            $("#order_type option[value='" + e + "']").prop("selected", true);
        });
    });
 
     
//   $('#printPage').click(function(){
//       Popup($('.invoice')[0].outerHTML);
//       function Popup(data) 
//       {
//           window.print();
//           return true;
//       }
//       });
//      function html_table_to_excel(type)
//      {
//         var data = document.getElementById('invoice');

//         var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

//         XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

//         XLSX.writeFile(file, 'WIP Report - 1.' + type);
//      }

//       const export_button = document.getElementById('export_button');
    
//       export_button.addEventListener('click', () =>  {
//             html_table_to_excel('xlsx');
//       });
      
//      $(document).ready(function(){
//             var result = [];
//             $('table tr').each(function(){
//               $('td', this).each(function(index, val){
//                   if(!result[index]) result[index] = 0;
//                   result[index] += parseFloat($(val).text().replace(/,/g , ''));
//               });
//             });
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             result.shift();
//             $('table').append('<tr><td colspan="13" class="text-right"><strong>Total : </strong></td></tr>');
//             $(result).each(function(){
//                 var y=Math.round(this);
//                 y=y.toString();
//                 var lastThree1 = y.substring(y.length-3);
//                 var otherNumbers1 = y.substring(0,y.length-3);
//                 if(otherNumbers1 != '')
//                     lastThree1 = ',' + lastThree1;
//                 var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
//               $('table tr').last().append('<td class="text-right"><strong>'+res1+'</strong></td>')
//             });
//       });
      
   </script>
@endsection