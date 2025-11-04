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
                     <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
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
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Trims Inward and Outward Stock Report   {{ date('d-m-Y',strtotime($fdate)) }} and {{ date('d-m-Y',strtotime($tdate)) }}				</h4>
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
                  background-image: url('http://kenerp.org/logo/ken.jpeg');background-repeat: no-repeat;background-size:cover;
                  }
               </style>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead style="text-align:center;">
                        <tr>
                           <th rowspan="2">Date</th>
                           <th rowspan="2" wrap>Opening Stock <br/>(Value In Lakh)</th>
                           <th rowspan="2" wrap>Inward <br/>(Value In Lakh)</th>
                           <th rowspan="2" wrap>Outward <br/>(Value In Lakh)</th>
                           <th rowspan="2" wrap>Closing Stock <br/>(Value In Lakh)</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php        
                                    
                        $totalInward=0; 
                        $totalOutward=0; 
                        $no=0;
                        $cntr=0;
                        $closingStock = 0;
                        $openingStock1 = 0;
                        $q_qty = 0; 
                        foreach ($period as $dt) 
                        { 
                            $TrimOpeningData =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data 
                                                INNER JOIN item_master ON item_master.item_code = dump_trim_stock_data.item_code WHERE item_master.cat_id != 4  AND trimDate < '".$dt."' GROUP BY po_no,dump_trim_stock_data.item_code");     
                            
                            $total_opening_value = 0;
                            
                            foreach($TrimOpeningData as $row)
                            {
                                $q_qty = 0;   
                                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                                
                                foreach($ind_outward1 as $indu)
                                {
                                    
                                     $ind_outward2 = (explode("=>",$indu));
                                      
                                     if($ind_outward2[0] < $dt)
                                     {
                                        $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                                        $q_qty = $q_qty + $ind_out;
                                       
                                     }
                                } 
                              
                                $stocks =  $row->gq - $q_qty; 
                                $total_opening_value += ($stocks * $row->rate);
                            }
        
                            $TrimInwardData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate)  as Inward from trimsInwardDetail INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                    where item_master.cat_id !=4 AND trimDate = '".$dt."'");
                                    
                            $TrimsOutwardData = DB::SELECT("SELECT trimsOutwardDetail.item_qty,trimsOutwardDetail.item_code,trimsOutwardDetail.po_code FROM trimsOutwardDetail 
                                    INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
                                    WHERE item_master.cat_id != 4 AND trimsOutwardDetail.tout_date = '".$dt."'");
                            
                                    
                            $outward_qty = 0;
                            foreach($TrimsOutwardData as $row)
                            {
                                $TrimsInwardData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsInwardDetail  
                                    INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
                                    WHERE item_master.cat_id != 4 AND trimsInwardDetail.item_code = '".$row->item_code."' AND po_code='".$row->po_code."'");
                                
                                $item_rate = isset($TrimsInwardData[0]->item_rate) ? $TrimsInwardData[0]->item_rate: 0;  
                                $outward_qty += ($row->item_qty * $item_rate);
                            }
                                 
                         
                            $openingQty = isset($TrimOpeningData[0]->OpeningStock) ? $TrimOpeningData[0]->OpeningStock: 0;  
                            $inwardQty = isset($TrimInwardData[0]->Inward) ? $TrimInwardData[0]->Inward: 0;   
                            $outwardQty = $outward_qty;     
                             
                            if($cntr == 0)
                            { 
                                $openingStock = $total_opening_value;
                                $opening = ($openingStock/100000);
                            }
                            else
                            {
                                $openingStock = $openingStock1;
                                $opening =  $openingStock;
                            }
                           
                            $inward =  $inwardQty/100000;
                            $outward = $outwardQty/100000;
                            $closingStock = $opening + $inward - $outward;
                            
                        @endphp
                        <tr>
                           <td style="text-align:center;" nowrap>{{ date('d-m-Y',strtotime($dt)) }}  </td>
                           <td style="text-align:right;">{{ money_format('%!i',$opening) }}</td>
                           <td style="text-align:right;">{{ money_format('%!i',$inward) }}</td>
                           <td style="text-align:right;">{{ money_format('%!i',$outward) }}</td>
                           <td style="text-align:right;">{{ money_format('%!i',$closingStock)  }}</td>
                        </tr>
                        @php
                        
                        $openingStock1 = $closingStock;
                        
                        $totalInward=$totalInward + $inward;
                        $totalOutward=$totalOutward + $outward;
                        $no=$no+1; 
                        $cntr++;
                        }
                        @endphp
                     <tfoot>
                        <tr>
                           <td></td>
                           <td style="font-weight:bold;">  <b>Total :   </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> {{ money_format('%!i',$totalInward) }}  </b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',$totalOutward)}}</td>
                           <td></td>
                        </tr>
                        <tr>
                           <td></td>
                           <td style="font-weight:bold;">  <b>Average :   </b></td>
                           <td style="font-weight:bold;text-align:right;"> <b> {{ money_format('%!i',round(($totalInward/$no)/100000,2)) }}  </b></td>
                           <td style="font-weight:bold;text-align:right;">{{money_format('%!i',round(($totalOutward/$no)/100000,2))}}</td>
                           <td></td>
                        </tr>
                     </tfoot>
                     </tbody>
                  </table>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="/GetTrimsInOutStockReportForm">&laquo; Back to List</a></p>
   </body>
   <script src="{{ URL::asset('http://kenerp.org/assets/libs/jquery/jquery.min.js')}}"></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
        
     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Trims Inward and Outward Stock Report.' + type);
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
</html>