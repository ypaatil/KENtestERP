@extends('layouts.master') 
@section('content')   
<style>
    .text-right
    {
        text-align:right;
    }
   
    .form-popup-bg {
      position:absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      flex-direction: column;
      align-content: center;
      justify-content: center;
    }
    .form-popup-bg {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(94, 110, 141, 0.9);
      opacity: 0;
      visibility: hidden;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
      transition: opacity 0.3s 0s, visibility 0s 0.3s;
      overflow-y: auto;
      z-index: 10000;
    }
    .form-popup-bg.is-visible {
      opacity: 1;
      visibility: visible;
      -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
      -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
      transition: opacity 0.3s 0s, visibility 0s 0s;
    }
    .form-container {
        background-color: #2d3638;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        position:relative;
        padding: 40px;
        color: #fff;
    }
    .close-button {
      background:none;
      color: #fff;
      width: 40px;
      height: 40px;
      position: absolute;
      top: 0;
      right: 0;
      border: solid 1px #fff;
    }
    
    .form-popup-bg:before{
        content:'';
        background-color: #fff;
        opacity: .25;
        position:absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    /* Table Styles */
    #datatable-buttons {
        border-collapse: collapse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Header Styling */
    #datatable-buttons thead th {
        background-color: #007bff; /* Professional blue */
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 12px;
        border-bottom: 2px solid #0056b3;
    }

    /* Alternating Row Colors */
    #datatable-buttons tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }

    #datatable-buttons tbody tr:nth-child(even) {
        background-color: #ffffff;
    }

    /* Row Hover Effect */
    #datatable-buttons tbody tr:hover {
        background-color: #e2e6ea;
        cursor: pointer;
        transition: 0.3s;
    }

    /* Cell Padding */
    #datatable-buttons td {
        padding: 10px;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    /* Capacity Column Highlight */
    #datatable-buttons td:nth-child(9) {
        font-weight: bold;
        color: #28a745;
    }

    /* Days Column Styling */
    #datatable-buttons td:nth-child(10) {
        font-weight: bold;
        color: #dc3545;
    }
    
    td
    {
        vertical-align: middle;
    }
</style>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- jQuery (Required for Toastr) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">PPC WIP Report</h4>
         <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Transaction</a></li>
               <li class="breadcrumb-item active">PPC WIP Report</li>
            </ol>
         </div>
      </div>
   </div>
</div> 
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <table data-order='[[ 0, "desc" ]]' data-page-length='25' id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
               <thead>
                  <tr>
                     <th>Unit</th>
                     <th>Line No.</th>
                     <th>Line WIP</th>
                     <th>Cutting Stock</th> 
                     <th>Balance To Cut</th>
                     <th>Cutting <hr/>{{ \Carbon\Carbon::yesterday()->format('d-m-Y') }}</th>
                     <th>Production <hr/>{{ \Carbon\Carbon::yesterday()->format('d-m-Y') }}</th>
                     <th>Total</th>
                     <th>Capacity</th>
                     <th>Days</th> 
                  </tr>
               </thead>
               <tbody>
                @php
                    $temp = ''; // Store previous unit name
                @endphp
                @foreach($UnitMasterData as $row)
                    @php
                        // Calculate remaining work days
                        if((($row->po_qty - $row->cutting_stock) - $row->cutting - $row->production) > 0 && $row->capacity > 0)
                        {
                            $days = (($row->po_qty - $row->cutting_stock) - $row->cutting - $row->production) / $row->capacity;
                        }
                        else 
                        {
                            $days = 0;
                        }
            
                        // Determine if rowspan is needed
                        $rowspan = 0;
                        if($temp != $row->ac_short_name)
                        {
                            $rowspan = $row->total_line_count;
                        } 
                    @endphp
            
                    <tr class="tr_{{$row->Ac_code}}">
                        @if($temp != $row->ac_short_name)
                            <td class="td_{{$row->Ac_code}}" rowspan="{{$rowspan}}">{{$row->ac_short_name}}</td> 
                        @endif
                        <td>{{$row->line_name}}</td> 
                        <td>{{$row->line_wip}}</td> 
                        <td>{{$row->cutting_stock}}</td> 
                        <td>{{$row->po_qty - $row->cutting_stock}}</td> 
                        <td>{{$row->cutting}}</td>  
                        <td>{{$row->production}}</td> 
                        <td>{{($row->po_qty - $row->cutting_stock) - $row->cutting - $row->production}}</td>
                        <td onclick="openPopup(this, {{ json_encode($row->Ac_code) }}, {{ json_encode($row->line_id) }}, {{ json_encode($row->line_wip) }},
                                                  {{ json_encode($row->cutting_stock) }}, {{ json_encode($row->po_qty - $row->cutting_stock) }},
                                                  {{ json_encode($row->cutting) }}, {{ json_encode($row->production) }},
                                                  {{ json_encode(($row->po_qty - $row->cutting_stock) - $row->cutting - $row->production) }},
                                                  {{ json_encode($row->machine_count) }}, {{ json_encode($row->available_mins) }},
                                                  {{ json_encode($row->line_efficiency) }}, {{ json_encode($row->sam) }},
                                                  {{ json_encode($row->capacity) }});">
                            {{ $row->capacity }}
                        </td>
                        <td>{{round($days,2)}}</td> 
                    </tr> 
                
                        @php 
                            $temp = $row->ac_short_name; // Update the last processed unit
                        @endphp
                    @endforeach
                </tbody>
            </table>
         </div>
      </div>
   </div>
   <!-- end col -->
