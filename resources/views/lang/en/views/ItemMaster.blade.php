@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Item Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">Item Master</li>
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
    
  @if(isset($items))
<form action="{{ route('Item.update',$items) }}" method="POST" enctype="multipart/form-data">
@method('put')

@csrf   
    
    
<h4 class="card-title mb-4">Item Code: {{ $items->item_code }}</h4>
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
<label for="formrow-inputState" class="form-label">Category</label>
<select name="cat_id" class="form-select select2" id="cat_id" onchange="getClassList(this.value);">
<option value="">Select Category</option>
@foreach($Categorylist as  $row)
{
<option value="{{ $row->cat_id }}"
{{ $row->cat_id == $items->cat_id ? 'selected="selected"' : '' }}

	>{{ $row->cat_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="class_id" class="form-label">Classification</label>
<select name="class_id" class="form-select select2" id="class_id">
<option value="">-Classification-</option>
@foreach($Classificationlist as  $row)
{
<option value="{{ $row->class_id }}"
{{ $row->class_id == $items->class_id ? 'selected="selected"' : '' }}

	>{{ $row->class_name }}</option>

}
@endforeach
</select>
</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="material_type_id" class="form-label">Material Type</label>
<select name="material_type_id" class="form-select select2" id="material_type_id">
<option value="">Material Type</option>
@foreach($MaterialTypeList as  $row)
{
<option value="{{ $row->material_type_id }}"
{{ $row->material_type_id == $items->material_type_id ? 'selected="selected"' : '' }}

	>{{ $row->material_type_name }}</option>

}
@endforeach
</select>
</div>
</div>


<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" value="{{ $items->item_name }}" >
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="quality_code" class="form-label">Quality</label>
<select name="quality_code" class="form-select select2" id="quality_code">
<option value="">--Quality--</option>
@foreach($QualityList as  $row)
{
<option value="{{ $row->quality_code }}"
{{ $row->quality_code == $items->quality_code ? 'selected="selected"' : '' }}

	>{{ $row->quality_name }}</option>

}
@endforeach
</select>
</div>
</div>


<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Description/Count Construction</label>
<input type="text" name="item_description" class="form-control" id="formrow-email-input" value="{{ $items->item_description }}">
</div>
</div>
</div>

<div class="row">
    
<div class="col-md-2">
<div class="mb-3">
<label for="unit_id" class="form-label">UOM</label>
<select name="unit_id" class="form-select select2" id="unit_id">
<option value="">--UOM--</option>
@foreach($UnitList as  $row)
{
<option value="{{ $row->unit_id }}"
{{ $row->unit_id == $items->unit_id ? 'selected="selected"' : '' }}
    >{{ $row->unit_name }}</option>

}
@endforeach
</select>
</div>
</div>    
    
    
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Color Name</label>
<input type="text" name="color_name" class="form-control" id="formrow-email-input" value="{{ $items->color_name }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Width/Dimentions</label>
<input type="text" name="dimension" class="form-control" id="formrow-email-input" value="{{ $items->dimension }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="moq" class="form-label">MOQ</label>
<input type="text" name="moq" class="form-control" id="moq" value="{{ $items->moq }}">
</div>
</div>

  <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Item Rate</label>
<input type="text" name="item_rate" class="form-control" id="formrow-email-input" value="{{ $items->item_rate }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Item MRP</label>
<input type="text" name="item_mrp" class="form-control" id="formrow-email-input" value="{{ $items->item_mrp }}">
</div>
</div>
     
</div>

<div class="row">    

<div class="col-md-2">
<div class="mb-3">
<label for="cgst_per" class="form-label">CGST %</label>
<input type="text" name="cgst_per" class="form-control" id="cgst_per" value="{{ $items->cgst_per }}" onkeyup="change(this.value);">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="sgst_per" class="form-label">SGST %</label>
<input type="text" name="sgst_per" class="form-control" id="sgst_per" value="{{ $items->sgst_per }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="igst_per" class="form-label">IGST %</label>
<input type="text" name="igst_per" class="form-control" id="igst_per" value="{{ $items->igst_per }}">
</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="hsn_code" class="form-label">HSN Code</label>
<input type="text" name="hsn_code" class="form-control" id="hsn_code" value="{{ $items->hsn_code }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="pur_rate" class="form-label">Purchase Rate</label>
<input type="text" name="pur_rate" class="form-control" id=">" value="{{ $items->pur_rate }}">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Sale Rate</label>
<input type="text" name="sale_rate" class="form-control" id="formrow-email-input" value="{{ $items->sale_rate }}">
</div>
</div>


</div>

<div class="row">


 
<div class="col-md-2">
<div class="mb-3">
<label for="item_image_path" class="form-label">Item Image</label>
<input type="file" name="item_image_path" class="form-control" id="item_image_path">
<input type="hidden" name="old_item_image_path" class="form-control" id="item_image_path" value="{{$items->item_image_path}}">
</div>
</div>
 
<div class="col-md-4">
<div class="mb-3">
 </br> </br>
 
<input type="radio" name="active"  id="active1" value="0" @php if($items->delflag==0){echo 'checked="checked"';} @endphp>Active
<input type="radio" name="active"   id="active2" value="1" @php if($items->delflag==1){echo 'checked="checked"';} @endphp>Deactive

</div>
</div>

<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Preview: </label>
@if($items->item_image_path!='') 
 <a href="{{url('images/'.$items->item_image_path)}}" target="_blank"><img  src="{{url('thumbnail/'.$items->item_image_path)}}"  > </a>
 @else
 <label for="NoImage" class="form-label">No Item Image</label>
 @endif
 </div>
</div>

</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>

@else
<form action="{{route('Item.store')}}" method="POST" enctype="multipart/form-data">
@csrf 
<div class="row">
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Category</label>
<select name="cat_id" class="form-select select2" id="cat_id" onchange="getClassList(this.value);">
<option value="">Select Category</option>
@foreach($Categorylist as  $row)
{
<option value="{{ $row->cat_id }}">{{ $row->cat_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="class_id" class="form-label">Classification</label>
<select name="class_id" class="form-select select2" id="class_id">
<option value="">-Classification-</option>
@foreach($Classificationlist as  $row)
{
    <option value="{{ $row->class_id }}">{{ $row->class_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="material_type_id" class="form-label">Material Type</label>
<select name="material_type_id" class="form-select select2" id="material_type_id">
<option value="">Material Type</option>
@foreach($MaterialTypeList as  $row)
{
<option value="{{ $row->material_type_id }}">{{ $row->material_type_name }}</option>

}
@endforeach
</select>
<input type="hidden" name="userId" value="{{ Session::get('userId')}}" class="form-control" id="formrow-email-input">
</div>
</div>


<div class="col-md-4">
<div class="mb-3" id="itemshow">
<label for="formrow-email-input" class="form-label">Item Name</label>
<input type="text" name="item_name" class="form-control" id="formrow-email-input" onBlur="itemExist(this.value)" required>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="quality_code" class="form-label">Quality</label>
<select name="quality_code" class="form-select select2" id="quality_code">
<option value="">--Quality--</option>
@foreach($QualityList as  $row)
{
<option value="{{ $row->quality_code }}"
 

	>{{ $row->quality_name }}</option>

}
@endforeach
</select>
</div>
</div>


<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Description/Count Construction</label>
<input type="text" name="item_description" class="form-control" id="formrow-email-input">
</div>
</div>
</div>

 
<div class="row">
    
<div class="col-md-2">
<div class="mb-3">
<label for="unit_id" class="form-label">UOM</label>
<select name="unit_id" class="form-select select2" id="unit_id">
<option value="">--UOM--</option>
@foreach($UnitList as  $row)
{
<option value="{{ $row->unit_id }}">{{ $row->unit_name }}</option>

}
@endforeach
</select>
</div>
</div>    
    
    <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Color Name</label>
<input type="text" name="color_name" class="form-control" id="formrow-email-input" value="">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Width/Dimentions</label>
<input type="text" name="dimension" class="form-control" id="formrow-email-input" value="">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="moq" class="form-label">MOQ</label>
<input type="text" name="moq" class="form-control" id="moq" value="">
</div>
</div>


 <div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Item Rate</label>
<input type="text" name="item_rate" class="form-control" id="formrow-email-input">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Item MRP</label>
<input type="text" name="item_mrp" class="form-control" id="formrow-email-input">
</div>
</div>

</div>

<div class="row">

<div class="col-md-2">
<div class="mb-3">
<label for="cgst_per" class="form-label">CGST %</label>
<input type="text" name="cgst_per" class="form-control" id="cgst_per" onkeyup="change(this.value)">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="sgst_per" class="form-label">SGST %</label>
<input type="text" name="sgst_per" class="form-control" id="sgst_per">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="igst_per" class="form-label">IGST %</label>
<input type="text" name="igst_per" class="form-control" id="igst_per">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="hsn_code" class="form-label">HSN Code</label>
<input type="text" name="hsn_code" class="form-control" id="hsn_code">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="pur_rate" class="form-label">Purchase Rate</label>
<input type="text" name="pur_rate" class="form-control" id="pur_rate">
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="sale_rate" class="form-label">Sale Rate</label>
<input type="text" name="sale_rate" class="form-control" id="sale_rate">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="item_image_path" class="form-label">Item Image</label>
<input type="file" name="item_image_path" class="form-control" id="item_image_path">
</div>
</div> 

<div class="col-md-2">
<div class="mb-3">
 </br>
  </br>
<input type="radio" name="active"  id="active1" value="0" ><label for="html">Active</label> 
<input type="radio" name="active"  id="active2" value="1"><label for="html">DeActive</label> 
</div>
</div>
 
    


</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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
    
    function change(cgst_per)
    {
        
        $("#sgst_per").val(cgst_per);
        $("#igst_per").val(cgst_per*2);
    }
    
    
    
    
function itemExist(val) {	
    
   // alert(val);

$.ajax({
type: "GET",
url: "{{ route('itemexist') }}",
data:'item_name='+val,
success: function(data){
    
  //alert(data);  
$("#itemshow").html(data.html);
}
});
}   


 function getClassList(val) 
{ 
    $.ajax({
    type: "GET",
    url: "{{ route('ClassList') }}",
    data:'cat_id='+val,
    success: function(data){
    $("#class_id").html(data.html);
    }
    });
}  




    
</script>
<!-- end row -->


<!-- end row -->
@endsection