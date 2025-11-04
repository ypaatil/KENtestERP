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
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Cutting OCR Report</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class="row">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
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
                                <tr style="background-color:#eee;">
                                   <th nowrap>DATE</th>
                                   <th nowrap>KDPL</th>
                                   <th nowrap>COLOUR/CODE</th>
                                   <th nowrap>ORDER QTY</th>
                                   <th nowrap>PPP NO.</th>
                                   <th nowrap>FABRIC ISSUED TO CUTTING(MTR)</th>
                                   <th nowrap>CONSUMPTION</th>
                                   @foreach ($SizeDetailList as $sz) 
                                        <th><b>{{$sz->size_name}}</b></th>
                                   @endforeach
                                   <th nowrap>TOTAL</th>
                                   <th nowrap>G.TOTAL</th>
                                   <th nowrap>EXPECTED GARMENT</th>
                                   <th nowrap>BALANCE TO CUT</th>
                                   <th nowrap>FABRIC REQ.</th>
                                   <th nowrap>FABRIC USED</th>
                                   <th nowrap>G.TOTAL FABRIC USED</th>
                                   <th nowrap>BALANCE FABRIC</th>
                                </tr>
                         </thead>
                         <tbody>
                            @php
                            
                            $totalCuttingQty=0;
                            $totalUsedMeter=0;
                            $gTotal = 0;
                            $gTotalFabricUsed = 0;
                            //DB::enableQueryLog();
                            if($vpo_code != '')
                            {
                                $CutPlanList=DB::select("select bundle_barcode_serial_details.task_id,bb_date,bundle_barcode_serial_details.item_code, item_master.item_name,sizes_id,vendor_purchase_order_detail.sales_order_no,
                                bundle_barcode_serial_details.vpo_code,(select sum(meter) from fabric_outward_details where fabric_outward_details.vpo_code='".$vpo_code."' 
                                and fabric_outward_details.item_code=bundle_barcode_serial_details.item_code)   as TotalMeter,
                                (select sum(size_qty_total) from vendor_purchase_order_detail where vendor_purchase_order_detail.vpo_code='".$vpo_code."' 
                                and vendor_purchase_order_detail.item_code=bundle_barcode_serial_details.item_code)   as CutPoQty
                                from 
                                bundle_barcode_serial_details
                                inner join item_master on item_master.item_code=bundle_barcode_serial_details.item_code
                                inner join size_detail on size_detail.size_id=bundle_barcode_serial_details.sizes_id
                                inner join vendor_purchase_order_detail on vendor_purchase_order_detail.item_code=bundle_barcode_serial_details.item_code
                                where bundle_barcode_serial_details.vpo_code='".$vpo_code."'
                                group by bundle_barcode_serial_details.bb_date,bundle_barcode_serial_details.item_code order by item_master.item_name");
                            }
                            else
                            {
                             
                             
                                $CutPlanList=DB::select("select bundle_barcode_serial_details.task_id,bb_date,bundle_barcode_serial_details.item_code, item_master.item_name,sizes_id,vendor_purchase_order_detail.sales_order_no,
                                    
                                bundle_barcode_serial_details.vpo_code,(select sum(meter) from fabric_outward_details where fabric_outward_details.item_code=bundle_barcode_serial_details.item_code)   as TotalMeter,
                                    (select sum(size_qty_total) from vendor_purchase_order_detail where  vendor_purchase_order_detail.item_code=bundle_barcode_serial_details.item_code)   as CutPoQty
                                    from 
                                    bundle_barcode_serial_details
                                    inner join item_master on item_master.item_code=bundle_barcode_serial_details.item_code
                                    inner join size_detail on size_detail.size_id=bundle_barcode_serial_details.sizes_id
                                    inner join vendor_purchase_order_detail on vendor_purchase_order_detail.item_code=bundle_barcode_serial_details.item_code
                                    where 1
                                    group by bundle_barcode_serial_details.bb_date,bundle_barcode_serial_details.item_code order by item_master.item_name");
                            }
                                
                            
                            //dd(DB::getQueryLog());
                            foreach ($CutPlanList as $row) 
                            {
                                $taskAvg = DB::select("select table_avg from task_master where vpo_code='".$row->vpo_code."'");
                                  
                                $FabricOutwardDetails=DB::select("select ifnull(sum(meter),0) as fabric_issue_cutting_mtr from 
                                    fabric_outward_details WHERE fabric_outward_details.vpo_code='".$row->vpo_code."' AND fabric_outward_details.item_code='".$row->item_code."'");
                            
                               
                                if(count($taskAvg) > 0)
                                {
                                    $taskAvg = $taskAvg[0]->table_avg;
                                }
                                else
                                {
                                    $taskAvg = 0;
                                } 
                            @endphp
                            <tr>
                               <td nowrap>{{ date('d-m-Y',strtotime($row->bb_date))  }}</td>
                               <td nowrap>{{ $row->sales_order_no }}</td>
                               <td>{{ $row->item_name }}</td>
                               <td>{{ $row->CutPoQty }}</td>
                               <td>{{ $row->vpo_code }}</td>
                               <td>{{$FabricOutwardDetails[0]->fabric_issue_cutting_mtr}}</td>
                               <td>{{$taskAvg}}</td>
                               @php  
                                   $totalCut = 0; 
                                   $meter=0;
                                   
                                   foreach ($SizeDetailList as $sz) 
                                   {
                                       //DB::enableQueryLog();  
                                       $CuttingQty=DB::select("select ifnull(sum(layers),0) as qty, (select ifnull(sum(used_meter),0) from cutting_balance_details
                                       inner join cutting_master on cutting_master.cu_code=cutting_balance_details.cu_code
                                       where cutting_master.table_task_code=bundle_barcode_serial_details.task_id and
                                       bundle_barcode_serial_details.vpo_code='".$row->vpo_code."' and cutting_balance_details.item_code='".$row->item_code."'  ) as meter
                                       from 
                                       bundle_barcode_serial_details
                                       inner join item_master on item_master.item_code=bundle_barcode_serial_details.item_code
                                       inner join size_detail on size_detail.size_id=bundle_barcode_serial_details.sizes_id
                                       where vpo_code='".$row->vpo_code."' and sizes_id='".$sz->size_id."' and bundle_barcode_serial_details.item_code='".$row->item_code."' and
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
                                           echo '<td style="text-align:right;">0</td>';
                                           $meter=0;
                                       }
                                     
                                    
                                   }
                                   $gTotal = $gTotal + $totalCut;
                                   
                              @endphp
                               <td>{{$totalCut}}</td>
                               <td>{{$gTotal}}</td>
                               @php 
                                    if($taskAvg > 0)
                                    {
                                        $expected_garment = ($FabricOutwardDetails[0]->fabric_issue_cutting_mtr/$taskAvg);
                                    }
                                    else
                                    {
                                        $expected_garment = 0;
                                    }
                                    
                                    $gTotalFabricUsed = $gTotalFabricUsed + $meter;
                               @endphp
                               <td>{{round($expected_garment,2)}}</td>
                               <td>{{$row->CutPoQty - $gTotal}}</td>
                               <td>{{$totalCut * $taskAvg}}</td>
                               <td>{{round($meter,2)}}</td>
                               <td>{{$gTotalFabricUsed}}</td>
                               <td>{{$FabricOutwardDetails[0]->fabric_issue_cutting_mtr }}</td>
                            </tr>
                            @php
                                $no=$no+1;   
                                $gTotal = 0;
                            }
                            @endphp
                         </tbody>
                      </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Cutting OCR Report</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
   
//   $('#invoice').click(function(){
//       Popup($('.invoice')[0].outerHTML);
//       function Popup(data) 
//       {
//           window.print();
//           return true;
//       }
//       });
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Cutting OCR Report.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     
   </script>
</html>