@extends('layouts.master')  
@section('content')   
   <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Fabric PO Vs GRN Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
                        <li class="breadcrumb-item active">Fabric PO Vs GRN Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body"> 
                <table id="tbl" class="table table-bordered dt-responsive nowrap w-100 ">
                      <thead>
                        <tr style="text-align:center;">
                            <th>SrNo</th>
                            <th>PO Number</th>
                            <th>PO Date</th>
                            <th>GRN No</th>
                            <th>GRN Date</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>UOM</th>
                            <th>Rate</th>
                            <th>PO  Qty</th>
                            <th>Received Qty</th>
                            <th>Pending Qty</th>
                            <th>QC Pass Qty</th>
                            <th>QC Reject Qty</th>
                            <th>Issue Qty</th>
                            <th>Balance Qty</th>
                            <th>PO Value</th>
                            <th>Received Value</th>
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
    
        $(function () 
        {
          	$('#tbl').DataTable().clear().destroy();
            var no=0;
            var table = $('#tbl').DataTable({
                ajax: "{{ route('FabricPOVsGRNDashboard') }}",
                     dom: 'lBfrtip',
                buttons: [
                    { extend: 'copyHtml5', footer: true },
                    { extend: 'excelHtml5', footer: true },
                    { extend: 'csvHtml5', footer: true },
                    { extend: 'pdfHtml5', footer: true }
                ],
                columns: [
                  { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                  {data: 'pur_code', name: 'purchase_order.pur_code'},
                  {data: 'pur_date', name: 'pur_date.pur_date'},
                  {data: 'in_code', name: 'inward_master.in_code'},
                  {data: 'in_date', name: "inward_master.in_date"},
                  {data: 'invoice_no', name: 'inward_master.invoice_no'},
                  {data: 'invoice_date', name: 'inward_master.invoice_date'},
                  {data: 'item_code', name: 'item_master.item_code'},
                  {data: 'item_name', name: 'item_master.item_name'},
                  {data: 'item_description', name: 'item_master.item_description'},
                  {data: 'unit_name', name: 'unit_master.unit_name'},
                  {data: 'item_rate', name: 'inward_detail.item_rate'},
                  {data: 'po_qty', name: 'po_qty'},
                  {data: 'received_qty', name: 'received_qty'},
                  {data: 'pending_qty', name: 'pending_qty'},
                  {data: 'pass_meter', name: 'pass_meter'},
                  {data: 'reject_short_meter', name: 'reject_short_meter'},
                  {data: 'issue_meter', name: 'issue_meter'},
                  {data: 'balance_meter', name: 'balance_meter'},
                  {data: 'PO_value', name: 'PO_value'},
                  {data: 'received_Value', name: 'received_Value'},
                 ]
            });
        });
    </script> 
@endsection