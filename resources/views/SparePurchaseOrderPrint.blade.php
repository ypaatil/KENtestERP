<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
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
                  <h4 class="mb-0">Spare Purchase Order</h4>
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
               </style>
               <div id="printInvoice">
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4" style="border: 1px solid; padding-top:5px; text-align:center;"   >
                        <p><img src="../logo/ken.jpeg"  alt="Ken Global Designs Pvt. Ltd." height="150" width="230"> </p>
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <b>KEN Global Designs Pvt. Ltd.</b></br>
                        GAT NO 298/299,A/P Kondigre, </br> Kolhapur, Maharashtra, 416101
                        GSTIN No: 27ABCCS7591Q1ZD</br>
                        State Code/ Name : 27 - Maharastra    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <p><b>  PO No:  </b>    {{ $SparePurchaseList[0]->pur_code }}</p>
                        <b>PO Date: </b>  {{ date('d-m-Y',strtotime($SparePurchaseList[0]->pur_date)) }}</br>
                        <br>
                     </div>
                  </div>
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4"  style="border: 1px solid;" > &nbsp;</div>
                     <div  class="col-md-4"  style="border: 1px solid;">
                        <b>To:</b> </br>
                        <b>{{ $SparePurchaseList[0]->ac_name1 }}</b> </br>
                        {{ $SparePurchaseList[0]->address }}</br>
                        <b> GSTIN No:</b> {{ $SparePurchaseList[0]->gst_no }}</br>
                        <b> PAN NO:</b> {{ $SparePurchaseList[0]->pan_no }}    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <b> Buyer Delivery Address :</b></br>   
                        <b>KEN GLOBAL DESIGNS PRIVATE LIMITED</b></br>    
                        {{ $SparePurchaseList[0]->deliveryAddress }}</br>
                        <b>Delivery Date :</b> {{ date('d-m-Y',strtotime($SparePurchaseList[0]->delivery_date)) }} 
                     </div>
                  </div>
               </div>
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;">We would like to place and confirm you following below order</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr  style="background-color:#eee; text-align:center;">
                           <th>Classification</th>
                           <th>ITEM CODE</th>
                           <th>PRODUCT DESCRIPTION</th>
                           <th>HSN/SAC Code</th>
                           <th>Qty</th>
                           <th>UOM</th>
                           <th>Rate</th>
                           <th>Taxable Amount</th>
                           <th>Total Amount</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                     
                        $detailpurchase =DB::select("SELECT  spare_purchase_order_detail.spare_item_code, totalQty,
                        spare_item_master.item_name, spare_item_master.item_description,class_name,machine_type_master.machinetype_name, machine_make_master.machine_make_name, machine_model_master.mc_model_name,
                        spare_item_master.dimension, spare_item_master.hsn_code, spare_purchase_order_detail.unit_id, sum(item_qty) as item_qty,
                        spare_purchase_order_detail.item_rate, spare_purchase_order_detail.disc_per, sum(disc_amount) as disc_amount,spare_purchase_order_detail.
                        pur_cgst, sum(camt) as camt, spare_purchase_order_detail.pur_sgst, sum(samt) as samt, spare_purchase_order_detail.pur_igst, 
                        sum(iamt) as iamt, sum(amount) as amount, freight_hsn, sum(freight_amt) as freight_amt,unit_master.unit_name, 
                        sum(total_amount) as total_amount FROM spare_purchase_order_detail
                        left outer join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code
                        left outer join machine_type_master on machine_type_master.machinetype_id=spare_item_master.machinetype_id
                        left outer join machine_make_master on machine_make_master.mc_make_Id=spare_item_master.mc_make_Id
                        left outer join machine_model_master on machine_model_master.mc_model_id=spare_item_master.mc_model_id
                        left outer join classification_master on classification_master.class_id=spare_item_master.class_id
                        LEFT outer join unit_master on unit_master.unit_id=spare_purchase_order_detail.unit_id
                        WHERE  pur_code='".$SparePurchaseList[0]->pur_code."'
                        group by spare_purchase_order_detail.spare_item_code");
                       
                        $no=1; 
                        $freight_hsn=0;
                        $freight_amt=0;
                        $pur_cgst=0;
                        $camt=0;
                        $pur_sgst=0;
                        $samt=0;
                        $pur_igst=0;
                        $iamt=0;
                        $Net_Amount=0;
                        @endphp
                        @foreach($detailpurchase as $rowDetail)  
                        @if($rowDetail->spare_item_code != '')
                        <tr>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->spare_item_code }}</td>
                           <td nowrap>
                                <div class="row">
                                     <div class="col-md-8">{{ $rowDetail->item_name }} </br>
                                        <b>Machine Type : </b>{{$rowDetail->machinetype_name}}</br>
                                        <b>Machine Make : </b>{{$rowDetail->machine_make_name}}</br>
                                        <b>Machine Model : </b>{{$rowDetail->mc_model_name}}</br>
                                        <b>Part Name : </b>{{$rowDetail->dimension}}
                                     </div>
                                </div>
                           </td>
                           <td>{{ $rowDetail->hsn_code }}</td>
                           <td style="text-align:right;">{{ money_format('%!i',round($rowDetail->item_qty,2))}}</td>
                           <td>{{ $rowDetail->unit_name }}<br>
                           <td>{{ number_format($rowDetail->item_rate,2) }}</td>
                           <td style="text-align:right;">{{ money_format('%!i', round($rowDetail->amount,2)) }}</td>
                           <td style="text-align:right;">{{ money_format('%!i',round($rowDetail->total_amount,2)) }}</td>
                        </tr>
                        @endif
                        @php 
                        $freight_hsn=$rowDetail->freight_hsn;
                        $freight_amt=$rowDetail->freight_amt;
                        $pur_cgst=$rowDetail->pur_cgst + $pur_cgst;
                        $camt=$rowDetail->camt + $camt;
                        $pur_sgst=$rowDetail->pur_sgst + $pur_sgst;
                        $samt=$rowDetail->samt + $samt;
                        $pur_igst=$rowDetail->pur_igst + $pur_igst;
                        $iamt=$rowDetail->iamt + $iamt;
                        $Net_Amount=$Net_Amount+round($rowDetail->total_amount,2);
                        $no=$no+1; @endphp
                        @endforeach
                        <tr>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th style="text-align:right;">{{money_format('%!i',round($SparePurchaseList[0]->total_qty,2))}}</th>
                           <th> </th>
                           <th> </th>
                           <th style="text-align:right;">{{money_format('%!i',round($SparePurchaseList[0]->Gross_amount,2))}} </th>
                           <th style="text-align:right;">{{money_format('%!i',round($Net_Amount,2))}} </th>
                        </tr>
                        @php
                        $number =  round($SparePurchaseList[0]->Net_amount);
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
                        while ($i < $digits_1) {
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
                              <th rowspan="2"  class="font-weight-600" style="text-align:left;">GROSS AMOUNT </th>
                              <th rowspan="2"  class="font-weight-600" style="text-align:right;">{{ money_format('%!i',round($SparePurchaseList[0]->Gross_amount))}}</th>
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
                           $hsnpurchase =DB::select("SELECT spare_item_master.hsn_code , spare_purchase_order_detail.pur_cgst ,spare_purchase_order_detail.pur_sgst ,
                           spare_purchase_order_detail.pur_igst , sum(spare_purchase_order.Gst_amount) as Gst_amount, sum(spare_purchase_order_detail.`camt`) as camt,
                           sum(spare_purchase_order_detail.`samt`) as samt,sum(spare_purchase_order_detail.`iamt`) as iamt, 
                           sum(spare_purchase_order_detail.`amount`) as gross_amount, sum(spare_purchase_order_detail.`total_amount`) as total_amount 
                           FROM `spare_purchase_order_detail` 
                           inner join spare_purchase_order on spare_purchase_order.`pur_code`=spare_purchase_order_detail.`pur_code` 
                           inner join spare_item_master on spare_item_master.spare_item_code=spare_purchase_order_detail.spare_item_code 
                           where spare_purchase_order_detail.`pur_code`='".$SparePurchaseList[0]->pur_code."' group by hsn_code , 
                           spare_purchase_order_detail.pur_cgst ,spare_purchase_order_detail.pur_sgst , spare_purchase_order_detail.pur_igst
                           ");
                           $a=1;$b=0; $c=-1; 
                           @endphp
                           @foreach($hsnpurchase as $rowtax) 
                           <tr class="font-weight-600 text-right">
                              <td class="font-weight-600 text-center">{{ $rowtax->hsn_code}}</td>
                              <td class="font-weight-600 text-right">{{ round($rowtax->gross_amount,2)}}</td>
                              <td class="font-weight-600 text-center">{{ $rowtax->pur_cgst}}%</td>
                              <td class="font-weight-600">{{ round($rowtax->camt,2)}}</td>
                              <td class="font-weight-600 text-center">{{ $rowtax->pur_sgst}}%</td>
                              <td class="font-weight-600">{{ round($rowtax->samt,2)}}</td>
                              <td class="font-weight-600 text-center">{{ $rowtax->pur_igst}}%</td>
                              <td class="font-weight-600">{{ round($rowtax->iamt,2)}}</td>
                              <td class="font-weight-600" style="text-align:right;">{{ (round($rowtax->camt,2)+round($rowtax->samt,2)+round($rowtax->iamt,2))}}</td>
                              @if($a==1) 
                              <td   class="font-weight-600" style="text-align:left;">TOTAL TAX</td>
                              <td   class="font-weight-600">{{ $SparePurchaseList[0]->Gst_amount}}</td>
                              @endif
                              @php $a=$a+1; @endphp
                              @if($b==1) 
                              <td style="text-align:left;" class="font-weight-600">Other Charges</td>
                              <td style="text-align:right;" class="font-weight-600">0</td>
                              @endif 
                              @php $b=$b+1; @endphp
                              @if($c==1)
                              <td colspan="1"  class="font-weight-600" style="text-align:left;">SUB TOTAL </td>
                              <td class="font-weight-600" style="text-align:right;">{{ ($SparePurchaseList[0]->Gross_amount+$SparePurchaseList[0]->Gst_amount) }}</td>
                              @endif
                              @php   $c=$c+1;   @endphp
                           </tr>
                           @endforeach
                           @php 
                           $roundoff=0;
                           $roundoff=$SparePurchaseList[0]->Net_amount-($SparePurchaseList[0]->Gross_amount+$SparePurchaseList[0]->Gst_amount);
                           @endphp
                           <tr class="font-weight-600 text-right">
                              <th> TOTAL</th>
                              <th> {{ $SparePurchaseList[0]->Gross_amount}}</th>
                              <th></th>
                              <th>{{ $camt}} </th>
                              <th> </th>
                              <th>{{ $samt}}</th>
                              <th> </th>
                              <th>{{ $iamt}}</th>
                              <th> {{ $SparePurchaseList[0]->Gst_amount}}</th>
                              <td colspan="1" class="font-weight-600" style="text-align:left;">ROUNDOFF </td>
                              <td class="font-weight-600" style="text-align:right;">{{ round($roundoff,2)}}</td>
                           </tr>
                           <tr>
                              <td colspan="5" class="font-weight-600">  </td>
                              <td colspan="4" class="font-weight-600"> </td>
                              <td colspan="1" class="font-weight-600" style="text-align:left;"> AMOUNT</td>
                              <td class="font-weight-600" style="text-align:right;">
                                 <h4>{{ money_format('%!i',$SparePurchaseList[0]->Net_amount) }}  </h4>
                              </td>
                           </tr>
                           <tr>
                              <td colspan="11" style="text-transform: uppercase;"><span class="font-weight-600" >Amount In Rupees:</span> {{ $result . "Rupees  Only"}}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <div class="row">
                     <!-- Fare Details -->
                     <div class="col-md-9">
                        <h4 class="text-4 mt-2">AGREED TERMS & CONDITIONS</h4>
                        @php  echo  htmlspecialchars_decode($SparePurchaseList[0]->terms_and_conditions); @endphp
                     </div>
                     <div class="col-md-3">
                        <br/>
                        <br/>
                     </div>
                  </div>
                  <!-- Footer -->
                  <footer> 
                     <div class="">
                        <table class="table table-bordered text-1 table-sm">
                           <tbody>
                              <tr>
                                 <td rowspan="2"><span class="font-weight-600">COMPANY SEAL :</span></td>
                                 <td style="text-align:right;">
                                    <span  class="font-weight-600">For KEN GLOBAL DESIGNS PRIVATE LIMITED</span>
                                    <br>
                                    <br>
                                    <br>
                                    <p style="text-align:right;" class="mt-4" style="margin-bottom: 0px;">Authorized Signature</p>
                                 </td>
                              </tr>
                              <tr>
                                 <td style="text-align:right;"><span class="font-weight-600">SUBJECT TO ICHALKARANJI JURISDICTION</span>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <div class="col-md-12"><span class="font-weight-600">Remark : </span>{{$SparePurchaseList[0]->narration}}</div>
                        <br>
                        <br>
                     </div>
                     <div class="btn-group btn-group-sm d-print-none"> <a  href="javascript:window.print()" class="btn btn-info border text-white shadow-none"> Print</a> </div>
                  </footer>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Purchase Order</p>
      <p class="text-center d-print-none"><a href="/PurchaseOrder">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script>  $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
   </script>
</html>