<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Advanced Dependent Filter</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <style>
     .filter-icon {
      cursor: pointer;
      margin-left: 6px;
      color: #007bff;
    }
    .filter-dropdown {
      position: absolute;
      display: none;
      background: #fff;
      border: 1px solid #ccc;
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      z-index: 999;
      width: 220px;
    }
    .filter-dropdown label {
      display: block;
      font-size: 14px;
      margin: 3px 0;
    }
    .filter-dropdown .sort-options div {
      cursor: pointer;
      padding: 4px 0;
    }
    .filter-dropdown .clear-filter {
      cursor: pointer;
      color: #dc3545;
      font-weight: bold;
      padding: 4px 0;
    }
    th { position: relative; }
    #customFilterModal, #customTextFilterModal, #customNumberFilterModal {
      display: none;
      position: fixed;
      background: white;
      padding: 20px;
      border: 1px solid #ccc;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      z-index: 10000;
    }
    #customFilterModal { top: 20%; left: 30%; width: 40%; }
    #customTextFilterModal, #customNumberFilterModal { top: 25%; left: 35%; width: 30%; }
    #customFilterModal input, #customFilterModal select,
    #customTextFilterModal input, #customTextFilterModal select,
    #customNumberFilterModal input, #customNumberFilterModal select {
      padding: 5px;
      margin: 5px 0;
      width: 100%;
    }

  </style>
