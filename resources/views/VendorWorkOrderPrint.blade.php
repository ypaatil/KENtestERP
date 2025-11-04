<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;">Date:{{ date('d-m-Y',strtotime($BOMList[0]->vw_date)) }}</h6>
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
               @php 
               $BuyerPurchaseOrderMasterList =  App\Models\BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.*','brand_master.brand_name')
               ->join('brand_master', 'brand_master.brand_id',"=",'buyer_purchse_order_master.brand_id')
               ->where('tr_code',"=",$BOMList[0]->sales_order_no)->get();
               
               @endphp
               <center>
                  <h6 class="mb-0" style="font-weight:bold;">WORK ORDER</h6>
               </center>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style="display: inline-block;text-align: left;" class="mt-1">VWO Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vw_date }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->vw_code }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sales_order_no }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">SAM:  </b> <span style="display: inline-block;text-align: right;"> {{ $BOMList[0]->sam }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer Brand:</b>    <span style="display: inline-block;text-align: right;">{{ $BuyerPurchaseOrderMasterList[0]->brand_name }} </span></br>
                     </div>
                     <div  class="col-md-4">         
                        <b style="display: inline-block;text-align: left;" class="mt-1">Main Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->mainstyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sub Style Category:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->substyle_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style Name:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->fg_name }} </span></br>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Style No:</b>    <span style="display: inline-block;text-align: right;">{{ $BOMList[0]->style_no }} </span></br>
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
               $VendorWorkOrderDetailList = App\Models\VendorWorkOrderDetailModel::
               join('color_master','color_master.color_id','=','vendor_work_order_detail.color_id')
               ->where('vendor_work_order_detail.vw_code','=', $BOMList[0]->vw_code)
               ->get(['vendor_work_order_detail.*','color_master.color_name']);
               //dd(DB::getQueryLog());
               @endphp
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Assortment Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; " id="assortDetail">
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
                           <td class="text-center">{{ $szQty }} </td>
                           @endforeach
                           <td class="text-center">{{ $rowDataList->size_qty_total }}</td>
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
                           <th>Item Code</th>
                           <th>Item Name</th>
                           <th>Color Name</th>
                           <th>Sizes</th>
                           <th>Classification</th>
                           <th>Description</th>
                           <th>Cons(Mtr/Nos)</th>
                           <th>Unit</th>
                           <th>Wastage %</th>
                           <th>BOM Qty</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        //DB::enableQueryLog();
                        $SewingTrimsList = App\Models\VendorWorkOrderSewingTrimsDetailModel::
                            select(
                                'vendor_work_order_sewing_trims_details.item_code',
                                'vendor_work_order_sewing_trims_details.sales_order_no',
                                'item_master.item_name',
                                'classification_master.class_name',
                                'vendor_work_order_sewing_trims_details.description',
                                'vendor_work_order_sewing_trims_details.consumption',
                                'unit_master.unit_name',
                                'vendor_work_order_sewing_trims_details.wastage',
                                'vendor_work_order_sewing_trims_details.final_cons',
                                'bom_sewing_trims_details.size_array'
                            )
                            ->selectRaw('SUM(DISTINCT vendor_work_order_sewing_trims_details.bom_qty) as totalbom_qty')
                            ->selectRaw('SUM(vendor_work_order_sewing_trims_details.size_qty) as totalsize_qty')
                            ->join('item_master', 'item_master.item_code', '=', 'vendor_work_order_sewing_trims_details.item_code')
                            ->join('classification_master', 'classification_master.class_id', '=', 'vendor_work_order_sewing_trims_details.class_id')
                            ->join('unit_master', 'unit_master.unit_id', '=', 'vendor_work_order_sewing_trims_details.unit_id')
                            ->join('bom_sewing_trims_details', function($join) {
                                   $join->on('bom_sewing_trims_details.item_code', '=', 'vendor_work_order_sewing_trims_details.item_code')
                                ->on('bom_sewing_trims_details.sales_order_no', '=', 'vendor_work_order_sewing_trims_details.sales_order_no');
                            })
                            ->where('vendor_work_order_sewing_trims_details.vw_code', '=', $BOMList[0]->vw_code)
                            ->groupBy( 
                                'vendor_work_order_sewing_trims_details.sales_order_no', 
                                'bom_sewing_trims_details.item_code'
                            )
                            ->orderBy("vendor_work_order_sewing_trims_details.sales_order_no", "ASC")
                            ->get();
                            
                        //dd(DB::getQueryLog());
                        $no=1; 
                        @endphp
                        @foreach($SewingTrimsList as $rowDetailtrims)  
                        @php 
                        
                        if (!empty($rowDetailtrims->size_array) && isset($rowDetailtrims->size_array)) {
                            $size_ids = explode(',', $rowDetailtrims->size_array);
                        } else {
                            // Handle the case where no data is returned
                            $size_ids = [];
                        }
                        
                        $SizeDetailList = App\Models\SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                        $sizes='';
                        foreach($SizeDetailList as $sz)
                        {
                        $sizes=$sizes.$sz->size_name.', ';
                        }
                        $ColorListpacking= App\Models\BOMSewingTrimsDetailModel::select('color_id','remark')
                        ->where('item_code', $rowDetailtrims->item_code)->where('sales_order_no', $BOMList[0]->sales_order_no)->get();
                        
                        if (!empty($ColorListpacking) && isset($ColorListpacking[0]->color_id)) {
                           if(count($ColorListpacking) == 2)
                            {
                                $clors =  $ColorListpacking[1]->color_id;
                            }
                            else
                            {
                                $clors =  $ColorListpacking[0]->color_id;
                            }
                            
                            $colorids = explode(',', $clors);
                        } else {
                            // Handle the case where no data is returned
                            $colorids = [];
                        }
                       
                       
                        $ColorList = DB::table('vendor_work_order_detail')
                            ->join('color_master', 'vendor_work_order_detail.color_id', '=', 'color_master.color_id')
                            ->whereIn('vendor_work_order_detail.color_id', $colorids) 
                            ->where('vendor_work_order_detail.sales_order_no', $BOMList[0]->sales_order_no)
                            ->where('vendor_work_order_detail.vw_code', $BOMList[0]->vw_code)
                            ->distinct()
                            ->get(['color_master.color_name']);
    
                        
                        $colors='';
                        foreach($ColorList as $color)
                        {
                        $colors=$colors.$color->color_name.', ';
                        }
                        
                        
                        @endphp
                        <tr>
                           <td>{{ $no }}</td>
                           <td>{{ $rowDetailtrims->item_code }}</td>
                           <td>{{ $rowDetailtrims->item_name }}</td>
                           <td>{{rtrim($colors, ',');}} </td>
                           <td>{{rtrim($sizes, ',');}} </td>
                           <td>{{ $rowDetailtrims->class_name }}</td>
                           <td>{{ $rowDetailtrims->description  }}</td>
                           <td>{{ $rowDetailtrims->consumption  }}</td>
                           <td>{{ $rowDetailtrims->unit_name  }}</td>
                           <td>{{ $rowDetailtrims->wastage  }}</td>
                           <td>{{ $rowDetailtrims->totalbom_qty  }}</td>
                           <td>{{ isset($ColorListpacking[0]->remark) ? $ColorListpacking[0]->remark : ''}}</td>
                        </tr>
                        @php
                        $no=$no+1;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
                  <div class="row">
                     <div class="col-md-16">
                        <h4 class="mt-2" style="font-size:15px;">Comments:{{isset($BOMList[0]->narration) ? $BOMList[0]->narration : ""}}</h4>
                     </div>
                  </div>
                  <br>
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
                  </div>
                  <br>
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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  
//   $('#printInvoice').click(function(){
    //   Popup($('.invoice')[0].outerHTML);
    //   function Popup(data) 
    //   {
    //       window.print();
    //       return true;
    //   }
    //   });
        $(document).ready(function(){
            var result = [];
            $('#assortDetail tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text());
               });
            });
            result.shift();
            result.shift();
            $('#assortDetail').append('<tr><td colspan="2" class="text-center"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
               $('#assortDetail tr').last().append('<td class="text-center"><strong>'+this+'</strong></td>')
            });
        });
      
   </script>
</html>