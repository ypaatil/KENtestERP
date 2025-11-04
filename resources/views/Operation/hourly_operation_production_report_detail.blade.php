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
          <!--<a  href="javascript:window.print()" class="button_niks btn  btn-info btn-rounded "> Print</a>-->
          <button type="button" id="export_button" class="btn btn-warning">Export</button>
         <!-- Header -->
         <div class="invoice" id="invoice" >
            <!-- Main Content -->
            <main>
               <!-- Item Details -->
   
            
               <style>
               body{
                   
                   overflow:hidden;
               }
       

        .invoice-container {
            margin: 0 auto;
            max-width: 1200px;
            background: white;
            border-radius: 10px;
            padding: 30px;
         
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table-wrapper {
            overflow-y: scroll;
            overflow-x: scroll;
            height: fit-content;
            max-height: 70.4vh;
            margin-top: 22px;
            margin: 15px;
            padding-bottom: 20px;
        }
 table {
            min-width: max-content;
            border-collapse: separate;
            border-spacing: 0px;
        }

        table th,
        table td {
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
            border:1px solid black;
        }

        table th {
            position: sticky;
            top: 0;
            left: 0;
            background-color: #FFF;
            color: #000;
            text-align: center;
            font-weight: bold;
           font-size: 14px; /* Default font size */
            outline: 0.7px solid black;
            border: 1.5px solid black;
            z-index: 2;
             border:1px solid black;
        }

       
        
 
        
  
        
        
        
        
           tfoot td {
            border: 1px solid black;
            text-align: right;
            font-weight: bold;
        }
      rder: #000000 solid 1px;
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
             
               <div id="printInvoice"  style="margin-right:10px;">
                 <h4 class="text-center" style="color: #000;font-size:20px;font-weight:bold;">HOURLY OPERATION PRODUCTION REPORT</h4>  
                 <h5 class="text-center" style="color: #000;font-size:20px;font-weight:bold;">Unit: {{ $sub_company_name }}</h5>  
                 <h5 class="text-center" style="color: #000;font-size:20px;font-weight:bold;">Line: {{ $line_name }} &nbsp;&nbsp; Date: {{ date('d/m/Y',strtotime($fromDate)) }}</h5>  
               <!-- Passenger Details -->
               
              <div class="outer-wrapper">
    <div class="table-wrapper">
                <table class="table table-bordered text-1 table-sm" style="height:10vh;">
                <thead>
                     <tr>
                        <th class="text-center">Sr.No.</th>  
                        <th class="text-center">Operator</th>
                         <th class="text-center">Operation</th>   
                         <th nowrap class="text-center">9 To 10</th>
                          <th nowrap class="text-center">10 To 11</th>
                           <th nowrap class="text-center">11 To 12</th>
                            <th nowrap class="text-center">12 To 1</th>
                            <th nowrap class="text-center">1.30 To 2.30</th>
                             <th nowrap class="text-center">2.30 To 3.30</th>
                           <th nowrap class="text-center">3.30 To 4.40</th>
                           <th nowrap class="text-center">4.40 To 5.40</th>
                        <th nowrap class="text-center">Total</th>  
                       
                     </tr>
                  </thead>
                  <tbody>
                         @php
                            $srno = 1; 
                     
                        @endphp
                        @foreach($data as $OP)     
                        
                        <tr>
                             <td class="text-center">{{ $srno++ }}</td>
                             <td class="text-center">{{ $OP->fullName }}</td>
                              <td class="text-center">{{ $OP->operation_name }}</td> 
                            <td class="text-right">{{ indian_number_format_wd($OP->nine_ten) }}</td>
                           <td class="text-right">{{ indian_number_format_wd($OP->ten_eleven) }}</td>
                        <td class="text-right">{{ indian_number_format_wd($OP->eleven_twelve) }}</td>   
                        <td class="text-right">{{ indian_number_format_wd($OP->twelve_one) }}</td>    
                         <td class="text-right">{{ indian_number_format_wd($OP->oneThirty_twoThirty) }}</td>   
                         <td class="text-right">{{ indian_number_format_wd($OP->twoThirty_threeThirty) }}</td>    
                          <td class="text-right">{{ indian_number_format_wd($OP->threeThirty_fourefourty) }}</td>    
                            <td class="text-right">{{ indian_number_format_wd($OP->fourefourty_fiveFourty) }}</td>
                              <td class="text-right">{{ indian_number_format_wd($OP->total_output) }}</td>   
                        </tr>
                        @php
                          
                          
                        @endphp
                        @endforeach
                  </tbody> 
               
               </table>
       </div>
                  </div>
                  <br>
               </div>
            </main>
         </div>
      </div>
      <p class="text-center d-print-none"><a href="EmployeeDetailedSalaryReport">	Back To Filter </a></p>
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

        XLSX.writeFile(file, 'Hourly Operation Production Detail Report.' + type);
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