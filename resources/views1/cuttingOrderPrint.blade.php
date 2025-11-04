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
<p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>   

</div>
<div class="col-md-6">    
<h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
<h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
<h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
 

</div>
<div class="col-md-2">    
<h6  style="font-weight:bold;"> </h6>
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

@php 
$BuyerPurchaseOrderMasterList =  App\Models\BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.*','brand_master.brand_name')
->join('brand_master', 'brand_master.brand_id',"=",'buyer_purchse_order_master.brand_id')
->where('tr_code',"=",$BOMList[0]->sales_order_no)->get();

@endphp
<center><h6 class="mb-0" style="font-weight:bold;">@if($BOMList[0]->process_id==1) CUTTING ORDER  @elseif($BOMList[0]->process_id==2) FINISHING ORDER @elseif($BOMList[0]->process_id==3) PACKING ORDER @elseif($BOMList[0]->process_id==4) WASHING ORDER @endif</h6></center>
<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
        
      <b style="display: inline-block;text-align: left;" class="mt-1">PO Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($BOMList[0]->vpo_date)) }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Process Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vpo_code }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PO Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($BOMList[0]->delivery_date)) }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sales_order_no }} </span></br>
       
</div>
    <div  class="col-md-4">       
    <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Brand:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerPurchaseOrderMasterList[0]->brand_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->mainstyle_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->substyle_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->fg_name }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->style_no }} </span></br>
     
    </div>
    <div  class="col-md-4" >
    <b style="display: inline-block;text-align: left;" class="mt-1">Buyer:</b><br>
    <span style="display: inline-block;">{{  $BOMList[0]->Ac_name }} </span></br>
    
     <b style="display: inline-block;text-align: left;" class="mt-1">Vendor Name:</b><br>
    <span style="display: inline-block;">{{  $BOMList[0]->vendorName }}  </span></br>
   <span style="display: inline-block;">{{  $BOMList[0]->address }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1"> PAN No: </b><span style="display: inline-block;">{{  $BOMList[0]->pan_no }} </span></br>
     <b style="display: inline-block;text-align: left;" class="mt-1"> GST No: </b><span style="display: inline-block;">{{  $BOMList[0]->gst_no }} </span></br>
        </div>
    </div>

</div>

 @php  
 

        
        $SizeDetailList =  App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //  DB::enableQueryLog();  
        
        
        
        $VendorPurchaseOrderDetailList =  App\Models\VendorPurchaseOrderDetailModel::where('vendor_purchase_order_detail.vpo_code','=', $BOMList[0]->vpo_code)
        ->join('color_master','color_master.color_id','=','vendor_purchase_order_detail.color_id')
        ->get(['vendor_purchase_order_detail.*','color_master.color_name']);
 
    
    
 @endphp

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm"  >
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
 @foreach($VendorPurchaseOrderDetailList as $rowDataList)  
 
 <tr>
    <td>{{ $no }}</td>  
 <td>{{ $rowDataList->color_name }}</td>  
 
 @php 
    $SizeQtyList=explode(',', $rowDataList->size_qty_array)
@endphp
@foreach($SizeQtyList  as $szQty)
<td class="text-center">{{ $szQty }} </td>
@endforeach
<td class="text-center">{{ $rowDataList->size_qty_total }}</td> 

</tr>

@php

$no=$no+1;

@endphp
@endforeach
</tbody>

<tfoot>
    <th></th>
    <th>Total</th>
    @php
    $nox=1;$sizex='';
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'sum(s'.$nox.') as s'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
    
    
     $SizeTotal= DB::select("select ".$sizex." , sum(size_qty_total) as Total from vendor_purchase_order_size_detail where vpo_code='".$BOMList[0]->vpo_code."'");
    @endphp
    
      
    @php foreach($SizeTotal as $row)
        {
        
        if(isset($row->s1)) { echo '<th class="text-center">'.$row->s1.'</th>' ; }
   if(isset($row->s2)) { echo '<th class="text-center">'.$row->s2.'</th>' ; }
   if(isset($row->s3)) { echo '<th class="text-center">'.$row->s3.'</th>' ; }
   if(isset($row->s4)) {echo  '<th class="text-center">'.$row->s4.'</th>' ; }
   if(isset($row->s5)) { echo '<th class="text-center">'.$row->s5.'</th>' ; }
   if(isset($row->s6)) { echo '<th class="text-center">'.$row->s6.'</th>' ; }
   if(isset($row->s7)) { echo '<th class="text-center">'.$row->s7.'</th>' ;}
   if(isset($row->s8)) { echo '<th class="text-center">'.$row->s8.'</th>' ;}
   if(isset($row->s9)) { echo '<th class="text-center">'.$row->s9.'</th>' ;}
   if(isset($row->s10)) { echo '<th class="text-center">'.$row->s10.'</th>' ;}
   if(isset($row->s11)) {echo  '<th class="text-center">'.$row->s11.'</th>' ;}
   if(isset($row->s12)) {echo '<th class="text-center">'.$row->s12.'</th>' ;}
   if(isset($row->s13)) { echo '<th class="text-center">'.$row->s13.'</th>' ;}
   if(isset($row->s14)) { echo '<th class="text-center">'.$row->s14.'</th>';}
   if(isset($row->s15)) {echo  '<th class="text-center">'.$row->s15.'</th>' ;}
   if(isset($row->s16)) {echo '<th class="text-center">'.$row->s16.'</th>' ;}
   if(isset($row->s17)) {echo '<th class="text-center">'.$row->s17.'</th>' ;}
   if(isset($row->s18)) { echo '<th class="text-center">'.$row->s18.'</th>' ;}
   if(isset($row->s19)) { echo '<th class="text-center">'.$row->s19.'</th>' ;}
   if(isset($row->s20)) {echo  '<th class="text-center">'.$row->s20.'</th>' ;}
          echo  '<th class="text-center">'.$row->Total.'</th>' ;
        }
    
    
     
    
    @endphp
    
    
</tfoot>


</table>    
    
   @if($BOMList[0]->process_id==1)
 <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>   
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
       <th>Item Code</th>
    <th>Item Name</th>
    <th>Colors</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>UOM</th>
    <th>Wastage %</th>
    <!--<th>Final Cons</th>-->
    <!--<th>Piece Qty</th>-->
    <th>Work Order Req. Qty (Incl Wastage)</th>
    <th>Remark</th>
</tr>
</thead>
<tbody>
@php 
//DB::enableQueryLog();
      $FabricList = App\Models\VendorPurchaseOrderFabricDetailModel::
      select('item_master.item_name','classification_master.class_name','vendor_purchase_order_fabric_details.description','vendor_purchase_order_fabric_details.item_code',
       'vendor_purchase_order_fabric_details.consumption','unit_master.unit_name','vendor_purchase_order_fabric_details.wastage',
       'vendor_purchase_order_fabric_details.final_cons')
     ->join('item_master','item_master.item_code','=','vendor_purchase_order_fabric_details.item_code')
     ->join('classification_master','classification_master.class_id','=','vendor_purchase_order_fabric_details.class_id') 
     ->join('unit_master','unit_master.unit_id','=','vendor_purchase_order_fabric_details.unit_id')  
     ->selectRaw('sum(vendor_purchase_order_fabric_details.bom_qty) as totalbom_qty,vendor_purchase_order_fabric_details.size_qty as totalsize_qty')  
     ->where('vendor_purchase_order_fabric_details.vpo_code','=', $BOMList[0]->vpo_code)
     ->groupBy('vendor_purchase_order_fabric_details.item_code')
     ->get();  
   // dd(DB::getQueryLog());
$no=1; 
@endphp
@foreach($FabricList as $rowDetail)  
@php

    $RemarkList= App\Models\BOMFabricDetailModel::select('remark')->
    where('item_code', $rowDetail->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();

    $ColorLists = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
    'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('item_code','=',$rowDetail->item_code)->where('tr_code','=',$BOMList[0]->sales_order_no)->DISTINCT()->get();
     
     $colors='';
     foreach($ColorLists as $row)
     {
       $colors=$colors.$row->color_name.', ';
     }
 @endphp
 
<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetail->item_code }}</td>
<td>{{ $rowDetail->item_name }}</td>
<td>{{ $colors }}</td>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description  }}</td>
<td>{{ $rowDetail->consumption  }}</td>
<td>{{ $rowDetail->unit_name  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->totalbom_qty  }}</td>
<td>{{$RemarkList[0]->remark}}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach
</tbody>
</table>


 <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trim Fabric Details:</h4>   
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
      <th>SrNo</th>
       <th>Item Code</th>
    <th>Item Name</th>
    <th>Colors</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>UOM</th>
    <th>Wastage %</th>
    <!--<th>Final Cons</th>-->
    <!--<th>Piece Qty</th>-->
    <th>Work Order Req. Qty (Incl Wastage)</th>
    <th>Remark</th>
