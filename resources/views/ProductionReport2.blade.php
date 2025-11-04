@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
         <h4 class="mb-sm-0 font-size-18">Production Report - 2</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Production Report - 2</li>
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
              <form action="{{route('ProductionReport2')}}" method="GET" enctype="multipart/form-data">
                   <div class="row"> 
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{ $fromDate }}"> 
                         </div>
                        </div> 
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" name="toDate" id="toDate" class="form-control" value="{{ $toDate }}"> 
                         </div>
                        </div> 
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor Name</label>
                            <select name="vendorId" id="vendorId" class="form-control select2"  onchange="GetPlanLineList(this.value);" >
                               <option value="">--Select--</option>
                               @foreach($LedgerList as  $row)
                               <option value="{{ $row->ac_code }}" {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }} >{{ $row->ac_short_name }}</option>
                               @endforeach
                            </select>
                         </div>
                        </div> 
                        <div class="col-md-3">
                         <div class="mb-3">
                            <label for="line_id" class="form-label">Line No.</label>
                            <select name="line_id" id="line_id" class="form-control select2" >
                                <option value="">--Select--</option>
                               @foreach($LineList as  $rows)
                               <option value="{{ $rows->line_id }}" {{ $rows->line_id == $line_id ? 'selected="selected"' : '' }} >{{ $rows->line_name }}</option>
                               @endforeach
                            </select>
                         </div>
                        </div> 
                        <div class="col-sm-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/ProductionReport2" class="btn btn-danger w-md">Clear</a>
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
               <table id="dt" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>Date</th>
                        <th nowrap colspan="5" style="background: #65fc65;">Cutting</th>
                        <th nowrap colspan="5" style="background: #c6c6f4;">Production</th>
                        <th nowrap colspan="5" style="background: #eebfbf;">Washing</th>
                        <th nowrap colspan="5" style="background: #abff00;">Packing</th>
                     </tr>
                     <tr style="text-align:center">
                        <th nowrap></th>
                        <th nowrap colspan="2" style="background: #65fc65;">Cutting</th>
                        <th nowrap colspan="2" style="background: #65fc65;">Cutting Issue</th>
                        <th nowrap style="background: #65fc65;">WIP</th>
                        <th nowrap colspan="2" style="background: #c6c6f4;">Input/Loding</th>
                        <th nowrap colspan="2" style="background: #c6c6f4;">Production</th>
                        <th nowrap style="background: #c6c6f4;">WIP</th>
                        <th nowrap colspan="2" style="background: #eebfbf;">Outward</th>
                        <th nowrap colspan="2" style="background: #eebfbf;">Inward</th>
                        <th nowrap style="background: #eebfbf;">WIP</th>
                        <th nowrap colspan="2" style="background: #abff00;">Input/Loding</th>
                        <th nowrap colspan="2" style="background: #abff00;">Packing</th>
                        <th nowrap style="background: #abff00;">WIP</th>
                     </tr>
                     <tr style="text-align:center">
                        <th nowrap></th>
                        <th nowrap style="background: #65fc65;">Today</th>
                        <th nowrap style="background: #65fc65;">Cumulative</th>
                        <th nowrap style="background: #65fc65;">Today</th>
                        <th nowrap style="background: #65fc65;">Cumulative</th> 
                        <th nowrap style="background: #65fc65;"></th>
                        <th nowrap style="background: #c6c6f4;">Today</th>
                        <th nowrap style="background: #c6c6f4;">Cumulative</th>
                        <th nowrap style="background: #c6c6f4;">Today</th>
                        <th nowrap style="background: #c6c6f4;">Cumulative</th> 
                        <th nowrap style="background: #c6c6f4;"></th>
                        <th nowrap style="background: #eebfbf;">Today</th>
                        <th nowrap style="background: #eebfbf;">Cumulative</th>
                        <th nowrap style="background: #eebfbf;">Today</th>
                        <th nowrap style="background: #eebfbf;">Cumulative</th> 
                        <th nowrap style="background: #eebfbf;"></th>
                        <th nowrap style="background: #abff00;">Today</th>
                        <th nowrap style="background: #abff00;">Cumulative</th>
                        <th nowrap style="background: #abff00;">Today</th>
                        <th nowrap style="background: #abff00;">Cumulative</th> 
                        <th nowrap style="background: #abff00;"></th>
                     </tr>
                  </thead>
                  <tbody> 
                    @php
                        $cumulativeCutPanelGRN = 0;
                        $cumulativeCutPanelIssue = 0;
                        $cumulativeStitiching = 0;
                        $cumulativeOutwardQty = 0;
                        $cumulativeWashingInwardQty = 0;
                        $cumulativePackingQty = 0;
                         
                        $prevCuttingWIP = 0;
                        $prevProductionWIP = 0;
                        $prevWashingWIP = 0;
                        
                    @endphp
                    @foreach($period as $dates)
                        @php
                            $filter = '';
                            $filter1 = '';
                            if($vendorId > 0 && $vendorId !='')
                            {
                                $filter .= ' AND vendorId='.$vendorId;
                            }
                            
                            if($line_id > 0 && $line_id !='')
                            {
                                $filter1 .= ' AND line_id='.$line_id;
                            }
                            
                            
                            $cutPanelGRNData = DB::SELECT("SELECT SUM(total_qty) as cut_panel_grn_qty FROM cut_panel_grn_master WHERE cpg_date = '".$dates."'".$filter);
                            $cutPanelIssueData = DB::SELECT("SELECT SUM(total_qty) as cut_panel_issue_qty FROM cut_panel_issue_master WHERE cpi_date = '".$dates."'".$filter." ".$filter1);
                            $stitchingData = DB::SELECT("SELECT SUM(total_qty) as stitiching_qty FROM stitching_inhouse_master WHERE sti_date = '".$dates."' ".$filter." ".$filter1);
                            $vendorPurchaseData = DB::SELECT("SELECT SUM(final_bom_qty) as outward_qty FROM vendor_purchase_order_master WHERE process_id = 4 AND vpo_date = '".$dates."'".$filter." ".$filter1);
                            //DB::enableQueryLog();
                            $washingInwardData = DB::SELECT("SELECT SUM(total_qty) as washing_inward_qty FROM washing_inhouse_master WHERE wash_date = '".$dates."'".$filter);
                            //dd(DB::getQueryLog());
                            $packingData = DB::SELECT("SELECT SUM(total_qty) as packing_qty FROM packing_inhouse_master WHERE packing_type_id = 4 AND pki_date = '".$dates."'".$filter);
                                
                            $cut_panel_grn_qty = isset($cutPanelGRNData[0]->cut_panel_grn_qty) ? $cutPanelGRNData[0]->cut_panel_grn_qty : 0;
                            $cut_panel_issue_qty = isset($cutPanelIssueData[0]->cut_panel_issue_qty) ? $cutPanelIssueData[0]->cut_panel_issue_qty : 0;
                            $stitiching_qty = isset($stitchingData[0]->stitiching_qty) ? $stitchingData[0]->stitiching_qty : 0;
                            $outward_qty = isset($vendorPurchaseData[0]->outward_qty) ? $vendorPurchaseData[0]->outward_qty : 0;
                            $washing_inward_qty = isset($washingInwardData[0]->washing_inward_qty) ? $washingInwardData[0]->washing_inward_qty : 0;
                            $packing_qty = isset($packingData[0]->packing_qty) ? $packingData[0]->packing_qty : 0;
                    
                            $cumulativeCutPanelGRN += $cut_panel_grn_qty;
                            $cumulativeCutPanelIssue += $cut_panel_issue_qty;
                            $cumulativeStitiching += $stitiching_qty;
                            $cumulativeOutwardQty += $outward_qty;
                            $cumulativeWashingInwardQty += $washing_inward_qty;
                            $cumulativePackingQty += $packing_qty;
                            
                            if($period[count($period) - 1] == $dates)
                            {
                                $cuttingWIP = ((float) $cut_panel_grn_qty + (float) $prevCuttingWIP) - (float) $cut_panel_issue_qty;
                                $productionWIP = ((float) $cut_panel_grn_qty + (float) $prevProductionWIP) - (float) $stitiching_qty;
                                $washingWIP = ((float) $outward_qty + (float) $prevWashingWIP) - (float) $washing_inward_qty;

                            }
                            else
                            {
                                $cuttingWIP = '';
                                $productionWIP = '';
                                $washingWIP = '';
                            }
                        @endphp
                        <tr>
                            <td style="text-align:center; white-space:nowrap">{{date("d-m-Y", strtotime($dates))}}</td>
                            <td style="text-align:right; white-space:nowrap;background: #65fc65;">{{money_format("%!.0n", $cut_panel_grn_qty)}}</td>
                            <td style="text-align:right; white-space:nowrap;background: #65fc65;">{{money_format("%!.0n", $cumulativeCutPanelGRN)}}</td>
                            <td style="text-align:right;background: #65fc65;">{{money_format("%!.0n", $cut_panel_issue_qty)}}</td>
                            <td style="text-align:right;background: #65fc65;">{{money_format("%!.0n", $cumulativeCutPanelIssue)}}</td>
                            <td style="text-align:right;background: #65fc65;">{{money_format("%!.0n", (float)$cuttingWIP)}}</td>
                            <td style="text-align:right; white-space:nowrap;background: #c6c6f4;">{{money_format("%!.0n", $cut_panel_issue_qty)}}</td>
                            <td style="text-align:right; white-space:nowrap;background: #c6c6f4;">{{money_format("%!.0n", $cumulativeCutPanelIssue)}}</td> 
                            <td style="text-align:right;background: #c6c6f4;">{{money_format("%!.0n", $stitiching_qty)}}</td>
                            <td style="text-align:right;background: #c6c6f4;">{{money_format("%!.0n", $cumulativeStitiching)}}</td>
                            <td style="text-align:right;background: #c6c6f4;">{{money_format("%!.0n", (float)$productionWIP)}}</td>
                            <td style="text-align:right;background: #eebfbf;">{{money_format("%!.0n", $outward_qty)}}</td>
                            <td style="text-align:right;background: #eebfbf;">{{money_format("%!.0n", $cumulativeOutwardQty)}}</td>
                            <td style="text-align:right;background: #eebfbf;">{{money_format("%!.0n", $washing_inward_qty)}}</td>
                            <td style="text-align:right;background: #eebfbf;">{{money_format("%!.0n", $cumulativeWashingInwardQty)}}</td>
                            <td style="text-align:right;background: #eebfbf;">{{money_format("%!.0n", (float)$washingWIP)}}</td>
                            <td style="text-align:right;background: #abff00;">0</td>
                            <td style="text-align:right;background: #abff00;">0</td>
                            <td style="text-align:right;background: #abff00;">{{money_format("%!.0n", $packing_qty)}}</td> 
                            <td style="text-align:right;background: #abff00;">{{money_format("%!.0n", $cumulativePackingQty)}}</td>
                            <td style="text-align:right;background: #abff00;">0</td>
                        </tr>
                        @php
                            $prevCuttingWIP = $cuttingWIP;
                            $prevProductionWIP = $productionWIP;
                            $prevWashingWIP = $washingWIP;
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    
    function GetPlanLineList(ele)
    {
            $.ajax({
                dataType: "json",
                url: "{{ route('GetPlanLineList') }}",
                data:{'Ac_code':ele},
                success: function(data){
                $('#line_id').html(data.html);
               }
            });
    } 
    
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#dt')) 
        {
            $('#dt').DataTable().clear().destroy();
        }
    
        $('#dt').DataTable({
            pageLength: 35,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
    });



</script>
@endsection