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
         @media print{
         @page {size: landscape; }
         body
         { zoom: 70%;
         font-size:14px;    
         }
         table  th, td
         {
         font-size:12px;   
         }
         #Assortment,#Fabric,#Sewing,#Packing
         {
         page-break-after: always;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid ">
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>  </br></br>
                      <div class="col-md-9 text-center">
                         <h6 class="mb-0" style="font-weight:bold;">Color Wise BOM Details</h6>
                      </div>
                  </div>
                  
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($BOMList[0]->bom_date)) }}</h6>
                  </div>
               </div>
               <h4 class="text-4"></h4>
               <div class=""></div>
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
                        <b style="display: inline-block;text-align: left;" class="mt-1">BOM Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->bom_date }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">BOM No:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->bom_code }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sales_order_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer: </b>  <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->Ac_name }} </span></br>
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
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Order No:</b>  <span style="display: inline-block;text-align: right;">{{  $BOMList[0]->bom_code }}</span></br>
                     </div>
                     <div  class="col-md-4" >
                     </div>
                  </div>
               </div>
               @php  
               $BuyerPurchaseOrderMasterList =  App\Models\BuyerPurchaseOrderMasterModel::find($BOMList[0]->sales_order_no);
               $SizeDetailList =  App\Models\SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
               @endphp
               <!-- Passenger Details -->
               <div class="Assortment">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Image</th>
                           <th>Color</th>
                           @foreach ($SizeDetailList as $sz) 
                           <th>{{$sz->size_name}}</th>
                           @endforeach
                           <th>Total Qty</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $sizes='';
                        $no=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                        $no=$no+1;
                        }
                        $sizes=rtrim($sizes,',');
                        $MasterdataList = DB::select("SELECT sales_order_detail.item_code,item_image_path,sales_order_detail.color_id, color_master.color_name, ".$sizes.", 
                        sum(size_qty_total) as size_qty_total  from sales_order_detail 
                        inner join color_master on color_master.color_id=sales_order_detail.color_id 
                        inner join item_master on item_master.item_code=sales_order_detail.item_code 
                        where tr_code='".$BOMList[0]->sales_order_no."' AND sales_order_detail.color_id = ".$color_id." group by sales_order_detail.color_id"); 
                        $no=1; 
                        @endphp
                        @foreach($MasterdataList as $rowDataList)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDataList->item_code }}</td>
                           <td><a href="{{url('images/'.$rowDataList->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDataList->item_image_path)}}"  ></a></td>
                           <td>{{ $rowDataList->color_name }}</td>
                           @if(isset($rowDataList->s1))  
                           <td class="ass_s1">{{$rowDataList->s1}}</td>
                           @endif
                           @if(isset($rowDataList->s2)) 
                           <td style="text-align:right" class="ass_s2">{{$rowDataList->s2}}</td>
                           @endif
                           @if(isset($rowDataList->s3)) 
                           <td style="text-align:right" class="ass_s3">{{$rowDataList->s3}}</td>
                           @endif
                           @if(isset($rowDataList->s4)) 
                           <td style="text-align:right" class="ass_s4">{{$rowDataList->s4}}</td>
                           @endif
                           @if(isset($rowDataList->s5)) 
                           <td style="text-align:right" class="ass_s5">{{$rowDataList->s5}}</td>
                           @endif
                           @if(isset($rowDataList->s6)) 
                           <td style="text-align:right" class="ass_s6">{{$rowDataList->s6}}</td>
                           @endif
                           @if(isset($rowDataList->s7)) 
                           <td style="text-align:right" class="ass_s7">{{$rowDataList->s7}}</td>
                           @endif
                           @if(isset($rowDataList->s8)) 
                           <td style="text-align:right" class="ass_s8">{{$rowDataList->s8}}</td>
                           @endif
                           @if(isset($rowDataList->s9)) 
                           <td style="text-align:right" class="ass_s9">{{$rowDataList->s9}}</td>
                           @endif
                           @if(isset($rowDataList->s10)) 
                           <td style="text-align:right" class="ass_s10">{{$rowDataList->s10}}</td>
                           @endif
                           @if(isset($rowDataList->s11)) 
                           <td style="text-align:right" class="ass_s11">{{$rowDataList->s11}}</td>
                           @endif
                           @if(isset($rowDataList->s12)) 
                           <td style="text-align:right" class="ass_s12">{{$rowDataList->s12}}</td>
                           @endif
                           @if(isset($rowDataList->s13)) 
                           <td style="text-align:right" class="ass_s13">{{$rowDataList->s13}}</td>
                           @endif
                           @if(isset($rowDataList->s14)) 
                           <td style="text-align:right" class="ass_s14">{{$rowDataList->s14}}</td>
                           @endif
                           @if(isset($rowDataList->s15)) 
                           <td style="text-align:right" class="ass_s15">{{$rowDataList->s15}}</td>
                           @endif
                           @if(isset($rowDataList->s16)) 
                           <td style="text-align:right" class="ass_s16">{{$rowDataList->s16}}</td>
                           @endif
                           @if(isset($rowDataList->s17)) 
                           <td style="text-align:right" class="ass_s17">{{$rowDataList->s17}}</td>
                           @endif
                           @if(isset($rowDataList->s18)) 
                           <td style="text-align:right" class="ass_s18">{{$rowDataList->s18}}</td>
                           @endif
                           @if(isset($rowDataList->s19)) 
                           <td style="text-align:right" class="ass_s19">{{$rowDataList->s19}}</td>
                           @endif
                           @if(isset($rowDataList->s20))  
                           <td style="text-align:right" class="ass_s20">{{$rowDataList->s20}}</td>
                           @endif
                           <td style="text-align:right" class="ass_size_qty_total">{{number_format($rowDataList->size_qty_total)}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                      
                     </tbody>
                  </table>
               </div>
               <div id="Fabric">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Image</th>
                           <th>Item Name</th>
                           <th colspan="2">Colors</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Cons (Mtr/Nos)</th>
                           <th>UOM</th>
                           <th>Wastage %</th>
                           <th>BOM Qty</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                          //DB::enableQueryLog();
                        $FabricList = App\Models\BOMFabricDetailModel::
                            join('item_master','item_master.item_code','=','bom_fabric_details.item_code')
                            ->join('classification_master','classification_master.class_id','=','bom_fabric_details.class_id') 
                            ->join('buyer_purchase_order_detail','buyer_purchase_order_detail.item_code','=','bom_fabric_details.item_code') 
                            ->join('unit_master','unit_master.unit_id','=','bom_fabric_details.unit_id')  
                            ->where('bom_fabric_details.bom_code','=', $BOMList[0]->bom_code)
                            ->whereRaw('FIND_IN_SET(?, buyer_purchase_order_detail.color_id)', [$color_id])  
                            ->groupby('buyer_purchase_order_detail.color_id')
                            ->get();   
                            // dd(DB::getQueryLog());
                        $no=1; 
                        @endphp
                        @foreach($FabricList as $rowDetail)  
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td><a href="{{url('images/'.$rowDetail->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetail->item_image_path)}}"  ></a></td>
                           <td>{{ $rowDetail->item_name }}</td>
                           @php
                           $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
                                    'color_master.color_name')
                                    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                                   ->where('buyer_purchase_order_detail.item_code','=',$rowDetail->item_code)
                                   ->where('buyer_purchase_order_detail.tr_code','=',$BOMList[0]->sales_order_no)
                                   ->where('buyer_purchase_order_detail.color_id','=',$color_id)
                                   ->DISTINCT()
                                   ->get();
                             @endphp
                          
                        
                           <td colspan="2"> {{$ColorList[0]->color_name}}</td>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->description  }}</td>
                           <td style="text-align:right">{{ $rowDetail->consumption  }}</td>
                           <td>{{ $rowDetail->unit_name  }}</td>
                           <td style="text-align:right">{{ $rowDetail->wastage  }}</td>
                           <td style="text-align:right"> {{number_format($rowDetail->bom_qty)}}</td>
                           <td style="text-align:right">{{ $rowDetail->remark  }}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div id="TrimFabric">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trim Fabric Details:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Image</th>
                           <th>Fabric Color Code</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Garment Color</th>
                           <th>Size</th>
                           <th>Cons (Mtr/Nos)</th>
                           <th>UOM</th>
                           <th>Wastage %</th>
                           <th>BOM Qty</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $TrimFabricList = App\Models\BOMTrimFabricDetailModel::
                        join('item_master','item_master.item_code','=','bom_trim_fabric_details.item_code')
                        ->join('classification_master','classification_master.class_id','=','bom_trim_fabric_details.class_id') 
                        ->join('unit_master','unit_master.unit_id','=','bom_trim_fabric_details.unit_id') 
                        ->where('bom_trim_fabric_details.bom_code','=', $BOMList[0]->bom_code)
                        ->whereRaw('FIND_IN_SET(?, bom_trim_fabric_details.color_id)', [$color_id])  
                        ->get();    
                        $no=1; 
                        @endphp
                        @foreach($TrimFabricList as $rowDetailtrimfabric)  
                        @php   
                        $size_ids = explode(',', $rowDetailtrimfabric->size_array); 
                        $ColorList= App\Models\ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetailtrimfabric->item_code }}</td>
                           <td><a href="{{url('images/'.$rowDetailtrimfabric->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrimfabric->item_image_path)}}"  ></a></td>
                           <td>{{ $rowDetailtrimfabric->item_name }}</td>
                           <td>{{ $rowDetailtrimfabric->class_name }}</td>
                           <td>{{ $rowDetailtrimfabric->description  }}</td>
                           <td>{{$ColorList[0]->color_name}}</td>
                           <td>
                              @php   
                              $size=""; 
                              foreach($SizeDetailList as $sizes)  
                              {
                              $size= $size.$sizes->size_name.',';   } 
                              @endphp 
                              {{ rtrim($size,",") }}
                           </td>
                           <td>{{ $rowDetailtrimfabric->consumption  }}</td>
                           <td>{{ $rowDetailtrimfabric->unit_name  }}</td>
                           <td style="text-align:right">{{ $rowDetailtrimfabric->wastage  }}</td>
                           <td style="text-align:right"> {{number_format($rowDetailtrimfabric->bom_qty)}}</td>
                           <td style="text-align:right">{{ $rowDetailtrimfabric->remark  }}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div id="Sewing">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Image</th>
                           <th>Item Name</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Color</th>
                           <th>Size</th>
                           <th>Cons (Mtr/Nos)</th>
                           <th>UOM</th>
                           <th>Wastage %</th>
                           <th>BOM Qty</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        //DB::enableQueryLog();
                        $SewingTrimsList = App\Models\BOMSewingTrimsDetailModel::join('item_master','item_master.item_code','=','bom_sewing_trims_details.item_code')
                        ->join('classification_master','classification_master.class_id','=','bom_sewing_trims_details.class_id') 
                        ->join('unit_master','unit_master.unit_id','=','bom_sewing_trims_details.unit_id') 
                        ->where('bom_sewing_trims_details.bom_code','=', $BOMList[0]->bom_code)
                        ->whereRaw('FIND_IN_SET(?, bom_sewing_trims_details.color_id)', [$color_id]) 
                        ->get();  
                        
                        //dd(DB::getQueryLog());
                        $no=1; 
                        @endphp
                        @foreach($SewingTrimsList as $rowDetailtrims)  
                        @php 
                        $size_ids = explode(',', $rowDetailtrims->size_array); 
                        $ColorList= App\Models\ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                        
                        //DB::enableQueryLog();
                        $buyerPurData = DB::select("SELECT (select sum(size_qty) from buyer_purchase_order_size_detail where   
                             tr_code='".$BOMList[0]->sales_order_no."'  and color_id in (".$color_id.") and size_id in (".$rowDetailtrims->size_array."))  as bom_qty
                             from sales_order_fabric_costing_details where sales_order_no='".$sales_order_no."'"); 
                        
                      
                        $s_b_qty = ((isset($buyerPurData[0]->bom_qty) ? $buyerPurData[0]->bom_qty : 0) * $rowDetailtrims->consumption);
                        $s_waste_qty = ($s_b_qty * ($rowDetailtrims->wastage/100));
                        $sewing_bom_qty =  $s_waste_qty + $s_b_qty;
                        //dd(DB::getQueryLog());
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetailtrims->item_code }}</td>
                           <td><a href="{{url('images/'.$rowDetailtrims->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrims->item_image_path)}}"  ></a></td>
                           <td>{{ $rowDetailtrims->item_name }}</td>
                           <td>{{ $rowDetailtrims->class_name }}</td>
                           <td>{{ $rowDetailtrims->description  }}</td>
                           <td> {{$ColorList[0]->color_name}}</td>
                           <td>
                              @php   
                              $size=""; 
                              foreach($SizeDetailList as $sizes)  
                              {
                              $size= $size.$sizes->size_name.',';   } 
                              @endphp 
                              {{ rtrim($size,",") }}
                           </td>
                           <td>{{ $rowDetailtrims->consumption  }}</td>
                           <td>{{ $rowDetailtrims->unit_name  }}</td>
                           <td style="text-align:right">{{ $rowDetailtrims->wastage  }}</td>
                           <td style="text-align:right"> {{number_format($sewing_bom_qty,2)}}</td>
                           <td style="text-align:right">{{ $rowDetailtrims->remark  }}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div id="Packing">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims:</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Code</th>
                           <th>Image</th>
                           <th>Item Name</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Color</th>
                           <th>Size</th>
                           <th>Cons (Mtr/Nos)</th>
                           <th>UOM</th>
                           <th>Wastage %</th>
                           <th>BOM Qty</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $PackingTrimsList = App\Models\BOMPackingTrimsDetailModel::leftJoin('item_master','item_master.item_code','=','bom_packing_trims_details.item_code')
                        ->leftJoin('classification_master','classification_master.class_id','=','bom_packing_trims_details.class_id') 
                        ->leftJoin('unit_master','unit_master.unit_id','=','bom_packing_trims_details.unit_id') 
                        ->where('bom_packing_trims_details.bom_code','=', $BOMList[0]->bom_code)
                        ->whereRaw('FIND_IN_SET(?, bom_packing_trims_details.color_id)', [$color_id]) 
                        ->get();
                        $nos=1; 
                        @endphp
                        @foreach($PackingTrimsList as $rowDetailpacking)  
                        @php  
                        $sizeids = explode(',', $rowDetailpacking->size_array); 
                        $ColorListpacking= App\Models\ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        $SizeDetailListpacking = App\Models\SizeDetailModel::whereIn('size_id',$sizeids)->get('size_name');
                        
                        $packingTrimsData = DB::select("SELECT (select sum(size_qty) from buyer_purchase_order_size_detail where   
                             tr_code='".$BOMList[0]->sales_order_no."'  and color_id in (".$color_id.") and size_id in (".$rowDetailpacking->size_array."))  as bom_qty
                             from sales_order_packing_trims_costing_details where sales_order_no='".$sales_order_no."'"); 
                        
                      
                        $pack_b_qty = ((isset($packingTrimsData[0]->bom_qty) ? $packingTrimsData[0]->bom_qty : 0) * $rowDetailpacking->consumption);
                        $pack_waste_qty = ($pack_b_qty * ($rowDetailpacking->wastage/100));
                        $packing_bom_qty =  $pack_waste_qty + $pack_b_qty;
                        
                        @endphp
                        <tr>
                           <td>{{ $nos }}</td>
                           <td>{{ $rowDetailpacking->item_code }}</td>
                           <td><a href="{{url('images/'.$rowDetailtrims->item_image_path)}}" target="_blank"><img src="{{url('thumbnail/'.$rowDetailtrims->item_image_path)}}"  ></a></td>
                           <td>{{ $rowDetailpacking->item_name }}</td>
                           <td>{{ $rowDetailpacking->class_name }}</td>
                           <td>{{ $rowDetailpacking->description  }}</td>
                           <td>{{ $ColorListpacking[0]->color_name }}</td>
                           <td>
                              @php   
                              $sizepack=""; 
                              foreach($SizeDetailListpacking as $sizespacking)  
                              {
                              $sizepack= $sizepack.$sizespacking->size_name.',';   } 
                              @endphp 
                              {{ rtrim($sizepack,",") }}   
                           </td>
                           <td style="text-align:right">{{ $rowDetailpacking->consumption  }}</td>
                           <td>{{ $rowDetailpacking->unit_name  }}</td>
                           <td style="text-align:right">{{ $rowDetailpacking->wastage  }}</td>
                           <td style="text-align:right"> {{number_format($packing_bom_qty,2)}}</td>
                           <td style="text-align:right">{{ $rowDetailpacking->remark  }}</td>
                        </tr>
                        @php
                        $nos=$nos+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <!-- Footer -->
               <footer  >
                  <div class="btn-group d-print-none"> <a  href="javascript:window.print()" class="btn btn-info"> Print</a> </div>
                  <button type="button" id="export_button" class="btn btn-warning">Export</button>  
               </footer>
         </div>
         </main>
      </div>
      </div>
      <p class="text-center d-print-none"><a href="/GetColorWiseBOMDetail">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"
      integrity="sha256-c9vxcXyAG4paArQG3xk6DjyW/9aHxai2ef9RpMWO44A=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
   <script>
      function html_table_to_excel(type)
       {
          var data = document.getElementById('invoice');
      
          var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
      
          XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
      
          XLSX.writeFile(file, 'Color Wise BOM Details.' + type);
       }
      
       const export_button = document.getElementById('export_button');
      
       export_button.addEventListener('click', () =>  {
          html_table_to_excel('xlsx');
       });
       
       
       
       
        //  $('#printInvoice').click(function(){
        //           Popup($('.invoice')[0].outerHTML);
        //           function Popup(data) 
        //           {
        //               window.print();
        //               return true;
        //           }
        //       });
      		
      		
   </script>
</html>