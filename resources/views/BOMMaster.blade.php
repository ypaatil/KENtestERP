@extends('layouts.master') 
@section('content')
<style>

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
    

    .navbar-brand-box
    {
        width: 266px !important;
    }
    .text-right
    {
        text-align:right;
    }
    /* ====== Bootstrap 3.4.1 Panel Styles Only ====== */
    
    /* Base panel structure */
    .panel {
      margin-bottom: 20px;
      background-color: #fff;
      border: 1px solid transparent;
      border-radius: 4px;
      box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }
    
    /* Panel body */
    .panel-body {
      padding: 15px;
    }
    
    /* Panel heading */
    .panel-heading {
      padding: 10px 15px;
      border-bottom: 1px solid transparent;
      border-top-left-radius: 3px;
      border-top-right-radius: 3px;
    }
    
    /* Panel title */
    .panel-title {
      margin-top: 0;
      margin-bottom: 0;
      font-size: 16px;
      color: inherit;
    }
    
    /* Anchor inside title */
    .panel-title > a,
    .panel-title > small,
    .panel-title > .small,
    .panel-title > small > a,
    .panel-title > .small > a {
      color: inherit;
      text-decoration: none;
    }
    
    /* Panel footer */
    .panel-footer {
      padding: 10px 15px;
      background-color: #f5f5f5;
      border-top: 1px solid #ddd;
      border-bottom-right-radius: 3px;
      border-bottom-left-radius: 3px;
    }
    
    /* Default panel theme */
    .panel-default {
      border-color: #ddd;
    }
    
    .panel-default > .panel-heading {
      color: #333;
      background-color: #f5f5f5;
      border-color: #ddd;
    }
    
    .panel-default > .panel-heading + .panel-collapse > .panel-body {
      border-top-color: #ddd;
    }
    
    .panel-default > .panel-footer + .panel-collapse > .panel-body {
      border-bottom-color: #ddd;
    }
    
    /* Collapse animation support (used by Bootstrap JS) */
    .panel-collapse.collapse {
      display: none;
    }
    
    .panel-collapse.collapse.in {
      display: block;
    }
    
    /* Accordion arrow caret (optional) */
    .panel-title a:after {
      content: "\25BC";
      float: right;
      transition: transform 0.3s ease;
    }
    
    .panel-heading.active .panel-title a:after {
      transform: rotate(180deg);
    }

