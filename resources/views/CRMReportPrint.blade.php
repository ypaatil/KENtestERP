@extends('layouts.master') 
@section('content')   
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<!-- end page title -->
<style>
    .text-right
    {
        text-align: right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">CRM Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">CRM Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
             <div class=""> <!-- Add this wrapper -->
                 <table id="dt" class="table table-bordered nowrap w-100">
                    <thead> 
                       <tr> 
                          <th>Inquiry No</th>
                          <th>Inquiry Received Date</th>
                          <th>Buyer</th>
                          <th>Type</th>
                          <th>Location</th>
                          <th>Contact Person</th>
                          <th>Contact Number</th>
                          <th>Email Address</th>
                          <th>Office Address</th>
                          <th>Pictures</th>
                          <th>Product Link if Any</th>
                          <th>Brand</th>
                          <th>Style Name</th>
                          <th>Style Descriptions</th>
                          <th>Fabric details</th>
                          <th>Category</th>
                          <th>Gender</th>
                          <th>Size Range</th>
                          <th>Projected Qty</th>
                          <th>Target price (INR)</th>
                          <th>Currency</th>
                          <th>Value in Cr.</th>
                          <th>Opportunity status</th>
                          <th>Remarks</th>
                       </tr>
                    </thead>
                    <tbody> 
                         @php
                             $srno = 1;
                         @endphp
                         @foreach($CRMMasterData as $row)
                         <tr> 
                            <td>OP{{$row->opportunity_id}}/{{$row->opportunity_detail_id}}</td>
                            <td>{{date("d-m-Y", strtotime($row->opportunity_date))}}</td>
                            <td>{{$row->buyer_name}}</td>
                            <td>{{$row->order_group_name}}</td>
                            <td>{{$row->state_name}}</td>
                            <td>{{$row->contactName}}</td>
                            <td>{{$row->contactNo}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->street_name}}</td>
                            <td><img src="{{ URL::asset('uploads/Opportunity/'.$row->product_image)}}" width="100" height="60"></td>
                            <td>{{$row->product_url}}</td>
                            <td>{{$row->buyer_brand}}</td>
                            <td>{{$row->style_name}}</td>
                            <td>{{$row->style_description}}</td>
                            <td>{{$row->fabric_details}}</td>
                            <td>{{$row->mainstyle_name}}</td>
                            <td>{{$row->gender_name}}</td>
                            <td>{{$row->size_range}}</td>
                            <td class="text-right">{{money_format("%!.0n",($row->quantity))}}</td>
                            <td class="text-right">{{money_format("%!.2n",($row->fob_rate_inr))}}</td>
                            <td>{{$row->currency_name}}</td>
                            <td class="text-right">{{money_format("%!.2n",($row->total_amount_inr/10000000))}}</td>
                            <td>{{$row->opportunity_stage_name}}</td>
                            <td>{{$row->remark}}</td>
                         </tr>
                         @endforeach
                    </tbody>
                 </table>
             </div> <!-- Close table-responsive -->
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
        $(document).ready(function() {
            $('#dt').DataTable({
                responsive: false, 
                scrollX: true, 
                dom: 'Bfrtip',  
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: 'Copy'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: 'Print'
                    }
                ]
            });
        });
</script>
@endsection
