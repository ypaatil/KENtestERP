@extends('layouts.master') 
@section('content')  
@php 
    ini_set('memory_limit', '10G');
    setlocale(LC_MONETARY, 'en_IN');  
@endphp   
<style>
   tfoot {
        display: table-header-group;
    }
    .text-right {
        text-align: right;
    }
    .table-warning {
        background-color: #fff3cd;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric And Trims PO Follow Up Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Fabric And Trims PO Follow Up Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row"> 
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/FabricTrimsPOFollowUpReport" method="GET">
                  <div class="row">
                      <div class="col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" value="{{$from_date }}">
                      </div>  
                      <div class="col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $to_date }}">
                      </div> 
                      <div class="col-md-2">
                            <label for="merchant_id" class="form-label">Merchant</label>
                            <select name="merchant_id" class="form-control select2" id="merchant_id">
                                <option value="">--Select--</option>
                                @foreach($merchantList as $row)
                                     <option value="{{$row->merchant_id}}"  {{ $row->merchant_id == $merchant_id ? 'selected="selected"' : '' }} >{{$row->merchant_name}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId" class="form-control select2" id="orderTypeId">
                                <option value="">--Select--</option>
                                @foreach($OrderTypeList as $row)
                                     <option value="{{$row->orderTypeId}}"  {{ $row->orderTypeId == $orderTypeId ? 'selected="selected"' : '' }} >{{$row->order_type}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="Ac_code" class="form-label">Buyer Name</label>
                            <select name="Ac_code" class="form-control select2" id="Ac_code">
                                <option value="">--Select--</option>
                                @foreach($LedgerList as $row)
                                     <option value="{{$row->ac_code}}"  {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{$row->ac_short_name}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="brand_id" class="form-label">Buyer Brand</label>
                            <select name="brand_id" class="form-control select2" id="brand_id">
                                <option value="">--Select--</option>
                                @foreach($BrandList as $row)
                                     <option value="{{$row->brand_id}}"  {{ $row->brand_id == $brand_id ? 'selected="selected"' : '' }} >{{$row->brand_name}}</option>
                                @endforeach
                            </select> 
                      </div> 
                      <div class="col-md-2 mt-4 text-center"> 
                            <button type="submit" class="btn btn-primary" aria-label="Search Button">Search</button>
                            <a href="/FabricTrimsPOFollowUpReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div> 
   <div class="col-12">
      <div class="card">
         <div class="card-body table-responsive">
              <table id="tbl" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>Order Recd</th>
                     <th>Order No.</th>
                     <th>KAM</th>
                     <th>Merchant</th>
                     <th>Order Delivery</th>
                     <th>Order Type</th>
                     <th>Buyer Name</th> 
                     <th>Buyer Brand</th>
                     <th>Product Type</th>
                     <th>Color</th>
                     <th>Order Qty</th>
                     <th>Fabric PO No.</th>
                     <th>Fab. Order Qty</th>
                     <th>Fab. Delivery Date</th>
                     <th>Fab. Supplier</th>
                     <th>Trims PO Status</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($BuyerPurchaseList as $row) 
                  @php
                        $sewingPackingData = DB::SELECT("SELECT((SELECT count(*) FROM bom_sewing_trims_details WHERE sales_order_no = '".$row->tr_code."') 
                                            + (SELECT count(*) FROM bom_sewing_trims_details WHERE sales_order_no = '".$row->tr_code."'))  as count");
                        $purchaseData = DB::SELECT("SELECT count(*) as count FROM purchaseorder_detail WHERE sales_order_no = '".$row->tr_code."'");
                        
                        $sew_pack_count = isset($sewingPackingData[0]->count) ? $sewingPackingData[0]->count : 0;
                        $purchase_count = isset($packingData[0]->count) ? $packingData[0]->count : 0;
                        
                        if($sew_pack_count === $purchase_count && $purchase_count > 0)
                        {
                            $status = 'PO Placed';
                        }
                        else if($sew_pack_count < $purchase_count && $sew_pack_count > 0 || $sew_pack_count > $purchase_count && $sew_pack_count > 0)
                        {
                            $status = 'Partial Placed';
                        }
                        else 
                        {
                            $status = 'Not Placed';
                        }
                        
                  @endphp
                  <tr>
                      <td nowrap>{{ date("d-m-Y", strtotime($row->order_received_date)) }}</td>
                      <td nowrap>{{$row->tr_code}}</td>
                      <td>{{$row->PDMerchant_name}}</td>
                      <td>{{$row->merchant_name}}</td>
                      <td>{{ date("d-m-Y", strtotime($row->shipment_date)) }}</td>
                      <td>{{$row->order_type}}</td>
                      <td>{{$row->buyer_name}}</td> 
                      <td>{{$row->brand_name}}</td>
                      <td>{{$row->mainstyle_name}}</td>
                      <td nowrap>{{$row->color_name}}</td>
                      <td class="text-right"> {{ number_format($row->order_qty, 0, ',', ',') }}</td>
                      <td>{{$row->pur_codes}}</td>
                      <td class="text-right"> {{ number_format($row->total_item_qty, 0, ',', ',') }}</td>
                      <td>@if($row->delivery_date != '') {{ date("d-m-Y", strtotime($row->delivery_date)) }} @endif</td>
                      <td>{{$row->supplier_name}}</td>
                      <td>{{$status}}</td>
                  </tr>
                  @endforeach
               </tbody> 
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.1/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        $('#tbl').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            dom: 'Bfrtip',  // Required for buttons to show
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection
