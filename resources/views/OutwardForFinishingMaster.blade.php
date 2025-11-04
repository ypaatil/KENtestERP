@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Outward For Finishing</h4>
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
 
<form action="{{route('OutwardForFinishing.store')}}" method="POST" enctype="multipart/form-data" id="frmData">
@csrf 
<div class="row">
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="off_date" class="form-label">Entry Date</label>
        <input type="date" name="off_date" class="form-control" id="off_date" value="{{date('Y-m-d')}}" required  >
        @foreach($counter_number as  $row)
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $row->c_code }}">
@endforeach
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>

 <div class="col-md-2">
<div class="mb-3">
<label for="process_id" class="form-label">Process Type</label>
<select name="process_id" class="form-control" id="process_id" required>
<option value="">--Select Process--</option>
@foreach($ProcessList as  $row)
{
    <option value="{{ $row->process_id }}">{{ $row->process_name }}</option>
}
@endforeach
</select>
</div>
</div>


<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Process Order</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="vpo_code" class="form-control select2" id="vpo_code" required  onChange="getVendorProcessOrderDetails(this.value);">
<option value="">--Process Order No--</option>
@foreach($VendorProcessOrderList as  $row)
{
    <option value="{{ $row->vpo_code }}">{{ $row->vpo_code }} ({{ $row->sales_order_no }})</option>
}
@endforeach
</select>
    </div>
</div>
 
 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Sales order No</label>
<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required readOnly>
</div>
</div>

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Vendor Rate</label>
<input type="text" name="vendor_rate" class="form-control" id="vendor_rate" value="" required readOnly>
</div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-control select2" id="Ac_code" required   >
<option value="">--Select Buyer--</option>
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
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required>
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


<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required  >
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}">{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
   
  
</div> 
  
   <div class="panel-group" id="accordion">
   
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">QC Stitching GRN Against Sales Order</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse in" style="width:100%;">
        <div class="panel-body">
            
       <div class="row">
   
        <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
        <div class="table-wrap">
        <div class="table-responsive">
        <table id="footable_1" class="table  table-bordered table-striped m-b-0  footable_1">
         
        </table>
        </div>
        </div>
        </div>
        
        </div>
      
      
      </div>
    </div>
   
   
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Outward For Finishing</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in" style="width:100%;">
        <div class="panel-body">
            
       <div class="row">
   
        <input type="number" value="1" name="cntrr1" id="cntrr1" readonly="" hidden="true"/>
        <div class="table-wrap">
        <div class="table-responsive">
        <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
         
        </table>
        </div>
        </div>
        </div>
        
        </div>
      
      
      </div>
    </div>

    
       
    </div>
  </div> 
</div>
</div>
 
 
 
 

 
 </br>
 
 </br>

 
<!-- end row -->
<div class="row">
     
     
<div class="col-md-2">
<div class="mb-3">
<label for="total_qty" class="form-label">Total Qty</label>
<input type="text" name="total_qty" class="form-control" id="total_qty" value="" required readOnly>
</div>
</div>
  
 
<div class="col-md-2">
<div class="mb-3">
<label for="vendor_amount" class="form-label">Total Amount</label>
<input type="text" name="vendor_amount" class="form-control" id="vendor_amount" value="" required readOnly>

</div>
</div>
     
<div class="col-sm-8">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="" />
</div>
</div>
  
</div>

<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="EnableFields();" id="Submit">Submit</button>
<a href="{{ Route('OutwardForFinishing.index') }}" class="btn btn-warning w-md">Cancel</a>
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

           
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<!-- end row -->
<script>

    $(document).ready(function() {
        $('#frmData').submit(function() {
            $('#Submit').prop('disabled', true);
        }); 
    });
// $(document).on("change", 'input[class^="size_id"]', function (event) 
// {
    
//     var value = $(this).val();
    
//              var maxLength = parseInt($(this).attr('max'));
//              var minLength = parseInt($(this).attr('min')); 
//     if(value>maxLength){alert('Value can not be greater than '+maxLength);}
//     if ((value !== '') && (value.indexOf('.') === -1)) {
         
         
         
//         $(this).val(Math.max(Math.min(value, maxLength), minLength));
//     }
    
   
// });




$(document).on("change", 'input[class^="size_id"]', function (event) 
{
   var no=1;
    var sales_order_no = $('#sales_order_no').val();
    var sizes= $(this).closest("tr").find('input[name="size_array[]"]').val();
    var size_array = sizes.split(',');
   
      var values = [];
      $("#footable_2 tr td  input[class='size_id']").each(function() {
      values.push($(this).val());
      if(values.length==size_array.length)
      {
          
        $(this).closest("tr").find('input[name="size_qty_array[]"]').val(values);
        // alert(values);
            var sum = values.reduce(function( a,  b){
                return parseInt(a) + parseInt(b);
            }, 0);
        $(this).closest("tr").find('input[name="size_qty_total[]"]').val(sum);
         
            values = [];
      }
       
    });
    
            mycalc();
   });

  

