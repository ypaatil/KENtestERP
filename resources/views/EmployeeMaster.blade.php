@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Employee Master</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Employee Master</li>
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
   <h4 class="card-title mb-4">Employee</h4>
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
   <form action="@if(isset($EmployeeList)) {{ route('Employee.store',array('id'=>$EmployeeList->employeeId)) }} @else {{ route('Employee.store') }} @endif" method="POST">
   @csrf 
   <div class="row">
       <div class="row">
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="company_id" class="form-label">Company</label>
                   <select name="maincompany_id" class="form-select" id="maincompany_id">
                   <option value="">--Select Party--</option>
                   @foreach($companyList as  $row)
                   {
                   <option value="{{ $row->company_id }}"
                   
                   @if(isset($EmployeeList))   {{ $row->company_id == $EmployeeList->maincompany_id ? 'selected="selected"' : '' }} @endif
                   
                   >{{ $row->company_name }}</option>
                   }
                   @endforeach
                   </select>
               </div>
           </div>
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="sub_company_id" class="form-label">Sub Company Name</label>
                   <select name="sub_company_id" class="form-select" id="sub_company_id">
                   <option value="">--Select Party--</option>
                   @foreach($subCompanyList as  $rowSub)
                   {
                   <option value="{{ $rowSub->sub_company_id }}"
                   
                    @if(isset($EmployeeList))   {{ $rowSub->sub_company_id == $EmployeeList->sub_company_id ? 'selected="selected"' : '' }} @endif
                   
                   >{{ $rowSub->sub_company_name }}</option>
                   }
                   @endforeach
                   </select>
               </div>
           </div>
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="employeeCode" class="form-label">Employee Code</label>
                   <input type="text" name="employeeCode" class="form-control" id="employeeCode" value="{{isset($EmployeeList->employeeCode) ? $EmployeeList->employeeCode: ""}}">
                   <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
                   <input type="hidden" name="egroup_id" value="71" class="form-control" id="egroup_id"> 
                   <input type="hidden" name="employee_status_id" value="1" class="form-control" id="egroup_id">   
                   <input type="hidden" name="emp_cat_id" value="3" class="form-control" id="emp_cat_id">     
                   
               </div>
           </div>
           <div class="col-md-6">
               <div class="mb-3">
                   <label for="employeeName" class="form-label">Employee Name</label>
                   <input type="text" name="fullName" class="form-control" id="fullName" value="{{isset($EmployeeList->fullName) ? $EmployeeList->fullName: ""}}"> 
               </div>
           </div> 
       </div>
       <div class="row"> 
           <div class="col-md-6"> 
                   <button type="submit" class="btn btn-primary w-md">@if(isset($EmployeeList)) Update @else Save @endif</button> 
           
                   <a href="/Employee" class="btn btn-danger">Cancel</a> 
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
</div>
</div>
<!-- end row -->
<!-- end row -->
<!-- end row -->
@endsection