@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Stock Detail</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Stock Detail</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="dt" class="table table-bordered nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Item Code</th>
                        <th>Preview</th>
                        <th>Item Name</th>
                        <th>Quality Name</th>
                        <th>Color</th>
                        <th>Item Description</th>
                        <th>Stock Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
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

    function tableData() 
    {
         var currentURL = window.location.href; 
         
      	 $('#dt').DataTable().clear().destroy();
        
          var table = $('#dt').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            "footerCallback": function (row, data, start, end, display) {                
                //  var total_meter = 0;             
                //  var total_value = 0;
                    
                // for (var i = 0; i < data.length; i++) {
                //     total_meter += parseFloat(data[i].meter);
                //     total_value += parseFloat(data[i].item_value);
                // }
                
                // $('#head_total_outward_qty').html(total_meter.toFixed(2));
                // $('#head_total_value').html(total_value.toFixed(2));
                
              },
            columns: [
                  {data: 'item_code', name: 'item_code'},
                  {data: 'item_image_path', name: 'item_image_path'},
                  {data: 'item_name', name: 'item_name'},
                  {data: 'quality_name', name: 'quality_name'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'item_description', name: 'item_description'},
                  {data: 'stock', name: 'stock'},
                  {data: 'item_rate', name: 'item_rate'},
                  {data: 'item_value', name: 'item_value'}
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
</script>
@endsection