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
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
         <div class="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Return Packing Inhouse</h4>
               </center>
               <!-- Item Details -->
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
                  .text-1
                  {
                      font-size: 15px !important;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4" style="border: 1px solid; padding-top:5px; text-align:center;"   >
                        <p><img src="https://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"></br></br>
                        <b>KEN Global Designs Pvt. Ltd.</b></br>
                        GAT NO 298/299,A/P Kondigre, </br> Kolhapur, Maharashtra, 416101
                        GSTIN No: 27ABCCS7591Q1ZD</br>
                        State Code/ Name : 27 - Maharastra    
                     </div>
                     <div class="col-md-4" style="border: 1px solid;"  ></br></br>
                        <p><b>Sale Code: </b>{{$MasterList[0]->rpki_code}}</p>
                        <b>Sale Date: </b>{{$MasterList[0]->rpki_date}}</br>
                     </div>
                  </div>
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4"  style="border: 1px solid;" > &nbsp;</div>
                     <div  class="col-md-4"  style="border: 1px solid;">
                        <b>To:</b> -</br>
                        <b>Address:</b>-</br>
                        <b> GSTIN No:</b>-</br>
                        <b> PAN NO:</b> -    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                         <b>To:</b> -</br>
                        <b>Address:</b>-</br>
                        <b>Delivery Date :</b>-
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;">We would like to place and confirm you following below order</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh;" id="detailTable" >
                     <thead>
                        <tr style="background-color:#eee;">
                           <th>SrNo.</th>
                           <th nowrap>Main Style Category</th>
                           <th nowrap>Style Description</th>
                           <th nowrap>Sales Order No.</th>
                           <th>Color</th>
                           @foreach ($SizeDetailList as $sz) 
                           <th>{{$sz->size_name}}</th>
                           @endforeach
                           <th>Qty</th>
                           <th>Rate</th>
                           <th nowrap>Taxable Value</th>
                           <th nowrap>C & SGST/IGST</th>
                           <th nowrap>Total Value</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                            $srno = 1;
                            $totalQty = 0;
                            $total_material_value = 0;
                            $total_value = 0;
                            $totalAmount = 0;
                            $result = "";
                        @endphp 
                        @foreach($MasterList as $buyerData)
                        @php
                            
                           if($buyerData->tax_type_id == 1)
                           {
                               $taxAmt = $buyerData->samt;
                           }
                           else
                           {
                               $taxAmt = $buyerData->iamt;
                           }
                           
                            $SizeQtyList=explode(',', $buyerData->size_qty_array)
                        @endphp   
                        <tr>
                           <td>{{$srno++}}</td>
                           <td>{{$buyerData->mainstyle_name}}</td>
                           <td>{{$buyerData->style_description}}</td>
                           <td>{{$buyerData->sales_order_no}}</td>
                           <td>{{$buyerData->color_name}}</td>
                           @foreach ($SizeQtyList as $szQty) 
                           <td class="text-right">{{$szQty}}</td>
                           @endforeach
                           <td class="text-right">{{$buyerData->size_qty_total}}</td>
                           <td class="text-right">{{$buyerData->rate}}</td>
                           <td class="text-right">{{$buyerData->size_qty_total * $buyerData->rate}}</td>
                           <td class="text-right">{{$taxAmt}}</td>
                           <td class="text-right">{{($buyerData->size_qty_total * $buyerData->rate) + $taxAmt}}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div id="breakdown">
                     <table class="table table-bordered text-3 table-sm" >
                        <thead style="text-align:center; color:black;">
                           <tr>
                              <th rowspan="2">HSN NO</th>
                              <th rowspan="2">TAXABLE VALUE</th>
                              <th colspan="2">CENTRAL TAX</th>
                              <th colspan="2">STATE TAX</th>
                              <th colspan="2">INTEGRATED TAX</th>
                              <th rowspan="2">TOTAL TAX AMOUNT</th>
                              <th rowspan="2" class="text-right">GROSS AMOUNT </th>
                              <th rowspan="2" class="text-right">{{number_format($total_material_value,2)}}</th>
                           </tr>
                           <tr>
                              <th>RATE</th>
                              <th>AMT</th>
                              <th>RATE</th>
                              <th>AMT</th>
                              <th>RATE</th>
                              <th>AMT</th>
                           </tr>
                        <tbody style="text-align:center;font-weight:bold; color:black;">
                           @php
                                $totalTaxAmt = 0;
                                $totalCTaxAmt = 0;
                                $totalSTaxAmt = 0;
                                $totalITaxAmt = 0;
                                $totalATaxAmt = 0;
                           @endphp 
                           @foreach($MasterList as $buyerData)
                           <tr class="font-weight-600 text-right">
                              <td class="text-center">{{$buyerData->hsn_code}}</td>
                              <td class="text-right">{{number_format($buyerData->size_qty_total * $buyerData->rate,2)}}</td>
                              <td class="text-right">{{$buyerData->cgst}}</td>
                              <td class="text-right">{{number_format($buyerData->camt,2)}}</td>
                              <td class="text-right">{{$buyerData->sgst}}</td>
                              <td class="text-right">{{number_format($buyerData->samt,2)}}</td>
                              <td class="text-right">{{$buyerData->igst}}</td>
                              <td class="text-right">{{number_format($buyerData->iamt,2)}}</td>
                              <td class="text-right">{{number_format($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                              <td class="text-right">TOTAL TAX :</td>
                              <td class="text-right">{{number_format($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                           </tr>
                            @php
                                $totalAmount = $totalAmount  + ($buyerData->camt + $buyerData->samt + $buyerData->iamt);
                                $totalTaxAmt = $totalTaxAmt + ($buyerData->size_qty_total * $buyerData->rate);
                                $totalCTaxAmt = $totalCTaxAmt + $buyerData->camt;
                                $totalSTaxAmt = $totalSTaxAmt + $buyerData->samt;
                                $totalITaxAmt = $totalITaxAmt + $buyerData->iamt;
                                $totalATaxAmt = $totalATaxAmt + ($buyerData->camt + $buyerData->samt + $buyerData->iamt);
                            @endphp
                            @endforeach
                          <tr class="font-weight-600">
                              <th class="text-right">TOTAL :</th>
                              <th class="text-right">{{number_format($totalTaxAmt,2)}}</th>
                              <th></th>
                              <th class="text-right">{{number_format($totalCTaxAmt,2)}}</th>
                              <th></th>
                              <th class="text-right">{{number_format($totalSTaxAmt,2)}}</th>
                              <th> </th>
                              <th class="text-right">{{number_format($totalITaxAmt,2)}}</th>
                              <th class="text-right">{{number_format($totalATaxAmt,2)}}</th>
                              <td colspan="1" class="text-right">ROUNDOFF :  </td>
                              <td class="text-right">0</td>
                           </tr>
                           <tr>
                              <td colspan="5"></td>
                              <td colspan="4"></td>
                              <td colspan="1" class="text-right">AMOUNT :</td>
                              <td class="text-right">{{number_format($totalAmount + $total_material_value,2)}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <footer>
                        <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Return Packing Inhouse</p>
      <p class="text-center d-print-none"><a href="/ReturnPackingInhouseMaster">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  
   
      $(document).ready(function(){
            var result = [];
            $('#detailTable tr').each(function(){
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
            $('#detailTable').append('<tr><td colspan="5" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
               $('#detailTable tr').last().append('<td class="text-right"><strong>'+this.toFixed(2)+'</strong></td>')
            });
      });
    // $('#printInvoice').click(function(){
    //   Popup($('.invoice')[0].outerHTML);
    //   function Popup(data) 
    //   {
    //       window.print();
    //       return true;
    //   }
    //   });
      
      
   </script>
</html>