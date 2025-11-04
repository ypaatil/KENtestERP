@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">General Purchase</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">General Purchase</li>
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
<h4 class="card-title mb-4">General Purchase</h4>
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

<form action="{{route('OtherPurchase.store')}}" method="POST">
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
@foreach($code as  $row)
<input type="text" name="pur_code" class="form-control" id="pur_code" value="{{ 'PC'.'-'.$row->tr_no }}" readonly="readonly">
<input type="hidden" name="c_code" id="c_code" value="{{ $row->c_code }}" />
@endforeach
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Purchase Date</label>
<input type="date" name="pur_date" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Purchase Bill No</label>
<input type="text" name="pur_bill_no" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Purchase Bill Date</label>
<input type="date" name="pur_bill_date" class="form-control" id="formrow-email-input">
</div>
</div>


</div>

<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Party Name</label>
<select name="Ac_code" class="form-select" id="Ac_code">
<option value="">--- Select Party Name ---</option>
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
<label for="formrow-inputState" class="form-label">GST</label>
<select name="tax_type_id" class="form-select" id="tax_type_id" onChange="divideBy(this.value);">
<option value="">--- Select Gst---</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Purchase Ledger</label>
<select name="Purchase_Ac_code" class="form-select" id="Purchase_Ac_code">
<option value="">--- Select Party Name ---</option>
@foreach($ledgerlist as  $rowledger)
{
<option value="{{ $rowledger->ac_code  }}">{{ $rowledger->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>

</div>



<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
<thead>
<tr>
<th><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button></th>
<th>Item Name</th>
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
<th>Remove</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" class="item" id="item_code" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>

<td><input type="text"   name="item_qtys[]"   value="0" id="item_qty" style="width:80px;" required/></td>
<td><input type="text"   name="item_rates[]"  value="0" class="RATE"  id="item_rate" style="width:80px;" required/></td>
<td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
<td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" required/></td>
<td><input type="text"   name="pur_cgsts[]"  value="0" class="pur_cgsts"  id="pur_cgst" style="width:80px;" required/></td>
<td><input type="text"   name="camts[]"  value="0" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_sgsts[]"  value="0" class=""  id="pur_sgst" style="width:80px;" required/></td>
<td><input type="text"   name="samts[]"  value="0" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_igsts[]"  value="0" class=""  id="pur_igst" style="width:80px;" required/></td>
<td><input type="text"   name="iamts[]"  value="0" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
<td><input type="text"   name="amounts[]"  value="0" class="GROSS"  id="amount" style="width:80px;" required/></td>
<td><input type="text"   name="total_amounts[]"  class="TOTAMT" value="0"  id="total_amount" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>


 </tbody>
<tfoot>
<tr>
<th></th>
<th>Item Name</th>
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
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
<input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
<br/>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Gross Amount</label>
<input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="Gst_amount" class="form-control" id="Gst_amount">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Net Amount</label>
<input type="text" name="Net_amount" class="form-control" id="Net_amount">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">TDS %</label>
<input type="text" name="tds_per" class="form-control" id="tds_per" onkeyup="tds_payable();" value="0">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">TDS Amount</label>
<input type="text" name="tds_amt" class="form-control" id="tds_amt" value="0">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Payable Amount</label>
<input type="text" name="payable_amt" class="form-control" id="payable_amt" value="0">
</div>
</div>


</div>

<div class="row">
<div class="col-md-4">	
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

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
y.attr("id","item_code");
y.attr("name","item_codes[]");
y.width(100);
y.appendTo(cell2);


var cell3 = row.insertCell(2);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
//t3.className="QTY";
t3.id = "item_qtys"+index;
t3.name="item_qtys[]";
t3.value=document.getElementById("item_qty").value;
cell3.appendChild(t3);

var cell4=row.insertCell(3);
var t4=document.createElement("input");
t4.style="display: table-cell; width:80px;";
t4.type="number";
t4.step="0.01";
t4.id = "item_rates"+index;
t4.name= "item_rates[]";
t4.value=document.getElementById("item_rate").value;
cell4.appendChild(t4);


var cell5=row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="number";
t5.step="0.01";
t5.id = "disc_pers"+index;
t5.name= "disc_pers[]";
t5.value=document.getElementById("disc_per").value;
cell5.appendChild(t5);


var cell6=row.insertCell(5);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="number";
t6.step="0.01";
t6.id = "disc_amounts"+index;
t6.name= "disc_amounts[]";
t6.value=document.getElementById("disc_amount").value;
cell6.appendChild(t6);

var cell7=row.insertCell(6);
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="number";
t7.step="0.01";
t7.id = "pur_cgsts"+index;
t7.name= "pur_cgsts[]";
t7.value=document.getElementById("pur_cgst").value;
cell7.appendChild(t7);


var cell8=row.insertCell(7);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;";
t8.type="number";
t8.step="0.01";
t8.className="GSTAMT";
t8.id = "camts"+index;
t8.name= "camts[]";
t8.value=document.getElementById("camt").value;
cell8.appendChild(t8);


var cell9=row.insertCell(8);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="number";
t9.step="0.01";
t9.id = "pur_sgsts"+index;
t9.name= "pur_sgsts[]";
t9.value=document.getElementById("pur_sgst").value;
cell9.appendChild(t9);

var cell10=row.insertCell(9);
var t10=document.createElement("input");
t10.style="display: table-cell; width:80px;";
t10.type="number";
t10.step="0.01";
t10.className="GSTAMT";
t10.id = "samts"+index;
t10.name= "samts[]";
t10.value=document.getElementById("samt").value;
cell10.appendChild(t10);


var cell11=row.insertCell(10);
var t11=document.createElement("input");
t11.style="display: table-cell; width:80px;";
t11.type="number";
t11.step="0.01";
t11.id = "pur_igsts"+index;
t11.name= "pur_igsts[]";
t11.value=document.getElementById("pur_igst").value;
cell11.appendChild(t11);

var cell12=row.insertCell(11);
var t12=document.createElement("input");
t12.style="display: table-cell; width:80px;";
t12.type="number";
t12.step="0.01";
t12.className="GSTAMT";
t12.id = "iamts"+index;
t12.name= "iamts[]";
t12.value=document.getElementById("iamt").value;
cell12.appendChild(t12);


var cell13=row.insertCell(12);
var t13=document.createElement("input");
t13.style="display: table-cell; width:80px;";
t13.type="number";
t13.step="0.01";
t13.className="GROSS";
t13.id = "amounts"+index;
t13.name= "amounts[]";
t13.value=document.getElementById("amount").value;
cell13.appendChild(t13);
document.getElementById("amounts"+index).style.display='value'; 

var cell14=row.insertCell(13);
var t14=document.createElement("input");
t14.style="display: table-cell; width:80px;";
t14.type="number";
t14.step="0.01";
t14.className='TOTAMT';
t14.id = "total_amounts"+index;
t14.name= "total_amounts[]";
t14.value=document.getElementById("total_amount").value;
cell14.appendChild(t14);
//document.getElementById("total_amounts"+index).style.display='value';  

var cell15=row.insertCell(14);
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


sum1 = 0.0;
var amounts = document.getElementsByClassName('GROSS');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Gross_amount").value = sum1.toFixed(2);;



sum1 = 0.0;
var amounts = document.getElementsByClassName('GSTAMT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Gst_amount").value = sum1.toFixed(2);;

sum1 = 0.0;
var amounts = document.getElementsByClassName('TOTAMT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("Net_amount").value = sum1.toFixed(0);;


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
item_code = document.getElementById("item_code").value;  

calculate_gst(item_code);

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
                        row.find('input[name^="pur_cgsts[]"]').val(data[0]['cgst_per']);
                        row.find('input[name^="pur_sgsts[]"]').val(data[0]['sgst_per']);
                        row.find('input[name^="pur_igsts[]"]').val();
            }
            else
            {
                row.find('input[name^="pur_igsts[]"]').val(data[0]['igst_per']);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
            }
      
        }
        });

});



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



     $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="total_amounts[]"]', function (event) {
        CalculateRow($(this).closest("tr"));

        
    });



    function CalculateRow(row)
    {



        var item_qtys=+row.find('input[name^="item_qtys[]"]').val();

        var item_rates=+row.find('input[name^="item_rates[]"]').val();

        var disc_pers=+row.find('input[name^="disc_pers[]"]').val();

        var disc_amounts=+row.find('input[name^="disc_amounts[]"]').val();

        var pur_cgsts=  +row.find('input[name^="pur_cgsts[]"]').val();

        var camts= +row.find('input[name^="camts[]"]').val();

        var pur_sgsts= +row.find('input[name^="pur_sgsts[]"]').val();

        var samts= +row.find('input[name^="samts[]"]').val();

        var pur_igsts= +row.find('input[name^="pur_igsts[]"]').val();

        var iamts= +row.find('input[name^="iamts[]"]').val();

        var amounts= +row.find('input[name^="amounts[]"]').val();

        var total_amounts= +row.find('input[name^="total_amounts[]"]').val();


       tax_type_id =document.getElementById("tax_type_id").value;
        
        
             
         if(item_qtys>0)
         {
            
                 Amount=item_qtys*item_rates;
                 disc_amt=(Amount*(disc_pers/100));
                 row.find('input[name^="disc_amounts[]"]').val((disc_amt).toFixed(2));
                 Amount=Amount-disc_amt;

                 row.find('input[name^="amounts[]"]').val((Amount).toFixed(2));
                         
             
             if(pur_igsts!=0)
             {
                  Iamt=(Amount*(pur_igsts/100));
                  row.find('input[name^="pur_iamt[]"]').val((Iamt).toFixed(2));
                  TAmount=Amount+Iamt;
                  row.find('input[name^="pur_tamt[]"]').val((TAmount).toFixed(2));
             }
             else
             {
                  Camt=(Amount*(pur_cgsts/100));
                  row.find('input[name^="camts[]"]').val((Camt).toFixed(2));
                  
                  Samt=(Amount*(pur_sgsts/100));
                  row.find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                  
                  TAmount=Amount+Camt+Samt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                  
                 
             }
             
        }
             
                  mycalc();
}






</script>

@endsection