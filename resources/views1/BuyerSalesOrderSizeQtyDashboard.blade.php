      
   @extends('layouts.master') 

@section('content')   
 
                         
                        @if(session()->has('message'))
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        </div>
                        @endif

                        @if(session()->has('messagedelete'))
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                {{ session()->get('messagedelete') }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                    <table id="tbl" class="table table-bordered dt-responsive nowrap w-100 ">
                                          <thead>
                                            <tr style="text-align:center;">
                                                <th>Buyer Name</th>
                                                <th>Sales Order No</th>
                                                <th>Order Date </th>
                                                <th>PO Status</th>
                                                <th>Brand Name</th>
                                                <th>Main Style Name</th>
                                                <th>Style Name</th>
                                                <th>Garment Color</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Order Qty</th>
                                               
                                             </tr>
                                            </thead>
        
                                            <tbody>

                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(function () {

  	 $('#tbl').DataTable().clear().destroy();
    
       var table = $('#tbl').DataTable({
        //processing: true,
       // serverSide: true,
       // "pageLength": 10,
        ajax: "{{ route('BuyerSalesOrderSizeQtyDashboard') }}",
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
         
        
        
        
        columns: [
          {data: 'Ac_name', name: 'ledger_master.Ac_name'},
          {data: 'tr_code', name: 'buyer_purchse_order_master.tr_code'},
          {data: 'tr_date', name: 'buyer_purchse_order_master.tr_date'},
          {data: 'job_status_name', name: 'job_status_master.job_status_name'},
           {data: 'brand_name', name: 'brand_master.brand_name'},
          {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'},
          {data: 'fg_name', name: 'fg_master.fg_name'},
           {data: 'item_name', name: 'item_master.item_name'},
          {data: 'color_name', name: "color_master.color_name"},
          {data: 'size_name', name: 'size_detail.size_name'},
           {data: 'size_qty', name: 'buyer_purchase_order_size_detail.size_qty'},
           
            
        ]
    });
    
  });
</script> 
                        
                        @endsection