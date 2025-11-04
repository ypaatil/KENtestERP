@extends('layouts.master') 
@section('content')
<style>
   input::-webkit-outer-spin-button,
   input::-webkit-inner-spin-button {
   -webkit-appearance: none;
   margin: 0;
   }
   /* Firefox */
   input[type=number] {
   -moz-appearance: textfield;
   }
   .hide
   {
   display:none;
   }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">FG Transfer To Location Inward Edit</h4>
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
            @if(isset($FGTransferToLocationInwardList))
            <form action="{{ route('FGTransferToLocationInward.update',$FGTransferToLocationInwardList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fgti_date" class="form-label">Entry Date</label>
                        <input type="date" name="fgti_date" class="form-control" id="fgti_date" value="{{$FGTransferToLocationInwardList->fgti_date}}"  {{ $FGTransferToLocationInwardList->pki_code != NULL ? 'readonly' : '' }}>
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FGTransferToLocationInwardList->c_code }}">
                        <input type="hidden" name="endflag" class="form-control" id="endflag" value="{{ $FGTransferToLocationInwardList->endflag }}">
                        <input type="hidden" name="fgti_code" class="form-control" id="fgti_code" value="{{$FGTransferToLocationInwardList->fgti_code}}" >
                        <input type="hidden" name="pki_code" class="form-control" id="pki_code" value="{{$FGTransferToLocationInwardList->pki_code}}" >
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-1 hide">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Firm</label>
                        <select name="firm_id" class="form-control" id="firm_id">
                           <option value="">--Company--</option>
                           @foreach($FirmList as  $row)
                           {
                           <option value="{{ $row->firm_id }}"
                           {{ $row->firm_id == $FGTransferToLocationInwardList->firm_id ? 'selected="selected"' : '' }}  
                           >{{ $row->firm_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fgt_code" class="form-label">FGT Code</label>
                        <select name="fgt_code" class="form-control select2" id="fgt_code" {{ $row->pki_code != NULL ? 'required' : '' }}  onChange="getSalesOrderList(this.value);" disabled >
                           <option value="">--Select--</option>
                           @foreach($FGTCodeList as  $row)
                                <option value="{{ $row->fgt_code }}" {{ $row->fgt_code == $FGTransferToLocationInwardList->fgt_code ? 'selected="selected"' : '' }}>{{ $row->fgt_code }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $FGTransferToLocationInwardList->Ac_code ? 'selected="selected"' : '' }} 
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <label for="from_loc_id" class="form-label">From Location</label>
                     <select name="from_loc_id" class="form-control select2" id="from_loc_id" disabled>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}" {{ $row->loc_id == $FGTransferToLocationInwardList->from_loc_id ? 'selected="selected"' : '' }} >{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-3">
                     <label for="to_loc_id" class="form-label">To Location</label>
                     <select name="to_loc_id" class="form-control select2" id="to_loc_id" disabled>
                        <option value="">--Location--</option>
                        @foreach($LocationList as  $row)
                        <option value="{{ $row->loc_id }}" {{ $row->loc_id == $FGTransferToLocationInwardList->to_loc_id ? 'selected="selected"' : '' }} >{{ $row->location }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="driver_name" class="form-label">Driver Name</label>
                        <input type="text" name="driver_name" class="form-control" id="driver_name" value="{{$FGTransferToLocationInwardList->driver_name}}"disabled >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vehical_no" class="form-label">Vehicle No</label>
                        <input type="text" name="vehical_no" class="form-control" id="vehical_no" value="{{$FGTransferToLocationInwardList->vehical_no}}" disabled>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-5">
                        <label for="po_date" class="form-label">Sales Order No</label>
                        <select name="sales_order_no[]" class="form-control select2" id="sales_order_no" onChange="getPackingInhouseDetails();"  multiple style="width:100%;height:50%;" disabled>
                           <option value="">--Sales Order No--</option>
                           @php $sales_orders = explode(',', $FGTransferToLocationInwardList->sales_order_no);   @endphp
                           @foreach($BuyerPurchaseOrderList as  $row)
                           {
                           <option value="{{ $row->tr_code }}"
                           @if(in_array($row->tr_code, str_replace("'", '', $sales_orders))) selected @endif  
                           >{{ $row->tr_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <input type="hidden" name="mainstyle_id" class="form-control" id="mainstyle_id" value="{{$FGTransferToLocationInwardList->mainstyle_id}}">
                  <input type="hidden" name="substyle_id" class="form-control" id="substyle_id" value="{{$FGTransferToLocationInwardList->substyle_id}}"> 
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-control" id="fg_id">
                           <option value="">--Select Style--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                           {{ $row->fg_id == $FGTransferToLocationInwardList->fg_id ? 'selected="selected"' : '' }} 
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{$FGTransferToLocationInwardList->style_no}}">
                     </div>
                  </div>
                  <div class="col-md-4 hide">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{$FGTransferToLocationInwardList->style_description}}">
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Order Rate</label>
                        <input type="text" name="order_rate" class="form-control" id="order_rate" value="{{$FGTransferToLocationInwardList->order_rate}}">
                     </div>
                  </div>
               </div>
               <div class="row"  >
                  <div class="  "  >
                     <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">FG Transfer To Location Entry</a>
                              </h4>
                           </div>
                           <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="{{count($FGTransferToLocationInwardDetailList)}}" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th nowrap>From Carton No</th>
                                                   <th nowrap>To Carton No</th>
                                                   <th nowrap>Sales Order No</th>
                                                   <th>Color</th>
                                                   @foreach ($SizeDetailList as $sz) 
                                                   <th>{{$sz->size_name}}</th>
                                                   @endforeach
                                                   <th>Total Qty</th>
                                                   @if($FGTransferToLocationInwardList->pki_code == NULL)
                                                   <th>Add/Remove</th>
                                                   @endif
                                                </tr>
                                             </thead>
                                             <tbody id="CartonData">
                                                @if(count($FGTransferToLocationInwardDetailList)>0)
                                                @php $no=1;$n=1; @endphp
                                                @foreach($FGTransferToLocationInwardDetailList as $List) 
                                                <tr>
                                                   <td>{{$no}}</td>
                                                   <td><input type="text" name="from_carton_no[]" value="{{$List->from_carton_no}}" id="id" style="width:80px;height:30px; "/></td>
                                                   <td><input type="text" name="to_carton_no[]" value="{{$List->to_carton_no}}" id="id" style="width:80px;height:30px; "/></td>
                                                   <td>
                                                      <select name="sales_order_nos[]"   id="sales_order_nos" style="width:150px; height:30px;" disabled class="select2" onchange="CalculateQtyRowProxx(this);">
                                                         <option value="">--Sales Order--</option>
                                                         @foreach($BuyerPurchaseOrderList as  $row)
                                                         {
                                                         <option value="{{ $row->tr_code }}"
                                                         {{ $row->tr_code == $List->sales_order_no ? 'selected="selected"' : '' }} 
                                                         >{{ $row->tr_code }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <input type="hidden" name="item_codef[]" value="{{$List->item_code}}" id="item_codef"  />
                                                      <select name="color_id[]"   id="color_id" style="width:200px; height:30px;"  class="select2" onchange="ResetAllValues(this);" >
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
                                                   $n=1;   $SizeQtyList=explode(',', $List->size_qty_array)
                                                   @endphp
                                                   @foreach($SizeQtyList  as $szQty)
                                                   <td ><input style="width:80px; float:left;" max="{{$szQty}}" min="0" name="s@php echo $n; @endphp[]" class="size_id" type="number" id="s@php echo $n; @endphp" value="{{$szQty}}" required />  </td>
                                                   @php $n=$n+1;  @endphp
                                                   @endforeach
                                                   <td><input type="number" name="size_qty_total[]" class="size_qty_total" value="{{$List->size_qty_total}}" id="size_qty_total" style="width:80px; height:30px; float:left;" readonly />
                                                      <input type="hidden" name="size_qty_array[]"  value="{{$List->size_qty_array}}" id="size_qty_array" style="width:80px; float:left;"  />
                                                      <input type="hidden" name="size_array[]"  value="{{$List->size_array}}" id="size_array" style="width:80px;  float:left;"  />
                                                   </td>
                                                   @if($FGTransferToLocationInwardList->pki_code == NULL)
                                                   <td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                                                   @endif
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
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="{{$FGTransferToLocationInwardList->total_qty}}" required readOnly>
                     </div>
                  </div>
                  <input type="hidden" name="order_amount" class="form-control" id="order_amount" value="{{$FGTransferToLocationInwardList->order_amount}}" > 
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{$FGTransferToLocationInwardList->narration}}" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();mycalc();" id="Submit">Submit</button>
                     <a href="{{ Route('FGTransferToLocationInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<div id="hidden_inputs">
</div>
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- end row -->
<script>
   $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        
        $('input[type="number"]').attr("readonly", true);
        
        var uniqueSalesOrderNos = [];
        
        $("#footable_2 tbody").find('tr').each(function() {
            var index = $(this).index();
            var sales_order_no = $(this).find('td select[name="sales_order_nos[]"]').val();
            
            if (uniqueSalesOrderNos.indexOf(sales_order_no) === -1) 
            {
                $("#hidden_inputs").append('<input type="hidden" row="'+index+'" class="'+sales_order_no+'" value="'+sales_order_no+'">');
                uniqueSalesOrderNos.push(sales_order_no);
            }
        });
    });
    
    function checkDifferentSizeGroup(row)
    {
        var close_index = $(row).siblings('div').find('.select2-search-choice-close').length-1;
        if(close_index == -1)
        {
            $("#footable_2").html("");
        } 
        var li = $(row).siblings('div').find('.select2-search-choice')[close_index];
        
        var sales_order_no = $("#sales_order_no").val();   
        var selectedValues = [];
        var selectedValues1 = []; 
        
         
        $('#sales_order_no option:selected').each(function() {
          selectedValues.push($(this).val());
        });
         
        $('#hidden_inputs').find('input').each(function() {
          selectedValues1.push($(this).val());
        });
         
        var differentElements = selectedValues1.filter(function(value) {
          return selectedValues.indexOf(value) === -1;
        }); 
        
        $.each(differentElements, function(index, value) { 
           
          $("#footable_2 > tbody > tr").find("td select[name='sales_order_nos[]']").each(function() {
            if($(this).val() == value) {
              $('.' + value).remove();
              $(this).parent().parent('tr').remove();
            }
          });
        });
         
   
       var Ac_code = $("#Ac_code").val();
       var sales_order_no1 = $(li).find('div').html();
       $.ajax({
           dataType: "json",
           url: "{{ route('checkDifferentSizeGroup') }}",
           data:{'sales_order_no':sales_order_no,'Ac_code':Ac_code},
           success: function(data)
           {    
               if(data.cnt==1)
               {
                   
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: sales_order_no1+' Size Group Not Matched...!'
                })
               
                var d1 = $(row).siblings('div').find('.select2-search-choice-close');  
                var d2 = $(row).siblings('div').find('.select2-search-choice-close')[close_index];
                $(d2).trigger('click');
   
               }
               else
               {
                   getPackingInhouseDetails();
               }
           }
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
       
              mycalc();
      });
   
   //     $(document).on("change", 'input[class^="size_id"]', function (event) 
   //     {
   //       var cur_color_id = $(this).parent().parent('tr').find('td select[name="color_id[]"]').val();
   //       var cur_size_name = $(this).attr('name');
   //       var cur_size_id = $(this).val(); 
   //       var maxLength = parseFloat($(this).attr('max'));
   //       var minLength = parseFloat($(this).attr('min')); 
   //       var deducted_qty = 0;
   //       var total = 0;
       
   //       if(cur_size_id>maxLength)
   //       {
   //           alert('Value can not be greater than '+maxLength);
   //       }
      
   //       if ((cur_size_id !== '') && (cur_size_id.indexOf('.') === -1)) 
   //       {
   //           $(this).val(Math.max(Math.min(cur_size_id, maxLength), minLength));
   //       }
       
   //       $("#CartonData").find('tr').each(function()
   //       {
   //           var color_id1 = $(this).find('td select[name="color_id[]"]').val();
   //           if(color_id1 === cur_color_id)
   //           {
   //               total += parseFloat($(this).find('td input[name="'+cur_size_name+'"]').val());
   //           }
   //       });
      
   //       if(total>maxLength)
   //       {
   //           alert('Value can not be greater than '+maxLength);
   //           $(this).val(0);
   //       }
     
    
   //   });
   function getSalesOrderList(Ac_code)
   {
       
        $.ajax({
           dataType: "json",
           url: "{{ route('NewSalesOrderList') }}",
           data:{'Ac_code':Ac_code},
           success: function(data){
           $("#sales_order_no").html(data.html).select2();
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
        var sales_order_no=$('#sales_order_no').val().join(",");
                
         $.ajax({
           dataType: "json",
           url: "{{ route('PKI_GetOrdarQtyByRow') }}",
           data:{'sales_order_no':sales_order_no},
            
           success: function(data)
           { 
                $("#CartonData").append(data.html);
                var prev_row = $("#CartonData tr:last").prev();
                var prev_value = $(prev_row).find("td input[name='carton_no[]']").val();
                $("#CartonData tr:last").find("td input[name='carton_no[]']").val(parseInt(prev_value) + parseInt(1));
                $("#CartonData tr:last").find("td input[name='id']").val(parseInt(prev_value) + parseInt(1));
                $('select').not('select[name^="color_id[]"]').select2();
                $.each($("#footable_2 tr"),function (i,el){
                    $(this).find("td:first").html(i); // Simply couse the first "prototype" is not counted in the list
                });
           }
           });
   
   }
   $(document).on( "click", "select", function() 
   {  
       $(this).attr('class','select2'); 
   });
   
   function getPackingInhouseDetails()
   {
       
         
        var sales_order_no=$('#sales_order_no').val().join(",");
        var latestSelected = null;

        // Get the dropdown element
        var $select = $('#sales_order_no');
    
        // Get currently selected values
        var selectedValues = $select.val();
    
        // Find the most recently selected option (not previously selected)
        $select.find('option').each(function() {
            if (this.selected && !$(this).data('wasSelected')) {
                latestSelected = this.value;
            }
        });
    
        // Store current state for future comparison
        $select.find('option').each(function() {
            $(this).data('wasSelected', this.selected);
        });
          
        console.log(latestSelected);
   
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
               }
         
            });
     
   
           
            var rowCount = countRows('footable_2');
            
            $.ajax({
                dataType: "json",
                cache: false,
                t: new Date().getTime(),
                url: "{{ route('PKI_GetOrderQty1') }}",
                data: {'sales_order_no': sales_order_no, 'rowCount': rowCount, 'latestSelected': latestSelected},
                success: function(data) 
                {
                    var newOptions = data.html;
                    var sz_code = data.sz_code;
                    $("#footable_2").append(data.html); 
                    $(".fg_select_" + sz_code).html(data.html1);
                }
            });
    }
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
   }
    
    
    $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
       
       mycalc();
   
   });
   
   
   //   $('select[name="sales_order_nos[]"]').on('change', function()
   //   {
   //          console.log("hii");
   //       CalculateQtyRowProxx($(this).closest("tr"));
         
   //   });
   function CalculateQtyRowProxx(ele)
   {   
       
       var row = $(ele).parent().parent('tr');
       var sales_order_no=row.find('select[name^="sales_order_nos[]"]').val();
   
    // alert(sales_order_no);
     $.ajax({
           dataType: "json",
           url: "{{ route('PKI_GetColorList') }}",
           data:{'sales_order_no':sales_order_no},
           success: function(data){
            row.find('select[name^="color_id[]"]').html(data.html).select2();
           // $(this).select2();  
           }
           });
     
   }
   
   //   $(document).on('change', 'select[name^="color_id[]"]', function()
   //   {
   //       CalculateQtyRowProColor($(this).closest("tr"));
   //   });
   
   function ResetAllValues(ele)
   {
       
        $(ele).parent().parent('tr').find(".size_id").each(function()
        {
            $(this).val(0);  
        });
        
       
        $(ele).parent().parent('tr').find(".size_qty_total").each(function()
        {
            $(this).val(0);  
        });
   }
   
   function CalculateQtyRowProColor(ele)
   {   
        
        var row = $(ele).parent().parent('tr');
        var sales_order_no=row.find('select[name^="sales_order_nos[]"]').val();
        var color_id=row.find('select[name^="color_id[]"]').val();
        var Ac_code = $("#Ac_code").val();
        
        if($("#isRTV").is(":checked"))
        {
             var isRTV = 1;
        }
        else
        {
             var isRTV = 0;
        }
     
        $.ajax({
           dataType: "json",
           url: "{{ route('PKI_GetMaxMinvalueList') }}",
           data:{'sales_order_no':sales_order_no,'color_id':color_id,'isRTV':isRTV,'Ac_code':Ac_code},
           success: function(data){
             for(var i=0;i<=data[0]['size_count'];i++)
             {
               if(data[0]['s'+i+''] < 1)
               {
                   var maxcount = 0;
               }
               else
               {
                    var maxcount = data[0]['s'+i+''];
               }
               
               row.find('input[name^="s'+i+'[]"]').attr({"max" : data[0]['s'+i+'']});
             }
             
             row.find('select[name^="item_codef[]"]').val(data[0]['item_code']);
             
             
           }
        }); 
        row.find('select[name^="sales_order_nos[]"]').attr('disabled', true);
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
   
    
   
   //  $('#footable_3').on('change', '.item_sewing_trims', function() 
   //  {
   //   var item_code = $(this).val();
       
   //   var row2 = $(this).closest('tr'); // get the row
   //     $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('ItemDetails') }}",
   //       data:{item_code:item_code},
   //         success: function(data2){
   
   //              console.log(data2); 
                
   //                 +row2.find('input[name^="unit_ids[]"]').val(data2[0]['unit_id']);
   //                  +row2.find('input[name^="descriptions[]"]').val(data2[0]['item_description']);
   //               // row2.find('select[name^="descriptions[]"]').attr('value', data[0]['item_description']);
                   
   //          }
   //         });
   
   // });
   // For Sewing Trims get Consumption Details From Sales Costing Table
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
   // $('#footable_4').on('change', '.item_packing_trims', function() 
   //  {
   //   var item_code = $(this).val();
     
   //   var row1 = $(this).closest('tr'); // get the row
   //     $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('ItemDetails') }}",
   //       data:{item_code:item_code},
   //         success: function(data1){
   
   //              console.log(data1); 
                
   //                 +row1.find('input[name^="unit_idss[]"]').val(data1[0]['unit_id']);
   //                  +row1.find('input[name^="descriptionss[]"]').val(data1[0]['item_description']);
   //               // row1.find('select[name^="descriptionss[]"]').attr('value', data[0]['item_description']);
                  
   //          }
   //         });
   
   // });
    
     
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
   
    
    
   var indexcone = 2;
   function insertcone1(){
   
   var table=document.getElementById("footable_1").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1"; 
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.disabled=true;
   t1.value=indexcone;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   y.attr("id","item_code");
   y.attr("name","item_code[]");
   y.width(200);
   y.appendTo(cell3);
      
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_id"),
   y = x.clone();
   y.attr("id","class_id");
   y.attr("name","class_id[]");
   y.width(200);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "description"+indexcone;
   t5.name="description[]";
   cell5.appendChild(t5); 
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "consumption"+indexcone;
   t5.name="consumption[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#unit_id"),
   y = x.clone();
   y.attr("id","unit_id");
   y.attr("name","unit_id[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_unit"+indexcone;
   t5.name="rate_per_unit[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastage"+indexcone;
   t5.name="wastage[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qty"+indexcone;
   t5.name="bom_qty[]";
   cell5.appendChild(t5);
    
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="FABRIC";
   t5.readOnly=true;
   t5.id = "total_amount"+indexcone;
   t5.name="total_amount[]";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(10);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone1()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_1').find('tr').eq(indexcone);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
   
   indexcone++;
   recalcIdcone1();
   }
   
   // Start Sewing Trims----------------------------
   var indexcone1 = 2;
   function insertcone2(){
   
   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "ids"+indexcone1;
   t1.name= "ids[]";
   t1.value=indexcone1;
   t1.disabled=true;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_codes"),
   y = x.clone();
   y.attr("id","item_codes");
   y.attr("name","item_codes[]");
   y.width(200);
   y.appendTo(cell3);
     
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_ids"),
   y = x.clone();
   y.attr("id","class_ids");
   y.attr("name","class_ids[]");
   y.width(200);
   y.appendTo(cell3);
   
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptions"+indexcone1;
   t5.name="descriptions[]";
   cell5.appendChild(t5); 
   
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_ids"),
   y = x.clone();
   y.attr("id","color_ids");
   y.attr("name","color_ids[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "color_arrays"+indexcone2;
   t5.name="color_arrays[]";
   cell3.appendChild(t5); 
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#size_ids"),
   y = x.clone();
   y.attr("id","size_ids");
   y.attr("name","size_ids[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "size_arrays"+indexcone2;
   t5.name="size_arrays[]";
   cell3.appendChild(t5); 
   
     
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "consumptions"+indexcone1;
   t5.name="consumptions[]";
   cell5.appendChild(t5);  
    
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_ids");
   y.attr("name","unit_ids[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_units"+indexcone1;
   t5.name="rate_per_units[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastages"+indexcone1;
   t5.name="wastages[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qtys"+indexcone1;
   t5.name="bom_qtys[]";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="SEWING";
   t5.readOnly=true;
   t5.id = "total_amounts"+indexcone1;
   t5.name="total_amounts[]";
   cell5.appendChild(t5); 
   
    
    
   var cell6=row.insertCell(12);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone2()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_3').find('tr').eq(indexcone1);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;
   
   indexcone1++;
   recalcIdcone2();
   }
   
   
   // Start Packing Trims----------------------------
   var indexcone2 = 2;
   function insertcone3(){
   
   var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "idss"+indexcone2;
   t1.name= "idss[]";
   t1.value=indexcone2;
   t1.disabled=true;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_codess"),
   y = x.clone();
   y.attr("id","item_codess");
   y.attr("name","item_codess[]");
   y.width(200);
   y.appendTo(cell3);
     
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_idss"),
   y = x.clone();
   y.attr("id","class_idss");
   y.attr("name","class_idss[]");
   y.width(200);
   y.appendTo(cell3);
   
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptionss"+indexcone2;
   t5.name="descriptionss[]";
   cell5.appendChild(t5); 
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_idss"),
   y = x.clone();
   y.attr("id","color_idss");
   y.attr("name","color_idss[][]");
   y.width(200);
   y.appendTo(cell3);  
    
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "color_arrayss"+indexcone2;
   t5.name="color_arrayss[]";
   cell3.appendChild(t5); 
   
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#size_idss"),
   y = x.clone();
   y.attr("id","size_idss");
   y.attr("name","size_idss[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "size_arrayss"+indexcone2;
   t5.name="size_arrayss[]";
   cell3.appendChild(t5);
    
     
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "consumptionss"+indexcone2;
   t5.name="consumptionss[]";
   cell5.appendChild(t5);  
    
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_idss"),
   y = x.clone();
   y.attr("id","unit_idss");
   y.attr("name","unit_idss[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_unitss"+indexcone2;
   t5.name="rate_per_unitss[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastagess"+indexcone2;
   t5.name="wastagess[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qtyss"+indexcone2;
   t5.name="bom_qtyss[]";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="PACKING";
   t5.readOnly=true;
   t5.id = "total_amountss"+indexcone2;
   t5.name="total_amountss[]";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(12);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone3()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_4').find('tr').eq(indexcone2);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;
   
   indexcone2++;
   recalcIdcone3();
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
    var order_rate=$("#order_rate").val();
    var order_amount=(parseFloat(order_rate)*parseInt(sum1)).toFixed(2);
    $("#order_amount").val(order_amount);
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