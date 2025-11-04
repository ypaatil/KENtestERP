@extends('layouts.master') 

@section('content')
 

<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Bundle Barcode</h4>
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

@if(isset($BundleBarcodeList))
<form action="{{ route('BundleBarcode.update',$BundleBarcodeList) }}" method="POST" enctype="multipart/form-data" id="frmData">
@method('put')

@csrf 
<div class="row">
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="bb_date" class="form-label">In Date</label>
        <input type="date" name="bb_date" class="form-control" id="bb_date" value="{{ $BundleBarcodeList->bb_date }}"  readOnly>
        
        <input type="hidden" name="bb_code" class="form-control" id="bb_code" value="{{ $BundleBarcodeList->bb_code }}">
<input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $BundleBarcodeList->c_code }}">
 
 
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
    </div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="task_id" class="form-label">Task Id</label>
<select name="task_id" class="form-select" id="task_id"  disabled>
<option value="">--Task Id--</option>
@foreach($TaskList as  $row)
{
    <option value="{{ $row->task_id }}"
    {{ $row->task_id == $BundleBarcodeList->task_id ? 'selected="selected"' : '' }} 
    >{{ $row->task_id }}</option>
}
@endforeach
</select>
</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="vpo_code" class="form-label">VPO Code</label>
<input type="text" name="vpo_code" class="form-control" id="vpo_code" value="{{$BundleBarcodeList->vpo_code}}" readOnly>

</div>
</div> 

<div class="col-md-2">
<div class="mb-3">
<label for="sales_order_no" class="form-label">Sales Order No</label>
<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="{{$BundleBarcodeList->sales_order_no}}" readOnly>

</div>
</div> 
 
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-control" id="vendorId" required  onchange="getVendorPO(this.value);" disabled> 
<option value="">--Select Vendor--</option>
@foreach($Ledger as  $rowvendor)
{
    <option value="{{ $rowvendor->ac_code }}"
     {{ $rowvendor->ac_code == $BundleBarcodeList->vendorId ? 'selected="selected"' : '' }} 
    >{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label" >Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required disabled>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
    {{ $row->mainstyle_id == $BundleBarcodeList->mainstyle_id ? 'selected="selected"' : '' }} 
    >{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style Category</label>
<select name="substyle_id" class="form-control" id="substyle_id" onchange="getStyle(this.value)" required disabled> 
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
    {{ $row->substyle_id == $BundleBarcodeList->substyle_id ? 'selected="selected"' : '' }} 
   >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>    
     
    
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Style Name</label>
<select name="fg_id" class="form-control" id="fg_id" required disabled>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    {{ $row->fg_id == $BundleBarcodeList->fg_id ? 'selected="selected"' : '' }} 
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{$BundleBarcodeList->style_no}}" readOnly> 
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{$BundleBarcodeList->style_description}}" readOnly>

</div>
</div>
 
<input type="number" value="{{ count($BundleBarcodeDetail) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>Color</th>
<th>Meter</th>
<th>Bal Meter</th>
<th>Total Pieces</th>
<th>Layers</th>
@php 



$sizes_array=explode(',', $BundleBarcodeList->sizes_array);
$size_serial=explode(',', $BundleBarcodeList->size_serial_array); $no=0; @endphp;

@foreach($sizes_array as $sz) 
@php
$SizeList2 = DB::select("SELECT size_id, size_name from size_detail where size_id =".$sz);
@endphp
<th>{{ $SizeList2[0]->size_name }}
@if($BundleBarcodeList->size_serial_array)
<input id="SizeSerialNo" style="width:70px;" type="text" name="SizeSerialNo[]" value="{{$size_serial[$no++]}}" required readOnly/>
@else
<input id="SizeSerialNo" style="width:70px;" type="text" name="SizeSerialNo[]" value="" required/>
@endif
</th>



@endforeach

<th>Track Code</th>
<th>Action</th>
</tr>
</thead>
<tbody>
  
@if(count($BundleBarcodeDetail)>0)

@php $no=1; @endphp
@foreach($BundleBarcodeDetail as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;" readOnly/></td>
  
<td> <select name="item_code[]"  id="item_code" style="width:100px;" required readOnly>
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}"
    {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}        
    >{{ $row->item_name }}</option>
}
@endforeach
</select></td>
 

<td><input type="text" readOnly  onkeyup="mycalc();" name="meter[]" value="{{ $List->meter }}" id="meter" style="width:80px;" required /></td>
<td><input type="text" readOnly  onkeyup="mycalc();" name="bal_meter[]" value="{{ $List->bal_meter }}" id="meter" style="width:80px;" required /></td>
<td><input type="text" readOnly  onkeyup="mycalc();" name="totalpiece[]" value="{{ $List->total_piece }}" id="meter" style="width:80px;" required /></td>
<td><input type="text" readOnly  onkeyup="mycalc();" name="layers[]" value="{{ $List->layers }}" id="piece" style="width:80px;" required /></td>
@php 
    $BundleList=explode(',', $List->bundle_no)
