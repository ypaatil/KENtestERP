@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Fabric Purchase</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Fabric Purchase Master</li>
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
<h4 class="card-title mb-4">Form Grid Layout</h4>
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

<form action="{{route('Fabric_Purchase.update',$fabricpurchasefetch)}}" method="POST">

@method('put')
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
<option value="{{ $row->firm_id }}"

{{ $row->firm_id == $fabricpurchasefetch->firm_id ? 'selected="selected"' : '' }}

	>{{ $row->firm_name }}</option>

}
@endforeach
</select> 
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Code</label>
<input type="text" name="fpur_code" class="form-control" id="pur_code" value="{{ $fabricpurchasefetch->fpur_code }}" readonly="readonly">
<input type="hidden" name="c_code" id="c_code" value="1" />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Date</label>
<input type="date" name="pur_date" class="form-control" id="formrow-email-input" value="{{ $fabricpurchasefetch->fpur_date }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CP Type</label>
<select name="cp_id" class="form-control" id="cp_id" onchange="setGSTType2(this.value);PartyShortlist(this.value); ">
<option value="0">--- Select CP Type ---</option>
@foreach($cptypelist as  $rowcp)
{
<option value="{{ $rowcp->cp_id  }}"

{{ $rowcp->cp_id == $fabricpurchasefetch->cp_id ? 'selected="selected"' : '' }}

	>{{ $rowcp->cp_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Type</label>
<select name="tax_type_id" id="tax_type_id" class="form-control" onchange="setGstType(this.value);">
<option value="">--Select GST Type--</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}"

{{ $rowgst->tax_type_id == $fabricpurchasefetch->tax_type_id ? 'selected="selected"' : '' }}

	>{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Bill No</label>
<input type="text" name="pur_bill_no" class="form-control" id="formrow-email-input" value="{{ $fabricpurchasefetch->fpur_bill }}">
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Party Name</label>
<select name="Ac_code" class="form-select" id="Ac_code">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}"

{{ $rowledger->ac_code == $fabricpurchasefetch->ac_code ? 'selected="selected"' : '' }}

	>{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Purchase Ledger</label>
<select name="Purchase_Ac_code" class="form-select" id="Purchase_Ac_code">
<option value="">--- Select Purchase Ledger ---</option>
@foreach($ledgerlist as  $rowledger2)
{
<option value="{{ $rowledger2->ac_code  }}"

{{ $rowledger2->ac_code == $fabricpurchasefetch->Purchase_Ac_code ? 'selected="selected"' : '' }}

	>{{ $rowledger2->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

</div>


<div>
<!-- <button type="button" class="btn btn-primary w-md" onclick="insertRow();mycalc();">Add</button> -->
</div>




<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
<thead>
<tr>
<th><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button></th>    
<th>Item Name</th>
<th>Style/Design</th>
<th>Meter</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</thead>
<tbody>

@php  if($detailfabricpurchase->isEmpty()) { @endphp

<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]"  id="item_code" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>

<td> 
<input list="pur_style_nos" id="pur_style_no" name="pur_style_nos[]" style="width:200px;">
<datalist id="pur_style_nos" name="pur_style_nos[]">
@foreach($stylenos as  $rowstyleno)
{
<option data-value="{{ $rowstyleno->fpur_style_no }}">{{ $rowstyleno->fpur_style_no }}</option>
 }
@endforeach
</datalist>

</td>

<td><input type="text"   name="pur_mtr[]"   value="0" class="Meter" id="pur_mtr" style="width:80px;" required/></td>
<td><input type="text" class=""  name="pur_qty[]" class="QTY"   value="0" id="pur_qty" style="width:80px;" required/></td>
<td><input type="text"   name="pur_rate[]"  value="0" class="RATE"  id="pur_rate" style="width:80px;" required/></td>
<td><input type="text"   name="Amount[]"  value="0" class="AMOUNT"  id="Amount" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

@php } else { @endphp

  @php $no=1; @endphp
@foreach($detailfabricpurchase as $rowdetail)

<tr>
<td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" id="item_code" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}"

{{ $rowitem->item_code == $rowdetail->item_code ? 'selected="selected"' : '' }}

	>{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>

<td> 
<input list="pur_style_nos" id="pur_style_no" name="pur_style_nos[]" value="{{ $rowdetail->fpur_style_no }}" style="width:200px;">
<datalist id="pur_style_nos" name="pur_style_nos[]">
@foreach($stylenos as  $rowstyleno)
{
<option data-value="{{ $rowstyleno->fpur_style_no }}"

{{ $rowstyleno->fpur_style_no == $rowdetail->fpur_style_no ? 'selected="selected"' : '' }}

	>{{ $rowstyleno->fpur_style_no }}</option>
 }
@endforeach
</datalist>

</td>

<td><input type="text"   name="pur_mtr[]"   value="{{ $rowdetail->fpur_mtr }}" class="Meter" id="pur_mtr" style="width:80px;" required/></td>
<td><input type="text"   name="pur_qty[]" class="QTY"   value="{{ $rowdetail->fpur_qty }}" id="pur_qty" style="width:80px;" required/></td>
<td><input type="text"   name="pur_rate[]"  value="{{ $rowdetail->pur_rate }}" class="RATE"  id="pur_rate" style="width:80px;" required/></td>
<td><input type="text"   name="Amount[]"  value="{{ $rowdetail->Amount }}" class="AMOUNT"  id="Amount" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp
</tbody>
<tfoot>
<tr>
<th></th>    
<th>Item Name</th>
<th>Style/Design</th>
<th>Meter</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th><i class="fas fa-trash"></i> </th>
</tr>
</tfoot>
</table>
</div>
</div>
<br/>
<input type="hidden"   name="cnt" id="cnt" value="{{ count($detailfabricpurchase) }}">

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Qty</label>
<input type="number" name="pur_tqty" id="pur_tqty" class="form-control" placeholder="TotalQty" value="{{ $fabricpurchasefetch->total_qty }}" readOnly required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Meter</label>
<input type="number" name="pur_tmeter" id="pur_tmeter" class="form-control"  value="{{ $fabricpurchasefetch->total_meter }}" placeholder="Total Meter"  readOnly required/>
</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Add 1</label>
<input type="number" step="0.01" name="pur_add1" id="pur_add1" class="form-control" placeholder="Add1" value="{{ $fabricpurchasefetch->add1 }}" onkeyup="calculateGST();" required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Add2</label>
<input type="number" v name="pur_add2" id="pur_add2" class="form-control" placeholder=" Add2" value="{{ $fabricpurchasefetch->add2 }}" onkeyup="calculateGST();" required/>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Less 1</label>
<input type="number" step="0.01" name="pur_less1" id="pur_less1" class="form-control" placeholder="Less1" value="{{ $fabricpurchasefetch->less1 }}" onkeyup="calculateGST();" required="" />
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Less 2</label>
<input type="number" step="0.01" name="pur_less2" id="pur_less2" class="form-control" placeholder=" Less2" value="{{ $fabricpurchasefetch->less2 }}" onkeyup="calculateGST();" required="" />
</div>
</div> 

</div>

<div class="row">

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST</label>
<input type="number" step="0.01" name="pur_CGST" id="pur_CGST" class="form-control" placeholder=" CGST" value="{{ $fabricpurchasefetch->cgst_per }}" readOnly required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">CGST Amount</label>
<input type="number" step="0.01" name="pur_camt" id="pur_camt" class="form-control" placeholder=" C Amount" value="{{ $fabricpurchasefetch->cgst_amt }}" readOnly required/>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST</label>
<input type="number" step="0.01" name="pur_SGST" id="pur_SGST" class="form-control" placeholder=" SGST" value="{{ $fabricpurchasefetch->sgst_per }}" readOnly required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">SGST Amount</label>
<input type="number" step="0.01" name="pur_samt" id="pur_samt" class="form-control" placeholder=" S Amount" value="{{ $fabricpurchasefetch->sgst_amt }}" readOnly required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST</label>
<input type="number" step="0.01" name="pur_IGST" id="pur_IGST" class="form-control" placeholder=" IGST" value="{{ $fabricpurchasefetch->igst_per }}" readOnly required/>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">IGST Amount</label>
<input type="number" step="0.01" name="pur_iamt" id="pur_iamt" class="form-control" placeholder="I Amount" value="{{ $fabricpurchasefetch->igst_amt }}" readOnly required/>
</div>
</div>

</div>



<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Gross Amount</label>
<input type="number" step="0.01" name="pur_gamt" id="pur_gamt" class="form-control" placeholder=" Gross Amount" value="{{ $fabricpurchasefetch->gross_amount }}" onkeyup="calculateGST();" required/>
</div>
</div>

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="number" step="0.01" name="pur_gstamt" id="pur_gstamt" class="form-control" placeholder=" GST Amount" value="{{ $fabricpurchasefetch->gst_amount }}" readOnly required/>
</div>
</div>

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Net Amount</label>
<input type="number" step="0.01" name="pur_namt" id="pur_namt" class="form-control" placeholder=" Net Amount" value="{{ $fabricpurchasefetch->net_amount }}" readOnly required/>
</div>
</div>

</div>





<div class="row">
<div class="col-md-4">	
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" value="{{ $fabricpurchasefetch->narration }}" id="narration">
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
var index = 1;
function insertRow(){

if($("#pur_bill_no").val()!='' &&  $("#pur_qty").val()!=0   &&  $("#pur_tamt").val()!=0  &&  $("#pur_amt").val()!=0)
{
var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="  width:50px;";
t1.readOnly="true";
t1.id = "id"+index;
t1.name= "id[]";
t1.value=index;
cell1.appendChild(t1);

//  document.getElementById("raw"+index).style.display='none';
var cell2 = row.insertCell(1);
var t1=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
y.attr("id","item_code");
y.attr("name","item_codes[]");
y.width(100);
y.appendTo(cell2);



var cell3=row.insertCell(2);
var t3=document.createElement("input");
t3.style="display: table-cell;  width:200px;";
//t4.className="form-control";
t3.readonly=true;
t3.default="NA";
t3.id = "pur_style_nos"+index;
t3.name="pur_style_nos[]";
t3.value= document.getElementById("pur_style_no").value;
cell3.appendChild(t3);

var cell4=row.insertCell(3);
var t4=document.createElement("input");
t4.style="display: table-cell; width:80px;";
t4.className="Meter";
t4.type="number";
t4.step="0.01";
t4.id = "pur_mtr"+index;
t4.name="pur_mtr[]";
t4.value= "";
cell4.appendChild(t4);


var cell5=row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px; ";
t5.className="QTY";
t5.id = "pur_qty"+index;
t5.name="pur_qty[]";
t5.value= "";
cell5.appendChild(t5);


var cell6=row.insertCell(5);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.className="RATE";
t6.type="number";
t6.step="0.01";
t6.id = "pur_rate"+index;
t6.name="pur_rate[]";
t6.value= "0";
t6.required="true";
cell6.appendChild(t6);


var cell7=row.insertCell(6);
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px; ";
t7.className="AMOUNT";
t7.id = "Amount"+index;
t7.name="Amount[]";
t7.value= "";
cell7.appendChild(t7);  

var t11=document.createElement("input");
t11.style="display: table-cell; width:80px;";
t11.id = "usedFlag"+index;
t11.name="usedFlag[]";
t11.value= "0";
cell7.appendChild(t11); 
document.getElementById("usedFlag"+index).style.display='none';

var cell8=row.insertCell(7);
var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRow(this); ");
cell8.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_2').find('tr').eq(index);
if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;
index++;

recalcId();

$("#item_code").focus();
var blank='';

if($("#item_type").val()==2)
{
$("#pur_style_no").val(blank);

}
if($("#cnt").val()>0)
{       
document.getElementById('Submit').disabled=false;
}

}   //if end    
else
{

alert("Fill Necessary Fields..!!")

}

}

</script>

<script>

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

function recalcId(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}


function mycalc()
{

var sum = 0.0;
var amounts = document.getElementsByClassName('Meter');
for(var i=0; i<amounts .length; i++)
{
var a = +amounts[i].value;
sum += parseFloat(a) || 0;
}
document.getElementById("pur_tmeter").value = sum.toFixed(2);


var sum = 0.0;
var amounts = document.getElementsByClassName('QTY');
for(var i=0; i<amounts .length; i++)
{
var a = +amounts[i].value;
sum += parseFloat(a) || 0;
}
document.getElementById("pur_tqty").value = sum.toFixed(2);


var sum = 0.0;
var amounts = document.getElementsByClassName('AMOUNT');
for(var i=0; i<amounts .length; i++)
{
var a = +amounts[i].value;
sum += parseFloat(a) || 0;
}
document.getElementById("pur_gamt").value = sum.toFixed(2);



}



function calculateGST()
{
var tax_type=$("#tax_type_id").val() 
var GrossAmount= $("#pur_gamt").val();
var CGST= $("#pur_CGST").val();
var SGST= $("#pur_SGST").val();
var IGST= $("#pur_IGST").val();

var Add1= $("#pur_add1").val();
var Add2= $("#pur_add2").val();
var less1= $("#pur_less1").val();
var less2= $("#pur_less2").val();

var add= parseFloat(Add1)+parseFloat(Add2);

var less=parseFloat(less1)+parseFloat(less2);

if(tax_type==1)
{
var CAMT=parseFloat(GrossAmount * parseFloat(CGST/100));
var SAMT=parseFloat(GrossAmount * parseFloat(SGST/100));
var IAMT=0;
}
else if(tax_type=2)
{
var IAMT=parseFloat((GrossAmount * parseFloat(IGST/100)).toFixed(2));
var CAMT=0;
var SAMT=0;
}
else if(tax_type=3)
{
var IAMT=0;
var CAMT=0;
var SAMT=0;
}

var totalGstAmount=parseFloat(parseFloat(CAMT)+parseFloat(SAMT)+parseFloat(IAMT));

$("#pur_camt").val(((CAMT * 100) / 100).toFixed(2));

$("#pur_samt").val(((SAMT * 100) / 100).toFixed(2));

$("#pur_iamt").val(((IAMT * 100) / 100).toFixed(2));

$("#pur_gstamt").val(((totalGstAmount * 100) / 100).toFixed(2));
$("#pur_namt").val(((parseFloat(GrossAmount) + parseFloat(totalGstAmount)+ parseFloat(add)- parseFloat(less))).toFixed(2));


}   





$("table.footable_2").on("keyup", 'input[name^="pur_mtr[]"],input[name^="pur_qty[]"],input[name^="pur_rate[]"],input[name^="pur_disc[]"],input[name^="pur_discamt[]"]', function (event) {
CalculateRow($(this).closest("tr"));


}); 


function CalculateRow(row)
{
var Meter=+row.find('input[name^="pur_mtr[]"]').val();
var Qty=+row.find('input[name^="pur_qty[]"]').val();
var Rate=+row.find('input[name^="pur_rate[]"]').val();
var disc_per=+row.find('input[name^="pur_disc[]"]').val();
var disc_amt=+row.find('input[name^="pur_disc_amt[]"]').val();
var Cgst=  +row.find('input[name^="pur_cgst[]"]').val();
var Sgst= +row.find('input[name^="pur_sgst[]"]').val();
var Igst= +row.find('input[name^="pur_igst[]"]').val();
var Camt= +row.find('input[name^="pur_camt[]"]').val();
var Samt= +row.find('input[name^="pur_samt[]"]').val();
var Iamt= +row.find('input[name^="pur_iamt[]"]').val();
var Amount= +row.find('input[name^="pur_amt[]"]').val();
var TAmount= +row.find('input[name^="pur_tamt[]"]').val();
var item_type=+row.find('input[name^="item_type[]"]').val();



if(Qty>0)
{

Amount=Meter*Rate;
row.find('input[name^="Amount[]"]').val((Amount).toFixed(2));


}

mycalc();
calculateGST();
}




function setGSTType2(cp_id)
{
if(cp_id==2)
{
$("#pur_rate").val(0);
$("#pur_gamt").val(0);
$("#tax_type_id").val(3);
setGstType(3);
calculateGST(); 
}
else
{
$("#tax_type_id").val(1);
setGstType(1);
calculateGST(); 

}

}



var tax_type_id=1;

function setGstType(tax_type)
{  
if(tax_type==1)
{
$("#pur_IGST").val(0);
$("#pur_iamt").val(0);
$("#pur_CGST").val(2.5);
$("#pur_SGST").val(2.5);
}
else if(tax_type==2)
{

$("#pur_IGST").val(5);
$("#pur_CGST").val(0);
$("#pur_SGST").val(0);
$("#pur_camt").val(0);
$("#pur_samt").val(0);
}
else if(tax_type==3)
{

$("#pur_CGST").val(0);
$("#pur_SGST").val(0);
$("#pur_IGST").val(0);
$("#pur_camt").val(0);
$("#pur_samt").val(0);
$("#pur_iamt").val(0);
}
calculateGST(); 
}




function PartyShortlist(str) {   

alert(str);

$.ajax({
type: "GET",
dataType:'json',
url: "{{ route('PartyShortlist') }}",
data:{cp_id:str},

success: function(data){

console.log(data);

$("#Ac_code").html(data.html);

}
});
}

</script>

@endsection