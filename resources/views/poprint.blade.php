<!DOCTYPE html>
<html lang="en">
   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Global Desgins Pvt. Ltd.</title>
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
                  <h4 class="mb-0">{{ $title }}</h4>
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
                        <p><img src="../logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="150" width="230"> </p>
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <b>KEN Global Designs Pvt. Ltd.</b></br>
                        GAT NO 298/299,A/P Kondigre, </br> Kolhapur, Maharashtra, 416101
                        GSTIN No: 27ABCCS7591Q1ZD</br>
                        State Code/ Name : 27 - Maharastra    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <p><b>  PO No:  </b>    {{ $poMaster[0]->pur_code }}</p>
                        <b>PO Date: </b>  {{ date('d-m-Y',strtotime($poMaster[0]->pur_date)) }}</br>
                        <b>PO Type:</b>   {{ $poMaster[0]->po_type_name }}</br>
                        @if($poMaster[0]->approveFlag==0) 
                        <!--<b>Purchase Order :</b>  Pending for Appoval -->
                        @elseif($poMaster[0]->approveFlag==1) 
                        <!--<b> Purchase Order :</b>  Approved -->
                        @elseif($poMaster[0]->approveFlag==2)
                        <!--<b> Purchase Order :</b>  Disappoved -->
                        @endif
                        <br>
                        <b> Sales Order No:</b> @php $sono=''; foreach($SalesOrderNo as $rows){ $sono=$sono.$rows->sales_order_no.','; } echo rtrim($sono,','); @endphp
                     </div>
                  </div>
                  @php
                        //DB::enableQueryLog();
                        $BillToData = DB::select("
                                    SELECT ld.* 
                                    FROM purchase_order po
                                    INNER JOIN ledger_details ld
                                        ON ld.sr_no =  po.bill_to
                                    WHERE po.pur_code = ?
                                ", [$poMaster[0]->pur_code]);

                       //dd(DB::getQueryLog());
                        $ShipToData = DB::select("SELECT ledger_details.* FROM purchase_order INNER JOIN ledger_details  ON ledger_details.sr_no = purchase_order.ship_to WHERE purchase_order.pur_code = '".$poMaster[0]->pur_code."'");

                  @endphp
                  <div class="row" style="border: 1px solid;">
                     <div  class="col-md-4"  style="border: 1px solid;" > &nbsp;</div>
                     <div  class="col-md-4"  style="border: 1px solid;">
                        <b>PURCHASE FROM:</b> </br>
                        <b>{{ $BillToData[0]->trade_name ?? "-" }}</b> </br>
                        {{ $BillToData[0]->addr1 ?? "-" }}</br>
                        <b> GSTIN No:</b> {{ $BillToData[0]->gst_no ?? "-" }}</br>
                        <b> PAN NO:</b> {{ $BillToData[0]->pan_no ?? "-" }}    
                     </div>
                     <div  class="col-md-4" style="border: 1px solid;"  >
                        <b>SHIP TO :</b></br>   
                        <b>{{ $ShipToData[0]->trade_name ?? "-" }}</b> </br>
                        {{ $ShipToData[0]->addr1 ?? "-" }}</br>
                        <b> GSTIN No:</b> {{ $ShipToData[0]->gst_no ?? "-" }}</br>
                        <b> PAN NO:</b> {{ $ShipToData[0]->pan_no ?? "-" }} </br>   
                        <b>Delivery Date :</b> {{ date('d-m-Y',strtotime($poMaster[0]->delivery_date)) }} 
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
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
                           <th>Amount With Tax</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php 
                        $detailpurchase =DB::select("SELECT  purchaseorder_detail.item_code, 
                        u1.unit_name as unit1, u2.unit_name as unit2, u3.unit_name as unit3, u4.unit_name as unit4,  conQty, unitIdM, priUnitd, SecConQty,secUnitId, sum(poQty) as poQty, poUnitId, rateM, totalQty,
                        item_master.item_name,item_master.item_image_path, item_master.item_description, item_master.color_name,class_name,
                        item_master.dimension, purchaseorder_detail.hsn_code, purchaseorder_detail.unit_id, sum(item_qty) as item_qty,
                        purchaseorder_detail.item_rate, purchaseorder_detail.disc_per, sum(disc_amount) as disc_amount,purchaseorder_detail.
                        pur_cgst, sum(camt) as camt, purchaseorder_detail.pur_sgst, sum(samt) as samt, purchaseorder_detail.pur_igst, 
                        sum(iamt) as iamt, sum(amount) as amount, freight_hsn, sum(freight_amt) as freight_amt, 
                        sum(total_amount) as total_amount FROM purchaseorder_detail
                        left outer join item_master on item_master.item_code=purchaseorder_detail.item_code
                        left outer join classification_master on classification_master.class_id=item_master.class_id
                        LEFT outer join unit_master u1 on u1.unit_id=purchaseorder_detail.unit_id
                        LEFT outer join unit_master u2 on u2.unit_id=purchaseorder_detail.priUnitd
                        LEFT outer join unit_master u3 on u3.unit_id=purchaseorder_detail.secUnitId
                        LEFT outer join unit_master u4 on u4.unit_id=purchaseorder_detail.poUnitId
                        WHERE  pur_code='".$poMaster[0]->pur_code."'
                        group by purchaseorder_detail.item_code");
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
                        $totalGst_Amount = 0;
                        @endphp
                        @foreach($detailpurchase as $rowDetail)  
                        @if($rowDetail->item_code != '')
                        <tr>
                           <td>{{ $rowDetail->class_name }}</td>
                           <td>{{ $rowDetail->item_code }}</td>
                           <td>
                              <div class="row">
                                 <div class="col-md-8">{{ $rowDetail->item_name }} </br>
                                    <b>Description:</b> {{ $rowDetail->item_description }},</br>
                                    <b>Color:</b> {{ $rowDetail->color_name }},</br>
                                    <b>Width:</b> {{ $rowDetail->dimension }} </br>
                                    @if($rowDetail->poQty!=0)  <b>1:</b>  {{$rowDetail->conQty}}   {{$rowDetail->unit1}}/{{$rowDetail->unit2}}  @endif
                                 </div>
                                 <div class="col-md-4">
                                    <img src="../images/{{ $rowDetail->item_image_path }}" width="70" height="70">
                           </td>
                           </div>
                           </div>
                           <td>{{ $rowDetail->hsn_code }}
                              </br> 
                              @if($rowDetail->poQty!=0) {{$rowDetail->SecConQty}}  {{$rowDetail->unit3}} @endif
                           </td>
                           <td style="text-align:right;">{{ money_format('%!i',round($rowDetail->item_qty,2))}} <br>
                              @if($rowDetail->poQty!=0) {{round($rowDetail->poQty,2)}} {{$rowDetail->unit4}} @endif
                           </td>
                           <td>{{ $rowDetail->unit1 }} <br>
                           <td>{{ number_format($rowDetail->item_rate,2) }} <br>
                              @if($rowDetail->poQty!=0) {{money_format('%!i',$rowDetail->rateM)}}/Box    @endif
                           </td>
                           <td style="text-align:right;">{{ money_format('%!i', round($rowDetail->amount ?? $rowDetail->iamt,2)) }} </td>
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
                        $totalGst_Amount=$totalGst_Amount+round($rowDetail->amount ?? $rowDetail->iamt,2);
                        $no=$no+1; @endphp
                        @endforeach
                        <tr>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th style="text-align:right;">{{money_format('%!i',round($poMaster[0]->total_qty,2))}}</th>
                           <th> </th>
                           <th> </th>
                           <th style="text-align:right;">{{money_format('%!i',round($totalGst_Amount,2))}} </th>
                           <th style="text-align:right;">{{money_format('%!i',round($Net_Amount,2))}} </th>
                        </tr>
                        @php
                        $number =  round($poMaster[0]->Net_amount);
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
                              <th rowspan="2">TAXABLE AMOUNT</th>
                              <th colspan="2">CENTRAL TAX</th>
                              <th colspan="2">STATE TAX</th>
                              <th colspan="2">INTEGRATED TAX</th>
                              <th rowspan="2">TOTAL TAX AMOUNT</th>
                              <th rowspan="2"  class="font-weight-600" style="text-align:left;">TAXABLE AMOUNT</th>
                              <th rowspan="2"  class="font-weight-600" style="text-align:right;">{{ money_format('%!i',round($poMaster[0]->Gross_amount))}}</th>
                           </tr>
                           <tr>
                              <th  >RATE</th>
                              <th  >AMT</th>
                              <th >RATE</th>
                              <th  >AMT</th>
                              <th  >RATE</th>
                              <th  >AMT</th>
                           </tr>
                        <tbody style="text-align:center;font-weight:bold; color:black;">
                           @php
                           $hsnpurchase =DB::select("SELECT item_master.hsn_code , purchaseorder_detail.pur_cgst ,purchaseorder_detail.pur_sgst ,
                           purchaseorder_detail.pur_igst , sum(purchase_order.Gst_amount) as Gst_amount, sum(purchaseorder_detail.`camt`) as camt,
                           sum(purchaseorder_detail.`samt`) as samt,sum(purchaseorder_detail.`iamt`) as iamt, 
                           sum(purchaseorder_detail.`amount`) as gross_amount, sum(purchaseorder_detail.`total_amount`) as total_amount 
                           FROM `purchaseorder_detail` 
                           inner join purchase_order on purchase_order.`pur_code`=purchaseorder_detail.`pur_code` 
                           inner join item_master on item_master.item_code=purchaseorder_detail.item_code 
                           where purchaseorder_detail.`pur_code`='".$poMaster[0]->pur_code."' group by hsn_code , 
                           purchaseorder_detail.pur_cgst ,purchaseorder_detail.pur_sgst , purchaseorder_detail.pur_igst
                           ");
                           $a=1;$b=0; $c=-1; 
                           @endphp
                           @foreach($hsnpurchase as $rowtax) 
                           <tr class="font-weight-600 text-right">
                              <td  class="font-weight-600 text-center">{{ $rowtax->hsn_code}}</td>
                              <td  class="font-weight-600 text-right">{{ round($rowtax->gross_amount,2)}}</td>
                              <td   class="font-weight-600 text-center">{{ $rowtax->pur_cgst}}%</td>
                              <td    class="font-weight-600">{{ round($rowtax->camt,2)}}</td>
                              <td   class="font-weight-600 text-center">{{ $rowtax->pur_sgst}}%</td>
                              <td class="font-weight-600">{{ round($rowtax->samt,2)}}</td>
                              <td   class="font-weight-600 text-center">{{ $rowtax->pur_igst}}%</td>
                              <td   class="font-weight-600">{{ round($rowtax->iamt,2)}}</td>
                              <td class="font-weight-600" style="text-align:right;">{{ (round($rowtax->camt,2)+round($rowtax->samt,2)+round($rowtax->iamt,2))}}</td>
                              @if($a==1) 
                              <td   class="font-weight-600" style="text-align:left;">TOTAL TAX</td>
                              <td   class="font-weight-600">{{ $poMaster[0]->Gst_amount}}</td>
                              @endif
                              @php $a=$a+1; @endphp
                              @if($b==1) 
                              <td style="text-align:left;" class="font-weight-600">Other Charges</td>
                              <td style="text-align:right;" class="font-weight-600">0</td>
                              @endif 
                              @php $b=$b+1; @endphp
                              @if($c==1)
                              <td colspan="1"  class="font-weight-600" style="text-align:left;">SUB TOTAL </td>
                              <td class="font-weight-600" style="text-align:right;">{{ ($poMaster[0]->Gross_amount+$poMaster[0]->Gst_amount) }}</td>
                              @endif
                              @php   $c=$c+1;   @endphp
                           </tr>
                           @endforeach
                           @php 
                           $roundoff=0;
                           $roundoff=$poMaster[0]->Net_amount-($poMaster[0]->Gross_amount+$poMaster[0]->Gst_amount);
                           @endphp
                           <tr class="font-weight-600 text-right">
                              <th> TOTAL</th>
                              <th > {{ $poMaster[0]->Gross_amount}}</th>
                              <th  ></th>
                              <th  >{{ $camt}} </th>
                              <th  > </th>
                              <th  >{{ $samt}}</th>
                              <th  > </th>
                              <th  >{{ $iamt}}</th>
                              <th  > {{ $poMaster[0]->Gst_amount}}</th>
                              <td colspan="1"  class="font-weight-600" style="text-align:left;">ROUNDOFF </td>
                              <td class="font-weight-600" style="text-align:right;">{{ round($roundoff,2)}}</td>
                           </tr>
                           <tr>
                              <td colspan="5"  class="font-weight-600">  </td>
                              <td colspan="4" class="font-weight-600"> </td>
                              <td colspan="1"  class="font-weight-600" style="text-align:left;"> AMOUNT</td>
                              <td class="font-weight-600" style="text-align:right;">
                                 <h4>{{ money_format('%!i',$poMaster[0]->Net_amount) }}  </h4>
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
                        @php  echo  htmlspecialchars_decode($poMaster[0]->terms_and_conditions); @endphp
                     </div>
                     <div class="col-md-3">
                        <br/>
                        <br/>
                     </div>
                  </div>
                  <!-- Footer -->
                  <footer  >
                     <!--<div class="">-->
                     <!--<table class="table border" style="width:100%;border:#000 1px solid;padding: 0px;margin-bottom: 4px;overflow: hidden;">-->
                     <!--<thead>-->
                     <!--  <tr>-->
                     <!--  <th>-->
                     <!--For Purchase and Delivery related queries contact :</th>-->
                     <!--  <td>Mr. Guruprasad Mane (General Manager): . 91 9970028707,<b> Email :</b> admin@rufsangli.com</td>-->
                     <!--</tr>-->
                     <!--    <tr>-->
                     <!--  <th>-->
                     <!--For Payment and Other related queries contact:</th>-->
                     <!--  <td>Mr. Mahesh Jadhav (Account And Finance Department),<b> Email :</b> madhavnagr@rufsangli.com</td>-->
                     <!--</tr>-->
                     <!--</thead>-->
                     <!--<tbody>-->
                     <!--</tbody>-->
                     <!--</table>-->
                     <!--</div>-->
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
                        <div class="col-md-12"><span class="font-weight-600">Remark : </span>{{$poMaster[0]->narration}}</div>
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