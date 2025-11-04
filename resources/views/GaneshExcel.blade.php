<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Excel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <style>
    table.excel-table {
      border-collapse: collapse;
      table-layout: fixed;
      width: 100%;
      font-size: 14px;
    }

    .excel-table td:focus-within {
      outline: 2px solid #21a366;
      z-index: 10;
      position: relative;
    }

    .excel-table th,
    .excel-table td {
      border: 1px solid #d1d5db;
      width: 80px;
      height: 26px;
      padding: 0;
      position: relative;
      background-color: #fff;
    }

    .excel-table input {
      width: 100%;
      height: 100%;
      border: none;
      outline: none;
      padding-left: 4px;
      background: transparent;
      font-weight: normal;
    }

    .row-header,
    .col-header {
      background-color: #f3f4f6;
      text-align: center;
    }

    .drag-range {
      background-color: #d1fae5 !important;
    }

    .table-cell {
      border: 1px solid #2563eb !important;
    }

    .table-header {
      font-weight: bold;
      background-color: #dbeafe !important;
    }

    .table-stripe {
      background-color: #f0f9ff !important;
    }

    .excel-handle::after {
      content: '+';
      position: absolute;
      width: 10px;
      height: 10px;
      bottom: 1px;
      right: 2px;
      font-size: 12px;
      font-weight: bold;
      color: #2563eb;
    }

    .selected {
      background-color: #e0f2fe !important;
    }
  </style>
</head>

<body class="bg-white">

  <div class="p-4">
    <div class="mb-2 flex gap-2">
      
      <button onclick="exportExcel()" class="bg-green-600 text-white px-3 py-1 rounded">Export to Excel</button>
      <button onclick="enableTableDraw()" class="bg-blue-500 text-white px-3 py-1 rounded">Draw Table</button>
      <button onclick="clearTableStyle()" class="bg-red-500 text-white px-3 py-1 rounded">Clear Table</button>
    </div>

    <div class="h-[80vh] overflow-auto border border-gray-300">
      <table class="excel-table w-full" id="excelTable">
        <thead>
          <tr>
            <th class="row-header w-10"></th>
            <script>
              for (let i = 65; i <= 90; i++) {
                document.write(`<th class="col-header text-center">${String.fromCharCode(i)}</th>`);
              }
            </script>
          </tr>
        </thead>
        <tbody>
          <script>
            for (let row = 1; row <= 30; row++) {
              document.write('<tr>');
              document.write(`<th class="row-header text-center">${row}</th>`);
              for (let col = 0; col < 26; col++) {
                document.write(`<td><input type="text" data-row="${row}" data-col="${col}" /></td>`);
              }
              document.write('</tr>');
            }
          </script>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  
    let isDragging = false;
    let dragStart = null;
    let selectedRange = {};
    let allowTableDraw = false;

    function enableTableDraw() 
    {
      allowTableDraw = true;
    }

    $(document).on('mousedown', 'td input', function (e) 
    {
      if (e.which !== 1) return;

      isDragging = true;
      dragStart = $(this);
      selectedRange = {};
      if (allowTableDraw) {
        $('.drag-range').removeClass('drag-range');
      } else {
        dragStart.parent().addClass('excel-handle');
      }
    });

    $(document).on('mouseover', 'td input', function ()
    {
      if (!isDragging || !dragStart) return;

      if (allowTableDraw) {
        $('.drag-range').removeClass('drag-range');

        const row1 = parseInt(dragStart.data('row'));
        const col1 = parseInt(dragStart.data('col'));
        const row2 = parseInt($(this).data('row'));
        const col2 = parseInt($(this).data('col'));

        const rowMin = Math.min(row1, row2);
        const rowMax = Math.max(row1, row2);
        const colMin = Math.min(col1, col2);
        const colMax = Math.max(col1, col2);

        selectedRange = { rowMin, rowMax, colMin, colMax };

        for (let r = rowMin; r <= rowMax; r++) {
          for (let c = colMin; c <= colMax; c++) {
            $(`input[data-row="${r}"][data-col="${c}"]`).parent().addClass('drag-range');
          }
        }
      } else {
        const value = dragStart.val();
        $(this).val(value).parent().addClass('selected');
      }
    });

    $(document).on('mouseup', function () 
    {
      if (isDragging && allowTableDraw && selectedRange.rowMin !== undefined) {
        drawTable(selectedRange);
        allowTableDraw = false;
      }
      isDragging = false;
      dragStart?.parent().removeClass('excel-handle');
      dragStart = null;
      setTimeout(() => {
        $('.selected').removeClass('selected');
      }, 200);
    });

    function drawTable({ rowMin, rowMax, colMin, colMax }) 
    {
      let colCounter = 1;
      for (let r = rowMin; r <= rowMax; r++) {
        for (let c = colMin; c <= colMax; c++) {
          const $input = $(`input[data-row="${r}"][data-col="${c}"]`);
          const $td = $input.parent();
          $td.removeClass('drag-range').addClass('table-cell');

          if (r === rowMin) {
            $td.addClass('table-header');
            $input.val('Column' + colCounter++);
          } else if ((r - rowMin) % 2 === 1) {
            $td.addClass('table-stripe');
          }

          if (r === rowMax && c === colMax) {
            $td.addClass('excel-handle');
          }
        }
      }
    }

    function clearTableStyle() 
    {
      $('td').removeClass('table-cell table-header table-stripe excel-handle drag-range selected');
      $('input').val('');
    }


    function exportExcel()
    {
      const wb = XLSX.utils.book_new();
      const ws = [];
      for (let i = 1; i <= 30; i++) {
        const row = [];
        for (let j = 0; j < 26; j++) {
          row.push($(`input[data-row="${i}"][data-col="${j}"]`).val());
        }
        ws.push(row);
      }
      const worksheet = XLSX.utils.aoa_to_sheet(ws);
      XLSX.utils.book_append_sheet(wb, worksheet, "Sheet1");
      XLSX.writeFile(wb, "sheet.xlsx");
    }

    $('#fileInput').on('change', function (e) 
    {
      const reader = new FileReader();
      reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: "array" });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });
        rows.forEach((row, i) => {
          row.forEach((cell, j) => {
            const input = $(`input[data-row="${i + 1}"][data-col="${j}"]`);
            if (input.length) input.val(cell);
          });
        });
      };
      reader.readAsArrayBuffer(e.target.files[0]);
    });

    // Arrow key navigation
    $(document).on('keydown', 'input', function (e) 
    {
      const row = parseInt($(this).data('row'));
      const col = parseInt($(this).data('col'));
      let next;
      switch (e.key) {
        case "ArrowDown": next = $(`input[data-row="${row + 1}"][data-col="${col}"]`); break;
        case "ArrowUp": next = $(`input[data-row="${row - 1}"][data-col="${col}"]`); break;
        case "ArrowRight": next = $(`input[data-row="${row}"][data-col="${col + 1}"]`); break;
        case "ArrowLeft": next = $(`input[data-row="${row}"][data-col="${col - 1}"]`); break;
      }
      if (next && next.length) {
        e.preventDefault();
        next.focus();
      }
    });
  </script>

</body>

</html>