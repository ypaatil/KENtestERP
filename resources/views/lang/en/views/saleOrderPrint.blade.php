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
<h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($SalesOrderCostingMaster[0]->tr_date)) }}</h6>
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
     <b style="display: inline-block;text-align: left;" class="mt-1">Order Ref No /Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $SalesOrderCostingMaster[0]->tr_code }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Buyer: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->Ac_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Group :</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->order_group_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Season:</b>    <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->season_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Brand:</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->brand_name }}</span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Order No:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->tr_code }}</span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">PO status:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->job_status_name }}</span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Payment Terms:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->ptm_name }}</span></br>     
        
</div>
        <div  class="col-md-4">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Quantity:  </b> <span style="display: inline-block;text-align: right;"> {{  number_format($SalesOrderCostingMaster[0]->total_qty) }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Style: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->fg_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>  <span style="display: inline-block;text-align: right;">{{ $SalesOrderCostingMaster[0]->style_no }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Order Rate:</b>    <span style="display: inline-block;text-align: right;">{{ number_format($SalesOrderCostingMaster[0]->order_rate) }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Order Value:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($SalesOrderCostingMaster[0]->order_value) }}</span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Shipped Qty:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($SalesOrderCostingMaster[0]->shipped_qty) }}</span></br>
     <b style="display: inline-block;text-align: left;" class="mt-1">Balance Qty:</b>  <span style="display: inline-block;text-align: right;">{{ number_format($SalesOrderCostingMaster[0]->balance_qty) }}</span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Terms:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->delivery_term_name }}</span></br>  
        </div>

    <div  class="col-md-4" >
        <!--<p><img src="{{url('thumbnail/'.$SalesOrderCostingMaster[0]->style_img_path)}}"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>   -->
      <b style="display: inline-block;text-align: left;" class="mt-1">Destination: </b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->warehouse_name }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Shipment Mode:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrderCostingMaster[0]->ship_mode_name }}</span></br>  
        </div>
    </div>

</div>

 @php  $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $SalesOrderCostingMaster[0]->sz_code)->get();
 
 @endphp

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
 <th>SrNo</th>
  <th>Item Code</th>
 <th>Item Name</th>
 <th>Image</th>
<th>Color</th>
 @foreach($SizeDetailList as $sz) 
    <th>{{ $sz->size_name }}</th>
    @endforeach
<th>Total Qty</th>
<th>Unit</th>
<th>Shipment Allow %</th>
<th>Rejection Allow %</th>
</tr>
</thead>
<tbody>
   
@php 

       $BuyerPurchaseOrderDetaillist = App\Models\BuyerPurchaseOrderDetailModel::
       join('item_master','item_master.item_code','=','buyer_purchase_order_detail.item_code')
       ->join('color_master','color_master.color_id','=','buyer_purchase_order_detail.color_id') 
       ->join('unit_master','unit_master.unit_id','=','buyer_purchase_order_detail.unit_id')  
       ->where('buyer_purchase_order_detail.tr_code','=', $SalesOrderCostingMaster[0]->tr_code)
       ->get(['buyer_purchase_order_detail.*','item_master.item_name','item_master.item_image_path','color_master.color_name','unit_master.unit_name']);      
        
$no=1; 

$totalQty=0;

@endphp

@foreach($BuyerPurchaseOrderDetaillist as $rowDetail)  


@php 
$totalQty=  $totalQty + $rowDetail->size_qty_total;

@endphp

<tr>
<td style="text-align:right;">{{ $no }}</td>    
<td style="text-align:right;">{{ $rowDetail->item_code }}</td>  
<td>{{ $rowDetail->item_name }} </td>
<td><img  src="{{url('thumbnail/'.$rowDetail->item_image_path)}}"  id="item_image" name="item_image[]" class="imgs"></td>
<td>{{ $rowDetail->color_name }}</td>

@php 
    $SizeQtyList=explode(',', $rowDetail->size_qty_array)
@endphp
@foreach($SizeQtyList  as $size_id)
<td style="text-align:right;">{{ $size_id }}</td>

@endforeach

<td style="text-align:right;">{{ $rowDetail->size_qty_total  }}</td>
<td >{{ $rowDetail->unit_name  }}</td>
<td style="text-align:right;">{{ $rowDetail->shipment_allowance  }}</td>
<td style="text-align:right;">{{ $rowDetail->garment_rejection_allowance  }}</td>
</tr>
@php
$no=$no+1;
@endphp
@endforeach 
</tbody>
<thead>
<tr  style="background-color:#eee; text-align:center; border: 1px solid;">
 <th></th>
  <th></th>
 <th></th>
 <th></th>
<th>Total</th>

@php 
    $SizeWsList=explode(',', $SalesOrderCostingMaster[0]->sz_ws_total);
@endphp
@foreach($SizeWsList  as $sztotal)
<td style="text-align:right;">{{ $sztotal }}</td>

@endforeach
<th>{{ $totalQty }}</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>



</table>



   <div class="row">
    <!-- Fare Details -->
<div class="col-md-9">
<h4 class="text-4 mt-2">Prepared By:</h4>

 </div>
<div class="col-md-3">
 <h4 class="text-4 mt-2">Verified By:</h4>
    
    </div>
    </div><br>
  


<!-- Footer -->
<footer  >
 


<div class="row"><div class="col-md-6"><b>Order Remark:</b> {{  $SalesOrderCostingMaster[0]->narration }}</div> <br><br></div>

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