</tr>
</thead>
<tbody>
   
@php 

      $FabricList = App\Models\VendorPurchaseOrderTrimFabricDetailModel::
      select('item_master.item_name','classification_master.class_name','vendor_purchase_order_trim_fabric_details.description',
      'vendor_purchase_order_trim_fabric_details.item_code',
       'vendor_purchase_order_trim_fabric_details.consumption','unit_master.unit_name','vendor_purchase_order_trim_fabric_details.wastage',
       'vendor_purchase_order_trim_fabric_details.final_cons')
      ->join('item_master','item_master.item_code','=','vendor_purchase_order_trim_fabric_details.item_code')
      ->join('classification_master','classification_master.class_id','=','vendor_purchase_order_trim_fabric_details.class_id') 
      ->join('unit_master','unit_master.unit_id','=','vendor_purchase_order_trim_fabric_details.unit_id')  
      ->selectRaw('sum(vendor_purchase_order_trim_fabric_details.bom_qty) as totalbom_qty,vendor_purchase_order_trim_fabric_details.size_qty as totalsize_qty')  
     ->where('vendor_purchase_order_trim_fabric_details.vpo_code','=', $BOMList[0]->vpo_code)
       ->groupBy('vendor_purchase_order_trim_fabric_details.item_code')
     ->get();   
        
        
