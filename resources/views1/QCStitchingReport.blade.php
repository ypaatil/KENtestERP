      
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
											    <th>QC No</th>
											    <th>Qc Date</th>
											    <th>Sales Order No</th>
											    <th>Work Order No</th>
											    <th>Vendor Name</th>
											    <th>Buyer Name</th>
												<th>Buyer Brand</th>
												<th>Main Style Category</th>
                                                <th>Style Name</th> 
                                                <th>Line No</th> 
                                                <th>Garment Color</th> 
                                                <th>Size</th> 
                                                <th>Pass Qty</th> 
                                                <th>Reject Qty</th> 
                                                <th>Total Qty</th> 
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
        //processing: true,
       // serverSide: true,
       // "pageLength": 10,
        ajax: "{{ route('QCStitchingReport') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columns: [
                {data: 'qcsti_code', name: 'qcsti_code'},
                {data: 'qcsti_date', name: 'qcsti_date'},
                {data: 'sales_order_no', name: 'sales_order_no'},
                {data: 'vw_code', name: 'vw_code'},
                {data: 'vendorName', name: 'vendorName'},
                {data: 'Ac_name', name: 'ledger_master.Ac_name'},
                {data: 'brand_name', name: "brand_master.brand_name"},
                {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'},
                {data: 'style_no', name: 'qcstitching_inhouse_size_detail2.style_no'},
                {data: 'line_name', name: 'line_master.line_name'},
                {data: 'color_name', name: 'color_master.color_name'},
                {data: 'size_name', name: 'size_detail.size_name'},
                {data: 'qty', name: 'qty'},
                {data: 'rejectQty', name: 'rejectQty'},
                {data: 'TotalQty', name: 'TotalQty'},
          
        ],
       columnDefs: [
           {
              targets: 13,
              autoWidth: true,
              searchable: false,
              orderable: false,
              render: function(data, type, row, info) 
              {  
                 return row.rejectQty;
              }   
            },
            {
              targets: 14,
              autoWidth: true,
              searchable: false,
              orderable: false,
              render: function(data, type, row, info) 
              { 
                 return parseFloat(row.qty) + (row.rejectQty);
              }   
            },
        ], 
    });
    
  });
</script>                                        
  
                        
                        @endsection