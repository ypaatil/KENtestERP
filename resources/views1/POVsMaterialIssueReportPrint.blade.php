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
      <div style="margin:10px;">
          <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
      </div>
      <!-- Container -->
      <div class="container-fluid invoice-container">
      <!-- Header -->
      <div class="invoice" id="invoice">
      <!-- Main Content -->
        <main>
         <!-- Item Details -->
         <div class="row">
            <div class="col-md-4">
               <p><img src="http://ken.korbofx.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
            </div>
            <div class="col-md-6">
               <h4 class="mb-0" style="font-weight:bold;">KEN GLOBAL DESIGNS PRIVATE LIMITED</h4>
               <h6 class="mb-0"><b>Address:</b> {{$FirmDetail->Address}}</h6>
               <h6 class="mb-0" style="margin-left:40px;"><b>GST No:</b> {{$FirmDetail->gst_no}} <b>PAN No:</b> {{$FirmDetail->pan_no}}</h6>
            </div>
            <div class="col-md-2">    
            </div>
         </div>
         <hr>
         <div class="">
            <h4 class="text-4">
            <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Material Issued Against Work/Process Order No				</h4>
         </div>
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
            <div class="row" style="border: #000000 solid 1px;">
               <div  class="col-md-4">
                  <b style="display: inline-block;text-align: left;" class="mt-1">Process/Work Order No:  </b> <span style="display: inline-block;text-align: right;"> {{ $vpo_code }} {{ $vw_code }} </span></br>     
                  <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{$sales_order_no }} </span></br>
               </div>
               <div  class="col-md-4">         
               </div>
               <div  class="col-md-4" >
               </div>
            </div>
         </div>
         @if($order_type==1)
         @if($VendorOrderList[0]->process_id==1)
         <!-- Passenger Details -->
         <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
         <div class="">
         <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
            <thead>
               <tr>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">SrNo</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">Item Name</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">Item Description</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">Classification</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">Color Name</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">UOM</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">PO Qty</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Qty</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">Balance</td>
                  <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Date</td>
                  <!--  $OrderList=DB::select("-->
                  <!-- select vendor_purchase_order_fabric_details.`item_code`, item_master.item_name,color_master.color_name,-->
                  <!-- vendor_purchase_order_fabric_details.`description`, vendor_purchase_order_fabric_details.`color_id`,-->
                  <!-- (select sum(meter) from fabric_outward_details where vpo_code='".$VendorOrderList[0]->vpo_code."' and-->
                  <!-- fabric_outward_details.item_code=vendor_purchase_order_fabric_details.item_code) as out_qty,-->
                  <!--sum(bom_qty) as bom_qty FROM `vendor_purchase_order_fabric_details` -->
                  <!-- inner join item_master on item_master.item_code=vendor_purchase_order_fabric_details.item_code -->
                  <!-- inner join color_master on color_master.color_id=vendor_purchase_order_fabric_details.color_id -->
                  <!-- where vendor_purchase_order_fabric_details.vpo_code='".$VendorOrderList[0]->vpo_code."'-->
                  <!-- group by vendor_purchase_order_fabric_details.vpo_code,vendor_purchase_order_fabric_details.item_code-->
                  <!-- ");-->
               </tr>
            </thead>
            <tbody>
               @php
               $totalOrderQty=0;
               $totalIssuedMeter=0;
               $OrderList=DB::select("
               select `fout_date`, fabric_outward_details.`item_code`, item_master.item_name, item_master.color_name,
               item_master.`item_description`, fabric_outward_details.meter, unit_master.unit_name, sum(meter) as out_qty,
               (select sum(bom_qty) from vendor_purchase_order_fabric_details where vpo_code='".$VendorOrderList[0]->vpo_code."' 
               and vendor_purchase_order_fabric_details.item_code=fabric_outward_details.item_code) as bom_qty,
               classification_master.class_name FROM `fabric_outward_details` 
               inner join item_master on item_master.item_code=fabric_outward_details.item_code 
               inner join classification_master on classification_master.class_id=item_master.class_id 
               inner join unit_master on unit_master.unit_id=item_master.unit_id 
               where fabric_outward_details.vpo_code='".$VendorOrderList[0]->vpo_code."'  and item_master.class_id in (1,2)
               group by fabric_outward_details.vpo_code,fabric_outward_details.item_code
               ");
               $no=1; 
               foreach ($OrderList as $row) 
               {
               @endphp
               <tr>
                  <td style="text-align:center;"> {{$no}}</td>
                  <td style="text-align:center;">{{ $row->item_name }}</td>
                  <td style="text-align:center;">{{ $row->item_description }}</td>
                  <td style="text-align:center;">{{ $row->class_name }}</td>
                  <td style="text-align:center;">{{ $row->color_name }}</td>
                  <td style="text-align:center;">{{ $row->unit_name }}</td>
                  <td style="text-align:right;">{{ number_format($row->bom_qty,2) }}</td>
                  <td style="text-align:right;">{{ number_format($row->out_qty,2) }}</td>
                  <td style="text-align:right;">{{number_format(($row->bom_qty-  $row->out_qty),2) }}</td>
                  <td style="text-align:center;">{{ $row->fout_date }}</td>
               </tr>
               @php
               $no=$no+1;     
               $totalOrderQty= $totalOrderQty + $row->bom_qty;
               $totalIssuedMeter= $totalIssuedMeter +  $row->out_qty;
               }
               @endphp
            <tfoot>
               <tr>
               <tr>
                  <td style="text-align:right;" colspan="6"> <b>Total </b></td>
                  <td colspan="1" style="text-align:right;"> <b>{{number_format($totalOrderQty,2)}} </b></td>
                  <td colspan="1" style="text-align:right;"> <b>{{number_format($totalIssuedMeter,2)}}</b> </td>
                  <td style="text-align:right;"  >  </td>
                  <td style="text-align:right;"  >  </td>
               </tr>
            </tfoot>
            </tbody>
            </tbody>
         </table>
         </div>
         @php
         $OrderList=DB::select("
         select `fout_date`, fabric_outward_details.`item_code`, item_master.item_name, item_master.color_name,
         item_master.`item_description`, fabric_outward_details.meter, unit_master.unit_name, sum(meter) as out_qty,
         (select sum(bom_qty) from vendor_purchase_order_trim_fabric_details where vpo_code='".$VendorOrderList[0]->vpo_code."' 
         and vendor_purchase_order_trim_fabric_details.item_code=fabric_outward_details.item_code) as bom_qty,
         classification_master.class_name FROM `fabric_outward_details` 
         inner join item_master on item_master.item_code=fabric_outward_details.item_code 
         inner join classification_master on classification_master.class_id=item_master.class_id 
         inner join unit_master on unit_master.unit_id=item_master.unit_id 
         where fabric_outward_details.vpo_code='".$VendorOrderList[0]->vpo_code."' and item_master.class_id not in (1,2) 
         group by fabric_outward_details.vpo_code,fabric_outward_details.item_code
         ");
         if(count($OrderList)>0)
         {
         @endphp
         <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trim Fabric Details:</h4>
         <div class="">
            <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
               <thead>
                  <tr>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">SrNo</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">Item Name</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">Item Description</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">Classification</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">Color Name</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">UOM</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">PO Qty</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Qty</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">Balance</td>
                     <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Date</td>
                  </tr>
               </thead>
               <tbody>
                  @php
                  $totalOrderQty=0;
                  $totalIssuedMeter=0;
                  $no=1; 
                  foreach ($OrderList as $row) 
                  {
                  @endphp
                  <tr>
                     <td style="text-align:center;"> {{$no}}</td>
                     <td style="text-align:center;">{{ $row->item_name }}</td>
                     <td style="text-align:center;">{{ $row->item_description }}</td>
                     <td style="text-align:center;">{{ $row->class_name }}</td>
                     <td style="text-align:center;">{{ $row->color_name }}</td>
                     <td style="text-align:center;">{{ $row->unit_name }}</td>
                     <td style="text-align:right;">{{ number_format($row->bom_qty,2) }}</td>
                     <td style="text-align:right;">{{ number_format($row->out_qty,2) }}</td>
                     <td style="text-align:right;">{{number_format(($row->bom_qty-  $row->out_qty),2) }}</td>
                     <td style="text-align:center;">{{ $row->fout_date }}</td>
                  </tr>
                  @php
                  $no=$no+1;     
                  $totalOrderQty= $totalOrderQty + $row->bom_qty;
                  $totalIssuedMeter= $totalIssuedMeter +  $row->out_qty;
                  }
                  @endphp
               <tfoot>
                  <tr>
                  <tr>
                     <td style="text-align:right;" colspan="6"> <b>Total </b></td>
                     <td colspan="1" style="text-align:right;"> <b>{{number_format($totalOrderQty,2)}} </b></td>
                     <td colspan="1" style="text-align:right;"> <b>{{number_format($totalIssuedMeter,2)}}</b> </td>
                     <td style="text-align:right;"  >  </td>
                     <td style="text-align:right;"  >  </td>
                  </tr>
               </tfoot>
               </tbody>
               </tbody>
            </table>
             </div>
            @php } @endphp
            @endif
            @if($VendorOrderList[0]->process_id==3)
            <!-- Passenger Details -->
            <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Packing Trims Details:</h4>
            <div class="">
               <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                  <thead>
                     <tr>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">SrNo</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">Item Name</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">Item Description</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">Classification</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">UOM</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Qty</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">Balance</td>
                        <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Date</td>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                     $totalOrderQty=0;
                     $totalIssuedMeter=0;
                     $OrderList=DB::select("
                     select trimsOutwardDetail.`item_code`, item_master.item_name, tout_date,class_name, unit_name,
                     item_master.`item_description`,  item_qty as out_qty, 
                     (select sum(bom_qty) from vendor_purchase_order_packing_trims_details where vpo_code='".$VendorOrderList[0]->vpo_code."' and
                     vendor_purchase_order_packing_trims_details.item_code=trimsOutwardDetail.item_code) as bom_qty
                     FROM `trimsOutwardDetail` 
                     inner join item_master on item_master.item_code=trimsOutwardDetail.item_code 
                     inner join classification_master on classification_master.class_id=item_master.class_id 
                     inner join unit_master on unit_master.unit_id=item_master.unit_id 
                     where trimsOutwardDetail.vpo_code='".$VendorOrderList[0]->vpo_code."'
                     group by trimsOutwardDetail.vpo_code,trimsOutwardDetail.item_code
                     order by trimsOutwardDetail.item_code
                     ");
                     $no=1; 
                     $poQty=0;
                     foreach($OrderList as $row) 
                     {
                     @endphp
                     <tr>
                        <td colspan="8"><b>PO Qty:</b> {{ number_format($row->bom_qty,2) }}</td>
                        @php   $poQty=round($row->bom_qty,2); @endphp
                     </tr>
                     <tr>
                        <td style="text-align:center;"> {{$no}}</td>
                        <td style="text-align:center;">{{ $row->item_name }}</td>
                        <td style="text-align:center;">{{ $row->item_description }}</td>
                        <td style="text-align:center;">{{ $row->class_name }}</td>
                        <td style="text-align:center;">{{ $row->unit_name }}</td>
                        <td style="text-align:right;">{{ number_format($row->out_qty,2) }}</td>
                        @php  $poQty=$poQty-$row->out_qty; @endphp
                        <td style="text-align:right;">{{number_format(($poQty),2) }}</td>
                        <td>{{$row->tout_date}}</td>
                     </tr>
                     @php
                     $no=$no+1;     
                     $totalOrderQty= $totalOrderQty + $row->bom_qty;
                     $totalIssuedMeter= $totalIssuedMeter +  $row->out_qty;
                     }
                     @endphp
                  <tfoot>
                     <tr>
                     <tr>
                        <td style="text-align:right;" colspan="2"> <b>Total </b></td>
                        <td colspan="1" style="text-align:right;"> <b>{{number_format($totalOrderQty,2)}} </b></td>
                        <td colspan="1" style="text-align:right;"> <b>{{number_format($totalIssuedMeter,2)}}</b> </td>
                        <td style="text-align:right;"  >  </td>
                     </tr>
                  </tfoot>
                  </tbody>
                  </tbody>
               </table>
               </div>
               @endif
               @else
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Sewing Trims Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">SrNo</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">Item Name</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">Item Description</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">Classification</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">UOM</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Qty</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">Balance</td>
                           <td rowspan="2" style="text-align:center; font-weight:bold;">O/W Date</td>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $totalOrderQty=0;
                        $totalIssuedMeter=0;
                        $OrderList=DB::select("select trimsOutwardDetail.`item_code`, item_master.item_name, tout_date,class_name, unit_name,
                        item_master.`item_description`,  item_qty as out_qty,   
                        (select sum(bom_qty) from vendor_work_order_sewing_trims_details where vw_code='".$VendorOrderList[0]->vw_code."' and
                        vendor_work_order_sewing_trims_details.item_code=trimsOutwardDetail.item_code) as bom_qty
                        FROM `trimsOutwardDetail` 
                        inner join item_master on item_master.item_code=trimsOutwardDetail.item_code 
                        inner join classification_master on classification_master.class_id=item_master.class_id 
                        inner join unit_master on unit_master.unit_id=item_master.unit_id 
                        where trimsOutwardDetail.vw_code='".$VendorOrderList[0]->vw_code."'
                        group by trimsOutwardDetail.vw_code,trimsOutwardDetail.item_code
                        order by trimsOutwardDetail.item_code");
                        $no=1; 
                        $poQty=0;
                        foreach($OrderList as $row) 
                        {
                        @endphp
                        <td colspan="8"> <b>PO Qty:</b> {{ number_format($row->bom_qty,2) }}</td>
                        @php   $poQty=round($row->bom_qty,2); @endphp
                        <tr>
                           <td style="text-align:center;"> {{$no}}</td>
                           <td style="text-align:center;">{{ $row->item_name }}</td>
                           <td style="text-align:center;">{{ $row->item_description }}</td>
                           <td style="text-align:center;">{{ $row->class_name }}</td>
                           <td style="text-align:center;">{{ $row->unit_name }}</td>
                           <td style="text-align:right;">{{ number_format($row->out_qty,2) }}</td>
                           @php  $poQty=$poQty-$row->out_qty; @endphp
                           <td style="text-align:right;">{{number_format(($poQty),2) }}</td>
                           <td>{{$row->tout_date}}</td>
                        </tr>
                        @php
                        $no=$no+1;     
                        $totalOrderQty= $totalOrderQty + $row->bom_qty;
                        $totalIssuedMeter= $totalIssuedMeter +  $row->out_qty;
                        }
                        @endphp
                     <tfoot>
                        <tr>
                        <tr>
                           <td style="text-align:right;" colspan="5"> <b>Total </b></td>
                           <td colspan="1" style="text-align:right;"> <b>{{number_format($totalOrderQty,2)}} </b></td>
                           <td colspan="1" style="text-align:right;"> <b>{{number_format($totalIssuedMeter,2)}}</b> </td>
                           <td style="text-align:right;"  >  </td>
                        </tr>
                     </tfoot>
                     </tbody>
                     </tbody>
                  </table>
               </div>
                  @endif
         </main>
        </div>
      </div>
      <p class="text-center d-print-none"><a href="/SalesOrderCosting">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://ken.korbofx.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
     
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Material Issued Against Work/Process Order No Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
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