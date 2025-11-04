<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Ken Global Designs Pvt. Ltd.</title>
      <meta name="author" content="">
      <!-- Web Fonts
         ======================= -->
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
      <!-- Stylesheet
         ======================= -->
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/bootstrap.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/all.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/style.css') }}"/> 
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp 
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
                <div class="row text-center">  
                    <p><img src="@if($BuyerCostingMaster[0]->style_img_path!=''){{url('thumbnail/'.$BuyerCostingMaster[0]->style_img_path)}}@else https://kenerp.com/logo/ken.jpeg @endif"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>
                    <div class="col-md-8"><h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4><br/><h4 class="mb-0" style="font-weight:bold;">Buyer Costing Sheet</h4> </div>
               </div>
              
               <style>
                .table-bordered td, .table-bordered th {
                 border: 1px solid #0c0c0c;
                 body{
                 font-family: "Times New Roman", Times, serif;
                 }
                 }
                  .alignRight{
                  text-align:right;
                  }
                  .bgYel{
                  background-color:#d8ff3c;    
                  }
                  .table{
                  display: table;
                  width:100%;
                  border-collapse:collapse;
                  }
                  .tr {
                  display: table-row;
                  padding: 2px;
                  }
                  .tr p {
                  margin: 0px !important; 
                  }
                  .td {
                  display: table-cell;
                  padding: 8px;
                  width: 410px;
                  border: #000000 solid 1px;
                  }
                  @page{
                  margin: 5px !important;
                  }
                  .merged{
                  width:25%;
                  height:25%;
                  padding: 8px;
                  display: table-cell;
                  background-image: url('https://kenerp.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
                  b,span
                  {
                      font-size:16px;
                      margin: 4px;
                  }
                   
                  #cost_summary th, td {
                        border: 1px solid black; /* Sets a solid black border around th and td */ 
                      }
                      
                      
                      @media print {
                        #fabric_costing {
                            page-break-after: always; /* or page-break-before: always; */
                        }
                        
                        #cost_summary th{
                            border: 3px solid black; /* Sets a solid black border around th and td */ 
                            text-align: center; /* Adjust text alignment as needed */
                            border-right: 3px solid black; /* Sets a solid black border around th and td */ 
                        }
                      
                        .row {
                            display: -ms-flexbox;
                            display: flex;
                            -ms-flex-wrap: wrap;
                            flex-wrap: wrap;
                            margin-right: -15px;
                            margin-left: -15px;
                        }
                        
                        .col-md-3 {
                            -ms-flex: 0 0 25%;
                            flex: 0 0 25%;
                            max-width: 25%;
                        }
                        .col-md-4 {
                            -ms-flex: 0 0 33.333333%;
                            flex: 0 0 33.333333%;
                            max-width: 33.333333%;
                        }
                        
                        .printInvoice {
                            padding-top: -20px;
                        }
                        
                        #cost_summary th, td {
                            border: 1px solid black; /* Sets a solid black border around th and td */
                        }
                    
                        /* Exclude styles for #main_print */
                        #main_print {
                            all: initial; /* Resets all properties to their initial values */
                        }
                    }


                   /*table th{*/
                   /*     border: 1px solid black;  */
                   /* }*/
                       
               </style>
               <div id="printInvoice">
                  <div class="row" id="main_print" >
                     <div  class="col-md-3">
                        <b style="display: inline-block;text-align: left;">Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y", strtotime($BuyerCostingMaster[0]->entry_date)) }} </span></br>
                        <b style="display: inline-block;text-align: left;">Costing No.:  </b> <span style="display: inline-block;text-align: right;"> {{ $BuyerCostingMaster[0]->revised_id ? $BuyerCostingMaster[0]->revised_id  : $BuyerCostingMaster[0]->sr_no }} </span></br>
                        <b style="display: inline-block;text-align: left;">Buyer Name:  </b> <span style="display: inline-block;text-align: right;"> {{ $BuyerCostingMaster[0]->buyer_name }} </span></br>
                        <b style="display: inline-block;text-align: left;">Brand Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->brand_name }} </span></br> 
                        <b style="display: inline-block;text-align: left;">Currency:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->currency_name }} </span></br> 
                        <b style="display: inline-block;text-align: left;">FOB  ({{$BuyerCostingMaster[0]->currency_name}}):</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$BuyerCostingMaster[0]->inr_rate) }} </span></br>
                        <b style="display: inline-block;text-align: left;">Exchange Rate :</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$BuyerCostingMaster[0]->exchange_rate) }} </span></br>
                        <b style="display: inline-block;text-align: left;">FOB Rate :</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$BuyerCostingMaster[0]->fob_rate) }} </span></br>
                     </div>
                     <div  class="col-md-8"> 
                        <b style="display: inline-block;text-align: left;">Order Group: </b>  <span style="display: inline-block;text-align: right;">{{  $BuyerCostingMaster[0]->order_group_name }} </span></br> 
                        <b style="display: inline-block;text-align: left;">Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->style_name }} </span></br> 
                        <b style="display: inline-block;text-align: left;">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->style_no }} </span></br> 
                        <b style="display: inline-block;text-align: left;">Style Description:</b>  <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->style_description }}</span></br>
                        <b style="display: inline-block;text-align: left;">SAM:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerCostingMaster[0]->sam }} </span></br> 
                        <b style="display: inline-block;text-align: left;">Order Qty:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($BuyerCostingMaster[0]->total_qty) }}</span></br>
                        <b style="display: inline-block;text-align: left;">Order Value:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($BuyerCostingMaster[0]->total_value) }}</span></br>
                     </div>
                     <div  class="col-md-3">
                     </div>
                  </div>
                 <div  class="row" style="justify-content: end;position: absolute;margin-left: 70%;margin-top: -300px;">
                     <img src="https://kenerp.com/uploads/BuyerCosting/{{$BuyerCostingMaster[0]->style_image}}" width="300" height="250" alt=" {{ $BuyerCostingMaster[0]->revised_id ? $BuyerCostingMaster[0]->revised_id  : $BuyerCostingMaster[0]->sr_no }}" />
                 </div>
               </div>
    
                  @php 
                  if($BuyerCostingMaster[0]->fabric_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                      $percentOffabric =($BuyerCostingMaster[0]->fabric_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOffabric = 0; 
                  }
                  if($BuyerCostingMaster[0]->sewing_trims_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfsewing_trims_value=($BuyerCostingMaster[0]->sewing_trims_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOfsewing_trims_value = 0; 
                  }
                  if($BuyerCostingMaster[0]->packing_trims_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfpacking_trims_value=($BuyerCostingMaster[0]->packing_trims_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOfpacking_trims_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->production_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfproduction_value=($BuyerCostingMaster[0]->production_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOfproduction_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->agent_commission_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfagent_commision_value=($BuyerCostingMaster[0]->agent_commission_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOfagent_commision_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->transport_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOftransaport_value=($BuyerCostingMaster[0]->transport_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOftransaport_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->other_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfother_value=($BuyerCostingMaster[0]->other_value / $BuyerCostingMaster[0]->fob_rate) * 100;
                  }
                  else
                  {
                        $percentOfother_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->dbk_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfdbk_value=($BuyerCostingMaster[0]->dbk_value / $BuyerCostingMaster[0]->fob_rate) * 100;  
                  }
                  else
                  {
                        $percentOfdbk_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->printing_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfprinting_value=($BuyerCostingMaster[0]->printing_value / $BuyerCostingMaster[0]->fob_rate) * 100;  
                  }
                  else
                  {
                        $percentOfprinting_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->printing_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfembroidery_value=($BuyerCostingMaster[0]->embroidery_value / $BuyerCostingMaster[0]->fob_rate) * 100;  
                  }
                  else
                  {
                        $percentOfembroidery_value = 0; 
                  }
                  
                  if($BuyerCostingMaster[0]->ixd_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfixd_value=($BuyerCostingMaster[0]->ixd_value / $BuyerCostingMaster[0]->fob_rate) * 100;   
                  }
                  else
                  {
                        $percentOfixd_value = 0; 
                  } 
                 
                  if($BuyerCostingMaster[0]->garment_reject_value > 0 && ($BuyerCostingMaster[0]->fabric_value + $BuyerCostingMaster[0]->sewing_trims_value + $BuyerCostingMaster[0]->packing_trims_value + $BuyerCostingMaster[0]->production_value + $BuyerCostingMaster[0]->dbk_value) > 0)
                  { 
                        $percentOfgarment_reject_value=($BuyerCostingMaster[0]->garment_reject_value / ($BuyerCostingMaster[0]->fabric_value + $BuyerCostingMaster[0]->sewing_trims_value + $BuyerCostingMaster[0]->packing_trims_value + $BuyerCostingMaster[0]->production_value + $BuyerCostingMaster[0]->dbk_value)) * 100;  
                  }
                  else
                  {
                        $percentOfgarment_reject_value = 0; 
                  } 
                  
                  if($BuyerCostingMaster[0]->testing_charges_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOftesting_charges_value=($BuyerCostingMaster[0]->testing_charges_value / $BuyerCostingMaster[0]->fob_rate) * 100;
                  }
                  else
                  {
                        $percentOftesting_charges_value = 0; 
                  } 
                    
                  if($BuyerCostingMaster[0]->finance_cost_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOffinance_cost_value=($BuyerCostingMaster[0]->finance_cost_value / $BuyerCostingMaster[0]->fob_rate) * 100;
                  }
                  else
                  {
                        $percentOffinance_cost_value = 0; 
                  } 
                     
                  if($BuyerCostingMaster[0]->extra_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfextra_value=($BuyerCostingMaster[0]->extra_value / $BuyerCostingMaster[0]->fob_rate) * 100;  
                  }
                  else
                  {
                        $percentOfextra_value = 0; 
                  } 
                     
                  if($BuyerCostingMaster[0]->total_cost_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOftotal_cost_value=($BuyerCostingMaster[0]->total_cost_value / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOftotal_cost_value = 0; 
                  }  
                  
                  if($BuyerCostingMaster[0]->dbk_value1 > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $percentOfdbk_value1=($BuyerCostingMaster[0]->dbk_value1 / $BuyerCostingMaster[0]->fob_rate) * 100; 
                  }
                  else
                  {
                        $percentOfdbk_value1 = 0; 
                  }
                     
                  $totalCost=$BuyerCostingMaster[0]->embroidery_value + $BuyerCostingMaster[0]->printing_value + $BuyerCostingMaster[0]->dbk_value + $BuyerCostingMaster[0]->fabric_value + $BuyerCostingMaster[0]->production_value + $BuyerCostingMaster[0]->sewing_trims_value + $BuyerCostingMaster[0]->packing_trims_value;
                  
                  if($totalCost > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                  { 
                        $totalmakingper=($totalCost/ $BuyerCostingMaster[0]->fob_rate) * 100;
                  }
                  else
                  {
                        $totalmakingper = 0; 
                  }   
                  
                  @endphp
               <div class="row"  id="fabric_costing"> 
                  <div class="col-md-4" ></div>
                 <div class="col-md-3" >
                    <table  style="border: 1px solid black; font-weight:bold;margin-top:10px;" id="cost_summary">
                       <tr>
                          <th colspan="3" style="font-size:20px;background-color:#eee; text-align:center;">Total Summary</th>
                       </tr>
                       <tr style="background: antiquewhite;">
                          <th nowrap class="text-center"><b>Particular</b></th>
                          <th class="text-center"><b>Cost/Pcs</b></th>
                          <th class="text-center"><b>% Of FOB</b></th>
                       </tr>
                       <tbody  class="col-md-4"  nowrap>
                          <tr class="col-md-4">
                             <td class="col-md-2" nowrap>Total  Fabric Cost</td>
                             <td class="col-md-2 alignRight">{{ number_format($BuyerCostingMaster[0]->fabric_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOffabric, 2, '.', '') }} %</td>
                          </tr>
                          <tr class="col-md-4">
                             <td class="col-md-2" nowrap> Trims Cost</td>
                             @php $trimCost=$BuyerCostingMaster[0]->sewing_trims_value + $BuyerCostingMaster[0]->packing_trims_value ; 
                             $totalCost=$BuyerCostingMaster[0]->embroidery_value + $BuyerCostingMaster[0]->printing_value + $BuyerCostingMaster[0]->dbk_value + $BuyerCostingMaster[0]->fabric_value + $BuyerCostingMaster[0]->production_value + $BuyerCostingMaster[0]->sewing_trims_value + $BuyerCostingMaster[0]->packing_trims_value
                             @endphp
                             <td class="col-md-2 alignRight">{{number_format($trimCost,2) }}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)($percentOfsewing_trims_value + $percentOfpacking_trims_value), 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Manufacturing Cost</td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->production_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfproduction_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Garment Washing Cost </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->dbk_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfdbk_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Printing Cost </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->printing_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfprinting_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Embroidery Cost </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->embroidery_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr class="bgYel">
                             <td class="col-md-2" nowrap>Total Making Cost </td>
                             <td class="col-md-2 alignRight">{{  number_format($totalCost,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$totalmakingper, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Garment Rejection  </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->garment_reject_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>IXD Cost </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->ixd_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfixd_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Commission Cost</td>
                             <td class="col-md-2 alignRight">{{ number_format($BuyerCostingMaster[0]->agent_commission_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Transport Cost</td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->transport_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOftransaport_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Overhead Cost</td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->other_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfother_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Testing Charges   </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->testing_charges_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Finance Cost   </td>
                             <td class="col-md-2 alignRight">{{  number_format($BuyerCostingMaster[0]->finance_cost_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Other Cost Value </td>
                             <td class="col-md-2 alignRight">{{ number_format($BuyerCostingMaster[0]->extra_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfextra_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>Total Cost</td>
                             <td class="col-md-2 alignRight">{{ number_format($BuyerCostingMaster[0]->total_cost_value,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             <td class="col-md-2" nowrap>DBK Value 1</td>
                             <td class="col-md-2 alignRight">{{ number_format($BuyerCostingMaster[0]->dbk_value1,2)}}</td>
                             <td class="col-md-2 alignRight" nowrap>{{ number_format((float)$percentOfdbk_value1, 2, '.', '') }} %</td>
                          </tr>
                          <tr>
                             @php
                             $profit_value=0.0;
                             $profit_value=  ($BuyerCostingMaster[0]->fob_rate - $BuyerCostingMaster[0]->total_cost_value + $BuyerCostingMaster[0]->dbk_value1);
                             if($profit_value > 0 && $BuyerCostingMaster[0]->fob_rate > 0)
                             {
                                $profitpercentage= (($profit_value / $BuyerCostingMaster[0]->fob_rate) * 100);
                             }
                             else
                             {
                                $profitpercentage = 0;
                             }
                            
                             @endphp
                             <td class="col-md-2">Profit %:</td>
                             <td class="col-md-2 alignRight">{{number_format($profit_value,2) }}</td>
                             @php
                                if($BuyerCostingMaster[0]->order_type == 3)
                                {
                             @endphp
                                    <td class="col-md-2 alignRight">--</td>
                              @php
                                }
                                else
                                {
                             @endphp
                                     <td class="col-md-2 alignRight" nowrap>{{number_format((float)$profitpercentage,2, '.', '')}} %</td>
                             @php
                                } 
                             @endphp
                            
                          </tr>
                          <tr>
                             <td class="col-md-2">FOB Rate ({{$BuyerCostingMaster[0]->currency_name}}): </td>
                             <td class="col-md-2 alignRight">{{ money_format('%!i',$BuyerCostingMaster[0]->inr_rate) }} </td>
                             <td class="col-md-2 alignRight" nowrap>--</td>
                          </tr>
                          <tr>
                             <td class="col-md-2"> Exchange Rate: </td>
                             <td class="col-md-2 alignRight"> {{ money_format('%!i',$BuyerCostingMaster[0]->exchange_rate) }}</td>
                             <td class="col-md-2 alignRight" nowrap>--</td>
                          </tr>
                          <tr>
                             <td class="col-md-2"> Order Rate (INR) :</td>
                             <td class="col-md-2 alignRight"> {{ money_format('%!i',$BuyerCostingMaster[0]->fob_rate) }} </td>
                             <td class="col-md-2 alignRight" nowrap>--</td>
                          </tr >
                          <tr>
                             <td class="col-md-2"> CMOHP :</td>
                             @php
                             
                                $profit_value=0.0;
                                $profit_value =  ($BuyerCostingMaster[0]->fob_rate -  $BuyerCostingMaster[0]->total_cost_value + $BuyerCostingMaster[0]->dbk_value1);
                       
                                $cmohp1 = $BuyerCostingMaster[0]->production_value + $profit_value + $BuyerCostingMaster[0]->other_value;
                               
                                $cmohp = $cmohp1;
                              
                              
                               if($BuyerCostingMaster[0]->production_value > 0 && $BuyerCostingMaster[0]->sam > 0)
                               {
                                    $cm_sam = ($BuyerCostingMaster[0]->production_value/$BuyerCostingMaster[0]->sam);
                               }
                               else
                               {
                                    $cm_sam = 0;
                               }
                           
                             $OHP = ((($BuyerCostingMaster[0]->other_value+$profit_value)*$BuyerCostingMaster[0]->total_qty));
                             $cmohpval = ($BuyerCostingMaster[0]->production_value  + $BuyerCostingMaster[0]->other_value + $profit_value);
                               
                             if($cmohpval>0 && $BuyerCostingMaster[0]->sam > 0)
                             { 
                                $cmohp_value = ($cmohpval)/$BuyerCostingMaster[0]->sam;
                             }
                             else
                             {
                                $cmohp_value = 0;
                             }
                             @endphp
                             <td class="col-md-2 alignRight"> {{ money_format('%!i',$cmohp_value) }} </td>
                             <td class="col-md-2 alignRight" nowrap>--</td>
                          </tr > 
                       </tbody>
                    </table>
                 </div>
                  <div class="col-md-3" ></div>
              </div>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;margin-top:50px;">Fabric Costing Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="border: 3px solid black;">
                        <tr  style="background-color:#eee; text-align:center;">
                           <th style="border-right: 3px solid black;">Item</th>
                           <th style="border-right: 3px solid black;">Consumption</th>
                           <th style="border-right: 3px solid black;">Rate</th>
                           <th style="border-right: 3px solid black;">Wastage %</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $FabricList = App\Models\BuyerFabricCostingDetailModel::where('fabric_buyer_costing_details.sr_no','=', $BuyerCostingMaster[0]->sr_no)->get(['fabric_buyer_costing_details.*']);
                        
                        $no=1; 
                        $fabricTotalAmt=0;
                        @endphp
                        @foreach($FabricList as $rowDetail)  
                        <tr>
                           <td class="text-left">{{ $rowDetail->item_name }}</td> 
                           <td class="alignRight">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                           <td class="alignRight">{{number_format(round($rowDetail->rate_per_unit,2),2)  }}</td>
                           <td class="alignRight">{{ number_format(round($rowDetail->wastage,2),2) }}</td> 
                           <td class="alignRight">{{ number_format(round($rowDetail->total_amount,2),2) }}</td>
                        </tr>
                        @php $fabricTotalAmt=$fabricTotalAmt + $rowDetail->total_amount; @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th> </th>
                        <th> </th>
                        <th> </th>
                        <th class="text-center">Total </th>
                        <th class="alignRight">{{ number_format(round($fabricTotalAmt,2),2) }} </th>
                     </tfoot>
                  </table>
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims Costing Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="border: 3px solid black;">
                        <tr  style="background-color:#eee; text-align:center;">
                           <th style="border-right: 3px solid black;">Item Name</th> 
                           <th style="border-right: 3px solid black;">Consumption</th>
                           <th style="border-right: 3px solid black;">Rate</th>
                           <th style="border-right: 3px solid black;">Wastage %</th> 
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $SewingTrimsList = App\Models\BuyerSewingCostingDetailModel::where('sewing_buyer_costing_details.sr_no','=', $BuyerCostingMaster[0]->sr_no)->get(['sewing_buyer_costing_details.*']); 
                        $no=1; 
                        $SewingTotalAmt=0;
                        @endphp
                        @foreach($SewingTrimsList as $rowDetail)  
                        <tr>
                           <td class="text-left">{{ $rowDetail->item_name }}</td> 
                           <td class="alignRight">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                           <td class="alignRight">{{number_format(round($rowDetail->rate_per_unit,2),2)  }}</td>
                           <td class="alignRight">{{ number_format(round($rowDetail->wastage,2),2) }}</td> 
                           <td class="alignRight">{{ number_format(round($rowDetail->total_amount,2),2) }}</td>
                        </tr>
                        @php $SewingTotalAmt=$SewingTotalAmt + $rowDetail->total_amount;@endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th> </th>
                        <th> </th>
                        <th> </th>
                        <th class="text-center">Total </th>
                        <th class="alignRight">{{ number_format(round($SewingTotalAmt,2),2)}} </th>
                     </tfoot>
                  </table>
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Costing Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="border: 3px solid black;">
                        <tr  style="background-color:#eee; text-align:center;">
                           <th style="border-right: 3px solid black;">Item Name</th> 
                           <th style="border-right: 3px solid black;">Consumption</th>
                           <th style="border-right: 3px solid black;">Rate</th>
                           <th style="border-right: 3px solid black;">Wastage %</th> 
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $PackingTrimsList = App\Models\BuyerPackingCostingDetailModel::where('packing_buyer_costing_details.sr_no','=', $BuyerCostingMaster[0]->sr_no)->get(['packing_buyer_costing_details.*']);
                        $no=1; 
                        $PackingTotalAmt=0;
                        @endphp
                        @foreach($PackingTrimsList as $rowDetail)  
                        <tr>
                           <td class="text-left">{{ $rowDetail->item_name }}</td> 
                           <td class="alignRight">{{ number_format(round($rowDetail->consumption,2),2) }}</td>
                           <td class="alignRight">{{number_format(round($rowDetail->rate_per_unit,2),2)  }}</td>
                           <td class="alignRight">{{ number_format(round($rowDetail->wastage,2),2) }}</td> 
                           <td class="alignRight">{{number_format(round($rowDetail->total_amount,2),2) }}</td>
                        </tr>
                        @php $PackingTotalAmt=$PackingTotalAmt + $rowDetail->total_amount; @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th> </th>
                        <th> </th>
                        <th> </th>
                        <th class="text-center">Total </th>
                        <th class="alignRight">{{ number_format(round($PackingTotalAmt,2),2)}} </th>
                     </tfoot>
                  </table>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-9">
                        <h4 class="text-4 mt-2">Comments:</h4>
                        {{$BuyerCostingMaster[0]->narration}}
                     </div>
                     <div class="col-md-3">
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <!-- Fare Details -->
                  </div>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-9">
                     </div>
                     <div class="col-md-3">
                        <h4 class="text-4 mt-2">Approved By:</h4>
                     </div>
                  </div>
                  <!-- Footer -->
                  <footer>
                     <div class="btn-group d-print-none"> <a  href="javascript:window.print()" class="btn btn-info"> Print</a> </div>
                     <button type="button" id="export_button" class="btn btn-warning">Export</button>  
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <input type="hidden" id="todaysDate" value="{{date('d-m-Y')}}"> 
      <p class="text-center d-print-none"><a href="/BuyerCosting">&laquo; Back to List</a></p>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
      <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
      <script> 
         function html_table_to_excel(type)
         {
             var data = document.getElementById('invoice');
             var todaysDate = $("#todaysDate").val();
         
             var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
         
             XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
         
             XLSX.writeFile(file, 'Buyer Costing Sheet('+todaysDate+').' + type);
         }
         
          const export_button = document.getElementById('export_button');
         
          export_button.addEventListener('click', () =>  {
             html_table_to_excel('xlsx');
         });
         
        //  $('#printInvoice').click(function(){
        //          Popup($('.invoice')[0].outerHTML);
        //          function Popup(data) 
        //          {
        //              window.print();
        //              return true;
        //          }
        //      });
         
        //   $('title').html("Costing Sheet");
      </script>
   </body>
</html>