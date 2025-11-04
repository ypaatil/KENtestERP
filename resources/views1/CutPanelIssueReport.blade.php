@extends('layouts.master') 
@section('content')
@php
setlocale(LC_MONETARY, 'en_IN');  
@endphp   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="card"> 
       <div class="col-md-12 m-2">
          <form action="CutPanelIssueReport" method="GET" enctype="multipart/form-data"> 
          <input type="hidden" name="formFlag" id="formFlag" value="1">
                <div class="row">
                    <div class="col-md-2">
                         <div class="mb-3">
                            <label for="fromDate" class="form-label">From Date</label>
                            <input type="date" name="fromDate" id="fromDate" class="form-control" value="{{isset($_GET['fromDate']) ? $_GET['fromDate'] : ""}}">
                         </div>
                      </div> 
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="toDate" class="form-label">To Date</label>
                            <input type="date" name="toDate" id="toDate" class="form-control" value="{{isset($_GET['toDate']) ? $_GET['toDate'] : ""}}">
                         </div>
                      </div>
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="Ac_code" class="form-label">Buyer Name</label>
                            <select name="Ac_code" class="form-control select2" id="Ac_code" onchange="GetSalesOrderDataFromBuyerVendor();">
                               <option value="">--All--</option>
                               @foreach($buyerList as $row)
                               {
                                <option value="{{$row->ac_code}}" >{{$row->ac_name}}</option>
                               }
                               @endforeach
                            </select>
                         </div>
                      </div>
                      <div class="col-md-2">
                         <div class="mb-3">
                            <label for="vendorId" class="form-label">Vendor Name</label>
                            <select name="vendorId" class="form-control select2" id="vendorId" onchange="GetSalesOrderDataFromBuyerVendor();">
                               <option value="">--All--</option>
                               @foreach($vendorList as $row)
                               {
                                <option value="{{$row->ac_code}}">{{$row->ac_name}}</option>
                               }
                               @endforeach
                            </select>
                         </div>
                      </div>
                       <div class="col-md-2">
                         <div class="mb-3">
                            <label for="sales_order_no" class="form-label">Sales Order No.</label>
                            <select name="sales_order_no" class="form-control select2" id="sales_order_no"  >
                               <option value="">--All--</option>
                            </select>
                         </div>
                      </div>
                       <div class="col-sm-2">
                         <div class="mb-3">
                          <label class="form-label"></label>
                          <div class="form-group mt-3">
                             <button type="submit" class="btn btn-primary btn-sm">Search</button>
                             <a href="CutPanelIssueReport" class="btn btn-danger btn-sm">Clear</a>
                          </div>
                         </div>
                       </div>
                   </div>
            </form>
         </div>
          <input type="hidden" id="Ac_code1" value="{{isset($_GET['Ac_code']) ? $_GET['Ac_code'] : 0}}">
          <input type="hidden" id="formFlag1" value="{{isset($_GET['formFlag']) ? $_GET['formFlag'] : 0}}">
          <input type="hidden" id="vendorId1" value="{{isset($_GET['vendorId']) ? $_GET['vendorId'] : 0}}" >
          <input type="hidden" id="sales_order_no1" value="{{isset($_GET['sales_order_no']) ? $_GET['sales_order_no'] : ""}}" >
          <input type="hidden" id="fromDate1" value="{{isset($_GET['fromDate']) ? $_GET['fromDate'] : ""}}">
          <input type="hidden" id="toDate1" value="{{isset($_GET['toDate']) ? $_GET['toDate'] : ""}}">
         <div class="card-body">
            <div class="table-responsive">
               <table id="tbl" class="table table-bordered   nowrap w-100">
                  <thead>
                     <tr style="text-align:center; white-space:nowrap;background: bisque;" id="total_head">
                        <th colspan="11"></th>
                        <th style="text-align: right;">Total : </th>
                        <th id="head_total_grn_qty">{{money_format('%!.0n',round($total_qty))}}</th>
                     </tr>
                     <tr style="text-align:center; white-space:nowrap">
                        <th>Issue No</th>
                        <th>Issue Date</th>
                        <th>Sales Order No</th>
                        <th>Work Order No</th>
                        <th>Buyer Name</th>
                        <th>Vendor Name</th>
                        <th>Buyer Brand</th>
                        <th>Main Style Category</th>
                        <th>User Name</th>
                        <th>Style Name</th>
                        <th>Garment Color</th>
                        <th>Line No.</th>
                        <th>Size</th>
                        <th>Issue Qty</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
   $(function () {
   
   	 $('#tbl').DataTable().clear().destroy();
        var fromDate = $('#fromDate1').val();
        var toDate =  $('#toDate1').val();
        var Ac_code =  $('#Ac_code1').val();
        var vendorId =  $('#vendorId1').val();
        var formFlag =  $('#formFlag1').val();
        var sales_order_no =  $('#sales_order_no1').val();
        
        var table = $('#tbl').DataTable({
         //processing: true,
        // serverSide: true,
        // "pageLength": 10,
        // ajax: "{{ route('CutPanelIssueReport') }}",
         ajax: {
                  url:"{{ route('CutPanelIssueReport') }}",
         		  data:  {'formFlag':formFlag,'fromDate':fromDate,'toDate':toDate,'Ac_code':Ac_code,'vendorId':vendorId,'sales_order_no':sales_order_no},
         		  dataSrc: 'data'
         },
         dom: 'lBfrtip',
         buttons: [
             { extend: 'copyHtml5', footer: true },
             { extend: 'excelHtml5', footer: true },
             { extend: 'csvHtml5', footer: true },
             { extend: 'pdfHtml5', footer: true }
         ],
         
         columns: [
                 {data: 'cpi_code', name: 'cpi_code'},
                 {data: 'cpi_date', name: 'cpi_date'},
                 {data: 'sales_order_no', name: 'sales_order_no'},
                 {data: 'vw_code', name: 'vw_code'},
                 {data: 'Ac_name', name: 'LM1.Ac_name'},
                 {data: 'vendor_Name', name: 'vendor_Name'},
                 {data: 'brand_name', name: "brand_master.brand_name"},
                 {data: 'mainstyle_name', name: 'main_style_master.mainstyle_name'},
                 {data: 'username', name: 'username'},
                 {data: 'style_no', name: 'cut_panel_issue_size_detail2.style_no'},
                 {data: 'color_name', name: 'color_master.color_name'},
                 {data: 'line_name', name: 'line_master.line_name'},
                 {data: 'size_name', name: 'size_detail.size_name'},
                 {data: 'qty', name: 'qty'},
         ]
     });
     
   });
   
    GetSalesOrderDataFromBuyerVendor();
    
    function GetSalesOrderDataFromBuyerVendor()
    {
        var Ac_code = $('#Ac_code').val();
        var vendorId = $('#vendorId').val();
        
        $.ajax({
            type: "GET",
            url: "{{ route('GetSalesOrderDataFromBuyerVendor') }}",
            data:{'vendorId':vendorId, 'Ac_code' : Ac_code },
            success: function(data)
            {
                $("#sales_order_no").html(data.html);
            }
        });
    }
    
    
</script>                                        
@endsection