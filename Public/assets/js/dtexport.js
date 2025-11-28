function commonExportOptions() {
  return {
    columns: ':visible',
    modifier: { search: 'applied' },
    format: {
      header: function (data, columnIdx) {
        const div = document.createElement("div");
        div.innerHTML = data;

        // Remove filter menus & icons
        div.querySelectorAll('.filter-menu, .filter-icon, i, svg').forEach(el => el.remove());

        return div.textContent.trim() || div.innerText.trim() || "";
      },

      body: function (data, row, column, node) {
        // 🧹 Remove any HTML tags and keep only text
        let cleanText = $('<div>').html(data).text().trim();

        // 🧠 Auto-detect date-like strings (e.g. 13-Nov-2025 or 01-Jan-24)
        const datePattern = /^(\d{1,2})[-/](\w{3})[-/](\d{2,4})$/;

        if (typeof cleanText === 'string' && datePattern.test(cleanText)) {
          const parts = cleanText.match(datePattern);
          const months = {
            Jan: '01', Feb: '02', Mar: '03', Apr: '04', May: '05', Jun: '06',
            Jul: '07', Aug: '08', Sep: '09', Oct: '10', Nov: '11', Dec: '12'
          };
          const d = parts[1].padStart(2, '0');
          const m = months[parts[2]] || '01';
          const y = parts[3].length === 2 ? `20${parts[3]}` : parts[3]; // handle YY or YYYY
          return `${y}-${m}-${d}`; // ISO format (Excel/CSV friendly)
        }

        // Return cleaned text
        return cleanText;
      }
    }
  };
}

function validateFilterMenu(menu) {
    // Find only normal checkboxes (ignore .select-all)
    const checkedCount = menu.find('input[type="checkbox"]:checked:not(.select-all)').length;
    console.log(checkedCount + "   "+menu);
    if (checkedCount === 0) {
        alert("Please select at least one option");
        return false;  // ❌ validation failed
    }
    const filterBtn = menu.closest('th').find('.filter-icon');
    if (checkedCount) {
        filterBtn.addClass('filter-active');   // highlight icon
    } else {
        filterBtn.removeClass('filter-active'); // remove highlight if no filter
    }

    return true;  // ✔ validation passed
  }

  function removeFilterColor() {
    $('.filter-icon').removeClass('filter-active');
  }

       // Start Function updateFooterTotals
        function updateFooterTotals() {
          const table = $('#dt').DataTable();
          const data = table.rows({ search: 'applied' }).data();
          const cols = [5, 6, 7, 8, 9, 10, 11, 12]; // numeric columns
          const totals = Array(cols.length).fill(0);

          for (let i = 0; i < data.length; i++) {
            cols.forEach((c, idx) => {
              let cell = (data[i][c] || "0").toString()
                .replace(/<[^>]*>/g, '') // remove HTML
                .trim()
                .replace(/,/g, '')       // remove commas
                .replace(/[^\d.-]/g, ''); // remove symbols
              const num = parseFloat(cell);
              if (!isNaN(num)) totals[idx] += num;
            });
          }

          const footerCells = $('#dt tfoot th');
          cols.forEach((c, idx) => {
            let value;
            if (c === 7) {
            
              value = 0.0;
              if (totals[0]  > 0){
              const calculateV= totals[3] / totals[0] ;
              value = calculateV.toFixed(2); 
              }

            } else {       
              value = totals[idx].toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
              });
            }
            $(footerCells[c]).text(value);
          }); 
          }
        // end Function updateFooterTotals

      function unique(arr){ return [...new Set(arr)].sort(); }

let lastParentFilterCol = null;
let savedFilterStates = {};
let savedVisibleValues = {};

function escapeHtml(str) {
    return str
        
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;")
         ;
}

