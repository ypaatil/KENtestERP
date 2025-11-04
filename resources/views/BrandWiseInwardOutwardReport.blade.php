@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<style>
    .hide
    {
        display:none;
    }
    .text-right
    {
        text-align:right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Brand Wise Inward Outward Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Brand Wise Inward Outward Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/BrandWiseInwardOutwardReport" method="GET">
              <div class="row">  
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div>  
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/BrandWiseInwardOutwardReport" class="btn btn-warning">Clear</a>
                  </div>
              </div>
          </form>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered">
                  <thead>
                    <tr style="text-align:center">
                      <th nowrap>Sr. No.</th>
                      <th nowrap>Brand</th>
                      <th nowrap class="text-center">Inward Qty</th>
                      <th nowrap class="text-center">Inward Value</th>
                      <th nowrap class="text-center">Sales Qty</th> 
                      <th nowrap class="text-center">Sales Value</th> 
                      <th nowrap>Balance Qty</th> 
                    </tr>
                  </thead>
                  <tbody> 
                    @php 
                        $srno = 1;
                        $totalInwardQty = 0;
                        $totalInwardValue = 0;
                        $totalOutletQty = 0;
                        $totalOutletValue = 0;
                        $totalBalance = 0;
                    @endphp
                    @foreach($OutletSaleData as $row) 
                      <tr>
                        <td class="text-center">{{$srno++}}</td>
                        <td>{{$row->brand_name}}</td>
                        <td class="text-right">{{money_format('%!.0n',$row->inward_qty)}}</td>
                        <td class="text-right">{{money_format('%!.2n',$row->inward_value)}}</td> 
                        <td class="text-right">{{money_format('%!.0n',$row->outlet_qty)}}</td>
                        <td class="text-right">{{money_format('%!.2n',$row->outlet_value)}}</td> 
                        <td class="text-right">{{money_format('%!.0n',$row->inward_qty - $row->outlet_qty)}}</td> 
                      </tr> 
                      @php
                        $totalInwardQty += $row->inward_qty;
                        $totalInwardValue += $row->inward_value;
                        $totalOutletQty += $row->outlet_qty;
                        $totalOutletValue += $row->outlet_value;
                        $totalBalance += $row->inward_qty - $row->outlet_qty;
                      @endphp
                    @endforeach
                  </tbody> 
                  <tfoot>
                      <tr>
                        <th class="text-center"></th>
                        <th class="text-right">Total : </th>
                        <th class="text-right">{{money_format('%!.0n',$totalInwardQty)}}</th>
                        <th class="text-right">{{money_format('%!.2n',$totalInwardValue)}}</th> 
                        <th class="text-right">{{money_format('%!.0n',$totalOutletQty)}}</th>
                        <th class="text-right">{{money_format('%!.2n',$totalOutletValue)}}</th> 
                        <th class="text-right">{{money_format('%!.0n',$totalBalance)}}</th> 
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
<script>
    // if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
    //     $('#datatable-buttons').DataTable().clear().destroy();
    // }
    
    // $('#datatable-buttons').DataTable({
    //     responsive: true,
    //     paging: true,
    //     searching: true,
    //     ordering: true
    // });

</script>
@endsection