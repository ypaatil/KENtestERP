$(document).ready(function() {
    // Sort table data on header click
    $(document).on("click", "table thead tr th:not(.no-sort)", function() {
        let table = $(this).parents("table");
        let index = $(this).index();
        let rows = table.find("tbody tr").toArray().sort(TableComparer(index));
        let dir = $(this).hasClass("sort-asc") ? "desc" : "asc";

        // Reverse rows if sorting in descending order
        if (dir === "desc") {
            rows.reverse();
        }

        // Append rows to table body in sorted order
        table.find("tbody").append(rows);

        // Update sort classes
        table.find("thead tr th").removeClass("sort-asc sort-desc");
        $(this).removeClass("sort-asc sort-desc").addClass("sort-" + dir);
    });
});

// Function to compare table cell values
function TableComparer(index) {
    return function (a, b) {
        let val_a = TableCellValue(a, index).trim();
        let val_b = TableCellValue(b, index).trim();

        // Remove commas for numeric comparison
        val_a = val_a.replace(/,/g, '');
        val_b = val_b.replace(/,/g, '');

        // Determine type and compare accordingly
        if ($.isNumeric(val_a) && $.isNumeric(val_b)) {
            return parseFloat(val_a) - parseFloat(val_b);
        } else if (isDate(val_a) && isDate(val_b)) {
            return new Date(val_a) - new Date(val_b);
        } else {
            return val_a.localeCompare(val_b);
        }
    };
}

// Function to get table cell value
function TableCellValue(row, index) {
    return $(row).children("td").eq(index).text();
}

// Function to check if a value is a valid date
function isDate(val) {
    let d = new Date(val);
    return !isNaN(d.getTime());
}
