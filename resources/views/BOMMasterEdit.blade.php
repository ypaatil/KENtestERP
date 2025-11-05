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
    
    .text-right
    {
        text-align:right;
    }
    
    .hide
    {
        display:none;
    }
    
    .navbar-brand-box
    {
        width: 266px !important;
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
    
    .text-right
    {
        text-align:right;
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
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4" style="font-size:20px;">BOM ({{$BOMMasterList->bom_code}})</h4>
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
            @if(isset($BOMMasterList))
            <form action="{{ route('BOMMasterEditTrialUpdate') }}" method="POST" enctype="multipart/form-data" id="frmData" >
               @method('put')
               @csrf
               @php  $user_type=Session::get('user_type'); @endphp   
               <div class="row">
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="bom_code" class="form-label">BOM Code</label>
                        <input type="hidden" name="bom_code" class="form-control" id="bom_code" value="{{$BOMMasterList->bom_code}}" readOnly> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="bom_date" class="form-label">Entry Date</label> 
                        <input type="date" name="bom_date" class="form-control" id="bom_date" value="{{$BOMMasterList->bom_date}}" readOnly>
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $BOMMasterList->c_code }}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <input type="hidden" name="cost_type_id" value="{{$BOMMasterList->cost_type_id}}">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Sales Order no</label>
                        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
                        <select name="sales_order_no" class="form-control" id="sales_order_no" required  onChange="getSalesOrderDetails(this.value);"  disabled>
                           <option value="">--Sales Order No--</option>
                           @foreach($SalesOrderList as  $row)
                           {
                           <option value="{{ $row->sales_order_no }}"
                           {{ $row->sales_order_no == $BOMMasterList->sales_order_no ? 'selected="selected"' : '' }} 
                           >{{ $row->sales_order_no }}</option>
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
                        <label for="Ac_code" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $BOMMasterList->Ac_code ? 'selected="selected"' : '' }} 
                           >{{ $row->ac_name }}</option>
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
                        <select name="season_id" class="form-control" id="season_id" disabled>
                           <option value="">--Season--</option>
                           @foreach($SeasonList as  $row)
                           {
                           <option value="{{ $row->season_id }}"
                           {{ $row->season_id == $BOMMasterList->season_id ? 'selected="selected"' : '' }}   
                           >{{ $row->season_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency</label>
                        <select name="currency_id" class="form-control" id="currency_id" disabled>
                           <option value="">--Currency--</option>
                           @foreach($CurrencyList as  $row)
                           {
                           <option value="{{ $row->cur_id }}"
                           {{ $row->cur_id == $BOMMasterList->currency_id ? 'selected="selected"' : '' }}
                           >{{ $row->currency_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">FOB Rate</label>
                        <input type="number" name="order_rate" class="form-control" id="order_rate" value="{{$BOMMasterList->order_rate}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Style</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                           <option value="">--Select Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $BOMMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->mainstyle_name }}</option>
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
                           {{ $row->substyle_id == $BOMMasterList->substyle_id ? 'selected="selected"' : '' }}  
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
                           {{ $row->fg_id == $BOMMasterList->fg_id ? 'selected="selected"' : '' }} 
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{$BOMMasterList->style_no}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{$BOMMasterList->style_description}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2 hide">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Order Qty</label>
                        <input type="text" name="total_qty" class="form-control" id="total_qty" value="{{$BOMMasterList->total_qty}}" readOnly> 
                     </div>
                     </div
                  </div>
                  <div class="col-md-12">
                     <h4 class="panel-title" style="padding: 10px;background: #f5f5f5;"> Order Qty </h4>
                     <table id="footable_2" class="table  table-bordered m-b-0  footable_2">
                        <thead>
                           <tr>
                              <th class="text-center">Sr No</th>
                              <th>Garment Color</th>
                              @foreach ($SizeDetailList as $sz) 
                              <th class="text-center">{{$sz->size_name}}</th>
                              @endforeach
                              <th class="text-center">Total Qty</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php   $no=1;  @endphp
                           @foreach ($MasterdataList as $row) 
                           <tr>
                              <td class="text-center">{{$no}}</td>
                              <td>{{$row->color_name}}</td>
                              @if(isset($row->s1))  
                              <td class="text-right">{{$row->s1}}</td>
                              @endif
                              @if(isset($row->s2)) 
                              <td class="text-right">{{$row->s2}}</td>
                              @endif
                              @if(isset($row->s3)) 
                              <td class="text-right">{{$row->s3}}</td>
                              @endif
                              @if(isset($row->s4)) 
                              <td class="text-right">{{$row->s4}}</td>
                              @endif
                              @if(isset($row->s5)) 
                              <td class="text-right">{{$row->s5}}</td>
                              @endif
                              @if(isset($row->s6)) 
                              <td class="text-right">{{$row->s6}}</td>
                              @endif
                              @if(isset($row->s7)) 
                              <td class="text-right">{{$row->s7}}</td>
                              @endif
                              @if(isset($row->s8)) 
                              <td class="text-right">{{$row->s8}}</td>
                              @endif
                              @if(isset($row->s9)) 
                              <td class="text-right">{{$row->s9}}</td>
                              @endif
                              @if(isset($row->s10)) 
                              <td class="text-right">{{$row->s10}}</td>
                              @endif
                              @if(isset($row->s11)) 
                              <td class="text-right">{{$row->s11}}</td>
                              @endif
                              @if(isset($row->s12)) 
                              <td class="text-right">{{$row->s12}}</td>
                              @endif
                              @if(isset($row->s13)) 
                              <td class="text-right">{{$row->s13}}</td>
                              @endif
                              @if(isset($row->s14)) 
                              <td class="text-right">{{$row->s14}}</td>
                              @endif
                              @if(isset($row->s15)) 
                              <td class="text-right">{{$row->s15}}</td>
                              @endif
                              @if(isset($row->s16)) 
                              <td class="text-right">{{$row->s16}}</td>
                              @endif
                              @if(isset($row->s17)) 
                              <td class="text-right">{{$row->s17}}</td>
                              @endif
                              @if(isset($row->s18)) 
                              <td class="text-right">{{$row->s18}}</td>
                              @endif
                              @if(isset($row->s19)) 
                              <td class="text-right">{{$row->s19}}</td>
                              @endif
                              @if(isset($row->s20))  
                              <td class="text-right">{{$row->s20}}</td>
                              @endif
                              <td class="text-right">{{$row->size_qty_total}}</td>
                           </tr>
                           @php $no=$no+1; @endphp
                           @endforeach
                           <tr  style="background-color:#eee; text-align:center;">
                              <th></th>
                              <th style="float:right;">Total</th>
                              @php 
                              $SizeWsList=explode(',', $BuyerPurchaseOrderMasterList->sz_ws_total);
                              @endphp
                              @foreach($SizeWsList  as $sztotal)
                              <th style="text-align:right;">{{ $sztotal }}</th>
                              @endforeach
                              <th class="text-right">{{ $BuyerPurchaseOrderMasterList->total_qty }}</th>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <div class="row">
                     <div class="  ">
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
                                             <table id="footable_1" class="table  table-bordered m-b-0  footable_1">
                                                <thead>
                                                   <tr>
                                                      <th>Sr No</th>
                                                      <th>Item Name</th>
                                                      <th>Garment Colors</th>
                                                      <th>Classification</th>
                                                      <th>Description</th>
                                                      <th>Cons(Mtr/Nos)</th>
                                                      <th>UOM</th>
                                                      <th>Rate</th>
                                                      <th>Wastage %</th>
                                                      <th>BOM Qty</th>
                                                      <th>Total Amount</th>
                                                      <th>Remark</th>
                                                      <th>Add</th>
                                                      <th>Remove</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @if(count($FabricList)>0)
                                                   @php $no=1;$oldArray=[]; @endphp
                                                   @foreach($FabricList as $List) 
                                                   <tr>
                                                      <td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;" readonly/>
                                                         <input type="hidden" name="sr_no_bom[]" value="@php echo $no; @endphp" id="sr_no_bom" style="width:50px;"/>
                                                      </td>
                                                      <td>
                                                         <select name="item_code[]"   id="item_code" onchange="checkDuplicateItemGeneric(this, 'footable_1', 'item_code[]');CheckDuplicateItemForFabric(this);" style="width:270px; height:30px;" required @php if($List->item_count>0){ echo 'disabled'; } @endphp disabled >
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList1 as  $row)
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }} 
                                                         > ({{$row->item_code}}) {{ $row->item_name }}</option>
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      @php
                                                      $ColorLists = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
                                                      'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                                                      ->where('item_code','=',$List->item_code)->where('tr_code','=',$BOMMasterList->sales_order_no)->DISTINCT()->get();
                                                      $data='';
                                                      foreach($ColorLists as $row)
                                                      {
                                                      $data=$data.$row->color_name.', ';
                                                      }
                                                      @endphp
                                                      <td><textarea type="text"  name="colors[]"   id="colors" style="width:200px; height:30px;" readonly>{{$data}}</textarea></td>
                                                      <td>
                                                         <select name="class_id[]"   id="class_id" style="width:200px; height:30px;" disabled>
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList5 as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}"
                                                            {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }} 
                                                            >{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text"    name="description[]" value="{{$List->description}}" id="description" style="width:200px; height:30px;" readOnly/></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="{{$List->consumption}}" @endif name="consumption[]" value="{{$List->consumption}}" id="consumption" style="width:80px; height:30px;" /></td>
                                                      <td>
                                                         <select name="unit_id[]" class=" " id="unit_id" style="width:100px; height:30px;" disabled>
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}"
                                                            {{ $row->unit_id == $List->unit_id ? 'selected="selected"' : '' }} 
                                                            >{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1)  min="0" max="{{$List->rate_per_unit}}" @endif name="rate_per_unit[]" value="{{$List->rate_per_unit}}" id="rate_per_unit" style="width:80px; height:30px;"  /></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="{{$List->wastage}}" @endif name="wastage[]" value="{{$List->wastage}}" id="wastage" style="width:80px; height:30px;" /></td>
                                                      <td><input type="number" step="any"  min="0" max="{{$List->bom_qty}}"    name="bom_qty[]" value="{{$List->bom_qty}}" id="bom_qty" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qty1[]" value="{{$List->item_qty}}" id="bom_qty1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qty_expect[]" value="0" id="bom_qty_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any" class="FABRIC"   name="total_amount[]" value="{{$List->total_amount}}" id="total_amount" style="width:80px; height:30px;" readOnly/></td>
                                                      <td><input type="text" name="remark[]" value="{{$List->remark}}" id="remark" style="width:80px; height:30px;" /></td>
                                                      <td><input type="button" readOnly name="Fbutton[]"   class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" @php if($List->item_count_fab >0){ echo 'disabled'; } @endphp></td>
                                                   </tr>
                                                   @php 
                                                   $oldArray[]=['sr_no'=>$no,'item_code'=>$List->item_code,'consumption'=>$List->consumption,'description'=>$List->description,'rate_per_unit'=>$List->rate_per_unit,'wastage'=>$List->wastage,'bom_qty'=>$List->bom_qty,'total_amount'=>$List->total_amount];
                                                   $no=$no+1; 
                                                   @endphp
                                                   @endforeach
                                                   <input type="hidden" name="fabric_old_data" value="{{  htmlspecialchars(json_encode($oldArray), ENT_QUOTES, 'UTF-8'); }}" id="fabric_old_data"/>
                                                   @else
                                                   <tr>
                                                      <td><input type="text" name="id" value="1" id="id" style="width:50px;" readonly/></td>
                                                      <td>
                                                         <select name="item_code[]"  onchange="checkDuplicateItemGeneric(this, 'footable_1', 'item_code[]')"  id="item_code" style="width:270px; height:30px;" required>
                                                            <option value="">--Item List--</option>
                                                            @foreach($ItemList1 as  $row)
                                                            {
                                                            <option value="{{ $row->item_code }}"
                                                               >({{$row->item_code}}) {{ $row->item_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><textarea type="text"  name="colors[]"   id="colors" style="width:300px; height:30px;" required ></textarea></td>
                                                      <td>
                                                         <select name="class_id[]"   id="class_id" style="width:200px; height:30px;" disabled>
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}"
                                                               >{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text"    name="description[]" value="" id="description" style="width:200px; height:30px;" readOnly /></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0" @endif  name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;"  /></td>
                                                      <td>
                                                         <select name="unit_id[]"   id="unit_id" style="width:100px; height:30px;" disabled>
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1)  min="0" max="0" @endif  name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px; height:30px;"/></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0" @endif name="wastage[]" value="0" id="wastage" style="width:80px; height:30px;" required /></td>
                                                      <td><input type="number" step="any" name="bom_qty[]" value="0" id="bom_qty" style="width:80px; height:30px;" readOnly />
                                                         <input type="hidden" name="bom_qty1[]" value="0" id="bom_qty1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qty_expect[]" value="0" id="bom_qty_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any"  class="FABRIC"  name="total_amount[]" value="0" id="total_amount" style="width:80px; height:30px;" readOnly/></td>
                                                      <td><input type="text" name="remark[]" value="0" id="remark" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button" readOnly name="Fbutton[]"  class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                                                   </tr>
                                                   @endif
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- rim Fabric Details Starts -->
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
                                             <table id="footable_5" class="table  table-bordered m-b-0  footable_5">
                                                <thead>
                                                   <tr>
                                                      <th>Sr No</th>
                                                      <th>Classification</th>
                                                      <th>Item Name</th>
                                                      <th>Description</th>
                                                      <th>Garment Color</th>
                                                      <th>Size</th>
                                                      <th>Cons(Mtr/Nos)</th>
                                                      <th>UOM</th>
                                                      <th>Rate</th>
                                                      <th>Wastage %</th>
                                                      <th>BOM Qty</th>
                                                      <th>Total Amount</th>
                                                      <th>Remark</th>
                                                      <th>Add</th>
                                                      <th>Remove</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @if(count($TrimFabricList)>0)
                                                   @php $no=1; @endphp
                                                   @foreach($TrimFabricList as $List) 
                                                   @php 
                                                   $ItemList4 = DB::table('item_master')->where('delflag','=', '0')->where('class_id','=', $List->class_id)->get(); 
                                                   @endphp
                                                   <tr>
                                                      <td><input type="text" name="idsx" value="@php echo $no; @endphp" id="idsx" style="width:50px;" readonly/></td>
                                                      <td>
                                                         <select name="class_idsx[]" class="select2"  id="class_idsx" style="width:200px; height:30px;"  onchange="CalculateQtyRowPros123(this);"  >
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}"
                                                            {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}
                                                            >{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codesx[]"  onchange="checkDuplicateItemGeneric(this, 'footable_5', 'item_codesx[]')" class="item_trim_fabric select2" id="item_codesx" style="width:270px; height:30px;"   @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList4 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >({{$row->item_code}}) {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text"    name="descriptionsx[]" value="{{$List->description}}" id="descriptionsx" style="width:200px; height:30px;" readOnly/></td>
                                                      <td>
                                                         <select name="color_idsx[][]"   id="color_idsx" style="width:300px; height:140px;"    multiple>
                                                            <option value="">--Color List--</option>
                                                            @php $color_ids = explode(',', $List->color_id);   @endphp
                                                            @foreach($ColorList as  $row)
                                                            {
                                                            <option value="{{ $row->color_id }}"
                                                            @if(in_array($row->color_id, $color_ids)) selected @endif  
                                                            >{{ $row->color_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden" name="color_arraysx[]" value="{{$List->color_id}}" id="color_arraysx" style="width:80px; height:30px;" readOnly   />
                                                      </td>
                                                      <td>
                                                         <select name="size_idsx[][]"   id="size_idsx" style="width:200px; height:140px;"   multiple>
                                                            <option value="">--Size List--</option>
                                                            @php $size_ids = explode(',', $List->size_array);   @endphp
                                                            @foreach($SizeDetailList as  $row)
                                                            {
                                                            <option value="{{ $row->size_id }}"
                                                            @if(in_array($row->size_id, $size_ids)) selected @endif  
                                                            >{{ $row->size_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden"  name="size_arraysx[]" value="{{$List->size_array}}" id="size_arraysx" style="width:80px; height:30px;" readOnly   />
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="{{$List->consumption}}" @endif   name="consumptionsx[]"  value="{{$List->consumption}}"   style="width:80px; height:30px;" /></td>
                                                      <td>
                                                         <select name="unit_idsx[]"   id="unit_idsx" style="width:100px; height:30px;"   disabled >
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}"
                                                            {{ $row->unit_id == $List->unit_id ? 'selected="selected"' : '' }}
                                                            >{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any"  @if(Session::get('user_type')!=1) min="0" max="{{$List->rate_per_unit}}" @endif name="rate_per_unitsx[]" value="{{$List->rate_per_unit}}" id="rate_per_unitsx" style="width:80px; height:30px;" readonly/></td>
                                                      <td><input type="number" step="any"  @if(Session::get('user_type')!=1)  min="0" max="{{$List->wastage}}"  @endif name="wastagesx[]" value="{{$List->wastage}}" id="wastagesx" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="number" step="any"  @if(Session::get('user_type')!=1) min="0" max="{{$List->bom_qty}}"  @endif name="bom_qtysx[]" value="{{$List->bom_qty}}" id="bom_qtysx" style="width:80px; height:30px;" readOnly/> 
                                                         <input type="hidden" name="bom_qtysx1[]" value="{{$List->item_qty}}" id="bom_qtysx1" style="width:80px; height:30px;"  readOnly/>
                                                         <input type="hidden" name="bom_qtysx1_expect[]" value="0" id="bom_qtysx1_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any" class="TRIMFABRIC"   name="total_amountsx[]" value="{{$List->total_amount}}" id="total_amountsx" style="width:80px; height:30px;"   readOnly/></td>
                                                      <td><input type="text" name="remarksx[]" value="{{$List->remark}}" id="remarksx" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button" readOnly name="Tbutton[]" class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone5(this);" value="X" @php if($List->item_count_trim_fab >0){ echo 'disabled'; } @endphp></td>
                                                   </tr>
                                                   @php $no=$no+1;  @endphp
                                                   @endforeach
                                                   @else
                                                   <tr>
                                                      <td><input type="text" name="idsx" value="1" id="idsx" style="width:50px;" readonly/></td>
                                                      <td>
                                                         <select name="class_idsx[]"  class="select2"  id="class_idsx" style="width:200px; height:30px;"  onchange="CalculateQtyRowPros123(this);" >
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}"
                                                               >{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codesx[]" onchange="checkDuplicateItemGeneric(this, 'footable_5', 'item_codesx[]')" class="item_trim_fabric select2" id="item_codesx" style="width:270px; height:30px;"  >
                                                            <option value="">--Item List--</option>
                                                            @foreach($ItemList4 as  $row)
                                                            {
                                                            <option value="{{ $row->item_code }}"
                                                               >({{$row->item_code}}) {{ $row->item_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text"    name="descriptionsx[]" value="" id="descriptionsx" style="width:200px; height:30px;"   readOnly /></td>
                                                      <td>
                                                         <select name="color_idsx[][]"   id="color_idsx" style="width:300px; height:140px;"   multiple>
                                                            <option value="">--Color List--</option>
                                                            @foreach($ColorList as  $row)
                                                            {
                                                            <option value="{{ $row->color_id }}"
                                                               >{{ $row->color_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="text"     name="color_arraysx[]" value="0" id="color_arraysx" style="width:80px; height:30px;"   />
                                                      </td>
                                                      <td>
                                                         <select name="size_idsx[][]"   id="size_idsx" style="width:200px; height:140px;"   multiple>
                                                            <option value="">--Size List--</option>
                                                            @foreach($SizeDetailList as  $row)
                                                            {
                                                            <option value="{{ $row->size_id }}"
                                                               >{{ $row->size_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="text"  name="size_arraysx[]" value="0" id="size_arraysx" style="width:80px; height:30px;"   />
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0" @endif   name="consumptionsx[]" value="0" style="width:80px; height:30px;"/></td>
                                                      <td>
                                                         <select name="unit_idsx[]" class=" " id="unit_idsx" style="width:100px; height:30px;" disabled  >
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0"  @endif  name="rate_per_unitsx[]" value="0" id="rate_per_unitsx" style="width:80px; height:30px;"/></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1)  min="0" max="0" @endif name="wastagesx[]" value="0" id="wastagesx" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="number" step="any" name="bom_qtysx[]" value="0" id="bom_qtysx" style="width:80px; height:30px;"   readOnly /> 
                                                         <input type="hidden" name="bom_qtysx1[]" value="0" id="bom_qtysx1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtysx1_expect[]" value="0" id="bom_qtysx1_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any"  class="TRIMFABRIC"  name="total_amountsx[]" value="0" id="total_amountsx" style="width:80px; height:30px;"   readOnly/></td>
                                                      <td><input type="text"      name="remarksx[]" value="0" id="remarksx" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button"  readOnly name="Tbutton[]" class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone5(this);" value="X" ></td>
                                                   </tr>
                                                   @endif
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!--Trim Fabric Ends -->
                           <div class="panel panel-default">
                              <div class="panel-heading">
                                 <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Sewing Trims: </a>
                                 </h4>
                              </div>
                              <div id="collapse2" class="panel-collapse collapse">
                                 <div class="panel-body">
                                    <div class="row">
                                       <input type="number" value="{{count($SewingTrimsList) }}" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
                                       <div class="table-wrap">
                                          <div class="table-responsive">
                                             <table id="footable_3" class="table  table-bordered m-b-0  footable_3">
                                                <thead>
                                                   <tr>
                                                      <th>Sr No</th>
                                                      <th>Classification</th>
                                                      <th>Item Name</th>
                                                      <th>Description</th>
                                                      <th>Garment Color</th>
                                                      <th>Size</th>
                                                      <th>Cons(Mtr/Nos)</th>
                                                      <th>UOM</th>
                                                      <th>Rate</th>
                                                      <th>Wastage %</th>
                                                      <th>BOM Qty</th>
                                                      <th>Total Amount</th>
                                                      <th>Remark</th>
                                                      <th>Add</th>
                                                      <th>Remove</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                   $PoSewingMatrixData = DB::selectOne("
                                                   SELECT GREATEST(level2_percentage, level3_percentage) as max_percentage
                                                   FROM so_po_authority_matrix
                                                   WHERE sales_order_no = ? 
                                                   AND ac_code = ? 
                                                   AND brand_id = ? AND cat_id = ?
                                                   ", [$BOMMasterList->sales_order_no, $BOMMasterList->ac_code, $BOMMasterList->brand_id, 2]);
                                                   $maxValue = $PoSewingMatrixData->max_percentage ?? $List->wastage;
                                                   @endphp
                                                   @if(count($SewingTrimsList)>0)
                                                   @php $no=1;$oldSewingArray=[]; @endphp
                                                   @foreach($SewingTrimsList as $List) 
                                                   @php 
                                                   $color_ids = explode(',', $List->color_id); 
                                                   $ItemList2= DB::table('item_master')->where('delflag','=', '0')->where('class_id','=', $List->class_id)->get(); 
                                                   @endphp
                                                   <tr>
                                                      <td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;" readonly/>
                                                         <input type="hidden" name="sr_no_sewing_trims[]" value="@php echo $no; @endphp" id="sr_no_sewing_trims" style="width:50px;"/>
                                                      </td>
                                                      <td>
                                                         <select name="class_ids[]"  class="select2" id="class_ids" style="width:200px; height:30px;" required  onchange="CalculateQtyRowPros10(this)" @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Classification--</option>
                                                         @foreach($ClassList2 as  $row)
                                                         {
                                                         <option value="{{ $row->class_id }}"
                                                         {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}
                                                         >{{ $row->class_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codes[]"  onchange="checkDuplicateItemGeneric(this, 'footable_3', 'item_codes[]')" class="select2" id="item_codes" style="width:270px; height:30px;" required @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList2 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >({{$row->item_code}}) {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text" name="descriptions[]" value="{{$List->description}}" id="descriptions" style="width:200px; height:30px;" readOnly /></td>
                                                      <td>
                                                         <select name="color_ids[][]"   id="color_ids" style="width:300px; height:140px;"   multiple @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Color List--</option>
                                                         @foreach($ColorList as  $row)
                                                         {
                                                         <option value="{{ $row->color_id }}"
                                                         @if(in_array($row->color_id, $color_ids)) selected @endif  
                                                         >{{ $row->color_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                         <input type="hidden" name="color_arrays[]" value="{{$List->color_id}}" id="color_arrays" style="width:80px; height:30px;" readOnly />
                                                      </td>
                                                      <td>
                                                         <select name="size_ids[][]"  id="size_ids" style="width:200px; height:140px;" required multiple @php if($List->item_count>0){ echo 'disabled'; } @endphp> 
                                                         <option value="">--Size List--</option>
                                                         @php $size_ids = explode(',', $List->size_array);   @endphp
                                                         @foreach($SizeDetailList as  $row)
                                                         {
                                                         <option value="{{ $row->size_id }}"
                                                         @if(in_array($row->size_id, $size_ids)) selected @endif  
                                                         >{{ $row->size_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                         <input type="hidden"  name="size_arrays[]" value="{{$List->size_array}}" id="size_arrays" style="width:80px; height:30px;" readOnly />
                                                      </td>
                                                      <td><input type="number" step="any"  min="0" max="{{$List->consumption}}"  name="consumptions[]"  value="{{$List->consumption}}" id="consumptions" style="width:80px; height:30px;" @php if($List->item_count>0){ echo 'readOnly'; } @endphp /></td>
                                                      <td>
                                                         <select name="unit_ids[]" class=" " id="unit_ids" style="width:100px; height:30px;" disabled>
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}"
                                                            {{ $row->unit_id == $List->unit_id ? 'selected="selected"' : '' }}
                                                            >{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="{{$List->rate_per_unit}}" @endif   name="rate_per_units[]" value="{{$List->rate_per_unit}}" id="rate_per_units" style="width:80px; height:30px;"  @php if($List->item_count>0){ echo 'readOnly'; } @endphp/></td>
                                                      <td><input type="number" step="any" name="wastages[]" value="{{$List->wastage}}" min="0" max="{{$List->wastage}}" id="wastages" style="width:80px; height:30px;" required  @php if($List->item_count>0){ echo 'readOnly'; } @endphp/></td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="{{$List->bom_qty}}"  @endif name="bom_qtys[]" value="{{$List->bom_qty}}" id="bom_qtys" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtys1[]" value="{{$List->item_qty}}" id="bom_qtys1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtys_expect[]" value="0" id="bom_qtys_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any" class="SEWING"   name="total_amounts[]" value="{{$List->total_amount}}" id="total_amounts" style="width:80px; height:30px;" readOnly/></td>
                                                      <td><input type="text"      name="remarks[]" value="{{$List->remark}}" id="remarks" style="width:80px; height:30px;"   /></td>
                                                      <td><input   name="Sbutton[]"    type="button"   class="btn btn-warning pull-left" value="+" /></td>
                                                      <td><input type="button" @php if($List->item_count>0){ echo 'disabled'; } @endphp class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                                                   </tr>
                                                   @php
                                                   $oldSewingArray[]=['sr_no'=>$no,'class_id'=>$List->class_id,'item_code'=>$List->item_code,
                                                   'color_id'=>implode(',', $color_ids),'size_array'=>implode(',', $size_ids),'consumption'=>$List->consumption,'rate_per_unit'=>$List->rate_per_unit,
                                                   'wastage'=>$List->wastage,'bom_qty'=>$List->bom_qty,'total_amount'=>$List->total_amount,'remark'=>$List->remark];
                                                   $no=$no+1; 
                                                   @endphp
                                                   @endforeach
                                                   <input type="hidden" name="sewing_old_data" value="{{  htmlspecialchars(json_encode($oldSewingArray), ENT_QUOTES, 'UTF-8'); }}" id="sewing_old_data"/>
                                                   @else
                                                   <tr>
                                                      <td><input type="text" name="ids" value="1" id="id" style="width:50px;" readonly/></td>
                                                      <td>
                                                         <select name="class_ids[]" class="select2"  id="class_ids" style="width:200px; height:30px;" required onchange="CalculateQtyRowPros10(this)" >
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList2 as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codes[]"  onchange="checkDuplicateItemGeneric(this, 'footable_3', 'item_codes[]')" class="item_sewing_trims class="select2"" id="item_codes" style="width:270px; height:30px;" required>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList2 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}">({{$row->item_code}}) {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="text" name="descriptions[]" value="" id="descriptions" style="width:200px; height:30px;" readOnly /></td>
                                                      <td>
                                                         <select name="color_ids[][]"  id="color_ids" style="width:300px; height:100px;"  multiple>
                                                            <option value="">--Color List--</option>
                                                            @foreach($ColorList as  $row)
                                                            {
                                                            <option value="{{ $row->color_id }}"
                                                               >{{ $row->color_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden" name="color_arrays[]" value="0" id="color_arrays" style="width:80px; height:30px;" required />
                                                      </td>
                                                      <td>
                                                         <select name="size_ids[][]" class="" id="size_ids" style="width:200px; height:140px;"  multiple>
                                                            <option value="">--Size List--</option>
                                                            @foreach($SizeDetailList as  $row)
                                                            {
                                                            <option value="{{ $row->size_id }}"
                                                               >{{ $row->size_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden"  name="size_arrays[]" value="0" id="size_arrays" style="width:80px; height:30px;" required />
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0" @endif  name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;"/></td>
                                                      <td>
                                                         <select name="unit_ids[]" class="" id="unit_ids" style="width:100px; height:30px;"  disabled>
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1)  min="0" max="0"  @endif  name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;"/></td>
                                                      <td><input type="number" step="any" min="0" name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td>
                                                      <td><input type="number" step="any" name="bom_qtys[]" value="0" id="bom_qtys" style="width:80px; height:30px;" readOnly />
                                                         <input type="hidden" name="bom_qtys1[]" value="0" id="bom_qtys1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtys_expect[]" value="0" id="bom_qtys_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" readOnly/></td>
                                                      <td><input type="text"      name="remarks[]" value="0" id="remarks" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button"  readOnly name="Sbutton[]" class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
                                                   </tr>
                                                   @endif
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
                                       <input type="number" value="{{count($PackingTrimsList)}}" name="cntrr3" id="cntrr3" readonly="" hidden="true"  />
                                       <div class="table-wrap">
                                          <div class="table-responsive">
                                             <table id="footable_4" class="table  table-bordered m-b-0  footable_4">
                                                <thead>
                                                   <tr>
                                                      <th>Sr No</th>
                                                      <th>Classification</th>
                                                      <th>Item Name</th>
                                                      <th>Description</th>
                                                      <th>Garment Color</th>
                                                      <th>Size</th>
                                                      <th>Cons(Mtr/Nos)</th>
                                                      <th>UOM</th>
                                                      <th>Rate</th>
                                                      <th>Wastage %</th>
                                                      <th>BOM Qty</th>
                                                      <th>Total Amount</th>
                                                      <th>Remark</th>
                                                      <th>Add</th>
                                                      <th>Remove</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @php
                                                  // $PoPackingMatrixData = DB::selectOne("
                                                  // SELECT GREATEST(level2_percentage, level3_percentage) as max_percentage
                                                  // FROM so_po_authority_matrix
                                                  // WHERE sales_order_no = ? 
                                                  // AND ac_code = ? 
                                                  // AND brand_id = ? AND cat_id = ?
                                                  // ", [$BOMMasterList->sales_order_no, $BOMMasterList->ac_code, $BOMMasterList->brand_id, 1]);
                                                   //$maxPackingValue = $PoPackingMatrixData->max_percentage ?? $List->wastage;
                                                   @endphp
                                                   @if(count($PackingTrimsList)>0)
                                                   @php $no=1; $oldPackingTrimsArray=[]; @endphp
                                                   @foreach($PackingTrimsList as $List) 
                                                   @php 
                                                   $ItemList3= DB::table('item_master')->where('delflag','=', '0')->where('class_id','=', $List->class_id)->get(); 
                                                   @endphp
                                                   <tr>
                                                      <td><input type="text" name="idss" value="{{ $no }}" id="id" style="width:50px;" readonly/>
                                                         <input type="hidden" name="sr_no_packing_trims[]" value="@php echo $no; @endphp" id="sr_no_packing_trims" style="width:50px;"/>
                                                      </td>
                                                      <td>
                                                         <select name="class_idss[]"  class="select2" id="class_idss" style="width:200px; height:30px;" required onchange="CalculateQtyRowPros11(this);" @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Classification--</option>
                                                         @foreach($ClassList3 as  $row)
                                                         {
                                                         <option value="{{ $row->class_id }}"
                                                         {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}
                                                         >{{ $row->class_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codess[]"  onchange="checkDuplicateItemGeneric(this, 'footable_4', 'item_codess[]')" class="item_packing_trims id select2" id="item_codess" style="width:270px; height:30px;" required @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList3 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >({{$row->item_code}}) {{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <input type="text" name="descriptionss[]" value="{{$List->description}}" id="descriptionss" style="width:200px; height:30px;" readOnly />
                                                      </td>
                                                      <td>
                                                         <select name="color_idss[][]"   id="color_idss" style="width:300px; height:140px;" required multiple @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Color List--</option>
                                                         @php $color_ids = explode(',', $List->color_id);   @endphp
                                                         @foreach($ColorList as  $row)
                                                         {
                                                         <option value="{{ $row->color_id }}"
                                                         @if(in_array($row->color_id, $color_ids)) selected @endif  
                                                         >{{ $row->color_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                         <input type="hidden" name="color_arrayss[]" value="{{$List->color_id}}" id="color_arrayss" style="width:80px; height:30px;" required />
                                                      </td>
                                                      <td>
                                                         <select name="size_idss[][]"   id="size_idss" style="width:200px; height:140px;" required multiple @php if($List->item_count>0){ echo 'disabled'; } @endphp>
                                                         <option value="">--Size List--</option>
                                                         @php $size_idss = explode(',', $List->size_array);   @endphp
                                                         @foreach($SizeDetailList as  $row)
                                                         {
                                                         <option value="{{ $row->size_id }}"
                                                         @if(in_array($row->size_id, $size_idss)) selected @endif  
                                                         >{{ $row->size_name }}</option>
                                                         }
                                                         @endforeach
                                                         </select>
                                                         <input type="hidden" name="size_arrayss[]" value="{{$List->size_array}}" id="size_arrayss" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1)  min="0" max="{{$List->consumption}}" @endif name="consumptionss[]" value="{{$List->consumption}}" id="consumptionss" style="width:80px; height:30px;" /></td>
                                                      <td>
                                                         <select name="unit_idss[]"  id="unit_idss" style="width:100px; height:30px;" disabled>
                                                            <option value="">--Unit List--</option>
                                                            @foreach($UnitList as  $row)
                                                            {
                                                            <option value="{{ $row->unit_id }}"
                                                            {{ $row->unit_id == $List->unit_id ? 'selected="selected"' : '' }}
                                                            >{{ $row->unit_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td><input type="number" step="any"  @if(Session::get('user_type')!=1) min="0" max="{{$List->rate_per_unit}}"   @endif name="rate_per_unitss[]" value="{{$List->rate_per_unit}}" id="rate_per_unitss" style="width:80px; height:30px;" /></td>
                                                      <td><input type="number" step="any" min="0" max="{{$List->wastage}}" name="wastagess[]" value="{{$List->wastage}}" id="wastagess" style="width:80px; height:30px;" required /></td>
                                                      <td><input type="number" step="any"  @if(Session::get('user_type')!=1) min="0" max="{{$List->bom_qty}}"  @endif  name="bom_qtyss[]" value="{{$List->bom_qty}}" id="bom_qtyss" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtyss1[]" value="{{$List->item_qty}}" id="bom_qtyss1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtyss_expect[]" value="0" id="bom_qtyss_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any" class="PACKING"   name="total_amountss[]" value="{{$List->total_amount}}" id="total_amountss" style="width:80px; height:30px;" readOnly/></td>
                                                      <td><input type="text"      name="remarkss[]" value="{{$List->remark}}" id="remarkss" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button"  readOnly name="Pbutton[]" class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X"  @php if($List->item_count > 0 || $List->item_count_pack>0 ){ echo 'disabled'; } @endphp></td>
                                                   </tr>
                                                   @php
                                                   $oldPackingTrimsArray[]=['sr_no'=>$no,'class_id'=>$List->class_id,'item_code'=>$List->item_code,
                                                   'color_id'=>implode(',', $color_ids),'size_array'=>implode(',', $size_idss),'consumption'=>$List->consumption,'rate_per_unit'=>$List->rate_per_unit,
                                                   'wastage'=>$List->wastage,'bom_qty'=>$List->bom_qty,'total_amount'=>$List->total_amount,'remark'=>$List->remark];
                                                   $no=$no+1;  @endphp
                                                   @endforeach
                                                   <input type="hidden" name="packing_trims_old_data" value="{{  htmlspecialchars(json_encode($oldPackingTrimsArray), ENT_QUOTES, 'UTF-8'); }}" id="packing_trims_old_data"/>
                                                   @else
                                                   <tr>
                                                      <td><input type="text" name="idss" value="1" id="id" style="width:50px;" readonly/></td>
                                                      <td>
                                                         <select name="class_idss[]"  class="select2" id="class_idss" style="width:200px; height:30px;" required onchange="CalculateQtyRowPros11(this);" >
                                                            <option value="">--Classification--</option>
                                                            @foreach($ClassList3 as  $row)
                                                            {
                                                            <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td>
                                                         <select name="item_codess[]"  onchange="checkDuplicateItemGeneric(this, 'footable_4', 'item_codess[]')" class="item_packing_trims select2" id="item_codess" style="width:270px; height:30px;" required>
                                                            <option value="">--Item List--</option>
                                                            @foreach($ItemList3 as  $row)
                                                            {
                                                            <option value="{{ $row->item_code }}">({{$row->item_code}}) {{ $row->item_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                      </td>
                                                      <td> <input type="text" name="descriptionss[]" value="" id="descriptionss" style="width:200px; height:30px;" readOnly /></td>
                                                      <td>
                                                         <select name="color_idss[][]"   id="color_idss" style="width:300px; height:100px;" required multiple>
                                                            <option value="">--Color List--</option>
                                                            @foreach($ColorList as  $row)
                                                            {
                                                            <option value="{{ $row->color_id }}"
                                                               >{{ $row->color_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden" name="color_arrayss[]" value="0" id="color_arrayss" style="width:80px; height:30px;" required />
                                                      </td>
                                                      <td>
                                                         <select name="size_idss[][]"   id="size_idss" style="width:200px; height:140px;" required multiple>
                                                            <option value="">--Size List--</option>
                                                            @foreach($SizeDetailList as  $row)
                                                            {
                                                            <option value="{{ $row->size_id }}"
                                                               >{{ $row->size_name }}</option>
                                                            }
                                                            @endforeach
                                                         </select>
                                                         <input type="hidden" name="size_arrayss[]" value="0" id="size_arrayss" style="width:80px; height:30px;" required />
                                                      </td>
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0" @endif  name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" /></td>
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
                                                      <td><input type="number" step="any" @if(Session::get('user_type')!=1) min="0" max="0"  @endif name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" /></td>
                                                      <td><input type="number" step="any" min="0"  name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td>
                                                      <td><input type="number" step="any" name="bom_qtyss[]" value="0" id="bom_qtyss" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtyss1[]" value="{{isset($List->item_qty) ? $List->item_qty : 0}}" id="bom_qtyss1" style="width:80px; height:30px;" readOnly/>
                                                         <input type="hidden" name="bom_qtyss_expect[]" value="0" id="bom_qtyss_expect1" style="width:80px; height:30px;" readOnly/>
                                                      </td>
                                                      <td><input type="number" step="any"  class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;" readOnly /></td>
                                                      <td><input type="text"      name="remarkss[]" value="0" id="remarkss" style="width:80px; height:30px;"   /></td>
                                                      <td><input type="button" readOnly name="Pbutton[]" class="btn btn-warning pull-left" value="+"></td>
                                                      <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
                                                   </tr>
                                                   @endif
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
                           <label for="po_date" class="form-label">Total Fabric Cost</label>
                           <input type="text" name="fabric_value" class="form-control" id="fabric_value" value="{{ $BOMMasterList->fabric_value }}" required  readOnly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="po_date" class="form-label">Sewing Trims Cost</label>
                           <input type="text" name="sewing_trims_value" class="form-control" id="sewing_trims_value" value="{{ $BOMMasterList->sewing_trims_value }}" required readOnly >
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="po_date" class="form-label">Packing Trims Cost</label>
                           <input type="text" name="packing_trims_value" class="form-control" id="packing_trims_value" value="{{ $BOMMasterList->packing_trims_value }}" required readOnly >
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="po_date" class="form-label">Total Cost</label>
                           <input type="text" name="total_cost_value" class="form-control" id="total_cost_value" value="{{ $BOMMasterList->total_cost_value }}" required readOnly>
                        </div>
                     </div>
                     @php  
                     $userId=Session::get('userId');
                     if($userId==1){   @endphp
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="is_approved" class="form-label">Do you want to Approve this BOM?</label>
                           <select name="is_approved" class="form-control" id="is_approved" required  >
                           @foreach($ApproveMasterList as  $row)
                           {
                           <option value="{{ $row->approve_id }}"
                           {{ $row->approve_id == $BOMMasterList->is_approved ? 'selected="selected"' : '' }} 
                           >{{ $row->approve_yes_no }}</option>
                           }
                           @endforeach
                           </select>
                        </div>
                     </div>
                     @php } @endphp
                  </div>
                  <div class="row">
                     <div class="col-sm-8">
                        <label for="formrow-inputState" class="form-label">Narration</label>
                        <div class="mb-3">
                           <input type="text" name="narration" class="form-control" id="narration"  value="{{ $BOMMasterList->narration }}" />
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
            @endif 
         </div>
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div> 
   <!-- end col -->
</div> 
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
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
        
            // selectors that might hold collapsible panels (BS3 uses .panel-collapse, BS4/5 uses .collapse)
            var collapseSelector = '.panel-custom .panel-collapse, .panel-custom .collapse';
            var $panels = $(collapseSelector);
        
            if ($panels.length === 0) return; // nothing found, exit safely
        
            // first panel collapse element
            var $firstPanel = $panels.first();
        
            // try to find #orderQtyDefult as a collapse; if not found, try by heading's target later
            var $orderPanel = $panels.filter('#orderQtyDefult');
            var $orderHeading;
        
            // If not found on collapse elements, check for an element with that id inside panel-custom (maybe user put id on heading)
            if ($orderPanel.length === 0) {
              var $possible = $('.panel-custom').find('#orderQtyDefult');
              if ($possible.length) {
                // if the element with that id is a heading, find its next collapse
                if ($possible.next(collapseSelector).length) {
                  $orderPanel = $possible.next(collapseSelector);
                } else if ($possible.is(collapseSelector)) {
                  $orderPanel = $possible;
                }
              }
            }
        
            // If still not found, try matching by data-target or href that points to #orderQtyDefult
            if ($orderPanel.length === 0) {
              var $toggleThatTargets = $('.panel-custom').find('[data-target="#orderQtyDefult"], a[href="#orderQtyDefult"]');
              if ($toggleThatTargets.length) {
                $orderPanel = $($toggleThatTargets.attr('data-target') || $toggleThatTargets.attr('href'));
              }
            }
        
            // ensure $orderPanel is at least an empty jQuery object if not found (so checks don't break)
            if (!$orderPanel || $orderPanel.length === 0) {
              $orderPanel = $(); // empty set
            }
        
            // helper to force a panel open (works for BS3/BS4/5)
            function forceOpen($p) {
              if (!$p || $p.length === 0) return;
              // if collapse plugin exists, prefer calling it
              if ($.fn.collapse) {
                try { $p.collapse('show'); } catch(err) {}
              }
              $p.addClass('in show').css('display', 'block').attr('aria-expanded', 'true');
              var $head = $p.prev('.panel-heading, .card-header, [data-toggle="collapse"], [data-bs-toggle="collapse"]');
              $head.addClass('open');
              // if toggler inside heading, ensure aria-expanded
              $head.find('[data-toggle="collapse"], [data-bs-toggle="collapse"]').attr('aria-expanded', 'true');
            }
        
            // helper to prevent a panel from being hidden
            function preventHide($p) {
              if (!$p || $p.length === 0) return;
              $p.on('hide.bs.collapse', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
              });
              // also for bootstrap 5 event namespace (just in case)
              $p.on('hide.bs.collapse hide.bs.toggle', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
              });
            }
        
            // Force open first panel and prevent hide
            forceOpen($firstPanel);
            preventHide($firstPanel);
        
            // If order panel exists, force open and prevent hide
            if ($orderPanel.length) {
              forceOpen($orderPanel);
              preventHide($orderPanel);
            }
        
            // Prevent clicking the headings from toggling those two panels (safe)
            var $firstHeading = $firstPanel.prev('.panel-heading, .card-header, [data-toggle="collapse"], [data-bs-toggle="collapse"]').first();
            if ($firstHeading.length) {
              $firstHeading.on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
              });
            }
            if ($orderPanel.length) {
              $orderHeading = $orderPanel.prev('.panel-heading, .card-header, [data-toggle="collapse"], [data-bs-toggle="collapse"]').first();
              if ($orderHeading.length) {
                $orderHeading.on('click', function(e){
                  e.preventDefault();
                  e.stopImmediatePropagation();
                  return false;
                });
              }
            }
        
            // Normal accordion behavior for other panels:
            $panels.not($firstPanel).not($orderPanel)
              .on('show.bs.collapse', function(){
                var $this = $(this);
                $this.prev('.panel-heading, .card-header').addClass('open');
        
                // close any other open panels except first and order
                $panels.filter('.in, .show').not($firstPanel).not($orderPanel).not($this).each(function(){
                  var $open = $(this);
                  if ($.fn.collapse) {
                    try { $open.collapse('hide'); } catch(err) {}
                  }
                  $open.removeClass('in show').css('display','none').attr('aria-expanded', 'false');
                  $open.prev('.panel-heading, .card-header').removeClass('open')
                    .find('[data-toggle="collapse"], [data-bs-toggle="collapse"]').attr('aria-expanded','false');
                });
              })
              .on('hide.bs.collapse', function(){
                // regular headings should lose 'open' when closed
                $(this).prev('.panel-heading, .card-header').removeClass('open')
                  .find('[data-toggle="collapse"], [data-bs-toggle="collapse"]').attr('aria-expanded','false');
              });
        
          });
    })(jQuery);

    
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
    
    function checkDuplicateItemGeneric(row, tableId, inputName)
    {
        var $rowEl = $(row);
        var currentItemCode = $rowEl.val();
        if (!currentItemCode) return false; // nothing selected, nothing to validate
    
        currentItemCode = String(currentItemCode);
        var $currentRow = $rowEl.closest('tr');
    
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('GetItemDetails') }}",
            data: { item_code: currentItemCode},
            success: function (data) 
            {
                $currentRow.find('td:eq(3) input').val(data[0].item_description);
            },
            error: function () {
                resolve();
            }
        }); 
        
        // find size select in this row
        var $currentSizeSelect = $currentRow.find('select[name^="size_ids"]');
        var currentSizesRaw = $currentSizeSelect.val() || [];
        var currentSizes = Array.isArray(currentSizesRaw) ? currentSizesRaw.map(String) : [String(currentSizesRaw)];
        currentSizes = currentSizes.filter(function(s){ return s !== "" && s !== null && typeof s !== 'undefined'; });
        if (currentSizes.length === 0) return false;
    
        var duplicateFound = false;
        var duplicateInfo = null;
    
        // iterate all item selects in table
        $('#' + tableId + ' select[name="' + inputName + '"]').each(function () {
      
            var $thisItem = $(this);
            if ($thisItem.is($rowEl)) return true; // skip same row
    
            var otherItemCode = String($thisItem.val() || '');
            if (!otherItemCode || otherItemCode !== currentItemCode) return true; // skip if not same item
    
            var $otherRow = $thisItem.closest('tr');
            var $otherSizeSelect = $otherRow.find('select[name^="size_ids"]');
            var otherSizesRaw = $otherSizeSelect.val() || [];
            var otherSizes = Array.isArray(otherSizesRaw) ? otherSizesRaw.map(String) : [String(otherSizesRaw)];
            otherSizes = otherSizes.filter(function(s){ return s !== "" && s !== null && typeof s !== 'undefined'; });
    
            // compare size overlap
            for (var i = 0; i < currentSizes.length; i++) {
                if (otherSizes.includes(currentSizes[i])) {
                    duplicateFound = true;
                    duplicateInfo = { size: currentSizes[i] };
                    break;
                }
            }
            if (duplicateFound) return false; // stop loop
        });
    
        if (duplicateFound) {
            $currentRow.find('input[type="number"]').val('');
            alert('This item with size "' + duplicateInfo.size + '" is already selected! Please choose a different size.');
            // ✅ Keep item_code as is — do NOT blank it
            // ❌ Don’t clear $(row).val(null)
            // Just disable the item select
            $rowEl.prop("disabled", false);
    
            // ✅ Clear only size dropdown
            if ($currentSizeSelect.length) {
                if ($currentSizeSelect.data('select2')) {
                    $currentSizeSelect.val(null).trigger('change.select2');
                } else {
                    $currentSizeSelect.val(null).trigger('change');
                }
            }
    
            $rowEl.focus();
            return true;
        }
    
        // If unique, disable as before
        setTimeout(function () {
            $rowEl.prop("disabled", true);
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

    
    // ---------- Example usage / bindings ----------
    // Call validator when item select changes (pass `this` as row)
    $(document).on('change', 'select[name="item_code[]"], select[name="item_code\\[\\][]"], select[name^="item_code"]', function () {
        // replace 'myTableId' with your actual table ID
        checkDuplicateItemGeneric(this, 'footable_1', $(this).attr('name'));
    });
    
    // Also call validator when the size multiselect changes (so changing sizes later is validated)
    $(document).on('change', 'select[name^="size_ids"]', function () {
        var $row = $(this).closest('tr');
        // find the item select in same row - try common name patterns; adjust if your item select name differs
        var $itemSelect = $row.find('select[name="item_code[]"], select[name^="item_code"]');
        if ($itemSelect.length) {
            checkDuplicateItemGeneric($itemSelect.first(), $row.closest('table').attr('id') || 'footable_1', $itemSelect.first().attr('name'));
        }
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
                        findInput(row, 'bom_qtysx1_expect[]').val(qty);
    
                        const bom_qty1 = parseFloat(findInput(row, 'bom_qtysx1_expect[]').val() || 0) || 0;
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
                { sel: '#collapse5', fn: CalculateQtyRowTrim, expect: 'bom_qtysx1_expect[]', actual: 'bom_qtysx1[]', msg: 'Trim Fabric rows have mismatched quantities. Please correct them.' },
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

   
//   var formModified = true;
//   window.addEventListener('beforeunload', function (e) {
//      if (formModified) {
//          var confirmationMessage = 'You have unsaved changes. Are you sure you want to leave this page? Changes you made may not be saved.';
//          (e || window.event).returnValue = confirmationMessage; 
//          return confirmationMessage;  
//      }
//   }); 
   
   getBuyerDetails($("#sales_order_no").val());

   function getBuyerDetails(sales_order_no)
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
           
           
           
           document.getElementById('season_id').disabled=true;
           document.getElementById('Ac_code').disabled=true;
           document.getElementById('currency_id').disabled=true;
           document.getElementById('mainstyle_id').disabled=true;
           document.getElementById('substyle_id').disabled=true;
           document.getElementById('fg_id').disabled=true;
   
      }
      }); 
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
   
   $.ajax({
      dataType: "json",
   url: "{{ route('GetItemList') }}",
   data:{'tr_code':sales_order_no},
   success: function(data){
   $("#item_code").html(data.html);
   
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
   $("#class_id").html(data.html);
   $("#class_idsx").html(data.html);
   }
   });
   
   }
   
   
   // $(document).on('change', 'select[name^="class_ids[]"]', function()
   // {CalculateQtyRowPros10($(this).closest("tr"));});
   function CalculateQtyRowPros10(row)
   {   
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
                  +row.find('select[name^="item_codess"]').html(data.html);
                  row.find('select[name^="item_codess"]').select2();
          }
        });

   }
   
   // $(document).on('change', 'select[name^="class_idsx[]"]', function()
   // {CalculateQtyRowPros12($(this).closest("tr"));});
   function CalculateQtyRowPros123(row)
   {    
   
        var class_id = $(row).val();
        var row = $(row).closest('tr'); 
        row.find('select[name^="item_codesx').select2('destroy');
   
        var class_id=+row.find('select[name^="class_idsx[]"]').val();
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetClassItemList') }}",
          data:{'class_id':class_id},
          success: function(data)
          { 
                +row.find('select[name^="item_codesx"]').html(data.html);
                row.find('select[name^="item_codesx').select2();
          }
        });
   
   }
   
   
   function EnableFields()
   {
          // document.getElementById('cost_type_id').disabled=false;
           document.getElementById('sales_order_no').disabled=false;
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
          //  $("input").prop('disabled', false);
   }
   
   $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
   
   setTimeout(mycalc,2000);
   
   });
   
   
   
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
   var class_id=row.find('select[name^="class_idsx[]"]').val();
   
   row.find('input[name^="color_arraysx[]"]').val(color_id);
   row.find('input[name^="size_arraysx[]"]').val(size_id);
   var sales_order_no=$('#sales_order_no').val();
   $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('TrimFabricWiseSalesOrderCosting') }}",
          data:{'item_code':item_code,'sales_order_no':sales_order_no,'color_id':color_id,'size_id':size_id,'class_id':class_id},
          success: function(data)
          {
                  console.log(data);
                //   row.find('input[name^="descriptionsx[]"]').val(data[0]['description']);
                  row.find('input[name^="consumptionsx[]"]').val(data[0]['consumption']);
              @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp  
              row.find('input[name^="consumptionsx[]"]').attr({"max" : parseFloat(data[0]['consumption']),"min" : 0}); 
              @php } @endphp
                  row.find('input[name^="wastagesx[]"]').val(data[0]['wastage']);
              @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp  
                  row.find('input[name^="wastagesx[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
              @php } @endphp
                  row.find('input[name^="rate_per_unitsx[]"]').val(data[0]['rate_per_unit']);
              @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp  
                  row.find('input[name^="rate_per_unitsx[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
              @php } @endphp    
                  //row.find('select[name^="class_idsx[]"]').val(data[0]['class_id']);
                  row.find('select[name^="unit_idsx[]"]').val(data[0]['unit_id']);
                   
                  var  wastage=data[0]['wastage'];
                  var  consumption=data[0]['consumption'];
                  var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                  var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                  row.find('input[name^="bom_qtysx[]"]').val(bom_qty1);
                  row.find('input[name^="bom_qtysx1[]"]').val(data[0]['bom_qty']);
             
                 var total_amount=(bom_qty1*data[0]['rate_per_unit']).toFixed(4)
                  row.find('input[name^="bom_qtysx[]"]').val(bom_qty1);
                 @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp  
                    row.find('input[name^="bom_qtysx[]"]').attr({"max" : bom_qty1,"min" : 0});
                   @php } @endphp    
                  row.find('input[name^="total_amountsx[]"]').val(total_amount);
                  alert(data[0]['consumption']);  
          }
      });
   
     setTimeout(mycalc,2000);
   
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
                    //   row.find('input[name^="descriptions[]"]').val(data[0]['description']);
                      row.find('input[name^="consumptions[]"]').val(data[0]['consumption']);
                      @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                          row.find('input[name^="consumptions[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                      @php } @endphp
                      row.find('input[name^="wastages[]"]').val(data[0]['wastage']);
                      @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                               row.find('input[name^="wastages[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                      @php } @endphp
                      row.find('input[name^="rate_per_units[]"]').val(data[0]['rate_per_unit']);
                      @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                           row.find('input[name^="rate_per_units[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                      @php } @endphp
                      row.find('select[name^="class_ids[]"]').val(data[0]['class_id']);
                      row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                    
                      var  wastage=data[0]['wastage'];
                      var  consumption=data[0]['consumption'];
                      var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                      var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                      row.find('input[name^="bom_qtys[]"]').val(bom_qty1);
                      row.find('input[name^="bom_qtys1[]"]').val(data[0]['bom_qty']);
                      @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                           row.find('input[name^="bom_qtys[]"]').attr({"max" : bom_qty1,"min" : 0});
                       @php } @endphp
                      var total_amount=(bom_qty1*data[0]['rate_per_unit']).toFixed(4)
                      
                      row.find('input[name^="total_amounts[]"]').val(total_amount);
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
       $(row).find('select[name^="item_code[]"]').attr('disabled', true);
       $(row).find('select[name^="class_id[]"]').attr('disabled', true);
       $(row).find('select[name^="unit_id[]"]').attr('disabled', true);
       var sales_order_no=$('#sales_order_no').val();
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('FabricWiseSalesOrderCosting') }}",
          data:{'item_code':item_code,sales_order_no:sales_order_no},
          success: function(data)
          {
                  console.log(data);
                //   row.find('input[name^="description[]"]').val(data[0]['description']);
               @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                  row.find('input[name^="consumption[]"]').val(data[0]['consumption']);
                @php } @endphp 
                  row.find('input[name^="consumption[]"]').attr({"max" : data[0]['consumption'],"min" : 0});
                 
                  row.find('input[name^="wastage[]"]').val(data[0]['wastage']);
                 @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                      row.find('input[name^="wastage[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                 @php } @endphp
                  row.find('input[name^="rate_per_unit[]"]').val(data[0]['rate_per_unit']);
                  @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                   row.find('input[name^="rate_per_unit[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                  @php } @endphp
                  
                  var  wastage=data[0]['wastage'];
                  var  consumption=data[0]['consumption'];
                   row.find('input[name^="consumption[]"]').val(data[0]['consumption'])
                   row.find('select[name^="class_id[]"]').val(data[0]['class_id']).trigger('change');
                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']).trigger('change');
                  var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                  
                  var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                       
                      
                  row.find('input[name^="bom_qty[]"]').val(bom_qty1);
                 @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp  
                   row.find('input[name^="bom_qty[]"]').attr({"max" : bom_qty1,"min" : 0});
                  @php } @endphp 
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
          }
      });
   
     setTimeout(mycalc,2000);
   }
      
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
                //   row.find('input[name^="descriptionss[]"]').val(data[0]['description']);
                  row.find('input[name^="consumptionss[]"]').val(data[0]['consumption']);
                  
                   @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                   row.find('input[name^="consumptionss[]"]').attr({"max" : data[0]['consumption']});
                  @php } @endphp
                  row.find('input[name^="wastagess[]"]').val(data[0]['wastage']);
                 
                  @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                  row.find('input[name^="wastagess[]"]').attr({"max" : data[0]['wastage'],"min" : 0});
                 @php } @endphp
                  row.find('select[name^="class_idss[]"]').val(data[0]['class_id']);
                  row.find('select[name^="unit_idss[]"]').val(data[0]['unit_id']);
                  row.find('input[name^="rate_per_unitss[]"]').val(data[0]['rate_per_unit']);
                  
                   @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                  row.find('input[name^="rate_per_unitss[]"]').attr({"max" : data[0]['rate_per_unit'],"min" : 0});
                  @php } @endphp
                // alert(data[0]['bom_qty']);
                 // var bom_qty=parseFloat(data[0]['bom_qty']);
                
              //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                  var  wastage=data[0]['wastage'];
                  var  consumption=data[0]['consumption'];
                  var bom_qty=parseFloat(data[0]['bom_qty']*consumption).toFixed(3);
                  var bom_qty1=(parseFloat(bom_qty) + (parseFloat(parseFloat(bom_qty)*(parseFloat(wastage)/100)))).toFixed(4); 
                  row.find('input[name^="bom_qtyss[]"]').val(bom_qty1);
                  @php $user_type=Session::get('user_type'); if($user_type!=1){ @endphp
                       row.find('input[name^="bom_qtyss[]"]').attr({"max" : bom_qty,"min" : 0});
                  @php } @endphp
                  row.find('input[name^="bom_qtyss1[]"]').val(data[0]['bom_qty']);
                  var rate=data[0]['rate_per_unit'];
                  var total_amount=(bom_qty1*rate).toFixed(4);
                 // row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
                  
                  row.find('input[name^="total_amountss[]"]').val(total_amount);        
          }
      });
   
      setTimeout(mycalc,2000);
   
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
   
   $(document).on("click", 'input[name^="Fbutton[]"]', function (event) {
   
      insertcone1($(this).closest("tr"));
      
   });
   
    var indexcone = 2;

    function insertcone1(Fbutton) 
    {
        var rows1 = $(Fbutton).closest("tr");
        var table = document.getElementById("footable_1").getElementsByTagName('tbody')[0];
        var row = table.insertRow(table.rows.length);
    
        var currentRowCount = table.rows.length;
        var indexcone = currentRowCount;
    
        // ======== Sr No. Cell ========
        var cell1 = row.insertCell(0);
        var t1 = document.createElement("input");
        t1.style = "display: table-cell; width:50px;";
        t1.id = "id" + indexcone;
        t1.name = "id[]";
        t1.readOnly = true;
        t1.value = indexcone;
        cell1.appendChild(t1);
    
        var hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.id = "sr_no_bom" + indexcone;
        hiddenInput.name = "sr_no_bom[]";
        hiddenInput.value = indexcone;
        cell1.appendChild(hiddenInput);
    
        // ======== Item Code Select ========
        var cell2 = row.insertCell(1);

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

    
        // ======== Color ========
        var cell3 = row.insertCell(2);
        var t3 = document.createElement("textarea");
        t3.style = "display: table-cell; width:200px; height:30px;";
        t3.id = "colors" + indexcone;
        t3.name = "colors[]";
        t3.value = "";
        cell3.appendChild(t3);
    
        // ======== Class Select ========
        var cell4 = row.insertCell(3);
        var y = $("#class_id").clone();
        y.attr({
            "id": "class_id" + indexcone,
            "name": "class_id[]"
        }).val("");
        y.removeAttr('disabled');
        y.width(200);
        y.appendTo(cell4);
    
        // ======== Description ========
        var cell5 = row.insertCell(4);
        var t5 = document.createElement("input");
        t5.style = "display: table-cell; width:200px; height:30px;";
        t5.type = "text";
        t5.readOnly = "true";
        t5.id = "description" + indexcone;
        t5.name = "description[]";
        t5.value = "";
        cell5.appendChild(t5);
    
        // ======== Consumption ========
        var cell6 = row.insertCell(5);
        var t6 = document.createElement("input");
        t6.style = "display: table-cell; width:80px;";
        t6.type = "text";
        t6.id = "consumption" + indexcone;
        t6.name = "consumption[]";
        t6.value = "";
        cell6.appendChild(t6);
    
        // ======== Unit Select ========
        var cell7 = row.insertCell(6);
        var y = $("#unit_id").clone();
        y.attr({
            "id": "unit_id" + indexcone,
            "name": "unit_id[]"
        }).val("");
        y.disabled=true;
        y.width(100);
        y.appendTo(cell7);
    
        // ======== Rate per Unit ========
        var cell8 = row.insertCell(7);
        var t8 = document.createElement("input");
        t8.style = "display: table-cell; width:80px;";
        t8.type = "text";
        t8.id = "rate_per_unit" + indexcone;
        t8.name = "rate_per_unit[]";
        t8.value = "";
        cell8.appendChild(t8);
    
        // ======== Wastage ========
        var cell9 = row.insertCell(8);
        var t9 = document.createElement("input");
        t9.style = "display: table-cell; width:80px;";
        t9.type = "text";
        t9.id = "wastage" + indexcone;
        t9.name = "wastage[]";
        t9.value = "";
        cell9.appendChild(t9);
    
        // ======== BOM Qty ========
        var cell10 = row.insertCell(9);
        var t10 = document.createElement("input");
        t10.style = "display: table-cell; width:80px;";
        t10.type = "text";
        t10.readOnly = true;
        t10.id = "bom_qty" + indexcone;
        t10.name = "bom_qty[]";
        t10.value = "";
        cell10.appendChild(t10);
    
        // hidden fields
        ["bom_qty1", "bom_qty_expect"].forEach(function (idPrefix) {
            var t = document.createElement("input");
            t.type = "hidden";
            t.id = idPrefix + indexcone;
            t.name = idPrefix + "[]";
            t.value = "";
            cell10.appendChild(t);
        });
    
        // ======== Total Amount ========
        var cell11 = row.insertCell(10);
        var t11 = document.createElement("input");
        t11.style = "display: table-cell; width:80px;";
        t11.type = "text";
        t11.className = "FABRIC";
        t11.readOnly = true;
        t11.id = "total_amount" + indexcone;
        t11.name = "total_amount[]";
        t11.value = "";
        cell11.appendChild(t11);
    
        // ======== Remark ========
        var cell12 = row.insertCell(11);
        var t12 = document.createElement("input");
        t12.style = "display: table-cell; width:80px;";
        t12.type = "text";
        t12.id = "remark" + indexcone;
        t12.name = "remark[]";
        t12.value = "";
        cell12.appendChild(t12);
    
        // ======== Buttons ========
        var cell13 = row.insertCell(12);
        var btnAdd = document.createElement("input");
        btnAdd.type = "button";
        btnAdd.className = "btn btn-warning pull-left";
        btnAdd.value = "+";
        btnAdd.setAttribute("onclick", "insertcone1(this)");
        cell13.appendChild(btnAdd);
    
        var cell14 = row.insertCell(13);
        var btnRemove = document.createElement("input");
        btnRemove.type = "button";
        btnRemove.className = "btn btn-danger pull-left";
        btnRemove.value = "X";
        btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
        cell14.appendChild(btnRemove);
    
        indexcone++;
    
        recalcIdcone1();
        selselect1();
        selselect2();
        selselect3();
    }

   
   // Start Sewing Trims----------------------------
   $(document).on("click", 'input[name^="Sbutton[]"]', function (event) {
   
      insertcone2($(this).closest("tr"));
      
   });
   
    var indexcone1 = 2;

    function insertcone2(Sbutton) 
    {
        var rows2 = $(Sbutton).closest("tr");
    
        // Destroy select2 before cloning (to avoid duplication issues)
        $("#item_codes").select2("destroy");
        $("#class_ids").select2("destroy");
    
        var table = document.getElementById("footable_3").getElementsByTagName('tbody')[0];
        var row = table.insertRow(table.rows.length);
    
        // --- Sr No. / ID column ---
        var cell1 = row.insertCell(0);
        var t1 = document.createElement("input");
        t1.style = "display: table-cell; width:50px;";
        t1.id = "ids" + indexcone1;
        t1.name = "ids[]";
        t1.readOnly = true;
        t1.value = indexcone1;
        cell1.appendChild(t1);
    
        var hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.id = "sr_no_sewing_trims" + indexcone1;
        hiddenInput.name = "sr_no_sewing_trims[]";
        hiddenInput.value = indexcone1;
        cell1.appendChild(hiddenInput);
    
        // --- Class select ---
        var cell2 = row.insertCell(1);
        var classSelect = rows2.find('select[name^="class_ids[]"]').clone();
        classSelect.attr({
            "id": "class_ids",
            "name": "class_ids[]"
        }).val(''); // make blank
        classSelect.appendTo(cell2).show().removeAttr('disabled');
    
        // --- Item code select ---
        var cell3 = row.insertCell(2);
        var itemSelect = rows2.find('select[name^="item_codes[]"]').clone();
        itemSelect.attr({
            "id": "item_codes",
            "name": "item_codes[]"
        }).val(''); // make blank
        itemSelect.empty();
        itemSelect.append('<option value="">Select Item</option>');
        itemSelect.appendTo(cell3).show().removeAttr('disabled');
    
        // --- Description input ---
        var cell4 = row.insertCell(3);
        var descInput = document.createElement("input");
        descInput.style = "display: table-cell; width:200px; height:30px";
        descInput.type = "text";
        descInput.readOnly = true;
        descInput.id = "descriptions" + indexcone1;
        descInput.name = "descriptions[]";
        descInput.value = ""; // blank
        cell4.appendChild(descInput);
    
        // --- Color select ---
        var cell5 = row.insertCell(4);
        var colorSelect = rows2.find('select[name^="color_ids[]"]').clone();
        colorSelect.attr({
            "id": "color_ids",
            "name": "color_ids[][]"
        }).val(''); // make blank
        colorSelect.appendTo(cell5).removeAttr('disabled');
    
        var colorArray = document.createElement("input");
        colorArray.type = "hidden";
        colorArray.id = "color_arrays" + indexcone1;
        colorArray.name = "color_arrays[]";
        cell5.appendChild(colorArray);
    
        // --- Size select ---
        var cell6 = row.insertCell(5);
        var sizeSelect = rows2.find('select[name^="size_ids[]"]').clone();
        sizeSelect.attr({
            "id": "size_ids",
            "name": "size_ids[][]"
        }).val(''); // make blank
        sizeSelect.appendTo(cell6).removeAttr('disabled');
    
        var sizeArray = document.createElement("input");
        sizeArray.type = "hidden";
        sizeArray.id = "size_arrays" + indexcone1;
        sizeArray.name = "size_arrays[]";
        cell6.appendChild(sizeArray);
    
        // --- Consumption input ---
        var cell7 = row.insertCell(6);
        var consumption = document.createElement("input");
        consumption.style = "display: table-cell; width:80px;";
        consumption.type = "text";
        consumption.id = "consumptions" + indexcone1;
        consumption.name = "consumptions[]";
        consumption.value = "";
        cell7.appendChild(consumption);
    
        // --- Unit select ---
        var cell8 = row.insertCell(7);
        var unitSelect = $("#unit_ids").clone();
        unitSelect.attr({
            "id": "unit_ids",
            "name": "unit_ids[]"
        }).val(''); // blank
        unitSelect.appendTo(cell8).attr('disabled', true);
    
        // --- Rate per unit ---
        var cell9 = row.insertCell(8);
        var rate = document.createElement("input");
        rate.style = "display: table-cell; width:80px;";
        rate.type = "text";
        rate.id = "rate_per_units" + indexcone1;
        rate.name = "rate_per_units[]";
        rate.value = "";
        cell9.appendChild(rate);
    
        // --- Wastage ---
        var cell10 = row.insertCell(9);
        var wastage = document.createElement("input");
        wastage.style = "display: table-cell; width:80px;";
        wastage.type = "text";
        wastage.id = "wastages" + indexcone1;
        wastage.name = "wastages[]";
        wastage.value = "";
        cell10.appendChild(wastage);
    
        // --- BOM Qty ---
        var cell11 = row.insertCell(10);
        var bomQty = document.createElement("input");
        bomQty.style = "display: table-cell; width:80px;";
        bomQty.type = "text";
        bomQty.readOnly = true;
        bomQty.id = "bom_qtys" + indexcone1;
        bomQty.name = "bom_qtys[]";
        bomQty.value = "";
        cell11.appendChild(bomQty);
    
        // hidden fields
        ["bom_qtys1", "bom_qtys_expect"].forEach(name => {
            var hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.id = name + indexcone1;
            hidden.name = name + "[]";
            cell11.appendChild(hidden);
        });
    
        // --- Total amount ---
        var cell12 = row.insertCell(11);
        var total = document.createElement("input");
        total.style = "display: table-cell; width:80px;";
        total.type = "text";
        total.className = "SEWING";
        total.readOnly = true;
        total.id = "total_amounts" + indexcone1;
        total.name = "total_amounts[]";
        total.value = "";
        cell12.appendChild(total);
    
        // --- Remarks ---
        var cell13 = row.insertCell(12);
        var remark = document.createElement("input");
        remark.style = "display: table-cell; width:80px;";
        remark.type = "text";
        remark.id = "remarks" + indexcone1;
        remark.name = "remarks[]";
        remark.value = "";
        cell13.appendChild(remark);
    
        // --- Buttons ---
        var cell14 = row.insertCell(13);
        var btnAdd = document.createElement("input");
        btnAdd.type = "button";
        btnAdd.className = "btn btn-warning pull-left";
        btnAdd.value = "+";
        btnAdd.setAttribute("onclick", "insertcone2(this)");
        cell14.appendChild(btnAdd);
    
        var cell15 = row.insertCell(14);
        var btnRemove = document.createElement("input");
        btnRemove.type = "button";
        btnRemove.className = "btn btn-danger pull-left";
        btnRemove.value = "X";
        btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
        cell15.appendChild(btnRemove);
    
        // --- Counter Update ---
        document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value) + 1;
    
        indexcone1++;
    
        // --- Refresh Select2 and Recalculate IDs ---
        recalcIdcone2();
        selselect1();
        selselect2();
        selselect3();
    }

   // Start Trim Fabric
   
   $(document).on("click", 'input[name^="Tbutton[]"]', function (event) {
   
      insertcone5($(this).closest("tr"));
      
   });
   
   
   var indexcone1 = 2;
   function insertcone5(Tbutton){
   var rowsx=$(Tbutton).closest("tr");
   $("select").select2("destroy");
   $("select").select2("destroy");
   var table=document.getElementById("footable_5").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1"; 
   t1.readOnly= true;
   t1.id = "idsx"+indexcone1;
   t1.name= "idsx[]";
   t1.value=indexcone1;
   
   cell1.appendChild(t1);
   
   
   
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = rowsx.find('select[name^="class_idsx[]"]') ,
   y = x.clone();
   y.attr("id","class_idsx");
   y.attr("name","class_idsx[]");
   y.width(200);
   y.attr("disabled",false);
   y.appendTo(cell3);
   
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x =   rowsx.find('select[name^="item_codesx[]"]'),
   y = x.clone();
   y.attr("id","item_codesx");
   y.attr("name","item_codesx[]");
   y.attr("value","");
   y.attr("onchange","checkDuplicateItemGeneric(this, 'footable_5', 'item_codesx[]')");
   y.width(270);
   y.empty();
   y.append('<option value="">Select Item</option>');
   y.appendTo(cell3);
   y.removeAttr('disabled');
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.readOnly = "true";
   t5.id = "descriptionsx"+indexcone1;
   t5.name="descriptionsx[]";
   cell5.appendChild(t5); 
   
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x =   rowsx.find('select[name^="color_idsx[]"]'),
   y = x.clone();
   y.attr("id","color_idsx");
   y.attr("name","color_idsx[][]");
   y.width(300);
   y.appendTo(cell3); 
   y.removeAttr('disabled');
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.readOnly=true;
   t5.id = "color_arraysx"+indexcone2;
   t5.name="color_arraysx[]";
   cell3.appendChild(t5); 
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x =  rowsx.find('select[name^="size_idsx[]"]'),
   y = x.clone();
   y.attr("id","size_idsx");
   y.attr("name","size_idsx[][]");
   y.width(200);
   y.appendTo(cell3); 
   y.removeAttr('disabled');
   
   
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
   t5.type="text";
   t5.id = "consumptionsx"+indexcone1;
   t5.name="consumptionsx[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(7);
   var t3=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_idsx");
   y.attr("name","unit_idsx[]");
   y.width(100);
   y.appendTo(cell3);
   y.disabled=true;
   
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_unitsx"+indexcone1;
   t5.name="rate_per_unitsx[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastagesx"+indexcone1;
   t5.name="wastagesx[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   t5.id = "bom_qtysx1_expect"+indexcone1;
   t5.name="bom_qtysx1_expect[]";
   cell5.appendChild(t5);
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="hidden";
   t5.id = "bom_qtysx1"+indexcone1;
   t5.name="bom_qtysx1[]";
   cell5.appendChild(t5);
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   btnAdd.name = "Tbutton[]";
   btnAdd.id = "Tbutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   
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
   
   $(document).on("click", 'input[name^="Pbutton[]"]', function (event) {
   
      insertcone3($(this).closest("tr"));
      
   });
   
    var indexcone2 = 2;
    
    function insertcone3(Pbutton) 
    {
        var rowsx = $(Pbutton).closest("tr");
    
        // destroy select2 before cloning
        $("#item_codess").select2("destroy");
        $("#class_idss").select2("destroy");
    
        var table = document.getElementById("footable_4").getElementsByTagName('tbody')[0];
        var row = table.insertRow(table.rows.length);
    
        // --- ID / Sr No. ---
        var cell1 = row.insertCell(0);
        var t1 = document.createElement("input");
        t1.style = "display: table-cell; width:50px;";
        t1.id = "idss" + indexcone2;
        t1.name = "idss[]";
        t1.readOnly=true;
        t1.value = indexcone2;
        cell1.appendChild(t1);
    
        var hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.id = "sr_no_packing_trims" + indexcone2;
        hiddenInput.name = "sr_no_packing_trims[]";
        hiddenInput.value = indexcone2;
        cell1.appendChild(hiddenInput);
    
        // --- Class Select ---
        var cell2 = row.insertCell(1);
        var classSelect = rowsx.find('select[name^="class_idss[]"]').clone();
        classSelect.attr({ id: "class_idss", name: "class_idss[]" }).val('');
        classSelect.appendTo(cell2).removeAttr('disabled').show();
    
        // --- Item Code Select ---
        var cell3 = row.insertCell(2);
        var itemSelect = rowsx.find('select[name^="item_codess[]"]').clone();
        itemSelect.attr({ id: "item_codess", name: "item_codess[]" }).val('');
        itemSelect.empty();
        itemSelect.append('<option value="">Select Item</option>');
        itemSelect.appendTo(cell3).removeAttr('disabled').show();
    
        // --- Description Input ---
        var cell4 = row.insertCell(3);
        var descInput = document.createElement("input");
        descInput.style = "display: table-cell; width:200px; height:30px";
        descInput.type = "text";
        descInput.readOnly = "true";
        descInput.id = "descriptionss" + indexcone2;
        descInput.name = "descriptionss[]";
        descInput.value = "";
        cell4.appendChild(descInput);
    
        // --- Color Select ---
        var cell5 = row.insertCell(4);
        var colorSelect = $("#color_idss").clone();
        colorSelect.attr({ id: "color_idss", name: "color_idss[][]" }).val('');
        colorSelect.appendTo(cell5).removeAttr('disabled');
    
        var colorHidden = document.createElement("input");
        colorHidden.type = "hidden";
        colorHidden.id = "color_arrayss" + indexcone2;
        colorHidden.name = "color_arrayss[]";
        cell5.appendChild(colorHidden);
    
        // --- Size Select ---
        var cell6 = row.insertCell(5);
        var sizeSelect = $("#size_idss").clone();
        sizeSelect.attr({ id: "size_idss", name: "size_idss[][]" }).val('');
        sizeSelect.appendTo(cell6).removeAttr('disabled');
    
        var sizeHidden = document.createElement("input");
        sizeHidden.type = "hidden";
        sizeHidden.id = "size_arrayss" + indexcone2;
        sizeHidden.name = "size_arrayss[]";
        cell6.appendChild(sizeHidden);
    
        // --- Consumption ---
        var cell7 = row.insertCell(6);
        var consumption = document.createElement("input");
        consumption.style = "display: table-cell; width:80px;";
        consumption.type = "text";
        consumption.id = "consumptionss" + indexcone2;
        consumption.name = "consumptionss[]";
        consumption.value = "";
        cell7.appendChild(consumption);
    
        // --- Unit Select ---
        var cell8 = row.insertCell(7);
        var unitSelect = $("#unit_idss").clone();
        unitSelect.attr({ id: "unit_idss", name: "unit_idss[]" }).val('');
        unitSelect.appendTo(cell8).attr('disabled', true);
    
        // --- Rate per Unit ---
        var cell9 = row.insertCell(8);
        var rate = document.createElement("input");
        rate.style = "display: table-cell; width:80px;";
        rate.type = "text";
        rate.id = "rate_per_unitss" + indexcone2;
        rate.name = "rate_per_unitss[]";
        rate.value = "";
        cell9.appendChild(rate);
    
        // --- Wastage ---
        var cell10 = row.insertCell(9);
        var wastage = document.createElement("input");
        wastage.style = "display: table-cell; width:80px;";
        wastage.type = "text";
        wastage.id = "wastagess" + indexcone2;
        wastage.name = "wastagess[]";
        wastage.value = "";
        cell10.appendChild(wastage);
    
        // --- BOM Qty ---
        var cell11 = row.insertCell(10);
        var bomQty = document.createElement("input");
        bomQty.style = "display: table-cell; width:80px;";
        bomQty.type = "text";
        bomQty.id = "bom_qtyss" + indexcone2;
        bomQty.name = "bom_qtyss[]";
        bomQty.value = "";
        bomQty.readOnly=true;
        cell11.appendChild(bomQty);
    
        ["bom_qtyss1", "bom_qtyss_expect"].forEach(name => {
            var hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.id = name + indexcone2;
            hidden.name = name + "[]";
            cell11.appendChild(hidden);
        });
    
        // --- Total Amount ---
        var cell12 = row.insertCell(11);
        var total = document.createElement("input");
        total.style = "display: table-cell; width:80px;";
        total.type = "text";
        total.className = "PACKING";
        total.readOnly = true;
        total.id = "total_amountss" + indexcone2;
        total.name = "total_amountss[]";
        total.value = "";
        cell12.appendChild(total);
    
        // --- Remarks ---
        var cell13 = row.insertCell(12);
        var remark = document.createElement("input");
        remark.style = "display: table-cell; width:80px;";
        remark.type = "text";
        remark.id = "remarkss" + indexcone2;
        remark.name = "remarkss[]";
        remark.value = "";
        cell13.appendChild(remark);
    
        // --- Buttons ---
        var cell14 = row.insertCell(13);
        var btnAdd = document.createElement("input");
        btnAdd.type = "button";
        btnAdd.className = "btn btn-warning pull-left";
        btnAdd.value = "+";
        btnAdd.setAttribute("onclick", "insertcone3(this)");
        cell14.appendChild(btnAdd);
    
        var cell15 = row.insertCell(14);
        var btnRemove = document.createElement("input");
        btnRemove.type = "button";
        btnRemove.className = "btn btn-danger pull-left";
        btnRemove.value = "X";
        btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
        cell15.appendChild(btnRemove);
    
        // --- Update counter ---
        document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value) + 1;
    
        indexcone2++;
    
        // --- Refresh logic ---
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
       mycalc();
   }
   
   function deleteRowcone5(btn) 
   {
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
       mycalc();
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
       
       }
       mycalc();
   }
   
   function deleteRowcone3(btn) 
   {
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
       mycalc();
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
   $.each($("#footable_4 tr"),function (i,el){
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