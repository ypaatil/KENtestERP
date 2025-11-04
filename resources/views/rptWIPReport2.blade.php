<!DOCTYPE html>
<html lang="en">
   <head>
       @php setlocale(LC_MONETARY, 'en_IN');  @endphp
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
         <a  href="javascript:void(0)" id="printPage" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">WIP Report - 2</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                             <tr>
                                 <th colspan="4"></th>
                                 <th class="text-center">Order Qty</th>
                                 <th class="text-center" colspan="2">Cutting</th>
                                 <th class="text-center" colspan="2">Sewing</th>
                                 <th class="text-center" colspan="2">Packing</th>
                                 <th class="text-center" colspan="2">Dispatch</th>
                             </tr>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Sr No</th>
                                   <th nowrap>Vendor Name</th>
                                   <th nowrap>Sales Order No</th>
                                   <th nowrap>Color</th>
                                   <th class="text-center" nowrap>Qty</th>
                                   <th class="text-center" nowrap>Qty</th>
                                   <th class="text-center" nowrap>WIP</th>
                                   <th class="text-center" nowrap>Qty</th>
                                   <th class="text-center" nowrap>WIP</th>
                                   <th class="text-center" nowrap>Qty</th>
                                   <th class="text-center" nowrap>WIP</th>
                                   <th class="text-center" nowrap>Qty</th>
                                   <th class="text-center" nowrap>WIP</th>
                              </tr>
                         </thead>
                         <tbody>
                            @php
                                $srno = 1;
                            @endphp
                            @foreach($Buyer_Purchase_Order_List as $row)  
                            @php
                                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                                     where  sales_order_no='".$row->tr_code."'");
                                
                                if($vendorId > 0)
                                {
                                    $vendor = " AND vendorId =".$vendorId;
                                }
                                else
                                {
                                    $vendor = "";
                                }
                                $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' ".$vendor." AND cpg_date='".$date."'");
                                
                                if(count($CutPanelData) > 0)
                                {
                                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                                }
                                else
                                {
                                        $cutPanelIssueQty = 0;
                                }
                                
                                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                                     where  sales_order_no='".$row->tr_code."' AND Ac_code=".$row->Ac_code." AND sti_date='".$date."'");
                                
                                if(count($StichingData) > 0)
                                {
                                        $stichingQty = $StichingData[0]->stiching_qty;
                                }
                                else
                                {
                                        $stichingQty = 0;
                                }
                                
                                
                               $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."'".$vendor." AND pki_date='".$date."'");
      
                               if(count($PackingData) > 0)
                               {
                                     $pack_order_qty = $PackingData[0]->total_qty;
                               }
                               else
                               {
                                     $pack_order_qty = 0;
                               }
                               
                               
                              if($row->order_type == 1)
                              {
                                  $order_type = 'Fresh';
                              }
                              else if($row->order_type == 2)
                              {
                                  $order_type = 'Stock';
                              }
                              else if($row->order_type == 3)
                              {
                                  $order_type = 'Job Work';
                              }
                              else
                              {
                                  $order_type = '';
                              }
                              
                              $sewing = $cutPanelIssueQty - $stichingQty;
                              
                              
                              $SaleTransactionData=DB::select("SELECT ifnull(sum(order_qty),0) as dispatch_qty from sale_transaction_detail  
                                     where  sales_order_no='".$row->tr_code."' AND Ac_code=".$row->Ac_code." AND sale_date='".$date."'");
                              
                               if(count($SaleTransactionData) > 0)
                               {
                                     $disptch = $SaleTransactionData[0]->dispatch_qty;
                               }
                               else
                               {
                                     $disptch = 0;
                               }
                               
                                     
                            @endphp
                            <tr>
                               <td nowrap>{{ $srno++ }}</td>
                               <td nowrap>{{ $row->vendorName  }}</td>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{$row->color_name}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty)}}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",$cutPanelIssueQty) }}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($VendorData[0]->work_order_qty - $cutPanelIssueQty)) }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($stichingQty))}}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($sewing)) }} </td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($pack_order_qty)) }}</td>
                               <td class="text-right">{{ money_format("%!.0n",($stichingQty - $pack_order_qty)) }} </td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($disptch)) }}</td>
                               <td class="text-right">{{ money_format("%!.0n",($pack_order_qty - $disptch)) }} </td>
                            </tr>
                            @endforeach
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated WIP Report-2</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

   
   
  $('#printPage').click(function(){
      Popup($('.invoice')[0].outerHTML);
      function Popup(data) 
      {
          window.print();
          return true;
      }
      });
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'WIP Report-2.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     $(document).ready(function(){
            var result = [];
            $('table tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text().replace(/,/g , ''));
               });
            });
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            $('table').append('<tr><td colspan="4" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var y=Math.round(this);
                y=y.toString();
                var lastThree1 = y.substring(y.length-3);
                var otherNumbers1 = y.substring(0,y.length-3);
                if(otherNumbers1 != '')
                    lastThree1 = ',' + lastThree1;
                var res1 = otherNumbers1.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree1;
               $('table tr').last().append('<td class="text-right"><strong>'+res1+'</strong></td>')
            });
      });
      
   </script>
</html>