@extends('layouts.master') 
@section('content')
<style>
    .select2 
    {
        width: 140px!important;
    }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Production Planning Control</h4>
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
            <form action="{{route('search')}}" method="POST" enctype="multipart/form-data">
                @csrf 
                <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-3" style="margin-bottom: 20px;">
                        <select name="searchVendorId"  id="searchVendorId" class="select2" onchange="GetLineData(this.value);" style="width:250px;">
                           <option value="">--Vendors--</option>
                           @foreach($Ledger as  $rowvendor)
                           @php 
                                if($rowvendor->ac_code == $searchVendorId)
                                {
                                    $selected  = 'selected';
                                }
                                else
                                {
                                     $selected  = '';
                                }
                           @endphp
                                <option value="{{ $rowvendor->ac_code }}" {{$selected}} >{{ $rowvendor->ac_name }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="col-md-2" style="margin-bottom: 20px;">
                        <select name="searchLineId"  id="searchLineId" class="select2" style="width:100px;">
                           <option value="">--Line No.--</option>
                        </select>
                    </div>
                     <div class="col-md-2" style="margin-bottom: 20px;">
                         <button type="submit" class="btn btn-warning">Search</button>
                         <a href="{{route('PPCMaster.index')}}" class="btn btn-info" >clear</a>
                     </div>
                </div>
            </form>
            <form action="{{route('PPCMaster.store')}}" method="POST" enctype="multipart/form-data">
               @csrf 
               <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
               <input type="hidden" name="searchVendorId" value="{{$searchVendorId}}" />
               <input type="hidden" name="searchLineId" value="{{$searchLineId}}" />
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Vendor Name</th>
                                 <th>Sales Order</th>
                                 <th>Color</th>
                                 <th>Line No</th>
                                 <th>No. of M/C</th>
                                 <th>Total Available Minutes</th>
                                 <th>Line Efficiency</th>
                                 <th>SAM</th>
                                 <th>Production Capacity</th>
                                 <th>Target</th>
                                 <th>Start Date</th>
                                 <th>End Date</th>
                                 <th>Add/Remove </th>   
                              </tr>
                           </thead>
                           <tbody id="endData">
                              @php 
                              $no=1; 
                              $calStartDate = "";
                              if($EndDate != "")
                              {
                                $EndDate1 = $EndDate->end_date;
                              }
                              else
                              {
                                $EndDate1 = "";
                              }
                             
                              if(count($PPCList)>0)
                              {
                                $calStartDate = $PPCList[0]->start_date;
                              @endphp
                              @foreach($PPCList as $ppc)
                              <tr>
                                 <td><input type="text" name="id[]" value="{{$no++}}" id="id" style="width:50px;"/></td>
                                 <td>
                                    <select name="vendorId[]"  id="vendorId" class="select2" style="width:250px;" required  onchange="GetLineList(this);">
                                       <option value="">--Vendors--</option>
                                       @foreach($Ledger as  $rowvendor)
                                       {
                                       <option value="{{ $rowvendor->ac_code }}"
                                       {{ $rowvendor->ac_code == $ppc->vendorId ? 'selected="selected"' : '' }}
                                       >{{ $rowvendor->ac_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="sales_order_no[]"  id="sales_order_no" class="select2" style="width:100px;" required onchange="GetColorList(this);" >
                                       <option value="">--Sales Order No.--</option>
                                       @foreach($SalesList as  $rowSale)
                                       {
                                       <option value="{{ $rowSale->sales_order_no }}"
                                       {{ $rowSale->sales_order_no == $ppc->sales_order_no ? 'selected="selected"' : '' }}
                                       >{{ $rowSale->sales_order_no }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 
                                 <td>
                                    <select name="color_id[]"  id="color_id" class="select2" style="width:100px;" required  >
                                       <option value="">--Color--</option>
                                       @foreach($ColorList as  $rowColor)
                                       {
                                       <option value="{{ $rowColor->color_id }}"
                                       {{ $rowColor->color_id == $ppc->color_id ? 'selected="selected"' : '' }}
                                       >{{ $rowColor->color_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 
                                 <td>
                                    <select name="line_id[]"  id="line_id" class="select2" style="width:100px;" required  >
                                       <option value="">--Line No.--</option>
                                       @foreach($LineList as  $rowline)
                                       {
                                       <option value="{{ $rowline->line_id }}"
                                       {{ $rowline->line_id == $ppc->line_id ? 'selected="selected"' : '' }}
                                       >{{ $rowline->line_name }}</option>
                                       }
                                       @endforeach
                                       @php 
                                            $class= "startDate";
                                       @endphp
                                    </select>
                                 </td>
                                 <td><input type="number" step="any"    name="machine_count[]" value="{{$ppc->machine_count}}" id="machine_count" style="width:80px;" required />
                                    <input type="hidden" step="any"    name="sr_no[]" value="{{$ppc->sr_no}}" id="sr_no" style="width:80px;" required />
                                 </td>
                                 <td><input type="number"  step="any"   name="available_mins[]" value="{{$ppc->available_mins}}" id="available_mins" style="width:80px;" required /></td>
                                 <td><input type="number"  step="any"   name="line_efficiency[]" value="{{$ppc->line_efficiency}}" id="line_efficiency" style="width:80px;" required /></td>
                                 <td><input type="number"  step="any"   name="sam[]" value="{{$ppc->sam}}" id="sam" style="width:80px;" required /></td>
                                 <td><input type="number"  step="any"   name="production_capacity[]" value="{{$ppc->production_capacity}}" id="production_capacity" style="width:80px;" required /></td>
                                 <td><input type="number"  step="any"   name="target[]" value="{{$ppc->target}}" id="target" style="width:80px;" required /></td>
                                 <td><input type="date" name="start_date[]" class="{{$class}}" value="{{$ppc->start_date}}" style="width:110px;" required /></td>
                                 <td><input type="date" name="end_date[]" value="{{$ppc->end_date}}" style="width:110px;" required /></td>
                                 <td><input type="button" class="btn btn-warning pull-left" onclick="insertcone(this);" value="+" > <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                              </tr>
                              @endforeach
                              @php 
                              }
                              else
                              {
                              @endphp
                              <tr>
                                 <td><input type="text" name="id[]" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
                                 <td>
                                    <select name="vendorId[]"  id="vendorId" style="width:250px;" class="select2" required onchange="GetLineList(this);" >
                                       <option value="">--Vendors--</option>
                                       @foreach($Ledger as  $rowvendor)
                                       {
                                       <option value="{{ $rowvendor->ac_code }}"
                                          >{{ $rowvendor->ac_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="sales_order_no[]"  id="sales_order_no" class="select2" style="width:100px;" required onchange="GetColorList(this);" >
                                       <option value="">--Sales Order No.--</option>
                                       @foreach($SalesList as  $rowSales)
                                       {
                                       <option value="{{ $rowSales->sales_order_no }}"
                                          >{{ $rowSales->sales_order_no }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 
                                 <td>
                                    <select name="color_id[]"  id="color_id" class="select2" style="width:100px;" required  >
                                       <option value="">--Color--</option>
                                       @foreach($ColorList as  $rowColor)
                                       {
                                       <option value="{{ $rowColor->color_id }}"
                                          >{{ $rowColor->color_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="line_id[]"  id="line_id" style="width:100px;" class="select2" required  >
                                       <option value="">--Line No.--</option>
                                       @foreach($LineList as  $rowline)
                                       {
                                       <option value="{{ $rowline->line_id }}"
                                          >{{ $rowline->line_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="text" name="machine_count[]" value="0" id="machine_count" style="width:80px;" required /></td>
                                 <td><input type="text" name="available_mins[]" value="0" id="available_mins" style="width:80px;" required /></td>
                                 <td><input type="text" name="line_efficiency[]" value="0" id="line_efficiency" style="width:80px;" required /></td>
                                 <td><input type="text" name="sam[]" value="0" id="sam" style="width:80px;" required /></td>
                                 <td><input type="text" name="production_capacity[]" value="0" id="production_capacity" style="width:80px;" required /></td>
                                 <td><input type="text" name="target[]" value="0" id="target" style="width:80px;" required /></td>
                                 <td><input type="date" name="start_date[]" class="startDate" style="width:110px;" required /></td>
                                 <td><input type="date" name="end_date[]"  style="width:110px;" required /></td>
                                 <td>
                                    <input type="button" class="btn btn-warning pull-left" onclick="insertcone(this);" value="+" >
                                    <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" >
                                 </td>
                              </tr>
                              @php 
                              }
                              @endphp
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Vendor Name</th>
                                 <th>Sales Order</th>
                                 <th>Color</th>
                                 <th>Line No</th>
                                 <th>No. of M/C</th>
                                 <th>Total Available Minutes</th>
                                 <th>Line Efficiency</th>
                                 <th>SAM</th>
                                 <th>Production Capacity</th>
                                 <th>Target</th>
                                 <th>Start Date</th>
                                 <th>End Date</th>
                                 <th>Add/Remove </th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
                     <a href="{{ Route('PPCMaster.index') }}" class="btn btn-warning w-md">Cancel</a>
                     @php
                     if($EndDate != "")
                     {
                     @endphp
                        <a target="_blank" href="PPCCalendarReport/{{ $calStartDate }}/{{$EndDate1}}/{{$searchVendorId}}/{{$searchLineId}}" class="btn btn-info w-md">Calendar</a>
                     @php
                     }
                     @endphp
                  </div>
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
    $(document).on("change", 'input[class^="startDate"]', function (event) 
    {
        var p_startDate = new Date($(this).val());
        var production_capacity = $(this).closest("tr").find('td:nth-child(10) input').val();
        var target = $(this).closest("tr").find('td:nth-child(11) input').val();
        var endDateObj = $(this).closest("tr").find('td:nth-child(13) input');
        var endDate = new Date($(this).closest("tr").find('td:nth-child(13) input').val());
        var startDate = new Date($(this).val());
        var divident = (target/production_capacity);
        var d = String(divident).split('.');  
        var whole = d[0];
        var fraction = d[1];
        var totalDays = 0;
        
        if(fraction > 0)
        { 
            totalDays = parseInt(whole);
        }
        else
        {
             totalDays = whole;
        }
        console.log(endDateObj);
        startDate.setDate(startDate.getDate() + totalDays);
        var dd = startDate.getDate();
        var mm = startDate.getMonth() + 1;
        var y =  startDate.getFullYear();
       
        if (mm < 10)
        {
          mm= '0'+mm;
        }
        if (dd <10)
        {
          dd = '0'+dd;
        } 
      
        var FormattedDate = y + '-' + mm + '-' + dd;
      
        $(endDateObj).attr("value", FormattedDate);
     
        $(".startDate").each(function( index ) 
        {
            
            var p_capacity = $(this).closest("tr").find('td:nth-child(10) input').val();
            var p_target = $(this).closest("tr").find('td:nth-child(11) input').val();
            var p_endDateObj = $(this).closest("tr").find('td:nth-child(13) input');
            var p_endDate = new Date($(this).closest("tr").find('td:nth-child(13) input').val());
            //var p_startDate = new Date($(this).closest("tr").prev("tr").find('td:nth-child(11) input').val());
           
            var sd = p_startDate.getDate();
            var sm = p_startDate.getMonth() + 1;
            var sy =  p_startDate.getFullYear();
            if (sm < 10)
            {
               sm= '0'+sm;
            }
            if (sd < 10)
            {
               sd = '0'+sd;
            } 
            var p_SFDate = sy + '-' + sm + '-' + sd;
            $(this).val(p_SFDate);
            
            var p_divident = (p_target/p_capacity);
            var p_d = String(p_divident).split('.');  
            var p_whole = p_d[0];
            var p_fraction = p_d[1];
            var p_totalDays = 0;
             
            if(p_fraction > 0)
            { 
                p_totalDays = parseInt(p_whole);
            }
            else
            {
                 p_totalDays = p_whole;
            }
               
            p_startDate.setDate(p_startDate.getDate() + p_totalDays);
           
            var p_dd = p_startDate.getDate();
            var p_mm = p_startDate.getMonth() + 1;
            var p_y =  p_startDate.getFullYear();
            
            if (p_mm < 10)
            {
              p_mm= '0'+p_mm;
            }
            if (p_dd <10)
            {
              p_dd = '0'+p_dd;
            } 
            var p_FormattedDate = p_y + '-' + p_mm + '-' + p_dd;
          
          
            $(p_endDateObj).attr("value", p_FormattedDate);  
        });
     
    });
    
    function changeDate(obj)
    {
    
       
    }
   $('table.footable_3').on('keyup', 'input[name^="machine_count[]"],input[name^="available_mins[]"],input[name^="line_efficiency[]"],input[name^="sam[]"]', function()
   {
   // alert();
   CalculateQtyRowPro($(this).closest("tr"));
   
   });
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
   
   function GetColorList(ele)
   {    
        var tr_code = $(ele).val();
        var ele = $(ele).closest('tr'); 
        
        $.ajax({
            dataType: "json",
            url: "{{ route('GetSaleOrderWiseColorList') }}",
            data:{'tr_code':tr_code},
            success: function(data){
            ele.find('select[name^="color_id[]"]').html(data.html);
           }
        });
   }
   
   
   function EnableFields()
   {
             
         document.getElementById('mainstyle_id').disabled=false;
         document.getElementById('substyle_id').disabled=false;
         document.getElementById('fg_id').disabled=false;
         document.getElementById('style_description').disabled=false;
         document.getElementById('style_no').disabled=false;
         document.getElementById('vendorId').disabled=false;
         $("select").prop('disabled', false);
   }
   
   
   function getColorBalanceData()
   {
    var part_id=$('#part_id').val();
    var vpo_code=$('#vpo_code').val().join("','");
    var item_code=$("#item_code").val().join("','");
   
    
    $.ajax({
                type: "GET",
                url: "{{ route('SizeBalanceList') }}",
                data:{'vpo_code':vpo_code,'item_code':item_code,'part_id':part_id},
                success: function(response){
                $("#endData").html(response.html);
                recalcIdcone();
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
          $(this).closest("tr").find('select[name="color_id[]"]').select2();
         });
     }, 1000);
   }
   
   var indexcone = 2;
   function insertcone(obj)
   {
       var available_mins = $(obj).closest("tr").find('td:nth-child(5) input').val();
       var endDate = $(obj).closest("td").prev("td").find('input').val();
       console.log(available_mins);
       $("#vendorId").select2("destroy");
       $("#line_id").select2("destroy");
       $("#sales_order_no").select2("destroy");
       $("#color_id").select2("destroy");
   
       var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
       var row=table.insertRow(table.rows.length);
       
       var cell1=row.insertCell(0);
       var t1=document.createElement("input");
       t1.style="display: table-cell; width:50px;";
       t1.id = "id"+indexcone;
       t1.name= "id[]";
       t1.value=indexcone;
       cell1.appendChild(t1);
       
       var cell2 = row.insertCell(1);
       var t2=document.createElement("select");
       var x = $("#vendorId"),
       y = x.clone();
       y.attr("id","vendorId"+indexcone);
       y.attr("name","vendorId[]");
       y.width(250);
       y.appendTo(cell2);
       
       
       var cell3 = row.insertCell(2);
       var t3=document.createElement("select");
       var x = $("#sales_order_no"),
       y = x.clone();
       y.attr("id","sales_order_no"+indexcone);
       y.attr("name","sales_order_no[]");
       y.width(100);
       y.appendTo(cell3);
       
       
       var cell4 = row.insertCell(3);
       var t4=document.createElement("select");
       var x = $("#color_id"),
       y = x.clone();
       y.attr("id","color_id"+indexcone);
       y.attr("name","color_id[]");
       y.width(100);
       y.appendTo(cell4);
       
       
       var cell5 = row.insertCell(4);
       var t5=document.createElement("select");
       var x = $("#line_id"),
       y = x.clone();
       y.attr("id","line_id"+indexcone);
       y.attr("name","line_id[]");
       y.width(100);
       y.appendTo(cell5);
       
       var cell6 = row.insertCell(5);
       var t6=document.createElement("input");
       t6.style="display: table-cell; width:80px;";
       t6.type="text";
       t6.required="true";
       t6.id = "machine_count"+indexcone;
       t6.name="machine_count[]";
       t6.value="0";
       //t5.setAttribute("onkeyup", "mycalc();");
       cell6.appendChild(t6);
       
       var t6=document.createElement("input");
       t6.style="display: table-cell; width:80px;";
       t6.type="hidden";
       t6.required="true";
       t6.id = "sr_no"+indexcone;
       t6.name="sr_no[]";
       t6.value="0";
       //t5.setAttribute("onkeyup", "mycalc();");
       cell6.appendChild(t6);
       
       
       var cell7 = row.insertCell(6);
       var t7=document.createElement("input");
       t7.style="display: table-cell; width:80px;";
       t7.type="text";
       t7.required="true";
       t7.id = "available_mins"+indexcone;
       t7.name="available_mins[]";
       t7.value= available_mins;
       //t5.setAttribute("onkeyup", "mycalc();");
       cell7.appendChild(t7);
       
       var cell8 = row.insertCell(7);
       var t8=document.createElement("input");
       t8.style="display: table-cell; width:80px;";
       t8.type="text";
       t8.required="true";
       t8.id = "line_efficiency"+indexcone;
       t8.name="line_efficiency[]";
       t8.value="0";
       //t8.setAttribute("onkeyup", "mycalc();");
       cell8.appendChild(t8);
       
       
       var cell9 = row.insertCell(8);
       var t9=document.createElement("input");
       t9.style="display: table-cell; width:80px;";
       t9.type="text";
       t9.required="true";
       t9.id = "sam"+indexcone;
       t9.name="sam[]";
       t9.value="0";
       //t9.setAttribute("onkeyup", "mycalc();");
       cell9.appendChild(t9);
       
       var cell10 = row.insertCell(9);
       var t10=document.createElement("input");
       t10.style="display: table-cell; width:80px;";
       t10.type="text";
       t10.required="true";
       t10.id = "production_capacity"+indexcone;
       t10.name="production_capacity[]";
       t10.value="0";
       //t10.setAttribute("onkeyup", "mycalc();");
       cell10.appendChild(t10);
       
       var cell11 = row.insertCell(10);
       var t11=document.createElement("input");
       t11.style="display: table-cell; width:80px;";
       t11.type="text";
       t11.required="true";
       t11.id = "target"+indexcone;
       t11.name="target[]";
       t11.value="0";
       //t11.setAttribute("onkeyup", "mycalc();");
       cell11.appendChild(t11);
       
       var cell12 = row.insertCell(11);
       var t12=document.createElement("input");
       t12.style="display: table-cell; width:110px;";
       t12.type="date";
       t12.required="true";
       t12.id = "start_date"+indexcone;
       t12.name="start_date[]";
       t12.className="startDate";
       t12.value = endDate;
       cell12.appendChild(t12);
       
       
       var cell13 = row.insertCell(12);
       var t13=document.createElement("input");
       t13.style="display: table-cell; width:110px;";
       t13.type="date";
       t13.required="true";
       t13.id = "end_date"+indexcone;
       t13.name="end_date[]";
       cell13.appendChild(t13);
       
       var cell14=row.insertCell(13);
       var btnAdd = document.createElement("INPUT");
       btnAdd.id = "Abutton";
       btnAdd.type = "button";
       btnAdd.className="btn btn-warning pull-left";
       btnAdd.value = "+";
       btnAdd.setAttribute("onclick", "insertcone(this)");
       cell14.appendChild(btnAdd);
       
       
       var btnRemove = document.createElement("INPUT");
       btnRemove.id = "Dbutton";
       btnRemove.type = "button";
       btnRemove.className="btn btn-danger pull-left";
       btnRemove.value = "X";
       btnRemove.setAttribute("onclick", "deleteRowcone(this)");
       cell14.appendChild(btnRemove);
       
       
       var w = $(window);
       var row = $('#footable_3').find('tr').eq(indexcone);
       
       if (row.length){
       $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
       }
       
       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
       
       indexcone++;
       recalcIdcone();
       selselect();
   }
   
   
   
   function deleteRowcone(btn) {
   
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
   
   
   }
   
   function recalcIdcone(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
</script>
<!-- end row -->
@endsection