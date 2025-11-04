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

    
    
         #rptsum   th.sticky {
            position: sticky;
              top: 0;
            left: 0;
                z-index: 2;
            background-color: #133b5c;
            color: #ffffff;
        }
        
          #rptsum th.sticky {
            z-index: 3;
        }
        
          table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
         
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


.table-responsive {
    overflow-x: auto; /* Enables horizontal scrolling */
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on touch devices */
    margin-top: 20px; /* Optional: Add some space above the table */
}

#rptsum {
    min-width: 800px; /* Set a minimum width for the table */
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
        <span>Unitwise Efficiency Report Monthly</span>
    </div>
    <div class="right-button">
        <a onclick="history.back()"><button id="back_button">Back</button></a>
    </div>
</div>


@php $totalProduced=[]; $totalPresentss=[]; $totalProducedDepartmentWise=[]; $totalPresentDepartmentWise=[]; 
$grandTotalUnitWise=[];$totalPresentUnitWise=[];$AllUnitTotal=0;$AllUnitPresent=0;$AllUnitHorizontalTotal=0;$AllUnitPresentHorizontalTotal=0;  @endphp
    
       
 <div class="table-responsive">
    
   <table border="1" id="rptsum">
    <thead>
        <tr>
            <th rowspan="3" class="sticky">Month</th>
            @foreach($subCompanyList as $rowSubcompany)
            
            <th colspan="10">{{ $rowSubcompany->sub_company_name }}</th>
            
         @endforeach
         
          <th style="text-align:center" colspan="2">All Units</th>
      
        </tr>
        <tr>
           
            @foreach($subCompanyList as $rowSubcompany)
             @foreach($deptlist as  $rowFetch)
            <th colspan="2">{{ $rowFetch->dept_name }}</th>
             @endforeach
            <th colspan="2">Total</th>
          
           @endforeach
             <th colspan="2" style="text-align:center">Total</th>
        </tr>
        <tr>
          
     @php $i=0;$sub_company_array=[]; @endphp
     @foreach($subCompanyList as $rowSubcompany)
      @foreach($deptlist as  $rowFetch)
   <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Prod. Mins.</th>
   <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Eff.</th>
   @php $i++;
   
   $sub_company_array[]=$rowSubcompany->sub_company_id;
   @endphp
      @endforeach
    <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Prod. Mins.</th>
   <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Eff.</th>  
          @endforeach
          
            <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Prod. Mins.</th>
   <th style="background-color:#91d14f;font-weight: 700;color:#000;" nowrap>Eff.</th>  
        </tr>
    </thead>
    <tbody>
        
     @php $totalProMin=0;$totalEFF=0;$totalPresent=0;  $totals = array_fill(0, count($deptlist), ['totalProMin' => 0, 'totalEFF' => 0]); @endphp
  

  
         @php 
        
        $fetchMaster = DB::table('daily_production_entry_masters')
        ->select('daily_pr_date','dept_id','sub_company_id', 
         DB::raw("MONTH(daily_pr_date) as month"),
        DB::raw("
        sum(overall_efficiency) as overall_efficiency,
        sum(total_present) as total_present,
        ifnull(sum(overall_sam),0) as overall_sam,
        ifnull(sum(overall_output),0) as overall_output
        "))
        ->whereIn('sub_company_id',$sub_company_array)
        ->groupBy('daily_pr_date', 'dept_id', 'sub_company_id') 
        ->get();
        
        $producedMin = 0;
        $overall_efficiency = 0;
        $total_present = 0;

    
    foreach($fetchMaster as $record) {
    $dataMap[$record->dept_id][$record->sub_company_id][$record->month][] = [
        'producedMin' =>  round(($record->overall_output ?? 0) * ($record->overall_sam ?? 0)),
        'overall_efficiency' =>$record->overall_efficiency ?? 0,
        'total_present' =>$record->total_present ?? 0
    ];
 
    }
    
          
    @endphp
  
        
        
           @foreach($MonthList as $month)
        <tr>
            <td style="font-weight:bold;"><a href="/show_all_unitwise_efficiency_weekly/{{ $month->monthId }}/{{ $year }}">{{ $month->MonthName }}</a></td>
              @foreach($subCompanyList as $rowSubcompany)
              @foreach($deptlist  as  $index =>  $rowFetch)
                    @php $producedMin=0;$total_present=0; 
             if (isset($dataMap[$rowFetch->dept_id][$rowSubcompany->sub_company_id][$month->monthId])) {
        foreach($dataMap[$rowFetch->dept_id][$rowSubcompany->sub_company_id][$month->monthId] as $record) {
            $producedMin+= $record['producedMin'];
            $total_present+= $record['total_present']; 
            
        }
    }  
   @endphp
                 @if($producedMin!=0)
            <td class="amount text-right lineAmt{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}" lineAttr="{{ $rowFetch->dept_id  }}{{ $rowSubcompany->sub_company_id  }}"  style="text-align:right">{{ indian_number_format_wd(round(($producedMin ?? 0)))  }}</td> 
            @else
             <td class="amount text-right lineAmt{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}" lineAttr="{{ $rowFetch->dept_id  }}{{ $rowSubcompany->sub_company_id  }}"  style="text-align:right">-</td> 
            
            @endif
              
           
             @if($producedMin!=0)
            
            <td class="amount text-right line{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-eff" style="text-align:right">{{ number_format((float)((round(($producedMin ?? 0)) / ($total_present * 480)) * (100)), 2, '.', ''); }}</td>  
              
               @else
               
                <td class="amount text-right line{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-eff" style="text-align:right">-</td>  
               
              @endif
              
           <td class="amount text-right present{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-emp" style="text-align:right;display:none">{{ $total_present; }}</td>  
           
           
                   @php 
           
             
                     if(!isset($totalProduced[$rowSubcompany->sub_company_id][$month->monthId])) {
                        $totalProduced[$rowSubcompany->sub_company_id][$month->monthId] = 0;
                        $totalPresentss[$rowSubcompany->sub_company_id][$month->monthId] = 0;
                     }
                    $totalProduced[$rowSubcompany->sub_company_id][$month->monthId] += $producedMin;
                    $totalPresentss[$rowSubcompany->sub_company_id][$month->monthId] += $total_present;
                    
                    
                    
                           if(!isset($totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id])) {
                        $totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] = 0;
                        $totalPresentDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] = 0;
                     }
                    $totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] += $producedMin;
                    $totalPresentDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] += $total_present;
      
             
             
             $producedMin=0;$total_present=0;
            @endphp
           @endforeach
           
           
             @if($totalProduced[$rowSubcompany->sub_company_id][$month->monthId]!=0)
            <td class="amount text-right" style="text-align:right;">{{ $totalProduced[$rowSubcompany->sub_company_id][$month->monthId] != 0 ? indian_number_format_wd($totalProduced[$rowSubcompany->sub_company_id][$month->monthId]) : '-'  }}</td> 
            @else
              <td class="amount text-right" style="text-align:right;">-</td> 
            @endif
            
            
          @if($totalProduced[$rowSubcompany->sub_company_id][$month->monthId]!=0 && $totalPresentss[$rowSubcompany->sub_company_id][$month->monthId]!=0)

            <td class="amount text-right" style="text-align:right;">{{   number_format((float)(($totalProduced[$rowSubcompany->sub_company_id][$month->monthId] / ($totalPresentss[$rowSubcompany->sub_company_id][$month->monthId] * 480)) * 100), 2, '.', '') }}</td>  
            
            @else
            
              <td class="amount text-right" style="text-align:right;">-</td>  
            @endif
            
            @php
            
               if(!isset($totalProduced[$rowSubcompany->sub_company_id][$month->monthId])) {
                        $totalProduced[$rowSubcompany->sub_company_id][$month->monthId] = 0;
                       $totalPresentss[$rowSubcompany->sub_company_id][$month->monthId] = 0;
                     }
            
             $AllUnitHorizontalTotal+=$totalProduced[$rowSubcompany->sub_company_id][$month->monthId];
             $AllUnitPresentHorizontalTotal+= $totalPresentss[$rowSubcompany->sub_company_id][$month->monthId];
       
             
             @endphp
            
            @endforeach
            
            
         @if($AllUnitHorizontalTotal!=0)   
         <td class="amount text-right" style="text-align:right">{{ indian_number_format_wd($AllUnitHorizontalTotal); }}</td>  
         @else
         
          <td class="amount text-right" style="text-align:right">-</td>  
         @endif
         
          @if($AllUnitHorizontalTotal!=0 && $AllUnitPresentHorizontalTotal!=0)   
         <td class="amount text-right" style="text-align:right">{{  number_format((float)(($AllUnitHorizontalTotal / ($AllUnitPresentHorizontalTotal * 480)) * 100), 2, '.', '') }}</td> 
         @else
          <td class="amount text-right" style="text-align:right">-</td> 
         @endif
        </tr>
        @php
             $AllUnitHorizontalTotal=0;
             $AllUnitPresentHorizontalTotal= 0; @endphp
      @endforeach
           <tr>
            <td style="font-weight:bold">Total</td>
              @foreach($subCompanyList as $rowSubcompany)
              @foreach($deptlist as  $rowFetch)
              
              @if($totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id]!=0)
              
            <td class="amount text-right line{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-total" style="text-align:right;font-weight:bold">{{ indian_number_format_wd($totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id]) }}</td>
            
            @else
             <td class="amount text-right line{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-total" style="text-align:right;font-weight:bold">-</td>
            
            @endif
            
            
            @if($totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id]!=0 && $totalPresentDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id]!=0)
            <td class="amount text-right line{{ $rowFetch->dept_id }}{{ $rowSubcompany->sub_company_id  }}-efficiency" style="text-align:right;font-weight:bold">{{ number_format((float)(($totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] / ($totalPresentDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id] * 480)) * 100), 2, '.', '') }}</td>
            
            @else
            
             <td class="amount text-right;font-weight:bold">-</td>
            
            @endif
            
            @php
                if(!isset($grandTotalUnitWise[$rowSubcompany->sub_company_id])) {
                        $grandTotalUnitWise[$rowSubcompany->sub_company_id] = 0;
                        $totalPresentUnitWise[$rowSubcompany->sub_company_id] = 0;
                     }
            
             $grandTotalUnitWise[$rowSubcompany->sub_company_id]+=$totalProducedDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id];
             $totalPresentUnitWise[$rowSubcompany->sub_company_id]+=$totalPresentDepartmentWise[$rowFetch->dept_id][$rowSubcompany->sub_company_id];
            
            @endphp
            
           @endforeach
           
           
           
           
     
             @if(($grandTotalUnitWise[$rowSubcompany->sub_company_id])!=0)
           <td class="amount text-right" style="text-align:right;font-weight:bold">{{  indian_number_format_wd($grandTotalUnitWise[$rowSubcompany->sub_company_id]) }}</td> 
           @else
            <td class="amount text-right" style="text-align:right;font-weight:bold">-</td> 
           @endif
           

             @if($grandTotalUnitWise[$rowSubcompany->sub_company_id]!=0 && $totalPresentUnitWise[$rowSubcompany->sub_company_id]!=0)
            <td class="amount text-right" style="text-align:right;font-weight:bold">{{ number_format((float)(($grandTotalUnitWise[$rowSubcompany->sub_company_id] / ($totalPresentUnitWise[$rowSubcompany->sub_company_id] * 480)) * 100), 2, '.', '') }}%</td>
            
            @else
            
             <td class="amount text-right">-</td>
            
            @endif
          
          
          @php
            $AllUnitTotal+=$grandTotalUnitWise[$rowSubcompany->sub_company_id];
            $AllUnitPresent+=$totalPresentUnitWise[$rowSubcompany->sub_company_id];
            
            @endphp
            
            @endforeach
         
         
            @if($AllUnitTotal!=0)    
           <td class="amount text-right" style="text-align:right;font-weight:bold">{{ indian_number_format_wd($AllUnitTotal) }}</td>  
           @else
            <td class="amount text-right" style="text-align:right;font-weight:bold">-</td>  
           
           @endif
           
           @if($AllUnitTotal!=0  && $AllUnitPresent!=0)
          <td class="amount text-right" style="text-align:right;font-weight:bold">{{ number_format((float)(($AllUnitTotal / ($AllUnitPresent * 480)) * 100), 2, '.', '') }}%</td>    
          
          @else
           <td class="amount text-right" style="text-align:right;font-weight:bold">-</td>    
          @endif
          
          
        </tr>
    </tbody>
</table>
</div>
    
    
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

    XLSX.writeFile(file, 'Unit-wise Efficiency Monthly.' + type);
}

// Attach the click event listener to the export button
const export_button = document.getElementById('export_button');

export_button.addEventListener('click', () => {
    html_table_to_excel('xlsx');
});

  

</script>

</html>
