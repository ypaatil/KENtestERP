@extends('layouts.master') 
@section('content')
@php 
        ini_set('memory_limit', '10G');
@endphp
<style>
     
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
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
            <h4 class="card-title mb-4" style="font-size:20px;">Sales Order</h4>
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
            <form action="{{route('BuyerPurchaseOrder.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tr_date" class="form-label">Entry Date</label>
                        <input type="date" name="tr_date" class="form-control" id="tr_date" value="{{date('Y-m-d')}}" readOnly>
                        @foreach($counter_number as  $row)
                        <!--<input type="hidden" name="tr_code" class="form-control" id="tr_code" value="{{ 'KDPL'.'-'.$row->tr_no }}">-->
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                        @php
                       // session()->put('BuyerPurchase','1');
                        Session::put('BuyerPurchase', '1');
                        Session::save();
                        
                        
                        @endphp   
                        <input type="hidden" name="BuyerPurchase" value="{{ Session::get('BuyerPurchase') }}" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="in_out_id" class="form-label">Execution SBU</label>
                        <select name="in_out_id" class="form-select select2" id="in_out_id" required>
                           <option value="">--Select Execution SBU--</option>
                           @foreach($InOutList as  $row) 
                           <option value="{{ $row->in_out_id }}">{{ $row->in_out_name }}</option> 
                           @endforeach 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">Buyer PO No.</label>
                        <input type="text" name="po_code" class="form-control" id="po_code" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label>
                        <select name="order_type" class="form-select select2"   id="order_type" required>
                           <option value="">--Select Order Type--</option>                   
                           @foreach($OrderTypeList as  $row) 
                           <option value="{{ $row->orderTypeId }}">{{ $row->order_type }}</option> 
                           @endforeach 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="og_id" class="form-label">Market Type</label>
                        <select name="og_id" class="form-select" id="og_id" required onchange="SetCurrency();">
                           <option value="">--Select Market Type--</option>
                           @foreach($OrderGroupList as  $row)
                           <option value="{{ $row->og_id }}">{{ $row->order_group_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="orderCategoryId" class="form-label">Order Category</label>
                        <select name="orderCategoryId" class="form-select" id="orderCategoryId" required>
                           <option value="">--Select Order Category--</option>
                           @foreach($OrderCategoryList as  $row) 
                           <option value="{{ $row->orderCategoryId }}">{{ $row->OrderCategoryName }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer/Party Name</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" required onchange="getSeasonList(this.value); getBrandList(this.value);GetDestinationForSalesOrderList();" >
                           <option value="">--Select Buyer/Party--</option>
                           @foreach($Ledger as  $row)
                           <option value="{{ $row->ac_code }}">{{ $row->ac_short_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select" id="brand_id" required>
                           <option value="">--Select Buyer Brand--</option>
                           @foreach($BrandList as  $row)
                           <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <!--<div class="col-md-2">-->
                  <!--    <div class="mb-3">-->
                  <!--        <label for="buyer_delivery_date" class="form-label">Delivery Date</label>-->
                  <!--        <input type="date" name="buyer_delivery_date" class="form-control" id="buyer_delivery_date" value="{{date('Y-m-d')}}" required>-->
                  <!--    </div>-->
                  <!--</div> -->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency</label>
                        <select name="currency_id" class="form-select" id="currency_id" required onchange="ExchangeCurrency();">
                           <option value="">--Select Currency--</option>
                           @foreach($CurrencyList as  $row)
                           <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inr_rate" class="form-label">Rate</label>
                        <input type="number" step="any" name="inr_rate" class="form-control" id="inr_rate" value="" max="999999" oninput="if(this.value.length > 6) this.value = this.value.slice(0,6);" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" step="any" name="exchange_rate" class="form-control" id="exchange_rate" value="" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Rate (INR)</label>
                        <input type="number" step="any" name="order_rate" class="form-control" id="order_rate" value="" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="season_id" class="form-label">Season</label>
                        <select name="season_id" class="form-select" id="season_id" required>
                           <option value="">--Select Season--</option>
                           @foreach($SeasonList as  $row)
                           <option value="{{ $row->season_id }}">{{ $row->season_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Style</label>
                        <select name="mainstyle_id" class="form-select select2" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
                           <option value="">--Select Style--</option>
                           @foreach($MainStyleList as  $row)
                           <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" onchange="getStyle(this.value)" required>
                           <option value="">--Select Sub Style--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-select select2" id="fg_id" required>
                           <option value="">--Select Style Name--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_no" class="form-label">Style No.</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value=""  >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ptm_id" class="form-label">Payment Terms</label>
                        <select name="ptm_id" class="form-select" id="ptm_id" required>
                           <option value="">--Select Payment Terms--</option>
                           @foreach($PaymentTermsList as  $row)
                           <option value="{{ $row->ptm_id }}">{{ $row->ptm_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dterm_id" class="form-label">TNA Template</label>
                        <select name="dterm_id" class="form-select" id="dterm_id" required>
                           <option value="">--Select TNA Template--</option>
                           @foreach($DeliveryTermsList as  $row)
                           <option value="{{ $row->dterm_id }}">{{ $row->delivery_term_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ship_id" class="form-label">Shipment Mode</label>
                        <select name="ship_id" class="form-select" id="ship_id" required>
                           <option value="">--Select Shipment Mode--</option>
                           @foreach($ShipmentList as  $row)
                           <option value="{{ $row->ship_id }}">{{ $row->ship_mode_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="warehouse_id" class="form-label">Ship To Location</label>
                        <select name="warehouse_id" class="form-select" id="warehouse_id" required>
                           <option value="">--Select Ship To Location--</option>
                           @foreach($WarehouseList as  $row)
                           <option value="{{ $row->warehouse_id }}">{{ $row->warehouse_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <input type="hidden" name="country_id" id="country_id" value="1" />
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">PO Received Date</label>
                        <input type="date" name="order_received_date" class="form-control" id="order_received_date" value="{{date('Y-m-d')}}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="plan_cut_date" class="form-label">Plan Cut Date(PCD)</label>
                        <input type="date" name="plan_cut_date" class="form-control" id="plan_cut_date" value="{{date('Y-m-d')}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inspection_date" class="form-label">Inspection Date</label>
                        <input type="date" name="inspection_date" class="form-control" id="inspection_date" value="{{date('Y-m-d')}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">Shipment Date</label>
                        <input type="date" name="shipment_date" class="form-control" id="shipment_date" value="{{date('Y-m-d')}}" onchange="setOtherDate();" required>
                     </div>
                  </div>
                  <input type="hidden" name="ex_factory_date" class="form-control" id="ex_factory_date" value="{{date('Y-m-d')}}">
                  <input type="hidden" name="from_tna_date" class="form-control" id="from_tna_date" value="{{date('Y-m-d')}}">
                  <input type="hidden" name="to_tna_date" class="form-control" id="to_tna_date" value="{{date('Y-m-d')}}">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select select2"   id="sz_code" required onChange="GetSizeDetailList(this.value);">
                           <option value="">--Select Size Group--</option>
                           @foreach($SizeList as  $row)
                           <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap" id="divSelect">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr class="text-center">
                                 <th>Sr No</th>
                                 <th>Fabric Color</th>
                                 <th>Garment Color</th>
                                 <th>Size</th>
                                 <th>Qty</th>
                                 <th>UOM</th>
                                 <th>  <i class="fas fa-trash"></i> </th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="row mt-5"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_pic_path" class="form-label">Style Image</label>
                        <input type="file" name="style_pic_path" class="form-control" id="style_pic_path" value="" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label">PO Document</label>
                        <input type="file" name="doc_path1" class="form-control" id="doc_path1" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tech_pack" class="form-label">Tech Pack</label>
                        <input type="file" name="tech_pack" class="form-control" id="tech_pack" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label">Measurement sheet</label>
                        <input type="file" name="measurement_sheet" class="form-control" id="measurement_sheet" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fit_pp_comments" class="form-label">FIT/PP comments</label>
                        <input type="file" name="fit_pp_comments" class="form-control" id="fit_pp_comments" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="approved_fabric_trim" class="form-label"> Approved Fabric/Trim</label>
                        <input type="file" name="approved_fabric_trim" class="form-control" id="approved_fabric_trim" value="">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input type="number" step="any"  name="total_qty" class="form-control" id="total_qty" value="0" readOnly required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_value" class="form-label">Order Value</label>
                        <input type="text" name="order_value" class="form-control" id="order_value" value="" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="shipped_qty" class="form-label">UOM</label>
                        <select name="unit_ids"  id="unit_ids"  class="form-select"   required>
                           <option value="">--Select Unit--</option>
                           @foreach($UnitList as  $row)
                           <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <input type="hidden" name="shipped_qty" class="form-control" id="shipped_qty" value="0" readOnly onkeyup="calculate();">
                  <input type="hidden" name="balance_qty" class="form-control" id="balance_qty" value="0" readOnly>
                  <input type="hidden" name="job_status_id" class="form-control" id="job_status_id" value="1">
                  <input type="hidden" name="order_close_date" class="form-control" id="order_close_date" value="">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="merchant_id" class="form-label">Bulk Merchant</label>
                        <select name="merchant_id" class="form-select" id="merchant_id" required>
                           <option value="">--Select Merchant--</option>
                           @foreach($MerchantList as  $row)
                           <option value="{{ $row->merchant_id }}">{{ $row->merchant_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="PDMerchant_id" class="form-label">PD Merchant</label>
                        <select name="PDMerchant_id" class="form-select" id="PDMerchant_id" required>
                           <option value="">--Select PD Merchant--</option>
                           @foreach($PDMerchantList as  $row)
                           <option value="{{ $row->PDMerchant_id }}">{{ $row->PDMerchant_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label">Order Remark / Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();" >Submit</button>
                     <a href="{{ Route('BuyerPurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.3/parsley.min.js"></script>
<script>

    function setOtherDate()
    {
        var shipment_date = $("#shipment_date").val();
    
        if (!shipment_date) return; // if shipment date is empty, exit
    
        // Convert to Date object
        var sDate = new Date(shipment_date);
    
        // Plan cut date = shipment_date - 30 days
        var planCutDate = new Date(sDate);
        planCutDate.setDate(sDate.getDate() - 30);
    
        // Inspection date = shipment_date - 4 days
        var inspectionDate = new Date(sDate);
        inspectionDate.setDate(sDate.getDate() - 4);
    
        // Format dates as yyyy-mm-dd
        var formatDate = (date) => {
            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
    
        $("#plan_cut_date").val(formatDate(planCutDate));
        $("#inspection_date").val(formatDate(inspectionDate));
    }


   function EnableFields()
   {
        $("input").prop("readonly", false);
        $("input").removeAttr("disabled");
        $("select").removeAttr("disabled"); 
     
   }
   
    $(document).on('keydown', 'input[type="number"]', function(e) {
        const invalidKeys = ['e', 'E', '+', '-'];
    
        // Block invalid keys
        if (invalidKeys.includes(e.key)) {
            e.preventDefault();
            return;
        }
    
        // Allow one dot only
        if (e.key === '.') {
            // If already contains a dot, block it
            if ($(this).val().includes('.')) {
                e.preventDefault();
            }
            return;
        }
    });

    $(document).on('input', 'input[type="date"]', function () {
        let value = $(this).val();
    
        // Split by dash
        let parts = value.split('-');
    
        // Limit year to 4 digits
        if (parts[0] && parts[0].length > 4) {
            parts[0] = parts[0].substring(0, 4);
        }
    
        // Limit month to 2 digits
        if (parts[1] && parts[1].length > 2) {
            parts[1] = parts[1].substring(0, 2);
        }
    
        // Limit day to 2 digits
        if (parts[2] && parts[2].length > 2) {
            parts[2] = parts[2].substring(0, 2);
        }
    
        $(this).val(parts.join('-'));
    });

    $(document).ready(function()
    { 
        setOtherDate();
        $('input[type="text"], textarea').on('input', function() {
            // Remove single and double quotes
            $(this).val($(this).val().replace(/['"]/g, ""));
        });

        for (const el of document.querySelectorAll("[data-workaround125]")) {
            el.innerHTML = el.getAttribute("data-workaround125") || "";
            el.removeAttribute("data-workaround125");
        }
        $("#unit_ids").val(3);
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
   function SetCurrency() 
   {
      var og_id = $("#og_id").val(); // 1 = Domestic, 2 = Export     
      $.ajax({
         type: "GET",
         url: "{{ route('GetCurrencyOrderGroupWise') }}",
         data:{'og_id':og_id },
         success: function(data)
         {
               $("#currency_id").html(data.html);
         }
      });

      if (og_id == 1) { // Domestic
         $("#currency_id").attr('disabled', true);
         $("#exchange_rate").val(1).attr('readonly', true);
         $("#order_rate, #inr_rate").val(0);

      } else if (og_id == 2) { // Export
         $("#currency_id").attr('disabled', false);
         $("#exchange_rate").val("").attr('readonly', false);
         $("#order_rate, #inr_rate").val(0);
      } 
   }

    
    function ExchangeCurrency() {
        var currency_id = $("#currency_id").val();
        var og_id = $("#og_id").val();

        if (og_id == 1) { 
            // Domestic: always Rupees
            $("#currency_id").val(1);
            $("#currency_id").attr('disabled', true);
            $("#exchange_rate").val(1);
            $("#exchange_rate").attr('readonly', true);
            $("#inr_rate").val(0);
            $("#order_rate").val(0);
        } else if (og_id == 2) { 
            // Export: Rupees is NOT allowed
            if (currency_id == 1) {
                alert("Rupees is not allowed for Export orders. Please select another currency."); 
                $("#exchange_rate").val("");
                $("#order_rate").val(0);
                $("#exchange_rate").attr('readonly', false);
            } else {
                // Any other currency
                $("#exchange_rate").val("");
                $("#order_rate").val(0);
                $("#exchange_rate").attr('readonly', false);
            }
            $("#inr_rate").val(0);
            $("#order_rate").val(0);
            $("#currency_id").attr('disabled', false);
        } else {
            // Default fallback
            $("#currency_id").attr('disabled', false);
            $("#exchange_rate").attr('readonly', false);
            $("#inr_rate").val(0);
            $("#order_rate").val(0);
        }
    }
    
    // Bind to change events
    $("#og_id").on('change', SetCurrency);
    $("#currency_id").on('change', ExchangeCurrency);



    function GetDestinationForSalesOrderList()
    {
         var Ac_code = $("#Ac_code").val();
         $.ajax({
            type: "GET",
            url: "{{ route('GetDestinationForSalesOrderList') }}",
            data:{'Ac_code':Ac_code },
            success: function(data)
            {
                  $("#warehouse_id").html(data.html);
            }
         });
    }
    
    function checkDuplicateColor(row)
    {
        var garment_color = $(row).val();
        $(".Garment_color").not(row).each(function()
        {
            if($(this).val() == garment_color)
            {
                alert("This garment color has already been selected.");
                $(row).select2('destroy');
                $(row).val("");
                $(row).select2();
            }
        });
    }
    function CheckOpenWorkProcessOrders(job_status_id)
    {
       var order_type = $("#order_type").val();
       var order_close_date = $("#order_close_date").val();
       if(order_type != 2 && order_close_date == "" && job_status_id == 2) 
       {
           alert("Please Enter Order Close Date...!");
           $("#order_close_date").attr('readonly', false);
           $("#job_status_id").val(1);
       }
       else
       {
           $("#order_close_date").attr('readonly', true);
       }
      
    }

   function calculate()
    {
       
        var shipped_qty=$('#shipped_qty').val();
        var order_qty=$('#total_qty').val();
        var balance_qty=order_qty-shipped_qty;
        $('#balance_qty').val(balance_qty);
        
        
    }
   
   var s1=0; var s4=0; var s7=0; var s10=0; var s13=0; var s16=0; var s19=0;  
   var s2=0; var s5=0; var s8=0; var s11=0; var s14=0; var s17=0; var s20=0;  
   var s3=0; var s6=0; var s9=0; var s12=0; var s15=0; var s18=0;  
   $(document).on("keyup", 'input[class^="size_id"]', function (event) 
   {
        var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
        var size_array = sizes.split(',');
         var values = [];
         $("#footable_3 tr td  input[class='size_id']").each(function() {
         values.push($(this).val());
         if(values.length==size_array.length)
         {
             
           $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
           var sum = values.reduce(function( a,  b){
                   return parseInt(a) + parseInt(b);
               }, 0);
           $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
           
               values = [];
           }
     
         
       });
        
     mycalc();
     calculate();
        
      });
       
     
    function getSubStyle(val) 
    {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('SubStyleList') }}",
       data:'mainstyle_id='+val,
       success: function(data)
       {
           $("#substyle_id").select2("destroy");
           $("#fg_id").select2("destroy");
           $("#fg_id").html("");
           $("#substyle_id").html(data.html);
           $("#substyle_id").select2();
           $("#fg_id").val("");
       }
       });
   }   
        
   function getStyle(val) 
   {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('StyleList') }}",
       data:{'substyle_id':val, },
       success: function(data)
       {
        $("#fg_id").select2("destroy");
        $("#fg_id").html(data.html);
        $("#fg_id").select2();
       }
       });
   }  
   
   
    function getSeasonList(val) 
   {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('SeasonList') }}",
       data:{'Ac_code':val, },
       success: function(data){
       $("#season_id").html(data.html);
       }
       });
   }  
   
   function getBrandList(val) 
   {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('BrandList') }}",
       data:{'Ac_code':val, },
       success: function(data){
       $("#brand_id").html(data.html);
       }
       });
   }  
   
   function GetSizeDetailList(str)
   {
       var Ac_code = $("#Ac_code").val();
       $("#Ac_code").attr("disabled", true);
       $.ajax({
           dataType: "json",
           contentType: "application/json; charset=utf-8",
           url: "{{ route('SizeDetailList') }}",
           data:{'sz_code':str, 'Ac_code': Ac_code},
           success: function(data)
           {
            $("#divSelect").html(data.html);
           }
       });
   }
   
   
   
    function getAddress(site_code)
    { 
   
        var Ac_code=document.getElementById('Ac_code').value;
        $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('GetAddress') }}",
               //data:'table_id='+table_id,
               data:{Ac_code:Ac_code,site_code:site_code},
               success: function(response)
               {  
                   $("#DeliveryAddress").val(response[0]['consignee_address']);
               }
        });
    }
   
   
    $('#footable_3').on('change', '.item', function() 
    {
        var tax_type_id=document.getElementById('tax_type_id').value;
        var item_code = $(this).val();
        var row = $(this).closest('tr');
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('TaxList') }}",
           data:'item_code='+item_code,
           success: function(data)
           {
               if(tax_type_id==1)
               {
                   row.find('input[name^="cgst_per[]"]').val(data[0]['cgst_per']);
                   row.find('input[name^="sgst_per[]"]').val(data[0]['sgst_per']);
                   row.find('input[name^="igst_per[]"]').val();
               }
               else
               {
                   row.find('input[name^="igst_per[]"]').val(data[0]['igst_per']);
                   row.find('input[name^="cgst_per[]"]').val(0);
                   row.find('input[name^="sgst_per[]"]').val(0);
               }
         
           }
        });
   });
   
 
   function calOrderRate()
   {
       var exchange_rate=$('#exchange_rate').val();
       var inr_rate=$('#inr_rate').val();
       var order_rate=(parseFloat(inr_rate) * parseFloat(exchange_rate)).toFixed(2);
       $('#order_rate').val(order_rate);
       
   }
   
   
   
   
   $(document).on('click', '.Abutton', function () {
       var $tr = $(this).closest('tr');
       var $lastTr = $tr.closest('table').find('tr:last');
   
       $lastTr.find('.select2-select').select2('destroy');
   
       var $clone = $lastTr.clone();
   
       $clone.find('td').each(function() {
           var el = $(this).find(':first-child');
           var id = el.attr('id') || null;
           if (id) {
               var i = id.substr(id.length - 1);
               var prefix = id.substr(0, (id.length - 1));
               el.attr('id', prefix + (+i + 1));
               
           }
       });
       $tr.closest('tbody').append($clone);
       $lastTr.find('.select2-select').select2();
       $clone.find('.select2-select').select2();
       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
       recalcIdcone();
       mycalc();
       
   });
    
    
   
   
   //  function addrow()
   // {
   //     var row = $("#footable_3 tr:last");
   
   //     row.find(".color").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
       
   //     row.find(".Item").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
   
   //     row.find(".unit").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
   
   
   //  row.find(".size").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
   
   //   var newrow = row.clone();   
       
   //  $('#footable_3 tbody tr:last').find('select[name^="unit_id[]"]').each(function() {
   //           $('select.select2').select2();
   //     });
   //     // $('#footable_3 tbody tr:last').find('select[name^="item_code[]"]').each(function() {
   //     //     newrow.find('select[name^="item_code[]"]').val(this.value);
   //     // });
   
   //  $('#footable_3 tbody tr:last').find('select[name^="unit_id[]"]').each(function() {
   //         newrow.find('select[name^="unit_id[]"]').val(this.value);
   //     });
       
      
       
   //     $("#footable_3").append(newrow);
       
        
   //      $('.select2').select2();
       
       
        
   //       document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   //      recalcIdcone();
       
   //     mycalc();
   //     calculate();
         
    
    
         
   // }
    
    
//   $(document).on("click", 'input[name^="Abutton[]"]', function (event) {
       
//           insertcone($(this).closest("tr"));
           
//       });
     
//   var indexcone = 2;
//   function insertcone(Abutton){
//       var rowsx=$(Abutton).closest("tr");
   
//   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
//   var row=table.insertRow(table.rows.length);
   
//   var cell1=row.insertCell(0);
//   var t1=document.createElement("input");
//   t1.style="display: table-cell; width:50px;";
//   t1.id = "id"+indexcone;
//   t1.name= "id[]";
//   t1.value=indexcone;
//   cell1.appendChild(t1);
    
   
//   var cell5 = row.insertCell(1);
//   var t5=document.createElement("select");
//   var x = $("#color_id"),
//   y = x.clone();
//   y.attr("id","color_id");
//   y.attr("name","color_id[]");
//   var color=+rowsx.find('select[name^="color_id[]"]').val();
//   y.val(color);
//   y.attr("selected","selected"); 
//   y.width(100);
//   y.appendTo(cell5);
     
   
//   var cell4 = row.insertCell(2);
//   var t4=document.createElement("select");
//   var x = $("#sz_code"),
//   y = x.clone();
//   y.attr("id","sz_code");
//   y.attr("name","sz_code[]");
//   y.width(100);
//   y.appendTo(cell4);
   
//   var cell5 = row.insertCell(3);
//   var t5=document.createElement("input");
//   t5.style="display: table-cell; width:80px;";
//   t5.type="text";
//   t5.required="true";
//   t5.id = "qty"+indexcone;
//   t5.name="qty[]";
//   t5.className="QTY";
//   t5.onkeyup=mycalc();
//   t5.value="0";
//   t5.setAttribute("onkeyup", "mycalc();");
//   cell5.appendChild(t5);
   
//   var cell6 = row.insertCell(4);
//   var t5=document.createElement("select");
//   var x = $("#unit_id"),
//   y = x.clone();
//   y.attr("id","unit_id");
//   y.attr("name","unit_id[]");
//   var unit=+rowsx.find('select[name^="unit_id[]"]').val();
//   y.val(unit);
//   y.attr("selected","selected");
//   y.width(100);
//   y.appendTo(cell6);
    
    
//   var cell8=row.insertCell(5);
//   var btnAdd = document.createElement("input");
//   btnAdd.id = "Abutton";
//   btnAdd.name = "Abutton[]";
//   btnAdd.type = "button";
//   btnAdd.className="btn btn-warning pull-left";
//   btnAdd.value = "+";
//   // btnAdd.setAttribute("onclick", "insertcone(); ");
//   cell8.appendChild(btnAdd);
    
    
    
//   var btnRemove = document.createElement("INPUT");
//   btnRemove.id = "Dbutton";
//   btnRemove.type = "button";
//   btnRemove.className="btn btn-danger pull-left";
//   btnRemove.value = "X";
//   btnRemove.setAttribute("onclick", "deleteRowcone(this)");
//   cell8.appendChild(btnRemove);
   
//   var w = $(window);
//   var row = $('#footable_3').find('tr').eq(indexcone);
   
//   if (row.length){
//   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
//   }
   
//   document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   
//   indexcone++;
//   recalcIdcone();
//     mycalc();
//   }
   
     
//   $("table.footable_3").on("keyup", 'input[name^="qty[]"],input[name^="base_rate[]"],input[name^="cgst_per[]"],input[name^="cgst_amt[]"],input[name^="sgst_per[]"],input[name^="sgst_amt[]"],input[name^="igst_per[]"],input[name^="igst_amt[]"],input[name^="amount[]"],input[name^="total_amount[]"],input[name^="gst_amt[]"],input[name^="total_qty[]"],input[name^="GrossAmount[]"],input[name^="GstAmount[]"],input[name^="NetAmount[]"]', function (event) {
//           // CalculateRow($(this).closest("tr"));
//           mycalc();
//       });
   	
   		
   		
   		
//   	function CalculateRow(row)
//   	{ 
//   		var qty=+row.find('input[name^="qty[]"]').val();
//           var total_qty=+row.find('input[name^="total_qty[]"]').val();
//   		var base_rate=+row.find('input[name^="base_rate[]"]').val();
//   		var amount=parseFloat(qty * base_rate).toFixed(2);
//           var total_amount=+row.find('input[name^="total_amount[]"]').val();
//   		var cgst_per=+row.find('input[name^="cgst_per[]"]').val();
//   		var cgst_amt=+row.find('input[name^="cgst_amt[]"]').val();
//           var igst_per=+row.find('input[name^="igst_per[]"]').val();
//   		var igst_amt=+row.find('input[name^="igst_amt[]"]').val();
//           var sgst_per=+row.find('input[name^="sgst_per[]"]').val();
//   		var sgst_amt=+row.find('input[name^="sgst_amt[]"]').val();
//   		var GrossAmount= +row.find('input[name^="GrossAmount[]"]').val();
//   		var GstAmount= +row.find('input[name^="GstAmount[]"]').val();
//   		var NetAmount=+row.find('input[name^="NetAmount[]"]').val();
   		  
   		 
//   		 if(qty>0)
//   		 {
   			 
//                 row.find('input[name^="amount[]"]').val(amount);
   			 
//   			 if(igst_per!=0)
//   			 {
//                   igst_amt=parseFloat(amount*(igst_per/100)).toFixed(2);
//   				  row.find('input[name^="igst_amt[]"]').val(parseFloat(igst_amt));
//   				  total_amount=parseFloat(amount)+parseFloat(igst_amt);
//   				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
//                      row.find('input[name^="gst_amt[]"]').val(parseFloat(igst_amt));
//                      row.find('input[name^="cgst_per[]"]').val(0);
//                       row.find('input[name^="cgst_amt[]"]').val(0);
//                       row.find('input[name^="sgst_per[]"]').val(0);
//                       row.find('input[name^="sgst_amt[]"]').val(0);
   
//   			 }
//   			 else
//   			 {
//                   row.find('input[name^="igst_per[]"]').val(0);
//                   row.find('input[name^="igst_amt[]"]').val(0);
//                   cgst_amt=parseFloat(amount*(cgst_per/100)).toFixed(2);
//   				  row.find('input[name^="cgst_amt[]"]').val(parseFloat(cgst_amt));
   				  
//   				  sgst_amt=parseFloat(amount*(sgst_per/100)).toFixed(2);
//   				  row.find('input[name^="sgst_amt[]"]').val(parseFloat(sgst_amt));
   				 				  
//   				  total_amount=parseFloat(amount)+parseFloat(cgst_amt)+parseFloat(sgst_amt);
//   				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
//   				  row.find('input[name^="gst_amt[]"]').val(parseFloat(cgst_amt)+parseFloat(sgst_amt));
   				 
//   			 }
   			 
//   		}
   			 
//   			 	  mycalc();
//   }
   
   function mycalc()
   {   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('QTY');
   for(var i=0; i<amounts.length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_qty").value = sum1.toFixed(2);
   
   var order_rate=$("#order_rate").val();
   var order_value=order_rate * sum1.toFixed(2);
   $("#order_value").val(order_value.toFixed(2));
   if(order_value<=0)
   {
       document.getElementById("Submit").disabled=true;
   }
   else
   {
       document.getElementById("Submit").disabled=false;
   }
   
           var sizes=$("#size_array").val();
           var size_array = sizes.split(',');
           var n=1;
           //alert(size_array.length);
           var sz_ws_totalx='';
           for(var k=1;k<=size_array.length;k++)
           {
               var sum5 = 0.0;
               var amounts = document.getElementsByName('s'+n+'[]');
               for(var i=0; i<amounts.length; i++)
               { 
               var a = +amounts[i].value;
               sum5 += parseFloat(a);
               }
               document.getElementById('s'+n+'total').value = sum5;
               $('#s'+n+'total').attr('readonly', true);
               n=n+1;
               sz_ws_totalx=sz_ws_totalx+sum5+',';
            }  
           document.getElementById("sz_ws_total").value = sz_ws_totalx.replace(/,\s*$/, "");
          
   }
   
   
   
   function deleteRowcone(btn) {
       // alert();
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
   }
   }
   
   
   
   function recalcIdcone(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
     
   
</script>
<!-- end row -->
@endsection