@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Purchase Order</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Purchase Order</li>
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
<form action="{{route('PurchaseOrder.update',$purchasefetch)}}" id="form1" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@method('put')
@csrf    
    
    
<h4 class="card-title mb-4">Purchase Order: {{ $purchasefetch->pur_code }}</h4>
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
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}"

{{ $row->firm_id == $purchasefetch->firm_id ? 'selected="selected"' : '' }}

    >{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">PO Date</label>
<input type="date" name="pur_date" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->pur_date }}">

<input type="hidden" name="pur_code" class="form-control" id="pur_code" value="{{ $purchasefetch->pur_code }}" readonly="readonly">
<input type="hidden" name="c_code" id="c_code" value="{{ $purchasefetch->c_code }}" />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Supplier</label>
<select name="Ac_code" class="form-select" id="Ac_code" onchange="getPartyDetails();">
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
 
<input type="hidden" name="supplierRef" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->supplierRef  }}">
<input type="hidden" name="gstNo" class="form-control" id="gstNo" value="{{ $purchasefetch->gstNo  }}">
 

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">GST</label>
<select name="tax_type_id" class="form-select" id="tax_type_id"  disabled>
<option value="">--- Select Gst---</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}"

{{ $rowgst->tax_type_id == $purchasefetch->tax_type_id ? 'selected="selected"' : '' }}


    >{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">PO</label>
<select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();">
<option value="">Type</option>
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


</div>     


<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">BOM</label>
<select name="bom_codes[]" class="form-select" id="bom_code" multiple>
<option value="">BOM List</option>
@php $bom_ids = explode(',', $purchasefetch->bom_code);   @endphp
@foreach($BOMLIST as  $rowbom)
{
    <option value="{{ $rowbom->bom_code  }}"
 
   @if(in_array($rowbom->bom_code, $bom_ids)) selected @endif  
    
    >{{ $rowbom->bom_code }}  ({{$rowbom->sales_order_no}})</option>
}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">BOM Type</label>
<select name="bom_type[]" class="form-select select2" id="bom_type"  onchange="GetClassesList();" multiple >
<option value="0">Type List</option>    

@php $cat_ids = explode(',', $purchasefetch->bom_type);   @endphp
@foreach($CatList as  $rowcat)
{
    <option value="{{ $rowcat->cat_id  }}"
 
   @if(in_array($rowcat->cat_id, $cat_ids)) selected @endif  
    
    >{{ $rowcat->cat_name }}</option>
}
@endforeach
 
</select>
</div>
</div>
 

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Classificaion </label>
<select name="class_id[]" class="form-select select2" id="class_id" onchange="getBomDetail();" multiple>
<option value="0">Class List {{$purchasefetch->class_id}}</option>    

@php  $class_ids = explode(',', $purchasefetch->class_id);   @endphp
 @foreach($ClassList as  $rowclass)
{
    <option value="{{ $rowclass->class_id  }}"
    @if(in_array($rowclass->class_id, $class_ids)) selected @endif  
    >{{ $rowclass->class_name }} </option>
}
@endforeach
</select>
</div>
</div>


</div> 
 
 <input type="hidden"  name="cnt" id="cnt" value="{{count($detailpurchase)}}"> 

<div>
</div>

<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">  
<thead>
<tr>
<th>SrNo</th>
<th>Action(Add/Remove)</th>
<th>Sales Order No</th>
<th>Item Name</th>
<th>Preview</th>
<th>HSN No	</th>
<th>Unit</th>
<th>MOQ</th>
<th>BOM Qty</th>
<th>Stock</th>
<th>Quantity</th>
<th>Rate</th>
<th>Disc%</th>
<th>Discount</th>
<th>CGST%</th>
<th>CAMT</th>
<th>SGST%</th>
<th>SAMT</th>
<th>IGST%</th>
<th>IAMT</th>
<th>Amount</th>
<th>Freight HSN</th>
<th>Freight</th>
<th>Total Amount</th>

</tr>
</thead>
<tbody id="bomdis">

@php  if($detailpurchase->isEmpty()) { @endphp

 <tr  >
                       
                        <td><input type="text" name="id[]" value="1" id="id" style="width:50px; height:30px;"/></td>
                        <td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
                    
                        <td><input type="text"  name="sales_order_no[]" value="" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                        <td> <select name="item_codes[]" class="item" id="item_code" style="width:250px; height:30px;" required     onchange="getItemDetails(this);" >
                        <option value="">--Select Item--</option> 
                        @foreach($itemlist as  $row1)
                            <option value="{{$row1->item_code}}" > {{$row1->item_name}}</option> 
                         @endforeach
                         </select></td> 
                        <td>
                         <a href="" target="_blank"><img  src=""  id="item_image" name="item_image[]" class="imgs"> </a>
                         </td> 
                        <td><input type="text"  name="hsn_code[]" value="" id="hsn_code" style="width:80px;" required readOnly/> </td> 
                         <td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;" required  >
                        <option value="">--Select Unit--</option> 
                        @foreach($unitlist as  $rowunit)
                      
                             <option value="{{$rowunit->unit_id}}">{{$rowunit->unit_name}}</option> 
                         
                         @endforeach
                         </select></td> 
                        <td><input type="text" value="0" name="moq[]" id="moq" style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="0"  name="bom_qty[]" id="bom_qty" style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="text" value="0"  name="stock[]" id="stock" style="width:80px;  height:30px;" readOnly/></td>
                        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]" value="" id="item_qty" style="width:80px;  height:30px;" required/>
                        	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
                        </td>
                        <td><input type="number" step="any"    name="item_rates[]"   value="0" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
                        <td><input type="number" step="any"    name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_cgsts[]" readOnly value="0" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"   name="camts[]" readOnly value="0" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_sgsts[]" readOnly value="0" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="samts[]" readOnly  value="0" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="pur_igsts[]" readOnly value="0" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="iamts[]" readOnly value="0" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"    name="amounts[]" readOnly value="0" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
                        <td><input type="number" step="any"  name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"  name="freight_amt[]" onkeyup="calFreightAmt(this);" class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
                        <td><input type="number" step="any"    name="total_amounts[]" readOnly class="TOTAMT" value=""  id="total_amount" style="width:80px; height:30px;" required/>
                         <input type="hidden" step="any"  name="conQtys[]" readOnly     value="1000" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="5" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="priUnitds[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="10" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="11" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="9" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="rateMs[]" readOnly     value="0" style="width:80px; height:30px;">
                        <input type="hidden" step="any"  name="totalQtys[]" readOnly     value="0" style="width:80px; height:30px;">
                        
                        </td>
                       </tr> 

    @php } else { @endphp
  @php $no=1; @endphp
@foreach($detailpurchase as $row)

 <tr>
<td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;height:30px;"/></td>
<td><button type="button" onclick="insertRow();mycalc();"  class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" > <button type="button" onclick="setConversion(this);" class="btn btn-success pull-left">?</button></td>
 <td><input type="text"  name="sales_order_no[]" value="{{$row->sales_order_no}}" id="sales_order_no" style="width:80px;" required readOnly/> </td>
                       
 <td>
<select name="item_codes[]" class="item" id="item_code" style="width:250px; height:30px;" disabled  >
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}"