function buildSimpleFilter(selector, colIndex) {
 
  let tableId = $('table.dataTable').attr('id'); 
  let table = null;

  if(tableId == 'dt')
  table = $('#dt').DataTable();
  else
  table = $('#'+tableId).DataTable();  

  const menu = $(selector);

  let valuesToShow;

  if (lastParentFilterCol === colIndex && savedVisibleValues[colIndex]) {
    // ✅ Reopen same parent filter: use saved visible options
    valuesToShow = savedVisibleValues[colIndex];
  } else {
    // ✅ Build from current filtered data
    const visibleData = table.column(colIndex, { search: 'applied' }).data().toArray();
   
try{
      //valuesToShow = [...new Set(visibleData.filter(v => v && v.trim() !== ''))].sort();     
      
      valuesToShow = [...new Set(
          visibleData
              .map(v => (v === null || v === undefined ? '' : String(v)))  
              .map(v => v.trim())  
              .filter(v => v !== '')
      )].sort();
     
    }
    catch(error){
 
      valuesToShow = [...new Set(
          visibleData
              .map(v => (v === null || v === undefined ? '' : String(v)))  
              .map(v => v.trim())  
              .filter(v => v !== '')
      )].sort();
    }

  }

  // Restore previously checked values
  const prevChecked = savedFilterStates[colIndex] || [];

  // Build HTML
  let html = `
    <input type='text' class='filter-search' placeholder='Search...'>
    <label><input type='checkbox' class='select-all'> <b>Select All</b></label>
    <div class='options'>
  `;
 


  /*valuesToShow.forEach(v => {   
    const checked = prevChecked.length === 0 || prevChecked.includes(v) ? 'checked' : '';
    html += `<label><input type='checkbox' class='opt' value='${v}' ${checked}> ${v}</label>`;
  });*/

  valuesToShow.forEach(v => {  
    const safeValue = escapeHtml(v); // Escape quotes and HTML
    const checked = prevChecked.length === 0 || prevChecked.includes(v) ? 'checked' : '';

    html += `
        <label>
            <input type="checkbox" class="opt" value="${safeValue}" ${checked}> 
            ${v}
        </label>`;
});

  html += `
    </div>
    <div class='filter-actions'>
      <button class='apply-btn'>Apply</button>
      <button class='clear-btn'>Clear</button>
    </div>
  `;
 
  menu.html(html);

  // Handle select-all state
  const allCount = valuesToShow.length;
  const checkedCount = menu.find('.opt:checked').length;
  menu.find('.select-all').prop('checked', allCount === checkedCount);
}

function buildColouredFilter(selector, colIndex) {
  const table = $('#dt').DataTable();
  const menu = $(selector);

  let valuesToShow;

  // ✅ 1️⃣ Check if this column is the last parent filter
  if (lastParentFilterCol === colIndex && savedVisibleValues[colIndex]) {
    // Reopen same parent filter → use previously saved visible values
    valuesToShow = savedVisibleValues[colIndex];
  } else {    
    const visibleData = table.column(colIndex, { search: 'applied' }).data().toArray();
    valuesToShow = [...new Set(visibleData.filter(v => v && v.trim() !== ''))].sort();
    

  }

  // ✅ 2️⃣ Get previously checked options (if any)
  const prevChecked = savedFilterStates[colIndex] || [];

  // ✅ 3️⃣ Build HTML
  let html = `
    <input type='text' class='filter-search' placeholder='Search...'>
    <label><input type='checkbox' class='select-all'> <b>Select All</b></label>
    <div class='options'>
  `;

  valuesToShow.forEach(v => {
    const cleanVal = $('<div>').html(v).text().trim(); // strip HTML
    const checked = prevChecked.length === 0 || prevChecked.includes(cleanVal) ? 'checked' : '';

    // Color label
    let colorClass = '';
    if (cleanVal.toLowerCase() === 'completed') colorClass = 'completed-label';
    else if (cleanVal.toLowerCase() === 'approved') colorClass = 'approved-label';
    else if (cleanVal.toLowerCase() === 'not approved') colorClass = 'not-approved-label';
    else if (cleanVal.toLowerCase() === 'not done') colorClass = 'not-done-label';

    html += `
      <label>
        <input type='checkbox' class='opt' value='${cleanVal}' ${checked}>
        <span class='${colorClass}'>${cleanVal}</span>
      </label>`;
  });

  html += `
    </div>
    <div class='filter-actions'>
      <button class='apply-btn'>Apply</button>
      <button class='clear-btn'>Clear</button>
    </div>
  `;

  menu.html(html);

  // ✅ 4️⃣ Handle select-all checkbox state
  const allCount = valuesToShow.length;
  const checkedCount = menu.find('.opt:checked').length;
  menu.find('.select-all').prop('checked', allCount === checkedCount);
}
 
