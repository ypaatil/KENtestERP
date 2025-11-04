<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ken</title>
	<style>
	
     * {
            margin: 0px;
            padding: 0;
           font-family: arial, sans-serif;
        }

        .heading {
            background-color: #133b5c;
            box-shadow: 0px 1px 2px #232f3e;
            text-align: center;
        }

        h3 {
            color: coral;
            font-weight: bold;
            background: transparent;
            padding: 7px;
        }

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
        }

        table th {
            position: sticky;
            top: 0;
            left: 0;
            background-color: #133b5c;
            color: rgb(241, 245, 179);
            text-align: center;
            font-weight: normal;
           font-size: 14px; /* Default font size */
            outline: 0.7px solid black;
            border: 1.5px solid black;
            z-index: 2;
        }

        table th:first-child {
            z-index: 3;
        }

        table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #133b5c;
            color: rgb(241, 245, 179);
        }
           tfoot td {
            border: 1px solid black;
            text-align: right;
            font-weight: bold;
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

/* CSS for the buttons */
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

   #back_button {
    padding: 10px 20px; /* Padding for the buttons */
    margin: 0 5px; /* Margin between buttons */
    background-color: #ff7f50; /* Button background color */
    color: #fff; /* Button text color */
    border: none; /* Remove button border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s; /* Smooth transition for background color */  
            position: absolute;
            top: 52px; /* Adjust as needed */
            right: 40px; /* Adjust as needed */
            text-decoration:none;
        }
        
        
        
/* styles.css */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 999; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */

}

.closeBtn {
    position: sticky;
            top: 0;
            left: 0;
    color: #aaa;
    float: right;
    font-size: 15px;
    font-weight: bold;
}

.closeBtn:hover,
.closeBtn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
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


#loading {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 100;
  width: 100vw;
  height: 100vh;
  background-color: rgba(192, 192, 192, 0.5);
  background-image: url("https://ken.ewebtrades.com/assets/images/gifLoading.gif");
  background-repeat: no-repeat;
  background-position: center;
}
	</style>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="{{URL::asset('assets/js/exporttoexcel.js')}}"></script>  
 <script src="{{URL::asset('assets/js/tableSort.js')}}"></script> 	
	
	
</head>

<body>
    
    <div id="printsdiv">
 <div class="heading">
    <div class="header-content">
        <h3 class="inline-heading">Profit / Loss (in ₹) Report  - {{ date("M-Y", strtotime($fdate)) }}</h3>
  
    </div>
</div>

<div class="row">
    
          <input type="text" id="searchInput" placeholder="Search by Operator" class="search-input ml-4">
          <button id="export_button">Export</button>
         <a href="/get_unitwise_pl" ><button id="back_button" >Back</button></a>
</div>


<div id="efficiency_data"></div>

<div id="loading"></div>  


</div>

<input type="hidden" id="fdate" value="{{ $fdate }}">
<input type="hidden" id="tdate" value="{{ $tdate }}">
<input type="hidden" id="empArray" value="">
<input type="hidden" id="avgCounts" value="">
<input type="hidden" id="TotalSum" value="">


    
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Rangewise Operators</h5>
                <button type="button" class="closeBtn"  aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content goes here -->
               
            </div>
            <div class="modal-footer mt-2">
                <button type="button" class="btn btn-secondary"  onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>
    
</body>

<script>

