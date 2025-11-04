@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Sales (Invoice)</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Sales (Invoice)</li>
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
<form action="{{route('SaleTransaction.update',base64_encode($SaleTransactionMasterList->sale_code) )}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'SaleTransaction' ?>" /> 
@method('put')
@csrf    
    
    
<h4 class="card-title mb-4">Sales (Invoice): {{ $SaleTransactionMasterList->sale_code }}</h4>
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

{{ $row->firm_id == $SaleTransactionMasterList->firm_id ? 'selected="selected"' : '' }}

    >{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="sale_date" class="form-label">Invoice Date</label>
<input type="date" name="sale_date" class="form-control" id="sale_date" value="{{ $SaleTransactionMasterList->sale_date }}">

<input type="hidden" name="sale_code" class="form-control" id="sale_code" value="{{ base64_encode($SaleTransactionMasterList->sale_code) }}" readonly="readonly">
<input type="hidden" name="c_code" id="c_code" value="{{ $SaleTransactionMasterList->c_code }}" />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer</label>
<select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}"

{{ $rowledger->ac_code == $SaleTransactionMasterList->Ac_code ? 'selected="selected"' : '' }}
    >{{ $rowledger->ac_name }}</option>
 }
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">GST</label>
<select name="tax_type_id" class="form-select" id="tax_type_id" onchange="getPartyDetails();">
<option value="">--- Select Gst---</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}"

