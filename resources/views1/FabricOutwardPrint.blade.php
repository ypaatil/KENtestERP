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

<div id="printInvoice">
    <div class="row" style="border: #000000 solid 1px;">
    <div  class="col-md-4">
        
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery No:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->fout_code }} </span></br>     
      <b style="display: inline-block;text-align: left;" class="mt-1">Delivery Date:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->fout_date }} </span></br>
      <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->sales_order_no }} </span></br>
      
     <b style="display: inline-block;text-align: left;" class="mt-1">Cutting PO No:  </b> <span style="display: inline-block;text-align: right;"> {{ $FabricOutwardMaster[0]->vpo_code }} </span></br>
       
</div> <div  class="col-md-3" >
   
        </div>
    <div  class="col-md-5">         
    <b style="display: inline-block;text-align: left;" class="mt-1">Vendor: </b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->Ac_name }} </span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PO No:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->po_code }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">Address:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->address }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">GST No:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->gst_no }}</span></br>
       <b style="display: inline-block;text-align: left;" class="mt-1">PAN No:</b>  <span style="display: inline-block;text-align: right;">{{  $FabricOutwardMaster[0]->pan_no }}</span></br>
    </div>
   
    </div>

</div>

<!-- Passenger Details -->
<h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
<div class="">
<table class="table table-bordered text-1 table-sm" style="height:10vh; ">
   <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Item Code</th>
<th rowspan="2">Item Name</th>
<th rowspan="2">HSN No.</th>
<th rowspan="2">Color</th>
<th rowspan="2">Part</th>
<th rowspan="2">Quality</th>
<th rowspan="2">Width</th>
<th rowspan="2">Qty</th>
<th rowspan="2">UOM</th>
 <th rowspan="2">Rate</th>
 <th rowspan="2">Amount</th>
 
</tr>

</thead>
<tbody>
@php 

  $FabricOutwardDetailstables = App\Models\FabricOutwardDetailModel::select('item_master.color_name','unit_master.unit_name','item_master.item_name','item_master.hsn_code','fabric_outward_details.width',
  'fabric_outward_details.item_code','item_master.item_description','inward_details.item_rate',
  'item_master.dimension','part_master.part_name',DB::raw('sum(fabric_outward_details.meter) as meter'))->join('item_master','item_master.item_code', '=', 'fabric_outward_details.item_code')
    ->join('part_master','part_master.part_id', '=', 'fabric_outward_details.part_id')
    ->join('unit_master','unit_master.unit_id', '=', 'item_master.unit_id')
    ->join('inward_details','inward_details.track_code','=','fabric_outward_details.track_code')
    ->where('fabric_outward_details.fout_code','=', $FabricOutwardMaster[0]->fout_code)
    ->groupby('fabric_outward_details.item_code')
    ->get();


$no=1; $amt=0; @endphp
@foreach($FabricOutwardDetailstables as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_code }}</td>
<td> {{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->hsn_code }}</td>
<td>{{ $rowDetail->color_name }}</td>
<td>{{ $rowDetail->part_name }}</td>
<td>{{ $rowDetail->item_description }}</td>
<td>{{ $rowDetail->width }}</td>
<td>{{ number_format($rowDetail->meter,2) }}</td>
<td>{{$rowDetail->unit_name}}</td>
 <td>{{ $rowDetail->item_rate }}</td>
  <td>{{ number_format($rowDetail->item_rate*$rowDetail->meter,2)}}</td>
</tr>

@php $no=$no+1;
$amt=$amt+($rowDetail->item_rate*$rowDetail->meter);
@endphp
  @endforeach
<tfoot>
<tr>
    <td colspan="6"><b>    </b></td>
<td  <b>Total Taga:   </b></td>
<td  ><b> {{ $FabricOutwardMaster[0]->total_taga_qty }}  </b></td>
<td style="font-weight:bold;">Total  Meter</td>
<td style="font-weight:bold;">{{ number_format($FabricOutwardMaster[0]->total_meter,2) }}</td>
 <td style="font-weight:bold;">Gross Amount</td>
<td style="font-weight:bold;">{{ number_format($amt) }}</td>

</tr>    
      <tr >
        
        <td colspan="12" class="text-center"><b>NOT FOR SALE, FOR JOB WORK ONLY</b></td>
        </tr>
</tfoot>
</tbody>
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
       @if($FabricOutwardMaster[0]->state_id==27)
        <tr>
            <th>CGST (2.5%) :</th>
            <th>{{round($amt*(2.5/100))}}</th>
        </tr>
     <tr>
                 <th>SGST (2.5%) : </th>
            <th> {{round($amt*(2.5/100))}}</th>
        </tr>  
        <tr>
      <th>Total Amount : </th> 
            <th> &#8377;{{number_format($amt + round($amt*(2.5/100)) + round($amt*(2.5/100)))}}</th>
        </tr>
        
        
    @else
     <tr>
          <th> </th>
            <th>  </th>
            <th>IGST (5%) : </th>
            <th> {{round($amt*(5/100))}}</th>
        </tr>
        <tr>
          <th> </th>
            <th>  </th>
            <th>Total Amount : </th>
            <th>&#8377; {{number_format($amt + round($amt*(5/100)))}}</th>
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