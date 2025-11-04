@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">WIP Detail Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">WIP Detail Report</li>
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
               <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                  <thead>
                     <th>Sr.No.</th>
                     <th class="text-center">Sales Order No.</th>
                     <th class="text-center">PO Qty.</th>
                     <th class="text-center">Garment Color</th>
                     <th class="text-center">Received Qty.</th>
                     <th class="text-center">Balance/WIP Qty.</th>
                  </thead>
                  <tbody>
                      @php
                        $srno = 1;
                        $total_po_qty = 0;
                        $total_received_qty = 0;
                        $total_balance_qty = 0;
                      @endphp
                      @foreach($ProductionOrderDetailList as $ProductionOrder)
                        <tr>
                            <td>{{$srno++}}</td>
                            <td class="text-center">{{$ProductionOrder->sales_order_no}}</td>
                            <td class="text-center">{{ number_format($ProductionOrder->order_qty) }}</td>
                            <td class="text-center">{{ $ProductionOrder->color_name  }}</td>
                            <td class="text-center">{{ number_format($ProductionOrder->total_packing_qty ) }}</td>
                            <td class="text-center">{{ number_format($ProductionOrder->order_qty-$ProductionOrder->total_packing_qty ) }}</td>
                        </tr>
                        @php
                             $total_po_qty = $total_po_qty + $ProductionOrder->order_qty;
                             $total_received_qty = $total_received_qty + $ProductionOrder->total_packing_qty;
                             $total_balance_qty = $total_balance_qty + ($ProductionOrder->order_qty-$ProductionOrder->total_packing_qty );
                        @endphp
                        @endforeach
                        
                        <tr>
                            <td class="text-right" colspan="2"><b>Total:</b></td>
                            <td class="text-center"><b>{{ number_format($total_po_qty) }}</b></td>
                            <td class="text-center"></td>
                            <td class="text-center"><b>{{ number_format($total_received_qty) }}</b></td>
                            <td class="text-center"><b>{{ number_format($total_balance_qty) }}</b></td>
                        </tr>
                  </tbody>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection