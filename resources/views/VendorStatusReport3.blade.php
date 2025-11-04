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
    .table-bordered td, .table-bordered th {
    border: 1px solid #0c0c0c;
    body{
    font-family: "Times New Roman", Times, serif;
    padding:50px;
    }
    
}
@page{
    
  margin: 5px !important;
}
 
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
    
   <h3>Vendor Status Report</h3>
   <h5>Vendor Name: {{$LedgerList[0]->ac_name}}</h5>
 @php 
 if(isset($fdate) && isset($tdate))
 {
 @endphp
<h6 class="text-center">From: {{ date('d-m-Y',strtotime($fdate)) }}  To: {{ date('d-m-Y',strtotime($tdate)) }} </h6>

@php }  @endphp
 
	 
 
     <hr>
              
            <table >
                <thead>
               
                                                <th>Department</th>
                                                <th>Line</th>
                                                <th>Qty</th>
                                                
                                           
                </thead>
                <tbody>
                    
                    
                    <tr><td colspan="3"><b>Cutting:</b></td></tr>
                     <tr>
                         @php
                         $totalQty=0;
                         $CuttingIssue=DB::select("select sum(size_qty_total) as  qty from cut_panel_issue_detail where vendorId='".$vendorId."' 
                         and cpi_date between '".$fdate."' and '".$tdate."'");
                          @endphp
                         
                          <td>-</td>
                          <td>N/A</td>
                         <td>{{$CuttingIssue[0]->qty}}</td>
                         
                     </tr>
                      <tr><td colspan="3"><b>Production:</b></td></tr>
                     
                        @foreach($LineList as $line)
                      <tr> 
                      @php
                         
                            $Stitching=DB::select("select sum(size_qty_total) as  qty from stitching_inhouse_detail
                            INNER JOIN stitching_inhouse_master on stitching_inhouse_master.sti_code=stitching_inhouse_detail.sti_code
                            where stitching_inhouse_master.vendorId='".$vendorId."' and 
                            stitching_inhouse_master.sti_date between '".$fdate."' and '".$tdate."' and 
                            stitching_inhouse_master.line_id='".$line->line_id."'");
                         
                          @endphp
                         
                          <td>-</td>
                          <td>{{$line->line_name}}</td>
                         <td>{{$Stitching[0]->qty}}</td>
                         
                    
                     @php 
                         $totalQty=$totalQty + $Stitching[0]->qty;
                     @endphp 
                     
                     </tr>
                     @endforeach
                      <tr>
                     <td>-</td>
                          <td><b>Total</b></td>
                         <td><b>{{$totalQty}}</b></td>
                      </tr>
                      <tr><td colspan="3"><b>QC-Rejection:</b></td></tr>
                      <tr>
                        
                        @php
                         
                            $QCStitching=DB::select("select sum(size_qty_total) as  qty from qcstitching_inhouse_reject_detail
                            INNER JOIN qcstitching_inhouse_master on qcstitching_inhouse_master.qcsti_code=qcstitching_inhouse_reject_detail.qcsti_code
                            where qcstitching_inhouse_master.vendorId='".$vendorId."' and 
                            qcstitching_inhouse_master.qcsti_date between '".$fdate."' and '".$tdate."'");
                         
                          @endphp
                         
                          <td>-</td>
                          <td>N/A</td>
                         <td>{{$QCStitching[0]->qty}}</td>
                         
                     </tr>
                     
                     
                      <tr><td colspan="3"><b>Finishing:</b></td></tr>
                      <tr>
                        
                        @php
                         
                            $Finishing=DB::select("select sum(size_qty_total) as  qty from finishing_inhouse_detail
                             where finishing_inhouse_detail.vendorId='".$vendorId."' and 
                            finishing_inhouse_detail.fns_date between '".$fdate."' and '".$tdate."'");
                         
                          @endphp
                         
                          <td>-</td>
                          <td>N/A</td>
                         <td>{{$Finishing[0]->qty}}</td>
                         
                     </tr>
                     
                      <tr><td colspan="3"><b>Packing:</b></td></tr>
                      <tr>
                        
                        @php
                         
                            $Packing=DB::select("select sum(size_qty_total) as  qty from packing_inhouse_detail
                             where packing_inhouse_detail.vendorId='".$vendorId."' and 
                            packing_inhouse_detail.pki_date between '".$fdate."' and '".$tdate."'");
                         
                          @endphp
                         
                          <td>-</td>
                          <td>N/A</td>
                         <td>{{$Packing[0]->qty}}</td>
                         
                     </tr>
                     
                     
                </tbody>
            </table>
 
      
 
    
    
     
</div>
 
   
<!--<center><h4 style="font-weight:bold;">Record Not Found</h4></center>-->

 


<p><small>Print Date </small>

</p><footer><a href="GetVendorStatusReport">	Back To Filter </a></footer>

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