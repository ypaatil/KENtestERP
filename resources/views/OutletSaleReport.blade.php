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
         <h4 class="mb-sm-0 font-size-18">Outlet Sale Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Outlet Sale Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/OutletSaleReport" method="GET">
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
                        <a href="/OutletSaleReport" class="btn btn-warning">Clear</a>
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
                        <th nowrap>Barcode No.</th>
                        <th nowrap>Payment Option</th> 
                        <th nowrap>Employee/Other</th>
                        <th nowrap>Customer Name</th>
                        <th nowrap>Mobile No</th> 
                        <th nowrap>Buyer Name</th> 
                        <th nowrap>Buyer Brand</th> 
                        <th nowrap>Main Style Category</th> 
                        <th nowrap>Garment Color</th>
                        <th nowrap>Size</th>
                        <th nowrap>Qty</th>
                        <th nowrap>Rate</th>
                        <th nowrap>Value</th>
                        <th nowrap>Disc %</th>
                        <th nowrap>Disc Amount</th>
                        <th nowrap>GST %</th>
                        <th nowrap>GST Amount</th>
                        <th nowrap>Total Amount</th>
                     </tr> 
                  </thead>
                  <tbody>
                     @foreach($OutletSaleData as $row) 
                     @php
                     
                        if($row->employee_type == 1)
                        {
                           $employee_type = 'Employee';     
                        }
                        else
                        {
                            $employee_type = 'Other';     
                        } 
                     
                     @endphp
                             <tr>
                                <td style="white-space:nowrap" class="text-left"> {{ $row->bill_date  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->outlet_sale_id  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->scan_barcode  }} </td>
                                <td style="white-space:nowrap" class="text-left"> {{ $row->payment_option_name  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $employee_type  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->fullName ? $row->fullName : $row->other_customer }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->mobile_no  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->ac_short_name1 ? $row->ac_short_name1 : $row->ac_short_name2  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->brand_name ?? '' }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->mainstyle_name1 ? $row->mainstyle_name1 : $row->mainstyle_name2 }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->color_name1 ? $row->color_name1 : $row->color_name2  }} </td> 
                                <td style="white-space:nowrap" class="text-left"> {{ $row->size_name  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{  money_format("%!.0n", $row->qty)  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{ number_format($row->rate,2)  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{ number_format($row->qty * $row->rate,2) }} </td>  
                                <td style="white-space:nowrap" class="text-right"> {{ $row->discount  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{  number_format($row->discount_amount,2)  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{ $row->gst_per  }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{  number_format($row->gst_amount,2) }} </td> 
                                <td style="white-space:nowrap" class="text-right"> {{  number_format($row->total_amount,2) }} </td>    
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