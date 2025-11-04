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
         <div class="invoice" id="invoice" >
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
               <div class="row" style="text-align:center">
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
               <br/>
               <br/>
               <div id="printInvoice"  style="margin-right:10px;">
                 <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">EMPLOYEE DETAILED SALARY REPORT </h4>  
               <!-- Passenger Details -->
                
                
            <div class="row"><div class="col-md-4"></div> <div class="col-md-4"> <h4 class="text-center mt-2" style="color: #000;font-size:20px;font-weight:bold;">From:  </b>  <span style="display: inline-block;text-align: right;"> {{ date('d-m-Y',strtotime($fromDate)) }}  <b>To: </b> {{ date('d-m-Y',strtotime($toDate)) }}  </span></h4></div><div class="col-md-2 mt-2"></div> <div class="col-md-2 mt-2"><b>Present Days:- {{ count(array_unique($PresentCount)); }}</b></div></div>    
              
              
               <div class="col-md-12">
                <table class="table table-bordered text-1 table-sm" style="height:10vh;">
                  <thead>
                     <tr>
                        <th class="text-center">Sr No</th>
                        <th nowrap class="text-center">Date</th>
                        <th nowrap class="text-center">Order No.</th>
                        <th nowrap class="text-center">Emp Code</th>
                        <th nowrap class="text-center">Emp Name</th> 
                        <th nowrap class="text-center">Operation Name</th> 
                        <th nowrap class="text-center">Qty</th> 
                        <th nowrap class="text-center">Rate</th> 
                        <th class="text-center">Amount</th> 
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
                        @endphp
                        @foreach($data as $emp)     
                        
                        @php
                        
                                    $rateMap = [
                        115 => 'rate',   
                        110 => 'rate3',  
                        628 => 'rate4',  
                        686 => 'rate5',  
                        113 => 'rate6'  
                        ];
                        
                        
                        $rateKey = $emp->vendorId;
                        
                     
                        $rateProperty = $rateMap[$rateKey] ?? 'rate'; 
                        $rate = isset($emp->{$rateProperty}) ? $emp->{$rateProperty} : $emp->rate;  
                        
                        @endphp
              
                        <tr>
                            <td nowrap class="text-center">{{$srno++}}</td>
                            <td nowrap >{{date("d-m-Y", strtotime($emp->dailyProductionEntryDate))}}</td>
                            <td nowrap class="text-center">{{$emp->sales_order_no}}</td>
                            <td nowrap class="text-center">{{$emp->employeeCode}}</td>
                            <td nowrap>{{$emp->fullName}}</td> 
                            <td nowrap>{{$emp->operation_name}}</td> 
                            <td nowrap class="text-right">{{ number_format($emp->stiching_qty, 0, '.', ',')}}</td> 
                            <td nowrap class="text-right">{{$rate}}</td> 
                            <td nowrap class="text-right">{{number_format(($emp->stiching_qty * $rate), 2, '.', ',')}}</td> 
                        </tr>
                        @php
                            $total_qty1 += $emp->stiching_qty;
                            $total_amount1 += ($emp->stiching_qty * $rate);
                        @endphp
                        @endforeach
                  </tbody> 
                  <tfoot>
                      <tr>
                          <th colspan="6" class="text-right">Total : </th>
                          <th class="text-right">{{number_format($total_qty1, 0, '.', ',')}}</th>
                          <th class="text-right"></th>
                          <th class="text-right">{{number_format( $total_amount1, 2, '.', ',')}}</th>
                      </tr>
                  </tfoot>
               </table>
                  <div class="row" style="display: flex; -ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;">
                   
                        <h4 class="text-4 mt-2">Prepared By:</h4>	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     
                        <h4 class="text-4 mt-2">Checked By:</h4>	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   
                        <h4 class="text-4 mt-2">Approved By:</h4>	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     
                        <h4 class="text-4 mt-2">Authorized By:</h4>
                     </div>
                  </div>
                  <br>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="EmployeeDetailedSalaryReport">	Back To Filter </a>></p>
   </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" ></script>
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <script type="text/javascript" src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>
   <script>  

     function html_table_to_excel(type)
     {
        var data = document.getElementById('invoice');

        var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

        XLSX.writeFile(file, 'Employee Detail Salary Report.' + type);
      }

      const export_button = document.getElementById('export_button');
    
      export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
      });
      
    //   $('#printInvoice').click(function()
    //   {
    //       Popup($('.invoice')[0].outerHTML);
    //       function Popup(data) 
    //       {
    //           window.print();
    //           return true;
    //       }
    //   });
      
      
   </script>
</html>