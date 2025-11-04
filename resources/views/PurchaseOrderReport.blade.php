@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Purchase Order Report</h4>
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
 
<form action="{{ route('pdf')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">From date</label>
<input type="date" name="fdate" class="form-control" id="fdate" value="" required>

</div>
</div>
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">To Date</label>
        <input type="date" name="tdate" class="form-control" id="tdate" value="{{date('Y-m-d')}}" required>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Supplier</label>
       <select name="Ac_code" class="form-select" id="Ac_code">
<option value="">--- Select Supplier ---</option>
<option value="All">All</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Status</label>
       <select name="approveFlag" class="form-select" id="approveFlag">
<option value="">--- Select Status ---</option>
<option value="All">All</option>
<option value="1">Approved</option>
<option value="0">Approval Pending</option>
</select>
</div>
</div>
</div>
 
<div class="col-sm-2">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">View</button>
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
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell3);



var cell4 = row.insertCell(2);
var t4=document.createElement("select");
var x = $("#sz_code"),
y = x.clone();
y.attr("id","sz_code");
y.attr("name","sz_code[]");
y.width(100);
y.appendTo(cell4);
 

 
var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.onkeyup="mycalc();";
t5.className="PRODQTY";
t5.id = "production_qty"+indexcone;
t5.name="production_qty[]";
cell5.appendChild(t5);
 
var cell6=row.insertCell(4);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell6.appendChild(btnRemove);

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

function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('PRODQTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("prod_qty").value = sum1.toFixed(2);
}


function calculateamount()
{
    
    
var prod_qty=document.getElementById('prod_qty').value;
var rate_per_piece=document.getElementById('rate_per_piece').value;


var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
$('#total_amount').val(total_amount.toFixed(2));
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


function getTotal()
{

}



</script>

<!-- end row -->
@endsection