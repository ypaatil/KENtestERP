@extends('layouts.master') 
@section('content')   
<style>
   .text-right
   {
        text-align:right;
   }
   .no-wrap
   {
   white-space: nowrap;
   }
</style>
@php
setlocale(LC_MONETARY, 'en_IN');   
ini_set('memory_limit', '10G'); 
@endphp
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Fabric Cutting WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Fabric Cutting WIP Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
             <form action="FabricCuttingWIP" method="GET">
               <div class="row">
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
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
                   <div class="col-sm-5">
                      <label for="formrow-inputState" class="form-label"></label>
                      <div class="form-group">
                         <button type="submit" class="btn btn-primary w-md">Search</button>
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
            <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 ">
               <thead>
                  <tr style="text-align:center;">
                     <th nowrap>Date</th>
                     <th nowrap>Fabric Opening (mtr.)</th>
                     <th nowrap>Fabric Inward In Cutting Dept.(mtr.)</th>
                     <th nowrap>Fabric Used In Cutting Dept.(mtr.)</th>
                     <th nowrap>Fabric Outward From Cutting Dept.(mtr.)</th>
                     <th nowrap>WIP (mtr.)</th> 
                  </tr>
               </thead>
               <tbody>
                @foreach($Dates as $key => $dates)
                    @php
                        if ($key == 0) {
                            // Opening WIP before the first date
                            $inward_before = DB::selectOne("
                                SELECT IFNULL(SUM(receive_meter), 0) AS inward
                                FROM fabric_inward_cutting_department_details 
                                INNER JOIN fabric_inward_cutting_department_master 
                                    ON fabric_inward_cutting_department_master.ficd_code = fabric_inward_cutting_department_details.ficd_code
                                WHERE fabric_inward_cutting_department_details.ficd_date < ? 
                                  AND fabric_inward_cutting_department_master.vendorId = ?
                            ", [$dates, $vendorId])->inward;
                            
                            $outward_before = DB::selectOne("
                                SELECT IFNULL(SUM(outward_meter), 0) AS outward
                                FROM fabric_outward_cutting_department_details 
                                INNER JOIN fabric_outward_cutting_department_master 
                                    ON fabric_outward_cutting_department_master.focd_code = fabric_outward_cutting_department_details.focd_code
                                WHERE fabric_outward_cutting_department_details.focd_date < ? 
                                  AND fabric_outward_cutting_department_master.vendorId = ?
                            ", [$dates, $vendorId])->outward;
                
                            $used_before = DB::selectOne("
                                SELECT IFNULL(SUM(fabric_used), 0) AS used
                                FROM cut_panel_grn_master WHERE cpg_date < ? AND vendorId = ?
                            ", [$dates, $vendorId])->used;
                            
                            $openingwip = $inward_before - ($outward_before + $used_before);
                        }
                
                        // Inward for this specific date
                        $inward = DB::selectOne("
                            SELECT IFNULL(SUM(receive_meter), 0) AS inward
                            FROM fabric_inward_cutting_department_details 
                            INNER JOIN fabric_inward_cutting_department_master 
                                ON fabric_inward_cutting_department_master.ficd_code = fabric_inward_cutting_department_details.ficd_code
                            WHERE fabric_inward_cutting_department_details.ficd_date = ? 
                              AND fabric_inward_cutting_department_master.vendorId = ?
                        ", [$dates, $vendorId])->inward;
                
                        // Outward for this specific date
                        $outward = DB::selectOne("
                            SELECT IFNULL(SUM(outward_meter), 0) AS outward
                            FROM fabric_outward_cutting_department_details 
                            INNER JOIN fabric_outward_cutting_department_master 
                                ON fabric_outward_cutting_department_master.focd_code = fabric_outward_cutting_department_details.focd_code
                            WHERE fabric_outward_cutting_department_details.focd_date = ? 
                              AND fabric_outward_cutting_department_master.vendorId = ?
                        ", [$dates, $vendorId])->outward;
                
                
                        $used = DB::selectOne("
                                SELECT IFNULL(SUM(fabric_used), 0) AS used
                                FROM cut_panel_grn_master WHERE cpg_date = ? AND vendorId = ?
                            ", [$dates, $vendorId])->used;
                            
                        // Closing WIP
                        $closingwip = $openingwip + $inward - ($outward + $used);
                    @endphp
                
                    <tr>
                        <td>{{ date("d-M-Y", strtotime($dates)) }}</td>
                        <td class="text-right">{{ number_format($openingwip, 2) }}</td>
                        <td class="text-right">{{ number_format($inward, 2) }}</td>
                        <td class="text-right">{{ number_format($used, 2) }}</td>
                        <td class="text-right">{{ number_format($outward, 2) }}</td>
                        <td class="text-right">{{ number_format($closingwip, 2) }}</td>
                    </tr>
                
                    @php
                        // Next day's opening
                        $openingwip = $closingwip;
                    @endphp
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
<script type="text/javascript">
     $(document).ready(function () {
        $('#datatable').DataTable({
            dom: 'Bfrtip',
            pageLength: 50, 
            buttons: [
                { extend: 'copy', className: 'btn btn-primary' },
                { extend: 'excel', className: 'btn btn-success' },
                { extend: 'pdf', className: 'btn btn-danger' },
                { extend: 'print', className: 'btn btn-info' }
            ]
        });
    });

</script> 
@endsection