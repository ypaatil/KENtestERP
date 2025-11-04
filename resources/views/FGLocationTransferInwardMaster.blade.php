@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">FG Location Transfer Inward</h4>
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
            <form action="{{route('FGLocationTransferInward.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vpo_date" class="form-label">Entry Date</label>
                        <input type="date" name="fglti_date" class="form-control" id="fglti_date" value="{{date('Y-m-d')}}">
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ltpki_code" class="form-label"> Outward No</label>
                        <select name="ltpki_code" class="form-control select2" id="ltpki_code" required  onchange="GetFGLocOutwardData();">
                           <option value="">--Outward No--</option>
                           @foreach($OutwardList as  $row)
                           {
                           <option value="{{ $row->ltpki_code }}">{{ $row->ltpki_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="main_sales_order_no" class="form-label"> Sales Order No</label>
                        <select name="main_sales_order_no" class="form-control select2" id="main_sales_order_no" disabled>
                           <option value="">--Sales Order No--</option>
                           @foreach($BuyerPurchaseOrderList as  $row)
                           {
                           <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled  onChange="getSalesOrderList(this.value);" >
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                           <option value="">--Main Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" disabled>
                           <option value="">--Sub Style--</option>
                           @foreach($SubStyleList as  $row)
                           {
                           <option value="{{ $row->substyle_id }}"
                              >{{ $row->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-control" id="fg_id" disabled>
                           <option value="">--Select Style--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                              >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" readOnly>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="loc_id" class="form-label">From Location</label>
                        <select name="from_loc_id" class="form-control" id="from_loc_id" disabled>
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                              >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="loc_id" class="form-label">To Location</label>
                        <select name="to_loc_id" class="form-control" id="to_loc_id" disabled>
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                              >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="default_rate" class="form-label">Rate (Default)</label>
                        <input type="number" step="any" name="default_rate" class="form-control" id="default_rate" value="" required onchange="SetDefaultRate();" >
                     </div>
                  </div>
                  <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
               </div>
               <div class="panel-group" id="accordion"> 
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">FG Location Transfer Outward</a>
                        </h4>
                     </div>
                     <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                        <div class="panel-body">
                           <div class="row">
                              <div class="table-wrap">
                                 <div class="table-responsive" id="InwardData"></div>
                              </div>
                           </div>
                        </div>
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
   <input type="text" name="total_qty" class="form-control" id="total_qty" value="" required readOnly>
   </div>
   </div> 
   <div class="col-sm-8">
   <label for="formrow-inputState" class="form-label">Narration</label>
   <div class="mb-3">
   <input type="text" name="narration" class="form-control" id="narration"  value="" />
   </div>
   </div>
   </div>
   <div class="col-sm-6">
   <label for="formrow-inputState" class="form-label"></label>
   <div class="form-group">
   <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
   <a href="{{ Route('FGLocationTransferInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- end row -->
<script>

    function SetDefaultRate()
    {
        var default_rate = $("#default_rate").val();
        $('.size_rate').attr("value",default_rate);
        var values1 = [];
        $("#footable_2 tr td input[class='size_rate']").each(function() {
            values1.push($(this).val());
            $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 
        });
    }
    
    function GetFGLocOutwardData()
    {
        $("#ltpki_code").attr("disabled", true);
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
        $(row).parent().parent('tr').find(".size_id").val(0);
        $(row).parent().parent('tr').find('input[name="size_qty_total[]"]').val(0);
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
         $("#footable_2 tr td  input[class='size_id']").each(function() {
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
       
        var values1 = [];
        $("#footable_2 tr td input[class='size_rate']").each(function() {
            values1.push($(this).val());
            $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 1
        });
       
        mycalc();
    });
   
    $(document).on("change", 'input[class^="size_rate"]', function (event) 
    {
        var values1 = [];
        $("#footable_2 tr td input[class='size_rate']").each(function() {
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
   
   
   function addNewRow()
   {
        
        var sales_order_no=$('#main_sales_order_no').val();  //.join(",")
     $.ajax({
           dataType: "json",
           url: "{{ route('LTPKI_GetTransferQtyByRow') }}",
           data:{'sales_order_no':sales_order_no},
           success: function(data){
           $("#CartonData").append(data.html);
           document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
           recalcIdcone1();
           }
           });
           
            
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
    // alert(sales_order_no);
     $.ajax({
           dataType: "json",
           url: "{{ route('FG_GetColorList') }}",
           data:{'sales_order_no':sales_order_no,'main_sales_order_no':main_sales_order_no},
           success: function(data){
            row.find('select[name^="color_id[]"]').html(data.html);
           }
           });
     
   }
   
   
   
   // This code is to change arrange color wise min max
   $(document).on('change', 'select[name^="color_id[]"]', function()
   {
       CalculateQtyRowProColor($(this).closest("tr"));
   });
   function CalculateQtyRowProColor(row)
   {   
       var main_sales_order_no=$('#main_sales_order_no').val();
    
     var color_id=row.find('select[name^="color_id[]"]').val();
     var from_loc_id=$('#from_loc_id').val();
     
    // alert(sales_order_no);
     $.ajax({
           dataType: "json",
           url: "{{ route('LTPKI_GetMaxMinvalueList') }}",
           data:{ 'color_id':color_id,'main_sales_order_no':main_sales_order_no,'from_loc_id':from_loc_id},
           success: function(data){
             for(var i=0;i<=data[0]['size_count'];i++)
             {
               row.find('input[name^="s'+i+'[]"]').attr({"max" : data[0]['s'+i+'']});
             }
             
             row.find('select[name^="item_codef[]"]').val(data[0]['item_code']);
             
             
           }
           });
     
   }
   
   
   
   
   $('table.footable_1').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   {
      // alert();
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
 

   $(document).on('click', function(evt) {
        $('select').select2();
    });

   
  
   function mycalc()
   {   
    
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('size_qty_total');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_qty").value = sum1;
   }
   
   
   function deleteRowcone1(btn) {
   if(document.getElementById('cntrr1').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr1').value = document.getElementById('cntrr1').value-1;
   
   recalcIdcone1();
   
   if($("#cntrr1").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
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
   
   recalcIdcone3();
   
   if($("#cntrr3").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
   
   function recalcIdcone1(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i);  
   })
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