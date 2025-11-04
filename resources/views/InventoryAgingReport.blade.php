@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right
    {
        text-align: right;
    }
    .b
    {
         font-weight: bolder;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.3.0/css/scroller.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">  
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Inventory Aging Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Fabric Inventory Aging Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>  
</div>
<div class="col-md-12">
    <div class="row">
            <div class="col-md-4 form-group mb-2">
                <label for="date" class="sr-only">Date</label>
                <input type="date" class="form-control" id="current_date" name="date" value="{{ date('Y-m-d') }}">
            </div> 
            <div class="col-md-4 form-group mx-sm-3 mb-2">
                <button type="button" onclick="LoadFabricInventory();" class="btn btn-primary mb-2">Search</button>
            </div> 
    </div> 
</div>  
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="">
               <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
                  <thead style="background: #244278;color: #fff;">
                     <tr>
                        <th style="text-align: center;">Sr No.</th> 
                        <th style="text-align: center;">Item Code</th>
                        <th style="text-align: center;">Item Name</th>
                        <th style="text-align: center;">Fabric Image</th>
                        <th style="text-align: center;" colspan="2">1-30 days</th> 
                        <th style="text-align: center;" colspan="2">31-60 days</th>  
                        <th style="text-align: center;" colspan="2">61-90 days</th>  
                        <th style="text-align: center;" colspan="2">91-180 days</th>  
                        <th style="text-align: center;" colspan="2">181-1 year</th>
                        <th style="text-align: center;" colspan="2">Above 1 year</th>  
                        <th style="text-align: center;" colspan="2">Total</th>  
                     </tr>
                     <tr> 
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th> 
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                     </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr> 
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;"></th>
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th> 
                        <th style="text-align: center;">Qty</th> 
                        <th style="text-align: center;">Amount</th>  
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
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/scroller/2.3.0/js/dataTables.scroller.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>
<script>
    LoadFabricInventory();
    
    function LoadFabricInventory() 
    {
        var URL = "loadInventoryAgingReport";
        var current_date = $("#current_date").val();
        $.ajax({
            dataType: "json",
            type: "GET",
            data: { "current_date": current_date },
            url: URL,
            success: function(data) {
                var myArray = JSON.parse(data.html);
    
                // Check if DataTable is already initialized and destroy it
                if ($.fn.DataTable.isDataTable('#dt')) {
                    $('#dt').DataTable().clear().destroy();
                }
    
                $('#dt').DataTable({
                    "dom": 'lBfrtip',
                    "pageLength": 10,
                    "buttons": ['csv', 'print', 'excel', 'pdf'],
                    data: myArray,
                    columns: [
                        { data: "srno", className:"text-right" }, 
                        { data: "item_code", className:"text-right" },
                        { data: "item_name" },
                        { data: "item_photo" },
                        { data: "total_stock30", className:"text-right" },
                        { data: "total_value30", className:"text-right" },
                        { data: "total_stock60", className:"text-right" },
                        { data: "total_value60", className:"text-right" },
                        { data: "total_stock90", className:"text-right" },
                        { data: "total_value90", className:"text-right" },
                        { data: "total_stock180", className:"text-right" },
                        { data: "total_value180", className:"text-right" },
                        { data: "total_stock365", className:"text-right" },
                        { data: "total_value365", className:"text-right" },
                        { data: "previousYearStock", className:"text-right" },
                        { data: "previousYearValue", className:"text-right" },
                        { data: "total_stock", className:"text-right b" },
                        { data: "total_value", className:"text-right b" },
                    ],
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api();
                        
                        // Update each column footer with the sum
                        api.columns().every(function(index) {
                            if (index >= 4) { // Apply condition to include columns starting from index 3
                                var column = this;
                                var sum = column.data().reduce(function(a, b) {
                                    // Remove commas from data values before parsing
                                    var num = parseFloat(b.toString().replace(/,/g, ''));
                                    return a + num;
                                }, 0);
                    
                                // Format sum as numeric string without currency symbol
                                var formattedSum = sum.toLocaleString('en-IN', {
                                    maximumFractionDigits: 2
                                });
                    
                                $(column.footer()).html(formattedSum);
                            }
                        });
                    }
                });
                
                $("#current_date").val(data.currentDate);
            }
        });
    }

     
</script>
@endsection