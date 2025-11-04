@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Trims Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Trims Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
<div class="col-md-3">
   <div class="card mini-stats-wid" style="background-color:#152d9f;" >
      <div class="card-body">
         <div class="d-flex">
            <div class="flex-grow-1">
               <p class="  fw-medium" style="color:#fff;">Total Amount</p>
               <h4 class="mb-0" style="color:#fff;" id="head_total_value">-</h4>
            </div>
            <div class="flex-shrink-0 align-self-center">
               <div class="avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                  <span class="avatar-title" style="background-color:#152d9f;">
                  <i class="bx bx-copy-alt font-size-24"></i>
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="col-md-12">
            <div class="card-title" style="text-align: end;margin: 20px;"><button class="btn btn-warning" onclick="DumpData();"><b>Refresh</b></button></div>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table id="tbl" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center;">
                        <th>Supplier Name</th>
                        <th>Buyer Name</th>
                        <th>PO Status</th>
                        <th>PO No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Stock Qty</th>
                        <th>rate</th>
                        <th>Value</th>
                        <th>Width</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Rack Name</th>
                     </tr>
                  </thead>
                  <tbody> 
                        <tr><th colspan="12" class="text-center">Please wait data is loading....</th></tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
        
    
    function DumpData()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('trimStocks') }}",
            success: function(data)
            {
                table1();
            }
        });
    }
    
    function table1()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('loadDumpTrimStockData') }}",
            success: function(data)
            {
                $('#head_total_value').html(data.overall);
                $('tbody').html(data.html1); 
                
                    var table = $('#tbl').DataTable({
                	"dom": "Bfrtip", 
                	"serverSide": false,
                	"bDestroy":true,
                	"bPaginate": false,
                	"extend": "excelHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "pdfHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "csvHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     },
                	"extend": "copyHtml5",
                     "exportOptions": {
                         "modifier" : {
                            "order" : "index", 
                            "page" : "all", 
                            "search" : "applied"
                         }
                     }
                });
                
            }
        });
    }
    
  $(function () 
  {
  	 table1();
  });
 
</script>
@endsection