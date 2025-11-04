@extends('layouts.master') 

@section('content')
 
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Fabric Summary GRN</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
<li class="breadcrumb-item active">Fabric Summary GRN</li>
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
<h4 class="card-title mb-4">Fabric Summary GRN</h4>
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

<form action="{{route('FabricSummaryGRN.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
@csrf 
<div class="row">
<div class="col-md-2">
    <div class="mb-3">
        <label for="in_date" class="form-label">In Date</label>
        <input type="date" name="fsg_date" class="form-control" id="fsg_date" value="{{date('Y-m-d')}}" required>
        @foreach($counter_number as  $row)
      <input type="hidden" name="fsg_code" class="form-control" id="fsg_code" value="{{ 'FSG'.''.$row->tr_no }}">
      <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
 
@endforeach
 
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="chk_code" class="form-label">CHK Code</label>   
      <input type="hidden" name="is_opening" class="form-control" id="is_opening" value="0">
<select name="chk_code" class="form-select select2" id="chk_code" onchange="GetPOCodes(this.value);"   >
<option value="">--Select--</option>
@foreach($chkList as  $chks) 
    <option value="{{ $chks->chk_code  }}" >{{ $chks->chk_code }}</option> 
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="po_code" class="form-label">PO Code</label>   
<select name="po_code" class="form-select select2" id="po_code" onchange="getPoForFabricTable(this.value);" disabled >
<option value="">PO code</option>
@foreach($POList as  $rowpol)
{
    <option value="{{ $rowpol->pur_code  }}"
      {{ $rowpol->pur_code == request()->po_code ? 'selected="selected"' : '' }} 
    
    >{{ $rowpol->pur_code }}</option>
}
@endforeach
</select>
</div>
</div>

