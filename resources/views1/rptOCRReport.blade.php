<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
         td{
         text-align: right;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
          <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                     @php $FirmDetail =  DB::table('firm_master')->first(); @endphp
                  </div>
                  <div class="col-md-6">
                     <h4 class="mb-0" style="font-weight:bold; text-center">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
                  </div>
                  <div class="col-md-2">
                     <h6  style="font-weight:bold;"> </h6>
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
                  background-image: url('http://ken.korbofx.com/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">OCR Report</h4>
               @if(count($Buyer_Purchase_Order_List)>0)
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4">
                        <b style=" text-align: left;" class="mt-1">Sales Order No:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->tr_code}}</span> <br>
                        <b style=" text-align: left;" class="mt-1">Buyer Name:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->Ac_name}}</span><br>
                        <b style=" text-align: left;" class="mt-1">Main Style:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->mainstyle_name}}</span><br>
                     </div>
                     <div  class="col-md-3" >
                     </div>
                     <div  class="col-md-5">        
                        <b style=" text-align: left;" class="mt-1">Job Style:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->fg_name}}</span><br>
                        <b style=" text-align: left;" class="mt-1">Style No:</b>  <span style="display: inline-block;text-align: right;">{{$Buyer_Purchase_Order_List[0]->style_no}}</span><br>
                     </div>
                  </div>
               </div>
               <center>
                  <h4 style="font-weight:bold;">Summary Report</h4>
               </center>
               <table class="table table-bordered text-1 table-sm" id="Summary" style="height:10vh; ">
                  <thead style="text-align:center;">
                     <th>Garment Color</th>
                     <th>Order Qty</th>
                     <th>Order Qty with Allowance </th>
                     <th>Work Order  Qty</th>
                     <th>CUT Qty</th>
                     <th>Pass Qty</th>
                     <th>Reject Qty</th>
                     <th>Total Prod Qty</th>
                     <th>Balance Qty</th>
                     <th>Pack Qty</th>
                     <th> Packing Balance Qty</th>
                     <th>Shipment Qty</th>
                     <th>Shipment Bal Qty</th>
                     <th>Left Over Pcs</th>
                     <th>Cut To Ship %</th>
                     <th>Order To Ship %</th>
                  </thead>
                  <tbody>
                     @php
                     //DB::enableQueryLog();
                     $Buyer_Purchase_Order_Detail_List = DB::table('buyer_purchase_order_detail')->
                     select('buyer_purchase_order_detail.*','color_master.color_name',
                     DB::raw('(select ifnull(sum(size_qty_total),0) from vendor_work_order_detail where
                     vendor_work_order_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     vendor_work_order_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as work_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from cut_panel_grn_detail where
                     cut_panel_grn_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as cut_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from qcstitching_inhouse_detail where
                     qcstitching_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     qcstitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as pass_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from packing_inhouse_detail where
                     packing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as packing_order_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_detail.cpki_code
                     where
                     carton_packing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     carton_packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id  
                     and carton_packing_inhouse_master.endflag=1 ) as invoice_qty')
                     )
                     ->join('color_master','color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                     ->where('buyer_purchase_order_detail.tr_code','=', $Buyer_Purchase_Order_List[0]->tr_code)
                     ->get();
                     // $query = DB::getQueryLog();
                     //     $query = end($query);
                     //    dd($query);
                     // DB::raw('(select ifnull(sum(size_qty_total),0) from finishing_inhouse_detail where
                     //finishing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     //finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id
                     //) as finish_order_qty'),
                     $no=1; @endphp
                     <input type="hidden" id="s1AllCount" value="{{count($Buyer_Purchase_Order_Detail_List)}}">
                     @foreach($Buyer_Purchase_Order_Detail_List as $List)
                     @php
                        
                        $rejectedData = DB::select("SELECT sum((select qcstitching_inhouse_size_reject_detail2.size_qty
                            from  qcstitching_inhouse_size_reject_detail2 where qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code
                            and qcstitching_inhouse_size_reject_detail2.color_id=qcstitching_inhouse_size_detail2.color_id  
                            and  qcstitching_inhouse_size_reject_detail2.size_id = qcstitching_inhouse_size_detail2.size_id )) as reject_order_qty
                            FROM qcstitching_inhouse_size_detail2
                            WHERE qcstitching_inhouse_size_detail2.sales_order_no = '".$List->tr_code."' AND qcstitching_inhouse_size_detail2.color_id=".$List->color_id);
                            
                            if(count($rejectedData) > 0)
                            {
                                $reject_qty = $rejectedData[0]->reject_order_qty;
                            }
                            else
                            {
                               $reject_qty = 0;
                            }
                     @endphp
                     <tr>
                        <td>{{$List->color_name  }}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total)}}</td>
                        <td style="text-align: right;">{{number_format($List->size_qty_total + round(($List->size_qty_total) * ($List->shipment_allowance/100)))  }}</td>
                        <td style="text-align: right;">{{number_format($List->work_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->pass_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($reject_qty)  }}</td>
                        <td style="text-align: right;">{{number_format(($List->pass_order_qty + $reject_qty))}}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty-($List->pass_order_qty + $reject_qty))  }}</td>
                        <td style="text-align: right;">{{number_format($List->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->cut_order_qty - $List->packing_order_qty)  }}</td>
                        <td style="text-align: right;">{{number_format($List->invoice_qty)}}</td>
                        <td style="text-align: right;">{{$List->packing_order_qty -$List->invoice_qty}}</td>
                        <td style="text-align: right;">{{$List->packing_order_qty -$List->invoice_qty}}</td>
                        @if($List->invoice_qty!=0)
                        <td style="text-align: right;">{{round((($List->invoice_qty/$List->cut_order_qty)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                        @if($List->invoice_qty!=0)
                        <td style="text-align: right;">{{round((($List->invoice_qty/$List->size_qty_total)*100),2)  }}</td>
                        @else
                        <td style="text-align: right;">0</td>
                        @endif
                     </tr>
                     @endforeach
                  </tbody>
                  <tfoot></tfoot>
                  </tbody>
               </table>
               <!-- Passenger Details -->
               <div class="">
                  @php 
                  $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $Buyer_Purchase_Order_List[0]->sz_code)->get();
                  $Buyer_Purchase_Order_Detail_List = App\Models\BuyerPurchaseOrderDetailModel::
                  join('item_master','item_master.item_code', '=', 'buyer_purchase_order_detail.item_code')
                  ->join('color_master','color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                  ->where('buyer_purchase_order_detail.tr_code','=', $Buyer_Purchase_Order_List[0]->tr_code)
                  ->get(['buyer_purchase_order_detail.*', 'item_master.item_name','item_master.item_description','item_master.dimension','color_master.color_name' ]);
                  $no=1; @endphp
                  @foreach($Buyer_Purchase_Order_Detail_List as $List)   
                  <table class="table table-bordered text-1 table-sm" ><tr><th><b>For:</b>   {{$List->item_name}} <b>Color:</b>  {{$List->color_name}}  </th></tr></table>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <th>Size</th>
                        @foreach($SizeDetailList as $sz) 
                        <th>{{ $sz->size_name }}</th>
                        @endforeach
                        <th>Total</th>
                     </thead>
                     <tbody>
                        <tr>
                           <td><b>  Order Qty</b></td>
                           @php 
                           $SizeQtyList=explode(',', $List->size_qty_array)
                           @endphp
                           @php $no=1; @endphp
                           @foreach($SizeQtyList  as $size_id)
                           <td style="text-align: right;">{{ number_format($size_id) }}</td>
                           @endforeach
                           <td style="text-align: right;">{{ number_format($List->size_qty_total) }}</td>
                        </tr>
                        <tr>
                           @php
                           $sizes='';
                           $no=1;
                           foreach ($SizeDetailList as $sz) 
                           {
                           $sizes=$sizes.'ifnull(sum(s'.$no.'),0) as s_'.$no.',';
                           $no=$no+1;
                           }
                           $sizes=rtrim($sizes,',');
                           $MasterdataList = DB::select("SELECT sales_order_detail.item_code, 
                           sales_order_detail.color_id, color_name, ".$sizes.", 
                           ifnull(sum(size_qty_total),0) as size_qty_total,shipment_allowance,garment_rejection_allowance from sales_order_detail inner join color_master on 
                           color_master.color_id=sales_order_detail.color_id where tr_code='".$Buyer_Purchase_Order_List[0]->tr_code."'
                           and sales_order_detail.item_code ='".$List->item_code."' and sales_order_detail.color_id='".$List->color_id."'
                           group by sales_order_detail.color_id");
                           if(count($MasterdataList)!=0){
                           @endphp
                           <td><b>Order Qty with Allowance  </b></td>
                           @if(isset($MasterdataList[0]->s_1) || $MasterdataList[0]->s_1='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1 + round($MasterdataList[0]->s_1*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2) || $MasterdataList[0]->s_2='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2 + round($MasterdataList[0]->s_2*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3) || $MasterdataList[0]->s_3='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3 + round($MasterdataList[0]->s_3*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4) || $MasterdataList[0]->s_4='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4 + round($MasterdataList[0]->s_4*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5) || $MasterdataList[0]->s_5='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5 + round($MasterdataList[0]->s_5*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6) || $MasterdataList[0]->s_6='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6 + round($MasterdataList[0]->s_6*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7) || $MasterdataList[0]->s_7='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7 + round($MasterdataList[0]->s_7*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8) || $MasterdataList[0]->s_8='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8 + round($MasterdataList[0]->s_8*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9) || $MasterdataList[0]->s_9='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9 + round($MasterdataList[0]->s_9*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10) || $MasterdataList[0]->s_10='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10 + round($MasterdataList[0]->s_10*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11) || $MasterdataList[0]->s_11='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11 + round($MasterdataList[0]->s_11*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12) || $MasterdataList[0]->s_12='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12 + round($MasterdataList[0]->s_12*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13) || $MasterdataList[0]->s_13='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13 + round($MasterdataList[0]->s_13*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14) || $MasterdataList[0]->s_14='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14 + round($MasterdataList[0]->s_14*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15) || $MasterdataList[0]->s_15='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15 + round($MasterdataList[0]->s_15*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16) || $MasterdataList[0]->s_16='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16 + round($MasterdataList[0]->s_16*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17) || $MasterdataList[0]->s_17='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17 + round($MasterdataList[0]->s_17*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18) || $MasterdataList[0]->s_18='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18 + round($MasterdataList[0]->s_18*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19) || $MasterdataList[0]->s_19='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19 + round($MasterdataList[0]->s_19*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20) || $MasterdataList[0]->s_20='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20 + round($MasterdataList[0]->s_20*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($MasterdataList[0]->size_qty_total + round($MasterdataList[0]->size_qty_total*($MasterdataList[0]->shipment_allowance/100)))}}</td>
                        </tr>
                        <tr>
                           @php
                           }
                           $sizes='';
                           $no=1;
                           foreach ($SizeDetailList as $sz) 
                           {
                           $sizes=$sizes.'ifnull(sum(s'.$no.'),0) as s_'.$no.',';
                           $no=$no+1;
                           }
                           $sizes=rtrim($sizes,',');
                           $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, 
                           vendor_work_order_size_detail.color_id, color_name, ".$sizes.", 
                           ifnull(sum(size_qty_total),0) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
                           color_master.color_id=vendor_work_order_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'
                           and vendor_work_order_size_detail.item_code ='".$List->item_code."' and vendor_work_order_size_detail.color_id='".$List->color_id."'
                           group by vendor_work_order_size_detail.color_id");
                           if(count($MasterdataList)!=0){
                           @endphp
                           <td  ><b>Work Order Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1) || $MasterdataList[0]->s_1='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2) || $MasterdataList[0]->s_2='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3) || $MasterdataList[0]->s_3='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4) || $MasterdataList[0]->s_4='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5) || $MasterdataList[0]->s_5='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6) || $MasterdataList[0]->s_6='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7) || $MasterdataList[0]->s_7='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8) || $MasterdataList[0]->s_8='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9) || $MasterdataList[0]->s_9='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10) || $MasterdataList[0]->s_10='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11) || $MasterdataList[0]->s_11='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12) || $MasterdataList[0]->s_12='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13) || $MasterdataList[0]->s_13='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14) || $MasterdataList[0]->s_14='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15) || $MasterdataList[0]->s_15='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16) || $MasterdataList[0]->s_16='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17) || $MasterdataList[0]->s_17='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18) || $MasterdataList[0]->s_18='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19) || $MasterdataList[0]->s_19='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20) || $MasterdataList[0]->s_20='') 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($MasterdataList[0]->size_qty_total) }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $MasterdataList = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from cut_panel_grn_size_detail inner join color_master on 
                        color_master.color_id=cut_panel_grn_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        cut_panel_grn_size_detail.item_code ='".$List->item_code."' and cut_panel_grn_size_detail.color_id='".$List->color_id."'  group by cut_panel_grn_size_detail.color_id");
                        if(count($MasterdataList)!=0){
                        @endphp      
                        <tr>
                           <td  ><b>Cutting Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7))  
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9))  
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($MasterdataList[0]->size_qty_total) }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        //echo ' '.$List->color_id;
                        //DB::enableQueryLog(); 
                        $List2 = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from cut_panel_grn_size_detail inner join color_master on 
                        color_master.color_id=cut_panel_grn_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        cut_panel_grn_size_detail.item_code ='".$List->item_code."'
                        and cut_panel_grn_size_detail.color_id='".$List->color_id."'
                        group by cut_panel_grn_size_detail.color_id");
                        //$query = DB::getQueryLog();
                        //$query = end($query);
                        //dd($query);
                        $sizess='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizess=$sizess.'ifnull(sum(s'.$nox.'),0) as s'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizess=rtrim($sizess,',');
                        //DB::enableQueryLog();
                        $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, 
                        vendor_work_order_size_detail.color_id, color_name, ".$sizess.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
                        color_master.color_id=vendor_work_order_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'
                        and vendor_work_order_size_detail.item_code ='".$List->item_code."' and vendor_work_order_size_detail.color_id='".$List->color_id."'
                        group by vendor_work_order_size_detail.color_id");
                        //$query = DB::getQueryLog();
                        //$query = end($query);
                        //dd($query);
                        $Bal=0;
                        if(count($MasterdataList) > 0)
                        {
                        if(isset($MasterdataList[0]->s1)) {$s1=0; $s1=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s1 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_1 : 0)));}
                        if(isset($MasterdataList[0]->s2)) {$s2=0; $s2=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s2 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_2 : 0)));}
                        if(isset($MasterdataList[0]->s3)) {$s3=0; $s3=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s3 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_3 : 0)));}
                        if(isset($MasterdataList[0]->s4)) {$s4=0; $s4=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s4 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_4 : 0)));}
                        if(isset($MasterdataList[0]->s5)) {$s5=0; $s5=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s5 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_5 : 0)));}
                        if(isset($MasterdataList[0]->s6)) {$s6=0; $s6=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s6 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_6 : 0)));}
                        if(isset($MasterdataList[0]->s7)) {$s7=0; $s7=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s7 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_7 : 0)));}
                        if(isset($MasterdataList[0]->s8)) {$s8=0; $s8=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s8 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_8 : 0)));}
                        if(isset($MasterdataList[0]->s9)) {$s9=0; $s9=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s9 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_9 : 0)));}
                        if(isset($MasterdataList[0]->s10)) {$s10=0; $s10=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s10 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_10 : 0)));}
                        if(isset($MasterdataList[0]->s11)) {$s11=0; $s11=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s11 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_11 : 0)));}
                        if(isset($MasterdataList[0]->s12)) {$s12=0; $s12=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s12 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_12 : 0)));}
                        if(isset($MasterdataList[0]->s13)) {$s13=0; $s13=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s13 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_13 : 0)));}
                        if(isset($MasterdataList[0]->s14)) {$s14=0; $s14=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s14 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_14 : 0)));}
                        if(isset($MasterdataList[0]->s15)) {$s15=0; $s15=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s15 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_15 : 0)));}
                        if(isset($MasterdataList[0]->s16)) {$s16=0; $s16=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s16 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_16 : 0)));}
                        if(isset($MasterdataList[0]->s17)) {$s17=0; $s17=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s17 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_17 : 0)));}
                        if(isset($MasterdataList[0]->s18)) {$s18=0; $s18=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s18 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_18 : 0)));}
                        if(isset($MasterdataList[0]->s19)) {$s19=0; $s19=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s19 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_19 : 0)));}
                        if(isset($MasterdataList[0]->s20)) {$s20=0; $s20=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s20 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_20 : 0)));}      
                        if(isset($MasterdataList[0]->size_qty_total)){$Bal=0; $Bal=(isset($MasterdataList[0]) ? $MasterdataList[0]->size_qty_total : 0)-(isset($List2[0]) ? $List2[0]->size_qty_total : 0);}
                        }
                        else
                        {
                        if(isset($MasterdataList->s1)  ) {$s1=0; $s1=((intval($MasterdataList->s1))-(intval(isset($List2[0]) ? $List2[0]->s_1 : 0))); }
                        if(isset($MasterdataList->s2)  ) { $s2=0;  $s2=((intval($MasterdataList->s2))-(intval(isset($List2[0]) ? $List2[0]->s_2 : 0)));}
                        if(isset($MasterdataList->s3)  ) {$s3=0; $s3=((intval($MasterdataList->s3))-(intval(isset($List2[0]) ? $List2[0]->s_3 : 0)));}
                        if(isset($MasterdataList->s4) ) {$s4=0; $s4=((intval($MasterdataList->s4))-(intval(isset($List2[0]) ? $List2[0]->s_4 : 0)));}
                        if(isset($MasterdataList->s5) )  {$s5=0; $s5=((intval($MasterdataList->s5))-(intval(isset($List2[0]) ? $List2[0]->s_5 : 0)));}
                        if(isset($MasterdataList->s6) ) {$s6=0; $s6=((intval($MasterdataList->s6))-(intval(isset($List2[0]) ? $List2[0]->s_6 : 0)));}
                        if(isset($MasterdataList->s7) ) {$s7=0; $s7=((intval($MasterdataList->s7))-(intval(isset($List2[0]) ? $List2[0]->s_7 : 0)));}
                        if(isset($MasterdataList->s8) ) {$s8=0; $s8=((intval($MasterdataList->s8))-(intval(isset($List2[0]) ? $List2[0]->s_8 : 0)));}
                        if(isset($MasterdataList->s9) ) {$s9=0; $s9=((intval($MasterdataList->s9))-(intval(isset($List2[0]) ? $List2[0]->s_9 : 0)));}
                        if(isset($MasterdataList->s10)  ) {$s10=0; $s10=((intval($MasterdataList->s10))-(intval(isset($List2[0]) ? $List2[0]->s_10 : 0)));}
                        if(isset($MasterdataList->s11) ) {$s11=0; $s11=((intval($MasterdataList->s11))-(intval(isset($List2[0]) ? $List2[0]->s_11 : 0)));}
                        if(isset($MasterdataList->s12) ) {$s12=0; $s12=((intval($MasterdataList->s12))-(intval(isset($List2[0]) ? $List2[0]->s_12 : 0)));}
                        if(isset($MasterdataList->s13) ) {$s13=0; $s13=((intval($MasterdataList->s13))-(intval(isset($List2[0]) ? $List2[0]->s_13 : 0)));}
                        if(isset($MasterdataList->s14) ) {$s14=0; $s14=((intval($MasterdataList->s14))-(intval(isset($List2[0]) ? $List2[0]->s_14 : 0)));}
                        if(isset($MasterdataList->s15) ) {$s15=0; $s15=((intval($MasterdataList->s15))-(intval(isset($List2[0]) ? $List2[0]->s_15 : 0)));}
                        if(isset($MasterdataList->s16) ) {$s16=0; $s16=((intval($MasterdataList->s16))-(intval(isset($List2[0]) ? $List2[0]->s_16 : 0)));}
                        if(isset($MasterdataList->s17) ) {$s17=0; $s17=((intval($MasterdataList->s17))-(intval(isset($List2[0]) ? $List2[0]->s_17 : 0)));}
                        if(isset($MasterdataList->s18) ) {$s18=0; $s18=((intval($MasterdataList->s18))-(intval(isset($List2[0]) ? $List2[0]->s_18 : 0)));}
                        if(isset($MasterdataList->s19) ) {$s19=0; $s19=((intval($MasterdataList->s19))-(intval(isset($List2[0]) ? $List2[0]->s_19 : 0)));}
                        if(isset($MasterdataList->s20) ) {$s20=0; $s20=((intval($MasterdataList->s20))-(intval(isset($List2[0]) ? $List2[0]->s_20 : 0)));}       
                        if(isset($MasterdataList->size_qty_total)){$Bal=0; $Bal=(isset($MasterdataList) ? $MasterdataList->size_qty_total : 0)-(isset($List2[0]) ? $List2[0]->size_qty_total : 0);} 
                        }
                        if(count($MasterdataList)!=0 && count($List2)!=0){
                        @endphp      
                        <tr>
                           <td><b>Cutting Balance Qty </b></td>
                           @if(isset($s1)) 
                           <td style="text-align: right;">{{number_format($s1)}}</td>
                           @endif
                           @if(isset($s2)) 
                           <td style="text-align: right;">{{number_format($s2)}}</td>
                           @endif
                           @if(isset($s3)) 
                           <td style="text-align: right;">{{number_format($s3)}}</td>
                           @endif
                           @if(isset($s4)) 
                           <td style="text-align: right;">{{number_format($s4)}}</td>
                           @endif
                           @if(isset($s5)) 
                           <td style="text-align: right;">{{number_format($s5)}}</td>
                           @endif
                           @if(isset($s6)) 
                           <td style="text-align: right;">{{number_format($s6)}}</td>
                           @endif
                           @if(isset($s7)) 
                           <td style="text-align: right;">{{number_format($s7)}}</td>
                           @endif
                           @if(isset($s8)) 
                           <td style="text-align: right;">{{number_format($s8)}}</td>
                           @endif
                           @if(isset($s9)) 
                           <td style="text-align: right;">{{number_format($s9)}}</td>
                           @endif
                           @if(isset($s10)) 
                           <td style="text-align: right;">{{number_format($s10)}}</td>
                           @endif
                           @if(isset($s11)) 
                           <td style="text-align: right;">{{number_format($s11)}}</td>
                           @endif
                           @if(isset($s12)) 
                           <td style="text-align: right;">{{number_format($s12)}}</td>
                           @endif
                           @if(isset($s13)) 
                           <td style="text-align: right;">{{number_format($s13)}}</td>
                           @endif
                           @if(isset($s14)) 
                           <td style="text-align: right;">{{number_format($s14)}}</td>
                           @endif
                           @if(isset($s15)) 
                           <td style="text-align: right;">{{number_format($s15)}}</td>
                           @endif
                           @if(isset($s16)) 
                           <td style="text-align: right;">{{number_format($s16)}}</td>
                           @endif 
                           @if(isset($s17)) 
                           <td style="text-align: right;">{{number_format($s17)}}</td>
                           @endif 
                           @if(isset($s18)) 
                           <td style="text-align: right;">{{number_format($s18)}}</td>
                           @endif
                           @if(isset($s19)) 
                           <td style="text-align: right;">{{number_format($s19)}}</td>
                           @endif 
                           @if(isset($s20)) 
                           <td style="text-align: right;">{{number_format($s20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($Bal) }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $MasterdataList = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from stitching_inhouse_size_detail inner join color_master on 
                        color_master.color_id=stitching_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        stitching_inhouse_size_detail.item_code ='".$List->item_code."'
                        and stitching_inhouse_size_detail.color_id='".$List->color_id."'
                        group by stitching_inhouse_size_detail.color_id");
                        if(count($MasterdataList)!=0){
                        @endphp      
                        <tr>
                           <td><b>Production Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ $MasterdataList[0]->size_qty_total }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                       
                        $MasterdataList = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from cut_panel_grn_size_detail inner join color_master on 
                        color_master.color_id=cut_panel_grn_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        cut_panel_grn_size_detail.item_code ='".$List->item_code."' 
                        and cut_panel_grn_size_detail.color_id='".$List->color_id."'
                        group by cut_panel_grn_size_detail.color_id");
                        
                        $sizess='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizess=$sizess.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizess=rtrim($sizess,',');
                        // DB::enableQueryLog();
                        $List2    = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizess.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from stitching_inhouse_size_detail inner join color_master on 
                        color_master.color_id=stitching_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        stitching_inhouse_size_detail.item_code ='".$List->item_code."'
                        and stitching_inhouse_size_detail.color_id='".$List->color_id."'
                        group by stitching_inhouse_size_detail.color_id");
                       // dd(DB::getQueryLog());
                        if(count($MasterdataList) > 0 ){
                        if(isset($MasterdataList[0]->s1)) {$s1=0; $s1=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s1 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_1 : 0)));  }
                        if(isset($MasterdataList[0]->s2)) { $s2=0;  $s2=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s2 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_2 : 0)));}
                        if(isset($MasterdataList[0]->s3)) {$s3=0; $s3=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s3 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_3 : 0)));}
                        if(isset($MasterdataList[0]->s4)) {$s4=0; $s4=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s4 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_4 : 0)));}
                        if(isset($MasterdataList[0]->s5)) {$s5=0; $s5=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s5 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_5 : 0)));}
                        if(isset($MasterdataList[0]->s6)) {$s6=0; $s6=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s6 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_6 : 0)));}
                        if(isset($MasterdataList[0]->s7)) {$s7=0; $s7=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s7 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_7 : 0)));}
                        if(isset($MasterdataList[0]->s8)) {$s8=0; $s8=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s8 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_8 : 0)));}
                        if(isset($MasterdataList[0]->s9)) {$s9=0; $s9=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s9 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_9 : 0)));}
                        if(isset($MasterdataList[0]->s10)) {$s10=0; $s10=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s10 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_10 : 0)));}
                        if(isset($MasterdataList[0]->s11)) {$s11=0; $s11=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s11 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_11 : 0)));}
                        if(isset($MasterdataList[0]->s12)) {$s12=0; $s12=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s12 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_12 : 0)));}
                        if(isset($MasterdataList[0]->s13)) {$s13=0; $s13=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s13 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_13 : 0)));}
                        if(isset($MasterdataList[0]->s14)) {$s14=0; $s14=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s14 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_14 : 0)));}
                        if(isset($MasterdataList[0]->s15)) {$s15=0; $s15=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s15 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_15 : 0)));}
                        if(isset($MasterdataList[0]->s16)) {$s16=0; $s16=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s16 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_16 : 0)));}
                        if(isset($MasterdataList[0]->s17)) {$s17=0; $s17=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s17 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_17 : 0)));}
                        if(isset($MasterdataList[0]->s18)) {$s18=0; $s18=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s18 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_18 : 0)));}
                        if(isset($MasterdataList[0]->s19)) {$s19=0; $s19=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s19 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_19 : 0)));}
                        if(isset($MasterdataList[0]->s20)) {$s20=0; $s20=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s20 : 0))-(intval(isset($List2[0]) ? $List2[0]->s_20 : 0)));}               
                        }
                        else
                        {
                        if(isset($MasterdataList->s1)) {$s1=0; $s1=((intval($MasterdataList->s1))-(intval(isset($List2[0]) ? $List2[0]->s_1 : 0)));  }
                        if(isset($MasterdataList->s2)) { $s2=0;  $s2=((intval($MasterdataList->s2))-(intval(isset($List2[0]) ? $List2[0]->s_2 : 0)));}
                        if(isset($MasterdataList->s3)) {$s3=0; $s3=((intval($MasterdataList->s3))-(intval(isset($List2[0]) ? $List2[0]->s_3 : 0)));}
                        if(isset($MasterdataList->s4)) {$s4=0; $s4=((intval($MasterdataList->s4))-(intval(isset($List2[0]) ? $List2[0]->s_4 : 0)));}
                        if(isset($MasterdataList->s5)) {$s5=0; $s5=((intval($MasterdataList->s5))-(intval(isset($List2[0]) ? $List2[0]->s_5 : 0)));}
                        if(isset($MasterdataList->s6)) {$s6=0; $s6=((intval($MasterdataList->s6))-(intval(isset($List2[0]) ? $List2[0]->s_6 : 0)));}
                        if(isset($MasterdataList->s7)) {$s7=0; $s7=((intval($MasterdataList->s7))-(intval(isset($List2[0]) ? $List2[0]->s_7 : 0)));}
                        if(isset($MasterdataList->s8)) {$s8=0; $s8=((intval($MasterdataList->s8))-(intval(isset($List2[0]) ? $List2[0]->s_8 : 0)));}
                        if(isset($MasterdataList->s9)) {$s9=0; $s9=((intval($MasterdataList->s9))-(intval(isset($List2[0]) ? $List2[0]->s_9 : 0)));}
                        if(isset($MasterdataList->s10)) {$s10=0; $s10=((intval($MasterdataList->s10))-(intval(isset($List2[0]) ? $List2[0]->s_10 : 0)));}
                        if(isset($MasterdataList->s11)) {$s11=0; $s11=((intval($MasterdataList->s11))-(intval(isset($List2[0]) ? $List2[0]->s_11 : 0)));}
                        if(isset($MasterdataList->s12)) {$s12=0; $s12=((intval($MasterdataList->s12))-(intval(isset($List2[0]) ? $List2[0]->s_12 : 0)));}
                        if(isset($MasterdataList->s13)) {$s13=0; $s13=((intval($MasterdataList->s13))-(intval(isset($List2[0]) ? $List2[0]->s_13 : 0)));}
                        if(isset($MasterdataList->s14)) {$s14=0; $s14=((intval($MasterdataList->s14))-(intval(isset($List2[0]) ? $List2[0]->s_14 : 0)));}
                        if(isset($MasterdataList->s15)) {$s15=0; $s15=((intval($MasterdataList->s15))-(intval(isset($List2[0]) ? $List2[0]->s_15 : 0)));}
                        if(isset($MasterdataList->s16)) {$s16=0; $s16=((intval($MasterdataList->s16))-(intval(isset($List2[0]) ? $List2[0]->s_16 : 0)));}
                        if(isset($MasterdataList->s17)) {$s17=0; $s17=((intval($MasterdataList->s17))-(intval(isset($List2[0]) ? $List2[0]->s_17 : 0)));}
                        if(isset($MasterdataList->s18)) {$s18=0; $s18=((intval($MasterdataList->s18))-(intval(isset($List2[0]) ? $List2[0]->s_18 : 0)));}
                        if(isset($MasterdataList->s19)) {$s19=0; $s19=((intval($MasterdataList->s19))-(intval(isset($List2[0]) ? $List2[0]->s_19 : 0)));}
                        if(isset($MasterdataList->s20)) {$s20=0; $s20=((intval($MasterdataList->s20))-(intval(isset($List2[0]) ? $List2[0]->s_20 : 0)));} 
                        }
                        if(count($MasterdataList)!=0 && count($List2)!=0){
                        @endphp      
                        <tr>
                           <td><b>Production Balance Qty </b> </td>
                           @if(isset($s1)) 
                           <td style="text-align: right;">{{number_format($s1)}}</td>
                           @endif
                           @if(isset($s2)) 
                           <td style="text-align: right;">{{number_format($s2)}}</td>
                           @endif
                           @if(isset($s3)) 
                           <td style="text-align: right;">{{number_format($s3)}}</td>
                           @endif
                           @if(isset($s4)) 
                           <td style="text-align: right;">{{number_format($s4)}}</td>
                           @endif
                           @if(isset($s5)) 
                           <td style="text-align: right;">{{number_format($s5)}}</td>
                           @endif
                           @if(isset($s6)) 
                           <td style="text-align: right;">{{number_format($s6)}}</td>
                           @endif
                           @if(isset($s7)) 
                           <td style="text-align: right;">{{number_format($s7)}}</td>
                           @endif
                           @if(isset($s8)) 
                           <td style="text-align: right;">{{number_format($s8)}}</td>
                           @endif
                           @if(isset($s9)) 
                           <td style="text-align: right;">{{number_format($s9)}}</td>
                           @endif
                           @if(isset($s10)) 
                           <td style="text-align: right;">{{number_format($s10)}}</td>
                           @endif
                           @if(isset($s11)) 
                           <td style="text-align: right;">{{number_format($s11)}}</td>
                           @endif
                           @if(isset($s12)) 
                           <td style="text-align: right;">{{number_format($s12)}}</td>
                           @endif
                           @if(isset($s13)) 
                           <td style="text-align: right;">{{number_format($s13)}}</td>
                           @endif
                           @if(isset($s14)) 
                           <td style="text-align: right;">{{number_format($s14)}}</td>
                           @endif
                           @if(isset($s15)) 
                           <td style="text-align: right;">{{number_format($s15)}}</td>
                           @endif
                           @if(isset($s16)) 
                           <td style="text-align: right;">{{number_format($s16)}}</td>
                           @endif 
                           @if(isset($s17)) 
                           <td style="text-align: right;">{{number_format($s17)}}</td>
                           @endif 
                           @if(isset($s18)) 
                           <td style="text-align: right;">{{number_format($s18)}}</td>
                           @endif
                           @if(isset($s19)) 
                           <td style="text-align: right;">{{number_format($s19)}}</td>
                           @endif 
                           @if(isset($s20)) 
                           <td style="text-align: right;">{{number_format($s20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format((isset($MasterdataList[0]) ? $MasterdataList[0]->size_qty_total : 0)-(isset($List2[0]) ? $List2[0]->size_qty_total :0)) }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $MasterdataList = DB::select("SELECT qcstitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from qcstitching_inhouse_size_detail inner join color_master on 
                        color_master.color_id=qcstitching_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        qcstitching_inhouse_size_detail.item_code ='".$List->item_code."'
                        and qcstitching_inhouse_size_detail.color_id='".$List->color_id."'
                        group by qcstitching_inhouse_size_detail.color_id");
                        if(count($MasterdataList)!=0){
                        @endphp      
                        <tr>
                           <td><b>QC Pass Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ $MasterdataList[0]->size_qty_total }}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        
                       

                        $MasterdataList = DB::select("SELECT qcstitching_inhouse_size_reject_detail.color_id,qcstitching_inhouse_size_reject_detail.size_array, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from qcstitching_inhouse_size_reject_detail inner join color_master on 
                        color_master.color_id=qcstitching_inhouse_size_reject_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        qcstitching_inhouse_size_reject_detail.item_code ='".$List->item_code."'
                        and qcstitching_inhouse_size_reject_detail.color_id='".$List->color_id."'
                        group by qcstitching_inhouse_size_reject_detail.color_id");
                        
                         //DB::enableQueryLog();
                        $RejectDataList = DB::select("SELECT qcstitching_inhouse_reject_detail.color_id,qcstitching_inhouse_reject_detail.size_array, color_name
                               from qcstitching_inhouse_reject_detail inner join color_master on 
                              color_master.color_id=qcstitching_inhouse_reject_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'");
                         // dd(DB::getQueryLog());
                        if(count($RejectDataList) > 0)
                        {
                            $reject_qty = explode(",", $RejectDataList[0]->size_array);
                        }
                        else
                        {
                           $reject_qty = 0;
                        }
                     
                        if(count($RejectDataList) > 0){
                        $rejTotal = 0;
                        @endphp      
                        <tr>
                           <td><b>QC Reject Qty</b></td>
                           @foreach($reject_qty as $val => $key)
                           @php
                        
                            $rejData = DB::select("SELECT sum((select qcstitching_inhouse_size_reject_detail2.size_qty
                                from  qcstitching_inhouse_size_reject_detail2 where qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code
                                and qcstitching_inhouse_size_reject_detail2.color_id=qcstitching_inhouse_size_detail2.color_id  
                                and  qcstitching_inhouse_size_reject_detail2.size_id = qcstitching_inhouse_size_detail2.size_id )) as reject_order_qty
                                FROM qcstitching_inhouse_size_detail2
                                WHERE qcstitching_inhouse_size_detail2.sales_order_no = '".$List->tr_code."' 
                                AND qcstitching_inhouse_size_detail2.color_id=".$List->color_id." AND qcstitching_inhouse_size_detail2.size_id='".$key."'");
                                
                                if(count($rejData) > 0)
                                {
                                    $rej_qty = $rejData[0]->reject_order_qty;
                                }
                                else
                                {
                                   $rej_qty = 0;
                                }
                                
                                $rejTotal =  $rejTotal + $rej_qty;
                         @endphp
                           <td style="text-align: right;">{{$rej_qty}}</td>
                           @endforeach
                           <td style="text-align: right;">{{$rejTotal}}</td>
                        </tr>
                        @php
                        }
                        $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $MasterdataList = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from packing_inhouse_size_detail inner join color_master on 
                        color_master.color_id=packing_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        packing_inhouse_size_detail.item_code ='".$List->item_code."' 
                        and packing_inhouse_size_detail.color_id='".$List->color_id."'
                        group by packing_inhouse_size_detail.color_id");
                        if(count($MasterdataList)!=0){
                        @endphp      
                        <tr>
                           <td><b>Packing Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ $MasterdataList[0]->size_qty_total }}</td>
                        </tr>
                        @php
                        }
                        $sizexx='';
                        $noxx=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizexx=$sizexx.'ifnull(sum(s'.$noxx.'),0) as s_'.$noxx.',';
                        $noxx=$noxx+1;
                        }
                        $sizexx=rtrim($sizexx,',');
                      
                        $MasterdataList1 = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizexx.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from cut_panel_grn_size_detail inner join color_master on 
                        color_master.color_id=cut_panel_grn_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        cut_panel_grn_size_detail.item_code ='".$List->item_code."' 
                        and cut_panel_grn_size_detail.color_id='".$List->color_id."'
                        group by cut_panel_grn_size_detail.color_id");
                       
                        $sizesss='';
                        $noxs=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                            $sizesss=$sizesss.'ifnull(sum(s'.$noxs.'),0) as s'.$noxs.',';
                            $noxs=$noxs+1;
                        }
                        $sizesss=rtrim($sizesss,',');
                      
                        $List21   = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizesss.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from packing_inhouse_size_detail inner join color_master on 
                        color_master.color_id=packing_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        packing_inhouse_size_detail.item_code ='".$List->item_code."'
                        and packing_inhouse_size_detail.color_id='".$List->color_id."'
                        group by packing_inhouse_size_detail.color_id");
                       
                       //print_r($List21);exit; 
                        if(count($MasterdataList1) > 0 )
                        {
                       
                            if(isset($MasterdataList1[0]->s_1)) { $s1=((intval(isset($MasterdataList1[0]) ?   $MasterdataList1[0]->s_1 : 0))-(intval(isset($List21[0]) ? $List21[0]->s1 : 0)));}
                            if(isset($MasterdataList1[0]->s_2)) { $s2=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_2 : 0  ))-(intval(isset($List21[0]) ? $List21[0]->s2 : 0)));}
                            if(isset($MasterdataList1[0]->s_3)) { $s3=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_3 : 0))-(intval(isset($List21[0]) ? $List21[0]->s3 : 0)));}
                            if(isset($MasterdataList1[0]->s_4)) { $s4=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_4 : 0))-(intval(isset($List21[0]) ? $List21[0]->s4 : 0)));}
                            if(isset($MasterdataList1[0]->s_5)) { $s5=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_5 : 0))-(intval(isset($List21[0]) ? $List21[0]->s5 : 0)));}
                            if(isset($MasterdataList1[0]->s_6)) { $s6=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_6 : 0))-(intval(isset($List21[0]) ? $List21[0]->s6 : 0)));}
                            if(isset($MasterdataList1[0]->s_7)) { $s7=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_7 : 0))-(intval(isset($List21[0]) ? $List21[0]->s7 : 0)));}
                            if(isset($MasterdataList1[0]->s_8)) { $s8=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_8 : 0))-(intval(isset($List21[0]) ? $List21[0]->s8 : 0)));}
                            if(isset($MasterdataList1[0]->s_9)) { $s9=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_9 : 0))-(intval(isset($List21[0]) ? $List21[0]->s9 : 0)));}
                            if(isset($MasterdataList1[0]->s_10)) { $s10=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_10 : 0))-(intval(isset($List21[0]) ? $List21[0]->s10 : 0)));}
                            if(isset($MasterdataList1[0]->s_11)) { $s11=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_11 : 0))-(intval(isset($List21[0]) ? $List21[0]->s11 : 0)));}
                            if(isset($MasterdataList1[0]->s_12)) { $s12=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_12 : 0))-(intval(isset($List21[0]) ? $List21[0]->s12 : 0)));}
                            if(isset($MasterdataList1[0]->s_13)) { $s13=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_13 : 0))-(intval(isset($List21[0]) ? $List21[0]->s13 : 0)));}
                            if(isset($MasterdataList1[0]->s_14)) { $s14=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_14 : 0))-(intval(isset($List21[0]) ? $List21[0]->s14 : 0)));}
                            if(isset($MasterdataList1[0]->s_15)) { $s15=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_15 : 0))-(intval(isset($List21[0]) ? $List21[0]->s15 : 0)));}
                            if(isset($MasterdataList1[0]->s_16)) { $s16=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_16 : 0))-(intval(isset($List21[0]) ? $List21[0]->s16 : 0)));}
                            if(isset($MasterdataList1[0]->s_17)) { $s17=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_17 : 0))-(intval(isset($List21[0]) ? $List21[0]->s17 : 0)));}
                            if(isset($MasterdataList1[0]->s_18)) { $s18=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_18 : 0))-(intval(isset($List21[0]) ? $List21[0]->s18 : 0)));}
                            if(isset($MasterdataList1[0]->s_19)) { $s19=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_19 : 0))-(intval(isset($List21[0]) ? $List21[0]->s19 : 0)));}
                            if(isset($MasterdataList1[0]->s_20)) { $s20=((intval(isset($MasterdataList1[0]) ? $MasterdataList1[0]->s_20 : 0))-(intval(isset($List21[0]) ? $List21[0]->s20 : 0)));}               
                        }
                        if(count($MasterdataList1)!=0 && count($List21)!=0)
                        {
                      
                        @endphp      
                        <tr>
                           <td> <b>Packing Balance Qty</b>  </td>
                           @if(isset($s1)) 
                           <td style="text-align: right;">{{$s1}}</td>
                           @endif
                           @if(isset($s2)) 
                           <td style="text-align: right;">{{number_format($s2)}}</td>
                           @endif
                           @if(isset($s3)) 
                           <td style="text-align: right;">{{number_format($s3)}}</td>
                           @endif
                           @if(isset($s4)) 
                           <td style="text-align: right;">{{number_format($s4)}}</td>
                           @endif
                           @if(isset($s5)) 
                           <td style="text-align: right;">{{number_format($s5)}}</td>
                           @endif
                           @if(isset($s6)) 
                           <td style="text-align: right;">{{number_format($s6)}}</td>
                           @endif
                           @if(isset($s7)) 
                           <td style="text-align: right;">{{number_format($s7)}}</td>
                           @endif
                           @if(isset($s8)) 
                           <td style="text-align: right;">{{number_format($s8)}}</td>
                           @endif
                           @if(isset($s9)) 
                           <td style="text-align: right;">{{number_format($s9)}}</td>
                           @endif
                           @if(isset($s10)) 
                           <td style="text-align: right;">{{number_format($s10)}}</td>
                           @endif
                           @if(isset($s11)) 
                           <td style="text-align: right;">{{number_format($s11)}}</td>
                           @endif
                           @if(isset($s12)) 
                           <td style="text-align: right;">{{number_format($s12)}}</td>
                           @endif
                           @if(isset($s13)) 
                           <td style="text-align: right;">{{number_format($s13)}}</td>
                           @endif
                           @if(isset($s14)) 
                           <td style="text-align: right;">{{number_format($s14)}}</td>
                           @endif
                           @if(isset($s15)) 
                           <td style="text-align: right;">{{number_format($s15)}}</td>
                           @endif
                           @if(isset($s16)) 
                           <td style="text-align: right;">{{number_format($s16)}}</td>
                           @endif 
                           @if(isset($s17)) 
                           <td style="text-align: right;">{{number_format($s17)}}</td>
                           @endif 
                           @if(isset($s18)) 
                           <td style="text-align: right;">{{number_format($s18)}}</td>
                           @endif
                           @if(isset($s19)) 
                           <td style="text-align: right;">{{number_format($s19)}}</td>
                           @endif 
                           @if(isset($s20)) 
                           <td style="text-align: right;">{{number_format($s20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($MasterdataList1[0]->size_qty_total-$List21[0]->size_qty_total) }}</td>
                        </tr>
                        @php }  
                        
                        
                        
                         $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                        $MasterdataList = DB::select("SELECT carton_packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from carton_packing_inhouse_size_detail
                        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail.cpki_code 
                        inner join color_master on color_master.color_id=carton_packing_inhouse_size_detail.color_id 
                        where carton_packing_inhouse_size_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'  
                        and carton_packing_inhouse_size_detail.color_id='".$List->color_id."' and carton_packing_inhouse_master.endflag=1
                        group by carton_packing_inhouse_size_detail.color_id");
                        if(count($MasterdataList)!=0){
                        @endphp      
                        <tr>
                           <td><b>Shipment Qty</b></td>
                           @if(isset($MasterdataList[0]->s_1)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_1)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_2)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_2)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_3)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_3)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_4)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_4)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_5)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_5)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_6)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_6)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_7)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_7)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_8)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_8)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_9)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_9)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_10)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_10)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_11)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_11)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_12)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_12)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_13)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_13)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_14)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_14)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_15)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_15)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_16)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_16)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_17)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_17)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_18)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_18)}}</td>
                           @endif
                           @if(isset($MasterdataList[0]->s_19)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_19)}}</td>
                           @endif 
                           @if(isset($MasterdataList[0]->s_20)) 
                           <td style="text-align: right;">{{number_format($MasterdataList[0]->s_20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ $MasterdataList[0]->size_qty_total }}</td>
                        </tr>
                        @php
                        }
                        
                         
                          $sizex='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                        $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
                        $nox=$nox+1;
                        }
                        $sizex=rtrim($sizex,',');
                         $MasterdataList= DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from cut_panel_grn_size_detail inner join color_master on 
                        color_master.color_id=cut_panel_grn_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                        cut_panel_grn_size_detail.item_code ='".$List->item_code."' 
                        and cut_panel_grn_size_detail.color_id='".$List->color_id."'
                        group by cut_panel_grn_size_detail.color_id");
                        $sizess='';
                        $nox=1;
                        foreach ($SizeDetailList as $sz) 
                        {
                            $sizess=$sizess.'ifnull(sum(s'.$nox.'),0) as s'.$nox.',';
                            $nox=$nox+1;
                        }
                        
                        $sizess=rtrim($sizess,',');
                     
                         $List2 = DB::select("SELECT carton_packing_inhouse_size_detail.color_id, color_name, ".$sizess.", 
                        ifnull(sum(size_qty_total),0) as size_qty_total  from carton_packing_inhouse_size_detail
                        inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail.cpki_code 
                        inner join color_master on color_master.color_id=carton_packing_inhouse_size_detail.color_id 
                        where carton_packing_inhouse_size_detail.sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'  
                        and carton_packing_inhouse_size_detail.color_id='".$List->color_id."' and carton_packing_inhouse_master.endflag=1
                        group by carton_packing_inhouse_size_detail.color_id");
                        
                       // echo $MasterdataList[0]->s1
                        if(isset($MasterdataList[0]->s_1)) {$s1=0; $s1=((intval(isset($MasterdataList[0]) ?   $MasterdataList[0]->s_1 : 0))-(intval(isset($List2[0]) ? $List2[0]->s1 : 0)));  }
                        if(isset($MasterdataList[0]->s_2)) { $s2=0;  $s2=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_2 : 0  ))-(intval(isset($List2[0]) ? $List2[0]->s2 : 0)));}
                        if(isset($MasterdataList[0]->s_3)) {$s3=0; $s3=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_3 : 0))-(intval(isset($List2[0]) ? $List2[0]->s3 : 0)));}
                        if(isset($MasterdataList[0]->s_4)) {$s4=0; $s4=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_4 : 0))-(intval(isset($List2[0]) ? $List2[0]->s4 : 0)));}
                        if(isset($MasterdataList[0]->s_5)) {$s5=0; $s5=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_5 : 0))-(intval(isset($List2[0]) ? $List2[0]->s5 : 0)));}
                        if(isset($MasterdataList[0]->s_6)) {$s6=0; $s6=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_6 : 0))-(intval(isset($List2[0]) ? $List2[0]->s6 : 0)));}
                        if(isset($MasterdataList[0]->s_7)) {$s7=0; $s7=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_7 : 0))-(intval(isset($List2[0]) ? $List2[0]->s7 : 0)));}
                        if(isset($MasterdataList[0]->s_8)) {$s8=0; $s8=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_8 : 0))-(intval(isset($List2[0]) ? $List2[0]->s8 : 0)));}
                        if(isset($MasterdataList[0]->s_9)) {$s9=0; $s9=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_9 : 0))-(intval(isset($List2[0]) ? $List2[0]->s9 : 0)));}
                        if(isset($MasterdataList[0]->s_10)) {$s10=0; $s10=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_10 : 0))-(intval(isset($List2[0]) ? $List2[0]->s10 : 0)));}
                        if(isset($MasterdataList[0]->s_11)) {$s11=0; $s11=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_11 : 0))-(intval(isset($List2[0]) ? $List2[0]->s11 : 0)));}
                        if(isset($MasterdataList[0]->s_12)) {$s12=0; $s12=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_12 : 0))-(intval(isset($List2[0]) ? $List2[0]->s12 : 0)));}
                        if(isset($MasterdataList[0]->s_13)) {$s13=0; $s13=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_13 : 0))-(intval(isset($List2[0]) ? $List2[0]->s13 : 0)));}
                        if(isset($MasterdataList[0]->s_14)) {$s14=0; $s14=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_14 : 0))-(intval(isset($List2[0]) ? $List2[0]->s14 : 0)));}
                        if(isset($MasterdataList[0]->s_15)) {$s15=0; $s15=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_15 : 0))-(intval(isset($List2[0]) ? $List2[0]->s15 : 0)));}
                        if(isset($MasterdataList[0]->s_16)) {$s16=0; $s16=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_16 : 0))-(intval(isset($List2[0]) ? $List2[0]->s16 : 0)));}
                        if(isset($MasterdataList[0]->s_17)) {$s17=0; $s17=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_17 : 0))-(intval(isset($List2[0]) ? $List2[0]->s17 : 0)));}
                        if(isset($MasterdataList[0]->s_18)) {$s18=0; $s18=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_18 : 0))-(intval(isset($List2[0]) ? $List2[0]->s18 : 0)));}
                        if(isset($MasterdataList[0]->s_19)) {$s19=0; $s19=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_19 : 0))-(intval(isset($List2[0]) ? $List2[0]->s19 : 0)));}
                        if(isset($MasterdataList[0]->s_20)) {$s20=0; $s20=((intval(isset($MasterdataList[0]) ? $MasterdataList[0]->s_20 : 0))-(intval(isset($List2[0]) ? $List2[0]->s20 : 0)));}               
                       // echo count($MasterdataList);
                        if(count($MasterdataList)!=0 && count($List2)!=0){
                        @endphp      
                        <tr>
                           <td> <b>Shipment Balance Qty</b>  </td>
                           @if(isset($s1)) 
                           <td style="text-align: right;">{{number_format($s1)}}</td>
                           @endif
                           @if(isset($s2)) 
                           <td style="text-align: right;">{{number_format($s2)}}</td>
                           @endif
                           @if(isset($s3)) 
                           <td style="text-align: right;">{{number_format($s3)}}</td>
                           @endif
                           @if(isset($s4)) 
                           <td style="text-align: right;">{{number_format($s4)}}</td>
                           @endif
                           @if(isset($s5)) 
                           <td style="text-align: right;">{{number_format($s5)}}</td>
                           @endif
                           @if(isset($s6)) 
                           <td style="text-align: right;">{{number_format($s6)}}</td>
                           @endif
                           @if(isset($s7)) 
                           <td style="text-align: right;">{{number_format($s7)}}</td>
                           @endif
                           @if(isset($s8)) 
                           <td style="text-align: right;">{{number_format($s8)}}</td>
                           @endif
                           @if(isset($s9)) 
                           <td style="text-align: right;">{{number_format($s9)}}</td>
                           @endif
                           @if(isset($s10)) 
                           <td style="text-align: right;">{{number_format($s10)}}</td>
                           @endif
                           @if(isset($s11)) 
                           <td style="text-align: right;">{{number_format($s11)}}</td>
                           @endif
                           @if(isset($s12)) 
                           <td style="text-align: right;">{{number_format($s12)}}</td>
                           @endif
                           @if(isset($s13)) 
                           <td style="text-align: right;">{{number_format($s13)}}</td>
                           @endif
                           @if(isset($s14)) 
                           <td style="text-align: right;">{{number_format($s14)}}</td>
                           @endif
                           @if(isset($s15)) 
                           <td style="text-align: right;">{{number_format($s15)}}</td>
                           @endif
                           @if(isset($s16)) 
                           <td style="text-align: right;">{{number_format($s16)}}</td>
                           @endif 
                           @if(isset($s17)) 
                           <td style="text-align: right;">{{number_format($s17)}}</td>
                           @endif 
                           @if(isset($s18)) 
                           <td style="text-align: right;">{{number_format($s18)}}</td>
                           @endif
                           @if(isset($s19)) 
                           <td style="text-align: right;">{{number_format($s19)}}</td>
                           @endif 
                           @if(isset($s20)) 
                           <td style="text-align: right;">{{number_format($s20)}}</td>
                           @endif
                           <td style="text-align: right;">{{ number_format($MasterdataList[0]->size_qty_total-$List2[0]->size_qty_total) }}</td>
                        </tr>
                        @php } @endphp
                     </tbody>
                     </tbody>
                  </table>
                  @endforeach   
                  @else
                  <center>
                     <h4 style="font-weight:bold;">Record Not Found</h4>
                  </center>
                  @endif
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
                  </div>
                  <br>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>
   
        function html_table_to_excel(type)
        {
            var data = document.getElementById('invoice');
    
            var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    
            XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
    
            XLSX.writeFile(file, 'OCR REPORT.' + type);
        }
    
        const export_button = document.getElementById('export_button');
    
        export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
        });
    
      $(document).ready(function()
      {
             var result = [];
         	var s1AllCount = $("#s1AllCount").val();
             $('#Summary tr').each(function(){
               $('td', this).each(function(index, val){
                   if(!result[index]) result[index] = 0;
                   result[index] += parseFloat($(val).html().replace(/,/g , ''));
               });
             });
            
             result.shift();
      
             $('#Summary').append('<tr><td   class="text-right"><strong>Total : </strong></td></tr>');
             $(result).each(function(){
                 var x=this;
                 x=x.toString();
                 var lastThree = x.substring(x.length-3);
                 var otherNumbers = x.substring(0,x.length-3);
                 if(otherNumbers != '')
                 
                 lastThree = ',' + lastThree;
                 var output = lastThree.split('.')[1];
                 //console.log(lastThree.replace(',', ''));
                 if(output > 0)
                 {  
                    
                     var res = ((otherNumbers+'.'+output)/s1AllCount).toFixed(2);
                     res.replace(',', '');
                     $('#Summary tr').last().append('<td class="text-center"><strong>'+res+'%</strong></td>')
                 }
                 else
                 {
                     var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",")+ lastThree;
                     $('#Summary tr').last().append('<td class="text-center"><strong>'+res+'</strong></td>')
                 }
               
             });
             
             var order_qty = $('#Summary tr').last().find('td:nth-child(2) strong').html().replace(/,/g , '');
             var cut_qty = $('#Summary tr').last().find('td:nth-child(5) strong').html().replace(/,/g , '');
             var shipment_qty = $('#Summary tr').last().find('td:nth-child(12) strong').html().replace(/,/g , '');
             var cut_ship_per = (parseFloat(shipment_qty)/parseFloat(cut_qty)) * 100;
             var order_ship_per = (parseFloat(shipment_qty)/parseFloat(order_qty)) * 100;
             console.log(cut_ship_per);
             $('#Summary tr').last().find('td:nth-child(15)').html(cut_ship_per.toFixed(2));
             $('#Summary tr').last().find('td:nth-child(16)').html(order_ship_per.toFixed(2));
             
       });
      
      
      
    $('#printInvoice').click(function()
    {
         Popup($('.invoice')[0].outerHTML);
         function Popup(data) 
         {
             window.print();
             return true;
         }
    });
      	
   </script>
</html>