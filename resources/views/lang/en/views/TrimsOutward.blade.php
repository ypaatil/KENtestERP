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
<h4 class="card-title mb-4">Trims Outward</h4>
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

<form action="{{route('TrimsOutward.store')}}" method="POST">
<input type="hidden" name="type" id="type" class="form-control" value="<?php echo  'PURCHASE' ?>" /> 
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Trim Date</label>
<input type="date" name="trimDate" class="form-control" id="formrow-email-input" value="{{date('Y-m-d')}}" required>
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>


 <div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label"> Order Type</label>
<select name="trim_type" class="form-control" id="trim_type" required   >
<option value="">--Trims Type--</option>
<option value="1">Sewing Trims</option>
<option value="2">Packing Trims</option>
 
</select>
</div>
</div>

 <div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control select2" id="vendorId" required  onchange="getProcessWorkDataList(this.value);">
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
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
            
</select>
</div>
</div> 
 
 <div class="col-md-2">
<div class="mb-3">
<label for="vpo_code" class="form-label">Process Order</label>
<select name="vpo_code" class="form-select select2" id="vpo_code"     onchange="getVendorProcessDetails(this.value);getProcessTrimData(this.value);">
<option value="">--Process Order--</option>
            
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
    <option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>
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
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
   >{{ $row->substyle_name }}</option>
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
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="" required readOnly>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="" required readOnly>

</div>
</div> 
</div>


<div class="table-wrap" id="trimInward">
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
<tbody>
<tr>
<td><input type="text" name="id" value="1" id="id"  style="width:50px;"/></td>
<td> <select name="item_codes[]" class="select2" id="item_codes" style="width:200px;height:30px;" >
<option value="">--- Select Item ---</option>
@foreach($itemlist as  $rowitem)
<option value="{{ $rowitem->item_code}}">{{ $rowitem->item_name }} </option>
@endforeach
</select></td>
<td>
   
  <input type="text"   name="hsn_code[]"   value="0" id="hsn_code" style="width:80px;height:30px;" required/></td>

<td> <select name="unit_ids[]" class="select2" id="unit_ids" style="width:100px;height:30px;">
<option value="">--- Select Unit ---</option>
@foreach($unitlist as  $rowunit)
{
<option value="{{ $rowunit->unit_id  }}">{{ $rowunit->unit_name }}</option>

}
@endforeach
</select></td>

<td><input type="text" class="QTY"   name="item_qtys[]"   value="0" id="item_qty" style="width:80px;height:30px;" required/></td>

<td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>


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
<input type="number" value="1" name="cnt" id="cnt" readonly="" hidden="true"  />
</table>
</div>
</div>

<br/>

<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Total Quantity</label>
<input type="text" name="totalqty" class="form-control" id="totalqty" required>
</div>
</div>
</div>

<div>
<button type="submit" class="btn btn-success w-md" onclick="EnableFields();">Save</button>
<a href="{{ Route('TrimsInward.index') }}" class="btn btn-warning w-md">Cancel</a>
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



















function selselect()
 {
     setTimeout(
  function() 
  {

  $("#footable_2 tr td  select[name='item_codes[]']").each(function() {
 
     $(this).closest("tr").find('select[name="item_codes[]"]').select2();
  $(this).closest("tr").find('select[name="unit_ids[]"]').select2();

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
                 row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
               
            }
            else if(tax_type_ids==2)
            {
                row.find('input[name^="pur_igsts[]"]').val(data[0]['igst_per']);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']);
               row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
            }
            else if(tax_type_ids==3)
            {
                row.find('input[name^="pur_igsts[]"]').val(0);
                row.find('input[name^="pur_cgsts[]"]').val(0);
                row.find('input[name^="pur_sgsts[]"]').val(0);
                row.find('input[name^="hsn_code[]"]').val(data[0]['hsn_code']);
                row.find('select[name^="unit_id[]"]').val(data[0]['unit_id']); 
                row.find('img[name^="item_image[]"]').attr('src', 'https://kenerp.org/thumbnail/'+data[0]['item_image_path']);
                
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







setInterval(function() {mycalc()}, 1000);

//  setInterval(fun, 3000);  




function getProcessWorkDataList(vendorId)
{

   var trims_type= $("#trim_type").val();
if(trims_type==1)
{
        $.ajax({
        type:"GET",
        url:"{{ route('getVendorCode') }}",
        //dataType:"json",
        data:{vendorId:vendorId},
        success:function(response){
        console.log(response);  
        
        //alert(response);
        
            $("#vw_code").html(response.html);
        
        }
        });
}
else
{
    $.ajax({
        type:"GET",
        url:"{{ route('getVendorProcessOrder') }}",
        //dataType:"json",
        data:{vendorId:vendorId},
        success:function(response){
        console.log(response);  
        
        //alert(response);
        
            $("#vpo_code").html(response.html);
        
        }
        });
}



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

function EnableFields()
{         $("select").prop('disabled', false); }
         

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