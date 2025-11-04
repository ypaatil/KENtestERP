@php setlocale(LC_MONETARY, 'en_IN'); @endphp  
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
                  <h4 class="mb-0">Sale Transaction</h4>
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
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
                  .text-1
                  {
                      font-size: 12px !important;
                  }
                  th,td{
                      font-size: 12px !important;
                  }
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4" style="border: 1px solid; padding-top:5px; text-align:center;"   >
                        <p><img src="https://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"></br></br>
                        <b>KEN Global Designs Pvt. Ltd.</b></br>
                        GAT NO 298/299,A/P Kondigre, </br> Kolhapur, Maharashtra, 416101
                        GSTIN No: 27ABCCS7591Q1ZD</br>
                        State Code/ Name : 27 - Maharastra    
                     </div>
                     <div class="col-md-4" style="border: 1px solid;"  ></br></br>
                        <p><b>Sale Code: </b>{{$BuyerDetail->sale_code}}</p>
                        <b>Sale Date: </b> {{$BuyerDetail->sale_date}}</br>
                     </div>
                  </div>
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4"  style="border: 1px solid;" > &nbsp;</div>
                     <div  class="col-md-4"  style="border: 1px solid;">
                        <b>To:</b> {{$BuyerDetail->ac_name}}</br>
                        <b>Address:</b>{{$BuyerDetail->address}}</br>
                        <b> GSTIN No:</b>{{$BuyerDetail->gst_no}}</br>
                        <b> PAN NO:</b> {{$BuyerDetail->pan_no}}    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                         <b>To:</b> {{$BuyerDetail->ac_name}}</br>
                        <b>Address:</b>{{$BuyerDetail->address}}</br>
                        <b>Delivery Date :</b> {{$BuyerDetail->sale_date}}
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:15px;">We would like to place and confirm you following below order</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th>SrNo.</th>
                           <th>Main Style Category</th>
                           <th>Style Description</th>
                           <th>Sales Order No.</th>
                           <th>HSN/SAC Code</th>
                           <th>UOM</th>
                           <th>Qty</th>
                           <th>Rate Per Item</th>
                           <th>Total Material Value</th>
                           <th>Taxable Value</th>
                           <th>C & SGST/IGST</th>
                           <th>Total Value</th>
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
                        @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                        @php
                            
                           if($buyerData->tax_type_id == 1)
                           {
                               $taxAmt = $buyerData->samt;
                           }
                           else
                           {
                               $taxAmt = $buyerData->iamt;
                           }
                        @endphp   
                        <tr>
                           <td>{{$srno++}}</td>
                           <td>{{$buyerData->mainstyle_name}}</td>
                           <td>{{$buyerData->style_description}}</td>
                           <td>{{$buyerData->sales_order_no}}</td>
                           <td>{{$buyerData->hsn_code}}</td>
                           <td>{{$buyerData->unit_name}}</td>
                           <td class="text-right">{{money_format("%!.0n",round($buyerData->order_qty,2))}}</td>
                           <td class="text-right">{{money_format("%!.0n",round($buyerData->order_rate,2))}}</td>
                           <td class="text-right">{{money_format("%!.0n",round(($buyerData->order_qty * $buyerData->order_rate),2))}}</td>
                           <td class="text-right">{{money_format("%!.0n",round(($buyerData->order_qty * $buyerData->order_rate),2))}}</td>
                           <td class="text-right">{{money_format("%!.0n",round($taxAmt,2))}}</td>
                           <td class="text-right">{{money_format("%!.0n",round(round(($buyerData->order_qty * $buyerData->order_rate) + $taxAmt),2))}}</td>
                        </tr
                        @php 
                            $totalQty = $totalQty + $buyerData->order_qty;
                            $total_material_value = $total_material_value + ($buyerData->order_qty * $buyerData->order_rate);
                            $total_value = $total_value + ($buyerData->order_qty * $buyerData->order_rate) + $taxAmt;

                            $number =  round($total_value);
                            $no = $number;
                            $point = round($number - $no, 2) * 100;
                            $hundred = null;
                            $digits_1 = strlen($no);
                            $i = 0;
                            $str = array();
                            $words = array('0' => '', '1' => 'one', '2' => 'two',
                                '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                                '7' => 'seven', '8' => 'eight', '9' => 'nine',
                                '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                                '13' => 'thirteen', '14' => 'fourteen',
                                '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                                '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
                                '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                                '60' => 'sixty', '70' => 'seventy',
                                '80' => 'eighty', '90' => 'ninety');
                            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
                            while ($i < $digits_1) 
                            {
                                 $divider = ($i == 2) ? 10 : 100;
                                 $number = floor($no % $divider);
                                 $no = floor($no / $divider);
                                 $i += ($divider == 10) ? 1 : 2;
                                 if ($number) {
                                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                    $str [] = ($number < 21) ? $words[$number] .
                                        " " . $digits[$counter] . $plural . " " . $hundred
                                        :
                                        $words[floor($number / 10) * 10]
                                        . " " . $words[$number % 10] . " "
                                        . $digits[$counter] . $plural . " " . $hundred;
                                 } else $str[] = null;
                            }
                            $str = array_reverse($str);
                            $result = implode('', $str);
                            $points = ($point) ?
                                    "." . $words[$point / 10] . " " . 
                                      $words[$point = $point % 10] : '';
 
                        @endphp
                        @endforeach
                        <tr>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th></th>
                           <th> </th>
                           <th style="text-align:right;"> Total:</th>
                           <th  class="text-right">{{number_format($totalQty,2)}}</th>
                           <th> </th>
                           <th  class="text-right">{{ number_format($total_material_value,2)}}</th>
                           <th  class="text-right">{{ number_format($total_material_value,2)}}</th>
                           <th> </th>
                           <th  class="text-right">{{ number_format($total_value,2)}}</th>
                        </tr>
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
                           @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                           <tr class="font-weight-600 text-right">
                              <td class="text-right">{{$buyerData->hsn_code ? $buyerData->hsn_code : "-"}}</td>
                              <td class="text-right">{{number_format($buyerData->order_qty * $buyerData->order_rate,2)}}</td>
                              <td class="text-right">{{$buyerData->sale_cgst}}</td>
                              <td class="text-right">{{number_format($buyerData->camt,2)}}</td>
                              <td class="text-right">{{$buyerData->sale_sgst}}</td>
                              <td class="text-right">{{number_format($buyerData->samt,2)}}</td>
                              <td class="text-right">{{$buyerData->sale_igst}}</td>
                              <td class="text-right">{{number_format($buyerData->iamt,2)}}</td>
                              <td class="text-right">{{number_format($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                              <td class="text-right">TOTAL TAX :</td>
                              <td class="text-right">{{number_format($buyerData->camt + $buyerData->samt + $buyerData->iamt,2)}}</td>
                           </tr>
                            @php
                                $totalAmount = $totalAmount  + ($buyerData->camt + $buyerData->samt + $buyerData->iamt);
                                $totalTaxAmt = $totalTaxAmt + ($buyerData->order_qty * $buyerData->order_rate);
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
                           <tr>
                              <td colspan="11" style="text-transform: uppercase;"><span>Amount In Rupees:</span> {{ $result . "Rupees  Only"}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <footer>
                        <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                        <form method="POST" action="{{ route('export.tally.xml') }}">
                            @csrf
                            <input type="hidden" name="sr_no" value="{{$BuyerPurchaseOrderMasterList[0]->sr_no}}">
                            <button type="submit" class="btn btn-success mb-3">Export to Tally XML</button>
                        </form>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Sale Transaction</p>
      <p class="text-center d-print-none"><a href="/SaleTransaction">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  
   
    $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
      
   </script>
</html>