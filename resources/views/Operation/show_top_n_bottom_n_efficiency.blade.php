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
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
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
            overflow: hidden;
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
 <script src="{{URL::asset('assets/js/tableSort.js')}}"></script> 
</head>
<body>
<div class="container" id="printsdiv">
    
<div class="row">
    <div class="left-buttons">
        <button id="export_button">Export</button>
    </div>
      <div class="centerDiv">
        <p>Efficiency Report</p>
         <p> {{ $sub_fetch->sub_company_name }}</p> 
        <p>From Date:  {{ date('d/m/Y',strtotime($fromDate)) }} To Date:  {{ date('d/m/Y',strtotime($toDate)) }}</p> 
        
         <p>{{ implode(', ', $deptArr); }}</p> 
         
        
    </div>
    <div class="right-button">
        <a href="/get_top_n_bottom_n"><button id="back_button">Back</button></a>
    </div>
</div>



<div class="row">
    <div class="col-md-4">
    <table id="rptsum">
        <thead>
         <tr>
               <th  style="text-align:center;background-color:#87c643" colspan="4">TOP {{  $top_n }} Operators</th>
           </tr>        
          <tr>
             <th  style="text-align:center">S.No.</th>
             <!--<th  style="text-align:center">Line</th>  -->
             <th  style="text-align:center">Operator</th>
           <th  style="text-align:center">Efficiency%</th>
      
        </tr>
       
        </thead>
        <tbody>
              
        @php  $no=1; $sum=[]; $cureentEmployee=null; @endphp
        
         @foreach($topList as $row)
    
        <tr> 
            <td class="text-right">{{ $no++ }}</td> 
           {{-- <td class="text-right">{{ $row->line_name }}</td>  --}}      
           <td class="text-right">{{  $row->fullName }}</td>    
            <td class="EFFICIENCY text-right" style="text-align:right">{{  number_format((float)($row->avg_efficiency ?? 0), 2, '.', '') }} </td>    
        </tr>
          
      @endforeach
  
        </tbody>

    </table>
      </div>
      <div class="col-md-4">
    <table id="rptsum">
        <thead>
               <tr>
               <th  style="text-align:center;background-color:#f70202" colspan="4">BOTTOM {{  $bottom_n }} Operators</th>
           </tr>  
          <tr>
             <th  style="text-align:center">S.No.</th>
             <!--<th  style="text-align:center">Line</th>  -->
             <th  style="text-align:center">Operator</th>
           <th  style="text-align:center">Efficiency%</th>
      
        </tr>
       
        </thead>
        <tbody>
         @php
         
 
         

     
         $no1=1; $sum=[]; $cureentEmployee=null; @endphp
         @foreach($bottomList as $index => $rowBottom)
         
        <tr> 
            <td class="text-right">{{ $no1++ }}</td> 
          {{--  <td class="text-right">{{ $rowBottom->line_name }}</td> --}}      
           <td class="text-right">{{ $rowBottom->fullName }}</td>    
            <td class="text-right" style="text-align:right">{{  number_format((float)($rowBottom->avg_efficiency_bottom ?? 0), 2, '.', '') }}</td>    
        </tr>
          
      @endforeach
  
        </tbody>

    </table>
      </div>
     </div>
      <br>
      

   
    
    
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
        var tdToSkip = row.querySelector('td:nth-child(6)'); // Adjust nth-child index as per your requirement

        if (tdToSkip) {
            tdToSkip.remove(); // Remove the td you want to skip from the cloned table
        }
    });

    // Convert the modified cloned table to Excel
    var file = XLSX.utils.table_to_book(clonedData, { sheet: "sheet1" });

    XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

    XLSX.writeFile(file, 'efficiency.' + type);
}

// Attach the click event listener to the export button
const export_button = document.getElementById('export_button');

export_button.addEventListener('click', () => {
    html_table_to_excel('xlsx');
});



         
 


</script>

</html>
