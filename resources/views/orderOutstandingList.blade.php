@extends('layouts.master') 
@section('content')  
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<!-- end page title -->
<link rel="stylesheet" href="https://themesbrand.com/skote/layouts/assets/css/bootstrap.min.css">
<style>
    .hide{
        display:none;
    }
    .text-right
    {
        text-align:right;
    } 
    
    .success-checkmark:after 
    {
      content: '✔';
      position: absolute;
      left:4px; 
      top: 10px;
      width: 27px; 
      height: 26px;
      text-align: center;
      border: 1px solid #aaa;
      background: #0b0b0b;
      border-radius: 50%;
      box-shadow: inset 0 1px 3px rgba(0,0,0,.3)
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Order Outstanding</h4> 
      </div>
   </div>
</div> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body"> 
            <div class="table-responsive">
               <table id="outsourceTbl" data-order='[[ 1, "desc" ]]' data-page-length='10' class="table table-bordered nowrap w-100">
                  <thead>
                     <tr style="text-align:center;">
                        <th>Sr.No</th>
                        <th>Buyer PO No.</th>
                        <th>Order Qty</th>
                        <th>Dispatch Qty</th>
                        <th>Balance Qty</th>
                        <th>Ready Stock</th> 
                     </tr>
                  </thead>
                  <tbody>
                      @php
                        $srno = 1;
                        $total_order_qty = 0;
                        $total_dispatch_qty = 0;
                        $total_balance_qty = 0;
                        $total_ready_qty = 0;
                      @endphp
                      @foreach($orderOutStandingData as $row)  
                      <tr>
                          <td class="text-center">{{$srno++}}</td>
                          <td class="text-center"><a href="javascript:void(0);">{{$row->po_code}}</a></td>
                          <td class="text-right">{{money_format('%!i',$row->order_qty)}}</td>
                          <td class="text-right"><a href="javascript:void(0);">{{money_format('%!i',$row->dispatch_qty)}}</a></td>
                          <td class="text-right"><a href="javascript:void(0);">{{money_format('%!i',$row->order_qty - $row->dispatch_qty)}}</a></td>
                          <td class="text-right">{{money_format('%!i',$row->ready_qty)}}</td> 
                      </tr>
                      @php
                        $total_order_qty += $row->order_qty;
                        $total_dispatch_qty += $row->dispatch_qty;
                        $total_balance_qty += ($row->order_qty - $row->dispatch_qty);
                        $total_ready_qty += $row->ready_qty;
                      @endphp
                      @endforeach
                      <tr>
                            <th></th>
                            <th class="text-right">Total </th>
                            <th class="text-right">{{money_format('%!i',round($total_order_qty))}}</th>
                            <th class="text-right">{{money_format('%!i',$total_dispatch_qty)}}</th>
                            <th class="text-right">{{money_format('%!i',$total_balance_qty)}}</th>
                            <th class="text-right">{{money_format('%!i',$total_ready_qty)}}</th> 
                      </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body"> 
           <div class="row mb-3">
               <div class="col-md-2"><button class="btn btn-soft-warning waves-effect waves-light success-checkmark" onclick="LoadDailyProdDashboard(1);" style="width: 150px;font-size: 18px;">Running</button></div>
               <div class="col-md-1"><button class="btn btn-soft-danger waves-effect waves-light" onclick="LoadDailyProdDashboard(2);" style="width: 150px;font-size: 18px;">Closed</button></div>
           </div>
            <div id="loader">
                  <div class="col-md-12 mt-5" style="text-align:center;"><img src="/images/loading5.gif" width="300" height="200"></div>
            </div>
            <div class="table-responsive">
               <table id="DailyTbl" data-order='[[ 1, "desc" ]]' data-page-length='10' class="table table-bordered nowrap w-100">
                  <thead>
                     <tr style="text-align:center;">
                        <th nowrap>Buyer Order NO</th>
                        <th nowrap>KEN Sales Order No</th>
                        <th nowrap>Style Description</th>
                        <th nowrap>Style No</th>
                        <th nowrap>Style Images</th>
                        <th nowrap>Garment Color</th>
                        <th nowrap>Order Qty</th>
                        <th nowrap>CUT Qty</th>
                        <th nowrap>Production Qty</th>
                        <th nowrap>Pass Qty</th> 
                        <th nowrap>Samples</th> 
                        <th nowrap>Reject Qty</th> 
                        <th nowrap>Prod Balance Qty</th> 
                        <th nowrap>Pack Qty</th> 
                        <th nowrap>Packing Balance Qty</th> 
                        <th nowrap>Shipment Qty</th> 
                        <th nowrap>Shipment Bal Qty</th> 
                        <th nowrap>Cut to Ship</th> 
                        <th nowrap>Order to Ship</th> 
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script>
    $(function()
    {
          var table = $('#outsourceTbl').DataTable({
            ordering: true,
            "paging": true,
            "lengthChange": true,
            "pageLength": 10,
            bDestroy: true,
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true }
            ] 
        });
        LoadDailyProdDashboard(1);
    });
    
    function LoadDailyProdDashboard(row)
    { 
       
        $("#loader").removeClass('hide');
        $.ajax({
            dataType: "json",
            type: "GET",  
            data : { 'filter' : row},
            url: "{{ route('LoadDailyProdDashboard') }}",
            beforeSend: function() 
            { 
                $("#loader").removeClass('hide');
                $('#DailyTbl').dataTable({
                    "bDestroy": true
                }).fnDestroy();
        
            },
            success: function(data)
            { 
                $('tbody').html(data.html); 
                $(this).addClass('success-checkmark');
            },
            complete: function() 
            {
                $("#loader").addClass('hide');
                
                var table = $('#DailyTbl').DataTable({
                    ordering: true,
                    "paging": true,
                    "lengthChange": true,
                    "pageLength": 10,
                    bDestroy: true,
                    dom: 'lBfrtip',
                    buttons: [
                        { extend: 'copyHtml5', footer: true },
                        { extend: 'excelHtml5', footer: true },
                        { extend: 'csvHtml5', footer: true },
                        { extend: 'pdfHtml5', footer: true }
                    ] 
                });
            },
        });
    }
    
    $('.waves-light').click(function()
    {
         $('.waves-effect').removeClass('success-checkmark');
         $(this).addClass('success-checkmark');
    });
</script>
@endsection