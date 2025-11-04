@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Employee Date Wise Salary Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Employee Date Wise Salary Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/EmployeeDateWiseSalary" method="GET" enctype="multipart/form-data">
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="fromDate" class="form-label">From date</label>
                                <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}" required> 
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}" required>
                            </div>
                        </div> 
                     <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Unit</label>
                                <select class="form-control select2" id="vendorId" name="vendorId">
                                    <option value="">--Select--</option>
                                     @foreach($unitList as $fetch_unit) 
                                       <option value="{{$fetch_unit->ac_code }}" {{ $fetch_unit->ac_code == $vendorId ? 'selected="selected"' : '' }} >{{$fetch_unit->ac_name}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div>   
                        
                        
                        <div class="col-sm-6 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                                <a href="/EmployeeDateWiseSalary" class="btn btn-danger w-md">Cancel</a>
                            </div>
                        </div>
                    </div> 
                </form> 
            </div>
        </div>
    </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table  id="datatable-buttons" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr>
                        <th>Sr No</th>
                         <th nowrap>Unit</th>
                        <th nowrap>Employee Code</th>
                        <th nowrap>Employee Name</th>
                        @foreach($allDates as $dates)
                            <th nowrap>{{date('d-m-Y', strtotime($dates))}}</th>
                        @endforeach
                        <th>Amount</th> 
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1;
                            $total_amount1 = 0;
                        @endphp
                        @foreach($employeeList as $emp)
                        @php
                            $total_amount = 0;
                        @endphp
                        <tr>
                            <td>{{$srno++}}</td>
                              <td>{{$emp->ac_name}}</td>
                                <td>{{$emp->employeeCode}}</td>
                                <td>{{$emp->employeeName}}</td>
                            @foreach($allDates as $dates)
                                @php
                                    $TotalAMountData = DB::SELECT("SELECT sum(daily_production_entry_details.amount) as amount FROM daily_production_entry_details 
                                                        INNER JOIN daily_production_entry ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                                        WHERE daily_production_entry.dailyProductionEntryDate='".$dates."' AND daily_production_entry.employeeCode=".$emp->employeeCode);
                                                        
                                    $amount = isset($TotalAMountData[0]->amount) ? $TotalAMountData[0]->amount : 0;
                                    $total_amount += $amount;
                                @endphp
                                <td  class="text-right">{{money_format('%!i',$amount)}}</td>
                            @endforeach
                            <td  class="text-right"><a href="/EmployeeDetailedSalaryReport?fromDate={{$fromDate}}&toDate={{$toDate}}&employeeCode={{$emp->employeeCode}}" target="_blank">{{money_format('%!i',$total_amount)}}</a></td> 
                        </tr>
                        @endforeach
                  </tbody>
                  <tfoot>
                      <tr>
                          <th colspan="4" class="text-right"><b>Total </b></th>
                          @foreach($allDates as $dates)
                            @php
                                $TotalAMountData1 = DB::SELECT("SELECT sum(daily_production_entry_details.amount) as amount FROM daily_production_entry_details 
                                                    INNER JOIN daily_production_entry ON daily_production_entry.dailyProductionEntryId = daily_production_entry_details.dailyProductionEntryId
                                                    WHERE daily_production_entry.dailyProductionEntryDate='".$dates."'");
                                                    
                                $amount1 = isset($TotalAMountData1[0]->amount) ? $TotalAMountData1[0]->amount : 0; 
                                $total_amount1 += $amount1;
                            @endphp
                            <th class="text-right"><b>{{money_format('%!i',$amount1)}}</b></th>
                          @endforeach
                        <th class="text-right"><b>{{money_format('%!i',$total_amount1)}}</b></th>
                      </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection