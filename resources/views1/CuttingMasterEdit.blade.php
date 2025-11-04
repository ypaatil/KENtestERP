@extends('layouts.master') 

@section('content')
 
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
    
@if(isset($CuttingMasterList))
<form action="{{ route('FabricCutting.update',$CuttingMasterList) }}" method="POST" enctype="multipart/form-data">
@method('put')

@csrf 
<span style="float:right;"><button type="submit" class="btn btn-primary w-md" id="Submit"   onclick="EnableFields(); mycalc();">Submit</button> <a href="{{ Route('FabricCutting.index') }}" class="btn btn-warning w-md">Cancel</a></span>
<h4 class="card-title mb-4">Cutting Task: <label class="form-label" id="lbl_lot_no"></label></h4>

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
        <label for="cu_date" class="form-label"> Date</label>
        <input type="date" name="cu_date"  class="form-control" id="cu_date" value="{{ $CuttingMasterList->cu_date }}"> 
        <input type="hidden" name="lot_no" class="form-control" id="lot_no" value="{{ $CuttingMasterList->lot_no }}" >
         
        <input type="hidden" name="cu_code" class="form-control" id="cu_code" value="{{ $CuttingMasterList->cu_code }}">
        <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $CuttingMasterList->c_code }}">
     
        <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>
 
 <div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Table</label>
