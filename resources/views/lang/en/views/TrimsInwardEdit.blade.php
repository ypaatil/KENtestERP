@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Trims Inward</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Trims Inward</li>
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
<form action="{{route('TrimsInward.update',$purchasefetch)}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@method('put')
@csrf    
    
    
<h4 class="card-title mb-4">Trims Inwar: {{ $purchasefetch->trimCode }}</h4>
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
<label for="formrow-email-input" class="form-label">Trim Date</label>
<input type="date" name="trimDate" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->trimDate  }}" required>
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
<input type="hidden" name="trimCode" class="form-control" id="trimCode" value="{{ $purchasefetch->trimCode }}" readonly="readonly">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Invoice No</label>
<input type="text" name="invoice_no" id="invoice_no" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->invoice_no }}"  >
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-invoice_date-input" class="form-label">Invoice Date</label>
<input type="date" name="invoice_date" id="invoice_date" class="form-control" id="formrow-invoice_date-input" value="{{ $purchasefetch->invoice_date }}">
</div>
</div>




<div class="col-md-2">
<div class="mb-3">
<label for="po_code" class="form-label">PO Code</label>   
<select name="po_code" class="form-select" id="po_code" onchange="getPODetails();FetchPoData();"   >
<option value="">PO code</option>
@foreach($POList as  $rowpol)
{
    <option value="{{ $rowpol->pur_code  }}"
    
    {{ $rowpol->pur_code == $purchasefetch->po_code ? 'selected="selected"' : '' }}
    >{{ $rowpol->pur_code }}</option>
}
@endforeach
</select>
<input type="hidden" name="po_codenew" id="po_codenew" class="form-control"  value="{{ request()->po_code;  }}">

</div>
</div>
 
 <div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">PO Type</label>
<select name="po_type_id" class="form-select" id="po_type_id" onchange="getPartyDetails();">
<option value="">PO Type</option>
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
 
 
<div class="col-md-2">
  <div class="form-check form-check-primary mb-5">
    <input class="form-check-input" type="checkbox" id="is_opening" name="is_opening"  @php if($purchasefetch->is_opening==1){echo 'checked'; } @endphp>
    <label class="form-check-label" for="is_opening">
    Opening Stock
</label>
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
<th>Item Name</th>
<th>Unit</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th>Rack Location</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody id="bomdis">

@php  if($detailpurchase->isEmpty()) { @endphp

<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" class="item" id="item_code" class="select2" style="width:200px;height:30px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>

 

<td> <select name="unit_id[]" class="unit_ids" id="unit_ids"    style="width:100px;height:30px;">
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>


<td><input type="number" step="any"  name="item_qtys[]" class="QTY"  value="0" id="item_qty" style="width:80px;height:30px;" required/>

 <input type="hidden"   name="hsn_codes[]"   value="0" id="hsn_codes" style="width:80px;height:30px;"  required/></td>
<td><input type="number" step="any"  name="item_rates[]"    value="0" id="item_rates" style="width:80px;height:30px;" required/></td>
<td><input type="number" step="any" readOnly name="amounts[]" class="AMT"  value="0" id="amounts" style="width:80px;height:30px;" required/>
</td>


 <td> <select name="rack_id[]"  id="rack_id"  class="select2" style="width:100px;height:30px;" required>
<option value="">--Racks--</option>
@foreach($RackList as  $row)
{
    <option value="{{ $row->rack_id }}"
    
    >{{ $row->rack_name }}</option>
}
@endforeach
</select></td>

<td><button type="button" onclick=" mycalc();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

    @php } else { @endphp
  @php $no=1; @endphp
@foreach($detailpurchase as $row)

 <tr>
<td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
 <td>
<select name="item_codes[]" class="select2" id="item_codes"  class="select2" style="width:200px;height:30px;" onchange="GetUnit(this);">
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
<td> <select name="unit_ids[]"   id="unit_ids"   style="width:100px;height:30px;">
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}"

