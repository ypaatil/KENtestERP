@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Piece</h4>
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
 
<form action="{{route('MaterialOutward.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Job Code</label>
<input type="text" name="job_code" class="form-control" id="job_code" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Tr Type</label>
<select name="transactionId" class="form-select" id="transactionId ">
<option value="">--Select Tr Type--</option>
@foreach($Trtype as  $Trtyperow)
{
    <option value="{{ $Trtyperow->transactionId  }}">{{ $Trtyperow->transactionType }}</option>
}
@endforeach
</select>
</div>
</div>
 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Lot No</label>
<input type="text" name="lot_no" class="form-control" id="lot_no" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Out Date</label>
        <input type="date" name="out_date" class="form-control" id="out_date" value="{{date('Y-m-d')}}">
    </div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="ac_code" class="form-select" id="ac_code">
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
 
</div>
 
<div class="row">

<h6>Piece Detail</h6>


<input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th><button type="button" onclick="insertcone();mycalc();" class="btn btn-warning pull-left">Add</button></th>
<th>Final Product</th>
<th>Style</th>
<th>Color</th>
<th>Size</th>
 <th>Qty</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id" value="1" id="id" style="width:50px;"/></td>
<td> <select name="item_code[]" class="item" id="item_code" style="width:100px;">
<option value="0">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td>
<td><input type="text" class="StyleNo" id="style_code" onkeyup="mycalc();" name="style_code[]" value="0"  style="width:100px;" /></td>

<td> <select name="color_id[]"  id="color_id" style="width:100px;">
<option value="0">--Color--</option>
@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
}
@endforeach
</select></td>

<td> <select name="sz_code[]"  id="sz_code" style="width:100px;">
<option value="0">--Size--</option>
@foreach($SizeList as  $row)
{
    <option value="{{ $row->sz_code }}">{{ $row->sz_name }}</option>
}
@endforeach
</select></td>
<td><input type="text" class="PRODQTY"  name="production_qty[]" value="0" id="prodqty1" style="width:80px;" /></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
 </tbody>
<tfoot>
<tr>
<th>Sr No</th>
<th>Fabric</th>
<th>Style</th>
<th>Color</th>
<th>Prod Qty</th>
<th>Fab Img</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>

</div>


<div class="row">
<h6>Fabric Detail</h6>

<input type="number" value="1" name="cntrfabric" id="cntrfabric" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_4" class="table  table-bordered table-striped m-b-0  footable_4">
<thead>
<tr>
<th><button type="button" onclick="insertfabric();mycalc();" class="btn btn-warning pull-left">Add</button></th>
<th>Final Product</th>
<th>Style</th>
<th>Color</th>
 <th>Meter</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id" value="1" id="id" style="width:50px;"/></td>
<td> <select name="item_codefabric[]" class="item" id="item_codefabric" style="width:100px;">
<option value="0">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td>

<td><input type="text" class="StyleNo" onkeyup="mycalc();" name="style_codefabric[]" value="0"  style="width:80px;" id="style_codefabric" required /></td>

<td> <select name="color_idfabric[]"  id="color_idfabric" style="width:100px;">
<option value="">--Color--</option>
@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
}
@endforeach
</select></td>

<td><input type="text" class="METER"  name="meter[]" value="0" id="meter" style="width:80px;" /></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
 </tbody>
<tfoot>
<tr>
<th></th>
<th>Final Product</th>
<th>Style</th>
<th>Color</th>
 <th>Meter</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Total Qty</label>
<input type="number"   name="total_qty" class="form-control" id="total_qty" value="0" onkeyup="mycalc();">
    </div>
</div>
<div class="col-md-2">
    <div class="mb-3">
<label for="rate_per_piece" class="form-label">Total Meter</label>
<input type="number" step="0.01"  name="total_meter" class="form-control" id="total_meter" value="0" onkeyup="mycalc();">
    </div>
</div>
</div>
</div>
<!-- end row -->
<div class="row">
<div class="col-sm-8">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="" />
</div>
</div>

</div>

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">Submit</button>
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

<script> 
                // Replace the <textarea id="editor1"> with a CKEditor 
                // instance, using default configuration. 
                CKEDITOR.replace('editor1'); 
                CKEDITOR.replace('editor2');
            </script> 
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});

 
var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
 

var cell2 = row.insertCell(1);
var t2=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
y.attr("id","item_code");
y.attr("name","item_code[]");
y.width(100);
y.appendTo(cell2);


var cell3 = row.insertCell(2);
var t3=document.createElement("input");
var x = $("#style_code"),
y = x.clone();
y.attr("id","style_code");
y.attr("name","style_code[]");
y.width(100);
y.appendTo(cell3);


var cell4 = row.insertCell(3);
var t4=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell4);


var cell5 = row.insertCell(4);
var t5=document.createElement("select");
var x = $("#sz_code"),
y = x.clone();
y.attr("id","sz_code");
y.attr("name","sz_code[]");
y.width(100);
y.appendTo(cell5);
 

 
var cell6 = row.insertCell(5);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="text";
t6.onkeyup="mycalc();";
t6.className="PRODQTY";
t6.id = "production_qty"+indexcone;
t6.name="production_qty[]";
cell6.appendChild(t6);




var cell7=row.insertCell(6);
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell7.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
$("#no_cones").val("");
$("#bags").val("");
$("#fr_weights").val("");

recalcIdcone();
}


var indexcone2 = 2;
function insertfabric(){

var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone2;
t1.name= "id[]";
t1.value=indexcone2;

cell1.appendChild(t1);
 

var cell2 = row.insertCell(1);
var t2=document.createElement("select");
var x = $("#item_codefabric"),
y = x.clone();
y.attr("id","item_codefabric");
y.attr("name","item_codefabric[]");
y.width(100);
y.appendTo(cell2);


var cell3 = row.insertCell(2);
var t3=document.createElement("input");
var x = $("#style_codefabric"),
y = x.clone();
y.attr("id","style_codefabric");
y.attr("name","style_codefabric[]");
y.width(100);
y.appendTo(cell3);


var cell4 = row.insertCell(3);
var t4=document.createElement("select");
var x = $("#color_idfabric"),
y = x.clone();
y.attr("id","color_idfabric");
y.attr("name","color_idfabric[]");
y.width(100);
y.appendTo(cell4);

 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.onkeyup="mycalc();";
t5.className="METER";
t5.id = "meter"+indexcone2;
t5.name="meter[]";
cell5.appendChild(t5);


var cell7=row.insertCell(5);
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
cell7.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_4').find('tr').eq(indexcone2);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrfabric').value = parseInt(document.getElementById('cntrfabric').value)+1;

indexcone2++;
$("#no_cones").val("");
$("#bags").val("");
$("#fr_weights").val("");

deleteRowcone2();
}






function mycalc()
{   


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
var amounts = document.getElementsByClassName('PRODQTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1.toFixed(2);
}



function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;

recalcIdcone();
mycalc();
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



function deleteRowcone2(btn) {
if(document.getElementById('cntrfabric').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrfabric').value = document.getElementById('cntrfabric').value-1;

recalcIdcone2();
mycalc();
if($("#cntrfabric").val()<=0)
{       
document.getElementById('Submit').disabled=true;
}
 
}
}


function recalcIdcone2(){
$.each($("#footable_4 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}



function getTotal()
{

}


</script>

<!-- end row -->
@endsection