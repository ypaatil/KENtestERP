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
         <h4 class="mb-sm-0 font-size-18">Washing Inward Outward Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Washing Inward Outward Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 

<div class="row">
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/WashingInwardOutwardReport" method="GET">
                  <div class="row"> 
                      <div class="col-md-3">
                         <div class="mb-3">
                            <label for="from_date" class="form-label">Date</label>
                            <input type="date" class="form-control" name="from_date" id="from_date" value="{{$from_date ? $from_date : date('Y-m-d')}}">
                         </div>
                      </div>
                      <div class="col-md-3"> 
                         <div class="mt-4">
                            <label class="form-label"></label>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="/WashingInwardOutwardReport" class="btn btn-warning">Clear</a>
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
                     <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                         <thead>
                                <tr style="background-color:#eee;"> 
                                   <th>Sr No</th>
                                   <th>Sales Order No</th>
                                   <th>Washing PO No</th>
                                   <th>PO Status</th> 
                                   <th>Stitching Vendor Name</th>  
                                   <th>Washing Vendor Name</th> 
                                   <th>Buyer Name</th>
                                   <th>Style Category</th>
                                   <th>Style No</th>
                                   <th>Garment Color</th>
                                   <th>Size</th>
                                   <th>Outward Qty</th>
                                   <th>Inward Qty</th>
                                   <th>Balance</th>
                                </tr>
                         </thead>
                         <tbody>
                            @php
                                $srno = 1;
                            @endphp
                            @foreach($WashList as $row)
                            @php
                               if($from_date !="")
                               {
                               
                                    $inwardData = DB::SELECT("SELECT sum(washing_inhouse_size_detail2.size_qty) as inward_qty
                                                FROM washing_inhouse_size_detail2  WHERE wash_date <= '".$from_date."' AND size_id=".$row->size_id." AND color_id=".$row->color_id." AND sales_order_no='".$row->sales_order_no."' AND vpo_code='".$row->vpo_code."'");
                                                
                                    $outwardData = DB::SELECT("SELECT sum(vendor_purchase_order_size_detail2.size_qty) as outward_qty,vendor_purchase_order_master.endflag,vendor_purchase_order_master.vpo_code 
                                                FROM vendor_purchase_order_size_detail2 
                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_size_detail2.vpo_code
                                                WHERE vendor_purchase_order_size_detail2.vpo_date <= '".$from_date."'
                                                AND vendor_purchase_order_master.process_id=4
                                                AND vendor_purchase_order_size_detail2.size_id=".$row->size_id." 
                                                AND vendor_purchase_order_size_detail2.color_id=".$row->color_id." 
                                                AND vendor_purchase_order_size_detail2.sales_order_no='".$row->sales_order_no."'
                                                AND vendor_purchase_order_size_detail2.vpo_code='".$row->vpo_code."'");
                               }
                               else
                               {
                                    $outwardData = DB::SELECT("SELECT sum(vendor_purchase_order_size_detail2.size_qty) as outward_qty,vendor_purchase_order_master.endflag,vendor_purchase_order_master.vpo_code
                                                FROM vendor_purchase_order_size_detail2 
                                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_size_detail2.vpo_code
                                                WHERE vendor_purchase_order_size_detail2.size_id=".$row->size_id." 
                                                AND vendor_purchase_order_master.process_id=4
                                                AND vendor_purchase_order_size_detail2.color_id=".$row->color_id." 
                                                AND vendor_purchase_order_size_detail2.sales_order_no='".$row->sales_order_no."' 
                                                AND vendor_purchase_order_size_detail2.vpo_code='".$row->vpo_code."'");
                                }
                                $inward_qty = isset($inwardData[0]->inward_qty) ? $inwardData[0]->inward_qty: 0;
                                $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty: 0;
                                $endflag = isset($outwardData[0]->endflag) ? $outwardData[0]->endflag: 0;
                                $vpo_code = isset($outwardData[0]->vpo_code) ? $outwardData[0]->vpo_code: "-";
                                if($endflag == 1)
                                {
                                    $po_status = 'Open';
                                }
                                else if($endflag == 2)
                                {
                                    $po_status = 'Close';
                                }
                                else
                                {
                                    $po_status = '';
                                }
                            @endphp
                                <tr>
                                   <td>{{$srno++}}</td>
                                   <td>{{$row->sales_order_no}}</td>
                                   <td>{{$vpo_code}}</td>
                                   <td>{{$po_status}}</td> 
                                   <td>{{$row->stiching_vendorName}}</td> 
                                   <td>{{$row->vendorName}}</td> 
                                   <td>{{$row->Ac_name}}</td> 
                                   <td>{{$row->mainstyle_name}}</td> 
                                   <td>{{$row->style_no}}</td> 
                                   <td>{{$row->color_name}}</td> 
                                   <td>{{$row->size_name}}</td> 
                                   <td class="text-right">{{$outward_qty}}</td> 
                                   <td class="text-right">{{$inward_qty}}</td> 
                                   <td class="text-right">{{$outward_qty - $inward_qty}}</td> 
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