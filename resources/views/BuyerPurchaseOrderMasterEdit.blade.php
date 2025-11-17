@extends('layouts.master') 
@section('content')
@php  
    ini_set('memory_limit', '512M');
@endphp
<style>
    .select2-dropdown.increasezindex 
    {
        z-index:99999;
    }
    
    .hide
    {
        display:none;
    }
     
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }  
    .navbar-brand-box
    {
        width: 266px !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4" style="font-size:20px;">Sales Order <span id="bom_title" class="hide" style="color:green;">(Costing has been done)</span></h4>
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
            @if(isset($BuyerPurchaseOrderMasterList))
            <form action="{{ route('BuyerPurchaseOrder.update',$BuyerPurchaseOrderMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tr_code" class="form-label">Sales Order No</label>
                        <input type="text" name="tr_code" class="form-control" id="tr_code" value="{{ $BuyerPurchaseOrderMasterList->tr_code }}" readOnly> 
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tr_date" class="form-label">Entry Date</label>
                        <input type="date" name="tr_date" class="form-control" id="tr_date" value="{{ $BuyerPurchaseOrderMasterList->tr_date }}" readOnly>
                        <input type="hidden" name="tr_code" class="form-control" id="tr_code" value="{{ $BuyerPurchaseOrderMasterList->tr_code }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $BuyerPurchaseOrderMasterList->c_code }}">
                        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $BuyerPurchaseOrderMasterList->created_at }}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="in_out_id" class="form-label">Execution SBU</label>
                        <select name="in_out_id" class="form-select select2" id="in_out_id" required>
                           <option value="">--Select Execution SBU--</option>
                           <option value="1" {{ $BuyerPurchaseOrderMasterList->in_out_id == 1 ? 'selected="selected"' : '' }}>Inhouse</option>
                           <option value="2" {{ $BuyerPurchaseOrderMasterList->in_out_id == 2 ? 'selected="selected"' : '' }}>Outsource</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">Buyer PO No.</label>
                        <input type="text" name="po_code" class="form-control" id="po_code" value="{{ $BuyerPurchaseOrderMasterList->po_code }}" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label>
                        <select name="order_type" class="form-select select2"   id="order_type" required>
                           <option value="">--Select Order Type--</option>
                           <option value="1" {{ $BuyerPurchaseOrderMasterList->order_type == 1 ? 'selected="selected"' : '' }}>FOB</option>
                           <option value="3" {{ $BuyerPurchaseOrderMasterList->order_type == 3 ? 'selected="selected"' : '' }}>Job Work</option>
                           <option value="2"  {{ $BuyerPurchaseOrderMasterList->order_type == 2 ? 'selected="selected"' : '' }}>Stock</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="og_id" class="form-label">Market Type</label>
                        <select name="og_id" class="form-select" id="og_id" onchange="SetCurrency();">
                           <option value="">--Select Market Type--</option>
                           @foreach($OrderGroupList as  $row)
                           {
                           <option value="{{ $row->og_id }}"
                           {{ $row->og_id == $BuyerPurchaseOrderMasterList->og_id ? 'selected="selected"' : '' }}
                           >{{ $row->order_group_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="orderCategoryId" class="form-label">Order Category</label>
                        <select name="orderCategoryId" class="form-select" id="orderCategoryId" required>
                           <option value="">--Select Order Category--</option>
                           @foreach($OrderCategoryList as  $row) 
                           <option value="{{ $row->orderCategoryId }}" {{ $row->orderCategoryId == $BuyerPurchaseOrderMasterList->orderCategoryId ? 'selected="selected"' : '' }}>{{ $row->OrderCategoryName }}</option> 
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party Name</label>
                        <select name="Ac_code" class="form-select" id="Ac_code" required onchange="getSeasonList(this.value); getBrandList(this.value);GetDestinationForSalesOrderList();">
                           <option value="">--Select Buyer/Party--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $BuyerPurchaseOrderMasterList->Ac_code ? 'selected="selected"' : '' }}    
                           >{{ $row->ac_short_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select" id="brand_id" required>
                           <option value="">--Select Buyer Brand--</option>
                           @foreach($BrandList as  $row)
                           {
                           <option value="{{ $row->brand_id }}"
                           {{ $row->brand_id == $BuyerPurchaseOrderMasterList->brand_id ? 'selected="selected"' : '' }}
                           >{{ $row->brand_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <!--<div class="col-md-2">-->
                  <!--    <div class="mb-3">-->
                  <!--        <label for="buyer_delivery_date" class="form-label">Delivery Date</label>-->
                  <!--        <input type="date" name="buyer_delivery_date" class="form-control" id="buyer_delivery_date" value="{{$BuyerPurchaseOrderMasterList->buyer_delivery_date}}" required>-->
                  <!--    </div>-->
                  <!--</div> -->
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="currency_id" class="form-label">Currency</label>
                        <select name="currency_id" class="form-select" id="currency_id" disabled  onchange="ExchangeCurrency();">
                           <option value="">--Select Currency--</option>
                           @foreach($CurrencyList as  $row)
                           {
                           <option value="{{ $row->cur_id }}"
                           {{ $row->cur_id == $BuyerPurchaseOrderMasterList->currency_id ? 'selected="selected"' : '' }}
                           >{{ $row->currency_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inr_rate" class="form-label">Rate</label>
                        <input type="number" step="any" name="inr_rate" max="999999" oninput="if(this.value.length &gt; 6) this.value = this.value.slice(0,6);" class="form-control" id="inr_rate" value="{{$BuyerPurchaseOrderMasterList->inr_rate}}" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" step="any" name="exchange_rate" class="form-control" id="exchange_rate" value="{{$BuyerPurchaseOrderMasterList->exchange_rate}}" @if($BuyerPurchaseOrderMasterList->og_id == 1) disabled @endif onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Rate  (INR)</label>
                        <input type="number" step="any" name="order_rate" class="form-control" id="order_rate" value="{{round($BuyerPurchaseOrderMasterList->order_rate,2)}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="season_id" class="form-label">Season</label>
                        <select name="season_id" class="form-select" id="season_id" required>
                           <option value="">--Select Season--</option>
                           @foreach($SeasonList as  $row)
                           {
                           <option value="{{ $row->season_id }}"
                           {{ $row->season_id == $BuyerPurchaseOrderMasterList->season_id ? 'selected="selected"' : '' }}
                           >{{ $row->season_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Style</label>
                        <select name="mainstyle_id" class="form-select select2" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
                           <option value="">--Select Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $BuyerPurchaseOrderMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" onchange="getStyle(this.value)" required>
                           <option value="">--Select Sub Style--</option>
                           @foreach($SubStyleList as  $row)
                           {
                           <option value="{{ $row->substyle_id }}"
                           {{ $row->substyle_id == $BuyerPurchaseOrderMasterList->substyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-select select2" id="fg_id" required>
                           <option value="">--Select Style Name--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                           {{ $row->fg_id == $BuyerPurchaseOrderMasterList->fg_id ? 'selected="selected"' : '' }}         
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No.</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{ $BuyerPurchaseOrderMasterList->style_no }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{ $BuyerPurchaseOrderMasterList->style_description }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sam" class="form-label">SAM</label>
                        <input type="number" step="any" name="sam" class="form-control" id="sam" value="{{ $BuyerPurchaseOrderMasterList->sam ? $BuyerPurchaseOrderMasterList->sam : $sam }}" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ptm_id" class="form-label">Payment Terms</label>
                        <select name="ptm_id" class="form-select" id="ptm_id" required>
                           <option value="">--Select Payment Terms--</option>
                           @foreach($PaymentTermsList as  $row)
                           {
                           <option value="{{ $row->ptm_id }}"
                           {{ $row->ptm_id == $BuyerPurchaseOrderMasterList->ptm_id ? 'selected="selected"' : '' }}
                           >{{ $row->ptm_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="dterm_id" class="form-label">TNA Template</label>
                        <select name="dterm_id" class="form-select" id="dterm_id" required>
                           <option value="">--Select TNA Template--</option>
                           @foreach($DeliveryTermsList as  $row)
                           {
                           <option value="{{ $row->dterm_id }}"
                           {{ $row->dterm_id == $BuyerPurchaseOrderMasterList->dterm_id ? 'selected="selected"' : '' }}
                           >{{ $row->delivery_term_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ship_id" class="form-label">Shipment Mode</label>
                        <select name="ship_id" class="form-select" id="ship_id" required>
                           <option value="">--select Shipment--</option>
                           @foreach($ShipmentList as  $row)
                           {
                           <option value="{{ $row->ship_id }}"
                           {{ $row->ship_id == $BuyerPurchaseOrderMasterList->ship_id ? 'selected="selected"' : '' }}
                           >{{ $row->ship_mode_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <input type="hidden" name="country_id" id="country_id" value="{{$BuyerPurchaseOrderMasterList->country_id}}" />
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="warehouse_id" class="form-label">Ship To Location</label>
                        <select name="warehouse_id" class="form-select" id="warehouse_id" required>
                           <option value="">--Select Ship To Location--</option>
                           @foreach($WarehouseList as  $row)
                           {
                           <option value="{{ $row->sr_no }}"
                           {{ $row->sr_no == $BuyerPurchaseOrderMasterList->warehouse_id ? 'selected="selected"' : '' }}
                           >{{ $row->site_code }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">PO Received Date</label>
                        <input type="date" name="order_received_date" class="form-control" id="order_received_date" value="{{$BuyerPurchaseOrderMasterList->order_received_date}}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="plan_cut_date" class="form-label">Plan Cut Date(PCD)</label>
                        <input type="date" name="plan_cut_date" class="form-control" id="plan_cut_date" value="{{ $BuyerPurchaseOrderMasterList->plan_cut_date }}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inspection_date" class="form-label">Inspection Date</label> 
                        <input type="date" name="inspection_date" class="form-control" id="inspection_date" value="{{ $BuyerPurchaseOrderMasterList->inspection_date }}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">Shipment Date</label>
                        <input type="date" name="shipment_date" class="form-control" id="shipment_date" value="{{ $BuyerPurchaseOrderMasterList->shipment_date }}" onchange="setOtherDate();" required>
                     </div>
                  </div> 
                  <input type="hidden" name="ex_factory_date" class="form-control" id="ex_factory_date" value="{{ $BuyerPurchaseOrderMasterList->ex_factory_date }}"> 
                  <input type="hidden" name="from_tna_date" class="form-control" id="from_tna_date" value="{{date('Y-m-d')}}">
                  <input type="hidden" name="to_tna_date" class="form-control" id="to_tna_date" value="{{date('Y-m-d')}}">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Size Group</label>
                        <select name="sz_code" class="form-select select2"   id="sz_code"  onChange="GetSizeDetailList(this.value);" disabled>
                           <option value="">--Select Size Group--</option>
                           @foreach($SizeList as  $row)
                           {
                           <option value="{{ $row->sz_code }}"
                           {{ $row->sz_code == $BuyerPurchaseOrderMasterList->sz_code ? 'selected="selected"' : '' }}        
                           >{{ $row->sz_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <input type="number" value="{{ count($BuyerPurchaseOrderDetaillist) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
                  <div class="table-wrap">
                     <div class="table-responsive">
                        <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                           <thead>
                              <tr class="text-center">
                                 <th>SrNo</th>
                                 <th>Item Code</th>
                                 <th>Fabric Color</th>
                                 <th>Style No.</th>
                                 <th>Garment Color</th>
                                 @foreach($SizeDetailList as $sz) 
                                 <th>{{ $sz->size_name }}</th>
                                 @endforeach
                                 <th>Total Qty</th>
                                 <th nowrap>Ship Allow %</th>
                                 <th>Adjust Qty</th>
                                 <th>Remark</th>
                                 @php  if(Session::get('user_type')==1 ||  $BOMCheck == 0){   @endphp
                                 <th>Add</th>
                                 <th>Remove</th>
                                 @php }  @endphp
                              </tr>
                           </thead>
                           <tbody id="buyerData">
                           </tbody>
                        </table>
                        <div class="row text-center"><img src="../../images/loading_dashboard.gif" id="loadingImg" width="150" height="300" ></div>
                     </div>
                  </div>
                  <input type="hidden" id="size_count" value="{{count($SizeDetailList)}}">
                  <table id="footable_1" style=" margin-left:auto;margin-right:auto;">
                     <tbody>
                        <tr>
                           <th colspan="2">Total</th>
                           <th colspan="2"> &nbsp;</th>
                           <th colspan="2">&nbsp; </th>
                           <th colspan="2">&nbsp; </th>
                           @php $nx=1; $nn=0;
                           
                           foreach ($SizeDetailList as $row) 
                           {
                           echo  " <th>".$row->size_name."<input type='number' name='s".$nx."total[]'  class='size_total' value='' id='s".$nx."total' style='width:80px; height:30px;' /></th>";
                           $nx=$nx+1; $nn= $nn+1;
                           }
                           @endphp 
                           <th><input type="hidden" name="sz_ws_total" value="" id="sz_ws_total" style="width:80px; height:30px;" /> </th>
                           <th></th>
                           <th></th>
                        </tr>
                     <tbody> 
                  </table>
               </div>
               <div class="row mt-5">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_pic_path" class="form-label">Style Image</label>
                        <input type="file" name="style_pic_path" class="form-control" id="style_pic_path"  >
                        <input type="hidden" name="style_pic_pathold" class="form-control" id="style_pic_pathold" value="{{ $BuyerPurchaseOrderMasterList->style_img_path }}"  >
                     </div>
                  </div> 
                  @if($BuyerPurchaseOrderMasterList->style_img_path!='') 
                  <div class="col-md-2">
                     <div class="mb-3"> 
                        <label for="formrow-email-input" class="form-label">Preview: </label>
                        <a href="{{url('images/'.$BuyerPurchaseOrderMasterList->style_img_path)}}" target="_blank"><img  src="{{url('thumbnail/'.$BuyerPurchaseOrderMasterList->style_img_path)}}" height="60" width="50" > </a>
                     </div>
                  </div>
                  @endif
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label">PO Document</label>
                        <input type="file" name="doc_path1" class="form-control" id="doc_path1" > 
                        <input type="hidden" name="doc_path1old" class="form-control" id="doc_path1old" value="{{$BuyerPurchaseOrderMasterList->buyer_document_path}}" >
                     </div>
                  </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tech_pack" class="form-label"> Tech Pack </label>
                        <input type="file" name="tech_pack" class="form-control" id="tech_pack" value="">
                         <input type="hidden" name="tech_pack_old" class="form-control" id="tech_pack_old" value="{{$BuyerPurchaseOrderMasterList->tech_pack}}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label"> Measurement sheet </label>
                        <input type="file" name="measurement_sheet" class="form-control" id="measurement_sheet" value="">
                         <input type="hidden" name="measurement_sheet_old" class="form-control" id="measurement_sheet_old" value="{{$BuyerPurchaseOrderMasterList->measurement_sheet}}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fit_pp_comments" class="form-label"> FIT/PP comments </label>
                        <input type="file" name="fit_pp_comments" class="form-control" id="fit_pp_comments" value="">
                         <input type="hidden" name="fit_pp_comments_old" class="form-control" id="fit_pp_comments_old" value="{{$BuyerPurchaseOrderMasterList->fit_pp_comments}}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="approved_fabric_trim" class="form-label">  Approved Fabric/Trim</label>
                        <input type="file" name="approved_fabric_trim" class="form-control" id="approved_fabric_trim" value="">
                         <input type="hidden" name="approved_fabric_trim_old" class="form-control" id="approved_fabric_trim_old" value="{{$BuyerPurchaseOrderMasterList->approved_fabric_trim}}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input type="number" step="any"  name="total_qty" class="form-control" id="total_qty" value="{{ $BuyerPurchaseOrderMasterList->total_qty }}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_value" class="form-label">Order Value</label>
                        <input type="text" name="order_value" class="form-control" id="order_value" value="{{$BuyerPurchaseOrderMasterList->order_value}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="unit_id" class="form-label">UOM</label>
                        <select name="unit_ids"  id="unit_ids" class="form-select"   required>
                           <option value="">--Select Unit--</option>
                           @foreach($UnitList as  $row)
                           {
                           <option value="{{ $row->unit_id }}"
                           {{ $row->unit_id == $BuyerPurchaseOrderMasterList->unit_id ? 'selected="selected"' : '' }}    
                           >{{ $row->unit_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div> 
                  <input type="hidden" name="shipped_qty" class="form-control" id="shipped_qty" value="{{ $ShippedQty[0]->ShippedQty }}" readOnly onkeyup="calculate();"> 
                  <input type="hidden" name="balance_qty" class="form-control" id="balance_qty" value="{{ $BuyerPurchaseOrderMasterList->balance_qty }}" readOnly> 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="merchant_id" class="form-label">Bulk Merchant</label>
                        <select name="merchant_id" class="form-select" id="merchant_id" required>
                           <option value="">--Select Bulk Merchant--</option>
                           @foreach($MerchantList as  $row)
                           {
                           <option value="{{ $row->merchant_id }}"
                           {{ $row->merchant_id == $BuyerPurchaseOrderMasterList->merchant_id ? 'selected="selected"' : '' }}     
                           >{{ $row->merchant_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="PDMerchant_id" class="form-label">PD Merchant</label>
                        <select name="PDMerchant_id" class="form-select" id="PDMerchant_id" required>
                           <option value="">--Select PD Merchant--</option>
                           @foreach($PDMerchantList as  $row)
                           {
                           <option value="{{ $row->PDMerchant_id }}"
                           {{ $row->PDMerchant_id == $BuyerPurchaseOrderMasterList->PDMerchant_id ? 'selected="selected"' : '' }}   
                           >{{ $row->PDMerchant_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="job_status_id" class="form-label">PO Status</label>
                        <select name="job_status_id" class="form-select" id="job_status_id" required onchange="CheckOpenWorkProcessOrders(this.value);">
                           <option value="">--Select PO Status--</option>
                           @foreach($JobStatusList as  $row)
                           {
                           <option value="{{ $row->job_status_id }}"
                           {{ $row->job_status_id == $BuyerPurchaseOrderMasterList->job_status_id ? 'selected="selected"' : '' }}     
                           >{{ $row->job_status_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_close_date" class="form-label">Order Close Date</label>
                        <input type="date" name="order_close_date" class="form-control" id="order_close_date" value="{{$BuyerPurchaseOrderMasterList->order_close_date}}" readonly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <label for="formrow-inputState" class="form-label">Order Remark / Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{ $BuyerPurchaseOrderMasterList->narration }}" />
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <label for="formrow-inputState" class="form-label"></label>
                     <div class="form-group">
                        <button type="submit" class="btn btn-primary w-md" id="Submit" onclick="mycalc(); EnableFields();">Submit</button>
                        <a href="{{ Route('BuyerPurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
   <input type="hidden" id="user_type" value="{{Session::get('user_type')}}">
   <input type="hidden" id="is_approved" value="{{$is_approved}}">
   <input type="hidden" id="BOMCheck" value="{{$BOMCheck}}">
   <input type="hidden" id="costing_count" value="{{$costing_count}}">
</div>
<!-- end row -->
<!-- end row -->  
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- end row -->
<script>
   
    function setOtherDate()
    {
        var shipment_date = $("#shipment_date").val();
    
        if (!shipment_date) return; // if shipment date is empty, exit
    
        // Convert to Date object
        var sDate = new Date(shipment_date);
    
        // Plan cut date = shipment_date - 30 days
        var planCutDate = new Date(sDate);
        planCutDate.setDate(sDate.getDate() - 30);
    
        // Inspection date = shipment_date - 4 days
        var inspectionDate = new Date(sDate);
        inspectionDate.setDate(sDate.getDate() - 4);
    
        // Format dates as yyyy-mm-dd
        var formatDate = (date) => {
            var yyyy = date.getFullYear();
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
    
        $("#plan_cut_date").val(formatDate(planCutDate));
        $("#inspection_date").val(formatDate(inspectionDate));
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

    $(document).on('input', 'input[type="date"]', function () {
        let value = $(this).val();
    
        // Split by dash
        let parts = value.split('-');
    
        // Limit year to 4 digits
        if (parts[0] && parts[0].length > 4) {
            parts[0] = parts[0].substring(0, 4);
        }
    
        // Limit month to 2 digits
        if (parts[1] && parts[1].length > 2) {
            parts[1] = parts[1].substring(0, 2);
        }
    
        // Limit day to 2 digits
        if (parts[2] && parts[2].length > 2) {
            parts[2] = parts[2].substring(0, 2);
        }
    
        $(this).val(parts.join('-'));
    });


   window.onload = function() {
     mycalc();
     var user_type = $("#user_type").val();
     var is_approved = $("#is_approved").val();
     var BOMCheck = $("#BOMCheck").val();
     
     if(parseInt(user_type) !=1 && BOMCheck>0 || is_approved==1) 
     {
            $("input").prop("readonly", false);
            //$("select").select2('destroy');
     } 
   };
   
     $(document).ready(function()
     {
        $('input[type="text"], textarea').on('input', function() {
            // Remove single and double quotes
            $(this).val($(this).val().replace(/['"]/g, ""));
        });

        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
    
    

    function SetCurrency() 
    {
        var og_id = $("#og_id").val(); // 1 = Domestic, 2 = Export
    
        if (og_id == 1) { // Domestic
            // Force Rupees
            $("#currency_id").val(1);
            $("#currency_id").attr('disabled', true);
            $("#exchange_rate").val(1);
            $("#exchange_rate").attr('readonly', true);
            $("#order_rate").val(0);
            $("#inr_rate").val(0);
        } else if (og_id == 2) { // Export
            // Allow selecting only non-Rupee currency
            $("#currency_id").val(""); // clear selection
            $("#currency_id").attr('disabled', false);
            $("#exchange_rate").val("");
            $("#exchange_rate").attr('readonly', false);
            $("#order_rate").val(0);
            $("#inr_rate").val(0);
        } else {
            // Default case
            $("#currency_id").attr('disabled', false);
            $("#exchange_rate").attr('readonly', false);
            $("#order_rate").val(0);
            $("#inr_rate").val(0);
        }
    }
    
    function ExchangeCurrency() {
        var currency_id = $("#currency_id").val();
        var og_id = $("#og_id").val();
    
        if (og_id == 1) { 
            // Domestic: always Rupees
            $("#currency_id").val(1);
            $("#currency_id").attr('disabled', true);
            $("#exchange_rate").val(1);
            $("#exchange_rate").attr('readonly', true);
            $("#inr_rate").val(0);
            $("#order_rate").val(0);
        } 
        else if (og_id == 2) 
        { 
            // Export: Rupees is NOT allowed
            if (currency_id == 1) {
                alert("Rupees is not allowed for Export orders. Please select another currency.");
                $("#currency_id").val(""); // reset currency
                $("#exchange_rate").val("");
                $("#exchange_rate").attr('readonly', false);
                $("#inr_rate").val(0);
                $("#order_rate").val(0);
            } else {
                // Any other currency
                $("#exchange_rate").val("");
                $("#exchange_rate").attr('readonly', false);
                $("#inr_rate").val(0);
                $("#order_rate").val(0);
            }
            $("#currency_id").attr('disabled', false);
        } 
        else
        {
            // Default fallback
            $("#currency_id").attr('disabled', false);
            $("#exchange_rate").attr('readonly', false);
            $("#inr_rate").val(0);
            $("#order_rate").val(0);
        }
    }
    
    // Bind to change events
    $("#og_id").on('change', SetCurrency);
    $("#currency_id").on('change', ExchangeCurrency);


    function GetDestinationForSalesOrderList()
    {
        var Ac_code = $("#Ac_code").val();
        $.ajax({
           type: "GET",
           url: "{{ route('GetDestinationForSalesOrderList') }}",
           data:{'Ac_code':Ac_code },
           success: function(data)
           {
                $("#warehouse_id").html(data.html);
           }
       });
    }
    
    
    function checkDuplicateColor(row)
    {
        var garment_color = $(row).val();
        $(".Garment_color").not(row).each(function()
        {
            if($(this).val() == garment_color)
            {
                alert("This garment color has already been selected.");
                $(row).select2('destroy');
                $(row).val("");
                $(row).select2();
            }
        });
    }
    
   function BomData()
   {
    
        var userId = $("#userId").val();
        var user_type = $("#user_type").val();
        var costing_count = $("#costing_count").val();
        var is_approved = $("#is_approved").val();
        
        if(costing_count == 0 || user_type == 1)
        {
     
            $('input').attr('disabled',false);
            $('select').not('#currency_id').not('#og_id').attr('disabled',false);
            // $('#Submit').removeClass("hide");
            $('#bom_title').addClass("hide");
           
        }
        else
        {

           if(parseInt(is_approved) == 2) 
           {
              $('input').not('input[name="shipment_allowance[]"]').not('input[name="adjust_qty[]"]').attr('disabled',true);
           }
           
           // $('#order_close_date').removeAttr('disabled');
           if(userId == 7 || userId == 15)
           {
               $('select').not('#job_status_id').attr('disabled',true);
           }
           else
           {
                if(parseInt(is_approved) == 2) 
                {
                    console.log("loop");
                    $('select').attr('disabled',true);
                }
                else
                {
                    $('select').attr('disabled',false);
                }
           
           }
            
           // $('#job_status_id').removeAttr('disabled');
            // $('#Submit').addClass("hide");
            $('#bom_title').removeClass("hide");
        }
   }
   
    function calculate()
    {
       
        var shipped_qty=$('#shipped_qty').val();
        var order_qty=$('#total_qty').val();
        var balance_qty=order_qty-shipped_qty;
        $('#balance_qty').val(balance_qty);
        
        
    }
   
   var s1=0; var s4=0; var s7=0; var s10=0; var s13=0; var s16=0; var s19=0;  
   var s2=0; var s5=0; var s8=0; var s11=0; var s14=0; var s17=0; var s20=0;  
   var s3=0; var s6=0; var s9=0; var s12=0; var s15=0; var s18=0;  
   $(document).on("keyup", 'input[class^="size_id"]', function (event) 
   {
        var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
        var size_array = sizes.split(',');
         var values = [];
         
         $("#footable_3 tr td  input[class^='size_id']").each(function() {
         values.push($(this).val());
         console.log($(this).val());
         if(values.length==size_array.length)
         {
             
           $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
           var sum = values.reduce(function( a,  b){
                   return parseInt(a) + parseInt(b);
               }, 0);
               
           $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
           
               values = [];
           }
     
         
       });
        
     mycalc();
     calculate();
        
      });
      
    $(document).ready(function () 
    {
        var tr_code = $('#tr_code').val();
        var Ac_code = $('#Ac_code').val();
        var size_count = $("#size_count").val();
      
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('EditDetailData') }}",
          data:{tr_code:tr_code, 'Ac_code': Ac_code},
          beforeSend: function() {
             $('#loadingImg').show();
             $('table #buyerData').removeClass('show').addClass('hide');
          },
          complete: function()
          {
                 $('#loadingImg').hide();
                 $('table #buyerData').removeClass('hide').addClass('show');
                 BomData();
                 $('#sz_code').attr('disabled', true); 
          },
          success: function(response)
          {
            $("#buyerData").html(response.html);
            mycalc();
            for(var i=1; i<=size_count; i++)
            {
                var size = $('.s'+i);
                var size_total = 0;
                
                $.each(size, function (i) 
                {
                    size_total = parseFloat(size_total) + parseFloat($(this).val());
                });
                
                $('#s'+i+'total').val(size_total).attr('readonly', true);
            }
          }
 
        });
        setTimeout(function() {
           $('select[name="item_code[]"]').select2(); 
           $('select[name="color_id[]"]').select2();   
           $('select[name="unit_id[]"]').select2(); 
           $('select[name="style_no_id[]"]').select2(); 
           $('#sz_code').attr('disabled', true); 
        }, 1000);
    });
    
    // $(document).on("click",".select2",function() 
    // {
    //       let $select = $(this).siblings('select').children(), 
    //       $parent = $select.parent();
            
    //         $select.select2({
    //           dropdownParent: $parent
    //         });
    //       });
    
    function CheckOpenWorkProcessOrders(job_status_id)
    {
       var order_type = $("#order_type").val();
       var order_close_date = $("#order_close_date").val();
       var userId = $("#userId").val();
       if(order_type != 2 && order_close_date == "" && job_status_id != 1) 
       {
            alert("This process cannot be reversed...!");
            var today = new Date();
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var year = today.getFullYear();
            var formattedDate = year + '-' + month + '-' + day;
            $("#order_close_date").val(formattedDate);
            $("#order_close_date").attr('readonly', true);
             $("#job_status_id").val(job_status_id);
            $("#job_status_id").attr('disabled', true);
       }
       else
       {
           $("#order_close_date").attr('readonly', true);
           if(job_status_id == 1)
           {
                $("#order_close_date").val('');
           }
           
       }
       
       if(job_status_id!=5)
       {
           var tr_code = $('#tr_code').val();
            $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('CheckOpenWorkProcessOrders') }}",
               data:{sales_order_no:tr_code},
               success: function(response)
               {
                   var workorders= response[0]['workorders'];
                   var processorders=response[0]['processorders'];
                   var count=workorders + processorders;
                   if(count>0)
                   {
                       alert("This PO can't be Closed, As  ("+workorders+") Work Orders & ("+processorders+") Process Orders are Still in Open Status.");
                       $('#job_status_id').val(1);
                       $("#order_close_date").val('');
                   }
                  
               }
           }); 
       } 
    }

   function calOrderRate()
   {
       var exchange_rate=$('#exchange_rate').val();
       var inr_rate=$('#inr_rate').val();
       var order_rate=(parseFloat(inr_rate) * parseFloat(exchange_rate)).toFixed(2);
       $('#order_rate').val(order_rate);
       
   }
   
   
   function EnableFields()
   {
        $("input").prop("readonly", false);
        $("input").removeAttr("disabled");
        $("select").removeAttr("disabled"); 
     
   }
   
    $(document).on("keyup", 'input[class^="size_id"]', function (event) 
   {
        var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
        if(sizes != "")
        {
            var size_array = sizes.split(',');
        }
        else
        {
            var size_array = "";
        }
         var values = [];
         $("#footable_3 tr td  input[class='size_id']").each(function() {
         values.push($(this).val());
         if(values.length==size_array.length)
         {
             
           $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
           
           var sum = values.reduce(function( a,  b){
                   return parseInt(a) + parseInt(b);
               }, 0);
           $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
           
               values = [];
         }
       });
       
       
            mycalc();
       calculate();
        
      });
       
    mycalc();
    calculate();
       
    function getSubStyle(val) 
    {	//alert(val);
       $.ajax({
       type: "GET",
       url: "{{ route('SubStyleList') }}",
       data:'mainstyle_id='+val,
       success: function(data)
       {
           $("#substyle_id").select2("destroy");
           $("#fg_id").html("");
           $("#fg_id").select2("destroy");
           $("#substyle_id").html(data.html);
           $("#substyle_id").select2();
       }
       });
    }   
        
    function getStyle(val) 
    {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('StyleList') }}",
       data:{'substyle_id':val, },
       success: function(data)
       {
           $("#fg_id").html("");
            $("#fg_id").select2("destroy");
            $("#fg_id").html(data.html);
            $("#fg_id").select2();
       }
       });
    }      
     
     
    function getSeasonList(val) 
    {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('SeasonList') }}",
       data:{'Ac_code':val, },
       success: function(data){
       $("#season_id").html(data.html);
       }
       });
   }  
   
//   $( document ).ready(function() 
//   {
//         getBrandList($("#Ac_code").val()); 
//   });
   
   function getBrandList(val) 
   {	//alert(val);
   
      $.ajax({
       type: "GET",
       url: "{{ route('BrandList') }}",
       data:{'Ac_code':val, },
       success: function(data){
       $("#brand_id").html(data.html);
       }
       });
   }    
     
     
    function calculate()
    {
       
        var shipped_qty=$('#shipped_qty').val();
        var order_qty=$('#total_qty').val();
        var balance_qty=order_qty-shipped_qty;
        $('#balance_qty').val(balance_qty);
        
        
    }
    
    
//     $('#footable_3').on('change', '.item', function() 
//     {
//       var tax_type_id=document.getElementById('tax_type_id').value;
//   if(tax_type_id!=0)
//   {
//       var item_code = $(this).val();
//       var row = $(this).closest('tr'); // get the row
        
//       $.ajax({
//           type: "GET",
//           dataType:"json",
//           url: "{{ route('TaxList') }}",
//           data:'item_code='+item_code,
//           success: function(data){
//               if(tax_type_id==1)
//               {
//                           row.find('input[name^="cgst_per[]"]').val(data[0]['cgst_per']);
//                           row.find('input[name^="sgst_per[]"]').val(data[0]['sgst_per']);
//                           row.find('input[name^="igst_per[]"]').val();
//               }
//               else
//               {
//                           row.find('input[name^="igst_per[]"]').val(data[0]['igst_per']);
//                           row.find('input[name^="cgst_per[]"]').val(0);
//                           row.find('input[name^="sgst_per[]"]').val(0);
//               }
         
//           }
//           });
//   }
//   else
//   {
//       alert('Select Tax Type..!');
//   }
           
   
   
//   });
   
   //  function addrow()
   // {
   //     var row = $("#footable_3 tr:last");
   
   //     row.find(".color").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
       
   //     row.find(".Item").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
   
   //     row.find(".unit").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
       
   //      row.find(".size").each(function(index)
   //     {
   //         $(this).select2('destroy');
   //     }); 
   
   //    var newrow = row.clone();        
       
   //     // $('#footable_3 tbody tr:last').find('select[name^="item_code[]"]').each(function() {
   //     //     newrow.find('select[name^="item_code[]"]').val(this.value);
   //     // });
   
   //  $('#footable_3 tbody tr:last').find('select[name^="unit_id[]"]').each(function() {
   //         newrow.find('select[name^="unit_id[]"]').val(this.value);
   //     });
   
   //     $("#footable_3").append(newrow);
   
   //      $("select.select2").select2();
        
   //      document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   //      recalcIdcone();
       
   //     mycalc();
   //     calculate();
       
        
   // }
    
   
          
   $(document).on('click', '.Abutton', function () 
   {
           
           $('select[name="item_code[]"]').select2('destroy');
           $('select[name="color_id[]"]').select2('destroy');
          
           var $tableBody = $('#footable_3').find("tbody"),
               $trLast = $tableBody.find("tr:last"),
               $trNew = $trLast.clone();
           $($trNew[0]).find('td:nth-child(1) input').val(parseInt($($trNew[0]).find('td:nth-child(1) input').val()) + parseInt(1));
         
           $trLast.after($trNew); 
           $trNew.find('select[name="item_code[]"]').val("");
           $trNew.find('select[name="color_id[]"]').val("");  
           $trNew.find('select[name="style_no_id[]"]').val("");
           $trNew.find('select[name="item_code[]"]').select2();
           $trNew.find('select[name="color_id[]"]').select2();
           $trNew.find('select[name="style_no_id[]"]').select2();
       
   });
//   $('select').focus( function() {
//         $(this).select2();
//     });
    
//     $('select').blur( function() {
//       $(this).select2('destroy');
//     });
    // $("select").on({
    //     if ($('select .select2').length  > 0)
    //     {
    //         $(this).select2('destroy');
    //     } 
    //     focus: function () {
    //          $(this).select2();
    //     }
    // });
   

   
    function GetSizeDetailList(str)
    {
       $.ajax({
           dataType: "json",
       url: "{{ route('SizeDetailList') }}",
       data:{'sz_code':str},
       success: function(data){
       $("#footable_3").html(data.html);
       }
       });
       
           $('select[name="item_code[]"]').select2();
           $('select[name="color_id[]"]').select2();
           $('select[name="style_no_id[]"]').select2();  
    }
   
   
   
   
//   $(document).on("click", 'input[name^="Abutton[]"]', function (event) {
       
//           //insertcone($(this).closest("tr"));
           
//       });
     
//   var indexcone = 2;
//   function insertcone(Abutton){
//       var rowsx=$(Abutton).closest("tr");
   
//   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
//   var row=table.insertRow(table.rows.length);
//   console.log(row);
//   var cell1=row.insertCell(0);
//   var t1=document.createElement("input");
//   t1.style="display: table-cell; width:50px;";
//   t1.id = "id"+indexcone;
//   t1.name= "id[]";
//   t1.value=indexcone;
//   cell1.appendChild(t1);
    
   
//   var cell5 = row.insertCell(1);
//   var t5=document.createElement("select");
//   var x = $("#color_id"),
//   y = x.clone();
//   y.attr("id","color_id");
//   y.attr("name","color_id[]");
//   var color=+rowsx.find('select[name^="color_id[]"]').val();
//   y.val(color);
//   y.attr("selected","selected"); 
//   y.width(100);
//   y.appendTo(cell5);
     
   
//   var cell4 = row.insertCell(2);
//   var t4=document.createElement("select");
//   var x = $("#sz_code"),
//   y = x.clone();
//   y.attr("id","sz_code");
//   y.attr("name","sz_code[]");
//   y.width(100);
//   y.appendTo(cell4);
   
//   var cell5 = row.insertCell(3);
//   var t5=document.createElement("input");
//   t5.style="display: table-cell; width:80px;";
//   t5.type="number";
//   t5.required="true";
//   t5.id = "qty"+indexcone;
//   t5.name="qty[]";
//   t5.className="QTY";
//   t5.onkeyup=mycalc();
//   t5.value="0";
//   t5.setAttribute("onkeyup", "mycalc();");
//   cell5.appendChild(t5);
   
//   var cell6 = row.insertCell(4);
//   var t5=document.createElement("select");
//   var x = $("#unit_id"),
//   y = x.clone();
//   y.attr("id","unit_id");
//   y.attr("name","unit_id[]");
//   var unit=+rowsx.find('select[name^="unit_id[]"]').val();
//   y.val(unit);
//   y.attr("selected","selected");
//   y.width(100);
//   y.appendTo(cell6);
    
    
//   var cell8=row.insertCell(5);
//   var btnAdd = document.createElement("input");
//   btnAdd.id = "Abutton";
//   btnAdd.name = "Abutton[]";
//   btnAdd.type = "button";
//   btnAdd.className="btn btn-warning pull-left";
//   btnAdd.value = "+";
//   // btnAdd.setAttribute("onclick", "insertcone(); ");
//   cell8.appendChild(btnAdd);
    
    
    
//   var btnRemove = document.createElement("INPUT");
//   btnRemove.id = "Dbutton";
//   btnRemove.type = "button";
//   btnRemove.className="btn btn-danger pull-left";
//   btnRemove.value = "X";
//   btnRemove.setAttribute("onclick", "deleteRowcone(this)");
//   cell8.appendChild(btnRemove);
   
//   var w = $(window);
//   var row = $('#footable_3').find('tr').eq(indexcone);
   
//   if (row.length){
//   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
//   }
   
//   document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
   
//   indexcone++;
//   recalcIdcone();
//     mycalc();
//   }
   
     
//   $("table.footable_3").on("keyup", 'input[name^="qty[]"],input[name^="base_rate[]"],input[name^="cgst_per[]"],input[name^="cgst_amt[]"],input[name^="sgst_per[]"],input[name^="sgst_amt[]"],input[name^="igst_per[]"],input[name^="igst_amt[]"],input[name^="amount[]"],input[name^="total_amount[]"],input[name^="gst_amt[]"],input[name^="total_qty[]"],input[name^="GrossAmount[]"],input[name^="GstAmount[]"],input[name^="NetAmount[]"]', function (event) {
//           // CalculateRow($(this).closest("tr"));
//           mycalc();
//       });
   	
   		
   		
   		
//   	function CalculateRow(row)
//   	{ 
//   		var qty=+row.find('input[name^="qty[]"]').val();
//           var total_qty=+row.find('input[name^="total_qty[]"]').val();
//   		var base_rate=+row.find('input[name^="base_rate[]"]').val();
//   		var amount=parseFloat(qty * base_rate).toFixed(2);
//           var total_amount=+row.find('input[name^="total_amount[]"]').val();
//   		var cgst_per=+row.find('input[name^="cgst_per[]"]').val();
//   		var cgst_amt=+row.find('input[name^="cgst_amt[]"]').val();
//           var igst_per=+row.find('input[name^="igst_per[]"]').val();
//   		var igst_amt=+row.find('input[name^="igst_amt[]"]').val();
//           var sgst_per=+row.find('input[name^="sgst_per[]"]').val();
//   		var sgst_amt=+row.find('input[name^="sgst_amt[]"]').val();
//   		var GrossAmount= +row.find('input[name^="GrossAmount[]"]').val();
//   		var GstAmount= +row.find('input[name^="GstAmount[]"]').val();
//   		var NetAmount=+row.find('input[name^="NetAmount[]"]').val();
   		  
   		 
//   		 if(qty>0)
//   		 {
   			 
//                 row.find('input[name^="amount[]"]').val(amount);
   			 
//   			 if(igst_per!=0)
//   			 {
//                   igst_amt=parseFloat(amount*(igst_per/100)).toFixed(2);
//   				  row.find('input[name^="igst_amt[]"]').val(parseFloat(igst_amt));
//   				  total_amount=parseFloat(amount)+parseFloat(igst_amt);
//   				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
//                      row.find('input[name^="gst_amt[]"]').val(parseFloat(igst_amt));
//                      row.find('input[name^="cgst_per[]"]').val(0);
//                   row.find('input[name^="cgst_amt[]"]').val(0);
//                   row.find('input[name^="sgst_per[]"]').val(0);
//                   row.find('input[name^="sgst_amt[]"]').val(0);
   
//   			 }
//   			 else
//   			 {
//                   row.find('input[name^="igst_per[]"]').val(0);
//                   row.find('input[name^="igst_amt[]"]').val(0);
//                   cgst_amt=parseFloat(amount*(cgst_per/100)).toFixed(2);
//   				  row.find('input[name^="cgst_amt[]"]').val(parseFloat(cgst_amt));
   				  
//   				  sgst_amt=parseFloat(amount*(sgst_per/100)).toFixed(2);
//   				  row.find('input[name^="sgst_amt[]"]').val(parseFloat(sgst_amt));
   				 				  
//   				  total_amount=parseFloat(amount)+parseFloat(cgst_amt)+parseFloat(sgst_amt);
//   				  row.find('input[name^="total_amount[]"]').val(parseFloat(total_amount));
//   				  row.find('input[name^="gst_amt[]"]').val(parseFloat(cgst_amt)+parseFloat(sgst_amt));
   				 
//   			 }
   			 
//   		}
   			 
//   			 	  mycalc();
//   }
    
   
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
   document.getElementById("total_qty").value = sum1.toFixed(2);
     
    var order_rate=$("#order_rate").val();
   var order_value=order_rate * sum1.toFixed(2);
   $("#order_value").val(order_value.toFixed(2));
   
   
   if($("#order_value").val()<=0)
   {
       document.getElementById("Submit").disabled=true;
   }
   else
   {
       document.getElementById("Submit").disabled=false;
   }
   
           var sizes=$("#size_array").val();
           if(typeof sizes !== 'undefined' && sizes.length > 0)
           {
              var size_array = sizes.split(',');
           }
           else
           {
               size_array = "";
           }
           var n=1;
           //alert(size_array.length);
           var sz_ws_totalx='';
           for(var k=1;k<=size_array.length;k++)
           {
               var sum5 = 0.0;
               var amounts = document.getElementsByName('s'+n+'[]');
               for(var i=0; i<amounts.length; i++)
               { 
               var a = +amounts[i].value;
               sum5 += parseFloat(a);
               }
               document.getElementById('s'+n+'total').value = sum5;
               n=n+1;
               sz_ws_totalx=sz_ws_totalx+sum5+',';
            }  
           document.getElementById("sz_ws_total").value = sz_ws_totalx.replace(/,\s*$/, "");
   
   }
   
   
   
   function deleteRowcone(btn) 
   {
       var row = btn.parentNode.parentNode;
       row.parentNode.removeChild(row);
       
       document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
       
       recalcIdcone();
       
       if($("#cntrr").val()<=0)
       {		
            document.getElementById('Submit').disabled=true;
       }
   }
   
   
   
           function recalcIdcone(){
   $.each($("#footable_3 tr"),function (i,el){
   $(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
   })
   }
    
   
</script>
<!-- end row -->
@endsection