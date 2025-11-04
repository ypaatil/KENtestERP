      
   @extends('layouts.master') 

@section('content')   
<!-- end page title -->

  <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tbl" class="table table-bordered   nowrap w-100">
                      <thead>
                        <tr style="text-align:center; white-space:nowrap">
						    <th>Buyer Name</th>
						    <th>Sales Order No</th>
						    <th>PO Status</th>
							<th>Buyer Brand</th>
							<th>Main Style Category</th>
                            <th>Style Name</th> 
                            <th>Garment Color</th> 
                             <th>Garment Image</th> 
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

  $(function () {

  	 $('#tbl').DataTable().clear().destroy();
    
       var table = $('#tbl').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 10,
        ajax: "{{ route('FGStockReport') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
          {data: 'Ac_name', name: 'ledger_master.Ac_name'},
          {data: 'sales_order_no', name: 'sales_order_no'},
          {data: 'job_status_name', name: 'job_status_master.job_status_name'},
          {data: 'brand_name', name: "brand_master.brand_name"},
          {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'},
          {data: 'style_no', name: 'packing_inhouse_size_detail2.style_no'},
          {data: 'color_name', name: 'color_master.color_name'},
          {data: 'imagePath', name: '0'},
          {data: 'size_name', name: 'size_detail.size_name'},
          {data: 'packing_grn_qty', name: 'packing_inhouse_size_detail2.packing_grn_qty'},
          {data: 'carton_pack_qty', name: 'carton_pack_qty'},
          {data: 'transfer_qty', name: 'transfer_qty'},
          {data: 'Carton_Paking_Qty', name: '0'},
          {data: 'order_rate', name: 'order_rate'},
          {data: 'Value', name: '0'},
          
        ]
    });
    
  });
</script>                                        
@endsection