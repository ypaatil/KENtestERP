@extends('layouts.master') 
@section('content')
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">GET EMPLOYEE WISE DETAIL REPORT</h4>
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
            <form action="{{route('employeeWiseDetailReport')}}" method="GET" enctype="multipart/form-data">
               @csrf 
               <div class="row">
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{date('Y-m-01')}}">
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="mb-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{date('Y-m-d')}}">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="maincompany_id" class="form-label">Company</label>
                        <select name="maincompany_id" class="form-control" id="maincompany_id" onchange="GetSubCompanyList(this.value);">
                             <option value="0">--Select--</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="sub_company_id" class="form-label">Sub Company</label>
                        <select name="sub_company_id" class="form-select select2"  id="sub_company_id" onchange="GetEmployeeList(this.value);">
                           <option value="">--Select--</option> 
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="employeeCode" class="form-label">Employee</label>
                        <select name="employeeCode" class="form-control select2" id="employeeCode" onchange="GetEmpWiseSalesOrderList(this.value);">
                             <option value="0">--Select--</option> 
                        </select>
                     </div>
                  </div> 
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="sales_order_no" class="form-label">KDPL</label>
                        <select name="sales_order_no" class="form-control select2" id="sales_order_no" onchange="GetEmpWiseMainStyleList(this.value);">
                             <option value="">--Select--</option> 
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="mb-3">
                        <label for="mainstyle_id" class="form-label">Style Name</label>
                        <select name="mainstyle_id" class="form-control select2" id="mainstyle_id">
                             <option value="">--Select--</option> 
                        </select>
                     </div>
                  </div>
                   <div class="col-md-4">
                          <input type="radio" name="report_type" value="1" checked> Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="radio" name="report_type" value="2"> Summary
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
<script>

   $(function()
   {
       $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetHRMSCompanyList') }}", 
          success: function(data)
          {  
               $('#maincompany_id').html(data.html);  
               $('#maincompany_id').val(1).trigger('change');
          }
        });
   });
   
   function GetSubCompanyList(maincompany_id)
   {
        
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetHRMSSubCompanyList') }}",
          data:{'maincompany_id':maincompany_id},
          success: function(data)
          {  
               $('#sub_company_id').html(data.html);  
          }
        });
   }
      
   function GetEmployeeList(sub_company_id)
   {
        var maincompany_id = $("#maincompany_id").val();
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetHRMSEmployeeList') }}",
          data:{'maincompany_id':maincompany_id,'sub_company_id':sub_company_id},
          success: function(data)
          {  
               $('#employeeCode').html(data.html);  
          }
        });
   }
   
   function GetEmpWiseSalesOrderList(employeeCode)
   {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetEmpWiseSalesOrderList') }}",
          data:{'employeeCode':employeeCode},
          success: function(data)
          {  
               $('#sales_order_no').html(data.html);  
          }
        });
   }
   
   function GetEmpWiseMainStyleList(sales_order_no)
   {
        $.ajax({
          type: "GET",
          dataType:"json",
          url: "{{ route('GetEmpWiseMainStyleList') }}",
          data:{'sales_order_no':sales_order_no},
          success: function(data)
          {  
               $('#mainstyle_id').html(data.html);  
          }
        });
   }
   
 
</script>
<!-- end row -->
@endsection