function getVendorProcessOrderDetails(vpo_code)
{

      $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('VendorProcessOrderDetails') }}",
            data:{'vpo_code':vpo_code},
            success: function(data){
            
           
            $("#Ac_code").val(data[0]['Ac_code']);
            $("#vendorId").val(data[0]['vendorId']);
            $("#sales_order_no").val(data[0]['sales_order_no']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#style_description").val(data[0]['style_description']);
            $("#vendor_rate").val(data[0]['order_rate']);
             document.getElementById('Ac_code').disabled=true;
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('vendorId').disabled=true;
            
        var process_id= $("#process_id").val();
        
          $.ajax({
        dataType: "json",
        url: "{{ route('GetSTITCHINGGRNQty') }}",
        data:{'tr_code':data[0]['sales_order_no'],'vpo_code':vpo_code,'process_id': process_id},
        success: function(data){
        $("#footable_1").html(data.html);
        }
        });
       
        
        
        }
        });
  

        $.ajax({
        dataType: "json",
        url: "{{ route('vpo_GetFinishingPOQty') }}",
        data:{'vpo_code':vpo_code},
        success: function(data){
        $("#footable_2").html(data.html);
        }
        });
 
}

function EnableFields()
{
            $("select").prop('disabled', false);
             
}



 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});

   
 