{{ $rowgst->tax_type_id == $SaleTransactionMasterList->tax_type_id ? 'selected="selected"' : '' }}


    >{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
 
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sales Order No</label>
<select name="sales_order_nos[]" class="form-select select2" id="sales_order_nos" multiple>
<option value="">Sales Order No</option>
@php $sales_order_nos = explode(',', $SaleTransactionMasterList->sales_order_nos);   @endphp
@foreach($SalesOrderList as  $row)
{
    <option value="{{ $row->tr_code  }}"
 
   @if(in_array($row->tr_code, $sales_order_nos)) selected @endif  
    
    >({{$row->tr_code}})</option>
}
@endforeach
</select>
</div>
</div>
 
</div> 
 
 

<div>
</div>

<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">  
<thead>
<tr>
<th>SrNo</th>
<th>Sales Order Details</th>
<th>HSN No</th>
<th>Unit</th>
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
<th>Total Amount</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody id="bomdis">

 
  
  
  @php  if($SaleTransactionDetails->isNotEmpty()) {  
  $no=1; @endphp
@foreach($SaleTransactionDetails as $row)

 <tr>
<td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
 <td>
 <input type="text"   name="sales_order_no[]"   value="{{ $row->sales_order_no }}" id="sales_order_no" style="width:80px;" required readOnly/>
    </td>
  
  <td>  <input type="text"   name="hsn_code[]"   value="{{ $row->hsn_code }}" id="hsn_code" style="width:80px;" required readOnly/></td>

<td> <select name="unit_id[]" class="unit_id" id="unit_id" style="width:100px;" disabled>
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}"

{{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}

    >{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>

<td><input style="width:80px;" type="text" class="Qty" name="order_qtys[]" value="{{ $row->order_qty }}" readOnly  id="order_qtys">
</td>
<td><input  style="width:80px;" type="number" step="0.01" class="" name="item_rates[]" value="{{ $row->order_rate }}" id="order_rate">
</td> 
<td><input style="width:100px;" type="number" step="0.01" id="disc_per" class="" name="disc_pers[]" value="{{ $row->disc_per }}"></td>
<td><input readOnly style="width:80px;" readOnly  type="number" id="disc_amount" step="0.01" class="" name="disc_amounts[]" value="{{ $row->disc_amount }}"></td>
<td><input readOnly style="width:80px;" readOnly type="number" id="sale_cgst" step="0.01"  class="" name="sale_cgsts[]" value="{{ $row->sale_cgst }}"></td>
<td><input  style="width:80px;"  type="number" readOnly step="0.01" id="camt" class="GSTAMT" name="camts[]" value="{{ $row->camt }}"></td>
<td><input readOnly style="width:80px;" readOnly type="number" step="0.01" id="sale_sgst" class="" name="sale_sgsts[]" value="{{ $row->sale_sgst }}"></td>
<td><input style="width:80px;"  type="number" readOnly step="0.01" id="samt" class="GSTAMT" name="samts[]" value="{{ $row->samt }}"></td>
<td><input readOnly style="width:80px;" readOnly type="number" step="0.01" id="sale_igst" class="" name="sale_igsts[]" value="{{ $row->sale_igst }}"></td>
<td><input  style="width:80px;"  type="number" readOnly step="0.01" id="iamt" class="GSTAMT" name="iamts[]" value="{{ $row->iamt }}"></td>
<td><input  style="width:80px;"  type="number" readOnly step="0.01" id="amount" class="GROSS" name="amounts[]" value="{{ $row->amount }}"></td>
<td><input  style="width:80px;"  type="number" readOnly step="0.01" id="total_amount" class="TOTAMT" name="total_amounts[]" value="{{ $row->total_amount }}"></td>
<td><button type="button" onclick="insertRow();mycalc();"  class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
 </tr>
 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp

 </tbody>
 
</table>
</div>
</div>
<br/>
<input type="hidden"   name="cnt" id="cnt" value="{{ count($SaleTransactionDetails) }}">  
<div class="row">
    
 <div class="col-md-2">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Qty</label>
<input type="text" name="total_qty" class="form-control" id="total_qty" value="{{ $SaleTransactionMasterList->total_qty  }}" required readOnly>
<input type="hidden" name="address" class="form-control" id="address" value="{{ $SaleTransactionMasterList->address  }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Gross Amount</label>
<input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();" value="{{ $SaleTransactionMasterList->Gross_amount }}" required readOnly>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="Gst_amount" class="form-control" id="Gst_amount" value="{{ $SaleTransactionMasterList->Gst_amount }}" required readOnly>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Net Amount</label>
<input type="text" name="Net_amount" class="form-control" id="Net_amount" value="{{ $SaleTransactionMasterList->Net_amount }}" required readOnly>
</div>
</div>
 
 
<div class="col-md-4">	
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Narration</label>
<input type="text" name="narration" class="form-control" id="narration" value="{{ $SaleTransactionMasterList->narration }}">
</div>
</div>	
 
  
 
</div>
 
    
    
    <div class="row">
<div class="col-md-12">
<div class="mb-3">
<label for="term_and_conditions" class="form-label">Terms and Conditions</label>
<textarea name="terms_and_conditions" class="form-control" id="editor1"   required>{{$SaleTransactionMasterList->terms_and_conditions}}</textarea>
</div>
</div>
</div>

    
     </br>  
<button type="submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
<a href="{{ Route('SaleTransaction.index') }}" class="btn btn-warning w-md">Cancel</a>
 
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
// Replace the <textarea id="editor1"> with a CKEditor 
// instance, using default configuration. 
CKEDITOR.replace('editor1'); 
 
</script> 
<script>

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
// t4.step="0.01";
// t4.id = "item_rates"+index;
// t4.name= "item_rates[]";
// t4.value="0";
// cell4.appendChild(t4);


// var cell5=row.insertCell(7);
// var t5=document.createElement("input");
// t5.style="display: table-cell; width:80px;";
// t5.type="number";
// t5.step="0.01";
// t5.id = "disc_pers"+index;
// t5.name= "disc_pers[]";
// t5.value="0";
// cell5.appendChild(t5);


// var cell6=row.insertCell(8);
// var t6=document.createElement("input");
// t6.style="display: table-cell; width:80px;";
// t6.type="number";
// t6.step="0.01";
// t6.id = "disc_amounts"+index;
// t6.name= "disc_amounts[]";
// t6.value="0";
// cell6.appendChild(t6);

// var cell7=row.insertCell(9);
// var t7=document.createElement("input");
// t7.style="display: table-cell; width:80px;";
// t7.type="number";
// t7.step="0.01";
// t7.id = "sale_cgsts"+index;
// t7.name= "sale_cgsts[]";
// t7.value="0";
// cell7.appendChild(t7);


// var cell8=row.insertCell(10);
// var t8=document.createElement("input");
// t8.style="display: table-cell; width:80px;";
// t8.type="number";
// t8.step="0.01";
// t8.className="GSTAMT";
// t8.id = "camts"+index;
// t8.name= "camts[]";
// t8.value="0";
// cell8.appendChild(t8);


// var cell9=row.insertCell(11);
// var t9=document.createElement("input");
// t9.style="display: table-cell; width:80px;";
// t9.type="number";
// t9.step="0.01";
// t9.id = "sale_sgsts"+index;
// t9.name= "sale_sgsts[]";
// t9.value="0";
// cell9.appendChild(t9);

// var cell10=row.insertCell(12);
// var t10=document.createElement("input");
// t10.style="display: table-cell; width:80px;";
// t10.type="number";
// t10.step="0.01";
// t10.className="GSTAMT";
// t10.id = "samts"+index;
// t10.name= "samts[]";
// t10.value="0";
// cell10.appendChild(t10);


// var cell11=row.insertCell(13);
// var t11=document.createElement("input");
// t11.style="display: table-cell; width:80px;";
// t11.type="number";
// t11.step="0.01";
// t11.id = "sale_igsts"+index;
// t11.name= "sale_igsts[]";
// t11.value="0";
// cell11.appendChild(t11);

// var cell12=row.insertCell(14);
// var t12=document.createElement("input");
// t12.style="display: table-cell; width:80px;";
// t12.type="number";
// t12.step="0.01";
// t12.className="GSTAMT";
// t12.id = "iamts"+index;
// t12.name= "iamts[]";
// t12.value="0";
// cell12.appendChild(t12);


// var cell13=row.insertCell(15);
// var t13=document.createElement("input");
// t13.style="display: table-cell; width:80px;";
// t13.type="number";
// t13.step="0.01";
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
// t13.step="0.01";
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
// t13.step="0.01";
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
// t14.step="0.01";
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

var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
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
var t2=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
var selectedValue="";
y.attr("id","item_code");
y.find("option[value = '" + selectedValue + "']").attr("selected", "selected");
y.attr("name","item_codes[]");
y.attr("value","");
y.width(100);
y.appendTo(cell2);


var cell3 = row.insertCell(2);
var t3=document.createElement("img");
t3.src="";
t3.id = "item_image"+index;
t3.name="item_image[]";
cell3.appendChild(t3);

var cell3 = row.insertCell(3); 
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="hidden";
//t3.className="QTY";
t3.id = "hsn_code"+index;
t3.name="hsn_code[]";
t3.value="";
cell3.appendChild(t3);


var cell2 = row.insertCell(4);
var t2=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
y.width(100);
y.appendTo(cell2);


var cell3 = row.insertCell(5);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
t3.className="ITEMQTY";
t3.id = "item_qtys"+index;
t3.name="item_qtys[]";
t3.value="0";
cell3.appendChild(t3);

var cell4=row.insertCell(6);
var t4=document.createElement("input");
t4.style="display: table-cell; width:80px;";
t4.type="number";
t4.step="0.01";
t4.id = "item_rates"+index;
t4.name= "item_rates[]";
t4.value="0";
cell4.appendChild(t4);


var cell5=row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="number";
t5.step="0.01";
t5.id = "disc_pers"+index;
t5.name= "disc_pers[]";
t5.value="0";
cell5.appendChild(t5);


var cell6=row.insertCell(8);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="number";
t6.step="0.01";
t6.readOnly=true;
t6.id = "disc_amounts"+index;
t6.name= "disc_amounts[]";
t6.value="0";
cell6.appendChild(t6);

var cell7=row.insertCell(9);
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="number";
t7.step="0.01";
t7.readOnly=true;
t7.id = "sale_cgsts"+index;
t7.name= "sale_cgsts[]";
t7.value="0";
cell7.appendChild(t7);


var cell8=row.insertCell(10);
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


var cell9=row.insertCell(11);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="number";
t9.step="0.01";
t9.readOnly=true;
t9.id = "sale_sgsts"+index;
t9.name= "sale_sgsts[]";
t9.value="0";
cell9.appendChild(t9);

var cell10=row.insertCell(12);
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


var cell11=row.insertCell(13);
var t11=document.createElement("input");
t11.style="display: table-cell; width:80px;";
t11.type="number";
t11.step="0.01";
t11.readOnly=true;
t11.id = "sale_igsts"+index;
t11.name= "sale_igsts[]";
t11.value="0";
cell11.appendChild(t11);

var cell12=row.insertCell(14);
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


var cell13=row.insertCell(15);
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


var cell13=row.insertCell(16);
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


var cell13=row.insertCell(17);
var t13=document.createElement("input");
t13.style="display: table-cell; width:80px;";
t13.type="text";
t13.step="0.01";
t13.readOnly=true;
t13.className="FREIGHT";
t13.id = "freight_amt"+index;
t13.name= "freight_amt[]";
t13.value="0";
cell13.appendChild(t13);
document.getElementById("freight_amt"+index).style.display='value';


var cell14=row.insertCell(18);
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

var cell15=row.insertCell(19);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertRow(); mycalc();");
cell15.appendChild(btnAdd);

var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRow(this)");
cell15.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_2').find('tr').eq( index );

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

index++;
recalcId();



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

 


	var sum = 0.0;
				var amounts = document.getElementsByClassName('ROWCOUNT');
                for(var i=0; i<amounts .length; i++)
                {
                    var a = +amounts[i].value;
                    sum += parseFloat(a) || 0;
                }
				 document.getElementById("cnt").value = sum;



}






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
var sale_cgst=document.getElementById('sale_cgst').value;
var sale_sgst=document.getElementById('sale_sgst').value;
var sale_igst=document.getElementById('sale_igst').value;

var tax_type_id1=document.getElementById('tax_type_id').value;
if(tax_type_id1==2)
{
var iamt=  parseFloat(( amount*(sale_igst/100))).toFixed(2);
$('#iamt').val(iamt);

$('#total_amount').val(parseFloat(amount) + parseFloat(iamt));

}
else{
var camt=  parseFloat(( amount*(sale_cgst/100))).toFixed(2);
$('#camt').val(camt);
var samt= parseFloat(( amount*(sale_sgst/100))).toFixed(2);
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

$("#sale_code").val(response["code"]+'-'+response["tr_no"]);
$("#c_code").val(response["c_code"]);

}
});
}