<select name="table_id" class="form-select" id="table_id" required onchange="getTaskData(this.value);">
<option value="">Table</option>
@foreach($TableList as  $row)
{
    <option value="{{ $row->table_id }}"
    {{ $row->table_id == $CuttingMasterList->table_id ? 'selected="selected"' : '' }}     
    >{{ $row->table_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Table Task</label>
<select name="table_task_code" class="form-select" id="table_task_code" required onchange="getCheckMasterData(this.value);" disabled>
<option value="">--TASK--</option>
 @foreach($TaskList as  $row)
{
    <option value="{{ $row->task_id }}"
    {{ $row->task_id == $CuttingMasterList->table_task_code ? 'selected="selected"' : '' }}     
    >{{ $row->task_id }}</option>
}
@endforeach
</select>
</div>
</div> 
 
 
   <div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required disabled>
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}"
     {{ $rowvendor->ac_code == $CuttingMasterList->vendorId ? 'selected="selected"' : '' }}    
    >{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 
 
 
 
  

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required disabled> 
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
     {{ $row->mainstyle_id == $CuttingMasterList->mainstyle_id ? 'selected="selected"' : '' }}   
    >{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required disabled> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
     {{ $row->substyle_id == $CuttingMasterList->substyle_id ? 'selected="selected"' : '' }}  
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
     {{ $row->fg_id == $CuttingMasterList->fg_id ? 'selected="selected"' : '' }}  
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{ $CuttingMasterList->style_no }}" required readOnly>
<input type="hidden" name="vpo_code" class="form-control" id="vpo_code" value="{{ $CuttingMasterList->vpo_code }}" required>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{ $CuttingMasterList->style_description }}" readOnly required>

</div>
</div>


</div>
<div class="row">
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="table_avg" class="form-label">Table Average</label>
        <input type="text" name="table_avg" class="form-control" id="table_avg" value="{{ $CuttingMasterList->table_avg }}" readonly required>
    </div>
</div>

 
<div class="col-md-2">
    <div class="mb-3">
        <label for="track_code" class="form-label">Scan Barcode</label>
        <input type="text" name="track_code" class="form-control" id="track_code" value=""  onfocusout="getCheckingFabricdata(1);" >
    </div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="item_code" class="form-label">Item</label>
<select name="item_code" class="form-select" id="item_code"  disabled>
<option value="">--Item List--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select>
</div>
</div>
 

<div class="col-md-2">
    <div class="mb-3">
        <label for="width" class="form-label">Width</label>
        <input type="text" name="width" class="form-control" id="width" value="" readOnlyreadOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="meter" class="form-label">Meter</label>
        <input type="text" name="meter" class="form-control" id="meter" value="" readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="layers" class="form-label">Layers</label>
        <input type="text"  class="form-control" id="layers" value="" onchange="delete_Row(); deleteEndDataRow();getCheckingFabricdata(2); " readOnly  >
    </div>
</div>

</div>

 
<div class="row">
 
<input type="number" value="{{ count($CuttingBalanceDetailList) }}" name="cntrr2" id="cntrr2" readonly="" hidden="true"  />
<label   class="form-label"><b>2. Comsuption/Cut Piece/Damage Meter:</b></label>
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th>SrNo</th>
<th>Track Code</th>
<th>Item</th>
<th>Width</th>
<th>Meter</th>
<th>Shade</th>
<th>Layers</th>
<th>Used Meter</th>
<th>Balance</th>
<th>Cut Piece Meter</th>
<th>Damage Meter</th>
<th>Short Meter</th>
<th>Extra Meter</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</thead>
<tbody id="endData">
@if(count($CuttingBalanceDetailList)>0)

@php $no=1; @endphp
@foreach($CuttingBalanceDetailList as $List) 

 <tr class="thisRow"> 
   <td><input type="text" name="id[]" value="@php echo $no; @endphp" id="id" style="width:50px;height:30px;"/></td>
   <td><input type="text" name="track_codess[]" class="track_code" id="track_codes@php echo $no; @endphp" value="{{ $List->track_code }}" style="width:80px;height:30px;" readOnly required/> 
   <input type="hidden" name="part_idss[]" id="part_idss@php echo $no; @endphp" value="{{ $List->part_id }}" style="width:80px;height:30px;" required/>
   </td> 
   <td> <select name="item_codes[]"  id="item_codes@php echo $no; @endphp" style="width:200px;height:30px;" required disabled>
   <option value="">--Item List--</option>';
   @foreach($ItemList as  $rowitem)
   { 
       <option value="{{ $rowitem->item_code }}"
    {{ $rowitem->item_code == $List->item_code ? 'selected="selected"' : '' }}     
    >{{ $rowitem->item_name }}</option>
    }
    @endforeach
   </select></td>
    <td><input type="text" name="widths[]" id="width@php echo $no; @endphp" value="{{ $List->width }}" style="width:80px;height:30px;" required readOnly/> </td>
    <td><input type="text" name="meters[]" id="meter@php echo $no; @endphp" value="{{ $List->meter }}" style="width:80px;height:30px;" required readOnly/> </td>
     <td> <select name="shade_ids[]"  id="shade_ids@php echo $no; @endphp" style="width:100px;height:30px;" required disabled>
   <option value="">--Shade List--</option>';
   @foreach($ShadeList as  $rowshade)
   { 
       <option value="{{ $rowshade->shade_id }}"
    {{ $rowshade->shade_id == $List->shade_id ? 'selected="selected"' : '' }}     
    >{{ $rowshade->shade_name }}</option>
    }
    @endforeach
   </select></td>
    
    <td><input type="text" name="layerss[]" class="Layers" id="layerss@php echo $no; @endphp" value="{{ $List->layers }}" style="width:80px;height:30px;" required/> </td>
    <td><input type="text" name="used_meters[]" class="UMETER" id="used_meters@php echo $no; @endphp" value="{{ $List->used_meter }}" style="width:80px;height:30px;" readOnly required/> </td>  
    <td><input type="text" onkeyup="mycalc();" name="bpiece_meters[]" id="bpiece_meters@php echo $no; @endphp" value="{{ $List->balance_meter }}" style="width:80px;height:30px;" required/> </td>
    <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();" class="cPiece" id="cpiece_meters@php echo $no; @endphp" value="{{ $List->cpiece_meter }}" style="width:80px;height:30px;" required/> </td> 
    <td><input type="text" name="dpiece_meters[]" onkeyup="mycalc();"  class="dPiece" id="dpiece_meters@php echo $no; @endphp" value="{{ $List->dpiece_meter }}" style="width:80px;height:30px;" required/> </td> 
    <td><input type="text" name="short_meters[]"  id="short_meters@php echo $no; @endphp" class="SPiece"  value="{{ $List->short_meter }}" style="width:80px;height:30px;" required/> </td>
    <td><input type="text" name="extra_meters[]" onkeyup="mycalc();"  class="EPiece" id="extra_meters@php echo $no; @endphp"   value="{{ $List->extra_meter }}" style="width:80px;height:30px;" required/> </td> 
    
    <td><input type="button" class="btn btn-danger pull-left" onclick="deleteEndDataRow2('{{ $List->track_code }}'); delete_Row2('{{ $List->track_code }}');" value="X" ></td>
    
</tr> 
     
@php $no=$no+1;  @endphp
@endforeach
 @endif
</tbody>
<tfoot>
<tr>
<th>SrNo</th>
<th>Track Code</th>
<th>Item</th>
<th>Width</th>
<th>Meter</th>
<th>Shade</th>
<th>Layers</th>
<th>Used Meter</th>
<th>Balance</th>
<th>Cut Piece Meter</th>
<th>Damage Meter</th>
<th>Short Meter</th>
<th>Extra Meter</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>

<div class="row">
<input type="number" value="{{ count($CuttingDetailList) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<label  class="form-label"><b>1. Size/Qty:</b></label>
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>Track Code</th>
<th>Color</th>
<th>Width</th>
<th>Meter</th>
<th>Shade</th>
<th>Size</th>
<th>Ratio</th>
<th>Layers</th>
<th>Qty</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</thead>
<tbody id="ratioData">
@if(count($CuttingDetailList)>0)

@php $no=1; @endphp
@foreach($CuttingDetailList as $List) 

 <tr class="thisRow"> 
    
   <td><input type="text" name="id[]" value="@php echo $no; @endphp" id="id" style="width:50px;height:30px;" readOnly/></td>
   <td>
   <input type="text" name="track_codes[]" class="track_code" id="track_code@php echo $no; @endphp" value="{{ $List->track_code }}" style="width:80px;height:30px;" readOnly required/>
   <input type="hidden" name="part_ids[]" id="part_ids@php echo $no; @endphp" value="{{ $List->part_id }}" style="width:80px;height:30px;" readOnly required/>
   </td> 
    
   <td> <select name="item_code[]"  id="item_code@php echo $no; @endphp" style="width:200px;height:30px;"  required  disabled>
   <option value="">--Item List--</option>';
   @foreach($ItemList as  $rowitem)
   { 
       <option value="{{ $rowitem->item_code }}"
    {{ $rowitem->item_code == $List->item_code ? 'selected="selected"' : '' }}     
    >{{ $rowitem->item_name }}</option>
    }
    @endforeach
   </select></td>
   
   <td>
   <input type="text" name="width[]" id="width@php echo $no; @endphp" value="{{ $List->width }}" style="width:80px;height:30px;" required readOnly/> </td>
  <td>
   <input type="text" name="meter[]" id="meter@php echo $no; @endphp" value="{{ $List->meter }}" style="width:80px;height:30px;" required readOnly/> </td>
    
       <td> <select name="shade_id[]"  id="shade_id@php echo $no; @endphp" style="width:100px;height:30px;" required disabled>
   <option value="">--Shade List--</option>';
   @foreach($ShadeList as  $rowshade)
   { 
       <option value="{{ $rowshade->shade_id }}"
    {{ $rowshade->shade_id == $List->shade_id ? 'selected="selected"' : '' }}     
    >{{ $rowshade->shade_name }}</option>
    }
    @endforeach
   </select></td>

   <td> <select name="size_id[]"  id="sz_code@php echo $no; @endphp" style="width:100px;" required disabled>
   <option value="">--Size List--</option>';
   @foreach($SizeList as  $rowfg)
   { 
       <option value="{{ $rowfg->size_id }}"
    {{ $rowfg->size_id == $List->size_id ? 'selected="selected"' : '' }}     
    >{{ $rowfg->size_name }}</option>
    }
    @endforeach
   </select></td>


   <td>
   <input type="text" name="ratio[]" id="ratio@php echo $no; @endphp" value="{{ $List->ratio }}" style="width:80px;height:30px;" readOnly required/> </td> 
   
    <td>
   <input type="text" name="layers[]" id="layers@php echo $no; @endphp" value="{{ $List->layers }}" style="width:80px;height:30px;" readOnly required/> </td> 
   
   <td><input type="text" class="QTY" onkeyup="mycalc();"  name="qty[]" id="qty@php echo $no; @endphp" value="{{ $List->qty }}" style="width:80px;height:30px;" readOnly required/></td>
   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
     </tr> 
     
@php $no=$no+1;  @endphp
@endforeach
@endif
</tbody>
<tfoot>
<tr>
<th>SrNo</th>
 <th>Track Code</th>
<th>Color</th>
<th>Width</th>
<th>Meter</th>
<th>Shade</th>
<th>Size</th>
<th>Ratio</th>
<th>Layers</th>
<th>Qty</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>

<!-- end row -->
<div class="row">

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Total Qty</label>
        <input type="text" name="total_pieces" class="form-control" id="total_qty" value="{{ $CuttingMasterList->total_pieces }}" required readOnly>
    </div>
</div>
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="total_layers" class="form-label">Total Layers</label>
        <input type="text" name="total_layers" class="form-control" id="total_layers" value="{{ $CuttingMasterList->total_layers }}" required readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_used_meter" class="form-label">Total Used Meter</label>
        <input type="text" name="total_used_meter" class="form-control" id="total_used_meter" value="{{ $CuttingMasterList->total_used_meter }}" required readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_cutpiece_meter" class="form-label">Total CutPiece</label>
        <input type="text" name="total_cutpiece_meter" class="form-control" id="total_cutpiece_meter" value="{{ $CuttingMasterList->total_cutpiece_meter }}" required readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_damage_meter" class="form-label">Total Damage</label>
        <input type="text" name="total_damage_meter" class="form-control" id="total_damage_meter" value="{{ $CuttingMasterList->total_damage_meter }}" required readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_short_meter" class="form-label">Total Short</label>
        <input type="text" name="total_short_meter" class="form-control" id="total_short_meter" value="{{ $CuttingMasterList->total_short_meter }}" required readOnly > 
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_extra_meter" class="form-label">Total Extra</label>
        <input type="text" name="total_extra_meter" class="form-control" id="total_extra_meter" value="{{ $CuttingMasterList->total_extra_meter }}" required readOnly>
    </div>
</div>

<div class="col-sm-8">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="{{ $CuttingMasterList->narration }}" />
</div>
</div>
 

 
 
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" id="Submit1"   onclick="EnableFields(); mycalc();">Submit</button>
<a href="{{ Route('FabricCutting.index') }}" class="btn btn-warning w-md">Cancel</a>
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
<!-- end row -->
<script>


$('body').on('keydown', 'input, select, textarea', function(e) {
var self = $(this)
, form = self.parents('form:eq(0)')
, focusable
, next
;
if (e.keyCode == 13) {
    
  
 var x = document.getElementById("track_code");
 
 
    if(document.getElementById('job_code').value!='' && document.getElementById('track_code').value!='' && document.hasFocus() )
    {
        
        getCheckingFabricdata(1);
         
    } else{
    
focusable = form.find('input,a,select,button,textarea').filter(':visible');
next = focusable.eq(focusable.index(this)+1);
if (next.length) {
next.focus();
} else {
form.submit();
}
return false;
}
}
});
//----------Over---------------------
$("div.table-wrap").on("keyup",'input[name^="meters[]"]', function (event) {
mycalc();
});


function EnableFields()
{
             
             document.getElementById('vpo_code').disabled=false;
            
             document.getElementById('style_description').disabled=false;
             document.getElementById('style_no').disabled=false;
                  $("select").prop('disabled', false);
}




 
 function getTaskData(val) 
{ 
    $.ajax({
    type: "GET",
    url: "{{ route('TaskList') }}",
    data:'table_id='+val,
    success: function(data){
    $("#table_task_code").html(data.html);
    }
    });
}
 
 // Main Table for size wise Qty
 function getDetails(Action12)
{  
    var table_avg=$("#table_avg").val();
    var job_code=$("#job_code").val();
    var meter=$("#meter").val();
    var table_id=$("#table_id").val();
    var layers=$("#layers").val();
    var track_code=$("#track_code").val();
    var table_task_code=$("#table_task_code").val();
    // alert("table_task_code:"+table_task_code);
    var next=0;
   // var length=$("#footable_2 tr").length;

    //var table = $("#footable_2 table tbody");
  //  table.find('tbody.ratioData').each(function() {
     $("#footable_2 tbody tr").each(function() {
    var thisRow = $(this);
    var match = thisRow.find('input[name^="track_codes[]"]').val();
    // note the `==` operator &
    if(match == track_code ){next=2;}
    
    });
 
 
  if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}

     if(next==2)
     {

        alert('Cutting of this Roll Already Done. Check Table Below..!') ;

     }
    else if(Action12==1 && next==1)
            {       
                    layers=0;
                    $.ajax({
                    type: "GET",
                    url: "{{ route('RatioList') }}",
                    data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code},
                    success: function(response){
                    $("#ratioData").append(response.html);
                    recalcIdcone(); recalcIdcone2();
                    }
                    });
            }
            else if(Action12==2 && next==1)
            {
                
                    $.ajax({
                    type: "GET",
                    url: "{{ route('RatioList') }}",
                    data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code},
                    success: function(response){
                    $("#ratioData").append(response.html);
                 recalcIdcone(); recalcIdcone2();
                    }
                    });
            }

     
}

