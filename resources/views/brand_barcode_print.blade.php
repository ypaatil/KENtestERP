@inject('carbon', 'Carbon\Carbon')
<!DOCTYPE html>
<html>
   <head>
      <title>KEN GLOBAL DESIGNS PRIVATE LIMITED</title>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <style>
         html, body, div, h1, h2, h3, p, blockquote, ul, ol, li, pre { 
         margin: 0; padding: 0 }
         li { margin-left: 1.5em }
         h1{text-align:center;}
         @media screen { body { margin: 0em;size: A4 landscape; }}
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
         table {
         font-family: arial, sans-serif;
         border-collapse: collapse;
         width: 100%;
         font-size: 12px;
         }
         th, td {
         text-align: left;
         padding: 4px;
         width: 25%; /* This ensures that all columns have the same width */
         }
         tr:nth-child(even) {
         background-color: #dddddd;
         }
         th {
         border: 1px solid black;
         }
         td {
         border: 1px solid black;
         }
         .sr_no{
         text-align: left;
         padding: 4px;
         width: 5%!important; /* This ensures that all columns have the same width */
         }
         span.sign{border: 1px solid black!important;  padding-left:90px;  padding-right:0px; padding-bottom: 27px; margin-left:30px !important;}
         /* Styles go here */
         .page-header,
         .page-header-space {
         height: 100px;
         }
         /*#page-footer,*/
         /*.page-footer-space {*/
         /*  height: 100px; */
         /*}*/
         .page-header {
         position: fixed;
         top: 0mm;
         width: 50%;
         margin-left: 30%;
         }
         @media print {
         .button_niks {
         display: none !important;
         }   
         }
         /*@media print { */
         /* #pageFooter{ */
         /*     counter-increment: mycount;*/
         /* } */
         /* #pageFooter::before {*/
         /*     content: "Page " counter(mycount);*/
         /*     position: fixed;*/
         /*     bottom: 0;*/
         /*     right: 0;*/
         /*     padding: 5px;*/
         /*     font-size: 12px;*/
         /* }*/
         /*}*/
         /*@media print {*/
         /*    .pagebreaks { page-break-before: always; } */
         /*     page-break-after works, as well */
         /*}*/
         /*   @media print {*/
         /*    .pagebreaks {*/
         /*           page-break-inside: avoid; */
         /*    }*/
         /*}*/
         @page {
         /*margin-top: 130px;*/
         /*margin-left: 2px;*/
         /*margin-bottom: 40px;*/
         /*margin-right: 2px;*/
         /*size: landscape;*/
         /*counter-increment: page;  */
         /*@bottom-right {*/
         /*    padding-right:20px;*/
         /*    content: "Page " counter(page);*/
         /*} */ 
         /*a[href]:after { display: none!important; }*/
         } 
      </style>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script> 
      <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
   </head>
   <body class="usd" id="printsdiv">
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <a href="{{ url()->previous() }}" class="button_niks btn  btn-danger btn-rounded print" >Back</a>   
     
      <div  id="load_data_table" >
         <div  style="page-break-inside: avoid;">
        <div style="border:1px solid black; width: 3in; padding: 10px; box-sizing: border-box; margin-top: 10px;">

  <!-- Barcode -->
  <div style="text-align: center; margin-bottom: 8px;">
    <svg id="barcode" style="width: 150px; height: 40px; display: inline-block;"></svg>
  </div>

  <!-- Style and Rate side-by-side -->
  <div style="display: flex; justify-content: space-between; font-size: 12px;">
    <div style="flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
      <strong>Style:</strong> {{ $data->mainstyle_name }}
    </div>
    <div style="flex: 1; text-align: right; white-space: nowrap;">
      <strong>Rate:</strong> ₹{{ number_format($data->barcode_brand_rate, 2) }}
    </div>
  </div>

</div>

         </div>
      </div>
    
      <script>
         document.getElementById("doPrint").addEventListener("click", function() { 
                 var printContents = document.getElementById('printsdiv').innerHTML;
                 var originalContents = document.body.innerHTML; 
                 document.body.innerHTML = printContents;  
                  console.log(window.location.pathname);
                 window.print();  
                 document.body.innerHTML = originalContents;  	
                 
         });
         
         
         // Wait until the DOM is fully loaded
         document.addEventListener("DOMContentLoaded", function() {
         // Loop through all the barcodes
      
             JsBarcode("#barcode", "{{ $data->barcode_brand_id }}", {
                 format: "CODE128",  // Barcode format
                 displayValue: true,  // Display the employee code below the barcode
                 height: 40,         // Height of the barcode
                 width: 2,           // Width of the barcode
                 margin: 10          // Margin around the barcode
             });
       
         });
         
         
         
      </script>
   </body>
</html>










