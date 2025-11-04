@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Finished Goods Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Style Name Master</li>
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
<h4 class="card-title mb-4">Style Name</h4>
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

@if(isset($FGList))
<form action="{{ route('FinishedGood.update',$FGList) }}" method="POST">
@method('put')

@csrf 
<div class="row">



<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id" onchange="getSubStyle(this.value)">
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
    <option value="{{ $row->mainstyle_id }}"
    {{ $row->mainstyle_id == $FGList->mainstyle_id ? 'selected="selected"' : '' }}
    >{{ $row->mainstyle_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style</label>
<select name="substyle_id" class="form-select" id="substyle_id">
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}"
    {{ $row->substyle_id == $FGList->substyle_id ? 'selected="selected"' : '' }}
    >{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>




<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style Name</label>
<input type="text" name="fg_name" class="form-control" id="formrow-email-input" value="{{ $FGList->fg_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FGList->created_at }}">
   
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Average Meter/Piece</label>
<input type="number" step="0.01" name="avg_mtr" class="form-control" id="avg_mtr" value="{{ $FGList->avg_mtr }}">

</div>
</div>
 
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">  </label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</div>
</form>


@else
<form action="{{route('FinishedGood.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Main Style</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id"  onchange="getSubStyle(this.value)">
<option value="">--Main Style--</option>
@foreach($MainStyleList as  $row)
{
<option value="{{ $row->mainstyle_id }}">{{ $row->mainstyle_name }}</option>

}
@endforeach
</select>
</div>
</div>
    
    
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Sub Style</label>
<select name="substyle_id" class="form-select" id="substyle_id">
<option value="">--Sub Style--</option>
@foreach($SubStyleList as  $row)
{
    <option value="{{ $row->substyle_id }}">{{ $row->substyle_name }}</option>
}
@endforeach
</select>
</div>
</div>






<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Style Name</label>
<input type="text" name="fg_name" class="form-control" id="formrow-email-input" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="">
   
</div>
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Average Meter/Piece</label>
<input type="number" step="0.01" name="avg_mtr" class="form-control" id="avg_mtr" value="">

</div>
</div>
 
</div>
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">  </label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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

<script>
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
</script>
<!-- end row -->
@endsection