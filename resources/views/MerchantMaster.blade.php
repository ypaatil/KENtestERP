@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Merchant Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Merchant Master</li>
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
<h4 class="card-title mb-4">Merchant Master</h4>
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

@if(isset($MerchantMasterList))
<form action="{{ route('MerchantMaster.update',$MerchantMasterList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-merchant_name-input" class="form-label">Merchant Name</label>
<input type="text" name="merchant_name" class="form-control" id="formrow-merchant_name-input" value="{{ $MerchantMasterList->merchant_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $MerchantMasterList->created_at }}">
   
</div>
</div>
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-contact-input" class="form-label">Contact</label>
<input type="text" name="contact" class="form-control" id="contact" value="{{ $MerchantMasterList->contact }}">

</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="text" name="email" class="form-control" id="email" value="{{ $MerchantMasterList->email }}">

</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>


@else
<form action="{{route('MerchantMaster.store')}}" method="POST">
@csrf 
<div class="row">

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Merchant Name</label>
<input type="text" name="merchant_name" class="form-control" id="formrow-email-input">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
 

<div class="col-md-4">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Contact</label>
<input type="text" name="contact" class="form-control" id="contact">

</div>
</div>

<div class="col-md-4">
<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="text" name="email" class="form-control" id="email" value="">

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


<!-- end row -->


<!-- end row -->
@endsection