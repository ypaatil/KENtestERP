@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
<style>

    .row1{
      padding-bottom:10px;
    }
    table , th , tr , td{
        box-shadow: -1px -1px 20px 20px #e0e0e5;
        border: 1px solid #f524dd;
        border-collapse: collapse;
        padding: 0;
        text-align: center;
        background-color: #e7e9eb;
        color: #10100ffa;
        font-weight: bold;
    }
    caption{
      margin-bottom:10px;
      font-weight:bold;
      color:white;
      font-size:25px;
      text-shadow:1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;
      background: aquamarine;
    }
    .col2{
      color:#10100ffa;
    }
    .row1 th{
        color: #5c4a4a;
        background-color: #1c79a5;
        text-shadow: 0 0 3px #ff0000, 0 0 5px #fdfdff00;
        font-size: 16px;
    }
    
    .hide
    {
        display:none;
    }
    .show
    {
       display:block; 
    }
</style>
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
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
<div class="col-md-12">
    <table width="100%" border="1" id="tbl1">
      <caption> 1. Order Booking <img src="../images/loading-dashboard.gif" id="loader1" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
      <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="orderBooking">
      <tr>
        <td> Quantity </td>
        <td class="col2"> Pcs </td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
      </tr>
      <tr>
        <td>  Value  </td>
        <td class="col2">  Lakh  </td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
      </tr>
      <tr>
        <td> Minutes </td>
        <td class="col2"> Lakh </td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
      </tr>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: goldenrod;"> 2. Sales <img src="../images/loading-dashboard.gif" id="loader2" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="salesBooking">
          <tr>
            <td> Quantity </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>  Value  </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Minutes </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #da2076c7;">  3. OCR <img src="../images/loading-dashboard.gif" id="loader3" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="ocrMDBooking">
          <tr>
            <td> Quantity </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>  Value  </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Minutes </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #70da20c7;"> 4. Fabric <img src="../images/loading-dashboard.gif" id="loader4" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="fabricMDBooking">
          <tr>
            <td> Quantity </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>  Value  </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Minutes </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #ed6e13c7;"> 5. Trims <img src="../images/loading-dashboard.gif" id="loader5" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="trimsMDBooking">
          <tr>
            <td> Quantity </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Value  </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Minutes </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #523ccfc7;"> 6. Operations <img src="../images/loading-dashboard.gif" id="loader6" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="operationMDBooking">
          <tr>
            <td> Quantity </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Value  </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Minutes </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #cfa23cc7;">  7. Open Order Status <img src="../images/loading-dashboard.gif" id="loader7" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="openOrderMDBooking">
          <tr>
            <td> Total Open Orders Pcs </td>
            <td class="col2"> Pcs </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Total Open Orders Min </td>
            <td class="col2">  Lakh  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Balance To Ship Pcs </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Balance To Ship Min </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Balance To Produce Pcs </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Balance To Produce Min </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #cf3c65c7;">  8. HR <img src="../images/loading-dashboard.gif" id="loader8" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Month To Date</th>
            <th>Year To Date</th>
          </tr>
      </thead>
      <tbody id="HRMDBooking">
          <tr>
            <td> Overall Man Power Present </td>
            <td class="col2"> No. </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>  Overall Absenteeism Number </td>
            <td class="col2">   No.   </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Overall Absenteeism </td>
            <td class="col2"> % </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Operator Man Power Present </td>
            <td class="col2">  No.  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Operator Absenteeism Number </td>
            <td class="col2">  No.  </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Operator Absenteeism </td>
            <td class="col2"> % </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Total OPEX </td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> MMR </td>
            <td class="col2"> Ratio </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> CPAM </td>
            <td class="col2"> Min </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<div class="col-md-12">
    <table width="100%" border="1">
      <caption style="background: #3c92cfc7;">   9. Inventory Status <img src="../images/loading-dashboard.gif" id="loader9" style="width: 14%;height: 68%;border-radius: 43px;position: relative;"></caption>
        <thead>
          <tr class="row1">
            <th nowrap>Key Indicators</th>
            <th class="col2">UOM</th>
            <th>Today</th>
            <th>Last Month End</th>
            <th>Last Year End</th>
          </tr>
      </thead>
      <tbody id="inventoyStatusMDBooking">
          <tr>
            <td>Fabric - Moving Quantity</td>
            <td class="col2">Mtr</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Fabric - Moving Value </td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Fabric - Non - Moving Quantity </td>
            <td class="col2">Mtr</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Fabric - Non - Moving Value </td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Trims - Moving Value </td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td> Trims - Non - Moving Value </td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>WIP -  Quantity</td>
            <td class="col2">Pcs</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>WIP - Value</td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>FG - Moving Quantity</td>
            <td class="col2">Pcs</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>FG - Moving Value</td>
            <td class="col2">Lakh</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>FG - Non - Moving Quantity</td>
            <td class="col2">Pcs</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
          <tr>
            <td>FG - Non - Moving Value</td>
            <td class="col2"> Lakh </td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
          </tr>
     </tbody>
    </table>
