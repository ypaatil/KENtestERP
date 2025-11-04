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
<center><h4 class="mb-0">{{ $title }}</h4></center>
<!-- Item Details -->




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
    <div class="row" style="border: 1px solid;">
    <div  class="col-md-4" style="border: 1px solid; padding-top:5px; text-align:center;"   >
        <p><img src="../logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>   
        </div>    
        <div  class="col-md-4" style="border: 1px solid;"  >
          <p><b>Bill From:</b></p>
      <b>KEN Global Designs Pvt. Ltd.</b></br>
       GAT NO 298/299,A/P Kondigre, </br> Kolhapur, Maharashtra, 416101
        
      GSTIN No: 27ABCCS7591Q1ZD</br>
    State Code/ Name : 27 - Maharastra    

        </div>
         <div  class="col-md-4" style="border: 1px solid;"  ><p><b>Fabric PO No:  </b>    {{ $poMaster[0]->pur_code }}</p>
      <b>PO Date: </b>  {{ date('d-m-Y',strtotime($poMaster[0]->pur_date)) }}</br>
      <b>Supplier Ref :</b>  {{ $poMaster[0]->supplierRef }}</br>
      <b>PO Type:</b>   {{ $poMaster[0]->po_type_name }}</br>
   
 @if($poMaster[0]->approveFlag==0) 
  
     <b>Purchase Order :</b>  Pending for Appoval 
    
    @elseif($poMaster[0]->approveFlag==1) 
     <b> Purchase Order :</b>  Approved 
        
    
     @elseif($poMaster[0]->approveFlag==2)
     
     <b> Purchase Order :</b>  Disappoved 
  
   @endif


</div>
    </div>
 <div class="row" style="border: 1px solid;">
      <div  class="col-md-4"  style="border: 1px solid;" > &nbsp;</div>
    <div  class="col-md-4"  style="border: 1px solid;">
        <b>To:</b> </br>
      <b>{{ $poMaster[0]->ac_name1 }}</b> </br>
      {{ $poMaster[0]->address }}</br>
   <b> GSTIN No:</b> {{ $poMaster[0]->gst_no }}</br>
 <b> PAN NO:</b> {{ $poMaster[0]->pan_no }}    

        </div>
         <div  class="col-md-4" style="border: 1px solid;"  >
       <b> Buyer Delivery Address :</b></br>   
         <b>KEN GLOBAL DESIGNS PRIVATE LIMITED</b></br>    
        {{ $poMaster[0]->deliveryAddress }}</br>
 <b>Delivery Date :</b> {{ date('d-m-Y',strtotime($poMaster[0]->delivery_date)) }} 


        </div>
    </div>
</div>
<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;">We would like to place and confirm you following below order</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
<thead>
<tr  style="background-color:#eee; text-align:center;">
<th>PRODUCT DESCRIPTION</th>
<th>HSN/SAC Code</th>
<th>UOM</th>
<th>Qty</th>
<th>Rate</th>
<th>Amount</th>
<th>Total Amount</th>
</tr>
</thead>
<tbody>
   
@php 

 $detailpurchase = App\Models\PurchaseOrderDetailModel::join('item_master','item_master.item_code', '=', 'purchaseorder_detail.item_code')
 
  ->join('unit_master', 'unit_master.unit_id', '=', 'purchaseorder_detail.unit_id')    
  ->where('pur_code','=', $poMaster[0]->pur_code)->get(['purchaseorder_detail.*','item_master.item_name','unit_master.unit_name', 'item_master.item_image_path','item_master.item_description','item_master.dimension','item_master.color_name']);


$no=1; 

$freight_hsn=0;
$freight_amt=0;
$pur_cgst=0;
$camt=0;
$pur_sgst=0;
$samt=0;
$pur_igst=0;
$iamt=0;
@endphp


@foreach($detailpurchase as $rowDetail)  

<tr>
<td>
    <div class="row">
<div class="col-md-8"><b>Fabric Code:</b> {{ $rowDetail->item_name }} </br>
<b>Quality:</b> {{ $rowDetail->item_description }},</br>
<b>Color:</b> {{ $rowDetail->color_name }},</br>
<b>Width:</b> {{ $rowDetail->dimension }} 
</div>
 <div class="col-md-4">
     
<img src="../images/{{ $rowDetail->item_image_path }}" width="70" height="70"></td>     
     </div>
  </div>


<td>{{ $rowDetail->hsn_code }}</td>
<td>{{ $rowDetail->unit_name }}</td>
<td>{{ number_format((float)$rowDetail->item_qty, 2, '.', '')}}</td>
<td>{{ number_format($rowDetail->item_rate) }}</td>
<td style="text-align:center;">{{ number_format($rowDetail->amount) }}</td>
<td style="text-align:center;">{{ number_format($rowDetail->total_amount) }}</td>
</tr>

@php 
 $freight_hsn=$rowDetail->freight_hsn;
 $freight_amt=$rowDetail->freight_amt;

