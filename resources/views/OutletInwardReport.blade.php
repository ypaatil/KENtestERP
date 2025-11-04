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
          <form action="/OutletInwardReport" method="GET">
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
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/OutletInwardReport" class="btn btn-warning">Clear</a>
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
                        <th nowrap>Date</th>
                        <th nowrap>Outward No</th>
                        <th nowrap>Sales Order No</th>
                        <th nowrap>Buyer Name</th> 
                        <th nowrap>Buyer Brand</th> 
                        <th nowrap>Main Style Category</th> 
                        <th nowrap>Sub Style Category</th> 
                        <th nowrap>Style Name</th>
                        <th nowrap>Style No</th>
                        <th nowrap>Style Description</th>
                        <th nowrap>From Location</th>
                        <th nowrap>To Location</th>
                        <th nowrap>Garment Color</th>
                        <th nowrap>Size</th>
                        <th nowrap>Qty</th>
                        <th nowrap>Rate</th>
                        <th nowrap>Value</th>
                     </tr> 
                  </thead>
                  <tbody>
                     @foreach($OutletInwardData as $row)    
                         @if($row->size_qty > 0)
                             <tr>
                                <td style="white-space:nowrap" class="text-left"> {{ $row->fglti_date  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->fglti_code  }} </td> 
                                <td style="white-space:nowrap" class="text-left" class="text-left"> {{ $row->sales_order_no  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->ac_short_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->brand_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->mainstyle_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->substyle_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->fg_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->style_no  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->style_description  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->from_loc  }} </td>  
                                <td style="white-space:nowrap" class="text-left"> {{ $row->to_loc  }} </td>      
                                <td style="white-space:nowrap" class="text-left"> {{ $row->color_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->size_name  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{  money_format("%!.0n", $row->size_qty)  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{ number_format($row->size_rate,2)  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{ number_format($row->size_qty * $row->size_rate,2) }} </td>  
                             </tr> 
                         @endif
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