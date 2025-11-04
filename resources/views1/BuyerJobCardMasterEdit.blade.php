@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Buyer Job Card</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
<li class="breadcrumb-item active">Buyer Job Card</li>
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
<h4 class="card-title mb-4">Job Card: {{ $BuyerJobCardMasterList->po_code }}</h4>
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
 


@if(isset($BuyerJobCardMasterList))
<form action="{{ route('BuyerJobCard.update',$BuyerJobCardMasterList) }}" method="POST" enctype="multipart/form-data">
@method('put')


@csrf
<div class="row">

<div class="col-md-2">
    <div class="mb-3">
        <label for="po_date" class="form-label">Job Card Date</label>
        <input type="date" name="po_date" class="form-control" id="po_date" value="{{ $BuyerJobCardMasterList->po_date }}">
    </div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style No</label>
<input type="text" name="style_no" class="form-control" id="style_no" value="{{ $BuyerJobCardMasterList->style_no }}">
 
     <input type="hidden" name="po_code" class="form-control" id="po_code" value="{{ $BuyerJobCardMasterList->po_code }}">
 

     <input type="hidden" name="size_counter" class="form-control" id="size_counter" value="{{ $BuyerJobCardMasterList->size_counter }}">
<input type="hidden" name="c_code" class="form-control" id="c_code" value="{{ $BuyerJobCardMasterList->c_code }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $BuyerJobCardMasterList->created_at }}">
 </div>
</div>
 


