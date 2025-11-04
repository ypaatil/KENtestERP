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
<h4 class="mb-0" style="font-weight:bold; text-center">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
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
     background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
     
}



</style>

<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
         <b style="display: inline-block;text-align: left;" class="mt-1">PO No:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsInwardMaster[0]->po_code }}</span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">GRN No:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsInwardMaster[0]->trimCode }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">GRN Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsInwardMaster[0]->trimDate }} </span></br>
       
      
     
      
 
</div> <div  class="col-md-3" >
   
        </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Invoice No :</b>  <span style="display: inline-block;text-align: right;">{{ $TrimsInwardMaster[0]->invoice_no }} </span></br>
    <b style="display: inline-block;text-align: left;" class="mt-1">Supplier: </b>  <span style="display: inline-block;text-align: right;">{{  $TrimsInwardMaster[0]->Ac_name }} </span></br>
      
       <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: left;">{{  $TrimsInwardMaster[0]->address }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsInwardMaster[0]->gst_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsInwardMaster[0]->pan_no }}</span></br>
    </div>
   
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Goods Receipt Note</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
 
<th rowspan="2">Qty</th>
<th rowspan="2">UOM</th>
 <th rowspan="2">Rate</th>
 <th rowspan="2">Amount</th>
   <th rowspan="2">CGST </th>
   <th rowspan="2">SGST</th>
    <th rowspan="2">IGST</th>
     <th rowspan="2">Total Amount</th>
 
</tr>

</thead>
<tbody>
@php 

  $TrimsInwardDetailstables = App\Models\TrimsInwardDetailModel::select('trimsInwardDetail.item_rate','unit_master.unit_name','item_master.color_name','item_master.item_name','unit_name',
  'item_master.cgst_per','item_master.sgst_per', 'item_master.igst_per', 
  'trimsInwardDetail.item_code','item_master.item_description',
  'item_master.dimension', DB::raw('sum(trimsInwardDetail.item_qty) as item_qty'))
  ->join('item_master','item_master.item_code', '=', 'trimsInwardDetail.item_code')
    ->join('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')         
            ->where('trimsInwardDetail.trimCode','=', $TrimsInwardMaster[0]->trimCode)
            ->groupby('trimsInwardDetail.item_code')
            ->get();


 $tax_type_id=0;
 
 if($TrimsInwardMaster[0]->is_opening!=1)
 {
    $GSTType=DB::table('purchase_order')->select('tax_type_id')->where('pur_code',$TrimsInwardMaster[0]->po_code)->first();
    $tax_type_id=$GSTType->tax_type_id;
    
 }
 else
 {
    $tax_type_id=1;
 }
$no=1; $amt=0; $tamt=0;$GST=0;
$CGST=0;
$SGST=0;
$IGST=0;
$TotalAmount=0;
$Amount=0;
 @endphp
@foreach($TrimsInwardDetailstables as $rowDetail) 

@php
$Amount=round(($rowDetail->item_rate*$rowDetail->item_qty),2);
if($tax_type_id==1){
$CGST=round((($Amount)*($rowDetail->cgst_per/100)),2);
$SGST=round((($Amount)*($rowDetail->sgst_per/100)),2);
$IGST=0;
$TotalAmount=$Amount+$CGST+$SGST;
}
else
{

$CSGT=0;
$SGST=0;
$IGST=round((($Amount)*($rowDetail->igst_per/100)),2);
$TotalAmount=$Amount+$IGST;
}

@endphp



<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_code }} </td>
<td>  {{ $rowDetail->item_name }}</td>
 
<td style="text-align:right;">{{ $rowDetail->item_qty }}</td>
<td>{{ $rowDetail->unit_name }}</td>
 <td style="text-align:right;">{{ $rowDetail->item_rate }}</td>
    <td>{{ money_format('%!i',$Amount)}}</td>
 @if($tax_type_id==1)
  <td style="text-align:right;">{{money_format('%!i',$CGST)}} <br> ({{$rowDetail->cgst_per}}%)</td>
  <td style="text-align:right;">{{money_format('%!i',$SGST)}}<br> ({{$rowDetail->sgst_per}}%)</td>
 <td>0</td>
 <td style="text-align:right;">{{money_format('%!i',$TotalAmount)}}</td>
 @else
 <td>0</td>
 <td>0</td>
   <td style="text-align:right;">{{money_format('%!i',$IGST)}}<br> ({{$rowDetail->igst_per}}%)</td>
   <td style="text-align:right;">{{money_format('%!i',$TotalAmount)}}</td>
 @endif
  
</tr>

@php $no=$no+1;
$amt=$amt+round(($rowDetail->item_rate*$rowDetail->item_qty),2);
$tamt=$tamt+$TotalAmount;
$GST=$GST+$CGST+$SGST+$IGST;
@endphp
  @endforeach
<tfoot>
<tr style="font-weight:bold; text-align:right">
 <td ></td>    <td ></td> 
<td>  <b>Total Qty:   </b></td>
<td  ><b> {{ money_format('%!i',$TrimsInwardMaster[0]->totalqty) }}  </b></td>
 <td ></td> 
 <td style="font-weight:bold;">Gross Amount</td>
<td style="font-weight:bold;">{{ money_format('%!i',$amt) }}</td>
<td colspan="3"><b>    </b></td>
<td colspan="3"><b> {{money_format('%!i',$tamt)}}   </b></td>

</tr>    

@php
 $number =   ($tamt);
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

<tr>

<td colspan="13" style="text-transform:uppercase;">  <b>Amount (INR):    {{ $result }}  </b></td>
 
  

</tr>
</tfoot>
</tbody>
</tbody>
</table>

<table style="float:right; " >
    <tbody>
        <tr>
            <th>Gross Amount :</th>
            <th>{{money_format('%!i',($amt))}}</th>
        </tr>
        <tr>
            <th>GST :</th>
            <th>{{money_format('%!i',($GST))}}</th>
        </tr>
       
        <tr>
      <th>Total Amount : </th> 
            <th> &#8377;{{money_format('%!i',$tamt)}}</th>
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
 

<p class="text-center d-print-none"><a href="/TrimsInward">&laquo; Back to List</a></p>
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