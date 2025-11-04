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
         <h4 class="mb-sm-0 font-size-18">Maintenance - Spares - GRN Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Maintenance - Spares - GRN Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <div class="card">
         <div class="card-body"> 
              <form action="{{route('MaintenanceSparesGRNReport')}}" method="GET" enctype="multipart/form-data">
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
                                    <option value="{{$row->spare_item_code}}" {{ $row->spare_item_code == $spare_item_code ? 'selected="selected"' : '' }} >{{$row->item_name}}({{$row->dimension}})</option>
                                @endforeach
                            </select>
                         </div>
                       </div> 
                       <div class="col-sm-3">
                          <label for="formrow-inputState" class="form-label"></label>
                          <div class="form-group">
                             <button type="submit" class="btn btn-primary w-md">Search</button>
                             <a href="/MaintenanceSparesGRNReport" class="btn btn-danger w-md">Clear</a>
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
                        <th nowrap>Id</th>
                        <th nowrap>GRN Date</th>
                        <th nowrap>PO No</th>
                        <th nowrap>GRN No</th>
                        <th nowrap>Location</th>
                        <th>Supplier</th>
                        <th>Supplier Invoice No</th> 
                        <th nowrap>Item Code</th>
                        <th nowrap>Item Description</th>
                        <th>Part Name/ Dimensions</th>
                        <th>Total Quantity</th>
                        <th>UOM</th>
                        <th>Rate</th>
                        <th>Value</th> 
                     </tr>
                  </thead>
                  <tbody>
                    @php $srno = 1; @endphp
                    @foreach($MaintenanceSparesItemData as $row) 
                        <tr style="background-color:#e8f0fe; font-weight:bold;">
                            <td colspan="17">
                                Spare Item Code: {{ $row->spare_item_code }} - {{ $row->item_name }}
                            </td>
                        </tr>
                        @php
                            $MaintenanceSparesData = DB::select("SELECT materialInwardMaster.*,materialInwardDetail.spare_item_code, unit_master.unit_name, spare_item_master.item_name, spare_item_master.item_description,spare_item_master.dimension, class_name,
                                sum(materialInwardDetail.item_qty) as item_qty , materialInwardDetail.item_rate, materialInwardDetail.unit_id,location_master.location,ledger_master.ac_short_name as ac_short_name
                                FROM materialInwardMaster 
                                inner join materialInwardDetail on materialInwardDetail.materiralInwardCode=materialInwardMaster.materiralInwardCode 
                                inner join spare_item_master on spare_item_master.spare_item_code=materialInwardDetail.spare_item_code
                                inner join unit_master on unit_master.unit_id=materialInwardDetail.unit_id
                                inner join classification_master on classification_master.class_id=spare_item_master.class_id
                                inner join location_master on location_master.loc_id=materialInwardMaster.location_id
                                inner join ledger_master on ledger_master.ac_code=materialInwardMaster.Ac_code 
                                WHERE materialInwardDetail.spare_item_code='".$row->spare_item_code."' AND materialInwardMaster.delflag=0 
                                group by materialInwardMaster.po_code, materialInwardDetail.spare_item_code");
                    
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
                                <td style="white-space:nowrap"> {{ $rows->sr_no ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->materiralInwardDate ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->po_code ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->materiralInwardCode ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->location ?? '-' }}</td>
                                <td style="white-space:nowrap"> {{ $rows->ac_short_name ?? '-' }} </td>
                                <td style="white-space:nowrap"> {{ $rows->invoice_no ?? '-' }} </td>
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
                            <td colspan="11" style="text-align:right;">Total :</td>
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