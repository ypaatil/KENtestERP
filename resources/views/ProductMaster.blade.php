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
          /*background-color: #f5f9fc;*/
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
        
         
        h1 {
          text-align: center;
          font-size: 2.4em;
          color: #f2f2f2;
        }
        .container {
          display: block;
          text-align: center;
        }
        h3 {
          display: inline-block;
          position: relative;
          text-align: center;
          font-size: 1.5em;
          color: #cecece;
        }
        h3:before {
          content: "\25C0";
          position: absolute;
          left: -50px;
          -webkit-animation: leftRight 2s linear infinite;
          animation: leftRight 2s linear infinite;
        }
        h3:after {
          content: "\25b6";
          position: absolute;
          right: -50px;
          -webkit-animation: leftRight 2s linear infinite reverse;
          animation: leftRight 2s linear infinite reverse;
        }
        @-webkit-keyframes leftRight {
          0%    { -webkit-transform: translateX(0)}
          25%   { -webkit-transform: translateX(-10px)}
          75%   { -webkit-transform: translateX(10px)}
          100%  { -webkit-transform: translateX(0)}
        }
        @keyframes leftRight {
          0%    { transform: translateX(0)}
          25%   { transform: translateX(-10px)}
          75%   { transform: translateX(10px)}
          100%  { transform: translateX(0)}
        }

        .fas {
            margin-right: 5px;
        }

        .heading {
            text-align: center;
            margin-bottom: -20px;
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
        
        form input,select  {
          width: 100%;
          padding: 8px;
          margin-bottom: 15px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }
        
        td input,select  {
          width: 100%;
          padding: 8px;
          margin-bottom: 15px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }
        
        form button {
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
    <section>
    <div class="col-md-12 m-2">
        <div class="heading"><b>Product List</b></div>
        <div class="table-responsive">
           <div class="row mt-5 upload-container">
                <h4>Upload Product Excel</h4>
                <form action="{{ route('ExhibitionProductImport') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <div class="d-flex align-items-center gap-2">
                        <div class="col-md-3">
                            <div class="input-group file-browser">
                                <input type="file" class="form-control upload-input" name="productfile" id="productfile" required>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-success upload-button" style="margin-top: -20px;">Import</button>
                        </div>
                        <div class="col-md-3">
                            <a href="/ExhibitionProductList" class="btn btn-warning"  style="margin-top: -20px;">Exhibition Product List</a>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-danger" onclick="DeleteAllExhibitionProducts();" >Delete All</button>
                        </div>
                    </div>
                </form>
            </div><hr/>
            <table class="rwd-table" id="datatable-buttons">
            <thead>
                <tr>
                    <th nowrap class="text-center"><a href="/ProductFilterCategoryList" class="btn btn-warning">*</a><br/>Sr No</th>
                    <th nowrap class="text-center"><br/>Image</th>
                    <th nowrap class="text-center"><br/>Preview</th>
                    <th nowrap><button class="btn btn-warning" onclick="SaveAll();" >Save All</button></th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(1, 'Type Master');">+</button><br/>Type</th>
                    <th nowrap class="text-center"><br/>Sort No</th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(7, 'End Use Master');">+</button><br/>End Use</th> 
                    <th nowrap class="text-center"><br/>Quality</th>
                    <th nowrap><br/>Width</th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(3, 'Width Master');">+</button><br/>Width Range</th>
                    <th nowrap class="text-center"><br/>OT/OL</th>
                    <th nowrap class="text-center"><br/>Weave</th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(4, 'Weave Master');">+</button><br/>Weave Category</th>
                    <th nowrap class="text-center"><br/>GSM</th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(5, 'GSM Master');">+</button><br/>GSM Range</th>
                    <th nowrap class="text-center"><br/>Content</th>
                    <th nowrap class="text-center"><button class="btn btn-warning" onclick="openModal(6, 'Content Master');">+</button><br/>Content Category</th>
                    <th><br/>Price</th>
                    <th><br/>Quantity</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody> 
                <tr>
                    <td>-</td>
                    <td nowrap>
                        <form action="{{route('ProductUploadImage')}}" method="POST" id="frmData" enctype="multipart/form-data" style="width: 150px;"> 
                            @csrf
                            <input type="hidden" name="ex_product_id" value="0" /> 
                            <input type="file" name="attachment" class="form-control" />
                            <button type="submit" class="btn btn-primary  btn-sm">Upload</button>      
                        </form>
                    </td>
                    <td nowrap></td>
                    <td class="text-center"> 
                        <button class="btn btn-outline-info btn-sm" onclick="SaveExProductDetails(this);"
                            <i class="fas fa-trash">Save</i>
                        </button> 
                    </td>
                    <td>
                        <select id="type" name="type" class="form-control" style="width: 105px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productTypeList as $row)
                                <option value="{{$row->filter_id}}" >{{$row->filter_name}}</option>
                            @endforeach
                        </select> 
                    </td>
                    <td>
                        <input type="hidden" name="ex_product_id" class="form-control" value="0" />
                        <input type="text" name="sort_no" class="form-control" value="" style="width: 105px;"  required/>
                    </td>
                    <td>
                        <select id="end_use" name="end_use" class="form-control" style="width: 180px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productEndUseList as $row)
                                <option value="{{$row->filter_id}}">{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="quality" class="form-control" value="" style="width: 200px;"  required/> 
                    </td>
                    <td>
                        <input type="number" step="any"  name="width" class="form-control" value="" onchange="setWidthRange(this);" style="width: 105px;"  required/> 
                    </td>
                    <td>
                        <select id="width_range" name="width_range" class="form-control" style="width: 110px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productWidthList as $row)
                                <option value="{{$row->filter_id}}">{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="OT_OL" class="form-control" value="" style="width: 75px;"  required/> 
                    </td>
                    <td>
                        <input type="text"  name="weave" class="form-control" value="" style="width: 200px;"  required/> 
                    </td>
                    <td>
                        <select id="weave_id" name="weave_id" class="form-control" style="width: 150px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productWeaveList as $row)
                                <option value="{{$row->filter_id}}">{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any"  name="gsm" class="form-control" value="" onchange="setGSMRange(this);" style="width: 105px;"  required/> 
                    </td>
                    <td>
                        <select id="gsm" name="gsm_range" class="form-control" style="width: 110px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productGSMList as $row)
                                <option value="{{$row->filter_id}}">{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"  name="content" class="form-control" value="" style="width: 200px;"  required/> 
                    </td>
                    <td>
                        <select id="content_id" name="content_id" class="form-control" style="width: 180px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productContentList as $row)
                                <option value="{{$row->filter_id}}">{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="any" name="rate" class="form-control" value="0"  style="width: 105px;" required/></td>
                    <td><input type="number" step="any" name="quantity"  class="form-control" value="0"  style="width: 105px;" required/></td>
                    <td> 
                        <button class="btn btn-outline-secondary btn-sm delete"><i class="fas fa-trash">Delete</i>
                        </button> 
                    </td>
                </tr> 
                @php 
                    $sr_no = 1;  
                @endphp
                @foreach($productList as $rows) 
                <tr>
                    <td>{{ $rows->sort_no }}</td>
                    <td nowrap style="width: 180px!important;">
                        <form action="{{route('ProductUploadImage')}}" method="POST" id="frmData" enctype="multipart/form-data" style="width: 150px;"> 
                            @csrf
                            <input type="hidden" name="ex_product_id" value="{{$rows->ex_product_id}}" /> 
                            <input type="file" name="attachment" class="form-control" />   
                        </form>
                    </td>
                    <td nowrap><img src="{{ $rows->attachment }}" width="60" height="60" /></td>
                    <td class="text-center"> 
                        <button class="btn btn-outline-info btn-sm save_sub" onclick="SaveExProductDetails(this);"
                            <i class="fas fa-trash">Save</i>
                        </button> 
                    </td>
                    <td> 
                        <select id="type" name="type" class="form-control" style="width: 105px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productTypeList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->type ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select> 
                    </td>
                    <td>
                        <input type="hidden" name="ex_product_id" class="form-control" value="{{ $rows->ex_product_id }}"/>
                        <input type="text" name="sort_no" class="form-control" value="{{ $rows->sort_no }}" style="width: 105px;" required />
                    </td>
                    <td>
                        <select id="end_use" name="end_use" class="form-control" style="width: 180px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productEndUseList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->end_use ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="quality" class="form-control" value="{{ $rows->quality }}" style="width: 200px;" required /> 
                    </td>
                    <td>
                        <input type="number" step="any"  name="width" class="form-control" value="{{ $rows->width }}" style="width: 100px;" onblur="setWidthRange(this);" required/> 
                    </td>
                    <td>
                        <select id="width_range" name="width_range" class="form-control" style="width: 110px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productWidthList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->width_range ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="OT_OL" class="form-control" value="{{ $rows->OT_OL }}" style="width: 75px;"  required/> 
                    </td>
                    <td>
                        <input type="text"  name="weave" class="form-control" value="{{ $rows->weave }}" style="width: 200px;"  required/> 
                    </td>
                    <td>
                        <select id="weave_id" name="weave_id" class="form-control" style="width: 200px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productWeaveList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->weave_id ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="any" name="gsm" class="form-control" value="{{ $rows->gsm }}" style="width: 105px;" onblur="setGSMRange(this);" required/> 
                    </td>
                    <td>
                        <select id="gsm_range" name="gsm_range" class="form-control" style="width: 110px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productGSMList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->gsm_range ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"  name="content" class="form-control" value="{{ $rows->content }}" style="width: 200px;"  required/> 
                    </td>
                    <td>
                        <select id="content_id" name="content_id" class="form-control" style="width: 180px;" required> 
                            <option value="0">--Select--</option>
                            @foreach($productContentList as $row)
                                <option value="{{$row->filter_id}}" {{ $row->filter_name == $rows->content_id ? 'selected="selected"' : '' }} >{{$row->filter_name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="any" name="rate" class="form-control" value="{{ $rows->rate }}"  style="width: 105px;" required/></td>
                    <td><input type="number" step="any" name="quantity"  class="form-control" value="{{ $rows->quantity }}" style="width: 105px;" required /></td>
                    <td> 
                        <button class="btn btn-outline-secondary btn-sm delete"  
                                data-placement="top" id="DeleteRecord" 
                                data-token="{{ csrf_token() }}" 
                                data-id="{{ $rows->ex_product_id }}"  
                                data-route="{{route('DeleteProduct', $rows->ex_product_id )}}" title="Delete">
                             <i class="fas fa-trash">Delete</i>
                        </button> 
                    </td>
                </tr> 
                @endforeach  
            </tbody>
        </table>
        </div>
    </div>
    </section>
    <div id="formModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2 id="form_name">Master</h2>
          <form id="formData">
            @csrf
            <label for="filter_name">Name:</label>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    
    function DeleteAllExhibitionProducts()
    {
        $.ajax({ 
              url: "{{ route('DeleteAllExhibitionProducts') }}", 
              type: 'GET',
              success: function (response) 
              {
                  location.reload(0);
              },
        });
    }
    
    function SaveAll()
    {
        $(".save_sub").trigger('click');
    }
        
    function setWidthRange(row) 
    {
        var width = parseFloat($(row).val()); // Get the input width value as a number
        var $select = $(row).closest('tr').find('select[name="width_range"]'); // Find the dropdown
        
        var selectedOption = null;
    
        $select.find('option').each(function () {
            var optionText = $(this).text().replace(/"/g, '').trim(); // Remove quotes and trim spaces
            var rangeMatch = optionText.match(/(\d+)\s*-\s*(\d+)/); // Extract numbers from "39 - 54"
    
            if (rangeMatch) {
                var min = parseFloat(rangeMatch[1]);
                var max = parseFloat(rangeMatch[2]);
    
                if (width >= min && width <= max) { 
                    selectedOption = $(this); // Select the first range that matches
                    return false; // Stop loop once the match is found
                }
            }
        });
    
        if (selectedOption) {
            selectedOption.prop('selected', true);
        } else {
            $select.prop('selectedIndex', -1); // Deselect if no match found
        }
    }


    function setGSMRange(row)
    {
        var gsm = parseFloat($(row).val()); // Get the input GSM value as a number
        var $select = $(row).closest('tr').find('select[name="gsm_range"]'); // Find the GSM dropdown
        
        var selectedOption = null;
    
        $select.find('option').each(function () {
            var optionText = $(this).text().trim(); // Get the text of the option
            var rangeMatch = optionText.match(/(\d+)\s*-\s*(\d+)/); // Extract numeric ranges like "40-100"
    
            if (rangeMatch) {
                var min = parseFloat(rangeMatch[1]); // Get min value (e.g., 40)
                var max = parseFloat(rangeMatch[2]); // Get max value (e.g., 100)
    
                if (gsm >= min && gsm <= max) { 
                    selectedOption = $(this); // If GSM falls within the range, select this option
                    return false; // Stop loop after finding the first match
                }
            }
        });
    
        if (selectedOption) {
            selectedOption.prop('selected', true);
        } else {
            $select.prop('selectedIndex', -1); // Deselect if no match found
        }
    }



    
    function openModal(obj,text)
    {
        $('#formModal').fadeIn();
        $("#filter_cat_id").val(obj);
        $("#form_name").text(text);
    }
    
    $('.close').click(function ()
    {
        $('#formModal').fadeOut();
     });
    
    function SaveExProductDetails(row)
    {
        // let isValid = true;

        // $("#datatable-buttons tbody tr").each(function () {
        //     $(this).find("input[required], select[required], textarea[required]").each(function () {
        //         if (!$(this).val()) {
        //             isValid = false;
        //             $(this).css("border", "2px solid red"); // Highlight the empty field
        //         } else {
        //             $(this).css("border", ""); // Reset border if valid
        //         }
        //     });
        // });

        // if (!isValid) {
        //     alert("Please fill in all required fields.");
        //     return true;
        // } else {
        //     alert("Form submitted successfully!");
        //     // You can proceed with form submission or any other action here
        // }
    
        var type = $(row).parent().parent('tr').find('select[name="type"]').val(); 
        var sort_no = $(row).parent().parent('tr').find('input[name="sort_no"]').val(); 
        var quality = $(row).parent().parent('tr').find('input[name="quality"]').val(); 
        var width = $(row).parent().parent('tr').find('input[name="width"]').val(); 
        var width_range = $(row).parent().parent('tr').find('select[name="width_range"]').val(); 
        var weave = $(row).parent().parent('tr').find('input[name="weave"]').val(); 
        var weave_id = $(row).parent().parent('tr').find('select[name="weave_id"]').val(); 
        var gsm = $(row).parent().parent('tr').find('input[name="gsm"]').val(); 
        var gsm_range = $(row).parent().parent('tr').find('select[name="gsm_range"]').val(); 
        var content = $(row).parent().parent('tr').find('input[name="content"]').val(); 
        var content_id = $(row).parent().parent('tr').find('select[name="content_id"]').val(); 
        var rate = $(row).parent().parent('tr').find('input[name="rate"]').val(); 
        var quantity = $(row).parent().parent('tr').find('input[name="quantity"]').val(); 
        var end_use = $(row).parent().parent('tr').find('select[name="end_use"]').val(); 
        var ex_product_id = $(row).parent().parent('tr').find('input[name="ex_product_id"]').val(); 
        var OT_OL = $(row).parent().parent('tr').find('input[name="OT_OL"]').val(); 
         
        $.ajax({ 
              url: "{{ route('UpdateExProductDetails') }}", // Laravel route for saving form data
              type: 'POST',
              data: {
                    _token: '{{ csrf_token() }}', // CSRF token for security
                    type: type,
                    ex_product_id: ex_product_id,
                    sort_no: sort_no,
                    quality: quality,
                    width: width,
                    width_range: width_range,
                    OT_OL: OT_OL,
                    weave: weave,
                    weave_id: weave_id,
                    gsm: gsm,
                    gsm_range: gsm_range,
                    content: content,
                    content_id: content_id,
                    rate: rate,
                    quantity: quantity,
                    end_use: end_use
              },
              success: function (response) {
                // alert('Form submitted successfully!');
                location.reload();
                console.log(response);
                $('#formModal').fadeOut();
                $('#formData')[0].reset();
                
                $(row).parent().parent('tr').find('form').submit(); 
              },
              error: function (xhr, status, error) {
                alert('Error saving data. Please try again.');
                console.error(xhr.responseText);
              },
       });
    }
    
    $(document).ready(function() 
    {
        $('#datatable-buttons').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ], 
        });
        $('#formData').submit(function (e) 
        {
            e.preventDefault(); // Prevent default form submission
    
            $.ajax({
              url: "{{ route('NewProductStore') }}", // Laravel route for saving form data
              type: 'POST',
              data: $('#formData').serialize(), 
              success: function (response) {
                alert('Form submitted successfully!');
                location.reload();
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
