      
   @extends('layouts.master') 

@section('content')   
<!-- end page title -->
<style>
    #lblSync
    {
        background: #a8e94269;
        padding: 10px;
        font-weight: 900;
        background-position: left top;
        padding-top:95px;
        margin-bottom:60px;
        -webkit-animation-duration: 10s;animation-duration: 10s;
        -webkit-animation-fill-mode: both;animation-fill-mode: both;
    }
    .hide
    {
        display:none;
    }
</style>
  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-2"><button onclick="syncData()" id="sync" class="btn btn-warning">Synchronization</button></div>
                <div class="alert alert-success hide">
                    Data Synchronization Completed
                </div>
                <div class="table-responsive">
                    <table id="tbl" class="table table-bordered   nowrap w-100">
                      <thead>
                        <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                            <th colspan="9"></th>
                            <th style="text-align: right;">Total : </th>
                            <th id="head_packing_grn_qty"></th>
                            <th id="head_carton_packing_qty"></th>
                            <th id="head_transfered_qty"></th>
                            <th id="head_fg_stock"></th>
                            <th></th>
                            <th id="head_value"></th>
                        </tr>
                        <tr style="text-align:center; white-space:nowrap">
						    <th>Date</th>
						    <th>Buyer Name</th>
						    <th>Sales Order No</th>
						    <th>SAM</th>
						    <th>PO Status</th>
						    <th>Brand Name</th>
							<th>Main Style Category</th>
                            <th>Style Name</th> 
                            <th>Garment Color</th> 
                            <th>Size</th> 
                            <th>Packing GRN Qty</th> 
                            <th>Carton Paking Qty</th>
                            <th>Transfered Qty</th>
                            <th>FG Stock</th>
                            <th>FOB Rate</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                       <tbody>
                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>  
</div>  
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script type="text/javascript">

   function tableData() 
   {
         var currentURL = window.location.href; 
         
      	 $('#tbl').DataTable().clear().destroy();
        
          var table = $('#tbl').DataTable({
            ajax: currentURL,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
             "footerCallback": function (row, data, start, end, display) {                
                 var totalpacking_qty = 0;
                 var totalcarton_pack_qty = 0;
                 var totaltransfer_qty = 0;
                 var totalstock = 0;
                 var totalValue = 0;
                    
                for (var i = 0; i < data.length; i++) {
                    totalpacking_qty += parseFloat(data[i].packing_qty);
                    totalcarton_pack_qty += parseFloat(data[i].carton_pack_qty);
                    totaltransfer_qty += parseFloat(data[i].transfer_qty);
                    totalstock += parseFloat(data[i].stock);
                    totalValue += parseFloat(data[i].Value);
                }
                
                $('#head_packing_grn_qty').html(totalpacking_qty.toLocaleString('en-IN'));
                $('#head_carton_packing_qty').html(totalcarton_pack_qty.toLocaleString('en-IN'));
                $('#head_transfered_qty').html(totaltransfer_qty.toLocaleString('en-IN'));
                $('#head_fg_stock').html(totalstock.toLocaleString('en-IN'));
                $('#head_value').html(totalValue.toLocaleString('en-IN'));
              },
              columns: [
                  {data: 'entry_date', name: 'entry_date'},
                  {data: 'ac_name', name: 'ac_name'},
                  {data: 'sales_order_no', name: 'sales_order_no'},
                  {data: 'sam', name: 'sam'},
                  {data: 'job_status_name', name: 'job_status_name'},
                  {data: 'brand_name', name: "brand_name"},
                  {data: 'mainstyle_name', name: 'mainstyle_name'},
                  {data: 'style_no', name: 'style_no'},
                  {data: 'color_name', name: 'color_name'},
                  {data: 'size_name', name: 'size_name'},
                  {data: 'packing_qty', name: 'packing_qty'},
                  {data: 'carton_pack_qty', name: 'carton_pack_qty'},
                  {data: 'transfer_qty', name: 'transfer_qty'},
                  {data: 'stock', name: 'stock'},
                  {data: 'fob_rate', name: 'fob_rate'},
                  {data: 'Value', name: 'Value'},
            ]
        });
    }
    
    
    $( document ).ready(function() 
    { 
        tableData();
       
    });
    
    var xhr;
    function syncData()
    {
         xhr = $.ajax({
            dataType: "json",
            url: "{{ route('DumpFGStockReport1') }}",
            beforeSend: function() 
            {
                $("#sync").attr('disabled','disabled');
            },
            complete: function(data)
            {
                $("#sync").removeAttr('disabled');
                setTimeout(function() 
                { 
                    $(".alert-success").addClass('hide'); 
                    
                }, 2500);
            },
            success: function(data)
            {
                tableData();
                $(".alert-success").removeClass('hide'); 
                    
            },
            error: function (error) 
            {
            }
        });
    }
   
    function abort()
    {
        console.log("abort");
        xhr.abort();
    }
  
</script>                                        
@endsection