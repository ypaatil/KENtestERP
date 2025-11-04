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
<p><img src="http://ken.korbofx.org/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>   

</div>
<div class="col-md-6">    
<h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>


</div>
<div class="col-md-2">    
<h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($BOMList[0]->vw_date)) }}</h6>
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
     background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
}



</style>
<center><h6 class="mb-0" style="font-weight:bold;">WORK ORDER</h6></center>
<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
        
      <b style="display: inline-block;text-align: left;" class="mt-1">VWO Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vw_date }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vw_code }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sales_order_no }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Cost Type :</b>  <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->cost_type_name }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">FOB Rate :</b>  <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->order_rate }} </span></br>
     <b style="display: inline-block;text-align: left;" class="mt-1">Season:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->season_name }} </span></br>
</div>
    <div  class="col-md-4">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->mainstyle_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->substyle_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->fg_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->style_no }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Currency:</b>  <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->currency_name }}</span></br>
 
    </div>
    <div  class="col-md-4" >
    <b style="display: inline-block;text-align: left;" class="mt-1">To:</b><br>
    <span style="display: inline-block;">{{  $BOMList[0]->Ac_name }} </span></br>
   <span style="display: inline-block;">{{  $BOMList[0]->address }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1"> Pan No: </b><span style="display: inline-block;">{{  $BOMList[0]->buyerpan }} </span></br>
        </div>
    </div>

</div>

 @php  
 

        $BuyerPurchaseOrderMasterList =  App\Models\BuyerPurchaseOrderMasterModel::find($BOMList[0]->sales_order_no);
        $SizeDetailList =  App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //  DB::enableQueryLog();  
        
        
        
         $VendorWorkOrderDetailList = App\Models\VendorWorkOrderDetailModel::
             join('color_master','color_master.color_id','=','vendor_work_order_detail.color_id')
         ->where('vendor_work_order_detail.vw_code','=', $BOMList[0]->vw_code)
         
         ->get(['vendor_work_order_detail.*','color_master.color_name']);
        
    
 @endphp

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
    <th>Color</th>
   @foreach ($SizeDetailList as $sz) 
                   
                      <th>{{$sz->size_name}}</th>
                       
                   @endforeach
    <th>Total Qty</th>
</tr>
</thead>
<tbody>
   
   @php $no=1;  @endphp
 @foreach($VendorWorkOrderDetailList as $rowDataList)  
 
 <tr>
    <td>{{ $no }}</td>  
 <td>{{ $rowDataList->color_name }}</td>  
 
 @php 
    $SizeQtyList=explode(',', $rowDataList->size_qty_array)
@endphp
@foreach($SizeQtyList  as $szQty)
<td>{{ $szQty }} </td>
 
@endforeach
<td>{{ $rowDataList->size_qty_total }}</td> 

</tr>

@php

$no=$no+1;

@endphp
@endforeach
</tbody>
</table>    
    
    
 <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>   
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
    <th>Item Name</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>Unit</th>
    <th>Wastage %</th>
    <th>BOM Qty</th>
</tr>
</thead>
<tbody>
   
@php 

      $FabricList = App\Models\VendorWorkOrderFabricDetailModel::
      join('item_master','item_master.item_code','=','vendor_work_order_fabric_details.item_code')
      ->join('classification_master','classification_master.class_id','=','vendor_work_order_fabric_details.class_id') 
       ->join('unit_master','unit_master.unit_id','=','vendor_work_order_fabric_details.unit_id')  
       
     ->where('vendor_work_order_fabric_details.vw_code','=', $BOMList[0]->vw_code)->get();   
        
        
$no=1; 

@endphp


@foreach($FabricList as $rowDetail)  

<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description  }}</td>
<td>{{ $rowDetail->consumption  }}</td>
<td>{{ $rowDetail->unit_name  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->bom_qty  }}</td>
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
  
    <th>Wastage %</th>
    <!--  <th>Final Cons</th>-->
    <!--<th>Piece Qty</th>-->
    <th>BOM Qty</th>
   
</tr>
</thead>
<tbody>
   
