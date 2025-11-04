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
   <form action="{{ route('Employee.update', $EmployeeList) }}" method="POST" enctype="multipart/form-data">  
   @method('put')
   @csrf  
   <div class="row">
       <div class="row">
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="company_id" class="form-label">Company</label>
                   <select name="company_id" class="form-select" id="company_id">
                   <option value="">--Select--</option>
                   @foreach($companyList as  $row)
                   {
                   <option value="{{ $row->company_id }}"  {{ $row->company_id == $EmployeeList->company_id ? 'selected="selected"' : '' }} >{{ $row->company_name }}</option>
                   }
                   @endforeach
                   </select>
               </div>
           </div>
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="sub_company_id" class="form-label">Sub Company Name</label>
                   <select name="sub_company_id" class="form-select" id="sub_company_id">
                   <option value="">--Select--</option>
                   @foreach($subCompanyList as  $row)
                   <option value="{{ $row->sub_company_id }}"  {{ $row->sub_company_id == $EmployeeList->sub_company_id ? 'selected="selected"' : '' }}>{{ $row->sub_company_name }}</option>
                   @endforeach
                   </select>
               </div>
           </div>
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="employeeCode" class="form-label">Employee Code</label>
                   <input type="text" name="employeeCode" class="form-control" id="employeeCode" value="{{$EmployeeList->employeeCode}}">
                   <input type="hidden" name="userId" value="{{ Session::get('userId') }}" class="form-control" id="userId">
               </div>
           </div>
           <div class="col-md-6">
               <div class="mb-3">
                   <label for="employeeName" class="form-label">Employee Name</label>
                   <input type="text" name="employeeName" class="form-control" id="employeeName" value="{{$EmployeeList->employeeName}}"> 
               </div>
           </div> 
           <div class="col-md-4">
               <div class="mb-3">
                   <label for="operationNameId" class="form-label">Operations</label> 
                    <select id="operationNameId" name="operationNameId[]" class="form-control select2"  style="width:400px;" multiple>
                        <option value="">--Select--</option>  
                        @foreach($OperationNameList as $operations)
                        @php
                            $selected = '';
                            if($EmployeeList->employeeCode != "")
                            {
                                $opEmpData = DB::SELECT("SELECT *  FROM employee_wise_operations WHERE employeeCode=".$EmployeeList->employeeCode." AND operationNameId=".$operations->operationNameId);
                                
                                 
                                if(count($opEmpData) > 0)
                                {
                                    $selected = 'selected';
                                } 
                            }
                        @endphp
                         <option value="{{$operations->operationNameId}}" {{$selected}} >{{$operations->operation_name}}</option>  
                        @endforeach
                    </select>
               </div>
           </div>
       </div>
       <div class="row"> 
           <div class="col-md-6"> 
                   <button type="submit" class="btn btn-primary w-md">Submit</button> 
           
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
<script src="https://code.jquery.com/jquery-1.12.3.js"></script> 
<script>
    $(document).ready(function()
    {
        $('.select2-search-choice-close').hide();
    });
</script>
@endsection