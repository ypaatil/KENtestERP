<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <style>
        /* Global Styles */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
             font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f7f7;
            color: #333333;
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Heading Styles */
        h1, h3 {
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
            animation: slideInDown 0.5s ease forwards;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table Styles */
        #rptsum {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            animation: fadeIn 0.5s ease forwards;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
        }
        
            
         thead.sticky {
            position: sticky;
            top: 0; / Start at the top initially /
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            z-index: 1000;
        }
        

        #rptsum th, td {
            padding: 8px; /* Smaller padding for cells */
         /* border: 1px solid #dddddd; */
            border: 1px solid #000;
            text-align: left;
        }

        #rptsum th {
            background-color: #133b5c; /* Green */
            color: #ffffff; /* White text */
        }

        #rptsum td {
            background-color: #f9f9f9;
        }

        .total {
            font-weight: bold;
            background-color: #4CAF50;
            color: #ffffff;
        }

        
        /* CSS for the search input */
.search-input {
    padding: 10px; /* Padding for the input */
    border: 1px solid #ccc; /* Border color */
    border-radius: 5px; /* Rounded corners */
    width: 300px; /* Width of the input box */
   
    font-size: 14px; /* Font size */
    transition: border-color 0.3s; /* Smooth transition for border color */
    margin:12px;
}

.search-input:focus {
    border-color: #007bff; /* Border color when input is focused */
    outline: none; /* Remove default focus outline */
}

button {
    padding: 10px 20px; /* Padding for the buttons */
    margin: 0 5px; /* Margin between buttons */
    background-color: #007bff; /* Button background color */
    color: #fff; /* Button text color */
    border: none; /* Remove button border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s; /* Smooth transition for background color */
}

button:hover {
    background-color: #0056b3; /* Button background color on hover */
}

#export_button{
     padding: 10px 20px; /* Padding for the buttons */
    margin: 0 5px; /* Margin between buttons */
    background-color: #ff7f50; /* Button background color */
    color: #fff; /* Button text color */
    border: none; /* Remove button border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s; /* Smooth transition for background color */  
}

         /* Hide buttons in print preview */
    /* Hide buttons in print preview */
@media print {
    #export_button,
    #print_button,
    #croreBtn,
    #lakhBtn,
    #actualBtn,
    #back_button {
        display: none !important;
    }
}


        /* Darken table header in print preview */
        @media print {
            th {
                color: #000000 !important;
            }
              tr td {
                color: #000000 !important;
            }
             tfoot tr td {
                color: #000000 !important;
            }
              .container {
                  
                  border:0;
                  border-radius: 8px;
                box-shadow: 0 0 0 rgba(0, 0, 0, 0.1);
                background-color: #ffffff;
                
              }
        }
        
  
.row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}



.centerDiv {
    text-align: center;
    flex-grow: 1; /* To make sure it takes up remaining space */
    font-weight:bold;
    padding-bottom:20px;
}


    #rptHead {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            animation: fadeIn 0.5s ease forwards;
            border-radius: 8px;
            padding:10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
     
     
th.sort-asc::after {
  content: " \2191"; /* Up arrow */
}

th.sort-desc::after {
  content: " \2193"; /* Down arrow */
}     
    </style>
    
    	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>  
         <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.6/xlsx.full.min.js"></script>
        
</head>
<body>
<div class="container" id="printsdiv">
    
<div class="row">
    <div class="left-buttons">
        <button  onClick="ExportToExcel()">Export</button>
        <button class="button" id="print_button" onclick="window.print()">Print</button>
    </div>
      <div class="centerDiv">
        <p>Daily Production Report</p>
         <p>{{ $sub_company_name }}</p>
        
    </div>
    <div class="right-button">
        <a href="/get_daily_production"><button id="back_button">Back</button></a>
    </div>
</div>


