@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>
    .hide{
        display :none;
    }
    .show{
        display:block;
    }
    .text-center
    {
        text-align:center;
    }
    th
    {
        background: #152d9f!important;
        color: #fff!important;
    }
</style>
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="https://www.datatables.net/rss.xml">
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.jqueryui.min.css">
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
               <li class="breadcrumb-item active">Dashboard</li>
            </ol>
         </div>
      </div>
   </div>
</div>

<!-- end row -->
<div class="row">
    <div class="col-md-6">
        <div class="card" style="backgound:black;">
         <div class="card-body">
            <h4 class="card-title mb-4">Sales Order Detail</h4>
            <table class="table align-middle table-nowrap mb-0 table-responsive" id="tbl_sales_order_detail">
              <thead style="background-color: #975acf; color:white; text-align:center;" >
                 <tr>
                    <th>Open Order Status</th> 
                    <th>PCS(Lakh)</th> 
                    <th>Minutes(Lakh)</th>
                 </tr>
              </thead>
              <tbody id="sale_order_detail"></tbody>
            </table>
            <div id="loader1" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
         </div>
        </div>
    </div>
    <div class="col-md-6">
    <div class="card">
     <div class="card-body">
        <h4 class="card-title mb-4">Sales</h4>
        <table class="table align-middle table-nowrap mb-0"  id="tbl_sales">
          <thead style="background-color:#f76134; color:white; text-align:center;" >
             <tr>
                <th>Sales</th> 
                <th>Target</th> 
                <th>Amount</th>
                <th>Percentage</th>
             </tr>
          </thead>
          <tbody id="sale"></tbody>
        </table>
        <div id="loader2" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
        <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Raw Material Dashboard</h4>
            <table class="table align-middle table-nowrap mb-0" id="tbl_raw_material_dashboard">
              <thead style="background-color:#34a6f7; color:white; text-align:center;" >
                 <tr>
                    <th>Raw Material</th> 
                    <th>Against Order</th> 
                    <th>Surplus from Closed Orders</th>
                    <th>Other Surplus</th>
                    <th>Total Value</th>
                 </tr>
              </thead>
              <tbody id="raw_material_dashboard"></tbody>
            </table>
            <div id="loader3" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
         </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="card">
     <div class="card-body">
        <h4 class="card-title mb-4">Finished Goods</h4>
        <table class="table align-middle table-nowrap mb-0" id="tbl_finishing">
          <thead style="background-color: #d9f734;color: black; text-align:center;" >
             <tr>
                <th>Finished Goods</th> 
                <th>Stock Of Running Order</th> 
                <th>Rejected Stock</th>
                <th>Letover/Surplus</th>
                <th>Total Value</th>
             </tr>
          </thead>
          <tbody id="finishing"></tbody>
        </table>
        <div id="loader4" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
        </div>
      </div>
    </div>
</div>
<div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Order Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0" id="tbl_order_Status">
                  <thead class=" " style="background-color:#f79733; color:white; text-align:center;" >
                     <tr >
                        <th  style="border: black 0.5px solid;" class="align-middle" rowspan="2">Particular</th>
                        <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Today</th>
                        <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Month To Date</th>
                        <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Year To Date</th>
                     </tr>
                     <tr  >
                        <th style="border: black 0.5px solid;"  class="align-middle">Plan</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                        <th  style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Plan</th>
                        <th style="border: black 0.5px solid;"  class="align-middle">Actual</th>
                        <th  style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Plan</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                     </tr>
                  </thead>
                  <tbody id="order_Status"></tbody>
               </table>
               <div id="loader5" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sale Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0" id="tbl_Sale_Status">
                  <thead style="background-color:#556ee6; color:white;text-align:center;">
                     <tr>
                        <th style="border: black 0.5px solid;" class="align-middle" rowspan="2">Particular</th>
                        <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Today</th>
                        <th style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Month To Date</th>
                        <th  style="border: black 0.5px solid;" class="align-middle" colspan="3" style="text-align:center">Year To Date</th>
                     </tr>
                     <tr>
                        <th  style="border: black 0.5px solid;" class="align-middle">Plan</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                        <th style="border: black 0.5px solid;"  class="align-middle">Achievement</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Plan</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Actual</th>
                        <th style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                        <th style="border: black 0.5px solid;"  class="align-middle">Plan</th>
                        <th  style="border: black 0.5px solid;" class="align-middle">Actual</th>
                        <th  style="border: black 0.5px solid;" class="align-middle">Achievement</th>
                     </tr>
                  </thead>
                  <tbody id="Sale_Status"></tbody>
               </table>
               <div id="loader6" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