// Main Table for Cut Piece, Damage, Used Meter
function getEndDataDetails(Action12)
{  
    var table_avg=$("#table_avg").val();
    var job_code=$("#job_code").val();
     var meter=$("#meter").val();
    // alert(meter);
    var table_id=$("#table_id").val();
     var layers=$("#layers").val();
    var track_code=$("#track_code").val();
   var table_task_code=$("#table_task_code").val();
  
     //alert(meter+",po_code:"+po_code+",table_id:"+table_id);
    var next=0;
   // var length=$("#footable_2 tr").length;

    //var table = $("#footable_2 table tbody");
  //  table.find('tbody.ratioData').each(function() {
     $("#footable_3 tbody tr").each(function() {
    var thisRow = $(this);
    var match = thisRow.find('input[name^="track_codess[]"]').val();
    // note the `==` operator &
    if(match == track_code ){next=2;}
    
    });
 
 
  if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}

     if(next==2)
     {

        alert('Cutting of this Roll Already Done. Check Table Below..!') ;

     }
    else if(Action12==1 && next==1)
            {       
                    layers=0;
                    $.ajax({
                    type: "GET",
                    url: "{{ route('EndDataList') }}",
                    data:{job_code:'job_code','meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code},
                    success: function(response){
                    $("#endData").append(response.html);
                  recalcIdcone(); recalcIdcone2();
                    }
                    });
            }
            else if(Action12==2 && next==1)
            {
                
                    $.ajax({
                    type: "GET",
                    url: "{{ route('EndDataList') }}",
                    data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers ,'task_id':table_task_code},
                    success: function(response){
                    $("#endData").append(response.html);
                  recalcIdcone(); recalcIdcone2();
                    }
                    });
            }

     
     $("#track_code").val('');
     
}

