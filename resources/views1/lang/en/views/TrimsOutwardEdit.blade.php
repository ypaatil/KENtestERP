@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Trims Outward</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Trims Outward</li>
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
<form action="{{route('TrimsOutward.update',$purchasefetch)}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@method('put')
@csrf    
    
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
<input type="date" name="trimDate" class="form-control" id="formrow-email-input" value="{{ $purchasefetch->tout_date  }}" required>
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
<input type="hidden" name="trimOutCode" value="{{ $purchasefetch->trimOutCode  }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="c_code" value="{{ $purchasefetch->c_code  }}" class="form-control" id="formrow-email-input">
</div>
</div>

 <div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label"> Order Type</label>
<select name="trim_type" class="form-control" id="trim_type" required   >
<option value="">--Trims Type--</option>
<option value="1" @php if($purchasefetch->trim_type==1){echo "selected";} @endphp>Sewing Trims</option>
<option value="2" @php if($purchasefetch->trim_type==1){echo "selected";} @endphp>Packing Trims</option>
 
</select>
</div>
</div>


 <div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control select2" id="vendorId" required  onchange="getvendorList(this.value);">
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}"
    
    {{ $rowvendor->ac_code == $purchasefetch->vendorId ? 'selected="selected"' : '' }}
    
    >{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="vw_code" class="form-label">Work Order</label>
<select name="vw_code" class="form-select select2" id="vw_code"     onchange="getvendorMasterList(this.value);getvendordata(this.value);">
 <option value="">--Vendor Code No--</option>
 
 @foreach($vendorcodeList as  $rowvendorcode)
{
    <option value="{{ $rowvendorcode->vw_code }}"
    
     {{ $rowvendorcode->vw_code == $purchasefetch->vw_code ? 'selected="selected"' : '' }}
    
    
    >{{ $rowvendorcode->vw_code }}</option>
}
@endforeach
            
</select>
</div>
</div> 
 
 <div class="col-md-2">
<div class="mb-3">
<label for="vpo_code" class="form-label">Process Order</label>
<select name="vpo_code" class="form-select select2" id="vpo_code"     onchange="getVendorProcessDetails(this.value);getProcessTrimData(this.value);">
 <option value="">--Vendor Code No--</option>
 
 @foreach($vendorProcessList as  $rowPO)
{
    <option value="{{ $rowPO->vpo_code }}"
    
     {{ $rowPO->vpo_code == $purchasefetch->vpo_code ? 'selected="selected"' : '' }}
    
    
    >{{ $rowPO->vpo_code }}</option>
}
@endforeach
            
</select>
</div>
</div> 
 
 
 
 
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value);" required>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
    
    
     {{ $row->mainstyle_id == $purchasefetch->mainstyle_id ? 'selected="selected"' : '' }}
    
    >{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
     
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $rowsyb)
{
    <option value="{{ $rowsyb->substyle_id }}"
    
   {{ $rowsyb->substyle_id == $purchasefetch->substyle_id ? 'selected="selected"' : '' }}   
    
    
   >{{ $rowsyb->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
      
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-control" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $rowstyle)
{
    <option value="{{ $rowstyle->fg_id }}"
    
 {{ $rowstyle->fg_id == $purchasefetch->fg_id ? 'selected="selected"' : '' }}   
    
    >{{ $rowstyle->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{ $purchasefetch->style_no  }}" required readOnly>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{ $purchasefetch->style_description  }}" required readOnly>

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
<th>HSN</th>
<th>Unit</th>
<th>Quantity</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody id="bomdis">

@php  if($detailpurchase->isEmpty()) { @endphp

<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" class="item" id="item_codes" class="select2" style="width:200px;height:30px;">
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
{
<option value="{{ $rowitem->item_code  }}">{{ $rowitem->item_name }}</option>

}
@endforeach
</select></td>

<td>
    <input type="text"   name="hsn_code[]"   value="0" id="hsn_code" style="width:80px;height:30px;"  required/></td>

<td> <select name="unit_id[]" class="unit_id" id="unit_ids"   class="select2" style="width:100px;height:30px;">
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>


<td><input type="text"   name="item_qtys[]"   value="0" id="item_qty" style="width:80px;height:30px;" required/></td>

<td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

    @php } else { @endphp
  @php $no=1; @endphp
@foreach($detailpurchase as $row)

 <tr>
<td><input type="text" name="id" value="{{ $no }}" id="id"  style="width:50px;"/></td>
 <td>
<select name="item_codes[]" class="item" id="item_codes"  class="select2" style="width:200px;height:30px;">
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

<td>
    
<input type="text"   name="hsn_code[]"   value="{{ $row->hsn_code }}" id="hsn_code" style="width:80px;height:30px;" required/></td>

<td> <select name="unit_id[]" class="unit_id" id="unit_ids" class="select2" style="width:100px;height:30px;">
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}"

{{ $rowunit->unit_id == $row->unit_id ? 'selected="selected"' : '' }}

    >{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>

<td><input style="width:80px;" type="text" class="Qty" name="item_qtys[]" value="{{ $row->item_qty }}" style="width:80px;height:30px;"  id="item_qty">
</td>
<td><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
 </tr>
 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp

 </tbody>
<tfoot>
<tr>
<th>SrNo</th>
<th>Item Name</th>
<th>HSN</th>
<th>Unit</th>
<th>Quantity</th>
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
<input type="text" name="totalqty" class="form-control" id="totalqty" value="{{ $purchasefetch->total_qty }}" required>
</div>
</div>
</div>

     </br>  
<button type="submit" class="btn btn-success w-md">Save</button>
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


var index = 1;
function insertRow(){
    
$("#item_codes").select2("destroy");
$("#unit_ids").select2("destroy");

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
 

var cell3 = row.insertCell(2);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
//t3.className="QTY";
t3.id = "hsn_code"+index;
t3.name="hsn_code[]";
t3.value="0";
cell3.appendChild(t3);


var cell2 = row.insertCell(3);
var t2=document.createElement("select");
var x = $("#unit_ids"),
y = x.clone();
y.attr("id","unit_ids");
y.attr("name","unit_ids[]");
y.width(100);
y.height(30);
y.appendTo(cell2);


var cell3 = row.insertCell(4);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
//t3.className="QTY";
t3.id = "item_qtys"+index;
t3.name="item_qtys[]";
t3.value="0";
cell3.appendChild(t3);

 
var cell15=row.insertCell(5);
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

 selselect();

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


sum1 = 0.0;
var amounts = document.getElementsByClassName('FREIGHT');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("totFreightAmt").value = sum1.toFixed(0);


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
                row.find('input[name^="pur_igsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                 row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
               
            }
            else if(tax_type_ids==2)
            {
                row.find('input[name^="pur_igsts[]"]').val(data[0]['igst_per']);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
               row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
            }
            else if(tax_type_ids==3)
            {
                row.find('input[name^="pur_igsts[]"]').val(0);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
                row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
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
}


  $("table.footable_2").on("keyup", 'input[name^="item_qtys[]"],input[name^="item_rates[]"],input[name^="disc_pers[]"],input[name^="disc_amounts[]"],input[name^="pur_cgsts[]"],input[name^="camts[]"],input[name^="pur_sgsts[]"],input[name^="pur_igsts[]"],input[name^="iamts[]"],input[name^="amounts[]"],input[name^="freight_amt[]"],input[name^="total_amounts[]"]', function (event) {
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
              
             if(pur_igsts!=0)
             {
                  Iamt=(Amount*(pur_igsts/100));
                  row.find('input[name^="iamts[]"]').val((Iamt).toFixed(2));
                  TAmount=Amount+Iamt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
             }
             else
             {
                  Camt=(Amount*(pur_cgsts/100));
                  row.find('input[name^="camts[]"]').val((Camt).toFixed(2));
                  
                  Samt=(Amount*(pur_sgsts/100));
                  row.find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                  
                  TAmount=Amount+Camt+Samt+freight_amt;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                  
             }
             
        }
             
                  mycalc();
}



function getBomDetail(type){
    

var bom_code=document.getElementById("bom_code").value;
var tax_type_id=document.getElementById("tax_type_id").value;



$.ajax({
type:"GET",
url:"{{ route('getBoMDetail') }}",
//dataType:"json",
data:{type:type,bom_code:bom_code,tax_type_id:tax_type_id},
success:function(response){
console.log(response);  
    $("#bomdis").append(response.html);
 mycalc();
}
});
}



function getvendorMasterList(vw_code){

  //alert(vw_code);

$.ajax({
type:"GET",
url:"{{ route('getVendorMasterDetail') }}",
dataType:"json",
data:{vw_code:vw_code},
success:function(response){
console.log(response);  

$("#mainstyle_id").val(response.mainstyle_id);
$("#substyle_id").val(response.substyle_id);
$("#fg_id").val(response.fg_id);
$("#style_no").val(response.style_no);
$("#style_description").val(response.style_description);


document.getElementById('mainstyle_id').disabled=true;
document.getElementById('substyle_id').disabled=true;
document.getElementById('fg_id').disabled=true;
 document.getElementById('vpo_code').disabled=true;

}
});

}



function getvendordata(vw_code){

  //alert(pur_code);

$.ajax({
type:"GET",
url:"{{ route('getvendortablenew') }}",
//dataType:"json",
data:{vw_code:vw_code},
success:function(response){
console.log(response);  
    $("#footable_2").html(response.html);

}
});

}

function EnableFields()
{         $("select").prop('disabled', false); }

function getVendorProcessDetails(vpo_code){

  alert(vpo_code);

$.ajax({
type:"GET",
url:"{{ route('VendorProcessOrderDetails') }}",
dataType:"json",
data:{vpo_code:vpo_code},
success:function(response){
console.log(response);  

$("#mainstyle_id").val(response[0]['mainstyle_id']);
$("#substyle_id").val(response[0]['substyle_id']);
$("#fg_id").val(response[0]['fg_id']);
$("#style_no").val(response[0]['style_no']);
$("#style_description").val(response[0]['style_description']);

document.getElementById('mainstyle_id').disabled=true;
document.getElementById('substyle_id').disabled=true;
document.getElementById('fg_id').disabled=true;
 document.getElementById('vw_code').disabled=true;

}
});

}



function getProcessTrimData(vpo_code){

  //alert(pur_code);

$.ajax({
type:"GET",
url:"{{ route('getProcessTrimData') }}",
//dataType:"json",
data:{vpo_code:vpo_code},
success:function(response){
console.log(response);  
    $("#footable_2").html(response.html);

}
});

}



</script>
@endsection