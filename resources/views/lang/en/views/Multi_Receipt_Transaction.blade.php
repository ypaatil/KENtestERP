@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Receipt</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Receipt</li>
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
<h4 class="card-title mb-4">Receipt</h4>
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

<form action="{{route('MultiReceipt.store')}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@csrf 
<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}">{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Tr No</label>
 <input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PAYMENT' ?>" /> 
    <input type="text" id="TrNo" name="TrNo" class="form-control" placeholder="Transaction No" value="" readonly />
    <input type="hidden" name="TrType" value="82" />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Receipt Date</label>
<input type="date" name="tr_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payment Mode</label><br>
  <input type="radio" name="pay_mode" value="1" >BY CASH  
    <input type="radio" name="pay_mode" value="2">BY CHEQUE  
    <input type="radio" name="pay_mode" value="3">BY NEFT/RTGS  
    <input type="radio" name="pay_mode" value="4">OTHER
</div>
</div>


</div>

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Cash/Bank AC</label>
<select name="dr_code" class="form-select" id="DrCode">
<option value="">--Select Cash/Bank AC--</option>
@foreach($cashbank as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-3"> 
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Party AC/Name</label>
<select name="Ac_code" class="form-select" id="CrCode" onchange="GetUnpaidBills(this.value);">
<option value="">--- Select Party AC/Name ---</option>
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
<label for="formrow-email-input" class="form-label">Total Paying Amount</label>
<input type="text" name="amttobepaid" class="form-control" id="amttobepaid" value="0">
</div>
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Bills (Invoices)</label>
<select name="tr_nos" class="form-select" id="TrNos" onchange="ReceiptDetail(this.value);">
<option value="Select Invoice" selected="selected"> Select Invoice</option>
<option value="0">Advance</option>
@foreach($billlist as  $billrow)
{
<option value="{{ $billrow->TrNo  }}">{{ $billrow->TrNo }}</option>

}
@endforeach
</select>
</div>
</div>

</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Bill Amount</label>
<input type="text" name="bill_amount" class="form-control" id="BillAmount" onkeyup="disc_calculatess();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Paying Amount</label>
<input type="text" name="PayingAmount" class="form-control" id="PayingAmount" onkeyup="disc_calculatess();" value="0">
<input type="number" value="0" name="cnt" id="cnt" readonly="" hidden="true" />
</div>
</div>

</div>

<div>
<button type="button" class="btn btn-primary w-md" onclick="insertRow();">Add</button>
</div>




<div class="table-wrap">
<div class="table-responsive">
<table id="payment_table" class="table  table-bordered table-striped m-b-0 payment_table">
<thead>
<tr>
<th>Sr No</th>
<th>Party Name</th>
<th>Bill/Invoice</th>
<th>Bill Amount</th>
<th>Paying Amount</th>
<th>Remove</th>
</tr>
</thead>
<tbody> </tbody>
<tfoot>
<tr>
<th>Sr No</th>
<th>Party Name</th>
<th>Bill/Invoice</th>
<th>Bill Amount</th>
<th>Paying Amount</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
<br/>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Paying Amount</label>
<input type="text" name="total_amount" class="form-control" id="total_amount" value="0">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" id="narration">
</div>
</div>

</div>

<div>
<button type="submit" class="btn btn-success w-md">Save</button>
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


<!-- end row -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>



var index = 1;
function insertRow(){

if($("#TrNos").val()!='Select Invoice')
{

var table=document.getElementById("payment_table").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+index;
t1.name= "id[]";
t1.value=index;

cell1.appendChild(t1);
 
 
 var cell2 = row.insertCell(1);
   
 var t2=document.createElement("input");
 t2.id = "CrCodes"+index;
 t2.name="CrCodes[]";
 
 t2.value=document.getElementById("CrCode").value;
 cell2.appendChild(t2);
 document.getElementById("CrCodes"+index).style.display='none';
                 
 var tx2=document.createElement("input");
 var tx = document.getElementById("CrCode");
 tx2.style="width:300px;";
 tx2.value = tx.options[tx.selectedIndex].text;
 tx2.readOnly=true;
 cell2.appendChild(tx2);
               

    var cell3 = row.insertCell(2);
    var t3=document.createElement("input");
    t3.style="display: table-cell; width:100px;";
    t3.type="text";
    t3.id = "TrNoss"+index;
    t3.name="TrNoss[]";
    t3.value=document.getElementById("TrNos").value;
    cell3.appendChild(t3);
    
    
    var cell4 = row.insertCell(3);
    var t4=document.createElement("input");
    t4.style="display: table-cell; width:100px;";
    t4.type="text";
    t4.id = "Amounts"+index;
    t4.name="Amounts[]";
    t4.value=document.getElementById("BillAmount").value;
    cell4.appendChild(t4);
    
    
    var cell5 = row.insertCell(4);
    var t5=document.createElement("input");
    t5.style="display: table-cell; width:100px;";
    t5.type="text";
    t5.className="TAMOUNT";
    t5.id = "PayingAmounts"+index;
    t5.name="PayingAmounts[]";
    t5.value=document.getElementById("PayingAmount").value;
    cell5.appendChild(t5);
    
    
    var cell6=row.insertCell(5);
    var btnRemove = document.createElement("INPUT");
    btnRemove.id = "Dbutton";
    btnRemove.type = "button";
    btnRemove.className="btn btn-danger pull-left";
    btnRemove.value = "Remove";
    btnRemove.setAttribute("onclick", "deleteRow(this)");
    cell6.appendChild(btnRemove);
    
    var w = $(window);
    var row = $('#payment_table').find('tr').eq( index );
    
    if (row.length){
    $('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
 
index++;
recalcId();
mycalc();
GetUnpaidBills(t2.value); 
$("#BillAmount").val(0);
$("#PayingAmount").val(0);

if($("#cnt").val()>0)
{       
document.getElementById('Submit').disabled=false;
}

}else
{

alert("Fill Necessary Fields..!!")

}


}
    
   
$("table.payment_table").on("keyup change", 'input[name^="Amounts[]"]', function (event) 
{
    mycalc();

}); 
    
    
 function deleteRow(btn) {
if(document.getElementById('cnt').value > 0){
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


</script>

<script>

function recalcId(){
$.each($("#payment_table tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}



 function mycalc()
{   
    sum1 = 0.0;
    var amounts = document.getElementsByClassName('TAMOUNT');
    //alert("value="+amounts[0].value);
    for(var i=0; i<amounts .length; i++)
    { 
        var a = +amounts[i].value;
        sum1 += parseFloat(a);
    }
    document.getElementById("total_amount").value = sum1.toFixed(2);
}   
    




function GetUnpaidBills(str) {   

var firm_id=document.getElementById('firm_id').value;


$.ajax({
type: "GET",
dataType:'json',
url: "{{ route('getUnpaidBills') }}",
data:{firm_id:1,Ac_code:str},

success: function(data){

console.log(data);

$("#TrNos").html(data.html);

}
});
}


     function ReceiptDetail(TrNos){

var Accode=document.getElementById('CrCode').value;

$.ajax({
type:"GET",
dataType:'json',
url: "{{ route('getReceiptDetail') }}",
data:{id:TrNos,Ac_code:Accode},
success:function(response){
console.log(response);  
 
$("#BillAmount").val(response[0].Amount);
var amttobepaid=parseFloat(document.getElementById('amttobepaid').value);
var total_amount=parseFloat(document.getElementById('total_amount').value);


//alert(total_amount);


var Pending=parseFloat(response[0].Pending);



if(amttobepaid>response[0].Pending && total_amount==0 && (amttobepaid-total_amount)>response[0].Pending)
{
    $("#PayingAmount").val(Pending);  
   // alert(1);
}
else if(amttobepaid>Pending &&   total_amount!=0 && (amttobepaid-total_amount)<response[0].Pending)
{
    $("#PayingAmount").val(amttobepaid-total_amount);  
     //alert(2);
}
else if(amttobepaid>Pending &&   total_amount!=0 && (amttobepaid-total_amount)>response[0].Pending)
{
    $("#PayingAmount").val(Pending);  
    // alert(2);
}


else if(amttobepaid<Pending &&   total_amount==0)
{
    $("#PayingAmount").val(amttobepaid);  
     //alert(3);
}
else if(amttobepaid<Pending &&   total_amount!=0)
{
    $("#PayingAmount").val(amttobepaid-total_amount);  
     //alert(4);
}
else if(amttobepaid==Pending &&   total_amount==0)
{
    $("#PayingAmount").val(amttobepaid);  
     //alert(5);
}
else if(amttobepaid==Pending &&   total_amount!=0)
{
    $("#PayingAmount").val(amttobepaid-total_amount);  
     //alert(6);
} 



}
});
} 


</script>

@endsection