$(document).ready(function(){
    
  load_data();
    
 function load_data()
 {
     
     
     var employeeCode = <?php echo json_encode($employeeCode); ?>;
      var fromDate=$('#fdate').val();
      var toDate=$('#tdate').val();
      var dept_id=<?php echo json_encode($dept_id); ?>;
       var sub_company_id = <?php echo $sub_company_id; ?>
     
     
  $.ajax({
   url:"{{ route('load_pl_by_unit') }}",
   method:"POST",
   data:{employeeCode:employeeCode,fromDate:fromDate,toDate:toDate,dept_id:dept_id,sub_company_id:sub_company_id,"_token":"{{ csrf_token(); }}"},
   success:function(data)
   {
       $('#loading').hide();   
   // $('#load_more_button').remove();
    $('#efficiency_data').append(data.html);
    $('#empArray').val(data.empArrayList); 
     $('#avgCounts').val(data.dailyAveragesCount); 
     $('#TotalSum').val(data.TotalSum);   
      console.log(data.TotalSum);
      
 
    
   },
    complete:function()
   {
       
          
       for(i=0;i<dept_id.length;i++)
       {
           
             var tableSelector = '#rptsum' + dept_id[i];
             var departmentId='#'+ dept_id[i];
             var CountIndexForAvg=0;
 
         $(tableSelector + ' tbody tr').each(function () {
            var columnIndex = $(this).index() + 1;
            var columnTotal = 0;
            var CountIndex=0;
        

            $(tableSelector + ' tbody  td.amount:nth-child(' + (columnIndex) + ')').each(function () {
                var amount = parseFloat($(this).text().replace(/,/g, '')) ? parseFloat($(this).text().replace(/,/g, '')) : 0;
                
                 if(!isNaN(amount)) {
                        columnTotal += amount;
                        CountIndex++;
                   
                    }
					
            });
            
            
                         var TOTALSAM=0;
                         var countTd=0;
                         
                        $(this).find('td').not('.TOTALSAM').not('.TOTEFF').not('.TOTEFF_MTD').not('.TOTALMTDS').each(function(){
                        var value =  $(this).text().replace(/,/g, '') ? parseFloat($(this).text().replace(/,/g, '')) : 0;
                        if (!isNaN(value)) {
                        TOTALSAM += value; 
                        
                        if(value!=0)
                        {
                        countTd++;
                        }
                        
                        }
                        });
            
            
            
            if(TOTALSAM==0)
            {
             $(this).find('td.TOTALSAM').text('-');  
            } else{
                
                $(this).find('td.TOTALSAM').text(TOTALSAM.toLocaleString('en-IN'));  
            }
             
              if(TOTALSAM==0)
            {
             $(this).find('td.TOTEFF').text('-');   
            } else{
                
                 $(this).find('td.TOTEFF').text((TOTALSAM / countTd).toFixed(2));   
            }
             
             
           if(columnTotal==0)
            {
            $(tableSelector + ' tfoot  td:nth-child(' + (columnIndex) + ')').not('.notConsider').not('.grandTotAll').not('.grandTotAVG').not('.grandTotMTD').text('-');
            } else{
              $(tableSelector + ' tfoot  td:nth-child(' + (columnIndex) + ')').not('.notConsider').not('.grandTotAll').not('.grandTotAVG').not('.grandTotMTD').text(columnTotal.toLocaleString('en-IN'));    
                
            }
         
         
          //  $(tableSelector + ' tfoot  td.grandTotAVG:nth-child(' + (columnIndex) + ')').not('.notConsider').not('.grandTotMTD').text((columnTotal).toFixed(2));
     
                        if(columnTotal!=0)
                        {
                        CountIndexForAvg++;
                        } 
             
        }); 
        
             var columnTotals = 0;
             
         $(tableSelector + ' tbody tr td.TOTALSAM').each(function () {
        
                var amounts = parseFloat($(this).text().replace(/,/g, '')) ? parseFloat($(this).text().replace(/,/g, '')) : 0;
                
                 if(!isNaN(amounts)) {
                        columnTotals += amounts;
                       
                    }
             
        }); 
        
        
        
        
                     var columnTotals2 = 0;
             
         $(tableSelector + ' tbody tr td.TOTALMTDS').each(function () {
        
                var amounts2 = parseFloat($(this).text().replace(/,/g, '')) ? parseFloat($(this).text().replace(/,/g, '')) : 0;
                
                 if(!isNaN(amounts2)) {
                        columnTotals2 += amounts2;
                       
                    }
             
        }); 
        
        
        
        
        
        
        
        
        
            var avgCounts=$('#avgCounts').val();
            var TotalSum=$('#TotalSum').val();
            
            console.log(avgCounts);
        
            
            $(tableSelector + ' tfoot  td.grandTotAll').not('.notConsider').not('.grandTotAVG').not('.grandTotMTD').text(columnTotals.toLocaleString('en-IN'));
            
            
              $(tableSelector + ' tfoot  td.grandTotMTDs').not('.notConsider').not('.grandTotAVG').not('.grandTotMTD').text(columnTotals2.toLocaleString('en-IN'));
            
            
            $(tableSelector + ' tfoot  td.grandTotAVG').not('.notConsider').not('.grandTotMTD').text((columnTotals/(CountIndexForAvg)).toFixed(2)); 
          
          
           $(tableSelector + ' tfoot  td.grandTotMTD').not('.grandTotMTDs').not('.notConsider').text((columnTotals2/(avgCounts)).toFixed(2));   
          
               
        
       }
        
  
   }
   
   
  })
 }

});

