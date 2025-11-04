@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Size Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Size Master</li>
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
<h4 class="card-title mb-4">Size</h4>
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

@if(isset($SizeList))
<form action="{{ route('Size.update',$SizeList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-size_name-input" class="form-label">Size</label>
<input type="text" name="sz_name" class="form-control" id="formrow-sz_name-input" value="{{ $SizeList->sz_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-userId-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $SizeList->created_at }}">
</div>
</div>
</div>

<div class="row">
<input type="number" value="@php echo count($SizeDetaillist); @endphp" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
    <th>SrNo</th>
    <th>Size</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

@if(count($SizeDetaillist)>0)

@php $no=1; @endphp
@foreach($SizeDetaillist as $List) 
 <tr>   <td><input type="text" name="id" value="{{$no}}" id="id" style="width:50px;"/></td>
    

<td> 
     <input type="hidden" name="size_id[]" value="{{$List->size_id}}" id="size_id" style="width:50px;"/>
     <input type="text" name="size_name[]"   id="size_name"   value="{{$List->size_name}}" style="width:80px;" required/></td>
    <td><input type="button" style="width:40px;" id="Abutton"  name="button[]"onclick="insertcone();" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>

</tr>  
@php $no=$no+1;  @endphp
@endforeach
@else
  <tr>   
    <td>
        
        <input type="text" name="id" value="" id="id" style="width:50px;"/>
        </td>
    <td>
        <input type="text" name="size_id[]" value="" id="size_id" style="width:50px;"/>
        <input type="text" name="size_name[]"   id="size_name"   value="" style="width:80px;" required/></td>
    <td><input type="button" style="width:40px;" id="Abutton"  name="button[]"onclick="insertcone();" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr> 
@endif



 </tbody>
 
</table>
</div>
</div>


</div>


<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('Size.index') }}" class="btn btn-warning w-md">Cancel</a>
</div>
</div>

 
</form>


@else
<form action="{{route('Size.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-sz_name-input" class="form-label">Size</label>
<input type="text" name="sz_name" class="form-control" id="formrow-sz_name-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
 
</div>
</div>
</div>


<div class="row">
<input type="number" value="1" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
    <th>SrNo</th>
    <th>Size</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<tr>
      <td><input type="text" name="size_id[]" value="" id="size_id" style="width:50px;"/></td>
    <td><input type="text" name="size_name[]"   id="size_name"   value="" style="width:80px;" required/></td>
    <td><input type="button" style="width:40px;" id="Abutton"  name="button[]"onclick="insertcone();" value="+" class="btn btn-warning pull-left"> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
 
</tr>

 </tbody>
 
</table>
</div>
</div>


</div>




<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label"></label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
<a href="{{ Route('Size.index') }}" class="btn btn-warning w-md">Cancel</a>

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


<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script>
function addrow()
{
    // var row = $("#footable_3 tr:last");
    // var newrow = row.clone();       
    // $("#footable_3").append(newrow);
    // document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;
    // recalcIdcone();
    
}

 
var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
var row=table.insertRow(table.rows.length);

var cell1=row.insertCell(0);
var t1=document.createElement("input");
t1.style="display: table-cell; width:50px;";
t1.id = "id"+indexcone;
t1.name= "id[]";
t1.value=indexcone;
 cell1.appendChild(t1);
var cell5 = row.insertCell(1);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="hidden";
t5.id = "size_id"+indexcone;
t5.name="size_id[]";
t5.value="0";
cell5.appendChild(t5);

var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.required=true;
t5.id = "size_name"+indexcone;
t5.name="size_name[]";
cell5.appendChild(t5);
 
 
var cell6=row.insertCell(2);
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

document.getElementById('cntrr').value = parseInt(document.getElementById('cntrr').value)+1;

indexcone++;
  
recalcIdcone();
}






function recalcIdcone(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
 
function deleteRowcone(btn) {
if(document.getElementById('cntrr').value > 1){
var row = btn.parentNode.parentNode;
row.parentNode.removeChild(row);

document.getElementById('cntrr').value = document.getElementById('cntrr').value-1;

recalcIdcone();

if($("#cntrr").val()<=0)
{		
document.getElementById('Submit').disabled=true;
}
 
}
} 
 
 
 
</script>

<!-- end row -->
@endsection