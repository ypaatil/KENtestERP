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
         <a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>
         <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <center>
                  <h4 class="mb-0">ITEM LEDGER REPORT</h4>
               </center>
               <!-- Item Details -->
               <h4 class="text-4"></h4>
               <div class="row">
                    <div class="col-md-12">
                      <table class="table" style="height:10vh; ">
                         <thead>
                                <tr style="background-color:#eee;">
                                   <th nowrap>From Date</th>
                                   <th nowrap>To Date</th>
                                   <th nowrap>Category</th>
                                   <th nowrap>Classification</th>
                                   <th nowrap>Item Code</th>
                                   <th nowrap>Item Name</th>
                                </tr>
                         </thead>
                         <tbody>
                            <tr>
                               <td>{{$fdate}}</td>
                               <td>{{$tdate}}</td>
                               <td>{{$cat_name}}</td>
                               <td>{{$class_name}}</td>
                               <td>{{$item_code}}</td>
                               <td>{{$item_name}}</td>
                            </tr>
                         </tbody>
                      </table>
                  </div>
               </div>
               <!-- Passenger Details -->
                 <div class="row">
                    <div class="col-md-12">
                     @php 
                        $srno = 1;
                        if($cat_id == 1)
                        {
                      @endphp
                      <table class="table" style="height:10vh; " id="tbl2">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Sr.No.</th>
                                   <th nowrap>Transaction Type</th>
                                   <th nowrap>Transaction No.</th>
                                   <th nowrap>Date</th>
                                   <th nowrap>From/To</th>
                                   <th nowrap>Order Ref No</th>
                                   <th nowrap>PO No.</th>
                                   <th nowrap>Item PO No.</th>
                                   <th nowrap>Receive Qty.</th>
                                   <th nowrap>Issued Qty.</th>
                                   <th nowrap>Balance</th>
                                   <th nowrap>QC Done</th>
                                   <th nowrap>Passed</th>
                                   <th nowrap>Rejected</th>
                                   <th nowrap>Seconds</th>
                              </tr>
                         </thead>
                         <tbody>
                            @php 
                                $srno = 1;
                                if($cat_id == 1)
                                {
                            @endphp
                            @foreach($ItemLedgerData as $row)
                            @php
                                if (str_contains($row->in_code ? $row->in_code : $row->fout_code, 'GRN')) 
                                {
                                    $in = $row->meter;
                                    $out = 0 ;
                                 }
                                else
                                {   
                                    $in = 0;
                                    $out = $row->meter;
                                }
                            @endphp
                                <tr>
                                   <td nowrap>{{$srno++}}</td>
                                   <td nowrap>{{$row->inward_type ? $row->inward_type  : $row->outward_type}}</td>
                                   <td nowrap>{{$row->in_code ? $row->in_code : $row->fout_code}}</td>
                                   <td nowrap>{{$row->in_date ? $row->in_date : $row->out_date}}</td>
                                   <td nowrap>{{$row->ac_name ? $row->ac_name : $row->vendor_name}}</td>
                                   <td nowrap>{{$row->order_ref_no  ? $row->order_ref_no  : 'Combined PO'}}</td>
                                   <td nowrap>{{$row->po_no ? $row->po_no : $row->po_code}}</td>
                                   <td nowrap>{{$row->po_code}}</td>
                                   <td nowrap>{{$in}}</td>
                                   <td nowrap>{{$out}}</td>
                                   <td nowrap>0</td>
                                   <td nowrap>{{$in ? $in : $out}}</td>
                                   <td nowrap>{{$row->passed ? $row->passed : 0}}</td>
                                   <td nowrap>{{$row->rejected ? $row->rejected : 0}}</td>
                                   <td nowrap>0</td>
                                </tr>
                            @endforeach
                            @php 
                               }
                               else
                               {
                            @endphp
                            @foreach($ItemLedgerData as $row)
                            @php
                            //DB::enableQueryLog();
                             $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name 
                             from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code 
                             where vw_code='".$row->vw_code."'");
                            //dd(DB::getQueryLog());
                             if(count($VWList) > 0)
                             {
                                $sales_order_no = $VWList[0]->sales_order_no;
                             }
                             else
                             {
                                    $sales_order_no = "-";
                             }
                             
                             
                            if (str_contains($row->po_code, 'OS')) 
                            {
                                $in = $row->in_qty;
                                $out = 0 ;
                             }
                            else
                            {   
                                $in = 0;
                                $out = $row->in_qty;
                            }
                            @endphp
                                <tr>
                                   <td nowrap>{{$srno++}}</td>
                                   <td nowrap>{{$row->inward_type ? $row->inward_type  : $row->outward_type}}</td>
                                   <td nowrap>{{$row->trimCode ? $row->trimCode : $row->trimOutCode}}</td>
                                   <td nowrap>{{$row->trimDate ? $row->trimDate : $row->tout_date}}</td>
                                   <td nowrap>{{$row->ac_name ? $row->ac_name : $row->vendor_name}}</td>
                                   <td nowrap>{{$sales_order_no }}</td>
                                   <td nowrap>{{$row->po_code ? $row->po_code : "-"}}</td>
                                   <td nowrap>0</td>
                                   <td nowrap>{{$in}}</td>
                                   <td nowrap>{{$out}}</td>
                                   <td nowrap>{{$out - $in}}</td>
                                   <td nowrap>0</td>
                                   <td nowrap>0</td>
                                   <td nowrap>0</td>
                                   <td nowrap>0</td>
                                </tr>
                            @endforeach
                            @php 
                               }
                            @endphp
                         </tbody>
                      </table>
                      @php 
                       }
                       else
                       {
                      @endphp
                       <table class="table" style="height:10vh; ">
                         <thead>
                              <tr style="background-color:#eee;">
                                   <th nowrap>Sr.No.</th>
                                   <th nowrap>Transaction Type</th>
                                   <th nowrap>Transaction No.</th>
                                   <th nowrap>Date</th>
                                   <th nowrap>From/To</th>
                                   <th nowrap>Order Ref No</th>
                                   <th nowrap>PO No.</th>
                                   <th nowrap>Item PO No.</th>
                                   <th nowrap>Receive Qty.</th>
                                   <th nowrap>Issued Qty.</th>
                                   <th nowrap>Balance</th>
                              </tr>
                         </thead>
                         <tbody>
                            @php 
                                $srno = 1;
                            @endphp
                            @foreach($ItemLedgerData as $row)
                            @php
                            //DB::enableQueryLog();
                             $VWList=DB::select("select distinct vendor_work_order_master.sales_order_no as sales_order_no, ledger_master.ac_name 
                             from vendor_work_order_master inner join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code 
                             where vw_code='".$row->vw_code."'");
                            //dd(DB::getQueryLog());
                             if(count($VWList) > 0)
                             {
                                $sales_order_no = $VWList[0]->sales_order_no;
                             }
                             else
                             {
                                    $sales_order_no = "0";
                             }
                             
                             
                            if (str_contains($row->trimCode, 'GRN')) 
                            {
                                $in = $row->in_qty;
                                $out = 0 ;
                             }
                            else
                            {   
                                $in = 0;
                                $out = $row->in_qty;
                            }
                            @endphp
                                <tr>
                                   <td nowrap>{{$srno++}}</td>
                                   <td nowrap>{{$row->inward_type ? $row->inward_type  : $row->outward_type}}</td>
                                   <td nowrap>{{$row->trimCode ? $row->trimCode : $row->trimOutCode}}</td>
                                   <td nowrap>{{$row->trimDate ? $row->trimDate : $row->tout_date}}</td>
                                   <td nowrap>{{$row->ac_name ? $row->ac_name : $row->vendor_name}}</td>
                                   <td nowrap>{{$sales_order_no }}</td>
                                   <td nowrap>{{$row->po_code ? $row->po_code : "-"}}</td>
                                   <td nowrap>{{$row->po_code ? $row->po_code : "-"}}</td>
                                   <td nowrap>{{$in}}</td>
                                   <td nowrap>{{$out}}</td>
                                   <td nowrap>{{$out - $in}}</td>
                                </tr>
                            @endforeach
                         </tbody>
                      </table>
                      @php 
                       }
                      @endphp
                  </div>
                </div>
               </div>
            </main>
         </div>
      </div>
      <p class="mt-1" style="margin-bottom: 0px; text-align:center;">This is a Computer Generated ITEM LEDGER REPORT</p>
      <!--<p class="text-center d-print-none"><a href="{{Route('FabricInward.index')}}">&laquo; Back to List</a></p>-->
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script> 
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  
   
      $('#invoice').click(function(){
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

        XLSX.writeFile(file, 'ITEM LEDGER REPORT.' + type);
     }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
     $(document).ready(function(){
            var result = [];
            $('#tbl2 tr').each(function(){
               $('td', this).each(function(index, val){
                  if(!result[index]) result[index] = 0;
                  result[index] += parseFloat($(val).text());
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
            $('#tbl2').append('<tr><td colspan="8" class="text-right"><strong>Total : </strong></td></tr>');
            $(result).each(function(){
               $('#tbl2 tr').last().append('<td><strong>'+this+'</strong></td>')
            });
      });
   </script>
</html>