@endphp
@foreach($BundleList  as $bundle)
<td class="bdl"><input type="text" class="bundlenos"  name="bundleno[]" value="{{ $bundle }}" id="bundleno{{ $bundle }}" style="width:80px;" onchange="RearrangeBarcodes(this);" required /></td>
@endforeach
<td>
<input type="hidden" readOnly name="bundles[]"  value="{{ $List->bundle_no }}" id="bundles" style="width:80px;" /> 
<input type="hidden" readOnly name="sz_codes[]"  value="{{ $List->sizes_array }}" id="sz_codes" style="width:80px;" />
<input type="text" readOnly  onkeyup="mycalc();" name="track_code[]" value="{{ $List->roll_track_code }}" id="track_code" style="width:80px;" required /></td> 
 <td><input type="button" class="btn btn-warning pull-left" name="add[]"   value="+"  ><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>

<!--<input type="button" class="btn btn-warning pull-left" name="add[]"  value="+" >-->

@php $no=$no+1  @endphp
@endforeach
@endif
</tbody>
 
</table>
</div>
</div>
 
</div>

<div class="row">
  <div class="col-md-2">
    <div class="mb-3">
        <label for="total_piece" class="form-label">Total Piece</label>
        <input type="number"  name="total_piece" class="form-control" id="total_piece" value="{{ $BundleBarcodeList->total_piece }}" readOnly>
    </div>
</div>
 
<div class="col-sm-8">
    <div class="mb-3">
        <label for="narration" class="form-label">Narration</label>
        <input type="text" name="narration" class="form-control" id="narration"  value="{{ $BundleBarcodeList->narration }}"    />
    </div>
</div>
</div>

<div class="row">
    <div class="col-sm-6">
    <div class="mb-3">
        <label for="jpart_id" class="form-label">For Which Job Parts Do You want Barcode Print..?</label>
       <select name="jpart_id[]" class="form-select" id="jpart_id"  size="10"  multiple>
        <option value="0">--All Part--</option>
        @foreach($JobPartList as  $row)
        {
            <option value="{{ $row->jpart_id }}">{{ $row->jpart_name }} ({{ $row->jpart_description }})</option>
        }
        @endforeach
        </select>
    </div>
</div>
<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md" onclick="  EnableFields();" id="Submit">Submit</button>
<a href="{{ Route('BundleBarcode.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>


</div>
</form>
 
@endif

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
    });
 $(document).on('keyup','input[name^="piece[]"]', function(event) {  
    
    mycalc();

});




function EnableFields()
{
    document.getElementById('task_id').disabled=false;
    document.getElementById('mainstyle_id').disabled=false;
    document.getElementById('substyle_id').disabled=false;
    document.getElementById('fg_id').disabled=false;
    document.getElementById('style_description').disabled=false;
    document.getElementById('style_no').disabled=false;
    document.getElementById('vendorId').disabled=false;
    document.getElementById('vpo_code').disabled=false;
    document.getElementById('sales_order_no').disabled=false;
}



function getDetails(str)
{
    var job_code=$("#job_code").val();
     
    $.ajax({
        dataType: "json",
    url: "{{ route('BundleList') }}",
    data:{'task_id':str,'job_code':job_code},
    success: function(data){
    $("#footable_2").html(data.html);
    }
    });
}

  function RearrangeBarcodes(row)
  {
        var sizes=document.getElementById('sz_codes').value; 
        setDynmc(sizes,$("#bundleno1").val());
      
  }
 
$(document).on("click", 'input[name^="add[]"]', function (event) 
{
       
    var track_code= $(this).closest("tr").find('input[name="track_code[]"]').val();
    var sizes= $(this).closest("tr").find('input[name="sz_codes[]"]').val();
    var job_code=$("#job_code").val();
    var task_id=$("#task_id").val();
    rownumber=$(this).closest('td').parent()[0].sectionRowIndex;
     var size_array = sizes.split(',');
 //alert(task_id);
    $.ajax({
    dataType: "json",
    url: "{{ route('BundleSplitList') }}",
    data:{'task_id':task_id,'job_code':job_code,'track_code':track_code},
    success: function(data){
 
    $('#footable_2 > tbody > tr').eq(rownumber).after(data.html);

    setDynmc(sizes);
     
     
     var values = [];
      
       $("#footable_2 tr td  input[name='bundleno[]']").each(function() {
       values.push($(this).val());
       if(values.length==size_array.length)
       {
           //alert(values);
        $(this).closest("tr").find('input[name="bundles[]"]').val(values);
        
            values = [];
       }
       
       
         });
         
         
  }
     
     
  
    
    });
    
          recalcIdcone();
    });


  
