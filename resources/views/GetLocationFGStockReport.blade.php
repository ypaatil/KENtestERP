@extends('layouts.master') 
@section('content')
<div class="row">
<div class="col-xl-12">
<div class="card">
<div class="card-body">
<h4 class="card-title mb-4">Location wise FG Stock Report</h4>
@if ($errors->any())
<div class="col-md-6">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    </div>
@endif
 
 
 
<div class="row">
<div class="col-md-3">
    <div class="mb-3">
        <label for="po_date" class="form-label">Sales Order no</label>
        <!--<input type="text" name="sales_order_no" class="form-control" id="sales_order_no" value="" required onfocusout="getSalesOrderDetails(this.value);">-->
       <select name="sales_order_no" class="form-control select2" id="sales_order_no" required   >
<option value="">--Sales Order No--</option>
@foreach($SalesOrderList as  $row)
{
    <option value="{{ $row->sales_order_no }}">{{ $row->sales_order_no }}</option>
}
@endforeach
</select>
    </div>
</div>
 
 <div class="col-md-4">
    <div class="mb-3">
        <label for="po_date" class="form-label">Location</label>
        
<select name="loc_id" class="form-control select2" id="loc_id" required   >
<option value="">--Location--</option>
@foreach($LocationList as  $row)
{
    <option value="{{ $row->loc_id }}">{{ $row->location }}</option>
}
@endforeach
</select>
    </div>
</div>


<div class="col-sm-3">
<label for="formrow-inputState" class="form-label"></label>
<div class="form-group">
<button type="button" class="btn btn-primary w-md" id="search">Search</button>
</div>
</div>

</div>
 
</div>
 
 
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
                            <th>O2O Qty</th>
                            <th>Location Transfer</th>
                            <th>Location Received Qty</th>
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
        

</div>
<!-- end card body -->
</div>
<!-- end card -->
</div>
<!-- end col -->


<!-- end col -->
</div>
<!-- end row -->

             
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- end row -->
 
 
 
 
 <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">

  $(function () {
      
      load_data();
      
      function load_data(sales_order_no,loc_id)
{  
      
      

  	 $('#tbl').DataTable().clear().destroy();
    
       var table = $('#tbl').DataTable({
        
        ajax: {
                  url:"{{ route('FGLocationStockReport') }}",
         		  data:  {sales_order_no:sales_order_no,loc_id:loc_id},
         		  dataSrc: 'data',
                  datatype: 'json',
               },
        
        
        
             dom: 'lBfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        
        columnDefs: [
              {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.packing_grn_qty;
                },
                "targets": 9
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.carton_pack_qty;
                },
                "targets": 10
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.transfer_qty;
                },
                "targets": 11
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.loc_transfer_qty;
                },
                "targets": 12
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.loc_rec_transfer_qty;
                },
                "targets": 13
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.StockQty;
                },
                "targets": 14
            },
            {
                "render": function ( data, type, row ) {
                    return row.arrayFiled.Value;
                },
                "targets": 16
            }
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
          {data: 'loc_transfer_qty', name: 'loc_transfer_qty'},
          {data: 'loc_rec_transfer_qty', name: 'loc_rec_transfer_qty'},
          {data: 'arrayFiled', name: "arrayFiled.StockQty"},
          {data: 'order_rate', name: 'order_rate'},
          {data: 'arrayFiled', name:  'arrayFiled.Value' },
          
        ]
    });
    
  
}



$('#search').click(function(e){

e.preventDefault();

var sales_order_no = $("#sales_order_no").val();
var loc_id = $("#loc_id").val();


// $('#AttendanceDatenew').val(AttendanceDate);

//alert(AttendanceDate);

//alert(h3);
if(sales_order_no!="" && loc_id!="")
{
load_data(sales_order_no,loc_id);
} else{
    
    load_data();
}



});


    
    
});
  
  
  
  
  
</script> 
 
 
 
 
 
 
 
<!-- end row -->
@endsection