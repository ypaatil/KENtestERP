@extends('layouts.master') 
@section('content')  
@php

ini_set('memory_limit', '10G');
setlocale(LC_MONETARY, 'en_IN');  
@endphp  
<!-- end page title -->


<style>
   tfoot {
        display: table-header-group;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Packing GRN Summary</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Packing GRN Summary</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row"> 
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/PackingGRNReport" method="GET">
                  <div class="row">
                      <div class="col-md-2">
                            <label><b>From Date</b></label>
                            <input type="date" name="fromDate" id="fromDate"  value="{{ $fromDate }}" class="form-control"> 
                      </div>
                      <div class="col-md-2">
                            <label><b>To Date</b></label>
                            <input type="date" name="toDate" id="toDate"  value="{{ $toDate }}" class="form-control"> 
                      </div>
                      <div class="col-md-4 mt-4"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/PackingGRNReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div> 
   <div class="col-12">
      <div class="card">
         <div class="card-body table-responsive ">
            <table id="dt" class="table w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>GRN No</th>
                     <th>Date</th>
                     <th>Packing PO No</th>
                     <th>Status</th>
                     <th>Style Category</th>
                     <th>Sales Order No</th>
                     <th>SAM</th>
                     <th>Buyer Name</th>
                     <th>Buyer Brand</th>
                     <th>Sub Style</th>
                     <th>Item Name</th>
                     <th>Vendor Name</th>
                     <th>Style No</th>
                     <th>Garment Color</th>
                     <th>Size</th>
                     <th>Quality Rate</th>
                     <th>Packing Rate</th>
                     <th>Kaj Button Rate</th>
                     <th>Total GRN</th>
                     <th>Total Min</th>
                     <th>FOB Rate</th>
                     <th>Total Value</th>
                  </tr>
               </thead>
               <tbody>
                          
               </tbody>
               
                <tfoot style="background-color:#d7ed92; font-weight:bold;">
                  <td colspan="17"></td>
                  <td class="text-right">Total</td>
                  <td class="text-right" id="head_packing_grn_qty"></td>
                  <td class="text-right"></td>
                  <td class="text-right"></td>
                  <td class="text-right"></td>
                 </tfoot>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

    $('tfoot').each(function () {
        $(this).insertBefore($(this).siblings('thead'));
    });
//   function LoadPackingGRNReport() 
//   {
//          var currentURL = window.location.href; 
//          var URL = "LoadFabricStockDataTrialCloned1?currentDate="+currentDate+"&job_status_id="+job_status_id;   
//       	 $('#dt').DataTable().clear().destroy();
        
//           var table = $('#dt').DataTable({
//             ajax: currentURL,
//             // pageLength: 10,
//             processing: false,
//             serverSide: false,
//             dom: 'lBfrtip',
//             buttons: [
//                 { extend: 'copyHtml5', footer: true },
//                 {  
//                     extend: 'excel', 
//                     exportOptions: {
//                      modifier : {
//                          order : 'index',  
//                          page : 'all', 
//                          search : 'none'  
//                      },
//                      columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21 ]
//                  }
//                 },
//                 { extend: 'csvHtml5', footer: true },
//                 { extend: 'pdfHtml5', footer: true }
//             ],
//             "footerCallback": function (row, data, start, end, display) {    
//                 console.log(data);
//                  var total_size_qty = 0;
                    
//                 for (var i = 0; i < data.length; i++) {
//                     total_size_qty += parseFloat(data[i].size_qty);
//                 }
                
//                 $('#head_packing_grn_qty').html(total_size_qty);
//               },
//             columns: [
//                   {data: 'pki_code'},
//                   {data: 'pki_date'},
//                   {data: 'vpo_code',class: 'text-center'},
//                   {data: 'job_status_name',class: 'text-center'},
//                   {data: 'mainstyle_name'},
//                   {data: 'sales_order_no'},
//                   {data: 'sam'},
//                   {data: 'buyer_name'},
//                   {data: 'brand_name'},
//                   {data: 'sub_style_name'},
//                   {data: 'item_name'},
//                   {data: 'vendor_name'},
//                   {data: 'fg_name'},
//                   {data: 'color_name'},
//                   {data: 'size_name'},
//                   {data: 'quality_rate'},
//                   {data: 'packing_rate'},
//                   {data: 'kaj_button_rate'},
//                   {data: 'size_qty', class: 'text-center'},
//                   {data: 'total_min',class: 'text-center'},
//                   {data: 'fob_rate',class: 'text-center'},
//                   {data: 'total_value',class: 'text-center'}
//             ]
//         });
         
//     }
    
    
    function LoadPackingGRNReport()
    {       
      
        $('#dt').DataTable().clear().destroy();  
        
        var fromDate = $("#fromDate").val();  
        var toDate = $("#toDate").val();
                
        var URL = "LoadPackingGRNReport?fromDate="+fromDate+"&toDate="+toDate;    
        $.ajax({
            dataType: "json",
            type: "GET", 
            url: URL,
            success: function(data)
            {
                
                 var myArray = JSON.parse(data.html); 
                 $('#dt').DataTable({
                    "dom": 'lBfrtip', // 'f' added for the search box
                    "pageLength": 10,
                    "buttons": ['csv', 'print', 'excel', 'pdf'],
                     data: myArray, 
                    "footerCallback": function (row, data, start, end, display) {    
                        console.log(data);
                         var total_size_qty = 0;
                            
                        for (var i = 0; i < data.length; i++) {
                            total_size_qty += parseFloat(data[i].size_qty);
                        }
                        
                        $('#head_packing_grn_qty').html(total_size_qty);
                    },
                    columns: [
                      {data: 'pki_code'},
                      {data: 'pki_date'},
                      {data: 'vpo_code',class: 'text-center'},
                      {data: 'job_status_name',class: 'text-center'},
                      {data: 'mainstyle_name'},
                      {data: 'sales_order_no'},
                      {data: 'sam'},
                      {data: 'buyer_name'},
                      {data: 'brand_name'},
                      {data: 'sub_style_name'},
                      {data: 'item_name'},
                      {data: 'vendor_name'},
                      {data: 'fg_name'},
                      {data: 'color_name'},
                      {data: 'size_name'},
                      {data: 'quality_rate'},
                      {data: 'packing_rate'},
                      {data: 'kaj_button_rate'},
                      {data: 'size_qty', class: 'text-center'},
                      {data: 'total_min',class: 'text-center'},
                      {data: 'fob_rate',class: 'text-center'},
                      {data: 'total_value',class: 'text-center'}
                    ]
                });
        
                $('#total_Stock_qty').html(data.total_stock);
                $('#all_value').html(data.total_value);
            }
        });
    } 
    
    
    
    $( document ).ready(function() 
    { 
        LoadPackingGRNReport();
       
    });
    
    
    

   
</script>
@endsection