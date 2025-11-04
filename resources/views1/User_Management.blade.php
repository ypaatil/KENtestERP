@extends('layouts.master') 

@section('content')
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
<h4 class="mb-sm-0 font-size-18">User Master</h4>

<div class="page-title-right">
<ol class="breadcrumb m-0">
<li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
<li class="breadcrumb-item active">User Master</li>
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
<h4 class="card-title mb-4">Form Grid Layout</h4>
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

@if(isset($permissions))
<form action="{{ route('User_Management.update',$permissions) }}" method="POST">
@method('put')

@csrf 
<div class="row">
    
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-select" id="vendorId ">
<option value="">--- Select Vendor ---</option>
@foreach($VendorList as  $row)
{
<option value="{{ $row->ac_code }}"

{{ $row->ac_code == $permissions->vendorId ? 'selected="selected"' : '' }}

	>{{ $row->ac_name }}</option>

}
@endforeach
</select>
</div>
</div>    
    
    
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Employee</label>
<select name="w_id" class="form-select" id="w_id ">
<option value="">--- Select Employee ---</option>
@foreach($workerlist as  $row)
{
<option value="{{ $row->w_id }}"

{{ $row->w_id == $permissions->w_id ? 'selected="selected"' : '' }}

	>{{ $row->w_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">User Type</label>
<select name="user_type" class="form-select" id="user_type">
<option value="">--- User Type ---</option>
@foreach($user_typelist as  $user_typerow)
{
<option value="{{ $user_typerow->utype_id }}"

{{ $user_typerow->utype_id == $permissions->user_type ? 'selected="selected"' : '' }}

	>{{ $user_typerow->user_type }}</option>

}
@endforeach
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Contact</label>
<input type="text" name="contact" class="form-control" id="formrow-email-input" value="{{ $permissions->contact }}">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Address</label>
<input type="text" name="address" class="form-control" id="formrow-email-input" value="{{ $permissions->address }}">
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Name</label>
<input type="text" name="username" class="form-control" id="formrow-email-input" value="{{ $permissions->username }}">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Password</label>
<input type="text" name="password" class="form-control" id="formrow-email-input" value="{{ $permissions->password }}">
</div>
</div>
</div>

<table id="myTable1" class="table table-hover display  pb-30">
<thead>
<tr>
<th>SrNo</th>
<th>Form Name</th>
<th>Read</th>
<th>Write</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tfoot>
<tr>
<th>SrNo</th>
<th>Form Name</th>
<th>Read</th>
<th>Write</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</tfoot>
<tbody>



<input type="hidden" name="userId" class="form-control"  value="{{ $permissions->userId }}" />


<input type="hidden" name="row" value="{{ count($formlist) }}">


  @php $no=1; @endphp
 @foreach($formlist as $row)   

<tr>
<td>{{ $no }}</td>
<td>{{ $row->form_label }}</td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">


<input type="checkbox" name="chk{{ $no }}" value=""
 @foreach($formlistbyuser as $rowc) 
{{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}
 @endforeach
>  

</td>   
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">
	<input type="checkbox" name="chkw{{ $no }}" value="" 

 @foreach($formlistbyuser as $rowc) 
  @if($rowc->write_access == 1)

{{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}

@endif
 @endforeach                   

	></td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chke{{ $no }}" value="" 

 @foreach($formlistbyuser as $rowc) 
  @if($rowc->edit_access==1)

{{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}

@endif
 @endforeach  

	></td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}">
	<input type="checkbox" name="chkd{{ $no }}" value="" 

 @foreach($formlistbyuser as $rowc) 
  @if($rowc->delete_access==1)

{{ $row->form_code == $rowc->form_code ?  'checked="checked"' : ''  }}

@endif
 @endforeach  


	></td>
</tr>
 @php $no=$no+1;  @endphp
 @endforeach
</tbody>
</table>
<div>
<button type="submit" class="btn btn-primary w-md">Submit</button>
</div>
</form>

@else
<form action="{{route('User_Management.store')}}" method="POST">
@csrf 
<div class="row">
    
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Vendor</label>
<select name="vendorId" class="form-select" id="vendorId ">
<option value="">--- Select Vendor ---</option>
@foreach($VendorList as  $row)
{
<option value="{{ $row->ac_code }}"
 	>{{ $row->ac_name }}</option>
}
@endforeach
</select>
</div>
</div>     
    
    
    
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">Employee</label>
<select name="w_id" class="form-select" id="w_id ">
<option value="">--- Select Employee ---</option>
@foreach($workerlist as  $row)
{
<option value="{{ $row->w_id }}">{{ $row->w_name }}</option>

}
@endforeach
</select>
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-inputState" class="form-label">User Type</label>
<select name="user_type" class="form-select" id="user_type">
<option value="">--- User Type ---</option>
@foreach($user_typelist as  $user_typerow)
{
<option value="{{ $user_typerow->utype_id }}">{{ $user_typerow->user_type }}</option>

}
@endforeach
</select>
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Contact</label>
<input type="text" name="contact" class="form-control" id="formrow-email-input" value="">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Address</label>
<input type="text" name="address" class="form-control" id="formrow-email-input" value="">
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">User Name</label>
<input type="text" name="username" class="form-control" id="formrow-email-input" value="">
</div>
</div>
<div class="col-md-6">
<div class="mb-3">
<label for="formrow-email-input" class="form-label">Password</label>
<input type="text" name="password" class="form-control" id="formrow-email-input" value="">
</div>
</div>
</div>


<table id="myTable1" class="table table-hover display  pb-30">
<thead>
<tr>
<th>SrNo</th>
<th>Form Name</th>
<th>Read</th>
<th>Write</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tfoot>
<tr>
<th>SrNo</th>
<th>Form Name</th>
<th>Read</th>
<th>Write</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</tfoot>
<tbody>

@foreach($maxuserid as  $max)

<input type="hidden" name="userId" class="form-control"  value="{{ $max->userId }}" />

@endforeach

<input type="hidden" name="row" value="{{ count($formlist) }}">


  @php $no=1; @endphp
 @foreach($formlist as $row)   

<tr>
<td>{{ $no }}</td>
<td>{{ $row->form_label }}</td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chk{{ $no }}"></td>   
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chkw{{ $no }}"></td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chke{{ $no }}"></td>
<td><input type="hidden" name="form_id{{ $no }}" value="{{ $row->form_code }}"><input type="checkbox" name="chkd{{ $no }}"></td>
</tr>
 @php $no=$no+1;  @endphp
 @endforeach
</tbody>
</table>

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