function getCheckMasterData(table_task_code)
{ 
$.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('CheckingMasterList') }}",
            //data:'table_id='+table_id,
            data:{table_task_code:table_task_code},
            success: function(data){
                
            console.log(data);     
              $("#table_avg").val(data[0]['table_avg']);
            $("#lot_no").val(data[0]['lot_no']);
            document.getElementById('lbl_lot_no').innerHTML=data[0]['lot_no'];
           $("#vendorId").val(data[0]['vendorId']);
            $("#season_id").val(data[0]['season_id']);
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#vpo_code").val(data[0]['vpo_code']);
            $("#style_description").val(data[0]['style_description']);
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('style_description').disabled=true;
             document.getElementById('style_no').disabled=true;
             document.getElementById('vendorId').disabled=true;
          
        }
        });
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
document.getElementById("total_qty").value = sum1.toFixed(2);

if(sum1.toFixed(2)>0)
{
   document.getElementById("Submit").disabled=false;
   document.getElementById("Submit1").disabled=false;
}
else if(sum1.toFixed(2)==0)
{
    document.getElementById("Submit").disabled=true;
    document.getElementById("Submit1").disabled=true;
}




sum1 = 0.0;
var amounts = document.getElementsByClassName('Layers');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_layers").value = sum1.toFixed(2);


