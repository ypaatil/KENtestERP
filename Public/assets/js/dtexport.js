const commonExportOptions = {
         columns: ':visible',
         modifier: { search: 'applied' },
         format: {
           header: function (data, columnIdx) {
                           const div = document.createElement("div");
                           div.innerHTML = data;

                           // Remove any filter dropdown content
                           const filterMenus = div.querySelectorAll('.filter-menu');
                           filterMenus.forEach(el => el.remove());

                           const filterMenusicon = div.querySelectorAll('.filter-icon');
                           filterMenusicon.forEach(el => el.remove());
                           

                           // Remove icons if present
                           const icons = div.querySelectorAll('i, svg');
                           icons.forEach(el => el.remove());

                           return div.textContent.trim() || div.innerText.trim() || "";
                           }
         }
      };

      function unique(arr){ return [...new Set(arr)].sort(); }

        function buildSimpleFilter(selector, colIndex) {
          const table = $('#dt').DataTable();
            const visible = table.rows({ search: 'applied' }).data().toArray();
            const values = unique(visible.map(r=>r[colIndex]));
            let html = `
              <input type='text' class='filter-search' placeholder='Search...'>
              <label><input type='checkbox' class='select-all' checked> Select All</label>
              <div class='options'>`;
            values.forEach(v=>{
              html += `<label><input type='checkbox' class='opt' value='${v}' checked> ${v}</label>`;
            });
            html += `</div>
              <div class='filter-actions'>
                <button class='apply-btn'>Apply</button>
                <button class='clear-btn'>Clear</button>
              </div>`;
            $(selector).html(html);
      }

      
      function buildColouredFilter(selector, colIndex) {
        const table = $('#dt').DataTable();
        const visible = table.rows({ search: 'applied' }).data().toArray();        
        const statuses = unique(visible.map(r => r[colIndex]).filter(Boolean));
         console.log(statuses);
        let html = `
          <input type='text' class='filter-search' placeholder='Search...'>
          <label><input type='checkbox' class='select-all' checked> Select All</label>
          <div class='options'>`;

        statuses.forEach(s => {
            let colorClass = '';
 
            if (s.toLowerCase().trim() === 'completed') colorClass = 'completed-label';
            if (s.toLowerCase().trim() === 'approved') colorClass = 'approved-label';
            if (s.toLowerCase().trim() === 'not approved') colorClass = 'not-approved-label';
            if (s.toLowerCase().trim() === 'not done') colorClass = 'not-done-label';

            html += `
              <label>
                <input type='checkbox' class='opt' value='${s}' checked>
                <span class='${colorClass}'>${s}</span>
              </label>`;
        });

        html += `
          </div>
          <div class='filter-actions'>
            <button class='apply-btn'>Apply</button>
            <button class='clear-btn'>Clear</button>
          </div>`;
          
        $(selector).html(html);
    }

    function buildDateFilter(selector, colIndex) {
      const table = $('#dt').DataTable();
      const visible = table.rows({ search: 'applied' }).data().toArray();
      const dates = unique(visible.map(r => r[colIndex]).filter(Boolean));
      const tree = {};

      // Group dates by Year → Month → Day
      dates.forEach(d => {
         const dt = new Date(d);
         if (isNaN(dt)) return;
         const y = dt.getFullYear();
         const m = dt.toLocaleString('default', { month: 'short' });
         if (!tree[y]) tree[y] = {};
         if (!tree[y][m]) tree[y][m] = [];
         tree[y][m].push(d);
      });

      // Build HTML
      let html = `<input type='text' class='filter-search' placeholder='Search date...'>`;

      Object.keys(tree).sort((a,b)=>b-a).forEach(y => {
         html += `
            <div class='year-block'>
            <div class='tree-line'>
               <span class='tree-toggle' data-target='year-${colIndex}-${y}'>+</span>
               <label><input type='checkbox' class='year-check' data-col='${colIndex}' data-year='${y}' checked> ${y}</label>
            </div>
            <div class='month-list collapsed' id='year-${colIndex}-${y}'>`;
         Object.keys(tree[y]).forEach(m => {
            html += `
            <div class='month-block'>
               <div class='tree-line'>
                  <span class='tree-toggle' data-target='month-${colIndex}-${y}-${m}'>+</span>
                  <label><input type='checkbox' class='month-check' data-col='${colIndex}' data-year='${y}' data-month='${m}' checked> ${m}</label>
               </div>
               <div class='day-list collapsed' id='month-${colIndex}-${y}-${m}'>`;
            tree[y][m].forEach(d => {
            html += `<label><input type='checkbox' class='date-opt' data-col='${colIndex}' data-year='${y}' data-month='${m}' value='${d}' checked> ${d}</label>`;
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

      $(selector).html(html);
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

  // Get selected checkbox text (strip HTML)
  const vals = menu.find('.opt:checked').map((i, e) => {
    return $('<div>').html($(e).val()).text().trim();
  }).get();

  // If nothing selected — clear column filter
  if (vals.length === 0) {
    table.column(col).search('❌', true, false).draw();
    return;
  }

  // Escape regex special chars and create exact match pattern
  const escapedVals = vals.map(v => v.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'));
  const regex = '^(' + escapedVals.join('|') + ')$';

  // Custom filter — strip HTML before comparing
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    const cellHtml = data[col] || '';
    const cellText = $('<div>').html(cellHtml).text().trim();
    return vals.includes(cellText);
  });

  // Redraw table with filter
  table.draw();

  // Remove custom filter after use (to prevent stacking)
  $.fn.dataTable.ext.search.pop();
}

  function applySimpleFilter(col, menu){
    const table = $('#dt').DataTable();
    const vals = menu.find('.opt:checked').map((i,e)=>e.value).get();    
    table.column(col).search(vals.length ? vals.join('|') : '❌', true, false).draw();
  }

function applyDateFilter(col,menu){ 
    const table = $('#dt').DataTable();
    const vals = menu.find('.date-opt:checked').map((i,e)=>e.value).get();
    table.column(col).search(vals.length ? vals.join('|') : '❌', true, false).draw();
  }


  // === UI Events ===
  $('.filter-icon').on('click', function(e){
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