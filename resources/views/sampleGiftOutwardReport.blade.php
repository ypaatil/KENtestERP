@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sample and Gift Outward Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sample and Gift Outward Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body"> 
                <form action="/sampleGiftOutwardReport" method="GET" enctype="multipart/form-data">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="fromDate" class="form-label">From date</label>
                            <input type="date" name="fromDate" class="form-control" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}" required> 
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" name="toDate" class="form-control" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}" required>
                        </div>
                    </div> 
                    <div class="col-sm-6">
                        <label for="formrow-inputState" class="form-label"></label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-md">Search</button>
                            <a href="/sampleGiftOutwardReport" class="btn btn-danger w-md">Cancel</a>
                        </div>
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
               <table  id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                  <thead>
                     <tr>
                        <th style="text-align: center;">Sr No.</th>
                        <th style="text-align: center;">DC No.</th>
                        <th style="text-align: center;">DC Type</th>
                        <th style="text-align: center;">DC Date</th>
                        <th style="text-align: center;">Party Name</th>
                        <th style="text-align: center;">To Loaction</th>
                        <th style="text-align: center;">Issued Quantity</th>
                        <th style="text-align: center;">Remark</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                        $srno = 1;
                     @endphp
                     @foreach($SaleTransactionMasterList as $row)    
                     <tr>
                        <td  nowrap>{{ $srno++ }}</td>
                        <td  nowrap>{{ $row->sale_code }}</td>
                        <td  nowrap>{{ $row->sales_head_name }}</td>
                        <td  nowrap  style="text-align: center;">{{ date("d-m-Y", strtotime($row->sale_date)) }}</td>
                        <td  nowrap>{{ $row->sent_through }}</td>
                        <td  nowrap>{{ $row->address }}</td>
                        <td style="text-align: right;">{{ money_format("%!.0n",$row->total_qty) }}</td>
                        <td>{{ $row->narration }}</td>
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