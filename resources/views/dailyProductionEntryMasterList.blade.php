@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
</style>
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Daily Production Entry List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
               <li class="breadcrumb-item active">Daily Production Entry List</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if($chekform->write_access==1)
<div class="row">
   <div class="col-md-6">
      <a href="{{ Route('DailyProductionEntry.create') }}"><button type="buuton" class="btn btn-primary w-md">Add New Record</button></a>
   </div>
</div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/DailyProductionEntry" method="GET" enctype="multipart/form-data">
                    <div class="row"> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="fromDate" class="form-label">From date</label>
                                <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($_GET['fromDate']) ? $_GET['fromDate'] : date('Y-m-01')}}" required> 
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="toDate" class="form-label">To Date</label>
                                <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($_GET['toDate']) ? $_GET['toDate'] : date('Y-m-d')}}" required>
                            </div>
                        </div> 
                        
                                 <div class="col-md-2">
                            <div class="mb-3">
                                <label for="toDate" class="form-label">ID</label>
                                <input type="text" name="dailyProductionEntryId" class="form-control" id="dailyProductionEntryId" value="{{ $dailyProductionEntryId }}">
                            </div>
                        </div> 
                        
                                <div class="col-md-4">
                            <div class="mb-3">
                                <label for="employeeCode" class="form-label">Employee</label>
                                <select class="form-control select2" id="employeeCode" name="employeeCode">
                                     @php  $chunkSize = 200; @endphp
                                    <option value="">--Select--</option>
                                     @foreach (array_chunk($employeeMap, $chunkSize) as $chunk) 
                                     @foreach ($chunk as $row) 
                                    <option value="{{ $row['employeeCode'] }}" {{ $row['employeeCode']  == $employeeCode ? 'selected="selected"' : '' }} >({{ $row['employeeCode'] }}) {{ $row['employeeName'] }}</option>
                                    @endforeach
                                    @endforeach
                                     
                                </select>
                            </div>
                        </div>     
                  
                        <div class="col-md-4 mt-2">
                            <label for="formrow-inputState" class="form-label"></label>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                                <a href="/DailyProductionEntry" class="btn btn-danger w-md">Cancel</a>
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
                  <div class="row"><div class="col-md-8">{{ $DailyProductionEntryList->appends(request()->query())->links() }}</div> <div class="col-md-4">  <a id="exportButton" class="btn btn-success w-md">Export Data  <span id="spinner" style="display:none;">
        <i class="fa fa-spinner fa-spin"></i> 
    </span></a></div></div> 
            <table id="datatable-buttons1" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr.No.</th>
                     <th>ID</th>     
                     <th>Date</th>
                     <th>Employee Code</th>  
                     <th>Employee</th>  
                     <th># KDPL</th>  
                     <th>Colors</th>  
                      <th>Operations</th>     
                     <th class="text-center">Total Stiching Qty</th>  
                     <th>Username</th>
                     <th>Edit</th>
                     <th>Delete</th>
                  </tr>
               </thead>
               <tbody>
                  @php
                    $srno = 1;
                  @endphp
                  @foreach($DailyProductionEntryList as $row)    
                  @php
                  
  
                
                        
                  @endphp
                  <tr>
                     <td>{{ $srno++ }}</td>
                    <td>{{ $row->dailyProductionEntryId }}</td>  
                     <td>{{ date('d-m-Y', strtotime($row->dailyProductionEntryDate)) }}</td>
                     <td>{{ $row->employeeCode }}</td> 
                     <td>{{ $row->fullName }}</td> 
                     <td>{{ rtrim($row->salesOrders,",") }}</td> 
                     <td>{{ rtrim($row->colors,",") }}</td> 
                       <td>{{ rtrim($row->operation_name,",")  }}</td>   
                     <td class="text-right">{{  number_format($row->total_stiching_qty, 2, '.', ',') }}</td> 
                     <td>{{ $row->username }}</td>
                     @if($chekform->edit_access==1)
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="{{route('DailyProductionEntry.edit', $row->dailyProductionEntryId)}}" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                        </a>
                     </td>
                     @else
                     <td>
                        <a class="btn btn-outline-secondary btn-sm edit" href="" title="Edit">
                        <i class="fas fa-lock"></i>
                        </a>
                     </td>
                     @endif
                     @if($chekform->delete_access==1) 
                     <td>
                        <button  class="btn btn-outline-secondary btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="{{ csrf_token() }}" data-id="{{ $row->dailyProductionEntryId }}"  data-route="{{route('DailyProductionEntry.destroy', $row->dailyProductionEntryId )}}" title="Delete">
                        <i class="fas fa-trash"></i>
                        </button>         
                     </td>
                     @else
                     <td>
                        <button class="btn btn-outline-secondary btn-sm delete" data-toggle="tooltip" data-placement="top" title="Delete">
                        <i class="fas fa-lock"></i>
                        </button>
                     </td>
                     @endif
                  </tr>
                  @endforeach
               </tbody>
            </table>
             {{ $DailyProductionEntryList->appends(request()->query())->links() }}
             
              </div>
             
             
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 
   $(document).on('click','#DeleteRecord',function(e) {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
     //alert(Route);
    
    //alert(data);
      if (confirm("Are you sure you want to Delete this Record?") == true) {
    $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "_method": 'DELETE',
             "_token": token,
             },
           
           success: function(data){
   
              //alert(data);
           location.reload();
   
           }
   });
   }
   
   });
   
   
 
   
   
$(document).ready(function() {
    $('#exportButton').click(function(event) {
        event.preventDefault();
 $('#exportButton').prop('disabled', true);
       
         const urlParams = new URLSearchParams(window.location.search);
        $('#spinner').show();

        // Get values from input fields and set null for empty values
        var fromDate = document.getElementById('fromDate').value.trim() || null;
        var toDate = document.getElementById('toDate').value.trim() || null;
        var employeeCode = document.getElementById('employeeCode').value.trim() || null;  
    
        var  dailyProductionEntryId = document.getElementById('dailyProductionEntryId').value.trim() || null;  

        // Build the URL dynamically
        var url = '/employee_detailed_production_export/' + encodeURIComponent(fromDate) + '/' + 
                  encodeURIComponent(toDate) + '/' + 
                  encodeURIComponent(employeeCode) + '/' + 
                  encodeURIComponent(dailyProductionEntryId);

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
                    var filename = "Daily_Production_Entry.xlsx";  // Set your desired filename here
                    
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