$pur_cgst=$rowDetail->pur_cgst + $pur_cgst;
$camt=$rowDetail->camt + $camt;
$pur_sgst=$rowDetail->pur_sgst + $pur_sgst;
$samt=$rowDetail->samt + $samt;
$pur_igst=$rowDetail->pur_igst + $pur_igst;
$iamt=$rowDetail->iamt + $iamt;


$no=$no+1; @endphp
  @endforeach


@php

$number =  round($poMaster[0]->Net_amount);
   $no = $number;
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';

 @endphp


</tbody>
</table>

 
<table class="table table-bordered text-1 table-sm" >
    <tbody>
<tr>
<td colspan="1" ></td>
<td colspan="1" >Freight HSN</td>
<td class="font-weight-600">{{  $freight_hsn }}</td>
<td colspan="1" class="font-weight-600" style="text-align:center;">Freight Amount</td>
<td style="text-align:right;"> {{ number_format($freight_amt) }} </td>
<td colspan="1" class="font-weight-600">Sub Total</td>

<td style="text-align:right;" class="font-weight-600">{{ number_format($poMaster[0]->Gross_amount) }}</td>

</tr>


<tr>
<!--<td colspan="1" class="font-weight-600">Tax Rate: 5%</td>-->
<!--<td colspan="1" class="font-weight-600">Sales Amount</td>-->

<td colspan="1" class="font-weight-600"></td>
<td colspan="1" class="font-weight-600">Tax Values</td>
<td colspan="1" class="font-weight-600">CGST @ 2.5%: {{ number_format($camt) }}</td>

<td colspan="1" class="font-weight-600">SGST @ 2.5%: {{ number_format($samt) }}</td>

<td colspan="1" class="font-weight-600">IGST @ 5%: {{ number_format($iamt) }}</td>

<td colspan="1" class="font-weight-600">Total Tax</td>
<td style="text-align:right;" class="font-weight-600">{{ number_format($poMaster[0]->Gst_amount) }}</td>
</tr>


<tr>
<td colspan="5" style="text-transform: uppercase;"><span class="font-weight-600">Amount In Rupees: </span>{{ $result . "Rupees  Only"; }}</td>  

<td colspan="1" class="font-weight-600">Round Off</td>
<td style="text-align:right;" class="font-weight-600">{{ number_format((float)($poMaster[0]->Net_amount - $poMaster[0]->Gross_amount - $poMaster[0]->Gst_amount), 2, '.', '')}}

</td>
</tr>



<tr>
<!--<td colspan="1" class="font-weight-600">Tax Rate: 5%</td>-->
<!--<td colspan="1" class="font-weight-600">Sales Amount</td>-->
<td colspan="5" >Remark: {{ $poMaster[0]->narration }}</td>
 
<td colspan="1" class="font-weight-600">Net Amount</td>
<td style="text-align:right;" class="font-weight-600">&#8377;{{ number_format($poMaster[0]->Net_amount) }}/-</td>
</tr>


</tbody>
</table>
  
   <div class="row">
    <!-- Fare Details -->
<div class="col-md-9">
<h4 class="text-4 mt-2">AGREED TERMS & CONDITIONS</h4>
 @php  echo  htmlspecialchars_decode($poMaster[0]->terms_and_conditions); @endphp
 </div>
<div class="col-md-3">
     <br/>
     <br/>
   
    
    </div>
    </div>

<!-- Footer -->
<footer  >
 
<!--<div class="">-->
<!--<table class="table border" style="width:100%;border:#000 1px solid;padding: 0px;margin-bottom: 4px;overflow: hidden;">-->
<!--<thead>-->
<!--  <tr>-->
<!--  <th>-->
<!--For Purchase and Delivery related queries contact :</th>-->
<!--  <td>Mr. Guruprasad Mane (General Manager): . 91 9970028707,<b> Email :</b> admin@rufsangli.com</td>-->
<!--</tr>-->
<!--    <tr>-->
<!--  <th>-->
<!--For Payment and Other related queries contact:</th>-->
<!--  <td>Mr. Mahesh Jadhav (Account And Finance Department),<b> Email :</b> madhavnagr@rufsangli.com</td>-->
<!--</tr>-->
<!--</thead>-->
<!--<tbody>-->

<!--</tbody>-->
<!--</table>-->
<!--</div>-->


<div class="">
<table class="table table-bordered text-1 table-sm">
<tbody>
<tr>
<td rowspan="2"><span class="font-weight-600">COMPANY SEAL :</span></td>
<td ><span class="font-weight-600">For KEN GLOBAL DESIGNS PRIVATE LIMITED</span>
<p class="mt-4" style="margin-bottom: 0px;">Authorized Signature</p>
</td>
</tr>
<tr>
<td ><span class="font-weight-600">SUBJECT TO ICHALKARANJI JURISDICTION</span>

</td>
</tr>

</tbody>
</table>
</div>

<div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
</footer>
</div>
</main>
</div>
</div>
 
<p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Purchase Order</p>
<p class="text-center d-print-none"><a href="/PurchaseOrder">&laquo; Back to List</a></p>
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