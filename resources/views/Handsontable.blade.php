<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Handsontable Excel Clone</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/handsontable@11.1.0/dist/handsontable.full.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 40px auto;
      max-width: 900px;
      background: #f3f6f9;
      color: #222;
    }

    h1 {
      font-weight: 600;
      font-size: 2.2rem;
      margin-bottom: 24px;
      color: #0b3d91;
      text-align: center;
      user-select: none;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .buttons button {
      background: #0b3d91;
      border: none;
      color: white;
      padding: 10px 18px;
      font-weight: 600;
      border-radius: 6px;
      box-shadow: 0 4px 8px rgb(11 61 145 / 0.3);
      cursor: pointer;
      transition: background 0.3s ease, box-shadow 0.3s ease;
      min-width: 110px;
      user-select: none;
    }

    .buttons button:hover {
      background: #164eae;
      box-shadow: 0 6px 12px rgb(11 61 145 / 0.45);
    }

    .buttons button:active {
      background: #0a3475;
      box-shadow: 0 2px 5px rgb(11 61 145 / 0.5);
      transform: translateY(1px);
    }

    #example {
      background: white;
      border-radius: 10px;
      box-shadow: 0 6px 12px rgb(0 0 0 / 0.1);
      padding: 15px;
      user-select: none;
      width:100%;
    }

    .handsontable .ht_clone_top th {
      background: #0b3d91 !important;
      color: white !important;
      font-weight: 700 !important;
      font-size: 0.9rem;
      border-right: 1px solid rgba(255, 255, 255, 0.3) !important;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .handsontable .wtBorder.current {
      border-color: #3a7bd5 !important;
      box-shadow: 0 0 8px #3a7bd5aa;
    }

    .handsontable td:hover {
      background-color: #e7f0ff !important;
      cursor: pointer;
    }

    .handsontable .ht_clone_left th {
      background: #f4f6f8 !important;
      color: #555 !important;
      font-weight: 600 !important;
      font-size: 0.85rem;
      user-select: none;
    }
  </style>
</head>
<body>
  <h1>Excel</h1>

  <div class="buttons">
    <button id="addRowBtn">Add Row</button>
    <button id="addColumnBtn">Add Column</button>
    <button id="removeRowBtn">Remove Row</button>
    <button id="removeColumnBtn">Remove Column</button>
  </div>

  <div id="example"></div>



  <script>
    const container = document.getElementById('example');
    const initialData = [
      ['Tesla', 'United States', 'Electric'],
      ['Nissan', 'Japan', 'Hybrid'],
      ['Toyota', 'Japan', 'Hybrid'],
        ['Tesla', 'United States', 'Electric'],
      ['Nissan', 'Japan', 'Hybrid'],
      ['Toyota', 'Japan', 'Hybrid'],
        ['Tesla', 'United States', 'Electric'],
      ['Nissan', 'Japan', 'Hybrid'],
      ['Toyota', 'Japan', 'Hybrid'],
        ['Tesla', 'United States', 'Electric'],
      ['Nissan', 'Japan', 'Hybrid'],
      ['Toyota', 'Japan', 'Hybrid'],
        ['Tesla', 'United States', 'Electric'],
      ['Nissan', 'Japan', 'Hybrid'],
      ['Toyota', 'Japan', 'Hybrid'],
    ];
    let columnHeaders = ['A', 'B', 'C'];

    // const hot = new Handsontable(container, {
    //   data: initialData,
    //   colHeaders: columnHeaders,
    //   rowHeaders: true,
    //   contextMenu: true,
    //   licenseKey: 'non-commercial-and-evaluation',
    //   stretchH: 'all',
    //   manualColumnResize: true,
    //   manualRowResize: true,
    //   height: 300,
    //   dropdownMenu: true,
    //   filters: true,
    // });
    
    
    const hot = new Handsontable(container, {
  data: initialData,
  colHeaders: columnHeaders,
  rowHeaders: true,
  contextMenu: true,
  licenseKey: 'non-commercial-and-evaluation',
  stretchH: 'all',
  manualColumnResize: true,
  manualRowResize: true,
   width: 1000,
  height: 320,
  dropdownMenu: true,
  filters: true,

  // ✅ Auto-save on cell change
  afterChange: function(changes, source) {
    if (source === 'edit') {
      const updatedData = hot.getData();
      
      alert(updatedData);

      fetch('/autosave-data', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ data: updatedData })
      })
      .then(response => response.json())
      .then(result => {
        console.log('Auto-saved:', result.message || 'Success');
      })
      .catch(error => {
        console.error('Auto-save failed:', error);
      });
    }
  }
});

    
    
    
    
    
    
    

    // Add Row
    document.getElementById('addRowBtn').addEventListener('click', () => {
      hot.alter('insert_row', hot.countRows());
    });

    // Add Column
    document.getElementById('addColumnBtn').addEventListener('click', () => {
      const colCount = hot.countCols();
      const newColName = prompt('Enter new column name:', `Column ${colCount + 1}`);
      if (newColName) {
        hot.alter('insert_col', colCount);
        columnHeaders.push(newColName);
        hot.updateSettings({ colHeaders: columnHeaders });
      }
    });

    // Remove Row
    document.getElementById('removeRowBtn').addEventListener('click', () => {
      const selected = hot.getSelectedLast();
      if (selected) {
        const startRow = selected[0];
        const endRow = selected[2];
        const fromRow = Math.min(startRow, endRow);
        const rowCount = Math.abs(endRow - startRow) + 1;
        hot.alter('remove_row', fromRow, rowCount);
      } else {
        alert('Please select a row to delete.');
      }
    });

    // Remove Column
    document.getElementById('removeColumnBtn').addEventListener('click', () => {
      const selected = hot.getSelectedLast();
      if (selected) {
        const startCol = selected[1];
        const endCol = selected[3];
        const fromCol = Math.min(startCol, endCol);
        const colCount = Math.abs(endCol - startCol) + 1;

        hot.alter('remove_col', fromCol, colCount);
        columnHeaders.splice(fromCol, colCount);
        hot.updateSettings({ colHeaders: columnHeaders });
      } else {
        alert('Please select a column to delete.');
      }
    });
  </script>
</body>
</html>
