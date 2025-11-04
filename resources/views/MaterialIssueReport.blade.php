@extends('layouts.master') 
@section('content')  
@php 
    ini_set('memory_limit', '10G');
    setlocale(LC_MONETARY, 'en_IN');  
@endphp   
<style>
   tfoot {
        display: table-header-group;
    }
    .text-right {
        text-align: right;
    }
    .table-warning {
        background-color: #fff3cd;
    }
    .hide
    {
        display:none;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Material Issue Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">MIS Report</a></li>
               <li class="breadcrumb-item active">Material Issue Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<div class="row"> 
    <div class="col-md-12">
       <div class="card mini-stats-wid">
          <div class="card-body">
              <form action="/MaterialIssueReport" method="GET">
                  <div class="row">
                      <div class="col-md-2">
                            <label for="vendorId" class="form-label">Vendor</label>
                            <select name="vendorId" class="form-control select2" id="vendorId">
                                <option value="">--Select--</option>
                                @foreach($vendorList as $row)
                                    <option value="{{$row->ac_code}}" {{ $row->ac_code == $vendorId ? 'selected' : '' }}>{{$row->ac_short_name}}</option>
                                @endforeach    
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" class="form-control select2" id="brand_id">
                                <option value="">--Select--</option>
                                @foreach($brandList as $row)
                                    <option value="{{$row->brand_id}}" {{ $row->brand_id == $brand_id ? 'selected' : '' }}>{{$row->brand_name}}</option>
                                @endforeach    
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="sales_order_no" class="form-label">Sales Order No</label>
                            <select name="sales_order_no" class="form-control select2" id="sales_order_no">
                                <option value="">--Select--</option>
                                @foreach($salesOrderList as $row)
                                    <option value="{{$row->tr_code}}" {{ $row->tr_code == $sales_order_no ? 'selected' : '' }}>{{$row->tr_code}}</option>
                                @endforeach    
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="vpo_code" class="form-label">Process Order No.</label>
                            <select name="vpo_code" class="form-control select2" id="vpo_code">
                                <option value="">--Select--</option>
                                @foreach($vpoList as $row)
                                    <option value="{{$row->vpo_code}}" {{ $row->vpo_code == $vpo_code ? 'selected' : '' }}>{{$row->vpo_code}}</option>
                                @endforeach   
                            </select> 
                      </div>  
                      <div class="col-md-2">
                            <label for="job_status_id" class="form-label">Status </label>
                            <select name="job_status_id" class="form-control select2" id="job_status_id">
                                <option value="">--Select--</option>
                                @foreach($jobStatusList as $row)
                                    <option value="{{$row->job_status_id}}" {{ $row->job_status_id == $job_status_id ? 'selected' : '' }}>{{$row->job_status_name}}</option>
                                @endforeach   
                            </select> 
                      </div>
                      <div class="col-md-2 mt-4 text-center"> 
                            <button type="submit" class="btn btn-primary" aria-label="Search Button">Search</button>
                            <button type="button" onclick="tableData(0,1);" class="btn btn-info" id="compl" >Zero Qty</button>
                            <a href="/MaterialIssueReport" class="btn btn-warning">Clear</a>
                      </div>
                  </div>
              </form>
          </div>
       </div>
    </div> 
   <div class="col-12">
      <div class="card">
         <div class="card-body table-responsive">
              <table id="tbl" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr style="text-align:center; white-space:nowrap">
                     <th>Sr No</th>
                     <th>Sales Order No.</th>
                     <th>Process Order No.</th>
                     <th>Buyer</th>
                     <th>Brand</th>
                     <th>Vendor</th>
                     <th>Item Code</th>
                     <th>Trims Type</th>
                     <th>Classification</th>
                     <th>Item Name</th>
                     <th>Item Description</th>
                     <th>UOM</th>
                     <th>Process Order Qty</th>
                     <th>Issue Qty</th>
                     <th>Balance</th> 
                  </tr>
               </thead>
               <tbody>
                    <img src="../../images/loading_dashboard.gif" id="loadingImg" class="img-fluid" width="1500" height="100" >
                    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); color:white; text-align:center; padding-top:20%; z-index:9999; font-size:18px;">
                        Please wait...Removing zero and less than balance qty.
                    </div>
               </tbody> 
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>
<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>

  let page = 1;

  function tableData(job_status_id,btn) 
  {
         var currentURL = window.location.href; 
                        
         var totalpacking_qty = 0;
         var totalcarton_pack_qty = 0;
         var totaltransfer_qty = 0;
         var totalstock = 0;
       
         
      	 $('#tbl').DataTable().clear().destroy();
        
            var vendorId = $("#vendorId").val();
            var brand_id = $("#brand_id").val();
            var sales_order_no = $("#sales_order_no").val();
            var vpo_code = $("#vpo_code").val(); 
            
            $.ajax({
                url:  "{{ route('LoadMaterialIssueReport') }}",
                type: 'GET',
                data: { 'page': page,'vendorId':vendorId,'sales_order_no':sales_order_no,'brand_id':brand_id,'vpo_code':vpo_code, 'btn':btn},  
                beforeSend: function(e, xhr, settings) 
                {
                    $("#loadingImg").show();
                },
                success: function(data) 
                { 
                     var myArray = data.html;

                     $('#tbl').DataTable({
                        "dom": 'lBfrtip',
                        "pageLength": 10,
                        "buttons": ['csv', 'print', 'excel', 'pdf'],
                        data: myArray,
                        columns: [
                          { data: "srno"},  
                          { data: "sales_order_no"}, 
                          { data: "vpo_code" }, 
                          { data: "ac_short_name"}, 
                          { data: "brand_name"},
                          { data: "vendor_name"},
                          { data: "item_code"},
                          { data: "process_name"},
                          { data: "class_name"},
                          { data: "item_name"},
                          { data: "item_description"},
                          { data: "unit_name"},
                          { data: "item_qty"},
                          { data: "issue_qty"},
                          { data: "bal_qty"}, 
                        ],
                    });
                      
                },
              complete: function()
              {  
                 $("#loadingImg").hide();
                 //ShowAll();
              }
            });
    } 
    
    
    $( document ).ready(function() 
    { 
        tableData(0,0);
    });
</script>
@endsection