<input type="hidden" name="challan_no" id="challan_no" class="form-control" id="challan_no">
<input type="hidden" name="challan_date" id="challan_date" class="form-control" id="challan_date" value="{{date('Y-m-d')}}">
<input type="hidden" name="invoice_no" id="invoice_no" class="form-control" id="invoice_no" >
<input type="hidden" name="invoice_date" id="invoice_date" class="form-control" id="invoice_date" value="{{date('Y-m-d')}}">
 
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Supplier</label>
<select name="supplier_id" class="form-select" id="supplier_id" disabled>
<option value="">--Select Supplier--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}">{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
  
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">PO Type</label>
<select name="po_type_id" class="form-select" id="po_type_id" disabled>
<option value="">Type</option>
@foreach($POTypeList as  $rowpo)
{
    <option value="{{ $rowpo->po_type_id  }}">{{ $rowpo->po_type_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 

</div>


<div class="table-wrap" id="fabricInward">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
    <th>SrNo</th>
 <th>Item Name</th>

<th>Meter</th>
<th>Rate Per Meter</th>
<th>Add/Remove</th>
</tr>
</thead>
<tbody>
<tr>
<td><input type="text" name="id[]" value="1" id="id" style="width:50px;"/></td>
  
<td> <select name="item_code[]"   id="item_code" class="select2" style="width:200px;height:30px;" required  onchange="getRateFromPO(this);">
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td> 
  


<td><input type="number" step="0.01" min="0" max="0" class="METER" name="item_qty[]" onkeyup="mycalc();" value="0" id="meter1" style="width:80px;height:30px;" required/></td>
 <td><input type="number" step="any"    name="item_rates[]"   value="0" id="item_rates" style="width:80px;height:30px;" @php $user_type=Session::get('user_type'); if($user_type!=1){ echo 'readOnly'; }@endphp required/>
  
<td>
    <input type="button"  style="width:40px;" onclick="insertcone(); " name="print" value="+" class="btn btn-warning pull-left AButton"> 
    <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" >
    <button type="button" name="allocate[]" onclick="stockAllocateForFabric(this);" item_code="" isclick="0" qty="" bom_code="" cat_id="1" class_id="" class="btn btn-success pull-left">Allocate</button>
</td>
</tr>

 </tbody>
<tfoot>
<tr>
    <th>SrNo</th>
 <th>Item Name</th>

<th>Meter</th>
<th>Rate Per Meter</th>
<th>Add/Remove</th>
</tr>
</tfoot>
<input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
</table>
</div>
</div>
 
<br/>
<div class="table-wrap">
  <div class="table-responsive">
     <table id="footable_2" class="table  table-bordered table-striped m-b-0 footable_2">
        <thead>
           <tr>
              <th>BOM Code</th>
              <th>Sales Order No</th>
              <th>Item Code</th>
              <th>Item Name</th>
              <th>Allocated Stock</th>
           </tr>
        </thead>
        <tbody id="stock_allocate"></tbody>
     </table>
  </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="mb-3">
            <label for="total_meter" class="form-label">Total Qty</label>
            <input type="number" readOnly step="0.01"  name="total_qty" class="form-control" id="total_qty" value="0" readOnly>
        </div>
    </div>
    <div class="col-md-2">
         <div class="mb-3">
             <label for="total_allocate_qty" class="form-label">Total Allocated Stock Qty</label>
             <input type="number" readOnly  class="form-control" id="total_allocate_qty" value="" >
         </div>
    </div>
   
<input type="hidden" name="transport_id" class="form-control" id="transport_id" value="0" > 
<input type="hidden" name="freight_paid" class="form-control" id="freight_paid" value="0" > 
 
</div>

<div class="row">
    
<div class="col-sm-6">
    <div class="mb-3">
        <label for="formrow-inputState" class="form-label">Narration</label>
        <input type="text" name="in_narration" class="form-control" id="in_narration"  value=""  />
    </div>
</div>


<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
<a href="{{ Route('FabricSummaryGRN.index') }}" class="btn btn-warning w-md">Cancel</a>
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

 <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
 

 
        
<!-- end row -->
<script>
    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
        calculateAllocatedQty();
    });
    function calculateAllocatedQty()
    {
        var total_allocate_qty = 0;
        $(".allocate_qty").each(function()
        {
            total_allocate_qty += parseFloat($(this).val());
        });
         $("#total_allocate_qty").val(total_allocate_qty);
    }  
    function GetPOCodes(row)
    {
        $.ajax({
              type: "GET",
              dataType:"json",
              url: "{{ route('GetPoCodeFromChk') }}",
              data:{'chk_code':row},
              success: function(data)
              { 
                  $("#po_code").html(data).change(); 
              }
        });
    }
    
    function SetQtyToBtn(obj)
    { 
        var qty = $(obj).val();
        $(obj).parent().parent('tr').find('td button[name="allocate[]"]').attr('qty', qty);
    }
    
    function stockAllocateForFabric(obj)
    {
      var row1 = $(obj).attr('item_code');
      var row2 = $(obj).attr('qty');
      var row3 = $(obj).attr('bom_code');
      var row4 = $(obj).attr('cat_id');
      var row5 = $(obj).attr('class_id');
      var is_opening = $(obj).attr('is_opening');
      var isClick = $(obj).attr('isClick');
      var po_type_id = $("#po_type_id").val();
      if(isClick == 0)
      {
          $.ajax({
                  type: "GET",
                  dataType:"json",
                  url: "{{ route('stockAllocateForFabric') }}",
                  data:{'bom_code':row3,'item_code' : row1, 'item_qty': row2, 'cat_id':row4, 'class_id':row5,'po_type_id':po_type_id,'is_opening':is_opening},
                  success: function(data)
                  {
                        $("#stock_allocate").append(data.html);
                        $(obj).attr('isClick', '1');
                        calculateAllocatedQty();
                  }
            });
      }
      else
      {
          alert('Already stock allocated..!');
      }
       calculateAllocatedQty();
    }
   
 function enable(opening)
{  
 @php $user_type=Session::get('user_type'); if($user_type!=1){  @endphp
    if(opening.checked==true)
    {  
      $("#footable_2 tr td  select[name='item_code[]']").each(function() {
          $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', false);
       });
      
    }
    else
    {
        $("#footable_2 tr td  select[name='item_code[]']").each(function() {
          $(this).closest("tr").find('input[name="item_rates[]"]').prop('readOnly', true);
        });
    }

@php } @endphp

}
 
 
 
 function getRateFromPO(row)
 {
     var po_code=$('#po_code').val();
      var item_code = $(row).val();
    var row = $(row).closest('tr'); 
          
         $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('ItemRateFromPO') }}",
            data:{'po_code':po_code,item_code:item_code},
            success: function(data){
                 +row.find('input[name^="item_rates[]"]').val(data[0]['item_rate']);
               
                 
        }
        });
         
 }
 
  function getMinMaxPO(row)
 {
    var po_code=$('#po_code').val();
    var color_id = $(row).val();
    var row = $(row).closest('tr');
    
    var item_code= +row.find('select[name^="item_code[]"]').val();
         $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('ItemMinMaxFromPO') }}",
            data:{'po_code':btoa(po_code),item_code:item_code,color_id:color_id},
            success: function(data){
              var max= data[0]['item_qty'].toFixed(2);
            row.find('input[name^="item_qty[]"]').attr({"max" :max,"min" : 0});
        }
        });
         
 }
 