</head>
<body>
  <table id="example" class="display">
    <thead>
      <tr>
        <th>Brand</th>
        <th>Price</th>
        <th>Order Date</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Nike</td><td>2500</td><td>2024-07-01</td></tr>
      <tr><td>Adidas</td><td>2300</td><td>2024-07-03</td></tr>
      <tr><td>Puma</td><td>1900</td><td>2024-06-28</td></tr>
      <tr><td>Nike</td><td>2100</td><td>2024-06-25</td></tr>
      <tr><td>Adidas</td><td>2600</td><td>2024-07-07</td></tr>
      <tr><td>Puma</td><td>2000</td><td>2024-07-02</td></tr>
      <tr><td>Nike</td><td>2500</td><td>2024-07-01</td></tr>
      <tr><td>Adidas</td><td>2300</td><td>2024-07-03</td></tr>
      <tr><td>Puma</td><td>1900</td><td>2024-06-28</td></tr>
      <tr><td>Nike</td><td>2100</td><td>2024-06-25</td></tr>
      <tr><td>Adidas</td><td>2600</td><td>2024-07-07</td></tr>
      <tr><td>Puma</td><td>2000</td><td>2024-07-02</td></tr>
    </tbody>
  </table>
  <!-- Date Filter Modal -->
  <div id="customFilterModal">
    <h3>Custom Filter</h3>
    <div><strong id="filterColumnName">Order Date</strong></div>
    <div style="display:flex; gap:10px; margin-top:10px;">
      <select id="dateOperator1">
        <option value="=">Equal</option>
        <option value=">">After</option>
        <option value="<">Before</option>
      </select>
      <input type="date" id="dateValue1">
    </div>
    <div>
      <label><input type="radio" name="dateLogic" value="AND" checked> AND</label>
      <label><input type="radio" name="dateLogic" value="OR"> OR</label>
    </div>
    <div style="display:flex; gap:10px;">
      <select id="dateOperator2">
        <option value="=">Equal</option>
        <option value=">">After</option>
        <option value="<">Before</option>
      </select>
      <input type="date" id="dateValue2">
    </div>
    <div style="margin-top:15px;">
      <button id="applyDateFilter">OK</button>
      <button id="cancelDateFilter">Cancel</button>
    </div>
  </div>

  <!-- Text Filter Modal -->
  <div id="customTextFilterModal">
    <h3>Text Filter</h3>
    <select id="textCondition">
      <option value="contains">Contains</option>
      <option value="starts">Starts With</option>
      <option value="ends">Ends With</option>
      <option value="equals">Equals</option>
    </select>
    <input type="text" id="textValue">
    <div>
      <button id="applyTextFilter">OK</button>
      <button id="cancelTextFilter">Cancel</button>
    </div>
  </div>

  <!-- Number Filter Modal -->
  <div id="customNumberFilterModal">
    <h3>Number Filter</h3>
    <select id="numberCondition">
      <option value="=">Equal</option>
      <option value=">">Greater Than</option>
      <option value="<">Less Than</option>
      <option value="between">Between</option>
    </select>
    <input type="number" id="numberValue1">
    <input type="number" id="numberValue2" style="display:none;">
    <div>
      <button id="applyNumberFilter">OK</button>
      <button id="cancelNumberFilter">Cancel</button>
    </div>
  </div>
  <script>
  
    let dataTable;
    let filterValues = {};
    let currentFilterCol = null; 
    let columnTypes = [], customDateFilter = null, textFilters = {};
    
    $(document).ready(function () 
    {
      dataTable = $('#example').DataTable();

      $('#example thead th').each(function (index) 
      {
        const filterBtn = $('<span class="filter-icon">&#128269;</span>').on('click', function (e) 
        {
          e.stopPropagation();
          $('.filter-dropdown').remove();  
          currentFilterCol = index;
          showFilterDropdown(index, $(this).closest('th'));
        });
        $(this).append(filterBtn);
      });

      $(document).on('click', function () 
      {
        $('.filter-dropdown').hide();
      });

      $.fn.dataTable.ext.search.push(function (settings, data) 
      {
        for (const col in filterValues) 
        {
          if (filterValues[col].length > 0 && !filterValues[col].includes(data[col])) 
          {
            return false;
          }
        }
        return true;
      });
    });

    function showFilterDropdown(colIndex, thElement) 
    {
      const dropdown = $('<div class="filter-dropdown"></div>');
      const allData = dataTable.rows({ search: 'applied' }).data().toArray();
      const otherCols = Object.keys(filterValues).map(Number).filter(c => c !== colIndex);

      const filteredSet = new Set();
      allData.forEach(row => {
        let match = true;
        for (const otherCol of otherCols) {
          if (filterValues[otherCol] && filterValues[otherCol].length > 0 && !filterValues[otherCol].includes(row[otherCol])) {
            match = false;
            break;
          }
        }
        if (match) {
          filteredSet.add(row[colIndex]);
        }
      });

      const sortedOptions = Array.from(filteredSet).sort();
      const currentSelected = filterValues[colIndex] || [];

      // Sorting + Clear options
      dropdown.append(`
        <div class="sort-options">
          <div class="sort-asc">&#x2193; Sort A → Z</div>
          <div class="sort-desc">&#x2191; Sort Z → A</div>
          <div class="clear-filter">🧹 Clear Filter</div>
        </div>
      `);

      dropdown.append(`<input type="text" class="search-filter" placeholder="Search...">`);
      dropdown.append(`<label><input type="checkbox" class="select-all"> Select All</label>`);

      sortedOptions.forEach(opt => {
        const checked = currentSelected.includes(opt) ? 'checked' : '';
        dropdown.append(`<label><input type="checkbox" class="filter-opt" value="${opt}" ${checked}> ${opt}</label>`);
      });

      $('.filter-dropdown').remove();
      thElement.append(dropdown);
      dropdown.show();

      dropdown.on('click', e => e.stopPropagation());

      dropdown.find('.search-filter').on('keyup', function () {
        const val = $(this).val().toLowerCase();
        dropdown.find('label').not(':first').each(function () {
          const label = $(this).text().toLowerCase();
          $(this).toggle(label.includes(val));
        });
      });

      dropdown.find('.select-all').on('change', function () {
        const checked = $(this).is(':checked');
        dropdown.find('.filter-opt').prop('checked', checked);
        updateFilterValues(colIndex, dropdown);
      });

      dropdown.find('.filter-opt').on('change', function () {
        updateFilterValues(colIndex, dropdown);
      });

      // Sorting
      dropdown.find('.sort-asc').on('click', function () {
        dataTable.order([colIndex, 'asc']).draw();
        dropdown.hide();
      });

      dropdown.find('.sort-desc').on('click', function () {
        dataTable.order([colIndex, 'desc']).draw();
        dropdown.hide();
      });

      // Clear filter
      dropdown.find('.clear-filter').on('click', function () {
        delete filterValues[colIndex];
        dataTable.draw();
        dropdown.hide();
      });
    }

    function showCustomTextFilter(colIndex) 
    {
      $('#customTextFilterModal').show();
      $('#applyTextFilter').off('click').on('click', function () {
        textFilters[colIndex] = { type: 'text', condition: $('#textCondition').val(), value: $('#textValue').val().toLowerCase() };
        $('#customTextFilterModal').hide(); dataTable.draw();
      });
      $('#cancelTextFilter').on('click', function () { $('#customTextFilterModal').hide(); });
    }

    function showCustomNumberFilter(colIndex) 
    {
      $('#customNumberFilterModal').show();
      $('#numberCondition').off('change').on('change', function () {
        $('#numberValue2').toggle($(this).val() === 'between');
      }).trigger('change');

      $('#applyNumberFilter').off('click').on('click', function () {
        const condition = $('#numberCondition').val();
        const val1 = parseFloat($('#numberValue1').val());
        const val2 = parseFloat($('#numberValue2').val());
        textFilters[colIndex] = { type: 'number', condition, val1, val2: condition === 'between' ? val2 : null };
        $('#customNumberFilterModal').hide(); dataTable.draw();
      });
      $('#cancelNumberFilter').on('click', function () { $('#customNumberFilterModal').hide(); });
    }

    function showCustomDateFilter(colIndex) 
    {
      $('#customFilterModal').show();
      $('#applyDateFilter').off('click').on('click', function () {
        customDateFilter = {
          col: colIndex,
          op1: $('#dateOperator1').val(), val1: $('#dateValue1').val(),
          op2: $('#dateOperator2').val(), val2: $('#dateValue2').val(),
          logic: $('input[name="dateLogic"]:checked').val()
        };
        $('#customFilterModal').hide(); dataTable.draw();
      });
      $('#cancelDateFilter').on('click', function () { $('#customFilterModal').hide(); });
    }
    
    function updateFilterValues(colIndex, dropdown) 
    {
      const selected = dropdown.find('.filter-opt:checked').map(function () {
        return $(this).val();
      }).get();

      if (selected.length > 0) {
        filterValues[colIndex] = selected;
      } else {
        delete filterValues[colIndex];
      }

      dataTable.draw();
    }
    
    function parseDate(str) 
    {
      const d = new Date(str);
      return isNaN(d) ? null : d;
    }

    function compareDate(actualDate, operator, inputDateStr) 
    {
      if (!inputDateStr) return true;
      const inputDate = new Date(inputDateStr);
      switch (operator) {
        case "=": return actualDate.toDateString() === inputDate.toDateString();
        case ">": return actualDate > inputDate;
        case "<": return actualDate < inputDate;
        default: return true;
      }
    }
    
  </script>
</body>
</html>
