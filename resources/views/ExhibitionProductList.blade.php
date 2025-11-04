<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <style> 
        @import 'https://fonts.googleapis.com/css?family=Open+Sans:600,700';

        * {font-family: 'Open Sans', sans-serif;}
        
        .rwd-table {
          margin: auto;
          min-width: 300px;
          max-width: 100%;
          border-collapse: collapse;
        }
        
        .rwd-table tr:first-child {
          border-top: none;
          background: #428bca;
          color: #fff;
        }
        
        .rwd-table tr {
          border-top: 1px solid #ddd;
          border-bottom: 1px solid #ddd;
        }
        
        .rwd-table tr:nth-child(odd):not(:first-child) {
          background-color: #ebf3f9;
        }
        
        .rwd-table th {
          display: none;
        }
        
        .rwd-table td {
          display: block;
        }
        
        .rwd-table td:first-child {
          margin-top: .5em;
        }
        
        .rwd-table td:last-child {
          margin-bottom: .5em;
        }
        
        .rwd-table td:before {
          content: attr(data-th) ": ";
          font-weight: bold;
          width: 120px;
          display: inline-block;
          color: #000;
        }
        
        .rwd-table th,
        .rwd-table td {
          text-align: left;
        }
        
        .rwd-table {
          color: #333;
          border-radius: .4em;
          overflow: hidden;
        }
        
        .rwd-table tr {
          border-color: #bfbfbf;
        }
        
        .rwd-table th,
        .rwd-table td {
          padding: .5em 1em;
        }
        @media screen and (max-width: 601px) {
          .rwd-table tr:nth-child(2) {
            border-top: none;
          }
        }
        @media screen and (min-width: 600px) {
          .rwd-table tr:hover:not(:first-child) {
            background-color: #d8e7f3;
          }
          .rwd-table td:before {
            display: none;
          }
          .rwd-table th,
          .rwd-table td {
            display: table-cell;
            padding: .25em .5em;
          }
          .rwd-table th:first-child,
          .rwd-table td:first-child {
            padding-left: 0;
          }
          .rwd-table th:last-child,
          .rwd-table td:last-child {
            padding-right: 0;
          }
          .rwd-table th,
          .rwd-table td {
            padding: 0.1em !important;
          }
        }
    </style>
</head>
<body>
    <section>
    <div class="col-md-12 m-5">
        <div class="heading" style="text-align: center;"><h1><b>Exhibition Product List</b></h1></div>
        <div class="table-responsive">
            <table class="rwd-table" id="productTable">
                <thead>
                    <tr>
                        <th nowrap class="text-center">Sr No</th>
                        <th nowrap class="text-center">Type</th>
                        <th nowrap class="text-center">Sort No</th>
                        <th nowrap class="text-center">End Use</th> 
                        <th nowrap class="text-center">Quality</th>
                        <th nowrap>Width</th>
                        <th nowrap class="text-center">Width Range</th>
                        <th nowrap class="text-center">OT/OL</th>
                        <th nowrap class="text-center">Weave</th>
                        <th nowrap class="text-center">Weave Category</th>
                        <th nowrap class="text-center">GSM</th>
                        <th nowrap class="text-center">GSM Range</th>
                        <th nowrap class="text-center">Content</th>
                        <th nowrap class="text-center">Content Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th nowrap class="text-center">Image</th>
                    </tr>
                </thead>
                <tbody> 
                    @php 
                        $sr_no = 1;  
                    @endphp
                    @foreach($productList as $rows) 
                    <tr>
                        <td>{{ $sr_no++ }}</td>
                        <td>{{$rows->type_name}}</td>
                        <td>{{$rows->sort_no }}</td>
                        <td>{{$rows->end_use_name}}</td>
                        <td>{{$rows->quality }}</td>
                        <td>{{$rows->width }}</td>
                        <td>{{$rows->width_range}}</td>
                        <td>{{$rows->OT_OL }}</td>
                        <td>{{$rows->weave }}</td>
                        <td>{{$rows->weave_range}}</td>
                        <td>{{$rows->gsm }}</td>
                        <td>{{$rows->gsm_range}}</td>
                        <td>{{$rows->content }}</td>
                        <td>{{$rows->content_range}}</td>
                        <td>{{$rows->rate }}</td>
                        <td>{{$rows->quantity }}</td>
                        <td nowrap>{{ asset('uploads/Exhibition/' . $rows->attachment) }}</td>
                    </tr> 
                    @endforeach  
                </tbody>
            </table>
        </div>
    </div>
    </section>
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            dom: 'Bfrtip', // This enables the buttons
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>

</body>
</html>