</div>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script>
<script>
    $( document ).ready(function() 
    {
        
        $.ajax({
            dataType: "json",
            url: "{{ route('orderBookingDashboard') }}",
            beforeSend: function() {
             $('#loader1').show();
             $('table #tbl1').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader1').hide();
             $('table #tbl1').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#orderBooking').html(data.html);
            }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('salesMDDashboard') }}",
            beforeSend: function() {
             $('#loader2').show();
             $('table #tbl2').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader2').hide();
             $('table #tbl2').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#salesBooking').html(data.html);
            }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('ocrMDDashboard') }}",
            beforeSend: function() {
             $('#loader3').show();
             $('table #tbl3').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader3').hide();
             $('table #tbl3').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#ocrMDBooking').html(data.html);
            }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('fabricMDDashboard') }}",
            beforeSend: function() {
             $('#loader4').show();
             $('table #tbl4').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader4').hide();
             $('table #tbl4').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#fabricMDBooking').html(data.html);
            }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('trimsMDDashboard') }}",
            beforeSend: function() {
             $('#loader5').show();
             $('table #tbl5').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader5').hide();
             $('table #tbl5').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#trimsMDBooking').html(data.html);
            }
        });
        
        $.ajax({
            dataType: "json",
            url: "{{ route('operationMDDashboard') }}",
            beforeSend: function() {
             $('#loader6').show();
             $('table #tbl6').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader6').hide();
             $('table #tbl6').removeClass('hide').addClass('show');
                var mpcs = 0;
                $(".mpcs").each(function( i ) {
                    mpcs += parseFloat($(this).html());
                });
                $("#mpcs").html(mpcs.toFixed(2));
                
                var tpcs = 0;
                $(".tpcs").each(function( i ) {
                    tpcs += parseFloat($(this).html());
                });
                $("#tpcs").html(tpcs.toFixed(2));
                
                var ypcs = 0;
                $(".ypcs").each(function( i ) {
                    ypcs += parseFloat($(this).html());
                });
                $("#ypcs").html(ypcs.toFixed(2));
                
                var mmin = 0;
                $(".mmin").each(function( i ) {
                    mmin += parseFloat($(this).html());
                });
                $("#mmin").html(mmin.toFixed(2));
                
                var tmin = 0;
                $(".tmin").each(function( i ) {
                    tmin += parseFloat($(this).html());
                });
                $("#tmin").html(tmin.toFixed(2));
                
                var ymin = 0;
                $(".ymin").each(function( i ) {
                    ymin += parseFloat($(this).html());
                });
                $("#ymin").html(ymin.toFixed(2));
                
                var meff = 0;
                $(".meff").each(function( i ) {
                    meff += parseFloat($(this).html());
                });
                $("#meff").html(meff.toFixed(2));
                
                var teff = 0;
                $(".teff").each(function( i ) {
                    teff += parseFloat($(this).html());
                });
                $("#teff").html(teff.toFixed(2));
                
                var yeff = 0;
                $(".yeff").each(function( i ) {
                    yeff += parseFloat($(this).html());
                });
                $("#yeff").html(yeff.toFixed(2));
            },
            success: function(data)
            {
                $('#operationMDBooking').html(data.html);
            }
        });        
       
        $.ajax({
            dataType: "json",
            url: "{{ route('openOrderMDDashboard') }}",
            beforeSend: function() {
             $('#loader7').show();
             $('table #tbl7').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader7').hide();
             $('table #tbl7').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#openOrderMDBooking').html(data.html);
            }
        });
       
        $.ajax({
            dataType: "json",
            url: "{{ route('inventoryStatusMDDashboard') }}",
            beforeSend: function() {
             $('#loader9').show();
             $('table #tbl9').removeClass('show').addClass('hide');
            },
            complete: function(){
             $('#loader9').hide();
             $('table #tbl9').removeClass('hide').addClass('show');
            },
            success: function(data)
            {
                $('#inventoyStatusMDBooking').html(data.html);
            }
        });
    });
</script>
@endsection
@section('script')

@endsection