$('#footable_2').on('change', '.item', function() 
 {
 
    var tax_type_ids=document.getElementById('tax_type_id').value;
    var item_code = $(this).val();
    var row = $(this).closest('tr'); // get the row
    
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('GSTPER') }}",
       data:{item_code:item_code,tax_type_id:tax_type_ids},
        success: function(data){

             console.log(data); 
            
            if(tax_type_ids==1)
            {
                row.find('input[name^="sale_cgsts[]"]').val(data[0]['cgst_per']);
                row.find('input[name^="sale_sgsts[]"]').val(data[0]['sgst_per']);
                row.find('input[name^="sale_igsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                 row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
               
            }
            else if(tax_type_ids==2)
            {
                row.find('input[name^="sale_igsts[]"]').val(data[0]['igst_per']);
                row.find('input[name^="sale_cgsts[]"]').val(0);
                row.find('input[name^="sale_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
               row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
            }
            else if(tax_type_ids==3)
            {
                row.find('input[name^="sale_igsts[]"]').val(0);
                row.find('input[name^="sale_cgsts[]"]').val(0);
                row.find('input[name^="sale_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
                row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
            }
      
        }
        });

});


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
        
         $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('NewSalesOrderList') }}",
            data:{'Ac_code':ac_code},
            success: function(response)
            {
                $("#sales_order_nos").html(response.html);
            }
        });
        
        
        
}


  $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="sale_cgsts[]"],input[name^="camts[]"],input[name^="sale_sgsts[]"],input[name^="sale_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
        CalculateRow($(this).closest("tr"));

        
    });



    function CalculateRow(row)
    {

        var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
        var item_rates=+row.find('input[name^="item_rates[]"]').val();
        var disc_pers=+row.find('input[name^="disc_pers[]"]').val();
        var disc_amounts=+row.find('input[name^="disc_amounts[]"]').val();
        var sale_cgsts=  +row.find('input[name^="sale_cgsts[]"]').val();
        var camts= +row.find('input[name^="camts[]"]').val();
        var sale_sgsts= +row.find('input[name^="sale_sgsts[]"]').val();
        var samts= +row.find('input[name^="samts[]"]').val();
        var sale_igsts= +row.find('input[name^="sale_igsts[]"]').val();
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
              
             if(sale_igsts!=0)
             {
                  Iamt=(Amount*(sale_igsts/100));
                  row.find('input[name^="iamts[]"]').val((Iamt).toFixed(2));
                  TAmount=Amount+Iamt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
             }
             else
             {
                  Camt=(Amount*(sale_cgsts/100));
                  row.find('input[name^="camts[]"]').val((Camt).toFixed(2));
                  
                  Samt=(Amount*(sale_sgsts/100));
                  row.find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                  
                  TAmount=Amount+Camt+Samt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                  
             }
             
        }
             
                  mycalc();
}


function getSalesOrder( ){
      
var  sales_order_nos = $("#sales_order_nos option:selected").map(function() {
  return this.value;
}).get().join(",");
   
var tax_type_id=document.getElementById("tax_type_id").value;
 
$.ajax({
type:"GET",
url:"{{ route('getSalesOrderData') }}",
//dataType:"json",
data:{sales_order_nos:sales_order_nos,tax_type_id:tax_type_id},
success:function(response){
console.log(response);  
    $("#bomdis").html(response.html);
 mycalc();
}
});
}

</script>
@endsection