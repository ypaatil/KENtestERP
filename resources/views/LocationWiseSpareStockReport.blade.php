@extends('layouts.master') 
@section('content')  
@php 
    ini_set('memory_limit', '10G');
    setlocale(LC_MONETARY, 'en_IN');  
@endphp   
<style>
   tfoot {
        display: table-header-group;
    }
    .text-right {
        text-align: right;
    }
    .table-warning {
        background-color: #fff3cd;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Location Wise Spare Stock Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Location Wise Spare Stock Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row"> 
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/LocationWiseSpareStockReport" method="GET">
                  <div class="row">
                      <div class="col-md-2">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" value="{{$from_date }}">
                      </div>  
                      <div class="col-md-2">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $to_date }}">
                      </div> 
                      <div class="col-md-2">
                            <label for="spare_item_code" class="form-label">Material Name</label>
                            <select name="spare_item_code" class="form-control select2" id="spare_item_code">
                                <option value="">--Select--</option>
                                @foreach($itemList as $items)
                                     <option value="{{$items->spare_item_code}}"  {{ $items->spare_item_code == $spare_item_code ? 'selected="selected"' : '' }} >{{$items->item_name}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="location_id" class="form-label">Location</label>
                            <select name="location_id" class="form-control select2" id="location_id">
                                <option value="">--Select--</option>
                                @foreach($locationList as $locations)
                                     <option value="{{$locations->loc_id}}"  {{ $locations->loc_id == $location_id ? 'selected="selected"' : '' }} >{{$locations->location}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="materiralInwardCode" class="form-label">GRN No.</label>
                            <select name="materiralInwardCode" class="form-control select2" id="materiralInwardCode">
                                <option value="">--Select--</option>
                                @foreach($GRNList as $grn)
                                     <option value="{{$grn->materiralInwardCode}}"  {{ $grn->materiralInwardCode == $materiralInwardCode ? 'selected="selected"' : '' }} >{{$grn->materiralInwardCode}}</option>
                                @endforeach
                            </select> 
                      </div>  
                      <div class="col-md-2 mt-4 text-center"> 
                            <button type="submit" class="btn btn-primary" aria-label="Search Button">Search</button>
                            <a href="/LocationWiseSpareStockReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div> 
   <div class="col-12">
      <div class="card">
         <div class="card-body table-responsive">
              <table id="tbl" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>Sr No</th>
                     <th>GRN No.</th>
                     <th>GRN Date</th>
                     <th>Material Name</th>
                     <th>Type</th>
                     <th>Qty</th>
                     <th>Rate</th> 
                     <th>Amount</th>
                     <th>Locaton</th>
                     <th>Aging</th>
                  </tr>
               </thead>
               <tbody>
                   @php
                        use Carbon\Carbon;
                   @endphp
                   @foreach($classificationList as $class)
                   @php
                        $srno = 1;
                    
                        $MaterialInwardData = DB::select("SELECT materialInwardDetail.materiralInwardCode, materialInwardDetail.materiralInwardDate, materialInwardDetail.item_rate,
                            spare_item_master.item_name,spare_item_master.spare_item_code, location_master.location,location_master.loc_id,materialInwardDetail.location_id as from_loc_id, '0' as to_loc_id 
                            FROM materialInwardDetail
                            INNER JOIN spare_item_master ON spare_item_master.spare_item_code = materialInwardDetail.spare_item_code INNER JOIN location_master ON location_master.loc_id = materialInwardDetail.location_id 
                            WHERE spare_item_master.class_id = ".$class->class_id." ".$filter."
                            GROUP BY materialInwardDetail.materiralInwardCode
                        
                            UNION ALL
                        
                            SELECT 
                                materialTransferFromInwardDetails.materiralInwardCode, materialTransferFromInwardDetails.materialTransferFromInwardDate as materiralInwardDate, materialInwardDetail.item_rate, 
                                spare_item_master.item_name,spare_item_master.spare_item_code, location_master.location,location_master.loc_id,materialTransferFromInwardDetails.from_loc_id,materialTransferFromInwardDetails.to_loc_id
                            FROM materialTransferFromInwardDetails
                            INNER JOIN location_master 
                                ON location_master.loc_id = materialTransferFromInwardDetails.to_loc_id
                            INNER JOIN location_master as LM1
                                ON LM1.loc_id = materialTransferFromInwardDetails.from_loc_id
                            INNER JOIN materialInwardDetail 
                                ON materialInwardDetail.materiralInwardCode = materialTransferFromInwardDetails.materiralInwardCode
                            INNER JOIN spare_item_master 
                                ON spare_item_master.spare_item_code = materialTransferFromInwardDetails.spare_item_code 
                            WHERE spare_item_master.class_id = ".$class->class_id." ".$filter." GROUP BY materialTransferFromInwardDetails.materiralInwardCode");
                            

                   @endphp
                   <tr><td><b>Category Name : </b></td><td colspan="10"><b>{{$class->class_name}}</b></td></tr>
                  @foreach($MaterialInwardData as $row)
                  @php
                  
                        $loc_id = $row->from_loc_id;
                        $to_loc_id = $row->to_loc_id;
                        $materiralInwardCode = $row->materiralInwardCode;
                        $spare_item_code = $row->spare_item_code;
                        
                        $date = $row->materiralInwardDate;
                        $pastDate = Carbon::parse($date);
                        $today = Carbon::now();
                    
                        $diffInDays = $today->diffInDays($pastDate);
                        
                        $stockData = DB::select("SELECT (
                                                (SELECT IFNULL(SUM(item_qty), 0) 
                                                 FROM materialInwardDetail 
                                                 WHERE location_id = ? AND materiralInwardCode = ? AND spare_item_code = ?) 
                                                - 
                                                (SELECT IFNULL(SUM(item_qty), 0) 
                                                 FROM materialoutwarddetails 
                                                 WHERE loc_id = ? AND materiralInwardCode = ? AND spare_item_code = ?) 
                                                - 
                                                (SELECT IFNULL(SUM(item_qty), 0) 
                                                 FROM materialTransferFromInwardDetails 
                                                 WHERE from_loc_id = ? AND materiralInwardCode = ? AND spare_item_code = ?) 
                                                + 
                                                (SELECT IFNULL(SUM(item_qty), 0) 
                                                 FROM materialTransferFromInwardDetails 
                                                 WHERE to_loc_id = ? AND materiralInwardCode = ? AND spare_item_code = ?)
                                            ) as stock", [
                                                $row->loc_id, $materiralInwardCode, $spare_item_code,
                                                $row->loc_id, $materiralInwardCode, $spare_item_code,
                                                $row->loc_id, $materiralInwardCode, $spare_item_code,
                                                $row->loc_id, $materiralInwardCode, $spare_item_code
                                            ]);

 
                        $stock = $stockData[0]->stock;
                   @endphp
                  <tr>
                      <td>{{$srno++}}</td>
                      <td>{{$row->materiralInwardCode}}</td>
                      <td>{{$row->materiralInwardDate}}</td>
                      <td>{{$row->item_name}}</td>
                      <td>-</td>
                      <td>{{abs($stock)}}</td>
                      <td>{{$row->item_rate}}</td> 
                      <td>{{abs($stock * $row->item_rate)}}</td>
                      <td>{{$row->location}}</td>
                      <td class="text-right">{{$diffInDays}}</td>
                  </tr>
                  @endforeach
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
<script>
  
</script>
@endsection
