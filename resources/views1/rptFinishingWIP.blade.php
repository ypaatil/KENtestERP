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
                  <h4 class="mb-0">Finishing WIP Report</h4>
               </center>
                 <div class="row mt-3">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Order No</th>
                                   <th nowrap>Vendor Name</th>
                                   <th class="text-center" nowrap>Color</th>
                                   <th class="text-center" nowrap>Style</th>
                                   <th class="text-center" nowrap>Work Order Qty</th>
                                   <th class="text-center" nowrap>FOB Rate</th>
                                   <th class="text-center" nowrap>Cut Qty</th>
                                   <th class="text-center" nowrap>Sew Qty</th>
                                   <th class="text-center" nowrap>Packing Qty</th>
                                   <th class="text-center" nowrap>Shipped Qty</th>
                                   <th class="text-center" nowrap>Finishing WIP</th>
                                   <th class="text-center" nowrap>Finishing WIP Value</th>
                                   <th class="text-center" nowrap>Cut To Pack</th>
                                   <th class="text-center" nowrap>Cut to Ship</th>
                              </tr>
                         </thead>
                         <tbody> 
                            @foreach($ProductionOrderDetailList as $row) 
                            @php
                             $CutGrnList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from cut_panel_grn_size_detail2 
                                                where sales_order_no='".$row->tr_code."' 
                                                AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                                AND vendorId ='".$vendorId."'");
                             
                             $Cut_Qty = isset($CutGrnList[0]->size_qty) ? $CutGrnList[0]->size_qty : 0;
                             
                             $sewingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from stitching_inhouse_size_detail2 
                                                where sales_order_no='".$row->tr_code."' 
                                                AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                                AND vendorId ='".$vendorId."'");
                             
                             $sew_Qty = isset($sewingList[0]->size_qty) ? $sewingList[0]->size_qty : 0;
                             
                             $packingList = DB::select("SELECT ifnull(sum(size_qty),0) as size_qty  from packing_inhouse_size_detail2 
                                                where sales_order_no='".$row->tr_code."' 
                                                AND color_id ='".$row->color_id."' AND mainstyle_id='".$row->mainstyle_id."'
                                                AND vendorId ='".$vendorId."'");
                             
                             $pack_Qty = isset($packingList[0]->size_qty) ? $packingList[0]->size_qty : 0;
                             
                                                   
                             $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."' 
                                 AND carton_packing_inhouse_size_detail2.mainstyle_id='".$row->mainstyle_id."' 
                                 AND carton_packing_inhouse_size_detail2.color_id ='".$row->color_id."' 
                                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                                 
                             $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                             if($pack_Qty > 0 && $Cut_Qty > 0)
                             {
                                $Cut_To_Pack = ($pack_Qty/$Cut_Qty) * 100;
                             }
                             else
                             {
                                $Cut_To_Pack = 0;
                             }
                             
                             if($Ship > 0 && $Cut_Qty > 0)
                             {
                                $Cut_to_Ship = ($Ship/$Cut_Qty) * 100;
                             }
                             else
                             {
                                $Cut_to_Ship = 0;
                             }
                            @endphp
                            <tr>
                               <td nowrap>{{ $row->tr_code  }}</td>
                               <td nowrap>{{ $vendorName  }}</td>
                               <td nowrap>{{ $row->color_name  }}</td>
                               <td nowrap>{{ $row->mainstyle_name  }}</td>
                               <td nowrap>{{ $row->order_qty }}</td>
                               <td nowrap>{{ number_format($row->order_rate,2) }}</td>
                               <td nowrap>{{ $Cut_Qty }}</td> 
                               <td nowrap>{{ $sew_Qty }}</td>
                               <td nowrap>{{ $pack_Qty }}</td>
                               <td nowrap>{{ $Ship }}</td>
                               <td nowrap>{{ $sew_Qty - $pack_Qty }}</td>
                               <td nowrap>{{ ($sew_Qty - $pack_Qty) * $row->order_rate }}</td>
                               <td nowrap>{{round($Cut_To_Pack,2)}}</td>
                               <td nowrap>{{round($Cut_to_Ship,2)}}</td>
                             </tr> 
                             @php
                                 $Ship = 0;
                             @endphp
                             @endforeach
                         </tbody>
                      </table>
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Finishing WIP Report</p>
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

        XLSX.writeFile(file, 'Total WIP Report.' + type);
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