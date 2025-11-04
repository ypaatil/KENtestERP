<!DOCTYPE html>
<html><head>

<title>KEN Garment</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<style>
html, body, div, h1, h2, h3, p, blockquote, ul, ol, li, pre { 
margin: 0; padding: 1em; }
li { margin-left: 1.5em }

h1{text-align:center;}
@page {size: a4;}
@media screen { body { margin: 0em }}

@page {
margin: 0cm;
}

body { font: 11pt Georgia, serif }
q::before { content: "\201C"; }
q::after { content: "\201D"; }
q { font-style: italic }
h1 { font-size: 3em; font-family: "san-serif"; padding: 0 0 0.2em }
h2, h3 { font-size: 1.1em; margin: 0.8em 0 0.4em; text-align:center;}
h4{padding: 0;}
p, li { margin: 0.2em 0 0.4em }
ul, ol { margin: 0.2em 0 0.4em 1.5em }
a { text-decoration: none; color: inherit }


address {   font-style: normal; text-align:center; }
aside { float: right; width: 10em }
footer { float: bottom; text-align: center }

body.usd td.currency:before { content: "USD\A0$" }
body.eur td.currency:before { content: "EUR\A0\20AC\A0" }
body.aud td.currency:before { content: "AUD\A0$" }

body.eur tr.usd, body.eur tr.aud { display: none }
body.usd tr.eur, body.usd tr.aud { display: none }
body.aud tr.eur, body.aud tr.usd { display: none }
h5{ text-align:center; }
.lines {
border: 1px solid #000;

}

.button_niks
{
background-color: #a0c715;
border: none;
color: white;
padding: 15px 32px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
margin: 4px 2px;
cursor: pointer;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
}
.ridge {border-style: groove;}

</style>
 <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 2px;
}

th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 2px;
  background-color:##cfcfcf;
}
tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
<body class="usd">
<div id="printsdiv">
<address  >
<h2>Ken Global Design Pvt. Ltd.</h2>
 <h3>OCR Report</h3>

</address>
	 

