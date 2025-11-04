@extends('layouts.master') 
@section('content')   
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
@endif
<style>
    .text-right{
        text-align:right;
    }
</style>
<!-- end page title --> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                     <h4 class="mb-sm-0 font-size-18">Employee Wise Detail Report</h4> 
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-3">
                  <label><b>From Date : </b></lable> {{date('d-m-Y',strtotime($from_date))}}
               </div>
               <div class="col-md-2">
                  <label><b>To Date : </b></lable> {{date('d-m-Y',strtotime($to_date))}}
               </div>
               <div class="col-md-3">
                  <label><b>Employee Code : </b></lable>{{isset($dailyProductionList[0]->employeeCode) ? $dailyProductionList[0]->employeeCode : ""}}
               </div>
               <div class="col-md-4">
                   @php
                       $employeeData = isset($dailyProductionList[0]->employeeName) ? $dailyProductionList[0]->employeeName : ""; 
                       if($employeeData != "")
                       {
                            $employee = explode(")",$dailyProductionList[0]->employeeName);
                            $employeeName = $employee[1];
                       }
                       else
                       { 
                            $employeeName = "";
                       } 
                   @endphp
                  <label><b>Employee Name : </b></lable>{{ $employeeName }}
               </div>
            </div> 
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr.No.</th>
                     <th>Date</th>
                     <th>Bundle Track Code</th>  
                     <th>Style Name</th>  
                     <th>Operation Name</th>
                     <th>KDPL No</th>
                     <th>Pieces</th>
                     <th>Rate</th>
                     <th>Amount</th> 
                  </tr>
               </thead>
               <tbody> 
                @php
                    $srno = 1;
                    $total_pieces = 0;
                    $total_amount = 0;
                @endphp
                @foreach($dailyProductionList as $row)
                  <tr>
                     <td>{{$srno++}}</td>
                     <td>{{$row->dailyProductionEntryDate}}</td>
                     <td>{{$row->bundle_track_code}}</td>
                     <td>{{$row->mainstyle_name}}</td>
                     <td>{{$row->operation_name}}</td>
                     <td>{{$row->sales_order_no}}</td>
                     <td class="text-right">{{$row->total_stiching_qty}}</td>
                     <td class="text-right">{{money_format('%!i',round($row->rate,2))}}</td>
                     <td class="text-right">{{money_format('%!i',round($row->total_amount,2))}}</td>
                  </tr> 
                @php 
                    $total_pieces += $row->total_stiching_qty;
                    $total_amount += $row->total_amount;
                @endphp
                @endforeach
               </tbody>
               <tfoot>
                   <tr>
                       <th></th>
                       <th></th>
                       <th></th>
                       <th></th>
                       <th></th>
                       <th class="text-right">Total</th>
                       <th class="text-right">{{$total_pieces}}</th>
                       <th></th>
                       <th class="text-right">{{money_format('%!i',round($total_amount,2))}}</th>
                   </tr>
               </tfoot>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 
   $(document).on('click','#DeleteRecord',function(e) {
   
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
     //alert(Route);
    
    //alert(data);
      if (confirm("Are you sure you want to Delete this Record?") == true) {
    $.ajax({
           url: Route,
           type: "DELETE",
            data: {
            "id": id,
            "_method": 'DELETE',
             "_token": token,
             },
           
           success: function(data){
   
              //alert(data);
           location.reload();
   
           }
   });
   }
   
   });
</script>               
@endsection