@php 
 
     $SewingTrimsList = App\Models\VendorWorkOrderSewingTrimsDetailModel::
      select('item_master.item_name','classification_master.class_name','vendor_work_order_sewing_trims_details.description',
       'vendor_work_order_sewing_trims_details.consumption','unit_master.unit_name','vendor_work_order_sewing_trims_details.wastage',
       'vendor_work_order_sewing_trims_details.final_cons')
        ->join('item_master','item_master.item_code','=','vendor_work_order_sewing_trims_details.item_code')
      ->join('classification_master','classification_master.class_id','=','vendor_work_order_sewing_trims_details.class_id') 
      ->join('unit_master','unit_master.unit_id','=','vendor_work_order_sewing_trims_details.unit_id') 
     ->selectRaw('sum(vendor_work_order_sewing_trims_details.bom_qty) as totalbom_qty,sum(vendor_work_order_sewing_trims_details.size_qty) as totalsize_qty')
     ->where('vendor_work_order_sewing_trims_details.vw_code','=', $BOMList[0]->vw_code)
      ->groupBy('vendor_work_order_sewing_trims_details.item_code')
     ->get();    
        
$no=1; 

@endphp


 
@foreach($SewingTrimsList as $rowDetailtrims)  



@php 

$color_ids = explode(',', $rowDetailtrims->color_id);  

$size_ids = explode(',', $rowDetailtrims->size_array); 

$ColorList= App\Models\ColorModel::whereIn('color_id', $color_ids)->where('delflag','=', '0')->get('color_name');

$SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
   
@endphp

<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetailtrims->item_name }}</td>
<td>{{ $rowDetailtrims->class_name }}</td>
<td>{{ $rowDetailtrims->description  }}</td>
<td>{{ $rowDetailtrims->consumption  }}</td>
<td>{{ $rowDetailtrims->unit_name  }}</td>
<td>{{ $rowDetailtrims->wastage  }}</td>
 
<td>{{ $rowDetailtrims->totalbom_qty  }}</td>



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
    <th>Wastage %</th>
    <!--<th>Final Cons</th>-->
    <!--<th>Piece Qty</th>-->
    <th>BOM Qty</th>
</tr>
</thead>
<tbody>
   
@php 
   
      $PackingTrimsList = App\Models\VendorWorkOrderPackingTrimsDetailModel::
        select('item_master.item_name','classification_master.class_name','vendor_work_order_packing_trims_details.description',
       'vendor_work_order_packing_trims_details.consumption','unit_master.unit_name','vendor_work_order_packing_trims_details.wastage','vendor_work_order_packing_trims_details.final_cons')
      ->leftJoin('item_master','item_master.item_code','=','vendor_work_order_packing_trims_details.item_code')
      ->leftJoin('classification_master','classification_master.class_id','=','vendor_work_order_packing_trims_details.class_id') 
      ->leftJoin('unit_master','unit_master.unit_id','=','vendor_work_order_packing_trims_details.unit_id') 
      ->selectRaw('sum(vendor_work_order_packing_trims_details.bom_qty) as totalbom_qty,sum(vendor_work_order_packing_trims_details.size_qty) as totalsize_qty')
      ->where('vendor_work_order_packing_trims_details.vw_code','=', $BOMList[0]->vw_code)
      ->groupBy('vendor_work_order_packing_trims_details.item_code')
      ->get();
 
$nos=1; 

@endphp



 
@foreach($PackingTrimsList as $rowDetailpacking)  



@php 

$colorids = explode(',', $rowDetailpacking->color_id);  

$sizeids = explode(',', $rowDetailpacking->size_array); 


$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');

$SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');
   
@endphp


<tr>
<td>{{ $nos }}</td>    
<td>{{ $rowDetailpacking->item_name }}</td>
<td>{{ $rowDetailpacking->class_name }}</td>
<td>{{ $rowDetailpacking->description  }}</td>

<td>{{ $rowDetailpacking->consumption  }}</td>
<td>{{ $rowDetailpacking->unit_name  }}</td>
<td>{{ $rowDetailpacking->wastage  }}</td>

<td>{{ $rowDetailpacking->totalbom_qty  }}</td>
</tr>
@php

$nos=$nos+1;
@endphp
@endforeach
</tbody>
</table>




   <div class="row">
    <!-- Fare Details -->
<div class="col-md-4">
<h4 class="mt-2" style="font-size:15px;">PREPARED BY:</h4>

 </div>
<div class="col-md-4">
 <h4 class="mt-2" style="font-size:15px;">CHECKED BY:</h4>
    
    </div>
    <div class="col-md-4">
 <h4 class="mt-2" style="font-size:15px;">APPROVED BY:</h4>
    
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

<script src="{{ URL::asset('http://ken.korbofx.org/assets/libs/jquery/jquery.min.js')}}"></script>

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