<div class="row">
<div class="col-lg-4">
  <div class="card">
     <div class="card-body">
        <h4 class="card-title mb-4">Fabric  Status</h4>
        <div class="table-responsive">
           <table class="table align-middle table-nowrap mb-0" id="tbl_Fabric_Status">
              <thead style="background-color:#008116; color:white">
                 <tr >
                    <th class="align-middle">Particular</th>
                    <th class="align-middle">Qty in Mtr</th>
                    <th class="align-middle">Value</th>
                 </tr>
              </thead>
              <tbody id="Fabric_Status"></tbody>
           </table>
           <div id="loader7" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
        </div>
        <!--end table-responsive -->
     </div>
  </div>
</div>
<div class="col-lg-4">
  <div class="card">
     <div class="card-body">
        <h4 class="card-title mb-4">Finished Goods Status</h4>
        <div class="table-responsive">
           <table class="table align-middle table-nowrap mb-0" id="tbl_Finishing_Goods_Status">
              <thead style="background-color:#008116; color:white">
                 <tr >
                    <th class="align-middle">Particular</th>
                    <th class="align-middle">PCS</th>
                    <th class="align-middle">Value</th>
                 </tr>
              </thead>
              <tbody id="Finishing_Goods_Status"></tbody>
           </table>
         <div id="loader8" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
        </div>
        <!--end table-responsive -->
     </div>
  </div>
</div>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Trim Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0"  id="tbl_Trim_Status">
                  <thead style="background-color:#008116; color:white">
                     <tr>
                        <th class="align-middle">Particular</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody id="Trim_Status"></tbody>
               </table>
               <div id="loader9" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Work In Progress Status</h4>
            <div class="table-responsive">
                 <table class="table align-middle table-nowrap mb-0" id="tbl_Work_In_Progress_Status">
                     <thead>
            			<tr>
                            <th>SrNo</th>
                            <th>Job Worker</th>
                            <th colspan="2">Work Order Qty/ Cut Panel Issue</th>
                            <th>Garment  Inward </th>
                            <th>WIP</th>
                         </tr>
                         <tr>
                            <th></th> 
                            <th>Fabric</th>
                            <th>CUT Panel </th>
                            <th>Garments </th>
                            <th>PCS </th>
                            <th></th>
                         </tr>
                         <tr>
                            <th> </th>
                            <th>PCS</th>
                            <th>PCS</th>
                            <th>PCS</th>
                            <th></th>
                            <th id="wip"></th>
                         </tr>
            		</thead>
                  <tbody id="Work_In_Progress_Status"></tbody>
                  <tfoot style="background-color:#008116; color:white;font-weight:bold;" id="Work_In_Progress_Status_Foot" ></tfoot>
               </table>
                <div id="loader10" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Garment Sale</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0" id="tbl_Garment_Sale">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">PCS</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody id="Garment_Sale"></tbody>
                  <tfoot style="background-color:#008116; color:white;font-weight:bold;" id="Garment_Sale_foot" ></tfoot>
               </table>
                <div id="loader11" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Fabric Purchase</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0"  id="tbl_Garment_Purchase">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">Meter</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody id="Garment_Purchase"></tbody>
                  <tfoot style="background-color:#008116; color:white;font-weight:bold;" id="Garment_Purchase_Foot"></tfoot>
               </table>
               <div id="loader12" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Finished Goods Inward</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0"  id="tbl_Finished_Goods_Inward">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">PCS</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody id="Finished_Goods_Inward"></tbody>
                  <tfoot style="background-color:#008116; color:white; font-weight:bold;" id="Finished_Goods_Inward_Foot"></tfoot>
               </table>
               <div id="loader13" class="text-center" ><img src="../images/loading.gif" style=" width: 16%;height: fit-content; border-radius: 43px;"></div>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
