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
 <h2>Buyer Job Card</h2>
<h2>From: {{ date('d-m-Y',strtotime($_POST['fdate'])) }}  To: {{ date('d-m-Y',strtotime($_POST['tdate'])) }} </h2>
</address>
	 

@if(count($jobcardMaster)>0)

       @foreach($jobcardMaster as $rowMaster)    
     <hr>
              
            <table >
                <thead>
               
                                              <th>JC No</th>
                                                <th>Date</th>
                                                <th>Buyer Name</th>
                                                <th>Final Product</th> 
                                                <th>Style/Design No</th>
                                               <th>Start Date</th> 
                                                <th>End Date</th> 
                                                <th>Job status</th>
                                                <th>Brand</th>
                                                <th>Season</th>
                                           
                </thead>
                <tbody>
                  
                    <tr>
                                                <td>{{ $rowMaster->po_code }}  </td>
                                                <td>{{ $rowMaster->po_date }}</td>
                                                <td>{{ $rowMaster->Ac_name }}</td>
                                                <td>{{ $rowMaster->fg_name }}</td>
                                                <td>{{ $rowMaster->style_no }}</td>
                                                <td>{{ $rowMaster->start_date }}</td>
                                                <td>{{ $rowMaster->end_date }}</td>
                                                <td>{{ $rowMaster->job_status_name }}</td>
                                                <td>{{ $rowMaster->brand_name }}</td>
                                                <td>{{ $rowMaster->season_name }}</td>
                                              
                </tr>
                
            
                </tbody>
            </table>
            
            
            
            
            <table >
                <thead>
               
                                     
                                                <th>Total meter</th>
                                                <th>Total Qty</th>
                                                <th>Rate/Piece</th>
                                                <th>Total Amount</th>
                                                <th>Development Sample</th>
                                                <th>Fit Sample</th> 
                                                <th>production Sample</th> 
                                                <th>FPT Sample</th> 
                                                <th>GPT Sample</th>
                                                <th>Sealer</th>
                                                <th>Shipment</th>
                                                <th>Photoshoot</th> 
                </thead>
                <tbody>
                    
                    
              
        

                    
                       
                    <tr>
                                            
                                                <td>{{ $rowMaster->total_meter }}</td>
                                                <td>{{ $rowMaster->total_qty }}</td>
                                                <td>{{ $rowMaster->rate_per_piece }}</td>
                                                <td>{{ $rowMaster->total_amount }}</td>
                                                @php 
                                                if($rowMaster->development_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->fit_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->production_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->fpt_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->gpt_sample==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->sealer==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->shipment==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                if($rowMaster->photoshoot==1){echo '<td>Required</td>';}else{ echo '<td>Not Required</td>';}
                                                @endphp
                </tr>
                
              
                </tbody>
            </table>
      
 <div class="row">
     <div class="col-md-6">
      <table >
    <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Color</th>
<th rowspan="2">Size</th>
<th rowspan="2">Qty</th>
</tr>

</thead>
<tbody>
@php

         $job_card_detailslists = App\Models\BuyerJobCardDetail::join('color_master','color_master.color_id', '=', 'job_card_details.color_id')
        ->where('job_card_details.po_code','=', $rowMaster->po_code)->get(['job_card_details.*','color_master.color_name']);

$no=1; @endphp
@foreach($job_card_detailslists as $rowDetail)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetail->color_name }}</td>
<td>{{ $rowDetail->sz_code }}</td>
<td>{{ $rowDetail->qty }}</td>
</tr>
@php $no=$no+1; @endphp
  @endforeach

</tbody>
</table>
</div>
     <div class="col-md-6">
           <table>
    <thead>
<tr>
<th rowspan="2">SrNo</th>
<th rowspan="2">Sample</th>
<th rowspan="2">Sample Company Date</th>
<th rowspan="2">Sample Tentative Date</th>
</tr>

</thead>
<tbody>
@php 
        $SampleSetLists = App\Models\BuyerJobCardSampleDetail::join('samples_master','samples_master.sample_id', '=', 'job_card_sample_details.sample_id')
        ->where('job_card_sample_details.po_code','=', $rowMaster->po_code)->get(['job_card_sample_details.*','samples_master.sample_name']);
                    


$no=1; @endphp
@foreach($SampleSetLists as $rowDetailSample)  
<tr>
<td>{{ $no }}</td>
<td>{{ $rowDetailSample->sample_name }}</td>
<td>{{ $rowDetailSample->sample_comp_date }}</td>
<td>{{ $rowDetailSample->sample_tentative_date }}</td>
</tr>
@php $no=$no+1; @endphp
  @endforeach

</tbody>
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