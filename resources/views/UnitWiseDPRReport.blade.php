@extends('layouts.master') 
@section('content')  
<style>
    .text-right
    {
        text-align: right;
    }
</style>
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18" id="p_title">Unit Wise DPR Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Reports</a></li>
               <li class="breadcrumb-item active">Unit Wise DPR Report</li>
            </ol>
         </div>
      </div>
   </div>
</div>
<!-- end page title -->
@if(session()->has('message'))
<div class="alert alert-success">
   {{ session()->get('message') }}
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="col-md-12 text-center"> 
                    <button class="btn btn-primary" onclick="Report(1);">Stitching</button>
                    <button class="btn btn-warning" onclick="Report(2);">Washing Packing</button>
            </div><br/>
            <div class="col-md-12">
               <div class="card mini-stats-wid">
                  <div class="card-body">
                      <form action="/UnitWiseDPRReport" method="GET">
                          <div class="row">
                              <div class="col-md-3">
                                    <label><b>Vendor</b></label>
                                    <select name="vendorId" class="form-select select2" id="vendorId" >
                                       <option value="">--- Select ---</option>
                                       @foreach($LedgerList as  $row)
                                       <option value="{{ $row->ac_code  }}">{{ $row->ac_short_name }}</option>
                                       @endforeach
                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label><b>Brand</b></label>
                                    <select name="brand_id" class="form-select select2" id="brand_id" >
                                       <option value="">--- Select ---</option>
                                       @foreach($BrandList as  $row)
                                       <option value="{{ $row->brand_id  }}">{{ $row->brand_name }}</option>
                                       @endforeach
                                    </select>
                              </div>
                              <div class="col-md-2">
                                    <label><b>Order No</b></label>
                                    <select name="sales_order_no" class="form-select select2" id="sales_order_no" >
                                       <option value="">--- Select ---</option>
                                       @foreach($SalesOrderList as  $row)
                                       <option value="{{ $row->tr_code  }}">{{ $row->tr_code }}</option>
                                       @endforeach
                                    </select>
                              </div>
                              <div class="col-md-5 mt-4"> 
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="/UnitWiseDPRReport" class="btn btn-warning">Clear</a>
                              </div>
                          </div>
                      </form>
                  </div>
               </div>
            </div>
            <!-- Loading Overlay -->
            <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); z-index: 9999; text-align: center;">
                <img src="{{ URL::asset('images/loading-waiting.gif')}}" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            </div>
            <table id="dt" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Vendor</th>
                        <th>Brand</th>
                        <th>Style Name</th>
                        <th>Color</th>
                        <th>Line No.</th>
                        <th>Cutting Issue</th>
                        <th>Stitching</th>
                        <th>Line/Bal</th>
                        <th>Cut to Stitch %</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
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

    $(document).ready(function() 
    {  
        loadDataTable(1); 
    });
    
    function Report(type)
    {
        if ($.fn.DataTable.isDataTable("#dt")) 
        {
            $("#dt").DataTable().destroy(); 
        }
        
        loadDataTable(type); 
    }
    
    function loadDataTable(type) 
    {
        if(type == 1)
        {
           $("#p_title").html("Stitching Unit Wise DPR Report"); 
        }
        else
        {
            $("#p_title").html("Washing Packing Unit Wise DPR Report");
        }
        
        let tableContainer = $("#dt"); 
                
        if ($.fn.DataTable.isDataTable(tableContainer.find("table"))) 
        {
            tableContainer.find("table").DataTable().destroy();
        }
             
        $("#loadingOverlay").fadeIn();
    
        var vendorId = getSearchParams("vendorId");
        var brand_id = getSearchParams("brand_id");
        var sales_order_no = getSearchParams("sales_order_no");
        
        var URL = "LoadUnitWiseDPRReport";  
        
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('LoadUnitWiseDPRReport') }}", 
            data:{'type':type,'vendorId':vendorId,'brand_id':brand_id,'sales_order_no':sales_order_no},
            success: function(data)
            { 
                $("#dt").html(data.html);
                
                $("#vendorId").val(vendorId).trigger('change');
                $("#brand_id").val(brand_id).trigger('change');
                $("#sales_order_no").val(sales_order_no).trigger('change');
        
        
                $('#dt').DataTable({
                    processing: true,
                    serverSide: false, 
                    destroy: true,
                    dom: 'lBfrtip',
                    buttons: [
                        { extend: 'copyHtml5', footer: true },
                        { extend: 'excelHtml5', footer: true },
                        { extend: 'csvHtml5', footer: true },
                        { extend: 'pdfHtml5', footer: true }
                    ],
                });
                
            },
            complete: function()
            {
                $("#loadingOverlay").fadeOut();
            }
        });
    }
    
    
    function getSearchParams(k)
    {
         var p={};
         location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
         return k?p[k]:p;
    }
    
    
</script>
@endsection