function EnableFields()
{
     $("select").prop('disabled', false);
}


// function serBarocode()
// {
//             if($("#cp_id").val()==1)
//             {
                     
//                     ++PBarcode;
//                     $("#track_code").val('P'.concat(PBarcode.toString()));
//                   //alert($("#track_code").val());
//             }
//             else if($("#cp_id").val()==2)
//             {       var CBar='';
//                     CBar='I' + parseInt(++CBarcode);
//                     $("#track_code").val(CBar);
//             }
// }


 
//  $(document).ready(function()
//  {
     
//       serBarocode();
//  });
 
function getPoForFabricTable(po_code)
{
    var chk_code = $('#chk_code').val();
  
    $.ajax({
        type:"GET",
        url:"{{ route('getPoForFabric') }}",
        //dataType:"json",
        data:{po_code:po_code,chk_code:chk_code},
        success:function(response)
        {
            $("#fabricInward").html(response.html);
            
            var inputQtyData = $('input[name="item_qtys[]"]');
            var totalQty = 0;
            
            $.each(inputQtyData, function() {
               totalQty += parseFloat($(this).val());
            });
            $("#total_qty").val(totalQty);
        
        
         
        
        }
    });
    getPODetails();
 
}


 function getPODetails()
{
   
    var po_code=btoa($("#po_code").val());
    
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('PODetail') }}",
            data:{'po_code':po_code},
            success: function(data)
            { 
                $("#po_type_id").val(data[0]['po_type_id']); 
                $("#supplier_id").val(data[0]['Ac_code']);
                $("#is_opening").val(data[0]['is_opening']); 
            }
    });
}
 
// $(document).on("keyup", 'input[name^="gram_per_meter[]"],input[name^="meter[]"]', function (event) {
//         CalculateRow($(this).closest("tr"));
        
//     });
	 	
// 	function CalculateRow(row)
// 	{ 
// 		var gram_per_meter=+row.find('input[name^="gram_per_meter[]"]').val();
//         var meter=+row.find('input[name^="meter[]"]').val();
// 	 	var kg=parseFloat(parseFloat(meter).toFixed(2) * parseFloat(gram_per_meter).toFixed(2)).toFixed(2);
	 	
//         row.find('input[name^="kg[]"]').val(kg.toFixed(2));
// 		mycalc();
// }


// $(document).on("click", 'input[name^="print[]"]', function (event) 
// {
//     CalculateRowPrint($(this).closest("tr"));
        
// });
	 	
// 	function CalculateRowPrint(btn)
// 	{ 
// 	    var row = $(btn).closest("tr");
//       	var width=+row.find('input[name^="width[]"]').val();
//         var meter=+row.find('input[name^="meter[]"]').val();
//          var kg=+row.find('input[name^="kg[]"]').val();
//         var color_id=+row.find('select[name^="color_id[]"]').val();
//         var part_id=+row.find('select[name^="part_id[]"]').val();
//         var quality_code=+row.find('select[name^="quality_code[]"]').val();
//         var track_code=row.find('input[name^="track_code[]"]').val();
//         var style_no=$("#style_no").val();
//         var job_code=$("#job_code").val();
        
//         //alert(track_code);
//         $.ajax({
//             type: "GET",
//             dataType:"json",
//             url: "{{ route('PrintBarcode') }}",
//             data:{'width':width,'meter':meter,'color_id':color_id,'quality_code':quality_code,'kg':kg,  'part_id':part_id,'track_code':track_code,'style_no':style_no,'job_code':job_code},
//             success: function(data){
                 
//              if((data['result'])=='success')
//             {
//               alert('Print Barcode For Roll: '+track_code);
//             }
//             else
//             {
//                 $alert('Data Can Not Be Printed');
//             }
            
//         }
//         });
        
// }
 



var indexcone = 2;
 