sum1 = 0.0;
var amounts = document.getElementsByClassName('UMETER');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_used_meter").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('cPiece');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_cutpiece_meter").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('dPiece');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_damage_meter").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('SPiece');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_short_meter").value = sum1.toFixed(2);

sum1 = 0.0;
var amounts = document.getElementsByClassName('EPiece');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_extra_meter").value = sum1.toFixed(2);
  

}

// 
 
 $("table.footable_3").on("change", 'input[name^="layerss[]"]', function (event) {
        CalculateLayers($(this).closest("tr"));
    });
	

	function CalculateLayers(row)
	{ 
        var layerss=+row.find('input[name^="layerss[]"]').val();
        var track_code=row.find('input[name^="track_codess[]"]').val();
        //alert(track_code);
        var meter=+row.find('input[name^="layerss[]"]').val();
        delete_Row2(track_code); deleteEndDataRow2(track_code);getCheckingFabricdata2(2,track_code,meter,layerss);
          recalcIdcone(); recalcIdcone2();
    }
 
  function getDetails2(Action12,track_code,meter,layers)
{  
    
    // This function is for change layer in consumption table and get data
    
    var table_avg=$("#table_avg").val();
    var job_code=$("#job_code").val();
   
    var table_id=$("#table_id").val();
  
    var table_task_code=$("#table_task_code").val();
    // alert("table_task_code:"+table_task_code);
    var next=0;
   // var length=$("#footable_2 tr").length;

    //var table = $("#footable_2 table tbody");
  //  table.find('tbody.ratioData').each(function() {
     $("#footable_2 tbody tr").each(function() {
    var thisRow = $(this);
    var match = thisRow.find('input[name^="track_codes[]"]').val();
    // note the `==` operator &
    if(match == track_code ){next=2;}
    
    });
 
 
  if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}

     if(next==2)
     {

        alert('Cutting of this Roll Already Done. Check Table Below..!') ;

     }
    else if(Action12==1 && next==1)
            {       
                    layers=0;
                    $.ajax({
                    type: "GET",
                    url: "{{ route('RatioList') }}",
                    data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code},
                    success: function(response){
                    $("#ratioData").append(response.html);
                    rrecalcIdcone(); recalcIdcone2();
                    }
                    });
            }
            else if(Action12==2 && next==1)
            {
                
                    $.ajax({
                    type: "GET",
                    url: "{{ route('RatioList') }}",
                    data:{'job_code':job_code,'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers,'task_id':table_task_code},
                    success: function(response){
                    $("#ratioData").append(response.html);
                    
                    recalcIdcone(); recalcIdcone2();
                    }
                    });
            }

     
}

 
 function getEndDataDetails2(Action12,track_code,meter,layers)
{  
    var table_avg=$("#table_avg").val();
  //  var po_code=$("#po_code").val();
     
    var table_id=$("#table_id").val();
   
     //alert(meter+",po_code:"+po_code+",table_id:"+table_id);
    var next=0;
   // var length=$("#footable_2 tr").length;

    //var table = $("#footable_2 table tbody");
  //  table.find('tbody.ratioData').each(function() {
     $("#footable_3 tbody tr").each(function() {
    var thisRow = $(this);
    var match = thisRow.find('input[name^="track_codess[]"]').val();
    // note the `==` operator &
    if(match == track_code ){next=2;}
    
    });
 
 
  if(next==0){next=1;}else if(next==2 && Action12==2){next=1;}

     if(next==2)
     {

        alert('Cutting of this Roll Already Done. Check Table Below..!') ;

     }
    else if(Action12==1 && next==1)
            {       
                    layers=0;
                    $.ajax({
                    type: "GET",
                    url: "{{ route('EndDataList') }}",
                    data:{'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers},
                    success: function(response){
                    $("#endData").append(response.html);
                    recalcIdcone();
                  recalcIdcone2();
                    }
                    });
            }
            else if(Action12==2 && next==1)
            {
                
                    $.ajax({
                    type: "GET",
                    url: "{{ route('EndDataList') }}",
                    data:{'meter':meter,'table_id':table_id,'track_code':track_code,'table_avg':table_avg,'layers':layers },
                    success: function(response){
                    $("#endData").append(response.html);
                    recalcIdcone();
                      recalcIdcone2();
                    }
                    });
            }

     
     $("#track_code").val('');
     
}
 
 
 function getCheckingFabricdata2(Action12,track_code,meter,layers)
{
   // This function is for change layer in consumption table and get data
     var table_avg=$("#table_avg").val();
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('CheckingFabricList') }}",
            data:{'track_code':track_code,'table_avg':table_avg},
            success: function(data){
                 getDetails2(Action12,track_code,meter,layers);
                 getEndDataDetails2(Action12,track_code,meter,layers);
                 setTimeout(function(){  mycalc();}, 2000);
                
        }
        });
    }
 
 
 
 
 
  function getCheckingFabricdata(Action12)
{
    // This function is for Scan Barcodeand get details
   
    var layers=$("#layers").val();
     var track_code=$("#track_code").val();
    var table_avg=$("#table_avg").val();
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('CheckingFabricList') }}",
            data:{'track_code':track_code,'table_avg':table_avg},
            success: function(data){
                
            $("#item_code").val(data[0]['item_code']);
            $("#width").val(data[0]['width']);
            $("#meter").val(data[0]['meter']);
            if(Action12==1)
            {          
                 $("#layers").val(data[0]['Layers']);
            }
             
                 getDetails(Action12);
                 getEndDataDetails(Action12);
                 setTimeout(function(){  mycalc();}, 2000);
                
        }
        });
    }
 
  function delete_Row2(track_code)  {
   //$("#footable_2 tr.thisRow").each(function() {
        $("#footable_2 tbody tr").each(function() {
        var thisRow = $(this);
        var match = thisRow.find('input[name^="track_codes[]"]').val();
        // note the `==` operator
        if(match == track_code) {
            thisRow.remove(); 
            // OR thisRow.remove();
        }
    });
mycalc();
}
 
 function deleteEndDataRow2(track_code)  {
    
    $("#footable_3 tbody tr").each(function() {
        var thisRow = $(this);
        var match = thisRow.find('input[name^="track_codess[]"]').val();
        // note the `==` operator
        if(match == track_code) {
            thisRow.remove(); 
            // OR thisRow.remove();
        }
        
       
        
    });
    
    
    
    mycalc();
    

}
 
 
 function delete_Row(track_code)  {
  
    var track_code=$("#track_code").val();
    //$("#footable_2 tr.thisRow").each(function() {
        $("#footable_2 tbody tr").each(function() {
        var thisRow = $(this);
        var match = thisRow.find('input[name^="track_codes[]"]').val();
        // note the `==` operator
        if(match == track_code) {
            thisRow.remove(); 
            // OR thisRow.remove();
        }
    });

}
 
 function deleteEndDataRow()  {
    
   var track_code=$("#track_code").val();
    //$("#footable_2 tr.thisRow").each(function() {
        $("#footable_3 tbody tr").each(function() {
        var thisRow = $(this);
        var match = thisRow.find('input[name^="track_codess[]"]').val();
        // note the `==` operator
        if(match == track_code) {
            thisRow.remove(); 
            // OR thisRow.remove();
        }
    });

}
 
