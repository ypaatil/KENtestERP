<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Excel-like Handsontable with Custom Date Filters</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
  <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    #toolbar {
      margin-bottom: 10px;
    }
    #toolbar button {
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      background: #dc3545;
      color: #fff;
      cursor: pointer;
      font-size: 14px;
    }
    #toolbar button:hover {
      background: #c82333;
    }
    #dateFilterPopup {
      display: none;
      position: absolute;
      background: #fff;
      border: 1px solid #ccc;
      padding: 12px;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 9999;
      width: 180px;
    }
    #dateFilterPopup button {
      display: block;
      width: 100%;
      margin: 4px 0;
      padding: 6px;
      border: none;
      border-radius: 4px;
      background: #f1f1f1;
      cursor: pointer;
    }
    #dateFilterPopup button:hover {
      background: #ddd;
    }
    #dateFilterPopup .footer {
      margin-top: 8px;
      text-align: right;
    }
    #dateFilterPopup .footer button {
      display: inline-block;
      width: auto;
      margin-left: 6px;
      padding: 6px 12px;
      border-radius: 4px;
    }
    #applyDateFilter { background: #007bff; color: #fff; }
    #cancelDateFilter { background: #6c757d; color: #fff; }
    #clearAllFilters { background: #dc3545; color: #fff; }
  </style>
</head>
<body>
  <!-- 🔹 Global Toolbar -->
  <div id="toolbar">
    <button id="globalClearFilters">Clear All Filters</button>
  </div>

  <div id="excelGrid"></div>

  <!-- Custom Date Filter Popup -->
  <div id="dateFilterPopup">
    <strong>Date Filter</strong>
    <button data-range="this_week">This Week</button>
    <button data-range="next_week">Next Week</button>
    <button data-range="last_week">Last Week</button>
    <button data-range="this_month">This Month</button>
    <button data-range="next_month">Next Month</button>
    <button data-range="last_month">Last Month</button>
    <div class="footer">
      <button id="applyDateFilter">OK</button>
      <button id="cancelDateFilter">Cancel</button>
      <button id="clearAllFilters">Clear Filters</button>
    </div>
  </div>

  <script>
    const data = [
      [1, "Alex Kumar", "Engineering", "2023-06-01", 850000],
      [2, "Priya Singh", "HR", "2022-12-12", 540000],
      [3, "Rohan Das", "Sales", "2024-04-07", 610000],
      [4, "Meera Iyer", "Finance", "2021-09-19", 780000],
      [5, "Arjun Patel", "Engineering", "2025-08-02", 900000],
      [6, "Sara Nair", "Marketing", "2025-09-15", 700000],
    ];

    const container = document.getElementById('excelGrid');
    const hot = new Handsontable(container, {
      data,
        rowHeaders: true,
    colHeaders: ["ID","Name","Department","Join Date","Salary"],
    dropdownMenu: true,
    filters: true,
    contextMenu: true,
    licenseKey: 'non-commercial-and-evaluation',
    autoRowSize: true,
    autoColumnSize: true,
    stretchH: 'all',    // stretch columns to fill width
    width: '100%',
    height: '100vh',    // fill viewport height

      columns: [
        { type: 'numeric' },
        { type: 'text' },
        { type: 'dropdown', source: ['Engineering','HR','Sales','Finance','Marketing'] },
        { type: 'date', dateFormat: 'YYYY-MM-DD', correctFormat: true },
        { type: 'numeric' }
      ]
    });

    const filtersPlugin = hot.getPlugin('filters');
    let selectedDateRange = null;

    function getDateRange(type) {
      const now = new Date();
      let start, end;
      const startOfWeek = (d) => {
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(d.setDate(diff));
      };
      const endOfWeek = (d) => {
        const first = startOfWeek(new Date(d));
        return new Date(first.getFullYear(), first.getMonth(), first.getDate() + 6);
      };
      switch(type) {
        case "this_week": start=startOfWeek(new Date()); end=endOfWeek(new Date()); break;
        case "next_week": start=startOfWeek(new Date()); start.setDate(start.getDate()+7); end=new Date(start); end.setDate(end.getDate()+6); break;
        case "last_week": start=startOfWeek(new Date()); start.setDate(start.getDate()-7); end=new Date(start); end.setDate(end.getDate()+6); break;
        case "this_month": start=new Date(now.getFullYear(),now.getMonth(),1); end=new Date(now.getFullYear(),now.getMonth()+1,0); break;
        case "next_month": start=new Date(now.getFullYear(),now.getMonth()+1,1); end=new Date(now.getFullYear(),now.getMonth()+2,0); break;
        case "last_month": start=new Date(now.getFullYear(),now.getMonth()-1,1); end=new Date(now.getFullYear(),now.getMonth(),0); break;
      }
      return [start,end];
    }

    // Show popup on right-click "Join Date" header
    container.addEventListener('contextmenu', function(e) {
      const th = e.target.closest('th');
      if (th && hot.getColHeader(th.cellIndex) === "Join Date") {
        e.preventDefault();
        const popup = document.getElementById('dateFilterPopup');
        popup.style.left = (e.pageX + 5) + "px";
        popup.style.top = (e.pageY + 5) + "px";
        popup.style.display = "block";
      }
    });

    document.querySelectorAll('#dateFilterPopup button[data-range]').forEach(btn => {
      btn.addEventListener('click', function() {
        selectedDateRange = getDateRange(this.dataset.range);
        document.querySelectorAll('#dateFilterPopup button[data-range]').forEach(b=>{
          b.style.background="#f1f1f1"; b.style.color="#000";
        });
        this.style.background="#007bff"; this.style.color="#fff";
      });
    });

    // Apply filter
    document.getElementById('applyDateFilter').addEventListener('click', function() {
      if (selectedDateRange) {
        filtersPlugin.removeConditions(3);
        filtersPlugin.addCondition(3,'between',[
          Handsontable.helper.stringify(selectedDateRange[0]).slice(0,10),
          Handsontable.helper.stringify(selectedDateRange[1]).slice(0,10)
        ]);
        filtersPlugin.filter();
      }
      document.getElementById('dateFilterPopup').style.display="none";
    });

    // Cancel button
    document.getElementById('cancelDateFilter').addEventListener('click', function() {
      document.getElementById('dateFilterPopup').style.display="none";
    });

    // Popup clear filters
    document.getElementById('clearAllFilters').addEventListener('click', function() {
      filtersPlugin.clearConditions();
      filtersPlugin.filter();
      selectedDateRange = null;
      document.querySelectorAll('#dateFilterPopup button[data-range]').forEach(b=>{
        b.style.background="#f1f1f1"; b.style.color="#000";
      });
      document.getElementById('dateFilterPopup').style.display="none";
    });

    // 🔹 Global clear filters button
    document.getElementById('globalClearFilters').addEventListener('click', function() {
      filtersPlugin.clearConditions();
      filtersPlugin.filter();
      selectedDateRange = null;
      document.querySelectorAll('#dateFilterPopup button[data-range]').forEach(b=>{
        b.style.background="#f1f1f1"; b.style.color="#000";
      });
    });
  </script>
</body>
</html>