{{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}

    >{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>

<td><input   type="number" step="any" class="QTY" name="item_qtys[]" value="{{ $row->item_qty }}" style="width:80px;height:30px;"  id="item_qty">
<input type="hidden" name="hsn_codes[]"   value="{{ $row->hsn_code }}" id="hsn_codes" style="width:80px;height:30px;" required/>
</td>

<td><input type="number" step="any"  name="item_rates[]"    value="{{ $row->item_rate }}" id="item_rates" style="width:80px;height:30px;" required/>
<td><input type="number" step="any" readOnly  name="amounts[]" class="AMT"  value="{{ $row->amount }}" id="amounts" style="width:80px;height:30px;" required/>


 <td> <select name="rack_id[]"  id="rack_id" class="select2" style="width:100px;height:30px;" required>
<option value="">--Racks--</option>
@foreach($RackList as  $rowrack)
{
    <option value="{{ $rowrack->rack_id }}"
    {{ $rowrack->rack_id == $row->rack_id ? 'selected="selected"' : '' }}   
    >{{ $rowrack->rack_name }}</option>
}
@endforeach
</select></td>

<td><button type="button" onclick=" mycalc();" class="Abutton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
 </tr>
 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp

 </tbody>
<tfoot>
<tr>
<th>SrNo</th>
<th>Item Name</th>
<th>Unit</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th>Rack Location</th>
<th>Add/Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
<br/>
<input type="hidden"   name="cnt" id="cnt" value="{{ count($detailpurchase) }}">  
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Quantity</label>
<input type="text" name="totalqty" class="form-control" id="totalqty" value="{{ $purchasefetch->totalqty }}" required>
</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Amount</label>
<input type="text" name="total_amount" class="form-control" id="total_amount" value="{{ $purchasefetch->total_amount }}" required>
</div>
</div>

</div>

     </br>  
<button type="submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
<a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
 
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

 
  $(document).on("click", '.Abutton', function (event) {
        insertRow($(this).closest("tr"));
         
    });
 
 
 
var index = 1;
function insertRow(Abutton){
      var rowsx=$(Abutton).closest("tr");
$("#item_codes").select2("destroy");
$("#rack_id").select2("destroy");
var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px; height:30px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+index;
t1.name= "id[]";
t1.value=index;
cell1.appendChild(t1);

var cell5 = row.insertCell(1);
var t5=document.createElement("select");
var x = $("#item_codes"),
y = x.clone();
y.attr("id","item_codes");
y.attr("name","item_codes[]");
y.width(200);
y.height(30);
y.appendTo(cell5);
    
var cell2 = row.insertCell(2);
var t2=document.createElement("select");
var x = $("#unit_ids"),
y = x.clone();
y.attr("id","unit_ids");
y.attr("name","unit_ids[]");
y.width(100);
y.height(30);
var unit_id=+rowsx.find('select[name^="unit_ids[]"]').val();
y.val(unit_id);
y.attr("selected","selected"); 
y.appendTo(cell2);


var cell3 = row.insertCell(3);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
t3.step="any";
t3.required="true";
t3.className="QTY";
t3.id = "item_qtys"+index;
t3.name="item_qtys[]";
t3.value="0";
cell3.appendChild(t3);


var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="hidden";
t3.id = "hsn_codes"+index;
t3.name="hsn_codes[]";
t3.value="0";
cell3.appendChild(t3);


var cell3 = row.insertCell(4);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
t3.step="any";
t3.required="true";
t3.id = "item_rates"+index;
t3.name="item_rates[]";
t3.value="0";
cell3.appendChild(t3);

var cell3 = row.insertCell(5);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
t3.readOnly="true";
t3.step="any";
t3.className="AMT";
t3.required="true";
t3.id = "amounts"+index;
t3.name="amounts[]";
t3.value="0";
cell3.appendChild(t3);
 
 
 var cell2 = row.insertCell(6);
var t2=document.createElement("select");
var x = $("#rack_id"),
y = x.clone();
y.attr("id","rack_id");
y.attr("name","rack_id[]");
y.width(100);
y.height(30);
var unit_id=+rowsx.find('select[name^="rack_id[]"]').val();
y.val(unit_id);
y.attr("selected","selected"); 
y.appendTo(cell2);

 
 
 
 
var cell15=row.insertCell(7);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left Abutton";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "  mycalc();");
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
 mycalc();
 selselect();

}

  



 
  
 function GetUnit(row)
 
 { 
    
   var tax_type_ids=1;
    var item_code = $(row).val();
    var row = $(row).closest('tr'); // get the row
    
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('GSTPER') }}",
       data:{item_code:item_code,tax_type_id:tax_type_ids},
        success: function(data){

             console.log(data); 
                  row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_ids[]"]').val(data[0]['unit_id']);
                  
               }
      
        
        });
        
 }





