@extends('layouts.master') 
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
      /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f5f5f5;
    }
    tr:hover {
        background-color: #e9e9e9;
    }

    /* Responsive Table */
    @media screen and (max-width: 600px) {
        table {
            border-radius: 0;
        }
    }
  input[type="text"] {
    width: 100%;
    height: 100%;
    border: none;
    padding: 8px;
    box-sizing: border-box;
  }
  
  .dropdown {
    position: relative;
    display: inline-block;
  }
  
  .dropdown-content { 
    position: absolute;
    background-color: #f9f9f9;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 10; /* Change z-index to -1 */
    padding: 10px;
  }
  
  .dropdown-content a {
    color: black;
    padding: 6px 8px;
    text-decoration: none;
    display: block;
  }
  
  /*.dropdown-content a:hover {background-color: #f1f1f1;}*/
  
  /*.dropdown:hover .dropdown-content {display: block;}*/
  
  .dropdown input[type="checkbox"] {
    margin-right: 5px;
  }
  
  .hide{
      display:none;
  }


  .modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* Center modal vertically and horizontally */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Adjust width as needed */
    max-width: 500px; /* Maximum width */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  /* Close button */
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }


  /* Radio buttons */
  input[type="radio"] {
    margin-right: 5px;
  }

 
  #filterValueInput1,#filterValueInput2
  {
        border:1px solid gray; 
        height: 40px;
        margin: 10px;

  }
      
    .year-list {
      font-family: Arial, sans-serif;
    }
    
    .years {
      list-style: none;
      padding: 0;
    }
    
    .year {
      margin-bottom: 10px;
    }
    
    .months {
      list-style: none;
      padding: 0;
      margin-left: 20px;
    }
    
    .month {
      margin-bottom: 5px;
    }
    
    .days {
      list-style: none;
      padding: 0;
      margin-left: 20px;
    }
    
    .days label {
      margin-left: 5px;
    }
    
    input[type="checkbox"] {
      vertical-align: middle;
    }

    .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            margin: auto;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hide content when loading */
        .content {
            display: none;
        }