var indexcone = 2;
function insertcone1(){

var table=document.getElementById("footable_1").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_code"),
y = x.clone();
y.attr("id","item_code");
y.attr("name","item_code[]");
y.width(200);
y.appendTo(cell3);
   
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_id"),
y = x.clone();
y.attr("id","class_id");
y.attr("name","class_id[]");
y.width(200);
y.appendTo(cell3);

var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "description"+indexcone;
t5.name="description[]";
cell5.appendChild(t5); 
 
var cell5 = row.insertCell(4);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumption"+indexcone;
t5.name="consumption[]";
cell5.appendChild(t5);  

var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#unit_id"),
y = x.clone();
y.attr("id","unit_id");
y.attr("name","unit_id[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unit"+indexcone;
t5.name="rate_per_unit[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(7);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastage"+indexcone;
t5.name="wastage[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qty"+indexcone;
t5.name="bom_qty[]";
cell5.appendChild(t5);
 

var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="FABRIC";
t5.readOnly=true;
t5.id = "total_amount"+indexcone;
t5.name="total_amount[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(10);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone1()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone1(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_1').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr1').value = parseInt(document.getElementById('cntrr1').value)+1;

indexcone++;
recalcIdcone1();
}

// Start Sewing Trims----------------------------
var indexcone1 = 2;
function insertcone2(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "ids"+indexcone1;
t1.name= "ids[]";
t1.value=indexcone1;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_codes"),
y = x.clone();
y.attr("id","item_codes");
y.attr("name","item_codes[]");
y.width(200);
y.appendTo(cell3);
  
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_ids"),
y = x.clone();
y.attr("id","class_ids");
y.attr("name","class_ids[]");
y.width(200);
y.appendTo(cell3);


var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptions"+indexcone1;
t5.name="descriptions[]";
cell5.appendChild(t5); 



var cell3 = row.insertCell(4);
var t3=document.createElement("select");
var x = $("#color_ids"),
y = x.clone();
y.attr("id","color_ids");
y.attr("name","color_ids[][]");
y.width(200);
y.appendTo(cell3); 


var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "color_arrays"+indexcone2;
t5.name="color_arrays[]";
cell3.appendChild(t5); 

var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#size_ids"),
y = x.clone();
y.attr("id","size_ids");
y.attr("name","size_ids[][]");
y.width(200);
y.appendTo(cell3); 

var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "size_arrays"+indexcone2;
t5.name="size_arrays[]";
cell3.appendChild(t5); 

  
var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptions"+indexcone1;
t5.name="consumptions[]";
cell5.appendChild(t5);  
 
var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#unit_ids"),
y = x.clone();
y.attr("id","unit_ids");
y.attr("name","unit_ids[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_units"+indexcone1;
t5.name="rate_per_units[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastages"+indexcone1;
t5.name="wastages[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(10);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtys"+indexcone1;
t5.name="bom_qtys[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(11);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="SEWING";
t5.readOnly=true;
t5.id = "total_amounts"+indexcone1;
t5.name="total_amounts[]";
cell5.appendChild(t5); 

 
 
var cell6=row.insertCell(12);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone2()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone2(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone1);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr2').value = parseInt(document.getElementById('cntrr2').value)+1;

indexcone1++;
recalcIdcone2();
}


// Start Packing Trims----------------------------
var indexcone2 = 2;
function insertcone3(){

var table=document.getElementById("footable_4").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
//t1.className="form-control col-sm-1";

t1.id = "idss"+indexcone2;
t1.name= "idss[]";
t1.value=indexcone2;

cell1.appendChild(t1);
  
var cell3 = row.insertCell(1);
var t3=document.createElement("select");
var x = $("#item_codess"),
y = x.clone();
y.attr("id","item_codess");
y.attr("name","item_codess[]");
y.width(200);
y.appendTo(cell3);
  
var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#class_idss"),
y = x.clone();
y.attr("id","class_idss");
y.attr("name","class_idss[]");
y.width(200);
y.appendTo(cell3);


var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:200px; height:30px";
t5.type="text";
t5.id = "descriptionss"+indexcone2;
t5.name="descriptionss[]";
cell5.appendChild(t5); 


var cell3 = row.insertCell(4);
var t3=document.createElement("select");
var x = $("#color_idss"),
y = x.clone();
y.attr("id","color_idss");
y.attr("name","color_idss[][]");
y.width(200);
y.appendTo(cell3);  
 
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "color_arrayss"+indexcone2;
t5.name="color_arrayss[]";
cell3.appendChild(t5); 


var cell3 = row.insertCell(5);
var t3=document.createElement("select");
var x = $("#size_idss"),
y = x.clone();
y.attr("id","size_idss");
y.attr("name","size_idss[][]");
y.width(200);
y.appendTo(cell3); 

var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "size_arrayss"+indexcone2;
t5.name="size_arrayss[]";
cell3.appendChild(t5);
 
  
var cell5 = row.insertCell(6);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "consumptionss"+indexcone2;
t5.name="consumptionss[]";
cell5.appendChild(t5);  
 
var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#unit_idss"),
y = x.clone();
y.attr("id","unit_idss");
y.attr("name","unit_idss[]");
y.width(100);
y.appendTo(cell3);

var cell5 = row.insertCell(8);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "rate_per_unitss"+indexcone2;
t5.name="rate_per_unitss[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(9);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "wastagess"+indexcone2;
t5.name="wastagess[]";
cell5.appendChild(t5);


var cell5 = row.insertCell(10);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.id = "bom_qtyss"+indexcone2;
t5.name="bom_qtyss[]";
cell5.appendChild(t5);
  
var cell5 = row.insertCell(11);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.className="PACKING";
t5.readOnly=true;
t5.id = "total_amountss"+indexcone2;
t5.name="total_amountss[]";
cell5.appendChild(t5); 
 
 
var cell6=row.insertCell(12);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone3()");
cell6.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone3(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_4').find('tr').eq(indexcone2);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr3').value = parseInt(document.getElementById('cntrr3').value)+1;

indexcone2++;
recalcIdcone3();
}







function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('size_qty_total');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_qty").value = sum1;

 
  
 var vendor_rate=$("#vendor_rate").val();
 var vendor_amount=(parseFloat(vendor_rate)*parseInt(sum1)).toFixed(2);
 $("#vendor_amount").val(vendor_amount);
}


function calculateamount()
{
    
    
var prod_qty=document.getElementById('prod_qty').value;
var rate_per_piece=document.getElementById('rate_per_piece').value;


var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
$('#total_amount').val(total_amount.toFixed(2));
}






function deleteRowcone1(btn) {
if(document.getElementById('cntrr1').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr1').value = document.getElementById('cntrr1').value-1;

recalcIdcone1();

if($("#cntrr1").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}


function deleteRowcone2(btn) {
if(document.getElementById('cntrr2').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr2').value = document.getElementById('cntrr2').value-1;

recalcIdcone2();

if($("#cntrr2").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}

function deleteRowcone3(btn) {
if(document.getElementById('cntrr3').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr3').value = document.getElementById('cntrr3').value-1;

recalcIdcone3();

if($("#cntrr3").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}


function recalcIdcone1(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}

function recalcIdcone2(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}

function recalcIdcone3(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}


 function getSubStyle(val) 
{	//alert(val);
    $.ajax({
    type: "GET",
    url: "{{ route('SubStyleList') }}",
    data:'mainstyle_id='+val,
    success: function(data){
    $("#substyle_id").html(data.html);
    }
    });
}   
     
  function getStyle(val) 
{	//alert(val);

   $.ajax({
    type: "GET",
    url: "{{ route('StyleList') }}",
    data:{'substyle_id':val, },
    success: function(data){
    $("#fg_id").html(data.html);
    }
    });
}  


</script>

<!-- end row -->
@endsection