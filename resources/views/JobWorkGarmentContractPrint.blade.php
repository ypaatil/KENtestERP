<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Ken Global Designs Pvt. Ltd.</title>
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
         @media print{
         @page {size: landscape; }
         body
         { zoom: 70%;
         font-size:14px;    
         }
         table  th, td
         {
         font-size:12px;   
         }
         #Assortment,#Fabric,#Sewing,#Packing
         {
         page-break-after: always;
         }
         }
      </style>
   </head>
   <body>
      <!-- Container -->
      <div class="container-fluid ">
         <!-- Header -->
         <div class="invoice" id="invoice">
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
              
               <h4 class="text-4"></h4>
               <div class=""></div>
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
                  width: 20%;
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
                  
                  td,th
                  {
                    width: 20%;
                  }
               </style>
               <div id="printInvoice" style="border: 1px solid;">
                    <div class="row"  style="margin-top:5%">
                      <div class="col-md-4">
                         <p><img src="http://kenerp.com/logo/ken.jpeg"  alt="Ken Enterprise Pvt. Ltd." height="130" width="230"> </p>
                      </div>
                      <div class="col-md-6">
                            <h5>KEN GLOBAL DESIGNS PRIVATE LIMITED</h5><br/>
                            18/20, BACK SIDE OF HOTEL CITY INN, INDUSTRIAL ESTATE, ICHALKARANJI,<br/> 
                            KOLHAPUR,MAHARASHTRA - 416115,<br/>
                            GSTIN NO:- 27ABCCS7591Q1ZD  PAN NO:- ABCCS7591Q
                      </div>
                    </div>
               </div>
               <!-- Passenger Details -->
               <div class="Assortment">
                  <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">Job Work Garment Contract</h4>
                  <table class="table table-bordered text-1 table-sm" style="height:10vh; ">
                        <tr>
                           <th>Date</th>
                           <td>{{ $VendorList[0]->vw_date }}</td>
                        </tr>
                        <tr>
                           <th>Work Order No</th>
                           <td>{{ $VendorList[0]->vw_code }}</td>
                        </tr>
                        <tr>
                           <th>Vendor Name</th>
                           <td>{{ $VendorList[0]->Ac_name }}</td>
                        </tr>
                        <tr>
                           <th>Job Work Type</th>
                           <td>Cut to Pack  / Finishing / Packing / Stitching</td>
                        </tr>
                        <tr>
                           <th>Style Details</th>
                           <td>{{ $VendorList[0]->style_no }}({{$VendorList[0]->fg_name}})</td>
                        </tr>
                        <tr>
                           <th>Order No.</th>
                           <td>{{ $VendorList[0]->sales_order_no }}</td>
                        </tr>
                        <tr>
                           <th>Order Qty.</th>
                           <td>{{ $VendorList[0]->final_bom_qty }} Pcs.</td>
                        </tr>
                        <tr>
                           <th>Delivery Date</th>
                           <td>{{ $VendorList[0]->delivery_date }}</td>
                        </tr>
                        <tr>
                           <th>Rate Per Piece</th>
                           <td>Rs. {{ $VendorList[0]->cons_per_piece }}</td>
                        </tr>
                        <tr>
                           <th>Debit of Thread Cost</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Debit of Excess Trim Consumption</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Debit of Rejection Garment</th>
                           <td>{{ $VendorList[0]->debit_reject_garment ? $VendorList[0]->debit_reject_garment : 'We will be accepting 1% Diff and incase of Shortage we won’t pay charge CM for rejection Pcs In case of rejection more than 1% then Fabric & Trim charges for rejected pcs will be debited towards Invoice. (we won’t accept any CM Charges for these Pcs. )'}}</td>
                        </tr>
                        <tr>
                           <th>Debit of Inspection Failure</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Debit of Transportation Cost</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Other Cost (If Any)</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Payment Terms</th>
                           <td>Against Delivery But need to Pass Inspection and OCR</td>
                        </tr>
                        <tr>
                           <th>Delivery At</th>
                           <td>At Factory KEN GLOBAL DESIGNS PRIVATE LIMITED, Kondigre</td>
                        </tr>
                        <tr>
                           <th>Remarks</th>
                           <td> 1) If any Rejection beyound 1% will be debited the Fabric & Trims cost to vendor in case of no excess material.<br/>
                                2) Vendor is not allowed to process except the factory registred location & also responsible for the damage/missing/stolen for the material. 
                           </td>
                        </tr>
                        <tr>
                           <th>Contact Persons 1</th>
                           <td>-</td>
                        </tr>
                        <tr>
                           <th>Contact Persons 2</th>
                           <td>-</td>
                        </tr>
                  </table>
               </div>
               <!-- Footer -->
               <footer  >
                  <div class="btn-group d-print-none"> <a  href="javascript:window.print()" class="btn btn-info"> Print</a> </div>
                  <button type="button" id="export_button" class="btn btn-warning">Export</button>  
               </footer>
         </div>
         </main>
      </div>
      </div>
      <input type="hidden" id="todaysDate" value="{{date('d-m-Y')}}"> 
      <p class="text-center d-print-none"><a href="{{route('VendorWorkOrder.index')}}">&laquo; Back to List</a></p>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"
      integrity="sha256-c9vxcXyAG4paArQG3xk6DjyW/9aHxai2ef9RpMWO44A=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
   <script>
      function html_table_to_excel(type)
       {
          var data = document.getElementById('invoice');
          var todaysDate = $("#todaysDate").val();
          var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
      
          XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
      
          XLSX.writeFile(file, 'Job Work Garment Contract('+todaysDate+').' + type);
       }
      
       const export_button = document.getElementById('export_button');
      
       export_button.addEventListener('click', () =>  {
          html_table_to_excel('xlsx');
       });
       
       
       
       
      //   $('#printInvoice').click(function(){
      //             Popup($('.invoice')[0].outerHTML);
      //             function Popup(data) 
      //             {
      //                 window.print();
      //                 return true;
      //             }
      //         });
      		
      		
   </script>
</html>