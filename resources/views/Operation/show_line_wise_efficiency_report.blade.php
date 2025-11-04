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

        #rptsum th, td {
            padding: 8px; /* Smaller padding for cells */
         /* border: 1px solid #dddddd; */
            border: 1px solid #000;
            text-align: left;
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
        
        

        #rptsum   th {
         
            background-color: #133b5c;
            color: #ffffff;
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
            overflow: hidden;
            padding:10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
     
     th {
  cursor: pointer;
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
      <script src="{{URL::asset('assets/js/tableSort.js')}}"></script>  
</head>
<body>
<div class="container" id="printsdiv">
    
<div class="row">
    <div class="left-buttons">
        <button id="export_button">Export</button>
        <button class="button" id="print_button" onclick="window.print()">Print</button>
    </div>
      <div class="centerDiv">
        <span>Unitwise Efficiency Report</span>
    </div>
    <div class="right-button">
        <a href="/get_daily_production"><button id="back_button">Back</button></a>
    </div>
</div>


    <table id="rptsum">
        <thead class="sticky">
            <tr>   <th colspan="15" class="text-center" style="background-color:#1f4e78;font-weight: 700;">{{ $sub_company_name }}<span class="ml-5"></span></th></tr>
               
                 <tr>
   <th rowspan="2" style="background-color:#1f4e78;font-weight: 700;" nowrap>Date</th>
   
   @foreach($deptlist as  $rowFetch)
    <th colspan="2" class="text-center" style="background-color:#91d14f;font-weight: 700;">{{ $rowFetch->line_name }}</th>
   @endforeach
     <th colspan="2" class="text-center" style="background-color:#91d14f;font-weight: 700;">Total</th>    
</tr>

<tr style="background-color:#91d14f;font-weight: 700;">
    @php $i=0; @endphp
      @foreach($deptlist as  $rowFetch)
   <th style="background-color:#91d14f;font-weight: 700;">Produced Mins.</th>
   <th style="background-color:#91d14f;font-weight: 700;">Efficiency </th>
   @php $i++ @endphp
      @endforeach
    <th style="background-color:#91d14f;font-weight: 700;">Produced Mins.</th>
   <th style="background-color:#91d14f;font-weight: 700;">Efficiency</th>  
      
</tr>
       
        </thead>
        <tbody>
    
      @php $totalProMin=0;$totalEFF=0;$totalPresent=0;  $totals = array_fill(0, count($deptlist), ['totalProMin' => 0, 'totalEFF' => 0]);$grandTotal=0;$grandPresent=0; @endphp
    @foreach($period as $rowDates)
        <tr> 
          <td class="amount text-right" nowrap>{{ date('d-m-Y',strtotime($rowDates)) }}</td>    
          @foreach($deptlist as  $index => $rowFetch)
          
          @php 
         
              $fetchMaster=DB::table('daily_production_entry_masters')->select(DB::raw("sum(overall_efficiency) as overall_efficiency,sum(total_present) as total_present,sum(overall_sam) as overall_sam,sum(overall_output) as overall_output"))
         ->where('daily_pr_date',$rowDates)->where('dept_id',$rowFetch->line_id)->where('daily_production_entry_masters.sub_company_id',$sub_company_id)->first();
          
          @endphp
          
          
            @if($fetchMaster->overall_output!=0)
            <td class="amount text-right lineAmt{{ $rowFetch->line_id }}" lineAttr="{{ $rowFetch->line_id  }}" style="text-align:right">{{ indian_number_format_wd(round(($fetchMaster->overall_output ?? 0)*  ($fetchMaster->overall_sam ?? 0)))  }}</td> 
            @else
             <td class="amount text-right lineAmt{{ $rowFetch->line_id }}" lineAttr="{{ $rowFetch->line_id  }}" style="text-align:right">-</td> 
            
            @endif
            
            
             @if($fetchMaster->overall_efficiency!=0)
            
            <td class="amount text-right line{{ $rowFetch->line_id }}-eff" style="text-align:right">{{ number_format((float)($fetchMaster->overall_efficiency ?? 0), 2, '.', ''); }}</td>  
              
               @else
               
                <td class="amount text-right line{{ $rowFetch->line_id }}-eff" style="text-align:right">-</td>  
               
              @endif
              
           <td class="amount text-right present{{ $rowFetch->line_id }}-emp" style="text-align:right;display:none">{{ $fetchMaster->total_present; }}</td>  
           
           @php 
             $totalProMin=$totalProMin + round(($fetchMaster->overall_output ?? 0)*  ($fetchMaster->overall_sam ?? 0));
             $totalPresent=$totalPresent + $fetchMaster->total_present;
             
             
     
             if($fetchMaster->overall_output!=0 && $fetchMaster->overall_sam!=0)
             {
              $totals[$index]['totalProMin'] += $totalProMin;
              } else{
               $totals[$index]['totalProMin'] +=0;
              }
              
              if($totalProMin!=0 && $totalPresent!=0)
              {
              $totals[$index]['totalEFF'] += (($totalProMin / ($totalPresent * 480)) * 100);
              } else{
               $totals[$index]['totalEFF']+=0;
              }
              
             
            @endphp
            
          @endforeach
          
            @if($totalProMin!=0)
            <td class="amount text-right" style="text-align:right">{{ indian_number_format_wd($totalProMin); }}</td> 
            @else
              <td class="amount text-right" style="text-align:right">-</td> 
            @endif
            
            
          @if($totalProMin!=0 && $totalPresent!=0)

            <td class="amount text-right" style="text-align:right">{{   number_format((float)(($totalProMin / ($totalPresent * 480)) * 100), 2, '.', '') }}</td>  
            
            @else
            
              <td class="amount text-right" style="text-align:right">-</td>  
            @endif
        </tr>
        @php 
        
         $grandTotal=$grandTotal + $totalProMin;
         $grandPresent=$grandPresent + $totalPresent;
        
      
        
        
        $totalProMin=0;$totalEFF=0;$totalPresent=0; @endphp
          @endforeach
        </tbody>
    
        <tfoot>
         
        <tr>
        <td colspan="1" style="text-align:right;font-weight:bold">Total</td>    
        
       @foreach($deptlist as $rowFetch)
       
          <td class="amount text-right line{{ $rowFetch->line_id }}-total" style="text-align:right;font-weight:bold;"></td>  
          <td class="amount text-right line{{ $rowFetch->line_id }}-efficiency" style="text-align:right;font-weight:bold"></td>  
         
          
       @endforeach
       
       @php 
           if($grandTotal!=0 && $grandPresent!=0)
         {
         $grandEFF=(($grandTotal / ($grandPresent * 480)) * 100);
         }  else{
         $grandEFF=0;
         }
       
       @endphp
       
         <td class="amount text-right" style="text-align:right;font-weight:bold">{{  indian_number_format_wd($grandTotal) }}</td>  
          <td class="amount text-right" style="text-align:right;font-weight:bold">{{ number_format((float)$grandEFF, 2, '.', '')  }}%</td>  
        </tr>    
             
        </tfoot>

  
    </table>
    
       
    
   
    
    
</div>
</body>


<script>


function html_table_to_excel(type) {
    var data = document.getElementById('printsdiv');

    // Clone the original table to avoid modifying the original DOM
    var clonedData = data.cloneNode(true);

    // Loop through each table row
    clonedData.querySelectorAll('table#rptsum tbody tr').forEach(function(row) {
        // Find and remove the td you want to skip (for example, the third td in each row)
        var tdToSkip = row.querySelector('td:nth-child(4)'); // Adjust nth-child index as per your requirement

        if (tdToSkip) {
            tdToSkip.remove(); // Remove the td you want to skip from the cloned table
        }
    });

    // Convert the modified cloned table to Excel
    var file = XLSX.utils.table_to_book(clonedData, { sheet: "sheet1" });

    XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

    XLSX.writeFile(file, 'Unit-wise Efficiency.' + type);
}

// Attach the click event listener to the export button
const export_button = document.getElementById('export_button');

export_button.addEventListener('click', () => {
    html_table_to_excel('xlsx');
});





// $(document).ready(function() {
//     // Calculate totals for each department
//     var totals = [];
    
//     // Loop through each department column
//     $('#rptsum tbody tr').each(function() {
//         $(this).find('td').each(function(index) {
//             if (index > 0) { // Skip the first column (date column)
//                 var value = parseFloat($(this).text().replace(/,/g, '')) || 0;
//                 totals[index - 1] = (totals[index - 1] || 0) + value;
                
//             }
//         });
//     });
    
//     // Update the footer with totals
//     $('#rptsum tfoot tr td').each(function(index) {
//         if (index > 0) { // Skip the first column (label column)
        
//          if (!$(this).hasClass('notConsider')) {
//             $(this).text(totals[index - 1].toLocaleString()); // Format totals as needed
//          }
            
//         }
//     });
// });




$(document).ready(function () {
    var totalProduced = {};
    var totalEfficiency = {};
    var count = {};
	var totalPresent={};

    $('#rptsum tbody tr').each(function () {
        $(this).find('td').each(function () {
            var deptId = $(this).attr('lineAttr');
            if (deptId) {
                if (!totalProduced[deptId]) {
                    totalProduced[deptId] = 0;
                    totalEfficiency[deptId] = 0;
					totalPresent[deptId] = 0;
                    count[deptId] = 0;
                }

                var produced = parseFloat($(this).closest('tr').find('td.lineAmt' + deptId).text().replace(/,/g, '')) || 0;
                var efficiency = parseFloat($(this).closest('tr').find('td.line' + deptId + '-eff').text()) || 0;
				 var present = parseFloat($(this).closest('tr').find('td.present' + deptId + '-emp').text()) || 0;

                totalProduced[deptId] += produced;
                totalEfficiency[deptId] += efficiency;
				totalPresent[deptId]+=present;
				
				
                count[deptId]++;
            }
        });
    });

    for (var deptId in totalProduced) {
        var avgEfficiency = count[deptId] > 0 ? (((totalProduced[deptId] / (totalPresent[deptId] * 480)) * (100))).toFixed(2) : 0;
        
        
        if(totalProduced[deptId]!='0')
       {
        $('#rptsum tfoot td.line' + deptId + '-total').text(totalProduced[deptId].toLocaleString('en-IN'));
       } else{
           
            $('#rptsum tfoot td.line' + deptId + '-total').text('-');
       }
        
        
        if(!isNaN(avgEfficiency)){
        
        $('#rptsum tfoot td.line' + deptId + '-efficiency').text(avgEfficiency + '%');
        } else{
            
             $('#rptsum tfoot td.line' + deptId + '-efficiency').text('-');
        }
        
        
    }
});
        
     
        

</script>


</html>