{{ $rowitem->item_code == $row->item_code ? 'selected="selected"' : '' }}

    >{{ $rowitem->item_name }}</option>

}
@endforeach
</select>
    </td>

<td>
    
    
    <a href="https://ken.korbofx.org/images/{{$row->item_image_path}}" target="_blank">
        <img  src="https://ken.korbofx.org/thumbnail/{{$row->item_image_path}}"  id="item_image" name="item_image[]" class="imgs"> </a> 
    <input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
    </td>
  <td>  <input type="text"   name="hsn_code[]"   value="{{ $row->hsn_code }}" id="hsn_code" style="width:80px;height:30px;" required readOnly/></td>

<td> <select name="unit_id[]" class="unit_id" id="unit_id" style="width:100px;height:30px;" disabled>
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}"

{{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}

    >{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>




@php
 
 
if($cat_ids[0]=="1")
 {
            $min=$row->FabGRNQty;
            if($purchasefetch->class_id!=7)
            { 
    
                $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$row->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$row->item_code."')) as Stock"));
            }
            else
            {
                

                $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$row->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$row->item_code."')) as Stock"));
            }
            
}
else
{
             $min=$row->TrimGRNQty;
             $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$row->item_code."')-
             (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$row->item_code."')) as Stock"));
        
}
 

@endphp







<td><input type="text" value="0"   style="width:80px;  height:30px;" readOnly/></td>
<td><input type="text" value="0"   style="width:80px;  height:30px;" readOnly/></td>
<td><input type="text" value="{{$stock[0]->Stock}}"   style="width:80px;  height:30px;" readOnly/></td>

<td><input style="width:80px;height:30px;" class="ITEMQTY" type="text" class="Qty" name="item_qtys[]" min="{{ $min }}" max="{{ $row->item_qty }}" value="{{ $row->item_qty }}" if(Session::get('user_type')!=1 && $is_approved==1){ echo 'readOnly'; } @endphp  id="item_qty">
</td>

@php
$bom_code='';
foreach($bom_ids as $bom)
        {
            $bom_code=$bom_code."'".$bom."',";
        }
        $bom_code=rtrim($bom_code,",");
 
