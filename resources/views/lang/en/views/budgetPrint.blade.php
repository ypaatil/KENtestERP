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
<!-- Item Details -->
<div class="row">
    
<div class="col-md-4">    
<p><img src="http://kenerp.org/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>   

</div>
<div class="col-md-6">    
<h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
</div>
<div class="col-md-2">    
<h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($BOMList[0]->bom_date)) }}</h6>
</div>
</div>



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
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
     <b style="display: inline-block;text-align: left;" class="mt-1">Order Ref No:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->bom_code }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Buyer: </b>  <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->Ac_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Cost Type :</b>  <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->cost_type_name }} </span></br>

</div>
        <div  class="col-md-4">         
              <b style="display: inline-block;text-align: left;" class="mt-1">Season:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->season_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Currency:</b>  <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->currency_name }}</span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Order No:</b>  <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->bom_code }}</span></br>
        </div>

    <div  class="col-md-4" >
   
        </div>
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
    <th>Item Name</th>
      <th>Colors</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Unit</th>
    <th>Rate</th>
    <th>Wastage %</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 

      $FabricList = App\Models\BOMFabricDetailModel::
      join('item_master','item_master.item_code','=','bom_fabric_details.item_code')
      ->join('classification_master','classification_master.class_id','=','bom_fabric_details.class_id') 
       ->join('unit_master','unit_master.unit_id','=','bom_fabric_details.unit_id')  
       
     ->where('bom_fabric_details.bom_code','=', $BOMList[0]->bom_code)->get();   
        
        
$no=1; 

@endphp


@foreach($FabricList as $rowDetail)  

<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetail->item_name }}</td>

@php
  
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('item_code','=',$rowDetail->item_code)->where('tr_code','=',$BOMList[0]->sales_order_no)->DISTINCT()->get();
    
     $data='';
     foreach($ColorList as $row)
     {
       $data=$data.$row->color_name.', ';
     }


@endphp

<td>{{rtrim($data,',')}}</td>

<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description  }}</td>
<td>{{ $rowDetail->consumption  }}</td>
<td>{{ $rowDetail->unit_name  }}</td>
<td>{{ $rowDetail->rate_per_unit  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->bom_qty  }}</td>
<td>{{ $rowDetail->total_amount  }}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach
</tbody>
</table>


<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims:</h4>

<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
    <th>Item Name</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Unit</th>
    <th>Rate</th>
    <th>Wastage %</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 
 
     $SewingTrimsList = App\Models\BOMSewingTrimsDetailModel::
        join('item_master','item_master.item_code','=','bom_sewing_trims_details.item_code')
      ->join('classification_master','classification_master.class_id','=','bom_sewing_trims_details.class_id') 
       ->join('unit_master','unit_master.unit_id','=','bom_sewing_trims_details.unit_id') 
       
     ->where('bom_sewing_trims_details.bom_code','=', $BOMList[0]->bom_code)->get();    
        
$no=1; 

@endphp


 
@foreach($SewingTrimsList as $rowDetailtrims)  



@php 

$color_ids = explode(',', $rowDetailtrims->color_id);  

$ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

   
@endphp

<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetailtrims->item_name }}</td>
<td>{{ $rowDetailtrims->class_name }}</td>
<td>{{ $rowDetailtrims->description  }}</td>
<td>{{ $rowDetailtrims->consumption  }}</td>
<td>{{ $rowDetailtrims->unit_name  }}</td>
<td>{{ $rowDetailtrims->rate_per_unit  }}</td>
<td>{{ $rowDetailtrims->wastage  }}</td>
<td>{{ $rowDetailtrims->bom_qty  }}</td>
<td>{{ $rowDetailtrims->total_amount  }}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach
</tbody>
</table>




<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims:</h4>

<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
    <th>Item Name</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Unit</th>
    <th>Rate</th>
    <th>Wastage %</th>
    <th>BOM Qty</th>
    <th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 
   
      $PackingTrimsList = App\Models\BOMPackingTrimsDetailModel::leftJoin('item_master','item_master.item_code','=','bom_packing_trims_details.item_code')
      ->leftJoin('classification_master','classification_master.class_id','=','bom_packing_trims_details.class_id') 
       ->leftJoin('unit_master','unit_master.unit_id','=','bom_packing_trims_details.unit_id') 
      ->where('bom_packing_trims_details.bom_code','=', $BOMList[0]->bom_code)->get();
 
$nos=1; 

@endphp


 
@foreach($PackingTrimsList as $rowDetailpacking)  

<tr>
<td>{{ $nos }}</td>    
<td>{{ $rowDetailpacking->item_name }}</td>
<td>{{ $rowDetailpacking->class_name }}</td>
<td>{{ $rowDetailpacking->description  }}</td>
<td>{{ $rowDetailpacking->consumption  }}</td>
<td>{{ $rowDetailpacking->unit_name  }}</td>
<td>{{ $rowDetailpacking->rate_per_unit  }}</td>
<td>{{ $rowDetailpacking->wastage  }}</td>
<td>{{ $rowDetailpacking->bom_qty  }}</td>
<td>{{ $rowDetailpacking->total_amount  }}</td>
</tr>
@php

$nos=$nos+1;
@endphp
@endforeach
</tbody>
</table>


<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
    <th>Total Fabric Cost</th>
    <th>Sewing Trims Cost</th>
    <th>Packing Trims Cost</th>
    <th>Total Cost</th>
</tr>
</thead>
<tbody>

<tr>
<td>{{ $BOMList[0]->fabric_value }}</td>    
<td>{{ $BOMList[0]->sewing_trims_value }}</td>
<td>{{ $BOMList[0]->packing_trims_value }}</td>
<td>{{ $BOMList[0]->total_cost_value  }}</td>
</tr>

</tbody>
</table>

   <div class="row">
    <!-- Fare Details -->
<div class="col-md-3">
<h4 class="text-4 mt-2">Prepared By:</h4>

 </div>
<div class="col-md-3">
 <h4 class="text-4 mt-2">Checked By:</h4>
    
    </div>
    <div class="col-md-3">
 <h4 class="text-4 mt-2">Approved By:</h4>
    
    </div>
    <div class="col-md-3">
 <h4 class="text-4 mt-2">Authorized By:</h4>
    
    </div>
    </div><br>
  


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