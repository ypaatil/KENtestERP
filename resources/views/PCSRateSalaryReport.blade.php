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
         <h4 class="mb-sm-0 font-size-18">Employee PCS-Wise Salary Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Employee PCS-Wise Salary Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/pcs_rate_salary_report" method="GET" enctype="multipart/form-data">
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Employee</label>
                                <select class="form-control select2" id="employeeCode" name="employeeCode">
                                    <option value="">--Select--</option>
                                     @foreach($empList as $emp) 
                                       <option value="{{$emp->employeeCode}}" {{ $emp->employeeCode == $employeeCode ? 'selected="selected"' : '' }}>({{$emp->employeeCode}}) {{$emp->fullName}}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-2 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                                <a href="/pcs_rate_salary_report" class="btn btn-danger w-md">Cancel</a>
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
             <button class="btn btn-info" onclick="GoToPrint();" style="margin-left: 20%;"> Print</button>
               <table  id="datatable-buttons" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr>
                        <th class="text-center">Sr No</th>
                        <th nowrap class="text-center">Employee Code</th>
                        <th nowrap class="text-center">Employee Name</th> 
                        <th nowrap class="text-center">Quantity</th>  
                        <th nowrap class="text-center">Amount</th> 
                        <th nowrap class="text-center">Days</th> 
                        <th nowrap class="text-center">Avg</th> 
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
                            $total_qty=0;
                        @endphp
                        @foreach($data as $emp)     
                        
                         @php
                        
                                    $rateMap = [
                        115 => 'rate',   
                        110 => 'rate3',  
                        628 => 'rate4',  
                        686 => 'rate5',  
                        113 => 'rate6'  
                        ];
                        
                        
                        $rateKey = $emp->vendorId;
                        
                     
                        $rateProperty = $rateMap[$rateKey] ?? 'rate'; 
                        $rate = isset($emp->{$rateProperty}) ? $emp->{$rateProperty} : $emp->rate;  
                        
                        @endphp
                       
                       
                        <tr>
                            <td class="text-center">{{$srno++}}</td>
                            <td class="text-center">{{$emp->employeeCode}}</td>
                            <td>{{$emp->fullName}}</td> 
                             <td class="text-right">{{ $emp->stiching_qty }}</td> 
                            <td class="text-right">{{number_format(($emp->stiching_qty * $rate), 2, '.', ',')}}</td> 
                            <td class="text-right">{{ $emp->present_days }}</td> 
                            <td class="text-right">{{number_format((($emp->stiching_qty * $rate) / ($emp->present_days)), 2, '.', ',')}}</td> 
                        </tr>
                        @php
                          
                            $total_amount1 += ($emp->stiching_qty * $rate);
                             $total_qty+= ($emp->stiching_qty); 
                        @endphp
                        @endforeach
                  </tbody> 
                  <tfoot>
                      <tr>
                          <th colspan="3" class="text-right">Total : </th>
                          <th class="text-right"><a href="#" target="_blank">{{number_format( $total_qty, 2, '.', ',')}}</a></th>   
                          <th class="text-right"><a href="#" target="_blank">{{number_format( $total_amount1, 2, '.', ',')}}</a></th>
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
<script>
   
    function GoToPrint()
    {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var employeeCode = $('#employeeCode').val();
        var vendorId = $('#vendorId').val();
        window.location.href = "https://kenerp.com/pcs_rate_salary_report_print?fromDate="+fromDate+"&toDate="+toDate+"&employeeCode="+employeeCode+"&vendorId="+vendorId;
    }
</script>
@endsection