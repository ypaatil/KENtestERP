@extends('layouts.master') 
@section('content')
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

   td,th
   {
      text-align: center;
   }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Inward : {{ $purchasefetch->trimCode }}</h4>
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
                  <button class="nav-link active" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab"
                   @if($purchasefetch->tab_button==2) disabled @endif>
                  Delivery
                  </button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button" role="tab"
                   @if($purchasefetch->tab_button==1) disabled @endif>
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
                
            <form action="{{route('TrimsInward.update',$purchasefetch)}}" method="POST" id="frmData">
               <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
               @method('put')
               @csrf    
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
               @php
                    //DB::enableQueryLog();
                    $stockData = DB::select("select stock_association.*,item_master.item_name from stock_association 
                    INNER JOIN item_master ON item_master.item_code = stock_association.item_code
                    where tr_code ='".$purchasefetch->trimCode."'"); 
                   // dd(DB::getQueryLog());
                    if(count($stockData) > 0)
                    {
                         $isclick = 1;
                    }
                    else
                    {
                         $isclick = 0;
                    } 
                @endphp
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">GRN Date</label>
                        <input type="date" name="trimDate" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->trimDate  }}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                        <input type="hidden" name="trimCode" class="form-control" id="trimCode" value="{{ $purchasefetch->trimCode }}" readonly="readonly">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->invoice_no }}"  >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
                        <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $purchasefetch->invoice_date }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">PO NO</label>   
                        <select name="po_code" class="form-select select2" id="po_code" onchange="getPODetails();FetchPoData();GetPurchaseBillDetails();"  disabled >
                           <option value="">PO code</option>
                           @foreach($POList as  $rowpol)
                           {
                           <option value="{{ $rowpol->pur_code  }}"
                           {{ $rowpol->pur_code == $purchasefetch->po_code ? 'selected="selected"' : '' }}
                           >{{ $rowpol->pur_code }}</option>
                           }
                           @endforeach
                        </select>
                        <input type="hidden" name="po_codenew" id="po_codenew" class="form-control"  value="{{ request()->po_code  }}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">PO Type</label>
                        <select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();" disabled>
                           <option value="">PO Type</option>
                           @foreach($POTypeList as  $rowpo)
                           {
                           <option value="{{ $rowpo->po_type_id  }}"
                           {{ $rowpo->po_type_id == $purchasefetch->po_type_id ? 'selected="selected"' : '' }}
                           >{{ $rowpo->po_type_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Supplier</label>
                        <select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();" disabled>
                           <option value="">--- Select Party Name ---</option>
                           @foreach($ledgerlist as  $rowledger)
                           {
                           <option value="{{ $rowledger->ac_code  }}"
                           {{ $rowledger->ac_code == $purchasefetch->Ac_code ? 'selected="selected"' : '' }}
                           >{{ $rowledger->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                      <div class="mb-3">
                         <label for="bill_to" class="form-label">Bill To</label>
                         <select name="bill_to" class="form-select" id="bill_to" disabled>
                            @foreach($BillToList as  $row) 
                                <option value="{{ $row->sr_no }}" {{ $row->sr_no == $purchasefetch->bill_to ? 'selected="selected"' : '' }} >{{ $row->trade_name }}({{$row->site_code}})</option> 
                            @endforeach
                         </select>
                      </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="form-check form-check-primary mb-5">
                        <input class="form-check-input" type="checkbox" id="is_opening" style="font-size: 25px;margin-top: 30px;margin-left: 0px;" name="is_opening"  @if($purchasefetch->is_opening==1) checked  @endif disabled >
                        <label class="form-check-label" for="is_opening" style="margin-top: 30px;position: absolute;margin-left: 20px;font-size: 16px;">
                        Opening Stock
                        </label>
                     </div>
                  </div>
                    <div class="col-md-3">
                        <label for="tge_code" class="form-label">Trim Gate Code</label>
                        <select name="tge_code" class="form-select select2" id="tge_code" disabled>
                           <option value="">--Select--</option>
                           @foreach($TGEList as  $row) 
                           <option value="{{ $row->tge_code }}"
                           {{ $row->tge_code == $purchasefetch->tge_code ? 'selected="selected"' : '' }}    
                           >{{ $row->tge_code }}</option> 
                           @endforeach
                        </select>
                     </div>
                    <div class="col-md-2">
                        <label for="formrow-inputState" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2  " id="location_id" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $purchasefetch->location_id ? 'selected="selected"' : '' }}    
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                    </div>
               </div> 
               <div class="table-wrap">
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
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
                        </thead>
                        <tbody id="bomdis" >
                           @if($detailpurchase =="") 
                           <tr>
                              <td><input type="text" name="id" value="1" id="id"  style="width:50px;" readonly/></td>
                              <td>
                                 <select name="item_codes[]" class="item" id="item_code" class="select2" style="width:200px;height:30px;" onchange="CheckDuplicateRow(this);">
                                      
                                    <option value="">--- Select Item ---</option>
                                    @foreach($itemlist as  $rowitem)
                                    {
                                    <option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td>
                                 <select name="unit_id[]" class="unit_ids" id="unit_ids"    style="width:150px;height:30px;">
                                    <option value="">--- Select Unit ---</option>
                                    @foreach($unitlist as  $rowunit)
                                    {
                                    <option value="{{ $rowunit->unit_id  }}" >{{ $rowunit->unit_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td><input type="number" step="any"  name="item_qtys[]" class="QTY"  value="0" id="item_qty" style="width:80px;height:30px;" required/>
                                 <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;"  required/>
                              </td>
                              <td><input type="number" step="any"  name="item_rates[]"    value="0" id="item_rates" style="width:80px;height:30px;" required/></td>
                              <td><input type="number" step="any" readOnly name="amounts[]" class="AMT"  value="0" id="amounts" style="width:80px;height:30px;" required/></td>
                              <td>
                                 <select name="rack_id[]"  id="rack_id"  class="select2" style="width:100px;height:30px;" required>
                                    <option value="">--Racks--</option>
                                    @foreach($RackList as  $row)
                                    {
                                    <option value="{{ $row->rack_id }}"
                                       >{{ $row->rack_name }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td> 
                                  <button type="button" onclick="deleteRow();" class="btn btn-danger pull-left" disabled >X</button> 
                              </td>
                              <td> 
                                  <button type="button" onclick="deleteRow();" class="btn btn-danger pull-left" disabled >X</button> 
                              </td>
                           </tr>
                           @endif   
                        </tbody>
                        <tfoot>
                           <tr>
                              <th>SrNo</th>
                              <th>Item Code</th>
                              <th>Classification</th>
                              <th>Item Name</th>
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Rate</th>
                              <th>Amount</th>
                              <th>Rack Location</th>
                              <th>Add</th>
                              <th>Remove</th>
                           </tr>
                        </tfoot>
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
                        <tbody id="stock_allocate"> 
                                @if(count($stockData) > 0) 
                            @foreach($stockData as $row)
                                <tr>
                                    <td><input type="text" name="stock_bom_code[]" value="{{$row->bom_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="sales_order_no[]" value="{{$row->sales_order_no}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="item_code[]" value="{{$row->item_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                    <td><input type="text" name="item_name[]" value="{{$row->item_name}}" class="form-control" style="width:260px;" readonly=""></td>
                                    <td>
                                        <input type="text" name="allocate_qty[]" value="{{$row->qty}}" class="form-control allocate_qty" style="width:100px;" readonly="">
                                        <input type="hidden" name="cat_id[]" value="{{$row->cat_id}}" class="form-control" style="width:100px;" >
                                        <input type="hidden" name="class_id[]" value="{{$row->class_id}}"  class="form-control" style="width:100px;">
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                     </table>
                  </div>
               </div>
               <input type="hidden"   name="cnt" id="cnt" value="{{ count($detailpurchase) }}">  
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" value="{{ $purchasefetch->totalqty }}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Amount</label>
                        <input type="text" name="total_amount" class="form-control" id="total_amount" value="{{ $purchasefetch->total_amount }}" readonly>
                        <input type="hidden" class="form-control" id="total_allocate_qty" value="0">
                     </div>
                  </div>
               </div>
               </br>  
               <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
               <a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
            </form>
            </div>
            <div class="tab-pane fade" id="return" role="tabpanel">
               <form action="{{route('TrimsInward.update',$purchasefetch)}}" method="POST" id="frmData">
                  <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
                  @method('put')
                  @csrf    
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
                  @php
                     //DB::enableQueryLog();
                     $stockData = DB::select("select stock_association.*,item_master.item_name from stock_association 
                     INNER JOIN item_master ON item_master.item_code = stock_association.item_code
                     where tr_code ='".$purchasefetch->trimCode."'"); 
                     // dd(DB::getQueryLog());
                     if(count($stockData) > 0)
                     {
                           $isclick = 1;
                     }
                     else
                     {
                           $isclick = 0;
                     } 
                  @endphp
                  <div class="row">
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isReturnTrimsInward" onchange="GetDCDropdown();" name="isReturnTrimsInward" 
                                  style="font-size: 30px;margin-left: 0px;margin-top: -3px;" @if($purchasefetch->isReturnTrimsInward==1)checked @else disabled @endif disabled>
                              <label class="form-check-label" for="isReturnTrimsInward" style="position: absolute;margin-left: 20px;font-size: 14px;">
                              Trims Return From Inhouse
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isOutsideVendor" name="isOutsideVendor" onchange="DisableDropdown();" 
                                 style="font-size: 30px;margin-left: 0px;margin-top: -3px;"  @if($purchasefetch->isOutsideVendor==1)checked @else disabled @endif disabled />
                              <label class="form-check-label" for="isOutsideVendor" style="position: absolute;margin-left: 20px;font-size: 14px;">
                              From Outsource Vendor
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">DC Date</label>
                           <input type="hidden" name="trimDate" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->trimDate  }}" required>
                           <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                           <input type="hidden" name="trimCode" class="form-control" id="trimCode" value="{{ $purchasefetch->trimCode }}" readonly="readonly">
                           <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $purchasefetch->invoice_date }}">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">DC No</label>
                           <input type="text" name="invoice_no" id="invoice_no1" class="form-control" value="{{ $purchasefetch->invoice_no }}"  >
                           <select name="invoice_no" class="form-select select2 hide" id="tocd_code">
                              <option value="">--Select--</option> 
                           </select>
                        </div>
                     </div> 
                     <div class="col-md-2">
                           <label for="tge_code" class="form-label">Trim Gate Code</label>
                           <select name="tge_code" class="form-select select2" id="tge_code" disabled>
                              <option value="">--Select--</option>
                              @foreach($TGEList as  $row) 
                              <option value="{{ $row->tge_code }}"
                              {{ $row->tge_code == $purchasefetch->tge_code ? 'selected="selected"' : '' }}    
                              >{{ $row->tge_code }}</option> 
                              @endforeach
                           </select>
                        </div>
                     <div class="col-md-3">
                           <label for="formrow-inputState" class="form-label">Location/Warehouse</label>
                           <select name="location_id" class="form-select select2  " id="location_id" disabled>
                              <option value="">--Select Buyer--</option>
                              @foreach($LocationList as  $row)
                              {
                              <option value="{{ $row->loc_id }}"
                              {{ $row->loc_id == $purchasefetch->location_id ? 'selected="selected"' : '' }}    
                              >{{ $row->location }}</option>
                              }
                              @endforeach
                           </select>
                     </div>
                     <div class="col-md-2" id="workOrder">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Work Order No.</label>   
                           <select name="vw_code" class="form-select select2" id="vw_code" onchange="GetVendorName(this.value);" disabled>
                              <option value="">--Select--</option>
                              @foreach($vendorWorkOrderList as  $vendors)
                              <option value="{{ $vendors->vw_code  }}" {{ $vendors->vw_code == $purchasefetch->vw_code ? 'selected="selected"' : '' }}>{{ $vendors->vw_code }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3" id="vendorData">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Name</label>   
                           <select name="vendorId" class="form-select select2" id="vendorId" disabled>
                              <option value="">--Select--</option>
                              @foreach($vendorData as  $rows)
                              <option value="{{ $rows->ac_code }}" {{ $rows->ac_code == $purchasefetch->vendorId ? 'selected="selected"' : '' }}> {{ $rows->ac_short_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-1 mt-4">
                        <button type="button" name="allocate[]"  onclick="stockAllocate1();" id="mainAllocation1" isClick="0" class="btn btn-warning pull-center">Allocate</button> 
                     </div>
                  </div> 
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_21" class="table  table-bordered table-striped m-b-0 footable_21">
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
                           <tbody id="bomdis1" >
                              @if($detailpurchase =="") 
                              <tr>
                                 <td><input type="text" name="id" value="1" id="id"  style="width:50px;" readonly/></td>
                                 <td>
                                    <select name="item_codes[]" class="item" id="item_code" class="select2" style="width:200px;height:30px;" onchange="CheckDuplicateRow(this);">
                                       
                                       <option value="">--- Select Item ---</option>
                                       @foreach($itemlist as  $rowitem)
                                       {
                                       <option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="unit_id[]" class="unit_ids" id="unit_ids"    style="width:150px;height:30px;">
                                       <option value="">--- Select Unit ---</option>
                                       @foreach($unitlist as  $rowunit)
                                       {
                                       <option value="{{ $rowunit->unit_id  }}" >{{ $rowunit->unit_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td><input type="number" step="any"  name="item_qtys[]" class="QTY"  value="0" id="item_qty" style="width:80px;height:30px;" required/>
                                    <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;"  required/>
                                 </td>
                                 <td><input type="number" step="any"  name="item_rates[]"    value="0" id="item_rates" style="width:80px;height:30px;" required/></td>
                                 <td><input type="number" step="any" readOnly name="amounts[]" class="AMT"  value="0" id="amounts" style="width:80px;height:30px;" required/></td>
                                 <td>
                                    <select name="rack_id[]"  id="rack_id"  class="select2" style="width:100px;height:30px;" required>
                                       <option value="">--Racks--</option>
                                       @foreach($RackList as  $row)
                                       {
                                       <option value="{{ $row->rack_id }}"
                                          >{{ $row->rack_name }}</option>
                                       }
                                       @endforeach
                                    </select>
                                 </td>
                                 <td> 
                                    <button type="button" onclick=" mycalc();" class="btn btn-warning pull-left" disabled >+</button> 
                                 </td>
                                 <td> 
                                    <button type="button" onclick="deleteRow();" class="btn btn-danger pull-left" disabled >X</button> 
                                 </td>
                              </tr>
                              @endif   
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th>SrNo</th>
                                 <th>Item Code</th>
                                 <th>Classification</th>
                                 <th>Item Name</th>
                                 <th>Unit</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Amount</th>
                                 <th>Rack Location</th>
                                 <th class="text-center">Add</th>
                                 <th class="text-center">Delete</th>
                              </tr>
                           </tfoot>
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
                           <tbody id="stock_allocate1"> 
                                 @if(count($stockData) > 0) 
                              @foreach($stockData as $row)
                                 <tr>
                                       <td><input type="text" name="stock_bom_code[]" value="{{$row->bom_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                       <td><input type="text" name="sales_order_no[]" value="{{$row->sales_order_no}}" class="form-control" style="width:100px;" readonly=""></td>
                                       <td><input type="text" name="item_code[]" value="{{$row->item_code}}" class="form-control" style="width:100px;" readonly=""></td>
                                       <td><input type="text" name="item_name[]" value="{{$row->item_name}}" class="form-control" style="width:260px;" readonly=""></td>
                                       <td>
                                          <input type="text" name="allocate_qty[]" value="{{$row->qty}}" class="form-control allocate_qty" style="width:100px;" readonly="">
                                          <input type="hidden" name="cat_id[]" value="{{$row->cat_id}}" class="form-control" style="width:100px;" >
                                          <input type="hidden" name="class_id[]" value="{{$row->class_id}}"  class="form-control" style="width:100px;">
                                       </td>
                                 </tr>
                              @endforeach
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <input type="hidden"   name="cnt" id="cnt" value="{{ count($detailpurchase) }}">  
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">Total Quantity</label>
                           <input type="text" name="totalqty" class="form-control" id="totalqty1" value="{{ $purchasefetch->totalqty }}" readonly>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">Total Amount</label>
                           <input type="text" name="total_amount" class="form-control" id="total_amount1" value="{{ $purchasefetch->total_amount }}" readonly>
                           <input type="hidden" class="form-control" id="total_allocate_qty1" value="0">
                        </div>
                     </div> 
                  </div>
                  </br>  
                  <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
                  <a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>
<script>

   let duplicateChecking = false;   // global flag

   function CheckDuplicateRow(row)
   {
      if (duplicateChecking) return;  // stop repeated alerts

      let selectedVal = $(row).val();

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


   $(document).ready(function() 
   {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 

        
      @if($purchasefetch->tab_button==1) 
         $("#delivery-tab").trigger('click'); 
      @else  
            $("#return-tab").trigger('click');
      @endif
      
        calculateAllocatedQty();
   });
    
   
 function DisableDropdown()
   {
         $("#delivery-tab").prop("disabled", true);
         if($("#isOutsideVendor").is(":checked"))
         {
            $("#isReturnTrimsInward").attr('disabled', true); 
            $("#vpo_code").prop('disabled', false); 
            $("#vpo_code").attr('onchange', 'GetFabricOutwardData()');
         }
         else
         {
            $("#vpo_code").prop('disabled', true); 
            $("#isReturnTrimsInward").attr('disabled', false);  
         }          

   } 

   function GetDCDropdown()
   { 
         $("#delivery-tab").prop('disabled', true);
         if($("#isReturnTrimsInward").is(":checked"))
         {
            $("#footable_4").addClass("hide");
            $("#isOutsideVendor").attr('disabled', true);
            $("#invoice_no1").removeAttr('name').removeAttr('required').addClass("hide");
            $("#tocd_code").attr('name', 'invoice_no').attr('required', true).removeClass("hide"); 
         }
         else
         {
            $("#invoice_no1").attr('name', 'invoice_no').attr('required', true).removeClass("hide");
            $("#tocd_code").removeAttr('name').removeAttr('required').addClass("hide"); 
         }
         $("#isReturnTrimsInward").attr('disabled', true);
         $("#isOutsideVendor").attr('disabled', true);
   }

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
    
    var vpo_code = $("#vpo_code").val();
   //  if(vw_code != "")
   //  {
   //        GetVendorName(vw_code);
   //  } 
   
    function GetVendorName(vpo_code)
    {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetVendorName') }}",
          data:{'vpo_code':vpo_code},
          success: function(data)
          {
              console.log(data);
               $("#vendorName").val(data.html);
               $('#vendorData').removeClass('hide');
               
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
    }
    
    function checkNumber(row) 
    { 
        var max = parseFloat($(row).attr('max'));  // Get the maximum allowed value
        var stock = parseFloat($(row).attr('stock'));  // Get stock value
        var minQty = parseFloat($(row).attr('min'));  // Get the minimum allowed value
        var inputNumber = parseFloat($(row).val());  // Get user input
        
        if (inputNumber < minQty) {
            alert(`Minimum Quantity is ${minQty}`);
            $(row).val(minQty);
        }
        else if (inputNumber > max && inputNumber > stock) {
            alert(`The number exceeds the maximum value of ${max}`);
            $(row).val(max);
        } 
    }

    
  
    
// $(document).ready(function() {
//     var isLoading = false;
//     var page = 1; // Initial page number
//     var contentDiv = $('#bomdis');
   
//     var trimCode = $("#trimCode").val();
//     var po_code = $("#po_code").val();

//     // Function to load more data
//     function loadMoreData() {
//         if (isLoading) {
//             return;
//         }
//         isLoading = true;
//         $.ajax({
//             type: "GET",
//             dataType:"json",
//             url: "{{ route('GetTrimCodeWiseData') }}",
//             data:{'trimCode':trimCode,'po_code' : po_code,'page':page},
//             success: function(data) {
//                 if (data) {
//                     contentDiv.append(data.html); // Append new data to the content
//                     page++; // Increment the page number for the next request
//                     isLoading = false;
//                 }
//             }
//         });
//     }

//     // Detect when the user scrolls to the bottom of the page
//     $(window).scroll(function() {
//         if ($(window).scrollTop() + $(window).height() >= contentDiv.height() - 100) {
//             loadMoreData();
//         }
//     });

//     // Initially load some data when the page loads
//     loadMoreData();
// });

    $( document ).ready(function() 
    {     
           GetBomdisData();   
    });
    
    function GetBomdisData()
    {
        var trimCode = $("#trimCode").val();
        var po_code = $("#po_code").val();
        
        $.ajax({
              type: "GET",
              dataType:"json",
              async: true,
              url: "{{ route('GetTrimCodeWiseData') }}",
              data:{'trimCode':trimCode,'po_code' : po_code},
              success: function(data)
              {
                  $("#bomdis").html(data.html); 
                  $("#bomdis1").html(data.html); 
                  $(".btn-danger").not("button[name='allocate[]']").attr("disabled", true);
              }
        }); 
    }
    
    function SetQtyToBtn(obj)
    { 
        var qty = $(obj).val();
        $(obj).parent().parent('tr').find('td button[name="allocate[]"]').attr('qty', qty);
    }
    
   function stockAllocate() 
   {
     
      let isValid = true;

      $("#footable_2 tbody tr").each(function () {
         let qty = $(this).find('td input[name="item_qtys[]"]').val();

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
      $('#Submit').prop('disabled', false);
      var Click = $("#mainAllocation").attr('isClick');

      // -------------------------
      // 1️⃣ Validate Qty > 0
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

   
  function stockAllocate1() 
  {
      // ---------- 3️⃣ Validate Qty > 0 ----------
      let isValid = true;

      $("#detailTbl tr").each(function () {
         let qty = $(this).find('td input[name="item_qtys[]"]').val();

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

      // Enable submit button
      $('#Submit').prop('disabled', false);

      // ---------- 1️⃣ Prevent double click ----------
      if ($("#mainAllocation1").data("running") === 1) {
         alert("Already processing...!");
         return;
      }
      $("#mainAllocation1").data("running", 1);

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
   function insertRow(Abutton){
       var rowsx=$(Abutton).closest("tr");
   $("#item_codes").select2("destroy");
   $("#rack_id").select2("destroy");
   var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px; height:30px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+index;
   t1.name= "id[]";
   t1.value=index;
   cell1.appendChild(t1);
   
   var cell5 = row.insertCell(1);
   var t5=document.createElement("select");
   var x = $("#item_codes"),
   y = x.clone();
   y.attr("id","item_codes");
   y.attr("name","item_codes[]");
   y.width(252);
   y.height(30);
   y.appendTo(cell5);
     
   var cell2 = row.insertCell(2);
   var t2=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_ids");
   y.attr("name","unit_ids[]");
   y.width(100);
   y.height(30);
   var unit_id=+rowsx.find('select[name^="unit_ids[]"]').val();
   y.val(unit_id);
   y.attr("selected","selected"); 
   y.appendTo(cell2);
   
   
   var cell3 = row.insertCell(3);
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
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   t3.step="any";
   t3.required="true";
   t3.id = "item_rates"+index;
   t3.name="item_rates[]";
   t3.value="0";
   cell3.appendChild(t3);
   
   var cell3 = row.insertCell(5);
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
   
   
   var cell2 = row.insertCell(6);
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
   
   var cell15=row.insertCell(7);
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left Abutton";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "  mycalc();");
   cell15.appendChild(btnAdd);
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.style="margin-left: 10px;";
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRow(this)");
   cell15.appendChild(btnRemove);
   
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
   function insertRow1(Abutton){
       var rowsx=$(Abutton).closest("tr");
   $("#item_codes").select2("destroy");
   $("#rack_id").select2("destroy");
   var table=document.getElementById("footable_21").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px; height:30px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+index;
   t1.name= "id[]";
   t1.value=index;
   cell1.appendChild(t1);
   
   var cell5 = row.insertCell(1);
   var t5=document.createElement("select");
   var x = $("#item_codes"),
   y = x.clone();
   y.attr("id","item_codes");
   y.attr("name","item_codes[]");
   y.width(252);
   y.height(30);
   y.appendTo(cell5);
     
   var cell2 = row.insertCell(2);
   var t2=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_ids");
   y.attr("name","unit_ids[]");
   y.width(100);
   y.height(30);
   var unit_id=+rowsx.find('select[name^="unit_ids[]"]').val();
   y.val(unit_id);
   y.attr("selected","selected"); 
   y.appendTo(cell2);
   
   
   var cell3 = row.insertCell(3);
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
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   t3.step="any";
   t3.required="true";
   t3.id = "item_rates"+index;
   t3.name="item_rates[]";
   t3.value="0";
   cell3.appendChild(t3);
   
   var cell3 = row.insertCell(5);
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
   
   
   var cell2 = row.insertCell(6);
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
   
   var cell15=row.insertCell(7);
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left Abutton1";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "  mycalc();");
   cell15.appendChild(btnAdd);
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.style="margin-left: 10px;";
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRow(this)");
   cell15.appendChild(btnRemove);
   
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
                $(row).find('input[name^="hsn_code[]"]').val(data.data[0].unit_id);
                $(row).find('select[name^="unit_ids[]"]').val(data.data[0].unit_id).change();
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
   }, 1000);
   }
   
   
   function deleteRow(btn) 
   { 
         var row = btn.parentNode.parentNode;
         row.parentNode.removeChild(row);
         
         recalcId();
         mycalc();  
   }
   
   function recalcId(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
   function getPODetails()
   {
      
       var po_code=$("#po_code").val();
       
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('getTrimsPODetails') }}",
               data:{'po_code':po_code},
               success: function(data)
               {
                   
               //   $("#po_type_id").val(data[0]['po_type_id']);
                  $("#Ac_code").val(data[0]['Ac_code']);                    
                  var qhtml=data[0]["ac_name"];
                  var q=data[0]['Ac_code'];
                  $('#Ac_code').val(data[0]["Ac_code"]);
                  // $('#select2-chosen-1').html(qhtml);   
               }
           });
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
      $(".QTY").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumQty += v;
      });

      let totalqty = safeVal("#totalqty");
      if (totalqty) totalqty.val(sumQty.toFixed(2));

      let totalqty2 = safeVal("#totalqty1");
      if (totalqty2) totalqty2.val(sumQty.toFixed(2));

      // SUM AMOUNT
      let sumAmt = 0;
      $(".AMT").each(function () {
         let v = parseFloat($(this).val()) || 0;
         sumAmt += v;
      });

      let totalAmt = safeVal("#total_amount");
      if (totalAmt) totalAmt.val(sumAmt.toFixed(2));

      let totalAmt2 = safeVal("#total_amount1");
      if (totalAmt2) totalAmt2.val(sumAmt.toFixed(2));
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
       $("#bomdis1").append(response.html);
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
   
   setInterval(function() {mycalc()}, 1000);
   
   //  setInterval(fun, 3000);  
   
   function gettable(po_code){
   
      
   var po_codes=btoa(po_code);
   $.ajax({
   type:"GET",
   url:"{{ route('getPoForTrims') }}",
   //dataType:"json",
   data:{po_code:po_codes},
   success:function(response){
   console.log(response);  
       $("#trimInward").html(response.html);
   
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
   
   
   function deleteRow(btn) 
   { 
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row); 
       recalcId();
       mycalc(); 
   }   
   
</script>
@endsection