@extends('layouts.master') 
@section('content')
<style>
  @keyframes scroll-left {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-100%); }
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales (Invoice)</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales (Invoice)</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
<div class="row">
          <!--<div style="-->
          <!--  display:inline-block;-->
          <!--  padding-left:100%;-->
          <!--  animation: scroll-left 10s linear infinite;-->
          <!--  color:red;-->
          <!--  font-size:20px;-->
          <!--">-->
          <!--  The form is currently under maintenance.-->
          <!--</div>-->
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sales (Invoice)</h4>
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
            <form action="{{route('SaleTransaction.store')}}" method="POST" id="frmData">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'SaleTransaction' ?>" /> 
               @csrf 
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-sales_head_id" class="form-label">Sales Head/Ledger</label>
                        <select name="sales_head_id" class="form-select select2" id="sales_head_id" onchange="GetInvoiceNo();" >
                           <option>--Select--</option>
                           @foreach($salesHeadlist as  $row)
                           {
                           <option value="{{ $row->sales_head_id }}">{{ $row->sales_head_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-sale_code" class="form-label">Invoice No</label>
                        <input type="text" name="sale_code" class="form-control" id="sale_code" value="" onchange="checkInvoice();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Firm</label>
                        <select name="firm_id" class="form-select" id="firm_id" required>
                           @foreach($firmlist as  $row)
                                <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sale_date" class="form-label">Sale Date</label>
                        <input type="date" name="sale_date" class="form-control" id="sale_date" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();" required>
                           <option value="">--- Select Buyer ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           {
                           <option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="bill_to" class="form-label">Bill To</label>
                        <select name="bill_to" class="form-select select2" id="bill_to"  onchange="getTradePartyDetails();" required>
                           <option value="">--- Select ---</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">GST Type</label>
                        <select name="tax_type_id" class="form-select " id="tax_type_id" required>
                           <option value="">--GST Type--</option>
                           @foreach($gstlist as  $rowgst) 
                                <option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Carton Packing No</label>
                        <select name="carton_packing_no[]" class="form-select select2" id="carton_packing_no" multiple onchange="getSalesOrder();" disabled>
                           <option value="">Carton Packing No</option>
                        </select>
                        <input type="hidden" name="gstNo" class="form-control" id="gstNo" value="" required>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ship_to" class="form-label">Ship To</label>
                        <select name="ship_to" class="form-select select2" id="ship_to" required onchange="ReadDistance();">
                           <option value="">--- Select ---</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="distance" class="form-label">Distance</label>
                        <input type="number" step="any" name="distance" class="form-control" id="distance" value="0" readonly> 
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="sent_through" class="form-label">Sent Through</label>
                        <input type="text" name="sent_through" class="form-control" id="sent_through" value="" > 
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                       <label for="address" class="form-label">Address</label>
                       <textarea id="address" name="address" class="form-control" rows="4" cols="50"></textarea>
                     </div>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
                              <th>Sales Order Details</th>
                              <th>Ref. No(Buyer PO No.)</th>
                              <th>Style No</th>
                              <th>HSN No</th>
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th nowrap>Pack Quantity</th>
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
                              <th>Total Amount</th>
                              <th>Remove</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis">
                        </tbody>
                        <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                     </table>
                  </div>
               </div>
               <br/>
               <div class="row">
                  <div class="col-md-2 mb-3">
                        <label for="delivary_note" class="form-label">Delivary Note</label>
                        <input type="text" value="" name="delivary_note" class="form-control" id="delivary_note">
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="delivary_note_date" class="form-label">Delivary Note Date</label>
                        <input type="date" value="" name="delivary_note_date" class="form-control" id="delivary_note_date">
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="mode_of_payment" class="form-label">Mode/Terms Of Payment</label>
                        <input type="number" step="any" required value="" name="mode_of_payment" class="form-control" id="mode_of_payment">
                  </div> 
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="transport_id" class="form-label">Dispatch Through(Transport Id)</label>
                        <select name="transport_id" class="form-select select2" id="transport_id" required>
                           <option value="">--- Select ---</option>
                           @foreach($dispatchlist as  $rows)
                             <option value="{{ $rows->ac_code  }}">{{ $rows->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="transDocNo" class="form-label">Transporter Doc No.</label>
                        <input type="text" value="" name="transDocNo" class="form-control" id="transDocNo">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="transDocDate" class="form-label">Transporter Doc Date.</label>
                        <input type="date" value="" name="transDocDate" class="form-control" id="transDocDate">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" required value="" name="destination" class="form-control" id="destination">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="bill_of_landing" class="form-label">Bill of Landing/LR RR No.</label>
                        <input type="text" value="" name="bill_of_landing" class="form-control" id="bill_of_landing">
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="vehicle_no" class="form-label">Motor Vehicle No.</label>
                        <input type="text" value="" name="vehicle_no" class="form-control" id="vehicle_no">
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="terms_of_delivery_id" class="form-label">Terms Of Delivery</label>
                        <select name="terms_of_delivery_id" class="form-select select2" id="terms_of_delivery_id" required>
                           <option value="">--- Select ---</option>
                           @foreach($shipment_mode_list as  $rows)
                             <option value="{{ $rows->ship_id  }}">{{ $rows->ship_mode_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="no_of_cartons" class="form-label">No of Cartons</label>
                        <input type="number" step="any" required value="" name="no_of_cartons" class="form-control" id="no_of_cartons">
                  </div> 
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Qty (Pieces)</label>
                        <input type="text" required readOnly value="0" name="total_qty" class="form-control" id="total_qty">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Gross Amount</label>
                        <input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Freight Charges</label>
                        <input type="text" name="freight_charges" class="form-control" id="freight_charges" onChange="mycalc();" required value="0" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other Cost</label>
                        <input type="text" name="other_cost" class="form-control" id="other_cost" onChange="mycalc();" required value="0" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other Cost GST %</label>
                        <input type="text" name="other_cost_gst_per" class="form-control" id="other_cost_gst_per" onChange="mycalc();" required value="0" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other GST Amt </label>
                        <input type="text" name="other_cost_gst_amt" class="form-control" id="other_cost_gst_amt" onChange="mycalc();" required value="0" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">GST Amount</label>
                        <input type="text" name="Gst_amount" class="form-control" id="Gst_amount" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Net Amount</label>
                        <input type="text" name="Net_amount" class="form-control" id="Net_amount" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="narration" class="form-label">Narration / Remark</label>
                        <input type="text" name="narration" class="form-control" id="narration"  >
                     </div>
                  </div>
               </div>
               <div class="row" style="display:none;">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="term_and_conditions" class="form-label">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" class="form-control" id="editor1"   required>
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
                  <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
                  <a href="{{ Route('SaleTransaction.index') }}" class="btn btn-warning w-md">Cancel</a>
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
     $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
    function CalPackQty(row) 
    {  
        var unitText = $(row).find("option:selected").text().trim();
        var order_qty = $(row).closest('tr').find('td input[name="order_qty[]"]').val();
        
        if (unitText.startsWith("Pack -")) 
        { 
            var unit = parseFloat(unitText.replace(/[^0-9.]/g, ''));
     
            if (!isNaN(unit) && unit > 0) 
            {
                var pack_order_qty = parseFloat(order_qty) / unit;
                $(row).closest('tr').find('td input[name="pack_order_qty[]"]').val(pack_order_qty);
                CalculateRow(row); 
            }
        }
        else
        { 
            $(row).closest('tr').find('td input[name="pack_order_qty[]"]').val(order_qty);
            CalculateRow(row); 
        }
    }
    
    function ReadDistance()
    {
        var trade_name = $("#ship_to").val();
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('ReadDistanceFromPincode') }}",
           data:{'trade_name':trade_name},
           success: function(data)
           { 
                $("#distance").val(data);
           }
        });  
    }
    
    function checkInvoice()
    {
        
       var sale_code = $("#sale_code").val();
       $.ajax(
       {
           type:"GET", 
           url: "{{ route('checkInvoice') }}",
           data:{sale_code:sale_code},
           success:function(res)
           {
                if(res > 0)
                {
                    alert("Already Exists...!")
                    $("#sale_code").val("");
                    $("#sales_head_id").select2('destroy');
                    $("#sales_head_id").val("").trigger('change');
                    $("#sales_head_id").select2();
                }
           }
       });
    }
    
   function GetInvoiceNo()
   {
       var sales_head_id = $("#sales_head_id").val();
       $.ajax(
       {
           type:"GET", 
           url: "{{ route('GetSalesInvoiceCode') }}",
           data:{sales_head_id:sales_head_id},
           success:function(res)
           {
                $("#sale_code").val(res);
                checkInvoice();
           }
       });
   }
  
   CKEDITOR.replace('editor1');  
   function EnableFields()
   {       
        $("select").prop('disabled', false);
        $("input").prop('disabled', false);
   }
   
    function insertRow()
    {
        var $lastRow = $("#footable_2 tbody tr:last");
    
        // Destroy Select2 in the last row before cloning
        $lastRow.find('select.select2').select2('destroy');
    
        var $newRow = $lastRow.clone();
    
        // Optional: clear input and select values
        $newRow.find('input').not('input[name="id[]"]').not('.btn-danger').val('');
        $newRow.find('select').val('').trigger('change');
    
        // Append the new row
        $("#footable_2 tbody").append($newRow);
    
        // Reapply Select2 to all select elements
        $("#footable_2 tbody tr:last").find('select.select2').select2();
        recalcIdcone();
    }
   
    function recalcIdcone()
    {
        $("#footable_2 tbody tr").each(function(index) 
        {
            $(this).find("input[name='id[]']").val(index + 1);
        });
    }
    
   function deleteRow(btn) {
   if(document.getElementById('cnt').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cnt').value = document.getElementById('cnt').value-1;
   recalcId();
   mycalc();
   if($("#cnt").val()<=0)
   {       
   document.getElementById('Submit').disabled=true;
   }
   
   
   
   }
   }
   
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   
   function mycalc()
   {   
       var freight_charges=$("#freight_charges").val();
       
       var other_cost=$("#other_cost").val();
       
       var other_cost_gst_per=$("#other_cost_gst_per").val();
       
       var other_cost_gst_amt=(parseFloat(other_cost) * parseFloat(other_cost_gst_per/100)).toFixed(2);
       
       $("#other_cost_gst_amt").val(other_cost_gst_amt);
       
       
       
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('GROSS');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       
       
       document.getElementById("Gross_amount").value = parseFloat(sum1.toFixed(2)) + parseFloat(freight_charges) +  parseFloat(other_cost);
        
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('GSTAMT');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       
       var TotalGst=parseFloat(freight_charges*(5/100).toFixed(2)) + parseFloat(sum1.toFixed(2))  + parseFloat(other_cost_gst_amt);
       document.getElementById("Gst_amount").value =TotalGst;
       
       sum1 = 0.0;
       var amounts = document.getElementsByClassName('TOTAMT');
       //alert("value="+amounts[0].value);
       for(var i=0; i<amounts .length; i++)
       { 
       var a = +amounts[i].value;
       sum1 += parseFloat(a);
       }
       
       var NetAmt=  parseFloat(sum1.toFixed(0)) + parseFloat(freight_charges) + parseFloat(freight_charges*(5/100).toFixed(2));
       
       document.getElementById("Net_amount").value = NetAmt.toFixed(2);
       
        
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
       document.getElementById("total_qty").value = sum1.toFixed(2);
   
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
   $('#disc_amount').val(disc_amount.toFixed(2));
   
   var amount= parseFloat(parseFloat(amount) - parseFloat(disc_amount)).toFixed(2);
   $('#amount').val(amount);
   calculateGstsss();
   
   }
   
   
   
   
   function calculateGstsss()
   {
   var amount=document.getElementById('amount').value;
   var sale_cgst=document.getElementById('sale_cgst').value;
   var sale_sgst=document.getElementById('sale_sgst').value;
   var sale_igst=document.getElementById('sale_igst').value;
   
   var tax_type_id1=document.getElementById('tax_type_id').value;
   if(tax_type_id1==2)
   {
   var iamt=  parseFloat(( amount*(sale_igst/100))).toFixed(2);
   $('#iamt').val(iamt);
   
   $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
   
   }
   else{
   var camt=  parseFloat(( amount*(sale_cgst/100))).toFixed(2);
   $('#camt').val(camt);
   var samt= parseFloat(( amount*(sale_sgst/100))).toFixed(2);
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
   
     $("#sale_cgst").val(response[0].cgst_per); 
     $("#sale_sgst").val(response[0].sgst_per); 
     $("#sale_igst").val(response[0].igst_per);
   } else{
   
   $("#sale_igst").val(response[0].igst_per);
   
   }
   
   }
   
   });
   }
   
   
    $('#footable_2').on('change', '.item', function() 
    {
    
       var tax_type_ids=document.getElementById('tax_type_id').value;
       var item_code = $(this).val();
       var row = $(this).closest('tr'); // get the row
       
       $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GSTPER') }}",
          data:{item_code:item_code,tax_type_id:tax_type_ids},
           success: function(data){
   
                console.log(data); 
               
               if(tax_type_ids==1)
               {
                   row.find('input[name^="sale_cgsts[]"]').val(data[0]['cgst_per']);
                   row.find('input[name^="sale_sgsts[]"]').val(data[0]['sgst_per']);
                   row.find('input[name^="sale_igsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                    row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
                  
               }
               else if(tax_type_ids==2)
               {
                   row.find('input[name^="sale_igsts[]"]').val(data[0]['igst_per']);
                   row.find('input[name^="sale_cgsts[]"]').val(0);
                   row.find('input[name^="sale_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                  row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
               }
               else if(tax_type_ids==3)
               {
                   row.find('input[name^="sale_igsts[]"]').val(0);
                   row.find('input[name^="sale_cgsts[]"]').val(0);
                   row.find('input[name^="sale_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
               }
         
           }
           });
   
   });
   
   
   
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
   
   $("#sale_code").val(response["code"]+'-'+response["tr_no"]);
   $("#c_code").val(response["c_code"]);
   
   }
   });
   }
   
   
   function getTradePartyDetails()
   {
        var trade_name = $("#bill_to").val();
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetTradePartyDetailsSale') }}",
           data:{'trade_name':trade_name},
           success: function(data)
           { 
               if(data.state_id==27){$("#tax_type_id").val(1);}
               else{$("#tax_type_id").val(2);}
               $("#carton_packing_no").removeAttr("disabled");
           }
        });  
   }
   
   
   function getPartyDetails()
   {
       var ac_code=$("#Ac_code").val();
       
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('GetPartyDetailsSale') }}",
               data:{'ac_code':ac_code},
               success: function(data)
               {
                   console.log(data);
                   $("#gstNo").val(data.master[0]['gst_no']);
                   if(data.master[0]['state_id']==27){$("#tax_type_id").val(1);}
                   else{$("#tax_type_id").val(2);}
                   $("#bill_to").html(data.detail);
                   $("#ship_to").html(data.detail);
               }
           });
           
           
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('CartonPackingList') }}",
               data:{'Ac_code':ac_code},
               success: function(response)
               {
                   $("#carton_packing_no").html(response.html);
               }
           });
           
           
   } 
   
    $("table.footable_2").on("keyup",'input[name^="pack_order_qty[]"]', 'input[name^="order_qty[]"],input[name^="order_rate[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="sale_cgsts[]"],input[name^="camts[]"],input[name^="sale_sgsts[]"],input[name^="sale_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
       CalculateRow($(this).closest("tr"));
       

       
    });
   
   
   
       function CalculateRow(row)
       {
   
           var item_qtys = +$(row).closest('tr').find('input[name="pack_order_qty[]"]').val();
           var item_rates=+$(row).closest('tr').find('input[name^="order_rate[]"]').val();
           var disc_pers=+$(row).closest('tr').find('input[name^="disc_pers[]"]').val();
           var disc_amounts=+$(row).closest('tr').find('input[name^="disc_amounts[]"]').val();
           var sale_cgsts=  +$(row).closest('tr').find('input[name^="sale_cgsts[]"]').val();
           var camts= +$(row).closest('tr').find('input[name^="camts[]"]').val();
           var sale_sgsts= +$(row).closest('tr').find('input[name^="sale_sgsts[]"]').val();
           var samts= +$(row).closest('tr').find('input[name^="samts[]"]').val();
           var sale_igsts= +$(row).closest('tr').find('input[name^="sale_igsts[]"]').val();
           var iamts= +$(row).closest('tr').find('input[name^="iamts[]"]').val();
           var amounts= +$(row).closest('tr').find('input[name^="amounts[]"]').val();
           var total_amounts= +$(row).closest('tr').find('input[name^="total_amounts[]"]').val();
           var tax_type_id =document.getElementById("tax_type_id").value;
           
           
                
            if(item_qtys>0)
            {
               
                    Amount=item_qtys*item_rates;
                    disc_amt=(Amount*(disc_pers/100));
                    $(row).closest('tr').find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(2));
                    Amount=Amount-disc_amt;
                    $(row).closest('tr').find('input[name^="amounts[]"]').val((Amount).toFixed(2));
                 
                if(sale_igsts!=0)
                {
                     Iamt=(Amount*(sale_igsts/100));
                     $(row).closest('tr').find('input[name^="iamts[]"]').val((Iamt).toFixed(2));
                     TAmount=Amount+Iamt ;
                     $(row).closest('tr').find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                }
                else
                {
                     Camt=(Amount*(sale_cgsts/100));
                     $(row).closest('tr').find('input[name^="camts[]"]').val((Camt).toFixed(2));
                     
                     Samt=(Amount*(sale_sgsts/100));
                     $(row).closest('tr').find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                     
                     TAmount=Amount+Camt+Samt ;
                     $(row).closest('tr').find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                     
                }
                
           }
                
                     mycalc();
   }
    
   
   function getSalesOrder(){
       
       
       var carton_packing_nos = $("#carton_packing_no option:selected").map(function() {
         return this.value;
       }).get().join(",");
       
    
   var tax_type_id=document.getElementById("tax_type_id").value;
   
   
   $.ajax({
   type:"GET",
   url:"{{ route('getSalesOrderData') }}",
    dataType:"json",
   data:{carton_packing_nos:carton_packing_nos,tax_type_id:tax_type_id},
   success:function(response){
   console.log(response);  
       $("#bomdis").html(response.html);
    mycalc();
   }
   });
   }
</script>
@endsection