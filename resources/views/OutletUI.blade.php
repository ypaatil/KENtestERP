<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ken Global Designs Pvt. Ltd.</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .product-card {
      border-radius: 12px;
      border: 1px solid #e9ecef;
      transition: 0.2s;
      height: 100%;
      cursor: pointer;
    }
    .product-card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      transform: translateY(-3px);
    }
    .product-card img {
      max-height: 180px;
      object-fit: contain;
      margin: 10px auto;
    }
    .qty-control {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .qty-btn {
      border: 1px solid #dee2e6;
      padding: 2px 10px;
      border-radius: 6px;
      background: #fff;
      cursor: pointer;
    }
    .qty-btn:hover {
      background: #f1f1f1;
    }
    .qty-input {
      width: 50px;
      text-align: center;
      margin: 0 6px;
    }
    .order-list {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .order-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid #f1f1f1;
    }
    .order-item:last-child {
      border-bottom: none;
    }
    .discount-box {
      background: #f3f0ff;
      color: #5a32d6;
      border-radius: 8px;
      padding: 10px;
      margin-top: 10px;
      font-size: 14px;
    }
    .bottom-actions button {
      border-radius: 10px;
      margin: 5px;
      min-width: 100px;
    }
    .product-title {
      font-size: 15px;
      font-weight: 600;
    }
    .product-price {
      font-size: 14px;
      color: #555;
    }
    .row.g-3 { flex-direction: column !important; } 
    .sale-header {
      background: linear-gradient(135deg, #ff9933, #ff5e62); /* festive gradient */
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      padding: 20px;
    }
    
    .sale-header .cart-title {
      font-size: 1.8rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: 2px;
      text-transform: uppercase;
      text-align: center;
      position: relative;
    }
    
    .sale-header .cart-title::after {
      content: "";
      display: block;
      width: 80px;
      height: 3px;
      background: #fff;
      margin: 8px auto 0;
      border-radius: 2px;
    }
    .hide
    {
        display:none;
    }
    table input,
    table select,
    table textarea {
      border: none !important;
      outline: none !important;
      box-shadow: none !important;
      background-color: #f8f9fa; /* light gray background */
      padding: 8px 10px;
      border-radius: 6px;
    }
    .GrandTotals {
      font-size: 20px;
      background: #f9f9fb;
      border-radius: 10px;
      font-weight:900;
    }
    
    .totals-box {
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .totals-box span {
      font-size: 14px;
    }
    
    .net-payable-box {
      background: linear-gradient(135deg, #3f2b96, #a8c0ff);
      border-radius: 10px;
      padding: 16px;
      color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }
    
    .net-payable-box .payable-text {
      background: #fff;
      color: #d97706;
      padding: 8px;
      border-radius: 6px;
      font-size: 25px;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }
    
    .net-payable-box1 .payable-text {
      background: #fff;
      color: #d97706;
      padding: 8px;
      border-radius: 6px;
      font-size: 18px;
      box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }
    
    
    .net-payable-box label {
      font-size: 13px;
      font-weight: 500;
    }
    
    .net-payable-box input {
      font-size: 14px;
      font-weight: bold;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .form-check-label {
      font-size: 13px;
      cursor: pointer;
    }


  </style>
</head>
<body>
  <div class="container-fluid py-3">
    <div class="col-lg-12">
      <div class="card product-card sale-header">
          <div class="row">
            <div class="col-md-2">
                <img src="{{ URL::asset('images/ken_logo.png')}}" loading="lazy" class="img-fluid">
            </div>
            <div class="col-md-9" style="margin-top: 25px;">
                <div class="cart-title">✨ DIWALI OUTLET SALE ✨</div>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
      <!-- Left Section -->
       <div class="col-lg-2">
      <div class="col-lg-12">
          <!-- Product Card -->
            <div class="card product-card p-2 text-center">
              <img src="{{ URL::asset('images/shirt.jpg')}}" loading="lazy" class="img-fluid">
              <div class="product-title">Shirt</div>
            </div>
      </div>
      <div class="col-lg-12">
          <!-- Product Card -->
            <div class="card product-card p-2 text-center">
              <img src="{{ URL::asset('images/boxer.jpg')}}" loading="lazy" class="img-fluid">
              <div class="product-title">Boxer</div>
            </div>
      </div>
      <div class="col-lg-12">
          <!-- Product Card -->
            <div class="card product-card p-2 text-center">
              <img src="{{ URL::asset('images/pyjama.jpg')}}" loading="lazy" class="img-fluid">
              <div class="product-title">Pyjama</div>
            </div>
      </div>
      </div>
      <!-- Right Section -->
      <div class="col-lg-10">
        <div class="order-list">
          <div class="row mb-2">
            <div class="col-md-2">
                  <div class="mb-3">
                     <label for="bill_date" class="form-label">Date</label>
                     <input type="date" class="form-control" name="bill_date" id="bill_date" value="{{date('Y-m-d')}}" required/>
                  </div>
               </div> 
               <div class="col-md-2 hide">
                  <div class="mb-3">
                     <label for="bill_no" class="form-label">Bill No</label>
                     <input type="text" class="form-control" name="bill_no" id="bill_no" value="KEN1001" required/>
                  </div>
               </div>
               <div class="col-md-2" style="margin-top: 28px;font-size: 20px;">
                  <div class="mb-3">
                      <label for="employee">
                        <input type="radio" id="employee" name="employee_type" onchange="setCustomer(1);" value="1" required>
                        Employee
                      </label> 
                      <label for="other" style="margin-left: 22px;">
                        <input type="radio" id="other" name="employee_type"  onchange="setCustomer(2);"  value="2" required>
                        Other
                      </label> 
                  </div>
               </div>  
               <div class="col-md-4 employee hide">
                  <div class="mb-3">
                     <label for="employeeCode" class="form-label">Employee Name</label> 
                     <select name="employeeCode" class="form-select select2" id="employeeCode" onchange="GetEmployeeDetails();" >
                        <option value="">--Select--</option>  
                     </select>
                  </div>
               </div>
               <div class="col-md-2 employee hide">
                  <div class="mb-3">
                     <label for="branch" class="form-label">Branch</label>
                     <input type="text" class="form-control" name="branch" id="branch_id" value="" readonly />
                  </div>
               </div>
               <div class="col-md-2 employee hide">
                  <div class="mb-3">
                     <label for="dept" class="form-label">Department</label>
                     <input type="text" class="form-control" name="dept" id="dept_id" value=""  readonly />
                  </div>
               </div>
               <div class="col-md-3 other_customer hide">
                  <div class="mb-3">
                     <label for="other_customer" class="form-label">Customer Name</label>
                     <input type="text" class="form-control" name="other_customer" id="other_customer" value="" />
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
                           required />
                  </div>
                </div> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="payment_option_id" class="form-label">Payment Option</label>
                     <select name="payment_option_id" class="form-select select2" id="payment_option_id" required>
                        <option value="">--Select--</option> 
                        <option value="1">UPI</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-1">
                  <div class="mb-3">
                     <label for="gst_type" class="form-label">GST Type</label> 
                     <select name="gst_type" class="form-select" id="gst_type" required> 
                        <option value="1">CGST</option>   
                        <option value="2">IGST</option>   
                     </select>
                  </div>
               </div>
          </div>

          <div class="row alert alert-light border"> 
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="scan_barcode" class="form-label">Barcode Scan</label> 
                     <input type="text" class="form-control" id="scan_barcode" value="" />
                  </div>
               </div>  
               
              <div class="col-md-8"></div> 
              <div class="col-md-2 net-payable-box1 mt-3">
                <div class="text-center fw-bold payable-text mb-3">
                  Total Sale: <span>₹10000.00</span>
                </div>
              </div>
          </div>

          <h6 class="fw-bold">Order Details</h6>

          <div class="order-item">
            
                  <table id="productTbl" class="table table-bordered dt-responsive nowrap w-100">
                   <thead>
                       <tr>
                           <th>Remove</th>
                           <th>Scan Barcode</th>
                           <th>Product Id</th>
                           <th>Product Name</th>
                           <th class="hide">Style No.</th>
                           <th>Qty</th>
                           <th>Size</th>
                           <th>Stock</th>
                           <th>Rate</th>
                           <th>Amount</th>
                           <th class="hide">Disc %</th>
                           <th class="hide">Disc Amount</th>
                           <th>GST %</th>
                           <th>GST Amount</th>
                           <th>Total Amount</th>
                       </tr>
                   </thead>
                   <tbody>
                        <tr>
                            <td><a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a></td> 
                            <td><input type="text"  name="scan_barcode[]" class="form-control" value="P68756"  style="width:120px;" readonly></td>
                            <td><input type="text"  name="product_id[]" class="form-control" value="KEN01245"  style="width:120px;" readonly></td>  
                            <td><input type="text"  name="product_name[]" class="form-control" value="Shirt"  style="width:120px;" readonly></td>
                            <td class="hide"><input type="text"  name="style_no[]" class="form-control" value="0"  style="width:120px;" readonly></td> 
                            <td><input type="number" step="any" name="qty[]" class="form-control" value="0" onchange="calQty(this);"  style="width:100px;"></td> 
                            <td><input type="hidden"  name="size_id[]" class="form-control" value="0">
                            <input type="text"  name="size_name[]" class="form-control" value="0" style="width:100px;" readonly></td> 
                            <td><input type="number" step="any" name="stock_qty[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                            <td><input type="number" step="any" name="rate[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                            <td><input type="number" step="any" name="amount[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                            <td class="hide"><input type="number" step="any" name="discount[]" class="form-control" value="0" onchange="calQty(this);"  style="width:100px;"></td> 
                            <td class="hide"><input type="number" step="any" name="discount_amount[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                            <td><input type="number" step="any" name="gst_per[]" class="form-control" value="0" onchange="calQty(this);"  style="width:100px;"></td> 
                            <td><input type="number" step="any" name="gst_amount[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                            <td><input type="number" step="any" name="total_amount[]" class="form-control" value="0" readonly  style="width:100px;"></td> 
                        </tr>
                   </tbody>
               </table>
          </div>
          <div class="row">
              <div class="col-md-8"></div>
              <div class="col-md-4">
                <div class="mt-3 GrandTotals p-3">
                  
                  <!-- Totals Section -->
                  <div class="">
                    <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">Bill Summary</h6>
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span>Total Items</span>
                      <span class="fw-semibold">5</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span>Total Qty.</span>
                      <span class="fw-semibold">5</span>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span>Gross Amount</span>
                      <span class="fw-semibold">310.50</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span>Total Discount Amount</span>
                      <span class="fw-semibold">-30.50</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                      <span>Total GST Amount</span>
                      <span class="fw-semibold">15.00</span>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="d-flex justify-content-between fw-bold fs-5">
                      <span><b>Grand Total</b></span>
                      <span>₹295.00</span>
                    </div>
                  </div>
                  
                  <!-- Net Payable Section -->
                  <div class="net-payable-box mt-3 p-3">
                    <div class="text-center fw-bold payable-text mb-3">
                      NET PAYABLE: <span>₹295.00</span>
                    </div>
                  </div>
                  
                </div>
              </div>
              <div class="row">
                   <div class="col-md-8"></div>
                    <div class="col-md-4 bottom-actions mt-3 d-flex flex-wrap" style="justify-content: end;">
                      <button class="btn btn-warning">Save & Print</button>
                      <button class="btn btn-danger">Cancel</button>
                    </div>
               </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS (deferred for speed) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
  <script defer>
    
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
    
    $("#frmOutsale").on("submit", function (e) {
        let mobile = $("#mobile_no").val();
        let regex = /^[6-9]\d{9}$/;
    
        if (!regex.test(mobile)) {
            alert("Please enter a valid 10-digit mobile number starting with 6,7,8, or 9.");
            e.preventDefault();
        }
    });

   $(document).ready(function()
   {
      
        setTimeout(function () {
            $("#other").trigger("click");
        }, 1000);
        

        let debounceTimer;
        const debounceDelay = 300; // Delay in milliseconds
        $('#scan_barcode').focus();
        $('#scan_barcode').on('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                GetBarcodeDetails();
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
       $("#total_disc_amount").val(discount_amount.toFixed(2));
       $("#total_gst_amount").val(gst_amount.toFixed(2));
       $("#net_amount").val(net_amount.toFixed(2));
   }
   
   function calQty(row)
   { 
       var qty = $(row).parent().parent('tr').find('td input[name="qty[]"]').val();
       var rate = $(row).parent().parent('tr').find('td input[name="rate[]"]').val();
       var discount = $(row).parent().parent('tr').find('td input[name="discount[]"]').val(); 
       var gst_per = $(row).parent().parent('tr').find('td input[name="gst_per[]"]').val(); 
       var stock_qty = $(row).parent().parent('tr').find('td input[name="stock_qty[]"]').val(); 
       
       if(parseInt(qty) <= parseInt(stock_qty))
       {
           var amount = parseFloat(qty) * parseFloat(rate);
           
           var discount_amount = 0;  
           var gst_amount = 0;
           
           if(parseFloat(discount) > 0)
           {
               discount_amount = parseFloat(amount) * parseFloat(discount/100); 
           }
           var total = (parseFloat(amount) - parseFloat(discount_amount));
           if(parseFloat(gst_per) > 0)
           {
               gst_amount = (parseFloat(total) * (gst_per/100)); 
           }
      
           var total_amount = parseFloat(total) + parseFloat(gst_amount); 
           
           $(row).parent().parent('tr').find('td input[name="amount[]"]').val(amount.toFixed(2));
           $(row).parent().parent('tr').find('td input[name="discount_amount[]"]').val(discount_amount.toFixed(2));
           $(row).parent().parent('tr').find('td input[name="gst_amount[]"]').val(gst_amount.toFixed(2));
           $(row).parent().parent('tr').find('td input[name="total_amount[]"]').val(total_amount.toFixed(2));
           
           calAmount(row);
       }
       else
       {
           alert("Please enter Quantity less than or equal to available stock ("+stock_qty+")");
           $(row).parent().parent('tr').find('td input[name="qty[]"]').val(stock_qty);
       }
   }
  
   function setCustomer(ele)
   { 
       if(ele == 1)
       {
           $('.employee').removeClass("hide");
           $('.other_customer').addClass("hide");
           $("#employeeCode").attr('required', true);
           $("#other_customer").removeAttr('required');
           $("#other_customer").val('');
       }
       else if(ele == 2)
       {
           $('.employee').addClass("hide");
           $('.other_customer').removeClass("hide");
           $("#employeeCode").removeAttr('required');
           $("#other_customer").attr('required', true);
           $("#employeeCode").val('');
       }
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
   
    function GetBarcodeDetails() 
    {
        var scan_barcode = $('#scan_barcode').val();
        
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('GetBarcodeDetails') }}",
            data: { 'scan_barcode': scan_barcode },
            success: function(data) {
                // Check if the barcode already exists in the table
                var existingRow = $("#productTbl > tbody").find('tr').filter(function() {
                    return $(this).find('input[name="scan_barcode[]"]').val() === scan_barcode;
                });
    
                if (existingRow.length > 0) {
                    // Barcode found, increase the quantity
                    var qtyInput = existingRow.find('input[name="qty[]"]');
                    qtyInput.val(parseInt(qtyInput.val()) + 1); 
                    calQty(qtyInput);  
                } else {
                    // Barcode not found, append the new row
                    $("#productTbl > tbody").append(data.html);
                    var qtyInput = $("#productTbl > tbody").last('tr td').find('input[name="qty[]"]');
                    calQty(qtyInput);  
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
   
  </script>
</body>
</html>
