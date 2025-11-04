<!DOCTYPE html>
<html><head>

<title>India Garment</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<style>
html, body, div, h1, h2, h3, p, blockquote, ul, ol, li, pre { 
margin: 0; padding: 0 }
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


address {  padding: 0 0 1em; font-style: normal; text-align:center; }
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
  padding: 8px;
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
India Garment
 <h2>Fabric Cutting</h2>
<h2>From: {{ date('d-m-Y',strtotime($_POST['fdate'])) }}  To: {{ date('d-m-Y',strtotime($_POST['tdate'])) }} </h2>
</address>
	 
@if(count($CuttingMasterList)>0)

 @foreach($CuttingMasterList as $rowMaster)    
     <hr>
              
            <table >
                <thead>
               
                                                 <th>TrCode</th>
                                                <th>TrDate</th>
                                                <th>Buyer Name</th>
                                                <th>Lot No</th>
                                                <th>Job No</th>
                                                <th>Style/Design No</th>
                                                <th>Table No</th>
                                                <th>Table Average</th>
                                           
                </thead>
                <tbody>
                        
                    <tr>
                                               <td> {{ $rowMaster->cu_code  }} </td>
                                                <td> {{ $rowMaster->cu_date  }} </td>
                                                <td> {{ $rowMaster->Ac_name  }} </td>
                                                <td> {{ $rowMaster->lot_no  }} </td>
                                                <td> {{ $rowMaster->job_code  }} </td>
                                                <td> {{ $rowMaster->style_no  }} </td>
                                                <td> {{ $rowMaster->table_name  }} </td>
                                                <td> {{ $rowMaster->table_avg  }} </td>
                                         
                                               
                                              
                </tr>
                
               
                </tbody>
            </table>
 
       <div class="row">
     <div class="col-md-6">
<span style="font-weight:bold;text-align:center;">1. Size/Qty:</span>
      <table >
    <thead>
<tr>
<th rowspan="2">SrNo</th>
<th>Track Code</th>
<th>Color</th>
<th>Width</th>
<th>Meter</th>
<th>Size</th>
<th>Ratio</th>
<th>Layers</th>
<th>Qty</th>
</tr>

</thead>
<tbody>
    
    
    
    
    
@php


$CuttingDetailList = App\Models\CuttingDetailModel::join('color_master','color_master.color_id', '=', 'cutting_details.color_id')
->join('size_master','size_master.sz_code','=','cutting_details.sz_code')
->where('cutting_details.cu_code','=', $rowMaster->cu_code)->get(['cutting_details.*','color_master.color_name','size_master.sz_name']);


$no=1; @endphp
@foreach($CuttingDetailList as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->track_code }}</td>
<td>{{ $rowDetail->color_name }}</td>
<td>{{ $rowDetail->width }}</td>
<td>{{ $rowDetail->meter }}</td>
<td>{{ $rowDetail->sz_name }}</td>
<!--<td>{{ $rowDetail->taga_qty }}</td>-->
<td>{{ $rowDetail->ratio }}</td>
<td>{{ $rowDetail->layers }}</td>
<td>{{ $rowDetail->qty }}</td>
</tr>
@php $no=$no+1; @endphp
  @endforeach

</tbody>

<tfoot>
<tr>
<td colspan="8"><b>Total Qty</b></td>
 <td>{{ $rowMaster->total_pieces }}</td>
</tr>    
    
</tfoot>
</table>
</div>

<div class="col-md-6">
<span style="font-weight:bold;text-align:center;">2. Comsuption/Cut Piece/Damage Meter:</span>
      <table >
    <thead>
<tr>
<th>SrNo</th>
<th>Track Code</th>
<th>Color</th>
<th>Width</th>
<th>Meter</th>
<th>Layers</th>
<th>Used Meter</th>
<th>Balance</th>
<th>Cut Piece Meter</th>
<th>Damage Meter</th>
<th>Short Meter</th>
<th>Extra Meter</th>
</tr>

</thead>
<tbody>
    
    
    
    
@php


$CuttingBalanceDetailList = App\Models\CuttingBalanceDetailModel::join('color_master','color_master.color_id', '=', 'cutting_balance_details.color_id')
->where('cutting_balance_details.cu_code','=', $rowMaster->cu_code)->get(['cutting_balance_details.*','color_master.color_name']);


$no=1; @endphp
@foreach($CuttingBalanceDetailList as $rowDetailbalance)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetailbalance->track_code }}</td>
<td>{{ $rowDetailbalance->color_name }}</td>
<td>{{ $rowDetailbalance->width }}</td>
<td>{{ $rowDetailbalance->meter }}</td>
<td>{{ $rowDetailbalance->layers }}</td>
<!--<td>{{ $rowDetail->taga_qty }}</td>-->
<td>{{ $rowDetailbalance->used_meter }}</td>
<td>{{ $rowDetailbalance->balance_meter }}</td>
<td>{{ $rowDetailbalance->cpiece_meter }}</td>
<td>{{ $rowDetailbalance->dpiece_meter }}</td>
<td>{{ $rowDetailbalance->short_meter }}</td>
<td>{{ $rowDetailbalance->extra_meter }}</td>

</tr>
@php $no=$no+1; @endphp
  @endforeach

</tbody>

<tfoot>
<tr>
<td colspan="5"><b>Total</b></td>
 <td>{{ $rowMaster->total_layers }}</td>
  <td>{{ $rowMaster->total_used_meter }}</td>
    <td></td>
    <td>{{ $rowMaster->total_cutpiece_meter }}</td>
    <td>{{ $rowMaster->total_damage_meter }}</td>
      <td>{{ $rowMaster->total_short_meter }}</td>
       <td>{{ $rowMaster->total_extra_meter }}</td>
</tr>    
    
</tfoot>
</table>





</div>
</div>
 
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