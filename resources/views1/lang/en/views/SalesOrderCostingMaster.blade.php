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
 
<form action="{{route('SalesOrderCosting.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">

 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="soc_date" class="form-label">Entry Date</label>
        <input type="date" name="soc_date" class="form-control" id="soc_date" value="{{date('Y-m-d')}}" required readOnly>
        @foreach($counter_number as  $row)
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
@endforeach
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="cost_type_id" class="form-label">Costing Type</label>
<select name="cost_type_id" class="form-select" id="cost_type_id" required>
<option value="">--Costing Type--</option>
@foreach($CostTypeList as  $row)
{
    <option value="{{ $row->cost_type_id }}">{{ $row->cost_type_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Sales Order no</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="sales_order_no" class="form-select" id="sales_order_no" required  onChange="getSalesOrderDetails(this.value);">
<option value="">--Sales Order No--</option>
@foreach($SalesOrderList as  $row)
{
    <option value="{{ $row->tr_code }}">{{ $row->tr_code }}</option>
}
@endforeach
</select>
    </div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-select" id="Ac_code" required  >
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
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
    <option value="{{ $row->season_id }}">{{ $row->season_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="currency_id" class="form-label">Currency</label>
<select name="currency_id" class="form-select" id="currency_id" required>
<option value="">--Currency--</option>
@foreach($CurrencyList as  $row)
{
    <option value="{{ $row->cur_id }}">{{ $row->currency_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="order_rate" class="form-label">FOB Rate</label>
        <input type="number" name="order_rate" class="form-control" id="order_rate" value="" required>
    </div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
<option value="{{ $row->mainstyle_id }}"
    >{{ $row->mainstyle_name }}</option>

}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-select" id="substyle_id" onchange="getStyle(this.value)" required> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
   >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
     
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-select" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="" required>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="" required>

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="exchange_rate" class="form-label">Exchange Rate</label>
<input type="text" name="exchange_rate" class="form-control" id="exchange_rate" value="" required>

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="style_description" class="form-label">SAM</label>
<input type="text" name="sam" class="form-control" id="sam" value="" required>

</div>
</div>

</div> 
 
<div class="row">
    <label   class="form-label">Fabric Costing: </label>
<input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
    <th>SrNo</th>
    <th>Classification</th>
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
    <input type="text"    name="description[]" value="0" id="description" style="width:200px;height:30px;" required />
</td> 
<td><input type="number" step="0.01"    name="consumption[]" value="0" id="consumption" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="0.01"     name="rate_per_unit[]" value="0" id="rate_per_unit" style="width:80px;height:30px;" required /></td> 
<td><input type="number" step="0.01"   name="wastage[]" value="0" id="wastage" style="width:80px;height:30px;" required /></td> 
<td><input type="text"      name="bom_qty[]" value="0" id="bom_qty" style="width:80px;height:30px;" required  readOnly/></td> 
 
<td><input type="number" step="0.01" class="FABRIC"   name="total_amount[]" value="0" id="total_amount" style="width:80px;height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone1();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
</tr>
 </tbody>
 
</table>
</div>
</div>
</div>
 
 </br>
<div class="row">
    <label   class="form-label">Sewing Trims Costing: </label>
<input type="number" value="1" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
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
<tr>
<td><input type="text" name="ids" value="1" id="id" style="width:50px;"/></td>
<td> <select name="class_ids[]" class="item_sewing_trims" id="class_ids" style="width:200px; height:30px;" required>
<option value="">--Classification--</option>
@foreach($ClassList2 as  $row)
{
    <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
}
@endforeach
</select></td>
 
<td><input type="text"    name="descriptions[]" value="0" id="descriptions" style="width:200px; height:30px;" required /></td> 
<td><input type="number" step="0.01"    name="consumptions[]" value="0" id="consumptions" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="0.01"     name="rate_per_units[]" value="0" id="rate_per_units" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="0.01"   name="wastages[]" value="0" id="wastages" style="width:80px; height:30px;" required /></td> 
<td><input type="text"      name="bom_qtys[]" value="0" id="bom_qtys" style="width:80px; height:30px;" required readOnly /></td> 
 
<td><input type="number" step="0.01"  class="SEWING"  name="total_amounts[]" value="0" id="total_amounts" style="width:80px; height:30px;" required readOnly/></td> 
 
<td><button type="button" onclick="insertcone2();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone2(this);" value="X" ></td>
</tr>
 </tbody>
 
</table>
</div>
</div>
</div> 
 </br>
<div class="row">
    <label   class="form-label">Packing Trims Costing: </label>
<input type="number" value="1" name="cntrr3" id="cntrr3" readonly="" hidden="true"  />
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
 
<td> <input type="text"    name="descriptionss[]" value="0" id="descriptionss" style="width:200px; height:30px;" required /></td> 
<td><input type="number" step="0.01"    name="consumptionss[]" value="0" id="consumptionss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="0.01"     name="rate_per_unitss[]" value="0" id="rate_per_unitss" style="width:80px; height:30px;" required /></td> 
<td><input type="number" step="0.01"   name="wastagess[]" value="0" id="wastagess" style="width:80px; height:30px;" required /></td> 
<td><input type="text"     name="bom_qtyss[]" value="0" id="bom_qtyss" style="width:80px; height:30px;" required  readOnly/></td> 
 
<td><input type="number" step="0.01"  class="PACKING"  name="total_amountss[]" value="0" id="total_amountss" style="width:80px; height:30px;" required readOnly /></td> 
 
<td><button type="button" onclick="insertcone3();" class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone3(this);" value="X" ></td>
</tr>
 </tbody>
 
</table>
</div>
</div>
</div> 
 
     <style>
		table{
			border-collapse: collapse;
			width: 100%;   
		}
	th,td{
		border: 2px solid black; 
		
	}
			 
	</style>
 
<!-- end row -->
<div class="row">
    
<table>
  <tr>
    <th>Cost Break Up</th>
      <th>Value</th>
    <th>% On FOB Value</th>
  </tr>
  <tr>
    <td>  <label for="fabric_value" class="form-label">Total Fabric Cost</label></td>
         <td> <input type="text" name="fabric_value" class="" id="fabric_value" style="width:150px;" value="" required  readOnly>
         <input type="text" name="fabric_per" class="" id="fabric_per" style="width:150px;" value="" required  readOnly>
         </td>
    <td id="percentoftotalfabriccost"></td>
  </tr>
  <tr>
    <td> <label for="sewing_trims_value" class="form-label">Sewing Trims Cost</label></td>
        <td> <input type="text" name="sewing_trims_value" class="" id="sewing_trims_value" style="width:150px;" value="" required readOnly >
        <input type="text" name="sewing_trims_per" class="" id="sewing_trims_per" style="width:150px;" value="" required readOnly >
        </td>
    <td id="percentofsewingtrims"></td>
  </tr>
    <tr>
    <td>  
    <label for="packing_trims_value" class="form-label">Packing Trims Cost</label></td>
        <td> <input type="text" name="packing_trims_value"  id="packing_trims_value" style="width:150px;" value="" required readOnly >
        <input type="text" name="packing_trims_per"  id="packing_trims_per" style="width:150px;" value="" required readOnly >
        </td>
    <td id="percentofpacking_trims_value"></td>
  </tr>
    <tr>
    <td>      
    <label for="po_date" class="form-label">Manufacturing Cost</label></td>
       <td><input type="text" name="production_value" id="production_value" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       <input type="text" name="production_per" id="production_per" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       </td>
    <td id="percentofproduction_value"></td>
  </tr>
    <tr>
    <td>      <label for="agent_commission_value" class="form-label">Commission Cost</label> </td>
        <td><input type="text" name="agent_commission_value"  id="agent_commission_value" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
        <input type="text" name="agent_commission_per"  id="agent_commission_per" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       </td>
    <td id="percentofagent_commission_value"></td>
  </tr>
     <tr>
    <td>      <label for="transport_value" class="form-label">Transport Cost</label> </td>
      <td>  <input type="text" name="transport_value" class="" id="transport_value" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       <input type="text" name="transport_per" class="" id="transport_per" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
      
       </td>
    <td id="percentoftransport_value"></td>
  </tr>
   <tr>
    <td>       <label for="other_value" class="form-label">Over Head Cost</label>  </td>
        <td> <input type="text" name="other_value" class="" id="other_value" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
        <input type="text" name="other_per" class="" id="other_per" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       </td>
    <td id="percentofother_value"></td>
  </tr>
  
   <tr>
    <td>       <label for="dbk_value" class="form-label">DBK</label>  </td>
        <td> <input type="text" name="dbk_value" class="" id="dbk_value" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
        
        <input type="text" name="dbk_per" class="" id="dbk_per" style="width:150px;" value="" required  onkeyup="mycalc(); calculatepercentage();">
       </td>
    <td id="percentofdbk_value"></td>
  </tr>
  
    <tr>
    <td>        <label for="total_cost_value" class="form-label">Total Cost</label> </td>
       <td>      <input type="text" name="total_cost_value" class="" id="total_cost_value" style="width:150px;" value="" required readOnly>
       <input type="text" name="total_cost_per" class="" id="total_cost_per" style="width:150px;" value="" required readOnly>
       </td>
    <td id="percentoftotal_cost_value"></td>
  </tr>
  
  
   <tr>
    <td>        <label for="profit_value" class="form-label">Profit</label> </td>
       <td>      <input type="text" name="profit_value" class="" id="profit_value" style="width:150px;" value="" required readOnly>
       </td>
    <td id="percentofprofit_value"></td>
  </tr>
  
</table>        
    
    
    
    
    
    
<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Total Fabric Cost</label>-->
<!--        <input type="text" name="fabric_value" class="form-control" id="fabric_value" value="" required  readOnly>-->
       
<!--    </div>-->
<!--</div>-->

<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Sewing Trims Cost</label>-->
<!--        <input type="text" name="sewing_trims_value" class="form-control" id="sewing_trims_value" value="" required readOnly >-->
       
<!--    </div>-->
<!--</div>-->


<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Packing Trims Cost</label>-->
<!--        <input type="text" name="packing_trims_value" class="form-control" id="packing_trims_value" value="" required readOnly >-->
       
<!--    </div>-->
<!--</div>-->


<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Manufacturing Cost</label>-->
<!--        <input type="text" name="production_value" class="form-control" id="production_value" value="" required  onkeyup="mycalc();">-->
       
<!--    </div>-->
<!--</div>-->
 
<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Commission Cost</label>-->
<!--        <input type="text" name="agent_commission_value" class="form-control" id="agent_commission_value" value="" required  onkeyup="mycalc();">-->
       
<!--    </div>-->
<!--</div>-->

<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Transport Cost</label>-->
<!--        <input type="text" name="transport_value" class="form-control" id="transport_value" value="" required  onkeyup="mycalc();">-->
       
<!--    </div>-->
<!--</div>-->

<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Misc. Cost</label>-->
<!--        <input type="text" name="other_value" class="form-control" id="other_value" value="" required  onkeyup="mycalc();">-->
       
<!--    </div>-->
<!--</div>-->

<!--<div class="col-md-2">-->
<!--    <div class="mb-3">-->
<!--        <label for="po_date" class="form-label">Total Cost</label>-->
<!--        <input type="text" name="total_cost_value" class="form-control" id="total_cost_value" value="" required readOnly>-->
       
<!--    </div>-->
<!--</div>     -->
    
    
    
<div class="col-sm-8">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="" />
</div>
</div>
  
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
<a href="{{ Route('SalesOrderCosting.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>

</div>

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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>


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

$('table.footable_2').on('keyup', 'input[name^="consumption[]"],input[name^="wastage[]"],input[name^="rate_per_unit[]"],input[name^="bom_qty[]"]', function()
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
 var total_price=(bom_qty*rate_per_unit).toFixed(2);
 row.find('input[name^="bom_qty[]"]').val(bom_qty);
 row.find('input[name^="total_amount[]"]').val(total_price);
 mycalc();
 
}

 
 
function calculatepercentage()
 {
    var fabric_value=$('#fabric_value').val();
    var sewing_trims_value=$('#sewing_trims_value').val();
    var packing_trims_value=$('#packing_trims_value').val();
    var production_value=$('#production_value').val();  
    var agent_commission_value=$('#agent_commission_value').val(); 
    var transport_value=$('#transport_value').val(); 
    var other_value=$('#other_value').val();
    var dbk_value=$('#dbk_value').val();
 
 var total_cost_value=parseFloat(dbk_value)+parseFloat(transport_value)+parseFloat(production_value)+parseFloat(other_value)+parseFloat(agent_commission_value)+parseFloat(sum1)+parseFloat(sum2)+parseFloat(sum3);
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
     $('#total_cost_per').val(total_costpercentage);
    
     
    $('#percentoftotalfabriccost').html(fabricpercentage);
     $('#percentofsewingtrims').html(sewing_trimspercentage);
      $('#percentofpacking_trims_value').html(packing_trimspercentage);
       $('#percentofproduction_value').html(production_valuepercentage);
        $('#percentofagent_commission_value').html(gent_commissionpercentage);
         $('#percentoftransport_value').html(transport_valuepercentage);
          $('#percentofother_value').html(other_valuepercentage);
            $('#percentoftotal_cost_value').html(total_costpercentage);
            $('#percentofdbk_value').html(dbk_valuepercentage);
            $('#percentofprofit_value').html(profitpercentage);
    
    
    
    
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

$('table.footable_3').on('keyup', 'input[name^="consumptions[]"],input[name^="wastages[]"],input[name^="rate_per_units[]"],input[name^="bom_qtys[]"]', function()
{CalculateQtyRowPros($(this).closest("tr"));});
function CalculateQtyRowPros(row)
{   
 var consumption=+row.find('input[name^="consumptions[]"]').val();
 var wastage=+row.find('input[name^="wastages[]"]').val();
 var rate_per_unit=+row.find('input[name^="rate_per_units[]"]').val();
 var bom_qty=(consumption + (consumption*(wastage/100))).toFixed(4);
 row.find('input[name^="bom_qtys[]"]').val(bom_qty);
 var total_price=(bom_qty*rate_per_unit).toFixed(2);
 row.find('input[name^="bom_qtys[]"]').val(bom_qty);
 
 row.find('input[name^="total_amounts[]"]').val(total_price);
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
 var total_price=(bom_qty*rate_per_unit).toFixed(2);
 row.find('input[name^="bom_qtyss[]"]').val(bom_qty);
 
 row.find('input[name^="total_amountss[]"]').val(total_price);
 mycalc();
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
 
 
 var transport_value=$("#transport_value").val();
 var production_value=$("#production_value").val();
 var other_value=$("#other_value").val();
 var dbk_value=$("#dbk_value").val();
 var agent_commission_value=$("#agent_commission_value").val();
 var total_cost_value=parseFloat(dbk_value)+parseFloat(transport_value)+parseFloat(production_value)+parseFloat(other_value)+parseFloat(agent_commission_value)+parseFloat(sum1)+parseFloat(sum2)+parseFloat(sum3);
 $("#total_cost_value").val(total_cost_value.toFixed(2));
 
 calculatepercentage();
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