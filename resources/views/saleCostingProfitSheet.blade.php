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
<center><h4 class="mb-0" style="font-weight:bold;">Costing Profit Sheet</h4></center>
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
     background-image: url('https://kenerp.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
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
      <b style="display: inline-block;text-align: left;">Garment SAM:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->sam }} </span> 
     
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
  
	@php 
	        $percentOffabric =($SalesOrderCostingMaster[0]->fabric_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOfsewing_trims_value=($SalesOrderCostingMaster[0]->sewing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOfpacking_trims_value=($SalesOrderCostingMaster[0]->packing_trims_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOfproduction_value=($SalesOrderCostingMaster[0]->production_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOfagent_commision_value=($SalesOrderCostingMaster[0]->agent_commision_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOftransaport_value=($SalesOrderCostingMaster[0]->transaport_value / $SalesOrderCostingMaster[0]->order_rate) * 100; 
	        $percentOfother_value=($SalesOrderCostingMaster[0]->other_value / $SalesOrderCostingMaster[0]->order_rate) * 100;
	        $percentOfdbk_value=($SalesOrderCostingMaster[0]->dbk_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	        $percentOfgarment_reject_value=($SalesOrderCostingMaster[0]->garment_reject_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	        $percentOftesting_charges_value=($SalesOrderCostingMaster[0]->testing_charges_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	        $percentOffinance_cost_value=($SalesOrderCostingMaster[0]->finance_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	        $percentOfextra_value=($SalesOrderCostingMaster[0]->extra_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	        $percentOftotal_cost_value=($SalesOrderCostingMaster[0]->total_cost_value / $SalesOrderCostingMaster[0]->order_rate) * 100;  
	      
 	@endphp

<div class="row">
 
<table  style="border: 1px solid black; font-weight:bold;" >
         <tr>
            <th colspan="3" style="text-align:center;">Total Summary</th>
            
        </tr>
        <tr>
             <th> Buyer Name</th>
            <th> Sales Order No</th>
            <th>Order Received Date</th>
             <th> Style No</th>
              <th> Main Style</th>
               <th> Sub Style</th>
            <td class="col-md-2">Total  Fabric Cost</td> 
            <th>% Of FOB</th>
            <td class="col-md-2">Sewing Trims Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Packing Trims Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Manufacturing Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Commission Cos</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Transport Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">DBK Value:</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Garment Rejection Value:</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Testing Charges Value:</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Finance Cost Value:</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Other Value:</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Overhead Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Total Cost</td>
            <th>% Of FOB</th>
            <td class="col-md-2">Profit:</td>
            <th>% Of FOB</th>
        </tr>
 
    <tbody  class="col-md-4">
        <tr class="col-md-4">
          <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->Ac_name }}</td> 
         <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->sales_order_no }}</td> 
           <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->style_no }}</td> 
           <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->order_received_date }}</td> 
           
           
            <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->mainstyle_name }}</td> 
             <td class="col-md-2"> {{ $SalesOrderCostingMaster[0]->substyle_name }}</td> 
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->fabric_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOffabric, 2, '.', '') }}%</td> 
        
            
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->sewing_trims_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfsewing_trims_value, 2, '.', '') }}%</td>
        
            
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->packing_trims_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfpacking_trims_value, 2, '.', '') }}%</td>
        
           
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->production_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfproduction_value, 2, '.', '') }}%</td>
        
            
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->agent_commision_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfagent_commision_value, 2, '.', '') }}%</td>
       
            
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->transaport_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOftransaport_value, 2, '.', '') }}%</td>
        
           
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->other_value }}</td>
            <td class="col-md-2">{{ number_format((float)$percentOfother_value, 2, '.', '') }}%</td>
         
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->garment_reject_value }}</td>
            <td class="col-md-2">{{ number_format((float)$percentOfgarment_reject_value, 2, '.', '') }}%</td>
             
              <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->testing_charges_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOftesting_charges_value, 2, '.', '') }}%</td>
             
              <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->finance_cost_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOffinance_cost_value, 2, '.', '') }}%</td>
             
              <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->extra_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOfextra_value, 2, '.', '') }}%</td>
              
           
            <td class="col-md-2">{{ $SalesOrderCostingMaster[0]->total_cost_value }}</td>
             <td class="col-md-2">{{ number_format((float)$percentOftotal_cost_value, 2, '.', '') }}%</td>
        
            @php
        
        	$profit_value=0.0;
        	$profit_value=  ($SalesOrderCostingMaster[0]->order_rate - $SalesOrderCostingMaster[0]->total_cost_value);
            $profitpercentage= (($profit_value / $SalesOrderCostingMaster[0]->order_rate) * 100);
        
        @endphp
        
        
            
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

<script src="{{ URL::asset('https://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>

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