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
                  <h4 class="mb-0">SAH PPC Month Wise Report </h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th>Sr.No.</th>
                           <th nowrap>Vendor Name</th>
                           <th nowrap>Line Name</th>
                           <th nowrap>Sales Order No.</th>
                           <th nowrap>Style</th>
                           <th nowrap>SAM</th>
                           @foreach($monthWise as $mon)
                               <th nowrap style="background: #dbdb29;">Available Min</th>
                               
                               @php 
                                 $month = date('F', mktime(0, 0, 0, $mon->month, 1));
                               @endphp
                               
                               <th nowrap>{{$month}}</th>
                               <th nowrap>Booked Min</th>
                               <th nowrap>Open Min</th>
                           @endforeach
                        </tr>
                     </thead>
                     <tbody>
                        @php
                            $no = 1;
                        @endphp 
                        @foreach($SAHPPCList as $row)
                        <tr>
                           <td>{{$no++}}</td>
                           <td nowrap>{{$row->ac_name}}</td>
                           <td nowrap>{{$row->line_name}}</td>
                           <td nowrap>{{$row->sales_order_no}}</td>
                           <td nowrap>{{$row->style_no}}</td>
                           <td>{{$row->sam}}</td>
                           @foreach($monthWise as $mon)
                          
                            @php
                               //DB::enableQueryLog();
                               $monthWise1 = DB::select("SELECT * FROM sah_ppc_master WHERE vendorId=".$row->vendorId." AND line_id=".$row->line_id." AND sales_order_no='".$row->sales_order_no."' AND month=".$mon->month);
                               //dd(DB::getQueryLog());
                               if(count($monthWise1) > 0)
                               {
                                    $totalAvaliableMin = $monthWise1[0]->totalAvaliableMin;
                                    $monthValue = $monthWise1[0]->monthValue;
                                    $bookedMin = $monthWise1[0]->bookedMin;
                                    $openMin = $monthWise1[0]->openMin;
                               }
                               else
                               {
                                    $totalAvaliableMin = 0;
                                    $monthValue = 0;
                                    $bookedMin = 0;
                                    $openMin = 0;
                               }
                           @endphp
                           
                               <td class="text-right" style="background: #dbdb29;">{{number_format($totalAvaliableMin)}}</td>
                               <td class="text-right">{{number_format($monthValue)}}</td>
                               <td class="text-right">{{number_format($bookedMin)}}</td>
                               <td class="text-right">{{number_format($openMin)}}</td>
                           @endforeach
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated SAH PPC Month Wise Report</p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
   //$('#printInvoice').click(function(){
    //   Popup($('.invoice')[0].outerHTML);
    //   function Popup(data) 
    //   {
    //       window.print();
    //       return true;
    //   }
    //   });
      
    //  document.getElementById("doPrint").addEventListener("click", function() {
    //  var printContents = document.getElementById('invoice').innerHTML;
    //  var originalContents = document.body.innerHTML;
    //  document.body.innerHTML = printContents;
    //  window.print();
    //  document.body.innerHTML = originalContents;
    //  });
     
    //  function html_table_to_excel(type)
    //  {
    //     var data = document.getElementById('invoice');

    //     var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

    //     XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

    //     XLSX.writeFile(file, 'Monthly Shipment Target Plan.' + type);
    //  }

    //      const export_button = document.getElementById('export_button');
    
    //   export_button.addEventListener('click', () =>  {
    //         html_table_to_excel('xlsx');
    //   });
      $(document).ready(function(){
            var result = [];
            $('table tr').each(function(){
              $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g, ''));
                  console.log($(val).text());
              });
            });
             result.shift();
             result.shift();
             result.shift();
             result.shift();
             result.shift();
            $('table').append('<tr><td colspan="5" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var x=this;
                x=x.toString();
                var afterPoint = '';
                if(x.indexOf('.') > 0)
                  afterPoint = x.substring(x.indexOf('.'),x.length);
                x = Math.floor(x);
                x=x.toString();
                var lastThree = x.substring(x.length-3);
                var otherNumbers = x.substring(0,x.length-3);
                if(otherNumbers != '')
                    lastThree = ',' + lastThree;
                var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
                
              $('table tr').last().append('<td class="text-right"><strong>'+res+'</strong></td>')
            });
      });
   </script>
</html>