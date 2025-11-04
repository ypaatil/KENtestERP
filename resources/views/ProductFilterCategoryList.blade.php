<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Filter List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <style> 
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #F5F5FA; 
            height: 100vh;
            overflow-y: auto; 
        }

        .container {
            padding: 15%;
            width: 100%;
            max-width: 90vw;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        table thead {
            background: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        table thead th {
            padding: 10px;
            text-align: left;
        }

        table tbody tr:nth-child(odd) {
            background: #f9f9f9;
        }

        table tbody tr:hover {
            background: #e0f7fa;
        }

        table tbody td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table tbody td input[type="file"] {
            font-size: 0.9em;
        }

        .btn {
            padding: 5px 10px;
            font-size: 0.9em; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-outline-secondary {
            background: #f44336;
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background: #d32f2f;
        }

        .fas {
            margin-right: 5px;
        }

        .heading {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        } 
        
        .modal {
          display: none;
          position: fixed;
          z-index: 1000;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          overflow: auto;
          background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
          background-color: white;
          margin: 15% auto;
          padding: 20px;
          border-radius: 10px;
          width: 40%;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .close {
          float: right;
          font-size: 20px;
          font-weight: bold;
          color: #333;
          cursor: pointer;
        }
        
        .close:hover {
          color: red;
        }
        
        form label {
          display: block;
          margin: 10px 0 5px;
        }
        
        form input,select {
          width: 100%;
          padding: 8px;
          margin-bottom: 15px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }
        
        form button, td button {
          width: 100%;
          padding: 10px;
          background-color: #4CAF50;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }
        
        form button:hover {
          background-color: #45a049;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="heading">Product Filter List</div>
        <table id="datatable-buttons">
            <thead>
                <tr>
                    <th nowrap>Sr No</th> 
                    <th nowrap>Filter Name</th> 
                    <th nowrap>Filter Type</th> 
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $sr_no = 1; 
                @endphp
                @foreach($filterList as $row)
                <tr>
                    <td>{{ $sr_no++ }}</td>
                    <td>{{ $row->filter_name}}</td> 
                    <td>{{ $row->main_product_cat_name}}</td> 
                    <td>
                        <button type="submit" class="btn btn-outline-warning"  onclick="openModal({{$row->filter_id}}, {{$row->main_product_cat_id}}, '{{$row->filter_name}}', '{{$row->main_product_cat_name}}');" >Update</button>      
                    </td>
                    <td> 
                        <button class="btn btn-outline-secondary btn-sm delete"  
                                data-placement="top" id="DeleteRecord" 
                                data-token="{{ csrf_token() }}" 
                                data-id="{{ $row->filter_id }}"  
                                data-route="{{route('DeleteProductSubFilter', $row->filter_id )}}" title="Delete">
                             <i class="fas fa-trash">Delete</i>
                        </button> 
                    </td>
                </tr> 
                @endforeach
            </tbody>
        </table>
    </div> 
    <div id="formModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2 id="form_name">Master</h2>
          <form id="formData">
            <label for="filter_name">Name:</label>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="filter_id" name="filter_id" value="0"> 
            <input type="text" id="filter_name" name="filter_name" required> 
            <label>Category:</label> 
            <select id="main_product_cat_id" name="main_product_cat_id" class="form-control"> 
                <option value="0">--Select--</option>
                @foreach($productCatList as $row)
                    <option value="{{$row->main_product_cat_id}}">{{$row->main_product_cat_name}}</option>
                @endforeach
            </select>
            <button type="submit">Submit</button>
          </form>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    
    function openModal(filter_id,main_product_cat_id,filter_name,main_product_cat_name)
    {
        $('#formModal').fadeIn();
        $("#filter_id").val(filter_id);
        $("#filter_name").val(filter_name);
        $("#main_product_cat_id").val(main_product_cat_id);
        $("#form_name").text(main_product_cat_name +' Master');
    }
    
    $('.close').click(function ()
    {
        $('#formModal').fadeOut();
    });
     
     
    $(document).ready(function() 
    {
        $('#datatable-buttons').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });  
        
        $('#formData').submit(function (e) 
        {
            e.preventDefault(); // Prevent default form submission
    
            $.ajax({
              url: "{{ route('UpdateProductFilter') }}", // Laravel route for saving form data
              type: 'POST',
              data: {
                _token: $('input[name="_token"]').val(), // Include CSRF token
                filter_id: $('#filter_id').val(),
                filter_name: $('#filter_name').val(),
                main_product_cat_id: $('#main_product_cat_id').val(),
              },
              success: function (response) {
                alert('Form submitted successfully!');
                console.log(response);
                $('#formModal').fadeOut();
                $('#formData')[0].reset();
              },
              error: function (xhr, status, error) {
                alert('Error saving data. Please try again.');
                console.error(xhr.responseText);
              },
            });
        });
        
          // Close modal on clicking outside the content
        $(window).click(function (e) {
            if ($(e.target).is('#formModal')) {
              $('#formModal').fadeOut();
            }
        }); 
    });
    
    
   $(document).on('click','.delete',function(e) 
   {
      var Route = $(this).attr("data-route");
      var id = $(this).data("id");
      var token = $(this).data("token");
   
      if (confirm("Are you sure you want to Delete this Record?") == true) 
      {
            $.ajax({
                   url: Route,
                   type: "DELETE",
                    data: {
                    "id": id,
                    "_method": 'DELETE',
                     "_token": token,
                     },
                   
                   success: function(data)
                   {
                      location.reload();
                   }
          });
      }
   });
   
</script>
</body>
</html>