@if(count($Buyer_Purchase_Order_List)>0)
<b>Sales Order No:</b> {{$Buyer_Purchase_Order_List[0]->tr_code}} <br>
<b>Buyer Name:</b> {{$Buyer_Purchase_Order_List[0]->Ac_name}}<br>
<b>Main Style:</b> {{$Buyer_Purchase_Order_List[0]->mainstyle_name}}<br>
<b>Job Style:</b> {{$Buyer_Purchase_Order_List[0]->fg_name}}<br>
<b>Style No:</b> {{$Buyer_Purchase_Order_List[0]->style_no}}<br>
@php 
$SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $Buyer_Purchase_Order_List[0]->sz_code)->get();
  $Buyer_Purchase_Order_Detail_List = App\Models\BuyerPurchaseOrderDetailModel::join('item_master','item_master.item_code', '=', 'buyer_purchase_order_detail.item_code')
            ->join('color_master','color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
            ->where('buyer_purchase_order_detail.tr_code','=', $Buyer_Purchase_Order_List[0]->tr_code)
            ->get(['buyer_purchase_order_detail.*', 'item_master.item_name','item_master.item_description','item_master.dimension','color_master.color_name' ]);


$no=1; @endphp
  @foreach($Buyer_Purchase_Order_Detail_List as $List)   
     <hr>
              <b>For:</b> {{$List->item_name}} <b>Color:</b>  {{$List->color_name}} 
            <table >
                <thead>
               
                                               <th>Size</th>
                                                 @foreach($SizeDetailList as $sz) 
                                                <th>{{ $sz->size_name }}</th>
                                                @endforeach
                                                <th>Total</th>
                                                 
                                            
                                           
                </thead>
                <tbody>
                         
                <tr>
                                                <td><b>Order Qty</b></td>
                                                
                                                @php 
                                                $SizeQtyList=explode(',', $List->size_qty_array)
                                                @endphp
                                                @php $no=1; @endphp
                                                @foreach($SizeQtyList  as $size_id)
                                                <td>{{ $size_id }}</td>
                                                 
                                                @endforeach
                                                
                                                <td>{{ $List->size_qty_total }}</td>
                                                 
                                         
                                              
                </tr>
                
                
                
                <tr>      @php
                
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
                <td><b>With Allowance Qty</b></td>
                                                
                    @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1 + round($MasterdataList[0]->s_1*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2 + round($MasterdataList[0]->s_2*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3 + round($MasterdataList[0]->s_3*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4 + round($MasterdataList[0]->s_4*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5 + round($MasterdataList[0]->s_5*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6 + round($MasterdataList[0]->s_6*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7 + round($MasterdataList[0]->s_7*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8 + round($MasterdataList[0]->s_8*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9 + round($MasterdataList[0]->s_9*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10 + round($MasterdataList[0]->s_10*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11 + round($MasterdataList[0]->s_11*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12 + round($MasterdataList[0]->s_12*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13 + round($MasterdataList[0]->s_13*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14 + round($MasterdataList[0]->s_14*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15 + round($MasterdataList[0]->s_15*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16 + round($MasterdataList[0]->s_16*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17 + round($MasterdataList[0]->s_17*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18 + round($MasterdataList[0]->s_18*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19 + round($MasterdataList[0]->s_19*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20 + round($MasterdataList[0]->s_20*($MasterdataList[0]->shipment_allowance/100))}}</td> @endif
                    <td>{{ $MasterdataList[0]->size_qty_total + round($MasterdataList[0]->size_qty_total*($MasterdataList[0]->shipment_allowance/100))}}</td>
             </tr>
                
                
                
                
                <tr>      @php
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
                <td><b>W.O. Qty</b></td>
                                                
                    @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                    @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                    @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                    @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                    @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                    @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                    @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                    @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                    @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                    @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                    @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                    @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                    @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                    @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                    @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                    @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                    @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                    @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                    <td>{{ $MasterdataList[0]->size_qty_total }}</td>
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
                                                <td><b>CUT Qty</b></td>
                                                
                                                 
                                                
                                            @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                                            
                                                
                                              
                                                
                                                <td>{{ $MasterdataList[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                
              
                  $List2 = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
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
                  
                  
                  
                 
                $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, 
                 vendor_work_order_size_detail.color_id, color_name, ".$sizess.", 
                 ifnull(sum(size_qty_total),0) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
                 color_master.color_id=vendor_work_order_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."'
                 and vendor_work_order_size_detail.item_code ='".$List->item_code."' and vendor_work_order_size_detail.color_id='".$List->color_id."'
                group by vendor_work_order_size_detail.color_id");
                
                
                // DB::enableQueryLog();
                
                 
                
                
                
                
                
                   //   $query = DB::getQueryLog();
   // $query = end($query);
      //   dd($query);
  
   $Bal=0;
                   
                      if(count($MasterdataList) > 0){
                             if(isset($MasterdataList[0]->s1)) {$s1=0; $s1=((intval($MasterdataList[0]->s1))-(intval($List2[0]->s_1)));  }
                           if(isset($MasterdataList[0]->s2)) { $s2=0;  $s2=((intval($MasterdataList[0]->s2))-(intval($List2[0]->s_2)));}
                           if(isset($MasterdataList[0]->s3)) {$s3=0; $s3=((intval($MasterdataList[0]->s3))-(intval($List2[0]->s_3)));}
                           if(isset($MasterdataList[0]->s4)) {$s4=0; $s4=((intval($MasterdataList[0]->s4))-(intval($List2[0]->s_4)));}
                           if(isset($MasterdataList[0]->s5)) {$s5=0; $s5=((intval($MasterdataList[0]->s5))-(intval($List2[0]->s_5)));}
                           if(isset($MasterdataList[0]->s6)) {$s6=0; $s6=((intval($MasterdataList[0]->s6))-(intval($List2[0]->s_6)));}
                           if(isset($MasterdataList[0]->s7)) {$s7=0; $s7=((intval($MasterdataList[0]->s7))-(intval($List2[0]->s_7)));}
                           if(isset($MasterdataList[0]->s8)) {$s8=0; $s8=((intval($MasterdataList[0]->s8))-(intval($List2[0]->s_8)));}
                           if(isset($MasterdataList[0]->s9)) {$s9=0; $s9=((intval($MasterdataList[0]->s9))-(intval($List2[0]->s_9)));}
                           if(isset($MasterdataList[0]->s10)) {$s10=0; $s10=((intval($MasterdataList[0]->s10))-(intval($List2[0]->s_10)));}
                           if(isset($MasterdataList[0]->s11)) {$s11=0; $s11=((intval($MasterdataList[0]->s11))-(intval($List2[0]->s_11)));}
                           if(isset($MasterdataList[0]->s12)) {$s12=0; $s12=((intval($MasterdataList[0]->s12))-(intval($List2[0]->s_12)));}
                           if(isset($MasterdataList[0]->s13)) {$s13=0; $s13=((intval($MasterdataList[0]->s13))-(intval($List2[0]->s_13)));}
                           if(isset($MasterdataList[0]->s14)) {$s14=0; $s14=((intval($MasterdataList[0]->s14))-(intval($List2[0]->s_14)));}
                           if(isset($MasterdataList[0]->s15)) {$s15=0; $s15=((intval($MasterdataList[0]->s15))-(intval($List2[0]->s_15)));}
                           if(isset($MasterdataList[0]->s16)) {$s16=0; $s16=((intval($MasterdataList[0]->s16))-(intval($List2[0]->s_16)));}
                           if(isset($MasterdataList[0]->s17)) {$s17=0; $s17=((intval($MasterdataList[0]->s17))-(intval($List2[0]->s_17)));}
                           if(isset($MasterdataList[0]->s18)) {$s18=0; $s18=((intval($MasterdataList[0]->s18))-(intval($List2[0]->s_18)));}
                           if(isset($MasterdataList[0]->s19)) {$s19=0; $s19=((intval($MasterdataList[0]->s19))-(intval($List2[0]->s_19)));}
                           if(isset($MasterdataList[0]->s20)) {$s20=0; $s20=((intval($MasterdataList[0]->s20))-(intval($List2[0]->s_20)));}      
                           if(isset($MasterdataList[0]->size_qty_total)){$Bal=0; $Bal=$MasterdataList[0]->size_qty_total-$List2[0]->size_qty_total;}
                           
                           }
                           else
                           {
                           
                           
                           
                        
                         
                           
                           
                           
                            if(isset($MasterdataList->s1)) {$s1=0; $s1=((intval($MasterdataList->s1))-(intval($List2[0]->s_1))); }
                           if(isset($MasterdataList->s2)) { $s2=0;  $s2=((intval($MasterdataList->s2))-(intval($List2[0]->s_2)));}
                           if(isset($MasterdataList->s3)) {$s3=0; $s3=((intval($MasterdataList->s3))-(intval($List2[0]->s_3)));}
                           if(isset($MasterdataList->s4)) {$s4=0; $s4=((intval($MasterdataList->s4))-(intval($List2[0]->s_4)));}
                           if(isset($MasterdataList->s5)) {$s5=0; $s5=((intval($MasterdataList->s5))-(intval($List2[0]->s_5)));}
                           if(isset($MasterdataList->s6)) {$s6=0; $s6=((intval($MasterdataList->s6))-(intval($List2[0]->s_6)));}
                           if(isset($MasterdataList->s7)) {$s7=0; $s7=((intval($MasterdataList->s7))-(intval($List2[0]->s_7)));}
                           if(isset($MasterdataList->s8)) {$s8=0; $s8=((intval($MasterdataList->s8))-(intval($List2[0]->s_8)));}
                           if(isset($MasterdataList->s9)) {$s9=0; $s9=((intval($MasterdataList->s9))-(intval($List2[0]->s_9)));}
                           if(isset($MasterdataList->s10)) {$s10=0; $s10=((intval($MasterdataList->s10))-(intval($List2[0]->s_10)));}
                           if(isset($MasterdataList->s11)) {$s11=0; $s11=((intval($MasterdataList->s11))-(intval($List2[0]->s_11)));}
                           if(isset($MasterdataList->s12)) {$s12=0; $s12=((intval($MasterdataList->s12))-(intval($List2[0]->s_12)));}
                           if(isset($MasterdataList->s13)) {$s13=0; $s13=((intval($MasterdataList->s13))-(intval($List2[0]->s_13)));}
                           if(isset($MasterdataList->s14)) {$s14=0; $s14=((intval($MasterdataList->s14))-(intval($List2[0]->s_14)));}
                           if(isset($MasterdataList->s15)) {$s15=0; $s15=((intval($MasterdataList->s15))-(intval($List2[0]->s_15)));}
                           if(isset($MasterdataList->s16)) {$s16=0; $s16=((intval($MasterdataList->s16))-(intval($List2[0]->s_16)));}
                           if(isset($MasterdataList->s17)) {$s17=0; $s17=((intval($MasterdataList->s17))-(intval($List2[0]->s_17)));}
                           if(isset($MasterdataList->s18)) {$s18=0; $s18=((intval($MasterdataList->s18))-(intval($List2[0]->s_18)));}
                           if(isset($MasterdataList->s19)) {$s19=0; $s19=((intval($MasterdataList->s19))-(intval($List2[0]->s_19)));}
                           if(isset($MasterdataList->s20)) {$s20=0; $s20=((intval($MasterdataList->s20))-(intval($List2[0]->s_20)));}       
                           if(isset($MasterdataList->size_qty_total)){$Bal=0; $Bal=$MasterdataList->size_qty_total - $List2[0]->size_qty_total;}
                           
                           
                           
                           
                           
                           
                           }
                            
                            
                       if(count($MasterdataList)!=0 && count($List2)!=0){
                @endphp      
                 <tr>
                                                <td><b>BAL Qty </b></td>
                                                   
                                            @if(isset($s1)) <td>{{$s1}}</td> @endif
                                            @if(isset($s2)) <td>{{$s2}}</td> @endif
                                            @if(isset($s3)) <td>{{$s3}}</td> @endif
                                            @if(isset($s4)) <td>{{$s4}}</td> @endif
                                            @if(isset($s5)) <td>{{$s5}}</td> @endif
                                            @if(isset($s6)) <td>{{$s6}}</td> @endif
                                            @if(isset($s7)) <td>{{$s7}}</td> @endif
                                            @if(isset($s8)) <td>{{$s8}}</td> @endif
                                            @if(isset($s9)) <td>{{$s9}}</td> @endif
                                            @if(isset($s10)) <td>{{$s10}}</td> @endif
                                            @if(isset($s11)) <td>{{$s11}}</td> @endif
                                            @if(isset($s12)) <td>{{$s12}}</td> @endif
                                            @if(isset($s13)) <td>{{$s13}}</td> @endif
                                            @if(isset($s14)) <td>{{$s14}}</td> @endif
                                            @if(isset($s15)) <td>{{$s15}}</td> @endif
                                            @if(isset($s16)) <td>{{$s16}}</td> @endif 
                                            @if(isset($s17)) <td>{{$s17}}</td> @endif 
                                            @if(isset($s18)) <td>{{$s18}}</td> @endif
                                            @if(isset($s19)) <td>{{$s19}}</td> @endif 
                                            @if(isset($s20)) <td>{{$s20}}</td> @endif
                                            <td>{{ $Bal }}</td>
                                                 
                                         
                                              
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
                                    <td><b>PROD Qty</b></td>
                                                
                                                 
                                                
                                            @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                                            
                                                
                                              
                                                
                                                <td>{{ $MasterdataList[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                
              
               $MasterdataList    = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
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
                      $List2    = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizess.", 
                  ifnull(sum(size_qty_total),0) as size_qty_total  from stitching_inhouse_size_detail inner join color_master on 
                  color_master.color_id=stitching_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                  stitching_inhouse_size_detail.item_code ='".$List->item_code."'
                   and stitching_inhouse_size_detail.color_id='".$List->color_id."'
                  group by stitching_inhouse_size_detail.color_id");
                
                   
                   
                   if(count($MasterdataList) > 0 ){
                      
                      
                        if(isset($MasterdataList[0]->s1)) {$s1=0; $s1=((intval($MasterdataList[0]->s1))-(intval($List2[0]->s_1)));  }
                           if(isset($MasterdataList[0]->s2)) { $s2=0;  $s2=((intval($MasterdataList[0]->s2))-(intval($List2[0]->s_2)));}
                           if(isset($MasterdataList[0]->s3)) {$s3=0; $s3=((intval($MasterdataList[0]->s3))-(intval($List2[0]->s_3)));}
                           if(isset($MasterdataList[0]->s4)) {$s4=0; $s4=((intval($MasterdataList[0]->s4))-(intval($List2[0]->s_4)));}
                           if(isset($MasterdataList[0]->s5)) {$s5=0; $s5=((intval($MasterdataList[0]->s5))-(intval($List2[0]->s_5)));}
                           if(isset($MasterdataList[0]->s6)) {$s6=0; $s6=((intval($MasterdataList[0]->s6))-(intval($List2[0]->s_6)));}
                           if(isset($MasterdataList[0]->s7)) {$s7=0; $s7=((intval($MasterdataList[0]->s7))-(intval($List2[0]->s_7)));}
                           if(isset($MasterdataList[0]->s8)) {$s8=0; $s8=((intval($MasterdataList[0]->s8))-(intval($List2[0]->s_8)));}
                           if(isset($MasterdataList[0]->s9)) {$s9=0; $s9=((intval($MasterdataList[0]->s9))-(intval($List2[0]->s_9)));}
                           if(isset($MasterdataList[0]->s10)) {$s10=0; $s10=((intval($MasterdataList[0]->s10))-(intval($List2[0]->s_10)));}
                           if(isset($MasterdataList[0]->s11)) {$s11=0; $s11=((intval($MasterdataList[0]->s11))-(intval($List2[0]->s_11)));}
                           if(isset($MasterdataList[0]->s12)) {$s12=0; $s12=((intval($MasterdataList[0]->s12))-(intval($List2[0]->s_12)));}
                           if(isset($MasterdataList[0]->s13)) {$s13=0; $s13=((intval($MasterdataList[0]->s13))-(intval($List2[0]->s_13)));}
                           if(isset($MasterdataList[0]->s14)) {$s14=0; $s14=((intval($MasterdataList[0]->s14))-(intval($List2[0]->s_14)));}
                           if(isset($MasterdataList[0]->s15)) {$s15=0; $s15=((intval($MasterdataList[0]->s15))-(intval($List2[0]->s_15)));}
                           if(isset($MasterdataList[0]->s16)) {$s16=0; $s16=((intval($MasterdataList[0]->s16))-(intval($List2[0]->s_16)));}
                           if(isset($MasterdataList[0]->s17)) {$s17=0; $s17=((intval($MasterdataList[0]->s17))-(intval($List2[0]->s_17)));}
                           if(isset($MasterdataList[0]->s18)) {$s18=0; $s18=((intval($MasterdataList[0]->s18))-(intval($List2[0]->s_18)));}
                           if(isset($MasterdataList[0]->s19)) {$s19=0; $s19=((intval($MasterdataList[0]->s19))-(intval($List2[0]->s_19)));}
                           if(isset($MasterdataList[0]->s20)) {$s20=0; $s20=((intval($MasterdataList[0]->s20))-(intval($List2[0]->s_20)));}               
                      
                      
                                  
                      
                         
                         }
                         else
                         {
                         
                         
                         if(isset($MasterdataList->s1)) {$s1=0; $s1=((intval($MasterdataList->s1))-(intval($List2[0]->s_1)));  }
                           if(isset($MasterdataList->s2)) { $s2=0;  $s2=((intval($MasterdataList->s2))-(intval($List2[0]->s_2)));}
                           if(isset($MasterdataList->s3)) {$s3=0; $s3=((intval($MasterdataList->s3))-(intval($List2[0]->s_3)));}
                           if(isset($MasterdataList->s4)) {$s4=0; $s4=((intval($MasterdataList->s4))-(intval($List2[0]->s_4)));}
                           if(isset($MasterdataList->s5)) {$s5=0; $s5=((intval($MasterdataList->s5))-(intval($List2[0]->s_5)));}
                           if(isset($MasterdataList->s6)) {$s6=0; $s6=((intval($MasterdataList->s6))-(intval($List2[0]->s_6)));}
                           if(isset($MasterdataList->s7)) {$s7=0; $s7=((intval($MasterdataList->s7))-(intval($List2[0]->s_7)));}
                           if(isset($MasterdataList->s8)) {$s8=0; $s8=((intval($MasterdataList->s8))-(intval($List2[0]->s_8)));}
                           if(isset($MasterdataList->s9)) {$s9=0; $s9=((intval($MasterdataList->s9))-(intval($List2[0]->s_9)));}
                           if(isset($MasterdataList->s10)) {$s10=0; $s10=((intval($MasterdataList->s10))-(intval($List2[0]->s_10)));}
                           if(isset($MasterdataList->s11)) {$s11=0; $s11=((intval($MasterdataList->s11))-(intval($List2[0]->s_11)));}
                           if(isset($MasterdataList->s12)) {$s12=0; $s12=((intval($MasterdataList->s12))-(intval($List2[0]->s_12)));}
                           if(isset($MasterdataList->s13)) {$s13=0; $s13=((intval($MasterdataList->s13))-(intval($List2[0]->s_13)));}
                           if(isset($MasterdataList->s14)) {$s14=0; $s14=((intval($MasterdataList->s14))-(intval($List2[0]->s_14)));}
                           if(isset($MasterdataList->s15)) {$s15=0; $s15=((intval($MasterdataList->s15))-(intval($List2[0]->s_15)));}
                           if(isset($MasterdataList->s16)) {$s16=0; $s16=((intval($MasterdataList->s16))-(intval($List2[0]->s_16)));}
                           if(isset($MasterdataList->s17)) {$s17=0; $s17=((intval($MasterdataList->s17))-(intval($List2[0]->s_17)));}
                           if(isset($MasterdataList->s18)) {$s18=0; $s18=((intval($MasterdataList->s18))-(intval($List2[0]->s_18)));}
                           if(isset($MasterdataList->s19)) {$s19=0; $s19=((intval($MasterdataList->s19))-(intval($List2[0]->s_19)));}
                           if(isset($MasterdataList->s20)) {$s20=0; $s20=((intval($MasterdataList->s20))-(intval($List2[0]->s_20)));}   
                         
                      
                      }
                      
                      
                      
                            if(count($MasterdataList)!=0 && count($List2)!=0){
                @endphp      
                 <tr>
                                                <td><b>BAL Qty </b> </td>
                                                   
                                            @if(isset($s1)) <td>{{$s1}}</td> @endif
                                            @if(isset($s2)) <td>{{$s2}}</td> @endif
                                            @if(isset($s3)) <td>{{$s3}}</td> @endif
                                            @if(isset($s4)) <td>{{$s4}}</td> @endif
                                            @if(isset($s5)) <td>{{$s5}}</td> @endif
                                            @if(isset($s6)) <td>{{$s6}}</td> @endif
                                            @if(isset($s7)) <td>{{$s7}}</td> @endif
                                            @if(isset($s8)) <td>{{$s8}}</td> @endif
                                            @if(isset($s9)) <td>{{$s9}}</td> @endif
                                            @if(isset($s10)) <td>{{$s10}}</td> @endif
                                            @if(isset($s11)) <td>{{$s11}}</td> @endif
                                            @if(isset($s12)) <td>{{$s12}}</td> @endif
                                            @if(isset($s13)) <td>{{$s13}}</td> @endif
                                            @if(isset($s14)) <td>{{$s14}}</td> @endif
                                            @if(isset($s15)) <td>{{$s15}}</td> @endif
                                            @if(isset($s16)) <td>{{$s16}}</td> @endif 
                                            @if(isset($s17)) <td>{{$s17}}</td> @endif 
                                            @if(isset($s18)) <td>{{$s18}}</td> @endif
                                            @if(isset($s19)) <td>{{$s19}}</td> @endif 
                                            @if(isset($s20)) <td>{{$s20}}</td> @endif
                                            <td>{{ $MasterdataList[0]->size_qty_total-$List2[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                                    <td><b>QC Passed Qty</b></td>
                                                
                                                 
                                                
                                            @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                                            
                                                
                                              
                                                
                                                <td>{{ $MasterdataList[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                
                
                  $MasterdataList = DB::select("SELECT qcstitching_inhouse_size_reject_detail.color_id, color_name, ".$sizex.", 
                  ifnull(sum(size_qty_total),0) as size_qty_total  from qcstitching_inhouse_size_reject_detail inner join color_master on 
                  color_master.color_id=qcstitching_inhouse_size_reject_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                  qcstitching_inhouse_size_reject_detail.item_code ='".$List->item_code."'
                  and qcstitching_inhouse_size_reject_detail.color_id='".$List->color_id."'
                  group by qcstitching_inhouse_size_reject_detail.color_id");
                        if(count($MasterdataList)!=0){
                @endphp      
                 <tr>
                                    <td><b>QC Rejected Qty</b></td>
                                                
                                                 
                                                
                                            @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                                            
                                                
                                              
                                                
                                                <td>{{ $MasterdataList[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                                    <td><b>PACK Qty</b></td>
                                                
                                                 
                                                
                                            @if(isset($MasterdataList[0]->s_1)) <td>{{$MasterdataList[0]->s_1}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_2)) <td>{{$MasterdataList[0]->s_2}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_3)) <td>{{$MasterdataList[0]->s_3}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_4)) <td>{{$MasterdataList[0]->s_4}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_5)) <td>{{$MasterdataList[0]->s_5}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_6)) <td>{{$MasterdataList[0]->s_6}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_7)) <td>{{$MasterdataList[0]->s_7}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_8)) <td>{{$MasterdataList[0]->s_8}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_9)) <td>{{$MasterdataList[0]->s_9}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_10)) <td>{{$MasterdataList[0]->s_10}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_11)) <td>{{$MasterdataList[0]->s_11}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_12)) <td>{{$MasterdataList[0]->s_12}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_13)) <td>{{$MasterdataList[0]->s_13}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_14)) <td>{{$MasterdataList[0]->s_14}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_15)) <td>{{$MasterdataList[0]->s_15}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_16)) <td>{{$MasterdataList[0]->s_16}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_17)) <td>{{$MasterdataList[0]->s_17}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_18)) <td>{{$MasterdataList[0]->s_18}}</td> @endif
                                            @if(isset($MasterdataList[0]->s_19)) <td>{{$MasterdataList[0]->s_19}}</td> @endif 
                                            @if(isset($MasterdataList[0]->s_20)) <td>{{$MasterdataList[0]->s_20}}</td> @endif
                                            
                                                
                                              
                                                
                                                <td>{{ $MasterdataList[0]->size_qty_total }}</td>
                                                 
                                         
                                              
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
                
              
                  $List2 = DB::select("SELECT cut_panel_grn_size_detail.color_id, color_name, ".$sizex.", 
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
                        
                         $MasterdataList = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizess.", 
                  ifnull(sum(size_qty_total),0) as size_qty_total  from packing_inhouse_size_detail inner join color_master on 
                  color_master.color_id=packing_inhouse_size_detail.color_id where sales_order_no='".$Buyer_Purchase_Order_List[0]->tr_code."' and 
                  packing_inhouse_size_detail.item_code ='".$List->item_code."'
                   and packing_inhouse_size_detail.color_id='".$List->color_id."'
                  group by packing_inhouse_size_detail.color_id");
                  
                   
                   
                   
                      
                         
                           if(isset($MasterdataList[0]->s1)) {$s1=0; $s1=((intval($MasterdataList[0]->s1))-(intval($List2[0]->s_1))); echo 'S1:'.$s1;}
                           if(isset($MasterdataList[0]->s2)) { $s2=0;  $s2=((intval($MasterdataList[0]->s2))-(intval($List2[0]->s_2)));}
                           if(isset($MasterdataList[0]->s3)) {$s3=0; $s3=((intval($MasterdataList[0]->s3))-(intval($List2[0]->s_3)));}
                           if(isset($MasterdataList[0]->s4)) {$s4=0; $s4=((intval($MasterdataList[0]->s4))-(intval($List2[0]->s_4)));}
                           if(isset($MasterdataList[0]->s5)) {$s5=0; $s5=((intval($MasterdataList[0]->s5))-(intval($List2[0]->s_5)));}
                           if(isset($MasterdataList[0]->s6)) {$s6=0; $s6=((intval($MasterdataList[0]->s6))-(intval($List2[0]->s_6)));}
                           if(isset($MasterdataList[0]->s7)) {$s7=0; $s7=((intval($MasterdataList[0]->s7))-(intval($List2[0]->s_7)));}
                           if(isset($MasterdataList[0]->s8)) {$s8=0; $s8=((intval($MasterdataList[0]->s8))-(intval($List2[0]->s_8)));}
                           if(isset($MasterdataList[0]->s9)) {$s9=0; $s9=((intval($MasterdataList[0]->s9))-(intval($List2[0]->s_9)));}
                           if(isset($MasterdataList[0]->s10)) {$s10=0; $s10=((intval($MasterdataList[0]->s10))-(intval($List2[0]->s_10)));}
                           if(isset($MasterdataList[0]->s11)) {$s11=0; $s11=((intval($MasterdataList[0]->s11))-(intval($List2[0]->s_11)));}
                           if(isset($MasterdataList[0]->s12)) {$s12=0; $s12=((intval($MasterdataList[0]->s12))-(intval($List2[0]->s_12)));}
                           if(isset($MasterdataList[0]->s13)) {$s13=0; $s13=((intval($MasterdataList[0]->s13))-(intval($List2[0]->s_13)));}
                           if(isset($MasterdataList[0]->s14)) {$s14=0; $s14=((intval($MasterdataList[0]->s14))-(intval($List2[0]->s_14)));}
                           if(isset($MasterdataList[0]->s15)) {$s15=0; $s15=((intval($MasterdataList[0]->s15))-(intval($List2[0]->s_15)));}
                           if(isset($MasterdataList[0]->s16)) {$s16=0; $s16=((intval($MasterdataList[0]->s16))-(intval($List2[0]->s_16)));}
                           if(isset($MasterdataList[0]->s17)) {$s17=0; $s17=((intval($MasterdataList[0]->s17))-(intval($List2[0]->s_17)));}
                           if(isset($MasterdataList[0]->s18)) {$s18=0; $s18=((intval($MasterdataList[0]->s18))-(intval($List2[0]->s_18)));}
                           if(isset($MasterdataList[0]->s19)) {$s19=0; $s19=((intval($MasterdataList[0]->s19))-(intval($List2[0]->s_19)));}
                           if(isset($MasterdataList[0]->s20)) {$s20=0; $s20=((intval($MasterdataList[0]->s20))-(intval($List2[0]->s_20)));}               
                      
                            if(count($MasterdataList)!=0 && count($List2)!=0){
                      
                @endphp      
                 <tr>
                                                <td> <b>BAL Qty</b>  </td>
                                                   
                                            @if(isset($s1)) <td>{{$s1}}</td> @endif
                                            @if(isset($s2)) <td>{{$s2}}</td> @endif
                                            @if(isset($s3)) <td>{{$s3}}</td> @endif
                                            @if(isset($s4)) <td>{{$s4}}</td> @endif
                                            @if(isset($s5)) <td>{{$s5}}</td> @endif
                                            @if(isset($s6)) <td>{{$s6}}</td> @endif
                                            @if(isset($s7)) <td>{{$s7}}</td> @endif
                                            @if(isset($s8)) <td>{{$s8}}</td> @endif
                                            @if(isset($s9)) <td>{{$s9}}</td> @endif
                                            @if(isset($s10)) <td>{{$s10}}</td> @endif
                                            @if(isset($s11)) <td>{{$s11}}</td> @endif
                                            @if(isset($s12)) <td>{{$s12}}</td> @endif
                                            @if(isset($s13)) <td>{{$s13}}</td> @endif
                                            @if(isset($s14)) <td>{{$s14}}</td> @endif
                                            @if(isset($s15)) <td>{{$s15}}</td> @endif
                                            @if(isset($s16)) <td>{{$s16}}</td> @endif 
                                            @if(isset($s17)) <td>{{$s17}}</td> @endif 
                                            @if(isset($s18)) <td>{{$s18}}</td> @endif
                                            @if(isset($s19)) <td>{{$s19}}</td> @endif 
                                            @if(isset($s20)) <td>{{$s20}}</td> @endif
                                            <td>{{ $MasterdataList[0]->size_qty_total-$List2[0]->size_qty_total }}</td>
                                                 
                                         
                                              
                </tr>
            
 
                       @php } @endphp
            
            
            
            
                </tbody>
            </table>
      

      
@endforeach    
@else

<center><h4 style="font-weight:bold;">Record Not Found</h4></center>

@endif

<p><small>Print Date </small>

</p><footer><a href="#">	 Developed By Seaquid Technology (I) Pvt. Ltd. </a></footer>

</div>

<script>

/*function printData() {
var divToPrint = document.getElementById('printsdiv');
var htmlToPrint = '' +
'<style type="text/css"> h2,address{text-align:center;}' +
'table {'+
'font-family: arial, sans-serif;'+
' border-collapse: collapse;'+
' width: 100%;'+
'}'+

'td, th {'+
'border: 1px solid #dddddd;'+
'text-align: left;'+
'padding: 8px;'+
'}'+

'tr:nth-child(even) {'+
'background-color: #dddddd;'+
'}'+
'</style>';
htmlToPrint += divToPrint.outerHTML;
newWin = window.open("");
newWin.document.write(htmlToPrint);
newWin.print();
newWin.close();
}*/


document.getElementById("doPrint").addEventListener("click", function() {
var printContents = document.getElementById('printsdiv').innerHTML;
var originalContents = document.body.innerHTML;
document.body.innerHTML = printContents;
window.print();
document.body.innerHTML = originalContents;
});



</script>


</body>
</html>