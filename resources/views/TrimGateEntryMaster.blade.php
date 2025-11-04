@extends('layouts.master') 
@section('content')
<style>
   .hide
   {
        display:none!important;
   }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trim Gate Entry</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trim Gate Entry</li>
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
         <h4 class="card-title mb-4">Trim Gate Entry</h4>
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
         <form action="{{route('TrimGateEntry.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
            @csrf 
            <div class="row">
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="tge_date" class="form-label">Date</label>
                     <input type="date" name="tge_date" class="form-control" id="tge_date" value="{{date('Y-m-d')}}" required>
                     @foreach($counter_number as  $row)
                     <input type="hidden" name="tge_code" class="form-control" id="tge_code" value="{{ 'TGE'.$row->tr_no }}"> 
                     @endforeach
                     <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                  </div>
               </div>
               <div class="col-md-2" style="margin-top: 27px;">
                  <div class="mb-3">
                     <input type="checkbox" value="0" name="is_manual" id="is_manual" style="width: 56px;height: 25px;" onchange="SetManualItemCode(this);" />
                     <label for="is_manual" class="form-label" style="position: absolute;margin-top: 4px;">Manual Item</label>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="po_code" class="form-label">PO No</label>
                     <input type="text" name="po_code2" class="form-control" id="po_code2" onchange="SetPOCode();" >
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="po_code" class="form-label">PO No</label>   
                     <select name="po_code" class="form-select select2" id="po_code1" onchange="SetPOCode();getDetails(this.value);GetPurchaseBillDetails();">
                        <option value="">--Select--</option>
                        @foreach($POList as  $rowpol) 
                        <option value="{{ $rowpol->pur_code  }}">{{ $rowpol->pur_code }}</option> 
                        @endforeach
                     </select>
                  </div>
               </div>
               <!-- this is for Dc No -->
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="dc_no" class="form-label">DC No</label>
                     <input type="text" name="dc_no" id="dc_no" class="form-control" id="dc_no" required>
                  </div>
               </div>
               <!-- This is for DC date -->
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="dc_date" class="form-label">DC Date</label>
                     <input type="date" name="dc_date" id="invoice_date" class="form-control" id="dc_date" value="{{date('Y-m-d')}}">
                  </div>
               </div>
               <!-- this is for Invoice no -->
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="invoice_no" class="form-label">Invoice No</label>
                     <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="invoice_no" required>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="invoice_date" class="form-label">Invoice Date</label>
                     <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="invoice_date" value="{{date('Y-m-d')}}">
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="mb-3">
                     <label for="Ac_code" class="form-label">Supplier</label>
                     <select name="Ac_code" class="form-select select2" id="Ac_code" required>
                        <option value="">--Select--</option>
                        @foreach($Ledger as  $row) 
                            <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option> 
                        @endforeach
                     </select>
                  </div>
               </div> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="bill_to" class="form-label">Bill To</label>
                     <select name="bill_to" class="form-select" id="bill_to" disabled>
                        <option value="">--Select--</option>
                        @foreach($BillToList as  $row) 
                            <option value="{{ $row->sr_no }}">{{ $row->trade_name }}({{$row->site_code}})</option> 
                        @endforeach
                     </select>
                  </div>
               </div> 
               <div class="col-md-3">
                  <label for="location_id" class="form-label">Location/Warehouse</label>
                  <select name="location_id" class="form-select select2  " id="location_id" required>
                     <option value="">--Location--</option>
                     @foreach($LocationList as  $row)
                     <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
                     @endforeach
                  </select>
               </div> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="lr_no" class="form-label">LR No</label>
                     <input type="text" name="lr_no" id="lr_no" class="form-control" id="lr_no" required>
                  </div>
               </div> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="transport_name" class="form-label">Transport Name</label>
                     <input type="text" name="transport_name" id="transport_name" class="form-control" id="transport_name" required>
                  </div>
               </div> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="vehicle_no" class="form-label">Vehicle No</label>
                     <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" id="vehicle_no" required>
                  </div>
               </div>
               <div class="table-wrap" id="pofetch">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                        <thead>
                           <tr>
                              <th>Sr No.</th>
                              <th>Item Name</th>
                              <th>Item Code</th>
                              <th>Item Description</th>
                              <th>UOM</th>
                              <th>Challan Qty</th>
                              <th>Receive Qty</th>
                              <th>Rate</th>
                              <th>Amount</th>
                              <th>Remark</th>  
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr> 
                              <td><input type="number" step="any" value="1" name="sr_no[]" id="sr_no" style="width:50px;" readonly/></td>
                              <td class="items">
                                 <input type="text" value="" name="item_name[]" id="item_names" style="width:250px;" class="hide" />
                                 <select name="item_code[]" id="item_code" class="" style="width:200px;height:30px;" onchange="GetItemDetail(this);" required >
                                    <option value="">--Item--</option>
                                    @foreach($ItemList as  $row)
                                    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="text" name="item_codes[]" value="" id="item_name" style="width:80px;" readonly/></td> 
                              <td><input type="text" name="item_description[]" value="" id="item_description" style="width:200px;" readonly/></td> 
                              <td>
                                  <select name="unit_id[]" id="unit_id" style="width:200px;height:30px;">
                                    <option value="">--Select--</option>
                                    @foreach($UnitList as  $row)
                                    <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                    @endforeach
                                 </select>
                              </td> 
                              <td><input type="number" step="any" name="challan_qty[]" onchange="calAmt(this);calc();" onkeyup="DuplicateQty(this);" value="" id="challan_qty" style="width:80px;"/></td> 
                              <td><input type="number" step="any" name="receive_qty[]" onchange="calAmt(this);calc();" value="" id="receive_qty" style="width:80px;"/></td> 
                              <td><input type="number" step="any" name="rate[]" onchange="calAmt(this);calc();" value="" id="rate" style="width:80px;"/></td> 
                              <td><input type="number" step="any" name="amount[]" value=""  onchange="calc();" id="amount" style="width:80px;" readonly/></td> 
                              <td><input type="text" name="remarks[]" value="" id="remarks" style="width:80px;"/></td>  
                              <td>
                                  <a href="javascript:void(0);" style="width:40px;" onclick="AddRow(this);"  class="btn btn-warning pull-left">+</a> 
                                  <a href="javascript:void(0);"  style="width:40px;" onclick="RemoveRow(this);"  class="btn btn-danger pull-left">X</a>  
                              </td>
                           </tr>
                        </tbody> 
                     </table>
                  </div>
               </div>
               <div class="row">
                  <!-- this is for total roll-->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Challan Qty</label>
                        <input type="number" readOnly step="any" name="total_qty" class="form-control" id="total_qty" value="0">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_received_meter" class="form-label">Total Received Qty</label>
                        <input type="number" readonly step="any" name="total_received_meter" class="form-control" id="total_received_meter" value="0">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_amt" class="form-label">Total Amount</label>
                        <input type="number" step="any" name="total_amt" readonly class="form-control" id="total_amt" value="0" >
                     </div>
                  </div>
                  </br>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <div class="form-check form-check-primary mb-5">
                            <label for="remark" class="form-label">Remark</label>
                            <input type="text" name="remark" class="form-control" id="remark">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                        <a href="{{ Route('TrimGateEntry.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row --><!-- Load jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Load Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- end row -->
