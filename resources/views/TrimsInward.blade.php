@extends('layouts.master') 
@section('content')
@php  
ini_set('memory_limit', '1G');
@endphp
<style>
   .hide
   {
   display:none!important;
   }
   .navbar-brand-box
   {
   width: 266px !important;
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

   /* td,th
   {
      text-align: center;
   } */
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims Inward</li>
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
         <!-- TAB HEADER -->
         <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab">
               Delivery
               </button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button" role="tab">
               Return
               </button>
            </li>
         </ul>
         <!-- TAB CONTENT -->
         <div class="tab-content mt-4" id="myTabContent">
            <!-- ===================================================
               DELIVERY TAB — FULL ORIGINAL FORM
               ==================================================== -->
            <div class="tab-pane fade show active" id="delivery" role="tabpanel">
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
               <form action="{{route('TrimsInward.store')}}" method="POST" id="frmData" novalidate>
                  <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'TrimsInward' ?>" /> 
                  <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="1">
                  @csrf 
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">GRN Date</label>
                           <input type="date" name="trimDate" class="form-control" id="formrow-email-input" value="{{date('Y-m-d')}}" required>
                           <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">Invoice No</label>
                           <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="formrow-email-input" value=""  >
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
                           <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{date('Y-m-d')}}">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="po_code" class="form-label">PO NO</label>   
                           <select name="po_code" class="form-select select2" id="po_code" onchange="getPODetails();FetchPoData();GetPurchaseBillDetails();">
                              <option value="">PO code</option>
                              @foreach($POList as  $rowpol)
                              {
                              <option value="{{ $rowpol->pur_code  }}">{{ $rowpol->pur_code }}</option>
                              }
                              @endforeach
                           </select>
                           <input type="hidden" name="po_codenew" id="po_codenew" class="form-control"  value="{{ request()->po_code  }}">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">PO Type</label>
                           <select name="po_type_id" class="form-select" id="po_type_id" disabled>
                              <option value="">Type</option>
                              @foreach($POTypeList as  $rowpo)
                              {
                              <option value="{{ $rowpo->po_type_id  }}">{{ $rowpo->po_type_name }}</option>
                              }
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">Supplier</label>
                           <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="GetPartyDetailsSale();"  disabled>
                              <option value="">--- Select Supplier ---</option>
                              @foreach($ledgerlist as  $rowledger)
                              {
                              <option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>
                              }
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
                     <div class="col-md-2">
                        <div class="form-check form-check-primary mb-5">
                           <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening" style="font-size: 25px;margin-top: 30px;margin-left: 0px;" onchange="DisabledPO(this);" >
                           <label class="form-check-label" for="is_opening" style="margin-top: 30px;position: absolute;margin-left: 20px;font-size: 16px;">
                           Opening Stock
                           </label>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <label for="tge_code" class="form-label">Trim Gate Code</label>
                        <select name="tge_code" class="form-select select2" id="tge_code" required>
                           <option value="">--Select--</option>
                           @foreach($TGEList as  $row) 
                           <option value="{{ $row->tge_code }}">{{ $row->tge_code }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label for="location_id" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2  " id="location_id" required>
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row) 
                           <option value="{{ $row->loc_id }}" {{ $row->loc_id == 4 ? 'selected="selected"' : '' }}>{{ $row->location }}</option> 
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-1 mt-4">
                        <button type="button" name="allocate[]"  onclick="stockAllocate();" id="mainAllocation" isClick="0" class="btn btn-warning pull-center">Allocate</button> 
                     </div>
                  </div>
                  <div class="table-wrap" id="trimInward">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Code</th>
                                 <th>Classification</th>
                                 <th>Item Name</th>
                                 <th>UOM</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Amount</th>
                                 <th>Rack Location</th>
                                 <th class="text-center">Add</th>
                                 <th class="text-center">Delete</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr item_code="" isClick = "0" qty="" bom_code="" cat_id="" class_id="">
                                 <td><input type="text" name="id" value="1" id="id"  style="width:50px;" readonly/></td>
                                 <td><input type="text" name="itemsCode[]" value="" id="itemsCode"  style="width:100px;" readonly/></td>
                                 <td>
                                    <select name="class_id[]" id="class_id" style="width:252px;height:30px;" disabled>
                                       <option value="">--- Select Class---</option>
                                       @foreach($classList as  $rowclass)
                                       <option value="{{ $rowclass->class_id}}">{{ $rowclass->class_name}} </option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="item_codes[]" class="select2" id="item_codes" style="width:300px;height:30px;" onchange="GetUnit(this);CheckDuplicateRow(this);" >
                                       <option value="">--- Select Item ---</option>
                                       @foreach($itemlist as  $rowitem)
                                       <option value="{{ $rowitem->item_code}}">{{ $rowitem->item_name }}-({{ $rowitem->item_code}}) </option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="unit_ids[]" class=" " id="unit_ids" style="width:150px;height:30px;">
                                       <option value="">--- Select Unit ---</option>
                                       @foreach($unitlist as  $rowunit)
                                       {
                                       <option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="number" step="any" class="QTY"   name="item_qtys[]"  onchange="SetQtyToBtn(this);" value="0" id="item_qty" style="width:80px;height:30px;" required/>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" required/>
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="0" id="amounts" style="width:80px;height:30px;" required/>
                                    <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;" required/>
                                 </td>
                                 <td>
                                    <select name="rack_id[]" class="select2"  id="rack_id" style="width:100px;height:30px;" required>
                                       <option value="">--Racks--</option>
                                       @foreach($RackList as  $row)
                                       {
                                       <option value="{{ $row->rack_id }}"
                                          >{{ $row->rack_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td class="text-center"><button type="button" onclick=" mycalc(); " class="btn btn-warning pull-center Abutton">+</button></td>
                                 <td class="text-center"><input type="button" class="btn btn-danger pull-center" onclick="deleteRow(this);" value="X" ></td>
                              </tr>
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Code</th>
                                 <th>Classification</th>
                                 <th>Item Name</th>
                                 <th>UOM</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Amount</th>
                                 <th>Rack Location</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </tfoot>
                           <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                        </table>
                     </div>
                  </div>
                  <br/>
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                           <thead>
                              <tr>
                                 <th>BOM Code</th>
                                 <th>Sales Order No</th>
                                 <th>Item Code</th>
                                 <th>Item Name</th>
                                 <th>Allocated Stock</th>
                              </tr>
                           </thead>
                           <tbody id="stock_allocate"></tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="totalqty" class="form-label">Total Quantity</label>
                           <input type="text" name="totalqty" class="form-control" id="totalqty" readonly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_amount" class="form-label">Total Amount</label>
                           <input type="text" name="total_amount" class="form-control" id="total_amount" readonly>
                           <input type="hidden" class="form-control" id="total_allocate_qty" value="0">
                        </div>
                     </div> 
                  </div>
                  <div>
                     <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
                     <a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
                  </div>
               </form>
            </div>
            <div class="tab-pane fade" id="return" role="tabpanel">
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
               <form action="{{route('TrimsInward.store')}}" method="POST" id="frmData1" novalidate>
                  <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'TrimsInward' ?>" /> 
                  <input type="hidden" name="trimDate" class="form-control" id="trimDate" value="{{date('Y-m-d')}}">
                  <input type="hidden" name="cp_id" class="form-control" id="cp_id1" value="1">
                  <input type="hidden" name="Ac_code" class="form-control" id="Ac_code1" value="">
                  <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="2">
                  @csrf 
                  <div class="row">
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isReturnTrimsInward" onchange="GetDCDropdown();" name="isReturnTrimsInward"  style="font-size: 30px;margin-left: 0px;margin-top: -3px;">
                              <label class="form-check-label" for="isReturnTrimsInward" style="position: absolute;margin-left: 20px;font-size: 14px;">
                              Trims Return From Inhouse
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isOutsideVendor" name="isOutsideVendor" onchange="DisableDropdown();" style="font-size: 30px;margin-left: 0px;margin-top: -3px;"/>
                              <label class="form-check-label" for="isOutsideVendor" style="position: absolute;margin-left: 20px;font-size: 14px;">
                              From Outsource Vendor
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="invoice_date" class="form-label">DC Date</label>
                           <input type="date" name="invoice_date" class="form-control" id="invoice_date" value="{{date('Y-m-d')}}" required>
                           <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">DC No</label>
                           <input type="text" name="invoice_no" id="invoice_no1" class="form-control" value=""  > 
                           <select name="invoice_no" class="form-select select2 hide" id="tocd_code" onchange="GetTrimsCuttingDeptData();">
                              <option value="">--Select--</option>
                              @foreach($TrimsCuttingOutwardList as  $row)
                              <option value="{{ $row->tocd_code }}">{{ $row->tocd_code }}({{ $row->dc_no }})</option> 
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <label for="tge_code" class="form-label">Trim Gate Code</label>
                        <select name="tge_code" class="form-select select2" id="tge_code" required>
                           <option value="">--Select--</option>
                           @foreach($TGEList as  $row) 
                           <option value="{{ $row->tge_code }}">{{ $row->tge_code }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label for="location_id" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2  " id="location_id">
                           <option value="">--Location--</option>
                           @foreach($LocationList as  $row) 
                           <option value="{{ $row->loc_id }}" {{ $row->loc_id == 4 ? 'selected="selected"' : '' }}>{{ $row->location }}</option> 
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-2" id="workOrder">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Work Order No.</label>   
                           <select name="vw_code" class="form-select select2" id="vw_code" onchange="GetVendorName(this.value);" >
                              <option value="">--Select--</option>
                              @foreach($vendorWorkOrderList as  $vendors)
                              <option value="{{ $vendors->code  }}">{{ $vendors->code }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3" id="vendorData">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Name</label>   
                           <select name="vendorId" class="form-select select2" id="vendorId" >
                              <option value="">--Select--</option>
                              @foreach($vendorData as  $rows)
                              <option value="{{ $rows->ac_code }}"  > {{ $rows->ac_short_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-1 mt-4">
                        <button type="button" name="allocate[]"  onclick="stockAllocate1();" id="mainAllocation1" isClick="0" class="btn btn-warning pull-center">Allocate</button> 
                     </div>
                  </div>
               
                  <!-- PURCHASE TABLE -->
                  <div class="table-wrap hide" id="OutwardTbl">
                     <div class="table-responsive">
                        <table id="footable_4" class="table table-bordered table-striped m-b-0">
                           <thead>
                              <tr class="text-center">
                                 <th>Sr No.</th>
                                 <th>Item Code</th>
                                 <th>Item Name</th>
                                 <th>Outward Qty</th> 
                                 <th>Received</th> 
                                 <th>Balance Qty</th> 
                              </tr>
                           </thead>
                           <tbody id="OutwardTbody">
                           </tbody>
                        </table>
                     </div>
                  </div>

                  <div class="table-wrap" id="trimInward1">
                     <div class="table-responsive">
                        <table id="footable_21" class="table  table-bordered table-striped m-b-0 footable_21">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
                                 <th>Item Code</th>
                                 <th>Classification</th>
                                 <th>Item Name</th>
                                 <th>UOM</th>
                                 <th>PO Code</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Amount</th>
                                 <th>Rack Location</th>
                                 <th class="text-center">Add</th>
                                 <th class="text-center">Delete</th>
                              </tr>
                           </thead>
                           <tbody id="detailTbl">
                              <tr item_code="" isClick = "0" qty="" bom_code="" cat_id="" class_id="">
                                 <td><input type="text" name="id" value="1" id="id"  style="width:50px;" readonly/></td>
                                 <td><input type="text" name="itemsCode[]" value="" id="itemsCode1"  style="width:100px;" readonly/></td>
                                 <td>
                                    <select name="class_id[]" id="class_id" style="width:252px;height:30px;" disabled>
                                       <option value="">--- Select Class---</option>
                                       @foreach($classList as  $rowclass)
                                       <option value="{{ $rowclass->class_id}}">{{ $rowclass->class_name}} </option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="item_codes[]" class="select2" id="item_codes" style="width:300px;height:30px;" onchange="GetUnit(this);CheckDuplicateRow(this);" >
                                       <option value="">--- Select Item ---</option>
                                       @foreach($itemlist as  $rowitem)
                                       <option value="{{ $rowitem->item_code}}">{{ $rowitem->item_name }}-({{ $rowitem->item_code}}) </option>
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="unit_ids[]" class=" " id="unit_ids" style="width:150px;height:30px;">
                                       <option value="">--- Select Unit ---</option>
                                       @foreach($unitlist as  $rowunit)
                                       {
                                       <option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="text" value="-" id="po_codes" style="width:120px;height:30px;" readonly />
                                 <td><input type="number" step="any" class="QTY"   name="item_qtys[]"  onchange="SetQtyToBtn(this);" value="0" id="item_qty" style="width:80px;height:30px;" required/>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" required/>
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="0" id="amounts" style="width:80px;height:30px;" required/>
                                    <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;" required/>
                                 </td>
                                 <td>
                                    <select name="rack_id[]" class="select2"  id="rack_id" style="width:100px;height:30px;" required>
                                       <option value="">--Racks--</option>
                                       @foreach($RackList as  $row)
                                       {
                                       <option value="{{ $row->rack_id }}"
                                          >{{ $row->rack_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td class="text-center"><button type="button" onclick=" mycalc(); " class="btn btn-warning pull-center Abutton1">+</button></td>
                                 <td class="text-center"><input type="button" class="btn btn-danger pull-center" onclick="deleteRow(this);" value="X" ></td>
                              </tr>
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Item Code</th>
                                 <th>Classification</th>
                                 <th>Item Name</th>
                                 <th>UOM</th>
                                 <th>PO Code</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Amount</th>
                                 <th>Rack Location</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </tfoot>
                           <input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
                        </table>
                     </div>
                  </div>
                  <br/>
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_211" class="table  table-bordered table-striped m-b-0 footable_211">
                           <thead>
                              <tr>
                                 <th>BOM Code</th>
                                 <th>Sales Order No</th>
                                 <th>Item Code</th>
                                 <th>Item Name</th>
                                 <th>Allocated Stock</th>
                              </tr>
                           </thead>
                           <tbody id="stock_allocate1"></tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="totalqty" class="form-label">Total Quantity</label>
                           <input type="number" step="any" name="totalqty" class="form-control" id="totalqty1" readonly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_amount" class="form-label">Total Amount</label>
                           <input type="number" step="any" name="total_amount" class="form-control" id="total_amount1" readonly>
                           <input type="hidden" class="form-control" id="total_allocate_qty1" value="0" readonly>
                        </div>
                     </div>
                  </div>
                  <div>
                     <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit1">Save</button>
                     <a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
                  </div>
               </form>
            </div>
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
<div class="modal fade" id="modalFormSize" role="dialog">
<div class="modal-dialog" style="margin: 1.75rem 19rem;">
   <div class="modal-content" style="width: 900px;">
      <!-- Modal Body -->
      <div class="modal-body">
         <p class="statusMsg"></p>
         <div class="seprator-block"></div>
         <h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>Trims PO Vs GRN</h6>
         <hr class="light-grey-hr"/>
         <div class="row">
            <div id="TrimsInwardData"></div>
         </div>
         <!-- Modal Footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();">Close</button>
         </div>
      </div>
   </div>
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

   let duplicateChecking = false;   // global flag

   function CheckDuplicateRow(row)
   {
      $(row).closest('tr').find('select[name="class_id[]"]').prop('disabled', true);
      $(row).closest('tr').find('input').not('input[name="id"]').not('input[name="id[]"]').not('.btn').val('');
      let selectedVal = $(row).val(); 
      $(row).closest('tr').find('input[name="itemsCode[]"]').val(selectedVal);
      if (duplicateChecking) return;  // stop repeated alerts

      // get all selected item codes except current
      let allSelected = $('select[name="item_codes[]"]').not(row).map(function () {
         return $(this).val(); 
      }).get();

      if (allSelected.includes(selectedVal)) {

         duplicateChecking = true;  // block next alerts

         alert("This Item is already selected in another row!");

         // reset and re-init Select2
         $(row).val(null).trigger('change.select2');
         $(row).select2('destroy');
         $(row).select2({
               placeholder: "Select Item",
               allowClear: true
         });

         // unlock alert **after resetting is done**
         setTimeout(() => duplicateChecking = false, 300);
      }
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
   

    $(document).on("input", 'input[name^="item_qtys[]"]', function (event) 
   {
         var row = $(this).closest("tr");

         var current_item_code = row.find('select[name^="item_codes[]"]').val();
   
         var total = 0;

         if($("#isOutsideVendor").is(":checked"))
         {
            var po_qty = parseFloat($(".item_code_" + current_item_code).find('.bal_qty1').html()) || 0;
            var total = 0;

            // First calculate total item_qtys already entered
            $("#footable_21 > tbody > tr").each(function () {

               var item_code = $(this).find('select[name="item_codes[]"]').val();
               if (current_item_code === item_code) {

                  var meterVal = parseFloat($(this).find('input[name="item_qtys[]"]').val()) || 0;
                  total += meterVal;
               }
            });

            // Now check the latest row where user typed item_qtys
            var currentMeterInput = $(this);
            var entered = parseFloat(currentMeterInput.val()) || 0;

            // Remaining quantity
            var remaining = po_qty - (total - entered);
 
            // Case 1: Total exceeded
            if (total > po_qty) {

               alert("Quantity is exceeding " + po_qty);

               if (remaining <= 0) {
                  currentMeterInput.val(0);
               } else {
                  currentMeterInput.val(remaining);
               }

               return;
            }

            // --- DISABLE NEXT ROWS IF FIRST ROW IS FULL ---
            if (total >= po_qty) {

               // Disable all remaining rows for this item
               $("#footable_3 > tbody > tr").each(function () {

                  var item_code = $(this).find('select[name="item_codes[]"]').val();

                  if (current_item_code === item_code) {

                        var meterInput = $(this).find('input[name="item_qtys[]"]');

                        // If this row is NOT the row with actual value (first filled row),
                        // then force meter = 0 and disable it
                        if (parseFloat(meterInput.val()) === 0) {

                           meterInput.val(0);
                           meterInput.prop("readonly", true);
                        }
                  }
               });
            } 
            else 
            {
               // If remaining qty exists → allow but limit to remaining
               if (entered > remaining) {
                  alert("Only " + remaining + " quantity remaining!");

                  currentMeterInput.val(remaining);
               }
            }

         }
         else
         {
            var po_qty = $(".item_code_" + current_item_code).find('.bal_qty').html();
            $("#footable_2 > tbody > tr").each(function() {

               var item_code = $(this).find('select[name="item_codes[]"]').val();

               if (current_item_code === item_code) {
                  var meter = parseFloat($(this).find('input[name="item_qtys[]"]').val()) || 0;
                  total = total + meter;
               } 
               // 5% allowed quantity
               var allow_qty = parseFloat(po_qty) + (parseFloat(po_qty) * 0.05);
      

               if (parseFloat(total) > parseFloat(allow_qty)) {
                  alert("Quantity is allow only 5%");
               }
            });
         } 

         CalculateRow($(this).closest("tr"));
   });
   
   function GetTrimsCuttingDeptData()
   {
        var tocd_code  = $("#tocd_code").val();
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetTrimsOutwardCuttingDeptData') }}",
          data:{'tocd_code':tocd_code},
          success: function(data)
          {
              $('#detailTbl').html(data.html); 
              $('#vw_code').val(data.masterData.cutting_po_no).trigger('change'); 
              mycalc();
          }
        }); 

   }

    
   function GetTrimsInwardOutwardData()
   {
         $("#isReturnTrimInward").attr('disabled', true);
         $("#isOutsideVendor").attr('disabled', true);
         var vw_code = $("#vw_code").val();
         $.ajax({
            type:"GET",
            url:"{{ route('GetTrimsInwardOutwardData') }}", 
            data:{vw_code:vw_code},
            success:function(response)
            {
               $("#OutwardTbl").removeClass("hide");
               $("#OutwardTbody").html(response.html);            
            }
         });  

         GetVendorName(vw_code);
         $("#vw_code").prop('disabled', true);
         $("#vendorId").prop('disabled', true);
   }


   function DisableDropdown()
   {
        $("#vendorId").attr('disabled', true);
        $('input[type="checkbox"]').attr('disabled', true);
        $("#delivery-tab").prop("disabled", true);
        if($("#isOutsideVendor").is(":checked"))
        {
           $("#isReturnTrimsInward").attr('disabled', true); 
           $("#vw_code").attr('onchange', 'GetTrimsInwardOutwardData()');
           $("#tocd_code").removeAttr("name");
           $("#invoice_no1").prop("required", true);
           $("#tocd_code").prop("required", false);
        }
        else
        {
           $("#invoice_no1").removeAttr("name");
           $("#isReturnTrimsInward").attr('disabled', false);  tocd_code
        }          
   
   } 
 
   
   function GetDCDropdown()
   { 
        $("#vw_code").attr('disabled', true);
        $("#vendorId").attr('disabled', true);
        $('input[type="checkbox"]').attr('disabled', true);
        $("#delivery-tab").prop("disabled", true);
        if($("#isReturnTrimsInward").is(":checked"))
        {
           $("#tocd_code").prop("required", true);
           $("#invoice_no1").prop("required", false);
           $("#isOutsideVendor").attr('disabled', true);
           $("#invoice_no1").removeAttr('name').removeAttr('required').addClass("hide");
           $("#tocd_code").attr('name', 'invoice_no').attr('required', true).removeClass("hide"); 
        }
        else
        {
           $("#isOutsideVendor").attr('disabled', false);
           $("#invoice_no1").attr('name', 'invoice_no').attr('required', true).removeClass("hide");
           $("#tocd_code").removeAttr('name').removeAttr('required').addClass("hide"); 
        }
   }
   
   
   $(document).ready(function() {
     $('#frmData').on('submit', function (e) {
         e.preventDefault();

         let form = $(this);
         let isValid = true;

         // Required validation — skip hidden fields
         form.find('[required]').each(function () {

            // ❌ Skip hidden or type="hidden"
            if ($(this).is(':hidden') || $(this).attr("type") === "hidden") {
                  return; // continue
            }

            // Normal validation
            if (!$(this).val().trim()) {
                  isValid = false;
                  $(this).addClass('is-invalid');
            } else {
                  $(this).removeClass('is-invalid');
            }
         });

         // Check mainAllocation click
         let Click = $("#mainAllocation").data("clicked");

         if (Click !== 1) {
            alert("Quantity is not allocated...!");
            $('#Submit').prop('disabled', true);
            $("#po_code, #is_opening").prop('disabled', true);
            return false;
         }

         // If required fields missing
         if (!isValid) {
            alert("Please fill all required fields!");
            $('#Submit').prop('disabled', false);
            return false;
         }

         // Check table rows
         let hasRows = $("#stock_allocate tr").length > 0;

         if (!hasRows) {
            alert("No allocation rows found!");
            $('#Submit').prop('disabled', true);
            return false;
         }

         // Disable submit & submit form (without recursion)
         $('#Submit').prop('disabled', true);
         form.off('submit').submit();
      });


      // Mark mainAllocation clicked
      $('#mainAllocation').on('click', function () {
         $(this).data("clicked", 1);
         $('#Submit').prop('disabled', false);
      });


      $('#frmData1').on('submit', function (e) {
         e.preventDefault();

         let form = $(this);
         let isValid1 = true;

        form.find('[required]').each(function () 
        {
            // ❌ Skip hidden fields
            if ($(this).is(':hidden') || $(this).attr("type") === "hidden") {
               return; // continue loop
            }

            // Normal validation
            if (!$(this).val().trim()) {
               isValid1 = false;
               $(this).addClass('is-invalid');
            } else {
               $(this).removeClass('is-invalid');
            }
         });

         // Check mainAllocation click
         let Click1 = $("#mainAllocation1").data("clicked");
         if (Click1 !== 1) {
            alert("Quantity is not allocated...!");
            $('#Submit1').prop('disabled', true);
            $("#isReturnTrimsInward, #isOutsideVendor").prop('disabled', true);
            return false;
         }

         // If required fields missing
         if (!isValid1) {
            alert("Please fill all required fields!");
            $('#Submit1').prop('disabled', false);
            return false;
         }

         // Check table rows (excluding header)
         let hasRows = $("#stock_allocate1 tr").length > 0;

         if (!hasRows) {
            alert("No allocation rows found!");
            $('#Submit1').prop('disabled', true);
            return false;
         }

         // Finally disable submit & submit form
         $('#Submit1').prop('disabled', true);

         // 🔥 This submits WITHOUT triggering submit event again
         form.off('submit').submit();
      });


      // Mark mainAllocation clicked
      $('#mainAllocation1').on('click', function () {
         $(this).data("clicked", 1);
         $('#Submit1').prop('disabled', false);
      });

   });
   
   
         
   function GetPurchaseBillDetails()
   {
      var po_code = $("#po_code").val(); 
      
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
    
   function GetVendorName(vw_code)
   {
       $.ajax({
         type: "GET",
         dataType:"json",
         url: "{{ route('GetTrimsVendorName') }}",
         data:{'code':vw_code},
         success: function(data)
         {
              $("#vendorId").val(data.ac_code).trigger('change');
              $('#vendorData').removeClass('hide');
         }
       }); 
       
       $.ajax({
         type: "GET",
         dataType:"json",
         url: "{{ route('GetItemWorkOrderPucharseOrder') }}",
         data:{'vw_code':vw_code},
         success: function(data)
         {
             $('select[name="item_codes[]"]').html(data.html); 
         }
       });
   }
   
   function calculateAllocatedQty()
   {
       var total_allocate_qty = 0;
       $(".allocate_qty").each(function()
       {
           total_allocate_qty += parseFloat($(this).val());
       });
        $("#total_allocate_qty").val(total_allocate_qty);

        
       var total_allocate_qty1 = 0;
       $(".allocate_qty").each(function()
       {
           total_allocate_qty1 += parseFloat($(this).val());
       });
       $("#total_allocate_qty1").val(total_allocate_qty1);
   }

   function SetQtyToBtn(obj)
   { 
      var qty = $(obj).val();
      $(obj).parent().parent('tr').attr("qty", qty);
      $(obj).parent().parent('tr').find('td button[name="allocate[]"]').attr('qty', qty); 
      var allow = $(obj).parent().parent('tr').find('td input[name="item_qtys[]"]').attr("allow");
      var toBeReceived = $(obj).parent().parent('tr').find('td input[name="toBeReceived[]"]').val();
      var current = $(obj).parent().parent('tr').find('td input[name="item_qtys[]"]').attr("current");
       
    
      if(parseFloat(qty) > parseFloat(current))
      {
           alert("Quantity is allow only 5%");
      }
      mycalc();
   }
   
   function stockAllocate(obj) 
   {
      $('#Submit').prop('disabled', false);
      var Click = $("#mainAllocation").attr('isClick');

      // -------------------------
      // 1️⃣ Validate Qty > 0
      // -------------------------
      let isValid = true;

      $("#footable_2 tbody tr").each(function () {
         let qty = $(this).find('input[name="item_qtys[]"]').val();

         if (qty === "" || qty == 0 || qty < 0) {
               isValid = false;
               return false; // break the loop
         }
      });

      if (!isValid) {  
         alert("Quantity should be greater than 0 for all items.");
         return false;   // ❗STOP FUNCTION COMPLETELY
      }
      // -------------------------
      // END VALIDATION
      // -------------------------


      setTimeout(() => {
         $('#footable_2')
               .find('select, button')
               .not('select[name="rack_id[]"]')
               .prop('disabled', true);

         $('#footable_2')
               .find('input')
               .prop('readOnly', true);

      }, 500);



      if (Click == 1) {
         alert('Already stock allocated..!');
         return false;
      }

      // -------------------------
      // 2️⃣ Perform Stock Allocation
      // -------------------------
      $("#footable_2 > tbody").find('tr').each(function () {

         var row1 = $(this).attr('item_code');
         var row2 = $(this).attr('qty');
         var row3 = $(this).attr('bom_code');
         var row4 = $(this).attr('cat_id');
         var row5 = $(this).attr('class_id');

         var is_opening = $('#is_opening').is(":checked") ? 1 : 0;
         var po_type_id = $("#po_type_id").val();

         $.ajax({
               type: "GET",
               dataType: "json",
               url: "{{ route('stockAllocate') }}",
               data: {
                  'bom_code': row3,
                  'item_code': row1,
                  'item_qty': row2,
                  'cat_id': row4,
                  'class_id': row5,
                  'is_opening': is_opening,
                  'po_type_id': po_type_id
               },
               success: function (data) {
                  $("#stock_allocate").append(data.html);
                  $("#mainAllocation").attr('isClick', '1');
                  $("#mainAllocation").removeClass('btn-success').addClass('btn-danger');
                  calculateAllocatedQty();
               }
         });
      });

      calculateAllocatedQty();
      
   }

   
  function stockAllocate1(obj) 
  {
      // Enable submit button
      $('#Submit').prop('disabled', false);

      // ---------- 1️⃣ Prevent double click ----------
      if ($("#mainAllocation1").data("running") === 1) {
         alert("Already processing...!");
         return;
      }
      $("#mainAllocation1").data("running", 1);

      // ---------- 3️⃣ Validate Qty > 0 ----------
      let isValid = true;

      $("#detailTbl tr").each(function () {
         let qty = $(this).find('input[name="item_qtys[]"]').val();

         if (qty === "" || qty == 0 || qty < 0) {
               isValid = false;
               return false; // stop loop
         }
      });

      if (!isValid) {
         alert("Quantity should be greater than 0 for all items.");
         $("#mainAllocation1").data("running", 0);
         return false;  // STOP FUNCTION
      }


      // ---------- 4️⃣ Stop if already allocated ----------
      let Click = $("#mainAllocation1").attr('isClick');

      if (Click == 1) {
         alert("Already stock allocated..!");
         $("#mainAllocation1").data("running", 0);
         return;
      }


      // ---------- 2️⃣ Disable controls except rack select ----------
      setTimeout(() => {
         $('#footable_21')
               .find('input, select, button')
               .not('select[name="rack_id[]"]')
               .prop('disabled', true);
      }, 300);


      // ---------- 5️⃣ Process each row ----------
      $("#footable_21 > tbody > tr").each(function () {

         let row = $(this);

         // Stop duplicate processing
         if (row.data("processed") === 1) {
               return;
         }
         row.data("processed", 1);

         // Collect attributes
         var row1 = row.attr('item_code');
         var row2 = row.attr('qty');
         var row3 = row.attr('bom_code');
         var row4 = row.attr('cat_id');
         var row5 = row.attr('class_id');
         var po_type_id = 2;

         $.ajax({
               type: "GET",
               dataType: "json",
               url: "{{ route('stockAllocate') }}",
               data: {
                  bom_code: row3,
                  item_code: row1,
                  item_qty: row2,
                  cat_id: row4,
                  class_id: row5,
                  po_type_id: po_type_id
               },
               success: function (data) {

                  $("#stock_allocate1").append(data.html);

                  // Update button status
                  $("#mainAllocation1")
                     .attr('isClick', '1')
                     .removeClass('btn-success')
                     .addClass('btn-danger');

                  // Run calculation once after batch
                  clearTimeout(window.calcTimer1);
                  window.calcTimer1 = setTimeout(() => {
                     calculateAllocatedQty();
                     $("#mainAllocation1").data("running", 0);
                  }, 200);
               }
         });

      });

   }



   $(document).on("click", '.Abutton', function (event) {
      insertRow($(this).closest("tr"));
       
   });
   
   $(document).on("click", '.Abutton1', function (event) {
      insertRow1($(this).closest("tr"));
       
   });
   
   
   
   var index = 1;
   function insertRow(Abutton)
   {
         var rowsx=$(Abutton).closest("tr");
         $("#item_codes").select2("destroy");
         $("#rack_id").select2("destroy");
         var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
         var row=table.insertRow(table.rows.length);
         
         var cell1=row.insertCell(0);
         var t1=document.createElement("input");
         t1.style="display: table-cell; width:50px; height:30px;";
         //t1.className="form-control col-sm-1";
         t1.readOnly="true";
         
         t1.id = "id"+index;
         t1.name= "id[]";
         t1.value=index;
         cell1.appendChild(t1);

         
         var cell5 = row.insertCell(1);

         var t3 = document.createElement("input");
         t3.style = "display: table-cell; width:100px; height:30px;";
         t3.type = "text";
         t3.id = index;
         t3.name = "itemsCode[]";
         t3.readOnly = true;

         cell5.appendChild(t3);

         var cell5 = row.insertCell(2);
         var t5=document.createElement("select");
         var x = $("#class_id"),
         y = x.clone();
         y.attr("id","class_id");
         y.attr("name","class_id[]");
         y.width(252);
         y.height(30);
         y.appendTo(cell5);

         var cell5 = row.insertCell(3);
         var t5=document.createElement("select");
         var x = $("#item_codes"),
         y = x.clone();
         y.attr("id","item_codes");
         y.attr("name","item_codes[]");
         y.width(300);
         y.height(30);
         y.appendTo(cell5);
         
         var cell2 = row.insertCell(4);
         var t2=document.createElement("select");
         var x = $("#unit_ids"),
         y = x.clone();
         y.attr("id","unit_ids");
         y.attr("name","unit_ids[]");
         y.width(150);
         y.height(30);
         var unit_id=+rowsx.find('select[name^="unit_ids[]"]').val();
         y.val(unit_id);
         y.attr("selected","selected"); 
         
         y.appendTo(cell2);
         
         
         var cell3 = row.insertCell(5);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.step="any";
         t3.required="true";
         t3.className="QTY";
         t3.id = "item_qtys"+index;
         t3.name="item_qtys[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="hidden";
         t3.id = "hsn_codes"+index;
         t3.name="hsn_codes[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         
         var cell3 = row.insertCell(6);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.step="any";
         t3.required="true";
         t3.id = "item_rates"+index;
         t3.name="item_rates[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         var cell3 = row.insertCell(7);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.readOnly="true";
         t3.step="any";
         t3.className="AMT";
         t3.required="true";
         t3.id = "amounts"+index;
         t3.name="amounts[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         var cell2 = row.insertCell(8);
         var t2=document.createElement("select");
         var x = $("#rack_id"),
         y = x.clone();
         y.attr("id","rack_id");
         y.attr("name","rack_id[]");
         y.width(100);
         y.height(30);
         var unit_id=+rowsx.find('select[name^="rack_id[]"]').val();
         y.val(unit_id);
         y.attr("selected","selected"); 
         y.appendTo(cell2);
         
         
         var cell15=row.insertCell(9);
         var btnAdd = document.createElement("INPUT");
         btnAdd.id = "Abutton";
         btnAdd.type = "button";
         btnAdd.className="btn btn-warning text-center Abutton";
         btnAdd.value = "+";
         btnAdd.setAttribute("onclick", "  mycalc();");
         cell15.appendChild(btnAdd);
         
         var cell16=row.insertCell(10);
         var btnRemove = document.createElement("INPUT");
         btnRemove.style="margin-left: 10px;";
         btnRemove.id = "Dbutton";
         btnRemove.type = "button";
         btnRemove.className="btn btn-danger text-center";
         btnRemove.value = "X";
         btnRemove.setAttribute("onclick", "deleteRow(this)");
         cell16.appendChild(btnRemove);
         
         // var w = $(window);
         // var row = $('#footable_2').find('tr').eq( index );
         
         // if (row.length){
         // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
         // }
         
         document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
         
         index++;
         recalcId();
         mycalc();
         selselect();
   
   }
    
   
   var index = 1;
   function insertRow1(Abutton)
   {
         var rowsx=$(Abutton).closest("tr");
         $("#item_codes").select2("destroy");
         $("#rack_id").select2("destroy");
         var table=document.getElementById("footable_21").getElementsByTagName('tbody')[0];
         var row=table.insertRow(table.rows.length);
         
         var cell1=row.insertCell(0);
         var t1=document.createElement("input");
         t1.style="display: table-cell; width:50px; height:30px;";
         //t1.className="form-control col-sm-1";
         t1.readOnly = true;
         t1.id = "id"+index;
         t1.name= "id[]";
         t1.value=index;
         cell1.appendChild(t1);
         
         var cell5 = row.insertCell(1);

         var t3 = document.createElement("input");
         t3.style = "display: table-cell; width:100px; height:30px;";
         t3.type = "text";
         t3.id = index;
         t3.name = "itemsCode[]";
         t3.readOnly = true;

         cell5.appendChild(t3);

         var cell5 = row.insertCell(2);
         var t5=document.createElement("select");
         var x = $("#class_id"),
         y = x.clone();
         y.attr("id","class_id");
         y.attr("name","class_id[]");
         y.width(252);
         y.height(30);
         y.appendTo(cell5);

         var cell5 = row.insertCell(3);
         var t5=document.createElement("select");
         var x = $("#item_codes"),
         y = x.clone();
         y.attr("id","item_codes");
         y.attr("name","item_codes[]");
         y.width(300);
         y.height(30);
         y.appendTo(cell5);
         
         var cell2 = row.insertCell(4);
         var t2=document.createElement("select");
         var x = $("#unit_ids"),
         y = x.clone();
         y.attr("id","unit_ids");
         y.attr("name","unit_ids[]");
         y.width(150);
         y.height(30);
         var unit_id=+rowsx.find('select[name^="unit_ids[]"]').val();
         y.val(unit_id);
         y.attr("selected","selected"); 
         
         y.appendTo(cell2);
         
         
         var cell3 = row.insertCell(5);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:120px;height:30px;";
         t3.type="text"; 
         t3.readOnly="true";
         t3.id = "po_codes"+index; 
         t3.value="-";
         cell3.appendChild(t3);

         var cell3 = row.insertCell(6);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.step="any";
         t3.required="true";
         t3.className="QTY";
         t3.id = "item_qtys"+index;
         t3.name="item_qtys[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="hidden";
         t3.id = "hsn_codes"+index;
         t3.name="hsn_codes[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         
         var cell3 = row.insertCell(7);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.step="any";
         t3.required="true";
         t3.id = "item_rates"+index;
         t3.name="item_rates[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         var cell3 = row.insertCell(8);
         var t3=document.createElement("input");
         t3.style="display: table-cell; width:80px;height:30px;";
         t3.type="number";
         t3.readOnly="true";
         t3.step="any";
         t3.className="AMT";
         t3.required="true";
         t3.id = "amounts"+index;
         t3.name="amounts[]";
         t3.value="0";
         cell3.appendChild(t3);
         
         var cell2 = row.insertCell(9);
         var t2=document.createElement("select");
         var x = $("#rack_id"),
         y = x.clone();
         y.attr("id","rack_id");
         y.attr("name","rack_id[]");
         y.width(100);
         y.height(30);
         var unit_id=+rowsx.find('select[name^="rack_id[]"]').val();
         y.val(unit_id);
         y.attr("selected","selected"); 
         y.appendTo(cell2);
         
         
         var cell15=row.insertCell(10);
         var btnAdd = document.createElement("INPUT");
         btnAdd.id = "Abutton1";
         btnAdd.type = "button";
         btnAdd.className="btn btn-warning pull-text Abutton1";
         btnAdd.value = "+";
         btnAdd.setAttribute("onclick", "mycalc();");
         cell15.appendChild(btnAdd);
         
         var cell16=row.insertCell(11);
         var btnRemove = document.createElement("INPUT");
         btnRemove.style="margin-left: 10px;";
         btnRemove.id = "Dbutton1";
         btnRemove.type = "button";
         btnRemove.className="btn btn-danger pull-text";
         btnRemove.value = "X";
         btnRemove.setAttribute("onclick", "deleteRow(this)");
         cell16.appendChild(btnRemove);
         
         // var w = $(window);
         // var row = $('#footable_21').find('tr').eq( index );
         
         // if (row.length){
         // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
         // }
         
         document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
         
         index++;
         recalcId();
         mycalc();
         selselect();
   
   }
    
   function GetUnit(row)
   { 
      var tax_type_ids=1;
      var item_code = $(row).val();
      var row = $(row).closest('tr');
      
      $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GSTPER') }}",
          data:{item_code:item_code,tax_type_id:tax_type_ids},
          success: function(data)
          { 
               $(row).attr('item_code', item_code).attr('cat_id', data.data[0].cat_id).attr('class_id', data.data[0].class_id); 
               $(row).find('input[name^="hsn_code[]"]').val(data.data[0].unit_id);
               $(row).find('select[name^="class_id[]"]').val(data.data[0].class_id);
               $(row).find('select[name^="unit_ids[]"]').val(data.data[0].unit_id).change().attr('disabled', true);
           }
       });
   }
   
   
   function selselect()
   {
      setTimeout(
      function() 
      {
      
      $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
      
      $(this).closest("tr").find('select[name="item_codes[]"]').select2();
      $(this).closest("tr").find('select[name="rack_id[]"]').select2();
      
      
      });

      $("#footable_21 tr td  select[name='item_codes[]']").each(function() {
      
      $(this).closest("tr").find('select[name="item_codes[]"]').select2();
      $(this).closest("tr").find('select[name="rack_id[]"]').select2();
      
      
      });

      }, 1000);
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
   
   function recalcId()
   {
      $.each($("#footable_2 tr"),function (i,el)
      {
         $(this).find("td:first input").val(i); 
      })
   }
   
   
   function DisabledPO(el)
   {
      if($(el).is(":checked"))
      {
         
         $("#return-tab").prop("disabled", true);

         $("#is_opening").attr("disabled", true);
         $("#po_code").attr("disabled", true);
         $("#po_type_id").val(2).attr("disabled", true);
         $("#Ac_code").val(50).trigger('change').attr("disabled", true);
         // $("#isReturnTrimsInward").prop('checked', false).attr("disabled", true);
         setTimeout(function() {
               $("#bill_to").val(1083).trigger('change');
         }, 1000);

         $("#tge_code").prop("required", false);
         $("#footable_2 tbody tr").find("td input[name='item_rates[]']").prop('readonly', false);   // most reliable
      }
      else
      {
         $("#po_code").val("").trigger('change').attr("disabled", false);
         $("#po_type_id").val(2).attr("disabled", false);
         $("#Ac_code").val(50).trigger('change').attr("disabled", true);

         setTimeout(function() {
               $("#bill_to").val(1083).trigger('change');
         }, 1000);
 
         $("#tge_code").attr("disabled", false).removeAttr("required");
      }
      $("#bill_to").attr("disabled", true);
      $("#tge_code").prop("required", false);
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
        
   }
   
   function getPODetails()
   {
      $("#return-tab").prop("disabled", true);
      $("#is_opening").attr("disabled", true);
      document.getElementById('Ac_code').disabled =true;
      document.getElementById('po_type_id').disabled=true;
      var po_code= $("#po_code").val();
      //console.log(po_code);
      $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('getTrimsPODetails') }}",
          data:{'po_code':po_code},
          success: function(data){
              
              $("#po_type_id").val(data[0]['po_type_id']);
              $("#Ac_code").val(data[0]['Ac_code']);
           //   $('#Ac_code').val(data[0]["Ac_code"]);
            //  $('#select2-chosen-1').html(qhtml);   
      }
      });
      $("#po_code").attr('disabled', true);
   }
   
  function mycalc() 
  { 
      function safeVal(selector) {
         let el = $(selector);
         if (el.length > 0) return el;      // element exists
         return null;                       // does not exist
      }

      // SUM QTY
      let sumQty = 0;
      $("#footable_2 .QTY").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumQty += v;
      });

      let totalqty = safeVal("#totalqty");
      if (totalqty) totalqty.val(sumQty.toFixed(2));

      // SUM AMOUNT
      let sumAmt = 0;
      $("#footable_2 .AMT").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumAmt += v;
      });

      let totalAmt = safeVal("#total_amount");
      if (totalAmt) totalAmt.val(sumAmt.toFixed(2));

       // SUM QTY
      let sumQty1 = 0;
      $("#footable_21 .QTY").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumQty1 += v;
      });

      let totalqty1 = safeVal("#totalqty1");
      if (totalqty1) totalqty1.val(sumQty1.toFixed(2));

      // SUM AMOUNT
      let sumAmt2 = 0;
      $("#footable_21 .AMT").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumAmt2 += v;
      });

      let totalAmt1 = safeVal("#total_amount1");
      if (totalAmt1) totalAmt1.val(sumAmt2.toFixed(2)); 
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
      var pur_cgst=document.getElementById('pur_cgst').value;
      var pur_sgst=document.getElementById('pur_sgst').value;
      var pur_igst=document.getElementById('pur_igst').value;
      
      var tax_type_id1=document.getElementById('tax_type_id').value;
      if(tax_type_id1==2)
      {
      var iamt=  parseFloat(( amount*(pur_igst/100))).toFixed(2);
      $('#iamt').val(iamt);
      
      $('#total_amount').val(parseFloat(amount) + parseFloat(iamt));
      
      }
      else{
      var camt=  parseFloat(( amount*(pur_cgst/100))).toFixed(2);
      $('#camt').val(camt);
      var samt= parseFloat(( amount*(pur_sgst/100))).toFixed(2);
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
   
   function firmchange(firm_id)
   { 
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
   // var ac_code=$("#Ac_code").val();
   
   // $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('PartyDetail') }}",
   //         data:{'ac_code':ac_code},
   //         success: function(data)
   //         {
   //             $("#gstNo").val(data[0]['gst_no']);
             
   //         }
   //     });
   }
   
   
   
   
   //  $(document).on("keyup", 'input[name^="item_qtys[]"]', function (event) {
    
   //         mycalc();
   // });
   
   
   $(document).on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"]', function (event) {
      CalculateRow($(this).closest("tr"));
   });

   function CalculateRow(row)
   {
      var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
      $(row).attr("qty", item_qtys);
      var item_rates=+row.find('input[name^="item_rates[]"]').val();
      var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed(2);
      row.find('input[name^="amounts[]"]').val(amount);
      mycalc();
   }
   
   function getBomDetail(type){
   
   
   var  bom_codes = $("#bom_code option:selected").map(function() {
    return this.value;
   }).get().join(",");
   
   
   // alert(bom_codes);
   // var bom_code=document.getElementById("bom_code").value;
   
   var tax_type_id=document.getElementById("tax_type_id").value;
   
   
   $.ajax({
   type:"GET",
   url:"{{ route('getBoMDetail') }}",
   //dataType:"json",
   data:{type:type,bom_code:bom_codes,tax_type_id:tax_type_id},
   success:function(response){
   console.log(response);  
   $("#bomdis").append(response.html);
   mycalc();
   }
   });
   }
   
   
   
   
   
   
   $(document).ready(function(){
   
   FetchPoData();
   
   }); 
   
   function FetchPoData()
   {
   var po_code=document.getElementById('po_code').value;  
   if(po_code !="" && po_code!=0)
   {
      $("#Ac_code").select2("destroy");     
      gettable(po_code);
      getPODetails();
   
   }
   }
   
   
   
   // setInterval(function() {mycalc()}, 1000);
   
   //  setInterval(fun, 3000);  
   
   function gettable(po_code) {
       var po_codes = btoa(po_code);
   
       $.ajax({
           type: "GET",
           url: "{{ route('getPoForTrims') }}",
           data: { po_code: po_codes },
           beforeSend: function () {
               $("#trimInward").html("<p>Loading...</p>"); // Show a loading message before data loads
           },
           success: function (response) {
               $("#trimInward").empty(); // Clear old content
               $("#trimInward").append(response.html); // Append new content instead of replacing everything
               mycalc();
           },
           error: function (xhr, status, error) {
               console.error("Error:", error);
               $("#trimInward").html("<p>Failed to load data.</p>");
           }
       });
   } 
   
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
       $("input").prop('disabled', false);
   }
   
   
   
   function getDetails(po_code){
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPoMasterDetailTrims') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);
   
   $("#Ac_code").val(response[0].Ac_code);
   $("#tax_type_id").val(response[0].tax_type_id);
   $("#supplierRef").val(response[0].supplierRef);
   $("#pur_date").val(response[0].pur_date);
   $("#po_type_id").val(response[0].po_type_id);
   $("#bomtype").val(response[0].bomtype);
   $("#bom_code").val(response[0].bom_code);
   $("#in_narration").val(response[0].narration);
   $("#po_code").val(response[0].pur_code);
   
   
   }
   });
   } 
   
   
   
   function openmodal(po_code,item_code)
   {
   
   getFabInDetails(po_code,item_code);
      $('#modalFormSize').modal('show');
   }
   
   function closemodal()
   {
     $('#modalFormSize').modal('hide');
   //    $('#product-options').modal('hide');
   }
   
   
   
   function getFabInDetails(po_code,item_code)
   {
   
   $.ajax({
   type: "GET",
   url: "{{ route('GetComparePOInwardList') }}",
   data: { sr_no: po_code, item_code: item_code },
   success: function(data){
   $("#TrimsInwardData").html(data.html);
   }
   });
   }
   
</script>
@endsection