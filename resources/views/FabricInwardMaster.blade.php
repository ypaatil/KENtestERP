@extends('layouts.master') 
@section('content')
<style>
   .hide
   {
   display:none!important;
   }
   .text-right
   {
      text-align: right;
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
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Inward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Inward</li>
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

                  <form action="{{route('FabricInward.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
                     @csrf 
                     <div class="row">
                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="in_date" class="form-label">In Date</label>
                              <input type="date" name="in_date" class="form-control" id="in_date" value="{{date('Y-m-d')}}" required>
                              @foreach($counter_number as  $row)
                              <input type="hidden" name="in_code" class="form-control" id="in_code" value="{{ 'GRN/25-26/FP'.''.$row->tr_no }}">
                              <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                              <input type="hidden" name="cp_id" class="form-control" id="cp_id" value="1">
                              <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="1">
                              <input type="hidden" name="PBarcode" class="form-control" id="PBarcode" value="{{ $row->PBarcode }}">
                              <input type="hidden" name="CBarcode" class="form-control" id="CBarcode" value="{{ $row->CBarcode }}">
                              @endforeach
                              <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="invoice_no" class="form-label">Invoice No</label>
                              <input type="text" name="invoice_no" id="invoice_no" class="form-control" required>
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="invoice_date" class="form-label">Invoice Date</label>
                              <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{date('Y-m-d')}}">
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="mb-3">
                              <label for="po_code" class="form-label">PO No.</label>   
                              <select name="po_code" class="form-select select2" id="po_code" onchange="getDetails(this.value);GetPurchaseBillDetails();">
                                 <option value="">PO code</option>
                                 @foreach($POList as  $rowpol)
                                 <option value="{{ $rowpol->pur_code }}" 
                                    {{ $rowpol->pur_code == request()->po_code ? 'selected="selected"' : '' }}>
                                    {{ $rowpol->pur_code }}
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <div class="mb-3">
                              <label for="po_type_id" class="form-label">PO Type</label>
                              <select name="po_type_id" class="form-select" id="po_type_id" disabled >
                                 <option value="">Type</option>
                                 @foreach($POTypeList as  $rowpo)
                                 <option value="{{ $rowpo->po_type_id }}">{{ $rowpo->po_type_name }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="Ac_code" class="form-label">Supplier</label>
                              <select name="Ac_code" class="form-select" id="Ac_code"  disabled>
                                 <option value="">--Select Supplier--</option>
                                 @foreach($Ledger as  $row)
                                 <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
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
                              <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening" 
                                 style="font-size: 25px;margin-top: 30px;margin-left: 0px;" 
                                 onclick="enable(this);DisabledPO(this);">

                              <label class="form-check-label" 
                                 for="is_opening" 
                                 style="margin-top: 30px;position: absolute;margin-left: 20px;font-size: 16px;">
                                 Opening Stock
                              </label>
                           </div>
                        </div>

                        <div class="col-md-3">
                           <label for="fge_code" class="form-label">Fabric Gate Code</label>
                           <select name="fge_code" class="form-select select2" id="fge_code" required>
                              <option value="">--Select--</option>
                              @foreach($FGECodeList as  $row)
                              <option value="{{ $row->fge_code }}">{{ $row->fge_code }}</option> 
                              @endforeach
                           </select>
                        </div>

                        <div class="col-md-3">
                           <label for="location_id" class="form-label">Location/Warehouse</label>
                           <select name="location_id" class="form-select select2" id="location_id" required>
                              <option value="">--Location--</option>
                              @foreach($LocationList as  $row)
                              <option value="{{ $row->loc_id }}" {{ $row->loc_id == 1 ? 'selected="selected"' : '' }}>
                                 {{ $row->location }}
                              </option> 
                              @endforeach
                           </select>
                        </div>
                     </div>

                     <!-- PURCHASE TABLE -->
                     <div class="table-wrap" id="PurchaseTbl">
                        <div class="table-responsive">
                           <table id="footable_1" class="table table-bordered table-striped m-b-0 footable_2">
                              <thead>
                                 <tr class="text-center">
                                    <th>Sr No.</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>PO Qty</th> 
                                    <th>Received</th> 
                                    <th>Balance Qty</th> 
                                 </tr>
                              </thead>
                              <tbody id="PurchaseTbody">
                              </tbody>
                           </table>
                        </div>
                     </div>

                     <!-- FABRIC INWARD TABLE -->
                     <div class="table-wrap" id="fabricInward">
                        <div class="table-responsive">
                           <table id="footable_2" class="table table-bordered table-striped m-b-0 footable_2">
                              <thead>
                                 <tr>
                                    <th>Roll No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Part</th>
                                    <th>Meter</th>
                                    <th>Gram/Meter</th>
                                    <th>KG</th>
                                    <th>Rate Per Meter</th>
                                    <th>Amount</th>
                                    <th nowrap>Suplier Roll No.</th>
                                    <th>Track Code</th>
                                    <th>Add</th>
                                    <th>Remove</th>
                                 </tr>
                              </thead>

                              <tbody>
                                 <tr>
                                    <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"></td>

                                    <td><input type="text" name="item_codes[]" value="" id="item_codes" style="width:80px;" readonly></td>

                                    <td>
                                       <select name="item_code[]" id="item_code" class="select2" style="width:200px;height:30px;" required onchange="getRateFromPO(this);">
                                          <option value="">--Item--</option>
                                          @foreach($ItemList as  $row)
                                          <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                          @endforeach
                                       </select>
                                    </td>

                                    <td>
                                       <select name="part_id[]" id="part_id" class="select2" style="width:200px;height:30px;" required>
                                          <option value="">--Part--</option>
                                          @foreach($PartList as  $row)
                                          <option value="{{ $row->part_id }}" {{ $row->part_id == 1 ? 'selected="selected"' : '' }}>
                                             {{ $row->part_name }}
                                          </option>
                                          @endforeach
                                       </select>
                                    </td>

                                    <td>
                                       <input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;height:30px;">
                                       <input type="number" step="any" min="0" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;height:30px;" required>
                                    </td>

                                    <td><input type="number" step="any" min="0" name="gram_per_meter[]" value="0" id="gram_per_meter" style="width:80px;height:30px;"></td>

                                    <td><input type="number" step="any" min="0" @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; } @endphp class="KG" 
                                       name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;height:30px;"  readOnly></td>

                                    <td>
                                       <input type="number" step="any" min="0" name="item_rates[]" value="0" id="item_rates" style="width:80px;height:30px;" 
                                          @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; } @endphp  readOnly>
                                    </td>

                                    <td><input type="number" step="any" min="0" class="AMT" readOnly name="amounts[]" value="0" id="amounts" style="width:80px;height:30px;"  readOnly></td>

                                    <td><input type="number" step="any" min="0" class="suplier_roll_no" name="suplier_roll_no[]" value="" id="suplier_roll_no" style="width:100px;height:30px;" required></td>

                                    <td><input type="text" name="track_code[]" id="track_code" style="width:80px;height:30px;" readOnly></td>

                                    <td>
                                       <input type="button" style="width:40px;" onclick="insertcone();" name="print" value="+" class="btn btn-warning pull-left AButton">
                                    </td>

                                    <td>
                                       <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X">
                                    </td>
                                 </tr>
                              </tbody>

                              <tfoot>
                                 <tr>
                                    <th>Roll No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Part</th>
                                    <th>Meter</th>
                                    <th>Gram/Meter</th>
                                    <th>KG</th>
                                    <th>Rate Per Meter</th>
                                    <th>Amount</th>
                                    <th>Suplier Roll No.</th>
                                    <th>Track Code</th>
                                    <th>Add</th>
                                    <th>Remove</th>
                                 </tr>
                              </tfoot>

                              <input type="number" value="1" name="cntrr" id="cntrr" readonly hidden>
                           </table>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_meter" class="form-label">Total Meter</label>
                              <input type="number" readOnly step="0.01" name="total_meter" class="form-control" id="total_meter" value="0">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_kg" class="form-label">Total KG</label>
                              <input type="number" readOnly step="0.01" name="total_kg" class="form-control" id="total_kg" value="0">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_taga_qty" class="form-label">Total No of Roll</label>
                              <input type="number" readOnly name="total_taga_qty" class="form-control" id="total_taga_qty" value="1">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_amount" class="form-label">Total Amount</label>
                              <input type="text" name="total_amount" readOnly class="form-control" id="total_amount" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                           <div class="mb-3">
                              <label for="in_narration" class="form-label">Narration</label>
                              <input type="text" name="in_narration" class="form-control" id="in_narration">
                           </div>
                        </div>

                        <div class="col-sm-6">
                           <label class="form-label"></label>
                           <div class="form-group">
                              <button type="submit" class="btn btn-primary w-md" onclick="UpdateBarcode(); EnableFields();" id="Submit">Submit</button>
                              <a href="{{ Route('FabricInward.index') }}" class="btn btn-warning w-md">Cancel</a>
                           </div>
                        </div>
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

                  <form action="{{route('FabricInward.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
                     @csrf 
                     @foreach($counter_number as  $row)
                     <input type="hidden" name="in_code" class="form-control" id="in_code" value="{{ 'GRN/25-26/FP'.''.$row->tr_no }}">
                     <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
                     <input type="hidden" name="in_date" class="form-control" id="in_date" value="{{ date('Y-m-d') }}">
                     <input type="hidden" name="cp_id" class="form-control" id="cp_id1" value="1">
                     <input type="hidden" name="Ac_code" class="form-control" id="Ac_code1" value="">
                     <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="2">
                     <input type="hidden" name="PBarcode" class="form-control" id="PBarcode" value="{{ $row->PBarcode }}">
                     <input type="hidden" name="CBarcode" class="form-control" id="CBarcode" value="{{ $row->CBarcode }}">
                     @endforeach
                     <div class="row">
                        <div class="col-md-3">
                           <div class="mb-3">
                              <label for="invoice_date" class="form-label">DC Date</label>
                              <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{date('Y-m-d')}}">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="invoice_no" class="form-label">DC No</label>
                              <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                              <input type="text" name="invoice_no" id="invoice_no1" class="form-control" required>
                              <select name="invoice_no" class="form-select select2 hide" id="focd_code" onchange="GetFabricCuttingDeptData();">
                                 <option value="">--Select--</option>
                                 @foreach($FabricCuttingOutwardList as  $row)
                                 <option value="{{ $row->focd_code }}">{{ $row->focd_code }}</option> 
                                 @endforeach
                              </select>
                           </div>
                        </div> 
                        <div class="col-md-3">
                           <label for="fge_code" class="form-label">Fabric Gate Code</label>
                           <select name="fge_code" class="form-select select2" id="fge_code" required>
                              <option value="">--Select--</option>
                              @foreach($FGECodeList as  $row)
                              <option value="{{ $row->fge_code }}">{{ $row->fge_code }}</option> 
                              @endforeach
                           </select>
                        </div>

                        <div class="col-md-4">
                           <label for="location_id" class="form-label">Location/Warehouse</label>
                           <select name="location_id" class="form-select select2" id="location_id" required>
                              <option value="">--Location--</option>
                              @foreach($LocationList as  $row)
                              <option value="{{ $row->loc_id }}" {{ $row->loc_id == 1 ? 'selected="selected"' : '' }}>
                                 {{ $row->location }}
                              </option> 
                              @endforeach
                           </select>
                        </div>
                        <div class="col-md-3 mt-4 m-0">
                           <div class="mb-3">
                              <div class="form-check form-check-primary mb-5">
                                 <input class="form-check-input" type="checkbox" id="isReturnFabricInward" onchange="GetOrderNo(this); GetDCDropdown();"  name="isReturnFabricInward" style="font-size: 30px;margin-left: 0px;margin-top: -3px;">

                                 <label class="form-check-label" for="isReturnFabricInward" style="position: absolute;margin-left: 20px;font-size: 14px;">
                                       Fabric Return From Inhouse
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
                        <div class="col-md-3" id="workOrder">
                           <div class="mb-3">
                              <label for="" class="form-label">Vendor Process Order No.</label>   
                              <select name="vpo_code" class="form-select select2" id="vpo_code" onchange="GetVendorName(this.value);">
                                 <option value="">Vendor Process Order No.</option>
                                 @foreach($vendorProcessOrderList as  $vendors)
                                 <option value="{{ $vendors->vpo_code }}"  > {{ $vendors->vpo_code }} </option>
                                 @endforeach
                              </select>
                           </div>
                        </div>

                        <div class="col-md-3" id="vendorData">
                           <div class="mb-3">
                              <label for="" class="form-label">Vendor Name</label>   
                              <select name="vendorId" class="form-select select2" id="vendorId" >
                                 <option value="">--Select--</option>
                                 @foreach($vendorData as  $rows)<option value="{{ $rows->ac_code }}"  > {{ $rows->ac_short_name }}</option>
                                 @endforeach
                              </select> 
                           </div>
                        </div>
                     </div>

                     <!-- PURCHASE TABLE -->
                     <div class="table-wrap" id="OutwardTbl">
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

                     <!-- FABRIC INWARD TABLE -->
                     <div class="table-wrap" id="fabricInward">
                        <div class="table-responsive">
                           <table id="footable_3" class="table table-bordered table-striped m-b-0 footable_2">
                              <thead>
                                 <tr>
                                    <th>Roll No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Part</th>
                                    <th>Meter</th>
                                    <th>Gram/Meter</th>
                                    <th>KG</th>
                                    <th>Rate Per Meter</th>
                                    <th>Amount</th>
                                    <th nowrap>Suplier Roll No.</th>
                                    <th>Track Code</th>
                                    <th>Add</th>
                                    <th>Remove</th>
                                 </tr>
                              </thead>

                              <tbody id="detailTbl">
                                 <tr>
                                    <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"></td>

                                    <td><input type="text" name="item_codes[]" value="" id="item_codes" style="width:80px;" readonly></td>

                                    <td>
                                       <select name="item_code[]" id="item_code" class="select2" style="width:200px;height:30px;" required onchange="getRateFromPO(this);">
                                          <option value="">--Item--</option>
                                          @foreach($ItemList as  $row)
                                          <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                          @endforeach
                                       </select>
                                    </td>

                                    <td>
                                       <select name="part_id[]" id="part_id" class="select2" style="width:200px;height:30px;" required>
                                          <option value="">--Part--</option>
                                          @foreach($PartList as  $row)
                                          <option value="{{ $row->part_id }}" {{ $row->part_id == 1 ? 'selected="selected"' : '' }}>
                                             {{ $row->part_name }}
                                          </option>
                                          @endforeach
                                       </select>
                                    </td>

                                    <td>
                                       <input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;height:30px;">
                                       <input type="number" step="any" min="0" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;height:30px;" required>
                                    </td>

                                    <td><input type="number" step="any" min="0" name="gram_per_meter[]" value="0" id="gram_per_meter" style="width:80px;height:30px;" required></td>

                                    <td><input type="number" step="any" min="0" @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; } @endphp class="KG" 
                                       name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;height:30px;" required></td>

                                    <td>
                                       <input type="number" step="any" min="0" name="item_rates[]" value="0" id="item_rates" style="width:80px;height:30px;" 
                                          @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; } @endphp required>
                                    </td>

                                    <td><input type="number" step="any" min="0" class="AMT" readOnly name="amounts[]" value="0" id="amounts" style="width:80px;height:30px;"></td>

                                    <td><input type="text" class="suplier_roll_no" name="suplier_roll_no[]" value="" id="suplier_roll_no" style="width:100px;height:30px;" required></td>

                                    <td><input type="text" name="track_code[]" id="track_code1" style="width:80px;height:30px;" readOnly></td>

                                    <td>
                                       <input type="button" style="width:40px;" onclick="insertcone1();" name="AButton" value="+" class="btn btn-warning pull-left AButton">
                                    </td>

                                    <td>
                                       <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X">
                                    </td>
                                 </tr>
                              </tbody>

                              <tfoot>
                                 <tr>
                                    <th>Roll No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Part</th>
                                    <th>Meter</th>
                                    <th>Gram/Meter</th>
                                    <th>KG</th>
                                    <th>Rate Per Meter</th>
                                    <th>Amount</th>
                                    <th>Suplier Roll No.</th>
                                    <th>Track Code</th>
                                    <th>Add</th>
                                    <th>Remove</th>
                                 </tr>
                              </tfoot>

                              <input type="number" value="1" name="cntrr" id="cntrr1" readonly hidden>
                           </table>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_meter" class="form-label">Total Meter</label>
                              <input type="number" readOnly step="0.01" name="total_meter" class="form-control" id="total_meter1" value="0">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_kg" class="form-label">Total KG</label>
                              <input type="number" readOnly step="0.01" name="total_kg" class="form-control" id="total_kg1" value="0">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_taga_qty" class="form-label">Total No of Roll</label>
                              <input type="number" readOnly name="total_taga_qty" class="form-control" id="total_taga_qty1" value="1">
                           </div>
                        </div>

                        <div class="col-md-2">
                           <div class="mb-3">
                              <label for="total_amount" class="form-label">Total Amount</label>
                              <input type="text" name="total_amount" readOnly class="form-control" id="total_amount1" required>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-sm-6">
                           <div class="mb-3">
                              <label for="in_narration" class="form-label">Narration</label>
                              <input type="text" name="in_narration" class="form-control" id="in_narration">
                           </div>
                        </div>

                        <div class="col-sm-6">
                           <label class="form-label"></label>
                           <div class="form-group">
                              <button type="submit" class="btn btn-primary w-md" onclick="UpdateBarcode(); EnableFields();" id="Submit">Submit</button>
                              <a href="{{ Route('FabricInward.index') }}" class="btn btn-warning w-md">Cancel</a>
                           </div>
                        </div>
                     </div>

                  </form>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>

