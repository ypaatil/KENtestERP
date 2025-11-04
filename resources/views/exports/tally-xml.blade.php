@php
    function convertNumberToWords($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
            6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 11 => 'eleven',
            12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen',
            17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety',
            100 => 'hundred', 1000 => 'thousand', 100000 => 'lakh', 10000000 => 'crore'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . convertNumberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos((string)$number, '.') !== false) {
            [$number, $fraction] = explode('.', (string)$number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = floor($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(100, floor(log($number, 100)));
                if ($baseUnit > 10000000) {
                    $baseUnit = 10000000; // crore
                } elseif ($baseUnit > 100000) {
                    $baseUnit = 100000; // lakh
                } elseif ($baseUnit > 1000) {
                    $baseUnit = 1000; // thousand
                }
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder  = $number % $baseUnit;
                $string = convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convertNumberToWords($remainder);
                }
                break;
        }

        if ($fraction !== null && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];
            foreach (str_split((string) $fraction) as $digit) {
                $words[] = $dictionary[$digit];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    $total_material_value = 0;
    $totalAmount = 0;
@endphp

<ENVELOPE>
    <HEADER>
        <TALLYREQUEST>Import Data</TALLYREQUEST>
    </HEADER>
    <BODY>
        <IMPORTDATA>
            <REQUESTDESC>
                <REPORTNAME>Vouchers</REPORTNAME>
                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>Ken Global Designs Pvt. Ltd.</SVCURRENTCOMPANY>
                </STATICVARIABLES>
            </REQUESTDESC>
            <REQUESTDATA>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <VOUCHER VCHTYPE="Sales" ACTION="Create">

                        <!-- Voucher Header -->
                        <DATE>{{ date('Y-m-d', strtotime($BuyerPurchaseOrderMasterList[0]->sale_date)) }}</DATE>
                        <VOUCHERTYPENAME>Sales</VOUCHERTYPENAME>
                        <VOUCHERNUMBER>{{ $BuyerPurchaseOrderMasterList[0]->sale_code }}</VOUCHERNUMBER>
                        <PARTYLEDGERNAME>{{ $ledgerDetails[0]->trade_name }}</PARTYLEDGERNAME>
                        <PARTYNAME>{{ $ledgerDetails[0]->trade_name }}</PARTYNAME>
                        <PARTYMAILINGNAME>{{ $ledgerDetails[0]->trade_name }}</PARTYMAILINGNAME>
                        <BASICBUYERNAME>{{ $ledgerDetails[0]->trade_name }}</BASICBUYERNAME>

                        <!-- Buyer Address -->
                        <BASICBUYERADDRESS.LIST TYPE="String">
                            <BASICBUYERADDRESS>{{ $ledgerDetails[0]->addr1 }}</BASICBUYERADDRESS>
                        </BASICBUYERADDRESS.LIST>

                        <!-- Consignee Info -->
                        <CONSIGNEESTATENAME>{{ $ledgerDetails1[0]->state_name ?? '' }}</CONSIGNEESTATENAME>
                        <CONSIGNEECOUNTRYNAME>{{ $ledgerDetails1[0]->c_name ?? '' }}</CONSIGNEECOUNTRYNAME>
                        <CONSIGNEEMAILINGNAME>{{ $ledgerDetails[0]->consignee_name ?? '' }}</CONSIGNEEMAILINGNAME>

                        <!-- Shipping Address -->
                        <BASICSHIPSELLERNAME>{{ $ledgerDetails1[0]->trade_name }}</BASICSHIPSELLERNAME>
                        <BASICSHIPADDRESS.LIST TYPE="String">
                            <BASICSHIPADDRESS>{{ $ledgerDetails1[0]->addr1 }}</BASICSHIPADDRESS>
                        </BASICSHIPADDRESS.LIST>

                        <!-- Tax & GST Info -->
                        <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
                        <PARTYGSTIN>{{ $ledgerDetails[0]->gst_no }}</PARTYGSTIN>
                        <PARTYPANNUMBER>{{ $ledgerDetails[0]->pan_no }}</PARTYPANNUMBER>
                        <COUNTRYOFRESIDENCE>{{ $ledgerDetails[0]->c_name ?? '' }}</COUNTRYOFRESIDENCE>
                        <COMPANYGSTIN>27ABCCS7591Q1ZD</COMPANYGSTIN>
                        <PLACEOFSUPPLY>27</PLACEOFSUPPLY>

                        <!-- Order & Reference -->
                        <ORDERNO>{{ $BuyerPurchaseOrderMasterList[0]->order_no ?? '' }}</ORDERNO>
                        <BASICORDERDATE>{{ $BuyerDetail->order_date ?? '' }}</BASICORDERDATE>
                        <ORDERTYPE>{{ $BuyerPurchaseOrderMasterList[0]->order_type ?? '' }}</ORDERTYPE>
                        <BASICPURCHASEORDERNO>{{ $BuyerDetail->po_code ?? '' }}</BASICPURCHASEORDERNO>
                        <REFERENCE>{{ $BuyePO[0]->buyer_po_nos ?? '' }}</REFERENCE>
                        <REFERENCEDATE>{{ $BuyePO[0]->sale_date ?? '' }}</REFERENCEDATE>
                        <TRACKINGNUMBER>{{ $BuyerDetail->tracking_no ?? '' }}</TRACKINGNUMBER>

                        <!-- Status & Narration -->
                        <VCHSTATUSDATE>{{ date('Y-m-d', strtotime($BuyerPurchaseOrderMasterList[0]->sale_date)) }}</VCHSTATUSDATE>
                        <BASICDUEDATE>{{ date('Y-m-d', strtotime($BuyerPurchaseOrderMasterList[0]->sale_date)) }}</BASICDUEDATE>
                        <NARRATION>Sale Invoice No. {{ $BuyerPurchaseOrderMasterList[0]->sale_code }}</NARRATION>

                        <!-- Inventory & Ledger Entries -->
                        @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                            @php
                                $amount = round($buyerData->order_qty * $buyerData->order_rate, 2);
                                $taxAmt = ($buyerData->tax_type_id == 1) ? ($buyerData->camt + $buyerData->samt) : $buyerData->iamt;
                                $total_material_value += $amount;
                                $totalAmount += $taxAmt;
                                $isInterstate = ($buyerData->tax_type_id == 2);
                            @endphp

                            <ALLINVENTORYENTRIES.LIST>
                                <STOCKITEMNAME>{{ $buyerData->style_description }}</STOCKITEMNAME>
                                <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                <RATE>{{ number_format($buyerData->order_rate, 2) }}/{{ $buyerData->unit_name }}</RATE>
                                <AMOUNT>{{ number_format($amount, 2) }}</AMOUNT>
                                <ACTUALQTY>{{ number_format($buyerData->order_qty, 2) }} {{ $buyerData->unit_name }}</ACTUALQTY>
                                <BILLEDQTY>{{ number_format($buyerData->order_qty, 2) }} {{ $buyerData->unit_name }}</BILLEDQTY>
                                <HSNCODE>{{ $buyerData->hsn_code }}</HSNCODE>
                                <TAXCLASSIFICATIONNAME>{{ $isInterstate ? 'IGST @ 18%' : 'GST @ 9% + 9%' }}</TAXCLASSIFICATIONNAME>
                                <GODOWNNAME>{{ $buyerData->godown_name ?? 'Main Location' }}</GODOWNNAME>

                                <!-- Batch Allocations -->
                                <BATCHALLOCATIONS.LIST>
                                    <BATCHNAME>{{ $buyerData->batch_name ?? 'Primary Batch' }}</BATCHNAME>
                                    <AMOUNT>{{ number_format($amount, 2) }}</AMOUNT>
                                </BATCHALLOCATIONS.LIST>
                            </ALLINVENTORYENTRIES.LIST>

                            @if(!$isInterstate)
                                <ALLLEDGERENTRIES.LIST>
                                    <LEDGERNAME>Output CGST</LEDGERNAME>
                                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                    <AMOUNT>{{ number_format($buyerData->camt, 2) }}</AMOUNT>

                                    <!-- Accounting Allocations -->
                                    <ACCOUNTINGALLOCATIONS.LIST>
                                        <LEDGERNAME>Output CGST</LEDGERNAME>
                                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                        <AMOUNT>{{ number_format($buyerData->camt, 2) }}</AMOUNT>
                                    </ACCOUNTINGALLOCATIONS.LIST>
                                </ALLLEDGERENTRIES.LIST>

                                <ALLLEDGERENTRIES.LIST>
                                    <LEDGERNAME>Output SGST</LEDGERNAME>
                                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                    <AMOUNT>{{ number_format($buyerData->samt, 2) }}</AMOUNT>

                                    <!-- Accounting Allocations -->
                                    <ACCOUNTINGALLOCATIONS.LIST>
                                        <LEDGERNAME>Output SGST</LEDGERNAME>
                                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                        <AMOUNT>{{ number_format($buyerData->samt, 2) }}</AMOUNT>
                                    </ACCOUNTINGALLOCATIONS.LIST>
                                </ALLLEDGERENTRIES.LIST>
                            @else
                                <ALLLEDGERENTRIES.LIST>
                                    <LEDGERNAME>Output IGST</LEDGERNAME>
                                    <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                    <ISPARTYLEDGER>No</ISPARTYLEDGER>
                                    <AMOUNT>{{ number_format($buyerData->iamt, 2) }}</AMOUNT>

                                    <!-- Accounting Allocations -->
                                    <ACCOUNTINGALLOCATIONS.LIST>
                                        <LEDGERNAME>Output IGST</LEDGERNAME>
                                        <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
                                        <AMOUNT>{{ number_format($buyerData->iamt, 2) }}</AMOUNT>
                                    </ACCOUNTINGALLOCATIONS.LIST>
                                </ALLLEDGERENTRIES.LIST>
                            @endif
                        @endforeach

                        <!-- HSN Summary -->
                        @foreach($BuyerPurchaseOrderMasterList as $buyerData)
                            <HSNSSUMMARY.LIST>
                                <STOCKITEMNAME>{{ $buyerData->style_description }}</STOCKITEMNAME>
                                <HSNCode>{{ $buyerData->hsn_code }}</HSNCode>
                                <TAXABLEAMOUNT>{{ number_format($buyerData->order_qty * $buyerData->order_rate, 2) }}</TAXABLEAMOUNT>
                                <CGSTRATE>{{ $buyerData->sale_cgst }}</CGSTRATE>
                                <CGSTAMOUNT>{{ number_format($buyerData->camt, 2) }}</CGSTAMOUNT>
                                <SGSTRATE>{{ $buyerData->sale_sgst }}</SGSTRATE>
                                <SGSTAMOUNT>{{ number_format($buyerData->samt, 2) }}</SGSTAMOUNT>
                                <IGSTRATE>{{ $buyerData->sale_igst }}</IGSTRATE>
                                <IGSTAMOUNT>{{ number_format($buyerData->iamt, 2) }}</IGSTAMOUNT>
                                <TOTALTAXAMOUNT>{{ number_format($buyerData->camt + $buyerData->samt + $buyerData->iamt, 2) }}</TOTALTAXAMOUNT>
                            </HSNSSUMMARY.LIST>
                        @endforeach

                        <!-- Bill Allocations -->
                        <BILLALLOCATIONS.LIST>
                            <BILLTYPE>New Ref</BILLTYPE>
                            <NAME>{{  $BuyerPurchaseOrderMasterList[0]->sale_code ?? ''}}</NAME>
                            <BILLCREDITPERIOD>{{  $BuyerPurchaseOrderMasterList[0]->mode_of_payment ?? '' }}</BILLCREDITPERIOD>
                            <AMOUNT>{{ number_format($total_material_value + $totalAmount, 2) }}</AMOUNT>
                        </BILLALLOCATIONS.LIST>

                        <!-- Invoice Order List -->
                        <INVOICEORDERLIST.LIST>
                            <BASICPURCHASEORDERNO>{{  $BuyePO[0]->buyer_po_nos ?? '' }}</BASICPURCHASEORDERNO>
                            <BASICORDERDATE>{{  $BuyerPurchaseOrderMasterList[0]->order_date ?? '' }}</BASICORDERDATE>
                        </INVOICEORDERLIST.LIST>

                        <!-- Final Amount -->
                        <BASICFINALAMOUNT>{{ number_format($total_material_value + $totalAmount, 2) }}</BASICFINALAMOUNT>
                        <BASICFINALAMOUNTINWORDS>{{ ucfirst(convertNumberToWords($total_material_value + $totalAmount)) }} Rupees Only</BASICFINALAMOUNTINWORDS>

                        <!-- Misc -->
                        <ISPARTYLEDGER>Yes</ISPARTYLEDGER>
                        <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>

                    </VOUCHER>
                </TALLYMESSAGE>
            </REQUESTDATA>
        </IMPORTDATA>
    </BODY>
</ENVELOPE>
