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
      <button class="button_niks btn  btn-info btn-rounded print" id="doPrint">Print</button>
      <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">Monthly Shipment Target Plan</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th>Sr.No.</th>
                           <th>Sales Order No.</th>
                           <th>Buyer</th>
                           <th>Main Style Category</th>
                           <th style="width:164px; background: #aecfeb;">Week 1</th>
                           <th style="width:164px; background: #aecfeb;">Week 2</th>
                           <th style="width:164px; background: #aecfeb;">Week 3</th>
                           <th style="width:164px; background: #aecfeb;">Week 4</th>
                           <th style="width:164px; background: #aecfeb;">Target Qty</th>
                           <th style="width:164px; background: #aecfeb;">Order Rate</th>
                           <th style="width:164px; background: #aecfeb;">Value</th>
                           <th style="background: #e7c290;">Week 1</th>
                           <th style="background: #e7c290;">Week 2</th>
                           <th style="background: #e7c290;">Week 3</th>
                           <th style="background: #e7c290;">Week 4</th>
                           <th style="background: #e7c290;">Actual shipped</th>
                           <th style="background: #e7c290;">Order Rate</th>
                           <th style="background: #e7c290;">Value</th>
                           <th>Difference in Qty</th>
                           <th>Difference in Value</th>
                           <th>%</th>
                        </tr>
                     </thead>
                     <tbody>
                       @php
                            $srno = 1;
                            $totalOrderQty1 = 0;
                            $totalOrderQty2 = 0;
                            $totalOrderQty3 = 0;
                            $totalOrderQty4 = 0;
                            $totalOrderQty5 = 0;
                            
                            function weekOfMonth($date) 
                            {
                                $firstOfMonth = strtotime(date("Y-m-01", $date));
                                return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
                            }
                            
                            function weekOfYear($date) 
                            {
                                $weekOfYear = intval(date("W", $date));
                                if (date('n', $date) == "1" && $weekOfYear > 51) 
                                {
                                    return 0;
                                }
                                else if (date('n', $date) == "12" && $weekOfYear == 1) 
                                {
                                    return 53;
                                }
                                else 
                                {
                                    return $weekOfYear;
                                }
                            }
                        @endphp
                        @foreach($planData as $row)
                        @php 
                               
                                $SaleTransactionDetails = App\Models\SaleTransactionDetailModel::select( 'sale_transaction_detail.sale_date',DB::raw('sum(order_qty) as order_qty'))
                                     ->leftJoin('sale_transaction_master','sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
                                     ->where('sales_order_no','=', $row->sales_order_no)
                                     ->groupBy('sale_transaction_master.sale_code')
                                     ->get();
                               
                                foreach($SaleTransactionDetails as $details)
                                {
                                    if(weekOfMonth(strtotime($details->sale_date)) == 1)
                                    {
                                        $totalOrderQty1 = $totalOrderQty1 + $details->order_qty;
                                    }
                                    
                                    if(weekOfMonth(strtotime($details->sale_date)) == 2)
                                    {
                                        $totalOrderQty2 = $totalOrderQty2 + $details->order_qty;
                                    }
                                    
                                    if(weekOfMonth(strtotime($details->sale_date)) == 3)
                                    {
                                        $totalOrderQty3 = $totalOrderQty3 + $details->order_qty;
                                    }
                                    
                                    if(weekOfMonth(strtotime($details->sale_date)) == 4)
                                    {
                                        $totalOrderQty4 = $totalOrderQty4 + $details->order_qty;
                                    }
                                }
                                
                                $actualShipped = $totalOrderQty1 + $totalOrderQty2 + $totalOrderQty3 + $totalOrderQty4;
                                if($row->targetQty > 0)
                                {
                                    $percentage  = (($actualShipped - $row->targetQty) * 1)/$row->targetQty;
                                }
                                else
                                {
                                    $percentage  = 0;
                                }
                                

                        @endphp
                        <tr>
                           <td>{{$srno++}}</td>
                           <td>{{$row->sales_order_no}}</td>
                           <td>{{$row->ac_name}}</td>
                           <td>{{$row->mainstyle_name}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->week1,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->week2,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->week3,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->week4,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->targetQty,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->orderRate,2)}}</td>
                           <td style="width:164px; background: #aecfeb;text-align: end;">{{number_format($row->value,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($totalOrderQty1,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($totalOrderQty2,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($totalOrderQty3,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($totalOrderQty4,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($actualShipped,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{number_format($row->orderRate,2)}}</td>
                           <td style="background: #e7c290;text-align: end;">{{ number_format($actualShipped * $row->orderRate,2)}}</td>
                           <td style="text-align: end;">{{number_format($actualShipped - $row->targetQty,2)}}</td>
                           <td style="text-align: end;">{{number_format(($actualShipped * $row->orderRate) - $row->value,2)}}</td>
                           <td style="text-align: end;">{{number_format($percentage,2)}}</td>
                        </tr>
                        @php
                            $totalOrderQty1 = 0;
                            $totalOrderQty2 = 0;
                            $totalOrderQty3 = 0;
                            $totalOrderQty4 = 0;
                        @endphp
                        @endforeach
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Monthly Shipment Target Plan</p>
      <p class="text-center d-print-none"><a href="/SaleTransaction">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  $('#printInvoice').click(function(){
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

        XLSX.writeFile(file, 'Monthly Shipment Target Plan.' + type);
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
                  result[index] += parseFloat($(val).text().replace(/,/g , '') ? $(val).text().replace(/,/g , '') : 0);
               });
            });
             result.shift();
             result.shift();
             result.shift();
             result.shift();
            $('table').append('<tr><td colspan="4" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
                var x=this;
                x=x.toString();
                var afterPoint = '';
                if(x.indexOf('.') > 0)
                   afterPoint = x.substring(x.indexOf('.'),x.length);
                x = Math.floor(x);
                x=x.toString();
                var lastThree = x.substring(x.length-3);
                var otherNumbers = x.substring(0,x.length-3);
                if(otherNumbers != '')
                    lastThree = ',' + lastThree;
                var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
                
               $('table tr').last().append('<td class="text-center"><strong>'+res+'</strong></td>')
               
              
            });
            var order_rate = $('table tr:last').find('td:nth-child(7) strong').html().replace(/,/g , '');
            var order_rate1 = $('table tr:last').find('td:nth-child(14) strong').html().replace(/,/g , '');
            var percentage = $('table tr:last').find('td:nth-child(18) strong').html();
            
            var noOfRecords = {{count($planData)}}
            
            var avg = (parseFloat(order_rate)/parseFloat(noOfRecords))
            var avg1 = (parseFloat(order_rate1)/parseFloat(noOfRecords))
            var avgPercent = (parseFloat(percentage)/parseFloat(noOfRecords))
            
            $('table tr:last').find('td:nth-child(7) strong').html(avg.toFixed(2));
            $('table tr:last').find('td:nth-child(14) strong').html(avg1.toFixed(2));
            $('table tr:last').find('td:nth-child(18) strong').html(avgPercent.toFixed(2));
      });
   </script>
</html>