@php
       
        $masterFetch=DB::table('daily_production_entry_masters')->selectRaw("sum(overall_sam) as overall_sam,sum(overall_output) as overall_output,sum(overall_efficiency) as overall_efficiency,main_style_master_operation.mainstyle_name,is_style_change")
        ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','daily_production_entry_masters.mainstyle_id')
       ->where('daily_pr_date',$fromDate)
        ->where('dept_id',$dept_id)
        ->where('daily_production_entry_masters.sub_company_id',$sub_company_id)   
        ->first(); 
        
        
        $multiStyleFetch=DB::table('daily_production_entry_masters')->selectRaw("main_style_master_operation.mainstyle_name")
        ->join('main_style_master_operation','main_style_master_operation.mainstyle_id','=','daily_production_entry_masters.mainstyle_id')
       ->where('daily_pr_date',$fromDate)
        ->where('dept_id',$dept_id)
          ->where('daily_production_entry_masters.sub_company_id',$sub_company_id)   
        ->get();
        
        $styleArray=[];
        
  foreach($multiStyleFetch as $rowFetch)
  {
  
      $styleArray[]=$rowFetch->mainstyle_name;
  
  }

        

 
 @endphp

@if(isset($masterFetch))


@php 

         
      $detail = DB::table('daily_production_entry_details_operation AS dp')
    ->select(
        'dm.line_name',
        'ms.mainstyle_name',
        'dp.operation_id',
        'ob_details.sam',
        'dp.pieces',
        'dp.efficiency',
        'em.fullName',
        'em.employeeCode',
        'ob_details.operation_name',
        'dp.station_no',
        'dp.remark',
        'dp.is_half_day'
    )
    ->join('line_master AS dm', 'dm.line_id', '=', 'dp.dept_id')
    ->join('main_style_master_operation AS ms', 'ms.mainstyle_id', '=', 'dp.mainstyle_id')
    ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dp.employeeCode')
    ->join('ob_details', function ($join) {
    $join->on('ob_details.operation_id', '=', 'dp.operation_id')
    ->whereColumn('ob_details.mainstyle_id', '=', 'dp.mainstyle_id');
    })
    ->where('dp.dept_id', $dept_id)
    ->where('dp.sub_company_id',$sub_company_id)     
    ->where('dp.daily_pr_date', $fromDate)
    ->groupBy('dp.daily_pr_date','em.employeeCode','dp.operation_id')
    ->orderBy('em.fullName')
    ->get();



@endphp



