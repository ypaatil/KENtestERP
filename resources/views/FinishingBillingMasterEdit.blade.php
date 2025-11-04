@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Finishing Rate Master Edit</h4>
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
            @if(isset($FinishingBillingMaster))
            <form action="{{ route('FinishingBilling.update',$FinishingBillingMaster) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <input type="hidden" name="finishing_billing_code" class="form-control" id="finishing_billing_code" value="{{ $FinishingBillingMaster->finishing_billing_code}}"> 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="finishing_billing_date" class="form-label">Date</label>
                        <input type="date" name="finishing_billing_date" class="form-control" id="finishing_billing_date" value="{{ $FinishingBillingMaster->finishing_billing_date}}" required>
                     </div>
                  </div>  
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="perticular_id" class="form-label">Particulars</label> 
                        <select name="perticular_id" class="form-select select2" id="perticular_id" onchange="SetBillNo();" disabled>
                           <option value="">--Select--</option>
                           @foreach($perticularList as  $row) 
                                <option value="{{ $row->perticular_id }}" {{ $row->perticular_id == $FinishingBillingMaster->perticular_id ? 'selected="selected"' : '' }} >{{ $row->perticular_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bill_no" class="form-label">Bill No.</label>
                        <input type="text" name="bill_no" class="form-control" id="bill_no" value="{{ $FinishingBillingMaster->bill_no}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label> 
                        <select name="supplier_id" class="form-select" id="supplier_id" required>
                           <option value="">--Select--</option>
                           @foreach($Ledger as  $row) 
                                <option value="{{ $row->ac_code }}"  {{ $row->ac_code == $FinishingBillingMaster->supplier_id ? 'selected="selected"' : '' }}  >{{ $row->ac_short_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row"> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr> 
                                 <th>Perticular</th>
                                 <th>Sales Order No.</th> 
                                 <th>Buyer Brand</th>
                                 <th>Style</th>
                                 <th warp>Pack Inward Qty</th>
                                 <th warp>Pack Qty <br/> Till Date </th>
                                 <th warp>Bill Qty <br/> Till Date </th>
                                 <th warp>Balance To Billing</th>
                                 <th warp>Invoice <br/> Qty</th>
                                 <th>Rate</th>
                                 <th>Amt</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                               @foreach($FinishingBillingDetailList as $rows)
                               @php
                                    $BillingData = DB::SELECT("SELECT sum(packing_qty) as till_date_billing_qty FROM finishing_billing_details 
                                                            INNER JOIN  finishing_billing_master ON finishing_billing_master.finishing_billing_code = finishing_billing_details.finishing_billing_code
                                                            WHERE sales_order_no ='".$rows->sales_order_no."' AND perticular_ids =".$rows->perticular_ids);  
                                    $till_date_billing_qty = isset($BillingData[0]->till_date_billing_qty) ? $BillingData[0]->till_date_billing_qty : 0;
                               @endphp
                              <tr> 
                                 <td>
                                    <select name="perticular_ids[]" class="form-select" style="width:150px;" onchange="SetPerticular(this);" disabled>
                                       <option value="">--Select--</option>
                                       @foreach($perticularList1 as  $row) 
                                            <option value="{{ $row->perticular_id }}" {{ $row->perticular_id == $rows->perticular_ids ? 'selected="selected"' : '' }} >{{ $row->perticular_name }}</option> 
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="sales_order_no[]" class="form-select"  onchange="getSalesOrderDetails(this);" style="width:150px;" disabled>
                                       <option value="">--Sales Order No--</option>
                                       @foreach($SalesOrderList as  $row) 
                                            <option value="{{ $row->sales_order_no }}" {{ $row->sales_order_no == $rows->sales_order_no ? 'selected="selected"' : '' }} >{{ $row->sales_order_no }}</option> 
                                       @endforeach
                                    </select>
                                 </td> 
                                 <td>
                                    <select name="brand_id[]" class="form-select"  style="width:150px;" disabled>
                                       <option value="">--Brand--</option>
                                       @foreach($BrandList as  $row)
                                          <option value="{{ $row->brand_id }}" {{ $row->brand_id == $rows->brand_id ? 'selected="selected"' : '' }}>{{ $row->brand_name }}</option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="fg_id[]" class="form-select"  style="width:150px;" disabled>
                                       <option value="">--Select Style--</option>
                                       @foreach($FGList as  $row)
                                          <option value="{{ $row->fg_id }}" {{ $row->fg_id == $rows->fg_id ? 'selected="selected"' : '' }}>{{ $row->fg_name }}</option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="number" step="any" name="till_date_packing_inward_qty[]"  class="form-control" value="{{$rows->till_date_packing_inward_qty}}"  style="width:80px;" readonly/></td>
                                 <td><input type="number" step="any" name="till_date_packing_qty[]"  class="form-control" value="{{$rows->till_date_packing_qty}}"  style="width:80px;" readonly/></td>
                                 <td><input type="number" step="any" name="till_date_billing_qty[]"  class="form-control"  value="{{$till_date_billing_qty}}" style="width:80px;" readonly/></td>
                                 <td><input type="number" step="any" name="till_date_balance_qty[]"  class="form-control"  value="{{$rows->till_date_packing_qty-$till_date_billing_qty}}" style="width:80px;" readonly/></td>
                                 <td><input type="number" step="any" name="packing_qty[]"  class="form-control"  value="{{$rows->packing_qty}}" @if($rows->perticular_ids == 8 || $rows->perticular_ids == 9 || $rows->perticular_ids == 12) max="{{min($rows->till_date_balance_qty, $rows->till_date_packing_inward_qty)}}"  @endif style="width:80px;" oninput="CalculateAmount(this);"  /></td>
                                 <td><input type="number" step="any" name="rate[]"  class="form-control"  value="{{ sprintf("%.2f", $rows->rate) }}" oninput="CalculateAmount(this);" style="width:100px;" @if($rows->perticular_ids == 8 || $rows->perticular_ids == 9 || $rows->perticular_ids == 12) readonly @endif /></td>
                                 <td><input type="number" step="any" name="amount[]"  class="form-control"  value="{{ sprintf("%.2f", $rows->amount) }}" style="width:120px;" readonly/></td>
                                 <td><button type="button" onclick="AddNew();" class="btn btn-warning pull-left">+</button></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X"  style="margin-left:10px;"></td>
                              @endforeach
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label> 
                        <input type="number" step="any" name="total_qty" class="form-control" id="total_qty" value="{{ $FinishingBillingMaster->total_qty}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label> 
                        <input type="number" step="any" name="total_amount" class="form-control" id="total_amount" value="{{ $FinishingBillingMaster->total_amount}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="narration" class="form-label">Narration</label> 
                        <input type="text" name="narration" class="form-control" id="narration" value="{{ $FinishingBillingMaster->narration}}">
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Update</button>
                     <a href="{{ Route('FinishingBilling.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script>
    
    function mycalc()
    {
        var total_qty = 0;
        var total_amount = 0;
        $('#footable_2 > tbody > tr').find('td input[name="packing_qty[]"]').each(function()
        {
            total_qty += parseFloat($(this).val());
        });   
        
        $('#footable_2 > tbody > tr').find('td input[name="amount[]"]').each(function()
        {
            total_amount += parseFloat($(this).val());
        });  
        
        $("#total_qty").val(total_qty);
        $("#total_amount").val(total_amount);
        
    }
    
    function SetPerticular(row)
    { 
        $(row).select2('destroy');
        $(row).attr("disabled", true);
        $(row).parent().parent('tr').find('td select[name="sales_order_no[]"]').removeAttr("disabled");
        
        var perticular_ids = $(row).parent().parent('tr').find('td select[name="perticular_ids[]"]').val();        
        if(perticular_ids == 8 || perticular_ids == 9 || perticular_ids == 12 )
        {
            $(row).parent().parent('tr').find('td input[name="rate[]"]').attr('readonly');
        }
        else
        {
            $(row).parent().parent('tr').find('td input[name="rate[]"]').removeAttr('readonly');
        } 
    }
    
    function CalculateAmount(row)
    {
        var packing_qty = $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').val();
        var max = $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').attr("max"); 
        
        var perticular_ids = $(row).parent().parent('tr').find('td select[name="perticular_ids[]"]').val();        
        if(perticular_ids == 8 || perticular_ids == 9 || perticular_ids == 12 )
        {
            if(parseInt(max) < parseInt(packing_qty))
            {
                alert("Packing Qty Should be less than "+max);
                $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').val(0);
            }
            else
            {
                var rate = $(row).parent().parent('tr').find('td input[name="rate[]"]').val();
                var amount = parseFloat(packing_qty * rate);
                $(row).parent().parent('tr').find('td input[name="amount[]"]').val(amount);
            }
        }
        else
        {
            var rate = $(row).parent().parent('tr').find('td input[name="rate[]"]').val();
            var amount = parseFloat(packing_qty * rate);
            $(row).parent().parent('tr').find('td input[name="amount[]"]').val(amount);
        }
        
        mycalc();
    }
    
    
    function SetBillNo()
    {
        
        var perticular_id = $("#perticular_id").val();
        var counter = $("#counter").val();
        var month = $("#month").val();
        
        $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('GetPerticularCode') }}",
               data:{'perticular_id':perticular_id},
               success: function(data)
               { 
                   $("#bill_no").val(data.financial_year+"/"+month+"/"+data.perticular_code+counter);
               }
        });
    }
    
    function AddNew()
    {
        // Destroy Select2 before cloning to prevent issues
        $('.select2').select2('destroy');
    
        // Clone the last row and reset values
        var newRow = $('#footable_2 tbody tr:last').clone();
        newRow.find('input').not('.btn-danger').val('');
        newRow.find('select').val(''); 
    
        // Enable the 'perticular_ids[]' select field in the new row
        newRow.find('select[name="perticular_ids[]"]').removeAttr("disabled");
    
        // Disable the 'sales_order_no[]' select field in the new row
        newRow.find('select[name="sales_order_no[]"]').prop("disabled", true);
    
        // Remove old Select2 instance from the cloned row
        newRow.find('.select2').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').removeAttr('aria-hidden');
    
        // Append the new row to the table
        $('#footable_2 tbody').append(newRow);
    
        // Reinitialize Select2 for all select elements within the new row
        //newRow.find('select').select2();
    
        // Handle mouse events
        var isDropdownOpen = false;
        
        $('select').off('mouseenter mouseleave select2:open select2:close');
    
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
            }, 3000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
    }

    $(document).ready(function() {
        $('#frmData').submit(function() {
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
            }, 3000); 
        });
         
        $('select').on('select2:open', function() {
            isDropdownOpen = true;
        });
         
        $('select').on('select2:close', function() {
            isDropdownOpen = false;
        });
        
        mycalc();
    });
    
    
    
    function getSalesOrderDetails(row)
    { 
        $(row).parent().parent('tr').find('td input[name="till_date_packing_inward_qty[]"]').val(0);
        $(row).parent().parent('tr').find('td input[name="till_date_packing_qty[]"]').val(0);
        $(row).parent().parent('tr').find('td input[name="rate[]"]').val(0);
        $(row).parent().parent('tr').find('td input[name="till_date_billing_qty[]"]').val(0);
        $(row).parent().parent('tr').find('td input[name="till_date_balance_qty[]"]').val(0);
        $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').val(0);
        
        var sales_order_no = $(row).val();
        var perticular_id = $("#perticular_id").val();
        
         let duplicateFound = false;
        
        // Create an array to store the pairs of perticular_id and sales_order_no
        let selectedPairs = [];
    
        // Iterate through all rows and collect the select values
        $('tbody > tr').each(function () {
            let perticularId = $(this).find('select[name="perticular_ids[]"]').val();
            let salesOrderNo = $(this).find('select[name="sales_order_no[]"]').val();
    
            // Only check if both fields are selected
            if (perticularId && salesOrderNo) {
                let pair = `${perticularId}-${salesOrderNo}`;
    
                // Check if the pair already exists
                if (selectedPairs.includes(pair)) {
                    duplicateFound = true;
                    return false; // Break out of the loop
                }
    
                // Add the pair to the list
                selectedPairs.push(pair);
            }
        }); 
        // Show an alert if duplicate pairs are found
        if (duplicateFound) 
        {
            let selectBox = $(row).parent().parent('tr').find('select[name="sales_order_no[]"]');
            selectBox.val(''); // Reset the value
            selectBox.select2('destroy'); // Safely destroy Select2
            alert('Duplicate combination of Particular and Sales Order detected!');
            selectBox.select2(); // Reinitialize Select2
        }
        else
        {  
            $.ajax({
                   type: "GET",
                   dataType:"json",
                   url: "{{ route('SalesOrderDetails') }}",
                   data:{'sales_order_no':sales_order_no},
                   success: function(data)
                   {
                   
                           $(row).parent().parent('tr').find('td select[name="brand_id[]"]').val(data[0]['brand_id']); 
                           $(row).parent().parent('tr').find('td select[name="fg_id[]"]').val(data[0]['fg_id']);
                           
                           $(row).parent().parent('tr').find('td select[name="brand_id[]"]').attr('disabled', true); 
                           $(row).parent().parent('tr').find('td select[name="fg_id[]"]').attr('disabled', true);
                           
                           var perticular_ids = $(row).parent().parent('tr').find('td select[name="perticular_ids[]"]').val();
                           var finishing_billing_date = $("#finishing_billing_date").val();
                           
                           $.ajax({
                               type: "GET",
                               dataType:"json",
                               url: "{{ route('GetPackingQtySalesOrderWise') }}",
                               data:{'sales_order_no':sales_order_no, 'perticular_id':perticular_ids, 'finishing_billing_date':finishing_billing_date},
                               success: function(data1)
                               {
                                   $(row).parent().parent('tr').find('td input[name="till_date_packing_inward_qty[]"]').val(data1.total_packing_inward);
                                   $(row).parent().parent('tr').find('td input[name="till_date_packing_qty[]"]').val(data1.total_packing);
                                   $(row).parent().parent('tr').find('td input[name="rate[]"]').val(data1.total_finishing);
                                   $(row).parent().parent('tr').find('td input[name="till_date_billing_qty[]"]').val(data1.till_date_billing_qty);
                                   $(row).parent().parent('tr').find('td input[name="till_date_balance_qty[]"]').val(parseFloat(data1.total_packing - data1.till_date_billing_qty));
                                   
                                   if(perticular_ids == 8 || perticular_ids == 9 || perticular_ids == 12)
                                   {
                                        var n1 = Math.abs(parseFloat(data1.total_packing - data1.till_date_billing_qty));
                                        var n2 = Math.abs(data1.total_packing_inward);
                                         
                                        var minValue = Math.min(n1, n2);
                                        $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').attr("max",  Math.abs(minValue));
                                   }
                                   else
                                   {
                                       $(row).parent().parent('tr').find('td input[name="packing_qty[]"]').removeAttr("max");
                                       $(row).parent().parent('tr').find('td input[name="till_date_billing_qty[]"]').val(0);
                                       $(row).parent().parent('tr').find('td input[name="till_date_balance_qty[]"]').val(0);
                                   }
                                   
                                    mycalc();
                               }
                           });
                    }
            });
        }
        
     
        mycalc();
    }
   
   
   function EnableFields()
   {
      $("select").removeAttr('disabled');
      $("input").removeAttr('disabled');
   }
   
   
   
   function deleteRowcone1(btn) 
   { 
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       
       mycalc();
   }
   
    function getSubStyle(val) 
    {	
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
    {	
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
@endsection