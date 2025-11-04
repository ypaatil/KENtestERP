<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
   body {
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f5f5f5;
        color: #333;
    }
    
    .container-fluid {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 20px;
        overflow: hidden;
    }
    
    .controls {
        margin-bottom: 10px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    button {
        padding: 8px 16px;
        background: #217346;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    button:hover {
        background: #1a5c38;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
    }
    
    .spreadsheet-container {
        border: 1px solid #d0d0d0;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .table-wrapper {
        width: 100%;
        height: 1000px;
        overflow: auto;
        position: relative;
    }

    /* Table styling */
    #spreadsheet {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 100%;
        font-size: 14px;
        table-layout: fixed;
    }

    #spreadsheet th,
    #spreadsheet td {
        border-right: 1px solid #d0d0d0;
        border-bottom: 1px solid #d0d0d0;
        padding: 6px 8px;
        min-width: 100px;
        position: relative;
        box-sizing: border-box;
        height: 28px;
        line-height: 16px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Sticky Top Header Row */
    #spreadsheet thead th {
        position: sticky;
        top: 0;
        background-color: #f2f2f2;
        z-index: 6;
        font-weight: 600;
        color: #215f33;
        border-top: 1px solid #d0d0d0;
        border-bottom: 2px solid #d0d0d0;
        text-align: center;
    }

    /* Sticky First Column (Row Header) */
    #spreadsheet td.row-header,
    #spreadsheet th.row-header {
        position: sticky;
        left: 0;
        z-index: 5;
        background-color: #f8f8f8;
        font-weight: 600;
        color: #215f33;
        border-left: 1px solid #d0d0d0;
        text-align: center;
        min-width: 60px;
    }

    #spreadsheet th.row-header {
        z-index: 10;
        border-bottom: 2px solid #d0d0d0;
    }

    /* Second column */
    #spreadsheet th:nth-child(2),
    #spreadsheet td:nth-child(2) {
        position: sticky;
        left: 60px; /* Matches the width of the first column */
        background-color: #f8f8f8;
        z-index: 8;
        min-width: 120px;
        border-right: 2px solid #ddd;
        font-weight: 600;
    }
    
    #spreadsheet th:nth-child(2) {
        z-index: 9;
    }

    /* Third column */
    #spreadsheet th:nth-child(3),
    #spreadsheet td:nth-child(3) {
        position: sticky;
        left: 180px; /* Matches the width of the first two columns */
        background-color: #f8f8f8;
        z-index: 8;
        min-width: 120px;
        border-right: 2px solid #ddd;
        font-weight: 600;
    }
    
    #spreadsheet th:nth-child(3) {
        z-index: 9;
    }

    /* Cell focus styling */
    #spreadsheet td[contenteditable="true"]:focus {
        outline: 2px solid #217346;
        outline-offset: -2px;
        background-color: #e6f4ee;
    }

    /* Column header styling */
    .col-header-content {
        display: inline-block;
        margin-right: 20px;
    }

    .filter-icon {
        width: 16px;
        height: 16px;
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        fill: #666;
        cursor: pointer;
    }

    .col-header.filtered .filter-icon {
        fill: #217346;
    }

    /* Drag handle styling */
    .drag-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #217346;
        border-radius: 1px;
        cursor: cell;
        z-index: 10;
        display: none;
    }

    /* Selected cell styling */
    td.selected {
        background-color: #ddebf7;
    }

    /* Dropdown cell styling */
    .dropdown-cell {
        position: relative;
        cursor: pointer;
        background-color: #f0f7ff;
    }

    .dropdown-arrow {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #555;
    }

    .dropdown-menu {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        font-size: 13px;
    }

    .dropdown-menu div {
        padding: 6px 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }

    .dropdown-menu div:hover {
        background-color: #e6f4ee;
    }

    .dropdown-menu div:last-child {
        border-bottom: none;
    }

    /* Filter popup styling */
    .filter-popup {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        padding: 10px;
        z-index: 10010;
        width: 250px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        font-size: 13px;
    }

    .filter-tabs {
        display: flex;
        border-bottom: 1px solid #ccc;
        margin-bottom: 10px;
    }

    .filter-tab {
        padding: 5px 10px;
        cursor: pointer;
        font-size: 12px;
    }

    .filter-tab.active {
        background-color: #f0f0f0;
        border-bottom: 2px solid #217346;
    }

    .filter-tab-content {
        display: none;
        max-height: 200px;
        overflow-y: auto;
    }

    .filter-tab-content.active {
        display: block;
    }

    .filter-search {
        width: 100%;
        margin-bottom: 10px;
        padding: 5px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 12px;
    }

    .values-list {
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #eee;
        padding: 5px;
    }

    .values-list label {
        display: block;
        margin-bottom: 5px;
        font-size: 12px;
    }

    .number-filter-options, .date-filter-options {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .number-filter-options input, .date-filter-options input {
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 12px;
    }

    .custom-filter-container {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .custom-filter-row {
        display: flex;
        gap: 5px;
    }

    .custom-filter-row select {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 12px;
        padding: 3px;
    }

    .custom-filter-row input {
        flex: 2;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 12px;
    }

    .custom-filter-and {
        padding: 5px 0;
    }

    .custom-filter-add {
        color: #217346;
        cursor: pointer;
        text-decoration: underline;
        margin-top: 5px;
        font-size: 12px;
    }

    .filter-popup-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 10px;
    }

    .filter-popup-buttons button {
        padding: 5px 10px;
        cursor: pointer;
        font-size: 12px;
    }

    /* Loading indicator */
    .loading-indicator {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 20px;
        border-radius: 5px;
        z-index: 2000;
    }

    /* Save status indicator */
    .save-status {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        font-size: 14px;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .save-status.saving {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }

    .save-status.saved {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .save-status.error {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    /* Special column widths */
    #spreadsheet td[data-col="15"] {
        min-width: 200px;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Row hover effect */
    #spreadsheet tbody tr:hover {
        background-color: #f5f5f5;
    }

    /* Zebra striping for rows */
    #spreadsheet tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    #spreadsheet tbody tr:nth-child(even):hover {
        background-color: #f0f0f0;
    }

    /* Scrollbar styling */
    .table-wrapper::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 6px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

.right-align {
    text-align: right !important;
}

.center-align {
    text-align: center !important;
}

.left-align {
    text-align: left !important;
}

.pending-cell {
    background-color: #F5B7B1 !important;
    color: #000000 !important;
    font-weight: bold !important;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body>


    <div class="container-fluid">
        
          <div class="controls">
             <button id="reload-btn">Synchronization</button>
             <button id="refresh-btn">Refresh Table</button>
             <button id="clear-filters-btn">Clear Filters</button>
             <button id="export-btn">Export To Excel</button>
        </div>
        
        <div class="spreadsheet-container">
            <div class="table-wrapper">
            <table id="spreadsheet"></table>
              </div>
        </div>
        
        
  
    </div>



<input type="text" id="hidden-datepicker" style="position:absolute; opacity:0; z-index:1000; height:1px; width:1px;">



<!-- Modal -->
<div class="modal fade" id="fitSampleModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="formModalLabel">Fit Sample Approval Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="fitSampleForm">
          <div class="mb-3">
            <label for="fit_sample_approval_plan_date" class="form-label">
              Select Date
            </label>
            <input type="date" class="form-control" name="fit_sample_approval_plan_date" id="fit_sample_approval_plan_date" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>

<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



const ROWS = {{ $totalRows }};
const COLUMNS = ['id','KDPL', 'BUYER', 'Merchant', 'Brand', 'Main Style Category','Style Name','Garment Color','Fabric Color','Order Qty','Sam','Taxable Amount','CMOHP','Rate','PO No.','Embroidery',
'Washing','Print','Fit Sample Approval Plan','Fit Sample Approval Plan Actual','PP Yardage Inhouse Date','PP Sample Dispatch Plan','PP Sample Dispatch Plan Actual',
'PP Sample Approval Plan','PP Sample Approval Plan Actual','TOP','Fabric Inhouse Date Plan','Fabric Inhouse Date Plan Actual','Fabric Inhouse Qty','FPT Status','GPT Status',
'Production File Release Date Plan','Production File Release Date Actual','Cut Qty','Current Status','Shipment Date','Shipment Month','Rejection Pcs','Shipment Qty','Balance To Ship Qty'];


// Dynamic columns from backend (Laravel)
const dynamicColumns = @json($classificationNamesArray);

// Append to existing COLUMNS array
COLUMNS.push(...dynamicColumns);
const COLS = COLUMNS.length;


console.log(COLUMNS);


const dateColumns = ['date', 'due_date'];
      const nonEditableColumns = {
                    'id': 'true',
                    'KDPL': 'true',
                    'BUYER': 'true',
                    'Merchant': 'true',
                    'Brand': 'true',
                    'Main Style Category': 'true',
                    'Style Name': 'true',
                    'Garment Color': 'true',
                    'Fabric Color': 'true',  
                    'Order Qty': 'true',
                    'Sam': 'true', 
                    'Taxable Amount':'true',
                    'CMOHP':'true',
                    'Rejection Pcs':'true',
                    'Rate': 'true',
                    'PO No.': 'true',
                    'Fabric Inhouse Qty':'true',
                    'Cut Qty':'true',
                    'Current Status':'true',
                    'Shipment Date':'true',
                    'Shipment Month':'true',
                    'Shipment Qty':'true',
                    'Balance To Ship Qty':'true'
                    
                    };
                    
                    
    dynamicColumns.forEach(column => {
    nonEditableColumns[column] = 'true';
    });          
                    
                    
                    
const COLUMN_MAP = {
    'id': 'id',
    'KDPL': 'order_no',
    'BUYER': 'buyer',
    'Merchant': 'merchant',
    'Brand': 'brand',
    'Main Style Category': 'main_style_category',
    'Style Name': 'style',
    'Garment Color': 'garment_color',
    'Fabric Color': 'fabric_color', 
    'Order Qty': 'order_qty',
    'Sam': 'sam', 
    'Taxable Amount':'total_sale_taxable_amount',
    'CMOHP':'cmohp',
    'Rejection Pcs':'rejection_pcs',
    'Rate': 'rate',
    'PO No.': 'po_no',
    'Embroidery': 'Embroidery',
    'Washing':'WashTypeId', 
    'Print': 'print',
    'Fit Sample Approval Plan': 'fit_sample_approval_plan',
    'Fit Sample Approval Plan Actual': 'fit_sample_approval_actual',
    'PP Yardage Inhouse Date':'pp_yardage_inhouse',
    'PP Sample Dispatch Plan':'pp_sample_dispatch_plan',
    'PP Sample Dispatch Plan Actual':'pp_sample_dispatch_plan_actual',
    'PP Sample Approval Plan':'pp_sample_approval_plan',
    'PP Sample Approval Plan Actual':'pp_sample_approval_plan_actual',
    'TOP':'top',
    'Fabric Inhouse Date Plan':'fabric_inhouse_date_plan',
    'Fabric Inhouse Date Plan Actual':'fabric_inhouse_date_plan_actual',
    'Fabric Inhouse Qty':'fabric_inhouse_qty',
    'FPT Status':'fpt_status',
    'GPT Status':'gpt_status',
    'Production File Release Date Plan':'production_file_release_date_plan',
    'Production File Release Date Actual':'production_file_release_date_plan_actual',
    'Cut Qty':'cut_qty',
    'Current Status':'current_status',
    'Shipment Date':'shipment_date',
    'Shipment Month':'shipment_month',
    'Shipment Qty':'shipment_qty',
    'Balance To Ship Qty':'bal_to_ship_qty'
    
};

 const yesNoList = [
    {value: "YES", label: "YES"},
    {value: "NO", label: "NO"}
];
       
                    
    //         const FitSampleApprovalPlanList = [
    // {fit_sample_approval_plan: "1", fit_sample_approval_plan_name: "Approved"},
    // {fit_sample_approval_plan: "2", fit_sample_approval_plan_name: "Rejected"},
    // {fit_sample_approval_plan: "3", fit_sample_approval_plan_name: "Pending"},
    // {fit_sample_approval_plan: "4", fit_sample_approval_plan_name: "NA"}]
    
    
        const FitSampleApprovalPlanList = [
        {value: "Approved", label: "Approved"},
        {value: "Rejected", label: "Rejected"},
        {value: "Pending", label: "Pending"},
        {value: "NA", label: "NA"}]
        
        
              const InhouseStatusList = [
        {value: "IH", label: "IH"},
        {value: "Released", label: "Released"}, 
        {value: "Done", label: "Done"}, 
        {value: "Pending", label: "Pending"},
        {value: "TBA", label: "TBA"},
        {value: "NA", label: "NA"}]  
    
    
         const StatusList = [
        {value: "Pass", label: "Pass"},
        {value: "Fail", label: "Fail"},
        {value: "Pending", label: "Pending"},
        {value: "Sent", label: "Sent"},
        {value: "NA", label: "NA"}]


// Sample data for demonstration
let sampleData = {};


const yesNoMap = {
    'YES': 'YES',
    'NO': 'NO'
};


    // Buyer list mapping IDs to names
        const buyerList = [@json($buyerList)];
        
            const buyerMap = {};
            
            // Flatten the nested array and build the map
            buyerList[0].forEach(buyer => {
            buyerMap[buyer.ac_code] = buyer.ac_short_name;
            });
            

            
            
                const washTypeList = [@json($washTypeList)];
                
                const washMap = {};
                // Build the washing options map correctly
                washTypeList[0].forEach(wash => {
                washMap[wash.WashTypeId] = wash.WashTypeName;
                });
            
            console.log(buyerMap);
            
            const FitSampleApprovalPlanMap = {};
            FitSampleApprovalPlanList.forEach(item => {
            FitSampleApprovalPlanMap[item.value] = item.label;
            });
            
            
                 const InHouseMap = {};
            InhouseStatusList.forEach(item => {
            InHouseMap[item.value] = item.label;
            });
            
            
            const StatusMap = {};
            StatusList.forEach(item => {
            StatusMap[item.value] = item.label;
            });
            
            

// Dropdown options for columns (example)
    const dropdownOptions = {
    'category': @json($buyerList),
    'Embroidery': yesNoList,
    'Print': yesNoList,
    'Washing':  @json($washTypeList),
    'Fit Sample Approval Plan':FitSampleApprovalPlanList,
    'Fit Sample Approval Plan Actual':FitSampleApprovalPlanList,  
    'PP Yardage Inhouse Date':InhouseStatusList,
    'PP Sample Dispatch Plan':FitSampleApprovalPlanList, 
    'PP Sample Dispatch Plan Actual':FitSampleApprovalPlanList, 
    'PP Sample Approval Plan':FitSampleApprovalPlanList, 
    'PP Sample Approval Plan Actual':FitSampleApprovalPlanList,
    'TOP':StatusList,
    'Fabric Inhouse Date Plan':InhouseStatusList,
    'Fabric Inhouse Date Plan Actual':InhouseStatusList,
    'FPT Status':InhouseStatusList, 
    'GPT Status':InhouseStatusList,
    'Production File Release Date Plan':InhouseStatusList,
    'Production File Release Date Actual':InhouseStatusList,
    
    };
    
//     dynamicColumns.forEach(column => {
//     dropdownOptions[column] = InhouseStatusList;
// });


 let saveTimeout = null;
   const SAVE_DELAY = 1000;
    let pendingChanges = [];

    // Save status element
    const saveStatus = $('<div class="save-status">No changes to save</div>');
    $('body').append(saveStatus);
    
    

    
       function showSaveStatus(message, type) {
        saveStatus.text(message).removeClass('saving saved error');
        if (type) saveStatus.addClass(type);
        saveStatus.show();
        
        if (type !== 'saving') {
            setTimeout(() => {
                saveStatus.fadeOut();
            }, 3000);
        }
    } 
    
    
    
    
    
   function saveChangesToServer() {
    if (pendingChanges.length === 0) return;
    
    showSaveStatus('Saving changes...', 'saving');
    
    const changesToSend = [...pendingChanges];
    pendingChanges = [];
    
    // Send changes to Laravel backend using jQuery
    $.ajax({
        url: '/saveExcelData',
        method: 'POST',
        data: JSON.stringify({ changes: changesToSend }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                console.log("Changes saved successfully:", changesToSend);
                showSaveStatus('Changes saved successfully!', 'saved');
            } else {
                throw new Error(data.message || "Server returned error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Save error:", error);
            showSaveStatus('Error saving changes: ' + error, 'error');
            
            // Add the failed changes back to pending
            pendingChanges = [...changesToSend, ...pendingChanges];
        }
    });
}

function addPendingChange(row, col, value, oldValue) {
    const displayColumnName = COLUMNS[col];

    // Skip read-only ID column
    if (displayColumnName === 'id') {
        return;
    }

    // Get actual DB column name from map, or fallback to display name
    const tableColumnName = COLUMN_MAP[displayColumnName] || displayColumnName;

    console.log('Column mapping:', displayColumnName, '->', tableColumnName);

    // Remove any existing change for the same row/column
    pendingChanges = pendingChanges.filter(change => 
        !(change.row === row && change.column === tableColumnName)
    );

    // Get row ID (primary key value)
    const rowId = getRowId(row);

    // Push new change
    pendingChanges.push({
        row: row,
        row_id: rowId,
        column: tableColumnName,
        value: value,
        old_value: oldValue
    });

    showSaveStatus(`${pendingChanges.length} change(s) pending save`, 'saving');

    // Debounced auto-save
    if (saveTimeout) clearTimeout(saveTimeout);
    saveTimeout = setTimeout(saveChangesToServer, SAVE_DELAY);
}


// Function to get the ID for a specific row
function getRowId(rowNumber) {
    // Assuming your data has an ID field for each row
    // This will depend on how your data is structured
    if (sampleData.id && sampleData.id[rowNumber - 1] !== undefined) {
        return sampleData.id[rowNumber - 1];
    }
    
    // Fallback: use row number if no ID exists
    return rowNumber;
}


// Convert 0-based column index to Excel letters: 0 -> A, 25 -> Z, 26 -> AA
function getColLetter(index) {
  let s = "";
  while (index >= 0) {
    s = String.fromCharCode((index % 26) + 65) + s;
    index = Math.floor(index / 26) - 1;
  }
  return s;
}

// Check if a string is a valid number
function isNumeric(value) {
  return !isNaN(parseFloat(value)) && isFinite(value);
}

// Check if a string is a valid date
function isDate(value) {
  return !isNaN(Date.parse(value));
}

// Show loading indicator
function showLoading() {
  if ($('#loading-indicator').length === 0) {
    $('body').append('<div id="loading-indicator" class="loading-indicator">Loading data...</div>');
  } else {
    $('#loading-indicator').show();
  }
}

// Hide loading indicator
function hideLoading() {
  $('#loading-indicator').hide();
}

function fetchDataFromServer() {
  showLoading();

  return fetch('/get_report_data') 
    .then(response => {
      if (!response.ok) throw new Error("Network error");
      return response.json();
    })
    .then(data => {
      hideLoading();
      
      console.log(data);
      
      
      sampleData = data;
      return data;
    })
    .catch(error => {
      hideLoading();
      console.error("Fetch error:", error);
    });
}



$(function() {
    
    
        $('#clear-filters-btn').on('click', function() {
        // Clear the filter state
        for (const col in filterState) {
            delete filterState[col];
        }
        
        // Apply filters (which will show all rows)
        applyFilters();
        
        // Update header styles
        updateHeaderFilterStyles();
        
        console.log("All filters cleared");
    });
    
    
    function isPendingValue(value) {
    if (value === null || value === undefined || value === '') return false;
    return value.toString().trim().toLowerCase() === 'pending';
}
    
    
  const $table = $('#spreadsheet');
  
  // Add reload button event listener
  fetchDataFromServer().then(data => {
    // Update the table with new data
    buildTable($table, data)
    applyFilters();
    updateHeaderFilterStyles();
  });

  // Function to update table with new data
            function buildTable($table, data) {
                $table.empty(); // Clear existing table 
                
                // Build header row with filter icon placeholder
                let headerRow = '<thead><tr><th class="row-header">Sr.No.</th>';
                for(let c=0; c<COLS; c++) {
                    headerRow += `<th data-col="${c}" class="col-header" tabindex="0">
                                    <div class="col-header-content">${COLUMNS[c]}</div>
                                    <svg class="filter-icon" viewBox="0 0 24 24" aria-label="Filter" role="img" tabindex="-1">
                                        <path d="M3 5h18l-7 7v7l-4-4v-3L3 5z" />
                                    </svg>
                                </th>`;
                }
                headerRow += '</tr></thead>';
                $table.append(headerRow);
                

                // Build data rows with editable cells
                let bodyHtml = '<tbody>';
                for(let r=1; r<=ROWS; r++) {
                    bodyHtml += `<tr><td class="row-header">${r}</td>`;
                    for(let c=0; c<COLS; c++) {
                        // Add sample data for demonstration
                        let cellContent = "";
                        const colName = COLUMNS[c];
                        if (data[colName] && data[colName][r-1] !== undefined) {
                            cellContent = data[colName][r-1];
                            
                            
                                cellAlignment = getCellAlignment(cellContent, colName);
                            
                            if (['Order Qty', 'Rate', 'Fabric Inhouse Qty', 'Cut Qty', 'Shipment Qty', 'Balance To Ship Qty'].includes(colName) ||
                    dynamicColumns.includes(colName)) {
                    cellContent = indian_number_format(cellContent);
                }
                            
                            
                            // If this is the category column, convert ID to name if needed
                            if (colName === 'category'  && buyerMap[cellContent]) {
                                cellContent = buyerMap[cellContent];
                            }
                            if (colName === 'Embroidery' && yesNoMap[cellContent]) {
                            cellContent = yesNoMap[cellContent];
                            }
                             if (colName === 'Print' && yesNoMap[cellContent]) {
                            cellContent = yesNoMap[cellContent];
                            }
                            if (colName === 'Washing' && washMap[cellContent]) {
                            cellContent = washMap[cellContent];
                            }  
                            // if (colName === 'Fit Sample Approval Plan' && FitSampleApprovalPlanMap[cellContent]) {
                            // cellContent = FitSampleApprovalPlanMap[cellContent];
                            // }  
                            
                           
                            
                           
                        }
                        
                        // Check if this column should have a dropdown
                            const hasDropdown = dropdownOptions.hasOwnProperty(colName);
                            const isDateCell = dateColumns.includes(colName); // use array for multiple date fields
                            const isEditable = !nonEditableColumns[colName] && !hasDropdown && !isDateCell;
                            
                            let cellClass = '';
                            let cellExtras = '';
                            let cellClass1='';
                            
                            // Assign class and extras based on type
                            if (hasDropdown) {
                            cellClass = 'dropdown-cell';
                            cellExtras = '<div class="dropdown-arrow"></div>';
                            } else if (isDateCell) {
                            cellClass = 'date-cell';
                            // No extra markup needed; handled via JS
                            }
                            
                            
                                if (cellAlignment === 'right') {
                                cellClass += cellClass ? ' right-align' : 'right-align';
                                } else if (cellAlignment === 'center') {
                                cellClass += cellClass ? ' center-align' : 'center-align';
                                } 
                                
                                 isPending = isPendingValue(cellContent);
                                
                            if (isPending) {
                            cellClass += cellClass ? ' pending-cell' : 'pending-cell';
                            }

                            
                            
                            bodyHtml += `<td  contenteditable="${isEditable}" 
                            data-row="${r}" 
                            data-col="${c}" 
                            class="${cellClass}">${cellContent}${cellExtras}</td>`;

                    }
                    bodyHtml += '</tr>';
                }
                bodyHtml += '</tbody>';
                $table.append(bodyHtml);

                // Add dropdown menus for dropdown cells
              /*  for (const colName in dropdownOptions) {
                    if (dropdownOptions.hasOwnProperty(colName)) {
                        const colIndex = COLUMNS.indexOf(colName);
                        if (colIndex === -1) continue;
                        
                        const options = dropdownOptions[colName];
                        let dropdownHtml = `<div class="dropdown-menu" data-col="${colIndex}">`;

                        options.forEach(option => {
                            // Handle both object or string formats
                            if (typeof option === 'object') {
                                if (colName === 'category') {
                                // For category dropdown (buyer objects)
                                dropdownHtml += `<div data-value="${option.ac_code}">${option.ac_short_name}</div>`;
                                } else if (colName === 'Embroidery') {
                                // For item_code dropdown (item objects)
                                dropdownHtml += `<div data-value="${option.value}">${option.lable}</div>`;
                                } else if (colName === 'Print') {
                                // For item_code dropdown (item objects)
                                dropdownHtml += `<div data-value="${option.value}">${option.lable}</div>`;
                                }  else if (colName === 'Washing') {
                                // For item_code dropdown (item objects)
                                dropdownHtml += `<div data-value="${option.WashTypeId}">${option.WashTypeName}</div>`;
                                }  else if (colName === 'Fit Sample Approval Plan') {
                                // For item_code dropdown (item objects)
                                dropdownHtml += `<div data-value="${option.fit_sample_approval_plan}">${option.fit_sample_approval_plan_name}</div>`;
                                }
                                
                                
                            } else {
                                dropdownHtml += `<div data-value="${option}">${option}</div>`;
                            }
                        });

                        dropdownHtml += '</div>';
                        $('body').append(dropdownHtml);
                    }
                } */
                
                
                
                for (const colName in dropdownOptions) {
    if (!dropdownOptions.hasOwnProperty(colName)) continue;
    
    const colIndex = COLUMNS.indexOf(colName);
    if (colIndex === -1) continue;
    
    const options = dropdownOptions[colName];
    let dropdownHtml = `<div class="dropdown-menu" data-col="${colIndex}">`;

    // Use a switch statement for even cleaner code
    switch(colName) {
        case 'Embroidery':
        case 'Print':
            yesNoList.forEach(item => {
                dropdownHtml += `<div data-value="${item.value}">${item.label}</div>`;
            });
      
            break;
            
        case 'Washing':
            options.forEach(item => {
                dropdownHtml += `<div data-value="${item.WashTypeName}">${item.WashTypeName}</div>`;
            });
            break;
            
        case 'category':
            options.forEach(item => {
                dropdownHtml += `<div data-value="${item.ac_code}">${item.ac_short_name}</div>`;
            });
            break;
            
        default:
            // Handle simple string arrays
            
            //  if (dynamicColumns.includes(colName)) {
            //     options.forEach(item => {
            //         dropdownHtml += `<div data-value="${item.value}">${item.label}</div>`;
            //     });

            // } else {
               
            //     options.forEach(option => {
            //         const value = typeof option === 'object' ? option.value : option;
            //         const label = typeof option === 'object' ? option.label : option;
            //         dropdownHtml += `<div data-value="${value}">${label}</div>`;
            //     });
            // }
            options.forEach(option => {
                const value = typeof option === 'object' ? option.value : option;
                const label = typeof option === 'object' ? option.label : option;
                dropdownHtml += `<div data-value="${value}">${label}</div>`;
            });   
            
            
    }

    dropdownHtml += '</div>';
    $('body').append(dropdownHtml);
}
                
                
                
            }




  // Handle dropdown cell click
  let $activeDropdownCell = null;

  $table.on('click', '.dropdown-cell', function(e) {
    const $cell = $(this);
    const colIndex = $cell.data('col');
      const rowIndex = $cell.data('row');
    const $dropdown = $(`.dropdown-menu[data-col="${colIndex}"]`);
    
     $dropdown.data('row', rowIndex);

    $activeDropdownCell = $cell; // Save reference

    const offset = $cell.offset();
    $dropdown.css({
      top: offset.top + $cell.outerHeight(),
      left: offset.left,
      width: $cell.outerWidth()
    }).show();

    $('.dropdown-menu').not($dropdown).hide();
    e.stopPropagation();
  });
  
  // Handle dropdown option selection
  $(document).on('click', '.dropdown-menu div', function() {
            const value = $(this).data('value');
            const label = $(this).text();
            const colIndex = $(this).parent().data('col');

            // If multiple cells are selected
            if ($('td.selected').length > 0) {
                $('td.selected').each(function() {
                    if ($(this).data('col') == colIndex) {
                        const $cell = $(this);
                        const oldValue = $cell.data('value') || $cell.data('old-value') || $cell.text();
                        
                        $cell.text(label);
                        $cell.data('value', value);
                        
                        // Add to pending changes
                        addPendingChange(
                            $cell.data('row'), 
                            colIndex, 
                            value, 
                            oldValue
                        );
                        $cell.data('old-value', value);
                    }
                });
            } else if ($activeDropdownCell) {
                const $cell = $activeDropdownCell;
                const oldValue = $cell.data('value') || $cell.data('old-value') || $cell.text();
                
                $cell.text(label);
                $cell.data('value', value);
                
                // Add to pending changes
                addPendingChange(
                    $cell.data('row'), 
                    colIndex, 
                    value, 
                    oldValue
                );
                $cell.data('old-value', value);
            }

            $('.dropdown-menu').hide();
            $activeDropdownCell = null;

            applyFilters();
            updateHeaderFilterStyles();
            
        });
  
  
  
  // Hide dropdowns when clicking elsewhere
  $(document).on('click', function() {
    $('.dropdown-menu').hide();
  });

 
  // Drag fill handle (reused)
  const $dragHandle = $('<div class="drag-handle"></div>');
  $('body').append($dragHandle);

  let dragStartCell = null;
  let dragCol = null;
  let dragRowStart = null;
  let dragRowEnd = null;
  let dragRowCurrent = null;
  let dragValue = null;
  let dragging = false;

  // Show drag handle on cell focus
  $table.on('focus', 'td[contenteditable]', function() {
    const $cell = $(this);
    const offset = $cell.offset();
    const width = $cell.outerWidth();
    const height = $cell.outerHeight();

    $dragHandle.show().css({
      top: offset.top + height - 10,
      left: offset.left + width - 10
    });

    dragStartCell = $cell;
    dragCol = $cell.data('col');
    dragRowStart = $cell.data('row');
    dragValue = $cell.text();
    
  
    
  });

  // Hide drag handle on blur if not dragging
  $table.on('blur', 'td[contenteditable]', function() {
    if (!dragging) $dragHandle.hide();
    
  });
  
     $table.on('blur', 'td[contenteditable="true"]', function() {
         
           if (!dragging) $dragHandle.hide();
            const $cell = $(this);
            const row = $cell.data('row');
            const col = $cell.data('col');
            const newValue = $cell.text().trim();
            const oldValue = $cell.data('old-value') || '';
            
            console.log('test'+col);
            
            if (newValue !== oldValue) {
                addPendingChange(row, col, newValue, oldValue);
                $cell.data('old-value', newValue);
            }
        });
  
  
  
  
  
// Dropdown down  Drag code start here 11-09-2025
  
isDragging=false;

// Mousedown on entire dropdown cell to start dragging
$('#spreadsheet').on('mousedown', '.dropdown-cell', function (e) {
    e.preventDefault(); // Prevent text selection

    $startCell = $(this);
    isDragging = true;

    dragValue = $startCell.data('value') || $startCell.text();
    startRow = $startCell.data('row');
    startCol = $startCell.data('col');
    dragText =  $startCell.text();

    clearSelection();

    $(document).on('mousemove.dropdownDrag', function (e) {
        if (!isDragging) return;

        const $target = $(document.elementFromPoint(e.clientX, e.clientY));
        if (!$target.is('td.dropdown-cell')) return;

        const row = $target.data('row');
        const col = $target.data('col');
        if (col !== startCol) return;

        const from = Math.min(startRow, row);
        const to = Math.max(startRow, row);

        clearSelection();

        for (let r = from; r <= to; r++) {
            const $cell = $(`td[data-row="${r}"][data-col="${startCol}"]`);
                $cell
                .addClass('selected')
                .text(dragText)
                .data('value', dragText);

            addPendingChange(r, startCol, dragText, $cell.data('old-value') || '');
            $cell.data('old-value', dragText);
        }
    });
});

// Mouseup to stop dragging
$(document).on('mouseup', function () {
    if (isDragging) {
        isDragging = false;
        $(document).off('mousemove.dropdownDrag');
        clearSelection();
        $startCell?.focus();
    }
});

  
  
  
//Dropdown Drag code End  
  
  

  // Prevent text selection on drag handle drag
  $(document).on('mousedown', e => {
    if ($(e.target).is('.drag-handle')) e.preventDefault();
  });

  // Drag start
  $dragHandle.on('mousedown', function(e) {
    e.preventDefault();
    dragging = true;
    dragRowEnd = dragRowStart;
    dragRowCurrent = dragRowStart;
    clearSelection();
    dragStartCell.addClass('selected');
  
  });
  
const columnMap = Object.fromEntries(COLUMNS.map((col, index) => [index, col]));

  // Drag move (vertical only)
let selectedRows = [];

$(document).on('mousemove', function(e) {
  if (!dragging) return;

  const $target = $(document.elementFromPoint(e.clientX, e.clientY));
  if (!$target.is('td[contenteditable]')) return;
  if ($target.data('col') !== dragCol) return;

  const row = $target.data('row');
  if (row !== dragRowCurrent) {
    dragRowCurrent = row;
    dragRowEnd = row;
    clearSelection();

    selectedRows = []; // reset
    const columnName = columnMap[dragCol];

    const from = Math.min(dragRowStart, dragRowEnd);
    const to = Math.max(dragRowStart, dragRowEnd);

    for (let r = from; r <= to; r++) {
      const $cell = $(`td[data-row="${r}"][data-col="${dragCol}"]`);
      $cell.addClass('selected');

      const rowId = getRowId(r); // Make sure this function exists
      selectedRows.push({
        rowId: rowId,
        value: dragValue,
        col: COLUMN_MAP[columnName],
        columnName:columnName
      });
      
      pendingChanges.push({
        row: 0,
        row_id: rowId, // Add the actual ID for WHERE clause
        column: COLUMN_MAP[columnName],  
        value: dragValue,
        old_value: 0
    });
    
       addPendingChange(r, dragCol, dragValue, 0); // change this line 01-09-2025 time 14:22 2:22PM
      
    }
    
    console.log(selectedRows);
  }
});




  // Drag end fill
  $(document).on('mouseup', function(e) {
    if (!dragging) return;
    dragging = false;

    const from = Math.min(dragRowStart, dragRowEnd);
    const to = Math.max(dragRowStart, dragRowEnd);

    for(let r=from; r<=to; r++) {
      $(`td[data-row="${r}"][data-col="${dragCol}"]`).text(dragValue);
    }

    clearSelection();
    applyFilters();
    updateHeaderFilterStyles();
    $dragHandle.hide();
    dragStartCell.focus();
  });

  function clearSelection() {
    $('td.selected').removeClass('selected');
  }

  // Debounce utility
  function debounce(func, wait) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  // Filter popup DOM template
  function buildFilterPopup(allValues, selectedValues, col) {
    // Check if column contains numbers or dates
    let columnType = 'text';
    let hasNumbers = true;
    let hasDates = true;
    
    allValues.forEach(val => {
      if (val !== '' && !isNumeric(val)) hasNumbers = false;
      if (val !== '' && !isDate(val)) hasDates = false;
    });
    
    if (hasDates) columnType = 'date';
    else if (hasNumbers) columnType = 'number';
    
    let html = `
      <div class="filter-tabs">
        <div class="filter-tab active" data-tab="values">Values</div>
        <div class="filter-tab" data-tab="number">Number Filters</div>
        <div class="filter-tab" data-tab="date">Date Filters</div>
        <div class="filter-tab" data-tab="custom">Custom Filter</div>
      </div>
      
      <div class="filter-tab-content active" data-tab="values">
        <input type="search" placeholder="Search..." class="filter-search" autocomplete="off" />
        <div class="values-list" tabindex="0">
          <label><input type="checkbox" class="filter-select-all" checked /> Select All</label>
    `;
    
    allValues.forEach(val => {
      const checked = selectedValues.has(val) ? 'checked' : '';
      const displayVal = val === '' ? '(Blank)' : val;
      html += `<label><input type="checkbox" class="filter-value" value="${val}" ${checked} /> ${displayVal}</label>`;
    });
    
    html += `</div>
      </div>
      
      <div class="filter-tab-content" data-tab="number">
        <div class="number-filter-options">
          <select class="number-filter-type">
            <option value="equals">Equals</option>
            <option value="notEqual">Does not equal</option>
            <option value="greaterThan">Greater than</option>
            <option value="lessThan">Less than</option>
            <option value="greaterThanOrEqual">Greater than or equal to</option>
            <option value="lessThanOrEqual">Less than or equal to</option>
            <option value="between">Between</option>
          </select>
          <input type="number" class="number-filter-value1" placeholder="Value">
          <input type="number" class="number-filter-value2" placeholder="And" style="display: none;">
        </div>
      </div>
      
      <div class="filter-tab-content" data-tab="date">
        <div class="date-filter-options">
          <select class="date-filter-type">
            <option value="equals">Equals</option>
            <option value="notEqual">Does not equal</option>
            <option value="after">After</option>
            <option value="before">Before</option>
            <option value="between">Between</option>
            <option value="tomorrow">Tomorrow</option>
            <option value="today">Today</option>
            <option value="yesterday">Yesterday</option>
            <option value="nextWeek">Next Week</option>
            <option value="thisWeek">This Week</option>
            <option value="lastWeek">Last Week</option>
            <option value="nextMonth">Next Month</option>
            <option value="thisMonth">This Month</option>
            <option value="lastMonth">Last Month</option>
          </select>
          <input type="date" class="date-filter-value1">
          <input type="date" class="date-filter-value2" style="display: none;">
        </div>
      </div>
      
      <div class="filter-tab-content" data-tab="custom">
        <div class="custom-filter-container">
          <div class="custom-filter-row">
            <select class="custom-filter-operator1">
              <option value="equals">Equals</option>
              <option value="notEqual">Does not equal</option>
              <option value="contains">Contains</option>
              <option value="notContains">Does not contain</option>
              <option value="startsWith">Begins with</option>
              <option value="endsWith">Ends with</option>
              <option value="greaterThan">Greater than</option>
              <option value="lessThan">Less than</option>
            </select>
            <input type="text" class="custom-filter-value1">
          </div>
          <div class="custom-filter-and" style="display: none;">
            <select class="custom-filter-relation">
              <option value="and">And</option>
              <option value="or">Or</option>
            </select>
          </div>
          <div class="custom-filter-row custom-filter-second" style="display: none;">
            <select class="custom-filter-operator2">
              <option value="equals">Equals</option>
              <option value="notEqual">Does not equal</option>
              <option value="contains">Contains</option>
              <option value="notContains">Does not contain</option>
              <option value="startsWith">Begins with</option>
              <option value="endsWith">Ends with</option>
              <option value="greaterThan">Greater than</option>
              <option value="lessThan">Less than</option>
            </select>
            <input type="text" class="custom-filter-value2">
          </div>
          <div class="custom-filter-add">+ Add condition</div>
        </div>
      </div>
      
      <div class="filter-popup-buttons">
        <button class="filter-ok">OK</button>
        <button class="filter-cancel">Cancel</button>
        <button class="filter-clear">Clear</button>
      </div>`;
    
    return {html, columnType};
  }

  let openFilterCol = null;
  let $filterPopup = null;
  const filterState = {}; // col => { type: 'values'|'number'|'date'|'custom', config: ... }

  // Open filter popup on clicking filter icon
  $table.on('click', '.filter-icon', function(e) {
    e.stopPropagation();

    // Close existing popup
    if ($filterPopup) {
      $filterPopup.remove();
      $filterPopup = null;
      openFilterCol = null;
    }

    const $th = $(this).closest('th');
    const col = $th.data('col');

    openFilterCol = col;

    // Get all unique values in column (including filtered rows)
    // let allValuesSet = new Set();
    // $table.find(`td[data-col="${col}"]`).each(function() {
    //   allValuesSet.add($(this).text().trim());
    // });
      // MODIFIED: Get all unique values from VISIBLE rows only
  let allValuesSet = new Set();
  $table.find('tbody tr:visible').each(function() {
    const value = $(this).find(`td[data-col="${col}"]`).text().trim();
    allValuesSet.add(value);
  });
    
    
    if (!allValuesSet.has('')) allValuesSet.add('');
    const allValues = Array.from(allValuesSet).sort();

    // Get selected values or select all if no filter yet
    let selectedValues = filterState[col] && filterState[col].type === 'values' ? 
      new Set(filterState[col].selectedValues) : new Set(allValues);

    // Make sure to include new unique values added by edits
    allValues.forEach(v => {
      if (!selectedValues.has(v)) selectedValues.add(v);
    });

    const {html, columnType} = buildFilterPopup(allValues, selectedValues, col);
    $filterPopup = $('<div class="filter-popup" tabindex="0"></div>');
    $filterPopup.html(html);
    
    
        // New function For popup overlapp issue 05-09-2025
        const offset = $th.offset();
        const leftPosition = offset.left;
        
        // Calculate if the popup would extend beyond the viewport
        const viewportWidth = $(window).width();
        const popupWidth = 250; // Same as CSS width
        
        // Adjust position if it would extend beyond right edge
        const adjustedLeft = (leftPosition + popupWidth > viewportWidth) 
        ? viewportWidth - popupWidth - 10 
        : leftPosition;
        
        $filterPopup.css({
        top: offset.top + $th.outerHeight(),
        left: adjustedLeft,
        position: 'fixed' // Use fixed positioning to avoid z-index issues
        });
        
        // Instead of appending to the th, append to body for better positioning
        $('body').append($filterPopup);
        
        //this old line before sticky function
        
        //$th.append($filterPopup);
    
    
    
    // Set the active tab based on existing filter state
    if (filterState[col]) {
      const activeTab = filterState[col].type;
      $filterPopup.find('.filter-tab').removeClass('active');
      $filterPopup.find(`.filter-tab[data-tab="${activeTab}"]`).addClass('active');
      $filterPopup.find('.filter-tab-content').removeClass('active');
      $filterPopup.find(`.filter-tab-content[data-tab="${activeTab}"]`).addClass('active');
      
      // Populate filter values if they exist
      if (activeTab === 'number' && filterState[col].numberFilter) {
        const nf = filterState[col].numberFilter;
        $filterPopup.find('.number-filter-type').val(nf.type);
        $filterPopup.find('.number-filter-value1').val(nf.value1);
        if (nf.value2) {
          $filterPopup.find('.number-filter-value2').val(nf.value2).show();
        }
      } else if (activeTab === 'date' && filterState[col].dateFilter) {
        const df = filterState[col].dateFilter;
        $filterPopup.find('.date-filter-type').val(df.type);
        $filterPopup.find('.date-filter-value1').val(df.value1);
        if (df.value2) {
          $filterPopup.find('.date-filter-value2').val(df.value2).show();
        }
      } else if (activeTab === 'custom' && filterState[col].customFilter) {
        const cf = filterState[col].customFilter;
        $filterPopup.find('.custom-filter-operator1').val(cf.operator1);
        $filterPopup.find('.custom-filter-value1').val(cf.value1);
        if (cf.hasSecondCondition) {
          $filterPopup.find('.custom-filter-relation').val(cf.relation);
          $filterPopup.find('.custom-filter-operator2').val(cf.operator2);
          $filterPopup.find('.custom-filter-value2').val(cf.value2);
          $filterPopup.find('.custom-filter-and, .custom-filter-second').show();
          $filterPopup.find('.custom-filter-add').hide();
        }
      }
    }
    
    // Hide tabs that aren't relevant to the column type
    if (columnType !== 'number') {
      $filterPopup.find('.filter-tab[data-tab="number"]').hide();
    }
    if (columnType !== 'date') {
      $filterPopup.find('.filter-tab[data-tab="date"]').hide();
    }

    $filterPopup.find('input.filter-search').focus();

    // Tab switching
    $filterPopup.on('click', '.filter-tab', function() {
      const tab = $(this).data('tab');
      $filterPopup.find('.filter-tab').removeClass('active');
      $(this).addClass('active');
      $filterPopup.find('.filter-tab-content').removeClass('active');
      $filterPopup.find(`.filter-tab-content[data-tab="${tab}"]`).addClass('active');
    });

    // Update Select All checkbox logic
    function updateSelectAllCheckbox() {
      const $visibleCheckboxes = $filterPopup.find('.filter-value:visible');
      const $checkedBoxes = $visibleCheckboxes.filter(':checked');
      const $selectAll = $filterPopup.find('.filter-select-all');

      // If no visible checkboxes, disable Select All
      if ($visibleCheckboxes.length === 0) {
        $selectAll.prop('checked', false).prop('indeterminate', false).prop('disabled', true);
        return;
      } else {
        $selectAll.prop('disabled', false);
      }

      if ($checkedBoxes.length === $visibleCheckboxes.length) {
        $selectAll.prop('checked', true).prop('indeterminate', false);
      } else if ($checkedBoxes.length === 0) {
        $selectAll.prop('checked', false).prop('indeterminate', false);
      } else {
        $selectAll.prop('indeterminate', true);
      }
    }

    // Search input debounce handler
    const debouncedFilterList = debounce(() => {
      const searchTerm = $filterPopup.find('.filter-search').val().toLowerCase();
      $filterPopup.find('.values-list label').each(function() {
        const $label = $(this);
        if ($label.find('.filter-select-all').length) return; // skip select all label
        const text = $label.text().toLowerCase();
        $label.toggle(text.indexOf(searchTerm) !== -1);
      });
      updateSelectAllCheckbox();
    }, 150);

    $filterPopup.on('input', '.filter-search', debouncedFilterList);

    // Select All toggle (only visible checkboxes)
    $filterPopup.on('change', '.filter-select-all', function() {
      const checked = $(this).prop('checked');
      $filterPopup.find('.filter-value:visible').prop('checked', checked);
      updateSelectAllCheckbox();
    });

    // Individual checkbox toggled updates Select All state
    $filterPopup.on('change', '.filter-value', function() {
      updateSelectAllCheckbox();
    });

    // Number filter type change
    $filterPopup.on('change', '.number-filter-type', function() {
      const type = $(this).val();
      if (type === 'between') {
        $filterPopup.find('.number-filter-value2').show();
      } else {
        $filterPopup.find('.number-filter-value2').hide();
      }
    });

    // Date filter type change
    $filterPopup.on('change', '.date-filter-type', function() {
      const type = $(this).val();
      const isRelative = ['tomorrow', 'today', 'yesterday', 'nextWeek', 'thisWeek', 
                         'lastWeek', 'nextMonth', 'thisMonth', 'lastMonth'].includes(type);
      const isBetween = type === 'between';
      
      if (isRelative) {
        $filterPopup.find('.date-filter-value1, .date-filter-value2').hide();
      } else if (isBetween) {
        $filterPopup.find('.date-filter-value1, .date-filter-value2').show();
      } else {
        $filterPopup.find('.date-filter-value1').show();
        $filterPopup.find('.date-filter-value2').hide();
      }
    });

    // Add custom filter condition
    $filterPopup.on('click', '.custom-filter-add', function() {
      $filterPopup.find('.custom-filter-and, .custom-filter-second').show();
      $(this).hide();
    });

    // OK button applies filter
    $filterPopup.on('click', '.filter-ok', function() {
      const activeTab = $filterPopup.find('.filter-tab.active').data('tab');
      
      if (activeTab === 'values') {
        const selected = new Set();
        $filterPopup.find('.filter-value:checked').each(function() {
          selected.add($(this).val());
        });
        filterState[col] = {
          type: 'values',
          selectedValues: Array.from(selected)
        };
      } 
      else if (activeTab === 'number') {
        const type = $filterPopup.find('.number-filter-type').val();
        const value1 = $filterPopup.find('.number-filter-value1').val();
        const value2 = type === 'between' ? $filterPopup.find('.number-filter-value2').val() : null;
        
        filterState[col] = {
          type: 'number',
          numberFilter: { type, value1, value2 }
        };
      }
      else if (activeTab === 'date') {
        const type = $filterPopup.find('.date-filter-type').val();
        const value1 = $filterPopup.find('.date-filter-value1').val();
        const value2 = type === 'between' ? $filterPopup.find('.date-filter-value2').val() : null;
        
        filterState[col] = {
          type: 'date',
          dateFilter: { type, value1, value2 }
        };
      }
      else if (activeTab === 'custom') {
        const operator1 = $filterPopup.find('.custom-filter-operator1').val();
        const value1 = $filterPopup.find('.custom-filter-value1').val();
        const hasSecondCondition = $filterPopup.find('.custom-filter-second').is(':visible');
        let customFilter = { operator1, value1, hasSecondCondition };
        
        if (hasSecondCondition) {
          customFilter.relation = $filterPopup.find('.custom-filter-relation').val();
          customFilter.operator2 = $filterPopup.find('.custom-filter-operator2').val();
          customFilter.value2 = $filterPopup.find('.custom-filter-value2').val();
        }
        
        filterState[col] = {
          type: 'custom',
          customFilter
        };
      }

      applyFilters();
      updateHeaderFilterStyles();

      $filterPopup.remove();
      $filterPopup = null;
      openFilterCol = null;
    });

    // Clear button removes filter
    $filterPopup.on('click', '.filter-clear', function() {
      delete filterState[col];
      applyFilters();
      updateHeaderFilterStyles();
      $filterPopup.remove();
      $filterPopup = null;
      openFilterCol = null;
    });

    // Cancel button closes without changes
    $filterPopup.on('click', '.filter-cancel', function() {
      $filterPopup.remove();
      $filterPopup = null;
      openFilterCol = null;
    });

    // Initialize select all checkbox
    updateSelectAllCheckbox();
  });

  // Close filter popup if clicking outside
  $(document).on('click', function() {
    if ($filterPopup) {
      $filterPopup.remove();
      $filterPopup = null;
      openFilterCol = null;
    }
  });

  // Prevent closing popup when clicking inside it
  $(document).on('click', '.filter-popup', function(e) {
    e.stopPropagation();
  });

  // Apply filters to rows
  function applyFilters() {
    $table.find('tbody tr').show();

    $table.find('tbody tr').each(function() {
      const $tr = $(this);
      let hide = false;

      for (const col in filterState) {
        const filter = filterState[col];
        if (!filter) continue;

        const $cell = $tr.find(`td[data-col="${col}"]`);
        const cellVal = $cell.text().trim();
        
        if (filter.type === 'values') {
          if (!filter.selectedValues.includes(cellVal)) {
            hide = true;
            break;
          }
        } 
        else if (filter.type === 'number') {
          const nf = filter.numberFilter;
          const numVal = parseFloat(cellVal);
          
          if (isNaN(numVal)) {
            hide = true;
            break;
          }
          
          let passes = false;
          const num1 = parseFloat(nf.value1);
          const num2 = nf.value2 ? parseFloat(nf.value2) : null;
          
          switch(nf.type) {
            case 'equals':
              passes = numVal === num1;
              break;
            case 'notEqual':
              passes = numVal !== num1;
              break;
            case 'greaterThan':
              passes = numVal > num1;
              break;
            case 'lessThan':
              passes = numVal < num1;
              break;
            case 'greaterThanOrEqual':
              passes = numVal >= num1;
              break;
            case 'lessThanOrEqual':
              passes = numVal <= num1;
              break;
            case 'between':
              passes = numVal >= Math.min(num1, num2) && numVal <= Math.max(num1, num2);
              break;
          }
          
          if (!passes) {
            hide = true;
            break;
          }
        }
        else if (filter.type === 'date') {
          const df = filter.dateFilter;
          const cellDate = new Date(cellVal);
          
          if (isNaN(cellDate.getTime())) {
            hide = true;
            break;
          }
          
          let passes = false;
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          
          const tomorrow = new Date(today);
          tomorrow.setDate(tomorrow.getDate() + 1);
          
          const yesterday = new Date(today);
          yesterday.setDate(yesterday.getDate() - 1);
          
          // Get start of week (Monday)
          const startOfWeek = new Date(today);
          startOfWeek.setDate(today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1));
          
          const endOfWeek = new Date(startOfWeek);
          endOfWeek.setDate(startOfWeek.getDate() + 6);
          
          const startOfLastWeek = new Date(startOfWeek);
          startOfLastWeek.setDate(startOfWeek.getDate() - 7);
          
          const endOfLastWeek = new Date(endOfWeek);
          endOfLastWeek.setDate(endOfWeek.getDate() - 7);
          
          const startOfNextWeek = new Date(startOfWeek);
          startOfNextWeek.setDate(startOfWeek.getDate() + 7);
          
          const endOfNextWeek = new Date(endOfWeek);
          endOfNextWeek.setDate(endOfWeek.getDate() + 7);
          
          // Get start of month
          const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
          
          const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
          
          const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
          
          const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
          
          const startOfNextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
          
          const endOfNextMonth = new Date(today.getFullYear(), today.getMonth() + 2, 0);
          
          let date1 = df.value1 ? new Date(df.value1) : null;
          let date2 = df.value2 ? new Date(df.value2) : null;
          
          if (date1) date1.setHours(0, 0, 0, 0);
          if (date2) date2.setHours(0, 0, 0, 0);
          
          const compareDate = new Date(cellDate);
          compareDate.setHours(0, 0, 0, 0);
          
          switch(df.type) {
            case 'equals':
              passes = compareDate.getTime() === date1.getTime();
              break;
            case 'notEqual':
              passes = compareDate.getTime() !== date1.getTime();
              break;
            case 'after':
              passes = compareDate > date1;
              break;
            case 'before':
              passes = compareDate < date1;
              break;
            case 'between':
              passes = compareDate >= date1 && compareDate <= date2;
              break;
            case 'tomorrow':
              passes = compareDate.getTime() === tomorrow.getTime();
              break;
            case 'today':
              passes = compareDate.getTime() === today.getTime();
              break;
            case 'yesterday':
              passes = compareDate.getTime() === yesterday.getTime();
              break;
            case 'nextWeek':
              passes = compareDate >= startOfNextWeek && compareDate <= endOfNextWeek;
              break;
            case 'thisWeek':
              passes = compareDate >= startOfWeek && compareDate <= endOfWeek;
              break;
            case 'lastWeek':
              passes = compareDate >= startOfLastWeek && compareDate <= endOfLastWeek;
              break;
            case 'nextMonth':
              passes = compareDate >= startOfNextMonth && compareDate <= endOfNextMonth;
              break;
            case 'thisMonth':
              passes = compareDate >= startOfMonth && compareDate <= endOfMonth;
              break;
            case 'lastMonth':
              passes = compareDate >= startOfLastMonth && compareDate <= endOfLastMonth;
              break;
          }
          
          if (!passes) {
            hide = true;
            break;
          }
        }
        else if (filter.type === 'custom') {
          const cf = filter.customFilter;
          let passes1 = applyCustomFilter(cellVal, cf.operator1, cf.value1);
          let passes = passes1;
          
          if (cf.hasSecondCondition) {
            const passes2 = applyCustomFilter(cellVal, cf.operator2, cf.value2);
            
            if (cf.relation === 'and') {
              passes = passes1 && passes2;
            } else {
              passes = passes1 || passes2;
            }
          }
          
          if (!passes) {
            hide = true;
            break;
          }
        }
      }

      if (hide) $tr.hide();
    });
  }
  
  // Helper function for custom filter conditions
  function applyCustomFilter(cellVal, operator, filterVal) {
    switch(operator) {
      case 'equals':
        return cellVal === filterVal;
      case 'notEqual':
        return cellVal !== filterVal;
      case 'contains':
        return cellVal.toLowerCase().includes(filterVal.toLowerCase());
      case 'notContains':
        return !cellVal.toLowerCase().includes(filterVal.toLowerCase());
      case 'startsWith':
        return cellVal.toLowerCase().startsWith(filterVal.toLowerCase());
      case 'endsWith':
        return cellVal.toLowerCase().endsWith(filterVal.toLowerCase());
      case 'greaterThan':
        return isNumeric(cellVal) && isNumeric(filterVal) && parseFloat(cellVal) > parseFloat(filterVal);
      case 'lessThan':
        return isNumeric(cellVal) && isNumeric(filterVal) && parseFloat(cellVal) < parseFloat(filterVal);
      default:
        return true;
    }
  }

  // Update header filter icon color for filtered columns
  function updateHeaderFilterStyles() {
    $table.find('th.col-header').removeClass('filtered');
    $table.find('th.col-header').each(function() {
      const col = $(this).data('col');
      if (filterState[col]) {
        $(this).addClass('filtered');
        $(this).find('.filter-icon').css('fill', '#0078d4');
      } else {
        $(this).removeClass('filtered');
        $(this).find('.filter-icon').css('fill', '#555');
      }
    });
  }
  
  
  

  // Update filters on cell edits, reapply
  $table.on('input', 'td[contenteditable]', function() {
    applyFilters();
    updateHeaderFilterStyles();
  });
  
  
  
     $('#export-btn').on('click', function() {
        exportToExcel();
    });
  
  
function exportToExcel() {
    // Show progress indicator
    $('.export-progress').show();
    
    // Use setTimeout to allow the UI to update before starting the heavy processing
    setTimeout(function() {
        try {
            // Prepare data for export
            const data = [];
            
            // Add headers - include "Sr.No." as the first column
            const headerRow = ['Sr.No.']; // Add serial number header first
            for (let c = 0; c < COLS; c++) {
                headerRow.push(COLUMNS[c]);
            }
            data.push(headerRow);
            
            // Add data rows (only visible rows after filtering)
            let rowCount = 0;
            $table.find('tbody tr:visible').each(function() {
                const rowData = [];
                
                // Add serial number from the first cell (row header)
                const serialNo = $(this).find('td.row-header').first().text().trim();
                rowData.push(serialNo);
                
                // Add the rest of the data cells
                $(this).find('td:not(.row-header)').each(function() {
                    rowData.push($(this).text().trim());
                });
                
                data.push(rowData);
                rowCount++;
                
                // Update progress bar
                if (rowCount % 10 === 0) {
                    const progress = Math.min(95, (rowCount / ROWS) * 100);
                    $('.export-progress-bar-inner').css('width', progress + '%');
                }
            });
            
            // Create worksheet
            const ws = XLSX.utils.aoa_to_sheet(data);
            
            // Create workbook
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            
            // Generate Excel file
            XLSX.writeFile(wb, "spreadsheet_export.xlsx");
            
            // Hide progress indicator
            $('.export-progress').hide();
            
        } catch (error) {
            console.error("Export error:", error);
            alert("Error exporting to Excel: " + error.message);
            $('.export-progress').hide();
        }
    }, 100);
}
  
  
  
  

});



$(function () {
    
   
     
  let $currentCell = null;

  $("#hidden-datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    onSelect: function (dateText) {
      if ($currentCell) {
        const row = $currentCell.data('row');
        const col = $currentCell.data('col');
        const oldValue = $currentCell.text().trim();
        
        // Update the cell content
        $currentCell.text(dateText);
        
        // Add to pending changes using the existing function
        addPendingChange(row, col, dateText, oldValue);
        
        // Also store the old value for future reference
        $currentCell.data('old-value', dateText);
      }
    },
    onClose: function () {
      $currentCell = null;
    }
  });

  $('#spreadsheet').on('click', '.date-cell', function () {
    $currentCell = $(this);

    const offset = $currentCell.offset();
    const existingDate = $currentCell.text().trim();

    if (existingDate) {
      $("#hidden-datepicker").datepicker("setDate", existingDate);
    }

    $("#hidden-datepicker").css({
      top: offset.top + $currentCell.outerHeight(),
      left: offset.left
    }).datepicker("show").focus();
  });
});