function setDynmc(sizes,nos=0) 
{

    var table = document.getElementById("footable_2");
    var table_len = (table.rows.length) - 1;
    var tr = table.getElementsByTagName("tr");
    var id = 0;
    //var colctr = $('#tbl_taka').columnCount();
    var colctr = $(document).find('tr')[0].cells.length;
    var size_array = sizes.split(',');
  
  var flag=2; // flag=1 means new entry 2 means  edit
   var job_code=$("#job_code").val();
  $.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('SessionValue') }}",
        data:{'flag':flag, 'job_code':job_code},
        success: function(data){
        
        if(nos == 0)
        {
            var no=data['size_counter']; 
        }
        else
        {
            var no=nos-1; 
        }
         //alert(data['size_counter']);
         
      
             var col_id=7;
             var arraysize='';
             for(let x=1;x<=size_array.length;x++)
             {
               $.each($('#footable_2 tr'),function (i,el){
               $(this).find("td:nth-child("+col_id+") input").val(no); 
               ++no;
               });
               no=no-1;
               col_id=col_id+1;
             }
            
            
            
        }
        });
     recalcIdcone();
}

 

 

 
function getMasterdata(job_code)
{
     
$.ajax({
        type: "GET",
        dataType:"json",
        url: "{{ route('InwardMasterList') }}",
        data:'job_code='+job_code,
        success: function(data){
            
       $("#cp_id").val(data[0]['cp_id']);
       $("#Ac_code").val(data[0]['Ac_code']);
       $("#gp_no").val(data[0]['gp_no']);
       $("#fg_id").val(data[0]['fg_id']);
       $("#style_no").val(data[0]['style_no']);
       $("#total_taga_qty").val(data[0]['total_taga_qty']);
       $("#total_meter").val(data[0]['total_meter']);
      
        }
        });
    }

var indexcone = 2;
function insertcone(){

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
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell5);



var cell3 = row.insertCell(2);
var t3=document.createElement("select");
var x = $("#part_id"),
y = x.clone();
y.attr("id","part_id");
y.attr("name","part_id[]");
y.width(100);
y.appendTo(cell3);
 

var cell3 = row.insertCell(3);
var t3=document.createElement("select");
var x = $("#quality_code"),
y = x.clone();
y.attr("id","quality_code");
y.attr("name","quality_code[]");
y.width(100);
y.appendTo(cell3);

var cell6 = row.insertCell(4);
var t6=document.createElement("input");
t6.style="display: table-cell; width:80px;";
t6.type="text";
t6.required="true";
t6.id = "width"+indexcone;
t6.name="width[]";
t6.onkeyup=mycalc();
t6.value="0";
cell6.appendChild(t6);
 
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="hidden";
t7.className="TAGAQTY";
t7.required="true";
t7.id = "taga_qty"+indexcone;
t7.name="taga_qty[]";
t7.onkeyup=mycalc();
t7.value="1";
cell6.appendChild(t7);


var cell7 = row.insertCell(5);
var t8=document.createElement("input");
t8.style="display: table-cell; width:80px;";
t8.type="text";
t8.id = "oldmeter"+indexcone;
t8.name="oldmeter[]";
t8.onkeyup=mycalc();
cell7.appendChild(t8);

var cell9 = row.insertCell(6);
var t9=document.createElement("input");
t9.style="display: table-cell; width:80px;";
t9.type="text";
t9.className="METER";
t9.id = "meter"+indexcone;
t9.name="meter[]";
t9.onkeyup=mycalc();
cell9.appendChild(t9);

var cell3 = row.insertCell(7);
var t3=document.createElement("select");
var x = $("#shade_id"),
y = x.clone();
y.attr("id","shade_id");
y.attr("name","shade_id[]");
y.width(100);
y.appendTo(cell3);

var cell10 = row.insertCell(8);
var t10=document.createElement("input");
t10.style="display: table-cell; width:80px;";
t10.type="text";
t10.id = "track_code"+indexcone;
t10.name="track_code[]";
cell10.appendChild(t10);
  

var cell11=row.insertCell(9);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell11.appendChild(btnAdd);


var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell11.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
mycalc();
recalcIdcone();
}

function mycalc()
{  
 

sum1 = 0.0;
var amounts = document.getElementsByClassName('PIECE');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_piece").value = sum1.toFixed(2);

 
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
function recalcIdcone2(){
$.each($("#footable_2 tr"),function (i,el){
$(this).find("td:nth-child(6) input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}


 
</script>

<!-- end row -->
@endsection