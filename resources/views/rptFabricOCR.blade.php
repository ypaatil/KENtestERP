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
          table{
          display: table;
          width:100%;
          border-collapse:collapse;
          }
          tr {
          display: table-row;
          padding: 2px;
          }
          tr p {
          margin: 0px !important; 
          }
          td,th {
          display: table-cell;
          padding: 8px;
          width: 410px;
          border: #000000 solid 1px;
          font-size:14px !important;
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
          
          .invoice-container{
                  border: none;
          }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">FABRIC OCR REPORT</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th nowrap>Sr.No.</th>
                           <th nowrap>PO Number</th>
                           <th nowrap>PO Date</th>
                           <th nowrap>GRN No</th>
                           <th nowrap>GRN Date</th>
                           <th nowrap>Invoice No.</th>
                           <th nowrap>Invoice Date</th>
                           <th nowrap>Item Name</th>
                           <th nowrap>Description</th>
                           <th nowrap>UOM</th>
                           <th nowrap>Rate</th>
                           <th nowrap class="text-center">PO  Qty</th>
                           <th nowrap class="text-center">Received Qty</th>
                           <th nowrap class="text-center">Pending Qty</th>
                           <th nowrap class="text-center">PO Value</th>
                           <th nowrap class="text-center">Received Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                            $nos = 1;
                        @endphp
                        @foreach($FabricOCRList as $ocr)
                        <tr>
                           <td nowrap>{{$nos++}}</td>
                           <td nowrap>{{$ocr->pur_code}}</td>
                           <td nowrap>{{$ocr->pur_date}}</td>
                           <td nowrap>{{$ocr->in_code}}</td>
                           <td nowrap>{{$ocr->in_date}}</td>
                           <td nowrap>{{$ocr->invoice_no}}</td>
                           <td nowrap>{{$ocr->invoice_date}}</td>
                           <td nowrap>{{$ocr->item_name}}</td>
                           <td nowrap>{{$ocr->item_description}}</td>
                           <td nowrap>{{$ocr->unit_name}}</td>
                           <td nowrap>{{$ocr->item_rate}}</td>
                           <td nowrap class="text-right">{{$ocr->po_qty}}</td>
                           <td nowrap class="text-right">{{$ocr->received_qty}}</td>
                           <td nowrap class="text-right">{{($ocr->po_qty) - ($ocr->received_qty)}}</td>
                           <td nowrap class="text-right">{{round(($ocr->po_qty * $ocr->item_rate))}}</td>
                           <td nowrap class="text-right">{{$ocr->received_Value}}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated FABRIC OCR REPORT</p>
      <p class="text-center d-print-none"><a href="{{Route('OpenOrderPPC.index')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
//   function setTextToTd(index,ele)
//   {
//         $("#td"+index).text(ele).css("text-align","right");
//         var updated = parseInt(index) + 3;
//         var total = $("#total"+updated).text().replace(/,/g , '');
//         console.log(total);
//         $("#DevTd"+index).text(parseInt(total) - parseInt(ele));
//         $("#td"+index).attr("onclick","setInputBox("+index+",this)");
//   }
   
//   function setInputBox(index,obj)
//   {
//       $(obj).removeAttr("onclick");
//       $(obj).wrapInner('<input type="number" step="any" class="form-control" value="0" style="width: 100px;"  onchange="setTextToTd('+index+',this.value);" />');
//   }
   $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
     document.getElementById("doPrint").addEventListener("click", function() {
     var printContents = document.getElementById('invoice').innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     });
     
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'FABRIC OCR REPORT.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
      $(document).ready(function(){
            var result = [];
            $('table tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text());
               });
            });
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            $('table').append('<tr><td colspan="11" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
               $('table tr').last().append('<td class="text-right"><strong>'+this.toFixed(2)+'</strong></td>')
            });
      });
    //   $(document).ready(function(){
    //         var result = [];
    //         $('table tr').each(function(){
    //           $('td', this).each(function(index, val){
    //               if(!result[index]) result[index] = 0;
    //               result[index] += parseFloat($(val).text() ? $(val).text() : 0);
    //           });
    //         });
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //         var p = 0;
    //         $(result).each(function(){
    //             var x=this;
    //             x=x.toString();
    //             var afterPoint = '';
    //             if(x.indexOf('.') > 0)
    //               afterPoint = x.substring(x.indexOf('.'),x.length);
    //             x = Math.floor(x);
    //             x=x.toString();
    //             var lastThree = x.substring(x.length-3);
    //             var otherNumbers = x.substring(0,x.length-3);
    //             if(otherNumbers != '')
    //                 lastThree = ',' + lastThree;
    //             var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
    //           if(res == 'NaN')
    //           {
    //               res = "-";
    //           }
    //           else
    //           {
    //               res1 = res.split('.');
    //               if(res1.length > 1)
    //               {
    //                  res2 = "."+res1[1].substr(0, 2);
    //               }
    //               else
    //               {
    //                   res2  = "";
    //               }
    //               res = res1[0]+""+res2;
    //           }
    //           $('#totalColumns').append('<td class="text-right" id="total'+p+'"><strong>'+res+'</strong></td>');
    //           p++;
    //         });
    //   });
   </script>
</html>