<!DOCTYPE html>
<html lang="en">
    @php 
        setlocale(LC_MONETARY, 'en_IN'); 
    @endphp
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Finishing Billing Invoice</title>
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
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
         <div class="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row">
                  <div class="col-md-4">
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Global Designs Pvt. Ltd." height="130" width="230"> </p>
                  </div>
                  <div class="col-md-6" class="text-center">
                     <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
                     <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
                     <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6> 
                  </div> 
               </div>
               <div class="row" style="justify-content:center;"> 
                     <h5><b>INVOICE</b></h5> 
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
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4" style="border-right:1px solid #000000;">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Supplier Name:  </b> <span style="display: inline-block;text-align: right;"> {{$finishingBillingMaster->ac_short_name}} </span></br>     
                        <p>    				
                            <b>Address :</b>  {{$finishingBillingMaster->address}} 	 
                        </p>
                     </div>
                     <div  class="col-md-3" style="border-right:1px solid #000000;" >
                        <b style="display: inline-block;text-align: left;" class="mt-1">Invoice No: </b>  <span style="display: inline-block;text-align: right;">{{$finishingBillingMaster->bill_no}}</span></br> 
                     </div>
                     <div  class="col-md-5" style="border-right:1px solid #000000;">    
                        <b style="display: inline-block;text-align: left;" class="mt-1">Date: </b>  <span style="display: inline-block;text-align: right;">{{date("d-m-Y", strtotime($finishingBillingMaster->finishing_billing_date))}}</span></br>      
                     </div>
                  </div>
                  <div class="row" style="border: #000000 solid 1px;">
                     <div  class="col-md-4" style="border-right:1px solid #000000;">
                        <b style="display: inline-block;text-align: left;" class="mt-1">Buyer (Bill To):  </b> <span style="display: inline-block;text-align: right;"> - </span></br>  
                        <p>
                            Ken Global Designs Pvt Ltd.</br>  						
                            <b>Address :</b> </br>  						
                            <b>Works Address :- </b>Gat # 298& 299, At: Kondigre,</br>  						
                            <b>Tal:</b> Shirol, <b>Dist.:</b> Kolhapur – 416 102</br>  						
                            <b>GSTN/UIN       :-</b>27ABCCS7591Q1ZD</br>  						
                            <b>PAN/ IT NO     :-</b></br>   						
                            <b>State Name     :-</b> Maharashtra, <b>Code :- </b>27		
                        </p>
                     </div>
                     <div  class="col-md-8" style="border-right:1px solid #000000;" >
                        <b style="display: inline-block;text-align: left;" class="mt-1">Supplier Bank Details: </b><span style="display: inline-block;text-align: right;">- </span></br> 
                        <p> 
                            <b>Bank name  :-  </b> {{$finishingBillingMaster->bank_name}}<br/>			
                            <b>A/C no.   :-  </b> {{$finishingBillingMaster->account_no}}<br/>			
                            <b>IFSC      :- </b>   {{$finishingBillingMaster->ifsc_code}}					
                        </p>
                     </div> 
                  </div>
               </div> 
               <div class="row">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr class="text-center">
                           <th>Sr No</th>
                           <th>KDPL</th>
                           <th>Perticulars</th>  
                           <th>HSN</th>
                           <th>Qty</th>
                           <th>Rate</th>
                           <th>Amount</th>
                        </tr>
                        <tr class="text-center">
                           <th></th>
                           <th></th>
                           <th>{{$finishingBillingMaster->perticular_name}} Invoice</th>  
                           <th></th>
                           <th></th>
                           <th></th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody> 
                        @php
                            $no = 1;
                            $total_qty = 0;
                            $total_amt = 0;
                        @endphp
                        @foreach($finishingBillingDetail as $row)
                            <tr>
                               <td>{{$no++}}</td>
                               <td>{{$row->sales_order_no}}</td>
                               <td>{{$row->ac_short_name}} - {{$row->brand_name}}</td> 
                               <td>-</td>
                               <td class="text-right">{{money_format("%!.0n", $row->packing_qty)}}</td>
                               <td class="text-right">{{money_format("%!.2n", $row->rate) }}</td> 
                               <td class="text-right">{{ money_format("%!.2n", $row->packing_qty*$row->rate)}}</td>
                            </tr> 
                        @php 
                            $total_qty += $row->packing_qty;
                            $total_amt += ($row->packing_qty*$row->rate);
                        @endphp
                        @endforeach
                     </tbody> 
                      <tfoot>
                          <tr> 
                               <th class="text-right" colspan="4">Total</th>
                               <th class="text-right">{{money_format("%!.0n", $total_qty)}}</td>
                               <th class="text-right"></td> 
                               <th class="text-right">{{ money_format("%!.2n", $total_amt)}}</td>
                            </tr> 
                      </tfoot> 
                  </table>
               </div>
              <div class="row">
                 <!-- Fare Details -->
                 <div class="col-md-3">
                    <h4 class="text-4 mt-2">Prepared By:</h4>
                 </div>
                 <div class="col-md-3">
                    <h4 class="text-4 mt-2">Checked By:</h4>
                 </div>
                 <div class="col-md-3">
                    <h4 class="text-4 mt-2">Approved By:</h4>
                 </div>
                 <div class="col-md-3">
                    <h4 class="text-4 mt-2">Authorized By:</h4>
                 </div>
              </div>
              </br>
              </br>
              </br>
              <!-- Footer -->
              <footer  >
                 <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:void(0);" onclick="PrintPage();" class="btn btn-info border text-white shadow-none"> Print</a> </div>
              </footer>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/FinishingBilling">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
   <script>  
   
    function PrintPage()
    {
        
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
    }
      
      
   </script>
</html>