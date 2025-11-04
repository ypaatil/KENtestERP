@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">FG Outlet Opening</h4>
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
            @if(isset($FGOutletOpeningList))
            <form action="{{ route('FGOutletOpening.update',$FGOutletOpeningList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fgo_date" class="form-label">Date</label>
                        <input type="date" name="fgo_date" class="form-control" id="fgo_date" value="{{$FGOutletOpeningList->fgo_date}}">
                        <input type="hidden" name="fgo_code" class="form-control" id="fgo_code" value="{{$FGOutletOpeningList->fgo_code}}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="default_rate" class="form-label">Rate (Default)</label>
                        <input type="number" step="any" name="default_rate" class="form-control" id="default_rate" value="" onchange="SetDefaultRate();" >
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label> 
                        <select name="sz_code" class="form-select" id="sz_code" required onChange="GetSizeDetailList(this.value);">
                           <option value="">--Size--</option>
                           @foreach($SizeList as  $row) 
                                <option value="{{ $row->sz_code }}" {{ $row->sz_code == $FGOutletOpeningList->sz_code ? 'selected="selected"' : '' }} >{{ $row->sz_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="table-wrap" id="divSelect">
                      <div class="table-responsive">
                         <table id="footable_1" class="table  table-bordered table-striped m-b-0  footable_1">
                            <thead>
                                <tr>
                                <th>Sr No</th>
                                <th>Buyer</th>
                                <th>Main Style</th>
                                <th>Color</th> 
                                @foreach($SizeDetailList as $sz) 
                                    <th>{{$sz->size_name}}</th>
                                @endforeach  
                                <th>Total Qty</th>  
                                <th>Add/Remove</th>
                                </tr>
                            </thead>
                            <tbody> 
                              @if(count($FGOutletOpeningDetailList)>0)
                              @php
                                    $no = 1;
                              @endphp
                                @foreach($FGOutletOpeningDetailList as $rows)
                                <tr> 
                                    <td><input type="text" name="id[]" value="{{$no++}}" id="id0" style="width:50px;"/></td>
                                    <td> 
                                        <select name="Ac_code[]" class="select2"  id="" style="width:150px; height:30px;" required>
                                            <option value="">--Select--</option> 
                                            @foreach($BuyerList as  $row2)
                                                 <option value="{{$row2->ac_code}}" {{ $row2->ac_code == $rows->Ac_code ? 'selected="selected"' : '' }} >{{$row2->ac_name}}</option>
                                            @endforeach   
                                        </select>
                                    </td> 
                                    <td> 
                                        <select name="mainstyle_id[]" class="select2"  id="" style="width:150px; height:30px;" required>
                                            <option value="">--Select--</option>
                                            @foreach($MainStyleList as  $row3)
                                                <option value="{{$row3->mainstyle_id}}" {{ $row3->mainstyle_id == $rows->mainstyle_id ? 'selected="selected"' : '' }} >{{$row3->mainstyle_name}}</option>
                                            @endforeach    
                                        </select>
                                    </td> 
                                    <td> 
                                        <select name="color_id[]" class="Garment_color select2"  id="" style="width:150px; height:30px;" onchange="SetColorBarcode(this);" required>
                                            <option value="">--Select--</option>
                                            @foreach($ColorList as  $row1)
                                                <option value="{{$row1->color_id}}"  {{ $row1->color_id == $rows->color_id ? 'selected="selected"' : '' }}  >({{$row1->color_id}}) {{$row1->color_name}}</option>
                                            @endforeach  
                                        </select>
                                    </td> 
                                       @php 
                                            $n=1;  
                                            $SizeQtyList=explode(',', $rows->size_qty_array);
                                            $SizeRateList=explode(',', $rows->rate_array);
                                            $BarcodeList=explode(',', $rows->barcode_array);
                                       @endphp
                                       @foreach($SizeQtyList  as $key=>$szQty)
                                        <td>
                                            <input style="width:80px; float:left;" max="{{$szQty}}" min="0" name="s@php echo $n; @endphp[]" class="size_id" type="number" id="s@php echo $n; @endphp" value="{{$szQty}}" required onkeyup="mycalc();" />  
                                            <br/><br/><input type="number" step="any" class="size_rate" name="s@php echo $n; @endphp_rate[]" placeholder="Rate" value="{{$SizeRateList[$key]}}"  style="width:80px;background: #ff000045;" required /> 
                                            <input type="hidden" class="barcode" name="s@php echo $n; @endphp_barcode[]"  value="{{$BarcodeList[$key]}}" />
                                        </td>
                                       @php $n=$n+1;  @endphp
                                       @endforeach
                                    <td>
                                        <input type="number" name="size_qty_total[]" class="size_qty_total QTY" value="{{$rows->size_qty_total}}" id="size_qty_total" style="width:80px; height:30px; float:left;"  />
                                        <input type="hidden" name="size_qty_array[]"  value="{{$rows->size_qty_array}}" id="size_qty_array" style="width:80px; float:left;"  />
                                        <input type="hidden" name="rate_array[]"  value="{{$rows->rate_array}}" id="rate_array" style="width:80px; float:left;"  />
                                        <input type="hidden" name="size_array[]"  value="{{$rows->size_array}}" id="size_array" style="width:80px;  float:left;"  />
                                        <input type="hidden" name="barcode_array[]"  value="{{$rows->barcode_array}}" id="barcode_array" style="width:80px;  float:left;"  />
                                    </td> 
                                    <td>
                                        <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" onclick="addNewRow(this);" class="Abutton btn btn-warning pull-left"> 
                                        <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" style="margin-left:5px;" >
                                    </td>
                                </tr>
                                @endforeach
                              @endif
                          </tbody>
                        </table> 
                    </div>
                  </div>
               </div>
         </div>
      </div>
   </div> 
   </br>
   </br>
   <!-- end row -->
   <div class="row">
   <div class="col-md-2">
   <div class="mb-3">
   <label for="total_qty" class="form-label">Total Qty</label>
   <input type="number" step="any" name="total_qty" class="form-control" id="total_qty" value="{{$FGOutletOpeningList->total_qty}}" readOnly>
   </div>
   </div> 
   <div class="col-sm-8">
   <label for="formrow-inputState" class="form-label">Narration</label>
   <div class="mb-3">
   <input type="text" name="narration" class="form-control" id="narration"  value="{{$FGOutletOpeningList->narration}}" />
   </div>
   </div>
   </div>
   <div class="col-sm-6">
   <label for="formrow-inputState" class="form-label"></label>
   <div class="form-group">
   <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
   <a href="{{ Route('FGOutletOpening.index') }}" class="btn btn-warning w-md">Cancel</a>
   </div>
   </div>
</div>
</form>
@endif
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
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
<!-- end row -->
<script>


   function SetColorBarcode(row)
   {
        var color_id = $(row).val();
       
        $(row).parent().parent('tr').find('td input[name="s1_barcode[]"]').val(color_id);
        $(row).parent().parent('tr').find('.barcode').val("");
        
        $(row).parent().parent('tr').find(".barcode").each(function(i)
        {
            var index = $(this).parent('td').index();
            var th = $('#footable_1 > thead').find('th:eq('+index+')').text(); 
            var barcode = $(this).val();
            var ap_barcode = th+color_id;
            $(this).val(ap_barcode);
        });
   }


   function GetSizeDetailList(str)
   {
       $.ajax({
           dataType: "json",
           contentType: "application/json; charset=utf-8",
           url: "{{ route('SizeOutletDetailList') }}",
           data:{'sz_code':str},
           success: function(data)
           {
                $("#divSelect").html(data.html);
            
                $(".barcode").each(function(i)
                {
                    var index = $(this).parent('td').index();
                    var th = $('#footable_1 > thead').find('th:eq('+index+')').text(); 
                    var barcode = $(this).val();
                    var ap_barcode = th+barcode;
                    $(this).val(ap_barcode);
                });
                $('.select2').select2();
                
           }
       });
   }
   
    function SetDefaultRate()
    {
        var default_rate = $("#default_rate").val();
        $('.size_rate').val(default_rate);
        
        var values1 = [];
        $("#footable_1 tr td input[class='size_rate']").each(function() 
        {
            values1.push($(this).val());
            $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 
        });
    }
    
    function GetFGLocOutwardData()
    {
        var ltpki_code = $("#ltpki_code").val();
        $.ajax({
            dataType: "json",
            url: "{{ route('GetFGLocOutwardData') }}",
            data: {'ltpki_code': ltpki_code},
            success: function(res) 
            { 
                $("#InwardData").html(res.html);
                $("#main_sales_order_no").val(res.sales_order_no).change();
                $("#from_loc_id").val(res.from_loc_id).change();
                $("#to_loc_id").val(res.to_loc_id).change(); 
                
                $("#total_qty").val(res.total_qty);
                var sizes= $('input[name="size_array[]"]').val();
                var size_array = sizes.split(',');
                var values = [];
                $("#footable_2 tr td  input[class='size_id']").each(function() 
                {
                    values.push($(this).val());
                    if(values.length==size_array.length)
                    {
                         
                       $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
                       // alert(values);
                           var sum = values.reduce(function( a,  b){
                               return parseInt(a) + parseInt(b);
                           }, 0);
                       $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
                        
                           values = [];
                     }
                      
                });
                
                $(".barcode").each(function(i)
                {
                    var index = $(this).parent('td').index();
                    var th = $('#footable_2 > thead').find('th:eq('+index+')').text(); 
                    var barcode = $(this).val();
                    var ap_barcode = th+barcode;
                    $(this).val(ap_barcode);
                });
                
                var values1 = [];
                $("#footable_2 tr td input[class='size_rate']").each(function() 
                {
                    values1.push($(this).val());
                    $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 
                });
        
                getPackingInhouseDetails();
                mycalc();
            }
        });
    }
    
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        
        mycalc();
    });  
    
    function checkNumber(row) 
    { 
        var max = parseFloat($(row).attr('max')); 
        var inputNumber =  parseFloat($(row).val());
        console.log(max);
        if (inputNumber > max) {
            alert(`The number exceeds the maximum value of ${max}`);
            $(row).val(max);
        } 
    }
    
    function setFGLimit(row) 
    { 
        var color_id = $(row).val();
        var sales_order_no = $('#main_sales_order_no').val();
        var size_array = $("#footable_2 tbody tr td:nth-child(2) select").attr('size_array');  
        var result = size_array.split(',');
    
        $(result).each(function(i) {
            var size_id = result[i];
            $.ajax({
                dataType: "json",
                url: "{{ route('FGStockSizeValue') }}",
                data: {'sales_order_no': sales_order_no, 'color_id': color_id, 'size_id': size_id},
                success: function(res) {
                    var index = parseInt(i) + 1;
                    var inputField = $(row).closest('tr').find('td input[name="s' + index + '[]"]');
                    
                    inputField.attr('max', res); // Set the max attribute
                    
                    inputField.attr('onchange', 'checkNumber(this);');
                    // Set the res value inside the td after the input field
                    inputField.closest('td').append(res);
                }
            });
        });
    }

   
    $(document).on("change", 'input[class^="size_id"]', function (event) 
    {
      var no=1;
       var sales_order_no = $('#main_sales_order_no').val();
       var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
       var size_array = sizes.split(',');
      
         var values = [];
         $("#footable_1 tr td  input[class='size_id']").each(function() {
         values.push($(this).val());
         if(values.length==size_array.length)
         {
             
           $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
           // alert(values);
               var sum = values.reduce(function( a,  b){
                   return parseInt(a) + parseInt(b);
               }, 0);
           $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
         }
          
               mycalc();
       });
       
        var values1 = [];
        $("#footable_1 tr td input[class='size_rate']").each(function() {
            values1.push($(this).val());
            $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 1
        });
       
        mycalc();
    });
   
    $(document).on("change", 'input[class^="size_rate"]', function (event) 
    {
        var values1 = [];
        $("#footable_1 tr td input[class='size_rate']").each(function() {
            values1.push($(this).val());
            $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 
        });
    });
      
   function getSalesOrderList(Ac_code)
   {
           $.ajax({
           dataType: "json",
           url: "{{ route('NewSalesOrderList') }}",
           data:{'Ac_code':Ac_code},
           success: function(data){
           $("#main_sales_order_no").html(data.html);
           }
           });
           
           $.ajax({
           dataType: "json",
           url: "{{ route('BuyerLocationList') }}",
           data:{'Ac_code':Ac_code},
           success: function(data){
           $("#buyer_location_id").html(data.html);
           }
           });
   }
   
   
    function recalcIdcone()
    {
       $.each($("#footable_1 tr"),function (i,el){
       $(this).find("td:first input").val(i);  
       })
    }
   
    function addNewRow(row) 
    {
        $('select').select2('destroy');
        var $newRow = $(row).closest('tr').clone();
    
        // Reset fields in the cloned row 
        $newRow.find('.size_id').val(0);
        $newRow.find('.QTY').val(0);
        $newRow.find('input').not('.size_rate').not('.size_rate').not('input[name="size_array[]"]').val(0);
    
        // Remove existing select2 instances to avoid conflicts
        $newRow.find('select').select2('destroy').end(); 
    
        // Append the new row to the table
        $('#footable_1 tbody').append($newRow);
    
        // Initialize select2 for the newly appended row
        $newRow.find('select').select2(); 
    
        // Any additional functions for ID recalculations
        recalcIdcone();
    }

   function getPackingInhouseDetails()
   {
        var from_loc_id=$('#from_loc_id').val();
       var sales_order_no=$('#main_sales_order_no').val();
           //alert(sales_order_no);
   
           $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('FGPackingInhouseDetails') }}",
               data:{'sales_order_no':sales_order_no},
               success: function(data){
              
               $("#Ac_code").val(data[0]['Ac_code']);
               $("#mainstyle_id").val(data[0]['mainstyle_id']);
               $("#substyle_id").val(data[0]['substyle_id']);
               $("#style_no").val(data[0]['style_no']);
               $("#fg_id").val(data[0]['fg_id']);
               $("#style_description").val(data[0]['style_description']);
                document.getElementById('Ac_code').disabled=true;
                document.getElementById('mainstyle_id').disabled=true;
                document.getElementById('substyle_id').disabled=true;
                document.getElementById('fg_id').disabled=true;
           }
           });
    
   
        //   $.ajax({
        //   dataType: "json",
        //   url: "{{ route('LTFG_GetRawData') }}",
        //   data:{'sales_order_no':sales_order_no },
        //   success: function(data){
        //       $("#footable_2").html(data.html);
        //       $("#size_array").html(data.size_array);
        //       $('select').select2();
        //   }
        //   });
           
          
           
           
   }
   
   
           
   function EnableFields()
   {
       $("select").prop('disabled', false);
   }
   
   
   
    
    $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
       
       mycalc();
   
   });
    
   $(document).on('change', 'select[name^="sales_order_nos[]"]', function()
   {
       CalculateQtyRowProxx($(this).closest("tr"));
   });
   
   function CalculateQtyRowProxx(row)
   {   
        var main_sales_order_no=$('#main_sales_order_no').val();
        var sales_order_no=row.find('select[name^="sales_order_nos[]"]').val(); 
        $.ajax({
           dataType: "json",
           url: "{{ route('FG_GetColorList') }}",
           data:{'sales_order_no':sales_order_no,'main_sales_order_no':main_sales_order_no},
           success: function(data)
           {
                row.find('select[name^="color_id[]"]').html(data.html);
           }
        });
     
   }
  
   
   $('table.footable_1').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   { 
         CalculateQtyRowPro($(this).closest("tr"));
   });
   function CalculateQtyRowPro(row)
   {   
    var consumption=+row.find('input[name^="consumption[]"]').val();
    var wastage=+row.find('input[name^="wastage[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
    var bom_qty=+row.find('input[name^="bom_qty[]"]').val();
   
   //var bom_qty1=(bom_qty + (bom_qty*(wastage/100))).toFixed(4);
    
   var total_price=(bom_qty*rate_per_unit).toFixed(2);
   //row.find('input[name^="bom_qty[]"]').val(bom_qty1);
   row.find('input[name^="total_amount[]"]').val(total_price);
   mycalc();
   }
   
   
   $(document).on('change', 'select[name^="item_codes[]"],select[name^="color_ids[][]"],select[name^="size_ids[][]"]', function()
   {CalculateQtyRowPros2($(this).closest("tr"));});
   function CalculateQtyRowPros2(row)
   {   
       var item_code=+row.find('select[name^="item_codes[]"]').val();
       var color_id=row.find('select[name^="color_ids[][]"]').val().join(",");
       var size_id=row.find('select[name^="size_ids[][]"]').val().join(",");
       row.find('input[name^="color_arrays[]"]').val(color_id);
       row.find('input[name^="size_arrays[]"]').val(size_id);
       var sales_order_no=$('#main_sales_order_no').val();
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('ItemWiseSalesOrderCosting') }}",
               data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
               success: function(data)
               {
                       row.find('input[name^="descriptions[]"]').val(data[0]['description']);
                       row.find('input[name^="consumptions[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastages[]"]').val(data[0]['wastage']);
                       row.find('input[name^="rate_per_units[]"]').val(data[0]['rate_per_unit']);
                      
                       row.find('select[name^="class_ids[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                       var bom_qty=data[0]['bom_qty'];
                       
                   //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                      var total_amount=(bom_qty*data[0]['rate_per_unit']).toFixed(4)
                       row.find('input[name^="bom_qtys[]"]').val(bom_qty);
                       row.find('input[name^="total_amounts[]"]').val(total_amount);
               }
           });
   
           mycalc();
   
   }
   
   // For Fabric Trims get Consumption Details From Sales Costing Table
   $('table.footable_1').on('change', 'select[name^="item_code[]"]', function()
   {CalculateQtyRowPros1($(this).closest("tr"));});
   function CalculateQtyRowPros1(row)
   {   
       var item_code=+row.find('select[name^="item_code[]"]').val();
   //alert(item_code);
   
       var sales_order_no=$('#main_sales_order_no').val();
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('FabricWiseSalesOrderCosting') }}",
               data:{'item_code':item_code,sales_order_no:sales_order_no},
               success: function(data)
               {
                       row.find('input[name^="description[]"]').val(data[0]['description']);
                       row.find('input[name^="consumption[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastage[]"]').val(data[0]['wastage']);
                       row.find('input[name^="rate_per_unit[]"]').val(data[0]['rate_per_unit']);
                       row.find('input[name^="bom_qty[]"]').val(data[0]['bom_qty']);
                       row.find('input[name^="color_id[][]"]').val(data[0]['color_id']);
                       row.find('select[name^="class_id[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                       row.find('input[name^="total_amount[]"]').val(data[0]['bom_qty']*data[0]['rate_per_unit']);
               }
           });
   
           mycalc();
   }
   
   // For Packing Trims get Consumption Details From Sales Costing Table
   $(document).on('change', 'select[name^="item_codess[]"],select[name^="color_idss[][]"],select[name^="size_idss[][]"]', function()
   {CalculateQtyRowPros3($(this).closest("tr"));});
   function CalculateQtyRowPros3(row)
   {   
       var item_code=+row.find('select[name^="item_codess[]"]').val();
       var sales_order_no=$('#main_sales_order_no').val();
       var color_id=row.find('select[name^="color_idss[][]"]').val().join(",");
       var size_id=row.find('select[name^="size_idss[][]"]').val().join(",");
       
   
       row.find('input[name^="color_arrayss[]"]').val(color_id);
       row.find('input[name^="size_arrayss[]"]').val(size_id);
   
      $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('PackingWiseSalesOrderCosting') }}",
               data:{'item_code':item_code,sales_order_no:sales_order_no,'color_id':color_id,'size_id':size_id},
               success: function(data)
               {
                       row.find('input[name^="descriptionss[]"]').val(data[0]['description']);
                       row.find('input[name^="consumptionss[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastagess[]"]').val(data[0]['wastage']);
                       row.find('select[name^="class_idss[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_idss[]"]').val(data[0]['unit_id']);
                       row.find('input[name^="rate_per_unitss[]"]').val(data[0]['rate_per_unit']);
                     // alert(data[0]['bom_qty']);
                       var bom_qty=parseFloat(data[0]['bom_qty']);
                       // var wastage=parseFloat(data[0]['wastage']);
                   //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                      var rate=data[0]['rate_per_unit'];
                      var total_amount=(bom_qty*rate).toFixed(4);
                       row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
                       row.find('input[name^="total_amountss[]"]').val(total_amount);
               }
           });
   
           mycalc();
   
   }
  
  
     
    $('table.footable_4').on("keyup", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"]', function()
   {
      // alert();
   CalculateQtyRowPross($(this).closest("tr"));
   
   });
   function CalculateQtyRowPross(row)
   {   
    
    var consumption=+row.find('input[name^="consumptionss[]"]').val();
    var wastage=+row.find('input[name^="wastagess[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
    var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
     
   //  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
    var total_price=(bom_qty*rate_per_unit).toFixed(2);
   //  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
    
    row.find('input[name^="total_amountss[]"]').val(total_price);
    mycalc();
   }
 

//   $(document).on('click', function(evt) {
//         $('select').select2();
//     });

   
  
   function mycalc()
   {   
       sum1 = 0;
       var amounts = document.getElementsByClassName('QTY');
       for(var i=0; i<amounts.length; i++)
       { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
       }
       console.log(sum1);
       $("#total_qty").val(sum1);
   }
   
   
   function deleteRowcone(btn)
   { 
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row); 
       
       recalcIdcone();
        	
       document.getElementById('Submit').disabled=true; 
   }
   
   
   function deleteRowcone2(btn) {
   if(document.getElementById('cntrr2').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;
    
   recalcIdcone2();
   
   if($("#cntrr2").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
   function deleteRowcone3(btn) {
   if(document.getElementById('cntrr3').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr3').value = document.getElementById('cntrr3').value-1;
   
   
   if($("#cntrr3").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
   
 
   
   
    function getSubStyle(val) 
   {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('SubStyleList') }}",
       data:'mainstyle_id='+val,
       success: function(data){
       $("#substyle_id").html(data.html);
       }
       });
   }   
        
     function getStyle(val) 
   {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('StyleList') }}",
       data:{'substyle_id':val, },
       success: function(data){
       $("#fg_id").html(data.html);
       }
       });
   }  
   
   
</script>
<!-- end row -->
@endsection