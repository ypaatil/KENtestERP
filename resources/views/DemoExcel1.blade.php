@extends('layouts.master') 
@section('content')   
<!-- end page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Excel Plugin</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">Excel Plugin</li>
            </ol>
         </div>
      </div>
   </div>
</div>  
<style>
    .filter-dropdown {
        width: 200px;
        /*max-height: 300px;*/
        overflow-y: auto;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
    }
    .filter-dropdown label {
        display: block;
        padding: 5px;
        cursor: pointer;
    }
    .filter-dropdown label:hover {
        background-color: #f0f0f0;
    }
    
    .filter-checkbox:checked + label::before{
      background:#e50000;              /* red */
      border-color:#e50000;
    }
    .filter-checkbox:checked + label::after{
      border-color:#fff;               /* white tick */
      opacity:1;
    }
    
    /* optional focus ring for keyboard nav */
    .filter-checkbox:focus + label::before{
      outline:2px solid #0047ff;
      outline-offset:2px;
    }
    
    
    .popup {
      display: none;
      position: fixed;
      top: 20%;
      left: 30%;
      width: 40%;
      background: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      z-index: 1000;
    }

    .row {
      display: flex;
      gap: 12px;
      margin-bottom: 20px;
      margin: 10px; 
    }

    .row select,
    .row input[type="date"] {
      flex: 1;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .radio-row {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: -12px;
    }

    .radio-row label {
      font-size: 14px;
    }

    .buttons {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 10px;
    }

    .buttons button {
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .buttons .ok-btn {
      background-color: #4CAF50;
      color: white;
    }

    .buttons .ok-btn:hover {
      background-color: #45a049;
    }

    .buttons .cancel-btn {
      background-color: #f44336;
      color: white;
    }

    .buttons .cancel-btn:hover {
      background-color: #d73833;
    }
    
    .hide
    {
        display:none;
    }
    
    ul.filter-list 
    {
      list-style: none;
      margin: 0;
      padding: 0 5px;
      max-height: 200px;
      overflow-y: auto;
      font-size: 16px;
    }

</style>
@if(session()->has('message'))
<div class="col-md-3">
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
</div>
@endif
@if(session()->has('messagedelete'))
<div class="col-md-3">
   <div class="alert alert-danger">
      {{ session()->get('messagedelete') }}
   </div>
</div>
@endif
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div id="filter-container">
            </div>
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="Inward_For_Packing_table" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Sr No</th>
                     <th>Issue Code</th>
                     <th>Sales Order No</th>
                     <th>Entry Date</th>
                     <th>Buyer Name</th>
                     <th>Vendor Name</th>
                     <th>Total Qty</th>
                     <th>User</th>
                  </tr>
               </thead>
               <tbody>
                   <tr>
                      <td>1</td>
                      <td>IC001</td>
                      <td>SO1234</td>
                      <td>2024-11-23</td>
                      <td>John Doe</td>
                      <td>Vendor A</td>
                      <td>100</td>
                      <td>Admin</td>
                   </tr>
                   <tr>
                      <td>2</td>
                      <td>IC002</td>
                      <td>SO1235</td>
                      <td>2024-11-22</td>
                      <td>Jane Smith</td>
                      <td>Vendor B</td>
                      <td>200</td>
                      <td>Manager</td>
                   </tr>
                   <tr>
                      <td>3</td>
                      <td>IC003</td>
                      <td>SO1236</td>
                      <td>2024-11-21</td>
                      <td>Adam Johnson</td>
                      <td>Vendor C</td>
                      <td>300</td>
                      <td>User</td>
                   </tr>
                   <tr>
                      <td>4</td>
                      <td>IC004</td>
                      <td>SO1237</td>
                      <td>2024-11-20</td>
                      <td>Emily Davis</td>
                      <td>Vendor D</td>
                      <td>400</td>
                      <td>Admin</td>
                   </tr>
                   <tr>
                      <td>5</td>
                      <td>IC005</td>
                      <td>SO1238</td>
                      <td>2024-11-19</td>
                      <td>Chris Lee</td>
                      <td>Vendor E</td>
                      <td>500</td>
                      <td>Manager</td>
                   </tr>
                   <tr>
                      <td>6</td>
                      <td>IC006</td>
                      <td>SO1239</td>
                      <td>2024-11-18</td>
                      <td>Lisa Brown</td>
                      <td>Vendor F</td>
                      <td>600</td>
                      <td>User</td>
                   </tr>
                   <tr>
                      <td>7</td>
                      <td>IC007</td>
                      <td>SO1240</td>
                      <td>2024-11-17</td>
                      <td>Michael Green</td>
                      <td>Vendor G</td>
                      <td>700</td>
                      <td>Admin</td>
                   </tr>
                   <tr>
                      <td>8</td>
                      <td>IC008</td>
                      <td>SO1241</td>
                      <td>2024-11-16</td>
                      <td>Sarah Wilson</td>
                      <td>Vendor H</td>
                      <td>800</td>
                      <td>Manager</td>
                   </tr>
                   <tr>
                      <td>9</td>
                      <td>IC009</td>
                      <td>SO1242</td>
                      <td>2024-11-15</td>
                      <td>Paul Martinez</td>
                      <td>Vendor I</td>
                      <td>900</td>
                      <td>User</td>
                   </tr>
                   <tr>
                      <td>10</td>
                      <td>IC010</td>
                      <td>SO1243</td>
                      <td>2024-11-14</td>
                      <td>Anna Garcia</td>
                      <td>Vendor J</td>
                      <td>1000</td>
                      <td>Admin</td>
                   </tr>
                   <tr>
                      <td>11</td>
                      <td>IC011</td>
                      <td>SO1241</td>
                      <td>2024-11-16</td>
                      <td>Sarah Wilson</td>
                      <td>Vendor H</td>
                      <td>800</td>
                      <td>Manager</td>
                   </tr>
                   <tr>
                      <td>12</td>
                      <td>IC012</td>
                      <td>SO1242</td>
                      <td>2024-11-15</td>
                      <td>Paul Martinez</td>
                      <td>Vendor I</td>
                      <td>900</td>
                      <td>User</td>
                   </tr>
                   <tr>
                      <td>13</td>
                      <td>IC013</td>
                      <td>SO1243</td>
                      <td>2024-11-14</td>
                      <td>Anna Garcia</td>
                      <td>Vendor J</td>
                      <td>1000</td>
                      <td>Admin</td>
                   </tr>
                </tbody>
            </table>
             
         </div>
      </div>
   </div>
   <!-- end col -->
</div>

<div class="popup">
  <!-- First Filter Row -->
  <div class="row">
      <h3>Custom Filter</h3>
      <h4>Show rows where:</h4>
      <h5>Order Date</h5>
      <div class="row">
        <select name="filter1">
          <option value="equal">Equal</option>
          <option value="equal_not_equal">Equal Or Not Equal</option>
          <option value="greater_than">Greater Than</option>
          <option value="greater_than_not_equal">Greater Than Or Equal</option>
          <option value="less_than">Less Than</option> 
          <option value="less_than_not_equal">Less Than Or Equal </option> 
          <option value="between">Between</option>
        </select>
        <input type="date" name="date1">
      </div>
    
      <!-- Logical Condition Row -->
      <div class="radio-row">
        <label><input type="radio" name="logic" value="AND" checked> AND</label>
        <label><input type="radio" name="logic" value="OR"> OR</label>
      </div>
    
      <!-- Second Filter Row -->
      <div class="row">
        <select name="filter2">
          <option value="equal">Equal</option>
          <option value="equal_not_equal">Equal Or Not Equal</option>
          <option value="greater_than">Greater Than</option>
          <option value="greater_than_not_equal">Greater Than Or Equal</option>
          <option value="less_than">Less Than</option> 
          <option value="less_than_not_equal">Less Than Or Equal </option> 
          <option value="between">Between</option>
        </select>
        <input type="date" name="date2">
      </div> 
      <!-- Action Buttons -->
      <div class="buttons">
        <button class="ok-btn" onclick="FilterPopup();">OK</button>
        <button class="cancel-btn">Cancel</button>
      </div>
    </div>
</div>


<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 
    
    function isDate(value) 
    {
      // Checks for ISO format (YYYY-MM-DD) or DD/MM/YYYY
      return !isNaN(Date.parse(value)) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
    }
    
    function isNumeric(value) 
    { 
      return !isNaN(value) && $.isNumeric(value);
    }

    function OpenPopup(row)
    {
        $(".filter-dropdown").hide(); 
        $(".popup").fadeIn();
    }
    
      // Hide the popup when the "Cancel" button is clicked
    $(".cancel-btn").click(function() 
    {
        $(".popup").fadeOut();
    });
    
    function FilterPopup() 
    {
        const filter1 = $("select[name='filter1']").val();
        const filter2 = $("select[name='filter2']").val();
        const date1 = $("input[name='date1']").val();
        const date2 = $("input[name='date2']").val();
        const logic = $("input[name='logic']:checked").val();
    
        $("table tbody tr").each(function () {
            let matchFound = false;
    
            $(this).find("td").each(function () {
                const cellText = $(this).text().trim();
                const dateValue = new Date(cellText);
    
                if (!isNaN(dateValue)) {
                    const condition1 = evaluateCondition(filter1, date1, dateValue);
                    const condition2 = evaluateCondition(filter2, date2, dateValue);
    
                    const finalCondition = (logic === "AND") ? (condition1 && condition2) : (condition1 || condition2);
    
                    if (finalCondition) {
                        matchFound = true;
                        return false; // break loop, we found a match in this row
                    }
                }
            });
    
            if (matchFound) {
                $(this).show(); // show entire row
            } else {
                $(this).hide(); // hide entire row
            }
        });
        $(".popup").fadeOut();
    }

    
    function evaluateCondition(filter, inputDate, dateValue) 
    {
        const input = new Date(inputDate);
    
        switch (filter) {
          case "equal":
            return dateValue.toDateString() === input.toDateString();
          case "equal_not_equal":
            return dateValue.toDateString() !== input.toDateString();
          case "greater_than":
            return dateValue > input;
          case "greater_than_not_equal":
            return dateValue >= input;
          case "less_than":
            return dateValue < input;
          case "less_than_not_equal":
            return dateValue <= input;
          case "between":
            const fromDate = new Date($("input[name='date1']").val());
            const toDate = new Date($("input[name='date2']").val());
            return dateValue >= fromDate && dateValue <= toDate;
          default:
            return true;
        }
    }
    
    $(".cancel-btn").click(function () 
    {
        $("td").show(); 
    });
    
    function getUncheckedValuesMatchingTarget(index) {
        const targetUl = $(".filter-dropdown ul")[index];
        const uncheckedValues = [];
    
        $('.filter-dropdown:visible').find('ul').each(function () {
            $(this).find('li:visible input[type="checkbox"]:not(:checked)').each(function () {
                const val = $(this).val();
                // Match with target UL's checkbox having same value
                if ($(targetUl).find('input[type="checkbox"][value="' + val + '"]').length) {
                    uncheckedValues.push(val);
                }
            });
        });
    
        return uncheckedValues;
    }
    
    function getCheckedFilterCount() {
        let count = 0;
        $('.filter-dropdown ul').each(function () {
            const checked = $(this).find('input[type="checkbox"]:checked').length;
            if (checked > 0) count++;
        });
        return count;
    }
    
    function RegenerateFilter(row, ele) {
        setTimeout(function () {
    
            var tableId = $('table').first().attr('id');
            var table = $('#' + tableId); // convert ID string back to jQuery object
            const columnsToFilter = [];
            table.find('thead th').each(function() {
                columnsToFilter.push($(this).text().trim());
            });

            const onlyOneFilterChecked = getCheckedFilterCount() === 1;
    
            columnsToFilter.forEach((columnName, index) => {
                const visibleValues = new Set();
                let isNumeric = true;
    
                // Step 1: Get visible values from the table for this column
                table.find(`tbody tr:visible td:nth-child(${index + 1})`).each(function () {
                    const text = $(this).text().trim();
                    if (text !== "") {
                        const numeric = text.replace(/,/g, '');
                        if (isNaN(Number(numeric))) isNumeric = false;
                        visibleValues.add(text);
                    }
                });
    
                const sorted = Array.from(visibleValues).sort((a, b) => {
                    return isNumeric ? Number(a.replace(/,/g, '')) - Number(b.replace(/,/g, '')) : a.localeCompare(b);
                });
    
                const targetUl = $(".filter-dropdown ul").eq(index);
                const isThisFilterChecked = targetUl.find('input[type="checkbox"]:checked').length > 0;
    
                // Step 2: Show/hide logic
                targetUl.find('li').each(function () {
                    const value = $(this).find('input[type="checkbox"]').val();
    
                    if (onlyOneFilterChecked && isThisFilterChecked) {
                        // ✅ Special case: show all if this is the only filter checked
                        $(this).removeClass('hide');
                    } else {
                        // Normal case: hide if value is not in visible table data
                        if (sorted.includes(value)) {
                            $(this).removeClass('hide');
                        } else {
                            $(this).addClass('hide');
                        }
                    }
                });
    
                // Step 3: Set data-unchecked attribute
                const unchecked = targetUl.find('li:visible input[type="checkbox"]:not(:checked)')
                    .map(function () { return $(this).val(); }).get().join(',');
                targetUl.attr('data-unchecked', unchecked);
            });
    
            // Step 4: Restore Reverse click
            $(row).removeAttr("onclick").attr("onclick", 'Reverse(this);');
        }, 200);
    }


    
    function Reverse(row) 
    {
        var allDrops = $('.filter-dropdown');
    
        allDrops.each(function(i) {
    
            const lis = $(this).find('li');
            const checkboxes = lis.find('input[type="checkbox"]');
    
            if (checkboxes.length > 0 && checkboxes.is(':checked')) {
                lis.removeClass('hide');
            }
        });
      
        $(row).removeAttr("onclick");
        $(row).attr("onclick", 'RegenerateFilter(this);');
        RegenerateFilter(row);
    }

    function MakeSelectAll(row) 
    {
        var ul = $(row).siblings('ul');
    
        // Find all checkboxes inside the <ul>
        var checkboxes = ul.find('input[type="checkbox"]');
     
        // Get the checked status of the "Select All" checkbox
        var isChecked = $(row).prop('checked');
    
        // Set all checkboxes to match the "Select All" status
        checkboxes.prop('checked', isChecked);
    }
  
    function ClearFilter(row)
    {
        $(row).parent().parent('.filter-dropdown').find('ul li').removeClass('hide');
    }
    
    $(document).ready(function () 
    { 
        //const table = $('#Inward_For_Packing_table');
        var tableId = $('table').first().attr('id');
        var table = $('#' + tableId); // convert ID string back to jQuery object
        const firstRow = table.find('tr').first();
        const totalCols = firstRow.find('th').length;

        
        for (let col = 0; col < totalCols; col++) 
        {
          let isNumeric = true;
          let isDate = true;
          let isText = true;
        
          table.find('tr').each(function () {
            const cell = $(this).find('td').eq(col);
            if (cell.length === 0) return;
        
            const value = cell.text().trim();
        
            // If value is empty, consider it non-numeric and non-date
            if (value === '') {
              isNumeric = false;
              isDate = false;
              return false;
            }
        
            if (isNaN(value)) isNumeric = false;
        
            if (isNaN(Date.parse(value))) isDate = false;
        
            if (!isNaN(value) || !isNaN(Date.parse(value))) isText = false;
        
            if (!isNumeric && !isDate && !isText) return false;
          });
        
            const th = firstRow.find('th').eq(col);
            const thButton = th.children('button');
            
            if (isNumeric) {
              th.addClass('numeric_val');
              thButton.addClass('numeric_val');
              th.attr('onclick', 'ReopenPopup(this,1);');
            } else if (isDate) {
              th.addClass('date_val');
              thButton.addClass('date_val');
              th.attr('onclick', 'ReopenPopup(this,1);');
            } else if (isText) {
              th.addClass('text_val');
              thButton.addClass('text_val');
              th.attr('onclick', 'ReopenPopup(this,1);');
            }
            
        }
            
        const columnsToFilter = [];
        table.find('thead th').each(function() {
            columnsToFilter.push($(this).text().trim());
        });
    
        columnsToFilter.forEach((columnName, index) => {
            const uniqueValues = [];
            let isNumericColumn = true;
    
            // Collect unique values & detect if numeric column
            table.find(`tbody tr:visible td:nth-child(${index + 1})`).each(function () {
                const text = $(this).text().trim();
                if (text !== "") {
                    const normalized = text.replace(/,/g, '').trim();
                    if (isNaN(Number(normalized))) isNumericColumn = false;
                    if (!uniqueValues.includes(text)) uniqueValues.push(text);
                }
            });
           
            const th = table.find(`thead tr th:eq(${index})`);
            const filterIcon = $(`
                <button class="btn btn-link filter-icon" type="button" id="filter-col-${index}-btn" style="position: relative;" onclick="RegenerateFilter(this,1);ReopenPopup(this,2);">
                    <i class="fas fa-filter"></i>
                </button>
            `);
            th.css('position', 'relative').append(filterIcon);
    
    
            const dropdown = $(`
              <div class="filter-dropdown" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 10px; z-index: 9999; width: 20vw; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Segoe UI', sans-serif;">
                <div class="sorting-section" style="margin-bottom: 10px; text-align: left;">
                  <button class="sort-asc" style="border: none; background: none; cursor: pointer; padding: 10px; font-size: 16px;" title="Sort A to Z">
                    <i class="fas fa-sort-alpha-down"></i>&nbsp;&nbsp; Sort Smallest to Largest
                  </button>
                  <button class="sort-desc" style="border: none; background: none; cursor: pointer; padding: 10px; font-size: 16px;" title="Sort Z to A">
                    <i class="fas fa-sort-alpha-up"></i>&nbsp;&nbsp; Sort Largest to Smallest
                  </button> 
                </div>
                <div class="sorting-section" style="margin-bottom: 10px; text-align: left; display: flex; justify-content: space-between; align-items: center;"> 
                  <button class="clear" style="border: none; background: none; cursor: pointer; padding: 10px; font-size: 16px;" onclick="ClearFilter(this);RegenerateFilter(this,2);">
                    <i class="fas fa-filter"></i>&nbsp;&nbsp; Clear Filter
                  </button> 
                </div>
                <div class="sorting-section" style="margin-bottom: 10px; text-align: left; display: flex; justify-content: space-between; align-items: center;">  
                  <div class="text_filter txtx" style="cursor: pointer; font-size: 16px; user-select: none; display: flex; align-items: center;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Text Filters&nbsp;&nbsp;<i class="fas fa-angle-right"></i>
                  </div>
                </div>
                <div class="sorting-section" style="margin-bottom: 10px; text-align: left; display: flex; justify-content: space-between; align-items: center;"> 
                  <div class="date_filter datex" style="cursor: pointer; font-size: 16px; user-select: none; display: flex; align-items: center;" onclick="OpenPopup(this);">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Filters&nbsp;&nbsp;<i class="fas fa-angle-right"></i>
                  </div>
                </div>
                <div class="sorting-section" style="margin-bottom: 10px; text-align: left; display: flex; justify-content: space-between; align-items: center;"> 
                  <div class="number_filter numx" style="cursor: pointer; font-size: 16px; user-select: none; display: flex; align-items: center;">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number Filters&nbsp;&nbsp;<i class="fas fa-angle-right"></i>
                  </div>
                </div>
                <input type="text" class="filter-search-box" placeholder="Search..."  style="width: 100%; margin-bottom: 10px; padding: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;"> 
                <input type="checkbox" value="Select All" class="filter-checkbox" style="height: 27px;width: 27px;margin-left: 6px;" onchange="MakeSelectAll(this);">
                <span style="font-size: 16px;margin-left: 10px;position: absolute;">Select All</span> 
                <ul style="list-style: none; margin: 0; padding: 0 5px; max-height: 200px; overflow-y: auto; font-size: 16px;">
                  ${uniqueValues.map(value => `
                    <li style="position: relative; padding-left: 40px; line-height: 33px;">
                      <input type="checkbox" value="${value.replace(/"/g, '&quot;')}" class="filter-checkbox" data-column="${index}" style="position: absolute; left: 0; top: 3px; height: 27px; width: 27px;">
                      <span>${value}</span> 
                    </li>
                  `).join('')}
                </ul>
              </div>
            `);
    
            const textFilterBox = $(`
              <div class="text-filter-box" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 15px; width: 15vw; z-index: 10000; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); font-family: 'Segoe UI', sans-serif;">
                <div class="filter-section">
                  <div class="custom-dropdown" style="position: relative; margin-bottom: 10px;">
                    <div class="selected-option" style="padding: 6px 10px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc; background: #fff; cursor: pointer;">Select Filter Type</div>
                    <div class="dropdown-options" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; margin-top: 4px; z-index: 10001; max-height: 150px; overflow-y: auto; width: 100%;">
                      ${[
                        { value: 'equal', label: 'Equal' },
                        { value: 'not_equal', label: 'Not Equal' },
                        { value: 'starts_with', label: 'Starts With' },
                        { value: 'ends_with', label: 'Ends With' },
                        { value: 'contains', label: 'Contains' },
                        { value: 'not_contains', label: 'Does Not Contain' }
                      ].map(opt => `
                        <div class="dropdown-option" data-value="${opt.value}" style="padding: 8px 10px; font-size: 14px; cursor: pointer;">${opt.label}</div>
                      `).join('')}
                    </div>
                  </div>
                  <input type="text" class="filter-value" placeholder="Enter text filter..." onchange="manualSearch(this);" column="${index}" style="width: 100%; margin-bottom: 10px; padding: 6px 10px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc;">
                </div>
              </div>
            `);
    
            const numberFilterBox = $(`
              <div class="number-filter-box" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 15px; width: 15vw; z-index: 10000; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); font-family: 'Segoe UI', sans-serif;">
                <div class="filter-section">
                  <div class="custom-dropdown" style="position: relative; margin-bottom: 10px;">
                    <div class="selected-option" style="padding: 6px 10px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc; background: #fff; cursor: pointer;">Select Filter Type</div>
                    <div class="dropdown-options" style="display: none; position: absolute; background: #fff; border: 1px solid #ccc; margin-top: 4px; z-index: 10001; max-height: 150px; overflow-y: auto; width: 100%;">
                      ${[
                        { value: 'equal', label: 'Equal' },
                        { value: 'not_equal', label: 'Not Equal' },
                        { value: 'greater_than', label: 'Greater Than' },
                        { value: 'greater_than_or_equal', label: 'Greater Than Or Equal' },
                        { value: 'less_than', label: 'Less Than' },
                        { value: 'less_than_or_equal', label: 'Less Than Or Equal' },
                        { value: 'between', label: 'Between' }
                      ].map(opt => `
                        <div class="dropdown-option" data-value="${opt.value}" style="padding: 8px 10px; font-size: 14px; cursor: pointer;">${opt.label}</div>
                      `).join('')}
                    </div>
                  </div>
                  <input type="text" class="filter-value" placeholder="Enter number..."  onchange="manualSearch(this);" column="${index}" style="width: 100%; margin-bottom: 10px; padding: 6px 10px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc;">
                  <input type="text" class="filter-value-between" placeholder="Enter second number (for 'Between')" style="width: 100%; margin-bottom: 10px; padding: 6px 10px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc; display: none;">
                </div>
              </div>
            `);
    
            $('body').append(dropdown, numberFilterBox,textFilterBox); 
            
            // Position dropdown under filter icon
            filterIcon.on('click', function (e) {
                e.stopPropagation();
                const thOffset = th.offset();
                $('.filter-dropdown').not(dropdown).hide();
                $('.text-filter-box').hide();
                dropdown.css({ top: thOffset.top + th.outerHeight() + 5, left: thOffset.left, display: 'block' });
            });
    
            dropdown.on('click', e => e.stopPropagation());
            textFilterBox.on('click', e => e.stopPropagation());
            numberFilterBox.on('click', e => e.stopPropagation());
    
            // Hide filters if clicking outside
            $(document).on('click', () => {
                //RegenerateFilter(this,1);
                dropdown.hide();
                textFilterBox.hide();
                numberFilterBox.hide();
            });
    
            // Show text filter box
            dropdown.find('.text_filter').on('click', function (e) {
                e.stopPropagation();
                const offset = $(this).offset();
                textFilterBox.css({ top: offset.top + $(this).outerHeight() + 5, left: offset.left, display: 'block' });
                numberFilterBox.css({ top: offset.top + $(this).outerHeight() + 5, left: offset.left, display: 'block' });
            });
    
            // Show number filter box (same UI for now)
            dropdown.find('.number_filter').on('click', function (e) {
                e.stopPropagation();
                const offset = $(this).offset();
                numberFilterBox.css({ top: offset.top + $(this).outerHeight() + 5, left: offset.left, display: 'block' });
            });
    
            // Custom dropdown toggle in text filter box
            textFilterBox.find('.selected-option').on('click', function () {
                $(this).siblings('.dropdown-options').toggle();
            });
    
            // Custom dropdown toggle in text filter box
            numberFilterBox.find('.selected-option').on('click', function () {
                $(this).siblings('.dropdown-options').toggle();
            });
    
            // Select option in custom dropdown
            textFilterBox.find('.dropdown-option').on('click', function () {
                const val = $(this).attr('data-value');
                const label = $(this).text();
                const container = $(this).closest('.custom-dropdown');
                container.find('.selected-option').text(label).attr('data-value', val);
                container.find('.dropdown-options').hide();
    
                // Show/hide second input for 'between' only if numeric
                if (isNumericColumn && val === 'between') {
                    textFilterBox.find('.filter-value-between').show();
                } else {
                    textFilterBox.find('.filter-value-between').hide();
                }
    
                applyCombinedFilter();
            });
            numberFilterBox.find('.dropdown-option').on('click', function () {
                const val = $(this).attr('data-value');
                const label = $(this).text();
                const container = $(this).closest('.custom-dropdown');
                container.find('.selected-option').text(label).attr('data-value', val);
                container.find('.dropdown-options').hide();
    
                // Show/hide second input for 'between' only if numeric
                if (isNumericColumn && val === 'between') {
                    numberFilterBox.find('.filter-value-between').show();
                } else {
                    numberFilterBox.find('.filter-value-between').hide();
                }
    
                applyCombinedFilter();
            });
            // Sorting buttons
            dropdown.find('.sort-asc').on('click', () => {
                sortTable(index, true);
                dropdown.hide();
            });
            dropdown.find('.sort-desc').on('click', () => {
                sortTable(index, false);
                dropdown.hide();
            });
    
            // Clear filter button
            dropdown.find('.clear').on('click', () => {
                dropdown.find('.filter-checkbox').prop('checked', false);
                textFilterBox.find('.filter-value').val('');
                textFilterBox.find('.filter-value-between').val('').hide();
                textFilterBox.find('.selected-option').text('Select Filter Type').removeAttr('data-value');
                applyCombinedFilter();
            });
    
            // Filter checkbox changes
            dropdown.find('.filter-checkbox').on('change', applyCombinedFilter);
            // Assuming dropdown is the container that will always be in DOM
            dropdown.on('keyup', '.filter-search-box', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
            
                if (searchTerm === '') {
                    // Show all and uncheck all when search box is empty
                    dropdown.find('.filter-checkbox').each(function() {
                        $(this).parent('li').show();
                        $(this).prop('checked', false);
                    });
                } else {
                    dropdown.find('.filter-checkbox').each(function() {
                        // Adjust selector if needed based on your HTML structure
                        const label = $(this).siblings('span').text().toLowerCase();
            
                        if (label.includes(searchTerm)) {
                            $(this).parent('li').show();
                            $(this).prop('checked', true);
                        } else {
                            $(this).parent('li').hide();
                            $(this).prop('checked', false);
                        }
                    });
                }
            
                applyCombinedFilter();
            });


            // Filter input changes with debounce (for text/number)
            let debounceTimeout;
            textFilterBox.find('.filter-value, .filter-value-between').on('input', function () {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(applyCombinedFilter, 400);
            });
            
            numberFilterBox.find('.filter-value, .filter-value-between').on('input', function () {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(applyCombinedFilter, 400);
            });
        
            function applyCombinedFilter() 
            { 
                $(this).parent().parent('ul').siblings('input').prop('checked', false);
                table.find('tbody tr').each(function () {
                    let showRow = true;
            
                    for (let i = 0; i < columnsToFilter.length; i++) {
                        const colIndex = i;
                        const colName = columnsToFilter[i];
                        const cell = $(this).find(`td:eq(${colIndex})`);
                        const cellText = cell.length ? cell.text().trim() : '';
                        const normalizedCell = cellText.replace(/[^\d.-]/g, '');
            
                        const checkboxes = $(`.filter-dropdown .filter-checkbox[data-column="${colIndex}"]:checked`);
                        if (checkboxes.length > 0) {
                            const selectedValues = checkboxes.map(function () {
                                return $(this).val();
                            }).get();
                             
                            // var prev_str = sortedValues.join(',');
                            // $(row).attr('prev_str', prev_str);
                
                            if (!selectedValues.includes(cellText)) {
                                showRow = false;
                                break; // This breaks out of the for loop, which is OK
                            }
                        }
                    }
            
                    $(this).toggle(showRow);
                });  
            }
    
     
            // Sorting function for column
            function sortTable(columnIdx, ascending = true) 
            {
                const rows = table.find('tbody tr').get();
                rows.sort((a, b) => {
                    let A = $(a).find(`td:eq(${columnIdx})`).text().trim();
                    let B = $(b).find(`td:eq(${columnIdx})`).text().trim();
    
                    if (isNumericColumn) {
                        A = parseFloat(A.replace(/,/g, ''));
                        B = parseFloat(B.replace(/,/g, ''));
                        A = isNaN(A) ? 0 : A;
                        B = isNaN(B) ? 0 : B;
                    } else {
                        A = A.toLowerCase();
                        B = B.toLowerCase();
                    }
    
                    if (A < B) return ascending ? -1 : 1;
                    if (A > B) return ascending ? 1 : -1;
                    return 0;
                });
    
                $.each(rows, function (idx, row) {
                    table.children('tbody').append(row);
                });
            }
        });
     
    });
   
    function ReopenPopup(row, pc)
    {
        var count = 1;

        $('.filter-dropdown').each(function() 
        {
            var $dropdown = $(this);
    
            var isAnyChecked = $dropdown.find('ul li input[type="checkbox"]:checked').length > 0;
    
            if (isAnyChecked) 
            {
                var cntLabel = 'cnt_' + count;
                $dropdown.attr('data-cnt', cntLabel);
                count++;
            }
            else
            {
                  $dropdown.removeAttr('data-cnt');
            }
        });
        
        $('.filter-dropdown').removeClass('num').removeClass('txt').removeClass('dates'); 
        
        if(pc == 2)
        {
            var cls = $(row).parent('th').attr('class');
            $(row).addClass(cls);
        }
        
        let dropdown = $('.filter-dropdown');
        if($(row).hasClass('numeric_val'))
        {
            $('.filter-dropdown').addClass('num');
            $('.filter-dropdown').parent().find('.numx').removeClass('hide');
            $('.filter-dropdown').parent().find('.datex').addClass('hide');
            $('.filter-dropdown').parent().find('.txtx').addClass('hide');
        } 
        else if ($(row).hasClass('text_val')) 
        {
            $('.filter-dropdown').parent().addClass('txt');
            $('.filter-dropdown').parent().find('.txtx').removeClass('hide');
            $('.filter-dropdown').parent().find('.datex').addClass('hide');
            $('.filter-dropdown').parent().find('.numx').addClass('hide');
        } 
        else if ($(row).hasClass('date_val')) 
        {
            $('.filter-dropdown').addClass('dates');
            $('.filter-dropdown').parent().find('.datex').removeClass('hide');
            $('.filter-dropdown').parent().find('.numx').addClass('hide');
            $('.filter-dropdown').parent().find('.txtx').addClass('hide');
        }
        
    }
 
    function manualSearch(row) 
    {
        //var table = $('#Inward_For_Packing_table');
        var tbl = $('table').first();
        var table = tbl.attr('id');  
        var colIndex = $(row).attr('column'); 
        var selectedOption = $(row).parent('div').find(".selected-option").attr('data-value');
        var inputValue = ($(row).val() || '').trim();
        var inputValue2 = $(row).siblings('.filter-value-between').val() || '';
    
        table.find('tbody tr').each(function () {
            var cell = $(this).find(`td:eq(${colIndex})`);
            var cellText = cell.text().trim();
            let showRow = true;
    
            let valueToCompare = cellText;
    
            if (['greater_than', 'less_than', 'between'].includes(selectedOption)) 
            {
                valueToCompare = parseFloat(cellText.replace(/[^\d.-]/g, ''));
            }
            console.log(selectedOption);
            switch (selectedOption) {
                case 'equal':
                    if (valueToCompare != inputValue) showRow = false;
                    break;
                case 'not_equal':
                    if (valueToCompare == inputValue) showRow = false;
                    break;
                case 'greater_than':
                    if (valueToCompare <= parseFloat(inputValue)) showRow = false;
                    break;
                case 'less_than':
                    if (valueToCompare >= parseFloat(inputValue)) showRow = false;
                    break;
                case 'between':
                    if (inputValue === '' || inputValue2 === '' ||
                        valueToCompare < parseFloat(inputValue) || valueToCompare > parseFloat(inputValue2)) {
                        showRow = false;
                    }
                    break;
                case 'starts_with':
                    if (!cellText.toLowerCase().startsWith(inputValue.toLowerCase())) showRow = false;
                    break;
                case 'ends_with':
                    if (!cellText.toLowerCase().endsWith(inputValue.toLowerCase())) showRow = false;
                    break;
                case 'contains':
                    if (!cellText.toLowerCase().includes(inputValue.toLowerCase())) showRow = false;
                    break;
                case 'not_contains':
                    if (cellText.toLowerCase().includes(inputValue.toLowerCase())) showRow = false;
                    break;
            }
    
            $(this).toggle(showRow);
        });
    }


</script>  
@endsection