function insertcone(){

$("#item_code").select2("destroy");
$("#color_id").select2("destroy");
var table=document.getElementById("footable_2").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
  

var cell5 = row.insertCell(1);
var t5=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
y.attr("id","item_code");
y.attr("name","item_code[]");
y.val('');
y.width(200);
y.height(30);
y.appendTo(cell5);
 
 
 

 
var cell7 = row.insertCell(2);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;height:30px;";
t8.type="text";
t8.step="any";
t8.className="METER";
t8.id = "item_qty"+indexcone;
t8.name="item_qty[]";
t8.min="0";
t8.max="0";
t8.setAttribute("onChange", "mycalc();");
t8.onkeyup=mycalc();
cell7.appendChild(t8);

  
var cell3 = row.insertCell(3);
var t3=document.createElement("input");
t3.style="display: table-cell; width:80px;height:30px;";
t3.type="number";
t3.step="any";
t3.required="true";
t3.id = "item_rates"+indexcone;
t3.name="item_rates[]";
t3.value="0";
if($('#is_opening').prop('checked')) 
{t3.readOnly=false;}else{t3.readOnly=true;}
cell3.appendChild(t3);
 
 
var cell8=row.insertCell(4);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.name = "print";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone();CalculateRowPrint(this);");
cell8.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell8.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
mycalc();
recalcIdcone();

 selselect();
    
}
 
 
 
 function selselect()
 {
     setTimeout(
  function() 
  {

  $("#footable_2 tr td  select[name='item_code[]']").each(function() {
 
     $(this).closest("tr").find('select[name="item_code[]"]').select2();
   $(this).closest("tr").find('select[name="color_id[]"]').select2();

    });
 }, 2000);
 }
 
 
//   $(document).on("keyup", 'input[name^="item_qty[]"],input[name^="item_rates[]"]', function (event) {
//         CalculateRow($(this).closest("tr"));
//       });
//     function CalculateRow(row)
//     {
//         var item_qtys=+row.find('input[name^="item_qty[]"]').val();
//         var item_rates=+row.find('input[name^="item_rates[]"]').val();
//         var amount=(parseFloat(item_qtys)*parseFloat(item_rates)).toFixed();
//         row.find('input[name^="amounts[]"]').val(amount);
//         mycalc();
//     }
 

function mycalc()
{  
 

sum1 = 0.0;
var amounts = document.getElementsByClassName('METER');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1.toFixed(2);


 
 
 
}

 


function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;
mycalc();
recalcIdcone();

if($("#cntrr").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}



function recalcIdcone(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
 
 
 
$(document).ready(function(){


 var po_code=document.getElementById('po_code').value;  
 
 
 if(po_code !="" && po_code!=0)
 {
 
getDetails(po_code);

}

}); 

function gettable(po_code){

  //alert(pur_code);

$.ajax({
type:"GET",
url:"{{ route('getPo') }}",
//dataType:"json",
data:{po_code:po_code},
success:function(response){ 
    $("#item_code").html(response.html);

}
});
}
 
 
function getDetails(po_code){

$.ajax({
type:"GET",
url:"{{ route('getPoMasterDetail') }}",
//dataType:"json",
data:{po_code:po_code},
success:function(response){ 

$("#supplier_id").val(response[0].Ac_code);
$("#invoice_no").val(response[0].supplierRef);
$("#invoice_date").val(response[0].pur_date);
$("#po_type_id").val(response[0].po_type_id);
$("#in_narration").val(response[0].narration);

 gettable(po_code);


document.getElementById('supplier_id').disabled =true;
document.getElementById('po_type_id').disabled=true;



 $.ajax({
        dataType: "json",
    url: "{{ route('GetPOItemList') }}",
    data:{'po_code':btoa(po_code)},
    success: function(data){
      $("#item_code").html(data.html);
     
   }
    });



$.ajax({
        dataType: "json",
    url: "{{ route('GetPOColorList') }}",
    data:{'po_code':btoa(po_code)},
    success: function(data){
      $("#color_id").html(data.html);
     
   }
    });





}
});
} 


    
    
    function openmodal(po_code,item_code)
 {
     
     getFabInDetails(po_code,item_code);
        $('#modalFormSize').modal('show');
 }
 
  function closemodal()
 {
       $('#modalFormSize').modal('hide');
    //    $('#product-options').modal('hide');
 }
 
 
 
 function getFabInDetails(po_code,item_code)
{
     
    $.ajax({
    type: "GET",
    url: "{{ route('GetCompareFabricPOInwardData') }}",
    data: { sr_no: po_code, item_code: item_code },
    success: function(data){
    $("#InwardData").html(data.html);
    }
    });
}
    









</script>



<div class="modal fade" id="modalFormSize" role="dialog">
<div class="modal-dialog" style="margin: 1.75rem 19rem;">
<div class="modal-content" style="width: 900px;">
<!-- Modal Body -->
<div class="modal-body">
<p class="statusMsg"></p>
 
<div class="seprator-block"></div>
<h6 class="txt-dark capitalize-font"><i class="zmdi zmdi-calendar-note mr-10"></i>Fabric PO Vs GRN</h6>
<hr class="light-grey-hr"/>

<div class="row">


<div id="InwardData"></div>
 
</div>

 


<!-- Modal Footer -->
<div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closemodal();">Close</button>
 
</div>
</div>
</div>
</div>



<!-- end row -->
@endsection