function buildDateFilter(selector, colIndex) {
  const table = $('#dt').DataTable();
  const menu = $(selector);

  let dates = [];

  // Reuse visible options if reopening same filter
  if (lastParentFilterCol === colIndex && Array.isArray(savedVisibleValues[colIndex])) {
    dates = savedVisibleValues[colIndex];
  } else {
    // Otherwise, rebuild from visible table data
    const colData = table.column(colIndex, { search: 'applied' }).data().toArray();
    dates = [...new Set(colData.filter(Boolean))];
    savedVisibleValues[colIndex] = dates;
  }

  // Sort newest first
  dates.sort((a, b) => new Date(b) - new Date(a));

  // Group: Year → Month → Dates[]
  const tree = {};
  dates.forEach(d => {
    const dt = new Date(d);
    if (isNaN(dt)) return;
    const y = dt.getFullYear();
    const m = dt.toLocaleString('default', { month: 'short' });
    if (!tree[y]) tree[y] = {};
    if (!tree[y][m]) tree[y][m] = [];
    tree[y][m].push(d);
  });

  // ✅ Detect if there’s a saved state
  const hasSavedState = Array.isArray(savedFilterStates[colIndex]);
  const prevChecked = hasSavedState ? savedFilterStates[colIndex] : [];

  let html = `<input type='text' class='filter-search' placeholder='Search date...'>`;

  Object.keys(tree)
    .sort((a, b) => b - a)
    .forEach(y => {
      html += `
        <div class='year-block'>
          <div class='tree-line'>
            <span class='tree-toggle' data-target='year-${colIndex}-${y}'>+</span>
            <label><input type='checkbox' class='year-check' data-col='${colIndex}' data-year='${y}'> ${y}</label>
          </div>
          <div class='month-list collapsed' id='year-${colIndex}-${y}'>
      `;

      Object.keys(tree[y]).forEach(m => {
        html += `
          <div class='month-block'>
            <div class='tree-line'>
              <span class='tree-toggle' data-target='month-${colIndex}-${y}-${m}'>+</span>
              <label><input type='checkbox' class='month-check' data-col='${colIndex}' data-year='${y}' data-month='${m}'> ${m}</label>
            </div>
            <div class='day-list collapsed' id='month-${colIndex}-${y}-${m}'>
        `;

        tree[y][m].forEach(d => {
          // ✅ If no saved state yet → all checked
          // ✅ Else → restore exactly
          const checked =
            !hasSavedState || prevChecked.includes(d) ? 'checked' : '';
          html += `<label><input type='checkbox' class='date-opt' data-col='${colIndex}' data-year='${y}' data-month='${m}' value='${d}' ${checked}> ${d}</label>`;
        });

        html += `</div></div>`;
      });

      html += `</div></div>`;
    });

  html += `
    <div class='filter-actions'>
      <button class='apply-btn' data-col='${colIndex}'>Apply</button>
      <button class='clear-btn' data-col='${colIndex}'>Clear</button>
    </div>`;

  menu.html(html);

  // ✅ Sync hierarchy (so years/months reflect day states)
  syncAllDateHierarchies(colIndex);
}


