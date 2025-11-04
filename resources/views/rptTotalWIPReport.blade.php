@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css"> 
<style>
    .hide{
        display:none;
    }
    
    .text-right{
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Total WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Total WIP Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="row">
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="  fw-medium" style="color:#fff;">Total WIP(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP">0</h4>
                </div>
                <div class="flex-shrink-0 align-self-center">
                   <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                      <span class="avatar-title" style="background-color:#152d9f;">
                      <i class="bx bx-copy-alt font-size-24"></i>
                      </span>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="col-md-2">
       <div class="card mini-stats-wid" style="background-color:#152d9f;" >
          <div class="card-body">
             <div class="d-flex">
                <div class="flex-grow-1">
                   <p class="  fw-medium" style="color:#fff;">Total Value(In Lakh)</p>
                   <h4 class="mb-0" style="color:#fff;" id="head_total_WIP_value">0</h4>
                </div>
                <div class="flex-shrink-0 align-self-center">
                   <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                      <span class="avatar-title" style="background-color:#152d9f;">
                      <i class="bx bx-copy-alt font-size-24"></i>
                      </span>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/rptTotalWIPReport" method="GET">
                  <div class="row">  
                      <div class="col-md-3 hide">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate}}">
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">Date</label>
                            <input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate}}">
                         </div>
                      </div>
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="job_status_id" class="form-label">Job Status Name</label>
                            <select name="job_status_id[]" id="job_status_id" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" >{{$row->job_status_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="orderTypeId" class="form-label">Order Type</label>
                            <select name="orderTypeId[]" id="orderTypeId" class="form-control select2" multiple>
                                <option value="">--Select--</option>
                                @foreach($orderTypeList as $row)
                                    <option value="{{$row->orderTypeId}}">{{$row->order_type}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-md-3">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor</label>
                            <select name="vendorId" id="vendorId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($vendorList as $row)
                                    <option value="{{$row->ac_code}}"  {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }}  >{{$row->ac_name}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                      <div class="col-md-6 mt-3"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/rptTotalWIPReport" class="btn btn-warning">Clear</a>
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
            <!--<div class="col-md-12 hide text-center"  id="waiting"><img src="{{ URL::asset('images/loading-waiting.gif')}}" width="300" height="300"></div>-->
            <div class="table-responsive">
               
                     <table id="dt" class="table table-bordered nowrap w-100">
                         <thead>
                              <tr style="background-color:#eee;"> 
                                   <th nowrap>Sr. No.</th>
                                   <th nowrap>Order No</th>
                                   <th nowrap>PO Status</th>
                                   <th nowrap>Order Type</th>
                                   <th nowrap>Buyer Brand</th>
                                   <th nowrap>Category</th>
                                   <th class="text-center" nowrap>Order Qty</th>
                                   <th class="text-center" nowrap>Work Order Qty</th> 
                                   <th class="text-center" nowrap>Cutting Qty</th>
                                   <th class="text-center" nowrap>Cutting WIP</th> 
                                   <th class="text-center" nowrap>Sewing Qty</th>
                                   <th class="text-center" nowrap>Sewing WIP</th> 
                                   <th class="text-center" nowrap>Packing Qty</th>
                                   <th class="text-center" nowrap>Packing WIP </th> 
                                   <th class="text-center" nowrap>Rejection Qty</th> 
                                   <th class="text-center" nowrap>WIP Adjust Qty</th> 
                                   <th class="text-center" nowrap>Total WIP</th>
                                   <th class="text-center" nowrap>Order Rate</th>
                                   <th class="text-center" nowrap>Total WIP Value</th>
                              </tr>
                         </thead>
                         <tbody>
                            @php
                                $head_total_WIPQty = 0;
                                $head_total_WIPValue = 0;
                                $srno = 1;
                            @endphp
                            @foreach($Buyer_Purchase_Order_List as $row)  
                            @php
                                $filter = '';
                                
                                if($vendorId > 0)
                                {
                                    $filter .= " AND vendorId=".$vendorId;
                                }
                                
                                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = vendor_work_order_detail.vw_code
                                     where  vendor_work_order_detail.sales_order_no='".$row->tr_code."' AND vendor_work_order_detail.vw_date <= '".$toDate."'".$filter);
                                
                                
                                $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <= '".$toDate."'".$filter);
                                
                                if(count($CutPanelData) > 0)
                                {
                                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                                }
                                else
                                {
                                        $cutPanelIssueQty = 0;
                                } 
                                
                                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$toDate."'".$filter);
                                
                                if(count($StichingData) > 0)
                                {
                                        $stichingQty = $StichingData[0]->stiching_qty;
                                }
                                else
                                {
                                        $stichingQty = 0;
                                }
                                
                                
                               $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$toDate."' AND packing_type_id=4".$filter);
      
                               if(count($PackingData) > 0)
                               {
                                     $pack_order_qty = $PackingData[0]->total_qty;
                               }
                               else
                               {
                                     $pack_order_qty = 0;
                               }
                               
                                //dd(DB::getQueryLog());
                             
                              //dd(DB::getQueryLog()); 
                              
                              if($row->order_type == 1)
                              {
                                  $order_type = 'Fresh';
                              }
                              else if($row->order_type == 2)
                              {
                                  $order_type = 'Stock';
                              }
                              else if($row->order_type == 3)
                              {
                                  $order_type = 'Job Work';
                              }
                              else
                              {
                                  $order_type = '';
                              }
                              
                            //  $packingOrderQty = DB::select("SELECT ifnull(sum(final_bom_qty),0) as pack_order_qty from vendor_purchase_order_master  
                            //     where vendor_purchase_order_master.sales_order_no='".$row->tr_code."' AND process_id=3");
                              
                            //  if(count($packingOrderQty) > 0)
                           //   {
                            //    $pack_order_qty = isset($packingOrderQty[0]->pack_order_qty) ? $packingOrderQty[0]->pack_order_qty : 0;
                             // }
                             // else
                              //{
                               // $pack_order_qty = 0;
                              //}
                              $sewing = $cutPanelIssueQty - $stichingQty;
                              
                              $totalWIP = ($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $stichingQty + $pack_order_qty;
                              
                              $SalesCostingData = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
                              
                              $fabric_value = isset($SalesCostingData[0]->fabric_value) ? $SalesCostingData[0]->fabric_value : 0;  
                              $sewing_trims_value = isset($SalesCostingData[0]->sewing_trims_value) ? $SalesCostingData[0]->sewing_trims_value : 0;
                              $packing_trims_value = isset($SalesCostingData[0]->packing_trims_value) ? $SalesCostingData[0]->packing_trims_value : 0;             
                              
                              $WIPAdjustQtyData=DB::select("SELECT ifnull(sum(size_qty_total),0) as WIP_adjust_qty from WIP_Adjustable_Qty_detail  
                                     where  sales_order_no='".$row->tr_code."'");
                            
                               if(count($WIPAdjustQtyData) > 0)
                               {
                                     $WIP_Adjust_qty = $WIPAdjustQtyData[0]->WIP_adjust_qty;
                               }
                               else
                               {
                                     $WIP_Adjust_qty = 0;
                               }
                               
                               $rejectionData=DB::select("SELECT ifnull(sum(size_qty_total),0) as reject_qty from qcstitching_inhouse_reject_detail  
                                     where  sales_order_no='".$row->tr_code."' AND qcsti_date <='".$toDate."'".$filter);
                              
                               $rejectionQty = isset($rejectionData[0]->reject_qty) ? $rejectionData[0]->reject_qty : 0;       
                            @endphp
                            <tr>
                               <td nowrap class="text-right">{{ $srno++ }}</td>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{ $row->job_status_name  }}</td>
                               <td nowrap>{{ $order_type  }}</td>
                               <td nowrap> {{ $row->brand_name  }} </td>
                               <td nowrap>{{ $row->mainstyle_name  }}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$row->total_qty) }} </td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty)}}</td>
                               
                               <td nowrap class="text-right">{{ money_format("%!.0n",$cutPanelIssueQty) }}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($VendorData[0]->work_order_qty - $cutPanelIssueQty)) }}</td>
                               
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($stichingQty))}}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($sewing)) }} </td>
                               
                               <td nowrap class="text-right">{{ money_format("%!.0n",($pack_order_qty + $rejectionQty)) }}</td>
                               <td class="text-right">{{ money_format("%!.0n",($stichingQty - $pack_order_qty - $rejectionQty)) }} </td>
                               <td class="text-right">{{ money_format("%!.0n",($rejectionQty)) }} </td>
                               <td class="text-right">{{ money_format("%!.0n",($WIP_Adjust_qty)) }} </td>
                              
                               <td class="text-right" nowrap>{{money_format("%!.0n",(($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty - $rejectionQty ) - $WIP_Adjust_qty)) }}</td>
                               <td class="text-right" nowrap>{{ sprintf('%0.2f', $fabric_value +  $sewing_trims_value + $packing_trims_value) }}</td>
                               
                               <td class="text-right" nowrap>{{money_format("%!.0n",(($fabric_value +  $sewing_trims_value + $packing_trims_value) * (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty - $rejectionQty) - $WIP_Adjust_qty))) }}</td>
                             </tr>
                             @php
                             
                              $head_total_WIPQty += (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty - $rejectionQty ) - $WIP_Adjust_qty);
                              $head_total_WIPValue += (($fabric_value +  $sewing_trims_value + $packing_trims_value) * (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty - $rejectionQty) - $WIP_Adjust_qty));
                             @endphp
                            @endforeach
                         </tbody>
                      </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<input type="hidden" id="hidden_WIP_Qty" value="{{number_format((float)$head_total_WIPQty/100000, 2, '.', '')}}">
<input type="hidden" id="hidden_WIP_Value" value="{{number_format((float)$head_total_WIPValue/100000, 2, '.', '')}}">
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script>  

    $(document).ready(function()
    { 
        $("#head_total_WIP").text($("#hidden_WIP_Qty").val());
        $("#head_total_WIP_value").text($("#hidden_WIP_Value").val()); 
        $('#job_status_id').val([{{$Status}}]).trigger('change');
        $('#orderTypeId').val([{{$orderType}}]).trigger('change');
                  
        if ($.fn.DataTable.isDataTable('#dt')) {
            $('#dt').DataTable().clear().destroy();
        } 

        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        const exportTitle = 'Total WIP Report (' + formattedDate + ')';
        
        $('#dt').DataTable({
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    title: exportTitle
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: exportTitle
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    title: exportTitle
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    title: exportTitle,
                    orientation: 'landscape',     // or 'portrait'
                    pageSize: 'A4',               // A4, A3, etc.
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10; // PDF text size
                    }
                },
                {
                    extend: 'print',
                    text: 'Print Table',
                    title: exportTitle
                }
            ]

        }); 
    }); 
   </script>
@endsection