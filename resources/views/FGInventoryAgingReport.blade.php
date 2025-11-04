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
 
    .hide{
        display:none;
    }
    
    .success-main-checkmark {
        position: relative; /* Ensure the button is a positioned parent */
    }
    
    .success-main-checkmark:after {
        content: '✔';
        position: absolute;
        top: 50%; /* Move the top of the pseudo-element to the middle of the button */
        left: 90%; /* Move the left of the pseudo-element to the middle of the button */
        transform: translate(-50%, -50%); /* Center the pseudo-element */
        width: 27px;
        height: 26px;
        text-align: center;
        border: 1px solid #aaa;
        background: #0b0b0b;
        border-radius: 50%;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, .3);
    }

  
</style> 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/scroller/2.3.0/css/scroller.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">  
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">FG Inventory Aging Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">FG Inventory Aging Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>  
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-12 hide text-center"  id="waiting1"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>
            <div class="tbl hide table-responsive">
               <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
                  <thead style="background: #244278;color: #fff;">
                     <tr>
                        <th style="text-align: center;">Sr No.</th> 
                        <th style="text-align: right;">KDPL NO.</th>
                        <th style="text-align: right;">Buyer Name</th>
                        <th style="text-align: center;">Style</th>
                        <th style="text-align: center;">Garment Color</th>
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
    LoadFGInventoryAgent();
    function LoadFGInventoryAgent() {
        var URL = "loadFGInventoryAgingReport";
        $.ajax({
            dataType: "json",
            type: "GET",
            url: URL,
            beforeSend: function() 
            {
                $("#waiting1").removeClass("hide");
                $(".tbl").addClass("hide");
            },
            success: function(data) {
                var myArray = JSON.parse(data.html);
                $('#dt').DataTable({
                    "dom": 'lBfrtip',
                    "pageLength": 10,
                    "buttons": ['csv', 'print', 'excel', 'pdf'],
                    data: myArray,
                    columns: [
                        { data: "srno", className:"text-right" }, 
                        { data: "sales_order_no" },
                        { data: "buyer_name" },
                        { data: "mainstyle_name" },
                        { data: "color_name" },
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
                        { data: "previousYearstock", className:"text-right" },
                        { data: "previousYearValue", className:"text-right" },
                        { data: "total_stock", className:"text-right b" },
                        { data: "total_value", className:"text-right b" },
                    ],
                   "footerCallback": function(row, data, start, end, display) {
                        var api = this.api();
                        
                        // Update each column footer with the sum
                        api.columns().every(function(index) {
                            if (index >= 5) { // Apply condition to include columns starting from index 3
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
            }, 
            complete: function (data) 
            {
                  $("#waiting1").addClass("hide");
                  $(".tbl").removeClass("hide"); 
            
            }
        });
    }
     
</script>
@endsection