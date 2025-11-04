<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <title>Tax Invoice</title>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" >
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" ></script>
      <script src="https://cdn.tailwindcss.com"></script>
      <style>
         @media print {
         body { width: 210mm; height: 297mm; margin: 0 auto; }
         }
         .a4 {
         width: 210mm;
         min-height: 297mm;
         margin: auto;
         padding: 20mm;
         background: white;
         font-size: 12px;
         }
         /*.barcode {*/
         /*width: 180px;*/
         /*height: 50px;*/
         /*background: #eee;*/
         /*text-align: center;*/
         /*line-height: 50px;*/
         /*font-size: 10px;*/
         /*margin-bottom: 10px;*/
         /*}*/
         /*table td, table th {*/
         /*  padding: 3px 4px;*/
         /*  border: 1px solid #000;*/
         /*}*/
      </style>
   </head>
   <body class="bg-gray-100">
      <div class="a4 text-gray-900" style="width: 250mm;">
         <p class="font-bold text-center text-2xl" style="margin-top: -32px;">TAX INVOICE</p>
         <!-- <p class="text-center text-2xl font-bold mb-2">TAX INVOICE</p> -->
         <div class="row" style="margin-bottom: 10px;">
            <!-- Left: IRN & Ack Details -->
            <div class="col-md-9" style="padding-top: 100px;">
               <div class="col-md-12"><label>IRN :&nbsp;&nbsp;</label><b>{{$invoice->irn}}</b></div>
               <div class="col-md-12"><label>Ack No. :&nbsp;&nbsp;</label><b>{{$invoice->ack_no}}</b></div>
               <div class="col-md-12"><label>Ack Date :&nbsp;&nbsp;</label><b>{{date("d-M-Y", strtotime($invoice->AckDt))}}</b></div>
            </div>
            <div class="col-md-3">
               <p class="font-bold">e-Invoice</p> 
               <div id="qrcode"></div> 
            </div>
         </div>
         <table class="text-xs mb-15 border border-black" style="width: -webkit-fill-available;">
            <tr>
               <td class="align-top border border-black px-2 py-1 w-[300px]">
                  <div class="flex items-start gap-3">
                     <!-- Logo -->
                     <div class="w-16">
                        <img src="./../images/KEN_LOGO1.jpg" alt="./images/KEN_LOGO1.jpg" alt="Ken Logo" class="img-fluid w-full" style="width: 70px;height: 100px;position: absolute;margin-top: 20px;" >
                     </div>
                     <hr class="border border-black my-2">
                     <!-- Company Info -->
                     <div class="text-xs leading-tight" style="width: 195px;"> 
                        <strong>Ken Global Designs Pvt Ltd</strong><br>
                        Gat No.- 298/299, A/P Kondigre<br>
                        Kolhapur Mahrashtra - 416101<br>
                        PAN NO.: ABCCS7591Q<br>
                        MSME-UDYAM-15-0016970<br>
                        GSTIN/UIN: 27ABCCS7591Q1ZD<br>
                        State Name : Maharashtra, Code : 27<br>
                        CIN: U18209PN2019PTC187216
                     </div>
                  </div>
                  <hr class="border border-black my-2">
                  <div class="mt-1">
                     Consignee (Ship to)<br>
                     <strong>{{$ShipToDetails[0]->trade_name ?? ""}}</strong><br>
                     {{$ShipToDetails[0]->addr1 ?? ""}}<br>
                     GSTIN/UIN : {{$ShipToDetails[0]->gst_no ?? ""}}<br>
                     State Name : {{$ShipToDetails[0]->state_name ?? ""}}, Code : {{$ShipToDetails[0]->state_id ?? ""}}
                  </div>
                  <hr class="border border-black my-2">
                  <div class="mt-1">
                     Buyer (Bill to)<br>
                     <strong>{{$BillToDetails[0]->trade_name ?? ""}}</strong><br>
                     {{$BillToDetails[0]->addr1 ?? ""}}<br>
                     GSTIN/UIN : {{$BillToDetails[0]->gst_no ?? ""}}<br>
                     State Name : {{$BillToDetails[0]->state_name ?? ""}}, Code : {{$BillToDetails[0]->state_id ?? ""}}
                  </div>
               </td>
               <td class="align-top border border-black px-2 py-1 w-1/3" style="width: 209px;">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-5">
                           <label>Invoice No.</label>
                           <h3><b>{{$invoice->sale_code}}</b></h3>
                        </div>
                        <div class="col-md-7">
                           <label>e-Way Bill No.</label>
                           <h3><b>{{$invoice->eway_bill_no}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Delivery Note</label><br/><br/>
                           <h3><b>{{$invoice->delivary_note}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Reference No. & Date</label>
                           <h3><b>{{$BuyePO[0]->buyer_po_nos}} dt. {{date("d-M-Y", strtotime($BuyePO[0]->sale_date))}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-6">
                           <label>Buyer’s Order No.</label>  <br/><br/>
                           <h3><b>{{$BuyePO[0]->buyer_po_nos}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Dispatch Doc No.</label>
                           <h3><b>-</b></h3><br/>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Dispatched through</label>
                           <h3><b>{{$invoice->dispatch_name}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Bill of Lading/LR-RR No.</label>
                           <h3><b>{{$invoice->bill_of_landing}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Motor Vehicle No.</label><br/><br/>
                           <h3><b>{{$invoice->vehicle_no}}</b></h3>
                        </div>
                     </div>
                     <hr/>
                     <div class="row">
                        <div class="col-md-12">
                           <label>Terms of Delivery</label><br/>
                           <h3><b>{{$invoice->ship_mode_name}}</b></h3>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12"><label>Due Days : <b> {{$invoice->mode_of_payment}} Days</b></label></div>
                     </div>
                     <div class="row">
                        <div class="col-md-12"><label>Due Date : <b>{{ date('d-M-Y', strtotime($invoice->AckDt . ' + ' . ($invoice->mode_of_payment ?? 0) . ' days')) }}</b></label></div>
                     </div>
                  </div>
               </td>
               <!-- Section 3: Dated to Port of Discharge -->
               <td class="align-top px-2 py-1 w-1/3" style="width: 155px;">
                  <div class="col-md-12">
                     <label>Dated</label>
                     <h3><b>{{date("d-M-Y", strtotime($invoice->sale_date))}}</b></h3>
                     <hr/>
                     <label>Mode/Terms of Payment</label>
                     <h3><b> {{$invoice->mode_of_payment}} Days</b></h3>
                     <hr/>
                     <label>Other References  <br/><br/></label>
                     <h3></h3>
                     <hr/>
                     <label>Dated <br/></label>
                     <h3><b>{{date("d-M-Y", strtotime($invoice->sale_date))}}</b></h3>
                     <hr/>
                     <label>Delivery Note Date</label>
                     <h3><b>{{ $invoice->delivary_note_date ? date("d-M-Y", strtotime($invoice->delivary_note_date)) : '-' }}</b></h3>
                     <hr/>
                     <label>Destination</label>
                     <h3><b>{{$invoice->destination}}</b></h3>
                     <hr/>
                     <label>Place of receipt by shipper : <br/> <br/></label>
                     <h3></h3>
                     <hr/>
                     <label>City/Port of Discharge <br/> <br/></label>
                     <h3></h3>
                  </div>
               </td>
            </tr>
         </table>
         <!-- Item Details Table -->
         <table class="w-full text-xs border border-black mb-0">
            <thead class="bg-gray-100 text-center font-bold">
               <tr class="border border-black">
                  <th class="border border-black w-6">Sl</th>
                  <th class="border border-black">Description of Goods</th>
                  <th class="border border-black">HSN/SAC</th>
                  <th class="border border-black">Quantity</th>
                  <th class="border border-black">Rate</th>
                  <th class="border border-black">Per</th>
                  <th class="border border-black">Amount</th>
               </tr>
            </thead>
            <tbody>
                  @php
                    $srno = 1; 
                    $total_qty = 0;
                    $total_amount = 0;
             
                    $total_qty = 0; 
                    $total_cgst = 0;
                    $total_sgst = 0;
                    $total_tax_amt = 0;
                    $total_igst = 0; 
                    $unitname = '';
                  @endphp
                  @foreach($invoiceDetails as $details)
                  
                   @php
                    
                      $sale_cgst = '';
                      $sale_sgst = '';
                      $sale_igst = '';
                      $tax_type = '';
                      $tax_type1 = ''; 
                      if($details->tax_type_id == 1)
                      {
                         $tax_type = 'Output CGST';
                         $tax_type1 = 'Output SGST';
                         $sale_cgst = $details->sale_cgst;
                         $sale_sgst = $details->sale_sgst;
                      } 
                      else if($details->tax_type_id == 2)
                      {
                         $tax_type = 'Output IGST';
                         $sale_igst = $details->sale_igst;
                      }
                      
                      if(\Illuminate\Support\Str::contains($details->unit_name, 'Pack -'))
                      {
                            $unitname = 'PAC';
                      }
                      else
                      {
                            $unitname = $details->unit_name;
                      }
                      
                    @endphp
               <tr class="align-top text-center">
                      <td class="border border-black">{{$srno++}}</td>
                      <td class="border border-black text-left" style="width: 27%;">
                         <strong>{{$details->style_no}}</strong><br>{{$details->style_description}}<br>
                      </td>
                      <td class="border border-black text-center"> {{$details->hsn_code}}</td>
                      <td class="border border-black font-bold text-right"> {{$details->qty}} {{$unitname}}</td>
                      <td class="border border-black" style="width: 8%;">{{ money_format("%!.2n",$details->order_rate) }}</td>
                      <td class="border border-black text-right" style="width: 6.1%;">{{$unitname}}</td>
                      <td class="border border-black font-bold text-right pr-1"> {{ money_format("%!.2n",$details->qty * $details->order_rate)}}</td>
               </tr>
                  @php
                    $total_qty += $details->qty;
                    $total_amount += $details->qty * $details->order_rate; 
                    
                    if($details->tax_type_id == 1)
                    {
                        $total_cgst += ($details->qty * $details->order_rate) * ($details->sale_cgst / 100);
                        $total_sgst += ($details->qty * $details->order_rate) * ($details->sale_sgst / 100);
                        $total_tax_amt += (($details->qty * $details->order_rate) * ($details->sale_cgst / 100)) + (($details->qty * $details->order_rate) * ($details->sale_sgst/100));
                    }
                    else if($details->tax_type_id == 2)
                    {
                        $total_igst += ($details->qty * $details->order_rate) * ($details->sale_igst / 100);
                        $total_tax_amt += (($details->qty * $details->order_rate) * ($details->sale_igst / 100));
                    }
                  @endphp
                  @endforeach
               <tr class="align-top text-center">
                      <td class="border border-black"></td>
                      <td class=" text-left">
                         Total Pieces - {{money_format("%!.0n",$invoice->total_qty)}}<br>
                         Total No of Carton - {{$invoice->no_of_cartons}}<br></br> 
                         <span style="position: absolute;margin-top: 30px;">Less : </span></br> 
                         <div class="row text-right">
                           <strong>{{$tax_type}}<br> {{$tax_type1}}</strong>
                           <strong>Round Off</strong>
                         </div>
                      </td>
                      <td class="border border-black text-center"> </td>
                      <td class="border border-black font-bold text-right"></td>
                      <td class="border border-black"></td>
                      <td class="border border-black text-right">-</td>
                      <td class="border border-black font-bold text-right pr-1"><br><br><span style="border-top: 1px solid;">{{money_format("%!.2n",round($total_amount,2))}}</span><br><br>
                      
                         @if($invoice->tax_type_id == 1)
                             <strong>{{ money_format("%!.2n",$total_cgst) }}</strong><br>
                             <strong>{{ money_format("%!.2n",$total_sgst) }}</strong><br>
                          @php 
                            $rounded =  $total_amount + round($total_cgst,2) + round($total_sgst,2); 
                         @endphp
                         @elseif($invoice->tax_type_id == 2)
                            <strong>{{ money_format("%!.2n",$total_igst) }}</strong><br>
                         @php 
                            $rounded = $total_amount + round($total_igst,2); 
                         @endphp
                         @endif
                          @php  
                            $decimalOnly = $rounded - floor($rounded); 
                            
                            if($decimalOnly >= 0.5)
                            {
                                $sign = "";
                            }
                            else
                            {
                                 $sign = "(-)";
                            }
                         @endphp
                         <strong>{{$sign}}{{number_format($decimalOnly, 2)}}</strong><br> 
                     </td>
               </tr>
               <tr class="font-bold">
                  <td></td>
                  <td colspan="" class="text-right border border-black pr-2">Total</td>
                  <td></td>
                  <td class="border border-black text-right" nowrap><b>{{money_format("%!.0n",$total_qty)}}  {{$unitname}}</b></td>
                  <td></td>
                  <td></td>
                  <td class="border border-black text-right pr-1" nowrap>₹ {{money_format("%!.2n",round($total_amount + $total_tax_amt))}}</td>
               </tr>
            </tbody>
         </table>
         @php
            function convert($number) {
                $hyphen      = '-';
                $conjunction = ' and ';
                $separator   = ', ';
                $negative    = 'negative ';
                $dictionary  = [
                    0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
                    5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                    10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
                    14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
                    18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
                    40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
                    80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
                    100000 => 'Lakh', 10000000 => 'Crore'
                ];
            
                if (!is_numeric($number)) return false;
                if ($number < 0) return $negative . convert(abs($number));
            
                $string = '';
            
                if ($number < 21) {
                    $string = $dictionary[$number];
                } elseif ($number < 100) {
                    $tens   = ((int) ($number / 10)) * 10;
                    $units  = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                } elseif ($number < 1000) {
                    $hundreds  = (int) ($number / 100);
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction . convert($remainder);
                    }
                } else {
                    foreach ([10000000 => 'Crore', 100000 => 'Lakh', 1000 => 'Thousand', 100 => 'Hundred'] as $value => $name) {
                        if ($number >= $value) {
                            $count = floor($number / $value);
                            $number %= $value;
                            $string .= convert($count) . ' ' . $name;
                            if ($number) $string .= $separator;
                        }
                    }
                    if ($number) $string .= convert($number);
                }
            
                return $string;
            }
            function convertToIndianCurrency($amount)
            {
                $number = floor($amount); // Rupee part
                $decimal = round(($amount - $number) * 100); // Paise part
            
                $rupeesText = convert($number);
            
                if ($decimal > 0) {
                    $paiseText = convert($decimal) . ' Paise';
                    return $rupeesText . ' and ' . $paiseText . ' Only';
                } else {
                    return $rupeesText . ' Only';
                }
            }
            @endphp
         <!-- Amount in Words -->
         <div class="border border-black border-t-0 px-2 py-1 font-semibold">
            <h4 style="font-size: 10px;">Amount Chargeable (in words):</h4>
            <h2 style="font-size: 15px;font-weight: 700;"> INR {{ convert(round($total_amount + $total_tax_amt)) }} Only</h2>
         </div>
         <!-- GST Summary Table -->
         <table class="text-xs border border-black mb-0" style="width: -webkit-fill-available;">
            <thead class="bg-gray-100 text-center font-bold">
               <tr>
                  <th rowspan="2" class="border border-black w-[300px]" style="width: 30%;">HSN/SAC</th>
                  <th rowspan="2" class="border border-black">Taxable Value</th>
                  @if($invoice->tax_type_id == 1)
                    <th colspan="2"class="border border-black">CGST Amount</th>
                    <th colspan="2"class="border border-black">SGST Amount</th>
                  @elseif($invoice->tax_type_id == 2)
                    <th colspan="2"class="border border-black">IGST Amount</th>
                  @endif
                  <th rowspan="2"class="border border-black">Total Tax Amount</th>
               </tr>
               <tr>
                  @if($invoice->tax_type_id == 1)
                      <th class="border border-black">Rate</th>
                      <th class="border border-black">Amount</th>
                      <th class="border border-black">Rate</th>
                      <th class="border border-black">Amount</th>
                  @elseif($invoice->tax_type_id == 2)
                      <th class="border border-black">Rate</th>
                      <th class="border border-black">Amount</th>
                  @endif
               </tr>
            </thead>
            <tbody class="text-center">
                @php 
                    $total_qty1 = 0; 
                    $total_cgst1 = 0;
                    $total_sgst1 = 0;
                    $total_tax_amt1 = 0;
                    $total_igst1 = 0; 
                    $total_amount1 = 0;
               @endphp
               @foreach($invoiceDetails1 as $details1)
               @php
                  $tax_rate = 0;
                  
                  if($details1->tax_type_id == 1)
                  {
                    $tax_rate = round($details1->sale_cgst,2) + round($details1->sale_sgst,2);
                  } 
                  else if($details1->tax_type_id == 2)
                  {
                    $tax_rate =  round($details1->sale_igst,2);
                  }
                @endphp
               <tr>
                  <td class="border border-black">{{$details1->hsn_code}}</td>
                  <td class="border border-black text-right">{{money_format("%!.2n",$details1->amount)}}</td>
                  @if($details1->tax_type_id == 1)
                      <td class="border border-black text-right pr-1">{{money_format("%!.2n",round($details1->sale_cgst,2))}}</td>
                      <td class="border border-black text-right">{{ money_format("%!.2n", ($details1->amount) * (round($details1->sale_cgst,2) / 100)) }}</td>
                      <td class="border border-black text-right pr-1">{{money_format("%!.2n",round($details1->sale_sgst,2))}}</td> 
                      <td class="border border-black text-right">{{money_format("%!.2n",($details1->amount) * (round($details1->sale_sgst,2)/100))}}</td>   
                      <td class="border border-black text-right">{{ money_format('%!.2n', round(($details1->amount) * (round($details1->sale_cgst,2)/100),2) + round(($details1->amount) * (round($details1->sale_sgst,2)/100),2)) }}</td>
                  @elseif($details1->tax_type_id == 2)
                      <td class="border border-black text-right pr-1">{{money_format("%!.2n",round($details1->sale_igst,2))}}</td>
                      <td class="border border-black text-right">{{ money_format("%!.2n", ($details1->qty * $details1->order_rate) * (round($details1->sale_igst,2) / 100)) }}</td>
                      <td class="border border-black text-right">{{ money_format("%!.2n", (($details1->qty * $details1->order_rate) * (round($details1->sale_igst,2) / 100))) }}</td>
                  @endif
               </tr> 
                  @php
                    $total_amount1 += $details1->amount; 
                    
                    if($details1->tax_type_id == 1)
                    {
                        $total_cgst1 += ($details1->amount) * (round($details1->sale_cgst,2) / 100);
                        $total_sgst1 += ($details1->amount) * (round($details1->sale_sgst,2) / 100);
                        $total_tax_amt1 += round(($details1->amount) * (round($details1->sale_cgst,2)/100),2) + round(($details1->amount) * (round($details1->sale_sgst,2)/100),2);
                    }
                    else if($details1->tax_type_id == 2)
                    {
                        $total_igst1 += ($details1->amount) * (round($details1->sale_igst,2) / 100);
                        $total_tax_amt1 += round((($details1->amount) * (round($details1->sale_igst,2) / 100)),2);
                    }
                  @endphp
               @endforeach
               <tr class="font-bold">
                  <td class="border border-black text-right">Total</td>
                  <td class="border border-black text-right">{{money_format("%!.2n",$total_amount1)}}</td>
                  @if($invoice->tax_type_id == 1)
                      <td class="border border-black text-right"></td>
                      <td class="border border-black text-right">{{money_format("%!.2n",$total_cgst1)}}</td>
                      <td class="border border-black"></td>
                      <td rowspan="2" class="border border-black text-right">{{money_format("%!.2n",$total_sgst1)}}</td>
                  @elseif($invoice->tax_type_id == 2)
                      <td class="border border-black"></td>
                      <td rowspan="2" class="border border-black text-right">{{money_format("%!.2n",$total_igst1)}}</td>
                  @endif
                  <td class="border border-black text-right">{{money_format("%!.2n",$total_tax_amt1)}}</td>
               </tr>
            </tbody>
         </table>
         <div class="declare" style="border: 1px solid;padding: 5px;">
            <div class="row">
               <p><strong>Tax Amount (in words):</strong> INR {{ convertToIndianCurrency($total_tax_amt) }}</p>
               <p><strong>Company’s PAN:</strong> {{$invoice->pan_no}}</p>
            </div>
            <br/><br/>
            <div class="row">
               <div class="col-md-6">
                  <p><strong>Declaration</strong></p>
                  <p>Transit Insurance: TATA Aig Insurance, Policy # 0891011021 Period 17/02/2025 To 16/02/2026.</p>
                  <p>1) We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
                  <p>2) Subject to Ichalkaranji Jurisdiction only.</p>
               </div>
               <div class="col-md-6">
                  <p><strong>Company’s Bank Details</strong></p>
                  <p>Bank Name: {{$invoice->bank_name}}</p>
                  <p>A/c No.: {{$invoice->account_no}}</p>
                  <p>Branch & IFSC Code: {{$invoice->branch_name}} & {{$invoice->ifsc_code}}</p> 
               </div> 
            </div>
            <br/>
            <hr/>
            <br/>
            <div class="row">
               <div class="col-md-6" style="border-right: 0.5px solid black;">
                 <strong>Customer's Seal and Signature</strong>
               </div>
               <div class="col-md-6 text-center" > 
                  <p>for Ken Global Designs Pvt Ltd</p>
                  <br/><br/>
                  <p><b>Prepared by</b> &nbsp;&nbsp;&nbsp; <b>Verified by</b> &nbsp;&nbsp;&nbsp; <b>Authorised Signatory</b></p>
               </div> 
            </div>
         </div>
         <div class="text-center text-xs mt-4 italic"><h1>SUBJECT TO ICHALKARANJI JURISDICTION</h1></div>
         <div class="text-center text-xs mt-4 italic">This is a Computer Generated Invoice</div>
         <br/><br/>
         <hr/>
      </div>
      <div class="a4 text-gray-900" style="width: 250mm;">
         <p class="font-bold text-center text-2xl" style="margin-top: -32px;">e-Way Bill</p>
         <div class="row" style="margin-bottom: 10px;">
            <!-- Left: IRN & Ack Details -->
            <div class="col-md-9" style="padding-top: 100px;">
               <div class="col-md-12"><label>Doc No. :&nbsp;&nbsp;</label><b>{{$invoice->sale_code}}</b></div>
               <div class="col-md-12"><label>Date :&nbsp;&nbsp;</label><b>{{date("d-M-Y", strtotime($invoice->sale_date))}}</b></div>
               <br/>
               <div class="col-md-12"><label>IRN :&nbsp;&nbsp;</label><b>{{$invoice->irn}}</b></div>
               <div class="col-md-12"><label>Ack No. :&nbsp;&nbsp;</label><b>{{$invoice->ack_no}}</b></div>
               <div class="col-md-12"><label>Ack Date :&nbsp;&nbsp;</label><b>{{date("d-M-Y", strtotime($invoice->AckDt))}}</b></div>
            </div>
            <div class="col-md-3">
               <p class="font-bold">e-Way Bill</p>
               <div id="qrcode1"></div> 
            </div>
         </div>
         <hr/>
         <br/>
         <div class="col-md-12"><b>1. e-Way Bill Details</b></div>
         <br/>
         <div class="row">
            <div class="col-md-4">
               <label>e-Way Bill No. :&nbsp;&nbsp;</label><b>{{$invoice->eway_bill_no}}</b><br/>
               <label>Generated By :&nbsp;&nbsp;</label><b>{{$invoice->pan_no}}</b><br/>
               <label>Supply Type :&nbsp;&nbsp;</label><b>Outward-Supply</b><br/>
            </div>
            <div class="col-md-4">
               <label>Mode :&nbsp;&nbsp;</label><b>{{$invoice->ship_mode_name}}</b><br/>
               <label>Approx Distance :&nbsp;&nbsp;</label><b>{{$invoice->distance}} KM</b><br/>
               <label>Transaction Type :&nbsp;&nbsp;</label><b>Regular</b>
            </div>
            <div class="col-md-4">
               <label>Generated Date:&nbsp;&nbsp;</label><b>{{ \Carbon\Carbon::createFromFormat('d/m/Y h:i:s A', $invoice->eway_bill_date)->format('d-M-Y h:i:s A') }}</b><br/>
               <label>Valid Upto:&nbsp;&nbsp;</label>
                <b>
                @if($invoice->validUpto)
                    {{ \Carbon\Carbon::parse($invoice->validUpto)->format('d-M-Y h:i:s A') }}
                @else
                    N/A
                @endif
                </b> <br/>

            </div>
         </div>
         <br/> 
         <hr/>
         <div class="col-md-12"><b>2. Address Details</b></div>
         <br/> 
         <div class="row">
            <div class="col-md-6">
               <label><b>From</b></label><br/>
               <p>Ken Global Designs Pvt Ltd.<br/>
                  GSTIN : 27ABCCS7591Q1ZD<br/>
                  Maharashtra
               </p>
               <br/>
               <label><b>Dispatch From</b></label><br/>
               <p>
                    Gat No.- 298/299, A/P Kondigre<br>
                    Ichalkarnji Mahrashtra - 416101<br>
               </p>
            </div> 
            <div class="col-md-6">
               <label><b>To</b></label><br/>
               <p> 
                  {{$BillToDetails[0]->trade_name ?? ""}} <br/>
                  GSTIN : {{$BillToDetails[0]->gst_no ?? ""}}<br/>
                  {{$BillToDetails[0]->state_name ?? ""}}
               </p>
               <br/>
               <label><b>Ship To</b></label><br/>
               <p>
                  {{$ShipToDetails[0]->addr1 ?? ""}}
               </p>
            </div>
         </div>
         <br/> 
         <hr/>
         <div class="col-md-12"><b>3. Goods Details</b></div>
         <br/> 
         <hr/>
         @php 
          $tax_type1 = '';  
              
          if($invoice->tax_type_id == 1)
          { 
            $tax_type1 = 'C+S';
          } 
          else if($invoice->tax_type_id == 2)
          { 
            $tax_type1 = 'I';
          }
         @endphp
         <div class="row">
            <table class="table">
               <thead>
                  <tr>
                     <th class="border border-black">HSN Code</th>
                     <th class="border border-black">Product Name & Desc</th>
                     <th class="border border-black">Quantity</th>
                     <th class="border border-black">Taxable Amt</th>
                     <th class="border border-black">Tax Rate({{$tax_type1}})</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($invoiceDetails as $details)
                  @php
                      $tax_rate = 0;
                      $tax_rate1 = 0;
                      if($details->tax_type_id == 1)
                      {
                        $tax_rate = round($details->sale_cgst,2);
                        $tax_rate1 = round($details->sale_sgst,2);
                      } 
                      else if($details->tax_type_id == 2)
                      {
                        $tax_rate =  round($details->sale_igst,2);
                      }
                  @endphp
                  <tr>
                     <td class="border border-black">{{$details->hsn_code}}</td>
                     <td class="border border-black">{{$details->style_no}} &nbsp;&nbsp;{{$details->style_description}}</td>
                     <td class="border border-black">{{money_format("%!.0n",$details->qty)}}  {{$unitname}}</td>
                     <td class="border border-black">{{money_format("%!.2n",($details->qty * $details->order_rate))}}</td>
                     @if($details->tax_type_id == 1)
                     <td class="border border-black">{{money_format("%!.2n",($tax_rate))}} + {{money_format("%!.2n",($tax_rate1))}}</td>
                     @elseif($details->tax_type_id == 2)
                        <td class="border border-black">{{money_format("%!.2n",($tax_rate))}}</td>
                     @endif
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
         <br/><br/><br/>
         <hr/>
         <div class="row">
            <div class="col-md-4">
               <label>Tot.Taxable Amt :&nbsp;&nbsp;</label><b>{{money_format("%!.2n",($invoice->Gross_amount))}}</b><br/>
              @if($invoice->tax_type_id == 1) 
                  <label>CGST Amt :&nbsp;&nbsp;</label><b>{{money_format("%!.2n",($total_cgst))}}</b>  
                  @php 
                        $rounded = round($invoice->Gross_amount + round($total_cgst,2) + round($total_sgst,2),2);
                        $decimalOnly = $rounded - floor($rounded); 
                 @endphp 
              @elseif($invoice->tax_type_id == 2) 
                  <label>IGST Amt :&nbsp;&nbsp;</label><b>{{money_format("%!.2n",($total_igst))}}</b><br/> 
                  @php 
                        $rounded = round($invoice->Gross_amount + round($total_igst,2),2);
                        $decimalOnly = $rounded - floor($rounded); 
                 @endphp 
              @endif 
            </div>
            <div class="col-md-4">
                @php
                
                    if($decimalOnly >= 0.5)
                    {
                        $sign = "";
                    }
                    else
                    {
                         $sign = "(-)";
                    }
                @endphp
               <label>Other Amt :&nbsp;&nbsp;</label><b>{{$sign}}{{money_format("%!.2n",($decimalOnly))}}</b><br/>
               @if($invoice->tax_type_id == 1) 
                  <label>SGST Amt :&nbsp;&nbsp;</label><b>{{money_format("%!.2n",($total_sgst))}}</b><br/>  
               @endif 
            </div>
            <div class="col-md-4">
               <label>Total Inv Amt:&nbsp;&nbsp;</label><b>{{money_format("%!.2n",($invoice->Net_amount))}}</b><br/> 
            </div>
         </div>
         <br/> 
         <hr/>
         <div class="col-md-12"><b>4. Transportation Details</b></div>
         <br/> 
         <hr/>
         <div class="row">
            <div class="col-md-4">
               <label>Transporter ID :&nbsp;&nbsp;</label><b>{{$invoice->tranport}}</b><br/>
               <label>Name :&nbsp;&nbsp;</label><b>{{$invoice->dispatch_name}}</b><br/> 
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4"> 
               <label>Doc No. : &nbsp;&nbsp;</label><b></b><br/> 
               <label>Date : &nbsp;&nbsp;</label><b></b><br/>  
            </div>
         </div>

         <br/> 
         <hr/>
         <div class="col-md-12"><b>5. Vehicle Details</b></div>
         <br/> 
         <hr/>
         <div class="row">
            <div class="col-md-4">
               <label>Vehicle No. : &nbsp;&nbsp;</label><b>{{$invoice->vehicle_no}}</b><br/> 
            </div>
            <div class="col-md-4">
               <label>From : &nbsp;&nbsp;</label><b>ICHALKARANJI</b><br/> 
            </div>
            <div class="col-md-4">
               <label>CEWB No. : &nbsp;&nbsp;</label><b>-</b><br/>  
            </div>
         </div>
         <br/> 
         <hr/>
      </div>
      <input type="hidden" id="SignedQRCode" value="{{$invoice->SignedQRCode}}" />
      <input type="hidden" id="ewayBillNo" value="{{$invoice->eway_bill_no}}" />
      <input type="hidden" id="eway_bill_date" value="{{$invoice->eway_bill_date}}" />
      <input type="hidden" id="gst_no" value="{{$invoice->gst_no}}" />
    <!-- end row -->
    <script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script type="text/javascript"> 
    
    $( document ).ready(function()
    {
        var SignedQRCode = $("#SignedQRCode").val();
        var ewayBillNo = $("#ewayBillNo").val();
        var eway_bill_date = $("#eway_bill_date").val();
        var gst_no = $("#gst_no").val();
        
        var qrData1 = [
                  "E-Way Bill No: " + ewayBillNo,
                  "GSTIN: " + gst_no,
                  "Date: " + eway_bill_date
                ].join("\n");
        
        new QRCode($("#qrcode1")[0], {
          text: qrData1,
          width: 350,
          height: 350,
          correctLevel: QRCode.CorrectLevel.H,
          render: "svg"
        });
        
        var qrData = ["" + SignedQRCode].join("\n");
        
        new QRCode($("#qrcode")[0], {
          text: qrData,
          width: 350,
          height: 350,
          correctLevel: QRCode.CorrectLevel.H,
          render: "svg"
        });
 
 
    });
  
</script>
   </body>
</html>