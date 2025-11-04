@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); ini_set('memory_limit', '1G'); @endphp
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
         <h4 class="mb-sm-0 font-size-18">Fabric Stock Cutting WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Fabric Stock Cutting WIP Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row"> 
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/FabricStockCuttingWIP" method="GET">
                  <div class="row">  
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{$fromDate ?? date('Y-m-01')}}">
                         </div>
                      </div>
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">Date</label>
                            <input type="date" class="form-control" name="toDate" id="toDate" value="{{$toDate ?? date('Y-m-d')}}">
                         </div>
                      </div>  
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">Vendor</label>
                            <select name="vendorId" id="vendorId" class="form-control select2">
                                <option value="">--Select--</option>
                                @foreach($vendorData as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $vendorId ? 'selected="selected"' : '' }}</option>{{$row->ac_short_name}}</option>
                                @endforeach
                            </select>
                         </div>
                      </div>  
                      <div class="col-md-6 mt-4"> 
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/FabricStockCuttingWIP" class="btn btn-warning">Clear</a>
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
                     <table id="datatable-buttons" class="table table-bordered nowrap w-100">
                         <thead>
                              <tr style="background-color:#eee;"> 
                                   <th>Sr. No.</th>
                                   <th>Date</th>
                                   <th>Sales Order No</th>
                                   <th>CPO Code</th>
                                   <th>Vendor Name</th>
                                   <th>Fabric Item Code</th>
                                   <th>Fabric Code</th>
                                   <th>Garment Color</th>
                                   <th>Opening Mtr</th>
                                   <th>Fabic Recived Cutting Dept.</th> 
                                   <th>Cutting Pcs</th>
                                   <th>Cons.</th> 
                                   <th>Utilize</th> 
                                   <th>WIP</th> 
                              </tr>
                         </thead>
                         <tbody>
                            @php
                                $srno = 1;
                            @endphp
                            @foreach($FabricStockCuttingWIPData as $row)  
                            @php
                                $CutPanelData = DB::SELECT("SELECT IFNULL(SUM(size_qty),0) as cutting_qty FROM cut_panel_grn_size_detail2 WHERE cpg_date = '".$row->cpg_date."'  AND item_code = ".$row->item_code." 
                                     AND color_id = ".$row->color_id."  AND vpo_code = '".$row->vpo_code."'");
                                
                               $FabricInwardCuttingData = DB::SELECT("SELECT IFNULL(SUM(fabric_inward_cutting_department_details.receive_meter), 0) as receive_meter FROM fabric_inward_cutting_department_details
                                      INNER JOIN fabric_inward_cutting_department_master ON fabric_inward_cutting_department_master.ficd_code = fabric_inward_cutting_department_details.ficd_code 
                                      INNER JOIN vendor_purchase_order_detail ON vendor_purchase_order_detail.vpo_code = fabric_inward_cutting_department_master.cutting_po_no 
                                      WHERE fabric_inward_cutting_department_details.ficd_date = '".$row->cpg_date."' AND fabric_inward_cutting_department_details.item_code = ".$row->item_code."  
                                      AND vendor_purchase_order_detail.color_id = ".$row->color_id." AND fabric_inward_cutting_department_master.cutting_po_no = '".$row->vpo_code."'");
                                      
                                
                                
                               $FabricInwardCuttingData1 = DB::SELECT("SELECT IFNULL(SUM(fabric_inward_cutting_department_details.receive_meter), 0) as receive_meter FROM fabric_inward_cutting_department_details
                                      INNER JOIN fabric_inward_cutting_department_master ON fabric_inward_cutting_department_master.ficd_code = fabric_inward_cutting_department_details.ficd_code 
                                      INNER JOIN vendor_purchase_order_detail ON vendor_purchase_order_detail.vpo_code = fabric_inward_cutting_department_master.cutting_po_no 
                                      WHERE fabric_inward_cutting_department_details.ficd_date < '".$row->cpg_date."' AND fabric_inward_cutting_department_details.item_code = ".$row->item_code."  
                                      AND vendor_purchase_order_detail.color_id = ".$row->color_id." AND fabric_inward_cutting_department_master.cutting_po_no = '".$row->vpo_code."'");
                                      
    
                                $received_cutting_meter = isset($FabricInwardCuttingData[0]->receive_meter) ? $FabricInwardCuttingData[0]->receive_meter : 0;
                                $opening_meter = isset($FabricInwardCuttingData1[0]->receive_meter) ? $FabricInwardCuttingData1[0]->receive_meter : 0;
                                $cutting_pcs = isset($CutPanelData[0]->cutting_qty) ? $CutPanelData[0]->cutting_qty : 0;
                                
                            @endphp
                            <tr>
                               <td nowrap>{{ $srno++ }}</td>
                               <td nowrap>{{ date("d-m-Y", strtotime($row->cpg_date))}}</td>
                               <td nowrap>{{$row->sales_order_no}}</td>
                               <td nowrap>{{$row->vpo_code}}</td>
                               <td nowrap>{{$row->vendorName}}</td>
                               <td nowrap>{{$row->item_code}}</td>
                               <td nowrap>{{$row->item_name}}</td>
                               <td nowrap>{{$row->color_name}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($opening_meter))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($received_cutting_meter))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($cutting_pcs))}}</td>
                               <td nowrap class="text-right">{{$row->consumption}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($cutting_pcs * $row->consumption))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",($received_cutting_meter- ($cutting_pcs * $row->consumption) + $opening_meter))}}</td>
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
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
<script>  

    $(document).ready(function()
    { 

    }); 
   </script>
@endsection