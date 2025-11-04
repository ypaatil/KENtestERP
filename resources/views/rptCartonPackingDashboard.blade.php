@extends('layouts.master') 
@section('content')   
@php 
    ini_set('memory_limit', '10G');
    setlocale(LC_MONETARY, 'en_IN'); 
@endphp                
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
<!--<div class="row">-->
<!--   <div class="col-md-3">-->
<!--      <div class="card mini-stats-wid" style="background-color:#152d9f;" >-->
<!--         <div class="card-body">-->
<!--            <div class="d-flex">-->
<!--               <div class="flex-grow-1">-->
<!--                  <p class="  fw-medium" style="color:#fff;">No. of Orders</p>-->
<!--                  <h4 class="mb-0" style="color:#fff;">  </h4>-->
<!--               </div>-->
<!--               <div class="flex-shrink-0 align-self-center">-->
<!--                  <div class="  avatar-sm rounded-circle bg-primary" style="background-color:#152d9f;">-->
<!--                     <span class="avatar-title" style="background-color:#152d9f;">-->
<!--                     <i class="bx bx-copy-alt font-size-24"></i>-->
<!--                     </span>-->
<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--   <div class="col-md-3">-->
<!--      <div class="card mini-stats-wid" style="background-color:#556ee6;">-->
<!--         <div class="card-body">-->
<!--            <div class="d-flex">-->
<!--               <div class="flex-grow-1">-->
<!--                  <p class="  fw-medium" style="color:#fff;" >Order Qty(Lakh)</p>-->
<!--                  <h4 class="mb-0" style="color:#fff;" >  </h4>-->
<!--               </div>-->
<!--               <div class="flex-shrink-0 align-self-center ">-->
<!--                  <div class="avatar-sm rounded-circle bg-primary  ">-->
<!--                     <span class="avatar-title  " style="background-color:#556ee6;" >-->
<!--                     <i class="bx bx-purchase-tag-alt font-size-24"></i>-->
<!--                     </span>-->
<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--   <div class="col-md-3">-->
<!--      <div class="card mini-stats-wid" style="background-color:#008116;">-->
<!--         <div class="card-body">-->
<!--            <div class="d-flex">-->
<!--               <div class="flex-grow-1">-->
<!--                  <p class="  fw-medium" style="color:#fff;">Shipped Qty(Lakh)</p>-->
<!--                  <h4 class="mb-0" style="color:#fff;">   </h4>-->
<!--               </div>-->
<!--               <div class="flex-shrink-0 align-self-center">-->
<!--                  <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--                     <span class="avatar-title  " style="background-color:#008116;">-->
<!--                     <i class="bx bx-archive-in font-size-24"></i>-->
<!--                     </span>-->
<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--   <div class="col-md-3">-->
<!--      <div class="card mini-stats-wid" style="background-color:#f79733;">-->
<!--         <div class="card-body">-->
<!--            <div class="d-flex">-->
<!--               <div class="flex-grow-1">-->
<!--                  <p class="  fw-medium" style="color:#fff;">Open Qty(Lakh)</p>-->
<!--                  <h4 class="mb-0" style="color:#fff;"> </h4>-->
<!--               </div>-->
<!--               <div class="flex-shrink-0 align-self-center">-->
<!--                  <div class="avatar-sm rounded-circle bg-primary  " >-->
<!--                     <span class="avatar-title  " style="background-color:#f79733;">-->
<!--                     <i class="bx bx-archive-in font-size-24"></i>-->
<!--                     </span>-->
<!--                  </div>-->
<!--               </div>-->
<!--            </div>-->
<!--         </div>-->
<!--      </div>-->
<!--   </div>-->
<!--</div>-->
                          
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-bordered   nowrap w-100">
                   <thead>
                    <tr>
                      <th nowrap>Sr No</th>
                      <th nowrap>CPKI Code</th>
                      <th nowrap>From Carton No</th>
                      <th nowrap>To Carton No</th>
                      <th nowrap>Sale Head</th>
                      <th nowrap>Invoice No</th>
                      <th nowrap>Invoice Date</th>
                      <th nowrap>Sales Order No</th> 
                      <th nowrap>Garment Color</th>  
                      <th nowrap>Size</th>    
                      <th nowrap>Size Qty</th>  
                      <th nowrap>Order Rate</th>  
                    </tr>
                  </thead>
                  <tbody id="cartonData"></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<input type="hidden" id="sales_order_no" value="{{$sales_order_no}}">
<input type="hidden" id="job_status_id" value="{{$job_status_id}}">
<input type="hidden" id="fdate" value="{{$fdate}}">
<input type="hidden" id="tdate" value="{{$tdate}}">
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script>
    $(function()
    {
        loadTable();
    });
    
    function loadTable()
    { 
        var sales_order_no = $("#sales_order_no").val();
        var job_status_id = $("#job_status_id").val();
        var fdate = $("#fdate").val();
        var tdate = $("#tdate").val();
        
        $.ajax({
           type: "GET",
           dataType:"json",
           url: "{{ route('LoadCartonPackingReport') }}",
           serverSide: true,
           data:{'sales_order_no' :sales_order_no,'job_status_id':job_status_id,'fdate':fdate,'tdate':tdate},
           success: function(response)
           {
               $('#datatable-buttons').DataTable().destroy(); 
               $('#cartonData').html(response.html);
               $('#datatable-buttons').DataTable({
                    dom: 'Bfrtip', 
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print' 
                    ]
               }); 
           }
        });
    }
</script>
@endsection