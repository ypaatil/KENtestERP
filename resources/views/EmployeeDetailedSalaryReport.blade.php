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
         <h4 class="mb-sm-0 font-size-18">Employee Detailed Salary Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Employee Detailed Salary Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/EmployeeDetailedSalaryReport" method="GET" enctype="multipart/form-data">
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
                                       <option value="{{$fetch_unit->ac_code }}"
                                       
                               {{ $fetch_unit->ac_code == $vendorIdrequest ? 'selected="selected"' : '' }}        
                                       
                                       >{{$fetch_unit->ac_name}}</option>      
                                     @endforeach
                                </select>
                            </div>
                        </div>    
                       
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="sales_order_no" class="form-label">Order No.</label>
                                <select class="form-control select2" id="sales_order_no" name="sales_order_no">
                                    <option value="">--Select--</option>
                                     @foreach($salesOrderList as $sales) 
                                       <option value="{{$sales->tr_code}}" {{ $sales->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$sales->tr_code}}</option>      
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
                                <a href="/EmployeeDetailedSalaryReport" class="btn btn-danger w-md">Cancel</a>
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
                  <div class="row"><div class="col-md-8">{{ $data->appends(request()->query())->links() }}</div><div class="col-md-2">    <button class="btn btn-info" onclick="GoToPrint();" style="margin-left: 20%;"> Print</button> </div> <div class="col-md-2">  <a id="exportButton" class="btn btn-success w-md">Export Data  <span id="spinner" style="display:none;">
        <i class="fa fa-spinner fa-spin"></i> 
    </span></a></div></div>   
                 
          
               <table  id="datatable-buttons1" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr>
                        <th class="text-center">Sr No</th>
                        <th class="text-center">ID</th> 
                        <th nowrap class="text-center">Date</th>
                         <th nowrap class="text-center">Unit</th> 
                         <th nowrap class="text-center">Style</th> 
                        <th nowrap class="text-center">Order No.</th>
                        <th nowrap class="text-center">Employee Code</th>
                        <th nowrap class="text-center">Employee Name</th> 
                        <th nowrap class="text-center">Operation Name</th> 
                        <th nowrap class="text-center">Qty</th> 
                        <th nowrap class="text-center">Rate</th> 
                        <th class="text-center">Amount</th> 
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
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
                              <td class="text-left">{{$emp->dailyProductionEntryId}}</td>   
                            <td nowrap>{{date("d-m-Y", strtotime($emp->dailyProductionEntryDate))}}</td>
                             <td class="text-left">{{$emp->ac_name}}</td>   
                            <td class="text-left">{{$emp->mainstyle_name}}</td>  
                            <td class="text-center">{{$emp->sales_order_no}}</td>
                            <td class="text-center">{{$emp->employeeCode}}</td>
                            <td>{{$emp->fullName}}</td> 
                            <td>{{$emp->operation_name}}</td> 
                            <td class="text-right"><a href="/EmployeeDetailedProductionReport?fromDate={{$emp->dailyProductionEntryDate}}&toDate={{$emp->dailyProductionEntryDate}}&employeeCode={{$emp->employeeCode}}&operationNameId={{$emp->operationNameId}}&sales_order_no={{$emp->sales_order_no}}" target="_blank">{{ number_format($emp->stiching_qty, 0, '.', ',')}}</a></td> 
                            
                             <td nowrap class="text-right">{{ $rate }}</td> 
                            
                            <td class="text-right"><a href="/EmployeeDetailedProductionReport?fromDate={{$emp->dailyProductionEntryDate}}&toDate={{$emp->dailyProductionEntryDate}}&employeeCode={{$emp->employeeCode}}&operationNameId={{$emp->operationNameId}}&sales_order_no={{$emp->sales_order_no}}" target="_blank">{{number_format(($emp->stiching_qty * $rate), 2, '.', ',')}}</a></td> 
                        </tr>
                        @php
                            $total_qty1 += $emp->stiching_qty;
                            $total_amount1 += ($emp->stiching_qty * $rate);
                        @endphp
                        @endforeach
                  </tbody> 
                  <tfoot>
                      <tr>
                          <th colspan="9" class="text-right">Total : </th>
                          <th class="text-right"><a href="/EmployeeDetailedProductionReport?fromDate={{$fromDate}}&toDate={{$toDate}}&employeeCode={{$emp->employeeCode}}&sales_order_no={{ isset($emp->sales_order_no) ? $emp->sales_order_no : ""}}" target="_blank">{{number_format($total_qty1, 0, '.', ',')}}</a></th>
                          <th class="text-right"></th>
                          <th class="text-right"><a href="/EmployeeDetailedProductionReport?fromDate={{$fromDate}}&toDate={{$toDate}}&employeeCode={{$emp->employeeCode}}&sales_order_no={{ isset($emp->sales_order_no) ? $emp->sales_order_no : ""}}" target="_blank">{{number_format( $total_amount1, 2, '.', ',')}}</a></th>
                      </tr>
                  </tfoot>
               </table>
                {{ $data->appends(request()->query())->links() }}
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
        var sales_order_no = $('#sales_order_no').val();
         var vendorId = $('#vendorId').val();  
        
        
        window.location.href = "https://kenerp.com/EmployeeDetailedSalaryReportPrint?fromDate="+fromDate+"&toDate="+toDate+"&employeeCode="+employeeCode+"&sales_order_no="+sales_order_no+"&vendorId="+vendorId;
    }
    
    
    
    $(document).ready(function() {
    $('#exportButton').click(function(event) {
        event.preventDefault();
 $('#exportButton').prop('disabled', true);
       
       
       const urlParams = new URLSearchParams(window.location.search);

        $('#spinner').show();

        // Get values from input fields and set null for empty values
        var fromDate = document.getElementById('fromDate').value.trim() || null;
        var toDate = document.getElementById('toDate').value.trim() || null;
        var sales_order_no = urlParams.get('sales_order_no') || null;
         var vendorId = document.getElementById('vendorId').value.trim() || null;
        var employeeCode = document.getElementById('employeeCode').value.trim() || null;

        // Build the URL dynamically
        var url = '/EmployeeDetailedSalaryReportExport/' + encodeURIComponent(fromDate) + '/' + 
                  encodeURIComponent(toDate) + '/' + encodeURIComponent(sales_order_no) + '/' + encodeURIComponent(vendorId) + '/' + encodeURIComponent(employeeCode);

        // Perform the AJAX request
        $.ajax({
            url: url,
            type: 'GET',
             xhrFields: {
                responseType: 'blob'  
            }, 
            success: function(response, status, xhr) {
                   if (response instanceof Blob) {
                    var blob = response;
                    var filename = "Employee_Detailed_Salary_Report.xlsx";  // Set your desired filename here
                    
                    // Check the content-disposition header (if available) to get the filename
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var matches = /filename="([^"]*)"/.exec(disposition);
                        if (matches != null && matches[1]) filename = matches[1];
                    }

                    // Create a link element
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob); 
                    link.download = filename;  
                    
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    console.error('The response is not a valid Blob:', response);
                }

                $('#exportButton').prop('disabled', false);
                $('#spinner').hide();
            },
            error: function(xhr, status, error) {
              
                console.log('Request failed:', error);
               
            },
            complete: function() {
              $('#exportButton').prop('disabled', false);
                $('#spinner').hide();  
            }
        });
    });
});
    
    
    
    
    
</script>
@endsection