@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">FG Location Transfer Inward Edit</h4>
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
            @if(isset($FGLocationTransferInwardMasterList))
            <form action="{{ route('FGLocationTransferInward.update',$FGLocationTransferInwardMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fglti_date" class="form-label">Entry Date</label>
                        <input type="date" name="fglti_date" class="form-control" id="fglti_date" value="{{$FGLocationTransferInwardMasterList->fglti_date}}" readOnly>
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FGLocationTransferInwardMasterList->c_code }}">
                        <input type="hidden" name="fglti_code" class="form-control" id="fglti_code" value="{{$FGLocationTransferInwardMasterList->fglti_code}}" readOnly>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ltpki_code" class="form-label"> Outward No</label>
                        <select name="ltpki_code" class="form-control select2" id="ltpki_code" required  onchange="GetFGLocOutwardData();" >
                           <option value="">--Outward No--</option>
                           @foreach($OutwardList as  $row)
                           {
                           <option value="{{ $row->ltpki_code }}" {{ $row->ltpki_code == $FGLocationTransferInwardMasterList->ltpki_code ? 'selected="selected"' : '' }}>{{ $row->ltpki_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Sales Order No: {{$FGLocationTransferInwardMasterList->sales_order_no}}</label>
                        <select name="main_sales_order_no" class="form-control select2" id="main_sales_order_no" required  onChange="getPackingInhouseDetails(this.value);"  >
                           <option value="">--Sales Order No--</option>
                           @foreach($BuyerPurchaseOrderList as  $row)
                           {
                           <option value="{{ $row->tr_code }}"
                           {{ $row->tr_code == $FGLocationTransferInwardMasterList->sales_order_no ? 'selected="selected"' : '' }} 
                           >{{ $row->tr_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $FGLocationTransferInwardMasterList->Ac_code ? 'selected="selected"' : '' }} 
                           >{{ $row->ac_name }}</option>
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
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $FGLocationTransferInwardMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->mainstyle_name }}</option>
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
                           {{ $row->substyle_id == $FGLocationTransferInwardMasterList->substyle_id ? 'selected="selected"' : '' }}
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
                           {{ $row->fg_id == $FGLocationTransferInwardMasterList->fg_id ? 'selected="selected"' : '' }} 
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{$FGLocationTransferInwardMasterList->style_no}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{$FGLocationTransferInwardMasterList->style_description}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="loc_id" class="form-label">From Location</label>
                        <select name="from_loc_id" class="form-control" id="from_loc_id" required>
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $FGLocationTransferInwardMasterList->from_loc_id ? 'selected="selected"' : '' }} 
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="loc_id" class="form-label">To Location</label>
                        <select name="to_loc_id" class="form-control" id="to_loc_id" required>
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $FGLocationTransferInwardMasterList->to_loc_id ? 'selected="selected"' : '' }} 
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <input type="hidden" value="{{count($FGLocationTransferInwardDetailList)}}" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
               <div class="row">
                  <div class="">
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
                                       <div class="table-responsive">
                                          <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Color</th>
                                                   @foreach ($SizeDetailList as $sz) 
                                                   <th>{{$sz->size_name}}</th>
                                                   @endforeach
                                                   <th>Total Qty</th>
                                                </tr>
                                             </thead>
                                             <tbody id="CartonData">
                                                @if(count($FGLocationTransferInwardDetailList)>0)
                                                @php $no=1;$n=1; @endphp
                                                @foreach($FGLocationTransferInwardDetailList as $List) 
                                                <tr>
                                                   <td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;height:30px;"/></td>
                                                   <td>
                                                      <input type="hidden" name="item_codef[]" value="{{$List->item_code}}" id="item_codef"  />
                                                      <select name="color_id[]"   id="color_id" style="width:350px; height:30px;" onchange="setFGLimit(this);" sales_order_no="'.$List->sales_order_no.'" size_array="'.$List->size_qty_array.'" required>
                                                         <option value="">--Color  List--</option>
                                                         @foreach($ColorList as  $row)
                                                         {
                                                         <option value="{{ $row->color_id }}"
                                                         {{ $row->color_id == $List->color_id ? 'selected="selected"' : '' }} 
                                                         >{{ $row->color_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   @php 
                                                        $n=1;  
                                                        $SizeQtyList=explode(',', $List->size_qty_array);
                                                        $SizeRateList=explode(',', $List->rate_array);
                                                        $BarcodeList=explode(',', $List->barcode_array);
                                                   @endphp
                                                   @foreach($SizeQtyList  as $key=>$szQty)
                                                   <td ><input style="width:80px; float:left;" max="{{$szQty}}" min="0" name="s@php echo $n; @endphp[]" class="size_id" type="number" id="s@php echo $n; @endphp" value="{{$szQty}}" required />  
                                                        <br/><br/><input type="number" step="any" class="size_rate" name="s@php echo $n; @endphp_rate[]" placeholder="Rate" value="{{$SizeRateList[$key]}}"  style="width:80px;background: #ff000045;" required /> 
                                                        <input type="hidden" class="barcode" name="s@php echo $n; @endphp_barcode[]"  value="{{$BarcodeList[$key]}}" /></td>
                                                   @php $n=$n+1;  @endphp
                                                   @endforeach
                                                   <td><input type="number" name="size_qty_total[]" class="size_qty_total" value="{{$List->size_qty_total}}" id="size_qty_total" style="width:80px; height:30px; float:left;"  />
                                                      <input type="hidden" name="size_qty_array[]"  value="{{$List->size_qty_array}}" id="size_qty_array" style="width:80px; float:left;"  />
                                                      <input type="hidden" name="rate_array[]"  value="{{$List->rate_array}}" id="rate_array" style="width:80px; float:left;"  />
                                                      <input type="hidden" name="size_array[]"  value="{{$List->size_array}}" id="size_array" style="width:80px;  float:left;"  />
                                                      <input type="hidden" name="barcode_array[]"  value="{{$List->barcode_array}}" id="barcode_array" style="width:80px;  float:left;"  />
                                                   </td>
                                                </tr>
                                                @php $no=$no+1;  @endphp
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
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="{{$FGLocationTransferInwardMasterList->total_qty}}" readOnly>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{$FGLocationTransferInwardMasterList->narration}}" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();mycalc();" id="Submit">Submit</button>
                     <a href="{{ Route('FGLocationTransferInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- end row -->
<script>
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
               
               
                var values1 = [];
                $("#footable_2 tr td input[class='size_rate']").each(function() 
                {
                    values1.push($(this).val());
                    $(this).closest("tr").find('input[name="rate_array[]"]').val(values1); 
                });
                
               mycalc();
           }
       });
   }
   
   $(document).ready(function() {
       $('#frmData').submit(function() {
           $('#Submit').prop('disabled', true);
       }); 
        $('input,select').not('.size_rate').not('.size_id').attr('disabled', true);
   });  
   $(document).on('click', function(evt) {
       $('select').select2();
   });
   
   function setFGLimit(row)
   { 
      var color_id = $(row).val();
      $(row).parent().parent('tr').find(".size_id").val(0);
      $(row).parent().parent('tr').find(".size_qty_total").val(0);
      var sales_order_no = $('#main_sales_order_no').val();
      var size_array =  $("#footable_2 tbody tr td:nth-child(2) select").attr('size_array');  
      
     var result = size_array.split(',');
   
     $(result).each(function(i) {
         size_id = result[i];
         $.ajax({
             dataType: "json",
             url: "{{ route('FGStockSizeValue') }}",
             data:{'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
             success: function(res)
             {
                 var index = parseInt(i) + parseInt(1);
                 $(row).parent().parent('tr').find('td input[name="s'+index+'[]"]').attr('max',res);
             }
         });
     });
   }
   $(document).on("change", 'input[class^="size_id"]', function (event) 
   {
   
   var value = $(this).val();
   
            var maxLength = parseInt($(this).attr('max'));
            var minLength = parseInt($(this).attr('min')); 
   if(value>maxLength){alert('Value can not be greater than '+maxLength);}
   if ((value !== '') && (value.indexOf('.') === -1)) {
        
        
        
       $(this).val(Math.max(Math.min(value, maxLength), minLength));
   }
   
   });
   
   
   
   $(document).on("change", 'input[class^="size_id"]', function (event) 
   {
   var no=1;
   var sales_order_no = $('#sales_order_no').val();
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
       $("#sales_order_no").html(data.html);
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
    var sales_order_no=$('#main_sales_order_no').val();   //.join(",")
    $.ajax({
       dataType: "json",
       url: "{{ route('PKI_GetTransferQtyByRow') }}",
       data:{'sales_order_no':sales_order_no},
       success: function(data){
           console.log(data);
       $("#CartonData").append(data.html);
       recalcIdcone1();
        document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
       }
       });
     
   
   }
   function getPackingInhouseDetails()
   {
       var from_loc_id=$('#from_loc_id').val();
       var sales_order_no=$('#sales_order_no').val().join(",");
   //alert(sales_order_no);
   
     $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('PackingInhouseDetails') }}",
           data:{'sales_order_no':sales_order_no},
           success: function(data){
          
           $("#Ac_code").val(data[0]['Ac_code']);
           $("#mainstyle_id").val(data[0]['mainstyle_id']);
           $("#substyle_id").val(data[0]['substyle_id']);
           $("#style_no").val(data[0]['style_no']);
           $("#fg_id").val(data[0]['fg_id']);
           $("#style_description").val(data[0]['style_description']);
           $("#order_rate").val(data[0]['order_rate']);
            document.getElementById('Ac_code').disabled=true;
            document.getElementById('mainstyle_id').disabled=true;
            document.getElementById('substyle_id').disabled=true;
            document.getElementById('fg_id').disabled=true;
        
   
       // $.ajax({
       // dataType: "json",
       // url: "{{ route('FG_GetRawData') }}",
       // data:{'sales_order_no':sales_order_no,'from_loc_id':from_loc_id},
       // success: function(data){
       // $("#footable_2").html(data.html);
        
       // }
       // });
            
   }
   });
   }
   function EnableFields()
   {
        $("select").prop('disabled', false);
        $("input").prop('disabled', false);
            
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
   var sales_order_no=row.find('select[name^="sales_order_nos[]"]').val();
   // alert(sales_order_no);
   $.ajax({
       dataType: "json",
       url: "{{ route('PKI_GetColorList') }}",
       data:{'sales_order_no':sales_order_no},
       success: function(data){
        row.find('select[name^="color_id[]"]').html(data.html);
       }
       });
   
   }
   
   $(document).on('change', 'select[name^="color_id[]"]', function()
   {
   CalculateQtyRowProColor($(this).closest("tr"));
   });
   function CalculateQtyRowProColor(row)
   {   
   var main_sales_order_no=$('#main_sales_order_no').val();
   
   var sales_order_no=row.find('select[name^="sales_order_nos[]"]').val();
   var color_id=row.find('select[name^="color_id[]"]').val();
   // alert(sales_order_no);
   $.ajax({
       dataType: "json",
       url: "{{ route('LTPKI_GetMaxMinvalueList') }}",
       data:{'sales_order_no':sales_order_no,'color_id':color_id,'main_sales_order_no':main_sales_order_no},
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
   var sales_order_no=$('#sales_order_no').val();
   $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('ItemWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
           success: function(data)
           {
                   console.log(data);
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
   
   var sales_order_no=$('#sales_order_no').val();
   $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('FabricWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,sales_order_no:sales_order_no},
           success: function(data)
           {
                   console.log(data);
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
   var sales_order_no=$('#sales_order_no').val();
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
                   console.log(data);
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
   
   
   function calculateamount()
   {
   var prod_qty=document.getElementById('prod_qty').value;
   var rate_per_piece=document.getElementById('rate_per_piece').value;
   var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
   $('#total_amount').val(total_amount.toFixed(2));
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
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   function recalcIdcone2(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   function recalcIdcone3(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
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