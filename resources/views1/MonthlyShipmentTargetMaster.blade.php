@extends('layouts.master') 
@section('content')
<style>
    .select2 
    {
        width: 200px!important;
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
            <form action="{{route('monthlyShipmentTargetStore')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>  
                                 <th class="text-center">Sr.No.</th>
                                 <th class="text-center">Sales Order No</th>
                                 <th class="text-center">Buyer Name</th>
                                 <th class="text-center">PO No</th>
                                 <th class="text-center">Buyer Brand</th>
                                 <th class="text-center">Main Style Category</th>
                                 <th class="text-center">Style Name</th>
                                 <th class="text-center">SAM</th>
                                 <th class="text-center">Order Rate</th>
                                 <th class="text-center">Order value  </th>
                                 <th class="text-center">From Date</th>
                                 <th class="text-center">To Date</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-1</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-2</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-3</th>
                                 <th class="text-center" style="background: #aecfeb;">Week-4</th>
                                 <th class="text-center" style="background: #aecfeb;">Target</th>
                                 <th class="text-center" style="background: #aecfeb;">Order Rate</th>
                                 <th class="text-center" style="background: #aecfeb;">Value</th>
                                 <th class="text-center" style="background: #e7c290;">Week 1</th>
                                 <th class="text-center" style="background: #e7c290;">Week 2</th>
                                 <th class="text-center" style="background: #e7c290;">Week 3</th>
                                 <th class="text-center" style="background: #e7c290;">Week 4</th>
                                 <th class="text-center" style="background: #e7c290;">Actual shipped</th>
                                 <th class="text-center" style="background: #e7c290;">Order Rate</th>
                                 <th class="text-center" style="background: #e7c290;">Value</th>
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
                                                $percentage  = (($actualShipped - $targetQty) * 1)/$targetQty;
                                            }
                                            else
                                            {
                                                $percentage  = 0;
                                            }
                                            $ActDiffVal = ($actualShipped * $row->order_rate) - ($targetQty * $row->order_rate);
                                      
                                
                                @endphp
                              <tr>
                                 <td>  
                                    <input type="text" style="width:60px;" readonly value=" {{ $nos++  }}"  class="form-control" />  
                                 </td>
                                 <td>  
                                    <input type="text" style="width:164px;" readonly step="any"  value=" {{ $row->tr_code  }}"  class="form-control" name="sales_order_no[]" />  
                                 </td>
                                 <td> 
                                    <input type="text" style="width:164px;" readonly step="any"  value=" {{ $row->Ac_name  }}" class="form-control"/>   
                                    <input type="hidden" style="width:164px;"  step="any"  value=" {{ $row->Ac_code  }}" class="form-control" name="buyer_code[]" />   
                                 </td>
                                 <td>  
                                    <input type="text" style="width:164px;" readonly step="any"  value=" {{ $row->po_code  }}" class="form-control" name="po_code[]" />   
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;" readonly step="any"  value="{{ $row->brand_name  }}" class="form-control" >  
                                    <input type="hidden" style="width:164px;" readonly step="any"  value="{{ $row->brand_id  }}" class="form-control" name="brand_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;" readonly step="any"  value="{{ $row->mainstyle_name  }}" class="form-control" /> 
                                    <input type="hidden" style="width:164px;" readonly step="any"  value="{{ $row->mainstyle_id  }}" class="form-control" name="mainstyle_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;" readonly step="any"  value="{{ $row->style_no  }}" class="form-control" name="style_no[]" />  
                                    <input type="hidden" style="width:164px;" readonly step="any"  value="{{ $row->fg_id  }}" class="form-control" name="fg_id[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;text-align: end;" step="any"  value="{{ $row->sam  }}" class="form-control" name="sam[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;text-align: end;" readonly step="any"  value="{{ money_format('%!i',$row->order_rate )  }}" class="form-control" name="order_rate[]" />  
                                 </td>
                                 <td>   
                                    <input type="text" style="width:164px;text-align: end;" readonly step="any"  value="{{ money_format('%!i',$row->order_value )  }}" class="form-control" name="order_value[]" />  
                                 </td>
                                 <td>
                                     <input type="date" style="width:164px;"  value="{{date('Y-m-d')}}" id="date" class="form-control" name="fromDate[]" />
                                 </td>
                                 <td>
                                     <input type="date" style="width:164px;"  value="{{date('Y-m-d')}}" id="date" class="form-control" name="toDate[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px; background: #aecfeb;text-align: end;" step="any"  value="{{$week1}}" id="week1{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week1[]" />
                                 </td>
                                 
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" step="any"  value="{{$week2}}" id="week2{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week2[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" step="any"  value="{{$week3}}" id="week3{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week3[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" step="any"  value="{{$week4}}" id="week4{{$nos}}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="week4[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" readonly step="any" name="targetQty[]" id="targetQty" value="{{$week1 + $week2 + $week3 + $week4}}" class="form-control" style="width:120px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" readonly step="any" name="orderRate[]" value="{{$row->order_rate}}" id="orderRate" class="form-control" style="width:120px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #aecfeb;text-align: end;" readonly step="any" name="value[]" value="{{($week1 + $week2 + $week3 + $week4) * $row->order_rate}}" id="value" class="form-control" style="width:120px;"  />
                                 </td>
                                          <td>
                                     <input type="number" style="width:164px; background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty1}}" id="ActWeek1{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek1[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty2}}" id="ActWeek2{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek2[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty3}}" id="ActWeek3{{ $nos }}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek3[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;" readonly step="any"  value="{{$totalOrderQty4}}" id="ActWeek4{{$nos}}" onchange="calWeek(this,this.value,{{$nos}});" class="form-control" name="ActWeek4[]" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;;"  readonly step="any" name="ActTargetQty[]" id="ActTargetQty{{ $nos }}" value="{{$actualShipped}}" class="form-control" style="width:120px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;" readonly  step="any" name="ActOrderRate[]" value="{{$row->order_rate}}" id="ActOrderRate{{ $nos }}" class="form-control" style="width:120px;" />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;background: #e7c290;text-align: end;" readonly step="any" name="ActValue[]" value="{{ $actualShipped * $row->order_rate}}" id="ActValue{{ $nos }}" class="form-control" style="width:120px;"  />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;text-align: end;"  step="any" readonly name="ActDiffQty[]" value="{{($actualShipped - $targetQty)}}" id="ActDiffQty{{ $nos }}" class="form-control" style="width:120px;"  />
                                 </td>
                                 <td>
                                     <input type="number" style="width:164px;text-align: end;"  step="any" readonly name="ActDiffVal[]" value="{{$ActDiffVal}}" id="ActDiffVal{{ $nos }}" class="form-control" style="width:120px;"  />
                                 </td>
                                 <td>
                                     <input type="text" style="width:164px;text-align: end;" step="any" readonly name="percentage[]" value="{{$percentage}}%" id="percentage{{ $nos }}" class="form-control" style="width:120px;"  />
                                 </td>
                            </tr>
                             @php
                                $totalOrderQty1 = 0;
                                $totalOrderQty2 = 0;
                                $totalOrderQty3 = 0;
                                $totalOrderQty4 = 0;
                             @endphp
                            @endforeach
                           </tbody>
                        </table>
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

        $(row).closest("tr").find('td:nth-child(17) input').val(total);
        $(row).closest("tr").find('td:nth-child(19) input').val(total * $("#orderRate").val());
    }
    
    function GetMinData(row,val)
    {
        var totalAvaliableMin = 0;
        var vendorId=  $(row).closest("tr").find('td:nth-child(1) select').val();
        var line_id= $(row).closest("tr").find('td:nth-child(2) select').val();
        var sales_order_no= $(row).closest("tr").find('td:nth-child(3) select').val();
        var sam = $(row).closest("tr").find('td:nth-child(4) input').val();
        var totalAvaliableMin = $(row).closest("tr").find('td:nth-child(6) input').val();
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetPPCData') }}",
            data:{'vendorId':vendorId, 'line_id':line_id, 'sales_order_no':sales_order_no},
            success: function(data)
            {
               var totalBookedMin = val*sam;
               $(row).closest("tr").find('td:nth-child(9) input').val(totalBookedMin);
               $(row).closest("tr").find('td:nth-child(10) input').val(parseInt(totalAvaliableMin) - parseInt(totalBookedMin));
            }
        });
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
   
   var indexcone = 2;
   function insertcone(obj)
   {
       var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
       var row=table.insertRow(table.rows.length);
       
       var cell1 = row.insertCell(0);
       var t1=document.createElement("input");
       t1.style="display: table-cell; width:164px;";
       t1.type="date";
       t1.id = "fromDate"+indexcone;
       t1.name="fromDate[]";
       t1.value="";
       t1.className="form-control";
       cell1.appendChild(t1);
       
       var cell1 = row.insertCell(1);
       var t1=document.createElement("input");
       t1.style="display: table-cell; width:164px;";
       t1.type="date";
       t1.id = "toDate"+indexcone;
       t1.name="toDate[]";
       t1.value="";
       t1.className="form-control";
       cell1.appendChild(t1);
       
       var cell1 = row.insertCell(2);
       var t1=document.createElement("input");
       t1.style="display: table-cell; width:164px;";
       t1.type="text";
       t1.id = "week1"+indexcone;
       t1.name="week1[]";
       t1.value="";
       t1.className="form-control";
       t1.setAttribute("onchange","calWeek(this,this.value,"+indexcone+")");
       cell1.appendChild(t1);
       
       var cell2 = row.insertCell(3);
       var t2=document.createElement("input");
       t2.style="display: table-cell; width:164px;";
       t2.type="text";
       t2.id = "week2"+indexcone;
       t2.name="week2[]";
       t2.setAttribute("onchange","calWeek(this,this.value,"+indexcone+")");
       t2.className="form-control";
       cell2.appendChild(t2);
       
       var cell3 = row.insertCell(4);
       var t3=document.createElement("input");
       t3.style="display: table-cell; width:164px;";
       t3.type="text";
       t3.id = "week3"+indexcone;
       t3.name="week3[]";
       t3.setAttribute("onchange","calWeek(this,this.value,"+indexcone+")");
       t3.className="form-control";
       cell3.appendChild(t3);
       
       
       var cell4 = row.insertCell(5);
       var t4=document.createElement("input");
       t4.style="display: table-cell; width:164px;";
       t4.type="text";
       t4.id = "week4"+indexcone;
       t4.name="week4[]";
       t4.setAttribute("onchange","calWeek(this,this.value,"+indexcone+")");
       t4.className="form-control";
       cell4.appendChild(t4);
       
       
       var cell5 = row.insertCell(6);
       var t4=document.createElement("input");
       t4.style="display: table-cell; width:164px;";
       t4.type="text";
       t4.className="form-control";
       t4.id = "targetQty"+indexcone;
       t4.name="targetQty[]";
       cell5.appendChild(t4);
       
       var cell6 = row.insertCell(7);
       var t6=document.createElement("input");
       t6.style="display: table-cell; width:164px;";
       t6.type="text";
       t6.className="form-control";
       t6.id = "orderRate"+indexcone;
       t6.name="orderRate[]";
       t6.value=$("#orderRate").val();
       cell6.appendChild(t6);
       
       var cell7 = row.insertCell(8);
       var t7=document.createElement("input");
       t7.style="display: table-cell; width:164px;";
       t7.type="text";
       t7.id = "value"+indexcone;
       t7.name="value[]";
       t7.className="form-control";
       cell7.appendChild(t7);
       
       var cell8=row.insertCell(9);
       var btnAdd = document.createElement("INPUT");
       btnAdd.id = "Abutton";
       btnAdd.type = "button";
       btnAdd.className="btn btn-warning pull-left";
       btnAdd.value = "+";
       btnAdd.setAttribute("onclick", "insertcone(this)");
       cell8.appendChild(btnAdd);
       
       
       var btnRemove = document.createElement("INPUT");
       btnRemove.id = "Dbutton";
       btnRemove.style="margin-left: 1px;";
       btnRemove.type = "button";
       btnRemove.className="btn btn-danger pull-left";
       btnRemove.value = "X";
       btnRemove.setAttribute("onclick", "deleteRowcone(this)");
       cell8.appendChild(btnRemove);

       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
       
       indexcone++;
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