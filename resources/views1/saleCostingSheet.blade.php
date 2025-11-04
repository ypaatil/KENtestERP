<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Enterprises Pvt. Ltd.</title>
      <meta name="author" content="">
      <!-- Web Fonts
         ======================= -->
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
      <!-- Stylesheet
         ======================= -->
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/bootstrap.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/all.min.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('InvoiceAssets/style.css') }}"/>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <style>
         .table-bordered td, .table-bordered th {
         border: 1px solid #0c0c0c;
         body{
         font-family: "Times New Roman", Times, serif;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0" style="font-weight:bold;">Costing Sheet</h4>
               </center>
               <!-- Item Details -->
               <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
               <h4 class="text-4"></h4>
               <div class=""></div>
               <style>
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
                  background-image: url('https://ken.korbofx.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row">
                     <div  class="col-md-4">
                        <p><b style="display: inline-block;text-align: left;">Buyer:  </b> <span style="display: inline-block;text-align: right;"> {{ $SalesOrderCostingMaster[0]->Ac_name }} </span></p>
                        <b style="display: inline-block;text-align: left;">Style: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->style_no }} </span></br>
                        <b style="display: inline-block;text-align: left;">Season :</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->season_name }} </span></br>
                        <b style="display: inline-block;text-align: left;">Currency:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->currency_name }} </span></br>
                        <b style="display: inline-block;text-align: left;">Rate ({{$SalesOrderCostingMaster[0]->currency_name}}):</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$SalesOrderCostingMaster[0]->inr_rate) }} </span></br>
                        <b style="display: inline-block;text-align: left;">Exchange Rate:</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$SalesOrderCostingMaster[0]->exchange_rate) }} </span></br>
                        <b style="display: inline-block;text-align: left;">Order Rate (INR):</b>    <span style="display: inline-block;text-align: right;">{{ money_format('%!i',$SalesOrderCostingMaster[0]->order_rate) }} </span></br>
                     </div>
                     <div  class="col-md-4">
                        <p><b style="display: inline-block;text-align: left;">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $SalesOrderCostingMaster[0]->sales_order_no }} </span></p>
                        <b style="display: inline-block;text-align: left;">Main Style Category: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->mainstyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;">Sub Style Category :</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->substyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->style_no }} </span></br>
                        <b style="display: inline-block;text-align: left;">Style Description:</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->style_description }}</span></br>
                        <b style="display: inline-block;text-align: left;">Garment SAM:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->sam }} </span> </br>
                        <b style="display: inline-block;text-align: left;">Order Qty:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($SalesOrderCostingMaster[0]->total_qty) }}</span></br>
                     </div>
                     <div  class="col-md-4">
                        <p><img src="@if($SalesOrderCostingMaster[0]->style_img_path!=''){{url('thumbnail/'.$SalesOrderCostingMaster[0]->style_img_path)}}@else https://ken.korbofx.com/logo/ken.jpeg @endif"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Costing Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>Item</th>
                           <th>Description</th>
                           <th>Consumption</th>
                           <th>Rate</th>
                           <th>Wastage%</th>
                           <th>BOM Qty</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $FabricList = App\Models\SalesOrderFabricCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_fabric_costing_details.class_id')
                        ->where('sales_order_fabric_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_fabric_costing_details.description','sales_order_fabric_costing_details.consumption',
                        'sales_order_fabric_costing_details.rate_per_unit',
                        'sales_order_fabric_costing_details.wastage','sales_order_fabric_costing_details.bom_qty','sales_order_fabric_costing_details.total_amount']);
                        $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
                        ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
                        'sales_order_sewing_trims_costing_details.rate_per_unit',
                        'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);
                        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();
                        $no=1; 
                        $fabricTotalAmt=0;
                        @endphp
                        @foreach($FabricList as $rowDetail)  
                        <tr>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description }}</td>
                           <td>{{ number_format($rowDetail->consumption,4) }}</td>
                           <td class="alignRight">{{number_format($rowDetail->rate_per_unit,4)  }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->wastage,4) }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->bom_qty,4) }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->total_amount,4) }}</td>
                        </tr>
                        @php $fabricTotalAmt=$fabricTotalAmt + $rowDetail->total_amount; @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th >Total </th>
                        <th class="alignRight">{{ number_format($fabricTotalAmt,4) }} </th>
                     </tfoot>
                  </table>
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims Costing Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Consumption</th>
                           <th>Rate</th>
                           <th>Wastage%</th>
                           <th>BOM Qty</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
                        ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
                        'sales_order_sewing_trims_costing_details.rate_per_unit',
                        'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);
                        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();
                        $no=1; 
                        $SewingTotalAmt=0;
                        @endphp
                        @foreach($SewingTrimsList as $rowDetail)  
                        <tr>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->consumption,4) }}</td>
                           <td class="alignRight">{{number_format($rowDetail->rate_per_unit,4)  }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->wastage,4) }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->bom_qty,4) }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->total_amount,4) }}</td>
                        </tr>
                        @php $SewingTotalAmt=$SewingTotalAmt + $rowDetail->total_amount;@endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th >Total </th>
                        <th class="alignRight">{{ number_format($SewingTotalAmt,4)}} </th>
                     </tfoot>
                  </table>
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Costing Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Consumption</th>
                           <th>Rate</th>
                           <th>Wastage%</th>
                           <th>BOM Qty</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::leftJoin('classification_master','classification_master.class_id','=','sales_order_packing_trims_costing_details.class_id')->where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_packing_trims_costing_details.description','sales_order_packing_trims_costing_details.consumption',
                        'sales_order_packing_trims_costing_details.rate_per_unit',
                        'sales_order_packing_trims_costing_details.wastage','sales_order_packing_trims_costing_details.bom_qty','sales_order_packing_trims_costing_details.total_amount']);
                        $no=1; 
                        $PackingTotalAmt=0;
                        @endphp
                        @foreach($PackingTrimsList as $rowDetail)  
                        <tr>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->consumption,4) }}</td>
                           <td class="alignRight">{{number_format($rowDetail->rate_per_unit,4)  }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->wastage,4) }}</td>
                           <td class="alignRight">{{ number_format($rowDetail->bom_qty,4) }}</td>
                           <td class="alignRight">{{number_format($rowDetail->total_amount,4) }}</td>
                        </tr>
                        @php $PackingTotalAmt=$PackingTotalAmt + $rowDetail->total_amount; @endphp
                        @endforeach
                     </tbody>
                     <tfoot>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th > </th>
                        <th >Total </th>
                        <th class="alignRight">{{ number_format($PackingTotalAmt,4)}} </th>
                     </tfoot>
                  </table>
                  @php 
                  $percentOffabric =($SalesOrderCostingMaster[0]->fabric_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOfsewing_trims_value=($SalesOrderCostingMaster[0]->sewing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOfpacking_trims_value=($SalesOrderCostingMaster[0]->packing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOfproduction_value=($SalesOrderCostingMaster[0]->production_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOfagent_commision_value=($SalesOrderCostingMaster[0]->agent_commision_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOftransaport_value=($SalesOrderCostingMaster[0]->transaport_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
                  $percentOfother_value=($SalesOrderCostingMaster[0]->other_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
                  $percentOfdbk_value=($SalesOrderCostingMaster[0]->dbk_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOfprinting_value=($SalesOrderCostingMaster[0]->printing_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOfembroidery_value=($SalesOrderCostingMaster[0]->embroidery_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOfixd_value=($SalesOrderCostingMaster[0]->ixd_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOfgarment_reject_value=($SalesOrderCostingMaster[0]->garment_reject_value / ($SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->dbk_value)) * 100;  
                  $percentOftesting_charges_value=($SalesOrderCostingMaster[0]->testing_charges_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOffinance_cost_value=($SalesOrderCostingMaster[0]->finance_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOfextra_value=($SalesOrderCostingMaster[0]->extra_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $percentOftotal_cost_value=($SalesOrderCostingMaster[0]->total_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
                  $totalCost=$SalesOrderCostingMaster[0]->embroidery_value + $SalesOrderCostingMaster[0]->printing_value + $SalesOrderCostingMaster[0]->dbk_value + $SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value;
                  $totalmakingper=($totalCost/ $SalesOrderCostingMaster[0]->order_rate) * 100;
                  
                  $mainstyleData = DB::table('main_style_master')->select('mainstyle_image')->where('mainstyle_id', $SalesOrderCostingMaster[0]->mainstyle_id)->first(); 
                  
                  @endphp
                  <div class="row">
                     <div class="col-md-8" >
                        <img id="mainstyle_image" width="700" height="300" src="https://ken.korbofx.com/uploads/MainStyleImages/{{$mainstyleData->mainstyle_image}}">  
                     </div>
                     <div class="col-md-4"  >
                        <table  style="border: 1px solid black; font-weight:bold;" >
                           <tr>
                              <th colspan="3" style="text-align:center;">Total Summary</th>
                           </tr>
                           <tr>
                              <th nowrap>Particular</th>
                              <th>Cost/Pcs</th>
                              <th>% Of FOB</th>
                           </tr>
                           <tbody  class="col-md-4"  nowrap>
                              <tr class="col-md-4">
                                 <td class="col-md-2" nowrap>Total  Fabric Cost</td>
                                 <td class="col-md-2 alignRight">{{      number_format($SalesOrderCostingMaster[0]->fabric_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOffabric, 2, '.', '') }}%</td>
                              </tr>
                              <tr class="col-md-4">
                                 <td class="col-md-2" nowrap> Trims Cost</td>
                                 @php $trimCost=$SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value ; 
                                 $totalCost=$SalesOrderCostingMaster[0]->embroidery_value + $SalesOrderCostingMaster[0]->printing_value + $SalesOrderCostingMaster[0]->dbk_value + $SalesOrderCostingMaster[0]->fabric_value + $SalesOrderCostingMaster[0]->production_value + $SalesOrderCostingMaster[0]->sewing_trims_value + $SalesOrderCostingMaster[0]->packing_trims_value
                                 @endphp
                                 <td class="col-md-2 alignRight">{{number_format($trimCost,2) }}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)($percentOfsewing_trims_value + $percentOfpacking_trims_value), 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Manufacturing Cost</td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->production_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Garment Washing Cost </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->dbk_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Printing Cost </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->printing_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfprinting_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Embroidery Cost </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->embroidery_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfembroidery_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr class="bgYel">
                                 <td class="col-md-2" nowrap>Total Making Cost </td>
                                 <td class="col-md-2 alignRight">{{  number_format($totalCost,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$totalmakingper, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>IXD Cost </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->ixd_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfixd_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Commission Cost</td>
                                 <td class="col-md-2 alignRight">{{ number_format($SalesOrderCostingMaster[0]->agent_commision_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Transport Cost</td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->transaport_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Overhead Cost</td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->other_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Garment Rejection  </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->garment_reject_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Testing Charges   </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->testing_charges_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Finance Cost   </td>
                                 <td class="col-md-2 alignRight">{{  number_format($SalesOrderCostingMaster[0]->finance_cost_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Other Cost Value </td>
                                 <td class="col-md-2 alignRight">{{ number_format($SalesOrderCostingMaster[0]->extra_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOfextra_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2" nowrap>Total Cost</td>
                                 <td class="col-md-2 alignRight">{{ number_format($SalesOrderCostingMaster[0]->total_cost_value,2)}}</td>
                                 <td class="col-md-2 alignRight">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
                              </tr>
                              <tr>
                                 @php
                                 $profit_value=0.0;
                                 $profit_value=  ($SalesOrderCostingMaster[0]->order_rate - $SalesOrderCostingMaster[0]->total_cost_value);
                                 $profitpercentage= (($profit_value / $SalesOrderCostingMaster[0]->order_rate) * 100);
                                 @endphp
                                 <td class="col-md-2">Profit %:</td>
                                 <td class="col-md-2 alignRight">{{number_format($profit_value,2) }}</td>
                                 <td class="col-md-2 alignRight">{{number_format((float)$profitpercentage,2, '.', '')}} %</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2">FOB Rate ({{$SalesOrderCostingMaster[0]->currency_name}}): </td>
                                 <td class="col-md-2 alignRight">{{ money_format('%!i',$SalesOrderCostingMaster[0]->inr_rate) }} </td>
                                 <td class="col-md-2 alignRight" nowrap>--</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2"> Exchange Rate: </td>
                                 <td class="col-md-2 alignRight"> {{ money_format('%!i',$SalesOrderCostingMaster[0]->exchange_rate) }}</td>
                                 <td class="col-md-2 alignRight" nowrap>--</td>
                              </tr>
                              <tr>
                                 <td class="col-md-2"> Order Rate (INR) :</td>
                                 <td class="col-md-2 alignRight"> {{ money_format('%!i',$SalesOrderCostingMaster[0]->order_rate) }} </td>
                                 <td class="col-md-2 alignRight" nowrap>--</td>
                              </tr >
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-9">
                        <h4 class="text-4 mt-2">Comments:</h4>
                        {{$SalesOrderCostingMaster[0]->narration}}
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
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
      <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
      <script> 
         function html_table_to_excel(type)
         {
             var data = document.getElementById('invoice');
         
             var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
         
             XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
         
             XLSX.writeFile(file, 'Costing Sheet.' + type);
         }
         
          const export_button = document.getElementById('export_button');
         
          export_button.addEventListener('click', () =>  {
             html_table_to_excel('xlsx');
         });
         
         $('#printInvoice').click(function(){
                 Popup($('.invoice')[0].outerHTML);
                 function Popup(data) 
                 {
                     window.print();
                     return true;
                 }
             });
         
         
      </script>
   </body>
</html>