</div>

<div class="form-popup-bg">
  <div class="form-container">
    <button id="btnCloseForm" class="close-button">X</button>
    <h1>Capacity</h1>
    <form id="capacityForm">
      <div class="row">
        <div class="col-md-4 form-group">
          <label for="machine_count">No Of Machine <span style="color:red;">*</span></label>
          <input type="number" step="any" name="machine_count" class="form-control" value="0" id="machine_count" required onchange="CalculateProductionCapacity();">
        </div>
        <div class="col-md-4 form-group">
          <label for="available_mins">Total Available Mins <span style="color:red;">*</span></label>
          <input type="number" step="any" name="available_mins" class="form-control" value="480" id="available_mins" required onchange="CalculateProductionCapacity();">
        </div>
        <div class="col-md-4 form-group">
          <label for="line_efficiency">Line Efficiency (%) <span style="color:red;">*</span></label>
          <input type="number" step="any" name="line_efficiency" class="form-control" value="0" id="line_efficiency" required onchange="CalculateProductionCapacity();">
        </div>
        <div class="col-md-4 form-group">
          <label for="sam">SAM <span style="color:red;">*</span></label>
          <input type="number" step="any" name="sam" value="0" class="form-control" id="sam" required onchange="CalculateProductionCapacity();">
        </div>
        <div class="col-md-4 form-group">
          <label for="capacity">Production Capacity</label>
          <input type="number" step="any" name="capacity" class="form-control" value="0" id="capacity" readonly>
        </div>
      </div>
      <input type="hidden"  name="Ac_code"  value="" id="Ac_code" >
      <input type="hidden"  name="line_id"  value="" id="line_id" >
      <input type="hidden"  name="line_wip"  value="" id="line_wip" >
      <input type="hidden"  name="cutting_stock"  value="" id="cutting_stock" >
      <input type="hidden"  name="bal_to_cut"  value="" id="bal_to_cut" >
      <input type="hidden"  name="cutting"  value="" id="cutting" >
      <input type="hidden"  name="production"  value="" id="production" >
      <input type="hidden"  name="total"  value="" id="total" >
      <input type="hidden"  name="days"  value="" id="days" >
      <hr/>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

<!-- end row -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script type="text/javascript"> 
   

  // Open Popup
    function openPopup(row,Ac_code,line_id,line_wip,cutting_stock,bal_to_cut,cutting,production,total,machine_count,available_mins,line_efficiency,sam,capacity) 
    {
        event.preventDefault();
        $('.form-popup-bg').addClass('is-visible');
        $("#Ac_code").val(Ac_code);
        $("#line_id").val(line_id);
        $("#line_wip").val(line_wip);
        $("#cutting_stock").val(cutting_stock);
        $("#bal_to_cut").val(bal_to_cut);
        $("#cutting").val(cutting);
        $("#production").val(production);
        $("#total").val(total); 
        $("#machine_count").val(machine_count); 
        $("#available_mins").val(available_mins ? available_mins : 480); 
        $("#line_efficiency").val(line_efficiency); 
        $("#sam").val(sam); 
        $("#capacity").val(capacity); 
    }
    
    // Close Popup only when clicking the close button
    $(document).on('click', '#btnCloseForm', function(event) {
        event.preventDefault();
        $('.form-popup-bg').removeClass('is-visible');
    });
    
    // Prevent closing when clicking outside the form-container
    $(document).on('click', '.form-popup-bg', function(event) {
        if (!$(event.target).closest('.form-container').length) {
            event.stopPropagation(); // Prevents unwanted closing
        }
    });
    
    $('#capacityForm').on('submit', function(event) {
        event.preventDefault(); // Prevent form from refreshing
    
        var machine_count = $('#machine_count').val();
        var available_mins = $('#available_mins').val();
        var line_efficiency = $('#line_efficiency').val();
        var sam = $('#sam').val();
    
        // Check for empty fields
        if (machine_count == 0 || available_mins == 0 || line_efficiency == 0 || sam == 0) {
            alert('Please fill in all required fields!');
            return;
        }
    
        var formData = $(this).serialize(); // Serialize form data
    
        $.ajax({
            type: "GET",
            url: "{{ route('StorePPCWIPData') }}",
            data: formData,
            dataType: "json",
            success: function(data) {
                toastr.success("Data submitted successful!", "Success"); 
                $('.form-popup-bg').removeClass('is-visible');
                setTimeout(function() 
                {
                    location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                toastr.error("Something went wrong!", "Error");
            }
        });
    });
    
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
        
    function CalculateProductionCapacity()
    {
        var machine_count = $('#machine_count').val();
        var available_mins = $('#available_mins').val();
        var line_efficiency = $('#line_efficiency').val();
        var sam = $('#sam').val();
        var capacity=parseFloat((((parseFloat(machine_count) * parseFloat(available_mins) * parseFloat(line_efficiency))/sam)/100)).toFixed(0);
        $('#capacity').val(capacity);
    }
    

</script> 
@endsection