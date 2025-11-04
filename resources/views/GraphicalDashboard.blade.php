@extends('layouts.master') 
@section('content')
@php setlocale(LC_MONETARY, 'en_IN'); @endphp

<!-- ====== CSS Libraries ====== -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> <!-- Font Awesome for icons -->
<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
        background-color: #f4f6f9;
    }

    /* ===== LEFT SIDEBAR ===== */
    .left-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 200px; /* adjust sidebar width */
        height: 100%;
        background-color: #2f3542;
        color: #fff;
        padding: 20px;
        box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        z-index: 20; /* higher than tab wrapper */
    }

    /* ===== DASHBOARD CONTAINER ===== */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        /*margin-left: 200px;  */
    }

    /* ===== TAB NAVIGATION BAR ===== */
    .tab-sidebar-wrapper {
        position: relative;
        z-index: 5;
    }

    .tab-sidebar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        background: #f1f2f6;
        border-bottom: 3px solid #000;
        padding: 15px 10px;
        overflow-x: auto;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-left: 0; /* tabs already shifted by container */
    }

    .tab-sidebar::-webkit-scrollbar {
        height: 6px;
    }
    .tab-sidebar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    /* ===== TAB BUTTON ===== */
    .tab-button {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 1px solid #ddd;
        border-bottom: none;
        color: #333;
        padding: 10px 20px;
        margin: 0 6px;
        border-radius: 12px 12px 0 0;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
    }

    .tab-button i, 
    .tab-button img {
        margin-right: 8px;
        font-size: 15px;
    }

    .tab-button:hover {
        background: #f9f9f9;
    }

    /* ===== ACTIVE TAB ===== */
    .tab-button.active {
        background: #000;
        color: #fff;
        border-color: #000;
        z-index: 2;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.25);
    }

    .tab-button.active i {
        color: #fff;
    }

    /* ===== CONTENT AREA ===== */
    .tab-content-area {
        flex: 1;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.6s ease-out;
        margin-left: 0; /* adjusted by container */
    }

    .tab-content {
        display: none;
        width: 100%;
        height: 100%;
    }
    .tab-content.active {
        display: block;
    }

    .iframe-container {
        width: 100%;
        height: 100%;
    }
    .iframe-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* ===== ANIMATION ===== */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== TAB SCROLL BUTTONS ===== */
    .scroll-btn {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50px; /* slightly wider for better visibility */
        background: linear-gradient(
            to right, 
            rgba(66, 96, 217, 0.9), 
            rgba(66, 96, 217, 0.6), 
            rgba(66, 96, 217, 0.9)
        );
        border: none;
        outline: none;
        cursor: pointer;
        font-size: 24px;
        color: #fff; /* white text for contrast */
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px; /* rounded edges */
        box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* subtle shadow */
        transition: all 0.3s ease;
    }
    
    .scroll-btn:hover {
        background: rgba(66, 96, 217, 1); /* solid on hover */
        transform: scale(1.1); /* slight zoom effect */
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }
    
    .scroll-btn.right {
        right: 0;
    }
    .scroll-btn:disabled {
        opacity: 0.3;
        cursor: default;
    }
    
    /* Make table container scrollable vertically */
   .table-responsive {
        max-height: calc(100vh - 200px); /* Adjust 200px to leave space for header/footer */
        overflow-y: auto;  /* Enables vertical scrolling */
        overflow-x: auto;  /* Enables horizontal scrolling */
    }

    
    /* Optional: keep table headers sticky */
    #openOrderReport thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }
    
    /* Optional: style the sticky header background to avoid overlap */
    #openOrderReport thead th {
        background-color: #00000061; /* keep your existing header background */
        color: #fff;
    }
    
    #progressBar 
    {
        width: 100%;
        background-color: #f3f3f3;
        border: 1px solid #ccc;
        height: 30px;
        border-radius: 5px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }
    #progress 
    {
        width: 0;
        height: 100%;
        background-color: #4caf50;
        text-align: center;
        line-height: 30px;
        color: white;
        border-radius: 5px;
        transition: width 0.4s ease;
    }
    #checkmark 
    {
        display: none;
        width: 50px;
        height: 50px;
        margin: 20px auto;
    }
    .checkmark_circle 
    {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark_check 
    {
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4caf50;
        fill: none;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards;
    }
    @keyframes stroke 
    {
        100% {
            stroke-dashoffset: 0;
        }
    }
    
       @import 'https://fonts.googleapis.com/css?family=Open+Sans:600,700';
    
    * {font-family: 'Open Sans', sans-serif;}
    
    .rwd-table {
      margin: auto;
      min-width: 300px;
      max-width: 100%;
      border-collapse: collapse;
    }
    
    .rwd-table tr:first-child {
      border-top: none;
      background: #ff000017;
      color: black;
    }
    
    .rwd-table tr {
      border-top: 1px solid #ddd;
      border-bottom: 1px solid #ddd;
      background-color: #f5f9fc;
    }
    
    .rwd-table tr:nth-child(odd):not(:first-child) {
      background-color: #ebf3f9;
    }
    
    .rwd-table th {
      display: none;
    }
    
    .rwd-table td {
      display: block;
    }
    
    .rwd-table td:first-child {
      margin-top: .5em;
    }
    
    .rwd-table td:last-child {
      margin-bottom: .5em;
    }
    
    .rwd-table td:before {
      content: attr(data-th) ": ";
      font-weight: bold;
      width: 120px;
      display: inline-block;
      color: #000;
    }
    
    .rwd-table th,
    .rwd-table td {
      /*text-align: left;*/
    }
    
    .rwd-table {
      color: #333;
      border-radius: .4em;
      overflow: hidden;
    }
    
    .rwd-table tr {
      border-color: #bfbfbf;
    }
    
    .rwd-table th,
    .rwd-table td {
      padding: .5em 1em;
    }
    @media screen and (max-width: 601px) {
      .rwd-table tr:nth-child(2) {
        border-top: none;
      }
    }
    @media screen and (min-width: 600px) {
      .rwd-table tr:hover:not(:first-child) {
        background-color: #d8e7f3;
      }
      .rwd-table td:before {
        display: none;
      }
      .rwd-table th,
      .rwd-table td {
        display: table-cell;
        padding: .25em .5em;
      }
      .rwd-table th:first-child,
      .rwd-table td:first-child {
        padding-left: 0;
      }
      .rwd-table th:last-child,
      .rwd-table td:last-child {
        padding-right: 0;
      }
      .rwd-table th,
      .rwd-table td {
        padding: 1em !important;
      }
    }
    
    
</style>

<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png"> 
@if(isset($chekform) && $chekform->write_access == 1)
<div class="dashboard-container">

    <!-- TAB NAVIGATION -->
    <div class="tab-sidebar-wrapper">
        <button class="scroll-btn left" id="scrollLeft">&#8249;</button>
        <div class="tab-sidebar" id="tabScroll">
            <div class="tab-button active" data-tab="tab1"><i class="fa-solid fa-chart-column"></i> MTD Booking</div>
            <div class="tab-button" data-tab="tab2"><i class="fa-solid fa-calendar-days"></i> Monthly Sales</div>
            <div class="tab-button" data-tab="tab3"><i class="fa-solid fa-hand-holding-dollar"></i> Sales (FOB + JW)</div>
            <div class="tab-button" data-tab="tab4"><i class="fa-solid fa-gears"></i> WIP</div>
            <div class="tab-button" data-tab="tab5"><i class="fa-solid fa-industry"></i> All Production</div>
            <div class="tab-button" data-tab="tab6"><i class="fa-solid fa-chart-line"></i> Monthwise Prod.</div>
            <div class="tab-button" data-tab="tab7"><i class="fa-solid fa-tachometer-alt"></i> Operation Status</div>
            <div class="tab-button" data-tab="tab8"><i class="fa-solid fa-users"></i> Buyer Costing</div>
            <div class="tab-button" data-tab="tab9"><i class="fa-solid fa-file-invoice"></i> Open Orders</div>
            <div class="tab-button" data-tab="tab10"><i class="fa-solid fa-boxes-stacked"></i> Inventory</div>
            <div class="tab-button" data-tab="tab11"><i class="fa-solid fa-flask"></i> Sampling</div>
            <div class="tab-button" data-tab="tab12"><i class="fa-solid fa-warehouse"></i> FG Stock</div>
            <div class="tab-button" data-tab="tab13"><i class="fa-solid fa-user-tag"></i> Buyer Inventory</div>
        </div>
        <button class="scroll-btn right" id="scrollRight">&#8250;</button>
    </div>


    <!-- TAB CONTENT -->
    <div class="tab-content-area">
        <div id="tab1" class="tab-content active">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiYzJkMDJlYzctM2QwZi00NzA5LTlhZmYtN2FlNTEwZGI5NzhiIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9"  allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab2" class="tab-content">
            <div class="iframe-container"><iframe src="https://app.powerbi.com/view?r=eyJrIjoiZjExMTVjNTMtZDA3Zi00MzQ2LThiZGYtYjg1YThkOTk2YjZlIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab3" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiYzhlMWM4N2MtNWViYy00ZWI4LTg3ZjYtMTE2Yzg1YThiN2U2IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe> 
            </div>
        </div>
        <div id="tab4" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiMDNhYzVkNGYtOGY2YS00NTAzLTg4ZTItOGUyZTI0MWVlZGIyIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab5" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiY2ZmNTFhZDYtOTY1ZC00OGEzLTg0MjctNTM5NjZiMWE1MzViIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe> 
            </div>
        </div>
        <div id="tab6" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiYzUxNDlmMTYtMjQyZi00MTUzLWIxM2YtMjA3ZjViNTc4NjAyIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab7" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiZDZmZWFlMTktZWZmMy00MTYzLWFjZWEtYjg0ZTZmYjdmNDkwIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab8" class="tab-content">
            <div class="iframe-container">
                 <iframe src="https://app.powerbi.com/view?r=eyJrIjoiMDM2NjI5ZGItNzlmMS00Nzc5LWE4ODQtNDBjNzZiZDAwMWY5IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab9" class="tab-content">
            <div class="iframe-container">
            <div class="row">
                <div class="col-lg-12 text-center"><label class="mb-4" style="font-size: 25px;color: black;">Open Order Report</label></div>
               <div class="col-lg-12">
                  <div class="card">
                     <div class="card-body @if(Session::get('userId') != 1) hide @endif ">  
                       <input type="hidden" name="slide_no" value="5">  
                        <div class="col-md-12"> 
                            <div class="row">  
                                <div class="col-md-12">   
                                       <div class="row">
                                           <div class="col-md-4">
                                             <div class="mb-3">
                                                <label for="OpenOrderTrDate" class="form-label">Date</label>
                                                <input type="date" class="form-control" name="toDate" id="OpenOrderTrDate" value="{{ date('Y-m-d')}}">
                                             </div>
                                           </div> 
                                           <div class="col-sm-4">
                                              <label for="formrow-inputState" class="form-label"></label>
                                              <div class="form-group"> 
                                                <button class="btn btn-primary w-md" onclick="OpenOrderReports();" type="button">Search</button> 
                                              </div>
                                           </div> 
                                           <div class="col-sm-4 text-right">
                                                <button type="button" id="export_button" class="btn btn-warning">Export</button>
                                           </div> 
                                       </div> 
                                </div>
                            </div>
                        </div> 
                        <div class="table-responsive"  style="overflow-y: scroll;overflow-x: scroll;"> 
                            <table id="openOrderReport" class="table dt-datatable table-bordered nowrap w-100">
                              <thead>  
                                 <tr style="white-space:nowrap;background: #00000061;color: #fff; border:1px solid;">
                                    <th class="text-center"  style="background: #e2d72b00;color: #fff;border-right: 3px solid;">Sr.</th>
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Buyer Name</th> 
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Order</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">No. Of</th> 
                                    <th class="text-center"  style="background: blueviolet;color: #fff;border-right: 3px solid;" colspan="3">Order Qty.</th> 
                                    <th class="text-center" style="background: chartreuse;" colspan="3">Produced</th>  
                                    <th class="text-center" style="background: #f57474;color:white;border-right: 3px solid;" colspan="3">Dispatch</th>   
                                    <th class="text-center" style="background: #1861d6;color:#fff;border-right: 3px solid;" colspan="3">B 2 P</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">Completion</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">(LM)</th> 
                                 </tr> 
                                 <tr style="white-space:nowrap;background: #00000061;color: #fff; border:1px solid;">
                                    <th class="text-center"  style="background: #e2d72b00;color: #fff;border-right: 3px solid;"> No.</th>
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;"></th> 
                                    <th class="text-center"  style="background: #657c41;color: #fff;border-right: 3px solid;">Type</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">Orders</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">L Pcs</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;">L Min</th>
                                    <th class="text-center"  style="background: blueviolet;color: #fff;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: chartreuse;">L Pcs</th> 
                                    <th class="text-center" style="background: chartreuse;">L Min</th> 
                                    <th class="text-center" style="background: chartreuse;border-right: 3px solid;">Rs. Cr.</th>  
                                    <th class="text-center" style="background: #f57474;color:white;">L Pcs</th> 
                                    <th class="text-center" style="background: #f57474;color:white;">L Min</th> 
                                    <th class="text-center" style="background: #f57474;color:white;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;">L Pcs</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;">L Min</th> 
                                    <th class="text-center" style="background: #1861d6;color:#fff;border-right: 3px solid;">Rs. Cr.</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;"> %</th> 
                                    <th class="text-center" style="background: #d34040;color:#fff;">% </th> 
                                 </tr>
                              </thead>
                              <tbody id="OpenOrderReportTbody"></tbody> 
                              <tfoot id="OpenOrderReportTbody1"></tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            </div>
        </div>
        <div id="tab10" class="tab-content">
            <div class="iframe-container">
                 <div class="col-md-12 text-center">    
                    <label class="mb-4" style="font-size: 25px;color: black;">Inventory Moving/Non-Moving Report</label> 
                    <div class="table-responsive" id="InventoryReportTbl">
                        <div class="row">
                           <div class="col-12">
                              <div class="card"> 
                                 <div class="card-body">
                                    <div class="col-md-12 text-center mb-5"> 
                                        <div id="progressBar">
                                            <div id="progress">0%</div>
                                        </div> 
                                        <div id="checkmark">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                                <circle class="checkmark_circle" cx="26" cy="26" r="25" fill="none"/>
                                                <path class="checkmark_check" fill="none" d="M14 27l10 10L38 17"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                       <div class="table-responsive invoice" id="tbleData">
                                        <table id="tbl" class="rwd-table">
                                          <thead class="tablehead"> 
                                          <tr style="text-align:center; white-space:nowrap">
                                			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col first-col"></th>
                                			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col second-col"></th>
                                			    <th style="background: #0006;color: #fff;border-top: 3px solid black;" class="sticky-col third-col">Perticulars</th>  
                                			     @php
                                			        $colorCtr = 0;
                                			        
                                                    foreach($period as $key1=>$dates)
                                                    {  
                                                      $yrdata= strtotime($dates."-01");
                                                      $monthName = date('F', $yrdata);  
                                                      $arr = explode("-", $dates);
                                                      $year = $arr[0];  
                                                  
                                                @endphp
                                			    <th colspan="2" style="background:{{$colorArr[0]}};border-top: 3px solid black;border-right: 1px solid gray;" class="text-center">{{$monthName}}-{{$year}}</th>
                                			    @php  
                                                   }   
                                                @endphp
                                            </tr>
                                            <tr style="text-align:center; white-space:nowrap"> 
                                			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col first-col"></th>
                                			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col second-col"></th>
                                			    <th style="background: #0006;color: #fff;border-bottom: 3px solid black;" class="sticky-col third-col"></th>
                                			    @php
                                			      $colorCtr1 = 0;
                                                    foreach($period as $key=>$dates)
                                                    {  
                                                @endphp
                                			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;border-right: 1px solid #8080803d;" class="sticky_row">Qty.</th> 
                                			    <th style="background:{{$colorArr[0]}};border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky_row">Value</th>
                                			    @php 
                                			      $colorCtr1++;
                                                   }   
                                                @endphp
                                            </tr>
                                            </thead>  
                                            <tbody id="tablebodyInventory"></tbody> 
                                            <tfoot id="tablefootInventory" style="background: #ff000017;color: black;font-size: larger;"></tfoot> 
                                        </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- end col -->
                        </div>
                    </div>
                 </div> 
            </div>
        </div>
        <div id="tab11" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiN2RlNzhiZDAtYjJlMC00OWMzLWI5NDgtYzRjN2Y1NzY2YjE2IiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
        <div id="tab12" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiNzg2NGRlM2QtMzdiYi00ZTVhLTg4M2EtZTM3NzlhMmE2OWFlIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe> 
            </div>
        </div>
        <div id="tab13" class="tab-content">
            <div class="iframe-container">
                <iframe src="https://app.powerbi.com/view?r=eyJrIjoiMDAxYmZlM2MtODczNC00NTAzLTk3MGYtN2EyMTMzMjBlYTAxIiwidCI6ImVmMGI1ODNjLTViNzMtNDUwMy1iZjVjLTI5MTY2Y2FiOTA1YyJ9" allowFullScreen="true"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>   
<script src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
<script> 

/* ===== FIX TAB SCROLL USING jQUERY ===== */
    $(document).ready(function() {
        var $tabScroll = $('#tabScroll');
        var $scrollLeftBtn = $('#scrollLeft');
        var $scrollRightBtn = $('#scrollRight');
    
        function updateScrollButtons() {
            var scrollLeft = $tabScroll.scrollLeft();
            var maxScroll = $tabScroll[0].scrollWidth - $tabScroll.outerWidth();
            $scrollLeftBtn.prop('disabled', scrollLeft <= 0);
            $scrollRightBtn.prop('disabled', scrollLeft >= maxScroll - 1);
        }
    
        // Smooth scroll with animation
        $scrollLeftBtn.on('click', function() {
            $tabScroll.animate({ scrollLeft: $tabScroll.scrollLeft() - 150 }, 300, updateScrollButtons);
        });
    
        $scrollRightBtn.on('click', function() {
            $tabScroll.animate({ scrollLeft: $tabScroll.scrollLeft() + 150 }, 300, updateScrollButtons);
        });
    
        $tabScroll.on('scroll', updateScrollButtons);
        $(window).on('resize', updateScrollButtons);
    
        // Initialize once on load
        updateScrollButtons(); 
        
            /* 🔹 TAB SWITCHING */
            $('.tab-button').click(function() {
                var tab_id = $(this).data('tab');
                $('.tab-button').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').removeClass('active');
                $('#' + tab_id).addClass('active');
            });
        
            /* 🔹 MOBILE REDIRECT */
            if (window.innerWidth <= 600) {
                window.location.href = "https://kenerp.com/BuyerCosting";
            }
        
            /* 🔹 INITIAL LOADS */
            $('#starting').removeClass('hide');
            quantitive_inventory();
            GetBookingData(1);
            GetBookingData1(1);
            OpenOrderReports();
            GetWorkInProgress();
        
            /* 🔹 BASIC DATATABLE INIT */
            var dataTable = $("#example").DataTable({
                order: [[4, "desc"]],
                pageLength: 14
            });
        
            dataTable.on("order.dt search.dt", function() {
                dataTable.column(0, { search: "applied", order: "applied" })
                         .nodes()
                         .each(function(cell, i) { cell.innerHTML = i + 1; });
            }).draw();
        });
    
    
    /* 🔹 EXPORT TABLE TO EXCEL */
    function html_table_to_excel(type) {
        var data = document.getElementById('openOrderReport');
        var file = XLSX.utils.table_to_book(data, { sheet: "sheet1" });
        XLSX.writeFile(file, 'Open Order Report.' + type);
    }
    
    
    /* 🔹 LOADERS */
    function quantitive_inventory() {
        LoadFabricQuantitiveReport();
        LoadTrimsQuantitiveReport();
        LoadWIPQuantitiveReport();
        LoadFGQuantitiveReport();
    }
    
    
    /* --- AJAX LOADERS (SIMPLE CLEAN VERSION) --- */
    function LoadFabricQuantitiveReport() {
        ajaxLoad('{{ route('LoadFabricQuantitiveReport') }}', '#tablebody');
    }
    
    function LoadTrimsQuantitiveReport() {
        ajaxLoad('{{ route('LoadTrimsQuantitiveReport') }}', '#tablebody');
    }
    
    function LoadWIPQuantitiveReport() {
        ajaxLoad('{{ route('LoadWIPQuantitiveReport') }}', '#tablebody');
    }
    
    function LoadFGQuantitiveReport() {
        ajaxLoad('{{ route('LoadFGQuantitiveReport') }}', '#tablebody');
    }
    
    function GetWorkInProgress() {
        ajaxLoad('{{ route('WorkInProgressStatusList') }}', '#WorkProgressTbl');
    }
    
    function GetBookingData(row) {
        ajaxLoad('{{ route('loadBookingSummary') }}', '#bookingTbody', { job_status_id: row });
    }
    
    function GetBookingData1(row) {
        ajaxLoad('{{ route('loadJobWorkBookingSummary') }}', '#bookingTbody1', { job_status_id: row });
    }
    
    // function OpenOrderReports() {
    //     var OpenOrderTrDate =$("#OpenOrderTrDate").val();  
    //     ajaxLoad('{{ route('GetOpenOrderReport') }}', '#OpenOrderReportTbody', { OpenOrderTrDate: OpenOrderTrDate });
    // }
    
    function OpenOrderReports()
    { 
          var OpenOrderTrDate =$("#OpenOrderTrDate").val();  
          
          $.ajax({
              dataType: "json",  
              data : { 'OpenOrderTrDate' : OpenOrderTrDate},
              url: "{{ route('GetOpenOrderReport') }}", 
              success: function(data)
              { 
                   $('#OpenOrderReportTbody').html(data.html); 
                   $('#OpenOrderReportTbody1').html(data.html1); 
            
                    
                        
                   $(document).ready(function() 
                   {
                        var dataTable = $('#openOrderReport').DataTable({
                            "order": [[13, "desc"]],  
                            "bProcessing": true,
                            "sAutoWidth": false,
                            "bDestroy":true,
                            "sPaginationType": "bootstrap", // full_numbers 
                            "bPaginate": false, //hide pagination
                            "bFilter": false, //hide Search bar
                            "bInfo": false, // hide showing entries 
                            // "scrollY": 700,     
                            // "responsive": true
                        });
                         dataTable.on('order.dt search.dt', function () {
                            dataTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                                cell.innerHTML = i + 1;
                            });
                        }).draw();
                    });
                      
                    
              },
          });
          
          GetOpenOrderSummaryReport();
    }
    
    function GetOpenOrderSummaryReport()
    { 
      var OpenOrderTrDate = $("#OpenOrderTrDate").val();
      $.ajax({
          dataType: "json", 
          data : { 'OpenOrderTrDate' : OpenOrderTrDate},
          url: "{{ route('GetOpenOrderSummaryReport') }}", 
          success: function(data)
          {
              $('#openOrderSummaryTbl').html(data.html);  
              
          },
      });
    }   

    LoadFabricInventoryMovingNonMovingReport();
    LoadTrimsInventoryMovingNonMovingReport();
    LoadFGInventoryMovingNonMovingReport();
    
    var totalRequests = 4;  
    var completedRequests = 0;
    
    
    function updateProgressBar() 
    {
            var percentage = Math.round((completedRequests / totalRequests) * 100);
            $('#progress').css('width', percentage + '%').text(percentage + '% ...Loading');
 
            if (percentage === 100) {
                $('#progressBar').fadeOut('slow', function() {
                    $('#checkmark').fadeIn('slow');
                });
            }
    }
    
    function LoadFabricInventoryMovingNonMovingReport()
    {
        var fin_year_id = 6;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFabricInventoryMovingNonMovingReport') }}",
            success: function(data)
            { 
                $("#tablebodyInventory").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            }
        });
    }
    
    function LoadTrimsInventoryMovingNonMovingReport()
    {
        var fin_year_id = 6;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadTrimsInventoryMovingNonMovingReport') }}",
            success: function(data)
            {
                $("#tablebodyInventory").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            }
        });
    }
    
    function LoadFGInventoryMovingNonMovingReport()
    {
        var fin_year_id = 6;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadFGInventoryMovingNonMovingReport') }}",
            success: function(data)
            {
                $("#tablebodyInventory").append(data.html);
                LoadWIPInventoryMovingNonMoving();
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            }
        });
    }
    
    function LoadWIPInventoryMovingNonMoving() {
        var fin_year_id = 6;
        $.ajax({
            dataType: "json",
            data: { 'fin_year_id': fin_year_id },
            url: "{{ route('LoadWIPInventoryMovingNonMoving') }}",
            success: function(data) {
                $("#tbl tbody").append(data.html);
                calculateGrandTotal();
                completedRequests++;
                updateProgressBar();
            },
        });
    }
    
    
    /* 🔹 REUSABLE AJAX LOADER FUNCTION */
    function ajaxLoad(url, target, data = {}) {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url,
            data: data,
            beforeSend: function() {
                $('#waitingGif').show();
            },
            success: function(response) {
                if (response.html) {
                    $(target).html(response.html);
                }
            },
            complete: function() {
                $('#waitingGif').hide();
            },
            error: function(xhr, status, error) {
                console.error("Error loading:", url, error);
                $('#waitingGif').hide();
            }
        });
    }

   function calculateGrandTotal() 
   {
        var trims = $(".trims_total_value");
        var fabric = $(".fabric_total_value");
        var FG = $(".FG_total_value");
        var WIP = $(".WIP_total_value");
       
        var GrandTotalHtml = '<tr><th></th><th></th><th nowrap>Grand Total</th>';
        for(var i=0; i<=11;i++)
        { 
            var total = 0;
            var trim = 0;
            var fab = 0;
            var fg = 0;
            var wip = 0;
            
            if(trims.length > 0)
            {
                trim = parseFloat($(trims[i]).text().replace(/,/g, ''));
            }
            
            if(fabric.length > 0)
            {
                fab = parseFloat($(fabric[i]).text().replace(/,/g, ''));
            }
            
            if(FG.length > 0)
            {
                fg = parseFloat($(FG[i]).text().replace(/,/g, ''));
            }
            
            if(WIP.length > i)
            {
                wip = parseFloat($(WIP[i]).text().replace(/,/g, ''));
            }
            
            var total = trim + fab + fg +wip;
            
            GrandTotalHtml += '<th></th><th class="grand_total text-right">' + formatCurrency(total.toFixed(0)) + '</th>';
        }
        GrandTotalHtml += '</tr>';
        var NonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>Total Non moving stock</th>';
        
        var Trims_non = $(".Trims_non_moving_value");
        var Fabric_non = $(".Fabric_non_moving_value");
        var FG_non = $(".FG_non_moving_value");
        
        for(var i=0; i<=11;i++)
        { 
            var total_non = 0;
            var trim_non = 0;
            var fab_non = 0;
            var fg_non = 0; 
            
            if(Trims_non.length > 0)
            {
                trim_non = parseFloat($(Trims_non[i]).text().replace(/,/g, ''));
            }
            
            if(Fabric_non.length > 0)
            {
                fab_non = parseFloat($(Fabric_non[i]).text().replace(/,/g, ''));
            }
            
            if(FG_non.length > 0)
            {
                fg_non = parseFloat($(FG_non[i]).text().replace(/,/g, ''));
            } 
            
            var total_non = trim_non + fab_non + fg_non;
            
            NonMovingTotalHtml += '<th></th><th class="total_non text-right">' + formatCurrency(total_non.toFixed(0)) + '</th>';
        }
        NonMovingTotalHtml += '</tr>';
        
        var PerNonMovingTotalHtml = '<tr><th></th><th></th><th nowrap>% of non moving Inventory in Total Inventory</th>';
        var grand_total = $(".grand_total"); 
        var total_non = $(".total_non");
        var WIP_total_value = $(".WIP_total_value");
        console.log(grand_total);
        for(var i=0; i<=11;i++)
        { 
            var per_total_non = 0;
            var grand = 0;
            var tot_non = 0; 
            var WIP_tot_value = 0;
            
            if(grand_total.length > 0 && total_non.length > 0)
            {
                grand = parseFloat($(grand_total[i]).text().replace(/,/g, ''));
                tot_non = parseFloat($(total_non[i]).text().replace(/,/g, ''));
                WIP_tot_value = parseFloat($(WIP_total_value[i]).text().replace(/,/g, ''));
                var grand1 = parseFloat(grand) + parseFloat(WIP_tot_value); 
                
                if(grand1 > 0 && tot_non > 0)
                {  
                    per_total_non = (tot_non/grand1) * 100;
                }
            } 
             
            PerNonMovingTotalHtml += '<th></th><th class="text-right">' + formatCurrency(per_total_non.toFixed(2)) + '</th>';
        }
        PerNonMovingTotalHtml += '</tr>';
      
        $("#tablefootInventory").html(GrandTotalHtml+NonMovingTotalHtml+PerNonMovingTotalHtml);
    } 
    
    window.onload = function() 
    {
        if (window.innerWidth <= 600) 
        {
          window.location.href = "https://kenerp.com/BuyerCosting";  
        }
    };
      
    function formatCurrency(amount) 
    {
        var strAmount = amount.toString();
        var parts = strAmount.split(".");
        var wholePart = parts[0];
        var decimalPart = parts.length > 1 ? "." + parts[1] : "";
        
        // Split the whole part into an array of digits
        var wholePartArray = wholePart.split('');
        var formattedWholePart = '';
        
        // Start from the end of the whole part
        var counter = 0;
        for (var i = wholePartArray.length - 1; i >= 0; i--) {
            formattedWholePart = wholePartArray[i] + formattedWholePart;
            counter++;
            if (counter === 3 && i > 0) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            } else if (counter === 2 && i > 0 && wholePartArray.length - i > 3) {
                formattedWholePart = ',' + formattedWholePart;
                counter = 0;
            }
        }
        
        return formattedWholePart + decimalPart;
    }
    
    function html_table_to_excel(type,orderTypeId)
    {
        var data = document.getElementById('openOrderReport'); 

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    
        XLSX.writeFile(file, 'Open Order Report.' + type);
    }
        
    const export_button = document.getElementById('export_button');

    export_button.addEventListener('click', () =>  {
        html_table_to_excel('xlsx');
    });
       
       

</script>
@else
<div class="row center-image">
  <img src="{{ URL::asset('logo/ken.jpeg')}}" alt="Ken Global Designs Pvt. Ltd." style="width: 1056px; height: 520px;">
</div>
@endif
@endsection