$(document).on('click','.dropdown-menu div', function() {
    const value = $(this).data('value');
    const label = $(this).text();
    const colIndex = $(this).parent().data('col');
    const rowIndex = $(this).parent().data('row'); // Get row index from dropdown
    
    console.log(colIndex);
    
        window.selectedFitSampleValue = value;
        window.selectedFitSampleLabel = label;
        window.selectedFitSampleColIndex = colIndex;
        window.selectedFitSampleRowIndex = rowIndex;
        const rowId = getRowId(rowIndex);
        
        $('#fit_sample_approval_plan_date').attr('data-rowid', rowId);
        $('#fit_sample_approval_plan_date').attr('data-rowIndex', rowIndex);
        $('#fit_sample_approval_plan_date').attr('data-colIndex', colIndex);
         $('#fit_sample_approval_plan_date').attr('data-colValue', value);
         
        const colName = COLUMNS[colIndex];
        console.log(COLUMN_MAP[colName]);
    
        $('#fitSampleModal').modal('show');
        return false;
    
});



$('#fit_sample_approval_plan_date').on('change', function () {
    const selectedDate = $(this).val(); // e.g., "2025-09-22"
    const rowId = $(this).attr('data-rowid');
    const row = $(this).attr('data-rowIndex');
    const col = $(this).attr('data-colIndex');
    const val = $(this).attr('data-colValue'); 
    

    /* const columnMap = {
        15: 'fit_sample_approval_plan_date',
        16: 'fit_sample_approval_actual_date',
        17: 'pp_yardage_inhouse_date',
        18: 'pp_sample_dispatch_plan_date',
        19: 'pp_sample_dispatch_plan_actual_date',
        20: 'pp_sample_approval_plan_date',
        21: 'pp_sample_approval_plan_actual_date',
        23: 'fabric_inhouse_date_plan_date',
        24: 'fabric_inhouse_date_plan_actual_date',
        26: 'fpt_status_date',
        27: 'gpt_status_date',
        28: 'production_file_release_date_plan_date',
        29: 'production_file_release_date_plan_actual_date',
    };
    */
    
       
          console.log(col);


    const column = COLUMN_MAP[COLUMNS[col]] || COLUMNS[col];
    if (!column) return;

    const prefix = val;
    //const newValue = `${prefix},${formatDateForDisplay(selectedDate)}`;
     const newValue = `${formatDateForDisplay(selectedDate)}`;

    // 🔽 Target cell in Handsontable (or table) using row & col
    const $cell = $(`#spreadsheet td[data-row="${row}"][data-col="${col}"]`);
    let currentText = $cell.text().trim();

    // 🔁 Split existing values, trim, avoid duplicates
    const existingValues = currentText
        ? currentText.split(',').map(v => v.trim())
        : [];

    // Combine prefix and date for comparison (e.g., "IH,2025-09-22")
    const exists = existingValues.join(',').includes(newValue);
    if (!exists) {
        if (currentText) {
           // currentText += `, ${newValue}`;
           currentText += `,${newValue}`;
        } else {
            currentText = newValue;
        }
    }
    
 

    // 🔄 Update cell text in DOM
    $cell.text(currentText);

    // 💾 Push to pendingChanges
    pendingChanges.push({
        row: 0,
        row_id: rowId,
        column: column,
        value: currentText,
        old_value: $cell.text().trim() // before updating
    });

    // ✅ Save & close
    saveChangesToServer();
    $('#fitSampleModal').modal('hide');
});








