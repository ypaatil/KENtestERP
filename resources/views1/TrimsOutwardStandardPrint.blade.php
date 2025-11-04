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

</div>
</div>



<hr>

<div class="">
    <h4 class="text-4"><h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Gate Pass/ Delivery Note</h4></h4>
    
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
if($TrimsOutwardMaster[0]->trim_type==2)
{
    $SalesOrder=DB::select("select sales_order_no from vendor_purchase_order_master where vpo_code='".$TrimsOutwardMaster[0]->vpo_code."'");
 }
 else
  {
    $SalesOrder=DB::select("select sales_order_no from vendor_work_order_master where vw_code='".$TrimsOutwardMaster[0]->vw_code."'");
    }
 @endphp





<div id="printInvoice">
<div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsOutwardMaster[0]->trimOutCode }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsOutwardMaster[0]->tout_date }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Work Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsOutwardMaster[0]->vw_code }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Process Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $TrimsOutwardMaster[0]->vpo_code }} </span></br>
      </div>
    <div  class="col-md-3" >
    </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $TrimsOutwardMaster[0]->Ac_name }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order No:</b>  <span style="display: inline-block;text-align: right;">{{  $SalesOrder[0]->sales_order_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsOutwardMaster[0]->address }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsOutwardMaster[0]->gst_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $TrimsOutwardMaster[0]->pan_no }}</span></br>
    </div>
</div>
</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trims Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Classification</th>
<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
<th rowspan="2">HSN Code</th>
<th rowspan="2">Qty</th>
<th rowspan="2">UOM</th>
<th rowspan="2">Rate</th>
<th rowspan="2">Amount</th>
<th rowspan="2">Total Amount</th> 


</tr>

</thead>
<tbody>
@php 
$total_amt=0;$igst=0;
$totalcgst=0;
$totalsgst=0;
$totaligst=0;
$totalqty=0;
$trimsOutwardDetailstables = App\Models\TrimsOutwardDetailModel::
select('trimsOutwardDetail.item_rate','item_master.color_name','classification_master.class_name','item_master.item_name','unit_name','trimsOutwardDetail.item_code','item_master.item_description','item_master.cgst_per','item_master.sgst_per','item_master.igst_per',
'item_master.hsn_code','item_master.dimension', DB::raw('sum(trimsOutwardDetail.item_qty) as item_qty'))
->join('item_master','item_master.item_code', '=', 'trimsOutwardDetail.item_code')
->join('classification_master','classification_master.class_id', '=', 'item_master.class_id')
->join('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')
->where('trimsOutwardDetail.trimOutCode','=', $TrimsOutwardMaster[0]->trimOutCode)
->groupby('trimsOutwardDetail.item_code')
->get();

$no=1; $amt=0;$tamt=0; @endphp
@foreach($trimsOutwardDetailstables as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->class_name }} </td>
<td>{{ $rowDetail->item_code }} </td>
<td> {{ $rowDetail->item_name }}</td>
 <td>{{ $rowDetail->hsn_code }} </td>
<td>{{ round($rowDetail->item_qty,2) }} <br>
 <td>{{ $rowDetail->unit_name }}  <br>
 @if($TrimsOutwardMaster[0]->state_id==27)
CGST in %: {{$rowDetail->cgst_per}} <br>
SGST in %: {{$rowDetail->sgst_per}}
@else

IGST in %: {{$rowDetail->igst_per}}   
 


@endif

</td>
 <td>{{ $rowDetail->item_rate }} <br>
  @if($TrimsOutwardMaster[0]->state_id==27)
 CGST AMT: {{ number_format(($rowDetail->cgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100))}} <br>
 SGST AMT: {{ number_format(($rowDetail->sgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100))}}  
  @else
  
  IGST AMT: {{ number_format(($rowDetail->igst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100))}} <br>
  
  @endif
 </td>
  <td>{{ number_format($rowDetail->item_rate*$rowDetail->item_qty)}} <br>
  
  </td>
  <td>
 @if($TrimsOutwardMaster[0]->state_id==27)
 {{ number_format((($rowDetail->item_rate*$rowDetail->item_qty) + ($rowDetail->cgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100) + ($rowDetail->sgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100)))}}  
  @php   
  
  $cgst=($rowDetail->cgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100);
  $sgst=($rowDetail->cgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100);
  
  $total_amt=(($rowDetail->item_rate*$rowDetail->item_qty) + ($rowDetail->cgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100) + ($rowDetail->sgst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100));   
  $totalcgst=$totalcgst+$cgst;
   $totalsgst=$totalsgst+$sgst;
   @endphp
  @else
  
  {{ number_format((($rowDetail->item_rate*$rowDetail->item_qty)+($rowDetail->igst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100)))}} 
 @php   
 $igst=($rowDetail->igst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100);
  $total_amt=(($rowDetail->item_rate*$rowDetail->item_qty)+($rowDetail->igst_per*($rowDetail->item_rate*$rowDetail->item_qty)/100));
  $totaligst=$totaligst + $igst;
@endphp
  @endif
      
  </td>
  
</tr>

@php $no=$no+1;

$totalqty = $totalqty + $rowDetail->item_qty;
$amt=$amt+($rowDetail->item_rate*$rowDetail->item_qty);
$tamt=$tamt+$total_amt;
@endphp
  @endforeach
<tfoot>

</tfoot>
</tbody>
 
</table>

<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
    <tbody>
<tr>
     
<td colspan="5" style="text-align:right;">   <b>Total Qty:   </b></td>
<td  ><b> {{ number_format($totalqty)}}  </b></td>
 
 <td style="font-weight:bold;">Total Amount</td>
<td style="font-weight:bold;">{{ number_format($amt) }}</td>
 <td style="font-weight:bold;">Net Amount</td>
<td style="font-weight:bold;">{{number_format($tamt)}}</td>
</tr>    
  <tr>   <td colspan="9" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td></tr>

</tbody>
 
</table>

@php
 $number =  round($amt);
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

<table style="float:right; " >
    <tbody>
       @if($TrimsOutwardMaster[0]->state_id==27)
        <tr>
            <th>CGST AMT :</th>
            <th>{{round($totalcgst)}}</th>
        </tr>
     <tr>
                 <th>SGST AMT : </th>
              <th>{{round($totalsgst)}}</th>
        </tr>  
        <tr>
      <th>Total Amount : </th> 
            <th> &#8377;{{number_format($tamt)}}</th>
        </tr>
    @else
     <tr>
          <th> </th>
            <th>  </th>
            <th>IGST AMT : </th>
            <th> {{round($totaligst)}}</th>
        </tr>
        <tr>
          <th> </th>
            <th>  </th>
            <th>Total Amount : </th>
            <th>&#8377; {{number_format($tamt)}}</th>
        </tr>
     @endif
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
 

<p class="text-center d-print-none"><a href="/TrimsOutward">&laquo; Back to List</a></p>
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