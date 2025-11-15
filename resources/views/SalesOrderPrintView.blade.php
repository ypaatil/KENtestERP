@php
function indianNumberFormat($num) {
$num = (string) $num;
$afterDecimal = '';
if (strpos($num, '.') !== false) {
list($num, $afterDecimal) = explode('.', $num);
$afterDecimal = '.' . $afterDecimal;
}
$lastThree = substr($num, -3);
$restUnits = substr($num, 0, -3);
if ($restUnits != '') {
$lastThree = ',' . $lastThree;
}
$restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
return $restUnits . $lastThree . $afterDecimal;
}
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
  @php setlocale(LC_MONETARY, 'en_IN'); @endphp
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Order | Ken Global Designs Pvt. Ltd. </title>
  <!-- Web Fonts -->
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
  <!-- Stylesheets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    @font-face {
      font-family: "Gotham";
      src: url("Gotham Fonts Family/Gotham-Regular.woff2") format("woff2"),
        url("Gotham Fonts Family/Gotham-Regular.woff") format("woff"),
        url("Gotham Fonts Family/Gotham-Regular.ttf") format("truetype");
      font-weight: 400;
      font-style: normal;
    }

    body {
      /* font-family: "Times New Roman", Times, serif; */
      background-color: #f8f9fa;
      color: #000000ff;
      padding: 20px;
    }

    .invoice-container {
      max-width: 1200px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
    }

    .table-bordered td,
    .table-bordered th {
      border: 1px solid #0c0c0c;
      padding: 8px;
    }

    .table-bordered th {
      vertical-align: middle;
      text-align: center;
    }

    .table {
      width: 100%;
      border-collapse: collapse;

    }

    .imgs {
      max-width: 80px;
      max-height: 80px;
    }

    thead tr {

      text-align: center;
    }

    tfoot tr {

      text-align: center;
      border: 1px solid;
    }

    :root {
      --label-width: 160px;
    }

    .info-row {
      display: flex;
      margin-bottom: -2px;
    }

    .info-row .label {
      font-weight: bold;
      width: var(--label-width);
      min-width: var(--label-width);
    }

    .info-row .colon {
      width: 15px;
      text-align: center;
    }

    .info-row .value {
      flex: 1;
    }


    /*     PRINT STYLES      */

    @media print {
      body {
        /* font-family: "Times New Roman", Times, serif; */
        background-color: #f8f9fa;
        color: #000000ff;
        padding: 20px;
      }

      #printInvoice,
      #printInvoice * {
        visibility: visible;
      }

      #printInvoice {
        position: absolute;
        left: 0;
        top: 0;

        width: 100%;
        max-width: 100% !important;
        margin: 0;
        padding: 5mm;
        background: white;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }



      .btn,
      .d-print-none {
        display: none !important;
      }

      /* General text */
      body,
      html,
      #printInvoice {

        line-height: 1.5 !important;
        color: #000 !important;
      }

      .info-row .label,
      .info-row .colon {

        font-weight: bold !important;
      }

      /* Table styles */
      .table {
        width: 100% !important;

        border-collapse: collapse !important;

        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        padding: 2px;
      }

      @media print {

        .table-bordered th {
          vertical-align: middle;
          text-align: center;
          white-space: nowrap;

        }

        th,
        td {
          white-space: normal !important;
          /* allow text to wrap */

        }

        #printInvoice {
          width: 100% !important;
          max-width: 100% !important;
        }
      }


      .table-bordered td,
      .table-bordered th {

        font-size: 11pt !important;

        text-align: center !important;
      }

      thead tr,
      tfoot tr {

        -webkit-print-color-adjust: exact;
        font-weight: bold !important;

      }

      /* Expand column widths */
      .row {
        display: flex !important;
        flex-wrap: nowrap !important;
        width: 100% !important;
        margin: 0;
      }

      .col-md-4 {
        width: 33.33% !important;
        flex: 0 0 33.33% !important;
      }

      .col-md-6 {
        width: 50% !important;
        flex: 0 0 50% !important;
      }

      .col-md-3 {
        width: 33.33% !important;
        flex: 0 0 33.33% !important;
      }

      .col-md-9 {
        width: 66.66% !important;
        flex: 0 0 66.66% !important;
      }

      .p-2 {
        padding: 12px !important;
      }

      body,
      html {
        overflow: visible !important;
      }
    }

    .verticalLine {
      border-right: solid grey;
      margin-right: 10px;

    }

    .double-color-hr {
      display: flex;
      width: 100%;
      height: 4px;
      margin: 0;
    }

    .double-color-hr .blue-part {
      width: 39%;
      background-color: #02659C;
    }

    .double-color-hr .green-part {
      width: 80%;
      background-color: #A8CF47;
    }

    /* 🔹 Make all borders black */
    .border,
    .border-end,
    .border-start,
    .border-bottom,
    .border-top,
    .table-bordered td,
    .table-bordered th {
      border-color: #000 !important;
    }

    .table-bordered tr th:first-child,
    .table-bordered tr td:first-child {
      border-left: none !important;
    }

    .table-bordered tr th:last-child,
    .table-bordered tr td:last-child {
      border-right: none !important;
    }


    .outer-border {
      border: 2px solid #000;
      padding: 0px !important;
      margin: 10px auto;
      max-width: 95%;
      background: #fff;
      height: 100%;
    }

    @media print {

      @page {
        size: A4;
        margin-top: 12mm;
        margin-right: 1mm;
        border: 1px solid #000;
        /* some browsers support this */
        height: 100%;
        margin-bottom: 10mm;
      }

      .outer-border {
        border: none !important;
        margin: 0 !important;

        padding: 0 !important;
        max-width: 100% !important;
        height: 100%;
      }

      .invoice-container img {
        max-width: 2000px;
        /* shrink logo if needed */
        height: 150px;
        margin-top: 5px;
      }

      .invoice-container h4 {
        font-size: 20pt;
        /* make text a little smaller for print */
      }

      .table-bordered tr th:first-child,
      .table-bordered tr td:first-child {
        border-left: none !important;
      }

      .table-bordered tr th:last-child,
      .table-bordered tr td:last-child {
        border-right: none !important;
      }


      .invoice-container h4 {
        font-size: 18pt;
        /* make text a little smaller for print */
      }

      .invoice-container p {
        font-size: 12pt;
      }

      td.text-start {
        text-align: left !important;
      }

      td.text-end {
        text-align: right !important;
      }

      /* Set fixed column widths only for print */
      table.table-bordered th:nth-child(1),
      table.table-bordered td:nth-child(1) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(2),
      table.table-bordered td:nth-child(2) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(3),
      table.table-bordered td:nth-child(3) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(4),
      table.table-bordered td:nth-child(4) {
        width: auto !important;
        /* Garment Color column */
      }

      table.table-bordered th:nth-child(5),
      table.table-bordered td:nth-child(5) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(6),
      table.table-bordered td:nth-child(6) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(7),
      table.table-bordered td:nth-child(7) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(8),
      table.table-bordered td:nth-child(8) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(9),
      table.table-bordered td:nth-child(9) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(10),
      table.table-bordered td:nth-child(10) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(11),
      table.table-bordered td:nth-child(11) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(12),
      table.table-bordered td:nth-child(12) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(13),
      table.table-bordered td:nth-child(13) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(14),
      table.table-bordered td:nth-child(14) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(15),
      table.table-bordered td:nth-child(15) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(16),
      table.table-bordered td:nth-child(16) {
        width: auto !important;
      }

      table.table-bordered th:nth-child(17),
      table.table-bordered td:nth-child(17) {
        width: auto !important;
      }

    }
  </style>