function syncAllDateHierarchies(colIndex) {
  // Sync each year’s checkboxes and indeterminate states
  $(`.year-check[data-col='${colIndex}']`).each(function () {
    const y = $(this).data('year');
    const months = $(`.month-check[data-col='${colIndex}'][data-year='${y}']`);
    const dates = $(`.date-opt[data-col='${colIndex}'][data-year='${y}']`);

    const totalDates = dates.length;
    const checkedDates = dates.filter(':checked').length;

    // ✅ Year checkbox checked if all dates checked
    // ✅ Indeterminate if partially checked
    this.checked = totalDates > 0 && checkedDates === totalDates;
    this.indeterminate = checkedDates > 0 && checkedDates < totalDates;
  });

  // Sync each month’s checkboxes within each year
  $(`.month-check[data-col='${colIndex}']`).each(function () {
    const y = $(this).data('year');
    const m = $(this).data('month');
    const dates = $(`.date-opt[data-col='${colIndex}'][data-year='${y}'][data-month='${m}']`);
    const totalDates = dates.length;
    const checkedDates = dates.filter(':checked').length;

    this.checked = totalDates > 0 && checkedDates === totalDates;
    this.indeterminate = checkedDates > 0 && checkedDates < totalDates;
  });
}


	function updateTotalsSalesOrderDetailDashboard() { 
    // Get visible rows (after filter applied)
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data(); //table.rows({ search: 'applied' }).data();

    let totalQty = 0;
    let totalMinQty = 0;
    let totalOrderValue = 0;
    let totalShipQty = 0;
    let totalBalQty = 0;

    // Loop through visible rows
    data.each(function (row) {  
      
        // Adjust column indexes as per your table structure
        totalQty         += parseFloat(row[19]?.toString().replace(/,/g, '')) || 0;  // Qty
        totalMinQty      += parseFloat(row[20]?.toString().replace(/,/g, '')) || 0;  // Min
        totalOrderValue  += parseFloat(row[21]?.toString().replace(/,/g, '')) || 0;  // Value
        totalShipQty     += parseFloat(row[22]?.toString().replace(/,/g, '')) || 0;  // Shipment Qty
        totalBalQty      += parseFloat(row[23]?.toString().replace(/,/g, '')) || 0;  // Bal. Qty
    });

    // Format number with commas and 2 decimals
    function formatNum(n) {
        return n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Set totals in header/footer
    $('#head_total_qty').text(formatNum(totalQty));
    $('#head_total_Min_qty').text(formatNum(totalMinQty));
    $('#head_total_order_value').text(formatNum(totalOrderValue));
    $('#head_total_ship_qty').text(formatNum(totalShipQty));
    $('#head_total_bal_qty').text(formatNum(totalBalQty));
   }

     // === Coloured Filter Apply ===
 
 function applyColouredFilter(col, menu) {
  const table = $('#dt').DataTable();

  // Get selected checkbox labels (clean HTML text)
  const vals = menu.find('.opt:checked').map((i, e) => {
    return $('<div>').html($(e).val()).text().trim();
  }).get();

  // Save checked values & visible values
  savedFilterStates[col] = vals;
  savedVisibleValues[col] = menu.find('.opt').map((i, e) => {
    return $('<div>').html($(e).val()).text().trim();
  }).get();

  // Remember last parent column
  lastParentFilterCol = col;

  // Clear filter if nothing selected
  if (vals.length === 0) {
    table.column(col).search('').draw();
    return;
  }

  // Remove any previous custom filter for this column
  $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(fn => !fn._isColouredFilter || fn._col !== col);

  // Create new custom filter
  const customFilter = function (settings, data, dataIndex) {
    if (settings.nTable !== table.table().node()) return true; // only this table

    const cellHtml = data[col] || '';
    const cellText = $('<div>').html(cellHtml).text().trim(); // strip HTML
    return vals.includes(cellText); // exact text match
  };
  customFilter._isColouredFilter = true;
  customFilter._col = col;

  $.fn.dataTable.ext.search.push(customFilter);

  // Redraw table with filter applied
  table.draw();
}





function escapeRegex(text) {
    return text.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}
  


    function applySimpleFilter(col, menu) {
    //const table = $('#dt').DataTable();

      let tableId = $('table.dataTable').attr('id'); 
      let table = null;

      if(tableId == 'dt')
      table = $('#dt').DataTable();
      else
      table = $('#'+tableId).DataTable();   

    const vals = menu.find('.opt:checked').map((i, e) => e.value).get();
    savedFilterStates[col] = vals;

    const visibleOptions = menu.find('.opt').map((i, e) => e.value).get();
    savedVisibleValues[col] = visibleOptions;
    lastParentFilterCol = col;

    // Exact-match regex pattern
    const pattern = vals.length
        ? vals.map(v => "^" + escapeRegex(v) + "$").join("|")
        : "";

    // Apply REGEX filter, exact match only
    table.column(col).search(pattern, true, false).draw();
}


function applyDateFilter(col, menu) {
  const table = $('#dt').DataTable();
  const vals = menu.find('.date-opt:checked').map((i,e)=>e.value).get();

  // Save checked values
  savedFilterStates[col] = vals;

  // Save visible options at this moment
  const visibleDates = menu.find('.date-opt').map((i,e)=>e.value).get();
  savedVisibleValues[col] = visibleDates;

  // Mark this as parent
  lastParentFilterCol = col;

  // Apply search
  table.column(col).search(vals.length ? vals.join('|') : '❌', true, false).draw();
}



  // === UI Events ===
  $('.filter-icon').on('click', function(e) {
    e.stopPropagation();
    $('.filter-menu').hide();
    $(this).next('.filter-menu').toggle();
  });
  $(document).on('click', e=>{
    if(!$(e.target).closest('.filter-menu, .filter-icon').length) $('.filter-menu').hide();
  });
  $(document).on('click', '.filter-menu', e=> e.stopPropagation());

  $(document).on('input', '.filter-search', function(){
    const val = $(this).val().toLowerCase();   
    $(this).closest('.filter-menu').find('label').each(function(){
      $(this).toggle($(this).text().toLowerCase().includes(val));
    });
  });

  $(document).on('change', '.select-all', function(){
    $(this).closest('.filter-menu').find('.opt').prop('checked', this.checked);
  });

  $(document).on('click', '.tree-toggle', function(){
    const id = $(this).data('target');
    const block = $('#'+id);
    if(block.hasClass('collapsed')){
      block.removeClass('collapsed');
      $(this).text('−');
    } else {
      block.addClass('collapsed');
      $(this).text('+');
    }
  });

  // Date - Mon-  Date  change code start
    $(document).on('change', '.year-check', function(){
    const y = $(this).data('year');
    $(`.month-check[data-year='${y}'], .date-opt[data-year='${y}']`).prop('checked', this.checked);
  });
  $(document).on('change', '.month-check', function(){
    const y = $(this).data('year');
    const m = $(this).data('month');
    $(`.date-opt[data-year='${y}'][data-month='${m}']`).prop('checked', this.checked);
    const allMonths = $(`.month-check[data-year='${y}']`);
    $(`.year-check[data-year='${y}']`).prop('checked', allMonths.length === allMonths.filter(':checked').length);
  });
  $(document).on('change', '.date-opt', function(){
    const y = $(this).data('year');
    const m = $(this).data('month');
    const allDates = $(`.date-opt[data-year='${y}'][data-month='${m}']`);
    const allChecked = allDates.length === allDates.filter(':checked').length;
    $(`.month-check[data-year='${y}'][data-month='${m}']`).prop('checked', allChecked);
    const allMonths = $(`.month-check[data-year='${y}']`);
    $(`.year-check[data-year='${y}']`).prop('checked', allMonths.length === allMonths.filter(':checked').length);
  });
  // Date - Mon-  Date  change code end

  

    function buildAllMenusSaleFilterReport() {
      buildSimpleFilter('.invno-menu', 1);
      buildSimpleFilter('.salehead-menu', 2);
      buildDateFilter('.date-menu', 3);
      buildSimpleFilter('.buyer-menu', 4);
    }

    function buildAllMenusTotalsSalesOrderDetailDashboard() {
    buildSimpleFilter('.order-no', 1);
    buildSimpleFilter('.inhouse-outsource', 2);
    buildSimpleFilter('.order-group', 3);
    buildSimpleFilter('.order-category', 4);
    buildSimpleFilter('.po-status', 5);
    buildSimpleFilter('.order-type', 6);    
    buildDateFilter('.recd-date',7);
    buildDateFilter('.plancut-date',8);
    buildDateFilter('.shipment-date',9);
    buildDateFilter('.close-date',10);
    buildSimpleFilter('.buyer-name', 11); 
    buildSimpleFilter('.buyer-brand', 12); 
    buildSimpleFilter('.style', 13); 
    buildSimpleFilter('.sub-style', 14); 
    buildSimpleFilter('.style-name', 15); 
    buildColouredFilter('.costing-entry', 24); 
    buildColouredFilter('.costing-status', 25); 
    buildSimpleFilter('.bulk-merchant', 26); 
  }

    function buildAllMenusOpenSalesDetailOrderDetailDashboard() {
    buildSimpleFilter('.order-no', 1);
    buildSimpleFilter('.order-group', 2);
    buildDateFilter('.order-rec-date', 3);
    buildSimpleFilter('.buyer-name', 4);
    buildSimpleFilter('.buyer-brand', 5);
    buildSimpleFilter('.style-category', 6);    
    buildSimpleFilter('.style-name',7);
    buildDateFilter('.plan-cut-date',32);
    buildDateFilter('.shipment-date',33);
    buildDateFilter('.shipment-month',34);
    buildSimpleFilter('.bulk-merchant',35);    
  }

  function buildAllMenusSalesCostingProfitSheet() {
    buildSimpleFilter('.sales-order-no', 1);
    buildSimpleFilter('.buyer', 2);    
    buildSimpleFilter('.brand', 3); 
    buildSimpleFilter('.costing-status', 4);
    buildSimpleFilter('.status', 5);
    buildSimpleFilter('.order-type', 6);
    buildDateFilter('.order-recd-date', 7);
    buildSimpleFilter('.style-no',8); 
    buildSimpleFilter('.style-category',9);
  }

  function buildAllMenusTrimGRNDataReport(){
    buildSimpleFilter('.supplier-name', 0);
    buildSimpleFilter('.bill-to', 1);
    buildSimpleFilter('.po-no', 2);
    buildSimpleFilter('.sales-order-no', 3);
    buildSimpleFilter('.buyer-name', 4);
    buildSimpleFilter('.return-wo-no', 5);
    buildSimpleFilter('.return-vendor-name', 6);
    buildSimpleFilter('.grn-no', 7);
    buildDateFilter('.grn-date', 8);
    buildSimpleFilter('.invoice-no', 9);
    buildDateFilter('.invoice-date', 10);
    buildSimpleFilter('.item-code', 11);
    buildSimpleFilter('.item-name', 12);
    buildSimpleFilter('.item-description', 18);
    buildSimpleFilter('.rack-code', 19);
  }

  function buildAllMenusTrimOutwardDataReport(){
      buildSimpleFilter('.vendor-name', 0);
      buildSimpleFilter('.process-order-no', 1);
      buildSimpleFilter('.work-order-no', 2);
      buildSimpleFilter('.sample-indent-code', 3);
      buildSimpleFilter('.trims-type', 4);
      buildSimpleFilter('.sales-order-no', 5);
      buildSimpleFilter('.buyer-name', 6);
      buildSimpleFilter('.out-no', 7);
      buildDateFilter('.out-date', 8);
      buildSimpleFilter('.po-code', 9);
      buildSimpleFilter('.supplier', 10);
      buildSimpleFilter('.bill-to', 11);
      buildSimpleFilter('.item-code', 12);
      buildSimpleFilter('.item-name', 13);
      buildSimpleFilter('.out-qty', 14);
      buildSimpleFilter('.rate', 15);
      buildSimpleFilter('.amount', 16);
      buildSimpleFilter('.width', 17);
      buildSimpleFilter('.quality-name', 18);
      buildSimpleFilter('.color', 19);
      buildSimpleFilter('.item-description', 20);
  }

    //Start For Fabric Inward Report
    function buildAllMenusFabricGRNDataReport(){
    buildSimpleFilter('.supplier-name', 0);
    buildSimpleFilter('.bill-to', 1);
    buildSimpleFilter('.buyer-name', 2);
    buildSimpleFilter('.po-no', 3);
    buildSimpleFilter('.grn-no', 4);
    buildDateFilter('.grn-date', 5);
    buildSimpleFilter('.return-cpo-no', 6);
    buildSimpleFilter('.return-vendor-name', 7);
    buildSimpleFilter('.invoice-no', 8);
    buildDateFilter('.invoice-date', 9);
    buildSimpleFilter('.item-code', 10);
    buildSimpleFilter('.item-name', 11);
    buildSimpleFilter('.item-description', 18);
    buildSimpleFilter('.track-code', 19);
    buildSimpleFilter('.rack-code', 20);
  }


    function updateFooterForFabricGRNDataReport() {
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data();
    let head_total_qty = 0;
    let head_total_value=0;

    data.each(function (row) {        
        head_total_qty         += parseFloat(row['meter']?.toString().replace(/,/g, '')) || 0;
        head_total_value           += parseFloat(row['item_value']?.toString().replace(/,/g, '')) || 0;
    });
  
    $('#head_total_grn_qty').text(formatNumberTableHead(head_total_qty));
    $('#head_total_value').text(formatNumberTableHead(head_total_value));
  }
  //End For Fabric Inward Report

      //Start For Fabric Inward Report
    function buildAllMenusFabricOutwardDataReport(){
    buildSimpleFilter('.vendor-name', 0);
    buildSimpleFilter('.buyer-name', 1);
    buildSimpleFilter('.process-order-no', 2);
    buildSimpleFilter('.sales-order-no', 3);
    buildSimpleFilter('.outward-no', 4);
    buildSimpleFilter('.outward-type', 5);
    buildDateFilter('.outward-date', 6); // date column
    buildSimpleFilter('.supplier-po-no', 7);
    buildSimpleFilter('.supplier-name', 8);
    buildSimpleFilter('.bill-to', 9);
    buildSimpleFilter('.item-code', 10);
    buildSimpleFilter('.item-name', 11);
    buildSimpleFilter('.quality-name', 12);
    buildSimpleFilter('.track-code', 13);
  }

  function updateFooterForFabricOutwardDataReport() {
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data();
    let head_total_qty = 0;
    let head_total_value=0;

    data.each(function (row) {                   
        head_total_qty         += parseFloat(row['meter']?.toString().replace(/,/g, '')) || 0;
        head_total_value           += parseFloat(row['item_value']?.toString().replace(/,/g, '')) || 0;
    });
  
    console.log(head_total_qty);
    $('#head_total_outward_qty').text(formatNumberTableHead(head_total_qty));
    $('#head_total_value').text(formatNumberTableHead(head_total_value));
  }
  //End For Fabric Inward Report

       //Start For Fabric Checking Report
        function buildAllMenusFabricCheckingDashboardReport(){
        buildDateFilter('.date_', 0);
        buildSimpleFilter('.chk-code', 1);
        buildSimpleFilter('.track-code', 2);
        buildSimpleFilter('.supplier-name', 3);
        buildSimpleFilter('.bill-to', 4);
        buildSimpleFilter('.po-code', 5);
        buildSimpleFilter('.grn-no', 6);
        buildSimpleFilter('.item-code', 7);
        buildSimpleFilter('.item-name', 8);
        buildSimpleFilter('.color', 9);
        buildSimpleFilter('.item-description', 10);
        buildSimpleFilter('.status', 11);  
        buildSimpleFilter('.shrinkage', 16);
        buildSimpleFilter('.pass-percentage', 17);
        buildSimpleFilter('.reject-percentage', 18);
        }
      //End For Fabric Checking Report

      //Start For Fabric Gate Entry Report
        function buildAllMenusFabricGateEntryReport(){
        buildSimpleFilter('.fge-code', 0);
        buildDateFilter('.date', 1);
        buildSimpleFilter('.po-no', 2);
        buildSimpleFilter('.manual-po-no', 3);
        buildSimpleFilter('.dc-no', 4);
        buildDateFilter('.dc-date', 5);
        buildSimpleFilter('.invoice-no', 6);
        buildDateFilter('.invoice-date', 7);
        buildSimpleFilter('.supplier', 8);
        buildSimpleFilter('.bill-to', 9);
        buildSimpleFilter('.location-warehouse', 10);
        buildSimpleFilter('.lr-no', 11);
        buildSimpleFilter('.transport-name', 12);
        buildSimpleFilter('.vehicle-no', 13);
        buildSimpleFilter('.item-name', 14);
        buildSimpleFilter('.item-code', 15);
        buildSimpleFilter('.item-description', 16);
        buildSimpleFilter('.no-of-roll', 17);
        buildSimpleFilter('.challan-qty', 18);
        buildSimpleFilter('.rate', 19);
        buildSimpleFilter('.amount', 20);
        buildSimpleFilter('.remark', 21);     
        }
      //End For Fabric Checking Report

     function buildAllMenusTrimsGateEntryReport(){
      buildSimpleFilter('.tge-code', 0);
      buildDateFilter('.date', 1);
      buildSimpleFilter('.po-no', 2);
      buildSimpleFilter('.manual-po-no', 3);
      buildSimpleFilter('.dc-no', 4);
      buildDateFilter('.dc-date', 5);
      buildSimpleFilter('.invoice-no', 6);
      buildDateFilter('.invoice-date', 7);
      buildSimpleFilter('.supplier', 8);
      buildSimpleFilter('.bill-to', 9);
      buildSimpleFilter('.location', 10);
      buildSimpleFilter('.lr-no', 11);
      buildSimpleFilter('.transport-name', 12);
      buildSimpleFilter('.vehicle-no', 13);
      buildSimpleFilter('.item-name', 14);
      buildSimpleFilter('.item-code', 15);
      buildSimpleFilter('.item-description', 16);
      buildSimpleFilter('.challan-qty', 17);
      buildSimpleFilter('.rate', 18);
      buildSimpleFilter('.amount', 19);
      buildSimpleFilter('.remark', 20);
     }

      function buildAllMenusFabricAssociationReport(){
        buildSimpleFilter('.sr-no', 0);
        buildSimpleFilter('.sales-order-no', 1);
        buildSimpleFilter('.item-code', 2);
        buildSimpleFilter('.po-code', 3);
        buildSimpleFilter('.supplier-name', 4);
        buildSimpleFilter('.bill-to', 5);
        buildSimpleFilter('.item-name', 6);
        buildSimpleFilter('.total-asso', 7);
        buildSimpleFilter('.allocated-stock', 8);
        buildSimpleFilter('.issue-stock', 9);
        buildSimpleFilter('.available-stock', 10);
      }

      function buildAllMenusTrimAssociationReport(){
        buildSimpleFilter('.sr-no', 0);
        buildSimpleFilter('.sales-order-no', 1);
        buildSimpleFilter('.item-code', 2);
        buildSimpleFilter('.item-category', 3);
        buildSimpleFilter('.po-code', 4);
        buildSimpleFilter('.supplier-name', 5);
        buildSimpleFilter('.bill-to', 6);
        buildSimpleFilter('.item-name', 7);
        buildSimpleFilter('.total-asso', 8);
        buildSimpleFilter('.allocated-stock', 9);
        buildSimpleFilter('.issue-stock', 10);
        buildSimpleFilter('.avaliable-stock', 11);
      }

       function table_values_indian_format()  {
          function formatIndianNumber(num) {
            if (isNaN(num)) return num; // Ignore non-numbers
            return Number(num).toLocaleString('en-IN', {
               minimumFractionDigits: 2,
               maximumFractionDigits: 2
            });
         }

         function convertTableToIndianFormat() {
            document.querySelectorAll("table tr td").forEach(td => {
               let value = td.innerText.trim();

               // Remove commas before converting
               let num = value.replace(/,/g, "");

               // Check if valid number
               if (!isNaN(num) && num !== "") {
                     td.innerText = formatIndianNumber(num);
               }
            });
         }

         // Run after page load
         document.addEventListener("DOMContentLoaded", convertTableToIndianFormat);
   }





  function formatNumberTableHead(n) {
        return n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

  function updateFooterForTrimGRNDataReport() {
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data();
    let head_total_qty = 0;
    let head_total_value=0;

    data.each(function (row) {        
        head_total_qty         += parseFloat(row['item_qty']?.toString().replace(/,/g, '')) || 0;
        head_total_value           += parseFloat(row['item_value']?.toString().replace(/,/g, '')) || 0;
    });
  
    $('#head_total_qty').text(formatNumberTableHead(head_total_qty));
    $('#head_total_value').text(formatNumberTableHead(head_total_value));
  }

    function updateFooterForTrimOutwardDataReport() {
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data();
    let head_total_qty = 0;
    let head_total_amount=0;

    data.each(function (row) { 
      
        head_total_qty       += parseFloat(row['item_qty']?.toString().replace(/,/g, '')) || 0;        
        head_total_amount    += parseFloat(row['item_value']?.toString().replace(/,/g, '')) || 0;
    });
  
    $('#head_total_qty').text(formatNumberTableHead(head_total_qty));
    $('#head_total_amount').text(formatNumberTableHead(head_total_amount));
  }

  function formatIndianNumber(x) {
    if (x === null || x === undefined || x === "" || isNaN(x)) {
        return "0.00";
    }

    let num = parseFloat(x).toFixed(2); 
    
    let parts = num.split(".");
    let integer = parts[0];
    let decimal = parts[1];
    
    let lastThree = integer.slice(-3);
    let otherNumbers = integer.slice(0, -3);

    if (otherNumbers !== "") {
        lastThree = "," + lastThree;
    }

    let indian = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;

    return indian + "." + decimal;
   }


  	function updateTotalsOpenSalesOrderDetailDashboard() {     
    const data = $('#dt').DataTable().rows({ search: 'applied' }).data(); //table.rows({ search: 'applied' }).data();

    let head_total_order_value = 0;
    let head_total_order_qty = 0;
    let head_total_Min_qty = 0;
    let head_total_cut_qty = 0;
    let head_total_production_qty = 0;
    let head_total_prod_bal_qty = 0;
    let head_total_prod_min = 0;
    let head_total_reject_qty = 0;
    let head_total_packing_qty = 0;
    let head_total_ship_qty = 0;
    let head_total_ship_min = 0;
    let head_total_excess_cut_qty = 0;
    let head_total_bal_ship_qty = 0;
    let head_total_bal_ship_min = 0;
    let head_total_bal_prod = 0;
    let head_total_bal_prod_min = 0;
    let head_total_short_close_qty = 0;
    let head_total_bal_qty_prod_actual = 0;
    let head_total_bal_min_produced_actual = 0;
    let head_total_bal_min_prod_actual = 0;
    let head_total_cmohp_value = 0;


    // Loop through visible rows
    data.each(function (row) {  
      
        // Adjust column indexes as per your table structure
        head_total_order_value         += parseFloat(row[10]?.toString().replace(/,/g, '')) || 0;
        head_total_order_qty           += parseFloat(row[11]?.toString().replace(/,/g, '')) || 0;
        head_total_Min_qty             += parseFloat(row[12]?.toString().replace(/,/g, '')) || 0;
        head_total_cut_qty             += parseFloat(row[13]?.toString().replace(/,/g, '')) || 0;
        head_total_production_qty      += parseFloat(row[14]?.toString().replace(/,/g, '')) || 0;
        head_total_prod_bal_qty        += parseFloat(row[15]?.toString().replace(/,/g, '')) || 0;
        head_total_prod_min            += parseFloat(row[16]?.toString().replace(/,/g, '')) || 0;
        head_total_reject_qty          += parseFloat(row[17]?.toString().replace(/,/g, '')) || 0;
        head_total_packing_qty         += parseFloat(row[18]?.toString().replace(/,/g, '')) || 0;
        head_total_ship_qty            += parseFloat(row[19]?.toString().replace(/,/g, '')) || 0;
        head_total_ship_min            += parseFloat(row[20]?.toString().replace(/,/g, '')) || 0;
        head_total_excess_cut_qty      += parseFloat(row[21]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_ship_qty        += parseFloat(row[22]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_ship_min        += parseFloat(row[23]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_prod            += parseFloat(row[24]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_prod_min        += parseFloat(row[25]?.toString().replace(/,/g, '')) || 0;
        head_total_short_close_qty     += parseFloat(row[26]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_qty_prod_actual += parseFloat(row[27]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_min_produced_actual += parseFloat(row[28]?.toString().replace(/,/g, '')) || 0;
        head_total_bal_min_prod_actual += parseFloat(row[29]?.toString().replace(/,/g, '')) || 0;
        head_total_cmohp_value         += parseFloat(row[31]?.toString().replace(/,/g, '')) || 0;

    });

    // Format number with commas and 2 decimals
    function formatNum(n) {
        return n.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Set totals in header/footer
    $('#head_total_order_value').text(formatNum(head_total_order_value));
    $('#head_total_order_qty').text(formatNum(head_total_order_qty));
    $('#head_total_Min_qty').text(formatNum(head_total_Min_qty));
    $('#head_total_cut_qty').text(formatNum(head_total_cut_qty));
    $('#head_total_production_qty').text(formatNum(head_total_production_qty));
    $('#head_total_prod_bal_qty').text(formatNum(head_total_prod_bal_qty));
    $('#head_total_prod_min').text(formatNum(head_total_prod_min));
    $('#head_total_reject_qty').text(formatNum(head_total_reject_qty));
    $('#head_total_packing_qty').text(formatNum(head_total_packing_qty));
    $('#head_total_ship_qty').text(formatNum(head_total_ship_qty));
    $('#head_total_ship_min').text(formatNum(head_total_ship_min));    
    $('#head_total_excess_cut_qty').text(formatNum(head_total_excess_cut_qty));
    $('#head_total_bal_ship_qty').text(formatNum(head_total_bal_ship_qty));  
    $('#head_total_bal_ship_min').text(formatNum(head_total_bal_ship_min));
    $('#head_total_bal_prod').text(formatNum(head_total_bal_prod));    
    $('#head_total_bal_prod_min').text(formatNum(head_total_bal_prod_min));
    $('#head_total_short_close_qty').text(formatNum(head_total_short_close_qty));
    $('#head_total_bal_qty_prod_actual').text(formatNum(head_total_bal_qty_prod_actual));
    $('#head_total_bal_min_produced_actual').text(formatNum(head_total_bal_min_produced_actual));
    $('#head_total_bal_min_prod_actual').text(formatNum(head_total_bal_min_prod_actual));    
    $('#head_total_cmohp_value').text(formatNum(head_total_cmohp_value));
   }

 