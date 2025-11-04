@extends('layouts.master') 

@section('content')
 

<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Fabric Checking</h4>
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
 
<form action="{{route('FabricChecking.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
    <div class="mb-3">
        <label for="in_date" class="form-label">Check Date</label>
        <input type="date" name="chk_date" class="form-control" id="chk_date" value="{{date('Y-m-d')}}" required>
 
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>    
<div class="col-md-2">
    <div class="mb-3">
<label for="in_date" class="form-label">In Code</label>
<input type="text" name="in_code" class="form-control" id="in_code" value=""  onchange="getDetails(this.value);getMasterdata(this.value);" required>
    </div>
</div>    


<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">CP Type</label>
<select name="cp_id" class="form-select" id="cp_id" required>
<option value="">--Select CP Type--</option>
@foreach($CPList as  $rowCP)
{
    <option value="{{ $rowCP->cp_id }}">{{ $rowCP->cp_name }}</option>
}
@endforeach
</select>
<input type="hidden" name="bomtype" class="form-control" id="bomtype" value="">
</div>
</div>

 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-invoice_no-input" class="form-label">Invoice No</label>
<input type="text" name="invoice_no" id="invoice_no" class="form-control" id="formrow-invoice_no-input" required readOnly>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
<input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="" readOnly>
</div>
</div>
  
</div>
<div class="row">
 <div class="col-md-2">
<div class="mb-3">
<label for="po_code" class="form-label">PO Code</label>   
 
<select name="po_code" class="form-select" id="po_code" onchange="getPODetails();"   >
<option value="">PO code</option>
@foreach($POList as  $rowpol)
{
    <option value="{{ $rowpol->pur_code  }}"
    
    >{{ $rowpol->pur_code }}</option>
}
@endforeach
</select>


</div>
</div>   



<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">PO Type</label>
<select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();">
<option value="">Type</option>
@foreach($POTypeList as  $rowpo)
{
    <option value="{{ $rowpo->po_type_id  }}">{{ $rowpo->po_type_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 <div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Supplier</label>
<select name="Ac_code" class="form-select" id="Ac_code" required>
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
  <div class="form-check form-check-primary mb-3">
<input class="form-check-input" type="checkbox" id="is_opening" name="is_opening" >
<label class="form-check-label" for="is_opening">
    Opening Stock
</label>
</div>
 
</div> 
</div>



<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
 <th>Roll No</th>
<th>Item Name</th>
<th>Part</th>
<th>Old Meter</th>
<th>Meter</th>
<th>Width</th>
<th>Kg</th>
<th>Shade</th>
<th>Status</th>
<th>Rejected/Short Meter</th>
<th>TrackCode</th>
<th>Rack Location</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
<td> <select name="item_code[]"  id="item_code" style="width:100px;" required>
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td> 


<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Part--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->part_id }}"
    
    >{{ $row->part_name }}</option>
}
@endforeach
</select></td>
 
<td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="1" id="taga_qty1" style="width:50px;"/><input type="text"   name="oldmeter[]" onkeyup="mycalc();" value="0" id="oldmeter1" style="width:80px;" required/></td>
<td><input type="text" class="METER" name="meter[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;" required/></td>
<td><input type="text" name="width[]"  value="0" id="width" style="width:80px;" required /></td>
<td><input type="number" step="0.01" class="KG" name="kg[]" onkeyup="mycalc();" value="0" id="kg" style="width:80px;" required/></td>
<td> <select name="shade_id[]"  id="shade_id" style="width:100px;" required>
<option value="">--Shade--</option>
@foreach($ShadeList as  $row)
{
    <option value="{{ $row->shade_id }}">{{ $row->shade_name }}</option>
}
@endforeach
</select></td>
 
<td> <select name="fcs_id[]"  id="fcs_id" style="width:100px;" required>
<option value="">--Fabric Status--</option>
@foreach($FabCheckList as  $row)
{
    <option value="{{ $row->fcs_id }}">{{ $row->fcs_name }}</option>
}
@endforeach
</select></td>
<td><input type="text" name="reject_short_meter[]"  value="0" id="reject_short_meter" style="width:80px;" required /></td>
<td><input type="text" name="track_code[]"  value="" id="track_code" style="width:80px;" readOnly /></td>

<td> <select name="rack_id[]"  id="rack_id" style="width:100px;" required>
<option value="">--Fabric Status--</option>
@foreach($RackList as  $row)
{
    <option value="{{ $row->rack_id }}">{{ $row->rack_name }}</option>
}
@endforeach
</select></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

 </tbody>
<tfoot>
<tr>
<th>Roll No</th>
<th>Item Name</th>
<th>Part</th>
<th>Old Meter</th>
<th>Meter</th>
<th>Width</th>
<th>Kg</th>
<th>Shade</th>
<th>Status</th>
<th>Rejected/Short Meter</th>
<th>TrackCode</th>
<th>Rack Location</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
 
</div>

