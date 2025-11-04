@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Job Part Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Job Part Master</li>
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
<h4 class="card-title mb-4">Job Part</h4>
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
<form action="{{ route('JobPart.update',$JobPartList) }}" method="POST">
@method('put')

@csrf 
<div class="row">
   
  <div class="col-md-4">
<div class="mb-3">
<label for="fg_id" class="form-label">Job Style</label>
<select name="fg_id" class="form-select" id="fg_id" required>
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
     {{ $row->fg_id == $JobPartList->fg_id ? 'selected="selected"' : '' }}
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
<input type="hidden" name="jpart_id" value="{{ $JobPartList->jpart_id }}" class="form-control" id="jpart_id">

</div>
</div>
</div>

<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
    <thead>
    <tr>
    <th>ID</th>
        <th>Job Part (Short Code)</th>
        <th>Job Description</th>
    </tr>
    </thead>
<tbody>
    
    @php  if($detailparts->isEmpty()) { @endphp
<tr>
    <td>
         <input type="text"   name="id[]"  class="form-control" id="id" value="1" style="width:50px;">
        </td>
        <td>
         <input type="text"   name="jpart_name[]"  class="form-control" id="jpart_name" value="">
        </td>
        <td> 
            <input type="text"   name="jpart_description[]"  class="form-control" id="jpart_description[]" value="">
        </td>

        <td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>

    </tr>
        @php } else { @endphp
  @php $no=1; @endphp
@foreach($detailparts as $row)
    
   <tr>
    <td>
         <input type="text"   name="id[]"  class="form-control" id="id" value="{{  $no }}" style="width:50px;">
        </td>
        <td>
         <input type="text"   name="jpart_name[]"  class="form-control" id="jpart_name" value="{{ $row->jpart_name }}">
        </td>
        <td> 
            <input type="text"   name="jpart_description[]"  class="form-control" id="jpart_description[]" value="{{ $row->jpart_description }}">
        </td>

        <td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>

    </tr> 
    
    
 @php $no=$no+1;  @endphp
@endforeach
@php } @endphp
 </tbody>
<input type="number" value="{{ count($detailparts) }}" name="cnt" id="cnt" readonly="" hidden="true"  />
</table>
</div>
</div>

<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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
<script>



var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
t1.className="form-control";
t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;
cell1.appendChild(t1);
   
var cell5 = row.insertCell(1);
var t5=document.createElement("input");
t5.className="form-control";
t5.type="text";
t5.id = "jpart_name"+indexcone;
t5.name="jpart_name[]";
cell5.appendChild(t5);

var cell5 = row.insertCell(2);
var t5=document.createElement("input");
t5.className="form-control";
t5.type="text";
t5.id = "jpart_description"+indexcone;
t5.name="jpart_description[]";
cell5.appendChild(t5);



var cell6=row.insertCell(3);
var btnAdd = document.createElement("INPUT");
btnAdd.id = "Abutton";
btnAdd.type = "button";
btnAdd.className="btn btn-warning pull-left";
btnAdd.value = "+";
btnAdd.setAttribute("onclick", "insertcone()");
cell6.appendChild(btnAdd);

var btnRemove = document.createElement("INPUT");
btnRemove.id = "Dbutton";
btnRemove.type = "button";
btnRemove.className="btn btn-danger pull-left";
btnRemove.value = "X";
btnRemove.setAttribute("onclick", "deleteRowcone(this)");
cell6.appendChild(btnRemove);

var w = $(window);
var row = $('#footable_3').find('tr').eq(indexcone);

if (row.length){
$('html,body').animate({scrollTop: row.offset().top - (w.height()/2)}, 1000 );
}

document.getElementById('cnt').value = parseInt(document.getElementById('cnt').value)+1;

indexcone++;
 

recalcIdcone();
}    
    
    
 function deleteRowcone(btn) {
if(document.getElementById('cnt').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cnt').value = document.getElementById('cnt').value-1;

recalcIdcone();

if($("#cnt").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
}

function recalcIdcone(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
   
    
    
</script>
<!-- end row -->


<!-- end row -->
@endsection