function selselect()
 {
     setTimeout(
  function() 
  {

  $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
 
    $(this).closest("tr").find('select[name="item_codes[]"]').select2();
    $(this).closest("tr").find('select[name="rack_id[]"]').select2();

    });
 }, 1000);
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

 function getPODetails()
{
   
    var po_code=$("#po_code").val();
    
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('PODetail') }}",
            data:{'po_code':po_code},
            success: function(data){
                
                $("#po_type_id").val(data[0]['po_type_id']);
                $("#Ac_code").val(data[0]['Ac_code']);
                 
                   
                 
                var qhtml=data[0]["ac_name"];
                var q=data[0]['Ac_code'];
                $('#Ac_code').val(data[0]["Ac_code"]);
                $('#select2-chosen-1').html(qhtml);
                
                
                
               
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
document.getElementById("totalqty").value = sum1.toFixed(2);

 
sum1 = 0.0;
var amounts = document.getElementsByClassName('AMT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_amount").value = sum1.toFixed(2);

}




function frieght_payable()
{
    var Net_amount=document.getElementById('Net_amount').value;
    var freight_amt=$('#freight_amt').val();

var payable_amount=(parseFloat(Net_amount) + (parseFloat(freight_amt)));
$('#payable_amt').val(parseFloat(payable_amount).toFixed(0));

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
// item_code = document.getElementById("item_code").value;  

// calculate_gst(item_code);

}

var tax_type_id =1;
function calculate_gst(item_code)
{


tax_type_ids =document.getElementById("tax_type_id").value

$.ajax(
{
type:"GET",
dataType:'json',
url: "{{ route('GSTPER') }}",
data:{item_code:item_code,tax_type_id:tax_type_ids},
success:function(response)
{

  console.log(response);  

   if(tax_type_id==1)
            {

  $("#pur_cgst").val(response[0].cgst_per); 
  $("#pur_sgst").val(response[0].sgst_per); 
  $("#pur_igst").val(response[0].igst_per);
} else{

$("#pur_igst").val(response[0].igst_per);

}

}

});
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



function getPartyDetails()
{
    // var ac_code=$("#Ac_code").val();
    
    // $.ajax({
    //         type: "GET",
    //         dataType:"json",
    //         url: "{{ route('PartyDetail') }}",
    //         data:{'ac_code':ac_code},
    //         success: function(data)
    //         {
    //             $("#gstNo").val(data[0]['gst_no']);
               
    //         }
    //     });
}




    //  $(document).on("keyup", 'input[name^="item_qtys[]"]', function (event) {
      
    //         mycalc();
    // });


    $(document).on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"]', function (event) {
        CalculateRow($(this).closest("tr"));
       });
    function CalculateRow(row)
    {
        var item_qtys=+row.find('input[name^="item_qtys[]"]').val();
        var item_rates=+row.find('input[name^="item_rates[]"]').val();
        var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed();
        row.find('input[name^="amounts[]"]').val(amount);
        mycalc();
    }

function getBomDetail(type){
    
    
    var  bom_codes = $("#bom_code option:selected").map(function() {
      return this.value;
    }).get().join(",");
    
    
   // alert(bom_codes);
// var bom_code=document.getElementById("bom_code").value;

var tax_type_id=document.getElementById("tax_type_id").value;


$.ajax({
type:"GET",
url:"{{ route('getBoMDetail') }}",
//dataType:"json",
data:{type:type,bom_code:bom_codes,tax_type_id:tax_type_id},
success:function(response){
console.log(response);  
    $("#bomdis").append(response.html);
 mycalc();
}
});
}





 
$(document).ready(function(){

FetchPoData();

}); 

function FetchPoData()
{
    var po_code=document.getElementById('po_code').value;  
    if(po_code !="" && po_code!=0)
    {
        $("#Ac_code").select2("destroy");     
        gettable(po_code);
        getPODetails();

    }
}



setInterval(function() {mycalc()}, 1000);

//  setInterval(fun, 3000);  

function gettable(po_code){

   
var po_codes=btoa(po_code);
$.ajax({
type:"GET",
url:"{{ route('getPoForTrims') }}",
//dataType:"json",
data:{po_code:po_codes},
success:function(response){
console.log(response);  
    $("#trimInward").html(response.html);

}
});

}
 
 function EnableFields()
{
     $("select").prop('disabled', false);
}
 
 
 
function getDetails(po_code){

$.ajax({
type:"GET",
url:"{{ route('getPoMasterDetailTrims') }}",
//dataType:"json",
data:{po_code:po_code},
success:function(response){
console.log(response);

$("#Ac_code").val(response[0].Ac_code);
$("#tax_type_id").val(response[0].tax_type_id);
$("#supplierRef").val(response[0].supplierRef);
$("#pur_date").val(response[0].pur_date);
$("#po_type_id").val(response[0].po_type_id);
$("#bomtype").val(response[0].bomtype);
$("#bom_code").val(response[0].bom_code);
$("#in_narration").val(response[0].narration);
$("#po_code").val(response[0].pur_code);


}
});
} 

</script>
@endsection