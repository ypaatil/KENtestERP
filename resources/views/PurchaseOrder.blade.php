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
   .navbar-brand-box
   {
   width: 266px !important;
   }
   /* Spinner style */
   .loading-spinner {
   display: inline-block;
   width: 16px;
   height: 16px;
   margin-left: 6px;
   border: 2px solid #ccc;
   border-top-color: #007bff;
   border-radius: 50%;
   animation: spin 0.7s linear infinite;
   vertical-align: middle;
   }
   @keyframes spin {
   to { transform: rotate(360deg); }
   }
   

    /* Hide arrows in Chrome, Safari, Edge, Opera */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    
    /* Hide arrows in Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Purchase Order</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Purchase Order</li>
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
            <form action="{{route('PurchaseOrder.store')}}" method="POST" id="frmData">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo 'PURCHASE'; ?>" /> 
               @csrf 
               <div class="row">
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="firm_id" class="form-label">Firm</label>
                        <select name="firm_id" class="form-select" id="firm_id" required>
                           @foreach($firmlist as  $row) 
                           <option value="{{ $row->firm_id }}">{{ $row->firm_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="pur_date" class="form-label">PO Date</label>
                        <input type="date" name="pur_date" class="form-control" id="pur_date" value="{{date('Y-m-d')}}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="SetBillTo();GetPartyDetailsSale();" required>
                           <option value="">--- Select Supplier ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           <option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_short_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bill_to" class="form-label">Bill To</label>
                        <select name="bill_to" class="form-select select2" id="bill_to" onchange="getPartyDetails();" required>
                           <option value="">--- Select ---</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ship_to" class="form-label">Ship To</label>
                        <select name="ship_to" class="form-select select2" id="ship_to" required>
                           <option value="">--- Select ---</option>
                        </select>
                     </div>
                  </div>
                  <input type="hidden" name="supplierRef" class="form-control" id="supplierRef" value="0" required>
                  <input type="hidden" name="gstNo" class="form-control" id="gstNo" value="">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tax_type_id" class="form-label">GST Type</label>
                        <select name="tax_type_id" class="form-select" id="tax_type_id"  disabled>
                           <option value="">--GST Type--</option>
                           @foreach($gstlist as  $rowgst) 
                           <option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_type_id" class="form-label">PO Type</label>
                        <select name="po_type_id" class="form-select select2" id="po_type_id" onchange="PODisabled();" >
                           <option value="">Type</option>
                           @foreach($POTypeList as  $rowpo)
                           <option value="{{ $rowpo->po_type_id  }}">{{ $rowpo->po_type_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="bom_code" class="form-label">BOM</label>
                        <select name="bom_codes[]" class="form-select select2" id="bom_code" multiple disabled>
                           @foreach($BOMLIST as  $rowbom)
                           <option value="{{ $rowbom->bom_code  }}">{{$rowbom->sales_order_no}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="buyer_id" class="form-label">Buyer</label>
                        <select name="buyer_id" class="form-select select2" id="buyer_id" disabled>
                           <option value="">--- Select ---</option>
                           @foreach($buyerlist as  $row)
                           <option value="{{ $row->ac_code  }}">{{ $row->ac_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bom_type" class="form-label">BOM Type</label>
                        <select name="bom_type[]" class="form-select select2" id="bom_type" onchange="GetClassesList();DropdownEmpty();" multiple>
                           <option value="1">Fabric</option>
                           <option value="2">Sewing Trims</option>
                           <option value="3">Packing Trims</option>
                           <option value="4">Other</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="class_id" class="form-label">Classification</label>
                        <select name="class_id[]" class="form-select select2" id="class_id" onchange="getBomDetail();DropdownEmpty();" multiple>
                           @foreach($ClassList as  $rowclass)
                           <option value="{{ $rowclass->class_id  }}">{{ $rowclass->class_name }} </option>
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
                              <th>Add</th>
                              <th>Remove</th>
                              <th>Conversion</th>
                              <th>Sales Order No</th>
                              <th>Classificaion</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>HSN No</th>
                              <th>Unit</th>
                              <th>BOM Qty</th>
                              <th>Stock</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>CGST%</th>
                              <th>CAMT</th>
                              <th>SGST%</th>
                              <th>SAMT</th>
                              <th>IGST%</th>
                              <th>IAMT</th> 
                              <th>Amount</th>
                              <th>MOQ</th>
                              <th>Freight</th>
                              <th>Total Amount</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis">
                           <tr class="hide primary" id="tr1">
                              <td><input type="text" name="id[]" value="1" id="id" style="width:50px; height:30px;"/></td>
                              <td>
                                 <button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> 
                              </td>
                              <td>
                                 <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" >X</button> 
                              </td>
                              <td> 
                                 <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv" disabled>?</button>
                              </td>
                              <td><input type="text"  name="sales_order_no[]" value="" id="sales_order_no" style="width:80px;"   /> </td>
                              <td>
                                 <select name="class_ids[]" class="class select2" id="class_ids" style="width:250px; height:30px;" disabled >
                                    <option value="">--Select--</option>
                                    @foreach($ClassList as  $row2)
                                    <option value="{{$row2->class_id}}" > {{$row2->class_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td></td>
                              <td>
                                 <select name="item_codes[]" class="item select2" id="item_code" style="width:250px; height:30px;" onchange="getItemDetails(this);" >
                                    <option value="">--Select Item--</option>
                                    @foreach($itemlist as  $row1)
                                    <option value="{{$row1->item_code}}" > {{$row1->item_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="text"  name="hsn_code[]" value="" id="hsn_code" style="width:80px;"  readOnly/> </td>
                              <td>
                                 <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" disabled  >
                                    <option value="">--Select Unit--</option>
                                    @foreach($unitlist as  $rowunit)
                                    <option value="{{$rowunit->unit_id}}">{{$rowunit->unit_name}}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="text" value="0"  name="bom_qty[]" id="bom_qty" style="width:80px;  height:30px;" readOnly/></td>
                              <td><input type="text" value="0"  name="stock[]" id="stock" style="width:80px;  height:30px;" onclick="stockPopup(this);" readOnly/></td>
                              <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]" value="" id="item_qty" style="width:80px;  height:30px;" />
                                 <input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                              </td>
                              <td><input type="number" step="any" name="item_rates[]" value="0" class="RATE"  id="item_rate" style="width:80px; height:30px;" /></td>
                              <td><input type="number" step="any" name="pur_cgsts[]" readOnly value="0" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;"/></td>
                              <td><input type="number" step="any" name="camts[]" readOnly value="0" class="GSTAMT"  id="camt" style="width:80px; height:30px;"/></td>
                              <td><input type="number" step="any" name="pur_sgsts[]" readOnly value="0" class=""  id="pur_sgst" style="width:80px; height:30px;"/></td>
                              <td><input type="number" step="any" name="samts[]" readOnly  value="0" class="GSTAMT"  id="samt" style="width:80px; height:30px;"/></td>
                              <td><input type="number" step="any" name="pur_igsts[]" readOnly value="0" class=""  id="pur_igst" style="width:80px; height:30px;"/></td>
                              <td><input type="number" step="any" name="iamts[]" readOnly value="0" class="GSTAMT"  id="iamt" style="width:80px; height:30px;"/></td> 
                              <td><input type="hidden" step="any" name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;"/><input type="hidden" step="any" name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;"/><input type="number" step="any" name="amounts[]" readOnly value="0" class="GROSS"  id="amount" style="width:80px; height:30px;"/></td>
                              <td><input type="text" value="0" name="moq[]" id="moq" style="width:80px;  height:30px;" readOnly/></td>
                              <td><input type="number" step="any" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="0" onkeyup="calFreightAmt(this);" style="width:80px; height:30px;"></td>
                              <td><input type="number" step="any" name="total_amounts[]" readOnly class="TOTAMT" value=""  id="total_amount" style="width:80px; height:30px;"/>
                                 <input type="hidden" step="any"  name="conQtys[]" readOnly value="1000" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="unitIdMs[]" readOnly value="5" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="priUnitds[]" readOnly value="10" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="SecConQtys[]" readOnly value="10" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="secUnitIds[]" readOnly value="11" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="poQtys[]" readOnly value="0" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="poUnitIds[]" readOnly value="9" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="rateMs[]" readOnly value="0" style="width:80px; height:30px;">
                                 <input type="hidden" step="any"  name="totalQtys[]" readOnly value="0" style="width:80px; height:30px;">
                              </td>
                           </tr>
                        </tbody>
                        <tfoot>
                           <tr>
                              <th>Sr No</th>
                              <th>Add</th>
                              <th>Remove</th>
                              <th>Conversion</th>
                              <th>Sales Order No</th>
                              <th>Classificaion</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>HSN No</th>
                              <th>Unit</th>
                              <th>BOM Qty</th>
                              <th>Stock</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>CGST%</th>
                              <th>CAMT</th>
                              <th>SGST%</th>
                              <th>SAMT</th>
                              <th>IGST%</th>
                              <th>IAMT</th> 
                              <th>Amount</th>
                              <th>MOQ</th>
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
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input type="hidden" value="0" name="address" class="form-control" id="address">
                        <input type="text" readOnly value="0" name="total_qty" class="form-control" id="total_qty">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Gross_amount" class="form-label">Gross Amount</label>
                        <input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onchange="mycalc();"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Gst_amount" class="form-label">GST Amount</label>
                        <input type="text" name="Gst_amount" class="form-control" id="Gst_amount"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="totFreightAmt" class="form-label">Freight Amount</label>
                        <input type="text" name="totFreightAmt"  class="form-control" id="totFreightAmt" value="0"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Net_amount" class="form-label">Net Amount</label>
                        <input type="text" name="Net_amount" class="form-control" id="Net_amount"  readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="delivery_date" class="form-label">Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" id="delivery_date" required>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4 hide">
                     <div class="mb-3">
                        <label for="deliveryAddress" class="form-label">Delivery Address</label>
                        <input type="text" name="deliveryAddress" class="form-control" id="deliveryAddress" value="GAT NO 298/299,A/P Kondigre,  Kolhapur, Maharashtra, 416101">
                     </div>
                  </div>
                  @php 
                  if(Session::get('userId') == 1 || Session::get('userId') == 2 || Session::get('userId') == 3)
                  {
                  @endphp
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="po_status" class="form-label">Job Status</label>
                        <select name="po_status" class="form-select" id="po_status" onchange="setCloseDate(this);" disabled>
                           <option value="1" selected>Moving</option>
                           <option value="2">Non Moving</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="closeDate" class="form-label">Close Date</label>
                        <input type="date" name="closeDate" class="form-control" id="closeDate" value="" readonly >
                     </div>
                  </div>
                  @php 
                  }
                  @endphp
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
                            <p>1. Ken Global Designs Pvt Ltd have right to reject any goods which is rejected by our QC and vendor will be sole responsible for rejection.<br />
                            2 .Ken Global Designs Pvt Ltd reserves the right to reject the goods if we find them defective even at the later stage and to recover the cost of material and losses if any from the<br />
                            sellers.<br />
                            3. Payment shall be made for the actual quantity received by us and our records shall be final and conclusive on this point.<br />
                            4. Ken Global Designs Pvt Ltd will be entitled to deduct Discount as mentioned in the order.<br />
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
                  <a href="{{ Route('PurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<div class="form-popup-bg">
   <div class="form-container">
      <button id="btnCloseForm" class="close-button">X</button>
      <h1 style="color: #db8e02;">Stock Details</h1>
      <div class="col-md-12">
         <table id="stockPopupTable" class="table  table-bordered table-striped m-b-0 footable_2 stripe row-border order-column" cellspacing="0" width="100%" style="color: antiquewhite!important;">
            <thead>
               <tr>
                  <th nowrap style="color: antiquewhite">Supplier Name</th>
                  <th nowrap style="color: antiquewhite">PO No</th>
                  <th nowrap style="color: antiquewhite">GRN No.</th>
                  <th nowrap style="color: antiquewhite">Track Code/Roll No.</th>
                  <th nowrap style="color: antiquewhite">Qty.</th>
                  <th nowrap style="color: antiquewhite">Width</th>
                  <th nowrap style="color: antiquewhite">Rack No.</th>
               </tr>
            </thead>
            <tbody id="stockPopupBody">
               <tr>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- Hidden Template (rendered by Blade safely) -->
<script type="text/template" id="empty-row-template">
   <tr class="hide primary" id="tr1">
      <td>
         <input type="text" name="id[]" value="1" id="id" style="width:50px; height:30px;" />
      </td>
      <td>
         <button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button>
      </td>
      <td>
         <button type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);">X</button>
      </td>
      <td>
         <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left unit_conv" disabled>?</button>
      </td>
      <td>
         <input type="text" name="sales_order_no[]" value="" id="sales_order_no" style="width:80px;" />
      </td>
      <td>
         <select name="class_ids[]" class="class select2" id="class_ids" style="width:250px; height:30px;" disabled>
            <option value="">--Select--</option>
            @foreach($ClassList as $row2)
               <option value="{{$row2->class_id}}">{{$row2->class_name}}</option>
            @endforeach
         </select>
      </td>
      <td></td>
      <td>
         <select name="item_codes[]" class="item select2" id="item_code" style="width:250px; height:30px;" onchange="getItemDetails(this);">
            <option value="">--Select Item--</option>
            @foreach($itemlist as $row1)
               <option value="{{$row1->item_code}}">{{$row1->item_name}}</option>
            @endforeach
         </select>
      </td>
      <td>
         <input type="text" name="hsn_code[]" value="" id="hsn_code" style="width:80px;" readonly />
      </td>
      <td>
         <select name="unit_id[]" id="unit_id" style="width:100px; height:30px;" disabled>
            <option value="">--Select Unit--</option>
            @foreach($unitlist as $rowunit)
               <option value="{{$rowunit->unit_id}}">{{$rowunit->unit_name}}</option>
            @endforeach
         </select>
      </td>
   </tr>
</script>

<script> 

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

    function hasTableRows(tbodyId) {
      var $tbody = $("#" + tbodyId);
      var rowCount = $tbody.find("tr").length;
      return rowCount > 0; // ✅ true if rows exist, false otherwise
   }

   // ✅ Automatically check whenever something happens on the page
   $(document).on("click change keyup", function () {
      var $tbody = $("#bomdis");

      if (hasTableRows("bomdis")) {
         console.log("✅ Rows are present in the table.");
      } else {
         console.log("⚠️ No rows found in the table. Inserting default row...");

         // ✅ insert <tr> from the template into tbody
         $tbody.html($("#empty-row-template").html());

         // ✅ reinitialize Select2 dropdowns after insertion
         if ($.fn.select2) {
            $tbody.find(".select2").select2();
         }

         // ✅ confirm row added
         console.log("✅ Row inserted:", $tbody.html());
      }
   });
   $(document).ready(function () 
   {
      // Initialize oldTypes from current selection (normalized)
      var initial = $("#bom_type option:selected").map(function () {
      return ($(this).text() || "").trim().toLowerCase();
      }).get();
      $("#bom_type").data("oldTypes", initial);
   
      // Listen for normal change and Select2 events (if Select2 is present)
      $("#bom_type").on("change select2:select select2:unselect", function (event) {
         DropdownEmpty(event);
      });
   
      // if other controls should trigger it keep them (optional)
      $("#bom_type, #class_id").on("change", function (event) {
         // you previously only acted when target === "bom_type"
         // keep this or remove if unnecessary
         DropdownEmpty(event);
      });
   });
   
   function DropdownEmpty(event) 
   {
      var target = event ? $(event.target).attr("id") : null;
      var bom_type = $("#bom_type").val() || [];
      var class_id = $("#class_id").val() || [];
      var po_type_id = $("#po_type_id").val();

      // --- 1️⃣ Enable / Disable bom_code safely
      if (bom_type.length === 0 && class_id.length === 0) {
         if (po_type_id != 2) {
            $("#bomdis").empty();
            if(po_type_id == 2)
            { 
               $("#bom_code").prop("disabled", true);
            }
            else
            {
               $("#bom_code").prop("disabled", false);
            }

            $("#class_id").empty().val("").trigger("change");
         }
      } 
      else 
      {
         $("#bom_code").prop("disabled", true);
      }

      // Only continue when bom_type actually changed
      if (target !== "bom_type") return;

      // --- 2️⃣ Normalize selected texts
      var selectedTypes = $("#bom_type option:selected")
         .map(function () {
            return ($(this).text() || "").trim().toLowerCase();
         })
         .get();

      var oldTypes = $("#bom_type").data("oldTypes") || [];

      var removedTypes = oldTypes.filter(t => !selectedTypes.includes(t));
      var addedTypes = selectedTypes.filter(t => !oldTypes.includes(t));

      // Store the new state
      $("#bom_type").data("oldTypes", selectedTypes);

      // --- helper: check if this type still exists in table
      function isTypeUsedInTable(typeTextLower) {
         var used = false;
         $("#bomdis tr").each(function () {
            var rowCat = (($(this).data("cat") || "").toString().trim().toLowerCase());
            if (rowCat === typeTextLower) {
               used = true;
               return false; // stop loop
            }
         });
         return used;
      }

      // --- 3️⃣ Handle removed types
      if (removedTypes.length > 0) {
         removedTypes.forEach(function (removedType) {
            var stillUsed = isTypeUsedInTable(removedType);

            // Remove class options linked to this type
            $("#class_id option").each(function () {
               var relatedType = (($(this).data("bomtype") || "").toString().trim().toLowerCase());
               if (relatedType === removedType && !stillUsed) {
                  $(this).remove();
               }
            });

            // Remove rows only if not used elsewhere
            if (!stillUsed) {
               $("#bomdis tr").filter(function () {
                  var rowCat = (($(this).data("cat") || "").toString().trim().toLowerCase());
                  return rowCat === removedType;
               }).fadeOut(150, function ()
               { 
                  $(this).remove(); 
                   
               });
            }
         });

         triggerSelect2Safe("#class_id");
      }

      // --- 4️⃣ Handle added types (load new class options)
      if (addedTypes.length > 0) {
         var bom_types = addedTypes.join(",");
         var bom_codes = ($("#bom_code").val() || []).join(",");

         // Cancel old pending AJAX if any
         if (DropdownEmpty.xhr && DropdownEmpty.xhr.readyState !== 4) {
            DropdownEmpty.xhr.abort();
         }

         DropdownEmpty.xhr = $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('getClassLists') }}",
            data: {
               cat_id: bom_types,
               sales_order_nos: bom_codes,
               po_type_id: po_type_id
            },
            beforeSend: function () {
               $("#class_id").prop("disabled", true);
            },
            success: function (data) {
               if (data && data.html) {
                  var $tmp = $("<div>").html("<select>" + data.html + "</select>");
                  $tmp.find("option").each(function () {
                     var val = $(this).val();
                     if ($("#class_id option[value='" + val + "']").length === 0) {
                        $("#class_id").append($(this).clone());
                     }
                  });
               } else {
                  $("#class_id").val("").trigger("change");
               }

               triggerSelect2Safe("#class_id");
            },
            error: function (xhr, status, err) {
               if (status !== "abort") console.error("getClassLists error:", status, err);
            },
            complete: function () {
               $("#class_id").prop("disabled", false);
            }
         });
      }

   }

   // --- helper
   function triggerSelect2Safe(selector) {
      if ($(selector).hasClass("select2-hidden-accessible")) {
         $(selector).trigger("change.select2");
      } else {
         $(selector).trigger("change");
      }
   }

   function getBomDetail()
   { 
       var po_type_id=$("#po_type_id").val();
       var class_id=$("#class_id").val();
       if(parseInt(po_type_id) == 2)
       {
           $("#tr1").removeClass("hide");
       }
       else
       {
           $("#tr1").addClass("hide");
       }
       if(class_id !='')
       { 
         if(po_type_id!=2)
         {
            var type=$("#bom_type").val();
            var  bom_codes = $("#bom_code option:selected").map(function() {
               return this.value;
            }).get().join(",");
            
               var class_ids = $("#class_id option:selected").map(function() {
                  return this.value;
               }).get().join(",");
               var tax_type_id = document.getElementById("tax_type_id").value;
               
               classIdsArray = class_ids.split(',');
               $(classIdsArray).each(function(i)
               {
                  $(".cls_"+classIdsArray[i]).remove();
                  $.ajax({
                     type: "GET",
                     url: "{{ route('getBoMDetail') }}",
                     data: { type: type, bom_code: bom_codes, tax_type_id: tax_type_id, class_ids: classIdsArray[i] },
                     success: function(response) 
                     {
                           $("#bomdis").append(response.html);
                           recalcId();
                           mycalc();
                     }
                  });
               
               });
         }
         else
         {
            
            var class_id=$("#class_id").val();
            
            $.ajax({
                  dataType:"json",
                  url: "{{ route('GetClassItemList') }}",
                  data:{'class_id':class_id},
                  success: function(data)
                  {
                     $("select[name='item_codes[]']").html(data.html);
                     $("#bomdis").find('tr td').each(function()
                     {
                           var item_code = $(this).attr('item_code'); 
                           $(this).find('select[name="item_codes[]"] option[value="'+item_code+'"]').prop('selected',true).change(); 
                     });
                  }
            });
         }  
      } 
      else
      {
         if(po_type_id==2)
         { 
            $("#bom_code").attr('disabled')
         }
         else
         {
            $("#bomdis").html("");
            $("#bom_code").removeAttr('disabled')
         }
      }
    }
   
    function setCloseDate(row)
    {
        if($(row).val() == 2)
        {
            alert("This process cannot be reversed...!");
            var today = new Date();
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var year = today.getFullYear();
            var formattedDate = year + '-' + month + '-' + day;
            $("#closeDate").val(formattedDate);
        }
        else
        {
            $("#closeDate").val('');
        }
   
    }
    
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
   CKEDITOR.replace('editor1'); 
   
    function closeForm() {
      $('.form-popup-bg').removeClass('is-visible');
    }
    
    
    function calFreightAmt(row)
    {
        var freight_amt = $(row).val() ? $(row).val() : 0;
        var totAmt = $(row).parent().next().find('input[name="total_amounts[]"]').val() ? $(row).parent().next().find('input[name="total_amounts[]"]').val() : 0;
        var total_Amt = parseFloat(freight_amt) + parseFloat(totAmt);
        $(row).parent().next().find('input[name="total_amounts[]"]').val(total_Amt);
    }
    
    function stockPopup(row,item_code)
    {
        var obj = $(row).parent().parent().find('td:nth-child(3) input')[0]; 
       
        var sales_order_no = $(obj).val();
        var bom_type_arr = $("#bom_type").val();
        $.ajax(
           {
           type:"GET",
           dataType:'json',
           url: "{{ route('GetStockDetailPopup') }}",
           data:{item_code:item_code, sales_order_no:sales_order_no,bom_type_arr:bom_type_arr},
           success:function(response)
           {
                $("#stockPopupBody").html(response.html);
                $('.form-popup-bg').addClass('is-visible');
           }
        });
   
    }
    $(document).ready(function($) 
    {
      
        //close popup when clicking x or off popup
      $('.form-popup-bg').on('click', function(event) {
        if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) {
          event.preventDefault();
          $(this).removeClass('is-visible');
        }
      });
      
      
      
      });
   
   $(document).on("change", 'input[class^="ITEMQTY"],input[class^="RATE"]', function (event) 
   {
              var po_type_id=$('#po_type_id').val();
             if(po_type_id!=2)
            {  var value = $(this).val();
              var maxLength = parseFloat($(this).attr('max'));
              var minLength = parseFloat($(this).attr('min')); 
              if(value>maxLength){alert('Value can not be greater than '+maxLength);}
              if ((value !== '') && (value.indexOf('.') === -1)) 
              {
                   $(this).val(Math.max(Math.min(value, maxLength), minLength));
              }
              
            }
      
     
   });
   
   function EnableFields()
   {        
        $("select").prop('disabled', false);
   }
   
   
   let index = 2;
   
   function insertRow() 
   {
      var $table = $("#footable_2 tbody");
      var $lastRow = $table.find("tr:last");
   
      // Destroy Select2 to avoid duplication
      $lastRow.find("select").select2('destroy');
   
      // Clone last row
      var $newRow = $lastRow.clone();
   
      // Clear input/select values
      $newRow.find("input, select").val("");
   
      // Append new row
      $newRow.appendTo($table);
   
      // Reinitialize Select2
      $newRow.find("select").select2();
   
      // Recalculate IDs or perform other logic
      recalcId();
      selselect();
      $newRow.find('.unit_conv').attr('disabled', true);
   }
   
   
   function selselect()
   {
      setTimeout(function() 
      {      
         $("#footable_2 tr td  select[name='item_codes[]']").each(function() {      
            $(this).closest("tr").find('select[name="item_codes[]"]').select2();
         });
      }, 2000);
   }
   
   
   function deleteRow(btn) {
   if(document.getElementById('cnt').value > 0){
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
           sum1 = 0.0;
           var amounts = document.getElementsByClassName('GROSS');
           //alert("value="+amounts[0].value);
           for(var i=0; i<amounts .length; i++)
           { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
           }
           document.getElementById("Gross_amount").value = sum1.toFixed(2);
           
           
           
           sum1 = 0.0;
           var amounts = document.getElementsByClassName('GSTAMT');
           //alert("value="+amounts[0].value);
           for(var i=0; i<amounts .length; i++)
           { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
           }
           document.getElementById("Gst_amount").value = sum1.toFixed(2);
           
           sum1 = 0.0;
           var amounts = document.getElementsByClassName('TOTAMT');
           //alert("value="+amounts[0].value);
           for(var i=0; i<amounts .length; i++)
           { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
           }
           document.getElementById("Net_amount").value = sum1.toFixed(2);
           
           
           sum1 = 0.0;
           var amounts = document.getElementsByClassName('FREIGHT');
           //alert("value="+amounts[0].value);
           for(var i=0; i<amounts .length; i++)
           { 
           var a = +amounts[i].value;
           sum1 += parseFloat(a);
           }
           document.getElementById("totFreightAmt").value = sum1.toFixed(2);
           
           
           
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
      
      $.ajax({
         type:"GET",
         dataType:'json',
         url: "{{ route('GSTPER') }}",
         data:{item_code:item_code,tax_type_id:tax_type_ids},
         success:function(response)
         {         
            if(tax_type_id==1)
            {         
               $("#pur_cgst").val(response[0].cgst_per); 
               $("#pur_sgst").val(response[0].sgst_per); 
               $("#pur_igst").val(response[0].igst_per);
            } 
            else
            {
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
           success: function(response)
           {                
               row.find('select[name^="class_ids[]"]').val(response.data[0]['class_id'] ? response.data[0]['class_id'] : 0).trigger('change');
               row.find('td:eq(6)').html(response.data[0]['item_code']);
               row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0).trigger('change');
               row.find('.unit_conv').attr('disabled', true);
               if(response.data[0]['cat_id'] == 1)
               {
                     row.attr('data-cat','Fabric');
                     row.find('.unit_conv').attr('disabled', true);
               }
               else if(response.data[0]['cat_id'] == 2)
               {
                     row.attr('data-cat','Sewing');
                     row.find('.unit_conv').attr('disabled', false);
               }
               else if(response.data[0]['cat_id'] == 3)
               {
                     row.attr('data-cat','Packing');
                     row.find('.unit_conv').attr('disabled', true);
               }
               else
               {
                     row.attr('data-cat','Other');
                     row.find('.unit_conv').attr('disabled', true);
               }
  
               if(tax_type_ids==1 && response != "")
               {
                   row.find('input[name^="pur_cgsts[]"]').val(response.data[0]['cgst_per'] ? response.data[0]['cgst_per'] : 0);
                   row.find('input[name^="pur_sgsts[]"]').val(response.data[0]['sgst_per'] ? response.data[0]['sgst_per'] : 0);
                   row.find('input[name^="pur_igsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0);
                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.com/thumbnail/'+response.data[0]['item_image_path'] ? response.data[0]['item_image_path'] : "");
                   row.find('input[name^="moq[]"]').val(response.data[0]['moq'] ? response.data[0]['moq'] : "");
                   row.find('input[name^="stock[]"]').val(response.stock[0]['Stock'] ? response.stock[0]['Stock'] : 0);                 
                  
               }
               else if(tax_type_ids==2 && response != "")
               {
                   row.find('input[name^="pur_igsts[]"]').val(response.data[0]['igst_per'] ? response.data[0]['igst_per'] : 0);
                   row.find('input[name^="pur_cgsts[]"]').val(0);
                   row.find('input[name^="pur_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0);
                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.com/thumbnail/'+response.data[0]['item_image_path'] ? response.data[0]['item_image_path'] : "");
                   row.find('input[name^="moq[]"]').val(response.data[0]['moq'] ? response.data[0]['moq'] : "");
                   row.find('input[name^="stock[]"]').val(response.stock[0]['Stock'] ? response.stock[0]['Stock'] : 0);
               }
               else if(tax_type_ids==3 && response != "")
               {
                   row.find('input[name^="pur_igsts[]"]').val(0);
                   row.find('input[name^="pur_cgsts[]"]').val(0);
                   row.find('input[name^="pur_sgsts[]"]').val(0);
                   row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code'] ? response.data[0]['hsn_code'] : "");
                   row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id'] ? response.data[0]['unit_id'] : 0); 
                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.com/thumbnail/'+response.data[0]['item_image_path'] ? response.data[0]['item_image_path'] : "");
                   row.find('input[name^="moq[]"]').val(response.data[0]['moq'] ? response.data[0]['moq'] : "");
                   row.find('input[name^="stock[]"]').val(response.stock[0]['Stock'] ? response.stock[0]['Stock'] : 0);
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
   
   $("#pur_code").val(response["code"]+'-'+response["tr_no"]);
   $("#c_code").val(response["c_code"]);
   
   }
   });
   }
       
   function PODisabled()
   {
       $("#po_type_id").attr("disabled", true);   
       var po_type_id = $("#po_type_id").val();
       if(po_type_id != 2)
       {
        $("#bom_code").attr("disabled", false); 
       }
   }
   
   function SetBillTo()
   {
       $("#bill_to").select2('destroy').select2();
       $("#bill_to").val("").trigger("change");
       $("#ship_to").select2('destroy').select2();
       $("#ship_to").val("").trigger("change");
       var po_type_id = $("#po_type_id").val();
       if(po_type_id == 2)
       {
           $("#tr1").removeClass("hide");   
           $("#bom_code").attr("disabled", true); 
           $("#buyer_id").attr("disabled", false); 
       }
       else
       {
           $("#tr1").addClass("hide");
           $("#buyer_id").attr("disabled", true); 
       }
        
   }
   
   function getPartyDetails() {
      var bill_to = $("#bill_to").val();  
      if (bill_to != '') {
         $.ajax({
               type: "GET",
               dataType: "json",
               url: "{{ route('PartyDetailForPO') }}",
               data: { 'bill_to': bill_to },
               success: function(data) {
                  console.log(data);
                  $("#gstNo").val(data[0]['gst_no']);
                  if (data[0]['state_id'] == 27) { $("#tax_type_id").val(1); }
                  else { $("#tax_type_id").val(2); }
               }
         });
      }
   }
   
   
   
   function GetPartyDetailsSale()
   {
       
       var ac_code = $("#Ac_code").val(); 
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetPartyDetailsPurchase') }}",
           data:{'ac_code':ac_code},
           success: function(data)
           { 
               $("#bill_to").html(data.detail); 
           }
        });
        
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetAllTradersFromPurchase') }}",
           data:{'ac_code':ac_code},
           success: function(data)
           { 
               $("#ship_to").html(data.detail); 
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
       var po_type_id = $("#po_type_id").val();
      
       //  cat_id=$("#bom_type").val();
       var  bom_types = $("#bom_type option:selected").map(function() {
         return this.value;
       }).get().join(",");
        
        
       var  bom_codes = $("#bom_code option:selected").map(function() {
         return this.value;
       }).get().join(",");
        
        $.ajax({
           dataType: "json",
           url: "{{ route('getClassLists') }}",
           data:{'cat_id':bom_types, sales_order_nos: bom_codes,'po_type_id':po_type_id},
           success: function(data){
           $("#class_id").html(data.html);
           
          }
       }); 
   }
   
   
      $(document).ready(function()
      {
         var maxSelection = 0;
         $("#bom_code").change(function () 
         {
               var po_type_id = $("#po_type_id").val();
               var bom_code= $(this).val();
               
               $.ajax({
                     type: "GET",
                     dataType:"json",
                     url: "{{ route('GetBuyerFromBOM') }}",
                     data:{'bom_code':bom_code[0]},
                     success: function(data)
                     {
                        $("#buyer_id").val(data.buyer_id).trigger('change');
                        $("#buyer_id").attr("disabled", true);
                     }
               });
         
               if(parseInt(po_type_id) == 1)
               {
                  var selectedOptions = $(this).find("option:selected");
         
                  if(selectedOptions.length > maxSelection) 
                  {
                     
                     // Prevent further selections by disabling unselected options
                     $("#bom_code option").prop("disabled", false); // Enable all first
                     selectedOptions.each(function () {
                           $("#bom_code option:not(:selected)").prop("disabled", true);
                     });
                  } 
                  else 
                  {
                     $("#bom_code option").prop("disabled", false); // Re-enable if within limit
                  }
               }
         }); 
         
            var previousSelection = [];
            
            $('#class_id').change(function(){
            
               
               var currentSelection = $(this).val() || [];
               
               // Compare previous selection with current selection
               $(previousSelection).each(function(index, value){
               if ($.inArray(value, currentSelection) === -1) 
               {
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
            
      
      });
       
   
    var rows=new Object();
    function setConversion(row)
    {
             rows = $(row).closest('tr'); 
             var idx =  $(row).closest('tr').index();
           // var unit_id= rows.find('select[name^="unit_id[]"]').val();
           // var rate= rows.find('input[name^="itemrates[]"]').val();
           // // alert(unit_id);
           
           // $("#unitIdM").val(unit_id);
           //   $("#rateMs").val(rateM);
             
           var conQty= rows.find('input[name^="conQtys[]"]').val();
           var unitIdM= rows.find('input[name^="unitIdMs[]"]').val();
           var priUnitd= rows.find('input[name^="priUnitds[]"]').val();
           var SecConQty= rows.find('input[name^="SecConQtys[]"]').val();
           var secUnitId= rows.find('input[name^="secUnitIds[]"]').val();
           var poQty= rows.find('input[name^="poQtys[]"]').val();
           var poUnitId= rows.find('input[name^="poUnitIds[]"]').val();
           var rateM= rows.find('input[name^="rateMs[]"]').val();
           var totalQty= rows.find('input[name^="totalQtys[]"]').val();
           var bomQty= rows.find('input[name^="bom_qty[]"]').val(); 
            
           $("#conQty").val(conQty);
           $("#unitIdM").val(unitIdM);
           $("#priUnitd").val(priUnitd);
           $("#SecConQty").val(SecConQty);
           $("#secUnitId").val(secUnitId);
           $("#poQty").val(poQty);
           $("#poUnitId").val(poUnitId);
           $("#rateM").val(rateM);
           $("#totalQty").val(totalQty);      
           $("#bomQty").val(bomQty);      
           $("#idx").val(idx);       
             
        mycalc();
        $('#modalFormSize').modal('show');
    }
    
    function closemodal()
    {
        $('#modalFormSize').modal('hide');
        setTimeout(function () {
            assignValue();
            mycalc();
        }, 500);
       
       //    $('#product-options').modal('hide');
    }
    
    
    
    function assignValue() {
        // Retrieve and assign input values
        var conQty = parseFloat($("#conQty").val()) || 0;
        var unitIdM = $("#unitIdM").val();
        var priUnitd = $("#priUnitd").val();
        var SecConQty = parseFloat($("#SecConQty").val()) || 0;
        var secUnitId = $("#secUnitId").val();
        var poQty = $("#poQty").val();
        var poUnitId = $("#poUnitId").val();
        var rateM = parseFloat($("#rateM").val()) || 0;
        var totalQty = parseFloat($("#totalQty").val()) || 0;
        var idx = $("#idx").val();
    
        rows.find('input[name^="conQtys[]"]').val(conQty);
        rows.find('input[name^="unitIdMs[]"]').val(unitIdM);
        rows.find('input[name^="priUnitds[]"]').val(priUnitd);
        rows.find('input[name^="SecConQtys[]"]').val(SecConQty);
        rows.find('input[name^="secUnitIds[]"]').val(secUnitId);
        rows.find('input[name^="poQtys[]"]').val(poQty);
        rows.find('input[name^="poUnitIds[]"]').val(poUnitId);
        rows.find('input[name^="rateMs[]"]').val(rateM);
        rows.find('input[name^="totalQtys[]"]').val(totalQty);
        rows.find('input[name^="item_qtys[]"]').val(totalQty);
    
        // Perform calculations
        var item_qtys = parseFloat(rows.find('input[name^="item_qtys[]"]').val()) || 0;
        var item_rates = parseFloat(rows.find('input[name^="item_rates[]"]').val()) || 0;
        var disc_pers = parseFloat(rows.find('input[name^="disc_pers[]"]').val()) || 0;
        var pur_igsts = parseFloat(rows.find('input[name^="pur_igsts[]"]').val()) || 0;
        var freight_amt = parseFloat(rows.find('input[name^="freight_amt[]"]').val()) || 0;
    
        if (item_qtys > 0) {
            var Amount = item_qtys * item_rates;
            var disc_amt = Amount * (disc_pers / 100);
            rows.find('input[name^="disc_amounts[]"]').val(disc_amt.toFixed(4));
            Amount -= disc_amt;
            rows.find('input[name^="amounts[]"]').val(Amount.toFixed(4));
    
            if (pur_igsts !== 0) {
                var Iamt = Amount * (pur_igsts / 100);
                rows.find('input[name^="iamts[]"]').val(Iamt.toFixed(4));
                var TAmount = Amount + Iamt + freight_amt;
                rows.find('input[name^="total_amounts[]"]').val(TAmount.toFixed(4));
            } else {
                var pur_cgsts = parseFloat(rows.find('input[name^="pur_cgsts[]"]').val()) || 0;
                var pur_sgsts = parseFloat(rows.find('input[name^="pur_sgsts[]"]').val()) || 0;
                var Camt = Amount * (pur_cgsts / 100);
                var Samt = Amount * (pur_sgsts / 100);
                rows.find('input[name^="camts[]"]').val(Camt.toFixed(4));
                rows.find('input[name^="samts[]"]').val(Samt.toFixed(4));
                TAmount = Amount + Camt + Samt + freight_amt;
                rows.find('input[name^="total_amounts[]"]').val(TAmount.toFixed(4));
            }
        }
     
    
        var final_rate = conQty && SecConQty ? (rateM / (conQty * SecConQty)) : 0;
        $("#footable_2 > tbody")
            .find(`tr:eq(${idx}) td:eq(9) input`)
            .removeAttr('max');
        $("#footable_2 > tbody")
            .find(`tr:eq(${idx}) td:eq(10) input`)
            .val(final_rate.toFixed(4));
    
        
        // Ensure values are updated before invoking mycalc()
    }
   
     
   function calcQty() 
   {
       setTimeout(function() 
       {
           var conQty= $("#conQty").val();
           var bomQty= $("#bomQty").val();
           var unitIdM= $("#unitIdM").val();
           var priUnitd= $("#priUnitd").val();
           var SecConQty= $("#SecConQty").val();
           var secUnitId= $("#secUnitId").val();
           var poQty= $("#poQty").val();
           var poUnitId= $("#poUnitId").val();
           var rateM= $("#rateM").val();
             
           var totalQty = poQty * (conQty * SecConQty);
   
           $("#totalQty").val(totalQty); 
           
       }, 500); 
   }
     
   function calcPOQty() 
   {
       
       var conQty= $("#conQty").val();
       var bomQty= $("#bomQty").val(); 
       var SecConQty= $("#SecConQty").val();
       
       var cal_po_qty = parseFloat(bomQty/(conQty * SecConQty));
       var cal_po_qty1 = Math.ceil(cal_po_qty);
       $("#poQty").val(cal_po_qty1);
       calcQty(); 
       
   }
</script>
<div class="modal fade" id="modalFormSize" role="dialog">
   <div class="modal-dialog" style="margin: 1.75rem 19rem;">
      <div class="modal-content" style="width: 900px;">
         <!-- Modal Body -->
         <div class="modal-body">
            <p class="statusMsg"></p>
            <div class="seprator-block"></div>
            <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>Unit Conversion</h6>
            <hr class="light-grey-hr"/>
            <div class="row">
               <div id="InwardData" class="table-responsive">
                  <table >
                     <thead>
                        <tr>
                           <th>Conv. Qty</th>
                           <th>UOM</th>
                           <th>Pri. Unit</th>
                           <th>Second Conv. Qty</th>
                           <th>Sec. Unit</th>
                           <th>PO Qty</th>
                           <th>PO Unit</th>
                           <th>Rate</th>
                           <th>Total Qty</th>
                        </tr>
                     </thead>
                     <tbody>
                        <th><input id="conQty" type="number" value="0" onkeyup="calcQty();calcPOQty();mycalc();"/></th>
                        <th>
                           <select id="unitIdM" >
                              @foreach($unitlist as  $rowunit)
                              <option value="{{ $rowunit->unit_id  }}"
                                 >{{ $rowunit->unit_name }}</option>
                              @endforeach
                           </select>
                        </th>
                        <th>
                           <select id="priUnitd" >
                              @foreach($unitlist as  $rowunit)
                              <option value="{{ $rowunit->unit_id  }}"
                                 >{{ $rowunit->unit_name }}</option>
                              @endforeach
                           </select>
                        </th>
                        <th><input id="SecConQty" type="number" value="0" onkeyup="calcQty();calcPOQty();mycalc();"/></th>
                        <th>
                           <select id="secUnitId" >
                              @foreach($unitlist as  $rowunit)
                              <option value="{{ $rowunit->unit_id  }}"
                                 >{{ $rowunit->unit_name }}</option>
                              @endforeach
                           </select>
                        </th>
                        </th>
                        <th><input id="poQty" type="number" value="0" onkeyup="calcQty();mycalc();"/></th>
                        <th>
                           <select id="poUnitId" >
                              @foreach($unitlist as  $rowunit)
                              <option value="{{ $rowunit->unit_id  }}"
                                 >{{ $rowunit->unit_name }}</option>
                              @endforeach
                           </select>
                        </th>
                        </th>
                        <th><input id="rateM" type="number" value="0" onchange="mycalc();"/></th>
                        <th><input id="totalQty" type="number" value="0"/></th>
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-success" data-dismiss="modal" onclick="assignValue();mycalc();closemodal();" onsubmit="mycalc();">Submit</button>
               <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();mycalc();">Close</button>
            </div>
            <input id="bomQty" type="hidden" value="0"/>
            <input id="idx" type="hidden" value="0"/>
         </div>
      </div>
   </div>
</div>
@endsection