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

<div class="invoice">
<!-- Main Content -->
<main>
<center><h4 class="mb-0" style="font-weight:bold;">Costing Sheet</h4></center>
<!-- Item Details -->
<h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>




<h4 class="text-4"></h4>
<div class="">
</div>
<style>

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
     background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
}



</style>

<div id="printInvoice">
    <div class="row">
    <div  class="col-md-4">
      <p><b style="display: inline-block;text-align: left;">Buyer:  </b> <span style="display: inline-block;text-align: right;"> {{ $SalesOrderCostingMaster[0]->Ac_name }} </span></p>
      <b style="display: inline-block;text-align: left;">Style: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->style_no }} </span></br>
      <b style="display: inline-block;text-align: left;">Season :</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->season_name }} </span></br>
      <b style="display: inline-block;text-align: left;">Currency:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->currency_name }} </span></br>
      <b style="display: inline-block;text-align: left;">Order Rate:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->order_rate }} </span></br>
     
</div> 
        <div  class="col-md-4">
     <p><b style="display: inline-block;text-align: left;">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $SalesOrderCostingMaster[0]->sales_order_no }} </span></p>
      <b style="display: inline-block;text-align: left;">Main Style Category: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->mainstyle_name }} </span></br>
      <b style="display: inline-block;text-align: left;">Sub Style Category :</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->substyle_name }} </span></br>
      <b style="display: inline-block;text-align: left;">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->style_no }} </span></br>
      <b style="display: inline-block;text-align: left;">Style Description:</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->style_description }}</span></br>
       
       </div>

    <div  class="col-md-4">
        <p><img src="{{url('thumbnail/'.$SalesOrderCostingMaster[0]->style_img_path)}}"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>   
        </div>
    </div>

</div>
<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Costing:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
    <th>Item</th>
    <th>Description</th>
    <th>Consumation</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 

        $FabricList = App\Models\SalesOrderFabricCostingDetailModel::join('classification_master','classification_master.class_id','=','sales_order_fabric_costing_details.class_id')
        ->where('sales_order_fabric_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_fabric_costing_details.description','sales_order_fabric_costing_details.consumption',
        'sales_order_fabric_costing_details.rate_per_unit',
        'sales_order_fabric_costing_details.wastage','sales_order_fabric_costing_details.bom_qty','sales_order_fabric_costing_details.total_amount']);


        $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::join('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
        ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
        'sales_order_sewing_trims_costing_details.rate_per_unit',
        'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);


        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();

$no=1; 

@endphp


@foreach($FabricList as $rowDetail)  

<tr>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description }}</td>
<td>{{ $rowDetail->consumption }}</td>
<td>{{ $rowDetail->rate_per_unit  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->bom_qty  }}</td>
<td>{{ $rowDetail->total_amount  }}</td>
</tr>
@endforeach
</tbody>
</table>

 
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims Costing:</h4>
 <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
    <th>Classification</th>
    <th>Description</th>
    <th>Consumation</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 


        $SewingTrimsList = App\Models\SalesOrderSewingTrimsCostingDetailModel::join('classification_master','classification_master.class_id','=','sales_order_sewing_trims_costing_details.class_id')
        ->where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_sewing_trims_costing_details.description','sales_order_sewing_trims_costing_details.consumption',
        'sales_order_sewing_trims_costing_details.rate_per_unit',
        'sales_order_sewing_trims_costing_details.wastage','sales_order_sewing_trims_costing_details.bom_qty','sales_order_sewing_trims_costing_details.total_amount']);


        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get();

$no=1; 

@endphp


@foreach($SewingTrimsList as $rowDetail)  

<tr>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description }}</td>
<td>{{ $rowDetail->consumption }}</td>
<td>{{ $rowDetail->rate_per_unit  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->bom_qty  }}</td>
<td>{{ $rowDetail->total_amount  }}</td>
</tr>
@endforeach
</tbody>
</table>




<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Costing:</h4>
 <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
    <th>Classification</th>
    <th>Description</th>
    <th>Consumation</th>
    <th>Rate</th>
    <th>Wastage</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 


        $PackingTrimsList = App\Models\SalesOrderPackingTrimsCostingDetailModel::join('classification_master','classification_master.class_id','=','sales_order_packing_trims_costing_details.class_id')->where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMaster[0]->soc_code)->get(['classification_master.class_name','sales_order_packing_trims_costing_details.description','sales_order_packing_trims_costing_details.consumption',
        'sales_order_packing_trims_costing_details.rate_per_unit',
        'sales_order_packing_trims_costing_details.wastage','sales_order_packing_trims_costing_details.bom_qty','sales_order_packing_trims_costing_details.total_amount']);

$no=1; 

@endphp


@foreach($PackingTrimsList as $rowDetail)  

<tr>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description }}</td>
<td>{{ $rowDetail->consumption }}</td>
<td>{{ $rowDetail->rate_per_unit  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->bom_qty  }}</td>
<td>{{ $rowDetail->total_amount  }}</td>
</tr>
@endforeach
</tbody>
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
	        $percentOftotal_cost_value=($SalesOrderCostingMaster[0]->total_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	      
 	@endphp

<div class="row">
<div class="col-md-8" >&nbsp;</div>
<div class="col-md-4"  >
<table  style="border: 1px solid black; font-weight:bold;" >
    
      <tr>
            <th>Particular</th>
            <th>Cost/Pcs</th>
            <th>% Of FOB</th>
        </tr>
 
    <tbody  class="col-md-4">
        <tr class="col-md-4">
            <td class="col-md-2">Total  Fabric Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->fabric_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOffabric, 2, '.', '') }}%</td> 
        </tr>
        <tr class="col-md-4">
            <td class="col-md-2">Sewing Trims Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->sewing_trims_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Packing Trims Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->packing_trims_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Manufacturing Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->production_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Commission Cos</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->agent_commision_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Transport Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->transaport_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Overhead Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->other_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">DBK Value:</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->dbk_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfdbk_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            <td class="col-md-2">Total Cost</td>
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->total_cost_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
        </tr>
        <tr>
            @php
        
        	$profit_value=0.0;
        	$profit_value=  ($SalesOrderCostingMaster[0]->order_rate - $SalesOrderCostingMaster[0]->total_cost_value);
            $profitpercentage= (($profit_value / $SalesOrderCostingMaster[0]->order_rate) * 100);
        
        @endphp
        
        
            <td class="col-md-2">Profit %:</td>
            <td class="col-md-2">{{number_format((float)$profit_value, 2, '.', '')}}</td>
             <td class="col-md-2">{{number_format((float)$profitpercentage, 2, '.', '')}} %</td>
        </tr>
          
    </tbody>

 </table>
     <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
        }
        th {
            text-align: left;
        }
    </style>
 
 
 
</div>
</div>







   <div class="row">
    <!-- Fare Details -->
<div class="col-md-9">
<h4 class="text-4 mt-2">Comments:</h4>

 </div>
<div class="col-md-3">
 
    
    </div>
    </div><br>
  
   <div class="row">
    <!-- Fare Details -->
<div class="col-md-9">
<h4 class="text-4 mt-2">Costing Done By:</h4>

 </div>
<div class="col-md-3">
 
   <h4 class="text-4 mt-2">Modified By:</h4>
    
    </div>
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
<footer  >
 




<div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
</footer>
</div>
</main>
</div>
</div>
 

<p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
</body>

<script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
 
<script>  $('#printInvoice').click(function(){
            Popup($('.invoice')[0].outerHTML);
            function Popup(data) 
            {
                window.print();
                return true;
            }
        });
		
		
		</script>

</html>