$(document).ready(function(){
    // Function to filter table rows based on input
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#rptsum tbody tr').filter(function() {
            $(this).toggle($(this).children('td:first').text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Rest of your existing code
    var result = [];
    $('#rptsum tr').each(function(){
        // Your existing code here
    });
    
    // Rest of your existing code
});   
    





        
//     $(function(){
//     $('.amount').each(function() {
//         var amountText = $(this).text();
//         // Check if amountText is a valid number
//         if (!isNaN(parseFloat(amountText))) {
//             $(this).text(indian_number_format(amountText));
//         }
//     });   
// });






function html_table_to_excel(type)
         {
            var data = document.getElementById('printsdiv');

            var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

            XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

            XLSX.writeFile(file, 'efficiencyDetail.' + type);
         }

         const export_button = document.getElementById('export_button');

         export_button.addEventListener('click', () =>  {
            html_table_to_excel('xlsx');
         });
         
         
         
// $(document).ready(function() {
//     // Function to calculate and update count of efficiencies between 1% to 19.99%
//     function updateEfficiencyCounts() {
//         $('.operator-row').each(function() {
//             var count1to19 = 0; // Initialize count for 1-19.99% efficiency range
//             $(this).find('.TOTEFF').each(function(index) {
//                 var efficiency = parseFloat($(this).text()); // Get efficiency value

//                 // Check if efficiency is between 1 and 19.99%
//                 if (!isNaN(efficiency) && efficiency >= 1 && efficiency < 20) {
//                     count1to19++; // Increment count if efficiency is within range
//                 }
                
//                 // Update the count for 1-19.99% in the corresponding date column
//                 $(`.efficiency-count-1-to-19:eq(${index})`).text(count1to19);
//             });
//         });
//     }

//     // Call the function initially
//     updateEfficiencyCounts();


   
// });


  

     
 



function closeModal(){
    
     $('#myModal').fadeOut();
    
}





</script>

<script>
function sortTable(n) {
  var table, tbody, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("rptsum");
  tbody = table.getElementsByTagName("tbody")[0];
  rows = tbody.getElementsByTagName("tr");
  switching = true;
  dir = "asc"; // Default direction

  // Clear previous sorting indicators
  var headers = table.getElementsByTagName("th");
  for (var j = 0; j < headers.length; j++) {
    headers[j].classList.remove("asc", "desc", "active");
  }
  
  // Set active and direction class on clicked header
  headers[n].classList.add("active");
  if (headers[n].classList.contains("asc")) {
    dir = "desc";
  } else {
    dir = "asc";
  }
  headers[n].classList.add(dir);

  while (switching) {
    switching = false;
    for (i = 0; i < rows.length - 1; i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("td")[n].innerText;
      y = rows[i + 1].getElementsByTagName("td")[n].innerText;

      let xValue = isNaN(x) ? x : parseFloat(x);
      let yValue = isNaN(y) ? y : parseFloat(y);

      if (dir == "asc") {
        if (xValue > yValue) {
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (xValue < yValue) {
          shouldSwitch = true;
          break;
        }
      }
    }

    if (shouldSwitch) {
      tbody.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount++;
    } else {
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
        headers[n].classList.remove("asc");
        headers[n].classList.add("desc");
      }
    }
  }
}













</script>


</html>