</style>
<body>
<div class="loader"></div>
<div class="card content" style="height:150vh;">
<div class="card-body" style="height:150vh;">
 
    <table class="table table-responsive" > 
      <thead>
        <tr>
            <th style="display: flex;">Order ID </th>
            <th class="sortable" data-column="customer_name">Customer Name</th>
            <th class="sortable" data-column="price">Price</th>
            <th class="sortable" data-column="date">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>1</td>
            <td>Bhikaji Kamble</td>
            <td>5000</td>
            <td>2024-01-03</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Bhikaji Kamble</td>
            <td>5200</td>
            <td>2023-01-03</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Bhikaji Kamble</td>
            <td>3000</td>
            <td>2023-02-03</td>
        </tr>
        <tr>
            <td>4</td>
            <td>Bhikaji Kamble</td>
            <td>2500</td>
            <td>2023-04-03</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Bhikaji Kamble</td>
            <td>4300</td>
            <td>2024-01-03</td>
        </tr>
        <tr>
            <td>6</td>
            <td>Bhikaji Kamble</td>
            <td>6520</td>
            <td>2023-12-04</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Bhikaji Kamble</td>
            <td>3265</td>
            <td>2023-08-03</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Bhikaji Kamble</td>
            <td>7854</td>
            <td>2023-02-05</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Bhikaji Kamble</td>
            <td>9875</td>
            <td>2023-01-06</td>
        </tr>
        <tr>
            <td>10</td>
            <td>Bhikaji Kamble</td>
            <td>3457</td>
            <td>2023-05-03</td>
        </tr>
        <tr>
            <td>11</td>
            <td>Bhikaji Kamble</td>
            <td>7548</td>
            <td>2024-02-03</td>
        </tr>
        <tr>
            <td>12</td>
            <td>Bhikaji Kamble</td>
            <td>9651</td>
            <td>2024-01-05</td>
        </tr>
        <!-- More table rows -->
      </tbody>
    </table>
    </div>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="row">
        <div class="col-md-11">  
             <h3>Custom Filter</h3>
        </div>
        <div class="col-md-1">  
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">  
            <select name="custom_filter" style="padding: 10px;margin-top: 11px;">
                <option value="1">Equal</option>
                <option value="2">Not Equal</option>
                <option value="3">Less Than</option>
                <option value="4">Less Than Or Equal</option>
                <option value="5">Greater Than</option>
                <option value="6">Greater Than Or Equal</option>
                <option value="7">Between</option> 
            </select> 
            <div class="row" style="margin-top: 11px;padding: 10px;">
                <div class="col-md-2"> 
                    <input type="radio" id="andRadio" name="operation" value="AND" checked>
                    <label for="andRadio">AND</label>
                 </div>
                <div class="col-md-2 ml-5"> 
                    <input type="radio" id="orRadio" name="operation" value="OR">
                    <label for="orRadio">OR</label>
                 </div>
            </div>
        </div>
        <div class="col-md-6"> 
            <input type="text" class="form-control" id="filterValueInput1" placeholder="Value 1">
            <input type="text" class="form-control" id="filterValueInput2"  placeholder="Value 2">
        </div>
    </div>
    <div class="col-md-12 text-right"> 
        <button class="btn btn-success" onclick="applyNumberFilter()" >OK</button>
        <button class="btn btn-danger" onclick="closeModal()">Cancel</button>
    </div>
  </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
      // Simulate page loading
        window.addEventListener('load', function () {
            // Hide the loader and show the content after a delay (simulating loading)
            setTimeout(function () {
                document.querySelector('.loader').style.display = 'none';
                document.querySelector('.content').style.display = 'block';
            }, 2000); // Adjust the delay as needed
        });
    function yearFunction(row) 
    { 
        $(row).closest(".year").find(".month").slideDown();
        if($(row).parent().parent(".year").find("input[type='checkbox']").is(':checked'))
        {   
            $(row).parent().parent().find(".months").slideUp();
        }
        else
        { 
            $(row).parent().find(".months").slideDown(); 
        }
    }
   
    
    function closeYearFunction(row)
    {
         $(row).parent().parent().find(".months").slideUp();
    }
    
    function closeMonthFunction(row)
    {
         $(row).parent().parent().find(".days").slideUp();
    }
    
  
    
    function DateFilters() {
 
        var uniqueYears = new Set();
        
        // Loop through table rows to extract years
        $('table tbody tr').each(function() {
            var date = $(this).find('td:eq(3)').text().trim(); // Assuming dates are in the fourth column
            var year = new Date(date).getFullYear(); // Extract the year from the date
            uniqueYears.add(year); // Add the year to the set
        });
        
        // Convert the Set back to an array
        var years = Array.from(uniqueYears);
    
        // Create a <ul> element for each year and append it to the container
        years.forEach(function(year) {
            var $yearLi = $("<li>").addClass("year");
            var $yearCheckbox = $("<input>").attr({ type: "checkbox", id: "year-" + year }).addClass("parent-checkbox").appendTo($yearLi);
            var $yearLabel = $("<label>").text(year).appendTo($yearLi);
            $yearLabel.attr('onclick', 'closeYearFunction(this);'); // Set onclick event handler 
            $yearLabel.on('dblclick', function() { // Set double click event handler
                yearFunction(this);
            });
            var $monthsUl = $("<ul>").addClass("months").appendTo($yearLi);
            $yearLi.appendTo('.year-list .years');
        });
    
        // Loop through years
        $(".year-list .year").each(function() {
            var $yearContainer = $(this);
            var year = parseInt($yearContainer.find('label').text().trim()); // Get the year from the label text
    
            // Loop through months to generate lists
            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for (var i = 0; i < months.length; i++) {
                var month = months[i];
                var $monthLi = $("<li>").addClass("month");
                var $monthCheckbox = $("<input>").attr({ type: "checkbox", id: "month-" + year + "-" + (i + 1) }).addClass("child-checkbox").appendTo($monthLi); // Include year in month id 
                var $monthLabel = $("<label>").text(month).appendTo($monthLi);
                $monthLabel.attr('onclick', 'closeMonthFunction(this);'); // Set onclick event handler 
                $monthLabel.on('dblclick', function() { // Set double click event handler
                    monthFunction(this);
                });
                var $daysUl = $("<ul>").addClass("days").appendTo($monthLi);
    
                // Adding days for each month
                var daysInMonth = new Date(year, i + 1, 0).getDate(); // Get the number of days in the month
                for (var j = 1; j <= daysInMonth; j++) {
                    var $dayLi = $("<li>").appendTo($daysUl);
                    var $dayCheckbox = $("<input>").attr({ type: "checkbox", id: "day-" + year + "-" + (i + 1) + "-" + j }).addClass("child-checkbox").appendTo($dayLi); // Include year in day id
                    var $dayLabel = $("<label>").text(j).appendTo($dayLi);
                    $dayLabel.attr('onclick', 'closeDayFunction(this);'); // Set onclick event handler
                    $dayLabel.on('dblclick', function() { // Set double click event handler
                        dayFunction(this);
                    });
                }
    
                $monthLi.appendTo($yearContainer.find(".months"));
            }
    
            // Collapse all months initially
            $yearContainer.find(".months").children('.month').children('.days').hide();
        });
    
        // Collapse all years initially
        $(".year-list .months").hide();
    
        // Handle change event for parent checkboxes
        // $(".parent-checkbox").change(function() {
        //     if ($(this).is(':visible')) { // Only check/uncheck child checkboxes if the parent checkbox is visible
        //         $(this).closest('.year').find('.child-checkbox').prop('checked', this.checked);
        //     }
        // });
    
        // // Handle change event for child checkboxes
        // $(".child-checkbox").change(function() {
        //     if ($(this).is(':visible')) { // Only check/uncheck parent checkbox if the child checkbox is visible
        //         var $parentCheckbox = $(this).closest('.year').find('.parent-checkbox');
        //         var allChildChecked = $(this).closest('.months').find('.child-checkbox:not(:checked)').length === 0; // Check if all child checkboxes are checked
        //         $parentCheckbox.prop('checked', allChildChecked); // Check/uncheck parent checkbox based on child checkboxes' state
        //     }
        // });
        
               
        // Handle change event for child checkboxes
    $(".child-checkbox").change(function() {
        filterTableData();
    });

    // Handle change event for parent checkboxes
    $(".parent-checkbox").change(function() {
        $(this).closest('.year').find('.child-checkbox').prop('checked', this.checked);
        filterTableData();
    });
 
    }
    
    function monthFunction(row) {
        $(row).parent().parent().find(".days").slideUp();
        var isChecked = $(row).parent().find("input[type='checkbox']").prop('checked');
        if (isChecked) {
            $(row).parent().find(".days").slideDown();
        }
        filterTableData(); // Call the filter function after selecting or deselecting months
    }
    
    function dayFunction(checkbox) {
        // Get the state of the parent checkbox
        var isChecked = $(checkbox).prop('checked');
        
        // Find all child checkboxes and set their state to match the parent checkbox
        $(checkbox).closest('.days').find('.days input[type="checkbox"]').prop('checked', isChecked);
        filterTableData(); // Call the filter function after selecting or deselecting days
    }
    
    function filterTableData() {
        var selectedDays = [];
    
        // Collect selected days
        $(".child-checkbox:checked").each(function() {
            var dayId = $(this).attr('id');
            selectedDays.push(dayId);
        });
    
        // Show all rows initially
        $('table tbody tr').show();
    
        // Filter rows based on selected days
        if (selectedDays.length > 0) {
            $('table tbody tr').each(function() {
                var date = $(this).find('td:eq(3)').text().trim();
                var year = new Date(date).getFullYear();
                var month = new Date(date).getMonth() + 1;
                var day = new Date(date).getDate();
                
                if(month < 10)
                {
                    var m = "0" + month;
                }
                else
                {
                     var m = month;
                }
                
                if(day < 10)
                {
                    var d = "0" + day;
                }
                else
                {
                     var d = day;
                }
                
                var dayId = year + "-" + m + "-" + d;
                  console.log(dayId);
                // Check if the dayId matches any of the selected days
                var found = false;
                selectedDays.forEach(function(selectedDay) {
                    if (selectedDay === dayId) {
                        found = true;
                    }
                });
    
                // If the dayId is not found among selected days, hide the row
                if (!found) {
                    $(this).hide();
                }
            });
        }
    }


    
   $(document).ready(function()
   { 
       $("th").append(`<div class="dropdown">
                  <i class="fa fa-bars ml-2"  onclick="showAllFilter(this);" style="margin-left: 20px;"></i>
                  <div class="dropdown-content hide allOptions">
                    <a href="javascript:void(0);" onclick="sortAscBtn(this);"><i class="fa fa-sort-alpha-up"></i> ASC </a>
                    <a href="javascript:void(0);" onclick="sortDescBtn(this);" > <i class="fa fa-sort-alpha-down"></i> DESC</a><hr/>
                    <a href="javascript:void(0);" onclick="clearFilters(this);"> <i class="fa fa-filter"></i> Clear Filters</a><hr/>
                    <a href="javascript:void(0);" onclick="showNumberFilter(this);">  Number Filters <i class="fa-solid fa-angle-right" style="margin-left:50px;"></i>
                        <div class="dropdown hide number_dropdown"> 
                          <div class="dropdown-content" style="margin-left: 190px;"> 
                                 <a href="javascript:void(0);" onclick="openModal('Equal')">Equal...</a>
                                 <a href="javascript:void(0);" onclick="openModal('Not Equal')">Not Equal...</a><hr/>
                                 <a href="javascript:void(0);" onclick="openModal('Less Than')">Less Than...</a>
                                 <a href="javascript:void(0);" onclick="openModal('Less Than Or Equal')">Less Than Or Equal...</a>
                                 <a href="javascript:void(0);" onclick="openModal('Greater Than')">Greater Than...</a>
                                 <a href="javascript:void(0);" onclick="openModal('Greater Than Or Equal')">Greater Than Or Equal...</a>
                                 <a href="javascript:void(0);" onclick="openModal('Between')">Between...</a><hr/>
                                 <a href="javascript:void(0);" onclick="openModal('Custom')">Custom Filter...</a> 
                          </div>
                        </div>
                    </a><hr/>
                    <input type="text" class="searchInput"  placeholder="Search..."> 
                    <div class="appendedText"></div>
                  </div>
                </div>`);
    // Populate unique data and create checkboxes
          var uniqueData = {}; // Object to store unique data
        $('table tbody tr').each(function(i) { 
            $(this).find('td').each(function(columnIndex) {
                var columnData = $(this).text().trim();
                if (columnData !== '') { // Ensure that data is not empty
                    if (!isDateFormat(columnData)) 
                    { 
                        if (!uniqueData[columnIndex]) {
                            uniqueData[columnIndex] = {}; // Create an object to store unique data for each column
                        }
                        if (!uniqueData[columnIndex][columnData]) {
                            $('.appendedText:eq(' + columnIndex + ')').append('<a href="javascript:void(0);"><input type="checkbox" name="order_id" value="' + columnData + '" checked>' + columnData + '</a>');
                            uniqueData[columnIndex][columnData] = true;
                        }
                    }
                   
                }
            });
        });
         $('.appendedText:eq(3)').append('<div class="year-list"><ul class="years"></ul></div>');
        DateFilters();
        
    

    function isDateFormat(dateString) 
    {
        var dateFormatRegex = /^\d{4}-\d{2}-\d{2}$/;
        return dateFormatRegex.test(dateString);
    }


    $('input[type="checkbox"]').not('.parent-checkbox').change(function() {
        
        var $checkbox = $(this);
        if (!$checkbox.is(':hidden')) { 
            if ($checkbox.hasClass('parent-checkbox')) {
                $checkbox.closest('.year').find('.child-checkbox').prop('checked', this.checked); 
            } else if ($checkbox.hasClass('child-checkbox')) { 
                var $monthContainer = $checkbox.closest('.month');
                var allDaysChecked = $monthContainer.find('.child-checkbox:not(:checked)').length === 0; 
                $monthContainer.find('.parent-checkbox').prop('checked', allDaysChecked); 
            }
        }
        
        var uniqueData = {}; // Object to store unique data
        $('table tbody tr').each(function() {
            var $row = $(this);
            var showRow = false;
            
            $row.find('td').each(function(columnIndex) {
                 
                  var thIndex = $checkbox.closest('th').index(); 
               
                var columnData = $(this).text().trim();
        
                // Check if the cell data is not empty and doesn't match the date format
                if (columnData !== '' && !isDateFormat(columnData)) {
                    var checkedValues = $('input[type="checkbox"]:checked').map(function() {
                        return $(this).val();
                    }).get(); // Get an array of checked checkbox values
        
                    // Check if the cell data matches any of the checked values
                    var orderId = $row.find('td:eq('+thIndex+')').text().trim();
                    if ($.inArray(orderId, checkedValues) !== -1) {
                        showRow = true;  
                        return false; 
                    }
                   
                    if (showRow) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                }
            });
        
        });
         
    });

    // Filter data based on search input
      $('.searchInput').keyup(function() {
        var searchText = $(this).val().toLowerCase();
        $('table tbody tr').each(function() {
            var found = false;
            $(this).find('td').each(function() {
                var cellText = $(this).text().toLowerCase();
                if (cellText.indexOf(searchText) !== -1) {
                    found = true;
                    return false; // Break loop
                }
            });
            if (found) {
                $(this).show();
                // Show checkboxes related to visible rows
                $(this).find('input[type="checkbox"]').closest('a').show();
            } else {
                $(this).hide();
                // Hide checkboxes related to hidden rows
                $(this).find('input[type="checkbox"]').closest('a').hide();
            }
        });
    });
    });
    
    function showNumberFilter(row) {
        if ($(row).parent().find(".number_dropdown").hasClass("hide")) {
            $(row).parent().find(".number_dropdown").removeClass("hide");
        } else {
            $(row).parent().find(".number_dropdown").addClass("hide");
        }
         $(row).parent().find(".dropdown-content").removeClass("hide");
    }
    
    function showAllFilter(row)
    {

         $(".month").slideUp();
         $(".allOptions").addClass("hide");
         if ($(row).parent().find(".allOptions").hasClass("hide")) {
            $(row).parent().find(".allOptions").removeClass("hide");
        } else {
            $(row).parent().find(".allOptions").addClass("hide");
        }
    }
    
    function openModal(filterType) {
        $('#filterType').text(filterType);
        $('#myModal').css('display', 'block');
        $(".dropdown-content").addClass("hide");
    }

    function closeModal() {
        $('#myModal').css('display', 'none');
    }

    function applyNumberFilter() {
        var filterType = $('#filterType').text();
        var filterValue = parseFloat($('#filterValueInput').val().trim());
        if(isNaN(filterValue)) {
            alert('Please enter a valid number!');
            return;
        }
        $('table tbody tr').each(function() {
            var columnValue = parseFloat($(this).find('td:eq(2)').text().trim()); // Assuming price is in the third column
            var showRow = false;
            switch(filterType) {
                case 'Equal':
                    showRow = (columnValue === filterValue);
                    break;
                case 'Not Equal':
                    showRow = (columnValue !== filterValue);
                    break;
                case 'Less Than':
                    showRow = (columnValue < filterValue);
                    break;
                case 'Less Than Or Equal':
                    showRow = (columnValue <= filterValue);
                    break;
                case 'Greater Than':
                    showRow = (columnValue > filterValue);
                    break;
                case 'Greater Than Or Equal':
                    showRow = (columnValue >= filterValue);
                    break;
                case 'Between':
                    var range = filterValue.split('-').map(parseFloat);
                    showRow = (columnValue >= range[0] && columnValue <= range[1]);
                    break;
                default:
                    showRow = true;
            }
            showRow ? $(this).show() : $(this).hide();
        });
        closeModal();
    }

    // Clear number filters
    function clearFilters(row)
    {
        $(row).closest('.dropdown-content').find('input[type="checkbox"]').prop('checked', true);
        // Show all table rows
        $('table tbody tr').show();
    }
    
    // Sort data based on ASC
    // Function to sort data based on ASC
    function sortAscending(columnIndex) {
        var rows = $('table tbody tr').get();
        rows.sort(function(a, b) {
            var aValue = $(a).find('td:eq('+columnIndex+')').text().trim();
            var bValue = $(b).find('td:eq('+columnIndex+')').text().trim();
            return aValue.localeCompare(bValue);
        });
        $('table tbody').empty().append(rows);
    }
    
    // Function to sort data based on DESC
    function sortDescending(columnIndex) {
        var rows = $('table tbody tr').get();
        rows.sort(function(a, b) {
            var aValue = $(a).find('td:eq('+columnIndex+')').text().trim();
            var bValue = $(b).find('td:eq('+columnIndex+')').text().trim();
            return bValue.localeCompare(aValue);
        });
        $('table tbody').empty().append(rows);
    }
     
    function sortAscBtn(row)
    { 
        var columnIndex = $(row).closest('th').index();
        sortAscending(columnIndex);
    }
    
    function sortDescBtn(row) 
    {
        var columnIndex = $(row).closest('th').index();
        sortDescending(columnIndex);
    }

   $('th[data-column="date"]').click(function() {
        if ($(this).hasClass('expanded')) {
            $(this).removeClass('expanded');
           // $(this).find('.months').slideUp();
        } else {
            var year = $(this).text().trim();
            var months = getMonthsForYear(year);
    
            $(this).addClass('expanded');
            $(this).append('<div class="months"></div>');
    
            var $monthsContainer = $(this).find('.months');
            months.forEach(function(month) {
                $monthsContainer.append('<div class="month">' + month + '</div>');
            });
    
          //  $monthsContainer.slideDown();
        }
    });
    
    function getMonthsForYear(year) 
    {
        var months = [];
        $('tbody tr').each(function() {
            var date = $(this).find('td:eq(3)').text().trim(); // Assuming dates are in the fourth column
            var dateYear = new Date(date).getFullYear();
            if (dateYear.toString() === year.toString()) {
                var dateMonth = new Date(date).toLocaleString('default', { month: 'long' });
                if (!months.includes(dateMonth)) {
                    months.push(dateMonth);
                }
            }
        });
        return months;
    }
    
    $(document).click(function(event) {
        if (!$(event.target).closest('.dropdown').length) {
            $(".allOptions").addClass("hide");
        }
    });
    
</script>

@endsection
