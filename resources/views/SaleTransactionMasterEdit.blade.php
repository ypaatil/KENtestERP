@extends('layouts.master') 
@section('content')
@php  
    ini_set('memory_limit', '10240M');
@endphp
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
    
<!--<div class="col-md-12" style="-->
<!--display:inline-block;-->
<!--padding-left:100%;-->
<!--animation: scroll-left 10s linear infinite;-->
<!--color:red;-->
<!--font-size:20px;-->
<!--">-->
<!--The form is currently under maintenance.-->
<!--</div>-->
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <form action="{{route('SaleTransaction.update',$SaleTransactionMasterList->sr_no )}}" method="POST" id="frmData">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'SaleTransaction' ?>" /> 
               @method('put')
               @csrf    
               <h4 class="card-title mb-4">Sales (Invoice): {{ $SaleTransactionMasterList->sale_code }}</h4>
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
               <div class="row">
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-sales_head_id" class="form-label">Sales Head/Ledger</label>
                        <select name="sales_head_id" class="form-select select2" id="sales_head_id" onchange="GetInvoiceNo();" >
                           <option value="0">--Select--</option>
                           @foreach($salesHeadlist as  $row)
                           {
                           <option value="{{ $row->sales_head_id }}" {{ $row->sales_head_id == $SaleTransactionMasterList->sales_head_id ? 'selected="selected"' : '' }}>{{ $row->sales_head_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-sale_code" class="form-label">Invoice No</label>
                        <input type="text" name="sale_code" class="form-control" id="sale_code" value="{{ $SaleTransactionMasterList->sale_code }}"  onchange="checkInvoice();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Firm</label>
                        <select name="firm_id" class="form-select" id="firm_id">
                           <option value="">--- Select Firm ---</option>
                           @foreach($firmlist as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $SaleTransactionMasterList->firm_id ? 'selected="selected"' : '' }}
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sale_date" class="form-label">Invoice Date</label>
                        <input type="date" name="sale_date" class="form-control" id="sale_date" value="{{ $SaleTransactionMasterList->sale_date }}">
                        <input type="hidden" name="c_code" id="c_code" value="{{ $SaleTransactionMasterList->c_code }}" />
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="formrow-Ac_code" class="form-label">Buyer</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();">
                           <option value="">--- Select Party Name ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           {
                           <option value="{{ $rowledger->ac_code  }}"
                           {{ $rowledger->ac_code == $SaleTransactionMasterList->Ac_code ? 'selected="selected"' : '' }}
                           >{{ $rowledger->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="bill_to" class="form-label">Bill To</label>
                        <select name="bill_to" class="form-select select2" id="bill_to" onchange="getTradePartyDetails();" required>
                           <option value="">--- Select ---</option>
                           @foreach($ledgerDetails as  $rows) 
                                <option value="{{ $rows->sr_no  }}" {{ $rows->sr_no == $SaleTransactionMasterList->bill_to ? 'selected="selected"' : '' }}>{{ $rows->trade_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tax_type_id" class="form-label">GST</label>
                        <select name="tax_type_id" class="form-select" id="tax_type_id" onchange="getPartyDetails();">
                           <option value="">--- Select---</option>
                           @foreach($gstlist as  $rowgst)
                           {
                           <option value="{{ $rowgst->tax_type_id  }}"
                           {{ $rowgst->tax_type_id == $SaleTransactionMasterList->tax_type_id ? 'selected="selected"' : '' }}
                           >{{ $rowgst->tax_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="carton_packing_no" class="form-label">Carton Packing No</label>
                        <select name="carton_packing_no[]" class="form-select select2" id="carton_packing_no" multiple>
                           <option value="">Carton Packing No</option>
                           @php $carton_packing_nos = explode(',', $SaleTransactionMasterList->carton_packing_nos);   @endphp
                           @foreach($CartonPackingList as  $row)
                           {
                           <option value="{{ $row->cpki_code  }}"
                           @if(in_array($row->cpki_code, $carton_packing_nos)) selected @endif  
                           >{{$row->cpki_code}} ({{$row->sales_order_no}})</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="ship_to" class="form-label">Ship To</label>
                        <select name="ship_to" class="form-select select2" id="ship_to" required onchange="ReadDistance();">
                           <option value="">--- Select ---</option>
                           @foreach($ledgerDetails as  $rows) 
                                <option value="{{ $rows->sr_no  }}" {{ $rows->sr_no == $SaleTransactionMasterList->ship_to ? 'selected="selected"' : '' }}>{{ $rows->trade_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="distance" class="form-label">Distance</label>
                        <input type="number" step="any" name="distance" class="form-control" id="distance" value="{{$SaleTransactionMasterList->distance}}" readonly> 
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="sent_through" class="form-label">Sent Through</label>
                        <input type="text" name="sent_through" class="form-control" id="sent_through" value="{{ $SaleTransactionMasterList->sent_through }}" > 
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                       <label for="address" class="form-label">Address</label>
                       <textarea id="address" name="address" class="form-control" rows="4" cols="50">{{ $SaleTransactionMasterList->address }}</textarea>
                     </div>
                  </div>
               </div>
               <div></div>
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
                           @php  if($SaleTransactionDetails->isNotEmpty()) {  
                           $no=1; @endphp
                           @foreach($SaleTransactionDetails as $row)
                           <tr>
                              <td><input type="text" name="id[]" value="{{ $no }}" id="id"  style="width:50px;"/></td>
                              <td>
                                 <input type="text"   name="sales_order_no[]"   value="{{ $row->sales_order_no }}" id="sales_order_no" style="width:80px;" required readOnly/>
                              </td>
                              <td><input type="text" name="buyer_po_no[]" required id="buyer_po_no" style="width:150px;" value="{{$row->buyer_po_no}}"/>  </td>  
                               <td> 
                                   @php 
                                        $styleNoList = DB::table('style_no_master')
                                                        ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.style_no_id', '=', 'style_no_master.style_no_id')
                                                        ->select('buyer_purchase_order_detail.style_no_id','style_no_master.style_no')
                                                        ->where('delflag','=',0)
                                                        ->where('tr_code','=',$row->sales_order_no)
                                                        ->groupBy('buyer_purchase_order_detail.style_no_id')
                                                        ->get();
                                   @endphp
                                  <select name="style_no_id[]" id="style_no_id" style="width:200px; height:30px;"  class="" disabled> 
                                     @foreach($styleNoList as $styles)
                                     <option value="{{ $styles->style_no_id }}">{{ $styles->style_no }}</option> 
                                     @endforeach
                                  </select>
                               </td>
                              <td>
                                 <select name="hsn_code[]" class="hsn_code form-control" id="hsn_code" style="width:120px;">
                                    <option value="">--- Select ---</option>
                                    @foreach($hsnlist as  $rowhsn)
                                    {
                                    <option value="{{ $rowhsn->hsn_code  }}"
                                    {{ $rowhsn->hsn_code == $row->hsn_code ? 'selected="selected"' : '' }}
                                    >{{ $rowhsn->hsn_code }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="unit_id[]" class="unit_id" id="unit_id" style="width:100px;"  onchange="CalPackQty(this);">
                                    <option value="">--- Select Unit ---</option>
                                    @foreach($unitlist as  $rowunit)
                                    {
                                    <option value="{{ $rowunit->unit_id  }}"
                                    {{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}
                                    >{{ $rowunit->unit_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input style="width:80px;" type="text" required class="ITEMQTY" name="order_qtys[]" value="{{ $row->order_qty }}" readOnly  id="order_qtys">
                              <input type="hidden"   class="ROWCOUNT"   value="1" readOnly  >
                              </td>
                              <td>  
                                    <input type="text" class="PCKITEMQTY" name="pack_order_qty[]" value="{{ $row->pack_order_qty }}" id="pack_order_qty" style="width:80px;" readonly>
                              </td>
                              <td><input  style="width:80px;" type="number" required step="any" class="" name="item_rates[]" value="{{ $row->order_rate }}" id="order_rate" onkeyup="CalculateRow(this);" ></td>
                              <td><input style="width:100px;" required  type="number" step="any" id="disc_per" class="" name="disc_pers[]" value="{{ $row->disc_per }}"></td>
                              <td><input readOnly style="width:80px;" type="number" id="disc_amount" step="any" class="" name="disc_amounts[]" value="{{ $row->disc_amount }}"></td>
                              <td><input  style="width:80px;" type="number" readOnly id="sale_cgst" step="any"  class="" name="sale_cgsts[]" value="{{ $row->sale_cgst }}"></td>
                              <td><input  style="width:80px;" type="number" readOnly step="any" id="camt" class="GSTAMT" name="camts[]" value="{{ $row->camt }}"></td>
                              <td><input  style="width:80px;" type="number" readOnly step="any" id="sale_sgst" class="" name="sale_sgsts[]" value="{{ $row->sale_sgst }}"></td>
                              <td><input style="width:80px;" type="number" readOnly step="any" id="samt" class="GSTAMT" name="samts[]" value="{{ $row->samt }}"></td>
                              <td><input   style="width:80px;"  type="number" step="any" id="sale_igst" class="" name="sale_igsts[]" value="{{ $row->sale_igst }}"></td>
                              <td><input  style="width:80px;"  type="number" readOnly step="any" id="iamt" class="GSTAMT" name="iamts[]" value="{{ $row->iamt }}"></td>
                              <td><input  style="width:80px;"  type="number" readOnly step="any" id="amount" class="GROSS" name="amounts[]" value="{{ $row->amount }}"></td>
                              <td><input  style="width:80px;"  type="number" readOnly step="any" id="total_amount" class="TOTAMT" name="total_amounts[]" value="{{ $row->total_amount }}"></td>
                              <td><button type="button" onclick="insertRow();mycalc();"  class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
                           </tr>
                           @php $no=$no+1;  @endphp
                           @endforeach
                           @php } @endphp
                        </tbody>
                     </table>
                  </div>
               </div>
               <br/>
               <div class="row">
                  <div class="col-md-2 mb-3">
                        <label for="delivary_note" class="form-label">Delivary Note</label>
                        <input type="text" value="{{ $SaleTransactionMasterList->delivary_note  }}" name="delivary_note" class="form-control" id="delivary_note">
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="delivary_note_date" class="form-label">Delivary Note Date</label>
                        <input type="date" value="{{ $SaleTransactionMasterList->delivary_note_date  }}" name="delivary_note_date" class="form-control" id="delivary_note_date">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="mode_of_payment" class="form-label">Mode/Terms Of Payment</label>
                        <input type="number" step="any" required value="{{$SaleTransactionMasterList->mode_of_payment}}" name="mode_of_payment" class="form-control" id="mode_of_payment">
                  </div> 
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="transport_id" class="form-label">Dispatch Through(Transport Id)</label>
                        <select name="transport_id" class="form-select select2" id="transport_id" required>
                           <option value="">--- Select ---</option>
                           @foreach($dispatchlist as  $rows)
                             <option value="{{ $rows->ac_code }}" {{ $rows->ac_code == $SaleTransactionMasterList->transport_id ? 'selected="selected"' : '' }}>{{ $rows->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>  
                  <div class="col-md-2 mb-3">
                        <label for="transDocNo" class="form-label">Transporter Doc No.</label>
                        <input type="text" value="{{$SaleTransactionMasterList->transDocNo}}" name="transDocNo" class="form-control" id="transDocNo">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="transDocDate" class="form-label">Transporter Doc Date.</label>
                        <input type="date" value="{{$SaleTransactionMasterList->transDocDate}}" name="transDocDate" class="form-control" id="transDocDate">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" required value="{{$SaleTransactionMasterList->destination}}" name="destination" class="form-control" id="destination">
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="bill_of_landing" class="form-label">Bill of Landing/LR RR No.</label>
                        <input type="text" value="{{ $SaleTransactionMasterList->bill_of_landing }}" name="bill_of_landing" class="form-control" id="bill_of_landing">
                  </div>
                  <div class="col-md-2 mb-3">
                        <label for="vehicle_no" class="form-label">Motor Vehicle No.</label>
                        <input type="text" value="{{ $SaleTransactionMasterList->vehicle_no }}" name="vehicle_no" class="form-control" id="vehicle_no">
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="terms_of_delivery_id" class="form-label">Terms Of Delivery</label>
                        <select name="terms_of_delivery_id" class="form-select select2" id="terms_of_delivery_id" required>
                           <option value="">--- Select ---</option>
                           @foreach($shipment_mode_list as  $rows)
                             <option value="{{ $rows->ship_id  }}" {{ $rows->ship_id == $SaleTransactionMasterList->terms_of_delivery_id ? 'selected="selected"' : '' }}>{{ $rows->ship_mode_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2 mb-3">
                        <label for="no_of_cartons" class="form-label">No of Cartons</label>
                        <input type="number" step="any" required value="{{ $SaleTransactionMasterList->no_of_cartons }}" name="no_of_cartons" class="form-control" id="no_of_cartons">
                  </div> 
               </div>
               <input type="hidden"   name="cnt" id="cnt" value="{{ count($SaleTransactionDetails) }}">  
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Qty (Pieces)</label>
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="{{ $SaleTransactionMasterList->total_qty  }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Gross Amount</label>
                        <input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();" value="{{ $SaleTransactionMasterList->Gross_amount }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Freight Charges</label>
                        <input type="number" name="freight_charges" step="any" class="form-control" id="freight_charges" onChange="mycalc();" value="{{ $SaleTransactionMasterList->freight_charges }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other Cost</label>
                        <input type="text" name="other_cost" class="form-control" id="other_cost" onChange="mycalc();" required value="{{ $SaleTransactionMasterList->other_cost }}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other Cost GST %</label>
                        <input type="text" name="other_cost_gst_per" class="form-control" id="other_cost_gst_per" onChange="mycalc();" required value="{{ $SaleTransactionMasterList->other_cost_gst_per }}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Other GST Amt </label>
                        <input type="text" name="other_cost_gst_amt" class="form-control" id="other_cost_gst_amt" onChange="mycalc();" required value="{{ $SaleTransactionMasterList->other_cost_gst_amt }}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">GST Amount</label>
                        <input type="text" name="Gst_amount" class="form-control" id="Gst_amount" value="{{ $SaleTransactionMasterList->Gst_amount }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Net Amount</label>
                        <input type="text" name="Net_amount" class="form-control" id="Net_amount" value="{{ $SaleTransactionMasterList->Net_amount }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Narration</label>
                        <input type="text" name="narration" class="form-control" id="narration" value="{{ $SaleTransactionMasterList->narration }}">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3" style="margin-top: 37px;">
                        <label for="formrow-email-input" class="form-label"><b>Is Cancel</b></label>
                        <input type="checkbox" name="isCancel"  id="isCancel"  style="width: 25px;height: 25px;position: absolute;margin: -3px 10px;" {{ $SaleTransactionMasterList->isCancel == 1 ? 'checked="checked"' : '' }}>
                     </div>
                  </div>
               </div>
               <div class="row" style="display:none;">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="term_and_conditions" class="form-label">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" class="form-control" id="editor1" required>{{$SaleTransactionMasterList->terms_and_conditions}}</textarea>
                     </div>
                  </div>
               </div>
               </br>  
               <button type="submit" class="btn btn-success w-md" id="Submit" onclick="EnableFields();">Save</button>
               <a href="{{ Route('SaleTransaction.index') }}" class="btn btn-warning w-md">Cancel</a>
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
        var order_qty = $(row).closest('tr').find('td input[name="order_qtys[]"]').val();
        
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
                    $("#sales_head_id").trigger('change');
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
 
   function status_change(flag)
   {
      
       if(flag==2)
       {
         //  document.getElementById("reason_disapproval").readOnly=true;
            $("#reason_disapproval").prop('readonly', false);
       }
      else  
      {
           $("#reason_disapproval").prop('readonly', true);
         //document.getElementById("reason_disapproval").readOnly=false;
       }
   }
   
   
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
   
   
   
   
   
   
   
   
   function tds_payable()
   {
       var amount=document.getElementById('Gross_amount').value;
       var tax_type_id1=document.getElementById('tax_type_id').value;
       var Gst_amount=$('#Gst_amount').val();
       
   
   if(tax_type_id1==2)
   {
      
       var tds_per=$('#tds_per').val();
   var tds_amt=(parseFloat(amount) * (parseFloat(tds_per))/100);
   var payable_amount= parseFloat(amount) - parseFloat(tds_amt.toFixed(0)) + parseFloat(Gst_amount);
   $('#tds_amt').val(parseFloat(tds_amt).toFixed(0));
   $('#payable_amt').val(parseFloat(payable_amount).toFixed(0));
   
   
   }
   else {
        
   
   var tds_per=$('#tds_per').val();
   var tds_amt=(parseFloat(amount) * (parseFloat(tds_per))/100);
   var payable_amount= parseFloat(amount) - parseFloat(tds_amt.toFixed(0)) + parseFloat(Gst_amount);
   $('#tds_amt').val(parseFloat(tds_amt).toFixed(0));
   $('#payable_amt').val(parseFloat(payable_amount).toFixed(0));
   
   }
   
   }
   
   
   
   
   
   // function disc_calculatess()
   // {
   
   // var item_qty=document.getElementById('item_qty').value;
   // var item_rate=document.getElementById('item_rate').value;
   // var disc_per=document.getElementById('disc_per').value;
   // var amount= item_qty*item_rate
   
   // var disc_amount= parseFloat(parseFloat(amount) * parseFloat(disc_per/100));
   // $('#disc_amount').val(disc_amount.toFixed(2));
   
   // var amount= parseFloat(parseFloat(amount) - parseFloat(disc_amount)).toFixed(2);
   // $('#amount').val(amount);
   // calculateGstsss();
   
   // }
   
   
   
   
   // function calculateGstsss()
   // {
   // var amount=document.getElementById('amount').value;
   // var sale_cgst=document.getElementById('sale_cgst').value;
   // var sale_sgst=document.getElementById('sale_sgst').value;
   // var sale_igst=document.getElementById('sale_igst').value;
   
   // var tax_type_id1=document.getElementById('tax_type_id').value;
   // if(tax_type_id1==2)
   // {
   // var iamt=  parseFloat(( amount*(sale_igst/100))).toFixed(2);
   // $('#iamt').val(iamt);
   
   // $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
   
   // }
   // else{
   // var camt=  parseFloat(( amount*(sale_cgst/100))).toFixed(2);
   // $('#camt').val(camt);
   // var samt= parseFloat(( amount*(sale_sgst/100))).toFixed(2);
   // $('#samt').val(samt);
   
   // $('#total_amount').val(parseFloat(amount) + parseFloat(camt) + parseFloat(samt));
   
   // }
   // }
   
   
   // function divideBy(str) 
   // { 
   // item_code = document.getElementById("item_code").value;  
   
   // calculate_gst(item_code);
   
   // }
   
   
   
   
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
   
   
   function getPartyDetails()
   {
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
           
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('NewSalesOrderList') }}",
               data:{'Ac_code':ac_code},
               success: function(response)
               {
                   $("#sales_order_nos").html(response.html);
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
   
   
     $(document).on("keyup", 'input[name="pack_order_qty[]"]', 'input[name^="order_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="sale_cgsts[]"],input[name^="camts[]"],input[name^="sale_sgsts[]"],input[name^="sale_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
           CalculateRow($(this).closest("tr"));
   
           
       });
    
   
 
   function CalculateRow(row) 
   { 
        let $tr = $(row).closest('tr');
        
        let item_qtys    = parseFloat($tr.find('input[name^="pack_order_qty[]"]').val()) || 0;
        let item_rates   = parseFloat($tr.find('input[name^="item_rates[]"]').val()) || 0;
        let disc_pers    = parseFloat($tr.find('input[name^="disc_pers[]"]').val()) || 0;
        let sale_cgsts   = parseFloat($tr.find('input[name^="sale_cgsts[]"]').val()) || 0;
        let sale_sgsts   = parseFloat($tr.find('input[name^="sale_sgsts[]"]').val()) || 0;
        let sale_igsts   = parseFloat($tr.find('input[name^="sale_igsts[]"]').val()) || 0;
    
        if(item_qtys > 0) {
            let Amount = item_qtys * item_rates;
            let disc_amt = (Amount * (disc_pers / 100));
            
            $tr.find('input[name^="disc_amounts[]"]').val(disc_amt.toFixed(2));
            
            Amount -= disc_amt;
            $tr.find('input[name^="amounts[]"]').val(Amount.toFixed(2));
    
            let TAmount = 0;
    
            if(sale_igsts != 0) {
                let Iamt = Amount * (sale_igsts / 100);
                $tr.find('input[name^="iamts[]"]').val(Iamt.toFixed(2));
                TAmount = Amount + Iamt;
            } else {
                let Camt = Amount * (sale_cgsts / 100);
                let Samt = Amount * (sale_sgsts / 100);
    
                $tr.find('input[name^="camts[]"]').val(Camt.toFixed(2));
                $tr.find('input[name^="samts[]"]').val(Samt.toFixed(2));
    
                TAmount = Amount + Camt + Samt;
            }
    
            $tr.find('input[name^="total_amounts[]"]').val(TAmount.toFixed(2));
        }
    
        mycalc();
    }

   
   
   function getSalesOrder( ){
         
   var  sales_order_nos = $("#sales_order_nos option:selected").map(function() {
     return this.value;
   }).get().join(",");
      
   var tax_type_id=document.getElementById("tax_type_id").value;
    
   $.ajax({
   type:"GET",
   url:"{{ route('getSalesOrderData') }}",
   //dataType:"json",
   data:{sales_order_nos:sales_order_nos,tax_type_id:tax_type_id},
   success:function(response){
   console.log(response);  
       $("#bomdis").html(response.html);
    mycalc();
   }
   });
   }
   
</script>
@endsection