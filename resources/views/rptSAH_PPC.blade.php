@extends('layouts.master') 
@section('content')   
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">SAH PPC Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">SAH PPC Report</li>
            </ol>
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
                     <th>Vendor Name</th>
                     <th class="text-center">Line No.</th>
                     <th class="text-center">Order No.</th>
                     <th class="text-center">SAM</th>
                     <th class="text-center">Available Min</th>
                     <th class="text-center">Month</th>
                     <th class="text-center">Value</th>
                     <th class="text-center">Booked Min</th>
                     <th class="text-center">Open Min</th>
                  </thead>
                  <tbody>
                      @foreach($SAHPPCList as $ppc)
                      @php 
                             $month = date('F', mktime(0, 0, 0, $ppc->month, 1));
                      @endphp
                        <tr>
                            <td>{{$ppc->ac_name}}</td>
                            <td class="text-center">{{$ppc->line_name}}</td>
                            <td class="text-center">{{$ppc->sales_order_no}}</td>
                            <td class="text-center">{{$ppc->sam}}</td>
                            <td class="text-center">{{$ppc->totalAvaliableMin}}</td>
                            <td class="text-center">{{$month}}</td>
                            <td class="text-center">{{$ppc->monthValue}}</td>
                            <td class="text-center">{{$ppc->bookedMin}}</td>
                            <td class="text-center">{{$ppc->openMin}}</td>
                        </tr>
                      @endforeach
                  </tbody>
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