@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Fabric Outward</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
<li class="breadcrumb-item active">Fabric Outward</li>
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
<h4 class="card-title mb-4">Fabric Outward</h4>
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
 
 
@if(isset($FabricOutwardMasterList))
<form action="{{ route('FabricOutward.update',$FabricOutwardMasterList) }}" method="POST" enctype="multipart/form-data">
@method('put')
@csrf 
<div class="row">
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="fout_date" class="form-label">Issue Date</label>
        <input type="date" name="fout_date" class="form-control" id="fout_date" value="{{ $FabricOutwardMasterList->fout_date }}" required>
        
    <input type="hidden" name="fout_code" class="form-control" id="fout_code" value="{{ $FabricOutwardMasterList->fout_code }}">
    <input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $FabricOutwardMasterList->c_code }}">
    <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
        
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
     {{ $rowvendor->ac_code == $FabricOutwardMasterList->vendorId ? 'selected="selected"' : '' }}
    >{{ $rowvendor->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
 
 
<div class="col-md-2">
    <div class="mb-3">
        <label for="job_code" class="form-label">Vendor PO</label>
            <select name="vpo_code" class="form-select select2" id="vpo_code" required  onChange="getBalanceCutingdata(this.value);"   >
            <option value="">--PO No--</option> 
            @foreach($VPOrderList as  $row)
            {
                <option value="{{ $row->vpo_code }}"
                @php if(strcmp($row->vpo_code,$FabricOutwardMasterList->vpo_code)==0){echo 'selected="selected"'; } @endphp> {{$row->vpo_code }}</option>
            }
            @endforeach
            </select>
    </div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style Category</label>
<select name="mainstyle_id" class="form-control" id="mainstyle_id"  onchange="getSubStyle(this.value)" required disabled>
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
      {{ $row->mainstyle_id == $FabricOutwardMasterList->mainstyle_id ? 'selected="selected"' : '' }} 
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
     {{ $row->substyle_id == $FabricOutwardMasterList->substyle_id ? 'selected="selected"' : '' }} 
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
    {{ $row->fg_id == $FabricOutwardMasterList->fg_id ? 'selected="selected"' : '' }}   
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div> 

 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{$FabricOutwardMasterList->style_no}}" required readOnly>
</div>
</div>
  
 
<div class="col-md-4">
<div class="mb-3">
<label for="style_description" class="form-label">Style Description</label>
<input type="text" name="style_description" class="form-control" id="style_description" value="{{$FabricOutwardMasterList->style_description}}" required readOnly>

</div>
</div>

</div>
<div class="row">






 


<div class="col-md-2">
<div class="mb-3">
<label for="track_code" class="form-label">Scan Barcode</label>   
<input type="text" name="track_code" class="form-control" id="track_code" value="" onfocusout="getFabricDetails(this.value);"  >
</div>
</div>

 
                        @if(session()->has('Alert'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('Alert') }}
                            </div>
                        </div>
                        @endif
                        
<input type="number" value="{{ count($FabricOutwardDetails) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>Roll No</th>
<th>TrackCode</th>
<th>Item Name</th>
<th>Color</th>
<th>Quality</th>
<th>Part</th>
<th>Shade</th>
<th>Width</th>
<th>Meter</th>
<th>Remove</th>
</tr>
</thead>
<tbody id="FabricData">
 
@php $no=1; @endphp
@foreach($FabricOutwardDetails as $List) 
 
<tr>
<td><input type="text" name="id[]" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
 
<td><input type="text" name="track_codes[]"  value="{{ $List->track_code }}" id="track_codes" style="width:80px;" readOnly /></td>
<td> <select name="item_code[]"  id="item_code" style="width:100px;" required disabled>
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}"
    {{ $row->item_code == $List->item_code ? 'selected="selected"' : '' }}       
    >{{ $row->item_name }}</option>
}
@endforeach
</select></td> 
  
<td>{{ $List->color_name }}</td>
 <td>{{ $List->item_description }}</td>
<td> <select name="part_ids[]"  id="part_ids" style="width:100px;" required disabled>
<option value="">--Part--</option>
@foreach($PartList as  $row)
{
    <option value="{{ $row->part_id }}"
    {{ $row->part_id == $List->part_id ? 'selected="selected"' : '' }}       
    >{{ $row->part_name }}</option>
}
@endforeach
</select></td>

<td> <select name="shade_ids[]"  id="shade_ids" style="width:100px;" required disabled>
<option value="">--Shade--</option>
@foreach($ShadeList as  $row)
{
    <option value="{{ $row->shade_id }}"
    {{ $row->shade_id == $List->shade_id ? 'selected="selected"' : '' }}       
    >{{ $row->shade_name }}</option>
}
@endforeach
</select></td>
<td>{{ $List->dimension }}</td>
<td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();" value="{{ $List->taga_qty }}" id="taga_qty1" style="width:50px;"  />
<input type="text" class="METER" name="meters[]" onkeyup="mycalc();" value="{{ $List->meter }}" id="meters" style="width:80px;" required  /></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
@php $no=$no+1;  @endphp
 
