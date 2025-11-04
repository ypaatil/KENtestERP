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
    th
    {
        background-color:#024a8e!important;
        color:#fff;
    }
    td
    { 
        color:#000; 
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Outlet Sales Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Outlet Sales Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="col-md-12">
   <div class="card mini-stats-wid">
      <div class="card-body">
          <form action="/OutletSalesReport" method="GET">
              <div class="row">  
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="fromDate" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="fromDate" id="fromDate" value="{{ isset($fromDate) ? $fromDate : date('Y-m-01')}}">
                     </div>
                   </div>
                   <div class="col-md-2">
                     <div class="mb-3">
                        <label for="toDate" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="toDate" id="toDate" value="{{ isset($toDate) ? $toDate : date('Y-m-d')}}">
                     </div>
                   </div>  
                  <div class="col-md-6 mt-4"> 
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="/OutletSalesReport" class="btn btn-warning">Clear</a>
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
               <table id="dt" class="table table-bordered">
                  <thead>
                    <tr style="text-align:center">
                      <th nowrap>Sr. No.</th>
                      <th nowrap>Date</th>
                      <th nowrap class="text-center">Cash Sale</th>
                      <th nowrap class="text-center">UPI Sale</th>
                      <th nowrap class="text-center">Salary Deduction Sale</th>
                      <th nowrap class="text-center">Total Sale</th>  
                    </tr>
                  </thead>
                  <tbody> 
                    @php 
                        $srno = 1;
                        $totalCashSale = 0;
                        $totalUPISale = 0;
                        $totalDeductionSale = 0;
                        $totalSales = 0; 
                    @endphp
                    @foreach($OutletSaleData as $row) 
                      <tr>
                        <td class="text-center">{{$srno++}}</td> 
                        <td class="text-center">{{date("d-m-Y", strtotime($row->bill_date))}}</td>
                        <td class="text-right">{{money_format('%!.2n',$row->cash_sale)}}</td>
                        <td class="text-right">{{money_format('%!.2n',$row->upi_sale)}}</td> 
                        <td class="text-right">{{money_format('%!.2n',$row->dedection_sale)}}</td> 
                        <td class="text-right">{{money_format('%!.2n',($row->cash_sale+$row->upi_sale+$row->dedection_sale))}}</td> 
                      </tr> 
                      @php
                        $totalCashSale += $row->cash_sale;
                        $totalUPISale += $row->upi_sale;
                        $totalDeductionSale += $row->dedection_sale;
                        $totalSales += ($row->cash_sale+$row->upi_sale+$row->dedection_sale); 
                      @endphp
                    @endforeach
                  </tbody> 
                  <tfoot>
                      <tr>
                        <th class="text-center"></th>
                        <th class="text-right">Total : </th>
                        <th class="text-right">{{money_format('%!.2n',$totalCashSale)}}</th>
                        <th class="text-right">{{money_format('%!.2n',$totalUPISale)}}</th> 
                        <th class="text-right">{{money_format('%!.2n',$totalDeductionSale)}}</th> 
                        <th class="text-right">{{money_format('%!.2n',$totalSales)}}</th> 
                      </tr> 
                  </tfoot>
                </table>
            </div>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script> 
<script>
function loadDataTable() {
    // Destroy existing instance if exists
    if ($.fn.DataTable.isDataTable('#dt')) {
        $('#dt').DataTable().clear().destroy();
    }

    // Initialize DataTable again
    $('#dt').DataTable({
        dom: 'Bfrtip',
        destroy: true, // Ensure destroy mode
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                title: 'Stock Report',
                filename: 'Outlet_Sales_' + new Date().toISOString().slice(0,10),
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'Stock Report',
                filename: 'Outlet_Sales_' + new Date().toISOString().slice(0,10),
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'Stock Report',
                filename: 'Outlet_Sales_' + new Date().toISOString().slice(0,10),
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'copyHtml5',
                text: 'Copy',
                title: 'Stock Report',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                text: 'Print',
                title: 'Stock Report',
                exportOptions: { columns: ':visible' }
            }
        ]
    });
}

// Call once when page loads
$(document).ready(function() {
    loadDataTable();
});
</script>
@endsection