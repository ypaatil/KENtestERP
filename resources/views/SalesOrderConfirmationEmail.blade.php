<html
<body>
@php setlocale(LC_MONETARY, 'en_IN'); @endphp     
    <div  style="max-width: 600px;">
        @php
            $salesOrderData = DB::SELECT("SELECT buyer_purchse_order_master.tr_code,order_type_master.order_type,ledger_master.ac_short_name,buyer_purchse_order_master.total_qty,brand_master.brand_name,delivery_term_name,shipment_date,
                                 buyer_purchse_order_master.order_rate,buyer_purchse_order_master.order_value,merchant_master.merchant_name,PDMerchant_master.PDMerchant_name,currency_master.currency_name,main_style_master.mainstyle_name,buyer_purchse_order_master.style_description,
                                 buyer_purchse_order_master.sam,buyer_purchse_order_master.style_no
                                 FROM buyer_purchse_order_master 
                                 INNER JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                                 INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                                 INNER JOIN delivery_terms_master ON delivery_terms_master.dterm_id = buyer_purchse_order_master.dterm_id
                                 INNER JOIN order_type_master ON order_type_master.orderTypeId = buyer_purchse_order_master.order_type
                                 INNER JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
                                 INNER JOIN PDMerchant_master ON PDMerchant_master.PDMerchant_id = buyer_purchse_order_master.PDMerchant_id
                                 INNER JOIN currency_master ON currency_master.cur_id = buyer_purchse_order_master.currency_id
                                 INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                                 WHERE tr_code='".$param1."'");
                                 
                                 
            $sales_order_no = isset($salesOrderData[0]->tr_code) ? $salesOrderData[0]->tr_code : "";
            $order_type = isset($salesOrderData[0]->order_type) ? $salesOrderData[0]->order_type : "";
            $buyer_name = isset($salesOrderData[0]->ac_short_name) ? $salesOrderData[0]->ac_short_name : "";
            $brand_name = isset($salesOrderData[0]->brand_name) ? $salesOrderData[0]->brand_name : "";
            $total_qty = isset($salesOrderData[0]->total_qty) ? $salesOrderData[0]->total_qty : "";
            $order_rate = isset($salesOrderData[0]->order_rate) ? $salesOrderData[0]->order_rate : "";
            $order_value = isset($salesOrderData[0]->order_value) ? $salesOrderData[0]->order_value : "";
            $delivery_term_name = isset($salesOrderData[0]->delivery_term_name) ? $salesOrderData[0]->delivery_term_name : "";
            $shipment_date = isset($salesOrderData[0]->shipment_date) ? $salesOrderData[0]->shipment_date : "-";
            $merchant_name = isset($salesOrderData[0]->merchant_name) ? $salesOrderData[0]->merchant_name : "-";
            $PDMerchant_name = isset($salesOrderData[0]->PDMerchant_name) ? $salesOrderData[0]->PDMerchant_name : "-";
            $currency_name = isset($salesOrderData[0]->currency_name) ? $salesOrderData[0]->currency_name : "-";
            $mainstyle_name = isset($salesOrderData[0]->mainstyle_name) ? $salesOrderData[0]->mainstyle_name : "-";
            $style_description = isset($salesOrderData[0]->style_description) ? $salesOrderData[0]->style_description : "-";
            $sam = isset($salesOrderData[0]->sam) ? $salesOrderData[0]->sam : "-";
            $style_no = isset($salesOrderData[0]->style_no) ? $salesOrderData[0]->style_no : "-";
            
        @endphp
             <p>
                Hi,<br/>
                Pleased to inform you that a new sales order has been successfully saved in our ERP system.<br/><br/>
                <b>PD Merchant Name:</b> {{$PDMerchant_name}}<br/>
                <b>Bulk Merchant Name:</b> {{$merchant_name}}<br/>
                <br/>
                <table  style="border-collapse: collapse; width: 100%;"> 
                    <tr>
                        <th colspan=2  style="padding: 8px;width: 100px;text-align:center"><b>Order Details</b></th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Sales Order No.</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$sales_order_no}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Order Type</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$order_type}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Buyer Name</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$buyer_name}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Buyer Brand</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$brand_name}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Order Quantity</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{number_format($total_qty, 2, '.', ',')}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Style Category</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$mainstyle_name}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Style Description</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$style_description}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Style No</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$style_no}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">SAM</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{sprintf("%.2f", $sam)}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Order Rate</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{sprintf("%.2f", $order_rate)}} ({{$currency_name}})</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Order Value</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{number_format($order_value, 2, '.', ',')}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Delivery Terms</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{$delivery_term_name}}</td>
                    </tr>
                    <tr>
                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">Delivery Date</th>
                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;width: 100px;">{{date("d M Y",strtotime($shipment_date))}}</td>
                    </tr>
                </table> 
                If any of the above details are incorrect or if you have questions regarding sales order entry, Please reply to the same email.<br/>
                <b>Thanks and Regards,</b><br/>
                <b>Nikhil Bhosale</b><br/>
            </p>
   </div>         
</body>
</html>