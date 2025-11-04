<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>KEN</title>
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
         .table-bordered td, .table-bordered th 
         {
             border: 1px solid #0c0c0c;
             body
             {
                 font-family: "Times New Roman", Times, serif;
                 font-weight:bold;
             }
         }
         th,td{
             font-size: 15px;
               font-weight:bold;
         }
         .text-1{
            font-size: 12px!important;
              font-weight:bold;
         }
         .txtsize{
            font-size: 15px;
              font-weight:bold;
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="btn-group btn-group-sm d-print-none mt-5" style="margin-left: 10px;"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
      <div class="container-fluid invoice-container  pull-center">
         <!-- Header -->
         <div class="row invoice" style="justify-content: center;">
         <div class="col-md-12">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-3">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="" height="100" width="230"> </p>
                  </div>
                  <div class="col-md-7 text-center" style="margin-top: 2%;margin-left: -4%;">
                     <h5 class="mb-0" style="font-weight:bold;text-transform: uppercase;"> {{$FirmDetail->firm_name}}</h5>
                     <h6 class="mb-0" style="font-weight:bold;margin-top: 5%;">Sales Transaction Receipt</h6>
                  </div>
                  <div class="col-md-1">
                     <h6  style="font-weight:bold;"> </h6>
                  </div>
               </div>
               <h4 class="text-4"></h4>
               <div class=""></div>
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
                     margin: none!important;
                  }
                  .merged{
                  width:25%;
                  height:25%;
                  padding: 8px;
                  display: table-cell;
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
                  
                  @media print {
                      .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
                      .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                           float: left;               
                      }
                
                      .col-sm-12 {
                           width: 100%;
                      }
                
                      .col-sm-11 {
                           width: 91.66666666666666%;
                      }
                
                      .col-sm-10 {
                           width: 83.33333333333334%;
                      }
                
                      .col-sm-9 {
                            width: 75%;
                      }
                
                      .col-sm-8 {
                            width: 66.66666666666666%;
                      }
                
                       .col-sm-7 {
                            width: 58.333333333333336%;
                       }
                
                       .col-sm-6 {
                            width: 50%;
                       }
                
                       .col-sm-5 {
                            width: 41.66666666666667%;
                       }
                
                       .col-sm-4 {
                            width: 33.33333333333333%;
                       }
                
                       .col-sm-3 {
                            width: 25%;
                       }
                
                       .col-sm-2 {
                              width: 16.666666666666664%;
                       }
                
                       .col-sm-1 {
                              width: 8.333333333333332%;
                        }            
                }
               </style>
               <div id="printInvoice">
                  <div class="row" >
                  <div class="col-md-6" style="border: #000000 solid 1px; padding: 16px;">
                       <h6 class="mb-0" style="font-weight:bold;text-transform: uppercase;"> {{$FirmDetail->firm_name}}</h6>
                       <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                       <b style="display: inline-block;text-align: left;" class="mt-1">GSTIN NO:</b>    <span style="display: inline-block;text-align: right;">{{$FirmDetail->gst_no}} </span><br/>
                       
                  </div>
                  <div class="col-md-6" style="border: #000000 solid 1px;">
                          <b style="display: inline-block;text-align: left;">To: </b> <span style="display: inline-block;">-</span><br/><strong>Address: </strong><span style="display: inline-block;">-</span>
                      </p>
                  </div>
                   <div class="col-md-6" style="border: #000000 solid 1px;">
                     <p>
                     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sent Through :  </b><span style="display: inline-block;text-align: right;">-</span>   <br/>
                     </p>

                  </div>
                </div>
               </div>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;"> Details:</h4>
               <div class="row">
                  <table class="table table-bordered text-1 table-sm"  >
                     <thead>
                        <tr style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Description</th>
                           <th>Quantity</th>
                           <th>Unit</th>
                           <th>Rate</th>
                           <th>Total Amount</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                           <td class="text-center">-</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
                  <div class="row" style="margin-top: 5%;">
                     <!-- Fare Details -->
                     <div class="col-md-3">
                        <h4 class="mt-2" style="font-size:15px;"><b>RECIEVED BY:</b></h4>
                     </div>
                     <div class="col-md-3 text-right">
                        <h4 class="mt-2" style="font-size:15px;"><b>PREPARED BY:</b></h4>
                     </div>
                     <div class="col-md-3 text-right">
                        <h4 class="mt-2" style="font-size:15px;"><b>APPROVED BY:</b></h4>
                     </div>
                     <div class="col-md-3 text-right">
                        <h4 class="mt-2" style="font-size:15px;"><b>AUTHORISED SIGNATORY</b></h4>
                     </div>
                  </div>
                  <br>
                  <!-- Footer -->
            </main>
            </div>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesTransactionPrint">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script>  
   
   //$('#printInvoice').click(function(){
    //   Popup($('.invoice')[0].outerHTML);
    //   function Popup(data) 
    //   {
    //       window.print();
    //       return true;
    //   }
    //   });


    //   document.getElementById("doPrint").addEventListener("click", function() {
    //         var printContents = document.getElementById('printsdiv').innerHTML;
    //         var originalContents = document.body.innerHTML;
    //         document.body.innerHTML = printContents;
    //         window.print();
    //         document.body.innerHTML = originalContents;
    //   });
      // var totalCol = 0;
      // $(document).ready(function(){
      //       var result = [];
      //       $('table tr').each(function(){
      //          $('td', this).each(function(index, val){
      //             if(!result[index]) result[index] = 0;
      //             result[index] += parseFloat($(val).text());
      //          });
      //       });
      //       $('table').append('<tr><td class="text-right"><strong>Total : </strong></td></tr>');
      //       $(result).each(function(){
                
      //           totalCol = this.toFixed(2);
               
      //          $('table tr').last().append('<td class="text-center"><strong>'+totalCol+'</strong></td>')
      //       });
      // });
      
   </script>
</html>