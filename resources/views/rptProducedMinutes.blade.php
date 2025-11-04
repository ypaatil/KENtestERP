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
          body {
                line-height: 8px;
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
          font-size:16px !important;
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
          
          .table thead th {
                vertical-align: middle;
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
                  <h4 class="mb-0">Produced Minutes Report</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>  
                            <tr style="background-color:#eee;">
                                   <th nowrap class="text-center">DATE</th>
                                   <th nowrap class="text-center">KEN-1</th>
                                   <th class="text-center" nowrap>KEN-2</th> 
                                   <th class="text-center" nowrap>KEN Shirala</th>
                                   <th class="text-center" nowrap>Padmavati</th>
                                   <th class="text-center" nowrap>Outsource</th>
                                   <th class="text-center" nowrap>Total</th> 
                                   <th class="text-center" nowrap>Cumulative</th>
                            </tr>
                         </thead>
                         <tbody>   
                            @php
                                $cumulative = 0;
                                $totalken1 = 0;
                                $totalken2 = 0;
                                $totalken3 = 0;
                                $totalken4 = 0;
                                $totalken5 = 0;
                                $overall = 0; 
                            @endphp
                            @foreach($Stitching as $row)
                            @php
                               $ken1 = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.vendorId='56' and 
                                    stitching_inhouse_size_detail2.sti_date = '".$row->sti_date."'");
                               
                                if(count($ken1) > 0)
                                {
                                    $ken1Min = round($ken1[0]->total_min);
                                    
                                }
                                else
                                {
                                    $ken1Min = 0;
                                }
                                
                               $ken2 = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.vendorId='115' and 
                                    stitching_inhouse_size_detail2.sti_date = '".$row->sti_date."'");
                               
                                if(count($ken2) > 0)
                                {
                                    $ken2Min = round($ken2[0]->total_min);
                                    
                                }
                                else
                                {
                                    $ken2Min = 0;
                                }
                           
                                $ken3 = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.vendorId='110' and 
                                    stitching_inhouse_size_detail2.sti_date = '".$row->sti_date."'");
                               
                                if(count($ken3) > 0)
                                {
                                    $ken3Min = round($ken3[0]->total_min);
                                    
                                }
                                else
                                {
                                    $ken3Min = 0;
                                }
                               
                                $ken4 = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.vendorId='241' and 
                                    stitching_inhouse_size_detail2.sti_date = '".$row->sti_date."'");
                               
                                if(count($ken4) > 0)
                                {
                                    $ken4Min = round($ken4[0]->total_min);
                                    
                                }
                                else
                                {
                                    $ken4Min = 0;
                                }
                               
                              $ken5 = DB::select("select sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as total_min 
                                    from stitching_inhouse_size_detail2
                                    INNER JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=stitching_inhouse_size_detail2.sales_order_no
                                    where stitching_inhouse_size_detail2.sti_date = '".$row->sti_date."'AND stitching_inhouse_size_detail2.vendorId NOT IN(56,241,110,115)");
                               
                                if(count($ken5) > 0)
                                {
                                    $ken5Min = round($ken5[0]->total_min);
                                    
                                }
                                else
                                {
                                    $ken5Min = 0;
                                } 
                                
                                $total = $ken1Min + $ken2Min + $ken3Min + $ken4Min + $ken5Min;
                                $overall += $total;
                                $cumulative += $total;
                            @endphp
                            <tr>
                               <td nowrap class="text-center">{{date('d-m-Y', strtotime($row->sti_date))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($ken1Min) ? $ken1Min : 0))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($ken2Min) ? $ken2Min : 0))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($ken3Min) ? $ken3Min : 0))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($ken4Min) ? $ken4Min : 0))}}</td>
                               <td nowrap class="text-right">{{money_format("%!.0n",(isset($ken5Min) ? $ken5Min : 0))}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",(isset($total) ? $total : 0))}}</td>
                               <td class="text-right" nowrap>{{money_format("%!.0n",($cumulative))}}</td>
                             </tr>
                             
                             @php
                                $totalken1 += $ken1[0]->total_min;
                                $totalken2 += $ken2[0]->total_min;
                                $totalken3 += $ken3[0]->total_min;
                                $totalken4 += $ken4[0]->total_min;
                                $totalken5 += $ken5[0]->total_min;
                             @endphp 
                             @endforeach
                             <tr>
                                 <td class="text-right"><b>Total</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($totalken1)))}}</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($totalken2)))}}</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($totalken3)))}}</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($totalken4)))}}</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($totalken5)))}}</b></td>
                                 <td class="text-right"><b>{{money_format("%!.0n",(round($overall)))}}</b></td>
                                 <td class="text-right"><b>-</b></td>
                             </tr>
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div> 
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Produced Minutes Report</p>
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

        XLSX.writeFile(file, 'Produced Minutes Report.' + type);
        location.reload();
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
 
   </script>
</html>