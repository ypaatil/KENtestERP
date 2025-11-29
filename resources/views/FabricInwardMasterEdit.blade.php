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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery" type="button" role="tab"
                   @if($FabricInwardMasterList->tab_button==2) disabled @endif>
                  Delivery
                  </button>
               </li>
               <li class="nav-item" role="presentation">
                  <button class="nav-link" id="return-tab" data-bs-toggle="tab" data-bs-target="#return" type="button" role="tab"
                   @if($FabricInwardMasterList->tab_button==1) disabled @endif>
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
               @if(isset($FabricInwardMasterList)) 
               <form action="{{ route('FabricInward.update',$FabricInwardMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
                  @method('put')
                  @csrf 
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="in_date" class="form-label">In Date</label>
                           <input type="date" name="in_date" class="form-control" id="in_date" value="{{ $FabricInwardMasterList->in_date }}" required>
                           @foreach($counter_number as  $row)
                           <input type="hidden" name="PBarcode" class="form-control" id="PBarcode" value="{{ $row->PBarcode }}">
                           <input type="hidden" name="CBarcode" class="form-control" id="CBarcode" value="{{ $row->CBarcode }}">
                           @endforeach 
                           <input type="hidden" name="in_code" class="form-control" id="in_code" value="{{ $FabricInwardMasterList->in_code }}">
                           <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricInwardMasterList->c_code }}">
                           <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FabricInwardMasterList->created_at }}">  
                           <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="1">
                           <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-invoice_no-input" class="form-label">Invoice No</label>
                           <input type="text" name="invoice_no" id="invoice_no" class="form-control" value="{{ $FabricInwardMasterList->invoice_no }}" id="formrow-invoice_no-input" required>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
                           <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $FabricInwardMasterList->invoice_date }}">
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">PO Code</label>   
                           <select name="po_code" class="form-select select2" id="po_code" onchange="getPODetails();DisabledPO(this);"  disabled >
                              <option value="">PO code</option>
                              @foreach($POList as  $rowpol)
                              {
                              <option value="{{ $rowpol->pur_code  }}"
                              {{ $rowpol->pur_code == $FabricInwardMasterList->po_code ? 'selected="selected"' : '' }} 
                              >{{ $rowpol->pur_code }}</option>
                              }
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">PO Type</label>
                           <select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();"  disabled>
                              <option value="">Type</option>
                              @foreach($POTypeList as  $rowpo)
                              {
                              <option value="{{ $rowpo->po_type_id  }}"
                              {{ $rowpo->po_type_id == $FabricInwardMasterList->po_type_id ? 'selected="selected"' : '' }}      
                              >{{ $rowpo->po_type_name }}</option>
                              }
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">Supplier</label>
                           <select name="Ac_code" class="form-select" id="Ac_code"  disabled>
                              <option value="">--Select Supplier--</option>
                              @foreach($Ledger as  $row)
                              {
                              <option value="{{ $row->ac_code }}"
                              {{ $row->ac_code == $FabricInwardMasterList->Ac_code ? 'selected="selected"' : '' }}   >  
                              {{ $row->ac_name }}</option>
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
                           <option value="{{ $row->sr_no }}" {{ $row->sr_no == $FabricInwardMasterList->bill_to ? 'selected="selected"' : '' }} >{{ $row->trade_name }}({{$row->site_code}})</option> 
                           @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2 hide">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">CP Type</label>
                           <select name="cp_id" class="form-select" id="cp_id" required onchange="serBarocode();" disabled>
                              <option value="">--Select CP Type--</option>
                              @foreach($CPList as  $rowCP)
                              {
                              <option value="{{ $rowCP->cp_id }}"
                              {{ $rowCP->cp_id == $FabricInwardMasterList->cp_id ? 'selected="selected"' : '' }}       
                              >{{ $rowCP->cp_name }}</option>
                              }
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-check form-check-primary mb-3">
                           <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening" style="font-size: 25px;margin-top: 30px;margin-left: 0px;" onclick="enable(this.value);"
                           @if($FabricInwardMasterList->is_opening==1)checked @else disabled @endif    >
                           <label class="form-check-label" for="is_opening" style="margin-top: 30px;position: absolute;margin-left: 20px;font-size: 16px;">
                           Opening Stock
                           </label>
                        </div>
                     </div>
                     <div class="col-md-3">
                        <label for="fge_code" class="form-label">Fabric Gate Code</label>
                        <select name="fge_code" class="form-select select2" id="fge_code" disabled>
                           <option value="">--Select--</option>
                           @foreach($FGECodeList as  $row) 
                           <option value="{{ $row->fge_code }}" {{ $row->fge_code == $FabricInwardMasterList->fge_code ? 'selected="selected"' : '' }} >{{ $row->fge_code }}</option> 
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <label for="formrow-inputState" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2" id="location_id" required>
                           <option value="">--Select Buyer--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $FabricInwardMasterList->location_id ? 'selected="selected"' : '' }}    
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-2 hide" id="vendorData">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Name</label>   
                           <input type="text" name="vendorName" class="form-control" id="vendorName"  value=""  readonly style="width: 250px;"/>
                        </div>
                     </div>
                  </div>
                  <input type="number" value="{{ count($FabricInwardDetails) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                           <thead>
                              <tr>
                                 <th>SrNo</th>
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
                              @php
                              $dis = '';
                              @endphp
                              @if(count($FabricInwardDetails)>0)
                              @php $no=1; @endphp
                              @foreach($FabricInwardDetails as $List) 
                              @php
                              $checkingData = DB::SELECT("SELECT count(*) as total_count FROM fabric_checking_details 
                              INNER JOIN fabric_checking_master ON fabric_checking_master.chk_code = fabric_checking_details.chk_code
                              WHERE fabric_checking_details.track_code='".$List->track_code."' AND fabric_checking_master.delflag=0");
                              $total_count = isset($checkingData[0]->total_count) ? $checkingData[0]->total_count : 0;
                              if($total_count > 0)
                              {
                              $dis = 'disabled';
                              }
                              else
                              {
                              $dis = '';
                              }
                              @endphp
                              <tr>
                                 <td><input type="text" name="id[]" value="{{ $no }}" id="id" style="width:50px;" {{$dis}} /></td>
                                 <td><input type="text" name="item_codes[]" value="{{ $List->item_code }}" id="item_codes" style="width:80px;" {{$dis}} /></td>
                                 <td>
                                    <select name="item_code[]"  id="item_code" style="width:200px; height:30px;" required onchange="getRateFromPO(this);" {{$dis}} > 
                                    <option value="{{ $List->item_code }}">{{ $List->item_name }}</option>
                                    </select>
                                 </td>
                                 <td>
                                    <select name="part_id[]"  id="part_id" style="width:200px; height:30px;" required  {{$dis}} >
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    <option value="{{ $row->part_id }}"
                                    {{ $row->part_id == $List->part_id ? 'selected="selected"' : '' }}       
                                    >{{ $row->part_name }}</option>
                                    @endforeach
                                    </select>
                                 </td>
                                 <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="{{ $List->taga_qty }}" id="taga_qty1" style="width:50px;"/><input type="number" step="0.01"class="METER" name="meter[]" onkeyup="mycalc();" value="{{ $List->meter }}" id="meter1" style="width:80px; height:30px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"  name="gram_per_meter[]" onkeyup="mycalc();" value="{{ $List->gram_per_meter }}" id="gram_per_meter" style="width:80px; height:30px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any" class="KG" name="kg[]" onkeyup="mycalc();" value="{{ $List->kg }}" id="kg" style="width:80px; height:30px;" readonly  {{$dis}}  /></td>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="{{ $List->item_rate }}" id="item_rates" style="width:80px;height:30px;" readonly    />
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="{{ $List->amount }}" id="amounts" style="width:80px;height:30px;" readonly/></td>
                                 <td><input type="number" step="any" class="suplier_roll_no"  name="suplier_roll_no[]"   value="{{ $List->suplier_roll_no }}" id="suplier_roll_no" style="width:100px;height:30px;"  {{$dis}} required /></td>
                                 <td><input type="text" name="track_code[]"  value="{{ $List->track_code }}" id="track_code" style="width:80px; height:30px;" required readOnly/></td>
                                 <td><input type="button" style="width:40px;" onclick="insertcone();" name="Abutton" value="+" class="btn btn-warning pull-left"></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                              </tr>
                              @php $no=$no+1; @endphp
                              @endforeach
                              @else
                              <tr>
                                 <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"  {{$dis}}  /></td>
                                 <td>
                                    <select name="item_code[]" class="item"  id="item_code" style="width:100px;" required onchange="getRateFromPO(this);"  {{$dis}} >
                                    <option value="">--Item--</option>
                                    @foreach($ItemList as  $row)
                                    {
                                    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                    }
                                    @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="part_id[]" class="part"   id="part_id" style="width:100px;" required  {{$dis}} >
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    {
                                    <option value="{{ $row->part_id }}">{{ $row->part_name }}</option>
                                    }
                                    @endforeach
                                    </select>
                                 </td>
                                 <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;"/><input type="number" step="0.01" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"  name="gram_per_meter[]"  value="0" id="gram_per_meter" style="width:80px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any" class="KG" name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;" readonly  {{$dis}}  /></td>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" readonly  {{$dis}}  />
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="0" id="amounts" style="width:80px;height:30px;" readonly  {{$dis}} />
                                 <td><input type="number" step="any" class="suplier_roll_no"  name="suplier_roll_no[]"   value="" id="suplier_roll_no" style="width:100px;height:30px;"  {{$dis}}  required /></td>
                                 <td><input type="text" name="track_code[]"  value="" id="track_code" style="width:80px;" {{$dis}}  /></td>
                                 <td><input type="button" style="width:40px;" onclick="insertcone();" name="Abutton" value="+" class="btn btn-warning pull-left"></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                              </tr>
                              @endif
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
                                 <th nowrap>Suplier Roll No.</th>
                                 <th>Track Code</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_meter" class="form-label">Total Meter</label>
                           <input type="number" readOnly step="0.01"  name="total_meter" class="form-control" id="total_meter" value="{{ $FabricInwardMasterList->total_meter }}" required>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_kg" class="form-label">Total KG</label>
                           <input type="number" readOnly step="0.01"  name="total_kg" class="form-control" id="total_kg" value="{{ $FabricInwardMasterList->total_kg }}">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_qty" class="form-label">Total No of Roll</label>
                           <input type="number" readOnly  name="total_taga_qty" class="form-control" id="total_taga_qty" value="{{ $FabricInwardMasterList->total_taga_qty }}" required>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">Total Amount</label>
                           <input type="text" readOnly name="total_amount" class="form-control" id="total_amount" value="{{ $FabricInwardMasterList->total_amount }}" required>
                        </div>
                     </div>
                     <div class="col-sm-8">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">Narration</label>
                           <input type="text" name="in_narration" class="form-control" id="in_narration"   value="{{ $FabricInwardMasterList->in_narration }}" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary w-md" onclick="UpdateBarcode();EnableFields();" id="Submit">Submit</button>
                           <a href="{{ Route('FabricInward.index') }}" class="btn btn-warning w-md">Cancel</a>
                        </div>
                     </div>
                  </div>
               </form>
               @endif
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
               @if(isset($FabricInwardMasterList)) 
               <form action="{{ route('FabricInward.update',$FabricInwardMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
                  @method('put')
                  @csrf 
                  <div class="row">  
                     <input type="hidden" name="in_date" class="form-control" id="in_date" value="{{ $FabricInwardMasterList->in_date }}" required>
                     @foreach($counter_number as  $row)
                     <input type="hidden" name="PBarcode" class="form-control" id="PBarcode" value="{{ $row->PBarcode }}">
                     <input type="hidden" name="CBarcode" class="form-control" id="CBarcode" value="{{ $row->CBarcode }}">
                     @endforeach 
                     <input type="hidden" name="in_code" class="form-control" id="in_code" value="{{ $FabricInwardMasterList->in_code }}">
                     <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricInwardMasterList->c_code }}">
                     <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FabricInwardMasterList->created_at }}">  
                     <input type="hidden" name="cp_id" class="form-control" id="cp_id" value="1">
                     <input type="hidden" name="tab_button" class="form-control" id="tab_button" value="1">
                     <input type="hidden" name="Ac_code" class="form-control" id="Ac_code1" value="{{ $FabricInwardMasterList->Ac_code }}">
                     <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input"> 
                     <div class="col-md-3">
                        <div class="mb-3">
                           <label for="formrow-invoice_date-input" class="form-label">DC Date</label>
                           <input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $FabricInwardMasterList->invoice_date }}">
                        </div>
                     </div> 
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="invoice_no1" class="form-label">DC No</label> 
                           
                           <input type="text" name="invoice_no" id="invoice_no1" class="form-control @if($FabricInwardMasterList->isReturnFabricInward == 1) hide @endif" value="{{ $FabricInwardMasterList->invoice_no }}" id="invoice_no" required>
                   
                           <select name="invoice_no" class="form-select select2 @if($FabricInwardMasterList->isReturnFabricInward != 1) hide @endif" id="focd_code" onchange="GetFabricCuttingDeptData();">
                              <option value="">--Select--</option>
                              @foreach($FabricCuttingOutwardList as  $row)
                              <option value="{{ $row->focd_code }}" {{ $row->focd_code == $FabricInwardMasterList->invoice_no ? 'selected="selected"' : '' }}>{{ $row->focd_code }}</option> 
                              @endforeach
                           </select> 
                        </div>
                     </div>
                     <div class="col-md-3">
                        <label for="fge_code" class="form-label">Fabric Gate Code</label>
                        <select name="fge_code" class="form-select select2" id="fge_code" disabled>
                           <option value="">--Select--</option>
                           @foreach($FGECodeList as  $row) 
                           <option value="{{ $row->fge_code }}" {{ $row->fge_code == $FabricInwardMasterList->fge_code ? 'selected="selected"' : '' }} >{{ $row->fge_code }}</option> 
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-4">
                        <label for="formrow-inputState" class="form-label">Location/Warehouse</label>
                        <select name="location_id" class="form-select select2" id="location_id" required>
                           <option value="">--Select Buyer--</option>
                           @foreach($LocationList as  $row)
                           {
                           <option value="{{ $row->loc_id }}"
                           {{ $row->loc_id == $FabricInwardMasterList->location_id ? 'selected="selected"' : '' }}    
                           >{{ $row->location }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isReturnFabricInward" onchange="GetOrderNo(this);GetDCDropdown();" name="isReturnFabricInward" style="font-size: 30px;margin-left: 0px;margin-top: -3px;" @if($FabricInwardMasterList->isReturnFabricInward==1)checked @else disabled @endif>

                              <label class="form-check-label" for="isReturnFabricInward" style="position: absolute;margin-left: 20px;font-size: 14px;">
                                    Is it retun fabric inward ? 
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3 mt-4 m-0">
                        <div class="mb-3">
                           <div class="form-check form-check-primary mb-5">
                              <input class="form-check-input" type="checkbox" id="isOutsideVendor" name="isOutsideVendor" onchange="DisableDropdown();" style="font-size: 30px;margin-left: 0px;margin-top: -3px;"  @if($FabricInwardMasterList->isOutsideVendor==1)checked @else disabled @endif >

                              <label class="form-check-label" for="isOutsideVendor" style="position: absolute;margin-left: 20px;font-size: 14px;">
                              Is it retun cutting inward ? 
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-3" id="workOrder"> 
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Process Order No.</label>   
                           <select name="vpo_code" class="form-select select2" id="vpo_code" onchange="GetVendorName(this.value);">
                              <option value="">--Select--</option>
                              @foreach($vendorProcessOrderList as  $vendors)
                              {
                              <option value="{{ $vendors->vpo_code  }}"
                              {{ $vendors->vpo_code == $FabricInwardMasterList->vpo_code ? 'selected="selected"' : '' }} 
                              >{{ $vendors->vpo_code }}</option>
                              }
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3" id="vendorData">
                        <div class="mb-3">
                           <label for="" class="form-label">Vendor Name</label>   
                           <select name="vendorId" class="form-select select2" id="vendorId" >
                              <option value="">--Select--</option>
                              @foreach($vendorData as  $rows)<option value="{{ $rows->ac_code }}"   {{ $rows->ac_code == $FabricInwardMasterList->vendorId ? 'selected="selected"' : '' }}  > {{ $rows->ac_short_name }}</option>
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
                  <input type="number" value="{{ count($FabricInwardDetails) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr>
                                 <th>Sr No</th>
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
                              @php
                              $dis = '';
                              @endphp
                              @if(count($FabricInwardDetails)>0)
                              @php $no=1; @endphp
                              @foreach($FabricInwardDetails as $List) 
                              @php
                              $checkingData = DB::SELECT("SELECT count(*) as total_count FROM fabric_checking_details 
                              INNER JOIN fabric_checking_master ON fabric_checking_master.chk_code = fabric_checking_details.chk_code
                              WHERE fabric_checking_details.track_code='".$List->track_code."' AND fabric_checking_master.delflag=0");
                              $total_count = isset($checkingData[0]->total_count) ? $checkingData[0]->total_count : 0;
                              if($total_count > 0)
                              {
                                 $dis = 'disabled';
                              }
                              else
                              {
                                 $dis = '';
                              }

                              if($FabricInwardMasterList->isReturnFabricInward == 1)
                              {
                                 $dis = '';
                              }

                              @endphp
                              <tr>
                                 <td><input type="text" name="id[]" value="{{ $no }}" id="id" style="width:50px;" {{$dis}} /></td>
                                 <td><input type="text" name="item_codes[]" value="{{ $List->item_code }}" id="item_codes" style="width:80px;" {{$dis}} /></td>
                                 <td>
                                    <select name="item_code[]"  id="item_code" style="width:200px; height:30px;" required onchange="getRateFromPO(this);" {{$dis}} > 
                                    <option value="{{ $List->item_code }}">{{ $List->item_name }}</option>
                                    </select>
                                 </td>
                                 <td>
                                    <select name="part_id[]"  id="part_id" style="width:200px; height:30px;" required  {{$dis}} >
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    <option value="{{ $row->part_id }}"
                                    {{ $row->part_id == $List->part_id ? 'selected="selected"' : '' }}       
                                    >{{ $row->part_name }}</option>
                                    @endforeach
                                    </select>
                                 </td>
                                 <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="{{ $List->taga_qty }}" id="taga_qty1" style="width:50px;"/><input type="number" step="0.01"class="METER" name="meter[]" onkeyup="mycalc();" value="{{ $List->meter }}" id="meter1" style="width:80px; height:30px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"  name="gram_per_meter[]" onkeyup="mycalc();" value="{{ $List->gram_per_meter }}" id="gram_per_meter" style="width:80px; height:30px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any" class="KG" name="kg[]" onkeyup="mycalc();" value="{{ $List->kg }}" id="kg" style="width:80px; height:30px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="{{ $List->item_rate }}" id="item_rates" style="width:80px;height:30px;" required    />
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="{{ $List->amount }}" id="amounts" style="width:80px;height:30px;" required/></td>
                                 <td><input type="text" step="any" class="suplier_roll_no"  name="suplier_roll_no[]"   value="{{ $List->suplier_roll_no }}" id="suplier_roll_no" style="width:100px;height:30px;"  {{$dis}} required /></td>
                                 <td><input type="text" name="track_code[]"  value="{{ $List->track_code }}" id="track_code1" style="width:80px; height:30px;" required readOnly/></td>
                                 <td><input type="button" style="width:40px;" onclick="insertcone1();" name="AButton" value="+" class="btn btn-warning pull-left" disabled></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X"  disabled></td>
                              </tr>
                              @php $no=$no+1; @endphp
                              @endforeach
                              @else
                              <tr>
                                 <td><input type="text" name="id[]" value="1" id="id" style="width:50px;"  {{$dis}}  /></td>
                                 <td>
                                    <select name="item_code[]" class="item"  id="item_code" style="width:100px;" required onchange="getRateFromPO(this);"  {{$dis}} >
                                    <option value="">--Item--</option>
                                    @foreach($ItemList as  $row)
                                    {
                                    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
                                    }
                                    @endforeach
                                    </select>
                                 </td>
                                 <td>
                                    <select name="part_id[]" class="part"   id="part_id" style="width:100px;" required  {{$dis}} >
                                    <option value="">--Part--</option>
                                    @foreach($PartList as  $row)
                                    {
                                    <option value="{{ $row->part_id }}">{{ $row->part_name }}</option>
                                    }
                                    @endforeach
                                    </select>
                                 </td>
                                 <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;"/><input type="number" step="0.01" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"  name="gram_per_meter[]"  value="0" id="gram_per_meter" style="width:80px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any" class="KG" name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;" required  {{$dis}}  /></td>
                                 <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" required  {{$dis}}  />
                                 <td><input type="number" step="any" class="AMT" readOnly  name="amounts[]"   value="0" id="amounts" style="width:80px;height:30px;" required  {{$dis}} />
                                 <td><input type="text" step="any" class="suplier_roll_no"  name="suplier_roll_no[]"   value="" id="suplier_roll_no" style="width:100px;height:30px;"  {{$dis}} required /></td>
                                 <td><input type="text" name="track_code[]"  value="" id="track_code" style="width:80px;" {{$dis}}  /></td>
                                 <td><input type="button" style="width:40px;" onclick="insertcone1();" name="Abutton" value="+" class="btn btn-warning pull-left"></td>
                                 <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                              </tr>
                              @endif
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
                                 <th nowrap>Suplier Roll No.</th>
                                 <th>Track Code</th>
                                 <th>Add</th>
                                 <th>Remove</th>
                              </tr>
                           </tfoot>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_meter" class="form-label">Total Meter</label>
                           <input type="number" readOnly step="0.01"  name="total_meter" class="form-control" id="total_meter1" value="{{ $FabricInwardMasterList->total_meter }}" required>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_kg" class="form-label">Total KG</label>
                           <input type="number" readOnly step="0.01"  name="total_kg" class="form-control" id="total_kg1" value="{{ $FabricInwardMasterList->total_kg }}">
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="total_qty" class="form-label">Total No of Roll</label>
                           <input type="number" readOnly  name="total_taga_qty" class="form-control" id="total_taga_qty1" value="{{ $FabricInwardMasterList->total_taga_qty }}" required>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="mb-3">
                           <label for="formrow-email-input" class="form-label">Total Amount</label>
                           <input type="text" readOnly name="total_amount" class="form-control" id="total_amount1" value="{{ $FabricInwardMasterList->total_amount }}" required>
                        </div>
                     </div>
                     <div class="col-sm-8">
                        <div class="mb-3">
                           <label for="formrow-inputState" class="form-label">Narration</label>
                           <input type="text" name="in_narration" class="form-control" id="in_narration"   value="{{ $FabricInwardMasterList->in_narration }}" />
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary w-md" onclick="UpdateBarcode();EnableFields();" id="Submit">Submit</button>
                           <a href="{{ Route('FabricInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
   <!-- end col -->
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


   $(document).ready(function() 
   {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 

         @if($FabricInwardMasterList->tab_button==1) 
            $("#delivery-tab").trigger('click'); 
         @else  
             $("#return-tab").trigger('click');
         @endif
    });
    
       

   function DisableDropdown()
   {
         if($("#isOutsideVendor").is(":checked"))
         {
            $("#isReturnFabricInward").attr('disabled', true); 
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
    
    
   var vpo_code = $("#vpo_code").val();
   if(vpo_code != "")
   {
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
              console.log(data);
               $("#vendorName").val(data.ac_code); 
               $("#Ac_code1").val(data.ac_code);
               $('#vendorData').removeClass('hide');               
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
      //      $("#is_opening").attr('disabled', false).prop('checked', true).trigger("change");
      //      $("#po_code").val("").attr('disabled', true).trigger("change");
      //      $("#fge_code").val("").trigger("change").attr('disabled', true);
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
    
   function getRateFromPO(row)
   {
       var po_code=$('#po_code').val();
       var item_code = $(row).val();
       $(row).parent().parent('tr').find('td input[name="item_codes[]"]').val(item_code);  
       var row = $(row).closest('tr'); 
        
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('ItemRateFromPO') }}",
          data:{'po_code':po_code,item_code:item_code},
          success: function(data){
               +row.find('input[name^="item_rates[]"]').val(data[0]['item_rate'])
               
      }
      });
          
          
          
          
   }
   function enable(opening)
   {
      
     
      if(opening.checked==true)
      { alert();
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
   
   }
   
   
   // $(document).on("click", 'input[name^="print[]"]', function (event) {
      
   //        CalculateRowPrint($(this).closest("tr"));
          
   //    });
    	
   // function CalculateRowPrint(btn)
   // { 
   //       var row = $(btn).closest("tr");
   //       var width=+row.find('input[name^="width[]"]').val();
   //       var meter=+row.find('input[name^="meter[]"]').val();
   //       var kg=+row.find('input[name^="kg[]"]').val();
   //       var color_id=+row.find('select[name^="color_id[]"]').val();
   //       var part_id=+row.find('select[name^="part_id[]"]').val();
   //       var quality_code=+row.find('select[name^="quality_code[]"]').val();
   //       var track_code=row.find('input[name^="track_code[]"]').val();
   //       var style_no=$("#style_no").val();
   //       var job_code=$("#job_code").val();
          
   //      //  alert(track_code);
   //        $.ajax({
   //            type: "GET",
   //            dataType:"json",
   //            url: "{{ route('PrintBarcode') }}",
   //            data:{'width':width,'meter':meter,'color_id':color_id,'quality_code':quality_code,'kg':kg,  'part_id':part_id,'track_code':track_code,'style_no':style_no,'job_code':job_code},
   //            success: function(data){
                   
   //            if((data['result'])=='success')
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
   
   function EnableFields()
   {
       $("select").prop('disabled', false);
       $("input").prop('disabled', false);
   }
   
   
   
   function getJobCardDetails()
   {
     
      var job_card_no=$("#job_code").val();
       
      $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('JobCardDetail') }}",
              data:{'job_card_no':job_card_no},
              success: function(data){
              $("#cp_id").val(data[0]['cp_id']);
              $("#style_no").val(data[0]['style_no']);
              $("#Ac_code").val(data[0]['Ac_code']);
              $("#fg_id").val(data[0]['fg_id']);
                  
                  
              //  if(data[0]['cp_id']==1)
              // {
                       
              //         ++PBarcode;
              //         $("#track_code").val('P'.concat(PBarcode.toString()));
              //       alert($("#track_code").val());
              // }
              // else
              // {       var CBar='';
              //         CBar='I' + parseInt(++CBarcode);
              //         $("#track_code").val(CBar);
              // }   
                     
                  
          }
          });
   }
   
   
    function getPODetails()
   {
     
      var po_code=$("#po_code").val();
      
      $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('PODetail') }}",
              data:{'po_code':po_code},
              success: function(data){
                  
                  $("#po_type_id").val(data[0]['po_type_id']);
                  $("#Ac_code").val(data[0]['Ac_code']);
                 
          }
          });
          
          
         
   
   }
   
   function DisabledPO(el)
   {
      if($(el).is(":checked"))
      {
         $("#is_opening").attr("disabled", true);
         $("#po_code").attr("disabled", true);
         $("#po_type_id").val(2).attr("disabled", true);
         $("#Ac_code").val(50).trigger('change').attr("disabled", true);
         $("#isReturnFabricInward").prop('checked', false).attr("disabled", true);
         setTimeout(function() {
               $("#bill_to").val(1083).trigger('change');
         }, 1000);
   
         $("#fge_code").attr("disabled", true).removeAttr("required");

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
   }
   
   var PBarcode=$("#PBarcode").val();
   var CBarcode=$("#CBarcode").val();
   
   
   function UpdateBarcode()
   {
       $("#PBarcode").val(PBarcode);
        $("#CBarcode").val(CBarcode);
   }
   
   $("#return-tab").click(function () {
      serBarocode1();
   });

      function serBarocode1()
   { 
      if($("#cp_id").val()==1)
      { 
         ++PBarcode;
         $("#track_code1").val('P'.concat(PBarcode.toString()));
         //alert($("#track_code").val());
      }
      else if($("#cp_id").val()==2)
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
              {       var CBar='';
                      CBar='I' + parseInt(++CBarcode);
                       $("#track_code").val(CBar);
              }
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
    roll.required = true;
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
    add.onclick = function () { insertcone1();}
    cell12.appendChild(add);

    // ---------------- DELETE BUTTON ----------------
    var cell13 = row.insertCell(12);
    var del = document.createElement("input");
    del.type = "button";
    del.value = "X";
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


   $("table.footable_2,table.footable_3").on("keyup", 'input[name^="gram_per_meter[]"],input[name^="meter[]"]', function (event) {
          CalculateRow($(this).closest("tr"));
          
   });
    	
   function CalculateRow(row)
   { 
   	var gram_per_meter=+row.find('input[name^="gram_per_meter[]"]').val();
          var meter=+row.find('input[name^="meter[]"]').val();
    	var kg=parseFloat(meter * gram_per_meter).toFixed(2);
          row.find('input[name^="kg[]"]').val(kg);
   	mycalc();
   }
   
   
   function getDetails(po_code){
   
   $.ajax({
   type:"GET",
   url:"{{ route('getPoMasterDetail') }}",
   //dataType:"json",
   data:{po_code:po_code},
   success:function(response){
   console.log(response);
   
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
   } 
   
   
   var indexcone = {{ count($FabricInwardDetails) }};
   //var indexcone = 2;
   function insertcone()
   {      
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
      t1.style="display: table-cell; width:50px;";
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
      t8.onkeyup=mycalc();
      cell7.appendChild(t8);
      
      var cell7 = row.insertCell(6);
      var t8=document.createElement("input");
      t8.style="display: table-cell; width:80px;height:30px;";
      t8.type="number";
      t8.step="any";
      t8.readOnly="true";
      t8.className="KG";
      t8.id = "kg"+indexcone;
      t8.name="kg[]";
      t8.readOnly=true;
      t8.onkeyup=mycalc();
      cell7.appendChild(t8);
      
      
      var cell3 = row.insertCell(7);
      var t3=document.createElement("input");
      t3.style="display: table-cell; width:80px;height:30px;";
      t3.type="number";
      t3.step="any";
      t3.required="true";
      t3.id = "item_rates"+indexcone;
      t3.name="item_rates[]";
      t3.value="0";
      t3.readOnly=true;
      if($('#is_opening').prop('checked')) 
      {t3.readOnly=false;}else{t3.readOnly=true;}
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
      t4.required="true";
      t4.className="suplier_roll_no";
      t4.id = "suplier_roll_no"+indexcone;
      t4.name="suplier_roll_no[]";
      t4.value="";
      t4.required = true;
      cell4.appendChild(t4);
      
      var cell7 = row.insertCell(10);
      var t7=document.createElement("input");
      t7.style="display: table-cell; width:80px;height:30px;";
      t7.type="text";
      t7.id = "track_code"+indexcone;
      t7.name="track_code[]";
      if($("#cp_id").val()==1)
      {
      t7.value='P'+(++PBarcode);
      }
      else
      {
         t7.value='I'+(++CBarcode);
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
      btnRemove.style="margin-left:10px;";
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
      $("#footable_2 tbody tr").find("td input[name='item_rates[]']").prop('readonly', false);   // most reliable
   }
   
   
    $(document).on("keyup", 'input[name^="meter[]"],input[name^="item_rates[]"]', function (event) {
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
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('METER');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_meter").value = sum1.toFixed(2);
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('KG');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_kg").value = sum1.toFixed(2);
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('AMT');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("total_amount").value = sum1.toFixed(2); 
   }
    
   function deleteRowcone(btn) 
   {
       
        var track_code = $(btn).parent().parent('tr').find('td input[name="track_code[]"]').val();
        $.ajax({
          type:"GET",
          url:"{{ route('CheckFabricEntryInChecking') }}",
          //dataType:"json",
          data:{track_code:track_code},
          success:function(res)
          {
              if(res.total_count == 0)
              {
                    var row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                    
                    recalcIdcone();
                    mycalc();
                    
                    $.ajax({
                      type:"GET",
                      url:"{{ route('DeleteDataFromDump') }}",
                      //dataType:"json",
                      data:{track_code:track_code},
                      success:function(res)
                      {
                          console.log("deleted..!");
                      }
                    });
              }
              else
              {
                  alert("The roll cannot be deleted because fabric checking has been completed !!!!");
              }
           
           
          }
        });
   
    //   if(document.getElementById('cntrr').value > 1)
    //   {
    //       var row = btn.parentNode.parentNode;
    //       row.parentNode.removeChild(row);
           
    //       document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
           
    //       recalcIdcone();
    //       mycalc();
    //       if($("#cntrr").val()<=0)
    //       {		
    //             document.getElementById('Submit').disabled=true;
    //       } 
    //   }
   }
   
    $("table.footable_2").on('keyup', 'input[name^="gram_per_meter[]"]', function (event)   
   { 
           
      var row=$(this).closest("tr");
      var gram_per_meter=parseFloat(+row.find('input[name^="gram_per_meter"]').val());
      var meter=parseFloat(+row.find('input[name^="meter"]').val());
     var kg=parseFloat(meter*gram_per_meter).toFixed(2);
      row.find('input[name^="kg[]"]').val(kg);
      
      });
   
   function recalcIdcone(){
   $.each($("#footable_2 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
   
</script>
<!-- end row -->
@endsection