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
         <h4 class="mb-sm-0 font-size-18">Outlet Inward Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Reports</a></li>
               <li class="breadcrumb-item active">Outlet Inward Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/OutletStockReport" method="GET">
              <div class="row">  
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-3">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div>  
                   <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mainstyle_id" class="form-label">Main Style Category</label><br/>
                            <select name="mainstyle_id" class="select2"  id="" style="width:150px; height:30px;">
                                <option value="">--Select--</option>
                                @foreach($MainStyleList as  $row3)
                                    <option value="{{$row3->mainstyle_id}}" {{ $row3->mainstyle_id == $mainstyle_id ? 'selected="selected"' : '' }} >{{$row3->mainstyle_name}}</option>
                                @endforeach    
                            </select> 
                        </div>
                   </div>  
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/OutletStockReport" class="btn btn-warning">Clear</a>
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
                        <th nowrap>Barcode No</th> 
                        <th nowrap>Buyer Name</th> 
                        <th nowrap>Main Style Category</th> 
                        <th nowrap>Sub Style Category</th> 
                        <th nowrap>Garment Color</th>
                        <th nowrap>Size</th>
                        <th nowrap>Inward Qty</th>
                        <th nowrap>Outward Qty</th>
                        <th nowrap>Stock Qty</th>
                        <th nowrap>Rate</th>
                        <th nowrap>Value</th>
                     </tr> 
                  </thead>
                  <tbody>
                     @foreach($OutletInwardData as $row)    
                     @php
                            
                            $regularInward = DB::SELECT("SELECT SUM(size_qty) as inward FROM fg_location_transfer_inward_size_detail2 WHERE barcode = '".$row->barcode."' AND size_id = '".$row->size_id."' AND color_id = '".$row->color_id."' AND Ac_code = '".$row->Ac_code."'");
                            $openingInward = DB::SELECT("SELECT SUM(size_qty) as inward FROM fg_outlet_opening_size_detail2 WHERE barcode = '".$row->barcode."' AND size_id = '".$row->size_id."' AND color_id = '".$row->color_id."' AND Ac_code = '".$row->Ac_code."'");
                            
                            $inward = (isset($regularInward[0]->inward) ? $regularInward[0]->inward : 0) + (isset($openingInward[0]->inward) ? $openingInward[0]->inward : 0);
                            
                            $outwardData = DB::SELECT("SELECT SUM(qty) as outward FROM outlet_sale_detail WHERE scan_barcode = '".$row->barcode."' AND size_id = '".$row->size_id."'");
                             
                            $outward = isset($outwardData[0]->outward) ? $outwardData[0]->outward : 0;
                     @endphp
                         <tr>
                            <td style="white-space:nowrap" class="text-left"> {{ $row->barcode  }} </td>   
                            <td style="white-space:nowrap" class="text-left"> {{ $row->ac_short_name  }} </td> 
                            <td style="white-space:nowrap" class="text-left"> {{ $row->mainstyle_name  }} </td> 
                            <td style="white-space:nowrap" class="text-left"> {{ $row->substyle_name  }} </td>  
                            <td style="white-space:nowrap" class="text-left"> {{ $row->color_name  }} </td> 
                            <td style="white-space:nowrap" class="text-left"> {{ $row->size_name  }} </td> 
                            <td style="white-space:nowrap" class="text-right">{{ money_format("%!.0n", $inward)  }} </td> 
                            <td style="white-space:nowrap" class="text-left"> {{  money_format("%!.0n", $outward)  }} </td> 
                            <td style="white-space:nowrap" class="text-left"> {{ money_format("%!.0n", $inward - $outward)  }} </td> 
                            <td style="white-space:nowrap" class="text-right">{{ number_format($row->size_rate,2)  }} </td> 
                            <td style="white-space:nowrap" class="text-right">{{ number_format(($inward - $outward) * $row->size_rate,2) }} </td>  
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
@endsection