function formatDateForDisplay(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    
    return `${day}-${month}-${year}`;
}

  

    // $(document).ready(function () {
    //     $('#reload-btn').click(function () {
            
            
    //         $.ajax({
    //             url: '{{ route("load_data") }}', // Your Laravel route
    //             method: 'GET', // or 'POST'
    //             dataType: 'json',
    //             success: function (response) {
    //                 // Display or process the data
    //                 $('#data-output').html(JSON.stringify(response, null, 2));
    //             },
    //             error: function (xhr) {
    //                 console.error(xhr.responseText);
                   
    //             }
    //         });
    //     });
        
        
    // });
    
    
    $(document).ready(function () {
     function loadData() {
         
           if (!$('#loading-indicator').length) {
            $('body').append('<div id="loading-indicator" class="loading-indicator">Loading data...</div>');
        }
         
                   $.ajax({
                url: '{{ route("load_data") }}', // Your Laravel route
                method: 'GET', // or 'POST'
                dataType: 'json',
                success: function (response) {
                    // Display or process the data
                    $('#data-output').html(JSON.stringify(response, null, 2));
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                   
                },
                 complete: function () {
                // Hide loading indicator after request is complete
                $('#loading-indicator').remove();
            }
            });   
         
     }
     
     
      // loadData();
       
          $('#reload-btn').click(loadData);
    });
    
    
    
    function getCellAlignment(value, colName) {
    if (value === null || value === undefined || value === '') {
        return 'left';
    }
    
    // For specific numeric columns, always align right
    if (['Sam','Taxable Amount','CMOHP','Order Qty', 'Rate', 'Fabric Inhouse Qty', 'Cut Qty', 'Shipment Qty', 'Balance To Ship Qty','Rejection Pcs'].includes(colName)) {
        return 'right';
    }
    
        if (['id'].includes(colName)) {
        return 'center';
    }
    
   if (['Style Name','Fabric Color','Fit Sample Approval Plan'].includes(colName)) {
        return 'left';
    }
    
    
    // For dynamic columns, check if value is numeric
    if (dynamicColumns.includes(colName)) {
        return 'right';
    }
    
    
    // Check if it's a date (you might want to expand this logic)
    if (!isNaN(Date.parse(value))) {
        return 'left';
    }
    
    return 'left';
}



function isNumericValue(value) {
    if (value === null || value === undefined || value === '') return false;
    
    // Check for non-numeric strings like "NA", "Pending", etc.
    const nonNumericValues = ['NA', 'Pending', 'TBA', 'IH', 'Released', 'Done', 'Pass', 'Fail', 'Sent'];
    if (nonNumericValues.includes(value.toString().trim())) {
        return false;
    }
    
    // Check if it's a valid number
    const num = typeof value === 'string' ? parseFloat(value.replace(/,/g, '')) : value;
    return !isNaN(num);
}



    
    
    function indian_number_format(number) {
    if (number === null || number === undefined || number === '') return '';
    
    // Convert to number if it's a string
    const num = typeof number === 'string' ? parseFloat(number.replace(/,/g, '')) : number;
    
    // Check if it's a valid number
    if (isNaN(num)) return number;
    
    // Format with Indian locale with exactly 2 decimal places
    return num.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true
    });
}
    
    
    
$(document).ready(function () {
    $('#refresh-btn').click(function () {
        location.reload(); // This reloads the current page
    });
});


  
</script>

</body>
</html>