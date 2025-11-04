@extends('layouts.master') 
@section('content')
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
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Outward</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Trims Outward</li>
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
            <form action="{{route('TrimsOutward.update',$purchasefetch)}}" method="POST" id="frmData">
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
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Issue Date</label>
                        <input type="date" name="trimDate" id="trimDate" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->tout_date  }}" required>
                        <input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
                        <input type="hidden" name="trimOutCode" value="{{ $purchasefetch->trimOutCode  }}" class="form-control" id="formrow-email-input">
                        <input type="hidden" name="c_code" value="{{ $purchasefetch->c_code  }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  
                  
                   <div class="col-md-2">
                    <div class="mb-3">
                    <label for="out_type_id" class="form-label">Outward For</label>
                    <select name="out_type_id" class="form-control select2" id="out_type_id" required onchange="enableItemList(this.value);" >
                    <option value="">--Type--</option>
                    @foreach($OutTypeList as  $rowot)
                    {
                        <option value="{{ $rowot->out_type_id }}"
                        {{ $rowot->out_type_id == $purchasefetch->out_type_id ? 'selected="selected"' : '' }}
                        >{{ $rowot->out_type_name }}</option>
                    }
                    @endforeach
                    </select>
                    </div>
                    </div>
                  
                  
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Process Order Type</label>
                        <select name="trim_type" class="form-control" id="trim_type" required   >
                           <option value="">--Trims Type--</option>
                           <option value="0" @php if($purchasefetch->trim_type==0){echo "selected";} @endphp>All</option>
                           <option value="1" @php if($purchasefetch->trim_type==1){echo "selected";} @endphp>Sewing Trims</option>
                           <option value="2" @php if($purchasefetch->trim_type==2){echo "selected";} @endphp>Packing Trims</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" required  onchange="getvendorList(this.value);">
                           <option value="">--Select Vendor--</option>
                           @foreach($Ledger as  $rowvendor)
                           {
                           <option value="{{ $rowvendor->ac_code }}"
                           {{ $rowvendor->ac_code == $purchasefetch->vendorId ? 'selected="selected"' : '' }}
                           >{{ $rowvendor->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ship_to" class="form-label">Ship To</label>
                        <select name="ship_to" class="form-select select2" id="ship_to">
                           <option value="">--- Select ---</option>
                           @foreach($ledgerDetails as  $rows) 
                                <option value="{{ $rows->trade_name  }}" {{ $rows->trade_name == $purchasefetch->ship_to ? 'selected="selected"' : '' }}>{{ $rows->trade_name }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 {{ $purchasefetch->out_type_id != 7 ? '' : 'hide' }}">
                     <div class="mb-3">
                        <label for="vw_code" class="form-label">Work Order No.</label>
                        <select name="vw_code" class="form-select select2" id="vw_code" onchange="getvendorMasterList(this.value);getvendordata(this.value);">
                           <option value="">--Select--</option>
                           @foreach($vendorcodeList as  $rowvendorcode)
                           {
                           <option value="{{ $rowvendorcode->vw_code }}"
                           {{ $rowvendorcode->vw_code == $purchasefetch->vw_code ? 'selected="selected"' : '' }}
                           >{{ $rowvendorcode->vw_code }} ({{ $rowvendorcode->sales_order_no }})</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 {{ $purchasefetch->out_type_id != 7 ? '' : 'hide' }}">
                     <div class="mb-3">
                        <label for="vpo_code" class="form-label">Process Order No.</label>
                        <select name="vpo_code" class="form-select select2" id="vpo_code" onchange="getVendorProcessDetails(this.value);getProcessTrimData(this.value);">
                           <option value="">--Select--</option>
                           @foreach($vendorProcessList as  $rowPO)
                           {
                           <option value="{{ $rowPO->vpo_code }}"
                           {{ $rowPO->vpo_code == $purchasefetch->vpo_code ? 'selected="selected"' : '' }}
                           >{{ $rowPO->vpo_code }} ({{ $rowPO->sales_order_no }})</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2 {{ $purchasefetch->out_type_id == 7 ? '' : 'hide' }}" id="sample_indent_code1">
                     <div class="mb-3">
                        <label for="sample_indent_code" class="form-label">SIN No.</label>
                        <select name="sample_indent_code" class="form-select select2" id="sample_indent_code" onchange="GetSampleIndentMasterCustomerData();">
                           <option value="">-- Select --</option>
                           @foreach($SINCodeList as  $rowSINCode) 
                           <option value="{{ $rowSINCode->sample_indent_code }}"
                           {{ $rowSINCode->sample_indent_code == $purchasefetch->sample_indent_code ? 'selected="selected"' : '' }}
                           >{{ $rowSINCode->sample_indent_code }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value);">
                           <option value="">--Main Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $purchasefetch->mainstyle_id ? 'selected="selected"' : '' }}
                           >{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)">
                           <option value="">--Sub Style--</option>
                           @foreach($SubStyleList as  $rowsyb)
                           {
                           <option value="{{ $rowsyb->substyle_id }}"
                           {{ $rowsyb->substyle_id == $purchasefetch->substyle_id ? 'selected="selected"' : '' }}   
                           >{{ $rowsyb->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-control" id="fg_id" >
                           <option value="">--Select Style--</option>
                           @foreach($FGList as  $rowstyle)
                           {
                           <option value="{{ $rowstyle->fg_id }}"
                           {{ $rowstyle->fg_id == $purchasefetch->fg_id ? 'selected="selected"' : '' }}   
                           >{{ $rowstyle->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{ $purchasefetch->style_no  }}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $purchasefetch->style_description  }}" readOnly>
                     </div>
                  </div>
               </div>
               <div></div>
               @if($purchasefetch->out_type_id == 7)
               <div class="table-wrap">
                  <div class="col-md-12"><h4><strong>Sewing</strong></h4></div>
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
                              <th>PO NO</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Description</th> 
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis">
                           @php 
                                $no=1; 
                                $detailpurchase1 = DB::table('trimsOutwardDetail')->leftJoin('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
                                                    ->where('trimOutCode','=', $purchasefetch->trimOutCode)
                                                    ->where('trim_type','=', 1)->get(['trimsOutwardDetail.*','item_master.item_description','item_master.item_name',DB::raw("sum(item_qty) as total_qty")]);
                           @endphp
                           @foreach($detailpurchase1 as $row)
                           @php
                                 
                                 $purchaseOrder = DB::select("select item_rate from purchaseorder_detail 
                                            where item_code='".$rows->item_code."' and pur_code='".$rows->po_code."'");
                                
                                 $po_item_rate = isset($purchaseOrder[0]->item_rate) ? $purchaseOrder[0]->item_rate : 0; 
                                 
                                $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
                           @endphp
                           <tr>
                              <td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
                              <td>
                                 <select name="po_code[]" id="po_code" class="select2 po_code" style="width:250px;height:30px;" onchange="GetTrimsItemList(this);" disabled>
                                    <option value="">--- PO NO ---</option>
                                    @foreach($POList as  $rowpo)
                                    {
                                    <option value="{{ $rowpo->po_code  }}"
                                    {{ $rowpo->po_code == $row->po_code ? 'selected="selected"' : '' }}
                                    >{{ $rowpo->po_code }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td class="i_codes">
                                     {{ $row->item_code  }}
                              </td>
                              <td>
                                 <select name="item_codes[]" class="item" id="item_codes"  class="select2" style="width:250px;height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" disabled> 
                                    <option value="{{ $row->item_code  }}">{{ $row->item_name }}</option>
                                 </select>
                              </td>
                              <td>
                                     {{ $row->item_description  }}
                              </td>
                              <td>
                                 <select name="unit_id[]" class="unit_id" id="unit_ids" class="select2" style="width:100px;height:30px;">
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
                                <td>
                                    <input type="number" step="any" class="QTY" name="item_qtys[]" value="{{ $row->item_qty }}" id="item_qty" style="width:80px;" max="{{ $row->item_qty }}" required onchange="mycalc();qtyCheck(this);setAssocQty(this);checkNumber(this);"  />
                                    <input type="hidden"   name="item_rate[]"   value="{{ $po_item_rate }}" id="item_rate" style="width:80px;height:30px;" />
                                    <input type="hidden" name="trim_type_id[]" value="{{ $row->trim_type }}" id="trim_type_id" />
                              </td>
                              <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left" disabled>+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" disabled></td>
                           </tr>
                           @php $no=$no+1;  @endphp
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="table-wrap">
                  <div class="col-md-12"><h4><strong>Packing</strong></h4></div>
                  <div class="table-responsive">
                     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                        <thead>
                           <tr>
                              <th>SrNo</th>
                              <th>PO NO</th>
                              <th>Item Code</th>
                              <th>Item Name</th>
                              <th>Description</th> 
                              <th>Unit</th>
                              <th>Quantity</th>
                              <th>Add/Remove</th>
                           </tr>
                        </thead>
                        <tbody id="bomdis">
                           @php
                                $no=1; 
                                $detailpurchase2 = DB::table('trimsOutwardDetail')->leftJoin('item_master', 'item_master.item_code', '=', 'trimsOutwardDetail.item_code')
                                                    ->where('trimOutCode','=', $purchasefetch->trimOutCode)
                                                    ->where('trim_type','=', 2)->get(['trimsOutwardDetail.*','item_master.item_description','item_master.item_name',DB::raw("sum(item_qty) as total_qty")]);
                           @endphp
                           @foreach($detailpurchase2 as $row)
                           @php
        
                                 $purchaseOrder = DB::select("select item_rate from purchaseorder_detail 
                                            where item_code='".$rows->item_code."' and pur_code='".$rows->po_code."'");
                                
                                 $po_item_rate = isset($purchaseOrder[0]->item_rate) ? $purchaseOrder[0]->item_rate : 0; 
        
                                 $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
                           @endphp
                           <tr>
                              <td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
                              <td>
                                 <select name="po_code[]" id="po_code" class="select2 po_code" style="width:250px;height:30px;" onchange="GetTrimsItemList(this);" disabled>
                                    <option value="">--- PO NO ---</option>
                                    @foreach($POList as  $rowpo)
                                    {
                                    <option value="{{ $rowpo->po_code  }}"
                                    {{ $rowpo->po_code == $row->po_code ? 'selected="selected"' : '' }}
                                    >{{ $rowpo->po_code }}</option>
                                    }
                                    @endforeach
                                 </select>
                              </td>
                              <td class="i_codes">
                                     {{ $row->item_code  }}
                              </td>
                              <td>
                                 <select name="item_codes[]" class="item" id="item_codes"  class="select2" style="width:250px;height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" disabled> 
                                    <option value="{{ $row->item_code  }}">{{ $row->item_name }}</option>
                                 </select>
                              </td>
                              <td>
                                     {{ $row->item_description  }}
                              </td> 
                              <td>
                                 <select name="unit_id[]" class="unit_id" id="unit_ids" class="select2" style="width:100px;height:30px;">
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
                                <td>
                                    <input type="number" step="any" class="QTY" name="item_qtys[]" value="{{ $row->item_qty }}" max="{{ $row->item_qty }}" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);checkNumber(this);"  />
                                    <input type="hidden"   name="item_rate[]"   value="{{ $po_item_rate }}" id="item_rate" style="width:80px;height:30px;" />
                                    <input type="hidden" name="trim_type_id[]" value="{{ $row->trim_type }}" id="trim_type_id" />
                              </td>
                              <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left" disabled>+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" disabled></td>
                           </tr>
                           @php $no=$no+1;  @endphp
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
               @else
                   <div class="table-wrap">
                      <div class="table-responsive">
                         <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
                            <thead>
                               <tr>
                                  <th>SrNo</th>
                                  <th>PO NO</th>
                                  <th>Item Code</th>
                                  <th>Item Name</th>
                                  <th>Description</th>  
                                  <th>Unit</th>
                                  <th>Quantity</th>
                                  <th>Add/Remove</th>
                               </tr>
                            </thead>
                            <tbody id="bomdis">
                               @php  if($detailpurchase->isEmpty()) { @endphp
                               <tr>
                                  <td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
                                  <td>
                                     <select name="po_code[]"    id="po_code"  class="select2" style="width:250px;height:30px;" onchange="GetTrimsItemList(this);">
                                        <option value="">--- PO NO ---</option>
                                        @foreach($POList as  $rowpo)
                                        {
                                        <option value="{{ $rowpo->po_code  }}" 
                                        >{{ $rowpo->po_code }}</option>
                                        }
                                        @endforeach
                                     </select>
                                  </td>
                                  <td>
                                         {{ $rowitem->item_code  }}
                                  </td>
                                  <td>
                                     <select name="item_codes[]" class="item" id="item_codes" class="select2" onchange="GetItemDescription(this);" style="width:200px;height:30px;"  >
                                        <option value="">--- Select Item ---</option>
                                        @foreach($itemlist as  $rowitem)
                                        {
                                        <option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>
                                        }
                                        @endforeach
                                     </select>
                                  </td>
                                  <td>
                                         {{ $rowitem->item_description  }}
                                  </td> 
                                  <td>
                                     <select name="unit_id[]" class="unit_id" id="unit_ids"   class="select2" style="width:100px;height:30px;">
                                        <option value="">--- Select Unit ---</option>
                                        @foreach($unitlist as  $rowunit)
                                        {
                                        <option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>
                                        }
                                        @endforeach
                                     </select>
                                  </td>
                                    <td>
                                        <input type="number" step="any" class="QTY" name="item_qtys[]" value="0" id="item_qty" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);checkNumber(this);"  />
                                        <input type="hidden"   name="item_rate[]"   value="0" id="item_rate" style="width:80px;height:30px;" />
                                        <input type="hidden" name="trim_type_id[]" value="1" id="trim_type_id" />
                                  </td>
                                  <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                               </tr>
                               @php } else { @endphp
                               @php $no=1; @endphp
                               @foreach($detailpurchase as $row)
                               @php
                                  
                                 $purchaseOrder = DB::select("select item_rate from purchaseorder_detail 
                                            where item_code='".$row->item_code."' and pur_code='".$row->po_code."'");
                                
                                 $po_item_rate = isset($purchaseOrder[0]->item_rate) ? $purchaseOrder[0]->item_rate : 0;                              
 
                                $POList=DB::table('trimsInwardMaster')->select('po_code')->distinct()->orderby('is_opening','asc')->get();
                               @endphp
                               
                               <tr>
                                  <td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
                                  <td>
                                     <select name="po_code[]" id="po_code" class="select2 po_code" style="width:250px;height:30px;" onchange="GetTrimsItemList(this);" disabled>
                                        <option value="">--- PO NO ---</option>
                                        @foreach($POList as  $rowpo)
                                        {
                                        <option value="{{ $rowpo->po_code  }}"
                                        {{ $rowpo->po_code == $row->po_code ? 'selected="selected"' : '' }}
                                        >{{ $rowpo->po_code }}</option>
                                        }
                                        @endforeach
                                     </select>
                                  </td>
                                  <td class="i_codes">
                                         {{ $row->item_code  }}
                                  </td>
                                  <td>
                                     <select name="item_codes[]" class="item" id="item_codes"  class="select2" style="width:250px;height:30px;" onchange="GetItemDescription(this);checkDuplicates(this);" disabled> 
                                        <option value="{{ $row->item_code  }}">{{ $row->item_name }}</option>
                                     </select>
                                  </td>
                                  <td>
                                         {{ $row->item_description  }}
                                  </td>
                                  <td>
                                     <select name="unit_id[]" class="unit_id" id="unit_ids" class="select2" style="width:100px;height:30px;">
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
                                    <td>
                                        <input type="number" step="any" class="QTY" name="item_qtys[]" value="{{ $row->item_qty }}" id="item_qty" max="{{ $row->item_qty }}" style="width:80px;" required onchange="mycalc();qtyCheck(this);setAssocQty(this);checkNumber(this);"  />
                                        <input type="hidden"   name="item_rate[]"   value="{{ $po_item_rate }}" id="item_rate" style="width:80px;height:30px;" />
                                        <input type="hidden" name="trim_type_id[]" value="{{ $row->trim_type }}" id="trim_type_id" />
                                  </td>
                                  <td><button type="button" onclick="insertRow(this);mycalc();" class="btn btn-warning pull-left" disabled>+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" disabled></td>
                               </tr>
                               @php $no=$no+1;  @endphp
                               @endforeach
                               @php } @endphp
                            </tbody>
                         </table>
                      </div>
                   </div>
               @endif
               <br/>
               <input type="hidden"   name="cnt" id="cnt" value="{{ count($detailpurchase) }}">  
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Total Quantity</label>
                        <input type="text" name="totalqty" class="form-control" id="totalqty" value="{{ $purchasefetch->total_qty }}" required>
                     </div>
                  </div>
               </div>
               </br>  
               <button type="submit" class="btn btn-success w-md" onclick="EnableFields();" id="Submit">Save</button>
               <a href="{{ Route('TrimsOutward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
    <h1 style="color: #db8e02;">Stock Deatils</h1>
    <div class="col-md-12">
         <table id="stockPopupTable" class="table  table-bordered table-striped m-b-0 footable_2 stripe row-border order-column" cellspacing="0" width="100%" style="color: antiquewhite!important;">
            <thead>
               <tr>
                  <th nowrap style="color: antiquewhite">Supplier Name</th>
                  <th nowrap style="color: antiquewhite">PO No</th>
                  <th nowrap style="color: antiquewhite">Stock Qty.</th>
                  <th nowrap style="color: antiquewhite">Rack No.</th>
               </tr>
            </thead>
            <tbody id="stockPopupBody">
               <tr>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
               </tr>
            </tbody>
            <tfoot id="stockPopupFoot">
            </tfoot>
         </table>
      </div>
  </div>
</div>  
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

    function closeForm() 
    {
      $('.form-popup-bg').removeClass('is-visible');
    }
    
    function checkDuplicateClass(p_code, item_code) 
    {
        var found = false;
        var classToFind = p_code + '_' + item_code;
    
        $('table tr').each(function() {
            if ($(this).find('.' + classToFind).length > 0) {
                found = true;
                return false; 
            }
        });
    
        if (found) {
            alert("Already exists!");
        }
    }
    
    function stockPopup(row,item_code)
    {
        var po_code = $(row).parent().parent().find('td select[name="po_code[]"]').val(); 
        var item_code = $(row).parent().parent().find('td:nth-child(3)').text(); 
        var vendorId = $("#vendorId").val();
        
        $.ajax(
        {
           type:"GET",
           dataType:'json',
           url: "{{ route('GetStockDetailPopupForTrims') }}",
           data:{'item_code':item_code, 'po_code':po_code,'vendorId':vendorId},
           success:function(response)
           {
                $("#stockPopupBody").html(response.html);
                $("#stockPopupFoot").html(response.html1);
                
                $('.form-popup-bg').addClass('is-visible');
           }
        });
   
    }
    
    $(document).ready(function($) 
    {
        $('.form-popup-bg').on('click', function(event) 
        {
            if ($(event.target).is('.form-popup-bg') || $(event.target).is('#btnCloseForm')) 
            {
              event.preventDefault();
              $(this).removeClass('is-visible');
            }
        });
    });

    function checkNumber(row) 
    {
        // const max = $(row).attr('max');  // Set the maximum value here
        // const inputNumber =  $(row).val();

        // if (inputNumber > max) {
        //     alert(`The number exceeds the maximum value of ${max}`);
        //     $(row).val(max);
        // } 
    }
        
    function checkDuplicates(row) 
    {
        
        var tr_class = $(row).closest('tr').attr('class');
        var po_code = $(row).parent().parent('tr').find('td select[name="po_code[]"]').val();
        var item_code =  $(row).parent().parent('tr').find('td select[name="item_codes[]"]').val();
        var ic1 = po_code+'_'+item_code; 
        var classArr = [];
        $("#footable_2 tbody").find('tr').each(function() 
        {
            var classes = ($(this).attr('class') || '').split(' ');
            var lastClass = classes[classes.length - 1];  

            if (lastClass && lastClass !== 'undefined') 
            {
                classArr.push(lastClass);
            }
        });

        var currentClass =  po_code+'_'+item_code; 
        if (classArr.indexOf(currentClass) !== -1) 
        {
           alert('Already exists Item Name and PO NO.');
           setTimeout(function() 
           {
                // $(row).closest('tr').filter(function() {
                //         return $(this).find('td select[name="po_code[]"]').length === 0;
                // }).find('select').val('');  
    
                $(row).parent().parent('tr').find('select[name="item_codes[]"]').val('');
                $(row).parent().parent('tr').find('.i_codes').text("");
                $(row).closest('tr').removeClass().addClass('tr_clone '+po_code+'_');
            }, 500);
        }
        else
        {
            $(row).closest('tr').removeClass().addClass('tr_clone '+ic1);
        }
    }
    
       
    function setAssocQty(row) 
    { 
        var assoc_qty1 = 0; 
        var item_code = $(row).closest('tr').find('td select[name="item_codes[]"]').val();
        var lastMatchingRow = null; 
        
        $('table > tbody > tr').each(function() {
            var item_code1 = $(this).find('td select[name="item_codes[]"]').val(); 
            
            var o_qty = parseFloat($(this).find('td .order_qty').val()) || 0;
            var item_qty = parseFloat($(this).find('td input[name="item_qtys[]"]').val()) || 0; 
            
            if (item_code == item_code1) 
            { 
                
                if(item_qty > 0) {
                    assoc_qty1 += (o_qty - item_qty);
                }
                
                lastMatchingRow = $(this);    
            } 
            
        }); 
        
        if($(row).closest('tr')[0] != lastMatchingRow[0])
        {
            $(lastMatchingRow).find('td .order_qty').val(assoc_qty1);
            $(lastMatchingRow).find('td input[name="item_qtys[]"]').attr('max', assoc_qty1);
        }
        
    }
    
  $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        
        $('input').not("#trimDate").not('input[name="item_qtys[]"]').prop('readonly', true);
        $('select').not('select[name="po_code[]"]').prop('disabled', true);
    });
function enableItemList(type)
{
        if(type==4)
       {$("#item_code").prop('disabled', false); }
       
    
    
}


   function EnableFields()
   {         $("select").prop('disabled', false); }
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
   
   
   var index = 1;
   function insertRow1(){
       
   $("#item_codes").select2("destroy");
   $("#unit_ids").select2("destroy");
   
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
   y.setAttribute("onclick", "checkDuplicates(this);");
   y.width(200);
   y.height(30);
   y.appendTo(cell5);
    
   
   var cell3 = row.insertCell(2);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   //t3.className="QTY";
   t3.id = "hsn_code"+index;
   t3.name="hsn_code[]";
   t3.value="0";
   cell3.appendChild(t3);
   
   
   var cell2 = row.insertCell(3);
   var t2=document.createElement("select");
   var x = $("#unit_ids"),
   y = x.clone();
   y.attr("id","unit_ids");
   y.attr("name","unit_ids[]");
   y.width(100);
   y.height(30);
   y.appendTo(cell2);
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("input");
   t3.style="display: table-cell; width:80px;height:30px;";
   t3.type="number";
   //t3.className="QTY";
   t3.id = "item_qtys"+index;
   t3.name="item_qtys[]";
   t3.value="0";
   cell3.appendChild(t3);
   
    
   var cell15=row.insertCell(5);
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertRow(); mycalc();");
   cell15.appendChild(btnAdd);
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRow(this)");
   cell15.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_2').find('tr').eq( index );
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
   
   index++;
   recalcId();
   
    selselect();
   
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
   
   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('QTY');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("totalqty").value = sum1.toFixed(2);
   
     
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
   item_code = document.getElementById("item_code").value;  
   
   calculate_gst(item_code);
   
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
   
   
   
   
//   $('#footable_2').on('change', '.item', function() 
//     {
    
//       var tax_type_ids=document.getElementById('tax_type_id').value;
//       var item_code = $(this).val();
//       var row = $(this).closest('tr'); // get the row
       
//       $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('GSTPER') }}",
//           data:{item_code:item_code,tax_type_id:tax_type_ids},
//           success: function(data){
   
//                 console.log(data); 
               
//               if(tax_type_ids==1)
//               {
//                   row.find('input[name^="pur_cgsts[]"]').val(data[0]['cgst_per']);
//                   row.find('input[name^="pur_sgsts[]"]').val(data[0]['sgst_per']);
//                   row.find('input[name^="pur_igsts[]"]').val(0);
//                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
//                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
//                     row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
                  
//               }
//               else if(tax_type_ids==2)
//               {
//                   row.find('input[name^="pur_igsts[]"]').val(data[0]['igst_per']);
//                   row.find('input[name^="pur_cgsts[]"]').val(0);
//                   row.find('input[name^="pur_sgsts[]"]').val(0);
//                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
//                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
//                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
//               }
//               else if(tax_type_ids==3)
//               {
//                   row.find('input[name^="pur_igsts[]"]').val(0);
//                   row.find('input[name^="pur_cgsts[]"]').val(0);
//                   row.find('input[name^="pur_sgsts[]"]').val(0);
//                   row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
//                   row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
//                   row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                   
//               }
         
//           }
//           });
   
//   });
   
   
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
   }
   
   
    function GetTrimsItemList(row)
    { 
            let duplicates = {};  

            // Iterate through each table row
            $('table tbody tr').each(function() {
                // Get selected values for po_code[] and item_codes[]
                let poCode = $(this).find('select[name="po_code[]"]').val();
                let itemCode = $(this).find('select[name="item_codes[]"]').val();
                
                // Create a unique key for the poCode and itemCode combination
                let key = poCode + '_' + itemCode;
                
                // If the key already exists, increment its count
                if (duplicates[key]) {
                    duplicates[key].count++;
                    duplicates[key].rows.push($(this)); // Track rows with the same key
                } else {
                    duplicates[key] = { count: 1, rows: [$(this)] }; // Initialize key with count and row reference
                }
            });
            
            // Check for duplicate rows
            let hasDuplicates = false;
            for (let key in duplicates) {
                if (duplicates[key].count > 1) {
                    hasDuplicates = true;
            
                    // Clear the duplicated rows except the first occurrence
                    duplicates[key].rows.forEach((row, index) => {
                        if (index > 0) {
                            row.find('select[name="po_code[]"]').select2('destroy'); // Destroy select2 for reset
                            row.find('select[name="po_code[]"]').val(''); // Clear the value
                            row.find('select[name="item_codes[]"]').select2('destroy');
                            row.find('select[name="item_codes[]"]').val(''); // Clear the value
                        }
                    });
                }
            }
            
            // If duplicates are found, show an alert
            if (hasDuplicates) {
                alert('PO Code and Item Code cannot be the same in more than one row.');
            }
            
            $(row).select2();
            $(row).closest('tr').find('input[name="item_qtys[]"]').val("");
            var p_code = $(row).val(); 
            var vw_code = $("#vw_code").val();
            var po_code = btoa($(row).val());
            var row = $(row).closest('tr'); 
            var item_code=row.find('select[name^="item_codes[]"]').val();
            var out_type=$('#out_type_id').val();
            if(item_code != '')
            {
                 $(row).closest('tr').removeClass().addClass('tr_clone '+p_code+'_'+item_code);
            }
            else
            {
                 $(row).closest('tr').removeClass().addClass('tr_clone '+p_code+"_");
            }
           
            // Call the function
            //checkDuplicateClass(p_code, item_code);
            
            $.ajax({
                  type:"GET",
                  url:"{{ route('getTrimsItemRate') }}",
                  dataType:"json",
                  data:{'item_code':item_code, 'po_code':po_code, 'vw_code':vw_code},
                  success:function(data2)
                  {
                      var data = data2.dataArr;
                      var data1 = data2.already_allocated_qty;
                     
                      
                      console.log(data2);
                      
                      if(!$.trim(data))
                      {
                          
                           if(out_type!=4)
                           { 
                               alert('This item is not found in selected PO!');
                               row.find('select[name^="po_code[]"]').val('');
                           }
                      }
                    //   else if(data[0][0].item_rate)
                    //   {
                    //       row.find('input[name^="item_rate[]"]').val(data[0][0].item_rate);
                    //   }
                  
                    //   $(row).closest('tr').find('.actual_stock_qty').val(data[1]);
                     // $(row).closest('tr').find('td:eq(7) input').val(data1);
                 }
              });
              
     
              
        
       if(out_type==4)
       {
              $.ajax({
              type:"GET",
              url:"{{ route('getItemListFromPO') }}",
              //dataType:"json",
              data:{po_code:po_code},
              success:function(response){
                  
                  row.find('select[name^="item_codes[]"]').html(response.html);
              
              }
              });
      }
              
       row.find('select[name^="item_codes[]"]').attr('disabled', true);    
    }  
   
   
   
   function getBomDetail(type){
       
   
   var bom_code=document.getElementById("bom_code").value;
   var tax_type_id=document.getElementById("tax_type_id").value;
   
   
   
   $.ajax({
   type:"GET",
   url:"{{ route('getBoMDetail') }}",
   //dataType:"json",
   data:{type:type,bom_code:bom_code,tax_type_id:tax_type_id},
   success:function(response){
   console.log(response);  
       $("#bomdis").append(response.html);
    mycalc();
   }
   });
   }
   
   
   
   function getvendorMasterList(vw_code){
   
     //alert(vw_code);
   
   $.ajax({
   type:"GET",
   url:"{{ route('getVendorMasterDetail') }}",
   dataType:"json",
   data:{vw_code:vw_code},
   success:function(response){
   console.log(response);  
   
   $("#mainstyle_id").val(response.mainstyle_id);
   $("#substyle_id").val(response.substyle_id);
   $("#fg_id").val(response.fg_id);
   $("#style_no").val(response.style_no);
   $("#style_description").val(response.style_description);
   
   
   document.getElementById('mainstyle_id').disabled=true;
   document.getElementById('substyle_id').disabled=true;
   document.getElementById('fg_id').disabled=true;
    document.getElementById('vpo_code').disabled=true;
   
   }
   });
   
   }
   
   
   
   function getvendordata(vw_code){
   
     //alert(pur_code);
   
   $.ajax({
   type:"GET",
   url:"{{ route('getvendortablenew') }}",
   //dataType:"json",
   data:{vw_code:vw_code},
   success:function(response){
   console.log(response);  
       $("#footable_2").html(response.html);
   
   }
   });
   
   }
   
   function EnableFields()
   {         $("select").prop('disabled', false); }
   
   function getVendorProcessDetails(vpo_code){
   
     alert(vpo_code);
   
   $.ajax({
   type:"GET",
   url:"{{ route('VendorProcessOrderDetails') }}",
   dataType:"json",
   data:{vpo_code:vpo_code},
   success:function(response){
   console.log(response);  
   
   $("#mainstyle_id").val(response[0]['mainstyle_id']);
   $("#substyle_id").val(response[0]['substyle_id']);
   $("#fg_id").val(response[0]['fg_id']);
   $("#style_no").val(response[0]['style_no']);
   $("#style_description").val(response[0]['style_description']);
   
   document.getElementById('mainstyle_id').disabled=true;
   document.getElementById('substyle_id').disabled=true;
   document.getElementById('fg_id').disabled=true;
    document.getElementById('vw_code').disabled=true;
   
   }
   });
   
   }
   
   
   
   function getProcessTrimData(vpo_code){
   
     //alert(pur_code);
   
   $.ajax({
   type:"GET",
   url:"{{ route('getProcessTrimData') }}",
   //dataType:"json",
   data:{vpo_code:vpo_code},
   success:function(response){
   console.log(response);  
       $("#footable_2").html(response.html);
   
   }
   });
   
   }
   
     function insertRow(ele)
      {
           var row = $("#footable_2 tr:last");
       
           row.find(".select2").each(function(index)
           {
               $(this).select2('destroy');
           }); 
           var newrow = row.clone();       
           var tdData = $(newrow).find('td:nth-child(1) input')[0];
           var prev_value = $(tdData).val();
           $(tdData).val(parseInt(prev_value) + parseInt(1));
           
           var item_code = $(newrow).find('td:nth-child(4) select')[0];
           var unit_id = $(newrow).find('td:nth-child(7) select')[0];
           var Order_Qty = $(newrow).find('td:nth-child(8) input')[0];
           var Stock_Qty = $(newrow).find('td:nth-child(9) input')[0];
           $(item_code).removeAttr('disabled').val('');
           $(unit_id).removeAttr('disabled').val('');
           $(Order_Qty).val('');
           $(Stock_Qty).val('');
           $(Order_Qty).removeAttr('readonly');
           $(Stock_Qty).removeAttr('readonly');
           $("#footable_2").append(newrow);
       
           $(newrow).find('input').val(''); 
           $(newrow).find('select').val('');
           $(newrow).closest('tr').find('.i_codes').text('');  
           $("select.select2").select2();
      }
   
    function getAssociatedStock(row)
    {
        var item_code = $(row).parent().parent('tr').find('td select[name="item_codes[]"]').val();
        var vw_code = $('#vw_code').val();
        var po_code = $(row).val();
        $.ajax({
          type:"GET",
          url:"{{ route('get_associated_stock') }}",
          //dataType:"json",
          data:{'po_code':po_code,'item_code':item_code, 'vw_code':vw_code},
          success:function(response)
          {
            $(row).closest('tr').find('td .assoc_qty').val(response);
          }
        });
          
    }
    
 
    function getAssociatedStockPacking(row)
    {
        
        let duplicates = {};  

        // Iterate through each table row
        $('table tbody tr').each(function() {
            // Get selected values for po_code[] and item_codes[]
            let poCode = $(this).find('select[name="po_code[]"]').val();
            let itemCode = $(this).find('select[name="item_codes[]"]').val();
            
            // Create a unique key for the poCode and itemCode combination
            let key = poCode + '_' + itemCode;
            
            // If the key already exists, increment its count
            if (duplicates[key]) {
                duplicates[key].count++;
                duplicates[key].rows.push($(this)); // Track rows with the same key
            } else {
                duplicates[key] = { count: 1, rows: [$(this)] }; // Initialize key with count and row reference
            }
        });
        
        // Check for duplicate rows
        let hasDuplicates = false;
        for (let key in duplicates) {
            if (duplicates[key].count > 1) {
                hasDuplicates = true;
        
                // Clear the duplicated rows except the first occurrence
                duplicates[key].rows.forEach((row, index) => {
                    if (index > 0) {
                        row.find('select[name="po_code[]"]').select2('destroy'); // Destroy select2 for reset
                        row.find('select[name="po_code[]"]').val(''); // Clear the value
                        row.find('select[name="item_codes[]"]').select2('destroy');
                        row.find('select[name="item_codes[]"]').val(''); // Clear the value
                    }
                });
            }
        }
        
        // If duplicates are found, show an alert
        if (hasDuplicates) {
            alert('PO Code and Item Code cannot be the same in more than one row.');
        }
        
            
        var item_code = $(row).parent().parent('tr').find('td select[name="item_codes[]"]').val();
        var vpo_code = $('#vpo_code').val();
        var po_code = $(row).val();
        $.ajax({
          type:"GET",
          url:"{{ route('get_associated_stock_packing') }}",
          //dataType:"json",
          data:{'po_code':po_code,'item_code':item_code, 'vpo_code':vpo_code},
          success:function(response)
          {
            $(row).closest('tr').find('td .assoc_qty').val(response);
          }
        });
          
       $(row).parent().parent('tr').find('td select[name^="item_codes[]"]').attr('disabled', true); 
    }
    
    function GetItemDescription(obj)
    {
           var item_code = $(obj).val();
           row = $(obj).closest('tr'); 
           $.ajax({
              type:"GET",
              url:"{{ route('getItemDescription') }}",
              data:{item_code:item_code},
              success:function(response)
              {
                  $(obj).parent().parent('tr').find('td:nth-child(3)').text(response.item_code);
                  $(obj).parent().parent('tr').find('td:nth-child(5)').text(response.item_description);
                  $(obj).parent().parent('tr').find('td select option[value="'+response.unit_id+'"]').prop('selected', true);
                  row.find('input[name^="stock[]"]').val(response.stock);
                        
              }
           });
            
            $.ajax({
                  type:"GET",
                  url:"{{ route('GetPOListFromItemCode') }}",
                  data:{item_code:item_code},
                  success:function(response1)
                  { 
                      $(obj).parent().parent('tr').find('td select[name="po_code[]"]').html(response1.html);      
                  }
            });
            
            var out_type=$('#out_type_id').val();
            
            // if(out_type==4)
            // {    
              var po_code =  btoa(row.find('select[name^="po_code[]"]').val());
              
              $.ajax({
                  type:"GET",
                  url:"{{ route('getTrimsItemRate') }}",
                  dataType:"json",
                  data:{'item_code':item_code, 'po_code':po_code},
                  success:function(data)
                  {
                      if(!$.trim(data))
                      {
                          alert('This item is not found in selected PO!');
                         //row.find('select[name^="po_code[]"]').val('');
                      }
                      else if(data[0]['item_rate'])
                      {
                          row.find('input[name^="item_rate[]"]').val(data[0]['item_rate']);
                      }
                  }
              });
           
            // }
            
            var current_item_code = item_code;  
            var total_Qty = 0;
            
            $('table tr').each(function(i) {
                var tbl_row = $('table tr')[i];
                var row_item_code = $(tbl_row).find("td:eq(2)").html();
                if (row_item_code == current_item_code) 
                {
                    var order_qty = parseFloat($(tbl_row).find("td:eq(8) input").val()) || 0;
                    var qty = parseFloat($(tbl_row).find("td:eq(9) input").val()) || 0;
                    total_Qty += (order_qty - qty);
                }
            });
            
            $(row).find("td:eq(8) input").val(total_Qty);  
            
        //     var assoc_qty = 0;
        //     var item_code = $(obj).val();
        //     $('table > tbody > tr').each(function()
        //     {
        //         var item_code1 = $(this).find('td select[name="item_codes[]"]').val();
        //         if(item_code == item_code1)
        //         {
        //             var o_qty = $(this).find('td .order_qty').val();
        //             var item_qty = $(this).find('td input[name="item_qtys[]"]').val();
        //             assoc_qty += parseFloat(o_qty - item_qty);
        //         }
        //     }); 
            
        //   $(obj).parent().parent('tr').find('td .order_qty').val(assoc_qty);
      }
   
    function qtyCheck(row)
    { 
        
           var stockQty = $(row).parent().parent('tr').find("td .assoc_qty").val();
           var orderQty = $(row).parent().parent('tr').find("td .order_qty").val();
           var actual_stock_Qty = $(row).closest('tr').find("td .actual_stock_qty").val();
          
           if(actual_stock_Qty === '-')
           {
               alert("Please check PO No.");
               $(row).val(0);
           }
           else
           {
               var reqQty = $(row).val(); 
               var allowQty =  0;
               var Word =  "";
               
               var out_type=$('#out_type_id').val();
               if(out_type!=4)
               {    
                   if(parseFloat(orderQty) < parseFloat(stockQty))
                   {
                       allowQty = parseFloat(orderQty);
                       Word = 'Order Quantity';
                   }
                   else if(parseFloat(orderQty) < parseFloat(stockQty))
                   {
                        allowQty = parseFloat(stockQty);
                        Word = 'Stock Quantity';
                   }
                   else if(parseFloat(orderQty) == parseFloat(stockQty))
                   {
                       allowQty = parseFloat(orderQty);
                       Word = 'Stock Quantity And Order Qty';
                   }
                   
                   var minQty = Math.min(parseFloat(orderQty), parseFloat(stockQty));

                    if(parseFloat(reqQty) > minQty) 
                    { 
                        alert("Quantity must be less than " + Word + " (" + minQty + ")");
                        $(row).val("");
                    }
               }
          }
           
    }
      
    function deleteRowcone(btn)
    {
           var row = btn.parentNode.parentNode;
           row.parentNode.removeChild(row);
           recalcId();
           mycalc();
   }
   
</script>
@endsection