$("table.footable_3").on("change", 'input[name^="cpiece_meters[]"],input[name^="dpiece_meters[]"],input[name^="bpiece_meters[]"],input[name^="short_meters[]"],input[name^="extra_meters[]"]', function (event) {
        CalculateRow($(this).closest("tr"));
    });
	

	function CalculateRow(row)
	{ 
        var layerss=+row.find('input[name^="layerss[]"]').val();
        var table_avg=$('#table_avg').val();
        var meters=+row.find('input[name^="meters[]"]').val();
        var used_meters=+row.find('input[name^="used_meters[]"]').val();
		var cpiece_meters=+row.find('input[name^="cpiece_meters[]"]').val();
        var dpiece_meters=+row.find('input[name^="dpiece_meters[]"]').val();
        var short_meters=+row.find('input[name^="short_meters[]"]').val();
        var extra_meters=+row.find('input[name^="extra_meters[]"]').val();
		var bpiece_meters= meters-used_meters;
// bal : 28.92  cut:2 
         
            var bm=(bpiece_meters-cpiece_meters-dpiece_meters- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
            var cm=(bpiece_meters-bm-dpiece_meters- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
            var dm=(bpiece_meters-bm-cm- parseFloat(short_meters) + parseFloat(extra_meters)).toFixed(2);
            row.find('input[name^="cpiece_meters[]"]').val(parseFloat(cm));
            row.find('input[name^="dpiece_meters[]"]').val(parseFloat(dm));
            if(bm>=0){row.find('input[name^="bpiece_meters[]"]').val(parseFloat(bm));}
            else{ alert('Balance Meter can not less than Zero..!!');}
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
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
 
 
function recalcIdcone2(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
} 
 

</script>

<!-- end row -->
@endsection