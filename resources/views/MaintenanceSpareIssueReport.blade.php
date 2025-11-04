@extends('layouts.master') 
@section('content')   
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
         <h4 class="mb-sm-0 font-size-18">Maintenance - Spares - Issue Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Maintenance - Spares - Issue Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('MaintenanceSpareIssueReport')}}" method="GET" enctype="multipart/form-data">
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
                                    <option value="{{$row->spare_item_code}}"  {{ $row->spare_item_code == $spare_item_code ? 'selected="selected"' : '' }} >{{$row->item_name}}({{$row->dimension}})</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-sm-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/MaintenanceSpareIssueReport" class="btn btn-danger w-md">Clear</a>
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
             <button onclick="exportTableToExcel('datatable-buttons', 'Spare_Item_Report')" class="btn btn-warning w-md">Export to Excel</button>
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                     <tr style="text-align:center">
                        <th nowrap>Sr No</th>
                        <th nowrap>Issue Type</th>
                        <th nowrap>Issue Date</th>
                        <th nowrap>Issue No</th>
                        <th nowrap>PO No</th> 
                        <th nowrap>GRN No</th>
                        <th nowrap>From Location</th>
                        <th nowrap>To Location</th>
                        <th>Supplier</th> 
                        <th nowrap>Item Code</th>
                        <th nowrap>Item Description</th>
                        <th>Part Name/ Dimensions</th>
                        <th>Issue Quantity</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Value</th> 
                     </tr>
                  </thead>
                  <tbody>
                    @php $srno = 1; @endphp
                    @foreach($MaintenanceSparesItemData as $row) 
                        <tr style="background-color:#e8f0fe; font-weight:bold;">
                            <td colspan="16">
                                Spare Item Code: {{ $row->spare_item_code }} - {{ $row->item_name }}
                            </td>
                        </tr>
                        @php
                           $MaintenanceSparesData = DB::select("SELECT 'M/c Outward' as type,
                                                materialoutwardmaster.materialOutwardCode, 
                                                materialoutwardmaster.materialOutwardDate, 
                                                materialoutwarddetails.spare_item_code, 
                                                unit_master.unit_name, 
                                                spare_item_master.item_name, 
                                                spare_item_master.item_description, 
                                                spare_item_master.dimension, 
                                                classification_master.class_name,
                                                SUM(DISTINCT materialoutwarddetails.item_qty) as item_qty,
                                                materialInwardDetail.item_rate, 
                                                materialInwardDetail.po_code, 
                                                materialInwardDetail.materiralInwardCode, 
                                                '-' as from_loc, 
                                                location_master.location as to_loc, 
                                                ledger_master.ac_short_name
                                            FROM 
                                                materialoutwardmaster 
                                            INNER JOIN 
                                                materialoutwarddetails ON materialoutwarddetails.materialOutwardCode = materialoutwardmaster.materialOutwardCode 
                                            INNER JOIN 
                                                materialInwardMaster ON materialInwardMaster.materiralInwardCode = materialoutwarddetails.materiralInwardCode
                                            INNER JOIN 
                                                materialInwardDetail ON materialInwardDetail.materiralInwardCode = materialInwardMaster.materiralInwardCode
                                            INNER JOIN 
                                                spare_item_master ON spare_item_master.spare_item_code = materialoutwarddetails.spare_item_code
                                            INNER JOIN 
                                                unit_master ON unit_master.unit_id = spare_item_master.unit_id
                                            INNER JOIN 
                                                classification_master ON classification_master.class_id = spare_item_master.class_id
                                            INNER JOIN 
                                                location_master ON location_master.loc_id = materialoutwardmaster.loc_id
                                            INNER JOIN 
                                                ledger_master ON ledger_master.ac_code = materialInwardMaster.Ac_code 
                                            WHERE 
                                                materialoutwarddetails.spare_item_code = '".$row->spare_item_code."' 
                                                AND materialoutwardmaster.delflag = 0 ".$filter." 
                                            GROUP BY 
                                                  materialoutwarddetails.spare_item_code,
                                                  materialoutwardmaster.materialOutwardCode,
                                                  materialoutwardmaster.materialOutwardDate
                                                  
                                            UNION
                                        
                                            SELECT 'Transfer' as type,
                                                materialTransferFromMaster.materialTransferFromCode as materialOutwardCode,
                                                materialTransferFromMaster.materialTransferFromDate as materialOutwardDate,
                                                materialTransferFromDetails.spare_item_code, 
                                                unit_master.unit_name, 
                                                spare_item_master.item_name, 
                                                spare_item_master.item_description, 
                                                spare_item_master.dimension, 
                                                classification_master.class_name,
                                                SUM(DISTINCT materialTransferFromDetails.item_qty) AS item_qty, 
                                                materialInwardDetail.item_rate, 
                                                materialInwardDetail.po_code, 
                                                materialInwardDetail.materiralInwardCode, 
                                                LM1.location as from_loc,  
                                                LM2.location as to_loc, 
                                                ledger_master.ac_short_name
                                            FROM 
                                                materialTransferFromMaster 
                                            INNER JOIN 
                                                materialTransferFromDetails ON materialTransferFromDetails.materialTransferFromCode = materialTransferFromMaster.materialTransferFromCode
                                            LEFT JOIN 
                                                materialInwardMaster ON materialInwardMaster.materiralInwardCode = materialTransferFromDetails.materiralInwardCode
                                            LEFT JOIN 
                                                materialInwardDetail ON materialInwardDetail.materiralInwardCode = materialInwardMaster.materiralInwardCode
                                            INNER JOIN 
                                                spare_item_master ON spare_item_master.spare_item_code = materialTransferFromDetails.spare_item_code
                                            INNER JOIN 
                                                unit_master ON unit_master.unit_id = spare_item_master.unit_id
                                            INNER JOIN 
                                                classification_master ON classification_master.class_id = spare_item_master.class_id
                                            LEFT JOIN 
                                                location_master as LM1 ON LM1.loc_id = materialTransferFromMaster.from_loc_id
                                            LEFT JOIN 
                                                location_master as LM2 ON LM2.loc_id = materialTransferFromMaster.to_loc_id
                                            LEFT JOIN 
                                                ledger_master ON ledger_master.ac_code = materialInwardMaster.Ac_code
                                            WHERE 
                                                materialTransferFromDetails.spare_item_code = '".$row->spare_item_code."' 
                                                AND materialTransferFromMaster.delflag = 0 ".$filter1."
                                            GROUP BY 
                                                  materialTransferFromDetails.spare_item_code,
                                                  materialTransferFromDetails.materialTransferFromCode,
                                                  materialTransferFromDetails.materialTransferFromDate
                                               ");
                    
                            $groupTotalQty = 0;
                            $groupTotalAmount = 0;
                        @endphp
                    
                        @foreach($MaintenanceSparesData as $rows)
                            @php
                                $amount = $rows->item_qty * $rows->item_rate;
                                $groupTotalQty += $rows->item_qty;
                                $groupTotalAmount += $amount;
                            @endphp
                            <tr>
                                <td style="text-align:center; white-space:nowrap"> {{ $srno++ }} </td>
                                <td style="white-space:nowrap"> {{ $rows->type ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->materialOutwardDate ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->materialOutwardCode ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->po_code ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->materiralInwardCode ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->from_loc ?? '-' }}</td>
                                <td style="white-space:nowrap"> {{ $rows->to_loc ?? '-' }}</td>
                                <td style="white-space:nowrap"> {{ $rows->ac_short_name ?? '-' }} </td>
                                <td> {{ $rows->spare_item_code }} </td>
                                <td> {{ $rows->item_description }} </td>
                                <td> {{ $rows->dimension }} </td>
                                <td style="text-align:right;"> {{ number_format($rows->item_qty, 0) }}</td>
                                <td> {{ $rows->unit_name }} </td>
                                <td style="text-align:right;"> {{ number_format($rows->item_rate, 2) }} </td>
                                <td style="text-align:right;"> {{ number_format($amount, 2) }} </td>
                            </tr>
                        @endforeach
                        <tr class="group-total" style="background-color:#f0f8ff; font-weight:bold;">
                            <td colspan="12" style="text-align:right;">Total :</td>
                            <td style="text-align:right;">{{ number_format($groupTotalQty, 0) }}</td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;">{{ number_format($groupTotalAmount, 2) }}</td>
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
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>

    function exportTableToExcel(tableID, filename = '') 
    {
        let table = document.getElementById(tableID);
        let wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    
        XLSX.writeFile(wb, filename ? `${filename}.xlsx` : 'export.xlsx');
    }

</script>

@endsection