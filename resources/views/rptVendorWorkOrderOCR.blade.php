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
          table{
          display: table;
          width:100%;
          border-collapse:collapse;
          }
          tr {
          display: table-row;
          padding: 2px;
          }
          tr p {
          margin: 0px !important; 
          }
          td,th {
          display: table-cell;
          padding: 8px;
          width: 410px;
          border: #000000 solid 1px;
          font-size:14px !important;
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
          
          .invoice-container{
                  border: none;
          }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid invoice-container">
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">VENDOR WORK ORDER OCR REPORT</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th nowrap>VENDOR</th>
                           <th nowrap>Style Code</th>
                           <th nowrap>AOP</th>
                           <th nowrap>Work Order No</th>
                           <th nowrap>DESIGN CODE</th>
                           <th nowrap>Order Qty.</th>
                           <th nowrap>Cons(3%)</th>
                           <th nowrap>Fab issued</th>
                           <th nowrap>Fab Utilised</th>
                           <th nowrap>Bal Fabric</th>
                           <th nowrap>Fab Returned</th>
                           <th nowrap class="text-center">CUT QTY</th>
                           <th nowrap class="text-center">SEW QTY</th>
                           <th nowrap class="text-center">PKG</th>
                           <th nowrap class="text-center">SAMPLE</th>
                           <th nowrap class="text-center">REJECTIONS</th>
                           <th nowrap class="text-center">TOTAL QTY</th>
                           <th nowrap class="text-center">DEVIATION</th>
                           <th nowrap class="text-center">REMARK</th>
                        </tr>
                     </thead>
                     <tbody>
                         @php 
                            $colorData = DB::select("SELECT vendor_work_order_detail.color_id FROM vendor_work_order_detail WHERE 
                                vendor_work_order_detail.sales_order_no = '".$sales_order_no."' AND vendor_work_order_detail.vw_code = '".$vw_code."'");
                            
                            $colorArr = "";  
                            foreach($colorData AS $array) 
                            {
                               
                               $colorArr = $colorArr.$array->color_id.',';
                            }
                            $colorArr = rtrim($colorArr,',');
                            
                         @endphp
                         @foreach($VendorWorkOrderList as $row)
                         @php
                            $orderQty =DB::select("SELECT ifnull(SUM(vendor_purchase_order_detail.size_qty_total),0) as OrderQty  FROM `vendor_purchase_order_master`  
                                INNER JOIN vendor_purchase_order_detail ON vendor_purchase_order_detail.vpo_code = vendor_purchase_order_master.vpo_code
                                WHERE vendor_purchase_order_master.vendorId = '".$row->vendorId."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND vendor_purchase_order_master.process_id = 1 
                                AND vendor_purchase_order_detail.color_id IN ($colorArr)");
                                
                            $CUTQTY =DB::select("SELECT SUM(total_qty) as total_qty FROM `cut_panel_grn_master` WHERE vpo_code 
                                IN (SELECT vpo_code FROM vendor_purchase_order_detail WHERE vendorId = '".$row->vendorId."' AND `sales_order_no`='".$row->sales_order_no."' AND process_id=1 AND color_id IN ($colorArr))");
                                
                            $pkgQty =DB::select("SELECT ifnull(SUM(packing_inhouse_detail.size_qty_total),0) as pkgQty  FROM `packing_inhouse_detail` 
                                 WHERE vpo_code IN (SELECT vpo_code FROM vendor_purchase_order_detail WHERE vendorId = '".$row->vendorId."' AND `sales_order_no`='".$row->sales_order_no."' AND process_id=3 AND color_id IN ($colorArr))");
                         
                            $rejectQty =DB::select("SELECT ifnull(SUM(qcstitching_inhouse_reject_detail.size_qty_total),0) as rejectQty  FROM `qcstitching_inhouse_reject_detail` 
                                  WHERE vw_code = '".$row->vw_code."' AND vendorId = '".$row->vendorId."' AND `sales_order_no`='".$row->sales_order_no."'");
                                  
                                  
                            $StitchQty =DB::select("SELECT ifnull(SUM(stitching_inhouse_detail.size_qty_total),0) as StitchQty  FROM `stitching_inhouse_detail` 
                                  WHERE vw_code = '".$row->vw_code."' AND vendorId = '".$row->vendorId."' AND `sales_order_no`='".$row->sales_order_no."'");
                                 
                            $fabReturned =DB::select("SELECT ifnull(sum(total_meter),0) as fabReturned FROM inward_master WHERE isReturnFabricInward = 1 AND vw_code = '".$row->vw_code."'"); 
                         @endphp 
                        <tr>
                           <td>{{$row->ac_name}}</td>
                           <td>{{$row->fg_name}}</td>
                           <td>-</td>
                           <td>{{$row->sales_order_no}}</td>
                           <td>{{$row->style_no}}</td>
                           <td>{{$row->final_bom_qty ? $row->final_bom_qty : 0}}</td>
                           <td>{{$row->cons_per_piece}}</td>
                           <td>{{$orderQty[0]->OrderQty}}</td>
                           <td>{{$CUTQTY[0]->total_qty * $row->cons_per_piece}}</td>
                           <td>{{($orderQty[0]->OrderQty) - ($CUTQTY[0]->total_qty * $row->cons_per_piece)}}</td>
                           <td>{{$fabReturned[0]->fabReturned}}</td>
                           <td>{{$CUTQTY[0]->total_qty}}</td>
                           <td>{{$StitchQty[0]->StitchQty}}</td>
                           <td>{{$pkgQty[0]->pkgQty}}</td>
                           <td>-</td>
                           <td>{{$rejectQty[0]->rejectQty}}</td>
                           <td>{{$pkgQty[0]->pkgQty + $rejectQty[0]->rejectQty}}</td>
                           <td>{{$CUTQTY[0]->total_qty - ($pkgQty[0]->pkgQty + $rejectQty[0]->rejectQty)}}</td>
                           <td>-</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated VENDOR WORK ORDER OCR REPORT</p>
      <p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   $('#printInvoice').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
      
     document.getElementById("doPrint").addEventListener("click", function() {
     var printContents = document.getElementById('invoice').innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     });
     
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'VENDOR WORK ORDER OCR REPORT.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
    //   $(document).ready(function(){
    //         var result = [];
    //         $('table tr').each(function(){
    //           $('td', this).each(function(index, val){
    //               if(!result[index]) result[index] = 0;
    //               result[index] += parseFloat($(val).text() ? $(val).text() : 0);
    //           });
    //         });
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //          result.shift();
    //         var p = 0;
    //         $(result).each(function(){
    //             var x=this;
    //             x=x.toString();
    //             var afterPoint = '';
    //             if(x.indexOf('.') > 0)
    //               afterPoint = x.substring(x.indexOf('.'),x.length);
    //             x = Math.floor(x);
    //             x=x.toString();
    //             var lastThree = x.substring(x.length-3);
    //             var otherNumbers = x.substring(0,x.length-3);
    //             if(otherNumbers != '')
    //                 lastThree = ',' + lastThree;
    //             var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
    //           if(res == 'NaN')
    //           {
    //               res = "-";
    //           }
    //           else
    //           {
    //               res1 = res.split('.');
    //               if(res1.length > 1)
    //               {
    //                  res2 = "."+res1[1].substr(0, 2);
    //               }
    //               else
    //               {
    //                   res2  = "";
    //               }
    //               res = res1[0]+""+res2;
    //           }
    //           $('#totalColumns').append('<td class="text-right" id="total'+p+'"><strong>'+res+'</strong></td>');
    //           p++;
    //         });
    //   });
   </script>
</html>