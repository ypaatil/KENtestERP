@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .btn-icon {
        display: flex;
        align-items: center;
    }
    .btn-icon i {
        margin-right: 5px;
    }
    
    @media (max-width: 600px) {
        .breadcumbCls {
            display: none;
        }
        
        .navbar-header {
            background: #703eb385;
        }
        .titleCls { 
            text-align: center;
        }
        
        #vertical-menu-btn {
            display: none;
        }
    }
    
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 18px;
        text-align: left;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    /*th {*/
    /*    background-color: #f8f9fa;*/
    /*    color: #333;*/
    /*}*/

    /*tr:nth-child(even) {*/
    /*    background-color: #f2f2f2;*/
    /*}*/

    tbody tr:hover {
        color: black;
        font-weight:900;
    }

    caption {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .table>thead {
        vertical-align: middle;
        background-color: #024A8E!important;
        color: #fff;
    }
    
</style> 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Daily Efficiency Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Daily Efficiency Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card" style="margin-bottom: 0px;!important;">
            <div class="card-body"> 
                <form action="/DailyEfficiencyReport" method="GET" enctype="multipart/form-data">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="fromDate" class="form-label">From date</label>
                            <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fdate) ? $fdate : date('Y-m-01')}}" required> 
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($tdate) ? $tdate : date('Y-m-d')}}" required>
                        </div>
                    </div> 
                    <div class="col-md-5">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor Name</label>
                            <select name="vendorId[]" class="form-control select2" id="vendorId" multiple>
                                <option value="">--Vendor--</option>
                                @foreach($LedgerList as $row)
                                    <option value="{{ $row->ac_code }}" {{ in_array($row->ac_code, $vendorIds) ? 'selected' : '' }}>
                                        {{ $row->ac_short_name }}
                                    </option>
                                @endforeach
                            </select>
                         </div>
                      </div> 
                    <div class="col-sm-3 mt-2">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-md">Search</button>
                            <a href="/GetDailyEfficiencyReport" class="btn btn-danger w-md">Cancel</a>
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
            <div class="table-responsive" style="margin-top: -50px;">
                <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead> 
                        <tr style="text-align:center; border: 1px  solid;">
                           <th rowspan="2">Unit</th>
                           <th rowspan="2">Line No.</th> 
                           <th rowspan="2">SAM</th>
                           <th rowspan="2">No. Of Helpers</th>
                           <th rowspan="2">No. Of Operators</th>
                           <th rowspan="2">Total Manpower</th>
                           <th rowspan="2">Output</th>
                           <th rowspan="2">Available Min </th>
                           <th rowspan="2">Produced Min</th>
                           <th rowspan="2">Line Eff %</th>
                        </tr>
                     </thead>
                     @php
                        $overall_total_avgSAM = 0;
                        $overall_total_helpers = 0;
                        $overall_total_workers = 0;
                        $overall_total_manpower = 0;
                        $overall_total_qty = 0;
                        $overall_total_available_min = 0;
                        $overall_total_sam_qty = 0;
                        $overall_totalProducedMin = 0;
                        $overall_total_lines = 0;
                    @endphp
                    
                    <tbody>
                        @foreach($groupedLedgerData as $ac_short_name => $rows)
                            @php 
                                $vendor_total_avgSAM = 0;
                                $vendor_total_helpers = 0;
                                $vendor_total_workers = 0;
                                $vendor_total_qty = 0;
                                $vendor_total_available_min = 0;
                                $vendor_total_sam_qty = 0;
                                $vendor_total_lineEff = 0;
                                $line_count = 0;
                                $totalProducedMin = 0;
                                $tlf = 0;
                            @endphp
                        
                            @foreach($rows as $index => $row)
                                @php
                                    $StichingData = DB::select("select sum(stitching_inhouse_detail.size_qty_total) as TodaysPcs,  
                                        buyer_purchse_order_master.sam,
                                        (SELECT sum(total_workers) FROM stitching_inhouse_master WHERE vendorId = stitching_inhouse_detail.vendorId AND sti_date BETWEEN '".$fdate."' AND '".$tdate."'
                                                AND line_id = stitching_inhouse_detail.line_id) as total_workers, (SELECT sum(total_helpers) FROM stitching_inhouse_master WHERE vendorId = stitching_inhouse_detail.vendorId AND sti_date BETWEEN '".$fdate."' AND '".$tdate."'
                                                AND line_id = stitching_inhouse_detail.line_id) as total_helpers, 
                                        sum(stitching_inhouse_detail.size_qty_total) as total_qty,  
                                        sum(stitching_inhouse_detail.size_qty_total * buyer_purchse_order_master.sam) as total_min,
                                        AVG(stitching_inhouse_master.total_workers) as Todaysworkers
                                        from stitching_inhouse_detail 
                                        INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                                        INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_detail.sales_order_no
                                        where stitching_inhouse_master.vendorId='".$row->ac_code."' 
                                        and stitching_inhouse_master.line_id='".$row->line_id."'
                                        AND stitching_inhouse_master.sti_date BETWEEN '".$fdate."' AND '".$tdate."'");
                    
                                    $totalPMin = $StichingData[0]->total_min ?? 0;
                                    $total_qty = $StichingData[0]->total_qty ?? 0;
                                    $totalWorkers = $StichingData[0]->total_workers ?? 0;
                                    $totalHelpers = $StichingData[0]->total_helpers ?? 0;
                                    $sam = $StichingData[0]->sam ?? 0; 
                                    $TodaysPcs = $StichingData[0]->TodaysPcs ?? 0; 
                                    $Todaysworkers = $StichingData[0]->Todaysworkers ?? 0;
                    
                                    $avgSAM = ($totalPMin > 0 && $total_qty > 0) ? $totalPMin / $total_qty : 0;
                                    $total_avaliable_min = ($TodaysPcs * $sam > 0 && (($totalWorkers+$totalHelpers) * 480) > 0) ? ($totalWorkers+$totalHelpers) * 480 : 0;
                                    
                                    $lineEff = (($total_qty * $sam) > 0 && (($totalWorkers+$totalHelpers) * 480) > 0) ? round((($total_qty * $sam)/(($totalWorkers+$totalHelpers) * 480)),2)*100 : 0;
                    
                                    $vendor_total_workers += $totalWorkers;
                                    $vendor_total_helpers += $totalHelpers;
                                    $vendor_total_qty += $total_qty;
                                    $vendor_total_available_min += $total_avaliable_min;
                                    $vendor_total_sam_qty += ($totalPMin);
                                    $vendor_total_lineEff += $lineEff;
                                    $line_count++;
                                    $totalProducedMin += $totalPMin;
                    
                                    if($totalPMin > 0 && $total_qty > 0) {
                                        $vendor_total_avgSAM = number_format(($totalProducedMin/$vendor_total_qty),2);
                                    }
                    
                                    if($totalProducedMin > 0 && $vendor_total_available_min > 0) {
                                        $tlf = number_format(($totalProducedMin/$vendor_total_available_min) * 100,2);
                                    }
                                @endphp
                                <tr style="border: 1px solid;">
                                    @if($index === 0)
                                        <td rowspan="{{ count($rows) + 1 }}" style="vertical-align: middle;"><b>{{ $ac_short_name }}</b></td>
                                    @endif
                                    <td nowrap>{{ $row->line_name }}</td>
                                    <td nowrap style="text-align: right;">{{ sprintf('%.2f', $avgSAM) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',$totalHelpers) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',$totalWorkers) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',$totalHelpers + $totalWorkers) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',$total_qty) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',$total_avaliable_min) }}</td>
                                    <td nowrap style="text-align: right;">{{ money_format('%!.0n',round($totalPMin)) }}</td>
                                    <td nowrap style="text-align: right;">{{ $lineEff }}</td>
                                </tr>
                            @endforeach
                    
                            <tr style="background-color: #f0f0f0; font-weight: bold; border: 1px solid;">
                                <td nowrap colspan="1" style="text-align: center;">Total</td>
                                <td nowrap style="text-align: right;">{{ sprintf('%.2f', $vendor_total_avgSAM) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n',$vendor_total_helpers) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n',$vendor_total_workers) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n',$vendor_total_helpers + $vendor_total_workers) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n',$vendor_total_qty) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n', $vendor_total_available_min) }}</td>
                                <td nowrap style="text-align: right;">{{ money_format('%!.0n',round($vendor_total_sam_qty)) }}</td>
                                <td nowrap style="text-align: right;">{{ round($tlf) }}</td>
                            </tr>
                    
                            @php
                                // Accumulate overall totals
                                $overall_total_helpers += $vendor_total_helpers;
                                $overall_total_workers += $vendor_total_workers;
                                $overall_total_manpower += ($vendor_total_helpers + $vendor_total_workers);
                                $overall_total_qty += $vendor_total_qty;
                                $overall_total_available_min += $vendor_total_available_min;
                                $overall_total_sam_qty += $vendor_total_sam_qty;
                                $overall_totalProducedMin += $totalProducedMin;
                                $overall_total_lines += $line_count;
                            @endphp
                    
                        @endforeach
                    </tbody>
                    
                    @php
                        $overall_total_avgSAM = ($overall_total_qty > 0) ? number_format($overall_totalProducedMin / $overall_total_qty, 2) : 0;
                        $overall_tlf = ($overall_total_available_min > 0) ? number_format(($overall_totalProducedMin / $overall_total_available_min) * 100, 2) : 0;
                    @endphp
                    
                    <tfoot>
                        <tr style="background-color: #024a8e; color:#fff; font-weight: bold; border: 1px solid;">
                            <td colspan="2" style="text-align: right;">Grand Total</td>
                            <td style="text-align: right;">{{ sprintf('%.2f', $overall_total_avgSAM) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',$overall_total_helpers) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',$overall_total_workers) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',$overall_total_manpower) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',$overall_total_qty) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',$overall_total_available_min) }}</td>
                            <td style="text-align: right;">{{ money_format('%!.0n',round($overall_total_sam_qty)) }}</td>
                            <td style="text-align: right;">{{ round($overall_tlf) }}</td>
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
<!-- end row -->
<script> 
</script>
@endsection