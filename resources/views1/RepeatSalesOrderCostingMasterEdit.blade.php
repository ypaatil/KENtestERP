@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Sales Order Costing Panel</h4>
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
 
@if(isset($SalesOrderCostingMasterList))
<form action="{{ route('RepeatSalesOrderCostSave') }}" method="POST" enctype="multipart/form-data">
 
@csrf


<div class="row">

 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="soc_date" class="form-label">Entry Date  </label>
        <input type="date" name="soc_date" class="form-control" id="soc_date" value="{{$SalesOrderCostingMasterList->soc_date}}" required readOnly>
 
        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $SalesOrderCostingMasterList->c_code }}">
        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="cost_type_id" class="form-label">Costing Type</label>
<select name="cost_type_id" class="form-select" id="cost_type_id" required disabled>
<option value="">--Costing Type--</option>
@foreach($CostTypeList as  $row)
{
    <option value="{{ $row->cost_type_id }}"
     {{ $row->cost_type_id == $SalesOrderCostingMasterList->cost_type_id ? 'selected="selected"' : '' }}  
    >{{ $row->cost_type_name }}</option>
}
@endforeach
</select>
 <input type="hidden" name="cost_type_id" value="{{ $SalesOrderCostingMasterList->cost_type_id }}" class="form-control" id="cost_type_id">
</div>
</div>




<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Sales Order no</label>
       <select name="sales_order_no" class="form-select select2" id="sales_order_no" required  onChange="getSalesOrderDetails(this.value);" >
