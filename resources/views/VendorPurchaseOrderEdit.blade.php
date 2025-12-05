@extends('layouts.master') 
@section('content')
<style>
    .hide
    {
        display:none;
    } 
    .navbar-header {
        float: none!important;
    }

   #page-topbar {
      position: fixed;
      /* left: -17px; */
   }

   .btn
   {
      background-image: unset !important;
   }

   .panel-heading
   {
         display: flex;
         justify-content: space-between;   /* left + right alignment */
         align-items: center;               /* vertical center */
         padding: 8px 10px;
         background: #f5f5f5;
         border-radius: 4px;
   }
 
    .navbar-brand-box
    {
        width: 251px !important;
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
            <h4 class="card-title mb-4">Vendor Process Order</h4>
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
            @if(isset($VendorPurchaseOrderMasterList))
          
            <form action="{{ route('VendorPurchaseOrder.update',$VendorPurchaseOrderMasterList) }}" method="POST" enctype="multipart/form-data" id="frmData">
               @method('put')
               @csrf
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vpo_date" class="form-label">PO Date</label>
                        <input type="date" name="vpo_date" class="form-control" id="vpo_date" value="{{$VendorPurchaseOrderMasterList->vpo_date}}" readOnly>
                        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $VendorPurchaseOrderMasterList->c_code }}">
                        <input type="hidden" name="vpo_code" class="form-control" id="vpo_code" value="{{$VendorPurchaseOrderMasterList->vpo_code}}" readOnly>
                        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
                     </div>
                  </div>
                  <input type="hidden" name="cost_type_id" value="0" > 
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="process_id" class="form-label">Process Type</label>
                        <select name="process_id" class="form-control" id="process_id" disabled>
                           <option value="">--Select Process--</option>
                           @foreach($ProcessList as  $row)
                           {
                           <option value="{{ $row->process_id }}"
                           {{ $row->process_id == $VendorPurchaseOrderMasterList->process_id ? 'selected="selected"' : '' }}  
                           >{{ $row->process_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="po_date" class="form-label">Sales Order no</label>
                        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" onChange="getSalesOrderDetails(this.value);" disabled>
                           <option value="">--Sales Order No--</option>
                           @foreach($SalesOrderList as  $row)
                           {
                           <option value="{{ $row->sales_order_no }}"
                           {{ $row->sales_order_no == $VendorPurchaseOrderMasterList->sales_order_no ? 'selected="selected"' : '' }} 
                           >{{ $row->sales_order_no }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Buyer</label>
                        <select name="Ac_code" class="form-control" id="Ac_code" disabled>
                           <option value="">--Select Buyer--</option>
                           @foreach($Ledger as  $row)
                           {
                           <option value="{{ $row->ac_code }}"
                           {{ $row->ac_code == $VendorPurchaseOrderMasterList->Ac_code ? 'selected="selected"' : '' }} 
                           >{{ $row->ac_short_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <input type="hidden" name="season_id" class="form-control" id="season_id" value="{{$VendorPurchaseOrderMasterList->season_id}}" readOnly>
                  <input type="hidden" name="currency_id" class="form-control" id="currency_id" value="{{$VendorPurchaseOrderMasterList->currency_id}}" readOnly>
                  <input type="hidden" name="order_rate" class="form-control" id="order_rate" value="{{$VendorPurchaseOrderMasterList->order_rate}}" readOnly>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Main Style Category</label>
                        <select name="mainstyle_id" class="form-control " id="mainstyle_id"  onchange="getSubStyle(this.value)" disabled>
                           <option value="">--Main Style--</option>
                           @foreach($MainStyleList as  $row)
                           {
                           <option value="{{ $row->mainstyle_id }}"
                           {{ $row->mainstyle_id == $VendorPurchaseOrderMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
                           >{{ $row->mainstyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-inputState" class="form-label">Sub Style Category</label>
                        <select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" disabled>
                           <option value="">--Sub Style--</option>
                           @foreach($SubStyleList as  $row)
                           {
                           <option value="{{ $row->substyle_id }}"
                           {{ $row->substyle_id == $VendorPurchaseOrderMasterList->substyle_id ? 'selected="selected"' : '' }}
                           >{{ $row->substyle_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fg_id" class="form-label">Style Name</label>
                        <select name="fg_id" class="form-control" id="fg_id" required disabled>
                           <option value="">--Select Style--</option>
                           @foreach($FGList as  $row)
                           {
                           <option value="{{ $row->fg_id }}"
                           {{ $row->fg_id == $VendorPurchaseOrderMasterList->fg_id ? 'selected="selected"' : '' }} 
                           >{{ $row->fg_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="formrow-email-input" class="form-label">Style No</label>
                        <input type="text" name="style_no" class="form-control" id="style_no" value="{{$VendorPurchaseOrderMasterList->style_no}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="style_description" class="form-label">Style Description</label>
                        <input type="text" name="style_description" class="form-control" id="style_description" value="{{$VendorPurchaseOrderMasterList->style_description}}" readOnly>
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vpo_date" class="form-label">PO Delivery Date</label>
                        <input type="date" name="delivery_date" class="form-control" id="delivery_date" value="{{$VendorPurchaseOrderMasterList->delivery_date}}" required  >
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="vendorId" class="form-label">Vendor Name</label>
                        <select name="vendorId" class="form-control select2" id="vendorId" required onchange="GetLineList();" >
                           <option value="">--Select Vendor--</option>
                           @foreach($Ledger2 as  $rowvendor)
                           <option value="{{ $rowvendor->ac_code }}"
                                {{  $rowvendor->ac_code == $VendorPurchaseOrderMasterList->vendorId ? 'selected="selected"' : '' }}>{{ $rowvendor->ac_short_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  @if($VendorPurchaseOrderMasterList->process_id == 1)
                  <div class="col-md-2" id="line_div">
                     <div class="mb-3">
                        <label for="line_id" class="form-label">Line</label>
                        <select name="line_id" class="form-control select2" id="line_id">
                           <option value="">--Select--</option>
                           @foreach($LineList as  $rowline)
                           <option value="{{ $rowline->line_id }}"
                                {{  $rowline->line_id == $VendorPurchaseOrderMasterList->line_id ? 'selected="selected"' : '' }}>{{ $rowline->line_name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  @endif
               </div>
               <div class="row">
                  <div class="">
                     <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h4 class="panel-title">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapsex">Cutting/Finishing/Packing GRN Against Work Order Qty</a>
                              </h4>
                           </div>
                           <div id="collapsex" class="panel-collapse collapse in">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_6" class="table  table-bordered table-striped m-b-0  footable_6">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Color</th>
                                                   @foreach ($SizeDetailList as $sz) 
                                                   <th>{{$sz->size_name}}</th>
                                                   @endforeach
                                                   <th>Total Qty</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @php   $no=1;  @endphp
                                                @if(!empty($VendorProcessDataList) && count($VendorProcessDataList))
                                                @foreach ($VendorProcessDataList as $row) 
                                                <tr>
                                                   <td>{{$no}}</td>
                                                   <td>{{$row->color_name}}</td>
                                                   @if(isset($row->s1))  
                                                   <td>{{$row->s1}}</td>
                                                   @endif
                                                   @if(isset($row->s2)) 
                                                   <td>{{$row->s2}}</td>
                                                   @endif
                                                   @if(isset($row->s3)) 
                                                   <td>{{$row->s3}}</td>
                                                   @endif
                                                   @if(isset($row->s4)) 
                                                   <td>{{$row->s4}}</td>
                                                   @endif
                                                   @if(isset($row->s5)) 
                                                   <td>{{$row->s5}}</td>
                                                   @endif
                                                   @if(isset($row->s6)) 
                                                   <td>{{$row->s6}}</td>
                                                   @endif
                                                   @if(isset($row->s7)) 
                                                   <td>{{$row->s7}}</td>
                                                   @endif
                                                   @if(isset($row->s8)) 
                                                   <td>{{$row->s8}}</td>
                                                   @endif
                                                   @if(isset($row->s9)) 
                                                   <td>{{$row->s9}}</td>
                                                   @endif
                                                   @if(isset($row->s10)) 
                                                   <td>{{$row->s10}}</td>
                                                   @endif
                                                   @if(isset($row->s11)) 
                                                   <td>{{$row->s11}}</td>
                                                   @endif
                                                   @if(isset($row->s12)) 
                                                   <td>{{$row->s12}}</td>
                                                   @endif
                                                   @if(isset($row->s13)) 
                                                   <td>{{$row->s13}}</td>
                                                   @endif
                                                   @if(isset($row->s14)) 
                                                   <td>{{$row->s14}}</td>
                                                   @endif
                                                   @if(isset($row->s15)) 
                                                   <td>{{$row->s15}}</td>
                                                   @endif
                                                   @if(isset($row->s16)) 
                                                   <td>{{$row->s16}}</td>
                                                   @endif
                                                   @if(isset($row->s17)) 
                                                   <td>{{$row->s17}}</td>
                                                   @endif
                                                   @if(isset($row->s18)) 
                                                   <td>{{$row->s18}}</td>
                                                   @endif
                                                   @if(isset($row->s19)) 
                                                   <td>{{$row->s19}}</td>
                                                   @endif
                                                   @if(isset($row->s20))  
                                                   <td>{{$row->s20}}</td>
                                                   @endif
                                                   <td>{{$row->size_qty_total}}</td>
                                                </tr>
                                                @php $no=$no+1; @endphp
                                                @endforeach
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
                           <div class="panel-heading" style="display: flex;">
                                 <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Order Qty</a>
                                 </h4> 
                                 <h4 class="panel-title">
                                    <input type="button" class="size_btn btn-primary" id="MBtn" is_click="0" value="Calculate All" onclick="MainBtn();this.disabled=true;">
                                 </h4> 
                           </div>
                           <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Color</th>
                                                   @foreach ($SizeDetailList as $sz) 
                                                   <th>{{$sz->size_name}}</th>
                                                   @endforeach
                                                   <th>Total Qty</th> 
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @if(count($VendorPurchaseOrderDetailList)>0)
                                                @php $no=1;$n1=0; $sumAllTotal=0; @endphp
                                                @foreach($VendorPurchaseOrderDetailList as $List) 
                                                <tr>
                                                   <td><input type="text" name="id" value="{{ $no }}" id="id" style="width:50px;"/></td>
                                                   <td>
                                                      <input type="hidden" name="item_codef[]" value="{{$List->item_code}}" id="item_codef"  />
                                                      <select name="color_id[]"   id="color_id" style="width:200px; height:30px;" disabled>
                                                         <option value="">--Color  List--</option>
                                                         @foreach($ColorList as  $row)
                                                         {
                                                         <option value="{{ $row->color_id }}"
                                                         {{ $row->color_id == $List->color_id ? 'selected="selected"' : '' }} 
                                                         >{{ $row->color_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   @php 
                                                   $n=1;   $SizeQtyList=explode(',', $List->size_qty_array)
                                                   @endphp
                                                   @foreach($SizeQtyList  as $key=>$szQty)
                                                   @php
                                                        $nos = $n++;
                                                        $sz = 's'.$nos;
                                                        $min = isset($VendorProcessDataList[$n1]->$sz) ? $VendorProcessDataList[$n1]->$sz : 0;
                                                   @endphp
                                                   <td ><input style="width:80px; float:left;" max="{{max($szQty, $min)}}" min="{{min($szQty, $min)}}" onchange="validateSize(this);" name="s{{ $nos }}[]" class="size_id" type="number" id="s1" value="{{$szQty}}" required /></td>
                                                   @php 
                                                        $sumAllTotal += $szQty;
                                                      
                                                   @endphp
                                                   @endforeach
                                                   <td><input type="number" name="size_qty_total[]" class="size_qty_total" value="{{$List->size_qty_total}}" id="size_qty_total" style="width:80px; height:30px; float:left;"  />
                                                      <input type="hidden" name="size_qty_array[]"  value="{{$List->size_qty_array}}" id="size_qty_array" style="width:80px; float:left;"  />
                                                      <input type="hidden" name="size_array[]"  value="{{$List->size_array}}" id="size_array" style="width:80px;  float:left;"  />
                                                   </td>
                                                </tr>
                                                @php    $n1=$n1+1; $no=$no+1;  @endphp
                                                @endforeach
                                                @endif
                                             </tbody>
                                          </table>
                                          <input type="hidden" name="allTotal" value="0,0,0,0,0" id="allTotal">
                                          <input type="hidden" name="sumAllTotal" value="{{isset($sumAllTotal) ? $sumAllTotal : 0}}" id="sumAllTotal">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @if($VendorPurchaseOrderMasterList->process_id == 1)
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
                                          <table id="footable_1" class="table  table-bordered table-striped m-b-0  footable_1">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Item Name</th>
                                                   <th>Item Code</th>
                                                   <th>Classification</th>
                                                   <th>Description</th>
                                                   <th>Cons(Mtr/Nos)</th>
                                                   <th>Unit</th>
                                                   <th>Wastage %</th>
                                                   <th>BOM Qty</th>
                                                </tr>
                                             </thead>
                                             <tbody id="FabricData">
                                                @if(count($FabricList)>0)
                                                @php $no=1; @endphp
                                                @foreach($FabricList as $List) 
                                                <tr>
                                                   <td><input type="text" name="id" value="{{ $no }}" id="id" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="item_code[]"   id="item_code" style="width:200px; height:30px;" required  @if($List->item_count>0) {{ 'disabled'}}  @endif>
                                                      <option value="">--Item List--</option>
                                                      @foreach($ItemList as  $row)
                                                      {
                                                      <option value="{{ $row->item_code }}"
                                                      {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }} 
                                                      >{{ $row->item_name }}</option>
                                                      }
                                                      @endforeach
                                                      </select>
                                                   </td>
                                                   <td><input type="text" name="item_code[]" value="{{$List->item_code}}" readOnly id="item_code" style="width:200px; height:30px;" /></td>
                                                   <td>
                                                      <select name="class_id[]"   id="class_id" style="width:200px; height:30px;" required @if($List->item_count>0){{'disabled'}} @endif>
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
                                                   <td><input type="text"    name="description[]" value="{{$List->description}}" readOnly id="description" style="width:200px; height:30px;" required /></td>
                                                   <td><input type="number" step="any"    name="consumption[]" value="{{round($List->consumption,2)}}" readOnly id="consumption" style="width:80px; height:30px;" required /></td>
                                                   <td>
                                                      <select name="unit_id[]" class="select2" id="unit_id" style="width:100px; height:30px;" required disabled>
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
                                                   @php
                                                         if(Session::get('user_type') == 1)
                                                         {
                                                            $mx = 500;    
                                                         }
                                                         else
                                                         {
                                                            $mx = 5;    
                                                         }
                                                   @endphp
                                                   <td><input type="number" max="{{$mx}}" step="any" min="0" class="WASTAGE" name="wastage[]" value="{{$List->wastage}}" id="wastage" style="width:80px; height:30px;" required @php if($List->item_count>0){ echo 'readOnly'; } @endphp  readonly/></td>
                                                   <td><input type="text" name="bom_qty[]" value="{{$List->bom_qty}}" id="bom_qty" style="width:80px; height:30px;" required readOnly/></td>
                                                      <input type="hidden"  name="bom_qty1[]" value="{{$List->actual_qty}}" id="bom_qty1" style="width:80px; height:30px;" required readOnly/>
                                                      <input type="hidden"  name="final_cons[]" value="{{$List->final_cons}}" id="final_cons'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                      <input type="hidden"  name="size_qty[]" value="{{$List->size_qty}}" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                </tr>
                                                @php $no=$no+1;  @endphp
                                                @endforeach
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
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapse5">Trim Fabric: </a>
                              </h4>
                           </div>
                           <div id="collapse5" class="panel-collapse collapse">
                              <div class="panel-body">
                                 <div class="row">
                                    <input type="number" value="1" name="cntrr5" id="cntrr5" readonly="" hidden="true"  />
                                    <div class="table-wrap">
                                       <div class="table-responsive">
                                          <table id="footable_5" class="table  table-bordered table-striped m-b-0  footable_5">
                                             <thead>
                                                <tr>
                                                   <th>SrNo</th>
                                                   <th>Item Name</th>
                                                   <th>Classification</th>
                                                   <th>Size</th>
                                                   <th>Description</th>
                                                   <th>Cons(Mtr/Nos)</th>
                                                   <th>Unit</th>
                                                   <th>Wastage %</th>
                                                   <th>BOM Qty</th>
                                                </tr>
                                             </thead>
                                                
                                             <tbody id="SewingData">
                                                @if(!empty($TrimFabricList)) 
                                                @php $no=1;  @endphp
                                                @foreach($TrimFabricList as $List)  
                                                @php
                                                $SizeListFromBOM=DB::select("select size_array from bom_trim_fabric_details where sales_order_no='".$List->sales_order_no."' and item_code='".$List->item_code."' limit 0,1");
                                                $size_ids = explode(',', isset($SizeListFromBOM[0]->size_array) ? $SizeListFromBOM[0]->size_array : ""); 
                                                $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                                                $sizes='';
                                                foreach($SizeDetailList as $sz)
                                                {
                                                $sizes=$sizes.$sz->size_name.', ';
                                                }
                                                @endphp
                                               
                                                <tr>
                                                   <td><input type="text" name="idsx" value="{{ $no }}" id="idsx" style="width:50px;" readOnly/></td>
                                                   <td>
                                                      <select name="item_codesx[]" class="item_trim_fabric" id="item_codesx" style="width:200px; height:30px;" required disabled>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList5 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >{{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td>
                                                      <select name="class_idsx[]"   id="class_idsx" style="width:200px; height:30px;" required disabled>
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
                                                   <td>{{rtrim($sizes,',');}}</td>
                                                   <td><input type="text"    name="descriptionsx[]" readOnly value="{{$List->description}}" id="descriptionsx" style="width:200px; height:30px;" required /></td>
                                                   <td><input type="number" step="any"  readOnly  name="consumptionsx[]"  value="{{$List->consumption}}" id="consumptionsx" style="width:80px; height:30px;" required /></td>
                                                   <td>
                                                      <select name="unit_idsx[]" class="select2" id="unit_idsx" style="width:100px; height:30px;" required disabled>
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
                                                   @php
                                                         if(Session::get('user_type') == 1)
                                                         {
                                                            $mx = 500;    
                                                         }
                                                         else
                                                         {
                                                            $mx = 5;    
                                                         }
                                                   @endphp
                                                   <td><input type="number" max="{{$mx}}" step="any" min="0" class="WASTAGE1"  name="wastagesx[]" value="{{$List->wastage}}" id="wastagesx" style="width:80px; height:30px;" required /></td>
                                                   <td><input type="text"     name="bom_qtysx[]" value="{{$List->bom_qty}}" id="bom_qtys" style="width:80px; height:30px;" required readOnly/></td>
                                                   <input type="hidden"      name="bom_qtysx1[]" value="{{$List->actual_qty}}" id="bom_qtysx1" style="width:80px; height:30px;" required readOnly/>
                                                   <input type="hidden"  name="final_conssx[]" value="{{$List->final_cons}}" id="final_conssx'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                   <input type="hidden"  name="size_qtysx[]" value="{{$List->size_qty}}" id="size_qtysx'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                </tr>   
                                                @php $no=$no+1;  @endphp
                                                @endforeach
                                                @endif
                                                
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @if($VendorPurchaseOrderMasterList->process_id == 3)
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
                                          <table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
                                             <thead>
                                                <tr>
                                                   <th>Sr No</th>
                                                   <th>Item Code</th>
                                                   <th>Item Name</th>
                                                   <th>Color</th>
                                                   <th>Sizes</th>
                                                   <th>Classification</th>
                                                   <th>Cons(Mtr/Nos)</th>
                                                   <th>Unit</th>
                                                   <th>Wastage %</th>
                                                   <th>BOM Qty</th>
                                                </tr>
                                             </thead>
                                             <tbody id="PackingData">
                                                @if(!empty($PackingTrimsList)) 
                                                @php $no=1; @endphp
                                                @foreach($PackingTrimsList as $List) 
                                                @php
                                                $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$List->sales_order_no."' and item_code='".$List->item_code."' limit 0,1");
                                                $sizes='';$colorspk='';
                                                $SizeListBOM = isset($SizeListFromBOM[0]->size_array) ? $SizeListFromBOM[0]->size_array : "";
                                                
                                                if(!empty($SizeListBOM))
                                                {
                                                    if(!empty($SizeListBOM))
                                                    {
                                                        $size_ids = explode(',', $SizeListBOM); 
                                                        $size_ids = is_array($size_ids) ? $size_ids : [$size_ids];
                                                        $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id', $size_ids)->get(['size_name']);

                                                        if(!empty($SizeDetailList))
                                                        {
                                                            foreach($SizeDetailList as $sz)
                                                            {
                                                                 $sizes=$sizes.$sz->size_name.', ';
                                                            }
                                                        }
                                                    }
                                                    $ColorListpacking= App\Models\BOMPackingTrimsDetailModel::select('color_id')->where('item_code', $List->item_code)->where('sales_order_no', $VendorPurchaseOrderMasterList->sales_order_no)->get();
                                                    
                                                    $ColorListpack = isset($ColorListpacking[0]->color_id) ? $ColorListpacking[0]->color_id : "";
                                                    
                                                    if(!empty($ColorListpack))
                                                    {
                                                         $colorids = explode(',', $ColorListpack);

                                                         $ColorListpacking = App\Models\VendorPurchaseOrderDetailModel::join(
                                                               'color_master',
                                                               'vendor_purchase_order_detail.color_id',
                                                               '=',
                                                               'color_master.color_id'
                                                            )
                                                            ->where('vendor_purchase_order_detail.sales_order_no', $VendorPurchaseOrderMasterList->sales_order_no)
                                                            ->where('vendor_purchase_order_detail.vpo_code', $VendorPurchaseOrderMasterList->vpo_code)
                                                            ->whereIn('vendor_purchase_order_detail.color_id', $colorids)
                                                            ->where('color_master.delflag', '=', '0')
                                                            ->distinct('color_master.color_id')
                                                            ->get(['color_name']);

                                                       
                                                        if(!empty($ColorListpacking))
                                                        {
                                                            foreach($ColorListpacking as $colorpk)
                                                            {
                                                                $colorspk=$colorspk.$colorpk->color_name.', ';
                                                            }
                                                        }
                                                    }
                                                }
                                                @endphp
                                                <tr>
                                                   <td><input type="text" name="idss" value="{{ $no }}" id="id" style="width:50px;" readOnly/></td>
                                                   <td><input type="text" value="{{$List->item_code}}"  style="width:100px;" readOnly/></td>
                                                   <td>
                                                      <select name="item_codess[]" class="item_packing_trims" id="item_codess" style="width:200px; height:30px;" required disabled>
                                                         <option value="">--Item List--</option>
                                                         @foreach($ItemList3 as  $row)
                                                         {
                                                         <option value="{{ $row->item_code }}"
                                                         {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}
                                                         >{{ $row->item_name }}</option>
                                                         }
                                                         @endforeach
                                                      </select>
                                                   </td>
                                                   <td >{{rtrim($colorspk, ', ');}} </td>
                                                   <td >{{rtrim($sizes, ', ');}} </td>
                                                   <td>
                                                      <select name="class_idss[]"   id="class_idss" style="width:200px; height:30px;" required disabled>
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
                                                   <td><input type="number" step="any"    name="consumptionss[]" value="{{$List->consumption}}" id="consumptionss" style="width:80px; height:30px;" required readOnly/></td>
                                                   <td>
                                                      <select name="unit_idss[]" class="" id="unit_idss" style="width:100px; height:30px;" required disabled>
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
                                                   @php
                                                         if(Session::get('user_type') == 1)
                                                         {
                                                            $mx = 500;    
                                                         }
                                                         else
                                                         {
                                                            $mx = 5;    
                                                         }
                                                   @endphp
                                                   <td><input type="number" max="{{$mx}}" step="any" min="0" class="WASTAGE2"  name="wastagess[]" value="{{$List->wastage}}" id="wastagess" disabled onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" /></td>
                                                   <td><input type="text" name="bom_qtyss[]" value="{{$List->bom_qty}}" id="bom_qtyss" style="width:80px; height:30px;" required readOnly/></td>
                                                   <input type="hidden" name="bom_qtyss1[]" value="{{$List->actual_qty}}" id="bom_qtyss1" style="width:80px; height:30px;" required readOnly/>
                                                   <input type="hidden" name="final_consss[]" value="{{$List->final_cons}}" id="final_consss'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                   <input type="hidden" name="size_qtyss[]" value="{{$List->size_qty}}" id="size_qtyss'.$no.'" style="width:80px; height:30px;" required readOnly />
                                                </tr>
                                                @php $no=$no+1; @endphp 
                                                @endforeach
                                                @endif
                                             </tbody> 
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                     </div>
                  </div>
               </div>
               </br>
               </br>
               <!-- end row -->
               <div class="row">
                  <div class="col-sm-2">
                     <label for="formrow-inputState" class="form-label">Total Qty</label>
                     <div class="mb-3">
                        <input type="number" required step="any" name="final_bom_qty" class="form-control" id="final_bom_qty"  value="{{$VendorPurchaseOrderMasterList->final_bom_qty}}"  />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="endflag" class="form-label">Order Status</label>
                        <select name="endflag" class="form-control" id="endflag" required>
                           <option value="">--Order Status--</option>
                           @foreach($JobStatusList as  $row)
                           {
                           <option value="{{ $row->job_status_id }}"
                           {{ $row->job_status_id == $VendorPurchaseOrderMasterList->endflag ? 'selected="selected"' : '' }}     
                           >{{ $row->job_status_name }}</option>
                           }
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-4">
                     <label for="formrow-inputState" class="form-label">Narration</label>
                     <div class="mb-3">
                        <input type="text" name="narration" class="form-control" id="narration"  value="{{$VendorPurchaseOrderMasterList->narration}}" />
                     </div>
                  </div>
               </div>
               <div class="col-sm-6">
                  <label for="formrow-inputState" class="form-label"></label>
                  <div class="form-group" id="sub">
                     <button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit" disabled>Submit</button>
                     <a href="{{ Route('VendorPurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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

    

    function GetLineList()
    {
        $('#line_id').select2('destroy').hide().show();

        var process_id = $("#process_id").val();
        if(process_id == 1)
        {
            var vendorId = $("#vendorId").val();
            $.ajax({
               dataType: "json",
               url: "{{ route('GetLineList') }}",
               data:{'Ac_code':vendorId},
               success: function(data)
               {
                  $('#line_id').select2('destroy')
                  $("#line_div").removeClass("hide"); 
                  $("#line_id").html(data.html);  
                  $('#line_id').select2();
               }
            });
        }
        else
        {
             $("#line_div").addClass("hide"); 
        }

        $('#line_id').select2();
    }
    
    
    function validateSize(row) 
    {
        var minQty = $(row).attr('min');
        var maxQty = $(row).attr('max');
        var enteredQty = parseInt($(row).val());

        if (enteredQty < minQty) {
            alert("Minimum Quantity is " + minQty);
            $(row).val(minQty);
        }
        
        if (enteredQty >= maxQty) {
            alert("Maximum Quantity is " + maxQty);
            $(row).val(maxQty);
        }
    }
    
   function calculateBomWithWastage(row)
   {
       var wastage = $(row).parent().parent().find('td input[name="wastagess[]"]').val();
       var bom_qty = $(row).parent().parent().find('td input[name="bom_qtyss1[]"]').val(); 
       var bom_qty1 = $(row).parent().parent().find('td input[name="bom_qtyss[]"]').val(); 
       var max_qty = $(row).parent().parent().find('td input[name="wastagess[]"]').attr("max"); 
    //   if(wastage <= max_qty)
    //   { 
           var total_bom = parseFloat(bom_qty) + parseFloat(parseFloat(bom_qty) * (wastage/100));
           $(row).parent().parent().find('td input[name="bom_qtyss[]"]').val(total_bom);  
    //   }
    //   else
    //   {
    //       $(row).parent().parent().find('td input[name="bom_qtyss[]"]').val(bom_qty);  
    //   }
   }
      
   function calculateBomWithWastage1(row)
   {
       var wastage = $(row).parent().parent().find('td input[name="wastage[]"]').val();
       var bom_qty = $(row).parent().parent().find('td input[name="bom_qty1[]"]').val(); 
     
       var max_qty = $(row).parent().parent().find('td input[name="wastage[]"]').attr("max"); 
    //   if(wastage <= max_qty)
    //   { 
           var total_bom = parseFloat(bom_qty) + parseFloat(parseFloat(bom_qty) * (wastage/100));
           $(row).parent().parent().find('td input[name="bom_qty[]"]').val(total_bom);  
    //   }
    //   else
    //   {
    //       $(row).parent().parent().find('td input[name="bom_qty[]"]').val(bom_qty);  
    //   }
   }
   
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
      
    //$('#sub').hover(function()
    //{
    //    var is_click = $("#MBtn").attr('is_click');
    //    if(is_click == 0)
    //    {
    //        alert("Please click on Calculate All Button...!");
     //   }
   // });
    
   window.onload = function() 
   {
     mycalc();
   }; 
   
 $(document).on("change", 'input[class^="size_id"],input[name^="vendorRate"],input[class^="WASTAGE"],input[class^="WASTAGE1"],input[class^="WASTAGE2"]', function (event) 
   {
        var cls = $(this).attr('class');
        if(cls == 'size_id')
        {
            $("#Submit").attr("disabled", true);
        }
        var value = $(this).val();

        var maxLength = parseInt($(this).attr('max'));
        var minLength = parseInt($(this).attr('min')); 
        if(value>maxLength){alert('Value can not be greater than '+maxLength);}
        if ((value !== '') && (value.indexOf('.') === -1)) {
                  
           $(this).val(Math.max(Math.min(value, maxLength), minLength));
           //calculateBomWithWastage($(this));
        }
       
        var total = 0;
        $(this).parent().parent('tr').find('td input[class^="size_id"]').each(function()
        {
           total += parseFloat($(this).val());
        });
        $(this).parent().parent('tr').find('td input[name="size_qty_total[]"]').val(total); 
        var total = 0;
        var name = $(this).attr('name'); 
        var index = parseFloat(name.match(/\d+/)); 
        
        $('input[name="' + name + '"]').each(function() {
            total += parseFloat($(this).val()) || 0; 
        });  
        
        var allTotalArray = $(this).parent().parent('tr').find('td input[name="size_qty_array[]"]').val().split(',');
        allTotalArray[index-1] = total.toString(); 
        $("#allTotal").val(allTotalArray.join(','));
        var sum = 0;
        $(".size_qty_total").each(function() 
        {
            sum += parseFloat($(this).val());
        });
        
        
        $("#sumAllTotal").val(sum);
        
        mycalc();
      
   });
   
     
   
    function MainBtn()
    {  
        setTimeout(() => {
            document.getElementById("MBtn").disabled = false;
        }, 3000);
        
        $('.size_id').each(function()
        {
            var total = 0;
            var name = $(this).attr('name'); 
            var index = parseFloat(name.match(/\d+/)); 
         
            $('input[name="' + name + '"]').each(function() {
                total += parseFloat($(this).val()) || 0; 
            });  
            
            var allTotalArray = $("#allTotal").val().split(',');
            allTotalArray[index-1] = total.toString(); 
            $("#allTotal").val(allTotalArray.join(','));
          
        });
        
        $("#PackingData").empty(); 
        var process_id = $('#process_id').val();
        var values = [];
         $("#footable_2 tr td  input[class='size_id']").each(function() {
            var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
            var size_array = sizes.split(',');
         values.push($(this).val());
         if(values.length==size_array.length)
         {
           $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
           // alert(values);
               var sum = values.reduce(function( a,  b){
                   return parseInt(a) + parseInt(b);
               }, 0);
           $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
            
               values = [];
         }
          
        });
        if(process_id == 3)
        {
               $("#footable_2 tbody").find('tr').each(function(){
                    var row = $(this).find('td input[name="size_btn"]'); 
                    GetSizeWiseQty(this);
               }); 
        }
        else
        {
            
            $("#footable_2 tbody").find('tr').each(function() 
            { 
                var size_qty_total = $(this).find('input[name="size_qty_total[]"]').val();
                
                if (size_qty_total > 0) 
                {
                    GetSizeWiseQty(this,size_qty_total);
                }
            });
        }
   }
   
    function removeDuplicateRows() 
    {  
    
        var seen = {};
        $('#footable_1 tbody tr').each(function() {
                var row = $(this);
                // Get values of item name, color, and size
                var itemName = row.find('td select[name="item_codess[]"] option:selected').text();
                var color = row.find('td select[name="color_ids[]"] option:selected').text();
                var size = row.find('td input[name="sizes_ids[]"]').val();
                // Get selected value from dropdown
                var selectedValue = row.find('select').val();
                // Create a unique key based on the combination of values
                var key = itemName + '-' + color + '-' + size + '-' + selectedValue;
                if (seen.hasOwnProperty(key)) {
                    row.remove();
                } else {
                    seen[key] = true;
                }
        });
         
    }

 
    function removeDuplicateRows1() 
    {   
        var seen1 = {};
        $('#footable_5 tbody tr').each(function() {
                var row = $(this);
                // Get values of item name, color, and size
                var itemName = row.find('td select[name="item_codess[]"] option:selected').text();
                var color = row.find('td select[name="color_ids[]"] option:selected').text();
                var size = row.find('td input[name="sizes_ids[]"]').val();
                // Get selected value from dropdown
                var selectedValue = row.find('select').val();
                // Create a unique key based on the combination of values
                var key = itemName + '-' + color + '-' + size + '-' + selectedValue;
                if (seen1.hasOwnProperty(key)) {
                    row.remove();
                } else {
                    seen1[key] = true;
                }

                CalculateQtyRowProsx(row);
        });
    }
    
   function GetSizeWiseQty(row) 
   {
      var no=1;
      
       var process_id = $('#process_id').val();
       var sales_order_no = $('#sales_order_no').val();
       
       if(process_id != 4 && process_id != 3)
       {

           $("#TrimFabricData tr").remove(); 
           $("#PackingData tr").remove(); 
           $("#FabricData tr").remove(); 
           $("#footable_2 tr td  input[class='size_qty_total']").each(function() {
             var color_id=$(this).closest("tr").find('select[name="color_id[]"]').val();
            // alert(color_id);
             var size_qty_total=$(this).closest("tr").find('input[name="size_qty_total[]"]').val();
             if(size_qty_total!=0 && process_id!=4 )
             {
               $.ajax({
               dataType: "json",
               url: "{{ route('GetFabricConsumptionPO') }}",
               data:{'color_id':color_id,'size_qty_total':size_qty_total,'sales_order_no':sales_order_no,'no':no},
               success: function(data){
                //$("#footable_2").html(data.html);
                $("#FabricData").append(data.html);
                  setTimeout(removeDuplicateRows, 500);

               }
               });
                 
               var size_qty_array=$(row).closest("tr").find('input[name="size_qty_array[]"]').val();
               var size_array=$(row).closest("tr").find('input[name="size_array[]"]').val();
               
               $.ajax({
               dataType: "json",
               url: "{{ route('GetTrimsConsumptionPO') }}",
               data:{'color_id':color_id,'size_qty_total':size_qty_total,'sales_order_no':sales_order_no,'no':no,'size_qty_array':size_qty_array,'size_array':size_array},
               success: function(data){
                
                 $("#TrimFabricData").append(data.html);
                 setTimeout(removeDuplicateRows1, 500); 

               }
               });
               
               
               
             }
             no=no+1;
           });
           
           mycalc();
       }   
       else if(process_id == 3)
       {
               var size_qty_total=0; 
               var size_array=$("#footable_2 > tbody > tr").find('input[name^="size_array[]"]').val(); 
               var color_id= $(row).find('td select[name^="color_id[]"]').val();
               var allTotal=$("#allTotal").val();
               var sumAllTotal=$("#sumAllTotal").val();
               var values = [];
               $("#footable_2 tr td  input[class='size_qty_total']").each(function() 
               {
                    size_qty_total += parseFloat($(this).val());
                       
               });
               var color_ids = [];
                
                $("#footable_2 tr td select[name='color_id[]']").each(function() {
                    var val = $(this).val();
                    if (val) 
                    {  
                        color_ids.push(val);
                    }
               });
                
               color_ids = color_ids.join(",");

               var size_qty_array = $(row).find('td input[name^="size_qty_array[]"]').val(); 

               var tbl_len = $('#footable_2 tbody tr').filter(function() {
                  var val = parseFloat($(this).find('input[name="size_qty_total[]"]').val()) || 0;
                  return val > 0;
               }).length;

               
              $.ajax({
                  dataType: "json",
                  url: "{{ route('GetPurchasePackingCreateConsumption') }}",
                  data: {
                     'tbl_len': tbl_len,
                     'color_id': color_id,
                     'size_qty_total': size_qty_total,
                     'sales_order_no': sales_order_no,
                     'no': no,
                     'size_qty_array': size_qty_array,
                     'size_array': size_array,
                     'allTotal': allTotal,
                     'sumAllTotal': sumAllTotal,
                     'color_ids': color_ids
                  },
                  success: function (data) 
                  {
                     $("#PackingData").append(data.html);

                     var itemCodeMap = {};

                     $('#footable_4 tbody tr').each(function () {
                           var row = $(this);
                           var itemCode = row.find('td input').eq(1).val();
                           var bomQty = row.find('td input[name="bom_qtyss[]"]').val() || 0;
                           var colorName = row.find('td input[name="color_ids[]"]').val();
                           var sizeIds = row.find('td input[name="sizes_ids[]"]').val();

                           console.log("bomQty=>"+bomQty);
                           // Skip rows with zero BOM quantity
                           if (bomQty <= 0) {
                              row.remove();
                              return; // move to next iteration
                           }

                           if (itemCodeMap[itemCode]) {
                              // If duplicate itemCode
                              if (!sizeIds.includes(",")) {
                                 itemCodeMap[itemCode].bomTotal += bomQty;
                              }

                              if (!itemCodeMap[itemCode].colors.includes(colorName)) {
                                 itemCodeMap[itemCode].colors.push(colorName);
                              }

                              row.remove();
                           } else {
                              // New itemCode entry
                              itemCodeMap[itemCode] = {
                                 row: row,
                                 bomTotal: bomQty,
                                 colors: [colorName],
                                 sizeIds: sizeIds
                              };
                           }
                     });
 
                     // Update merged rows with totals and colors
                     $.each(itemCodeMap, function (itemCode, data) {
                           const input = data.row.find('td input[name="bom_qtyss[]"]');
                           input.val(data.bomTotal).attr('value', data.bomTotal).trigger('change');

                           let input1 = data.row.find('td input[name="bom_qtyss1[]"]');
                           input1.val(data.bomTotal).attr('value', data.bomTotal).trigger('change');

                           data.row.find('td input[name="color_ids[]"]').val(data.colors.join(", "));
                     });

                     // Enable wastage inputs
                     $('#footable_4 tbody').find('td input[name="wastagess[]"]').each(function () {
                           $(this).removeAttr('disabled');
                     });

                     recalcIdcone4();
                  }
               });
                mycalc();
       }
       
       $("#Submit").removeAttr('disabled');
   }
   
   
   function getSalesOrderDetails(sales_order_no)
   {
   
         $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('SalesOrderDetails') }}",
               data:{'sales_order_no':sales_order_no},
               success: function(data){
               
               $("#season_id").val(data[0]['season_id']);
               $("#Ac_code").val(data[0]['Ac_code']);
                $("#currency_id").val(data[0]['currency_id']);
               
               
                $("#mainstyle_id").val(data[0]['mainstyle_id']);
               $("#substyle_id").val(data[0]['substyle_id']);
               
                $("#style_no").val(data[0]['style_no']);
               $("#fg_id").val(data[0]['fg_id']);
               
                $("#style_description").val(data[0]['style_description']);
               $("#order_rate").val(data[0]['order_rate']);
                
                document.getElementById('season_id').disabled=true;
                document.getElementById('Ac_code').disabled=true;
                document.getElementById('currency_id').disabled=true;
                document.getElementById('mainstyle_id').disabled=true;
                document.getElementById('substyle_id').disabled=true;
                document.getElementById('fg_id').disabled=true;
              
           }
           });
   
       $("#footable_2").html('');
   
       $.ajax({
       dataType: "json",
       url: "{{ route('VPO_GetOrderQty') }}",
       data:{'tr_code':sales_order_no,'process_id':process_id},
       success: function(data)
       {
                 $("#footable_2").html(data.html);
                
                $("#footable_2 thead th").each(function(index) {
                    var columnName = $(this).text().trim(); 
                    if (columnName !== "SrNo" && columnName !== "Color" && columnName !== "Total Qty") 
                    { 
                        $("#footable_2 tbody td:nth-child(" + (index + 1) + ") input[type='number']").attr('sz_group', columnName);
                    }  
                });

                $("#footable_2 tbody tr").each(function(index) 
                {
                     var colorName = $(this).find("td select[name='color_id[]'] option:selected").text().replace(/\s/g, "_"); 
                     $(this).find(".size_id").attr("color",colorName);
                });
       }
       });
   
       $.ajax({
           dataType: "json",
       url: "{{ route('VPO_GetSizeList') }}",
       data:{'tr_code':sales_order_no},
       success: function(data){
       // $("#size_id").html(data.html);
       $("#size_ids").html(data.html);
       $("#size_idss").html(data.html);
      }
       });
   
   
       $.ajax({
           dataType: "json",
       url: "{{ route('VPO_GetColorList') }}",
       data:{'tr_code':sales_order_no},
       success: function(data){
       // $("#color_id").html(data.html);
       $("#color_ids").html(data.html);
       $("#color_idss").html(data.html);
      }
       });
   
       $.ajax({
           dataType: "json",
       url: "{{ route('VPO_GetItemList') }}",
       data:{'tr_code':sales_order_no},
       success: function(data){
       $("#item_code").html(data.html);
      }
       });
   
       $.ajax({
           dataType: "json",
       url: "{{ route('VPO_GetClassList') }}",
       data:{'tr_code':sales_order_no},
       success: function(data){
       $("#class_id").html(data.html);
      }
       });
   
   }
   
   function EnableFields()
   {
                  $("select").prop('disabled', false);
   }
   
   
   
    
    $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
       
       mycalc();
   
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
   
   
   // $('table.footable_2').on('keyup', 'input[name^="s1[]"],input[name^="s2[]"],input[name^="s3[]"],input[name^="s4[]"],input[name^="s5[]"],input[name^="s6[]"],input[name^="s7[]"],input[name^="s8[]"],input[name^="s9[]"],input[name^="s10[]"],input[name^="s11[]"],input[name^="s12[]"],input[name^="s13[]"],input[name^="s14[]"],input[name^="s15[]"],input[name^="s16[]"],input[name^="s17[]"],input[name^="s18[]"],input[name^="s19[]"],input[name^="s20[]"]', function()
   // {
   //   // alert();
   // CalculateQtyRowProxx($(this).closest("tr"));
   
   // });
   // function CalculateQtyRowProxx(row)
   // {   
   // if(row.find('input[name^="s1[]"]').val()){ var s1=row.find('input[name^="s1[]"]').val();}else{var s1=0;}
   // if(row.find('input[name^="s2[]"]').val()){ var s2=row.find('input[name^="s2[]"]').val();}else{var s2=0;}
   // if(row.find('input[name^="s3[]"]').val()){ var s3=row.find('input[name^="s3[]"]').val();}else{var s3=0;}
   //  if(row.find('input[name^="s4[]"]').val()){var s4=row.find('input[name^="s4[]"]').val();}else{var s4=0;}
   //  if(row.find('input[name^="s5[]"]').val()){var s5=row.find('input[name^="s5[]"]').val();}else{var s5=0;}
   //  if(row.find('input[name^="s6[]"]').val()){var s6=row.find('input[name^="s6[]"]').val();}else{var s6=0;}
   //  if(row.find('input[name^="s7[]"]').val()){var s7=row.find('input[name^="s7[]"]').val();}else{var s7=0;}
   //  if(row.find('input[name^="s8[]"]').val()){var s8=row.find('input[name^="s8[]"]').val();}else{var s8=0;}
   //  if(row.find('input[name^="s9[]"]').val()){var s9=row.find('input[name^="s9[]"]').val();}else{var s9=0;}
   //  if(row.find('input[name^="s10[]"]').val()){var s10=row.find('input[name^="s10[]"]').val();}else{var s10=0;}
   //  if(row.find('input[name^="s11[]"]').val()){var s11=row.find('input[name^="s11[]"]').val();}else{var s11=0;}
   //  if(row.find('input[name^="s12[]"]').val()){var s12=row.find('input[name^="s12[]"]').val();}else{var s12=0;}
   //  if(row.find('input[name^="s13[]"]').val()){var s13=row.find('input[name^="s13[]"]').val();}else{var s13=0;}
   //  if(row.find('input[name^="s14[]"]').val()){var s14=row.find('input[name^="s14[]"]').val();}else{var s14=0;}
   //  if(row.find('input[name^="s15[]"]').val()){var s15=row.find('input[name^="s15[]"]').val();}else{var s15=0;}
   //  if(row.find('input[name^="s16[]"]').val()){var s16=row.find('input[name^="s16[]"]').val();}else{var s16=0;}
   //  if(row.find('input[name^="s17[]"]').val()){var s17=row.find('input[name^="s17[]"]').val();}else{var s17=0;}
   //  if(row.find('input[name^="s18[]"]').val()){var s18=row.find('input[name^="s18[]"]').val();}else{var s18=0;}
   //  if(row.find('input[name^="s19[]"]').val()){var s19=row.find('input[name^="s19[]"]').val();}else{var s19=0;}
   //  if(row.find('input[name^="s20[]"]').val()){var s20=row.find('input[name^="s20[]"]').val();}else{var s20=0;}
   //  var total=parseInt(s1)+parseInt(s2)+parseInt(s3)+parseInt(s4)+parseInt(s5)+parseInt(s6)+parseInt(s7)+parseInt(s8)+parseInt(s9)+parseInt(s10)+parseInt(s11)+parseInt(s12)+parseInt(s13)+parseInt(s14)+parseInt(s15)+parseInt(s16)+parseInt(s17)+parseInt(s18)+parseInt(s19)+parseInt(s20);
   //  row.find('input[name^="size_qty_total[]"]').val(total);
    
    
    
    
    
   // }
   
   
   
   
   
   
   // $('table.footable_1').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   // {
   //   // alert();
   // CalculateQtyRowPro($(this).closest("tr"));
   
   // });
   // function CalculateQtyRowPro(row)
   // {   
   //  var consumption=+row.find('input[name^="consumption[]"]').val();
   //  var wastage=+row.find('input[name^="wastage[]"]').val();
   //  var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
   //  var bom_qty=+row.find('input[name^="bom_qty[]"]').val();
   
   // //var bom_qty1=(bom_qty + (bom_qty*(wastage/100))).toFixed(4);
    
   // var total_price=(bom_qty*rate_per_unit).toFixed(2);
   // //row.find('input[name^="bom_qty[]"]').val(bom_qty1);
   // row.find('input[name^="total_amount[]"]').val(total_price);
   // mycalc();
   // }
   
     $('table.footable_1').on('change', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
   {
         CalculateQtyRowPro($(this).closest("tr"));
    });
   function CalculateQtyRowPro(row)
   {   
    var consumption=+row.find('input[name^="consumption[]"]').val();
    var wastage=+row.find('input[name^="wastage[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
    var qty=$("#final_bom_qty").val();
    var final_cons=parseFloat(consumption) + parseFloat(consumption*(wastage/100));
   var bom_qty=(parseFloat(final_cons) * parseInt(qty)).toFixed(2);
    //var bom_qty1=(parseInt(bom_qty) + (parseInt(bom_qty)*(wastage/100))).toFixed(2);
    
   var total_price=(bom_qty*rate_per_unit).toFixed(2);
  //  row.find('input[name^="bom_qty[]"]').val(bom_qty);
   row.find('input[name^="total_amount[]"]').val(total_price);
    
   }
   
   //  $('#footable_3').on('change', '.item_sewing_trims', function() 
   //  {
   //   var item_code = $(this).val();
       
   //   var row2 = $(this).closest('tr'); // get the row
   //     $.ajax({
   //         type: "GET",
   //         dataType:"json",
   //         url: "{{ route('ItemDetails') }}",
   //       data:{item_code:item_code},
   //         success: function(data2){
   
   //              console.log(data2); 
                
   //                 +row2.find('input[name^="unit_ids[]"]').val(data2[0]['unit_id']);
   //                  +row2.find('input[name^="descriptions[]"]').val(data2[0]['item_description']);
   //               // row2.find('select[name^="descriptions[]"]').attr('value', data[0]['item_description']);
                   
   //          }
   //         });
   
   // });
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
                       row.find('input[name^="descriptions[]"]').val(data[0]['description']);
                       row.find('input[name^="consumptions[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastages[]"]').val(data[0]['wastage']);
                       row.find('input[name^="rate_per_units[]"]').val(data[0]['rate_per_unit']);
                      
                       row.find('select[name^="class_ids[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                       var bom_qty=data[0]['bom_qty'];
                       
                   //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                      var total_amount=(bom_qty*data[0]['rate_per_unit']).toFixed(4)
                       row.find('input[name^="bom_qtys[]"]').val(bom_qty);
                       row.find('input[name^="total_amounts[]"]').val(total_amount);
               }
           });
   
           mycalc();
   
   }
   
   // For Fabric Trims get Consumption Details From Sales Costing Table
   $('table.footable_1').on('change', 'select[name^="item_code[]"]', function()
   {CalculateQtyRowPros1($(this).closest("tr"));});
   function CalculateQtyRowPros1(row)
   {   
       var item_code=+row.find('select[name^="item_code[]"]').val();
   //alert(item_code);
   
       var sales_order_no=$('#sales_order_no').val();
       $.ajax({
               type: "GET",
               dataType:"json",
               url: "{{ route('FabricWiseSalesOrderCosting') }}",
               data:{'item_code':item_code,sales_order_no:sales_order_no},
               success: function(data)
               {
                       console.log(data);
                       row.find('input[name^="description[]"]').val(data[0]['description']);
                       row.find('input[name^="consumption[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastage[]"]').val(data[0]['wastage']);
                       row.find('input[name^="rate_per_unit[]"]').val(data[0]['rate_per_unit']);
                       row.find('input[name^="bom_qty[]"]').val(data[0]['bom_qty']);
                       row.find('input[name^="color_id[][]"]').val(data[0]['color_id']);
                       row.find('select[name^="class_id[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                       row.find('input[name^="total_amount[]"]').val(data[0]['bom_qty']*data[0]['rate_per_unit']);
               }
           });
   
           mycalc();
   }
   
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
                       row.find('input[name^="descriptionss[]"]').val(data[0]['description']);
                       row.find('input[name^="consumptionss[]"]').val(data[0]['consumption']);
                       row.find('input[name^="wastagess[]"]').val(data[0]['wastage']);
                       row.find('select[name^="class_idss[]"]').val(data[0]['class_id']);
                       row.find('select[name^="unit_idss[]"]').val(data[0]['unit_id']);
                       row.find('input[name^="rate_per_unitss[]"]').val(data[0]['rate_per_unit']);
                     // alert(data[0]['bom_qty']);
                       var bom_qty=parseFloat(data[0]['bom_qty']);
                       // var wastage=parseFloat(data[0]['wastage']);
                   //    var bom_qty_final= (bom_qty + (bom_qty*(wastage/100))).toFixed(4);
                      var rate=data[0]['rate_per_unit'];
                      var total_amount=(bom_qty*rate).toFixed(4);
                       row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
                       row.find('input[name^="total_amountss[]"]').val(total_amount);
               }
           });
   
           mycalc();
   
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
    
     
   //  $('table.footable_4').on("keyup", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"]', function()
   // {
   //   // alert();
   // CalculateQtyRowPross($(this).closest("tr"));
   
   // });
   // function CalculateQtyRowPross(row)
   // {   
    
   //  var consumption=+row.find('input[name^="consumptionss[]"]').val();
   //  var wastage=+row.find('input[name^="wastagess[]"]').val();
   //  var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
   //  var bom_qty=+row.find('input[name^="bom_qtyss[]"]').val();
     
   // //  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
   //  var total_price=(bom_qty*rate_per_unit).toFixed(2);
   // //  row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
    
   //  row.find('input[name^="total_amountss[]"]').val(total_price);
   //  mycalc();
   // }
  
   $('table.footable_4').on("change", 'input[name^="consumptionss[]"],input[name^="wastagess[]"],input[name^="rate_per_unitss[]"],input[name^="bom_qtyss[]"]', function()
   {
      // alert();
   CalculateQtyRowPross($(this).closest("tr"));
   
   });
   function CalculateQtyRowPross(row)
   {   
    
    var consumption=+row.find('input[name^="consumptionss[]"]').val();
    var wastage=+row.find('input[name^="wastagess[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unitss[]"]').val();
     var qty=+row.find('input[name^="bom_qtyss1[]"]').val();
    // var qty=$("#final_bom_qty").val();
    var final_cons=parseFloat(consumption) + parseFloat(consumption*(wastage/100));
   var bom_qty=(parseFloat(final_cons) * parseInt(qty)).toFixed(2);
     
    var total_price=(bom_qty*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
    row.find('input[name^="total_amountss[]"]').val(total_price);
    mycalc();
   }
   
     $('table.footable_5').on("change", 'input[name^="consumptionsx[]"],input[name^="wastagesx[]"],input[name^="rate_per_unitsx[]"],input[name^="bom_qtysx[]"]', function()
   {
      // alert();
        // CalculateQtyRowProsx($(this).closest("tr"));
   
   });
   function CalculateQtyRowProsx(row)
   {   
    var consumption=+row.find('input[name^="consumptionsx[]"]').val();
    var wastage=+row.find('input[name^="wastagesx[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_unitsx[]"]').val();
    var  qty=+row.find('input[name^="bom_qtysx1[]"]').val();
   //  var qty=$("#final_bom_qty").val();
    var final_cons=parseFloat(consumption) + parseFloat(consumption*(wastage/100));
   var bom_qty=(parseFloat(final_cons) * parseInt(qty)).toFixed(2);
    
    
    var total_price=(bom_qty*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtysx[]"]').val(bom_qty);
    row.find('input[name^="total_amountsx[]"]').val(total_price);
    mycalc();
   }
    
    
      $('table.footable_3').on("change", 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"]', function()
   {
      // alert();
   CalculateQtyRowPros($(this).closest("tr"));
   
   });
   function CalculateQtyRowPros(row)
   {   
    var consumption=+row.find('input[name^="consumptions[]"]').val();
    var wastage=+row.find('input[name^="wastages[]"]').val();
    var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
     var qty=+row.find('input[name^="bom_qtys1[]"]').val();
    // var qty=$("#final_bom_qty").val();
    var final_cons=parseFloat(consumption) + parseFloat(consumption*(wastage/100));
   var bom_qty=(parseFloat(final_cons) * parseInt(qty)).toFixed(2);
    
    var total_price=(bom_qty*rate_per_unit).toFixed(2);
    row.find('input[name^="bom_qtys[]"]').val(bom_qty);
    row.find('input[name^="total_amounts[]"]').val(total_price);
    mycalc();
   }
    
   var indexcone = 2;
   function insertcone1(){
   
   var table=document.getElementById("footable_1").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "id"+indexcone;
   t1.name= "id[]";
   t1.value=indexcone;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_code"),
   y = x.clone();
   y.attr("id","item_code");
   y.attr("name","item_code[]");
   y.width(200);
   y.appendTo(cell3);
      
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_id"),
   y = x.clone();
   y.attr("id","class_id");
   y.attr("name","class_id[]");
   y.width(200);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "description"+indexcone;
   t5.name="description[]";
   cell5.appendChild(t5); 
    
   var cell5 = row.insertCell(4);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "consumption"+indexcone;
   t5.name="consumption[]";
   cell5.appendChild(t5);  
   
   var cell3 = row.insertCell(5);
   var t3=document.createElement("select");
   var x = $("#unit_id"),
   y = x.clone();
   y.attr("id","unit_id");
   y.attr("name","unit_id[]");
   y.width(100);
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_unit"+indexcone;
   t5.name="rate_per_unit[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(7);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastage"+indexcone;
   t5.name="wastage[]";
   cell5.appendChild(t5);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qty"+indexcone;
   t5.name="bom_qty[]";
   cell5.appendChild(t5);
    
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="FABRIC";
   t5.readOnly=true;
   t5.id = "total_amount"+indexcone;
   t5.name="total_amount[]";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(10);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone1()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_1').find('tr').eq(indexcone);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;
   
   indexcone++;
   recalcIdcone1();
   }
   
   // Start Sewing Trims----------------------------
   var indexcone1 = 2;
   function insertcone2(){
   
   var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "ids"+indexcone1;
   t1.name= "ids[]";
   t1.value=indexcone1;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_codes"),
   y = x.clone();
   y.attr("id","item_codes");
   y.attr("name","item_codes[]");
   y.width(200);
   y.appendTo(cell3);
     
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_ids"),
   y = x.clone();
   y.attr("id","class_ids");
   y.attr("name","class_ids[]");
   y.width(200);
   y.appendTo(cell3);
   
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptions"+indexcone1;
   t5.name="descriptions[]";
   cell5.appendChild(t5); 
   
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_ids"),
   y = x.clone();
   y.attr("id","color_ids");
   y.attr("name","color_ids[][]");
   y.width(200);
   y.appendTo(cell3); 
   
   
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   t5.type="text";
   t5.id = "size_arrays"+indexcone2;
   t5.name="size_arrays[]";
   cell3.appendChild(t5); 
   
     
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_units"+indexcone1;
   t5.name="rate_per_units[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastages"+indexcone1;
   t5.name="wastages[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qtys"+indexcone1;
   t5.name="bom_qtys[]";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="SEWING";
   t5.readOnly=true;
   t5.id = "total_amounts"+indexcone1;
   t5.name="total_amounts[]";
   cell5.appendChild(t5); 
   
    
    
   var cell6=row.insertCell(12);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone2()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_3').find('tr').eq(indexcone1);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;
   
   indexcone1++;
   recalcIdcone2();
   }
   
   
   // Start Packing Trims----------------------------
   var indexcone2 = 2;
   function insertcone3(){
   
   var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
   var row=table.insertRow(table.rows.length);
   
   var cell1=row.insertCell(0);
   var t1=document.createElement("input");
   t1.style="display: table-cell; width:50px;";
   //t1.className="form-control col-sm-1";
   
   t1.id = "idss"+indexcone2;
   t1.name= "idss[]";
   t1.value=indexcone2;
   
   cell1.appendChild(t1);
     
   var cell3 = row.insertCell(1);
   var t3=document.createElement("select");
   var x = $("#item_codess"),
   y = x.clone();
   y.attr("id","item_codess");
   y.attr("name","item_codess[]");
   y.width(200);
   y.appendTo(cell3);
     
   var cell3 = row.insertCell(2);
   var t3=document.createElement("select");
   var x = $("#class_idss"),
   y = x.clone();
   y.attr("id","class_idss");
   y.attr("name","class_idss[]");
   y.width(200);
   y.appendTo(cell3);
   
   
   var cell5 = row.insertCell(3);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:200px; height:30px";
   t5.type="text";
   t5.id = "descriptionss"+indexcone2;
   t5.name="descriptionss[]";
   cell5.appendChild(t5); 
   
   
   var cell3 = row.insertCell(4);
   var t3=document.createElement("select");
   var x = $("#color_idss"),
   y = x.clone();
   y.attr("id","color_idss");
   y.attr("name","color_idss[][]");
   y.width(200);
   y.appendTo(cell3);  
    
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   t5.type="text";
   t5.id = "size_arrayss"+indexcone2;
   t5.name="size_arrayss[]";
   cell3.appendChild(t5);
    
     
   var cell5 = row.insertCell(6);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
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
   y.appendTo(cell3);
   
   var cell5 = row.insertCell(8);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "rate_per_unitss"+indexcone2;
   t5.name="rate_per_unitss[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(9);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "wastagess"+indexcone2;
   t5.name="wastagess[]";
   cell5.appendChild(t5);
   
   
   var cell5 = row.insertCell(10);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.id = "bom_qtyss"+indexcone2;
   t5.name="bom_qtyss[]";
   cell5.appendChild(t5);
     
   var cell5 = row.insertCell(11);
   var t5=document.createElement("input");
   t5.style="display: table-cell; width:80px;";
   t5.type="text";
   t5.className="PACKING";
   t5.readOnly=true;
   t5.id = "total_amountss"+indexcone2;
   t5.name="total_amountss[]";
   cell5.appendChild(t5); 
    
    
   var cell6=row.insertCell(12);
   
   var btnAdd = document.createElement("INPUT");
   btnAdd.id = "Abutton";
   btnAdd.type = "button";
   btnAdd.className="btn btn-warning pull-left";
   btnAdd.value = "+";
   btnAdd.setAttribute("onclick", "insertcone3()");
   cell6.appendChild(btnAdd);
   
   
   var btnRemove = document.createElement("INPUT");
   btnRemove.id = "Dbutton";
   btnRemove.type = "button";
   btnRemove.className="btn btn-danger pull-left";
   btnRemove.value = "X";
   btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
   cell6.appendChild(btnRemove);
   
   var w = $(window);
   var row = $('#footable_4').find('tr').eq(indexcone2);
   
   if (row.length){
   $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
   }
   
   document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;
   
   indexcone2++;
   recalcIdcone3();
   }
   
   
   
   
   
   
   
   function mycalc()
   {   
   sum1 = 0.0;
   var amounts = document.getElementsByClassName('size_qty_total');
   //alert("value="+amounts[0].value);
   for(var i=0; i<amounts .length; i++)
   { 
   var a = +amounts[i].value;
   sum1 += parseFloat(a);
   }
   document.getElementById("final_bom_qty").value = sum1.toFixed(2);
   // sum1 = 0.0;
   // var amounts = document.getElementsByClassName('FABRIC');
   // //alert("value="+amounts[0].value);
   // for(var i=0; i<amounts .length; i++)
   // { 
   // var a = +amounts[i].value;
   // sum1 += parseFloat(a);
   // }
   // document.getElementById("fabric_value").value = sum1.toFixed(2);
   
   // sum2 = 0.0;
   // var amounts = document.getElementsByClassName('SEWING');
   // //alert("value="+amounts[0].value);
   // for(var i=0; i<amounts .length; i++)
   // { 
   // var a = +amounts[i].value;
   // sum2 += parseFloat(a);
   // }
   // document.getElementById("sewing_trims_value").value = sum2.toFixed(2);
   
   // sum3 = 0.0;
   // var amounts = document.getElementsByClassName('PACKING');
   // //alert("value="+amounts[0].value);
   // for(var i=0; i<amounts .length; i++)
   // { 
   // var a = +amounts[i].value;
   // sum3 += parseFloat(a);
   // }
   // document.getElementById("packing_trims_value").value = sum3.toFixed(2);
     
   //  var agent_commission_value=$("#agent_commission_value").val();
   //  var total_cost_value=parseFloat(sum1)+parseFloat(sum2)+parseFloat(sum3);
   //  $("#total_cost_value").val(total_cost_value.toFixed(2));
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
    
   }
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
   
   function recalcIdcone4(){
   $.each($("#footable_4 tr"),function (i,el){
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