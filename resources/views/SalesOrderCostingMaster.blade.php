@extends('layouts.master') 
@section('content')
<style>
    .hide
    {
        display:none;
    }
    .navbar-brand-box
    {
        width: 266px !important;
    }
    /* Apply globally */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      -moz-appearance: textfield;
    }

</style>

<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4"><h2><b>Costing</b></h2></h4>
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
            <form action="{{route('SalesOrderCosting.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="soc_date" class="form-label">Entry Date</label>
                        <input type="date" name="soc_date" class="form-control" id="soc_date" value="{{date('Y-m-d')}}" readOnly>
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <input type="hidden" name="cost_type_id" class="form-control" id="cost_type_id" value="1"> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Sales Order No.</label>
                        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
                        <select name="sales_order_no" class="form-select select2" id="sales_order_no"  onChange="getSalesOrderDetails(this.value);">
                           <option value="">--Select Sales Order No--</option>
                           @foreach($SalesOrderList as  $row)
                                <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Buyer PO No.</label>
                        <input type="text" name="po_code" class="form-control" id="po_code" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label> 
                        <select name="order_type" class="form-select select2" id="order_type" disabled>
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
                        <select name="og_id" class="form-select select2" id="og_id"  disabled>
                           <option value="">--Select Market Type--</option>
                           @foreach($OrderGroupList as  $row) 
                               <option value="{{ $row->og_id }}">{{ $row->order_group_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="Ac_code" class="form-label">Buyer/Party Name</label>
                        <select name="Ac_code" class="form-select" id="Ac_code" disabled  >
                           <option value="">--Select Buyer/Party--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}">{{ $row->ac_short_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select" id="brand_id"  disabled>
                           <option value="">--Select Brand--</option>
                           @foreach($BrandList as  $row)
                           {
                           <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency</label>
                        <select name="currency_id" class="form-select" id="currency_id"  disabled>
                           <option value="">--Select Currency--</option>
                           @foreach($CurrencyList as  $row)
                           {
                           <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inr_rate" class="form-label">Rate</label>
                        <input type="text" name="inr_rate" class="form-control" id="inr_rate" value="" onkeyup="calOrderRate()" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="text" name="exchange_rate" class="form-control" id="exchange_rate" value="" onkeyup="calOrderRate()" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Rate (INR)</label>
                        <input type="number" step="any" name="order_rate" class="form-control" id="order_rate" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Order Qty</label>
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="" readonly>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="order_value" class="form-label">Total Value</label>
                        <input type="text" name="order_value" class="form-control" id="order_value" value="" readonly>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Style</label>
                        <select name="mainstyle_id" class="form-select" id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                           <option value="">--Select Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                              >{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select" id="substyle_id" onchange="getStyle(this.value)" disabled>
                           <option value="">--Select Sub Style --</option>
                           @foreach($SubStyleList as  $row)
                           {
                           <option value="{{ $row->substyle_id }}"
                              >{{ $row->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-select" id="fg_id" disabled>
                           <option value="">--Select Style Name--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                              >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No.</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="text" name="sam" class="form-control" id="sam" value="" readOnly onkeyup="calculateMfgCost(this.value);">
                     </div>
                  </div>
                  <input type="hidden" name="transport_ocr_cost" class="form-control" id="transport_ocr_cost" value="0"> 
                  <input type="hidden" name="testing_ocr_cost" class="form-control" id="testing_ocr_cost" value="0"> 
               </div>
               <div class="row">
                  <label   class="form-label"><h4><b>Fabric Details: </b></h4></label>
                  <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Classification</th>
                                 <th>Description</th>
                                 <th>Cons./Gmt (Mtr/Nos)</th>
                                 <th>Per Pcs Cost</th>
                                 <th>Wastage %</th>
                                 <th>Qty</th>
                                 <th>Gmt Cost</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="id" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                    <select name="class_id[]" class="item select2"  id="class_id" style="width:200px; height:30px;" required>
                                       <option value="">--Select Classification--</option>
                                       @foreach($ClassList as  $row)
                                       {
                                       <option value="{{ $row->class_id }}" {{ $row->class_id == 2 ? 'selected="selected"' : '' }} >{{ $row->class_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <input type="text"    name="description[]" value="" id="description" style="width:200px;height:30px;"   />
                                 </td>
                                 <td><input type="number" step="any" name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px;height:30px;" required /></td>
                                 <td><input type="number" step="any" name="wastage[]" value="0" id="wastage" style="width:80px;height:30px;" required /></td>
                                 <td><input type="text"      name="bom_qty[]" value="0" id="bom_qty" style="width:80px;height:30px;" required  readOnly/></td>
                                 <td><input type="number" step="any" class="FABRIC"   name="total_amount[]" value="0" id="total_amount" style="width:80px;height:30px;" required readOnly/></td>
                                 <td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label   class="form-label"><h4><b>Sewing Trims Details:</b> </h4></label>
                  <input type="number" value="1" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Classification</th>
                                 <th>Description</th>
                                 <th>Cons./Gmt (Mtr/Nos)</th>
                                 <th>Per Pcs Cost</th>
                                 <th>Wastage %</th>
                                 <th>Qty</th>
                                 <th>Gmt Cost</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="ids" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                    <select name="class_ids[]" class="item_sewing_trims select2" id="class_ids" style="width:200px; height:30px;" required>
                                       <option value="">--Select Classification--</option>
                                       @foreach($ClassList2 as  $row)
                                       {
                                       <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="text"    name="descriptions[]" value="" id="descriptions" style="width:200px; height:30px;"   /></td>
                                 <td><input type="number" step="any" name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any" name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td>
                                 <td><input type="text"      name="bom_qtys[]" value="0" id="bom_qtys" style="width:80px; height:30px;" required readOnly /></td>
                                 <td><input type="number" step="any"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" required readOnly/></td>
                                 <td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               </br>
               <div class="row">
                  <label   class="form-label"><h4><b>Packing Trims Details: </b></h4></label>
                  <input type="number" value="1" name="cntrr3" id="cntrr3" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
                           <thead>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Classification</th>
                                 <th>Description</th>
                                 <th>Cons./Gmt (Mtr/Nos)</th>
                                 <th>Per Pcs Cost</th>
                                 <th>Wastage %</th>
                                 <th>Qty</th>
                                 <th>Gmt Cost</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td><input type="text" name="idss" value="1" id="id" style="width:50px;" readonly/></td>
                                 <td>
                                    <select name="class_idss[]" class="item_packing_trims select2" id="class_idss" style="width:200px; height:30px;" required>
                                       <option value="">--Select Classification--</option>
                                       @foreach($ClassList3 as  $row)
                                       {
                                       <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td> <input type="text"    name="descriptionss[]" value="" id="descriptionss" style="width:200px; height:30px;"   /></td>
                                 <td><input type="number" step="any"    name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any"     name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" required /></td>
                                 <td><input type="number" step="any"   name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td>
                                 <td><input type="text"     name="bom_qtyss[]" value="0" id="bom_qtyss" style="width:80px; height:30px;" required  readOnly/></td>
                                 <td><input type="number" step="any"  class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;"  readOnly /></td>
                                 <td><button type="button" onclick="insertcone3();" class="btn btn-warning pull-left">+</button></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <style>
                  table{
                  border-collapse: collapse;
                  width: 100%;   
                  }
                  th,td{
                  border: 2px solid black; 
                  }
               </style>
               <!-- end row -->
               <div class="row">
                                    <table id="footable_5" class="table  table-bordered table-striped m-b-0  footable_5">
                     <thead>
                         <tr>
                            <th>Cost Break Up</th>
                            <th>Value   </th>
                            <th>% On FOB Value</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr>
                            <td>Total Fabric Cost</td> 
                            <td><input type="number" step="any" name="fabric_value" class="form-control gar_val tmcv" id="fabric_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" readonly ></td> 
                            <td><input type="number" step="any" name="fabric_per" class="form-control gar_per tmcp" id="fabric_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td> 
                         </tr>
                         <tr>
                            <td>Sewing Trims Cost</td> 
                            <td><input type="number" step="any" name="sewing_trims_value" class="form-control gar_val tmcv" id="sewing_trims_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" readonly></td> 
                            <td><input type="number" step="any" name="sewing_trims_per" class="form-control gar_per tmcp" id="sewing_trims_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td> 
                         </tr>
                         <tr>
                            <td>Packing Trims Cost</td> 
                            <td><input type="number" step="any" name="packing_trims_value" class="form-control gar_val tmcv" id="packing_trims_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" readonly></td> 
                            <td><input type="number" step="any" name="packing_trims_per" class="form-control gar_per tmcp" id="packing_trims_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td> 
                         </tr>
                         <tr>
                            <td>Manufacturing Cost</td> 
                            <td><input type="number" step="any" name="production_value" class="form-control gar_val tmcv value" id="production_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="production_per" class="form-control gar_per tmcp per" id="production_per" style="width:150px;" value="0.00"  onchange="calculate_percentage_value(this);" readonly></td> 
                         </tr>
                         <tr>
                            <td>Garment Washing Cost</td> 
                            <td><input type="number" step="any" name="dbk_value" class="form-control gar_val tmcv value" id="dbk_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="dbk_per" class="form-control gar_per tmcp per" id="dbk_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Printing Cost</td> 
                            <td><input type="number" step="any" name="printing_value" class="form-control tmcv value" id="printing_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="printing_per" class="form-control tmcp per" id="printing_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Embroidery Cost</td> 
                            <td><input type="number" step="any" name="embroidery_value" class="form-control gar_val tmcv value" id="embroidery_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="embroidery_per" class="form-control gar_per tmcp per" id="embroidery_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Total Making Cost</td> 
                            <td><input type="number" step="any" name="total_making_value" class="form-control" id="total_making_value" style="width:150px;" value="0" readonly ></td> 
                            <td><input type="number" step="any" name="total_making_per" class="form-control" id="total_making_per" style="width:150px;" value="0.00" readonly ></td> 
                         </tr>
                         <tr>
                            <td>Garment Rejection %</td> 
                            <td><input type="number" step="any" name="garment_reject_value" class="form-control value" id="garment_reject_value" style="width:150px;" value="0"  readonly ></td> 
                            <td><input type="number" step="any" name="garment_reject_per" class="form-control per" id="garment_reject_per" style="width:150px;" value="0.00" onchange="calculateGarmentRejectionValue(this);" ></td> 
                         </tr>
                         <tr>
                            <td>IXD Cost</td> 
                            <td><input type="number" step="any" name="ixd_value" class="form-control value value1" id="ixd_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="ixd_per" class="form-control per per1" id="ixd_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Commission Cost</td> 
                            <td><input type="number" step="any" name="agent_commission_value" class="form-control value value1" id="agent_commission_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="agent_commission_per" class="form-control per per1" id="agent_commission_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Transport Cost</td> 
                            <td><input type="number" step="any" name="transport_value" class="form-control value value1" id="transport_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="transport_per" class="form-control per per1" id="transport_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Over Head Cost</td> 
                            <td><input type="number" step="any" name="other_value" class="form-control value value1" id="other_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="other_per" class="form-control per per1" id="other_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Testing Charges</td> 
                            <td><input type="number" step="any" name="testing_charges_value" class="form-control value value1" id="testing_charges_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="testing_charges_per" class="form-control per per1" id="testing_charges_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Finance Cost</td> 
                            <td><input type="number" step="any" name="finance_cost_value" class="form-control value value1" id="finance_cost_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="finance_cost_per" class="form-control per per1" id="finance_cost_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Other Cost</td> 
                            <td><input type="number" step="any" name="extra_value" class="form-control value value1" id="extra_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="extra_per" class="form-control per per1" id="extra_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Total Cost</td> 
                            <td><input type="number" step="any" name="total_cost_value" class="form-control" id="total_cost_value" style="width:150px;" value="0" onchange="calculatepercentage(this);" readonly></td> 
                            <td><input type="number" step="any" name="total_cost_per" class="form-control" id="total_cost_per" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" readonly></td> 
                         </tr>
                         <tr>
                            <td>DBK Value 1</td> 
                            <td><input type="number" step="any" name="dbk_value1" class="form-control value" id="dbk_value1" style="width:150px;" value="0" onchange="calculatepercentage(this);" ></td> 
                            <td><input type="number" step="any" name="dbk_per1" class="form-control per" id="dbk_per1" style="width:150px;" value="0.00" onchange="calculate_percentage_value(this);" ></td> 
                         </tr>
                         <tr>
                            <td>Profit</td> 
                            <td><input type="number" step="any" name="profit_value" class="form-control" id="profit_value" style="width:150px;" value="0" readonly></td> 
                            <td><input type="number" step="any" name="profit_per" class="form-control" id="profit_per" style="width:150px;" value="0.00" readonly></td> 
                         </tr>
                    </tbody>
                  </table>
                  <div class="col-sm-8">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="" />
                     </div>
                  </div> 
                   <input type="hidden" name="PDMerchant_id" class="form-control" id="PDMerchant_id"  value="0" />
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="EnableFields();">Submit</button>
                     <a href="{{ Route('SalesOrderCosting.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
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


    $(document).ready(function() {
        
        $('input[type="text"], textarea').on('input', function() {
            // Remove single and double quotes
            $(this).val($(this).val().replace(/['"]/g, ""));
        });
        
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        CalFabricSewingPacking();
        setTimeout(function() {
            calculate_percentage_value(0);
            calculatepercentage(0);
            GetTotalmakingCost();
            calculateTotalCost();
        }, 500);
    });
    
    function GetTotalmakingCost()
    {
        var total_making_value = 0;
        var total_making_per = 0;
        $(".tmcv").each(function()
        {
            total_making_value += parseFloat($(this).val());
        });
        $("#total_making_value").val(total_making_value);
        
        $(".tmcp").each(function()
        {
            total_making_per += parseFloat($(this).val());
        });
        $("#total_making_per").val(total_making_per);
    }
    
    function calculateTotalCost()
    {
        
          var total_cost_value = 0;
          var total_cost_per = 0;
          
          var order_rate = $("#order_rate").val();
          var dbk_value1 = $("#dbk_value1").val();
          var dbk_per1 = $("#dbk_per1").val();
          $(".value1").not("#dbk_value1").each(function()
          {
                 total_cost_value += parseFloat($(this).val());   
          });
          
          $(".per1").not("#dbk_per1").each(function()
          {
                 total_cost_per += parseFloat($(this).val());   
          });
          
        //   $("#total_cost_per").val((parseFloat($("#total_cost_value").val())/parseFloat(order_rate)) * 100); 
        //   $("#total_cost_value").val(parseFloat(total_cost_value) + parseFloat($("#total_making_value").val()) +  parseFloat($("#garment_reject_value").val()));
          
        //   $("#profit_value").val((parseFloat(order_rate) - parseFloat($("#total_cost_value").val()) + parseFloat(dbk_value1))); 
        //   $("#profit_per").val((parseFloat(100) - parseFloat($("#total_cost_per").val()) + parseFloat(dbk_per1)));
          
          var total_cost_per = ((parseFloat($("#total_cost_value").val())/parseFloat(order_rate)) * 100);
          var total_cost_value = parseFloat(total_cost_value) + parseFloat($("#total_making_value").val()) +  parseFloat($("#garment_reject_value").val());
          $("#total_cost_per").val(total_cost_per.toFixed(2));
          $("#total_cost_value").val(total_cost_value.toFixed(2));
          
          var profit_value = (parseFloat(order_rate) - parseFloat($("#total_cost_value").val()) + parseFloat(dbk_value1));
          $("#profit_value").val(profit_value.toFixed(2));
          var profit_per = (parseFloat(100) - parseFloat($("#total_cost_per").val()) + parseFloat(dbk_per1));
          $("#profit_per").val(profit_per.toFixed(2));
    }
    
    function CalFabricSewingPacking()
    {
        var total_fabric_cost = 0;
        var total_sewing_cost = 0;
        var total_packing_cost = 0;
        $(".FABRIC").each(function(){
            total_fabric_cost += parseFloat($(this).val());
        });
        $(".SEWING").each(function()
        {
            total_sewing_cost += parseFloat($(this).val());
        });
        $(".PACKING").each(function(){
            total_packing_cost += parseFloat($(this).val());
        }); 
        $("#fabric_value").val(total_fabric_cost.toFixed(2));
        $("#sewing_trims_value").val(total_sewing_cost.toFixed(2));
        $("#packing_trims_value").val(total_packing_cost.toFixed(2));
    }
    function calculate_percentage_value(row)
    {    
         var order_rate = $('#order_rate').val();
         var value = $(row).val();
         var total_value = ((value * order_rate)/100).toFixed(2);
         $(row).parent().parent('tr').find('.value').val(total_value); 
         CalFabricSewingPacking();
         setTimeout(function() 
         {
            calculateGarmentRejectionValue();
         }, 500);
         GetTotalmakingCost();
         calculateTotalCost();
    }
    
       
    function calculatepercentage(row)
    {  
      var order_rate = $('#order_rate').val(); 
      var per = $(row).val(); 
      var total_per = ((per/order_rate) * 100).toFixed(2);
      $(row).parent().parent('tr').find('.per').val(total_per);  
      CalFabricSewingPacking();
      setTimeout(function() 
      {
        calculateGarmentRejectionValue();
      }, 500);
      GetTotalmakingCost();
      calculateTotalCost();
    }
   
         
    function calculateGarmentRejectionValue()
    {   
      var value = $("#garment_reject_per").val(); 
      var total_making_value = $('#total_making_value').val();
      
      $("#garment_reject_value").val(((total_making_value * value)/100).toFixed(2)); 
      calculateTotalCost();
    }
    
    
    function SalesOrderDisable(type)
    {
          if(type==1)
          {
               document.getElementById('sales_order_no').disabled=false;
          }
          else
          {
                document.getElementById('sales_order_no').disabled=true;
          }
    }


   
   function getSalesOrderDetails(sales_order_no)
   {
         $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('SalesOrderDetails') }}",
               data:{'sales_order_no':sales_order_no},
               success: function(data){
               
               $("#brand_id").val(data[0]['brand_id']);
               $("#po_code").val(data[0]['po_code']);
               $("#order_type").val(data[0]['order_type']).trigger('change');
               $("#og_id").val(data[0]['og_id']).trigger('change');
               $("#Ac_code").val(data[0]['Ac_code']);
               $("#currency_id").val(data[0]['currency_id']);
               $("#exchange_rate").val(data[0]['exchange_rate']);
               $("#inr_rate").val(data[0]['inr_rate']);
               $("#mainstyle_id").val(data[0]['mainstyle_id']);
               $("#substyle_id").val(data[0]['substyle_id']);
               
               $("#style_no").val(data[0]['style_no']);
               $("#fg_id").val(data[0]['fg_id']);
               $("#sam").val(data[0]['sam']);
               $("#style_description").val(data[0]['style_description']);
               let rate = parseFloat(data[0]['order_rate'] || 0);
               $("#order_rate").val(rate.toFixed(2)); 
               $("#total_qty").val(data[0]['total_qty']);
               $("#order_value").val(data[0]['order_value']);
                document.getElementById('brand_id').disabled=true;
                document.getElementById('Ac_code').disabled=true;
                document.getElementById('currency_id').disabled=true;
                document.getElementById('mainstyle_id').disabled=true;
                document.getElementById('substyle_id').disabled=true;
                document.getElementById('fg_id').disabled=true;
                document.getElementById('style_description').disabled=true;
                document.getElementById('order_rate').disabled=true;
                document.getElementById('style_no').disabled=true;
                document.getElementById('sam').disabled=true;
                
                calculateMfgCost(data[0]['sam']);
                $.ajax({
                   type: "GET",
                   url: "{{ route('GetMainStyleImage') }}",
                   data:{'mainstyle_id':data[0]['mainstyle_id']},
                   success: function(res)
                   {
                        $("#mainstyle_image").attr({ "src": "https://kenerp.com/uploads/MainStyleImages/"+res});
                        $("#imgDiv").removeClass("hide");
                   }
                });
                  
           }
           });
           $("#sales_order_no").attr("disabled", true);
   }
     
    $('table').not('#footable_5').on('input change', 'input', function() {
        calculate_percentage_value(0);
        calculatepercentage(0);
    });
    
   function calculateMfgCost(sam)
   {
        //  var order_rate=$('#order_rate').val();  
        //  var mfg_value=(sam*(3.75)).toFixed(2);
        //  $('#production_value').val(mfg_value);
        //  var production_valuepercentage= ((mfg_value / order_rate) * 100).toFixed(2);
        //  $('#production_per').val(production_valuepercentage);
         
         
   }
   
   
   function EnableFields()
   {
      $("select").removeAttr('disabled');
      $("input").removeAttr('disabled');
   }
   
   
   
    
    $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
       
       mycalc();
   
   });
   
    
   
   $('table.footable_2').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   {
      // alert();
   CalculateQtyRowPro($(this).closest("tr"));
   
   });
   function CalculateQtyRowPro(row)
   {   
        var consumption=+row.find('input[name^="consumption[]"]').val();
        var wastage=+row.find('input[name^="wastage[]"]').val();
        var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
        var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
        row.find('input[name^="bom_qty[]"]').val(bom_qty);
        var total_price=(bom_qty*rate_per_unit).toFixed(4);
        row.find('input[name^="bom_qty[]"]').val(bom_qty);
        row.find('input[name^="total_amount[]"]').val(total_price);
        mycalc();
        GetTotalmakingCost(); 
        calculateTotalCost();
   }
   
    
 
   
    
   
     
   $('table.footable_3').on('keyup', 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"]', function()
   {CalculateQtyRowPros($(this).closest("tr"));});
   function CalculateQtyRowPros(row)
   {   
        var consumption=+row.find('input[name^="consumptions[]"]').val();
        var wastage=+row.find('input[name^="wastages[]"]').val();
        var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
        var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
        row.find('input[name^="bom_qtys[]"]').val(bom_qty);
        var total_price=(bom_qty*rate_per_unit).toFixed(4);
        row.find('input[name^="bom_qtys[]"]').val(bom_qty);
        
        row.find('input[name^="total_amounts[]"]').val(total_price);
        mycalc();
        GetTotalmakingCost(); 
        calculateTotalCost();
   }
   

    
     
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
        var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
         
        row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
        var total_price=(bom_qty*rate_per_unit).toFixed(4);
        row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
        
        row.find('input[name^="total_amountss[]"]').val(total_price);
        mycalc();
        GetTotalmakingCost(); 
        calculateTotalCost();
   }
   
    function selselect1()
    {
        setTimeout(function(){
        $("#footable_2 tr td  select[name='class_id[]']").each(function() {
             $(this).closest("tr").find('select[name="class_id[]"]').select2();
            
            });
        }, 1000);
    }
    
   var indexcone = 2;
   function insertcone1(){
   $("#class_id").select2("destroy");
   var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.readOnly = true; // ✅ correct property name
   
   t1.value=indexcone;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_id"),
   y = x.clone();
   y.attr("id","class_id");
   y.attr("name","class_id[]");
   y.width(200);
   y.appendTo(cell3);
      
     
   var cell5 = row.insertCell(2);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "description"+indexcone;
   t5.name="description[]";
   cell5.appendChild(t5); 
    
    
    
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumption"+indexcone;
   t5.name="consumption[]";
   t5.value="0";
   cell5.appendChild(t5);  
    
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_unit"+indexcone;
   t5.name="rate_per_unit[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(5);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastage"+indexcone;
   t5.name="wastage[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "bom_qty"+indexcone;
   t5.name="bom_qty[]";
   t5.value="0";
   cell5.appendChild(t5);
    
    
    
    
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="FABRIC";
   t5.readOnly=true;
   t5.id = "total_amount"+indexcone;
   t5.name="total_amount[]";
   t5.value="0";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(8);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone1()");
   cell6.appendChild(btnAdd);
   
   
   var cell7=row.insertCell(9);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
   cell7.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_2').find('tr').eq(indexcone);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
   
   indexcone++;
   recalcIdcone1();
   
   selselect1();
   
   }
   
   
   
    function selselect2()
    {
        setTimeout(function(){
        $("#footable_3 tr td  select[name='class_ids[]']").each(function() {
             $(this).closest("tr").find('select[name="class_ids[]"]').select2();
            
            });
        }, 1000);
    }
   
   // Start Sewing Trims----------------------------
   var indexcone1 = 2;
   function insertcone2(){
   $("#class_ids").select2("destroy");
   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "ids"+indexcone1;
   t1.name= "ids[]";
   t1.readOnly = true; // ✅ correct property name
   
   t1.value=indexcone1;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_ids"),
   y = x.clone();
   y.attr("id","class_ids");
   y.attr("name","class_ids[]");
   y.width(200);
   y.appendTo(cell3);
     
      
   var cell5 = row.insertCell(2);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptions"+indexcone1;
   t5.name="descriptions[]";
   cell5.appendChild(t5); 
     
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumptions"+indexcone1;
   t5.name="consumptions[]";
   t5.value="0";
   cell5.appendChild(t5);  
    
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_units"+indexcone1;
   t5.name="rate_per_units[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(5);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastages"+indexcone1;
   t5.name="wastages[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "bom_qtys"+indexcone1;
   t5.name="bom_qtys[]";
   t5.value="0";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="SEWING";
   t5.readOnly=true;
   t5.id = "total_amounts"+indexcone1;
   t5.name="total_amounts[]";
   t5.value="0";
   cell5.appendChild(t5); 
   
    
    
   var cell6=row.insertCell(8);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone2()");
   cell6.appendChild(btnAdd);
   
   
    
   var cell7=row.insertCell(9);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
   cell7.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_3').find('tr').eq(indexcone1);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;
   
   indexcone1++;
   recalcIdcone2();
   selselect2();
   }
   
   
   
    function selselect3()
    {
        setTimeout(function(){
        $("#footable_4 tr td  select[name='class_idss[]']").each(function() {
             $(this).closest("tr").find('select[name="class_idss[]"]').select2();
            
            });
        }, 1000);
    }
   
   // Start Packing Trims----------------------------
   var indexcone2 = 2;
   function insertcone3(){
   $("#class_idss").select2("destroy");
   var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "idss"+indexcone2;
   t1.name= "idss[]";
   t1.value=indexcone2;
   t1.readOnly = true; // ✅ correct property name
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_idss"),
   y = x.clone();
   y.attr("id","class_idss");
   y.attr("name","class_idss[]");
   y.width(200);
   y.appendTo(cell3);
     
      
   var cell5 = row.insertCell(2);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptionss"+indexcone2;
   t5.name="descriptionss[]";
   cell5.appendChild(t5); 
     
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumptionss"+indexcone2;
   t5.name="consumptionss[]";
   t5.value="0";
   cell5.appendChild(t5);  
    
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_unitss"+indexcone2;
   t5.name="rate_per_unitss[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(5);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastagess"+indexcone2;
   t5.name="wastagess[]";
   t5.value="0";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "bom_qtyss"+indexcone2;
   t5.name="bom_qtyss[]";
   t5.value="0";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="PACKING";
   t5.readOnly=true;
   t5.id = "total_amountss"+indexcone2;
   t5.name="total_amountss[]";
   t5.value="0";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(8);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone3()");
   cell6.appendChild(btnAdd);
    
   var cell7=row.insertCell(9);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
   cell7.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_4').find('tr').eq(indexcone2);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;
   
   indexcone2++;
   recalcIdcone3();
   selselect3();
   }
   
   
   
   
   
   
   
   function mycalc()
   {   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('FABRIC');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("fabric_value").value = sum1.toFixed(2);
   
   sum2 = 0.0;
   var amounts = document.getElementsByClassName('SEWING');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum2 += parseFloat(a);
   }
   document.getElementById("sewing_trims_value").value = sum2.toFixed(2);
   
   sum3 = 0.0;
   var amounts = document.getElementsByClassName('PACKING');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum3 += parseFloat(a);
   }
   document.getElementById("packing_trims_value").value = sum3.toFixed(2);
    
     var order_rate=$('#order_rate').val();  
    
    
   var fabricpercentage= ((sum1.toFixed(2) / order_rate) * 100).toFixed(2);
   var sewing_trimspercentage= ((sum2.toFixed(2) / order_rate) * 100).toFixed(2);
   var packing_trimspercentage= ((sum3.toFixed(2) / order_rate) * 100).toFixed(2);
        
        $('#fabric_per').val(fabricpercentage);
        $('#sewing_trims_per').val(sewing_trimspercentage);
        $('#packing_trims_per').val(packing_trimspercentage);
     
   
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
    
        
        CalFabricSewingPacking();
        setTimeout(function() {
            calculate_percentage_value(0);
            calculatepercentage(0);
            GetTotalmakingCost();
            calculateTotalCost();
        }, 500);
   }
   }
   
   
   function deleteRowcone2(btn)
   {
       if(document.getElementById('cntrr2').value > 1){
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       
       document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;
       
       recalcIdcone2();
       
       if($("#cntrr2").val()<=0)
       {		
       document.getElementById('Submit').disabled=true;
       }
        
        CalFabricSewingPacking();
        setTimeout(function() {
            calculate_percentage_value(0);
            calculatepercentage(0);
            GetTotalmakingCost();
            calculateTotalCost();
        }, 500);
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
        
        
        CalFabricSewingPacking();
        setTimeout(function() {
            calculate_percentage_value(0);
            calculatepercentage(0);
            GetTotalmakingCost();
            calculateTotalCost();
        }, 500);
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