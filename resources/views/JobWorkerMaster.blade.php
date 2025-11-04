@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">Job Worker Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
<li class="breadcrumb-item active">Job Worker Master</li>
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
<h4 class="card-title mb-4">Job Worker</h4>
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

@if(isset($WorkerList))
<form action="{{ route('JobWorker.update',$WorkerList) }}" method="POST">
@method('put')

@csrf 
<div class="row">

  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-dept_id" class="form-label">Department</label>
<select name="dept_id" class="form-select" id="dept_id">
<option value="">--Dept--</option>
@foreach($DeptList as  $row)
{
<option value="{{ $row->dept_id }}"

{{ $row->dept_id == $WorkerList->dept_id ? 'selected="selected"' : '' }}

>{{ $row->dept_name }}</option>

}
@endforeach
</select>
</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="formrow-egroup_id" class="form-label">Employee Group</label>
<select name="egroup_id" class="form-select" id="egroup_id">
<option value="">--Group--</option>
@foreach($EmpGroup as  $row)
{
<option value="{{ $row->egroup_id }}"

{{ $row->egroup_id == $WorkerList->egroup_id ? 'selected="selected"' : '' }}

>{{ $row->egroup_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Worker Name</label>
<input type="text" name="w_name" class="form-control" id="w_name" value="{{ $WorkerList->w_name }}">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
<input type="hidden" name="created_at" class="form-control" id="created_at" value="{{ $WorkerList->created_at }}">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-w_contact-input" class="form-label">Contact</label>
<input type="text" name="w_contact" class="form-control" id="formrow-w_contact-input" value="{{ $WorkerList->w_contact }}">

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="w_address" class="form-label">Worker Address</label>
<input type="text" name="w_address" class="form-control" id="w_address" value="{{ $WorkerList->w_address }}">

</div>
</div>
</div>
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="w_particular" class="form-label">Worker Speciality</label>
<input type="text" name="w_particular" class="form-control" id="w_particular" value="{{ $WorkerList->w_particular }}">

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="basic_pay" class="form-label">Basic Salary</label>
<input type="text" name="basic_pay" class="form-control" id="basic_pay" value="{{ $WorkerList->basic_pay }}">

</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="salary_id" class="form-label">Salary Type</label>
<select name="salary_id" class="form-select" id="salary_id">
<option value="">--Type--</option>
@foreach($Salary_Type as  $row)
{
<option value="{{ $row->salary_id }}"

{{ $row->salary_id == $WorkerList->salary_id ? 'selected="selected"' : '' }}

>{{ $row->type }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="ptm_id" class="form-label">Payment Term</label>
<select name="ptm_id" class="form-select" id="ptm_id">
<option value="">--Pay Term--</option>
@foreach($PayTerm as  $row)
{
<option value="{{ $row->ptm_id }}"

{{ $row->ptm_id == $WorkerList->ptm_id ? 'selected="selected"' : '' }}

>{{ $row->ptm_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="day_count" class="form-label">Days</label>
<input type="text" name="day_count" class="form-control" id="day_count" value="{{ $WorkerList->day_count }}">

</div>
</div>
 

<div class="col-md-2">
<div class="mb-3">
<label for="m_id" class="form-label">Machine ID</label>
<input type="text" name="m_id" class="form-control" id="m_id" value="{{ $WorkerList->m_id }}">

</div>
</div>

</div>

<div class="row">
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Bank Name</label>
<div class="mb-3">
<input type="text" name="bank_name" class="form-control"   value="{{ $WorkerList->bank_name }} " />
</div>
</div>
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account Name</label>
<div class="mb-3">
<input type="text" name="account_name" value="{{ $WorkerList->account_name }}" class="form-control"  />
</div>
</div>

 
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account No</label>
<div class="mb-3">
<input type="text" name="account_no" class="form-control"   value="{{ $WorkerList->account_no }}" />
</div>
</div>

<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">A/C Type</label>
<select name="ac_id" class="form-select" id="ac_id">
<option value="">--Select Type</option>
@foreach($Account_Type as  $row)
{
<option value="{{ $row->Ac_id }}"

{{ $row->Ac_id == $WorkerList->ac_id ? 'selected="selected"' : '' }}

>{{ $row->Ac_type_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-sm-2">
<label for="formrow-inputState" class="form-label">IFSC Code</label>
<div class="mb-3">
<input type="text" name="ifsc_code" value="{{ $WorkerList->ifsc_code }}" class="form-control"  />
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


@else
<form action="{{route('JobWorker.store')}}" method="POST">
@csrf 
<div class="row">

  
<div class="col-md-2">
<div class="mb-3">
<label for="formrow-dept_id" class="form-label">Department</label>
<select name="dept_id" class="form-select" id="dept_id">
<option value="">--Dept--</option>
@foreach($DeptList as  $row)
{
<option value="{{ $row->dept_id }}">{{ $row->dept_name }}</option>

}
@endforeach
</select>
</div>
</div>



<div class="col-md-2">
<div class="mb-3">
<label for="formrow-egroup_id" class="form-label">Employee Group</label>
<select name="egroup_id" class="form-select" id="egroup_id">
<option value="">--Group--</option>
@foreach($EmpGroup as  $row)
{
<option value="{{ $row->egroup_id }}">{{ $row->egroup_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Worker Name</label>
<input type="text" name="w_name" class="form-control" id="w_name" value="">
<input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="formrow-email-input">
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="formrow-w_contact-input" class="form-label">Contact</label>
<input type="text" name="w_contact" class="form-control" id="formrow-w_contact-input" value="">

</div>
</div>

<div class="col-md-3">
<div class="mb-3">
<label for="w_address" class="form-label">Worker Address</label>
<input type="text" name="w_address" class="form-control" id="w_address" value="">

</div>
</div>
</div>
<div class="row"> 
<div class="col-md-2">
<div class="mb-3">
<label for="w_particular" class="form-label">Worker Speciality</label>
<input type="text" name="w_particular" class="form-control" id="w_particular" value="">

</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="basic_pay" class="form-label">Basic Salary</label>
<input type="text" name="basic_pay" class="form-control" id="basic_pay" value="">

</div>
</div>


<div class="col-md-2">
<div class="mb-3">
<label for="salary_id" class="form-label">Salary Type</label>
<select name="salary_id" class="form-select" id="salary_id">
<option value="">--Type--</option>
@foreach($Salary_Type as  $row)
{
<option value="{{ $row->salary_id }}">{{ $row->type }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="ptm_id" class="form-label">State</label>
<select name="ptm_id" class="form-select" id="ptm_id">
<option value="">--Pay Term--</option>
@foreach($PayTerm as  $row)
{
<option value="{{ $row->ptm_id }}">{{ $row->ptm_name }}</option>

}
@endforeach
</select>
</div>
</div>

<div class="col-md-2">
<div class="mb-3">
<label for="day_count" class="form-label">Days</label>
<input type="text" name="day_count" class="form-control" id="day_count" value="">

</div>
</div>
 
<div class="col-md-2">
<div class="mb-3">
<label for="m_id" class="form-label">Machine ID</label>
<input type="text" name="m_id" class="form-control" id="m_id" value="">

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

 
<div class="col-sm-3">
 
<label for="formrow-inputState" class="form-label">Account No</label>
<div class="mb-3">
<input type="text" name="account_no" class="form-control"   value="" />
</div>
</div>

<div class="col-md-1">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">A/C Type</label>
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