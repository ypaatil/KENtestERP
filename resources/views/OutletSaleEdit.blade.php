@extends('layouts.master') 
@section('content')
<style>
    input[type="radio"] {
        transform: scale(1.5); 
        margin-right: 10px;  
    }
  
    .hide
    {
         display:none;    
    }
    
    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Outlet Sale Edit</h4>
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
            @if(isset($OutletSaleMasterList))
            <form action="{{ route('OutletSale.update',$OutletSaleMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
                <div class="row">
                       <div class="col-md-2" style="margin-top: 28px;font-size: 20px;">
                          <div class="mb-3">
                              <label for="employee">
                                <input type="radio" id="employee" name="employee_type" onchange="setCustomer(1);" value="1" {{ $OutletSaleMasterList->employee_type == 1 ? 'checked="checked"' : '' }} required>
                                Employee
                              </label> 
                              <label for="other" style="margin-left: 22px;">
                                <input type="radio" id="other" name="employee_type"  onchange="setCustomer(2);"  value="2" {{ $OutletSaleMasterList->employee_type == 2 ? 'checked="checked"' : '' }} required>
                                Other
                              </label> 
                          </div>
                       </div>  
                </div>
                <div class="row">
                       <div class="col-md-2">
                          <div class="mb-3">
                             <label for="bill_date" class="form-label">Date</label>
                             <input type="date" class="form-control" name="bill_date" id="bill_date" value="{{$OutletSaleMasterList->bill_date}}" required/>
                          </div>
                       </div>
                       <div class="col-md-2">
                          <div class="mb-3">
                             <label for="scan_barcode" class="form-label">Barcode Scan</label> 
                             <input type="text" class="form-control" id="scan_barcode" value="" tabindex="1" />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="bill_no" class="form-label">Bill No</label>
                             <input type="text" class="form-control" name="bill_no" id="bill_no" value="{{$OutletSaleMasterList->bill_no}}" tabindex="2" required/>
                          </div>
                       </div>
                       <div class="col-md-2">
                          <div class="mb-3">
                             <label for="payment_option_id" class="form-label">Payment Type</label>
                             <select name="payment_option_id" class="form-select select2" id="payment_option_id" tabindex="3" required onchange="SetEmployeeData();">
                                <option value="">--Select--</option> 
                                @foreach($paymentOptionList as $options)
                                <option value="{{$options->payment_option_id}}"   {{ $options->payment_option_id == $OutletSaleMasterList->payment_option_id ? 'selected="selected"' : '' }}  >{{$options->payment_option_name}}</option>  
                                @endforeach
                             </select>
                          </div>
                       </div>
                       <div class="col-md-4 employee {{ $OutletSaleMasterList->employee_type != 1 ? 'hide' : '' }}">
                          <div class="mb-3">
                             <label for="employeeCode" class="form-label">Employee Name</label> 
                             <select name="employeeCode" class="form-select select2" id="employeeCode" onchange="GetEmployeeDetails();" >
                                <option value="">--Select--</option> 
                                @foreach($employeeList as $emp)
                                <option value="{{$emp->employeeCode}}"  {{ $emp->employeeCode == $OutletSaleMasterList->employeeCode ? 'selected="selected"' : '' }} >{{$emp->fullName}} ({{$emp->employeeCode}})</option>  
                                @endforeach
                             </select>
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="branch" class="form-label">Branch</label>
                             <input type="text" class="form-control" name="branch" id="branch_id" value="" readonly />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="dept" class="form-label">Department</label>
                             <input type="text" class="form-control" name="dept" id="dept_id" value=""  readonly />
                          </div>
                       </div>
                       <div class="col-md-3 other_customer  {{ $OutletSaleMasterList->employee_type != 2 ? 'hide' : '' }}">
                          <div class="mb-3">
                             <label for="other_customer" class="form-label">Customer Name</label>
                             <input type="text" class="form-control" name="other_customer" id="other_customer" value="{{$OutletSaleMasterList->other_customer}}" tabindex="4" />
                          </div>
                       </div> 
                        <div class="col-md-2">
                          <div class="mb-3">
                            <label for="mobile_no" class="form-label">Mobile No</label>
                            <input type="tel" 
                                   class="form-control" 
                                   name="mobile_no" 
                                   id="mobile_no" 
                                   maxlength="10"
                                   pattern="^[6-9]\d{9}$" 
                                   title="Enter valid 10-digit mobile number starting with 6-9" 
                                   tabindex="5"
                                   value="{{$OutletSaleMasterList->mobile_no}}"/>
                          </div>
                        </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="gst_type" class="form-label">GST Type</label> 
                             <select name="gst_type" class="form-select" id="gst_type" required> 
                                <option value="1" {{  $OutletSaleMasterList->gst_type == 1 ? 'selected="selected"' : '' }}>Maharashtra</option>   
                                <option value="2" {{  $OutletSaleMasterList->gst_type == 2 ? 'selected="selected"' : '' }}>Others</option>   
                             </select>
                          </div>
                       </div>
                          <table id="productTbl" class="table table-bordered dt-responsive nowrap w-100">
                           <thead>
                               <tr>
                                   <th>Remove</th>
                                   <th>Sr No.</th>
                                   <th>Scan Barcode</th> 
                                   <th>Brand Name</th> 
                                   <th>Product Name</th> 
                                   <th>Size</th>
                                   <th>Stock</th>
                                   <th>Rate</th>
                                   <th>Qty</th>
                                   <th>Amount</th> 
                               </tr>
                           </thead>
                           <tbody>
                               @php
                                    $srno = 1;
                               @endphp
                               @foreach($OutletSaleDetailList as $row)
                                <tr>
                                    <td><a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a></td> 
                                    <td><input type="number" step="any" name="srno[]" class="form-control" value="{{$srno++}}" style="width:80px;" readonly></td>
                                    <td>
                                        <input type="text"  name="scan_barcode[]" class="form-control" value="{{$row->scan_barcode}}"  style="width:120px;" readonly>
                                        <input type="hidden"  name="product_id[]" class="form-control" value="{{$row->product_id}}"  style="width:120px;" readonly>
                                        <input type="hidden"  name="style_no[]" class="form-control" value="{{$row->style_no}}"  style="width:120px;" readonly>
                                    </td>
                                    <td><input type="text" name="brand_name[]" class="form-control" value="{{$row->brand_name}}" style="width:80px;" readonly>
                                        <input type="hidden" name="brand_id[]" class="form-control" value="{{$row->brand_id}}" style="width:80px;" readonly>
                                    </td> 
                                    <td><input type="text"  name="product_name[]" class="form-control" value="{{$row->product_name}}"  style="width:120px;" readonly></td> 
                                    <td><input type="hidden"  name="size_id[]" class="form-control" value="{{$row->size_id}}"><input type="text"  name="size_name[]" class="form-control" value="{{$row->size_name}}" style="width:100px;" readonly></td>
                                    <td><input type="number" step="any" name="stock_qty[]" class="form-control" value="{{$row->stock_qty}}" readonly  style="width:100px;"></td>  
                                    <td><input type="number" step="any" name="rate[]" class="form-control" value="{{$row->rate}}" readonly  style="width:100px;"></td> 
                                    <td><input type="number" step="any" name="qty[]" class="form-control" value="{{$row->qty}}" onchange="calQty(this);"  style="width:100px;" readonly></td> 
                                    <td>
                                        <input type="number" step="any" name="amount[]" class="form-control" value="{{$row->amount}}" readonly  style="width:100px;">
                                        <input type="hidden" step="any" name="discount[]" class="form-control" value="{{$row->discount}}" onchange="calQty(this);"  style="width:100px;">
                                        <input type="hidden" step="any" name="discount_amount[]" class="form-control" value="{{$row->discount_amount}}" style="width:100px;">
                                        <input type="hidden" step="any" name="gst_per[]" class="form-control" value="{{$row->gst_per}}" onchange="calQty(this);"  style="width:100px;">
                                        <input type="hidden" step="any" name="gst_amount[]" class="form-control" value="{{$row->gst_amount}}" readonly  style="width:100px;">
                                        <input type="hidden" step="any" name="total_amount[]" class="form-control" value="{{$row->total_amount}}" readonly  style="width:100px;">
                                    </td> 
                                </tr>
                              @endforeach
                           </tbody>
                           <tfoot>
                               <tr>
                                   <th></th>
                                   <th></th>
                                   <th></th> 
                                   <th></th> 
                                   <th></th>
                                   <th></th>
                                   <th></th>
                                   <th style="font-size:20px;">Total</th>
                                   <th style="font-size:20px;" id="total_qty1">{{$OutletSaleMasterList->total_qty}}</th>
                                   <th style="font-size:20px;" id="gross_amount1">{{$OutletSaleMasterList->gross_amount}}</th> 
                               </tr>
                            </tfoot>
                       </table>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="total_qty" class="form-label">Total Qty</label>
                             <input type="number" step="any" class="form-control" name="total_qty" id="total_qty" value="{{$OutletSaleMasterList->total_qty}}" readonly />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="gross_amount" class="form-label">Gross Amount</label>
                             <input type="number" step="any" class="form-control" name="gross_amount" id="gross_amount" value="{{$OutletSaleMasterList->gross_amount}}" readonly />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="total_disc_amount" class="form-label">Total Discount Amount</label>
                             <input type="number" step="any" class="form-control" name="total_disc_amount" id="total_disc_amount" value="{{$OutletSaleMasterList->total_disc_amount}}" readonly />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="total_gst_amount" class="form-label">Total GST Amount</label>
                             <input type="number" step="any" class="form-control" name="total_gst_amount" id="total_gst_amount" value="{{$OutletSaleMasterList->total_gst_amount}}" readonly />
                          </div>
                       </div>
                       <div class="col-md-2 hide">
                          <div class="mb-3">
                             <label for="net_amount" class="form-label">Net Amount</label>
                             <input type="number" step="any" class="form-control" name="net_amount" id="net_amount" value="{{$OutletSaleMasterList->net_amount}}" readonly />
                          </div>
                       </div>
                       <div class="col-md-3">
                          <div class="mb-3">
                             <label for="remark" class="form-label">Remark</label>
                             <input type="text" class="form-control" name="remark" id="remark" value="{{$OutletSaleMasterList->remark}}" />
                          </div>
                       </div>
                    </div>
                <div class="col-sm-4">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();mycalc();" id="Submit" tabindex="6">Submit</button>
                     <a href="{{ Route('OutletSale.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script>


    function SetEmployeeData()
    {
        var payment_option_id = $("#payment_option_id").val();
        
        if(parseInt(payment_option_id) == 3)
        {
            $("#employee").trigger("click");
            $("#other").attr("disabled", true);
            $("#employeeCode").attr('required', true);
        }
        else
        {
            $("#other").attr("disabled", false);
            $("#employeeCode").attr('required', false);
        }
    }


  $(document).on("input", "input[type=number]", function () {
        let val = parseInt($(this).val(), 10);
    
        if (val < 0 || isNaN(val)) {
            alert("Negative values are not allowed!");
            $(this).val(0); // reset to 0
        }
    });

    $(document).on("input", "#mobile_no", function () {
        let val = $(this).val();
    
        // Remove non-digits
        val = val.replace(/\D/g, '');
        $(this).val(val);
    
        // Enforce 10 digits only
        if (val.length > 10) {
            $(this).val(val.substring(0, 10));
        }
    });
    
    // $("#frmData").on("submit", function (e) {
    //     let mobile = $("#mobile_no").val();
    //     let regex = /^[6-9]\d{9}$/;
    
    //     if (!regex.test(mobile)) {
    //         alert("Please enter a valid 10-digit mobile number starting with 6,7,8, or 9.");
    //         e.preventDefault();
    //     }
    // });
    
   $(document).ready(function() 
   {
        $('#other_customer').on('input', function() {
            // Convert entered text to uppercase
            $(this).val($(this).val().toUpperCase());
        });
        
        setTimeout(function () {
            $("#other").trigger("click");
        }, 1000);
    
        let debounceTimer;
        const debounceDelay = 300; // Delay in milliseconds
    
        $('#scan_barcode').focus();
    
        $('#scan_barcode').on('input', function() {
            clearTimeout(debounceTimer);
    
            debounceTimer = setTimeout(() => {
                const barcode = $(this).val().trim();
                console.log(barcode);
                if (barcode.length >= 8 && barcode.length <= 21) {
                    GetBarcodeDetails();
                } else {
                    alert("Invalid Barcode");
                    $('#scan_barcode').val('');
                    $('#scan_barcode').focus();
                }
            }, debounceDelay);
        });
    });

    
    
   function calAmount(row)
   {
       var total_qty = 0;
       var gross_amount = 0;
       var discount_amount = 0;
       var gst_amount = 0;
       
       $("#productTbl > tbody > tr").find('td input[name="qty[]"]').each(function()
       {
           total_qty += parseFloat($(this).val());
       });
       $("#productTbl > tbody > tr").find('td input[name="amount[]"]').each(function()
       {
           gross_amount += parseFloat($(this).val());
       });
       $("#productTbl > tbody > tr").find('td input[name="discount_amount[]"]').each(function()
       {
           discount_amount += parseFloat($(this).val());
       });
       $("#productTbl > tbody > tr").find('td input[name="gst_amount[]"]').each(function()
       {
           gst_amount += parseFloat($(this).val());
       });
       
       var net_amount = parseFloat(gross_amount) + parseFloat(gst_amount) - parseFloat(discount_amount);
       $("#total_qty").val(total_qty.toFixed(2));
       $("#gross_amount").val(gross_amount.toFixed(2));
       $("#total_qty1").html(total_qty);
       $("#gross_amount1").html(gross_amount.toFixed(2));
       $("#total_disc_amount").val(discount_amount.toFixed(2));
       $("#total_gst_amount").val(gst_amount.toFixed(2));
       $("#net_amount").val(net_amount.toFixed(2));
   }
   
   

   function setCustomer(ele)
   {
       if(ele == 1)
       {
           $('.employee').removeClass("hide");
           $('.other_customer').addClass("hide");
           $("#employeeCode").attr('required', true);
           $("#other_customer").removeAttr('required');
       }
       else if(ele == 2)
       {
           $('.employee').addClass("hide");
           $('.other_customer').removeClass("hide");
           $("#employeeCode").removeAttr('required');
           $("#other_customer").attr('required', true);
       }
       $('#branch_id').addClass("hide");
       $('#dept_id').addClass("hide");
   }
   
   function EnableFeilds()
   {
       $('select').removeAttr('disabled');
   }
   
   function GetEmployeeDetails()
   {
        var employeeCode = $('#employeeCode').val();
         
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetEmployeeDetails') }}",
          data:{'employeeCode':employeeCode},
          success: function(data)
          {  
               $('#branch_id').val(data[0].branch_name);
               $('#dept_id').val(data[0].dept_name); 
          }
        });
   }
    function calQty(row, existingRow)
    {  
        // Always work with the row of qty input
        var tr = $(row).closest('tr');
    
        var qty       = tr.find('input[name="qty[]"]').val();
        var rate      = tr.find('input[name="rate[]"]').val();
        var discount  = tr.find('input[name="discount[]"]').val(); 
        var gst_per   = tr.find('input[name="gst_per[]"]').val(); 
        var stock_qty = tr.find('input[name="stock_qty[]"]').val(); 
        
        if (parseInt(qty) <= parseInt(stock_qty)) {
            var amount = parseFloat(qty) * parseFloat(rate);
            var discount_amount = 0;  
            var gst_amount = 0;
            
            if (parseFloat(discount) > 0) {
                discount_amount = parseFloat(amount) * (parseFloat(discount) / 100); 
            }
            
            var total = amount - discount_amount;
            
            if (parseFloat(gst_per) > 0) {
                gst_amount = total * (parseFloat(gst_per) / 100); 
            }
        
            var total_amount = total + gst_amount;  
    
            // Set calculated values
            tr.find('input[name="amount[]"]').val(amount.toFixed(2));
            tr.find('input[name="discount_amount[]"]').val(discount_amount.toFixed(2));
            tr.find('input[name="gst_amount[]"]').val(gst_amount.toFixed(2));
            tr.find('input[name="total_amount[]"]').val(total_amount.toFixed(2));
            
            calAmount(row); // your external function for recalculation
        } else {
            alert("Please enter Quantity less than or equal to available stock ("+ stock_qty +")");
            tr.find('input[name="qty[]"]').val(stock_qty);
        } 
    }

    function GetBarcodeDetails() 
    {
        var scan_barcode = $('#scan_barcode').val();
    
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('GetBarcodeDetailsTest') }}",
            data: { 'scan_barcode': scan_barcode },
            success: function(data) {
                if (scan_barcode != '') {
                    // Check if the barcode already exists in the table
                    var existingRow = $("#productTbl > tbody").find('tr').filter(function() {
                        return $(this).find('input[name="scan_barcode[]"]').val() === scan_barcode;
                    });
    
                    if (existingRow.length > 0) {
                        // Barcode found, increase the quantity
                        var qtyInput = existingRow.find('input[name="qty[]"]');
                        qtyInput.val(parseInt(qtyInput.val()) + 1);
                        calQty(qtyInput, existingRow);
    
                    } else {
                        // Barcode not found, append the new row
                        $("#productTbl > tbody").append(data.html);
    
                        // 🔹 Now fetch the row you just appended
                        existingRow = $("#productTbl > tbody tr").last();
                        var qtyInput = existingRow.find('input[name="qty[]"]');
                        qtyInput.val(1);
    
                        calQty(qtyInput, existingRow);
                    }
                }
    
                $('#scan_barcode').val('');
                recalcIdcone();
            }
        });
    }

   
   function removeRow(row)
   { 
      $(row).parents('tr').remove(); 
   }
   
   function recalcIdcone()
   {
       $.each($("#productTbl tr"),function (i,el)
       {
            $(this).find("td:eq(1) input").val(i); 
       })
   }
   
   
</script>
<!-- end row -->
@endsection