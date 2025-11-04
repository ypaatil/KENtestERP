@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Firm Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Firm Master</li>
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
<h4 class="card-title mb-4">Firm</h4>
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

@if(isset($FirmList))
<form action="{{ route('Firm.update',$FirmList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Firm Name</label>
<input type="text" name="firm_name" class="form-control" id="ac_name" value="{{ $FirmList->firm_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>
 

<div class="col-md-4">
<div class="mb-3">
<label for="address" class="form-label">Address</label>
<input type="text" name="Address" class="form-control" id="Address" value="{{ $FirmList->Address }}">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $FirmList->created_at }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Country</label>
<select name="c_id" class="form-select" id="c_id">
<option value="">--- Select Country ---</option>
@foreach($Countrys as  $row)
{
<option value="{{ $row->c_id }}"

{{ $row->c_id == $FirmList->c_id ? 'selected="selected"' : '' }}

>{{ $row->c_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">State</label>
<select name="state_id" class="form-select" id="state_id">
<option value="">--State--</option>
@foreach($State as  $row)
{
<option value="{{ $row->state_id }}"

{{ $row->state_id == $FirmList->state_id ? 'selected="selected"' : '' }}

>{{ $row->state_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">District</label>
<select name="dist_id" class="form-select" id="dist_id">
<option value="">--- Select District ---</option>
@foreach($District as  $row)
{
<option value="{{ $row->d_id }}"

{{ $row->d_id == $FirmList->dist_id ? 'selected="selected"' : '' }}

>{{ $row->d_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputtaluka_id" class="form-label">Country</label>
<select name="taluka_id" class="form-select" id="taluka_id">
<option value="">--- Select Taluka ---</option>
@foreach($Taluka as  $row)
{
<option value="{{ $row->tal_id }}"

{{ $row->tal_id == $FirmList->taluka_id ? 'selected="selected"' : '' }}

>{{ $row->taluka }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="city_name" class="form-label">City Name</label>
<input type="text" name="city_name" class="form-control" id="city_name" value="{{ $FirmList->city_name }}">

</div>
</div>

</div>
<div class="row">
<div class="col-md-4">
<div class="mb-3">
<label for="formrow-mobile-input" class="form-label">Mobile</label>
<input type="text" name="mobile_no" class="form-control" id="formrow-mobile-input" value="{{ $FirmList->mobile_no }}">

</div>
</div>

 
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Email</label>
<input type="text" name="email_id" class="form-control" id="formrow-email-input" value="{{ $FirmList->email_id }}">

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-pan_no-input" class="form-label">PAN No.</label>
<input type="text" name="pan_no" class="form-control" id="formrow-pan_no-input" value="{{ $FirmList->pan_no }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-gst_no-input" class="form-label">GST No.</label>
<input type="text" name="gst_no" class="form-control" id="formrow-gst_no-input" value="{{ $FirmList->gst_no }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-owner_name-input" class="form-label">Owner Name</label>
<input type="text" name="owner_name" class="form-control" id="formrow-owner_name-input" value="{{ $FirmList->owner_name }}">
</div>
</div>
 

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-reg_id-input" class="form-label">Regisrtation No</label>
<input type="text" name="reg_id" class="form-control" id="formrow-reg_id-input" value="{{ $FirmList->reg_id }}">
</div>
</div>

</div>

<div class="row">
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Bank Name</label>
<div class="mb-3">
<input type="text" name="bank_name" class="form-control"   value="{{ $FirmList->bank_name }} " />
</div>
</div>
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account Name</label>
<div class="mb-3">
<input type="text" name="account_name" value="{{ $FirmList->account_name }}" class="form-control"  />
</div>
</div>

 
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account No</label>
<div class="mb-3">
<input type="text" name="account_no" class="form-control"   value="{{ $FirmList->account_no }}" />
</div>
</div>

<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Account Type</label>
<select name="ac_id" class="form-select" id="ac_id">
<option value="">--Select Type</option>
@foreach($Account_Type as  $row)
{
<option value="{{ $row->Ac_id }}"

{{ $row->Ac_id == $FirmList->ac_id ? 'selected="selected"' : '' }}

>{{ $row->Ac_type_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label">IFSC Code</label>
<div class="mb-3">
<input type="text" name="ifsc_code" value="{{ $FirmList->ifsc_code }}" class="form-control"  />
</div>
</div>
 
 
</div>

<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>


@else
<form action="{{route('Firm.store')}}" method="POST">
@csrf 
<div class="row">
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Firm Name</label>
<input type="text" name="firm_name" class="form-control" id="firm_name" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Address</label>
<input type="text" name="Address" class="form-control" id="Address" value="">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Country</label>
<select name="c_id" class="form-select" id="c_id" onChange="getState(this.value);">
<option value="">--- Select Country ---</option>
@foreach($Countrys as  $row)
{
    <option value="{{ $row->c_id }}">{{ $row->c_name }}</option>
}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">State</label>
<select name="state_id" class="form-select" id="state_id" onChange="getDistrict(this.value);">
<option value="">--State--</option>
@foreach($State as  $row)
{
<option value="{{ $row->state_id }}">{{ $row->state_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">District</label>
<select name="dist_id" class="form-select" id="dist_id" onChange="getTaluka(this.value);">
<option value="">--- Select District ---</option>
@foreach($District as  $row)
{
<option value="{{ $row->d_id }}">{{ $row->d_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Taluka</label>
<select name="taluka_id" class="form-select" id="taluka_id">
<option value="">--- Select Taluka ---</option>
@foreach($Taluka as  $row)
{
<option value="{{ $row->tal_id }}">{{ $row->taluka }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="city_name" class="form-label">City Name</label>
<input type="text" name="city_name" class="form-control" id="city_name" value="">

</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="formrow-mobile-input" class="form-label">Mobile</label>
<input type="text" name="mobile_no" class="form-control" id="formrow-mobile-input" value="">

</div>
</div>
</div>
<div class="row">
 
<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Email</label>
<input type="text" name="email_id" class="form-control" id="email_id" value="">

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="pan_no" class="form-label">PAN No.</label>
<input type="text" name="pan_no" class="form-control" id="pan_no" value="">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-GST_no-input" class="form-label">GST No.</label>
<input type="text" name="gst_no" class="form-control" id="formrow-GST_no-input" value="">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-owner_name-input" class="form-label">Owner Name</label>
<input type="text" name="owner_name" class="form-control" id="formrow-owner_name-input" value="">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-reg_id-input" class="form-label">Regisrtation No</label>
<input type="text" name="reg_id" class="form-control" id="formrow-reg_id-input" value="">
</div>
</div>
 
</div>

<div class="row">
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Bank Name</label>
<div class="mb-3">
<input type="text" name="bank_name" class="form-control"   value="" />
</div>
</div>
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account Name</label>
<div class="mb-3">
<input type="text" name="account_name" value="" class="form-control"  />
</div>
</div>

 
<div class="col-sm-2">
 
<label for="formrow-inputState" class="form-label">Account No</label>
<div class="mb-3">
<input type="text" name="account_no" class="form-control"   value="" />
</div>
</div>

<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Account Type</label>
<select name="ac_id" class="form-select" id="ac_id">
<option value="">--Select Type</option>
@foreach($Account_Type as  $row)
{
<option value="{{ $row->Ac_id }}">{{ $row->Ac_type_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label">IFSC Code</label>
<div class="mb-3">
<input type="text" name="ifsc_code" value="" class="form-control"  />
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


<!-- end row -->
<script>
function getState(val) 
{	alert(val);
    $.ajax({
    type: "GET",
    url: "{{ route('StateList') }}",
    data:'country_id='+val,
    success: function(data){
    $("#state_id").html(data.html);
    }
    });
}

function getDistrict(val) 
{	alert(val);
    $.ajax({
    type: "GET",
    url: "{{ route('DistrictList') }}",
    data:'state_id='+val,
    success: function(data){
    $("#dist_id").html(data.html);
    }
    });
}

function getTaluka(val) 
{	alert(val);
    $.ajax({
    type: "GET",
    url: "{{ route('TalukaList') }}",
    data:'dist_id='+val,
    success: function(data){
    $("#taluka_id").html(data.html);
    }
    });
}
</script>

<!-- end row -->
@endsection