$no=1; 

@endphp



 

@foreach($FabricList as $rowDetail)  
 
@php
// DB::enableQueryLog();
$ColorListpacking= App\Models\BOMTrimFabricDetailModel::select('color_id','remark')->
where('item_code', $rowDetail->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();
 
// $query = DB::getQueryLog();
 // $query = end($query);
 // dd($query);
 $colorids = explode(',', $ColorListpacking[0]->color_id); 
//$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');


$ColorListpacking= App\Models\VendorPurchaseOrderDetailModel::
join('color_master','vendor_purchase_order_detail.color_id','=','color_master.color_id')
->where('vendor_purchase_order_detail.sales_order_no', $BOMList[0]->sales_order_no)
->where('vendor_purchase_order_detail.vpo_code', $BOMList[0]->vpo_code)
->whereIn('vendor_purchase_order_detail.color_id', $colorids)->where('delflag','=', '0')->distinct('color_master.color_id')->get('color_name');
  



 $colorstrimfabric='';
foreach($ColorListpacking as $colorpk)
{
    $colorstrimfabric=$colorstrimfabric.$colorpk->color_name.', ';
} 
@endphp
<tr>
<td>{{ $no }}</td>    
<td>{{ $rowDetail->item_code }}</td>
<td>{{ $rowDetail->item_name }}</td>
<td >{{rtrim($colorstrimfabric, ', ');}} </td>
<td>{{ $rowDetail->class_name }}</td>
<td>{{ $rowDetail->description  }}</td>
<td>{{ $rowDetail->consumption  }}</td>
<td>{{ $rowDetail->unit_name  }}</td>
<td>{{ $rowDetail->wastage  }}</td>
<td>{{ $rowDetail->totalbom_qty  }}</td>
<td>{{$ColorListpacking[0]->remark}}</td>
</tr>
@php

$no=$no+1;
@endphp
@endforeach
</tbody>
</table>










@elseif($BOMList[0]->process_id==2)

@elseif($BOMList[0]->process_id==3)

<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims:</h4>

<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
     <th>SrNo</th>
     <th>Item Code</th>
    <th>Item Name</th>
    <th>Color</th>
    <th>Sizes</th>
    <th>Classification</th>
    <th>Description</th>
    <th>Cons(Mtr/Nos)</th>
    <th>UOM</th>
    <th>Wastage %</th>
    <!--<th>Final Cons</th>-->
    <!--<th>Piece Qty</th>-->
    <th>Work Order Req. Qty (Incl Wastage)</th>
    <th>Remark</th>
</tr>
</thead>
<tbody>
   
@php 
      $PackingTrimsList = App\Models\VendorPurchaseOrderPackingTrimsDetailModel::
        select('item_master.item_name','classification_master.class_name','vendor_purchase_order_packing_trims_details.sales_order_no','vendor_purchase_order_packing_trims_details.description','vendor_purchase_order_packing_trims_details.item_code',
       'vendor_purchase_order_packing_trims_details.consumption','unit_master.unit_name','vendor_purchase_order_packing_trims_details.wastage','vendor_purchase_order_packing_trims_details.final_cons')
      ->leftJoin('item_master','item_master.item_code','=','vendor_purchase_order_packing_trims_details.item_code')
      ->leftJoin('classification_master','classification_master.class_id','=','vendor_purchase_order_packing_trims_details.class_id') 
      ->leftJoin('unit_master','unit_master.unit_id','=','vendor_purchase_order_packing_trims_details.unit_id') 
      ->selectRaw('sum(vendor_purchase_order_packing_trims_details.bom_qty) as totalbom_qty,sum(vendor_purchase_order_packing_trims_details.size_qty) as totalsize_qty')
      ->where('vendor_purchase_order_packing_trims_details.vpo_code','=', $BOMList[0]->vpo_code)
      ->groupBy('vendor_purchase_order_packing_trims_details.item_code')
      ->get();
 
$nos=1; 
@endphp
@foreach($PackingTrimsList as $rowDetailpacking)  
@php 


$SizeListFromBOM=DB::select("select size_array, remark from bom_packing_trims_details where sales_order_no='".$rowDetailpacking->sales_order_no."' and item_code='".$rowDetailpacking->item_code."' limit 0,1");
$size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
$SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
$sizes='';
foreach($SizeDetailList as $sz)
{
    $sizes=$sizes.$sz->size_name.', ';
}

 
//$colorids = explode(',', $rowDetailpacking->color_id);  

$sizeids = explode(',', $rowDetailpacking->size_array); 



 //DB::enableQueryLog();
$ColorListpacking= App\Models\BOMPackingTrimsDetailModel::select('color_id')->
where('item_code', $rowDetailpacking->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();
 $colorids = explode(',', $ColorListpacking[0]->color_id);  
 //$query = DB::getQueryLog();
        // $query = end($query);
      //  dd($query);
//$ColorListpacking= App\Models\ColorModel::whereIn('color_id', $colorids)->where('delflag','=', '0')->get('color_name');

$ColorListpacking= App\Models\VendorPurchaseOrderDetailModel::
join('color_master','vendor_purchase_order_detail.color_id','=','color_master.color_id')
->where('vendor_purchase_order_detail.sales_order_no', $BOMList[0]->sales_order_no)
->where('vendor_purchase_order_detail.vpo_code', $BOMList[0]->vpo_code)
->whereIn('vendor_purchase_order_detail.color_id', $colorids)->where('delflag','=', '0')
->distinct('color_master.color_id')->get('color_name');

$colorspk='';
foreach($ColorListpacking as $colorpk)
{
    $colorspk=$colorspk.$colorpk->color_name.', ';
}




$SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');
   
@endphp


<tr>
<td>{{ $nos }}</td>  
<td>{{ $rowDetailpacking->item_code }}</td>
<td>{{ $rowDetailpacking->item_name }}</td>
<td >{{rtrim($colorspk, ', ');}} </td>
<td>{{rtrim($sizes, ', ');}} </td>
<td>{{ $rowDetailpacking->class_name }}</td>
<td>{{ $rowDetailpacking->description  }}</td>

<td>{{ $rowDetailpacking->consumption  }}</td>
<td>{{ $rowDetailpacking->unit_name  }}</td>
<td>{{ $rowDetailpacking->wastage  }}</td>

<td>{{ $rowDetailpacking->totalbom_qty  }}</td>
<td>{{$SizeListFromBOM[0]->remark}}</td>
</tr>
@php

$nos=$nos+1;
@endphp
@endforeach
</tbody>
</table>




@endif

 <div class="row">
   <div class="col-md-16">
    <h4 class="mt-2" style="font-size:15px;">Comments:{{$BOMList[0]->narration}}</h4>
    
   </div>
    </div><br>

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