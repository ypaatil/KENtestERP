@extends('layouts.master') 
@section('content')
<style>
    .select2 
    {
        width: 200px!important;
    }
    
     table 
     { 
      border-collapse: collapse;         
      width: 100%; 
    }   
    
    .sticky-column1 
    {
      flex: 0 0 25%; 
      position: sticky;
      background:beige!important;
      left: 0; 
      top: 0; 
    }
    .sticky-column2 
    {
      flex: 0 0 25%; 
      position: sticky;
      background:beige!important;
      left: 80px; 
      top: 0; 
    }
    .sticky-column3 
    {
      flex: 0 0 25%; 
      position: sticky;
      background:beige!important;
      left: 210px; 
      top: 0; 
    }
    .sticky-column4 
    {
      flex: 0 0 25%; 
      position: sticky;
      background:beige!important;
      left: 330px; 
      top: 0; 
    }
   
    #tableWrapper {
      overflow-x: auto;
      max-width: 100%;
      max-height: 600px;
      position: relative;
      table-layout: relative;
    }
    
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Monthly Shipment Target Master</h4>
            @if ($errors->any())
            <div class="col-md-6">
               <div class="alert alert-danger">
                  <ul>
                     @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               </div>
            </div>
            @endif
            <div class="col-md-6">
              <form action="/MonthlyShipmentTargetMaster" method="GET" enctype="multipart/form-data">
                  <div class="row m-4"> 
                      <div class="col-md-3"> <label for="fromDate" class="form-label">Month</label><input type="month" name="monthDate" value="{{$monthDate}}" class="form-control" id="monthDate" ></div>
                      <div class="col-sm-2 mt-4"> 
                         <button type="submit" class="btn btn-primary w-md">Search</button> 
                      </div>
                  </div>
              </form>
            </div>
            <form action="{{route('monthlyShipmentTargetStore')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
               <input type="hidden" name="monthDate" value="{{ $monthDate }}"  />  
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap" >
                     <div class="table-responsive" id="tableWrapper">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>  
                                 <th class="text-center sticky-column1">Sr.No.</th>
                                 <th class="text-center sticky-column2">Sales Order No</th>
                                 <th class="text-center sticky-column3">Order Status</th>
                                 <th class="text-center sticky-column4">Order Type</th>
                                 <th class="text-center">Buyer Name</th> 
                                 <th class="text-center">Buyer Brand</th>
                                 <th class="text-center">Main Style</th>
                                 <th class="text-center">Style Name</th>
                                 <th class="text-center">SAM</th>
                                 <th class="text-center">Order Rate</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-1</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-2</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-3</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-4</th>
                                 <th class="text-center" style="background: #54e31882;">Target</th>
                                 <th class="text-center" style="background: #54e31882;">Value</th>
                                 <th class="text-center" style="background: #e7c290;">Week 1</th>
                                 <th class="text-center" style="background: #e7c290;">Week 2</th>
                                 <th class="text-center" style="background: #e7c290;">Week 3</th>
                                 <th class="text-center" style="background: #e7c290;">Week 4</th>
                                 <th class="text-center" style="background: #54e31882;">Actual shipped</th>
                                 <th class="text-center" style="background: #54e31882;">Value</th>
                                 <th class="text-center">Difference in Qty</th>
                                 <th class="text-center">Difference in Value</th>
                                 <th class="text-center">%</th>
                              </tr>
                           </thead>
                           <tbody id="endData">
                             @php
                                $nos = 1;
                                $totalOrderQty1 = 0;
                                $totalOrderQty2 = 0;
                                $totalOrderQty3 = 0;
                                $totalOrderQty4 = 0;
                                
                                function weekOfMonth($date) 
                                {
                                    $firstOfMonth = strtotime(date("Y-m-01", $date));
                                    return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
                                }
                                
                                function weekOfYear($date) 
                                {
                                    $weekOfYear = intval(date("W", $date));
                                    if (date('n', $date) == "1" && $weekOfYear > 51) 
                                    {
                                        return 0;
                                    }
                                    else if (date('n', $date) == "12" && $weekOfYear == 1) 
                                    {
                                        return 53;
                                    }
                                    else 
                                    {
                                        return $weekOfYear;
                                    }
                                }
                             $week1 = 0;
                             $week2 = 0;
                             $week3 = 0;
                             $week4 = 0;
                             @endphp
                             @foreach($salesOrderList as $row) 
                               @php 
                                       $monthlyShipementDetails = App\Models\MonthlyShipmentTargetDetailModel::select('*')
                                             ->where('sales_order_no','=', $row->tr_code)
                                             ->where('monthDate', '=', date("Y-m", strtotime($fromDate)))
                                             ->first();
                                        if($monthlyShipementDetails!= "")
                                        {
                                            $week1 = $monthlyShipementDetails->week1;
                                            $week2 = $monthlyShipementDetails->week2;
                                            $week3 = $monthlyShipementDetails->week3;
                                            $week4 = $monthlyShipementDetails->week4;
                                        }
                                            
                                        $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select( 'sale_transaction_detail.sale_date',DB::raw('sum(order_qty) as order_qty'))
                                             ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                                             ->where('sales_order_no','=', $row->tr_code)
                                             ->whereBetween('sale_transaction_master.sale_date', [$fromDate,$toDate])
                                             ->groupBy('sale_transaction_master.sale_code')
                                             ->get();
                                       
                                        foreach($SaleTransactionDetails as $details)
                                        {
                                            if(weekOfMonth(strtotime($details->sale_date)) == 1)
                                            {
                                                $totalOrderQty1 = $totalOrderQty1 + $details->order_qty;
                                            }
                                            
                                            if(weekOfMonth(strtotime($details->sale_date)) == 2)
                                            {
                                                $totalOrderQty2 = $totalOrderQty2 + $details->order_qty;
                                            }
                                            
                                            if(weekOfMonth(strtotime($details->sale_date)) == 3)
                                            {
                                                $totalOrderQty3 = $totalOrderQty3 + $details->order_qty;
                                            }
                                            
                                            if(weekOfMonth(strtotime($details->sale_date)) == 4)
                                            {
                                                $totalOrderQty4 = $totalOrderQty4 + $details->order_qty;
                                            }
                                            
                                        }
                                            $targetQty = $week1 + $week2 + $week3 + $week4;
                                            $actualShipped = $totalOrderQty1 + $totalOrderQty2 + $totalOrderQty3 + $totalOrderQty4;
                                            if($targetQty > 0)
                                            {
                                                $percentage  = sprintf("%.2f",((($targetQty-$actualShipped) * 100)/$targetQty));
                                            }
                                            else
                                            {
                                                $percentage  = 0;
                                            }
                                            $ActDiffVal = ($targetQty * $row->order_rate) - ($actualShipped * $row->order_rate);
                                      
                                
                                @endphp
                              <tr>
                                 <td class=" sticky-column1">  
                                    <input type="text" style="width:60px;" readonly value=" {{ $nos++  }}"  class="form-control" />
                                 </td>
                                 <td class=" sticky-column2">  
                                    <input type="text" style="width:110px;" readonly value=" {{ $row->tr_code  }}"  class="form-control" name="sales_order_no[]" />  
                                 </td>
                                 <td class=" sticky-column3">  
                                    <input type="text" style="width:110px;" readonly value=" {{ $row->job_status_name  }}"  class="form-control" name="job_status_name[]" />  
                                 </td>
                                 <td class=" sticky-column4">  
                                    <input type="text" style="width:110px;" readonly value=" {{ $row->order_type  }}"  class="form-control" name="order_type[]" />  
                                 </td>
                                 <td> 
                                    <input type="text" style="width:170px;" readonly step="any"  value=" {{ $row->Ac_name  }}" class="form-control"/>   
                                    <input type="hidden" style="width:170px;"  step="any"  value=" {{ $row->Ac_code  }}" class="form-control" name="buyer_code[]" />   
                                 </td> 
                                 <td>   
                                    <input type="text" style="width:170px;" readonly step="any"  value="{{ $row->brand_name  }}" class="form-control" >  
                                    <input type="hidden" style="width:170px;" readonly step="any"  value="{{ $row->brand_id  }}" class="form-control" name="brand_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:110px;" readonly step="any"  value="{{ $row->mainstyle_name  }}" class="form-control" /> 
                                    <input type="hidden" style="width:110px;" readonly step="any"  value="{{ $row->mainstyle_id  }}" class="form-control" name="mainstyle_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:170px;" readonly step="any"  value="{{ $row->style_no  }}" class="form-control" name="style_no[]" />  
                                    <input type="hidden" style="width:170px;" readonly step="any"  value="{{ $row->fg_id  }}" class="form-control" name="fg_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:110px;text-align: end;" step="any"  value="{{ $row->sam  }}" class="form-control" name="sam[]" readonly />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:110px;text-align: end;" readonly step="any"  value="{{ money_format('%!i',$row->order_rate )  }}" class="form-control" name="order_rate[]" />  
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px; background: #aecfeb;text-align: end;" step="any"  value="{{$week1}}" id="week1{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week1[]" />
                                 </td>
                                 
                                 <td>
                                     <input type="number" style="width:110px;background: #aecfeb;text-align: end;" step="any"  value="{{$week2}}" id="week2{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week2[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #aecfeb;text-align: end;" step="any"  value="{{$week3}}" id="week3{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week3[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #aecfeb;text-align: end;" step="any"  value="{{$week4}}" id="week4{{$nos}}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week4[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #54e31882;text-align: end;" readonly step="any" name="targetQty[]" id="targetQty" value="{{$week1 + $week2 + $week3 + $week4}}" class="form-control" style="width:110px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #54e31882;text-align: end;" readonly step="any" name="value[]" value="{{round(($week1 + $week2 + $week3 + $week4) * $row->order_rate)}}" id="value" class="form-control" style="width:110px;"  />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px; background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty1}}" id="ActWeek1{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek1[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #e7c290;text-align: end;" readonly step="any" value="{{$totalOrderQty2}}" id="ActWeek2{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek2[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty3}}" id="ActWeek3{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek3[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty4}}" id="ActWeek4{{$nos}}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek4[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #54e31882;text-align: end;;"  readonly step="any" name="ActTargetQty[]" id="ActTargetQty{{ $nos }}" value="{{$actualShipped}}" class="form-control" style="width:110px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;background: #54e31882;text-align: end;" readonly step="any" name="ActValue[]" value="{{ round($actualShipped * $row->order_rate)}}" id="ActValue{{ $nos }}" class="form-control" style="width:110px;"  />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;text-align: end;"  step="any" readonly name="ActDiffQty[]" value="{{($targetQty - $actualShipped)}}" id="ActDiffQty{{ $nos }}" class="form-control" style="width:110px;"  />
                                 </td>
                                 <td>
                                     <input type="number" style="width:110px;text-align: end;"  step="any" readonly name="ActDiffVal[]" value="{{round($ActDiffVal)}}" id="ActDiffVal{{ $nos }}" class="form-control" style="width:110px;"  />
                                 </td>
                                 <td>
                                     <input type="text" style="width:110px;text-align: end;" step="any" readonly name="percentage[]" value="{{$percentage}}" id="percentage{{ $nos }}" class="form-control" style="width:110px;"  />
                                 </td>
                            </tr>
                             @php
                                
                                $week1 = 0;
                                $week2 = 0;
                                $week3 = 0;
                                $week4 = 0;
                                $totalOrderQty1 = 0;
                                $totalOrderQty2 = 0;
                                $totalOrderQty3 = 0;
                                $totalOrderQty4 = 0;
                                
                             @endphp
                            @endforeach
                           </tbody>
                        </table>
                     </div>
                     <div class="sticky-scrollbar">
                      
                      </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                  </div>
                </div>
         </form>
      </div>
      <!-- end card body -->
   </div>
   <!-- end card -->
</div>
<!-- end col -->
<!-- end col -->
</div>
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
       
    document.addEventListener('DOMContentLoaded', function() {
      var tableWrapper = document.getElementById('tableWrapper');
      var table = document.getElementById('footable_3');
      var thead = table.querySelector('thead');
    
      tableWrapper.addEventListener('scroll', function() {
        var wrapperRect = tableWrapper.getBoundingClientRect();
        var tableRect = table.getBoundingClientRect();
    
        // Calculate the difference between the top positions of the wrapper and the table
        var topDifference = tableRect.top;
    
        // Set thead to sticky
        thead.style.position = 'sticky';
       
        // Set top position dynamically
        if (wrapperRect.top > tableRect.top) {
          thead.style.top = Math.max(0, topDifference) + 'px';
          console.log("1");
        } else {
          thead.style.top = ""; // Reset to original value
          console.log("2");
        }
    
        // Set z-index
        thead.style.zIndex = '100';
    
        // Set background color
        thead.style.backgroundColor = 'beige';
      });
    });





    function GetBuyerData(obj)
    {
       $.ajax({
            dataType: "json",
            url: "{{ route('GetBuyerData') }}",
            data:{'sales_order_no':obj},
            success: function(data)
            {
              $("#buyer_code").html(data.buyer);
            }
        }); 
    }
    function GetStyleCategoryData(obj)
    {
       var sales_order_no = $("#sales_order_no").val();
       $.ajax({
            dataType: "json",
            url: "{{ route('GetStyleCategoryData') }}",
            data:{'sales_order_no':sales_order_no,ac_code:obj},
            success: function(data)
            {
              $("#mainstyle_id").html(data.mainStyle);
              $("#orderRate").val(data.order_rate);
            }
        }); 
    }
    
    function calWeek(row,val,index)
    {
        
        total = parseInt($("#week1"+index).val() ? $("#week1"+index).val() : 0) + parseInt($("#week2"+index).val() ? $("#week2"+index).val() : 0)  + parseInt($("#week3"+index).val() ? $("#week3"+index).val() : 0)  + parseInt($("#week4"+index).val()? $("#week4"+index).val() : 0);
        var orderRate = $(row).closest("tr").find('td:nth-child(10) input').val();
        $(row).closest("tr").find('td:nth-child(15) input').val(total);
        $(row).closest("tr").find('td:nth-child(16) input').val(total * orderRate);
    }
    
    function GetMinData(row,val)
    {
        // var totalAvaliableMin = 0;
        // var vendorId=  $(row).closest("tr").find('td:nth-child(1) select').val();
        // var line_id= $(row).closest("tr").find('td:nth-child(2) select').val();
        // var sales_order_no= $(row).closest("tr").find('td:nth-child(3) select').val();
        // var sam = $(row).closest("tr").find('td:nth-child(4) input').val();
        // var totalAvaliableMin = $(row).closest("tr").find('td:nth-child(6) input').val();
        
        // $.ajax({
        //     dataType: "json",
        //     url: "{{ route('GetPPCData') }}",
        //     data:{'vendorId':vendorId, 'line_id':line_id, 'sales_order_no':sales_order_no},
        //     success: function(data)
        //     {
        //       var totalBookedMin = val*sam;
        //       $(row).closest("tr").find('td:nth-child(9) input').val(totalBookedMin);
        //       $(row).closest("tr").find('td:nth-child(10) input').val(parseInt(totalAvaliableMin) - parseInt(totalBookedMin));
        //     }
        // });
    }
    function GetLineData(ele)
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GetLineList') }}",
            data:{'Ac_code':ele},
            success: function(data){
            $('#searchLineId').html(data.html);
           }
        });
    }
 
    
   
   function CalculateQtyRowPro(row)
   {   
     
    var machine_count=+row.find('input[name^="machine_count[]"]').val();
    var available_mins=+row.find('input[name^="available_mins[]"]').val();
    var line_efficiency=+row.find('input[name^="line_efficiency[]"]').val();
    var sam=+row.find('input[name^="sam[]"]').val();
    var production_capacity=parseFloat((((parseFloat(machine_count) * parseFloat(available_mins) * parseFloat(line_efficiency))/sam)/100)).toFixed(0);
    row.find('input[name^="production_capacity[]"]').val(production_capacity);
   }
    
   function GetLineList(row)
   {    
        var vendorId = $(row).val();
        var row = $(row).closest('tr'); 
        $.ajax({
            dataType: "json",
            url: "{{ route('GetLineList') }}",
            data:{'Ac_code':vendorId},
            success: function(data){
            row.find('select[name^="line_id[]"]').html(data.html);
           }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetSalesOrderList') }}",
            data:{'vendorId':vendorId},
            success: function(data){
            row.find('select[name^="sales_order_no[]"]').html(data.html);
           }
        });
   }
  
   function selselect()
   {
     setTimeout(function(){
     $("#footable_3 tr td  select[name='vendorId[]']").each(function() {
          $(this).closest("tr").find('select[name="vendorId[]"]').select2();
          $(this).closest("tr").find('select[name="line_id[]"]').select2();
          $(this).closest("tr").find('select[name="sales_order_no[]"]').select2();
          $(this).closest("tr").find('select[name="month[]"]').select2();
         });
     }, 1000);
   }
   

   
   function deleteRowcone(btn) 
   {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       
       document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
       
       if($("#cntrr").val()<=0)
       {		
         document.getElementById('Submit').disabled=true;
       }
   }
   
</script>
<!-- end row -->
@endsection