<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
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
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
   });
   
   $("#return-tab").click(function () {
      serBarocode1();
   });

   function DisabledPO(el)
   {
      if($(el).is(":checked"))
      {
         
         $("#is_opening").attr("disabled", true);
         $("#po_code").attr("disabled", true);
         $("#po_type_id").val(2).attr("disabled", true);
         $("#Ac_code").val(50).trigger('change').attr("disabled", true);
         // $("#isReturnFabricInward").prop('checked', false).attr("disabled", true);
         setTimeout(function() {
               $("#bill_to").val(1083).trigger('change');
         }, 1000);

         $("#fge_code").prop("required", false);
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
 
         $("#fge_code").attr("disabled", false).removeAttr("required");
      }
      $("#bill_to").attr("disabled", true);
      $("#fge_code").prop("required", false);
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
       $("#bill_to").attr('disabled', true);
       $("#isReturnFabricInward").prop('checked', false).attr('disabled', true);
        if(po_code !='')
        {
            $("#is_opening").prop('checked', false).attr('disabled', true);
        }
    } 
    
   function GetFabricOutwardData()
   {
         $("#isReturnFabricInward").attr('disabled', true);
         $("#isOutsideVendor").attr('disabled', true);
         var vpo_code = $("#vpo_code").val();
         $.ajax({
            type:"GET",
            url:"{{ route('GetFabricInwardOutwardData') }}", 
            data:{vpo_code:vpo_code},
            success:function(response)
            {
               $("#OutwardTbody").html(response.html);            
            }
         });  

         GetVendorName(vpo_code);
   }

   function GetVendorName(vpo_code)
   {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetVendorName') }}",
          data:{'vpo_code':vpo_code},
          success: function(data)
          {
               $("#vendorId").val(data.ac_code).trigger('change');
               $("#Ac_code1").val(data.ac_code);
               $('#vendorData').removeClass('hide');
               
          }
         });

         if(!$("#isReturnFabricInward").is(":checked"))
         {
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('GetItemPucharseOrder') }}",
               data:{'vpo_code':vpo_code},
               success: function(data)
               {
                  $('select[name="item_code[]"]').html(data.html); 
               }
            });
         }
   }

   function GetFabricCuttingDeptData()
   {
        var focd_code  = $("#focd_code").val();
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetFabricCuttingDeptData') }}",
          data:{'focd_code':focd_code},
          success: function(data)
          {
              $('#detailTbl').html(data.html); 
              $('#vpo_code').val(data.vpo_code).trigger('change'); 
              GetVendorName(data.vpo_code);
          }
        }); 

   }

   function GetOrderNo(ele)
   {
      //  if($(ele).is(":checked"))
      //  {
      //      $('#workOrder').removeClass('hide');
      //      $(ele).val(1);
      //      $("#po_code").removeAttr('onchange'); 
      //      $("#is_opening").attr('disabled', true).prop('checked', true).trigger("change");
      //      $("#po_code").val("").attr('disabled', true).trigger("change");
      //      $("#fge_code").val("").trigger("change").attr('disabled', true);
      //      $("#Ac_code").val(50).trigger("change").attr('disabled', true);
      //      $("#bill_to").val(26).trigger("change").attr('disabled', true);
      //  }
      //  else
      //  {
      //      $("#po_code").attr('onchange', 'getPODetails();GetPurchaseBillDetails();'); 
      //      $("#is_opening").prop('checked', false).trigger("change").attr('disabled', true);
      //      $("#po_code").attr('disabled', false);
      //      $("#fge_code").val("").trigger("change").attr('disabled', false);
      //      $('#workOrder').addClass('hide');
      //      $(ele).val(0);
      //  }
   }   
   
   function DisableDropdown()
   {
         if($("#isOutsideVendor").is(":checked"))
         {
            $("#isReturnFabricInward").attr('disabled', true); 
            $("#vpo_code").attr('onchange', 'GetFabricOutwardData()');
         }
         else
         {
            $("#isReturnFabricInward").attr('disabled', false);  
         }          

   } 

   function GetDCDropdown()
   { 
         if($("#isReturnFabricInward").is(":checked"))
         {
            $("#isOutsideVendor").attr('disabled', true);
            $("#invoice_no1").removeAttr('name').removeAttr('required').addClass("hide");
            $("#focd_code").attr('name', 'invoice_no').attr('required', true).removeClass("hide"); 
         }
         else
         {
            $("#isOutsideVendor").attr('disabled', false);
            $("#invoice_no1").attr('name', 'invoice_no').attr('required', true).removeClass("hide");
            $("#focd_code").removeAttr('name').removeAttr('required').addClass("hide"); 
         }
   }

   function enable(opening)
   {  
   @php $user_type=Session::get('user_type'); if($user_type!=1){  @endphp
      if(opening.checked==true)
      {  
        $("#footable_2 tr td  select[name='item_code[]']").each(function() {
            $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', false);
         });
        
      }
      else
      {
          $("#footable_2 tr td  select[name='item_code[]']").each(function() {
            $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', true);
          });
      }
   
   @php } @endphp
   
   }
   
   
   
   function getRateFromPO(row)
   {
        var po_code=$('#po_code').val();
        var item_code = $(row).val();
        
        $(row).parent().parent('tr').find('td input[name="item_codes[]"]').val(item_code);  
        var row = $(row).closest('tr'); 
        
        if(po_code != '')
        {
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('ItemRateFromPO') }}",
               data:{'po_code':po_code,item_code:item_code},
               success: function(data)
               {
                     +row.find('input[name^="item_rates[]"]').val(data[0]['item_rate']);              
               }
            });   
        }
   }
   
   
   
   var PBarcode=$("#PBarcode").val();
   var CBarcode=$("#CBarcode").val();
   
   
   function UpdateBarcode()
   {
        $("#PBarcode").val(PBarcode);
        $("#CBarcode").val(CBarcode);
         
   }
   
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
       $("input").prop('disabled', false);
   }
   
   function serBarocode1()
   { 
      if($("#cp_id1").val()==1)
      { 
         ++PBarcode;
         $("#track_code1").val('P'.concat(PBarcode.toString()));
         //alert($("#track_code").val());
      }
      else if($("#cp_id1").val()==2)
      {      
         var CBar='';
         CBar='I' + parseInt(++CBarcode);
         $("#track_code1").val(CBar);
      }
   }
   
   
   function serBarocode()
   { 
      if($("#cp_id").val()==1)
      { 
         ++PBarcode;
         $("#track_code").val('P'.concat(PBarcode.toString()));
         //alert($("#track_code").val());
      }
      else if($("#cp_id").val()==2)
      {      
         var CBar='';
         CBar='I' + parseInt(++CBarcode);
         $("#track_code").val(CBar);
      }
   }
   
   
   
   $(document).ready(function()
   {
        serBarocode();
   }); 
   
   function getPODetails()
   {     
      var po_code=$("#po_code").val();
      if(po_code !='')
      {
         $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('PODetail') }}",
            data:{'po_code':po_code},
            success: function(data)
            {                     
               $("#po_type_id").val(data[0]['po_type_id']);
               $("#Ac_code").val(data[0]['Ac_code']);                  
            }
         }); 
      } 
   }
   
   $(document).on("keyup", 'input[name^="gram_per_meter[]"],input[name^="meter[]"]', function (event) {
          CalculateRow($(this).closest("tr"));
          
      });
    	
   function CalculateRow(row)
   {  
   	   var gram_per_meter=+row.find('input[name^="gram_per_meter[]"]').val();
         var meter=+row.find('input[name^="meter[]"]').val();

    	   var kg=parseFloat(parseFloat(meter).toFixed(2) * parseFloat(gram_per_meter).toFixed(2)).toFixed(2);
    	
         row.find('input[name^="kg[]"]').val(kg.toFixed(2));
   	   mycalc();
   }
   
   
   // $(document).on("click", 'input[name^="print[]"]', function (event) {
      
   //        CalculateRowPrint($(this).closest("tr"));
          
   // });
    	
   // function CalculateRowPrint(btn)
   // { 
   //        var row = $(btn).closest("tr");
   //        var width=+row.find('input[name^="width[]"]').val();
   //        var meter=+row.find('input[name^="meter[]"]').val();
   //        var kg=+row.find('input[name^="kg[]"]').val();
   //        var color_id=+row.find('select[name^="color_id[]"]').val();
   //        var part_id=+row.find('select[name^="part_id[]"]').val();
   //        var quality_code=+row.find('select[name^="quality_code[]"]').val();
   //        var track_code=row.find('input[name^="track_code[]"]').val();
   //        var style_no=$("#style_no").val();
   //        var job_code=$("#job_code").val();
          
   //        //alert(track_code);
   //        $.ajax({
   //            type: "GET",
   //            dataType:"json",
   //            url: "{{ route('PrintBarcode') }}",
   //            data:{'width':width,'meter':meter,'color_id':color_id,'quality_code':quality_code,'kg':kg,  'part_id':part_id,'track_code':track_code,'style_no':style_no,'job_code':job_code},
   //            success: function(data){
                   
   //             if((data['result'])=='success')
   //            {
   //              alert('Print Barcode For Roll: '+track_code);
   //            }
   //            else
   //            {
   //                $alert('Data Can Not Be Printed');
   //            }
              
   //        }
   //        });
          
   // }
   
   
   
   
   var indexcone = 2;
   
   function insertcone()
   {      
      $("#item_code").select2("destroy");
      $("#part_id").select2("destroy");
      var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
      var row=table.insertRow(table.rows.length);
      
      var cell1=row.insertCell(0);
      var t1=document.createElement("input");
      t1.style="display: table-cell; width:50px;";
      //t1.className="form-control col-sm-1";
      
      t1.id = "id"+indexcone;
      t1.name= "id[]";
      t1.value=indexcone;
      
      cell1.appendChild(t1);
      
      
      var cell1=row.insertCell(1);
      var t1=document.createElement("input");
      t1.style="display: table-cell; width:80px;";
      //t1.className="form-control col-sm-1";
      
      t1.id = "item_codes"+indexcone;
      t1.name= "item_codes[]";
      
      cell1.appendChild(t1);
      
      var cell5 = row.insertCell(2);
      var t5=document.createElement("select");
      var x = $("#item_code"),
      y = x.clone();
      y.attr("id","item_code");
      y.attr("name","item_code[]");
      y.width(200);
      y.height(30);
      y.appendTo(cell5);
      
      
      
      var cell3 = row.insertCell(3);
      var t3=document.createElement("select");
      var x = $("#part_id"),
      y = x.clone();
      y.attr("id","part_id");
      y.attr("name","part_id[]");
      y.width(200);
      y.height(30);
      y.appendTo(cell3);
      
      
      var t7=document.createElement("input");
      t7.style="display: table-cell; width:80px;height:30px;";
      t7.type="hidden";
      t7.className="TAGAQTY";
      t7.required="true";
      t7.id = "taga_qty"+indexcone;
      t7.name="taga_qty[]";
      t7.onkeyup=mycalc();
      t7.value="1";
      cell3.appendChild(t7);
      
      var cell7 = row.insertCell(4);
      var t8=document.createElement("input");
      t8.style="display: table-cell; width:80px;height:30px;";
      t8.type="number";
      t8.step="any";
      t8.className="METER";
      t8.id = "meter"+indexcone;
      t8.name="meter[]";
      t8.onkeyup=mycalc();
      cell7.appendChild(t8);
      
      var cell7 = row.insertCell(5);
      var t8=document.createElement("input");
      t8.style="display: table-cell; width:80px;height:30px;";
      t8.type="number";
      t8.step="any";
      t8.id = "gram_per_meter"+indexcone;
      t8.name="gram_per_meter[]";
      t8.value=$('#gram_per_meter').val();
      t8.onkeyup=mycalc();
      cell7.appendChild(t8);
      
      var cell7 = row.insertCell(6);
      var t8=document.createElement("input");
      t8.style="display: table-cell; width:80px;height:30px;";
      t8.type="number";
      t8.step="any";
      t8.className="KG";
      t8.id = "kg"+indexcone;
      t8.name="kg[]";
      t8.readOnly=true;
      t8.value=$('#kg').val();
      t8.onkeyup=mycalc();
      cell7.appendChild(t8);
      
      var cell3 = row.insertCell(7);
      var t3=document.createElement("input");
      t3.style="display: table-cell; width:80px;height:30px;";
      t3.type="number";
      t3.step="any";
      t3.readOnly="true";
      t3.id = "item_rates"+indexcone;
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
      t3.id = "amounts"+indexcone;
      t3.name="amounts[]";
      t3.value="0";
      cell3.appendChild(t3);
      
      var cell4 = row.insertCell(9);
      var t4=document.createElement("input");
      t4.style="display: table-cell; width:100px;height:30px;";
      t4.type="number";
      t4.step="any"; 
      t4.className="suplier_roll_no";
      t4.id = "suplier_roll_no"+indexcone;
      t4.name="suplier_roll_no[]";
      t4.value="";
      t4.required="true";
      cell4.appendChild(t4);
      
      var cell7 = row.insertCell(10);
      var t7=document.createElement("input");
      t7.style="display: table-cell; width:80px;height:30px;";
      t7.type="text";
      t7.readOnly=true;
      t7.id = "track_code"+indexcone;
      t7.name="track_code[]";
      if($("#cp_id").val()==1)
      {
         ++PBarcode;
      t7.value='P'+PBarcode;
      }
      else
      {
         ++CBarcode;
         t7.value='I'+CBarcode;
      } 
      
      
      cell7.appendChild(t7);   
      
      var cell8=row.insertCell(11);
      var btnAdd = document.createElement("INPUT");
      btnAdd.id = "Abutton";
      btnAdd.type = "button";
      btnAdd.name = "print";
      btnAdd.className="btn btn-warning pull-left";
      btnAdd.value = "+";
      btnAdd.setAttribute("onclick", "insertcone();");
      cell8.appendChild(btnAdd);
      
      var cell9=row.insertCell(12);
      var btnRemove = document.createElement("INPUT");
      btnRemove.id = "Dbutton";
      btnRemove.type = "button";
      btnRemove.className="btn btn-danger pull-left";
      btnRemove.value = "X";
      btnRemove.setAttribute("onclick", "deleteRowcone(this)");
      cell9.appendChild(btnRemove);
      
      var w = $(window);
      var row = $('#footable_3').find('tr').eq(indexcone);
      
      if (row.length){
      $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
      }
      
      document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
      
      indexcone++;
      mycalc();
      recalcIdcone();
      
      $("#item_code").select2();
      $("#part_id").select2();

      selselect();
      $("#footable_2 tbody tr").find("td input[name='item_rates[]']").prop('readonly', false);   // most reliable
      
   }
   
   var indexcone = 2;
   
  function insertcone1() 
  {
    $("#item_code").select2("destroy");
    $("#part_id").select2("destroy");

    var table = document.getElementById("footable_3").getElementsByTagName('tbody')[0];
    var row = table.insertRow(table.rows.length);

    // ---------------- ID ----------------
    var cell1 = row.insertCell(0);
    var t1 = document.createElement("input");
    t1.style = "display: table-cell; width:50px;";
    t1.id = "id" + indexcone;
    t1.name = "id[]";
    t1.value = indexcone;
    cell1.appendChild(t1);

    // ---------------- Item Code Text ----------------
    var cell2 = row.insertCell(1);
    var t2 = document.createElement("input");
    t2.style = "display: table-cell; width:80px;";
    t2.id = "item_codes" + indexcone;
    t2.name = "item_codes[]";
    cell2.appendChild(t2);

    // ---------------- Item Code Dropdown (Select2) ----------------
    var cell3 = row.insertCell(2);
    var ic = $("#item_code").clone();
    ic.attr("id", "item_code" + indexcone);
    ic.attr("name", "item_code[]");
    ic.val("");
    ic.appendTo(cell3);

    // ---------------- Part ID Dropdown (Select2) ----------------
    var cell4 = row.insertCell(3);
    var pt = $("#part_id").clone();
    pt.attr("id", "part_id" + indexcone);
    pt.attr("name", "part_id[]");
    pt.val("");
    pt.appendTo(cell4);

    // Hidden taga qty
    var t7 = document.createElement("input");
    t7.type = "hidden";
    t7.className = "TAGAQTY";
    t7.id = "taga_qty" + indexcone;
    t7.name = "taga_qty[]";
    t7.value = "1";
    t7.onkeyup = mycalc;
    cell4.appendChild(t7);

    // ---------------- Meter ----------------
    var cell5 = row.insertCell(4);
    var meter = document.createElement("input");
    meter.type = "number";
    meter.step = "any";
    meter.className = "METER";
    meter.id = "meter" + indexcone;
    meter.name = "meter[]";
    meter.style = "display: table-cell; width:80px;height:30px;";
    meter.onkeyup = mycalc;
    cell5.appendChild(meter);

    // ---------------- Gram per meter ----------------
    var cell6 = row.insertCell(5);
    var gpm = document.createElement("input");
    gpm.type = "number";
    gpm.step = "any";
    gpm.id = "gram_per_meter" + indexcone;
    gpm.name = "gram_per_meter[]";
    gpm.value = $('#gram_per_meter').val();
    gpm.style = "display: table-cell; width:80px;height:30px;";
    gpm.onkeyup = mycalc;
    cell6.appendChild(gpm);

    // ---------------- KG ----------------
    var cell7 = row.insertCell(6);
    var kg = document.createElement("input");
    kg.type = "number";
    kg.step = "any";
    kg.className = "KG";
    kg.id = "kg" + indexcone;
    kg.name = "kg[]";
    kg.readOnly = true;
    kg.value = $('#kg').val();
    kg.style = "display: table-cell; width:80px;height:30px;";
    cell7.appendChild(kg);

    // ---------------- Rate ----------------
    var cell8 = row.insertCell(7);
    var rate = document.createElement("input");
    rate.type = "number";
    rate.step = "any";
    rate.id = "item_rates" + indexcone;
    rate.name = "item_rates[]";
    rate.value = "0";
    rate.style = "display: table-cell; width:80px;height:30px;";
    cell8.appendChild(rate);

    // ---------------- Amount ----------------
    var cell9 = row.insertCell(8);
    var amt = document.createElement("input");
    amt.type = "number";
    amt.step = "any";
    amt.readOnly = true;
    amt.className = "AMT";
    amt.id = "amounts" + indexcone;
    amt.name = "amounts[]";
    amt.value = "0";
    amt.style = "display: table-cell; width:80px;height:30px;";
    cell9.appendChild(amt);

    // ---------------- Supplier Roll ----------------
    var cell10 = row.insertCell(9);
    var roll = document.createElement("input");
    roll.type = "number";
    roll.step = "any";
    roll.className = "suplier_roll_no";
    roll.id = "suplier_roll_no" + indexcone;
    roll.name = "suplier_roll_no[]";
    roll.required="true";
    roll.style = "display: table-cell; width:100px;height:30px;";
    cell10.appendChild(roll);

    // ---------------- Track Code ----------------
    var cell11 = row.insertCell(10);
    var tc = document.createElement("input");
    tc.type = "text";
    tc.readOnly = true;
    tc.id = "track_code" + indexcone;
    tc.name = "track_code[]";

    if ($("#cp_id").val() == 1) {
        ++PBarcode;
        tc.value = 'P' + PBarcode;
    } else {
        ++CBarcode;
        tc.value = 'I' + CBarcode;
    }
    tc.style = "display: table-cell; width:80px;height:30px;";
    cell11.appendChild(tc);

    // ---------------- ADD BUTTON ----------------
    var cell12 = row.insertCell(11);
    var add = document.createElement("input");
    add.type = "button";
    add.value = "+";
    add.className = "btn btn-warning pull-left";
    add.onclick = function () { insertcone1(); }
    cell12.appendChild(add);

    // ---------------- DELETE BUTTON ----------------
    var cell13 = row.insertCell(12);
    var del = document.createElement("input");
    del.type = "button";
    del.value = "X";
    del.setAttribute("onclick", "deleteRowcone(this)");
    del.className = "btn btn-danger pull-left";
    del.onclick = function () { deleteRowcone(this); }
    cell13.appendChild(del);

    // Scroll to new row
    var w = $(window);
    var tr = $('#footable_3').find('tr').eq(indexcone);
    if (tr.length) {
        $('html,body').animate({ scrollTop: tr.offset().top - (w.height() / 2) }, 1000);
    }

    $("#cntrr1").val(parseInt($("#cntrr1").val()) + 1);

    indexcone++;
    mycalc();
    recalcIdcone();

    // ---------------- Reapply Select2 ----------------
    $("#item_code" + (indexcone - 1)).select2();
    $("#part_id" + (indexcone - 1)).select2();

    selselect();
}

   
   function selselect()
   {
       setTimeout(
    function() 
    {
   
    $("#footable_2 tr td  select[name='item_code[]']").each(function() {
   
       $(this).closest("tr").find('select[name="item_code[]"]').select2();
     $(this).closest("tr").find('select[name="part_id[]"]').select2();
   
      });
   }, 2000);
   }
   
 

   $(document).on("input", 'input[name^="meter[]"]', function (event) 
   {
         var row = $(this).closest("tr");

         var current_item_code = row.find('input[name^="item_codes[]"]').val();
   
         var total = 0;

         if($("#isOutsideVendor").is(":checked"))
         {
            var po_qty = parseFloat($(".item_code_" + current_item_code).find('.bal_qty1').html()) || 0;
            var total = 0;

            // First calculate total meter already entered
            $("#footable_3 > tbody > tr").each(function () {

               var item_code = $(this).find('input[name="item_codes[]"]').val();
               if (current_item_code === item_code) {

                  var meterVal = parseFloat($(this).find('input[name="meter[]"]').val()) || 0;
                  total += meterVal;
               }
            });

            // Now check the latest row where user typed meter
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

                  var item_code = $(this).find('input[name="item_codes[]"]').val();

                  if (current_item_code === item_code) {

                        var meterInput = $(this).find('input[name="meter[]"]');

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
                  alert("Only " + remaining + " meter remaining!");

                  currentMeterInput.val(remaining);
               }
            }

         }
         else
         {
            var po_qty = $(".item_code_" + current_item_code).find('.bal_qty').html();
            $("#footable_2 > tbody > tr").each(function() {

               var item_code = $(this).find('input[name="item_codes[]"]').val();

               if (current_item_code === item_code) {
                  var meter = parseFloat($(this).find('input[name="meter[]"]').val()) || 0;
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
   
   $(document).on("input", 'input[name^="item_rates[]"]', function (event) 
   {
          CalculateRow($(this).closest("tr"));
   });
     
      function CalculateRow(row)
      {
         var item_qtys=+row.find('input[name^="meter[]"]').val();
         var item_rates=+row.find('input[name^="item_rates[]"]').val();
         var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed();
         row.find('input[name^="amounts[]"]').val(amount);
         mycalc();
      }
   
   
   function mycalc()
   {    
         document.getElementById("total_taga_qty").value =document.getElementById('cntrr').value;
         document.getElementById("total_taga_qty1").value =document.getElementById('cntrr1').value;
         
         sum1 = 0.0;
         var amounts = document.getElementsByClassName('METER');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
         var a = +amounts[i].value;
         sum1 += parseFloat(a);
         }
         document.getElementById("total_meter").value = sum1.toFixed(2);

         sum2 = 0.0;
         var amounts = document.getElementsByClassName('METER');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
         var a = +amounts[i].value;
         sum2 += parseFloat(a);
         }
         document.getElementById("total_meter1").value = sum2.toFixed(2);
         
         
         sum1 = 0.0;
         var amounts = document.getElementsByClassName('KG');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
         var a = +amounts[i].value;
         sum1 += parseFloat(a);
         }
         document.getElementById("total_kg").value = sum1.toFixed(2);
         
         sum4 = 0.0;
         var amounts = document.getElementsByClassName('KG');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
         var a = +amounts[i].value;
         sum4 += parseFloat(a);
         }
         document.getElementById("total_kg1").value = sum1.toFixed(2);
         
         
         
         sum1 = 0.0;
         var amounts = document.getElementsByClassName('AMT');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
         var a = +amounts[i].value;
         sum1 += parseFloat(a);
         }
         document.getElementById("total_amount").value = sum1.toFixed(2);  

         sum5 = 0.0;
         var amounts = document.getElementsByClassName('AMT');
         //alert("value="+amounts[0].value);
         for(var i=0; i<amounts .length; i++)
         { 
            var a = +amounts[i].value;
            sum5 += parseFloat(a);
         }
         document.getElementById("total_amount1").value = sum5.toFixed(2);  
   
   }
   
   $("table.footable_2").on('keyup', 'input[name^="gram_per_meter[]"]', function (event)   
   { 
           
      var row=$(this).closest("tr");
      var gram_per_meter=parseFloat(+row.find('input[name^="gram_per_meter"]').val());
      var meter=parseFloat(+row.find('input[name^="meter"]').val());
     var kg=parseFloat(meter*gram_per_meter).toFixed(2);
      row.find('input[name^="kg[]"]').val(kg);
      
      });
   
   
   function deleteRowcone(btn) 
   {
      var row = btn.parentNode.parentNode;
      row.parentNode.removeChild(row); 
      mycalc();
      recalcIdcone();   
   }
   
   
   
   function recalcIdcone()
   {
      $.each($("#footable_2 tr"),function (i,el){
      $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
      });
   }
   
   
   
   $(document).ready(function(){
   
   
   var po_code=document.getElementById('po_code').value;  
   
   
   if(po_code !="" && po_code!=0)
   {
   
   getDetails(po_code);
   
   }
   
   }); 
   
   function gettable(po_code)
   { 
      $.ajax({
         type:"GET",
         url:"{{ route('getPo') }}",
         //dataType:"json",
         data:{po_code:po_code},
         success:function(response){
            
            $("#item_code").html(response.html);
         
         }
      });    
   }
   
   
   function getDetails(po_code)
   {
       
         $.ajax({
            type:"GET",
            url:"{{ route('getPoMasterDetail') }}",
            //dataType:"json",
            data:{po_code:po_code},
            success:function(response)
            {
               
                  
                  $("#Ac_code").val(response[0].Ac_code);
                  $("#invoice_no").val(response[0].supplierRef);
                  $("#invoice_date").val(response[0].pur_date);
                  $("#po_type_id").val(response[0].po_type_id);
                  $("#in_narration").val(response[0].narration);
                  
                  gettable(po_code);

                  document.getElementById('Ac_code').disabled =true;
                  document.getElementById('po_type_id').disabled=true;
            }
            
         });
         $("#po_code").attr('disabled', true);
         $.ajax({
            type:"GET",
            url:"{{ route('GetPurchaseDetailItemCodeWise') }}", 
            data:{po_code:po_code},
            success:function(response){
               $("#PurchaseTbody").html(response.html);
            
            }
         });  
   } 
</script>
<!-- end row -->
@endsection