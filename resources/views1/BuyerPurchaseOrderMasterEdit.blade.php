@extends('layouts.master') 
@section('content')
<style>
    .select2-dropdown.increasezindex 
    {
        z-index:99999;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sales Order</h4>
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
            <form action="{{ route('BuyerPurchaseOrder.update',$BuyerPurchaseOrderMasterList) }}" method="POST" enctype="multipart/form-data">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_code" class="form-label">Buyer PO No</label>
                        <input type="text" name="po_code" class="form-control" id="po_code" value="{{ $BuyerPurchaseOrderMasterList->po_code }}" required >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="tr_date" class="form-label">Entry Date</label>
                        <input type="date" name="tr_date" class="form-control" id="tr_date" value="{{ $BuyerPurchaseOrderMasterList->tr_date }}" required readOnly>
                        <input type="hidden" name="tr_code" class="form-control" id="tr_code" value="{{ $BuyerPurchaseOrderMasterList->tr_code }}">
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $BuyerPurchaseOrderMasterList->c_code }}">
                        <input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $BuyerPurchaseOrderMasterList->created_at }}">
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="og_id" class="form-label">Order Group</label>
                        <select name="og_id" class="form-select" id="og_id" required>
                           <option value="">--Order Group--</option>
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
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer/Party</label>
                        <select name="Ac_code" class="form-select" id="Ac_code" required onchange="getSeasonList(this.value); getBrandList(this.value);">
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $BuyerPurchaseOrderMasterList->Ac_code ? 'selected="selected"' : '' }}    
                           >{{ $row->ac_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="brand_id" class="form-label">Buyer Brand</label>
                        <select name="brand_id" class="form-select" id="brand_id" required>
                           <option value="">--Brands--</option>
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
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="season_id" class="form-label">Season</label>
                        <select name="season_id" class="form-select" id="season_id" required>
                           <option value="">--Season--</option>
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
                        <label for="order_received_date" class="form-label">Received Date</label>
                        <input type="date" name="order_received_date" class="form-control" id="order_received_date" value="{{$BuyerPurchaseOrderMasterList->order_received_date}}" required>
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
                        <label for="currency_id" class="form-label">Order currency</label>
                        <select name="currency_id" class="form-select" id="currency_id" required>
                           <option value="">--Currency--</option>
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
                        <input type="number" step="any" name="inr_rate" class="form-control" id="inr_rate" value="{{$BuyerPurchaseOrderMasterList->inr_rate}}" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" step="any" name="exchange_rate" class="form-control" id="exchange_rate" value="{{$BuyerPurchaseOrderMasterList->exchange_rate}}" required onkeyup="calOrderRate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_rate" class="form-label">Order Rate  (INR)</label>
                        <input type="number" step="any" name="order_rate" class="form-control" id="order_rate" value="{{$BuyerPurchaseOrderMasterList->order_rate}}" required readOnly>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_value" class="form-label">Order Value</label>
                        <input type="text" name="order_value" class="form-control" id="order_value" value="{{$BuyerPurchaseOrderMasterList->order_value}}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="total_qty" class="form-label">Total Qty</label>
                        <input type="number" step="any"  name="total_qty" class="form-control" id="total_qty" value="{{ $BuyerPurchaseOrderMasterList->total_qty }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-select select2" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
                           <option value="">--Main Style--</option>
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
                        <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-select select2" id="substyle_id" onchange="getStyle(this.value)" required>
                           <option value="">--Sub Style--</option>
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
                           <option value="">--Select Style--</option>
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
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{ $BuyerPurchaseOrderMasterList->style_no }}" required>
                     </div>
                  </div>
                  <div class="col-md-4">
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
                        <label for="style_pic_path" class="form-label">Style Image</label>
                        <input type="file" name="style_pic_path" class="form-control" id="style_pic_path"  >
                        <input type="hidden" name="style_pic_pathold" class="form-control" id="style_pic_pathold" value="{{ $BuyerPurchaseOrderMasterList->style_img_path }}"  >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Preview: </label>
                        @if($BuyerPurchaseOrderMasterList->style_img_path!='') 
                        <a href="{{url('images/'.$BuyerPurchaseOrderMasterList->style_img_path)}}" target="_blank"><img  src="{{url('thumbnail/'.$BuyerPurchaseOrderMasterList->style_img_path)}}" height="60" width="50" > </a>
                        @else
                        <label for="NoImage" class="form-label">No Item Image</label>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ptm_id" class="form-label">Payment Terms</label>
                        <select name="ptm_id" class="form-select" id="ptm_id" required>
                           <option value="">--Payment Terms--</option>
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
                        <label for="dterm_id" class="form-label">Delivery Terms</label>
                        <select name="dterm_id" class="form-select" id="dterm_id" required>
                           <option value="">--Delivery Terms--</option>
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
                           <option value="">--Shipment--</option>
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
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="country_id" class="form-label">Country</label>
                        <select name="country_id" class="form-select" id="country_id" required>
                           <option value="">--Country--</option>
                           @foreach($CountryList as  $row)
                           {
                           <option value="{{ $row->c_id }}"
                           {{ $row->c_id == $BuyerPurchaseOrderMasterList->country_id ? 'selected="selected"' : '' }}
                           >{{ $row->c_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="warehouse_id" class="form-label">Destination</label>
                        <select name="warehouse_id" class="form-select" id="warehouse_id" required>
                           <option value="">--Destination--</option>
                           @foreach($WarehouseList as  $row)
                           {
                           <option value="{{ $row->warehouse_id }}"
                           {{ $row->warehouse_id == $BuyerPurchaseOrderMasterList->warehouse_id ? 'selected="selected"' : '' }}
                           >{{ $row->warehouse_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">Shipment Date</label>
                        <input type="date" name="shipment_date" class="form-control" id="shipment_date" value="{{ $BuyerPurchaseOrderMasterList->shipment_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="plan_cut_date" class="form-label">Plan Cut Date</label>
                        <input type="date" name="plan_cut_date" class="form-control" id="plan_cut_date" value="{{ $BuyerPurchaseOrderMasterList->plan_cut_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="inspection_date" class="form-label">Inspection Date</label> 
                        <input type="date" name="inspection_date" class="form-control" id="inspection_date" value="{{ $BuyerPurchaseOrderMasterList->inspection_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="ex_factory_date" class="form-label">Ex Factory Date</label>
                        <input type="date" name="ex_factory_date" class="form-control" id="ex_factory_date" value="{{ $BuyerPurchaseOrderMasterList->ex_factory_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">From TNA Date</label>
                        <input type="date" name="from_tna_date" class="form-control" id="from_tna_date" value="{{ $BuyerPurchaseOrderMasterList->from_tna_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_received_date" class="form-label">To TNA Date</label>
                        <input type="date" name="to_tna_date" class="form-control" id="to_tna_date" value="{{ $BuyerPurchaseOrderMasterList->to_tna_date }}" required>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="sz_code" class="form-label">Select Size Group</label>
                        <select name="sz_code" class="form-select select2"   id="sz_code"   required onChange="GetSizeDetailList(this.value);">
                           <option value="">--Size--</option>
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
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="order_type" class="form-label">Order Type</label>
                        <select name="order_type" class="form-select select2"   id="order_type" required>
                           <option value="">--Select--</option>
                           <option value="1" {{ $BuyerPurchaseOrderMasterList->order_type == 1 ? 'selected="selected"' : '' }}>Fresh</option>
                           <option value="2"  {{ $BuyerPurchaseOrderMasterList->order_type == 2 ? 'selected="selected"' : '' }}>Stock</option>
                           <option value="3" {{ $BuyerPurchaseOrderMasterList->order_type == 3 ? 'selected="selected"' : '' }}>Job Work</option>
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
                              <tr>
                                 <th>SrNo</th>
                                 <th>Item Code</th>
                                 <th>Fabric Color Code</th>
                                 <th>Garment Color</th>
                                 @foreach($SizeDetailList as $sz) 
                                 <th>{{ $sz->size_name }}</th>
                                 @endforeach
                                 <th>Total Qty</th>
                                 <th>UOM</th>
                                 <th>Shipment Allowance %</th>
                                 <th>Adjust Qty</th>
                                 <th>Remark</th>
                                 @php  if(Session::get('user_type')==1 ||  $BOMCheck == 0){   @endphp
                                 <th>Add/Remove</th>
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
                           <th></th>
                        </tr>
                     <tbody> 
                  </table>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label"><a href="{{ url('uploads/'.$BuyerPurchaseOrderMasterList->buyer_document_path) }}" target="_blank" >PO Document</a></label>
                        <input type="file" name="doc_path1" class="form-control" id="doc_path1" > 
                        <input type="hidden" name="doc_path1old" class="form-control" id="doc_path1old" value="{{$BuyerPurchaseOrderMasterList->buyer_document_path}}" >
                     </div>
                  </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="tech_pack" class="form-label"><a href="{{ url('uploads/'.$BuyerPurchaseOrderMasterList->tech_pack) }}" target="_blank" >TECH PACK</a></label>
                        <input type="file" name="tech_pack" class="form-control" id="tech_pack" value="">
                         <input type="hidden" name="tech_pack_old" class="form-control" id="tech_pack_old" value="{{$BuyerPurchaseOrderMasterList->tech_pack}}" >
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="doc_path1" class="form-label"><a href="{{ url('uploads/'.$BuyerPurchaseOrderMasterList->measurement_sheet) }}" target="_blank" >Measurement sheet</a></label>
                        <input type="file" name="measurement_sheet" class="form-control" id="measurement_sheet" value="">
                         <input type="hidden" name="measurement_sheet_old" class="form-control" id="measurement_sheet_old" value="{{$BuyerPurchaseOrderMasterList->measurement_sheet}}" >
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="fit_pp_comments" class="form-label"><a href="{{ url('uploads/'.$BuyerPurchaseOrderMasterList->fit_pp_comments) }}" target="_blank" >FIT/PP comments</a></label>
                        <input type="file" name="fit_pp_comments" class="form-control" id="fit_pp_comments" value="">
                         <input type="hidden" name="fit_pp_comments_old" class="form-control" id="fit_pp_comments_old" value="{{$BuyerPurchaseOrderMasterList->fit_pp_comments}}" >
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="approved_fabric_trim" class="form-label"> <a href="{{ url('uploads/'.$BuyerPurchaseOrderMasterList->approved_fabric_trim) }}" target="_blank" > Approved Fabric/Trim</a></label>
                        <input type="file" name="approved_fabric_trim" class="form-control" id="approved_fabric_trim" value="">
                         <input type="hidden" name="approved_fabric_trim_old" class="form-control" id="approved_fabric_trim_old" value="{{$BuyerPurchaseOrderMasterList->approved_fabric_trim}}" >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="unit_id" class="form-label">UOM</label>
                        <select name="unit_ids"  id="unit_ids" class="form-select"   required>
                           <option value="">--Unit--</option>
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
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="shipped_qty" class="form-label">Shipped Qty</label>
                        <input type="number" name="shipped_qty" class="form-control" id="shipped_qty" value="{{ $ShippedQty[0]->ShippedQty }}" required readOnly onkeyup="calculate();">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="balance_qty" class="form-label">Balance Qty</label>
                        <input type="number" name="balance_qty" class="form-control" id="balance_qty" value="{{ $BuyerPurchaseOrderMasterList->balance_qty }}" required readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="job_status_id" class="form-label">PO Status</label>
                        <select name="job_status_id" class="form-select" id="job_status_id" required onchange="CheckOpenWorkProcessOrders(this.value);">
                           <option value="">--PO Status--</option>
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
                        <input type="date" name="order_close_date" class="form-control" id="order_close_date" value="{{$BuyerPurchaseOrderMasterList->order_close_date}}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="merchant_id" class="form-label">Bulk Merchant</label>
                        <select name="merchant_id" class="form-select" id="merchant_id" required>
                           <option value="">--Bulk Merchant--</option>
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
                           <option value="">--PD Merchant--</option>
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
                  <div class="col-sm-6">
                     <label for="formrow-inputState" class="form-label">Order Remark / Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{ $BuyerPurchaseOrderMasterList->narration }}" />
                     </div>
                  </div>
                  <div class="col-sm-6">
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
</div>
<!-- end row -->
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- end row -->
<script>
    $('.select2').select2();
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
        var size_count = $("#size_count").val();
      
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('EditDetailData') }}",
          data:{tr_code:tr_code},
          beforeSend: function() {
             $('#loadingImg').show();
             $('table #buyerData').removeClass('show').addClass('hide');
          },
          complete: function()
          {
                 $('#loadingImg').hide();
                 $('table #buyerData').removeClass('hide').addClass('show');
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
                
                $('#s'+i+'total').val(size_total);
            }
          }
        });  
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
        
        if(job_status_id!=5)
       {
           var tr_code = $('#tr_code').val();
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('CheckOpenWorkProcessOrders') }}",
           data:{sales_order_no:tr_code},
           success: function(response){
           console.log(response);     
           var workorders= response[0]['workorders'];
           var processorders=response[0]['processorders'];
           var count=workorders + processorders;
           if(count>0)
           {
               alert("This PO can't be Closed, As  ("+workorders+") Work Orders & ("+processorders+") Process Orders are Still in Open Status.");
               $('#job_status_id').val(1);
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
   
   window.onload = function() {
     mycalc();
     @php  $user_type=Session::get('user_type');    if(Session::get('user_type')!=1 && $BOMCheck>0 || $is_approved==1) {   @endphp
     $("input").prop("readonly", false);
     //$("select").select2('destroy');
    
     @php     }   @endphp
   };
   
   function EnableFields()
   {
        $("input").prop("readonly", false);
     //$("select").prop("disabled", false);
     
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
           $('select[name="item_code[]"]').select2();
           $('select[name="color_id[]"]').select2();
       
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
   
   
   
   function deleteRowcone(btn) {
   if(document.getElementById('cntrr').value > 1){
   var row = btn.parentNode.parentNode;
   row.parentNode.removeChild(row);
   
   document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
   
   recalcIdcone();
   
   if($("#cntrr").val()<=0)
   {		
   document.getElementById('Submit').disabled=true;
   }
    
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