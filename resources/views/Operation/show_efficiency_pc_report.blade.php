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
	<style>

th.asc::after {
  content: " \2191"; /* Up arrow */
}

th.desc::after {
  content: " \2193"; /* Down arrow */
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
        <h3 class="inline-heading">Efficiency Detail Report  - {{ date("M-Y", strtotime($fdate)) }}</h3>
  
    </div>
</div>

<div class="row">
    
          <input type="text" id="searchInput" placeholder="Search by Operator" class="search-input ml-4">
          <button id="export_button">Export</button>
         <a href="/get_unitwise_efficiency" ><button id="back_button" >Back</button></a>
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
    
    
    <div class="modal fade" id="myModalOperation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
               
                <button type="button" class="closeBtn"  aria-label="Close" onclick="closeModalOperation()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body operations">
                <!-- Content goes here -->
               
            </div>
        
        </div>
    </div>
</div>








<div class="modal fade" id="myModalOperatorList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel"></h5>
                <button type="button" class="closeBtnOL"  aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body OL">
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
   url:"{{ route('load_efficiency_by_unit_pc') }}",
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
       
           var grand_total_1_19=[];
           var grand_total_20_39=[];
           var grand_total_40_54=[];
           var grand_total_55_69=[];
           var grand_total_70_84=[];
           var grand_total_above_85=[];
           var grand_total_above_60=[];

          
             var grand_total_1_to_19_AVG=0;
             var grand_total_20_to_39_AVG=0;
             var grand_total_40_to_54_AVG=0;
             var grand_total_55_to_69_AVG=0;
             var grand_total_70_to_84_AVG=0;
             var grand_total_above_85_AVG=0;
             var grand_total_above_60_AVG=0;
             
             
          var  grand_total_1_to_19_MTD=0;
          var  grand_total_20_to_39_MTD=0;
          var  grand_total_40_to_54_MTD=0;
          var  grand_total_55_to_69_MTD=0;
          var  grand_total_70_to_84_MTD=0;
          var  grand_total_above_85_MTD=0;
          var  grand_total_above_60_MTD=0;
           
       
       
       for(i=0;i<dept_id.length;i++)
       {
           
            var tableSelector = '#rptsum' + dept_id[i];
            var departmentId='#'+ dept_id[i];
            

           
  
      $(tableSelector + ' tbody tr td').each(function () {
            var columnIndex = $(this).index() + 1;
            var columnTotal_1_19 = 0;
           var columnTotal_20_39 = 0;  
           var columnTotal_40_54 = 0;   
           var columnTotal_55_69=0;
           var columnTotal_70_84=0;
           var columnTotal_above_85=0;
           var columnTotal_above_60=0;
           
            // Only sum cells that do not belong to a category-total or other total class
            $(tableSelector + ' tbody  td:nth-child(' + (columnIndex) + ')').each(function () {
                var eff = $(this).text() ? parseFloat($(this).text()) : 0;
                
                
                  if (!isNaN(eff) && eff >= 1 && eff < 20) {
                    columnTotal_1_19++; // Increment count if efficiency is within range
                    $(this).css('background-color', '#e01414');
                    }
                    if (!isNaN(eff) && eff >= 20 && eff < 40) {
                   columnTotal_20_39++;
                    $(this).css('background-color', '#ef3232');
                  }
                       if (!isNaN(eff) && eff >= 40 && eff < 55) {
                   columnTotal_40_54++;
                    $(this).css('background-color', '#f7c560');
                  }
                   if (!isNaN(eff) && eff >= 60) {
                   columnTotal_above_60++;
                    
                  } 
                  
                  if (!isNaN(eff) && eff >= 55 && eff < 70) {
                   columnTotal_55_69++;
                    $(this).css('background-color', '#f8fc2d');
                  }
                  
                  if (!isNaN(eff) && eff >= 70 && eff < 85) {
                   columnTotal_70_84++;
                    $(this).css('background-color', '#1594ef');
                  } 
                  
                   if (!isNaN(eff) && eff >= 85) {
                   columnTotal_above_85++;
                    $(this).css('background-color', '#0ba01f');
                  } 
                  
                
                //columnTotal += eff;
					//console.log($(this).html());
					
				
            });
            // Display column total in the footer
           $(tableSelector + ' tfoot  td.efficiency-count-1-to-19:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_1_19);
           $(tableSelector + ' tfoot  td.efficiency-count-20-to-39:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_20_39);
           $(tableSelector + ' tfoot  td.efficiency-count-40-to-54:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_40_54);
           $(tableSelector + ' tfoot  td.efficiency-count-55-to-69:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_55_69);
           $(tableSelector + ' tfoot  td.efficiency-count-70-to-84:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_70_84);
           $(tableSelector + ' tfoot  td.efficiency-count-above-85:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_above_85);
           $(tableSelector + ' tfoot  td.efficiency-count-above-60:nth-child(' + (columnIndex) + ')').not('.notConsider').text(columnTotal_above_60); 
           
        
            
              
           
          
           
        }); 
        
        
        
      $(tableSelector + ' tbody tr').each(function () {
            var columnIndex = $(this).index() + 1;
                    
                     var total_1_19=$(tableSelector + ' tfoot  td.efficiency-count-1-to-19:nth-child(' + (columnIndex) + ')').text();
                     var total_20_39=$(tableSelector + ' tfoot  td.efficiency-count-20-to-39:nth-child(' + (columnIndex) + ')').text(); 
                      var total_40_54=$(tableSelector + ' tfoot  td.efficiency-count-40-to-54:nth-child(' + (columnIndex) + ')').text(); 
                      var total_55_69=$(tableSelector + ' tfoot  td.efficiency-count-55-to-69:nth-child(' + (columnIndex) + ')').text();   
                      var total_70_84=$(tableSelector + ' tfoot  td.efficiency-count-70-to-84:nth-child(' + (columnIndex) + ')').text();   
                      var total_above_85=$(tableSelector + ' tfoot  td.efficiency-count-above-85:nth-child(' + (columnIndex) + ')').text(); 
                      var total_above_60=$(tableSelector + ' tfoot  td.efficiency-count-above-60:nth-child(' + (columnIndex) + ')').text();    
                      
                      
         
                            if (typeof grand_total_1_19[columnIndex] !== 'number') { grand_total_1_19[columnIndex] = 0; }
                            var parsedValue = Number(total_1_19); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_1_19[columnIndex] += parseFloat(total_1_19);
                            
                             if (typeof grand_total_20_39[columnIndex] !== 'number') { grand_total_20_39[columnIndex] = 0; }
                            var parsedValue = Number(total_20_39); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_20_39[columnIndex] += parseFloat(total_20_39);     
                            
                             if (typeof grand_total_40_54[columnIndex] !== 'number') { grand_total_40_54[columnIndex] = 0; }
                            var parsedValue = Number(total_40_54); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_40_54[columnIndex] += parseFloat(total_40_54);     
                            
                             if (typeof grand_total_55_69[columnIndex] !== 'number') { grand_total_55_69[columnIndex] = 0; }
                            var parsedValue = Number(total_55_69); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_55_69[columnIndex] += parseFloat(total_55_69);  
                            
                            if (typeof grand_total_70_84[columnIndex] !== 'number') { grand_total_70_84[columnIndex] = 0; }
                            var parsedValue = Number(total_70_84); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_70_84[columnIndex] += parseFloat(total_70_84); 
                            
                             if (typeof grand_total_above_85[columnIndex] !== 'number') { grand_total_above_85[columnIndex] = 0; }
                            var parsedValue = Number(total_above_85); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_above_85[columnIndex] += parseFloat(total_above_85); 
                          
                            if (typeof grand_total_above_60[columnIndex] !== 'number') { grand_total_above_60[columnIndex] = 0; }
                            var parsedValue = Number(total_above_60); if (isNaN(parsedValue)) { parsedValue = 0; }
                            grand_total_above_60[columnIndex] += parseFloat(total_above_60);
                            
                            
                            
                            
                           
                            
                     
        
           
        }); 

     
 
        //AVG
        
        
     var countEfficiency1to19 = 0;
    var countEfficiency20to39 = 0;
    var countEfficiency40to54=0;
     var countEfficiency55to69=0;
    var countEfficiency70to84=0;
     var countEfficiencyabove_85=0; 
    var countEfficiencyabove_60=0;    
         
         
  $(tableSelector + ' tbody tr').each(function () {
      
      
             
            
             var TOTEFF =  $(this).find('td.TOTEFF').text().trim();  // Get current efficiency text
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
  
  
            
      
     $(departmentId +'1_to_19').text(countEfficiency1to19);
     $(departmentId +'20_to_39').text(countEfficiency20to39);
     $(departmentId +'40_to_54').text(countEfficiency40to54);   
     $(departmentId +'55_to_69').text(countEfficiency55to69);    
     $(departmentId +'70_84').text(countEfficiency70to84);  
     $(departmentId +'above_85').text(countEfficiencyabove_85);  
     $(departmentId +'above_60').text(countEfficiencyabove_60); 
     
     
     
            var total_1_to_19_avg=$(departmentId +'1_to_19').text();
            var total_20_to_39_avg=$(departmentId +'20_to_39').text();
            var total_40_to_54_avg=$(departmentId +'40_to_54').text();
            var total_55_to_69_avg=$(departmentId +'55_to_69').text();
            var total_70_to_84_avg=$(departmentId +'70_84').text();
            var total_above_85_avg=$(departmentId +'above_85').text(); 
            var total_above_60_avg=$(departmentId +'above_60').text(); 
        
        
           grand_total_1_to_19_AVG += parseFloat(total_1_to_19_avg);
           grand_total_20_to_39_AVG += parseFloat(total_20_to_39_avg);
           grand_total_40_to_54_AVG += parseFloat(total_40_to_54_avg);
           grand_total_55_to_69_AVG += parseFloat(total_55_to_69_avg);
           grand_total_70_to_84_AVG += parseFloat(total_70_to_84_avg);
           grand_total_above_85_AVG += parseFloat(total_above_85_avg);
           grand_total_above_60_AVG += parseFloat(total_above_60_avg);
        
        //MTD
        
        
        
            var countEfficiency1to19 = 0;
    var countEfficiency20to39 = 0;
    var countEfficiency40to54=0;
     var countEfficiency55to69=0;
    var countEfficiency70to84=0;
     var countEfficiencyabove_85=0; 
    var countEfficiencyabove_60=0;    
         
         
  $(tableSelector + ' tbody tr').each(function () {
      
             
            
             var TOTEFF =  $(this).find('td.TOTEFF_MTD').text().trim();  // Get current efficiency text
           var currentEFF = parseFloat(TOTEFF.replace(/,/g, '')); // Parse efficiency as float, remove commas 
          
          
           if(currentEFF >= 1 && currentEFF < 20) {
            countEfficiency1to19++;
            $(this).find('td.TOTEFF_MTD').css('background-color', '#e01414');
            
        }  if (currentEFF >= 20 && currentEFF < 40) {
            countEfficiency20to39++;
             $(this).find('td.TOTEFF_MTD').css('background-color', '#ef3232');   
        }
          if (currentEFF >= 40 && currentEFF < 55) {
            countEfficiency40to54++;
             $(this).find('td.TOTEFF_MTD').css('background-color', '#f7c560');   
        }
         if (currentEFF >= 60) {
            countEfficiencyabove_60++;
            
        }
         if (currentEFF >= 55 && currentEFF < 70) {
            countEfficiency55to69++;
              $(this).find('td.TOTEFF_MTD').css('background-color', '#f8fc2d');   
        }  if (currentEFF >= 70 && currentEFF < 85) {
            countEfficiency70to84++;
             $(this).find('td.TOTEFF_MTD').css('background-color', '#1594ef');   
        }  if (currentEFF >= 85) {
            countEfficiencyabove_85++;
              $(this).find('td.TOTEFF_MTD').css('background-color', '#0ba01f');   
        } 
      
      
  }); 
  
  
            
      
     $(departmentId +'1_to_19_MTD').text(countEfficiency1to19);
     $(departmentId +'20_to_39_MTD').text(countEfficiency20to39);
     $(departmentId +'40_to_54_MTD').text(countEfficiency40to54);   
     $(departmentId +'55_to_69_MTD').text(countEfficiency55to69);    
     $(departmentId +'70_84_MTD').text(countEfficiency70to84);  
     $(departmentId +'above_85_MTD').text(countEfficiencyabove_85);  
     $(departmentId +'above_60_MTD').text(countEfficiencyabove_60); 
        
        
        
            var total_1_to_19_MTD=$(departmentId +'1_to_19_MTD').text();
            var total_20_to_39_MTD=$(departmentId +'20_to_39_MTD').text();
            var total_40_to_54_MTD=$(departmentId +'40_to_54_MTD').text();
            var total_55_to_69_MTD=$(departmentId +'55_to_69_MTD').text();
            var total_70_to_84_MTD=$(departmentId +'70_84_MTD').text();
            var total_above_85_MTD=$(departmentId +'above_85_MTD').text(); 
            var total_above_60_MTD=$(departmentId +'above_60_MTD').text(); 
        
        
           grand_total_1_to_19_MTD += parseFloat(total_1_to_19_MTD);
           grand_total_20_to_39_MTD += parseFloat(total_20_to_39_MTD);
           grand_total_40_to_54_MTD += parseFloat(total_40_to_54_MTD);
           grand_total_55_to_69_MTD += parseFloat(total_55_to_69_MTD);
           grand_total_70_to_84_MTD += parseFloat(total_70_to_84_MTD);
           grand_total_above_85_MTD += parseFloat(total_above_85_MTD);
           grand_total_above_60_MTD += parseFloat(total_above_60_MTD);
        
            
          
           
       }
       
                
      $('#grandTable tfoot tr td').each(function () {
            var columnIndex = $(this).index() + 1;
                 
           $('#grandTable tfoot  td.efficiency-count-1-to-19-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_1_19[columnIndex]);
           $('#grandTable tfoot  td.efficiency-count-20-to-39-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_20_39[columnIndex]);
           $('#grandTable tfoot  td.efficiency-count-40-to-54-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_40_54[columnIndex]);
          $('#grandTable tfoot  td.efficiency-count-55-to-69-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_55_69[columnIndex]);  
          $('#grandTable tfoot  td.efficiency-count-70-to-84-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_70_84[columnIndex]);  
          $('#grandTable tfoot  td.efficiency-count-above-85-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_above_85[columnIndex]); 
          $('#grandTable tfoot  td.efficiency-count-above-60-grand:nth-child(' + (columnIndex) + ')').not('.notConsider').text(grand_total_above_60[columnIndex]); 
           
           
        }); 
        
            $('#grandTable tfoot tr').each(function () {
            var columnIndex = $(this).index() + 1;
            
            
            //Avg
            
              $('#grand-avg-1-to-19').text(grand_total_1_to_19_AVG);
              $('#grand-avg-20-to-39').text(grand_total_20_to_39_AVG);
              $('#grand-avg-40-to-54').text(grand_total_40_to_54_AVG);
              $('#grand-avg-55-to-69').text(grand_total_55_to_69_AVG);
              $('#grand-avg-70-to-84').text(grand_total_70_to_84_AVG);
              $('#grand-avg-above-85').text(grand_total_above_85_AVG);
              $('#grand-avg-above-60').text(grand_total_above_60_AVG);
              
              
             //MTD
             
             $('#grand-mtd-1-to-19').text(grand_total_1_to_19_MTD);
             $('#grand-mtd-20-to-39').text(grand_total_20_to_39_MTD);
             $('#grand-mtd-40-to-54').text(grand_total_40_to_54_MTD);
             $('#grand-mtd-55-to-69').text(grand_total_55_to_69_MTD);
             $('#grand-mtd-70-to-84').text(grand_total_70_to_84_MTD);
             $('#grand-mtd-above-85').text(grand_total_above_85_MTD);
             $('#grand-mtd-above-60').text(grand_total_above_60_MTD);
  
           
        }); 

        
      
  
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


  

     
 


function showOperatorsInRangeMultiple(minRange, maxRange, Rangedate, dept_id, fromDate,toDate,flag,empArray){
    
 
    
  $.ajax({
type: "POST",
url: "{{ route('get_range_wise_operators') }}",
data:{minRange:minRange,maxRange:maxRange,Rangedate:Rangedate,dept_id:dept_id,fromDate:fromDate,toDate:toDate,flag:flag,empArray:empArray,"_token":"{{ csrf_token() }}"},
success: function(data){
 
  $(".modal-body").html(data.html);
 $('#myModal').fadeIn();

}
});  
    
    
}

function showOperatorsEffDetails(fromDate,toDate,employeeCode,dept_id){

    
  $.ajax({
type: "POST",
url: "{{ route('get_eff_datewise_operators') }}",
data:{fromDate:fromDate,toDate:toDate,employeeCode:employeeCode,dept_id:dept_id,"_token":"{{ csrf_token() }}"},
success: function(data){
 
  $(".OL").html(data.html);
 $('#myModalOperatorList').fadeIn();

}
});  
    
    
}






function closeModal(){
    
     $('#myModal').fadeOut();
     $('#myModalOperatorList').fadeOut();
    
}

function closeModalOperation(){
    
     $('#myModalOperation').fadeOut();
    
}


function get_operation_detail(employeeCode,productionDate,deptId){
    
//alert('employeeCode:'+employeeCode+'productionDate:'+productionDate+'deptId:'+deptId);


  $.ajax({
type: "POST",
url: "{{ route('get_date_wise_operation_detail_pcs') }}",
data:{employeeCode:employeeCode,productionDate:productionDate,deptId:deptId,"_token":"{{ csrf_token() }}"},
success: function(data){
 
  $(".operations").html(data.html);
 $('#myModalOperation').fadeIn();

}
});  


}
</script>



<script>

    $(document).ready(function() {
            $('#rptsum th').on('click', function() {
                var $table = $('#rptsum');
                var index = $(this).index();
                var type = $(this).data('type');
                var rows = $table.find('tbody tr').get();

                rows.sort(function(a, b) {
                    var A = $(a).children('td').eq(index).text();
                    var B = $(b).children('td').eq(index).text();

                    if (type === 'number') {
                        A = parseFloat(A) || 0;
                        B = parseFloat(B) || 0;
                    }

                    if (A < B) return -1;
                    if (A > B) return 1;
                    return 0;
                });

                $.each(rows, function(index, row) {
                    $table.children('tbody').append(row);
                });
            });
        });
</script>


</html>


















