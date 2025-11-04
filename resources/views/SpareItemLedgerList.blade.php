@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Maintenance - Spares - Stock List</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Maintenance - Spares - Stock List</li>
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
               <table id="dt" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>Sr No</th> 
                        <th nowrap>Item Code</th>
                        <th nowrap>Item Name</th>
                        <th nowrap>Item Description</th>
                        <th nowrap>UOM</th>
                        <th>Balance Qty</th> 
                     </tr>
                  </thead>
                  <tbody>  
                     @php
                        $srno = 1;
                     @endphp
                     @php 
                            $MaintenanceSparesDatas = DB::select("SELECT spare_item_master.*,unit_master.unit_name,
                                          IFNULL(SUM(materialInwardDetail.item_qty), 0)
                                          - (
                                            SELECT IFNULL(SUM(materialoutwarddetails.item_qty), 0)
                                            FROM materialoutwarddetails
                                            WHERE materialoutwarddetails.spare_item_code = materialInwardDetail.spare_item_code
                                           )
                                          - (
                                            SELECT IFNULL(SUM(materialTransferFromDetails.item_qty), 0)
                                            FROM materialTransferFromDetails
                                            WHERE materialTransferFromDetails.spare_item_code = materialInwardDetail.spare_item_code
                                          )
                                          + (
                                            SELECT IFNULL(SUM(materialTransferFromInwardDetails.item_qty), 0)  
                                            FROM materialTransferFromInwardDetails
                                            WHERE materialTransferFromInwardDetails.spare_item_code = materialInwardDetail.spare_item_code
                                          ) AS stock
                                        FROM materialInwardDetail
                                        INNER JOIN materialInwardMaster 
                                          ON materialInwardMaster.materiralInwardCode = materialInwardDetail.materiralInwardCode
                                        INNER JOIN spare_item_master 
                                          ON spare_item_master.spare_item_code = materialInwardDetail.spare_item_code
                                        INNER JOIN unit_master 
                                          ON unit_master.unit_id = materialInwardDetail.unit_id
                                        WHERE materialInwardMaster.delflag = 0  GROUP BY materialInwardDetail.spare_item_code");
                                      
                            $from_date = isset($request->fromDate) ? $request-> fromDate : date('Y-m-01');
                            $to_date = isset($request->toDate) ? $request-> toDate : date('Y-m-d');
        
                    @endphp 
                    @foreach($MaintenanceSparesDatas as $row)
                     <tr>
                        <td style="text-align:center; white-space:nowrap"> {{ $srno++ }} </td> 
                        <td style="white-space:nowrap"> {{$row->spare_item_code}} </td>
                        <td style="white-space:nowrap"> {{$row->item_name}} </td>
                        <td style="white-space:nowrap"> {{$row->item_description}} </td>
                        <td style="white-space:nowrap"> {{$row->unit_name}} </td>
                        <td style="white-space:nowrap;text-align: right;"><a href="./SpareItemLedgerReport?fromDate={{$from_date}}&toDate={{$to_date}}&spare_item_code={{$row->spare_item_code}}" target="_blank" >{{money_format("%!.0n",$row->stock)}}</a></td>  
                     </tr> 
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
    
   $(document).ready(function () {
        const tableSelector = '#dt';
    
        setTimeout(function () {
            $(tableSelector).DataTable({
                order: [[3, 'asc']],
                columnDefs: [{ targets: 3, type: 'date' }],
                dom: 'Bfrtip', 
                buttons: [
                    'copy', 'excel', 'csv', 'pdf', 'print'
                ]
            });
        }, 0);
    });


</script>
@endsection