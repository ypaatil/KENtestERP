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
          background-image: url('http://ken.korbofx.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
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
                  <h4 class="mb-0">WIP Report - 1</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Code</th>
                                   <th nowrap>Order Type</th>
                                   <th nowrap>PO status</th>
                                   <th class="text-center" nowrap>Received Date</th>
                                   <th class="text-center" nowrap>Plan Cut Date</th>
                                   <th class="text-center" nowrap>Shipment Date</th>
                                   <th class="text-center" nowrap>Vendor Name With Qty</th>
                                   <th class="text-center" nowrap>Buyer Name</th>
                                   <th class="text-center" nowrap>PO No</th>
                                   <th class="text-center" nowrap>Buyer Brand</th>
                                   <th class="text-center" nowrap>Main Style Category</th>
                                   <th class="text-center" nowrap>Style Name</th>
                                   <th class="text-center" nowrap>SAM</th>
                                   <th class="text-center" nowrap>Order Rate</th>
                                   <th class="text-center" nowrap>Stock Rate</th>
                                   <th class="text-center" nowrap>Order Qty</th>
                                   <th class="text-center" nowrap>Work Order Qty</th>
                                   <th class="text-center" nowrap>Total Open Work Order Qty</th>
                                   <th class="text-center" nowrap>Cut Qty</th>
                                   <th class="text-center" nowrap>Cut WIP</th>
                                   <th class="text-center" nowrap>CUT WIP Value</th>
                                   <th class="text-center" nowrap>Sewing</th>
                                   <th class="text-center" nowrap>Sew WIP</th>
                                   <th class="text-center" nowrap>Sew WIP Value</th>
                                   <th class="text-center" nowrap>Fininshing WIP</th>
                                   <th class="text-center" nowrap>Finishing WIP Value</th>
                                   <th class="text-center" nowrap>Packing Order Qty.</th>
                                   <th class="text-center" nowrap>Packing</th>
                                   <th class="text-center" nowrap>Open Packing Order Qty.</th>
                                   <th class="text-center" nowrap>Shipped Qty</th>
                                   <th class="text-center" nowrap>Balance Qty</th>
                                   <th class="text-center" nowrap>FG Moving Qty</th>
                                   <th class="text-center" nowrap>WIP Qty</th>
                                   <th class="text-center" nowrap>WIP Value</th>
                                   <th class="text-center" nowrap>WIP Min</th>
                              </tr>
                         </thead>
                         <tbody>
                            @foreach($Buyer_Purchase_Order_List as $row)  
                            @php
                                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                                     where  sales_order_no='".$row->tr_code."'");
                                
                                $openWorkOrderData = DB::select("SELECT ifnull(sum(size_qty_total),0) as order_qty from vendor_work_order_detail  
                                                    inner join  vendor_work_order_master on  vendor_work_order_master.vw_code=vendor_work_order_detail.vw_code
                                                    where  vendor_work_order_detail.sales_order_no='".$row->tr_code."' AND vendor_work_order_master.endflag = 1");
                                    
                                if($Status !='')
                                {
                                    $CutPanelData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from cut_panel_grn_master  
                                      where cut_panel_grn_master.sales_order_no = '".$row->tr_code."'");
                                }
                                else
                                {
                                    $CutPanelData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from cut_panel_grn_master 
                                      left join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code = cut_panel_grn_master.vpo_code
                                      inner join job_status_master on job_status_master.job_status_id=vendor_purchase_order_master.endflag
                                      where cut_panel_grn_master.sales_order_no = '".$row->tr_code."' 
                                      group by cut_panel_grn_master.cpg_code,cut_panel_grn_master.sales_order_no");
                                }
                                if(count($CutPanelData) > 0)
                                {
                                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                                }
                                else
                                {
                                        $cutPanelIssueQty = 0;
                                }
                                
                                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                                     where  sales_order_no='".$row->tr_code."'");
                                
                                if(count($StichingData) > 0)
                                {
                                        $stichingQty = $StichingData[0]->stiching_qty;
                                }
                                else
                                {
                                        $stichingQty = 0;
                                }
                                
                                
                               $PackingData = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2  WHERE packing_inhouse_size_detail2.sales_order_no = '".$row->tr_code."'");
      
                               if(count($StichingData) > 0)
                               {
                                     $packingQty = $PackingData[0]->size_qty;
                               }
                               else
                               {
                                     $packingQty = 0;
                               }
                               
                               $openPackingOrderData = DB::select("SELECT ifnull(sum(size_qty_total),0) as order_qty from packing_inhouse_detail  
                                                    inner join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_detail.vpo_code
                                                    where  packing_inhouse_detail.sales_order_no='".$row->tr_code."' AND vendor_purchase_order_master.endflag = 1");
                                                    
                               $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                                 
                               $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                               // DB::enableQueryLog();
                               //$fgData = DB::select("SELECT order_rate from temp_fg_stock_report_data where sales_order_no='".$row->tr_code."'");
                               //DB::enableQueryLog();
                               $fgData = DB::select("SELECT sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate FROM buyer_purchse_order_master    
                                          left join sales_order_costing_master On sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                                          where buyer_purchse_order_master.tr_code='".$row->tr_code."' group by buyer_purchse_order_master.tr_code");
                                //dd(DB::getQueryLog());
                                $stockData = isset($fgData[0]->total_cost_value) ? $fgData[0]->total_cost_value : 0;
                                if($stockData == 0)
                                {
                                    $fob_rate = isset($fgData[0]->order_rate) ? $fgData[0]->order_rate : 0;
                                }
                                else
                                {
                                    $fob_rate = $fgData[0]->total_cost_value;
                                }
                              //dd(DB::getQueryLog()); 
                              
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
                              
                              $packingOrderQty = DB::select("SELECT ifnull(sum(final_bom_qty),0) as pack_order_qty from vendor_purchase_order_master  
                                 where vendor_purchase_order_master.sales_order_no='".$row->tr_code."' AND process_id=3");
                              
                              if(count($packingOrderQty) > 0)
                              {
                                $pack_order_qty = money_format("%!.0n",$packingOrderQty[0]->pack_order_qty);
                              }
                              else
                              {
                                $pack_order_qty = 0;
                              }
                                 
                            @endphp
                            <tr>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{ $order_type  }}</td>
                               <td nowrap>{{ $row->job_status_name  }}</td>
                               <td class="text-center" nowrap> {{ date('Y-m-d', strtotime($row->tr_date)) }}</td>
                               <td class="text-center" nowrap>{{ date('Y-m-d', strtotime($row->plan_cut_date))  }}</td>
                               <td class="text-center" nowrap>{{ date('Y-m-d', strtotime($row->shipment_date))  }}</td>
                               <td nowrap>-</td>
                               <td nowrap> {{ $row->Ac_name  }} </td>
                               <td class="text-center" nowrap> {{ $row->po_code  }} </td>
                               <td class="text-center" nowrap> {{ $row->brand_name  }} </td>
                               <td class="text-center" nowrap>{{ $row->mainstyle_name  }}</td>
                               <td nowrap> {{ $row->style_no  }}</td>
                               <td nowrap class="text-right">{{ number_format($row->sam)  }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($row->order_rate) )}}</td>
                               <td class="text-right" nowrap>{{ number_format((isset($fob_rate) ? $fob_rate : 0),2 )}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$row->total_qty) }} </td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$VendorData[0]->work_order_qty)}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0)}}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",$cutPanelIssueQty) }}</td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",($VendorData[0]->work_order_qty - $cutPanelIssueQty)) }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($VendorData[0]->work_order_qty - $cutPanelIssueQty) * $fob_rate) }}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$stichingQty)}}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",$cutPanelIssueQty - $stichingQty) }} </td>
                               <td nowrap class="text-right">{{ money_format("%!.0n",(($cutPanelIssueQty - $stichingQty) * $fob_rate)) }}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",((isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0) -  (isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0)) - ($cutPanelIssueQty - $stichingQty) - ($VendorData[0]->work_order_qty - $cutPanelIssueQty)  )}} </td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",((isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0) -  (isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0) - ($cutPanelIssueQty - $stichingQty) - ($VendorData[0]->work_order_qty - $cutPanelIssueQty) * $fob_rate))}}</td>
                               <td nowrap class="text-right">{{$pack_order_qty}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",$packingQty)}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0)}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",$Ship)}}</td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($row->total_qty -  $Ship)) }} </td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",($packingQty -  $Ship)) }} </td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",((isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0) -  (isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0))) }} </td>
                               <td class="text-right" nowrap>{{ money_format("%!.0n",(((isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0) -  (isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0)) * $fob_rate)) }} </td>
                               <td>{{ money_format("%!.0n",(((isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty : 0) -  (isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty : 0))) * $row->sam) }}</td>
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
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated WIP Report - 1</p>
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

        XLSX.writeFile(file, 'WIP Report - 1.' + type);
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
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            result.shift();
            $('table').append('<tr><td colspan="13" class="text-right"><strong>Total : </strong></td></tr>');
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