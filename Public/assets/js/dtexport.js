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
let savedFilterStates = {};     // { colIndex: [checked values] }
let savedVisibleValues = {};    // { colIndex: [visible values at apply time] }

function buildSimpleFilter(selector, colIndex) {
  const table = $('#dt').DataTable();
  const menu = $(selector);

  let valuesToShow;

  if (lastParentFilterCol === colIndex && savedVisibleValues[colIndex]) {
    // ✅ Reopen same parent filter: use saved visible options
    valuesToShow = savedVisibleValues[colIndex];
  } else {
    // ✅ Build from current filtered data
    const visibleData = table.column(colIndex, { search: 'applied' }).data().toArray();
    valuesToShow = [...new Set(visibleData.filter(v => v && v.trim() !== ''))].sort();
  }

  // Restore previously checked values
  const prevChecked = savedFilterStates[colIndex] || [];

  // Build HTML
  let html = `
    <input type='text' class='filter-search' placeholder='Search...'>
    <label><input type='checkbox' class='select-all'> <b>Select All</b></label>
    <div class='options'>
  `;

  valuesToShow.forEach(v => {
    const checked = prevChecked.length === 0 || prevChecked.includes(v) ? 'checked' : '';
    html += `<label><input type='checkbox' class='opt' value='${v}' ${checked}> ${v}</label>`;
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
    // Build new options based on currently filtered data
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




  function applySimpleFilter(col, menu) {
  const table = $('#dt').DataTable();
  const vals = menu.find('.opt:checked').map((i,e)=>e.value).get();    

  // Save checked values
  savedFilterStates[col] = vals;

  // Save visible options at this moment
  const visibleOptions = menu.find('.opt').map((i,e)=>e.value).get();
  savedVisibleValues[col] = visibleOptions;

  // Mark this as parent
  lastParentFilterCol = col;

  // Apply search
  table.column(col).search(vals.length ? vals.join('|') : '❌', true, false).draw();
}

    //******************************************** */


 /////////////////////////////
  

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