</head>

<body>
  <!-- Buttons -->
  <!-- Buttons -->
  <div class="  mb-3 d-print-none" style="margin-left: 1200px;">
    <button class="btn btn-primary" onclick="window.print()">
      Print
    </button>
    <!-- <button type="button" id="export_button" class="btn btn-warning" style="margin-left: 10px;">Export</button>   -->
  </div>


  <!-- Invoice Container -->
  <div class="container-fluid invoice-container">
    <div class="invoice">
      <main>
        <div id="printInvoice" class="outer-border">
          <!-- Header -->
          <div class="row">
            <div class="col-md-4 verticalLine text-center p-2">
              <img src="http://kenerp.com/logo/ken.jpeg" alt="Ken Enterprise Pvt. Ltd." height="130" width="230">
            </div>

            <div class="col-md-7" style="margin-top:10px;">
              <h4 class="mb-2 fw-bold">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
              <p>Reg.Office:18/20 Back Side Of Hotel City In, Industrial Estate, Ichalkaranji-416115<br>
                Tal Hatkanangale Dist Kolhapur Maharashtra INDIA.<br>
                Works: Gat No 298&299,At Kondigare, Tal: Shirol, Dist: Kolhapur-416102 <br>
                Tel : +91230 2438538 Email:office@kenindia.in
              </p>
            </div>

            <div class="col-md-2">
              <!-- <h6 class="fw-bold">Date: 15-09-2023</h6> -->
            </div>
          </div>

          <div class="double-color-hr">
            <div class="blue-part"></div>
            <div class="green-part"></div>
          </div>

          <h4 class="mb-0 fw-bold mt-6 text-center"> SALES ORDER</h4>

          <!-- Sales Info -->
          <div class="row  border-top border-bottom  g-0">
            <div class="col-md-4 p-2 border-end">
              <div class="info-row">
                <div class="label">Sales Order No</div>
                <div class="colon">:</div>
                <div class="value"> {{ $SalesOrderCostingMaster[0]->tr_code }}</div>
              </div>
              <div class="info-row">
                <div class="label">Execution SBU</div>
                <div class="colon">: </div>
                <div class="value"> @php if($SalesOrderCostingMaster[0]->in_out_id == 1){ echo "Inhouse"; }if($SalesOrderCostingMaster[0]->in_out_id == 2){ echo "Outsource"; } @endphp</div>
              </div>
              <div class="info-row">
                <div class="label">Order Type</div>
                <div class="colon">:</div>
                <div class="value">@php if($SalesOrderCostingMaster[0]->order_type==1){echo "Fresh" ;} if($SalesOrderCostingMaster[0]->order_type==2) {echo "Stock";} if($SalesOrderCostingMaster[0]->order_type==3) {echo "Job Work";} @endphp</div>
              </div>
              <div class="info-row">
                <div class="label">Buyer PO No</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->po_code }}</div>
              </div>
              <div class="info-row">
                <div class="label">Buyer Name</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->ac_name }} </div>
              </div>
              <div class="info-row">
                <div class="label">Brand Name</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->brand_name }}</div>
              </div>
            </div>
            <div class="col-md-4 p-2 border-end">
              <div class="info-row">
                <div class="label">Market</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->order_group_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">FOB (₹)</div>
                <div class="colon">:</div>
                <div class="value">{{ number_format($SalesOrderCostingMaster[0]->order_rate,2) }}</div>
              </div>
              <div class="info-row">
                <div class="label">Quantity</div>
                <div class="colon">:</div>
                <div class="value"> {{ number_format($SalesOrderCostingMaster[0]->total_qty) }}</div>
              </div>
              <div class="info-row">
                <div class="label">Order Value</div>
                <div class="colon">:</div>
                <div class="value">{{ indianNumberFormat(round($SalesOrderCostingMaster[0]->order_value)) }}</div>
              </div>
            </div>
            <div class="col-md-4 p-2">
              <div class="info-row">
                <div class="label">Style Category</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->fg_name }} </div>
              </div>
              <div class="info-row">
                <div class="label">Style Name</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->fg_name }} </div>
              </div>
              <div class="info-row">
                <div class="label">Style No</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->style_no }}</div>
              </div>
              <div class="info-row">
                <div class="label">Style Description</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->style_description }}</div>
              </div>
              <div class="info-row">
                <div class="label">SAM</div>
                <div class="colon">:</div>
                <div class="value">{{ number_format($SalesOrderCostingMaster[0]->sam,2) }}</div>
              </div>
            </div>
          </div>

          <!-- Second Row -->
          <div class="row  border-bottom  g-0">
            <div class="col-md-4 p-2  border-end">
              <div class="info-row">
                <div class="label">Payment Terms</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->ptm_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">Delivery Terms</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->delivery_term_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">Shipment Mode</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->ship_mode_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">Delivery Place</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->warehouse_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">Country</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->c_name }}</div>
              </div>
            </div>
            <div class="col-md-4 p-2 border-end">
              <div class="info-row">
                <div class="label">Order Received Date</div>
                <div class="colon">:</div>
                <div class="value">{{ \Carbon\Carbon::parse ($SalesOrderCostingMaster[0]->order_received_date ?? '')->format('d-m-Y') }}</div>
              </div>
              <div class="info-row">
                <div class="label">Plan Cut Date (PCD)</div>
                <div class="colon">:</div>
                <div class="value">{{ \Carbon\Carbon::parse ($SalesOrderCostingMaster[0]->plan_cut_date ?? '')->format('d-m-Y')  }}</div>
              </div>
              <div class="info-row">
                <div class="label">Inspection Date</div>
                <div class="colon">:</div>
                <div class="value"> {{ \Carbon\Carbon::parse($SalesOrderCostingMaster[0]->inspection_date ?? '')->format('d-m-Y') }}</div>
              </div>
              <div class="info-row">
                <div class="label">Shipment Date</div>
                <div class="colon">:</div>
                <div class="value">{{ \Carbon\Carbon::parse( $SalesOrderCostingMaster[0]->shipment_date ?? '')->format('d-m-Y') }}</div>
              </div>
            </div>
            <div class="col-md-4 p-2">
              <div class="info-row">
                <div class="label">Bulk Merchant Name</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->merchant_name }}</div>
              </div>
              <div class="info-row">
                <div class="label">PD Merchant Name</div>
                <div class="colon">:</div>
                <div class="value">{{ $SalesOrderCostingMaster[0]->PDMerchant_name }}</div>
              </div>
            </div>
          </div>
          @php $SizeDetailList = App\Models\SizeDetailModel::where('size_detail.sz_code','=', $SalesOrderCostingMaster[0]->sz_code)->get();
          @endphp
          <!-- Assortment Table -->
          <h4 class="text-center mt-6 fw-bold">Assortment Details</h4>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Item Code</th>
                <th style="white-space: nowrap;">Fabric Color Code</th>

                <th>Garment Color</th>
                @foreach($SizeDetailList as $sz)
                <th>{{ $sz->size_name }}</th>
                @endforeach
                <th>Total</th>
                <th>UOM</th>
                <th>Allow %</th>
              </tr>
            </thead>
            <tbody>
              @php
              $BuyerPurchaseOrderDetaillist = App\Models\BuyerPurchaseOrderDetailModel::
              join('item_master','item_master.item_code','=','buyer_purchase_order_detail.item_code')
              ->join('color_master','color_master.color_id','=','buyer_purchase_order_detail.color_id')
              ->join('unit_master','unit_master.unit_id','=','buyer_purchase_order_detail.unit_id')
              ->where('buyer_purchase_order_detail.tr_code','=', $SalesOrderCostingMaster[0]->tr_code)
              ->get(['buyer_purchase_order_detail.*','item_master.item_name','item_master.item_image_path','color_master.color_name','unit_master.unit_name']);
              $no=1;
              $totalQty=0;
              @endphp
              @foreach($BuyerPurchaseOrderDetaillist as $rowDetail)
              @php
              $totalQty= $totalQty + $rowDetail->size_qty_total;
              @endphp
              <tr>
                <td class="text-center">{{ $no }}</td>
                <td class="text-center">{{ $rowDetail->item_code }}</td>
                <td class="text-start">{{ $rowDetail->item_name }} </td>

                <td class="text-start">{{ $rowDetail->color_name }}</td>
                @php
                $SizeQtyList=explode(',', $rowDetail->size_qty_array)
                @endphp
                @foreach($SizeQtyList as $size_id)
                <td class="text-end">{{ indianNumberFormat($size_id) }}</td>
                @endforeach
                <td class="text-end">{{ indianNumberFormat($rowDetail->size_qty_total)  }}</td>
                <td class="text-center">{{ $rowDetail->unit_name  }}</td>
                <td class="text-end">{{ indianNumberFormat($rowDetail->shipment_allowance) }}</td>
              </tr>
              @php
              $no=$no+1;
              @endphp
              @endforeach
            </tbody>
            <tfoot>
              <tr>

                <td colspan="4" class="text-end"><b>Total:</b></td>
                @php
                $SizeWsList=explode(',', $SalesOrderCostingMaster[0]->sz_ws_total);
                @endphp
                @foreach($SizeWsList as $sztotal)
                <td class="text-end">{{ indianNumberFormat($sztotal)  }}</td>
                @endforeach
                <td class="text-end">{{indianNumberFormat($totalQty) }}</td>
                <th class="text-end"></th>
                <th class="text-end"> </th>
              </tr>
            </tfoot>
          </table>

          <!-- Prepared & Verified -->
          <br>
          <br>
          <div class="row">
            <div class="col-md-9">
              <h5 class="text-4 mt-2">Prepared By: </h5>
            </div>
            <br>
            <br>
            <div class="col-md-3">
              <h5 class="text-4 mt-2" style="margin-left: -40px;">Authorised sign:</h5>


            </div>
          </div>
          <br>
          <!-- Footer -->
          <footer>
            <div class="row">
              <div class="col-md-6"><b>Order Remark:</b> {{ $SalesOrderCostingMaster[0]->narration }}</div>
            </div>
          </footer>

        </div>
      </main>
    </div>
  </div>

  <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>


  <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
  <script>
    function html_table_to_excel(type) {
      var data = document.getElementById('invoice');

      var file = XLSX.utils.table_to_book(data, {
        sheet: "sheet1"
      });

      XLSX.write(file, {
        bookType: type,
        bookSST: true,
        type: 'base64'
      });

      XLSX.writeFile(file, 'Sale Order.' + type);
    }

    const export_button = document.getElementById('export_button');

    export_button.addEventListener('click', () => {
      html_table_to_excel('xlsx');
    });

    $('#printInvoice').click(function() {
      Popup($('.invoice')[0].outerHTML);

      function Popup(data) {
        //window.print();
        return true;
      }
    });
  </script>



</body>

</html>