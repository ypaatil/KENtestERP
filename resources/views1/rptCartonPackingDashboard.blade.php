@extends('layouts.master') 
@section('content')   
@php setlocale(LC_MONETARY, 'en_IN'); @endphp                
<!-- end page title -->
 
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Sales Order: Open</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Sales Order : Open</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#152d9f;" >
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>
                  <h4 class="mb-0" style="color:#fff;">  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">
                     <span class="avatar-title" style="background-color:#152d9f;">
                     <i class="bx bx-copy-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#556ee6;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;" >  </h4>
               </div>
               <div class="flex-shrink-0 align-self-center ">
                  <div class="avatar-sm rounded-circle bg-primary  ">
                     <span class="avatar-title  " style="background-color:#556ee6;" >
                     <i class="bx bx-purchase-tag-alt font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#008116;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;">   </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#008116;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card mini-stats-wid" style="background-color:#f79733;">
         <div class="card-body">
            <div class="d-flex">
               <div class="flex-grow-1">
                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>
                  <h4 class="mb-0" style="color:#fff;"> </h4>
               </div>
               <div class="flex-shrink-0 align-self-center">
                  <div class="avatar-sm rounded-circle bg-primary  " >
                     <span class="avatar-title  " style="background-color:#f79733;">
                     <i class="bx bx-archive-in font-size-24"></i>
                     </span>
                  </div>
               </div>
            </div>
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
              <tr>
              <th nowrap>SrNo</th>
              <th nowrap>CPKI Code</th>
              <th nowrap>From Carton No</th>
              <th nowrap>To Carton No</th>
              <th nowrap>Sales Order No</th> 
              <th nowrap>Garment Color</th> 
                 @foreach ($SizeDetailList as $sz) 
                   
                      <th>{{$sz->size_name}}</th>
                       
                   @endforeach
                   
                  <th nowrap>Total Qty</th>
                   </tr>
              </thead>
              <tbody> 
        @php   $no=1; $totalAmt=0; $totalQty=0;@endphp
          @foreach ($CartonPackingList as $row) 
        
          <tr> 
       
          <td>{{$no}}</td> 
          <td>{{$row->cpki_code}}</td> 
          <td>{{$row->from_carton_no}}</td> 
          <td>{{$row->to_carton_no}}</td> 
              <td>{{$row->sales_order_no}}</td> 
          <td>{{$row->color_name}}</td> 

          @if(isset($row->s1))  <td>{{$row->s1}}</td> @endif
          @if(isset($row->s2)) <td>{{$row->s2}}</td>@endif
          @if(isset($row->s3)) <td>{{$row->s3}}</td>@endif
          @if(isset($row->s4)) <td>{{$row->s4}}</td>@endif
          @if(isset($row->s5)) <td>{{$row->s5}}</td>@endif
          @if(isset($row->s6)) <td>{{$row->s6}}</td>@endif
          @if(isset($row->s7)) <td>{{$row->s7}}</td>@endif
          @if(isset($row->s8)) <td>{{$row->s8}}</td>@endif
          @if(isset($row->s9)) <td>{{$row->s9}}</td>@endif
          @if(isset($row->s10)) <td>{{$row->s10}}</td>@endif
          @if(isset($row->s11)) <td>{{$row->s11}}</td>@endif
          @if(isset($row->s12)) <td>{{$row->s12}}</td>@endif
          @if(isset($row->s13)) <td>{{$row->s13}}</td>@endif
          @if(isset($row->s14)) <td>{{$row->s14}}</td>@endif
          @if(isset($row->s15)) <td>{{$row->s15}}</td>@endif
          @if(isset($row->s16)) <td>{{$row->s16}}</td>@endif
          @if(isset($row->s17)) <td>{{$row->s17}}</td>@endif
          @if(isset($row->s18)) <td>{{$row->s18}}</td>@endif
          @if(isset($row->s19)) <td>{{$row->s19}}</td>@endif
         @if(isset($row->s20))  <td>{{$row->s20}}</td> @endif
          <td>{{$row->size_qty_total}}</td> 
            </tr>

          @php $no=$no+1; 
          
             
          $totalQty = $totalQty + $row->size_qty_total;
          
          
          
          @endphp
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