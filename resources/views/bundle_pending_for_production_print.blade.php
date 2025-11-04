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
                  .outer-wrapper {
            margin: 10px;
            margin-left: 20px;
            margin-right: 20px;
            border: 1px solid black;
            border-radius: 4px;
            box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.9);
            max-width: fit-content;
            max-height: fit-content;
            overflow-x: auto; /* Horizontal scrolling */
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
 .table {
            min-width: max-content;
            border-collapse: separate;
            border-spacing: 0px;
        }

        .table th,
        .table td {
            padding: 15px;
            padding-top: 10px;
            padding-bottom: 10px;
            border:1px solid black;
        }

        .table th {
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

        .table th:first-child {
            z-index: 3;
        }

        .table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #FFF;
              color: #000;
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
           
                 
                 <table style="width:100%;" class="text-center">
<tr class="mb-4">
<td colspan="9" style="border-top: none !important;border-left:none !important;border-right:none !important;margin-bottom:100%!important;" nowrap><h4>PRODUCTION PENDING BUNDLE NO. REPORT </h4></td>
</tr>
<tr><td><br></td></tr>
<tr class="noBorder">
<td  nowrap class="mt-4" style="font-weight:bold;">Sales Order :  {{ $sales_order_no }}</td>
<td  nowrap class="mt-4" style="font-weight:bold;">Color:  {{ $color_name }}</td>
<td  nowrap class="mt-4" style="font-weight:bold;">Style :  {{ $mainstyle_name }}</td>


</tr>  
</table>
               <!-- Passenger Details -->
               
              <div class="outer-wrapper">
    <div class="table-wrapper">
                <table class="table table-bordered text-1 table-sm" id="bundleTable" style="height:10vh;">
                <thead>
                     <tr>
                        <th class="text-center">Bundle No</th>
                       <th class="text-center">Cutting Qty</th> 
                        
                        @foreach($operationList as $rowOP)
                         <th nowrap class="text-center">{{ $rowOP->operation_name }}</th>
                        @endforeach
                       
                        <th nowrap class="text-center">Total</th>  
                       
                     </tr>
                  </thead>
                  <tbody>
                        @php
                            $srno = 1; 
                            $total_qty1 = 0;
                            $total_amount1 = 0;
                            $total_qty=0;
                            
                            
                           
                        
            $filter = DB::table('daily_production_entry_details AS dps')
             ->leftJoin('daily_production_entry_masters','daily_production_entry_masters.daily_pr_entry_id','=','dps.dailyProductionEntryId')
                ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
            ->join('ob_details', function ($join) {
            $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
            })    
            ->select(
            'dps.bundleNo',
            'dps.operationNameId',
            'dps.dailyProductionEntryDate',
            'dps.sales_order_no','dps.color_id','ob_details.mainstyle_id',DB::raw('sum(dps.stiching_qty) as stiching_qty,dps.vendorId'))
            ->where('dps.sales_order_no',$sales_order_no)
            ->where('dps.vendorId',$vendorId)  
            ->where('dps.color_id',$color_id)   
            ->where('ob_details.mainstyle_id',$mainstyle_id)   
            ->groupBy('dps.bundleNo','dps.operationNameId')
            ->get();   
           
            
            $dataMAP=[];
            
            foreach($filter as $rowMap)
            {
              
              $dataMAP[$rowMap->sales_order_no][$rowMap->vendorId][$rowMap->color_id][$rowMap->mainstyle_id][$rowMap->operationNameId][$rowMap->bundleNo][]=[
              "stiching_qty"=>$rowMap->stiching_qty
              ];
            
            
            }
            


              $Cuttingfilter = DB::table('cutting_entry_details')
             ->join('cutting_entry_master','cutting_entry_master.cuttingEntryId','=','cutting_entry_details.cuttingEntryId')
            ->select(
             DB::raw('sum(cutting_entry_details.cut_panel_issue_qty) as cut_panel_issue_qty,cutting_entry_master.sales_order_no,cutting_entry_details.vendorId,cutting_entry_details.color_id,cutting_entry_details.bundleNo'))
            ->where('cutting_entry_master.sales_order_no',$sales_order_no)
            ->where('cutting_entry_details.vendorId',$vendorId)  
            ->where('cutting_entry_details.color_id',$color_id)   
            ->groupBy('cutting_entry_details.bundleNo')
            ->get();   
           
            
            $dataMAPCutting=[];
            
            foreach($Cuttingfilter as $rowMapCutting)
            {
              
              $dataMAPCutting[$rowMapCutting->sales_order_no][$rowMapCutting->vendorId][$rowMapCutting->color_id][$rowMapCutting->bundleNo][]=[
              "cut_panel_issue_qty"=>$rowMapCutting->cut_panel_issue_qty
              ];
            
            
            }
        
                            
                           
                        @endphp
                        @foreach($data as $emp)     
                        
                 
                        
                        <tr>
                            <td class="text-center">{{ $emp->bundleNo }}</td>
                            
                           @php $TotalCuttingQty=0; @endphp
                             @if(isset($dataMAPCutting[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->bundleNo]))
                             
                             @foreach($dataMAPCutting[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->bundleNo] as $rowCuttingQty)
                             
                           @php  $TotalCuttingQty+=$rowCuttingQty['cut_panel_issue_qty'];  @endphp
                           
                          @endforeach
                          @endif
                           
                           <td class="text-center TotalCuttingQty">{{ $TotalCuttingQty }}</td>
                            
                            
                            @php   $grandTotal=0; @endphp
                             @foreach($operationList as $rowOP)
                             
                             @php $TotalQty=0; @endphp
                             
                             @if(isset($dataMAP[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->mainstyle_id][$rowOP->operation_id][$emp->bundleNo]))
                             
                             @foreach($dataMAP[$emp->sales_order_no][$emp->vendorId][$emp->color_id][$emp->mainstyle_id][$rowOP->operation_id][$emp->bundleNo] as $rowQty)
                             
                           @php  $TotalQty+=$rowQty['stiching_qty'];
                           
                            $grandTotal=$grandTotal+$TotalQty;
                           @endphp
                                  
                             @endforeach
                             @endif
                            <td class="text-center">{{ $TotalQty }}</td>
                          
                             @endforeach
                            <td class="text-right">{{ $grandTotal }}</td> 
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

        XLSX.writeFile(file, 'PRODUCTION PENDING BUNDLE NO. REPORT.' + type);
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
      
$(document).ready(function () {
    var $tbody = $('#bundleTable tbody');
    var rows = $tbody.find('tr').get();

    rows.sort(function (a, b) {
        var textA = $(a).children('td').first().text().trim();
        var textB = $(b).children('td').first().text().trim();

        // Check for blank cells first
        var isBlankA = textA === "";
        var isBlankB = textB === "";

        if (isBlankA && !isBlankB) return -1;
        if (!isBlankA && isBlankB) return 1;

        // If neither is blank, sort numerically
        var keyA = parseInt(textA, 10);
        var keyB = parseInt(textB, 10);

        return keyA - keyB;
    });

    $.each(rows, function (index, row) {
        $tbody.append(row); // Re-append in sorted order
    });
});


$(function() {
    let total = 0;

    $('#bundleTable tbody tr').each(function() {
        $(this).find('td.TotalCuttingQty').each(function() {
            let value = parseFloat($(this).text());
            if (!isNaN(value)) {
                total += value;
            }
        });
    });


  $('#bundleTable tbody tr:first td.TotalCuttingQty').first().text(total);
});


   </script>
</html>