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
<h4 class="card-title mb-4">Sales (Invoice)</h4>
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

<form action="{{route('SaleTransaction.store')}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'SaleTransaction' ?>" /> 
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Firm</label>
<select name="firm_id" class="form-select" id="firm_id" required>

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
<label for="sale_date" class="form-label">Sale Date</label>
<input type="date" name="sale_date" class="form-control" id="sale_date" value="{{date('Y-m-d')}}" required>
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer</label>
<select name="Ac_code" class="form-select select2" id="Ac_code" onchange="getPartyDetails();" required>
<option value="">--- Select Buyer ---</option>
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
<label for="formrow-inputState" class="form-label">GST Type</label>
<select name="tax_type_id" class="form-select " id="tax_type_id" onChange="getPartyDetails();" required>
<option value="">--GST Type--</option>
@foreach($gstlist as  $rowgst)
{
<option value="{{ $rowgst->tax_type_id  }}">{{ $rowgst->tax_type_name }}</option>

}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sales Order No</label>
<select name="sales_order_nos[]" class="form-select select2" id="sales_order_nos" multiple onchange="getSalesOrder();">
<option value="">Sales Order No</option>
 
</select>

<input type="hidden" name="gstNo" class="form-control" id="gstNo" value="" required>
</div>
</div>
  
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
  

 </tbody>
 
<input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
</table>
</div>
</div>

<br/>

<div class="row">
    
 <div class="col-md-2">  
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Qty</label>
<input type="hidden" value="0" name="address" class="form-control" id="address">
<input type="text" required readOnly value="0" name="total_qty" class="form-control" id="total_qty">
</div>
</div> 

    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Gross Amount</label>
<input type="text" name="Gross_amount" class="form-control" id="Gross_amount" onChange="mycalc();" required readOnly>
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">GST Amount</label>
<input type="text" name="Gst_amount" class="form-control" id="Gst_amount" required readOnly>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Net Amount</label>
<input type="text" name="Net_amount" class="form-control" id="Net_amount" required readOnly>
</div>
</div>

 
<div class="col-md-4">  
<div class="mb-3">
<label for="narration" class="form-label">Narration / Remark</label>
<input type="text" name="narration" class="form-control" id="narration"  >
</div>
</div>  
</div>


<div class="row">
<div class="col-md-12">
<div class="mb-3">
<label for="term_and_conditions" class="form-label">Terms and Conditions</label>
<textarea name="terms_and_conditions" class="form-control" id="editor1"   required>
    
    

<p>1. We have right to reject any goods which is rejected by our QC and vendor will be sole responsible for rejection.<br />
2 .We reserves the right to reject the goods if we find them defective even at the later stage and to recover the cost of material and losses if any from the<br />
sellers.<br />
3. Payment shall be made for the actual quantity received by us and our records shall be final and conclusive on this point.<br />
4. We will be entitled to deduct Discount as mentioned in the order.<br />
5. Any dispute arise with respect to this PO shall be subjected to &quot;Ichalkaranji Jurisdiction&quot;.<br />
6. You will allow our customers &amp; quality person to do visit to your factory to verify the quality of material supplied by you so also to see the system of quality<br />
control followed by you.<br />
7. Excess of PO qty is +/-2 % acceptable, Payment will be released only as per physical received qty. (PO qty whichever is lower).<br />
8. Delivery Address: - as above.<br />
9. Goods will be inspected at your factory as per our quality requirements Packing list, Invoice &amp; L.R. copy required on the mail after dispatch.<br />
&nbsp;</p>
 
</textarea>
</div>
</div>
</div>
 
<div>
<button type="submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
<a href="{{ Route('SaleTransaction.index') }}" class="btn btn-warning w-md">Cancel</a>
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

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script> 
// Replace the <textarea id="editor1"> with a CKEditor 
// instance, using default configuration. 
CKEDITOR.replace('editor1'); 
 
</script> 
            
            
<script>
 
 
 function EnableFields()
{
               
                $("select").prop('disabled', false);
}
 
 
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



sum1 = 0.0;
var amounts = document.getElementsByClassName('ITEMQTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1.toFixed(2);;

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

  $("#sale_cgst").val(response[0].cgst_per); 
  $("#sale_sgst").val(response[0].sgst_per); 
  $("#sale_igst").val(response[0].igst_per);
} else{

$("#sale_igst").val(response[0].igst_per);

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
                row.find('input[name^="sale_cgsts[]"]').val(data[0]['cgst_per']);
                row.find('input[name^="sale_sgsts[]"]').val(data[0]['sgst_per']);
                row.find('input[name^="sale_igsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
                 row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
               
            }
            else if(tax_type_ids==2)
            {
                row.find('input[name^="sale_igsts[]"]').val(data[0]['igst_per']);
                row.find('input[name^="sale_cgsts[]"]').val(0);
                row.find('input[name^="sale_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
               row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
            }
            else if(tax_type_ids==3)
            {
                row.find('input[name^="sale_igsts[]"]').val(0);
                row.find('input[name^="sale_cgsts[]"]').val(0);
                row.find('input[name^="sale_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
                row.find('img[name^="item_image[]"]').attr('src', 'https://ken.korbofx.org/thumbnail/'+data[0]['item_image_path']);
                
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

$("#sale_code").val(response["code"]+'-'+response["tr_no"]);
$("#c_code").val(response["c_code"]);

}
});
}



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
                  TAmount=Amount+Iamt ;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
             }
             else
             {
                  Camt=(Amount*(sale_cgsts/100));
                  row.find('input[name^="camts[]"]').val((Camt).toFixed(2));
                  
                  Samt=(Amount*(sale_sgsts/100));
                  row.find('input[name^="samts[]"]').val((Samt).toFixed(2));
                                  
                  TAmount=Amount+Camt+Samt ;
                  row.find('input[name^="total_amounts[]"]').val((TAmount).toFixed(2));
                  
             }
             
        }
             
                  mycalc();
}
 

function getSalesOrder(){
    
    
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