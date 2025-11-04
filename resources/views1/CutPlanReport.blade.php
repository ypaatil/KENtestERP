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
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
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
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Inward and Outward Stock Report Till Date				</h4>
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
                        <b style="display: inline-block;text-align: left;" class="mt-1">Cutting PO No:  </b> <span style="display: inline-block;text-align: right;"> {{ $vpo_code }} </span></br>     
                        <b style="display: inline-block;text-align: left;" class="mt-1">Sales Order no:  </b> <span style="display: inline-block;text-align: right;"> {{ $sales_order_no }} </span></br>
                     </div>
                     <div  class="col-md-4">         
                     </div>
                     <div  class="col-md-4" >
                     </div>
                  </div>
               </div>
               <!-- Passenger Details -->
               <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Fabric Details:</h4>
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     @php 
                     $BuyerPurchaseOrderMasterList =App\Models\BuyerPurchaseOrderMasterModel::find($sales_order_no);
                     $SizeDetailList = App\Models\SizeDetailModel::select('size_id','size_name')->where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
                     $sizes='';
                     $no=1;
                     foreach ($SizeDetailList as $sz) 
                     {
                     $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                     $no=$no+1;
                     }
                     $sizes=rtrim($sizes,',');
                     @endphp
                     <thead>
                        <tr>
                           <th rowspan="2">Date</th>
                           <th rowspan="2">Color</th>
                           @php  foreach ($SizeDetailList as $sz) 
                           {@endphp
                           <th><b>{{$sz->size_name}}</b></th>
                           @php }
                           @endphp
                           <th rowspan="2">Total</th>
                           <th rowspan="2">Fabric Used </th>
                        </tr>
                     </thead>
                     <tbody>
                        @php
                        $totalCuttingQty=0;
                        $totalUsedMeter=0;
                        $CutPlanList=DB::select("select bundle_barcode_serial_details.task_id,bb_date,bundle_barcode_serial_details.item_code, item_master.item_name,sizes_id,
                        (select sum(meter) from fabric_outward_details where fabric_outward_details.vpo_code='".$vpo_code."' 
                        and fabric_outward_details.item_code=bundle_barcode_serial_details.item_code)   as TotalMeter,
                        (select sum(size_qty_total) from vendor_purchase_order_detail where vendor_purchase_order_detail.vpo_code='".$vpo_code."' 
                        and vendor_purchase_order_detail.item_code=bundle_barcode_serial_details.item_code)   as CutPoQty
                        from 
                        bundle_barcode_serial_details
                        inner join item_master on item_master.item_code=bundle_barcode_serial_details.item_code
                        inner join size_detail on size_detail.size_id=bundle_barcode_serial_details.sizes_id
                        where vpo_code='".$vpo_code."'
                        group by vpo_code,bundle_barcode_serial_details.task_id,bb_date,bundle_barcode_serial_details.item_code");
                        foreach ($CutPlanList as $row) 
                        {
                        @endphp
                        <tr>
                           <td colspan="{{count($SizeDetailList)+2}}"><b>Color PO Qty: {{ $row->CutPoQty }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Fabric Meter Issued: {{ $row->TotalMeter }} </b></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td>{{ date('d-m-Y',strtotime($row->bb_date))  }}</td>
                           <td>{{ $row->item_name }}</td>
                           @php   $totalCut = 0; $meter=0;
                           foreach ($SizeDetailList as $sz) 
                           {
                           //DB::enableQueryLog();  
                           $CuttingQty=DB::select("select ifnull(sum(layers),0) as qty, (select ifnull(sum(used_meter),0) from cutting_balance_details
                           inner join cutting_master on cutting_master.cu_code=cutting_balance_details.cu_code
                           where cutting_master.table_task_code=bundle_barcode_serial_details.task_id and
                           bundle_barcode_serial_details.vpo_code='".$vpo_code."' and cutting_balance_details.item_code='".$row->item_code."'  ) as meter
                           from 
                           bundle_barcode_serial_details
                           inner join item_master on item_master.item_code=bundle_barcode_serial_details.item_code
                           inner join size_detail on size_detail.size_id=bundle_barcode_serial_details.sizes_id
                           where vpo_code='".$vpo_code."' and sizes_id='".$sz->size_id."' and bundle_barcode_serial_details.item_code='".$row->item_code."' and
                           bundle_barcode_serial_details.task_id='".$row->task_id."'
                           group by bundle_barcode_serial_details.task_id,   sizes_id");
                           // $query = DB::getQueryLog();
                           // $query = end($query);
                           // dd($query);
                           if(isset($CuttingQty[0]->qty))
                           {echo '
                           <td style="text-align:right;">'.$CuttingQty[0]->qty.'</td>
                           ';
                           $meter=$CuttingQty[0]->meter;
                           $totalCut= $totalCut + $CuttingQty[0]->qty;
                           }
                           else
                           {
                           echo '
                           <td style="text-align:right;">0</td>
                           ';
                           $meter=0;
                           }
                           }
                           @endphp
                           <td style="text-align:right;">{{$totalCut}}</td>
                           <td style="text-align:right;">{{round($meter,2)}}</td>
                        </tr>
                        @php
                        $no=$no+1;     
                        $totalCuttingQty= $totalCuttingQty + $totalCut;
                        $totalUsedMeter= $totalUsedMeter +  round($meter,2);
                        }
                        @endphp
                     <tfoot>
                        <tr>
                        <tr>
                           <td style="text-align:right;" colspan="{{count($SizeDetailList)+2}}"> <b>Total </b></td>
                           <td colspan="1" style="text-align:right;"> <b>{{$totalCuttingQty}} </b></td>
                           <td colspan="1" style="text-align:right;"> <b>{{$totalUsedMeter}}</b> </td>
                        </tr>
                     </tfoot>
                     </tbody>
                     </tbody>
                  </table>
               </div>
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

        XLSX.writeFile(file, 'Fabric Inward and Outward Stock Report Till Date REPORT.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      function changeCurrency(ele)
      {
            var x=Math.round(ele);
            x=x.toString();
            var lastThree = x.substring(x.length-3);
            var otherNumbers = x.substring(0,x.length-3);
            if(otherNumbers != '')
                lastThree = ',' + lastThree;
            var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
                    
            return res;
      }
      
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