<!-- end row -->
<!-- apexcharts -->
<!-- dashboard init -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.jqueryui.min.js"></script>
<script>

    function SalesOrderDetailDashboard()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('SalesOrderDetailDashboard') }}",
            beforeSend: function() {
             $('#loader1').show();
             $('#tbl_sales_order_detail').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader1').hide();
             $('#tbl_sales_order_detail').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#sale_order_detail').html(data.html);
            }
        });
    }
    
    function SalesDashboard()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('SalesDashboard') }}",
            beforeSend: function() {
             $('#loader2').show();
             $('#tbl_sales').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader2').hide();
             $('#tbl_sales').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#sale').html(data.html);
            }
        });
    }
    
    function RawMaterialDashboard()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('RawMaterialDashboard') }}",
            beforeSend: function() {
             $('#loader3').show();
             $('#tbl_raw_material_dashboard').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader3').hide();
             $('#tbl_raw_material_dashboard').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#raw_material_dashboard').html(data.html);
            }
        });
    }
    
    function Finishing()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('Finishing') }}",
            beforeSend: function() {
             $('#loader4').show();
             $('#tbl_finishing').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader4').hide();
             $('#tbl_finishing').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#finishing').html(data.html);
            }
        });
    }
    
    function OrderStatus()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('OrderStatus') }}",
            beforeSend: function() {
             $('#loader5').show();
             $('#tbl_order_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader5').hide();
             $('#tbl_order_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#order_Status').html(data.html);
            }
        });
    }
    
    function SaleStatus()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('SaleStatus') }}",
            beforeSend: function() {
             $('#loader6').show();
             $('#tbl_Sale_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader6').hide();
             $('#tbl_Sale_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Sale_Status').html(data.html);
            }
        });
    }
       
    function FabricStatus()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('FabricStatus') }}",
            beforeSend: function() {
             $('#loader7').show();
             $('#tbl_Fabric_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader7').hide();
             $('#tbl_Fabric_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Fabric_Status').html(data.html);
            }
        });
    }
    
    function FinishingGoodsStatus()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('FinishingGoodsStatus') }}",
            beforeSend: function() {
             $('#loader8').show();
             $('#tbl_Finishing_Goods_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader8').hide();
             $('#tbl_Finishing_Goods_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Finishing_Goods_Status').html(data.html);
            }
        });
    }
    
    function TrimStatus()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('TrimStatus') }}",
            beforeSend: function() {
             $('#loader9').show();
             $('#tbl_Trim_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader9').hide();
             $('#tbl_Trim_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Trim_Status').html(data.html);
            }
        });
    }
    
    function WorkInProgressStatus()
    {
         $.ajax({
            dataType: "json",
            url: "{{ route('WorkInProgressStatus') }}",
            beforeSend: function() {
             $('#loader10').show();
             $('#tbl_Work_In_Progress_Status').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader10').hide();
             $('#tbl_Work_In_Progress_Status').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Work_In_Progress_Status').html(data.html1);
                $('#Work_In_Progress_Status_Foot').html(data.html2);
            }
        });
    }
    
    function GarmentSale()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GarmentSale') }}",
            beforeSend: function() {
             $('#loader11').show();
             $('#tbl_Garment_Sale').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader11').hide();
             $('#tbl_Garment_Sale').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Garment_Sale').html(data.html1);
                $('#Garment_Sale_foot').html(data.html2);
            }
        });
    }
    
    function GarmentPurchase()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('GarmentPurchase') }}",
            beforeSend: function() {
             $('#loader12').show();
             $('#tbl_Garment_Purchase').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader12').hide();
             $('#tbl_Garment_Purchase').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Garment_Purchase').html(data.html1);
                $('#Garment_Purchase_Foot').html(data.html2);
            }
        });
    }
    
    function FinishedGoodsInward()
    {
        $.ajax({
            dataType: "json",
            url: "{{ route('FinishedGoodsInward') }}",
            beforeSend: function() {
             $('#loader13').show();
             $('#tbl_Finished_Goods_Inward').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader13').hide();
             $('#tbl_Finished_Goods_Inward').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#Finished_Goods_Inward').html(data.html1);
                $('#Finished_Goods_Inward_Foot').html(data.html2);
            }
        });
    }
    $( document ).ready(function() 
    { 
        $('#tbl_sales_order_detail').addClass('hide');
        $('#tbl_sales').addClass('hide');
        $('#tbl_raw_material_dashboard').addClass('hide');
        $('#tbl_finishing').addClass('hide');
        $('#tbl_order_Status').addClass('hide');
        $('#tbl_Sale_Status').addClass('hide');
        $('#tbl_Fabric_Status').addClass('hide');
        $('#tbl_Finishing_Goods_Status').addClass('hide');
        $('#tbl_Trim_Status').addClass('hide');
        $('#tbl_Work_In_Progress_Status').addClass('hide');
        $('#tbl_Garment_Sale').addClass('hide');
        $('#tbl_Garment_Purchase').addClass('hide');
        $('#tbl_Finished_Goods_Inward').addClass('hide');
        
        SalesOrderDetailDashboard();
        RawMaterialDashboard()
        SalesDashboard();
        Finishing();
        OrderStatus();
        SaleStatus();
        FabricStatus();
        FinishingGoodsStatus();
        TrimStatus();
        WorkInProgressStatus();
        GarmentSale();
        GarmentPurchase();
        FinishedGoodsInward();
    });
    
</script>
@endsection
@section('script')

@endsection