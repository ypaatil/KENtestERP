<!DOCTYPE html>
<html><head>

<title>Stiching Garment</title>
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

@page{
margin: 2%;

}
</style>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
<a class="button_niks btn  btn-danger btn-rounded" href="{{route('TrimsOutward.index')}}" >Back</a>
<body class="usd">
<div id="printsdiv" style="margin: 2%;">
<address  >
KEN GLOBAL DESIGNS PRIVATE LIMITED
 <h2>TRIM OUTWARD</h2>
 @php if(isset($_POST['fdate']))
 {
echo '<h2>From: '.date("d-m-Y",strtotime($_POST["fdate"])).' To: '.date("d-m-Y",strtotime($_POST["tdate"])).'   </h2>';
}
@endphp


</address>
	 

@if(count($datafetch)>0)

 @foreach($datafetch as $rowMaster)   
     <hr>
              
            <table >
                <thead>
               
                               <th>Code</th>
                                <th>Date</th>
                                <th>Vendor Name</th>
                                <th>Vendor Code</th> 
                                <th>Total Qty</th>
                                            
                                           
                </thead>
                <tbody>
                         
                    <tr>
                                                 <td>{{ $rowMaster->trimOutCode }}</td>
                                                <td>{{ $rowMaster->tout_date }}</td>
                                                <td>{{ $rowMaster->Ac_name }}</td>
                                                <td>{{ $rowMaster->vw_code }}</td>
                                                <td>{{ $rowMaster->total_qty }}</td>
                                         
                                              
                </tr>
                
            
                </tbody>
            </table>
      

      <table >
    <thead>
<tr>
<th>SrNo</th>
<th>Item Name</th>
<th>HSN</th>
<th>Unit</th>
<th>Quantity</th>
</tr>

</thead>
<tbody>
@php 



   $detailpurchase = App\Models\TrimsOutwardDetailModel::
   join('item_master','item_master.item_code', '=', 'trimsOutwardDetail.item_code')
   ->join('unit_master','unit_master.unit_id', '=', 'trimsOutwardDetail.unit_id')
   ->where('trimOutCode','=', $rowMaster->trimOutCode)->get(['trimsOutwardDetail.*','item_master.item_name','unit_master.unit_name']);



$no=1; @endphp
@foreach($detailpurchase as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->item_name }}</td>
<td>{{ $rowDetail->hsn_code }}</td>
<td>{{ $rowDetail->unit_name }}</td>
<td>{{ $rowDetail->item_qty }}</td>
</tr>

@php $no=$no+1; @endphp
  @endforeach
<tfoot>
   
    
</tfoot>
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