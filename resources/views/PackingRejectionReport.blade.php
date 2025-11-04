@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Packing Rejection Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Packing Rejection Report</li>
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
               <table id="tbl" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>QCP No</th>
                        <th>QCP Date</th>
                        <th>Sales Order No</th>
                        <th>Packing No</th>
                        <th>Vendor Name</th>
                        <th>Buyer Name</th>
                        <th>Buyer Brand</th>
                        <th>Main Style Category</th> 
                        <th>Garment Color</th>
                        <th>Size</th> 
                        <th>Reject Qty</th> 
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
            ajax: {
                url: "{{ route('PackingRejectionReport') }}",
                type: 'GET'
            },
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ],
            columns: [
                {data: 'qcp_code', name: 'qcp_code'},
                {data: 'qcp_date', name: 'qcp_date'},
                {data: 'sales_order_no', name: 'sales_order_no'},
                {data: 'vpo_code', name: 'vpo_code'},
                {data: 'vendorName', name: 'vendorName'},
                {data: 'Ac_name', name: 'ledger_master.Ac_name'},
                {data: 'brand_name', name: "brand_master.brand_name"},
                {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'}, 
                {data: 'color_name', name: 'color_master.color_name'},
                {data: 'size_name', name: 'size_detail.size_name', class: 'text-center'}, 
                {data: 'TotalQty', name: 'TotalQty', class: 'text-center'}
            ]
        });
    });
</script>                                        
@endsection