<div class="col-md-2">
<div class="mb-3">
<label for="cp_id" class="form-label">Type</label>
<select name="cp_id" class="form-select" id="cp_id" required>
<option value="">--CP Type--</option>
@foreach($CPList as  $row)
{
    <option value="{{ $row->cp_id }}"
    {{ $row->cp_id == $BuyerJobCardMasterList->cp_id ? 'selected="selected"' : '' }}    
    >{{ $row->cp_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Buyer/Party</label>
<select name="Ac_code" class="form-select" id="Ac_code">
<option value="">--Select Buyer--</option>
@foreach($Ledger as  $row)
{
    <option value="{{ $row->ac_code }}"
    {{ $row->ac_code == $BuyerJobCardMasterList->Ac_code ? 'selected="selected"' : '' }}    
    >{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>
  
<div class="col-md-2">
<div class="mb-3">
<label for="fg_id" class="form-label">Job Style</label>
<select name="fg_id" class="form-select" id="fg_id">
<option value="">--Select Style--</option>
@foreach($FGList as  $row)
{
    <option value="{{ $row->fg_id }}"
    {{ $row->fg_id == $BuyerJobCardMasterList->fg_id ? 'selected="selected"' : '' }}       
    >{{ $row->fg_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="brand_id" class="form-label">Brand</label>
<select name="brand_id" class="form-select" id="brand_id">
<option value="">--Select Brand--</option>
@foreach($BrandList as  $row)
{
    <option value="{{ $row->brand_id }}"
    {{ $row->brand_id == $BuyerJobCardMasterList->brand_id ? 'selected="selected"' : '' }}         
    >{{ $row->brand_name }}</option>
}
@endforeach
</select>
</div>
</div>
 
</div>
<div class="row">

<div class="col-md-2">
<div class="mb-3">
<label for="season_id" class="form-label">Season</label>
<select name="season_id" class="form-select" id="season_id">
<option value="">--Select Style--</option>
@foreach($SeasonList as  $row)
{
    <option value="{{ $row->season_id }}"
    {{ $row->season_id == $BuyerJobCardMasterList->season_id ? 'selected="selected"' : '' }}     
    >{{ $row->season_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="style_pic_path" class="form-label">Attach Style Picture</label>
<input type="file" name="style_pic_path" class="form-control" id="style_pic_path"  >
<input type="hidden" name="style_pic_pathold" class="form-control" id="style_pic_pathold" value="{{ $BuyerJobCardMasterList->style_pic_path }}" >
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="doc_path1" class="form-label">Attach Document1</label>
<input type="file" name="doc_path1" class="form-control" id="doc_path1"  >
<input type="hidden" name="doc_path1old" class="form-control" id="doc_path1old" value="{{ $BuyerJobCardMasterList->doc_path1 }}" >
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="doc_path2" class="form-label">Attach Document2</label>
<input type="file" name="doc_path2" class="form-control" id="doc_path2"  >
<input type="hidden" name="doc_path2old" class="form-control" id="doc_path2old" value="{{ $BuyerJobCardMasterList->doc_path_2 }}" >
</div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" id="start_date" value="{{ $BuyerJobCardMasterList->start_date }}">
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" id="end_date" value="{{ $BuyerJobCardMasterList->end_date }}">
    </div>
</div>

</div>
  
<div class="row">
<input type="number" value="{{ count($job_card_detailslist) }}" name="cntrr" id="cntrr" readonly="" hidden="true"  />
<div class="table-wrap">
<div class="table-responsive">
<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
<thead>
<tr>
<th>SrNo</th>
<th>Color</th>
<th>Size</th>
 <th>Qty</th>
<th>Remove</th>
</tr>
</thead>
<tbody>
@if(count($job_card_detailslist)>0)

@php $no=1; @endphp
@foreach($job_card_detailslist as $List) 

<tr>
<td><input type="text" name="id" value="@php echo $no; @endphp" id="id" style="width:50px;"/></td>
  
<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Color--</option>
@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}"
    {{ $row->color_id == $List->color_id ? 'selected="selected"' : '' }}        
    >{{ $row->color_name }}</option>
}
@endforeach
</select></td>

<td> <select name="sz_code[]"  id="sz_code" style="width:100px;" required>
<option value="">--Size--</option>
@foreach($SizeList as  $row2)
{
    <option value="{{ $row2->sz_code }}"

  {{ $row2->sz_code == $List->sz_code ? 'selected="selected"' : '' }}    

        >{{ $row2->sz_name }}</option>
}
@endforeach
</select></td>

<td><input type="text" class="PRODQTY" onkeyup="mycalc();" name="production_qty[]" value="{{ $List->qty }}" id="prodqty1" style="width:80px;" required /></td>
<td><button type="button" onclick="insertcone(); " class="btn btn-warning pull-left">+</button><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
@php $no=$no+1;  @endphp
@endforeach

@else
 
    <tr>
<td><input type="text" name="id" value="1" id="id" style="width:50px;"/></td>
<td> <select name="item_code[]"  id="item_code" style="width:100px;" required>
<option value="">--Item--</option>
@foreach($ItemList as  $row)
{
    <option value="{{ $row->item_code }}">{{ $row->item_name }}</option>
}
@endforeach
</select></td>

<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Color--</option>
@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}">{{ $row->color_name }}</option>
}
@endforeach
</select></td>
<td> <select name="sz_code[]"  id="sz_code" style="width:100px;" required>
<option value="">--Size--</option>
@foreach($SizeList as  $row2)
{
    <option value="{{ $row2->sz_code }}" 

        >{{ $row2->sz_name }}</option>
}
@endforeach
</select></td>
 <td><input type="text" class="PRODQTY" onkeyup="mycalc();" name="production_qty[]" value="0" id="prodqty1" style="width:80px;" required /></td>
<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
</tr>
 @endif
 </tbody>
<tfoot>
<tr>
<th>Color</th>
<th>Size</th>
 <th>Qty</th>
<th>Remove</th>
</tr>
</tfoot>
</table>
</div>
</div>



<div class="col-md-2">
    <div class="mb-3">
        <label for="total_qty" class="form-label">Production Qty</label>
        <input type="number"   name="prod_qty" class="form-control" id="prod_qty" value="{{ $BuyerJobCardMasterList->prod_qty }}" onkeyup="mycalc();">
    </div>
</div>
<div class="col-md-2">
    <div class="mb-3">
        <label for="rate_per_piece" class="form-label">Rate / Piece</label>
        <input type="number" step="0.01"  name="rate_per_piece" class="form-control" id="rate_per_piece" value="{{ $BuyerJobCardMasterList->rate_per_piece }}" onkeyup="calculateamount();">
    </div>
</div>

<div class="col-sm-2">
<label for="total_amount" class="form-label">Total Amount</label>
<div class="form-group">
<input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ $BuyerJobCardMasterList->total_amount }}" class="form-control"   />
</div>
</div>


 <div class="col-md-2">
    <div class="mb-3">
        <label for="ppk_ratio" class="form-label">PPK Ratio</label>
        <input type="text"  name="ppk_ratio" placeholder="For S:M:L:XL write as 1,2,3,4"  class="form-control" id="ppk_ratio" value="{{ $BuyerJobCardMasterList->ppk_ratio }}">
    </div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="total_meter" class="form-label">Color</label>
<select name="color_ids[]"  id="color_ids" class="form-select" multiple="multiple"  required>
<option value="">--Color--</option>

@foreach($ColorList as  $row)
{
    <option value="{{ $row->color_id }}"
    @if(in_array($row->color_id, $color_ids)) selected @endif   
    >{{ $row->color_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
    <div class="mb-3">
        <label for="piece_avg" class="form-label">Piece Average</label>
        <input type="text"   name="piece_avg" placeholder="e.g. 1.5,1.2,1.9,0.6"  class="form-control" id="piece_avg" value="{{ $BuyerJobCardMasterList->piece_avg }}" required>
    </div>
</div>



</div>
 
<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<h4 class="card-title">Samples and Guideline Required</h4>


<div class="row">

<div class="col-xl-7 col-sm-7"><label for="comment_guidance" class="form-label">Samples</label>
<div class="mt-4">
 <div>

<div class="table-wrap">
<div class="table-responsive">

<table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
    <thead>
    <tr>
        <th>Sample Required</th>
        <th>Company Date</th>
        <th>Tentative Date</th>
    </tr>
    </thead>
<tbody>
@foreach($SampleList as  $row)

 
    <tr>
        <th>
            <div class="form-check form-checkbox-outline form-check-danger mb-3">
            <input class="form-check-input" type="checkbox" name="sample[]" value="{{ $row->sample_id }}"
            id="sample"  @foreach($SampleSetList as  $rowset) {{ $row->sample_id == $rowset->sample_id ? 'checked' : '' }}  @endforeach >
            <label class="form-check-label" for="sample">
            {{ $row->sample_name }}
            </label>
            </div>
        </th>
        <th> 
            <input type="date"   name="sample_comp_date[]"  class="form-control" id="sample_comp_date[]" @foreach($SampleSetList as  $rowset) @if($rowset->sample_id== $row->sample_id ) {{ $row->sample_id == $rowset->sample_id ? "value=$rowset->sample_comp_date" :  ''  }} @endif @endforeach>
        </th>
        <th> 
        <input type="date"   name="sample_tentative_date[]"  class="form-control" id="sample_tentative_date[]" @foreach($SampleSetList as  $rowset)  @if($rowset->sample_id== $row->sample_id ) {{ $row->sample_id == $rowset->sample_id ? "value=$rowset->sample_tentative_date" : ''  }} @endif @endforeach>
        </th>
    </tr>
 


@endforeach
 </tbody>
 
</table>
</div>
</div>
 
</div>


</div>   <!--Open div End -->
  



</div><!--mt-4 End -->


<div class="col-xl-5 col-sm-5">
        <label for="comment_guidance" class="form-label">Comments / Guidelines</label>
        <textarea type="text" name="comment_guidance" class="form-control" id="editor1" required  >{{ $BuyerJobCardMasterList->comment_guidance }}
        </textarea>
    </div> 





</div> <!-- row end -->



 
</div>
</div>
</div>
</div>
<!-- end row -->
<div class="row">
<div class="col-sm-10">
<label for="formrow-inputState" class="form-label">Narration</label>
<div class="mb-3">
<input type="text" name="narration" class="form-control" id="narration"  value="{{ $BuyerJobCardMasterList->narration }}" />
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="job_status_id" class="form-label">Job Status</label>
<select name="job_status_id" class="form-select" id="job_status_id">
<option value="">--Job Status--</option>
@foreach($JobStatusList as  $row)
{
    <option value="{{ $row->job_status_id }}"
    {{ $row->job_status_id == $BuyerJobCardMasterList->job_status_id ? 'selected="selected"' : '' }}        
    >{{ $row->job_status_name }}</option>
}
@endforeach
</select>
</div>
</div>


</div>

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="submit" class="btn btn-primary w-md">Submit</button>
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

<script> 
                // Replace the <textarea id="editor1"> with a CKEditor 
                // instance, using default configuration. 
                CKEDITOR.replace('editor1'); 
                CKEDITOR.replace('editor2');
            </script> 
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>
 
 $(document).on('keyup','input[name^="meter[]"],input[name^="production_qty[]"]', function(event) {  
    
    mycalc();

});
 
var indexcone = 2;
function insertcone(){

var table=document.getElementById("footable_3").getElementsByTagName('tbody')[0];
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
var x = $("#color_id"),
y = x.clone();
y.attr("id","color_id");
y.attr("name","color_id[]");
y.width(100);
y.appendTo(cell3);



var cell4 = row.insertCell(2);
var t4=document.createElement("select");
var x = $("#sz_code"),
y = x.clone();
y.attr("id","sz_code");
y.attr("name","sz_code[]");
y.width(100);
y.appendTo(cell4);
 

 
var cell5 = row.insertCell(3);
var t5=document.createElement("input");
t5.style="display: table-cell; width:80px;";
t5.type="text";
t5.onkeyup="mycalc();";
t5.className="PRODQTY";
t5.id = "production_qty"+indexcone;
t5.name="production_qty[]";
cell5.appendChild(t5);
 

var cell6=row.insertCell(4);

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
$("#no_cones").val("");
$("#bags").val("");
$("#fr_weights").val("");

recalcIdcone();
}

function mycalc()
{   

sum1 = 0.0;
var amounts = document.getElementsByClassName('PRODQTY');
//alert("value="+amounts[0].value);
for(var i=0; i<amounts .length; i++)
{ 
var a = +amounts[i].value;
sum1 += parseFloat(a);
}
document.getElementById("prod_qty").value = sum1.toFixed(2);

calculateamount();
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

function calculateamount()
{
    
    
var prod_qty=document.getElementById('prod_qty').value;
var rate_per_piece=document.getElementById('rate_per_piece').value;


var total_amount= parseFloat(parseFloat(prod_qty) * parseFloat(rate_per_piece));
$('#total_amount').val(total_amount.toFixed(2));
}


function recalcIdcone(){
$.each($("#footable_3 tr"),function (i,el){
$(this).find("td:first input").val(i); // Simply couse the first "prototype" is not counted in the list
})
}
</script>
 
<!-- end row -->
@endsection