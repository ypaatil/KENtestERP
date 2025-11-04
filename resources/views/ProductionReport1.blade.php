@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<style>
    /*.borderless {*/
    /*    border-right: 1px solid #ffffff !important;*/
    /*    vertical-align: middle !important;*/
    /*}*/
</style>
@php
if($job_status_id==1) { @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Production Report - 1</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Production Report - 1</li>
            </ol>
         </div>
      </div>
   </div>
</div>
@php 
}
@endphp  
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('ProductionReport1')}}" method="GET" enctype="multipart/form-data">
                   <div class="row"> 
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="po_code" class="form-label">PO NO</label>
                            <select name="po_code" id="po_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($poList as $row)
                                    <option value="{{$row->po_code}}"  {{ $row->po_code == $po_code ? 'selected="selected"' : '' }}  >{{$row->po_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="ac_code" class="form-label">Buyer Name</label>
                            <select name="ac_code" id="ac_code" class="form-control select2" onchange="getBrandList(this.value)">
                                <option value="">--Select--</option>
                                @foreach($buyerList as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $Ac_code ? 'selected="selected"' : '' }} >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select2" onchange="Disabled();">
                                <option value="">--Select--</option>
                                @foreach($brandList as $row)
                                    <option value="{{$row->brand_id}}"  {{ $row->brand_id == $brand_id ? 'selected="selected"' : '' }}  >{{$row->brand_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>  
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No</label>
                            <select name="sales_order_no" id="sales_order_no" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($salesOrderList as $row)
                                    <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected="selected"' : '' }} >{{$row->tr_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div>  
                       <div class="col-sm-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/ProductionReport1" class="btn btn-danger w-md">Clear</a>
                          </div>
                       </div> 
                   </div>
             </form>
         </div>
      </div>
   </div>
</div>                        
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>Order No</th>
                        <th nowrap>Buyer Name</th>
                        <th nowrap>Buyer Brand</th>
                        <th nowrap>Style</th>
                        <th nowrap>Style Name</th>
                        <th nowrap>PO No</th>
                        <th>Color</th>
                        <th nowrap nowrap>Order Qty<br/>(A)</th> 
                        <th class="borderless" nowrap>Cutting<br/>(B)</th> 
                        <th class="borderless" nowrap>Stitching<br/>(C)</th> 
                        <th class="borderless" nowrap>Packing<br/>(D)</th> 
                        <th class="borderless" nowrap>Fresh<br/>G=(C-F)</th> 
                        <th class="borderless" nowrap>Rejection<br/>(E)</th> 
                        <th class="borderless" nowrap>Total<br/>H=(D+G+E)</th>
                        <th class="borderless" nowrap>Deviation<br/>(B-H)</th>  
                        <th class="borderless" nowrap>Cut To Pack %<br/>(D/B)</th>  
                        <th class="borderless" nowrap>Shipment Qty<br/>(F)</th>  
                        <th class="borderless" nowrap>Cut To Ship %<br/>(F/B)</th>  
                        <th class="borderless" nowrap>Order To Ship %<br/>(F/A)</th>  
                     </tr>
                  </thead>
                  <tbody>  
                     @php
                        $total_order_qty = 0;
                        $total_cutting_qty = 0;
                        $total_stitching_qty = 0;
                        $total_packing_qty = 0;
                        $total_fresh = 0;
                        $total_rejection_qty = 0;
                        $total_overallTotal_qty = 0;
                        $total_deviation_qty = 0;
                        $total_shipment_qty = 0;
                     @endphp
                     @foreach($ProductionOrderDetailList as $row) 
                     @php 
                     
                     $TotalCutQty=DB::select("select  ifnull(sum(size_qty_total),0)  as total_cutting_qty from cut_panel_grn_detail where
                     cut_panel_grn_detail.color_id='".$row->color_id."' and
                     cut_panel_grn_detail.sales_order_no='".$row->tr_code."'");  
                     
                     
                     $TotalStitchQty=DB::select("select ifnull(sum(size_qty_total),0) as total_stitching_qty from stitching_inhouse_detail where
                     stitching_inhouse_detail.color_id='".$row->color_id."' and
                     stitching_inhouse_detail.sales_order_no='".$row->tr_code."'"); 
                     
                     
                     $TotalQCQty=DB::select("select ifnull(sum(size_qty_total),0) as total_qcstitching_reject_qty from qcstitching_inhouse_reject_detail where
                     qcstitching_inhouse_reject_detail.color_id='".$row->color_id."' and 
                     qcstitching_inhouse_reject_detail.sales_order_no='".$row->tr_code."'");
                     
                     
                     $TotalPackQty=DB::select("select ifnull(sum(size_qty_total),0) as total_packing_qty from packing_inhouse_detail
                     inner join packing_inhouse_master on packing_inhouse_master.pki_code= packing_inhouse_detail.pki_code
                     where   packing_inhouse_detail.color_id='".$row->color_id."'
                     and packing_inhouse_detail.sales_order_no='".$row->tr_code."'");
                     
                     
                     $TotalShipQty=DB::select("select ifnull(sum(size_qty_total),0) as total_shipment_qty from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code = carton_packing_inhouse_detail.cpki_code
                     where   carton_packing_inhouse_detail.color_id='".$row->color_id."'
                     and carton_packing_inhouse_detail.sales_order_no='".$row->tr_code."'
                     and carton_packing_inhouse_master.endflag=1");  
                     
                     $cut_per = 0;
                     if($TotalPackQty[0]->total_packing_qty > 0 && $TotalCutQty[0]->total_cutting_qty > 0)
                     {
                        $cut_per = $TotalPackQty[0]->total_packing_qty/$TotalCutQty[0]->total_cutting_qty;
                     } 
                     
                     $cut_ship_per = 0;
                     if($TotalShipQty[0]->total_shipment_qty > 0 && $TotalCutQty[0]->total_cutting_qty > 0)
                     {
                        $cut_ship_per = $TotalShipQty[0]->total_shipment_qty/$TotalCutQty[0]->total_cutting_qty;
                     } 
                     
                     $order_ship_per = 0;
                     if($TotalShipQty[0]->total_shipment_qty > 0 && $row->order_qty > 0)
                     {
                        $order_ship_per = $TotalShipQty[0]->total_shipment_qty/$row->order_qty;
                     } 
                     @endphp                                    
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $row->tr_code  }} </td>
                        <td style="white-space:nowrap"> {{ $row->ac_short_name  }} </td>
                        <td style="white-space:nowrap"> {{ $row->brand_name  }} </td>
                        <td style="white-space:nowrap"> {{ $row->mainstyle_name  }} </td>
                        <td style="white-space:nowrap"> {{ $row->style_no  }} </td>
                        <td style="white-space:nowrap"> {{ $row->po_code  }} </td>
                        <td style="white-space:nowrap"> {{ $row->color_name  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$row->order_qty) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$TotalCutQty[0]->total_cutting_qty)  }}</td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$TotalStitchQty[0]->total_stitching_qty)  }}</td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$TotalPackQty[0]->total_packing_qty)  }}</td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty)  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',$TotalQCQty[0]->total_qcstitching_reject_qty)  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n', $TotalPackQty[0]->total_packing_qty + ($TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty) +  $TotalQCQty[0]->total_qcstitching_reject_qty) }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n',($TotalCutQty[0]->total_cutting_qty) - ($TotalPackQty[0]->total_packing_qty + ($TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty) +  $TotalQCQty[0]->total_qcstitching_reject_qty)) }}</td>
                        <td style="text-align:right;white-space:nowrap"> {{ round(($cut_per*100),2)  }} </td>
                        <td style="text-align:right;"> {{ money_format('%!.0n', $TotalShipQty[0]->total_shipment_qty)  }}</td> 
                        <td style="text-align:right;white-space:nowrap"> {{ round(($cut_ship_per*100),2)  }} </td>
                        <td style="text-align:right;white-space:nowrap"> {{ round(($order_ship_per*100),2)  }} </td>
                     </tr>
                     @php
                            $total_order_qty += $row->order_qty;
                            $total_cutting_qty += $TotalCutQty[0]->total_cutting_qty;
                            $total_stitching_qty += $TotalStitchQty[0]->total_stitching_qty;
                            $total_packing_qty += $TotalPackQty[0]->total_packing_qty;
                            $total_fresh += ($TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty);
                            $total_rejection_qty += $TotalQCQty[0]->total_qcstitching_reject_qty;
                            $total_overallTotal_qty += ($TotalPackQty[0]->total_packing_qty + ($TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty) +  $TotalQCQty[0]->total_qcstitching_reject_qty);
                            $total_deviation_qty += ($TotalCutQty[0]->total_cutting_qty) - ($TotalPackQty[0]->total_packing_qty + ($TotalStitchQty[0]->total_stitching_qty - $TotalShipQty[0]->total_shipment_qty) +  $TotalQCQty[0]->total_qcstitching_reject_qty);
                            $total_shipment_qty += $TotalShipQty[0]->total_shipment_qty;
                     @endphp
                     @endforeach
                  </tbody>
                  <tfoot>
                      @php
                         $avg_cut_per = 0;
                         if($total_packing_qty > 0 && $total_cutting_qty > 0)
                         {
                            $avg_cut_per = $total_packing_qty/$total_cutting_qty;
                         } 
                         
                         $avg_cut_ship_per = 0;
                         if($total_shipment_qty > 0 && $total_cutting_qty > 0)
                         {
                            $avg_cut_ship_per = $total_shipment_qty/$total_cutting_qty;
                         } 
                         
                         $avg_order_ship_per = 0;
                         if($total_shipment_qty > 0 && $total_order_qty > 0)
                         {
                            $avg_order_ship_per = $total_shipment_qty/$total_order_qty;
                         } 
                      @endphp
                     <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:right;">Total </th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_order_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_cutting_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_stitching_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_packing_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_fresh)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_rejection_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_overallTotal_qty)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_deviation_qty)}}</th>
                        <th style="text-align:right;">{{round(($avg_cut_per*100),2)}}</th>
                        <th style="text-align:right;">{{money_format("%!.0n",$total_shipment_qty)}}</th>
                        <th style="text-align:right;">{{round(($avg_cut_ship_per*100),2)}}</th>
                        <th style="text-align:right;">{{round(($avg_order_ship_per*100),2)}}</th>
                     </tr>
                  </tfoot>
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
    
    function getBrandList(val)
    { 
        $("#brand_id").select2('destroy'); 
        $.ajax({
            type: "GET",
            url: "{{ route('BrandList') }}",
            data: { 'Ac_code': val },
            success: function(data) {
                $("#brand_id").html(data.html);
                $("#brand_id").select2();
            }
        });
    }
    
    function Disabled()
    {
        $("#ac_code").attr('disabled', true);
    }
 


   
</script>
@endsection