if($row->cat_id==1)
{
  
  
  
  
    $List = DB::select(" select  MIN(rate_per_unit) as  rate_per_unit from bom_fabric_details where  bom_code in ($bom_code)
    and bom_fabric_details.item_code='".$row->item_code."'");
    
    if(is_null($List[0]->rate_per_unit))
    {
    
            $List = DB::select(" select  MIN(rate_per_unit) as  rate_per_unit from bom_trim_fabric_details where  bom_code in ($bom_code)
            and bom_trim_fabric_details.item_code='".$row->item_code."'");
    } 
          
          
          
}

elseif($row->cat_id==2)
{
 
    $List = DB::select(" select  MIN(rate_per_unit) as  rate_per_unit from bom_sewing_trims_details where  bom_code in ($bom_code)
    and bom_sewing_trims_details.item_code='".$row->item_code."'");
  
}
elseif($row->cat_id==3)
{
  $List = DB::select(" select  MIN(rate_per_unit) as  rate_per_unit from bom_packing_trims_details where bom_code in ($bom_code)
    and bom_packing_trims_details.item_code='".$row->item_code."'");
}


@endphp


<td><input  style="width:80px;height:30px;" type="number"  step="any" class="RATE" name="item_rates[]" value="{{ $row->item_rate }}" id="item_rate"  @php  if(Session::get('user_type')!=1 && $is_approved==1){ echo 'readOnly'; } @endphp>
</td> 
<td><input style="width:100px;height:30px;" type="number" step="any" id="disc_per" class="" name="disc_pers[]" value="{{ $row->disc_per }}" @php  if(Session::get('user_type')!=1 && $is_approved==1){ echo 'readOnly'; } @endphp></td>
<td><input readOnly style="width:80px;height:30px;" readOnly  type="number" id="disc_amount" step="any" class="" name="disc_amounts[]" value="{{ $row->disc_amount }}" ></td>
<td><input readOnly style="width:80px;height:30px;" readOnly type="number" id="pur_cgst" step="any"  class="" name="pur_cgsts[]" value="{{ $row->pur_cgst }}"></td>
<td><input  style="width:80px;height:30px;"  type="number" readOnly step="any" id="camt" class="GSTAMT" name="camts[]" value="{{ $row->camt }}"></td>
<td><input readOnly style="width:80px;height:30px;" readOnly type="number" step="any" id="pur_sgst" class="" name="pur_sgsts[]" value="{{ $row->pur_sgst }}"></td>
<td><input style="width:80px;height:30px;"  type="number" readOnly step="any" id="samt" class="GSTAMT" name="samts[]" value="{{ $row->samt }}"></td>
<td><input readOnly style="width:80px;height:30px;" readOnly type="number" step="any" id="pur_igst" class="" name="pur_igsts[]" value="{{ $row->pur_igst }}"></td>
<td><input  style="width:80px;height:30px;"  type="number" readOnly step="any" id="iamt" class="GSTAMT" name="iamts[]" value="{{ $row->iamt }}"></td>
<td><input  style="width:80px;height:30px;"  type="number" readOnly step="any" id="amount" class="GROSS" name="amounts[]" value="{{ $row->amount }}"></td>
<td><input type="text" name="freight_hsn[]" readOnly class="" id="freight_hsn" value="{{ $row->freight_hsn }}" style="width:80px;height:30px;"></td>
<td><input type="text" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="{{ $row->freight_amt }}" style="width:80px;height:30px;"></td>
<td><input  style="width:80px;height:30px;"  type="number" readOnly step="any" id="total_amount" class="TOTAMT" name="total_amounts[]" value="{{ $row->total_amount }}">


<input type="hidden" step="any"  name="conQtys[]" readOnly     value="{{ $row->conQty }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="unitIdMs[]" readOnly     value="{{ $row->unitIdM }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="priUnitds[]" readOnly     value="{{ $row->priUnitd }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="SecConQtys[]" readOnly     value="{{ $row->SecConQty }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="secUnitIds[]" readOnly     value="{{ $row->secUnitId }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="poQtys[]" readOnly     value="{{ $row->poQty }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="poUnitIds[]" readOnly     value="{{ $row->poUnitId }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="rateMs[]" readOnly     value="{{ $row->rateM }}" style="width:80px; height:30px;">
<input type="hidden" step="any"  name="totalQtys[]" readOnly     value="{{ $row->totalQty }}" style="width:80px; height:30px;">






</td>
 </tr>
 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp

 </tbody>
<tfoot>
<tr>
<th>SrNo</th>
<th>Action(Add/Remove)</th>
<th>Sales Order No</th>
<th>Item Name</th>
<th>Preview</th>
<th>HSN No	</th>
<th>Unit</th>
<th>MOQ</th>
<th>BOM Qty</th>
<th>Stock</th>
<th>Quantity</th>
<th>Rate</th>
<th>Disc%</th>
<th>Discount</th>
<th>CGST%</th>
<th>CAMT</th>
<th>SGST%</th>
<th>SAMT</th>
<th>IGST%</th>
<th>IAMT</th>
<th>Amount</th>
<th>Freight HSN</th>
<th>Freight</th>
<th>Total Amount</th>

</tr>
</tfoot>
</table>
</div>
</div>
<br/>
 
<div class="row">
    
 <div class="col-md-2">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Qty</label>
<input type="text" name="total_qty" class="form-control" id="total_qty" value="{{ $purchasefetch->total_qty  }}" required readOnly>
<input type="hidden" name="address" class="form-control" id="address" value="{{ $purchasefetch->address  }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Gross Amount</label>
<input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();" value="{{ $purchasefetch->Gross_amount }}" required readOnly>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="Gst_amount" class="form-control" id="Gst_amount" value="{{ $purchasefetch->Gst_amount }}" required readOnly>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Freight Amount</label>
<input type="text" name="totFreightAmt"  class="form-control" id="totFreightAmt" value="{{ $purchasefetch->totFreightAmt }}" required readOnly>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Net Amount</label>
<input type="text" name="Net_amount" class="form-control" id="Net_amount" value="{{ $purchasefetch->Net_amount }}" required readOnly>
</div>
</div>


</div>

<div class="row"> 
<div class="col-md-4">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Delivery Address</label>
<input type="text" name="deliveryAddress" class="form-control" id="deliveryAddress" value="{{ $purchasefetch->deliveryAddress  }}">
</div>
</div>  

<div class="col-md-2">  
<div class="mb-3">
<label for="delivery_date" class="form-label">Delivery Date</label>
<input type="date" name="delivery_date" class="form-control" id="delivery_date" value="{{ $purchasefetch->delivery_date  }}" required>
</div>
</div>

@php 
if(Session::get('userId') == 1 || Session::get('userId') == 2 || Session::get('userId') == 3)
{
@endphp
<div class="col-md-2">
<div class="mb-3">
<label for="po_status" class="form-label">PO Status</label>
<select name="po_status" class="form-select" id="po_status" required>
<option value="">--PO Status--</option>
@foreach($JobStatusList as  $row)
{
    <option value="{{ $row->job_status_id }}"
    {{ $row->job_status_id == $purchasefetch->po_status ? 'selected="selected"' : '' }}        
    >{{ $row->job_status_name }}</option>
}
@endforeach
</select>
</div>
</div>
@php 
}
@endphp
<div class="col-md-4">	
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" id="narration" value="{{ $purchasefetch->narration }}">
</div>
</div>	

@php $user_type=Session::get('user_type'); @endphp
<div class="col-md-4" @php if($user_type!=1){ echo 'style="display:none;"'; } @endphp>  
<div class="mb-3">
<h5>Approve/Disapproval</h5>
 <select name="approveFlag" class="form-select" id="approveFlag" style="width:100px;" onchange="status_change(this.value);">
<option value="0" @php if($purchasefetch->approveFlag==0){echo 'selected="selected"';} @endphp>Pending</option>
<option value="1" @php if($purchasefetch->approveFlag==1){echo 'selected="selected"';} @endphp>Approved</option>
<option value="2" @php if($purchasefetch->approveFlag==2){echo 'selected="selected"';} @endphp>Disapproved</option>
 
</select>
</div>
</div>
 
  
  <div class="col-md-8" @php if($user_type!=1){ echo 'style="display:none;"'; } @endphp>	
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Reason For Disapproval</label>
     <input type="text" name="reason_disapproval"  readOnly  class="form-control" id="reason_disapproval" placeholder="Reason For Disapproval" value="{{$purchasefetch->reason_disapproval}}">
 </div>
</div>
 
</div>
 
    
    
    <div class="row">
<div class="col-md-12">
<div class="mb-3">
<label for="term_and_conditions" class="form-label">Terms and Conditions</label>
<textarea name="terms_and_conditions" class="form-control" id="editor1"   required>{{$purchasefetch->terms_and_conditions}}</textarea>
</div>
</div>
</div>

    
     </br>  
<button type="submit" id="Submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
<a href="{{ Route('PurchaseOrder.index') }}" class="btn btn-warning w-md">Cancel</a>
 
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


<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>




<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script> 
    
CKEDITOR.replace('editor1'); 
 

 
  @php  if(Session::get('user_type')!=1 && $is_approved==1){   @endphp
         $('#form1 input').attr('readonly', 'readonly');
          $("select").prop('disabled', true);
             $("textarea").prop('disabled', false);
         
   @php   }   @endphp
 
 $(document).on("change", 'input[class^="ITEMQTY"],input[class^="RATE"]', function (event) 
{
   @php  if(Session::get('user_type')!=1  ){   @endphp
        var po_type_id=$('#po_type_id').val();
           if(po_type_id!=2)
          {
                var value = $(this).val();
    
             var maxLength = parseFloat($(this).attr('max'));
             var minLength = parseFloat($(this).attr('min')); 
            if(value>maxLength){alert('Value can not be greater than '+maxLength);}
            if ((value !== '') && (value.indexOf('.') === -1)) 
            {
                 $(this).val(Math.max(Math.min(value, maxLength), minLength));
            }
     
          }
          
          @php   }   @endphp
          
   
});

    
    function calFreightAmt(row)
    {
        var freight_amt = $(row).val() ? $(row).val() : 0;
        var totAmt = $(row).parent().next().find('input[name="total_amounts[]"]').val() ? $(row).parent().next().find('input[name="total_amounts[]"]').val() : 0;
        var total_Amt = parseFloat(freight_amt) + parseFloat(totAmt);
        $(row).parent().next().find('input[name="total_amounts[]"]').val(total_Amt);
    }

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


function EnableFields()
{
                $("select").prop('disabled', false);
 
}


// var index = 1;
// function insertRow(){

// var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
// var row=table.insertRow(table.rows.length);

// var cell1=row.insertCell(0);
// var t1=document.createElement("input");
// t1.style="display: table-cell; width:50px;";
// //t1.className="form-control col-sm-1";

// t1.id = "id"+index;
// t1.name= "id[]";
// t1.value=index;

// cell1.appendChild(t1);



// var cell2 = row.insertCell(1);
// var t2=document.createElement("select");
// var x = $("#item_code"),
// y = x.clone();
// var selectedValue="";
// y.attr("id","item_code");
// y.find("option[value = '" + selectedValue + "']").attr("selected", "selected");
// y.attr("name","item_codes[]");
// y.width(100);
// y.appendTo(cell2);


// var cell3 = row.insertCell(2);
// var t3=document.createElement("img");
// t3.src="";
// t3.id = "item_image"+index;
// t3.name="item_image[]";
// cell3.appendChild(t3);

// var cell3 = row.insertCell(3); 
// var t3=document.createElement("input");
// t3.style="display: table-cell; width:80px;";
// t3.type="hidden";
// //t3.className="QTY";
// t3.id = "hsn_code"+index;
// t3.name="hsn_code[]";
// t3.value="";
// cell3.appendChild(t3);


// var cell2 = row.insertCell(4);
// var t2=document.createElement("select");
// var x = $("#unit_id"),
// y = x.clone();
// y.attr("id","unit_id");
// y.attr("name","unit_id[]");
// y.width(100);
// y.appendTo(cell2);


// var cell3 = row.insertCell(5);
// var t3=document.createElement("input");
// t3.style="display: table-cell; width:80px;";
// t3.type="number";
// //t3.className="QTY";
// t3.id = "item_qtys"+index;
// t3.name="item_qtys[]";
// t3.value="0";
// cell3.appendChild(t3);

// var cell4=row.insertCell(6);
// var t4=document.createElement("input");
// t4.style="display: table-cell; width:80px;";
// t4.type="number";
// t4.step="any";
// t4.id = "item_rates"+index;
// t4.name= "item_rates[]";
// t4.value="0";
// cell4.appendChild(t4);


// var cell5=row.insertCell(7);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="number";
// t5.step="any";
// t5.id = "disc_pers"+index;
// t5.name= "disc_pers[]";
// t5.value="0";
// cell5.appendChild(t5);


// var cell6=row.insertCell(8);
// var t6=document.createElement("input");
// t6.style="display: table-cell; width:80px;";
// t6.type="number";
// t6.step="any";
// t6.id = "disc_amounts"+index;
// t6.name= "disc_amounts[]";
// t6.value="0";
// cell6.appendChild(t6);

// var cell7=row.insertCell(9);
// var t7=document.createElement("input");
// t7.style="display: table-cell; width:80px;";
// t7.type="number";
// t7.step="any";
// t7.id = "pur_cgsts"+index;
// t7.name= "pur_cgsts[]";
// t7.value="0";
// cell7.appendChild(t7);


// var cell8=row.insertCell(10);
// var t8=document.createElement("input");
// t8.style="display: table-cell; width:80px;";
// t8.type="number";
// t8.step="any";
// t8.className="GSTAMT";
// t8.id = "camts"+index;
// t8.name= "camts[]";
// t8.value="0";
// cell8.appendChild(t8);


// var cell9=row.insertCell(11);
// var t9=document.createElement("input");
// t9.style="display: table-cell; width:80px;";
// t9.type="number";
// t9.step="any";
// t9.id = "pur_sgsts"+index;
// t9.name= "pur_sgsts[]";
// t9.value="0";
// cell9.appendChild(t9);

// var cell10=row.insertCell(12);
// var t10=document.createElement("input");
// t10.style="display: table-cell; width:80px;";
// t10.type="number";
// t10.step="any";
// t10.className="GSTAMT";
// t10.id = "samts"+index;
// t10.name= "samts[]";
// t10.value="0";
// cell10.appendChild(t10);


// var cell11=row.insertCell(13);
// var t11=document.createElement("input");
// t11.style="display: table-cell; width:80px;";
// t11.type="number";
// t11.step="any";
// t11.id = "pur_igsts"+index;
// t11.name= "pur_igsts[]";
// t11.value="0";
// cell11.appendChild(t11);

// var cell12=row.insertCell(14);
// var t12=document.createElement("input");
// t12.style="display: table-cell; width:80px;";
// t12.type="number";
// t12.step="any";
// t12.className="GSTAMT";
// t12.id = "iamts"+index;
// t12.name= "iamts[]";
// t12.value="0";
// cell12.appendChild(t12);


// var cell13=row.insertCell(15);
// var t13=document.createElement("input");
// t13.style="display: table-cell; width:80px;";
// t13.type="number";
// t13.step="any";
// t13.className="GROSS";
// t13.id = "amounts"+index;
// t13.name= "amounts[]";
// t13.value="0";
// cell13.appendChild(t13);
// document.getElementById("amounts"+index).style.display='value';


// var cell13=row.insertCell(16);
// var t13=document.createElement("input");
// t13.style="display: table-cell; width:80px;";
// t13.type="text";
// t13.step="any";
// t13.className="";
// t13.id = "freight_hsn"+index;
// t13.name= "freight_hsn[]";
// t13.value=document.getElementById("freight_hsn").value;
// cell13.appendChild(t13);
// document.getElementById("freight_hsn"+index).style.display='value';


// var cell13=row.insertCell(17);
// var t13=document.createElement("input");
// t13.style="display: table-cell; width:80px;";
// t13.type="text";
// t13.step="any";
// t13.className="FREIGHT";
// t13.id = "freight_amt"+index;
// t13.name= "freight_amt[]";
// t13.value="0";
// cell13.appendChild(t13);
// document.getElementById("freight_amt"+index).style.display='value';


// var cell14=row.insertCell(18);
// var t14=document.createElement("input");
// t14.style="display: table-cell; width:80px;";
// t14.type="number";
// t14.step="any";
// t14.className='TOTAMT';
// t14.id = "total_amounts"+index;
// t14.name= "total_amounts[]";
// t14.value="0"
// cell14.appendChild(t14);
// //document.getElementById("total_amounts"+index).style.display='value';  

// var cell15=row.insertCell(19);

// var btnAdd = document.createElement("INPUT");
// btnAdd.id = "Abutton";
// btnAdd.type = "button";
// btnAdd.className="btn btn-warning pull-left";
// btnAdd.value = "+";
// btnAdd.setAttribute("onclick", "insertRow(); mycalc();");
// cell15.appendChild(btnAdd);

// var btnRemove = document.createElement("INPUT");
// btnRemove.id = "Dbutton";
// btnRemove.type = "button";
// btnRemove.className="btn btn-danger pull-left";
// btnRemove.value = "X";
// btnRemove.setAttribute("onclick", "deleteRow(this)");
// cell15.appendChild(btnRemove);

// var w = $(window);
// var row = $('#footable_2').find('tr').eq( index );

// if (row.length){
// $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
// }

// document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

// index++;
// recalcId();



// }




var index = 1;
function insertRow(){
$("#item_code").select2("destroy");
var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
t1.id = "id"+index;
t1.name= "id[]";
t1.value=index;
cell1.appendChild(t1);

 
var cell15=row.insertCell(1);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertRow();mycalc();)");
cell15.appendChild(btnAdd);
  
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRow(this)");
cell15.appendChild(btnRemove);
 
 
 
var btnInfo = document.createElement("INPUT");
btnInfo.id = "Ibutton";
btnInfo.type = "button";
btnInfo.className="btn btn-success pull-left";
btnInfo.value = "?";
btnInfo.setAttribute("onclick", "setConversion(this)");
cell15.appendChild(btnInfo);
 
 
var cell5=row.insertCell(2);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
 
t5.id = "sales_order_no"+index;
t5.name= "sales_order_no[]";
t5.value="0";
cell5.appendChild(t5); 
 
   
var cell2 = row.insertCell(3);
var t2=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
var selectedValue="";
y.attr("id","item_code");
y.find("option[value = '" + selectedValue + "']").attr("selected", "selected");
y.attr("name","item_codes[]");
y.attr("value","");
y.width(250);
y.disabled=false;
y.appendTo(cell2);


var cell3 = row.insertCell(4);
var t3=document.createElement("img");
t3.src="";
t3.id = "item_image"+index;
t3.name="item_image[]";
cell3.appendChild(t3);

var cell3 = row.insertCell(5); 
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="text";
//t3.className="QTY";
t3.id = "hsn_code"+index;
t3.name="hsn_code[]";
t3.value="";
cell3.appendChild(t3);


var cell2 = row.insertCell(6);
var t2=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
y.width(100);
y.appendTo(cell2);



var cell3 = row.insertCell(7);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
t3.id = "moq"+index;
t3.name="moq[]";
t3.value="0";
cell3.appendChild(t3);

var cell3 = row.insertCell(8);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
t3.id = "bom_qty"+index;
t3.name="bom_qty[]";
t3.value="0";
cell3.appendChild(t3);


var cell3 = row.insertCell(9);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
t3.id = "stock"+index;
t3.name="stock[]";
t3.value="0";
cell3.appendChild(t3);



var cell3 = row.insertCell(10);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
t3.className="ITEMQTY";
t3.id = "item_qtys"+index;
t3.name="item_qtys[]";
t3.value="0";
cell3.appendChild(t3);

var cell4=row.insertCell(11);
var t4=document.createElement("input");
t4.style="display: table-cell; width:80px;";
t4.type="number";
t4.step="0.01";
t4.id = "item_rates"+index;
t4.name= "item_rates[]";
t4.value="0";
cell4.appendChild(t4);


var cell5=row.insertCell(12);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="number";
t5.step="0.01";
t5.id = "disc_pers"+index;
t5.name= "disc_pers[]";
t5.value="0";
cell5.appendChild(t5);


var cell6=row.insertCell(13);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="number";
t6.step="0.01";
t6.readOnly=true;
t6.id = "disc_amounts"+index;
t6.name= "disc_amounts[]";
t6.value="0";
cell6.appendChild(t6);

var cell7=row.insertCell(14);
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="number";
t7.step="0.01";
t7.readOnly=true;
t7.id = "pur_cgsts"+index;
t7.name= "pur_cgsts[]";
t7.value="0";
cell7.appendChild(t7);


var cell8=row.insertCell(15);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;";
t8.type="number";
t8.step="0.01";
t8.readOnly=true;
t8.className="GSTAMT";
t8.id = "camts"+index;
t8.name= "camts[]";
t8.value="0";
cell8.appendChild(t8);


var cell9=row.insertCell(16);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="number";
t9.step="0.01";
t9.readOnly=true;
t9.id = "pur_sgsts"+index;
t9.name= "pur_sgsts[]";
t9.value="0";
cell9.appendChild(t9);

var cell10=row.insertCell(17);
var t10=document.createElement("input");
t10.style="display: table-cell; width:80px;";
t10.type="number";
t10.step="0.01";
t10.readOnly=true;
t10.className="GSTAMT";
t10.id = "samts"+index;
t10.name= "samts[]";
t10.value="0";
cell10.appendChild(t10);


var cell11=row.insertCell(18);
var t11=document.createElement("input");
t11.style="display: table-cell; width:80px;";
t11.type="number";
t11.step="0.01";
t11.readOnly=true;
t11.id = "pur_igsts"+index;
t11.name= "pur_igsts[]";
t11.value="0";
cell11.appendChild(t11);

var cell12=row.insertCell(19);
var t12=document.createElement("input");
t12.style="display: table-cell; width:80px;";
t12.type="number";
t12.step="0.01";
t12.readOnly=true;
t12.className="GSTAMT";
t12.id = "iamts"+index;
t12.name= "iamts[]";
t12.value="0";
cell12.appendChild(t12);


var cell13=row.insertCell(20);
var t13=document.createElement("input");
t13.style="display: table-cell; width:80px;";
t13.type="number";
t13.step="0.01";
t13.readOnly=true;
t13.className="GROSS";
t13.id = "amounts"+index;
t13.name= "amounts[]";
t13.value="0";
cell13.appendChild(t13);
document.getElementById("amounts"+index).style.display='value';


var cell13=row.insertCell(21);
var t13=document.createElement("input");
t13.style="display: table-cell; width:80px;";
t13.type="text";
t13.step="0.01";
t13.className="";
t13.readOnly=true;
t13.id = "freight_hsn"+index;
t13.name= "freight_hsn[]";
t13.value=document.getElementById("freight_hsn").value;
cell13.appendChild(t13);
document.getElementById("freight_hsn"+index).style.display='value';


var cell13=row.insertCell(22);
var t13=document.createElement("input");
t13.style="display: table-cell; width:80px;";
t13.type="text";
t13.step="0.01";
t13.className="FREIGHT";
t13.id = "freight_amt"+index;
t13.name= "freight_amt[]";
t13.value="0";
t13.setAttribute("onkeyup", "calFreightAmt(this)");
cell13.appendChild(t13);
document.getElementById("freight_amt"+index).style.display='value';


var cell14=row.insertCell(23);
var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="number";
t14.step="0.01";
t14.readOnly=true;
t14.className='TOTAMT';
t14.id = "total_amounts"+index;
t14.name= "total_amounts[]";
t14.value="0";
cell14.appendChild(t14);
//document.getElementById("total_amounts"+index).style.display='value';  

 
                          
  
  
var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "conQtys"+index;
t14.name= "conQtys[]";
t14.value="1000";
cell14.appendChild(t14);
  
 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "unitIdMs"+index;
t14.name= "unitIdMs[]";
t14.value="5";
cell14.appendChild(t14); 
  
 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "priUnitds"+index;
t14.name= "priUnitds[]";
t14.value="10";
cell14.appendChild(t14);  
 
 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "SecConQtys"+index;
t14.name= "SecConQtys[]";
t14.value="10";
cell14.appendChild(t14); 

 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "secUnitIds"+index;
t14.name= "secUnitIds[]";
t14.value="11";
cell14.appendChild(t14); 

 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "poQtys"+index;
t14.name= "poQtys[]";
t14.value="0";
cell14.appendChild(t14); 

 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "poUnitIds"+index;
t14.name= "poUnitIds[]";
t14.value="9";
cell14.appendChild(t14); 

 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "rateMs"+index;
t14.name= "rateMs[]";
t14.value="0";
cell14.appendChild(t14); 



 var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="hidden";
t14.step="any";
t14.readOnly=true;
t14.id = "totalQtys"+index;
t14.name= "totalQtys[]";
t14.value="0";
cell14.appendChild(t14); 
// var w = $(window);
// var row = $('#footable_2').find('tr').eq( index );

// if (row.length){
// $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
// }

document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

index++;
recalcId();


 selselect();
    
}
 
 
 
 function selselect()
 {
     setTimeout(
  function() 
  {

  $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
 
     $(this).closest("tr").find('select[name="item_codes[]"]').select2();
  

    });
 }, 2000);
 }


</script>


<script>

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
var amounts = document.getElementsByClassName('GROSS');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Gross_amount").value = sum1.toFixed(2);



sum1 = 0.0;
var amounts = document.getElementsByClassName('GSTAMT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Gst_amount").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('TOTAMT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Net_amount").value = sum1.toFixed(0);


sum1 = 0.0;
var amounts = document.getElementsByClassName('FREIGHT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("totFreightAmt").value = sum1.toFixed(0);


	var sum = 0.0;
				var amounts = document.getElementsByClassName('ROWCOUNT');
                for(var i=0; i<amounts .length; i++)
                {
                    var a = +amounts[i].value;
                    sum += parseFloat(a) || 0;
                }
				 document.getElementById("cnt").value = sum;



}


$(document).on("change", 'input[class^="Qty"] ', function (event) 
{
    
     var po_type_id=$('#po_type_id').val();
           if(po_type_id!=2)
          {
    var value = $(this).val();
    var maxLength = parseInt($(this).attr('max'));
    var minLength = parseInt($(this).attr('min')); 
    if(value>maxLength){alert('Value can not be greater than '+maxLength);}
    if ((value !== '') && (value.indexOf('.') === -1)) 
    {
        $(this).val(Math.max(Math.min(value, maxLength), minLength));
    }
    
          }
   
});



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




      function getItemDetails(row)
 {
          row = $(row).closest('tr'); 
 
    var tax_type_ids=document.getElementById('tax_type_id').value;
    var item_code = $(this).val();
      // get the row
    
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('GSTPER') }}",
       data:{item_code:item_code,tax_type_id:tax_type_ids},
        success: function(response){

             console.log(response); 
            
            if(tax_type_ids==1)
            {
                row.find('input[name^="pur_cgsts[]"]').val(response.data[0]['cgst_per']);
                row.find('input[name^="pur_sgsts[]"]').val(response.data[0]['sgst_per']);
                row.find('input[name^="pur_igsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id']);
                row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+response.data[0]['item_image_path']);
                row.find('input[name^="moq[]"]').val(response.data[0]['moq']);
                row.find('input[name^="stock[]"]').val(response.stock[0]['Stock']);
               
               
            }
            else if(tax_type_ids==2)
            {
                row.find('input[name^="pur_igsts[]"]').val(response.data[0]['igst_per']);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id']);
                row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+response.data[0]['item_image_path']);
                row.find('input[name^="moq[]"]').val(response.data[0]['moq']);
                row.find('input[name^="stock[]"]').val(response.stock[0]['Stock']);
            }
            else if(tax_type_ids==3)
            {
                row.find('input[name^="pur_igsts[]"]').val(0);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(response.data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(response.data[0]['unit_id']); 
                row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+response.data[0]['item_image_path']);
                row.find('input[name^="moq[]"]').val(response.data[0]['moq']);
                row.find('input[name^="stock[]"]').val(response.stock[0]['Stock']);
            }
      
        }
        });

} 

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


  $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
        CalculateRow($(this).closest("tr"));

        
    });



    function CalculateRow(row)
    {

        var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
        var item_rates=+row.find('input[name^="item_rates[]"]').val();
        var disc_pers=+row.find('input[name^="disc_pers[]"]').val();
        var disc_amounts=+row.find('input[name^="disc_amounts[]"]').val();
        var pur_cgsts=  +row.find('input[name^="pur_cgsts[]"]').val();
        var camts= +row.find('input[name^="camts[]"]').val();
        var pur_sgsts= +row.find('input[name^="pur_sgsts[]"]').val();
        var samts= +row.find('input[name^="samts[]"]').val();
        var pur_igsts= +row.find('input[name^="pur_igsts[]"]').val();
        var iamts= +row.find('input[name^="iamts[]"]').val();
        var amounts= +row.find('input[name^="amounts[]"]').val();
        var freight_amt= +row.find('input[name^="freight_amt[]"]').val();
        var total_amounts= +row.find('input[name^="total_amounts[]"]').val();
        var tax_type_id =document.getElementById("tax_type_id").value;
        
        
             
         if(item_qtys>0)
         {
                 Amount=item_qtys*item_rates;
                 disc_amt=(Amount*(disc_pers/100));
                 row.find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(2));
                 Amount=Amount-disc_amt;
                 row.find('input[name^="amounts[]"]').val((Amount).toFixed(2));
              
             if(pur_igsts!=0)
             {
                  Iamt=(Amount*(pur_igsts/100));
                  row.find('input[name^="iamts[]"]').val((Iamt).toFixed(2));
                  TAmount=Amount+Iamt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
             }
             else
             {
                  Camt=(Amount*(pur_cgsts/100));
                  row.find('input[name^="camts[]"]').val((Camt).toFixed(2));
                  
                  Samt=(Amount*(pur_sgsts/100));
                  row.find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                  
                  TAmount=Amount+Camt+Samt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                  
             }
             
        }
             
                  mycalc();
}

$(document).on("change", 'input[class^="Qty"] ', function (event) 
{
     var po_type_id=$('#po_type_id').val();
           if(po_type_id!=2)
          {
        var value = $(this).val();
    
             var maxLength = parseInt($(this).attr('max'));
             var minLength = parseInt($(this).attr('min')); 
    if(value>maxLength){alert('Value can not be greater than '+maxLength);}
    if ((value !== '') && (value.indexOf('.') === -1)) {
               
        $(this).val(Math.max(Math.min(value, maxLength), minLength));
    }
          }
   
});

function GetClassesList()
{
  //  cat_id=$("#bom_type").val();
    var  bom_types = $("#bom_type option:selected").map(function() {
      return this.value;
    }).get().join(",");
    
    alert(bom_types);
    
  $.ajax({
        dataType: "json",
    url: "{{ route('getClassLists') }}",
    data:{'cat_id':bom_types},
    success: function(data){
    $("#class_id").html(data.html);
    
   }
    });   
}



// function getBomDetail(){
//     var type=$("#bom_type").val();
     
//     var  bom_codes = $("#bom_code option:selected").map(function() {
//       return this.value;
//     }).get().join(",");
    
//       var  class_ids = $("#class_id option:selected").map(function() {
//       return this.value;
//     }).get().join(",");
//   // alert(bom_codes);
// // var bom_code=document.getElementById("bom_code").value;

// var tax_type_id=document.getElementById("tax_type_id").value;


// $.ajax({
// type:"GET",
// url:"{{ route('getBoMDetail') }}",
// //dataType:"json",
// data:{type:type,bom_code:bom_codes,tax_type_id:tax_type_id,class_ids:class_ids},
// success:function(response){
// console.log(response);  
//     $("#bomdis").html(response.html);
//  mycalc();
// }
// });
// }


function getBomDetail(){
    
    
    
    var po_type_id=$("#po_type_id").val();
    
    if(po_type_id!=2)
    {
   
                var type=$("#bom_type").val();
                var  bom_codes = $("#bom_code option:selected").map(function() {
                  return this.value;
                }).get().join(",");
                var  class_ids = $("#class_id option:selected").map(function() {
                return this.value;
                }).get().join(",");
                var tax_type_id=document.getElementById("tax_type_id").value;
            
             
                $.ajax({
                type:"GET",
                url:"{{ route('getBoMDetail') }}",
                //dataType:"json",
                data:{type:type,bom_code:bom_codes,tax_type_id:tax_type_id,class_ids:class_ids},
                success:function(response){
                console.log(response);  
                    $("#bomdis").html(response.html);
                 mycalc();
                }
                });
    }
    else
    {
        var class_id=$("#class_id").val();
        
        $.ajax({
        dataType:"json",
        url: "{{ route('GetClassItemList') }}",
        data:{'class_id':class_id},
        success: function(data){
        $("#item_code").html(data.html);
        }
        });
        
    }
 
}








 
 var rows=new Object();
 function setConversion(row)
 {
        rows = $(row).closest('tr'); 
        var conQty= rows.find('input[name^="conQtys[]"]').val();
        var unitIdM= rows.find('input[name^="unitIdMs[]"]').val();
        var priUnitd= rows.find('input[name^="priUnitds[]"]').val();
        var SecConQty= rows.find('input[name^="SecConQtys[]"]').val();
        var secUnitId= rows.find('input[name^="secUnitIds[]"]').val();
        var poQty= rows.find('input[name^="poQtys[]"]').val();
        var poUnitId= rows.find('input[name^="poUnitIds[]"]').val();
        var rateM= rows.find('input[name^="rateMs[]"]').val();
        var totalQty= rows.find('input[name^="totalQtys[]"]').val();
        
     
        $("#conQty").val(conQty);
        $("#unitIdM").val(unitIdM);
        $("#priUnitd").val(priUnitd);
        $("#SecConQty").val(SecConQty);
        $("#secUnitId").val(secUnitId);
        $("#poQty").val(poQty);
        $("#poUnitId").val(poUnitId);
        $("#rateM").val(rateM);
        $("#totalQty").val(totalQty);
          
    //  getFabInDetails(item_code);
     $('#modalFormSize').modal('show');
 }
 
  function closemodal()
 {
       $('#modalFormSize').modal('hide');
    //    $('#product-options').modal('hide');
 }
 
 
 
 function assignValue()
{
     var conQty= $("#conQty").val();
     var unitIdM= $("#unitIdM").val();
     var priUnitd= $("#priUnitd").val();
     var SecConQty= $("#SecConQty").val();
     var secUnitId= $("#secUnitId").val();
     var poQty= $("#poQty").val();
     var poUnitId= $("#poUnitId").val();
     var rateM= $("#rateM").val();
     var totalQty= $("#totalQty").val();
     
     
     rows.find('input[name^="conQtys[]"]').val(conQty);
     rows.find('input[name^="unitIdMs[]"]').val(unitIdM);
     rows.find('input[name^="priUnitds[]"]').val(priUnitd);
     rows.find('input[name^="SecConQtys[]"]').val(SecConQty);
     rows.find('input[name^="secUnitIds[]"]').val(secUnitId);
     rows.find('input[name^="poQtys[]"]').val(poQty);
     rows.find('input[name^="poUnitIds[]"]').val(poUnitId);
     rows.find('input[name^="rateMs[]"]').val(rateM);
     rows.find('input[name^="totalQtys[]"]').val(totalQty);
     rows.find('input[name^="item_qtys[]"]').val(totalQty);
     
     alert(totalQty);
     
        var item_qtys=+rows.find('input[name^="item_qtys[]"]').val();
        var item_rates=+rows.find('input[name^="item_rates[]"]').val();
        var disc_pers=+rows.find('input[name^="disc_pers[]"]').val();
        var disc_amounts=+rows.find('input[name^="disc_amounts[]"]').val();
        var pur_cgsts=  +rows.find('input[name^="pur_cgsts[]"]').val();
        var camts= +rows.find('input[name^="camts[]"]').val();
        var pur_sgsts= +rows.find('input[name^="pur_sgsts[]"]').val();
        var samts= +rows.find('input[name^="samts[]"]').val();
        var pur_igsts= +rows.find('input[name^="pur_igsts[]"]').val();
        var iamts= +rows.find('input[name^="iamts[]"]').val();
        var amounts= +rows.find('input[name^="amounts[]"]').val();
        var freight_amt= +rows.find('input[name^="freight_amt[]"]').val();
        var total_amounts= +rows.find('input[name^="total_amounts[]"]').val();
        var tax_type_id =document.getElementById("tax_type_id").value;
     
      
        if(item_qtys>0)
         {
            
                 Amount=item_qtys*item_rates;
                 disc_amt=(Amount*(disc_pers/100));
                 rows.find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(4));
                 Amount=Amount-disc_amt;
                 rows.find('input[name^="amounts[]"]').val((Amount).toFixed(4));
              
             if(pur_igsts!=0)
             {
                  Iamt=(Amount*(pur_igsts/100));
                  rows.find('input[name^="iamts[]"]').val((Iamt).toFixed(4));
                  TAmount=Amount+Iamt+freight_amt;
                  rows.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
             }
             else
             {
                  Camt=(Amount*(pur_cgsts/100));
                  rows.find('input[name^="camts[]"]').val((Camt).toFixed(4));
                  
                  Samt=(Amount*(pur_sgsts/100));
                  rows.find('input[name^="samts[]"]').val((Samt).toFixed(4));
                                  
                  TAmount=Amount+Camt+Samt+freight_amt;
                  rows.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(4));
                  
             }
             
        }
     
     
      mycalc();
     
     
     
     
     
     
     
     
     
   closemodal();
}
 
 
 
 function calcQty() 
{
    
    var conQty= $("#conQty").val();
    var unitIdM= $("#unitIdM").val();
    var priUnitd= $("#priUnitd").val();
    var SecConQty= $("#SecConQty").val();
    var secUnitId= $("#secUnitId").val();
    var poQty= $("#poQty").val();
    var poUnitId= $("#poUnitId").val();
    var rateM= $("#rateM").val();
    
    
    var totalQty=(parseFloat(poQty)*parseFloat(SecConQty)*parseFloat(conQty)).toFixed(2);
     
      $("#totalQty").val(totalQty);
     
}
 
 
</script>