<div class="row">
  <div class="col-md-2">
    <div class="mb-3">
        <label for="total_meter" class="form-label">Total Meter</label>
        <input type="number" step="0.01"  name="total_meter" class="form-control" id="total_meter" value="0" readOnly>
    </div>
</div>

 <div class="col-md-2">
    <div class="mb-3">
        <label for="total_kg" class="form-label">Total KG</label>
        <input type="number" step="0.01"  name="total_kg" class="form-control" id="total_kg" value="0" readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Total Taga</label>
        <input type="number"   name="total_taga_qty" class="form-control" id="total_taga_qty" value="1" readOnly>
    </div>
</div>
 
 
    <div class="col-sm-6">
        <div class="mb-3">
            <label for="formrow-inputState" class="form-label">Narration</label>
            <input type="text" name="in_narration" class="form-control" id="in_narration"  value=""     />
        </div>
    </div>


<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();">Submit</button>
<a href="{{ Route('FabricChecking.index') }}" class="btn btn-warning w-md">Cancel</a>
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

 <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 
 

function EnableFields()
{
     $("select").prop('disabled', false);
}


$(document).on("keyup", 'input[name^="meter[]"]', function (event) {
        CalculateRow($(this).closest("tr"));
        
    });
	 	
	function CalculateRow(row)
	{ 
		var old_meter=+row.find('input[name^="old_meter[]"]').val();
        var meter=+row.find('input[name^="meter[]"]').val();
	 	var reject_short_meter=parseFloat(old_meter - meter).toFixed(2);
        row.find('input[name^="reject_short_meter[]"]').val(reject_short_meter);
      	mycalc();
}




function getDetails(str)
{
     
    $.ajax({
    type: "GET",
    url: "{{ route('InwardList') }}",
    data:'in_code='+str,
    success: function(data){
    $("#footable_2").html(data.html);
    }
    });
}


function getMasterdata(in_code)
{
     
$.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('InwardMasterList') }}",
        data:'in_code='+in_code,
        success: function(data){
            
            console.log(data);
            
        $("#cp_id").val(data[0]['cp_id']);
       $("#Ac_code").val(data[0]['Ac_code']);
       $("#po_code").val(data[0]['po_code']);
       $("#total_taga_qty").val(data[0]['total_taga_qty']);
       $("#total_meter").val(data[0]['total_meter']);
       $("#total_kg").val(data[0]['total_kg']);
       $("#invoice_no").val(data[0]['invoice_no']);
       $("#po_type_id").val(data[0]['po_type_id']); 
       $("#invoice_date").val(data[0]['invoice_date']);
       $("#in_narration").val(data[0]['in_narration']);
       
        document.getElementById('Ac_code').disabled =true;
        document.getElementById('po_type_id').disabled=true;
        document.getElementById('po_code').disabled=true;
        document.getElementById('cp_id').disabled=true;
       
        }
        });
    }

var indexcone = 2;
function insertcone(){

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
  

var cell5 = row.insertCell(1);
var t5=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell5);



var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#part_id"),
y = x.clone();
y.attr("id","part_id");
y.attr("name","part_id[]");
y.width(100);
y.appendTo(cell3);




var cell3 = row.insertCell(3);
var t3=document.createElement("select");
var x = $("#quality_code"),
y = x.clone();
y.attr("id","quality_code");
y.attr("name","quality_code[]");
y.width(100);
y.appendTo(cell3);

var cell6 = row.insertCell(4);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="text";
t6.required="true";
t6.id = "width"+indexcone;
t6.name="width[]";
t6.onkeyup=mycalc();
t6.value="0";
cell6.appendChild(t6);
 
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="hidden";
t7.className="TAGAQTY";
t7.required="true";
t7.id = "taga_qty"+indexcone;
t7.name="taga_qty[]";
t7.onkeyup=mycalc();
t7.value="1";
cell6.appendChild(t7);


var cell7 = row.insertCell(5);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;";
t8.type="text";
t8.id = "oldmeter"+indexcone;
t8.name="oldmeter[]";
t8.onkeyup=mycalc();
cell7.appendChild(t8);

var cell9 = row.insertCell(6);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="text";
t9.className="METER";
t9.id = "meter"+indexcone;
t9.name="meter[]";
t9.onkeyup=mycalc();
cell9.appendChild(t9);

var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#shade_id"),
y = x.clone();
y.attr("id","shade_id");
y.attr("name","shade_id[]");
y.width(100);
y.appendTo(cell3);

var cell10 = row.insertCell(8);
var t10=document.createElement("input");
t10.style="display: table-cell; width:80px;";
t10.type="text";
t10.id = "track_code"+indexcone;
t10.name="track_code[]";
cell10.appendChild(t10);
  

var cell11=row.insertCell(9);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell11.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell11.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
mycalc();
recalcIdcone();
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

 
}



function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
mycalc();
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
 
 
 
</script>

<!-- end row -->
@endsection