@endforeach
 </tbody>
<tfoot>
<tr>
<th>Roll No</th>
<th>TrackCode</th>
<th>Item Name</th>
<th>Color</th>
<th>Quality</th>
<th>Part</th>
<th>Shade</th>
<th>Width</th>
<th>Meter</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>
 
</div>

<div class="row">
  <div class="col-md-2">
    <div class="mb-3">
        <label for="total_meter" class="form-label">Total Meter</label>
        <input type="number" step="0.01"  name="total_meter" class="form-control" id="total_meter" value="{{ $FabricOutwardMasterList->total_meter }}" readOnly>
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Total Taga</label>
        <input type="number"   name="total_taga_qty" class="form-control" id="total_taga_qty" value="{{ $FabricOutwardMasterList->total_taga_qty }}" readOnly>
    </div>
</div>
 
 
    <div class="col-sm-8">
        <div class="mb-3">
            <label for="formrow-inputState" class="form-label">Narration</label>
            <input type="text" name="in_narration" class="form-control" id="in_narration"  value="{{ $FabricOutwardMasterList->narration }}"     />
        </div>
    </div>


<div class="col-sm-6">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md"   onclick="EnableFields();">Submit</button>
<a href="{{ Route('FabricOutward.index') }}" class="btn btn-warning w-md">Cancel</a>
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
 
 function getBalanceCutingdata()
{
    
     var vpo_code=$('#vpo_code').val();
  
    $.ajax({
            type: "GET",
            dataType:"json",
            url: "{{ route('VendorPurchaseOrderDetails') }}",
            data:{'vpo_code':vpo_code},
            success: function(data){
           
            $("#vendorId").val(data[0]['vendorId']);
            $("#mainstyle_id").val(data[0]['mainstyle_id']);
            $("#substyle_id").val(data[0]['substyle_id']);
            $("#style_no").val(data[0]['style_no']);
            $("#fg_id").val(data[0]['fg_id']);
            $("#style_description").val(data[0]['style_description']);
             document.getElementById('mainstyle_id').disabled=true;
             document.getElementById('substyle_id').disabled=true;
             document.getElementById('fg_id').disabled=true;
             document.getElementById('style_description').disabled=true;
             document.getElementById('style_no').disabled=true;
             document.getElementById('vendorId').disabled=true;
              
        }
    });
}

function EnableFields()
{
             
     document.getElementById('mainstyle_id').disabled=false;
     document.getElementById('substyle_id').disabled=false;
     document.getElementById('fg_id').disabled=false;
     document.getElementById('style_description').disabled=false;
     document.getElementById('style_no').disabled=false;
     document.getElementById('vendorId').disabled=false;
     $("select").prop('disabled', false);
}




function getFabricDetails(track_code)
{
    if(track_code!='')
    {
                var next=1;
                $("#footable_2 tbody tr").each(function() {
                var thisRow = $(this);
                var match = thisRow.find('input[name^="track_codes[]"]').val();
                // note the `==` operator &
                if(match == track_code ){next=2;}
                
                });

                if(next!=2)
                {
                    $.ajax({
                                    type: "GET",
                                    url: "{{ route('FabricRecord') }}",
                                    data:{'track_code':track_code},
                                    success: function(response)
                                    {
                                         
                                             $("#FabricData").append(response.html);

                                         mycalc();
                                      // 
                                        recalcIdcone();
                                    }
                        });
                        $('#track_code').val('');
                        $('#track_code').focus();
                        document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
                }
                else
                {
                    alert("This Roll Already in the list..!");
                    next=1;
                    $('#track_code').val('');
                    $('#track_code').focus();
                }

    
    }
}





 $(document).on('keyup','input[name^="meter[]"]', function(event) {  
    
    mycalc();

});




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
t8.className="METER";
t8.id = "meter"+indexcone;
t8.name="meter[]";
t8.onkeyup=mycalc();
cell7.appendChild(t8);

var cell7 = row.insertCell(6);
var t7=document.createElement("input");
t7.style="display: table-cell; width:80px;";
t7.type="text";
t7.id = "track_code"+indexcone;
t7.name="track_code[]";
cell7.appendChild(t7);
 
var cell8=row.insertCell(7);

var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
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
}

function mycalc()
{ 

// sum1 = 0.0;
// var amounts = document.getElementsByClassName('TAGAQTY');
// //alert("value="+amounts[0].value);
// for(var i=0; i<amounts .length; i++)
// { 
// var a = +amounts[i].value;
// sum1 += parseFloat(a);
// }
// document.getElementById("total_taga_qty").value = sum1.toFixed(2);
document.getElementById("total_taga_qty").value =document.getElementById('cntrr').value;

sum1 = 0.0;
var amounts = document.getElementsByClassName('METER');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("total_meter").value = sum1.toFixed(2);

 
}
 
function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 0){
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
 
</script>

<!-- end row -->
@endsection