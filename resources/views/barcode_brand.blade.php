@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Brand Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Brand Master</li>
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
<h4 class="card-title mb-4">Brand</h4>
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

<form action="@if(isset($BarcodeBrandFetch)) {{ route('barcode_brand.store',array('id'=>$BarcodeBrandFetch->barcode_brand_id)) }} @else {{ route('barcode_brand.store') }} @endif" method="POST" id="insertform">
   @csrf 
   
<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="brand_id" class="form-label">Brand Name</label>
<select name="brand_id" class="form-select" id="brand_id">
<option value="">--Type--</option>
@foreach($brandList as  $row)
{
<option value="{{ $row->brand_id  }}"

@if(isset($BarcodeBrandFetch))  {{ $row->brand_id == $BarcodeBrandFetch->brand_id ? 'selected="selected"' : '' }} @endif

>{{ $row->brand_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-brand_name-input" class="form-label"> Style</label>
<select name="mainstyle_id" class="form-select" id="mainstyle_id">
<option value="">--Type--</option>
@foreach($styleList as  $rowStyle)
{
<option value="{{ $rowStyle->mainstyle_id  }}"

@if(isset($BarcodeBrandFetch))  {{ $rowStyle->mainstyle_id == $BarcodeBrandFetch->mainstyle_id ? 'selected="selected"' : '' }} @endif

>{{ $rowStyle->mainstyle_name }}</option>

}
@endforeach
</select>



<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-brand_name-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ isset($BarcodeBrandFetch->created_at) ? $BarcodeBrandFetch->created_at: now() }}">
   
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Rate</label>
<input type="text" name="barcode_brand_rate" class="form-control" id="barcode_brand_rate" value="{{ isset($BarcodeBrandFetch->barcode_brand_rate) ? $BarcodeBrandFetch->barcode_brand_rate:"" }}">

</div>
</div>
 

</div>
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">&nbsp;</label>
<button type="submit" class="btn btn-primary w-md">Submit</button>
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


<!-- end row -->
@endsection