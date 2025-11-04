<!DOCTYPE html>
<html><head>

<title>KEN GLOBAL DESIGNS PRIVATE LIMITED</title>
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
<a href="{{ Route('FabricChecking.index') }}" class="btn  btn-danger btn-rounded print" >Back</a>
<body class="usd">
<div id="printsdiv">
<address  >
<h3>KEN GLOBAL DESIGNS PRIVATE LIMITED</h3>
 <h5>Fabric Checking</h5>
 
 @php 
 if(isset($_POST['fdate']) && isset($_POST['tdate']))
 {
 @endphp
<h2>From: {{ date('d-m-Y',strtotime($_POST['fdate'])) }}  To: {{ date('d-m-Y',strtotime($_POST['tdate'])) }} </h2>

@php }  @endphp
</address>
	 
@if(count($fabricChekingMaster)>0)

 @foreach($fabricChekingMaster as $rowMaster)    
     <hr>
              
            <table >
                <thead>
               
                                                <th>Code</th>
                                                <th>Date</th>
                                                <th>Inward Type</th>
                                                <th>Buyer</th>
                                                <th>PO Code</th> 
                                             
                                           
                </thead>
                <tbody>
                        
                    <tr>
                                                <td>{{ $rowMaster->chk_code }}  </td>
                                                <td>{{ $rowMaster->chk_date }}</td>
                                                <td>{{ $rowMaster->cp_name }}</td>    
                                                <td>{{ $rowMaster->Ac_name }}</td>
                                                <td>{{ $rowMaster->po_code }}</td>
                                               
                                              
                </tr>
                
               
                </tbody>
            </table>
 
      

      <table >
    <thead>
<tr>
<th>SrNo</th>
<th>Item Name</th>
<th>Width</th>
<th>Quality</th>
<th>Part</th>
<th>Old Meter</th>
<th>Meter</th>
<th>Kg</th>
<th>Shade</th>
<th>Status</th>
<th>Rejected / </br>Short Meter</th>
<th>TrackCode</th>
</tr>

</thead>
<tbody>
    
    
    
    
    
@php
 
$FabricChekingdetailslists = App\Models\FabricCheckingDetailModel::
join('item_master','item_master.item_code', '=', 'fabric_checking_details.item_code')
->join('shade_master','shade_master.shade_id', '=', 'fabric_checking_details.shade_id')
->join('part_master','part_master.part_id', '=', 'fabric_checking_details.part_id')
->join('fabric_check_status_master','fabric_check_status_master.fcs_id', '=', 'fabric_checking_details.status_id')
->where('fabric_checking_details.chk_code','=', $rowMaster->chk_code)
->get(['fabric_checking_details.*','fabric_check_status_master.fcs_name',
'item_master.item_description','item_master.item_name','item_master.item_description',
'item_master.color_name','item_master.dimension','shade_master.shade_name','part_master.part_name']);
 
$no=1; @endphp
@foreach($FabricChekingdetailslists as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_name }}</td>
 
<td>{{ $rowDetail->dimension }}</td>
<td>{{ $rowDetail->item_description }}</td>
<td>{{ $rowDetail->part_name }}</td>
<td>{{ $rowDetail->old_meter }}</td>
<td>{{ $rowDetail->meter }}</td>
<td>{{ $rowDetail->kg }}</td>
<td>{{ $rowDetail->shade_name }}</td>
<td>{{ $rowDetail->fcs_name }}</td>
<td>{{ $rowDetail->reject_short_meter }}</td>
<td>{{ $rowDetail->track_code }}</td>
</tr>
@php $no=$no+1; @endphp
  @endforeach
 
</tbody>

<tfoot>
<tr>
    <td colspan="5"><b>Narration:{{ $rowMaster->in_narration }} </b></td>
<td  ><b>Total</b></td>
 <td>{{ $rowMaster->total_meter }}</td>
  <td>{{ $rowMaster->total_kg }}</td>
 <td colspan="4"></td>
   
</tr>    
    
</tfoot>
</table>
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