<option value="">--Sales Order No--</option>
@foreach($SalesOrderList as  $rowsalesorder)
{
    <option value="{{ $rowsalesorder->tr_code }}"
     {{ $rowsalesorder->tr_code == $SalesOrderCostingMasterList->sales_order_no ? 'selected="selected"' : '' }}  
    >{{ $rowsalesorder->tr_code }}</option>
}
@endforeach
</select>
 
    </div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-select" id="Ac_code" required disabled >
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $rowbuyer)
{
    <option value="{{ $rowbuyer->ac_code }}"
     {{ $rowbuyer->ac_code == $SalesOrderCostingMasterList->Ac_code ? 'selected="selected"' : '' }}  
    >{{ $rowbuyer->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
 
   
<div class="col-md-2">
<div class="mb-3">
<label for="season_id" class="form-label">Season</label>
<select name="season_id" class="form-select" id="season_id" required disabled>
<option value="">--Season--</option>
@foreach($SeasonList as  $row)
{
    <option value="{{ $row->season_id }}"
    {{ $row->season_id == $SalesOrderCostingMasterList->season_id ? 'selected="selected"' : '' }}  
    >{{ $row->season_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="currency_id" class="form-label">Currency</label>
<select name="currency_id" class="form-select" id="currency_id" required disabled>
<option value="">--Currency--</option>
@foreach($CurrencyList as  $row)
{
    <option value="{{ $row->cur_id }}"
     {{ $row->cur_id == $SalesOrderCostingMasterList->currency_id ? 'selected="selected"' : '' }}  
    >{{ $row->currency_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 
   
 <div class="col-md-2">
<div class="mb-3">
<label for="inr_rate" class="form-label">Rate</label>
<input type="text" name="inr_rate" class="form-control" id="inr_rate" value="{{ $SalesOrderCostingMasterList->inr_rate }}" required onkeyup="calOrderRate()" readOnly>

</div>
</div>
 
 
 <div class="col-md-2">
<div class="mb-3">
<label for="exchange_rate" class="form-label">Exchange Rate</label>
<input type="number" step="any" name="exchange_rate" class="form-control" id="exchange_rate" value="{{ $SalesOrderCostingMasterList->exchange_rate }}" required readOnly onkeyup="calOrderRate()">

</div>
</div>
 
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="order_rate" class="form-label">FOB Rate (INR)</label>
        <input type="number" step="any" name="order_rate" class="form-control" id="order_rate" value="{{$SalesOrderCostingMasterList->order_rate}}" required  readonly>
    </div>
</div> 
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id"  onchange="getSubStyle(this.value)" required disabled>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
<option value="{{ $row->mainstyle_id }}"
 {{ $row->mainstyle_id == $SalesOrderCostingMasterList->mainstyle_id ? 'selected="selected"' : '' }}  
    >{{ $row->mainstyle_name }}</option>

}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-select" id="substyle_id" onchange="getStyle(this.value)" required disabled> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
     {{ $row->substyle_id == $SalesOrderCostingMasterList->substyle_id ? 'selected="selected"' : '' }}  
    >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
        
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-select" id="fg_id" required disabled>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    {{ $row->fg_id == $SalesOrderCostingMasterList->fg_id ? 'selected="selected"' : '' }}         
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{ $SalesOrderCostingMasterList->style_no }}" required readOnly>
</div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{ $SalesOrderCostingMasterList->style_description }}" required readOnly>

</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="style_description" class="form-label">SAM</label>
<input type="text" name="sam" class="form-control" id="sam" value="{{ $SalesOrderCostingMasterList->sam }}" onkeyup="calculateMfgCost(this.value);" required>

</div>
</div>
  
</div>
 
<div class="row">
    <label   class="form-label">Fabric Costing: </label>
<input type="number" value="{{count($FabricList);}}" name="cntrr1" id="cntrr1" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
    <th>SrNo</th>
    <th>Item</th>
    <th>Description</th>
    <th>Cons(Mtr/Kg)</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
    
    <th>Total Amount</th>
     
    <th>Add/Remove</th>
</tr>
</thead>
<tbody>
  @if(count($FabricList)>0)

@php $no=1; @endphp
@foreach($FabricList as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
<td> <select name="class_id[]" class="item"  id="class_id" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList as  $row)
{
    <option value="{{ $row->class_id }}"
     {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }} 
    >{{ $row->class_name }}</option>
}
@endforeach
</select></td>
  
<td> 
<input type="text"    name="description[]" value="{{$List->description}}" id="description" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumption[]" value="{{$List->consumption}}" id="consumption" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_unit[]" value="{{$List->rate_per_unit}}" id="rate_per_unit" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastage[]" value="{{$List->wastage}}" id="wastage" style="width:80px; height:30px;" required /></td> 
<td><input type="text"      name="bom_qty[]" value="{{$List->bom_qty}}" id="bom_qty" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any" class="FABRIC"   name="total_amount[]" value="{{$List->total_amount}}" id="total_amount" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
</tr>
@php $no=$no+1;  @endphp
@endforeach

@else
    
<tr>
<td><input type="text" name="id" value="1" id="id" style="width:50px;"/></td>
<td> <select name="class_id[]" class="item"  id="class_id" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList as  $row)
{
    <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
}
@endforeach
</select></td>
 
<td> 
<input type="text"    name="description[]" value="0" id="description" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastage[]" value="0" id="wastage" style="width:80px; height:30px;" required /></td> 
<td><input type="text"      name="bom_qty[]" value="0" id="bom_qty" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any" class="FABRIC"   name="total_amount[]" value="0" id="total_amount" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
</tr>

@endif
 </tbody>
 
</table>
</div>
</div>
</div>
 
 </br>
<div class="row">
    <label   class="form-label">Sewing Trims Costing: </label>
<input type="number" value="@php echo count($SewingTrimsList); @endphp" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
    <th>SrNo</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
   
    <th>Total Amount</th>
    
    <th>Add/Remove</th>
</tr>
</thead>
<tbody>
    
      @if(count($SewingTrimsList)>0)

@php $no=1; @endphp
@foreach($SewingTrimsList as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
<td> <select name="class_ids[]" class="item_sewing_trims" id="class_ids" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList2 as  $row)
{
    <option value="{{ $row->class_id }}"
     {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}  
    >{{ $row->class_name }}</option>
}
@endforeach
</select></td>

 <td> 
<input type="text"    name="descriptions[]" value="{{$List->description}}" id="descriptions" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumptions[]" value="{{$List->consumption}}" id="consumptions" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_units[]" value="{{$List->rate_per_unit}}" id="rate_per_units" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastages[]" value="{{$List->wastage}}" id="wastages" style="width:80px; height:30px;" required /></td> 
<td><input type="text"     name="bom_qtys[]" value="{{$List->bom_qty}}" id="bom_qtys" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any" class="SEWING"   name="total_amounts[]" value="{{$List->total_amount}}" id="total_amounts" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
</tr>
@php $no=$no+1;  @endphp
@endforeach

@else
     
    
<tr>
<td><input type="text" name="ids" value="1" id="id" style="width:50px;"/></td>
<td> <select name="class_ids[]" class="item_sewing_trims" id="class_ids" style="width:200px;" required>
<option value="">--Classification--</option>
@foreach($ClassList2 as  $row)
{
    <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
}
@endforeach
</select></td>
 
<td>
   
    <input type="text"    name="descriptions[]" value="0" id="descriptions" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td> 
<td><input type="text"     name="bom_qtys[]" value="0" id="bom_qtys" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
</tr>

@endif
 </tbody>
 
</table>
</div>
</div>
</div> 
 </br>
<div class="row">
    <label   class="form-label">Packing Trims Costing: </label>
<input type="number" value="@php echo count($PackingTrimsList); @endphp" name="cntrr3" id="cntrr3" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
<thead>
<tr>
    <th>SrNo</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
    
    <th>Total Amount</th>
    
    <th>Add/Remove</th>
</tr>
</thead>
<tbody>
 @if(count($PackingTrimsList)>0)

@php $no=1; @endphp
@foreach($PackingTrimsList as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
 
<td> <select name="class_idss[]" class="item_packing_trims" id="class_idss" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList3 as  $row)
{
    <option value="{{ $row->class_id }}"
     {{ $row->class_id == $List->class_id ? 'selected="selected"' : '' }}  
    >{{ $row->class_name }}</option>
}
@endforeach
</select></td>

 <td> 
<input type="text"    name="descriptionss[]" value="{{$List->description}}" id="descriptionss" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumptionss[]" value="{{$List->consumption}}" id="consumptionss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_unitss[]" value="{{$List->rate_per_unit}}" id="rate_per_unitss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastagess[]" value="{{$List->wastage}}" id="wastagess" style="width:80px; height:30px;" required /></td> 
<td><input type="text"      name="bom_qtyss[]" value="{{$List->bom_qty}}" id="bom_qtyss" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any" class="PACKING"   name="total_amountss[]" value="{{$List->total_amount}}" id="total_amountss" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone3();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
</tr>
@php $no=$no+1;  @endphp
@endforeach

@else
     
<tr>
<td><input type="text" name="idss" value="1" id="id" style="width:50px;"/></td>
<td> <select name="class_idss[]" class="item_packing_trims" id="class_idss" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList3 as  $row)
{
    <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
}
@endforeach
</select></td>
 
<td> 
    <input type="text"    name="descriptionss[]" value="0" id="descriptionss" style="width:200px; height:30px;"   /></td> 
<td><input type="number" step="any"    name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"     name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="any"   name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td> 
<td><input type="text"      name="bom_qtyss[]" value="0" id="bom_qtyss" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><input type="number" step="any"  class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone3();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
</tr>
@endif
 </tbody>
 
</table>
</div>
</div>
</div> 
  
 
 <div class="row">
   @php  
     $userId=Session::get('userId');
     if($userId==1){   @endphp
     
        <div class="col-md-3">
        <div class="mb-3">
        <label for="is_approved" class="form-label">Do you want to Approve this Costing?</label>
        <select name="is_approved" class="form-control" id="is_approved" required  >
        @foreach($ApproveMasterList as  $row)
        {
            <option value="{{ $row->approve_id }}"
            {{ $row->approve_id == $SalesOrderCostingMasterList->is_approved ? 'selected="selected"' : '' }} 
            >{{ $row->approve_yes_no }}</option>
        }
        @endforeach
        </select>
        </div>
        </div> 
    
     @php 
     }
     else
     {
     
     echo '<input type="hidden"     name="is_approved" value="'.($SalesOrderCostingMasterList->is_approved?$SalesOrderCostingMasterList->is_approved:0).'" id="is_approved" />';
     
     
     }
     @endphp
 
 
 
 
    <style>
		table{
			border-collapse: collapse;
			width: 100%;   
		}
	th,td{
		border: 1px solid black; 
		
	}
			 
	</style>
	
	
	@php 
	        $percentOffabric =($SalesOrderCostingMasterList->fabric_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOfsewing_trims_value=($SalesOrderCostingMasterList->sewing_trims_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOfpacking_trims_value=($SalesOrderCostingMasterList->packing_trims_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOfproduction_value=($SalesOrderCostingMasterList->production_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOfagent_commision_value=($SalesOrderCostingMasterList->agent_commision_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOftransaport_value=($SalesOrderCostingMasterList->transaport_value / $SalesOrderCostingMasterList->order_rate) * 100; 
	        $percentOfother_value=($SalesOrderCostingMasterList->other_value / $SalesOrderCostingMasterList->order_rate) * 100;
	        $percentOfdbk_value=($SalesOrderCostingMasterList->dbk_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOfprinting_value=($SalesOrderCostingMasterList->printing_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOfembroidery_value=($SalesOrderCostingMasterList->embroidery_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOfixd_value=($SalesOrderCostingMasterList->ixd_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        
	        $percentOfgarment_reject_value=($SalesOrderCostingMasterList->garment_reject_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOftesting_charges_value=($SalesOrderCostingMasterList->testing_charges_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOffinance_cost_value=($SalesOrderCostingMasterList->finance_cost_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOfextra_value=($SalesOrderCostingMasterList->extra_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $percentOftotal_cost_value=($SalesOrderCostingMasterList->total_cost_value / $SalesOrderCostingMasterList->order_rate) * 100;  
	        $profit_value=0.0;
        	$profit_value=  ($SalesOrderCostingMasterList->order_rate - $SalesOrderCostingMasterList->total_cost_value);
            $profitpercentage= (($profit_value / $SalesOrderCostingMasterList->order_rate) * 100);
 	@endphp
    
<table  >
  <tr>
    <th>Cost Break Up</th>
      <th>Value   </th>
    <th>% On FOB Value</th>
  </tr>
  <tr>
    <td>  <label for="fabric_value" class="form-label">Total Fabric Cost</label></td>
         <td> <input type="text" name="fabric_value" class="" id="fabric_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->fabric_value }}" required  readOnly>
      </td>  <td>   <input type="text" name="fabric_per" class="" id="fabric_per" style="width:150px;" value="{{ number_format((float)$percentOffabric, 2, '.', '') }}" required  readOnly>
         </td>
    <td id="percentoftotalfabriccost">{{ number_format((float)$percentOffabric, 2, '.', '') }}</td>
  </tr>
  <tr>
    <td> <label for="sewing_trims_value" class="form-label">Sewing Trims Cost</label></td>
        <td> <input type="text" name="sewing_trims_value" class="" id="sewing_trims_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->sewing_trims_value }}" required readOnly >
       </td>  <td>  <input type="text" name="sewing_trims_per" class="" id="sewing_trims_per" style="width:150px;" value="{{  number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}" required readOnly >
        
        </td>
    <td id="percentofsewingtrims">{{  number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}</td>
  </tr>
    <tr>
    <td>  
    <label for="packing_trims_value" class="form-label">Packing Trims Cost</label></td>
        <td> <input type="text" name="packing_trims_value"  id="packing_trims_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->packing_trims_value }}" required readOnly > 
      </td>  <td>   <input type="text" name="packing_trims_per"  id="packing_trims_per" style="width:150px;" value="{{  number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}" required readOnly ></td>
        
    <td id="percentofpacking_trims_value">{{  number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}</td>
  </tr>
    <tr>
    <td>      
    <label for="production_value" class="form-label">Manufacturing Cost</label></td>
       <td><input type="text" name="production_value" id="production_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->production_value }}" required  onchange="mycalc(); calculatepercentage();">
     </td>  <td>   <input type="text" name="production_per" id="production_per" style="width:150px;" value="{{  number_format((float)$percentOfproduction_value, 2, '.', '')  }}" required  onchange="calculate_percentage_value();">
       </td> 
    <td id="percentofproduction_value">{{  number_format((float)$percentOfproduction_value, 2, '.', '')  }}</td>
  </tr>
    <tr>
    <td>      <label for="agent_commission_value" class="form-label">Commission Cost</label> </td>
        <td><input type="text" name="agent_commission_value"  id="agent_commission_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->agent_commision_value }}" required  onchange="mycalc(); calculatepercentage();">
      </td>  <td>   <input type="text" name="agent_commission_per"  id="agent_commission_per" style="width:150px;" value="{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofagent_commission_value">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}</td>
  </tr>
     <tr>
    <td>      <label for="transport_value" class="form-label">Transport Cost</label> </td>
      <td>  <input type="text" name="transport_value" class="" id="transport_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->transaport_value }}" required  onchange="mycalc(); calculatepercentage();">
     </td>  <td>  <input type="text" name="transport_per" class="" id="transport_per" style="width:150px;" value="{{  number_format((float)$percentOftransaport_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentoftransport_value">{{  number_format((float)$percentOftransaport_value, 2, '.', '') }}</td>
  </tr>
   <tr>
    <td>       <label for="other_value" class="form-label">Over Head Cost</label>  </td>
        <td> <input type="text" name="other_value" class="" id="other_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->other_value }}" required  onchange="mycalc(); calculatepercentage();">
       </td>  <td>  <input type="text" name="other_per" class="" id="other_per" style="width:150px;" value="{{ number_format((float)$percentOfother_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofother_value">{{ number_format((float)$percentOfother_value, 2, '.', '') }}</td>
  </tr>
  
   <tr>
    <td>       <label for="dbk_value" class="form-label">Garment Washing Cost</label>  </td>
        <td> <input type="text" name="dbk_value" class="" id="dbk_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->dbk_value }}" required  onchange="mycalc(); calculatepercentage();">
       </td>  <td>  <input type="text" name="dbk_per" class="" id="dbk_per" style="width:150px;" value="{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofdbk_value">{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}</td>
  </tr>
  
  
    
  <tr>
    <td>       <label for="dbk_value" class="form-label">Printing Cost</label>  </td>
        <td> <input type="text" name="printing_value" class="" id="printing_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->printing_value }}" required  onchange="mycalc(); calculatepercentage();"></td>
        
       <td> <input type="text" name="printing_per" class="" id="printing_per" style="width:150px;" value="{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofprinting_value">{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}</td>
  </tr>
  
  
  <tr>
    <td>       <label for="dbk_value" class="form-label">Embroidery Cost</label>  </td>
        <td> <input type="text" name="embroidery_value" class="" id="embroidery_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->embroidery_value }}" required  onchange="mycalc(); calculatepercentage();"></td>
        
       <td> <input type="text" name="embroidery_per" class="" id="embroidery_per" style="width:150px;" value="{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofembroidery_value">{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}</td>
  </tr>
  
    <tr>
    <td>       <label for="ixd_value" class="form-label">IXD Cost</label>  </td>
        <td> <input type="text" name="ixd_value" class="" id="ixd_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->ixd_value }}" required  onchange="mycalc(); calculatepercentage();">  </td>
        
        <td>  <input type="text" name="ixd_per" class="" id="ixd_per" style="width:150px;" value="{{ number_format((float)$percentOfixd_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofixd_value">{{ number_format((float)$percentOfixd_value, 2, '.', '') }}</td>
  </tr>
  
   <tr>
    <td>       <label for="garment_reject_value" class="form-label">Garment Rejection %</label>  </td>
        <td> <input type="text" name="garment_reject_value" class="" id="garment_reject_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->garment_reject_value }}" required  onchange="mycalc(); calculatepercentage();">
         </td>
      <td> <input type="text" name="garment_reject_per" class="" id="garment_reject_per" style="width:150px;" value="{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofgarment_reject_per_value">{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}</td>
  </tr>
     <tr>
    <td>       <label for="testing_charges_value" class="form-label">Testing Charges</label>  </td>
        <td> <input type="text" name="testing_charges_value" class="" id="testing_charges_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->testing_charges_value }}" required  onchange="mycalc(); calculatepercentage();">
        </td>
      <td> 
        <input type="text" name="testing_charges_per" class="" id="testing_charges_per" style="width:150px;" value="{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentoftesting_charges_value">{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}</td>
  </tr>
    <tr>
    <td>      
    <label for="finance_cost_value" class="form-label">Finance Cost</label>  </td>
        <td> <input type="text" name="finance_cost_value" class="" id="finance_cost_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->finance_cost_value }}" required  onchange="mycalc(); calculatepercentage();">
        
        </td>
      <td> <input type="text" name="finance_cost_per" class="" id="finance_cost_per" style="width:150px;" value="{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentoffinance_cost_value">{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}</td>
  </tr>
  
    <tr>
    <td>      
    <label for="extra_value" class="form-label">Other Cost</label>  </td>
        <td> <input type="text" name="extra_value" class="" id="extra_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->extra_value }}" required  onchange="mycalc(); calculatepercentage();">
        </td>
      <td> 
        <input type="text" name="extra_per" class="" id="extra_per" style="width:150px;" value="{{ number_format((float)$percentOfextra_value, 2, '.', '') }}" required  onchange="calculate_percentage_value();">
       </td>
    <td id="percentofextra_value">{{ number_format((float)$percentOfextra_value, 2, '.', '') }}</td>
  </tr>
  
  
  
  
  
    <tr>
        
    <td>        <label for="total_cost_value" class="form-label">Total Cost</label> </td>
       <td>      <input type="text" name="total_cost_value" class="" id="total_cost_value" style="width:150px;" value="{{ $SalesOrderCostingMasterList->total_cost_value }}" required readOnly>
      </td>  <td>  <input type="text" name="total_cost_per" class="" id="total_cost_per" style="width:150px;" value="{{number_format((float)$percentOftotal_cost_value, 2, '.', '') }}" required readOnly>
       </td>
    <td id="percentoftotal_cost_value">{{number_format((float)$percentOftotal_cost_value, 2, '.', '') }}</td>
  </tr>
  
  
  
   <tr>
    <td>        <label for="profit_value" class="form-label">Profit</label> </td>
       <td>      <input type="text" name="profit_value" class="" id="profit_value" style="width:150px;" value="{{$profit_value}}" required readOnly>
      </td>  <td>  <input type="text" name="profit_per" class="" id="profit_per" style="width:150px;" value="{{number_format((float)$profitpercentage, 2, '.', '')}}" required readOnly>
       </td>
    <td id="percentofprofit_value"> {{number_format((float)$profitpercentage, 2, '.', '')}}</td>
  </tr>
  
</table>    
    
    
    
    
    
    
<div class="col-sm-8">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="{{ $SalesOrderCostingMasterList->narration }}" />
</div>
</div>
  
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
<a href="{{ Route('SalesOrderCosting.index') }}" class="btn btn-warning w-md">Cancel </a>
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

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>

function calOrderRate()
{
    var exchange_rate=$('#exchange_rate').val();
    var inr_rate=$('#inr_rate').val();
    var order_rate=(parseFloat(inr_rate) * parseFloat(exchange_rate)).toFixed(2);
    $('#order_rate').val(order_rate);
    
}



window.onload = function() {

  @php  
  $user_type=Session::get('user_type');  
 // echo 'alert('.$SalesOrderCostingMasterList->is_approved.');';
  if($SalesOrderCostingMasterList->is_approved==2 && $user_type!=1) {   @endphp
  $("input").prop("readonly", true);
  $("select").prop("disabled", true);
 
  @php     }   @endphp 
  mycalc();
};

 
function calculate_percentage_value()
 {
    var fabric_value=$('#fabric_value').val();
    var sewing_trims_value=$('#sewing_trims_value').val();
    var packing_trims_value=$('#packing_trims_value').val();
    
  
     var order_rate=$('#order_rate').val(); 
     
     var mfg_valuepercentage= $('#production_per').val();
     var mfg_value=(order_rate*(mfg_valuepercentage/100)).toFixed(2);
      $('#production_value').val(mfg_value);
     
      var agent_commission_per= $('#agent_commission_per').val();
     var agent_commission_value=(order_rate*(agent_commission_per/100)).toFixed(2);
      $('#agent_commission_value').val(agent_commission_value);
      
      var transport_per= $('#transport_per').val();
     var transport_value=(order_rate*(transport_per/100)).toFixed(2);
       $('#transport_value').val(transport_value);
     
     
     var other_valuepercentage= $('#other_per').val();
     var other_value=(order_rate*(other_valuepercentage/100)).toFixed(2);
     $('#other_value').val(other_value);
     
     var dbk_valuepercentage= $('#dbk_per').val();
     var dbk_value=(order_rate*(dbk_valuepercentage/100)).toFixed(2);
     $('#dbk_value').val(dbk_value);
     
        
    var dbk_value=$('#dbk_value').val();
    var production_value=$('#production_value').val();  
    var printing_value=$('#printing_value').val();
    var embroidery_value=$('#embroidery_value').val();
    var ixd_value=$('#ixd_value').val();  
        
    var TotalCost=parseFloat(fabric_value) + parseFloat(sewing_trims_value) + parseFloat(packing_trims_value) + parseFloat(production_value)+ parseFloat(dbk_value) + parseFloat(printing_value)+ parseFloat(embroidery_value);   
        
     var garment_reject_valuepercentage= $('#garment_reject_per').val();
     var garment_reject_value=(TotalCost*(garment_reject_valuepercentage/100)).toFixed(2);
     $('#garment_reject_value').val(garment_reject_value);
     
     
     var testing_charges_valuepercentage= $('#testing_charges_per').val();
     var testing_charges_value=(order_rate*(testing_charges_valuepercentage/100)).toFixed(2);
     $('#testing_charges_value').val(testing_charges_value);
     
     
     var ixd_valuepercentage= $('#ixd_per').val();
     var ixd_value=(order_rate*(ixd_valuepercentage/100)).toFixed(2);
     $('#ixd_value').val(ixd_value);
     
     
     
     var finance_cost_valuepercentage= $('#finance_cost_per').val();
     var finance_cost_value=(order_rate*(finance_cost_valuepercentage/100)).toFixed(2);
     $('#finance_cost_value').val(finance_cost_value);
     
     
     var extra_valuepercentage= $('#extra_per').val();
     var extra_value=(order_rate*(extra_valuepercentage/100)).toFixed(2);
     $('#extra_value').val(extra_value);
     
 
   // mycalc();
      calculatepercentage();
 }

function getSalesOrderDetails(sales_order_no)
{
      $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('SalesOrderDetails') }}",
            data:{'sales_order_no':sales_order_no},
            success: function(data){
            console.log(data);
             $("#season_id").val(data[0]['season_id']);
             $("#Ac_code").val(data[0]['Ac_code']);
             $("#currency_id").val(data[0]['currency_id']);
             $("#exchange_rate").val(data[0]['exchange_rate']);
             $("#inr_rate").val(data[0]['inr_rate']);
             $("#mainstyle_id").val(data[0]['mainstyle_id']);
             $("#substyle_id").val(data[0]['substyle_id']);
             $("#sam").val(data[0]['sam']);
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
             document.getElementById('style_description').disabled=true;
             document.getElementById('order_rate').disabled=true;
             document.getElementById('style_no').disabled=true;
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


function calculateMfgCost(sam)
{
      var order_rate=$('#order_rate').val();  
      var mfg_value=(sam*(3.75)).toFixed(2);
      $('#production_value').val(mfg_value);
      var production_valuepercentage= ((mfg_value / order_rate) * 100).toFixed(2);
      $('#production_per').val(production_valuepercentage);
      
      
}
 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});



$(document).on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
{
   // alert();
CalculateQtyRowPro($(this).closest("tr"));

});
function CalculateQtyRowPro(row)
{   
 var consumption=+row.find('input[name^="consumption[]"]').val();
 var wastage=+row.find('input[name^="wastage[]"]').val();
 var rate_per_unit=+row.find('input[name^="rate_per_unit[]"]').val();
 var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
 row.find('input[name^="bom_qty[]"]').val(bom_qty);
 var total_price=(bom_qty*rate_per_unit).toFixed(4);
 row.find('input[name^="bom_qty[]"]').val(bom_qty);
 row.find('input[name^="total_amount[]"]').val(total_price);
 mycalc();
 
 
   calculatepercentage();
 
 
}

 
 
function calculatepercentage()
 {
    var fabric_value=$('#fabric_value').val();
    var sewing_trims_value=$('#sewing_trims_value').val();
    var packing_trims_value=$('#packing_trims_value').val();
    
     var printing_value=$('#printing_value').val();  
     var embroidery_value=$('#embroidery_value').val();
    
     var dbk_value=$('#dbk_value').val();
    var production_value=$('#production_value').val();  
    var agent_commission_value=$('#agent_commission_value').val(); 
    var transport_value=$('#transport_value').val(); 
    var other_value=$('#other_value').val();
   
   
   
   
     var TotalCost=parseFloat(fabric_value) + parseFloat(sewing_trims_value) + parseFloat(printing_value) + parseFloat(embroidery_value)+ parseFloat(packing_trims_value)+ parseFloat(production_value)+ parseFloat(dbk_value);
     
     
     
    var garment_reject_value=$('#garment_reject_value').val();
    var testing_charges_value=$('#testing_charges_value').val();
    var finance_cost_value=$('#finance_cost_value').val();
    var extra_value=$('#extra_value').val();
  var ixd_value=$('#ixd_value').val();
 var total_cost_value=parseFloat(garment_reject_value)+parseFloat(testing_charges_value)+parseFloat(finance_cost_value)+parseFloat(extra_value)+parseFloat(dbk_value)+parseFloat(transport_value)+parseFloat(production_value)+parseFloat(other_value)+parseFloat(agent_commission_value)+parseFloat(ixd_value)+     parseFloat(sum1)+parseFloat(sum2)+parseFloat(sum3);
 $("#total_cost_value").val(total_cost_value.toFixed(2));
 var order_rate=$('#order_rate').val();   
    

var fabricpercentage= ((fabric_value / order_rate) * 100).toFixed(2);
var sewing_trimspercentage= ((sewing_trims_value / order_rate) * 100).toFixed(2);
var packing_trimspercentage= ((packing_trims_value / order_rate) * 100).toFixed(2);
var production_valuepercentage= ((production_value / order_rate) * 100).toFixed(2);
var gent_commissionpercentage= ((agent_commission_value / order_rate) * 100).toFixed(2);
var transport_valuepercentage= ((transport_value / order_rate) * 100).toFixed(2);
var other_valuepercentage= ((other_value / order_rate) * 100).toFixed(2);

var dbk_valuepercentage= ((dbk_value / order_rate) * 100).toFixed(2);
var printing_valuepercentage=((printing_value / order_rate) * 100).toFixed(2);
var embroidery_valuepercentage=((embroidery_value / order_rate) * 100).toFixed(2);
var garment_rejectpercentage= ((garment_reject_value / TotalCost) * 100).toFixed(2);
var testing_charges_valuepercentage= ((testing_charges_value / order_rate) * 100).toFixed(2);
var finance_cost_valuepercentage= ((finance_cost_value / order_rate) * 100).toFixed(2);
var extra_valuepercentage= ((extra_value / order_rate) * 100).toFixed(2);
var ixd_valuepercentage= ((ixd_value / order_rate) * 100).toFixed(2);
var total_costpercentage= ((total_cost_value / order_rate) * 100).toFixed(2);
var profit_value=  (order_rate - total_cost_value).toFixed(2);
var profitpercentage= ((profit_value / order_rate) * 100).toFixed(2);


$('#profit_value').val(profit_value);
$('#profit_per').val(profitpercentage);
$('#fabric_per').val(fabricpercentage);
$('#sewing_trims_per').val(sewing_trimspercentage);
$('#packing_trims_per').val(packing_trimspercentage);
$('#production_per').val(production_valuepercentage); 
$('#agent_commission_per').val(gent_commissionpercentage); 
$('#transport_per').val(transport_valuepercentage); 
$('#other_per').val(other_valuepercentage);
$('#dbk_per').val(dbk_valuepercentage);
$('#printing_per').val(printing_valuepercentage);
$('#embroidery_per').val(embroidery_valuepercentage);
$('#ixd_per').val(ixd_valuepercentage);
$('#garment_reject_per').val(garment_rejectpercentage);
$('#testing_charges_per').val(testing_charges_valuepercentage);
$('#finance_cost_per').val(finance_cost_valuepercentage);
$('#extra_per').val(extra_valuepercentage);
$('#total_cost_per').val(total_costpercentage);
$('#percentoftotalfabriccost').html(fabricpercentage);
$('#percentofsewingtrims').html(sewing_trimspercentage);
$('#percentofpacking_trims_value').html(packing_trimspercentage);
$('#percentofprofit_value').html(profitpercentage);
     
 }
 


$('table.footable_3').on('keyup', 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"]', function()
{CalculateQtyRowPros($(this).closest("tr"));});
function CalculateQtyRowPros(row)
{   
 var consumption=+row.find('input[name^="consumptions[]"]').val();
 var wastage=+row.find('input[name^="wastages[]"]').val();
 var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
 var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
 row.find('input[name^="bom_qtys[]"]').val(bom_qty);
 var total_price=(bom_qty*rate_per_unit).toFixed(4);
 row.find('input[name^="bom_qtys[]"]').val(bom_qty);
 
 row.find('input[name^="total_amounts[]"]').val(total_price);
 mycalc();
 
   // calculatepercentage();
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
 var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
  
 row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
 var total_price=(bom_qty*rate_per_unit).toFixed(4);
 row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
 
 row.find('input[name^="total_amountss[]"]').val(total_price);
 mycalc();
    calculatepercentage();
 
}

 
 
var indexcone = 2;
function insertcone1(){

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
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#class_id"),
y = x.clone();
y.attr("id","class_id");
y.attr("name","class_id[]");
y.width(200);
y.appendTo(cell3);
   
  
var cell5 = row.insertCell(2);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "description"+indexcone;
t5.name="description[]";
cell5.appendChild(t5); 
 
 
 
var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumption"+indexcone;
t5.name="consumption[]";
cell5.appendChild(t5);  
 
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unit"+indexcone;
t5.name="rate_per_unit[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastage"+indexcone;
t5.name="wastage[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qty"+indexcone;
t5.name="bom_qty[]";
cell5.appendChild(t5);
 
 
 
 
var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="FABRIC";
t5.readOnly=true;
t5.id = "total_amount"+indexcone;
t5.name="total_amount[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(8);

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
var row = $('#footable_2').find('tr').eq(indexcone);

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
var x = $("#class_ids"),
y = x.clone();
y.attr("id","class_ids");
y.attr("name","class_ids[]");
y.width(200);
y.appendTo(cell3);
  
   
var cell5 = row.insertCell(2);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptions"+indexcone1;
t5.name="descriptions[]";
cell5.appendChild(t5); 
  
var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptions"+indexcone1;
t5.name="consumptions[]";
cell5.appendChild(t5);  
 
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_units"+indexcone1;
t5.name="rate_per_units[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastages"+indexcone1;
t5.name="wastages[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtys"+indexcone1;
t5.name="bom_qtys[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="SEWING";
t5.readOnly=true;
t5.id = "total_amounts"+indexcone1;
t5.name="total_amounts[]";
cell5.appendChild(t5); 

 
 
var cell6=row.insertCell(8);

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
var x = $("#class_idss"),
y = x.clone();
y.attr("id","class_idss");
y.attr("name","class_idss[]");
y.width(200);
y.appendTo(cell3);
  
   
var cell5 = row.insertCell(2);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptionss"+indexcone2;
t5.name="descriptionss[]";
cell5.appendChild(t5); 
  
var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptionss"+indexcone2;
t5.name="consumptionss[]";
cell5.appendChild(t5);  
 
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unitss"+indexcone2;
t5.name="rate_per_unitss[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastagess"+indexcone2;
t5.name="wastagess[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtyss"+indexcone2;
t5.name="bom_qtyss[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="PACKING";
t5.readOnly=true;
t5.id = "total_amountss"+indexcone2;
t5.name="total_amountss[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(8);

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
var amounts = document.getElementsByClassName('FABRIC');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("fabric_value").value = sum1.toFixed(2);

//alert(sum1);

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
 
  var order_rate=$('#order_rate').val();  
 
 
var fabricpercentage= ((sum1.toFixed(2) / order_rate) * 100).toFixed(2);
var sewing_trimspercentage= ((sum2.toFixed(2) / order_rate) * 100).toFixed(2);
var packing_trimspercentage= ((sum3.toFixed(2) / order_rate) * 100).toFixed(2);
     
     $('#fabric_per').val(fabricpercentage);
     $('#sewing_trims_per').val(sewing_trimspercentage);
     $('#packing_trims_per').val(packing_trimspercentage);
  

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