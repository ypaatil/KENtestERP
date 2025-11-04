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
             }
         }
         th,td{
             font-size: 15px;
         }
         .text-1{
            font-size: 12px!important;
         }
         .txtsize{
            font-size: 15px;
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
                     <p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="" height="100" width="230"> </p>
                  </div>
                  <div class="col-md-7 text-center" style="margin-top: 2%;margin-left: -4%;">
                     <h5 class="mb-0" style="font-weight:bold;text-transform: uppercase;"> {{$FirmDetail->firm_name}}</h5>
                     <h6 class="mb-0" style="font-weight:bold;margin-top: 5%;">Gate Pass/Delivery Challan</h6>
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
                  <div class="row" >
                  <div class="col-md-6" style="border: #000000 solid 1px; padding: 16px;">
                       <h6 class="mb-0" style="font-weight:bold;text-transform: uppercase;"> {{$FirmDetail->firm_name}}</h6>
                       <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                       <b style="display: inline-block;text-align: left;" class="mt-1">GSTIN NO:</b>    <span style="display: inline-block;text-align: right;">{{$FirmDetail->gst_no}} </span><br/>
                       
                  </div>
                  <div class="col-md-6" style="border: #000000 solid 1px;">
                        <p>
                            <b style="display: inline-block;text-align: left;" class="mt-1">Department:</b>
                            <span style="display: inline-block;">{{ $DeliveryChallanMasterData->dept_name}} </span><br/>
                        
                     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Issue No:  </b> <span style="display: inline-block;text-align: right;"> {{$DeliveryChallanMasterData->issue_no}}  </span>   <br/>
                      
                        <b style="display: inline-block;text-align: left;" class="mt-1">Issue Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y",strtotime($DeliveryChallanMasterData->issue_date))}}  </span>  <br/>
                        
                        <b style="display: inline-block;text-align: left;" class="mt-1">Expected Return Date:  </b> <span style="display: inline-block;text-align: right;"> {{ date("d-m-Y",strtotime($DeliveryChallanMasterData->return_date))}}  </span><br/><br/>
                       </p>
                     </div>
                  <div class="col-md-6" style="border: #000000 solid 1px;">
                      <p> 
                        @php
                        
                            if($DeliveryChallanMasterData->reciever_type == 1)
                            {
                                $to = $DeliveryChallanMasterData->otherBuyerorVendor;
                            }
                            else
                            {
                                $to = $DeliveryChallanMasterData->ac_name;
                            }
                            
                        @endphp
                          <b style="display: inline-block;text-align: left;">To: </b> <span style="display: inline-block;">{{ $to }}</span><br/><strong>Address: </strong><span style="display: inline-block;">{{ $DeliveryChallanMasterData->to_location}} </span>
                          <br/>
                          <b style="display: inline-block;text-align: left;" class="mt-1">GSTIN NO:</b>    <span style="display: inline-block;text-align: right;">{{$DeliveryChallanMasterData->gst_no}} </span><br/>
                      </p>
                  </div>
                   <div class="col-md-6" style="border: #000000 solid 1px;">
                     <p>
                        <b style="display: inline-block;text-align: left;" class="mt-1">Type:</b>
                        <span style="display: inline-block;">{{ $DeliveryChallanMasterData->product_type}} </span><br/>
                     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sent Through :  </b><span style="display: inline-block;text-align: right;"> {{$DeliveryChallanMasterData->sent_through}}  </span>   <br/>
                     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Transaction Name:  </b>
                        <span style="display: inline-block;text-align: right;"> 
                           @if($DeliveryChallanMasterData->dc_case_id == 1)
                             Returnable
                           @else
                           Non-Returnable
                           @endif
                        </span>   
                     </p>

                  </div>
                </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;"> Details:</h4>
               <div class="row">
                  <table class="table table-bordered text-1 table-sm"  >
                     <thead>
                        <tr style="background-color:#eee; text-align:center;">
                           <th>SrNo</th>
                           <th>Item Description</th>
                           <th>Quantity</th>
                           <th>Unit</th>
                           @if($DeliveryChallanMasterData->issue_case_id == 2)
                           <th>Return Quantity</th>
                           @endif
                           <th>Rate</th>
                           <th>Total Amount</th>
                           <th>Remark</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php  
                           $no=1;
                           $total_amt=0;
                           $totalqty=0;
                           $amt=0;
                           $tamt=0;
                        @endphp
                        @foreach($DeliveryChallanDetailList as $details)
                        <tr>
                           <td class="text-center">{{$no}}</td>
                           <td class="text-center">{{$details->item_description}}</td>
                           <td class="text-center">{{$details->quantity}}</td>
                           <td class="text-center">{{$details->unit_name}}</td>
                           @if($DeliveryChallanMasterData->issue_case_id == 2)
                           <td class="text-center">{{$details->return_quantity}}</td>
                           @endif
                           <td class="text-center">{{$details->rate}}</td>
                           <td class="text-center">{{$details->total_amount}}</td>
                           <td class="text-center">{{$details->remark}}</td>
                        </tr>
                        @endforeach
                        <tr>
                           <td colspan="3"></td>
                           <td class="txtsize"><b>Total Qty: </b> {{ number_format($DeliveryChallanMasterData->total_qty)}}</td>
                           <td></td>
                           <td class="txtsize"><b>Net Amount: </b> {{ number_format($DeliveryChallanMasterData->NetAmount) }}</td>
                           <td></td>
                        </tr>
                        <tr>
                           <td colspan="7" class="txtsize"><strong>Remark : {{$DeliveryChallanMasterData->narration}}</strong></td>
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
      <p class="text-center d-print-none"><a href="/DeliveryChallan">&laquo; Back to List</a></p>
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