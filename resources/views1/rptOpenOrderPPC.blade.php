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
                  <h4 class="mb-0">Open Order PPC</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class=""></div>
               <!-- Passenger Details -->
               <div class="">
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                     <thead>
                        <tr style="background-color:#eee;">
                           <th nowrap>Sr.No.</th>
                           <th nowrap>Sales Order No.</th>
                           <th nowrap>PO status</th>
                           <th nowrap>Received Date</th>
                           <th nowrap>Plan Cut Date</th>
                           <th nowrap>Shipment Date</th>
                           <th nowrap>Buyer Name</th>
                           <th nowrap>PO No</th>
                           <th nowrap>Buyer Brand</th>
                           <th nowrap>Main Style Category</th>
                           <th nowrap>Style Name</th>
                           <th nowrap>SAM</th>
                           <th nowrap>Order Rate</th>
                           <th nowrap>Order Qty</th>
                           <th nowrap>Order Value</th>
                           <th nowrap>Shipped Qty</th>
                           <th nowrap>Balance Qty</th>
                           <th nowrap>Allocation Qty</th>
                           <th nowrap>Balance Qty</th>
                           @php
                            $v = 1;
                           @endphp
                           @foreach($vendorCount as $vendors)
                           <th>Vendor {{$v++}}</th>
                           <th>Qty</th>
                           @endforeach
                        </tr>
                     </thead>
                     <tbody>
                        @php
                            $nos = 1;
                            $BalanceQty2 = 0;
                        @endphp
                        @foreach($Buyer_Purchase_Order_List as $row)
                        @php
                              $ShippedQty=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
                                 inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
                                 where carton_packing_inhouse_size_detail2.sales_order_no='".$row->tr_code."'
                                 and carton_packing_inhouse_master.endflag=1 group by carton_packing_inhouse_size_detail2.sales_order_no");
                                 
                              $Ship=isset($ShippedQty[0]->carton_pack_qty) ? $ShippedQty[0]->carton_pack_qty : 0;
                              
                               $vendorName = "-";
                               $vendorQty = 0;
                               
                               $vendorsList=DB::select("SELECT *,ledger_master.ac_name FROM open_order_ppc_details 
                                            INNER JOIN ledger_master ON ledger_master.ac_code = open_order_ppc_details.vendorId 
                                            WHERE sales_order_no='".$row->tr_code."'");
                               $dVendorCount1 = count($vendorsList);
                               $dVendorCount2 = count($vendorCount);
                               
                               foreach($vendorsList as $vendors)
                               {
                                    $BalanceQty2  = $BalanceQty2 + $vendors->vendorQty;
                               }
                        @endphp
                        <tr>
                           <td nowrap>{{$nos++}}</td>
                           <td nowrap>{{ $row->tr_code  }}</td>
                           <td nowrap>{{ $row->job_status_name  }}</td>
                           <td nowrap>{{ $row->order_received_date}}</td>
                           <td nowrap>{{ $row->plan_cut_date}}</td>
                           <td nowrap>{{ $row->shipment_date}}</td>
                           <td nowrap>{{ $row->Ac_name  }}</td>
                           <td nowrap>{{ $row->po_code  }}</td>
                           <td nowrap>{{ $row->brand_name  }}</td>
                           <td nowrap>{{ $row->mainstyle_name  }}</td>
                           <td nowrap>{{ $row->style_no  }}</td>
                           <td nowrap class="text-right">{{ number_format($row->sam,2)  }}</td>
                           <td nowrap class="text-right">{{ number_format($row->order_rate,2 ) }}</td>
                           <td nowrap class="text-right">{{ number_format($row->total_qty,2) }}</td>
                           <td nowrap class="text-right">{{ number_format($row->order_value,2)  }}</td>
                           <td nowrap class="text-right">{{number_format($Ship,2)}}</td>
                           <td nowrap class="text-right">{{number_format(($row->total_qty - $Ship),2)}}</td>
                           <td nowrap class="text-right">{{number_format((($row->total_qty - $Ship) - $BalanceQty2),2)}}</td>
                           <td nowrap class="text-right">{{number_format($BalanceQty2,2)}}</td>
                           @foreach($vendorsList as $vendors)
                               <td nowrap>{{$vendors->ac_name}}</td>
                               <td nowrap>{{number_format($vendors->vendorQty,2)}}</td>
                           @endforeach
                           @php
                                for($i=0; $i<$dVendorCount2-$dVendorCount1; $i++)
                                {
                           @endphp
                              <td>-</td>
                              <td>0</td>
                           @php
                               }
                               $BalanceQty2 = 0;
                           @endphp
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated Open Order PPC</p>
      <p class="text-center d-print-none"><a href="{{Route('OpenOrderPPC.index')}}">&laquo; Back to List</a></p>
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

        XLSX.writeFile(file, 'Open Order PPC.' + type);
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
                  result[index] += parseFloat($(val).text() ? $(val).text() : 0);
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
            $('table').append('<tr><td colspan="11" class="text-right"><strong>Total : </strong></td></tr>');
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
              if(res == 'NaN')
              {
                  res = "-";
              }
              else
              {
                  res1 = res.split('.');
                  if(res1.length > 1)
                  {
                     res2 = "."+res1[1].substr(0, 2);
                  }
                  else
                  {
                      res2  = "";
                  }
                  res = res1[0]+""+res2;
              }
              $('table tr').last().append('<td class="text-right"><strong>'+res+'</strong></td>')
            });
      });
   </script>
</html>