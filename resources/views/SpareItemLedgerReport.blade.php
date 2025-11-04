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
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Maintenance - Spares - Stock Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Maintenance - Spares - Stock Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('SpareItemLedgerReport')}}" method="GET" enctype="multipart/form-data">
                   <div class="row">
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($from_date) ? $from_date : ""}}">
                         </div>
                       </div>
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($to_date) ? $to_date : ""}}">
                         </div>
                       </div>    
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="po_code" class="form-label">PO NO</label>
                            <select name="po_code" id="po_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($POList as $row)
                                    <option value="{{$row->pur_code}}" {{ $row->pur_code == $po_code ? 'selected="selected"' : '' }} >{{$row->pur_code}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                        <div class="col-md-2">
                         <div class="mb-3">
                            <label for="materiralInwardCode" class="form-label">GRN NO</label>
                            <select name="materiralInwardCode" id="materiralInwardCode" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($MaterialInwardList as $row)
                                    <option value="{{$row->materiralInwardCode}}" {{ $row->materiralInwardCode == $materiralInwardCode ? 'selected="selected"' : '' }} >{{$row->materiralInwardCode}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                        <div class="col-md-3">
                         <div class="mb-3">
                            <label for="location_id" class="form-label">Location</label>
                            <select name="location_id" id="location_id" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($LocationList as $row)
                                    <option value="{{$row->loc_id}}" {{ $row->loc_id == $location_id ? 'selected="selected"' : '' }} >{{$row->location}}</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                        <div class="col-md-3">
                         <div class="mb-3">
                            <label for="spare_item_code" class="form-label">Item Name</label>
                            <select name="spare_item_code" id="spare_item_code" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($itemlist as $row)
                                    <option value="{{$row->spare_item_code}}" {{ $row->spare_item_code == $spare_item_code ? 'selected="selected"' : '' }} >{{$row->item_name}}({{$row->spare_item_code}})</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-sm-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/SpareItemLedgerReport?clear=1" class="btn btn-danger w-md">Clear</a>
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
                        <th nowrap>Sr No</th> 
                        <th nowrap>Transaction Type</th>
                        <th nowrap>Transaction No.</th>
                        <th nowrap>Date</th>
                        <th nowrap>From/To</th>
                        <th>GRN No.</th>
                        <th nowrap>PO No.</th> 
                        <th nowrap>Supplier Name</th> 
                        <th>Receive Qty.</th>
                        <th>Issued Qty.</th>
                        <th>Stock Qty</th> 
                     </tr>
                  </thead>
                  <tbody>  
                     @php
                        $srno = 1;
                     @endphp
                     @php
                            $filter1 = '';
                            $filter2 = '';
                            $filter3 = '';
                            $filter4 = '';
                            $filter5 = '';
                            $filter6 = '';
                            $filter7 = '';
                            $filter8 = '';
                            
                            if($from_date != '' AND $to_date != '')
                            {
                                $filter1 .= " AND materialInwardDetail.materiralInwardDate BETWEEN '".$from_date."' AND '".$to_date."'";
                                $filter2 .= " AND materialoutwardmaster.materialOutwardDate BETWEEN '".$from_date."' AND '".$to_date."'";
                                $filter3 .= " AND materialTransferFromDetails.materialTransferFromDate BETWEEN '".$from_date."' AND '".$to_date."'";
                                $filter4 .= " AND materialTransferFromInward.materialTransferFromInwardDate BETWEEN '".$from_date."' AND '".$to_date."'";
                                
                                $filter5 .= " AND materialInwardDetail.materiralInwardDate < '".$from_date."'";
                                $filter6 .= " AND materialoutwarddetails.materialOutwardDate < '".$from_date."'";
                                $filter7 .= " AND materialTransferFromDetails.materialTransferFromDate < '".$from_date."'";
                                $filter8 .= " AND materialTransferFromInwardDetails.materialTransferFromInwardDate < '".$from_date."'";
                            }
                            
                            if($spare_item_code > 0)
                            {
                                $filter1 .= " AND materialInwardDetail.spare_item_code=".$spare_item_code;
                                $filter2 .= " AND materialoutwarddetails.spare_item_code=".$spare_item_code;
                                $filter3 .= " AND materialTransferFromDetails.spare_item_code=".$spare_item_code;
                                $filter4 .= " AND materialTransferFromInwardDetails.spare_item_code=".$spare_item_code; 
                                
                                $filter5 .= " AND materialInwardDetail.spare_item_code=".$spare_item_code;
                                $filter6 .= " AND materialoutwarddetails.spare_item_code=".$spare_item_code;
                                $filter7 .= " AND materialTransferFromDetails.spare_item_code=".$spare_item_code;
                                $filter8 .= " AND materialTransferFromInwardDetails.spare_item_code=".$spare_item_code; 
                            }
                     
                            if($po_code != '')
                            {
                                $filter1 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter2 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter3 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter4 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                
                                $filter5 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter6 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter7 .= " AND materialInwardDetail.po_code='".$po_code."'";
                                $filter8 .= " AND materialInwardDetail.po_code='".$po_code."'";
                            }
                     
                            if($materiralInwardCode != '')
                            {
                                $filter1 .= " AND materialInwardDetail.materiralInwardCode='".$materiralInwardCode."'";
                                $filter2 .= " AND materialoutwarddetails.materiralInwardCode='".$materiralInwardCode."'";
                                $filter3 .= " AND materialTransferFromDetails.materiralInwardCode='".$materiralInwardCode."'";
                                $filter4 .= " AND materialTransferFromInwardDetails.materiralInwardCode='".$materiralInwardCode."'";
                                
                                $filter5 .= " AND materialInwardDetail.materiralInwardCode='".$materiralInwardCode."'";
                                $filter6 .= " AND materialoutwarddetails.materiralInwardCode='".$materiralInwardCode."'";
                                $filter7 .= " AND materialTransferFromDetails.materiralInwardCode='".$materiralInwardCode."'";
                                $filter8 .= " AND materialTransferFromInwardDetails.materiralInwardCode='".$materiralInwardCode."'";
                            }
                            
                            if($location_id > 0)
                            {
                                $filter1 .= " AND materialInwardDetail.location_id=".$location_id;
                                $filter2 .= " AND materialoutwarddetails.loc_id=".$location_id;
                                $filter3 .= " AND materialTransferFromDetails.from_loc_id=".$location_id;
                                $filter4 .= " AND materialTransferFromInwardDetails.to_loc_id=".$location_id; 
                                
                                $filter5 .= " AND materialInwardDetail.location_id=".$location_id;
                                $filter6 .= " AND materialoutwarddetails.loc_id=".$location_id;
                                $filter7 .= " AND materialTransferFromDetails.from_loc_id=".$location_id;
                                $filter8 .= " AND materialTransferFromInwardDetails.to_loc_id=".$location_id; 
                            }
                            if($clear == 1)
                            {
                                $clear = 1;
                            }
                            else
                            {
                                $clear = 0;
                            }
                           
                             $MaintenanceSparesDatas = DB::select("SELECT 
                                        materialInwardDetail.materiralInwardCode, 
                                        materialInwardMaster.materiralInwardCode AS code,
                                        materialInwardMaster.materiralInwardDate AS date,
                                        location_master.location,
                                        materialInwardMaster.po_code,
                                        materialInwardDetail.item_qty AS item_qty,
                                        'GRN' AS type,
                                        '0' AS outward,
                                        ledger_master.ac_short_name as supplier_name
                                    FROM materialInwardDetail 
                                    INNER JOIN materialInwardMaster 
                                        ON materialInwardMaster.materiralInwardCode = materialInwardDetail.materiralInwardCode 
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = materialInwardMaster.Ac_code 
                                    INNER JOIN location_master 
                                        ON location_master.loc_id = materialInwardDetail.location_id 
                                    WHERE materialInwardMaster.delflag = ".$clear." ".$filter1."
                                    GROUP BY materialInwardMaster.materiralInwardCode, materialInwardMaster.materiralInwardDate, materialInwardMaster.po_code
                                
                                    UNION
                                
                                    SELECT 
                                        materialoutwarddetails.materiralInwardCode, 
                                        materialoutwardmaster.materialOutwardCode AS code,
                                        materialoutwardmaster.materialOutwardDate AS date,
                                        LC1.location,
                                        materialInwardDetail.po_code,
                                       '0' AS item_qty,
                                       'M/c Outward' AS type,
                                        materialoutwarddetails.item_qty AS outward,
                                        ledger_master.ac_short_name as supplier_name
                                    FROM materialoutwarddetails 
                                    INNER JOIN materialoutwardmaster 
                                        ON materialoutwardmaster.materialOutwardCode = materialoutwarddetails.materialOutwardCode 
                                    INNER JOIN materialInwardDetail 
                                        ON materialInwardDetail.materiralInwardCode = materialoutwarddetails.materiralInwardCode 
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = materialInwardDetail.Ac_code 
                                    INNER JOIN location_master as LC1 
                                        ON LC1.loc_id = materialoutwarddetails.loc_id 
                                    WHERE materialoutwardmaster.delflag = ".$clear." ".$filter2."
                                    GROUP BY materialoutwardmaster.materialOutwardCode, materialoutwardmaster.materialOutwardDate, materialInwardDetail.po_code
                                    
                                    UNION
                                
                                    SELECT 
                                        materialTransferFromDetails.materiralInwardCode, 
                                        materialTransferFromMaster.materialTransferFromCode AS code,
                                        materialTransferFromMaster.materialTransferFromDate AS date,
                                        LC2.location,
                                        materialInwardDetail.po_code,
                                       '0' AS item_qty,
                                       'Transfer' AS type,
                                        materialTransferFromDetails.item_qty AS outward,
                                        ledger_master.ac_short_name as supplier_name
                                    FROM materialTransferFromDetails 
                                    INNER JOIN materialTransferFromMaster 
                                        ON materialTransferFromMaster.materialTransferFromCode = materialTransferFromDetails.materialTransferFromCode 
                                    INNER JOIN materialInwardDetail 
                                        ON materialInwardDetail.materiralInwardCode = materialTransferFromDetails.materiralInwardCode 
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = materialInwardDetail.Ac_code 
                                    INNER JOIN location_master as LC2 
                                        ON LC2.loc_id = materialTransferFromDetails.from_loc_id 
                                    WHERE materialTransferFromMaster.delflag = ".$clear." ".$filter3."
                                    GROUP BY materialTransferFromMaster.materialTransferFromCode
                                    
                                    UNION
                                
                                    SELECT 
                                        materialTransferFromInwardDetails.materiralInwardCode, 
                                        materialTransferFromInward.materialTransferFromInwardCode AS code,
                                        materialTransferFromInward.materialTransferFromInwardDate AS date,
                                        LC3.location,
                                        materialInwardDetail.po_code,
                                        materialTransferFromInwardDetails.item_qty AS item_qty,
                                        'Transfer Inward' AS type,
                                        '0' AS outward,
                                        ledger_master.ac_short_name as supplier_name
                                    FROM materialTransferFromInwardDetails 
                                    INNER JOIN materialTransferFromInward 
                                        ON materialTransferFromInward.materialTransferFromInwardCode = materialTransferFromInwardDetails.materialTransferFromInwardCode 
                                    INNER JOIN materialInwardDetail 
                                        ON materialInwardDetail.materiralInwardCode = materialTransferFromInwardDetails.materiralInwardCode 
                                    INNER JOIN ledger_master 
                                        ON ledger_master.ac_code = materialInwardDetail.Ac_code 
                                    INNER JOIN location_master as LC3 
                                        ON LC3.loc_id = materialTransferFromInwardDetails.to_loc_id 
                                    WHERE materialTransferFromInward.delflag =".$clear." ".$filter4."
                                    GROUP BY materialTransferFromInward.materialTransferFromInwardCode, materialTransferFromInward.materialTransferFromInwardDate, materialInwardDetail.po_code");

                             //DB::enableQueryLog();
                             $MaintenanceSparesDatas1 = DB::select("SELECT 
                                                      IFNULL(SUM(materialInwardDetail.item_qty), 0)
                                                      - (
                                                        SELECT IFNULL(SUM(materialoutwarddetails.item_qty), 0)
                                                        FROM materialoutwarddetails
                                                        WHERE 1 ".$filter6."
                                                       )
                                                      - (
                                                        SELECT IFNULL(SUM(materialTransferFromDetails.item_qty), 0)
                                                        FROM materialTransferFromDetails
                                                        WHERE 1 ".$filter7."
                                                      )
                                                      + (
                                                        SELECT IFNULL(SUM(materialTransferFromInwardDetails.item_qty), 0)  
                                                        FROM materialTransferFromInwardDetails
                                                        WHERE 1 ".$filter8."
                                                      ) AS opening_stock
                                                    FROM materialInwardDetail
                                                    INNER JOIN materialInwardMaster 
                                                      ON materialInwardMaster.materiralInwardCode = materialInwardDetail.materiralInwardCode
                                                    INNER JOIN location_master 
                                                      ON location_master.loc_id = materialInwardDetail.location_id
                                                    WHERE materialInwardMaster.delflag = 0 ".$filter5);
                                    
                             //dd(DB::getQueryLog()); 
                             
                            if($clear != 1)
                            {
                                $opening_stock = isset($MaintenanceSparesDatas1[0]->opening_stock) ? $MaintenanceSparesDatas1[0]->opening_stock : 0; 
                            }
                            else
                            {
                                $opening_stock = 0;
                            }
                            
                            $total_inward = 0;
                            $total_outward = 0;
                            $total_stock = $opening_stock;
                    @endphp
                     <tr>
                        <td style="text-align:center; white-space:nowrap"></td> 
                        <td style="white-space:nowrap"> <b>Opening Stock ({{date("d-m-Y", strtotime("-1 day", strtotime($from_date)))}}) </b></td>
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap"></td> 
                        <td style="white-space:nowrap"></td> 
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap"></td>
                        <td style="white-space:nowrap; text-align: right;"><b>{{$opening_stock}}</b></td>
                     </tr>
                    @foreach($MaintenanceSparesDatas as $row)
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $srno++ }} </td> 
                        <td style="white-space:nowrap"> {{$row->type}} </td>
                        <td style="white-space:nowrap"> {{$row->code}} </td>
                        <td style="white-space:nowrap"> {{$row->date}} </td>
                        <td style="white-space:nowrap"> {{$row->location}} </td>
                        <td style="white-space:nowrap"> {{$row->materiralInwardCode}} </td> 
                        <td style="white-space:nowrap"> {{$row->po_code}} </td> 
                        <td style="white-space:nowrap"> {{$row->supplier_name}} </td> 
                        <td style="white-space:nowrap; text-align: right;"> {{money_format("%!.0n",$row->item_qty)}} </td>
                        <td style="white-space:nowrap; text-align: right;"> {{money_format("%!.0n",$row->outward) }} </td>
                        <td style="white-space:nowrap"></td>
                     </tr>
                     @php
                        $total_inward += $row->item_qty;
                        $total_outward += $row->outward;
                        $total_stock += $row->item_qty-$row->outward;
                       
                   
                     @endphp
                     @endforeach
                  </tbody>
                  <tfoot>
                      <tr>
                        <th style="text-align:center; white-space:nowrap"></td> 
                        <th style="white-space:nowrap"> </td>
                        <th style="white-space:nowrap"> </td>
                        <th style="white-space:nowrap"> </td>
                        <th style="white-space:nowrap"> </td>
                        <th style="white-space:nowrap"> </td> 
                        <th style="white-space:nowrap"> </td> 
                        <th style="white-space:nowrap; text-align: right;">Total</td> 
                        <th style="white-space:nowrap; text-align: right;">{{money_format("%!.0n",$total_inward)}}</td>
                        <th style="white-space:nowrap; text-align: right;">{{money_format("%!.0n",$total_outward)}}</td>
                        <th style="white-space:nowrap; text-align: right;">{{money_format("%!.0n",abs($total_stock))}}</td>
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
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>

    function exportTableToExcel(tableID, filename = '') 
    {
        let table = document.getElementById(tableID);
        let wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    
        XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'export.xlsx');
    }
    
    $(document).ready(function () 
    {
        const tableSelector = '#dt';
     
        setTimeout(function () {
            $(tableSelector).DataTable({
                order: [[3, 'asc']],
                columnDefs: [
                    { targets: 3, type: 'date' }
                ]
            });
        }, 0);
    });

</script>
@endsection