@if(count($detail) > 0)

  <table id="rptHead">
  <tr>
        <th style="border:0">Date :  {{ date('d/m/Y',strtotime($fromDate)) }}</th>
         <th  style="border:0">Department :  {{ $line_name ?? null }}</th>
        <th colspan="4" style="border:0">Style : {{ implode(',',array_unique($styleArray)) }}</th> 
    </tr>
     </table> 

    <table id="rptsum">
        <thead class="sticky">
              <tr>
           
             <th  style="text-align:center" colspan="8">Wage-Wise Daily Production</th>
        </tr>   
          <tr>
           
             <th  style="text-align:center">Operator</th>
              <th  style="text-align:center">Station</th>
             <th  style="text-align:center">Operation</th>
            <th  style="text-align:center">Sam</th>
            <th  style="text-align:center">Target</th>  
            <th  style="text-align:center">Output</th>
           <th  style="text-align:center">Eff%</th>
          <th  style="text-align:center">Remark</th>   
        </tr>
       
        </thead>
        <tbody>
         @php
        

     
         $no=1; $sum=[]; $cureentEmployee=null; @endphp
         @foreach($detail as $index => $row)
         
         
         @php
         
            if(isset($sum[$row->employeeCode])) 
           {
              
           $sum[$row->employeeCode] += isset($row->efficiency) ? $row->efficiency : 0;
            
           } else{
              
           $sum[$row->employeeCode]=isset($row->efficiency) ? $row->efficiency : 0;  
          
           }
         
      @endphp
      
        <tr> 
           <td class="amount text-right">{{ $row->fullName }}</td>    
            <td class="amount text-right"  style="text-align:right;">{{  $no++ }}</td>  
            <td class="amount text-right">{{  $row->operation_id }}-{{ $row->operation_name }}</td>  
          <td class="amount text-right" style="text-align:right;mso-number-format:'#,##0.00'">{{  number_format($row->sam,3) }}</td>  
          
          
          @if($row->sam!=0 || $row->sam!='')
          <td class="amount text-right" style="text-align:right">{{  round((60/$row->sam)) }}</td>   
          @else
            <td class="amount text-right" style="text-align:right">-</td>   
          @endif
          
           <td class="amount text-right" style="text-align:right">{{  $row->pieces }}</td>   
                               {{-- $row->efficiency --}}
              
             @if($row->is_half_day==1)                  
             <td class="EFFICIENCY text-right" style="display:none;">{{ ((($row->sam *  $row->pieces) / 2.4))  }}</td>   
             
             @else
               <td class="EFFICIENCY text-right" style="display:none;">{{ ((($row->sam *  $row->pieces) / 4.8))  }}</td>    
             @endif
            
          <td class="amount text-right TOTEFF" id="total_efficiency" style="font-weight:bold;text-align:right"></td>    
         <td  style="text-align:left;font-weight:bold;">{{  $row->remark }}</td>   
        </tr>
          
      @endforeach
  
        </tbody>
        
        <tfoot>
        <tr>
        <td colspan="3" style="text-align:right;font-weight:bold">Total</td>    
         <td  style="text-align:right;font-weight:bold">{{ $masterFetch->overall_sam ??null }}</td>
        
          <td  style="text-align:right;font-weight:bold">-</td>
          
           <td  style="text-align:right;font-weight:bold">{{ $masterFetch->overall_output ??null }}</td>  
             <td  style="text-align:right;font-weight:bold">{{ $masterFetch->overall_efficiency ??null }}</td>  
           <td  style="text-align:right;font-weight:bold"></td>       
        </tr>    
            
        </tfoot>

  
    </table>
    @endif
    
    
    @php
    
    
    $detailPcs = DB::table('daily_production_entry_details AS dps')
    ->select(
        'em.fullName',
        'dps.operationNameId',
        'ob_details.operation_name',
        'ob_details.sam',
        DB::raw('SUM(dps.stiching_qty) as stiching_qty')
    )
    ->join('employeemaster_operation AS em', 'em.employeeCode', '=', 'dps.employeeCode')
    ->join('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'dps.sales_order_no')
    ->join('ob_details', function ($join) {
        $join->on('ob_details.operation_id', '=', 'dps.operationNameId')
            ->whereColumn('ob_details.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation');
    })
    ->where('dps.line_no', $dept_id)
    ->where('dps.vendorId', $sub_company_id)
    ->where('dps.dailyProductionEntryDate', $fromDate)
    ->groupBy('dps.dailyProductionEntryDate', 'dps.employeeCode', 'ob_details.operation_id')
    ->get();


    
    @endphp
    
    
    
    

    @if(count($detailPcs)>0)
     <table id="rptsum">
        <thead class="sticky">
           <tr>
           
             <th  style="text-align:center" colspan="4">PCS-Wise Daily Production</th>
             <th  style="text-align:center" colspan="4">Style : {{ implode(',',array_unique($styleArrayPCS)) }}</th> 
            
        </tr>
         
          <tr>
           
             <th  style="text-align:center">Operator</th>
              <th  style="text-align:center">Station</th>
             <th  style="text-align:center">Operation</th>
            <th  style="text-align:center">Sam</th>
            <th  style="text-align:center">Target</th>  
            <th  style="text-align:center">Output</th>
           <th  style="text-align:center">Eff%</th>
          <th  style="text-align:center">Remark</th>   
        </tr>
       
        </thead>
        <tbody>
         @php
         
      

    

     
         $no=1; $sum=[]; $cureentEmployee=null; @endphp
         @foreach($detailPcs as $index => $rowPcs)
         
    
      
     
        <tr> 
           <td class="amount text-right">{{ $rowPcs->fullName }}</td>    
            <td class="amount text-right">{{  $no++ }}</td>  
            <td class="amount text-right">{{  $rowPcs->operationNameId }}-{{ $rowPcs->operation_name }}</td>  
          <td class="amount text-right" style="text-align:right;mso-number-format:'#,##0.00'">{{  number_format($rowPcs->sam,3) }}</td>  
          
          
          @if($rowPcs->sam!=0 || $rowPcs->sam!='')
          <td class="amount text-right" style="text-align:right">{{  round((60/$rowPcs->sam)) }}</td>   
          @else
            <td class="amount text-right" style="text-align:right">-</td>   
          @endif
          
           <td class="amount text-right" style="text-align:right">{{  $rowPcs->stiching_qty }}</td>   
                              
           <td class="EFFICIENCY text-right" style="display:none;">{{ ((($rowPcs->sam *  $rowPcs->stiching_qty) / 4.8))  }}</td>   
            
          <td class="amount text-right TOTEFF" id="total_efficiency" style="font-weight:bold;text-align:right"></td>    
         <td  style="text-align:left;font-weight:bold;">-</td>   
        </tr>
          
      @endforeach
  
        </tbody>
        
        <tfoot>
        <tr>
        <td colspan="3" style="text-align:right;font-weight:bold">Total</td>    
         <td  style="text-align:right;font-weight:bold">-</td>
        
          <td  style="text-align:right;font-weight:bold">-</td>
          
           <td  style="text-align:right;font-weight:bold">-</td>  
             <td  style="text-align:right;font-weight:bold">-</td>  
           <td  style="text-align:right;font-weight:bold"></td>       
        </tr>    
            
        </tfoot>

  
    </table>
    @endif
       
      @else
      <div class="row" style="display:flex;justify-content:center;font-weight:bold;color:#F00">
   <span></span>       
      <span>No records has been added yet.!</span>
       <span></span>       
      </div>
      
      @endif

      
      
      
      <br>
      
    <table id="rptEffi">
        <thead>
      
        </thead>
        <tbody>
       <tr>
           
             <td  style="text-align:center;background-color:#e01414">1-19.99%</td>
              <td  style="text-align:center;background-color:#e01414" id="1_to_19"></td>
        </tr>
           <tr>
           
             <td  style="text-align:center;background-color:#ef3232">20-39.99%</td>
              <td  style="text-align:center;background-color:#ef3232" id="20_to_39"></td>
        </tr>
           <tr>
           
             <td  style="text-align:center;background-color:#f7c560">40-54.99%</td>
              <td  style="text-align:center;background-color:#f7c560" id="40_to_54"></td>
        </tr>
           <tr>
           
             <td  style="text-align:center;background-color:#f8fc2d">55-69.99%</td>
              <td  style="text-align:center;background-color:#f8fc2d" id="55_to_69"></td>
        </tr>
           <tr>
           
             <td  style="text-align:center;background-color:#1594ef">70-84.99%</td>
              <td  style="text-align:center;background-color:#1594ef" id="70_84"></td>
        </tr>
           <tr>
           
             <td  style="text-align:center;background-color:#0ba01f">ABOVE 85%</td>
              <td  style="text-align:center;background-color:#0ba01f" id="above_85"></td>
        </tr>
        <tr>
           
             <td  style="text-align:center;background-color:#f4bab5">ABOVE 60%</td>
              <td  style="text-align:center;background-color:#f4bab5"  id="above_60"></td>
        </tr>
        </tbody>
        
    </table>
   
    
    
</div>
</body>


<script>

function ExportToExcel(type, fn, dl) {
    // Get the element to export
    var elt = document.getElementById('rptsum'); // Adjusted to 'rptsum' if that's the main table
    
    // Ensure the element exists
    if (!elt) {
        console.error('Element with id "rptsum" not found');
        return;
    }
    
    // Remove elements with class "EFFICIENCY" (if required)
    const elements = elt.getElementsByClassName("EFFICIENCY");
    while (elements.length > 0) {
        elements[0].remove();
    }

    // Convert the table to a worksheet
    var ws = XLSX.utils.table_to_sheet(elt, { raw: true });

    // Iterate through the rows to format decimals properly
    for (let cell in ws) {
        if (ws[cell].t === 'n' && ws[cell].v % 1 !== 0) {
            // If it's a number and not an integer, format as a number with decimal
            ws[cell].z = '0.00'; // Change '0.00' to your desired format
        }
    }

    // Create a workbook from the worksheet
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "sheet1");

    if (dl) {
        // Generate a base64 string and trigger download
        var wbBase64 = XLSX.write(wb, {
            bookType: type || 'xlsx',
            bookSST: true,
            type: 'base64'
        });

        var link = document.createElement('a');
        link.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + wbBase64;
        link.download = fn || ('DailyProduction.' + (type || 'xlsx'));
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else {
        // Save file
        XLSX.writeFile(wb, fn || ('DailyProduction.' + (type || 'xlsx')));
    }
}



         
  $(document).ready(function() {
    var lastName = "";
    var rowspan = 1;
    var totalEFF = 0;
    
    $('#rptsum tbody tr').each(function(index) {
      
        var currentName = $(this).find('td:first').text().trim(); // Get current employee name
        var currentEFFText = $(this).find('td.EFFICIENCY.text-right').text().trim(); // Get current efficiency text
        var currentEFF = parseFloat(currentEFFText.replace(/,/g, '')); // Parse efficiency as float, remove commas
        
 if (isNaN(currentEFF)) {
            currentEFF = 0; // Set to 0 if not a valid number
        }
        
        
        
            if (currentName === lastName) {
                rowspan++;
                totalEFF += currentEFF;
                
                $(this).find('td:first').remove(); // Remove redundant name cell
                $(this).find('td.amount.text-right').last().remove(); // Remove last efficiency cell in the row
                $('#rptsum tbody tr').eq(index - rowspan + 1).find('td:first').attr('rowspan', rowspan); // Set rowspan for the first cell of the group
                $('#rptsum tbody tr').eq(index - rowspan + 1).find('td.amount.text-right').last().attr('rowspan', rowspan).text(totalEFF.toFixed(2)); // Set rowspan for the last cell of the group and display total EFF
            } else {
                
                if (rowspan > 1) {
                    $('#rptsum tbody tr').eq(index - rowspan).find('td.amount.text-right').last().text(totalEFF.toFixed(2)); // Display total EFF for the previous group
                } else{
                    
                     $('#rptsum tbody tr').eq(index - rowspan).find('td.amount.text-right').last().text(totalEFF.toFixed(2));
                }
                lastName = currentName;
                rowspan = 1;
                totalEFF = currentEFF;
                
                
                
                
            }
             if (index === $('#rptsum tbody tr').length - 1) {
            $('#rptsum tbody tr').eq(index - rowspan + 1).find('td.amount.text-right').last().text(totalEFF.toFixed(2));
        }
        
        
            
        
    });
    

   
});




  $(document).ready(function() {
    var lastName = "";
    var rowspan = 1;
    var totalEFF = 0;
     var countEfficiency1to19 = 0;
    var countEfficiency20to39 = 0;
    var countEfficiency40to54=0;
     var countEfficiency55to69=0;
    var countEfficiency70to84=0;
     var countEfficiencyabove_85=0; 
    var countEfficiencyabove_60=0; 
    
    $('#rptsum tbody tr').each(function(index) {
      
        var currentName = $(this).find('td:first').text().trim(); // Get current employee name
        var TOTEFF = $(this).find('td.TOTEFF.text-right').text().trim(); // Get current efficiency text
        var currentEFF = parseFloat(TOTEFF.replace(/,/g, '')); // Parse efficiency as float, remove commas
        
  
                     
                       if(currentEFF >= 1 && currentEFF < 20) {
            countEfficiency1to19++;
          $(this).find('td.TOTEFF.text-right').css('background-color', '#e01414');
            
        }  if (currentEFF >= 20 && currentEFF < 40) {
            countEfficiency20to39++;
           $(this).find('td.TOTEFF.text-right').css('background-color', '#ef3232');   
        }
          if (currentEFF >= 40 && currentEFF < 55) {
            countEfficiency40to54++;
             $(this).find('td.TOTEFF.text-right').css('background-color', '#f7c560');   
        }
         if (currentEFF >= 60) {
            countEfficiencyabove_60++;
            
        }
         if (currentEFF >= 55 && currentEFF < 70) {
            countEfficiency55to69++;
             $(this).find('td.TOTEFF.text-right').css('background-color', '#f8fc2d');   
        }  if (currentEFF >= 70 && currentEFF < 85) {
            countEfficiency70to84++;
             $(this).find('td.TOTEFF.text-right').css('background-color', '#1594ef');   
        }  if (currentEFF >= 85) {
            countEfficiencyabove_85++;
             $(this).find('td.TOTEFF.text-right').css('background-color', '#0ba01f');   
        } 

        
            
        
    });
    
   
     $('#1_to_19').text(countEfficiency1to19);
     $('#20_to_39').text(countEfficiency20to39);
     $('#40_to_54').text(countEfficiency40to54);   
     $('#55_to_69').text(countEfficiency55to69);    
     $('#70_84').text(countEfficiency70to84);  
     $('#above_85').text(countEfficiencyabove_85);  
     $('#above_60').text(countEfficiencyabove_60);       
    

   
});




</script>

</html>