<script>

    $(document).ready(function() 
    {
        $('#frmData').submit(function() 
        {
            $('#Submit').prop('disabled', true);
        }); 
        // Initialize Select2 only for uninitialized selects
        $(document).on('focus', 'select.select2', function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({ width: '100%' });
            }
        });
    });
    
       
   function GetPurchaseBillDetails()
   {
       var po_code = $("#po_code1").val(); 
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetPurchaseBillToDetails') }}",
           data:{'po_code':po_code},
           success: function(data)
           { 
               $("#bill_to").html(data.detail); 
           }
        }); 
   } 
      
    function DuplicateQty(row)
    {
        var challan_qty = $(row).parent().parent('tr').find('td input[name="challan_qty[]"]').val();
        $(row).parent().parent('tr').find('td input[name="receive_qty[]"]').val(challan_qty);
    }
    
    function SetManualItemCode(row) 
    {
        if ($("#is_manual").is(":checked")) 
        {
            $('tbody tr').find('input[name="item_name[]"]').removeClass("hide");
            $('tbody tr').find('select[name="item_code[]"]').addClass("hide");
            $('tbody tr').find('select[name="item_code[]"]').removeAttr("required");
            $('tbody tr').find('select[name="item_name[]"]').attr("required");
            $('tbody tr').find('input[name="item_codes[]"]').attr('readonly', true);
            $('#po_code1').prop('disabled', true);
            $('#po_code2').attr('required', true);
            $(row).val(1);
        }
        else
        {
            $('tbody tr').find('input[name="item_name[]"]').addClass("hide");
            $('tbody tr').find('select[name="item_code[]"]').removeClass("hide");
            $('tbody tr').find('select[name="item_name[]"]').removeAttr("required");
            $('tbody tr').find('select[name="item_code[]"]').attr("required");
            $('tbody tr').find('input[name="item_codes[]"]').attr('readonly', false);
            $('#po_code1').prop('disabled', false);
            $('#po_code2').attr('required', false);
            $(row).val(0);
        }
    }
    
    function getDetails(po_code)
    { 
        $.ajax({
           type:"GET",
           url:"{{ route('GetPOApproveStatus') }}",
           data:{po_code:po_code},
           success:function(res)
           { 
               if(res.isApprove > 0)
               {
                    alert("PO Not Approved...!");
               } 
               else
               {
                    $.ajax({
                       type:"GET",
                       url:"{{ route('getPo') }}",
                       data:{po_code:po_code},
                       success:function(res)
                       {
                           $("#footable_2 > tbody > tr > td select[name='item_code[]']").html(res.html);
                       }
                   });
                   
                    $.ajax({
                       type:"GET",
                       url:"{{ route('getPoMasterDetail') }}",
                       data:{po_code:po_code},
                       success:function(res)
                       { 
                            $("#Ac_code").val(res[0].Ac_code).trigger('change'); 
                            $("#Ac_code").prop("disabled", true);
                       }
                   });
               }
           }
        });
    }
   
    function GetItemDetail(row) {
        var currentRow = $(row).closest('tr');
        var $select = currentRow.find('select[name="item_code[]"]');
        var item_code = $select.val();
        var duplicateFound = false;
    
        // Check for duplicate item_code in all dropdowns except current one
        $("select[name='item_code[]']").each(function () {
            if (this !== $select[0] && $(this).val() === item_code) {
                duplicateFound = true;
                return false; // Exit loop early
            }
        });
    
        if (duplicateFound) {
            alert("Duplicate item code selected. Please choose a different item.");
    
            // Remove select2
            $select.select2('destroy');
    
            // Reset value
            $select.val('');
    
            // Clear dependent inputs
            currentRow.find('input[name="item_codes[]"]').val('');
            currentRow.find('input[name="item_description[]"]').val('');
    
            // Re-apply select2
            $select.select2();
    
            return;
        }
    
        // If no duplicate, fetch item details
        $.ajax({
            dataType: "json",
            url: "{{ route('GetItemDetails') }}",
            data: { 'item_code': item_code },
            success: function (data) 
            {
                currentRow.find('input').not('input[name="sr_no[]"]').val('');
                currentRow.find('input[name="item_codes[]"]').val(item_code);
                currentRow.find('input[name="item_description[]"]').val(data[0].item_description);
                currentRow.find('select[name="unit_id[]"]').val(data[0].unit_id).trigger('change');
            }
        });
    }

    
    function calAmt(row)
    {
        var receive_qty = $(row).parent().parent('tr').find('td input[name="receive_qty[]"]').val();
        var rate = $(row).parent().parent('tr').find('td input[name="rate[]"]').val();
        var amount = parseFloat(receive_qty) * parseFloat(rate);
        
        $(row).parent().parent('tr').find('td input[name="amount[]"]').val(amount);
    }
    
    function calc() 
    { 
        let meter_total = 0;
        let received_total = 0;
        let amount_total = 0;
    
        $('#footable_2 input[name="challan_qty[]"]').each(function() {
            let val = parseFloat($(this).val());
            meter_total += isNaN(val) ? 0 : val;
        });
    
        $('#footable_2 input[name="receive_qty[]"]').each(function() {
            let val = parseFloat($(this).val());
            received_total += isNaN(val) ? 0 : val;
        });
    
        $('#footable_2 input[name="amount[]"]').each(function() {
            let val = parseFloat($(this).val());
            amount_total += isNaN(val) ? 0 : val;
        });
    
        $('#total_qty').val(meter_total); // Optional: limit to 2 decimal places
        $('#total_amt').val(amount_total);
        $('#total_received_meter').val(received_total);
    }

    function AddRow(btn) 
    {
        var currentRow = $(btn).closest('tr');
    
        // Clone the row
        var clonedRow = currentRow.clone();
    
        // Clear input fields except total_meter and total_amount
        clonedRow.find('input').not("#total_meter").not("#total_amount").val('');
    
        // Properly reset and remove Select2 on all select elements
        clonedRow.find('select.select2').each(function () {
            var $select = $(this);
    
            // Destroy Select2
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
    
            // Reset value and remove extra Select2 elements
            $select.val('');
            $select.removeAttr('data-select2-id').removeClass('select2-hidden-accessible').removeAttr('aria-hidden');
            $select.next('.select2').remove();
        });
    
        // Append the clean cloned row
        $('#footable_2 tbody').append(clonedRow);
    
        // Re-initialize Select2 on cloned selects
        clonedRow.find('select.select2').select2({
            width: '100%' // optional, but helps layout
        });
    
        recalcIdcone();
        //calc();
    }


    function RemoveRow(btn) 
    {
        var rowCount = $('#footable_2 tr').length;
        if (rowCount > 1) {
          $(btn).closest('tr').remove();
        } else {
          alert("⚠️ You must keep at least one row.");
        }
        recalcIdcone();
        calc();
    }
    function SetPOCode()
    {
       var po_code1 = $("#po_code1").val();
       var po_code2 = $("#po_code2").val();
       if(po_code1 != '')
       {
          $('#po_code1').attr('name');
          $('#po_code2').removeAttr('name');  
          $('#po_code2').prop('disabled', true);
       }
       else if(po_code2 != '')
       {
          $('#po_code2').attr('name');
          $('#po_code1').removeAttr('name');  
          $('#po_code1').prop('disabled', true);
       }
       else
       {
          $('#po_code1').attr('name');
          $('#po_code2').removeAttr('name'); 
          $('#po_code2').prop('disabled', true);
       }
   }
   
  
   function EnableFields()
   {
       $("select").prop('disabled', false);
   }
   
   function selselect()
   {
       setTimeout(
        function() 
        {
            $("#footable_2 tr td  select[name='item_code[]']").each(function() 
            {
                $(this).closest("tr").find('select[name="item_code[]"]').select2();
            });
       }, 2000);
   }
   function recalcIdcone()
   {
       $.each($("#footable_2 tr"),function (i,el)
       {
            $(this).find("td:first input").val(i); 
       });
   }
</script>
<!-- end row -->
@endsection