<div class="modal fade" id="modalFormSize" role="dialog">
<div class="modal-dialog" style="margin: 1.75rem 19rem;">
<div class="modal-content" style="width: 900px;">
<!-- Modal Body -->
<div class="modal-body">
<p class="statusMsg"></p>
 
<div class="seprator-block"></div>
<h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>Unit Conversion</h6>
<hr class="light-grey-hr"/>

<div class="row">


<div id="InwardData" class="table-responsive">
    
    <table >
        <thead>
            <tr>
                <th>Conv. Qty</th>
                <th>UOM</th>
                <th>Pri. Unit</th>
                <th>Second Conv. Qty</th>
                <th>Sec. Unit</th>
                <th>PO Qty</th>
                <th>PO Unit</th>
                 <th>Rate</th>
                <th>Total Qty</th>
                
            </tr>
        </thead>
        <tbody>
            <th><input id="conQty" type="number" value="0"  onkeyup="calcQty();"/></th>
            <th>
            <select id="unitIdM" >
                @foreach($unitlist as  $rowunit)
                    <option value="{{ $rowunit->unit_id  }}"
                    >{{ $rowunit->unit_name }}</option>
                @endforeach
            </select></th>
            <th>
                <select id="priUnitd" >
                @foreach($unitlist as  $rowunit)
                    <option value="{{ $rowunit->unit_id  }}"
                    >{{ $rowunit->unit_name }}</option>
                @endforeach
            </select>
            </th> 
            <th><input id="SecConQty" type="number" value="0"  onkeyup="calcQty();"/></th>
            <th>
                <select id="secUnitId" >
                @foreach($unitlist as  $rowunit)
                    <option value="{{ $rowunit->unit_id  }}"
                    >{{ $rowunit->unit_name }}</option>
                @endforeach
            </select></th>
                
            </th>
            <th><input id="poQty" type="number" value="0"  onkeyup="calcQty();"/></th>
            <th>
                <select id="poUnitId" >
                @foreach($unitlist as  $rowunit)
                    <option value="{{ $rowunit->unit_id  }}"
                    >{{ $rowunit->unit_name }}</option>
                @endforeach
            </select></th>
                
            </th>
            <th><input id="rateM" type="number" value="0"/></th>
            <th><input id="totalQty" type="number" value="0"/></th>
        </tbody>
        
    </table>
    
    
    
    
</div>
 
</div>

 


<!-- Modal Footer -->
<div class="modal-footer">
<button type="button" class="btn btn-success" data-dismiss="modal" onclick="assignValue();">Submit</button>
<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();">Close</button>
 
</div>
</div>
</div>
</div>
</div>





@endsection