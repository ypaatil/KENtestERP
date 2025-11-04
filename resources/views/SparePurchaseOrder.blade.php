@extends('layouts.master') 
@section('content')
@php 
        ini_set('memory_limit', '10G');
@endphp 
<style>
    .form-popup-bg {
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      flex-direction: column;
      align-content: center;
      justify-content: center;
    }
    .form-popup-bg {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(94, 110, 141, 0.9);
      opacity: 0;
      visibility: hidden;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      transition: opacity 0.3s 0s, visibility 0s 0.3s;
      overflow-y: auto;
      z-index: 10000;
    }
    .form-popup-bg.is-visible {
      opacity: 1;
      visibility: visible;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
      transition: opacity 0.3s 0s, visibility 0s 0s;
    }
    .form-container {
        background-color: #011b3285;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 825px;
        margin-left: auto;
        margin-right: auto;
        position:relative;
        padding: 40px;
        color: #fff;
        height: fit-content;
        max-height: -webkit-fill-available;
    }
    .close-button {
      background:none;
      color: #fff;
      width: 40px;
      height: 40px;
      position: absolute;
      top: 0;
      right: 0;
      border: solid 1px #fff;
    }
    
    .form-popup-bg:before{
      content:'';
      background-color: #fff;
      opacity: .25;
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Spare Purchase Order</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Spare Purchase Order</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
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
            <form action="{{route('SparePurchaseOrder.store')}}" method="POST" id="frmData">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Firm</label>
                        <select name="firm_id" class="form-select" id="firm_id" required>
                           @foreach($firmlist as  $row)
                           {
                           <option value="{{ $row->firm_id }}">{{ $row->firm_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">PO Date</label>
                        <input type="date" name="pur_date" class="form-control" id="formrow-email-input" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();" required>
                           <option value="">--- Select Supplier ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           {
                           <option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }} ({{$rowledger->Bt_name}})</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <input type="hidden" name="supplierRef" class="form-control" id="formrow-email-input" value="0" required>
                  <input type="hidden" name="gstNo" class="form-control" id="gstNo" value="">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">GST Type</label>
                        <select name="tax_type_id" class="form-select" id="tax_type_id"  disabled required>
                           <option value="">--GST Type--</option>
                           @foreach($gstlist as  $rowgst) 
                           <option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Category Spares</label>
                        <select name="cat_id" class="form-select select2" id="cat_id" onchange="GetClassesList();">
                            @foreach($CategroyList as  $row) 
                           <option value="{{ $row->cat_id  }}">{{ $row->cat_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Classificaion</label>
                        <select name="class_id[]" class="form-select select2" id="class_id" onchange="GetSpareItemDetail();" multiple>
                           <option value="0">Class List</option>
                           @foreach($ClassList as  $rowclass)
                           {
                           <option value="{{ $rowclass->class_id  }}">{{ $rowclass->class_name }} </option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="table-wrap">
                  <div  class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2 stripe row-border order-column" cellspacing="0" width="100%">
                        <thead>
                           <tr>
                              <th>Sr No</th>
                              <th>Add/Remove</th>
                              <th>Item Name</th>
                              <th>Model No</th>
                              <th>HSN No</th>
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>Disc%</th>
                              <th>Discount</th>
                              <th>CGST%</th>
                              <th>CAMT</th>
                              <th>SGST%</th>
                              <th>SAMT</th>
                              <th>IGST%</th>
                              <th>IAMT</th>
                              <th>Amount</th>
                              <th>Freight HSN</th>
                              <th>Freight</th>
                              <th>Total Amount</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis"></tbody>
                        <tfoot>
                           <tr>
                              <th>Sr No</th>
                              <th>Add/Remove</th>
                              <th>Item Name</th>
                              <th>Model No</th>
                              <th>HSN No</th>
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>Disc%</th>
                              <th>Discount</th>
                              <th>CGST%</th>
                              <th>CAMT</th>
                              <th>SGST%</th>
                              <th>SAMT</th>
                              <th>IGST%</th>
                              <th>IAMT</th>
                              <th>Amount</th>
                              <th>Freight HSN</th>
                              <th>Freight</th>
                              <th>Total Amount</th>
                           </tr>
                        </tfoot>
                        <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                     </table>
                  </div>
               </div>
               <br/>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Qty</label>
                        <input type="hidden" value="0" name="address" class="form-control" id="address">
                        <input type="text" readOnly value="0" name="total_qty" class="form-control" id="total_qty">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Gross Amount</label>
                        <input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">GST Amount</label>
                        <input type="text" name="Gst_amount" class="form-control" id="Gst_amount"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Freight Amount</label>
                        <input type="text" name="totFreightAmt"  class="form-control" id="totFreightAmt" value="0"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Net Amount</label>
                        <input type="text" name="Net_amount" class="form-control" id="Net_amount"  readOnly>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Delivery Address</label>
                        <input type="text" name="deliveryAddress" class="form-control" id="deliveryAddress" value="GAT NO 298/299,A/P Kondigre,  Kolhapur, Maharashtra, 416101">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="delivery_date" class="form-label">Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" id="delivery_date" required>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="narration" class="form-label">Narration / Remark</label>
                        <input type="text" name="narration" class="form-control" id="narration"  >
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="term_and_conditions" class="form-label">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" class="form-control" id="editor1" >
                            <p>1. We have right to reject any goods which is rejected by our QC and vendor will be sole responsible for rejection.<br />
                            2 .We reserves the right to reject the goods if we find them defective even at the later stage and to recover the cost of material and losses if any from the<br />
                            sellers.<br />
                            3. Payment shall be made for the actual quantity received by us and our records shall be final and conclusive on this point.<br />
                            4. We will be entitled to deduct Discount as mentioned in the order.<br />
                            5. Any dispute arise with respect to this PO shall be subjected to &quot;Ichalkaranji Jurisdiction&quot;.<br />
                            6. You will allow our customers &amp; quality person to do visit to your factory to verify the quality of material supplied by you so also to see the system of quality<br />
                            control followed by you.<br />
                            7. Excess of PO qty is +/-2 % acceptable, Payment will be released only as per physical received qty. (PO qty whichever is lower).<br />
                            8. Delivery Address: - as above.<br />
                            9. Goods will be inspected at your factory as per our quality requirements Packing list, Invoice &amp; L.R. copy required on the mail after dispatch.<br />
                            &nbsp;</p>
                        </textarea>
                     </div>
                  </div>
               </div>
               <div>
                  <button type="submit" id="Submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
                  <a href="{{ Route('SparePurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script> 
   
    function GetSpareItemMasterData(row)
    {
        var spare_item_code = $(row).val();
        var tax_type_id = $("#tax_type_id").val();
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetSpareItemMasterData') }}",
           data:{spare_item_code:spare_item_code},
           success: function(response)
           {
                var data = response.ItemData[0];
                
                $(row).parent().parent('tr').find('td input[name="hsn_code[]"]').val(data.hsn_code);
                $(row).parent().parent('tr').find('td select[name="unit_id[]"]').val(data.unit_id);
                $(row).parent().parent('tr').find('td input[name="item_qtys[]"]').val(data.item_qty ?? 0);
                $(row).parent().parent('tr').find('td input[name="item_rate[]"]').val(data.item_rate ?? 0);
                $(row).parent().parent('tr').find('td input[name="disc_pers[]"]').val(data.disc_per ?? 0);
                $(row).parent().parent('tr').find('td input[name="disc_amounts[]"]').val(data.disc_amount ?? 0);
                $(row).parent().parent('tr').find('td input[name="pur_cgsts[]"]').val(data.cgst_per ?? 0);
                $(row).parent().parent('tr').find('td input[name="camts[]"]').val(0);
                $(row).parent().parent('tr').find('td input[name="pur_sgsts[]"]').val(data.sgst_per ?? 0);
                $(row).parent().parent('tr').find('td input[name="samts[]"]').val(0);
                $(row).parent().parent('tr').find('td input[name="pur_igsts[]"]').val(data.igst_per ?? 0);
                $(row).parent().parent('tr').find('td input[name="iamts[]"]').val(0);
                $(row).parent().parent('tr').find('td input[name="amounts[]"]').val(data.amounts ?? 0); 
                $(row).parent().parent('tr').find('td input[name="freight_hsn[]"]').val(data.freight_hsn ?? 0);
                $(row).parent().parent('tr').find('td input[name="freight_amt[]"]').val(data.freight_amt ?? 0);
                $(row).parent().parent('tr').find('td input[name="total_amounts[]"]').val(data.total_amounts ?? 0);
                $(row).parent().parent('tr').find('td.model_cls').html(data.mc_model_name ?? '');
           }
        });
    }
   
    $(document).ready(function() 
    {
        $('#frmData').submit(function() 
        {
            $('#Submit').prop('disabled', true);
        }); 
        
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() {
            // Initialize select2 if it's not already initialized
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 10000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
    });
    
   CKEDITOR.replace('editor1'); 
   
    function calFreightAmt(row)
    {
        var freight_amt = $(row).val() ? $(row).val() : 0;
        var totAmt = $(row).parent().next().find('input[name="total_amounts[]"]').val() ? $(row).parent().next().find('input[name="total_amounts[]"]').val() : 0;
        var total_Amt = parseFloat(freight_amt) + parseFloat(totAmt);
        $(row).parent().next().find('input[name="total_amounts[]"]').val(total_Amt);
    }
 

//   $(document).on("change", 'input[class^="ITEMQTY"],input[class^="RATE"]', function (event) 
//   {
//               var po_type_id=$('#po_type_id').val();
//              if(po_type_id!=2)
//             {  var value = $(this).val();
//               var maxLength = parseFloat($(this).attr('max'));
//               var minLength = parseFloat($(this).attr('min')); 
//               if(value>maxLength){alert('Value can not be greater than '+maxLength);}
//               if ((value !== '') && (value.indexOf('.') === -1)) 
//               {
//                   $(this).val(Math.max(Math.min(value, maxLength), minLength));
//               }
              
//             }
      
     
//   });
   
   function EnableFields()
   {
                 
        $("select").prop('disabled', false);
   }
    
   function insertRow(row)
   {
       let clonedRow = $(row).closest('tr').clone(); 
       $('#footable_2 tbody').append(clonedRow);
       recalcId();
       selselect();
            
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() {
            // Initialize select2 if it's not already initialized
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 10000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
   }
   
   function selselect()
   {
        setTimeout(
            function() 
            {
           
            $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
           
               $(this).closest("tr").find('select[name="item_codes[]"]').select2();
            
           
              });
        }, 10000);
   }
   
   
   function deleteRow(btn) 
   {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       recalcId();
       mycalc();
      
   }
   
   function recalcId()
   {
       $.each($("#footable_2 tr"),function (i,el)
       {
            $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
       });
   }
   
   
   
   function mycalc()
   {   
   
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('GROSS');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       document.getElementById("Gross_amount").value = sum1.toFixed(4);
       
       
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('GSTAMT');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       document.getElementById("Gst_amount").value = sum1.toFixed(4);
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('TOTAMT');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       document.getElementById("Net_amount").value = sum1.toFixed(0);
       
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('FREIGHT');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       document.getElementById("totFreightAmt").value = sum1.toFixed(0);
       
       
       
       var sum = 0.0;
       var amounts = document.getElementsByClassName('ROWCOUNT');
       for(var i=0; i<amounts .length; i++)
       {
           var a = +amounts[i].value;
           sum += parseFloat(a) || 0;
       }
        document.getElementById("cnt").value = sum;
       
       
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('ITEMQTY');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       document.getElementById("total_qty").value = sum1.toFixed(4);
   }
   
   
   
   
   
   
   function frieght_payable()
   {
       var Net_amount=document.getElementById('Net_amount').value;
       var freight_amt=$('#freight_amt').val();
   
       var payable_amount=(parseFloat(Net_amount) + (parseFloat(freight_amt)));
       $('#payable_amt').val(parseFloat(payable_amount).toFixed(0));
   
   }
   
   
   function disc_calculatess()
   {
   
   var item_qty=document.getElementById('item_qty').value;
   var item_rate=document.getElementById('item_rate').value;
   var disc_per=document.getElementById('disc_per').value;
   var amount= item_qty*item_rate
   
   var disc_amount= parseFloat(parseFloat(amount) * parseFloat(disc_per/100));
   $('#disc_amount').val(disc_amount.toFixed(4));
   
   var amount= parseFloat(parseFloat(amount) - parseFloat(disc_amount)).toFixed(4);
   $('#amount').val(amount);
   calculateGstsss();
   
   }
   
   
   
   
   function calculateGstsss()
   {
   var amount=document.getElementById('amount').value;
   var pur_cgst=document.getElementById('pur_cgst').value;
   var pur_sgst=document.getElementById('pur_sgst').value;
   var pur_igst=document.getElementById('pur_igst').value;
   
   var tax_type_id1=document.getElementById('tax_type_id').value;
   if(tax_type_id1==2)
   {
   var iamt=  parseFloat(( amount*(pur_igst/100))).toFixed(4);
   $('#iamt').val(iamt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
   
   }
   else{
   var camt=  parseFloat(( amount*(pur_cgst/100))).toFixed(4);
   $('#camt').val(camt);
   var samt= parseFloat(( amount*(pur_sgst/100))).toFixed(4);
   $('#samt').val(samt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(camt) + parseFloat(samt));
   
   }
   }
   
   
   function divideBy(str) 
   { 
   // item_code = document.getElementById("item_code").value;  
   
   // calculate_gst(item_code);
   
   }
   
   var tax_type_id =1;
   function calculate_gst(item_code)
   {
   
   
   tax_type_ids =document.getElementById("tax_type_id").value
   
   $.ajax(
   {
   type:"GET",
   dataType:'json',
   url: "{{ route('GSTPER') }}",
   data:{item_code:item_code,tax_type_id:tax_type_ids},
   success:function(response)
   {
   
     console.log(response);  
   
      if(tax_type_id==1)
               {
   
     $("#pur_cgst").val(response[0].cgst_per); 
     $("#pur_sgst").val(response[0].sgst_per); 
     $("#pur_igst").val(response[0].igst_per);
   } else{
   
   $("#pur_igst").val(response[0].igst_per);
   
   }
   
   }
   
   });
   }
     
        
    function getItemDetails(row)
    {
             
    
       var tax_type_ids=document.getElementById('tax_type_id').value;
       var item_code = $(row).val();
       
       $(row).parent('td').attr('item_code',item_code); 
       $(row).parent().parent('tr').attr('class',"tr_"+item_code); 
         // get the row
       row = $(row).closest('tr'); 
       $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GSTPER') }}",
          data:{item_code:item_code,tax_type_id:tax_type_ids},
           success: function(response){
   
                
               
               if(tax_type_ids==1 && response != "")
               {
                   row.find('input[name^="pur_cgsts[]"]').val(response.data[0]['cgst_per'] ? response.data[0]['cgst_per'] : 0);
                   row.find('input[name^="pur_sgsts[]"]').val(response.data[0]['sgst_per'] ? response.data[0]['sgst_per'] : 0);
                   row.find('input[name^="pur_igsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0);
               }
               else if(tax_type_ids==2 && response != "")
               {
                   row.find('input[name^="pur_igsts[]"]').val(response.data[0]['igst_per'] ? response.data[0]['igst_per'] : 0);
                   row.find('input[name^="pur_cgsts[]"]').val(0);
                   row.find('input[name^="pur_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0);
               }
               else if(tax_type_ids==3 && response != "")
               {
                   row.find('input[name^="pur_igsts[]"]').val(0);
                   row.find('input[name^="pur_cgsts[]"]').val(0);
                   row.find('input[name^="pur_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0); 
               }
         
           }
           });
   
   } 
   
   
   
   function firmchange(firm_id){
   
   
   var type=document.getElementById('type').value;
   
   //alert(firm_id);
   
   $.ajax({
   type:"GET",
   url:'getdata.php',
   dataType:"json",
   data:{firm_id:firm_id,type:type, fn:"Firm_change"},
   success:function(response){
   console.log(response);  
   
   $("#pur_code").val(response["code"]+'-'+response["tr_no"]);
   $("#c_code").val(response["c_code"]);
   
   }
   });
   }
   
   
   
   function getPartyDetails()
   {
       var po_type_id = $("#po_type_id").val();
       if(po_type_id == 2)
       {
           $("#tr1").removeClass("hide");
       }
       else
       {
           $("#tr1").addClass("hide");
       }
       
       var ac_code=$("#Ac_code").val();
       
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('PartyDetail') }}",
               data:{'ac_code':ac_code},
               success: function(data)
               {
                   $("#gstNo").val(data[0]['gst_no']);
                   if(data[0]['state_id']==27){$("#tax_type_id").val(1);}
                   else{$("#tax_type_id").val(2);}
               }
           });
   }
   
   
   
   
        $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
           CalculateRow($(this).closest("tr"));
           
   
           
       });
   
   
   
       function CalculateRow(row)
       {
   
           var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
           var item_rates=+row.find('input[name^="item_rates[]"]').val();
           var disc_pers=+row.find('input[name^="disc_pers[]"]').val();
           var disc_amounts=+row.find('input[name^="disc_amounts[]"]').val();
           var pur_cgsts=  +row.find('input[name^="pur_cgsts[]"]').val();
           var camts= +row.find('input[name^="camts[]"]').val();
           var pur_sgsts= +row.find('input[name^="pur_sgsts[]"]').val();
           var samts= +row.find('input[name^="samts[]"]').val();
           var pur_igsts= +row.find('input[name^="pur_igsts[]"]').val();
           var iamts= +row.find('input[name^="iamts[]"]').val();
           var amounts= +row.find('input[name^="amounts[]"]').val();
           var freight_amt= +row.find('input[name^="freight_amt[]"]').val();
           var total_amounts= +row.find('input[name^="total_amounts[]"]').val();
           var tax_type_id =document.getElementById("tax_type_id").value;
           
           
                
            if(item_qtys>0)
            {
               
                    Amount=item_qtys*item_rates;
                    disc_amt=(Amount*(disc_pers/100));
                    row.find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(4));
                    Amount=Amount-disc_amt;
                    row.find('input[name^="amounts[]"]').val((Amount).toFixed(4));
                 
                if(pur_igsts!=0)
                {
                     Iamt=(Amount*(pur_igsts/100));
                     row.find('input[name^="iamts[]"]').val((Iamt).toFixed(4));
                     TAmount=Amount+Iamt+freight_amt;
                     row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
                }
                else
                {
                     Camt=(Amount*(pur_cgsts/100));
                     row.find('input[name^="camts[]"]').val((Camt).toFixed(4));
                     
                     Samt=(Amount*(pur_sgsts/100));
                     row.find('input[name^="samts[]"]').val((Samt).toFixed(4));
                                     
                     TAmount=Amount+Camt+Samt+freight_amt;
                     row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
                     
                }
                
           }
                
                     mycalc();
   }
   
   
   function GetClassesList()
   {
     //  cat_id=$("#bom_type").val();
       var  bom_types = $("#bom_type option:selected").map(function() {
         return this.value;
       }).get().join(",");
        
        $.ajax({
           dataType: "json",
           url: "{{ route('getClassLists') }}",
           data:{'cat_id':bom_types},
           success: function(data){
           $("#class_id").html(data.html);
           
          }
       });   
   }
   
   
   $(document).ready(function()
   {
          var previousSelection = [];
          
           $('#class_id').change(function(){
           
             
            var currentSelection = $(this).val() || [];
            
            // Compare previous selection with current selection
            $(previousSelection).each(function(index, value){
              if ($.inArray(value, currentSelection) === -1) 
              {
                console.log('Option deselected: ' + value);
                 $(".cls_"+value).remove(); 
                  
                   $.ajax({
                       dataType: "json",
                       url: "{{ route('getItemCodeList') }}",
                       data:{'class_id':value},
                       success: function(data)
                       {
                           
                            $(data.ItemList).each(function()
                            {
                                var item_code = $(this)[0]; 
                                $('.tr_'+item_code).remove(); 
                           });
                      }
                   });  
               
              }
            });
            
            previousSelection = currentSelection;
          }); 
          
        var isDropdownOpen = false;
        
        $('select').on('mouseenter', function() {
            // Initialize select2 if it's not already initialized
            if (!$(this).data('select2')) {
                $(this).select2();
            }
        });
        
        $('select').on('mouseleave', function() {
            var $this = $(this); 
            setTimeout(function() { 
                if (!isDropdownOpen && !$this.is(':hover')) {
                    $this.select2('destroy');
                }
            }, 10000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
    });
       

   
   function GetSpareItemDetail()
   { 
        
       var type=$("#cat_id").val();
      
        var class_ids = $("#class_id option:selected").map(function() {
            return this.value;
        }).get().join(",");
        var tax_type_id = document.getElementById("tax_type_id").value;
        console.log(class_ids);
         classIdsArray = class_ids.split(',');
        //  $(classIdsArray).each(function(i)
        //  {
           // $(".cls_"+classIdsArray[i]).remove();
            $.ajax({
                type: "GET",
                url: "{{ route('GetSpareItemDetail') }}",
                data: { type: type,  tax_type_id: tax_type_id, class_ids: class_ids },
                success: function(response) 
                {
                    $("#bomdis").html(response.html);
                    mycalc();
                        
                    var isDropdownOpen = false;
                    
                    $('select').on('mouseenter', function() {
                        // Initialize select2 if it's not already initialized
                        if (!$(this).data('select2')) {
                            $(this).select2();
                        }
                    });
                    
                    $('select').on('mouseleave', function() {
                        var $this = $(this); 
                        setTimeout(function() { 
                            if (!isDropdownOpen && !$this.is(':hover')) {
                                $this.select2('destroy');
                            }
                        }, 10000); 
                    });
                     
                    $('select').on('select2:open', function() {
                        isDropdownOpen = true;
                    });
                     
                    $('select').on('select2:close', function() {
                        isDropdownOpen = false;
                    });
                    
                }
            });
        
        // });
           
    }
          
</script>
@endsection