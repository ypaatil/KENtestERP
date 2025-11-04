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

        table tr th {
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

        table tr th:first-child {
            z-index: 5;
              
        }

        table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #133b5c;
            color: rgb(241, 245, 179);
        }
        
        
             td:nth-of-type(2){
        position: -webkit-sticky;
        position: sticky;
        left: 250px;
        z-index: 2;
        background-color: #133b5c;
         color: rgb(241, 245, 179);
        }
        
        
        /*    td:nth-of-type(3){*/
        /*position: -webkit-sticky;*/
        /*position: sticky;*/
        /*left: 418px;*/
        /*z-index: 2;*/
        /*background-color: #133b5c;*/
        /* color: rgb(241, 245, 179);*/
        /*}*/
        
     
       

      thead tr th:nth-of-type(2){
        z-index: 5;
        left:250px;
        } 
        
        /* thead  th:nth-of-type(3){*/
        /*z-index: 5;*/
        /*left:420px;*/
        /*} */
        
       
        
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
         <a href="/get_unitwise_pl_register" ><button id="back_button" >Back</button></a>
</div>


<div id="efficiency_data"></div>

<div id="loading"></div>  


</div>

<input type="hidden" id="fdate" value="{{ $fdate }}">
<input type="hidden" id="tdate" value="{{ $tdate }}">
<input type="hidden" id="empArray" value="">


    
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
   url:"{{ route('load_pl_register_by_unit') }}",
   method:"POST",
   data:{employeeCode:employeeCode,fromDate:fromDate,toDate:toDate,dept_id:dept_id,sub_company_id:sub_company_id,"_token":"{{ csrf_token(); }}"},
   success:function(data)
   {
       $('#loading').hide();   
   // $('#load_more_button').remove();
    $('#efficiency_data').append(data.html);
    $('#empArray').val(data.empArrayList); 
    
   },
    complete:function()
   {
       
    

                var GRANDPROMIN=0;
                var GRANDEARN=0;
                var GRANDPL=0;
 
         $('#rptsum tbody tr').each(function () {
            var columnIndex = $(this).index() + 1;
            var columnTotal = 0;
            var CountIndex=0;
         

            $('#rptsum tbody  td:nth-child(' + (columnIndex) + ')').each(function () {
                var amount = $(this).text().replace(/,/g, '') ? parseFloat($(this).text().replace(/,/g, '')) : 0;
                
                 if(!isNaN(amount)) {
                        columnTotal += amount;
                        CountIndex++;
                    }
					
            });
            
            console.log(columnTotal);
            
            

                 var TotalPROMIN = 0;  var TotalPROEARN = 0;  var TotalPL = 0; var columnTotalNEW=0;
                
                    $(this).find('td.amount').each(function (index) {
                    var amount = parseFloat($(this).text().replace(/,/g, '')) || 0;
                    if (!isNaN(amount)) {
                    // Sum for the column index
                    if (index + 1 === columnIndex) {
                    columnTotalNEW += amount;
                    }
                    
                    // Add to appropriate total based on column class
                    if ($(this).hasClass('PROM')) {
                    TotalPROMIN += amount;
                    } else if ($(this).hasClass('PROEARN')) {
                    TotalPROEARN += amount;
                    } else if ($(this).hasClass('PL')) {
                    TotalPL += amount;
                    }
                    }
                    });
                
                
               $(this).find('td.TOTALPL').not('.notConsider').text(TotalPL.toLocaleString('en-IN'));
               $(this).find('td.TOTALEARN').not('.notConsider').text(TotalPROEARN.toLocaleString('en-IN'));
               $(this).find('td.TOTALPROMIN').not('.notConsider').text(TotalPROMIN.toLocaleString('en-IN'));
          
        
              $('#rptsum tfoot  td:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal.toLocaleString('en-IN'));
              
     
              
              
               if(!isNaN(parseFloat($(this).find('td.TOTALPROMIN').not('.notConsider').text()))) {
          GRANDPROMIN+=parseFloat($(this).find('td.TOTALPROMIN').not('.notConsider').text().replace(/,/g, ''));
               } else{
                   
                   GRANDPROMIN+=0;
                   
               }
           if(!isNaN(parseFloat($(this).find('td.TOTALEARN').not('.notConsider').text()))) {
          GRANDEARN+=parseFloat($(this).find('td.TOTALEARN').not('.notConsider').text().replace(/,/g, ''));
           } else{
               
                GRANDEARN+=0;
           }
           
             if(!isNaN(parseFloat($(this).find('td.TOTALPL').not('.notConsider').text()))) 
             {
            GRANDPL+=parseFloat($(this).find('td.TOTALPL').not('.notConsider').text().replace(/,/g, ''));  
             } else{
                 
                 GRANDPL+=0;
             }
            
               
        }); 
        
        $('#rptsum tfoot  td.GRANDPROMIN').not('.notConsider').text(GRANDPROMIN.toLocaleString('en-IN'));
        $('#rptsum tfoot  td.GRANDEARN').not('.notConsider').text(GRANDEARN.toLocaleString('en-IN'));
        $('#rptsum tfoot  td.GRANDPL').not('.notConsider').text(GRANDPL.toLocaleString('en-IN'));
          
  
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




</html>