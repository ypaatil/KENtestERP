   <head>
      @php setlocale(LC_MONETARY, 'en_IN'); @endphp
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
 <!-- Container -->
      <div class="container-fluid invoice-container">
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
         
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
               <!-- Passenger Details -->
               <div class="">
                   <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="text-align:center;">
                        <tr>
                           <th rowspan="2">Date</th>
                           <th rowspan="2">Opening Stock</th>
                           <th rowspan="2">Opening Value</th>
                           <th rowspan="2">Inward</th> 
                           <th rowspan="2">Inward Value</th> 
                           <th rowspan="2">Outward</th>
                           <th rowspan="2">Outward Value</th>
                           <th rowspan="2">Closing Stock</th>
                           <th rowspan="2">Closing Value</th>
                        </tr> 
                     </thead>
                     <tbody>
                        @php
                        
                        $totalInward=0; 
                        $totalOutward=0; 
                        $totalInwardValue=0;
                        $totalOutwardValue=0;
                        $openingStock1=0; 
                        $openingValue1=0;
                        $no=0; 
                        $cntr = 0;
                        
                        foreach ($period as $dt) 
                        {
                           
                            $openWorkOrderData1 = DB::table('vendor_work_order_detail')
                                ->select(
                                    DB::raw('IFNULL(SUM(size_qty_total), 0) as order_qty'),
                                    DB::raw('SUM(size_qty_total * (fabric_value + sewing_trims_value + packing_trims_value)) as inward_value')
                                )
                                ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=', 'vendor_work_order_detail.vw_code')
                                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_detail.sales_order_no')
                                ->join('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                                ->where('buyer_purchse_order_master.job_status_id', '=', 1)
                                ->where('vendor_work_order_detail.vw_date', $dt)
                                ->where('buyer_purchse_order_master.og_id', '!=', 4)
                                ->get();


                                                       
                            $openPackingOrderData = DB::table('packing_inhouse_detail')
                                ->select(
                                    DB::raw('IFNULL(SUM(size_qty_total), 0) as order_qty'),
                                    DB::raw('SUM(size_qty_total * (fabric_value + sewing_trims_value + packing_trims_value)) as outward_value')
                                )
                                ->join('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_inhouse_detail.vpo_code')
                                ->join('packing_inhouse_master', 'packing_inhouse_master.pki_code', '=', 'packing_inhouse_detail.pki_code')
                                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'packing_inhouse_detail.sales_order_no')
                                ->join('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                                ->whereIn('buyer_purchse_order_master.job_status_id',[1])
                                ->where('packing_inhouse_detail.pki_date', $dt)
                                ->where('buyer_purchse_order_master.og_id', '!=', 4)
                                ->where('packing_inhouse_master.packing_type_id', '=', 4)
                                ->get();
                                 
                         
                         
                         $openingData1 = DB::select("SELECT 
                                    b.tr_code,
                                    IFNULL(sc.fabric_value, 0) AS fabric_value,
                                    IFNULL(sc.sewing_trims_value, 0) AS sewing_trims_value,
                                    IFNULL(sc.packing_trims_value, 0) AS packing_trims_value,
                                    IFNULL(vw.work_order_qty, 0) AS work_order_qty,
                                    IFNULL(cp.total_qty, 0) AS cutPanelIssueQty,
                                    IFNULL(st.stiching_qty, 0) AS stichingQty,
                                    IFNULL(pk.total_qty, 0) AS pack_order_qty
                                FROM 
                                    buyer_purchse_order_master AS b
                                LEFT JOIN 
                                    sales_order_costing_master AS sc ON b.tr_code = sc.sales_order_no
                                LEFT JOIN 
                                    (SELECT sales_order_no, SUM(size_qty_total) AS work_order_qty 
                                    FROM vendor_work_order_detail 
                                    WHERE vw_date < '".$dt."' GROUP BY sales_order_no) AS vw ON b.tr_code = vw.sales_order_no
                                LEFT JOIN 
                                    (SELECT sales_order_no, SUM(size_qty) AS total_qty 
                                    FROM cut_panel_grn_size_detail2 
                                    WHERE cpg_date < '".$dt."' GROUP BY sales_order_no) AS cp ON b.tr_code = cp.sales_order_no
                                LEFT JOIN 
                                    (SELECT sales_order_no, SUM(total_qty) AS stiching_qty 
                                    FROM stitching_inhouse_master 
                                    WHERE sti_date < '".$dt."' GROUP BY sales_order_no) AS st ON b.tr_code = st.sales_order_no
                                LEFT JOIN 
                                    (SELECT sales_order_no, SUM(total_qty) AS total_qty 
                                    FROM packing_inhouse_master 
                                    WHERE pki_date < '".$dt."' AND packing_type_id = 4 GROUP BY sales_order_no) AS pk ON b.tr_code = pk.sales_order_no
                                WHERE 
                                    (b.order_received_date < '".$dt."' AND b.job_status_id = 1 AND b.og_id != 4)
                                    OR (b.order_close_date = '".$dt."' AND b.og_id != 4 AND b.order_type != 2 AND b.delflag = 0)
                            ");
                                                
                            $opening_value = 0;
                            $openingStock = 0;
                            
                            foreach($openingData1 as $row) {
                                $sewing = $row->cutPanelIssueQty - $row->stichingQty;
                                $openingStock += (($row->work_order_qty - $row->cutPanelIssueQty) + $sewing + ($row->stichingQty - $row->pack_order_qty));
                                $opening_value += (($row->fabric_value + $row->sewing_trims_value + $row->packing_trims_value) * (($row->work_order_qty - $row->cutPanelIssueQty) + $sewing + ($row->stichingQty - $row->pack_order_qty)));
                            }

                            
                            $opening = isset($openWorkOrderData[0]->order_qty) ? $openWorkOrderData[0]->order_qty: 0;
                            $inward = isset($openWorkOrderData1[0]->order_qty) ? $openWorkOrderData1[0]->order_qty: 0;
                            $inward_value = isset($openWorkOrderData1[0]->inward_value) ? $openWorkOrderData1[0]->inward_value: 0;
                            $outward = isset($openPackingOrderData[0]->order_qty) ? $openPackingOrderData[0]->order_qty: 0;
                            $outward_value = isset($openPackingOrderData[0]->outward_value) ? $openPackingOrderData[0]->outward_value: 0;
                            
                            if($cntr == 0)
                            {
                                $openingStock = $openingStock;
                                $openingValue = $opening_value;
                            }
                            else
                            {
                                $openingStock = $openingStock1;
                                $openingValue = $openingValue1;
                            }
                             
                            @endphp
                            <tr>
                               <td style="text-align:center;">{{ date('d-m-Y',strtotime($dt)) }}  </td>
                               <td style="text-align:right;">{{ money_format('%!i',round($openingStock,2)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!i',round($openingValue,2)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!i',round($inward,2)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!i',round($inward_value,2)) }}</td>  
                               <td style="text-align:right;">{{ money_format('%!i',round($outward,2)) }}</td> 
                               <td style="text-align:right;">{{ money_format('%!i',round($outward_value,2)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!i',round(($openingStock + $inward - $outward),2)) }}</td>
                               <td style="text-align:right;">{{ money_format('%!i',round(($openingValue + $inward_value - $outward_value),2)) }}</td>
                            </tr>
                            @php
                            
                                $openingStock1 = $openingStock + $inward - $outward;
                                $openingValue1 = $openingValue + $inward_value - $outward_value;
                              
                                $totalInward=$totalInward + $inward;
                                $totalInwardValue=$totalInwardValue + $inward_value;
                                $totalOutward=$totalOutward + $outward;
                                $totalOutwardValue=$totalOutwardValue + $outward_value;
                                $no=$no+1;    
                                $cntr++;
                        }
                        @endphp
                     <tfoot>
                        <tr>
                           <td></td>
                           <td></td>
                           <td style="font-weight:bold;text-align:right;"><b>Total : </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> {{ money_format('%!i',round($totalInward,2)) }}  </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> {{ money_format('%!i',round($totalInwardValue,2)) }}  </b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round($totalOutward,2))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round($totalOutwardValue,2))}}</td>
                           <td></td>
                           <td></td>
                        </tr>
                        <tr>
                           <td></td>
                           <td></td>
                           <td style="font-weight:bold;text-align:right;"><b>Average :</b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> {{ money_format('%!i',round(($totalInward/$no),2)) }}  </b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round(($totalInwardValue/$no),2))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round(($totalOutward/$no),2))}}</td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round(($totalOutwardValue/$no),2))}}</td>
                           <td></td>
                           <td></td>
                        </tr>
                     </tfoot>
                     </tbody>
                     </tbody>
                  </table>
                 </div>
            </main>
         </div>
      </div>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
        
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'FG Inward and Outward Stock Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
      $('#printInvoice').click(function()
      {
          Popup($('.invoice')[0].outerHTML);
          function Popup(data) 
          {
              window.print();
              return true;
          }
      });
      
      
   </script>