</style>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">BOM</h4>
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
            <form action="{{route('BOM.store')}}" method="POST" enctype="multipart/form-data" id="frmData" >
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="soc_date" class="form-label">Entry Date</label>
                        <input type="date" name="bom_date" class="form-control" id="bom_date" value="{{date('Y-m-d')}}" readOnly>
                        @foreach($counter_number as  $row)
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                        @endforeach
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <input type="hidden" name="cost_type_id" value="0">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">Sales Order no</label>
                        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" required  onChange="getSalesOrderDetails(this.value);">
                           <option value="">--Sales Order No--</option>
                           @foreach($SalesOrderList as  $row)
                           {
                           <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
                           }
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
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled  >
                           <option value="">--Select Buyer--</option>
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
                        <select name="brand_id" class="form-select" id="brand_id" disabled>
                           <option value="">--Select Brand--</option>
                           @foreach($BrandList as  $row)
                           {
                           <option value="{{ $row->brand_id }}">{{ $row->brand_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="season_id" class="form-label">Season</label>
                        <select name="season_id" class="form-control" id="season_id" required>
                           <option value="">--Season--</option>
                           @foreach($SeasonList as  $row)
                           {
                           <option value="{{ $row->season_id }}">{{ $row->season_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency</label>
                        <select name="currency_id" class="form-control" id="currency_id" required>
                           <option value="">--Currency--</option>
                           @foreach($CurrencyList as  $row)
                           {
                           <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">FOB Rate</label>
                        <input type="number" name="order_rate" class="form-control" id="order_rate" value="" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Style</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                           <option value="">--Select Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" disabled>
                           <option value="">--Select Sub Style--</option>
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
                        <select name="fg_id" class="form-control" id="fg_id" disabled>
                           <option value="">--Select Style--</option>
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
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Order Qty</label>
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="0" readOnly>
                     </div>
                  </div>
               </div>
               
               <div class="col-md-12">
                    <h4 class="panel-title" style="padding: 10px;background: #f5f5f5;"> Order Qty </h4>
                    <div class="table-wrap">
                       <div class="table-responsive">
                          <table id="footable_2" class="table  table-bordered  m-b-0  footable_2">
                          </table>
                       </div>
                    </div>
               </div>
               <div class="row"  >
                  <div class="  "  >
                     <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Fabric: </a>
                              </h4>
                           </div>
                           <div id="collapse4" class="panel-collapse collapse">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_1" class="table  table-bordered  m-b-0  footable_1">
                                             <thead>
                                                <tr>
                                                   <th>Sr No</th>
                                                   <th>Classification</th>
                                                   <th>Item Name</th>
                                                   <th>Garment Colors</th>
                                                   <th>Description</th>
                                                   <th>Cons./Gmt (Mtr/Nos)</th>
                                                   <th>UOM</th>
                                                   <th>Cost/Pcs</th>
                                                   <th>Wastage %</th>
                                                   <th>Qty</th>
                                                   <th>Amount</th>
                                                   <th>Remark</th>
                                                   <th>Add</th>
                                                   <th>Remove</th>
                                                </tr>
                                             </thead>
                                             <tbody id="fabricBody">
                                                <tr>
                                                   <td><input type="text" readonly name="id" value="1" id="id" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="class_id[]"   id="class_id" style="width:200px; height:30px;" disabled>
                                                         <option value="">--Classification--</option>
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <select name="item_code[]" id="item_code" onchange="checkDuplicateItemGeneric(this, 'footable_1', 'item_code[]')" style="width:270px; height:30px;">
                                                         <option value="">--Item List--</option>
                                                      </select>
                                                   </td>
                                                   <td><textarea type="text"  name="colors[]" value="0" id="colors" style="width:200px; height:30px;" readOnly></textarea></td>
                                                   <td><input type="text"    name="description[]" value="" id="description" style="width:200px; height:30px;" readOnly/></td>
                                                   <td><input type="number" step="any"    name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;" /></td>
                                                   <td>
                                                      <select name="unit_id[]"  id="unit_id" style="width:100px; height:30px;" disabled>
                                                         <option value="">--Unit List--</option>
                                                         @foreach($UnitList as  $row)
                                                         {
                                                         <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="number" step="any" name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px; height:30px;" /></td>
                                                   <td><input type="number" step="any" name="wastage[]" value="0" id="wastage" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="number" step="any" name="bom_qty[]" value="0" id="bom_qty" style="width:80px; height:30px;" readOnly />
                                                      <input type="hidden"  name="bom_qty1[]" value="0" id="bom_qty1" style="width:80px; height:30px;" readOnly />
                                                      <input type="hidden" name="bom_qty_expect[]" value="0" id="bom_qty_expect1" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td><input type="number" step="any"  class="FABRIC"  name="total_amount[]" value="0" id="total_amounts" style="width:80px; height:30px;" readOnly/></td>
                                                   <td><input type="text"      name="remark[]" value="" id="remark" style="width:80px; height:30px;" /></td>
                                                   <td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button></td>
                                                   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Trim Fabric: </a>
                              </h4>
                           </div>
                           <div id="collapse5" class="panel-collapse collapse">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr5" id="cntrr5" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_5" class="table  table-bordered  m-b-0  footable_5">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Classification</th>
                                                   <th>Item Name</th>
                                                   <th>Description</th>
                                                   <th>Garment Color</th>
                                                   <th>Size</th>
                                                   <th>Cons./Gmt (Mtr/Nos)</th>
                                                   <th>UOM</th>
                                                   <th>Cost/Pcs</th>
                                                   <th>Wastage %</th>
                                                   <th>Qty</th>
                                                   <th>Amount</th>
                                                   <th>Remark</th>
                                                   <th>Add</th>
                                                   <th>Remove</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td><input type="text" readonly name="idsx" value="1" id="idsx" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="class_idsx[]" class="Classify"  id="class_idsx" style="width:200px; height:30px;"  onchange="CalculateQtyRowPros12(this);"  >
                                                         <option value="">--Classification--</option>
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <select name="item_codesx[]" class="select2 " id="item_codesx"  onchange="checkDuplicateItemGeneric(this, 'footable_5', 'item_codesx[]')"  style="width:270px; height:30px;"  >
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList4 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                            >({{  $row->item_code  }})   {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="text" name="descriptionsx[]" value="" id="descriptionsx" style="width:200px; height:30px;"   readOnly/></td>
                                                   <td>
                                                      <select name="color_idsx[][]"   id="color_idsx" style="width:300px; height:140px;"   multiple>
                                                         <option value="">--Color List--</option>
                                                      </select>
                                                      <input type="hidden" name="color_arraysx[]" value="0" id="color_arraysx" style="width:80px; height:30px;"   />
                                                   </td>
                                                   <td>
                                                      <select name="size_idsx[][]"   id="size_idsx" style="width:200px; height:140px;"   multiple>
                                                         <option value="">--Size List--</option>
                                                      </select>
                                                      <input type="hidden"  name="size_arraysx[]" value="0" id="size_arraysx" style="width:80px; height:30px;"   />
                                                   </td>
                                                   <td><input type="number" step="any"    name="consumptionsx[]" value="0" id="consumptionsx" style="width:80px; height:30px;"/></td>
                                                   <td>
                                                      <select name="unit_idsx[]" id="unit_idsx" style="width:100px; height:30px;" disabled >
                                                         <option value="">--Unit List--</option>
                                                         @foreach($UnitList as  $row)
                                                         {
                                                         <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="number" step="any" name="rate_per_unitsx[]" value="0" id="rate_per_unitsx" style="width:80px; height:30px;"  /></td>
                                                   <td><input type="number" step="any" name="wastagesx[]" value="0" id="wastagesx" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="number" step="any" name="bom_qtysx[]" value="0" id="bom_qtysx" style="width:80px; height:30px;"   readOnly />
                                                      <input type="hidden" name="bom_qtysx1[]" value="0" id="bom_qtysx1" style="width:80px; height:30px;"   readOnly />
                                                      <input type="hidden" name="bom_qtysx_expect[]" value="0" id="bom_qtysx_expect1" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td><input type="number" step="any"  class="TRIMFABRIC"  name="total_amountsx[]" value="0" id="total_amountsx" style="width:80px; height:30px;"   readOnly/></td>
                                                   <td><input type="text"      name="remarksx[]" value="" id="remarksx" style="width:80px; height:30px;" /></td>
                                                   <td><button type="button" onclick="insertcone5();" class="btn btn-warning pull-left">+</button></td>
                                                   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone5(this);" value="X" ></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Sewing Trims: </a>
                              </h4>
                           </div>
                           <div id="collapse2" class="panel-collapse collapse">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_3" class="table  table-bordered  m-b-0  footable_3">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Classification</th>
                                                   <th>Item Name</th>
                                                   <th>Description</th>
                                                   <th>Garment Color</th>
                                                   <th>Size</th>
                                                   <th>Cons./Gmt (Mtr/Nos)</th>
                                                   <th>UOM</th>
                                                   <th>Cost/Pcs</th>
                                                   <th>Wastage %</th>
                                                   <th>Qty</th>
                                                   <th>Amount</th>
                                                   <th>Remark</th>
                                                   <th>Add</th>
                                                   <th>Remove</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td><input type="text" readonly name="ids" value="1" id="id" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="class_ids[]" class="Classify"  id="class_ids" style="width:200px; height:30px;" onchange="CalculateQtyRowPros10(this);">
                                                         <option value="">--Classification--</option>
                                                         @foreach($ClassList2 as  $row)
                                                         {
                                                         <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <select name="item_codes[]" class="select2" id="item_codes" onchange="checkDuplicateItemGeneric(this, 'footable_3', 'item_codes[]')" style="width:270px; height:30px;" >
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList2 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"> ({{  $row->item_code  }})    {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="text" name="descriptions[]" value="" id="descriptions" style="width:200px; height:30px;" readOnly/></td>
                                                   <td>
                                                      <select name="color_ids[][]"   id="color_ids" style="width:300px; height:140px;" multiple>
                                                         <option value="">--Color List--</option>
                                                      </select>
                                                      <input type="hidden" name="color_arrays[]" value="0" id="color_arrays" style="width:80px; height:30px;" />
                                                   </td>
                                                   <td>
                                                      <select name="size_ids[][]"   id="size_ids" style="width:200px; height:140px;" multiple>
                                                         <option value="">--Size List--</option>
                                                      </select>
                                                      <input type="hidden" name="size_arrays[]" value="0" id="size_arrays" style="width:80px; height:30px;" />
                                                   </td>
                                                   <td><input type="number" step="any"  name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;" /></td>
                                                   <td>
                                                      <select name="unit_ids[]"   id="unit_ids" style="width:100px; height:30px;" disabled>
                                                         <option value="">--Unit List--</option>
                                                         @foreach($UnitList as  $row)
                                                         {
                                                         <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="number" step="any" name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;" /></td>
                                                   <td><input type="number" step="any" name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="number" step="any" name="bom_qtys[]" value="0" id="bom_qtys" style="width:80px; height:30px;" readOnly />
                                                      <input type="hidden" name="bom_qtys1[]" value="0" id="bom_qtys1" style="width:80px; height:30px;" readOnly />
                                                      <input type="hidden" name="bom_qtys_expect[]" value="0" id="bom_qtys_expect1" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td><input type="number" step="any"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" readOnly/></td>
                                                   <td><input type="text" name="remarks[]" value="" id="remarks" style="width:80px; height:30px;" /></td>
                                                   <td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button></td>
                                                   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Packing Trims:</a>
                              </h4>
                           </div>
                           <div id="collapse3" class="panel-collapse collapse">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr3" id="cntrr3" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_4" class="table  table-bordered  m-b-0  footable_4">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Classification</th>
                                                   <th>Item Name</th>
                                                   <th>Description</th>
                                                   <th>Garment Color</th>
                                                   <th>Size</th>
                                                   <th>Cons./Gmt (Mtr/Nos)</th>
                                                   <th>UOM</th>
                                                   <th>Cost/Pcs</th>
                                                   <th>Wastage %</th>
                                                   <th>Qty</th>
                                                   <th>Amount</th>
                                                   <th>Remark</th>
                                                   <th>Add</th>
                                                   <th>Remove</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td><input type="text" readonly name="idss" value="1" id="id" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="class_idss[]" class="Classify"  id="class_idss" style="width:200px; height:30px;" onchange="CalculateQtyRowPros11(this);" >
                                                         <option value="">--Classification--</option>
                                                         @foreach($ClassList3 as  $row)
                                                         {
                                                         <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <select name="item_codess[]" class="select2" id="item_codess" onchange="checkDuplicateItemGeneric(this, 'footable_4', 'item_codess[]')" style="width:270px; height:30px;">
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList3 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}">({{ $row->item_code }})    {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td> <input type="text" name="descriptionss[]" value="" id="descriptionss" style="width:200px; height:30px;" readOnly/></td>
                                                   <td>
                                                      <select name="color_idss[][]"   id="color_idss" style="width:300px; height:140px;" multiple>
                                                         <option value="">--Color List--</option>
                                                      </select>
                                                      <input type="hidden" name="color_arrayss[]" value="0" id="color_arrayss" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td>
                                                      <select name="size_idss[][]"   id="size_idss" style="width:200px; height:140px;" multiple>
                                                         <option value="">--Size List--</option>
                                                      </select>
                                                      <input type="hidden"     name="size_arrayss[]" value="0" id="size_arrayss" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td><input type="number" step="any" name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" /></td>
                                                   <td>
                                                      <select name="unit_idss[]"   id="unit_idss" style="width:100px; height:30px;" disabled>
                                                         <option value="">--Unit List--</option>
                                                         @foreach($UnitList as  $row)
                                                         {
                                                         <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="number" step="any" name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" /></td>
                                                   <td><input type="number" step="any" name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="number" step="any" name="bom_qtyss[]" value="0" id="bom_qtyss" style="width:80px; height:30px;" readOnly/>
                                                      <input type="hidden"  name="bom_qtyss1[]" value="0" id="bom_qtyss1" style="width:80px; height:30px;" readOnly/>
                                                      <input type="hidden"  name="bom_qtyss_expect[]" value="0" id="bom_qtyss_expect1" style="width:80px; height:30px;" readOnly/>
                                                   </td>
                                                   <td><input type="number" step="any"  class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;" readOnly /></td>
                                                   <td><input type="text"      name="remarkss[]" value="" id="remarkss" style="width:80px; height:30px;" /></td>
                                                   <td><button type="button" onclick="insertcone3(this);" class="btn btn-warning pull-left">+</button></td>
                                                   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               </br>
               </br>
               <!-- end row -->
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Fabric Budget Cost</label>
                        <input type="text" name="fabric_value" class="form-control" id="fabric_value" value="" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Sewing Trims Budget Cost</label>
                        <input type="text" name="sewing_trims_value" class="form-control" id="sewing_trims_value" value="" readOnly >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Packing Trims Budget Cost</label>
                        <input type="text" name="packing_trims_value" class="form-control" id="packing_trims_value" value="" readOnly >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Total Budget Cost</label>
                        <input type="text" name="total_cost_value" class="form-control" id="total_cost_value" value="" readOnly>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <label for="formrow-inputState" class="form-label">Remark</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
                     <a href="{{ Route('BOM.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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


    (function($){
        $(document).ready(function(){
          // initialize any panels that are already open
          $('.panel-custom .panel-collapse.in').each(function(){
            $(this).prev('.panel-heading').addClass('open');
          });
    
          // on show/hide toggle the .open class
          $('.panel-custom .panel-collapse').on('show.bs.collapse', function(){
            $(this).prev('.panel-heading').addClass('open');
          }).on('hide.bs.collapse', function(){
            $(this).prev('.panel-heading').removeClass('open');
          });
    
          // also allow clicking the entire header area to toggle (if you want)
          $('.panel-custom .panel-heading').on('click', function(e){
            // avoid double-toggle if clicking the <a> itself (Bootstrap handles that)
            if ($(e.target).is('a')) return;
            var $t = $(this).find('a[data-toggle="collapse"]');
            if ($t.length) $t.trigger('click');
          });
        });
      })(jQuery);
//   function checkDuplicateItemGeneric(row, tableId, inputName)
//   {
//         // $(row).parent().parent('tr').find('select').select2('destroy');
//         var currentItemCode = $(row).val();
//         if (!currentItemCode) return; // Skip if empty
    
//         var duplicateCount = 0;
    
//         // Build dynamic selector based on table ID and input name
//         $('#' + tableId + ' select[name="' + inputName + '"]').each(function () {
//             if ($(this).val() === currentItemCode) {
//                 duplicateCount++;
//             }
//         });
    
//         if (duplicateCount > 1) {
//             alert('This item is already selected! Please choose another.');
//             if(tableId != 'footable_1')
//             {
//                 $('#' + tableId + ' select[name="' + inputName + '"]').select2('destroy');
//             }
//             // Properly clear Select2 field (value + UI text)
//             $(row).val(null).trigger('change'); // resets value safely
//             $(row).select2('close'); // close dropdown if open
    
//             $(row).focus();
//             if(tableId != 'footable_1')
//             {
//                 $('#' + tableId + ' select[name="' + inputName + '"]').select2();
//             }
//         } else {
//             // Allow dependent function to run before disabling
//             setTimeout(function () {
//                 $(row).prop("disabled", true);
//                 if(tableId == 'footable_5')
//                 {
//                     $(row).parent().parent('tr').find('select[name="class_idsx[]"]').prop("disabled", true); 
//                 }
//                 if(tableId == 'footable_3')
//                 {
//                     $(row).parent().parent('tr').find('select[name="class_ids[]"]').prop("disabled", true);
//                 }
//                 if(tableId === 'footable_4')
//                 {
//                     $(row).parent().parent('tr').find('select[name="class_idss[]"]').prop("disabled", true);
//                 }
//             }, 200);
//         }
//     }
    
    function CheckDuplicateItemForFabric(selectEl) 
    {
        var $select = $(selectEl);
        var currentValue = $select.val();
        var currentText = $select.find("option:selected").text();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('GetItemDetails') }}",
            data: { item_code: currentValue },
            success: function (data) {
                if (data && data.length > 0) {
                    console.log(data[0].item_description);
                    $select.closest('tr').find('td:eq(4) input').val(data[0].item_description); 
                } else {
                    console.warn("No item details found for code:", currentValue);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching item details:", error);
            }
        });
        if (!currentValue) return; // if nothing selected, exit
    
        // find all item_code select elements
        var allValues = [];
        $('select[name="item_code[]"]').each(function() {
            var val = $(this).val();
            if (val) allValues.push(val);
        });
    
        // count occurrences of the current value
        var duplicateCount = allValues.filter(v => v === currentValue).length;
    
        if (duplicateCount > 1) {
            alert('Duplicate Item: ' + currentText);
    
            // Destroy Select2 before clearing value
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }
    
            // Clear the value
            $select.val('');
    
            // Reinitialize Select2
            $select.select2();
    
            // Optional: visually highlight the duplicate field for a moment
            $select.next('.select2-container').find('.select2-selection').css('border', '2px solid red');
            setTimeout(function() {
                $select.next('.select2-container').find('.select2-selection').css('border', '');
            }, 1500);
        }
        else
        {
            CalculateQtyRowPros1(selectEl);
        }
    }

    
      
   function checkDuplicateItemGeneric(row, tableId) 
   {
      var $currentRow = $(row).closest('tr');

      // 🟢 Get Item, Color, and Size elements using column index
      var $itemSelect  = $currentRow.find('td:eq(2)').find('select');
      var $colorSelect = $currentRow.find('td:eq(4)').find('select');
      var $sizeSelect  = $currentRow.find('td:eq(5)').find('select');

      // 🟢 Get current values
      var itemCode = ($itemSelect.val() || '').toString().trim();
      var colorsRaw = $colorSelect.val() || [];
      var sizesRaw  = $sizeSelect.val()  || [];

      // Convert all to array (even if single value)
      var colors = Array.isArray(colorsRaw) ? colorsRaw.map(String) : [String(colorsRaw)];
      var sizes  = Array.isArray(sizesRaw)  ? sizesRaw.map(String)  : [String(sizesRaw)];

      colors = colors.filter(c => c && c !== 'null' && c !== 'undefined');
      sizes  = sizes.filter(s => s && s !== 'null' && s !== 'undefined');

      if (!itemCode || colors.length === 0 || sizes.length === 0) return false;

      var duplicateFound = false;
      var duplicateInfo  = null;

      // 🟢 Loop through all rows in the same table
      $('#' + tableId + ' tbody tr').each(function () {
         var $row = $(this);
         if ($row.is($currentRow)) return true; // skip same row

         var $otherItem  = $row.find('td:eq(2)').find('select');
         var $otherColor = $row.find('td:eq(4)').find('select');
         var $otherSize  = $row.find('td:eq(5)').find('select');

         var otherItemCode = ($otherItem.val() || '').toString().trim();
         var otherColorsRaw = $otherColor.val() || [];
         var otherSizesRaw  = $otherSize.val()  || [];

         var otherColors = Array.isArray(otherColorsRaw) ? otherColorsRaw.map(String) : [String(otherColorsRaw)];
         var otherSizes  = Array.isArray(otherSizesRaw)  ? otherSizesRaw.map(String)  : [String(otherSizesRaw)];

         otherColors = otherColors.filter(c => c && c !== 'null' && c !== 'undefined');
         otherSizes  = otherSizes.filter(s => s && s !== 'null' && s !== 'undefined');

         // Compare (item + color + size)
         if (itemCode === otherItemCode) {
               for (var i = 0; i < colors.length; i++) {
                  for (var j = 0; j < sizes.length; j++) {
                     if (otherColors.includes(colors[i]) && otherSizes.includes(sizes[j])) {
                           duplicateFound = true;
                           var colorText = $colorSelect.find('option[value="' + colors[i] + '"]').text().trim();
                           var sizeText  = $sizeSelect.find('option[value="' + sizes[j] + '"]').text().trim();
                           duplicateInfo = { color_name: colorText || colors[i], size_name: sizeText || sizes[j] };
                           break;
                     }
                  }
                  if (duplicateFound) break;
               }
         }

         if (duplicateFound) return false; // stop loop
      });

      // 🟢 If duplicate found — show alert and clear fields
      if (duplicateFound) {
         $currentRow.find('input[type="number"]').val('');
         alert('This item with Color "' + duplicateInfo.color_name + '" and Size "' + duplicateInfo.size_name + '" already exists!');

         // Reset selects
         [$colorSelect, $sizeSelect].forEach(function($select) {
               if ($select.data('select2')) {
                  $select.val(null).trigger('change.select2');
               } else {
                  $select.val(null).trigger('change');
               }
         });

         // Keep item enabled
         $itemSelect.prop("disabled", false).focus();
         return true;
      }

      // 🟢 If unique, disable item & class selects
      setTimeout(function () {
         $itemSelect.prop("disabled", true);
         if (tableId === 'footable_5') {
               $currentRow.find('select[name="class_idsx[]"]').prop("disabled", true);
         }
         if (tableId === 'footable_3') {
               $currentRow.find('select[name="class_ids[]"]').prop("disabled", true);
         }
         if (tableId === 'footable_4') {
               $currentRow.find('select[name="class_idss[]"]').prop("disabled", true);
         }
      }, 200);

      return false;
   }

   $(document).on('change', '#footable_3 select, #footable_3 input, #footable_4 select, #footable_4 input, #footable_5 select, #footable_5 input', function() {
      var $table = $(this).closest('table');
      var tableId = $table.attr('id'); // get which table triggered it
      checkDuplicateItemGeneric(this, tableId);
   });


    
    $(document).on("click", 'input[name^="Fbutton[]"]', function (event) {
        // Find the closest tr from the clicked button
        var $currentRow = $(this).closest('tr');
        
        $currentRow.find('select').select2('destroy');
        console.log("Select2 destroyed");

        // Clone the row
        var $clonedRow = $currentRow.clone();
    
        // Optional: clear input values in cloned row
        $clonedRow.find('input, select, textarea').each(function() {
            $(this).not('input[type="button"]').val(''); // clear value
        });
    
        $clonedRow.find('select').not('select[name="unit_id[]"]').attr("disabled", false);
        $clonedRow.find('input').not('input[name="wastage[]"]').not('button').attr("readonly", false);
        $clonedRow.find('textarea').attr("readonly", false);
        // Append the cloned row to the end of the table
        $currentRow.closest('table').append($clonedRow);
           // Re-initialize Select2 for both original and cloned rows
        $currentRow.find('select').select2();
        $clonedRow.find('select').select2();
        
    });

  $(document).ready(function () 
  {
        function setRowError($row) 
        {
            $row.addClass('row-error').css('background-color', '#ffdddd');
        }
    
        function clearRowError($row) 
        {
            $row.removeClass('row-error');
            const s = ($row.attr('style') || '')
                .replace(/(--bs-table-accent-bg|background(?:-color)?|background):[^;]+;?/gi, '')
                .trim();
            if (s) $row.attr('style', s);
            else $row.removeAttr('style');
        }
    
        function findInput($row, name) 
        {
            let $el = $row.find(`input[name="${name}"]`);
            if ($el.length) return $el;
            const base = name.replace(/\[\]$/, '');
            $el = $row.find(`input[name^="${base}"]`);
            if ($el.length) return $el;
            return $row.find(`input[name*="${base}"]`);
        }
    
        function toggleRowState($row, bom_qty_expected, bom_qty_actual) {
            if (bom_qty_expected !== bom_qty_actual) {
                $row.find("select, input").prop("disabled", false).prop("readonly", false);
            } 
        }
    
        // --- Calculate functions ---
        function CalculateQtyRowTrim(row) {
            return new Promise((resolve) => {
                console.log("In Trim");
                const item_code = +row.find('select[name^="item_codesx[]"]').val();
                const sales_order_no = $('#sales_order_no').val();
                const color_val = row.find('select[name^="color_idsx[][]"]').val();
                const size_val = row.find('select[name^="size_idsx[][]"]').val();
                const class_id = row.find('select[name^="class_idsx[]"]').val();
    
                const color_id = Array.isArray(color_val) ? color_val.join(",") : (color_val || "");
                const size_id = Array.isArray(size_val) ? size_val.join(",") : (size_val || "");
    
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('TrimFabricWiseSalesOrderCosting') }}",
                    data: { item_code, sales_order_no, color_id, size_id, class_id },
                    success: function (data) {
                        const qty = (data && data[0] && data[0].bom_qty) ? data[0].bom_qty : 0;
                        findInput(row, 'bom_qtysx_expect[]').val(qty);
    
                        const bom_qty1 = parseFloat(findInput(row, 'bom_qtysx_expect[]').val() || 0) || 0;
                        const bom_qty2 = parseFloat(findInput(row, 'bom_qtysx1[]').val() || 0) || 0;
                        toggleRowState(row, bom_qty1, bom_qty2);
                        resolve();
                    },
                    error: function () { resolve(); }
                });
            });
        }
    
        function CalculateQtyRowFabric(row) {
            return new Promise((resolve) => {
                console.log("In Fabric");
                const item_code = +row.find('select[name^="item_code[]"]').val();
                const sales_order_no = $('#sales_order_no').val();
    
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('FabricWiseSalesOrderCosting') }}",
                    data: { item_code, sales_order_no },
                    success: function (data) {
                        const qty = (data && data[0] && data[0].bom_qty) ? data[0].bom_qty : 0;
                        findInput(row, 'bom_qty_expect[]').val(qty);
    
                        const bom_qty1 = parseFloat(findInput(row, 'bom_qty_expect[]').val() || 0) || 0;
                        const bom_qty2 = parseFloat(findInput(row, 'bom_qty1[]').val() || 0) || 0;
                        toggleRowState(row, bom_qty1, bom_qty2);
                        resolve();
                    },
                    error: function () { resolve(); }
                });
            });
        }
    
        function CalculateQtyRowSewing(row) {
            return new Promise((resolve) => {
                console.log("In Sewing");
                const item_code = +row.find('select[name^="item_codes[]"]').val();
                const color_val = row.find('select[name^="color_ids[][]"]').val();
                const size_val = row.find('select[name^="size_ids[][]"]').val();
                const sales_order_no = $('#sales_order_no').val();
    
                const color_id = Array.isArray(color_val) ? color_val.join(",") : (color_val || "");
                const size_id = Array.isArray(size_val) ? size_val.join(",") : (size_val || "");
    
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('ItemWiseSalesOrderCosting') }}",
                    data: { item_code, sales_order_no, color_id, size_id },
                    success: function (data) {
                        const qty = (data && data[0] && data[0].bom_qty) ? data[0].bom_qty : 0;
                        findInput(row, 'bom_qtys_expect[]').val(qty);
    
                        const bom_qty1 = parseFloat(findInput(row, 'bom_qtys_expect[]').val() || 0) || 0;
                        const bom_qty2 = parseFloat(findInput(row, 'bom_qtys1[]').val() || 0) || 0;
                        toggleRowState(row, bom_qty1, bom_qty2);
                        resolve();
                    },
                    error: function () { resolve(); }
                });
            });
        }
    
        function CalculateQtyRowPacking(row) {
            return new Promise((resolve) => {
                console.log("In packing");
                const item_code = +row.find('select[name^="item_codess[]"]').val();
                const sales_order_no = $('#sales_order_no').val();
                const color_val = row.find('select[name^="color_idss[][]"]').val();
                const size_val = row.find('select[name^="size_idss[][]"]').val();
    
                const color_id = Array.isArray(color_val) ? color_val.join(",") : (color_val || "");
                const size_id = Array.isArray(size_val) ? size_val.join(",") : (size_val || "");
    
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "{{ route('PackingWiseSalesOrderCosting') }}",
                    data: { item_code, sales_order_no, color_id, size_id },
                    success: function (data) {
                        const qty = (data && data[0] && data[0].bom_qty) ? data[0].bom_qty : 0;
                        findInput(row, 'bom_qtyss_expect[]').val(qty);
    
                        const bom_qty1 = parseFloat(findInput(row, 'bom_qtyss_expect[]').val() || 0) || 0;
                        const bom_qty2 = parseFloat(findInput(row, 'bom_qtyss1[]').val() || 0) || 0;
                        toggleRowState(row, bom_qty1, bom_qty2);
                        resolve();
                    },
                    error: function () { resolve(); }
                });
            });
        }
    
        // --- Validator ---
        function validateRows(containerSelector, expectName, actualName) {
            const EPS = 1e-6;
            let hasError = false;
    
            $(containerSelector).find('table tbody tr').each(function () {
                const $row = $(this);
                const raw1 = (findInput($row, expectName).val() || '').toString().trim().replace(/,/g, '');
                const raw2 = (findInput($row, actualName).val() || '').toString().trim().replace(/,/g, '');
    
                const n1 = parseFloat(raw1);
                const n2 = parseFloat(raw2);
    
                if (!isFinite(n1) || !isFinite(n2) || Math.abs((n1 || 0) - (n2 || 0)) > EPS) {
                    hasError = true;
                    setRowError($row);
                } else {
                    clearRowError($row);
                }
            });
    
            return !hasError;
        }
    
        async function processCollapseParallel(collapseSelector, calculateFn, expectName, actualName, errorMsg) {
            const $collapse = $(collapseSelector);
            const rows = $collapse.find('table tbody tr').toArray();
            if (!rows.length) return true;
    
            const promises = rows.map(r => calculateFn($(r)));
            await Promise.all(promises);
    
            const ok = validateRows(collapseSelector, expectName, actualName);
            if (!ok) {
                alert("❌ " + errorMsg);
                return false;
            }
    
            $collapse.find(':disabled').prop('disabled', false);
            return true;
        }
    
        // --- Main Submit ---
        $('#frmData').on('submit', async function (e) {
            e.preventDefault();
            const form = this;
            const tasks = [
                { sel: '#collapse5', fn: CalculateQtyRowTrim, expect: 'bom_qtysx_expect[]', actual: 'bom_qtysx1[]', msg: 'Trim Fabric rows have mismatched quantities. Please correct them.' },
                { sel: '#collapse4', fn: CalculateQtyRowFabric, expect: 'bom_qty_expect[]', actual: 'bom_qty1[]', msg: 'Fabric rows have mismatched quantities. Please correct them.' },
                { sel: '#collapse2', fn: CalculateQtyRowSewing, expect: 'bom_qtys_expect[]', actual: 'bom_qtys1[]', msg: 'Sewing rows have mismatched quantities. Please correct them.' },
                { sel: '#collapse3', fn: CalculateQtyRowPacking, expect: 'bom_qtyss_expect[]', actual: 'bom_qtyss1[]', msg: 'Packing rows have mismatched quantities. Please correct them.' }
            ];
    
            for (const t of tasks) {
                const ok = await processCollapseParallel(t.sel, t.fn, t.expect, t.actual, t.msg);
                if (!ok) {
                    return; // stop on first failure
                }
            }
    
            $('#frmData').find(':disabled').prop('disabled', false);
            form.submit();
        });
    });

   
   function popup()
   {
        alert('Before making BOM, please ensure that all garment colors, corresponding fabrics, and sizewise quantities are entered correctly in the sales order as per buyer PO');    
   }

   function getSalesOrderDetails(sales_order_no)
   {
       
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('SalesOrderCostingStatus') }}",
           data:{'sales_order_no':sales_order_no},
           success: function(data)
           {
               if(data.status != 2)
               {
                   $("#sales_order_no").select2("destroy"); // properly destroys Select2
                   $("#sales_order_no").val(null); // clears the value and updates Select2
                   
                   alert('The selected sales order no. '+sales_order_no+' costing is not approved.'); 
                   $("#sales_order_no").select2();
               }
               else
               {
                    popup();
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
                        $("#season_id").val(data[0]['season_id']);
                        $("#Ac_code").val(data[0]['Ac_code']);
                        $("#currency_id").val(data[0]['currency_id']);
                       
                       
                        $("#mainstyle_id").val(data[0]['mainstyle_id']);
                        $("#substyle_id").val(data[0]['substyle_id']);
                       
                        $("#style_no").val(data[0]['style_no']);
                        $("#fg_id").val(data[0]['fg_id']);
                       
                        $("#style_description").val(data[0]['style_description']);
                        $("#order_rate").val(data[0]['order_rate']);
                        $("#total_qty").val(data[0]['total_qty']);
                         
                        
                        $("#sales_order_no").attr('disabled', true);
                        document.getElementById('season_id').disabled=true;
                        document.getElementById('Ac_code').disabled=true;
                        document.getElementById('currency_id').disabled=true;
                        document.getElementById('mainstyle_id').disabled=true;
                        document.getElementById('substyle_id').disabled=true;
                        document.getElementById('fg_id').disabled=true;
                
                   }
                   });
               
                   $.ajax({
                        dataType: "json",
                       url: "{{ route('GetOrderQty') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       $("#footable_2").html(data.html);
                       }
                    });
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetSizeList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       // $("#size_id").html(data.html);
                       $("#size_ids").html(data.html);
                        $("#size_idsx").html(data.html);
                       $("#size_idss").html(data.html);
                       }
                    });
                       
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetColorList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       // $("#color_id").html(data.html);
                       $("#color_ids").html(data.html);
                       $("#color_idss").html(data.html);
                       
                       $("#color_idsx").html(data.html);
                       }
                    });
                       
                    // $.ajax({
                    //       dataType: "json",
                    //   url: "{{ route('GetItemList') }}",
                    //   data:{'tr_code':sales_order_no},
                    //   success: function(data){
                    //   $("#item_code").html(data.html);
                        
                    //   }
                    // });
                   
                    $.ajax({
                       dataType: "json",
                       url: "{{ route('GetBOMFabricFromSalesOrder') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data)
                       {
                            $("#fabricBody").html(data.html); 
                            setTimeout(mycalc,2000);
                       }
                    });
   
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetSewingTrimItemList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       $("#item_codes").html(data.html);
                        
                       }
                    });
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetPackingTrimItemList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       $("#item_codess").html(data.html);
                        
                       }
                    });
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetClassList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                    //   $("#class_id").html(data.html);
                       $("#class_idsx").html(data.html);
                       }
                    });
                       
                       
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetSewingClassList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       $("#class_ids").html(data.html);
                       
                       }
                    });
                       
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetPackingClassList') }}",
                       data:{'tr_code':sales_order_no},
                       success: function(data){
                       $("#class_idss").html(data.html);
                       
                       }
                    }); 
               }
           }
        });
               
   }
   
   function EnableFields()
   {
            document.getElementById('season_id').disabled=false;
            document.getElementById('Ac_code').disabled=false;
            document.getElementById('currency_id').disabled=false;
            document.getElementById('mainstyle_id').disabled=false;
            document.getElementById('substyle_id').disabled=false;
            document.getElementById('fg_id').disabled=false;
            document.getElementById('style_description').disabled=false;
            document.getElementById('order_rate').disabled=false;
            document.getElementById('style_no').disabled=false;
            $("select").prop('disabled', false);
   }
   
   
   
   
   $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
   
   setTimeout(mycalc,2000);
   
   });
   
   
   
   //  $('#footable_2').on('change', '.item', function() 
   //  {
   //   var item_code = $(this).val();
   
   //   var row = $(this).closest('tr'); // get the row
   //     $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('ItemDetails') }}",
   //       data:{item_code:item_code},
   //         success: function(data){
   
   //              console.log(data); 
           
   //                 row.find('select[name^="quality_code[]"]').val(data[0]['quality_code']);
   //                 +row.find('input[name^="unit_id[]"]').attr('value', data[0]['unit_id']); 
   //                 +row.find('input[name^="count_construction[]"]').attr('value', data[0]['item_description']);
            
   //          }
   //         });
   
   // });
   
   $('table.footable_1').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   {
   // alert();
   CalculateQtyRowPro($(this).closest("tr"));
   
   });
   function CalculateQtyRowPro(row)
   {   
    var consumption=+row.find('input[name^="consumption[]"]').val();
    var wastage=+row.find('input[name^="wastage[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
    var bom_qty=(parseFloat((+row.find('input[name^="bom_qty1[]"]').val())*consumption));
    var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4);
    var total_price=(bom_qty1*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qty[]"]').val(bom_qty1);
    row.find('input[name^="total_amount[]"]').val(total_price);
    setTimeout(mycalc,2000);
   }
   
   
   // For Trim Fabric get Consumption Details From Sales Costing Table
   $(document).on('change', 'select[name^="item_codesx[]"],select[name^="color_idsx[][]"],select[name^="size_idsx[][]"]', function()
   {CalculateQtyRowPros5($(this).closest("tr"));});
   function CalculateQtyRowPros5(row)
   {   
   var item_code=+row.find('select[name^="item_codesx[]"]').val();
   var color_id=row.find('select[name^="color_idsx[][]"]').val().join(",");
   var size_id=row.find('select[name^="size_idsx[][]"]').val().join(",");
   
   row.find('input[name^="color_arraysx[]"]').val(color_id);
   row.find('input[name^="size_arraysx[]"]').val(size_id);
   var sales_order_no=$('#sales_order_no').val();
   $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('TrimFabricWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
           success: function(data)
           {
                   console.log(data);
                //   row.find('input[name^="descriptionsx[]"]').val(data[0]['description1']);
                   row.find('input[name^="consumptionsx[]"]').val(data[0]['consumption']);
                  
                   row.find('input[name^="wastagesx[]"]').val(data[0]['wastage']);
                     
                   row.find('input[name^="rate_per_unitsx[]"]').val(data[0]['rate_per_unit']);
                 
                   row.find('select[name^="class_idsx[]"]').val(data[0]['class_id']);
                   row.find('select[name^="unit_idsx[]"]').val(data[0]['unit_id']);
                    
                   var  wastage=data[0]['wastage'];
                   var  consumption=data[0]['consumption'];
                   var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                   var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                   row.find('input[name^="bom_qtysx[]"]').val(bom_qty1);
                   row.find('input[name^="bom_qtysx1[]"]').val(data[0]['bom_qty']);
              
                  var total_amount=(bom_qty1*data[0]['rate_per_unit']).toFixed(4)
                   row.find('input[name^="bom_qtysx[]"]').val(bom_qty1);
                   
                   row.find('input[name^="total_amountsx[]"]').val(total_amount);
                   
                   
                   @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                    row.find('input[name^="consumptionsx[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                   row.find('input[name^="wastagesx[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                    row.find('input[name^="rate_per_unitsx[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                   row.find('input[name^="bom_qtysx[]"]').attr({"max" : bom_qty1,"min" : 0});
                   @php } @endphp
                   
           }
       });
   
      setTimeout(mycalc,2000);
   
   }
   
   
   // $(document).on('change', 'select[name^="class_ids[]"]', function()
   // {CalculateQtyRowPros10($(this).closest("tr"));});
   function CalculateQtyRowPros10(row)
   {  //  alert('1');
    
    var class_id = $(row).val();
    var row = $(row).closest('tr'); 
   
    row.find('select[name^="item_codes[]"]').select2('destroy');
   // var class_id=+row.find('select[name^="class_ids[]"]').val();
    $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetClassItemList') }}",
           data:{'class_id':class_id},
           success: function(data)
           {
                row.find('select[name^="item_codes[]"]').html(data.html);
                row.find('select[name^="item_codes[]"]').select2();
           }
       });
   
   }
   
   
   // $(document).on('change', 'select[name^="class_idss[]"]', function()
   // {CalculateQtyRowPros11($(this).closest("tr"));});
   function CalculateQtyRowPros11(row)
   {   
        var class_id = $(row).val();
        var row = $(row).closest('tr'); 
   
        row.find('select[name^="item_codess"]').select2('destroy');
   // var class_id=+row.find('select[name^="class_idss[]"]').val();
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetClassItemList') }}",
           data:{'class_id':class_id},
           success: function(data)
           {
                   console.log(data);
                   +row.find('select[name^="item_codess"]').html(data.html);
                    row.find('select[name^="item_codess"]').select2();
                   
           }
        });
   
   }
   
   // $(document).on('change', 'select[name^="class_idsx[]"]', function()
   // {CalculateQtyRowPros12($(this).closest("tr"));});
   function CalculateQtyRowPros12(row)
   {    
   
        var class_id = $(row).val();
        var row = $(row).closest('tr'); 
        row.find('select[name^="item_codesx"]').select2('destroy');
        
        var class_id=+row.find('select[name^="class_idsx[]"]').val();
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('GetClassItemList') }}",
           data:{'class_id':class_id},
           success: function(data)
           {
                   console.log(data);
                   +row.find('select[name^="item_codesx"]').html(data.html);
                    row.find('select[name^="item_codesx"]').select2();
                   
           }
        });
   
   }
   
   
   // For Sewing Trims get Consumption Details From Sales Costing Table
   $(document).on('change', 'select[name^="item_codes[]"],select[name^="color_ids[][]"],select[name^="size_ids[][]"]', function()
   {CalculateQtyRowPros2($(this).closest("tr"));});
   function CalculateQtyRowPros2(row)
   {   
   var item_code=+row.find('select[name^="item_codes[]"]').val();
   var color_id=row.find('select[name^="color_ids[][]"]').val().join(",");
   var size_id=row.find('select[name^="size_ids[][]"]').val().join(",");
   row.find('input[name^="color_arrays[]"]').val(color_id);
   row.find('input[name^="size_arrays[]"]').val(size_id);
   var sales_order_no=$('#sales_order_no').val();
   $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('ItemWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id},
           success: function(data)
           {
                   console.log(data);
                //   row.find('input[name^="descriptions[]"]').val(data[0]['description1']);
                   row.find('input[name^="consumptions[]"]').val(data[0]['consumption']);
                  
                   row.find('input[name^="wastages[]"]').val(data[0]['wastage']);
                   
                   row.find('input[name^="rate_per_units[]"]').val(data[0]['rate_per_unit']);
                 
                   row.find('select[name^="class_ids[]"]').val(data[0]['class_id']);
                   row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                 
                   var  wastage=data[0]['wastage'];
                   var  consumption=data[0]['consumption'];
                   var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                   var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                   row.find('input[name^="bom_qtys[]"]').val(bom_qty1);
                   row.find('input[name^="bom_qtys1[]"]').val(data[0]['bom_qty']);
                   var total_amount=(bom_qty1*data[0]['rate_per_unit']).toFixed(4)
                   
                   row.find('input[name^="total_amounts[]"]').val(total_amount);
                   
                   
                   
                    @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                    row.find('input[name^="consumptions[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                   row.find('input[name^="wastages[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                     row.find('input[name^="rate_per_units[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                   row.find('input[name^="bom_qtys[]"]').attr({"max" : bom_qty1,"min" : 0});
                   @php } @endphp
           }
       });
   
      setTimeout(mycalc,2000);
   
   }
   
   // For Fabric Trims get Consumption Details From Sales Costing Table
   $('table.footable_1').on('change', 'select[name^="item_code[]"], input[name^="consumption[]"],input[name^="rate_per_unit[]"]', function()
   {CalculateQtyRowPros1($(this).closest("tr"));});
   function CalculateQtyRowPros1(rows)
   {    
       var row = $(rows).parent().parent('tr');
       var item_code = $(row).find('select[name^="item_code[]"]').val();
       var sales_order_no=$('#sales_order_no').val();
       $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('FabricWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,sales_order_no:sales_order_no},
           success: function(data)
           {
                //   row.find('input[name^="description[]"]').val(data[0]['description1']);
                   row.find('input[name^="consumption[]"]').val(data[0]['consumption']);
                  
                   row.find('input[name^="wastage[]"]').val(data[0]['wastage']);
                  
                   row.find('input[name^="rate_per_unit[]"]').val(data[0]['rate_per_unit']);
                   
                   console.log(data[0]['class_id']);
                   row.find('select[name^="class_id[]"]').val(data[0]['class_id']).trigger('change');
                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']).trigger('change');
                   var  wastage=data[0]['wastage'];
                   var  consumption=data[0]['consumption'];
                   
                   var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                   
                   var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                        
                       
                   row.find('input[name^="bom_qty[]"]').val(bom_qty1);
                   row.find('input[name^="bom_qty1[]"]').val(data[0]['bom_qty']);
                  
                    $.ajax({
                           dataType: "json",
                       url: "{{ route('GetItemColorList') }}",
                       data:{'tr_code':sales_order_no,'item_code':item_code},
                       success: function(data2){
                       row.find('textarea[name^="colors[]"]').val(data2['color_name']);
                        
                      }
                       });
                   
                   row.find('input[name^="total_amount[]"]').val(bom_qty1*data[0]['rate_per_unit']);
                   
                   @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                    row.find('input[name^="consumption[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                    row.find('input[name^="wastage[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                    row.find('input[name^="rate_per_unit[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                    row.find('input[name^="bom_qty[]"]').attr({"max" : bom_qty1,"min" : 0});
                    @php } @endphp
                 recalcIdcone6();   
           }
       });
   
       $(row).find('select[name^="item_code[]"]').attr('disabled', true);
       $(row).find('select[name^="class_id[]"]').attr('disabled', true);
       $(row).find('select[name^="unit_id[]"]').attr('disabled', true);
      setTimeout(mycalc,2000);
   }
   
   
//   $(document).on("change", 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"],input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"],input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"],input[name^="consumptionsx[]"],input[name^="wastagesx[]"],input[name^="rate_per_unitsx[]"],input[name^="bom_qtysx[]"]', function (event) 
//   {
//   @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
//   var value = $(this).val();
   
//   var maxLength = parseFloat($(this).attr('max'));
//   var minLength = parseFloat($(this).attr('min')); 
//   if(value>maxLength){alert('Value can not be greater than '+maxLength);}
//   if ((value !== '') ) {
//       var value = parseFloat($(this).val()); // Ensure value is a number
//       if(parseFloat($(this).val()) <= maxLength)
//       { 
//           $(this).val(value);  
//       }
//       else
//       { 
//           var cons = isNaN(value) ? Math.max(Math.min(value, maxLength), minLength) : maxLength; // Handle invalid values
//           $(this).val(cons); // Set the calculated value back to the input
//       }
//   }
   
//   @php } @endphp
//   });
   
    $(document).on("change", 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"],input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"],input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"],input[name^="consumptionsx[]"],input[name^="wastagesx[]"],input[name^="rate_per_unitsx[]"],input[name^="bom_qtysx[]"]', function () {
        @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
       
        let $this = $(this);
        let rawVal = $this.val().trim();
    
        let maxVal = parseFloat($this.attr('max'));
        let minVal = parseFloat($this.attr('min'));
    
        // if input is empty → set default to max
        if (rawVal === "") {
            if (!isNaN(maxVal)) {
                $this.val(maxVal);
            } else if (!isNaN(minVal)) {
                $this.val(minVal);
            } else {
                $this.val(0);
            }
            return;
        }
    
        let value = parseFloat(rawVal);
    
        // handle invalid values
        if (isNaN(value)) {
            $this.val(!isNaN(maxVal) ? maxVal : (minVal || 0));
            return;
        }
    
        // enforce min/max
        if (!isNaN(maxVal) && value > maxVal) {
            alert('Value cannot be greater than ' + maxVal);
            $this.val(maxVal); 
            CalculateQtyRowPros($(this).closest("tr"));
            CalculateQtyRowPross($(this).closest("tr"));
            CalculateQtyRowProsx($(this).closest("tr"));
            CalculateQtyRowPros1($(this).closest("tr"));
        
        } else if (!isNaN(minVal) && value < minVal) {
            alert('Value cannot be less than ' + minVal);
            $this.val(minVal);
            CalculateQtyRowPros($(this).closest("tr"));
            CalculateQtyRowPross($(this).closest("tr"));
            CalculateQtyRowProsx($(this).closest("tr"));
            CalculateQtyRowPros1($(this).closest("tr"));
        } else {
            $this.val(value);
        }
    
        @php } else { @endphp
           $(this).removeAttr('max');
        @php } @endphp
        // CalculateQtyRowPros2($(this).closest("tr"));
        // CalculateQtyRowPro($(this).closest("tr"));
    });


   // For Packing Trims get Consumption Details From Sales Costing Table
   $(document).on('change', 'select[name^="item_codess[]"],select[name^="color_idss[][]"],select[name^="size_idss[][]"]', function()
   {CalculateQtyRowPros3($(this).closest("tr"));});
   function CalculateQtyRowPros3(row)
   {   
   var item_code=+row.find('select[name^="item_codess[]"]').val();
   var sales_order_no=$('#sales_order_no').val();
   var color_id=row.find('select[name^="color_idss[][]"]').val().join(",");
   var size_id=row.find('select[name^="size_idss[][]"]').val().join(",");
   
   
   row.find('input[name^="color_arrayss[]"]').val(color_id);
   row.find('input[name^="size_arrayss[]"]').val(size_id);
   
   $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('PackingWiseSalesOrderCosting') }}",
           data:{'item_code':item_code,sales_order_no:sales_order_no,'color_id':color_id,'size_id':size_id},
           success: function(data)
           {
                   console.log(data);
                //   row.find('input[name^="descriptionss[]"]').val(data[0]['description1']);
                   row.find('input[name^="consumptionss[]"]').val(data[0]['consumption']);
                   
                   row.find('input[name^="wastagess[]"]').val(data[0]['wastage']);
                  
                   row.find('select[name^="class_idss[]"]').val(data[0]['class_id']);
                   row.find('select[name^="unit_idss[]"]').val(data[0]['unit_id']);
                   row.find('input[name^="rate_per_unitss[]"]').val(data[0]['rate_per_unit']);
                   
                 // alert(data[0]['bom_qty']);
                  // var bom_qty=parseFloat(data[0]['bom_qty']);
                 
               //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                   var  wastage=data[0]['wastage'];
                   var  consumption=data[0]['consumption'];
                   var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                   var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                   row.find('input[name^="bom_qtyss[]"]').val(bom_qty1);
                   row.find('input[name^="bom_qtyss1[]"]').val(data[0]['bom_qty']);
                   var rate=data[0]['rate_per_unit'];
                   var total_amount=(bom_qty1*rate).toFixed(4);
                  // row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
                   
                   row.find('input[name^="total_amountss[]"]').val(total_amount);
                   
                   
                   
                    @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                   row.find('input[name^="consumptionss[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                    row.find('input[name^="wastagess[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                   row.find('input[name^="rate_per_unitss[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                   row.find('input[name^="bom_qtyss[]"]').attr({"max" : bom_qty,"min" : 0});
                   @php } @endphp
           }
       });
   
       setTimeout(mycalc,2000);
   
   }
   // $('#footable_4').on('change', '.item_packing_trims', function() 
   //  {
   //   var item_code = $(this).val();
   
   //   var row1 = $(this).closest('tr'); // get the row
   //     $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('ItemDetails') }}",
   //       data:{item_code:item_code},
   //         success: function(data1){
   
   //              console.log(data1); 
            
   //                 +row1.find('input[name^="unit_idss[]"]').val(data1[0]['unit_id']);
   //                  +row1.find('input[name^="descriptionss[]"]').val(data1[0]['item_description']);
   //               // row1.find('select[name^="descriptionss[]"]').attr('value', data[0]['item_description']);
              
   //          }
   //         });
   
   // });
   
   
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
    //var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
    var bom_qty=(parseFloat((+row.find('input[name^="bom_qtyss1[]"]').val())*consumption));
    var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4);
    var total_price=(bom_qty1*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtyss[]"]').val(bom_qty1);
    row.find('input[name^="total_amountss[]"]').val(total_price);
    setTimeout(mycalc,2000);
   }
   
   $('table.footable_3').on("keyup", 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"]', function()
   {
   // alert();
   CalculateQtyRowPros($(this).closest("tr"));
   
   });
   function CalculateQtyRowPros(row)
   {   
    var consumption=+row.find('input[name^="consumptions[]"]').val();
    var wastage=+row.find('input[name^="wastages[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
    //var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
    var bom_qty=(parseFloat((+row.find('input[name^="bom_qtys1[]"]').val())*consumption));
    var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4);
    var total_price=(bom_qty1*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtys[]"]').val(bom_qty1);
    row.find('input[name^="total_amounts[]"]').val(total_price);
    setTimeout(mycalc,2000);
   }
   
   $('table.footable_5').on("keyup", 'input[name^="consumptionsx[]"],input[name^="wastagesx[]"],input[name^="rate_per_unitsx[]"],input[name^="bom_qtysx[]"]', function()
   {
   // alert();
   CalculateQtyRowProsx($(this).closest("tr"));
   
   });
   function CalculateQtyRowProsx(row)
   {   
    var consumption=+row.find('input[name^="consumptionsx[]"]').val();
    var wastage=+row.find('input[name^="wastagesx[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unitsx[]"]').val();
    //var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
    var bom_qty=(parseFloat((+row.find('input[name^="bom_qtysx1[]"]').val())*consumption));
    var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4);
    var total_price=(bom_qty1*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtysx[]"]').val(bom_qty1);
    row.find('input[name^="total_amountsx[]"]').val(total_price);
    setTimeout(mycalc,2000);
   }
    
   var indexcone = 2;
   function insertcone1()
   {
    
   var table=document.getElementById("footable_1").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.type="text";
   t1.readOnly="true";
   t1.disabled="true";
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
   
   // ======== Item Code Select ========
    var cell2 = row.insertCell(2);

    // Destroy select2 before cloning
    rows1.find('select[name^="item_code[]"]').select2('destroy');
    
    // Clone the select element
    var y = rows1.find('select[name^="item_code[]"]').clone(false);
    
    // Set attributes
    y.attr({
        "id": "item_code" + indexcone,
        "name": "item_code[]"
    }).val("");
    
    // Enable it
    y.removeAttr('disabled');
    
    // Set width
    y.width(270);
    
    // Remove any existing Select2 container (safety)
    cell2.querySelectorAll('.select2-container').forEach(el => el.remove());
    
    // Append cloned select
    $(cell2).append(y);
    
    // Reinitialize Select2
    y.select2();
 
   var cell5 = row.insertCell(3);
   var t5=document.createElement("textarea");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "colors"+indexcone;
   t5.name="colors[]";
   cell5.appendChild(t5);   
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.readOnly="true";
   t5.id = "description"+indexcone;
   t5.name="description[]";
   cell5.appendChild(t5); 
   
   var cell5 = row.insertCell(5);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumption"+indexcone;
   t5.name="consumption[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(6);
   var t3=document.createElement("select");
   var x = $("#unit_id"),
   y = x.clone();
   y.attr("id","unit_id");
   y.attr("name","unit_id[]");
   y.width(100);
   y.disabled="true";
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;"; 
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_unit"+indexcone;
   t5.name="rate_per_unit[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastage"+indexcone;
   t5.name="wastage[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.readOnly="true";
   t5.id = "bom_qty"+indexcone;
   t5.name="bom_qty[]";
   cell5.appendChild(t5);
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qty1"+indexcone;
   t5.name="bom_qty1[]";
   cell5.appendChild(t5);
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qty_expect"+indexcone;
   t5.name="bom_qty_expect[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="FABRIC";
   t5.readOnly=true;
   t5.id = "total_amount"+indexcone;
   t5.name="total_amount[]";
   cell5.appendChild(t5); 
   
   
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   
   t5.id = "remark"+indexcone;
   t5.name="remark[]";
   cell5.appendChild(t5); 
   
   var cell6=row.insertCell(12);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone1()");
   cell6.appendChild(btnAdd);
   
   
   var cell7=row.insertCell(13);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
   cell7.appendChild(btnRemove);
   
   // var w = $(window);
   // var row = $('#footable_1').find('tr').eq(indexcone);
   
   // if (row.length){
   // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   // }
   
   document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
   
   indexcone++;
   recalcIdcone1();
   }
   
   // Start Sewing Trims----------------------------
   var indexcone1 = 2;
   function insertcone2(){
   $("#item_codes").select2("destroy");
   $("#class_ids").select2("destroy");
   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "ids"+indexcone1;
   t1.name= "ids[]";
   t1.type="text";
   t1.disabled="true";
   t1.value=indexcone1; 
   cell1.appendChild(t1);
   
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_ids"),
   y = x.clone();
   y.attr("id","class_ids");
   y.attr("name","class_ids[]");
   y.attr("disabled",false);
   y.width(200);
   y.appendTo(cell3);  
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#item_codes"),
   y = x.clone();
   y.attr("id","item_codes");
   y.attr("name","item_codes[]");
   y.attr("disabled",false);
   y.width(270);
   y.empty();
   y.append('<option value="">Select Item</option>');
   y.appendTo(cell3); 
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.readOnly="true";
   t5.id = "descriptions"+indexcone1;
   t5.name="descriptions[]";
   cell5.appendChild(t5); 
   
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_ids"),
   y = x.clone();
   y.attr("id","color_ids");
   y.attr("name","color_ids[][]");
   y.width(300);
   y.appendTo(cell3); 
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "color_arrays"+indexcone2;
   t5.name="color_arrays[]";
   cell3.appendChild(t5); 
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#size_ids"),
   y = x.clone();
   y.attr("id","size_ids");
   y.attr("name","size_ids[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "size_arrays"+indexcone2;
   t5.name="size_arrays[]";
   cell3.appendChild(t5); 
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumptions"+indexcone1;
   t5.name="consumptions[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_ids");
   y.attr("name","unit_ids[]");
   y.width(100);
   y.disabled="true";
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_units"+indexcone1;
   t5.name="rate_per_units[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastages"+indexcone1;
   t5.name="wastages[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "bom_qtys"+indexcone1;
   t5.name="bom_qtys[]";
   cell5.appendChild(t5);
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtys1"+indexcone1;
   t5.name="bom_qtys1[]";
   cell5.appendChild(t5);
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtys_expect"+indexcone1;
   t5.name="bom_qtys_expect[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="SEWING";
   t5.readOnly=true;
   t5.id = "total_amounts"+indexcone1;
   t5.name="total_amounts[]";
   cell5.appendChild(t5); 
   
   var cell5 = row.insertCell(12);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   
   t5.id = "remarks"+indexcone;
   t5.name="remarks[]";
   cell5.appendChild(t5); 
   
   var cell6=row.insertCell(13);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone2()");
   cell6.appendChild(btnAdd);
   
   var cell7=row.insertCell(14);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
   cell7.appendChild(btnRemove);
   
   // var w = $(window);
   // var row = $('#footable_3').find('tr').eq(indexcone1);
   
   // if (row.length){
   // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   // }
   
   document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;
   
   indexcone1++;
   recalcIdcone2();
   
   selselect1();
   selselect2();
   selselect3();
   }
   
   
   // Start Trim Fabric
   
   
   var indexcone1 = 2;
   function insertcone5(){
   
   $("#item_codesx").select2("destroy");
   $("#class_idsx").select2("destroy");
   var table=document.getElementById("footable_5").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "idsx"+indexcone1;
   t1.name= "idsx[]";
   t1.type="text";
   t1.disabled="true";
   t1.value=indexcone1;
   cell1.appendChild(t1);
   
   
   
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_idsx"),
   y = x.clone();
   y.attr("id","class_idsx");
   y.attr("name","class_idsx[]");
   y.attr("disabled",false);
   y.width(200);
   y.appendTo(cell3);
   
   
    var cell34 = row.insertCell(2);
    
    // Clone the existing select element
    var y = $("#item_codesx").clone();
    
    // Update attributes properly
    y.attr({
        id: '', // optional — keep blank to avoid duplicate IDs
        name: 'item_codesx[]',
        onchange: "checkDuplicateItemGeneric(this, 'footable_5', 'item_codesx[]');", 
        disabled:false
        
    });
    
    y.empty();
    y.append('<option value="">Select Item</option>');
    // Adjust width
    y.css('width', '270px');
    
    // Append to the cell
    $(cell34).append(y);

   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.readOnly="true";
   t5.id = "descriptionsx"+indexcone1;
   t5.name="descriptionsx[]";
   cell5.appendChild(t5); 
   
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_idsx"),
   y = x.clone();
   y.attr("id","color_idsx");
   y.attr("name","color_idsx[][]");
   y.width(300);
   y.appendTo(cell3); 
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "color_arraysx"+indexcone2;
   t5.name="color_arraysx[]";
   cell3.appendChild(t5); 
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#size_idsx"),
   y = x.clone();
   y.attr("id","size_idsx");
   y.attr("name","size_idsx[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "size_arraysx"+indexcone2;
   t5.name="size_arraysx[]";
   cell3.appendChild(t5); 
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumptionsx"+indexcone1;
   t5.name="consumptionsx[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_idsx");
   y.disabled=true;
   y.attr("name","unit_idsx[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_unitsx"+indexcone1;
   t5.name="rate_per_unitsx[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastagesx"+indexcone1;
   t5.name="wastagesx[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.readOnly=true;
   t5.id = "bom_qtysx"+indexcone1;
   t5.name="bom_qtysx[]";
   cell5.appendChild(t5);
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtysx1"+indexcone1;
   t5.name="bom_qtysx1[]";
   cell5.appendChild(t5);
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtysx_expect"+indexcone1;
   t5.name="bom_qtysx_expect[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="TRIMFABRIC";
   t5.readOnly=true;
   t5.id = "total_amountsx"+indexcone1;
   t5.name="total_amountsx[]";
   cell5.appendChild(t5); 
   
   var cell5 = row.insertCell(12);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   
   t5.id = "remarksx"+indexcone;
   t5.name="remarksx[]";
   cell5.appendChild(t5); 
   
   var cell6=row.insertCell(13);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone5()");
   cell6.appendChild(btnAdd);
   
   var cell7=row.insertCell(14);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone5(this)");
   cell7.appendChild(btnRemove);
   
   // var w = $(window);
   // var row = $('#footable_5').find('tr').eq(indexcone1);
   
   // if (row.length){
   // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   // }
   
   document.getElementById('cntrr5').value = parseInt(document.getElementById('cntrr5').value)+1;
   
   indexcone1++;
   recalcIdcone5();
   selselect1();
   selselect2();
   selselect3();
   }
   
   
   function selselect1()
   {
    setTimeout(function(){
    $("#footable_3 tr td  select[name='item_codes[]']").each(function() {
         $(this).closest("tr").find('select[name="item_codes[]"]').select2();
         $(this).closest("tr").find('select[name="class_ids[]"]').select2();
        });
    }, 1000);
   }
   
   function selselect2()
   {
    setTimeout(function(){
    $("#footable_4 tr td  select[name='item_codess[]']").each(function() {
         $(this).closest("tr").find('select[name="item_codess[]"]').select2();
          $(this).closest("tr").find('select[name="class_idss[]"]').select2();
        });
    }, 1000);
   }
   
   function selselect3()
   {
    setTimeout(function(){
    $("#footable_5 tr td  select[name='item_codesx[]']").each(function() {
         $(this).closest("tr").find('select[name="item_codesx[]"]').select2();
        $(this).closest("tr").find('select[name="class_idsx[]"]').select2();
        });
    }, 1000);
   }
   
   
   // Start Packing Trims----------------------------
   var indexcone2 = 2;
   function insertcone3(row){
   $("#item_codess").select2("destroy");
   $("#class_idss").select2("destroy");
   var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "idss"+indexcone2;
   t1.name= "idss[]";
   t1.type="text";
   t1.disabled="true";
   t1.value=indexcone2;
   cell1.appendChild(t1);
   
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#class_idss"),
   y = x.clone();
   y.attr("id","class_idss");
   y.attr("name","class_idss[]");
   y.attr("disabled",false);
   y.width(200);
   y.appendTo(cell3);
   
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#item_codess"),
   y = x.clone();
   y.attr("id","item_codess");
   y.attr("name","item_codess[]");
   y.attr("disabled",false);
   y.width(270);
   y.empty();
   y.append('<option value="">Select Item</option>');
   y.appendTo(cell3);
   
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.readOnly="true";
   t5.id = "descriptionss"+indexcone2;
   t5.name="descriptionss[]";
   cell5.appendChild(t5); 
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_idss"),
   y = x.clone();
   y.attr("id","color_idss");
   y.attr("name","color_idss[][]");
   y.width(300);
   y.appendTo(cell3);  
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "color_arrayss"+indexcone2;
   t5.name="color_arrayss[]";
   cell3.appendChild(t5); 
   
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#size_idss"),
   y = x.clone();
   y.attr("id","size_idss");
   y.attr("name","size_idss[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "size_arrayss"+indexcone2;
   t5.name="size_arrayss[]";
   cell3.appendChild(t5);
   
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "consumptionss"+indexcone2;
   t5.name="consumptionss[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_idss"),
   y = x.clone();
   y.attr("id","unit_idss");
   y.attr("name","unit_idss[]");
   y.width(100);
   y.disabled=true;
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "rate_per_unitss"+indexcone2;
   t5.name="rate_per_unitss[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.id = "wastagess"+indexcone2;
   t5.name="wastagess[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.readOnly=true;
   t5.id = "bom_qtyss"+indexcone2;
   t5.name="bom_qtyss[]";
   cell5.appendChild(t5);
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtyss1"+indexcone2;
   t5.name="bom_qtyss1[]";
   cell5.appendChild(t5);
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtyss_expect"+indexcone2;
   t5.name="bom_qtyss_expect[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="number";
   t5.step="any";
   t5.className="PACKING";
   t5.readOnly=true;
   t5.id = "total_amountss"+indexcone2;
   t5.name="total_amountss[]";
   cell5.appendChild(t5); 
   
   
   var cell5 = row.insertCell(12);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   
   t5.id = "remarkss"+indexcone;
   t5.name="remarkss[]";
   cell5.appendChild(t5); 
   
   var cell6=row.insertCell(13);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone3(this)");
   cell6.appendChild(btnAdd);
    
   var cell7=row.insertCell(14);
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
   cell7.appendChild(btnRemove);
   
   // var w = $(window);
   // var row = $('#footable_4').find('tr').eq(indexcone2);
   
   // if (row.length){
   // $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   // }
   
   document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;
   
   indexcone2++;
   recalcIdcone3();
   selselect1();
   selselect2();
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
   document.getElementById("fabric_value").value =sum1.toFixed(2);
   
   sum1x = 0.0;
   var amounts = document.getElementsByClassName('TRIMFABRIC');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1x += parseFloat(a);
   }
   
   document.getElementById("fabric_value").value =parseFloat(document.getElementById("fabric_value").value) + parseFloat(sum1x) ;
   
   
   
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
   
   var agent_commission_value=$("#agent_commission_value").val();
   var total_cost_value=parseFloat(sum1)+parseFloat(sum2)+parseFloat(sum3)+parseFloat(sum1x);
   $("#total_cost_value").val(total_cost_value.toFixed(2));
   }
   
   
   function calculateamount()
   {
   
   
   var prod_qty=document.getElementById('prod_qty').value;
   var rate_per_piece=document.getElementById('rate_per_piece').value;
   
   
   var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
   $('#total_amount').val(total_amount.toFixed(2));
   }
   
   
   
   
   
   
   function deleteRowcone1(btn) 
   {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       recalcIdcone1();
       setTimeout(mycalc,2000);
   }
   
   
   function deleteRowcone5(btn) {
   if(document.getElementById('cntrr5').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr5').value = document.getElementById('cntrr5').value-1;
   
   recalcIdcone5();
   
   if($("#cntrr5").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
   
   }
   setTimeout(mycalc,2000);
   }
   
   
   
   function deleteRowcone2(btn) {
   if(document.getElementById('cntrr2').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;
   
   recalcIdcone2();
   
   if($("#cntrr2").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
   
   }
   setTimeout(mycalc,2000);
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
   
   }
   setTimeout(mycalc,2000);
   }
   
   
   function recalcIdcone6(){
   $.each($("#footable_1 tr"),function (i,el){
   $(this).find("td:first input").val(i); 
   })
   }
   
   function recalcIdcone1(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); 
   })
   }
   
   function recalcIdcone2(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); 
   })
   }
   
   function recalcIdcone3(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); 
   })
   }
   
   
   function recalcIdcone5(){
   $.each($("#footable_5 tr"),function (i,el){
   $(this).find("td:first input").val(i); 
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