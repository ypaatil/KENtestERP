@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
<!--end page title  -->
<div class="row" style="display:none;">
   <!--<div class="col-xl-4">-->
   <!--   <div class="card overflow-hidden">-->
   <!--      <div class="bg-primary bg-soft">-->
   <!--         <div class="row">-->
   <!--            <div class="col-7">-->
   <!--               <div class="text-primary p-3">-->
   <!--                  <h5 class="text-primary">Welcome Back !</h5>-->
   <!--                  <p>{{Session::get('username')}}</p>-->
   <!--               </div>-->
   <!--            </div>-->
   <!--            <div class="col-5 align-self-end">-->
   <!--               <img src="assets/images/logo/ken.jpeg" alt="" class="img-fluid">-->
   <!--            </div>-->
   <!--         </div>-->
   <!--      </div>-->
         <!--<div class="card-body pt-0">-->
         <!--   <div class="row">-->
         <!--      <div class="col-sm-4">-->
         <!--         <div class="avatar-md profile-user-wid mb-4">-->
         <!--            <img src="assets/images/users/avatar-1.jpg" alt="" class="img-thumbnail rounded-circle">-->
         <!--         </div>-->
         <!--         <h5 class="font-size-15 text-truncate">{{Session::get('username')}}</h5>-->
         <!--         <p class="text-muted mb-0 text-truncate">{{Session::get('username')}}</p>-->
         <!--      </div>-->
               <!--<div class="col-sm-8">-->
               <!--<div class="pt-4">-->
               <!--<div class="row">-->
               <!--<div class="col-6">-->
               <!--<h5 class="font-size-15">10</h5>-->
               <!--<p class="text-muted mb-0">Orders</p>-->
               <!--</div>-->
               <!--<div class="col-6">-->
               <!--<h5 class="font-size-15">12450</h5>-->
               <!--<p class="text-muted mb-0">Target</p>-->
               <!--</div>-->
               <!--</div>-->
               <!--<div class="mt-4">-->
               <!--<a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>-->
               <!--</div>-->
               <!--</div>-->
               <!--</div>-->
      <!--      </div>-->
      <!--   </div>-->
      <!--</div>-->
      <!--<div class="card">-->
      <!--<div class="card-body">-->
      <!--<h4 class="card-title mb-4">Piece Outward</h4>-->
      <!--<div class="row">-->
      <!--<div class="col-sm-6">-->
      <!--<p class="text-muted">This month</p>-->
      <!--<h3>34,252</h3>-->
      <!--<p class="text-muted"><span class="text-success me-2"> 12% <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>-->
      <!--<div class="mt-4">-->
      <!--<a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a>-->
      <!--</div>-->
      <!--</div>-->
      <!--<div class="col-sm-6">-->
      <!--<div class="mt-4 mt-sm-0">-->
      <!--<div id="radialBar-chart" class="apex-charts"></div>-->
      <!--</div>-->
      <!--</div>-->
      <!--</div>-->
      <!--<p class="text-muted mb-0">We craft digital, graphic and dimensional thinking.</p>-->
      <!--</div>-->
      <!--</div>-->
   <!--</div>-->
   <!--<div class="col-xl-8">-->
   <!--<div class="row">-->
   <!--<div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Pending Lots</p>-->
   <!--<h4 class="mb-0">3</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center">-->
   <!--<div class="mini-stat-icon avatar-sm rounded-circle bg-primary">-->
   <!--<span class="avatar-title">-->
   <!--<i class="bx bx-copy-alt font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Completed</p>-->
   <!--<h4 class="mb-0">7</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center ">-->
   <!--<div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
   <!--<span class="avatar-title rounded-circle bg-primary">-->
   <!--<i class="bx bx-archive-in font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Pending Dispatch</p>-->
   <!--<h4 class="mb-0">8</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center">-->
   <!--<div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
   <!--<span class="avatar-title rounded-circle bg-primary">-->
   <!--<i class="bx bx-purchase-tag-alt font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!-- <div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Today's Tasks</p>-->
   <!--<h4 class="mb-0">5</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center">-->
   <!--<div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
   <!--<span class="avatar-title rounded-circle bg-primary">-->
   <!--<i class="bx bx-purchase-tag-alt font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!-- <div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Today's Prduction</p>-->
   <!--<h4 class="mb-0">4200</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center">-->
   <!--<div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
   <!--<span class="avatar-title rounded-circle bg-primary">-->
   <!--<i class="bx bx-purchase-tag-alt font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!-- <div class="col-md-4">-->
   <!--<div class="card mini-stats-wid">-->
   <!--<div class="card-body">-->
   <!--<div class="d-flex">-->
   <!--<div class="flex-grow-1">-->
   <!--<p class="text-muted fw-medium">Total Workers</p>-->
   <!--<h4 class="mb-0">420</h4>-->
   <!--</div>-->
   <!--<div class="flex-shrink-0 align-self-center">-->
   <!--<div class="avatar-sm rounded-circle bg-primary mini-stat-icon">-->
   <!--<span class="avatar-title rounded-circle bg-primary">-->
   <!--<i class="bx bx-purchase-tag-alt font-size-24"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="card">-->
   <!--<div class="card-body">-->
   <!--<div class="d-sm-flex flex-wrap">-->
   <!--<h4 class="card-title mb-4">Email Sent</h4>-->
   <!--<div class="ms-auto">-->
   <!--<ul class="nav nav-pills">-->
   <!--<li class="nav-item">-->
   <!--<a class="nav-link" href="#">Week</a>-->
   <!--</li>-->
   <!--<li class="nav-item">-->
   <!--<a class="nav-link" href="#">Month</a>-->
   <!--</li>-->
   <!--<li class="nav-item">-->
   <!--<a class="nav-link active" href="#">Year</a>-->
   <!--</li>-->
   <!--</ul>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="row">-->
   <!--<div class="col-xl-4">-->
   <!--<div class="card">-->
   <!--<div class="card-body">-->
   <!--<h4 class="card-title mb-4">Social Source</h4>-->
   <!--<div class="text-center">-->
   <!--<div class="avatar-sm mx-auto mb-4">-->
   <!--<span class="avatar-title rounded-circle bg-primary bg-soft font-size-24">-->
   <!--<i class="mdi mdi-facebook text-primary"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--<p class="font-16 text-muted mb-2"></p>-->
   <!--<h5><a href="javascript: void(0);" class="text-dark">Facebook - <span class="text-muted font-16">125 sales</span> </a></h5>-->
   <!--<p class="text-muted">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus tincidunt.</p>-->
   <!--<a href="javascript: void(0);" class="text-primary font-16">Learn more <i class="mdi mdi-chevron-right"></i></a>-->
   <!--</div>-->
   <!--<div class="row mt-4">-->
   <!--<div class="col-4">-->
   <!--<div class="social-source text-center mt-3">-->
   <!--<div class="avatar-xs mx-auto mb-3">-->
   <!--<span class="avatar-title rounded-circle bg-primary font-size-16">-->
   <!--<i class="mdi mdi-facebook text-white"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--<h5 class="font-size-15">Facebook</h5>-->
   <!--<p class="text-muted mb-0">125 sales</p>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="col-4">-->
   <!--<div class="social-source text-center mt-3">-->
   <!--<div class="avatar-xs mx-auto mb-3">-->
   <!--<span class="avatar-title rounded-circle bg-info font-size-16">-->
   <!--<i class="mdi mdi-twitter text-white"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--<h5 class="font-size-15">Twitter</h5>-->
   <!--<p class="text-muted mb-0">112 sales</p>-->
   <!--</div>-->
   <!--</div>-->
   <!--<div class="col-4">-->
   <!--<div class="social-source text-center mt-3">-->
   <!--<div class="avatar-xs mx-auto mb-3">-->
   <!--<span class="avatar-title rounded-circle bg-pink font-size-16">-->
   <!--<i class="mdi mdi-instagram text-white"></i>-->
   <!--</span>-->
   <!--</div>-->
   <!--<h5 class="font-size-15">Instagram</h5>-->
   <!--<p class="text-muted mb-0">104 sales</p>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <!--</div>-->
   <div class="col-xl-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-5">Activity</h4>
            <ul class="verti-timeline list-unstyled">
               <!--<li class="event-list">-->
               <!--<div class="event-timeline-dot">-->
               <!--<i class="bx bx-right-arrow-circle font-size-18"></i>-->
               <!--</div>-->
               <!--<div class="d-flex">-->
               <!--<div class="flex-shrink-0 me-3">-->
               <!--<h5 class="font-size-14">22 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>-->
               <!--</div>-->
               <!--<div class="flex-grow-1">-->
               <!--<div>-->
               <!--Responded to need “Volunteer Activities-->
               <!--</div>-->
               <!--</div>-->
               <!--</div>-->
               <!--</li>-->
               <!--<li class="event-list">-->
               <!--<div class="event-timeline-dot">-->
               <!--<i class="bx bx-right-arrow-circle font-size-18"></i>-->
               <!--</div>-->
               <!--<div class="d-flex">-->
               <!--<div class="flex-shrink-0 me-3">-->
               <!--<h5 class="font-size-14">17 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>-->
               <!--</div>-->
               <!--<div class="flex-grow-1">-->
               <!--<div>-->
               <!--Everyone realizes why a new common language would be desirable... <a href="javascript: void(0);">Read more</a>-->
               <!--</div>-->
               <!--</div>-->
               <!--</div>-->
               <!--</li>-->
               @php
               //  DB::enableQueryLog();
               $details = DB::table('t_and_a_detail')->select('activity_master.act_name','tr_code','target_date')
               ->join('activity_master','activity_master.act_id','=','t_and_a_detail.act_id')
               ->where('target_date', date('Y-m-d'))->get();
               // dd(DB::getQueryLog());
               foreach($details as $rowtna)
               {
               @endphp
               <li class="event-list active">
                  <div class="event-timeline-dot">
                     <i class="bx bxs-right-arrow-circle font-size-18 bx-fade-right"></i>
                  </div>
                  <div class="d-flex">
                     <div class="flex-shrink-0 me-3">
                        <h5 class="font-size-14">{{$rowtna->target_date}} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                     </div>
                     <div class="flex-grow-1">
                        <div>
                           “ {{ $rowtna->tr_code }} ” - {{$rowtna->act_name}}
                        </div>
                     </div>
                  </div>
               </li>
               @php }   @endphp
               <!--<li class="event-list">-->
               <!--<div class="event-timeline-dot">-->
               <!--<i class="bx bx-right-arrow-circle font-size-18"></i>-->
               <!--</div>-->
               <!--<div class="d-flex">-->
               <!--<div class="flex-shrink-0 me-3">-->
               <!--<h5 class="font-size-14">12 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>-->
               <!--</div>-->
               <!--<div class="flex-grow-1">-->
               <!--<div>-->
               <!--Responded to need “In-Kind Opportunity”-->
               <!--</div>-->
               <!--</div>-->
               <!--</div>-->
               <!--</li>-->
            </ul>
            <div class="text-center mt-4"><a href="Timeline" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
         </div>
      </div>
   </div>
   <div class="col-xl-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Top Selling Products</h4>
            @php 
            $Orders=DB::select("select fg_name , sum(order_value) as  volume
            from buyer_purchse_order_master
            INNER JOIN fg_master on fg_master.fg_id=buyer_purchse_order_master.fg_id
            GROUP by buyer_purchse_order_master.fg_id
            order  by volume desc Limit 0,3");
            @endphp
            <div class="text-center">
               <div class="mb-4">
                  <i class="bx bx-map-pin text-primary display-4"></i>
               </div>
               <h3>{{ money_format('%!i',(round($Orders[0]->volume)/100000)) }}</h3>
               <p>{{$Orders[0]->fg_name}}</p>
            </div>
            <div class="table-responsive mt-4">
               <table class="table align-middle table-nowrap">
                  <tbody>
                     @php
                     $firstorder=0;
                     $noo=0;
                     foreach($Orders as $order)
                     {
                     if($noo==0){ $firstorder=round($order->volume);}
                     @endphp
                     <tr>
                        <td style="width: 30%">
                           <p class="mb-0">{{$order->fg_name}}</p>
                        </td>
                        <td style="width: 25%">
                           <h5 class="mb-0">{{ money_format('%!i',(round($order->volume)/100000)) }}</h5>
                        </td>
                        <td>
                           <div class="progress bg-transparent progress-sm">
                              <div class="progress-bar @if($noo==0) bg-success @elseif($noo==1) bg-warning @else bg-danger @endif rounded" role="progressbar" style="width: @if($noo==0)  94%  @else {{ ($order->volume/$firstorder)*100}}%  @endif" aria-valuenow="@if($noo==0)  94%  @else {{ ($order->volume/$firstorder)*100}}%  @endif" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                        </td>
                     </tr>
                     @php
                     $noo=$noo+1;
                     }
                     @endphp 
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- end row -->
<div class="row">
<div class="col-md-6">
<div class="card">
 <div class="card-body">
    <h4 class="card-title mb-4">Sales Order Detail</h4>
    <table class="table align-middle table-nowrap mb-0 table-responsive">
      <thead style="background-color: #975acf; color:white; text-align:center;" >
         <tr>
            <th>Open Order Status</th> 
            <th>PCS(Lakh)</th> 
            <th>Minutes(Lakh)</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td class="text-center">Total Live Orders</td>
            <td class="text-center">{{number_format((double)($total_qtyc/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)($total_order_min/100000), 2, '.', '')}}</td>
         </tr>
         <tr>
            <td class="text-center">Delivered</td>
            <td class="text-center">{{number_format((double)($total_shipped_qtyc/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)($total_shipped_min/100000), 2, '.', '')}}</td>
         </tr>
         <tr>
            <td class="text-center">Balance To Ship</td>
            <td class="text-center">{{number_format((double)($total_balance_qty/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)($total_balance_min/100000), 2, '.', '')}}</td>
         </tr>
         <tr>
            <td class="text-center">Balance To Produce</td>
            <td class="text-center">{{number_format((double)($total_produce_qty/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)($total_produce_min/100000), 2, '.', '')}}</td>
         </tr>
      </tbody>
    </table>
    </div>
  </div>
</div>


<div class="col-md-6">
<div class="card">
 <div class="card-body">
    <h4 class="card-title mb-4">Sales</h4>
    <table class="table align-middle table-nowrap mb-0">
      <thead style="background-color:#f76134; color:white; text-align:center;" >
         <tr>
            <th>Sales</th> 
            <th>Target</th> 
            <th>Amount</th>
            <th>Percentage</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td class="text-center">Monthly</td>
            <td class="text-center">400.00</td>
            <td class="text-center">{{number_format((double)($MonthList[0]->total_sale_value/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)((($MonthList[0]->total_sale_value/400) * 100)/100000), 2, '.', '')}}%</td>
         </tr>
         <tr>
            <td class="text-center">Yearly</td>
            <td class="text-center">5,000.00</td>
            <td class="text-center">{{number_format((double)($SaleTotal[0]->TotalGross/100000), 2, '.', '')}}</td>
            <td class="text-center">{{number_format((double)((($SaleTotal[0]->TotalGross/5000) * 100)/100000), 2, '.', '')}}%</td>
         </tr>
      </tbody>
    </table>
    </div>
  </div>
</div>
<div class="col-md-12">
<div class="card">
 <div class="card-body">
    <h4 class="card-title mb-4">Raw Material Dashboard</h4>
    <table class="table align-middle table-nowrap mb-0">
      <thead style="background-color:#34a6f7; color:white; text-align:center;" >
         <tr>
            <th>Raw Material</th> 
            <th>Against Order</th> 
            <th>Surplus from Closed Orders</th>
            <th>Other Surplus</th>
            <th>Total Value</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td class="text-center">Fabric</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">{{number_format((double)($total_fabric_value/100000), 2, '.', '')}}</td>
         </tr>
         <tr>
            <td class="text-center">Trims</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">{{number_format((double)($total_trim_value/100000), 2, '.', '')}}</td>
         </tr>
      </tbody>
    </table>
    </div>
  </div>
</div>

<div class="col-md-12">
<div class="card">
 <div class="card-body">
    <h4 class="card-title mb-4">Finished Goods</h4>
    <table class="table align-middle table-nowrap mb-0">
      <thead style="background-color: #d9f734;color: black; text-align:center;" >
         <tr>
            <th>Finished Goods</th> 
            <th>Stock Of Running Order</th> 
            <th>Rejected Stock</th>
            <th>Letover/Surplus</th>
            <th>Total Value</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td class="text-center">Garments</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td class="text-center">{{number_format((double)($total_FGStock/100000), 2, '.', '')}}</td>
         </tr>
      </tbody>
    </table>
    </div>
  </div>
</div>

</div>

@php
$DBoard = DB::select("SELECT db_id, BK_VOL_TD_P, BK_VOL_M_TO_Dt_P, BK_VOL_Yr_TO_Dt_P, 
BK_VAL_TD_P, BK_VAL_M_TO_Dt_P, BK_VAL_Yr_TO_Dt_P, SAL_VOL_TD_P,
SAL_VOL_M_TO_Dt_P, SAL_VOL_Yr_TO_Dt_P, SAL_VAL_TD_P,
SAL_VAL_M_TO_Dt_P, SAL_VAL_Yr_TO_Dt_P, BOK_SAH_TD_P,
BOK_SAH_M_TO_Dt_P, BOK_SAH_Y_TO_Dt_P, SAL_SAH_TD_P,
SAL_SAH_M_TO_Dt_P, SAL_SAH_Yr_TO_Dt_P  FROM dashboard_master");
$TodayList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and `order_received_date`=CURRENT_DATE()");
$MonthList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value
from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and MONTH(order_received_date)=MONTH(CURRENT_DATE()) and YEAR(order_received_date)=YEAR(CURRENT_DATE())");
$YearList = DB::select("select ifnull(sum(total_qty),0) as total_order_qty, ifnull(sum(order_value),0) as total_order_value 
from buyer_purchse_order_master where job_status_id!=3 and buyer_purchse_order_master.og_id!=4 and order_received_date between (select fdate from financial_year_master 
where financial_year_master.fin_year_id=1) and (select tdate from financial_year_master where financial_year_master.fin_year_id=1)");
//echo $YearList[0]->total_order_qty;
@endphp
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Order Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
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
                  <tbody>
                     <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Volume (Pcs) In Lakh</b>	</td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->BK_VOL_TD_P)}} </td>
                        @if($TodayList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($TodayList[0]->total_order_qty/100000))}}</a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_TD_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->BK_VOL_M_TO_Dt_P)}}</td>
                        @if($MonthList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',(round(($MonthList[0]->total_order_qty/100000),2)))}}</a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($MonthList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_M_TO_Dt_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;"> 0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        @php 
                        $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
                        @endphp
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->BK_VOL_Yr_TO_Dt_P)}}  </td>
                        @if($YearList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{money_format('%!i',($YearList[0]->total_order_qty/100000))}}</a></td>
                        <td style="border: black 0.5px solid;">  {{money_format('%!i',round((($YearList[0]->total_order_qty/100000)/$DBoard[0]->BK_VOL_Yr_TO_Dt_P),2))}} % </td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;"> 0.00</td>
                        @endif
                     </tr>
                     <tr style="color:black; text-align:right;   border: black 0.5px solid;">
                        <td style="border: black 0.5px solid;"><b>Booking Value in In Lakh</b></td>
                        <td style="border: black 0.5px solid;">  {{money_format('%!i',$DBoard[0]->BK_VAL_TD_P)}}  </td>
                        @if($TodayList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($TodayList[0]->total_order_value/100000))}} </a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_TD_P),2))}} % </td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;" >{{money_format('%!i',$DBoard[0]->BK_VAL_M_TO_Dt_P)}} </td>
                        @if($MonthList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',($MonthList[0]->total_order_value/100000))}} </a></td>
                        <td style="border: black 0.5px solid;">  {{money_format('%!i',round((($MonthList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_M_TO_Dt_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->BK_VAL_Yr_TO_Dt_P)}}   </td>
                        @if($YearList[0]->total_order_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="TotalSalesOrderDetailDashboardFilter?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{money_format('%!i',($YearList[0]->total_order_value/100000))}} </a></td>
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',round((($YearList[0]->total_order_value/100000)/$DBoard[0]->BK_VAL_Yr_TO_Dt_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                     </tr>
                  </tbody>
               </table>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
@php
$TodayList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
from sale_transaction_master where `sale_date`=CURRENT_DATE()");
$MonthList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value
from sale_transaction_master where MONTH(sale_date)=MONTH(CURRENT_DATE()) and YEAR(sale_date)=YEAR(CURRENT_DATE())");
$YearList = DB::select("select ifnull(sum(total_qty),0) as total_sale_qty, ifnull(sum(Gross_amount),0) as total_sale_value 
from sale_transaction_master where sale_date between (select fdate from financial_year_master 
where financial_year_master.fin_year_id=1) and (select tdate from financial_year_master where financial_year_master.fin_year_id=1)");
@endphp
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Sale Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#556ee6; color:white;text-align:center;"     >
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
                  <tbody>
                     <tr style="color:black; text-align:right;">
                        <td style="border: black 0.5px solid;"><b>Sale Volume (Pcs) In Lakh</b>	</td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->SAL_VOL_TD_P)}} </td>
                        @if($TodayList[0]->total_sale_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}"> {{money_format('%!i',($TodayList[0]->total_sale_qty/100000))}}</a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_TD_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->SAL_VOL_M_TO_Dt_P)}}</td>
                        @if($MonthList[0]->total_sale_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',(round(($MonthList[0]->total_sale_qty/100000),2)))}}</a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($MonthList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_M_TO_Dt_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->SAL_VOL_Yr_TO_Dt_P)}}  </td>
                        @php 
                        $Financial=DB::select("select fdate ,tdate from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
                        @endphp
                        @if($YearList[0]->total_sale_qty!=0)
                        <td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{money_format('%!i',($YearList[0]->total_sale_qty/100000))}}</a></td>
                        <td style="border: black 0.5px solid;">  {{money_format('%!i',round((($YearList[0]->total_sale_qty/100000)/$DBoard[0]->SAL_VOL_Yr_TO_Dt_P),2))}} % </td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;"> 0.00</td>
                        @endif
                     </tr>
                     <tr style="color:black;text-align:right; ">
                        <td style="border: black 0.5px solid;"><b>Sale Value in In Lakh</b></td>
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->SAL_VAL_TD_P)}}  </td>
                        @if($TodayList[0]->total_sale_value!=0)
                        <td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($TodayList[0]->total_sale_value/100000))}}</a></td>
                        <td style="border: black 0.5px solid;">{{money_format('%!i',round((($TodayList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_TD_P),2))}} % </td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;">{{money_format('%!i',$DBoard[0]->SAL_VAL_M_TO_Dt_P)}} </td>
                        @if($MonthList[0]->total_sale_value!=0)
                        <td style="border: black 0.5px solid;"><a href="/SaleFilterReport?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',($MonthList[0]->total_sale_value/100000))}} </a></td>
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',round((($MonthList[0]->total_sale_value/100000)/$DBoard[0]->SAL_VAL_M_TO_Dt_P),2))}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                        <td style="border: black 0.5px solid;"> {{money_format('%!i',$DBoard[0]->SAL_VAL_Yr_TO_Dt_P)}}   </td>
                        @if($YearList[0]->total_sale_value!=0)
                        <td style="border: black 0.5px solid;"> <a href="/SaleFilterReport?fdate={{$Financial[0]->fdate}}&tdate={{$Financial[0]->tdate}}">{{number_format((double)($SaleTotal[0]->TotalGross/100000), 2, '.', '')}}</a></td>
                        <td style="border: black 0.5px solid;"> {{number_format((double)((($SaleTotal[0]->TotalGross/5000) * 100)/100000), 2, '.', '')}} %</td>
                        @else
                        <td style="border: black 0.5px solid;">0.00</td>
                        <td style="border: black 0.5px solid;">0.00</td>
                        @endif
                     </tr>
                  </tbody>
               </table>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
@php
$today_meter=0; $today_amount=0;
$month_meter=0; $month_amount=0;
$year_meter=0; $year_amount=0;
$TodayDetails =DB::select("select inward_details.meter,inward_details.item_rate,
(SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
from inward_details where in_date=CURRENT_DATE()");
if(count($TodayDetails)>0)
{
foreach($TodayDetails as $trow) 
{
$today_meter=$today_meter + $trow->meter;
$today_amount=$today_amount + ($trow->meter *$trow->item_rate);
}
}   
$MonthDetails =DB::select("select inward_details.meter,inward_details.item_rate,
(SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
from inward_details where MONTH(in_date)=MONTH(CURRENT_DATE()) and YEAR(in_date)=YEAR(CURRENT_DATE())");
if(count($MonthDetails)>0)
{
foreach($MonthDetails as $mrow) 
{
$month_meter=$month_meter + $mrow->meter;
$month_amount=$month_amount + ($mrow->meter *$mrow->item_rate);
}
}   
$year_meter=0;
$YearDetails =DB::select("select sum(inward_details.meter) as in_meter,inward_details.item_rate,
(SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code) as out_meter,
(sum(inward_details.meter) - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)) as StockMeter 
from inward_details group by inward_details.track_code");
if(count($YearDetails)>0)
{
foreach($YearDetails as $yrow) 
{
$year_meter=$year_meter + ($yrow->in_meter - $yrow->out_meter);
$year_amount=$year_amount + ($yrow->StockMeter *$yrow->item_rate);
}
}   
@endphp
<div class="row">
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Fabric  Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Particular</th>
                        <th class="align-middle">Qty in Mtr</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr style="color:black;">
                        <td><b>Inward Today</b>	</td>
                        <td style="text-align:right;color:black; ">  <a href="/FabricGRNFilterReport?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($today_meter/100000 ))}}</a> </td>
                        <td style="color:black;text-align:right;"  <a href="/FabricGRNFilterReport?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($today_amount/100000))}}</a></td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Inward MTD	</b></td>
                        <td style="text-align:right;color:black;">
                           <a href="/FabricGRNFilterReport?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">
                              {{money_format('%!i',($month_meter/100000 ))}} 
                        </td>
                        <td style="text-align:right;color:black;">  <a href="/FabricGRNFilterReport?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',($month_amount/100000))}}</td>
                     </tr >
                     <tr style="color:black;">
                     <td><b>Fabric Stock</b>	</td>
                     <td style="text-align:right;color:black;"><a href="FabricInOutStockReport?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}" >{{money_format('%!i',($year_meter/100000))}}</a> </td>
                     <td style="text-align:right;color:black;"><a href="FabricStockSummaryData" >{{money_format('%!i',($year_amount/100000))}}</a></td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
   @php
   $today_packed=0; $today_amount=0;
   $month_packed=0; $month_amount=0;
   $year_packed=0; $year_amount=0;
   $TodayDetails =DB::select("SELECT sales_order_no, `order_rate`, 
   ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
   from packing_inhouse_master
   inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
   where pki_date=CURRENT_DATE() group by sales_order_no");
   if(count($TodayDetails)>0)
   {
   foreach($TodayDetails as $trow) 
   {
   $today_packed=$today_packed + $trow->Packed_Qty;
   $today_amount=$today_amount + ($trow->Packed_Qty *$trow->order_rate);
   }
   }   
   
   $MonthDetails =DB::select("SELECT sales_order_no, `order_rate`, 
   ifnull(sum(packing_inhouse_master.total_qty),0) as Packed_Qty
   from packing_inhouse_master
    inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_master.sales_order_no
   where   MONTH(pki_date)=MONTH(CURRENT_DATE()) and YEAR(pki_date)=YEAR(CURRENT_DATE()) group by sales_order_no ");
   if(count($MonthDetails)>0)
   {
   foreach($MonthDetails as $mrow) 
   {
   $month_packed=$month_packed + $mrow->Packed_Qty;
   $month_amount=$month_amount + ($mrow->Packed_Qty *$mrow->order_rate);
   }
   }   
   
   $YearDetails =DB::select("SELECT sales_order_no, `order_rate`, 
   ifnull(sum(packing_inhouse_detail.size_qty_total),0) as Packed_Qty, (select ifnull(sum(sale_transaction_detail.order_qty),0) 
   from sale_transaction_detail where sales_order_no=packing_inhouse_detail.sales_order_no) as 'sold' 
   ,(SELECT ifnull(sum(transfer_packing_inhouse_detail.size_qty_total),0)
   from transfer_packing_inhouse_detail where transfer_packing_inhouse_detail.usedFlag=1 and  main_sales_order_no=packing_inhouse_detail.sales_order_no) as TransferQty
   FROM `packing_inhouse_detail`
   inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code= packing_inhouse_detail.sales_order_no
  group by sales_order_no ");
   $Totalstock=0;
   if(count($YearDetails)>0)
   {
   foreach($YearDetails as $yrow) 
   {
   $year_packed=$yrow->Packed_Qty - $yrow->sold - $yrow->TransferQty;
   $Totalstock=$Totalstock+$year_packed;
   $year_amount=round(($year_amount + ($yrow->Packed_Qty * $yrow->order_rate)),2);
   }
   }   
   @endphp
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Finished Goods Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Particular</th>
                        <th class="align-middle">PCS</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr style="color:black;">
                        <td><b>Inward Today</b>	</td>
                        <td style="text-align:right;">{{money_format('%!i',($today_packed/100000 ))}} </td>
                        <td style="text-align:right;">{{money_format('%!i',($today_amount/100000))}}</td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Inward MTD</b>	</td>
                        <td style="text-align:right;"> {{money_format('%!i',($month_packed/100000 ))}} </td>
                        <td style="text-align:right;">{{money_format('%!i',($month_amount/100000))}}</td>
                     </tr >
                     <tr style="color:black;">
                        <td><b>FG Stock</b>	</td>
                        <td style="text-align:right;"><a href="/FGStockSummaryReport">{{money_format('%!i',($Totalstock/100000))}} </a> </td>
                        <td style="text-align:right;"><a href="/FGStockReport">{{money_format('%!i',($year_amount/100000))}} </a></td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
   @php
   $today_qty=0; $today_amount=0;
   $month_qty=0; $month_amount=0;
   $year_qty=0; $year_amount=0;
   $TodayDetails =DB::select("select ifnull((trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate 
   from trimsInwardDetail   where trimDate=CURRENT_DATE()");
   if(count($TodayDetails)>0)
   {  
   foreach($TodayDetails as $trow) 
   {
   $today_qty=$today_qty + $trow->item_qty;
   $today_amount=$today_amount + ($trow->item_qty * $trow->item_rate);
   }
   }     
   $MonthDetails =DB::select("select ifnull( (trimsInwardDetail.item_qty),0) as item_qty,trimsInwardDetail.item_rate
   from trimsInwardDetail where MONTH(trimDate)=MONTH(CURRENT_DATE()) and YEAR(trimDate)=YEAR(CURRENT_DATE())");
   if(count($MonthDetails)>0)
   {
   foreach($MonthDetails as $mrow) 
   {
   $month_amount=$month_amount + ($mrow->item_qty * $mrow->item_rate);
   }
   }   
   //$TrimsInwardDetails = DB::select("select trimsInwardDetail.*,
   //  (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
   //  where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
   //  
   //  trimsInwardMaster.is_opening, trimsInwardMaster.invoice_no,trimsInwardMaster.po_code, 
   //   trimsInwardMaster.invoice_date,  ledger_master.ac_name,item_master.dimension,item_master.item_name,
   ///   item_master.color_name,item_master.item_description,rack_master.rack_name
   //  from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
   //  inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
   //  inner join item_master on item_master.item_code=trimsInwardDetail.item_code
   //  inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
   $TrimsInwardDetails = DB::select("select trimsInwardMaster.po_code,trimsInwardDetail.item_code,
   ledger_master.ac_name, sum(item_qty) as item_qty,trimsInwardDetail.item_rate,
   (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
   where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
   trimsInwardMaster.po_code, 
   ledger_master.ac_name,item_master.dimension,item_master.item_name,
   item_master.color_name,item_master.item_description,rack_master.rack_name
   from trimsInwardDetail
   left join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
   left join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
   left join item_master on item_master.item_code=trimsInwardDetail.item_code
   left join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id
   group by trimsInwardMaster.po_code,trimsInwardDetail.item_code
   ");
   if(count($TrimsInwardDetails)>0)
   {
   foreach($TrimsInwardDetails as $yrow) 
   {
   $year_amount=$year_amount + (($yrow->item_qty-$yrow->out_qty) *$yrow->item_rate);
   }
   }   
   @endphp
   <div class="col-lg-4">
      <div class="card">
         <div class="card-body">
            <h4 class="card-title mb-4">Trim Status</h4>
            <div class="table-responsive">
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr>
                        <th class="align-middle">Particular</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr style="color:black;">
                        <td><b>Inward Today</b>	</td>
                        <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate={{date('Y-m-d')}}&tdate={{date('Y-m-d')}}">{{money_format('%!i',($today_amount/100000))}}</a></td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Inward MTD</b>	</td>
                        <td style="text-align:right;">  <a href="/TrimsGRNReportPrint?fdate={{date('Y-m-01')}}&tdate={{date('Y-m-t')}}">{{money_format('%!i',($month_amount/100000))}}</a></td>
                     </tr>
                     <tr style="color:black;">
                        <td><b>Trim Stock</b>	</td>
                        <td style="text-align:right;"><a href="TrimsStockData">{{money_format('%!i',($year_amount/100000))}}</a></td>
                     </tr>
                  </tbody>
               </table>
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
               <iframe src="{{url('WorkInProgressStatusList')}}" width="1161" height="700"></iframe>
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
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">PCS</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php     
                     $MonthSale =DB::select("SELECT DATE_FORMAT(`sale_date`, '%b-%Y') AS SaleDate, sale_date,
                     ifnull(sum(`total_qty`),0) as soldQty,     (sum(Gross_amount)/ifnull(sum(`total_qty`),0)) as Rate,
                     sum(Gross_amount) as Taxable_Amount FROM sale_transaction_master where
                     sale_transaction_master.sale_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
                     and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
                     GROUP BY SaleDate ORDER BY sale_date ASC");
                     $totalAmt=0;
                     $AvrRate=0;
                     $TotalPcs=0;
                     $no=0;
                     if(count($MonthSale)>0)
                     {
                     foreach($MonthSale as $mrow) 
                     {
                     @endphp
                     <tr style="color:black;">
                        <td><b>{{$mrow->SaleDate}}</b>	</td>
                        @php $month=date('m', strtotime($mrow->sale_date));
                        @endphp
                        <td style="text-align:right;"><a href="/SaleFilterReport?fdate={{date('Y-'.$month.'-01')}}&tdate={{date('Y-m-t', strtotime($mrow->sale_date))}}">{{money_format('%!i',($mrow->soldQty/100000 ))}} </a></td>
                        <td style="text-align:right;">{{money_format('%!i',($mrow->Rate ))}} </td>
                        <td style="text-align:right;"><a href="/SaleFilterReport?fdate={{date('Y-'.$month.'-01')}}&tdate={{date('Y-m-t', strtotime($mrow->sale_date))}}">{{money_format('%!i',($mrow->Taxable_Amount/100000))}}</a></td>
                     </tr>
                     @php
                     $totalAmt=$totalAmt + ($mrow->Taxable_Amount/100000);
                     $TotalPcs=$TotalPcs + ($mrow->soldQty/100000);
                     $AvrRate=$AvrRate + $mrow->Rate;
                     $no=$no+1;
                     }
                     }
                     @endphp
                  </tbody>
                  <tfoot style="background-color:#008116; color:white;font-weight:bold;">
                     <tr>
                        <td><b>Total</b></td>
                        <td style="text-align:right;">{{round($TotalPcs,2)}}</td>
                        <td style="text-align:right;">{{round(($AvrRate/$no),2)}}</td>
                        <td style="text-align:right;">{{round($totalAmt,2)}}</td>
                     </tr>
                  </tfoot>
               </table>
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
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">Meter</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php     
                     $totalAmt=0;
                     $AvrRate=0;
                     $TotalPcs=0;
                     $MonthInward =DB::select("SELECT DATE_FORMAT(`in_date`, '%b-%Y') AS INDate, in_date ,  
                     ifnull(sum(total_meter),0) as meter ,(ifnull(sum(total_amount),0)/ifnull(sum(total_meter),0)) as Rate,
                     ifnull(sum(total_amount),0) as Taxable_Amount FROM inward_master 
                     where inward_master.in_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
                     and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
                     GROUP BY INDate ORDER BY inward_master.in_date ASC");
                     if(count($MonthInward)>0)
                     {
                     $no=0;
                     foreach($MonthInward as $mrow) 
                     {
                     @endphp
                     <tr style="color:black;">
                        <td><b>{{$mrow->INDate}}</b>	</td>
                        @php $month=date('m', strtotime($mrow->in_date));
                        @endphp
                        <td style="text-align:right;"><a href="/FabricGRNFilterReport?fdate={{date('Y-'.$month.'-01')}}&tdate={{date('Y-m-t', strtotime($mrow->in_date))}}">{{money_format('%!i',($mrow->meter/100000 ))}} </a></td>
                        <td style="text-align:right;">{{money_format('%!i',($mrow->Rate ))}} </td>
                        <td style="text-align:right;"> <a href="/FabricGRNFilterReport?fdate={{date('Y-'.$month.'-01')}}&tdate={{date('Y-m-t', strtotime($mrow->in_date))}}">{{money_format('%!i',($mrow->Taxable_Amount/100000))}}</a></td>
                     </tr>
                     @php
                     $TotalPcs = $TotalPcs + ($mrow->meter/100000);
                     $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                     $AvrRate = $AvrRate + $mrow->Rate;
                     $no=$no+1;
                     }
                     }
                     @endphp
                  </tbody>
                  <tfoot style="background-color:#008116; color:white;font-weight:bold;">
                     <tr>
                        <td><b>Total</b></td>
                        <td style="text-align:right;">{{round($TotalPcs,2)}}</td>
                        <td style="text-align:right;">{{round(($AvrRate/$no),2)}}</td>
                        <td style="text-align:right;">{{round($totalAmt,2)}}</td>
                     </tr>
                  </tfoot>
               </table>
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
               <table class="table align-middle table-nowrap mb-0">
                  <thead style="background-color:#008116; color:white">
                     <tr >
                        <th class="align-middle">Month</th>
                        <th class="align-middle">PCS</th>
                        <th class="align-middle">AVG Rate</th>
                        <th class="align-middle">Value</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php     
                     $MonthInward =DB::select("SELECT DATE_FORMAT(`pki_date`, '%b-%Y') AS PKIDate, 
                     ifnull(sum(total_qty),0) as TotalQty ,AVG(rate) as Rate,(ifnull(sum(total_qty),0)*AVG(rate)) as Taxable_Amount FROM packing_inhouse_master 
                     where packing_inhouse_master.pki_date between (select fdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master))
                     and (select tdate  from financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)) 
                     GROUP BY PKIDate ORDER BY packing_inhouse_master.pki_date ASC");
                     if(count($MonthInward)>0)
                     {
                     $totalAmt=0;
                     $AvrRate=0;
                     $TotalPcs=0;
                     foreach($MonthInward as $mrow) 
                     {
                     @endphp
                     <tr style="color:black;">
                        <td><b>{{$mrow->PKIDate}}</b>	</td>
                        <td style="text-align:right;">{{money_format('%!i',($mrow->TotalQty/100000 ))}} </td>
                        <td style="text-align:right;">{{money_format('%!i',($mrow->Taxable_Amount/$mrow->TotalQty))}} </td>
                        <td style="text-align:right;">{{money_format('%!i',($mrow->Taxable_Amount/100000))}}</td>
                     </tr>
                     @php
                     $TotalPcs = $TotalPcs + ($mrow->TotalQty/100000);
                     $totalAmt = $totalAmt + ($mrow->Taxable_Amount/100000);
                     $AvrRate = $AvrRate +  ($mrow->Taxable_Amount/$mrow->TotalQty);
                     $no=$no+1;
                     }
                     }
                     @endphp
                  </tbody>
                  <tfoot style="background-color:#008116; color:white; font-weight:bold;">
                     <tr>
                        <td><b>Total</b></td>
                        <td style="text-align:right;">{{round($TotalPcs,2)}}</td>
                        <td style="text-align:right;">{{round(($AvrRate/$no),2)}}</td>
                        <td style="text-align:right;">{{round($totalAmt,2)}}</td>
                     </tr>
                  </tfoot>
               </table>
            </div>
            <!--end table-responsive -->
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<!-- dashboard init -->
<script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
@endsection