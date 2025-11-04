@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Requisition</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Requisition</li>
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
<h4 class="card-title mb-4">Requisition</h4>
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

<form action="{{route('Requisition.update',$requisitionfetch)}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@method('put')    
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id">
<option value="">--- Select Firm ---</option>
@foreach($firmlist as  $row)
{
<option value="{{ $row->firm_id }}"

{{ $row->firm_id == $requisitionfetch->firm_id ? 'selected="selected"' : '' }}

  >{{ $row->firm_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Requisition No</label>
<input type="text" name="requisitionNo" class="form-control" id="requisitionNo" value="{{ $requisitionfetch->requisitionNo }}" readonly="readonly">
<input type="hidden" name="c_code" id="c_code" value="{{ $requisitionfetch->c_code }}" />
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Date</label>
<input type="date" name="requisitionDate" class="form-control" id="formrow-email-input" value="{{ $requisitionfetch->requisitionDate }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Requisition Type</label>
<select name="requisitionTypeId" class="form-select" id="requisitionTypeId">
<option value="">--- Select Requisition Type---</option>
@foreach($requisitiontypelist as  $rowrequisitionlist)
{
<option value="{{ $rowrequisitionlist->requisitionId  }}"

{{ $rowrequisitionlist->requisitionId == $requisitionfetch->requisitionTypeId ? 'selected="selected"' : '' }}

  >{{ $rowrequisitionlist->requisitiontype }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Department</label>
<select name="dept_id" class="form-select" id="dept_id">
<option value="">--- Select Department---</option>
@foreach($departmentlist as  $rowdepartment)
{
<option value="{{ $rowdepartment->dept_id  }}"

{{ $rowdepartment->dept_id == $requisitionfetch->dept_id ? 'selected="selected"' : '' }}

  >{{ $rowdepartment->dept_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Machine</label>
<select name="machineId" class="form-select" id="machineId">
<option value="">--- Select Machine---</option>
@foreach($machinelist as  $rowmachine)
{
<option value="{{ $rowmachine->machineId  }}"

{{ $rowmachine->machineId == $requisitionfetch->machineId ? 'selected="selected"' : '' }}

  >{{ $rowmachine->machineName }}</option>

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
<th>Unit</th>
<th>Requested QTY</th>
<th>Stock QTY</th>
<th>APPROVED QTY</th>
<th>Remove</th>
</tr>
</thead>
<tbody>

@php  if($detailrequisition->isEmpty()) { @endphp


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
<td> <select name="unit_id[]" class="unit_id" id="unit_id" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>
<td><input type="text"   name="requestedQty[]"   value="0" id="requestedQty" style="width:80px;" required/></td>
<td><input type="text"   name="stockQty[]"  value="0" class="RATE"  id="stockQty" style="width:80px;" required/></td>
<td><input type="text"   name="approvedQty[]"  value="0" class="pur_cgsts"  id="approvedQty" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

  @php } else { @endphp
  @php $no=1; @endphp
@foreach($detailrequisition as $rowdetail)

<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" class="item" id="item_code" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}"

{{ $rowitem->item_code == $rowdetail->item_code ? 'selected="selected"' : '' }}


  >{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>
<td> <select name="unit_id[]" class="unit_id" id="unit_id" style="width:100px;">
<option value="">--- Select Item ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}"


{{ $rowunit->unit_id == $rowdetail->unit_id ? 'selected="selected"' : '' }}

  >{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>
<td><input type="text"   name="requestedQty[]"    id="requestedQty" style="width:80px;" value="{{ $rowdetail->requestedQty }}" required/></td>


<td><input type="text"   name="stockQty[]"   class="RATE"  id="stockQty" style="width:80px;" value="{{ $rowdetail->stockQty }}" required/></td>
<td><input type="text"   name="approvedQty[]"  value="{{ $rowdetail->approvedQty }}" class="pur_cgsts"  id="approvedQty" style="width:80px;" required/></td>

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
<th>Unit</th>
<th>Requested QTY</th>
<th>Stock QTY</th>
<th>APPROVED QTY</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
<input type="hidden"   name="cnt" id="cnt" value="{{ count($detailrequisition) }}">  
<br/>

<div class="row">
<div class="col-md-4">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Issue To</label>
<input type="text" name="issueTo" class="form-control" id="issueTo" value="{{ $requisitionfetch->issueTo }}">
</div>
</div>
<div class="col-md-4">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Reason</label>
<select name="reasonId" class="form-select" id="reasonId">
<option value="">--- Select Reason---</option>
@foreach($reasonlist as  $rowreason)
{
<option value="{{ $rowreason->reasonId  }}"

{{ $rowreason->reasonId == $requisitionfetch->reasonId ? 'selected="selected"' : '' }}


  >{{ $rowreason->reason }}</option>

}
@endforeach
</select>
</div>
</div>  
<div class="col-md-4">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Approve</label>
<input class="form-check-input" type="hidden" name="requisitionApproveFlag" value="0" id="requisitionApproveFlag">
<input class="form-check-input" type="checkbox" name="requisitionApproveFlag" value="1" id="requisitionApproveFlag"  @if($requisitionfetch->requisitionApproveFlag==1) checked="Checked" @endif>
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


var cell2 = row.insertCell(2);
var t2=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
y.width(100);
y.appendTo(cell2);



var cell3 = row.insertCell(3);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;";
t3.type="number";
//t3.className="QTY";
t3.id = "requestedQty"+index;
t3.name="requestedQtys[]";
t3.value=document.getElementById("requestedQty").value;
cell3.appendChild(t3);

var cell4 = row.insertCell(4);
var t4=document.createElement("input");
t4.style="display: table-cell; width:80px;";
t4.type="number";
//t3.className="QTY";
t4.id = "stockQtys"+index;
t4.name="stockQtys[]";
t4.value=document.getElementById("stockQty").value;
cell4.appendChild(t4);

var cell5=row.insertCell(5);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="number";
t5.step="0.01";
t5.id = "approvedQtys"+index;
t5.name= "approvedQtys[]";
t5.value=document.getElementById("approvedQty").value;
cell5.appendChild(t5);
//document.getElementById("total_amounts"+index).style.display='value';  

var cell15=row.insertCell(6);
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
 
    var item_code = $(this).val();
    var row = $(this).closest('tr'); // get the row
    
    $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('GETSTOCK') }}",
       data:{item_code:item_code},
        success: function(data){

             console.log(data); 
    row.find('input[name^="stockQty[]"]').val(data[0]['Stock']);
      
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



     $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="total_amounts[]"]', function (event) {
        CalculateRow($(this).closest("tr"));

        
    });



    function CalculateRow(row)
    {



        var item_qtys=+row.find('input[name^="item_qtys[]"]').val();

        var item_rates=+row.find('input[name^="item_rates[]"]').val();


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